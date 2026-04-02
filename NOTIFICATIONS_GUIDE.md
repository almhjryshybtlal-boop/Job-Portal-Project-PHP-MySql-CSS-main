# 🔔 دليل نظام الإشعارات والتنبيهات

**تاريخ الإنشاء:** 2025-10-25  
**الإصدار:** 1.0  
**الحالة:** ✅ جاهز للاستخدام

---

## 📋 نظرة عامة

تم إنشاء نظام إشعارات متكامل يعرض رسائل النجاح/الفشل/التحذير/المعلومات لجميع العمليات في المشروع.

### ✅ المميزات الرئيسية:
- ✅ **4 أنواع إشعارات**: نجاح، خطأ، تحذير، معلومة
- ✅ **تكامل تلقائي** مع PHP Session
- ✅ **تصميم عصري** مع تأثيرات حركية
- ✅ **متجاوب** لجميع الشاشات
- ✅ **سهل الاستخدام** من PHP أو JavaScript
- ✅ **لا يؤثر** على أي كود موجود
- ✅ **دعم اللغة العربية** الكامل

---

## 📁 الملفات المضافة

### 1. ملفات CSS و JavaScript:
```
✅ assets/css/notifications.css (270 سطر)
✅ assets/js/notifications.js (199 سطر)
```

### 2. ملفات PHP:
```
✅ includes/notification_display.php (49 سطر)
```

### 3. ملفات محدثة:
```
✅ includes/indexHeader.php (إضافة 3 أسطر)
✅ includes/indexHeaderr.php (إضافة 3 أسطر)
✅ index.php (إضافة 4 أسطر)
```

---

## 🚀 كيفية الاستخدام

### 1️⃣ من PHP (تلقائي)

النظام يعمل **تلقائياً** مع جميع الملفات التي تستخدم `$_SESSION['message']`!

#### مثال - الكود الموجود حالياً:
```php
// في ملف process/newJob.php (كمثال)
if ($success) {
    $_SESSION['message'] = 'تم إضافة الوظيفة بنجاح';
    $_SESSION['messagetype'] = 'success';
    header("Location: ../dashboard/manageJobs.php");
}
```

**سيعمل تلقائياً!** لا حاجة لتعديل أي شيء! ✅

### ✅ دعم اللغة العربية:
جميع الإشعارات تظهر باللغة العربية:
- **نجاح**: نجح! ✓
- **خطأ**: خطأ! ✗
- **تحذير**: تحذير! ⚠
- **معلومة**: معلومة ℹ

---

### 2️⃣ من PHP (إضافة جديدة)

إذا أردت إضافة إشعار في أي ملف PHP جديد:

```php
// إشعار نجاح
$_SESSION['message'] = 'تمت العملية بنجاح!';
$_SESSION['messagetype'] = 'success';

// إشعار خطأ
$_SESSION['message'] = 'حدث خطأ ما!';
$_SESSION['messagetype'] = 'error';

// إشعار تحذير
$_SESSION['message'] = 'يرجى الانتباه!';
$_SESSION['messagetype'] = 'warning';

// إشعار معلومة
$_SESSION['message'] = 'معلومة مفيدة';
$_SESSION['messagetype'] = 'info';
```

---

### 3️⃣ من JavaScript

يمكنك عرض إشعارات من JavaScript مباشرة:

```javascript
// إشعار نجاح
notify.success('تم الحفظ بنجاح!');

// إشعار خطأ
notify.error('حدث خطأ في النظام!');

// إشعار تحذير
notify.warning('يرجى مراجعة البيانات!');

// إشعار معلومة
notify.info('نصيحة: يمكنك حفظ تقدمك');

// إشعار مخصص مع مدة محددة
notify.show('رسالة مخصصة', 'success', 10000); // 10 ثواني
```

---

## 🎨 أنواع الإشعارات

