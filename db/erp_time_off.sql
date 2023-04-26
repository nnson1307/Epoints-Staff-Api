/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 50734
 Source Host           : localhost:8889
 Source Schema         : erp_matthews

 Target Server Type    : MySQL
 Target Server Version : 50734
 File Encoding         : 65001

 Date: 12/07/2022 17:00:34
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for staff_title
-- ----------------------------
DROP TABLE IF EXISTS `staff_title`;
CREATE TABLE `staff_title` (
  `staff_title_id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_title_name` varchar(191) CHARACTER SET utf8mb4 NOT NULL COMMENT 'Tên chức vụ',
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `staff_title_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã chức vụ',
  `staff_title_description` text COLLATE utf8mb4_unicode_ci COMMENT 'Mô tả',
  `is_system` tinyint(4) DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Trạng thái',
  `is_delete` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  PRIMARY KEY (`staff_title_id`) USING BTREE,
  UNIQUE KEY `staff_title_name` (`staff_title_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of staff_title
-- ----------------------------
BEGIN;
INSERT INTO `staff_title` (`staff_title_id`, `staff_title_name`, `slug`, `staff_title_code`, `staff_title_description`, `is_system`, `is_active`, `is_delete`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES (1, 'Giám Đốc', 'giam-doc', 'CV_0804201901', NULL, 0, 1, 0, '2019-04-08 13:48:53', '2019-04-08 06:48:53', NULL, NULL);
INSERT INTO `staff_title` (`staff_title_id`, `staff_title_name`, `slug`, `staff_title_code`, `staff_title_description`, `is_system`, `is_active`, `is_delete`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES (2, 'Trưởng Phòng', 'truong-phong', 'CV_0804201902', NULL, 0, 1, 0, '2019-04-08 13:49:01', '2019-04-08 06:49:01', NULL, NULL);
INSERT INTO `staff_title` (`staff_title_id`, `staff_title_name`, `slug`, `staff_title_code`, `staff_title_description`, `is_system`, `is_active`, `is_delete`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES (3, 'Nhóm Trưởng', 'nhom-truong', 'CV_0804201903', NULL, 0, 1, 0, '2019-04-08 13:49:06', '2019-04-08 06:49:06', NULL, NULL);
INSERT INTO `staff_title` (`staff_title_id`, `staff_title_name`, `slug`, `staff_title_code`, `staff_title_description`, `is_system`, `is_active`, `is_delete`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES (4, 'Nhân Viên', 'nhan-vien', 'CV_0705201904', 'nhunhu', 0, 1, 0, '2019-05-07 21:10:44', '2019-05-07 21:10:44', NULL, NULL);
COMMIT;

-- ----------------------------
-- Table structure for time_off_days
-- ----------------------------
DROP TABLE IF EXISTS `time_off_days`;
CREATE TABLE `time_off_days` (
  `time_off_days_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Mã ngày phép',
  `time_off_type_id` int(11) DEFAULT NULL COMMENT 'Mã loại phép	',
  `time_off_days_start` date DEFAULT NULL COMMENT 'Ngày nghĩ bắt đầu',
  `time_off_days_end` date DEFAULT NULL COMMENT 'Ngày nghĩ kết thúc',
  `time_off_note` text COLLATE utf8mb4_unicode_ci COMMENT 'Ghi chú	',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`time_off_days_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of time_off_days
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for time_off_days_activity_approve
-- ----------------------------
DROP TABLE IF EXISTS `time_off_days_activity_approve`;
CREATE TABLE `time_off_days_activity_approve` (
  `time_off_days_activity_approve_id` int(11) NOT NULL AUTO_INCREMENT,
  `time_off_days_id` int(11) DEFAULT NULL COMMENT 'mã ngày nghĩ',
  `is_approvce` int(11) DEFAULT NULL COMMENT '0 : không duyệt, 1 : duyệt',
  `time_off_days_activity_approve_note` text COLLATE utf8mb4_unicode_ci COMMENT 'Ghi chú',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`time_off_days_activity_approve_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of time_off_days_activity_approve
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for time_off_days_config_approve
-- ----------------------------
DROP TABLE IF EXISTS `time_off_days_config_approve`;
CREATE TABLE `time_off_days_config_approve` (
  `time_off_days_config_approve_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Mã config người duyệt ngày nghĩ',
  `time_off_type_id` int(11) DEFAULT NULL COMMENT 'Mã loại ngày phép',
  `staff_title_id` int(11) DEFAULT NULL COMMENT 'Mã chức danh',
  `time_off_days_config_approve_level` int(11) DEFAULT NULL COMMENT 'Cấp duyệt',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`time_off_days_config_approve_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of time_off_days_config_approve
-- ----------------------------
BEGIN;
INSERT INTO `time_off_days_config_approve` (`time_off_days_config_approve_id`, `time_off_type_id`, `staff_title_id`, `time_off_days_config_approve_level`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (1, 1, 3, 1, '2022-07-12 16:56:37', 1, '2022-07-12 16:56:40', 1);
INSERT INTO `time_off_days_config_approve` (`time_off_days_config_approve_id`, `time_off_type_id`, `staff_title_id`, `time_off_days_config_approve_level`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (2, 1, 2, 1, '2022-07-12 16:56:37', 1, '2022-07-12 16:56:40', 1);
INSERT INTO `time_off_days_config_approve` (`time_off_days_config_approve_id`, `time_off_type_id`, `staff_title_id`, `time_off_days_config_approve_level`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (3, 1, 1, 1, '2022-07-12 16:56:37', 1, '2022-07-12 16:56:40', 1);
COMMIT;

-- ----------------------------
-- Table structure for time_off_days_files
-- ----------------------------
DROP TABLE IF EXISTS `time_off_days_files`;
CREATE TABLE `time_off_days_files` (
  `time_off_days_files_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Mã file ngày phép',
  `time_off_days_files_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên file',
  `time_off_days_id` int(11) DEFAULT NULL COMMENT 'Mã ngày phép',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`time_off_days_files_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of time_off_days_files
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for time_off_type
-- ----------------------------
DROP TABLE IF EXISTS `time_off_type`;
CREATE TABLE `time_off_type` (
  `time_off_type_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Mã loại  đơn phép',
  `time_off_type_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên loại đơn phép',
  `time_off_type_parent_id` int(11) DEFAULT NULL COMMENT 'Mã loại đơn hàng cha',
  `time_off_type_description` text COLLATE utf8mb4_unicode_ci COMMENT 'Ghi chú',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`time_off_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of time_off_type
-- ----------------------------
BEGIN;
INSERT INTO `time_off_type` (`time_off_type_id`, `time_off_type_name`, `time_off_type_parent_id`, `time_off_type_description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (1, 'Nghĩ hưởng lương', 0, 'Nghĩ hưởng lương', '2022-07-12 16:54:35', 1, '2022-07-12 16:54:39', 1);
INSERT INTO `time_off_type` (`time_off_type_id`, `time_off_type_name`, `time_off_type_parent_id`, `time_off_type_description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (2, 'Nghĩ phép năm', 1, 'Nghĩ phép năm', '2022-07-12 16:54:35', 1, '2022-07-12 16:54:35', 1);
INSERT INTO `time_off_type` (`time_off_type_id`, `time_off_type_name`, `time_off_type_parent_id`, `time_off_type_description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (3, 'Nghĩ bản thân kết hôn', 1, 'Nghĩ bản thân kết hôn', '2022-07-12 16:54:35', 1, '2022-07-12 16:54:35', 1);
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
