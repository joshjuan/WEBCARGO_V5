-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 18, 2019 at 05:27 PM
-- Server version: 10.4.8-MariaDB
-- PHP Version: 7.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `webcargo_development`
--

-- --------------------------------------------------------

--
-- Table structure for table `allocated_device`
--

CREATE TABLE `allocated_device` (
  `id` int(11) NOT NULL,
  `serial_no` int(11) NOT NULL,
  `allocated_date` datetime NOT NULL,
  `allocated_to` int(11) NOT NULL,
  `allocated_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `auth_assignment`
--

CREATE TABLE `auth_assignment` (
  `item_name` varchar(64) NOT NULL,
  `user_id` varchar(64) NOT NULL,
  `created_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `auth_item`
--

CREATE TABLE `auth_item` (
  `name` varchar(64) NOT NULL,
  `type` smallint(6) NOT NULL,
  `description` text DEFAULT NULL,
  `rule_name` varchar(64) DEFAULT NULL,
  `data` blob DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `auth_item_child`
--

CREATE TABLE `auth_item_child` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `auth_rule`
--

CREATE TABLE `auth_rule` (
  `name` varchar(64) NOT NULL,
  `data` blob DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `border_port`
--

CREATE TABLE `border_port` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `location` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `border_port`
--

INSERT INTO `border_port` (`id`, `name`, `location`) VALUES
(1, 'TUNDUMA', 1),
(2, 'KASUMULU', 1),
(3, 'KABANGA', 1),
(4, 'MTUKULA', 1),
(5, 'RUSUMO', 1),
(6, 'AFRICAN ICD', 2),
(7, 'ALHUSHOOM', 2),
(8, 'AMI', 2),
(9, 'BAKHRESA', 2),
(10, 'DICD', 2),
(11, 'EASTCOST', 2),
(12, 'GATE 2', 2),
(13, 'GATE 3', 2),
(14, 'GATE 5', 2),
(15, 'JEFA', 2),
(16, 'KICD', 2),
(17, 'MCCL', 2),
(18, 'MOFED', 2),
(19, 'PMM', 2),
(20, 'TICTS', 2),
(21, 'TRH', 2);

-- --------------------------------------------------------

--
-- Table structure for table `border_port_user`
--

CREATE TABLE `border_port_user` (
  `id` int(11) NOT NULL,
  `border_port` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `assigned_date` datetime NOT NULL,
  `assigned_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `border_port_user`
--

INSERT INTO `border_port_user` (`id`, `border_port`, `name`, `assigned_date`, `assigned_by`) VALUES
(1, 1, '1', '2019-12-17 18:31:29', 1),
(2, 1, '2', '2019-12-18 08:47:13', 1),
(3, 6, '3', '2019-12-18 08:47:32', 1);

-- --------------------------------------------------------

--
-- Table structure for table `devices`
--

CREATE TABLE `devices` (
  `id` int(11) NOT NULL,
  `serial` bigint(20) UNSIGNED NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT 10
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `devices`
--

INSERT INTO `devices` (`id`, `serial`, `created_by`, `created_at`, `status`) VALUES
(1, 7560924149, 1, '2019-12-17 17:10:56', 1),
(2, 7560924368, 1, '2019-12-17 17:10:56', 1),
(3, 7560923974, 1, '2019-12-17 17:10:56', 1),
(4, 7560924317, 1, '2019-12-17 17:10:56', 1),
(5, 7571018432, 1, '2019-12-17 17:10:56', 1),
(6, 7560923604, 1, '2019-12-17 17:10:56', 1),
(7, 7560923952, 1, '2019-12-17 17:10:56', 1),
(8, 7560923989, 1, '2019-12-17 17:10:56', 1),
(9, 7560924021, 1, '2019-12-17 17:10:56', 1),
(10, 7560924435, 1, '2019-12-17 17:10:56', 1),
(11, 7560924467, 1, '2019-12-17 17:10:56', 1),
(12, 7560924005, 1, '2019-12-17 17:10:56', 1),
(13, 7560924078, 1, '2019-12-17 17:10:56', 1),
(14, 7560924126, 1, '2019-12-17 17:10:56', 1),
(15, 7560924274, 1, '2019-12-17 17:10:56', 1),
(16, 7560924431, 1, '2019-12-17 17:10:56', 1),
(17, 7560923854, 1, '2019-12-17 17:10:56', 1),
(18, 7560923864, 1, '2019-12-17 17:10:56', 1),
(19, 7560924275, 1, '2019-12-17 17:10:56', 1),
(20, 7560924373, 1, '2019-12-17 17:10:56', 1),
(21, 7560923959, 1, '2019-12-17 17:10:56', 1),
(22, 7560924017, 1, '2019-12-17 17:10:56', 1),
(23, 7560924088, 1, '2019-12-17 17:10:56', 1),
(24, 7560924423, 1, '2019-12-17 17:10:56', 1),
(25, 7571018094, 1, '2019-12-17 17:10:56', 1),
(26, 7570512173, 1, '2019-12-17 17:10:56', 1),
(27, 7571018058, 1, '2019-12-17 17:10:56', 1),
(28, 7571018279, 1, '2019-12-17 17:10:56', 1),
(29, 7571018298, 1, '2019-12-17 17:10:56', 1),
(30, 7571018440, 1, '2019-12-17 17:10:56', 1),
(31, 7571018663, 1, '2019-12-17 17:10:56', 1),
(32, 7571019191, 1, '2019-12-17 17:10:56', 1),
(33, 7570512122, 1, '2019-12-17 17:10:56', 1),
(34, 7571018850, 1, '2019-12-17 17:10:56', 1),
(35, 1234568, 1, '2019-12-18 07:56:37', 1),
(36, 1234567, 1, '2019-12-18 07:56:37', 1),
(37, 7654321896, 1, '2019-12-18 07:58:19', 1),
(38, 7655676753, 1, '2019-12-18 07:58:19', 1),
(39, 7570512059, 1, '2019-12-18 08:04:38', 1),
(40, 7570512071, 1, '2019-12-18 08:04:38', 1),
(41, 7570512075, 1, '2019-12-18 08:04:38', 1),
(42, 7570512079, 1, '2019-12-18 08:04:38', 1),
(43, 7570512090, 1, '2019-12-18 08:04:38', 1),
(44, 7570512117, 1, '2019-12-18 08:04:38', 1),
(45, 7570512119, 1, '2019-12-18 08:04:38', 1),
(46, 7570512219, 1, '2019-12-18 08:04:38', 1),
(47, 7560923606, 1, '2019-12-18 08:04:38', 1),
(48, 7560923625, 1, '2019-12-18 08:04:38', 1),
(49, 7560923677, 1, '2019-12-18 08:04:38', 1),
(50, 7560923688, 1, '2019-12-18 08:04:38', 1),
(51, 7560923700, 1, '2019-12-18 08:04:38', 1),
(52, 7560923713, 1, '2019-12-18 08:04:38', 1),
(53, 7560923744, 1, '2019-12-18 08:04:38', 1),
(54, 7560923754, 1, '2019-12-18 08:04:38', 1),
(55, 7560923768, 1, '2019-12-18 08:04:38', 1),
(56, 7560923798, 1, '2019-12-18 08:04:38', 1),
(57, 7560923812, 1, '2019-12-18 08:04:38', 1),
(58, 7560923813, 1, '2019-12-18 08:04:38', 1),
(59, 7560923815, 1, '2019-12-18 08:04:38', 1),
(60, 7560923817, 1, '2019-12-18 08:04:38', 1),
(61, 7560923846, 1, '2019-12-18 08:04:38', 1),
(62, 7560923850, 1, '2019-12-18 08:04:38', 1),
(63, 7560923852, 1, '2019-12-18 08:04:38', 1),
(64, 7560923858, 1, '2019-12-18 08:04:38', 1),
(65, 7560923867, 1, '2019-12-18 08:04:38', 1),
(66, 7560923870, 1, '2019-12-18 08:04:38', 1),
(67, 7560923873, 1, '2019-12-18 08:04:38', 1),
(68, 7560923876, 1, '2019-12-18 08:04:38', 1),
(69, 7560923877, 1, '2019-12-18 08:04:38', 1),
(70, 7560923881, 1, '2019-12-18 08:04:38', 1),
(71, 7560923900, 1, '2019-12-18 08:04:38', 1),
(72, 7560923941, 1, '2019-12-18 08:04:38', 1),
(73, 7560923954, 1, '2019-12-18 08:04:38', 1),
(74, 7560923955, 1, '2019-12-18 08:04:38', 1),
(75, 7560923963, 1, '2019-12-18 08:04:38', 1),
(76, 7560923967, 1, '2019-12-18 08:04:38', 1),
(77, 7560923972, 1, '2019-12-18 08:04:38', 1),
(78, 7560923976, 1, '2019-12-18 08:04:38', 1),
(79, 7560923984, 1, '2019-12-18 08:04:38', 1),
(80, 7560923986, 1, '2019-12-18 08:04:38', 1),
(81, 7560924033, 1, '2019-12-18 08:04:38', 1),
(82, 7560924049, 1, '2019-12-18 08:04:38', 1),
(83, 7560924057, 1, '2019-12-18 08:04:38', 1),
(84, 7560924064, 1, '2019-12-18 08:04:38', 1),
(85, 7560924065, 1, '2019-12-18 08:04:38', 1),
(86, 7560924066, 1, '2019-12-18 08:04:38', 1),
(87, 7560924080, 1, '2019-12-18 08:04:38', 1),
(88, 7560924092, 1, '2019-12-18 08:04:38', 1),
(89, 7560924096, 1, '2019-12-18 08:04:38', 1),
(90, 7560924107, 1, '2019-12-18 08:04:38', 1),
(91, 7560924114, 1, '2019-12-18 08:04:38', 1),
(92, 7560924145, 1, '2019-12-18 08:04:38', 1),
(93, 7560924155, 1, '2019-12-18 08:04:38', 1),
(94, 7560924169, 1, '2019-12-18 08:04:38', 1),
(95, 7560924289, 1, '2019-12-18 08:04:38', 1),
(96, 7560924293, 1, '2019-12-18 08:04:38', 1),
(97, 7560924299, 1, '2019-12-18 08:04:38', 1),
(98, 7560924323, 1, '2019-12-18 08:04:38', 1),
(99, 7560924328, 1, '2019-12-18 08:04:38', 1),
(100, 7560924335, 1, '2019-12-18 08:04:38', 1),
(101, 7560924336, 1, '2019-12-18 08:04:38', 1),
(102, 7560924344, 1, '2019-12-18 08:04:38', 1),
(103, 7560924357, 1, '2019-12-18 08:04:38', 1),
(104, 7560924367, 1, '2019-12-18 08:04:38', 1),
(105, 7560924372, 1, '2019-12-18 08:04:38', 1),
(106, 7560924437, 1, '2019-12-18 08:04:38', 1),
(107, 7560924439, 1, '2019-12-18 08:04:38', 1),
(108, 7560924441, 1, '2019-12-18 08:04:38', 1),
(109, 7560924453, 1, '2019-12-18 08:04:38', 1),
(110, 7560924470, 1, '2019-12-18 08:04:38', 1),
(111, 7560504507, 1, '2019-12-18 08:04:38', 1),
(112, 7560504509, 1, '2019-12-18 08:04:38', 1),
(113, 7560504595, 1, '2019-12-18 08:04:38', 1),
(114, 7560504650, 1, '2019-12-18 08:04:38', 1),
(115, 7571018091, 1, '2019-12-18 08:04:38', 1),
(116, 7571018135, 1, '2019-12-18 08:04:38', 1),
(117, 7571018138, 1, '2019-12-18 08:04:38', 1),
(118, 7571018151, 1, '2019-12-18 08:04:38', 1),
(119, 7571018158, 1, '2019-12-18 08:04:38', 1),
(120, 7571018176, 1, '2019-12-18 08:04:38', 1),
(121, 7571018184, 1, '2019-12-18 08:04:38', 1),
(122, 7571018286, 1, '2019-12-18 08:04:38', 1),
(123, 7571018342, 1, '2019-12-18 08:04:38', 1),
(124, 7571018348, 1, '2019-12-18 08:04:38', 1),
(125, 7571018382, 1, '2019-12-18 08:04:38', 1),
(126, 7571018385, 1, '2019-12-18 08:04:38', 1),
(127, 7571018389, 1, '2019-12-18 08:04:38', 1),
(128, 7571018392, 1, '2019-12-18 08:04:38', 1),
(129, 7571018393, 1, '2019-12-18 08:04:38', 1),
(130, 7571018408, 1, '2019-12-18 08:04:38', 1),
(131, 7571018482, 1, '2019-12-18 08:04:38', 1),
(132, 7571018571, 1, '2019-12-18 08:04:38', 1),
(133, 7571018610, 1, '2019-12-18 08:04:38', 1),
(134, 7571018644, 1, '2019-12-18 08:04:38', 1),
(135, 7571018683, 1, '2019-12-18 08:04:38', 1),
(136, 7571018686, 1, '2019-12-18 08:04:38', 1),
(137, 7571018687, 1, '2019-12-18 08:04:38', 1),
(138, 7571018695, 1, '2019-12-18 08:04:38', 1),
(139, 7571018758, 1, '2019-12-18 08:04:38', 1),
(140, 7571018860, 1, '2019-12-18 08:04:38', 1),
(141, 7571018881, 1, '2019-12-18 08:04:38', 1),
(142, 7571018898, 1, '2019-12-18 08:04:38', 1),
(143, 7571018906, 1, '2019-12-18 08:04:38', 1),
(144, 7571018942, 1, '2019-12-18 08:04:38', 1),
(145, 7571019069, 1, '2019-12-18 08:04:38', 1),
(146, 7571019077, 1, '2019-12-18 08:04:38', 1),
(147, 7571019090, 1, '2019-12-18 08:04:38', 1),
(148, 7571019091, 1, '2019-12-18 08:04:38', 1),
(149, 7571019099, 1, '2019-12-18 08:04:38', 1),
(150, 7571019123, 1, '2019-12-18 08:04:38', 1),
(151, 7571019124, 1, '2019-12-18 08:04:38', 1);

-- --------------------------------------------------------

--
-- Table structure for table `fault_devices`
--

CREATE TABLE `fault_devices` (
  `id` int(11) NOT NULL,
  `device_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `remarks` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE `location` (
  `id` int(11) NOT NULL,
  `location_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `location`
--

INSERT INTO `location` (`id`, `location_name`) VALUES
(1, 'BORDER'),
(2, 'PORT');

-- --------------------------------------------------------

--
-- Table structure for table `migration`
--

CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `migration`
--

INSERT INTO `migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1575282062),
('m130524_201442_init', 1575282072),
('m190124_110200_add_verification_token_column_to_user_table', 1575282073),
('m191202_084746_devices', 1575282074),
('m191202_085456_fault_devices', 1575282077);

-- --------------------------------------------------------

--
-- Table structure for table `received_devices`
--

CREATE TABLE `received_devices` (
  `id` int(11) NOT NULL,
  `serial_no` bigint(20) NOT NULL,
  `received_from` int(11) NOT NULL,
  `border_port` int(11) DEFAULT NULL,
  `received_from_staff` int(11) DEFAULT NULL,
  `received_at` datetime NOT NULL,
  `received_status` int(11) NOT NULL,
  `remark` text DEFAULT NULL,
  `received_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `received_devices`
--

INSERT INTO `received_devices` (`id`, `serial_no`, `received_from`, `border_port`, `received_from_staff`, `received_at`, `received_status`, `remark`, `received_by`) VALUES
(1, 1212121, 1, 1, 1, '2019-12-18 13:38:12', 1, 'hello', 1),
(2, 1234567891, 1, 1, 2, '2019-12-18 13:41:03', 1, 'fdfd', 1),
(3, 2121212, 1, 1, 2, '2019-12-18 13:52:03', 1, 'ffgf', 1),
(4, 7570512059, 1, 1, 1, '2019-12-18 15:23:35', 1, 'ok', 1),
(5, 7570512071, 1, 1, 1, '2019-12-18 15:23:35', 1, 'ok', 1),
(6, 7570512075, 1, 1, 1, '2019-12-18 15:23:35', 1, 'ok', 1),
(7, 7570512079, 1, 1, 1, '2019-12-18 15:23:35', 1, 'ok', 1),
(8, 7570512090, 1, 1, 1, '2019-12-18 15:23:35', 1, 'ok', 1),
(9, 7570512117, 1, 1, 1, '2019-12-18 15:23:35', 1, 'ok', 1),
(10, 7570512119, 1, 1, 1, '2019-12-18 15:23:35', 1, 'ok', 1),
(11, 7570512219, 1, 1, 1, '2019-12-18 15:23:35', 1, 'ok', 1),
(12, 75705120593, 2, 6, 3, '2019-12-18 15:25:40', 1, 'ok', 1),
(13, 75705120719, 2, 6, 3, '2019-12-18 15:25:40', 1, 'ok', 1),
(14, 75705120758, 2, 6, 3, '2019-12-18 15:25:40', 1, 'ok', 1),
(15, 75705120793, 2, 6, 3, '2019-12-18 15:25:40', 1, 'ok', 1),
(16, 75705120907, 2, 6, 3, '2019-12-18 15:25:40', 1, 'ok', 1),
(17, 75705121175, 2, 6, 3, '2019-12-18 15:25:40', 1, 'ok', 1),
(18, 75705121191, 2, 6, 3, '2019-12-18 15:25:40', 1, 'ok', 1),
(19, 75705122192, 2, 6, 3, '2019-12-18 15:25:40', 1, 'ok', 1),
(20, 7570512219, 1, 1, 1, '2019-12-18 15:32:40', 1, 'ok', 1),
(21, 7560923606, 1, 1, 1, '2019-12-18 15:32:40', 1, 'ok', 1),
(22, 7560923625, 1, 1, 1, '2019-12-18 15:32:40', 1, 'ok', 1),
(23, 7560923677, 1, 1, 1, '2019-12-18 15:32:40', 1, 'ok', 1),
(24, 7560923688, 1, 1, 1, '2019-12-18 15:32:40', 1, 'ok', 1),
(25, 7560923700, 1, 1, 1, '2019-12-18 15:32:40', 1, 'ok', 1),
(26, 7560923713, 1, 1, 1, '2019-12-18 15:32:40', 1, 'ok', 1),
(27, 7560923744, 1, 1, 1, '2019-12-18 15:32:40', 1, 'ok', 1),
(28, 7560923754, 1, 1, 1, '2019-12-18 15:32:40', 1, 'ok', 1);

-- --------------------------------------------------------

--
-- Table structure for table `rfidcard`
--

CREATE TABLE `rfidcard` (
  `id` int(11) NOT NULL,
  `card_no` int(11) NOT NULL,
  `assigned_to` int(11) NOT NULL,
  `assigned_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `stock_device`
--

CREATE TABLE `stock_device` (
  `id` int(11) NOT NULL,
  `serial_no` bigint(20) UNSIGNED NOT NULL,
  `device_status` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT 10,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `verification_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `auth_key`, `password_hash`, `password_reset_token`, `email`, `status`, `created_at`, `updated_at`, `verification_token`) VALUES
(1, 'admin', 'yCGlwHKeyPRHOfjef5elNVP2mheF4-iy', '$2y$13$oTTjjEVCyut/9a6TsqjoR.mKIt.7pY5m23fCUbMQLyolHxewZD/1i', NULL, 'admin@webtechnoligies.co.tz', 10, 1576596077, 1576596077, 'fQJlp160cOglOasf_qnhcorNaIv3S-Vm_1576596077'),
(2, 'adam', 'iRHD38UAx3o4Jm_oCZJv122YK2qGQI-z', '$2y$13$b5/DaHuFbwIXeUBLPk9AKOTW8yHoaIRFRCoZ/q.9pxZ72TjUhOiLO', NULL, 'adam@webtechnologies.co.tz', 9, 1576603609, 1576603609, 'ODYuZZYuoB33x6tWkWjyK5_x_88kn0f8_1576603609'),
(3, 'vedasto', 'Y0Hi0lbfqnU4U7cid5w71fAWuboLeFI-', '$2y$13$w8WMFFEa3CTv1sZ3rH7st.OlUNkIjq8kNfiogzs5WfJp470VzSqLq', NULL, 'vedasto@webtechnologies.co.tz', 9, 1576603664, 1576603664, 'bsjLtG3mgnSjIZjfHZbYvlC_RujvYSom_1576603664'),
(4, 'Vitus', 'L3pdw0Va5JIDCl-dF11b5sGngRetdt-z', '$2y$13$2E0YO5sm9kLbGg/nqfkjiOM9pOCD6/pc69PBaMbI3h.K8HzrlndvW', NULL, 'vitus@webtechnologies.co.tz', 9, 1576603706, 1576603706, 'FrWMmEoD-iehv-JTMkgdQmi1auZ_NeGc_1576603706'),
(5, 'Abbas', 'hz7LydnCt3qfacpZPNxIflbu6C-cDNrq', '$2y$13$H6n1apgcsjfQ//8dfHwiAOyfG9/0joyqVF87ll2KAIjJRJpyc6/H2', NULL, 'abbas@webtechnologies.co.tz', 9, 1576603734, 1576603734, 'zahYqzjqnK4CBITFV63ARNw994XmWj0y_1576603734');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `allocated_device`
--
ALTER TABLE `allocated_device`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `auth_assignment`
--
ALTER TABLE `auth_assignment`
  ADD PRIMARY KEY (`item_name`,`user_id`),
  ADD KEY `idx-auth_assignment-item_name` (`item_name`);

--
-- Indexes for table `auth_item`
--
ALTER TABLE `auth_item`
  ADD PRIMARY KEY (`name`),
  ADD KEY `idx-auth_item-rule_name` (`rule_name`);

--
-- Indexes for table `auth_item_child`
--
ALTER TABLE `auth_item_child`
  ADD PRIMARY KEY (`parent`,`child`),
  ADD KEY `idx-auth_item_child-parent` (`parent`),
  ADD KEY `idx-auth_item_child-child` (`child`);

--
-- Indexes for table `auth_rule`
--
ALTER TABLE `auth_rule`
  ADD PRIMARY KEY (`name`);

--
-- Indexes for table `border_port`
--
ALTER TABLE `border_port`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`),
  ADD KEY `location` (`location`);

--
-- Indexes for table `border_port_user`
--
ALTER TABLE `border_port_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `border_port` (`border_port`);

--
-- Indexes for table `devices`
--
ALTER TABLE `devices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx-devices-created_by` (`created_by`),
  ADD KEY `serial` (`serial`);

--
-- Indexes for table `fault_devices`
--
ALTER TABLE `fault_devices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx-fault_devices-created_by` (`created_by`),
  ADD KEY `idx-fault_devices-device_id` (`device_id`);

--
-- Indexes for table `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migration`
--
ALTER TABLE `migration`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `received_devices`
--
ALTER TABLE `received_devices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rfidcard`
--
ALTER TABLE `rfidcard`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_device`
--
ALTER TABLE `stock_device`
  ADD PRIMARY KEY (`id`),
  ADD KEY `serial_no` (`serial_no`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `password_reset_token` (`password_reset_token`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `allocated_device`
--
ALTER TABLE `allocated_device`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `border_port`
--
ALTER TABLE `border_port`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `border_port_user`
--
ALTER TABLE `border_port_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `devices`
--
ALTER TABLE `devices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=152;

--
-- AUTO_INCREMENT for table `fault_devices`
--
ALTER TABLE `fault_devices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `location`
--
ALTER TABLE `location`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `received_devices`
--
ALTER TABLE `received_devices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `rfidcard`
--
ALTER TABLE `rfidcard`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_device`
--
ALTER TABLE `stock_device`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `auth_assignment`
--
ALTER TABLE `auth_assignment`
  ADD CONSTRAINT `fk-auth_assignment-item_name` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE;

--
-- Constraints for table `auth_item`
--
ALTER TABLE `auth_item`
  ADD CONSTRAINT `fk-auth_item-rule_name` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE CASCADE;

--
-- Constraints for table `auth_item_child`
--
ALTER TABLE `auth_item_child`
  ADD CONSTRAINT `fk-auth_item_child-child` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk-auth_item_child-parent` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE;

--
-- Constraints for table `border_port`
--
ALTER TABLE `border_port`
  ADD CONSTRAINT `border_port_ibfk_1` FOREIGN KEY (`location`) REFERENCES `location` (`id`);

--
-- Constraints for table `border_port_user`
--
ALTER TABLE `border_port_user`
  ADD CONSTRAINT `border_port_user_ibfk_1` FOREIGN KEY (`border_port`) REFERENCES `border_port` (`id`);

--
-- Constraints for table `devices`
--
ALTER TABLE `devices`
  ADD CONSTRAINT `fk-devices-created_by` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`);

--
-- Constraints for table `fault_devices`
--
ALTER TABLE `fault_devices`
  ADD CONSTRAINT `fk-fault_devices-created_by` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `fk-fault_devices-device_id` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`);

--
-- Constraints for table `stock_device`
--
ALTER TABLE `stock_device`
  ADD CONSTRAINT `stock_device_ibfk_1` FOREIGN KEY (`serial_no`) REFERENCES `devices` (`serial`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
