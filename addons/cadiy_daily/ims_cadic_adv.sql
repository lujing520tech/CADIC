/*
 Navicat Premium Data Transfer

 Source Server         : 卢静测试专用
 Source Server Type    : MySQL
 Source Server Version : 50648
 Source Host           : localhost:3306
 Source Schema         : shch

 Target Server Type    : MySQL
 Target Server Version : 50648
 File Encoding         : 65001

 Date: 06/11/2020 13:57:25
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for ims_cadic_adv
-- ----------------------------
DROP TABLE IF EXISTS `ims_cadic_adv`;
CREATE TABLE `ims_cadic_adv`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '0',
  `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '0',
  `sort` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `adv_type` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '0',
  `from` int(2) NULL DEFAULT 0,
  `img_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '0',
  `state` int(2) NULL DEFAULT 0,
  `add_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of ims_cadic_adv
-- ----------------------------
INSERT INTO `ims_cadic_adv` VALUES (4, '新品上市', 'www.baidu.com', '1', '1', 0, 'images/5/2020/11/jcNhY4bJ4FOYOgVYkxx4KCJVHjHyxH.jpg', 0, '2020-11-06 13:47:49');

SET FOREIGN_KEY_CHECKS = 1;
