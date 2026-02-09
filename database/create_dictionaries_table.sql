-- Run this in MySQL (e.g. Laragon MySQL, phpMyAdmin) if the migration was not run.
-- Database: myapp

CREATE TABLE IF NOT EXISTS `dictionaries` (
  `dictionar_id` int unsigned NOT NULL AUTO_INCREMENT,
  `parameter` varchar(255) DEFAULT NULL,
  `romanian` mediumtext DEFAULT NULL,
  `english` mediumtext DEFAULT NULL,
  `record_update_date` datetime DEFAULT NULL,
  PRIMARY KEY (`dictionar_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
