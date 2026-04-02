<?php 
require_once "../includes/auth_check.php";
requireLogin("../login.php"); // التحقق من تسجيل الدخول

include "../includes/conn.php";
include "../includes/indexHeader.php"; 
?>

<body>
  <?php include "../includes/indexNavbar.php"; ?>
  
  <!-- Notification Display -->
  <?php include "../includes/notification_display.php" ?>

  <div class="dashboard-container">
    <?php include "./dashboardSidebar.php"; ?>
    <div class="dashboard-content-container">
      <?php if (($_SESSION['role_id']) == 1) : ?>
        <div class="container-box">
          <div class="icon">
            <i class="fa-solid fa-briefcase"></i>
          </div>
          <h1>
            <?php
            $id_user = intval($_SESSION['id_user']);
            $sql  = "select * from applied_jobposts where id_user = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id_user);
            $stmt->execute();
            $query = $stmt->get_result();
            echo $query->num_rows;
            ?>
          </h1>
         <span>الوظائف المتقدم لها</span>
        </div>
        <div class="container-box">
          <div class="icon">
            <i class="fa-sharp fa-solid fa-heart"></i>
          </div>
          <h1>
            <?php
            $id_user = intval($_SESSION['id_user']);
            $sql  = "select * from saved_jobposts where id_user = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id_user);
            $stmt->execute();
            $query = $stmt->get_result();
            echo $query->num_rows;
            ?>

          </h1>
          <span>الوظائف المحفوظة</span>
        </div>

      <?php endif; ?>
      <?php if (($_SESSION['role_id']) == 2) : ?>
        <div class="container-box">
          <div class="icon">
            <i class="fa-solid fa-briefcase-medical"></i>
          </div>
          <h1>
            <?php
            $id_company = intval($_SESSION['id_company']);
            $sql = "SELECT * FROM job_post WHERE id_company = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id_company);
            $stmt->execute();
            $query = $stmt->get_result();
            echo $query->num_rows;
            ?>
          </h1>
         <span>الوظائف المنشورة</span>
        </div>
        <div class="container-box">
          <div class="icon">
            <i class="fa-solid fa-star"></i>
          </div>
          <h1>
            <?php
            $id_company = intval($_SESSION['id_company']);
            $sql = "SELECT * FROM company_reviews JOIN company on company_reviews.company_id=company.id_company WHERE id_company = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id_company);
            $stmt->execute();
            $query = $stmt->get_result();
            echo $query->num_rows;
            ?>
          </h1>
          <span>التقييمات</span>
        </div>
      <?php endif; ?>

      <?php if (($_SESSION['role_id']) == 3) : ?>
        <div class="container-box">
          <div class="icon">
            <i class="fa-solid fa-briefcase-medical"></i>
          </div>
          <h1>
            <?php
            $sql = "SELECT * FROM job_post";
            $query = $conn->query($sql);
            echo $query->num_rows;
            ?>
          </h1>
           <span>الوظائف</span>
        </div>
        <div class="container-box">
          <div class="icon">
            <i class="fa-solid fa-building"></i>
          </div>
          <h1>
            <?php
            $sql = "SELECT * FROM company";
            $query = $conn->query($sql);

            echo $query->num_rows;
            ?>
          </h1>
           <span>الشركات / أصحاب العمل</span>
        </div>
        <div class="container-box">
          <div class="icon">
            <i class="fa-solid fa-user"></i>
          </div>
          <h1>
            <?php
            $sql = "SELECT * FROM users";
            $query = $conn->query($sql);

            echo $query->num_rows;
            ?>
          </h1>
           <span>الباحثين عن عمل</span>
        </div>
      <?php endif; ?>
    </div>
  </div>

</body> 