<?php
include "../includes/session.php";

// دالة مُحسَّنة لرفع الصور وتحديث قاعدة البيانات
function updateProfilePicture($conn, $tableName, $email, $file) {
  $profile_pic = $file['name'];
  $hash = md5($email);
  $filename = $hash . $profile_pic;

  if ($profile_pic != '') {
    if (move_uploaded_file($file['tmp_name'], '../assets/images/' . $filename)) {
      $sql = "UPDATE $tableName SET profile_pic = ? WHERE email = ?";
      $stmt = $conn->prepare($sql);
      if ($stmt) {
        $stmt->bind_param("ss", $filename, $email);
        $stmt->execute();
        $stmt->close();
        echo "Profile picture is changed!";
      }
    }
  }
}

if (isset($_POST['myPic'])) {
  updateProfilePicture($conn, "users", $_SESSION['email'], $_FILES['picture']);
} elseif (isset($_POST['companyPic'])) {
  updateProfilePicture($conn, "company", $_SESSION['email'], $_FILES['picture']);
} elseif (isset($_POST['aPic'])) {
  updateProfilePicture($conn, "admin", $_SESSION['email'], $_FILES['picture']);
}

header("location: ../dashboard/editProfile.php");
exit();