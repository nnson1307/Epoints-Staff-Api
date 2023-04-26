-- Adminer 4.8.1 MySQL 5.5.5-10.4.25-MariaDB dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `actions`;
CREATE TABLE `actions` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'tên slug chức năng',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'tên chức năng',
  `is_actived` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `action_group_id` int(11) DEFAULT NULL COMMENT 'Mã nhóm chức năng',
  `hot_function` tinyint(4) DEFAULT 0 COMMENT '0: Ko hot function, 1: Là hot function',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `action_group`;
CREATE TABLE `action_group` (
  `action_group_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `action_group_name_vi` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên nhóm quyền tiếng Việt',
  `action_group_name_en` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên nhóm quyền tiếng Anh',
  `is_actived` tinyint(1) DEFAULT 1 COMMENT 'Trạng thái',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `platform` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT 'portal' COMMENT 'portal: Site CRM, app_staff: App nhân vien',
  PRIMARY KEY (`action_group_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `admin_feature`;
CREATE TABLE `admin_feature` (
  `feature_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Mã chức năng',
  `feature_group_id` int(11) NOT NULL COMMENT 'Mã nhóm chức năng',
  `feature_name_vi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên chức năng - Tiếng việt',
  `feature_name_en` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên chức năng - Tiếng anh',
  `feature_code` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mã chức năng',
  `service_type` enum('portal','epoint') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Loại dịch vụ : portal , epoint ',
  `platform_type` enum('epoint','app') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Loại nền tảng : epoint , app',
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mô tả chức năng',
  `brand_action_id` int(11) DEFAULT NULL COMMENT 'Mã chức năng của brand : dmspro_mys_admin_action',
  `is_actived` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Trạng thái ',
  `created_at` datetime NOT NULL COMMENT 'Thời điểm tạo',
  `created_by` int(11) NOT NULL COMMENT 'Người tạo',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Thời điểm cập nhật ',
  `updated_by` int(11) NOT NULL COMMENT 'Người cập nhật ',
  `position` int(11) DEFAULT 0 COMMENT 'Thứ tự xuất hiện',
  PRIMARY KEY (`feature_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Danh sách chức năng của hệ thống -Master data';


DROP TABLE IF EXISTS `admin_feature_group`;
CREATE TABLE `admin_feature_group` (
  `feature_group_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Mã nhóm chức năng',
  `feature_group_name_vi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên nhóm chức năng - Tiếng việt',
  `feature_group_name_en` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên nhóm chức năng - Tiếng anh',
  `feature_group_name_redefine` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên nhóm chức năng - định nghĩa lại',
  `description_short` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mô tả ngắn',
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mô tả',
  `is_actived` tinyint(1) DEFAULT 1 COMMENT 'Trạng thái ',
  `created_at` datetime NOT NULL COMMENT 'Thời điểm tạo',
  `created_by` int(11) NOT NULL COMMENT 'người tạo',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Thời điểm cập nhật',
  `updated_by` int(11) NOT NULL COMMENT 'Người cập nhật ',
  PRIMARY KEY (`feature_group_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Danh sách nhóm chức năng - Master data';


DROP TABLE IF EXISTS `admin_menu`;
CREATE TABLE `admin_menu` (
  `admin_menu_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_menu_name_vi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_menu_name_en` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_menu_category_id` int(11) DEFAULT NULL,
  `admin_menu_route` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_menu_icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên icon',
  `admin_menu_img` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_menu_position` int(11) DEFAULT NULL COMMENT 'Vị trí ',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`admin_menu_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT;


DROP TABLE IF EXISTS `admin_menu_category`;
CREATE TABLE `admin_menu_category` (
  `menu_category_id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_category_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `menu_category_icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `is_show` tinyint(1) DEFAULT 0 COMMENT 'Hiển thị trên menu ngang',
  PRIMARY KEY (`menu_category_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `admin_menu_function`;
CREATE TABLE `admin_menu_function` (
  `admin_menu_function_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Mã menu chức năng',
  `admin_menu_category_id` int(11) DEFAULT NULL COMMENT 'Mã nhóm menu chức năng (danh mục menu)',
  `admin_menu_id` int(11) DEFAULT NULL COMMENT 'Mã menu',
  `position` int(11) DEFAULT NULL COMMENT 'Vị trí menu chức năng',
  `type` enum('horizontal','vertical') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'horizontal: menu ngang, vertical: menu dọc',
  `is_actived` tinyint(1) DEFAULT 1 COMMENT 'Trạng thái menu chức năng',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Ngày cập nhật',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`admin_menu_function_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `admin_service_brand`;
CREATE TABLE `admin_service_brand` (
  `service_brand_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Mã map dịch vụ và brand ',
  `service_id` int(11) NOT NULL COMMENT 'Mã dịch vụ ',
  `brand_id` int(11) NOT NULL COMMENT 'Mã brand',
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Đánh dấu xóa',
  `is_actived` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Trạng thái ',
  `created_by` int(11) NOT NULL COMMENT 'người tạo',
  `created_at` datetime NOT NULL COMMENT 'thời điểm tạo',
  `updated_by` int(11) NOT NULL COMMENT 'Người cập nhật ',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Thời điểm cập nhật',
  PRIMARY KEY (`service_brand_id`) USING BTREE,
  UNIQUE KEY `service_id_brand_id` (`service_id`,`brand_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Danh sách dịch vụ đăng ký của brand';


DROP TABLE IF EXISTS `admin_service_brand_feature`;
CREATE TABLE `admin_service_brand_feature` (
  `service_brand_feature_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Mã map brand dịch vụ và chức năng',
  `brand_id` int(11) NOT NULL COMMENT 'Mã  thương hiệu ',
  `service_id` int(11) DEFAULT NULL COMMENT 'Mã dịch vụ ',
  `feature_group_id` int(11) NOT NULL COMMENT 'Mã nhóm chức năng',
  `is_actived` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Tình trạng ',
  `created_at` datetime NOT NULL COMMENT 'Thời điểm tạo',
  `created_by` int(11) NOT NULL COMMENT 'người tạo',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Thời điểm cập nhật',
  `updated_by` int(11) NOT NULL COMMENT 'Người cập nhật ',
  `brand_update_at` datetime DEFAULT NULL COMMENT 'Thời điểm cập nhật bởi brand',
  `brand_update_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật bởi brand',
  PRIMARY KEY (`service_brand_feature_id`) USING BTREE,
  UNIQUE KEY `service_brand_id_feature_group_id` (`brand_id`,`feature_group_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Danh sách chi tiết chức năng brand đã đăng ký và tình trạng của nó';


DROP TABLE IF EXISTS `admin_service_brand_feature_child`;
CREATE TABLE `admin_service_brand_feature_child` (
  `service_brand_feature_child_id` int(11) NOT NULL AUTO_INCREMENT,
  `service_brand_feature_id` int(11) NOT NULL COMMENT 'Mã map brand dịch vụ và chức năng',
  `brand_id` int(11) NOT NULL COMMENT 'Mã  thương hiệu ',
  `service_id` int(11) DEFAULT NULL COMMENT 'Mã dịch vụ ',
  `feature_group_id` int(11) NOT NULL COMMENT 'Mã nhóm chức năng',
  `feature_id` int(11) DEFAULT NULL COMMENT 'Mã chức năng con của nhóm',
  `feature_code` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mã chức năng',
  `is_actived` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Tình trạng ',
  `created_at` datetime NOT NULL COMMENT 'Thời điểm tạo',
  `created_by` int(11) NOT NULL COMMENT 'người tạo',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Thời điểm cập nhật',
  `updated_by` int(11) NOT NULL COMMENT 'Người cập nhật ',
  `brand_update_at` datetime DEFAULT NULL COMMENT 'Thời điểm cập nhật bởi brand',
  `brand_update_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật bởi brand',
  PRIMARY KEY (`service_brand_feature_child_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Danh sách chi tiết chức năng brand đã đăng ký và tình trạng của nó';


DROP TABLE IF EXISTS `appointment_services`;
CREATE TABLE `appointment_services` (
  `appointment_service_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `customer_appointment_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_deleted` tinyint(4) DEFAULT 0,
  PRIMARY KEY (`appointment_service_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `appointment_source`;
CREATE TABLE `appointment_source` (
  `appointment_source_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `appointment_source_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`appointment_source_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `banner_slider`;
CREATE TABLE `banner_slider` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên file',
  `type` enum('mobile','desktop') COLLATE utf8mb4_unicode_ci DEFAULT 'desktop',
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` int(11) DEFAULT NULL COMMENT 'Vị trí hiển thị',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `booking_extra`;
CREATE TABLE `booking_extra` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Hình ảnh',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `booking_link`;
CREATE TABLE `booking_link` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link_iframe` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `branches`;
CREATE TABLE `branches` (
  `branch_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `branch_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Số điện thoại ',
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Email',
  `hot_line` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Hot line ',
  `provinceid` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT '79' COMMENT 'Mã Tỉnh / Thành phố',
  `districtid` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT '760' COMMENT 'Mã Quận / Huyện',
  `ward_id` int(11) DEFAULT NULL COMMENT 'Mã Phường/Xã',
  `is_representative` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Là chi nhánh chính',
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `representative_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'mã đại diện',
  `is_actived` tinyint(1) NOT NULL DEFAULT 1,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `created_by` int(11) DEFAULT 1,
  `updated_by` int(11) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `latitude` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Vĩ độ',
  `longitude` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Kinh độ',
  `branch_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'outlet_uuid',
  `site_id` int(11) DEFAULT NULL,
  `ghn_shop_id` int(11) DEFAULT NULL COMMENT 'Id shop giao hàng nhanh',
  PRIMARY KEY (`branch_id`) USING BTREE,
  UNIQUE KEY `branch_name` (`branch_name`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Danh sách chi nhánh';


DROP TABLE IF EXISTS `branch_images`;
CREATE TABLE `branch_images` (
  `branch_image_id` int(11) NOT NULL AUTO_INCREMENT,
  `branch_id` int(11) NOT NULL COMMENT 'Id chi nhánh',
  `name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên file',
  `type` enum('mobile','desktop') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'desktop',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`branch_image_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Danh sách hình ảnh của chi nhánh';


DROP TABLE IF EXISTS `brand`;
CREATE TABLE `brand` (
  `brand_id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `tenant_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ID branch dùng để xác định config multi tenant',
  `brand_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên nhãn hàng',
  `brand_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `brand_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'link api của nhà phân phối',
  `brand_avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Avatar nhãn hàng',
  `brand_banner` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'banner nhãn hàng',
  `brand_about` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Giới thiệu về nhãn hàng',
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên công ty',
  `company_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã công ty',
  `position` int(11) NOT NULL DEFAULT 0 COMMENT 'Vị trí sắp xếp. Số càng lớn thì nằm phía trên',
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên hiển thị',
  `is_published` tinyint(1) DEFAULT 0 COMMENT 'Hiển thị trên app',
  `is_activated` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Cho phép user tương tác với nhãn hàng',
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Đã xóa',
  `created_at` datetime DEFAULT NULL COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Thời gian cập nhật',
  `created_by` int(11) NOT NULL COMMENT 'người tạo',
  `updated_by` int(11) NOT NULL COMMENT 'người cập nhật',
  PRIMARY KEY (`brand_id`) USING BTREE,
  KEY `tenant_id` (`tenant_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Danh sách nhà phân phối';


DROP TABLE IF EXISTS `brandname`;
CREATE TABLE `brandname` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `bussiness`;
CREATE TABLE `bussiness` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên ngành nghề',
  `is_actived` tinyint(1) DEFAULT 1,
  `is_deleted` tinyint(1) DEFAULT 0,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Thông tin mô tả',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `carrier_notification`;
CREATE TABLE `carrier_notification` (
  `carrier_notification_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID tự tăng',
  `carrier_notification_detail_id` bigint(20) DEFAULT NULL COMMENT 'Chi tiet notification',
  `user_carrier_id` int(11) NOT NULL COMMENT 'ID user giao hàng',
  `notification_avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Avatar của thông báo',
  `notification_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Title',
  `notification_message` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nội dung thông báo',
  `is_read` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Tin nhắn đọc chua',
  `is_new` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0: mới 1: cũ',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`carrier_notification_id`) USING BTREE,
  KEY `carrier_notification_detail_id` (`carrier_notification_detail_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Thông báo';


DROP TABLE IF EXISTS `carrier_notification_detail`;
CREATE TABLE `carrier_notification_detail` (
  `carrier_notification_detail_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID notification tu tang',
  `tenant_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ID tenant. Neu notification cua mystore thi nul',
  `background` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Noi dung thong bao',
  `action_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên hiển thị của action',
  `action` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'App route khi click vao thong bao',
  `action_params` varchar(512) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Param route',
  `is_brand` tinyint(1) DEFAULT 0 COMMENT '0 nếu ở backoffice, 1 nếu gửi từ brandportal',
  `created_at` datetime DEFAULT NULL COMMENT 'Thoi gian tao',
  `created_by` int(11) DEFAULT NULL COMMENT 'Nguoi tao',
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Thoi gian cap nhat',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Nguoi cap nhat',
  PRIMARY KEY (`carrier_notification_detail_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Chi tiet notification';


DROP TABLE IF EXISTS `chathub_attribute`;
CREATE TABLE `chathub_attribute` (
  `attribute_id` int(11) NOT NULL AUTO_INCREMENT,
  `attribute_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `old_entities` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `entities` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attribute_status` tinyint(1) DEFAULT 1,
  `type` enum('have_response','not_have_response') DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `date_start_report` datetime DEFAULT NULL,
  PRIMARY KEY (`attribute_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `chathub_brand`;
CREATE TABLE `chathub_brand` (
  `brand_id` int(11) NOT NULL AUTO_INCREMENT,
  `brand_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `old_entities` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `entities` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `brand_status` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`brand_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `chathub_broadcast`;
CREATE TABLE `chathub_broadcast` (
  `broadcast_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `channel_id` varchar(255) DEFAULT NULL,
  `tag_id` int(11) DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `content_type` enum('text','image','file','audio') NOT NULL,
  `date` datetime DEFAULT NULL COMMENT 'Ngày gửi',
  `is_sent` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`broadcast_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `chathub_cache`;
CREATE TABLE `chathub_cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `chathub_channel`;
CREATE TABLE `chathub_channel` (
  `channel_id` int(11) NOT NULL AUTO_INCREMENT,
  `channel_social_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `service_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `cover_image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Link của trang',
  `user_id` int(11) DEFAULT NULL,
  `channel_access_token` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_subscribed` tinyint(4) NOT NULL DEFAULT 0,
  `is_dialogflow` tinyint(4) NOT NULL DEFAULT 0,
  `project_id_dialogflow` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `private_key_dialogflow` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_email_dialogflow` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_deleted` tinyint(4) NOT NULL DEFAULT 0,
  `show_option` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`channel_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `chathub_channel_service`;
CREATE TABLE `chathub_channel_service` (
  `service_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`service_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `chathub_comment`;
CREATE TABLE `chathub_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` int(10) unsigned NOT NULL,
  `message` varchar(2000) CHARACTER SET utf8 DEFAULT NULL,
  `image` varchar(500) CHARACTER SET utf8 DEFAULT NULL,
  `video` varchar(500) CHARACTER SET utf8 DEFAULT NULL,
  `post_id` int(10) unsigned NOT NULL,
  `comment_id` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `date_comment` datetime DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `chathub_config_post`;
CREATE TABLE `chathub_config_post` (
  `config_post_id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `brand` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `response_content_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`config_post_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `chathub_conversation`;
CREATE TABLE `chathub_conversation` (
  `conversation_id` int(11) NOT NULL AUTO_INCREMENT,
  `channel_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `last_time` datetime NOT NULL COMMENT 'thời gian cuối cùng hoạt động',
  `last_message_send` longtext DEFAULT NULL,
  `last_message` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content_type` varchar(255) NOT NULL DEFAULT 'text' COMMENT 'loại tin nhắn',
  `is_read` int(11) DEFAULT 0 COMMENT 'tổng số tin nhắn chưa đọc',
  `option_status` int(11) DEFAULT 0,
  `is_survey` tinyint(1) DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`conversation_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `chathub_customer`;
CREATE TABLE `chathub_customer` (
  `customer_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_social_id` varchar(255) NOT NULL,
  `channel_id` int(11) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `gender` enum('male','female') DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `avatar` text DEFAULT NULL,
  `tag_id` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `is_survey` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`customer_id`) USING BTREE,
  UNIQUE KEY `customer_social_id` (`customer_social_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `chathub_customer_channel_tag`;
CREATE TABLE `chathub_customer_channel_tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_channel_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  `created_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `chathub_history`;
CREATE TABLE `chathub_history` (
  `history_id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `source` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `conversation` int(11) DEFAULT 1 COMMENT 'nhóm conversation',
  `conversation_next` int(11) DEFAULT 1,
  `query` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parameters` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL COMMENT 'params nguyên mẫu truyền qua',
  `parameters_parse` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'params đã conver',
  `brand` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sub_brand` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sku` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attribute` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `response_detail_id` int(11) DEFAULT NULL,
  `response_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `response_same` tinyint(1) DEFAULT NULL COMMENT 'nếu 2 câu trã lời liên tiếp trùng nhau',
  `first_history` tinyint(1) DEFAULT 0 COMMENT 'đánh dấu là đoạn chat mở đầu',
  `request_time` datetime DEFAULT NULL,
  `response_time` datetime DEFAULT NULL,
  `type` enum('comment','message') DEFAULT NULL COMMENT 'kiểu của tin nhắn là comment hoặc chat',
  `post_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`history_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `chathub_history_banned`;
CREATE TABLE `chathub_history_banned` (
  `chathub_history_banned_id` int(11) NOT NULL AUTO_INCREMENT,
  `chathub_history_session` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`chathub_history_banned_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `chathub_keyword`;
CREATE TABLE `chathub_keyword` (
  `keyword_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`keyword_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `chathub_keyword_log`;
CREATE TABLE `chathub_keyword_log` (
  `keyword_log_id` int(11) NOT NULL AUTO_INCREMENT,
  `keyword_id` int(11) DEFAULT NULL COMMENT 'id của bảng keyword',
  `keyword` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'nội dung text',
  `user_id` int(11) DEFAULT NULL COMMENT 'session trong history',
  `history_id` int(11) DEFAULT NULL COMMENT 'id bảng histort',
  `log_date` datetime DEFAULT NULL COMMENT 'ngày của history ',
  `total` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL COMMENT 'ngày tạo',
  PRIMARY KEY (`keyword_log_id`) USING BTREE,
  KEY `user_id` (`user_id`) USING BTREE,
  KEY `history_id` (`history_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `chathub_link`;
CREATE TABLE `chathub_link` (
  `chathub_link_id` int(11) NOT NULL AUTO_INCREMENT,
  `brand_entities` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'chuỗi md5',
  `source` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'link muốn redirect',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`chathub_link_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `chathub_link_log`;
CREATE TABLE `chathub_link_log` (
  `link_log_id` int(11) NOT NULL AUTO_INCREMENT,
  `link_id` int(11) DEFAULT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`link_log_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `chathub_message`;
CREATE TABLE `chathub_message` (
  `message_id` int(11) NOT NULL AUTO_INCREMENT,
  `conversation_id` int(11) NOT NULL,
  `receiver_id` varchar(255) NOT NULL,
  `sender_id` varchar(255) NOT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content_type` varchar(255) NOT NULL DEFAULT 'text',
  `is_bot` tinyint(4) NOT NULL,
  `time` datetime DEFAULT NULL,
  `type` enum('receive','send') NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`message_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `chathub_post`;
CREATE TABLE `chathub_post` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `channel_id` int(10) unsigned NOT NULL,
  `post_id` varchar(255) CHARACTER SET utf8 NOT NULL,
  `brand` varchar(255) CHARACTER SET utf8 DEFAULT '0',
  `sub_brand` varchar(255) COLLATE utf8_unicode_ci DEFAULT '0',
  `sku` varchar(255) COLLATE utf8_unicode_ci DEFAULT '0',
  `attribute` varchar(255) COLLATE utf8_unicode_ci DEFAULT '0',
  `message` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(500) CHARACTER SET utf8 DEFAULT NULL,
  `video` varchar(500) CHARACTER SET utf8 DEFAULT NULL,
  `active` tinyint(1) DEFAULT 0,
  `date_comment` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `chathub_post_image`;
CREATE TABLE `chathub_post_image` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `image` varchar(500) CHARACTER SET utf8 DEFAULT NULL,
  `post_id` int(10) unsigned NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `chathub_response`;
CREATE TABLE `chathub_response` (
  `response_id` int(11) NOT NULL AUTO_INCREMENT,
  `response_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `response_status` tinyint(4) DEFAULT 1,
  `response_content` int(11) DEFAULT NULL,
  `brand` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sub_brand` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sku` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attribute` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`response_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `chathub_response_button`;
CREATE TABLE `chathub_response_button` (
  `response_button_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('postback','web_url') CHARACTER SET utf8mb4 DEFAULT NULL,
  `payload` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT 'DEVELOPER_DEFINED_PAYLOAD',
  `url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`response_button_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `chathub_response_code`;
CREATE TABLE `chathub_response_code` (
  `response_code_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1 COMMENT '0: used, 1: not use',
  `created_at` datetime DEFAULT NULL,
  `use_at` datetime DEFAULT NULL,
  PRIMARY KEY (`response_code_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `chathub_response_content`;
CREATE TABLE `chathub_response_content` (
  `response_content_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `response_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `response_end` tinyint(1) DEFAULT 0 COMMENT 'Câu trã lời kết thúc 1 hội thoại',
  `response_target` tinyint(1) DEFAULT NULL COMMENT 'Câu trã lời đúng mục tiêu',
  `is_personalized` tinyint(1) DEFAULT 0 COMMENT '1 nếu đang cá nhân hóa câu trã lời',
  `is_multi_response` tinyint(1) DEFAULT 0 COMMENT '1 nếu có nhiều câu trã lời',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `response_forward` tinyint(4) DEFAULT 0 COMMENT 'Attribute chưa có response',
  `brand_entities` varchar(255) DEFAULT NULL,
  `link_id` int(11) DEFAULT NULL,
  `type_message` enum('template','define') DEFAULT NULL,
  `template_type` enum('generic','list') DEFAULT 'generic' COMMENT 'template: câu trả lời có template, define: câu trả lời thường',
  PRIMARY KEY (`response_content_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `chathub_response_detail`;
CREATE TABLE `chathub_response_detail` (
  `response_detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `response_id` int(11) DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `brand` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `sub_brand` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `sku` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `attribute` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `response_content_id` int(11) DEFAULT NULL,
  `response_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `response_end` tinyint(1) DEFAULT 0,
  `response_target` tinyint(1) DEFAULT NULL,
  `response_status` tinyint(4) DEFAULT 0 COMMENT '1 end, 0 not end',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `type_message` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `template_type` enum('generic','list') DEFAULT NULL,
  PRIMARY KEY (`response_detail_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `chathub_response_detail_element`;
CREATE TABLE `chathub_response_detail_element` (
  `response_detail_element_id` int(11) NOT NULL AUTO_INCREMENT,
  `response_detail_id` int(11) NOT NULL,
  `response_element_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  `response_content_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`response_detail_element_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `chathub_response_element`;
CREATE TABLE `chathub_response_element` (
  `response_element_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subtitle` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`response_element_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `chathub_response_element_button`;
CREATE TABLE `chathub_response_element_button` (
  `response_element_button_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `response_element_id` int(10) unsigned NOT NULL,
  `response_button_id` int(10) unsigned NOT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`response_element_button_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `chathub_sku`;
CREATE TABLE `chathub_sku` (
  `sku_id` int(11) NOT NULL AUTO_INCREMENT,
  `sku_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `old_entities` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `entities` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sku_status` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `brand_entities` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`sku_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `chathub_sub_brand`;
CREATE TABLE `chathub_sub_brand` (
  `sub_brand_id` int(11) NOT NULL AUTO_INCREMENT,
  `brand_id` int(11) DEFAULT NULL,
  `sub_brand_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `old_entities` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `entities` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sub_brand_status` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`sub_brand_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `chathub_survey`;
CREATE TABLE `chathub_survey` (
  `survey_id` int(11) NOT NULL AUTO_INCREMENT,
  `type` enum('option','phone','gks') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `title` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keyword` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `img` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_child` tinyint(1) DEFAULT 0 COMMENT 'cho dẫn theo bé',
  `step` tinyint(4) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`survey_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `chathub_survey_log`;
CREATE TABLE `chathub_survey_log` (
  `survey_log` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `survey_id` int(11) DEFAULT NULL,
  `option_id` int(11) DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `img` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) DEFAULT 0,
  `is_end` int(11) DEFAULT 0,
  `conversation` int(11) DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `is_child` tinyint(4) DEFAULT NULL,
  `step` tinyint(4) DEFAULT NULL,
  `cmnd` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_cmnd` int(11) DEFAULT NULL,
  `is_gks` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `chathub_survey_option`;
CREATE TABLE `chathub_survey_option` (
  `option_id` int(11) NOT NULL AUTO_INCREMENT,
  `survey_id` int(11) DEFAULT NULL,
  `title` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keyword` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`option_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `chathub_welcome_persistent_menu`;
CREATE TABLE `chathub_welcome_persistent_menu` (
  `welcome_id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`welcome_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `check_version`;
CREATE TABLE `check_version` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `platform` enum('android','ios') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Platform',
  `version` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên version',
  `release_date` date NOT NULL COMMENT 'Ngày release',
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Link cập nhật',
  `flag` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1: Bắt buộc update; 0: không bắt update',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  `is_review` tinyint(4) DEFAULT 0 COMMENT 'App đang được review trên store',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Quản lý version của app';


DROP TABLE IF EXISTS `commission_log`;
CREATE TABLE `commission_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) DEFAULT NULL COMMENT 'Khách giới thiệu',
  `branch_id` int(11) DEFAULT NULL COMMENT 'Chi nhánh',
  `money` decimal(10,0) DEFAULT NULL,
  `type` enum('cash_out','tranfer_money') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'tranfer_money',
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `config`;
CREATE TABLE `config` (
  `config_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `key` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_show` tinyint(1) DEFAULT 1,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT 'text' COMMENT 'Loại form input  (text, ckeditor, date, time, image, boolean, option)',
  PRIMARY KEY (`config_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `config_customer_parameter`;
CREATE TABLE `config_customer_parameter` (
  `config_customer_parameter_id` int(11) NOT NULL AUTO_INCREMENT,
  `parameter_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên parameter',
  `content` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nội dung được chèn vào',
  `is_deleted` tinyint(4) DEFAULT 0 COMMENT 'Đánh dấu xoá',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`config_customer_parameter_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `config_detail`;
CREATE TABLE `config_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `config_id` int(11) NOT NULL,
  `key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `config_email_template`;
CREATE TABLE `config_email_template` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `logo` tinyint(1) DEFAULT 1 COMMENT 'Hiển thị logo',
  `website` tinyint(1) DEFAULT 1 COMMENT 'Hiển thị website ',
  `background_header` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Màu nền header',
  `color_header` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Màu chữ header',
  `background_body` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Màu nền body',
  `color_body` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Màu chữ body',
  `background_footer` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Màu nền footer',
  `color_footer` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Màu chữ footer',
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ảnh minh họa',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `config_function_app`;
CREATE TABLE `config_function_app` (
  `config_function_key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Key cấu hình',
  `value` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Giá trị',
  PRIMARY KEY (`config_function_key`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `config_home_app`;
CREATE TABLE `config_home_app` (
  `config_home_app_id` int(11) NOT NULL AUTO_INCREMENT,
  `object_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'product: sản phẩm, service: dịch vụ',
  `object_category_id` int(11) DEFAULT NULL COMMENT 'Loại sp, dv',
  `position` int(11) DEFAULT NULL COMMENT 'Vị trí hiển thị',
  `max_item` int(11) DEFAULT NULL COMMENT 'Số lượng phần tử con hiển thị',
  `object_name_display_vi` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên hiển thị cụm vi',
  `object_name_display_en` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên hiển thị cụm en',
  `type_display` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'H: horizontal, V: vertical',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`config_home_app_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `config_notification`;
CREATE TABLE `config_notification` (
  `key` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Key để phân biệt notification',
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên nội dung cấu hình',
  `config_notification_group_id` int(11) NOT NULL COMMENT 'Nhóm cấu hình',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Active cho phép gửi',
  `display_sort` int(11) NOT NULL DEFAULT 100 COMMENT 'Vị trí hiển thị trong cùng 1 nhóm',
  `send_type` enum('immediately','before','after','in_time') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'immediately' COMMENT 'Loại gửi',
  `schedule_unit` enum('day','hour','minute') CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Đơn vị cộng thêm. Dùng hàm Cartbon->add(2, ''hour'') để tính thời gian chính xác',
  `value` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Giá trị sẽ + - hoặc đúng thời điểm',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Ngày cạp nhật',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  PRIMARY KEY (`key`) USING BTREE,
  KEY `config_notification_group_id` (`config_notification_group_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Cấu hình push notification tự động';


DROP TABLE IF EXISTS `config_notification_group`;
CREATE TABLE `config_notification_group` (
  `config_notification_group_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID tự tăng',
  `config_notification_group_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên nhóm',
  `display_sort` int(11) DEFAULT 100 COMMENT 'Sắp xếp vị trí hiển thị',
  `created_at` datetime DEFAULT NULL COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Thời gian cập nhật',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  PRIMARY KEY (`config_notification_group_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Group cấu hình push notification';


DROP TABLE IF EXISTS `config_print_bill`;
CREATE TABLE `config_print_bill` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `printed_sheet` int(11) DEFAULT 1 COMMENT 'Số liên in',
  `is_print_reply` int(11) NOT NULL DEFAULT 1 COMMENT 'In lại hoặc không',
  `print_time` int(11) DEFAULT NULL COMMENT 'Số lần in',
  `is_show_logo` int(11) DEFAULT 1 COMMENT 'Show logo',
  `is_show_unit` int(11) DEFAULT 1 COMMENT 'Show  tên đơn vị/ cty',
  `is_show_address` int(11) DEFAULT 1 COMMENT 'Show  địa chỉ đơn vị/ cty/ chi nhánh',
  `is_show_phone` int(11) DEFAULT 1 COMMENT 'Show  SĐT',
  `is_show_order_code` int(11) DEFAULT 1 COMMENT 'Show  mã hóa đơn',
  `is_show_cashier` int(11) DEFAULT 1 COMMENT 'Show người thu ngân',
  `is_show_customer` int(11) DEFAULT 1 COMMENT 'Show khách hàng',
  `is_show_datetime` int(11) DEFAULT 1 COMMENT 'Show thời gian in',
  `is_show_footer` int(11) DEFAULT 1 COMMENT 'Show footer',
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `template` enum('k58','k80','A5','A4') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'k80',
  `symbol` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_total_bill` int(11) DEFAULT 1 COMMENT 'Show tổng tiền',
  `is_total_discount` int(11) DEFAULT 1 COMMENT 'Show tổng giảm giá',
  `is_total_amount` int(11) DEFAULT 1 COMMENT 'Show tổng tiền phải trả',
  `is_total_receipt` int(11) DEFAULT 1 COMMENT 'Show tổng tiền KH trả',
  `is_amount_return` int(11) DEFAULT 1 COMMENT 'Show tiền trả lại',
  `note_footer` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Thông tin thêm ở cuối hoá đơn',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `config_print_service_card`;
CREATE TABLE `config_print_service_card` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `card_type` enum('service_card','money_card','voucher') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Loại thẻ',
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Hình logo',
  `name_spa` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên spa',
  `background` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Màu nền',
  `color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Màu chữ',
  `background_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Hình nền',
  `qr_code` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `config_reject_order`;
CREATE TABLE `config_reject_order` (
  `config_reject_order_id` int(11) NOT NULL AUTO_INCREMENT,
  `province_id` int(11) DEFAULT NULL COMMENT 'Id tỉnh thành',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`config_reject_order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `config_reject_order_detail`;
CREATE TABLE `config_reject_order_detail` (
  `config_reject_order_detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `config_reject_order_id` int(11) DEFAULT NULL COMMENT 'Map với cầu hình',
  `province_id` int(11) DEFAULT NULL COMMENT 'Id tỉnh thành',
  `district_id` int(11) DEFAULT NULL COMMENT 'Id quận huyện',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`config_reject_order_detail_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `config_reviews`;
CREATE TABLE `config_reviews` (
  `config_review_id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'order: Đơn hàng, product: sp/dv/ thẻ dv',
  `is_browse` tinyint(4) DEFAULT 1 COMMENT '0: Ko cần duyệt, 1: Duyệt',
  `is_buy` tinyint(4) DEFAULT 1 COMMENT '0: Bất kì ai cũng đánh giá, 1: Đã mua sản phẩm mới đánh giá',
  `expired_review` int(11) DEFAULT NULL COMMENT 'Thời hạn đánh giá (ngày)',
  `is_edit` tinyint(4) DEFAULT 1 COMMENT '1: Cho phép sửa đánh giá, 0: Ko cho sửa đánh giá',
  `is_deleted` tinyint(4) DEFAULT NULL COMMENT '1: Cho phép xoá, 0: Ko cho phép xoá',
  `is_review_image` tinyint(4) DEFAULT 1 COMMENT '1: Cho bình luận bằng hình ảnh, 0: Ko cho bình luận hình ảnh',
  `limit_number_image` int(11) DEFAULT 1 COMMENT 'Giới hạn số lượng hình ảnh',
  `limit_capacity_image` int(11) DEFAULT NULL COMMENT 'Giới hạn dung lượng hình ảnh',
  `is_review_video` tinyint(4) DEFAULT 0 COMMENT '1: Cho phép bình luận video, 0: Ko cho phép bình luận video',
  `limit_number_video` int(11) DEFAULT NULL COMMENT 'Giới hạn số lượng video',
  `limit_capacity_video` int(11) DEFAULT NULL COMMENT 'Giới hạn dung lượng video',
  `is_auto_reply` tinyint(4) DEFAULT 0 COMMENT '1: Tự động trả lời, 0: Ko tự động trả lời',
  `is_suggest` tinyint(4) DEFAULT 0 COMMENT '1: Cho phép chọn gợi ý, 0: Ko cho phép chọn gợi ý',
  `is_review_google` tinyint(4) DEFAULT 0 COMMENT '1: Show view đánh giá google, 0: Ko show view đánh giá google',
  `rating_value_google` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Số sao đánh giá để hiển thị view google',
  `max_length_content` int(11) DEFAULT 191 COMMENT 'Giới hạn kí tự nhập nội dung đánh giá',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`config_review_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `config_review_content_hint`;
CREATE TABLE `config_review_content_hint` (
  `config_review_content_hint_id` int(11) NOT NULL AUTO_INCREMENT,
  `config_review_id` int(11) DEFAULT NULL COMMENT 'Link với cấu hình đánh giá',
  `rating_value` int(11) DEFAULT NULL COMMENT 'Giá trị đánh giá',
  `content_hint` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nội dung gợi ý đánh giá',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`config_review_content_hint_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `config_review_content_reply`;
CREATE TABLE `config_review_content_reply` (
  `config_review_content_reply_id` int(11) NOT NULL AUTO_INCREMENT,
  `config_review_id` int(11) DEFAULT NULL COMMENT 'Link với cầu hình đánh giá',
  `rating_value` int(11) DEFAULT NULL COMMENT 'Giá trị đánh giá',
  `content` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nội dung trả lời tự động',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngàu cập nhật',
  PRIMARY KEY (`config_review_content_reply_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `config_review_content_suggest`;
CREATE TABLE `config_review_content_suggest` (
  `config_review_content_suggest_id` int(11) NOT NULL AUTO_INCREMENT,
  `config_review_id` int(11) DEFAULT NULL COMMENT 'Link với cấu hình đánh giá',
  `content_suggest_id` int(11) DEFAULT NULL COMMENT 'Link với cú pháp gợi ý',
  `rating_value` int(11) DEFAULT NULL COMMENT 'Giá trị đánh giá',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`config_review_content_suggest_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `config_staff_notification`;
CREATE TABLE `config_staff_notification` (
  `key` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Key để phân biệt notification',
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên nội dung cấu hình',
  `config_staff_notification_group_id` int(11) NOT NULL COMMENT 'Nhóm cấu hình',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Active cho phép gửi',
  `display_sort` int(11) NOT NULL DEFAULT 100 COMMENT 'Vị trí hiển thị trong cùng 1 nhóm',
  `send_type` enum('immediately','before','after','in_time') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'immediately' COMMENT 'Loại gửi',
  `schedule_unit` enum('day','hour','minute') CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Đơn vị cộng thêm. Dùng hàm Cartbon->add(2, ''hour'') để tính thời gian chính xác',
  `value` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Giá trị sẽ + - hoặc đúng thời điểm',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Ngày cạp nhật',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  PRIMARY KEY (`key`) USING BTREE,
  KEY `config_notification_group_id` (`config_staff_notification_group_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Cấu hình push notification tự động';


DROP TABLE IF EXISTS `config_staff_notification_group`;
CREATE TABLE `config_staff_notification_group` (
  `config_notification_group_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID tự tăng',
  `config_notification_group_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên nhóm',
  `display_sort` int(11) DEFAULT 100 COMMENT 'Sắp xếp vị trí hiển thị',
  `created_at` datetime DEFAULT NULL COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Thời gian cập nhật',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  PRIMARY KEY (`config_notification_group_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Group cấu hình push notification';


DROP TABLE IF EXISTS `config_text_app`;
CREATE TABLE `config_text_app` (
  `text_key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `text_vi` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Text VI',
  `text_en` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Text EN',
  PRIMARY KEY (`text_key`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `config_time_reset_rank`;
CREATE TABLE `config_time_reset_rank` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tháng reset rank',
  `type` enum('one_month','two_month','three_month','four_month','six_month','one_year') COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_deleted` tinyint(4) DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `content_suggest`;
CREATE TABLE `content_suggest` (
  `content_suggest_id` int(11) NOT NULL AUTO_INCREMENT,
  `content_suggest` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nội dung cú pháp gợi ý',
  `rating_value` int(11) DEFAULT NULL COMMENT 'Vị trí ở tab sao',
  `is_actived` tinyint(4) DEFAULT 1,
  `is_deleted` tinyint(4) DEFAULT 0 COMMENT 'Đánh dấu xoá',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Ngày cập nhật',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`content_suggest_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `contracts`;
CREATE TABLE `contracts` (
  `contract_id` int(11) NOT NULL AUTO_INCREMENT,
  `contract_category_id` int(11) NOT NULL COMMENT 'Link với mã loại HĐ',
  `contract_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên HĐ',
  `contract_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã HĐ',
  `contract_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Số HĐ',
  `contract_form` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'new' COMMENT 'new : ký mới. renew : tái ký',
  `sign_date` date DEFAULT NULL COMMENT 'Ngày ký HĐ',
  `performer_by` int(11) DEFAULT NULL COMMENT 'Người thực hiện',
  `effective_date` date DEFAULT NULL COMMENT 'Ngày có hiệu lực',
  `expired_date` date DEFAULT NULL COMMENT 'Ngày hết hiệu lực',
  `is_renew` tinyint(4) DEFAULT 0 COMMENT '1: Tự động gia hạn, 0: Ko tự động gia hạn',
  `number_day_renew` int(11) DEFAULT 0 COMMENT 'Số ngày gia hạn trước',
  `is_created_ticket` tinyint(4) NOT NULL DEFAULT 0 COMMENT '1: Tự động tạo ticket, 0: Ko tự động tạo',
  `status_code_created_ticket` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Trạng thái tự động tạo ticket',
  `warranty_start_date` date DEFAULT NULL COMMENT 'Ngày bắt đầu bảo hành',
  `warranty_end_date` date DEFAULT NULL COMMENT 'Ngày kết thúc bảo hành',
  `content` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nội dung',
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ghi chú',
  `status_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Trạng thái hợp đồng',
  `is_value_goods` tinyint(4) DEFAULT 0 COMMENT '1: Lấy theo giá trị hàng hoá, 0: ko theo giá trị',
  `ticket_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'link với mã ticket',
  `reason_remove` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Lý do xoá',
  `is_deleted` tinyint(4) DEFAULT 0 COMMENT 'Đánh dấu xoá',
  `created_by` int(11) DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `custom_1` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_2` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_3` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_4` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_5` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_6` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_7` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_8` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_9` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_10` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_11` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_12` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_13` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_14` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_15` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_16` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_17` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_18` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_19` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_20` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  `is_browse` tinyint(4) DEFAULT 0 COMMENT '0: Ko đợi duyệt, 1: Đang đợi duyệt',
  `is_applied_kpi` tinyint(1) DEFAULT 1 COMMENT '1: Được áp dụng KPI, 0: Không áp dụng KPI',
  PRIMARY KEY (`contract_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `contract_annex`;
CREATE TABLE `contract_annex` (
  `contract_annex_id` int(11) NOT NULL AUTO_INCREMENT,
  `contract_id` int(11) DEFAULT NULL COMMENT 'link với hợp đồng',
  `contract_annex_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã phụ lục',
  `sign_date` date DEFAULT NULL COMMENT 'Ngày ký phụ lục',
  `effective_date` date DEFAULT NULL COMMENT 'Ngày có hiệu lực',
  `expired_date` date DEFAULT NULL COMMENT 'Ngày hết hiệu lực',
  `adjustment_type` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Loại điều chỉnh',
  `content` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nội dung',
  `is_active` tinyint(4) DEFAULT 0 COMMENT 'Đánh dấu họat động',
  `is_deleted` tinyint(4) DEFAULT 0 COMMENT 'Đánh dấu xoá',
  `is_checked_recare` tinyint(4) DEFAULT 0 COMMENT 'Đã được đánh dấu là hợp đồng gia hạn ở báo cáo',
  `created_by` int(11) DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`contract_annex_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `contract_annex_files`;
CREATE TABLE `contract_annex_files` (
  `contract_annex_file_id` int(11) NOT NULL AUTO_INCREMENT,
  `contract_annex_id` int(11) NOT NULL COMMENT 'Link với loại hợp đồng',
  `link` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Link file',
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên file',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`contract_annex_file_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `contract_annex_follow_map`;
CREATE TABLE `contract_annex_follow_map` (
  `contract_annex_follow_map_id` int(11) NOT NULL AUTO_INCREMENT,
  `contract_annex_id` int(11) NOT NULL COMMENT 'Link với HĐ',
  `follow_by` int(11) NOT NULL COMMENT 'Link với người theo dõi',
  PRIMARY KEY (`contract_annex_follow_map_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `contract_annex_general`;
CREATE TABLE `contract_annex_general` (
  `contract_annex_general_id` int(11) NOT NULL AUTO_INCREMENT,
  `contract_annex_id` int(11) DEFAULT NULL COMMENT 'link với phụ lục',
  `contract_category_id` int(11) NOT NULL COMMENT 'Link với mã loại HĐ',
  `contract_name` varchar(191) CHARACTER SET utf16le DEFAULT NULL COMMENT 'Tên HĐ',
  `contract_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã HĐ',
  `contract_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Số HĐ',
  `sign_date` date DEFAULT NULL COMMENT 'Ngày ký HĐ',
  `performer_by` int(11) DEFAULT NULL COMMENT 'Người thực hiện',
  `effective_date` date DEFAULT NULL COMMENT 'Ngày có hiệu lực',
  `expired_date` date DEFAULT NULL COMMENT 'Ngày hết hiệu lực',
  `is_renew` tinyint(4) DEFAULT 0 COMMENT '1: Tự động gia hạn, 0: Ko tự động gia hạn',
  `number_day_renew` int(11) DEFAULT 0 COMMENT 'Số ngày gia hạn trước',
  `is_created_ticket` tinyint(4) NOT NULL DEFAULT 0 COMMENT '1: Tự động tạo ticket, 0: Ko tự động tạo',
  `status_code_created_ticket` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Trạng thái tự động tạo ticket',
  `warranty_start_date` date DEFAULT NULL COMMENT 'Ngày bắt đầu bảo hành',
  `warranty_end_date` date DEFAULT NULL COMMENT 'Ngày kết thúc bảo hành',
  `content` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nội dung',
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ghi chú',
  `status_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Trạng thái hợp đồng',
  `is_value_goods` tinyint(4) DEFAULT 0 COMMENT '1: Lấy theo giá trị hàng hoá, 0: ko theo giá trị',
  `is_deleted` tinyint(4) DEFAULT 0 COMMENT 'Đánh dấu xoá',
  `created_by` int(11) DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `custom_1` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_2` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_3` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_4` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_5` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_6` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_7` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_8` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_9` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_10` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_11` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_12` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_13` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_14` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_15` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_16` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_17` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_18` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_19` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_20` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`contract_annex_general_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `contract_annex_goods`;
CREATE TABLE `contract_annex_goods` (
  `contract_goods_id` int(11) NOT NULL AUTO_INCREMENT,
  `contract_annex_id` int(11) NOT NULL COMMENT 'Link với mã HĐ',
  `object_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'product: Sản phẩm, service: Dịch vụ, service_card: Thẻ dịch vụ',
  `object_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên đối tượng',
  `object_id` int(11) DEFAULT NULL COMMENT 'Id đối tượng',
  `object_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã đối tượng',
  `unit_id` int(11) DEFAULT NULL COMMENT 'Đơn vị tính',
  `price` decimal(16,3) DEFAULT NULL COMMENT 'Giá',
  `quantity` int(11) DEFAULT NULL COMMENT 'Số lượng',
  `discount` decimal(16,3) DEFAULT NULL COMMENT 'Giảm giá',
  `tax` int(11) DEFAULT NULL COMMENT 'Thuế',
  `amount` decimal(16,3) DEFAULT NULL COMMENT 'Thành tiền ',
  `note` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ghi chú',
  `order_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã HĐ',
  `staff_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nhân viên phục vụ',
  `is_deleted` tinyint(4) DEFAULT 0 COMMENT 'Đánh dấu xoá',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  `is_applied_kpi` tinyint(1) DEFAULT 1 COMMENT '1: Được áp dụng KPI, 0: Không áp dụng KPI',
  PRIMARY KEY (`contract_goods_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `contract_annex_logs`;
CREATE TABLE `contract_annex_logs` (
  `contract_annex_log_id` int(11) NOT NULL AUTO_INCREMENT,
  `object_type` varchar(191) DEFAULT NULL COMMENT 'contract or annex. nếu annex thì là log của 1 annex, nếu là contract thì log của 1 contract',
  `contract_annex_id` int(11) DEFAULT NULL COMMENT 'id của phu lục',
  `key_table` varchar(191) DEFAULT NULL COMMENT 'tên table: contract_annex, contract_annex_general, contract_annex_partner, contract_annex_payment',
  `key` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'field cần lưu log',
  `key_name` varchar(191) DEFAULT NULL COMMENT 'tên hiển thị field',
  `value_old` varchar(191) DEFAULT NULL COMMENT 'value cũ của key',
  `value_new` varchar(191) DEFAULT NULL COMMENT 'value mới của key',
  `created_by` int(11) DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`contract_annex_log_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `contract_annex_log_goods`;
CREATE TABLE `contract_annex_log_goods` (
  `contract_annex_log_good_id` int(11) NOT NULL AUTO_INCREMENT,
  `contract_annex_id` int(11) NOT NULL COMMENT 'Link với phụ lục',
  `version` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'old or new',
  `object_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'product: Sản phẩm, service: Dịch vụ, service_card: Thẻ dịch vụ',
  `object_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên đối tượng',
  `object_id` int(11) DEFAULT NULL COMMENT 'Id đối tượng',
  `object_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã đối tượng',
  `unit_id` int(11) DEFAULT NULL COMMENT 'Đơn vị tính',
  `price` decimal(16,3) DEFAULT NULL COMMENT 'Giá',
  `quantity` int(11) DEFAULT NULL COMMENT 'Số lượng',
  `discount` decimal(16,3) DEFAULT NULL COMMENT 'Giảm giá',
  `tax` int(11) DEFAULT NULL COMMENT 'Thuế',
  `amount` decimal(16,3) DEFAULT NULL COMMENT 'Thành tiền ',
  `note` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ghi chú',
  `order_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã HĐ',
  `staff_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nhân viên phục vụ',
  `is_deleted` tinyint(4) DEFAULT 0 COMMENT 'Đánh dấu xoá',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`contract_annex_log_good_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `contract_annex_partner`;
CREATE TABLE `contract_annex_partner` (
  `contract_annex_partner_id` int(11) NOT NULL AUTO_INCREMENT,
  `contract_annex_id` int(11) NOT NULL COMMENT 'Link đến phụ lục',
  `partner_object_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'customer: Khách hàng, supplier: Nhà cung cấp',
  `partner_object_id` int(11) DEFAULT NULL COMMENT 'Link với đối tượng ăn theo type',
  `partner_object_form` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'internal : nội bộ, external : bên ngoài, partner : đại lý',
  `address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Địa chỉ',
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Email đối tác',
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Số điện thoại đối tác',
  `tax_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã số thuế',
  `representative` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Người đại diện',
  `hotline` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Số hotline',
  `staff_title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Chức vụ',
  `custom_1` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_2` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_3` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_4` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_5` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_6` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_7` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_8` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_9` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_10` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_11` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_12` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_13` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_14` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_15` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_16` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_17` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_18` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_19` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_20` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`contract_annex_partner_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `contract_annex_payment`;
CREATE TABLE `contract_annex_payment` (
  `contract_annex_payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `contract_annex_id` int(11) NOT NULL COMMENT 'Link với phụ lục',
  `total_amount` decimal(16,3) DEFAULT 0.000 COMMENT 'Tổng giá trị hàng hoá',
  `tax` int(11) DEFAULT 0 COMMENT 'Số % VAT',
  `discount` decimal(16,3) DEFAULT 0.000 COMMENT 'Giảm giá',
  `last_total_amount` decimal(16,3) DEFAULT 0.000 COMMENT '= Tổng tiền - giảm giá + VAT',
  `payment_method_id` int(11) DEFAULT NULL COMMENT 'Phương thức thanh toán',
  `payment_unit_id` int(11) DEFAULT NULL COMMENT 'Đơn vị thanh toán',
  `promotion_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã khuyến mãi',
  `reason_discount` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Lý do giảm giá',
  `custom_1` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_2` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_3` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_4` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_5` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_6` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_7` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_8` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_9` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_10` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_11` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_12` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_13` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_14` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_15` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_16` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_17` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_18` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_19` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_20` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`contract_annex_payment_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `contract_annex_sign_map`;
CREATE TABLE `contract_annex_sign_map` (
  `contract_annex_sign_id` int(11) NOT NULL AUTO_INCREMENT,
  `contract_annex_id` int(11) NOT NULL COMMENT 'Link với HĐ',
  `sign_by` int(11) DEFAULT NULL COMMENT 'Người ký',
  PRIMARY KEY (`contract_annex_sign_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `contract_annex_tag_map`;
CREATE TABLE `contract_annex_tag_map` (
  `contract_annex_tag_map_id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_id` int(11) NOT NULL,
  `contract_annex_id` int(11) NOT NULL COMMENT 'Link với phụ lục',
  PRIMARY KEY (`contract_annex_tag_map_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `contract_approve`;
CREATE TABLE `contract_approve` (
  `contract_appove_id` int(11) NOT NULL AUTO_INCREMENT,
  `contract_id` int(11) NOT NULL COMMENT 'Link với HĐ',
  `status_code_current` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Trạng thái hiện tại',
  `status_code_new` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Trạng thái cần cập nhật',
  `is_approve` tinyint(4) DEFAULT 0 COMMENT '1: Đã duyệt, 0: Chưa duyệt',
  `approve_by` int(11) DEFAULT NULL COMMENT 'Người duyệt',
  `is_deleted` tinyint(4) DEFAULT 0 COMMENT '1: Đã xoá, 0: Chưa xoá',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`contract_appove_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `contract_browse`;
CREATE TABLE `contract_browse` (
  `contract_browse_id` int(11) NOT NULL AUTO_INCREMENT,
  `contract_id` int(11) DEFAULT NULL COMMENT 'Link với HĐ',
  `status_code_now` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Trạng thái HĐ hiện tại',
  `status_code_new` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Trạng thái HĐ cần update',
  `request_by` int(11) DEFAULT NULL COMMENT 'Người yêu cầu duyệt',
  `status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT 'new' COMMENT 'new: Đợi duyệt, confirm: Đã duyệt, refuse: Từ chối',
  `reason_refuse` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Lý do từ chối',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người duyệt or người từ chối theo trạng thái',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`contract_browse_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `contract_care`;
CREATE TABLE `contract_care` (
  `contract_care_id` int(11) NOT NULL AUTO_INCREMENT,
  `contract_id` int(11) DEFAULT NULL,
  `contract_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'expire or soon_expire',
  `status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'success or fail or new',
  `created_by` int(11) DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`contract_care_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `contract_categories`;
CREATE TABLE `contract_categories` (
  `contract_category_id` int(11) NOT NULL AUTO_INCREMENT,
  `contract_category_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã loại hợp đồng',
  `contract_category_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên loại hợp đồng',
  `contract_code_format` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Format mã hợp đồng',
  `type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT 'sell' COMMENT 'sell: Bán, buy: Mua',
  `is_actived` tinyint(4) DEFAULT 1 COMMENT 'Trạng thái',
  `is_deleted` tinyint(4) DEFAULT 0 COMMENT 'Đánh dấu xoá',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`contract_category_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `contract_category_config_status_default`;
CREATE TABLE `contract_category_config_status_default` (
  `contract_category_config_status_default_id` int(11) NOT NULL AUTO_INCREMENT,
  `status_name_vi` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên trạng thái Vi',
  `status_name_en` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên trạng thái En',
  `default_system` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Các giá trị mặc định của hệ thống',
  PRIMARY KEY (`contract_category_config_status_default_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `contract_category_config_tab`;
CREATE TABLE `contract_category_config_tab` (
  `contract_category_config_tab_id` int(11) NOT NULL AUTO_INCREMENT,
  `contract_category_id` int(11) NOT NULL COMMENT 'Link với loại hợp đồng',
  `tab` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'general: Thông tin chung, partner: Đối tác, payment: Thanh toán',
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Ứng với tên trường',
  `type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'int: Là number, date: Date, select: Chọn 1, select_multiple: Chọn nhiều, float: Số lẻ, text_area: Nhập text area, text: Nhập text bình thường, select_insert: Chọn 1 và insert, code: text và unique',
  `key_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên field',
  `is_default` tinyint(4) DEFAULT 1 COMMENT '1: Mặc định, 0: Ko mặc định',
  `is_show` tinyint(4) DEFAULT 1 COMMENT '1: Hiển thị, 0: Ko hiển thị',
  `is_validate` tinyint(4) DEFAULT 1 COMMENT '1: Ràng buộc, 0: Ko ràng buộc',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`contract_category_config_tab_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `contract_category_files`;
CREATE TABLE `contract_category_files` (
  `contract_category_file_id` int(11) NOT NULL AUTO_INCREMENT,
  `contract_category_id` int(11) NOT NULL COMMENT 'Link với loại hợp đồng',
  `link` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Link file',
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên file',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`contract_category_file_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `contract_category_remind`;
CREATE TABLE `contract_category_remind` (
  `contract_category_remind_id` int(11) NOT NULL AUTO_INCREMENT,
  `contract_category_id` int(11) NOT NULL COMMENT 'Link tới loại hợp đồng',
  `remind_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Loại nhắc nhở',
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tiêu đề nhắc nhở',
  `content` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nội dung gửi',
  `recipe` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Công thức < or =',
  `unit` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'D: Ngày, W: Tuần, M: Tháng, Q: Quý, Y: Năm',
  `unit_value` int(11) DEFAULT NULL COMMENT 'Giá trị ăn theo đơn vị',
  `compare_unit` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Dùng để so sánh ăn theo công thức, vd: date_payment, end_date',
  `is_actived` tinyint(4) DEFAULT 1 COMMENT 'Trạng thái',
  `is_deleted` tinyint(4) DEFAULT 0 COMMENT 'Đánh dấu xoá',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`contract_category_remind_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `contract_category_remind_map_method`;
CREATE TABLE `contract_category_remind_map_method` (
  `contract_category_remind_map_method_id` int(11) NOT NULL AUTO_INCREMENT,
  `contract_category_remind_id` int(11) DEFAULT NULL COMMENT 'map với remind id',
  `remind_method` varchar(191) DEFAULT NULL COMMENT 'method nhận: vd staff_notify, email',
  PRIMARY KEY (`contract_category_remind_map_method_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `contract_category_remind_map_receiver`;
CREATE TABLE `contract_category_remind_map_receiver` (
  `contract_category_remind_map_receiver_id` int(11) NOT NULL AUTO_INCREMENT,
  `contract_category_remind_id` int(11) DEFAULT NULL COMMENT 'map với remind id',
  `receiver_by` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Người nhận, lưu key',
  PRIMARY KEY (`contract_category_remind_map_receiver_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `contract_category_remind_type`;
CREATE TABLE `contract_category_remind_type` (
  `contract_category_remind_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `remind_type_code` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã loại nhắc nhở',
  `remind_type_name_vi` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Loại nhắc nhở vi',
  `remind_type_name_en` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Loại nhắc nhở en',
  `limit` varchar(191) DEFAULT NULL COMMENT 'Số nhắc nhở cho phép theo loại nhắc nhở: vd: 1 - chỉ được tạo 1 nhắc nhở cho 1 loại HĐ, n - nhiều',
  PRIMARY KEY (`contract_category_remind_type_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `contract_category_status`;
CREATE TABLE `contract_category_status` (
  `contract_category_status_id` int(11) NOT NULL AUTO_INCREMENT,
  `contract_category_id` int(11) NOT NULL COMMENT 'Link với loại hợp đồng',
  `status_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên trạng thái',
  `status_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã trạng thái',
  `default_system` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Giá trị mặc định của hệ thống',
  `is_approve` tinyint(4) DEFAULT 0 COMMENT '1: Có phê duyệt, 0: Ko phê duyệt',
  `approve_by` int(11) DEFAULT NULL COMMENT 'Người phê duyệt',
  `is_edit_contract` tinyint(4) DEFAULT 1 COMMENT '1: Được chỉnh sửa hđ, 0: Ko chỉnh sửa hđ',
  `is_deleted_contract` tinyint(4) DEFAULT 1 COMMENT '1: Được xoá hợp đồng, 0: Ko được xoá hđ',
  `is_reason` tinyint(4) DEFAULT 1 COMMENT '1: Nhập lý do, 0: Ko nhập lý do',
  `is_show` tinyint(4) DEFAULT 1 COMMENT '1: Hiển thị, 0: Ko hiển thị',
  `is_deleted` tinyint(4) DEFAULT 0 COMMENT '1: Đã xoá, 0: Chưa xoá',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`contract_category_status_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `contract_category_status_approve`;
CREATE TABLE `contract_category_status_approve` (
  `contract_category_status_approve_id` int(11) NOT NULL AUTO_INCREMENT,
  `status_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã trạng thái',
  `approve_by` int(11) DEFAULT NULL COMMENT 'id role duyệt',
  PRIMARY KEY (`contract_category_status_approve_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `contract_category_status_notify`;
CREATE TABLE `contract_category_status_notify` (
  `contract_category_status_notify_id` int(11) NOT NULL AUTO_INCREMENT,
  `contract_category_id` int(11) NOT NULL COMMENT 'Link với loại hợp đồng',
  `status_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã trạng thái',
  `content` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nội dung gửi thông báo',
  `is_created_by` tinyint(4) DEFAULT 0 COMMENT 'Check người tạo',
  `is_performer_by` tinyint(4) DEFAULT 0 COMMENT 'Check người thực hiện',
  `is_signer_by` tinyint(4) DEFAULT 0 COMMENT 'Check người ký',
  `is_follow_by` tinyint(4) DEFAULT 0 COMMENT 'Check người theo dõi',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`contract_category_status_notify_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `contract_category_status_update`;
CREATE TABLE `contract_category_status_update` (
  `contract_category_status_update_id` int(11) NOT NULL AUTO_INCREMENT,
  `status_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã trạng thái từ',
  `status_code_update` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã trạng thái đến',
  PRIMARY KEY (`contract_category_status_update_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `contract_category_tab_default`;
CREATE TABLE `contract_category_tab_default` (
  `contract_category_config_tab_default_id` int(11) NOT NULL AUTO_INCREMENT,
  `tab` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'general: Thông tin chung, partner: Đối tác, payment: Thanh toán',
  `key` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ứng với tên trường',
  `type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'int: Là number, date: Date, select: Chọn 1, select_multiple: Chọn nhiều, float: Số lẻ, text_area: Nhập text area, text: Nhập text bình thường',
  `key_name_vi` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên trường mặc định Vi',
  `key_name_en` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên trường mặc định En',
  `is_show` tinyint(4) DEFAULT 1 COMMENT '1: Hiển thị, 0: Ko hiển thị',
  `is_validate` tinyint(4) DEFAULT 1 COMMENT '1: Ràng buộc, 0: Ko ràng buộc',
  PRIMARY KEY (`contract_category_config_tab_default_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `contract_config`;
CREATE TABLE `contract_config` (
  `key_contract` varchar(191) DEFAULT NULL COMMENT 'field của bảng contracts',
  `is_search` tinyint(1) DEFAULT 1 COMMENT 'field này có search hay không?',
  `is_filter` tinyint(1) DEFAULT 1 COMMENT 'field này có filter hay không?',
  `is_show` tinyint(1) DEFAULT 1 COMMENT 'field này có show ra ds hay không?'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `contract_expected_revenue`;
CREATE TABLE `contract_expected_revenue` (
  `contract_expected_revenue_id` int(11) NOT NULL AUTO_INCREMENT,
  `contract_id` int(11) NOT NULL COMMENT 'Link với HĐ',
  `type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'receipt: Thu, spend: Chi',
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tiêu đề dự kiến thu',
  `contract_category_remind_id` int(11) DEFAULT NULL COMMENT 'Link với cấu hình nội dung gửi',
  `send_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Loại dự kiến thu/chi , after: Sau ngày ký HĐ, hard: Cố định, custom: Tuỳ chọn ngày',
  `send_value` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Giá trị ăn theo loại dự kiến thu',
  `send_value_child` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nếu là cố định thì sẽ là giá trị mỗi %n tháng',
  `amount` decimal(16,3) DEFAULT NULL COMMENT 'Giá trị thanh toán',
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ghi chú',
  `is_deleted` tinyint(4) DEFAULT 0 COMMENT 'Đánh dấu xoá',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`contract_expected_revenue_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `contract_expected_revenue_files`;
CREATE TABLE `contract_expected_revenue_files` (
  `contract_expected_revenue_file_id` int(11) NOT NULL AUTO_INCREMENT,
  `contract_expected_revenue_id` int(11) NOT NULL COMMENT 'Link với dự kiến thu/ chi',
  `file_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên file',
  `link` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên file',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`contract_expected_revenue_file_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `contract_expected_revenue_log`;
CREATE TABLE `contract_expected_revenue_log` (
  `contract_expected_revenue_log_id` int(11) NOT NULL AUTO_INCREMENT,
  `contract_expected_revenue_id` int(11) NOT NULL COMMENT 'Link với dự kiến thu/ chi',
  `contract_id` int(11) DEFAULT NULL COMMENT 'Link với HĐ',
  `date_send` date DEFAULT NULL COMMENT 'Ngày gửi',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`contract_expected_revenue_log_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `contract_files`;
CREATE TABLE `contract_files` (
  `contract_file_id` int(11) NOT NULL AUTO_INCREMENT,
  `contract_id` int(11) NOT NULL COMMENT 'Link với HĐ',
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên hồ sơ',
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ghi chú',
  `is_deleted` tinyint(4) DEFAULT 0 COMMENT 'Đánh dấu xoá',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`contract_file_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `contract_file_details`;
CREATE TABLE `contract_file_details` (
  `contract_file_detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `contract_file_id` int(11) NOT NULL COMMENT 'Link với file HĐ',
  `file_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên file',
  `link` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Link file',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`contract_file_detail_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `contract_follow_map`;
CREATE TABLE `contract_follow_map` (
  `contract_follow_map_id` int(11) NOT NULL AUTO_INCREMENT,
  `contract_id` int(11) NOT NULL COMMENT 'Link với HĐ',
  `follow_by` int(11) NOT NULL COMMENT 'Link với người theo dõi',
  PRIMARY KEY (`contract_follow_map_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `contract_goods`;
CREATE TABLE `contract_goods` (
  `contract_goods_id` int(11) NOT NULL AUTO_INCREMENT,
  `contract_id` int(11) NOT NULL COMMENT 'Link với mã HĐ',
  `object_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'product: Sản phẩm, service: Dịch vụ, service_card: Thẻ dịch vụ',
  `object_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên đối tượng',
  `object_id` int(11) DEFAULT NULL COMMENT 'Id đối tượng',
  `object_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã đối tượng',
  `unit_id` int(11) DEFAULT NULL COMMENT 'Đơn vị tính',
  `price` decimal(16,3) DEFAULT NULL COMMENT 'Giá',
  `quantity` int(11) DEFAULT NULL COMMENT 'Số lượng',
  `discount` decimal(16,3) DEFAULT NULL COMMENT 'Giảm giá',
  `tax` int(11) DEFAULT NULL COMMENT 'Thuế',
  `amount` decimal(16,3) DEFAULT NULL COMMENT 'Thành tiền ',
  `note` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ghi chú',
  `order_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã HĐ',
  `staff_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nhân viên phục vụ',
  `is_deleted` tinyint(4) DEFAULT 0 COMMENT 'Đánh dấu xoá',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  `is_applied_kpi` tinyint(1) DEFAULT 1 COMMENT '1: Được áp dụng KPI, 0: Không áp dụng KPI',
  PRIMARY KEY (`contract_goods_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `contract_logs`;
CREATE TABLE `contract_logs` (
  `contract_log_id` int(11) NOT NULL AUTO_INCREMENT,
  `contract_id` int(11) NOT NULL COMMENT 'Link với HĐ',
  `change_object_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'info: Thông tin HĐ, expected_revenue: Dự kiến thu chi, receipt: Thu, payment: Chi, goods: Hàng hoá\n',
  `note` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ghi chú',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`contract_log_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `contract_log_expected_revenue`;
CREATE TABLE `contract_log_expected_revenue` (
  `contract_log_expected_revenue_id` int(11) NOT NULL AUTO_INCREMENT,
  `contract_log_id` int(11) NOT NULL COMMENT 'Link với log HĐ',
  `contract_expected_revenue_id` int(11) NOT NULL COMMENT 'Link với dự kiến thu chi',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`contract_log_expected_revenue_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `contract_log_files`;
CREATE TABLE `contract_log_files` (
  `contract_log_file_id` int(11) NOT NULL AUTO_INCREMENT,
  `contract_log_id` int(11) NOT NULL COMMENT 'Link với log HĐ',
  `contract_file_id` int(11) NOT NULL COMMENT 'Link với file HĐ',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`contract_log_file_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `contract_log_general`;
CREATE TABLE `contract_log_general` (
  `contract_log_general_id` int(11) NOT NULL AUTO_INCREMENT,
  `contract_log_id` int(11) NOT NULL COMMENT 'Link với log HĐ',
  `contract_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên HĐ',
  `contract_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã HĐ',
  `sign_date` date DEFAULT NULL COMMENT 'Ngày ký HĐ',
  `sign_by` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Người ký',
  `tag` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Hash tag',
  `performer_by` int(11) DEFAULT NULL COMMENT 'Người thực hiện',
  `effective_date` date DEFAULT NULL COMMENT 'Ngày có hiệu lực',
  `expired_date` date DEFAULT NULL COMMENT 'Ngày hết hiệu lực',
  `follow_by` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Người theo dõi vd:1,2,3',
  `is_renew` tinyint(4) DEFAULT 0 COMMENT '1: Tự động gia hạn, 0: Ko tự động gia hạn',
  `number_day_renew` int(11) DEFAULT 0 COMMENT 'Số ngày gia hạn trước',
  `is_created_ticket` tinyint(4) DEFAULT 0 COMMENT '1: Tự động tạo ticket, 0: Ko tự động tạo',
  `status_code_created_ticket` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Trạng thái tự động tạo ticket',
  `warranty_start_date` date DEFAULT NULL COMMENT 'Ngày bắt đầu bảo hành',
  `warranty_end_date` date DEFAULT NULL COMMENT 'Ngày kết thúc bảo hành',
  `content` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nội dung',
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ghi chú',
  `status_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Trạng thái hợp đồng',
  `is_value_goods` tinyint(4) DEFAULT 0 COMMENT '1: Lấy theo giá trị hàng hoá, 0: ko theo giá trị',
  `custom_1` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_2` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_3` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_4` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_5` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_6` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_7` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_8` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_9` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_10` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_11` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_12` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_13` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_14` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_15` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_16` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_17` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_18` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_19` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_20` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contract_name_new` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên HĐ',
  `contract_code_new` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã HĐ new',
  `sign_date_new` date DEFAULT NULL COMMENT 'Ngày ký HĐ',
  `sign_by_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Người ký',
  `performer_by_new` int(11) DEFAULT NULL COMMENT 'Người thực hiện',
  `effective_date_new` date DEFAULT NULL COMMENT 'Ngày có hiệu lực',
  `expired_date_new` date DEFAULT NULL COMMENT 'Ngày hết hiệu lực',
  `follow_by_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Người theo dõi vd:1,2,3',
  `is_renew_new` tinyint(4) DEFAULT 0 COMMENT '1: Tự động gia hạn, 0: Ko tự động gia hạn',
  `number_day_renew_new` int(11) DEFAULT 0 COMMENT 'Số ngày gia hạn trước',
  `is_created_ticket_new` tinyint(4) DEFAULT 0 COMMENT '1: Tự động tạo ticket, 0: Ko tự động tạo',
  `status_code_created_ticket_new` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Trạng thái tự động tạo ticket',
  `warranty_start_date_new` date DEFAULT NULL COMMENT 'Ngày bắt đầu bảo hành',
  `warranty_end_date_new` date DEFAULT NULL COMMENT 'Ngày kết thúc bảo hành',
  `content_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nội dung',
  `note_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ghi chú',
  `status_code_new` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Trạng thái hợp đồng',
  `is_value_goods_new` tinyint(4) DEFAULT 0 COMMENT '1: Lấy theo giá trị hàng hoá, 0: ko theo giá trị',
  `tag_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Hash tag new',
  `custom_1_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_2_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_3_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_4_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_5_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_6_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_7_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_8_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_9_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_10_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_11_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_12_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_13_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_14_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_15_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_16_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_17_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_18_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_19_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_20_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`contract_log_general_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `contract_log_goods`;
CREATE TABLE `contract_log_goods` (
  `contract_log_goods_id` int(11) NOT NULL AUTO_INCREMENT,
  `contract_log_id` int(11) NOT NULL COMMENT 'Link với log HĐ',
  `contract_godds_id` int(11) NOT NULL COMMENT 'Link với hàng hoá',
  `object_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'product: Sản phẩm, service: Dịch vụ, service_card: Thẻ dịch vụ',
  `object_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên đối tượng',
  `object_id` int(11) DEFAULT NULL COMMENT 'Id đối tượng',
  `object_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã đối tượng',
  `price` decimal(16,3) DEFAULT NULL COMMENT 'Giá',
  `quantity` int(11) DEFAULT NULL COMMENT 'Số lượng',
  `discount` decimal(16,3) DEFAULT NULL COMMENT 'Giảm giá',
  `tax` int(11) DEFAULT NULL COMMENT 'Thuế',
  `amount` decimal(16,3) DEFAULT NULL COMMENT 'Thành tiền ',
  `staff_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nhân viên phục vụ',
  `note` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ghi chú',
  `object_type_new` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'product: Sản phẩm, service: Dịch vụ, service_card: Thẻ dịch vụ',
  `object_name_new` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên đối tượng',
  `object_id_new` int(11) DEFAULT NULL COMMENT 'Id đối tượng',
  `object_code_new` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã đối tượng',
  `price_new` decimal(16,3) DEFAULT NULL COMMENT 'Giá',
  `quantity_new` int(11) DEFAULT NULL COMMENT 'Số lượng',
  `discount_new` decimal(16,3) DEFAULT NULL COMMENT 'Giảm giá',
  `tax_new` int(11) DEFAULT NULL COMMENT 'Thuế',
  `amount_new` decimal(16,3) DEFAULT NULL COMMENT 'Thành tiền ',
  `staff_id_new` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nhân viên phục vụ',
  `note_new` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ghi chú',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`contract_log_goods_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `contract_log_partner`;
CREATE TABLE `contract_log_partner` (
  `contract_log_partner_id` int(11) NOT NULL AUTO_INCREMENT,
  `contract_log_id` int(11) NOT NULL COMMENT 'Link đến log HĐ',
  `partner_object_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'customer: Khách hàng, supplier: Nhà cung cấp',
  `partner_object_id` int(11) DEFAULT NULL COMMENT 'Link với đối tượng ăn theo type',
  `address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Địa chỉ',
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Email đối tác',
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Số điện thoại đối tác',
  `tax_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã số thuế',
  `representative` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Người đại diện',
  `hotline` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Số hotline',
  `staff_title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Chức vụ',
  `custom_1` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_2` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_3` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_4` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_5` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_6` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_7` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_8` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_9` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_10` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_11` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_12` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_13` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_14` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_15` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_16` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_17` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_18` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_19` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_20` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `partner_object_type_new` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'customer: Khách hàng, supplier: Nhà cung cấp',
  `partner_object_id_new` int(11) DEFAULT NULL COMMENT 'Link với đối tượng ăn theo type',
  `address_new` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Địa chỉ',
  `email_new` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Email đối tác',
  `phone_new` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Số điện thoại đối tác',
  `tax_code_new` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã số thuế',
  `representative_new` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Người đại diện',
  `hotline_new` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Số hotline',
  `staff_title_new` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Chức vụ',
  `custom_1_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_2_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_3_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_4_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_5_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_6_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_7_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_8_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_9_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_10_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_11_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_12_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_13_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_14_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_15_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_16_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_17_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_18_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_19_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_20_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`contract_log_partner_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `contract_log_payment`;
CREATE TABLE `contract_log_payment` (
  `contract_log_payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `contract_log_id` int(11) NOT NULL COMMENT 'Link với log hợp đồng',
  `total_amount` decimal(16,3) DEFAULT NULL COMMENT 'Tổng giá trị hàng hoá',
  `tax` int(11) DEFAULT NULL COMMENT 'Số % VAT',
  `discount` decimal(16,3) DEFAULT NULL COMMENT 'Giảm giá',
  `last_total_amount` decimal(16,3) DEFAULT NULL COMMENT '= Tổng tiền - giảm giá + VAT',
  `payment_method_id` int(11) DEFAULT NULL COMMENT 'Phương thức thanh toán',
  `payment_unit_id` int(11) DEFAULT NULL COMMENT 'Đơn vị thanh toán',
  `promotion_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã giảm giá',
  `reason_discount` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Lý do giảm giá',
  `custom_1` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_2` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_3` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_4` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_5` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_6` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_7` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_8` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_9` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_10` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_11` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_12` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_13` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_14` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_15` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_16` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_17` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_18` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_19` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_20` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_amount_new` decimal(16,3) DEFAULT NULL COMMENT 'Tổng giá trị hàng hoá',
  `tax_new` int(11) DEFAULT NULL COMMENT 'Số % VAT',
  `discount_new` decimal(16,3) DEFAULT NULL COMMENT 'Giảm giá',
  `last_total_amount_new` decimal(16,3) DEFAULT NULL COMMENT '= Tổng tiền - giảm giá + VAT',
  `payment_method_id_new` int(11) DEFAULT NULL COMMENT 'Phương thức thanh toán',
  `payment_unit_id_new` int(11) DEFAULT NULL COMMENT 'Đơn vị thanh toán',
  `promotion_code_new` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã giảm giá',
  `reason_discount_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Lý do giảm giá',
  `custom_1_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_2_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_3_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_4_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_5_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_6_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_7_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_8_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_9_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_10_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_11_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_12_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_13_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_14_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_15_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_16_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_17_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_18_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_19_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_20_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`contract_log_payment_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `contract_log_receipt_spend`;
CREATE TABLE `contract_log_receipt_spend` (
  `contract_log_receipt_spend_id` int(11) NOT NULL AUTO_INCREMENT,
  `contract_log_id` int(11) NOT NULL COMMENT 'Link với log hợp đồng',
  `object_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'receipt: Đơt thu, payment: Đợt chi',
  `object_id` int(11) DEFAULT NULL COMMENT 'Link với đối tượng ăn theo type',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`contract_log_receipt_spend_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `contract_map_order`;
CREATE TABLE `contract_map_order` (
  `contract_map_order_id` int(11) NOT NULL AUTO_INCREMENT,
  `contract_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Link với mã HĐ',
  `order_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Link với mã ĐH',
  `source` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT 'contract' COMMENT 'contract: Hợp đồng, order: Đơn hàng',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`contract_map_order_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `contract_notify_config`;
CREATE TABLE `contract_notify_config` (
  `contract_notify_config_id` int(11) NOT NULL AUTO_INCREMENT,
  `contract_notify_config_code` varchar(191) DEFAULT NULL COMMENT 'code để bắt case',
  `contract_notify_config_name_vi` varchar(191) DEFAULT NULL COMMENT 'name vi notify',
  `contract_notify_config_name_en` varchar(191) DEFAULT NULL COMMENT 'name en notify',
  `contract_notify_config_content` varchar(191) DEFAULT NULL COMMENT 'content notify',
  `is_created_by` tinyint(4) DEFAULT 0 COMMENT 'Check người tạo',
  `is_performer_by` tinyint(4) DEFAULT 0 COMMENT 'Check người thực hiện',
  `is_signer_by` tinyint(4) DEFAULT 0 COMMENT 'Check người ký',
  `is_follow_by` tinyint(4) DEFAULT 0 COMMENT 'Check người theo dõi',
  `detail_action_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên button tương tác',
  `detail_action` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Action khi click ở app',
  `detail_action_params` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Param bổ sung',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`contract_notify_config_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `contract_notify_config_method_map`;
CREATE TABLE `contract_notify_config_method_map` (
  `contract_notify_config_method_map_id` int(11) NOT NULL AUTO_INCREMENT,
  `contract_notify_config_id` int(11) DEFAULT NULL COMMENT 'id link',
  `notify_method` varchar(191) DEFAULT NULL COMMENT 'notify or email',
  PRIMARY KEY (`contract_notify_config_method_map_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `contract_overview_logs`;
CREATE TABLE `contract_overview_logs` (
  `contract_overview_log_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `contract_id` int(11) DEFAULT NULL,
  `contract_overview_type` varchar(191) DEFAULT NULL COMMENT 'new, recare, renew',
  `effective_date` datetime DEFAULT NULL COMMENT 'ngày có hiệu lục tại thời điểm log',
  `performer_by` int(11) DEFAULT NULL COMMENT 'người thực hiện tại thời điểm log',
  `total_amount` decimal(16,3) DEFAULT NULL COMMENT 'giá trị hợp đồng',
  `created_at` datetime DEFAULT NULL COMMENT 'Thoi gian tao',
  `created_by` int(11) DEFAULT NULL COMMENT 'Nguoi tao',
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Nguoi cap nhat',
  PRIMARY KEY (`contract_overview_log_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `contract_partner`;
CREATE TABLE `contract_partner` (
  `contract_partner_id` int(11) NOT NULL AUTO_INCREMENT,
  `contract_id` int(11) NOT NULL COMMENT 'Link đến HĐ',
  `partner_object_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'customer: Khách hàng, supplier: Nhà cung cấp',
  `partner_object_form` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'internal : nội bộ, external : bên ngoài, partner : đại lý',
  `partner_object_id` int(11) DEFAULT NULL COMMENT 'Link với đối tượng ăn theo type',
  `address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Địa chỉ',
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Email đối tác',
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Số điện thoại đối tác',
  `tax_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã số thuế',
  `representative` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Người đại diện',
  `hotline` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Số hotline',
  `staff_title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Chức vụ',
  `custom_1` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_2` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_3` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_4` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_5` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_6` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_7` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_8` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_9` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_10` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_11` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_12` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_13` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_14` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_15` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_16` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_17` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_18` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_19` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_20` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`contract_partner_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `contract_payment`;
CREATE TABLE `contract_payment` (
  `contract_payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `contract_id` int(11) NOT NULL COMMENT 'Link với hợp đồng',
  `total_amount` decimal(16,3) DEFAULT 0.000 COMMENT 'Tổng giá trị hàng hoá',
  `tax` int(11) DEFAULT 0 COMMENT 'Số % VAT',
  `discount` decimal(16,3) DEFAULT 0.000 COMMENT 'Giảm giá',
  `last_total_amount` decimal(16,3) DEFAULT 0.000 COMMENT '= Tổng tiền - giảm giá + VAT',
  `payment_method_id` int(11) DEFAULT NULL COMMENT 'Phương thức thanh toán',
  `payment_unit_id` int(11) DEFAULT NULL COMMENT 'Đơn vị thanh toán',
  `promotion_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã khuyến mãi',
  `reason_discount` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Lý do giảm giá',
  `custom_1` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_2` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_3` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_4` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_5` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_6` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_7` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_8` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_9` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_10` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_11` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_12` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_13` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_14` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_15` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_16` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_17` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_18` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_19` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_20` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`contract_payment_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `contract_receipt`;
CREATE TABLE `contract_receipt` (
  `contract_receipt_id` int(11) NOT NULL AUTO_INCREMENT,
  `contract_id` int(11) NOT NULL COMMENT 'Link với HĐ',
  `content` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nội dung thu',
  `collection_date` date DEFAULT NULL COMMENT 'Ngày thu tiền',
  `collection_by` int(11) DEFAULT NULL COMMENT 'Người thu',
  `prepayment` decimal(16,3) DEFAULT NULL COMMENT 'Số tiền dự thu',
  `amount_remain` decimal(16,3) DEFAULT NULL COMMENT 'Tiền còn lại',
  `total_amount_receipt` decimal(16,3) DEFAULT NULL COMMENT 'Tổng tiền thanh toán',
  `invoice_date` date DEFAULT NULL COMMENT 'Ngày xuất hoá đơn',
  `invoice_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Số hoá đơn',
  `receipt_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Link với mã thanh toán',
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ghi chú',
  `reason` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Lý do huỷ',
  `is_deleted` tinyint(4) DEFAULT 0 COMMENT 'Đánh dấu xoá',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`contract_receipt_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `contract_receipt_details`;
CREATE TABLE `contract_receipt_details` (
  `contract_receipt_detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `contract_receipt_id` int(11) DEFAULT NULL COMMENT 'Link với chi tiết thu',
  `amount_receipt` decimal(16,3) DEFAULT NULL COMMENT 'Tiền thanh toán',
  `payment_method_id` int(11) DEFAULT NULL COMMENT 'Phương thức thanh toán',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`contract_receipt_detail_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `contract_receipt_files`;
CREATE TABLE `contract_receipt_files` (
  `contract_receipt_file_id` int(11) NOT NULL AUTO_INCREMENT,
  `contract_receipt_id` int(11) NOT NULL COMMENT 'Link với đợt thu',
  `file_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên file',
  `link` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên file',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`contract_receipt_file_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `contract_role_data_config`;
CREATE TABLE `contract_role_data_config` (
  `contract_role_data_config_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_group_id` int(11) DEFAULT NULL COMMENT 'id role group',
  `role_data_type` varchar(191) DEFAULT NULL COMMENT 'all, branch, department, own',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`contract_role_data_config_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `contract_sign_map`;
CREATE TABLE `contract_sign_map` (
  `contract_sign_id` int(11) NOT NULL AUTO_INCREMENT,
  `contract_id` int(11) NOT NULL COMMENT 'Link với HĐ',
  `sign_by` int(11) DEFAULT NULL COMMENT 'Người ký',
  PRIMARY KEY (`contract_sign_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `contract_spend`;
CREATE TABLE `contract_spend` (
  `contract_spend_id` int(11) NOT NULL AUTO_INCREMENT,
  `contract_id` int(11) NOT NULL COMMENT 'Link với HĐ',
  `content` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nội dung chi',
  `spend_date` date DEFAULT NULL COMMENT 'Ngày chi tiền',
  `spend_by` int(11) DEFAULT NULL COMMENT 'Người chi tiền',
  `prepayment` decimal(16,3) DEFAULT NULL COMMENT 'Số tiền dự chi',
  `amount_remain` decimal(16,3) DEFAULT NULL COMMENT 'Tiền còn lại',
  `amount_spend` decimal(16,3) DEFAULT NULL COMMENT 'Tiền thanh toán',
  `invoice_date` date DEFAULT NULL COMMENT 'Ngày xuất hoá đơn',
  `invoice_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Số hoá đơn',
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ghi chú',
  `payment_method_id` int(11) DEFAULT NULL COMMENT 'Phương thức thanh toán',
  `payment_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Link với mã phiếu chi',
  `reason` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Lý do xoá',
  `is_deleted` tinyint(4) DEFAULT 0 COMMENT 'Đánh dấu xoá',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`contract_spend_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `contract_spend_files`;
CREATE TABLE `contract_spend_files` (
  `contract_spend_file_id` int(11) NOT NULL AUTO_INCREMENT,
  `contract_spend_id` int(11) NOT NULL COMMENT 'Link với đợt chi',
  `file_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên file',
  `link` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên file',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`contract_spend_file_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `contract_staff_queue`;
CREATE TABLE `contract_staff_queue` (
  `contract_staff_queue_id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID tự tăng',
  `staff_notification_detail_id` bigint(20) DEFAULT NULL COMMENT 'Noi dung gui notification',
  `tenant_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ID xac dinh brand',
  `contract_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `staff_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'id nhân viên',
  `staff_notification_avatar` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Avatar của thông báo',
  `staff_notification_title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Title',
  `staff_notification_message` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nội dung thông báo',
  `send_at` datetime NOT NULL COMMENT 'Thoi gian hen gio',
  `is_actived` tinyint(4) DEFAULT 0,
  `is_send` tinyint(1) DEFAULT 0 COMMENT 'Đanh dau da gui',
  `created_at` datetime DEFAULT NULL COMMENT 'Thoi gian tao',
  `created_by` int(11) DEFAULT NULL COMMENT 'Nguoi tao',
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Nguoi cap nhat',
  PRIMARY KEY (`contract_staff_queue_id`) USING BTREE,
  KEY `notification_detail_id` (`staff_notification_detail_id`) USING BTREE,
  KEY `tenant_id_is_send` (`tenant_id`,`is_send`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Notification hẹn giờ gửi';


DROP TABLE IF EXISTS `contract_tags`;
CREATE TABLE `contract_tags` (
  `contract_tag_id` int(11) NOT NULL AUTO_INCREMENT,
  `keyword` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Từ khoá của hashtag',
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên hashtag',
  `is_deleted` tinyint(4) DEFAULT 0 COMMENT 'Đanh dấu xoá',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`contract_tag_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `contract_tag_map`;
CREATE TABLE `contract_tag_map` (
  `contract_tag_map_id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_id` int(11) NOT NULL,
  `contract_id` int(11) NOT NULL COMMENT 'Link với HĐ',
  PRIMARY KEY (`contract_tag_map_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `country_iso`;
CREATE TABLE `country_iso` (
  `country_iso_id` int(11) NOT NULL AUTO_INCREMENT,
  `country_iso` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_iso3` char(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `calling_code` int(11) NOT NULL,
  `country_name` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`country_iso_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `cpo_customer_care`;
CREATE TABLE `cpo_customer_care` (
  `customer_care_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_lead_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Link tới mã KH tiềm năng',
  `care_type` enum('call','chat','email','message','meeting','other') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Loại chăm sóc',
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nội dung chăm sóc',
  `created_by` int(11) NOT NULL COMMENT 'Người chăm sóc',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `object_id` int(11) DEFAULT NULL COMMENT 'Id đối tượng ăn theo loại chăm sóc',
  PRIMARY KEY (`customer_care_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `cpo_customer_contact`;
CREATE TABLE `cpo_customer_contact` (
  `customer_contact_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_lead_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Link tới mã khách hàng',
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên người liên hệ',
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Số điện thoại liên hệ',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Email liên hệ',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Địa chỉ',
  PRIMARY KEY (`customer_contact_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `cpo_customer_email`;
CREATE TABLE `cpo_customer_email` (
  `customer_email_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_lead_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Link tới mã khách hàng',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`customer_email_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `cpo_customer_fanpage`;
CREATE TABLE `cpo_customer_fanpage` (
  `customer_fanpage_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_lead_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Link tới mã khách hàng',
  `fanpage` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`customer_fanpage_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `cpo_customer_lead`;
CREATE TABLE `cpo_customer_lead` (
  `customer_lead_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_lead_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mã khách hàng',
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Họ và tên',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Email',
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Số điện thoại',
  `gender` enum('female','male','other') COLLATE utf8mb4_unicode_ci DEFAULT 'other' COMMENT 'Giới tính',
  `birthday` datetime DEFAULT NULL COMMENT 'Ngày sinh',
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Địa chỉ',
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ảnh đại diện',
  `tag_id` int(11) DEFAULT NULL,
  `pipeline_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mã pipeline',
  `journey_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mã hành trình khách hàng',
  `customer_type` enum('personal','business') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'personal' COMMENT 'Cá nhân hay doanh nghiệp',
  `hotline` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fanpage` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Link fanpage',
  `zalo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Link zalo',
  `is_deleted` tinyint(4) DEFAULT 0,
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  `tax_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã số thuế',
  `representative` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Người đại diện',
  `customer_source` int(11) DEFAULT NULL COMMENT 'Nguồn khách hàng',
  `business_clue` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Đầu mối doanh nghiệp',
  `is_convert` tinyint(4) DEFAULT 0 COMMENT 'Chuyển đổi khách hàng',
  `branch_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `assign_by` int(11) DEFAULT NULL COMMENT 'id Người phân công',
  `sale_id` int(11) DEFAULT NULL COMMENT 'id Nhân viên (staff_id) được phân công',
  `date_revoke` datetime DEFAULT NULL COMMENT 'Hạn cuối để lead chuyển đổi (thời điểm phân công +thời gian tối đa lead chuyển đổi trong pipeline) ',
  `province_id` int(11) DEFAULT NULL COMMENT 'Tỉnh thành',
  `district_id` int(11) DEFAULT NULL COMMENT 'Quận huyện',
  `deal_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Link deal được tạo từ lead',
  `custom_1` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_2` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_3` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_4` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_5` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_6` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_7` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_8` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_9` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_10` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `convert_object_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Loại chuyển đổi: KH / Deal ?',
  `convert_object_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã tương ứng loại chuyển đổi',
  PRIMARY KEY (`customer_lead_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `cpo_customer_lead_custom_define`;
CREATE TABLE `cpo_customer_lead_custom_define` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Key ứng với tên trường custom',
  `type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'text: kiểu text, boolean: kiểu true false',
  `title_vi` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tiêu đề vi',
  `title_en` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tiêu đề en',
  PRIMARY KEY (`key`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `cpo_customer_lead_row_gsheet`;
CREATE TABLE `cpo_customer_lead_row_gsheet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `number_row_last` int(11) DEFAULT NULL COMMENT 'Số hàng cuối insert googleSheet',
  `id_google_sheet` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Id cấu hình nguồn dữ liệu khách hàng tiềm năng',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `cpo_customer_phone`;
CREATE TABLE `cpo_customer_phone` (
  `customer_phone_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_lead_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Link tới mã khách hàng',
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Số điện thoại',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`customer_phone_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `cpo_customer_registers`;
CREATE TABLE `cpo_customer_registers` (
  `customer_register_id` int(11) NOT NULL AUTO_INCREMENT,
  `register_source` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nguồn đăng ký',
  `register_object_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `channel_id` int(11) DEFAULT NULL COMMENT 'Kênh đăng ký',
  `customer_lead_id` int(11) DEFAULT NULL COMMENT 'Link tới KH tiềm năng',
  `gender` enum('male','female') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Họ tên',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Email',
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Số điện thoại',
  `avatar` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ảnh đại diện',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`customer_register_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `cpo_deals`;
CREATE TABLE `cpo_deals` (
  `deal_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `deal_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deal_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên deal',
  `customer_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'nếu type = lead, customer_code là customer_lead_code từ bảng customer_lead, nếu type = customer, customer_code là customer_code từ bảng customers',
  `total` decimal(16,3) DEFAULT NULL,
  `discount` decimal(16,3) DEFAULT NULL,
  `amount` decimal(16,3) DEFAULT NULL,
  `probability` decimal(16,3) DEFAULT NULL COMMENT 'Xác xuất % ',
  `owner` int(11) DEFAULT NULL COMMENT 'nhân viên sở hữu deal',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `pipeline_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `journey_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deal_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_source_id` int(11) DEFAULT 1,
  `voucher_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_member` decimal(16,0) DEFAULT NULL,
  `customer_contact_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã liên hệ',
  `is_deleted` tinyint(1) DEFAULT 0,
  `closing_date` date DEFAULT NULL COMMENT 'ngày kết thúc dự kiến ',
  `closing_due_date` date DEFAULT NULL COMMENT 'ngày kết thúc thực tế',
  `reason_lose_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'lý do thất bại',
  `tag` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'multiple, ví dụ: 1,2,3 hoặc 1 hoặc 1,2',
  `type_customer` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'customer' COMMENT 'loại KH của deal, lead hoặc customer',
  `branch_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deal_type_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Loại deal được tạo',
  `deal_type_object_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'id theo nguồn deal được tạo',
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'số điện thoại của lead hoặc customer',
  `contract_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã hợp đồng',
  `sale_id` int(11) DEFAULT NULL COMMENT 'nhân viên được phân bổ deal',
  `date_revoke` datetime DEFAULT NULL COMMENT 'hạn cuối để deal chuyển dổi',
  PRIMARY KEY (`deal_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `cpo_deal_care`;
CREATE TABLE `cpo_deal_care` (
  `deal_care_id` int(11) NOT NULL AUTO_INCREMENT,
  `deal_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Link tới mã KH tiềm năng',
  `care_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Loại chăm sóc ''call'',''chat'',''email''',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nội dung chăm sóc',
  `created_by` int(11) NOT NULL COMMENT 'Người chăm sóc',
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `object_id` int(11) DEFAULT NULL COMMENT 'Id đối tượng ăn theo loại chăm sóc',
  PRIMARY KEY (`deal_care_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `cpo_deal_details`;
CREATE TABLE `cpo_deal_details` (
  `deal_detail_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `deal_code` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `object_id` int(11) NOT NULL COMMENT 'id của dich vụ , sản phẩm hoac thẻ dịch vụ tuỳ objecttype ',
  `object_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'tên của dich vụ , sản phẩm hoac thẻ dịch vụ tuỳ objecttype ',
  `object_type` enum('service_card','service','product','member_card','product_gift','service_gift','service_card_gift') CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `object_code` varchar(1024) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `price` decimal(16,3) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `discount` decimal(16,3) DEFAULT NULL,
  `amount` decimal(16,3) DEFAULT NULL,
  `voucher_code` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT NULL,
  `is_deleted` tinyint(4) DEFAULT 0,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`deal_detail_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `cpo_deal_type`;
CREATE TABLE `cpo_deal_type` (
  `deal_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `deal_type_code` varchar(191) DEFAULT NULL,
  `deal_type_name_vi` varchar(191) DEFAULT NULL,
  `deal_type_name_en` varchar(191) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`deal_type_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `cpo_journey`;
CREATE TABLE `cpo_journey` (
  `journey_id` int(11) NOT NULL AUTO_INCREMENT,
  `pipeline_id` int(11) NOT NULL COMMENT 'Link tới pipeline',
  `pipeline_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mã pipeline',
  `journey_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên hành trình',
  `journey_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mã định danh',
  `journey_updated` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Hành trình được cập nhật 1,2,3',
  `position` int(11) NOT NULL COMMENT 'Vị trí hiển thị',
  `default_system` enum('new','fail','win') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Giá trị mặc định của hệ thống',
  `is_actived` tinyint(4) DEFAULT 1,
  `is_deleted` tinyint(4) DEFAULT 0,
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  `is_deal_created` tinyint(1) DEFAULT 0 COMMENT 'Có tạo deal hay không. 1 có, 0 không',
  `is_contract_created` tinyint(1) DEFAULT NULL COMMENT 'Có tạo hợp đồng hay không. 1 có, 0 không',
  PRIMARY KEY (`journey_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `cpo_map_customer_tag`;
CREATE TABLE `cpo_map_customer_tag` (
  `map_customer_tag_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_lead_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tag_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`map_customer_tag_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `cpo_pipelines`;
CREATE TABLE `cpo_pipelines` (
  `pipeline_id` int(11) NOT NULL AUTO_INCREMENT,
  `pipeline_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pipeline_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_default` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'Đặt làm pipeline mặc định',
  `is_deleted` tinyint(4) NOT NULL DEFAULT 0,
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  `pipeline_category_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã pipeline category',
  `time_revoke_lead` int(11) DEFAULT NULL COMMENT 'Thời gian tối đa để lead chuyển đổi, nếu hết sẽ thu hồi lead lại',
  `owner_id` int(11) DEFAULT NULL COMMENT 'Id chủ sở hữu (nhân viên)',
  PRIMARY KEY (`pipeline_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `cpo_pipeline_categories`;
CREATE TABLE `cpo_pipeline_categories` (
  `pipeline_category_id` int(11) NOT NULL AUTO_INCREMENT,
  `pipeline_category_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mã pipeline category',
  `pipeline_category_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_actived` tinyint(4) DEFAULT 1,
  `is_deleted` tinyint(4) DEFAULT 0,
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`pipeline_category_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `cpo_pipeline_journey_default`;
CREATE TABLE `cpo_pipeline_journey_default` (
  `pipeline_journey_default_id` int(11) NOT NULL AUTO_INCREMENT,
  `pipeline_journey_default_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pipeline_journey_default_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên hành trình mặc định',
  `pipeline_category_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã danh mục pipeline',
  `position` int(11) DEFAULT NULL COMMENT 'Vị trí hiển thị',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`pipeline_journey_default_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `cpo_tag`;
CREATE TABLE `cpo_tag` (
  `tag_id` int(11) NOT NULL AUTO_INCREMENT,
  `type` enum('tag','chatbot') COLLATE utf8mb4_unicode_ci NOT NULL,
  `keyword` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_deleted` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`tag_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `customers`;
CREATE TABLE `customers` (
  `customer_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `branch_id` int(11) DEFAULT NULL,
  `customer_group_id` int(11) DEFAULT NULL,
  `full_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Họ và Tên',
  `birthday` datetime DEFAULT NULL COMMENT 'Ngày sinh',
  `gender` enum('male','female','other') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'giới tính',
  `phone1` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'số điện thoại1',
  `phone2` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'số điện thoại2',
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'email1',
  `facebook` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'facebook',
  `province_id` int(11) DEFAULT NULL,
  `district_id` int(11) DEFAULT NULL,
  `ward_id` int(11) DEFAULT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Dia chi',
  `customer_source_id` int(10) unsigned DEFAULT NULL COMMENT 'Nguồn khách hàng',
  `customer_refer_id` int(11) DEFAULT NULL COMMENT 'mã khách giới thiệu',
  `customer_avatar` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Link avatar',
  `zalo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ghi chú',
  `point_rank` double(16,2) NOT NULL COMMENT 'point tích lũy trong kỳ',
  `account_money` decimal(10,0) DEFAULT NULL COMMENT 'số tiền tài khoản còn lại',
  `customer_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `point` double(16,2) DEFAULT 0.00,
  `member_level_id` int(11) DEFAULT 1,
  `password` char(128) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '$2y$12$ePB/hO4n6SSEsmbBjjhAHunkh/zZhLodzFspa4rEW18GBrL538zbO',
  `phone_verified` tinyint(1) DEFAULT 0 COMMENT 'Đã xác thực số điện thoại',
  `date_last_visit` datetime DEFAULT NULL COMMENT 'ngày đến gần nhất',
  `FbId` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ZaloId` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postcode` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `site_id` int(11) DEFAULT NULL,
  `point_balance` double(16,2) DEFAULT NULL,
  `is_updated` tinyint(1) DEFAULT 0 COMMENT 'Cập nhật thông tin khi đăng ký tài khoản trên app',
  `is_actived` tinyint(4) NOT NULL DEFAULT 1,
  `is_deleted` tinyint(4) NOT NULL DEFAULT 0,
  `created_by` int(11) NOT NULL COMMENT 'Người tạo',
  `updated_by` int(11) NOT NULL COMMENT 'Ngừoi thay đổi gần nhất',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `custom_1` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_2` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_3` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_4` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_5` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_6` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_7` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_8` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_9` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_10` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_commission` decimal(16,3) DEFAULT 0.000 COMMENT 'Tổng tiền hoa hồng của KH',
  `customer_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT 'personal' COMMENT 'Loại KH: personal: cá nhân, bussiness: doanh nghiệp',
  `tax_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã số thuế',
  `representative` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Người đại diện',
  `hotline` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã hồ sơ',
  PRIMARY KEY (`customer_id`) USING BTREE,
  UNIQUE KEY `customer_id_UNIQUE` (`customer_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `customer_account`;
CREATE TABLE `customer_account` (
  `customer_account_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) DEFAULT NULL COMMENT 'Id customer',
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Số điện thoại',
  `password` char(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mật khẩu',
  `FbId` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ZaloId` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `GoogleId` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `AppleId` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `imei` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'imei thiết bị',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  `phone_verified` tinyint(4) DEFAULT 1 COMMENT '0: Chưa verify otp, 1: Đã verify otp',
  PRIMARY KEY (`customer_account_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `customer_appointments`;
CREATE TABLE `customer_appointments` (
  `customer_appointment_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `customer_appointment_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã lịch hẹn',
  `customer_id` int(11) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `customer_refer` int(11) DEFAULT NULL COMMENT 'người giới thiệu',
  `appointment_source_id` int(10) unsigned DEFAULT NULL COMMENT 'nguồn khách hàng',
  `customer_appointment_type` enum('direct','appointment','booking') COLLATE utf8mb4_unicode_ci DEFAULT 'appointment',
  `date` date NOT NULL COMMENT 'Ngày bắt đầu',
  `time` time NOT NULL COMMENT 'Giờ bắt đầu',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('new','confirm','cancel','finish','wait','processing') COLLATE utf8mb4_unicode_ci DEFAULT 'new' COMMENT 'Trạng thái lịch hẹn',
  `customer_quantity` int(11) DEFAULT NULL COMMENT 'số lượng khách ',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `total` decimal(16,3) NOT NULL COMMENT 'Tổng tiền',
  `discount` decimal(16,3) NOT NULL COMMENT 'Giảm giá',
  `amount` decimal(16,3) NOT NULL COMMENT 'Thành tiền',
  `voucher_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã giảm giá',
  `end_date` date DEFAULT NULL COMMENT 'Ngày kết thúc',
  `end_time` time DEFAULT NULL COMMENT 'Giờ kết thúc',
  `time_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT 'R' COMMENT 'R: theo ng, W: tuần, M: tháng, Y: year',
  `number_start` int(11) DEFAULT 0 COMMENT 'W: tuần bắt đầu, M: tháng bắt đầu, Y: tháng kết thúc',
  `number_end` int(11) DEFAULT 0 COMMENT 'W: tuần kết thúc, M: tháng kết thúc, Y: năm kết thúc',
  PRIMARY KEY (`customer_appointment_id`) USING BTREE,
  KEY `appointment_source_id` (`appointment_source_id`) USING BTREE,
  CONSTRAINT `customer_appointments_ibfk_1` FOREIGN KEY (`appointment_source_id`) REFERENCES `appointment_source` (`appointment_source_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `customer_appointment_details`;
CREATE TABLE `customer_appointment_details` (
  `customer_appointment_detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_appointment_id` int(11) NOT NULL,
  `service_id` int(11) DEFAULT NULL COMMENT 'id dịch vụ',
  `staff_id` int(11) DEFAULT NULL COMMENT 'id nhân viên phụ vụ',
  `room_id` int(11) DEFAULT NULL COMMENT 'id phòng',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `is_deleted` tinyint(1) DEFAULT 0,
  `customer_order` int(11) NOT NULL COMMENT 'số thứ tự khách hàng , nếu 1 khách hàng có dùng 2 dịch vụ thì sô thứ tự giống nhau',
  `price` decimal(16,3) NOT NULL,
  `object_type` enum('service','member_card') COLLATE utf8mb4_unicode_ci DEFAULT 'service',
  `object_id` int(11) DEFAULT NULL,
  `object_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `object_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_check_promotion` tinyint(4) DEFAULT 1 COMMENT '1: Có lưu log CTKM, 0: Ko lưu log CTKM',
  PRIMARY KEY (`customer_appointment_detail_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `customer_appointment_log`;
CREATE TABLE `customer_appointment_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_appointment_id` int(11) NOT NULL,
  `created_type` enum('backend','app') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'backend',
  `status` enum('new','confirm','cancel','finish','wait') COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int(11) NOT NULL COMMENT 'Người tạo ăn theo type',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `customer_appointment_times`;
CREATE TABLE `customer_appointment_times` (
  `customer_appointment_time_id` int(11) NOT NULL AUTO_INCREMENT,
  `time` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '2020-10-14 13:32:48',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`customer_appointment_time_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `customer_branch`;
CREATE TABLE `customer_branch` (
  `customer_branch_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) DEFAULT NULL COMMENT 'Link với id khách hàng',
  `branch_id` int(11) DEFAULT NULL COMMENT 'Link với id chi nhánh',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`customer_branch_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `customer_branch_money`;
CREATE TABLE `customer_branch_money` (
  `branch_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `total_money` decimal(10,0) DEFAULT NULL COMMENT 'tổng tiền đã active',
  `total_using` decimal(10,0) DEFAULT NULL COMMENT 'tổng tiền đã sử dụng',
  `balance` decimal(10,0) DEFAULT NULL COMMENT 'tiền còn lại',
  `commission_money` decimal(10,0) DEFAULT NULL COMMENT 'Tiền khuyến mãi',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`branch_id`,`customer_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `customer_branch_money_log`;
CREATE TABLE `customer_branch_money_log` (
  `customer_branch_money_log_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) DEFAULT NULL COMMENT 'Link với id KH',
  `branch_id` int(11) DEFAULT NULL COMMENT 'Link với id chi nhánh',
  `source` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT 'member_money' COMMENT 'member_money: Tiền thành viên, commission: Tiền hoa hồng',
  `type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'plus: Cộng, subtract: Trừ',
  `money` decimal(16,3) DEFAULT NULL COMMENT 'Tiền cộng vào hoặc trừ ra',
  `screen` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'active_card: Kích hoạt thẻ tiền, order: Mua hàng, exchange: Quy đổi',
  `screen_object_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã đối tượng theo screen',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`customer_branch_money_log_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `customer_care`;
CREATE TABLE `customer_care` (
  `customer_care_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Link tới mã KH tiềm năng',
  `care_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Loại chăm sóc',
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nội dung chăm sóc',
  `created_by` int(11) NOT NULL COMMENT 'Người chăm sóc',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `object_id` int(11) DEFAULT NULL COMMENT 'Id đối tượng ăn theo loại chăm sóc',
  PRIMARY KEY (`customer_care_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `customer_contacts`;
CREATE TABLE `customer_contacts` (
  `customer_contact_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `district_id` int(11) DEFAULT NULL,
  `province_id` int(11) DEFAULT NULL,
  `postcode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_default` tinyint(1) DEFAULT NULL COMMENT 'Dùng để thiết lập địa chỉ mặc định, mặc định là 1, còn lại là 0',
  `contact_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên người nhận',
  `contact_phone` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'SĐT người nhận',
  `contact_email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Email người nhận',
  `full_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `customer_contact_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã địa chỉ người nhận',
  `is_deleted` tinyint(1) unsigned zerofill DEFAULT 0 COMMENT 'Xóa contact, 1: đã xóa, 0: chưa xóa',
  `customer_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ward_id` int(11) DEFAULT NULL COMMENT 'Id  Phường/Xã',
  `type_address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Loại địa chỉ . home : nhà riêng, office : văn phòng',
  PRIMARY KEY (`customer_contact_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `customer_custom_define`;
CREATE TABLE `customer_custom_define` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Key ứng với tên trường custom',
  `type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'text: kiểu text, boolean: kiểu true false',
  `title_vi` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tiêu đề vi',
  `title_en` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tiêu đề en',
  PRIMARY KEY (`key`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `customer_debt`;
CREATE TABLE `customer_debt` (
  `customer_debt_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `debt_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'CN_2019091231',
  `customer_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `debt_type` enum('order','first') COLLATE utf8mb4_unicode_ci DEFAULT 'order',
  `order_id` int(11) NOT NULL,
  `status` enum('unpaid','part-paid','paid','cancel','fail') COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(16,3) NOT NULL DEFAULT 0.000 COMMENT 'Số tiền nợ',
  `amount_paid` decimal(16,3) NOT NULL DEFAULT 0.000 COMMENT 'Số tiền đã thanh toán',
  `note` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'nội dung thu',
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`customer_debt_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `customer_device`;
CREATE TABLE `customer_device` (
  `customer_device_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL COMMENT 'id của user',
  `imei` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'imei thiết bị',
  `model` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Model thiết bị',
  `platform` enum('android','ios','other') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Flatform',
  `os_version` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'hệ điều hành',
  `app_version` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Phiên bản app',
  `token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'token bắn notification',
  `date_created` datetime DEFAULT NULL COMMENT 'ngày tạo',
  `last_access` datetime DEFAULT NULL COMMENT 'lần truy cập gần nhất',
  `date_modified` datetime DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'ngày cập nhật ',
  `modified_by` int(11) DEFAULT NULL COMMENT 'người cập nhật',
  `created_by` int(11) DEFAULT NULL COMMENT 'người tạo',
  `is_actived` tinyint(1) DEFAULT 1,
  `is_deleted` tinyint(1) DEFAULT 0,
  `endpoint_arn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'endpoint push amazon',
  PRIMARY KEY (`customer_device_id`) USING BTREE,
  KEY `customer_id_is_actived_is_deleted` (`customer_id`,`is_actived`,`is_deleted`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='danh sách thiết bị theo nhân viên';


DROP TABLE IF EXISTS `customer_files`;
CREATE TABLE `customer_files` (
  `customer_file_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL COMMENT 'Id khách hàng',
  `type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT 'image' COMMENT 'image: Hình ảnh, file: tệp',
  `link` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Link hình ảnh/file',
  `is_deleted` tinyint(4) DEFAULT 0,
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`customer_file_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `customer_groups`;
CREATE TABLE `customer_groups` (
  `customer_group_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `is_actived` tinyint(1) DEFAULT 1,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `group_uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`customer_group_id`) USING BTREE,
  UNIQUE KEY `group_name` (`group_name`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `customer_group_condition`;
CREATE TABLE `customer_group_condition` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `customer_group_define_detail`;
CREATE TABLE `customer_group_define_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` int(11) NOT NULL,
  `customer_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_group_id` int(11) NOT NULL COMMENT 'ID nhóm khách hàng tự định nghĩa',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `customer_group_detail`;
CREATE TABLE `customer_group_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_group_id` int(11) NOT NULL COMMENT 'ID nhóm khách hàng nhận thông báo',
  `group_type` enum('A','B') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'A: Bao gồm, B: Loại bỏ',
  `condition_rule` enum('or','and') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'or : hoặc, and : và',
  `condition_id` int(11) NOT NULL COMMENT 'ID điều kiện',
  `customer_group_define_id` int(11) DEFAULT NULL COMMENT 'ID nhóm khách hàng tự định nghĩa đã tạo',
  `day_appointment` int(11) DEFAULT NULL COMMENT 'Ngày hẹn cách hiện tại bao nhiêu ngày?',
  `status_appointment` enum('new','confirm','cancel','finish','wait') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Trạng thái lịch hẹn',
  `time_appointment` enum('morning','noon','afternoon','evening') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Thời gian hẹn: ''morning'',''noon'',''afternoon'',''evening''',
  `not_appointment` tinyint(1) DEFAULT NULL COMMENT 'Chưa có lịch hẹn: 1 chưa, 0 có',
  `use_service` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ID dịch vụ: 1,2,3,4,5 hoặc 1 hoặc 1,3',
  `not_use_service` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ID dịch vụ: 1,2,3,4,5 hoặc 1 hoặc 1,3',
  `use_product` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ID sản phẩm: 1,2,3,4,5 hoặc 1 hoặc 1,3',
  `not_use_product` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ID sản phẩm: 1,2,3,4,5 hoặc 1 hoặc 1,3',
  `not_order` int(11) DEFAULT NULL COMMENT 'Chưa từng mua hàng cách đây mấy ngày?',
  `inactive_app` tinyint(1) DEFAULT NULL COMMENT '1: không hoạt động (login app), 0: có hoạt động',
  `use_promotion` tinyint(1) DEFAULT NULL COMMENT '1: Có tham gia, 0: không tham gia',
  `is_rank` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ID rank 1,2,3,4,5 hoặc 1 hoặc 1,3',
  `range_point` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'vd: [10-88] => 10,88',
  `top_high_revenue` int(11) DEFAULT NULL COMMENT 'TOP ? KH có doanh thu cao nhất',
  `top_low_revenue` int(11) DEFAULT NULL COMMENT 'TOP ? KH có doanh thu thấp nhất',
  `use_service_card` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ID thẻ dịch vụ: 1,2,3,4,5 hoặc 1 hoặc 1,3',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `customer_group_filter`;
CREATE TABLE `customer_group_filter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT 1,
  `filter_group_type` enum('user_define','auto') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Kiểu tự định nghĩa/ auto',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `filter_condition_rule_A` enum('or','and') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `filter_condition_rule_B` enum('or','and') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Nhóm khách hàng nhận thông báo';


DROP TABLE IF EXISTS `customer_info_temp`;
CREATE TABLE `customer_info_temp` (
  `customer_info_temp_id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Họ tên',
  `phone` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Số điện thoại',
  `province_id` int(11) DEFAULT NULL COMMENT 'Tỉnh thành',
  `district_id` int(11) DEFAULT NULL COMMENT 'Quận huyện',
  `address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Địa chỉ',
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Email',
  `gender` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Giới tính',
  `birthday` date DEFAULT NULL COMMENT 'Ngày sinh',
  `status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT 'new' COMMENT 'new: Mới, confirm: Đã xác nhận, cancel: Huỷ',
  `confirm_by` int(11) DEFAULT NULL COMMENT 'Người xác nhận',
  `customer_id` int(11) NOT NULL COMMENT 'Khách hàng cần thay đổi',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`customer_info_temp_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `customer_info_type`;
CREATE TABLE `customer_info_type` (
  `customer_info_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_info_type_name_vi` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên loại thông tin kèm theo',
  `customer_info_type_name_en` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_actived` tinyint(4) DEFAULT 1,
  `is_deleted` tinyint(4) DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  PRIMARY KEY (`customer_info_type_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `customer_logs`;
CREATE TABLE `customer_logs` (
  `customer_log_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `object_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'customer hoặc lead',
  `object_id` int(11) DEFAULT NULL COMMENT 'id customer hoặc id lead',
  `key_table` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'cpo_customer_lead hoặc customers',
  `created_by` int(11) DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`customer_log_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `customer_log_update`;
CREATE TABLE `customer_log_update` (
  `customer_log_update_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_log_id` int(11) DEFAULT NULL COMMENT 'link log chính',
  `key_table` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'cpo_customer_lead hoặc customers',
  `key` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'field cần lưu log',
  `value_old` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'value cũ của key',
  `value_new` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'value mới của key',
  `created_by` int(11) DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`customer_log_update_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `customer_potential_log`;
CREATE TABLE `customer_potential_log` (
  `customer_potential_log_id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `type` enum('product','service','service_card') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'product : gói, service : dịch vụ',
  `obj_id` int(11) DEFAULT NULL COMMENT 'Id của chi tiết product nếu là chi tiết gói, id của chi tiết dịch vụ nếu là ở dịch vụ',
  `obj_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `customer_real_care`;
CREATE TABLE `customer_real_care` (
  `customer_care_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Link tới mã KH tiềm năng',
  `care_type` enum('call','chat','email','message','meeting','other') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Loại chăm sóc',
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nội dung chăm sóc',
  `created_by` int(11) NOT NULL COMMENT 'Người chăm sóc',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `object_id` int(11) DEFAULT NULL COMMENT 'Id đối tượng ăn theo loại chăm sóc',
  PRIMARY KEY (`customer_care_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `customer_remind_care`;
CREATE TABLE `customer_remind_care` (
  `customer_remind_care_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_remind_use_id` int(11) NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'email: Loại email, sms: loại sms, notify: loại thông báo, care: Chăm sóc thủ công',
  `type_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên hình thức chăm sóc',
  `type_id` int(11) DEFAULT NULL COMMENT 'Id đối tượng chăm sóc',
  `content` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nội dung chăm sóc',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  PRIMARY KEY (`customer_remind_care_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `customer_remind_use`;
CREATE TABLE `customer_remind_use` (
  `customer_remind_use_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL COMMENT 'Mã khách hàng',
  `order_id` int(11) NOT NULL COMMENT 'Mã đơn hàng',
  `object_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Loại đối tượng',
  `object_id` int(11) DEFAULT NULL COMMENT 'Id đối tượng',
  `object_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã đối tượng',
  `object_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên đối tượng',
  `sent_at` datetime DEFAULT NULL COMMENT 'Ngày gửi thông báo',
  `is_finish` tinyint(4) DEFAULT 0 COMMENT '0: Chưa kết thúc, 1: Đã kết thúc ',
  `is_queue` tinyint(4) DEFAULT 0 COMMENT '0: Chưa quét queue, 1: Đã quét queue',
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Thông tin mô tả',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`customer_remind_use_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `customer_service_cards`;
CREATE TABLE `customer_service_cards` (
  `customer_service_card_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `card_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `service_card_id` int(10) unsigned NOT NULL,
  `actived_date` datetime DEFAULT NULL,
  `expired_date` datetime DEFAULT NULL,
  `number_using` int(11) DEFAULT NULL,
  `count_using` int(11) DEFAULT NULL,
  `money` decimal(10,0) DEFAULT NULL,
  `money_using` decimal(10,0) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `is_actived` tinyint(1) DEFAULT 1,
  `is_deleted` tinyint(1) DEFAULT 0 COMMENT 'Đánh dấu hủy',
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ghi chú',
  `is_reserve` tinyint(1) DEFAULT 0 COMMENT 'Bảo lưu (1: Đang bảo lưu, 0: không bảo lưu)',
  `date_reserve` datetime DEFAULT NULL COMMENT 'Ngày bảo lưu',
  `number_days_remain_reserve` int(11) DEFAULT 0 COMMENT 'Số ngày còn lại sau khi bảo lưu',
  PRIMARY KEY (`customer_service_card_id`) USING BTREE,
  KEY `service_card_id` (`service_card_id`) USING BTREE,
  CONSTRAINT `customer_service_cards_ibfk_1` FOREIGN KEY (`service_card_id`) REFERENCES `service_cards` (`service_card_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `customer_sources`;
CREATE TABLE `customer_sources` (
  `customer_source_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `customer_source_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_source_type` enum('in','out') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'in',
  `is_actived` tinyint(4) NOT NULL DEFAULT 1,
  `is_deleted` tinyint(4) NOT NULL DEFAULT 0,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`customer_source_id`) USING BTREE,
  UNIQUE KEY `customer_source_name` (`customer_source_name`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `dashboard`;
CREATE TABLE `dashboard` (
  `dashboard_id` int(11) NOT NULL AUTO_INCREMENT,
  `name_vi` varchar(191) DEFAULT NULL COMMENT 'tên dasboard',
  `name_en` varchar(191) DEFAULT NULL COMMENT 'tên tiếng anh',
  `is_actived` tinyint(1) DEFAULT 1 COMMENT 'trạng thái hiển thị',
  `is_deleted` tinyint(1) DEFAULT 0 COMMENT '0',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`dashboard_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `dashboard_components`;
CREATE TABLE `dashboard_components` (
  `dashboard_component_id` int(11) NOT NULL AUTO_INCREMENT,
  `dashboard_id` int(11) DEFAULT NULL COMMENT 'component thuộc dashboard nào, default thì id = 0',
  `component_type` varchar(191) DEFAULT NULL COMMENT 'column or tab',
  `component_position` int(11) DEFAULT NULL COMMENT 'vị trí của component',
  `is_default` tinyint(1) DEFAULT 0 COMMENT 'component default',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`dashboard_component_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `dashboard_component_widgets`;
CREATE TABLE `dashboard_component_widgets` (
  `dashboard_component_widget_id` int(11) NOT NULL AUTO_INCREMENT,
  `dashboard_component_id` int(11) DEFAULT NULL,
  `dashboard_widget_id` int(11) DEFAULT NULL,
  `widget_display_name` varchar(191) DEFAULT NULL COMMENT 'tên hiển thị của widget',
  `widget_position` int(11) DEFAULT NULL COMMENT 'vị trí widget',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`dashboard_component_widget_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `dashboard_widgets`;
CREATE TABLE `dashboard_widgets` (
  `dashboard_widget_id` int(11) NOT NULL AUTO_INCREMENT,
  `widget_name_vi` varchar(191) DEFAULT NULL COMMENT 'tên của widget',
  `widget_name_en` varchar(191) DEFAULT NULL COMMENT 'tên tiếng anh',
  `widget_code` varchar(191) DEFAULT NULL COMMENT 'mã của widget, để bắt case hiển thị',
  `size_column` int(11) DEFAULT NULL COMMENT '1 đến 12',
  `widget_type` varchar(191) DEFAULT NULL COMMENT 'column hoặc tab',
  `icon` text DEFAULT NULL COMMENT 'icon widget',
  `image` text DEFAULT NULL COMMENT 'image of widget',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`dashboard_widget_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `deliveries`;
CREATE TABLE `deliveries` (
  `delivery_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL COMMENT ' ',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `contact_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_transport_estimate` int(11) DEFAULT 1 COMMENT 'số lần giao hàng dự kiến',
  `is_deleted` tinyint(1) DEFAULT 0,
  `is_actived` tinyint(1) DEFAULT NULL,
  `delivery_status` enum('packing','delivered','preparing','delivering','cancel') COLLATE utf8mb4_unicode_ci DEFAULT 'packing',
  `time_order` datetime NOT NULL COMMENT 'Thời gian đặt hàng',
  `delivery_partner_id` int(11) DEFAULT NULL COMMENT 'Đối tác giao hàng',
  PRIMARY KEY (`delivery_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `delivery_client`;
CREATE TABLE `delivery_client` (
  `delivery_client_id` int(11) NOT NULL AUTO_INCREMENT,
  `delivery_method_id` int(11) DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `tenant` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mode` enum('sandbox','product') COLLATE utf8mb4_unicode_ci DEFAULT 'sandbox',
  `token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dr_sandbox_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dr_product_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`delivery_client_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `delivery_costs`;
CREATE TABLE `delivery_costs` (
  `delivery_cost_id` int(11) NOT NULL AUTO_INCREMENT,
  `delivery_cost_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivery_cost_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivery_cost` decimal(10,3) DEFAULT NULL,
  `is_actived` tinyint(1) DEFAULT 1 COMMENT '1 hoạt động ; 0 không hoạt động',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `is_system` tinyint(1) DEFAULT 0,
  `is_delivery_fast` tinyint(1) DEFAULT 0 COMMENT 'Giao hàng nhanh',
  `delivery_fast_cost` decimal(10,3) DEFAULT NULL COMMENT 'Phí giao hàng nhanh',
  PRIMARY KEY (`delivery_cost_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `delivery_cost_detail`;
CREATE TABLE `delivery_cost_detail` (
  `delivery_cost_detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `delivery_cost_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Chi phí vận chuyển',
  `postcode` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'postcode của district',
  `district_id` int(11) DEFAULT NULL,
  `province_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`delivery_cost_detail_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `delivery_cost_map_method`;
CREATE TABLE `delivery_cost_map_method` (
  `delivery_cost_map_method_id` int(11) NOT NULL AUTO_INCREMENT,
  `delivery_cost_id` int(11) DEFAULT NULL COMMENT 'id Cấu hình chi phí giao hàng',
  `delivery_method_config_id` int(11) DEFAULT NULL COMMENT 'Id Phương thức vận chuyển',
  `delivery_cost` decimal(10,3) DEFAULT NULL COMMENT 'Chi phí giao hàng',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`delivery_cost_map_method_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=FIXED COMMENT='Map dữ liệu của cấu hình phí vận chuyển và phương thức vận chuyển';


DROP TABLE IF EXISTS `delivery_customer_address`;
CREATE TABLE `delivery_customer_address` (
  `delivery_customer_address_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) DEFAULT NULL COMMENT 'Id khách hàng',
  `customer_name` varchar(191) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT 'Tên khách hàng nhận hàng theo địa chỉ',
  `customer_phone` varchar(12) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT 'Số điện thoại người nhận',
  `province_id` int(11) DEFAULT NULL COMMENT 'Id Tỉnh/Thành phố',
  `district_id` int(11) DEFAULT NULL COMMENT 'Id Quận/Huyện',
  `ward_id` int(11) DEFAULT NULL COMMENT 'Id  Phường/Xã',
  `address` varchar(191) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT 'Địa chỉ nhận hàng',
  `type_address` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT 'Loại địa chỉ . home : nhà riêng, office : văn phòng',
  `is_default` tinyint(1) DEFAULT 0 COMMENT 'Địa chỉ mặc định',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`delivery_customer_address_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `delivery_detail`;
CREATE TABLE `delivery_detail` (
  `delivery_detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `delivery_history_id` int(11) NOT NULL,
  `object_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `object_type` enum('product','service','service_card','product_gift','service_gift','service_card_gift') COLLATE utf8mb4_unicode_ci DEFAULT 'product',
  `price` decimal(16,3) DEFAULT NULL,
  PRIMARY KEY (`delivery_detail_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `delivery_details`;
CREATE TABLE `delivery_details` (
  `delivery_detail_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `delivery_id` int(11) NOT NULL,
  `transport_id` int(11) DEFAULT NULL COMMENT ' ',
  `staff_id` int(11) DEFAULT NULL,
  `object_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `status` enum('new','inprogress','success','cancle') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`delivery_detail_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `delivery_district`;
CREATE TABLE `delivery_district` (
  `district_id` int(11) NOT NULL,
  `district_id_ghn` int(11) DEFAULT NULL,
  `district_code_ghn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `province_id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `postcode` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lat` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `long` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`district_id`) USING BTREE,
  KEY `provinceid` (`province_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `delivery_history`;
CREATE TABLE `delivery_history` (
  `delivery_history_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `delivery_id` int(11) NOT NULL,
  `transport_id` int(11) DEFAULT NULL COMMENT ' ',
  `transport_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'mã giao hàng',
  `delivery_staff` int(11) DEFAULT NULL,
  `delivery_start` datetime DEFAULT NULL,
  `delivery_end` datetime DEFAULT NULL,
  `contact_phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(16,3) DEFAULT NULL COMMENT 'tiền cần thu',
  `verified_payment` tinyint(4) DEFAULT NULL COMMENT 'xác nhận đã thu tiền',
  `verified_by` int(11) DEFAULT NULL COMMENT 'kế toán xác nhận đã thu tiền',
  `status` enum('new','inprogress','success','cancel','fail','confirm','pending') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `time_ship` datetime DEFAULT NULL COMMENT 'thời gian giao hàng dự kiến',
  `pick_up` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'nơi lấy hàng',
  `image_pick_up` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Hình ảnh lấy hàng',
  `image_drop` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Hình ảnh giao hàng',
  `time_pick_up` datetime DEFAULT NULL COMMENT 'Thời gian nhận hàng',
  `reason_delivery_fail_id` int(11) DEFAULT NULL COMMENT 'Lý do giao hàng thất bại',
  `reason_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên lý do giao hàng thất bại',
  `delivery_history_code` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã phiếu giao hàng',
  `time_drop` datetime DEFAULT NULL COMMENT 'Thời gian giao hàng',
  `is_request_payment` tinyint(4) DEFAULT 0 COMMENT 'Yêu cầu thanh toán',
  `pickup_address_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã code địa chỉ lấy hàng',
  `delivery_note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ghi chú của nhân viên giao hàng',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `warehouse_id_pick_up` int(11) DEFAULT NULL COMMENT 'id kho lấy hàng',
  `is_back` tinyint(4) DEFAULT 0 COMMENT '0: Chưa hoàn kho 1: đã hoàn kho',
  `province_id` int(11) DEFAULT NULL COMMENT 'Tỉnh thành',
  `district_id` int(11) DEFAULT NULL COMMENT 'Quận huyện',
  `ward_id` int(11) DEFAULT NULL COMMENT 'Phường xã',
  `weight` decimal(16,0) DEFAULT NULL COMMENT 'Trọng lượng',
  `type_weight` varchar(191) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT 'Loại trọng lượng : gam, kg',
  `length` int(11) DEFAULT NULL COMMENT 'Chiều dài',
  `width` int(11) DEFAULT NULL COMMENT 'Rộng',
  `height` int(11) DEFAULT NULL COMMENT 'Cao',
  `shipping_unit` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Đơn vị vận chuyển',
  `is_insurance` tinyint(1) DEFAULT NULL COMMENT 'Bảo hiểm hàng hoá',
  `insurance_fee` decimal(16,0) DEFAULT NULL COMMENT 'Phí bảo hiểm hàng hoá',
  `is_post_office` tinyint(1) DEFAULT NULL COMMENT 'Mang ra bưu cục',
  `required_note` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Lưu ý khi giao hàng : CHOTHUHANG ,CHOXEMHANGKHONGTHU, KHONGCHOXEMHANG',
  `is_cod_amount` tinyint(1) DEFAULT NULL COMMENT 'Thu hộ tiền',
  `cod_amount` decimal(16,0) DEFAULT NULL COMMENT 'Số tiền thu hộ',
  `fee` decimal(16,0) DEFAULT NULL COMMENT 'Phí',
  `total_fee` decimal(16,0) DEFAULT NULL COMMENT 'Tổng phí trả chco đối tác giao hàng',
  `name_service` varchar(191) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT 'Tên dịch vụ',
  `service_id` int(11) DEFAULT NULL COMMENT 'Id dịch vụ giao hàng (đối tác)',
  `service_type_id` int(11) DEFAULT NULL COMMENT 'id loại dịch vụ giao hàng (đối tác)',
  `ghn_order_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã đơn hàng từ ghn',
  `partner` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã đối tác vận chuyển',
  `status_ghn` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Trạng thái của GHN',
  PRIMARY KEY (`delivery_history_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `delivery_history_log`;
CREATE TABLE `delivery_history_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `delivery_history_id` int(11) NOT NULL,
  `status` enum('new','inprogress','success','fail','cancel','confirm') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Trạng thái giao hàng',
  `created_by` int(11) NOT NULL COMMENT 'Người tạo sẽ ăn theo type',
  `created_type` enum('backend','app','app_carrier') COLLATE utf8mb4_unicode_ci DEFAULT 'backend',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `delivery_history_payment`;
CREATE TABLE `delivery_history_payment` (
  `delivery_payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `delivery_history_id` int(11) NOT NULL,
  `total` decimal(16,3) NOT NULL COMMENT 'Tổng tiền thanh toán',
  `is_verify` int(11) NOT NULL DEFAULT 0 COMMENT 'Đã được duyệt',
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ghi chú',
  `created_source` enum('app_carrier','app_loyalty') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Được tạo từ nguồn nào',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`delivery_payment_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `delivery_history_payment_detail`;
CREATE TABLE `delivery_history_payment_detail` (
  `delivery_history_payment_detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `delivery_history_payment_id` int(11) NOT NULL,
  `payment_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(16,3) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  `payment_transaction_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Phương thức thanh toán',
  PRIMARY KEY (`delivery_history_payment_detail_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `delivery_method`;
CREATE TABLE `delivery_method` (
  `delivery_method_id` int(11) NOT NULL AUTO_INCREMENT,
  `delivery_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`delivery_method_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `delivery_method_config`;
CREATE TABLE `delivery_method_config` (
  `delivery_method_config_id` int(11) NOT NULL AUTO_INCREMENT,
  `delivery_method_name` varchar(191) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT 'Tên phương thức vận chuyển',
  `delivery_method_code` varchar(191) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT 'Mã phương thức vận chuyển',
  `is_active` tinyint(1) DEFAULT NULL COMMENT 'Trạng thái',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`delivery_method_config_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC COMMENT='Phương thức vận chuyển';


DROP TABLE IF EXISTS `delivery_order`;
CREATE TABLE `delivery_order` (
  `delivery_order_id` int(11) NOT NULL AUTO_INCREMENT,
  `shop_id` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_id` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `return_name` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `return_phone` varchar(13) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `return_address` varchar(90) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `return_ward_code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `return_district_id` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `return_location` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '"lat":10.810695,"long":106.682046,,"cell_code":"AJIAEQJQ","place_id":"ChIJz-YRGgQvdTERoKHzo8O2L8g","trust_level":5,"wardcode":"21302",',
  `from_name` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `from_phone` varchar(13) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `from_address` varchar(90) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `from_ward_code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `from_district_id` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `from_location` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '"lat":10.810695,"long":106.682046,,"cell_code":"AJIAEQJQ","place_id":"ChIJz-YRGgQvdTERoKHzo8O2L8g","trust_level":5,"wardcode":"21302",',
  `deliver_station_id` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `to_name` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `to_phone` varchar(13) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `to_address` varchar(90) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `to_ward_code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `to_district_id` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `to_location` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '"lat":10.810695,"long":106.682046,,"cell_code":"AJIAEQJQ","place_id":"ChIJz-YRGgQvdTERoKHzo8O2L8g","trust_level":5,"wardcode":"21302",',
  `weight` int(11) DEFAULT NULL,
  `length` int(11) DEFAULT NULL,
  `width` int(11) DEFAULT NULL,
  `height` int(11) DEFAULT NULL,
  `converted_weight` int(11) DEFAULT NULL,
  `image_ids` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_type_id` int(11) DEFAULT NULL,
  `service_id` int(11) DEFAULT NULL,
  `payment_type_id` int(11) DEFAULT NULL,
  `payment_type_ids` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_service_fee` int(11) DEFAULT NULL,
  `sort_code` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cod_amount` int(11) DEFAULT 0,
  `cod_collect_date` datetime DEFAULT NULL,
  `cod_transfer_date` datetime DEFAULT NULL,
  `is_cod_transferred` tinyint(4) DEFAULT NULL,
  `is_cod_collected` tinyint(4) DEFAULT NULL,
  `insurance_value` int(11) DEFAULT NULL,
  `order_value` int(11) DEFAULT NULL,
  `pick_station_id` int(11) DEFAULT NULL,
  `client_order_code` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `required_note` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `employee_note` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seal_code` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pickup_time` datetime DEFAULT NULL,
  `items` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `coupon` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `station_do` int(11) DEFAULT 0 COMMENT 'Phí gửi hàng tại bưu cục.',
  `station_pu` int(11) DEFAULT 0 COMMENT 'Phí lấy hàng tại bưu cục.',
  `_id` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_code` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `version_no` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_ip` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_employee` int(11) DEFAULT NULL,
  `updated_client` int(11) DEFAULT NULL,
  `updated_source` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `updated_warehouse` int(11) DEFAULT NULL,
  `created_ip` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_employee` int(11) DEFAULT NULL,
  `created_client` int(11) DEFAULT NULL,
  `created_source` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `status` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pick_warehouse_id` int(11) DEFAULT NULL,
  `deliver_warehouse_id` int(11) DEFAULT NULL,
  `current_warehouse_id` int(11) DEFAULT NULL,
  `return_warehouse_id` int(11) DEFAULT NULL,
  `next_warehouse_id` int(11) DEFAULT NULL,
  `leadtime` datetime DEFAULT NULL,
  `order_date` datetime DEFAULT NULL,
  `data` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `soc_id` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `r2s_time` datetime DEFAULT NULL,
  `return_time` datetime DEFAULT NULL,
  `finish_date` datetime DEFAULT NULL,
  `tag` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `log` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '"status": "picking",\n                 "payment_type_id": "2",\n                 "updated_date": "2021-11-11T03:04:48.053Z"',
  `is_partial_return` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reason` varchar(95) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reason_code` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipper_name` varchar(95) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipper_phone` varchar(13) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `warehouse` varchar(145) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`delivery_order_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `delivery_order_status`;
CREATE TABLE `delivery_order_status` (
  `delivery_order_status_id` int(11) NOT NULL AUTO_INCREMENT,
  `delivery_order_id` int(11) DEFAULT NULL COMMENT 'id giao hàng',
  `shop_id` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_id` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deliver_station_id` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_type_id` int(11) DEFAULT NULL,
  `service_id` int(11) DEFAULT NULL,
  `payment_type_id` int(11) DEFAULT NULL,
  `payment_type_ids` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_service_fee` int(11) DEFAULT NULL,
  `sort_code` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cod_amount` int(11) DEFAULT 0,
  `cod_collect_date` datetime DEFAULT NULL,
  `cod_transfer_date` datetime DEFAULT NULL,
  `order_value` int(11) DEFAULT NULL,
  `pick_station_id` int(11) DEFAULT NULL,
  `station_do` int(11) DEFAULT 0 COMMENT 'Phí gửi hàng tại bưu cục.',
  `station_pu` int(11) DEFAULT 0 COMMENT 'Phí lấy hàng tại bưu cục.',
  `status` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`delivery_order_status_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `delivery_partner`;
CREATE TABLE `delivery_partner` (
  `delivery_partner_id` int(11) NOT NULL AUTO_INCREMENT,
  `delivery_partner_code` varchar(191) DEFAULT NULL COMMENT 'Mã code ',
  `delivery_partner_name` varchar(191) DEFAULT NULL COMMENT 'Tên đối tác giao hàng',
  `delivery_partner_avatar` varchar(191) DEFAULT NULL COMMENT 'Link icon đối tác giao hàng',
  `is_active` tinyint(1) DEFAULT 0 COMMENT 'Trạng thái',
  `is_connect` tinyint(1) DEFAULT 0 COMMENT 'Trạng thái kết nối',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`delivery_partner_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `delivery_province`;
CREATE TABLE `delivery_province` (
  `province_id` int(11) NOT NULL,
  `province_id_ghn` int(11) DEFAULT NULL,
  `province_code_ghn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_id` int(11) NOT NULL DEFAULT 1,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`province_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `delivery_shop`;
CREATE TABLE `delivery_shop` (
  `delivery_shop_id` int(11) NOT NULL AUTO_INCREMENT,
  `delivery_client_id` int(11) DEFAULT NULL,
  `shop_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'id từ gdn',
  `shop_uuid` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL COMMENT 'id từ ghn',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `district_id` int(11) DEFAULT NULL,
  `ward_id` int(11) DEFAULT NULL,
  `province_id` int(11) DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`delivery_shop_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Cửa hàng';


DROP TABLE IF EXISTS `delivery_status`;
CREATE TABLE `delivery_status` (
  `delivery_status_id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(191) DEFAULT NULL COMMENT 'Nhà đổi tác',
  `status` varchar(191) DEFAULT NULL COMMENT 'Trạng thái phiếu giao hàng',
  `status_partner` varchar(191) DEFAULT NULL COMMENT 'Trạng thái của đối tác giao hàng',
  `description` varchar(191) DEFAULT NULL COMMENT 'Mô tả trạng thái của dối tác giao hàng',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`delivery_status_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `delivery_transaction`;
CREATE TABLE `delivery_transaction` (
  `delivery_transaction_id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID tự tăng',
  `delivery_transaction_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã giao dịch tự tạo để đối chiếu',
  `delivery_transaction_uuid` int(11) DEFAULT NULL,
  `delivery_method_id` int(11) NOT NULL COMMENT 'ID phương thức thanh toán',
  `client_id` int(11) NOT NULL COMMENT 'Thanh toán cho công ty/chi nhánh',
  `tenant` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã công ty/chi nhánh',
  `client_ip` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'IP của người dùng',
  `order_type` enum('order','debt') COLLATE utf8mb4_unicode_ci DEFAULT 'order' COMMENT 'Loại thanh toán: Đơn hàng, công nợ',
  `order_id` bigint(20) NOT NULL COMMENT 'ID base order',
  `amount` decimal(19,6) DEFAULT NULL COMMENT 'Số tiền thanh toán',
  `transaction_date` date NOT NULL COMMENT 'Ngày tạo giao dịch, phục vụ báo cáo',
  `payment_transaction_status` enum('new','waiting','success','fail','cancel') COLLATE utf8mb4_unicode_ci DEFAULT 'new' COMMENT 'new: mới khởi tạo; waiting: thanh toán thành công và redirect về app, chưa có ipn; success: Thanh toán thành công cập nhật từ IPN, fail: thanh toán lỗi cập nhật từ IPN; cancel: User hủy thanh toán trên app',
  `payment_url` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'URL được tạo ra cho giao dịch thanh toán',
  `is_cross_check` tinyint(1) DEFAULT 0 COMMENT 'Đánh dấu đã kiểm tra đối với các giao dịch thanh toán thành công. Để job không chạy lại nữa',
  `bank_code` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã ngân hàng',
  `bank_transaction_code` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã giao dịch ngân hàng',
  `bank_card_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Loại tài khoản/ thẻ',
  `transaction_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã giao dịch VNPay',
  `payment_at` datetime DEFAULT NULL COMMENT 'Thời gian thanh toán từ cổng thanh toán trả về',
  `vnpay_transaction_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã giao dịch VNPay',
  `created_at` datetime DEFAULT NULL COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT current_timestamp() COMMENT 'Thời gian cập nhật',
  PRIMARY KEY (`delivery_transaction_id`) USING BTREE,
  KEY `created_at` (`created_at`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Dùng để tạo ra các giao dịch, lấy id này cho việc thanh toán đơn hàng';


DROP TABLE IF EXISTS `delivery_ward`;
CREATE TABLE `delivery_ward` (
  `ward_id` int(11) NOT NULL,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ward_type` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ward_code_ghn` varchar(255) DEFAULT NULL,
  `ward_id_ghn` varchar(255) DEFAULT NULL,
  `location` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `district_id` int(11) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ward_id`) USING BTREE,
  KEY `districtid` (`district_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `departments`;
CREATE TABLE `departments` (
  `department_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `department_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_inactive` tinyint(4) NOT NULL DEFAULT 0,
  `is_deleted` tinyint(4) NOT NULL DEFAULT 0,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`department_id`) USING BTREE,
  UNIQUE KEY `department_name` (`department_name`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `device_token`;
CREATE TABLE `device_token` (
  `id` int(11) NOT NULL COMMENT 'ID tự tăng',
  `user_id` int(11) NOT NULL COMMENT 'ID user',
  `platform` enum('ios','android') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Hệ điều hành của thiết bị',
  `device_token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'token của thiết bị',
  `endpoint_arn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'endpoint push amazon',
  `created_at` datetime DEFAULT NULL COMMENT 'Thời gian tạo',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Thời gian cập nhật'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Thiết bị push notification. Mỗi user hoặc 1 máy là unique';


DROP TABLE IF EXISTS `discount_causes`;
CREATE TABLE `discount_causes` (
  `discount_causes_id` int(11) NOT NULL AUTO_INCREMENT,
  `discount_causes_name_vi` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên lý do giảm giá',
  `discount_causes_name_en` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên lý do giảm giá en',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'trạng thái',
  `is_delete` tinyint(1) NOT NULL DEFAULT 0,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`discount_causes_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Danh sách lý do giảm giá';


DROP TABLE IF EXISTS `district`;
CREATE TABLE `district` (
  `districtid` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `provinceid` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `postcode` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lat` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `long` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`districtid`) USING BTREE,
  KEY `provinceid` (`provinceid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `educational_level`;
CREATE TABLE `educational_level` (
  `educational_level_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id trình độ học vấn',
  `name` varchar(191) NOT NULL COMMENT 'tên trình độ học vấn',
  `code` varchar(191) NOT NULL COMMENT 'tên viết tắt',
  PRIMARY KEY (`educational_level_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `email_campaign`;
CREATE TABLE `email_campaign` (
  `campaign_id` int(11) NOT NULL AUTO_INCREMENT,
  `branch_id` int(11) DEFAULT NULL COMMENT 'Id Chi nhánh',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên chiến dịch',
  `status` enum('cancel','new','sent') COLLATE utf8mb4_unicode_ci DEFAULT 'new' COMMENT 'Trang thái chiến dịch',
  `content` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nội dung email',
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Slug chiến dịch để check trùng',
  `value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Thời gian gửi',
  `is_now` tinyint(4) DEFAULT 0 COMMENT 'Gửi ngay',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Ngươi update',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `sent_by` int(11) DEFAULT NULL COMMENT 'Người gửi',
  `time_sent` datetime DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Thời gian gửi xong',
  `is_deal_created` tinyint(1) DEFAULT 0 COMMENT 'check có tạo deal hay không? 1 có, 0 không',
  `cost` float(16,3) DEFAULT NULL COMMENT 'Chi phí cho chiến dịch',
  PRIMARY KEY (`campaign_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `email_config`;
CREATE TABLE `email_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` enum('birthday','new_appointment','cancel_appointment','remind_appointment','paysuccess','new_customer','service_card_nearly_expired','service_card_over_number_used','service_card_expires','delivery_note','confirm_deliveried','order_success','active_warranty_card','otp','is_remind_use') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Loại tin nhắn',
  `value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Điều kiện số ngày gửi',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tiêu đề mẫu',
  `content` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nội dung email mẫu',
  `is_actived` tinyint(1) DEFAULT 0 COMMENT 'trạng thái',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `time_sent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Thời gian gửi ',
  `actived_by` int(11) DEFAULT NULL COMMENT 'Người actived',
  `datetime_actived` datetime DEFAULT NULL COMMENT 'Thời gian active',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `email_deal`;
CREATE TABLE `email_deal` (
  `email_deal_id` int(11) NOT NULL AUTO_INCREMENT,
  `email_campaign_id` int(11) DEFAULT NULL,
  `closing_date` date DEFAULT NULL COMMENT 'ngày kết thúc dự kiến ',
  `pipeline_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `journey_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Ngươi update',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `amount` decimal(16,3) DEFAULT NULL,
  PRIMARY KEY (`email_deal_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `email_deal_detail`;
CREATE TABLE `email_deal_detail` (
  `email_deal_detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `email_deal_id` int(11) NOT NULL,
  `object_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'tên của dich vụ , sản phẩm hoac thẻ dịch vụ tuỳ objecttype ',
  `object_type` enum('service_card','service','product','member_card','product_gift','service_gift','service_card_gift') CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `object_code` varchar(1024) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `object_id` int(11) NOT NULL COMMENT 'id của dich vụ , sản phẩm hoac thẻ dịch vụ tuỳ object_type',
  `price` decimal(16,3) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `discount` decimal(16,3) DEFAULT NULL,
  `amount` decimal(16,3) DEFAULT NULL,
  `voucher_code` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT NULL,
  `is_deleted` tinyint(4) DEFAULT 0,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`email_deal_detail_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `email_log`;
CREATE TABLE `email_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `campaign_id` int(11) DEFAULT NULL COMMENT 'Tên chiến dịch',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Email',
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên khách hàng',
  `email_status` enum('new','cancel','sent') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Trạng thái gửi tin',
  `email_type` enum('birthday','new_appointment','cancel_appointment','remind_appointment','paysuccess','new_customer','service_card_nearly_expired','service_card_over_number_used','service_card_expires','print_card','order_success','warranty_actived') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Loại tin',
  `object_id` int(11) DEFAULT NULL,
  `object_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content_sent` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nội dung gửi',
  `created_at` datetime DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `time_sent` datetime DEFAULT NULL COMMENT 'Thời gian bắt đầu gửi tin nhắn',
  `time_sent_done` datetime DEFAULT NULL COMMENT 'Thời gian gửi xong tin nhắn',
  `provider` enum('gmail','amazone') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'dich vụ email',
  `sent_by` int(11) DEFAULT NULL COMMENT 'Người gửi',
  `type_customer` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'customer' COMMENT 'lead or customer',
  `deal_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Link với mã deal',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `email_provider`;
CREATE TABLE `email_provider` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` enum('gmail','amazone','clicksend') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Loại dịch vu',
  `name_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên đại điện',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Email',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mật khẩu',
  `is_actived` tinyint(1) DEFAULT NULL COMMENT 'TRạng thái',
  `email_template_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `email_templates`;
CREATE TABLE `email_templates` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ảnh hiển thị template',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `estimate_branch_time`;
CREATE TABLE `estimate_branch_time` (
  `estimate_branch_time_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `branch_id` int(11) DEFAULT NULL COMMENT 'ID chi nhánh',
  `type` enum('W','M') NOT NULL COMMENT 'M: theo tháng. W: theo tuần',
  `week` int(2) DEFAULT NULL COMMENT 'tuần',
  `month` int(2) DEFAULT NULL COMMENT 'tháng',
  `year` int(4) DEFAULT NULL COMMENT 'năm',
  `estimate_time` decimal(14,2) DEFAULT NULL COMMENT 'số giờ làm việc tối thiểu',
  `estimate_money` decimal(14,2) DEFAULT NULL COMMENT 'ngân sách lương dự kiến',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`estimate_branch_time_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `ethnic`;
CREATE TABLE `ethnic` (
  `ethnic_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id dân tộc',
  `ethnic_group_id` int(11) NOT NULL COMMENT 'nhóm dân tộc, relationshop to table ethnic_group',
  `name` varchar(191) NOT NULL COMMENT 'tên dân tộc',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`ethnic_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `ethnic_group`;
CREATE TABLE `ethnic_group` (
  `ethnic_group_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id nhóm dân tộc',
  `name` varchar(191) NOT NULL COMMENT 'tên nhóm dân tộc',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`ethnic_group_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `faq`;
CREATE TABLE `faq` (
  `faq_id` int(11) NOT NULL AUTO_INCREMENT,
  `faq_group` int(11) DEFAULT NULL COMMENT 'nhóm ? nếu type khác faq thì null',
  `faq_type` enum('faq','privacy_policy','terms_use') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'faq : hỏi đáp, policy : chính sach bảo mật, terms : điều khoản sử dụng',
  `faq_title_vi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'title',
  `faq_title_en` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `faq_content_vi` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'nội dung',
  `faq_content_en` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `faq_position` int(10) unsigned DEFAULT 1 COMMENT 'thứ tự hiển thị',
  `is_actived` tinyint(1) DEFAULT 1,
  `is_deleted` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`faq_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='trung tâm cài đặt hỗ trợ';


DROP TABLE IF EXISTS `faq_group`;
CREATE TABLE `faq_group` (
  `faq_group_id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `faq_group_type` enum('basic','default','page') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'nếu loại là trang (dùng cho cms)',
  `faq_group_title_vi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'tiêu đề',
  `faq_group_title_en` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `faq_group_position` int(10) unsigned DEFAULT 1 COMMENT 'vị trí hiển thị',
  `is_actived` tinyint(1) DEFAULT 1,
  `is_deleted` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`faq_group_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='nhóm trung tâm cài đặt hỗ trợ';


DROP TABLE IF EXISTS `feedback_answer`;
CREATE TABLE `feedback_answer` (
  `feedback_answer_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `feedback_question_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `feedback_answer_value` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`feedback_answer_id`) USING BTREE,
  KEY `feedback_question_id` (`feedback_question_id`) USING BTREE,
  KEY `user_id` (`user_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Danh sách câu trả lời của bảng khảo sát';


DROP TABLE IF EXISTS `feedback_question`;
CREATE TABLE `feedback_question` (
  `feedback_question_id` int(11) NOT NULL AUTO_INCREMENT,
  `feedback_question_type` enum('rating','comment','product','service','service_card') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'rating' COMMENT 'rating: câu hỏi đánh giá; comment: câu hỏi dạng phát biểu cảm nghĩ',
  `feedback_question_title` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `feedback_question_active` tinyint(1) DEFAULT 1 COMMENT '0: Ẩn; 1: Hiển thị',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `object_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`feedback_question_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Danh sách câu hỏi của bảng khảo sát';


DROP TABLE IF EXISTS `file_activity_log`;
CREATE TABLE `file_activity_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `log_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` bigint(20) unsigned DEFAULT NULL,
  `causer_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `causer_id` bigint(20) unsigned DEFAULT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `batch_uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `object_id` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'id của file hoặc folder',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `subject` (`subject_type`,`subject_id`) USING BTREE,
  KEY `causer` (`causer_type`,`causer_id`) USING BTREE,
  KEY `activity_log_log_name_index` (`log_name`) USING BTREE,
  KEY `activity_log_object_id_index` (`object_id`(768)) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `file_comments`;
CREATE TABLE `file_comments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'File Id',
  `staff_id` int(11) NOT NULL,
  `message` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `file_file`;
CREATE TABLE `file_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `folder_key` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` int(11) DEFAULT NULL,
  `owner` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_version` int(5) DEFAULT 1,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `key` (`key`) USING BTREE,
  UNIQUE KEY `folder_key_2` (`folder_key`,`name`) USING BTREE,
  KEY `folder_key` (`folder_key`) USING BTREE,
  KEY `name` (`name`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `file_folder`;
CREATE TABLE `file_folder` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `owner` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `permission` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_delete` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `key` (`key`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `file_indentity`;
CREATE TABLE `file_indentity` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sid` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Staff indentity epoint',
  `name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Name',
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Email',
  `access_key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Minio access key',
  `secret_key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Minio secret key',
  `active` tinyint(4) NOT NULL DEFAULT 1 COMMENT 'Trạng thái tài khoản: 1 => ok, 0 => quéo queo',
  `created_by` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'User epoint tạo tài khoản',
  `updated_by` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'User epoint cập nhật tài khoản',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `inventory_checkings`;
CREATE TABLE `inventory_checkings` (
  `inventory_checking_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `warehouse_id` int(11) DEFAULT NULL,
  `checking_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'phiếu kiểm kê : KK + nam + thang + ngay + (id tự tăng phiếu )',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `status` enum('draft','success') COLLATE utf8mb4_unicode_ci NOT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`inventory_checking_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `inventory_checking_details`;
CREATE TABLE `inventory_checking_details` (
  `inventory_checking_detail_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `inventory_checking_id` int(11) NOT NULL,
  `product_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity_old` int(11) DEFAULT NULL,
  `quantity_new` int(11) DEFAULT NULL,
  `quantity_difference` int(11) DEFAULT NULL,
  `current_price` decimal(10,3) NOT NULL,
  `total` decimal(10,3) NOT NULL,
  `type_resolve` enum('not','output','input') COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ghi chú',
  `updated_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`inventory_checking_detail_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `inventory_checking_detail_serial`;
CREATE TABLE `inventory_checking_detail_serial` (
  `inventory_checking_detail_serial_id` int(11) NOT NULL AUTO_INCREMENT,
  `inventory_checking_detail_id` int(11) DEFAULT NULL COMMENT 'Link với chi tiết phiếu kiểm kho',
  `product_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mã sản phẩm',
  `serial` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Số seri',
  `barcode` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã vạch',
  `inventory_checking_status_id` int(11) DEFAULT 0 COMMENT 'Trạng thái',
  `is_new` tinyint(1) DEFAULT 0 COMMENT 'Kiểm tra loại mới. 1 là cần tạo mới , 0 là không cần tạo mới ',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`inventory_checking_detail_serial_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `inventory_checking_log`;
CREATE TABLE `inventory_checking_log` (
  `inventory_checking_log_id` int(11) NOT NULL AUTO_INCREMENT,
  `inventory_checking_id` int(11) DEFAULT NULL COMMENT 'Id kiểm kho',
  `staff_id` int(11) DEFAULT NULL COMMENT 'Id nhân viên',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nội dung cập nhật',
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Lý do chung',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`inventory_checking_log_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `inventory_checking_status`;
CREATE TABLE `inventory_checking_status` (
  `inventory_checking_status_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên trạng thái',
  `is_delete` tinyint(1) DEFAULT 0 COMMENT 'Trạng thái xoá',
  `is_active` tinyint(1) DEFAULT 1 COMMENT 'Trạng thái kích hoạt',
  `is_default` tinyint(1) DEFAULT 0 COMMENT 'Trạng thái mặc định',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`inventory_checking_status_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `inventory_inputs`;
CREATE TABLE `inventory_inputs` (
  `inventory_input_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `warehouse_id` int(11) NOT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `pi_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'phiếu nhập kho : PN + nam + thang + ngay + (id tự tăng phiếu )',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `approved_at` datetime DEFAULT NULL,
  `status` enum('success','inprogress','new','draft','cancel') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_recived` int(11) DEFAULT NULL,
  `date_recived` datetime DEFAULT NULL,
  `object_id` int(11) DEFAULT NULL,
  `type` enum('normal','transfer','checking','return') COLLATE utf8mb4_unicode_ci DEFAULT 'normal',
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `inventory_checking_id` int(11) DEFAULT NULL COMMENT 'Id kiểm kho nếu được tạo từ kiểm kho',
  PRIMARY KEY (`inventory_input_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `inventory_input_details`;
CREATE TABLE `inventory_input_details` (
  `inventory_input_detail_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `inventory_input_id` int(11) DEFAULT NULL,
  `product_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `current_price` decimal(16,3) DEFAULT NULL,
  `quantity_recived` int(11) DEFAULT NULL,
  `total` decimal(16,3) unsigned DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`inventory_input_detail_id`) USING BTREE,
  KEY `filling_station_inventory_workflow_header_id` (`inventory_input_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `inventory_input_detail_serial`;
CREATE TABLE `inventory_input_detail_serial` (
  `inventory_input_detail_serial_id` int(11) NOT NULL AUTO_INCREMENT,
  `inventory_input_detail_id` int(11) DEFAULT NULL COMMENT 'Link với chi tiết phiếu nhập kho',
  `product_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mã sản phẩm',
  `serial` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Số seri',
  `barcode` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã vạch',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  `is_export` tinyint(1) DEFAULT 0 COMMENT 'Đã xuất kho',
  PRIMARY KEY (`inventory_input_detail_serial_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `inventory_outputs`;
CREATE TABLE `inventory_outputs` (
  `inventory_output_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `warehouse_id` int(11) NOT NULL,
  `po_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'phiếu xuất kho: XK + nam + thang + ngay + (id tự tăng phiếu )',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `approved_at` datetime DEFAULT NULL,
  `status` enum('success','inprogress','new','draft','cancel') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('normal','retail','transfer','checking') COLLATE utf8mb4_unicode_ci DEFAULT 'normal',
  `object_id` int(11) DEFAULT NULL,
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `inventory_checking_id` int(11) DEFAULT NULL COMMENT 'id kiểm kho nếu được tạo từ kiểm kho',
  PRIMARY KEY (`inventory_output_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `inventory_output_details`;
CREATE TABLE `inventory_output_details` (
  `inventory_output_detail_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `inventory_output_id` int(11) DEFAULT NULL,
  `product_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `current_price` decimal(16,3) DEFAULT NULL,
  `total` decimal(16,3) unsigned DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`inventory_output_detail_id`) USING BTREE,
  KEY `filling_station_inventory_workflow_header_id` (`inventory_output_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `inventory_output_detail_serial`;
CREATE TABLE `inventory_output_detail_serial` (
  `inventory_output_detail_serial_id` int(11) NOT NULL AUTO_INCREMENT,
  `inventory_output_detail_id` int(11) DEFAULT NULL COMMENT 'Link với chi tiết phiếu xuất kho',
  `product_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã sản phẩm',
  `serial` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Số seri',
  `barcode` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã vạch',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`inventory_output_detail_serial_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `inventory_tranfers`;
CREATE TABLE `inventory_tranfers` (
  `inventory_tranfer_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `warehouse_to` int(11) NOT NULL,
  `warehouse_from` int(11) DEFAULT NULL,
  `transfer_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'phiếu chuyển kho : CK + nam + thang + ngay + (id tự tăng phiếu )',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `approved_at` datetime DEFAULT NULL,
  `transfer_at` datetime DEFAULT NULL,
  `status` enum('success','inprogress','new','draft','cancel') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`inventory_tranfer_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `inventory_tranfer_details`;
CREATE TABLE `inventory_tranfer_details` (
  `inventory_tranfer_detail_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `inventory_tranfer_id` int(11) DEFAULT NULL,
  `product_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `current_price` decimal(10,3) DEFAULT NULL,
  `quantity_tranfer` int(11) DEFAULT NULL,
  `total` decimal(10,3) unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`inventory_tranfer_detail_id`) USING BTREE,
  KEY `filling_station_inventory_workflow_header_id` (`inventory_tranfer_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `maintenance`;
CREATE TABLE `maintenance` (
  `maintenance_id` int(11) NOT NULL AUTO_INCREMENT,
  `maintenance_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'mã phiếu bảo trì ',
  `customer_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mã khách hàng',
  `warranty_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'mã phiếu bảo hành ',
  `maintenance_cost` decimal(16,3) DEFAULT NULL COMMENT 'chi phí bảo trì',
  `warranty_value` decimal(16,3) DEFAULT NULL COMMENT 'giá trị dc bảo hành',
  `insurance_pay` decimal(16,3) DEFAULT NULL COMMENT 'bảo hiểm chi trả',
  `amount_pay` decimal(16,3) DEFAULT NULL COMMENT 'Số tiền phải trả',
  `total_amount_pay` decimal(16,3) DEFAULT NULL COMMENT 'Tổng tiền phải trả: Số tiền phải trả + total phí phát sinh',
  `staff_id` int(11) NOT NULL COMMENT 'Nhân viên thực hiện',
  `object_type` enum('service_card','product','service') COLLATE utf8mb4_unicode_ci NOT NULL,
  `object_type_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'mã sản phẩm, dịch vụ, thẻ dịch vụ được bảo hành',
  `object_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'mã đối tượng bảo trì',
  `object_serial` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'số serial đối tượng bảo trì',
  `object_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'tình trạng đối tượng bảo trì',
  `maintenance_content` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'nội dung bảo trì',
  `date_estimate_delivery` datetime DEFAULT NULL COMMENT 'ngày dự kiến trả hàng sau khi bảo hành',
  `status` enum('new','received','processing','ready_delivery','finish','cancel') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '''new'': mới ,''received'' : đã nhận hàng ,''processing'': đang xử lý,''ready_delivery'': sẵn sàng trả hàng,''finish'': hoàn tất',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`maintenance_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `maintenance_cost`;
CREATE TABLE `maintenance_cost` (
  `maintenance_cost_id` int(11) NOT NULL AUTO_INCREMENT,
  `maintenance_id` int(11) NOT NULL COMMENT 'mã phiếu bảo trì',
  `maintenance_cost_type` int(11) NOT NULL COMMENT 'loại chi phí bảo trì ',
  `cost` decimal(16,3) NOT NULL COMMENT 'chi phí ',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`maintenance_cost_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `maintenance_cost_type`;
CREATE TABLE `maintenance_cost_type` (
  `maintenance_cost_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `maintenance_cost_type_name_vi` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên loại chi phí bảo trì',
  `maintenance_cost_type_name_en` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên loại chi phí bảo trì en',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'trạng thái ',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_delete` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`maintenance_cost_type_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Danh sách phương thức thanh toán';


DROP TABLE IF EXISTS `maintenance_images`;
CREATE TABLE `maintenance_images` (
  `maintenance_image_id` int(11) NOT NULL AUTO_INCREMENT,
  `maintenance_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mã phiếu bảo trì',
  `type` enum('before','after') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'loại ảnh : trước bảo trì, sau bảo trì',
  `link` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Link ảnh',
  `is_deleted` tinyint(4) DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`maintenance_image_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `manage_comment`;
CREATE TABLE `manage_comment` (
  `manage_comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `manage_work_id` int(11) DEFAULT NULL COMMENT 'Id công việc',
  `manage_parent_comment_id` int(11) DEFAULT NULL COMMENT 'manage_comment_id cha của comment',
  `staff_id` int(11) DEFAULT NULL COMMENT 'Id nhân viên nhắn tin',
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nội dung tin nhắn',
  `path` varchar(255) DEFAULT NULL COMMENT 'link hình ảnh',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`manage_comment_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Danh sách comment';


DROP TABLE IF EXISTS `manage_config_list`;
CREATE TABLE `manage_config_list` (
  `manage_config_list_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `value` varchar(1024) DEFAULT NULL,
  `route_name` varchar(255) DEFAULT NULL,
  `type` enum('column,filter') CHARACTER SET utf8 DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`manage_config_list_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `manage_config_notification`;
CREATE TABLE `manage_config_notification` (
  `manage_config_notification_id` int(11) NOT NULL AUTO_INCREMENT,
  `manage_config_notification_key` varchar(255) DEFAULT NULL COMMENT 'Key để lấy thông báo',
  `manage_config_notification_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tiêu đề thông báo hạng mục',
  `is_mail` tinyint(1) DEFAULT NULL COMMENT 'Gửi qua mail',
  `is_noti` tinyint(1) DEFAULT NULL COMMENT 'Thông báo qua noti',
  `manage_config_notification_message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nội dung tin nhắn',
  `is_created` tinyint(1) DEFAULT NULL COMMENT 'Gửi cho người tạo',
  `is_processor` tinyint(1) DEFAULT NULL COMMENT 'Gửi cho người thực hiện',
  `is_support` tinyint(1) DEFAULT NULL COMMENT 'Gửi cho người theo dõi',
  `is_approve` tinyint(1) DEFAULT NULL COMMENT 'Gửi cho người duyệt',
  `is_active` tinyint(1) DEFAULT NULL COMMENT 'Trạng thái kích hoạt',
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Hình của notification',
  `has_detail` tinyint(4) DEFAULT 1 COMMENT 'Có trang chi tiết không',
  `detail_action_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên button tương tác',
  `detail_action` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Action khi click ở app',
  `detail_action_params` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Param bổ sung',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`manage_config_notification_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Quản lý công việc cấu hình thông báo';


DROP TABLE IF EXISTS `manage_document_file`;
CREATE TABLE `manage_document_file` (
  `manage_document_file_id` int(11) NOT NULL AUTO_INCREMENT,
  `manage_work_id` int(11) DEFAULT NULL COMMENT 'Id thông tin hồ sơ',
  `file_name` varchar(255) DEFAULT NULL COMMENT 'Tên file',
  `file_type` varchar(255) DEFAULT NULL COMMENT 'hình ảnh hoặc file . image là hình ảnh, file là file',
  `note` varchar(255) DEFAULT NULL COMMENT 'Ghi chú',
  `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Đường dẫn đến file',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`manage_document_file_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Danh sách file theo hồ sơ';


DROP TABLE IF EXISTS `manage_history`;
CREATE TABLE `manage_history` (
  `manage_history_id` int(11) NOT NULL AUTO_INCREMENT,
  `manage_work_id` int(11) DEFAULT NULL COMMENT 'Id công việc',
  `staff_id` int(11) DEFAULT NULL COMMENT 'Id nhân viên',
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nội dung text',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`manage_history_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Danh sách lịch sử';


DROP TABLE IF EXISTS `manage_project`;
CREATE TABLE `manage_project` (
  `manage_project_id` int(11) NOT NULL AUTO_INCREMENT,
  `manage_project_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên dự án',
  `is_active` tinyint(1) DEFAULT NULL COMMENT 'Trạng thái',
  `created_at` datetime DEFAULT NULL COMMENT 'Thời gian tạo',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`manage_project_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Danh sách dự án';


DROP TABLE IF EXISTS `manage_remind`;
CREATE TABLE `manage_remind` (
  `manage_remind_id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_id` int(11) DEFAULT NULL COMMENT 'Nhắc ai',
  `date_remind` datetime DEFAULT NULL COMMENT 'Thời gian nhắc',
  `time` int(11) DEFAULT NULL COMMENT 'Thời giam trước nhắc nhở',
  `time_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Loại thời gian trước nhắc nhở . w: tuần d : Ngày , h :giờ, m : phút',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nội dung nhắc nhở',
  `is_sent` tinyint(1) DEFAULT 0 COMMENT 'Kiểm tra nhắc nhở đã gửi. 1 : là đã gửi , 0 là chưa gửi',
  `manage_work_id` int(11) DEFAULT NULL COMMENT 'Tác vụ là id công việc',
  `is_active` tinyint(1) DEFAULT 1 COMMENT 'Trạng thái',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `title` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tiêu đề',
  PRIMARY KEY (`manage_remind_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Danh sách nhắc nhở';


DROP TABLE IF EXISTS `manage_repeat_time`;
CREATE TABLE `manage_repeat_time` (
  `manage_repeat_time_id` int(11) NOT NULL AUTO_INCREMENT,
  `manage_work_id` int(11) DEFAULT NULL COMMENT 'Id công việc',
  `time` int(11) DEFAULT NULL COMMENT 'Thời gian là ngày , tuần , tháng .Thứ trong tuần  2 -> CN  từ 0 -> 6',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`manage_repeat_time_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Quản lý cấu hình các tháng trong lặp lại';


DROP TABLE IF EXISTS `manage_role`;
CREATE TABLE `manage_role` (
  `manage_role_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_group_id` int(11) DEFAULT NULL COMMENT 'Id quyền',
  `is_all` tinyint(1) DEFAULT NULL COMMENT 'Tất cả',
  `is_branch` tinyint(1) DEFAULT NULL COMMENT 'Chi nhánh',
  `is_department` tinyint(1) DEFAULT NULL COMMENT 'Phòng ban',
  `is_own` tinyint(1) DEFAULT NULL COMMENT 'Sở hữu',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`manage_role_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Cấu hình quản lý role';


DROP TABLE IF EXISTS `manage_status`;
CREATE TABLE `manage_status` (
  `manage_status_id` int(11) NOT NULL AUTO_INCREMENT,
  `manage_status_value` int(11) DEFAULT NULL COMMENT 'Giá trị',
  `manage_status_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên trạng thái',
  `manage_status_color` varchar(255) DEFAULT NULL COMMENT 'Màu trạng thái',
  `is_default` tinyint(1) DEFAULT 0 COMMENT 'Trạng thái mặc định',
  `is_active` tinyint(1) DEFAULT NULL COMMENT 'Trạng thái',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`manage_status_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Danh sách trạng thái';


DROP TABLE IF EXISTS `manage_status_config`;
CREATE TABLE `manage_status_config` (
  `manage_status_config_id` int(11) NOT NULL AUTO_INCREMENT,
  `manage_status_group_config_id` int(11) DEFAULT NULL COMMENT 'Id nhóm cấu hình status',
  `manage_status_config_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên status hiển thị',
  `manage_status_id` int(11) DEFAULT NULL COMMENT 'Id trạng thái',
  `manage_color_code` varchar(255) DEFAULT NULL COMMENT 'mã màu',
  `position` int(11) DEFAULT NULL COMMENT 'Vị trí',
  `is_edit` tinyint(1) DEFAULT NULL COMMENT 'Được phép sửa',
  `is_deleted` tinyint(1) DEFAULT NULL COMMENT 'Được phép xoá',
  `is_default` tinyint(1) DEFAULT NULL COMMENT 'Trạng thái mặc định . Nếu là 1 không thể xoá',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`manage_status_config_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Quản lý status hiển thị ở karban';


DROP TABLE IF EXISTS `manage_status_config_map`;
CREATE TABLE `manage_status_config_map` (
  `manage_status_config_map_id` int(11) NOT NULL AUTO_INCREMENT,
  `manage_status_config_id` int(11) DEFAULT NULL COMMENT 'Id cấu hình trạng thái',
  `manage_status_id` int(11) DEFAULT NULL COMMENT 'Id status',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`manage_status_config_map_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Quản lý danh sách trạng thái theo cấu hình trạng thái';


DROP TABLE IF EXISTS `manage_status_group_config`;
CREATE TABLE `manage_status_group_config` (
  `manage_status_group_config_id` int(11) NOT NULL AUTO_INCREMENT,
  `manage_status_group_config_title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Tên nhóm cấu hình trạng thái',
  `position` int(11) DEFAULT NULL COMMENT 'Vị trí trạng thái',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`manage_status_group_config_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Danh sách nhóm cấu hình trạng thái';


DROP TABLE IF EXISTS `manage_tags`;
CREATE TABLE `manage_tags` (
  `manage_tag_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id tags công việc',
  `manage_tag_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'tên tags',
  `manage_tag_icon` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`manage_tag_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Bảng tag công việc\r\n';


DROP TABLE IF EXISTS `manage_type_work`;
CREATE TABLE `manage_type_work` (
  `manage_type_work_id` int(11) NOT NULL AUTO_INCREMENT,
  `manage_type_work_key` varchar(255) DEFAULT NULL COMMENT 'từ khoá ',
  `manage_type_work_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tiêu đề',
  `manage_type_work_icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Icon',
  `manage_type_work_default` tinyint(1) DEFAULT 0 COMMENT 'Loại mặc định',
  `is_active` tinyint(1) DEFAULT 1 COMMENT 'Trạng thái',
  `created_at` datetime DEFAULT NULL COMMENT 'Thời gian tạo',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`manage_type_work_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Loại công việc';


DROP TABLE IF EXISTS `manage_work`;
CREATE TABLE `manage_work` (
  `manage_work_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id công việc',
  `manage_work_customer_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'customer' COMMENT 'Loại khách hàng. customer : loại customer , lead : tạo từ lead, deal : tạo từ deal deal',
  `manage_project_id` int(11) DEFAULT NULL COMMENT 'Id dự án',
  `manage_work_code` varchar(255) DEFAULT NULL COMMENT 'Mã công việc',
  `manage_type_work_id` int(11) DEFAULT NULL COMMENT 'Id loại công việc',
  `manage_work_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tiêu đề công việc',
  `date_start` datetime DEFAULT NULL COMMENT 'Thời gian bắt đầu',
  `date_end` datetime DEFAULT NULL COMMENT 'Thời gian kết thúc',
  `date_finish` datetime DEFAULT NULL COMMENT 'Thời gian hoàn thành',
  `processor_id` int(11) DEFAULT NULL COMMENT 'Id nhân viên thực hiện',
  `assignor_id` int(11) DEFAULT NULL COMMENT 'Id nhân viên giao việc',
  `time` int(11) DEFAULT NULL COMMENT 'Thời gian ước lượng',
  `time_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Loại thời gian ước lượng. d : ngày, h : giờ',
  `progress` int(11) DEFAULT NULL COMMENT 'Tiến độ dự án . Tiến độ là số nguyên',
  `customer_id` int(11) DEFAULT NULL COMMENT 'Id khách hàng liên quan',
  `obj_id` int(11) DEFAULT NULL COMMENT 'Khác null nếu customer_id từ lead hoặc deal',
  `customer_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên khách hàng liên quan',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mô tả công việc',
  `approve_id` int(11) DEFAULT NULL COMMENT 'Id người duyệt',
  `is_approve_id` tinyint(1) DEFAULT NULL COMMENT 'Công việc bắt buộc có người phê duyệt',
  `parent_id` int(11) DEFAULT NULL COMMENT 'Tác vụ cha',
  `type_card_work` varchar(255) DEFAULT NULL COMMENT 'Loại thẻ công việc. bonus : thưởng , kpi : KPI',
  `priority` int(11) DEFAULT NULL COMMENT 'Mức độ ưu tiên. 1 : Cao, 2 : Bình thường, 3 : Thấp',
  `manage_status_id` int(11) DEFAULT NULL COMMENT 'Id trạng thái',
  `repeat_type` varchar(255) DEFAULT NULL COMMENT 'Loại lặp lại . none : Không có , daily : hằng ngày , weekly : hằng tuần, monthly : hằng tháng ',
  `repeat_end` varchar(255) DEFAULT NULL COMMENT 'Kết thúc lặp lại. none : Không bao giờ , after : Sau , date : vào ngày',
  `repeat_end_time` int(11) DEFAULT NULL COMMENT 'Thời gian lặp lại ở kết thúc .',
  `repeat_end_type` varchar(255) DEFAULT NULL COMMENT 'Loại thời gian lặp lại ở kết thúc .  d : ngày , w : tuần , m : tháng',
  `repeat_end_full_time` datetime DEFAULT NULL COMMENT 'Thời gian kết thúc vào ngày',
  `repeat_time` time DEFAULT NULL COMMENT 'Giờ lặp',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `is_overdue_noti` tinyint(1) DEFAULT 0 COMMENT 'Kiểm tra đã gửi noti quá hạn',
  `is_booking` tinyint(1) DEFAULT 0 COMMENT 'Check đặt lịch. 1 : đã check : 0 : chưa check',
  PRIMARY KEY (`manage_work_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Danh sách quản lý công việc';


DROP TABLE IF EXISTS `manage_work_support`;
CREATE TABLE `manage_work_support` (
  `manage_work_support_id` int(11) NOT NULL AUTO_INCREMENT,
  `manage_work_id` int(11) DEFAULT NULL COMMENT 'Id công việc',
  `staff_id` int(11) DEFAULT NULL COMMENT 'Id nhân viên hỗ trợ',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`manage_work_support_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Bảng lưu danh sách nhân viên hỗ trợ';


DROP TABLE IF EXISTS `manage_work_tag`;
CREATE TABLE `manage_work_tag` (
  `manage_work_tag_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id map',
  `manage_work_id` int(11) DEFAULT NULL COMMENT 'Id công việc',
  `manage_tag_id` int(11) DEFAULT NULL COMMENT 'Id tag công việc',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`manage_work_tag_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Bảng map giữa bảng tag và bảng công việc\r\n';


DROP TABLE IF EXISTS `manuals`;
CREATE TABLE `manuals` (
  `manual_id` int(11) NOT NULL AUTO_INCREMENT,
  `manual_group_id` int(11) NOT NULL,
  `manual_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'title',
  `manual_content` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'nội dung',
  `is_actived` tinyint(1) DEFAULT 1,
  `is_deleted` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`manual_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='trung tâm cài đặt hỗ trợ';


DROP TABLE IF EXISTS `manual_group`;
CREATE TABLE `manual_group` (
  `manual_group_id` int(11) NOT NULL AUTO_INCREMENT,
  `manual_group_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'tiêu đề',
  `is_actived` tinyint(1) DEFAULT 1,
  `is_deleted` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`manual_group_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='nhóm trung tâm cài đặt hỗ trợ';


DROP TABLE IF EXISTS `map_product_attributes`;
CREATE TABLE `map_product_attributes` (
  `map_product_attribute_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_attribute_groupd_id` int(11) DEFAULT NULL,
  `product_attribute_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`map_product_attribute_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `map_role_group_staff`;
CREATE TABLE `map_role_group_staff` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_group_id` int(11) DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `is_actived` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `member_levels`;
CREATE TABLE `member_levels` (
  `member_level_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `point` int(11) NOT NULL,
  `discount` int(11) NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_actived` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `is_deleted` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`member_level_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `mystore_filter_group`;
CREATE TABLE `mystore_filter_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT 1,
  `filter_group_type` enum('user_define','auto') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Kiểu tự định nghĩa/ auto',
  `tenant_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_brand` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `filter_condition_rule_A` enum('or','and') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `filter_condition_rule_B` enum('or','and') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Nhóm khách hàng nhận thông báo';


DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
  `new_id` int(11) NOT NULL AUTO_INCREMENT,
  `title_vi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title_en` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description_vi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description_en` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description_detail_vi` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description_detail_en` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Sản phẩm liên quan',
  `service` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Dịch vụ liên quan',
  `is_actived` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  `is_deleted` tinyint(4) NOT NULL DEFAULT 0,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`new_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `notification`;
CREATE TABLE `notification` (
  `notification_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID tự tăng',
  `notification_detail_id` bigint(20) DEFAULT NULL COMMENT 'Chi tiet notification',
  `user_id` int(11) NOT NULL COMMENT 'ID user',
  `notification_avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Avatar của thông báo',
  `notification_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Title',
  `notification_message` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nội dung thông báo',
  `is_read` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Tin nhắn đọc chua',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  `is_new` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0: mới 1: cũ',
  PRIMARY KEY (`notification_id`) USING BTREE,
  KEY `notification_detail_id` (`notification_detail_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Thông báo';


DROP TABLE IF EXISTS `notification_detail`;
CREATE TABLE `notification_detail` (
  `notification_detail_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID notification tu tang',
  `tenant_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ID tenant. Neu notification cua mystore thi nul',
  `background` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Noi dung thong bao',
  `action_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên hiển thị của action',
  `action` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'App route khi click vao thong bao',
  `action_params` varchar(512) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Param route',
  `is_brand` tinyint(1) DEFAULT 0 COMMENT '0 nếu ở backoffice, 1 nếu gửi từ brandportal',
  `created_at` datetime DEFAULT NULL COMMENT 'Thoi gian tao',
  `created_by` int(11) DEFAULT NULL COMMENT 'Nguoi tao',
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Thoi gian cap nhat',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Nguoi cap nhat',
  PRIMARY KEY (`notification_detail_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Chi tiet notification';


DROP TABLE IF EXISTS `notification_queue`;
CREATE TABLE `notification_queue` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID tự tăng',
  `notification_detail_id` bigint(20) unsigned DEFAULT NULL COMMENT 'Noi dung gui notification',
  `tenant_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ID xac dinh brand',
  `send_type` enum('all','group','unicast') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Gui tat ca user hoac nhom',
  `send_type_object` int(11) DEFAULT NULL COMMENT 'ID object',
  `notification_avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Avatar của thông báo',
  `notification_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Title',
  `notification_message` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nội dung thông báo',
  `send_at` datetime NOT NULL COMMENT 'Thoi gian hen gio',
  `is_brand` tinyint(1) NOT NULL COMMENT 'Notification cua brand hay mystore',
  `created_at` datetime DEFAULT NULL COMMENT 'Thoi gian tao',
  `created_by` int(11) DEFAULT NULL COMMENT 'Nguoi tao',
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Nguoi cap nhat',
  `is_actived` tinyint(4) DEFAULT NULL,
  `is_send` tinyint(1) DEFAULT 0 COMMENT 'Đanh dau da gui',
  `is_deleted` tinyint(1) DEFAULT 0 COMMENT 'Danh dau huy,xoa',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `notification_detail_id` (`notification_detail_id`) USING BTREE,
  KEY `tenant_id_is_send` (`tenant_id`,`is_send`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Notification hẹn giờ gửi';


DROP TABLE IF EXISTS `notification_template`;
CREATE TABLE `notification_template` (
  `notification_template_id` int(11) NOT NULL AUTO_INCREMENT,
  `notification_detail_id` int(11) DEFAULT NULL,
  `notification_type_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'tiêu đề thông báo',
  `title_short` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'tiêu đề hiển thị ngắn',
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'thông tin nổi bật của thông báo',
  `action_group` tinyint(4) DEFAULT 1 COMMENT '0 không có hành động, 1 có hành động',
  `action_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'tên hành động',
  `from_type` enum('all','group') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'loại người nhận',
  `from_type_object` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `send_type` enum('immediately','schedule') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'loại thời gian gửi thông báo : ngay lập tức hay lịch',
  `send_at` datetime DEFAULT NULL,
  `schedule_option` enum('specific','none') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Loại nếu gửi tùy chọn : chính xác hoặc tương đối',
  `schedule_value` tinyint(4) DEFAULT NULL COMMENT 'giá trị',
  `schedule_value_type` enum('day','hours','minute') COLLATE utf8mb4_unicode_ci DEFAULT 'hours',
  `send_status` enum('sent','not','pending') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_actived` tinyint(4) NOT NULL DEFAULT 1,
  `cost` float(16,3) DEFAULT NULL COMMENT 'Chi phí cho chiến dịch',
  `is_deal_created` tinyint(1) DEFAULT 0 COMMENT 'check có tạo deal hay không? 1 có, 0 không',
  PRIMARY KEY (`notification_template_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `notification_template_auto`;
CREATE TABLE `notification_template_auto` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID tự tăng',
  `key` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Key đùng để xác định notification',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tiêu đề',
  `message` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nội dung tin nhắn',
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Hình của notification',
  `has_detail` tinyint(1) DEFAULT 0 COMMENT 'Có trang chi tiết không',
  `detail_background` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Background detail',
  `detail_content` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nội dung detail',
  `detail_action_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên button tương tác',
  `detail_action` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Action khi click ở app',
  `detail_action_params` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Param bổ sung',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `key` (`key`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Template cấu hình nội dung gửi Notification';


DROP TABLE IF EXISTS `notification_template_deal`;
CREATE TABLE `notification_template_deal` (
  `notification_template_deal_id` int(11) NOT NULL AUTO_INCREMENT,
  `notification_template_id` int(11) DEFAULT NULL,
  `closing_date` date DEFAULT NULL COMMENT 'ngày kết thúc dự kiến ',
  `pipeline_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `journey_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Ngươi update',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `amount` decimal(16,3) DEFAULT NULL,
  PRIMARY KEY (`notification_template_deal_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `notification_template_deal_detail`;
CREATE TABLE `notification_template_deal_detail` (
  `notification_template_deal_detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `notification_template_deal_id` int(11) NOT NULL,
  `object_id` int(11) NOT NULL COMMENT 'id của dich vụ , sản phẩm hoac thẻ dịch vụ tuỳ objecttype ',
  `object_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'tên của dich vụ , sản phẩm hoac thẻ dịch vụ tuỳ objecttype ',
  `object_type` enum('service_card','service','product','member_card','product_gift','service_gift','service_card_gift') CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `object_code` varchar(1024) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `price` decimal(16,3) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `discount` decimal(16,3) DEFAULT NULL,
  `amount` decimal(16,3) DEFAULT NULL,
  `voucher_code` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT NULL,
  `is_deleted` tinyint(4) DEFAULT 0,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`notification_template_deal_detail_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `notification_type`;
CREATE TABLE `notification_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_name_vi` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_name_en` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_detail` tinyint(1) DEFAULT 0 COMMENT 'có đích đến chi tiết',
  `detail_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'loại popup danh sách tương ứng với action app',
  `action` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'App route khi click vao thong bao',
  `from` enum('backoffice','brand','all') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'dùng cho cms nào ?',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_notify` tinyint(1) DEFAULT 1,
  `is_banner` tinyint(1) DEFAULT 0 COMMENT 'dùng cho banner',
  `is_deleted` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `object_accounting_type`;
CREATE TABLE `object_accounting_type` (
  `object_accounting_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `object_accounting_type_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã loại đối tượng thu chi',
  `object_accounting_type_name_vi` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên loại đối tượng thu chi tiếng Việt',
  `object_accounting_type_name_en` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên loại đối tượng thu chi tiếng Anh',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Trạng thái',
  `is_system` tinyint(1) DEFAULT 1,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`object_accounting_type_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `oc_account`;
CREATE TABLE `oc_account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên tài khoản',
  `password` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mật khẩu',
  `is_actived` tinyint(4) DEFAULT 1 COMMENT 'Trạng thái',
  `enabled_webhook` tinyint(4) DEFAULT 0 COMMENT 'Trạng thái webhook',
  `link_webhook` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Link webhook',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `oc_extensions`;
CREATE TABLE `oc_extensions` (
  `extension_id` int(11) NOT NULL AUTO_INCREMENT,
  `extension_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Số extension cho oncall cung cấp',
  `full_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên extension',
  `user_agent` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Email từ hệ thống oncall',
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Sdt từ hệ thống oncall',
  `staff_id` int(11) DEFAULT NULL COMMENT 'Người được phân bổ',
  `status` tinyint(4) DEFAULT 1 COMMENT '0: ko hoạt đông, 1: đang hoạt động (ko cho edit sync từ hệ thống oncall)',
  `is_deleted` tinyint(4) DEFAULT 0,
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`extension_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `oc_histories`;
CREATE TABLE `oc_histories` (
  `history_id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã gọi cuộc oncall',
  `object_id_call` int(11) NOT NULL COMMENT 'Người gọi ăn theo loại cuộc gọi',
  `extension_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Extension thực hiện cuộc gọi',
  `source_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nguồn cuộc gọi (lead: khách hàng tiềm năng, deal: cơ hội bán hàng, customer: khách hàng, staff: nhân viên)',
  `object_id` int(11) DEFAULT NULL COMMENT 'Link id đối tượng',
  `object_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Link code đối tượng',
  `object_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên đối tượng nhận',
  `object_phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Số điện thoại người nhận',
  `start_time` datetime DEFAULT NULL COMMENT 'Thời gian bắt đầu',
  `end_time` datetime DEFAULT NULL COMMENT 'Thời gian kết thúc',
  `reply_time` datetime DEFAULT NULL COMMENT 'Thời gian trả lời',
  `ring_time` datetime DEFAULT NULL COMMENT 'Thời gian đổ chuông',
  `total_ring_time` int(11) DEFAULT NULL COMMENT 'Tổng thời gian đổ chuông (đơn vị giây)',
  `total_reply_time` int(11) DEFAULT NULL COMMENT 'Tổng thời gian trả lời (đơn vị giây)',
  `postage` decimal(16,3) DEFAULT NULL COMMENT 'Cước phí',
  `history_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'out: Cuộc gọi đi, in: Cuộc gọi đến',
  `status` tinyint(4) DEFAULT NULL COMMENT '0: Thất bại, 1: Thành công',
  `error_text` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nội dung lỗi',
  `link_record` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'File ghi âm',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`history_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `oc_sources`;
CREATE TABLE `oc_sources` (
  `source_id` int(11) NOT NULL AUTO_INCREMENT,
  `source_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã nguồn cuộc gọi',
  `source_name_vi` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên nguồn cuộc gọi vi',
  `source_name_en` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên nguồn cuộc gọi en',
  `is_actived` tinyint(4) DEFAULT 1 COMMENT 'Trạng thái',
  `is_deleted` tinyint(4) DEFAULT 0,
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`source_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `oncall_call`;
CREATE TABLE `oncall_call` (
  `oncall_call_id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ID định danh trã cho client',
  `oncall_log_id` int(11) DEFAULT NULL COMMENT 'ID khoá bảng oncall_log',
  `tenant` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'tenant',
  `client_id` int(11) DEFAULT NULL,
  `oncall_service_id` int(11) DEFAULT NULL COMMENT 'ID bảng oncall_service',
  `refer_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Code refer để biết user nào đang gọi',
  `extension` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ex',
  `caller` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'người gọi',
  `callee` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'bị gọi',
  `domain` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'F : fail, S : success',
  `error_desc` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_end` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`oncall_call_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `oncall_extension`;
CREATE TABLE `oncall_extension` (
  `oncall_extension_id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tenant_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `oncall_service_id` int(11) DEFAULT NULL,
  `extension_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `extension` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `access_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expires` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expires_time` datetime DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `web_access_password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `api_version` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transports` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`oncall_extension_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `oncall_log`;
CREATE TABLE `oncall_log` (
  `oncall_log_id` int(11) NOT NULL AUTO_INCREMENT,
  `oncall_log_uid` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tenant` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `from` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `oncall_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `call_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Id cuộc gọi',
  `call_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Trạng thái cuộc gọi',
  `callee` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'số bị gọi',
  `callee_domain` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Domain số bị gọi',
  `caller` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Số chủ gọi',
  `caller_display_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên / Số chủ gọi được hiển thị',
  `caller_domain` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Domain của số chủ gọi',
  `did_cid` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'DID hoặc CID',
  `direction` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Phân loại cuộc gọi vào/ra',
  `record_filepath` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Link file ghi âm',
  `start_time` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Thời gian bắt đầu cuộc gọi (định dạng UNIX)',
  `start_dtime` datetime DEFAULT NULL,
  `ring_time` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Thời gian đổ chuông (định dạng UNIX)',
  `ring_dtime` datetime DEFAULT NULL,
  `ring_duration` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tổng thời gian đổ chuông',
  `answered_time` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Thời gian trả lời cuộc gọi (định dạng UNIX)',
  `answered_dtime` datetime DEFAULT NULL,
  `talk_duration` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tổng thời gian đàm thoại',
  `ended_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nguyên nhân kết thúc cuộc gọi',
  `ended_time` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Thời gian kết thúc cuộc gọi (địnhdạng UNIX)',
  `ended_dtime` datetime DEFAULT NULL,
  `fail_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã code lỗi khi cuộc gọi kết thúc',
  `final_dest` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Số bị gọi cuối cùng',
  `outbound_caller_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Số đại diện dùng khi gọi ra',
  `related_callid1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Id cuộc gọi liên quan 1',
  `related_callid2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Id cuộc gọi liên quan 2',
  `oncall_tenant_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Id của tenant',
  `oncall_tenant_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên tenant',
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`oncall_log_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `oncall_log_detail`;
CREATE TABLE `oncall_log_detail` (
  `oncall_log_detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `oncall_log_id` int(11) DEFAULT NULL COMMENT 'ID bảng oncall_call',
  `oncall_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ID oncall trã về',
  `call_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ID oncall trã về',
  `caller` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'số chủ gọi',
  `callee` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'số bị gọi',
  `destination` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_time` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'thời gian bắt đầu cuộc gọi',
  `answer_time` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'thời gian trả lời cuộc gọi',
  `end_time` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ringing_time` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fail_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `related_callid` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duration` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'tổng thời gian cuộc đàm thoại',
  `session_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `related_callid_1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `related_callid_2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ring_duration` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'tổng thời gian đổ chuông',
  `talk_duration` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'tồng thời gian đàm thoại',
  `reroute_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `caller_endpoint` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `callee_endpoint` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `direction` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'phân loại cuộc gọi vào/ra',
  `end_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT ' nguyên nhân kết thúc cuộc gọi',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'tình trạng cuộc gọi',
  `outcid` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'số đại diện gọi ra',
  `didcid` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'số đại diện gọi vào',
  `call_cost` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'cước cuộc gọi',
  `target_cost` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`oncall_log_detail_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `oncall_log_ext`;
CREATE TABLE `oncall_log_ext` (
  `oncall_log_ext_id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `call_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `call_target` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `callee` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `caller` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `session_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tenant_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tenant_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `time` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`oncall_log_ext_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `oncall_log_rating`;
CREATE TABLE `oncall_log_rating` (
  `oncall_log_rating_id` int(11) NOT NULL AUTO_INCREMENT,
  `oncall_log_id` int(11) NOT NULL,
  `oncall_log_detail_id` int(11) DEFAULT NULL,
  `call_prefix` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `free_seconds` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `grace_period` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `interval1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `intervaln` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `connect_fee` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postcall_surcharge` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pricen` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`oncall_log_rating_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `oncall_log_record`;
CREATE TABLE `oncall_log_record` (
  `oncall_log_record_id` int(11) NOT NULL AUTO_INCREMENT,
  `oncall_log_id` int(11) DEFAULT NULL,
  `records_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `filepath` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fullpath` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `caller` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `callee` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `duration` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`oncall_log_record_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `oncall_log_target`;
CREATE TABLE `oncall_log_target` (
  `oncall_log_target_id` int(11) NOT NULL AUTO_INCREMENT,
  `oncall_log_id` int(11) DEFAULT NULL,
  `add_time` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `answered_time` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `end_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ended_time` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fail_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `related_callid` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ring_duration` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ring_time` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `talk_duration` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trunk_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`oncall_log_target_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `oncall_service`;
CREATE TABLE `oncall_service` (
  `oncall_service_id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `domain` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `access_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expires` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expires_time` datetime DEFAULT NULL,
  `api_version` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `enable_webhook` tinyint(1) DEFAULT 0,
  `webhook` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `webhook_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `webhook_pass` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tenant_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`oncall_service_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `is_deleted` tinyint(1) DEFAULT 0,
  `order_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_id` int(11) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `refer_id` int(11) DEFAULT NULL COMMENT 'Khách giới thiệu',
  `total` decimal(16,3) DEFAULT NULL,
  `discount` decimal(16,3) DEFAULT NULL,
  `amount` decimal(16,3) DEFAULT NULL,
  `tranport_charge` decimal(16,3) DEFAULT NULL,
  `process_status` enum('not_call','confirmed','ordercomplete','ordercancle','paysuccess','payfail','new','pay-half') COLLATE utf8mb4_unicode_ci DEFAULT 'new',
  `order_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_method_id` int(11) DEFAULT NULL,
  `order_source_id` int(11) DEFAULT 1,
  `transport_id` int(11) DEFAULT NULL,
  `voucher_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_member` decimal(16,3) DEFAULT NULL,
  `is_apply` tinyint(4) DEFAULT 0 COMMENT 'Đơn hàng từ app đã chuyển chi nhánh chưa',
  `total_saving` decimal(16,3) DEFAULT NULL COMMENT 'Giảm giá khuyến mãi',
  `total_tax` decimal(16,3) DEFAULT NULL,
  `shipping_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Địa chỉ giao hàng',
  `contact_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Người nhận',
  `contact_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Sđt người nhận',
  `customer_contact_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã địa chỉ người nhận',
  `receive_at_counter` tinyint(1) DEFAULT 0 COMMENT 'Nhận hàng tại quầy, Nhận hàng tại quầy 1 , địa chỉ khách hàng là 0',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `deal_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã deal (dành cho đơn hàng cho deal)',
  `cashier_by` int(11) DEFAULT NULL COMMENT 'Người thanh toán',
  `cashier_date` datetime DEFAULT NULL COMMENT 'Ngày thanh toán',
  `delivery_request_date` date DEFAULT NULL COMMENT 'Ngày yêu cầu giao hàng',
  `blessing` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Câu chúc',
  `receipt_info_check` tinyint(1) DEFAULT NULL COMMENT 'Giao hàng cho khách hàng',
  `type_time` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Loại thời gian mong muốn nhận hàng. before : Trước, in : Trong , after : Sau',
  `time_address` date DEFAULT NULL COMMENT 'Thời gian mong muốn nhận hàng',
  `type_shipping` tinyint(4) DEFAULT 0 COMMENT '0: Giao hàng bình thường, 1: Giao hàng nhanh',
  `delivery_cost_id` int(11) DEFAULT NULL COMMENT 'Id cấu hình phí',
  `customer_contact_id` int(11) DEFAULT NULL COMMENT 'Id địa chỉ nhận hàng',
  PRIMARY KEY (`order_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `order_commission`;
CREATE TABLE `order_commission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_detail_id` int(11) DEFAULT NULL,
  `refer_id` int(11) DEFAULT NULL COMMENT 'Người giới thiệu',
  `staff_id` int(11) DEFAULT NULL COMMENT 'Nhân viên phục vụ',
  `deal_id` int(11) DEFAULT NULL COMMENT 'Deal',
  `refer_money` decimal(16,3) DEFAULT NULL COMMENT 'Tiền hoa hồng người giới thiệu',
  `staff_money` decimal(16,3) DEFAULT NULL COMMENT 'Tiền hoa hồng nhân viên phục vụ',
  `deal_money` decimal(16,3) DEFAULT NULL COMMENT 'Tiền hoa hồng cho deal',
  `status` enum('approve','cancel') COLLATE utf8mb4_unicode_ci DEFAULT 'approve',
  `staff_commission_rate` decimal(16,3) DEFAULT 1.000 COMMENT 'Tỉ lệ hoa hồng nv',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `note` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ghi chú',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `order_details`;
CREATE TABLE `order_details` (
  `order_detail_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `object_id` int(11) NOT NULL COMMENT 'id của dich vụ , sản phẩm hoac thẻ dịch vụ tuỳ objecttype ',
  `object_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'tên của dich vụ , sản phẩm hoac thẻ dịch vụ tuỳ objecttype ',
  `object_type` enum('service_card','service','product','member_card','product_gift','service_gift','service_card_gift') COLLATE utf8mb4_unicode_ci NOT NULL,
  `object_code` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `staff_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'nhân viên phục vụ',
  `refer_id` int(11) DEFAULT NULL COMMENT 'Người giới thiệu',
  `price` decimal(16,3) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `discount` decimal(16,3) DEFAULT NULL,
  `amount` decimal(16,3) DEFAULT NULL,
  `voucher_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT NULL,
  `is_deleted` tinyint(4) DEFAULT 0,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `quantity_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `case_quantity` int(11) DEFAULT NULL,
  `saving` decimal(16,3) DEFAULT NULL,
  `is_change_price` tinyint(4) DEFAULT 0 COMMENT 'Thay đổi giá',
  `is_check_promotion` tinyint(4) DEFAULT 1 COMMENT '1: Có lưu log CTKM, 0: Ko lưu log CTKM',
  `tax` decimal(16,3) DEFAULT NULL COMMENT 'Thuế',
  PRIMARY KEY (`order_detail_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `order_detail_serial`;
CREATE TABLE `order_detail_serial` (
  `order_detail_serial_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL COMMENT 'Id đơn hàng',
  `order_detail_id` int(11) DEFAULT NULL COMMENT 'Id chi tiết đơn hàng',
  `product_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã sản phẩm',
  `serial` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Serial',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`order_detail_serial_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `order_images`;
CREATE TABLE `order_images` (
  `order_image_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã đơn hàng',
  `type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'before: trước, after: sau',
  `link` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Link ảnh đã up',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`order_image_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `order_log`;
CREATE TABLE `order_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `created_type` enum('backend','app') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'backend' COMMENT 'Người tạo ăn theo type',
  `type` enum('update','status') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('not_call','confirmed','ordercomplete','ordercancel','paysuccess','payfail','new','pay-half','packing','delivering') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nội dung cập nhật',
  `created_by` int(11) NOT NULL COMMENT 'Người tạo sẽ ăn theo type',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  `note_vi` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nội dung cập nhật tiếng Việt',
  `note_en` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nội dung cập nhật tiếng Anh',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `order_session_serial_log`;
CREATE TABLE `order_session_serial_log` (
  `order_session_serial_log_id` int(11) NOT NULL AUTO_INCREMENT,
  `session` varchar(191) DEFAULT NULL COMMENT 'Session khi tạo đơn hàng',
  `position` int(11) DEFAULT NULL COMMENT 'Thứ tự',
  `product_code` varchar(191) DEFAULT NULL COMMENT 'Mã sản phẩm',
  `serial` varchar(191) DEFAULT NULL COMMENT 'Mã serial ',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`order_session_serial_log_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `order_sources`;
CREATE TABLE `order_sources` (
  `order_source_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_source_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_actived` tinyint(4) NOT NULL DEFAULT 1,
  `is_deleted` tinyint(4) NOT NULL DEFAULT 0,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`order_source_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `otp_log`;
CREATE TABLE `otp_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `brandname` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên brandname',
  `telco` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nhà mạng',
  `customer_id` int(11) NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Số điện thoại',
  `message` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nội dung tin nhắn',
  `otp` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `otp_type` enum('register','forget_password') COLLATE utf8mb4_unicode_ci NOT NULL,
  `otp_expired` datetime NOT NULL,
  `is_actived` tinyint(1) DEFAULT 0 COMMENT '0: Otp chưa sử dụng 1: Otp đã sử dụng',
  `is_sent` tinyint(1) DEFAULT 0 COMMENT 'Đã gửi',
  `time_send` datetime DEFAULT NULL COMMENT 'Ngày gửi',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  `otp_method` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT 'sms' COMMENT 'voice: Gửi otp bằng voice, sms: Gửi otp bằng sms',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `pages`;
CREATE TABLE `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên trang',
  `route` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Route của trang',
  `is_actived` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `action_group_id` int(11) DEFAULT NULL COMMENT 'Mã nhóm chức năng',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `params`;
CREATE TABLE `params` (
  `params_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_zns` tinyint(1) DEFAULT 1 COMMENT 'sử dụng cho zns',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`params_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `payments`;
CREATE TABLE `payments` (
  `payment_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `payment_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mã phiếu chi sinh tự động : P+''ddmmyyyy''+ số tự tăng',
  `branch_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'mã chi nhánh ',
  `staff_id` int(11) NOT NULL COMMENT 'người chi tiền',
  `total_amount` decimal(16,3) NOT NULL DEFAULT 0.000 COMMENT 'tổng tiền chi ',
  `approved_by` int(11) DEFAULT NULL,
  `status` enum('new','approved','paid','unpaid') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'new',
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'lý do chi  ',
  `payment_date` datetime DEFAULT NULL COMMENT 'ngày ghi nhận chi',
  `object_accounting_type_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã loại đối tượng thu chi',
  `accounting_id` int(11) DEFAULT NULL COMMENT 'Id đối tượng thu chi',
  `accounting_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'tên đối tượng thu chi',
  `payment_type` int(11) DEFAULT NULL COMMENT 'loại thanh toán lấy từ bảng payment_type',
  `document_code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'mã chứng từ , ví dụ đối với phiếu nhập kho : mã phiếu nhập kho',
  `payment_method` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'hình thức thanh toán : tiền mặt, chuyển khoản..... lấy từ bảng payment_method',
  `created_by` int(11) NOT NULL COMMENT 'người tạo phiếu',
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_delete` int(11) DEFAULT NULL,
  PRIMARY KEY (`payment_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `payment_client`;
CREATE TABLE `payment_client` (
  `payment_client_id` int(11) NOT NULL AUTO_INCREMENT,
  `payment_method_id` int(11) DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `tenant` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vnpay_order_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '100000',
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `secret` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ipn_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `retry_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `callback_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`payment_client_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `payment_method`;
CREATE TABLE `payment_method` (
  `payment_method_id` int(11) NOT NULL AUTO_INCREMENT,
  `payment_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_method_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mã hình thức thanh toán',
  `payment_method_name_vi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên hình thức thanh toán',
  `payment_method_name_en` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_method_type` enum('auto','manual') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Loại hình thức thanh toán',
  `payment_method_image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Hình icon đại diện phương thức thanh toán',
  `note` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ghi chú',
  `is_system` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'trạng thái ',
  `is_delete` tinyint(1) NOT NULL DEFAULT 0,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'url khởi tạo (vn pay)',
  `terminal_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `secret_key` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `access_key` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Access key của momo',
  PRIMARY KEY (`payment_method_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Danh sách phương thức thanh toán';


DROP TABLE IF EXISTS `payment_partner`;
CREATE TABLE `payment_partner` (
  `payment_partner_id` int(11) NOT NULL,
  `partner_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `api_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `api_pass` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `api_endpoint` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`payment_partner_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Thông tin nhà cung cấp dịch vụ giao dịch';


DROP TABLE IF EXISTS `payment_refund`;
CREATE TABLE `payment_refund` (
  `payment_refund_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `refund_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ship_to_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `outlet_id` bigint(20) DEFAULT NULL,
  `retailpro_order_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dms_order_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `refund_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_received_payment` decimal(19,6) DEFAULT NULL,
  `total_order_refund` decimal(19,6) DEFAULT NULL,
  `refund_amount` decimal(19,6) DEFAULT NULL,
  `refund_code_reference` varchar(19) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `refund_date` datetime DEFAULT NULL,
  `create_datetime` timestamp NULL DEFAULT current_timestamp() COMMENT 'Ngày giờ tạo record',
  `last_modify_datetime` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'ngày cập nhật cuối vào record',
  PRIMARY KEY (`payment_refund_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `payment_transaction`;
CREATE TABLE `payment_transaction` (
  `TransactionMasterId` int(11) NOT NULL AUTO_INCREMENT,
  `PartnerID` int(11) DEFAULT NULL COMMENT 'ID nhà cung cấp dịch vụ',
  `TransactionType` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'eway' COMMENT 'Loại thanh toán : eway, momo, vnpay....',
  `TransactionID` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ID Transaction khi thanh toán thành công',
  `AccessCode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã code khi thanh toán eway dùng để check trạng thái',
  `InvoiceReference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã đơn hàng dùng để đối soát với bên thứ 3',
  `InvoiceNumber` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã đơn hàng',
  `TotalAmount` double(18,2) DEFAULT NULL,
  `DeviceID` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'IME thiết bị',
  `CustomerIP` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'IP khách hàng',
  `Language` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `RequestTime` datetime DEFAULT NULL COMMENT 'Thời gian gửi yêu cầu thanh toán',
  `ResponseTime` datetime DEFAULT NULL,
  `Status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Message` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Retry` smallint(6) DEFAULT 0 COMMENT 'Số lần gọi lại api để check thanh toán',
  `CreatedAt` datetime DEFAULT NULL,
  `CreatedBy` int(11) DEFAULT NULL,
  `UpdatedAt` datetime DEFAULT NULL,
  `UpdatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`TransactionMasterId`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Lịch sử giao dịch';


DROP TABLE IF EXISTS `payment_transaction_customer`;
CREATE TABLE `payment_transaction_customer` (
  `TransactionCusId` int(11) NOT NULL AUTO_INCREMENT,
  `TransactionMasterId` int(11) NOT NULL COMMENT 'ID transaction',
  `Reference` int(11) DEFAULT NULL COMMENT 'ID customer để đối chiếu',
  `Title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Chức danh : ông, bà, anh chị ...',
  `FirstName` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `LastName` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `CompanyName` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên công ty',
  `JobDescription` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Vị trí (công việc : dev, lead, manager ...)',
  `Street1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'địa chỉ 1',
  `Street2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'địa chỉ 2',
  `City` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Thành phố',
  `State` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Bang',
  `PostalCode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Quốc gia',
  `Phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Số điện thoại',
  `Mobile` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'di động',
  `Email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'email liên hệ',
  `Url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'url',
  PRIMARY KEY (`TransactionCusId`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Lịch sử khách hàng tiến hành giao dịch';


DROP TABLE IF EXISTS `payment_transaction_ipn`;
CREATE TABLE `payment_transaction_ipn` (
  `payment_transaction_ipn_id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID tự tăng',
  `payment_transaction_id` bigint(20) NOT NULL COMMENT 'ID giao dịch',
  `order_type` enum('order','debt') COLLATE utf8mb4_unicode_ci DEFAULT 'order' COMMENT 'Loại thanh toán: Đơn hàng, công nợ',
  `order_id` bigint(20) NOT NULL COMMENT 'ID base order',
  `result` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Kết quả giao dịch',
  `raw_data` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Raw data của cổng thanh toán trả về',
  `response_data` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Kết quả xử lý server RetailPro trả lại cho cổng thanh toán',
  `created_at` timestamp NULL DEFAULT current_timestamp() COMMENT 'Thời gian tạo',
  PRIMARY KEY (`payment_transaction_ipn_id`) USING BTREE,
  KEY `payment_transaction_id` (`payment_transaction_id`) USING BTREE,
  KEY `order_id` (`order_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Lưu log trả IPN của công thanh toán (response request)';


DROP TABLE IF EXISTS `payment_transaction_item`;
CREATE TABLE `payment_transaction_item` (
  `TransactionItemId` int(11) NOT NULL AUTO_INCREMENT,
  `TransactionMasterId` int(11) DEFAULT NULL,
  `Reference` int(11) DEFAULT NULL COMMENT 'ID sản phẩm',
  `SKU` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã sản phẩm',
  `Description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Quantity` int(11) DEFAULT NULL,
  `UnitCost` double(18,2) DEFAULT NULL,
  PRIMARY KEY (`TransactionItemId`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Thông tin sản phẩm trong giao dịch';


DROP TABLE IF EXISTS `payment_transaction_log`;
CREATE TABLE `payment_transaction_log` (
  `payment_transaction_log_id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID tự tăng',
  `payment_transaction_id` bigint(20) NOT NULL COMMENT 'ID Giao dịch',
  `previous_payment_transaction_status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Trạng thái giao dịch trước đó',
  `payment_transaction_status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Trạng thái giao dịch khi xử lý xong',
  `previous_order_status` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Trạng thái của đơn hàng trước đó',
  `order_status` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Trạng thái của đơn hàng sau khi xử lý',
  `source` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nguồn làm thay đổi trạng thái: ipn, querydlr,...',
  `source_obj_id` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ID của source làm thay đổi trạng thái',
  `created_at` timestamp NULL DEFAULT current_timestamp() COMMENT 'Thời gian phát sinh log',
  PRIMARY KEY (`payment_transaction_log_id`) USING BTREE,
  KEY `payment_transaction_id` (`payment_transaction_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Log thay đổi trạng thái của giao dịch (log request từ payment gateway)';


DROP TABLE IF EXISTS `payment_transaction_master`;
CREATE TABLE `payment_transaction_master` (
  `payment_transaction_id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID tự tăng',
  `payment_transaction_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã giao dịch tự tạo để đối chiếu',
  `payment_transaction_uuid` int(11) DEFAULT NULL,
  `payment_client_id` int(11) NOT NULL COMMENT 'Thanh toán cho công ty/chi nhánh',
  `tenant` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã công ty/chi nhánh',
  `payment_method_id` int(11) NOT NULL COMMENT 'ID phương thức thanh toán',
  `client_ip` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'IP của người dùng',
  `order_type` enum('order','debt') COLLATE utf8mb4_unicode_ci DEFAULT 'order' COMMENT 'Loại thanh toán: Đơn hàng, công nợ',
  `order_id` bigint(20) NOT NULL COMMENT 'ID base order',
  `amount` decimal(19,6) DEFAULT NULL COMMENT 'Số tiền thanh toán',
  `transaction_date` date NOT NULL COMMENT 'Ngày tạo giao dịch, phục vụ báo cáo',
  `payment_transaction_status` enum('new','waiting','success','fail','cancel') COLLATE utf8mb4_unicode_ci DEFAULT 'new' COMMENT 'new: mới khởi tạo; waiting: thanh toán thành công và redirect về app, chưa có ipn; success: Thanh toán thành công cập nhật từ IPN, fail: thanh toán lỗi cập nhật từ IPN; cancel: User hủy thanh toán trên app',
  `payment_url` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'URL được tạo ra cho giao dịch thanh toán',
  `is_cross_check` tinyint(1) DEFAULT 0 COMMENT 'Đánh dấu đã kiểm tra đối với các giao dịch thanh toán thành công. Để job không chạy lại nữa',
  `bank_code` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã ngân hàng',
  `bank_transaction_code` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã giao dịch ngân hàng',
  `bank_card_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Loại tài khoản/ thẻ',
  `transaction_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã giao dịch VNPay',
  `payment_at` datetime DEFAULT NULL COMMENT 'Thời gian thanh toán từ cổng thanh toán trả về',
  `vnpay_transaction_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã giao dịch VNPay',
  `created_at` datetime DEFAULT NULL COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT current_timestamp() COMMENT 'Thời gian cập nhật',
  PRIMARY KEY (`payment_transaction_id`) USING BTREE,
  KEY `created_at` (`created_at`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Dùng để tạo ra các giao dịch, lấy id này cho việc thanh toán đơn hàng';


DROP TABLE IF EXISTS `payment_transaction_paid`;
CREATE TABLE `payment_transaction_paid` (
  `payment_transaction_paid_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID tự tăng',
  `payment_transaction_id` bigint(20) NOT NULL COMMENT 'ID giao dịch',
  `payment_method_id` int(11) NOT NULL COMMENT 'ID hình thức thanh toán',
  `order_type` enum('order','debt') COLLATE utf8mb4_unicode_ci DEFAULT 'order' COMMENT 'Loại thanh toán: Đơn hàng, công nợ',
  `order_id` bigint(20) NOT NULL COMMENT 'ID base order',
  `order_date` datetime DEFAULT NULL COMMENT 'Ngày đặt hàng',
  `successful_at` datetime DEFAULT NULL COMMENT 'Thời gian thanh toán thành công',
  PRIMARY KEY (`payment_transaction_paid_id`) USING BTREE,
  KEY `payment_transaction_id` (`payment_transaction_id`) USING BTREE,
  KEY `payment_id` (`payment_method_id`) USING BTREE,
  KEY `order_id` (`order_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Chứa giao dịch thành công, dùng để kiểm tra, tránh thanh toán nhiều lần 1 order';


DROP TABLE IF EXISTS `payment_transaction_retry`;
CREATE TABLE `payment_transaction_retry` (
  `payment_transaction_retry_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `payment_transaction_id` bigint(20) NOT NULL COMMENT 'ID giao dịch kiểm tra trạng thái',
  `company_branch_id` int(11) NOT NULL COMMENT 'Thanh toán cho công ty/chi nhánh',
  `company_branch_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã công ty/chi nhánh',
  `payment_method_id` bigint(20) NOT NULL COMMENT 'ID cổng thanh toán',
  `order_type` enum('order','debt') COLLATE utf8mb4_unicode_ci DEFAULT 'order' COMMENT 'Loại thanh toán: Đơn hàng, công nợ',
  `order_id` bigint(20) DEFAULT NULL COMMENT 'ID base order',
  `result` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Kết quả retry',
  `raw_data` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'raw Data trả về từ cổng thanh toán',
  `is_successful` tinyint(1) DEFAULT 1 COMMENT 'Có thành công hay không',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`payment_transaction_retry_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Log job retry. Gọi tới ngân hàng để kiểm tra giao dịch';


DROP TABLE IF EXISTS `payment_transaction_return`;
CREATE TABLE `payment_transaction_return` (
  `payment_transaction_return_id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID tự tăng',
  `payment_transaction_id` bigint(20) NOT NULL COMMENT 'ID giao dịch',
  `result` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Kết quả giao dịch',
  `raw_data` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Raw data của cổng thanh toán trả về',
  `created_at` timestamp NULL DEFAULT current_timestamp() COMMENT 'Thời gian tạo',
  PRIMARY KEY (`payment_transaction_return_id`) USING BTREE,
  KEY `payment_transaction_id` (`payment_transaction_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Lưu log kết quả return url của payment gateway';


DROP TABLE IF EXISTS `payment_transaction_shipping`;
CREATE TABLE `payment_transaction_shipping` (
  `TransactionShippingId` int(11) NOT NULL AUTO_INCREMENT,
  `TransactionMasterId` int(11) NOT NULL COMMENT 'ID transaction',
  `Reference` int(11) DEFAULT NULL COMMENT 'ID Shiping để đối chiếu',
  `FirstName` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `LastName` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Street1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'địa chỉ 1',
  `Street2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'địa chỉ 2',
  `City` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Thành phố',
  `State` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Bang',
  `PostalCode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Quốc gia',
  `Phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Số điện thoại',
  `ShippingMethod` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`TransactionShippingId`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Thông tin vận chuyển cho lần giao dịch';


DROP TABLE IF EXISTS `payment_type`;
CREATE TABLE `payment_type` (
  `payment_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `payment_type_name_vi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên loại thanh toán ',
  `payment_type_name_en` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên loại thanh toán ',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'trạng thái ',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_system` tinyint(4) DEFAULT 0 COMMENT '1: Là hệ thống, 0: Tạo bt',
  `system_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã code hệ thống',
  PRIMARY KEY (`payment_type_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Danh sách phương thức thanh toán';


DROP TABLE IF EXISTS `payment_units`;
CREATE TABLE `payment_units` (
  `payment_unit_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_actived` tinyint(4) DEFAULT 1 COMMENT 'Trạng thái',
  `is_deleted` tinyint(4) DEFAULT 0 COMMENT 'Đánh dấu xoá',
  `updated_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`payment_unit_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `people`;
CREATE TABLE `people` (
  `people_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `full_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Họ và Tên',
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'mã hồ sơ',
  `birthday` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ngày sinh',
  `gender` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'giới tính',
  `temporary_address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'địa chỉ tạm trú',
  `permanent_address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'địa chỉ thường trú',
  `id_number` decimal(20,0) DEFAULT NULL COMMENT 'CMND/CCCD',
  `id_license_date` date DEFAULT NULL COMMENT 'ngày cấp CCCD',
  `people_id_license_place_id` int(11) DEFAULT NULL COMMENT 'nơi cấp CCCD',
  `hometown_id` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'quê quán, province id',
  `ethnic_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'dân tộc',
  `religion_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'tôn giáo',
  `people_job_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'nghề nghiệp',
  `birthplace_id` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'nơi sinh, province id',
  `people_group_id` varchar(191) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT 'khu phố',
  `people_quarter_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'tổ dân phố',
  `group` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Khu phố',
  `quarter` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tổ dân phố',
  `people_family_type_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'thành phần gia đình',
  `educational_level_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'trình độ học vấn',
  `elementary_school` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT '0.00' COMMENT 'trường cấp 1',
  `middle_school` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT '1' COMMENT 'trường cấp 2',
  `high_school` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'trường cấp 3',
  `from_18_to_21` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'từ 18 đến 21 tuổi',
  `from_21_to_now` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'từ 21 tuổi đến nay',
  `birth_year` year(4) DEFAULT NULL COMMENT 'năm sinh',
  `avatar` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'avatar',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `hometown` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birthplace` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `union_join_date` date DEFAULT NULL,
  `group_join_date` date DEFAULT NULL,
  `graduation_year` year(4) DEFAULT NULL,
  `specialized` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foreign_language` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `workplace` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nơi làm việc',
  PRIMARY KEY (`people_id`) USING BTREE,
  UNIQUE KEY `customer_id_UNIQUE` (`people_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `people_deletable`;
CREATE TABLE `people_deletable` (
  `people_deletable_id` int(11) NOT NULL AUTO_INCREMENT,
  `people_object_group_id` int(11) DEFAULT NULL COMMENT 'ID của nhóm đối tượng có thể xóa',
  `people_object_id` int(11) DEFAULT NULL COMMENT 'ID của đối tượng có thể xóa',
  `people_id` int(11) DEFAULT NULL COMMENT 'id của người có thể xóa',
  `deletable` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  PRIMARY KEY (`people_deletable_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `people_educational_level`;
CREATE TABLE `people_educational_level` (
  `people_educational_level_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id trình độ học vấn',
  `name` varchar(191) NOT NULL COMMENT 'tên trình độ học vấn',
  `code` varchar(191) NOT NULL COMMENT 'tên viết tắt',
  PRIMARY KEY (`people_educational_level_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `people_family`;
CREATE TABLE `people_family` (
  `people_family_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `people_id` int(11) NOT NULL COMMENT 'relationship to table people_id',
  `people_family_relationship_type_id` varchar(191) DEFAULT NULL COMMENT 'kiểu quan hệ',
  `full_name` varchar(191) DEFAULT NULL COMMENT 'họ và tên',
  `birth_year` year(4) DEFAULT NULL COMMENT 'ngày sinh',
  `people_job_id` varchar(191) DEFAULT NULL COMMENT 'nghề nghiệp',
  `address` text DEFAULT NULL COMMENT 'địa chỉ hiện tại',
  `before_30041975` text DEFAULT NULL COMMENT 'mô tả trước 30/04/1975',
  `after_30041975` text DEFAULT NULL COMMENT 'mô tả sau 30/04/1975',
  `is_dead` int(4) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`people_family_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `people_family_relationship_type`;
CREATE TABLE `people_family_relationship_type` (
  `people_family_relationship_type_id` int(11) NOT NULL COMMENT 'id kiểu quan hệ',
  `name` varchar(255) NOT NULL COMMENT 'tên kiểu quan hệ',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`people_family_relationship_type_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `people_family_type`;
CREATE TABLE `people_family_type` (
  `people_family_type_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id thành phần gia đình',
  `name` varchar(191) NOT NULL COMMENT 'tên thành phần gia đình',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`people_family_type_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `people_group`;
CREATE TABLE `people_group` (
  `people_group_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id khu phố',
  `name` varchar(191) NOT NULL COMMENT 'tên khu phố',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`people_group_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `people_health_type`;
CREATE TABLE `people_health_type` (
  `people_health_type_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'loại sức khỏe',
  `name` varchar(191) NOT NULL COMMENT 'tên loại sức khỏe',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`people_health_type_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `people_id_license_place`;
CREATE TABLE `people_id_license_place` (
  `people_id_license_place_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  PRIMARY KEY (`people_id_license_place_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `people_import_error`;
CREATE TABLE `people_import_error` (
  `people_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Họ và Tên',
  `full_name` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'mã hồ sơ',
  `gender` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ngày sinh',
  `id_number` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'giới tính',
  `id_license_date` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'địa chỉ tạm trú',
  `people_id_license_place` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'địa chỉ thường trú',
  `birth_day` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'CMND/CCCD',
  `birth_month` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ngày cấp CCCD',
  `birth_year` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'nơi cấp CCCD',
  `permanent_address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'quê quán, province id',
  `temporary_address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'dân tộc',
  `birthplace` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'tôn giáo',
  `hometown` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'nghề nghiệp',
  `people_group` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'nơi sinh, province id',
  `people_quarter` text CHARACTER SET utf8mb4 DEFAULT NULL COMMENT 'khu phố',
  `ethnic` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'tổ dân phố',
  `religion` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Khu phố',
  `people_family` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tổ dân phố',
  `educational_level` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'thành phần gia đình',
  `graduation_year` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'trình độ học vấn',
  `specialized` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'trường cấp 1',
  `foreign_language` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'trường cấp 2',
  `union_join_date` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'trường cấp 3',
  `group_join_date` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'từ 18 đến 21 tuổi',
  `people_job` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'từ 21 tuổi đến nay',
  `elementary_school` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'năm sinh',
  `middle_school` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'avatar',
  `high_school` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `from_18_to_21` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `from_21_to_now` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `full_name_dad` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birth_year_dad` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `job_dad` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `before_30_04_dad` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `after_30_04_dad` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `full_name_mom` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birth_year_mom` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `job_mom` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `before_30_04_mom` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nơi làm việc',
  `after_30_04_mom` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `info_brother_1` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `info_brother_2` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `info_brother_3` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `info_brother_4` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `info_brother_5` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `info_brother_6` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `full_name_couple` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birth_year_couple` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `job_couple` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `info_child_1` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `info_child_2` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `error` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`people_id`) USING BTREE,
  UNIQUE KEY `customer_id_UNIQUE` (`people_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `people_job`;
CREATE TABLE `people_job` (
  `people_job_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`people_job_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `people_object`;
CREATE TABLE `people_object` (
  `people_object_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `name` varchar(191) NOT NULL COMMENT 'tên đối tượng',
  `is_active` tinyint(1) unsigned zerofill NOT NULL COMMENT 'trạng thái',
  `created_by` varchar(191) DEFAULT NULL COMMENT 'người tạo',
  `created_at` datetime DEFAULT current_timestamp() COMMENT 'thời điểm tạo',
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'thời điểm thay đổi',
  `is_deleted` tinyint(1) unsigned zerofill NOT NULL COMMENT 'trạng thái xóa',
  `people_object_group_id` int(11) DEFAULT NULL,
  `is_skip` tinyint(1) NOT NULL DEFAULT 0,
  `code` varchar(191) NOT NULL,
  PRIMARY KEY (`people_object_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `people_object_group`;
CREATE TABLE `people_object_group` (
  `people_object_group_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `name` varchar(191) NOT NULL COMMENT 'tên đối tượng',
  `is_active` tinyint(1) unsigned zerofill NOT NULL COMMENT 'trạng thái',
  `created_by` varchar(191) DEFAULT NULL COMMENT 'người tạo',
  `created_at` datetime DEFAULT current_timestamp() COMMENT 'thời điểm tạo',
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'thời điểm thay đổi',
  `is_deleted` tinyint(1) unsigned zerofill NOT NULL COMMENT 'trạng thái xóa',
  `is_skip` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`people_object_group_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `people_quarter`;
CREATE TABLE `people_quarter` (
  `people_quarter_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id tổ dân phố',
  `name` varchar(191) NOT NULL COMMENT 'tên tổ dân phố',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`people_quarter_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `people_report_log`;
CREATE TABLE `people_report_log` (
  `people_report_log_id` int(11) NOT NULL AUTO_INCREMENT,
  `birthyear` year(4) DEFAULT NULL,
  PRIMARY KEY (`people_report_log_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `people_report_years`;
CREATE TABLE `people_report_years` (
  `people_report_year_id` int(11) NOT NULL AUTO_INCREMENT,
  `year` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`people_report_year_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `people_verification`;
CREATE TABLE `people_verification` (
  `people_verification_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `name` varchar(191) DEFAULT NULL COMMENT 'tên đợt phúc tra',
  `date` date DEFAULT NULL COMMENT 'ngày bắt đầu',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `year` int(10) DEFAULT NULL,
  `month` int(10) DEFAULT NULL,
  `day` int(10) DEFAULT NULL,
  PRIMARY KEY (`people_verification_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `people_verify`;
CREATE TABLE `people_verify` (
  `people_verify_id` int(255) NOT NULL AUTO_INCREMENT COMMENT 'id phúc tra',
  `people_verification_id` int(11) DEFAULT NULL COMMENT 'id đợt phúc tra, rela to table people_verification',
  `age` int(11) DEFAULT NULL COMMENT 'tuổi',
  `people_object_id` int(11) DEFAULT NULL COMMENT 'relationship to talbe people_object',
  `content` text DEFAULT NULL COMMENT 'lý do',
  `people_health_type_id` varchar(191) DEFAULT NULL COMMENT 'loại sức khỏe',
  `note` varchar(191) DEFAULT NULL COMMENT 'ghi chú',
  `people_id` int(11) DEFAULT NULL COMMENT 'người phúc tra',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`people_verify_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `people_verify_log`;
CREATE TABLE `people_verify_log` (
  `people_verify_log_id` varchar(255) DEFAULT NULL COMMENT 'id phúc tra',
  `people_verification_id` int(11) DEFAULT NULL COMMENT 'id đợt phúc tra, rela to table people_verification',
  `years_old` int(11) DEFAULT NULL COMMENT 'tuổi',
  `people_object_group_id` int(11) DEFAULT NULL COMMENT 'relationship to table people_object_group',
  `people_object_id` int(11) DEFAULT NULL COMMENT 'relationship to talbe people_object',
  `content` text DEFAULT NULL COMMENT 'lý do',
  `health_type` varchar(191) DEFAULT NULL COMMENT 'loại sức khỏe',
  `people_verify_id` varchar(191) DEFAULT NULL COMMENT 'ghi chú'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `phone_service`;
CREATE TABLE `phone_service` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID tự tăng',
  `telco` enum('vina','mobi','viettel','gphone','vnm','australia') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nhà mạng',
  `service_num` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Đầu số',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `telco_service_num` (`telco`,`service_num`) USING BTREE,
  KEY `service_num` (`service_num`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Đầu số di động';


DROP TABLE IF EXISTS `pickup_address`;
CREATE TABLE `pickup_address` (
  `pickup_address_id` int(11) NOT NULL AUTO_INCREMENT,
  `pickup_address_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_actived` tinyint(4) DEFAULT 1,
  `is_deleted` tinyint(4) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`pickup_address_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `point_history`;
CREATE TABLE `point_history` (
  `point_history_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `point` double NOT NULL,
  `type` enum('plus','subtract') COLLATE utf8mb4_unicode_ci DEFAULT 'plus',
  `point_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `object_id` int(11) DEFAULT NULL COMMENT 'Ăn theo point_description',
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `accepted_ranking` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` date DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `source` enum('accumulate','redeem','reset_pireodic') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`point_history_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `point_history_detail`;
CREATE TABLE `point_history_detail` (
  `point_history_detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `point_history_id` int(11) NOT NULL,
  `point_reward_rule_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`point_history_detail_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `point_reward_rule`;
CREATE TABLE `point_reward_rule` (
  `point_reward_rule_id` int(11) NOT NULL AUTO_INCREMENT,
  `rule_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên chương trình ',
  `rule_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'mã chương trình',
  `point_maths` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nhân hoặc +',
  `point_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'giá trị điểm được cộng ',
  `rule_type` enum('purchase','event') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Loại : mua hàng hoặc event',
  `hagtag_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Danh sách id các dịch vụ , sản phẩm , thẻ dịch vụ đặc biệt',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_actived` tinyint(1) DEFAULT 1,
  `modified_by` int(11) DEFAULT NULL,
  `modified_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`point_reward_rule_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `point_rules`;
CREATE TABLE `point_rules` (
  `point_rule_id` int(11) NOT NULL AUTO_INCREMENT COMMENT ' ',
  `rule_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_amount_smallest` decimal(10,0) DEFAULT NULL,
  `order_amount_biggest` decimal(10,0) DEFAULT NULL,
  `order_quantity_smallest` int(11) DEFAULT NULL,
  `order_quantity_biggest` int(11) DEFAULT NULL,
  `product_allow` tinyint(1) DEFAULT NULL,
  `hastag_product_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_allow` tinyint(1) DEFAULT NULL,
  `hastag_service_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_card_allow` tinyint(1) DEFAULT NULL,
  `hagtag_service_card_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `formula` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'công thức tính VD : amount/100000',
  `branch_limit` tinyint(1) DEFAULT 0,
  `branch_allow` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_source_limit` tinyint(1) DEFAULT 0,
  `customer_source_alllow` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_source_limit` tinyint(1) DEFAULT 0,
  `order_source_allow` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_method_limit` tinyint(1) DEFAULT 0,
  `payment_method_allow` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`point_rule_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `print_bill_devices`;
CREATE TABLE `print_bill_devices` (
  `print_bill_device_id` int(11) NOT NULL AUTO_INCREMENT,
  `branch_id` int(11) DEFAULT NULL COMMENT 'Map với chi nhánh',
  `printer_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên máy in',
  `printer_ip` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Địa chỉ ip máy in',
  `printer_port` int(11) DEFAULT NULL COMMENT 'Port của máy in',
  `template` enum('k58','k80','A5','A4') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'k80',
  `template_width` int(11) DEFAULT NULL COMMENT 'Chiều rộng giấy in',
  `is_default` tinyint(4) DEFAULT 0 COMMENT 'Máy in mặc định',
  `is_actived` tinyint(4) DEFAULT NULL COMMENT 'Trạng thái',
  `is_deleted` tinyint(4) DEFAULT NULL COMMENT 'Trạng thái',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`print_bill_device_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `print_log`;
CREATE TABLE `print_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `branch_id` int(11) NOT NULL COMMENT 'Chi nhánh',
  `order_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mã hóa đơn',
  `debt_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mã công nợ',
  `staff_print_reply_by` int(11) NOT NULL COMMENT 'Người in lại',
  `staff_print_by` int(11) NOT NULL COMMENT 'Người in đầu',
  `total_money` decimal(10,0) DEFAULT NULL COMMENT 'Tổng tiền phải trả',
  `created_at` datetime NOT NULL COMMENT 'Thời gian in đầu',
  `updated_at` datetime NOT NULL COMMENT 'Thời gian in sau',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `product_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_category_id` int(11) DEFAULT NULL,
  `product_model_id` int(11) DEFAULT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_short_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `cost` decimal(16,3) DEFAULT NULL,
  `price_standard` decimal(16,3) DEFAULT NULL,
  `is_sales` tinyint(1) DEFAULT 0 COMMENT 'sản phẩm giảm giá ',
  `is_promo` tinyint(1) DEFAULT 0 COMMENT 'quà tặng',
  `type` enum('normal','hot','new') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type_manager` enum('normal','attribute') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Cách thức quản lý ',
  `count_version` int(11) DEFAULT NULL COMMENT 'số phiên bản',
  `is_inventory_warning` tinyint(1) DEFAULT 0,
  `inventory_warning` int(11) DEFAULT 0 COMMENT 'mức cảnh báo tồn kho',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `staff_commission_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type_staff_commission` enum('percent','money') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Kiểu hoa hồng cho nhân viên phục vụ',
  `refer_commission_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type_refer_commission` enum('percent','money') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Kiểu hoa hồng cho khách giới thiệu',
  `deal_commission_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Giá trị hoa hồng cho deal (theo % hoặc tiền)',
  `type_deal_commission` enum('percent','money') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Kiểu hoa hồng cho deal',
  `supplier_id` int(11) DEFAULT NULL COMMENT 'nhà cung cấp',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT 0,
  `is_actived` tinyint(1) DEFAULT 1,
  `product_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_all_branch` tinyint(1) DEFAULT 0 COMMENT 'Tất cả chi nhánh',
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'slug check trùng',
  `description_detail` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mô tả chi tiết',
  `type_app` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'new: mới, best_seller: bán chạy',
  `percent_sale` int(11) DEFAULT 0 COMMENT '% giảm giá',
  `inventory_management` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT 'basic' COMMENT 'basic: Quản lý theo số lượng, serial: Quản lý theo serial, packet: Lô hàng, all : Vừa theo serial và lô hàng',
  PRIMARY KEY (`product_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `product_attributes`;
CREATE TABLE `product_attributes` (
  `product_attribute_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_attribute_label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_attribute_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `is_deleted` tinyint(1) DEFAULT 0,
  `is_actived` tinyint(1) DEFAULT 1,
  `product_attribute_group_id` int(11) DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT 'text' COMMENT 'Kiểu dữ liệu của attribute_label (int, date, text, boolean)',
  PRIMARY KEY (`product_attribute_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `product_attribute_groups`;
CREATE TABLE `product_attribute_groups` (
  `product_attribute_group_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_attribute_group_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `is_deleted` tinyint(1) DEFAULT 0,
  `is_actived` tinyint(1) DEFAULT 1,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`product_attribute_group_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `product_branch_prices`;
CREATE TABLE `product_branch_prices` (
  `product_branch_price_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(10) unsigned NOT NULL,
  `branch_id` int(11) NOT NULL,
  `product_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `old_price` decimal(16,3) DEFAULT NULL,
  `new_price` decimal(16,3) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `is_actived` tinyint(1) DEFAULT 1,
  `is_deleted` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`product_branch_price_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `product_categories`;
CREATE TABLE `product_categories` (
  `product_category_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_actived` tinyint(1) DEFAULT 1,
  `is_deleted` tinyint(1) DEFAULT 0,
  `category_uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'uuid category ETL',
  `created_by` int(11) DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon_image` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ảnh icon image',
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`product_category_id`) USING BTREE,
  UNIQUE KEY `category_name` (`category_name`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `product_childs`;
CREATE TABLE `product_childs` (
  `product_child_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `product_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_child_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `cost` decimal(10,3) DEFAULT NULL,
  `price` decimal(16,3) DEFAULT NULL,
  `percent_sale` int(11) DEFAULT NULL COMMENT '% giảm giá',
  `is_display` tinyint(1) DEFAULT 1 COMMENT 'Hiển thị trên app',
  `is_actived` tinyint(1) DEFAULT 1,
  `is_sales` tinyint(1) DEFAULT 0 COMMENT 'sản phẩm giảm giá ',
  `is_deleted` tinyint(1) DEFAULT 0,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'slug check trùng',
  `type_app` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'new: mới, best_seller: bán chạy',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `is_surcharge` tinyint(4) DEFAULT 0 COMMENT 'Dịch vụ phụ thu',
  `custom_1` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'tuỳ chỉnh 1',
  `custom_2` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_3` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_4` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_5` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_6` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_7` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_8` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_9` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_10` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_remind` tinyint(4) DEFAULT 0 COMMENT 'Dự kiến nhắc sử dụng lại',
  `remind_value` int(11) DEFAULT NULL COMMENT 'Số ngày dự kiến nhắc',
  `barcode` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã vạch',
  `inventory_management` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT 'basic' COMMENT 'basic: Quản lý theo số lượng, serial: Quản lý theo serial, packet: Lô hàng',
  `is_applied_kpi` tinyint(1) DEFAULT 1 COMMENT '1: Được áp dụng KPI, 0: Không áp dụng KPI',
  PRIMARY KEY (`product_child_id`) USING BTREE,
  UNIQUE KEY `product_code` (`product_code`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `product_child_custom_define`;
CREATE TABLE `product_child_custom_define` (
  `key` varchar(191) NOT NULL COMMENT 'Key ứng với tên trường custom',
  `type` varchar(191) DEFAULT NULL COMMENT 'text: kiểu text, boolean: kiểu true false, product_code : kiểu để check unique',
  `title_vi` varchar(191) DEFAULT NULL COMMENT 'Tiêu đề vi',
  `title_en` varchar(191) DEFAULT NULL COMMENT 'Tiêu đề en',
  PRIMARY KEY (`key`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `product_condition`;
CREATE TABLE `product_condition` (
  `product_condition_id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(255) DEFAULT NULL COMMENT 'Key phân biệt điều kiện',
  `product_condition_name` varchar(255) DEFAULT NULL COMMENT 'Tên điều kiện',
  `type` varchar(255) DEFAULT NULL COMMENT 'number : hiển thị số lượng,\r\ndate : hiển thị thời gian,\r\nnumber_date : vừa hiển thị số lượng vừa hiển thị thời gian',
  `is_active` tinyint(1) NOT NULL COMMENT 'Trạng thái',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`product_condition_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Danh sách điều kiện';


DROP TABLE IF EXISTS `product_config`;
CREATE TABLE `product_config` (
  `product_config_id` int(11) NOT NULL AUTO_INCREMENT,
  `display_view_category` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT 'V' COMMENT 'H: horizontal, V: vertical  (loại hiển thị màn hình sản phẩm theo danh mục)',
  `is_display_bundled` tinyint(4) DEFAULT 0 COMMENT '0: Ko hiển thị, 1: Có hiển thị',
  `type_bundled_product` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'tag: Theo tag sp, category: Theo loại sp, custom_category: Loại chỉ định',
  `limit_item` int(11) DEFAULT 0 COMMENT 'Hiển thị tối đa bao nhiêu sp',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`product_config_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `product_config_details`;
CREATE TABLE `product_config_details` (
  `product_config_detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_config_id` int(11) NOT NULL,
  `object_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'tag: Theo tag sp, category: Theo loại sp, custom_category: Loại chỉ định',
  `object_id` int(11) DEFAULT NULL COMMENT 'Đối tượng ăn theo type',
  PRIMARY KEY (`product_config_detail_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `product_favourite`;
CREATE TABLE `product_favourite` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL COMMENT 'id của product_child',
  `user_id` int(11) NOT NULL COMMENT 'người thích',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `product_images`;
CREATE TABLE `product_images` (
  `product_image_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) DEFAULT NULL,
  `product_child_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã sp con',
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('mobile','desktop') COLLATE utf8mb4_unicode_ci DEFAULT 'desktop',
  `is_avatar` tinyint(4) DEFAULT 0 COMMENT 'Là ảnh đại diện sản phẩm',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`product_image_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `product_inventorys`;
CREATE TABLE `product_inventorys` (
  `product_inventory_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(10) unsigned DEFAULT NULL,
  `product_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `warehouse_id` int(11) NOT NULL,
  `import` decimal(10,0) DEFAULT NULL,
  `export` decimal(10,0) DEFAULT NULL,
  `quantity` decimal(10,0) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  PRIMARY KEY (`product_inventory_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `product_inventory_logs`;
CREATE TABLE `product_inventory_logs` (
  `product_inventory_log_id` int(11) NOT NULL AUTO_INCREMENT,
  `warehouse_id` int(11) NOT NULL COMMENT 'Id kho',
  `product_id` int(11) NOT NULL COMMENT 'Id sản phẩm',
  `product_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã sản phẩm',
  `inventory` int(11) DEFAULT NULL COMMENT 'Số lượng tồn kho',
  `inventory_value` decimal(16,3) DEFAULT NULL COMMENT 'Giá trị tồn kho',
  `export` int(11) DEFAULT NULL COMMENT 'Tổng xuất',
  `import` int(11) DEFAULT NULL COMMENT 'Tổng nhập',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`product_inventory_log_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `product_inventory_serial`;
CREATE TABLE `product_inventory_serial` (
  `product_inventory_serial_id` int(11) NOT NULL AUTO_INCREMENT,
  `warehouse_id` int(11) NOT NULL COMMENT 'Link với mã kho',
  `product_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mã sản phẩm',
  `serial` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Số seri',
  `barcode` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã barcode',
  `status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT 'new' COMMENT 'new: Mới, export: Đã xuất kho',
  `inventory_checking_status_id` int(11) DEFAULT 1 COMMENT 'Id trạng thái kiểm tra',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`product_inventory_serial_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `product_model`;
CREATE TABLE `product_model` (
  `product_model_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_model_name` varchar(191) CHARACTER SET utf8mb4 NOT NULL,
  `product_model_note` varchar(254) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_deleted` tinyint(1) DEFAULT 0,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`product_model_id`) USING BTREE,
  UNIQUE KEY `product_model_name` (`product_model_name`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `product_search`;
CREATE TABLE `product_search` (
  `product_search_id` int(11) NOT NULL AUTO_INCREMENT,
  `keyword` text DEFAULT NULL COMMENT 'Từ khoá search',
  `slug` text DEFAULT NULL COMMENT 'Từ khoá search dạng slug',
  `sum` int(11) DEFAULT NULL COMMENT 'Số lần tìm kiếm',
  `last_search` datetime DEFAULT NULL COMMENT 'Ngày search gần nhất',
  `is_deleted` tinyint(4) DEFAULT 0 COMMENT 'Đánh dấu xoá',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  PRIMARY KEY (`product_search_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `product_search_logs`;
CREATE TABLE `product_search_logs` (
  `product_search_log_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_search_id` int(11) DEFAULT NULL COMMENT 'Link với bảng search của user',
  `keyword` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Từ khoá search',
  `slug` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Từ khoá search dạng slug',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`product_search_log_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `product_suggest_cache`;
CREATE TABLE `product_suggest_cache` (
  `product_suggest_cache_id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) DEFAULT NULL COMMENT 'Loại product (product) hoặc service (service)',
  `product_id` int(11) DEFAULT NULL COMMENT 'id product hoặc service',
  `user_id` int(11) DEFAULT NULL COMMENT 'id khách hàng. Dành cho các điều kiện thuộc cá nhân hoá',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`product_suggest_cache_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Dánh sách cache sản phẩm';


DROP TABLE IF EXISTS `product_suggest_config`;
CREATE TABLE `product_suggest_config` (
  `product_suggest_config_id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(255) DEFAULT NULL COMMENT 'Key phân biệt điều kiện',
  `type` varchar(255) DEFAULT NULL COMMENT 'Loại sản phẩm (product) hoặc dịch vụ (service)',
  `is_condition` tinyint(1) DEFAULT NULL COMMENT 'Loại điều kiện. 1 là đúng điều kiện . 0 là ngược lại với điều kiện',
  `product_condition_id` int(11) DEFAULT NULL COMMENT 'Id điều kiện của sản phẩm',
  `type_condition` varchar(255) DEFAULT NULL COMMENT 'Type của cấu hình sản phẩm gợi ý',
  `quantity` int(11) DEFAULT NULL COMMENT 'Số lượng sản phẩm',
  `start_date` datetime DEFAULT NULL COMMENT 'Thời gian bắt đầu',
  `end_date` datetime DEFAULT NULL COMMENT 'Thời gian kết thúc',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`product_suggest_config_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Danh sách cấu hình sản phẩm\r\n';


DROP TABLE IF EXISTS `product_suggest_config_map`;
CREATE TABLE `product_suggest_config_map` (
  `product_suggest_config_map_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_suggest_config_id` int(11) DEFAULT NULL COMMENT 'id cấu hình sản phẩm gợi ý',
  `type` varchar(255) DEFAULT NULL COMMENT 'Loại : tags là tag sản phẩm',
  `object_id` int(11) DEFAULT NULL COMMENT 'Id tag nếu type là tag',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`product_suggest_config_map_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `product_tags`;
CREATE TABLE `product_tags` (
  `product_tag_id` int(11) NOT NULL AUTO_INCREMENT,
  `keyword` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Từ khoá của hash tag',
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên hash tag',
  `is_deleted` tinyint(4) DEFAULT 0 COMMENT 'Đánh dấu xoá',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`product_tag_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `product_tag_map`;
CREATE TABLE `product_tag_map` (
  `product_tag_map_id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_id` int(11) DEFAULT NULL COMMENT 'Link với tag',
  `product_child_id` int(11) DEFAULT NULL COMMENT 'Link với sản phẩm',
  PRIMARY KEY (`product_tag_map_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `promotion_daily_time`;
CREATE TABLE `promotion_daily_time` (
  `promotion_daily_id` int(11) NOT NULL AUTO_INCREMENT,
  `promotion_id` int(11) NOT NULL COMMENT 'Link tới CTKM',
  `promotion_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Code của CTKM',
  `start_time` time DEFAULT NULL COMMENT 'giờ bắt đầu chạy hàng ngày',
  `end_time` time DEFAULT NULL COMMENT 'giờ kết thúc hàng ngày',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`promotion_daily_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `promotion_date_time`;
CREATE TABLE `promotion_date_time` (
  `promotion_date_id` int(11) NOT NULL AUTO_INCREMENT,
  `promotion_id` int(11) NOT NULL COMMENT 'Link tới CTKM',
  `promotion_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'code của CTKM',
  `form_date` date DEFAULT NULL COMMENT 'Ngày bắt đầu chạy CTKM',
  `to_date` date DEFAULT NULL COMMENT 'Ngày kết thúc chạy CTKM',
  `start_time` time DEFAULT NULL COMMENT 'Giờ bắt đầu chạy (của ngày form date)',
  `end_time` time DEFAULT NULL COMMENT 'Giờ kết thúc (của ngày to date)',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`promotion_date_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `promotion_details`;
CREATE TABLE `promotion_details` (
  `promotion_detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `promotion_id` int(11) NOT NULL COMMENT 'Link tới CTKM',
  `promotion_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Code của CTKM',
  `object_type` enum('service_card','service','product') COLLATE utf8mb4_unicode_ci NOT NULL,
  `object_id` int(11) DEFAULT NULL,
  `object_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `object_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `base_price` decimal(16,3) DEFAULT NULL COMMENT 'Giá gốc',
  `promotion_price` decimal(16,3) DEFAULT NULL COMMENT 'Giá giảm',
  `quantity_buy` int(11) DEFAULT NULL COMMENT 'Số lượng cần mua',
  `quantity_gift` int(11) DEFAULT NULL COMMENT 'Số lượng được tặng',
  `gift_object_type` enum('service_card','service','product') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Loại quà tặng',
  `gift_object_id` int(11) DEFAULT NULL COMMENT 'Link tới quà tặng',
  `gift_object_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gift_object_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã quà tặng',
  `is_actived` tinyint(4) DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`promotion_detail_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `promotion_logs`;
CREATE TABLE `promotion_logs` (
  `promotion_log_id` int(11) NOT NULL AUTO_INCREMENT,
  `promotion_id` int(11) NOT NULL COMMENT 'Link tới CTKM',
  `promotion_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Code của CTKM',
  `start_date` datetime DEFAULT NULL COMMENT 'Ngày bắt đầu CTKM',
  `end_date` datetime DEFAULT NULL COMMENT 'Ngày kết thúc CTKM',
  `order_id` int(11) DEFAULT NULL COMMENT 'Link tới đơn hàng',
  `order_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã đơn hàng',
  `object_type` enum('service_card','service','product') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `object_id` int(11) DEFAULT NULL,
  `object_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL COMMENT 'Số lượng mua',
  `base_price` decimal(10,3) DEFAULT NULL COMMENT 'Giá gốc',
  `promotion_price` decimal(10,3) DEFAULT NULL COMMENT 'Giá giảm',
  `gift_object_type` enum('service_card','service','product') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Loại quà tặng',
  `gift_object_id` int(11) DEFAULT NULL,
  `gift_object_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã quà tặng',
  `quantity_gift` int(11) DEFAULT NULL COMMENT 'Số lượng quà được tặng',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`promotion_log_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `promotion_master`;
CREATE TABLE `promotion_master` (
  `promotion_id` int(11) NOT NULL AUTO_INCREMENT,
  `promotion_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mã CTKM',
  `promotion_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên CTKM',
  `start_date` datetime NOT NULL COMMENT 'Ngày bắt đầu',
  `end_date` datetime NOT NULL COMMENT 'Ngày kết thúc',
  `is_actived` tinyint(4) NOT NULL DEFAULT 1 COMMENT 'Trạng thái hoạt động',
  `is_display` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'Hiển thị trên app',
  `is_time_campaign` tinyint(4) DEFAULT NULL COMMENT '1: Áp dụng KM theo giờ, 0: Không áp dụng Km theo giờ',
  `time_type` varchar(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'D: Daily, W: Weekly, M: Monthly, R: Date',
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ảnh đại diện',
  `branch_apply` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'all: tất cả  1,2,3: lưu id của chi nhánh',
  `is_feature` tinyint(4) DEFAULT 1 COMMENT 'Hiển thị nổi bật trang chủ',
  `position_feature` tinyint(4) DEFAULT NULL COMMENT 'Vị trí hiển thị nổi bật nếu is_feature = 1 ',
  `promotion_type` int(11) NOT NULL COMMENT '1: Giảm giá 2: Quà tặng 3: Tích lũy',
  `promotion_type_discount` enum('percent','same','custom') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Percent: %, Same: đồng giá, Custom: Tùy chỉnh',
  `promotion_type_discount_value` decimal(16,3) DEFAULT NULL,
  `order_source` enum('all','live','app') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'All: tất cả, Live: trực tiếp, App: app loyalty',
  `quota` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Loại giảm giá thì quota là tiền, KM theo quà tặng quota là total sp',
  `promotion_apply_to` int(11) DEFAULT NULL COMMENT '1: Tất cả khách hàng, 2: Hạng thành viên, 3: Nhóm khách hàng, 4: Khách hàng chỉ định',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mô tả ngắn',
  `description_detail` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mô tả chi tiết',
  `is_deleted` tinyint(4) DEFAULT 0,
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `site_id` int(11) DEFAULT NULL,
  `quota_use` decimal(16,3) DEFAULT 0.000 COMMENT 'Quota đã sử dụng',
  PRIMARY KEY (`promotion_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `promotion_monthly_time`;
CREATE TABLE `promotion_monthly_time` (
  `promotion_monthly_id` int(11) NOT NULL AUTO_INCREMENT,
  `promotion_id` int(11) DEFAULT NULL COMMENT 'link toi CTKM',
  `promotion_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'code của CTKM',
  `run_date` date DEFAULT NULL COMMENT 'Ngày chạy KM trong tháng',
  `start_time` time DEFAULT NULL COMMENT 'giờ bắt đầu chạy ',
  `end_time` time DEFAULT NULL COMMENT 'giờ kết thúc ',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`promotion_monthly_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `promotion_object_apply`;
CREATE TABLE `promotion_object_apply` (
  `promotion_object_apply_id` int(11) NOT NULL AUTO_INCREMENT,
  `promotion_id` int(11) NOT NULL COMMENT 'Link tới CTKM',
  `promotion_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Code của CTKM',
  `object_type` int(11) NOT NULL COMMENT '1: Hạng thành viên, 2: Nhóm khách hàng, 3: Khách hàng ',
  `object_id` int(11) NOT NULL,
  `object_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`promotion_object_apply_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `promotion_weekly_time`;
CREATE TABLE `promotion_weekly_time` (
  `promotion_week_id` int(11) NOT NULL AUTO_INCREMENT,
  `promotion_id` int(11) NOT NULL COMMENT 'link toi CTKM',
  `promotion_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'code của CTKM',
  `default_start_time` time DEFAULT NULL COMMENT 'giờ bắt đầu chạy mặc định',
  `default_end_time` time DEFAULT NULL COMMENT 'giờ kết thúc mặc định',
  `is_monday` tinyint(4) DEFAULT NULL COMMENT 'Chạy vào ngày thứ 2',
  `is_tuesday` tinyint(4) DEFAULT NULL COMMENT 'Chạy vào ngày thứ 3',
  `is_wednesday` tinyint(4) DEFAULT NULL COMMENT 'Chạy vào ngày thứ 4',
  `is_thursday` tinyint(4) DEFAULT NULL COMMENT 'Chạy vào ngày thứ 5',
  `is_friday` tinyint(4) DEFAULT NULL COMMENT 'Chạy vào ngày thứ 6',
  `is_saturday` tinyint(4) DEFAULT NULL COMMENT 'Chạy vào ngày thứ 7',
  `is_sunday` tinyint(4) DEFAULT NULL COMMENT 'Chạy vào ngày chủ nhật',
  `is_other_monday` tinyint(4) DEFAULT NULL COMMENT 'Chạy giờ khác giờ mặc định vào thứ 2',
  `is_other_monday_start_time` time DEFAULT NULL COMMENT 'Giờ chạy riêng ngày thứ 2',
  `is_other_monday_end_time` time DEFAULT NULL COMMENT 'Giờ kết thúc riêng ngày thứ 2',
  `is_other_tuesday` tinyint(4) DEFAULT NULL COMMENT 'Chạy giờ khác giờ mặc định vào thứ 3',
  `is_other_tuesday_start_time` time DEFAULT NULL COMMENT 'Giờ chạy riêng ngày thứ 3',
  `is_other_tuesday_end_time` time DEFAULT NULL COMMENT 'Giờ kết thúc riêng ngày thứ 3',
  `is_other_wednesday` tinyint(4) DEFAULT NULL COMMENT 'Chạy giờ khác giờ mặc định vào thứ 4',
  `is_other_wednesday_start_time` time DEFAULT NULL COMMENT 'Giờ chạy riêng ngày thứ 4',
  `is_other_wednesday_end_time` time DEFAULT NULL COMMENT 'Giờ kết thúc riêng ngày thứ 4',
  `is_other_thursday` tinyint(4) DEFAULT NULL COMMENT 'Chạy giờ khác giờ mặc định vào thứ 5',
  `is_other_thursday_start_time` time DEFAULT NULL COMMENT 'Giờ chạy riêng ngày thứ 5',
  `is_other_thursday_end_time` time DEFAULT NULL COMMENT 'Giờ kết thúc riêng ngày thứ 5',
  `is_other_friday` tinyint(4) DEFAULT NULL COMMENT 'Chạy giờ khác giờ mặc định vào thứ 6',
  `is_other_friday_start_time` time DEFAULT NULL COMMENT 'Giờ chạy riêng ngày thứ 6',
  `is_other_friday_end_time` time DEFAULT NULL COMMENT 'Giờ kết thúc riêng ngày thứ 6',
  `is_other_saturday` tinyint(4) DEFAULT NULL COMMENT 'Chạy giờ khác giờ mặc định vào thứ 7',
  `is_other_saturday_start_time` time DEFAULT NULL COMMENT 'Giờ chạy riêng ngày thứ 7',
  `is_other_saturday_end_time` time DEFAULT NULL COMMENT 'Giờ kết thúc riêng ngày thứ 7',
  `is_other_sunday` tinyint(4) DEFAULT NULL COMMENT 'Chạy giờ khác giờ mặc định vào chủ nhật',
  `is_other_sunday_start_time` time DEFAULT NULL COMMENT 'Giờ chạy riêng ngày chủ nhật',
  `is_other_sunday_end_time` time DEFAULT NULL COMMENT 'Giờ kết thúc riêng ngày chủ nhật',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`promotion_week_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `province`;
CREATE TABLE `province` (
  `provinceid` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location_id` int(11) NOT NULL DEFAULT 1,
  `sort_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`provinceid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `rating_log`;
CREATE TABLE `rating_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object` enum('order','appointment','product','airtist','voucher','article','service') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'loại rating',
  `object_value` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'giá trị từng loại (id)',
  `rating_by` int(11) NOT NULL COMMENT 'người dùng đánh giá',
  `rating_value` int(11) NOT NULL COMMENT 'chấm điểm',
  `comment` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_show` tinyint(4) DEFAULT 0 COMMENT '0: ko hiển thị, 1: hiển thị',
  `is_check_browser` tinyint(4) DEFAULT 0 COMMENT '1: Cần phê duyệt, 0: Ko cần phê duyệt',
  `is_browser` tinyint(4) DEFAULT 0 COMMENT '1: Đã duyệt, 0: Chưa duyệt',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Bảng ghi log rating ';


DROP TABLE IF EXISTS `rating_log_image`;
CREATE TABLE `rating_log_image` (
  `rating_log_image_id` int(11) NOT NULL AUTO_INCREMENT,
  `rating_log_id` int(11) DEFAULT NULL COMMENT 'Link với log đánh giá',
  `type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'image: Hình, video: Video',
  `link` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Link ảnh or video',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`rating_log_image_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `rating_log_suggest`;
CREATE TABLE `rating_log_suggest` (
  `rating_log_suggest_id` int(11) NOT NULL AUTO_INCREMENT,
  `rating_log_id` int(11) DEFAULT NULL COMMENT 'Link với log đánh giá',
  `content_suggest` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Cú pháp gợi ý đánh giá',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`rating_log_suggest_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `reason_delivery_fail`;
CREATE TABLE `reason_delivery_fail` (
  `reason_delivery_fail_id` int(11) NOT NULL AUTO_INCREMENT,
  `reason_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_actived` tinyint(4) NOT NULL DEFAULT 1,
  `is_deleted` tinyint(4) NOT NULL DEFAULT 0,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`reason_delivery_fail_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `receipts`;
CREATE TABLE `receipts` (
  `receipt_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `receipt_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `object_type` enum('order','debt','delivery','maintenance') COLLATE utf8mb4_unicode_ci DEFAULT 'order',
  `object_id` int(11) NOT NULL DEFAULT 0,
  `order_id` int(11) DEFAULT 0,
  `total_money` decimal(16,3) DEFAULT NULL COMMENT 'Tổng tiền dịch vụ',
  `voucher_code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã giảm giá',
  `status` enum('unpaid','part-paid','paid','cancel','fail') COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount` decimal(16,3) DEFAULT 0.000 COMMENT 'Số tiền giảm bằng voucher',
  `custom_discount` decimal(16,3) DEFAULT 0.000 COMMENT 'Số tiền giảm trực tiếp',
  `is_discount` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Đã thanh trừ giảm giá toàn bill',
  `amount` decimal(16,3) DEFAULT 0.000 COMMENT 'Số tiền cần thanh toán',
  `amount_paid` decimal(16,3) DEFAULT 0.000 COMMENT 'Số tiền đã thanh toán',
  `amount_return` decimal(16,3) DEFAULT 0.000 COMMENT 'Số tiền hoàn lại',
  `note` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'nội dung thu',
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `discount_member` decimal(16,3) DEFAULT NULL,
  `receipt_source` enum('direct','delivery') COLLATE utf8mb4_unicode_ci DEFAULT 'direct' COMMENT 'Nguồn thanh toán',
  `receipt_type_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'RTC_ORDER' COMMENT 'Mã loại thu',
  `object_accounting_type_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã loại đối tượng thu chi',
  `object_accounting_id` int(11) DEFAULT NULL COMMENT 'Id đối tượng thu chi',
  `object_accounting_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên đối tượng thu chi',
  `type_insert` enum('manual','auto') COLLATE utf8mb4_unicode_ci DEFAULT 'auto' COMMENT 'Insert bằng tay (manual), tự động (auto)',
  `document_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã chứng từ nếu có',
  `is_deleted` tinyint(4) DEFAULT 0,
  PRIMARY KEY (`receipt_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `receipt_details`;
CREATE TABLE `receipt_details` (
  `receipt_detail_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `receipt_id` int(11) NOT NULL,
  `cashier_id` int(11) DEFAULT NULL,
  `receipt_type` enum('cash','transfer','visa','member_card','member_point','member_money') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(16,3) NOT NULL DEFAULT 0.000,
  `note` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_method_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Phương thức thanh toán',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`receipt_detail_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `receipt_online`;
CREATE TABLE `receipt_online` (
  `receipt_online_id` int(11) NOT NULL AUTO_INCREMENT,
  `receipt_id` int(11) DEFAULT NULL COMMENT 'Link với phiếu thu',
  `object_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Loại thanh toán order: đơn hàng, receipt: Phiếu thu, debt: Công nợ, maintenance: Phiếu bảo trì',
  `object_id` int(11) DEFAULT NULL COMMENT 'Link với id đối tượng',
  `object_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã đối tượng',
  `payment_method_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã phương thức thanh toán',
  `amount_paid` decimal(16,3) DEFAULT NULL COMMENT 'Tiền thanh toán',
  `payment_transaction_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã đợt thanh toán với đối tác',
  `payment_transaction_uuid` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Uuid đợt thanh toán với đối tác',
  `payment_time` datetime DEFAULT NULL COMMENT 'Ngày thanh toán',
  `status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT 'inprocess' COMMENT 'inprocess: Đang thực hiện, success: Thành công, cancel: Huỷ',
  `performer_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên người thực hiện',
  `performer_phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Số điện thoại người thực hiện',
  `type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'auto: Tự động, manual: Thủ công',
  `note` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ghi chú',
  `platform` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT 'web' COMMENT 'web: Trên web, app_loyalty: App khách hàng, app_staff: App nhân viên, app_carrier: App nhân viên',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`receipt_online_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `receipt_type`;
CREATE TABLE `receipt_type` (
  `receipt_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `receipt_type_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mã loại thu',
  `receipt_type_name_vi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên loại thu ',
  `receipt_type_name_en` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên loại thu ',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'trạng thái ',
  `is_system` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`receipt_type_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Danh sách phương thức thanh toán';


DROP TABLE IF EXISTS `redeemed_gifts`;
CREATE TABLE `redeemed_gifts` (
  `redeemed_gift_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) DEFAULT NULL,
  `object_type` enum('product','service','service_card','voucher') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `object_id` int(11) DEFAULT NULL,
  `object_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `object_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`redeemed_gift_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `religion`;
CREATE TABLE `religion` (
  `religion_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id tôn giáo',
  `name` varchar(191) NOT NULL COMMENT 'tên tôn giáo',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`religion_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `repairs`;
CREATE TABLE `repairs` (
  `repair_id` int(11) NOT NULL AUTO_INCREMENT,
  `repair_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `staff_id` int(11) NOT NULL,
  `repair_cost` decimal(16,3) NOT NULL COMMENT 'Chi phí bảo dưỡng',
  `insurance_pay` decimal(16,3) DEFAULT NULL COMMENT 'Bảo hiểm chi trả',
  `total_pay` decimal(16,3) DEFAULT NULL COMMENT 'Tổng tiền phải trả  bao gồm chi phí phát sinh',
  `amount_pay` decimal(16,0) DEFAULT NULL COMMENT 'Tổng tiền phải trả chưa bao gồm chi phí phát sinh',
  `object_type` enum('product','service','service_card') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Loại đối tượng (sản phẩm, dịch vụ, thẻ dv)',
  `object_id` int(11) NOT NULL COMMENT 'Id đối tượng bảo dưỡng',
  `object_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã đối tượng bảo dưỡng',
  `object_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tình trạng đối tượng bảo dưỡng',
  `repair_content` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nội dung bảo dưỡng',
  `status` enum('new','processing','finish','cancel','ready_delivery','received') COLLATE utf8mb4_unicode_ci DEFAULT 'new' COMMENT '''new'': mới ,''received'' : đã nhận hàng ,''processing'': đang xử lý,''ready_delivery'': sẵn sàng trả hàng,''finish'': hoàn tất',
  `repair_date` datetime DEFAULT NULL COMMENT 'Ngày bảo dưỡng',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`repair_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `repair_costs`;
CREATE TABLE `repair_costs` (
  `repair_cost_id` int(11) NOT NULL AUTO_INCREMENT,
  `repair_id` int(11) NOT NULL COMMENT 'Mã phiếu bảo dưỡng',
  `maintenance_cost_type` int(11) NOT NULL COMMENT 'Loại chi phí bảo trì (bảo dưỡng)',
  `cost` decimal(16,3) NOT NULL COMMENT 'Chi phí',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`repair_cost_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `repair_images`;
CREATE TABLE `repair_images` (
  `repair_image_id` int(11) NOT NULL AUTO_INCREMENT,
  `repair_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mã phiếu bảo dưỡng',
  `type` enum('before','after') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'before' COMMENT 'Loại ảnh: trước bảo dưỡng, sau bảo dưỡng',
  `link` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Link ảnh',
  `is_deleted` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`repair_image_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `reset_rank_log`;
CREATE TABLE `reset_rank_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `time_reset_rank_id` int(11) NOT NULL COMMENT 'Thời gian reset rank',
  `month_reset` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tháng reset rank ứng với time reset',
  `member_level_id` int(11) NOT NULL COMMENT 'Hạng mới',
  `member_level_old_id` int(11) DEFAULT NULL COMMENT 'Hạng cũ',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `role_actions`;
CREATE TABLE `role_actions` (
  `role_action_id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_title_id` int(11) DEFAULT NULL COMMENT 'Nhóm nhân viên',
  `action_id` int(11) DEFAULT NULL COMMENT 'Chức năng',
  `group_id` int(11) DEFAULT NULL COMMENT 'Nhom quyên',
  `is_actived` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`role_action_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `role_group`;
CREATE TABLE `role_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'tên nhóm',
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'slug để check trùng',
  `is_actived` tinyint(1) DEFAULT 1 COMMENT 'trạng thái',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `role_pages`;
CREATE TABLE `role_pages` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_title_id` int(11) DEFAULT NULL COMMENT 'Chức vụ nhân viên',
  `group_id` int(11) DEFAULT NULL COMMENT 'Nhóm quyền',
  `page_id` int(11) DEFAULT NULL COMMENT 'Trang (page)',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_actived` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`role_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `rooms`;
CREATE TABLE `rooms` (
  `room_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seat` int(11) NOT NULL COMMENT 'số ghế phục vụ',
  `seat_using` int(11) DEFAULT NULL COMMENT 'số ghế đã sử dụng',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `is_actived` tinyint(1) DEFAULT 1,
  `is_deleted` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`room_id`) USING BTREE,
  UNIQUE KEY `name` (`name`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `room_logs`;
CREATE TABLE `room_logs` (
  `room_log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `room_id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `checkin` datetime DEFAULT NULL COMMENT 'thời gian vào phòng',
  `checkout` datetime DEFAULT NULL COMMENT 'thời gian ra phòng',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `is_finish` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`room_log_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `rule_booking`;
CREATE TABLE `rule_booking` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên các bước đặt lịch',
  `is_actived` tinyint(1) DEFAULT 1,
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `rule_menu`;
CREATE TABLE `rule_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên menu',
  `is_actived` tinyint(1) DEFAULT 1,
  `position` int(11) DEFAULT NULL COMMENT 'Vị trí hiển thị',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `rule_setting_other`;
CREATE TABLE `rule_setting_other` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_actived` tinyint(1) DEFAULT 1,
  `day` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Số ngày đặt lịch xa nhất',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `salary`;
CREATE TABLE `salary` (
  `salary_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `season_month` int(11) DEFAULT NULL COMMENT 'tháng của kỳ lương',
  `season_year` int(11) DEFAULT NULL COMMENT 'năm của kỳ lương',
  `date_start` date DEFAULT NULL COMMENT 'ngày bắt đầu',
  `date_end` date DEFAULT NULL COMMENT 'ngày kết thúc',
  `queue_status` tinyint(1) DEFAULT 0 COMMENT '1 : thành công, 0 : là đang chạy',
  `is_active` tinyint(1) DEFAULT 0 COMMENT '0 là chưa khoá, 1 đã khoá',
  `is_deleted` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`salary_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Bảng lương theo kỳ';


DROP TABLE IF EXISTS `salary_allowance`;
CREATE TABLE `salary_allowance` (
  `salary_allowance_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `salary_allowance_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `salary_allowance_type` enum('percent','money') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'money',
  `salary_allowance_num` float DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`salary_allowance_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `salary_bonus_minus`;
CREATE TABLE `salary_bonus_minus` (
  `salary_bonus_minus_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `salary_bonus_minus_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `salary_bonus_minus_type` enum('bonus','minus') COLLATE utf8mb4_unicode_ci DEFAULT 'bonus',
  `salary_bonus_minus_num` decimal(16,2) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`salary_bonus_minus_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `salary_commission_config`;
CREATE TABLE `salary_commission_config` (
  `salary_commission_config_id` int(11) NOT NULL AUTO_INCREMENT,
  `department_id` int(11) DEFAULT NULL,
  `type_view` enum('kd','kt') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'kd : kinh doanh, kt : kỹ thuật',
  `internal_new` float(16,0) DEFAULT NULL COMMENT 'bán mới nội bộ',
  `internal_renew` float(16,0) DEFAULT NULL COMMENT 'gia hạn nội bộ',
  `external_new` float(16,0) DEFAULT NULL COMMENT 'bán mới bên ngoài',
  `external_renew` float(16,0) DEFAULT NULL COMMENT 'gia hạn bên ngoài',
  `partner_new` float(16,0) DEFAULT NULL COMMENT 'bán mới địa lý',
  `partner_renew` float(16,0) DEFAULT NULL COMMENT 'gia hạn đại lý',
  `installation_commission` float(16,0) DEFAULT NULL COMMENT 'hoa hồng lắp đặt',
  `kpi_probationers` double(18,2) DEFAULT NULL COMMENT 'KPI nhân viên thử việc',
  `kpi_staff` double(18,2) DEFAULT NULL,
  `is_actived` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`salary_commission_config_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `salary_commission_config_cache`;
CREATE TABLE `salary_commission_config_cache` (
  `salary_commission_config_cache_id` int(11) NOT NULL AUTO_INCREMENT,
  `salary_commission_config_id` int(11) NOT NULL,
  `salary_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `type_view` enum('kd','kt') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'kd : kinh doanh, kt : kỹ thuật',
  `internal_new` float(16,0) DEFAULT NULL COMMENT 'bán mới nội bộ',
  `internal_renew` float(16,0) DEFAULT NULL COMMENT 'gia hạn nội bộ',
  `external_new` float(16,0) DEFAULT NULL COMMENT 'bán mới bên ngoài',
  `external_renew` float(16,0) DEFAULT NULL COMMENT 'gia hạn bên ngoài',
  `partner_new` float(16,0) DEFAULT NULL COMMENT 'bán mới địa lý',
  `partner_renew` float(16,0) DEFAULT NULL COMMENT 'gia hạn đại lý',
  `installation_commission` float(16,0) DEFAULT NULL COMMENT 'hoa hồng lắp đặt',
  `kpi_probationers` double(18,2) DEFAULT NULL COMMENT 'KPI nhân viên thử việc',
  `kpi_staff` double(18,2) DEFAULT NULL,
  `is_actived` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`salary_commission_config_cache_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Bảng config cache lại sau khi tạo lương';


DROP TABLE IF EXISTS `salary_file`;
CREATE TABLE `salary_file` (
  `salary_file_id` int(11) NOT NULL AUTO_INCREMENT,
  `salary_id` int(11) DEFAULT NULL,
  `type` enum('import','export') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_record` int(11) DEFAULT NULL,
  `total_success` int(11) DEFAULT NULL,
  `total_fail` int(11) DEFAULT NULL,
  `file_fail` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`salary_file_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Các file excel đã import, export';


DROP TABLE IF EXISTS `salary_staff`;
CREATE TABLE `salary_staff` (
  `salary_staff_id` int(11) NOT NULL AUTO_INCREMENT,
  `salary_id` int(11) DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `staff_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `staff_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `department_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `salary` double(16,2) DEFAULT 0.00 COMMENT 'Lương cơ bảng',
  `total_revenue` double(16,2) DEFAULT 0.00 COMMENT 'tổng doanh thu',
  `total_commission` double(16,2) DEFAULT 0.00 COMMENT 'tổng hoa hồng',
  `total_kpi` double(16,2) DEFAULT 0.00 COMMENT 'tổng kpi',
  `total_allowance` double(16,2) DEFAULT 0.00 COMMENT 'tổng phụ cấp',
  `plus` double(16,2) DEFAULT 0.00 COMMENT 'tăng',
  `minus` double(16,2) DEFAULT 0.00 COMMENT 'giảm',
  `total` double(16,2) DEFAULT 0.00 COMMENT 'tổng tiền thực lãnh',
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `revenue_kpi` double(16,2) DEFAULT 0.00,
  PRIMARY KEY (`salary_staff_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Lương của nhân viên';


DROP TABLE IF EXISTS `salary_staff_detail`;
CREATE TABLE `salary_staff_detail` (
  `salary_staff_detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `salary_staff_id` int(11) DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `contract_id` int(11) DEFAULT NULL COMMENT 'Nếu từ salary : id nhân viên, revenue : id đợt thanh toán',
  `ticket_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'nếu đến từ revenue thì đến từ ticket nào',
  `value` double(16,2) DEFAULT 0.00 COMMENT 'số tiền',
  `percent` float(10,0) DEFAULT NULL,
  `commission` double(16,2) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`salary_staff_detail_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Chi tiết lương của 1 nhân viên';


DROP TABLE IF EXISTS `service`;
CREATE TABLE `service` (
  `service_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`service_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `services`;
CREATE TABLE `services` (
  `service_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `service_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_category_id` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `service_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `service_avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price_standard` decimal(16,3) DEFAULT NULL,
  `is_sale` tinyint(1) DEFAULT NULL COMMENT 'dịch vụ giảm giá',
  `service_type` enum('normal','hot','new') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `time` int(11) DEFAULT NULL COMMENT 'thời gian phục vụ mỗi dịch vụ',
  `have_material` tinyint(1) DEFAULT 0 COMMENT 'có sử dụng nguyên liệu trừ kho',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `staff_commission_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type_staff_commission` enum('percent','money') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Kiểu hoa hồng cho nhân viên phục vụ',
  `refer_commission_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type_refer_commission` enum('percent','money') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Kiểu hoa hồng cho khách giới thiệu',
  `deal_commission_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Giá trị hoa hồng cho deal (theo % hoặc tiền)',
  `type_deal_commission` enum('percent','money') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Kiểu hoa hồng cho deal',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `is_deleted` tinyint(1) DEFAULT 0,
  `is_actived` tinyint(1) DEFAULT 1,
  `detail_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_surcharge` tinyint(4) DEFAULT 0 COMMENT 'Dịch vụ phụ thu',
  `is_remind` tinyint(4) DEFAULT 0 COMMENT 'Dự kiến nhắc sử dụng lại',
  `remind_value` int(11) DEFAULT NULL COMMENT 'Số ngày dự kiến nhắc',
  PRIMARY KEY (`service_id`) USING BTREE,
  UNIQUE KEY `service_name_code` (`service_name`,`service_code`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `service_branch_prices`;
CREATE TABLE `service_branch_prices` (
  `service_branch_price_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `branch_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `old_price` decimal(16,3) DEFAULT NULL,
  `new_price` decimal(16,3) DEFAULT NULL,
  `is_actived` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `price_week` decimal(16,3) DEFAULT 0.000 COMMENT 'Giá tuần',
  `price_month` decimal(16,3) DEFAULT 0.000 COMMENT 'Giá tháng',
  `price_year` decimal(16,3) DEFAULT 0.000 COMMENT 'Giá năm',
  PRIMARY KEY (`service_branch_price_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `service_cards`;
CREATE TABLE `service_cards` (
  `service_card_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `service_card_group_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_is_all` tinyint(1) DEFAULT NULL COMMENT 'thẻ 1 dịch vụ  or tất cả dịch vụ',
  `service_id` int(11) DEFAULT NULL COMMENT 'mã dịch vụ nếu như loại thẻ đó là thẻ dùng 1 dịch vụ',
  `service_card_type` enum('money','service') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_using` int(11) DEFAULT NULL COMMENT 'số ngày sử dụng so với ngày active . 0 là ko giới hạn',
  `number_using` int(11) DEFAULT NULL COMMENT 'số lần sử dụng . 0 là không giới hạn',
  `price` decimal(16,3) DEFAULT 0.000 COMMENT 'giá bán',
  `money` decimal(16,3) DEFAULT 0.000 COMMENT 'tiền sẽ được cộng vào tài khoản ',
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_actived` tinyint(1) DEFAULT 1,
  `is_deleted` tinyint(1) DEFAULT 0,
  `updated_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Check trùng',
  `staff_commission_value` varchar(244) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type_staff_commission` enum('percent','money') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Kiểu hoa hồng cho nhân viên',
  `refer_commission_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type_refer_commission` enum('percent','money') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Kiểu hoa hồng cho khách giới thiệu',
  `deal_commission_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Giá trị hoa hồng cho deal (theo % hoặc tiền)',
  `type_deal_commission` enum('percent','money') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Kiểu hoa hồng cho deal',
  `is_surcharge` tinyint(4) DEFAULT 0 COMMENT 'Dịch vụ phụ thu',
  `is_remind` tinyint(4) DEFAULT 0 COMMENT 'Dự kiến nhắc sử dụng lại',
  `remind_value` int(11) DEFAULT NULL COMMENT 'Số ngày dự kiến nhắc',
  PRIMARY KEY (`service_card_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `service_card_groups`;
CREATE TABLE `service_card_groups` (
  `service_card_group_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `is_deleted` tinyint(1) DEFAULT 0,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`service_card_group_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `service_card_list`;
CREATE TABLE `service_card_list` (
  `service_card_list_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `branch_id` int(11) NOT NULL,
  `service_card_id` int(11) NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(16,0) DEFAULT NULL,
  `is_actived` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `actived_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `refer_commission` decimal(16,3) DEFAULT 0.000 COMMENT 'Hoa hồng người giới thiệu khi sử dụng thẻ',
  `staff_commission` decimal(16,3) DEFAULT 0.000 COMMENT 'Hoa hồng nv phục vụ khi sử dụng thẻ',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`service_card_list_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `service_card_sold_accrual_logs`;
CREATE TABLE `service_card_sold_accrual_logs` (
  `service_card_sold_accrual_log_id` int(11) NOT NULL AUTO_INCREMENT,
  `card_code_destination` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã code thẻ dịch vụ đã bán được thẻ khác cộng dồn',
  `card_code_target` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã code thẻ dịch vụ đã bán bị cộng dồn vào thẻ khác',
  `number_of_days` int(11) DEFAULT NULL COMMENT 'Số ngày sử dụng còn lại của thẻ target',
  `number_of_uses` int(11) DEFAULT NULL COMMENT 'Số lần sử dụng còn lại của thẻ target',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người cộng dồn',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`service_card_sold_accrual_log_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `service_card_sold_images`;
CREATE TABLE `service_card_sold_images` (
  `service_card_sold_image_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_service_card_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã thẻ dịch vụ đã bán',
  `order_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã đơn hàng',
  `type` enum('before','after') COLLATE utf8mb4_unicode_ci DEFAULT 'before' COMMENT 'Trước khi điều trị, sau khi điều trị',
  `link` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Link ảnh',
  `is_deleted` tinyint(4) DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`service_card_sold_image_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `service_categories`;
CREATE TABLE `service_categories` (
  `service_category_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_actived` tinyint(1) DEFAULT 1,
  `is_deleted` tinyint(1) DEFAULT 0,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`service_category_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `service_favourite`;
CREATE TABLE `service_favourite` (
  `id` int(11) NOT NULL,
  `service_code` varchar(50) NOT NULL COMMENT 'Mã dịch vụ',
  `customer_id` int(11) NOT NULL COMMENT 'Người thích',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `service_images`;
CREATE TABLE `service_images` (
  `service_image_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `service_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('mobile','desktop') COLLATE utf8mb4_unicode_ci DEFAULT 'desktop',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`service_image_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `service_materials`;
CREATE TABLE `service_materials` (
  `service_material_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `service_id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL COMMENT 'id sản phẩm ',
  `material_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'mã sản phẩm',
  `quantity` decimal(11,0) DEFAULT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `is_actived` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`service_material_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `sf_check_in_change_log`;
CREATE TABLE `sf_check_in_change_log` (
  `check_in_change_log_id` int(11) NOT NULL AUTO_INCREMENT,
  `check_in_log_id` int(11) DEFAULT NULL COMMENT 'Map với log check in',
  `time_working_staff_id` int(11) DEFAULT NULL COMMENT 'Map với lịch làm việc',
  `staff_id` int(11) DEFAULT NULL COMMENT 'Map với nhân viên',
  `branch_id` int(11) DEFAULT NULL COMMENT 'Map với chi nhánh',
  `shift_id` int(11) DEFAULT NULL COMMENT 'Map với ca làm việc',
  `check_in_day_old` date DEFAULT NULL COMMENT 'Ngày vào ca',
  `check_in_time_old` time DEFAULT NULL COMMENT 'Giờ vào ca',
  `status_old` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ok: Hợp lệ, not_ok: Không hợp lệ',
  `reason_old` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Lý do',
  `created_type_old` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT 'staff' COMMENT 'admin: Quản lý tạo, staff: Nhân viên tạo',
  `created_by_old` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `check_in_day_new` date DEFAULT NULL COMMENT 'Ngày vào ca',
  `check_in_time_new` time DEFAULT NULL COMMENT 'Giờ vào ca',
  `status_new` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ok: Hợp lệ, not_ok: Không hợp lệ',
  `reason_new` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Lý do',
  `created_type_new` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT 'staff' COMMENT 'admin: Quản lý tạo, staff: Nhân viên tạo',
  `created_by_new` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`check_in_change_log_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `sf_check_in_log`;
CREATE TABLE `sf_check_in_log` (
  `check_in_log_id` int(11) NOT NULL AUTO_INCREMENT,
  `time_working_staff_id` int(11) DEFAULT NULL COMMENT 'Map với lịch làm việc',
  `staff_id` int(11) DEFAULT NULL COMMENT 'Map với nhân viên',
  `branch_id` int(11) DEFAULT NULL COMMENT 'Map với chi nhánh',
  `shift_id` int(11) DEFAULT NULL COMMENT 'Map với ca làm việc',
  `check_in_day` date DEFAULT NULL COMMENT 'Ngày vào ca',
  `check_in_time` time DEFAULT NULL COMMENT 'Giờ vào ca',
  `request_ip` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên wifi',
  `wifi_ip` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên wifi',
  `wifi_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên wifi',
  `status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ok: Hợp lệ, not_ok: Không hợp lệ',
  `reason` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Lý do',
  `created_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT 'staff' COMMENT 'admin: Quản lý tạo, staff: Nhân viên tạo',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Ngày cập nhật',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `timekeeping_type` enum('wifi','gps') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'loại checkin/checkout',
  `latitude` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tọa độ gps chi nhánh',
  `longitude` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tọa độ gps chi nhánh',
  `radius` double DEFAULT NULL COMMENT 'Bán kính checkin/checkout cho phép',
  PRIMARY KEY (`check_in_log_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `sf_check_out_change_log`;
CREATE TABLE `sf_check_out_change_log` (
  `check_out_change_log_id` int(11) NOT NULL AUTO_INCREMENT,
  `check_out_log_id` int(11) DEFAULT NULL COMMENT 'Map với log check out',
  `time_working_staff_id` int(11) DEFAULT NULL COMMENT 'Map với lịch làm việc',
  `staff_id` int(11) DEFAULT NULL COMMENT 'Map với nhân viên',
  `branch_id` int(11) DEFAULT NULL COMMENT 'Map với chi nhánh',
  `shift_id` int(11) DEFAULT NULL COMMENT 'Map với ca làm việc',
  `check_out_day_old` date DEFAULT NULL COMMENT 'Ngày check out',
  `check_out_time_old` time DEFAULT NULL COMMENT 'Ngày check out',
  `status_old` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ok: Hợp lệ, not_ok: Không hợp lệ',
  `reason_old` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Lý do',
  `created_type_old` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT 'staff' COMMENT 'admin: Quản lý, staff: Nhân viên',
  `created_by_old` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `check_out_day_new` date DEFAULT NULL COMMENT 'Ngày check out',
  `check_out_time_new` time DEFAULT NULL COMMENT 'Ngày check out',
  `status_new` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ok: Hợp lệ, not_ok: Không hợp lệ',
  `reason_new` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Lý do',
  `created_type_new` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT 'staff' COMMENT 'admin: Quản lý, staff: Nhân viên',
  `created_by_new` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`check_out_change_log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `sf_check_out_log`;
CREATE TABLE `sf_check_out_log` (
  `check_out_log_id` int(11) NOT NULL AUTO_INCREMENT,
  `time_working_staff_id` int(11) DEFAULT NULL COMMENT 'Map với lịch làm việc',
  `staff_id` int(11) DEFAULT NULL COMMENT 'Map với nhân viên',
  `branch_id` int(11) DEFAULT NULL COMMENT 'Map với chi nhánh',
  `shift_id` int(11) DEFAULT NULL COMMENT 'Map với ca làm việc',
  `check_out_day` date DEFAULT NULL COMMENT 'Ngày check out',
  `check_out_time` time DEFAULT NULL COMMENT 'Ngày check out',
  `request_ip` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên wifi',
  `wifi_ip` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên wifi',
  `wifi_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên wifi',
  `status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ok: Hợp lệ, not_ok: Không hợp lệ',
  `reason` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Lý do',
  `created_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT 'staff' COMMENT 'admin: Quản lý, staff: Nhân viên',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `timekeeping_type` enum('wifi','gps') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'loại checkin/checkout',
  `latitude` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tọa độ gps chi nhánh',
  `longitude` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tọa độ gps chi nhánh',
  `radius` double DEFAULT NULL COMMENT 'Bán kính checkin/checkout cho phép',
  PRIMARY KEY (`check_out_log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `sf_map_shift_branch`;
CREATE TABLE `sf_map_shift_branch` (
  `map_shift_branch_id` int(11) NOT NULL AUTO_INCREMENT,
  `branch_id` int(11) DEFAULT NULL COMMENT 'Map với chi nhánh',
  `shift_id` int(11) DEFAULT NULL COMMENT 'Map với ca làm việc',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`map_shift_branch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `sf_map_work_schedule_shifts`;
CREATE TABLE `sf_map_work_schedule_shifts` (
  `map_work_schedule_shift_id` int(11) NOT NULL AUTO_INCREMENT,
  `work_schedule_id` int(11) DEFAULT NULL COMMENT 'Map với lịch làm việc',
  `shift_id` int(11) DEFAULT NULL COMMENT 'Map với ca làm việc',
  `branch_id` int(11) DEFAULT NULL COMMENT 'Map với chi nhánh',
  `is_monday` tinyint(4) DEFAULT NULL,
  `is_tuesday` tinyint(4) DEFAULT NULL,
  `is_wednesday` tinyint(4) DEFAULT NULL,
  `is_thursday` tinyint(4) DEFAULT NULL,
  `is_friday` tinyint(4) DEFAULT NULL,
  `is_saturday` tinyint(4) DEFAULT NULL,
  `is_sunday` tinyint(4) DEFAULT NULL,
  `is_ot` tinyint(4) DEFAULT 0 COMMENT '0: Ko tăng ca, 1: Tăng ca',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`map_work_schedule_shift_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `sf_map_work_schedule_staffs`;
CREATE TABLE `sf_map_work_schedule_staffs` (
  `map_work_schedule_staff_id` int(11) NOT NULL AUTO_INCREMENT,
  `work_schedule_id` int(11) DEFAULT NULL COMMENT 'Map với lịch làm việc',
  `staff_id` int(11) DEFAULT NULL COMMENT 'Map với nhân viên',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`map_work_schedule_staff_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `sf_shifts`;
CREATE TABLE `sf_shifts` (
  `shift_id` int(11) NOT NULL AUTO_INCREMENT,
  `shift_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên ca làm việc',
  `shift_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Loại ca, full: ca nguyên ngày, half: ca gãy',
  `start_work_time` time DEFAULT NULL COMMENT 'Thời gian bắt đầu làm việc',
  `end_work_time` time DEFAULT NULL COMMENT 'Thời gian kết thúc làm việc',
  `start_lunch_break` time DEFAULT NULL COMMENT 'Thời gian bắt đầu nghỉ trưa',
  `end_lunch_break` time DEFAULT NULL COMMENT 'Thời gian kết thúc nghỉ trưa',
  `start_timekeeping_on` time DEFAULT NULL COMMENT 'Thời gian bắt đầu chấm công',
  `end_timekeeping_on` time DEFAULT NULL COMMENT 'Thời gian kết thúc chấm công',
  `start_timekeeping_out` time DEFAULT NULL COMMENT 'Thời gian bắt đầu chấm công ra',
  `end_timekeeping_out` time DEFAULT NULL COMMENT 'Thời gian kết thúc chấm công ra',
  `timekeeping_coefficient` decimal(16,3) DEFAULT NULL COMMENT 'Hệ số chấm công',
  `is_monday` tinyint(4) DEFAULT 0,
  `is_tuesday` tinyint(4) DEFAULT 0,
  `is_wednesday` tinyint(4) DEFAULT 0,
  `is_thursday` tinyint(4) DEFAULT 0,
  `is_friday` tinyint(4) DEFAULT 0,
  `is_saturday` tinyint(4) DEFAULT 0,
  `is_sunday` tinyint(4) DEFAULT 0,
  `min_time_work` decimal(16,3) DEFAULT NULL COMMENT 'Số giờ làm tối thiểu tính đủ công',
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ghi chú',
  `is_actived` tinyint(4) DEFAULT 1 COMMENT 'Trạng thái',
  `is_deleted` tinyint(4) DEFAULT 0 COMMENT '0: Chưa xoá, 1: Đã xoá',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người thực hiện',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  `time_work` decimal(16,3) DEFAULT 0.000 COMMENT 'Số giờ làm việc',
  PRIMARY KEY (`shift_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `sf_timekeeping_config`;
CREATE TABLE `sf_timekeeping_config` (
  `timekeeping_config_id` int(11) NOT NULL AUTO_INCREMENT,
  `branch_id` int(11) DEFAULT NULL COMMENT 'Map với chi nhánh',
  `wifi_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên wifi',
  `wifi_ip` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'BSSID',
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ghi chú',
  `is_actived` tinyint(4) DEFAULT NULL COMMENT 'Trạng thái',
  `is_deleted` tinyint(4) DEFAULT 0 COMMENT '0: Chưa xoá, 1: Đã xoá',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  `timekeeping_type` enum('wifi','gps') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'loại checkin/checkout',
  `latitude` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tọa độ gps chi nhánh',
  `longitude` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tọa độ gps chi nhánh',
  `allowable_radius` double DEFAULT NULL COMMENT 'Bán kính checkin/checkout cho phép',
  PRIMARY KEY (`timekeeping_config_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `sf_timekeeping_staffs`;
CREATE TABLE `sf_timekeeping_staffs` (
  `timekeeping_staff_id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_id` int(11) DEFAULT NULL COMMENT 'Map với nhân viên',
  `staff_salary_id` int(11) DEFAULT NULL COMMENT 'Mã bảng lương',
  `total_working_day` float DEFAULT 0 COMMENT 'Tổng ngày làm',
  `total_day_saturday` float DEFAULT 0 COMMENT 'Tổng số ngày đi làm thứ bảy',
  `total_day_sunday` float DEFAULT 0 COMMENT 'Tổng số ngày đi làm chủ nhật',
  `total_day_holiday` float DEFAULT 0 COMMENT 'Tổng số ngày lễ',
  `total_working_ot_day` float DEFAULT 0 COMMENT 'Tổng ngày tăng ca',
  `total_working_ot_saturday` float DEFAULT 0 COMMENT 'Tổng ngày tăng ca T7',
  `total_working_ot_sunday` float DEFAULT 0 COMMENT 'Tổng ngày tăng ca CN',
  `total_working_ot_holiday` float DEFAULT 0 COMMENT 'Tổng ngày tăng ca ngày lễ',
  `total_working_time` float DEFAULT 0 COMMENT 'Tổng giờ làm',
  `total_time_saturday` float DEFAULT 0 COMMENT 'Tổng số giờ đi làm thứ bảy',
  `total_time_sunday` float DEFAULT 0 COMMENT 'Tổng số giờ đi làm chủ nhật',
  `total_time_holiday` float DEFAULT 0 COMMENT 'Tổng số giờ lễ',
  `total_working_ot_time` float DEFAULT 0 COMMENT 'Tổng giờ tăng ca',
  `total_time_ot_saturday` float DEFAULT 0 COMMENT 'Tổng ngày tăng ca T7',
  `total_time_ot_sunday` float DEFAULT 0 COMMENT 'Tổng ngày tăng ca CN',
  `total_time_ot_holiday` float DEFAULT 0 COMMENT 'Tổng ngày tăng ca ngày lễ',
  `total_day_late` int(11) DEFAULT 0 COMMENT 'Tổng số lần đi trễ',
  `total_late_time` float DEFAULT 0 COMMENT 'Tổng số giờ đi trễ',
  `total_day_back_soon` int(11) DEFAULT 0 COMMENT 'Tổng số lần về sớm',
  `total_time_back_soon` float DEFAULT 0 COMMENT 'Tổng số giờ về sớm',
  `total_shift_off` float DEFAULT 0 COMMENT 'Tổng số ca nghỉ',
  `total_day_not_check_in` float DEFAULT 0 COMMENT 'Tổng số ngày không check in',
  `total_day_not_check_out` float DEFAULT 0 COMMENT 'Tổng số ngày không check out',
  `total_day_paid_leave` float DEFAULT 0 COMMENT 'Tổng số ngày nghỉ có lương',
  `total_saturday_paid_leave` float DEFAULT 0 COMMENT 'Tổng số ngày nghỉ có lương',
  `total_sunday_paid_leave` float DEFAULT 0 COMMENT 'Tổng số ngày nghỉ có lương',
  `total_holiday_paid_leave` float DEFAULT 0 COMMENT 'Tổng số ngày nghỉ có lương',
  `total_day_unpaid_leave` float DEFAULT 0 COMMENT 'Tổng số ngày nghỉ không lương',
  `total_saturday_unpaid_leave` float DEFAULT 0 COMMENT 'Tổng số ngày nghỉ không lương',
  `total_sunday_unpaid_leave` float DEFAULT 0 COMMENT 'Tổng số ngày nghỉ không lương',
  `total_holiday_unpaid_leave` float DEFAULT 0 COMMENT 'Tổng số ngày nghỉ không lương',
  `start_date` datetime DEFAULT NULL COMMENT 'Ngày bắt đầu',
  `end_date` datetime DEFAULT NULL COMMENT 'Ngày kết thúc',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  `total_time_paid_leave` float DEFAULT NULL,
  `total_saturday_time_paid_leave` float DEFAULT NULL,
  `total_sunday_time_paid_leave` float DEFAULT NULL,
  `total_holiday_time_paid_leave` float DEFAULT NULL,
  `total_time_unpaid_leave` float DEFAULT NULL,
  `total_saturday_time_unpaid_leave` float DEFAULT NULL,
  `total_sunday_time_unpaid_leave` float DEFAULT NULL,
  `total_holiday_time_unpaid_leave` float DEFAULT NULL,
  PRIMARY KEY (`timekeeping_staff_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `sf_timekeeping_staff_details`;
CREATE TABLE `sf_timekeeping_staff_details` (
  `timekeeping_staff_detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `timekeeping_staff_id` int(11) DEFAULT NULL COMMENT 'Map với chấm công',
  `time_working_staff_id` int(11) DEFAULT NULL COMMENT 'Map với ngày làm việc của nhân viên',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`timekeeping_staff_detail_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `sf_time_working_staffs`;
CREATE TABLE `sf_time_working_staffs` (
  `time_working_staff_id` int(11) NOT NULL AUTO_INCREMENT,
  `work_schedule_id` int(11) DEFAULT NULL COMMENT 'Map với lịch làm việc',
  `shift_id` int(11) DEFAULT NULL COMMENT 'Map với ca làm việc',
  `branch_id` int(11) DEFAULT NULL COMMENT 'Map với chi nhánh',
  `staff_id` int(11) DEFAULT NULL COMMENT 'Map với nhân viên',
  `working_day` date DEFAULT NULL COMMENT 'Ngày làm việc',
  `working_time` time DEFAULT NULL COMMENT 'Giờ bắt đầu làm việc',
  `number_late_time` int(11) DEFAULT NULL COMMENT 'Thời gian đi trễ',
  `number_time_back_soon` int(11) DEFAULT NULL COMMENT 'Thời gian đi trễ',
  `start_working_format_day` int(11) DEFAULT NULL COMMENT 'Ngày làm  việc',
  `start_working_format_week` int(11) DEFAULT NULL COMMENT 'Tuần làm việc',
  `start_working_format_month` int(11) DEFAULT NULL COMMENT 'Tháng làm việc',
  `start_working_format_year` int(11) DEFAULT NULL COMMENT 'Năm làm việc',
  `working_end_day` date DEFAULT NULL COMMENT 'Ngày kết thúc làm việc',
  `working_end_time` time DEFAULT NULL COMMENT 'Giờ kết thúc làm việc',
  `timekeeping_coefficient` decimal(16,3) DEFAULT 1.000 COMMENT 'Hệ số công',
  `is_check_in` tinyint(4) DEFAULT 0 COMMENT '0: Ko check in, 1: Đã check in',
  `is_check_out` tinyint(4) DEFAULT 0 COMMENT '0: Ko check out, 1: Đã check out',
  `is_deducted` tinyint(4) DEFAULT NULL COMMENT '1: Trừ lương, 0: Ko trừ lương',
  `is_close` tinyint(4) DEFAULT 0 COMMENT '0: Chưa chốt, 1: Đã chốt',
  `is_ot` tinyint(4) DEFAULT 0 COMMENT '0: Ko tăng ca, 1: Có tăng ca',
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ghi chú',
  `is_deleted` tinyint(4) DEFAULT 0 COMMENT '1: Đã xoá, 0: Chưa xoá',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  `updated_by` int(11) DEFAULT NULL,
  `is_approve_late` tinyint(4) DEFAULT 0,
  `approve_late_by` int(11) DEFAULT 0 COMMENT 'người duyệt',
  `is_approve_soon` tinyint(4) DEFAULT 0,
  `approve_soon_by` int(11) DEFAULT 0 COMMENT 'người duyệt',
  `check_in_by` int(11) DEFAULT 0 COMMENT 'người điểm danh',
  `check_out_by` int(11) DEFAULT 0 COMMENT 'người check out',
  `time_work` decimal(16,3) DEFAULT NULL COMMENT 'Số giờ làm việc',
  `min_time_work` decimal(16,3) DEFAULT NULL COMMENT 'Số giờ làm việc tối thiểu',
  PRIMARY KEY (`time_working_staff_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `sf_time_working_staff_change_log`;
CREATE TABLE `sf_time_working_staff_change_log` (
  `time_working_staff_change_log_id` int(11) NOT NULL AUTO_INCREMENT,
  `time_working_staff_id` int(11) DEFAULT NULL COMMENT 'Map với thời gian làm việc',
  `is_deducted_old` tinyint(4) DEFAULT 0 COMMENT '1: Trừ lương, 0: Ko trừ lương',
  `is_ot_old` tinyint(4) DEFAULT 0 COMMENT '0: Ko tăng ca, 1: Có tăng ca',
  `is_off_old` tinyint(4) DEFAULT 0 COMMENT '0: Ko nghỉ, 1: Có nghỉ',
  `note_old` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ghi chú',
  `is_deducted_new` tinyint(4) DEFAULT 0 COMMENT '1: Trừ lương, 0: Ko trừ lương',
  `is_ot_new` tinyint(4) DEFAULT 0 COMMENT '0: Ko tăng ca, 1: Có tăng ca',
  `is_off_new` tinyint(4) DEFAULT 0 COMMENT '0: Ko nghỉ, 1: Có nghỉ',
  `note_new` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ghi chú',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`time_working_staff_change_log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `sf_work_schedules`;
CREATE TABLE `sf_work_schedules` (
  `work_schedule_id` int(11) NOT NULL AUTO_INCREMENT,
  `work_schedule_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên lịch làm việc',
  `start_day_shift` date DEFAULT NULL COMMENT 'Ngày bắt đầu phân ca',
  `end_day_shift` date DEFAULT NULL COMMENT 'Ngày kết thúc phân ca',
  `repeat` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT 'hard' COMMENT 'hard: Cố định, monthly: Hàng tháng',
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ghi chú',
  `is_actived` tinyint(4) DEFAULT 1 COMMENT 'Trạng thái',
  `is_deleted` tinyint(4) DEFAULT 0 COMMENT '0: Chưa xoá, 1: Đã xoá',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`work_schedule_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `shifts`;
CREATE TABLE `shifts` (
  `shift_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shift_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `is_actived` tinyint(1) DEFAULT 1,
  `is_deleted` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`shift_id`) USING BTREE,
  UNIQUE KEY `shift_code` (`shift_code`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `sms_campaign`;
CREATE TABLE `sms_campaign` (
  `campaign_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên chiến dịch',
  `status` enum('cancel','new','sent') COLLATE utf8mb4_unicode_ci DEFAULT 'new' COMMENT 'Trang thái chiến dịch',
  `content` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nội dung tin nhắn',
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Slug chiến dịch để check trùng',
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mã chiến dịch',
  `value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Thời gian gửi',
  `is_now` tinyint(1) DEFAULT 0 COMMENT 'Gửi ngay',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Ngươi update',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `sent_by` int(11) DEFAULT NULL,
  `time_sent` datetime DEFAULT NULL,
  `branch_id` int(11) NOT NULL COMMENT 'Id chi nhánh',
  `cost` float(16,3) DEFAULT NULL COMMENT 'Chi phí cho chiến dịch',
  `is_deal_created` tinyint(1) DEFAULT 0 COMMENT 'check có tạo deal hay không? 1 có, 0 không',
  PRIMARY KEY (`campaign_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `sms_config`;
CREATE TABLE `sms_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` enum('birthday','new_appointment','cancel_appointment','remind_appointment','paysuccess','new_customer','service_card_nearly_expired','service_card_over_number_used','service_card_expires','delivery_note','confirm_deliveried','order_success','active_warranty_card','otp','is_remind_use') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Loại tin nhắn',
  `value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'điều kiện gửi (Số giờ, số ngày gửi trước)',
  `time_sent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'thời điểm gửi tin nhắn',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên loại tin nhắn',
  `content` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nội dung nhắn tin',
  `is_active` tinyint(1) DEFAULT 0 COMMENT 'Trạng thái tin nhắn',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `actived_by` int(11) DEFAULT NULL COMMENT 'Người active',
  `datetime_actived` datetime DEFAULT NULL COMMENT 'Thời gian active',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `sms_deal`;
CREATE TABLE `sms_deal` (
  `sms_deal_id` int(11) NOT NULL AUTO_INCREMENT,
  `sms_campaign_id` int(11) DEFAULT NULL,
  `closing_date` date DEFAULT NULL COMMENT 'ngày kết thúc dự kiến ',
  `pipeline_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `journey_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Ngươi update',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `amount` decimal(16,3) DEFAULT NULL,
  PRIMARY KEY (`sms_deal_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `sms_deal_detail`;
CREATE TABLE `sms_deal_detail` (
  `sms_deal_detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `sms_deal_id` int(11) NOT NULL,
  `object_id` int(11) NOT NULL COMMENT 'id của dich vụ , sản phẩm hoac thẻ dịch vụ tuỳ objecttype ',
  `object_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'tên của dich vụ , sản phẩm hoac thẻ dịch vụ tuỳ objecttype ',
  `object_type` enum('service_card','service','product','member_card','product_gift','service_gift','service_card_gift') CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `object_code` varchar(1024) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `price` decimal(16,3) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `discount` decimal(16,3) DEFAULT NULL,
  `amount` decimal(16,3) DEFAULT NULL,
  `voucher_code` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT NULL,
  `is_deleted` tinyint(4) DEFAULT 0,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`sms_deal_detail_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `sms_log`;
CREATE TABLE `sms_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `brandname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên brandname',
  `campaign_id` int(11) DEFAULT NULL COMMENT 'Tên chiến dịch',
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Số điện thoại',
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên khách hàng',
  `message` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nội dung tin nhắn',
  `sms_status` enum('new','cancel','sent') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Trạng thái gửi tin',
  `sms_type` enum('birthday','new_appointment','cancel_appointment','remind_appointment','paysuccess','new_customer','service_card_nearly_expired','service_card_over_number_used','service_card_expires','delivery_note','confirm_deliveried','order_success','warranty_actived') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Loại tin nhắn',
  `error_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'tin nhắn trả ve mã lỗi',
  `error_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'thông tin mã lỗi',
  `sms_guid` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'tin nhắn trả về guid',
  `created_at` datetime DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  `time_sent` datetime DEFAULT NULL COMMENT 'Thời gian bắt đầu gửi tin nhắn',
  `time_sent_done` datetime DEFAULT NULL COMMENT 'Thời gian gửi xong tin nhắn',
  `sent_by` int(11) DEFAULT NULL COMMENT 'Người gửi',
  `created_by` int(11) DEFAULT NULL,
  `object_id` int(11) DEFAULT NULL,
  `object_type` enum('customer','customer_appointment','service_card','order','warranty','lead') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type_customer` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'customer' COMMENT 'lead or customer',
  `deal_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Link với mã deal',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `sms_setting_brandname`;
CREATE TABLE `sms_setting_brandname` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `provider` enum('st','vht','vietguys','fpt','viettel','clicksend') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nhà cung cấp',
  `value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Số / Tên brandname',
  `type` enum('brandname','1900xxxx','8xxx','09xxxxxx') COLLATE utf8mb4_unicode_ci DEFAULT 'brandname' COMMENT 'branhname: Brandname, 1900xxxx: Số cố định (1900xxxxx), 8xxx: Số cố định (8xxx), 09xxxxxx: Số ngẫu nhiên (09xxxxxx)',
  `account` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tài khoản hoặc API key',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mật khẩu hoặc API secret',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `is_actived` tinyint(1) DEFAULT 0 COMMENT 'Bật tắt cấu hình',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `sms_vendor_config`;
CREATE TABLE `sms_vendor_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor` enum('st','vht','vietguys','fpt','viettel') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nhà cung cấp',
  `value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Số / Tên brandname',
  `type` enum('brandname','1900xxxx','8xxx','09xxxxxx') COLLATE utf8mb4_unicode_ci DEFAULT 'brandname' COMMENT 'branhname: Brandname, 1900xxxx: Số cố định (1900xxxxx), 8xxx: Số cố định (8xxx), 09xxxxxx: Số ngẫu nhiên (09xxxxxx)',
  `account` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tài khoản hoặc API key',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mật khẩu hoặc API secret',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `is_actived` tinyint(1) DEFAULT 0 COMMENT 'Bật tắt cấu hình',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `spa_info`;
CREATE TABLE `spa_info` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên đơn vị',
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mã đơn vị',
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Số điện thoại',
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Email',
  `hot_line` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Hot line',
  `provinceid` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '79' COMMENT 'Mã tỉnh/ thành phố',
  `districtid` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '760',
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slogan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bussiness_id` int(11) DEFAULT NULL COMMENT 'Mã ngành nghề kinh doanh',
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_actived` tinyint(1) DEFAULT 1,
  `is_deleted` tinyint(1) DEFAULT 0,
  `fanpage` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zalo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instagram_page` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã số thuế',
  `is_part_paid` tinyint(1) DEFAULT 1 COMMENT 'Cho phép thanh toán nhiều lần',
  `introduction` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_apply_order` int(11) DEFAULT NULL COMMENT 'Chi nhánh nhận đơn hàng từ app',
  `total_booking_time` int(11) DEFAULT 0 COMMENT 'Số lượng đặt lịch tối đa trong 1 khung giờ, 0: ko giới hạn',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `spa_notification_auto_config`;
CREATE TABLE `spa_notification_auto_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `notification_auto_group_id` int(11) NOT NULL COMMENT 'loại tin nhắn tự động',
  `key` enum('birthday','new_appointment','cancel_appointment','remind_appointment','paysuccess','new_customer','service_card_nearly_expired','service_card_over_number_used','service_card_expires','new_order','order_delivering','order_deliveried','order_cancel','member_new_level') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Loại tin nhắn',
  `value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'điều kiện gửi (Số giờ, số ngày gửi trước)',
  `time_sent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'thời điểm gửi tin nhắn',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên loại tin nhắn',
  `content` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nội dung nhắn tin',
  `is_active` tinyint(1) DEFAULT 0 COMMENT 'Trạng thái tin nhắn',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `actived_by` int(11) DEFAULT NULL COMMENT 'Người active',
  `datetime_actived` datetime DEFAULT NULL COMMENT 'Thời gian active',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `spa_notification_auto_group`;
CREATE TABLE `spa_notification_auto_group` (
  `notification_auto_group_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID tự tăng',
  `notification_auto_group_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên nhóm',
  `is_detail` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Có vào xem chi tiết ko ',
  `action_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'View vào chi tiết',
  `display_sort` int(11) DEFAULT 100 COMMENT 'Sắp xếp vị trí hiển thị',
  `created_at` datetime DEFAULT NULL COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Thời gian cập nhật',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  PRIMARY KEY (`notification_auto_group_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Group cấu hình push notification';


DROP TABLE IF EXISTS `spa_notification_detail`;
CREATE TABLE `spa_notification_detail` (
  `notification_detail_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID notification tu tang',
  `notification_auto_group` int(11) DEFAULT NULL,
  `background` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Noi dung thong bao',
  `action_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên hiển thị của action',
  `action` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'App route khi click vao thong bao',
  `action_params` varchar(512) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Param route',
  `created_at` datetime DEFAULT NULL COMMENT 'Thoi gian tao',
  `created_by` int(11) DEFAULT NULL COMMENT 'Nguoi tao',
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Thoi gian cap nhat',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Nguoi cap nhat',
  PRIMARY KEY (`notification_detail_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Chi tiet notification';


DROP TABLE IF EXISTS `spa_notification_log`;
CREATE TABLE `spa_notification_log` (
  `notification_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID tự tăng',
  `notification_detail_id` bigint(20) DEFAULT NULL COMMENT 'Chi tiet notification',
  `user_id` int(11) NOT NULL COMMENT 'ID user',
  `notification_avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Avatar của thông báo',
  `notification_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Title',
  `notification_message` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nội dung thông báo',
  `is_read` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Tin nhắn đọc chua',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`notification_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Thông báo';


DROP TABLE IF EXISTS `staffs`;
CREATE TABLE `staffs` (
  `staff_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `department_id` int(11) DEFAULT NULL COMMENT 'Id phòng ban',
  `branch_id` int(11) DEFAULT NULL,
  `staff_title_id` int(11) DEFAULT NULL COMMENT 'Id chức vụ',
  `user_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` char(128) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '70fb6cc4d9ed728fa61892a8e7d085aad3c904dd',
  `salt` char(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '900150983cd24fb0d6963f7d28e17f72',
  `full_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Họ và Tên',
  `birthday` datetime DEFAULT NULL COMMENT 'Ngày sinh',
  `gender` enum('male','female','other') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'giới tính',
  `phone1` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'số điện thoại1',
  `phone2` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'số điện thoại2',
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'email1',
  `facebook` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'facebook',
  `date_last_login` datetime DEFAULT NULL COMMENT 'last login',
  `is_admin` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'Là admin',
  `is_actived` tinyint(4) DEFAULT 0 COMMENT 'đã in acive',
  `is_deleted` tinyint(4) DEFAULT 0 COMMENT 'đã xoá',
  `staff_avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'link hình avatar',
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Dia chi',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `remember_token` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_master` tinyint(4) DEFAULT 0 COMMENT 'Là tài khoản ẩn không hiển thị trong trang tài khoản . Mặc định là 0',
  `staff_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã nhân viên',
  `salary` decimal(16,3) DEFAULT 0.000 COMMENT 'Lương cứng',
  `subsidize` decimal(16,3) DEFAULT 0.000 COMMENT 'Trợ cấp',
  `commission_rate` decimal(16,3) DEFAULT 0.000 COMMENT 'Tỉ lệ hoa hồng nv',
  `password_reset` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_password_reset` datetime DEFAULT NULL,
  `staff_type` enum('probationers','staff') COLLATE utf8mb4_unicode_ci DEFAULT 'staff' COMMENT 'probationers : thử việc, official : chính thức',
  `password_chat` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT '123456' COMMENT 'Mật khẩu chat',
  `is_allotment` tinyint(4) DEFAULT NULL COMMENT 'Trạng thái phân bổ - 0 : Chưa phân bổ, 1: Đã phân bổ	',
  PRIMARY KEY (`staff_id`) USING BTREE,
  UNIQUE KEY `user_name` (`user_name`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `staff_commission_logs`;
CREATE TABLE `staff_commission_logs` (
  `staff_commission_log_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_commission_id` int(11) NOT NULL,
  `action_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'edit' COMMENT 'edit: chỉnh sửa, delete: xoá',
  `staff_id` int(11) DEFAULT NULL COMMENT 'Nhân viên được hoa hồng trước khi thay đổi',
  `staff_money` decimal(16,3) DEFAULT NULL COMMENT 'Số tiền nhân viên được nhận trước khi thay đổi',
  `content` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nội dung',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) NOT NULL COMMENT 'Người thay đổi',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `updated_by` int(11) NOT NULL,
  PRIMARY KEY (`staff_commission_log_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `staff_device`;
CREATE TABLE `staff_device` (
  `staff_device_id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_id` int(11) NOT NULL COMMENT 'id của user',
  `imei` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'imei thiết bị',
  `model` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Model thiết bị',
  `platform` enum('android','ios','other') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Flatform',
  `os_version` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'hệ điều hành',
  `app_version` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Phiên bản app',
  `token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'token bắn notification',
  `date_created` datetime DEFAULT NULL COMMENT 'ngày tạo',
  `last_access` datetime DEFAULT NULL COMMENT 'lần truy cập gần nhất',
  `date_modified` datetime DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'ngày cập nhật ',
  `modified_by` int(11) DEFAULT NULL COMMENT 'người cập nhật',
  `created_by` int(11) DEFAULT NULL COMMENT 'người tạo',
  `is_actived` tinyint(1) DEFAULT 1,
  `is_deleted` tinyint(1) DEFAULT 0,
  `endpoint_arn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'endpoint push amazon',
  PRIMARY KEY (`staff_device_id`) USING BTREE,
  KEY `customer_id_is_actived_is_deleted` (`staff_id`,`is_actived`,`is_deleted`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='danh sách thiết bị theo nhân viên';


DROP TABLE IF EXISTS `staff_email_log`;
CREATE TABLE `staff_email_log` (
  `staff_email_log_id` int(11) NOT NULL AUTO_INCREMENT,
  `email_type` varchar(255) DEFAULT NULL COMMENT 'Loại email',
  `email_subject` varchar(255) DEFAULT NULL COMMENT 'Tiêu đề email',
  `email_from` varchar(255) DEFAULT NULL COMMENT 'Email gửi',
  `email_to` varchar(255) DEFAULT NULL COMMENT 'Email nhận',
  `email_cc` varchar(255) DEFAULT NULL COMMENT 'Email được cc, chuỗi email',
  `email_params` text DEFAULT NULL COMMENT 'Param cho email',
  `is_error` tinyint(1) DEFAULT 0,
  `error_description` varchar(255) DEFAULT NULL COMMENT 'Nội dung lỗi',
  `is_run` tinyint(1) DEFAULT 0 COMMENT 'Kiểm tra email đã gửi',
  `run_at` datetime DEFAULT NULL COMMENT 'Thời gian gửi',
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`staff_email_log_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `staff_holiday`;
CREATE TABLE `staff_holiday` (
  `staff_holiday_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `staff_holiday_title` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `staff_holiday_start_date` date DEFAULT NULL,
  `staff_holiday_end_date` date DEFAULT NULL,
  `staff_holiday_number` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`staff_holiday_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `staff_notification`;
CREATE TABLE `staff_notification` (
  `staff_notification_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID tự tăng',
  `staff_notification_detail_id` bigint(20) DEFAULT NULL COMMENT 'Chi tiet notification',
  `user_id` int(11) NOT NULL COMMENT 'ID user',
  `notification_avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Avatar của thông báo',
  `notification_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Title',
  `notification_message` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nội dung thông báo',
  `is_read` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Tin nhắn đọc chua',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  `is_new` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0: mới 1: cũ',
  `staff_id` int(11) DEFAULT NULL COMMENT 'Id nhân viên',
  `branch_id` int(11) DEFAULT NULL COMMENT 'Id chi nhánh',
  PRIMARY KEY (`staff_notification_id`) USING BTREE,
  KEY `staff_notification_detail_id` (`staff_notification_detail_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Thông báo';


DROP TABLE IF EXISTS `staff_notification_detail`;
CREATE TABLE `staff_notification_detail` (
  `staff_notification_detail_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID notification tu tang',
  `tenant_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ID tenant. Neu notification cua mystore thi nul',
  `background` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Noi dung thong bao',
  `action_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên hiển thị của action',
  `action` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'App route khi click vao thong bao',
  `action_params` varchar(512) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Param route',
  `is_brand` tinyint(1) DEFAULT 0 COMMENT '0 nếu ở backoffice, 1 nếu gửi từ brandportal',
  `created_at` datetime DEFAULT NULL COMMENT 'Thoi gian tao',
  `created_by` int(11) DEFAULT NULL COMMENT 'Nguoi tao',
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Thoi gian cap nhat',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Nguoi cap nhat',
  PRIMARY KEY (`staff_notification_detail_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Chi tiet notification';


DROP TABLE IF EXISTS `staff_notification_template_auto`;
CREATE TABLE `staff_notification_template_auto` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID tự tăng',
  `key` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Key đùng để xác định notification',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tiêu đề',
  `message` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nội dung tin nhắn',
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Hình của notification',
  `has_detail` tinyint(1) DEFAULT 0 COMMENT 'Có trang chi tiết không',
  `detail_background` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Background detail',
  `detail_content` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nội dung detail',
  `detail_action_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên button tương tác',
  `detail_action` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Action khi click ở app',
  `detail_action_params` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Param bổ sung',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `key` (`key`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Template cấu hình nội dung gửi Notification';


DROP TABLE IF EXISTS `staff_salary`;
CREATE TABLE `staff_salary` (
  `staff_salary_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `staff_salary_type_code` enum('shift','monthly','hourly') COLLATE utf8mb4_unicode_ci DEFAULT 'shift' COMMENT 'loại lương',
  `staff_salary_pay_period_code` enum('pay_month','pay_week') COLLATE utf8mb4_unicode_ci DEFAULT 'pay_week',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `staff_salary_days` int(11) DEFAULT NULL COMMENT 'Ngày',
  `staff_salary_months` int(11) DEFAULT NULL COMMENT 'Tháng',
  `staff_salary_years` int(11) DEFAULT NULL COMMENT 'Năm',
  `staff_salary_weeks` int(11) DEFAULT NULL COMMENT 'Tuần',
  `staff_salary_status` tinyint(4) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`staff_salary_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `staff_salary_allowance`;
CREATE TABLE `staff_salary_allowance` (
  `staff_salary_allowance_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `staff_id` int(11) DEFAULT NULL,
  `salary_allowance_id` int(11) DEFAULT NULL,
  `staff_salary_allowance_num` decimal(16,2) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`staff_salary_allowance_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `staff_salary_attribute`;
CREATE TABLE `staff_salary_attribute` (
  `staff_salary_attribute_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `staff_salary_attribute_code` enum('salary_weekday','salary_sarturday','salary_sunday','salary_holiday','salary_contract','salary_monthly') COLLATE utf8mb4_unicode_ci DEFAULT 'salary_weekday',
  `staff_salary_attribute_value` decimal(16,2) DEFAULT NULL,
  `staff_salary_attribute_type` enum('money','percent') COLLATE utf8mb4_unicode_ci DEFAULT 'money',
  `staff_id` int(11) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`staff_salary_attribute_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `staff_salary_bonus_minus`;
CREATE TABLE `staff_salary_bonus_minus` (
  `staff_salary_bonus_minus_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `staff_id` int(11) DEFAULT NULL,
  `salary_bonus_minus_id` int(11) DEFAULT NULL,
  `staff_salary_bonus_minus_num` float DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`staff_salary_bonus_minus_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `staff_salary_config`;
CREATE TABLE `staff_salary_config` (
  `staff_salary_config_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `staff_id` int(11) DEFAULT NULL,
  `staff_salary_type_code` enum('shift','monthly','hourly') COLLATE utf8mb4_unicode_ci DEFAULT 'shift',
  `staff_salary_pay_period_code` enum('pay_month','pay_week') COLLATE utf8mb4_unicode_ci DEFAULT 'pay_month',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`staff_salary_config_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `staff_salary_detail`;
CREATE TABLE `staff_salary_detail` (
  `staff_salary_detail_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `staff_salary_id` int(11) DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `staff_salary_type_code` enum('shift','monthly','hourly') COLLATE utf8mb4_unicode_ci DEFAULT 'shift',
  `staff_salary_pay_period_code` enum('pay_month','pay_week') COLLATE utf8mb4_unicode_ci DEFAULT 'pay_week',
  `staff_salary_overtime` decimal(10,2) DEFAULT NULL,
  `staff_salary_bonus` decimal(10,2) DEFAULT NULL,
  `staff_salary_allowance` decimal(10,2) DEFAULT NULL,
  `staff_salary_main` decimal(10,2) DEFAULT NULL,
  `staff_salary_received` decimal(10,2) DEFAULT NULL,
  `staff_salary_minus` decimal(10,2) DEFAULT NULL,
  `staff_salary_status` tinyint(4) DEFAULT 0 COMMENT '0 : chưa duyệt 1: đã duyệt',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`staff_salary_detail_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `staff_salary_overtime`;
CREATE TABLE `staff_salary_overtime` (
  `staff_salary_overtime_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `staff_id` int(11) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `staff_salary_overtime_weekday` decimal(10,2) DEFAULT NULL,
  `staff_salary_overtime_holiday` decimal(10,2) DEFAULT NULL,
  `staff_salary_overtime_holiday_type` enum('money','percent') COLLATE utf8mb4_unicode_ci DEFAULT 'money',
  `staff_salary_overtime_saturday` decimal(10,2) DEFAULT NULL,
  `staff_salary_overtime_saturday_type` enum('money','percent') COLLATE utf8mb4_unicode_ci DEFAULT 'money',
  `staff_salary_overtime_sunday` decimal(10,2) DEFAULT NULL,
  `staff_salary_overtime_sunday_type` enum('money','percent') COLLATE utf8mb4_unicode_ci DEFAULT 'money',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`staff_salary_overtime_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `staff_salary_pay_period`;
CREATE TABLE `staff_salary_pay_period` (
  `staff_salary_pay_period_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `staff_salary_pay_period_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `staff_salary_pay_period_code` enum('pay_month','pay_week') COLLATE utf8mb4_unicode_ci DEFAULT 'pay_month',
  PRIMARY KEY (`staff_salary_pay_period_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `staff_salary_type`;
CREATE TABLE `staff_salary_type` (
  `staff_salary_type_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `staff_salary_type_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `staff_salary_type_code` enum('shift','monthly','hourly') COLLATE utf8mb4_unicode_ci DEFAULT 'shift',
  PRIMARY KEY (`staff_salary_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `staff_title`;
CREATE TABLE `staff_title` (
  `staff_title_id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_title_name` varchar(191) CHARACTER SET utf8mb4 NOT NULL COMMENT 'Tên chức vụ',
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `staff_title_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã chức vụ',
  `staff_title_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mô tả',
  `is_system` tinyint(4) DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Trạng thái',
  `is_delete` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT current_timestamp() COMMENT 'Thời gian cập nhật',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  PRIMARY KEY (`staff_title_id`) USING BTREE,
  UNIQUE KEY `staff_title_name` (`staff_title_name`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `statistic_customer`;
CREATE TABLE `statistic_customer` (
  `statistic_customer_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_new` int(11) NOT NULL DEFAULT 0 COMMENT 'Số lượng khách hàng mới',
  `customer_old` int(11) NOT NULL DEFAULT 0 COMMENT 'Số lượng khách hàng cũ',
  `customer_haunt` int(11) NOT NULL DEFAULT 0 COMMENT 'Số lượng khách hàng vãng lai',
  `branch_id` int(11) DEFAULT NULL COMMENT 'Chi nhánh',
  `gender` enum('male','female','other') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Giới tính',
  `customer_source_id` int(11) DEFAULT NULL COMMENT 'Nguồn khách hàng',
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`statistic_customer_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `stel_service`;
CREATE TABLE `stel_service` (
  `stel_service_id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `auth` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `voice_otp_from` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`stel_service_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Bảng cấu hình South Telecom Voice Service';


DROP TABLE IF EXISTS `suppliers`;
CREATE TABLE `suppliers` (
  `supplier_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `supplier_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT 0,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'đia chỉ nhà cung cấp',
  `contact_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'tên nguời đại diện',
  `contact_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'chức vụ người đại diện',
  `contact_phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'số dt người đại diện',
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`supplier_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `survey`;
CREATE TABLE `survey` (
  `survey_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID tự tăng',
  `survey_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên khảo sát',
  `survey_code` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mã khảo sát',
  `survey_description` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mô tả khảo sát',
  `survey_banner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Link hình banner hiển thị ở app',
  `is_exec_time` tinyint(1) DEFAULT NULL COMMENT 'Thời gian hiệu lực chương trình:0 - Không xác định1 - Giới hạn thời gian',
  `start_date` datetime DEFAULT NULL COMMENT 'Ngày bắt đầu khảo sát. Tính theo giờ luôn',
  `end_date` datetime DEFAULT NULL COMMENT 'Ngày kết thúc khảo sát. Tính theo giờ luôn',
  `close_date` datetime DEFAULT NULL COMMENT 'Ngày đóng chương trình',
  `frequency` enum('daily','weekly','monthly') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tần suất thực hiện khảo sát',
  `frequency_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tần suất thực hiện khảo sát: 1. Hàng tuần - Lặp lại vào thứ: 0,1,2,3,4,5,62. Hàng tháng - Lặp lại vào tháng: 1-12',
  `is_limit_exec_time` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Có giới hạn thời gian thực hiện khảo sát không? 0: Không giới hạn; 1: gới hạn',
  `exec_time_from` time DEFAULT NULL COMMENT 'Thời gian thực hiện khảo sát trong ngày. Phải thiết lập khi is_limit_exec_time = 1',
  `exec_time_to` time DEFAULT NULL COMMENT 'Thời gian thực hiện khảo sát trong ngày. Phải thiết lập khi is_limit_exec_time = 1',
  `frequency_monthly_type` enum('day_in_month','day_in_week') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Loại nếu là hàng thángday_in_month: Ngày trong thángday_in_week: Ngày trong tuần',
  `day_in_monthly` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ngày trong tháng:Nếu giá trị là ngày trong tháng: value 1,2,3 ... n, -1 là ngày cuối cùng',
  `day_in_week` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Lặp lại vào tuầnNếu giá trị là ngày trong tuần 0,1,2,3, -1 nếu là tuần cuối',
  `day_in_week_repeat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Lặp lại vào thứ:Nếu giá trị là ngày trong tuần lặp lại vào thứ 0,1,2,3,4,5,6',
  `period_in_date_type` enum('unlimited','limited') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Thời gian thực hiện trong ngày',
  `period_in_date_start` varchar(8) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Từ giờ',
  `period_in_date_end` varchar(8) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Đến giờ',
  `max_times` int(11) DEFAULT 0 COMMENT 'Giới hạn số lượng khảo sát của toàn bộ branch. Nếu đủ số lượng này thì sẽ không tiếp nhận khảo sát nữa. 0: Không giới hạn',
  `branch_max_times_per_day` int(11) DEFAULT 0 COMMENT 'Số lần khảo sát tối đa của mỗi branch trong ngày. 0: không giới hạn',
  `branch_max_times` int(11) NOT NULL DEFAULT 0 COMMENT 'Số lần khảo sát tối đa trên mỗi branch. 0: Không giới hạn',
  `allow_all_branch` tinyint(1) DEFAULT NULL COMMENT '1: Áp dụng cho tất cả branch. 0: Chỉ áp dụng cho một số branch',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Trạng thái kích hoạt. 1: Hiển thị trên app; 0: Không hiển thị',
  `status` char(1) COLLATE utf8mb4_unicode_ci DEFAULT 'N' COMMENT 'Trạng thái\\r\\nN: Bản nháp\\r\\nR : Đã duyệt\\r\\nC : Kết thúc\\r\\nD : Từ chối',
  `is_delete` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Đánh dấu xóa',
  `created_at` datetime DEFAULT NULL COMMENT 'Thời gian tạo',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Thời gian cập nhật',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  PRIMARY KEY (`survey_id`) USING BTREE,
  KEY `survey_code` (`survey_code`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT COMMENT='Danh sách chương trình khảo sát';


DROP TABLE IF EXISTS `survey_answer`;
CREATE TABLE `survey_answer` (
  `survey_answer_id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID tự tăng',
  `branch_id` bigint(20) unsigned NOT NULL COMMENT 'ID branch',
  `user_id` int(10) unsigned NOT NULL COMMENT 'ID User',
  `user_type` enum('customer','staff') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'staff',
  `survey_id` int(10) unsigned NOT NULL COMMENT 'ID khảo sát',
  `survey_answer_status` enum('in-process','done') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Trạng thái khảo sát',
  `total_questions` int(11) DEFAULT NULL COMMENT 'Tổng số câu hỏi lúc khảo sát',
  `num_questions_completed` int(11) DEFAULT NULL COMMENT 'Số câu hỏi đã hoàn thành',
  `accumulation_point` int(11) DEFAULT NULL COMMENT 'Điểm tích lũy khi hoàn thành lúc hoàn thành khảo sát',
  `finished_at` datetime DEFAULT NULL COMMENT 'Thời gian hoàn thành khảo sát',
  `created_at` datetime NOT NULL COMMENT 'Thời gian thực hiện khảo sát',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Thời gian cập nhật cuối',
  PRIMARY KEY (`survey_answer_id`) USING BTREE,
  KEY `survey_id` (`survey_id`) USING BTREE,
  KEY `branch_id` (`branch_id`) USING BTREE,
  KEY `survey_id_branch_id` (`survey_id`,`branch_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT COMMENT='Trả lời khảo sát của branch';


DROP TABLE IF EXISTS `survey_answer_question`;
CREATE TABLE `survey_answer_question` (
  `answer_question_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID Tự tăng',
  `survey_id` bigint(20) unsigned NOT NULL COMMENT 'ID Khảo sát, để dễ query',
  `survey_answer_id` bigint(20) unsigned NOT NULL COMMENT 'Phiên trả lời khảo sát',
  `branch_id` bigint(20) unsigned DEFAULT NULL COMMENT 'ID Branch',
  `survey_question_id` bigint(20) unsigned NOT NULL COMMENT 'ID Câu hỏi',
  `survey_question_choice_id` bigint(20) unsigned DEFAULT NULL COMMENT 'Đáp án của câu hỏi (multi choice)',
  `answer_value` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Đáp án do user nhập (văn bản, tự luận,...)',
  `created_at` datetime DEFAULT NULL COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Thời gian cập nhật',
  PRIMARY KEY (`answer_question_id`) USING BTREE,
  KEY `survey_answer_id` (`survey_answer_id`) USING BTREE,
  KEY `survey_id_branch_id` (`survey_id`,`branch_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT COMMENT='Nội dung trả lời của từng câu hỏi';


DROP TABLE IF EXISTS `survey_block`;
CREATE TABLE `survey_block` (
  `survey_block_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID Tự tăng',
  `survey_id` int(10) unsigned NOT NULL COMMENT 'ID Survey',
  `survey_block_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên block',
  `survey_block_position` int(11) NOT NULL DEFAULT 1 COMMENT 'Vị trí hiển thị. sắp xếp theo thứ tự tăng dần (ASC)',
  `created_at` datetime DEFAULT NULL COMMENT 'Thời gian tạo',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian cập nhật',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  PRIMARY KEY (`survey_block_id`) USING BTREE,
  KEY `survey_id` (`survey_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT COMMENT='Block câu hỏi';


DROP TABLE IF EXISTS `survey_branch`;
CREATE TABLE `survey_branch` (
  `survey_branch_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID tự tăng',
  `survey_id` int(11) NOT NULL COMMENT 'ID chương trình khảo sát',
  `branch_id` bigint(20) NOT NULL COMMENT 'ID branch',
  `target_user` enum('customer','staff') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL COMMENT 'Thời gian tạo',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Thời gian cập nhật',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  PRIMARY KEY (`survey_branch_id`) USING BTREE,
  UNIQUE KEY `branch_id_survey_id` (`branch_id`,`survey_id`) USING BTREE,
  KEY `survey_id` (`survey_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT COMMENT='Danh sách branch áp dụng';


DROP TABLE IF EXISTS `survey_question`;
CREATE TABLE `survey_question` (
  `survey_question_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID tự tăng',
  `parent_id` bigint(20) unsigned DEFAULT NULL COMMENT 'ID câu hỏi cha. Số cấp tùy loại câu hỏi',
  `survey_id` int(10) unsigned NOT NULL COMMENT 'ID khảo sát',
  `survey_block_id` int(11) DEFAULT NULL COMMENT 'ID Block',
  `survey_question_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tiêu đề câu hỏi',
  `survey_question_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mô tả cho câu hỏi',
  `survey_question_type` enum('single_choice','multi_choice','matrix_single','matrix_multi','matrix_entry','text','photo_tracking','page_picture','page_text','description') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Loại câu hỏi:\r\nsingle_choice: Trắc nghiệm - Chỉ chọn được 1 đáp án\r\nmulti_choice: Trắc nghiệm - Có thể chọn nhiều đáp án\r\nmatrix_single: Bảng ma trận - Chọn 1 đáp án\r\nmatrix_multi: Bảng ma trận - Chọn nhiều đáp án\r\nmatrix_entry: Nhập đáp án\r\ntext: Tự luận\r\nphoto_tracking:\r\npage_picture: Hình ảnh minh họa\r\npage_text: Văn bản mô tả\r\ndescription:',
  `survey_question_config` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Cấu hình câu hỏi',
  `is_required` int(11) DEFAULT 1 COMMENT 'Bắt buộc trả lời',
  `is_combine_question` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0: câu hỏi bình thường (không có câu hỏi con); 1: Đây là câu hỏi kết hợp. Có trường này thì sẽ có những câu hỏi parent_id là id câu hỏi này',
  `survey_question_position` int(11) DEFAULT 100 COMMENT 'Vị trí hiển thị. sắp xếp theo thứ tự tăng dần (ASC)',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`survey_question_id`) USING BTREE,
  KEY `survey_block_id` (`survey_block_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT COMMENT='Danh sách câu hỏi của bảng khảo sát';


DROP TABLE IF EXISTS `survey_question_choice`;
CREATE TABLE `survey_question_choice` (
  `survey_question_choice_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID tự tăng',
  `survey_question_id` bigint(20) unsigned NOT NULL COMMENT 'ID câu hỏi',
  `survey_id` bigint(20) unsigned NOT NULL COMMENT 'ID Bảng khảo sát, mục đích cho dễ query',
  `survey_question_choice_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nội dung hiển thị',
  `survey_question_choice_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Giá trị của câu hỏi',
  `survey_question_choice_position` int(11) NOT NULL DEFAULT 1 COMMENT 'Vị trí hiển thị. Sắp xếp theo thứ tự tăng dần (ASC)',
  `survey_question_choice_config` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Cấu hình đáp án',
  PRIMARY KEY (`survey_question_choice_id`) USING BTREE,
  KEY `survey_question_id` (`survey_question_id`) USING BTREE,
  KEY `survey_id` (`survey_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT COMMENT='Danh sách câu trả lời của câu hỏi';


DROP TABLE IF EXISTS `survey_report_export`;
CREATE TABLE `survey_report_export` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `export_id` int(11) NOT NULL,
  `company_branch_code` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_branch_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ship_to_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ship_to_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `survey_question_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `survey_question` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `answer_value` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT COMMENT='Lưu danh sách báo cáo khảo sát để export';


DROP TABLE IF EXISTS `survey_template_notification`;
CREATE TABLE `survey_template_notification` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID tự tăng',
  `survey_id` int(11) NOT NULL COMMENT 'ID khảo sát',
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tiêu đề',
  `message` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nội dung tin nhắn',
  `avatar` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Hình của notification',
  `has_detail` tinyint(1) DEFAULT 0 COMMENT 'Có trang chi tiết không',
  `detail_background` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Background detail',
  `detail_content` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nội dung detail',
  `detail_action_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên button tương tác',
  `detail_action` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Action khi click ở app',
  `detail_action_params` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Param bổ sung',
  `notification_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'default' COMMENT 'Loại thông báo. Dùng để ở app bắt trigger',
  `params_show` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT COMMENT='Template cấu hình nội dung gửi notification khảo sát';


DROP TABLE IF EXISTS `ticket`;
CREATE TABLE `ticket` (
  `ticket_id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `localtion_id` int(11) DEFAULT NULL COMMENT 'tỉnh thành phố',
  `ticket_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'loại ticket : D : triển khai,  I : sự cố',
  `ticket_issue_group_id` int(11) DEFAULT NULL COMMENT 'nhóm vấnd dề',
  `ticket_issue_id` int(11) DEFAULT NULL COMMENT 'vấn đề',
  `issule_level` int(11) DEFAULT NULL COMMENT 'cấp độ sự cố',
  `priority` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Độ ưu tiên : L : bình thường , H : cao, N : thấp',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'tiêu đề',
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'nội dung',
  `date_issue` datetime DEFAULT NULL COMMENT 'thời gian phát sinh sự cố ',
  `date_estimated` datetime DEFAULT NULL COMMENT 'thời gian dự kiến xử lý hoàn thành ',
  `date_expected` datetime DEFAULT NULL COMMENT 'thời gian bắt buộc hoàn tất',
  `date_finished` datetime DEFAULT NULL COMMENT 'Thời gian hoàn thành ticket',
  `date_request` datetime DEFAULT NULL COMMENT 'thời gian khách hàng yêu cầu',
  `found_by` int(11) DEFAULT NULL COMMENT 'nhân viên phát hiện sự cố ',
  `customer_id` int(11) DEFAULT NULL COMMENT 'khách hàng',
  `customer_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'địa chỉ khách hàng',
  `queue_process_id` int(11) DEFAULT NULL COMMENT 'queue xử lý sự cố',
  `operate_by` int(11) DEFAULT NULL COMMENT 'nhân viên chủ trì ( thuộc queue xử lý sự cố ) ',
  `staff_notification_id` int(11) DEFAULT NULL COMMENT 'nhân viên thông báo',
  `alert_time` datetime DEFAULT NULL COMMENT 'thời gian nâng mức cảnh báo trước đó ',
  `image` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT 'ảnh',
  `ticket_status_id` int(11) DEFAULT 1,
  `platform` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `step_warning` int(11) DEFAULT 0 COMMENT 'Cấp độ cảnh báo sự cố',
  `step_warning_date` datetime DEFAULT NULL COMMENT 'Thời gian ',
  `step_warning_assign` int(11) DEFAULT 0 COMMENT 'Cấp độ cảnh báo assign',
  `step_warning_assign_date` datetime DEFAULT NULL COMMENT 'Thời gian cảnh báo assign',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `count_opened` int(11) DEFAULT 0 COMMENT 'Số lần ticket được mở lại',
  PRIMARY KEY (`ticket_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `ticket_acceptance`;
CREATE TABLE `ticket_acceptance` (
  `ticket_acceptance_id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_acceptance_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ticket_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'tên',
  `sign_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'người ký',
  `sign_date` datetime DEFAULT NULL COMMENT 'ngày ký',
  `sign_date_request` datetime DEFAULT NULL COMMENT 'ngày yêu cầu phải ký',
  `customer_id` int(11) DEFAULT NULL COMMENT 'khách hàng',
  `status` enum('new','cancel','approve') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`ticket_acceptance_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Biên bảng nghiệm thu';


DROP TABLE IF EXISTS `ticket_acceptance_incurred`;
CREATE TABLE `ticket_acceptance_incurred` (
  `ticket_acceptance_incurred_id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_acceptance_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL COMMENT 'vật tư phát sinh',
  `product_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã sản phẩm',
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên sản phẩm',
  `quantity` int(11) DEFAULT NULL COMMENT 'số lượng yêu cầu',
  `money` decimal(16,0) DEFAULT NULL COMMENT 'Thành tiền',
  `unit_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Đơn vị tính',
  `status` enum('new','cancel','approve') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'mới, huỷ, duyệt',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`ticket_acceptance_incurred_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Danh sách vật tư phát sinh của biên bản nghiệm thu';


DROP TABLE IF EXISTS `ticket_action`;
CREATE TABLE `ticket_action` (
  `ticket_action_id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_action_value` int(11) DEFAULT NULL,
  `action_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`ticket_action_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='chuyển thành Quyền xem, đã chỉnh sửa';


DROP TABLE IF EXISTS `ticket_alert`;
CREATE TABLE `ticket_alert` (
  `ticket_alert_id` int(11) NOT NULL AUTO_INCREMENT,
  `time` int(11) DEFAULT NULL COMMENT 'thời gian cảnh báo phút',
  `time_2` int(11) DEFAULT NULL,
  `time_3` int(11) DEFAULT NULL,
  `ticket_role_queue_id` int(11) DEFAULT NULL COMMENT 'vai trò trên queue',
  `template` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'template cảnh báo',
  `params` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'key params cho template',
  `is_noti` tinyint(1) DEFAULT 1 COMMENT 'cảnh báo qua thông báo',
  `is_email` tinyint(1) DEFAULT 1 COMMENT 'cảnh báo qua email',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`ticket_alert_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Cấu hình hệ thống cảnh báo';


DROP TABLE IF EXISTS `ticket_file`;
CREATE TABLE `ticket_file` (
  `ticket_file_id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_id` int(11) NOT NULL,
  `type` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT 'image : ảnh, file : file',
  `group` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT 'acceptance : nếu là biên bản nghiệm thu, image : nếu là hình ảnh, ticket : nếu hình ảnh do khách hàng gửi làm thông tin',
  `path` varchar(255) COLLATE utf8_bin NOT NULL COMMENT 'link ảnh',
  `created_at` datetime DEFAULT NULL,
  `note` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT 'ghi chú',
  `created_by` int(11) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`ticket_file_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='danh sách image cho ticket ';


DROP TABLE IF EXISTS `ticket_history`;
CREATE TABLE `ticket_history` (
  `ticket_process_history_id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_id` int(11) NOT NULL COMMENT 'id ticket',
  `note_vi` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `note_en` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`ticket_process_history_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='danh sách lịch xữ xử lý của 1 ticket';


DROP TABLE IF EXISTS `ticket_issue`;
CREATE TABLE `ticket_issue` (
  `ticket_issue_id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_issue_group_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `level` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'cấp độ sự cố',
  `process_time` float DEFAULT NULL COMMENT 'thời gian xử lý',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'mô tả',
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`ticket_issue_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Danh sách yêu cầu';


DROP TABLE IF EXISTS `ticket_issue_group`;
CREATE TABLE `ticket_issue_group` (
  `ticket_issue_group_id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nhóm yêu cầu : D : triển khai,  I : sự cố',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Cấp độ sự cố',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`ticket_issue_group_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Danh sách nhóm yêu cầu';


DROP TABLE IF EXISTS `ticket_operater`;
CREATE TABLE `ticket_operater` (
  `ticket_operater_id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_id` int(11) DEFAULT NULL COMMENT 'id ticket',
  `name` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT 'tên hiển thị',
  `operate_by` int(11) NOT NULL COMMENT 'nhân viên chủ trì',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`ticket_operater_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='danh sách trạng thái ticket';


DROP TABLE IF EXISTS `ticket_processor`;
CREATE TABLE `ticket_processor` (
  `ticket_processor_id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_id` int(11) DEFAULT NULL COMMENT 'id ticket',
  `name` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT 'tên hiển thị',
  `process_by` int(11) NOT NULL COMMENT 'nhân viên xử lý',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`ticket_processor_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='nhân viên xử lý theo ticket';


DROP TABLE IF EXISTS `ticket_process_history`;
CREATE TABLE `ticket_process_history` (
  `ticket_process_history_id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_id` int(11) NOT NULL,
  `ticket_code` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'SC+dd+mm+yy+(tự tăng số ticket đã dc tạo trong ngày ) ',
  `ticket_status` int(11) NOT NULL COMMENT 'trạng thái ticket ( giá trị value trong ticket_status)',
  `date_issue` datetime NOT NULL COMMENT 'thời gian phát sinh sự cố ',
  `date_estimated` datetime NOT NULL COMMENT 'thời gian dự kiến xử lý hoàn thành ',
  `date_expected` datetime NOT NULL COMMENT 'thời gian bắt buộc hoàn tất',
  `queue_process_id` int(11) DEFAULT NULL COMMENT 'queue xử lý sự cố',
  `operate_by` int(11) DEFAULT NULL COMMENT 'nhân viên chủ trì ( thuộc queue xử lý sự cố ) ',
  `visor_by` int(11) DEFAULT NULL COMMENT 'nhân viên giám sát ( thuộc queue xử lý sự cố ) ',
  `process_by` int(11) DEFAULT NULL COMMENT 'nhân viên xử lý sự cố (thuộc queue xử lý sự cố )',
  `issue_reason_id` int(11) DEFAULT NULL COMMENT 'id nguyên nhân',
  `issue_reason_detail_id` int(11) DEFAULT NULL COMMENT 'id nguyên nhân chi tiết',
  `effect_id` int(11) DEFAULT NULL COMMENT 'id phạm vi ảnh hưởng',
  `platform` enum('mobile','web') COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_modified` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`ticket_process_history_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `ticket_queue`;
CREATE TABLE `ticket_queue` (
  `ticket_queue_id` int(11) NOT NULL AUTO_INCREMENT,
  `queue_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `is_actived` tinyint(1) DEFAULT 1 COMMENT 'trạng thái',
  PRIMARY KEY (`ticket_queue_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Danh sách queue';


DROP TABLE IF EXISTS `ticket_rating`;
CREATE TABLE `ticket_rating` (
  `ticket_rating_id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_id` int(11) DEFAULT NULL,
  `point` int(11) DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`ticket_rating_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Đánh giá ticket';


DROP TABLE IF EXISTS `ticket_refund`;
CREATE TABLE `ticket_refund` (
  `ticket_refund_id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'mã hoàn ứng',
  `staff_id` int(11) DEFAULT NULL COMMENT 'Nhân viên hoàn ứng',
  `approve_id` int(11) DEFAULT NULL COMMENT 'nhân viên duyệt',
  `status` enum('D','W','WF','A','R','C') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'D : bản nháp, W : chờ duyệt, WF : chờ hồ sơ, A : đã duyệt, R : từ chối, C : hoàn tất',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`ticket_refund_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `ticket_refund_file`;
CREATE TABLE `ticket_refund_file` (
  `ticket_refund_file_id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_refund_map_id` int(11) DEFAULT NULL,
  `ticket_id` int(11) DEFAULT NULL,
  `path_file` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('refund','acceptance') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`ticket_refund_file_id`) USING BTREE,
  KEY `ticket_id` (`ticket_id`) USING BTREE,
  CONSTRAINT `ticket_refund_file_ibfk_1` FOREIGN KEY (`ticket_id`) REFERENCES `ticket` (`ticket_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `ticket_refund_item`;
CREATE TABLE `ticket_refund_item` (
  `ticket_refund_item_id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_refund_map_id` int(11) DEFAULT NULL,
  `ticket_id` int(11) DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'A : tạm ứng, I : phát sinh',
  `obj_id` int(11) DEFAULT NULL COMMENT 'nếu type A thì product_id, I thì id vật tư phát sinh',
  `quantity` int(11) DEFAULT NULL COMMENT 'sl hoan ung',
  `money` double(255,0) DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`ticket_refund_item_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `ticket_refund_map`;
CREATE TABLE `ticket_refund_map` (
  `ticket_refund_map_id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_refund_id` int(11) DEFAULT NULL,
  `ticket_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`ticket_refund_map_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Bảng chứa ticket cần hoàn ứng';


DROP TABLE IF EXISTS `ticket_request_group`;
CREATE TABLE `ticket_request_group` (
  `ticket_request_group_id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(255) DEFAULT NULL,
  `type` tinyint(4) DEFAULT NULL COMMENT '(0 Ticket triển khai - 1 Ticket xử lý sự cố',
  `status` tinyint(1) DEFAULT 1,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ticket_request_group_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `ticket_request_material`;
CREATE TABLE `ticket_request_material` (
  `ticket_request_material_id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_request_material_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ticket_id` int(11) DEFAULT NULL,
  `proposer_by` int(11) DEFAULT NULL COMMENT 'người đề xuất',
  `proposer_date` datetime DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL COMMENT 'Người duyệt',
  `approved_date` datetime DEFAULT NULL COMMENT 'Thời gian duyệt',
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('new','cancel','approve') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'new, cancel, approve',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`ticket_request_material_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Danh sách yêu cầu vật tư của ticket';


DROP TABLE IF EXISTS `ticket_request_material_detail`;
CREATE TABLE `ticket_request_material_detail` (
  `ticket_request_material_detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_request_material_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL COMMENT 'người đề xuất',
  `quantity` int(11) DEFAULT 0 COMMENT 'số lượng yêu cầu',
  `quantity_approve` int(11) DEFAULT 0 COMMENT 'số lượng duyệt',
  `quantity_return` int(11) DEFAULT 0 COMMENT 'số lượng hoàn ứng',
  `quantity_reality` int(11) DEFAULT 0 COMMENT 'Số lượng thực tế',
  `status` enum('new','cancel','approve') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'mới, huỷ, duyệt',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `warehouse_id` int(11) DEFAULT 0 COMMENT 'id kho',
  `product_inventory_id` int(11) DEFAULT 0 COMMENT 'id sp kho',
  PRIMARY KEY (`ticket_request_material_detail_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Danh sách yêu cầu vật tư của ticket';


DROP TABLE IF EXISTS `ticket_request_type`;
CREATE TABLE `ticket_request_type` (
  `ticket_request_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `is_actived` tinyint(1) NOT NULL,
  `created_at` datetime DEFAULT NULL COMMENT 'Ngày tạo',
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ticket_request_type_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `ticket_role`;
CREATE TABLE `ticket_role` (
  `ticket_role_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_group_id` int(11) DEFAULT NULL COMMENT 'Role group id',
  `role_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_approve_refund` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`ticket_role_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `ticket_role_action_map`;
CREATE TABLE `ticket_role_action_map` (
  `ticket_role_action_map_id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_role_id` int(11) DEFAULT NULL,
  `ticket_action_value` int(11) DEFAULT NULL,
  `allow` tinyint(1) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`ticket_role_action_map_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Bảng map role với quyền ';


DROP TABLE IF EXISTS `ticket_role_queue`;
CREATE TABLE `ticket_role_queue` (
  `ticket_role_queue_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`ticket_role_queue_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Danh sách vai trò trên queue';


DROP TABLE IF EXISTS `ticket_role_status_map`;
CREATE TABLE `ticket_role_status_map` (
  `ticket_role_status_map_id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_role_id` int(11) DEFAULT NULL COMMENT 'role',
  `ticket_status_id` int(11) DEFAULT NULL COMMENT 'trạng thái',
  `allow` tinyint(1) DEFAULT 1 COMMENT 'cho phép 1, ko cho phép 0',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`ticket_role_status_map_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Bảng map role với trạng thái ticket';


DROP TABLE IF EXISTS `ticket_staff_device`;
CREATE TABLE `ticket_staff_device` (
  `ticket_staff_device_id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_id` int(11) NOT NULL COMMENT 'id của user',
  `email` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `imei` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'imei thiết bị',
  `model` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Model thiết bị',
  `platform` enum('android','ios','other') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Flatform',
  `os_version` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'hệ điều hành',
  `app_version` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Phiên bản app',
  `token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'token bắn notification',
  `date_created` datetime DEFAULT NULL COMMENT 'ngày tạo',
  `last_access` datetime DEFAULT NULL COMMENT 'lần truy cập gần nhất',
  `date_modified` datetime DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'ngày cập nhật ',
  `modified_by` int(11) DEFAULT NULL COMMENT 'người cập nhật',
  `created_by` int(11) DEFAULT NULL COMMENT 'người tạo',
  `is_actived` tinyint(1) DEFAULT 1,
  `is_deleted` tinyint(1) DEFAULT 0,
  `endpoint_arn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'endpoint push amazon',
  PRIMARY KEY (`ticket_staff_device_id`) USING BTREE,
  KEY `staff_id_is_actived_is_deleted` (`staff_id`,`is_actived`,`is_deleted`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='danh sách thiết bị theo nhân viên';


DROP TABLE IF EXISTS `ticket_staff_queue`;
CREATE TABLE `ticket_staff_queue` (
  `ticket_staff_queue_id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_id` int(11) DEFAULT NULL COMMENT 'nhan viên',
  `ticket_queue_id` int(11) DEFAULT NULL COMMENT 'queue trực thuộc',
  `ticket_role_queue_id` int(11) DEFAULT NULL COMMENT 'vai trò trên queue',
  `role_id` int(11) DEFAULT NULL COMMENT 'quyền tren ticket',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ticket_staff_queue_id`) USING BTREE,
  KEY `role_id` (`role_id`) USING BTREE,
  CONSTRAINT `ticket_staff_queue_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role_pages` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='PHÂN CÔNG NHÂN VIÊN THEO QUEUE';


DROP TABLE IF EXISTS `ticket_staff_queue_map`;
CREATE TABLE `ticket_staff_queue_map` (
  `ticket_staff_queue_map_id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_staff_queue_id` int(11) DEFAULT NULL COMMENT 'id phân công',
  `ticket_queue_id` int(11) DEFAULT NULL COMMENT 'queue id',
  `created_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`ticket_staff_queue_map_id`) USING BTREE,
  KEY `ticket_staff_queue_id` (`ticket_staff_queue_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='danh sách trạng thái ticket';


DROP TABLE IF EXISTS `ticket_status`;
CREATE TABLE `ticket_status` (
  `ticket_status_id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_status_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`ticket_status_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `timekeeper_logs`;
CREATE TABLE `timekeeper_logs` (
  `timekeeper_log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `staff_id` int(11) NOT NULL,
  `shift_code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date DEFAULT NULL,
  `time_in` time DEFAULT NULL,
  `time_out` time DEFAULT NULL,
  `hour` int(11) DEFAULT NULL,
  `dayhour` int(11) DEFAULT NULL,
  `late_minute` int(11) DEFAULT NULL,
  `early_minute` int(11) DEFAULT NULL,
  `is_overtime` tinyint(1) DEFAULT 0,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`timekeeper_log_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `timekeeper_shifts`;
CREATE TABLE `timekeeper_shifts` (
  `timekeeper_shift_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `staff_id` int(11) NOT NULL,
  `shift_code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `all_day` tinyint(1) DEFAULT 0,
  `d1` tinyint(1) DEFAULT 0,
  `d2` tinyint(1) DEFAULT 0,
  `d3` tinyint(1) DEFAULT 0,
  `d4` tinyint(1) DEFAULT 0,
  `d5` tinyint(1) DEFAULT 0,
  `d6` tinyint(1) DEFAULT 0,
  `d7` tinyint(1) DEFAULT 0,
  `d8` tinyint(1) DEFAULT 0,
  `d9` tinyint(1) DEFAULT 0,
  `d10` tinyint(1) DEFAULT 0,
  `d11` tinyint(1) DEFAULT 0,
  `d12` tinyint(1) DEFAULT 0,
  `d13` tinyint(1) DEFAULT 0,
  `d14` tinyint(1) DEFAULT 0,
  `d15` tinyint(1) DEFAULT 0,
  `d16` tinyint(1) DEFAULT 0,
  `d17` tinyint(1) DEFAULT 0,
  `d18` tinyint(1) DEFAULT 0,
  `d19` tinyint(1) DEFAULT 0,
  `d20` tinyint(1) DEFAULT 0,
  `d21` tinyint(1) DEFAULT 0,
  `d22` tinyint(1) DEFAULT 0,
  `d23` tinyint(1) DEFAULT 0,
  `d24` tinyint(1) DEFAULT 0,
  `d25` tinyint(1) DEFAULT 0,
  `d26` tinyint(1) DEFAULT 0,
  `d27` tinyint(1) DEFAULT 0,
  `d28` tinyint(1) DEFAULT 0,
  `d29` tinyint(1) DEFAULT 0,
  `d30` tinyint(1) DEFAULT 0,
  `d31` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`timekeeper_shift_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `time_insert_reset_rank`;
CREATE TABLE `time_insert_reset_rank` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `value` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Giá trị Insert vào',
  `type` enum('one_month','two_month','three_month','four_month','six_month','one_year') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `time_working`;
CREATE TABLE `time_working` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `eng_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên thứ bằng tiếng anh',
  `vi_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên thứ bằng tiếng việt',
  `is_actived` tinyint(1) DEFAULT 1,
  `start_time` time NOT NULL COMMENT 'Giờ bắt đầu làm việc',
  `end_time` time NOT NULL COMMENT 'Giờ tan ca',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `transports`;
CREATE TABLE `transports` (
  `transport_id` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `transport_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `charge` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `is_deleted` tinyint(4) DEFAULT 0,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người cập nhật',
  `is_system` tinyint(4) DEFAULT 0 COMMENT 'Giá trị từ hệ thống . 1 của hệ thống',
  `transport_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã code',
  `token` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã token theo nhà đối tác giao hàng',
  PRIMARY KEY (`transport_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `units`;
CREATE TABLE `units` (
  `unit_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_standard` tinyint(1) NOT NULL DEFAULT 0,
  `is_actived` tinyint(1) DEFAULT 1,
  `is_deleted` tinyint(1) DEFAULT 0,
  `updated_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`unit_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `unit_conversions`;
CREATE TABLE `unit_conversions` (
  `unit_conversion_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `unit_id` int(11) NOT NULL COMMENT 'đơn vị cần quy đổi',
  `unit_standard` int(11) NOT NULL COMMENT 'đơn vị gốc',
  `conversion_rate` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `is_deleted` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`unit_conversion_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `user_carrier`;
CREATE TABLE `user_carrier` (
  `user_carrier_id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Họ và tên',
  `birthday` datetime DEFAULT NULL COMMENT 'Ngày sinh',
  `gender` enum('male','female','other') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Giới tính',
  `phone` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Số điện thoại',
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_last_visit` datetime DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Địa chỉ',
  `user_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tài khoản',
  `password` char(128) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mật khẩu',
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ảnh đại diện',
  `is_actived` tinyint(4) DEFAULT 1,
  `is_deleted` tinyint(4) DEFAULT 0,
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`user_carrier_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `user_carrier_device`;
CREATE TABLE `user_carrier_device` (
  `user_carrier_device_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_carrier_id` int(11) NOT NULL COMMENT 'id của user giao hàng',
  `imei` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'imei thiết bị',
  `model` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Model thiết bị',
  `platform` enum('android','ios','other') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Flatform',
  `os_version` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'hệ điều hành',
  `app_version` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Phiên bản app',
  `token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'token bắn notification',
  `date_created` datetime DEFAULT NULL COMMENT 'ngày tạo',
  `last_access` datetime DEFAULT NULL COMMENT 'lần truy cập gần nhất',
  `date_modified` datetime DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'ngày cập nhật ',
  `modified_by` int(11) DEFAULT NULL COMMENT 'người cập nhật',
  `created_by` int(11) DEFAULT NULL COMMENT 'người tạo',
  `is_actived` tinyint(1) DEFAULT 1,
  `is_deleted` tinyint(1) DEFAULT 0,
  `endpoint_arn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'endpoint push amazon',
  PRIMARY KEY (`user_carrier_device_id`) USING BTREE,
  KEY `user_carrier_id_is_actived_is_deleted` (`user_carrier_id`,`is_actived`,`is_deleted`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='danh sách thiết bị theo nhân viên giao hàng';


DROP TABLE IF EXISTS `user_contacts`;
CREATE TABLE `user_contacts` (
  `user_contact_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `province_id` int(11) NOT NULL,
  `district_id` int(11) NOT NULL,
  `ward_id` int(11) DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address_full` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_deleted` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`user_contact_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='4';


DROP TABLE IF EXISTS `voice_otp`;
CREATE TABLE `voice_otp` (
  `voice_otp_id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uid` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `smsid` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tenant` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `from` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `to` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `error_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `error_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`voice_otp_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `vouchers`;
CREATE TABLE `vouchers` (
  `voucher_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `voucher_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT 'order' COMMENT 'order: Đơn hàng, ship: Phí vận chuyển',
  `is_all` tinyint(1) DEFAULT NULL,
  `type` enum('sale_percent','sale_cash') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Loại giảm giá: giảm theo phần trăm, giảm tiền trực tiếp',
  `percent` decimal(10,3) DEFAULT NULL COMMENT 'phần trăm giảm',
  `cash` decimal(10,3) DEFAULT NULL COMMENT 'giảm bao nhiêu tiền',
  `max_price` decimal(10,3) DEFAULT NULL COMMENT 'Giá giảm tối đa dành cho giảm theo %',
  `required_price` decimal(10,3) DEFAULT NULL COMMENT 'Yêu cầu giá dịch vụ lớn hơn hoặc bằng (%, cash)',
  `object_type` enum('service_card','product','service','all') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `object_type_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'VD : "all" giảm tất cả  ''1,2,3'' chỉ những object nào có id trong đó mới dc giảm ',
  `expire_date` datetime NOT NULL COMMENT 'Ngày hết hạn',
  `branch_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quota` int(11) DEFAULT NULL COMMENT 'hạn mức sd',
  `total_use` int(11) DEFAULT NULL COMMENT 'số lần đã sd',
  `is_actived` tinyint(4) NOT NULL DEFAULT 1,
  `sale_special` tinyint(1) DEFAULT 0,
  `voucher_img` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Hình ảnh',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mô tả ngắn',
  `detail_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mô tả chi tiết',
  `member_level_apply` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'VD : "all" giảm tất cả  ''1,2,3'' chỉ những member level id nào có id trong đó mới dc áp dụng\r\n',
  `type_using` enum('public','private') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'public: sử dụng tất cả, private: sử dụng nội bộ',
  `is_deleted` tinyint(4) NOT NULL DEFAULT 0,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `voucher_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `point` int(11) NOT NULL DEFAULT 0 COMMENT 'Điểm đổi voucher',
  `customer_group_apply` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Voucher áp dụng cho nhóm khách hàng',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `background_color` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Màu nền',
  `text_color` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Màu chữ',
  `content_color` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Màu nội dung',
  `using_by_guest` tinyint(1) DEFAULT 1 COMMENT 'Được sử dụng?',
  `number_of_using` int(11) DEFAULT NULL COMMENT 'Số lần sử dụng voucher',
  PRIMARY KEY (`voucher_id`) USING BTREE,
  UNIQUE KEY `code` (`code`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `ward`;
CREATE TABLE `ward` (
  `ward_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `district_id` int(11) NOT NULL,
  `ward_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_actived` tinyint(4) DEFAULT 1,
  `is_deleted` tinyint(4) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`ward_id`) USING BTREE,
  KEY `districtid` (`district_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT COMMENT='Phường Xã';


DROP TABLE IF EXISTS `warehouses`;
CREATE TABLE `warehouses` (
  `warehouse_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_id` int(11) NOT NULL,
  `province_id` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '79',
  `district_id` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `ward_id` int(11) DEFAULT NULL COMMENT 'Mã phường xã',
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `is_deleted` tinyint(1) DEFAULT 0,
  `is_retail` tinyint(1) DEFAULT 0,
  `ghn_shop_id` int(11) DEFAULT NULL COMMENT 'Mã shop giao hàng nhanh',
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Số điện thoại',
  PRIMARY KEY (`warehouse_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `warranty_card`;
CREATE TABLE `warranty_card` (
  `warranty_card_id` int(11) NOT NULL AUTO_INCREMENT,
  `warranty_card_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'mã thẻ bảo hành ',
  `customer_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'mã khách hàng',
  `warranty_packed_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'mã gói bảo hành',
  `date_actived` datetime DEFAULT NULL COMMENT 'ngày kích hoạt',
  `date_expired` datetime DEFAULT NULL COMMENT 'ngày hết hạn bảo hành , nếu null là vô thời hạn ',
  `quota` int(11) NOT NULL COMMENT 'hạn mức số lần dc bảo hành , nếu 0 là 0 giới hạn',
  `warranty_percent` decimal(16,3) DEFAULT NULL COMMENT '% bảo hành',
  `warranty_value` decimal(16,3) DEFAULT NULL COMMENT 'giá trị được bảo hành ',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'nội dung bảo hành',
  `object_type` enum('service_card','product','service') COLLATE utf8mb4_unicode_ci NOT NULL,
  `object_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'mã sản phẩm, dịch vụ, thẻ dịch vụ được bảo hành',
  `object_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'mã đối tượng bảo hành',
  `object_price` decimal(16,3) DEFAULT 0.000 COMMENT 'Giá đối tượng bảo hành',
  `object_serial` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'số serial đối tượng bảo hành',
  `object_note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ghi chú',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `status` enum('new','actived','cancel','finish') COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã đơn hàng',
  PRIMARY KEY (`warranty_card_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `warranty_images`;
CREATE TABLE `warranty_images` (
  `warranty_image_id` int(11) NOT NULL AUTO_INCREMENT,
  `warranty_card_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã thẻ dịch vụ đã bán',
  `link` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Link ảnh',
  `is_deleted` tinyint(4) DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`warranty_image_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `warranty_packed`;
CREATE TABLE `warranty_packed` (
  `warranty_packed_id` int(11) NOT NULL AUTO_INCREMENT,
  `packed_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `packed_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'tên gói bảo hành',
  `time_type` enum('day','week','month','year','infinitive') COLLATE utf8mb4_unicode_ci DEFAULT 'day' COMMENT 'loại thời gian bảo hành (ngày, tuần, tháng, năm)',
  `time` int(11) NOT NULL COMMENT 'thời hạn bảo hành ( lưu ngày) tính tháng 30 ngày. Nếu = 0 thì vô hạn ',
  `percent` decimal(16,3) NOT NULL COMMENT '% giá trị bảo hành ',
  `quota` int(11) NOT NULL COMMENT 'số lần được bảo hành. Nếu bằng 0 thì là ko giới hạn',
  `required_price` decimal(16,3) DEFAULT 0.000 COMMENT 'Yêu cầu giá dịch vụ lớn hơn hoặc bằng ',
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_actived` tinyint(1) NOT NULL DEFAULT 1,
  `is_deleted` tinyint(4) NOT NULL DEFAULT 0,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mô tả ngắn',
  `detail_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mô tả chi tiết',
  PRIMARY KEY (`warranty_packed_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `warranty_packed_detail`;
CREATE TABLE `warranty_packed_detail` (
  `warranty_packed_detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `warranty_packed_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'mã gói bảo hành',
  `object_type` enum('product','service','service_card') COLLATE utf8mb4_unicode_ci NOT NULL,
  `object_id` int(11) NOT NULL,
  `object_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`warranty_packed_detail_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `zalo_campaign_follower`;
CREATE TABLE `zalo_campaign_follower` (
  `zalo_campaign_follower_id` int(11) NOT NULL AUTO_INCREMENT,
  `zns_template_id` int(11) DEFAULT NULL,
  `zns_client_id` int(11) DEFAULT NULL,
  `campaign_type` enum('zns','follower','broadcast') COLLATE utf8mb4_unicode_ci DEFAULT 'follower',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên chiến dịch',
  `status` enum('cancel','new','sent') COLLATE utf8mb4_unicode_ci DEFAULT 'new' COMMENT 'Trang thái chiến dịch',
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Slug chiến dịch để check trùng',
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mã chiến dịch',
  `value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Thời gian gửi',
  `cost` float(16,3) DEFAULT NULL COMMENT 'Chi phí cho chiến dịch',
  `is_now` tinyint(1) DEFAULT 0 COMMENT 'Gửi ngay',
  `is_actived` tinyint(1) DEFAULT 1 COMMENT 'Hoạt động',
  `time_sent` datetime DEFAULT NULL,
  `branch_id` int(11) NOT NULL COMMENT 'Id chi nhánh',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Ngươi update',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `params` text CHARACTER SET utf8mb4 DEFAULT NULL,
  PRIMARY KEY (`zalo_campaign_follower_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `zalo_client`;
CREATE TABLE `zalo_client` (
  `zalo_client_id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `tenant` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `oa_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`zalo_client_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `zalo_customer_care`;
CREATE TABLE `zalo_customer_care` (
  `zalo_customer_care_id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(191) DEFAULT NULL,
  `avatar` text DEFAULT NULL,
  `phone_number` varchar(50) DEFAULT NULL,
  `address` varchar(50) DEFAULT NULL,
  `province_id` int(11) DEFAULT NULL,
  `district_id` int(11) DEFAULT NULL,
  `zalo_customer_tag_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `zalo_user_id` varchar(191) DEFAULT NULL,
  `status` enum('follower','unllower') DEFAULT NULL,
  `gender` tinyint(4) DEFAULT 0,
  `user_gender` tinyint(4) DEFAULT 0,
  PRIMARY KEY (`zalo_customer_care_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `zalo_customer_tag`;
CREATE TABLE `zalo_customer_tag` (
  `zalo_customer_tag_id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_name` varchar(191) DEFAULT NULL,
  `color_code` varchar(191) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`zalo_customer_tag_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `zalo_customer_tag_map`;
CREATE TABLE `zalo_customer_tag_map` (
  `zalo_customer_tag_map_id` int(11) NOT NULL AUTO_INCREMENT,
  `zalo_customer_care_id` int(11) DEFAULT NULL,
  `zalo_customer_tag_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`zalo_customer_tag_map_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `zalo_log_follower`;
CREATE TABLE `zalo_log_follower` (
  `zalo_log_follower_id` int(11) NOT NULL AUTO_INCREMENT,
  `zalo_campaign_follower_id` int(11) DEFAULT NULL COMMENT 'Tên chiến dịch',
  `user_id` int(11) DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Số điện thoại',
  `message` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nội dung tin nhắn',
  `params` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('new','sent','error') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Trạng thái gửi tin',
  `error_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'tin nhắn trả ve mã lỗi',
  `error_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'thông tin mã lỗi',
  `type_customer` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'customer' COMMENT 'lead or customer',
  `time_sent_done` datetime DEFAULT NULL COMMENT 'Thời gian gửi xong tin nhắn',
  `object_id` int(11) DEFAULT NULL,
  `template_id` int(11) DEFAULT NULL,
  `object_type` enum('customer','customer_appointment','service_card','order','warranty','lead','delivery') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `time_sent` datetime DEFAULT NULL COMMENT 'Thời gian bắt đầu gửi tin nhắn',
  `deal_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Link với mã deal',
  `msg_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'id api trã về',
  `created_at` datetime DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày tạo',
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  `sent_by` int(11) DEFAULT NULL COMMENT 'Người gửi',
  PRIMARY KEY (`zalo_log_follower_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `zns_campaign`;
CREATE TABLE `zns_campaign` (
  `zns_campaign_id` int(11) NOT NULL AUTO_INCREMENT,
  `zns_template_id` int(11) DEFAULT NULL,
  `zns_client_id` int(11) DEFAULT NULL,
  `campaign_type` enum('zns','follower','broadcast') COLLATE utf8mb4_unicode_ci DEFAULT 'zns',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên chiến dịch',
  `status` enum('cancel','new','sent') COLLATE utf8mb4_unicode_ci DEFAULT 'new' COMMENT 'Trang thái chiến dịch',
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Slug chiến dịch để check trùng',
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mã chiến dịch',
  `value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Thời gian gửi',
  `cost` float(16,3) DEFAULT NULL COMMENT 'Chi phí cho chiến dịch',
  `is_now` tinyint(1) DEFAULT 0 COMMENT 'Gửi ngay',
  `is_actived` tinyint(1) DEFAULT 1 COMMENT 'Hoạt động',
  `time_sent` datetime DEFAULT NULL,
  `branch_id` int(11) NOT NULL COMMENT 'Id chi nhánh',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Ngươi update',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `params` text CHARACTER SET utf8mb4 DEFAULT NULL,
  PRIMARY KEY (`zns_campaign_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `zns_client`;
CREATE TABLE `zns_client` (
  `zns_client_id` int(11) NOT NULL AUTO_INCREMENT,
  `oa_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `token` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`zns_client_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `zns_list_params`;
CREATE TABLE `zns_list_params` (
  `zns_list_params_id` int(11) NOT NULL AUTO_INCREMENT,
  `zns_template_id` int(11) DEFAULT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `required` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `max_length` int(11) DEFAULT NULL,
  `min_length` int(11) DEFAULT NULL,
  `accept_null` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`zns_list_params_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `zns_log`;
CREATE TABLE `zns_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zns_campaign_id` int(11) DEFAULT NULL COMMENT 'Tên chiến dịch',
  `user_id` int(11) DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Số điện thoại',
  `message` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nội dung tin nhắn',
  `params` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('new','sent','error') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Trạng thái gửi tin',
  `error_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'tin nhắn trả ve mã lỗi',
  `error_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'thông tin mã lỗi',
  `type_customer` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'customer' COMMENT 'lead or customer',
  `time_sent_done` datetime DEFAULT NULL COMMENT 'Thời gian gửi xong tin nhắn',
  `object_id` int(11) DEFAULT NULL,
  `template_id` int(11) DEFAULT NULL,
  `object_type` enum('customer','customer_appointment','service_card','order','warranty','lead','delivery') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `time_sent` datetime DEFAULT NULL COMMENT 'Thời gian bắt đầu gửi tin nhắn',
  `deal_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Link với mã deal',
  `msg_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'id api trã về',
  `created_at` datetime DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày tạo',
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  `sent_by` int(11) DEFAULT NULL COMMENT 'Người gửi',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `zns_template`;
CREATE TABLE `zns_template` (
  `zns_template_id` int(11) NOT NULL AUTO_INCREMENT,
  `template_id` int(11) DEFAULT NULL,
  `template_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('zns','follower','broadcast') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'status = 1: Lấy các template có trạng thái Enable.\nstatus = 2: Lấy các template có trạng thái Pending review.\nstatus = 3: Lấy các template có trạng thái Reject.\nstatus = 4: Lấy các template có trạng thái Disable.\nstatus = 5: Lấy các template có trạng thái Delete.',
  `price` double(18,2) DEFAULT NULL COMMENT 'giá',
  `preview` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'link preview',
  `template_tag` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'OTP – OTP\nIN_TRANSACTION – Xác nhận/Cập nhật thông tin giao dịch\nPOST_TRANSACTION – Thông báo hoàn tất giao dịch và hỗ trợ có liên quan\nACCOUNT_UPDATE – Cập nhật thông tin tài khoản\nGENERAL_UPDATE – Cập nhật thay đổi về sản phẩm, dịch vụ',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `is_trigger_config` tinyint(1) unsigned DEFAULT 0,
  `message` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nội dung tin nhắn',
  `image` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'link ảnh template',
  `image_title` varchar(2056) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'tiêu đề template',
  `file` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'link file',
  `file_title` varchar(2056) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'tiêu đề file',
  `type_template_follower` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'loại template cho follower\r\n\r\n0- Gửi thông báo văn bản\r\n1- Gửi thông báo theo mẫu đính kèm ảnh\r\n2- Gửi thông báo theo mẫu đính kèm danh sách\r\n3- Gửi thông báo theo mẫu đính kèm file',
  `title_show` varchar(100) CHARACTER SET utf8mb4 DEFAULT NULL,
  `sub_title` text CHARACTER SET utf8mb4 DEFAULT NULL,
  `attachment_id` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `token_upload_file` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link_image` varchar(2056) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`zns_template_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `zns_template_button`;
CREATE TABLE `zns_template_button` (
  `zns_template_button_id` int(11) NOT NULL AUTO_INCREMENT,
  `zns_template_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `content` varchar(255) DEFAULT NULL,
  `phone_number` varchar(50) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `type_button` enum('1','2','3') DEFAULT NULL COMMENT '1: đến trang web khác,2:Gọi điện,3:gửi tin nhắn',
  PRIMARY KEY (`zns_template_button_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `zns_trigger_config`;
CREATE TABLE `zns_trigger_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zns_template_id` int(11) DEFAULT NULL,
  `key` enum('new_customer','order_success','order_waiting','order_thanks','membership','order_cancle','bonus_points','use_points','otp','coupon_nearly_expired','birthday','new_appointment','cancel_appointment','remind_appointment','service_card_nearly_expired','service_card_over_number_used','service_card_expires','delivery_note','confirm_deliveried','active_warranty_card','is_remind_use') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Loại tin nhắn',
  `value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'điều kiện gửi (Số giờ, số ngày gửi trước)',
  `time_sent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'thời điểm gửi tin nhắn',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên loại tin nhắn',
  `hint` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'mô tả',
  `is_active` tinyint(1) DEFAULT 0 COMMENT 'Trạng thái tin nhắn',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `actived_by` int(11) DEFAULT NULL COMMENT 'Người active',
  `datetime_actived` datetime DEFAULT NULL COMMENT 'Thời gian active',
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `check_send` enum('before','after','now') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Gửi trước số giờ , gửi sau số giờ',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `zns_trigger_params`;
CREATE TABLE `zns_trigger_params` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zns_trigger_config_id` int(11) DEFAULT NULL COMMENT 'id bảng zns_trigger_config',
  `params_id` int(11) DEFAULT NULL COMMENT 'Loại tin nhắn',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `zone`;
CREATE TABLE `zone` (
  `zone_id` int(11) NOT NULL AUTO_INCREMENT,
  `country_code` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `zone_name` varchar(35) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`zone_id`) USING BTREE,
  KEY `idx_country_code` (`country_code`) USING BTREE,
  KEY `idx_zone_name` (`zone_name`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 2022-07-12 07:43:48
