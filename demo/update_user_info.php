<?php
require 'db_connection.php'; // Kết nối CSDL

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_start();
    if (!isset($_SESSION['UserID'])) {
        echo json_encode(["success" => false, "message" => "Bạn cần đăng nhập!"]);
        exit;
    }

    $userID = $_SESSION['UserID'];
    $fullname = trim($_POST['fullname']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    // Kiểm tra dữ liệu đầu vào
    if (empty($fullname) || empty($phone) || empty($address)) {
        echo json_encode(["success" => false, "message" => "Vui lòng nhập đầy đủ thông tin!"]);
        exit;
    }

    // Cập nhật thông tin vào bảng Orders (Chỉ cập nhật đơn hàng có UserID hiện tại)
    $sql = "UPDATE Orders SET FullName = ?, Phone = ?, Address = ? WHERE UserID = ? AND OrderStatus = 'Pending'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $fullname, $phone, $address, $userID);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Lỗi khi cập nhật dữ liệu!"]);
    }

    $stmt->close();
    $conn->close();
}
?>
