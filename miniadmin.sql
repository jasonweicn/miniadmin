/*
 Navicat Premium Data Transfer

 Source Server         : xampp-mysql-miniadmin
 Source Server Type    : MySQL
 Source Server Version : 100408
 Source Host           : localhost:3306
 Source Schema         : miniadmin

 Target Server Type    : MySQL
 Target Server Version : 100408
 File Encoding         : 65001

 Date: 20/11/2019 09:15:45
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for ma_adminuser
-- ----------------------------
DROP TABLE IF EXISTS `ma_adminuser`;
CREATE TABLE `ma_adminuser` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `username` varchar(20) NOT NULL COMMENT '用户名',
  `password` varchar(32) NOT NULL COMMENT '密码',
  `encrypt` char(8) NOT NULL COMMENT '密码加密串',
  `nickname` varchar(20) NOT NULL COMMENT '昵称',
  `login_time` datetime DEFAULT NULL COMMENT '登录时间',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime DEFAULT NULL COMMENT '更新时间',
  `disable` tinyint(1) NOT NULL DEFAULT 0 COMMENT '禁用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ma_adminuser
-- ----------------------------
BEGIN;
INSERT INTO `ma_adminuser` VALUES (1, 'admin', 'f6a167d71371a3eba3851f7e761fe6e6', 'EmQErRRc', '超级管理员', '2019-11-18 14:16:14', '2019-01-01 09:00:00', '2019-11-11 18:53:12', 0);
COMMIT;

-- ----------------------------
-- Table structure for ma_adminuser_role
-- ----------------------------
DROP TABLE IF EXISTS `ma_adminuser_role`;
CREATE TABLE `ma_adminuser_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `adminuser_id` int(10) unsigned DEFAULT NULL COMMENT '后台用户ID',
  `role_id` int(10) unsigned DEFAULT NULL COMMENT '角色ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ma_adminuser_role
-- ----------------------------
BEGIN;
INSERT INTO `ma_adminuser_role` VALUES (1, 1, 1);
COMMIT;

-- ----------------------------
-- Table structure for ma_role
-- ----------------------------
DROP TABLE IF EXISTS `ma_role`;
CREATE TABLE `ma_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(20) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ma_role
-- ----------------------------
BEGIN;
INSERT INTO `ma_role` VALUES (1, '超级管理员', NULL);
INSERT INTO `ma_role` VALUES (2, '普通用户', '2019-11-01 09:00:00');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
