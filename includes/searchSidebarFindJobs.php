<div class="sidebar-container">
  <div class="keyword-search-container">
    <div class="search-form-container">
      <form method="post" action="./searchJob.php">
        <div class="input-group">
          <label for="search-keyword">كلمات البحث</label>
          <div class="line line-light line-light-left"></div>
          <div class="search-input-wrapper">
            <input type="search" name="searchKeyword" placeholder="ابحث بالكلمات المفتاحية..." required>
            <button type="submit" name="submitSearch"><i class="fa-solid fa-magnifying-glass"></i></button>
          </div>
        </div>
      </form>
    </div>
  </div>
  <div class="location-search-container">
    <div class="search-form-container">
      <form method="post" action="searchJob.php">
        <div class="input-group">
          <label for="location-keyword">الموقع</label>
          <div class="line line-light line-light-left"></div>
          <div class="select-container">
            <select id="select-category" name="location-search">
              <option value="">جميع المواقع</option>
              <?php
              $districtOrCitySql = "SELECT * from districts_or_cities";
              $districtOrCityQuery = $conn->query($districtOrCitySql);
              while ($districtOrCity = $districtOrCityQuery->fetch_assoc()) {
              ?>
                <option value="<?php echo $districtOrCity['id'] ?>"><?php echo $districtOrCity['name'] ?></option>
              <?php } ?>
            </select>
            <span class="custom-arrow"></span>
          </div>
        </div>
        <button type="submit" class="btn" name="submitSearch">بحث</button>
      </form>
    </div>
  </div>
  <div class="category-search-container">
    <div class="search-form-container">
      <form method="post" action="searchJob.php">
        <div class="input-group">
          <label for="category-keyword">الفئة</label>
          <div class="line line-light line-light-left"></div>
          <div class="select-container">
            <select id="select-category" name="category-search">
              <option value="">جميع الفئات</option>
              <?php
              $jobCategorySql = "SELECT * from industry";
              $jobCategoryQuery = $conn->query($jobCategorySql);
              while ($jobCategory = $jobCategoryQuery->fetch_assoc()) {
              ?>
                <option value="<?php echo $jobCategory['id'] ?>"><?php echo $jobCategory['name'] ?></option>
              <?php } ?>
            </select>
            <span class="custom-arrow"></span>
          </div>
        </div>
        <button type="submit" class="btn" name="submitSearch">بحث</button>
      </form>
    </div>
  </div>
  <div class="job-type-search-container">
    <div class="search-form-container">
      <form method="post" action="searchJob.php">
        <div class="input-group">
          <label for="type-keyword">نوع الوظيفة</label>
          <div class="line line-light line-light-left"></div>
          <div class="select-container">
            <select id="select-category" name="job-type-search">
              <option value="">جميع أنواع الوظائف</option>
              <?php
              $jobTypeSql = "SELECT * from job_type";
              $jobTypeQuery = $conn->query($jobTypeSql);
              while ($jobType = $jobTypeQuery->fetch_assoc()) {
              ?>
                <option value="<?php echo $jobType['id'] ?>"><?php echo $jobType['type'] ?></option>
              <?php } ?>
            </select>
            <span class="custom-arrow"></span>
          </div>
        </div>
        <button type="submit" class="btn" name="submitSearch">بحث</button>
      </form>
    </div>
  </div>
</div>