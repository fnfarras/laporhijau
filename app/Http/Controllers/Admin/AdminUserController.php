<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class AdminUserController extends Controller
{
    /**
     * Daftar semua pengguna dengan filter & pagination.
     */
    public function index(Request $request): View
    {
        $query = User::with('roles')->latest();

        if ($request->filled('role')) {
            $query->role($request->role);
        }

        if ($request->filled('search')) {
            $query->where(fn($q) => $q
                ->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%')
            );
        }

        $users = $query->paginate(20)->withQueryString();
        $roles = Role::orderBy('name')->get();

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Ganti role pengguna.
     */
    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $request->validate(['role' => 'required|exists:roles,name']);

        // Admin tidak bisa downgrade diri sendiri
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat mengubah role akun sendiri.');
        }

        $user->syncRoles([$request->role]);

        return back()->with('success', "Role pengguna \"{$user->name}\" berhasil diubah menjadi {$request->role}.");
    }
}
