<?php
include_once 'config.php';

// Kiểm tra nếu user chưa đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

// Lấy thông tin người dùng từ bảng Orders
$sql = "SELECT FullName, Phone, Address FROM Orders WHERE UserID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();


// Nếu không tìm thấy user
if (!$user) {
    echo "Lỗi: Người dùng không tồn tại!";
    exit();
}

// Lấy thông tin giỏ hàng từ CSDL
$sql = "SELECT p.ProductID, p.Name, p.Color, p.Price, c.Quantity 
        FROM Cart c 
        JOIN Products p ON c.ProductID = p.ProductID 
        WHERE c.UserID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$cartItems = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Kiểm tra nếu giỏ hàng trống
if (empty($cartItems)) {
    $totalAmount = 0;
} else {
    // Tính tổng tiền giỏ hàng
    $totalAmount = 0;
    foreach ($cartItems as $item) {
        $totalAmount += $item['Price'] * $item['Quantity'];
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán</title>
    <link rel="stylesheet" href="main.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    >
</head>

<body>
    <div class="container">
        <header>
            <img src="https://th.bing.com/th/id/OIP.Fssk5m6e0R66ejp_qiFi5AHaHa?w=167&h=180&c=7&r=0&o=5&dpr=1.3&pid=1.7"
                alt="Logo" class="logo">
            <nav class="menu">
            <ul>
                    <li><a href="main.php"><i class="fa-solid fa-bars" style="margin-right: 3px;"></i> Trang chủ</a></li>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Member'): ?>
                        <li><a href="order.php"><i class="fa-solid fa-cart-shopping" style="margin-right: 3px;"></i>Đặt hàng</a></li>
                    <?php endif; ?>
                    <li><a href="login.php"><i class="fa-regular fa-user" style="margin-right: 3px;"></i>Người dùng</a></li>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Admin'): ?>
                        <li><a href="addproduct.php">Thêm sản phẩm mới</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </header>
        <h1>Thanh toán</h1>
        <div class="order-summary">
            <h2>Tóm tắt đơn hàng</h2>
            <table id="summaryTable">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Màu sắc</th>
                        <th>Đơn giá</th>
                        <th>Số lượng</th>
                        <th>Số tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cartItems as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['Name']); ?></td>
                            <td><?php echo htmlspecialchars($item['Color']); ?></td>
                            <td><?php echo number_format($item['Price'], 0, ',', '.'); ?> VND</td>
                            <td><?php echo $item['Quantity']; ?></td>
                            <td><?php echo number_format($item['Price'] * $item['Quantity'], 0, ',', '.'); ?> VND</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <h3 id="totalAmount">Tổng tiền: <?php echo number_format($totalAmount, 0, ',', '.'); ?> VND</h3>
        </div>

        <div class="payment-form">
            <form action="process_payment.php" method="post">
                <div class="form-group">
                    <label>Thông tin người nhận:</label>
                    <input type="text" name="fullname" value="<?php echo htmlspecialchars($user['FullName']); ?>" required>
                    <input type="text" name="phone" value="<?php echo htmlspecialchars($user['Phone']); ?>" required>
                    <input type="text" name="address" value="<?php echo htmlspecialchars($user['Address']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="payment-method">Phương thức thanh toán:</label>
                    <select id="payment-method" name="payment-method" required>
                        <option value="cash-on-delivery">Thanh toán khi nhận hàng</option>
                        <option value="bank-transfer">Chuyển khoản ngân hàng</option>
                    </select>
                </div>
                <button type="submit" class="btn-submit">Thanh toán</button>
            </form>
            <button id="edit-info-btn" class="btn-submit" onclick="showEditForm()">Sửa thông tin</button>
        </div>

        <div class="edit-info-form">
            <h2>Chỉnh sửa thông tin người nhận</h2>
            <form action="update_user_info.php" method="post">
                <div class="form-group">
                    <label for="edit-fullname">Họ và tên:</label>
                    <input type="text" id="edit-fullname" name="fullname" value="<?php echo htmlspecialchars($user['FullName']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="edit-phone">Số điện thoại:</label>
                    <input type="text" id="edit-phone" name="phone" value="<?php echo htmlspecialchars($user['Phone']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="edit-address">Địa chỉ:</label>
                    <input type="text" id="edit-address" name="address" value="<?php echo htmlspecialchars($user['Address']); ?>" required>
                </div>
                <button type="submit" class="btn-submit">Cập nhật</button>
                <button type="button" class="btn-submit" onclick="hideEditForm()">Hủy</button>
            </form>
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
    <script>
        function showEditForm() {
            document.querySelector('.edit-info-form').style.display = 'block';
        }

        function hideEditForm() {
            document.querySelector('.edit-info-form').style.display = 'none';
        }

        document.addEventListener("DOMContentLoaded", function() {
            document.querySelector(".edit-info-form form").addEventListener("submit", function(event) {
                event.preventDefault(); // Ngăn chặn reload trang

                let formData = new FormData(this);

                fetch("update_user_info.php", {
                        method: "POST",
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert("Thông tin đã được cập nhật!");
                            location.reload(); // Reload lại trang để cập nhật UI
                        } else {
                            alert("Cập nhật thất bại: " + data.message);
                        }
                    })
                    .catch(error => console.error("Lỗi:", error));
            });
        });
    </script>

</body>

</html>
<?php $conn->close(); ?>