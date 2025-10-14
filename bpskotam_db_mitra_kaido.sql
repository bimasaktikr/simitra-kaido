-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 08, 2025 at 01:29 PM
-- Server version: 10.6.23-MariaDB-cll-lve
-- PHP Version: 8.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bpskotam_db_mitra_kaido`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `breezy_sessions`
--

CREATE TABLE `breezy_sessions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `authenticatable_type` varchar(255) NOT NULL,
  `authenticatable_id` bigint(20) UNSIGNED NOT NULL,
  `panel_id` varchar(255) DEFAULT NULL,
  `guard` varchar(255) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `two_factor_secret` text DEFAULT NULL,
  `two_factor_recovery_codes` text DEFAULT NULL,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('livewire-rate-limiter:1501ea1f0e0e9900a19b03945e97a3bb759fbf0d', 'i:1;', 1757920782),
('livewire-rate-limiter:1501ea1f0e0e9900a19b03945e97a3bb759fbf0d:timer', 'i:1757920782;', 1757920782),
('livewire-rate-limiter:26813804100e25e7cf0bf09b4a5f3eec05d4f507', 'i:2;', 1758159583),
('livewire-rate-limiter:26813804100e25e7cf0bf09b4a5f3eec05d4f507:timer', 'i:1758159583;', 1758159583),
('livewire-rate-limiter:278c4814d965c53f8fc607607ab5d54aefb637d6', 'i:1;', 1758350713),
('livewire-rate-limiter:278c4814d965c53f8fc607607ab5d54aefb637d6:timer', 'i:1758350713;', 1758350713),
('livewire-rate-limiter:5ac12620303c60726b70021fe064e244d54e5db0', 'i:1;', 1759293583),
('livewire-rate-limiter:5ac12620303c60726b70021fe064e244d54e5db0:timer', 'i:1759293583;', 1759293583),
('livewire-rate-limiter:e5047f1bfa5a3ae53cb17d1e3d73c5de44e234a5', 'i:3;', 1759821260),
('livewire-rate-limiter:e5047f1bfa5a3ae53cb17d1e3d73c5de44e234a5:timer', 'i:1759821260;', 1759821260),
('spatie.permission.cache', 'a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:160:{i:0;a:4:{s:1:\"a\";s:1:\"1\";s:1:\"b\";s:9:\"view_book\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:1;a:4:{s:1:\"a\";s:1:\"2\";s:1:\"b\";s:13:\"view_any_book\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:2;a:4:{s:1:\"a\";s:1:\"3\";s:1:\"b\";s:11:\"create_book\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:3;a:4:{s:1:\"a\";s:1:\"4\";s:1:\"b\";s:11:\"update_book\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:4;a:4:{s:1:\"a\";s:1:\"5\";s:1:\"b\";s:12:\"restore_book\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:5;a:4:{s:1:\"a\";s:1:\"6\";s:1:\"b\";s:16:\"restore_any_book\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:6;a:4:{s:1:\"a\";s:1:\"7\";s:1:\"b\";s:14:\"replicate_book\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:7;a:4:{s:1:\"a\";s:1:\"8\";s:1:\"b\";s:12:\"reorder_book\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:8;a:4:{s:1:\"a\";s:1:\"9\";s:1:\"b\";s:11:\"delete_book\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:9;a:4:{s:1:\"a\";s:2:\"10\";s:1:\"b\";s:15:\"delete_any_book\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:10;a:4:{s:1:\"a\";s:2:\"11\";s:1:\"b\";s:17:\"force_delete_book\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:11;a:4:{s:1:\"a\";s:2:\"12\";s:1:\"b\";s:21:\"force_delete_any_book\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:12;a:4:{s:1:\"a\";s:2:\"13\";s:1:\"b\";s:16:\"book:create_book\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:13;a:4:{s:1:\"a\";s:2:\"14\";s:1:\"b\";s:16:\"book:update_book\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:14;a:4:{s:1:\"a\";s:2:\"15\";s:1:\"b\";s:16:\"book:delete_book\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:15;a:4:{s:1:\"a\";s:2:\"16\";s:1:\"b\";s:20:\"book:pagination_book\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:16;a:4:{s:1:\"a\";s:2:\"17\";s:1:\"b\";s:16:\"book:detail_book\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:17;a:4:{s:1:\"a\";s:2:\"18\";s:1:\"b\";s:13:\"view_employee\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:18;a:4:{s:1:\"a\";s:2:\"19\";s:1:\"b\";s:17:\"view_any_employee\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:19;a:4:{s:1:\"a\";s:2:\"20\";s:1:\"b\";s:15:\"create_employee\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:20;a:4:{s:1:\"a\";s:2:\"21\";s:1:\"b\";s:15:\"update_employee\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:21;a:4:{s:1:\"a\";s:2:\"22\";s:1:\"b\";s:16:\"restore_employee\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:22;a:4:{s:1:\"a\";s:2:\"23\";s:1:\"b\";s:20:\"restore_any_employee\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:23;a:4:{s:1:\"a\";s:2:\"24\";s:1:\"b\";s:18:\"replicate_employee\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:24;a:4:{s:1:\"a\";s:2:\"25\";s:1:\"b\";s:16:\"reorder_employee\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:25;a:4:{s:1:\"a\";s:2:\"26\";s:1:\"b\";s:15:\"delete_employee\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:26;a:4:{s:1:\"a\";s:2:\"27\";s:1:\"b\";s:19:\"delete_any_employee\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:27;a:4:{s:1:\"a\";s:2:\"28\";s:1:\"b\";s:21:\"force_delete_employee\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:28;a:4:{s:1:\"a\";s:2:\"29\";s:1:\"b\";s:25:\"force_delete_any_employee\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:29;a:4:{s:1:\"a\";s:2:\"30\";s:1:\"b\";s:10:\"view_mitra\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:30;a:4:{s:1:\"a\";s:2:\"31\";s:1:\"b\";s:14:\"view_any_mitra\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:31;a:4:{s:1:\"a\";s:2:\"32\";s:1:\"b\";s:12:\"create_mitra\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:32;a:4:{s:1:\"a\";s:2:\"33\";s:1:\"b\";s:12:\"update_mitra\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:33;a:4:{s:1:\"a\";s:2:\"34\";s:1:\"b\";s:13:\"restore_mitra\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:34;a:4:{s:1:\"a\";s:2:\"35\";s:1:\"b\";s:17:\"restore_any_mitra\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:35;a:4:{s:1:\"a\";s:2:\"36\";s:1:\"b\";s:15:\"replicate_mitra\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:36;a:4:{s:1:\"a\";s:2:\"37\";s:1:\"b\";s:13:\"reorder_mitra\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:37;a:4:{s:1:\"a\";s:2:\"38\";s:1:\"b\";s:12:\"delete_mitra\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:38;a:4:{s:1:\"a\";s:2:\"39\";s:1:\"b\";s:16:\"delete_any_mitra\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:39;a:4:{s:1:\"a\";s:2:\"40\";s:1:\"b\";s:18:\"force_delete_mitra\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:40;a:4:{s:1:\"a\";s:2:\"41\";s:1:\"b\";s:22:\"force_delete_any_mitra\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:41;a:4:{s:1:\"a\";s:2:\"42\";s:1:\"b\";s:19:\"view_mitra::teladan\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:42;a:4:{s:1:\"a\";s:2:\"43\";s:1:\"b\";s:23:\"view_any_mitra::teladan\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:43;a:4:{s:1:\"a\";s:2:\"44\";s:1:\"b\";s:21:\"create_mitra::teladan\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:44;a:4:{s:1:\"a\";s:2:\"45\";s:1:\"b\";s:21:\"update_mitra::teladan\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:45;a:4:{s:1:\"a\";s:2:\"46\";s:1:\"b\";s:22:\"restore_mitra::teladan\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:46;a:4:{s:1:\"a\";s:2:\"47\";s:1:\"b\";s:26:\"restore_any_mitra::teladan\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:47;a:4:{s:1:\"a\";s:2:\"48\";s:1:\"b\";s:24:\"replicate_mitra::teladan\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:48;a:4:{s:1:\"a\";s:2:\"49\";s:1:\"b\";s:22:\"reorder_mitra::teladan\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:49;a:4:{s:1:\"a\";s:2:\"50\";s:1:\"b\";s:21:\"delete_mitra::teladan\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:50;a:4:{s:1:\"a\";s:2:\"51\";s:1:\"b\";s:25:\"delete_any_mitra::teladan\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:51;a:4:{s:1:\"a\";s:2:\"52\";s:1:\"b\";s:27:\"force_delete_mitra::teladan\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:52;a:4:{s:1:\"a\";s:2:\"53\";s:1:\"b\";s:31:\"force_delete_any_mitra::teladan\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:53;a:4:{s:1:\"a\";s:2:\"54\";s:1:\"b\";s:11:\"view_nilai1\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:54;a:4:{s:1:\"a\";s:2:\"55\";s:1:\"b\";s:15:\"view_any_nilai1\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:55;a:4:{s:1:\"a\";s:2:\"56\";s:1:\"b\";s:13:\"create_nilai1\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:56;a:4:{s:1:\"a\";s:2:\"57\";s:1:\"b\";s:13:\"update_nilai1\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:57;a:4:{s:1:\"a\";s:2:\"58\";s:1:\"b\";s:14:\"restore_nilai1\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:58;a:4:{s:1:\"a\";s:2:\"59\";s:1:\"b\";s:18:\"restore_any_nilai1\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:59;a:4:{s:1:\"a\";s:2:\"60\";s:1:\"b\";s:16:\"replicate_nilai1\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:60;a:4:{s:1:\"a\";s:2:\"61\";s:1:\"b\";s:14:\"reorder_nilai1\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:61;a:4:{s:1:\"a\";s:2:\"62\";s:1:\"b\";s:13:\"delete_nilai1\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:62;a:4:{s:1:\"a\";s:2:\"63\";s:1:\"b\";s:17:\"delete_any_nilai1\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:63;a:4:{s:1:\"a\";s:2:\"64\";s:1:\"b\";s:19:\"force_delete_nilai1\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:64;a:4:{s:1:\"a\";s:2:\"65\";s:1:\"b\";s:23:\"force_delete_any_nilai1\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:65;a:4:{s:1:\"a\";s:2:\"66\";s:1:\"b\";s:11:\"view_nilai2\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:66;a:4:{s:1:\"a\";s:2:\"67\";s:1:\"b\";s:15:\"view_any_nilai2\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:67;a:4:{s:1:\"a\";s:2:\"68\";s:1:\"b\";s:13:\"create_nilai2\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:68;a:4:{s:1:\"a\";s:2:\"69\";s:1:\"b\";s:13:\"update_nilai2\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:69;a:4:{s:1:\"a\";s:2:\"70\";s:1:\"b\";s:14:\"restore_nilai2\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:70;a:4:{s:1:\"a\";s:2:\"71\";s:1:\"b\";s:18:\"restore_any_nilai2\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:71;a:4:{s:1:\"a\";s:2:\"72\";s:1:\"b\";s:16:\"replicate_nilai2\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:72;a:4:{s:1:\"a\";s:2:\"73\";s:1:\"b\";s:14:\"reorder_nilai2\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:73;a:4:{s:1:\"a\";s:2:\"74\";s:1:\"b\";s:13:\"delete_nilai2\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:74;a:4:{s:1:\"a\";s:2:\"75\";s:1:\"b\";s:17:\"delete_any_nilai2\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:75;a:4:{s:1:\"a\";s:2:\"76\";s:1:\"b\";s:19:\"force_delete_nilai2\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:76;a:4:{s:1:\"a\";s:2:\"77\";s:1:\"b\";s:23:\"force_delete_any_nilai2\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:77;a:4:{s:1:\"a\";s:2:\"78\";s:1:\"b\";s:12:\"view_payment\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:78;a:4:{s:1:\"a\";s:2:\"79\";s:1:\"b\";s:16:\"view_any_payment\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:79;a:4:{s:1:\"a\";s:2:\"80\";s:1:\"b\";s:14:\"create_payment\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:80;a:4:{s:1:\"a\";s:2:\"81\";s:1:\"b\";s:14:\"update_payment\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:81;a:4:{s:1:\"a\";s:2:\"82\";s:1:\"b\";s:15:\"restore_payment\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:82;a:4:{s:1:\"a\";s:2:\"83\";s:1:\"b\";s:19:\"restore_any_payment\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:83;a:4:{s:1:\"a\";s:2:\"84\";s:1:\"b\";s:17:\"replicate_payment\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:84;a:4:{s:1:\"a\";s:2:\"85\";s:1:\"b\";s:15:\"reorder_payment\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:85;a:4:{s:1:\"a\";s:2:\"86\";s:1:\"b\";s:14:\"delete_payment\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:86;a:4:{s:1:\"a\";s:2:\"87\";s:1:\"b\";s:18:\"delete_any_payment\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:87;a:4:{s:1:\"a\";s:2:\"88\";s:1:\"b\";s:20:\"force_delete_payment\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:88;a:4:{s:1:\"a\";s:2:\"89\";s:1:\"b\";s:24:\"force_delete_any_payment\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:89;a:4:{s:1:\"a\";s:2:\"90\";s:1:\"b\";s:9:\"view_role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:90;a:4:{s:1:\"a\";s:2:\"91\";s:1:\"b\";s:13:\"view_any_role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:91;a:4:{s:1:\"a\";s:2:\"92\";s:1:\"b\";s:11:\"create_role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:92;a:4:{s:1:\"a\";s:2:\"93\";s:1:\"b\";s:11:\"update_role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:93;a:4:{s:1:\"a\";s:2:\"94\";s:1:\"b\";s:11:\"delete_role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:94;a:4:{s:1:\"a\";s:2:\"95\";s:1:\"b\";s:15:\"delete_any_role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:95;a:4:{s:1:\"a\";s:2:\"96\";s:1:\"b\";s:11:\"view_survey\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:96;a:4:{s:1:\"a\";s:2:\"97\";s:1:\"b\";s:15:\"view_any_survey\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:97;a:4:{s:1:\"a\";s:2:\"98\";s:1:\"b\";s:13:\"create_survey\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:98;a:4:{s:1:\"a\";s:2:\"99\";s:1:\"b\";s:13:\"update_survey\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:99;a:4:{s:1:\"a\";s:3:\"100\";s:1:\"b\";s:14:\"restore_survey\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:100;a:4:{s:1:\"a\";s:3:\"101\";s:1:\"b\";s:18:\"restore_any_survey\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:101;a:4:{s:1:\"a\";s:3:\"102\";s:1:\"b\";s:16:\"replicate_survey\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:102;a:4:{s:1:\"a\";s:3:\"103\";s:1:\"b\";s:14:\"reorder_survey\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:103;a:4:{s:1:\"a\";s:3:\"104\";s:1:\"b\";s:13:\"delete_survey\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:104;a:4:{s:1:\"a\";s:3:\"105\";s:1:\"b\";s:17:\"delete_any_survey\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:105;a:4:{s:1:\"a\";s:3:\"106\";s:1:\"b\";s:19:\"force_delete_survey\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:106;a:4:{s:1:\"a\";s:3:\"107\";s:1:\"b\";s:23:\"force_delete_any_survey\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:107;a:4:{s:1:\"a\";s:3:\"108\";s:1:\"b\";s:9:\"view_team\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:108;a:4:{s:1:\"a\";s:3:\"109\";s:1:\"b\";s:13:\"view_any_team\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:109;a:4:{s:1:\"a\";s:3:\"110\";s:1:\"b\";s:11:\"create_team\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:110;a:4:{s:1:\"a\";s:3:\"111\";s:1:\"b\";s:11:\"update_team\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:111;a:4:{s:1:\"a\";s:3:\"112\";s:1:\"b\";s:12:\"restore_team\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:112;a:4:{s:1:\"a\";s:3:\"113\";s:1:\"b\";s:16:\"restore_any_team\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:113;a:4:{s:1:\"a\";s:3:\"114\";s:1:\"b\";s:14:\"replicate_team\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:114;a:4:{s:1:\"a\";s:3:\"115\";s:1:\"b\";s:12:\"reorder_team\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:115;a:4:{s:1:\"a\";s:3:\"116\";s:1:\"b\";s:11:\"delete_team\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:116;a:4:{s:1:\"a\";s:3:\"117\";s:1:\"b\";s:15:\"delete_any_team\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:117;a:4:{s:1:\"a\";s:3:\"118\";s:1:\"b\";s:17:\"force_delete_team\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:118;a:4:{s:1:\"a\";s:3:\"119\";s:1:\"b\";s:21:\"force_delete_any_team\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:119;a:4:{s:1:\"a\";s:3:\"120\";s:1:\"b\";s:10:\"view_token\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:120;a:4:{s:1:\"a\";s:3:\"121\";s:1:\"b\";s:14:\"view_any_token\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:121;a:4:{s:1:\"a\";s:3:\"122\";s:1:\"b\";s:12:\"create_token\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:122;a:4:{s:1:\"a\";s:3:\"123\";s:1:\"b\";s:12:\"update_token\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:123;a:4:{s:1:\"a\";s:3:\"124\";s:1:\"b\";s:13:\"restore_token\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:124;a:4:{s:1:\"a\";s:3:\"125\";s:1:\"b\";s:17:\"restore_any_token\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:125;a:4:{s:1:\"a\";s:3:\"126\";s:1:\"b\";s:15:\"replicate_token\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:126;a:4:{s:1:\"a\";s:3:\"127\";s:1:\"b\";s:13:\"reorder_token\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:127;a:4:{s:1:\"a\";s:3:\"128\";s:1:\"b\";s:12:\"delete_token\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:128;a:4:{s:1:\"a\";s:3:\"129\";s:1:\"b\";s:16:\"delete_any_token\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:129;a:4:{s:1:\"a\";s:3:\"130\";s:1:\"b\";s:18:\"force_delete_token\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:130;a:4:{s:1:\"a\";s:3:\"131\";s:1:\"b\";s:22:\"force_delete_any_token\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:131;a:4:{s:1:\"a\";s:3:\"132\";s:1:\"b\";s:16:\"view_transaction\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:132;a:4:{s:1:\"a\";s:3:\"133\";s:1:\"b\";s:20:\"view_any_transaction\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:133;a:4:{s:1:\"a\";s:3:\"134\";s:1:\"b\";s:18:\"create_transaction\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:134;a:4:{s:1:\"a\";s:3:\"135\";s:1:\"b\";s:18:\"update_transaction\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:135;a:4:{s:1:\"a\";s:3:\"136\";s:1:\"b\";s:19:\"restore_transaction\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:136;a:4:{s:1:\"a\";s:3:\"137\";s:1:\"b\";s:23:\"restore_any_transaction\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:137;a:4:{s:1:\"a\";s:3:\"138\";s:1:\"b\";s:21:\"replicate_transaction\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:138;a:4:{s:1:\"a\";s:3:\"139\";s:1:\"b\";s:19:\"reorder_transaction\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:139;a:4:{s:1:\"a\";s:3:\"140\";s:1:\"b\";s:18:\"delete_transaction\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:140;a:4:{s:1:\"a\";s:3:\"141\";s:1:\"b\";s:22:\"delete_any_transaction\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:141;a:4:{s:1:\"a\";s:3:\"142\";s:1:\"b\";s:24:\"force_delete_transaction\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:142;a:4:{s:1:\"a\";s:3:\"143\";s:1:\"b\";s:28:\"force_delete_any_transaction\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:143;a:4:{s:1:\"a\";s:3:\"144\";s:1:\"b\";s:9:\"view_user\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:144;a:4:{s:1:\"a\";s:3:\"145\";s:1:\"b\";s:13:\"view_any_user\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:145;a:4:{s:1:\"a\";s:3:\"146\";s:1:\"b\";s:11:\"create_user\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:146;a:4:{s:1:\"a\";s:3:\"147\";s:1:\"b\";s:11:\"update_user\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:147;a:4:{s:1:\"a\";s:3:\"148\";s:1:\"b\";s:12:\"restore_user\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:148;a:4:{s:1:\"a\";s:3:\"149\";s:1:\"b\";s:16:\"restore_any_user\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:149;a:4:{s:1:\"a\";s:3:\"150\";s:1:\"b\";s:14:\"replicate_user\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:150;a:4:{s:1:\"a\";s:3:\"151\";s:1:\"b\";s:12:\"reorder_user\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:151;a:4:{s:1:\"a\";s:3:\"152\";s:1:\"b\";s:11:\"delete_user\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:152;a:4:{s:1:\"a\";s:3:\"153\";s:1:\"b\";s:15:\"delete_any_user\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:153;a:4:{s:1:\"a\";s:3:\"154\";s:1:\"b\";s:17:\"force_delete_user\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:154;a:4:{s:1:\"a\";s:3:\"155\";s:1:\"b\";s:21:\"force_delete_any_user\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:155;a:4:{s:1:\"a\";s:3:\"156\";s:1:\"b\";s:25:\"page_EmployeeNilai2Status\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:156;a:4:{s:1:\"a\";s:3:\"157\";s:1:\"b\";s:18:\"page_ManageSetting\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:157;a:4:{s:1:\"a\";s:3:\"158\";s:1:\"b\";s:23:\"page_SelectMitraTeladan\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:5;}}i:158;a:4:{s:1:\"a\";s:3:\"159\";s:1:\"b\";s:11:\"page_Themes\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:159;a:4:{s:1:\"a\";s:3:\"160\";s:1:\"b\";s:18:\"page_MyProfilePage\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}}s:5:\"roles\";a:4:{i:0;a:3:{s:1:\"a\";s:1:\"1\";s:1:\"b\";s:11:\"super_admin\";s:1:\"c\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";s:1:\"2\";s:1:\"b\";s:7:\"Pegawai\";s:1:\"c\";s:3:\"web\";}i:2;a:3:{s:1:\"a\";s:1:\"3\";s:1:\"b\";s:9:\"Ketua Tim\";s:1:\"c\";s:3:\"web\";}i:3;a:3:{s:1:\"a\";s:1:\"5\";s:1:\"b\";s:9:\"Ketua SDM\";s:1:\"c\";s:3:\"web\";}}}', 1759907633);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(200) NOT NULL,
  `nip` varchar(200) NOT NULL,
  `jenis_kelamin` varchar(200) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `team_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tanggal_lahir` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `name`, `nip`, `jenis_kelamin`, `user_id`, `team_id`, `tanggal_lahir`, `created_at`, `updated_at`) VALUES
