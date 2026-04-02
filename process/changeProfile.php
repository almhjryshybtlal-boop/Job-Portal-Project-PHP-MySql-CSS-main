<?php include "../includes/session.php";

// دالة مُحسَّنة لتحديث بيانات المستخدمين
function updateUsersProfile($conn, $data, $email) {
  $sql = "UPDATE users SET fullname=?, email=?, address=?, headline=?, city_id=?, state_id=?, contactno=?, education_id=?, dob=?, aboutme=?, skills=?, gender=? WHERE email = ?";
  $stmt = $conn->prepare($sql);
  
  if ($stmt) {
    $stmt->bind_param("ssssssissssss", $data['fullname'], $data['email'], $data['address'], $data['headline'], $data['city'], $data['region'], $data['phoneno'], $data['education'], $data['dob'], $data['aboutme'], $data['skills'], $data['gender'], $email);
    $stmt->execute();
    $stmt->close();
    echo "Profile Updated successfully!!";
  }
}

// دالة مُحسَّنة لتحديث بيانات الشركات
function updateCompanyProfile($conn, $data, $email) {
  $sql = "UPDATE company SET companyname=?, email=?, address=?, industry_id=?, city_id=?, state_id=?, contactno=?, website=?, esta_date=?, aboutme=?, empno=? WHERE email = ?";
  $stmt = $conn->prepare($sql);
  
  if ($stmt) {
    $stmt->bind_param("ssssssisssss", $data['companyname'], $data['email'], $data['address'], $data['industry'], $data['city'], $data['region'], $data['phoneno'], $data['website'], $data['esta_date'], $data['aboutme'], $data['empno'], $email);
    $stmt->execute();
    $stmt->close();
    echo "Profile Updated successfully!!";
  }
}

// دالة مُحسَّنة لتحديث بيانات المشرفين
function updateAdminProfile($conn, $data, $email) {
  $sql = "UPDATE admin SET fullname=?, email=?, address=?, contactno=?, dob=?, gender=? WHERE email = ?";
  $stmt = $conn->prepare($sql);

  if ($stmt) {
    $stmt->bind_param("sssssss", $data['fullname'], $email, $data['address'], $data['phoneno'], $data['dob'], $data['gender'], $email);
    if ($stmt->execute()) {
        $_SESSION['message'] = 'Profile Updated successfully!!';
        $_SESSION['messagetype'] = 'success';
    } else {
        $_SESSION['message'] = $conn->error;
        $_SESSION['messagetype'] = 'warning';
    }
    $stmt->close();
  } else {
    $_SESSION['message'] = $conn->error;
    $_SESSION['messagetype'] = 'warning';
  }
}

if (isset($_POST['myProfile'])) {
  updateUsersProfile($conn, $_POST, $_SESSION['email']);
} elseif (isset($_POST['companyProfile'])) {
  updateCompanyProfile($conn, $_POST, $_SESSION['email']);
} elseif (isset($_POST['aProfile'])) {
  updateAdminProfile($conn, $_POST, $_SESSION['email']);
}

header('location: ../dashboard/editProfile.php');
exit();