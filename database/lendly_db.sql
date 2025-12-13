-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th12 13, 2025 lúc 08:22 AM
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
-- Cấu trúc bảng cho bảng `attributes`
--

CREATE TABLE `attributes` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `attributes`
--

INSERT INTO `attributes` (`id`, `name`) VALUES
(1, 'Storage'),
(2, 'Color'),
(3, 'CPU'),
(4, 'RAM'),
(5, 'SSD');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `attribute_values`
--

CREATE TABLE `attribute_values` (
  `id` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `value` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `attribute_values`
--

INSERT INTO `attribute_values` (`id`, `attribute_id`, `value`) VALUES
(1, 1, '128GB'),
(2, 1, '256GB'),
(3, 1, '512GB'),
(4, 1, '1TB'),
(5, 2, 'Đen Không Gian'),
(6, 2, 'Trắng Ngọc Trai'),
(7, 2, 'Xanh Da Trời'),
(8, 2, 'Titan Sa Mạc'),
(9, 2, 'Xám Bạc'),
(10, 3, 'i5 13500H'),
(11, 3, 'i7 13500H'),
(12, 4, '8GB'),
(13, 4, '16GB'),
(14, 5, '250GB'),
(15, 5, '500GB'),
(16, 5, '1TB');

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
(3, 'Smartwatch', NULL),
(5, 'Macbook', 2),
(6, 'Asus', 2),
(7, 'Acer', 2),
(8, 'Dell', 2),
(9, 'iPhone', 1),
(10, 'Samsung', 1),
(11, 'Oppo', 1),
(12, 'Xiaomi', 1),
(13, 'Realme', 1),
(14, 'Apple Watch', 3),
(15, 'Samsung Watch', 3),
(16, 'Huawei Watch', 3),
(17, 'Oppo Watch', 3),
(18, 'Pixel Watch', 3);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `coupons`
--

CREATE TABLE `coupons` (
  `id` int(11) NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `discount_amount` int(11) DEFAULT NULL,
  `min_order_total` int(11) DEFAULT 0,
  `expired_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `districts`
--

CREATE TABLE `districts` (
  `id` int(11) NOT NULL,
  `province_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `districts`
--

INSERT INTO `districts` (`id`, `province_id`, `name`) VALUES
(80, 10, 'Thành phố Lào Cai'),
(82, 10, 'Huyện Bát Xát'),
(83, 10, 'Huyện Mường Khương'),
(84, 10, 'Huyện Si Ma Cai'),
(85, 10, 'Huyện Bắc Hà'),
(86, 10, 'Huyện Bảo Thắng'),
(87, 10, 'Huyện Bảo Yên'),
(88, 10, 'Huyện Sa Pa'),
(89, 10, 'Huyện Văn Bàn'),
(94, 11, 'Thành phố Điện Biên Phủ'),
(95, 11, 'Thị Xã Mường Lay'),
(96, 11, 'Huyện Mường Nhé'),
(97, 11, 'Huyện Mường Chà'),
(98, 11, 'Huyện Tủa Chùa'),
(99, 11, 'Huyện Tuần Giáo'),
(100, 11, 'Huyện Điện Biên'),
(101, 11, 'Huyện Điện Biên Đông'),
(102, 11, 'Huyện Mường Ảng'),
(103, 11, 'Huyện Nậm Pồ'),
(105, 12, 'Thành phố Lai Châu'),
(106, 12, 'Huyện Tam Đường'),
(107, 12, 'Huyện Mường Tè'),
(108, 12, 'Huyện Sìn Hồ'),
(109, 12, 'Huyện Phong Thổ'),
(110, 12, 'Huyện Than Uyên'),
(111, 12, 'Huyện Tân Uyên'),
(112, 12, 'Huyện Nậm Nhùn'),
(116, 14, 'Thành phố Sơn La'),
(118, 14, 'Huyện Quỳnh Nhai'),
(119, 14, 'Huyện Thuận Châu'),
(120, 14, 'Huyện Mường La'),
(121, 14, 'Huyện Bắc Yên'),
(122, 14, 'Huyện Phù Yên'),
(123, 14, 'Huyện Mộc Châu'),
(124, 14, 'Huyện Yên Châu'),
(125, 14, 'Huyện Mai Sơn'),
(126, 14, 'Huyện Sông Mã'),
(127, 14, 'Huyện Sốp Cộp'),
(128, 14, 'Huyện Vân Hồ'),
(132, 15, 'Thành phố Yên Bái'),
(133, 15, 'Thị xã Nghĩa Lộ'),
(135, 15, 'Huyện Lục Yên'),
(136, 15, 'Huyện Văn Yên'),
(137, 15, 'Huyện Mù Căng Chải'),
(138, 15, 'Huyện Trấn Yên'),
(139, 15, 'Huyện Trạm Tấu'),
(140, 15, 'Huyện Văn Chấn'),
(141, 15, 'Huyện Yên Bình'),
(148, 17, 'Thành phố Hòa Bình'),
(150, 17, 'Huyện Đà Bắc'),
(151, 17, 'Huyện Kỳ Sơn'),
(152, 17, 'Huyện Lương Sơn'),
(153, 17, 'Huyện Kim Bôi'),
(154, 17, 'Huyện Cao Phong'),
(155, 17, 'Huyện Tân Lạc'),
(156, 17, 'Huyện Mai Châu'),
(157, 17, 'Huyện Lạc Sơn'),
(158, 17, 'Huyện Yên Thủy'),
(159, 17, 'Huyện Lạc Thủy'),
(164, 19, 'Thành phố Thái Nguyên'),
(165, 19, 'Thành phố Sông Công'),
(167, 19, 'Huyện Định Hóa'),
(168, 19, 'Huyện Phú Lương'),
(169, 19, 'Huyện Đồng Hỷ'),
(170, 19, 'Huyện Võ Nhai'),
(171, 19, 'Huyện Đại Từ'),
(172, 19, 'Thị xã Phổ Yên'),
(173, 19, 'Huyện Phú Bình'),
(178, 20, 'Thành phố Lạng Sơn'),
(180, 20, 'Huyện Tràng Định'),
(181, 20, 'Huyện Bình Gia'),
(182, 20, 'Huyện Văn Lãng'),
(183, 20, 'Huyện Cao Lộc'),
(184, 20, 'Huyện Văn Quan'),
(185, 20, 'Huyện Bắc Sơn'),
(186, 20, 'Huyện Hữu Lũng'),
(187, 20, 'Huyện Chi Lăng'),
(188, 20, 'Huyện Lộc Bình'),
(189, 20, 'Huyện Đình Lập'),
(193, 22, 'Thành phố Hạ Long'),
(194, 22, 'Thành phố Móng Cái'),
(195, 22, 'Thành phố Cẩm Phả'),
(196, 22, 'Thành phố Uông Bí'),
(198, 22, 'Huyện Bình Liêu'),
(199, 22, 'Huyện Tiên Yên'),
(200, 22, 'Huyện Đầm Hà'),
(201, 22, 'Huyện Hải Hà'),
(202, 22, 'Huyện Ba Chẽ'),
(203, 22, 'Huyện Vân Đồn'),
(204, 22, 'Huyện Hoành Bồ'),
(205, 22, 'Thị xã Đông Triều'),
(206, 22, 'Thị xã Quảng Yên'),
(207, 22, 'Huyện Cô Tô'),
(213, 24, 'Thành phố Bắc Giang'),
(215, 24, 'Huyện Yên Thế'),
(216, 24, 'Huyện Tân Yên'),
(217, 24, 'Huyện Lạng Giang'),
(218, 24, 'Huyện Lục Nam'),
(219, 24, 'Huyện Lục Ngạn'),
(220, 24, 'Huyện Sơn Động'),
(221, 24, 'Huyện Yên Dũng'),
(222, 24, 'Huyện Việt Yên'),
(223, 24, 'Huyện Hiệp Hòa'),
(227, 25, 'Thành phố Việt Trì'),
(228, 25, 'Thị xã Phú Thọ'),
(230, 25, 'Huyện Đoan Hùng'),
(231, 25, 'Huyện Hạ Hoà'),
(232, 25, 'Huyện Thanh Ba'),
(233, 25, 'Huyện Phù Ninh'),
(234, 25, 'Huyện Yên Lập'),
(235, 25, 'Huyện Cẩm Khê'),
(236, 25, 'Huyện Tam Nông'),
(237, 25, 'Huyện Lâm Thao'),
(238, 25, 'Huyện Thanh Sơn'),
(239, 25, 'Huyện Thanh Thuỷ'),
(240, 25, 'Huyện Tân Sơn'),
(243, 26, 'Thành phố Vĩnh Yên'),
(244, 26, 'Thị xã Phúc Yên'),
(246, 26, 'Huyện Lập Thạch'),
(247, 26, 'Huyện Tam Dương'),
(248, 26, 'Huyện Tam Đảo'),
(249, 26, 'Huyện Bình Xuyên'),
(251, 26, 'Huyện Yên Lạc'),
(252, 26, 'Huyện Vĩnh Tường'),
(253, 26, 'Huyện Sông Lô'),
(256, 27, 'Thành phố Bắc Ninh'),
(258, 27, 'Huyện Yên Phong'),
(259, 27, 'Huyện Quế Võ'),
(260, 27, 'Huyện Tiên Du'),
(261, 27, 'Thị xã Từ Sơn'),
(262, 27, 'Huyện Thuận Thành'),
(263, 27, 'Huyện Gia Bình'),
(264, 27, 'Huyện Lương Tài'),
(288, 30, 'Thành phố Hải Dương'),
(290, 30, 'Thị xã Chí Linh'),
(291, 30, 'Huyện Nam Sách'),
(292, 30, 'Huyện Kinh Môn'),
(293, 30, 'Huyện Kim Thành'),
(294, 30, 'Huyện Thanh Hà'),
(295, 30, 'Huyện Cẩm Giàng'),
(296, 30, 'Huyện Bình Giang'),
(297, 30, 'Huyện Gia Lộc'),
(298, 30, 'Huyện Tứ Kỳ'),
(299, 30, 'Huyện Ninh Giang'),
(300, 30, 'Huyện Thanh Miện'),
(303, 31, 'Quận Hồng Bàng'),
(304, 31, 'Quận Ngô Quyền'),
(305, 31, 'Quận Lê Chân'),
(306, 31, 'Quận Hải An'),
(307, 31, 'Quận Kiến An'),
(308, 31, 'Quận Đồ Sơn'),
(309, 31, 'Quận Dương Kinh'),
(311, 31, 'Huyện Thuỷ Nguyên'),
(312, 31, 'Huyện An Dương'),
(313, 31, 'Huyện An Lão'),
(314, 31, 'Huyện Kiến Thuỵ'),
(315, 31, 'Huyện Tiên Lãng'),
(316, 31, 'Huyện Vĩnh Bảo'),
(317, 31, 'Huyện Cát Hải'),
(318, 31, 'Huyện Bạch Long Vĩ'),
(323, 33, 'Thành phố Hưng Yên'),
(325, 33, 'Huyện Văn Lâm'),
(326, 33, 'Huyện Văn Giang'),
(327, 33, 'Huyện Yên Mỹ'),
(328, 33, 'Huyện Mỹ Hào'),
(329, 33, 'Huyện Ân Thi'),
(330, 33, 'Huyện Khoái Châu'),
(331, 33, 'Huyện Kim Động'),
(332, 33, 'Huyện Tiên Lữ'),
(333, 33, 'Huyện Phù Cừ'),
(336, 34, 'Thành phố Thái Bình'),
(338, 34, 'Huyện Quỳnh Phụ'),
(339, 34, 'Huyện Hưng Hà'),
(340, 34, 'Huyện Đông Hưng'),
(341, 34, 'Huyện Thái Thụy'),
(342, 34, 'Huyện Tiền Hải'),
(343, 34, 'Huyện Kiến Xương'),
(344, 34, 'Huyện Vũ Thư'),
(347, 35, 'Thành phố Phủ Lý'),
(349, 35, 'Huyện Duy Tiên'),
(350, 35, 'Huyện Kim Bảng'),
(351, 35, 'Huyện Thanh Liêm'),
(352, 35, 'Huyện Bình Lục'),
(353, 35, 'Huyện Lý Nhân'),
(356, 36, 'Thành phố Nam Định'),
(358, 36, 'Huyện Mỹ Lộc'),
(359, 36, 'Huyện Vụ Bản'),
(360, 36, 'Huyện Ý Yên'),
(361, 36, 'Huyện Nghĩa Hưng'),
(362, 36, 'Huyện Nam Trực'),
(363, 36, 'Huyện Trực Ninh'),
(364, 36, 'Huyện Xuân Trường'),
(365, 36, 'Huyện Giao Thủy'),
(366, 36, 'Huyện Hải Hậu'),
(369, 37, 'Thành phố Ninh Bình'),
(370, 37, 'Thành phố Tam Điệp'),
(372, 37, 'Huyện Nho Quan'),
(373, 37, 'Huyện Gia Viễn'),
(374, 37, 'Huyện Hoa Lư'),
(375, 37, 'Huyện Yên Khánh'),
(376, 37, 'Huyện Kim Sơn'),
(377, 37, 'Huyện Yên Mô'),
(380, 38, 'Thành phố Thanh Hóa'),
(381, 38, 'Thị xã Bỉm Sơn'),
(382, 38, 'Thị xã Sầm Sơn'),
(384, 38, 'Huyện Mường Lát'),
(385, 38, 'Huyện Quan Hóa'),
(386, 38, 'Huyện Bá Thước'),
(387, 38, 'Huyện Quan Sơn'),
(388, 38, 'Huyện Lang Chánh'),
(389, 38, 'Huyện Ngọc Lặc'),
(390, 38, 'Huyện Cẩm Thủy'),
(391, 38, 'Huyện Thạch Thành'),
(392, 38, 'Huyện Hà Trung'),
(393, 38, 'Huyện Vĩnh Lộc'),
(394, 38, 'Huyện Yên Định'),
(395, 38, 'Huyện Thọ Xuân'),
(396, 38, 'Huyện Thường Xuân'),
(397, 38, 'Huyện Triệu Sơn'),
(398, 38, 'Huyện Thiệu Hóa'),
(399, 38, 'Huyện Hoằng Hóa'),
(400, 38, 'Huyện Hậu Lộc'),
(401, 38, 'Huyện Nga Sơn'),
(402, 38, 'Huyện Như Xuân'),
(403, 38, 'Huyện Như Thanh'),
(404, 38, 'Huyện Nông Cống'),
(405, 38, 'Huyện Đông Sơn'),
(406, 38, 'Huyện Quảng Xương'),
(407, 38, 'Huyện Tĩnh Gia'),
(412, 40, 'Thành phố Vinh'),
(413, 40, 'Thị xã Cửa Lò'),
(414, 40, 'Thị xã Thái Hoà'),
(415, 40, 'Huyện Quế Phong'),
(416, 40, 'Huyện Quỳ Châu'),
(417, 40, 'Huyện Kỳ Sơn'),
(418, 40, 'Huyện Tương Dương'),
(419, 40, 'Huyện Nghĩa Đàn'),
(420, 40, 'Huyện Quỳ Hợp'),
(421, 40, 'Huyện Quỳnh Lưu'),
(422, 40, 'Huyện Con Cuông'),
(423, 40, 'Huyện Tân Kỳ'),
(424, 40, 'Huyện Anh Sơn'),
(425, 40, 'Huyện Diễn Châu'),
(426, 40, 'Huyện Yên Thành'),
(427, 40, 'Huyện Đô Lương'),
(428, 40, 'Huyện Thanh Chương'),
(429, 40, 'Huyện Nghi Lộc'),
(430, 40, 'Huyện Nam Đàn'),
(431, 40, 'Huyện Hưng Nguyên'),
(432, 40, 'Thị xã Hoàng Mai'),
(436, 42, 'Thành phố Hà Tĩnh'),
(437, 42, 'Thị xã Hồng Lĩnh'),
(439, 42, 'Huyện Hương Sơn'),
(440, 42, 'Huyện Đức Thọ'),
(441, 42, 'Huyện Vũ Quang'),
(442, 42, 'Huyện Nghi Xuân'),
(443, 42, 'Huyện Can Lộc'),
(444, 42, 'Huyện Hương Khê'),
(445, 42, 'Huyện Thạch Hà'),
(446, 42, 'Huyện Cẩm Xuyên'),
(447, 42, 'Huyện Kỳ Anh'),
(448, 42, 'Huyện Lộc Hà'),
(449, 42, 'Thị xã Kỳ Anh'),
(450, 44, 'Thành Phố Đồng Hới'),
(452, 44, 'Huyện Minh Hóa'),
(453, 44, 'Huyện Tuyên Hóa'),
(454, 44, 'Huyện Quảng Trạch'),
(455, 44, 'Huyện Bố Trạch'),
(456, 44, 'Huyện Quảng Ninh'),
(457, 44, 'Huyện Lệ Thủy'),
(458, 44, 'Thị xã Ba Đồn'),
(461, 45, 'Thành phố Đông Hà'),
(462, 45, 'Thị xã Quảng Trị'),
(464, 45, 'Huyện Vĩnh Linh'),
(465, 45, 'Huyện Hướng Hóa'),
(466, 45, 'Huyện Gio Linh'),
(467, 45, 'Huyện Đa Krông'),
(468, 45, 'Huyện Cam Lộ'),
(469, 45, 'Huyện Triệu Phong'),
(470, 45, 'Huyện Hải Lăng'),
(471, 45, 'Huyện Cồn Cỏ'),
(474, 46, 'Thành phố Huế'),
(476, 46, 'Huyện Phong Điền'),
(477, 46, 'Huyện Quảng Điền'),
(478, 46, 'Huyện Phú Vang'),
(479, 46, 'Thị xã Hương Thủy'),
(480, 46, 'Thị xã Hương Trà'),
(481, 46, 'Huyện A Lưới'),
(482, 46, 'Huyện Phú Lộc'),
(483, 46, 'Huyện Nam Đông'),
(490, 48, 'Quận Liên Chiểu'),
(491, 48, 'Quận Thanh Khê'),
(492, 48, 'Quận Hải Châu'),
(493, 48, 'Quận Sơn Trà'),
(494, 48, 'Quận Ngũ Hành Sơn'),
(495, 48, 'Quận Cẩm Lệ'),
(497, 48, 'Huyện Hòa Vang'),
(498, 48, 'Huyện Hoàng Sa'),
(502, 49, 'Thành phố Tam Kỳ'),
(503, 49, 'Thành phố Hội An'),
(504, 49, 'Huyện Tây Giang'),
(505, 49, 'Huyện Đông Giang'),
(506, 49, 'Huyện Đại Lộc'),
(507, 49, 'Thị xã Điện Bàn'),
(508, 49, 'Huyện Duy Xuyên'),
(509, 49, 'Huyện Quế Sơn'),
(510, 49, 'Huyện Nam Giang'),
(511, 49, 'Huyện Phước Sơn'),
(512, 49, 'Huyện Hiệp Đức'),
(513, 49, 'Huyện Thăng Bình'),
(514, 49, 'Huyện Tiên Phước'),
(515, 49, 'Huyện Bắc Trà My'),
(516, 49, 'Huyện Nam Trà My'),
(517, 49, 'Huyện Núi Thành'),
(518, 49, 'Huyện Phú Ninh'),
(519, 49, 'Huyện Nông Sơn'),
(522, 51, 'Thành phố Quảng Ngãi'),
(524, 51, 'Huyện Bình Sơn'),
(525, 51, 'Huyện Trà Bồng'),
(526, 51, 'Huyện Tây Trà'),
(527, 51, 'Huyện Sơn Tịnh'),
(528, 51, 'Huyện Tư Nghĩa'),
(529, 51, 'Huyện Sơn Hà'),
(530, 51, 'Huyện Sơn Tây'),
(531, 51, 'Huyện Minh Long'),
(532, 51, 'Huyện Nghĩa Hành'),
(533, 51, 'Huyện Mộ Đức'),
(534, 51, 'Huyện Đức Phổ'),
(535, 51, 'Huyện Ba Tơ'),
(536, 51, 'Huyện Lý Sơn'),
(540, 52, 'Thành phố Qui Nhơn'),
(542, 52, 'Huyện An Lão'),
(543, 52, 'Huyện Hoài Nhơn'),
(544, 52, 'Huyện Hoài Ân'),
(545, 52, 'Huyện Phù Mỹ'),
(546, 52, 'Huyện Vĩnh Thạnh'),
(547, 52, 'Huyện Tây Sơn'),
(548, 52, 'Huyện Phù Cát'),
(549, 52, 'Thị xã An Nhơn'),
(550, 52, 'Huyện Tuy Phước'),
(551, 52, 'Huyện Vân Canh'),
(555, 54, 'Thành phố Tuy Hoà'),
(557, 54, 'Thị xã Sông Cầu'),
(558, 54, 'Huyện Đồng Xuân'),
(559, 54, 'Huyện Tuy An'),
(560, 54, 'Huyện Sơn Hòa'),
(561, 54, 'Huyện Sông Hinh'),
(562, 54, 'Huyện Tây Hoà'),
(563, 54, 'Huyện Phú Hoà'),
(564, 54, 'Huyện Đông Hòa'),
(568, 56, 'Thành phố Nha Trang'),
(569, 56, 'Thành phố Cam Ranh'),
(570, 56, 'Huyện Cam Lâm'),
(571, 56, 'Huyện Vạn Ninh'),
(572, 56, 'Thị xã Ninh Hòa'),
(573, 56, 'Huyện Khánh Vĩnh'),
(574, 56, 'Huyện Diên Khánh'),
(575, 56, 'Huyện Khánh Sơn'),
(576, 56, 'Huyện Trường Sa'),
(582, 58, 'Thành phố Phan Rang-Tháp Chàm'),
(584, 58, 'Huyện Bác Ái'),
(585, 58, 'Huyện Ninh Sơn'),
(586, 58, 'Huyện Ninh Hải'),
(587, 58, 'Huyện Ninh Phước'),
(588, 58, 'Huyện Thuận Bắc'),
(589, 58, 'Huyện Thuận Nam'),
(593, 60, 'Thành phố Phan Thiết'),
(594, 60, 'Thị xã La Gi'),
(595, 60, 'Huyện Tuy Phong'),
(596, 60, 'Huyện Bắc Bình'),
(597, 60, 'Huyện Hàm Thuận Bắc'),
(598, 60, 'Huyện Hàm Thuận Nam'),
(599, 60, 'Huyện Tánh Linh'),
(600, 60, 'Huyện Đức Linh'),
(601, 60, 'Huyện Hàm Tân'),
(602, 60, 'Huyện Phú Quí'),
(608, 62, 'Thành phố Kon Tum'),
(610, 62, 'Huyện Đắk Glei'),
(611, 62, 'Huyện Ngọc Hồi'),
(612, 62, 'Huyện Đắk Tô'),
(613, 62, 'Huyện Kon Plông'),
(614, 62, 'Huyện Kon Rẫy'),
(615, 62, 'Huyện Đắk Hà'),
(616, 62, 'Huyện Sa Thầy'),
(617, 62, 'Huyện Tu Mơ Rông'),
(618, 62, 'Huyện Ia H\' Drai'),
(622, 64, 'Thành phố Pleiku'),
(623, 64, 'Thị xã An Khê'),
(624, 64, 'Thị xã Ayun Pa'),
(625, 64, 'Huyện KBang'),
(626, 64, 'Huyện Đăk Đoa'),
(627, 64, 'Huyện Chư Păh'),
(628, 64, 'Huyện Ia Grai'),
(629, 64, 'Huyện Mang Yang'),
(630, 64, 'Huyện Kông Chro'),
(631, 64, 'Huyện Đức Cơ'),
(632, 64, 'Huyện Chư Prông'),
(633, 64, 'Huyện Chư Sê'),
(634, 64, 'Huyện Đăk Pơ'),
(635, 64, 'Huyện Ia Pa'),
(637, 64, 'Huyện Krông Pa'),
(638, 64, 'Huyện Phú Thiện'),
(639, 64, 'Huyện Chư Pưh'),
(643, 66, 'Thành phố Buôn Ma Thuột'),
(644, 66, 'Thị Xã Buôn Hồ'),
(645, 66, 'Huyện Ea H\'leo'),
(646, 66, 'Huyện Ea Súp'),
(647, 66, 'Huyện Buôn Đôn'),
(648, 66, 'Huyện Cư M\'gar'),
(649, 66, 'Huyện Krông Búk'),
(650, 66, 'Huyện Krông Năng'),
(651, 66, 'Huyện Ea Kar'),
(652, 66, 'Huyện M\'Đrắk'),
(653, 66, 'Huyện Krông Bông'),
(654, 66, 'Huyện Krông Pắc'),
(655, 66, 'Huyện Krông A Na'),
(656, 66, 'Huyện Lắk'),
(657, 66, 'Huyện Cư Kuin'),
(660, 67, 'Thị xã Gia Nghĩa'),
(661, 67, 'Huyện Đăk Glong'),
(662, 67, 'Huyện Cư Jút'),
(663, 67, 'Huyện Đắk Mil'),
(664, 67, 'Huyện Krông Nô'),
(665, 67, 'Huyện Đắk Song'),
(666, 67, 'Huyện Đắk R\'Lấp'),
(667, 67, 'Huyện Tuy Đức'),
(672, 68, 'Thành phố Đà Lạt'),
(673, 68, 'Thành phố Bảo Lộc'),
(674, 68, 'Huyện Đam Rông'),
(675, 68, 'Huyện Lạc Dương'),
(676, 68, 'Huyện Lâm Hà'),
(677, 68, 'Huyện Đơn Dương'),
(678, 68, 'Huyện Đức Trọng'),
(679, 68, 'Huyện Di Linh'),
(680, 68, 'Huyện Bảo Lâm'),
(681, 68, 'Huyện Đạ Huoai'),
(682, 68, 'Huyện Đạ Tẻh'),
(683, 68, 'Huyện Cát Tiên'),
(688, 70, 'Thị xã Phước Long'),
(689, 70, 'Thị xã Đồng Xoài'),
(690, 70, 'Thị xã Bình Long'),
(691, 70, 'Huyện Bù Gia Mập'),
(692, 70, 'Huyện Lộc Ninh'),
(693, 70, 'Huyện Bù Đốp'),
(694, 70, 'Huyện Hớn Quản'),
(695, 70, 'Huyện Đồng Phú'),
(696, 70, 'Huyện Bù Đăng'),
(697, 70, 'Huyện Chơn Thành'),
(698, 70, 'Huyện Phú Riềng'),
(703, 72, 'Thành phố Tây Ninh'),
(705, 72, 'Huyện Tân Biên'),
(706, 72, 'Huyện Tân Châu'),
(707, 72, 'Huyện Dương Minh Châu'),
(708, 72, 'Huyện Châu Thành'),
(709, 72, 'Huyện Hòa Thành'),
(710, 72, 'Huyện Gò Dầu'),
(711, 72, 'Huyện Bến Cầu'),
(712, 72, 'Huyện Trảng Bàng'),
(718, 74, 'Thành phố Thủ Dầu Một'),
(719, 74, 'Huyện Bàu Bàng'),
(720, 74, 'Huyện Dầu Tiếng'),
(721, 74, 'Thị xã Bến Cát'),
(722, 74, 'Huyện Phú Giáo'),
(723, 74, 'Thị xã Tân Uyên'),
(724, 74, 'Thị xã Dĩ An'),
(725, 74, 'Thị xã Thuận An'),
(726, 74, 'Huyện Bắc Tân Uyên'),
(731, 75, 'Thành phố Biên Hòa'),
(732, 75, 'Thị xã Long Khánh'),
(734, 75, 'Huyện Tân Phú'),
(735, 75, 'Huyện Vĩnh Cửu'),
(736, 75, 'Huyện Định Quán'),
(737, 75, 'Huyện Trảng Bom'),
(738, 75, 'Huyện Thống Nhất'),
(739, 75, 'Huyện Cẩm Mỹ'),
(740, 75, 'Huyện Long Thành'),
(741, 75, 'Huyện Xuân Lộc'),
(742, 75, 'Huyện Nhơn Trạch'),
(747, 77, 'Thành phố Vũng Tàu'),
(748, 77, 'Thành phố Bà Rịa'),
(750, 77, 'Huyện Châu Đức'),
(751, 77, 'Huyện Xuyên Mộc'),
(752, 77, 'Huyện Long Điền'),
(753, 77, 'Huyện Đất Đỏ'),
(754, 77, 'Huyện Tân Thành'),
(755, 77, 'Huyện Côn Đảo'),
(760, 79, 'Quận 1'),
(761, 79, 'Quận 12'),
(762, 79, 'Quận Thủ Đức'),
(763, 79, 'Quận 9'),
(764, 79, 'Quận Gò Vấp'),
(765, 79, 'Quận Bình Thạnh'),
(766, 79, 'Quận Tân Bình'),
(767, 79, 'Quận Tân Phú'),
(768, 79, 'Quận Phú Nhuận'),
(769, 79, 'Quận 2'),
(770, 79, 'Quận 3'),
(771, 79, 'Quận 10'),
(772, 79, 'Quận 11'),
(773, 79, 'Quận 4'),
(774, 79, 'Quận 5'),
(775, 79, 'Quận 6'),
(776, 79, 'Quận 8'),
(777, 79, 'Quận Bình Tân'),
(778, 79, 'Quận 7'),
(783, 79, 'Huyện Củ Chi'),
(784, 79, 'Huyện Hóc Môn'),
(785, 79, 'Huyện Bình Chánh'),
(786, 79, 'Huyện Nhà Bè'),
(787, 79, 'Huyện Cần Giờ'),
(794, 80, 'Thành phố Tân An'),
(795, 80, 'Thị xã Kiến Tường'),
(796, 80, 'Huyện Tân Hưng'),
(797, 80, 'Huyện Vĩnh Hưng'),
(798, 80, 'Huyện Mộc Hóa'),
(799, 80, 'Huyện Tân Thạnh'),
(800, 80, 'Huyện Thạnh Hóa'),
(801, 80, 'Huyện Đức Huệ'),
(802, 80, 'Huyện Đức Hòa'),
(803, 80, 'Huyện Bến Lức'),
(804, 80, 'Huyện Thủ Thừa'),
(805, 80, 'Huyện Tân Trụ'),
(806, 80, 'Huyện Cần Đước'),
(807, 80, 'Huyện Cần Giuộc'),
(808, 80, 'Huyện Châu Thành'),
(815, 82, 'Thành phố Mỹ Tho'),
(816, 82, 'Thị xã Gò Công'),
(817, 82, 'Thị xã Cai Lậy'),
(818, 82, 'Huyện Tân Phước'),
(819, 82, 'Huyện Cái Bè'),
(820, 82, 'Huyện Cai Lậy'),
(821, 82, 'Huyện Châu Thành'),
(822, 82, 'Huyện Chợ Gạo'),
(823, 82, 'Huyện Gò Công Tây'),
(824, 82, 'Huyện Gò Công Đông'),
(825, 82, 'Huyện Tân Phú Đông'),
(829, 83, 'Thành phố Bến Tre'),
(831, 83, 'Huyện Châu Thành'),
(832, 83, 'Huyện Chợ Lách'),
(833, 83, 'Huyện Mỏ Cày Nam'),
(834, 83, 'Huyện Giồng Trôm'),
(835, 83, 'Huyện Bình Đại'),
(836, 83, 'Huyện Ba Tri'),
(837, 83, 'Huyện Thạnh Phú'),
(838, 83, 'Huyện Mỏ Cày Bắc'),
(842, 84, 'Thành phố Trà Vinh'),
(844, 84, 'Huyện Càng Long'),
(845, 84, 'Huyện Cầu Kè'),
(846, 84, 'Huyện Tiểu Cần'),
(847, 84, 'Huyện Châu Thành'),
(848, 84, 'Huyện Cầu Ngang'),
(849, 84, 'Huyện Trà Cú'),
(850, 84, 'Huyện Duyên Hải'),
(851, 84, 'Thị xã Duyên Hải'),
(855, 86, 'Thành phố Vĩnh Long'),
(857, 86, 'Huyện Long Hồ'),
(858, 86, 'Huyện Mang Thít'),
(859, 86, 'Huyện  Vũng Liêm'),
(860, 86, 'Huyện Tam Bình'),
(861, 86, 'Thị xã Bình Minh'),
(862, 86, 'Huyện Trà Ôn'),
(863, 86, 'Huyện Bình Tân'),
(866, 87, 'Thành phố Cao Lãnh'),
(867, 87, 'Thành phố Sa Đéc'),
(868, 87, 'Thị xã Hồng Ngự'),
(869, 87, 'Huyện Tân Hồng'),
(870, 87, 'Huyện Hồng Ngự'),
(871, 87, 'Huyện Tam Nông'),
(872, 87, 'Huyện Tháp Mười'),
(873, 87, 'Huyện Cao Lãnh'),
(874, 87, 'Huyện Thanh Bình'),
(875, 87, 'Huyện Lấp Vò'),
(876, 87, 'Huyện Lai Vung'),
(877, 87, 'Huyện Châu Thành'),
(883, 89, 'Thành phố Long Xuyên'),
(884, 89, 'Thành phố Châu Đốc'),
(886, 89, 'Huyện An Phú'),
(887, 89, 'Thị xã Tân Châu'),
(888, 89, 'Huyện Phú Tân'),
(889, 89, 'Huyện Châu Phú'),
(890, 89, 'Huyện Tịnh Biên'),
(891, 89, 'Huyện Tri Tôn'),
(892, 89, 'Huyện Châu Thành'),
(893, 89, 'Huyện Chợ Mới'),
(894, 89, 'Huyện Thoại Sơn'),
(899, 91, 'Thành phố Rạch Giá'),
(900, 91, 'Thị xã Hà Tiên'),
(902, 91, 'Huyện Kiên Lương'),
(903, 91, 'Huyện Hòn Đất'),
(904, 91, 'Huyện Tân Hiệp'),
(905, 91, 'Huyện Châu Thành'),
(906, 91, 'Huyện Giồng Riềng'),
(907, 91, 'Huyện Gò Quao'),
(908, 91, 'Huyện An Biên'),
(909, 91, 'Huyện An Minh'),
(910, 91, 'Huyện Vĩnh Thuận'),
(911, 91, 'Huyện Phú Quốc'),
(912, 91, 'Huyện Kiên Hải'),
(913, 91, 'Huyện U Minh Thượng'),
(914, 91, 'Huyện Giang Thành'),
(916, 92, 'Quận Ninh Kiều'),
(917, 92, 'Quận Ô Môn'),
(918, 92, 'Quận Bình Thuỷ'),
(919, 92, 'Quận Cái Răng'),
(923, 92, 'Quận Thốt Nốt'),
(924, 92, 'Huyện Vĩnh Thạnh'),
(925, 92, 'Huyện Cờ Đỏ'),
(926, 92, 'Huyện Phong Điền'),
(927, 92, 'Huyện Thới Lai'),
(930, 93, 'Thành phố Vị Thanh'),
(931, 93, 'Thị xã Ngã Bảy'),
(932, 93, 'Huyện Châu Thành A'),
(933, 93, 'Huyện Châu Thành'),
(934, 93, 'Huyện Phụng Hiệp'),
(935, 93, 'Huyện Vị Thuỷ'),
(936, 93, 'Huyện Long Mỹ'),
(937, 93, 'Thị xã Long Mỹ'),
(941, 94, 'Thành phố Sóc Trăng'),
(942, 94, 'Huyện Châu Thành'),
(943, 94, 'Huyện Kế Sách'),
(944, 94, 'Huyện Mỹ Tú'),
(945, 94, 'Huyện Cù Lao Dung'),
(946, 94, 'Huyện Long Phú'),
(947, 94, 'Huyện Mỹ Xuyên'),
(948, 94, 'Thị xã Ngã Năm'),
(949, 94, 'Huyện Thạnh Trị'),
(950, 94, 'Thị xã Vĩnh Châu'),
(951, 94, 'Huyện Trần Đề'),
(954, 95, 'Thành phố Bạc Liêu'),
(956, 95, 'Huyện Hồng Dân'),
(957, 95, 'Huyện Phước Long'),
(958, 95, 'Huyện Vĩnh Lợi'),
(959, 95, 'Thị xã Giá Rai'),
(960, 95, 'Huyện Đông Hải'),
(961, 95, 'Huyện Hoà Bình'),
(964, 96, 'Thành phố Cà Mau'),
(966, 96, 'Huyện U Minh'),
(967, 96, 'Huyện Thới Bình'),
(968, 96, 'Huyện Trần Văn Thời'),
(969, 96, 'Huyện Cái Nước'),
(970, 96, 'Huyện Đầm Dơi'),
(971, 96, 'Huyện Năm Căn'),
(972, 96, 'Huyện Phú Tân'),
(973, 96, 'Huyện Ngọc Hiển');

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
  `payment_method` varchar(50) DEFAULT NULL,
  `shipping_method` varchar(50) DEFAULT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `province_id` int(11) NOT NULL,
  `district_id` int(11) NOT NULL,
  `street` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `coupon_code` varchar(50) DEFAULT NULL,
  `coupon_discount` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total`, `status`, `payment_method`, `shipping_method`, `fullname`, `phone`, `province_id`, `district_id`, `street`, `address`, `created_at`, `coupon_code`, `coupon_discount`) VALUES
(1, 1, 29990000.00, 'pending', 'cod', 'standard', 'Nam', '0932660941', 51, 522, '123 Lê Lợi', NULL, '2025-12-13 05:09:07', NULL, 0),
(2, 1, 61980000.00, 'pending', 'cod', 'standard', 'Nam', '0932660941', 51, 522, '123 Lê Lợi', NULL, '2025-12-13 06:05:29', NULL, 0);

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

--
-- Đang đổ dữ liệu cho bảng `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `sku_id`, `quantity`, `price`) VALUES
(1, 1, 15, 1, 29990000.00),
(2, 2, 4, 1, 19990000.00),
(3, 2, 148, 1, 41990000.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_item_discounts`
--

CREATE TABLE `order_item_discounts` (
  `id` int(11) NOT NULL,
  `order_item_id` int(11) DEFAULT NULL,
  `discount_amount` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_item_returns`
--

CREATE TABLE `order_item_returns` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `order_item_id` int(11) NOT NULL,
  `sku_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `reason` text DEFAULT NULL,
  `status` enum('pending','approved','rejected','refunded') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `order_item_returns`
--

INSERT INTO `order_item_returns` (`id`, `order_id`, `order_item_id`, `sku_id`, `quantity`, `reason`, `status`, `created_at`) VALUES
(1, 2, 2, 4, 1, 'ko mua', 'pending', '2025-12-13 06:05:49');

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

--
-- Đang đổ dữ liệu cho bảng `order_tracking`
--

INSERT INTO `order_tracking` (`id`, `order_id`, `status`, `note`, `updated_at`) VALUES
(1, 1, 'pending', 'Đơn hàng đã được tạo', '2025-12-13 05:09:07'),
(2, 2, 'pending', 'Đơn hàng đã được tạo', '2025-12-13 06:05:29');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `provinces`
--

CREATE TABLE `provinces` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `provinces`
--

INSERT INTO `provinces` (`id`, `name`) VALUES
(1, 'Thành phố Hà Nội'),
(2, 'Tỉnh Hà Giang'),
(4, 'Tỉnh Cao Bằng'),
(6, 'Tỉnh Bắc Kạn'),
(8, 'Tỉnh Tuyên Quang'),
(10, 'Tỉnh Lào Cai'),
(11, 'Tỉnh Điện Biên'),
(12, 'Tỉnh Lai Châu'),
(14, 'Tỉnh Sơn La'),
(15, 'Tỉnh Yên Bái'),
(17, 'Tỉnh Hoà Bình'),
(19, 'Tỉnh Thái Nguyên'),
(20, 'Tỉnh Lạng Sơn'),
(22, 'Tỉnh Quảng Ninh'),
(24, 'Tỉnh Bắc Giang'),
(25, 'Tỉnh Phú Thọ'),
(26, 'Tỉnh Vĩnh Phúc'),
(27, 'Tỉnh Bắc Ninh'),
(30, 'Tỉnh Hải Dương'),
(31, 'Thành phố Hải Phòng'),
(33, 'Tỉnh Hưng Yên'),
(34, 'Tỉnh Thái Bình'),
(35, 'Tỉnh Hà Nam'),
(36, 'Tỉnh Nam Định'),
(37, 'Tỉnh Ninh Bình'),
(38, 'Tỉnh Thanh Hóa'),
(40, 'Tỉnh Nghệ An'),
(42, 'Tỉnh Hà Tĩnh'),
(44, 'Tỉnh Quảng Bình'),
(45, 'Tỉnh Quảng Trị'),
(46, 'Tỉnh Thừa Thiên Huế'),
(48, 'Thành phố Đà Nẵng'),
(49, 'Tỉnh Quảng Nam'),
(51, 'Tỉnh Quảng Ngãi'),
(52, 'Tỉnh Bình Định'),
(54, 'Tỉnh Phú Yên'),
(56, 'Tỉnh Khánh Hòa'),
(58, 'Tỉnh Ninh Thuận'),
(60, 'Tỉnh Bình Thuận'),
(62, 'Tỉnh Kon Tum'),
(64, 'Tỉnh Gia Lai'),
(66, 'Tỉnh Đắk Lắk'),
(67, 'Tỉnh Đắk Nông'),
(68, 'Tỉnh Lâm Đồng'),
(70, 'Tỉnh Bình Phước'),
(72, 'Tỉnh Tây Ninh'),
(74, 'Tỉnh Bình Dương'),
(75, 'Tỉnh Đồng Nai'),
(77, 'Tỉnh Bà Rịa - Vũng Tàu'),
(79, 'Thành phố Hồ Chí Minh'),
(80, 'Tỉnh Long An'),
(82, 'Tỉnh Tiền Giang'),
(83, 'Tỉnh Bến Tre'),
(84, 'Tỉnh Trà Vinh'),
(86, 'Tỉnh Vĩnh Long'),
(87, 'Tỉnh Đồng Tháp'),
(89, 'Tỉnh An Giang'),
(91, 'Tỉnh Kiên Giang'),
(92, 'Thành phố Cần Thơ'),
(93, 'Tỉnh Hậu Giang'),
(94, 'Tỉnh Sóc Trăng'),
(95, 'Tỉnh Bạc Liêu'),
(96, 'Tỉnh Cà Mau');

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
(1, 1, 'IP14-128-BLACK', '{\"storage\":\"128GB\",\"color\":\"Black\"}', 19990000.00, 18990000.00, 30, 'KHO1', '2025-12-11 04:58:17'),
(2, 1, 'IP14-128-WHITE', '{\"storage\":\"128GB\",\"color\":\"White\"}', 19990000.00, 18990000.00, 25, 'KHO1', '2025-12-11 04:58:17'),
(3, 1, 'IP14-128-BLUE', '{\"storage\":\"128GB\",\"color\":\"Blue\"}', 19990000.00, 18990000.00, 20, 'KHO1', '2025-12-11 04:58:17'),
(4, 1, 'IP14-128-TITAN', '{\"storage\":\"128GB\",\"color\":\"Titan\"}', 19990000.00, 18990000.00, 19, 'KHO1', '2025-12-11 04:58:17'),
(5, 1, 'IP14-256-BLACK', '{\"storage\":\"256GB\",\"color\":\"Black\"}', 21990000.00, 20990000.00, 30, 'KHO1', '2025-12-11 04:58:17'),
(6, 1, 'IP14-256-WHITE', '{\"storage\":\"256GB\",\"color\":\"White\"}', 21990000.00, 20990000.00, 20, 'KHO1', '2025-12-11 04:58:17'),
(7, 1, 'IP14-256-BLUE', '{\"storage\":\"256GB\",\"color\":\"Blue\"}', 21990000.00, 20990000.00, 18, 'KHO1', '2025-12-11 04:58:17'),
(8, 1, 'IP14-256-TITAN', '{\"storage\":\"256GB\",\"color\":\"Titan\"}', 21990000.00, 20990000.00, 15, 'KHO1', '2025-12-11 04:58:17'),
(9, 1, 'IP14-512-BLACK', '{\"storage\":\"512GB\",\"color\":\"Black\"}', 25990000.00, 24990000.00, 15, 'KHO1', '2025-12-11 04:58:17'),
(10, 1, 'IP14-512-WHITE', '{\"storage\":\"512GB\",\"color\":\"White\"}', 25990000.00, 24990000.00, 10, 'KHO1', '2025-12-11 04:58:17'),
(11, 1, 'IP14-512-BLUE', '{\"storage\":\"512GB\",\"color\":\"Blue\"}', 25990000.00, 24990000.00, 12, 'KHO1', '2025-12-11 04:58:17'),
(12, 1, 'IP14-512-TITAN', '{\"storage\":\"512GB\",\"color\":\"Titan\"}', 25990000.00, 24990000.00, 8, 'KHO1', '2025-12-11 04:58:17'),
(13, 1, 'IP14-1TB-BLACK', '{\"storage\":\"1TB\",\"color\":\"Black\"}', 29990000.00, 28990000.00, 10, 'KHO1', '2025-12-11 04:58:17'),
(14, 1, 'IP14-1TB-WHITE', '{\"storage\":\"1TB\",\"color\":\"White\"}', 29990000.00, 28990000.00, 10, 'KHO1', '2025-12-11 04:58:17'),
(15, 1, 'IP14-1TB-BLUE', '{\"storage\":\"1TB\",\"color\":\"Blue\"}', 29990000.00, 28990000.00, 9, 'KHO1', '2025-12-11 04:58:17'),
(16, 1, 'IP14-1TB-TITAN', '{\"storage\":\"1TB\",\"color\":\"Titan\"}', 29990000.00, 28990000.00, 10, 'KHO1', '2025-12-11 04:58:17'),
(17, 2, 'IP15-128-BLACK', '{\"storage\":\"128GB\",\"color\":\"Black\"}', 22990000.00, 21990000.00, 30, 'KHO1', '2025-12-11 05:02:55'),
(18, 2, 'IP15-128-WHITE', '{\"storage\":\"128GB\",\"color\":\"White\"}', 22990000.00, 21990000.00, 25, 'KHO1', '2025-12-11 05:02:55'),
(19, 2, 'IP15-128-BLUE', '{\"storage\":\"128GB\",\"color\":\"Blue\"}', 22990000.00, 21990000.00, 20, 'KHO1', '2025-12-11 05:02:55'),
(20, 2, 'IP15-128-TITAN', '{\"storage\":\"128GB\",\"color\":\"Titan\"}', 22990000.00, 21990000.00, 20, 'KHO1', '2025-12-11 05:02:55'),
(21, 2, 'IP15-256-BLACK', '{\"storage\":\"256GB\",\"color\":\"Black\"}', 24990000.00, 23990000.00, 30, 'KHO1', '2025-12-11 05:03:46'),
(22, 2, 'IP15-256-WHITE', '{\"storage\":\"256GB\",\"color\":\"White\"}', 24990000.00, 23990000.00, 20, 'KHO1', '2025-12-11 05:03:46'),
(23, 2, 'IP15-256-BLUE', '{\"storage\":\"256GB\",\"color\":\"Blue\"}', 24990000.00, 23990000.00, 18, 'KHO1', '2025-12-11 05:03:46'),
(24, 2, 'IP15-256-TITAN', '{\"storage\":\"256GB\",\"color\":\"Titan\"}', 24990000.00, 23990000.00, 15, 'KHO1', '2025-12-11 05:03:46'),
(25, 2, 'IP15-512-BLACK', '{\"storage\":\"512GB\",\"color\":\"Black\"}', 28990000.00, 27990000.00, 15, 'KHO1', '2025-12-11 05:03:46'),
(26, 2, 'IP15-512-WHITE', '{\"storage\":\"512GB\",\"color\":\"White\"}', 28990000.00, 27990000.00, 10, 'KHO1', '2025-12-11 05:03:46'),
(27, 2, 'IP15-512-BLUE', '{\"storage\":\"512GB\",\"color\":\"Blue\"}', 28990000.00, 27990000.00, 12, 'KHO1', '2025-12-11 05:03:46'),
(28, 2, 'IP15-512-TITAN', '{\"storage\":\"512GB\",\"color\":\"Titan\"}', 28990000.00, 27990000.00, 8, 'KHO1', '2025-12-11 05:03:46'),
(29, 2, 'IP15-1TB-BLACK', '{\"storage\":\"1TB\",\"color\":\"Black\"}', 32990000.00, 31990000.00, 10, 'KHO1', '2025-12-11 05:03:46'),
(30, 2, 'IP15-1TB-WHITE', '{\"storage\":\"1TB\",\"color\":\"White\"}', 32990000.00, 31990000.00, 10, 'KHO1', '2025-12-11 05:03:46'),
(31, 2, 'IP15-1TB-BLUE', '{\"storage\":\"1TB\",\"color\":\"Blue\"}', 32990000.00, 31990000.00, 10, 'KHO1', '2025-12-11 05:03:46'),
(32, 2, 'IP15-1TB-TITAN', '{\"storage\":\"1TB\",\"color\":\"Titan\"}', 32990000.00, 31990000.00, 10, 'KHO1', '2025-12-11 05:03:46'),
(33, 3, 'IP16-128-BLACK', '{\"storage\":\"128GB\",\"color\":\"Black\"}', 21990000.00, 20990000.00, 30, 'KHO1', '2025-12-11 07:07:14'),
(34, 3, 'IP16-128-WHITE', '{\"storage\":\"128GB\",\"color\":\"White\"}', 21990000.00, 20990000.00, 25, 'KHO1', '2025-12-11 07:07:14'),
(35, 3, 'IP16-128-BLUE', '{\"storage\":\"128GB\",\"color\":\"Blue\"}', 21990000.00, 20990000.00, 20, 'KHO1', '2025-12-11 07:07:14'),
(36, 3, 'IP16-128-TITAN', '{\"storage\":\"128GB\",\"color\":\"Titan\"}', 21990000.00, 20990000.00, 20, 'KHO1', '2025-12-11 07:07:14'),
(37, 3, 'IP16-256-BLACK', '{\"storage\":\"256GB\",\"color\":\"Black\"}', 23990000.00, 22990000.00, 30, 'KHO1', '2025-12-11 07:07:14'),
(38, 3, 'IP16-256-WHITE', '{\"storage\":\"256GB\",\"color\":\"White\"}', 23990000.00, 22990000.00, 20, 'KHO1', '2025-12-11 07:07:14'),
(39, 3, 'IP16-256-BLUE', '{\"storage\":\"256GB\",\"color\":\"Blue\"}', 23990000.00, 22990000.00, 18, 'KHO1', '2025-12-11 07:07:14'),
(40, 3, 'IP16-256-TITAN', '{\"storage\":\"256GB\",\"color\":\"Titan\"}', 23990000.00, 22990000.00, 15, 'KHO1', '2025-12-11 07:07:14'),
(41, 3, 'IP16-512-BLACK', '{\"storage\":\"512GB\",\"color\":\"Black\"}', 27990000.00, 26990000.00, 15, 'KHO1', '2025-12-11 07:07:14'),
(42, 3, 'IP16-512-WHITE', '{\"storage\":\"512GB\",\"color\":\"White\"}', 27990000.00, 26990000.00, 10, 'KHO1', '2025-12-11 07:07:14'),
(43, 3, 'IP16-512-BLUE', '{\"storage\":\"512GB\",\"color\":\"Blue\"}', 27990000.00, 26990000.00, 12, 'KHO1', '2025-12-11 07:07:14'),
(44, 3, 'IP16-512-TITAN', '{\"storage\":\"512GB\",\"color\":\"Titan\"}', 27990000.00, 26990000.00, 8, 'KHO1', '2025-12-11 07:07:14'),
(45, 3, 'IP16-1TB-BLACK', '{\"storage\":\"1TB\",\"color\":\"Black\"}', 31990000.00, 30990000.00, 10, 'KHO1', '2025-12-11 07:07:14'),
(46, 3, 'IP16-1TB-WHITE', '{\"storage\":\"1TB\",\"color\":\"White\"}', 31990000.00, 30990000.00, 10, 'KHO1', '2025-12-11 07:07:14'),
(47, 3, 'IP16-1TB-BLUE', '{\"storage\":\"1TB\",\"color\":\"Blue\"}', 31990000.00, 30990000.00, 10, 'KHO1', '2025-12-11 07:07:14'),
(48, 3, 'IP16-1TB-TITAN', '{\"storage\":\"1TB\",\"color\":\"Titan\"}', 31990000.00, 30990000.00, 10, 'KHO1', '2025-12-11 07:07:14'),
(58, 4, 'S25-128-BLACK', '{\"storage\":\"128GB\",\"color\":\"Black\"}', 24990000.00, 23990000.00, 30, 'KHO1', '2025-12-11 07:34:39'),
(59, 4, 'S25-128-WHITE', '{\"storage\":\"128GB\",\"color\":\"White\"}', 24990000.00, 23990000.00, 25, 'KHO1', '2025-12-11 07:34:39'),
(60, 4, 'S25-256-BLACK', '{\"storage\":\"256GB\",\"color\":\"Black\"}', 27990000.00, 26990000.00, 30, 'KHO1', '2025-12-11 07:34:39'),
(61, 4, 'S25-256-WHITE', '{\"storage\":\"256GB\",\"color\":\"White\"}', 27990000.00, 26990000.00, 25, 'KHO1', '2025-12-11 07:34:39'),
(62, 4, 'S25-512-BLACK', '{\"storage\":\"512GB\",\"color\":\"Black\"}', 31990000.00, 30990000.00, 15, 'KHO1', '2025-12-11 07:34:39'),
(63, 4, 'S25-512-WHITE', '{\"storage\":\"512GB\",\"color\":\"White\"}', 31990000.00, 30990000.00, 10, 'KHO1', '2025-12-11 07:34:39'),
(64, 4, 'S25-1TB-BLACK', '{\"storage\":\"1TB\",\"color\":\"Black\"}', 35990000.00, 34990000.00, 10, 'KHO1', '2025-12-11 07:34:39'),
(65, 4, 'S25-1TB-WHITE', '{\"storage\":\"1TB\",\"color\":\"White\"}', 35990000.00, 34990000.00, 10, 'KHO1', '2025-12-11 07:34:39'),
(66, 5, 'S25U-256-BLACK', '{\"storage\":\"256GB\",\"color\":\"Black\"}', 27990000.00, 26990000.00, 30, 'KHO1', '2025-12-11 07:35:03'),
(67, 5, 'S25U-256-WHITE', '{\"storage\":\"256GB\",\"color\":\"White\"}', 27990000.00, 26990000.00, 25, 'KHO1', '2025-12-11 07:35:03'),
(69, 5, 'S25U-512-BLACK', '{\"storage\":\"512GB\",\"color\":\"Black\"}', 31990000.00, 30990000.00, 15, 'KHO1', '2025-12-11 07:35:03'),
(70, 5, 'S25U-512-WHITE', '{\"storage\":\"512GB\",\"color\":\"White\"}', 31990000.00, 30990000.00, 10, 'KHO1', '2025-12-11 07:35:03'),
(72, 5, 'S25U-1TB-BLACK', '{\"storage\":\"1TB\",\"color\":\"Black\"}', 35990000.00, 34990000.00, 10, 'KHO1', '2025-12-11 07:35:03'),
(73, 5, 'S25U-1TB-WHITE', '{\"storage\":\"1TB\",\"color\":\"White\"}', 35990000.00, 34990000.00, 10, 'KHO1', '2025-12-11 07:35:03'),
(100, 6, 'OPPO-X7-256-BLACK', '{\"storage\":\"256GB\",\"color\":\"Black\"}', 19990000.00, 18990000.00, 15, 'KHO1', '2025-12-11 07:55:21'),
(101, 6, 'OPPO-X7-256-WHITE', '{\"storage\":\"256GB\",\"color\":\"White\"}', 19990000.00, 18990000.00, 10, 'KHO1', '2025-12-11 07:55:21'),
(103, 6, 'OPPO-X7-512-BLACK', '{\"storage\":\"512GB\",\"color\":\"Black\"}', 21990000.00, 20990000.00, 10, 'KHO1', '2025-12-11 07:55:21'),
(104, 6, 'OPPO-X7-512-WHITE', '{\"storage\":\"512GB\",\"color\":\"White\"}', 21990000.00, 20990000.00, 8, 'KHO1', '2025-12-11 07:55:21'),
(106, 6, 'OPPO-X7-1TB-BLACK', '{\"storage\":\"1TB\",\"color\":\"Black\"}', 24990000.00, 23990000.00, 5, 'KHO1', '2025-12-11 07:55:21'),
(107, 6, 'OPPO-X7-1TB-WHITE', '{\"storage\":\"1TB\",\"color\":\"White\"}', 24990000.00, 23990000.00, 5, 'KHO1', '2025-12-11 07:55:21'),
(109, 7, 'OPPO-X7PRO-256-BLACK', '{\"storage\":\"256GB\",\"color\":\"Black\"}', 25990000.00, 24990000.00, 10, 'KHO1', '2025-12-11 07:55:21'),
(110, 7, 'OPPO-X7PRO-256-WHITE', '{\"storage\":\"256GB\",\"color\":\"White\"}', 25990000.00, 24990000.00, 8, 'KHO1', '2025-12-11 07:55:21'),
(112, 7, 'OPPO-X7PRO-512-BLACK', '{\"storage\":\"512GB\",\"color\":\"Black\"}', 27990000.00, 26990000.00, 6, 'KHO1', '2025-12-11 07:55:21'),
(113, 7, 'OPPO-X7PRO-512-WHITE', '{\"storage\":\"512GB\",\"color\":\"White\"}', 27990000.00, 26990000.00, 6, 'KHO1', '2025-12-11 07:55:21'),
(115, 7, 'OPPO-X7PRO-1TB-BLACK', '{\"storage\":\"1TB\",\"color\":\"Black\"}', 31990000.00, 30990000.00, 3, 'KHO1', '2025-12-11 07:55:21'),
(116, 7, 'OPPO-X7PRO-1TB-WHITE', '{\"storage\":\"1TB\",\"color\":\"White\"}', 31990000.00, 30990000.00, 3, 'KHO1', '2025-12-11 07:55:21'),
(117, 8, 'RN14P-BLACK', '{\"storage\":\"256GB\",\"color\":\"Black\"}', 8990000.00, 8490000.00, 20, 'KHO1', '2025-12-11 09:09:54'),
(118, 8, 'RN14P-WHITE', '{\"storage\":\"256GB\",\"color\":\"White\"}', 8990000.00, 8490000.00, 15, 'KHO1', '2025-12-11 09:09:54'),
(119, 8, 'RN14P-BLUE', '{\"storage\":\"256GB\",\"color\":\"Blue\"}', 8990000.00, 8490000.00, 10, 'KHO1', '2025-12-11 09:09:54'),
(120, 9, 'RN13P-BLACK', '{\"storage\":\"128GB\",\"color\":\"Black\"}', 7990000.00, 7490000.00, 25, 'KHO1', '2025-12-11 09:09:54'),
(121, 9, 'RN13P-WHITE', '{\"storage\":\"128GB\",\"color\":\"White\"}', 7990000.00, 7490000.00, 20, 'KHO1', '2025-12-11 09:09:54'),
(122, 9, 'RN13P-BLUE', '{\"storage\":\"128GB\",\"color\":\"Blue\"}', 7990000.00, 7490000.00, 15, 'KHO1', '2025-12-11 09:09:54'),
(123, 10, 'RN12-BLACK', '{\"storage\":\"128GB\",\"color\":\"Black\"}', 6990000.00, 6490000.00, 30, 'KHO1', '2025-12-11 09:09:54'),
(124, 10, 'RN12-WHITE', '{\"storage\":\"128GB\",\"color\":\"White\"}', 6990000.00, 6490000.00, 25, 'KHO1', '2025-12-11 09:09:54'),
(125, 11, 'MBA-M2-8-256-SIL', '{\"ram\":\"8GB\",\"ssd\":\"256GB\",\"color\":\"Silver\"}', 31990000.00, 30990000.00, 10, 'KHO1', '2025-12-11 09:58:57'),
(126, 11, 'MBA-M2-8-256-GRAY', '{\"ram\":\"8GB\",\"ssd\":\"256GB\",\"color\":\"Space Gray\"}', 31990000.00, 30990000.00, 10, 'KHO1', '2025-12-11 09:58:57'),
(127, 11, 'MBA-M2-8-512-SIL', '{\"ram\":\"8GB\",\"ssd\":\"512GB\",\"color\":\"Silver\"}', 35990000.00, 34990000.00, 8, 'KHO1', '2025-12-11 09:58:57'),
(128, 11, 'MBA-M2-8-512-GRAY', '{\"ram\":\"8GB\",\"ssd\":\"512GB\",\"color\":\"Space Gray\"}', 35990000.00, 34990000.00, 8, 'KHO1', '2025-12-11 09:58:57'),
(129, 11, 'MBA-M2-16-256-SIL', '{\"ram\":\"16GB\",\"ssd\":\"256GB\",\"color\":\"Silver\"}', 35990000.00, 34990000.00, 5, 'KHO1', '2025-12-11 09:58:57'),
(130, 11, 'MBA-M2-16-256-GRAY', '{\"ram\":\"16GB\",\"ssd\":\"256GB\",\"color\":\"Space Gray\"}', 35990000.00, 34990000.00, 5, 'KHO1', '2025-12-11 09:58:57'),
(131, 11, 'MBA-M2-16-512-SIL', '{\"ram\":\"16GB\",\"ssd\":\"512GB\",\"color\":\"Silver\"}', 39990000.00, 38990000.00, 3, 'KHO1', '2025-12-11 09:58:57'),
(132, 11, 'MBA-M2-16-512-GRAY', '{\"ram\":\"16GB\",\"ssd\":\"512GB\",\"color\":\"Space Gray\"}', 39990000.00, 38990000.00, 3, 'KHO1', '2025-12-11 09:58:57'),
(133, 12, 'MBA-M3-8-256-SIL', '{\"ram\":\"8GB\",\"ssd\":\"256GB\",\"color\":\"Silver\"}', 32990000.00, 31990000.00, 10, 'KHO1', '2025-12-11 09:58:57'),
(134, 12, 'MBA-M3-8-256-GRAY', '{\"ram\":\"8GB\",\"ssd\":\"256GB\",\"color\":\"Space Gray\"}', 32990000.00, 31990000.00, 10, 'KHO1', '2025-12-11 09:58:57'),
(135, 12, 'MBA-M3-8-512-SIL', '{\"ram\":\"8GB\",\"ssd\":\"512GB\",\"color\":\"Silver\"}', 36990000.00, 35990000.00, 8, 'KHO1', '2025-12-11 09:58:57'),
(136, 12, 'MBA-M3-8-512-GRAY', '{\"ram\":\"8GB\",\"ssd\":\"512GB\",\"color\":\"Space Gray\"}', 36990000.00, 35990000.00, 8, 'KHO1', '2025-12-11 09:58:57'),
(137, 12, 'MBA-M3-16-256-SIL', '{\"ram\":\"16GB\",\"ssd\":\"256GB\",\"color\":\"Silver\"}', 36990000.00, 35990000.00, 5, 'KHO1', '2025-12-11 09:58:57'),
(138, 12, 'MBA-M3-16-256-GRAY', '{\"ram\":\"16GB\",\"ssd\":\"256GB\",\"color\":\"Space Gray\"}', 36990000.00, 35990000.00, 5, 'KHO1', '2025-12-11 09:58:57'),
(139, 12, 'MBA-M3-16-512-SIL', '{\"ram\":\"16GB\",\"ssd\":\"512GB\",\"color\":\"Silver\"}', 40990000.00, 39990000.00, 3, 'KHO1', '2025-12-11 09:58:57'),
(140, 12, 'MBA-M3-16-512-GRAY', '{\"ram\":\"16GB\",\"ssd\":\"512GB\",\"color\":\"Space Gray\"}', 40990000.00, 39990000.00, 3, 'KHO1', '2025-12-11 09:58:57'),
(141, 13, 'MBA-M4-8-256-SIL', '{\"ram\":\"8GB\",\"ssd\":\"256GB\",\"color\":\"Silver\"}', 33990000.00, 32990000.00, 10, 'KHO1', '2025-12-11 09:58:57'),
(142, 13, 'MBA-M4-8-256-GRAY', '{\"ram\":\"8GB\",\"ssd\":\"256GB\",\"color\":\"Space Gray\"}', 33990000.00, 32990000.00, 10, 'KHO1', '2025-12-11 09:58:57'),
(143, 13, 'MBA-M4-8-512-SIL', '{\"ram\":\"8GB\",\"ssd\":\"512GB\",\"color\":\"Silver\"}', 37990000.00, 36990000.00, 8, 'KHO1', '2025-12-11 09:58:57'),
(144, 13, 'MBA-M4-8-512-GRAY', '{\"ram\":\"8GB\",\"ssd\":\"512GB\",\"color\":\"Space Gray\"}', 37990000.00, 36990000.00, 8, 'KHO1', '2025-12-11 09:58:57'),
(145, 13, 'MBA-M4-16-256-SIL', '{\"ram\":\"16GB\",\"ssd\":\"256GB\",\"color\":\"Silver\"}', 37990000.00, 36990000.00, 5, 'KHO1', '2025-12-11 09:58:57'),
(146, 13, 'MBA-M4-16-256-GRAY', '{\"ram\":\"16GB\",\"ssd\":\"256GB\",\"color\":\"Space Gray\"}', 37990000.00, 36990000.00, 5, 'KHO1', '2025-12-11 09:58:57'),
(147, 13, 'MBA-M4-16-512-SIL', '{\"ram\":\"16GB\",\"ssd\":\"512GB\",\"color\":\"Silver\"}', 41990000.00, 40990000.00, 3, 'KHO1', '2025-12-11 09:58:57'),
(148, 13, 'MBA-M4-16-512-GRAY', '{\"ram\":\"16GB\",\"ssd\":\"512GB\",\"color\":\"Space Gray\"}', 41990000.00, 40990000.00, 2, 'KHO1', '2025-12-11 09:58:57'),
(149, 14, 'VIVS14-I5-8GB-500GB-BLACK', '{\"cpu\":\"i5 13500H\",\"ram\":\"8GB\",\"ssd\":\"500GB\",\"color\":\"Đen Không Gian\"}', 18000000.00, 17500000.00, 50, 'KHO1', '2025-12-11 13:10:53'),
(150, 14, 'VIVS14-I5-8GB-1TB-BLACK', '{\"cpu\":\"i5 13500H\",\"ram\":\"8GB\",\"ssd\":\"1TB\",\"color\":\"Đen Không Gian\"}', 19000000.00, 18500000.00, 50, 'KHO1', '2025-12-11 13:10:53'),
(151, 14, 'VIVS14-I5-16GB-500GB-BLACK', '{\"cpu\":\"i5 13500H\",\"ram\":\"16GB\",\"ssd\":\"500GB\",\"color\":\"Đen Không Gian\"}', 19000000.00, 18500000.00, 50, 'KHO1', '2025-12-11 13:10:53'),
(152, 14, 'VIVS14-I5-16GB-1TB-BLACK', '{\"cpu\":\"i5 13500H\",\"ram\":\"16GB\",\"ssd\":\"1TB\",\"color\":\"Đen Không Gian\"}', 20000000.00, 19500000.00, 50, 'KHO1', '2025-12-11 13:10:53'),
(153, 14, 'VIVS14-I5-8GB-500GB-WHITE', '{\"cpu\":\"i5 13500H\",\"ram\":\"8GB\",\"ssd\":\"500GB\",\"color\":\"Trắng Ngọc Trai\"}', 18500000.00, 18000000.00, 50, 'KHO1', '2025-12-11 13:10:53'),
(154, 14, 'VIVS14-I5-8GB-1TB-WHITE', '{\"cpu\":\"i5 13500H\",\"ram\":\"8GB\",\"ssd\":\"1TB\",\"color\":\"Trắng Ngọc Trai\"}', 19500000.00, 19000000.00, 50, 'KHO1', '2025-12-11 13:10:53'),
(155, 14, 'VIVS14-I5-16GB-500GB-WHITE', '{\"cpu\":\"i5 13500H\",\"ram\":\"16GB\",\"ssd\":\"500GB\",\"color\":\"Trắng Ngọc Trai\"}', 19500000.00, 19000000.00, 50, 'KHO1', '2025-12-11 13:10:53'),
(156, 14, 'VIVS14-I5-16GB-1TB-WHITE', '{\"cpu\":\"i5 13500H\",\"ram\":\"16GB\",\"ssd\":\"1TB\",\"color\":\"Trắng Ngọc Trai\"}', 20500000.00, 20000000.00, 50, 'KHO1', '2025-12-11 13:10:53'),
(157, 14, 'VIVS14-I7-8GB-500GB-BLACK', '{\"cpu\":\"i7 13500H\",\"ram\":\"8GB\",\"ssd\":\"500GB\",\"color\":\"Đen Không Gian\"}', 20000000.00, 19500000.00, 50, 'KHO1', '2025-12-11 13:10:53'),
(158, 14, 'VIVS14-I7-8GB-1TB-BLACK', '{\"cpu\":\"i7 13500H\",\"ram\":\"8GB\",\"ssd\":\"1TB\",\"color\":\"Đen Không Gian\"}', 21000000.00, 20500000.00, 50, 'KHO1', '2025-12-11 13:10:53'),
(159, 14, 'VIVS14-I7-16GB-500GB-BLACK', '{\"cpu\":\"i7 13500H\",\"ram\":\"16GB\",\"ssd\":\"500GB\",\"color\":\"Đen Không Gian\"}', 21000000.00, 20500000.00, 50, 'KHO1', '2025-12-11 13:10:53'),
(160, 14, 'VIVS14-I7-16GB-1TB-BLACK', '{\"cpu\":\"i7 13500H\",\"ram\":\"16GB\",\"ssd\":\"1TB\",\"color\":\"Đen Không Gian\"}', 22000000.00, 21500000.00, 50, 'KHO1', '2025-12-11 13:10:53'),
(161, 14, 'VIVS14-I7-8GB-500GB-WHITE', '{\"cpu\":\"i7 13500H\",\"ram\":\"8GB\",\"ssd\":\"500GB\",\"color\":\"Trắng Ngọc Trai\"}', 20500000.00, 20000000.00, 50, 'KHO1', '2025-12-11 13:10:53'),
(162, 14, 'VIVS14-I7-8GB-1TB-WHITE', '{\"cpu\":\"i7 13500H\",\"ram\":\"8GB\",\"ssd\":\"1TB\",\"color\":\"Trắng Ngọc Trai\"}', 21500000.00, 21000000.00, 50, 'KHO1', '2025-12-11 13:10:53'),
(163, 14, 'VIVS14-I7-16GB-500GB-WHITE', '{\"cpu\":\"i7 13500H\",\"ram\":\"16GB\",\"ssd\":\"500GB\",\"color\":\"Trắng Ngọc Trai\"}', 21500000.00, 21000000.00, 50, 'KHO1', '2025-12-11 13:10:53'),
(164, 14, 'VIVS14-I7-16GB-1TB-WHITE', '{\"cpu\":\"i7 13500H\",\"ram\":\"16GB\",\"ssd\":\"1TB\",\"color\":\"Trắng Ngọc Trai\"}', 22500000.00, 22000000.00, 50, 'KHO1', '2025-12-11 13:10:53');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sku_attribute_values`
--

CREATE TABLE `sku_attribute_values` (
  `id` int(11) NOT NULL,
  `sku_id` int(11) NOT NULL,
  `attribute_value_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `sku_attribute_values`
--

INSERT INTO `sku_attribute_values` (`id`, `sku_id`, `attribute_value_id`) VALUES
(1, 1, 1),
(2, 1, 5),
(3, 2, 1),
(4, 2, 6),
(5, 3, 1),
(6, 3, 7),
(7, 4, 1),
(8, 4, 8),
(9, 5, 2),
(10, 5, 5),
(11, 6, 2),
(12, 6, 6),
(13, 7, 2),
(14, 7, 7),
(15, 8, 2),
(16, 8, 8),
(33, 1, 1),
(34, 2, 1),
(35, 3, 1),
(36, 4, 1),
(37, 5, 2),
(38, 6, 2),
(39, 7, 2),
(40, 8, 2),
(41, 9, 3),
(42, 10, 3),
(43, 11, 3),
(44, 12, 3),
(45, 13, 4),
(46, 14, 4),
(47, 15, 4),
(48, 16, 4),
(49, 1, 5),
(50, 2, 6),
(51, 3, 7),
(52, 4, 8),
(53, 5, 5),
(54, 6, 6),
(55, 7, 7),
(56, 8, 8),
(57, 9, 5),
(58, 10, 6),
(59, 11, 7),
(60, 12, 8),
(61, 13, 5),
(62, 14, 6),
(63, 15, 7),
(64, 16, 8),
(65, 17, 1),
(66, 18, 1),
(67, 19, 1),
(68, 20, 1),
(69, 21, 2),
(70, 22, 2),
(71, 23, 2),
(72, 24, 2),
(73, 25, 3),
(74, 26, 3),
(75, 27, 3),
(76, 28, 3),
(77, 29, 4),
(78, 30, 4),
(79, 31, 4),
(80, 32, 4),
(81, 17, 5),
(82, 18, 6),
(83, 19, 7),
(84, 20, 8),
(85, 21, 5),
(86, 22, 6),
(87, 23, 7),
(88, 24, 8),
(89, 25, 5),
(90, 26, 6),
(91, 27, 7),
(92, 28, 8),
(93, 29, 5),
(94, 30, 6),
(95, 31, 7),
(96, 32, 8),
(97, 33, 1),
(98, 34, 1),
(99, 35, 1),
(100, 36, 1),
(101, 37, 2),
(102, 38, 2),
(103, 39, 2),
(104, 40, 2),
(105, 41, 3),
(106, 42, 3),
(107, 43, 3),
(108, 44, 3),
(109, 45, 4),
(110, 46, 4),
(111, 47, 4),
(112, 48, 4),
(113, 33, 5),
(114, 34, 6),
(115, 35, 7),
(116, 36, 8),
(117, 37, 5),
(118, 38, 6),
(119, 39, 7),
(120, 40, 8),
(121, 41, 5),
(122, 42, 6),
(123, 43, 7),
(124, 44, 8),
(125, 45, 5),
(126, 46, 6),
(127, 47, 7),
(128, 48, 8),
(129, 58, 1),
(130, 59, 1),
(131, 60, 2),
(132, 61, 2),
(133, 62, 3),
(134, 63, 3),
(135, 64, 4),
(136, 65, 4),
(137, 58, 5),
(138, 59, 6),
(139, 60, 5),
(140, 61, 6),
(141, 62, 5),
(142, 63, 6),
(143, 64, 5),
(144, 65, 6),
(145, 66, 2),
(146, 67, 2),
(148, 69, 3),
(149, 70, 3),
(151, 72, 4),
(152, 73, 4),
(154, 66, 5),
(155, 67, 6),
(157, 69, 5),
(158, 70, 6),
(160, 72, 5),
(161, 73, 6),
(163, 100, 2),
(164, 101, 2),
(166, 103, 3),
(167, 104, 3),
(169, 106, 4),
(170, 107, 4),
(172, 100, 5),
(173, 101, 6),
(175, 103, 5),
(176, 104, 6),
(178, 106, 5),
(179, 107, 6),
(181, 109, 2),
(182, 110, 2),
(184, 112, 3),
(185, 113, 3),
(187, 115, 4),
(188, 116, 4),
(190, 109, 5),
(191, 110, 6),
(193, 112, 5),
(194, 113, 6),
(196, 115, 5),
(197, 116, 6),
(199, 117, 5),
(200, 118, 6),
(201, 119, 7),
(202, 120, 5),
(203, 121, 6),
(204, 122, 7),
(205, 123, 5),
(206, 124, 6),
(207, 125, 12),
(208, 126, 12),
(209, 127, 12),
(210, 128, 12),
(211, 129, 13),
(212, 130, 13),
(213, 131, 13),
(214, 132, 13),
(215, 125, 14),
(216, 126, 14),
(217, 127, 15),
(218, 128, 15),
(219, 129, 14),
(220, 130, 14),
(221, 131, 15),
(222, 132, 15),
(223, 125, 5),
(224, 126, 6),
(225, 127, 5),
(226, 128, 6),
(227, 129, 5),
(228, 130, 6),
(229, 131, 5),
(230, 132, 6),
(231, 133, 12),
(232, 134, 12),
(233, 135, 12),
(234, 136, 12),
(235, 137, 13),
(236, 138, 13),
(237, 139, 13),
(238, 140, 13),
(239, 133, 14),
(240, 134, 14),
(241, 135, 15),
(242, 136, 15),
(243, 137, 14),
(244, 138, 14),
(245, 139, 15),
(246, 140, 15),
(247, 133, 5),
(248, 134, 6),
(249, 135, 5),
(250, 136, 6),
(251, 137, 5),
(252, 138, 6),
(253, 139, 5),
(254, 140, 6),
(255, 141, 12),
(256, 142, 12),
(257, 143, 12),
(258, 144, 12),
(259, 145, 13),
(260, 146, 13),
(261, 147, 13),
(262, 148, 13),
(263, 141, 14),
(264, 142, 14),
(265, 143, 15),
(266, 144, 15),
(267, 145, 14),
(268, 146, 14),
(269, 147, 15),
(270, 148, 15),
(271, 141, 5),
(272, 142, 6),
(273, 143, 5),
(274, 144, 6),
(275, 145, 5),
(276, 146, 6),
(277, 147, 5),
(278, 148, 6),
(279, 125, 12),
(280, 126, 12),
(281, 127, 12),
(282, 128, 12),
(283, 129, 13),
(284, 130, 13),
(285, 131, 13),
(286, 132, 13),
(287, 125, 14),
(288, 126, 14),
(289, 127, 15),
(290, 128, 15),
(291, 129, 14),
(292, 130, 14),
(293, 131, 15),
(294, 132, 15),
(295, 125, 5),
(296, 126, 6),
(297, 127, 5),
(298, 128, 6),
(299, 129, 5),
(300, 130, 6),
(301, 131, 5),
(302, 132, 6),
(303, 133, 12),
(304, 134, 12),
(305, 135, 12),
(306, 136, 12),
(307, 137, 13),
(308, 138, 13),
(309, 139, 13),
(310, 140, 13),
(311, 133, 14),
(312, 134, 14),
(313, 135, 15),
(314, 136, 15),
(315, 137, 14),
(316, 138, 14),
(317, 139, 15),
(318, 140, 15),
(319, 133, 5),
(320, 134, 6),
(321, 135, 5),
(322, 136, 6),
(323, 137, 5),
(324, 138, 6),
(325, 139, 5),
(326, 140, 6),
(327, 141, 12),
(328, 142, 12),
(329, 143, 12),
(330, 144, 12),
(331, 145, 13),
(332, 146, 13),
(333, 147, 13),
(334, 148, 13),
(335, 141, 14),
(336, 142, 14),
(337, 143, 15),
(338, 144, 15),
(339, 145, 14),
(340, 146, 14),
(341, 147, 15),
(342, 148, 15),
(343, 141, 5),
(344, 142, 6),
(345, 143, 5),
(346, 144, 6),
(347, 145, 5),
(348, 146, 6),
(349, 147, 5),
(350, 148, 6),
(391, 149, 10),
(392, 150, 10),
(393, 151, 10),
(394, 152, 10),
(395, 153, 10),
(396, 154, 10),
(397, 155, 10),
(398, 156, 10),
(399, 157, 11),
(400, 158, 11),
(401, 159, 11),
(402, 160, 11),
(403, 161, 11),
(404, 162, 11),
(405, 163, 11),
(406, 164, 11),
(407, 149, 12),
(408, 150, 12),
(409, 151, 13),
(410, 152, 13),
(411, 153, 12),
(412, 154, 12),
(413, 155, 13),
(414, 156, 13),
(415, 157, 12),
(416, 158, 12),
(417, 159, 13),
(418, 160, 13),
(419, 161, 12),
(420, 162, 12),
(421, 163, 13),
(422, 164, 13),
(423, 149, 15),
(424, 150, 16),
(425, 151, 15),
(426, 152, 16),
(427, 153, 15),
(428, 154, 16),
(429, 155, 15),
(430, 156, 16),
(431, 157, 15),
(432, 158, 16),
(433, 159, 15),
(434, 160, 16),
(435, 161, 15),
(436, 162, 16),
(437, 163, 15),
(438, 164, 16),
(439, 149, 5),
(440, 150, 5),
(441, 151, 5),
(442, 152, 5),
(443, 153, 6),
(444, 154, 6),
(445, 155, 6),
(446, 156, 6),
(447, 157, 5),
(448, 158, 5),
(449, 159, 5),
(450, 160, 5),
(451, 161, 6),
(452, 162, 6),
(453, 163, 6),
(454, 164, 6);

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

--
-- Đang đổ dữ liệu cho bảng `sku_images`
--

INSERT INTO `sku_images` (`id`, `sku_id`, `image_url`, `is_primary`) VALUES
(1, 1, '/techzone/assets/images/iphone14/black.jpg', 1),
(2, 2, '/techzone/assets/images/iphone14/white.jpg', 1),
(3, 3, '/techzone/assets/images/iphone14/blue.jpg', 1),
(4, 4, '/techzone/assets/images/iphone14/titan.jpg', 1),
(5, 5, '/techzone/assets/images/iphone14/black.jpg', 1),
(6, 6, '/techzone/assets/images/iphone14/white.jpg', 1),
(7, 7, '/techzone/assets/images/iphone14/blue.jpg', 1),
(8, 8, '/techzone/assets/images/iphone14/titan.jpg', 1),
(9, 9, '/techzone/assets/images/iphone14/black.jpg', 1),
(10, 10, '/techzone/assets/images/iphone14/white.jpg', 1),
(11, 11, '/techzone/assets/images/iphone14/blue.jpg', 1),
(12, 12, '/techzone/assets/images/iphone14/titan.jpg', 1),
(13, 13, '/techzone/assets/images/iphone14/black.jpg', 1),
(14, 14, '/techzone/assets/images/iphone14/white.jpg', 1),
(15, 15, '/techzone/assets/images/iphone14/blue.jpg', 1),
(16, 16, '/techzone/assets/images/iphone14/titan.jpg', 1),
(17, 17, '/techzone/assets/images/iphone15/black.jpg', 1),
(18, 18, '/techzone/assets/images/iphone15/white.jpg', 1),
(19, 19, '/techzone/assets/images/iphone15/blue.jpg', 1),
(20, 20, '/techzone/assets/images/iphone15/titan.jpg', 1),
(21, 21, '/techzone/assets/images/iphone15/black.jpg', 1),
(22, 22, '/techzone/assets/images/iphone15/white.jpg', 1),
(23, 23, '/techzone/assets/images/iphone15/blue.jpg', 1),
(24, 24, '/techzone/assets/images/iphone15/titan.jpg', 1),
(25, 25, '/techzone/assets/images/iphone15/black.jpg', 1),
(26, 26, '/techzone/assets/images/iphone15/white.jpg', 1),
(27, 27, '/techzone/assets/images/iphone15/blue.jpg', 1),
(28, 28, '/techzone/assets/images/iphone15/titan.jpg', 1),
(29, 29, '/techzone/assets/images/iphone15/black.jpg', 1),
(30, 30, '/techzone/assets/images/iphone15/white.jpg', 1),
(31, 31, '/techzone/assets/images/iphone15/blue.jpg', 1),
(32, 32, '/techzone/assets/images/iphone15/titan.jpg', 1),
(49, 33, '/techzone/assets/images/iphone16/black.jpg', 1),
(50, 34, '/techzone/assets/images/iphone16/white.jpg', 1),
(51, 35, '/techzone/assets/images/iphone16/blue.jpg', 1),
(52, 36, '/techzone/assets/images/iphone16/titan.jpg', 1),
(53, 37, '/techzone/assets/images/iphone16/black.jpg', 1),
(54, 38, '/techzone/assets/images/iphone16/white.jpg', 1),
(55, 39, '/techzone/assets/images/iphone16/blue.jpg', 1),
(56, 40, '/techzone/assets/images/iphone16/titan.jpg', 1),
(57, 41, '/techzone/assets/images/iphone16/black.jpg', 1),
(58, 42, '/techzone/assets/images/iphone16/white.jpg', 1),
(59, 43, '/techzone/assets/images/iphone16/blue.jpg', 1),
(60, 44, '/techzone/assets/images/iphone16/titan.jpg', 1),
(61, 45, '/techzone/assets/images/iphone16/black.jpg', 1),
(62, 46, '/techzone/assets/images/iphone16/white.jpg', 1),
(63, 47, '/techzone/assets/images/iphone16/blue.jpg', 1),
(64, 48, '/techzone/assets/images/iphone16/titan.jpg', 1),
(65, 58, '/techzone//assets/images/s25/black.jpg', 1),
(66, 59, '/techzone//assets/images/s25/white.jpg', 1),
(67, 60, '/techzone//assets/images/s25/black.jpg', 1),
(68, 61, '/techzone//assets/images/s25/white.jpg', 1),
(69, 62, '/techzone//assets/images/s25/black.jpg', 1),
(70, 63, '/techzone//assets/images/s25/white.jpg', 1),
(71, 64, '/techzone//assets/images/s25/black.jpg', 1),
(72, 65, '/techzone//assets/images/s25/white.jpg', 1),
(73, 66, '/techzone//assets/images/s25u/black.jpg', 1),
(74, 67, '/techzone//assets/images/s25u/white.jpg', 1),
(76, 69, '/techzone//assets/images/s25u/black.jpg', 1),
(77, 70, '/techzone//assets/images/s25u/white.jpg', 1),
(79, 72, '/techzone//assets/images/s25u/black.jpg', 1),
(80, 73, '/techzone/assets/images/s25u/white.jpg', 1),
(82, 100, '/techzone/assets/images/oppo/x7-black.jpg', 1),
(83, 101, '/techzone/assets/images/oppo/x7-white.jpg', 1),
(85, 103, '/techzone/assets/images/oppo/x7-black.jpg', 1),
(86, 104, '/techzone/assets/images/oppo/x7-white.jpg', 1),
(88, 106, '/techzone/assets/images/oppo/x7-black.jpg', 1),
(89, 107, '/techzone/assets/images/oppo/x7-white.jpg', 1),
(91, 109, '/techzone/assets/images/oppo/x7pro-black.jpg', 1),
(92, 110, '/techzone/assets/images/oppo/x7pro-white.jpg', 1),
(94, 112, '/techzone/assets/images/oppo/x7pro-black.jpg', 1),
(95, 113, '/techzone/assets/images/oppo/x7pro-white.jpg', 1),
(97, 115, '/techzone/assets/images/oppo/x7pro-black.jpg', 1),
(98, 116, '/techzone/assets/images/oppo/x7pro-white.jpg', 1),
(100, 117, '/techzone/assets/images/xiaomi/redmi14pro/black.jpg', 1),
(101, 118, '/techzone/assets/images/xiaomi/redmi14pro/white.jpg', 1),
(102, 119, '/techzone/assets/images/xiaomi/redmi14pro/blue.jpg', 1),
(103, 120, '/techzone/assets/images/xiaomi/redmi13pro/black.jpg', 1),
(104, 121, '/techzone/assets/images/xiaomi/redmi13pro/white.jpg', 1),
(105, 122, '/techzone/assets/images/xiaomi/redmi13pro/blue.jpg', 1),
(106, 123, '/techzone/assets/images/xiaomi/redmi12/black.jpg', 1),
(107, 124, '/techzone/assets/images/xiaomi/redmi12/white.jpg', 1),
(108, 125, '/techzone/assets/images/macbook/air-m2-silver.jpg', 1),
(109, 126, '/techzone/assets/images/macbook/air-m2-gray.jpg', 1),
(110, 127, '/techzone/assets/images/macbook/air-m2-silver.jpg', 1),
(111, 128, '/techzone/assets/images/macbook/air-m2-gray.jpg', 1),
(112, 129, '/techzone/assets/images/macbook/air-m2-silver.jpg', 1),
(113, 130, '/techzone/assets/images/macbook/air-m2-gray.jpg', 1),
(114, 131, '/techzone/assets/images/macbook/air-m2-silver.jpg', 1),
(115, 132, '/techzone/assets/images/macbook/air-m2-gray.jpg', 1),
(116, 125, '/techzone/assets/images/macbook/air-m2-silver.jpg', 1),
(117, 126, '/techzone/assets/images/macbook/air-m2-gray.jpg', 1),
(118, 127, '/techzone/assets/images/macbook/air-m2-silver.jpg', 1),
(119, 128, '/techzone/assets/images/macbook/air-m2-gray.jpg', 1),
(120, 129, '/techzone/assets/images/macbook/air-m2-silver.jpg', 1),
(121, 130, '/techzone/assets/images/macbook/air-m2-gray.jpg', 1),
(122, 131, '/techzone/assets/images/macbook/air-m2-silver.jpg', 1),
(123, 132, '/techzone/assets/images/macbook/air-m2-gray.jpg', 1),
(124, 133, '/techzone/assets/images/macbook/air-m3-silver.jpg', 1),
(125, 134, '/techzone/assets/images/macbook/air-m3-gray.jpg', 1),
(126, 135, '/techzone/assets/images/macbook/air-m3-silver.jpg', 1),
(127, 136, '/techzone/assets/images/macbook/air-m3-gray.jpg', 1),
(128, 137, '/techzone/assets/images/macbook/air-m3-silver.jpg', 1),
(129, 138, '/techzone/assets/images/macbook/air-m3-gray.jpg', 1),
(130, 139, '/techzone/assets/images/macbook/air-m3-silver.jpg', 1),
(131, 140, '/techzone/assets/images/macbook/air-m3-gray.jpg', 1),
(132, 141, '/techzone/assets/images/macbook/air-m4-silver.jpg', 1),
(133, 142, '/techzone/assets/images/macbook/air-m4-gray.jpg', 1),
(134, 143, '/techzone/assets/images/macbook/air-m4-silver.jpg', 1),
(135, 144, '/techzone/assets/images/macbook/air-m4-gray.jpg', 1),
(136, 145, '/techzone/assets/images/macbook/air-m4-silver.jpg', 1),
(137, 146, '/techzone/assets/images/macbook/air-m4-gray.jpg', 1),
(138, 147, '/techzone/assets/images/macbook/air-m4-silver.jpg', 1),
(139, 148, '/techzone/assets/images/macbook/air-m4-gray.jpg', 1),
(140, 149, '/techzone/assets/images/asus/vivs14-black.jpg', 1),
(141, 150, '/techzone/assets/images/asus/vivs14-black.jpg', 1),
(142, 151, '/techzone/assets/images/asus/vivs14-black.jpg', 1),
(143, 152, '/techzone/assets/images/asus/vivs14-black.jpg', 1),
(144, 153, '/techzone/assets/images/asus/vivs14-black.jpg', 1),
(145, 154, '/techzone/assets/images/asus/vivs14-black.jpg', 1),
(146, 155, '/techzone/assets/images/asus/vivs14-black.jpg', 1),
(147, 156, '/techzone/assets/images/asus/vivs14-black.jpg', 1),
(148, 157, '/techzone/assets/images/asus/vivs14-white.jpg', 1),
(149, 158, '/techzone/assets/images/asus/vivs14-white.jpg', 1),
(150, 159, '/techzone/assets/images/asus/vivs14-white.jpg', 1),
(151, 160, '/techzone/assets/images/asus/vivs14-white.jpg', 1),
(152, 161, '/techzone/assets/images/asus/vivs14-white.jpg', 1),
(153, 162, '/techzone/assets/images/asus/vivs14-white.jpg', 1),
(154, 163, '/techzone/assets/images/asus/vivs14-white.jpg', 1),
(155, 164, '/techzone/assets/images/asus/vivs14-white.jpg', 1);

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
(1, 'iPhone 14', 'Apple', 9, 'iPhone 14', '2025-12-08 02:09:30'),
(2, 'iPhone 15', 'Apple', 9, 'iPhone 15', '2025-12-08 02:09:30'),
(3, 'iPhone 16', 'Apple', 9, 'iPhone 16 thế hệ mới với nhiều cải tiến.', '2025-12-11 07:07:04'),
(4, 'S25', 'Samsung', 10, NULL, '2025-12-11 07:33:21'),
(5, 'S25 Ultra', 'Samsung', 10, NULL, '2025-12-11 07:33:21'),
(6, 'Find X9', 'Oppo', 11, 'Oppo Find X9 - cao cấp, sang trọng', '2025-12-11 07:53:29'),
(7, 'Find X9 Pro', 'Oppo', 11, 'Oppo Find X9 Pro - cao cấp, tối ưu hiệu năng', '2025-12-11 07:53:29'),
(8, 'Redmi Note 14 Pro+', 'Xiaomi', 12, 'Điện thoại Xiaomi Redmi Note 14 Pro+', '2025-12-11 09:07:49'),
(9, 'Redmi Note 13 Pro+', 'Xiaomi', 12, 'Điện thoại Xiaomi Redmi Note 13 Pro+', '2025-12-11 09:07:49'),
(10, 'Redmi Note 12', 'Xiaomi', 12, 'Điện thoại Xiaomi Redmi Note 12', '2025-12-11 09:07:49'),
(11, 'MacBook Air M2', 'Apple', 5, NULL, '2025-12-11 09:53:52'),
(12, 'MacBook Air M3', 'Apple', 5, NULL, '2025-12-11 09:53:52'),
(13, 'MacBook Air M4', 'Apple', 5, NULL, '2025-12-11 09:53:52'),
(14, 'Vivobook S14', 'Asus', 6, 'Laptop Asus Vivobook S14, phù hợp học tập và văn phòng.', '2025-12-11 12:40:08'),
(15, 'TUF F15', 'Asus', 6, 'Laptop Asus TUF F15, mạnh mẽ cho gaming và làm việc nặng.', '2025-12-11 12:40:08'),
(16, 'ROG Strix G15', 'Asus', 6, 'Laptop Asus ROG Strix G15, tối ưu cho game thủ chuyên nghiệp.', '2025-12-11 12:40:08');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `gender` enum('Nam','Nữ','Khác') DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('customer','admin') DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `province_id` int(11) DEFAULT NULL,
  `district_id` int(11) DEFAULT NULL,
  `ward_id` int(11) DEFAULT NULL,
  `street` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `fullname`, `gender`, `birthday`, `email`, `password`, `role`, `created_at`, `phone`, `address`, `province_id`, `district_id`, `ward_id`, `street`) VALUES
(1, 'Nam', 'Nam', '2005-08-30', 'user@gmail.com', '$2y$10$CIHU9nhtsgDv7WFoj49B3.V8x62xguhZfwFrRX5VaeRhEjftL7wwe', '', '2025-12-03 05:56:54', '0932660941', NULL, 51, 522, NULL, '123 Lê Lợi'),
(2, 'Admin', NULL, NULL, 'admin@gmail.com', '$2y$10$CIHU9nhtsgDv7WFoj49B3.V8x62xguhZfwFrRX5VaeRhEjftL7wwe', 'admin', '2025-12-03 05:56:54', NULL, NULL, NULL, NULL, NULL, NULL);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `attributes`
--
ALTER TABLE `attributes`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `attribute_values`
--
ALTER TABLE `attribute_values`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attribute_id` (`attribute_id`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Chỉ mục cho bảng `districts`
--
ALTER TABLE `districts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `province_id` (`province_id`);

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
  ADD KEY `fk_orders_province` (`province_id`),
  ADD KEY `fk_orders_district` (`district_id`),
  ADD KEY `fk_orders_user` (`user_id`);

--
-- Chỉ mục cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `sku_id` (`sku_id`);

--
-- Chỉ mục cho bảng `order_item_discounts`
--
ALTER TABLE `order_item_discounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_item_id` (`order_item_id`);

--
-- Chỉ mục cho bảng `order_item_returns`
--
ALTER TABLE `order_item_returns`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `order_tracking`
--
ALTER TABLE `order_tracking`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Chỉ mục cho bảng `provinces`
--
ALTER TABLE `provinces`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `sku`
--
ALTER TABLE `sku`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sku_code` (`sku_code`),
  ADD KEY `spu_id` (`spu_id`);

--
-- Chỉ mục cho bảng `sku_attribute_values`
--
ALTER TABLE `sku_attribute_values`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sku_id` (`sku_id`),
  ADD KEY `attribute_value_id` (`attribute_value_id`);

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
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `province_id` (`province_id`),
  ADD KEY `district_id` (`district_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `attributes`
--
ALTER TABLE `attributes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT cho bảng `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `item`
--
ALTER TABLE `item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `order_item_discounts`
--
ALTER TABLE `order_item_discounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `order_item_returns`
--
ALTER TABLE `order_item_returns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `order_tracking`
--
ALTER TABLE `order_tracking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `sku`
--
ALTER TABLE `sku`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=165;

--
-- AUTO_INCREMENT cho bảng `sku_attribute_values`
--
ALTER TABLE `sku_attribute_values`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=455;

--
-- AUTO_INCREMENT cho bảng `sku_images`
--
ALTER TABLE `sku_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=156;

--
-- AUTO_INCREMENT cho bảng `spu`
--
ALTER TABLE `spu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `attribute_values`
--
ALTER TABLE `attribute_values`
  ADD CONSTRAINT `attribute_values_ibfk_1` FOREIGN KEY (`attribute_id`) REFERENCES `attributes` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `districts`
--
ALTER TABLE `districts`
  ADD CONSTRAINT `districts_ibfk_1` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`);

--
-- Các ràng buộc cho bảng `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `item_ibfk_1` FOREIGN KEY (`sku_id`) REFERENCES `sku` (`id`);

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_district` FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_orders_province` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`sku_id`) REFERENCES `sku` (`id`);

--
-- Các ràng buộc cho bảng `order_item_discounts`
--
ALTER TABLE `order_item_discounts`
  ADD CONSTRAINT `order_item_discounts_ibfk_1` FOREIGN KEY (`order_item_id`) REFERENCES `order_items` (`id`);

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
-- Các ràng buộc cho bảng `sku_attribute_values`
--
ALTER TABLE `sku_attribute_values`
  ADD CONSTRAINT `sku_attribute_values_ibfk_1` FOREIGN KEY (`sku_id`) REFERENCES `sku` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sku_attribute_values_ibfk_2` FOREIGN KEY (`attribute_value_id`) REFERENCES `attribute_values` (`id`) ON DELETE CASCADE;

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

--
-- Các ràng buộc cho bảng `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`),
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
