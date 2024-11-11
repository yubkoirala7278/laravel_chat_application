<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Messenger</title>

    <!-- Styles -->
    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-left {
            text-align: left;
            margin-right: 50px;
        }

        .login-left h1 {
            font-size: 3rem;
            color: #1877f2;
            font-weight: bold;
        }

        .login-left p {
            font-size: 1.2rem;
            margin-top: -15px;
        }

        .login-right {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            width: 360px;
        }

        .login-right input {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #dddfe2;
            border-radius: 6px;
            font-size: 16px;
        }

        .login-right input:focus {
            outline: none;
            border-color: #1877f2;
        }

        .login-right button {
            background-color: #1877f2;
            color: white;
            padding: 12px;
            width: 100%;
            border: none;
            border-radius: 6px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
        }

        .login-right button:hover {
            background-color: #165ecb;
        }

        .forgot-password,
        .signup {
            text-align: center;
            margin-top: 20px;
        }

        .forgot-password a {
            text-decoration: none;
            color: #1877f2;
            font-size: 14px;
        }

        .signup a {
            background-color: #42b72a;
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: bold;
        }

        .signup a:hover {
            background-color: #36a420;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-left">
            <h1>Messenger</h1>
            <p>Connect with friends and the world around you on Messenger.</p>
        </div>
        <div class="login-right">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Email or phone number" class="@error('email') is-invalid @enderror">
                @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror

                <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Password" class="@error('password') is-invalid @enderror">
                @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror

                <button type="submit">{{ __('Login') }}</button>

                <div class="forgot-password">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
                    @endif
                </div>
            </form>

            <div class="signup">
                <a href="{{ route('register') }}">Create New Account</a>
            </div>
        </div>
    </div>
</body>
</html>
