<div id="header">
  <div class="container">
    <div class="header-left">
      <div class="logo-container">
        <img src="../assets/images/logo.png" alt="logo">
        <h3>بوابة التسجيل </h3>
      </div>
      <nav class="navbar">
        <ul>
          <li><a href="../index.php">الرئيسية</a> </li>
          <li><a href="../findJobs.php">ابحث عن الوظائف </a></li>
          <li><a href="../browseCompanies.php">تصفح الشركات </a></li>
          <!-- <li><a href="">About Us</a></li> -->
          <!-- <li><a href="">Contact Us</a></li> -->
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
        echo '<button class="dropdown-btn">' . 'Hi,  ' . $username . '!' . '</button>';
        echo '<div class="dropdown-content">';
        echo '<a href="dashboard.php" class="nav-link"> <i class="fa-solid fa-table-cells-large"></i> Dashboard</a>';
        echo '<a href="editProfile.php" class="nav-link"> <i class="fa-solid fa-user"></i> My Profile</a>';
        echo '<a href="./login.php" class="nav-link"> <i class="fa-solid fa-power-off"></i> Logout</a>';
        echo '</div>';
        echo '</div>';
      } else {
        // User is not logged in, display the "Sign In" button
        echo '<a href="./login.php" class="btn">Sign In</a>';
      }
      ?>
    </div>
  </div>
</div>