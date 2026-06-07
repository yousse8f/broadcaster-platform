# كيفية استخدام السايدبار

السايدبار الآن منفصل بالكامل ويمكن استخدامه في أي صفحة.

## ✅ تم حل المشكلة

تم تحديث جميع الصفحات لاستخدام التخطيط الموحد، والسايدبار الآن:
1. **منفصل تماماً**: ملف CSS منفصل في `resources/css/sidebar.css`
2. **موحد في جميع الصفحات**: جميع الصفحات تستخدم `@extends('layouts.app')`
3. **سهل الصيانة**: أي تغيير في السايدبار يتم في مكان واحد فقط

## الطريقة 1: استخدام التخطيط الموجود (app.blade.php)

في أي صفحة تريد استخدام السايدبار، ببساطة استخدم التخطيط:

```blade
@extends('layouts.app')

@section('title', 'عنوان الصفحة')

@section('content')
    <!-- محتوى الصفحة هنا -->
@endsection
```

## الطريقة 2: تضمين السايدبار مباشرة

إذا كنت تريد استخدام السايدبار في صفحة بتخطيط مخصص:

```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>صفحتك</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- تضمين CSS السايدبار -->
    @vite(['resources/css/app.css', 'resources/css/sidebar.css'])
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f0f2f5;
            min-height: 100vh;
            display: flex;
        }
        .main-content {
            margin-left: 260px;
            padding: 32px;
            flex: 1;
        }
    </style>
</head>
<body>
    <!-- تضمين السايدبار -->
    @include('layouts.sidebar')

    <!-- المحتوى الرئيسي -->
    <div class="main-content">
        <!-- محتوى الصفحة هنا -->
    </div>
</body>
</html>
```

## ملاحظات مهمة:

1. **ملف CSS منفصل**: يوجد ملف CSS منفصل للسايدبار في `resources/css/sidebar.css`
2. **التضمين التلقائي**: السايدبار يتضمن CSS الخاص به تلقائياً عند استخدام التخطيط الموحد
3. **التوافق مع الشاشات الصغيرة**: السايدبار يتضمن تصميم متجاوب للشاشات الصغيرة
4. **لا حاجة لتعديل المحتوى**: عند استخدام التخطيط الموحد، لا يحتاج المحتوى الرئيسي إلى `margin-left` لأنه يتم معالجته تلقائياً

## إنشاء صفحة جديدة:

لإنشاء صفحة جديدة مع السايدبار:

```blade
@extends('layouts.app')

@section('title', 'عنوان الصفحة')

@section('content')
    <div class="header">
        <h1 class="header-title">عنوان الصفحة</h1>
    </div>

    <!-- محتوى الصفحة هنا -->
@endsection

@push('styles')
<style>
    /* تنسيقات خاصة بالصفحة هنا */
</style>
@endpush
```

## التحديثات التي تم إجراؤها:

### 1. إنشاء ملف CSS منفصل للسايدبار
- تم إنشاء `resources/css/sidebar.css` يحتوي على جميع تنسيقات السايدبار
- تم تحديث `vite.config.js` لتضمين الملف في عملية البناء
- يتم بناؤه تلقائياً مع `npm run build`

### 2. تحديث التخطيط الموحد
- تم تحديث `resources/views/layouts/app.blade.php` لاستخدام Vite
- تم إزالة CSS المكرر من التخطيط الرئيسي
- الآن السايدبار يتم تضمينه تلقائياً عبر `@include('layouts.sidebar')`

### 3. تحديث جميع الصفحات لاستخدام التخطيط الموحد
تم تحديث الصفحات التالية لاستخدام `@extends('layouts.app')`:
- ✅ `resources/views/dashboard.blade.php` (كان يستخدم التخطيط الموحد)
- ✅ `resources/views/settings/index.blade.php` (كان يستخدم التخطيط الموحد)
- ✅ `resources/views/licenses/index.blade.php` (تم تحديثه)
- ✅ `resources/views/licenses/create.blade.php` (تم تحديثه)
- ✅ `resources/views/licenses/edit.blade.php` (تم تحديثه)
- ✅ `resources/views/licenses/show.blade.php` (تم تحديثه)
- ✅ `resources/views/devices/index.blade.php` (تم تحديثه)
- ✅ `resources/views/devices/show.blade.php` (تم تحديثه)
- ✅ `resources/views/devices/create.blade.php` (تم إنشاؤه)
- ✅ `resources/views/devices/edit.blade.php` (تم إنشاؤه)

### 4. إزالة CSS والسايدبار المكرر
تم إزالة ما يقرب من 140 سطر من CSS المكرر من كل صفحة
تم إزالة HTML السايدبار المكرر من كل صفحة

### 5. تحسينات صفحة التراخيص
- ✅ تحديث شكل الأزرار لتكون مربعة وأكثر جاذبية
- ✅ إزالة زر Suspend
- ✅ إضافة عرض اسم المستخدم والإيميل
- ✅ إضافة عرض عدد الأجهزة المسموحة
- ✅ تحسين عرض التاريخ (يدعم expires_at و expiry_date)
- ✅ تحديث الأزرار لتكون أيقونات فقط

### 6. إصلاح المسارات وال Controllers
- ✅ إضافة مسارات missing في routes/web.php
- ✅ إضافة دوال missing في Controllers
- ✅ تحديث License Model لدعم expires_at و expiry_date
