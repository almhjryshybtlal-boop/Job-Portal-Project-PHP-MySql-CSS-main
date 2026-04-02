<?php
require_once "./includes/Database.php";
include "./includes/indexHeader.php";

$database = new Database();
$conn = $database->getConnection();

$total_jobs = 0;
$total_users = 0;
$total_companies = 0;

if ($conn) {
    $sql_jobs = "SELECT COUNT(*) AS total FROM job_post";
    $result_jobs = $conn->query($sql_jobs);
    if ($result_jobs) {
        $row_jobs = $result_jobs->fetch_assoc();
        $total_jobs = $row_jobs['total'];
    }

    $sql_users = "SELECT COUNT(*) AS total FROM users";
    $result_users = $conn->query($sql_users);
    if ($result_users) {
        $row_users = $result_users->fetch_assoc();
        $total_users = $row_users['total'];
    }

    $sql_companies = "SELECT COUNT(*) AS total FROM company";
    $result_companies = $conn->query($sql_companies);
    if ($result_companies) {
        $row_companies = $result_companies->fetch_assoc();
        $total_companies = $row_companies['total'];
    }
    $conn->close();
}
?>
 <link rel="stylesheet" href="assets/CSS/styles.css">
  <link rel="stylesheet" href="assets/CSS/responsive.css">
<body>
    <?php include "./includes/indexNavbarr.php" ?>
    <?php include "./includes/indexChat.php" ?>
    
    <!-- Notification Display -->
    <?php include "./includes/notification_display.php" ?>
    
    <div id="home-page">
        <div class="intro-banner">
            <div class="intro-banner-overlay">
                <div class="intro-banner-content">
                    <div class="container glassmorphism">
                        <div class="banner-headline-text-part">
                            <h3>مرحباً بك في بوابة الوظائف</h3>
                            <div class="line line-dark"></div>
                            <p>ابحث عن وظيفتك المثالية. استكشف الفرص المثيرة.</p>
                            <div>
                                <a href="" class="btn btn-secondary">تصفح قائمة الوظائف</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <section id="icon-boxes">
            <div class="container">
                <div class="icon-boxes-content">
                    <div class="upper-side">
                        <div class="section-headline-item">
                            <h2>كيف تعمل؟</h2>
                            <div class="line"></div>
                            <p class="slogan-text">اكتشف قوة بوابة الوظائف لدينا</p>
                        </div>
                    </div>
                    <div class="bottom-side">
                        <div class="icon-box">
                            <div class="icon-box-circle">
                                <div class="icon-box-circle-inner"><i class="fa-solid fa-user-plus"></i></div>
                            </div>
                            <h3>إنشاء حساب</h3>
                            <p>أنشئ حسابك واحصل على الوصول إلى عالم من فرص العمل.</p>
                        </div>
                        <div class="icon-box">
                            <div class="icon-box-circle">
                                <div class="icon-box-circle-inner"><i class="fa-solid fa-file-arrow-up"></i></i></div>
                            </div>
                            <h3>رفع السيرة الذاتية</h3>
                            <p>اعرض مهاراتك وخبراتك من خلال رفع سيرتك الذاتية الاحترافية.</p>
                        </div>
                        <div class="icon-box">
                            <div class="icon-box-circle">
                                <div class="icon-box-circle-inner"><i class="fa-solid fa-file-invoice"></i></div>
                            </div>
                            <h3>التقدم للوظيفة</h3>
                            <p>تقدم للوظيفة المثالية وأرسل طلبك بنقرة واحدة.</p>
                        </div>
                        <div class="icon-box">
                            <div class="icon-box-circle">
                                <div class="icon-box-circle-inner"><i class="fa-solid fa-check"></i></div>
                            </div>
                            <h3>احصل على وظيفة</h3>
                            <p>مبروك، لقد حصلت على وظيفتك المثالية! ابدأ رحلتك المهنية اليوم.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="counter">
            <div class="container">
                <div class="section-headline-item">
                    <h2>أرقامنا</h2>
                    <div class="line"></div>
                    <p class="slogan-text">شاهد إجمالي الوظائف المنشورة، المرشحين، الشركات، إلخ.</p>
                </div>
                <div class="counter-content">
                    <div class="counter-box">
                        <div class="counter-box-circle">
                            <div class="counter-box-circle-inner"><i class="fa-solid fa-briefcase"></i></div>
                        </div>
                        <div class="counter-inner-item">
                            <h3>
                                <span class="counter">
                                    <?php echo htmlspecialchars($total_jobs); ?>
                                </span>
                            </h3>
                            <p class="counter-title">وظيفة منشورة</p>
                        </div>
                    </div>
                    <div class="counter-box">
                        <div class="counter-box-circle">
                            <div class="counter-box-circle-inner"><i class="fa-solid fa-user-check"></i></div>
                        </div>
                        <div class="counter-inner-item">
                            <h3>
                                <span class="counter">
                                    <?php echo htmlspecialchars($total_users); ?>
                                </span>
                            </h3>
                            <p class="counter-title">مرشح للوظائف</p>
                        </div>
                    </div>
                    <div class="counter-box">
                        <div class="counter-box-circle">
                            <div class="counter-box-circle-inner"><i class="fa-sharp fa-solid fa-building"></i></div>
                        </div>
                        <div class="counter-inner-item">
                            <h3>
                                <span class="counter">
                                    <?php echo htmlspecialchars($total_companies); ?>
                                </span>
                            </h3>
                            <p class="counter-title">شركة</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div id="footer">
            <?php include 'includes/indexFooterWidgets.php'; ?>
            <?php include 'includes/footer.php' ?>
        </div>
    </div>
    <script src="./assets/js/chatbot.js"></script>
    <script src="./assets/js/custom.js"></script>
</body>
</html>