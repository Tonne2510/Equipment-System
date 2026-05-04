<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Equipment Management</title>
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .register-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            padding: 40px;
        }
        .register-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .register-header h1 {
            color: #333;
            font-size: 28px;
            margin: 0;
        }
        .register-header p {
            color: #666;
            font-size: 14px;
            margin: 5px 0 0 0;
        }
        .form-group {
            margin-bottom: 20px;
        }

        .form-group:last-child {
            margin-bottom: 0;
        }
        .form-group label {
            display: block;
            color: #333;
            font-weight: 500;
            margin-bottom: 8px;
            font-size: 14px;
        }
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: all 0.3s;
            box-sizing: border-box;
        }
        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .error-message {
            color: #e74c3c;
            font-size: 13px;
            margin-top: 5px;
        }
        .btn-register {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
        }
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .login-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }
        .login-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
        .captcha-container {
            margin-bottom: 20px;
            padding: 15px;
            background: #f9f9f9;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
        }
        .error-recaptcha {
            color: #e74c3c;
            font-size: 13px;
            margin-top: 5px;
            display: none;
        }
        .error-recaptcha.show {
            display: block;
        }
        .password-requirements {
            font-size: 12px;
            color: #666;
            margin-top: 8px;
            padding: 10px;
            background: #f5f5f5;
            border-radius: 4px;
            line-height: 1.6;
        }
        .password-requirements-item {
            margin: 4px 0;
        }
        .password-requirements-item.valid {
            color: #27ae60;
        }
        .password-requirements-item.invalid {
            color: #e74c3c;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <h1>Create Account</h1>
            <p>Equipment Management System</p>
        </div>

        @if ($errors->any())
            <div style="background: #ffe6e6; color: #c00; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                <strong>Registration Failed</strong>
                @foreach ($errors->all() as $error)
                    <p style="margin: 5px 0;">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf
            
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus>
                @error('name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required onkeyup="validatePassword()">
                <div class="password-requirements">
                    <div class="password-requirements-item invalid" id="req-length">✓ At least 12 characters</div>
                    <div class="password-requirements-item invalid" id="req-upper">✓ Uppercase letter (A-Z)</div>
                    <div class="password-requirements-item invalid" id="req-lower">✓ Lowercase letter (a-z)</div>
                    <div class="password-requirements-item invalid" id="req-number">✓ Number (0-9)</div>
                    <div class="password-requirements-item invalid" id="req-special">✓ Special character (@$!%*?&)</div>
                </div>
                @error('password')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>

            <div class="captcha-container">
                <div class="g-recaptcha" data-sitekey="{{ config('recaptcha.site_key') }}"></div>
                @error('g-recaptcha-response')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-register">Register</button>
        </form>

        <div class="login-link">
            Already have an account? <a href="{{ route('login') }}">Login here</a>
        </div>
    </div>

    <!-- reCAPTCHA v2 Script -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
        var formElement = document.querySelector('form');
        
        console.log('reCAPTCHA v2 initialization on register page');
        console.log('Site Key configured:', '{{ config('recaptcha.site_key') }}' ? 'Yes' : 'No');
        
        // Simply let the form submit normally with the reCAPTCHA checkbox
        // The g-recaptcha-response token is automatically added by Google
        
        function validatePassword() {
            const password = document.getElementById('password').value;
            
            // Check length
            const lengthValid = password.length >= 12;
            document.getElementById('req-length').className = lengthValid ? 'password-requirements-item valid' : 'password-requirements-item invalid';
            
            // Check uppercase
            const upperValid = /[A-Z]/.test(password);
            document.getElementById('req-upper').className = upperValid ? 'password-requirements-item valid' : 'password-requirements-item invalid';
            
            // Check lowercase
            const lowerValid = /[a-z]/.test(password);
            document.getElementById('req-lower').className = lowerValid ? 'password-requirements-item valid' : 'password-requirements-item invalid';
            
            // Check number
            const numberValid = /[0-9]/.test(password);
            document.getElementById('req-number').className = numberValid ? 'password-requirements-item valid' : 'password-requirements-item invalid';
            
            // Check special character
            const specialValid = /[@$!%*?&]/.test(password);
            document.getElementById('req-special').className = specialValid ? 'password-requirements-item valid' : 'password-requirements-item invalid';
        }
    </script>
</body>
</html>
