<?php 
include "../includes/session.php";
require_once "../includes/auth_check.php";

// التحقق من أن المستخدم مسجل دخول وهو مدير (role_id = 3)
requireRole(3, '../index.php');

if (isset($_GET['page']) && $_GET['page'] == "delete-jobseekers") {
  if (isset($_GET['id'])) {
    $id_user = intval($_GET['id']);
    
    // التحقق من وجود المستخدم قبل الحذف
    $check_sql = "SELECT id_user FROM users WHERE id_user = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $id_user);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
      // حذف طلبات التقديم التابعة للمستخدم أولاً
      $delete_apply_sql = "DELETE FROM apply_job_post WHERE id_user = ?";
      $delete_apply_stmt = $conn->prepare($delete_apply_sql);
      $delete_apply_stmt->bind_param("i", $id_user);
      $delete_apply_stmt->execute();
      $delete_apply_stmt->close();
      
      // حذف المستخدم
      $sql = "DELETE FROM users WHERE id_user = ?";
      $stmt = $conn->prepare($sql);

      if ($stmt) {
          $stmt->bind_param("i", $id_user);
          if ($stmt->execute()) {
            $_SESSION['message'] = 'تم حذف المستخدم بنجاح';
            $_SESSION['messagetype'] = 'success';
          } else {
            $_SESSION['message'] = 'حدث خطأ أثناء الحذف';
            $_SESSION['messagetype'] = 'error';
          }
          $stmt->close();
      }
    } else {
      $_SESSION['message'] = 'المستخدم غير موجود';
      $_SESSION['messagetype'] = 'error';
    }
    $check_stmt->close();

    header("Location: ../dashboard/allJobSeekers.php");
    exit();
  }
}

$_SESSION['message'] = 'طلب غير صحيح';
$_SESSION['messagetype'] = 'error';
header("Location: ../dashboard/allJobSeekers.php");
exit();
