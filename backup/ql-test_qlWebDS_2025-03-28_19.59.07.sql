# --------------------------------------------------------------------------------- #
# Description: qlWebDS Pro-6.6.0a-B-1DL-4/2025 MySQL Database Backup Dump
# Date: 2025-03-28 19:59:07
# Website: https://localhost/
# Database Name: PapirusDir
# --------------------------------------------------------------------------------- #

USE `PapirusDir`;

# Drop tables 
DROP TABLE IF EXISTS `qlWebDS_sites`;

CREATE TABLE `qlwebds_sites` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `description_short` text NOT NULL,
  `description` text NOT NULL,
  `facebook_url` varchar(220) NOT NULL DEFAULT '',
  `twitter_url` varchar(220) NOT NULL DEFAULT '',
  `myspace_url` varchar(220) NOT NULL DEFAULT '',
  `youtube_url` varchar(220) NOT NULL DEFAULT '',
  `embedded_video_title` varchar(255) NOT NULL DEFAULT '',
  `embedded_video_code` text NOT NULL,
  `original_description` text NOT NULL,
  `keywords` varchar(255) NOT NULL DEFAULT '',
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `link_type` tinyint(4) NOT NULL DEFAULT 0,
  `reciprocal` varchar(255) NOT NULL DEFAULT '',
  `url1` varchar(220) NOT NULL DEFAULT '',
  `url2` varchar(220) NOT NULL DEFAULT '',
  `url3` varchar(220) NOT NULL DEFAULT '',
  `url4` varchar(220) NOT NULL DEFAULT '',
  `url5` varchar(220) NOT NULL DEFAULT '',
  `title1` varchar(220) NOT NULL DEFAULT '',
  `title2` varchar(220) NOT NULL DEFAULT '',
  `title3` varchar(220) NOT NULL DEFAULT '',
  `title4` varchar(220) NOT NULL DEFAULT '',
  `title5` varchar(220) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(64) NOT NULL DEFAULT '',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `pr` tinyint(4) NOT NULL DEFAULT -1,
  `user_id` mediumint(9) NOT NULL DEFAULT 0,
  `user_ip` varchar(30) NOT NULL DEFAULT '',
  `nofollow` tinyint(1) NOT NULL DEFAULT 0,
  `featured` tinyint(1) NOT NULL DEFAULT 0,
  `backlink` varchar(255) NOT NULL DEFAULT '',
  `last_checked` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `paid_link` tinyint(1) NOT NULL DEFAULT 0,
  `last_payment` datetime DEFAULT NULL,
  `last_payment_id` int(11) NOT NULL DEFAULT 0,
  `mod_rewrite` varchar(255) NOT NULL DEFAULT '',
  `stats` mediumint(6) NOT NULL DEFAULT 0,
  `note` varchar(255) NOT NULL DEFAULT '',
  `company` varchar(255) NOT NULL DEFAULT '',
  `product` varchar(255) NOT NULL DEFAULT '',
  `address` varchar(255) NOT NULL DEFAULT '',
  `city` varchar(255) NOT NULL DEFAULT '',
  `state` varchar(255) NOT NULL DEFAULT '',
  `zip` varchar(255) NOT NULL DEFAULT '',
  `country` varchar(255) NOT NULL DEFAULT '',
  `tel` varchar(255) NOT NULL DEFAULT '',
  `tel_digits` varchar(255) NOT NULL DEFAULT '',
  `fax` varchar(255) NOT NULL DEFAULT '',
  `exp_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `visit_count` int(11) NOT NULL DEFAULT 0,
  `id_code` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`link_type`),
  KEY `status` (`status`),
  KEY `email` (`email`),
  KEY `last_payment` (`last_payment`),
  KEY `paid_link` (`paid_link`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ;
