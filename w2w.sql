-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 16, 2026 at 10:54 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `recypoints`
--

-- --------------------------------------------------------

--
-- Table structure for table `basic_site`
--

CREATE TABLE `basic_site` (
  `site_id` int(11) NOT NULL,
  `site_name` varchar(255) DEFAULT NULL,
  `logo` text DEFAULT NULL,
  `title_1` text DEFAULT NULL,
  `about` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `basic_site`
--

INSERT INTO `basic_site` (`site_id`, `site_name`, `logo`, `title_1`, `about`) VALUES
(1, 'Waste2Win', 'logo.png', 'حوّل نفاياتك إلى مكافآت', 'waste2win هي منصة لإعادة التدوير تمنح نقاطًا ومكافآت للمستخدمين.');

-- --------------------------------------------------------

--
-- Table structure for table `citys`
--

CREATE TABLE `citys` (
  `cityid` int(11) NOT NULL,
  `cityname` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `citys`
--

INSERT INTO `citys` (`cityid`, `cityname`) VALUES
(1, 'دمياط'),
(2, 'طنطا'),
(3, 'المنصوره');

-- --------------------------------------------------------

--
-- Table structure for table `communication`
--

CREATE TABLE `communication` (
  `comid` int(11) NOT NULL,
  `email` text NOT NULL,
  `phone` varchar(255) NOT NULL,
  `location` text NOT NULL,
  `email_support` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `communication`
--

INSERT INTO `communication` (`comid`, `email`, `phone`, `location`, `email_support`) VALUES
(1, '--', '01230284784', '--', '--');

-- --------------------------------------------------------

--
-- Table structure for table `communication_links`
--

CREATE TABLE `communication_links` (
  `link_id` int(11) NOT NULL,
  `facebook` text NOT NULL,
  `insta` text NOT NULL,
  `twitter` text NOT NULL,
  `linkedin` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `communication_links`
--

INSERT INTO `communication_links` (`link_id`, `facebook`, `insta`, `twitter`, `linkedin`) VALUES
(1, 'face', '--', '--', '--');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `custid` int(11) NOT NULL,
  `cust_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `cityid` int(11) NOT NULL,
  `points` int(11) NOT NULL DEFAULT 0,
  `status_account` int(11) DEFAULT 2,
  `registration_date` date NOT NULL DEFAULT curdate(),
  `role` int(11) DEFAULT 3
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`custid`, `cust_name`, `email`, `password`, `phone`, `cityid`, `points`, `status_account`, `registration_date`, `role`) VALUES
(1, 'omar', 'omar@gmail.com', '03d32ced79eb05fbf8498bce991d9cd0', '106732434', 1, 10000, 2, '2025-11-04', 3),
(2, 'mohamed', 'mohamed@gmail.com', '1e2a796539042ca860c3091662aa4346', '0109287433', 2, 150, 1, '2025-11-02', 3),
(5, 'krk', 'krk@gmail.com', '1d430d0a0757ca4b16a57dbc5c436353', '0101231231', 1, 550, 2, '2025-10-29', 3);

-- --------------------------------------------------------

--
-- Table structure for table `order_after_process`
--

CREATE TABLE `order_after_process` (
  `infoid` int(11) NOT NULL,
  `orderid` int(11) DEFAULT NULL,
  `img` varchar(255) DEFAULT NULL,
  `quantity` varchar(255) DEFAULT NULL,
  `wasteid` int(11) DEFAULT NULL,
  `total_points` int(11) DEFAULT NULL,
  `order_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_after_process`
--

INSERT INTO `order_after_process` (`infoid`, `orderid`, `img`, `quantity`, `wasteid`, `total_points`, `order_date`) VALUES
(1, 15, '1768952934_WhatsApp Image 2026-01-18 at 9.52.35 PM.jpeg', '3', 2, 180, '2026-01-21'),
(2, 5, '1768953149_pngtree-recycling-of-plastic-bottles-image_13227286.jpg', '5', 1, 250, '2026-01-21'),
(3, 6, '1768985192_WhatsApp Image 2026-01-18 at 9.52.35 PM.jpeg', '1', 5, 80, '2026-01-21');

-- --------------------------------------------------------

--
-- Table structure for table `order_info`
--

CREATE TABLE `order_info` (
  `infoid` int(11) NOT NULL,
  `recyid` int(11) DEFAULT NULL,
  `img` text DEFAULT NULL,
  `points` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_info`
--

INSERT INTO `order_info` (`infoid`, `recyid`, `img`, `points`) VALUES
(1, 23, '1771204472_team 404.png', NULL),
(2, 23, '1771204472_images (1).png', NULL),
(3, 25, NULL, 150);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `proid` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `quantity` int(11) NOT NULL,
  `type_of_pro` int(11) NOT NULL,
  `points` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`proid`, `title`, `comment`, `quantity`, `type_of_pro`, `points`, `status`) VALUES
(1, 'Recycled Fabric Tote Bag', 'حقيبة أنيقة مصنوعة من خامات معاد تدويرها للاستخدام اليومي', 20, 2, 200, 1),
(2, 'Beauty of Joseon Glow Serum', 'سيروم لتفتيح البشرة وتقليل التصبغات', 10, 1, 550, 1),
(3, 'Medicube Collagen Night Wrapping Mask', 'ماسك ليل لترطيب وشد البشرة أثناء النوم', 5, 1, 490, 1),
(4, 'Peace Lily', 'أوراق خضراء وزهور بيضاء، مثالي للمنزل أو المكتب', 20, 3, 300, 1),
(5, 'Natural Soy Candle', 'شمعة يدوية الصنع بعطور طبيعية وبدون تغليف بلاستيك', 15, 2, 250, 1),
(6, 'Reusable Coffee Cup', 'استمتع بقهوتك دون أكواب الاستخدام الواحد', 10, 2, 150, 1),
(7, 'Recycled Glass Vase', 'مزهرية أنيقة مصنوعة من زجاجات معاد استخدامها', 5, 2, 300, 2),
(8, 'Recycled Blanket Throw', 'بطانية ناعمة ودافئة مصنوعة من قطن معاد الاستخدام', 5, 2, 250, 2),
(9, 'Reusable Bamboo Hair Brush', 'فرشاة شعر من الخيزران — تقلل من الكهرباء الساكنة وتعتني بالشعر بلطف', 10, 1, 360, 1),
(10, 'Organic Lip Balm', 'مرطب شفاه طبيعي — يحافظ على ترطيب الشفاه طوال اليوم', 20, 1, 200, 1),
(11, 'Reclaimed Wood Plant Holder', 'حامل نباتات خشبي بتصميم أنيق', 15, 2, 190, 1),
(12, 'Bamboo Toothbrush', 'صديقة للبيئة وتقلل من النفايات البلاستيكية', 15, 1, 180, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pro_imgs`
--

CREATE TABLE `pro_imgs` (
  `imgid` int(11) NOT NULL,
  `proid` int(11) DEFAULT NULL,
  `img` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pro_imgs`
--

INSERT INTO `pro_imgs` (`imgid`, `proid`, `img`) VALUES
(1, 1, 'tote bag.jpeg'),
(2, 2, 'Beauty of Joseon.jpeg'),
(3, 3, 'Medicube Collagen mask.jpeg'),
(4, 4, 'Peace Lily.jpeg'),
(5, 5, 'soy candle.jpeg'),
(6, 6, 'reusable coffee cup.jpeg'),
(7, 7, 'Recycled Glass Vase.jpeg'),
(8, 8, 'recycled blanket.jpeg'),
(9, 9, 'bamboo brushes.jpeg'),
(10, 10, 'organic lip balm.jpeg'),
(11, 11, 'wood plant holder.jpeg'),
(12, 12, 'bamboo toothbrush.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `recy_order`
--

CREATE TABLE `recy_order` (
  `recyid` int(11) NOT NULL,
  `custid` int(11) DEFAULT NULL,
  `cityid` int(11) DEFAULT NULL,
  `location` text DEFAULT NULL,
  `statusid` int(11) DEFAULT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `workerid` int(11) DEFAULT NULL,
  `comment_rej` text DEFAULT NULL,
  `total_points` int(11) DEFAULT NULL,
  `type_of_order` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recy_order`
--

INSERT INTO `recy_order` (`recyid`, `custid`, `cityid`, `location`, `statusid`, `date`, `workerid`, `comment_rej`, `total_points`, `type_of_order`) VALUES
(23, 1, 1, NULL, 1, '2026-02-16 03:14:32', NULL, NULL, NULL, 1),
(25, 1, 3, 'kln', 1, '2026-02-16 02:19:41', NULL, NULL, 150, 2);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `roleid` int(11) NOT NULL,
  `role_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`roleid`, `role_name`) VALUES
(1, 'admin'),
(2, 'worker'),
(3, 'customer');

-- --------------------------------------------------------

--
-- Table structure for table `status_of_account`
--

CREATE TABLE `status_of_account` (
  `statusid` int(11) NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `status_of_account`
--

INSERT INTO `status_of_account` (`statusid`, `status`) VALUES
(1, 'محظور'),
(2, 'نشط');

-- --------------------------------------------------------

--
-- Table structure for table `status_of_pro`
--

CREATE TABLE `status_of_pro` (
  `statusid` int(11) NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `status_of_pro`
--

INSERT INTO `status_of_pro` (`statusid`, `status`) VALUES
(1, 'Stock'),
(2, 'Out Of Stock');

-- --------------------------------------------------------

--
-- Table structure for table `status_of_recy_order`
--

CREATE TABLE `status_of_recy_order` (
  `statusid` int(11) NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `status_of_recy_order`
--

INSERT INTO `status_of_recy_order` (`statusid`, `status`) VALUES
(1, 'قيد الانتظار'),
(2, 'تمت الموافقه'),
(3, 'تم الرفض'),
(4, 'تم انتهاء بنجاح');

-- --------------------------------------------------------

--
-- Table structure for table `status_of_worker`
--

CREATE TABLE `status_of_worker` (
  `statusid` int(11) NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `status_of_worker`
--

INSERT INTO `status_of_worker` (`statusid`, `status`) VALUES
(1, 'نشط'),
(2, 'محظور'),
(3, 'اجازه'),
(4, 'تحت التجربة');

-- --------------------------------------------------------

--
-- Table structure for table `type_of_order`
--

CREATE TABLE `type_of_order` (
  `type_id` int(11) NOT NULL,
  `type_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `type_of_order`
--

INSERT INTO `type_of_order` (`type_id`, `type_name`) VALUES
(1, 'Recycle'),
(2, 'Exchange');

-- --------------------------------------------------------

--
-- Table structure for table `type_of_pro`
--

CREATE TABLE `type_of_pro` (
  `typeid` int(11) NOT NULL,
  `type_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `type_of_pro`
--

INSERT INTO `type_of_pro` (`typeid`, `type_name`) VALUES
(1, 'Personal Care'),
(2, 'Lifestyle'),
(3, 'Garden\r\n'),
(4, 'Electronics');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userid` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `role` int(11) NOT NULL,
  `salary` int(11) NOT NULL,
  `city_work` int(11) DEFAULT NULL,
  `statusid` int(11) DEFAULT NULL,
  `rank` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userid`, `user_name`, `email`, `password`, `phone`, `role`, `salary`, `city_work`, `statusid`, `rank`) VALUES
(1, 'roaa', 'roaa@gmail.com', '1dc90e80c77fe245a82ea7ed30d1f849', '01111111111', 1, 4000, 1, 4, 5),
(2, 'abdo', 'abdo@gmail.com', '80c9ef0fb86369cd25f90af27ef53a9e', '0102987342', 2, 3500, 2, 3, 4.8),
(3, 'daii', 'daii@gmail.com', '7097c422d46bb61fc4c169dbbae1c1e6', '0129923353', 2, 3000, 3, 1, 4.2),
(4, 'mohamed', 'mo@gmail.com', '1e2a796539042ca860c3091662aa4346', '010892876', 1, 2773, 3, 1, 4),
(8, 'سعد سمير', 'saad@gmail.com', '7812e8b74f6837fba66f86fe86688a2b', '0199999333', 2, 4000, 2, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `wastes`
--

CREATE TABLE `wastes` (
  `wasteid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `points` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wastes`
--

INSERT INTO `wastes` (`wasteid`, `name`, `points`) VALUES
(1, 'بلاستيك', 1),
(2, 'ورق', 5),
(3, 'معدن', 2),
(5, 'زجاج', 10);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `basic_site`
--
ALTER TABLE `basic_site`
  ADD PRIMARY KEY (`site_id`);

--
-- Indexes for table `citys`
--
ALTER TABLE `citys`
  ADD PRIMARY KEY (`cityid`);

--
-- Indexes for table `communication`
--
ALTER TABLE `communication`
  ADD PRIMARY KEY (`comid`);

--
-- Indexes for table `communication_links`
--
ALTER TABLE `communication_links`
  ADD PRIMARY KEY (`link_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`custid`),
  ADD KEY `status_acc_fk` (`status_account`),
  ADD KEY `cityid_fk` (`cityid`);

--
-- Indexes for table `order_after_process`
--
ALTER TABLE `order_after_process`
  ADD PRIMARY KEY (`infoid`);

--
-- Indexes for table `order_info`
--
ALTER TABLE `order_info`
  ADD PRIMARY KEY (`infoid`),
  ADD KEY `orderinfoid_fk` (`recyid`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`proid`),
  ADD KEY `statusid_fk` (`status`),
  ADD KEY `typeid_fk` (`type_of_pro`);

--
-- Indexes for table `pro_imgs`
--
ALTER TABLE `pro_imgs`
  ADD PRIMARY KEY (`imgid`),
  ADD KEY `proid_fk` (`proid`);

--
-- Indexes for table `recy_order`
--
ALTER TABLE `recy_order`
  ADD PRIMARY KEY (`recyid`),
  ADD KEY `statusrecyid_fk` (`statusid`),
  ADD KEY `custid_fk` (`custid`),
  ADD KEY `cityidor_fk` (`cityid`),
  ADD KEY `workerid_fk` (`workerid`),
  ADD KEY `type_of_or_fk` (`type_of_order`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`roleid`);

--
-- Indexes for table `status_of_account`
--
ALTER TABLE `status_of_account`
  ADD PRIMARY KEY (`statusid`);

--
-- Indexes for table `status_of_pro`
--
ALTER TABLE `status_of_pro`
  ADD PRIMARY KEY (`statusid`);

--
-- Indexes for table `status_of_recy_order`
--
ALTER TABLE `status_of_recy_order`
  ADD PRIMARY KEY (`statusid`);

--
-- Indexes for table `status_of_worker`
--
ALTER TABLE `status_of_worker`
  ADD PRIMARY KEY (`statusid`);

--
-- Indexes for table `type_of_order`
--
ALTER TABLE `type_of_order`
  ADD PRIMARY KEY (`type_id`);

--
-- Indexes for table `type_of_pro`
--
ALTER TABLE `type_of_pro`
  ADD PRIMARY KEY (`typeid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userid`),
  ADD KEY `roleid_fk` (`role`),
  ADD KEY `citywork_fk` (`city_work`),
  ADD KEY `statuswork_fk` (`statusid`);

--
-- Indexes for table `wastes`
--
ALTER TABLE `wastes`
  ADD PRIMARY KEY (`wasteid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `basic_site`
--
ALTER TABLE `basic_site`
  MODIFY `site_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `citys`
--
ALTER TABLE `citys`
  MODIFY `cityid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `communication`
--
ALTER TABLE `communication`
  MODIFY `comid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `communication_links`
--
ALTER TABLE `communication_links`
  MODIFY `link_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `custid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `order_after_process`
--
ALTER TABLE `order_after_process`
  MODIFY `infoid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `order_info`
--
ALTER TABLE `order_info`
  MODIFY `infoid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `proid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `pro_imgs`
--
ALTER TABLE `pro_imgs`
  MODIFY `imgid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `recy_order`
--
ALTER TABLE `recy_order`
  MODIFY `recyid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `roleid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `status_of_account`
--
ALTER TABLE `status_of_account`
  MODIFY `statusid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `status_of_pro`
--
ALTER TABLE `status_of_pro`
  MODIFY `statusid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `status_of_recy_order`
--
ALTER TABLE `status_of_recy_order`
  MODIFY `statusid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `status_of_worker`
--
ALTER TABLE `status_of_worker`
  MODIFY `statusid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `type_of_order`
--
ALTER TABLE `type_of_order`
  MODIFY `type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `type_of_pro`
--
ALTER TABLE `type_of_pro`
  MODIFY `typeid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `wastes`
--
ALTER TABLE `wastes`
  MODIFY `wasteid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `cityid_fk` FOREIGN KEY (`cityid`) REFERENCES `citys` (`cityid`),
  ADD CONSTRAINT `status_acc_fk` FOREIGN KEY (`status_account`) REFERENCES `status_of_account` (`statusid`);

--
-- Constraints for table `order_info`
--
ALTER TABLE `order_info`
  ADD CONSTRAINT `orderinfoid_fk` FOREIGN KEY (`recyid`) REFERENCES `recy_order` (`recyid`) ON DELETE SET NULL;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `statusid_fk` FOREIGN KEY (`status`) REFERENCES `status_of_pro` (`statusid`),
  ADD CONSTRAINT `typeid_fk` FOREIGN KEY (`type_of_pro`) REFERENCES `type_of_pro` (`typeid`);

--
-- Constraints for table `pro_imgs`
--
ALTER TABLE `pro_imgs`
  ADD CONSTRAINT `proid_fk` FOREIGN KEY (`proid`) REFERENCES `products` (`proid`);

--
-- Constraints for table `recy_order`
--
ALTER TABLE `recy_order`
  ADD CONSTRAINT `cityidor_fk` FOREIGN KEY (`cityid`) REFERENCES `citys` (`cityid`),
  ADD CONSTRAINT `custid_fk` FOREIGN KEY (`custid`) REFERENCES `customers` (`custid`),
  ADD CONSTRAINT `statusrecyid_fk` FOREIGN KEY (`statusid`) REFERENCES `status_of_recy_order` (`statusid`),
  ADD CONSTRAINT `type_of_or_fk` FOREIGN KEY (`type_of_order`) REFERENCES `type_of_order` (`type_id`),
  ADD CONSTRAINT `workerid_fk` FOREIGN KEY (`workerid`) REFERENCES `users` (`userid`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `citywork_fk` FOREIGN KEY (`city_work`) REFERENCES `citys` (`cityid`),
  ADD CONSTRAINT `roleid_fk` FOREIGN KEY (`role`) REFERENCES `roles` (`roleid`),
  ADD CONSTRAINT `statuswork_fk` FOREIGN KEY (`statusid`) REFERENCES `status_of_worker` (`statusid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
