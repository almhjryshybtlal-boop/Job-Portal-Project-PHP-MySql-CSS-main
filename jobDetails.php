<?php
require_once "./includes/Database.php";
include "./includes/indexHeader.php";

$database = new Database();
$conn = $database->getConnection();

$jobDetails = null;
$companyDetails = null;
$stateDetails = null;
$cityDetails = null;
$educationDetails = null;
$jobType = null;

if (isset($_GET['key']) && isset($_GET['id']) && $conn) {
    $job_id = intval($_GET['id']); // تأمين المتغير
    $jobDetails = $database->fetchDetails("job_post", "id_jobpost", $job_id);

    if ($jobDetails) {
        $companyDetails = $database->fetchDetails("company", "id_company", $jobDetails['id_company']);
        $stateDetails = $database->fetchDetails("states", "id", $jobDetails['state_id']);
        $cityDetails = $database->fetchDetails("districts_or_cities", "id", $jobDetails['city_id']);
        $educationDetails = $database->fetchDetails("education", "id", $jobDetails['edu_qualification']);
        $jobType = $database->fetchDetails("job_type", "id", $jobDetails['job_status']);
    }
}
?>
<link rel="stylesheet" href="assets/CSS/styles.css">
  <link rel="stylesheet" href="assets/CSS/responsive.css">
<body>
    <?php include "./includes/indexNavbarr.php" ?>
    
    <!-- Notification Display -->
    <?php include "./includes/notification_display.php" ?>
    
    <?php if ($jobDetails) : ?>
        <div id="browse-job-details">
            <div class="intro-banner">
                <div class="intro-banner-overlay">
                    <div class="intro-banner-content">
                        <div class="container glassmorphism">
                            <div class="banner-headline-text-part">
                                <div class="profile-container">
                                    <img src="./assets/images/<?php echo htmlspecialchars($companyDetails['profile_pic']); ?>" alt="">
                                </div>
                                <div class="job-info-container">
                                    <h3> <?php echo htmlspecialchars($jobDetails['jobtitle']); ?> </h3>
                                    <div class="others-info">
                                        <div class="company-name-info">
                                            <i class="fa-solid fa-briefcase"></i>
                                            <span> <?php echo htmlspecialchars($companyDetails['companyname']); ?> </span>
                                        </div>
                                        <div class="job-status">
                                            <i class="fa-solid fa-briefcase"></i>
                                            <span> <?php echo htmlspecialchars($jobType['type']); ?> </span>
                                        </div>
                                    </div>
                                    <div class="job-location-info">
                                        <div class="location-icon-container">
                                            <i class="fa-solid fa-location-dot"></i>
                                        </div>
                                        <span><?php echo htmlspecialchars($stateDetails['name'] . ", " . $cityDetails['name']); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="job-details-page-content">
                <div class="job-details-page-content-left-side">
                    <div class="job-details-page-content-left-side-description">
                        <div class="headline">
                            <span class="icon-container">
                                <i class="fa-solid fa-circle-exclamation"></i>
                            </span>
                            <h3>وصف الوظيفة</h3>
                        </div>
                        <div class="job-description">
                            <p>
                                <?php echo nl2br(htmlspecialchars($jobDetails['description'])); ?></p>
                        </div>
                    </div>
                    <div class="job-details-page-content-left-side-requirements">
                        <div class="headline">
                            <span class="icon-container">
                                <i class="fa-solid fa-file-lines"></i>
                            </span>
                            <h3>المتطلبات</h3>
                        </div>
                        <div class="job-requirement">
                            <p><?php echo nl2br(htmlspecialchars($jobDetails['responsibility'])); ?></p>
                        </div>
                    </div>
                </div>
                <div class="job-details-page-content-right-side">
                    <div class="information-section">
                        <div class="information-wrapper">
                            <div class="icon-with-title">
                                <div class="icon-container">
                                    <i class="fa-solid fa-info-circle"></i>
                                </div>
                                <h3>نظرة عامة على الوظيفة</h3>
                            </div>
                            <ul class="information-list">
                                <li class="information-list-item">
                                    <div class=" icon-container">
                                        <i class="fa-solid fa-briefcase"></i>
                                    </div>
                                    <div class="info-container">
                                        <span>المسمى الوظيفي:</span>
                                        <span><?php echo htmlspecialchars($jobDetails['jobtitle']); ?></span>
                                    </div>
                                </li>
                                <li class="information-list-item">
                                    <div class=" icon-container">
                                        <i class="fa-solid fa-money-check-dollar"></i>
                                    </div>
                                    <div class="info-container">
                                        <span>الراتب:</span>
                                        <span><?php echo htmlspecialchars($jobDetails['minimumsalary'] . "-" . $jobDetails['maximumsalary'] . " BDT"); ?></span>
                                    </div>
                                </li>
                                <li class="information-list-item">
                                    <div class=" icon-container">
                                        <i class="fa-solid fa-briefcase"></i>
                                    </div>
                                    <div class="info-container">
                                        <span>نوع الوظيفة:</span>
                                        <span><?php echo htmlspecialchars($jobType['type']); ?></span>
                                    </div>
                                </li>
                                <li class="information-list-item">
                                    <div class=" icon-container">
                                        <i class="fa-solid fa-graduation-cap"></i>
                                    </div>
                                    <div class="info-container">
                                        <span>المؤهل العلمي:</span>
                                        <span><?php echo htmlspecialchars($educationDetails['name']); ?></span>
                                    </div>
                                </li>
                                <li class="information-list-item">
                                    <div class=" icon-container">
                                        <i class="fa-solid fa-location-dot"></i>
                                    </div>
                                    <div class="info-container">
                                        <span>الموقع:</span>
                                        <span><?php echo htmlspecialchars($stateDetails['name'] . ", " . $cityDetails['name']); ?></span>
                                    </div>
                                </li>
                                <li class="information-list-item">
                                    <div class=" icon-container">
                                        <i class="fa-solid fa-clock"></i>
                                    </div>
                                    <div class="info-container">
                                        <span>تاريخ النشر:</span>
                                        <span><?php echo htmlspecialchars($jobDetails['createdat']); ?></span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="information-wrapper">
                            <div class="icon-with-title">
                                <div class="icon-container">
                                    <i class="fa-solid fa-money-check-dollar"></i>
                                </div>
                                <h3>الراتب المقدم</h3>
                            </div>
                            <span> <?php echo "BDT " . htmlspecialchars($jobDetails['minimumsalary']) . " - BDT " . htmlspecialchars($jobDetails['maximumsalary']); ?></span>
                        </div>
                        <div class="information-wrapper">
                            <div class="icon-with-deadline">
                                <div class="icon-container">
                                    <i class="fa-solid fa-calendar-week"></i>
                                </div>
                                <h3>الموعد النهائي</h3>
                            </div>
                            <span><?php echo htmlspecialchars($jobDetails['deadline']); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php else : ?>
        <p>لم يتم العثور على وظيفة بهذا المعرف.</p>
    <?php endif; ?>
    <div id="footer">
        <?php include 'includes/indexFooterWidgets.php'; ?>
        <?php include 'includes/footer.php' ?>
    </div>
</body>
</html>