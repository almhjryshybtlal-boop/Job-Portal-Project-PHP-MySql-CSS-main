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
        <h3> جميع الشركات</h3>
        <a href="../Report Generation/Admin/report-for-companies.php" class="btn"><i class="fa-solid fa-download"></i> تقرير</a>
      </div>
      <div>
        <table>
          <thead>
             <th>#</th>
            <th>الشعار</th>
            <th>اسم الشركة</th>
            <th>الصناعة</th>
            <th>البريد الإلكتروني</th>
            <th>الهاتف</th>
            <th>الموقع الإلكتروني</th>
            <th>القطاع</th>
            <th>المدينة</th>
            <th>العنوان</th>
            <th>الإجراء</th>
          </thead>
          <tbody>
            <?php
            $sql =  "SELECT * FROM company ORDER BY id_company DESC";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $query = $stmt->get_result();
            //id auto increament in tables initiation
            $i = 1;
            while ($row = $query->fetch_assoc()) {
              $hash = md5($row['id_company']);
              //getting other detaills
              $company_id = $row['id_company'];
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

              echo "
                              <tr>
                                <td>" . $i . "</td>
                                <td><img height='50' width='50' src='../assets/images/" . htmlspecialchars($row['profile_pic']) . "'></td>
                                <td>" . htmlspecialchars($row['companyname']) . "</td>
                                <td>" . htmlspecialchars($industry_row[0]) . "</td>
                                <td>" . htmlspecialchars($row['email']) . "</td>
                                <td>" . htmlspecialchars($row['contactno']) . "</td>
                                <td>" . htmlspecialchars($row['website']) . "</td>
                                <td>" . htmlspecialchars($state_row[0]) . "</td>
                                <td>" . htmlspecialchars($city_row[0]) . "</td>
                                <td>" . htmlspecialchars($row['address']) . "</td>
                                <td class='action-button' >
                                <a class='btn btn-optional' href='../companyDetails.php?key=" . $hash . "&id=" . $company_id . "'>عرض</a>
                                <a href='../process/deleteCompany.php?key=" . $hash . "&id=" . $company_id . "&page=admin-control' class='btn btn-optional'>إزالة </a> 
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