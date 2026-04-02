<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اختبار الإشعارات بالعربية</title>
    
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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            direction: rtl;
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
            text-align: right;
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
            direction: rtl;
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
            direction: ltr;
            text-align: left;
        }
        
        .code-block pre {
            margin: 0;
            color: #333;
        }
        
        .info-box {
            background: #e0f2fe;
            border-right: 4px solid #3b82f6;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
        }
        
        .info-box h3 {
            color: #1e40af;
            margin-bottom: 10px;
            text-align: right;
        }
        
        .info-box p {
            color: #1e3a8a;
            line-height: 1.6;
            text-align: right;
        }
        
        .rtl-text {
            direction: rtl;
            text-align: right;
        }
    </style>
</head>
<body>
    <!-- عرض الإشعارات -->
    <?php include "./includes/notification_display.php" ?>
    
    <div class="container">
        <h1>🔔 اختبار الإشعارات بالعربية</h1>
        <p class="subtitle">جميع الإشعارات تظهر باللغة العربية الآن</p>
        
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
$_SESSION[\'message\'] = \'تم تنفيذ العملية بنجاح\';
$_SESSION[\'messagetype\'] = \'success\';
header("Location: page.php");'); ?></pre>
            </div>
        </div>
        
        <!-- JavaScript Notifications -->
        <div class="section">
            <h2>2️⃣ إشعارات JavaScript</h2>
            <div class="btn-group">
                <button onclick="notify.success('تم حفظ البيانات بنجاح! 💾')" class="btn btn-js">
                    <i class="fa-solid fa-code"></i>
                    نجاح JS
                </button>
                <button onclick="notify.error('فشلت عملية الحفظ! ❌')" class="btn btn-js">
                    <i class="fa-solid fa-code"></i>
                    خطأ JS
                </button>
                <button onclick="notify.warning('يرجى التحقق من البيانات! ⚠️')" class="btn btn-js">
                    <i class="fa-solid fa-code"></i>
                    تحذير JS
                </button>
                <button onclick="notify.info('هذه معلومة مهمة! 💡')" class="btn btn-js">
                    <i class="fa-solid fa-code"></i>
                    معلومة JS
                </button>
            </div>
            
            <div class="code-block">
                <pre><?php echo htmlspecialchars('// في JavaScript
notify.success(\'تم الحفظ بنجاح\');
notify.error(\'حدث خطأ\');
notify.warning(\'تحذير\');
notify.info(\'معلومة\');'); ?></pre>
            </div>
        </div>
        
        <!-- Info Box -->
        <div class="info-box">
            <h3>📚 معلومات مهمة:</h3>
            <p class="rtl-text">
                • جميع الإشعارات تظهر الآن باللغة العربية<br>
                • العناوين: نجح! ✓ | خطأ! ✗ | تحذير! ⚠ | معلومة ℹ<br>
                • النص محاذي لليمين ومناسب للغة العربية<br>
                • الأيقونة في الجهة اليسرى والزر في الجهة اليمنى<br>
                • النظام يعمل تلقائياً مع جميع عمليات المشروع
            </p>
        </div>
    </div>
    
    <script>
        // إضافة تأثيرات صوتية (اختياري)
        console.log('🔔 نظام الإشعارات جاهز!');
        console.log('جميع الإشعارات تظهر باللغة العربية الآن!');
    </script>
</body>
</html>

<?php
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
    
    // إعادة التوجيه لعرض الإشعار
    header("Location: " . strtok($_SERVER['REQUEST_URI'], '?'));
    exit();
}
?>