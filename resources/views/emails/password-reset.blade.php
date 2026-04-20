<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password - Toolsbyprabhat</title>
    <!-- Import Professional Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&family=Space+Mono:wght@700&display=swap"
        rel="stylesheet">

    <style>
        /* General Reset */
        body {
            margin: 0;
            padding: 0;
            width: 100% !important;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            background-color: #f8fafc;
        }

        table {
            border-spacing: 0;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        /* Layout */
        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #f8fafc;
            padding: 64px 0;
        }

        .container {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 24px;
            overflow: hidden;
            /* Professional Blue Border */
            border: 2px solid #2563eb;
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.08);
        }

        .content {
            padding: 56px 48px;
            text-align: center;
        }

        /* Typography Sections */
        .brand {
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            font-weight: 800;
            color: #2563eb;
            /* Primary Blue */
            text-transform: uppercase;
            letter-spacing: 0.2em;
            margin-bottom: 32px;
            display: block;
        }

        .heading {
            font-family: 'Inter', sans-serif;
            font-size: 28px;
            font-weight: 800;
            color: #1e3a8a;
            /* Darker Blue Heading */
            margin: 0 0 16px 0;
            letter-spacing: -0.02em;
        }

        .text {
            font-family: 'Inter', sans-serif;
            font-size: 15px;
            font-weight: 400;
            color: #64748b;
            margin-bottom: 40px;
        }

        /* OTP Block - Blue Theme */
        .otp-box {
            background-color: #f0f7ff;
            /* Very Light Blue */
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 40px;
            border: 1px dashed #82baff;
            /* Subtle Blue Border */
        }

        .otp-code {
            font-family: 'Space Mono', monospace;
            font-size: 36px;
            font-weight: 700;
            letter-spacing: 12px;
            color: #1d4ed8;
            /* Strong Blue Code */
            margin: 0;
            padding-left: 12px;
        }

        /* Footer */
        .footer {
            padding: 0 48px 56px 48px;
            text-align: center;
        }

        .footer-text {
            font-family: 'Inter', sans-serif;
            font-size: 12px;
            font-weight: 400;
            color: #94a3b8;
            margin: 0;
        }

        .security-hint {
            font-family: 'Inter', sans-serif;
            font-size: 11px;
            font-weight: 700;
            color: #3b82f6;
            /* Accent Blue */
            margin-top: 20px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        @media screen and (max-width: 480px) {
            .content {
                padding: 40px 24px;
            }

            .otp-code {
                font-size: 28px;
                letter-spacing: 8px;
            }
        }
    </style>
</head>

<body>
    <center class="wrapper">
        <table class="container" role="presentation">
            <tr>
                <td class="content">
                    <span class="brand">Toolsbyprabhat</span>
                    <h1 class="heading">Reset Password</h1>
                    <p class="text">We received a request to reset your password. Use the verification code below to
                        proceed with the reset process. This code is valid for 10 minutes.</p>

                    <div class="otp-box">
                        <div class="otp-code">582 904</div>
                    </div>

                    <p class="text" style="font-size: 13px; margin-bottom: 0;">
                        If you did not request a password reset, you can safely ignore this email. No changes have been
                        made to your account.
                    </p>
                </td>
            </tr>
            <tr>
                <td class="footer">
                    <p class="footer-text">&copy; 2026 Toolsbyprabhat. Professional utility suite.</p>
                    <p class="security-hint">Secure Password Reset</p>
                </td>
            </tr>
        </table>
    </center>
</body>

</html>