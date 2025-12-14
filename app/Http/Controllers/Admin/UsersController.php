<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UsersController extends Controller
{
    public function index(Request $request): Response
    {
        $role = $request->string('role')->toString();
        $search = $request->string('search')->toString();

        $users = User::query()
            ->when($role, fn ($q) => $q->where('role', $role))
            ->when($search, fn ($q) => $q
                ->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', '%'.$search.'%')
                        ->orWhere('email', 'like', '%'.$search.'%');
                }))
            ->orderByRaw("FIELD(role, 'admin', 'creator', 'reader')")
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString(['role' => $role, 'search' => $search])
            ->through(fn (User $user) => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'is_locked' => $user->is_locked,
                'created_at' => $user->created_at,
                'email_verified_at' => $user->email_verified_at,
            ]);

        $counts = [
            'total' => User::count(),
            'admin' => User::where('role', 'admin')->count(),
            'creator' => User::where('role', 'creator')->count(),
            'reader' => User::where('role', 'reader')->count(),
            'locked' => User::where('is_locked', true)->count(),
        ];

        return Inertia::render('admin/Users/Index', [
            'users' => $users,
            'counts' => $counts,
            'filters' => [
                'role' => $role,
                'search' => $search,
            ],
        ]);
    }

    public function show(string $user): Response
    {
        return Inertia::render('admin/Users/Show', [
            'userId' => $user,
        ]);
    }

    public function edit(string $user): Response
    {
        $record = User::findOrFail($user, ['id', 'name', 'email', 'role', 'is_locked', 'created_at', 'email_verified_at']);

        return Inertia::render('admin/Users/Edit', [
            'user' => $record,
        ]);
    }

    public function update(Request $request, string $user): RedirectResponse
    {
        $record = User::findOrFail($user);

        $request->validate([
            'lock' => ['required', 'boolean'],
        ]);

        $record->update(['is_locked' => $request->boolean('lock')]);

        return redirect()->route('admin.users.edit', $user)->with('success', 'Compte mis à jour');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => 'admin',
            'is_locked' => false,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Administrateur créé');
    }
}
