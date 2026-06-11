# Project Documentation

## Project Overview
This is a broadcast platform license and device management system built with Laravel. It includes role-based access control (admin/client), license management, device management, and comprehensive system settings.

## System Settings

### Overview
The platform now features a centralized system settings management with database storage. The settings are organized into three main sections:

1. **General Settings**
   - Company Name
   - Support Email
   - Support URL
   - Timezone
   - Logo (with upload/delete functionality)

2. **License Settings**
   - Default Expiration (in days)
   - Default Devices Limit
   - Heartbeat Timeout (in seconds)
   - Validation Timeout (in seconds)

3. **Security Settings**
   - Rate Limit (per minute)
   - Max Activations Per Day
   - API Secret (with generate functionality)
   - Allowed Origins (CORS)

### Technical Implementation

**Database:**
- Table: `settings`
- Uses singleton pattern to ensure only one settings record exists
- Includes caching for performance (1-hour cache duration)

**Model:** `App\Models\Settings`
- `getCurrent()`: Retrieves the current settings instance (creates defaults if none exist)
- `clearCache()`: Clears the settings cache
- `getAllowedOriginsArray()`: Returns allowed origins as an array
- `setAllowedOriginsFromArray()`: Sets allowed origins from an array

**Controller:** `App\Http\Controllers\SettingsController`
- `index()`: Displays the settings page with three tabs
- `updateGeneral()`: Updates general settings
- `updateLicense()`: Updates license settings
- `updateSecurity()`: Updates security settings
- `deleteLogo()`: Deletes the current logo
- `generateApiSecret()`: Generates a new API secret

**Routes:** (All under admin middleware)
- `GET /admin/settings` - Settings index page
- `POST /admin/settings/general` - Update general settings
- `POST /admin/settings/license` - Update license settings
- `POST /admin/settings/security` - Update security settings
- `POST /admin/settings/delete-logo` - Delete logo
- `POST /admin/settings/generate-api-secret` - Generate new API secret

**View:** `resources/views/settings/index.blade.php`
- Tabbed interface with General, License, and Security sections
- Modern UI with Tailwind CSS
- Form validation and error handling
- JavaScript for tab switching and API secret visibility toggle

### Access Control
- Settings management is restricted to admin users only
- Routes are protected by `auth` and `admin` middleware
- Admin users can access settings via the sidebar "System Settings" link

### File Storage
- Logos are stored in `public/images/logo/`
- Logo files are named with timestamp to prevent conflicts
- Old logos are deleted when new ones are uploaded

### Caching
- Settings are cached for 1 hour to improve performance
- Cache is automatically cleared when settings are updated
- Cache key: `settings.current`

### Database Configuration
- The system currently uses SQLite for development/testing
- Migration file: `2026_06_10_095546_create_settings_table.php`
- To run migrations: `php artisan migrate --force`

### Testing
- System has been tested with SQLite database
- Server starts successfully: `php artisan serve`
- All routes and views are properly configured

## API Endpoints

### License Info API
- **Endpoint:** `GET /api/license/info?license_key={key}`
- **Purpose:** Retrieve detailed license information for external applications (Encoder)
- **Response:**
  - `success`: Boolean
  - `data`: License details including:
    - `license_key`: The license key
    - `status`: License status (active, expired, suspended)
    - `expiration`: ISO date string of expiration
    - `allowed_devices`: Maximum devices allowed
    - `used_devices`: Currently active devices count
    - `customer`: Customer information (id, name, email)
    - `created_at`: License creation date
- **Error Cases:**
  - `404`: License not found
  - `403`: License suspended or expired

### Device Info API
- **Endpoint:** `GET /api/device/info?device_id={id}`
- **Purpose:** Retrieve detailed device information
- **Response:**
  - `success`: Boolean
  - `data`: Device details including:
    - `device_id`: Unique device identifier
    - `device_name`: Human-readable device name
    - `os`: Operating system (windows, mac, linux)
    - `status`: Device status (active, suspended, revoked)
    - `last_seen`: ISO timestamp of last activity
    - `license_key`: Associated license key
- **Error Cases:**
  - `404`: Device not found

### Device Deactivation API
- **Endpoint:** `POST /api/device/deactivate`
- **Purpose:** Remove a device from a license (use case: user formats PC or buys new PC)
- **Request Body:**
  - `device_id`: Device identifier to deactivate
- **Response:**
  - `success`: Boolean
  - `message`: Status message
- **Error Cases:**
  - `404`: Device not found

### Security
- All API endpoints are excluded from CSRF verification
- No authentication required (public APIs)
- IP addresses are logged in validation logs

