<?php
include_once '../config/config.php'; // Kết nối CSDL
checkAdmin();
echo "Bạn đang đăng nhập với quyền: " . $_SESSION['role'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm sản phẩm mới</title>
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
        <h1>Thêm sản phẩm mới</h1>
        <div class="feedback-form">
            <form id="add-product-form" method="POST" action="addproduct.php">
                <label for="product-name">Tên sản phẩm:</label>
                <input type="text" id="product-name" name="product-name" required>

                <label for="product-price">Giá sản phẩm:</label>
                <input type="number" id="product-price" name="product-price" required>

                <label for="product-color">Màu sắc sản phẩm:</label>
                <input type="text" id="product-color" name="product-color" required>

                <label for="product-stock">Số lượng:</label>
                <input type="number" id="product-stock" name="product-stock" required>

                <label for="product-image">URL hình ảnh sản phẩm:</label>
                <input type="url" id="product-image" name="product-image" required>

                <label for="product-category">Danh mục sản phẩm:</label>
                <select id="product-category" name="product-category" required>
                    <option value="Điện thoại">Điện thoại</option>
                    <option value="Linh kiện">Linh kiện</option>
                    <option value="Khác">Khác</option>
                </select>

                <button type="submit">Thêm sản phẩm</button>
            </form>
        </div>
    </div>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        include_once 'config.php';

        $productName = $_POST['product-name'];
        $productPrice = $_POST['product-price'];
        $productColor = $_POST['product-color'];
        $productStock = $_POST['product-stock'];
        $productImage = $_POST['product-image'];
        $productCategory = $_POST['product-category'];

        $sql = "INSERT INTO Products (Name, Color, Price, Stock, Image, Category) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdiss", $productName, $productColor, $productPrice, $productStock, $productImage, $productCategory);

        if ($stmt->execute()) {
            echo "<script>alert('Sản phẩm đã được thêm thành công!'); window.location.href='home.php';</script>";
        } else {
            echo "<script>alert('Lỗi: " . $conn->error . "');</script>";
        }

        $stmt->close();
        $conn->close();
    }
    ?>
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