<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of users (admin only).
     */
    public function index()
    {
        $users = User::with('roles')
            ->withSum('files as total_size', 'file_size')
            ->withCount('files')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('users.index', compact('users'));
    }

    /**
     * Update user role.
     */
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,user',
        ]);

        // Sync roles - remove old ones and assign new
        $user->syncRoles([$request->role]);

        return redirect()->route('users.index')
            ->with('success', 'Role pengguna "' . $user->name . '" berhasil diubah menjadi ' . ucfirst($request->role) . '!');
    }

    /**
     * Delete a user (admin only).
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'Anda tidak bisa menghapus akun sendiri!');
        }

        // Delete all user's files from storage
        foreach ($user->files as $file) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($file->file_path);
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Pengguna berhasil dihapus!');
    }
}
