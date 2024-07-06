<!-- resources/views/emails/reset-password.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password OTP</title>
</head>
<body>
    <h2>Reset Password OTP</h2>
    <p>Dear User,</p>
    <p>Silakan gunakan OTP berikut untuk mengatur ulang kata sandi Anda:</p>
    <h3>{{ $otp }}</h3>
    <p>Thank you</p>
</body>
</html>
