<?php
include "../includes/session.php";
require_once "../includes/auth_check.php";

if (isset($_POST['registerbtn'])) {
  $fullname = sanitizeInput($_POST['fullname']);
  $email = sanitizeInput($_POST['email']);
  $acctype = $_POST['acctype'];
  $password = $_POST['password'];
  $password_repeat = $_POST['passwordrepeat'];
  
  // التحقق من صحة البيانات
  if (empty($fullname) || empty($email) || empty($password)) {
    $_SESSION['message'] = 'يرجى ملء جميع الحقول';
    $_SESSION['messagetype'] = 'warning';
    header('location: ../login.php');
    exit();
  }
  
  if (!isValidEmail($email)) {
    $_SESSION['message'] = 'البريد الإلكتروني غير صحيح';
    $_SESSION['messagetype'] = 'warning';
    header('location: ../login.php');
    exit();
  }
  
  if (!isStrongPassword($password)) {
    $_SESSION['message'] = 'كلمة المرور يجب أن تكون 6 أحرف على الأقل';
    $_SESSION['messagetype'] = 'warning';
    header('location: ../login.php');
    exit();
  }
  
  if (!passwordsMatch($password, $password_repeat)) {
    $_SESSION['message'] = 'كلمتا المرور غير متطابقتين';
    $_SESSION['messagetype'] = 'warning';
    header('location: ../login.php');
    exit();
  }
  
  $password = password_hash($password, PASSWORD_DEFAULT);
  $createdat = date('Y-m-d');

  //getting role id
  if ($acctype === "Applicant") {
    $acctype = 1;
    $tableName = "users";
    $columnName = "fullname";
  }
  else if($acctype === "Employer") {
    $acctype = 2;
    $tableName = "company";
    $columnName = "companyname";
  }
  else
  {
     $acctype = 3;
    $tableName = "admin";
    $columnName = "fullname";
  }

  // استخدام Prepared Statements للتحقق من وجود البريد الإلكتروني
  $sql_check = "SELECT * FROM $tableName WHERE email=?";
  $stmt_check = $conn->prepare($sql_check);
  $stmt_check->bind_param("s", $email);
  $stmt_check->execute();
  $query = $stmt_check->get_result();
  
  if ($query->num_rows > 0) {
    $_SESSION['message'] = 'Email Address Already Exits!! Please Login.!';
    $_SESSION['messagetype'] = 'warning';
    header('location: ../login.php');
    exit();
  } else {
    // إدراج المستخدم الجديد باستخدام Prepared Statements
    $sql_insert = "INSERT INTO $tableName ($columnName, email, role_id, password, createdat) VALUES (?, ?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    
    if ($stmt_insert) {
        $stmt_insert->bind_param("ssiss", $fullname, $email, $acctype, $password, $createdat);
        if ($stmt_insert->execute()) {
            $_SESSION['message'] = 'Account created successfully. Please login to continue.';
            $_SESSION['messagetype'] = 'success';
            header('location: ../login.php');
        } else {
            $_SESSION['message'] = 'Error creating account: ' . $stmt_insert->error;
            $_SESSION['messagetype'] = 'warning';
            header('location: ../register.php');
        }
        $stmt_insert->close();
    } else {
        $_SESSION['message'] = 'Database error: ' . $conn->error;
        $_SESSION['messagetype'] = 'warning';
        header('location: ../register.php');
    }
    
    exit();
  }
} else {
  $_SESSION['message'] = 'Fill all information';
  $_SESSION['messagetype'] = 'warning';
  header('location: ../register.php');
  exit();
}
