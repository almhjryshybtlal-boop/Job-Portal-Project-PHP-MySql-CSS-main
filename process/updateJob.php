<?php
include "../includes/session.php";
require_once "../includes/auth_check.php";

// التحقق من أن المستخدم مسجل دخول وهو شركة (role_id = 2)
requireRole(2, '../login.php');

if (isset($_GET['id'])) {
  if (isset($_POST['updateJob'])) {
    $id_jobpost = intval($_GET['id']);
    $id_company = intval($_SESSION['id_company']);
    
    // التحقق من أن الوظيفة تابعة لهذه الشركة
    $check_sql = "SELECT id_jobpost FROM job_post WHERE id_jobpost = ? AND id_company = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $id_jobpost, $id_company);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows == 0) {
      $_SESSION['message'] = 'ليس لديك صلاحية تعديل هذه الوظيفة';
      $_SESSION['messagetype'] = 'warning';
      header('Location: ../dashboard/manageJobs.php');
      exit();
    }
    $check_stmt->close();
    
    // تعقيم وتنظيف المدخلات
    $jobtitle = sanitizeInput($_POST['jobtitle']);
    $industry = intval($_POST['industry']);
    $job_type = sanitizeInput($_POST['job_type']);
    $experience = sanitizeInput($_POST['experience']);
    $edu_qualification = sanitizeInput($_POST['edu_qualification']);
    $division_or_state_id = intval($_POST['division_or_state']);
    $district_or_city_id = intval($_POST['district_or_city']);
    $minimumsalary = intval($_POST['minimumsalary']);
    $maximumsalary = intval($_POST['maximumsalary']);
    $deadline = sanitizeInput($_POST['deadline']);
    $skills_ability = sanitizeInput($_POST['skills']);
    $description = sanitizeInput($_POST['description']);
    $responsibility = sanitizeInput($_POST['responsibility']);
    
    // التحقق من البيانات المطلوبة
    if (empty($jobtitle) || empty($description)) {
      $_SESSION['message'] = 'يرجى ملء جميع الحقول المطلوبة';
      $_SESSION['messagetype'] = 'warning';
      header('Location: ../dashboard/editJob.php?id=' . $id_jobpost);
      exit();
    }
    
    if ($minimumsalary > $maximumsalary) {
      $_SESSION['message'] = 'الحد الأدنى للراتب يجب أن يكون أقل من الحد الأقصى';
      $_SESSION['messagetype'] = 'warning';
      header('Location: ../dashboard/editJob.php?id=' . $id_jobpost);
      exit();
    }

    $sql = "UPDATE job_post
        SET
            id_company = ?,
            jobtitle = ?,
            industry_id = ?,
            job_status = ?,
            experience = ?,
            edu_qualification = ?,
            state_id = ?,
            city_id = ?,
            minimumsalary = ?,
            maximumsalary = ?,
            deadline = ?,
            skills_ability = ?,
            description = ?,
            responsibility = ?
        WHERE id_jobpost = ?";

    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("isssiisiisssssi", $id_company, $jobtitle, $industry, $job_type, $experience, $edu_qualification, $division_or_state_id, $district_or_city_id, $minimumsalary, $maximumsalary, $deadline, $skills_ability, $description, $responsibility, $id_jobpost);
        
        if ($stmt->execute()) {
          $_SESSION['message'] = 'تم تحديث الوظيفة بنجاح';
          $_SESSION['messagetype'] = 'success';
        } else {
          $_SESSION['message'] = 'حدث خطأ أثناء تحديث الوظيفة';
          $_SESSION['messagetype'] = 'error';
        }
        $stmt->close();
    } else {
      $_SESSION['message'] = 'حدث خطأ في قاعدة البيانات';
      $_SESSION['messagetype'] = 'error';
    }
  }
}

header('Location: ../dashboard/manageJobs.php');
exit();
