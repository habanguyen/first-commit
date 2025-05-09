-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 09, 2025 at 06:25 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbbandienthoai`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `CartID` int(11) NOT NULL,
  `UserID` int(11) DEFAULT NULL,
  `ProductID` int(11) DEFAULT NULL,
  `Quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`CartID`, `UserID`, `ProductID`, `Quantity`) VALUES
(5, 4, 3, 1),
(7, 5, 5, 2),
(15, 1, 3, 2),
(16, 1, 4, 1),
(17, 1, 7, 1);

-- --------------------------------------------------------

--
-- Table structure for table `orderdetails`
--

CREATE TABLE `orderdetails` (
  `OrderDetailID` int(11) NOT NULL,
  `OrderID` int(11) DEFAULT NULL,
  `ProductID` int(11) DEFAULT NULL,
  `Quantity` int(11) NOT NULL,
  `Price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orderdetails`
--

INSERT INTO `orderdetails` (`OrderDetailID`, `OrderID`, `ProductID`, `Quantity`, `Price`) VALUES
(1, 8, 3, 2, 15000000.00),
(2, 8, 5, 1, 499000.00),
(3, 8, 1, 1, 26000000.00),
(4, 9, 4, 1, 22000000.00),
(5, 10, 3, 1, 15000000.00),
(6, 10, 4, 1, 22000000.00);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `OrderID` int(11) NOT NULL,
  `UserID` int(11) DEFAULT NULL,
  `TotalAmount` decimal(10,2) NOT NULL,
  `PaymentMethod` enum('Tiền mặt khi nhận hàng','Online') DEFAULT 'Tiền mặt khi nhận hàng',
  `OrderDate` datetime DEFAULT current_timestamp(),
  `Status` enum('Pending','Completed','Cancelled') DEFAULT 'Pending',
  `OrderStatus` enum('Pending','Processing','Completed','Canceled') DEFAULT 'Pending',
  `SaleDate` datetime DEFAULT NULL,
  `FullName` varchar(255) DEFAULT NULL,
  `Phone` varchar(20) DEFAULT NULL,
  `Address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`OrderID`, `UserID`, `TotalAmount`, `PaymentMethod`, `OrderDate`, `Status`, `OrderStatus`, `SaleDate`, `FullName`, `Phone`, `Address`) VALUES
(1, 1, 5000000.00, 'Tiền mặt khi nhận hàng', '2025-03-10 10:30:00', 'Pending', 'Pending', NULL, NULL, NULL, NULL),
(2, 4, 7500000.00, 'Online', '2025-03-10 11:15:00', 'Pending', 'Pending', NULL, NULL, NULL, NULL),
(3, 5, 12000000.00, 'Tiền mặt khi nhận hàng', '2025-03-09 15:45:00', 'Pending', 'Pending', NULL, NULL, NULL, NULL),
(4, 1, 8500000.00, 'Online', '2025-03-08 09:20:00', 'Pending', 'Pending', NULL, NULL, NULL, NULL),
(5, 4, 6700000.00, 'Tiền mặt khi nhận hàng', '2025-03-07 18:10:00', 'Pending', 'Pending', NULL, NULL, NULL, NULL),
(6, 5, 9400000.00, 'Online', '2025-03-06 14:50:00', 'Pending', 'Pending', NULL, NULL, NULL, NULL),
(7, 1, 0.00, 'Tiền mặt khi nhận hàng', '2025-03-14 13:56:12', 'Pending', 'Pending', NULL, 'Nguyen Ba Ha', '1234567890', '<br />\r\n<b>Warning</b>:  Undefined variable $infor in <b>C:\\xampp\\htdocs\\20222180-NguyenBaHa\\demo\\complete.php</b> on line <b>105</b><br />\r\n<br />\r\n<b>Warning</b>:  Trying to access array offset on value of type null in <b>C:\\xampp\\htdocs\\20222180-NguyenBaHa\\demo\\complete.php</b> on line <b>105</b><br />\r\n'),
(8, 1, 56499000.00, '', '2025-03-14 15:17:22', 'Pending', 'Pending', NULL, 'Nguyen Ba Ha', '0338750266', '12-HN'),
(9, 1, 22000000.00, '', '2025-03-14 17:26:59', 'Pending', 'Pending', NULL, 'Nguyen Ba Ha', '0338750266', '12-HN'),
(10, 1, 37000000.00, '', '2025-03-15 20:23:52', 'Pending', 'Pending', NULL, 'Nguyen Ba Ha', '0338750266', '12-HN');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `ProductID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Color` varchar(50) DEFAULT NULL,
  `Price` decimal(10,2) NOT NULL,
  `Stock` int(11) DEFAULT 0,
  `Image` varchar(255) DEFAULT NULL,
  `Category` varchar(100) DEFAULT 'Uncategorized'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`ProductID`, `Name`, `Color`, `Price`, `Stock`, `Image`, `Category`) VALUES
(1, 'Iphone 12prm', 'Xám', 26000000.00, 1, 'iphone12prm.png', 'Điện thoại'),
(3, 'Iphone 12', 'Đen', 15000000.00, 10, 'iphone12.png', 'Điện thoại'),
(4, 'Iphone 13', 'Xanh Dương', 22000000.00, 2, 'iphone13.png', 'Điện thoại'),
(5, 'Ốp lưng iPhone 15 Pro Max Devia Pino từ tính chống sốc', 'Xám', 499000.00, 3, 'iphone15prm.png', 'Linh kiện'),
(6, 'Ốp lưng trong Devia kèm giá đỡ iPhone 14 Pro Max', 'Xanh Dương', 150000.00, 10, 'ol14prm.png', 'Linh kiện'),
(7, 'Ốp lưng Devia chống nước & chống vân tay iPhone 14 Pro Max', 'full', 90000.00, 12, 'olcx14prm.png', 'Linh kiện'),
(8, 'iPhone 15 128GB', 'Hồng', 25000000.00, 5, 'iphone15.png', 'Điện thoại'),
(9, 'iPhone 16 Pro Max 1TB', 'Titan đen', 41900000.00, 3, 'iphone16prm.png', 'Điện thoại'),
(10, 'Sạc dự phòng Energizer 20,000mAh /3.7V Li-Polymer - UE20009BK', 'Xanh Dương', 490000.00, 20, 'sdp.png', 'Linh kiện'),
(11, 'Củ sạc nhanh Vivumax PD20 20W', 'Trắng', 190000.00, 20, 'csn.png', 'Linh kiện'),
(12, 'iPhone 15 Pro 1TB', 'Titan xanh', 32900000.00, 4, 'iphone15pr.png', 'Điện thoại'),
(13, 'iPhone 14 256GB', 'MIDNIGHT', 15900000.00, 10, 'iphone14.png', 'Điện thoại'),
(14, 'Tai nghe AirPods 4', 'Trắng', 3000000.00, 10, 'ap.png', 'Linh kiện'),
(15, 'iPhone 16e 128GB', 'Đen/Trắng', 16990000.00, 3, 'iphone16e.png', 'Điện thoại');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Phone` varchar(20) NOT NULL,
  `Role` enum('Member','Admin') NOT NULL DEFAULT 'Member',
  `CreatedAt` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `Username`, `Password`, `Phone`, `Role`, `CreatedAt`) VALUES
