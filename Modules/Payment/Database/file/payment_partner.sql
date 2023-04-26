/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 100138
 Source Host           : localhost:3306
 Source Schema         : piospa

 Target Server Type    : MySQL
 Target Server Version : 100138
 File Encoding         : 65001

 Date: 19/10/2020 17:41:32
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for payment_partner
-- ----------------------------
DROP TABLE IF EXISTS `payment_partner`;
CREATE TABLE `payment_partner`  (
  `payment_partner_id` int(11) NOT NULL,
  `partner_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `api_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `api_pass` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `api_endpoint` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`payment_partner_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = 'Thông tin nhà cung cấp dịch vụ giao dịch' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for payment_transaction
-- ----------------------------
DROP TABLE IF EXISTS `payment_transaction`;
CREATE TABLE `payment_transaction`  (
  `TransactionMasterId` int(11) NOT NULL AUTO_INCREMENT,
  `PartnerID` int(11) NULL DEFAULT NULL COMMENT 'ID nhà cung cấp dịch vụ',
  `TransactionType` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'eway' COMMENT 'Loại thanh toán : eway, momo, vnpay....',
  `TransactionID` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'ID Transaction khi thanh toán thành công',
  `AccessCode` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Mã code khi thanh toán eway dùng để check trạng thái',
  `InvoiceReference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Mã đơn hàng dùng để đối soát với bên thứ 3',
  `InvoiceNumber` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Mã đơn hàng',
  `TotalAmount` double(18, 2) NULL DEFAULT NULL,
  `DeviceID` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'IME thiết bị',
  `CustomerIP` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'IP khách hàng',
  `Language` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `RequestTime` datetime(0) NULL DEFAULT NULL COMMENT 'Thời gian gửi yêu cầu thanh toán',
  `ResponseTime` datetime(0) NULL DEFAULT NULL,
  `Status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Message` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Retry` smallint(5) NULL DEFAULT 0 COMMENT 'Số lần gọi lại api để check thanh toán',
  `CreatedAt` datetime(0) NULL DEFAULT NULL,
  `CreatedBy` int(11) NULL DEFAULT NULL,
  `UpdatedAt` datetime(0) NULL DEFAULT NULL,
  `UpdatedBy` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`TransactionMasterId`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = 'Lịch sử giao dịch' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for payment_transaction_customer
-- ----------------------------
DROP TABLE IF EXISTS `payment_transaction_customer`;
CREATE TABLE `payment_transaction_customer`  (
  `TransactionCusId` int(11) NOT NULL AUTO_INCREMENT,
  `TransactionMasterId` int(11) NOT NULL COMMENT 'ID transaction',
  `Reference` int(11) NULL DEFAULT NULL COMMENT 'ID customer để đối chiếu',
  `Title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Chức danh : ông, bà, anh chị ...',
  `FirstName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `LastName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `CompanyName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Tên công ty',
  `JobDescription` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Vị trí (công việc : dev, lead, manager ...)',
  `Street1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'địa chỉ 1',
  `Street2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'địa chỉ 2',
  `City` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Thành phố',
  `State` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Bang',
  `PostalCode` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Country` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Quốc gia',
  `Phone` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Số điện thoại',
  `Mobile` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'di động',
  `Email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'email liên hệ',
  `Url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'url',
  PRIMARY KEY (`TransactionCusId`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = 'Lịch sử khách hàng tiến hành giao dịch' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for payment_transaction_item
-- ----------------------------
DROP TABLE IF EXISTS `payment_transaction_item`;
CREATE TABLE `payment_transaction_item`  (
  `TransactionItemId` int(11) NOT NULL AUTO_INCREMENT,
  `TransactionMasterId` int(11) NULL DEFAULT NULL,
  `Reference` int(11) NULL DEFAULT NULL COMMENT 'ID sản phẩm',
  `SKU` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Mã sản phẩm',
  `Description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Quantity` int(11) NULL DEFAULT NULL,
  `UnitCost` double(18, 2) NULL DEFAULT NULL,
  PRIMARY KEY (`TransactionItemId`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = 'Thông tin sản phẩm trong giao dịch' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for payment_transaction_log
-- ----------------------------
DROP TABLE IF EXISTS `payment_transaction_log`;
CREATE TABLE `payment_transaction_log`  (
  `TransactionLogId` int(11) NOT NULL AUTO_INCREMENT,
  `TransactionMasterId` int(11) NULL DEFAULT NULL,
  `Worker` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'User hoặc Job',
  `Type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Request hoặc Response',
  `Status` tinyint(1) NULL DEFAULT 1,
  `Code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Message` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `DataInput` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `DataOutput` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `CreatedAt` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`TransactionLogId`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = 'Bảng log lịch sử giao dịch' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for payment_transaction_shipping
-- ----------------------------
DROP TABLE IF EXISTS `payment_transaction_shipping`;
CREATE TABLE `payment_transaction_shipping`  (
  `TransactionShippingId` int(11) NOT NULL AUTO_INCREMENT,
  `TransactionMasterId` int(11) NOT NULL COMMENT 'ID transaction',
  `Reference` int(11) NULL DEFAULT NULL COMMENT 'ID Shiping để đối chiếu',
  `FirstName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `LastName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Street1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'địa chỉ 1',
  `Street2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'địa chỉ 2',
  `City` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Thành phố',
  `State` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Bang',
  `PostalCode` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Country` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Quốc gia',
  `Phone` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Số điện thoại',
  `ShippingMethod` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`TransactionShippingId`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = 'Thông tin vận chuyển cho lần giao dịch' ROW_FORMAT = Compact;

SET FOREIGN_KEY_CHECKS = 1;
