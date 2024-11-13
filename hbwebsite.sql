-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 13, 2024 lúc 05:38 AM
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
-- Cơ sở dữ liệu: `hbwebsite`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `admin`
--

CREATE TABLE `admin` (
  `sr_no` int(11) NOT NULL,
  `ad_name` varchar(150) NOT NULL,
  `ad_pass` varchar(150) NOT NULL,
  `c_vu` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `admin`
--

INSERT INTO `admin` (`sr_no`, `ad_name`, `ad_pass`, `c_vu`) VALUES
(1, 'admin', '123', 'giám đốc'),
(2, 'bao', '123', 'nhân viên');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `d_trung`
--

CREATE TABLE `d_trung` (
  `id` int(11) NOT NULL,
  `ten` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `d_trung`
--

INSERT INTO `d_trung` (`id`, `ten`) VALUES
(5, 'Bể bơi vô cực'),
(7, 'Hai giường đôi'),
(8, 'Hai giường đơn'),
(9, 'Một giường đôi'),
(10, 'Một giường đơn'),
(11, 'Sân tennis');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phong`
--

CREATE TABLE `phong` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `area` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `soluong` int(11) NOT NULL,
  `adult` int(11) NOT NULL,
  `children` int(11) NOT NULL,
  `mo_ta` varchar(350) NOT NULL,
  `tr_thai` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `phong`
--

INSERT INTO `phong` (`id`, `name`, `area`, `price`, `soluong`, `adult`, `children`, `mo_ta`, `tr_thai`) VALUES
(9, 'Phòng Vip', 1, 12500000, 1, 4, 2, 'okla', 0),
(10, 'Phòng tổng thống', 2, 25000000, 2, 6, 2, 'ngon', 0),
(12, 'Phòng phổng thông', 2, 10000000, 1, 2, 1, 'là dị đó', 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phong_d_trung`
--

CREATE TABLE `phong_d_trung` (
  `sr_no` int(11) NOT NULL,
  `phong_id` int(11) NOT NULL,
  `d_trung_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phong_image`
--

CREATE TABLE `phong_image` (
  `sr_no` int(11) NOT NULL,
  `phong_id` int(11) NOT NULL,
  `image` varchar(150) NOT NULL,
  `thumb` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phong_tien_tich`
--

CREATE TABLE `phong_tien_tich` (
  `sr_no` int(11) NOT NULL,
  `phong_id` int(11) NOT NULL,
  `tien_ich_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tien_ich`
--

CREATE TABLE `tien_ich` (
  `id` int(11) NOT NULL,
  `icon` varchar(100) NOT NULL,
  `ten` varchar(50) NOT NULL,
  `mo_ta` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tien_ich`
--

INSERT INTO `tien_ich` (`id`, `icon`, `ten`, `mo_ta`) VALUES
(6, 'IMG_63358.svg', 'TV', ''),
(7, 'IMG_41869.svg', 'Air-conditioner', ''),
(8, 'IMG_94095.svg', 'Radio', ''),
(9, 'IMG_36249.svg', 'Spa', '');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`sr_no`);

--
-- Chỉ mục cho bảng `d_trung`
--
ALTER TABLE `d_trung`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `phong`
--
ALTER TABLE `phong`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `phong_d_trung`
--
ALTER TABLE `phong_d_trung`
  ADD PRIMARY KEY (`sr_no`),
  ADD KEY `phong_id` (`phong_id`),
  ADD KEY `d_trung id` (`d_trung_id`);

--
-- Chỉ mục cho bảng `phong_image`
--
ALTER TABLE `phong_image`
  ADD PRIMARY KEY (`sr_no`),
  ADD KEY `phong_id` (`phong_id`);

--
-- Chỉ mục cho bảng `phong_tien_tich`
--
ALTER TABLE `phong_tien_tich`
  ADD PRIMARY KEY (`sr_no`),
  ADD KEY `room id` (`phong_id`),
  ADD KEY `facilities id` (`tien_ich_id`);

--
-- Chỉ mục cho bảng `tien_ich`
--
ALTER TABLE `tien_ich`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `admin`
--
ALTER TABLE `admin`
  MODIFY `sr_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `d_trung`
--
ALTER TABLE `d_trung`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `phong`
--
ALTER TABLE `phong`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT cho bảng `phong_d_trung`
--
ALTER TABLE `phong_d_trung`
  MODIFY `sr_no` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `phong_image`
--
ALTER TABLE `phong_image`
  MODIFY `sr_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT cho bảng `phong_tien_tich`
--
ALTER TABLE `phong_tien_tich`
  MODIFY `sr_no` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `tien_ich`
--
ALTER TABLE `tien_ich`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `phong_d_trung`
--
ALTER TABLE `phong_d_trung`
  ADD CONSTRAINT `d_trung id` FOREIGN KEY (`d_trung_id`) REFERENCES `d_trung` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `phong_id` FOREIGN KEY (`phong_id`) REFERENCES `phong` (`id`) ON UPDATE NO ACTION;

--
-- Các ràng buộc cho bảng `phong_image`
--
ALTER TABLE `phong_image`
  ADD CONSTRAINT `room_images_ibfk_1` FOREIGN KEY (`phong_id`) REFERENCES `phong` (`id`);

--
-- Các ràng buộc cho bảng `phong_tien_tich`
--
ALTER TABLE `phong_tien_tich`
  ADD CONSTRAINT `facilities id` FOREIGN KEY (`tien_ich_id`) REFERENCES `tien_ich` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `room id` FOREIGN KEY (`phong_id`) REFERENCES `phong` (`id`) ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
