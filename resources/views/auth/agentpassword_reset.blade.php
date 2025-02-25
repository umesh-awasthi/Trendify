<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
    <div class="container">
        <h1>Reset Your Password</h1>
            <form action="{{ route('agent.password.update') }}" method="POST">

            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div>
                <label for="email">Email</label>
                <input type="email" name="email" required >
            </div>
            
            <div>
                <label for="password">New Password</label>
                <input type="password" name="password" required>
            </div>
            <div>
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" name="password_confirmation" required>
            </div>
            <button type="submit">Reset Password</button>
        </form>
    </div>
</body>
</html>
