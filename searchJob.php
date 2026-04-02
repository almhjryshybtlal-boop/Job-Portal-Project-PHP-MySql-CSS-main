<?php include "./includes/conn.php" ?>
<?php include "./includes/indexHeader.php"; ?>
<link rel="stylesheet" href="assets/CSS/styles.css">
  <link rel="stylesheet" href="assets/CSS/responsive.css">
<body>
  <?php include "./includes/indexNavbarr.php" ?>

  <div id="find-jobs-page">
    <div class="intro-banner">
      <div class="intro-banner-overlay">
        <div class="intro-banner-content">
          <div class="container glassmorphism">
            <div class="banner-headline-text-part">
              <h3>ابحث عن وظائف قريبة</h3>
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
        <?php include "./includes/searchSidebarFindJobs.php"; ?>
      </div>
      <div class="page-content-right-side">
        <div class="headline">
          <span class="icon-container">
            <i class="fa-solid fa-magnifying-glass"></i>
          </span>
          <h3>عرض نتائج الوظائف</h3>
        </div>
        <?php
        if (isset($_POST['submitSearch'])) {
          $sqlStatement = [];
          $params = [];
          $types = "";
          
          if (isset($_POST['searchKeyword']) && $_POST['searchKeyword'] != '') {
            $searchKeyword = $_POST['searchKeyword'];
            $sqlStatement[] = "jobtitle LIKE ?";
            $params[] = "%$searchKeyword%";
            $types .= "s";
          }

          if (isset($_POST['location-search']) && $_POST['location-search'] != '') {
            $district_or_city_id = intval($_POST['location-search']);
            $sqlStatement[] = "city_id = ?";
            $params[] = $district_or_city_id;
            $types .= "i";
          }

          if (isset($_POST['category-search']) && $_POST['category-search'] != '') {
            $industry_id = intval($_POST['category-search']);
            $sqlStatement[] = "industry_id = ?";
            $params[] = $industry_id;
            $types .= "i";
          }

          if (isset($_POST['job-type-search']) && $_POST['job-type-search'] != '') {
            $job_status_id = intval($_POST['job-type-search']);
            $sqlStatement[] = "job_status = ?";
            $params[] = $job_status_id;
            $types .= "i";
          }

          if (empty($sqlStatement)) {
            $sql = "SELECT * FROM job_post ORDER BY createdat DESC";
            $query = $conn->query($sql);
          } else {
            $sql = "SELECT * FROM job_post WHERE " . implode(' AND ', $sqlStatement) . " ORDER BY createdat DESC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $query = $stmt->get_result();
          }

          if ($query->num_rows < 1) {
            echo '<div class="no-job-results">
                <p>لم يتم العثور على نتائج...</p> 
              </div>';
          } else {
            while ($row = $query->fetch_assoc()) {
              $id_company = $row['id_company'];
              $jobtitle = $row['jobtitle'];
              $city_id = $row['city_id'];
              $industry_id = $row['industry_id'];
              $job_status_id = $row['job_status'];
              $minimum_salary = $row['minimumsalary'];
              $maximum_salary = $row['maximumsalary'];
              $create_date = $row['createdat'];
              $location = $conn->query("SELECT name FROM districts_or_cities WHERE id = '$city_id'")->fetch_assoc();
              $job_category = $conn->query("SELECT name FROM industry WHERE id = '$industry_id'")->fetch_assoc();
              $job_type = $conn->query("SELECT type FROM job_type WHERE id = '$job_status_id'")->fetch_assoc();
              $profile_pic = $conn->query("SELECT profile_pic FROM company WHERE id_company = '$id_company'")->fetch_assoc();
        ?>
              <div class="job-item-container">
                <div class="profile-container">
                  <img src="./assets/images/<?php echo $profile_pic['profile_pic']; ?>" alt="">
                </div>
                <div class="job-info-container">
                  <div class="job-info-left-side">
                    <div class="job-status">
                      <i class="fa-solid fa-briefcase"></i>
                      <span><?php echo $job_type['type']; ?></span>
                    </div>
                    <h3><?php echo $jobtitle; ?></h3>
                    <div class="others-info">
                      <div class="job-category-info">
                        <i class="fa-solid fa-briefcase"></i>
                        <span><?php echo $job_category['name']; ?></span>
                      </div>
                      <div class="salary-info">
                        <i class="fa-solid fa-money-check-dollar"></i>
                        <span><?php echo $minimum_salary . "-" . $maximum_salary; ?></span>
                      </div>
                      <div class="location-info">
                        <i class="fa-solid fa-location-dot"></i>
                        <span><?php echo $location['name']; ?></span>
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
                      echo '<span class="validity-active">نشط</span>';
                    } else {
                      echo '<span class="validity-expired">منتهي</span>';
                    }
                    ?>
                  </div>
                </div>
              </div>
        <?php
            }
          }
        }
        ?>
      </div>
    </section>

    <!-- Footer -->
    <div id="footer">
      <!-- Footer Widgets -->
      <?php include 'includes/indexFooterWidgets.php';
      ?>
      <!-- Footer Copyrights -->
      <?php include 'includes/footer.php' ?>
    </div>
  </div>
</body>