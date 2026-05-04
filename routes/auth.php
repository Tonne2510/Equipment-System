<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\RecaptchaService;

// Simple Auth routes without Breeze/Fortify
Route::middleware('guest')->group(function () {
    Route::get('login', function () {
        return view('auth.login');
    })->name('login');

    Route::post('login', function (Request $request) {
        $recaptcha = new RecaptchaService();

        // Validate form inputs and reCAPTCHA
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'g-recaptcha-response' => 'required',
        ], [
            'g-recaptcha-response.required' => 'reCAPTCHA verification is required.',
        ]);

        // Verify reCAPTCHA response
        if (!$recaptcha->verify($request->input('g-recaptcha-response'))) {
            return back()->withErrors([
                'g-recaptcha-response' => 'reCAPTCHA verification failed. Please try again.',
            ])->onlyInput('email');
        }

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = auth()->user();
            // Check if account is disabled
            if ($user->status != 1) {
                auth()->logout();
                return back()->withErrors([
                    'email' => 'Tài khoản của bạn đã bị vô hiệu hóa. Vui lòng liên hệ quản trị viên.',
                ])->onlyInput('email');
            }
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    });

    Route::get('register', function () {
        return view('auth.register');
    })->name('register');

    Route::post('register', function (Request $request) {
        $recaptcha = new RecaptchaService();

        // Validate reCAPTCHA first
        $request->validate([
            'g-recaptcha-response' => 'required',
        ], [
            'g-recaptcha-response.required' => 'reCAPTCHA verification is required.',
        ]);

        // Verify reCAPTCHA response
        if (!$recaptcha->verify($request->input('g-recaptcha-response'))) {
            return back()->withErrors([
                'g-recaptcha-response' => 'reCAPTCHA verification failed. Please try again.',
            ])->onlyInput('name', 'email');
        }

        // Validate registration data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => [
                'required',
                'string',
                'min:12',
                'regex:/[a-z]/', // lowercase
                'regex:/[A-Z]/', // uppercase
                'regex:/[0-9]/', // number
                'regex:/[@$!%*?&]/', // special character
                'confirmed',
            ],
        ], [
            'password.min' => 'Password must be at least 12 characters long.',
            'password.regex' => 'Password must contain uppercase letters, lowercase letters, numbers, and special characters (@$!%*?&).',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        $user = \App\Models\User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role_id' => 3, // Default: employee role
            'status' => 1,
        ]);

        Auth::login($user);
        return redirect('/');
    });
});

Route::middleware('auth')->group(function () {
    Route::post('logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});


