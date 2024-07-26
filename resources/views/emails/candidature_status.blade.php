<!DOCTYPE html>
<html>
<head>
    <title>Candidature Status Update</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; margin: 0; padding: 0; background-color: #f4f4f4; color: #333;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="max-width: 600px; margin: auto; background-color: #ffffff; border: 1px solid #dddddd; padding: 20px;">
        <tr>
            <td style="padding: 20px; text-align: center;">
                <h1 style="font-size: 24px; color: #333333; margin-bottom: 20px;">Candidature Status Update</h1>
            </td>
        </tr>
        <tr>
            <td style="padding: 10px 20px;">
                <p style="font-size: 16px; color: #333333;">Dear <strong>{{ $candidature->prenom }} {{ $candidature->nom }}</strong>,</p>
                <p style="font-size: 16px; color: #333333;">Your candidature has been <strong>{{ $status }}</strong>.</p>
                <p style="font-size: 16px; color: #333333;">Thank you for your interest.</p>
                <p style="font-size: 16px; color: #333333;">Best regards,</p>
                <p style="font-size: 16px; color: #333333;">The Team</p>
            </td>
        </tr>
    </table>
</body>
</html>
