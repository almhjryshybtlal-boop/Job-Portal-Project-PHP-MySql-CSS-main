<?php 
require_once "../includes/auth_check.php";
requireRole(1, "../login.php"); // فقط الباحثين عن عمل

include "../includes/session.php";

// دالة منفصلة لإنشاء تقرير PDF. هذا يفصل المسؤوليات.
function generateJobApplicationReport($conn, $id_jobpost, $id_company, $id_user) {
    // ⚠️ ملاحظة: ميزة PDF معطلة مؤقتاً حتى يتم تثبيت TCPDF
    // راجع ملف TCPDF_INSTALLATION_GUIDE.md للتعليمات
    
    /* معطل مؤقتاً - قم بإزالة التعليق بعد تثبيت TCPDF
    
    // Report Generation:
    require_once('../tcpdf/tcpdf.php');

    // Create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // Set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Your Name');
    $pdf->SetTitle('Job Application Report');
    $pdf->SetSubject('Job Application Data');
    $pdf->SetKeywords('TCPDF, Job, Application');

    // Add a page
    $pdf->AddPage();

    // Set font
    $pdf->SetFont('helvetica', 'B', 16);

    // Add headline
    $pdf->Cell(0, 10, 'Job Application Report', 0, 1, 'C');
    $pdf->SetFont('helvetica', '', 12);

    // Fetch Data from the Database using Prepared Statements for security
    
    // Using a function to fetch data safely
    function fetchData($conn, $sql, $params, $types) {
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();
            $stmt->close();
            return $data;
        }
        return null;
    }

    $row = fetchData($conn, "SELECT * FROM job_post WHERE id_jobpost=?", [$id_jobpost], "i");
    $row1 = fetchData($conn, "SELECT * FROM company WHERE id_company=?", [$id_company], "i");
    $row2 = fetchData($conn, "SELECT * FROM states WHERE id=?", [$row['state_id']], "i");
    $row3 = fetchData($conn, "SELECT * FROM districts_or_cities WHERE id=?", [$row['city_id']], "i");
    $row4 = fetchData($conn, "SELECT * FROM education WHERE id=?", [$row['edu_qualification']], "i");
    $jobtype = fetchData($conn, "SELECT type FROM job_type WHERE id=?", [$row['job_status']], "i");
    $row5 = fetchData($conn, "SELECT * FROM users WHERE id_user=?", [$id_user], "i");
    $industry = fetchData($conn, "SELECT name FROM industry WHERE id=?", [$row['industry_id']], "i");

    // Generate content
    $content = "
                                                                Job Details
                                                                    --------
    Job Title : {$row['jobtitle']}
    Job-Category : {$industry['name']}
    Salary(Tk.) : {$row['minimumsalary']} - {$row['maximumsalary']}
    Company Name : {$row1['companyname']}
    State/Division : {$row2['name']}
    District/City : {$row3['name']}
    Educational Requirement : {$row4['name']}
    Job Type : {$jobtype['type']}
    -------------------------------------------------------------------------------------------------------------------------------
                                                          Applicants Details
                                                                -------------
    Fullname : {$row5['fullname']}
    Email : {$row5['email']}
    Gender : {$row5['gender']}
    Contact : {$row5['contactno']}
    Date-Of-Birth : {$row5['dob']}
    Address : {$row5['address']}
";

    // Add content to PDF
    $pdf->MultiCell(0, 10, $content);
    // Close and output PDF document
    $pdf->Output('job_application_report.pdf', 'I');
    
    */ // نهاية القسم المعطل
    
    return true; // إرجاع نجاح مؤقت
}

if (isset($_GET['id'])) {
  if (!isset($_SESSION['email'])) {
    header("location: ../login.php");
    exit();
  } else {
    $id_jobpost = intval($_GET['id']);
    $id_company = intval($_GET['cid']);
    $id_user = intval($_SESSION['id_user']);
    $createdat = date("Y-m-d");

    // تحقق من وجود السيرة الذاتية أولاً
    $sql_check = "SELECT resume FROM users WHERE id_user = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("i", $id_user);
    $stmt_check->execute();
    $result = $stmt_check->get_result();
    $resume = $result->fetch_assoc();

    if (!$resume['resume']) {
      $_SESSION['message'] = "Please upload your resume first!";
      header("location: ../dashboard/myresume.php");
      exit();
    } else {
      // إدراج طلب التقديم باستخدام Prepared Statements
      $sql = "INSERT INTO applied_jobposts (id_jobpost, id_user, id_company, createdat) VALUES (?, ?, ?, ?)";
      $stmt = $conn->prepare($sql);
      if ($stmt) {
        $stmt->bind_param("iiis", $id_jobpost, $id_user, $id_company, $createdat);
        $stmt->execute();
        $stmt->close();
      }

      // بعد التقديم بنجاح، استدعاء دالة إنشاء التقرير
      generateJobApplicationReport($conn, $id_jobpost, $id_company, $id_user);
    }
    
    header('location: ../jobDetails.php?key=' . md5($id_jobpost) . '&id=' . $id_jobpost . '');
  }
}