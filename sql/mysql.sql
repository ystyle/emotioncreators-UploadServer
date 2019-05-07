/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : ec

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2019-05-08 02:50:10
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for charainfo
-- ----------------------------
DROP TABLE IF EXISTS `charainfo`;
CREATE TABLE `charainfo` (
  `idx` int(11) NOT NULL AUTO_INCREMENT,
  `upload_type` int(1) DEFAULT NULL,
  `userID` int(128) DEFAULT NULL,
  `uid` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `uuid` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `passwd` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mac_id` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `voicetype` int(11) DEFAULT NULL,
  `birthmonth` int(11) DEFAULT NULL,
  `birthday` int(11) DEFAULT NULL,
  `bloodtype` int(11) DEFAULT NULL,
  `comment` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  `package` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sex` int(11) DEFAULT NULL,
  `height` int(11) DEFAULT NULL,
  `bust` int(11) DEFAULT NULL,
  `hair` int(11) DEFAULT NULL,
  `dlCount` int(11) DEFAULT NULL,
  `weekCount` int(11) DEFAULT NULL,
  `updateTime` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `update_idx` int(11) DEFAULT NULL,
  PRIMARY KEY (`idx`)
) ENGINE=MyISAM AUTO_INCREMENT=3208 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for mapinfo
-- ----------------------------
DROP TABLE IF EXISTS `mapinfo`;
CREATE TABLE `mapinfo` (
  `idx` int(11) NOT NULL AUTO_INCREMENT,
  `upload_type` int(1) DEFAULT NULL,
  `userID` int(128) DEFAULT NULL,
  `uid` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `uuid` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `passwd` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mac_id` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mapset` int(11) DEFAULT NULL,
  `object_num` int(11) DEFAULT NULL,
  `comment` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `package` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dlCount` int(11) DEFAULT NULL,
  `weekCount` int(11) DEFAULT NULL,
  `updateTime` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `update_idx` int(11) DEFAULT NULL,
  `ratingTotal` int(11) DEFAULT NULL,
  `rateCount` int(11) DEFAULT NULL,
  PRIMARY KEY (`idx`)
) ENGINE=MyISAM AUTO_INCREMENT=258 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for poseinfo
-- ----------------------------
DROP TABLE IF EXISTS `poseinfo`;
CREATE TABLE `poseinfo` (
  `idx` int(11) NOT NULL AUTO_INCREMENT,
  `upload_type` int(1) DEFAULT NULL,
  `userID` int(128) DEFAULT NULL,
  `uid` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `uuid` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `passwd` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mac_id` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dlCount` int(11) DEFAULT NULL,
  `weekCount` int(11) DEFAULT NULL,
  `updateTime` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `update_idx` int(11) DEFAULT NULL,
  `ratingTotal` int(11) DEFAULT NULL,
  `rateCount` int(11) DEFAULT NULL,
  PRIMARY KEY (`idx`)
) ENGINE=MyISAM AUTO_INCREMENT=230 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for sceneinfo
-- ----------------------------
DROP TABLE IF EXISTS `sceneinfo`;
CREATE TABLE `sceneinfo` (
  `idx` int(11) NOT NULL AUTO_INCREMENT,
  `upload_type` int(1) DEFAULT NULL,
  `userID` int(128) DEFAULT NULL,
  `uid` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `uuid` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `passwd` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mac_id` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mapset` int(11) DEFAULT NULL,
  `object_num` int(11) DEFAULT NULL,
  `male_num` int(11) DEFAULT NULL,
  `female_num` int(11) DEFAULT NULL,
  `is_adv` int(11) DEFAULT NULL,
  `is_h` int(11) DEFAULT NULL,
  `tag` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `map_package` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `chara_package` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dlCount` int(11) DEFAULT NULL,
  `weekCount` int(11) DEFAULT NULL,
  `updateTime` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `update_idx` int(11) DEFAULT NULL,
  `ratingTotal` int(11) DEFAULT NULL,
  `rateCount` int(11) DEFAULT NULL,
  `playCount` int(11) DEFAULT NULL,
  PRIMARY KEY (`idx`)
) ENGINE=MyISAM AUTO_INCREMENT=673 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for userinfo
-- ----------------------------
DROP TABLE IF EXISTS `userinfo`;
CREATE TABLE `userinfo` (
  `userIdx` int(128) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `passwd` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `handleName` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mapPoint` int(11) DEFAULT NULL,
  `posePoint` int(11) DEFAULT NULL,
  `scenePoint` int(11) DEFAULT NULL,
  `mapCount` int(11) DEFAULT NULL,
  `poseCount` int(11) DEFAULT NULL,
  `sceneCount` int(11) DEFAULT NULL,
  PRIMARY KEY (`userIdx`)
) ENGINE=MyISAM AUTO_INCREMENT=10215 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