## License Management Features

### License Renewal
- **Location:** Admin Dashboard → License Details page
- **Function:** Renew license expiration by adding days
- **Options:**
  - 30 Days
  - 90 Days
  - 180 Days
  - 365 Days
- **Behavior:**
  - Automatically extends `expires_at` date
  - Reactivates license if it was expired
  - Updates status to 'active'
- **Route:** `POST /admin/licenses/{license}/renew`
- **Controller:** `LicenseController@renew`

### License Expiration Automation
- **Mechanism:** Laravel Scheduler
- **Command:** `licenses:check-expired`
- **Schedule:** Daily at midnight
- **Function:**
  - Checks all active licenses
  - Updates status to 'expired' if `expires_at < now()`
  - Sends expired email notification to license owner
- **Implementation:** `app/Console/Commands/CheckExpiredLicenses.php`

## Email Notification System

### Notification Types

#### 1. License Expiring Soon
- **Trigger:** Daily check at 9 AM
- **Timing:** 30, 7, 3, and 1 days before expiration
- **Command:** `licenses:send-expiration-warnings`
- **Mailable:** `App\Mail\LicenseExpiringSoon`
- **View:** `resources/views/emails/license-expiring-soon.blade.php`
- **Content:**
  - License key and expiration date
  - Days remaining
  - Device usage statistics
  - Action required notice

#### 2. License Expired
- **Trigger:** Automatic expiration check
- **Timing:** Immediately when license expires
- **Mailable:** `App\Mail\LicenseExpired`
- **View:** `resources/views/emails/license-expired.blade.php`
- **Content:**
  - License key and expiration date
  - Service suspension notice
  - Action required notice
  - Contact information

#### 3. New Device Activated
- **Trigger:** Device activation via API
- **Timing:** Immediately upon new device activation
- **Mailable:** `App\Mail\NewDeviceActivated`
- **View:** `resources/views/emails/new-device-activated.blade.php`
- **Content:**
  - Device details (name, ID, OS)
  - Activation timestamp
  - License usage statistics
  - Security notice

### Email Configuration
- **Queue:** All emails use Laravel queue system (implements `ShouldQueue`)
- **Error Handling:** Failed emails are logged but don't block operations
- **Settings:** Configure mail settings in `.env` file (MAIL_MAILER, MAIL_HOST, etc.)
- **Current Setting:** Log mode (MAIL_MAILER=log) for development

## Build & Verification Commands

### Development
```bash
php artisan serve
```

### Database
```bash
php artisan migrate --force
php artisan migrate:rollback --force
```

### Caching
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Scheduler Commands
```bash
# Check for expired licenses
php artisan licenses:check-expired

# Send expiration warning emails
php artisan licenses:send-expiration-warnings

# Run scheduler (for production)
php artisan schedule:work
```

### Testing Commands
```bash
# Test license expiration check
php artisan licenses:check-expired

# Test expiration warnings
php artisan licenses:send-expiration-warnings

# Test server
php artisan serve
```

## Security & Audit System

### API Rate Limiting
All public API endpoints are protected with rate limiting to prevent abuse:
- **License Validation:** 60 requests per minute
- **License Info:** 60 requests per minute
- **Device Activation:** 10 requests per minute (strict)
- **Device Deactivation:** 10 requests per minute (strict)
- **Device Heartbeat:** 120 requests per minute (higher for frequent updates)
- **Device Info:** 60 requests per minute

**Implementation:** Laravel `throttle` middleware on routes

### Security Audit Logs
Tracks security-related events for monitoring and threat detection:

**Event Types:**
- `failed_login` - Failed authentication attempts
- `failed_activation` - Failed device activation attempts
- `api_abuse` - Suspected API abuse
- `blocked_request` - Blocked requests
- `rate_limit_exceeded` - Rate limit violations

**Logged Data:**
- Event type and severity
- IP address and user agent
- License key and device ID (when applicable)
- Event details (JSON)
- Timestamp

**Model:** `App\Models\SecurityAuditLog`
**Table:** `security_audit_logs`

**Automatic Logging:**
- Failed login attempts in `AuthenticatedSessionController`
- Failed activation attempts in `ActivationController` (license not found, suspended, expired, device limit reached, device revoked)

### Admin Audit Logs
Tracks all administrative actions for accountability and compliance:

**Action Types:**
- `license_created` - New license created
- `license_updated` - License details modified
- `license_deleted` - License deleted/suspended
- `license_renewed` - License renewal
- `device_revoked` - Device revoked
- `device_created` - New device added
- `device_updated` - Device details modified
- `device_deleted` - Device removed
- `settings_updated` - System settings changed

