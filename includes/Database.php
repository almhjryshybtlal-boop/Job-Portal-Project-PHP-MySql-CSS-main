<?php
class Database {
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "jobportal";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);
            
            if ($this->conn->connect_error) {
                throw new Exception("فشل الاتصال بقاعدة البيانات: " . $this->conn->connect_error);
            }
            
            $this->conn->set_charset("utf8");
            
        } catch (Exception $exception) {
            echo "خطأ في الاتصال: " . $exception->getMessage();
        }
        return $this->conn;
    }

    // دالة مساعدة لجلب بيانات من جدول
    public function fetchDetails($table, $column, $value) {
        $sql = "SELECT * FROM $table WHERE $column = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $value);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null;
        }
    }

    // دالة إضافية لتنفيذ استعلامات عامة
    public function executeQuery($sql) {
        $result = $this->conn->query($sql);
        return $result;
    }

    // إغلاق الاتصال
    public function closeConnection() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
?>