-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th1 04, 2021 lúc 08:54 AM
-- Phiên bản máy phục vụ: 10.1.37-MariaDB
-- Phiên bản PHP: 7.1.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `db_muaban_pos`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_account_account`
--

CREATE TABLE `tbl_account_account` (
  `id` int(11) UNSIGNED NOT NULL,
  `id_type` int(11) UNSIGNED NOT NULL,
  `id_business` int(11) UNSIGNED NOT NULL,
  `account_username` varchar(100) CHARACTER SET utf8 NOT NULL,
  `account_password` varchar(100) CHARACTER SET utf8 NOT NULL,
  `account_fullname` varchar(50) CHARACTER SET utf8 NOT NULL,
  `account_email` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `account_phone` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `account_status` enum('Y','N') CHARACTER SET utf8 NOT NULL DEFAULT 'Y',
  `force_sign_out` enum('0','1') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Đang đổ dữ liệu cho bảng `tbl_account_account`
--

INSERT INTO `tbl_account_account` (`id`, `id_type`, `id_business`, `account_username`, `account_password`, `account_fullname`, `account_email`, `account_phone`, `account_status`, `force_sign_out`) VALUES
(1, 1, 1, 'abc', 'e10adc3949ba59abbe56e057f20f883e', 'ABC', 'ABC@gmail.com', '0808080', 'Y', '0'),
(2, 2, 1, 'abd', '4911e516e5aa21d327512e0c8b197616', 'ABD', 'ABD@gmail.com', '090909', 'Y', '0'),
(3, 3, 1, 'abe', '7888d65a43501d992cc38638b59964d6', 'ABE', 'ABE@gmail.com', '0707070', 'Y', '0');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_account_authorize`
--

