<?php
session_start();

// تعيين رسالة اختبارية بناءً على المعامل
if (isset($_GET['type'])) {
    $type = $_GET['type'];
    
    switch($type) {
        case 'success':
            $_SESSION['message'] = 'تم تنفيذ العملية بنجاح! ✓';
            $_SESSION['messagetype'] = 'success';
            break;
        case 'error':
            $_SESSION['message'] = 'حدث خطأ أثناء تنفيذ العملية! ✗';
            $_SESSION['messagetype'] = 'error';
            break;
        case 'warning':
            $_SESSION['message'] = 'تحذير: يرجى مراجعة البيانات المدخلة! ⚠';
            $_SESSION['messagetype'] = 'warning';
            break;
        case 'info':
            $_SESSION['message'] = 'معلومة: تذكر حفظ التغييرات قبل المغادرة ℹ';
            $_SESSION['messagetype'] = 'info';
            break;
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اختبار نظام الإشعارات</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Notification System -->
    <link rel="stylesheet" href="./assets/css/notifications.css">
    <script src="./assets/js/notifications.js"></script>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 20px;
            padding: 40px;
            max-width: 800px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 10px;
            font-size: 32px;
        }
        
        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 40px;
            font-size: 16px;
        }
        
        .section {
            margin-bottom: 40px;
        }
        
        .section h2 {
            color: #444;
            margin-bottom: 20px;
            font-size: 24px;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }
        
        .btn-group {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .btn {
            padding: 15px 25px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        }
        
        .btn-success {
            background: linear-gradient(135deg, #10b981, #059669);
        }
        
        .btn-error {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }
        
        .btn-warning {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }
        
        .btn-info {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
        }
        
        .btn-js {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        }
        
        .code-block {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            overflow-x: auto;
        }
        
        .code-block pre {
            margin: 0;
            color: #333;
        }
        
        .info-box {
            background: #e0f2fe;
            border-left: 4px solid #3b82f6;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
        }
        
        .info-box h3 {
            color: #1e40af;
            margin-bottom: 10px;
        }
        
        .info-box p {
            color: #1e3a8a;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <!-- عرض الإشعارات -->
    <?php include "./includes/notification_display.php" ?>
    
    <div class="container">
        <h1>🔔 اختبار نظام الإشعارات</h1>
        <p class="subtitle">اختبر جميع أنواع الإشعارات في المشروع</p>
        
        <!-- PHP Notifications -->
        <div class="section">
            <h2>1️⃣ إشعارات PHP (من Session)</h2>
            <div class="btn-group">
                <a href="?type=success" class="btn btn-success">
                    <i class="fa-solid fa-circle-check"></i>
                    إشعار نجاح
                </a>
                <a href="?type=error" class="btn btn-error">
                    <i class="fa-solid fa-circle-xmark"></i>
                    إشعار خطأ
                </a>
                <a href="?type=warning" class="btn btn-warning">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    إشعار تحذير
                </a>
                <a href="?type=info" class="btn btn-info">
                    <i class="fa-solid fa-circle-info"></i>
                    إشعار معلومة
                </a>
            </div>
            
            <div class="code-block">
                <pre><?php echo htmlspecialchars('// في ملف PHP
$_SESSION[\'message\'] = \'تم التنفيذ بنجاح\';
$_SESSION[\'messagetype\'] = \'success\';
header("Location: page.php");'); ?></pre>
            </div>
        </div>
        
        <!-- JavaScript Notifications -->
        <div class="section">
            <h2>2️⃣ إشعارات JavaScript</h2>
            <div class="btn-group">
                <button onclick="notify.success('تم الحفظ بنجاح! 💾')" class="btn btn-js">
                    <i class="fa-solid fa-code"></i>
                    نجاح JS
                </button>
                <button onclick="notify.error('فشلت العملية! ❌')" class="btn btn-js">
                    <i class="fa-solid fa-code"></i>
                    خطأ JS
                </button>
                <button onclick="notify.warning('يرجى الانتباه! ⚠️')" class="btn btn-js">
                    <i class="fa-solid fa-code"></i>
                    تحذير JS
                </button>
                <button onclick="notify.info('معلومة مفيدة! 💡')" class="btn btn-js">
                    <i class="fa-solid fa-code"></i>
                    معلومة JS
                </button>
            </div>
            
            <div class="code-block">
                <pre><?php echo htmlspecialchars('// في JavaScript
notify.success(\'رسالة النجاح\');
notify.error(\'رسالة الخطأ\');
notify.warning(\'رسالة التحذير\');
notify.info(\'رسالة المعلومة\');'); ?></pre>
            </div>
        </div>
        
        <!-- Advanced Features -->
        <div class="section">
            <h2>3️⃣ ميزات متقدمة</h2>
            <div class="btn-group">
                <button onclick="notify.success('إشعار سريع (3 ثواني)', 3000)" class="btn btn-js">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    3 ثواني
                </button>
                <button onclick="notify.info('إشعار طويل (10 ثواني)', 10000)" class="btn btn-js">
                    <i class="fa-solid fa-clock"></i>
                    10 ثواني
                </button>
                <button onclick="notify.warning('إشعار دائم (حتى الإغلاق)', 0)" class="btn btn-js">
                    <i class="fa-solid fa-infinity"></i>
                    دائم
                </button>
                <button onclick="notify.clear()" class="btn btn-error">
                    <i class="fa-solid fa-trash"></i>
                    حذف الكل
                </button>
            </div>
            
            <div class="code-block">
                <pre><?php echo htmlspecialchars('// مع مدة مخصصة (بالميلي ثانية)
notify.success(\'رسالة\', 3000);  // 3 ثواني
notify.info(\'رسالة\', 10000);     // 10 ثواني
notify.error(\'رسالة\', 0);        // دائم

// حذف جميع الإشعارات
notify.clear();'); ?></pre>
            </div>
        </div>
        
        <!-- Info Box -->
        <div class="info-box">
            <h3>📚 معلومات مهمة:</h3>
            <p>
                • النظام يعمل <strong>تلقائياً</strong> مع جميع ملفات المشروع التي تستخدم $_SESSION['message']<br>
                • يمكن استخدامه من <strong>PHP</strong> أو <strong>JavaScript</strong> بسهولة<br>
                • التصميم <strong>متجاوب</strong> لجميع الشاشات<br>
                • راجع <code>NOTIFICATIONS_GUIDE.md</code> للتفاصيل الكاملة
            </p>
        </div>
    </div>
    
    <script>
        // إضافة تأثيرات صوتية (اختياري)
        console.log('🔔 نظام الإشعارات جاهز!');
        console.log('استخدم notify.success(), notify.error(), notify.warning(), notify.info()');
    </script>
</body>
</html>
