<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit', ['user' => auth()->user()]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore(auth()->id())],
            'phone' => 'nullable|string|max:20',
        ]);

        auth()->user()->update($validated);

        return redirect()->route('profile.edit')
            ->with('success', 'Thông tin hồ sơ đã được cập nhật!');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!\Hash::check($value, auth()->user()->password)) {
                        $fail('Mật khẩu hiện tại không đúng.');
                    }
                },
            ],
            'password' => 'required|min:8|confirmed|different:current_password',
        ]);

        auth()->user()->update([
            'password' => bcrypt($validated['password']),
        ]);

        return redirect()->route('profile.edit')
            ->with('success', 'Mật khẩu đã được thay đổi thành công!');
    }
}
