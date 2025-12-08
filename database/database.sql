-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th12 06, 2025 lúc 04:55 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `lendly_db`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `name`, `parent_id`) VALUES
(1, 'Điện thoại', NULL),
(2, 'Laptop', NULL),
(3, 'Phụ kiện', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `item`
--

CREATE TABLE `item` (
  `id` int(11) NOT NULL,
  `sku_id` int(11) DEFAULT NULL,
  `serial_number` varchar(255) DEFAULT NULL,
  `status` enum('in_stock','sold','returned') DEFAULT 'in_stock',
  `import_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `status` enum('pending','confirmed','processing','shipping','delivered','cancelled') DEFAULT 'pending',
  `fullname` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `sku_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_tracking`
--

CREATE TABLE `order_tracking` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sku`
--

CREATE TABLE `sku` (
  `id` int(11) NOT NULL,
  `spu_id` int(11) DEFAULT NULL,
  `sku_code` varchar(255) DEFAULT NULL,
  `variant` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`variant`)),
  `price` decimal(10,2) DEFAULT NULL,
  `promo_price` decimal(10,2) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL,
  `warehouse_location` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `sku`
--

INSERT INTO `sku` (`id`, `spu_id`, `sku_code`, `variant`, `price`, `promo_price`, `stock`, `warehouse_location`, `created_at`) VALUES
(1, 1, 'IP14-128', '{\"storage\":\"128GB\",\"color\":\"Blue\"}', 19990000.00, 18990000.00, 50, 'KHO1', '2025-12-06 03:31:49'),
(2, 2, 'SS23-256', '{\"storage\":\"256GB\",\"color\":\"Black\"}', 21990000.00, 20990000.00, 40, 'KHO2', '2025-12-06 03:31:49'),
(3, 3, 'RN12-128', '{\"storage\":\"128GB\",\"color\":\"Gray\"}', 5990000.00, 5490000.00, 100, 'KHO1', '2025-12-06 03:31:49'),
(4, 4, 'MBA-M2-256', '{\"color\":\"Space Gray\",\"storage\":\"256GB\"}', 28990000.00, 27990000.00, 30, 'KHO3', '2025-12-06 03:31:49'),
(5, 5, 'DXPS13-512', '{\"color\":\"Silver\",\"storage\":\"512GB SSD\"}', 34990000.00, 32990000.00, 20, 'KHO2', '2025-12-06 03:31:49'),
(6, 6, 'ASUS-VB15', '{\"cpu\":\"i5\",\"ram\":\"8GB\",\"storage\":\"256GB\"}', 14990000.00, 13990000.00, 80, 'KHO1', '2025-12-06 03:31:49');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sku_images`
--

CREATE TABLE `sku_images` (
  `id` int(11) NOT NULL,
  `sku_id` int(11) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `is_primary` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `spu`
--

CREATE TABLE `spu` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `brand` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `spu`
--

INSERT INTO `spu` (`id`, `name`, `brand`, `category_id`, `description`, `created_at`) VALUES
(1, 'iPhone 14', 'Apple', 1, 'Điện thoại Apple iPhone 14, hiệu năng mạnh mẽ.', '2025-12-06 03:15:50'),
(2, 'Samsung Galaxy S23', 'Samsung', 1, 'Flagship mới nhất của Samsung.', '2025-12-06 03:15:50'),
(3, 'Xiaomi Redmi Note 12', 'Xiaomi', 1, 'Máy ngon giá rẻ.', '2025-12-06 03:15:50'),
(4, 'MacBook Air M2', 'Apple', 2, 'Laptop Apple hiệu năng mạnh, dùng chip M2.', '2025-12-06 03:15:50'),
(5, 'Dell XPS 13', 'Dell', 2, 'Ultrabook cao cấp.', '2025-12-06 03:15:50'),
(6, 'Asus VivoBook 15', 'Asus', 2, 'Laptop phổ thông, phù hợp sinh viên.', '2025-12-06 03:15:50');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('customer','admin') DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `password`, `role`, `created_at`) VALUES
(5, 'test user', 'user@gmail.com', '$2y$10$CIHU9nhtsgDv7WFoj49B3.V8x62xguhZfwFrRX5VaeRhEjftL7wwe', '', '2025-12-03 12:56:54'),
(6, 'Admin', 'admin@gmail.com', '$2y$10$CIHU9nhtsgDv7WFoj49B3.V8x62xguhZfwFrRX5VaeRhEjftL7wwe', 'admin', '2025-12-03 12:56:54');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sku_id` (`sku_id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `sku_id` (`sku_id`);

--
-- Chỉ mục cho bảng `order_tracking`
--
ALTER TABLE `order_tracking`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Chỉ mục cho bảng `sku`
--
ALTER TABLE `sku`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sku_code` (`sku_code`),
  ADD KEY `spu_id` (`spu_id`);

--
-- Chỉ mục cho bảng `sku_images`
--
ALTER TABLE `sku_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sku_id` (`sku_id`);

--
-- Chỉ mục cho bảng `spu`
--
ALTER TABLE `spu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `item`
--
ALTER TABLE `item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `order_tracking`
--
ALTER TABLE `order_tracking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `sku`
--
ALTER TABLE `sku`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `sku_images`
--
ALTER TABLE `sku_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `spu`
--
ALTER TABLE `spu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `item_ibfk_1` FOREIGN KEY (`sku_id`) REFERENCES `sku` (`id`);

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`sku_id`) REFERENCES `sku` (`id`);

--
-- Các ràng buộc cho bảng `order_tracking`
--
ALTER TABLE `order_tracking`
  ADD CONSTRAINT `order_tracking_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Các ràng buộc cho bảng `sku`
--
ALTER TABLE `sku`
  ADD CONSTRAINT `sku_ibfk_1` FOREIGN KEY (`spu_id`) REFERENCES `spu` (`id`);

--
-- Các ràng buộc cho bảng `sku_images`
--
ALTER TABLE `sku_images`
  ADD CONSTRAINT `sku_images_ibfk_1` FOREIGN KEY (`sku_id`) REFERENCES `sku` (`id`);

--
-- Các ràng buộc cho bảng `spu`
--
ALTER TABLE `spu`
  ADD CONSTRAINT `spu_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
