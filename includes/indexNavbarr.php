<div id="header">
  <div class="container">
    <div class="header-left">
      <div class="logo-container">
        <img src="assets/images/logo.png" alt="logo">
        <h3>بوابة الوظائف</h3>
      </div>
      <nav class="navbar">
        <ul>
          <li><a href="index.php">الرئيسية</a> </li>
          <li><a href="findJobs.php">ابحث عن وظائف</a></li>
          <li><a href="browseCompanies.php">تصفح الشركات</a></li>
          <!-- <li><a href="">من نحن</a></li> -->
          <!-- <li><a href="">اتصل بنا</a></li> -->
        </ul>
      </nav>
    </div>

    <div class="navigation header-right">
      <?php
      // التحقق من عدم بدء الجلسة مسبقاً
      if (session_status() === PHP_SESSION_NONE) {
        session_start();
      }
      // Check if the user is logged in
      if (isset($_SESSION['email'])) {
        // User is logged in, display the user's name and dropdown menu
        $username = explode('@', $_SESSION['email'])[0];
        echo '<div class="dropdown">';
        echo '<button class="dropdown-btn">' . 'مرحباً،  ' . $username . '!' . '</button>';
        echo '<div class="dropdown-content">';
        echo '<a href="./dashboard/dashboard.php" class="nav-link"> <i class="fa-solid fa-table-cells-large"></i> لوحة التحكم</a>';
        echo '<a href="./dashboard/editProfile.php" class="nav-link"> <i class="fa-solid fa-user"></i> الملف الشخصي</a>';
        echo '<a href="./process/logout.php" class="nav-link"> <i class="fa-solid fa-power-off"></i> تسجيل الخروج</a>';
        echo '</div>';
        echo '</div>';
      } else {
        // User is not logged in, display the "Sign In" button
        echo '<a href="./login.php" class="btn">تسجيل الدخول</a>';
      }
      ?>
    </div>
  </div>
</div>