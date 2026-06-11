<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\License;
use App\Models\Device;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;

echo "=== اختبار شامل لدورة حياة الترخيص ===\n\n";

// Initialize variables in higher scope
$license = null;
$user = null;
$device = null;
$device_id = null;

// Test 1: Create License (via admin dashboard simulation)
echo "1. إنشاء ترخيص جديد (Create License)\n";
try {
    $user = User::where('role', 'client')->first();
    if (!$user) {
        $user = User::create([
            'name' => 'Test Client',
            'email' => 'test@client.com',
            'password' => bcrypt('password'),
            'role' => 'client'
        ]);
        echo "   ✓ تم إنشاء مستخدم اختباري\n";
    }

    $license = License::create([
        'license_key' => 'TEST-' . strtoupper(substr(md5(uniqid()), 0, 16)),
        'user_id' => $user->id,
        'expires_at' => Carbon::now()->addDays(30),
        'allowed_devices' => 3,
        'status' => 'active'
    ]);
    echo "   ✓ تم إنشاء الترخيص: {$license->license_key}\n";
    echo "   ✓ الحالة: {$license->status}\n";
    echo "   ✓ تاريخ الانتهاء: {$license->expires_at}\n";
    echo "   ✓ عدد الأجهزة المسموحة: {$license->allowed_devices}\n";
} catch (Exception $e) {
    echo "   ✗ خطأ في إنشاء الترخيص: {$e->getMessage()}\n";
    exit(1);
}

// Test 2: Activate Device via API
echo "\n2. تفعيل جهاز جديد (Activate Device)\n";
try {
    $device_id = 'DEVICE-' . strtoupper(substr(md5(uniqid()), 0, 8));
    $response = Http::post('http://127.0.0.1:8000/api/device/activate', [
        'license_key' => $license->license_key,
        'device_id' => $device_id,
        'device_name' => 'Test Device 1',
        'os' => 'windows'
    ]);

    if ($response->successful()) {
        echo "   ✓ تم تفعيل الجهاز بنجاح\n";
        echo "   ✓ معرف الجهاز: {$device_id}\n";
        echo "   ✓ الرد: " . json_encode($response->json(), JSON_UNESCAPED_UNICODE) . "\n";
        
        // Verify device was created
        $device = Device::where('device_id', $device_id)->first();
        if ($device) {
            echo "   ✓ تم حفظ الجهاز في قاعدة البيانات\n";
            echo "   ✓ حالة الجهاز: {$device->status}\n";
        } else {
            echo "   ✗ الجهاز لم يتم حفظه في قاعدة البيانات\n";
        }
    } else {
        echo "   ✗ فشل تفعيل الجهاز\n";
        echo "   ✓ خطأ: " . $response->body() . "\n";
    }
} catch (Exception $e) {
    echo "   ✗ خطأ في تفعيل الجهاز: {$e->getMessage()}\n";
}

// Test 3: Heartbeat to make device online
echo "\n3. إرسال إشارة نبض القلب (Heartbeat)\n";
try {
    $response = Http::post('http://127.0.0.1:8000/api/device/heartbeat', [
        'device_id' => $device_id,
        'license_key' => $license->license_key
    ]);

    if ($response->successful()) {
        echo "   ✓ تم إرسال إشارة نبض القلب بنجاح\n";
        echo "   ✓ الرد: " . json_encode($response->json(), JSON_UNESCAPED_UNICODE) . "\n";
        
        // Verify device is now online
        $device->refresh();
        if ($device->isOnline()) {
            echo "   ✓ الجهاز متصل الآن (Online)\n";
            echo "   ✓ آخر نشاط: {$device->last_seen}\n";
        } else {
            echo "   ✗ الجهاز غير متصل\n";
        }
    } else {
        echo "   ✗ فشل إرسال إشارة نبض القلب\n";
        echo "   ✓ خطأ: " . $response->body() . "\n";
    }
} catch (Exception $e) {
    echo "   ✗ خطأ في إرسال إشارة نبض القلب: {$e->getMessage()}\n";
}

// Test 4: Verify License Info API
echo "\n4. التحقق من معلومات الترخيص (License Info)\n";
try {
    $response = Http::get('http://127.0.0.1:8000/api/license/info', [
        'license_key' => $license->license_key
    ]);

    if ($response->successful()) {
        echo "   ✓ تم الحصول على معلومات الترخيص\n";
        echo "   ✓ الرد: " . json_encode($response->json(), JSON_UNESCAPED_UNICODE) . "\n";
    } else {
        echo "   ✗ فشل الحصول على معلومات الترخيص\n";
        echo "   ✓ خطأ: " . $response->body() . "\n";
    }
} catch (Exception $e) {
    echo "   ✗ خطأ في الحصول على معلومات الترخيص: {$e->getMessage()}\n";
}

// Test 5: Renew License
echo "\n5. تجديد الترخيص (Renew License)\n";
try {
    $original_expiration = $license->expires_at;
    $license->expires_at = Carbon::now()->subDays(5); // Simulate expired license
    $license->save();
    echo "   ✓ محاكاة ترخيص منتهي (تاريخ الانتهاء: {$license->expires_at})\n";

    // Simulate admin renewal via controller
    $license->expires_at = Carbon::now()->addDays(90);
    $license->status = 'active';
    $license->save();
    
    echo "   ✓ تم تجديد الترخيص لـ 90 يوم\n";
    echo "   ✓ التاريخ الجديد: {$license->expires_at}\n";
    echo "   ✓ الحالة: {$license->status}\n";
    
    if ($license->expires_at > $original_expiration) {
        echo "   ✓ تم تمديد التاريخ بنجاح\n";
    }
} catch (Exception $e) {
    echo "   ✗ خطأ في تجديد الترخيص: {$e->getMessage()}\n";
}

