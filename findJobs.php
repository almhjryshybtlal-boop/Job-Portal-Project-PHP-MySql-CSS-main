<?php
require_once "./includes/Database.php";
include "./includes/indexHeader.php";

$database = new Database();
$conn = $database->getConnection();

$jobs = [];
if ($conn) {
    $sql = "SELECT * FROM job_post ORDER BY createdat DESC";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $jobs[] = $row;
        }
    }
}
?>
<link rel="stylesheet" href="assets/CSS/styles.css">
  <link rel="stylesheet" href="assets/CSS/responsive.css">
<body>
  <?php include "./includes/indexNavbarr.php" ?>
  
  <!-- Notification Display -->
  <?php include "./includes/notification_display.php" ?>
  
  <div id="find-jobs-page">
    <div class="intro-banner">
      <div class="intro-banner-overlay">
        <div class="intro-banner-content">
          <div class="container glassmorphism">
            <div class="banner-headline-text-part">
              <h3>ابحث عن وظائف قريبة</h3>
              <div class="line line-dark"></div>
              <div class="keywords">
                <p>كلمات وظائف رائجة:</p>
                <span> ممرض، مهندس معماري، مصمم جرافيك، أمين صندوق، مهندس كهربائي، مطور أندرويد</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <section class="page-content">
      <div class="page-content-left-side">
        <?php include "includes/searchSidebarFindJobs.php" ?>
      </div>
      <div class="page-content-right-side">
        <div class="headline">
          <span class="icon-container">
            <i class="fa-solid fa-magnifying-glass"></i>
          </span>
          <h3>نتائج قائمة الوظائف</h3>
        </div>
        <?php foreach ($jobs as $row) :
            $company = $database->fetchDetails("company", "id_company", $row['id_company']);
            $location = $database->fetchDetails("districts_or_cities", "id", $row['city_id']);
            $create_date = date("d/m/Y", strtotime($row['createdat']));
            $minimum_salary = htmlspecialchars($row['minimumsalary']);
            $maximum_salary = htmlspecialchars($row['maximumsalary']);
            $hash = md5($row['id_jobpost']);
        ?>
          <a href="./jobDetails.php?key=<?php echo $hash . '&id=' . htmlspecialchars($row['id_jobpost']); ?>">
            <div class="job-item-container">
              <div class="job-item-left-side">
                <div class="profile-container">
                  <img src="./assets/images/<?php echo htmlspecialchars($company['profile_pic']); ?>" alt="">
                </div>
                <div class="job-info">
                  <h3> <?php echo htmlspecialchars($row['jobtitle']); ?> </h3>
                  <div class="company-name-info">
                    <i class="fa-solid fa-briefcase"></i>
                    <span> <?php echo htmlspecialchars($company['companyname']); ?> </span>
                  </div>
                </div>
              </div>
              <div class="job-item-right-side">
                <div class="job-info-left-side">
                  <div class="salary-info">
                    <i class="fa-solid fa-money-check-dollar"></i>
                    <span><?php echo $minimum_salary . "-" . $maximum_salary ?></span>
                  </div>
                  <div class="location-info">
                    <i class="fa-solid fa-location-dot"></i>
                    <span><?php echo htmlspecialchars($location['name']); ?></span>
                  </div>
                </div>
                <div class="date-info">
                  <i class="fa-solid fa-calendar-days"></i>
                  <span><?php echo $create_date; ?></span>
                </div>
              </div>
              <div class="job-info-right-side">
                <?php
                $deadline = date_create($row['deadline']);
                $now = new DateTime();
                if ($now < $deadline) {
                  echo "<span class=\"validity-active\">نشط</span>";
                } else {
                  echo "<span class=\"validity-expired\">منتهي</span>";
                }
                ?>
              </div>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    </section>

    <div id="footer">
      <?php include 'includes/indexFooterWidgets.php'; ?>
      <?php include 'includes/footer.php' ?>
    </div>
  </div>
</body>
</html>