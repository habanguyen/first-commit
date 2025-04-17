<?php
session_start();
print_r($_POST);
include_once 'config.php';

// Kiểm tra nếu người dùng đã đăng nhập
if (!isset($_SESSION['user_id'])) {
    echo "Vui lòng đăng nhập để mua hàng!";
    exit;
}

$user_id = $_SESSION['user_id']; // ID người dùng
$product_id = $_POST['product_id'];

// Kiểm tra sản phẩm có tồn tại trong bảng Products không
$sql_check_product = "SELECT * FROM Products WHERE ProductID = ?";
$stmt_check_product = $conn->prepare($sql_check_product);
$stmt_check_product->bind_param("i", $product_id);
$stmt_check_product->execute();
$result_product = $stmt_check_product->get_result();

if ($result_product->num_rows == 0) {
    echo "Sản phẩm không tồn tại!";
    exit;
}

// Kiểm tra sản phẩm đã có trong giỏ hàng chưa
$sql_check_cart = "SELECT Quantity FROM Cart WHERE UserID = ? AND ProductID = ?";
$stmt_check_cart = $conn->prepare($sql_check_cart);
$stmt_check_cart->bind_param("ii", $user_id, $product_id);
$stmt_check_cart->execute();
$result_cart = $stmt_check_cart->get_result();

if ($result_cart->num_rows > 0) {
    // Nếu sản phẩm đã có trong giỏ, tăng số lượng
    $row_cart = $result_cart->fetch_assoc();
    $new_quantity = $row_cart['Quantity'] + 1;

    $sql_update = "UPDATE Cart SET Quantity = ? WHERE UserID = ? AND ProductID = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("iii", $new_quantity, $user_id, $product_id);
    $stmt_update->execute();
    
    echo "Sản phẩm đã được cập nhật trong giỏ hàng!";
} else {
    // Nếu sản phẩm chưa có trong giỏ, thêm mới
    $sql_insert = "INSERT INTO Cart (UserID, ProductID, Quantity) VALUES (?, ?, 1)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("ii", $user_id, $product_id);

    if ($stmt_insert->execute()) {
        echo "Sản phẩm đã được thêm vào giỏ hàng!";
    } else {
        echo "Lỗi: " . $conn->error;
    }
}

// Đóng kết nối
$stmt_check_product->close();
$stmt_check_cart->close();
$conn->close();
?>
