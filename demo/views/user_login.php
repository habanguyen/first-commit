<?php
session_start();
include_once '../config/config.php'; // Kết nối CSDL

// Xử lý đăng nhập nếu có request POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    if (!empty($username) && !empty($password)) {
        // Lấy cả Role từ database
        $sql = "SELECT UserID, Username, Password, Role FROM Users WHERE Username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            if (password_verify($password, $row["Password"])) {
                $_SESSION["user_id"] = $row["UserID"];
                $_SESSION["username"] = $row["Username"];
                $_SESSION["role"] = $row["Role"];

                // Debug để kiểm tra session sau khi set
                // echo "<pre>";
                // print_r($_SESSION);
                // echo "</pre>";
                // exit();

                header("Location: home.php");
                exit();
            } else {
                $error = "Mật khẩu không đúng!";
            }
        } else {
            $error = "Tên đăng nhập không tồn tại!";
        }
    } else {
        $error = "Vui lòng nhập đầy đủ thông tin!";
    }
}

// Đăng xuất
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: user_login.php");
    exit();
}

// Kiểm tra đăng nhập
$loginRequired = !isset($_SESSION["user_id"]);
?>


<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $loginRequired ? "Đăng Nhập" : "Thông tin cá nhân"; ?></title>
    <link rel="stylesheet" href="/demo/assets/css/main.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

</head>

<body>
    <div class="container">
        <header>
            <img src="https://th.bing.com/th/id/OIP.Fssk5m6e0R66ejp_qiFi5AHaHa?w=167&h=180&c=7&r=0&o=5&dpr=1.3&pid=1.7"
                alt="Logo" class="logo">
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
        <div class="login-container">
            <?php if ($loginRequired): ?>
                <div class="header_login">
                    <h2>Đăng Nhập</h2>
                </div>
                <div class="login">
                    <form method="POST" action="user_login.php">
                        <label for="username">Tên đăng nhập:</label><br>
                        <input type="text" id="username" name="username" placeholder="Nhập tên đăng nhập" required><br><br>

                        <label for="password">Mật khẩu:</label><br>
                        <input type="password" id="password" name="password" placeholder="Nhập mật khẩu" required><br><br>

                        <button type="submit" name="login">Đăng Nhập</button>
                    </form>
                    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
                    <p class="comment">Bạn chưa có tài khoản? <a href="user_register.php">Đăng ký</a></p>
                <?php else: ?>
                    <div class="header_login">
                        <h2>Thông tin cá nhân</h2>
                    </div>
                    <div class="login">
                        <p><strong>Tên đăng nhập:</strong> <?php echo $_SESSION['username']; ?></p>
                        <button type="submit" name="login" onclick="window.location.href='user_login.php?logout=true'">Đăng xuất</button>
                    </div>
                <?php endif; ?>
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