SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `sd_history_log`
-- ----------------------------
DROP TABLE IF EXISTS `sd_history_log`;
CREATE TABLE `sd_history_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` char(255) DEFAULT NULL,
  `user_type` char(25) DEFAULT NULL,
  `date_time` datetime DEFAULT NULL,
  `y` int(4) DEFAULT NULL,
  `m` char(2) DEFAULT NULL,
  `d` char(2) DEFAULT NULL,
  `time` char(50) DEFAULT NULL,
  `code` char(250) DEFAULT NULL,
  `ip_addess` char(50) DEFAULT NULL,
  `modules` varchar(255) DEFAULT NULL,
  `process` varchar(255) DEFAULT NULL,
  `message` text,
  `status` int(2) DEFAULT '0',
  PRIMARY KEY (`log_id`),
  KEY `year` (`y`),
  KEY `mounth` (`m`),
  KEY `date` (`d`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
