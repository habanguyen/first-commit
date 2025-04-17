<?php
include_once 'config.php';
checkLogin();

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    die("Vui lòng đăng nhập để xem giỏ hàng.");
}

try {
    $sql = "SELECT c.CartID, c.ProductID, p.Name, c.Quantity, p.Price, p.Color 
            FROM Cart c
            JOIN Products p ON c.ProductID = p.ProductID
            WHERE c.UserID = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Lỗi truy vấn: " . $conn->error);
    }
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cart_items = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} catch (Exception $e) {
    die("Lỗi hệ thống: " . htmlspecialchars($e->getMessage()));
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng</title>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
        <h1>Giỏ hàng</h1>
        <div class="order-list">
            <table id="orderTable">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Màu sắc</th>
                        <th>Đơn giá</th>
                        <th>Số lượng</th>
                        <th>Tổng tiền</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item): ?>
                        <tr data-id="<?= htmlspecialchars($item['CartID']) ?>">
                            <td><?= htmlspecialchars($item['Name']) ?></td>
                            <td><?= htmlspecialchars($item['Color']) ?></td>
                            <td><?= number_format($item['Price']) ?> VND</td>
                            <td>
                                <button class="update-quantity" data-change="-1">-</button>
                                <span><?= (int)$item['Quantity'] ?></span>
                                <button class="update-quantity" data-change="1">+</button>
                            </td>
                            <td><?= number_format($item['Price'] * $item['Quantity']) ?> VND</td>
                            <td><button class="delete-item">Xóa</button></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <form id="checkoutForm" action="payment.php" method="POST">
            <input type="hidden" name="cart" id="cartData">
            <button type="submit" class="checkout-button">Thanh toán</button>
            <a href="order_history.php">Lịch sử mua hàng</a>
        </form>
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
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector(".order-list").addEventListener("click", function(e) {
                let button = e.target;
                let row = button.closest("tr");
                let cart_id = row.getAttribute("data-id");
                if (!cart_id) return;

                if (button.classList.contains("update-quantity")) {
                    let change = button.getAttribute("data-change");
                    fetch("update_cart.php", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/x-www-form-urlencoded"
                            },
                            body: `cart_id=${cart_id}&change=${change}`
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                let quantitySpan = row.querySelector("td:nth-child(4) span");
                                let totalCell = row.querySelector("td:nth-child(5)");
                                let price = parseInt(row.querySelector("td:nth-child(3)").textContent.replace(" VND", "").replace(",", ""));

                                if (data.quantity === 0) {
                                    row.remove();
                                } else {
                                    quantitySpan.textContent = data.quantity;
                                    totalCell.textContent = (price * data.quantity).toLocaleString() + " VND";
                                }
                            }
                        });
                }

                if (button.classList.contains("delete-item")) {
                    fetch("delete_cart.php", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/x-www-form-urlencoded"
                            },
                            body: `cart_id=${cart_id}`
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                row.remove();
                            }
                        });
                }
            });

            document.querySelector(".checkout-button").addEventListener("click", function(e) {
                e.preventDefault();
                let cartData = [];
                document.querySelectorAll("#orderTable tbody tr").forEach(function(row) {
                    let item = {
                        id: row.getAttribute("data-id"),
                        quantity: row.querySelector("td:nth-child(4) span").textContent
                    };
                    cartData.push(item);
                });
                document.getElementById("cartData").value = JSON.stringify(cartData);
                document.getElementById("checkoutForm").submit();
            });
        });
    </script>
</body>

</html>