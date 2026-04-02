<?php
// Function to fetch user details based on role
function getUserDetails($conn, $role_id, $session_vars) {
    if ($role_id == 1) {
        $stmt = $conn->prepare("SELECT fullname FROM users WHERE id_user = ?");
        $stmt->bind_param("s", $session_vars['id_user']);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    } elseif ($role_id == 2) {
        $stmt = $conn->prepare("SELECT companyname FROM company WHERE id_company = ?");
        $stmt->bind_param("s", $session_vars['id_company']);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    } elseif ($role_id == 3) {
        $stmt = $conn->prepare("SELECT fullname FROM admin WHERE id_admin = ?");
        $stmt->bind_param("s", $session_vars['id_admin']);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    return null;
}

$userData = getUserDetails($conn, $_SESSION['role_id'], $_SESSION);

// Function to get count of rows
function getCountt($conn, $table) {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM `$table`");
    $stmt->execute();
    $result = $stmt->get_result()->fetch_array();
    return $result[0];
}

$jobPostCount = getCountt($conn, 'job_post');
$companyCount = getCountt($conn, 'company');
$userCount = getCountt($conn, 'users');
$adminCount = getCountt($conn, 'admin');
?>
<aside class="sidebar">
  <div class="dashboard-profile-box">
    <?php if ($userData) : ?>
      <div class="user-profile-text">
        <span class="fullname">
          <?php
          if (isset($userData['fullname'])) {
            echo htmlspecialchars($userData['fullname']);
          } elseif (isset($userData['companyname'])) {
            echo htmlspecialchars($userData['companyname']);
          }
          ?>
        </span>
      </div>
    <?php endif; ?>
  </div>
  <ul>
    <?php if (($_SESSION['role_id']) == 1) : ?>
      <li><a href="dashboard.php"> <span class="icon-container"> <i class="fa-solid fa-table-cells-large"></i> </span> لوحة التحكم </a></li>
      <li><a href="myResume.php"><span class="icon-container"> <i class="fa-sharp fa-solid fa-file-pdf"></i> </span> إدارة السيرة الذاتية</a></li>
      <li><a href="editProfile.php"><span class="icon-container"> <i class="fa-solid fa-user"></i> </span> تعديل الملف الشخصي</a></li>

      <li><a href="../login.php"><span class="icon-container"><i class="fa-solid fa-right-from-bracket"></i></span> تسجيل الخروج</a></li>
    <?php endif; ?>

    <?php if (($_SESSION['role_id']) == 2) : ?>
      <li><a href="dashboard.php"> <span class="icon-container"> <i class="fa-solid fa-table-cells-large"></i> </span> لوحة التحكم </a></li>
      <li><a href="addJob.php"><span class="icon-container"> <i class="fa-solid fa-user-plus"></i> </span>نشر وظيفة جديدة</a></li>
      <li><a href="manageApplications.php"><span class="icon-container"> <i class="fa-solid fa-paperclip"></i> </span> إدارة طلبات التقديم</a></li>
      <li><a href="manageJobs.php"><span class="icon-container"> <i class="fa-solid fa-briefcase"></i> </span>  إدارة الوظائف</a></li>
      <li><a href="editProfile.php"><span class="icon-container"> <i class="fa-solid fa-user"></i> </span> تعديل الملف الشخصي</a></li>

      <li><a href="../login.php"><span class="icon-container"><i class="fa-solid fa-right-from-bracket"></i></span> تسجيل الخروج</a></li>
    <?php endif; ?>

    <?php if (($_SESSION['role_id']) == 3) : ?>
      <li><a href="dashboard.php"> <span class="icon-container"> <i class="fa-solid fa-table-cells-large"></i> </span> لوحة التحكم </a></li>
      <li><a href="./allJobPost.php"><span class="icon-container"><i class="fa-solid fa-briefcase"></i></span>جميع الوظائف <?php echo '<span style="background-color:#101935;padding:0px 0.5rem;border-radius:50%;margin-left:1rem;" >' . htmlspecialchars($jobPostCount) . '</span>' ?></a></li>
      <li><a href="./allCompanies.php"><span class="icon-container"> <i class="fa-solid fa-building"></i> </span>جميع الشركات <?php echo '<span style="background-color:#101935;padding:0px 0.5rem;border-radius:50%;margin-left:1rem;" >' . htmlspecialchars($companyCount) . '</span>' ?></a> </li>
      <li><a href="./allJobSeekers.php"><span class="icon-container"> <i class="fa-solid fa-people-group"></i> </span>جميع الباحثين عن عمل <?php echo '<span style="background-color:#101935;padding:0px 0.5rem;border-radius:50%;margin-left:1rem;" >' . htmlspecialchars($userCount) . '</span>' ?></a></li>
      <li><a href="./adminUsers.php"><span class="icon-container"> <i class="fa-solid fa-circle-user"></i> </span>المستخدمون - المشرفون <?php echo '<span style="background-color:#101935;padding:0px 0.5rem;border-radius:50%;margin-left:1rem;" >' . htmlspecialchars($adminCount) . '</span>' ?></a></li>
      <li><a href="editProfile.php"><span class="icon-container"> <i class="fa-solid fa-user"></i> </span>تعديل الملف الشخصي</a></li>
      <li><a href="../login.php"><span class="icon-container"><i class="fa-solid fa-right-from-bracket"></i></span>تسجيل الخروج</a></li>
    <?php endif; ?>
  </ul>
</aside>