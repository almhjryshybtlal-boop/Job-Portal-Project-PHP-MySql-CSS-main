<?php include 'includes/indexHeader.php'; ?>
<link rel="stylesheet" href="assets/CSS/styles.css">
  <link rel="stylesheet" href="assets/CSS/responsive.css">
<body>
  <?php include 'includes/indexNavbarr.php' ?>
  
  <!-- Notification Display -->
  <?php include "./includes/notification_display.php" ?>

  <div class="container">
    <div class="modal-content">
      <div class="signin-form-part">
        <ul class="popup-tabs-nav-item">
          <li><a href="#login">تسجيل الدخول</a></li>
          <li><a href="#register">إنشاء حساب</a></li>
        </ul>

        <div class="popup-container-part-tabs">
          <div class="popup-tab-content-item" id="login">
            <div class="welcome-text-item">
              <h3>مرحباً بعودتك! سجل الدخول للمتابعة</h3>
              <span>ليس لديك حساب؟ <a href="#" class="register-tab">سجل الآن!</a></span>
            </div>
            <form method="post" id="login-form" action="./process/login.php">
              <div class="input-group">
                <div class="select-container">
                  <select id="select-category" name="acctype">
                    <option value="Applicant">باحث عن عمل / متقدم</option>
                    <option value="Employer">صاحب عمل</option>
                    <option value="Admin">مدير</option>
                  </select>
                  <span class="custom-arrow"></span>
                </div>
              </div>
              <div class="input-group">
                <input type="email" name="email" id="email" placeholder="البريد الإلكتروني" required />
              </div>
              <div class="input-group">
                <input type="password" name="password" id="password" placeholder="كلمة المرور" required />
              </div>
              <button class="btn btn-secondary-form" type="submit" id="loginbtn" name="loginbtn">تسجيل الدخول</button>
            </form>
          </div>
          <div class="popup-tab-content-item" id="register">
            <div class="welcome-text-item">
              <h3>انضم إلى منصتنا وابدأ اليوم!</h3>
              <span>هل لديك حساب بالفعل؟ <a href="#" class="login-tab">سجل الدخول!</a></span>
            </div>
            <form method="post" id="register-form" action="./process/register.php">
              <div class="input-group">
                <div class="select-container">
                  <select id="select-category" name="acctype">
                    <option value="Applicant">باحث عن عمل / متقدم</option>
                    <option value="Employer">صاحب عمل</option>
                    <option value="Admin">مدير</option>
                  </select>
                  <span class="custom-arrow"></span>
                </div>
              </div>
              <div class="input-group">
                <input type="text" name="fullname" placeholder="الاسم الكامل / اسم الشركة" required />
              </div>
              <div class="input-group">
                <input type="email" name="email" placeholder="البريد الإلكتروني" required />
              </div>
              <div class="input-group">
                <input type="password" name="password" placeholder="كلمة المرور" required minlength="6" />
              </div>
              <div class="input-group">
                <input type="password" name="passwordrepeat" placeholder="تأكيد كلمة المرور" required minlength="6" />
              </div>
              <button class="btn btn-secondary-form" type="submit" id="regisbtn" name="registerbtn">إنشاء حساب</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="footer">
    <?php include 'includes/indexFooterWidgets.php'; ?>
    <?php include 'includes/footer.php' ?>
  </div>
</body>
</html>