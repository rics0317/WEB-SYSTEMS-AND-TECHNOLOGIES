<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
</head>
<body>
    <h1>Verify Your Email Address</h1>
    <p>Hello {{ $user->name }},</p>
    <p>Your email verification code is: <strong>{{ $user->verification_code }}</strong></p>
    <p>Please enter this code on the verification page to complete your registration.</p>
</body>
</html>