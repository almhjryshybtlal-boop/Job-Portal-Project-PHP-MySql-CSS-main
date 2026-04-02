<?php
require_once "../includes/auth_check.php";
requireRole(2, "../login.php"); // فقط الشركات

include "../includes/session.php";

if (isset($_POST['submitJob'])) {
  $id_company = intval($_SESSION['id_company']);
  $jobtitle = sanitizeInput($_POST['jobtitle']);
  $industry = intval($_POST['industry']);
  $job_type = intval($_POST['job_type']);
  $experience = isset($_POST['experience']) ? intval($_POST['experience']) : 0;
  $edu_qualification = isset($_POST['edu_qualification']) ? intval($_POST['edu_qualification']) : 0;
  $division_or_state_id = intval($_POST['states']);
  $district_or_city_id = intval($_POST['cities']);
  $minimumsalary = intval($_POST['minimumsalary']);
  $maximumsalary = intval($_POST['maximumsalary']);
  $deadline = $_POST['deadline'];
  $skills_ability = sanitizeInput($_POST['skills']);
  $description = sanitizeInput($_POST['description']);
  $responsibility = sanitizeInput($_POST['responsibility']);

  $sql = "INSERT INTO job_post (id_company, jobtitle, industry_id, job_status, description, minimumsalary, maximumsalary, state_id, city_id, experience, skills_ability, edu_qualification, responsibility, deadline) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($sql);
  
  if ($stmt) {
    $stmt->bind_param("issssiisisssss", $id_company, $jobtitle, $industry, $job_type, $description, $minimumsalary, $maximumsalary, $division_or_state_id, $district_or_city_id, $experience, $skills_ability, $edu_qualification, $responsibility, $deadline);
    if ($stmt->execute()) {
        echo "تم إضافة الوظيفة بنجاح!";
    }
    $stmt->close();
  }
}

header('Location: ../dashboard/addJob.php');
exit();
