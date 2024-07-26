<!DOCTYPE html>
<html>
<head>
    <title>Password Reset</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; background-color: #f9f9f9; color: #333; padding: 20px;">
    <p style="font-size: 16px; color: #333;">
        Click 
        <a href="{{ route('reset.password.get', $token) }}" style="color: #007bff; text-decoration: none; font-weight: bold;">
            Here
        </a> 
        to reset your password.
    </p>
</body>
</html>
