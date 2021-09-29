CREATE DATABASE IF NOT EXISTS `to2`
USE `to2`;

CREATE TABLE IF NOT EXISTS `countries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `start` int(10) unsigned NOT NULL,
  `end` int(10) unsigned NOT NULL,
  `code` char(2) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `shortUrl` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `regDate` datetime NOT NULL,
  `url` varchar(500) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `code` char(10) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `sCount` int(10) unsigned NOT NULL DEFAULT '1',
  `rCount` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` char(15) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `url` (`url`(255)),
  KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;