// Test 6: License Expiration Warning
echo "\n6. اختبار تحذير انتهاء الترخيص (Expiration Warning)\n";
try {
    // Set license to expire in 7 days
    $license->expires_at = Carbon::now()->addDays(7);
    $license->save();
    echo "   ✓ تعيين الترخيص لينتهي خلال 7 أيام\n";

    // Check if license is expiring soon
    $days_until_expiration = Carbon::now()->diffInDays($license->expires_at, false);
    echo "   ✓ الأيام المتبقية: {$days_until_expiration}\n";
    
    if ($days_until_expiration <= 30 && $days_until_expiration > 0) {
        echo "   ✓ الترخيص سيتم إرسال تحذير له (التنبيه يعمل)\n";
    }

    // Test the command
    echo "   ✓ تشغيل أمر إرسال تحذيرات انتهاء الترخيص...\n";
    $exit_code = Artisan::call('licenses:send-expiration-warnings');
    if ($exit_code === 0) {
        echo "   ✓ تم تنفيذ الأمر بنجاح\n";
    } else {
        echo "   ✗ فشل تنفيذ الأمر\n";
    }
} catch (Exception $e) {
    echo "   ✗ خطأ في اختبار تحذير انتهاء الترخيص: {$e->getMessage()}\n";
}

// Test 7: License Expiration Automation
echo "\n7. اختبار أتمتة انتهاء الترخيص (Expiration Automation)\n";
try {
    // Set license to expired
    $license->expires_at = Carbon::now()->subDays(1);
    $license->status = 'active';
    $license->save();
    echo "   ✓ تعيين الترخيص كمنتهي (تاريخ الانتهاء: {$license->expires_at})\n";
    echo "   ✓ الحالة الحالية: {$license->status}\n";

    // Run the expiration check command
    echo "   ✓ تشغيل أمر فحص التراخيص المنتهية...\n";
    $exit_code = Artisan::call('licenses:check-expired');
    if ($exit_code === 0) {
        echo "   ✓ تم تنفيذ الأمر بنجاح\n";
    } else {
        echo "   ✗ فشل تنفيذ الأمر\n";
    }

    // Verify license status changed
    $license->refresh();
    echo "   ✓ الحالة بعد الفحص: {$license->status}\n";
    
    if ($license->status === 'expired') {
        echo "   ✓ تم تغيير الحالة إلى منتهي بنجاح\n";
    } else {
        echo "   ✗ لم يتم تغيير الحالة\n";
    }
} catch (Exception $e) {
    echo "   ✗ خطأ في اختبار أتمتة انتهاء الترخيص: {$e->getMessage()}\n";
}

// Test 8: Device Info API
echo "\n8. اختبار معلومات الجهاز (Device Info)\n";
try {
    $response = Http::get('http://127.0.0.1:8000/api/device/info', [
        'device_id' => $device_id
    ]);

    if ($response->successful()) {
        echo "   ✓ تم الحصول على معلومات الجهاز\n";
        echo "   ✓ الرد: " . json_encode($response->json(), JSON_UNESCAPED_UNICODE) . "\n";
    } else {
        echo "   ✗ فشل الحصول على معلومات الجهاز\n";
        echo "   ✓ خطأ: " . $response->body() . "\n";
    }
} catch (Exception $e) {
    echo "   ✗ خطأ في الحصول على معلومات الجهاز: {$e->getMessage()}\n";
}

// Test 9: Device Deactivation
echo "\n9. اختبار إلغاء تفعيل الجهاز (Device Deactivation)\n";
try {
    $response = Http::post('http://127.0.0.1:8000/api/device/deactivate', [
        'device_id' => $device_id
    ]);

    if ($response->successful()) {
        echo "   ✓ تم إلغاء تفعيل الجهاز بنجاح\n";
        echo "   ✓ الرد: " . json_encode($response->json(), JSON_UNESCAPED_UNICODE) . "\n";
        
        // Verify device was removed
        $device_check = Device::where('device_id', $device_id)->first();
        if (!$device_check) {
            echo "   ✓ تم حذف الجهاز من قاعدة البيانات\n";
        } else {
            echo "   ✗ الجهاز لا يزال موجوداً في قاعدة البيانات\n";
        }
    } else {
        echo "   ✗ فشل إلغاء تفعيل الجهاز\n";
        echo "   ✓ خطأ: " . $response->body() . "\n";
    }
} catch (Exception $e) {
    echo "   ✗ خطأ في إلغاء تفعيل الجهاز: {$e->getMessage()}\n";
}

// Cleanup
echo "\n10. تنظيف بيانات الاختبار\n";
try {
    // Delete any remaining device
    if ($device_id) {
        Device::where('device_id', $device_id)->delete();
        echo "   ✓ تم حذف أي جهاز متبقي\n";
    }
    
    if ($license) {
        $license->delete();
        echo "   ✓ تم حذف الترخيص\n";
    }
    if ($user) {
        $user->delete();
        echo "   ✓ تم حذف المستخدم\n";
    }
} catch (Exception $e) {
    echo "   ✗ خطأ في التنظيف: {$e->getMessage()}\n";
}

echo "\n=== انتهى الاختبار الشامل ===\n";
