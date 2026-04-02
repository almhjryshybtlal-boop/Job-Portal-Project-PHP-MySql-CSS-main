<?php
require_once "../includes/auth_check.php";
requireRole(1, "../login.php"); // فقط الباحثين عن عمل

include "../includes/session.php";

if (isset($_GET['cid'])) {
  if (isset($_POST['submit'])) {
    $id_user = intval($_SESSION['id_user']);
    $id_company = intval($_GET['cid']);
    $review = sanitizeInput($_POST['company-review']);
    $createdat = date("Y-m-d");
    $hash = md5($id_company);

    // التحقق من وجود التقييمات
    $rate = 0;
    if (isset($_POST['rate1'])) {
      $rate = 1;
    }
    if (isset($_POST['rate2'])) {
      $rate = 2;
    }
    if (isset($_POST['rate3'])) {
      $rate = 3;
    }
    if (isset($_POST['rate4'])) {
      $rate = 4;
    }
    if (isset($_POST['rate5'])) {
      $rate = 5;
    }

    $sql = "INSERT INTO company_reviews (company_id, createdby, review, createdat, rating) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
      $stmt->bind_param("iissi", $id_company, $id_user, $review, $createdat, $rate);
      if ($stmt->execute()) {
        header("Location: ../companyDetails.php?key=" . $hash . "&id=" . $id_company);
        exit();
      }
      $stmt->close();
    }
  }
}