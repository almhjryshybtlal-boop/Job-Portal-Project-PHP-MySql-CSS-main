<?php include "../includes/session.php";

if (isset($_GET['id'])) {
  if (!isset($_SESSION['email'])) {
    $_SESSION['email'] = "Please log in to save job";
    header("location: ../login.php");
    exit();
  } else {
    $id_jobpost = $_GET['id'];
    $id_user = $_SESSION['id_user'];
    $createdat = date("Y-m-d");

    $sql = "INSERT INTO saved_jobposts (id_jobpost, id_user, createdat) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("iis", $id_jobpost, $id_user, $createdat);
        $stmt->execute();
        $stmt->close();
    }

    header('location: ../jobDetails.php?key=' . md5($id_jobpost) . '&id=' . $id_jobpost . '');
    exit();
  }
}
