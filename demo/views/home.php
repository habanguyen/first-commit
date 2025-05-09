<?php
include_once '../config/config.php'; // Kết nối CSDL
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ</title>
    <link rel="stylesheet" href="/demo/assets/css/main.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="top-item">
            <a href="user_login.php" class="user-button"><i class="fa-regular fa-user" style="margin-right: 3px;"></i>Người dùng</a>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Admin'): ?><button><a href="product_add.php">Thêm sản phẩm mới</a></button>
            <?php endif; ?>
        </div>
        <header>
            <img src="https://th.bing.com/th/id/OIP.Fssk5m6e0R66ejp_qiFi5AHaHa?w=167&h=180&c=7&r=0&o=5&dpr=1.3&pid=1.7" alt="Logo" class="logo">
            <form method="GET" action="home.php" class="search-form">
                <input type="text" name="search" placeholder="Tìm kiếm sản phẩm..."
                    value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">

                <button type="submit" class="button">
                    <i class="fa-solid fa-magnifying-glass"></i> Tìm kiếm
                </button>

                <a href="order_process.php" class="button">
                    <i class="fa-solid fa-cart-shopping"></i> Đặt hàng
                </a>
            </form>
        </header>
        <h1><i class="fa-brands fa-product-hunt" style="margin-right: 13px;"></i>Danh sách sản phẩm</h1>
        <div class="category-buttons">
            <a href="home.php?category=Điện thoại" class="btn-category">Điện thoại</a>
            <a href="home.php?category=Linh kiện" class="btn-category">Linh kiện</a>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Admin'): ?>
                <a href="admin_revenue.php" class="btn-category">Doanh thu</a>
            <?php endif; ?>
        </div>
        <div class="product-grid" id="product-grid">
            <?php
            // Kiểm tra điều kiện tìm kiếm hoặc danh mục
            if (isset($_GET['search']) && $_GET['search'] !== '') {
                $search = '%' . $_GET['search'] . '%';
                $sql = "SELECT * FROM Products WHERE Name LIKE ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $search);
            } else {
                $category = isset($_GET['category']) ? $_GET['category'] : 'Điện thoại';
                $sql = "SELECT * FROM Products WHERE Category = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $category);
            }

            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="product-card">';
                    echo '<img src="/demo/assets/img/' . $row["Image"] . '" alt="' . $row["Name"] . '" class="product-image">';
                    echo '<h2 class="product-name">' . $row["Name"] . '</h2>';
                    echo '<p class="product-price">' . number_format($row["Price"], 0, ',', '.') . ' VND</p>';
                    echo '<select class="product-color" id="color-' . $row["ProductID"] . '">';
                    echo '<option value="' . $row["
                    Color"] . '">' . $row["Color"] . '</option>';
                    echo '</select>';

                    if (isset($_SESSION['role']) && $_SESSION['role'] === 'Admin') {
                        echo '<a href="home.php?action=edit&id=' . $row["ProductID"] . '" class="btn-edit">Sửa</a>';
                        echo '<a href="home.php?action=delete&id=' . $row["ProductID"] . '" class="btn-delete" onclick="return confirm(\'Bạn có chắc chắn muốn xóa sản phẩm này?\')">Xóa</a>';
                    } elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'Member') {
                        echo '<button class="btn-buy" onclick="buyNow(' . $row["ProductID"] . ', \'' . $row["Name"] . '\', ' . $row["Price"] . ',\'color-' . $row["ProductID"] . '\')">Mua Ngay</button>';
                        echo '<button class="btn-add-cart" onclick="addToCartAlert(' . $row["ProductID"] . ', \'' . $row["Name"] . '\', ' . $row["Price"] . ',\'color-' . $row["ProductID"] . '\')">Thêm vào giỏ hàng</button>';
                    } else {
                        echo '<button class="btn-buy" onclick="buyNow(' . $row["ProductID"] . ', \'' . $row["Name"] . '\', ' . $row["Price"] . ',\'color-' . $row["ProductID"] . '\')">Mua Ngay</button>';
                    }

                    echo '</div>';
                }
            } else {
                echo "<p>Không tìm thấy sản phẩm nào.</p>";
            }
            $stmt->close();
            ?>
        </div>
        <?php
        if (isset($_GET['action']) && isset($_GET['id'])) {
            $action = $_GET['action'];
            $productId = $_GET['id'];

            if ($action == 'edit') {
                $sql = "SELECT * FROM Products WHERE ProductID=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $productId);
                $stmt->execute();
                $result = $stmt->get_result();
                $product = $result->fetch_assoc();
                $stmt->close();
        ?>
                <div class="edit-form">
                    <h2>Sửa sản phẩm</h2>
                    <form method="POST" action="product_add.php">
                        <input type="hidden" name="product-id" value="<?php echo $product['ProductID']; ?>">
                        <label for="product-name">Tên sản phẩm:</label>
                        <input type="text" id="product-name" name="product-name" value="<?php echo $product['Name']; ?>" required>

                        <label for="product-price">Giá sản phẩm:</label>
                        <input type="number" id="product-price" name="product-price" value="<?php echo $product['Price']; ?>" required>

                        <label for="product-color">Màu sắc sản phẩm:</label>
                        <input type="text" id="product-color" name="product-color" value="<?php echo $product['Color']; ?>" required>

                        <label for="product-stock">Số lượng:</label>
                        <input type="number" id="product-stock" name="product-stock" value="<?php echo $product['Stock']; ?>" required>

                        <label for="product-image">URL hình ảnh sản phẩm:</label>
                        <input type="url" id="product-image" name="product-image" value="<?php echo $product['Image']; ?>" required>

                        <label for="product-category">Danh mục sản phẩm:</label>
                        <select id="product-category" name="product-category" required>
                            <option value="Điện thoại" <?php if ($product['Category'] == 'Điện thoại') echo 'selected'; ?>>Điện thoại</option>
                            <option value="Linh kiện" <?php if ($product['Category'] == 'Linh kiện') echo 'selected'; ?>>Linh kiện</option>
                            <option value="Khác" <?php if ($product['Category'] == 'Khác') echo 'selected'; ?>>Khác</option>
                        </select>

                        <button type="submit" name="update-product">Cập nhật sản phẩm</button>
                    </form>
                </div>
        <?php
            } elseif ($action == 'delete') {
                $sql = "DELETE FROM Products WHERE ProductID=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $productId);

                if ($stmt->execute()) {
                    echo "<script>alert('Sản phẩm đã được xóa thành công!'); window.location.href='product_add.php';</script>";
                } else {
                    echo "<script>alert('Lỗi: " . $conn->error . "');</script>";
                }

                $stmt->close();
            }
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update-product'])) {
            $productId = $_POST['product-id'];
            $productName = $_POST['product-name'];
            $productPrice = $_POST['product-price'];
            $productColor = $_POST['product-color'];
            $productStock = $_POST['product-stock'];
            $productImage = $_POST['product-image'];
            $productCategory = $_POST['product-category'];

            $sql = "UPDATE Products SET Name=?, Color=?, Price=?, Stock=?, Image=?, Category=? WHERE ProductID=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssdissi", $productName, $productColor, $productPrice, $productStock, $productImage, $productCategory, $productId);

            if ($stmt->execute()) {
                echo "<script>alert('Sản phẩm đã được cập nhật thành công!'); window.location.href='home.php';</script>";
            } else {
                echo "<script>alert('Lỗi: " . $conn->error . "');</script>";
            }

            $stmt->close();
        }

        $conn->close();
        ?>
        <script>
            function addToCartAlert(productID, productName, productPrice, colorSelectID) {
                let color = document.getElementById(colorSelectID).value;

                let xhr = new XMLHttpRequest();
                xhr.open("POST", "cart_add.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        alert("Đã thêm " + productName + " (" + color + ") vào giỏ hàng!");
                    }
                };
                xhr.send("product_id=" + productID + "&color=" + encodeURIComponent(color));
            }

            function buyNow(productID, productName, productPrice, colorSelectID) {
                let color = document.getElementById(colorSelectID).value;

                let xhr = new XMLHttpRequest();
                xhr.open("POST", "cart_add.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        window.location.href = "order_process.php"; // Điều hướng đến trang giỏ hàng sau khi mua
                    }
                };
                xhr.send("product_id=" + productID + "&color=" + encodeURIComponent(color));
            }
            function ChatbotConversion() {
                const bot = document.getElementById('chatbot-container');
                if (bot.style.display === 'none' || bot.style.display === '') {
                    bot.style.display = 'block';
                } else {
                    bot.style.display = 'none';
                }
            }

            // Khởi đầu: ẩn chatbot
            window.onload = function() {
                document.getElementById('chatbot-container').style.display = 'none';
            }
        </script>

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
        <!-- Chatbot AI Box -->
        <div id="chatbot-container" style="position: fixed; bottom: 20px; right: 20px; width: 300px; max-height: 500px; z-index: 1000;">
            <iframe
                src="https://www.chatbase.co/chatbot-iframe/YOUR_CHATBOT_ID"
                title="Chatbot"
                width="100%"
                height="100%"
                style="border: none; border-radius: 10px;">
            </iframe>
        </div>
        <button onclick="ChatbotConversion()" style="position: fixed; bottom: 90px; right: 20px; z-index: 1001; padding: 10px 15px; background-color: #007bff; color: white; border: none; border-radius: 50%;">
            💬
        </button>
</body>

</html>