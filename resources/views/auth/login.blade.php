<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Equipment Management</title>
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            padding: 40px;
        }
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-header h1 {
            color: #333;
            font-size: 28px;
            margin: 0;
        }
        .login-header p {
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
        .btn-login {
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
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .register-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }
        .register-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        .register-link a:hover {
            text-decoration: underline;
        }
        .test-credentials {
            background: #f0f4ff;
            border-left: 4px solid #667eea;
            padding: 12px;
            margin-top: 20px;
            border-radius: 4px;
            font-size: 12px;
            color: #333;
        }
        .test-credentials p {
            margin: 5px 0;
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
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>Equipment System</h1>
            <p>Equipment Management & Borrowing</p>
        </div>

        @if ($errors->any())
            <div style="background: #ffe6e6; color: #c00; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                <strong>Login Failed</strong>
                @foreach ($errors->all() as $error)
                    <p style="margin: 5px 0;">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                @error('password')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="captcha-container">
                <div class="g-recaptcha" data-sitekey="{{ config('recaptcha.site_key') }}"></div>
                @error('g-recaptcha-response')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-login">Login</button>
        </form>

        <div class="register-link">
            Don't have an account? <a href="{{ route('register') }}">Register here</a>
        </div>
    </div>

    <!-- reCAPTCHA v2 Script -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
        var formElement = document.querySelector('form');
        
        console.log('reCAPTCHA v2 initialization on login page');
        console.log('Site Key configured:', '{{ config('recaptcha.site_key') }}' ? 'Yes' : 'No');
        
        // Simply let the form submit normally with the reCAPTCHA checkbox
        // The g-recaptcha-response token is automatically added by Google
    </script>
</body>
</html>
