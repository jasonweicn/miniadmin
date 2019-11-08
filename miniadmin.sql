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

 Date: 08/11/2019 11:05:24
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
  `delete_mark` tinyint(1) NOT NULL DEFAULT 0 COMMENT '删除标记',
  `delete_time` datetime DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ma_adminuser
-- ----------------------------
BEGIN;
INSERT INTO `ma_adminuser` VALUES (1, 'admin', 'f6a167d71371a3eba3851f7e761fe6e6', 'EmQErRRc', '超级管理员', '2019-11-08 10:54:22', '2019-01-01 09:00:00', '2019-11-03 12:30:31', 0, '2019-11-02 11:26:31');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
