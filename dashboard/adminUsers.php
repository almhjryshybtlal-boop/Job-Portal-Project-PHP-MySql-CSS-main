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
        <h3>جميع المستخدمين - المشرفين</h3>
        <a href="../Report Generation/Admin/report-for-admin.php" class="btn"><i class="fa-solid fa-download"></i> تقرير</a>
      </div>
      <div>
        <table>
          <thead>
            <th>#</th>
           <th>الصورة الشخصية</th>
            <th>الاسم الكامل</th>
            <th>البريد الإلكتروني</th>
            <th>الجنس</th>
            <th>رقم الاتصال</th>
            <th>تاريخ الميلاد</th>
            <th>العنوان</th>
            <th>الإجراء</th>
          </thead>
          <tbody>
            <?php
            $sql =  "SELECT * FROM admin";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $query = $stmt->get_result();
            //id auto increament in tables initiation
            $i = 1;
            while ($row = $query->fetch_assoc()) {
              $hash = md5($row['id_admin']);
              $admin_id = $row['id_admin'];

              echo "
                              <tr>
                                <td>" . $i . "</td>
                                <td><img height='50' width='50' src='../assets/images/" . htmlspecialchars($row['profile_pic']) . "'></td>
                                <td>" . htmlspecialchars($row['fullname']) . "</td>
                                <td>" . htmlspecialchars($row['email']) . "</td>
                                <td>" . htmlspecialchars(isset($row['gender']) ? $row['gender'] : 'unknown') . "</td>
                                <td>" .  htmlspecialchars(isset($row['contactno']) ? $row['contactno'] : 'unknown')  . "</td>
                                <td>" . htmlspecialchars(isset($row['dob']) ? $row['dob'] : 'unknown') . "</td>
                                <td>" . htmlspecialchars(isset($row['address']) ? $row['address'] : 'unknown') . "</td>
                                
                                <td class='action-button' >
                                <a href='../process/deleteAdmins.php?key=" . $hash . "&id=" . $admin_id . "&page=delete-admins' class='btn btn-optional'>إزالة </a> 
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