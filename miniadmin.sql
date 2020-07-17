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

 Date: 18/07/2020 00:09:39
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ma_adminuser
-- ----------------------------
BEGIN;
INSERT INTO `ma_adminuser` VALUES (1, 'admin', 'f6a167d71371a3eba3851f7e761fe6e6', 'EmQErRRc', '超级管理员', '2020-07-18 00:01:53', '2019-01-01 09:00:00', '2020-07-16 18:09:06', 0);
INSERT INTO `ma_adminuser` VALUES (2, 'demo', '0f8ae977c40f3adccb2d002d3f2be4f0', 'KuByvhWR', '演示账号', '2020-07-18 00:00:54', '2020-02-16 22:20:57', '2020-07-14 18:44:38', 0);
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ma_adminuser_role
-- ----------------------------
BEGIN;
INSERT INTO `ma_adminuser_role` VALUES (1, 1, 1);
INSERT INTO `ma_adminuser_role` VALUES (2, 2, 2);
COMMIT;

-- ----------------------------
-- Table structure for ma_menu
-- ----------------------------
DROP TABLE IF EXISTS `ma_menu`;
CREATE TABLE `ma_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `menu_name` varchar(32) DEFAULT NULL,
  `pid` int(10) unsigned DEFAULT 0,
  `route` varchar(255) DEFAULT NULL,
  `sort` int(10) unsigned DEFAULT 0,
  `protected` tinyint(1) unsigned DEFAULT 0 COMMENT '是否受保护（系统默认菜单）',
  `update_time` datetime DEFAULT NULL,
  `disable` tinyint(1) unsigned DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ma_menu
-- ----------------------------
BEGIN;
INSERT INTO `ma_menu` VALUES (1, '系统设置', 0, NULL, 1, 1, NULL, 0);
INSERT INTO `ma_menu` VALUES (2, '用户管理', 1, '/adminuser', 2, 1, NULL, 0);
INSERT INTO `ma_menu` VALUES (3, '角色管理', 1, '/role', 3, 1, NULL, 0);
INSERT INTO `ma_menu` VALUES (4, '菜单管理', 1, '/menu', 4, 1, NULL, 0);
INSERT INTO `ma_menu` VALUES (5, '示例菜单', 0, '/test', 0, 0, '2020-07-17 23:48:43', 0);
INSERT INTO `ma_menu` VALUES (6, '示例子菜单A', 5, '/test/a', 0, 0, '2020-07-17 23:49:22', 0);
INSERT INTO `ma_menu` VALUES (7, '示例子菜单B', 5, '/test/b', 0, 0, '2020-07-17 23:49:45', 0);
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
INSERT INTO `ma_role` VALUES (1, '超级管理员', '2020-07-13 09:20:52');
INSERT INTO `ma_role` VALUES (2, '普通用户', '2019-11-01 09:00:00');
COMMIT;

-- ----------------------------
-- Table structure for ma_role_purview
-- ----------------------------
DROP TABLE IF EXISTS `ma_role_purview`;
CREATE TABLE `ma_role_purview` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) unsigned DEFAULT NULL,
  `menu_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ma_role_purview
-- ----------------------------
BEGIN;
INSERT INTO `ma_role_purview` VALUES (1, 1, 5);
INSERT INTO `ma_role_purview` VALUES (2, 1, 6);
INSERT INTO `ma_role_purview` VALUES (3, 1, 7);
INSERT INTO `ma_role_purview` VALUES (4, 1, 1);
INSERT INTO `ma_role_purview` VALUES (5, 1, 2);
INSERT INTO `ma_role_purview` VALUES (6, 1, 3);
INSERT INTO `ma_role_purview` VALUES (7, 1, 4);
INSERT INTO `ma_role_purview` VALUES (8, 2, 5);
INSERT INTO `ma_role_purview` VALUES (9, 2, 6);
INSERT INTO `ma_role_purview` VALUES (10, 2, 7);
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
