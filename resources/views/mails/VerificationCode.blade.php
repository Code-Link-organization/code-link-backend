<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CodeLink | Verification Code</title>
</head>

<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">

    <div style="max-width: 600px; margin: 0 auto; padding: 20px; background-color: #ffffff;">

        <h3 style="font-size: 24px; color: #333;">Hello {{ $user->name }},</h3>

        <p style="font-size: 16px; color: #666;">Your Verification Code: <strong>{{ $user->code }}</strong></p>

        <p style="font-size: 16px; color: #666;">
            It will expire after {{ config('auth.code_timeout')/60 }} minutes</strong>
        </p>

        <p style="font-size: 16px; color: #666;">Thank you!</p>

    </div>

</body>

</html>
