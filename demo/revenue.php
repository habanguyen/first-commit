<?php
include_once 'config.php';
checkAdmin();

$sql = "SELECT p.Name, SUM(od.Quantity) AS TotalSold, SUM(od.Quantity * od.Price) AS TotalRevenue
        FROM OrderDetails od
        JOIN Products p ON od.ProductID = p.ProductID
        GROUP BY od.ProductID";
$result = $conn->query($sql);

$productNames = [];
$totalSold = [];
$totalRevenues = [];
$grandTotal = 0; // Tổng doanh thu tất cả sản phẩm

while ($row = $result->fetch_assoc()) {
    $productNames[] = $row["Name"];
    $totalSold[] = $row["TotalSold"];
    $totalRevenues[] = $row["TotalRevenue"];
    $grandTotal += $row["TotalRevenue"]; // Cộng dồn tổng doanh thu
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Doanh thu</title>
    <link rel="stylesheet" href="main.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="min.css">
</head>

<body>
    <div class="container">
        <h1>Doanh thu bán hàng</h1>

        <!-- Bảng doanh thu -->
        <table border="1">
            <tr>
                <th>Tên sản phẩm</th>
                <th>Số lượng đã bán</th>
                <th>Doanh thu (VND)</th>
            </tr>
            <?php foreach ($productNames as $index => $name): ?>
                <tr>
                    <td><?php echo htmlspecialchars($name); ?></td>
                    <td><?php echo $totalSold[$index]; ?></td>
                    <td><?php echo number_format($totalRevenues[$index], 0, ',', '.'); ?> VND</td>
                </tr>
            <?php endforeach; ?>
            <!-- Hàng tổng doanh thu -->
            <tr>
                <td colspan="2" style="font-weight: bold; text-align: right;">Tổng doanh thu:</td>
                <td style="font-weight: bold;"><?php echo number_format($grandTotal, 0, ',', '.'); ?> VND</td>
            </tr>
        </table>

        <!-- Biểu đồ doanh thu -->
        <canvas id="revenueChart"></canvas>

        <div class="button-container">
            <a href="main.php" class="btn">Quay lại trang chủ</a>
            <button class="btn" onclick="createFile()">Tạo file</button>
        </div>
    </div>

    <script>
        // Dữ liệu từ PHP truyền vào JavaScript
        const productNames = <?php echo json_encode($productNames); ?>;
        const totalRevenues = <?php echo json_encode($totalRevenues); ?>;

        // Vẽ biểu đồ với Chart.js
        const ctx = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: productNames,
                datasets: [{
                    label: 'Doanh thu (VND)',
                    data: totalRevenues,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        function createFile() {
            //logic tạo file
            arlet('Tạo file thành công!');
        }
    </script>
</body>

</html>