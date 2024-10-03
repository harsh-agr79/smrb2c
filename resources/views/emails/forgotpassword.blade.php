<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h1>Hello, {{ $name }}</h1>
    <p>You requested to reset your password. Please click the link below to reset it:</p>
    <a href="{{ $resetUrl }}">Reset Password</a>
    <p>If you did not request this, please ignore this email.</p>
</body>
</html>
