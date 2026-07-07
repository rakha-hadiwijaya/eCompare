<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteProfileRequest;
use App\Http\Requests\ProfilePreferenceRequest;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit', ['user' => request()->user()]);
    }

    public function update(ProfileUpdateRequest $request)
    {
        $user = $request->user();
        $data = $request->validated();
        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }
        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }
        $user->fill($data);
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profil diperbarui.');
    }

    public function preference(ProfilePreferenceRequest $request)
    {
        $request->user()->preference()->updateOrCreate([], $request->validated());

        return back()->with('success', 'Preferensi disimpan.');
    }

    public function destroy(DeleteProfileRequest $request)
    {
        $user = $request->user();
        Auth::logout();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
