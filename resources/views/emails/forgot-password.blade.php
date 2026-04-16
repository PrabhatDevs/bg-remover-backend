<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reset Your Password</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f9fafb; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); border: 1px solid #e5e7eb; }
        .header { background: #4f46e5; padding: 30px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 24px; letter-spacing: 1px; }
        .content { padding: 40px; text-align: center; color: #374151; }
        .otp-container { background-color: #f3f4f6; border: 2px dashed #4f46e5; border-radius: 8px; padding: 20px; margin: 30px 0; display: inline-block; width: 80%; }
        .otp-code { font-size: 32px; font-weight: bold; color: #1f2937; letter-spacing: 8px; margin: 0; }
        .footer { padding: 20px; text-align: center; font-size: 12px; color: #9ca3af; background-color: #f9fafb; border-top: 1px solid #e5e7eb; }
        .warning { font-size: 13px; color: #ef4444; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>ToolsByPrabhat</h1>
        </div>
        <div class="content">
            <h2 style="margin-top: 0;">Password Reset Request</h2>
            <p>We received a request to reset your password. Use the code below to proceed with the reset. This code is valid for <strong>15 minutes</strong>.</p>
            
            <div class="otp-container">
                <p style="margin: 0 0 10px 0; text-transform: uppercase; font-size: 12px; color: #6b7280;">Your Reset Code</p>
                <p class="otp-code">{{ $otp }}</p>
            </div>

            <p class="warning">If you did not request a password reset, please ignore this email or contact support if you have concerns.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} ToolsByPrabhat. All rights reserved.<br>
            Gorakhpur, Uttar Pradesh, India
        </div>
    </div>
</body>
</html>