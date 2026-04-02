<?php include "../includes/session.php";

// دالة مُحسَّنة للحذف الآمن
function deleteRecord($conn, $tableName, $columnName, $id) {
  $sql = "DELETE FROM $tableName WHERE $columnName = ?";
  $stmt = $conn->prepare($sql);

  if ($stmt) {
    $stmt->bind_param("i", $id); 
    if ($stmt->execute()) {
      echo "Account Deactivated Successfully!";
    } else {
      echo "Error deactivating account: " . $stmt->error;
    }
    $stmt->close();
  } else {
    echo "Error preparing statement: " . $conn->error;
  }
}

if (isset($_POST['userProfile'])) {
  $id_user = $_SESSION['id_user'];
  deleteRecord($conn, "users", "id_user", $id_user);
}

if (isset($_POST['companyProfile'])) {
  $id_company = $_SESSION['id_company'];
  // حذف الشركة أولاً
  deleteRecord($conn, "company", "id_company", $id_company);
  // ثم حذف الوظائف المتعلقة بها
  deleteRecord($conn, "job_post", "id_company", $id_company);
}

if (isset($_POST['adminProfile'])) {
  $id_admin = $_SESSION['id_admin'];
  deleteRecord($conn, "admin", "id_admin", $id_admin);
}

include "./logout.php";
header('location: ../index.php');

exit();