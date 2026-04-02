<?php 
include "../includes/session.php";
require_once "../includes/auth_check.php";

// التحقق من أن المستخدم مسجل دخول وهو مدير (role_id = 3)
requireRole(3, '../index.php');

if (isset($_GET['page']) && $_GET['page'] == "delete-admins") {
  if (isset($_GET['id'])) {
    $id_admin = intval($_GET['id']);
    
    // التحقق من أن المدير لا يحذف نفسه
    if (isset($_SESSION['id_admin']) && $id_admin == $_SESSION['id_admin']) {
      $_SESSION['message'] = 'لا يمكنك حذف حسابك الخاص';
      $_SESSION['messagetype'] = 'warning';
      header("Location: ../dashboard/adminUsers.php");
      exit();
    }
    
    // التحقق من وجود المدير قبل الحذف
    $check_sql = "SELECT id_admin FROM admin WHERE id_admin = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $id_admin);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
      $sql = "DELETE FROM admin WHERE id_admin = ?";
      $stmt = $conn->prepare($sql);
      
      if ($stmt) {
          $stmt->bind_param("i", $id_admin);
          if ($stmt->execute()) {
            $_SESSION['message'] = 'تم حذف المدير بنجاح';
            $_SESSION['messagetype'] = 'success';
          } else {
            $_SESSION['message'] = 'حدث خطأ أثناء الحذف';
            $_SESSION['messagetype'] = 'error';
          }
          $stmt->close();
      }
    } else {
      $_SESSION['message'] = 'المدير غير موجود';
      $_SESSION['messagetype'] = 'error';
    }
    $check_stmt->close();
    
    header("Location: ../dashboard/adminUsers.php");
    exit();
  }
}

$_SESSION['message'] = 'طلب غير صحيح';
$_SESSION['messagetype'] = 'error';
header("Location: ../dashboard/adminUsers.php");
exit();