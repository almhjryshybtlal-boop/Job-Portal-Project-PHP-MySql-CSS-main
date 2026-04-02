<?php
require_once "../includes/auth_check.php";
// التحقق من أن المستخدم شركة (role_id = 2)
requireRole(2, "../login.php");

include "../includes/conn.php";
include "../includes/indexHeader.php";

// Function to fetch data from a table
function fetchData($conn, $table) {
    $stmt = $conn->prepare("SELECT * FROM `$table`");
    $stmt->execute();
    return $stmt->get_result();
}

// Fetching data for dropdowns using a reusable function
$jobCategories = fetchData($conn, 'industry');
$jobTypes = fetchData($conn, 'job_type');
$cities = fetchData($conn, 'districts_or_cities');
$states = fetchData($conn, 'states');
?>

<body>
  <?php include "../includes/indexNavbar.php"; ?>`n  `n  <!-- Notification Display -->`n  <?php include "../includes/notification_display.php" ?>`n

  <div class="dashboard-container">
    <?php include "../dashboard/dashboardSidebar.php" ?>

    <div class="add-job-container">
      <div class="add-job-content-container">
        <div class="headline">
          <h3>إضافة وظيفة جديدة</h3>
        </div>
        <form class="job-form-container" method="post" action="../process/newJob.php">
          <div class="input-group item-a">
            <label for="job-title">المسمى الوظيفي / التصنيف</label>
            <input type="text" placeholder="المسمى الوظيفي" name="jobtitle" required>
          </div>
          <div class="input-group item-b">
            <label for="job-title">فئة الوظيفة</label>
            <div class="select-container">
              <select id="select-category" name="industry">
                <?php while ($jobCategory = $jobCategories->fetch_assoc()) { ?>
                  <option value="<?php echo htmlspecialchars($jobCategory['id']); ?>"><?php echo htmlspecialchars($jobCategory['name']); ?></option>
                <?php } ?>
              </select>
              <span class="custom-arrow"></span>
            </div>
          </div>
          <div class="input-group  item-c">
            <label for="job-type">نوع الوظيفة</label>
            <div class="select-container">
              <select id="select-category" name="job_type">
                <?php while ($jobType = $jobTypes->fetch_assoc()) { ?>
                  <option value="<?php echo htmlspecialchars($jobType['id']); ?>"><?php echo htmlspecialchars($jobType['type']); ?></option>
                <?php } ?>
              </select>
              <span class="custom-arrow"></span>
            </div>
          </div>
          <div class="input-group item-d">
            <label for="job-type">القطاع / الولاية</label>
            <div class="select-container">
              <select id="select-category" name="states">
                <?php while ($state = $states->fetch_assoc()) { ?>
                  <option value="<?php echo htmlspecialchars($state['id']); ?>"><?php echo htmlspecialchars($state['name']); ?></option>
                <?php } ?>
              </select>
              <span class="custom-arrow"></span>
            </div>
          </div>
          <div class="input-group item-e">
            <label for="job-type">المدينة / المنطقة</label>
            <div class="select-container">
              <select id="select-category" name="cities">
                <?php while ($city = $cities->fetch_assoc()) { ?>
                  <option value="<?php echo htmlspecialchars($city['id']); ?>"><?php echo htmlspecialchars($city['name']); ?></option>
                <?php } ?>
              </select>
              <span class="custom-arrow"></span>
            </div>
          </div>
          <div class="input-group item-f">
            <label for="job-title">الحد الأدنى للراتب</label>
            <input type="number" placeholder="الحد الأدنى للراتب" min="0" name="minimumsalary" required>
          </div>
          <div class="input-group item-g">
            <label for="job-title">الحد الأقصى للراتب</label>
            <input type="number" placeholder="الحد الأقصى للراتب" min="0" required name="maximumsalary">
          </div>
          <div class="input-group item-h">
            <label for="job-title">الموعد النهائي</label>
            <input type="date" name="deadline" required>
          </div>
          <div class="input-group item-i">
            <label for="job-title">المهارات المطلوبة</label>
            <textarea cols="20" rows="5" placeholder="المهارات..." name="skills" required></textarea>
          </div>
          <div class="input-group item-j">
            <label for="job-title">وصف الوظيفة</label>
            <textarea cols="20" rows="5" placeholder="وصف الوظيفة..." required name="description"></textarea>
          </div>
          <div class="input-group item-k">
            <label for="job-title">المسؤوليات</label>
            <textarea cols="20" rows="5" placeholder="المسؤوليات..." required name="responsibility"></textarea>
          </div>
          <div class="button-container item-l">
            <button type="submit" name="submitJob" class="btn btn-secondary">نشر الوظيفة</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</body>