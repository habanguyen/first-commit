<!-- filepath: /c:/xampp/htdocs/20222180-NguyenBaHa/admin/demo/user_register.php -->
<?php
include_once '../config/config.php'; // Kết nối CSDL

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $phone = $_POST["phone"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    // SQL chuẩn bị câu lệnh chèn dữ liệu người dùng vào cơ sở dữ liệu
    $sql = "INSERT INTO Users (Username, Password, Phone) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $password, $phone);

    if ($stmt->execute()) {
        echo "<script>alert('Đăng ký thành công!'); window.location.href='user_login.php';</script>";
    } else {
        echo "<script>alert('Lỗi: " . $conn->error . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký</title>
    <link rel="stylesheet" href="/demo/assets/css/main.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <header>
            <img src="https://th.bing.com/th/id/OIP.Fssk5m6e0R66ejp_qiFi5AHaHa?w=167&h=180&c=7&r=0&o=5&dpr=1.3&pid=1.7" alt="Logo" class="logo">
            <nav class="menu">
            <ul>
                    <li><a href="home.php"><i class="fa-solid fa-bars" style="margin-right: 3px;"></i> Trang chủ</a></li>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Member'): ?>
                        <li><a href="order_process.php"><i class="fa-solid fa-cart-shopping" style="margin-right: 3px;"></i>Đặt hàng</a></li>
                    <?php endif; ?>
                    <li><a href="user_login.php"><i class="fa-regular fa-user" style="margin-right: 3px;"></i>Người dùng</a></li>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Admin'): ?>
                        <li><a href="product_add.php">Thêm sản phẩm mới</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </header>

        <div class="register-container">
            <div class="header_login">
                <h2>Đăng Ký Tài Khoản</h2>
            </div>
            <div class="login">
                <form method="POST" action="user_register.php">
                    <label for="username">Tên đăng nhập:</label><br>
                    <input type="text" id="username" name="username" placeholder="Nhập tên đăng nhập" required><br><br>

                    <label for="phone">Số điện thoại:</label><br>
                    <input type="text" id="phone" name="phone" placeholder="Nhập số điện thoại" required><br><br>

                    <label for="password">Mật khẩu:</label><br>
                    <input type="password" id="password" name="password" placeholder="Nhập mật khẩu" required><br><br>

                    <button type="submit">Đăng Ký</button>
                </form>
            </div>
            <div class="comment">
                <label>Bạn đã có tài khoản? <a href="user_login.php">Đăng nhập</a></label>
            </div>
        </div>
    </div>
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
</body>

</html>