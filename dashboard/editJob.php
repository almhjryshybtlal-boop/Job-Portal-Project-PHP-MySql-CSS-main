<?php
include "../includes/conn.php";
require_once "../includes/auth_check.php";

// التحقق من أن المستخدم مسجل دخول وهو شركة (role_id = 2)
requireRole(2, '../login.php');

include "../includes/indexHeader.php";

function fetchData($conn, $table) {
    $stmt = $conn->prepare("SELECT * FROM `$table`");
    $stmt->execute();
    return $stmt->get_result();
}

$jobCategories = fetchData($conn, 'industry');
$jobTypes = fetchData($conn, 'job_type');
$cities = fetchData($conn, 'districts_or_cities');
$states = fetchData($conn, 'states');

$jobData = null;
if (isset($_GET['id'])) {
    $id_jobpost = intval($_GET['id']);
    $id_company = intval($_SESSION['id_company']);
    
    // التحقق من أن الوظيفة تابعة لهذه الشركة
    $stmt = $conn->prepare("SELECT * FROM job_post WHERE id_jobpost = ? AND id_company = ?");
    $stmt->bind_param("ii", $id_jobpost, $id_company);
    $stmt->execute();
    $result = $stmt->get_result();
    $jobData = $result->fetch_assoc();
    
    if (!$jobData) {
        $_SESSION['message'] = 'ليس لديك صلاحية تعديل هذه الوظيفة';
        $_SESSION['messagetype'] = 'warning';
        header('Location: manageJobs.php');
        exit();
    }
}
?>

<body>
  <?php include "../includes/indexNavbar.php"; ?>`n  `n  <!-- Notification Display -->`n  <?php include "../includes/notification_display.php" ?>`n

  <div class="dashboard-container">
    <?php include "../dashboard/dashboardSidebar.php" ?>
    <div class="add-job-container">
      <div class="add-job-content-container">
        <?php if ($jobData) :
          $hash = md5($jobData['id_jobpost']);
        ?>
          <div class="headline">
            <h3>تعديل الوظيفة</h3>
          </div>
          <form class="job-form-container" method="post" action="../process/updateJob.php?key=<?php echo htmlspecialchars($hash) . '&id=' . htmlspecialchars($jobData['id_jobpost']) ?>">
            <div class="input-group item-a">
              <label for="job-title">المسمى الوظيفي / التصنيف</label>
              <input type="text" name="jobtitle" value="<?php echo htmlspecialchars($jobData['jobtitle']); ?>">
            </div>
            <div class="input-group item-b">
              <label for="job-title">فئة الوظيفة</label>
              <div class="select-container">
                <select id="select-category" name="industry">
                  <?php while ($jobCategory = $jobCategories->fetch_assoc()) { ?>
                    <option value="<?php echo htmlspecialchars($jobCategory['id']); ?>" <?php if ($jobCategory['id'] == $jobData['industry_id']) echo 'selected'; ?>><?php echo htmlspecialchars($jobCategory['name']); ?></option>
                  <?php } ?>
                </select>
                <span class="custom-arrow"></span>
              </div>
            </div>
            <div class="input-group item-c">
              <label for="job-type">نوع الوظيفة</label>
              <div class="select-container">
                <select id="select-category" name="job_type">
                  <?php while ($jobType = $jobTypes->fetch_assoc()) { ?>
                    <option value="<?php echo htmlspecialchars($jobType['id']); ?>" <?php if ($jobType['id'] == $jobData['job_status']) echo 'selected'; ?>><?php echo htmlspecialchars($jobType['type']); ?></option>
                  <?php } ?>
                </select>
                <span class="custom-arrow"></span>
              </div>
            </div>
            <div class="input-group item-d">
              <label for="job-type">القطاع / الولاية</label>
              <div class="select-container">
                <select id="select-category" name="division_or_state">
                  <?php while ($state = $states->fetch_assoc()) { ?>
                    <option value="<?php echo htmlspecialchars($state['id']); ?>" <?php if ($state['id'] == $jobData['state_id']) echo 'selected'; ?>><?php echo htmlspecialchars($state['name']); ?></option>
                  <?php } ?>
                </select>
                <span class="custom-arrow"></span>
              </div>
            </div>
            <div class="input-group item-e">
              <label for="job-type">المدينة / المنطقة</label>
              <div class="select-container">
                <select id="select-category" name="district_or_city">
                  <?php while ($city = $cities->fetch_assoc()) { ?>
                    <option value="<?php echo htmlspecialchars($city['id']); ?>" <?php if ($city['id'] == $jobData['city_id']) echo 'selected'; ?>><?php echo htmlspecialchars($city['name']); ?></option>
                  <?php } ?>
                </select>
                <span class="custom-arrow"></span>
              </div>
            </div>
            <div class="input-group item-f">
              <label for="job-title">الحد الأدنى للراتب</label>
              <input type="number" name="minimumsalary" value="<?php echo htmlspecialchars($jobData['minimumsalary']); ?>" required>
            </div>
            <div class="input-group item-g">
              <label for="job-title">الحد الأقصى للراتب</label>
              <input type="number" name="maximumsalary" value="<?php echo htmlspecialchars($jobData['maximumsalary']); ?>" required>
            </div>
            <div class="input-group item-h">
              <label for="job-title">الموعد النهائي</label>
              <input type="date" name="deadline" value="<?php echo htmlspecialchars($jobData['deadline']); ?>" required>
            </div>
            <div class="input-group item-i">
              <label for="job-title">المهارات المطلوبة</label>
              <textarea cols="20" rows="5" name="skills" required><?php echo htmlspecialchars($jobData['skills_ability']); ?></textarea>
            </div>
            <div class="input-group item-j">
              <label for="job-title">وصف الوظيفة</label>
              <textarea cols="20" rows="5" name="description" required><?php echo htmlspecialchars($jobData['description']); ?></textarea>
            </div>
            <div class="input-group item-k">
              <label for="job-title">المسؤوليات</label>
              <textarea cols="20" rows="5" name="responsibility" required><?php echo htmlspecialchars($jobData['responsibility']); ?></textarea>
            </div>
            <div class="button-container item-l">
              <button type="submit" name="updateJob" class="btn btn-secondary">تحديث الوظيفة</button>
            </div>
          </form>
        <?php endif; ?>
      </div>
    </div>
  </div>
</body>