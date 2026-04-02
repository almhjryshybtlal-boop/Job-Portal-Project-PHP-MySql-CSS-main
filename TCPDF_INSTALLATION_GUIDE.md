# 📚 دليل تثبيت مكتبة TCPDF لمشروع بوابة الوظائف

## ⚠️ **مهم جداً:**
مكتبة TCPDF مطلوبة لتوليد تقارير PDF في المشروع. بدونها، ميزة التقارير لن تعمل.

---

## 🔧 **خطوات التثبيت:**

### **الطريقة 1: التحميل المباشر (موصى بها)**

1. **تحميل المكتبة:**
   - اذهب إلى: https://github.com/tecnickcom/TCPDF/releases
   - حمّل آخر إصدار (مثلاً: `TCPDF-6.6.5.zip`)

2. **فك الضغط:**
   - فك ضغط الملف المحمل
   - ستحصل على مجلد اسمه `TCPDF-6.x.x` أو `tcpdf`

3. **نقل المجلد:**
   ```
   انقل مجلد tcpdf إلى:
   c:\xampp\htdocs\Job-Portal-Project-PHP-MySql-CSS-main\tcpdf\
   ```

4. **التأكد من المسار:**
   ```
   يجب أن يكون المسار النهائي:
   c:\xampp\htdocs\Job-Portal-Project-PHP-MySql-CSS-main\tcpdf\tcpdf.php
   ```

### **الطريقة 2: باستخدام Composer (إذا كان متوفراً)**

```bash
cd c:\xampp\htdocs\Job-Portal-Project-PHP-MySql-CSS-main
composer require tecnickcom/tcpdf
```

---

## ✅ **التحقق من التثبيت:**

### **1. إنشاء ملف اختبار:**
أنشئ ملف `test_tcpdf.php` في المجلد الرئيسي:

```php
<?php
require_once('tcpdf/tcpdf.php');

// إنشاء PDF جديد
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// إعدادات المستند
$pdf->SetCreator('Job Portal');
$pdf->SetTitle('اختبار TCPDF');

// إضافة صفحة
$pdf->AddPage();

// كتابة نص
$pdf->SetFont('helvetica', 'B', 20);
$pdf->Cell(0, 10, 'مرحباً! TCPDF يعمل بنجاح', 0, 1, 'C');

// عرض PDF
$pdf->Output('test.pdf', 'I');
?>
```

### **2. اختبار الملف:**
- افتح المتصفح
- اذهب إلى: `http://localhost/Job-Portal-Project-PHP-MySql-CSS-main/test_tcpdf.php`
- يجب أن ترى ملف PDF يحتوي على "مرحباً! TCPDF يعمل بنجاح"

---

## 🎥 **فيديو تعليمي:**

شاهد هذا الفيديو للمساعدة:
https://youtu.be/Q1iGTtMspho?si=f7XJ_I8wEJxiiaJb

---

## ❗ **مشاكل شائعة وحلولها:**

### **المشكلة 1: خطأ "Class 'TCPDF' not found"**
**الحل:**
- تأكد من المسار الصحيح في `require_once`
- يجب أن يكون: `require_once('tcpdf/tcpdf.php')`
- تحقق من وجود ملف `tcpdf.php` في المجلد

### **المشكلة 2: خطأ في الصور**
**الحل:**
- تأكد من أذونات المجلد (chmod 755)
- تحقق من مسار الصور المستخدمة

### **المشكلة 3: اللغة العربية لا تظهر**
**الحل:**
- استخدم خطوط Unicode
- أضف هذا السطر بعد إنشاء TCPDF:
  ```php
  $pdf->SetFont('dejavusans', '', 12);
  ```

---

## 📁 **الملفات التي تستخدم TCPDF:**

1. `process/applyJob.php` - تقرير التقديم على الوظيفة
2. `Report Generation/Admin/pdf-generator.php` - توليد تقارير المدير

---

## 🔐 **ملاحظات أمنية:**

⚠️ **تحذير:** تم تعطيل ميزة PDF مؤقتاً في `applyJob.php` حتى يتم تثبيت TCPDF

لإعادة تفعيلها:
1. ثبت TCPDF كما هو موضح أعلاه
2. ستعمل الميزة تلقائياً

---

## ✨ **بعد التثبيت:**

بعد تثبيت TCPDF بنجاح، ستتمكن من:
- ✅ توليد تقارير PDF للتقديمات
- ✅ تصدير بيانات الوظائف
- ✅ إنشاء تقارير للمدير
- ✅ طباعة معلومات الشركات

---

## 📞 **الدعم:**

إذا واجهت أي مشاكل:
1. راجع الفيديو التعليمي
2. تحقق من المسارات
3. تأكد من نسخة PHP (يجب أن تكون >= 7.0)

**ملاحظة:** المشروع الآن يعمل بدون TCPDF، لكن ميزة التقارير معطلة مؤقتاً.
