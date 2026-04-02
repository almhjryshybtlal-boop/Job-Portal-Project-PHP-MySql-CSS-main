<?php
require_once "./includes/Database.php";
include "./includes/indexHeader.php";

$database = new Database();
$conn = $database->getConnection();

$companies = [];
if ($conn) {
    $sql = "SELECT * from company";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $companies[] = $row;
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
  
  <div id="browse-company-page">
    <div class="intro-banner">
      <div class="intro-banner-overlay">
        <div class="intro-banner-content">
          <div class="container glassmorphism">
            <div class="banner-headline-text-part">
              <h3>تصفح الشركات</h3>
              <div class="line line-dark"></div>
              <span>اكتشف مجموعة متنوعة من الشركات واستكشف عروضها. سواء كنت تبحث عن فرص عمل أو ترغب في التعرف على مختلف الصناعات، فإن قائمتنا المختارة من الشركات توفر لك ما تحتاجه.</span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <section class="page-content">
      <div class="page-content-left-side">
        <?php include "./includes/searchSidebarCompany.php" ?>
      </div>
      <div class="page-content-right-side">
        <?php foreach ($companies as $row) :
            $hash = md5($row['id_company']);
            $id_company = $row['id_company'];
            $industry = $database->fetchDetails("industry", "id", $row['industry_id']);
            $division_or_state = $database->fetchDetails("states", "id", $row['state_id']);
            $district_or_city = $database->fetchDetails("districts_or_cities", "id", $row['city_id']);
        ?>
          <a href="./companyDetails.php?key=<?php echo htmlspecialchars($hash) . '&id=' . htmlspecialchars($id_company) ?>">
            <div class="company-item">
              <div class="profile-container">
                <img src="./assets/images/<?php echo htmlspecialchars($row['profile_pic']) ?>" alt="">
              </div>
              <div class="job-info-container">
                <h3> <?php echo htmlspecialchars($row['companyname']) ?> </h3>
                <div class="job-category-info">
                  <i class="fa-solid fa-briefcase"></i>
                  <span><?php echo htmlspecialchars($industry['name']) . " صناعة" ?></span>
                </div>
                <div class="location-info">
                  <i class="fa-solid fa-location-dot"></i>
                  <span><?php echo htmlspecialchars($division_or_state['name']) . ', ' . htmlspecialchars($district_or_city['name']); ?></span>
                </div>
              </div>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
  </div>
  <div id="footer">
    <?php include 'includes/indexFooterWidgets.php'; ?>
    <?php include 'includes/footer.php' ?>
  </div>
</body>
</html>