**Logged Data:**
- Admin ID who performed the action
- Action type
- Entity type and ID
- Old values (before change)
- New values (after change)
- IP address
- Description
- Timestamp

**Model:** `App\Models\AdminAuditLog`
**Table:** `admin_audit_logs`

**Automatic Logging:**
- License actions in `LicenseController` (create, update, activate, suspend, renew)
- Device actions in `DeviceController` (revoke)

## Client Portal

### My Devices
- **Route:** `GET /client/devices`
- **Access:** Client users only
- **Features:**
  - View all devices registered under user's licenses
  - Filter by status (active, suspended, revoked)
  - Filter by operating system (windows, mac, linux)
  - View device status indicators (online/offline)
  - View license association
  - Statistics dashboard (total, online, offline, active devices)

**Controller:** `DeviceController@myDevices`
**View:** `resources/views/devices/my-devices.blade.php`

### Enhanced My Licenses
Improved license view with enhanced status indicators:
- License status badges (active, suspended, expired)
- Expiration warnings (⚠️ icon when license expires in 30 days)
- Device usage details (active/online device counts)
- Expiration date with relative time (e.g., "2 days left")
- Visual status indicators for quick scanning

**View:** `resources/views/licenses/my-licenses.blade.php`

### Status Indicators

#### License Status
- **Active:** Green badge
- **Suspended:** Red badge
- **Expired:** Yellow badge
- **Warning:** Expiring soon indicator (≤ 30 days)

#### Device Status
- **Online:** Green badge with last seen time
- **Offline:** Gray badge with last seen time
- **Active:** Green status badge
- **Suspended:** Yellow status badge
- **Revoked:** Red status badge

## Production Checklist

Before deploying to production:
1. ✅ Configure SMTP settings in `.env` for email notifications
2. ✅ Set up queue worker: `php artisan queue:work`
3. ✅ Configure Laravel scheduler: `php artisan schedule:work`
4. ✅ Review rate limiting limits based on expected traffic
5. ✅ Set up log rotation for audit logs
6. ✅ Configure proper CORS settings if needed
7. ✅ Review and update security settings
8. ✅ Test all API endpoints with rate limiting
9. ✅ Verify email notifications are working
10. ✅ Configure backup strategy for database

## Important Notes

1. **Database Connection**: The system currently uses SQLite for development. For production, configure MySQL connection in `.env` file.

2. **Settings Storage**: Previous settings implementation used JSON file storage (`storage/app/settings.json`). The new system uses database storage for better performance and reliability.

3. **API Security**: The API secret should be kept confidential and generated using the built-in functionality. Do not hardcode secrets in the application.

4. **Logo Uploads**: Ensure the `public/images/logo` directory exists and has proper write permissions.

5. **CORS Configuration**: Allowed origins should be configured based on your application's needs. Leave blank to allow all origins (*).

## Future Enhancements

Potential improvements for the settings system:
- Email notification settings
- Backup and restore functionality
- Settings export/import
- Multi-language support
- Advanced security options (2FA, IP whitelisting)
- Integration with external services

## Testing

### Comprehensive System Test (2026-06-10)
A complete lifecycle test was performed to verify all system functionality before adding new features.

**Test Results:** ✅ ALL TESTS PASSED

**Test Scenario:**
1. ✅ Create License - Successfully creates license with automatic key generation
2. ✅ Activate Device - Successfully activates device via API (10 req/min rate limit)
3. ✅ Heartbeat - Successfully sends heartbeat and updates device status (120 req/min rate limit)
4. ✅ Online Status - Successfully detects and displays device online status
5. ✅ Renew License - Successfully extends license expiration and reactivates expired licenses
6. ✅ Expiration Warning - Successfully detects licenses expiring soon and sends warnings (daily at 9 AM)
7. ✅ Expiration Automation - Successfully detects expired licenses and updates status (daily at midnight)
8. ✅ License Info API - Successfully retrieves license information (60 req/min rate limit)
9. ✅ Device Info API - Successfully retrieves device information (60 req/min rate limit)
10. ✅ Device Deactivation - Successfully deactivates and removes devices (10 req/min rate limit)

**Test Script:** `test_complete_lifecycle.php`
**Test Report:** `TEST_REPORT.md`

**System Status:** ✅ READY FOR NEW FEATURES
- All API endpoints working correctly
- All rate limiting functional
- All automation commands working
- No critical issues found
- Database migrations complete
- Server running successfully

**Issues Resolved:**
- Fixed field name error in test script (customer_id → user_id)

**Recommendation:** System is fully functional and ready for new feature development.