<?php
include "../includes/session.php";

// دالة مُحسَّنة لتحديث كلمة المرور في أي جدول
// تستخدم Prepared Statements لمنع حقن SQL
function updatePassword($conn, $tableName, $email, $newPassword) {
  $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
  
  $sql = "UPDATE $tableName SET password = ? WHERE email = ?";
  $stmt = $conn->prepare($sql);
  
  if ($stmt) {
    $stmt->bind_param("ss", $hashedPassword, $email);
    
    if ($stmt->execute()) {
      echo "Password Updated successfully!!";
    } else {
      echo "Error updating password: " . $stmt->error;
    }
    $stmt->close();
  } else {
    echo "Error preparing statement: " . $conn->error;
  }
}

// استدعاء الدالة حسب نوع المستخدم
if (isset($_POST['myPassword'])) {
  $email = $_SESSION['email'];
  $newPassword = $_POST['newPassword'];
  updatePassword($conn, "users", $email, $newPassword);
} elseif (isset($_POST['companyPassword'])) {
  $email = $_SESSION['email'];
  $newPassword = $_POST['newPassword'];
  updatePassword($conn, "company", $email, $newPassword);
} elseif (isset($_POST['aPassword'])) {
  $email = $_SESSION['email'];
  $newPassword = $_POST['newPassword'];
  updatePassword($conn, "admin", $email, $newPassword);
}

header('location: ../index.php');
exit();
