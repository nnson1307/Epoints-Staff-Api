-- Adminer 4.8.1 MySQL 8.0.29 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `staffs`;
CREATE TABLE `staffs` (
  `staff_id` int unsigned NOT NULL AUTO_INCREMENT,
  `department_id` int DEFAULT NULL COMMENT 'Id phòng ban',
  `branch_id` int DEFAULT NULL,
  `staff_title_id` int DEFAULT NULL COMMENT 'Id chức vụ',
  `user_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` char(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '70fb6cc4d9ed728fa61892a8e7d085aad3c904dd',
  `salt` char(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '900150983cd24fb0d6963f7d28e17f72',
  `full_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Họ và Tên',
  `birthday` datetime DEFAULT NULL COMMENT 'Ngày sinh',
  `gender` enum('male','female','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'giới tính',
  `phone1` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'số điện thoại1',
  `phone2` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'số điện thoại2',
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'email1',
  `facebook` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'facebook',
  `date_last_login` datetime DEFAULT NULL COMMENT 'last login',
  `is_admin` tinyint NOT NULL DEFAULT '0' COMMENT 'Là admin',
  `is_actived` tinyint DEFAULT '0' COMMENT 'đã in acive',
  `is_deleted` tinyint DEFAULT '0' COMMENT 'đã xoá',
  `staff_avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'link hình avatar',
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Dia chi',
  `created_by` int NOT NULL,
  `updated_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `remember_token` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_master` tinyint DEFAULT '0' COMMENT 'Là tài khoản ẩn không hiển thị trong trang tài khoản . Mặc định là 0',
  `staff_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã nhân viên',
  `staff_type` enum('probationers','staff') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'staff' COMMENT 'probationers : thử việc, official : chính thức',
  `salary` decimal(16,3) DEFAULT '0.000' COMMENT 'Lương cứng',
  `subsidize` decimal(16,3) DEFAULT '0.000' COMMENT 'Trợ cấp',
  `commission_rate` decimal(16,3) DEFAULT '0.000' COMMENT 'Tỉ lệ hoa hồng nv',
  `password_chat` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT '123456' COMMENT 'Mật khẩu chat',
  PRIMARY KEY (`staff_id`) USING BTREE,
  UNIQUE KEY `user_name` (`user_name`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

TRUNCATE `staffs`;
INSERT INTO `staffs` (`staff_id`, `department_id`, `branch_id`, `staff_title_id`, `user_name`, `password`, `salt`, `full_name`, `birthday`, `gender`, `phone1`, `phone2`, `email`, `facebook`, `date_last_login`, `is_admin`, `is_actived`, `is_deleted`, `staff_avatar`, `address`, `created_by`, `updated_by`, `created_at`, `updated_at`, `remember_token`, `is_master`, `staff_code`, `staff_type`, `salary`, `subsidize`, `commission_rate`, `password_chat`) VALUES
(25,	1,	1,	1,	'admin',	'$2y$10$H7cmDr9Cu9ZUjyRi3PfXDOGrUKVvYJp8qcSFbn3yD69fsn4MeCBSu',	'900150983cd24fb0d6963f7d28e17f72',	'Admin Manager',	'1994-05-29 00:00:00',	'female',	'0967005205',	NULL,	'linhntp1997@gmail.com',	NULL,	NULL,	1,	1,	0,	'/uploads/admin/staff/20190916/7156864320116092019_staff.jpg',	'24 trường chinh , gò dầu tây ninh',	1,	1,	'2018-10-02 12:16:53',	'2022-03-17 14:04:25',	'zFoQHHsvr5GdFwYrpqHntK5DchxnSg0UhLfkNFcVs6t5WYO61hR6wx76CIZg',	0,	NULL,	'staff',	0.000,	0.000,	0.000,	'123456'),
(47,	3,	1,	3,	'yenni',	'$2y$10$iXsXtsFhNxYpMIlBWEmOe.VYxcLkbHbCqD03HYFm6eFoQ1BUIrFoO',	'900150983cd24fb0d6963f7d28e17f72',	'Yenni',	'1989-08-22 00:00:00',	'female',	'0478479939',	NULL,	'yenniegolden@gmail.com',	NULL,	NULL,	0,	1,	0,	NULL,	'263 Hampshire Road, Sunshine VIC 3020',	25,	25,	'2020-06-19 20:16:03',	'2020-07-15 04:17:16',	'QE2kVlcsGULJ77dBsQdzVcXvV1oCvK0ZcjqV8xDtv8CLzBeKlUjJxmRFhizv',	0,	NULL,	'staff',	0.000,	0.000,	0.000,	'123456'),
(48,	3,	1,	3,	'matthew03',	'$2y$10$Yiqw8OvKk4ZCUw/ubbw6nO7ZWTLALJebp4h1cAdSwwsJNKbt6TLge',	'900150983cd24fb0d6963f7d28e17f72',	'Matthew 03',	NULL,	'male',	'01234567892',	NULL,	NULL,	NULL,	NULL,	0,	1,	0,	NULL,	'72 tran trong cung',	25,	25,	'2020-06-20 02:22:43',	'2020-07-02 21:15:46',	'g3nKjVp8IP3r1aPeWUl4WQovE4KzljBQrgl5elR9FkSUcZcYaI5N5lccbjlB',	0,	NULL,	'staff',	0.000,	0.000,	0.000,	'123456'),
(49,	4,	1,	3,	'matthew02',	'$2y$10$Q2qVvGJgQGtQtzbRiGGt2OPzCkGbSPb/WlgqFp/MBFS3yhqGZowIy',	'900150983cd24fb0d6963f7d28e17f72',	'Matthew 02',	NULL,	'male',	'0987654321',	NULL,	NULL,	NULL,	NULL,	0,	1,	0,	NULL,	'72 Trần Trọng Cung',	25,	25,	'2020-07-02 21:25:24',	'2020-07-08 19:46:35',	'IwfSkZCl8UfPBbnchuqvK7EpM9XDIIoPr5RUdxUlpOhuHrfmuUsO6PZToWh7',	0,	NULL,	'staff',	0.000,	0.000,	0.000,	'123456'),
(50,	4,	2,	3,	'Nate',	'$2y$10$JJlXp6tvczM2DMDsiV5NlOg3zB4sjaFJCMJBnWdjkyiAW0YTn4x4G',	'900150983cd24fb0d6963f7d28e17f72',	'Nate',	'1988-09-29 00:00:00',	'male',	'0478479939',	NULL,	NULL,	NULL,	NULL,	0,	1,	0,	NULL,	'263 Hampshire Rd, Sunshine, Vic, 3020',	25,	25,	'2020-07-07 21:44:17',	'2020-10-16 04:36:56',	'5UCyGx6FwKx4cVjsVuGe8JsVEyv6l5mLzYUWjF7iCjRHMv2YreELgMs10d04',	0,	NULL,	'staff',	0.000,	0.000,	0.000,	'123456'),
(51,	4,	2,	3,	'admin01',	'$2y$10$f4nEYQCZ16SjDn7bB9ULJ.vXA36GPCZABFvVZcZQCnPmZfBts7m4K',	'900150983cd24fb0d6963f7d28e17f72',	'Matthews Admin',	NULL,	'female',	'0908123456',	NULL,	NULL,	NULL,	NULL,	1,	1,	0,	NULL,	'263 Hampshire Rd, Sunshine, Vic, 3020',	25,	25,	'2020-07-08 19:54:08',	'2021-09-14 06:34:48',	'IEVCpdV2PQEdjzQLruWTVUaSrdPKPBg5aa6Jg0l5FgLfBB2WJlEvSLuS8rxy',	0,	NULL,	'staff',	0.000,	0.000,	0.000,	'123456'),
(52,	4,	2,	1,	'TrungH',	'$2y$10$KWDHXy2TCbU.ArVIPfxXkOZBRuXww6rMTnnEoJrk.hqrvHdaAuCIK',	'900150983cd24fb0d6963f7d28e17f72',	'Trung',	NULL,	'male',	'0433218601',	NULL,	NULL,	NULL,	NULL,	1,	1,	0,	NULL,	'263 Hampshire Rd, Sunshine, Vic, 3020',	25,	25,	'2020-07-08 20:20:55',	'2021-05-10 07:47:51',	'cSnuSIW7GKoavk8YniQEQEOJltjnOp4yZTf3dwAUseQ2a5JYwW7erFDkruV2',	0,	NULL,	'staff',	0.000,	0.000,	0.000,	'123456'),
(53,	3,	1,	2,	'son1234',	'70fb6cc4d9ed728fa61892a8e7d085aad3c904dd',	'900150983cd24fb0d6963f7d28e17f72',	'Nguyễn Sơn 11',	NULL,	'male',	'0794212391',	NULL,	'b2dontcry@gmail.com',	NULL,	NULL,	0,	1,	0,	'https://matthews.piospa.com/uploads/admin/staff/20201017/5160291838117102020_staff.png',	'72 Trần Trọng Cung',	0,	0,	'2020-08-03 03:19:34',	'2020-12-11 03:03:41',	NULL,	0,	'bb94d412388c5847928dcd2ebcf123b9',	'staff',	0.000,	0.000,	0.000,	'123456'),
(54,	4,	2,	2,	'Jason',	'$2y$10$FxSv.z2CrwxRC5ULpsAc0uUq6qjatFiIaoOOV0ei0pIEMiehs33lK',	'900150983cd24fb0d6963f7d28e17f72',	'Jason Tran',	NULL,	'male',	'0411560714',	NULL,	'jason.tran369@gmail.com',	NULL,	NULL,	0,	0,	0,	NULL,	'263 Hampshire Rd, Sunshine, Vic, 3020',	51,	51,	'2020-08-05 19:44:48',	'2020-11-09 01:11:34',	'jmt6m6spd1uq10BkV20sL6l6MjBQUPAvSrOEZEFIDCfFrTX22xF7tdJBCRh9',	0,	NULL,	'staff',	0.000,	0.000,	0.000,	'123456'),
(55,	4,	2,	2,	'Troy',	'$2y$10$Bqj1fChEbOas5cVhcEbs1./XUJmCoFwNU1T5U3IPmkLScA.xLCqnq',	'900150983cd24fb0d6963f7d28e17f72',	'Troy',	NULL,	'male',	'0401798223',	NULL,	'troy@matthewsliquor.com.au',	NULL,	NULL,	0,	1,	0,	NULL,	'263 Hampshire Rd, Sunshine, Vic, 3020',	51,	51,	'2020-08-05 19:49:22',	'2020-08-13 01:45:06',	'FILm4BJFpfWU9bpG9isoIZ0nAmeFpMX0lb6HJawB1Q3JRSZtQkjBS9LmYYnj',	0,	NULL,	'staff',	0.000,	0.000,	0.000,	'123456'),
(56,	4,	2,	2,	'Chris',	'$2y$10$MehPkRFsIxURgPExy3oBUOnPhRFjskAGTsK7ybgvzLhfcYmi4s5gW',	'900150983cd24fb0d6963f7d28e17f72',	'Chris',	NULL,	'male',	'0435435176',	NULL,	'chanchris931@gmail.com',	NULL,	NULL,	0,	1,	0,	NULL,	'263 Hampshire Rd, Sunshine, Vic, 3020',	51,	51,	'2020-08-05 19:54:18',	'2020-08-13 01:44:17',	'AyWnr7EBeImSNvnzlzLVvto33anD1cJxp34DCQpx0jk33jNbakegzcQcNDgd',	0,	NULL,	'staff',	0.000,	0.000,	0.000,	'123456'),
(57,	4,	1,	2,	'linhlinh',	'$2y$10$vC4ayqV4Boso9sO7LKLpEutBw7lXzvYYvmKwgNiHvUhpfB5lcArOa',	'900150983cd24fb0d6963f7d28e17f72',	'Nguyen Phuong Linh',	NULL,	'female',	'0969998347',	NULL,	NULL,	NULL,	NULL,	1,	1,	0,	'https://matthews.piospa.com/uploads/admin/staff/20201014/6160267324714102020_staff.jpg',	'135B Tran Hung Dao',	25,	25,	'2020-09-22 04:28:40',	'2020-12-19 08:45:32',	NULL,	0,	NULL,	'staff',	0.000,	0.000,	0.000,	'123456'),
(58,	3,	4,	2,	'manh',	'$2y$10$ZOhFuGKwgwAEVdaECh6k3uJolIlJEt.XUtQamrfQvBHUCErfVXnma',	'900150983cd24fb0d6963f7d28e17f72',	'Manh',	'1970-01-01 00:00:00',	'male',	'0123456789',	NULL,	NULL,	NULL,	NULL,	1,	1,	0,	'https://matthews.piospa.com/uploads/admin/staff/20201015/5160275943115102020_staff.png',	'VietName',	25,	25,	'2020-10-14 23:27:25',	'2020-12-28 14:05:36',	NULL,	0,	NULL,	'staff',	0.000,	0.000,	0.000,	'123456'),
(59,	4,	1,	1,	'Haseena',	'$2y$10$Yaa62KneGs/ptG5y8L/BLeGoVx2wE9Vb/gPdbnwCM1/5ynpD8gxLa',	'900150983cd24fb0d6963f7d28e17f72',	'Haseena Shaikh',	NULL,	NULL,	'0470623480',	NULL,	NULL,	NULL,	NULL,	0,	1,	0,	NULL,	'215 Bell Stresst, Preston VIC 3072',	57,	57,	'2020-10-16 00:19:12',	'2020-10-16 00:19:12',	NULL,	0,	NULL,	'staff',	0.000,	0.000,	0.000,	'123456'),
(60,	4,	1,	1,	'Aashath',	'$2y$10$pBLxKCy7sIPCwNhBkVRYR.CfgnvBXZ8uCza5ja62dYY8tQjLcjq0W',	'900150983cd24fb0d6963f7d28e17f72',	'Aashath Kaamil',	NULL,	NULL,	'0433471113',	NULL,	'aashath.verve@gmail.com',	NULL,	NULL,	0,	1,	0,	NULL,	'215 Bell Street, Preston VIC 3072',	57,	57,	'2020-10-16 00:22:56',	'2020-10-16 00:22:56',	NULL,	0,	NULL,	'staff',	0.000,	0.000,	0.000,	'123456'),
(61,	2,	2,	1,	'test2020',	'$2y$10$XNTwKlAfpyuAgqVFo3OGHee77HQ7AYoXP470bwsIu34zP0Gqadva6',	'900150983cd24fb0d6963f7d28e17f72',	'Staff test',	NULL,	NULL,	'0909123123',	NULL,	NULL,	NULL,	NULL,	0,	1,	0,	NULL,	'227 Nguyen van Cu',	57,	57,	'2020-10-28 06:45:08',	'2020-10-28 06:45:08',	NULL,	0,	NULL,	'staff',	0.000,	0.000,	0.000,	'123456'),
(62,	2,	1,	2,	'Ngan',	'$2y$10$M7iJL4nYgPqVWzOvjGfdEuHJ/ya8QL7mBpDydbMOBvtSbKtaUfYfW',	'900150983cd24fb0d6963f7d28e17f72',	'Ngân',	'1997-08-02 00:00:00',	'female',	'0967313406',	NULL,	'cashierngan001@gmail.com',	NULL,	NULL,	0,	1,	0,	NULL,	'72, Trần Trọng Cung',	25,	25,	'2020-12-21 06:00:11',	'2020-12-21 06:00:11',	NULL,	0,	NULL,	'staff',	0.000,	0.000,	0.000,	'123456'),
(63,	4,	2,	2,	'linh1997',	'$2y$10$NWc9DJtWFyjNtD4VqgifvejAk5sIbEPIblJAfVPPK4rUvUNChD21u',	'900150983cd24fb0d6963f7d28e17f72',	'Linh Linh',	'1997-12-12 00:00:00',	'female',	'0909123123',	NULL,	'linhntp@pioapps.vn',	NULL,	NULL,	1,	1,	0,	NULL,	'135 Tran Hung Dao',	25,	25,	'2020-12-22 14:43:09',	'2022-03-17 14:04:35',	NULL,	0,	NULL,	'staff',	0.000,	0.000,	0.000,	'123456'),
(64,	1,	1,	1,	'JinaHuynh',	'$2y$10$0kf9tD2wPoY.GfMcXLotguRGK4afq8xZKNfRSgorGDLgVwQuHjWTS',	'900150983cd24fb0d6963f7d28e17f72',	'Jina Huynh',	NULL,	NULL,	'0987654321',	NULL,	NULL,	NULL,	NULL,	0,	1,	0,	NULL,	'418/14 Hồng Bàng, phường 16, quận 11, TP.HCM',	52,	52,	'2021-05-10 07:50:26',	'2021-05-10 07:50:26',	NULL,	0,	NULL,	'staff',	0.000,	0.000,	0.000,	'123456'),
(65,	1,	1,	1,	'Loan',	'$2y$10$iWux0FLL1NXjKXlZAUyz1uB/KZdE7bkwuMCWp0hcvw7inYPHai5oe',	'900150983cd24fb0d6963f7d28e17f72',	'Loan',	NULL,	'male',	'0909123456',	NULL,	NULL,	NULL,	NULL,	0,	1,	0,	NULL,	'135 TRAN HUNG DAO',	52,	52,	'2021-05-10 09:02:06',	'2021-05-10 09:04:38',	NULL,	0,	NULL,	'staff',	0.000,	0.000,	0.000,	'123456'),
(66,	1,	1,	2,	'Chi test',	'$2y$10$VWBrPfVC9BJH5T4QP9WuGe2pRKUa4Ty2vTjrSO/tFFOf3IU07x6L6',	'900150983cd24fb0d6963f7d28e17f72',	'Chi test',	'1971-01-02 00:00:00',	'female',	'0385456360',	NULL,	NULL,	NULL,	NULL,	0,	1,	0,	NULL,	'65/24 giai phong phuong 4 tân bình',	25,	25,	'2021-08-27 12:24:15',	'2021-08-27 12:30:57',	NULL,	0,	NULL,	'staff',	0.000,	0.000,	0.000,	'123456'),
(67,	3,	95,	7,	'Jina Test',	'$2y$10$PonXiG.u9yU1v3r7NYmZT.r4DOc10BNSHMNJWFQR5wt8cYol9zusO',	'900150983cd24fb0d6963f7d28e17f72',	'Jina Test',	NULL,	'male',	'0414792301',	NULL,	NULL,	NULL,	NULL,	0,	1,	1,	NULL,	'xyz',	51,	51,	'2021-09-21 12:42:45',	'2021-09-21 12:43:54',	NULL,	0,	NULL,	'staff',	0.000,	0.000,	0.000,	'123456'),
(68,	2,	2,	2,	'hquochung',	'$2y$10$LVavxuwipensi.pUPqTqtesxaDcbyvk3BnHxeVcRWvTRpkMp5CRiW',	'900150983cd24fb0d6963f7d28e17f72',	'Hung Test',	NULL,	'male',	'0478127471',	NULL,	NULL,	NULL,	NULL,	0,	1,	0,	NULL,	'1N',	25,	25,	'2021-09-24 06:57:40',	'2021-09-24 06:57:40',	NULL,	0,	NULL,	'staff',	0.000,	0.000,	0.000,	'123456');

-- 2022-07-18 02:37:58
