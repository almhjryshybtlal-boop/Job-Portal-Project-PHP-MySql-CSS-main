<?php 
include "../includes/session.php";
require_once "../includes/auth_check.php";

// التحقق من أن المستخدم مسجل دخول وهو مدير (role_id = 3)
requireRole(3, '../index.php');

if (isset($_GET['id'])) {
  $id_company = intval($_GET['id']);
  
  // التحقق من وجود الشركة قبل الحذف
  $check_sql = "SELECT id_company FROM company WHERE id_company = ?";
  $check_stmt = $conn->prepare($check_sql);
  $check_stmt->bind_param("i", $id_company);
  $check_stmt->execute();
  $check_result = $check_stmt->get_result();
  
  if ($check_result->num_rows > 0) {
    // حذف الوظائف التابعة للشركة أولاً
    $delete_jobs_sql = "DELETE FROM job_post WHERE id_company = ?";
    $delete_jobs_stmt = $conn->prepare($delete_jobs_sql);
    $delete_jobs_stmt->bind_param("i", $id_company);
    $delete_jobs_stmt->execute();
    $delete_jobs_stmt->close();
    
    // حذف الشركة
    $sql = "DELETE FROM company WHERE id_company = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
      $stmt->bind_param("i", $id_company);
      if ($stmt->execute()) {
        $_SESSION['message'] = 'تم حذف الشركة وجميع وظائفها بنجاح';
        $_SESSION['messagetype'] = 'success';
      } else {
        $_SESSION['message'] = 'حدث خطأ أثناء الحذف';
        $_SESSION['messagetype'] = 'error';
      }
      $stmt->close();
    }
  } else {
    $_SESSION['message'] = 'الشركة غير موجودة';
    $_SESSION['messagetype'] = 'error';
  }
  $check_stmt->close();

  header("Location: ../dashboard/allCompanies.php");
  exit();
}

$_SESSION['message'] = 'طلب غير صحيح';
$_SESSION['messagetype'] = 'error';
header("Location: ../dashboard/allCompanies.php");
exit();
