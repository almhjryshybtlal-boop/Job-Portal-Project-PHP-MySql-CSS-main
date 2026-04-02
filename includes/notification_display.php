<?php
/**
 * ملف عرض الإشعارات من PHP Session
 * Notification Display Helper
 * 
 * يتم تضمين هذا الملف في جميع الصفحات لعرض رسائل النجاح/الفشل
 * من متغيرات الـ Session
 */

// التحقق من وجود رسائل في الـ Session
if (isset($_SESSION['message']) && !empty($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $messageType = isset($_SESSION['messagetype']) ? $_SESSION['messagetype'] : 'info';
    
    // تحويل أنواع الرسائل المختلفة إلى الأنواع القياسية
    $typeMapping = [
        'success' => 'success',
        'error' => 'error',
        'warning' => 'warning',
        'info' => 'info',
        'danger' => 'error',
        'primary' => 'info',
        'secondary' => 'info'
    ];
    
    $notificationType = isset($typeMapping[$messageType]) ? $typeMapping[$messageType] : 'info';
    
    // تنظيف الرسالة من HTML tags للأمان
    $cleanMessage = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
    
    // إنشاء JSON للبيانات
    $notificationData = json_encode([
        'message' => $cleanMessage,
        'type' => $notificationType
    ]);
    
    // عرض البيانات في عنصر مخفي
    echo '<script type="application/json" id="session-notification-data">' . $notificationData . '</script>';
    
    // حذف الرسالة من الـ Session بعد عرضها
    unset($_SESSION['message']);
    unset($_SESSION['messagetype']);
}
?>

<!-- تضمين ملفات CSS و JavaScript للإشعارات -->
<link rel="stylesheet" href="<?php echo (isset($isSubfolder) && $isSubfolder) ? '../' : './'; ?>assets/css/notifications.css">
<script src="<?php echo (isset($isSubfolder) && $isSubfolder) ? '../' : './'; ?>assets/js/notifications.js"></script>
