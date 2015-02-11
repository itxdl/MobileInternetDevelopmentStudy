/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MariaDB
 Source Server Version : 100014
 Source Host           : localhost
 Source Database       : newsdb

 Target Server Type    : MariaDB
 Target Server Version : 100014
 File Encoding         : utf-8

 Date: 02/05/2015 22:15:09 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `tb_attachment`
-- ----------------------------
DROP TABLE IF EXISTS `tb_attachment`;
CREATE TABLE `tb_attachment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `addtime` int(10) unsigned DEFAULT NULL,
  `count` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `tb_category`
-- ----------------------------
DROP TABLE IF EXISTS `tb_category`;
CREATE TABLE `tb_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `count` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `tb_category`
-- ----------------------------
BEGIN;
INSERT INTO `tb_category` VALUES ('1', '国内新闻', '1'), ('2', '国际新闻', '0'), ('3', '娱乐新闻', '1'), ('4', '体育新闻', '0');
COMMIT;

-- ----------------------------
--  Table structure for `tb_comment`
-- ----------------------------
DROP TABLE IF EXISTS `tb_comment`;
CREATE TABLE `tb_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `content` text,
  `addtime` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `tb_news`
-- ----------------------------
DROP TABLE IF EXISTS `tb_news`;
CREATE TABLE `tb_news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(64) NOT NULL,
  `content` text NOT NULL,
  `cid` int(11) NOT NULL,
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `tb_news`
-- ----------------------------
BEGIN;
INSERT INTO `tb_news` VALUES ('1', '大家好！', '大家好！新年快乐！', '1', '1423056496', '1'), ('2', '大家好！', '大家好！新年快乐！', '1', '1423057218', '1'), ('3', 'dsddd', 'sdfsdf', '2', '1423057740', '1'), ('4', 'asdf', 'asdf', '1', '1423057834', '1'), ('5', 'asdfasdf', 'asdfasdfasdf', '3', '1423058362', '1');
COMMIT;

-- ----------------------------
--  Table structure for `tb_token`
-- ----------------------------
DROP TABLE IF EXISTS `tb_token`;
CREATE TABLE `tb_token` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `token` char(32) NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  `devicesn` char(32) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `tb_token`
-- ----------------------------
BEGIN;
INSERT INTO `tb_token` VALUES ('2', '1', '04fafee2ef3775ffb6cdfdf0ea79db09', '1423138302', 'xdl_pc'), ('4', '1', 'c808572abd127bae9c7d83deab0f45ba', '1423145315', '052e752b243faf95d6e46d1c3a61987e'), ('5', '1', '3d08282baee8900a6f68fe09152795c3', '1423145452', '');
COMMIT;

-- ----------------------------
--  Table structure for `tb_user`
-- ----------------------------
DROP TABLE IF EXISTS `tb_user`;
CREATE TABLE `tb_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL,
  `userpass` char(32) NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  `state` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `tb_user`
-- ----------------------------
BEGIN;
INSERT INTO `tb_user` VALUES ('1', 'admin', '21232f297a57a5a743894a0e4a801fc3', '123456767', '1');
COMMIT;

-- ----------------------------
--  Table structure for `tb_userlogin`
-- ----------------------------
DROP TABLE IF EXISTS `tb_userlogin`;
CREATE TABLE `tb_userlogin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `account` varchar(56) NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `devicesn` char(32) DEFAULT NULL,
  `state` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `account` (`account`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `tb_userlogin`
-- ----------------------------
BEGIN;
INSERT INTO `tb_userlogin` VALUES ('1', 'admin', '1', 'xdl_pc', '1');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
