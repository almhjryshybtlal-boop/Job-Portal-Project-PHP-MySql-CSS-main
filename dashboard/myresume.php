<?php
include "../includes/conn.php";
require_once "../includes/auth_check.php";

// التحقق من أن المستخدم مسجل دخول وهو باحث عن عمل (role_id = 1)
requireRole(1, '../login.php');

include "../includes/indexHeader.php";
?>

<body>
  <?php include "../includes/indexNavbar.php"; ?>`n  `n  <!-- Notification Display -->`n  <?php include "../includes/notification_display.php" ?>`n
  <div class="dashboard-container">
    <?php include "./dashboardSidebar.php"; ?>
    <div class="resume-content-container">
      <?php if (($_SESSION['role_id'] == 1)) :
        $id_user = $_SESSION['id_user'];
        $stmt = $conn->prepare("SELECT resume FROM users WHERE id_user = ?");
        $stmt->bind_param("s", $id_user);
        $stmt->execute();
        $query = $stmt->get_result();
        $row = $query->fetch_assoc();
      ?>
        <div class="resume-content-container-left-side">
          <div class="headline">
            <h3>سيرتي الذاتية</h3>
          </div>
          <div class="resume-container">
            <?php if (empty($row['resume'])) : ?>
              <p>لا توجد سيرة ذاتية!! يرجى الرفع.</p>
            <?php else : ?>
              <a href="../assets/resume/<?php echo htmlspecialchars($row['resume']); ?>" target="_blank" class="btn btn-secondary"><i class="fa-solid fa-download" style="color:white;  font-size:1rem;margin-right:.25rem;"></i> السيرة الذاتية</a>
            <?php endif; ?>
          </div>
        </div>
        <div class="resume-content-container-right-side">
          <div class="headline">
            <h3>تغيير السيرة الذاتية</h3>
          </div>
          <div class="upload-resume-container">
            <img src="../assets/images/pdf.png" alt="" />
            <form method="POST" action="../process/changeResume.php" enctype="multipart/form-data">
              <input name="resume" type="file" accept=".pdf" required="">
              <button type="submit" class="btn btn-secondary" name="changeResume">تغيير السيرة الذاتية</button>
            </form>
          </div>
        </div>
      <?php endif ?>
    </div>
  </div>
</body>