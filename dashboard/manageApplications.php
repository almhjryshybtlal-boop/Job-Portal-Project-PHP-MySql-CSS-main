<?php 
include "../includes/conn.php";
require_once "../includes/auth_check.php";

// التحقق من أن المستخدم مسجل دخول وهو شركة (role_id = 2)
requireRole(2, '../login.php');
?>

<?php include "../includes/indexHeader.php" ?>

<body>
  <?php include "../includes/indexNavbar.php" ?>
  <div class="dashboard-container">
    <?php include "./dashboardSidebar.php" ?>
    <div class="manage-jobs-container">
      <div class="headline">
        <h3>طلبات التقديم للوظائف</h3>
      </div>
      <?php
      $id_company = intval($_SESSION['id_company']);

      $id_jobpost_array = array();

      $sql =  "SELECT  * FROM applied_jobposts WHERE id_company = ?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("i", $id_company);
      $stmt->execute();
      $query = $stmt->get_result();
      
      while ($row = $query->fetch_assoc()) {
        $id_jobpost = $row['id_jobpost'];

        if (!in_array($id_jobpost, $id_jobpost_array)) {
          array_push($id_jobpost_array, $id_jobpost);
          
          $sql1 =  "SELECT * FROM job_post WHERE id_company = ? AND id_jobpost = ?";
          $stmt1 = $conn->prepare($sql1);
          $stmt1->bind_param("ii", $id_company, $id_jobpost);
          $stmt1->execute();
          $query1 = $stmt1->get_result();
          
          while ($row1 = $query1->fetch_assoc()) {
            $city_id = $row1['city_id'];
            $industry = $row1['industry_id'];
            $jobtype = $row1['job_status'];
            
            $location_sql = "SELECT name FROM districts_or_cities WHERE id = ?";
            $location_stmt = $conn->prepare($location_sql);
            $location_stmt->bind_param("i", $city_id);
            $location_stmt->execute();
            $location = $location_stmt->get_result()->fetch_assoc();
            $location_stmt->close();
            
            $category_sql = "SELECT name FROM industry WHERE id = ?";
            $category_stmt = $conn->prepare($category_sql);
            $category_stmt->bind_param("i", $industry);
            $category_stmt->execute();
            $category = $category_stmt->get_result()->fetch_assoc();
            $category_stmt->close();
            
            $profile_pic_sql = "SELECT profile_pic FROM company WHERE id_company = ?";
            $profile_pic_stmt = $conn->prepare($profile_pic_sql);
            $profile_pic_stmt->bind_param("i", $id_company);
            $profile_pic_stmt->execute();
            $profile_pic = $profile_pic_stmt->get_result()->fetch_assoc();
            $profile_pic_stmt->close();
            
            $jobtype_sql = "SELECT type FROM job_type WHERE id = ?";
            $jobtype_stmt = $conn->prepare($jobtype_sql);
            $jobtype_stmt->bind_param("i", $jobtype);
            $jobtype_stmt->execute();
            $jobtype = $jobtype_stmt->get_result()->fetch_assoc();
            $jobtype_stmt->close();
      ?>
            <div class="job-item-container">
              <div class="profile-container">
                <img src="../assets/images/<?php echo htmlspecialchars($profile_pic['profile_pic']) ?>" alt="">
              </div>
              <div class="job-info-container">
                <?php
                $sql2 = "SELECT * from applied_jobposts where id_jobpost = ?";
                $stmt2 = $conn->prepare($sql2);
                $stmt2->bind_param("i", $id_jobpost);
                $stmt2->execute();
                $query2 = $stmt2->get_result();

                echo '<div><a class="validity-active btn-green" href="viewApplications.php?hash=' . md5($id_jobpost) . '&id=' . $id_jobpost . '" >' . $query2->num_rows . ' طلبات <i class="fa-regular fa-eye" style="color:white; font-size:.85rem;" ></i></a></div>';
                $stmt2->close();
                ?>
                <div class="title-with-status">
                  <h3><?php echo htmlspecialchars($row1["jobtitle"]) ?></h3>
                  <div class="job-status">
                    <i class="fa-solid fa-briefcase"></i>
                    <span><?php echo htmlspecialchars($jobtype['type']) ?></span>
                  </div>
                  <span class="validity-active btn-green">نشط</span>
                </div>
                <div class="others-info">
                  <div class="job-category-info">
                    <i class="fa-solid fa-briefcase"></i>
                    <span><?php echo htmlspecialchars($category['name']) ?></span>
                  </div>
                  <div class="date-info">
                    <i class="fa-solid fa-calendar-days"></i>
                    <span><?php echo htmlspecialchars($row1['createdat']) ?></span>
                  </div>
                  <div class="salary-info">
                    <i class="fa-solid fa-money-check-dollar"></i>
                    <span><?php echo htmlspecialchars($row1["minimumsalary"]) . "-" . htmlspecialchars($row1["maximumsalary"]) ?></span>
                  </div>
                  <div class="location-info">
                    <i class="fa-solid fa-location-dot"></i>
                    <span><?php echo htmlspecialchars($location['name']) ?></span>
                  </div>
                </div>
              </div>
            </div>
        <?php }
          $stmt1->close();
        } ?>
      <?php
      }
      $stmt->close();
      ?>
    </div>
  </div>
</body>