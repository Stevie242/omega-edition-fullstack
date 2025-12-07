<?php

namespace App\Http\Requests\Creator;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCreatorProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isCreator() || $this->user()?->isAdmin();
    }

    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($this->user()?->id),
            ],
            'first_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'display_name' => ['nullable', 'string', 'max:150'],
            'age' => ['nullable', 'integer', 'min:0', 'max:120'],
            'gender' => ['nullable', 'string', 'max:100'],
            'nationality' => ['nullable', 'string', 'max:150'],
            'location' => ['nullable', 'string', 'max:150'],
            'languages' => ['nullable', 'string', 'max:150'],
            'headline' => ['nullable', 'string', 'max:200'],
            'bio' => ['nullable', 'string'],
            'signature_style' => ['nullable', 'string', 'max:200'],
            'favorite_formats' => ['nullable', 'string', 'max:200'],
            'portfolio_links' => ['nullable', 'string'],
            'moodboard' => ['nullable', 'string'],
            'website' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'availability' => ['nullable', 'string', 'max:150'],
            'avatar_url' => ['nullable', 'string', 'max:255'],
            'cover_url' => ['nullable', 'string', 'max:255'],
            'avatar_file' => ['nullable', 'image', 'max:2048'],
            'cover_file' => ['nullable', 'image', 'max:4096'],
        ];
    }
}
