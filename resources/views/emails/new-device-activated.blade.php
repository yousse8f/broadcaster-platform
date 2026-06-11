<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Device Activated</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <div style="background: #f8f9fa; padding: 30px; border-radius: 8px;">
            <h1 style="color: #28a745; margin-bottom: 20px;">✅ New Device Activated</h1>
            
            <p>Dear {{ $device->license->user->name }},</p>
            
            <p>A new device has been successfully activated on your license <strong>{{ $device->license->license_key }}</strong>.</p>
            
            <p><strong>Device Details:</strong></p>
            <ul style="list-style: none; padding: 0;">
                <li style="margin-bottom: 10px;"><strong>Device Name:</strong> {{ $device->device_name }}</li>
                <li style="margin-bottom: 10px;"><strong>Device ID:</strong> {{ $device->device_id }}</li>
                <li style="margin-bottom: 10px;"><strong>Operating System:</strong> {{ ucfirst($device->operating_system) }}</li>
                <li style="margin-bottom: 10px;"><strong>Status:</strong> {{ ucfirst($device->status) }}</li>
                <li><strong>Activated At:</strong> {{ $device->first_activated_at ? $device->first_activated_at->format('F d, Y H:i') : 'N/A' }}</li>
            </ul>
            
            <p><strong>License Usage:</strong></p>
            <ul style="list-style: none; padding: 0;">
                <li style="margin-bottom: 10px;"><strong>License Key:</strong> {{ $device->license->license_key }}</li>
                <li style="margin-bottom: 10px;"><strong>Allowed Devices:</strong> {{ $device->license->allowed_devices }}</li>
                <li><strong>Active Devices:</strong> {{ $device->license->active_devices_count }}</li>
            </ul>
            
            <div style="margin-top: 30px; padding: 20px; background: #fff; border-left: 4px solid #28a745; border-radius: 4px;">
                <p style="margin: 0;"><strong>Security Notice:</strong> If you did not activate this device, please contact our support team immediately.</p>
            </div>
            
            <p style="margin-top: 30px; font-size: 14px; color: #6c757d;">
                If you have any questions, please contact our support team.
            </p>
        </div>
    </div>
</body>
</html>