### 1. إشعار النجاح (Success) ✅
```php
$_SESSION['message'] = 'تم التسجيل بنجاح';
$_SESSION['messagetype'] = 'success';
```
- **اللون:** أخضر (#10b981)
- **الأيقونة:** علامة صح ✓
- **الاستخدام:** عمليات ناجحة (تسجيل، حفظ، تحديث، إضافة)

### 2. إشعار الخطأ (Error) ❌
```php
$_SESSION['message'] = 'فشلت عملية الحذف';
$_SESSION['messagetype'] = 'error';
```
- **اللون:** أحمر (#ef4444)
- **الأيقونة:** علامة X ✗
- **الاستخدام:** أخطاء، فشل العمليات

### 3. إشعار التحذير (Warning) ⚠️
```php
$_SESSION['message'] = 'البريد الإلكتروني موجود مسبقاً';
$_SESSION['messagetype'] = 'warning';
```
- **اللون:** برتقالي (#f59e0b)
- **الأيقونة:** مثلث تحذير ⚠
- **الاستخدام:** تحذيرات، تنبيهات

### 4. إشعار المعلومات (Info) ℹ️
```php
$_SESSION['message'] = 'يرجى ملء جميع الحقول';
$_SESSION['messagetype'] = 'info';
```
- **اللون:** أزرق (#3b82f6)
- **الأيقونة:** معلومة ℹ
- **الاستخدام:** معلومات عامة، نصائح

---

## 🔧 التخصيص المتقدم

### تغيير مدة العرض:

```javascript
// عرض لمدة 3 ثواني بدلاً من 5
notify.success('رسالة سريعة', 3000);

// عرض دائم (حتى الإغلاق اليدوي)
notify.error('خطأ حرج!', 0);

// عرض لمدة 10 ثواني
notify.info('معلومة مهمة', 10000);
```

### حذف جميع الإشعارات:

```javascript
notify.clear();
```

### دوال مساعدة سريعة:

```javascript
// دوال قصيرة
showSuccess('نجح!');
showError('فشل!');
showWarning('انتبه!');
showInfo('معلومة');
```

---

## 📊 أمثلة عملية

### مثال 1: تسجيل مستخدم جديد

```php
// في process/register.php
if ($stmt->execute()) {
    $_SESSION['message'] = 'تم إنشاء الحساب بنجاح! يمكنك تسجيل الدخول الآن';
    $_SESSION['messagetype'] = 'success';
    header("Location: ../login.php");
} else {
    $_SESSION['message'] = 'حدث خطأ أثناء التسجيل. يرجى المحاولة مرة أخرى';
    $_SESSION['messagetype'] = 'error';
    header("Location: ../register.php");
}
```

### مثال 2: إضافة وظيفة

```php
// في process/newJob.php
if ($success) {
    $_SESSION['message'] = 'تم نشر الوظيفة بنجاح! ستظهر في قائمة الوظائف';
    $_SESSION['messagetype'] = 'success';
} else {
    $_SESSION['message'] = 'فشل نشر الوظيفة. تحقق من البيانات المدخلة';
    $_SESSION['messagetype'] = 'error';
}
header("Location: ../dashboard/manageJobs.php");
```

### مثال 3: حذف عنصر

```php
// في process/deleteJobs.php
if ($can_delete) {
    if ($stmt->execute()) {
        $_SESSION['message'] = 'تم حذف الوظيفة بنجاح';
        $_SESSION['messagetype'] = 'success';
    } else {
        $_SESSION['message'] = 'حدث خطأ أثناء الحذف';
        $_SESSION['messagetype'] = 'error';
    }
} else {
    $_SESSION['message'] = 'ليس لديك صلاحية حذف هذه الوظيفة';
    $_SESSION['messagetype'] = 'warning';
}
```

### مثال 4: تحديث ملف شخصي

```php
// في process/changeProfile.php
if (empty($fullname) || empty($email)) {
    $_SESSION['message'] = 'يرجى ملء جميع الحقول المطلوبة';
    $_SESSION['messagetype'] = 'warning';
} elseif (!isValidEmail($email)) {
    $_SESSION['message'] = 'البريد الإلكتروني غير صحيح';
    $_SESSION['messagetype'] = 'error';
} else {
    // التحديث
    $_SESSION['message'] = 'تم تحديث الملف الشخصي بنجاح';
    $_SESSION['messagetype'] = 'success';
}
```

---

## 🎯 التكامل مع جميع الصفحات

### الصفحات المدعومة تلقائياً:

النظام يعمل على **جميع الصفحات** التي تستخدم:
- ✅ `includes/indexHeader.php`
- ✅ `includes/indexHeaderr.php`

### الصفحات التي تحتاج إضافة يدوية:

إذا كانت هناك صفحات **لا** تستخدم الملفات أعلاه، أضف هذا الكود:

```php
<!-- في نهاية <head> -->
<link rel="stylesheet" href="./assets/css/notifications.css">
<script src="./assets/js/notifications.js"></script>
```

```php
<!-- بعد navbar مباشرة -->
<?php include "./includes/notification_display.php" ?>
```

---

## 🔍 الملفات التي تستخدم الإشعارات حالياً

تم التحقق من أن هذه الملفات **تستخدم بالفعل** `$_SESSION['message']`:

### ملفات Process:
```
✅ process/login.php
✅ process/register.php
✅ process/newJob.php
✅ process/submitReview.php
✅ process/applyJob.php
✅ process/updateJob.php
✅ process/changeResume.php
✅ process/deleteAdmins.php
✅ process/deleteCompany.php
✅ process/deleteJobs.php
✅ process/deleteUsers.php
```

### ملفات Dashboard:
```
✅ dashboard/dashboard.php
✅ dashboard/editProfile.php
✅ dashboard/editJob.php
```

**جميع هذه الملفات ستعرض الإشعارات تلقائياً!** ✅

---

## ⚙️ التخصيص والألوان

### تغيير الألوان:

افتح `assets/css/notifications.css` وعدّل:

```css
/* إشعار النجاح */
.notification.success {
    border-left-color: #10b981; /* غيّر هذا */
}

/* إشعار الخطأ */
.notification.error {
    border-left-color: #ef4444; /* غيّر هذا */
}

/* إشعار التحذير */
.notification.warning {
    border-left-color: #f59e0b; /* غيّر هذا */
}

/* إشعار المعلومات */
.notification.info {
    border-left-color: #3b82f6; /* غيّر هذا */
}
```

### تغيير الموقع:

```css
.notification-container {
    top: 80px;    /* المسافة من الأعلى */
    right: 20px;  /* المسافة من اليمين */
    /* لوضعه يساراً: */
    /* left: 20px; right: auto; */
}
```

### تغيير المدة الافتراضية:

افتح `assets/js/notifications.js` وعدّل:

```javascript
show(message, type = 'info', duration = 5000) // غيّر 5000 إلى المدة المطلوبة بالميلي ثانية
```

---

## 📱 التجاوب مع الشاشات

النظام متجاوب تماماً:

- ✅ **Desktop:** أعلى يمين الشاشة
- ✅ **Tablet:** أعلى يمين بعرض كامل
- ✅ **Mobile:** أعلى الشاشة بعرض كامل

---

## 🐛 استكشاف الأخطاء

### المشكلة: الإشعارات لا تظهر

**الحل 1:** تحقق من تضمين الملفات في `<head>`:
```html
<link rel="stylesheet" href="./assets/css/notifications.css">
<script src="./assets/js/notifications.js"></script>
```

**الحل 2:** تحقق من إضافة كود العرض بعد navbar:
```php
<?php include "./includes/notification_display.php" ?>
```

**الحل 3:** تحقق من المسار الصحيح للملفات:
- للصفحات في المجلد الرئيسي: `./assets/`
- للصفحات في مجلدات فرعية: `../assets/`

### المشكلة: الإشعار يظهر ولا يختفي

**الحل:** قد تكون المدة `0`، اضبطها:
```javascript
notify.success('رسالة', 5000); // 5 ثواني
```

### المشكلة: الإشعار لا يظهر من PHP

**الحل:** تأكد من تعيين الرسالة قبل `header()`:
```php
$_SESSION['message'] = 'الرسالة';
$_SESSION['messagetype'] = 'success';
header("Location: page.php");
exit();
```

---

## ✅ قائمة التحقق

### للمطورين:
- [x] تم تثبيت ملفات CSS/JS
- [x] تم تحديث ملفات Header
- [x] تم إضافة notification_display.php
- [x] تم اختبار جميع أنواع الإشعارات
- [x] النظام متوافق مع الكود الحالي
- [x] لم يتم كسر أي وظيفة موجودة

### للمستخدمين:
- [x] الإشعارات تظهر بعد العمليات
- [x] التصميم متجاوب
- [x] يمكن إغلاق الإشعارات يدوياً
- [x] تختفي تلقائياً بعد 5 ثواني
- [x] تعمل على جميع الصفحات

---

## 🎉 الخلاصة

### ✅ تم بنجاح:
- ✅ **نظام إشعارات متكامل** جاهز للاستخدام
- ✅ **لا يؤثر على أي كود موجود**
- ✅ **يعمل تلقائياً** مع Session messages
- ✅ **سهل الاستخدام** من PHP و JavaScript
- ✅ **تصميم عصري** ومتجاوب
- ✅ **موثق بالكامل**

### 📊 الإحصائيات:
- **ملفات جديدة:** 3 ملفات (CSS, JS, PHP)
- **ملفات محدثة:** 3 ملفات
- **أسطر كود مضافة:** 500+ سطر
- **أنواع إشعارات:** 4 أنواع
- **الوقت المطلوب:** جاهز فوراً!

---

**تم الإنجاز بنجاح!** 🎊  
**النظام جاهز للاستخدام فوراً!** 🚀

**آخر تحديث:** 2025-10-25
