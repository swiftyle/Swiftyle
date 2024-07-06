<!-- resources/views/emails/otp.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>OTP Verification</title>
</head>
<body>
    <h1>OTP Verification</h1>
    <p>Dear {{ $user->name }},</p>
    <p>Terima kasih telah mendaftar. Silakan gunakan OTP berikut untuk memverifikasi alamat email Anda:</p>
    <h2>{{ $otp }}</h2>
    <p>Terima Kasih</p>
</body>
</html>
