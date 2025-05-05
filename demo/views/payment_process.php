<?php
include_once '../config/config.php'; // Kết nối CSDL

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

// Lấy dữ liệu từ form
$fullname = $_POST['fullname'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$paymentMethod = $_POST['payment-method'];

// Lấy giỏ hàng của người dùng
$sql = "SELECT p.ProductID, p.Price, c.Quantity 
        FROM Cart c 
        JOIN Products p ON c.ProductID = p.ProductID 
        WHERE c.UserID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$cartItems = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Nếu giỏ hàng trống, dừng xử lý
if (empty($cartItems)) {
    echo "<script>alert('Giỏ hàng của bạn đang trống!'); window.location.href='cart.php';</script>";
    exit();
}

// Tính tổng tiền
$totalAmount = 0;
foreach ($cartItems as $item) {
    $totalAmount += $item['Price'] * $item['Quantity'];
}

// Thêm đơn hàng vào bảng Orders
$sql = "INSERT INTO Orders (UserID, TotalAmount, PaymentMethod, FullName, Phone, Address) 
        VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("idssss", $userId, $totalAmount, $paymentMethod, $fullname, $phone, $address);
$stmt->execute();
$orderId = $stmt->insert_id;
$stmt->close();

// Thêm chi tiết đơn hàng vào bảng OrderDetails
$sql = "INSERT INTO OrderDetails (OrderID, ProductID, Quantity, Price) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
foreach ($cartItems as $item) {
    $stmt->bind_param("iiid", $orderId, $item['ProductID'], $item['Quantity'], $item['Price']);
    $stmt->execute();
}
$stmt->close();

// Xóa giỏ hàng sau khi đặt hàng thành công
$sql = "DELETE FROM Cart WHERE UserID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->close();

// Hiển thị thông báo và chuyển hướng
echo "<script>alert('Đặt hàng thành công! Đơn hàng của bạn đang được xử lý.'); window.location.href='order_history.php';</script>";

$conn->close();
