<?php
include "../includes/conn.php";
require_once "../includes/auth_check.php";

// التحقق من أن المستخدم مسجل دخول وهو شركة (role_id = 2)
requireRole(2, '../login.php');

include "../includes/indexHeader.php";
?>

<body>
  <?php include "../includes/indexNavbar.php"; ?>`n  `n  <!-- Notification Display -->`n  <?php include "../includes/notification_display.php" ?>`n
  <div class="dashboard-container">
    <?php include "./dashboardSidebar.php"; ?>
    <div class="manage-job-content-container">
      <div class="headline">
        <h3>قائمة وظائفي</h3>
      </div>
      <?php
      $id_company = intval($_SESSION['id_company']);

      // Use a single JOIN query to fetch all related data
      $stmt = $conn->prepare("SELECT jp.*, d.name AS city_name, i.name AS industry_name, jt.type AS job_type_name, c.profile_pic
                              FROM job_post AS jp
                              LEFT JOIN districts_or_cities AS d ON jp.city_id = d.id
                              LEFT JOIN industry AS i ON jp.industry_id = i.id
                              LEFT JOIN job_type AS jt ON jp.job_status = jt.id
                              LEFT JOIN company AS c ON jp.id_company = c.id_company
                              WHERE jp.id_company = ?
                              ORDER BY jp.id_jobpost DESC");
      $stmt->bind_param("i", $id_company);
      $stmt->execute();
      $result = $stmt->get_result();

      while ($row = $result->fetch_assoc()) {
      ?>
        <div class="job-item-container">
          <div class="profile-container">
            <img src="../assets/images/<?php echo htmlspecialchars($row['profile_pic']); ?>" alt="">
          </div>
          <div class="job-info-container">
            <div class="job-info-upper-area">
              <span class="validity-active">نشط</span>
              <div class="activity-container">
                <a href="../jobDetails.php?key=<?php echo htmlspecialchars(md5($row['id_jobpost'])) . '&id=' . htmlspecialchars($row['id_jobpost']); ?>"><i class="fa-solid fa-eye"></i></a>
                <a href="./editJob.php?key=<?php echo htmlspecialchars(md5($row['id_jobpost'])) . '&id=' . htmlspecialchars($row['id_jobpost']); ?>"><i class="fa-solid fa-pen-to-square"></i></a>
                <a href="../process/deleteJobs.php?key=<?php echo htmlspecialchars(md5($row['id_jobpost'])) . '&id=' . htmlspecialchars($row['id_jobpost']); ?>"><i class="fa-solid fa-trash"></i></a>
              </div>
            </div>
            <div class="title-with-job-status">
              <h3><?php echo htmlspecialchars($row['jobtitle']); ?></h3>
              <div class="job-status">
                <i class="fa-solid fa-briefcase"></i>
                <span><?php echo htmlspecialchars($row['job_type_name']); ?></span>
              </div>
            </div>
            <div class="others-info">
              <div class="job-category-info">
                <i class="fa-solid fa-briefcase"></i>
                <span><?php echo htmlspecialchars($row['industry_name']); ?></span>
              </div>
              <div class="date-info">
                <i class="fa-solid fa-calendar-days"></i>
                <span><?php echo htmlspecialchars($row['createdat']); ?></span>
              </div>
              <div class="salary-info">
                <i class="fa-solid fa-money-check-dollar"></i>
                <span><?php echo htmlspecialchars($row['minimumsalary']) . "-" . htmlspecialchars($row['maximumsalary']); ?></span>
              </div>
              <div class="location-info">
                <i class="fa-solid fa-location-dot"></i>
                <span><?php echo htmlspecialchars($row['city_name']); ?></span>
              </div>
            </div>
          </div>
        </div>
      <?php } ?>
    </div>
  </div>
</body>

