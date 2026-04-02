<?php
include "../includes/session.php";
require_once "../includes/auth_check.php";

// التحقق من أن المستخدم مسجل دخول وهو باحث عن عمل (role_id = 1)
requireRole(1, '../login.php');

if (isset($_POST['changeResume'])) {
  $email = sanitizeInput($_SESSION['email']);
  $resume = $_FILES['resume']['name'];

  // التحقق من رفع الملف
  if (isset($_FILES['resume']) && $_FILES['resume']['error'] == 0) {
    $allowed_ext = ['pdf'];
    $max_size = 5 * 1024 * 1024; // 5MB
    
    $file_ext = strtolower(pathinfo($resume, PATHINFO_EXTENSION));
    $file_size = $_FILES['resume']['size'];
    
    // التحقق من صحة الملف
    if (!in_array($file_ext, $allowed_ext)) {
      $_SESSION['message'] = 'يُسمح فقط بملفات PDF';
      $_SESSION['messagetype'] = 'warning';
      header('Location: ../dashboard/myResume.php');
      exit();
    }
    
    if ($file_size > $max_size) {
      $_SESSION['message'] = 'حجم الملف يجب أن لا يتجاوز 5 ميجابايت';
      $_SESSION['messagetype'] = 'warning';
      header('Location: ../dashboard/myResume.php');
      exit();
    }
    
    // إنشاء اسم ملف آمن
    $hash = md5($email . time());
    $safe_filename = $hash . '.' . $file_ext;
    $upload_path = '../assets/resume/' . $safe_filename;
    
    // حذف السيرة الذاتية القديمة إن وجدت
    $sql_old = "SELECT resume FROM users WHERE email = ?";
    $stmt_old = $conn->prepare($sql_old);
    $stmt_old->bind_param("s", $email);
    $stmt_old->execute();
    $result_old = $stmt_old->get_result();
    if ($row_old = $result_old->fetch_assoc()) {
      if (!empty($row_old['resume']) && file_exists('../assets/resume/' . $row_old['resume'])) {
        unlink('../assets/resume/' . $row_old['resume']);
      }
    }
    $stmt_old->close();
    
    // رفع الملف الجديد
    if (move_uploaded_file($_FILES['resume']['tmp_name'], $upload_path)) {
      $sql = "UPDATE users SET resume = ? WHERE email = ?";
      $stmt = $conn->prepare($sql);

      if ($stmt) {
        $stmt->bind_param("ss", $safe_filename, $email);
        if ($stmt->execute()) {
          $_SESSION['message'] = 'تم تحديث السيرة الذاتية بنجاح';
          $_SESSION['messagetype'] = 'success';
        } else {
          $_SESSION['message'] = 'حدث خطأ أثناء تحديث السيرة الذاتية';
          $_SESSION['messagetype'] = 'error';
          // حذف الملف المرفوع في حالة فشل التحديث
          if (file_exists($upload_path)) {
            unlink($upload_path);
          }
        }
        $stmt->close();
      } else {
        $_SESSION['message'] = 'حدث خطأ في قاعدة البيانات';
        $_SESSION['messagetype'] = 'error';
        // حذف الملف المرفوع
        if (file_exists($upload_path)) {
          unlink($upload_path);
        }
      }
    } else {
      $_SESSION['message'] = 'حدث خطأ أثناء رفع الملف';
      $_SESSION['messagetype'] = 'error';
    }
  } else {
    $_SESSION['message'] = 'يرجى اختيار ملف للرفع';
    $_SESSION['messagetype'] = 'warning';
  }
}

header('Location: ../dashboard/myResume.php');
exit();
