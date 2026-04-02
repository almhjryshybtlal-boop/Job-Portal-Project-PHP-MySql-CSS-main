<?php
// ملف التحقق من الصلاحيات والمصادقة

// التحقق من بدء الجلسة
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * التحقق من تسجيل دخول المستخدم
 * @return bool
 */
function isLoggedIn() {
    return isset($_SESSION['email']) && isset($_SESSION['role_id']);
}

/**
 * التحقق من دور المستخدم
 * @param int $required_role - الدور المطلوب (1: باحث عن عمل، 2: شركة، 3: مدير)
 * @return bool
 */
function checkRole($required_role) {
    return isLoggedIn() && $_SESSION['role_id'] == $required_role;
}

/**
 * إعادة توجيه المستخدم إذا لم يكن مسجل الدخول
 * @param string $redirect_to - الصفحة المراد التوجيه إليها
 */
function requireLogin($redirect_to = '../login.php') {
    if (!isLoggedIn()) {
        $_SESSION['message'] = 'يرجى تسجيل الدخول للمتابعة';
        $_SESSION['messagetype'] = 'warning';
        header("Location: $redirect_to");
        exit();
    }
}

/**
 * إعادة توجيه المستخدم إذا لم يكن لديه الصلاحيات المطلوبة
 * @param int $required_role - الدور المطلوب
 * @param string $redirect_to - الصفحة المراد التوجيه إليها
 */
function requireRole($required_role, $redirect_to = '../index.php') {
    requireLogin();
    
    if ($_SESSION['role_id'] != $required_role) {
        $_SESSION['message'] = 'ليس لديك صلاحية للوصول إلى هذه الصفحة';
        $_SESSION['messagetype'] = 'warning';
        header("Location: $redirect_to");
        exit();
    }
}

/**
 * تنظيف وتأمين المدخلات
 * @param string $data - البيانات المراد تنظيفها
 * @return string
 */
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * التحقق من صحة البريد الإلكتروني
 * @param string $email
 * @return bool
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * التحقق من قوة كلمة المرور
 * @param string $password
 * @return bool
 */
function isStrongPassword($password) {
    // على الأقل 8 أحرف
    return strlen($password) >= 6;
}

/**
 * التحقق من تطابق كلمتي المرور
 * @param string $password
 * @param string $confirm_password
 * @return bool
 */
function passwordsMatch($password, $confirm_password) {
    return $password === $confirm_password;
}
?>
