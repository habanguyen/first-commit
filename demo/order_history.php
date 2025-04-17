<?php
session_start();
include "config.php"; // Kết nối database

// Kiểm tra nếu user chưa đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userID = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT OrderID, TotalAmount, SaleDate FROM Orders WHERE UserID = ?");
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch sử đơn hàng</title>
    <link rel="stylesheet" href="main.css">
</head>

<body>
    <div class="container">
        <header>
            <img src="https://th.bing.com/th/id/OIP.Fssk5m6e0R66ejp_qiFi5AHaHa?w=167&h=180&c=7&r=0&o=5&dpr=1.3&pid=1.7"
                alt="Logo" class="logo">
            <nav class="menu">
                <ul>
                    <li><a href="main.php"><i class="fa-solid fa-bars"></i> Trang chủ</a></li>
                    <?php if ($_SESSION['role'] === 'Member'): ?>
                        <li><a href="order.php"><i class="fa-solid fa-cart-shopping"></i> Đặt hàng</a></li>
                    <?php endif; ?>
                    <li><a href="product.php"><i class="fa-brands fa-product-hunt"></i> Sản phẩm</a></li>
                    <li><a href="login.php"><i class="fa-regular fa-user"></i> Người dùng</a></li>
                    <?php if ($_SESSION['role'] === 'Admin'): ?>
                        <li><a href="addproduct.php">Thêm sản phẩm</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </header>
        <h2>Lịch sử đơn hàng</h2>
        <table border="1">
            <tr>
                <th>Mã đơn hàng</th>
                <th>Tổng tiền</th>
                <th>Ngày đặt</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['OrderID']; ?></td>
                    <td><?php echo number_format($row['TotalAmount'], 0, ',', '.'); ?> VND</td>
                    <td><?php echo $row['SaleDate']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
<footer class="footer">
    <div class="grid">
        <div class="grid__row">
            <div class="grid__column-2-4">
                <h3 class="footer__heading">Chăm Sóc Khách Hàng</h3>
                <ul class="footer__list">
                    <li class="footer-item">
                        <a href="" class="footer-item__link">Trung Tâm Trợ Giúp</a>
                    </li>
                    <li class="footer-item">
                        <a href="" class="footer-item__link">Justin Hoàng Shop</a>
                    </li>
                    <li class="footer-item">
                        <a href="" class="footer-item__link">Hướng Dẫn Mua Hàng</a>
                    </li>
                </ul>
            </div>
            <div class="grid__column-2-4">
                <h3 class="footer__heading">Giới Thiệu</h3>
                <ul class="footer__list">
                    <li class="footer-item">
                        <a href="" class="footer-item__link">Giới Thiệu</a>
                    </li>
                    <li class="footer-item">
                        <a href="" class="footer-item__link">Điều Khoản</a>
                    </li>
                    <li class="footer-item">
                        <a href="" class="footer-item__link">Tuyển Dụng</a>
                    </li>
                </ul>
            </div>
            <div class="grid__column-2-4">
                <h3 class="footer__heading">Theo Dõi</h3>
                <ul class="footer__list">
                    <li class="footer-item">
                        <a href="" class="footer-item__icon footer-item__link">
                            <i class="fa-brands fa-facebook"></i>
                            Facebook
                        </a>
                    </li>
                    <li class="footer-item">
                        <a href="" class="footer-item__icon footer-item__link">
                            <i class="fa-brands fa-instagram"></i>
                            Instagram
                        </a>
                    </li>
                    <li class="footer-item">
                        <a href="" class="footer-item__icon footer-item__link">
                            <i class="fa-brands fa-github"></i>
                            Git-Hub
                        </a>
                    </li>
                </ul>
            </div>
            <div class="grid__column-2-4">
                <h3 class="footer__heading">Danh Mục</h3>
                <ul class="footer__list">
                    <li class="footer-item">
                        <a href="" class="footer-item__link">Trang Điểm</a>
                    </li>
                    <li class="footer-item">
                        <a href="" class="footer-item__link">Skin Care</a>
                    </li>
                    <li class="footer-item">
                        <a href="" class="footer-item__link">Beauty Collection</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</footer>

</html>

<?php
$stmt->close();
$conn->close();
?>