<?php
// اتصال مباشر بقاعدة البيانات
$host = "localhost";
$username = "root"; 
$password = "";
$database = "jobportal";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}

// إذا كان الطلب لتحميل التقرير
if (isset($_GET['action']) && $_GET['action'] == 'download_report') {
    header('Content-Type: text/html; charset=utf-8');
    header('Content-Disposition: attachment; filename="companies_report_'.date('Y-m-d').'.html"');
    
    // جلب البيانات
    $sql = "SELECT * FROM company ORDER BY createdAt DESC";
    $result = $conn->query($sql);
    
    echo '<!DOCTYPE html>
    <html dir="rtl">
    <head>
        <meta charset="UTF-8">
        <title>تقرير الشركات</title>
        <style>
            body { 
                font-family: Arial, sans-serif; 
                margin: 20px; 
                background: white;
            }
            .header { 
                text-align: center; 
                background: #2c3e50; 
                color: white; 
                padding: 20px; 
                margin-bottom: 20px;
                border-radius: 10px;
            }
            table { 
                width: 100%; 
                border-collapse: collapse; 
                margin: 20px 0;
            }
            th, td { 
                border: 1px solid #ddd; 
                padding: 12px; 
                text-align: center; 
            }
            th { 
                background-color: #34495e; 
                color: white; 
                font-weight: bold;
            }
            tr:nth-child(even) {
                background-color: #f8f9fa;
            }
            .stats {
                display: flex;
                justify-content: center;
                gap: 20px;
                margin: 20px 0;
                flex-wrap: wrap;
            }
            .stat-box {
                background: white;
                padding: 15px;
                border-radius: 10px;
                border: 2px solid #34495e;
                text-align: center;
                min-width: 150px;
            }
            .stat-number {
                font-size: 2em;
                font-weight: bold;
                color: #2c3e50;
            }
            .footer {
                text-align: center;
                margin-top: 30px;
                color: #666;
                font-size: 14px;
            }
            @media print {
                body { margin: 0; }
                .header { background: #2c3e50 !important; }
                th { background: #34495e !important; }
            }
        </style>
    </head>
    <body>
        <div class="header">
            <h1>تقرير الشركات المسجلة</h1>
            <p>نظام بوابة الوظائف - تاريخ التقرير: '.date('Y-m-d').'</p>
        </div>';
        
    // الإحصائيات
    $total_companies = $result->num_rows;
    $total_jobs = $conn->query("SELECT COUNT(*) as total FROM job_post")->fetch_assoc()['total'];
    $active_jobs = $conn->query("SELECT COUNT(*) as total FROM job_post WHERE deadline >= CURDATE()")->fetch_assoc()['total'];
    
    echo '<div class="stats">
            <div class="stat-box">
                <div class="stat-number">'.$total_companies.'</div>
                <div>إجمالي الشركات</div>
            </div>
            <div class="stat-box">
                <div class="stat-number">'.$total_jobs.'</div>
                <div>الوظائف المنشورة</div>
            </div>
            <div class="stat-box">
                <div class="stat-number">'.$active_jobs.'</div>
                <div>وظائف نشطة</div>
            </div>
          </div>
        
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>اسم الشركة</th>
                    <th>المجال الصناعي</th>
                    <th>البريد الإلكتروني</th>
                    <th>رقم الهاتف</th>
                    <th>الموقع الإلكتروني</th>
                    <th>المدينة</th>
                    <th>عدد الموظفين</th>
                    <th>تاريخ التأسيس</th>
                    <th>تاريخ التسجيل</th>
                </tr>
            </thead>
            <tbody>';
    
    $i = 1;
    while($row = $result->fetch_assoc()) {
        $industry = $conn->query("SELECT name FROM industry WHERE id = '{$row['industry_id']}'")->fetch_row()[0] ?? 'غير محدد';
        $city = $conn->query("SELECT name FROM districts_or_cities WHERE id = '{$row['city_id']}'")->fetch_row()[0] ?? 'غير محدد';
        
        echo '<tr>
                <td>'.$i.'</td>
                <td>'.htmlspecialchars($row['companyname']).'</td>
                <td>'.htmlspecialchars($industry).'</td>
                <td>'.htmlspecialchars($row['email']).'</td>
                <td>'.htmlspecialchars($row['contactno'] ?? 'غير متوفر').'</td>
                <td>'.htmlspecialchars($row['website'] ?? 'غير متوفر').'</td>
                <td>'.htmlspecialchars($city).'</td>
                <td>'.($row['empno'] ?? 'غير محدد').'</td>
                <td>'.($row['esta_date'] ?? 'غير محدد').'</td>
                <td>'.$row['createdAt'].'</td>
              </tr>';
        $i++;
    }
    
    echo '</tbody>
        </table>
        
        <div class="footer">
            <p>تم إنشاء هذا التقرير تلقائياً من نظام بوابة الوظائف</p>
            <p>عدد السجلات: '.($i-1).' سجل</p>
        </div>
    </body>
    </html>';
    exit;
}

// الكود العادي للعرض في المتصفح
$sql = "SELECT * FROM company ORDER BY createdAt DESC";
$result = $conn->query($sql);
$total_companies = $result->num_rows;
$total_jobs = $conn->query("SELECT COUNT(*) as total FROM job_post")->fetch_assoc()['total'];
$active_jobs = $conn->query("SELECT COUNT(*) as total FROM job_post WHERE deadline >= CURDATE()")->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تقرير الشركات</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 20px; min-height: 100vh; }
        .report-container { max-width: 1400px; margin: 0 auto; background: white; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.3); overflow: hidden; }
        .report-header { background: linear-gradient(135deg, #2c3e50, #34495e); color: white; padding: 30px; text-align: center; }
        .report-header h1 { font-size: 2.5em; margin-bottom: 10px; }
        .controls { background: #f8f9fa; padding: 20px; text-align: center; border-bottom: 1px solid #dee2e6; }
        .btn { background: linear-gradient(135deg, #28a745, #20c997); color: white; border: none; padding: 12px 25px; border-radius: 25px; cursor: pointer; font-size: 16px; margin: 0 10px; transition: all 0.3s ease; text-decoration: none; display: inline-block; }
        .btn:hover { transform: translateY(-2px); }
        .btn-download { background: linear-gradient(135deg, #dc3545, #c82333); }
        .btn-print { background: linear-gradient(135deg, #007bff, #0056b3); }
        .stats { display: flex; justify-content: center; gap: 20px; margin-top: 15px; flex-wrap: wrap; }
        .stat-box { background: white; padding: 15px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center; min-width: 150px; }
        .stat-number { font-size: 2em; font-weight: bold; color: #2c3e50; }
        .table-container { padding: 20px; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        th { background: linear-gradient(135deg, #34495e, #2c3e50); color: white; padding: 15px; text-align: center; }
        td { padding: 12px 15px; text-align: center; border-bottom: 1px solid #e9ecef; }
        tr:hover { background-color: #f8f9fa; }
        @media (max-width: 768px) { .btn { padding: 10px 15px; margin: 5px; } }
    </style>
</head>
<body>
    <div class="report-container">
        <div class="report-header">
            <h1>🏢 تقرير الشركات المسجلة</h1>
            <p>نظام بوابة الوظائف - قاعدة بيانات الشركات</p>
        </div>
        
        <div class="controls">
            <a href="?action=download_report" class="btn btn-download">📥 تحميل التقرير (HTML)</a>
            <button class="btn btn-print" onclick="window.print()">🖨️ طباعة التقرير</button>
            
            <div class="stats">
                <div class="stat-box"><div class="stat-number"><?php echo $total_companies; ?></div><div>إجمالي الشركات</div></div>
                <div class="stat-box"><div class="stat-number"><?php echo $total_jobs; ?></div><div>الوظائف المنشورة</div></div>
                <div class="stat-box"><div class="stat-number"><?php echo $active_jobs; ?></div><div>وظائف نشطة</div></div>
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>#</th><th>اسم الشركة</th><th>المجال الصناعي</th><th>البريد الإلكتروني</th><th>رقم الهاتف</th><th>الموقع</th><th>المدينة</th><th>تاريخ التسجيل</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        $i = 1;
                        while($row = $result->fetch_assoc()) {
                            $industry = $conn->query("SELECT name FROM industry WHERE id = '{$row['industry_id']}'")->fetch_row()[0] ?? 'غير محدد';
                            $city = $conn->query("SELECT name FROM districts_or_cities WHERE id = '{$row['city_id']}'")->fetch_row()[0] ?? 'غير محدد';
                            
                            echo '<tr>
                                    <td>'.$i.'</td>
                                    <td>'.htmlspecialchars($row['companyname']).'</td>
                                    <td>'.htmlspecialchars($industry).'</td>
                                    <td>'.htmlspecialchars($row['email']).'</td>
                                    <td>'.htmlspecialchars($row['contactno'] ?? 'غير متوفر').'</td>
                                    <td>'.htmlspecialchars($row['website'] ?? 'غير متوفر').'</td>
                                    <td>'.htmlspecialchars($city).'</td>
                                    <td>'.$row['createdAt'].'</td>
                                  </tr>';
                            $i++;
                        }
                    } else {
                        echo '<tr><td colspan="8" style="text-align: center; padding: 30px;">لا توجد بيانات</td></tr>';
                    }
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const downloadBtn = document.querySelector('.btn-download');
            downloadBtn.addEventListener('click', function(e) {
                if(!confirm('سيتم تحميل التقرير كملف HTML. بعد التحميل، يمكنك فتحه بالمتصفح واختيار "Print → Save as PDF" لحفظه كـ PDF.')) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>