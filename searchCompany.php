<?php include "./includes/conn.php" ?>
<?php include "./includes/indexHeader.php"; ?>
<link rel="stylesheet" href="assets/CSS/styles.css">
  <link rel="stylesheet" href="assets/CSS/responsive.css">
<body>
  <?php include "./includes/indexNavbarr.php" ?>

  <div id="browse-company-page">
    <div class="intro-banner">
      <div class="intro-banner-overlay">
        <div class="intro-banner-content">
          <div class="container">
            <div class="banner-headline-text-part glassmorphism">
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
        <?php include "./includes/searchSidebarCompany.php"; ?>
      </div>
      <div class="page-content-right-side">
        <?php
        if (isset($_POST['submitSearch'])) {
          $sqlStatement = [];
          $params = [];
          $types = "";

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

          if (empty($sqlStatement)) {
            $sql = "SELECT * FROM company ORDER BY createdAt DESC";
            $query = $conn->query($sql);
          } else {
            $sql = "SELECT * FROM company WHERE " . implode(' AND ', $sqlStatement) . " ORDER BY createdAt DESC";
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
              $hash = md5($row['id_company']);
              $id_company = $row['id_company'];
              $companyname = $row['companyname'];
              $city_id = $row['city_id'];
              $state_id = $row['state_id'];
              $industry_id = $row['industry_id'];
              $create_date = $row['createdAt'];
              $location = $conn->query("SELECT name FROM districts_or_cities WHERE id = '$city_id'")->fetch_assoc();
              $job_category = $conn->query("SELECT name FROM industry WHERE id = '$industry_id'")->fetch_assoc();
              $division_or_state =
                $conn->query("SELECT name FROM states WHERE id = '$state_id'")->fetch_assoc();
              $job_category = $conn->query("SELECT name FROM industry WHERE id = '$industry_id'")->fetch_assoc();

              $profile_pic = $conn->query("SELECT profile_pic FROM company WHERE id_company = '$id_company'")->fetch_assoc();
        ?>
              <a href="./companyDetails.php?key=<?php echo $hash . '&id=' . $id_company ?>">
                <div class="company-item">
                  <div class="profile-container">
                    <img src="./assets/images/<?php echo $profile_pic['profile_pic'] ?>" alt="">
                  </div>
                  <div class="job-info-container">
                    <h3> <?php echo $companyname ?> </h3>
                    <div class="job-category-info">
                      <i class="fa-solid fa-briefcase"></i>
                      <span><?php echo $job_category['name'] . " صناعة" ?></span>
                    </div>
                    <div class="location-info">
                      <i class="fa-solid fa-location-dot"></i>
                      <span><?php echo $division_or_state['name'] . ", " . $location['name'] ?></span>
                    </div>
                  </div>
                </div>
              </a>
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