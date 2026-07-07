<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.users.index', ['users' => User::with('role')->latest()->paginate(20), 'roles' => Role::all()]);
    }

    public function update(Request $request, User $user)
    {
        abort_if($user->id === $request->user()->id && ! $request->boolean('is_active'), 422, 'Akun sendiri tidak dapat dinonaktifkan.');
        $data = $request->validate(['role_id' => ['required', 'exists:roles,id'], 'is_active' => ['required', 'boolean']]);
        $user->update($data);

        return back()->with('success', 'Akun pengguna diperbarui.');
    }

    public function destroy(Request $request, User $user)
    {
        abort_if($user->id === $request->user()->id, 422, 'Akun sendiri tidak dapat dihapus.');
        $user->delete();

        return back()->with('success', 'Pengguna dihapus.');
    }
}
