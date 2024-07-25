<!DOCTYPE html>
<html>
<head>
    <title>Candidature Status Update</title>
</head>
<body>
    <p>Dear {{ $candidature->prenom }} {{ $candidature->nom }},</p>
    <p>Your candidature has been {{ $status }}.</p>
    <p>Thank you for your interest.</p>
    <p>Best regards,</p>
    <p>The Team</p>
</body>
</html>