(1, 'Umar Sjaifudin M.Si', '197012161997031005', 'Laki-Laki', 2, 6, '1970-12-16', '2025-07-10 07:28:07', '2025-07-10 07:28:07'),
(2, 'Ir. Dwi Handayani Prasetyawati M.AP', '196810281994012001', 'Perempuan', 3, 7, '1968-10-28', '2025-07-10 07:28:07', '2025-07-10 08:20:14'),
(3, 'Ir. Wahyu Furqandari M.M', '196901181994012001', 'Perempuan', 4, 6, '1969-01-18', '2025-07-10 07:28:07', '2025-07-10 07:28:07'),
(4, 'Tasmilah SST, M.E', '198309102006022001', 'Perempuan', 5, 5, '1983-09-10', '2025-07-10 07:28:08', '2025-07-10 07:28:08'),
(5, 'Ir. Agustina Martha M.M', '196808231994012001', 'Perempuan', 6, 3, '1968-08-23', '2025-07-10 07:28:08', '2025-07-10 07:28:08'),
(6, 'Ahmad Junaedi S.Si, M.M', '197501311994011001', 'Laki-Laki', 7, 3, '1975-01-31', '2025-07-10 07:28:09', '2025-07-10 07:28:09'),
(7, 'Anggi Fatwa Mauliza A.Md.Kb.N', '200006272022012005', 'Perempuan', 8, 1, '2000-06-27', '2025-07-10 07:28:09', '2025-07-10 07:28:09'),
(8, 'Arini Ismiati S.ST', '197912202003122006', 'Perempuan', 9, 1, '1979-12-20', '2025-07-10 07:28:09', '2025-07-10 07:28:09'),
(9, 'Baqtiar Arifin S.Stat., MM', '197806272005021002', 'Laki-Laki', 10, 1, '1978-06-27', '2025-07-10 07:28:10', '2025-07-10 07:28:10'),
(10, 'Bima Sakti Krisdianto SST', '199511062017011001', 'Laki-Laki', 11, 6, '1995-11-06', '2025-07-10 07:28:10', '2025-07-10 07:28:10'),
(11, 'Eka Arifianto A.Md. Lib', '199709202023211005', 'Laki-Laki', 12, 6, '1997-09-20', '2025-07-10 07:28:11', '2025-07-10 07:28:11'),
(12, 'Erlisa Cantika Herawati S.Si', '199408052019032003', 'Perempuan', 13, 4, '1994-08-05', '2025-07-10 07:28:11', '2025-07-10 07:28:11'),
(14, 'Heru Kartiko SST', '197604211999031003', 'Laki-Laki', 15, 6, '1976-04-21', '2025-07-10 07:28:12', '2025-07-10 07:28:12'),
(15, 'Rachmad Widi Wijayanto', '197704042006041016', 'Laki-Laki', 16, 1, '1977-04-04', '2025-07-10 07:28:13', '2025-07-10 07:28:13'),
(16, 'Ratri Adhipradani Ratih S.Si', '198510022009022012', 'Perempuan', 17, 3, '1985-10-02', '2025-07-10 07:28:13', '2025-07-10 07:28:13'),
(17, 'Rendra Anandhika A.Md', '198802292011011005', 'Laki-Laki', 18, 6, '1988-02-29', '2025-07-10 07:28:13', '2025-07-10 07:28:13'),
(18, 'Rhyke Chrisdiana Novita S.E', '198404122005022001', 'Perempuan', 19, 3, '1984-04-12', '2025-07-10 07:28:14', '2025-07-10 07:28:14'),
(19, 'Rizky Maulidya SST, M.AP', '199109302014102001', 'Perempuan', 20, 5, '1991-09-30', '2025-07-10 07:28:15', '2025-07-10 07:28:15'),
(20, 'Saras Wati Utami S.Si, M.E', '198803302010122002', 'Perempuan', 21, 4, '1988-03-30', '2025-07-10 07:28:15', '2025-07-10 07:28:15'),
(21, 'Saruni Gincahyo SE', '196802281989031003', 'Laki-Laki', 22, 3, '1968-02-28', '2025-07-10 07:28:16', '2025-07-10 07:28:16'),
(22, 'Satria Candra Wibawa A.Md', '198810282011011004', 'Laki-Laki', 23, 6, '1988-10-28', '2025-07-10 07:28:16', '2025-07-10 07:28:16'),
(23, 'Siti Barokatun Solihah SST', '199110232016022001', 'Perempuan', 24, 2, '1999-11-02', '2025-07-10 07:28:16', '2025-07-10 07:28:16'),
(24, 'Soekesi Irawati S.Psi., M.M', '197009251994012001', 'Perempuan', 25, 4, '1970-09-25', '2025-07-10 07:28:17', '2025-07-10 07:28:17'),
(25, 'Windi Wijayanti S.Si, M.E', '198712182011012022', 'Perempuan', 26, 2, '1987-12-18', '2025-07-10 07:28:17', '2025-07-10 07:28:17'),
(26, 'Yenita Mirawanti SST., M.Si', '197806032000122002', 'Perempuan', 27, 2, '1978-06-03', '2025-07-10 07:28:17', '2025-07-10 07:28:17'),
(27, 'Yusuf Fatoni SE', '197012251997031004', 'Laki-Laki', 28, 4, '1970-12-25', '2025-07-10 07:28:18', '2025-07-10 07:28:18'),
(28, 'Jasmine Amalia Nastiti S.Tr.Stat.', '199805162021042001', 'Perempuan', 80, 3, '1998-05-16', '2025-07-10 16:30:56', '2025-07-10 16:30:56');

-- --------------------------------------------------------

--
-- Table structure for table `exports`
--

CREATE TABLE `exports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `file_disk` varchar(255) NOT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `exporter` varchar(255) NOT NULL,
  `processed_rows` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `total_rows` int(10) UNSIGNED NOT NULL,
  `successful_rows` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_import_rows`
--

CREATE TABLE `failed_import_rows` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`data`)),
  `import_id` bigint(20) UNSIGNED NOT NULL,
  `validation_error` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `imports`
--

CREATE TABLE `imports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `importer` varchar(255) NOT NULL,
  `processed_rows` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `total_rows` int(10) UNSIGNED NOT NULL,
  `successful_rows` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(1, 'default', '{\"uuid\":\"8c8b3919-1da4-4afb-8181-a0734d8658a3\",\"displayName\":\"Filament\\\\Notifications\\\\Auth\\\\VerifyEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:80;}s:9:\\\"relations\\\";a:1:{i:0;s:14:\\\"breezySessions\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:39:\\\"Filament\\\\Notifications\\\\Auth\\\\VerifyEmail\\\":2:{s:3:\\\"url\\\";s:194:\\\"https:\\/\\/mitra.bpskotamalang.id\\/email-verification\\/verify\\/80\\/c1cab1911c72ae528968925da09650f6809ce8a0?expires=1752399314&signature=a780ada30f71818cc28600f30d83e50bc1ae5b3ab3465080f01ec486969a98fd\\\";s:2:\\\"id\\\";s:36:\\\"019a6db8-4886-4106-893e-71ccc50c4731\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"}}', 0, NULL, 1752395714, 1752395714),
(2, 'default', '{\"uuid\":\"5eecd412-74f0-4e40-872c-cf28a007fa17\",\"displayName\":\"Filament\\\\Notifications\\\\Auth\\\\VerifyEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:80;}s:9:\\\"relations\\\";a:1:{i:0;s:14:\\\"breezySessions\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:39:\\\"Filament\\\\Notifications\\\\Auth\\\\VerifyEmail\\\":2:{s:3:\\\"url\\\";s:194:\\\"https:\\/\\/mitra.bpskotamalang.id\\/email-verification\\/verify\\/80\\/c1cab1911c72ae528968925da09650f6809ce8a0?expires=1752401235&signature=d8f512d0c462928503214c193341a8588fcd22a1af19ecf107a9b0691097589e\\\";s:2:\\\"id\\\";s:36:\\\"c24e9588-5c29-4bbf-b127-61f16edb4a09\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"}}', 0, NULL, 1752397635, 1752397635);

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `master_surveys`
--

CREATE TABLE `master_surveys` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `code` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `master_surveys`
--

INSERT INTO `master_surveys` (`id`, `name`, `code`, `created_at`, `updated_at`) VALUES
(1, 'Survey-Test-on-Dev', 'STOD', '2025-07-07 16:22:24', '2025-07-07 16:22:24'),
(3, 'Pengolahan Survei Sosial Ekonomi Nasional', 'SUSENAS', '2025-07-07 16:22:24', '2025-07-07 16:22:24'),
(5, 'Survey Ketenagakerjaan Nasional Februari', 'SAK', '2025-07-07 16:22:24', '2025-07-07 16:22:24'),
(7, 'Survei Nasional Literasi dan Inklusi Keuangan', 'SNLIK', '2025-07-07 16:22:24', '2025-07-07 16:22:24'),
(8, 'Survei Sosial Ekonomi Nasional', 'SUSENASMARET', '2025-07-07 16:22:24', '2025-07-07 16:22:24'),
(11, 'Survey-Test-on-Dev', 'STOD', '2025-07-07 16:22:24', '2025-07-07 16:22:24'),
(12, 'Pengolahan Survei Ekonomi Rumah Tangga Triwulanan', 'P-SERUTI', '2025-07-07 16:22:24', '2025-07-07 16:22:24'),
(13, 'Pengolahan Survei Sosial Ekonomi Nasional', 'SUSENAS', '2025-07-07 16:22:24', '2025-07-07 16:22:24'),
(14, 'Survei Harga Kemahalan Konstruksi', 'SHKK', '2025-07-07 16:22:24', '2025-07-07 16:22:24'),
(15, 'Survey Ketenagakerjaan Nasional Februari', 'SAK', '2025-07-07 16:22:24', '2025-07-07 16:22:24'),
(16, 'Survei Ekonomi Rumah Tangga', 'SERUTI', '2025-07-07 16:22:24', '2025-07-07 16:22:24'),
(17, 'Survei Nasional Literasi dan Inklusi Keuangan', 'SNLIK', '2025-07-07 16:22:24', '2025-07-07 16:22:24'),
(18, 'Survei Sosial Ekonomi Nasional', 'SUSENASMARET', '2025-07-07 16:22:24', '2025-07-07 16:22:24'),
(19, 'Survey Industri Besar Sedang', 'IBS', '2025-07-07 16:22:24', '2025-07-07 16:22:24'),
(20, 'Survey Konstruksi Triwulanan', 'KONSTRUKSI', '2025-07-07 16:22:24', '2025-07-07 16:22:24'),
(21, 'Kerangka Sampel Area', 'KSA', '2025-07-07 16:22:24', '2025-07-07 16:22:24'),
(22, 'Laporan Pemotongan Ternak Bulanan', 'LPTB', '2025-07-07 16:22:24', '2025-07-07 16:22:24'),
(23, 'Survei Perusahaan Air Bersih', 'SPAB', '2025-07-07 16:22:24', '2025-07-07 16:22:24'),
(24, 'Survey Ubinan', 'UBINAN', '2025-07-07 16:22:24', '2025-07-07 16:22:24'),
(25, 'Survei Khusus Lembaga Non Profit Rumah Tangga', 'SKLNPRT', '2025-07-07 16:22:24', '2025-07-07 16:22:24'),
(26, 'Survei Khusus Triwulanan Neraca Produksi', 'SKTNP', '2025-07-07 16:22:24', '2025-07-07 16:22:24'),
(27, 'Survei Triwulanan Kegiatan Usaha', 'SKTU', '2025-07-07 16:22:24', '2025-07-07 16:22:24'),
(28, 'Survei Tingkat Penghunian Kamar Hotel', 'VHTS', '2025-07-07 16:22:24', '2025-07-07 16:22:24'),
(29, 'Survey Harga Konsumen', 'SHK', '2025-07-07 16:22:24', '2025-07-07 16:22:24'),
(30, 'Survei Harga Produsen', 'SHP', '2025-07-07 16:22:24', '2025-07-07 16:22:24'),
(31, 'Survei Harga Perdagangan Besar', 'SHPB', '2025-07-07 16:22:24', '2025-07-07 16:22:24'),
(32, 'Survey Harga Produsen', 'SHP', '2025-07-07 16:22:24', '2025-07-07 16:22:24'),
(33, 'Pengolahan Survei Sosial Ekonomi Nasional', 'P-SSN-MARET', '2025-07-07 16:22:24', '2025-07-07 16:22:24'),
(34, 'Survei Objek Daya Tarik Wisata', 'VDTW', '2025-07-10 08:03:05', '2025-07-10 08:03:05'),
(35, 'Survei Perusahaan/Usaha Penyedia Jasa Akomodasi', 'VHTL', '2025-07-10 08:15:58', '2025-07-10 08:15:58'),
(36, 'Survei Tingkat Penghunian Kamar Hotel', 'VHTS', '2025-07-10 08:18:11', '2025-07-10 08:18:11'),
(37, 'Survei Perdagangan Barang Domestik', 'VPBD', '2025-07-10 08:21:37', '2025-07-10 08:21:37'),
(38, 'Survei Pergudangan dan Jasa Penunjang Angkutan', 'VPEK', '2025-07-10 08:23:14', '2025-07-10 08:23:14'),
(39, 'Survei Usaha Penyediaan Makanan Minuman', 'VREST', '2025-07-10 08:24:37', '2025-07-10 08:24:37'),
(40, 'Survei Survei Pola Usaha Non Pertanian', 'SPUNP', '2025-07-10 08:27:32', '2025-07-10 08:27:32'),
(41, 'Updating Direktori', 'UD', '2025-07-10 08:29:06', '2025-07-10 08:29:06'),
(42, 'Survei Lembaga Keuangan', 'SLK', '2025-07-10 08:30:16', '2025-07-10 08:30:16'),
(43, 'Survei Volume Penjualan Eceran Beras', 'SVPEB', '2025-07-10 08:41:13', '2025-07-10 08:41:13'),
(44, 'Potensi Desa', 'PODES', '2025-07-10 08:43:34', '2025-07-10 08:43:34'),
(45, 'Survei Captive Power', 'SCP', '2025-07-10 08:49:31', '2025-07-10 08:49:31'),
(46, 'Survei Industri Mikro dan Kecil', 'IMK', '2025-07-10 08:56:49', '2025-07-10 08:56:49'),
(47, 'Survei Non Migas', 'SNM', '2025-07-10 09:10:55', '2025-07-10 09:10:55'),
(48, 'Survei Perusahaan Hortikultura', 'SPH', '2025-07-10 09:18:07', '2025-07-10 09:18:07'),
(49, 'Survei Perusahaan Kehutanan', 'SPK', '2025-07-10 09:19:35', '2025-07-10 09:19:35'),
(50, 'Survei Perusahaan Peternakan', 'SPP', '2025-07-10 09:20:51', '2025-07-10 09:20:51'),
(51, 'Survei Khusus Lembaga Non Profit Rumah Tangga Triwulanan', 'SKLNPRT', '2025-07-10 09:26:15', '2025-07-10 09:26:15');

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) DEFAULT NULL,
  `collection_name` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `mime_type` varchar(255) DEFAULT NULL,
  `disk` varchar(255) NOT NULL,
  `conversions_disk` varchar(255) DEFAULT NULL,
  `size` bigint(20) UNSIGNED NOT NULL,
  `manipulations` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`manipulations`)),
  `custom_properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`custom_properties`)),
  `generated_conversions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`generated_conversions`)),
  `responsive_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`responsive_images`)),
  `order_column` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2022_12_14_083707_create_settings_table', 1),
