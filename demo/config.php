<?php
// Kết nối MySQL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dbbandienthoai";

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Kiểm tra và bắt đầu session nếu chưa có
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// DEBUG: Kiểm tra session đang lưu gì
// echo "<pre>";
// print_r($_SESSION);
// echo "</pre>";

// Hàm kiểm tra đăng nhập
function checkLogin()
{
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit();
    }
}

// Hàm kiểm tra quyền admin
function checkAdmin()
{
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
        echo "Lỗi: Session không tồn tại!";
        exit();
    }
    if ($_SESSION['role'] !== 'Admin') {
        echo "Bạn không có quyền truy cập!";
        exit();
    }
}
?>
