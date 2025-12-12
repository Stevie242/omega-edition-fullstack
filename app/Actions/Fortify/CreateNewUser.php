<?php

namespace App\Actions\Fortify;

use App\Models\ProfileCreator;
use App\Models\ReaderProfile;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['nullable', 'string', 'max:255'],
            'first_name' => ['required_if:role,reader,creator', 'string', 'max:255'],
            'last_name' => ['required_if:role,reader,creator', 'string', 'max:255'],
            'stage_name' => ['required_if:role,creator', 'string', 'max:255'],
            'birthdate' => ['nullable', 'date', 'before_or_equal:today'],
            'avatar_url' => ['nullable', 'string', 'max:2048'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
            'role' => ['nullable', 'string', Rule::in(['reader', 'creator'])],
        ])->validate();

        return DB::transaction(function () use ($input) {
            $firstName = $input['first_name'] ?? null;
            $lastName = $input['last_name'] ?? null;
            $displayName = $input['stage_name'] ?? $input['name'] ?? trim("{$firstName} {$lastName}");
            $avatarUrl = $input['avatar_url'] ?? null;

            $user = User::create([
                'name' => $displayName ?: trim("{$firstName} {$lastName}") ?: $input['email'],
                'email' => $input['email'],
                'password' => $input['password'],
                'role' => $input['role'] ?? 'reader',
            ]);

            if (($input['role'] ?? 'reader') === 'creator') {
                ProfileCreator::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'display_name' => $displayName,
                        'is_completed' => false,
                    ],
                );
            }

            if (($input['role'] ?? 'reader') === 'reader') {
                $birthdate = isset($input['birthdate']) ? new \DateTime($input['birthdate']) : null;
                $age = $birthdate ? $birthdate->diff(new \DateTime('now'))->y : null;

                ReaderProfile::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'avatar_url' => $avatarUrl,
                        'birthdate' => $birthdate,
                        'age' => $age,
                        'is_completed' => false,
                    ],
                );
            }

            return $user;
        });
    }
}
