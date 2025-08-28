-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th8 27, 2025 lúc 10:50 AM
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
-- Cơ sở dữ liệu: `ql_hocsinh`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `diem`
--

CREATE TABLE `diem` (
  `id` int(11) NOT NULL,
  `id_hocsinh` int(11) DEFAULT NULL,
  `namhoc` varchar(10) DEFAULT NULL,
  `hocky` int(11) DEFAULT NULL,
  `toan` float DEFAULT NULL,
  `van` float DEFAULT NULL,
  `anh` float DEFAULT NULL,
  `ly` float DEFAULT NULL,
  `hoa` float DEFAULT NULL,
  `sinh` float DEFAULT NULL,
  `su` float DEFAULT NULL,
  `dia` float DEFAULT NULL,
  `gdcd` float DEFAULT NULL,
  `cn` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `diem`
--

INSERT INTO `diem` (`id`, `id_hocsinh`, `namhoc`, `hocky`, `toan`, `van`, `anh`, `ly`, `hoa`, `sinh`, `su`, `dia`, `gdcd`, `cn`) VALUES
(1, 1, '2025', 2, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10),
(3, 8, '2023', 1, 5, 4.9, 5, 5, 5, 5, 5, 5, 5, 5),
(4, 8, '2023', 2, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6),
(5, 8, '2025', 1, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hocsinh`
--

CREATE TABLE `hocsinh` (
  `id` int(11) NOT NULL,
  `ten` varchar(100) NOT NULL,
  `ngaysinh` date DEFAULT NULL,
  `gioitinh` varchar(10) DEFAULT NULL,
  `lop` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hocsinh`
--

INSERT INTO `hocsinh` (`id`, `ten`, `ngaysinh`, `gioitinh`, `lop`) VALUES
(1, 'Long', '2025-08-13', 'Nam', '10A1'),
(5, 'bcd', '2024-03-04', 'Nam', '10A3'),
(7, 'An', '2020-03-27', 'Nam', '11A1'),
(8, 'Mai', '2025-07-31', 'Nữ', '11A1');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `diem`
--
ALTER TABLE `diem`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_hocsinh` (`id_hocsinh`);

--
-- Chỉ mục cho bảng `hocsinh`
--
ALTER TABLE `hocsinh`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `diem`
--
ALTER TABLE `diem`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `hocsinh`
--
ALTER TABLE `hocsinh`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `diem`
--
ALTER TABLE `diem`
  ADD CONSTRAINT `diem_ibfk_1` FOREIGN KEY (`id_hocsinh`) REFERENCES `hocsinh` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
