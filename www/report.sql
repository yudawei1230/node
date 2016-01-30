/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : report

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2016-01-20 00:11:16
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for report_report
-- ----------------------------
DROP TABLE IF EXISTS `report_report`;
CREATE TABLE `report_report` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `u_id` int(10) NOT NULL,
  `year` varchar(4) DEFAULT NULL,
  `month` varchar(2) DEFAULT NULL,
  `frequentness` text,
  `updatetime` int(10) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=63 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of report_report
-- ----------------------------

-- ----------------------------
-- Table structure for report_user
-- ----------------------------
DROP TABLE IF EXISTS `report_user`;
CREATE TABLE `report_user` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `type` tinyint(3) NOT NULL,
  `updatetime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of report_user
-- ----------------------------
INSERT INTO `report_user` VALUES ('1', 'admin', '21218cca77804d2ba1922c33e0151105', '1', '1453143916');
INSERT INTO `report_user` VALUES ('2', 'test', '21218cca77804d2ba1922c33e0151105', '2', '1453217603');

-- ----------------------------
-- Table structure for report_user_set
-- ----------------------------
DROP TABLE IF EXISTS `report_user_set`;
CREATE TABLE `report_user_set` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `u_id` varchar(255) DEFAULT NULL,
  `organizationcode` char(4) DEFAULT NULL COMMENT '机构类代码',
  `areascode` char(7) DEFAULT NULL COMMENT '地区代码',
  `institutioncode` char(14) DEFAULT NULL COMMENT '标准化机构编码',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of report_user_set
-- ----------------------------
