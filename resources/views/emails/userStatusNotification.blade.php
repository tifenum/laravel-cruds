<!DOCTYPE html>
<html>
<head>
    <title>User Status Notification</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; margin: 0; padding: 0; background-color: #f9f9f9; color: #333;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="max-width: 600px; margin: auto; background-color: #ffffff; border: 1px solid #dddddd; padding: 20px;">
        <tr>
            <td style="padding: 20px; text-align: center;">
                <h1 style="font-size: 24px; color: #333333; margin-bottom: 20px;">User Status Notification</h1>
            </td>
        </tr>
        <tr>
            <td style="padding: 10px 20px;">
                <p style="font-size: 16px; color: #333333;">Dear <strong>{{ $userName }}</strong>,</p>
                <p style="font-size: 16px; color: #333333;">Your account has been <strong>{{ $status }}</strong>.</p>
                <p style="font-size: 16px; color: #333333;">Thank you.</p>
            </td>
        </tr>
    </table>
</body>
</html>
