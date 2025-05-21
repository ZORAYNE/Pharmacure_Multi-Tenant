<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Tenant Registration Accepted</title>
</head>
<body>
    <h1>Tenant Registration Accepted</h1>
    <p>Dear {{ $fullName }},</p>
    <p>We are pleased to inform you that your tenant registration for <strong>{{ $tenantName }}</strong> has been accepted.</p>
    <p>You can access your tenant login page here: <a href="http://127.0.0.1:8000/login?tenant={{ $tenantName }}">http://127.0.0.1:8000/login?tenant={{ $tenantName }}</a></p>
    <p>Your login credentials are as follows:</p>
    <ul>
        <li>Email: {{ $email }}</li>
        <li>Password: {{ $password }}</li>
    </ul>
    <p>Thank you for choosing our service.</p>
    <p>Best regards,<br/>The Team</p>
</body>
</html>
