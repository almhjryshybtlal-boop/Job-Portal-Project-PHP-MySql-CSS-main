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
       <h3>جميع الباحثين عن عمل</h3>
        <a href="../Report Generation/Admin/report-for-jobseekers.php" class="btn"><i class="fa-solid fa-download"></i> تقرير</a>
      </div>
      <div>
        <table>
          <thead>
            <th>#</th>
            <th>الصورة الشخصية</th>
            <th>الاسم الكامل</th>
            <th>الجنس</th>
            <th>الهاتف</th>
            <th>المؤهل العلمي</th>
            <th>القطاع</th>
            <th>المدينة</th>
            <th>العنوان</th>
            <th>الإجراء</th>
          </thead>
          <tbody>
            <?php
            $sql =  "SELECT * FROM users ORDER BY id_user DESC";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $query = $stmt->get_result();
            //id auto increament in tables initiation
            $i = 1;
            while ($row = $query->fetch_assoc()) {
              $hash = md5($row['id_user']);
              //getting other detaills
              $id_user = $row['id_user'];
              //city
              $city_id = $row['city_id'];
              $city_sql = "SELECT name FROM districts_or_cities WHERE id = ?";
              $city_stmt = $conn->prepare($city_sql);
              $city_stmt->bind_param("i", $city_id);
              $city_stmt->execute();
              $city_result = $city_stmt->get_result();
              $city_row = $city_result->fetch_row();
              $city_stmt->close();

              //Division
              $state_id = $row['state_id'];
              $state_sql = "SELECT name FROM states WHERE id = ?";
              $state_stmt = $conn->prepare($state_sql);
              $state_stmt->bind_param("i", $state_id);
              $state_stmt->execute();
              $state_result = $state_stmt->get_result();
              $state_row = $state_result->fetch_row();
              $state_stmt->close();

              // education
              $education = $row['education_id'];
              $education_sql = "SELECT name FROM education WHERE id = ?";
              $education_stmt = $conn->prepare($education_sql);
              $education_stmt->bind_param("i", $education);
              $education_stmt->execute();
              $education_result = $education_stmt->get_result();
              $education_row = $education_result->fetch_row();
              $education_stmt->close();

              echo
              "<tr>
            <td>" . $i . "</td>
            <td><img height='50' width='50' src='../assets/images/" . htmlspecialchars($row['profile_pic']) . "'></td>
            <td>" . htmlspecialchars($row['fullname']) . "</td>
            <td>" . htmlspecialchars(isset($row['gender']) ? $row['gender'] : 'unknown') . "</td>
            <td>" . htmlspecialchars(isset($row['contactno']) ? $row['contactno'] : 'unknown') . "</td>
            <td>" . htmlspecialchars(isset($education_row[0]) ? $education_row[0] : 'unknown') . "</td>
            <td>" . htmlspecialchars(isset($state_row[0]) ? $state_row[0] : 'unknown')  . "</td>
            <td>" . htmlspecialchars(isset($city_row[0]) ? $city_row[0] : 'unknown') . "</td>
            <td>" . htmlspecialchars(isset($row['address']) ? $row['address'] : 'unknown') . "</td>
            <td class='action-button' >
            <a href='../process/deleteUsers.php?key=" . $hash . "&id=" . $id_user . "&page=delete-jobseekers' class='btn btn-optional'>إزالة</a> 
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