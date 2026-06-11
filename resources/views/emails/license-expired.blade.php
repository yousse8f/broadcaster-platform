<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>License Expired</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <div style="background: #f8f9fa; padding: 30px; border-radius: 8px;">
            <h1 style="color: #dc3545; margin-bottom: 20px;">❌ Your License Has Expired</h1>
            
            <p>Dear {{ $license->user->name }},</p>
            
            <p>We regret to inform you that your license <strong>{{ $license->license_key }}</strong> has expired on <strong>{{ $license->expires_at->format('F d, Y') }}</strong>.</p>
            
            <p><strong>License Details:</strong></p>
            <ul style="list-style: none; padding: 0;">
                <li style="margin-bottom: 10px;"><strong>License Key:</strong> {{ $license->license_key }}</li>
                <li style="margin-bottom: 10px;"><strong>Expiration Date:</strong> {{ $license->expires_at->format('F d, Y') }}</li>
                <li style="margin-bottom: 10px;"><strong>Allowed Devices:</strong> {{ $license->allowed_devices }}</li>
                <li><strong>Active Devices:</strong> {{ $license->active_devices_count }}</li>
            </ul>
            
            <p style="margin-top: 20px;">Your service has been suspended. To resume using the service, please renew your license immediately.</p>
            
            <div style="margin-top: 30px; padding: 20px; background: #fff; border-left: 4px solid #dc3545; border-radius: 4px;">
                <p style="margin: 0;"><strong>Action Required:</strong> Contact your administrator to renew your license as soon as possible.</p>
            </div>
            
            <p style="margin-top: 30px; font-size: 14px; color: #6c757d;">
                If you have any questions or need assistance, please contact our support team.
            </p>
        </div>
    </div>
</body>
</html>