(5, '2024_12_04_025120_create_media_table', 1),
(6, '2024_12_04_041953_create_breezy_sessions_table', 1),
(7, '2025_01_01_120813_create_books_table', 1),
(8, '2025_01_02_064819_create_permission_tables', 1),
(9, '2025_01_02_225927_add_avatar_url_column_to_users_table', 1),
(10, '2025_01_03_114929_create_personal_access_tokens_table', 1),
(11, '2025_01_04_125650_create_posts_table', 1),
(12, '2025_01_08_152510_create_kaido_settings', 1),
(13, '2025_01_08_233142_create_socialite_users_table', 1),
(14, '2025_01_09_225908_update_user_table_make_password_column_nullable', 1),
(15, '2025_01_12_031340_create_notifications_table', 1),
(16, '2025_01_12_031357_create_imports_table', 1),
(17, '2025_01_12_031358_create_exports_table', 1),
(18, '2025_01_12_031359_create_failed_import_rows_table', 1),
(19, '2025_01_12_091355_create_contacts_table', 1),
(20, '2025_01_31_020024_add_themes_settings_to_users_table', 1),
(21, '2025_04_10_082435_create_mitras_table', 1),
(22, '2025_04_10_084851_create_teams_table', 1),
(23, '2025_04_10_084919_create_employees_table', 1),
(24, '2025_04_10_163140_create_payments_table', 1),
(25, '2025_04_10_163451_create_surveys_table', 1),
(26, '2025_04_10_165714_create_transactions_table', 1),
(27, '2025_04_10_192738_create_nilai1s_table', 1),
(28, '2025_04_11_161853_create_mitra_teladans_table', 1),
(29, '2025_07_07_231115_create_master_surveys_table', 1),
(30, '2025_07_07_231256_add_master_survey_id_to_surveys_table', 1),
(31, '2025_07_07_232322_remove_name_code_from_surveys_table', 1),
(32, '2025_07_08_095132_add_year_to_surveys_table', 1),
(33, '2025_07_08_140341_add_all_column_to_nilai2s_table', 1),
(34, '2025_07_10_102532_rename_employee_id_to_user_id_in_nilai2s_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `mitras`
--

CREATE TABLE `mitras` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sobat_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(200) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(200) NOT NULL,
  `pendidikan` varchar(50) NOT NULL,
  `jenis_kelamin` varchar(50) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mitras`
--

INSERT INTO `mitras` (`id`, `sobat_id`, `name`, `user_id`, `email`, `pendidikan`, `jenis_kelamin`, `tanggal_lahir`, `photo`, `created_at`, `updated_at`) VALUES
(1, 357322090040, 'SUTARTI YUNI ASTUTIK', 32, 'astuti.sya@gmail.com', '5', 'Perempuan', '1970-01-01', NULL, '2025-07-10 08:07:44', '2025-07-10 08:07:44'),
(2, 357322030002, 'Emman Suparji', 29, 'e81emm4n5@gmail.com', '5', 'Perempuan', '1981-06-21', NULL, '2025-07-10 08:14:57', '2025-07-10 08:14:57'),
(3, 357322010048, 'SONNA ASDINATA', 30, 'Sonna.asdinata19@gmail.com', '5', 'Perempuan', '1982-04-19', NULL, '2025-07-10 08:14:57', '2025-07-10 08:14:57'),
(4, 357322090027, 'Didiet Ananto Widodo', 31, 'didietwidodo33@gmail.com', '3', 'Perempuan', '1964-03-21', NULL, '2025-07-10 08:14:58', '2025-07-10 08:14:58'),
(5, 357322090007, 'Shindy Ayu Widyaswara', 33, 'shindyayu.widyaswara@gmail.com', '5', 'Perempuan', '1993-10-26', NULL, '2025-07-10 08:16:42', '2025-07-10 08:16:42'),
(6, 357322090013, 'Hilda Seskowati', 34, 'hildayasmin2211@gmail.com', '3', 'Perempuan', '1991-06-18', NULL, '2025-07-10 08:16:44', '2025-07-10 08:16:44'),
(7, 357322090177, 'Zulfian Arif', 35, 'zulfian.arif@gmail.com', '5', 'Perempuan', '1968-02-29', NULL, '2025-07-10 08:16:44', '2025-07-10 08:16:44'),
(8, 357323070003, 'Arif Hambali', 36, 'hambali.arif@gmail.com', '5', 'Perempuan', '1983-10-28', NULL, '2025-07-10 08:16:45', '2025-07-10 08:16:45'),
(9, 357323110322, 'Tri Wahyu Utomo', 37, 'my_hygeva@yahoo.com', '3', 'Perempuan', '1970-01-01', NULL, '2025-07-10 08:16:45', '2025-07-10 08:16:45'),
(10, 839724452, 'Dessy Anggrainy', 38, 'dessy.anggrainy87@gmail.com', '3', 'Perempuan', '1987-01-31', NULL, '2025-07-10 08:22:06', '2025-07-10 08:22:06'),
(11, 839814778, 'KUSMILAH', 39, 'Bundakus42300@gmail.com', '3', 'Perempuan', '1973-07-24', NULL, '2025-07-10 08:22:07', '2025-07-10 08:22:07'),
(12, 839804489, 'TITIEK TRISOESILOWATI', 40, 'ndhoxsusie@gmail.com', '5', 'Perempuan', '1970-01-01', NULL, '2025-07-10 08:22:07', '2025-07-10 08:22:07'),
(13, 357322090103, 'IKE MARDIANA SADIYAH', 41, 'ikerafi1@gmail.com', '5', 'Perempuan', '1972-09-14', NULL, '2025-07-10 08:25:03', '2025-07-10 08:25:03'),
(14, 357323110133, 'Muhammad Jehan Rabbani', 42, 'mjgila8@gmail.com', '3', 'Perempuan', '2005-04-26', NULL, '2025-07-10 08:29:37', '2025-07-10 08:29:37'),
(15, 357322030004, 'Riyono', 43, 'kopler87@gmail.com', '5', 'Perempuan', '1979-04-22', NULL, '2025-07-10 08:29:38', '2025-07-10 08:29:38'),
(16, 357322030023, 'Ardhea Citra Dhamayanty', 44, 'ardheacitraa2807@gmail.com', '5', 'Perempuan', '1997-07-28', NULL, '2025-07-10 08:37:01', '2025-07-10 08:37:01'),
(17, 357322030026, 'Mila Nurjana', 45, 'aisyah.nurjanah116@gmail.com', '3', 'Perempuan', '1985-04-16', NULL, '2025-07-10 08:37:02', '2025-07-10 08:37:02'),
(18, 357322030032, 'Ila Nur Chasanah', 46, 'ilanurchasanah5@gmail.com', '3', 'Perempuan', '1970-01-01', NULL, '2025-07-10 08:37:02', '2025-07-10 08:37:02'),
(19, 357322050019, 'Khoirunisa Sukma Hadi', 47, 'sukma.khoirunisa21@gmail.com', '5', 'Perempuan', '1970-01-01', NULL, '2025-07-10 08:37:02', '2025-07-10 08:37:02'),
(20, 357322100017, 'Yessy Tri Hardiyanti', 48, 'hardiyantiyessy@gmail.com', '5', 'Perempuan', '1970-01-01', NULL, '2025-07-10 08:37:03', '2025-07-10 08:37:03'),
(21, 357322090185, 'Indah Wahyuningsih', 49, 'sbw.mlg@gmail.com', '6', 'Perempuan', '1970-01-01', NULL, '2025-07-10 08:37:03', '2025-07-10 08:37:03'),
(22, 357323110001, 'Sharon Mahar Tanjung', 50, 'sharonmahartn@gmail.com', '5', 'Perempuan', '1970-01-01', NULL, '2025-07-10 08:37:04', '2025-07-10 08:37:04'),
(23, 357322090014, 'Arry Puspitasari', 51, 'trust.indigo@gmail.com', '3', 'Perempuan', '1970-01-01', NULL, '2025-07-10 08:38:26', '2025-07-10 08:38:26'),
(24, 357322020041, 'Rujiyani', 52, 'rujiyaniyani@gmail.com', '3', 'Perempuan', '1972-07-19', NULL, '2025-07-10 08:44:15', '2025-07-10 08:44:15'),
(25, 357323030009, 'Theresiana Septioningrum', 53, 'theresiana07@gmail.com', '5', 'Perempuan', '1970-01-01', NULL, '2025-07-10 08:44:17', '2025-07-10 08:44:17'),
(26, 357322030009, 'Puji Rahayu', 54, 'doublesix664@gmail.com', '5', 'Perempuan', '1977-11-18', NULL, '2025-07-10 08:46:37', '2025-07-10 08:46:37'),
(27, 357322090108, 'Era Dhani Restari', 55, 'Eradhani588@gmail.com', '3', 'Perempuan', '1976-06-22', NULL, '2025-07-10 08:46:38', '2025-07-10 08:46:38'),
(28, 357322030029, 'ROOS PANDAN WANGI(WIWIN).SH', 56, 'roospandanwangi24@gmail.com', '5', 'Perempuan', '1973-04-26', NULL, '2025-07-10 08:46:38', '2025-07-10 08:46:38'),
(29, 357322030044, 'Farida Achadyah', 57, 'faridachadyah1309@gmail.com', '5', 'Perempuan', '1970-09-13', NULL, '2025-07-10 08:46:39', '2025-07-10 08:46:39'),
(30, 357322030059, 'Aris Sugiarti', 58, 'mbkaris80@gmail.com', '3', 'Perempuan', '1970-01-01', NULL, '2025-07-10 08:46:39', '2025-07-10 08:46:39'),
(31, 357322090163, 'YUNIAR PANDANSARI', 59, 'yuniarpandansari67@gmail.com', '5', 'Perempuan', '1967-06-15', NULL, '2025-07-10 08:46:39', '2025-07-10 08:46:39'),
(32, 357322100100, 'Rodiyah', 60, 'rodiyah44307@gmail.com', '4', 'Perempuan', '1970-01-01', NULL, '2025-07-10 08:46:40', '2025-07-10 08:46:40'),
(33, 357322090417, 'Dewi Ayu Indriani', 61, 'dewia4101@gmail.com', '5', 'Perempuan', '1982-02-22', NULL, '2025-07-10 08:46:40', '2025-07-10 08:46:40'),
(34, 357323110167, 'HERTIN SULISTYO RINI', 62, 'hertinrini59@gmail.com', '3', 'Perempuan', '1979-09-16', NULL, '2025-07-10 08:46:41', '2025-07-10 08:46:41'),
(35, 357322100385, 'Eka Astuti', 63, 'ekaastuti1980@gmail.com', '5', 'Perempuan', '1970-01-01', NULL, '2025-07-10 08:50:12', '2025-07-10 08:50:12'),
(36, 357322090428, 'HENI KUSUMAWATI', 64, 'Henikusuma29@gmail.com', '3', 'Perempuan', '1970-01-01', NULL, '2025-07-10 08:50:12', '2025-07-10 08:50:12'),
(37, 357323030003, 'Dicky Ramadhan', 65, 'Dickyrammm97@gmail.com', '3', 'Perempuan', '1970-01-01', NULL, '2025-07-10 08:50:13', '2025-07-10 08:50:13'),
(38, 357322030003, 'Juwariyah', 66, 'e85riasuparji@gmail.com', '4', 'Perempuan', '1970-01-01', NULL, '2025-07-10 08:57:25', '2025-07-10 08:57:25'),
(39, 357322020022, 'Muhammad Handy Rizki Prasetyo', 67, 'tunjungsekarppl@gmail.com', '3', 'Perempuan', '1970-01-01', NULL, '2025-07-10 08:57:25', '2025-07-10 08:57:25'),
(40, 357322010053, 'TEJO WIRAWAN', 68, 'tejowirawan1@gmail.com', '5', 'Perempuan', '1970-01-01', NULL, '2025-07-10 08:57:26', '2025-07-10 08:57:26'),
(41, 357322020080, 'Agus Dwi Prasetyo', 69, 'agusdwiprasetyo07@gmail.com', '5', 'Perempuan', '1970-01-01', NULL, '2025-07-10 08:57:26', '2025-07-10 08:57:26'),
(42, 357322030031, 'Fenny Gunawan', 70, 'fennymarsudi@gmail.com', '3', 'Perempuan', '1970-01-01', NULL, '2025-07-10 08:57:27', '2025-07-10 08:57:27'),
(43, 357322100321, 'ERNAWATI', 71, 'ernaina6@gmail.com', '3', 'Perempuan', '1970-01-01', NULL, '2025-07-10 08:57:27', '2025-07-10 08:57:27'),
(44, 357323030002, 'Maulid Diyah Kholis Saputri', 72, 'diyahmaulid234@gmail.com', '3', 'Perempuan', '1970-01-01', NULL, '2025-07-10 08:57:28', '2025-07-10 08:57:28'),
(45, 840744433, 'Nurfuady Rafi\' Alfauznursy', 73, 'nurfuady1@gmail.com', '5', 'Perempuan', '1970-01-01', NULL, '2025-07-10 09:09:07', '2025-07-10 09:09:07'),
(46, 357323110096, 'TALITHA ALIFAH', 74, 'talithaalifah99@gmail.com', '5', 'Perempuan', '1970-01-01', NULL, '2025-07-10 09:30:42', '2025-07-10 09:30:42'),
(47, 357322030027, 'ALFIATUL KHAMIMAH', 75, 'alfi.sewing@gmail.com', '3', 'Perempuan', '1988-10-21', NULL, '2025-07-10 09:30:43', '2025-07-10 09:30:43'),
(48, 357322030035, 'Arinda Putri Dewi', 76, 'dewiarindaputri3@gmail.com', '5', 'Perempuan', '1993-03-22', NULL, '2025-07-10 09:30:43', '2025-07-10 09:30:43'),
(49, 839734549, 'Aulia Rachman', 77, 'armand.m.513@gmail.com', '3', 'Perempuan', '1970-01-01', NULL, '2025-07-10 09:33:34', '2025-07-10 09:33:34'),
(50, 839764446, 'Catur Sandy Cahyono', 78, 'catursandy70@gmail.com', '5', 'Perempuan', '1970-01-01', NULL, '2025-07-10 09:33:34', '2025-07-10 09:33:34'),
(51, 839724505, 'Feri Achmad Ardiansyah', 79, 'feriachmad683@gmail.com', '4', 'Perempuan', '2000-08-23', NULL, '2025-07-10 09:33:35', '2025-07-10 09:33:35');

-- --------------------------------------------------------

--
-- Table structure for table `mitra_teladans`
--

CREATE TABLE `mitra_teladans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mitra_id` bigint(20) UNSIGNED NOT NULL,
  `team_id` bigint(20) UNSIGNED NOT NULL,
  `year` year(4) NOT NULL,
  `quarter` tinyint(3) UNSIGNED NOT NULL,
  `avg_rating_1` decimal(4,2) DEFAULT NULL,
  `avg_rating_2` decimal(4,2) DEFAULT NULL,
  `surveys_count` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `status_phase_2` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mitra_teladans`
--

INSERT INTO `mitra_teladans` (`id`, `mitra_id`, `team_id`, `year`, `quarter`, `avg_rating_1`, `avg_rating_2`, `surveys_count`, `status_phase_2`, `created_at`, `updated_at`) VALUES
(1, 2, 4, '2025', 2, 4.00, 9.07, 7, 1, '2025-07-10 08:34:41', '2025-07-13 09:28:27'),
(2, 21, 7, '2025', 2, 5.00, 8.64, 3, 1, '2025-07-10 08:42:03', '2025-07-13 09:28:36'),
(3, 2, 2, '2025', 2, 4.67, 9.02, 1, 1, '2025-07-10 09:23:29', '2025-07-13 09:28:22'),
(4, 36, 3, '2025', 2, 4.44, 8.88, 9, 1, '2025-07-10 09:23:32', '2025-07-13 09:28:25'),
(5, 50, 6, '2025', 2, 5.00, 9.10, 1, 1, '2025-07-10 09:33:44', '2025-07-13 09:28:33'),
(6, 13, 5, '2025', 2, 4.67, 8.82, 1, 1, '2025-07-10 09:54:12', '2025-07-13 09:28:30');

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 2),
(2, 'App\\Models\\User', 3),
(2, 'App\\Models\\User', 4),
(2, 'App\\Models\\User', 5),
(2, 'App\\Models\\User', 6),
(2, 'App\\Models\\User', 7),
(2, 'App\\Models\\User', 8),
(2, 'App\\Models\\User', 9),
(2, 'App\\Models\\User', 10),
(2, 'App\\Models\\User', 11),
(2, 'App\\Models\\User', 12),
(2, 'App\\Models\\User', 13),
(2, 'App\\Models\\User', 15),
(2, 'App\\Models\\User', 16),
(2, 'App\\Models\\User', 17),
(2, 'App\\Models\\User', 18),
(2, 'App\\Models\\User', 19),
(2, 'App\\Models\\User', 20),
(2, 'App\\Models\\User', 21),
(2, 'App\\Models\\User', 22),
(2, 'App\\Models\\User', 23),
(2, 'App\\Models\\User', 24),
(2, 'App\\Models\\User', 25),
(2, 'App\\Models\\User', 26),
(2, 'App\\Models\\User', 27),
(2, 'App\\Models\\User', 28),
(2, 'App\\Models\\User', 80),
(3, 'App\\Models\\User', 3),
(3, 'App\\Models\\User', 4),
(3, 'App\\Models\\User', 5),
(3, 'App\\Models\\User', 6),
(3, 'App\\Models\\User', 21),
(5, 'App\\Models\\User', 24);

-- --------------------------------------------------------

--
-- Table structure for table `nilai1s`
--

