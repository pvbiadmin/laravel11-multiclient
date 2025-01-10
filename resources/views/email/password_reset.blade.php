<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            color: #555;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo img {
            max-width: 150px;
            height: auto;
        }

        .reset-button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #007bff;
            color: #ffffff;
            text-decoration: none;
            border-radius: 4px;
            margin: 20px 0;
            text-align: center;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .reset-button:hover {
            background-color: #0056b3;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 0.8em;
            color: #888;
        }

        .note {
            font-size: 0.9em;
            color: #666;
            margin-top: 20px;
            line-height: 1.5;
        }

        .link-note {
            margin-top: 20px;
            font-size: 0.9em;
            color: #666;
            line-height: 1.5;
        }

        .link-note a {
            color: #007bff;
            text-decoration: none;
        }

        .link-note a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="logo">
            <img src="https://example.com/logo.png" alt="Company Logo">
        </div>

        <p>Hi there,</p>

        <p>We received a request to reset your password. If this was you, click the button below to create a new
            password:</p>

        <div style="text-align: center;">
            <a href="{{ $resetLink }}" class="reset-button"><span style="color: #ffffff;">Reset My Password</span></a>
        </div>

        <p class="note">
            <strong>Important:</strong> This link will expire in <strong>60 minutes</strong>. If you don't reset your
            password within this time, you'll need to request another link.
        </p>

        <p class="note">
            If you didn't request a password reset, you can safely ignore this email. Your account is still secure, and
            no changes have been made.
        </p>

        <p>Best regards,<br>The {{ config('app.name') }} Team</p>

        <div class="link-note">
            <p>If the button above doesn't work, copy and paste this link into your browser:</p>
            <a href="{{ $resetLink }}">{{ $resetLink }}</a>
        </div>

        <div class="footer">
            © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </div>
</body>

</html>
