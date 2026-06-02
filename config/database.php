<?php
    class database{
        private $DB_USER="root";
        private $DB_NAME="web_thuong_mai_dien_tu";
        private $DB_PASSWORD="";
        private $DB_HOST="localhost";
        private $conn=null;

        public function getConnection(){
            try {
                $dsn = "mysql:host=" . $this->DB_HOST . ";dbname=" . $this->DB_NAME . ";charset=utf8";
                $this->conn = new PDO($dsn, $this->DB_USER, $this->DB_PASSWORD);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $this->conn;
            } catch(PDOException $e) { 
                die("Kết nối database thất bại! Lỗi: " . $e->getMessage());
            }
        }
    }
?>