-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th12 17, 2024 lúc 05:54 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `gymmanagement`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `eRole` enum('ADMIN','TRAINER','MEMBER') NOT NULL DEFAULT 'ADMIN',
  `status` enum('ACTIVE','INACTIVE') NOT NULL DEFAULT 'ACTIVE',
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `type` enum('NEWS','PROMOTION','MAINTENANCE','OTHER') NOT NULL,
  `startDate` datetime NOT NULL,
  `endDate` datetime DEFAULT NULL,
  `status` enum('DRAFT','PUBLISHED','ARCHIVED') NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `check_ins`
--

CREATE TABLE `check_ins` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `checkInTime` datetime NOT NULL,
  `checkOutTime` datetime DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `equipment`
--

CREATE TABLE `equipment` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `purchaseDate` date NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `status` enum('ACTIVE','MAINTENANCE','BROKEN','RETIRED') NOT NULL,
  `lastMaintenanceDate` date DEFAULT NULL,
  `nextMaintenanceDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `equipment_images`
--

CREATE TABLE `equipment_images` (
  `id` int(11) NOT NULL,
  `equipment_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 999,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `maintenance_logs`
--

CREATE TABLE `maintenance_logs` (
  `id` int(11) NOT NULL,
  `equipmentId` int(11) NOT NULL,
  `maintenanceDate` date NOT NULL,
  `description` text NOT NULL,
  `cost` decimal(15,2) DEFAULT NULL,
  `performedBy` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `membership_packages`
--

CREATE TABLE `membership_packages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `duration` int(11) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `maxFreeze` int(11) DEFAULT 0,
  `benefits` text NOT NULL,
  `status` enum('ACTIVE','INACTIVE') NOT NULL DEFAULT 'ACTIVE',
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `membership_registrations`
--

CREATE TABLE `membership_registrations` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `packageId` int(11) NOT NULL,
  `startDate` date NOT NULL,
  `endDate` date NOT NULL,
  `freezeCount` int(11) DEFAULT 0,
  `freezeDays` int(11) DEFAULT 0,
  `status` enum('ACTIVE','EXPIRED','FROZEN','CANCELLED') NOT NULL DEFAULT 'ACTIVE',
  `paymentId` int(11) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Bẫy `membership_registrations`
--
DELIMITER $$
CREATE TRIGGER `after_membership_registration_update` AFTER UPDATE ON `membership_registrations` FOR EACH ROW BEGIN
    IF NEW.status != OLD.status THEN
        UPDATE users 
        SET membershipStatus = CASE
            WHEN NEW.status = 'ACTIVE' THEN 'ACTIVE'
            WHEN NEW.status = 'FROZEN' THEN 'SUSPENDED'
            ELSE 'EXPIRED'
        END
        WHERE id = NEW.userId;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Cấu trúc đóng vai cho view `membership_revenue_report`
-- (See below for the actual view)
--
CREATE TABLE `membership_revenue_report` (
`month_year` varchar(7)
,`package_name` varchar(255)
,`total_registrations` bigint(21)
,`total_revenue` decimal(37,2)
);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `paymentMethod` enum('CASH_ON_DELIVERY','QR_TRANSFER') NOT NULL,
  `qrImage` varchar(255) DEFAULT NULL,
  `refNo` varchar(255) DEFAULT NULL,
  `paymentStatus` enum('PENDING','COMPLETED','FAILED','REFUNDED') NOT NULL DEFAULT 'PENDING',
  `paymentDate` datetime DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `progress_tracking`
--

CREATE TABLE `progress_tracking` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `weight` decimal(5,2) NOT NULL,
  `height` decimal(5,2) NOT NULL,
  `bodyFat` decimal(5,2) DEFAULT NULL,
  `muscle` decimal(5,2) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `measurementDate` date NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `promotions`
--

CREATE TABLE `promotions` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `discountType` enum('PERCENTAGE','FIXED_AMOUNT') NOT NULL,
  `discountValue` decimal(15,2) NOT NULL,
  `startDate` date NOT NULL,
  `endDate` date NOT NULL,
  `applicableType` enum('MEMBERSHIP','PT','ALL') NOT NULL,
  `status` enum('ACTIVE','INACTIVE') NOT NULL DEFAULT 'ACTIVE'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `pt_packages`
--

CREATE TABLE `pt_packages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `sessions` int(11) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `validity` int(11) NOT NULL,
  `status` enum('ACTIVE','INACTIVE') NOT NULL DEFAULT 'ACTIVE',
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `pt_registrations`
--

CREATE TABLE `pt_registrations` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `trainerId` int(11) NOT NULL,
  `packageId` int(11) NOT NULL,
  `startDate` date NOT NULL,
  `endDate` date NOT NULL,
  `remainingSessions` int(11) NOT NULL,
  `status` enum('ACTIVE','COMPLETED','EXPIRED','CANCELLED') NOT NULL DEFAULT 'ACTIVE',
  `paymentId` int(11) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `remember_tokens`
--

CREATE TABLE `remember_tokens` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expires` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `schedules`
--

CREATE TABLE `schedules` (
  `id` int(11) NOT NULL,
  `trainerId` int(11) NOT NULL,
  `dayOfWeek` tinyint(4) NOT NULL,
  `startTime` time NOT NULL,
  `endTime` time NOT NULL,
  `status` enum('ACTIVE','OFF') NOT NULL DEFAULT 'ACTIVE'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `trainers`
--

CREATE TABLE `trainers` (
  `id` int(11) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `fullName` varchar(255) NOT NULL,
  `dateOfBirth` date NOT NULL,
  `sex` enum('Male','Female','Other') NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `specialization` text NOT NULL,
  `experience` int(11) NOT NULL,
  `certification` text NOT NULL,
  `salary` decimal(10,0) NOT NULL,
  `password` varchar(255) NOT NULL,
  `eRole` enum('ADMIN','TRAINER','MEMBER') NOT NULL DEFAULT 'TRAINER',
  `status` enum('ACTIVE','INACTIVE') NOT NULL DEFAULT 'ACTIVE',
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc đóng vai cho view `trainer_performance`
-- (See below for the actual view)
--
CREATE TABLE `trainer_performance` (
`trainer_id` int(11)
,`trainer_name` varchar(255)
,`total_clients` bigint(21)
,`total_sessions` bigint(21)
,`completed_sessions` bigint(21)
,`cancelled_sessions` bigint(21)
,`no_show_sessions` bigint(21)
);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `training_sessions`
--

CREATE TABLE `training_sessions` (
  `id` int(11) NOT NULL,
  `ptRegistrationId` int(11) NOT NULL,
  `sessionDate` datetime NOT NULL,
  `status` enum('SCHEDULED','COMPLETED','CANCELLED','NO_SHOW') NOT NULL DEFAULT 'SCHEDULED',
  `notes` text DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Bẫy `training_sessions`
--
DELIMITER $$
CREATE TRIGGER `after_training_session_update` AFTER UPDATE ON `training_sessions` FOR EACH ROW BEGIN
    IF NEW.status = 'COMPLETED' AND OLD.status != 'COMPLETED' THEN
        UPDATE pt_registrations
        SET remainingSessions = remainingSessions - 1
        WHERE id = NEW.ptRegistrationId;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `fullName` varchar(255) NOT NULL,
  `dateOfBirth` date NOT NULL,
  `sex` enum('Male','Female','Other') NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `membershipStatus` enum('ACTIVE','EXPIRED','SUSPENDED') NOT NULL DEFAULT 'EXPIRED',
  `eRole` enum('ADMIN','TRAINER','MEMBER') NOT NULL DEFAULT 'MEMBER',
  `status` enum('ACTIVE','INACTIVE') NOT NULL DEFAULT 'ACTIVE',
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `avatar`, `username`, `fullName`, `dateOfBirth`, `sex`, `phone`, `email`, `password`, `membershipStatus`, `eRole`, `status`, `createdAt`, `updatedAt`) VALUES
(1, '/assets/images/default-avatar.png', 'hieuqt', 'quangtronghieu', '2003-05-12', 'Male', '0899813765', 'sdasd@gmail.com', '$2y$10$CCBot307rkLgGsKS1JrIWOlrU9J7BFxrEc5I7XmpibqDIOS/.eKJa', 'EXPIRED', '', 'ACTIVE', '2024-12-12 01:19:39', '2024-12-12 01:19:39'),
(2, '/assets/images/default-avatar.png', 'hieuqt05', 'quangtronghieu123', '2003-05-12', 'Male', '0899813761', 'qweqweqw@gmail.com', '$2y$10$dmyUy5bWyudJwX2DHWT8c.toVMyqxZUDYRYf78RtbkOsRwUndinnq', 'EXPIRED', '', 'ACTIVE', '2024-12-12 02:02:42', '2024-12-12 02:02:42'),
(3, '/assets/images/default-avatar.png', 'admin01', 'admin123', '2003-05-12', 'Male', '0899813769', 'hieuqt.0511@gmail.com', '$2y$10$LoQXsENVcpHKqO9y8HLGmeKqLgtVuRYYIczxDtusdy4cWgu48oVC6', 'EXPIRED', '', 'ACTIVE', '2024-12-12 02:06:30', '2024-12-12 02:06:30');

-- --------------------------------------------------------

--
-- Cấu trúc cho view `membership_revenue_report`
--
DROP TABLE IF EXISTS `membership_revenue_report`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `membership_revenue_report`  AS SELECT date_format(`mr`.`createdAt`,'%Y-%m') AS `month_year`, `mp`.`name` AS `package_name`, count(`mr`.`id`) AS `total_registrations`, sum(`p`.`amount`) AS `total_revenue` FROM ((`membership_registrations` `mr` join `membership_packages` `mp` on(`mr`.`packageId` = `mp`.`id`)) join `payments` `p` on(`mr`.`paymentId` = `p`.`id`)) WHERE `p`.`paymentStatus` = 'COMPLETED' GROUP BY date_format(`mr`.`createdAt`,'%Y-%m'), `mp`.`id` ORDER BY date_format(`mr`.`createdAt`,'%Y-%m') DESC, sum(`p`.`amount`) DESC ;

-- --------------------------------------------------------

--
-- Cấu trúc cho view `trainer_performance`
--
DROP TABLE IF EXISTS `trainer_performance`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `trainer_performance`  AS SELECT `t`.`id` AS `trainer_id`, `t`.`fullName` AS `trainer_name`, count(distinct `ptr`.`userId`) AS `total_clients`, count(`ts`.`id`) AS `total_sessions`, count(case when `ts`.`status` = 'COMPLETED' then 1 end) AS `completed_sessions`, count(case when `ts`.`status` = 'CANCELLED' then 1 end) AS `cancelled_sessions`, count(case when `ts`.`status` = 'NO_SHOW' then 1 end) AS `no_show_sessions` FROM ((`trainers` `t` left join `pt_registrations` `ptr` on(`t`.`id` = `ptr`.`trainerId`)) left join `training_sessions` `ts` on(`ptr`.`id` = `ts`.`ptRegistrationId`)) GROUP BY `t`.`id` ;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_admin_username` (`username`),
  ADD UNIQUE KEY `unique_admin_email` (`email`);

--
-- Chỉ mục cho bảng `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `check_ins`
--
ALTER TABLE `check_ins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userId` (`userId`),
  ADD KEY `idx_checkin_time` (`checkInTime`,`checkOutTime`);

--
-- Chỉ mục cho bảng `equipment`
--
ALTER TABLE `equipment`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `equipment_images`
--
ALTER TABLE `equipment_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `equipment_id` (`equipment_id`);

--
-- Chỉ mục cho bảng `maintenance_logs`
--
ALTER TABLE `maintenance_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `equipmentId` (`equipmentId`);

--
-- Chỉ mục cho bảng `membership_packages`
--
ALTER TABLE `membership_packages`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `membership_registrations`
--
ALTER TABLE `membership_registrations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userId` (`userId`),
  ADD KEY `packageId` (`packageId`),
  ADD KEY `paymentId` (`paymentId`),
  ADD KEY `idx_membership_status` (`userId`,`status`,`startDate`,`endDate`);

--
-- Chỉ mục cho bảng `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `progress_tracking`
--
ALTER TABLE `progress_tracking`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userId` (`userId`);

--
-- Chỉ mục cho bảng `promotions`
--
ALTER TABLE `promotions`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `pt_packages`
--
ALTER TABLE `pt_packages`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `pt_registrations`
--
ALTER TABLE `pt_registrations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userId` (`userId`),
  ADD KEY `trainerId` (`trainerId`),
  ADD KEY `packageId` (`packageId`),
  ADD KEY `paymentId` (`paymentId`),
  ADD KEY `idx_pt_status` (`userId`,`trainerId`,`status`,`startDate`,`endDate`);

--
-- Chỉ mục cho bảng `remember_tokens`
--
ALTER TABLE `remember_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_token` (`token`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `trainerId` (`trainerId`);

--
-- Chỉ mục cho bảng `trainers`
--
ALTER TABLE `trainers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_trainer_username` (`username`),
  ADD UNIQUE KEY `unique_trainer_email` (`email`),
  ADD UNIQUE KEY `unique_trainer_phone` (`phone`),
  ADD KEY `idx_trainer_search` (`fullName`,`specialization`(100),`status`);

--
-- Chỉ mục cho bảng `training_sessions`
--
ALTER TABLE `training_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ptRegistrationId` (`ptRegistrationId`),
  ADD KEY `idx_session_date` (`sessionDate`,`status`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_username` (`username`),
  ADD UNIQUE KEY `unique_email` (`email`),
  ADD UNIQUE KEY `unique_phone` (`phone`),
  ADD KEY `idx_user_search` (`fullName`,`phone`,`email`,`membershipStatus`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `check_ins`
--
ALTER TABLE `check_ins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `equipment`
--
ALTER TABLE `equipment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `equipment_images`
--
ALTER TABLE `equipment_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `maintenance_logs`
--
ALTER TABLE `maintenance_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `membership_packages`
--
ALTER TABLE `membership_packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `membership_registrations`
--
ALTER TABLE `membership_registrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `progress_tracking`
--
ALTER TABLE `progress_tracking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `promotions`
--
ALTER TABLE `promotions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `pt_packages`
--
ALTER TABLE `pt_packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `pt_registrations`
--
ALTER TABLE `pt_registrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `remember_tokens`
--
ALTER TABLE `remember_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `trainers`
--
ALTER TABLE `trainers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `training_sessions`
--
ALTER TABLE `training_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `check_ins`
--
ALTER TABLE `check_ins`
  ADD CONSTRAINT `check_ins_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `equipment_images`
--
ALTER TABLE `equipment_images`
  ADD CONSTRAINT `equipment_images_ibfk_1` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `maintenance_logs`
--
ALTER TABLE `maintenance_logs`
  ADD CONSTRAINT `maintenance_logs_ibfk_1` FOREIGN KEY (`equipmentId`) REFERENCES `equipment` (`id`);

--
-- Các ràng buộc cho bảng `membership_registrations`
--
ALTER TABLE `membership_registrations`
  ADD CONSTRAINT `membership_registrations_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `membership_registrations_ibfk_2` FOREIGN KEY (`packageId`) REFERENCES `membership_packages` (`id`),
  ADD CONSTRAINT `membership_registrations_ibfk_3` FOREIGN KEY (`paymentId`) REFERENCES `payments` (`id`);

--
-- Các ràng buộc cho bảng `progress_tracking`
--
ALTER TABLE `progress_tracking`
  ADD CONSTRAINT `progress_tracking_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `pt_registrations`
--
ALTER TABLE `pt_registrations`
  ADD CONSTRAINT `pt_registrations_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `pt_registrations_ibfk_2` FOREIGN KEY (`trainerId`) REFERENCES `trainers` (`id`),
  ADD CONSTRAINT `pt_registrations_ibfk_3` FOREIGN KEY (`packageId`) REFERENCES `pt_packages` (`id`),
  ADD CONSTRAINT `pt_registrations_ibfk_4` FOREIGN KEY (`paymentId`) REFERENCES `payments` (`id`);

--
-- Các ràng buộc cho bảng `remember_tokens`
--
ALTER TABLE `remember_tokens`
  ADD CONSTRAINT `remember_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `schedules`
--
ALTER TABLE `schedules`
  ADD CONSTRAINT `schedules_ibfk_1` FOREIGN KEY (`trainerId`) REFERENCES `trainers` (`id`);

--
-- Các ràng buộc cho bảng `training_sessions`
--INSERT INTO `admins` 
(
    `avatar`, 
    `username`, 
    `email`, 
    `password`, 
    `eRole`, 
    `status`
) VALUES (
    'https://openui.fly.dev/openui/24x24.svg?text=👤',
    'admin',
    'admin@powergym.com',
    '$2y$10$CCBot307rkLgGsKS1JrIWOlrU9J7BFxrEc5I7XmpibqDIOS/.eKJa', -- This is hashed '123456'
    'ADMIN',
    'ACTIVE'
);
ALTER TABLE `training_sessions`
  ADD CONSTRAINT `training_sessions_ibfk_1` FOREIGN KEY (`ptRegistrationId`) REFERENCES `pt_registrations` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
