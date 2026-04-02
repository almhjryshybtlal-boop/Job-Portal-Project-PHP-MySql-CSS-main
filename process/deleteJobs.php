<?php 
include "../includes/session.php";
require_once "../includes/auth_check.php";

// التحقق من أن المستخدم مسجل دخول
requireLogin('../login.php');

if (isset($_GET['id'])) {
  $id_jobpost = intval($_GET['id']);
  
  // التحقق من الصلاحيات
  // المدير يستطيع حذف أي وظيفة، الشركات فقط ووظائفها
  $can_delete = false;
  
  if ($_SESSION['role_id'] == 3) {
    // المدير يستطيع حذف أي وظيفة
    $can_delete = true;
  } elseif ($_SESSION['role_id'] == 2 && isset($_SESSION['id_company'])) {
    // الشركة يمكنها حذف وظائفها فقط
    $check_sql = "SELECT id_jobpost FROM job_post WHERE id_jobpost = ? AND id_company = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $id_jobpost, $_SESSION['id_company']);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
      $can_delete = true;
    }
    $check_stmt->close();
  }
  
  if ($can_delete) {
    // حذف طلبات التقديم التابعة للوظيفة أولاً
    $delete_apply_sql = "DELETE FROM apply_job_post WHERE id_jobpost = ?";
    $delete_apply_stmt = $conn->prepare($delete_apply_sql);
    $delete_apply_stmt->bind_param("i", $id_jobpost);
    $delete_apply_stmt->execute();
    $delete_apply_stmt->close();
    
    // حذف الوظيفة
    $sql = "DELETE FROM job_post WHERE id_jobpost = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
      $stmt->bind_param("i", $id_jobpost);
      if ($stmt->execute()) {
        $_SESSION['message'] = 'تم حذف الوظيفة بنجاح';
        $_SESSION['messagetype'] = 'success';
      } else {
        $_SESSION['message'] = 'حدث خطأ أثناء الحذف';
        $_SESSION['messagetype'] = 'error';
      }
      $stmt->close();
    }
  } else {
    $_SESSION['message'] = 'ليس لديك صلاحية حذف هذه الوظيفة';
    $_SESSION['messagetype'] = 'warning';
  }

  // إعادة التوجيه بناءً على وجود 'page'
  if (isset($_GET['page'])) {
    header("Location: ../dashboard/allJobPost.php");
  } else {
    header("Location: ../dashboard/manageJobs.php");
  }
  exit();
}

$_SESSION['message'] = 'طلب غير صحيح';
$_SESSION['messagetype'] = 'error';
header("Location: ../dashboard/manageJobs.php");
exit();