(1, 'habanguyen', '$2y$10$IDr6j8.YcOjUXbwAcx7CLubWwzNOjhhCbcC8oM1gw0SRvo/lg7Nmm', '0338750266', 'Member', '2025-02-28 17:15:53'),
(2, 'admin', '$2y$10$84JqsQSnmKhwrk/Z9rGMyemJ3VL8w7bpoH5a.oRlpoPAVr9xrkh8C', '123456789', 'Admin', '2025-03-05 21:51:34'),
(4, 'user1', 'password123', '0909123456', 'Member', '2025-03-11 21:08:59'),
(5, 'user2', 'password456', '0912345678', 'Member', '2025-03-11 21:08:59'),
(6, 'admin2', 'adminpassword', '0987654321', 'Admin', '2025-03-11 21:08:59');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`CartID`),
  ADD UNIQUE KEY `unique_cart` (`UserID`,`ProductID`),
  ADD KEY `ProductID` (`ProductID`);

--
-- Indexes for table `orderdetails`
--
ALTER TABLE `orderdetails`
  ADD PRIMARY KEY (`OrderDetailID`),
  ADD KEY `OrderID` (`OrderID`),
  ADD KEY `ProductID` (`ProductID`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`OrderID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`ProductID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Username` (`Username`),
  ADD UNIQUE KEY `Phone` (`Phone`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `CartID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `orderdetails`
--
ALTER TABLE `orderdetails`
  MODIFY `OrderDetailID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `OrderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `ProductID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`ProductID`) REFERENCES `products` (`ProductID`) ON DELETE CASCADE;

--
-- Constraints for table `orderdetails`
--
ALTER TABLE `orderdetails`
  ADD CONSTRAINT `orderdetails_ibfk_1` FOREIGN KEY (`OrderID`) REFERENCES `orders` (`OrderID`) ON DELETE CASCADE,
  ADD CONSTRAINT `orderdetails_ibfk_2` FOREIGN KEY (`ProductID`) REFERENCES `products` (`ProductID`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
