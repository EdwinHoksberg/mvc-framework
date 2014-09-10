-- phpMyAdmin SQL Dump
-- version 4.0.9
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 10, 2014 at 04:19 PM
-- Server version: 5.6.14
-- PHP Version: 5.5.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `mvc`
--
CREATE DATABASE IF NOT EXISTS `mvc` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `mvc`;

-- --------------------------------------------------------

--
-- Table structure for table `mvc__language`
--

CREATE TABLE IF NOT EXISTS `mvc__language` (
  `language_id` int(11) NOT NULL AUTO_INCREMENT,
  `short` varchar(6) NOT NULL,
  `name` varchar(32) NOT NULL,
  `fullname` varchar(64) NOT NULL,
  PRIMARY KEY (`language_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `mvc__language`
--

INSERT INTO `mvc__language` (`language_id`, `short`, `name`, `fullname`) VALUES
(1, 'en', 'english', 'English');

-- --------------------------------------------------------

--
-- Table structure for table `mvc__session`
--

CREATE TABLE IF NOT EXISTS `mvc__session` (
  `session_id` varchar(32) NOT NULL,
  `ip_address` varchar(16) NOT NULL,
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL,
  `user_data` text NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `mvc__settings`
--

CREATE TABLE IF NOT EXISTS `mvc__settings` (
  `settings_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`settings_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `mvc__settings`
--

INSERT INTO `mvc__settings` (`settings_id`, `name`, `value`) VALUES
(1, 'default_language', '1'),
(2, 'maintenance_mode', '0'),
(3, 'log_errors', '1'),
(4, 'display_errors', '1'),
(5, 'product_name', 'MVC'),
(6, 'use_smtp', '0'),
(7, 'smtp_server', ''),
(8, 'smtp_username', ''),
(9, 'smtp_password', ''),
(10, 'smtp_fromname', ''),
(11, 'system_timezone', 'Europe/Amsterdam'),
(12, 'gzip_output', '0'),
(13, 'gzip_level', '6');
