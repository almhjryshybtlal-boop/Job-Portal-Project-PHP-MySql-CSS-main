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
    <?php include "../dashboardSidebar.php"; ?>
    <div class="view-applicants-container">
      <div class="headline">
        <h3>المتقدمون</h3>
      </div>
      <?php
      if (isset($_GET['id']) && isset($_GET['hash'])) {
        $id_company = intval($_SESSION['id_company']);
        $job = intval($_GET['id']);
        
        // التحقق من أن الوظيفة تابعة لهذه الشركة
        $check_sql = "SELECT id_jobpost FROM job_post WHERE id_jobpost = ? AND id_company = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("ii", $job, $id_company);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows == 0) {
          echo "<p>ليس لديك صلاحية عرض هذه الطلبات.</p>";
        } else {
          $check_stmt->close();

          // Use a single JOIN query to fetch all necessary data
          $stmt = $conn->prepare("SELECT users.*, districts_or_cities.name AS city_name, education.name AS education_name
                                  FROM applied_jobposts
                                  JOIN users ON applied_jobposts.id_user = users.id_user
                                  LEFT JOIN districts_or_cities ON users.city_id = districts_or_cities.id
                                  LEFT JOIN education ON users.education_id = education.id
                                  WHERE applied_jobposts.id_company = ? AND applied_jobposts.id_jobpost = ?");
          $stmt->bind_param("ii", $id_company, $job);
          $stmt->execute();
          $query = $stmt->get_result();

          if ($query->num_rows > 0) {
            while ($row = $query->fetch_assoc()) {
      ?>
            <div class="job-item-container">
              <div class="profile-container">
                <img src="../assets/images/<?php echo htmlspecialchars($row['profile_pic']); ?>" alt="">
              </div>
              <div class="job-info-container">
                <div class="job-info-container-left-side">
                  <div class="applicants-info">
                    <i class="fa-solid fa-user"></i>
                    <span><?php echo htmlspecialchars($row['fullname']); ?></span>
                  </div>
                  <div class="email-info">
                    <i class="fa-solid fa-envelope"></i>
                    <span><?php echo htmlspecialchars($row['email']); ?></span>
                  </div>
                  <div class="education-info">
                    <i class="fa-solid fa-building-columns"></i>
                    <span><?php echo htmlspecialchars($row['education_name'] ?? 'غير معروف'); ?></span>
                  </div>
                  <div class="others-info">
                    <div class="contact-info">
                      <i class="fa-solid fa-phone"></i>
                      <span><?php echo htmlspecialchars($row['contactno'] ?? 'غير معروف'); ?></span>
                    </div>
                    <div class="location-info">
                      <i class="fa-solid fa-location-dot"></i>
                      <span><?php echo htmlspecialchars($row['city_name'] ?? 'غير معروف'); ?></span>
                    </div>
                  </div>
                </div>
                <div class="job-info-container-right-side">
                  <?php
                  $filePath = "../assets/resume/" . $row['resume'];
                  if (!empty($row['resume']) && file_exists($filePath)) {
                    echo '<a href="' . htmlspecialchars($filePath) . '" class="btn"><i class="fa-solid fa-download" style="color:white;  font-size:1rem;margin-right:.25rem;" ></i>السيرة الذاتية</a>';
                  } else {
                    echo '<a href="#" class="btn"><i class="fa-solid fa-circle-xmark" style="color:white;  font-size:1rem;margin-right:.25rem;"></i>السيرة الذاتية</a>';
                  }
                  ?>
                </div>
              </div>
            </div>
      <?php
          }
          $stmt->close();
        } else {
          echo "<p>لم يتم العثور على متقدمين لهذه الوظيفة.</p>";
        }
      }
      } else {
        echo "<p>طلب غير صالح.</p>";
      }
      ?>
    </div>
  </div>
</body>