CREATE TABLE `nilai1s` (
  `transaction_id` bigint(20) UNSIGNED NOT NULL,
  `aspek1` tinyint(3) UNSIGNED NOT NULL,
  `aspek2` tinyint(3) UNSIGNED NOT NULL,
  `aspek3` tinyint(3) UNSIGNED NOT NULL,
  `rerata` decimal(5,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `nilai1s`
--

INSERT INTO `nilai1s` (`transaction_id`, `aspek1`, `aspek2`, `aspek3`, `rerata`, `created_at`, `updated_at`) VALUES
(2, 4, 4, 4, 4.00, '2025-07-10 08:14:57', '2025-07-10 08:14:57'),
(3, 4, 4, 4, 4.00, '2025-07-10 08:14:57', '2025-07-10 08:14:57'),
(4, 4, 4, 4, 4.00, '2025-07-10 08:14:58', '2025-07-10 08:14:58'),
(1, 4, 4, 4, 4.00, '2025-07-10 08:14:58', '2025-07-10 08:14:58'),
(5, 4, 4, 4, 4.00, '2025-07-10 08:16:42', '2025-07-10 08:16:42'),
(6, 4, 4, 4, 4.00, '2025-07-10 08:16:42', '2025-07-10 08:16:42'),
(7, 4, 4, 4, 4.00, '2025-07-10 08:16:43', '2025-07-10 08:16:43'),
(8, 4, 4, 4, 4.00, '2025-07-10 08:16:43', '2025-07-10 08:16:43'),
(9, 4, 4, 4, 4.00, '2025-07-10 08:16:44', '2025-07-10 08:16:44'),
(10, 4, 4, 4, 4.00, '2025-07-10 08:16:44', '2025-07-10 08:16:44'),
(11, 4, 4, 4, 4.00, '2025-07-10 08:16:44', '2025-07-10 08:16:44'),
(12, 4, 4, 4, 4.00, '2025-07-10 08:16:45', '2025-07-10 08:16:45'),
(13, 4, 4, 4, 4.00, '2025-07-10 08:16:45', '2025-07-10 08:16:45'),
(14, 4, 4, 4, 4.00, '2025-07-10 08:19:42', '2025-07-10 08:19:42'),
(15, 4, 4, 4, 4.00, '2025-07-10 08:19:42', '2025-07-10 08:19:42'),
(16, 4, 4, 4, 4.00, '2025-07-10 08:19:43', '2025-07-10 08:19:43'),
(17, 4, 4, 4, 4.00, '2025-07-10 08:19:43', '2025-07-10 08:19:43'),
(18, 4, 4, 4, 4.00, '2025-07-10 08:19:43', '2025-07-10 08:19:43'),
(19, 4, 4, 4, 4.00, '2025-07-10 08:19:44', '2025-07-10 08:19:44'),
(20, 4, 4, 4, 4.00, '2025-07-10 08:19:44', '2025-07-10 08:19:44'),
(21, 4, 4, 4, 4.00, '2025-07-10 08:19:44', '2025-07-10 08:19:44'),
(22, 4, 4, 4, 4.00, '2025-07-10 08:19:45', '2025-07-10 08:19:45'),
(23, 4, 4, 4, 4.00, '2025-07-10 08:22:06', '2025-07-10 08:22:06'),
(24, 4, 4, 4, 4.00, '2025-07-10 08:22:06', '2025-07-10 08:22:06'),
(25, 4, 5, 4, 4.33, '2025-07-10 08:22:06', '2025-07-10 08:22:06'),
(26, 4, 4, 4, 4.00, '2025-07-10 08:22:07', '2025-07-10 08:22:07'),
(27, 4, 4, 4, 4.00, '2025-07-10 08:22:07', '2025-07-10 08:22:07'),
(28, 4, 4, 4, 4.00, '2025-07-10 08:22:08', '2025-07-10 08:22:08'),
(29, 4, 4, 4, 4.00, '2025-07-10 08:23:37', '2025-07-10 08:23:37'),
(30, 4, 4, 4, 4.00, '2025-07-10 08:25:02', '2025-07-10 08:25:02'),
(31, 4, 4, 4, 4.00, '2025-07-10 08:25:02', '2025-07-10 08:25:02'),
(32, 4, 4, 4, 4.00, '2025-07-10 08:25:03', '2025-07-10 08:25:03'),
(33, 4, 4, 4, 4.00, '2025-07-10 08:25:03', '2025-07-10 08:25:03'),
(34, 4, 4, 4, 4.00, '2025-07-10 08:28:04', '2025-07-10 08:28:04'),
(35, 4, 4, 4, 4.00, '2025-07-10 08:28:04', '2025-07-10 08:28:04'),
(36, 4, 4, 4, 4.00, '2025-07-10 08:28:04', '2025-07-10 08:28:04'),
(37, 4, 4, 4, 4.00, '2025-07-10 08:29:37', '2025-07-10 08:29:37'),
(38, 4, 4, 4, 4.00, '2025-07-10 08:29:37', '2025-07-10 08:29:37'),
(39, 4, 4, 4, 4.00, '2025-07-10 08:29:37', '2025-07-10 08:29:37'),
(40, 4, 4, 4, 4.00, '2025-07-10 08:29:38', '2025-07-10 08:29:38'),
(41, 4, 4, 4, 4.00, '2025-07-10 08:29:38', '2025-07-10 08:29:38'),
(42, 4, 4, 4, 4.00, '2025-07-10 08:29:38', '2025-07-10 08:29:38'),
(43, 4, 4, 4, 4.00, '2025-07-10 08:29:39', '2025-07-10 08:29:39'),
(44, 4, 4, 4, 4.00, '2025-07-10 08:29:39', '2025-07-10 08:29:39'),
(45, 4, 4, 4, 4.00, '2025-07-10 08:29:39', '2025-07-10 08:29:39'),
(46, 4, 4, 4, 4.00, '2025-07-10 08:29:40', '2025-07-10 08:29:40'),
(47, 4, 4, 4, 4.00, '2025-07-10 08:29:40', '2025-07-10 08:29:40'),
(48, 4, 4, 4, 4.00, '2025-07-10 08:29:40', '2025-07-10 08:29:40'),
(49, 4, 4, 4, 4.00, '2025-07-10 08:31:02', '2025-07-10 08:31:02'),
(50, 4, 4, 4, 4.00, '2025-07-10 08:31:03', '2025-07-10 08:31:03'),
(51, 5, 5, 5, 5.00, '2025-07-10 08:37:01', '2025-07-10 08:37:01'),
(52, 4, 5, 5, 4.67, '2025-07-10 08:37:02', '2025-07-10 08:37:02'),
(53, 4, 5, 5, 4.67, '2025-07-10 08:37:02', '2025-07-10 08:37:02'),
(54, 5, 4, 5, 4.67, '2025-07-10 08:37:02', '2025-07-10 08:37:02'),
(55, 5, 4, 5, 4.67, '2025-07-10 08:37:03', '2025-07-10 08:37:03'),
(56, 5, 5, 5, 5.00, '2025-07-10 08:37:03', '2025-07-10 08:37:03'),
(57, 5, 4, 5, 4.67, '2025-07-10 08:37:04', '2025-07-10 08:37:04'),
(58, 5, 4, 5, 4.67, '2025-07-10 08:38:25', '2025-07-10 08:38:25'),
(59, 5, 5, 4, 4.67, '2025-07-10 08:38:25', '2025-07-10 08:38:25'),
(60, 5, 4, 5, 4.67, '2025-07-10 08:38:26', '2025-07-10 08:38:26'),
(61, 5, 4, 4, 4.33, '2025-07-10 08:38:26', '2025-07-10 08:38:26'),
(62, 5, 5, 5, 5.00, '2025-07-10 08:38:27', '2025-07-10 08:38:27'),
(63, 5, 5, 5, 5.00, '2025-07-10 08:38:27', '2025-07-10 08:38:27'),
(64, 5, 5, 5, 5.00, '2025-07-10 08:38:28', '2025-07-10 08:38:28'),
(65, 5, 5, 5, 5.00, '2025-07-10 08:40:33', '2025-07-10 08:40:33'),
(66, 5, 4, 5, 4.67, '2025-07-10 08:40:34', '2025-07-10 08:40:34'),
(67, 5, 5, 5, 5.00, '2025-07-10 08:40:34', '2025-07-10 08:40:34'),
(68, 5, 4, 5, 4.67, '2025-07-10 08:40:34', '2025-07-10 08:40:34'),
(69, 4, 5, 5, 4.67, '2025-07-10 08:40:35', '2025-07-10 08:40:35'),
(70, 4, 5, 5, 4.67, '2025-07-10 08:40:35', '2025-07-10 08:40:35'),
(71, 5, 5, 5, 5.00, '2025-07-10 08:41:53', '2025-07-10 08:41:53'),
(72, 5, 5, 5, 5.00, '2025-07-10 08:41:53', '2025-07-10 08:41:53'),
(73, 5, 5, 5, 5.00, '2025-07-10 08:41:53', '2025-07-10 08:41:53'),
(74, 5, 4, 5, 4.67, '2025-07-10 08:44:15', '2025-07-10 08:44:15'),
(75, 4, 5, 5, 4.67, '2025-07-10 08:44:15', '2025-07-10 08:44:15'),
(76, 4, 5, 4, 4.33, '2025-07-10 08:44:15', '2025-07-10 08:44:15'),
(77, 4, 4, 4, 4.00, '2025-07-10 08:44:16', '2025-07-10 08:44:16'),
(78, 4, 5, 5, 4.67, '2025-07-10 08:44:16', '2025-07-10 08:44:16'),
(79, 4, 4, 4, 4.00, '2025-07-10 08:44:17', '2025-07-10 08:44:17'),
(80, 4, 4, 5, 4.33, '2025-07-10 08:46:37', '2025-07-10 08:46:37'),
(81, 4, 4, 4, 4.00, '2025-07-10 08:46:38', '2025-07-10 08:46:38'),
(82, 4, 5, 4, 4.33, '2025-07-10 08:46:38', '2025-07-10 08:46:38'),
(83, 4, 4, 5, 4.33, '2025-07-10 08:46:39', '2025-07-10 08:46:39'),
(84, 4, 5, 4, 4.33, '2025-07-10 08:46:39', '2025-07-10 08:46:39'),
(85, 4, 5, 5, 4.67, '2025-07-10 08:46:39', '2025-07-10 08:46:39'),
(86, 4, 4, 4, 4.00, '2025-07-10 08:46:40', '2025-07-10 08:46:40'),
(87, 4, 4, 4, 4.00, '2025-07-10 08:46:40', '2025-07-10 08:46:40'),
(88, 4, 4, 4, 4.00, '2025-07-10 08:46:40', '2025-07-10 08:46:40'),
(89, 4, 4, 4, 4.00, '2025-07-10 08:46:41', '2025-07-10 08:46:41'),
(91, 5, 5, 5, 5.00, '2025-07-10 08:50:11', '2025-07-10 08:50:11'),
(92, 5, 5, 5, 5.00, '2025-07-10 08:50:11', '2025-07-10 08:50:11'),
(93, 5, 5, 5, 5.00, '2025-07-10 08:50:12', '2025-07-10 08:50:12'),
(94, 5, 5, 5, 5.00, '2025-07-10 08:50:12', '2025-07-10 08:50:12'),
(95, 5, 4, 5, 4.67, '2025-07-10 08:50:12', '2025-07-10 08:50:12'),
(96, 5, 4, 5, 4.67, '2025-07-10 08:50:13', '2025-07-10 08:50:13'),
(97, 5, 4, 5, 4.67, '2025-07-10 08:51:43', '2025-07-10 08:51:43'),
(98, 5, 5, 5, 5.00, '2025-07-10 08:51:43', '2025-07-10 08:51:43'),
(99, 5, 5, 5, 5.00, '2025-07-10 08:57:25', '2025-07-10 08:57:25'),
(100, 5, 4, 5, 4.67, '2025-07-10 08:57:25', '2025-07-10 08:57:25'),
(101, 5, 5, 5, 5.00, '2025-07-10 08:57:25', '2025-07-10 08:57:25'),
(102, 5, 5, 5, 5.00, '2025-07-10 08:57:26', '2025-07-10 08:57:26'),
(103, 5, 4, 4, 4.33, '2025-07-10 08:57:26', '2025-07-10 08:57:26'),
(104, 5, 5, 5, 5.00, '2025-07-10 08:57:27', '2025-07-10 08:57:27'),
(105, 5, 5, 5, 5.00, '2025-07-10 08:57:27', '2025-07-10 08:57:27'),
(106, 5, 5, 5, 5.00, '2025-07-10 08:57:27', '2025-07-10 08:57:27'),
(107, 5, 4, 4, 4.33, '2025-07-10 08:57:28', '2025-07-10 08:57:28'),
(108, 5, 4, 4, 4.33, '2025-07-10 08:57:28', '2025-07-10 08:57:28'),
(109, 5, 4, 4, 4.33, '2025-07-10 08:58:48', '2025-07-10 08:58:48'),
(110, 5, 4, 4, 4.33, '2025-07-10 08:58:48', '2025-07-10 08:58:48'),
(111, 5, 5, 5, 5.00, '2025-07-10 08:58:49', '2025-07-10 08:58:49'),
(112, 5, 4, 4, 4.33, '2025-07-10 08:58:49', '2025-07-10 08:58:49'),
(113, 5, 4, 4, 4.33, '2025-07-10 08:58:50', '2025-07-10 08:58:50'),
(114, 5, 5, 5, 5.00, '2025-07-10 08:58:51', '2025-07-10 08:58:51'),
(115, 5, 4, 4, 4.33, '2025-07-10 08:58:51', '2025-07-10 08:58:51'),
(116, 5, 5, 5, 5.00, '2025-07-10 09:01:33', '2025-07-10 09:01:33'),
(117, 5, 5, 5, 5.00, '2025-07-10 09:01:33', '2025-07-10 09:01:33'),
(118, 5, 4, 4, 4.33, '2025-07-10 09:01:33', '2025-07-10 09:01:33'),
(119, 5, 5, 5, 5.00, '2025-07-10 09:01:34', '2025-07-10 09:01:34'),
(120, 5, 4, 4, 4.33, '2025-07-10 09:01:34', '2025-07-10 09:01:34'),
(121, 5, 4, 4, 4.33, '2025-07-10 09:09:07', '2025-07-10 09:09:07'),
(122, 5, 4, 5, 4.67, '2025-07-10 09:11:17', '2025-07-10 09:11:17'),
(123, 5, 4, 4, 4.33, '2025-07-10 09:12:23', '2025-07-10 09:12:23'),
(124, 5, 5, 5, 5.00, '2025-07-10 09:18:38', '2025-07-10 09:18:38'),
(125, 5, 4, 4, 4.33, '2025-07-10 09:18:39', '2025-07-10 09:18:39'),
(126, 5, 4, 4, 4.33, '2025-07-10 09:20:00', '2025-07-10 09:20:00'),
(127, 5, 4, 4, 4.33, '2025-07-10 09:21:58', '2025-07-10 09:21:58'),
(128, 5, 5, 5, 5.00, '2025-07-10 09:22:49', '2025-07-10 09:22:49'),
(129, 5, 5, 5, 5.00, '2025-07-10 09:22:50', '2025-07-10 09:22:50'),
(130, 5, 5, 5, 5.00, '2025-07-10 09:22:50', '2025-07-10 09:22:50'),
(131, 5, 4, 4, 4.33, '2025-07-10 09:22:50', '2025-07-10 09:22:50'),
(132, 5, 5, 5, 5.00, '2025-07-10 09:29:08', '2025-07-10 09:29:08'),
(133, 5, 5, 5, 5.00, '2025-07-10 09:29:08', '2025-07-10 09:29:08'),
(134, 5, 5, 4, 4.67, '2025-07-10 09:29:09', '2025-07-10 09:29:09'),
(135, 5, 5, 4, 4.67, '2025-07-10 09:30:42', '2025-07-10 09:30:42'),
(136, 5, 5, 4, 4.67, '2025-07-10 09:30:42', '2025-07-10 09:30:42'),
(137, 5, 4, 5, 4.67, '2025-07-10 09:30:43', '2025-07-10 09:30:43'),
(138, 5, 5, 5, 5.00, '2025-07-10 09:30:43', '2025-07-10 09:30:43'),
(139, 5, 5, 5, 5.00, '2025-07-10 09:30:44', '2025-07-10 09:30:44'),
(140, 5, 5, 4, 4.67, '2025-07-10 09:33:34', '2025-07-10 09:33:34'),
(141, 5, 5, 5, 5.00, '2025-07-10 09:33:35', '2025-07-10 09:33:35'),
(142, 4, 5, 5, 4.67, '2025-07-10 09:33:35', '2025-07-10 09:33:35');

-- --------------------------------------------------------

--
-- Table structure for table `nilai2s`
--

CREATE TABLE `nilai2s` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mitra_teladan_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `aspek1` int(11) NOT NULL,
  `aspek2` int(11) NOT NULL,
  `aspek3` int(11) NOT NULL,
  `aspek4` int(11) NOT NULL,
  `aspek5` int(11) NOT NULL,
  `aspek6` int(11) NOT NULL,
  `aspek7` int(11) NOT NULL,
  `aspek8` int(11) NOT NULL,
  `aspek9` int(11) NOT NULL,
  `aspek10` int(11) NOT NULL,
  `rerata` float NOT NULL,
  `is_final` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `nilai2s`
--

INSERT INTO `nilai2s` (`id`, `mitra_teladan_id`, `user_id`, `aspek1`, `aspek2`, `aspek3`, `aspek4`, `aspek5`, `aspek6`, `aspek7`, `aspek8`, `aspek9`, `aspek10`, `rerata`, `is_final`, `created_at`, `updated_at`) VALUES
(1, 3, 11, 10, 10, 9, 10, 10, 10, 9, 10, 10, 10, 9.8, 1, '2025-07-10 16:19:37', '2025-07-10 16:19:37'),
(2, 3, 24, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 1, '2025-07-11 00:38:27', '2025-07-13 10:01:06'),
(3, 4, 24, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 1, '2025-07-11 00:52:18', '2025-07-13 09:59:22'),
(4, 1, 24, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 1, '2025-07-11 00:52:35', '2025-07-13 09:58:34'),
(5, 6, 24, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 1, '2025-07-11 00:57:15', '2025-07-13 09:59:48'),
(6, 5, 24, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 1, '2025-07-11 00:57:41', '2025-07-13 10:00:08'),
(7, 2, 24, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 1, '2025-07-11 00:57:59', '2025-07-13 10:00:53'),
(8, 3, 17, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 0, '2025-07-11 06:22:18', '2025-07-11 06:22:18'),
(9, 4, 17, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 0, '2025-07-11 06:22:37', '2025-07-11 06:22:37'),
(10, 1, 17, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 0, '2025-07-11 06:23:03', '2025-07-11 06:23:03'),
(11, 6, 17, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 0, '2025-07-11 06:23:23', '2025-07-11 06:23:23'),
(12, 5, 17, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 0, '2025-07-11 06:23:39', '2025-07-11 06:23:39'),
(13, 2, 17, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 0, '2025-07-11 06:23:58', '2025-07-11 06:23:58'),
(14, 3, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 0, '2025-07-11 06:34:53', '2025-07-11 06:34:53'),
(15, 3, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 0, '2025-07-11 06:34:56', '2025-07-11 06:34:56'),
(16, 4, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 0, '2025-07-11 06:35:28', '2025-07-11 06:35:28'),
(17, 1, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 0, '2025-07-11 06:36:02', '2025-07-11 06:36:02'),
(18, 1, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 0, '2025-07-11 06:36:05', '2025-07-11 06:36:05'),
(19, 5, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 0, '2025-07-11 06:36:35', '2025-07-11 06:36:35'),
(20, 5, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 0, '2025-07-11 06:36:39', '2025-07-11 06:36:39'),
(21, 2, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 0, '2025-07-11 06:37:02', '2025-07-11 06:37:02'),
(22, 6, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 0, '2025-07-11 06:37:30', '2025-07-11 06:37:30'),
(23, 3, 21, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 1, '2025-07-11 06:38:35', '2025-07-13 10:03:15'),
(24, 4, 21, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 1, '2025-07-11 06:38:50', '2025-07-13 10:02:56'),
(25, 1, 21, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 0, '2025-07-11 06:39:09', '2025-07-13 10:04:07'),
(26, 6, 21, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 0, '2025-07-11 06:39:25', '2025-07-13 10:04:38'),
(27, 5, 21, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 0, '2025-07-11 06:39:38', '2025-07-13 10:05:37'),
(28, 2, 21, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 0, '2025-07-11 06:39:51', '2025-07-13 10:06:16'),
(29, 3, 27, 10, 10, 10, 10, 10, 10, 10, 9, 10, 10, 9.9, 0, '2025-07-11 06:40:37', '2025-07-11 06:40:37'),
(30, 3, 27, 10, 10, 10, 10, 10, 10, 10, 9, 10, 10, 9.9, 1, '2025-07-11 06:40:39', '2025-07-11 06:40:39'),
(31, 4, 27, 8, 9, 9, 9, 9, 9, 9, 9, 9, 9, 8.9, 1, '2025-07-11 06:41:03', '2025-07-11 06:41:03'),
(32, 1, 27, 9, 10, 10, 10, 10, 10, 10, 9, 10, 10, 9.8, 0, '2025-07-11 06:41:30', '2025-07-11 06:41:30'),
(33, 6, 27, 9, 10, 10, 9, 9, 9, 9, 9, 9, 9, 9.2, 0, '2025-07-11 06:41:55', '2025-07-11 06:41:55'),
(34, 5, 27, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 1, '2025-07-11 06:42:25', '2025-07-11 06:42:25'),
(35, 2, 27, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 0, '2025-07-11 06:42:46', '2025-07-11 06:42:46'),
(36, 2, 27, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 1, '2025-07-11 06:42:50', '2025-07-11 06:42:50'),
(37, 3, 12, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 1, '2025-07-11 06:43:09', '2025-07-11 06:43:09'),
(38, 4, 12, 8, 9, 9, 9, 8, 8, 9, 8, 8, 8, 8.4, 1, '2025-07-11 06:45:11', '2025-07-11 06:45:11'),
(39, 1, 12, 9, 8, 9, 9, 9, 9, 9, 9, 8, 8, 8.7, 1, '2025-07-11 06:45:57', '2025-07-11 06:45:57'),
(40, 6, 12, 8, 9, 9, 8, 8, 8, 8, 9, 8, 8, 8.3, 1, '2025-07-11 06:47:32', '2025-07-11 06:47:32'),
(41, 5, 12, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 0, '2025-07-11 06:48:19', '2025-07-11 06:48:19'),
(42, 2, 12, 9, 9, 9, 10, 10, 9, 9, 10, 9, 9, 9.3, 1, '2025-07-11 06:49:08', '2025-07-11 06:49:08'),
(43, 4, 11, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 0, '2025-07-11 09:03:08', '2025-07-11 09:03:08'),
(44, 1, 11, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 1, '2025-07-11 09:03:27', '2025-07-11 09:03:27'),
(45, 6, 11, 8, 8, 8, 8, 8, 8, 8, 8, 9, 9, 8.2, 0, '2025-07-11 09:03:35', '2025-07-11 09:03:35'),
(46, 5, 11, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 1, '2025-07-11 09:03:45', '2025-07-11 09:03:45'),
(47, 2, 11, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 1, '2025-07-11 09:04:01', '2025-07-11 09:04:01'),
(48, 3, 4, 9, 9, 9, 9, 9, 9, 8, 9, 9, 9, 8.9, 0, '2025-07-11 09:33:48', '2025-07-11 09:33:48'),
(49, 4, 4, 9, 8, 9, 8, 9, 8, 9, 8, 9, 8, 8.5, 0, '2025-07-11 09:34:21', '2025-07-11 09:34:21'),
(50, 3, 19, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 0, '2025-07-11 09:34:25', '2025-07-11 09:34:25'),
(51, 5, 23, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 0, '2025-07-11 09:34:31', '2025-07-11 09:34:31'),
(52, 1, 4, 8, 9, 8, 9, 9, 9, 9, 9, 9, 9, 8.8, 0, '2025-07-11 09:34:50', '2025-07-11 09:34:50'),
(53, 3, 23, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 0, '2025-07-11 09:34:59', '2025-07-11 09:34:59'),
(54, 4, 19, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 1, '2025-07-11 09:35:16', '2025-07-11 09:35:16'),
(55, 6, 4, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 0, '2025-07-11 09:35:24', '2025-07-11 09:35:24'),
(56, 4, 23, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 0, '2025-07-11 09:35:27', '2025-07-11 09:35:27'),
(57, 5, 4, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 0, '2025-07-11 09:35:55', '2025-07-11 09:35:55'),
(58, 1, 19, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 1, '2025-07-11 09:35:56', '2025-07-11 09:35:56'),
(59, 1, 23, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 0, '2025-07-11 09:35:57', '2025-07-11 09:35:57'),
(60, 6, 23, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 0, '2025-07-11 09:36:21', '2025-07-11 09:36:21'),
(61, 2, 4, 9, 8, 9, 8, 9, 8, 9, 8, 9, 8, 8.5, 0, '2025-07-11 09:36:25', '2025-07-11 09:36:25'),
(62, 2, 23, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 0, '2025-07-11 09:36:54', '2025-07-11 09:36:54'),
(63, 6, 19, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 1, '2025-07-11 09:36:54', '2025-07-11 09:36:54'),
(64, 5, 19, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 1, '2025-07-11 09:37:27', '2025-07-11 09:37:27'),
(65, 2, 19, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 1, '2025-07-11 09:37:55', '2025-07-11 09:37:55'),
(66, 3, 15, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 0, '2025-07-11 09:40:29', '2025-07-11 09:40:29'),
(67, 3, 22, 10, 9, 10, 10, 10, 9, 10, 10, 10, 10, 9.8, 1, '2025-07-11 09:41:57', '2025-07-11 09:41:57'),
(68, 4, 22, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 1, '2025-07-11 09:44:09', '2025-07-11 09:44:09'),
(69, 3, 5, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 1, '2025-07-11 09:44:15', '2025-07-11 09:44:15'),
(70, 4, 5, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 1, '2025-07-11 09:44:34', '2025-07-11 09:44:34'),
(71, 1, 5, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 1, '2025-07-11 09:44:57', '2025-07-11 09:44:57'),
(72, 6, 5, 8, 8, 9, 8, 8, 8, 8, 9, 9, 9, 8.4, 1, '2025-07-11 09:45:16', '2025-07-11 09:45:16'),
(73, 6, 22, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 1, '2025-07-11 09:45:28', '2025-07-11 09:45:28'),
(74, 5, 5, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 1, '2025-07-11 09:45:29', '2025-07-11 09:45:29'),
(75, 2, 5, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 1, '2025-07-11 09:45:51', '2025-07-11 09:45:51'),
(76, 5, 22, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 0, '2025-07-11 09:46:19', '2025-07-11 09:46:19'),
(77, 1, 22, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 1, '2025-07-11 09:46:53', '2025-07-11 09:46:53'),
(78, 4, 15, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 0, '2025-07-11 09:47:17', '2025-07-11 09:47:17'),
(79, 2, 22, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 1, '2025-07-11 09:47:25', '2025-07-11 09:47:25'),
(80, 2, 22, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 1, '2025-07-11 09:47:27', '2025-07-11 09:47:27'),
(81, 1, 15, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 0, '2025-07-11 09:47:54', '2025-07-11 09:47:54'),
(82, 6, 15, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 0, '2025-07-11 09:48:28', '2025-07-11 09:48:28'),
(83, 5, 15, 10, 10, 10, 10, 9, 9, 9, 9, 9, 9, 9.4, 0, '2025-07-11 09:49:11', '2025-07-11 09:49:11'),
(84, 2, 15, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 0, '2025-07-11 09:49:46', '2025-07-11 09:49:46'),
(85, 3, 2, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 1, '2025-07-13 00:36:10', '2025-07-13 00:36:10'),
(86, 3, 2, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 1, '2025-07-13 00:36:12', '2025-07-13 00:36:12'),
(87, 4, 2, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 1, '2025-07-13 00:36:26', '2025-07-13 00:36:26'),
(88, 1, 2, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 0, '2025-07-13 00:36:40', '2025-07-13 00:36:40'),
(89, 6, 2, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 1, '2025-07-13 00:36:55', '2025-07-13 00:36:55'),
(90, 6, 2, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 1, '2025-07-13 00:36:57', '2025-07-13 00:36:57'),
(91, 5, 2, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 1, '2025-07-13 00:37:10', '2025-07-13 00:37:10'),
(92, 5, 2, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 1, '2025-07-13 00:37:12', '2025-07-13 00:37:12'),
(93, 2, 2, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 1, '2025-07-13 00:37:25', '2025-07-13 00:37:25'),
(94, 3, 20, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 1, '2025-07-13 06:54:02', '2025-07-13 06:54:02'),
(95, 4, 20, 8, 8, 9, 9, 7, 9, 8, 8, 8, 9, 8.3, 0, '2025-07-13 06:55:53', '2025-07-13 06:55:53'),
(96, 4, 20, 8, 8, 9, 9, 7, 9, 8, 8, 8, 9, 8.3, 1, '2025-07-13 06:55:56', '2025-07-13 06:55:56'),
(97, 1, 20, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 1, '2025-07-13 06:56:33', '2025-07-13 06:56:33'),
(98, 1, 20, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 1, '2025-07-13 06:56:36', '2025-07-13 06:56:36'),
(99, 6, 20, 7, 9, 9, 9, 9, 9, 8, 9, 9, 9, 8.7, 1, '2025-07-13 06:57:38', '2025-07-13 06:57:38'),
(100, 5, 20, 10, 8, 8, 8, 8, 8, 10, 8, 10, 8, 8.6, 1, '2025-07-13 06:58:19', '2025-07-13 06:58:19'),
(101, 2, 20, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 1, '2025-07-13 06:58:48', '2025-07-13 06:58:48'),
(102, 2, 20, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 1, '2025-07-13 06:58:49', '2025-07-13 06:58:49'),
(103, 3, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 1, '2025-07-13 07:00:27', '2025-07-13 07:00:27'),
(104, 4, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 1, '2025-07-13 07:00:53', '2025-07-13 07:00:53'),
(105, 1, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 1, '2025-07-13 07:01:08', '2025-07-13 07:01:08'),
(106, 6, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 1, '2025-07-13 07:01:22', '2025-07-13 07:08:22'),
(107, 5, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 1, '2025-07-13 07:01:37', '2025-07-13 07:01:37'),
(108, 2, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 1, '2025-07-13 07:01:54', '2025-07-13 07:08:45'),
(109, 3, 6, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 1, '2025-07-13 07:10:04', '2025-07-13 07:10:04'),
(110, 4, 6, 9, 10, 10, 10, 10, 10, 9, 10, 9, 10, 9.7, 1, '2025-07-13 07:10:43', '2025-07-13 07:10:43'),
(111, 1, 6, 10, 10, 10, 10, 9, 10, 9, 9, 10, 9, 9.6, 1, '2025-07-13 07:12:23', '2025-07-13 07:12:23'),
(112, 6, 6, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 1, '2025-07-13 07:13:10', '2025-07-13 07:13:10'),
(113, 5, 6, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 1, '2025-07-13 07:13:24', '2025-07-13 07:13:24'),
(114, 2, 6, 9, 9, 10, 10, 10, 10, 9, 9, 9, 9, 9.4, 1, '2025-07-13 07:14:01', '2025-07-13 07:14:01'),
(115, 3, 18, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 1, '2025-07-13 07:15:51', '2025-07-13 07:15:51'),
(116, 4, 18, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 1, '2025-07-13 07:16:04', '2025-07-13 07:16:04'),
(117, 1, 18, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 1, '2025-07-13 07:16:21', '2025-07-13 07:16:21'),
(118, 6, 18, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 1, '2025-07-13 07:16:34', '2025-07-13 07:16:34'),
(119, 5, 18, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 0, '2025-07-13 07:16:48', '2025-07-13 07:16:48'),
(120, 2, 18, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 1, '2025-07-13 07:17:04', '2025-07-13 07:17:04'),
(121, 3, 3, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 1, '2025-07-13 07:19:32', '2025-07-13 07:19:32'),
(122, 4, 3, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 1, '2025-07-13 07:19:50', '2025-07-13 07:19:50'),
(123, 1, 3, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 1, '2025-07-13 07:20:12', '2025-07-13 07:20:12'),
(124, 6, 3, 8, 8, 9, 9, 8, 9, 8, 9, 8, 8, 8.4, 1, '2025-07-13 07:20:41', '2025-07-13 07:20:41'),
(125, 5, 3, 7, 7, 8, 8, 7, 8, 7, 8, 7, 7, 7.4, 1, '2025-07-13 07:21:08', '2025-07-13 07:21:08'),
(126, 2, 3, 9, 10, 10, 10, 10, 10, 10, 10, 10, 9, 9.8, 1, '2025-07-13 07:21:40', '2025-07-13 07:21:40'),
(127, 3, 25, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 1, '2025-07-13 07:22:54', '2025-07-13 07:22:54'),
(128, 4, 25, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 1, '2025-07-13 07:23:13', '2025-07-13 07:23:13'),
(129, 1, 25, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 1, '2025-07-13 07:23:36', '2025-07-13 07:23:36'),
(130, 6, 25, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 1, '2025-07-13 07:24:01', '2025-07-13 07:24:01'),
(131, 5, 25, 8, 8, 9, 9, 9, 9, 9, 8, 9, 9, 8.7, 1, '2025-07-13 07:25:05', '2025-07-13 07:25:05'),
(132, 2, 25, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 1, '2025-07-13 07:25:24', '2025-07-13 07:25:24'),
(133, 3, 10, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 1, '2025-07-13 07:26:32', '2025-07-13 07:26:32'),
(134, 4, 10, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 1, '2025-07-13 07:26:43', '2025-07-13 07:26:43'),
(135, 1, 10, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 1, '2025-07-13 07:26:55', '2025-07-13 07:26:55'),
(136, 6, 10, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 1, '2025-07-13 07:27:08', '2025-07-13 07:27:08'),
(137, 5, 10, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 1, '2025-07-13 07:27:22', '2025-07-13 07:27:22'),
(138, 2, 10, 8, 8, 10, 10, 8, 10, 10, 8, 8, 8, 8.8, 1, '2025-07-13 07:27:52', '2025-07-13 07:27:52'),
(139, 3, 13, 10, 10, 10, 10, 9, 10, 10, 10, 10, 10, 9.9, 1, '2025-07-13 07:28:55', '2025-07-13 07:28:55'),
(140, 4, 13, 9, 10, 10, 10, 10, 10, 10, 10, 10, 10, 9.9, 1, '2025-07-13 07:29:21', '2025-07-13 07:29:21'),
(141, 1, 13, 10, 10, 10, 10, 9, 10, 10, 10, 10, 10, 9.9, 1, '2025-07-13 07:29:42', '2025-07-13 07:29:42'),
(142, 6, 13, 10, 10, 10, 10, 9, 10, 10, 10, 10, 10, 9.9, 1, '2025-07-13 07:30:09', '2025-07-13 07:30:09'),
(143, 5, 13, 10, 10, 10, 10, 9, 10, 10, 10, 10, 10, 9.9, 1, '2025-07-13 07:30:31', '2025-07-13 07:30:31'),
(144, 2, 13, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 1, '2025-07-13 07:30:56', '2025-07-13 07:30:56'),
(145, 3, 26, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 1, '2025-07-13 07:32:21', '2025-07-13 07:32:21'),
(146, 4, 26, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 1, '2025-07-13 07:32:41', '2025-07-13 07:32:41'),
(147, 1, 26, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 1, '2025-07-13 07:33:00', '2025-07-13 07:33:00'),
(148, 6, 26, 9, 9, 10, 10, 10, 10, 10, 10, 10, 9, 9.7, 1, '2025-07-13 07:33:35', '2025-07-13 07:33:35'),
(149, 5, 26, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 1, '2025-07-13 07:33:55', '2025-07-13 07:33:55'),
(150, 2, 26, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 1, '2025-07-13 07:34:15', '2025-07-13 07:34:15'),
(151, 3, 8, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 1, '2025-07-13 07:36:15', '2025-07-13 07:36:15'),
(152, 4, 8, 10, 9, 10, 10, 9, 10, 10, 10, 10, 10, 9.8, 1, '2025-07-13 07:36:53', '2025-07-13 07:36:53'),
(153, 1, 8, 10, 10, 10, 10, 9, 10, 10, 10, 10, 9, 9.8, 1, '2025-07-13 07:37:19', '2025-07-13 07:37:19'),
(154, 6, 8, 9, 10, 10, 10, 10, 10, 10, 10, 10, 9, 9.8, 1, '2025-07-13 07:37:43', '2025-07-13 07:37:43'),
(155, 5, 8, 10, 10, 10, 10, 9, 10, 10, 10, 10, 10, 9.9, 1, '2025-07-13 07:38:07', '2025-07-13 07:38:07'),
(156, 2, 8, 10, 9, 10, 10, 10, 10, 9, 10, 10, 9, 9.7, 1, '2025-07-13 07:38:34', '2025-07-13 07:38:34'),
(157, 3, 28, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 1, '2025-07-13 07:42:27', '2025-07-13 07:42:27'),
(158, 4, 28, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 1, '2025-07-13 07:42:40', '2025-07-13 07:42:40'),
(159, 1, 28, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 1, '2025-07-13 07:42:58', '2025-07-13 07:42:58'),
(160, 6, 28, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 1, '2025-07-13 07:43:13', '2025-07-13 07:43:13'),
(161, 5, 28, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 1, '2025-07-13 07:43:29', '2025-07-13 07:43:29'),
(162, 2, 28, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 1, '2025-07-13 07:43:49', '2025-07-13 07:43:49'),
(163, 3, 16, 9, 9, 9, 9, 9, 9, 9, 10, 9, 9, 9.1, 1, '2025-07-13 07:49:05', '2025-07-13 07:49:05'),
(164, 4, 16, 9, 9, 9, 9, 9, 9, 9, 10, 9, 9, 9.1, 1, '2025-07-13 07:49:20', '2025-07-13 07:49:20'),
(165, 1, 16, 9, 9, 9, 9, 9, 9, 9, 10, 9, 9, 9.1, 1, '2025-07-13 07:49:34', '2025-07-13 07:49:34'),
(166, 6, 16, 9, 9, 9, 9, 9, 9, 9, 10, 9, 9, 9.1, 1, '2025-07-13 07:49:47', '2025-07-13 07:49:47'),
(167, 5, 16, 9, 9, 9, 9, 9, 9, 9, 10, 9, 9, 9.1, 1, '2025-07-13 07:50:00', '2025-07-13 07:50:00'),
(168, 2, 16, 9, 9, 9, 9, 9, 9, 9, 10, 9, 9, 9.1, 1, '2025-07-13 07:50:17', '2025-07-13 07:50:17'),
(169, 3, 80, 9, 9, 10, 10, 10, 10, 9, 10, 10, 10, 9.7, 1, '2025-07-13 09:24:23', '2025-07-13 09:24:23'),
(170, 4, 80, 10, 9, 10, 10, 10, 10, 10, 10, 10, 9, 9.8, 1, '2025-07-13 09:25:29', '2025-07-13 09:25:29'),
(171, 4, 80, 10, 9, 10, 10, 10, 10, 10, 10, 10, 9, 9.8, 1, '2025-07-13 09:25:31', '2025-07-13 09:25:31'),
(172, 1, 80, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 1, '2025-07-13 09:25:53', '2025-07-13 09:25:53'),
(173, 6, 80, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 1, '2025-07-13 09:26:34', '2025-07-13 09:26:34'),
(174, 5, 80, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 1, '2025-07-13 09:27:07', '2025-07-13 09:27:07'),
(175, 2, 80, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 1, '2025-07-13 09:27:30', '2025-07-13 09:27:30');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`data`)),
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `payment_type` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `payment_type`, `created_at`, `updated_at`) VALUES
(1, 'Orang Bulan', NULL, NULL),
(2, 'Dokumen', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'view_book', 'web', '2025-07-10 07:32:11', '2025-07-10 07:32:11'),
(2, 'view_any_book', 'web', '2025-07-10 07:32:11', '2025-07-10 07:32:11'),
(3, 'create_book', 'web', '2025-07-10 07:32:11', '2025-07-10 07:32:11'),
(4, 'update_book', 'web', '2025-07-10 07:32:11', '2025-07-10 07:32:11'),
(5, 'restore_book', 'web', '2025-07-10 07:32:11', '2025-07-10 07:32:11'),
(6, 'restore_any_book', 'web', '2025-07-10 07:32:11', '2025-07-10 07:32:11'),
(7, 'replicate_book', 'web', '2025-07-10 07:32:11', '2025-07-10 07:32:11'),
(8, 'reorder_book', 'web', '2025-07-10 07:32:11', '2025-07-10 07:32:11'),
(9, 'delete_book', 'web', '2025-07-10 07:32:11', '2025-07-10 07:32:11'),
(10, 'delete_any_book', 'web', '2025-07-10 07:32:11', '2025-07-10 07:32:11'),
(11, 'force_delete_book', 'web', '2025-07-10 07:32:11', '2025-07-10 07:32:11'),
(12, 'force_delete_any_book', 'web', '2025-07-10 07:32:11', '2025-07-10 07:32:11'),
(13, 'book:create_book', 'web', '2025-07-10 07:32:11', '2025-07-10 07:32:11'),
(14, 'book:update_book', 'web', '2025-07-10 07:32:11', '2025-07-10 07:32:11'),
(15, 'book:delete_book', 'web', '2025-07-10 07:32:11', '2025-07-10 07:32:11'),
(16, 'book:pagination_book', 'web', '2025-07-10 07:32:11', '2025-07-10 07:32:11'),
(17, 'book:detail_book', 'web', '2025-07-10 07:32:11', '2025-07-10 07:32:11'),
(18, 'view_employee', 'web', '2025-07-10 07:32:11', '2025-07-10 07:32:11'),
(19, 'view_any_employee', 'web', '2025-07-10 07:32:11', '2025-07-10 07:32:11'),
(20, 'create_employee', 'web', '2025-07-10 07:32:11', '2025-07-10 07:32:11'),
(21, 'update_employee', 'web', '2025-07-10 07:32:11', '2025-07-10 07:32:11'),
(22, 'restore_employee', 'web', '2025-07-10 07:32:11', '2025-07-10 07:32:11'),
(23, 'restore_any_employee', 'web', '2025-07-10 07:32:11', '2025-07-10 07:32:11'),
(24, 'replicate_employee', 'web', '2025-07-10 07:32:11', '2025-07-10 07:32:11'),
(25, 'reorder_employee', 'web', '2025-07-10 07:32:11', '2025-07-10 07:32:11'),
(26, 'delete_employee', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(27, 'delete_any_employee', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(28, 'force_delete_employee', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(29, 'force_delete_any_employee', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(30, 'view_mitra', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(31, 'view_any_mitra', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(32, 'create_mitra', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(33, 'update_mitra', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(34, 'restore_mitra', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(35, 'restore_any_mitra', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(36, 'replicate_mitra', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(37, 'reorder_mitra', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(38, 'delete_mitra', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(39, 'delete_any_mitra', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(40, 'force_delete_mitra', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(41, 'force_delete_any_mitra', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(42, 'view_mitra::teladan', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(43, 'view_any_mitra::teladan', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(44, 'create_mitra::teladan', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(45, 'update_mitra::teladan', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(46, 'restore_mitra::teladan', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(47, 'restore_any_mitra::teladan', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(48, 'replicate_mitra::teladan', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(49, 'reorder_mitra::teladan', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(50, 'delete_mitra::teladan', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(51, 'delete_any_mitra::teladan', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(52, 'force_delete_mitra::teladan', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(53, 'force_delete_any_mitra::teladan', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(54, 'view_nilai1', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(55, 'view_any_nilai1', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(56, 'create_nilai1', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(57, 'update_nilai1', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(58, 'restore_nilai1', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(59, 'restore_any_nilai1', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(60, 'replicate_nilai1', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(61, 'reorder_nilai1', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(62, 'delete_nilai1', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(63, 'delete_any_nilai1', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(64, 'force_delete_nilai1', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(65, 'force_delete_any_nilai1', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(66, 'view_nilai2', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(67, 'view_any_nilai2', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(68, 'create_nilai2', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(69, 'update_nilai2', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(70, 'restore_nilai2', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(71, 'restore_any_nilai2', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(72, 'replicate_nilai2', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(73, 'reorder_nilai2', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(74, 'delete_nilai2', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(75, 'delete_any_nilai2', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(76, 'force_delete_nilai2', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(77, 'force_delete_any_nilai2', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(78, 'view_payment', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(79, 'view_any_payment', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(80, 'create_payment', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(81, 'update_payment', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(82, 'restore_payment', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(83, 'restore_any_payment', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(84, 'replicate_payment', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(85, 'reorder_payment', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(86, 'delete_payment', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(87, 'delete_any_payment', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(88, 'force_delete_payment', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(89, 'force_delete_any_payment', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(90, 'view_role', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(91, 'view_any_role', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(92, 'create_role', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(93, 'update_role', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(94, 'delete_role', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(95, 'delete_any_role', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(96, 'view_survey', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(97, 'view_any_survey', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(98, 'create_survey', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(99, 'update_survey', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(100, 'restore_survey', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(101, 'restore_any_survey', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(102, 'replicate_survey', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(103, 'reorder_survey', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(104, 'delete_survey', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(105, 'delete_any_survey', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(106, 'force_delete_survey', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(107, 'force_delete_any_survey', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(108, 'view_team', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(109, 'view_any_team', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(110, 'create_team', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(111, 'update_team', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(112, 'restore_team', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(113, 'restore_any_team', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(114, 'replicate_team', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(115, 'reorder_team', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(116, 'delete_team', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(117, 'delete_any_team', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(118, 'force_delete_team', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(119, 'force_delete_any_team', 'web', '2025-07-10 07:32:12', '2025-07-10 07:32:12'),
(120, 'view_token', 'web', '2025-07-10 07:32:13', '2025-07-10 07:32:13'),
(121, 'view_any_token', 'web', '2025-07-10 07:32:13', '2025-07-10 07:32:13'),
(122, 'create_token', 'web', '2025-07-10 07:32:13', '2025-07-10 07:32:13'),
(123, 'update_token', 'web', '2025-07-10 07:32:13', '2025-07-10 07:32:13'),
(124, 'restore_token', 'web', '2025-07-10 07:32:13', '2025-07-10 07:32:13'),
(125, 'restore_any_token', 'web', '2025-07-10 07:32:13', '2025-07-10 07:32:13'),
(126, 'replicate_token', 'web', '2025-07-10 07:32:13', '2025-07-10 07:32:13'),
(127, 'reorder_token', 'web', '2025-07-10 07:32:13', '2025-07-10 07:32:13'),
(128, 'delete_token', 'web', '2025-07-10 07:32:13', '2025-07-10 07:32:13'),
(129, 'delete_any_token', 'web', '2025-07-10 07:32:13', '2025-07-10 07:32:13'),
(130, 'force_delete_token', 'web', '2025-07-10 07:32:13', '2025-07-10 07:32:13'),
(131, 'force_delete_any_token', 'web', '2025-07-10 07:32:13', '2025-07-10 07:32:13'),
(132, 'view_transaction', 'web', '2025-07-10 07:32:13', '2025-07-10 07:32:13'),
(133, 'view_any_transaction', 'web', '2025-07-10 07:32:13', '2025-07-10 07:32:13'),
(134, 'create_transaction', 'web', '2025-07-10 07:32:13', '2025-07-10 07:32:13'),
(135, 'update_transaction', 'web', '2025-07-10 07:32:13', '2025-07-10 07:32:13'),
(136, 'restore_transaction', 'web', '2025-07-10 07:32:13', '2025-07-10 07:32:13'),
(137, 'restore_any_transaction', 'web', '2025-07-10 07:32:13', '2025-07-10 07:32:13'),
(138, 'replicate_transaction', 'web', '2025-07-10 07:32:13', '2025-07-10 07:32:13'),
(139, 'reorder_transaction', 'web', '2025-07-10 07:32:13', '2025-07-10 07:32:13'),
(140, 'delete_transaction', 'web', '2025-07-10 07:32:13', '2025-07-10 07:32:13'),
(141, 'delete_any_transaction', 'web', '2025-07-10 07:32:13', '2025-07-10 07:32:13'),
(142, 'force_delete_transaction', 'web', '2025-07-10 07:32:13', '2025-07-10 07:32:13'),
(143, 'force_delete_any_transaction', 'web', '2025-07-10 07:32:13', '2025-07-10 07:32:13'),
(144, 'view_user', 'web', '2025-07-10 07:32:13', '2025-07-10 07:32:13'),
(145, 'view_any_user', 'web', '2025-07-10 07:32:13', '2025-07-10 07:32:13'),
(146, 'create_user', 'web', '2025-07-10 07:32:13', '2025-07-10 07:32:13'),
(147, 'update_user', 'web', '2025-07-10 07:32:13', '2025-07-10 07:32:13'),
(148, 'restore_user', 'web', '2025-07-10 07:32:13', '2025-07-10 07:32:13'),
(149, 'restore_any_user', 'web', '2025-07-10 07:32:13', '2025-07-10 07:32:13'),
(150, 'replicate_user', 'web', '2025-07-10 07:32:13', '2025-07-10 07:32:13'),
(151, 'reorder_user', 'web', '2025-07-10 07:32:13', '2025-07-10 07:32:13'),
(152, 'delete_user', 'web', '2025-07-10 07:32:13', '2025-07-10 07:32:13'),
(153, 'delete_any_user', 'web', '2025-07-10 07:32:13', '2025-07-10 07:32:13'),
(154, 'force_delete_user', 'web', '2025-07-10 07:32:13', '2025-07-10 07:32:13'),
(155, 'force_delete_any_user', 'web', '2025-07-10 07:32:13', '2025-07-10 07:32:13'),
(156, 'page_EmployeeNilai2Status', 'web', '2025-07-10 07:32:13', '2025-07-10 07:32:13'),
(157, 'page_ManageSetting', 'web', '2025-07-10 07:32:13', '2025-07-10 07:32:13'),
(158, 'page_SelectMitraTeladan', 'web', '2025-07-10 07:32:13', '2025-07-10 07:32:13'),
(159, 'page_Themes', 'web', '2025-07-10 07:32:13', '2025-07-10 07:32:13'),
(160, 'page_MyProfilePage', 'web', '2025-07-10 07:32:13', '2025-07-10 07:32:13');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'super_admin', 'web', '2025-07-10 07:32:11', '2025-07-10 07:32:11'),
(2, 'Pegawai', 'web', '2025-07-10 07:34:16', '2025-07-10 07:34:16'),
(3, 'Ketua Tim', 'web', '2025-07-10 07:35:15', '2025-07-10 07:35:15'),
(4, 'Mitra', 'web', '2025-07-10 07:35:43', '2025-07-10 07:35:43'),
(5, 'Ketua SDM', 'web', '2025-07-11 17:43:40', '2025-07-11 17:43:40');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(18, 2),
(18, 3),
(19, 1),
(19, 2),
(19, 3),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 1),
(29, 1),
(30, 1),
(30, 2),
(30, 3),
(31, 1),
(31, 2),
(32, 1),
(33, 1),
(34, 1),
(35, 1),
(36, 1),
(37, 1),
(38, 1),
(39, 1),
(40, 1),
(41, 1),
(42, 1),
(42, 2),
(42, 3),
(43, 1),
(43, 2),
(43, 3),
(44, 1),
(45, 1),
(46, 1),
(47, 1),
(48, 1),
(49, 1),
(50, 1),
(51, 1),
(52, 1),
(53, 1),
(54, 1),
(55, 1),
(56, 1),
(57, 1),
(58, 1),
(59, 1),
(60, 1),
(61, 1),
(62, 1),
(63, 1),
(64, 1),
(65, 1),
(66, 1),
(66, 2),
(67, 1),
(67, 2),
(68, 1),
(68, 2),
(69, 1),
(69, 2),
(70, 1),
(71, 1),
(72, 1),
(73, 1),
(74, 1),
(75, 1),
(76, 1),
(77, 1),
(78, 1),
(78, 3),
(79, 1),
(79, 3),
(80, 1),
(80, 3),
(81, 1),
(81, 3),
(82, 1),
(82, 3),
(83, 1),
(84, 1),
(85, 1),
(86, 1),
(87, 1),
(88, 1),
(89, 1),
(90, 1),
(91, 1),
(92, 1),
(93, 1),
(94, 1),
(95, 1),
(96, 1),
(96, 2),
(96, 3),
(97, 1),
(97, 2),
(97, 3),
(98, 1),
(98, 3),
(99, 1),
(99, 3),
(100, 1),
(101, 1),
(102, 1),
(103, 1),
(104, 1),
(105, 1),
(106, 1),
(107, 1),
(108, 1),
(108, 2),
(108, 3),
(109, 1),
(109, 2),
(109, 3),
(110, 1),
(111, 1),
(112, 1),
(113, 1),
(114, 1),
(115, 1),
(116, 1),
(117, 1),
(118, 1),
(119, 1),
(120, 1),
(121, 1),
(122, 1),
(123, 1),
(124, 1),
(125, 1),
(126, 1),
(127, 1),
(128, 1),
(129, 1),
(130, 1),
(131, 1),
(132, 1),
(133, 1),
(134, 1),
(135, 1),
(136, 1),
(137, 1),
(138, 1),
(139, 1),
(140, 1),
(141, 1),
(142, 1),
(143, 1),
(144, 1),
(145, 1),
(146, 1),
(147, 1),
(148, 1),
(149, 1),
(150, 1),
(151, 1),
(152, 1),
(153, 1),
(154, 1),
(155, 1),
(156, 1),
(157, 1),
(158, 1),
(158, 5),
(159, 1),
(160, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('ah7MBihMTjGMtR3YygFto2D0r1PpC6KAiMjuXwX7', 1, '103.121.96.242', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoibDM2bWhwdlRKOG12Rmx1OFdTMU9PNmRzWDc2b1NZMmRvbzcyMXBtQSI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjcxOiJodHRwczovL21pdHJhLmJwc2tvdGFtYWxhbmcuaWQvZXhwb3J0LW5pbGFpMi1yZXBvcnQ/cXVhcnRlcj00JnllYXI9MjAyNSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl8zZGM3YTkxM2VmNWZkNGI4OTBlY2FiZTM0ODcwODU1NzNlMTZjZjgyIjtpOjE7czoxNzoicGFzc3dvcmRfaGFzaF93ZWIiO3M6NjA6IiQyeSQxMiR3dENYdEZ2VE1ZY1FrT0FuTFlTd3Z1NnZCSWtCOU9wcEoxRHVSaHlQcjlXelVzdE0vcGM1LiI7fQ==', 1759822157);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `group` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `locked` tinyint(1) NOT NULL DEFAULT 0,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`payload`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `group`, `name`, `locked`, `payload`, `created_at`, `updated_at`) VALUES
(1, 'KaidoSetting', 'site_name', 0, '\"Spatie\"', '2025-07-10 07:27:58', '2025-07-10 10:04:37'),
(2, 'KaidoSetting', 'site_active', 0, 'true', '2025-07-10 07:27:58', '2025-07-10 10:04:37'),
(3, 'KaidoSetting', 'registration_enabled', 0, 'false', '2025-07-10 07:27:58', '2025-07-10 10:04:37'),
(4, 'KaidoSetting', 'login_enabled', 0, 'true', '2025-07-10 07:27:58', '2025-07-10 10:04:37'),
(5, 'KaidoSetting', 'password_reset_enabled', 0, 'false', '2025-07-10 07:27:58', '2025-07-10 10:04:37'),
(6, 'KaidoSetting', 'sso_enabled', 0, 'false', '2025-07-10 07:27:58', '2025-07-10 10:04:37');

-- --------------------------------------------------------

--
-- Table structure for table `socialite_users`
--

CREATE TABLE `socialite_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `provider` varchar(255) NOT NULL,
  `provider_id` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `surveys`
--

CREATE TABLE `surveys` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `master_survey_id` bigint(20) UNSIGNED DEFAULT NULL,
  `triwulan` tinyint(3) UNSIGNED DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `payment_id` bigint(20) UNSIGNED NOT NULL,
  `team_id` bigint(20) UNSIGNED NOT NULL,
  `rate` int(11) NOT NULL,
  `file` varchar(255) DEFAULT NULL,
  `is_scored` tinyint(1) NOT NULL DEFAULT 0,
  `is_synced` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('not started','in progress','done') NOT NULL DEFAULT 'not started',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `surveys`
--

INSERT INTO `surveys` (`id`, `master_survey_id`, `triwulan`, `year`, `payment_id`, `team_id`, `rate`, `file`, `is_scored`, `is_synced`, `status`, `created_at`, `updated_at`) VALUES
(1, 34, 2, 2025, 1, 4, 1, NULL, 1, 0, 'not started', '2025-07-10 08:03:24', '2025-07-10 08:15:11'),
(2, 35, 2, 2025, 1, 4, 1, NULL, 1, 0, 'not started', '2025-07-10 08:16:21', '2025-07-10 08:16:54'),
(3, 36, 2, 2025, 1, 4, 1, NULL, 1, 0, 'not started', '2025-07-10 08:18:32', '2025-07-10 08:19:52'),
(4, 37, 2, 2025, 1, 4, 1, NULL, 1, 0, 'not started', '2025-07-10 08:21:49', '2025-07-10 08:22:11'),
(5, 38, 2, 2025, 1, 4, 1, NULL, 1, 0, 'not started', '2025-07-10 08:23:22', '2025-07-10 08:23:44'),
(6, 39, 2, 2025, 1, 4, 1, NULL, 1, 0, 'not started', '2025-07-10 08:24:47', '2025-07-10 08:25:08'),
(7, 40, 2, 2025, 1, 4, 1, NULL, 1, 0, 'not started', '2025-07-10 08:27:40', '2025-07-10 08:28:17'),
(8, 41, 2, 2025, 1, 4, 1, NULL, 1, 0, 'not started', '2025-07-10 08:29:13', '2025-07-10 08:29:45'),
(9, 42, 2, 2025, 1, 4, 1, NULL, 1, 0, 'not started', '2025-07-10 08:30:28', '2025-07-10 08:31:06'),
(10, 29, 2, 2025, 1, 7, 1, NULL, 1, 0, 'not started', '2025-07-10 08:36:36', '2025-07-10 08:37:16'),
(11, 30, 2, 2025, 1, 7, 1, NULL, 1, 0, 'not started', '2025-07-10 08:37:53', '2025-07-10 08:38:43'),
(12, 31, 2, 2025, 1, 7, 1, NULL, 1, 0, 'not started', '2025-07-10 08:40:10', '2025-07-10 08:40:53'),
(13, 43, 2, 2025, 1, 7, 1, NULL, 1, 0, 'not started', '2025-07-10 08:41:35', '2025-07-10 08:41:57'),
(14, 44, 2, 2025, 1, 2, 1, NULL, 1, 0, 'not started', '2025-07-10 08:43:45', '2025-07-10 08:44:25'),
(15, 16, 2, 2025, 1, 2, 1, NULL, 1, 0, 'not started', '2025-07-10 08:45:49', '2025-07-10 08:46:45'),
(16, 45, 2, 2025, 1, 3, 1, NULL, 1, 0, 'not started', '2025-07-10 08:49:39', '2025-07-10 08:50:18'),
(17, 19, 2, 2025, 1, 3, 1, NULL, 1, 0, 'not started', '2025-07-10 08:51:26', '2025-07-10 08:51:48'),
(18, 46, 2, 2025, 1, 3, 1, NULL, 1, 0, 'not started', '2025-07-10 08:56:58', '2025-07-10 08:57:38'),
(19, 20, 2, 2025, 1, 3, 1, NULL, 1, 0, 'not started', '2025-07-10 08:58:31', '2025-07-10 08:58:56'),
(20, 21, 2, 2025, 1, 3, 1, NULL, 1, 0, 'not started', '2025-07-10 08:59:34', '2025-07-10 09:02:08'),
(21, 22, 2, 2025, 1, 3, 1, NULL, 1, 0, 'not started', '2025-07-10 09:03:26', '2025-07-10 09:09:12'),
(22, 47, 2, 2025, 1, 3, 1, NULL, 1, 0, 'not started', '2025-07-10 09:11:03', '2025-07-10 09:11:19'),
(23, 23, 2, 2025, 1, 3, 1, NULL, 1, 0, 'not started', '2025-07-10 09:12:06', '2025-07-10 09:12:30'),
(24, 48, 2, 2025, 1, 3, 1, NULL, 1, 0, 'not started', '2025-07-10 09:18:16', '2025-07-10 09:18:46'),
(25, 49, 2, 2025, 1, 3, 1, NULL, 1, 0, 'not started', '2025-07-10 09:19:47', '2025-07-10 09:20:07'),
(26, 50, 2, 2025, 1, 3, 1, NULL, 1, 0, 'not started', '2025-07-10 09:21:03', '2025-07-10 09:22:04'),
(27, 24, 2, 2025, 1, 3, 1, NULL, 1, 0, 'not started', '2025-07-10 09:22:36', '2025-07-10 09:22:56'),
(28, 51, 2, 2025, 1, 5, 1, NULL, 1, 0, 'not started', '2025-07-10 09:26:50', '2025-07-10 09:29:31'),
(29, 26, 2, 2025, 1, 5, 1, NULL, 1, 0, 'not started', '2025-07-10 09:30:07', '2025-07-10 09:30:50'),
(30, 12, 2, 2025, 1, 6, 1, NULL, 0, 0, 'not started', '2025-07-10 09:32:40', '2025-07-10 09:32:40');

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE `teams` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `has_survey` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `teams`
--

INSERT INTO `teams` (`id`, `name`, `has_survey`, `created_at`, `updated_at`) VALUES
(1, 'Umum', 0, NULL, NULL),
(2, 'Sosial', 1, NULL, NULL),
(3, 'Produksi', 1, NULL, NULL),
(4, 'Distribusi', 1, NULL, NULL),
(5, 'Neraca', 1, NULL, NULL),
(6, 'IPDS', 1, NULL, NULL),
(7, 'Harga', 1, '2025-07-10 08:18:41', '2025-07-10 08:18:41');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mitra_id` bigint(20) UNSIGNED NOT NULL,
  `survey_id` bigint(20) UNSIGNED NOT NULL,
  `target` int(11) NOT NULL,
  `rate` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `mitra_id`, `survey_id`, `target`, `rate`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, '2025-07-10 08:07:44', '2025-07-10 08:07:44'),
(2, 2, 1, 1, 1, '2025-07-10 08:14:57', '2025-07-10 08:14:57'),
(3, 3, 1, 1, 1, '2025-07-10 08:14:57', '2025-07-10 08:14:57'),
(4, 4, 1, 1, 1, '2025-07-10 08:14:58', '2025-07-10 08:14:58'),
(5, 5, 2, 1, 1, '2025-07-10 08:16:42', '2025-07-10 08:16:42'),
(6, 2, 2, 1, 1, '2025-07-10 08:16:42', '2025-07-10 08:16:42'),
(7, 3, 2, 1, 1, '2025-07-10 08:16:43', '2025-07-10 08:16:43'),
(8, 4, 2, 1, 1, '2025-07-10 08:16:43', '2025-07-10 08:16:43'),
(9, 6, 2, 1, 1, '2025-07-10 08:16:44', '2025-07-10 08:16:44'),
(10, 1, 2, 1, 1, '2025-07-10 08:16:44', '2025-07-10 08:16:44'),
(11, 7, 2, 1, 1, '2025-07-10 08:16:44', '2025-07-10 08:16:44'),
(12, 8, 2, 1, 1, '2025-07-10 08:16:45', '2025-07-10 08:16:45'),
(13, 9, 2, 1, 1, '2025-07-10 08:16:45', '2025-07-10 08:16:45'),
(14, 5, 3, 1, 1, '2025-07-10 08:19:42', '2025-07-10 08:19:42'),
(15, 2, 3, 1, 1, '2025-07-10 08:19:42', '2025-07-10 08:19:42'),
(16, 3, 3, 1, 1, '2025-07-10 08:19:43', '2025-07-10 08:19:43'),
(17, 4, 3, 1, 1, '2025-07-10 08:19:43', '2025-07-10 08:19:43'),
(18, 6, 3, 1, 1, '2025-07-10 08:19:43', '2025-07-10 08:19:43'),
(19, 1, 3, 1, 1, '2025-07-10 08:19:44', '2025-07-10 08:19:44'),
(20, 7, 3, 1, 1, '2025-07-10 08:19:44', '2025-07-10 08:19:44'),
(21, 8, 3, 1, 1, '2025-07-10 08:19:44', '2025-07-10 08:19:44'),
(22, 9, 3, 1, 1, '2025-07-10 08:19:45', '2025-07-10 08:19:45'),
(23, 10, 4, 1, 1, '2025-07-10 08:22:06', '2025-07-10 08:22:06'),
(24, 2, 4, 1, 1, '2025-07-10 08:22:06', '2025-07-10 08:22:06'),
(25, 4, 4, 1, 1, '2025-07-10 08:22:06', '2025-07-10 08:22:06'),
(26, 11, 4, 1, 1, '2025-07-10 08:22:07', '2025-07-10 08:22:07'),
(27, 12, 4, 1, 1, '2025-07-10 08:22:07', '2025-07-10 08:22:07'),
(28, 7, 4, 1, 1, '2025-07-10 08:22:08', '2025-07-10 08:22:08'),
(29, 8, 5, 1, 1, '2025-07-10 08:23:37', '2025-07-10 08:23:37'),
(30, 5, 6, 1, 1, '2025-07-10 08:25:02', '2025-07-10 08:25:02'),
(31, 6, 6, 1, 1, '2025-07-10 08:25:02', '2025-07-10 08:25:02'),
(32, 12, 6, 1, 1, '2025-07-10 08:25:03', '2025-07-10 08:25:03'),
(33, 13, 6, 1, 1, '2025-07-10 08:25:03', '2025-07-10 08:25:03'),
(34, 2, 7, 1, 1, '2025-07-10 08:28:04', '2025-07-10 08:28:04'),
(35, 12, 7, 1, 1, '2025-07-10 08:28:04', '2025-07-10 08:28:04'),
(36, 7, 7, 1, 1, '2025-07-10 08:28:04', '2025-07-10 08:28:04'),
(37, 14, 8, 1, 1, '2025-07-10 08:29:37', '2025-07-10 08:29:37'),
(38, 5, 8, 1, 1, '2025-07-10 08:29:37', '2025-07-10 08:29:37'),
(39, 10, 8, 1, 1, '2025-07-10 08:29:37', '2025-07-10 08:29:37'),
(40, 2, 8, 1, 1, '2025-07-10 08:29:38', '2025-07-10 08:29:38'),
(41, 3, 8, 1, 1, '2025-07-10 08:29:38', '2025-07-10 08:29:38'),
(42, 15, 8, 1, 1, '2025-07-10 08:29:38', '2025-07-10 08:29:38'),
(43, 6, 8, 1, 1, '2025-07-10 08:29:39', '2025-07-10 08:29:39'),
(44, 1, 8, 1, 1, '2025-07-10 08:29:39', '2025-07-10 08:29:39'),
(45, 12, 8, 1, 1, '2025-07-10 08:29:39', '2025-07-10 08:29:39'),
(46, 13, 8, 1, 1, '2025-07-10 08:29:40', '2025-07-10 08:29:40'),
(47, 7, 8, 1, 1, '2025-07-10 08:29:40', '2025-07-10 08:29:40'),
(48, 8, 8, 1, 1, '2025-07-10 08:29:40', '2025-07-10 08:29:40'),
(49, 2, 9, 1, 1, '2025-07-10 08:31:02', '2025-07-10 08:31:02'),
(50, 12, 9, 1, 1, '2025-07-10 08:31:03', '2025-07-10 08:31:03'),
(51, 16, 10, 1, 1, '2025-07-10 08:37:01', '2025-07-10 08:37:01'),
(52, 17, 10, 1, 1, '2025-07-10 08:37:02', '2025-07-10 08:37:02'),
(53, 18, 10, 1, 1, '2025-07-10 08:37:02', '2025-07-10 08:37:02'),
(54, 19, 10, 1, 1, '2025-07-10 08:37:02', '2025-07-10 08:37:02'),
(55, 20, 10, 1, 1, '2025-07-10 08:37:03', '2025-07-10 08:37:03'),
(56, 21, 10, 1, 1, '2025-07-10 08:37:03', '2025-07-10 08:37:03'),
(57, 22, 10, 1, 1, '2025-07-10 08:37:04', '2025-07-10 08:37:04'),
(58, 2, 11, 1, 1, '2025-07-10 08:38:25', '2025-07-10 08:38:25'),
(59, 15, 11, 1, 1, '2025-07-10 08:38:25', '2025-07-10 08:38:25'),
(60, 4, 11, 1, 1, '2025-07-10 08:38:26', '2025-07-10 08:38:26'),
(61, 23, 11, 1, 1, '2025-07-10 08:38:26', '2025-07-10 08:38:26'),
(62, 11, 11, 1, 1, '2025-07-10 08:38:27', '2025-07-10 08:38:27'),
(63, 12, 11, 1, 1, '2025-07-10 08:38:27', '2025-07-10 08:38:27'),
(64, 7, 11, 1, 1, '2025-07-10 08:38:28', '2025-07-10 08:38:28'),
(65, 2, 12, 1, 1, '2025-07-10 08:40:33', '2025-07-10 08:40:33'),
(66, 7, 12, 1, 1, '2025-07-10 08:40:34', '2025-07-10 08:40:34'),
(67, 21, 12, 1, 1, '2025-07-10 08:40:34', '2025-07-10 08:40:34'),
(68, 12, 12, 1, 1, '2025-07-10 08:40:34', '2025-07-10 08:40:34'),
(69, 4, 12, 1, 1, '2025-07-10 08:40:35', '2025-07-10 08:40:35'),
(70, 1, 12, 1, 1, '2025-07-10 08:40:35', '2025-07-10 08:40:35'),
(71, 16, 13, 1, 1, '2025-07-10 08:41:53', '2025-07-10 08:41:53'),
(72, 17, 13, 1, 1, '2025-07-10 08:41:53', '2025-07-10 08:41:53'),
(73, 21, 13, 1, 1, '2025-07-10 08:41:53', '2025-07-10 08:41:53'),
(74, 5, 14, 1, 1, '2025-07-10 08:44:15', '2025-07-10 08:44:15'),
(75, 2, 14, 1, 1, '2025-07-10 08:44:15', '2025-07-10 08:44:15'),
(76, 24, 14, 1, 1, '2025-07-10 08:44:15', '2025-07-10 08:44:15'),
(77, 6, 14, 1, 1, '2025-07-10 08:44:16', '2025-07-10 08:44:16'),
(78, 13, 14, 1, 1, '2025-07-10 08:44:16', '2025-07-10 08:44:16'),
(79, 25, 14, 1, 1, '2025-07-10 08:44:17', '2025-07-10 08:44:17'),
(80, 26, 15, 1, 1, '2025-07-10 08:46:37', '2025-07-10 08:46:37'),
(81, 27, 15, 1, 1, '2025-07-10 08:46:38', '2025-07-10 08:46:38'),
(82, 28, 15, 1, 1, '2025-07-10 08:46:38', '2025-07-10 08:46:38'),
(83, 29, 15, 1, 1, '2025-07-10 08:46:39', '2025-07-10 08:46:39'),
(84, 30, 15, 1, 1, '2025-07-10 08:46:39', '2025-07-10 08:46:39'),
(85, 31, 15, 1, 1, '2025-07-10 08:46:39', '2025-07-10 08:46:39'),
(86, 23, 15, 1, 1, '2025-07-10 08:46:40', '2025-07-10 08:46:40'),
(87, 32, 15, 1, 1, '2025-07-10 08:46:40', '2025-07-10 08:46:40'),
(88, 33, 15, 1, 1, '2025-07-10 08:46:40', '2025-07-10 08:46:40'),
(89, 34, 15, 1, 1, '2025-07-10 08:46:41', '2025-07-10 08:46:41'),
(90, 5, 16, 1, 1, '2025-07-10 08:50:11', '2025-07-10 08:50:11'),
(91, 23, 16, 1, 1, '2025-07-10 08:50:11', '2025-07-10 08:50:11'),
(92, 11, 16, 1, 1, '2025-07-10 08:50:11', '2025-07-10 08:50:11'),
(93, 12, 16, 1, 1, '2025-07-10 08:50:12', '2025-07-10 08:50:12'),
(94, 35, 16, 1, 1, '2025-07-10 08:50:12', '2025-07-10 08:50:12'),
(95, 36, 16, 1, 1, '2025-07-10 08:50:12', '2025-07-10 08:50:12'),
(96, 37, 16, 1, 1, '2025-07-10 08:50:13', '2025-07-10 08:50:13'),
(97, 36, 17, 1, 1, '2025-07-10 08:51:43', '2025-07-10 08:51:43'),
(98, 2, 17, 1, 1, '2025-07-10 08:51:43', '2025-07-10 08:51:43'),
(99, 38, 18, 1, 1, '2025-07-10 08:57:25', '2025-07-10 08:57:25'),
(100, 39, 18, 1, 1, '2025-07-10 08:57:25', '2025-07-10 08:57:25'),
(101, 24, 18, 1, 1, '2025-07-10 08:57:25', '2025-07-10 08:57:25'),
(102, 40, 18, 1, 1, '2025-07-10 08:57:26', '2025-07-10 08:57:26'),
(103, 41, 18, 1, 1, '2025-07-10 08:57:26', '2025-07-10 08:57:26'),
(104, 42, 18, 1, 1, '2025-07-10 08:57:27', '2025-07-10 08:57:27'),
(105, 12, 18, 1, 1, '2025-07-10 08:57:27', '2025-07-10 08:57:27'),
(106, 43, 18, 1, 1, '2025-07-10 08:57:27', '2025-07-10 08:57:27'),
(107, 36, 18, 1, 1, '2025-07-10 08:57:28', '2025-07-10 08:57:28'),
(108, 44, 18, 1, 1, '2025-07-10 08:57:28', '2025-07-10 08:57:28'),
(109, 39, 19, 1, 1, '2025-07-10 08:58:48', '2025-07-10 08:58:48'),
(110, 41, 19, 1, 1, '2025-07-10 08:58:48', '2025-07-10 08:58:48'),
(111, 12, 19, 1, 1, '2025-07-10 08:58:49', '2025-07-10 08:58:49'),
(112, 44, 19, 1, 1, '2025-07-10 08:58:49', '2025-07-10 08:58:49'),
(113, 37, 19, 1, 1, '2025-07-10 08:58:50', '2025-07-10 08:58:50'),
(114, 11, 19, 1, 1, '2025-07-10 08:58:51', '2025-07-10 08:58:51'),
(115, 36, 19, 1, 1, '2025-07-10 08:58:51', '2025-07-10 08:58:51'),
(116, 11, 20, 1, 1, '2025-07-10 08:59:49', '2025-07-10 08:59:49'),
(117, 43, 20, 1, 1, '2025-07-10 08:59:49', '2025-07-10 08:59:49'),
(118, 36, 20, 1, 1, '2025-07-10 08:59:49', '2025-07-10 08:59:49'),
(119, 35, 20, 1, 1, '2025-07-10 08:59:50', '2025-07-10 08:59:50'),
(120, 37, 20, 1, 1, '2025-07-10 08:59:50', '2025-07-10 08:59:50'),
(121, 45, 21, 1, 1, '2025-07-10 09:09:07', '2025-07-10 09:09:07'),
(122, 36, 22, 1, 1, '2025-07-10 09:11:17', '2025-07-10 09:11:17'),
(123, 36, 23, 1, 1, '2025-07-10 09:12:23', '2025-07-10 09:12:23'),
(124, 12, 24, 1, 1, '2025-07-10 09:18:38', '2025-07-10 09:18:38'),
(125, 44, 24, 1, 1, '2025-07-10 09:18:39', '2025-07-10 09:18:39'),
(126, 36, 25, 1, 1, '2025-07-10 09:20:00', '2025-07-10 09:20:00'),
(127, 36, 26, 1, 1, '2025-07-10 09:21:58', '2025-07-10 09:21:58'),
(128, 11, 27, 1, 1, '2025-07-10 09:22:49', '2025-07-10 09:22:49'),
(129, 43, 27, 1, 1, '2025-07-10 09:22:50', '2025-07-10 09:22:50'),
(130, 35, 27, 1, 1, '2025-07-10 09:22:50', '2025-07-10 09:22:50'),
(131, 37, 27, 1, 1, '2025-07-10 09:22:50', '2025-07-10 09:22:50'),
(132, 1, 28, 1, 1, '2025-07-10 09:29:08', '2025-07-10 09:29:08'),
(133, 13, 28, 1, 1, '2025-07-10 09:29:08', '2025-07-10 09:29:08'),
(134, 25, 28, 1, 1, '2025-07-10 09:29:09', '2025-07-10 09:29:09'),
(135, 46, 29, 1, 1, '2025-07-10 09:30:42', '2025-07-10 09:30:42'),
(136, 10, 29, 1, 1, '2025-07-10 09:30:42', '2025-07-10 09:30:42'),
(137, 47, 29, 1, 1, '2025-07-10 09:30:43', '2025-07-10 09:30:43'),
(138, 48, 29, 1, 1, '2025-07-10 09:30:43', '2025-07-10 09:30:43'),
(139, 35, 29, 1, 1, '2025-07-10 09:30:44', '2025-07-10 09:30:44'),
(140, 49, 30, 1, 1, '2025-07-10 09:33:34', '2025-07-10 09:33:34'),
(141, 50, 30, 1, 1, '2025-07-10 09:33:35', '2025-07-10 09:33:35'),
(142, 51, 30, 1, 1, '2025-07-10 09:33:35', '2025-07-10 09:33:35');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `avatar_url` varchar(255) DEFAULT NULL,
  `theme` varchar(255) DEFAULT 'default',
  `theme_color` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `avatar_url`, `theme`, `theme_color`) VALUES
(1, 'admin', 'admin@mitra.bpskotamalang.id', '2025-07-10 07:28:06', '$2y$12$wtCXtFvTMYcQkOAnLYSwvu6vBIkB9OppJ1DuRhyPr9WzUstM/pc5.', '2ZeKlMZgGNUEOyMGdOcgf7bjo95V6BeX1uxk9Wmfcih4JsAmg7Oitnb3rozZ', '2025-07-10 07:28:06', '2025-07-10 10:04:25', NULL, 'default', NULL),
(2, 'Umar Sjaifudin M.Si', 'umars@bps.go.id', '2025-07-10 07:28:06', '$2y$12$2P1/lWVE3kTwHZuVZubbrO6M1NXMqwK1v77YqJZc1RIZ58kdgK27K', 'JVU7xiUPbbr0LQvADBx2bhI96ywmwNmGgfXZ11eD48j7W8HkaJXAMEhgUUrz', '2025-07-10 07:28:07', '2025-07-10 15:59:21', NULL, 'default', NULL),
(3, 'Ir. Dwi Handayani Prasetyawati M.AP', 'dwi.handayani@bps.go.id', '2025-07-10 07:28:06', '$2y$12$9hbUT84X0NJYG5c2JOcEieWC3FthPq0ITqdhjgYW08aTAc4wHBhgq', 'Eq3YO5GBFZ2g9riXSzEk8G3r3B5GR2fXNfXk8ggaFxsIw8Z19B4hvQ8Wbmfn', '2025-07-10 07:28:07', '2025-07-10 16:13:40', NULL, 'default', NULL),
(4, 'Ir. Wahyu Furqandari M.M', 'wfurqan@bps.go.id', '2025-07-10 07:28:06', '$2y$12$ZrXI6SbqG.O3pg1hocNIfOmQNwfsqHxRo.p3ndKdXHxx9.l/hLiQK', 'CORBkiSmjQfR1XVKhbWOjyxbGQ7NT37KF5pz9z1gmvPFO1QskLrOMvZYtDmt', '2025-07-10 07:28:07', '2025-07-10 16:13:59', NULL, 'default', NULL),
(5, 'Tasmilah SST, M.E', 'tasmilah@bps.go.id', '2025-07-10 07:28:06', '$2y$12$JpedBQLv1GjBZfE7A.1dKelV7V4mAUFHQSVh9FxNy4X1ZoSaATHvq', 'hPxguuxQTPZjPL1fIyvX0Iy51kMUP5520TDitKWtinRqCftWtkCG9VnpRtpf', '2025-07-10 07:28:08', '2025-07-10 15:59:47', NULL, 'default', NULL),
(6, 'Ir. Agustina Martha M.M', 'agustina.martha@bps.go.id', '2025-07-10 07:28:06', '$2y$12$ocOIeFQLsZbt7lng7iVhSuLhcgPe521yIbs4sBoV47VB6hYdFP7si', 'pnbPSzyGiXAVTM1hoYYDdCOYFOVp75n9WMCeFRMHeGQoDsKXbNMkzdUqX3bp', '2025-07-10 07:28:08', '2025-07-10 16:14:29', NULL, 'default', NULL),
(7, 'Ahmad Junaedi S.Si, M.M', 'ahmad.junaedi@bps.go.id', '2025-07-10 07:28:06', '$2y$12$w2T/.fAGqc2fT4iZTmOemOBrFPhxqTYgO8YlL74TGW6jWLVM6Agtu', 'GYeQPQAl71KY9F72k9FNAdn1r6i65nX4wlIetIlT1cXfmNbHbEDYn3BeCAty', '2025-07-10 07:28:09', '2025-07-10 16:14:51', NULL, 'default', NULL),
(8, 'Anggi Fatwa Mauliza A.Md.Kb.N', 'anggi.mauliza@bps.go.id', '2025-07-10 07:28:06', '$2y$12$xPHxtk7Q8biCrRbJ6.t2HuSxQ5R8HF3DTyJ2YmBymOncxUts7LPRS', 'SGQbOs7CTEpTeyS4nUEpdxLiGL9iYySWg5BHalnIthd5Z1OMHVzocSY3hJzc', '2025-07-10 07:28:09', '2025-07-10 16:15:14', NULL, 'default', NULL),
(9, 'Arini Ismiati S.ST', 'arini.ismi@bps.go.id', '2025-07-10 07:28:06', '$2y$12$PqpBxyQ5Qo8U0Pg25b3pqu/fsnFHME5aclhiAV3TbO7.1lyvKRx9m', '0AUyXQppLBjk4ZdCBFrnXyUnieEzsdTQQ3ldtypLMX6LHLF8baROEwEwQzLc', '2025-07-10 07:28:09', '2025-07-10 16:15:36', NULL, 'default', NULL),
(10, 'Baqtiar Arifin S.Stat., MM', 'baqtiar.arifin@bps.go.id', '2025-07-10 07:44:18', '$2y$12$ghGcrC4GpU2wExW9vBm3R.sQ4xdV0x9Cq9lAveVQwwJbBoTDsK.Pu', 'uP6MGIU9W9lEs2xfjl9nfA5lkWqp0YoGb340nTr8MIBVc373DDEcZzSVFbim', '2025-07-10 07:28:10', '2025-07-10 16:16:11', NULL, 'default', NULL),
(11, 'Bima Sakti Krisdianto SST', 'bima.sakti@bps.go.id', '2025-07-10 07:44:20', '$2y$12$tWCNVFt1vz3IrxcK73Rp7eIJ7KkiOUyoNLpCks3v9XjHHEEfQ2zca', 'd0aei3jGD7cMG9GiTVoQzadFHMLFtXBYtR30LOfRnoQNKUAz3jR7B9q10j0o', '2025-07-10 07:28:10', '2025-07-10 16:16:54', NULL, 'default', NULL),
(12, 'Eka Arifianto A.Md. Lib', 'ekaarifianto-pppk@bps.go.id', '2025-07-10 07:28:06', '$2y$12$RfGx0q1Syu6fwHT59e/Bxu89jZLPrxe3VvxWAQKiQi3sZ3eBvZDmm', '3afpO0RVTX6cBNWTp31nQsZOJtJZ89VrTuAjf6NOdpgNUt9970btHwdCmmd1', '2025-07-10 07:28:11', '2025-07-10 16:16:34', NULL, 'default', NULL),
(13, 'Erlisa Cantika Herawati S.Si', 'erlisa.ch@bps.go.id', '2025-07-10 07:28:06', '$2y$12$DwdRI69/3QPaK6d9sIErgOBXY1A0/vHl9lhZEUMR1S4wpDprEsxfe', '1hPTo0XtLmRja9njWfjhXaLufCeJxX7Lh0sEERovW2hduzMfBnOSNFQhfi6Y', '2025-07-10 07:28:11', '2025-07-10 16:21:18', NULL, 'default', NULL),
(15, 'Heru Kartiko SST', 'heru.kartiko@bps.go.id', '2025-07-10 07:28:06', '$2y$12$n.hLwCirKhzEP7WbCdDStOToWdGVxY3fMhkAIIquiIIkGbHyEo8PK', 'k9HBhlLIAMpU4tQrEIuKd3L3bQeQ1VWSOtqHttYJpQ3oLU0nw1eXkCftA5wb', '2025-07-10 07:28:12', '2025-07-10 16:22:58', NULL, 'default', NULL),
(16, 'Rachmad Widi Wijayanto', 'rachmadwidi@bps.go.id', '2025-07-10 07:28:06', '$2y$12$NdO8D12lvhN4x0VawyPm5OvVe5i3MsWlSUTRtrmVrP5maI5Fgf5M6', 'uJ9vzN4WzGxaGhH44aX4nRlnTF5Oc2I7WyEhql9S0eTUqQMELvusiqQpteVw', '2025-07-10 07:28:13', '2025-07-10 16:23:28', NULL, 'default', NULL),
(17, 'Ratri Adhipradani Ratih S.Si', 'ratri@bps.go.id', '2025-07-10 07:28:06', '$2y$12$GYkO7bBC.0TN5FPnGGubHurYTEubl1RUyDfOhSoDfjzA1tSN1d8xq', '0H2O6WTwtk7wCHQPzRGtUI2vE6I7osWgYsYAdLhmS6w0CFD7ovHTNmHXFdD4', '2025-07-10 07:28:13', '2025-07-10 07:28:13', NULL, 'default', NULL),
(18, 'Rendra Anandhika A.Md', 'rendra@bps.go.id', '2025-07-10 07:28:06', '$2y$12$oq6rjfmizfhDHQgMa1zpme6tLxtjpzKAH9GSXJsAp0C5U0EWHUxNO', 'SxE73GQEwMrIg7y3udiHAy6CI9V4vpNgvFCZ06EXV8vLsIDqjQ5rEsbOekU5', '2025-07-10 07:28:13', '2025-07-10 07:28:13', NULL, 'default', NULL),
(19, 'Rhyke Chrisdiana Novita S.E', 'rhyke.novita@bps.go.id', '2025-07-10 07:28:06', '$2y$12$v6zMMTc3PWyULOzQhvV4J.2H96cSQNlsSZ1Dh4LZxWohaDfdI8ON2', 'dvPrSDghgYscpoWutXcLlIVkKe1s1UHzdUu2zm3N6EvxzWTqHqIyzkm5NK2U', '2025-07-10 07:28:14', '2025-07-10 16:24:28', NULL, 'default', NULL),
(20, 'Rizky Maulidya SST, M.AP', 'rizky.maulidya@bps.go.id', '2025-07-10 07:28:06', '$2y$12$R9p/px/sFiMXUzmuTG2I7el.JwPRwkKo/12DCMJ92iqDu8MCqy6Li', 'S930RtziNx9Q2GOGT5pAixIrfxu13jrqNJkni7BfA0yhfXvvqzXarBtI1nDN', '2025-07-10 07:28:15', '2025-07-10 16:24:46', NULL, 'default', NULL),
(21, 'Saras Wati Utami S.Si, M.E', 'saras.wati@bps.go.id', '2025-07-10 07:28:06', '$2y$12$V4pPhbOGXKyxBxHxcsF5ROURNIxrtTXODY8gNv0TJSgydhO0NG8xG', 'F2fxNnrl59QvP1iKqfqg4Ibc8yzZU2b4AjLCtpStY2DPm7Snyjv97IuIymlR', '2025-07-10 07:28:15', '2025-07-10 16:25:06', NULL, 'default', NULL),
(22, 'Saruni Gincahyo SE', 'saruni.gincahyo@bps.go.id', '2025-07-10 07:28:06', '$2y$12$oba6w.vhbwHJSf3grFBMa.HAVnUNm.XDSQMD9ZdDuqYMxpNNs0Pj6', 'BmQ8yG8DNdWcsV39Y9brv4lZsV1OLMliFpdgA31CfEAcGsYSALjbh3ofJTrt', '2025-07-10 07:28:15', '2025-07-10 16:25:25', NULL, 'default', NULL),
(23, 'Satria Candra Wibawa A.Md', 'satria.wibawa@bps.go.id', '2025-07-10 07:28:06', '$2y$12$XdII3V/QdjxFGHKc4ZyL.e2u4.3IpF6B7Z1zJ7d1DCjatLl1EJ4Y2', 'z2cczsqXnYsVhOaQogfC8eyBjPKIlqJq0WZdNTc84x0oN4AIX6lYw3LHrj29', '2025-07-10 07:28:16', '2025-07-10 16:25:45', NULL, 'default', NULL),
(24, 'Siti Barokatun Solihah SST', 'siti.barokatun@bps.go.id', '2025-07-10 07:28:06', '$2y$12$87wnWgsY9CYfglF2VQDPEedKT98pjIvBGsL/A5qWxnxf0JnDg4Ake', 'ErUbktMIKD9w2FhJLvabqKgxIWy70pfHWwTi7nbRDopfGSYKvjONGaDw4xwL', '2025-07-10 07:28:16', '2025-07-10 16:26:08', NULL, 'default', NULL),
(25, 'Soekesi Irawati S.Psi., M.M', 'soekesi.irawati@bps.go.id', '2025-07-10 07:28:06', '$2y$12$kUtZqHFEL7UILGqUiTSoTeNagw9YrNoPNibpxQDjsBlwKoxxKipNm', '0TSgNfUje6JHdWxiJ83v7kJaF1EKoH7R08ha6fv9ZKA3Sjyw7BI9h4xaMntn', '2025-07-10 07:28:17', '2025-07-10 16:26:25', NULL, 'default', NULL),
(26, 'Windi Wijayanti S.Si, M.E', 'windi.wijayanti@bps.go.id', '2025-07-10 07:28:06', '$2y$12$8Mh4nZpRscHlY7yFMp9gju5iLQL0N6yNuLfnIn7kZrWE7y76n0Bbu', '4ezgrJrPjBo5yZMH4AkbAcAdnKd4S56Mja3d30hmrNcvoHIefzXJwya3jn2I', '2025-07-10 07:28:17', '2025-07-10 16:26:48', NULL, 'default', NULL),
(27, 'Yenita Mirawanti SST., M.Si', 'yenita@bps.go.id', '2025-07-10 07:44:54', '$2y$12$z1PaGPyPQpGhrlkYwfKQbuXe/9jkR1AuJ16GaW05kIFtaHf4si6/m', 'KBKzww51K0ZXKrHT9NCsyVfc9pOy8L3XIQYabrPA9YBsLB6ppDZ5dfA8PfnH', '2025-07-10 07:28:17', '2025-07-10 07:28:17', NULL, 'default', NULL),
(28, 'Yusuf Fatoni SE', 'yusuf.fatoni@bps.go.id', '2025-07-10 07:28:06', '$2y$12$yhNWEo9oMFGHF2ekUvp5Wu7/v8vxrOtVlyigvojDxAeBRfvY/McN2', '2xiQWhUDr1DhrL8ySyZrgfTgAazTrCNYcwC8qeS1sBQ3cuBs1J80jEjjB6Wx', '2025-07-10 07:28:18', '2025-07-10 16:27:15', NULL, 'default', NULL),
(29, 'Emman Suparji', 'e81emm4n5@gmail.com', NULL, '$2y$12$VFsKVu4KklYujzSGlBPMDejOi3Ot.BkTZ7V6lKBiDTaRBazxLIsOW', NULL, '2025-07-10 08:03:45', '2025-07-10 08:03:45', NULL, 'default', NULL),
(30, 'SONNA ASDINATA', 'Sonna.asdinata19@gmail.com', NULL, '$2y$12$SZoL/zsleRN4dmN8SxgGNuNpuiQjTqNnkcBNNi9Li5bPUEuCKd0Dq', NULL, '2025-07-10 08:03:45', '2025-07-10 08:03:45', NULL, 'default', NULL),
(31, 'Didiet Ananto Widodo', 'didietwidodo33@gmail.com', NULL, '$2y$12$IzYswlK5wtVLVPKF8DGgDu.jqMxIpahgqS1YucQ1YNsWpasE96zgO', NULL, '2025-07-10 08:03:46', '2025-07-10 08:03:46', NULL, 'default', NULL),
(32, 'SUTARTI YUNI ASTUTIK', 'astuti.sya@gmail.com', NULL, '$2y$12$gi.g.C.YuZOu/Svn9T323Ozqrtedg1NRQ3zIRrn9sIZuVvf6L9o.m', NULL, '2025-07-10 08:03:46', '2025-07-10 08:03:46', NULL, 'default', NULL),
(33, 'Shindy Ayu Widyaswara', 'shindyayu.widyaswara@gmail.com', NULL, '$2y$12$0dX/wIDLEFMwu70hqIibGexk8RddCJzbHNPKaoj6fT8TqO61fWDEK', NULL, '2025-07-10 08:16:42', '2025-07-10 08:16:42', NULL, 'default', NULL),
(34, 'Hilda Seskowati', 'hildayasmin2211@gmail.com', NULL, '$2y$12$io1W/sF88ifYF.sgype8J.6R4qJLmdnwZwoVzvSUQ/6ib/HJTYroa', NULL, '2025-07-10 08:16:44', '2025-07-10 08:16:44', NULL, 'default', NULL),
(35, 'Zulfian Arif', 'zulfian.arif@gmail.com', NULL, '$2y$12$bz1yH7n20pzFD5NzybHm0uFjuUeGXj.utLFKpXac9caNQdkwTNz8S', NULL, '2025-07-10 08:16:44', '2025-07-10 08:16:44', NULL, 'default', NULL),
(36, 'Arif Hambali', 'hambali.arif@gmail.com', NULL, '$2y$12$sdK4DGvQn04sKDPUXgIMpufQMiRfIxKiRR2NbjOK5a6YHyCcX/WeG', NULL, '2025-07-10 08:16:45', '2025-07-10 08:16:45', NULL, 'default', NULL),
(37, 'Tri Wahyu Utomo', 'my_hygeva@yahoo.com', NULL, '$2y$12$QiHxlIe00Ghqjl9lBlhGqO6iVb6.sEsHRFq/NwQ7bZGDpNTUWKtbS', NULL, '2025-07-10 08:16:45', '2025-07-10 08:16:45', NULL, 'default', NULL),
(38, 'Dessy Anggrainy', 'dessy.anggrainy87@gmail.com', NULL, '$2y$12$/gP8y6PQBboi12PHkhdKoOSaCsoVVlEuoI5/K/fp3XmPRG9cAcR52', NULL, '2025-07-10 08:22:06', '2025-07-10 08:22:06', NULL, 'default', NULL),
(39, 'KUSMILAH', 'Bundakus42300@gmail.com', NULL, '$2y$12$kfOxWAo9b1zrY6Zw9QooieLHkeE68/PSxsxDjBihy8TgbfNj2koiO', NULL, '2025-07-10 08:22:07', '2025-07-10 08:22:07', NULL, 'default', NULL),
(40, 'TITIEK TRISOESILOWATI', 'ndhoxsusie@gmail.com', NULL, '$2y$12$6fYXzIGSIZ0rX4C.X/CkEehIvj783nxR/VaN5JK2GAy.hZ515NFhW', NULL, '2025-07-10 08:22:07', '2025-07-10 08:22:07', NULL, 'default', NULL),
(41, 'IKE MARDIANA SADIYAH', 'ikerafi1@gmail.com', NULL, '$2y$12$F.Dq75Xbs5HUpza3k9ZYR.G3H8MVdI666/51i/xhqL0JIY2zbJnOy', NULL, '2025-07-10 08:25:03', '2025-07-10 08:25:03', NULL, 'default', NULL),
(42, 'Muhammad Jehan Rabbani', 'mjgila8@gmail.com', NULL, '$2y$12$S43ZU3.CqGJlycAAtnxkpezx68dWli8WhSNov9Pqn/CzzpGXcnmUG', NULL, '2025-07-10 08:29:37', '2025-07-10 08:29:37', NULL, 'default', NULL),
(43, 'Riyono', 'kopler87@gmail.com', NULL, '$2y$12$xz0/gy8f8IKk5eeqh5uRLOVjHyxyNB2J.zlyd3CKz75C0JwJrNiZG', NULL, '2025-07-10 08:29:38', '2025-07-10 08:29:38', NULL, 'default', NULL),
(44, 'Ardhea Citra Dhamayanty', 'ardheacitraa2807@gmail.com', NULL, '$2y$12$JGWWv2COZAAVEHLuXJnFXu31zAmDYyJyEqXEGtGTg.avzA3v1rfuu', NULL, '2025-07-10 08:37:01', '2025-07-10 08:37:01', NULL, 'default', NULL),
(45, 'Mila Nurjana', 'aisyah.nurjanah116@gmail.com', NULL, '$2y$12$6nr6jwhosLStrEGVEJAUEu2jdOhfycbJB/feuqtTHzEyU/mBy3lL.', NULL, '2025-07-10 08:37:02', '2025-07-10 08:37:02', NULL, 'default', NULL),
(46, 'Ila Nur Chasanah', 'ilanurchasanah5@gmail.com', NULL, '$2y$12$NgzRbhSP003rPrGgbVEekutQ1bSQ6HpAh3LhKv7V8pjIvFseUUQ66', NULL, '2025-07-10 08:37:02', '2025-07-10 08:37:02', NULL, 'default', NULL),
(47, 'Khoirunisa Sukma Hadi', 'sukma.khoirunisa21@gmail.com', NULL, '$2y$12$87Ea1WUH4ba2VujNDVwPG.Jd5Pb0X88CaSJrwAVobaQdjGnQuhSq.', NULL, '2025-07-10 08:37:02', '2025-07-10 08:37:02', NULL, 'default', NULL),
(48, 'Yessy Tri Hardiyanti', 'hardiyantiyessy@gmail.com', NULL, '$2y$12$hSK4BbgEoUG7WLhIKk6eUeVYkrJVZUirgi4cejZ1ncVxIUheDfSHe', NULL, '2025-07-10 08:37:03', '2025-07-10 08:37:03', NULL, 'default', NULL),
(49, 'Indah Wahyuningsih', 'sbw.mlg@gmail.com', NULL, '$2y$12$VRYfh5tgZ/Z/vtisM0hwoOYPdtQ/rJkTdaYkZZh.3IqInZjQgwUvO', NULL, '2025-07-10 08:37:03', '2025-07-10 08:37:03', NULL, 'default', NULL),
(50, 'Sharon Mahar Tanjung', 'sharonmahartn@gmail.com', NULL, '$2y$12$..5MeS62xbytY0QNDcxHXOnVPrJ20nz2e2q9z/ABtEVFRkmbtA/4m', NULL, '2025-07-10 08:37:04', '2025-07-10 08:37:04', NULL, 'default', NULL),
(51, 'Arry Puspitasari', 'trust.indigo@gmail.com', NULL, '$2y$12$944/6O9MdHjmtcebBH3RCOyoG37FYFmNc7qwxgEip7yk.kI8MQYo2', NULL, '2025-07-10 08:38:26', '2025-07-10 08:38:26', NULL, 'default', NULL),
(52, 'Rujiyani', 'rujiyaniyani@gmail.com', NULL, '$2y$12$rkudJc.LJnQWlNKoMPW3GuQKEnl.Wv.RDFGGbOHpx3h9.hjtwBsr.', NULL, '2025-07-10 08:44:15', '2025-07-10 08:44:15', NULL, 'default', NULL),
(53, 'Theresiana Septioningrum', 'theresiana07@gmail.com', NULL, '$2y$12$iSZwzeVvis4bkOtV1HhZF.ql4myp9BMFYrQaFKl3g2AxFQKOlkajG', NULL, '2025-07-10 08:44:17', '2025-07-10 08:44:17', NULL, 'default', NULL),
(54, 'Puji Rahayu', 'doublesix664@gmail.com', NULL, '$2y$12$C2RhHoFMwX6Af3Fk33AEAeidaEtqChgHoTbVTS23.es0UMRFC5AgW', NULL, '2025-07-10 08:46:37', '2025-07-10 08:46:37', NULL, 'default', NULL),
(55, 'Era Dhani Restari', 'Eradhani588@gmail.com', NULL, '$2y$12$QgrsODZdyKHJQReA8npb3OCh9TW722i4iLFt6bZP9yeUAFZGMhHUW', NULL, '2025-07-10 08:46:38', '2025-07-10 08:46:38', NULL, 'default', NULL),
(56, 'ROOS PANDAN WANGI(WIWIN).SH', 'roospandanwangi24@gmail.com', NULL, '$2y$12$T7x7fnkgtMB6FQHLgtSGGeCJu7KmpSbCjbovWaDgIAkG4VUeX4oZG', NULL, '2025-07-10 08:46:38', '2025-07-10 08:46:38', NULL, 'default', NULL),
(57, 'Farida Achadyah', 'faridachadyah1309@gmail.com', NULL, '$2y$12$06A.jfn9Es9FfvqNM6noJuhsLQ1V2g.DWx752X6yqfN/9vC9H8pmi', NULL, '2025-07-10 08:46:39', '2025-07-10 08:46:39', NULL, 'default', NULL),
(58, 'Aris Sugiarti', 'mbkaris80@gmail.com', NULL, '$2y$12$mX6UN/9jucl0aO2s000orOtiYjQiLAWAtDS8EQoVj38cqaxfgOmAW', NULL, '2025-07-10 08:46:39', '2025-07-10 08:46:39', NULL, 'default', NULL),
(59, 'YUNIAR PANDANSARI', 'yuniarpandansari67@gmail.com', NULL, '$2y$12$yVdWI4L8OwHlVNtKmbkB5.hxkRNDeZOSKPttp9ckW1H9mT27i.RR.', NULL, '2025-07-10 08:46:39', '2025-07-10 08:46:39', NULL, 'default', NULL),
(60, 'Rodiyah', 'rodiyah44307@gmail.com', NULL, '$2y$12$8QMdq.xYZam88lUZLIyajuAzuLiMEv52c3bickP9098qokbq.TxBG', NULL, '2025-07-10 08:46:40', '2025-07-10 08:46:40', NULL, 'default', NULL),
(61, 'Dewi Ayu Indriani', 'dewia4101@gmail.com', NULL, '$2y$12$YBqRipG2UJC0xRb7R96gaOp/vxCxAVPv/.gm7inWJjfrHTNP9CFtO', NULL, '2025-07-10 08:46:40', '2025-07-10 08:46:40', NULL, 'default', NULL),
(62, 'HERTIN SULISTYO RINI', 'hertinrini59@gmail.com', NULL, '$2y$12$nRDhhQ7QKy9f8KcF1vrlte5vALQiE5LhZQmfX5ysYJXLmnUH6SQaO', NULL, '2025-07-10 08:46:41', '2025-07-10 08:46:41', NULL, 'default', NULL),
(63, 'Eka Astuti', 'ekaastuti1980@gmail.com', NULL, '$2y$12$A2RC/bJmfpoRp8JJp6JIAuJB0NtuBEN2DS9/NDH4XUkI1xOY9Rpiy', NULL, '2025-07-10 08:50:12', '2025-07-10 08:50:12', NULL, 'default', NULL),
(64, 'HENI KUSUMAWATI', 'Henikusuma29@gmail.com', NULL, '$2y$12$Ep/Ad9oF56aFo.iabHgYLeLlQSV4A/myYSa6mDSDoYl3GE9iZlrja', NULL, '2025-07-10 08:50:12', '2025-07-10 08:50:12', NULL, 'default', NULL),
(65, 'Dicky Ramadhan', 'Dickyrammm97@gmail.com', NULL, '$2y$12$ROkN6q2cxwN5iLobwToXQuKYjnPGP32S4Cyf1c4AvZKPYT4VcqlCq', NULL, '2025-07-10 08:50:13', '2025-07-10 08:50:13', NULL, 'default', NULL),
(66, 'Juwariyah', 'e85riasuparji@gmail.com', NULL, '$2y$12$bLcZ8P.iOyubwlQvxvUaz.aayqoGvpPThtT.SL5WemhoMQsOGJnJ2', NULL, '2025-07-10 08:57:25', '2025-07-10 08:57:25', NULL, 'default', NULL),
(67, 'Muhammad Handy Rizki Prasetyo', 'tunjungsekarppl@gmail.com', NULL, '$2y$12$Vkudg5AxDAuZ3creQE9GPOXPMFSbv0AQYQ/r/8.uHJISyiau9nimm', NULL, '2025-07-10 08:57:25', '2025-07-10 08:57:25', NULL, 'default', NULL),
(68, 'TEJO WIRAWAN', 'tejowirawan1@gmail.com', NULL, '$2y$12$kHspA0A8MFpqCnieaNaGJ..4tHSponXI6Vt2n2DOFZk12DCoEI13W', NULL, '2025-07-10 08:57:26', '2025-07-10 08:57:26', NULL, 'default', NULL),
(69, 'Agus Dwi Prasetyo', 'agusdwiprasetyo07@gmail.com', NULL, '$2y$12$rE5Dj8uYDwtuvPOtFHGmxu4bWUt12NbXPX7Uz.Zw.cBS8PsHYJXhS', NULL, '2025-07-10 08:57:26', '2025-07-10 08:57:26', NULL, 'default', NULL),
(70, 'Fenny Gunawan', 'fennymarsudi@gmail.com', NULL, '$2y$12$V5fX4K3DWb6atYu1z9j/vuaqTYFAIcv1YSgsMagjOcJfSlNJ9ksBK', NULL, '2025-07-10 08:57:27', '2025-07-10 08:57:27', NULL, 'default', NULL),
(71, 'ERNAWATI', 'ernaina6@gmail.com', NULL, '$2y$12$Mc4lGQilsN33VYY.9pTRVO1Cvw0sDLZPqBOAPLAitLVBP.0agCZQm', NULL, '2025-07-10 08:57:27', '2025-07-10 08:57:27', NULL, 'default', NULL),
(72, 'Maulid Diyah Kholis Saputri', 'diyahmaulid234@gmail.com', NULL, '$2y$12$5fGVSBUHxzzCrdhVflA6wuECcE4AYJOXIthFMVFOoeGNJPSX1Lyz6', NULL, '2025-07-10 08:57:28', '2025-07-10 08:57:28', NULL, 'default', NULL),
(73, 'Nurfuady Rafi\' Alfauznursy', 'nurfuady1@gmail.com', NULL, '$2y$12$Sk5xCYtBJ0e3s59/JLu/R.16FYYxWRQfRYFKM4uosvgBY/hujRNuu', NULL, '2025-07-10 09:09:07', '2025-07-10 09:09:07', NULL, 'default', NULL),
(74, 'TALITHA ALIFAH', 'talithaalifah99@gmail.com', NULL, '$2y$12$m49IHG54YrMwT7VhmGIEXOz9.TyNBQnq2f7QyT1k5TqZ6a6todtJ.', NULL, '2025-07-10 09:30:42', '2025-07-10 09:30:42', NULL, 'default', NULL),
(75, 'ALFIATUL KHAMIMAH', 'alfi.sewing@gmail.com', NULL, '$2y$12$NevcpPKkz7GxgArvIHsJs.t4o.ZgTwGkM9S/LVGBLdkjjYpK/6ceq', NULL, '2025-07-10 09:30:43', '2025-07-10 09:30:43', NULL, 'default', NULL),
(76, 'Arinda Putri Dewi', 'dewiarindaputri3@gmail.com', NULL, '$2y$12$B/K.Ekjz5nWjV/c1UCqoqeV9FzpKLq7Pm/lvt8NtiuiOQT8ZsfYgq', NULL, '2025-07-10 09:30:43', '2025-07-10 09:30:43', NULL, 'default', NULL),
(77, 'Aulia Rachman', 'armand.m.513@gmail.com', NULL, '$2y$12$FKaosz/mOc6mkRrVrq9iEOvbo.e83T34KzVuqJi/DA2z4TQeHHoXO', NULL, '2025-07-10 09:33:34', '2025-07-10 09:33:34', NULL, 'default', NULL),
(78, 'Catur Sandy Cahyono', 'catursandy70@gmail.com', NULL, '$2y$12$lIp7XZgmmJ5aBxzFKGbxbOr7ni08rNp6FE9mkhLdt3241Wrwm8zva', NULL, '2025-07-10 09:33:34', '2025-07-10 09:33:34', NULL, 'default', NULL),
(79, 'Feri Achmad Ardiansyah', 'feriachmad683@gmail.com', NULL, '$2y$12$qOsqvOf9v4p5ELGVJkb7meFW9tzVw2YfZTVBXIIuZy27VtdKT7ZK2', NULL, '2025-07-10 09:33:35', '2025-07-10 09:33:35', NULL, 'default', NULL),
(80, 'Jasmine Amalia Nastiti S.Tr.Stat.', 'jasmine.amalia@bps.go.id', '2025-07-13 09:20:36', '$2y$12$gfCL2wXpi9vbvt4VJ6x3fuYRAIHSWbW47mgQCR688hAAlD61qRbky', 'i37sIoGjSFXTTLM336A9JG0oBAXDr0JVBVblOcNjwxjAqEXnpNI9aVxzdaiw', '2025-07-10 16:30:55', '2025-07-10 16:30:55', NULL, 'default', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `breezy_sessions`
--
ALTER TABLE `breezy_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `breezy_sessions_authenticatable_type_authenticatable_id_index` (`authenticatable_type`,`authenticatable_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employees_user_id_foreign` (`user_id`),
  ADD KEY `employees_team_id_foreign` (`team_id`);

--
-- Indexes for table `exports`
--
ALTER TABLE `exports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exports_user_id_foreign` (`user_id`);

--
-- Indexes for table `failed_import_rows`
--
ALTER TABLE `failed_import_rows`
  ADD PRIMARY KEY (`id`),
  ADD KEY `failed_import_rows_import_id_foreign` (`import_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `imports`
--
ALTER TABLE `imports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `imports_user_id_foreign` (`user_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `master_surveys`
--
ALTER TABLE `master_surveys`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `media_uuid_unique` (`uuid`),
  ADD KEY `media_model_type_model_id_index` (`model_type`,`model_id`),
  ADD KEY `media_order_column_index` (`order_column`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mitras`
--
ALTER TABLE `mitras`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mitras_email_unique` (`email`),
  ADD KEY `mitras_user_id_foreign` (`user_id`);

--
-- Indexes for table `mitra_teladans`
--
ALTER TABLE `mitra_teladans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mitra_teladans_mitra_id_foreign` (`mitra_id`),
  ADD KEY `mitra_teladans_year_quarter_index` (`year`,`quarter`),
  ADD KEY `mitra_teladans_team_id_index` (`team_id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `nilai1s`
--
ALTER TABLE `nilai1s`
  ADD KEY `nilai1s_transaction_id_foreign` (`transaction_id`);

--
-- Indexes for table `nilai2s`
--
ALTER TABLE `nilai2s`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nilai2s_mitra_teladan_id_foreign` (`mitra_teladan_id`),
  ADD KEY `nilai2s_user_id_foreign` (`user_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_group_name_unique` (`group`,`name`);

--
-- Indexes for table `socialite_users`
--
ALTER TABLE `socialite_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `socialite_users_provider_provider_id_unique` (`provider`,`provider_id`);

--
-- Indexes for table `surveys`
--
ALTER TABLE `surveys`
  ADD PRIMARY KEY (`id`),
  ADD KEY `surveys_payment_id_foreign` (`payment_id`),
  ADD KEY `surveys_team_id_foreign` (`team_id`);

--
-- Indexes for table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transactions_mitra_id_foreign` (`mitra_id`),
  ADD KEY `transactions_survey_id_foreign` (`survey_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `breezy_sessions`
--
ALTER TABLE `breezy_sessions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `exports`
--
ALTER TABLE `exports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_import_rows`
--
ALTER TABLE `failed_import_rows`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `imports`
--
ALTER TABLE `imports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `master_surveys`
--
ALTER TABLE `master_surveys`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `mitras`
--
ALTER TABLE `mitras`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `mitra_teladans`
--
ALTER TABLE `mitra_teladans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `nilai2s`
--
ALTER TABLE `nilai2s`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=176;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=161;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `socialite_users`
--
ALTER TABLE `socialite_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `surveys`
--
ALTER TABLE `surveys`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `teams`
--
ALTER TABLE `teams`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=143;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `employees_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `exports`
--
ALTER TABLE `exports`
  ADD CONSTRAINT `exports_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `failed_import_rows`
--
ALTER TABLE `failed_import_rows`
  ADD CONSTRAINT `failed_import_rows_import_id_foreign` FOREIGN KEY (`import_id`) REFERENCES `imports` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `imports`
--
ALTER TABLE `imports`
  ADD CONSTRAINT `imports_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `mitras`
--
ALTER TABLE `mitras`
  ADD CONSTRAINT `mitras_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `mitra_teladans`
--
ALTER TABLE `mitra_teladans`
  ADD CONSTRAINT `mitra_teladans_mitra_id_foreign` FOREIGN KEY (`mitra_id`) REFERENCES `mitras` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `mitra_teladans_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `nilai1s`
--
ALTER TABLE `nilai1s`
  ADD CONSTRAINT `nilai1s_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `nilai2s`
--
ALTER TABLE `nilai2s`
  ADD CONSTRAINT `nilai2s_mitra_teladan_id_foreign` FOREIGN KEY (`mitra_teladan_id`) REFERENCES `mitra_teladans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `nilai2s_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `surveys`
--
ALTER TABLE `surveys`
  ADD CONSTRAINT `surveys_payment_id_foreign` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `surveys_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_mitra_id_foreign` FOREIGN KEY (`mitra_id`) REFERENCES `mitras` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_survey_id_foreign` FOREIGN KEY (`survey_id`) REFERENCES `surveys` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
