<!DOCTYPE html>
<html>
<head>
    <title>OTP Verification</title>
</head>
<body style="font-family: sans-serif; background-color: #f4f4f4; padding: 20px;">
    <div style="max-width: 600px; margin: auto; background: white; padding: 20px; border-radius: 8px;">
        <h2 style="color: #333;">Verification Code</h2>
        <p>Hello,</p>
        <p>Use the code below to verify your account on PixoCut by ToolsByPrabhat. It will expire in 10 minutes.</p>
        <div style="font-size: 24px; font-weight: bold; color: #4F46E5; letter-spacing: 5px; text-align: center; padding: 20px; border: 1px dashed #4F46E5;">
            {{ $otp }}
        </div>
        <p>If you didn't request this, you can safely ignore this email.</p>
    </div>
</body>
</html>