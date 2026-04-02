<?php 
include "../includes/conn.php";
require_once "../includes/auth_check.php";

// التحقق من أن المستخدم مسجل دخول وهو مدير (role_id = 3)
requireRole(3, '../index.php');
?>

<?php include "../includes/indexHeader.php" ?>

<body>
  <?php include "../includes/indexNavbar.php" ?>
  <div class="dashboard-container">
    <?php include "./dashboardSidebar.php" ?>
    <div class="all-jobs-container">
      <div class="headline headline-container">
       <h3>جميع الوظائف المنشورة</h3>
        <a href="../Report Generation/Admin/report-for-jobposts.php" class="btn"><i class="fa-solid fa-download"></i> تقرير</a>
      </div>
      <div>
        <table>
          <thead>
            <th>#</th>
            <th>المسمى الوظيفي</th>
            <th>الصناعة</th>
            <th>نوع الوظيفة</th>
            <th>الراتب(&#2547;)</th>
            <th>الموقع</th>
            <th>المدينة</th>
            <th>نشر بواسطة</th>
            <th>الإجراء</th>
          </thead>
          <tbody>
            <?php

            $sql =  "SELECT * FROM job_post";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $query = $stmt->get_result();
            //id auto increament in tables initiation
            $i = 1;
            while ($row = $query->fetch_assoc()) {
              $hash = md5($row['id_jobpost']);
              //getting other details
              $job_id = $row['id_jobpost'];
              //city
              $city_id = $row['city_id'];
              $city_sql = "SELECT name FROM districts_or_cities WHERE id = ?";
              $city_stmt = $conn->prepare($city_sql);
              $city_stmt->bind_param("i", $city_id);
              $city_stmt->execute();
              $city_result = $city_stmt->get_result();
              $city_row = $city_result->fetch_row();
              $city_stmt->close();

              //region
              $state_id = $row['state_id'];
              $state_sql = "SELECT name FROM states WHERE id = ?";
              $state_stmt = $conn->prepare($state_sql);
              $state_stmt->bind_param("i", $state_id);
              $state_stmt->execute();
              $state_result = $state_stmt->get_result();
              $state_row = $state_result->fetch_row();
              $state_stmt->close();

              // industry
              $industry = $row['industry_id'];
              $industry_sql = "SELECT name FROM industry WHERE id = ?";
              $industry_stmt = $conn->prepare($industry_sql);
              $industry_stmt->bind_param("i", $industry);
              $industry_stmt->execute();
              $industry_result = $industry_stmt->get_result();
              $industry_row = $industry_result->fetch_row();
              $industry_stmt->close();

              //job status
              $job_status = $row['job_status'];
              $job_status_sql = "SELECT type FROM job_type WHERE id = ?";
              $job_status_stmt = $conn->prepare($job_status_sql);
              $job_status_stmt->bind_param("i", $job_status);
              $job_status_stmt->execute();
              $job_status_result = $job_status_stmt->get_result();
              $job_status_row = $job_status_result->fetch_row();
              $job_status_stmt->close();

              //company
              $company = $row['id_company'];
              $company_sql = "SELECT companyname FROM company WHERE id_company = ?";
              $company_stmt = $conn->prepare($company_sql);
              $company_stmt->bind_param("i", $company);
              $company_stmt->execute();
              $company_result = $company_stmt->get_result();
              $company_row = $company_result->fetch_row();
              $company_stmt->close();

              echo "
                              <tr>
                                <td>" . $i . "</td>
                                <td>" . htmlspecialchars($row['jobtitle']) . "</td>
                                <td>" . htmlspecialchars($industry_row[0]) . "</td>
                                <td>" . htmlspecialchars($job_status_row[0]) . "</td>
                                <td>" . htmlspecialchars($row['minimumsalary']) . " - " . htmlspecialchars($row['maximumsalary']) . "</td>
                                <td>" . htmlspecialchars($state_row[0]) . "</td>
                                <td>" . htmlspecialchars($city_row[0]) . "</td>
                                <td>" . htmlspecialchars($company_row[0]) . "</td>
                                <td class='action-button' ><a class='btn btn-optional' href='../jobDetails.php?key=" . $hash . "&id=" . $job_id . "'>عرض</a>
                                <a href='../process/deleteJobs.php?key=" . $hash . "&id=" . $job_id . "&page=admin-control' class='btn btn-optional'>إزالة </a> 
                                </td>
                                </tr>";
              $i++;
            }
            $stmt->close();
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>