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
    header('Content-Disposition: attachment; filename="jobposts_report_'.date('Y-m-d').'.html"');
    
    // جلب البيانات
    $sql = "SELECT jp.*, c.companyname, i.name as industry_name, jt.type as job_type, 
                   s.name as state_name, dc.name as city_name
            FROM job_post jp 
            LEFT JOIN company c ON jp.id_company = c.id_company 
            LEFT JOIN industry i ON jp.industry_id = i.id 
            LEFT JOIN job_type jt ON jp.job_status = jt.id 
            LEFT JOIN states s ON jp.state_id = s.id 
            LEFT JOIN districts_or_cities dc ON jp.city_id = dc.id 
            ORDER BY jp.createdat DESC";
    $result = $conn->query($sql);
    
    echo '<!DOCTYPE html>
    <html dir="rtl">
    <head>
        <meta charset="UTF-8">
        <title>تقرير الوظائف المنشورة</title>
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
                padding: 10px; 
                text-align: center; 
                font-size: 12px;
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
                gap: 15px;
                margin: 20px 0;
                flex-wrap: wrap;
            }
            .stat-box {
                background: white;
                padding: 12px;
                border-radius: 8px;
                border: 2px solid #34495e;
                text-align: center;
                min-width: 120px;
            }
            .stat-number {
                font-size: 1.5em;
                font-weight: bold;
                color: #2c3e50;
            }
            .status-active {
                background: #d4edda;
                color: #155724;
                padding: 4px 8px;
                border-radius: 12px;
                font-size: 11px;
            }
            .status-expired {
                background: #f8d7da;
                color: #721c24;
                padding: 4px 8px;
                border-radius: 12px;
                font-size: 11px;
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
            <h1>تقرير الوظائف المنشورة</h1>
            <p>نظام بوابة الوظائف - تاريخ التقرير: '.date('Y-m-d').'</p>
        </div>';
        
    // الإحصائيات
    $total_jobs = $result->num_rows;
    $active_jobs = $conn->query("SELECT COUNT(*) as total FROM job_post WHERE deadline >= CURDATE()")->fetch_assoc()['total'];
    $expired_jobs = $conn->query("SELECT COUNT(*) as total FROM job_post WHERE deadline < CURDATE()")->fetch_assoc()['total'];
    $total_companies = $conn->query("SELECT COUNT(*) as total FROM company")->fetch_assoc()['total'];
    
    echo '<div class="stats">
            <div class="stat-box">
                <div class="stat-number">'.$total_jobs.'</div>
                <div>إجمالي الوظائف</div>
            </div>
            <div class="stat-box">
                <div class="stat-number">'.$active_jobs.'</div>
                <div>وظائف نشطة</div>
            </div>
            <div class="stat-box">
                <div class="stat-number">'.$expired_jobs.'</div>
                <div>وظائف منتهية</div>
            </div>
            <div class="stat-box">
                <div class="stat-number">'.$total_companies.'</div>
                <div>الشركات الناشرة</div>
            </div>
          </div>
        
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>المسمى الوظيفي</th>
                    <th>الشركة</th>
                    <th>المجال</th>
                    <th>نوع الوظيفة</th>
                    <th>الراتب (BDT)</th>
                    <th>الموقع</th>
                    <th>الخبرة</th>
                    <th>تاريخ النشر</th>
                    <th>الموعد النهائي</th>
                    <th>الحالة</th>
                </tr>
            </thead>
            <tbody>';
    
    $i = 1;
    while($row = $result->fetch_assoc()) {
        $status_class = (strtotime($row['deadline']) >= strtotime(date('Y-m-d'))) ? 'status-active' : 'status-expired';
        $status_text = (strtotime($row['deadline']) >= strtotime(date('Y-m-d'))) ? 'نشط' : 'منتهي';
        $salary_range = $row['minimumsalary'] . ' - ' . $row['maximumsalary'];
        
        echo '<tr>
                <td>'.$i.'</td>
                <td>'.htmlspecialchars($row['jobtitle']).'</td>
                <td>'.htmlspecialchars($row['companyname']).'</td>
                <td>'.htmlspecialchars($row['industry_name']).'</td>
                <td>'.htmlspecialchars($row['job_type']).'</td>
                <td>'.$salary_range.'</td>
                <td>'.htmlspecialchars($row['city_name']).'</td>
                <td>'.$row['experience'].' سنوات</td>
                <td>'.date('Y-m-d', strtotime($row['createdat'])).'</td>
                <td>'.$row['deadline'].'</td>
                <td><span class="'.$status_class.'">'.$status_text.'</span></td>
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
$sql = "SELECT jp.*, c.companyname, i.name as industry_name, jt.type as job_type, 
               s.name as state_name, dc.name as city_name
        FROM job_post jp 
        LEFT JOIN company c ON jp.id_company = c.id_company 
        LEFT JOIN industry i ON jp.industry_id = i.id 
        LEFT JOIN job_type jt ON jp.job_status = jt.id 
        LEFT JOIN states s ON jp.state_id = s.id 
        LEFT JOIN districts_or_cities dc ON jp.city_id = dc.id 
        ORDER BY jp.createdat DESC";
$result = $conn->query($sql);

// جلب الإحصائيات
$total_jobs = $result->num_rows;
$active_jobs = $conn->query("SELECT COUNT(*) as total FROM job_post WHERE deadline >= CURDATE()")->fetch_assoc()['total'];
$expired_jobs = $conn->query("SELECT COUNT(*) as total FROM job_post WHERE deadline < CURDATE()")->fetch_assoc()['total'];
$total_companies = $conn->query("SELECT COUNT(*) as total FROM company")->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تقرير الوظائف المنشورة</title>
    <style>
        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
        }
        body { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
            padding: 20px; 
            min-height: 100vh; 
        }
        .report-container { 
            max-width: 1600px; 
            margin: 0 auto; 
            background: white; 
            border-radius: 15px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.3); 
            overflow: hidden; 
        }
        .report-header { 
            background: linear-gradient(135deg, #2c3e50, #34495e); 
            color: white; 
            padding: 30px; 
            text-align: center; 
        }
        .report-header h1 { 
            font-size: 2.5em; 
            margin-bottom: 10px; 
        }
        .controls { 
            background: #f8f9fa; 
            padding: 20px; 
            text-align: center; 
            border-bottom: 1px solid #dee2e6; 
        }
        .btn { 
            background: linear-gradient(135deg, #28a745, #20c997); 
            color: white; 
            border: none; 
            padding: 12px 25px; 
            border-radius: 25px; 
            cursor: pointer; 
            font-size: 16px; 
            margin: 0 10px; 
            transition: all 0.3s ease; 
            text-decoration: none; 
            display: inline-block; 
        }
        .btn:hover { 
            transform: translateY(-2px); 
            box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4); 
        }
        .btn-download { 
            background: linear-gradient(135deg, #dc3545, #c82333); 
        }
        .btn-print { 
            background: linear-gradient(135deg, #007bff, #0056b3); 
        }
        .stats { 
            display: flex; 
            justify-content: center; 
            gap: 20px; 
            margin-top: 15px; 
            flex-wrap: wrap; 
        }
        .stat-box { 
            background: white; 
            padding: 15px; 
            border-radius: 10px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.1); 
            text-align: center; 
            min-width: 150px; 
        }
        .stat-number { 
            font-size: 2em; 
            font-weight: bold; 
            color: #2c3e50; 
        }
        .stat-label { 
            color: #6c757d; 
            font-size: 0.9em; 
        }
        .table-container { 
            padding: 20px; 
            overflow-x: auto; 
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            background: white; 
            border-radius: 10px; 
            overflow: hidden; 
            box-shadow: 0 5px 15px rgba(0,0,0,0.1); 
        }
        th { 
            background: linear-gradient(135deg, #34495e, #2c3e50); 
            color: white; 
            padding: 15px; 
            text-align: center; 
            font-weight: 600; 
            font-size: 14px; 
        }
        td { 
            padding: 12px 10px; 
            text-align: center; 
            border-bottom: 1px solid #e9ecef; 
            font-size: 13px; 
        }
        tr:hover { 
            background-color: #f8f9fa; 
        }
        .status-active {
            background: #d4edda;
            color: #155724;
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-expired {
            background: #f8d7da;
            color: #721c24;
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
        }
        .company-name {
            font-weight: bold;
            color: #2c3e50;
        }
        .job-title {
            font-weight: 600;
            color: #34495e;
        }
        .salary {
            color: #28a745;
            font-weight: bold;
        }
        @media (max-width: 768px) { 
            .btn { 
                padding: 10px 15px; 
                margin: 5px; 
                font-size: 14px; 
            } 
            .stats { 
                gap: 10px; 
            } 
            .stat-box { 
                min-width: 120px; 
                padding: 10px; 
            }
            th, td { 
                padding: 8px 6px; 
                font-size: 11px; 
            }
        }
    </style>
</head>
<body>
    <div class="report-container">
        <div class="report-header">
            <h1>💼 تقرير الوظائف المنشورة</h1>
            <p>نظام بوابة الوظائف - جميع الوظائف المتاحة</p>
        </div>
        
        <div class="controls">
            <a href="?action=download_report" class="btn btn-download">📥 تحميل التقرير (HTML)</a>
            <button class="btn btn-print" onclick="window.print()">🖨️ طباعة التقرير</button>
            
            <div class="stats">
                <div class="stat-box">
                    <div class="stat-number"><?php echo $total_jobs; ?></div>
                    <div class="stat-label">إجمالي الوظائف</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number"><?php echo $active_jobs; ?></div>
                    <div class="stat-label">وظائف نشطة</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number"><?php echo $expired_jobs; ?></div>
                    <div class="stat-label">وظائف منتهية</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number"><?php echo $total_companies; ?></div>
                    <div class="stat-label">الشركات الناشرة</div>
                </div>
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th width="4%">#</th>
                        <th width="16%">المسمى الوظيفي</th>
                        <th width="12%">الشركة</th>
                        <th width="12%">المجال</th>
                        <th width="8%">نوع الوظيفة</th>
                        <th width="12%">الراتب (BDT)</th>
                        <th width="8%">الموقع</th>
                        <th width="6%">الخبرة</th>
                        <th width="8%">تاريخ النشر</th>
                        <th width="8%">الموعد النهائي</th>
                        <th width="6%">الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        $i = 1;
                        while($row = $result->fetch_assoc()) {
                            $status_class = (strtotime($row['deadline']) >= strtotime(date('Y-m-d'))) ? 'status-active' : 'status-expired';
                            $status_text = (strtotime($row['deadline']) >= strtotime(date('Y-m-d'))) ? 'نشط' : 'منتهي';
                            $salary_range = $row['minimumsalary'] . ' - ' . $row['maximumsalary'];
                            
                            echo '<tr>
                                    <td>'.$i.'</td>
                                    <td class="job-title">'.htmlspecialchars($row['jobtitle']).'</td>
                                    <td class="company-name">'.htmlspecialchars($row['companyname']).'</td>
                                    <td>'.htmlspecialchars($row['industry_name']).'</td>
                                    <td>'.htmlspecialchars($row['job_type']).'</td>
                                    <td class="salary">'.$salary_range.'</td>
                                    <td>'.htmlspecialchars($row['city_name']).'</td>
                                    <td>'.$row['experience'].' سنوات</td>
                                    <td>'.date('Y-m-d', strtotime($row['createdat'])).'</td>
                                    <td>'.$row['deadline'].'</td>
                                    <td><span class="'.$status_class.'">'.$status_text.'</span></td>
                                  </tr>';
                            $i++;
                        }
                    } else {
                        echo '<tr><td colspan="11" style="text-align: center; padding: 30px;">لا توجد وظائف منشورة</td></tr>';
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