CREATE TABLE `tbl_account_authorize` (
  `id` int(11) UNSIGNED NOT NULL,
  `id_admin` int(11) UNSIGNED NOT NULL,
  `grant_permission` int(11) UNSIGNED NOT NULL,
  `id_business` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Đang đổ dữ liệu cho bảng `tbl_account_authorize`
--

INSERT INTO `tbl_account_authorize` (`id`, `id_admin`, `grant_permission`, `id_business`) VALUES
(1, 1, 1, 1),
(2, 2, 2, 1),
(3, 3, 3, 1),
(6, 1, 2, 1),
(7, 1, 3, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_account_permission`
--

CREATE TABLE `tbl_account_permission` (
  `id` int(11) UNSIGNED NOT NULL,
  `permission` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `id_business` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Đang đổ dữ liệu cho bảng `tbl_account_permission`
--

INSERT INTO `tbl_account_permission` (`id`, `permission`, `description`, `id_business`) VALUES
(1, 'Quản lý Bếp', '', 1),
(2, 'Quản lý order', '', 1),
(3, 'Quản lý Khách', '', 1),
(4, 'Quản lý bàn', '', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_account_type`
--

CREATE TABLE `tbl_account_type` (
  `id` int(11) UNSIGNED NOT NULL,
  `type_account` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `id_business` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Đang đổ dữ liệu cho bảng `tbl_account_type`
--

INSERT INTO `tbl_account_type` (`id`, `type_account`, `description`, `id_business`) VALUES
(1, 'admin', '', 1),
(2, 'order', '', 1),
(3, 'chef', '', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_app_deploy`
--

CREATE TABLE `tbl_app_deploy` (
  `id` int(11) UNSIGNED NOT NULL,
  `live_version` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_business_model`
--

CREATE TABLE `tbl_business_model` (
  `id` int(11) UNSIGNED NOT NULL,
  `business_model` enum('L','S') CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Đang đổ dữ liệu cho bảng `tbl_business_model`
--

INSERT INTO `tbl_business_model` (`id`, `business_model`) VALUES
(1, 'L'),
(2, 'S');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_business_store`
--

CREATE TABLE `tbl_business_store` (
  `id` int(11) UNSIGNED NOT NULL,
  `id_business_model` int(11) NOT NULL,
  `store_name` varchar(100) NOT NULL,
  `store_code` varchar(100) NOT NULL,
  `store_phone` varchar(20) NOT NULL,
  `store_address` varchar(300) NOT NULL,
  `store_prefix` varchar(100) CHARACTER SET utf8 NOT NULL,
  `store_created` date NOT NULL,
  `store_expired` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Đang đổ dữ liệu cho bảng `tbl_business_store`
--

INSERT INTO `tbl_business_store` (`id`, `id_business_model`, `store_name`, `store_code`, `store_phone`, `store_address`, `store_prefix`, `store_created`, `store_expired`) VALUES
(1, 1, 'Cửa hàng bánh mì', 'BM', '0909090', 'adsfadfadf', 'BM', '0000-00-00', '0000-00-00'),
(2, 2, 'Cửa hàng bún', 'CHB', '09050504', 'phan văn trị', 'CHB', '2020-12-01', '2020-12-03');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_customer_customer`
--

CREATE TABLE `tbl_customer_customer` (
  `id` int(11) UNSIGNED NOT NULL,
  `id_business` int(11) UNSIGNED NOT NULL,
  `id_account` int(11) UNSIGNED NOT NULL,
  `customer_code` varchar(100) CHARACTER SET utf8 NOT NULL,
  `customer_name` varchar(100) CHARACTER SET utf8 NOT NULL,
  `customer_phone` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `customer_sex` enum('male','female','comp') CHARACTER SET utf8 NOT NULL DEFAULT 'male',
  `customer_email` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `customer_birthday` date DEFAULT NULL,
  `customer_address` varchar(300) CHARACTER SET utf8 NOT NULL,
  `customer_point` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '0',
  `customer_taxcode` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `customer_created_by` datetime NOT NULL,
  `force_sign_out` enum('0','1') CHARACTER SET utf8 NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Đang đổ dữ liệu cho bảng `tbl_customer_customer`
--

INSERT INTO `tbl_customer_customer` (`id`, `id_business`, `id_account`, `customer_code`, `customer_name`, `customer_phone`, `customer_sex`, `customer_email`, `customer_birthday`, `customer_address`, `customer_point`, `customer_taxcode`, `customer_created_by`, `force_sign_out`) VALUES
(1, 1, 2, 'ACfufucucu', 'chi Diễm', '0879797', 'female', 'han', '1998-12-02', 'kkkkkkk', '700', '1111111', '0000-00-00 00:00:00', '0'),
(2, 1, 2, 'AD', 'Anh Dinh', '123456', 'male', 'fffffff', NULL, 'adfafaf', '200', '123', '0000-00-00 00:00:00', '0'),
(3, 1, 2, 'AD', 'a ', '1231213', 'male', '2020-12-31', '2020-12-31', 'adfafaf', '500', NULL, '0000-00-00 00:00:00', '0'),
(4, 1, 2, 'AD', 'a DInh', '05050505', 'male', 'fffffff', NULL, 'adfafaf', '1200', NULL, '0000-00-00 00:00:00', '0'),
(6, 1, 2, 'ttt', 'M L', '125398', 'male', '2021-01-04', '2021-01-04', 'hcychvh', '1300', 'gxtcg g g', '0000-00-00 00:00:00', '0'),
(8, 1, 1, 'hh', 'Phung Quoc Minh Khanh', '0336819000', 'male', 'khanh@minhkhanh.com', NULL, 'Califonia', '3600', '1111', '0000-00-00 00:00:00', '0'),
(9, 1, 1, 'hh', 'h&acirc;n', '132465789', 'male', 'han@minhkhanh.com', NULL, 'Califonia', '2600', '1111', '0000-00-00 00:00:00', '0'),
(10, 1, 1, 'hh', 'Phung Quoc Minh Khanh', '0336819000', 'male', 'khanh@minhkhanh.com', NULL, 'Califonia', '2000', '1111', '0000-00-00 00:00:00', '0'),
(11, 1, 2, 'BMKH09213876', 'a T&acirc;m', '0335506678', 'male', 'tam@gmail.com', NULL, 'Thủ Đức', '3000', '123456789', '0000-00-00 00:00:00', '0'),
(12, 1, 2, 'BMKH09223858', 'a dinh', '05050505', 'male', 'fffffff', NULL, 'adfafaf', '0', '99999999999999999', '0000-00-00 00:00:00', '0'),
(13, 1, 2, 'BMKH09294576', 'a dinh', '05050505', 'male', 'fffffff', NULL, 'adfafaf', '0', '99999999999999999', '0000-00-00 00:00:00', '0'),
(14, 1, 2, 'BMKH09318588', 'a T&acirc;m', '0335506678', 'male', 'tam@gmail.com', NULL, 'Thủ Đức', '0', '123456789', '0000-00-00 00:00:00', '0'),
(20, 1, 2, 'BMKH09319535', 'Xu&acirc;n T&acirc;m,', '039952126', 'male', 'tam@gmail.com', NULL, 'Thủ Đức', '0', '123456789', '0000-00-00 00:00:00', '0'),
(23, 1, 3, 'BMKH09380369', 'Dinh Cảnh', '039933135', 'male', 'dinh@gmail.com', '1995-02-25', 'B&igrave;nh Thạnh', '0', '18001800', '0000-00-00 00:00:00', '0'),
(24, 1, 3, 'BMKH09408506', 'Ngọc Diễm', '0339933723', 'female', 'diem@gmail.com', '1995-02-25', 'Bình Chánh', '0', '17001700', '0000-00-00 00:00:00', '0'),
(25, 1, 2, 'BMKH09745968', 'Ngọc diễm', '090977777', 'male', NULL, NULL, '', '0', NULL, '0000-00-00 00:00:00', '0');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_customer_point`
--

CREATE TABLE `tbl_customer_point` (
  `id` int(11) UNSIGNED NOT NULL,
  `id_business` int(11) UNSIGNED NOT NULL,
  `customer_level` varchar(100) CHARACTER SET utf8 NOT NULL,
  `customer_point` varchar(100) CHARACTER SET utf8 NOT NULL,
  `customer_discount` varchar(100) CHARACTER SET utf8 NOT NULL,
  `customer_description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Đang đổ dữ liệu cho bảng `tbl_customer_point`
--

INSERT INTO `tbl_customer_point` (`id`, `id_business`, `customer_level`, `customer_point`, `customer_discount`, `customer_description`) VALUES
(1, 1, 'Cấp độ vip', '1000', '10', 'aaaaaa'),
(2, 1, 'Cấp độ thường', '500', '15', 'bbbbbb'),
(3, 1, 'Cấp độ premium', '1500', '20', ''),
(4, 1, 'Cấp độ silver', '2000', '25', ''),
(5, 1, 'Cấp độ diamon', '3000', '30', '');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_order_detail`
--

CREATE TABLE `tbl_order_detail` (
  `id` int(11) UNSIGNED NOT NULL,
  `id_order` int(11) UNSIGNED NOT NULL,
  `id_product` int(11) UNSIGNED NOT NULL,
  `detail_extra` varchar(50) NOT NULL,
  `detail_quantity` varchar(100) NOT NULL,
  `detail_cost` varchar(100) CHARACTER SET utf8 NOT NULL,
  `detail_status` enum('Y','N','C') NOT NULL,
  `detail_view` enum('Y','N') NOT NULL DEFAULT 'N',
  `id_business` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Đang đổ dữ liệu cho bảng `tbl_order_detail`
--

INSERT INTO `tbl_order_detail` (`id`, `id_order`, `id_product`, `detail_extra`, `detail_quantity`, `detail_cost`, `detail_status`, `detail_view`, `id_business`) VALUES
(34, 29, 1, '2,3,4', '2', '10000', 'N', 'Y', 1),
(35, 29, 2, '3,2', '3', '50000', 'N', 'Y', 1),
(36, 29, 3, '3,4', '3', '25000', 'Y', 'Y', 1),
(37, 30, 1, '2,3,4', '2', '10000', 'C', 'Y', 1),
(38, 30, 2, '3,2', '3', '50000', 'C', 'Y', 1),
(39, 30, 3, '3,4', '3', '25000', 'C', 'Y', 1),
(40, 31, 1, '2,3,4', '2', '10000', 'N', 'Y', 1),
(41, 31, 2, '3,2', '3', '50000', 'N', 'Y', 1),
(42, 31, 3, '3,4', '3', '25000', 'N', 'Y', 1),
(52, 36, 7, '', '10', '3000', 'N', 'N', 1),
(57, 39, 7, '', '1', '3000', 'Y', 'N', 1),
(59, 39, 6, '', '1', '3000', 'Y', 'N', 1),
(60, 41, 1, '2,3,4', '2', '10000', 'N', 'N', 1),
(61, 41, 1, '3,', '3', '50000', 'N', 'N', 1),
(62, 41, 3, '', '3', '25000', 'N', 'N', 1),
(63, 42, 1, '2,3,4', '2', '10000', 'N', 'N', 1),
(64, 42, 1, '3,', '3', '50000', 'N', 'N', 1),
(65, 42, 3, '', '3', '25000', 'N', 'N', 1),
(66, 39, 1, '2,3,4', '2', '10000', 'N', 'N', 1),
(67, 39, 1, '2,3,4', '2', '10000', 'N', 'N', 1),
(68, 39, 1, '3,', '3', '50000', 'N', 'N', 1),
(69, 39, 1, '3,', '3', '50000', 'N', 'N', 1),
(70, 39, 3, '', '3', '25000', 'N', 'N', 1),
(71, 39, 3, '', '3', '25000', 'N', 'N', 1),
(84, 42, 1, '2,3,4', '2', '10000', 'N', 'N', 1),
(85, 42, 1, '3,2', '3', '50000', 'N', 'N', 1),
(86, 42, 3, '', '3', '25000', 'N', 'N', 1),
(90, 43, 7, '', '3', '3000', 'N', 'N', 1),
(91, 43, 1, '3', '2', '10000', 'N', 'N', 1),
(92, 44, 1, '2,3', '1', '30000', 'N', 'N', 1),
(93, 44, 6, '', '1', '3000', 'N', 'N', 1),
(94, 44, 7, '', '1', '3000', 'N', 'N', 1),
(95, 45, 1, '3', '1', '20000', 'N', 'N', 1),
(96, 45, 7, '', '1', '3000', 'N', 'N', 1),
(97, 45, 6, '', '1', '3000', 'N', 'N', 1),
(98, 46, 1, '2,3', '1', '30000', 'N', 'N', 1),
(99, 47, 1, '2,3', '1', '30000', 'N', 'N', 1),
(100, 48, 2, '', '3', '10000', 'Y', 'N', 1),
(101, 48, 1, '2,3', '2', '30000', 'Y', 'N', 1),
(102, 49, 6, '', '1', '3000', 'N', 'N', 1),
(103, 49, 7, '', '1', '3000', 'N', 'N', 1),
(104, 46, 6, '', '1', '3000', 'N', 'N', 1),
(105, 46, 7, '', '1', '3000', 'N', 'N', 1),
(106, 50, 6, '', '1', '3000', 'N', 'N', 1),
(107, 50, 7, '', '1', '3000', 'N', 'N', 1),
(108, 50, 1, '2,3', '1', '30000', 'N', 'N', 1),
(109, 51, 1, '2', '1', '20000', 'Y', 'N', 1),
(110, 50, 6, '', '1', '3000', 'N', 'N', 1),
(111, 50, 7, '', '1', '3000', 'N', 'N', 1),
(112, 45, 6, '', '1', '3000', 'N', 'N', 1),
(113, 45, 7, '', '1', '3000', 'N', 'N', 1),
(114, 44, 6, '', '1', '3000', 'N', 'N', 1),
(115, 44, 7, '', '1', '3000', 'N', 'N', 1),
(116, 52, 7, '', '1', '3000', 'N', 'N', 1),
(117, 47, 7, '', '2', '3000', 'N', 'N', 1),
(119, 53, 7, '', '1', '3000', 'N', 'N', 1),
(120, 53, 7, '', '3', '3000', 'N', 'N', 1),
(121, 53, 6, '', '1', '3000', 'N', 'N', 1),
(122, 53, 1, '2,3', '1', '30000', 'N', 'N', 1),
(123, 53, 7, '', '1', '3000', 'N', 'N', 1),
(124, 52, 7, '', '1', '3000', 'N', 'N', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_order_log`
--

CREATE TABLE `tbl_order_log` (
  `id` int(11) UNSIGNED NOT NULL,
  `id_order` int(11) UNSIGNED NOT NULL,
  `log_status` varchar(100) NOT NULL,
  `time_log` time NOT NULL,
  `id_business` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Đang đổ dữ liệu cho bảng `tbl_order_log`
--

INSERT INTO `tbl_order_log` (`id`, `id_order`, `log_status`, `time_log`, `id_business`) VALUES
(8, 27, 'waiting', '00:11:21', 1),
(9, 27, 'processing', '00:10:39', 1),
(10, 27, 'delivery', '00:29:38', 1),
(11, 27, 'payment', '00:17:34', 1),
(12, 29, 'waiting', '00:04:34', 1),
(13, 30, 'waiting', '00:15:32', 1),
(14, 30, 'processing', '00:00:25', 1),
(15, 31, 'waiting', '00:15:51', 1),
(16, 31, 'processing', '00:00:08', 1),
(17, 31, 'delivery', '00:00:07', 1),
(18, 32, 'waiting', '00:00:45', 1),
(19, 32, 'processing', '00:00:10', 1),
(20, 32, 'delivery', '00:00:05', 1),
(21, 32, 'payment', '00:05:01', 1),
(22, 33, 'waiting', '00:00:47', 1),
(23, 33, 'processing', '00:00:07', 1),
(24, 33, 'delivery', '00:00:09', 1),
(25, 33, 'payment', '00:00:14', 1),
(26, 34, 'waiting', '00:02:23', 1),
(27, 34, 'processing', '00:00:31', 1),
(28, 34, 'delivery', '00:00:22', 1),
(29, 34, 'payment', '00:04:01', 1),
(30, 34, 'delivery', '00:08:35', 1),
(31, 34, 'payment', '00:00:31', 1),
(32, 33, 'payment', '00:11:21', 1),
(33, 84, 'payment', '00:06:31', 1),
(34, 84, 'payment', '00:07:53', 1),
(35, 88, 'payment', '00:02:43', 1),
(36, 88, 'payment', '00:00:43', 1),
(37, 29, 'waiting', '00:33:34', 1),
(38, 29, 'processing', '00:00:37', 1),
(39, 29, 'delivery', '00:00:11', 1),
(40, 29, 'payment', '00:00:27', 1),
(41, 29, 'payment', '00:00:51', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_order_order`
--

CREATE TABLE `tbl_order_order` (
  `id` int(11) UNSIGNED NOT NULL,
  `id_business` int(11) UNSIGNED NOT NULL,
  `id_account` int(11) UNSIGNED NOT NULL,
  `id_customer` int(11) UNSIGNED NOT NULL,
  `order_floor` varchar(10) NOT NULL,
  `order_table` varchar(10) NOT NULL,
  `order_code` varchar(100) CHARACTER SET utf8 NOT NULL,
  `order_status` enum('1','2','3','4','5','6') CHARACTER SET utf8 NOT NULL DEFAULT '1' COMMENT '1: Datmon ; 2:Chebien; 3: Lenmon; 4: ThanhToan; 5:HoanTat; 6: Huy',
  `order_direct_discount` varchar(100) CHARACTER SET utf8 NOT NULL,
  `order_total_cost` varchar(100) CHARACTER SET utf8 NOT NULL,
  `order_comment` text CHARACTER SET utf8,
  `order_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `order_check_time` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Đang đổ dữ liệu cho bảng `tbl_order_order`
--

INSERT INTO `tbl_order_order` (`id`, `id_business`, `id_account`, `id_customer`, `order_floor`, `order_table`, `order_code`, `order_status`, `order_direct_discount`, `order_total_cost`, `order_comment`, `order_created`, `order_check_time`) VALUES
(29, 1, 2, 0, 'Tầng 1', 'Bàn 6', 'BMSO09145549', '5', '', '1000000', 'xin chào', '2020-12-28 15:52:29', '1609407163'),
(30, 1, 2, 6, 'Tầng 1', 'Bàn 4', 'BMSO09145984', '5', '', '1000000', NULL, '2020-12-28 15:59:44', '1609146941'),
(31, 1, 2, 5, 'Tầng 1', 'Bàn 7', 'BMSO09146017', '5', '', '1000000', NULL, '2020-12-28 16:00:17', '1609146983'),
(36, 1, 2, 5, 'Tầng 1', 'Bàn 1', 'BMSO09228417', '5', '', '30000', NULL, '2020-12-29 14:53:37', '1609228417'),
(39, 1, 2, 0, 'Tầng 1', 'Bàn 3', 'BMSO09229847', '5', '', '6000', 'xin chào', '2020-12-29 15:17:27', '1609229847'),
(41, 1, 2, 0, 'Tầng 2', 'Bàn 4', 'BMSO09726991', '5', '', '1000000', NULL, '2021-01-04 09:23:11', '1609726991'),
(42, 1, 2, 0, 'Tầng 2', 'Bàn 4', 'BMSO09727928', '1', '', '1000000', NULL, '2021-01-04 09:38:48', '1609727928'),
(43, 1, 2, 1, 'Mang đi', 'Order 1', 'BMSO09730974', '2', '', '40000', 'Không lấy hành ngò', '2021-01-04 10:29:34', '1609730974'),
(44, 1, 2, 0, 'Tầng 1', 'Bàn 1', 'BMSO09734038', '1', '', '', NULL, '2021-01-04 11:20:38', '1609734038'),
(45, 1, 2, 0, 'Tầng 1', 'Bàn 2', 'BMSO09734485', '1', '', '', NULL, '2021-01-04 11:28:05', '1609734485'),
(46, 1, 2, 0, 'Tầng 1', 'Bàn 4', 'BMSO09734644', '1', '', '', NULL, '2021-01-04 11:30:44', '1609734644'),
(47, 1, 2, 0, 'Tầng 1', 'Bàn 3', 'BMSO09734709', '1', '', '', NULL, '2021-01-04 11:31:49', '1609734709'),
(48, 1, 2, 0, 'Mang đi', 'Order 2', 'BMSO09735706', '1', '', '85000', 'Không lấy hành ngò', '2021-01-04 11:48:26', '1609735706'),
(49, 1, 2, 0, 'Tầng 1', 'Bàn 4', 'BMSO09742629', '1', '', '', NULL, '2021-01-04 13:43:49', '1609742629'),
(50, 1, 2, 0, 'Tầng 2', 'Bàn 2', 'BMSO09743307', '1', '', '', NULL, '2021-01-04 13:55:07', '1609743307'),
(51, 1, 2, 0, 'Mang đi', 'Order 3', 'BMSO09743451', '1', '', '18000', NULL, '2021-01-04 13:57:31', '1609743451'),
(52, 1, 2, 0, 'Tầng 2', 'Bàn 1', 'BMSO09744162', '1', '', '', NULL, '2021-01-04 14:09:22', '1609744162'),
(53, 1, 2, 0, 'Tầng 4', 'Bàn 1', 'BMSO09744510', '1', '', '', NULL, '2021-01-04 14:15:10', '1609744510');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_organization_floor`
--

CREATE TABLE `tbl_organization_floor` (
  `id` int(11) UNSIGNED NOT NULL,
  `id_business` int(11) UNSIGNED NOT NULL,
  `floor_priority` int(11) NOT NULL,
  `floor_title` varchar(100) CHARACTER SET utf8 NOT NULL,
  `floor_type` enum('eat-in','carry-out') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Đang đổ dữ liệu cho bảng `tbl_organization_floor`
--

INSERT INTO `tbl_organization_floor` (`id`, `id_business`, `floor_priority`, `floor_title`, `floor_type`) VALUES
(1, 1, 1, 'Mang đi', 'carry-out'),
(2, 1, 2, 'Tầng 1', 'eat-in'),
(3, 1, 3, 'Tầng 2', 'eat-in'),
(4, 1, 4, 'Tầng 3', 'eat-in'),
(8, 1, 5, 'Tầng 4', 'eat-in');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_organization_table`
--

CREATE TABLE `tbl_organization_table` (
  `id` int(11) UNSIGNED NOT NULL,
  `id_floor` int(11) UNSIGNED NOT NULL,
  `table_title` varchar(100) CHARACTER SET utf8 NOT NULL,
  `table_status` enum('empty','full') CHARACTER SET utf8 NOT NULL DEFAULT 'empty',
  `id_business` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Đang đổ dữ liệu cho bảng `tbl_organization_table`
--

INSERT INTO `tbl_organization_table` (`id`, `id_floor`, `table_title`, `table_status`, `id_business`) VALUES
(1, 1, 'Order 1', 'full', 1),
(2, 1, 'Order 2', 'full', 1),
(3, 1, 'Order 3', 'full', 1),
(4, 2, 'Bàn 1', 'full', 1),
(5, 2, 'Bàn 2', 'full', 1),
(6, 2, 'Bàn 3', 'full', 1),
(7, 2, 'Bàn 4', 'full', 1),
(8, 2, 'Bàn 5', 'empty', 1),
(9, 2, 'Bàn 6', 'empty', 1),
(10, 2, 'Bàn 7', 'empty', 1),
(11, 2, 'Bàn 8', 'empty', 1),
(12, 2, 'Bàn 9', 'empty', 1),
(13, 2, 'Bàn 10', 'empty', 1),
(14, 3, 'Bàn 1', 'full', 1),
(15, 3, 'Bàn 2', 'full', 1),
(16, 3, 'Bàn 3', 'empty', 1),
(17, 3, 'Bàn 4', 'full', 1),
(18, 3, 'Bàn 5', 'empty', 1),
(19, 3, 'Bàn 6', 'empty', 1),
(20, 3, 'Bàn 7', 'empty', 1),
(21, 3, 'Bàn 8', 'empty', 1),
(22, 3, 'Bàn 9', 'empty', 1),
(23, 3, 'Bàn 10', 'empty', 1),
(24, 3, 'Bàn 11', 'empty', 1),
(25, 3, 'Bàn 12', 'empty', 1),
(26, 3, 'Bàn 13', 'empty', 1),
(27, 3, 'Bàn 14', 'empty', 1),
(28, 3, 'Bàn 15', 'empty', 1),
(29, 3, 'Bàn 16', 'empty', 1),
(30, 3, 'Bàn 17', 'empty', 1),
(31, 3, 'Bàn 18', 'empty', 1),
(32, 3, 'Bàn 19', 'empty', 1),
(33, 3, 'Bàn 20', 'empty', 1),
(34, 4, 'Bàn 1', 'empty', 1),
(35, 4, 'Bàn 2', 'empty', 1),
(36, 4, 'Bàn 3', 'empty', 1),
(37, 4, 'Bàn 4', 'empty', 1),
(38, 4, 'Bàn 5', 'empty', 1),
(39, 4, 'Bàn 6', 'empty', 1),
(40, 4, 'Bàn 7', 'empty', 1),
(41, 4, 'Bàn 8', 'empty', 1),
(42, 4, 'Bàn 9', 'empty', 1),
(43, 4, 'Bàn 10', 'empty', 1),
(44, 4, 'Bàn 11', 'empty', 1),
(45, 4, 'Bàn 12', 'empty', 1),
(46, 4, 'Bàn 13', 'empty', 1),
(47, 4, 'Bàn 14', 'empty', 1),
(48, 4, 'Bàn 15', 'empty', 1),
(49, 4, 'Bàn 16', 'empty', 1),
(50, 4, 'Bàn 17', 'empty', 1),
(51, 4, 'Bàn 18', 'empty', 1),
(52, 4, 'Bàn 19', 'empty', 1),
(53, 4, 'Bàn 20', 'empty', 1),
(54, 1, 'Order 4', 'empty', 1),
(55, 8, 'Bàn 1', 'full', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_product_category`
--

CREATE TABLE `tbl_product_category` (
  `id` int(11) UNSIGNED NOT NULL,
  `id_business` int(11) UNSIGNED NOT NULL,
  `category_icon` varchar(200) CHARACTER SET utf8 NOT NULL,
  `category_title` varchar(200) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Đang đổ dữ liệu cho bảng `tbl_product_category`
--

INSERT INTO `tbl_product_category` (`id`, `id_business`, `category_icon`, `category_title`) VALUES
(1, 1, 'aaaa', 'Món khai vị'),
(2, 1, 'bbbbb', 'Món chính'),
(3, 1, 'cccc', 'Tráng miệng'),
(4, 1, 'dddd', 'Thức uống');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_product_extra`
--

CREATE TABLE `tbl_product_extra` (
  `id` int(11) UNSIGNED NOT NULL,
  `id_product` int(11) UNSIGNED NOT NULL,
  `id_product_extra` int(11) UNSIGNED NOT NULL,
  `id_business` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Đang đổ dữ liệu cho bảng `tbl_product_extra`
--

INSERT INTO `tbl_product_extra` (`id`, `id_product`, `id_product_extra`, `id_business`) VALUES
(1, 1, 2, 1),
(3, 1, 3, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_product_product`
--

CREATE TABLE `tbl_product_product` (
  `id` int(11) UNSIGNED NOT NULL,
  `id_business` int(11) UNSIGNED NOT NULL,
  `id_category` int(10) UNSIGNED NOT NULL,
  `id_unit` int(10) UNSIGNED NOT NULL,
  `product_img` varchar(500) CHARACTER SET utf8 NOT NULL,
  `product_title` varchar(200) CHARACTER SET utf8 NOT NULL,
  `product_code` varchar(200) CHARACTER SET utf8 NOT NULL,
  `product_sales_price` varchar(200) CHARACTER SET utf8 NOT NULL,
  `product_description` text CHARACTER SET utf8 NOT NULL,
  `product_point` varchar(100) CHARACTER SET utf8 NOT NULL,
  `product_disable` enum('Y','N','','') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `product_sold_out` enum('Y','N','','') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Đang đổ dữ liệu cho bảng `tbl_product_product`
--

INSERT INTO `tbl_product_product` (`id`, `id_business`, `id_category`, `id_unit`, `product_img`, `product_title`, `product_code`, `product_sales_price`, `product_description`, `product_point`, `product_disable`, `product_sold_out`) VALUES
(1, 1, 1, 1, 'images/product_product/post_3.jpg', 'Bánh mì không', 'BMK', '10000', 'bánh mì thơm ngon nhiều tiền nhiều ổ', '70', 'N', 'N'),
(2, 1, 2, 1, 'images/product_product/post_1.jpg', 'Bánh mì chả cá', 'BMCC', '10000', 'bánh mì thơm ngon nhiều tiền nhiều ổ', '80', 'N', 'N'),
(3, 1, 2, 1, 'images/product_product/post_4.jpg', 'Bánh mì thịt bò', 'BMTB', '10000', 'bánh mì thơm ngon nhiều tiền nhiều ổ', '90', 'N', 'Y'),
(4, 1, 3, 1, 'images/product_product/post_1.jpg', 'Chả cá', 'CC', '3000', 'topping rẻ', '20', 'Y', 'N'),
(5, 1, 4, 1, 'images/product_product/post_2.jpg', 'Dưa leo', 'DL', '3000', 'topping rẻ', '20', 'N', 'N'),
(6, 1, 3, 1, 'images/product_product/post_3.jpg', 'Dưa chuôt', 'DC', '3000', 'topping rẻ', '20', 'N', 'N'),
(7, 1, 4, 1, 'images/product_product/post_2.jpg', 'Trứng', 'TR', '3000', 'topping rẻ', '20', 'N', 'N');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_product_unit`
--

CREATE TABLE `tbl_product_unit` (
  `id` int(10) UNSIGNED NOT NULL,
  `unit_title` varchar(200) NOT NULL,
  `unit` varchar(200) NOT NULL,
  `id_business` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `tbl_product_unit`
--

INSERT INTO `tbl_product_unit` (`id`, `unit_title`, `unit`, `id_business`) VALUES
(1, 'Đồng', 'Đ', 1);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `tbl_account_account`
--
ALTER TABLE `tbl_account_account`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_type` (`id_type`,`id_business`);

--
-- Chỉ mục cho bảng `tbl_account_authorize`
--
ALTER TABLE `tbl_account_authorize`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_admin` (`id_admin`);

--
-- Chỉ mục cho bảng `tbl_account_permission`
--
ALTER TABLE `tbl_account_permission`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `tbl_account_type`
--
ALTER TABLE `tbl_account_type`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `tbl_app_deploy`
--
ALTER TABLE `tbl_app_deploy`
  ADD PRIMARY KEY (`id`),
  ADD KEY `live_version` (`live_version`);

--
-- Chỉ mục cho bảng `tbl_business_model`
--
ALTER TABLE `tbl_business_model`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `tbl_business_store`
--
ALTER TABLE `tbl_business_store`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `tbl_customer_customer`
--
ALTER TABLE `tbl_customer_customer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_business` (`id_business`,`id_account`);

--
-- Chỉ mục cho bảng `tbl_customer_point`
--
ALTER TABLE `tbl_customer_point`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_business` (`id_business`);

--
-- Chỉ mục cho bảng `tbl_order_detail`
--
ALTER TABLE `tbl_order_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_order` (`id_order`,`id_product`);

--
-- Chỉ mục cho bảng `tbl_order_log`
--
ALTER TABLE `tbl_order_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_order` (`id_order`);

--
-- Chỉ mục cho bảng `tbl_order_order`
--
ALTER TABLE `tbl_order_order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_business` (`id_business`,`id_account`,`id_customer`);

--
-- Chỉ mục cho bảng `tbl_organization_floor`
--
ALTER TABLE `tbl_organization_floor`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_business` (`id_business`);

--
-- Chỉ mục cho bảng `tbl_organization_table`
--
ALTER TABLE `tbl_organization_table`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_floor` (`id_floor`);

--
-- Chỉ mục cho bảng `tbl_product_category`
--
ALTER TABLE `tbl_product_category`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_business` (`id_business`);

--
-- Chỉ mục cho bảng `tbl_product_extra`
--
ALTER TABLE `tbl_product_extra`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_product` (`id_product`,`id_product_extra`);

--
-- Chỉ mục cho bảng `tbl_product_product`
--
ALTER TABLE `tbl_product_product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_business` (`id_business`);

--
-- Chỉ mục cho bảng `tbl_product_unit`
--
ALTER TABLE `tbl_product_unit`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `tbl_account_account`
--
ALTER TABLE `tbl_account_account`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `tbl_account_authorize`
--
ALTER TABLE `tbl_account_authorize`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `tbl_account_permission`
--
ALTER TABLE `tbl_account_permission`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `tbl_account_type`
--
ALTER TABLE `tbl_account_type`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `tbl_app_deploy`
--
ALTER TABLE `tbl_app_deploy`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `tbl_business_model`
--
ALTER TABLE `tbl_business_model`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `tbl_business_store`
--
ALTER TABLE `tbl_business_store`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `tbl_customer_customer`
--
ALTER TABLE `tbl_customer_customer`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT cho bảng `tbl_customer_point`
--
ALTER TABLE `tbl_customer_point`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `tbl_order_detail`
--
ALTER TABLE `tbl_order_detail`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=125;

--
-- AUTO_INCREMENT cho bảng `tbl_order_log`
--
ALTER TABLE `tbl_order_log`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT cho bảng `tbl_order_order`
--
ALTER TABLE `tbl_order_order`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT cho bảng `tbl_organization_floor`
--
ALTER TABLE `tbl_organization_floor`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `tbl_organization_table`
--
ALTER TABLE `tbl_organization_table`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT cho bảng `tbl_product_category`
--
ALTER TABLE `tbl_product_category`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `tbl_product_extra`
--
ALTER TABLE `tbl_product_extra`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `tbl_product_product`
--
ALTER TABLE `tbl_product_product`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
