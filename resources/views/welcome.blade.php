<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Welcome | {{ config('app.name') }}</title>
    <style>
        /* Fullscreen background image */
        body, html {
            margin: 0; padding: 0; height: 100%;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen,
                Ubuntu, Cantarell, "Open Sans", "Helvetica Neue", sans-serif;
            background: url('/logo.png') no-repeat center center fixed;
            background-size: cover;
            color: white;
        }
        /* Login button */
        .login-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: rgba(0,0,0,0.6);
            border: none;
            padding: 10px 20px;
            color: white;
            font-weight: 600;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
            font-size: 1rem;
        }
        .login-btn:hover {
            background-color: rgba(0,0,0,0.8);
        }
        /* Optional: center text or welcome message */
        .welcome-text {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            text-shadow: 0 0 10px rgba(0,0,0,0.7);
            font-size: 3rem;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <a href="{{ route('login') }}" class="login-btn">Login</a>
    <a href="{{ route('register') }}" class="register-btn" style="position: fixed; top: 20px; right: 120px; background-color: rgba(0,0,0,0.6); color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; font-weight: 600;">Register</a>
   

    <div class="welcome-text">
        Welcome to {{ config('app.name') }}
    </div>
</body>
</html>
