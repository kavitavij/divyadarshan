<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | DivyaDarshan</title>
    @vite(['resources/css/custom.css']) {{-- Load custom CSS --}}
</head>
<body>

<div class="login-container">
    <div class="login-box">
        <div class="logo">
            <img src="{{ asset('images/logo.png') }}" alt="DivyaDarshan Logo">
            <h2>Admin Login</h2>
        </div>

        @if($errors->any())
            <div class="error-msg">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf
            <label>Email</label>
            <input type="email" name="email" placeholder="Enter email" required>

            <label>Password</label>
            <input type="password" name="password" placeholder="Enter password" required>

            <div class="extras">
                <label><input type="checkbox"> Remember me</label>
                <a href="#">Forgot Password?</a>
            </div>

            <button type="submit">Log In</button>
        </form>
    </div>
</div>

</body>
</html>
