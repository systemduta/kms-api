-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 29, 2021 at 11:56 AM
-- Server version: 10.3.29-MariaDB-cll-lve
-- PHP Version: 7.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `duatang1_api_staging_kms_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(5000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `image`, `title`, `description`, `created_at`, `updated_at`) VALUES
(11, 'book_60559417c0119.png', '1 VHS - Corporate Values', '1 Visi\nMenjadi Perusahaan yang disegani dalam berbagi bidang usaha dan berdampak positif bagi pemegang saham, lingkungan sekitar dan bangsa.\n\n1 Hati\nBekerja dengan cara, sikap hati dan respon yang benar.\n\n1 Semangat\nSemangat perubahan dan pantang menyerah kalau gagal coba lagi, kalau salah coba lagi dengan cara yang lain.\n\nVISI\n\"Pandangan terjauh yang dapat kita lihat.\"\n\nMengapa perlu VISI? \nSetiap kita punya GAGASAN, KEINGINAN, HARAPAN, CITA-CITA. Visi menolong kita lebih terarah, cepat, semangat, bermakna.\n\nPerusahaan ini juga punya visi.Apa yang dapat Anda lakukan untuk mencapai visi perusahaan? Dapatkah visi Anda sejalan dengan visi perusahaan ini?\n\nSimak materi Corporate Values secara lengkap pada link berikut : \n\nhttp://bit.ly/corporate-value-maesa', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `calendars`
--

CREATE TABLE `calendars` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_parent` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `code`, `name`, `is_parent`, `created_at`, `updated_at`) VALUES
(1, 'MH', 'MAESA HOLDING', 0, NULL, NULL),
(2, 'AUM', 'ANUGERAH UTAMA MOTOR', 0, NULL, NULL),
(3, 'BA', 'BANK ARTHAYA', 0, NULL, NULL),
(4, 'CMG', 'CUN MOTOR GROUP', 0, NULL, NULL),
(5, 'DTI', 'DUA TANGAN INDONESIA', 0, NULL, NULL),
(6, 'EKPG', 'ES KRISTAL PMP GROUP', 0, NULL, NULL),
(7, 'HC', 'HENNESSY CUISINE', 0, NULL, NULL),
(8, 'KSDM', 'KOPERASI SDM', 0, NULL, NULL),
(9, 'MF', 'MAESA FOUNDATION', 0, NULL, NULL),
(10, 'MHT', 'MAESA HOTEL', 0, NULL, NULL),
(11, 'MIT', 'MIXTRA INTI TEKINDO', 0, NULL, NULL),
(12, 'PEPG', 'PABRIK ES PMP GROUP', 0, NULL, NULL),
(13, 'PD', 'PANDHU DISTRIBUTOR', 0, NULL, NULL),
(14, 'PL', 'PRAMA LOGISTIC', 0, NULL, NULL),
(15, 'WMH', 'WERKST MATERIAL HANDLING', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `organization_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `video` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `organization_id`, `title`, `description`, `image`, `file`, `video`, `link`, `type`, `created_at`, `updated_at`) VALUES
(109, NULL, 'Oral Communication', 'Oral Communication (Komunikasi Lisan) adalah pertukaran informasi dan gagasan melalui kata yang diucapkan. Dapat dilakukan secara langsung dalam interaksi tatap muka atau melalui perangkat elektronik lain seperti telepon.', 'course_60543e7168894.png', 'files/sUSpAjBdT0.pdf', 'files/w5G6KPIiuG.mp4', 'https://www.youtube.com/watch?v=thbBBzgkP8E', 4, NULL, NULL),
(131, NULL, 'Written Communication', 'Written Communication (Komunikasi Tertulis) adalah proses penyampaian pesan atau pertukaran informasi antara dua orang atau lebih melalui kata-kata tertulis. Biasanya digunakan ketika informasi yang akan dikirimkan panjang dan mencakup beberapa istilah kompleks yang tidak dapat dijelaskan secara lisan.', 'course_609bac2fdfe48.png', 'files/HND95vOPvY.pdf', 'files/video_written_communication.mp4', 'https://www.youtube.com/watch?v=tf94uMEVTSc', 4, NULL, NULL),
(132, NULL, 'Presentation', 'Kemampuan presentasi adalah skill yang harus dimiliki oleh setiap orang, terutama yang berhubungan dengan dunia bisnis. Presentasi bisnis merupakan bentuk komunikasi yang berorientasi pada proposal, yang disajikan dalam suatu lingkungan bisnis kepada khalayak yang relatif homogen dari berbagai tingkat pengambil keputusan. Presentasi bisnis ini biasanyanya dilakukan untuk memperkenalkan suatu produk, mempromosikan produk, atau mengusulkan dilakukannya pengembangan produk atau bisnis. Karena itulah, dalam suatu presentasi bisnis harus harus ada persiapan yang matang dari presentator', 'course_60ab1995874c8.png', 'files/file_EY3APM7wED.pdf', 'files/course_video_presentation.mp4', 'https://www.youtube.com/watch?v=cUjewztqx14', 4, NULL, NULL),
(133, NULL, 'About discount', 'Ini adalah course tentang discount', 'course_60c71684acd78.png', 'files/file_KYWsmjpA7z.pdf', 'files/kdTnyrV2VPOYJKpn1GZcSn7up0YWJLM6iAZpGNsO.mp4', '#', 4, NULL, NULL),
(134, NULL, 'pots 1', 'Belajar desain modah dan menyenangkan', 'course_60c71e8146e39.png', 'files/file_05ZNBj3MGk.pdf', 'files/chP4aWrGs9QKtgv8N4N2FWNxQ9bm3iRAEKnLLLhJ.mp4', '#', 4, NULL, NULL),
(135, NULL, 'Portfolios', 'ini pembelajaran tentang desain', 'course_60c72a7b2e028.png', 'files/file_cRQEPyVICN.pdf', 'files/RdU5zV7dnlgICznyKbcAXsyZ4cmx6rcmqwUBEPzC.mp4', '#', 4, NULL, NULL),
(136, NULL, 'Mini VHS 1', 'Deskripsi singkat mengenai mini VHS adalah hal yang harus dipelajari satu sama lain', 'course_6021f55e79c0f.png', 'files/sUSpAjBdT0.pdf', 'files/Video-Respon_yang_Benar.mov', NULL, 3, '2021-06-25 06:38:56', '2021-06-25 06:38:56'),
(137, NULL, 'Mini VHS 2', 'Ini adalah mini vhs yang kedua', 'course_6021f5b302d68.png', 'files/file_EY3APM7wED.pdf', 'files/Video-Semangat_pantang_Menyerah.mp4', '#', 3, '2021-06-25 10:25:55', '2021-06-25 10:25:55');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(5000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `golongans`
--

CREATE TABLE `golongans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `golongans`
--

INSERT INTO `golongans` (`id`, `name`, `code`, `created_at`, `updated_at`) VALUES
(1, 'CEO / CSO / CFO / CPO / COO / CMO / Direktur', '8', NULL, NULL),
(2, 'General Manager', '7', NULL, NULL),
(3, 'Manager', '6', NULL, NULL),
(4, 'Supervisor', '5', NULL, NULL),
(5, 'Staff', '4', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `leaderboards`
--

CREATE TABLE `leaderboards` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `organization_id` bigint(20) UNSIGNED DEFAULT NULL,
  `point` bigint(20) UNSIGNED NOT NULL,
  `level` bigint(20) UNSIGNED NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `leaderboards`
--

INSERT INTO `leaderboards` (`id`, `organization_id`, `point`, `level`, `created_at`, `updated_at`, `user_id`) VALUES
(8, NULL, 80, 1, NULL, NULL, 46),
(9, NULL, 300, 3, NULL, NULL, 34),
(10, NULL, 160, 2, NULL, NULL, 36),
(11, NULL, 0, 0, NULL, NULL, 38),
(12, NULL, 20, 1, NULL, NULL, 44),
(13, NULL, 20, 1, NULL, NULL, 50),
(14, NULL, 120, 2, NULL, NULL, 37),
(15, NULL, 260, 3, NULL, NULL, 35),
(16, NULL, 40, 1, NULL, NULL, 33),
(17, NULL, 360, 4, NULL, NULL, 49),
(18, NULL, 140, 2, NULL, NULL, 40),
(19, NULL, 0, 0, NULL, NULL, 43),
(20, NULL, 160, 2, NULL, NULL, 51),
(21, NULL, 240, 3, NULL, NULL, 42),
(22, NULL, 180, 2, NULL, NULL, 41);

-- --------------------------------------------------------

--
-- Table structure for table `materis`
--

CREATE TABLE `materis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `materi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `video` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2016_06_01_000001_create_oauth_auth_codes_table', 2),
(5, '2016_06_01_000002_create_oauth_access_tokens_table', 2),
(6, '2016_06_01_000003_create_oauth_refresh_tokens_table', 2),
(7, '2016_06_01_000004_create_oauth_clients_table', 2),
(8, '2016_06_01_000005_create_oauth_personal_access_clients_table', 2),
(13, '2020_11_13_140331_create_companies_table', 3),
(14, '2020_11_13_140352_create_organizations_table', 3),
(15, '2020_11_13_140420_create_courses_table', 3),
(16, '2020_11_13_140421_alter_user_table', 3),
(19, '2020_11_17_140151_alter_organization_table', 4),
(22, '2020_11_17_142621_alter_course_table', 5),
(23, '2020_11_18_015037_alter_course_table_two', 6),
(26, '2020_11_18_055240_create_leaderboards_table', 7),
(27, '2020_11_18_061455_alter_leaderboard_table', 7),
(28, '2020_11_18_063859_create_events_table', 8),
(39, '2020_11_20_013351_create_materis_table', 9),
(40, '2020_11_20_013614_create_tests_table', 9),
(41, '2020_11_20_013728_create_test_questions_table', 9),
(42, '2020_11_20_013750_create_test_answers_table', 9),
(43, '2020_11_20_013823_create_user_scores_table', 9),
(44, '2020_11_20_032203_create_books_table', 10),
(45, '2020_11_21_140138_alter_test_table', 11),
(46, '2020_11_21_140424_alter_user_score_table', 12),
(47, '2020_11_21_140900_drop_test_table', 12),
(48, '2020_11_22_143120_alter_course_table_third', 13),
(51, '2020_11_22_160944_create_calendars_table', 14),
(52, '2020_12_17_032336_alter_user_table_two', 15),
(53, '2020_12_18_035027_alter_user_tablehirdo', 16),
(54, '2021_01_07_063325_alter_course_table_four', 17),
(55, '2021_01_07_075634_alter_user_course_table_four', 18),
(56, '2021_01_08_044350_alter_user_table_token', 19),
(63, '2021_05_05_074440_create_golongans_table', 20),
(64, '2021_05_05_090630_add_golongan_id_to_users_table', 20),
(66, '2021_06_15_020222_create_vhses_table', 21),
(67, '2021_06_28_015948_add_is_pretest_to_test_questions_table', 22),
(68, '2021_06_28_024046_add_is_pre_test_to_user_scores_table', 22);

-- --------------------------------------------------------

--
-- Table structure for table `oauth_access_tokens`
--

CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_access_tokens`
--

INSERT INTO `oauth_access_tokens` (`id`, `user_id`, `client_id`, `name`, `scopes`, `revoked`, `created_at`, `updated_at`, `expires_at`) VALUES
('003e9600233f28afcba8dae7b6c406cadf830bf749d64004645fc4e0a601624513da5ea3efe06f5d', 7, 1, 'nApp', '[]', 0, '2020-11-27 00:53:43', '2020-11-27 00:53:43', '2021-11-27 07:53:43'),
('01ae6ee9bf44c9d187a73cb41e79422437fcca00e6aeefb9f22f728617efc2d602cd5f35bef49212', 4, 1, 'nApp', '[]', 0, '2021-01-12 20:15:42', '2021-01-12 20:15:42', '2022-01-13 03:15:42'),
('02e11a1cd50277ab312b716ae6e53b819b752a6585f1079f95738993e1f1cf023d18d2ddca742b7a', 4, 1, 'nApp', '[]', 0, '2020-12-08 19:45:11', '2020-12-08 19:45:11', '2021-12-09 02:45:11'),
('0315b23fb40e4c006f1bf58e958bef8605d4667ef52246f882d6a8ca3bb44b5ebda7e4f00de55e82', 4, 1, 'nApp', '[]', 0, '2021-01-18 21:25:40', '2021-01-18 21:25:40', '2022-01-19 04:25:40'),
('033074621e138e6f945854f7f60cefd254f1e6a98eaed245e62b5473a7aae4a7cb292239c10a2e7e', 7, 1, 'nApp', '[]', 0, '2020-11-27 01:11:30', '2020-11-27 01:11:30', '2021-11-27 08:11:30'),
('03ee84c42038fe60e9308b76627d81dcfcf105638528247133499c10df597c8a8a31334d934837e3', 4, 1, 'nApp', '[]', 0, '2020-11-24 01:09:47', '2020-11-24 01:09:47', '2021-11-24 08:09:47'),
('045735adf83b62c8c3a0b62c6404288f58146e4e6b50f7ad789b35d5a75f72d32a9990843e429aa3', 32, 1, 'nApp', '[]', 0, '2021-03-22 20:04:30', '2021-03-22 20:04:30', '2022-03-23 03:04:30'),
('04d57007379a55a9c1e98bbf510053069eae6faeefd33bb825b4c5cb08b37b45e3dbd4535cc300d9', 4, 1, 'nApp', '[]', 0, '2020-11-22 08:27:11', '2020-11-22 08:27:11', '2021-11-22 15:27:11'),
('04e4cfc6828ae3780f5101532fe1bf4586ed7eb0715d301db7cd7e3e64d8e26b96af978f642866f9', 21, 1, 'nApp', '[]', 0, '2021-01-19 07:37:44', '2021-01-19 07:37:44', '2022-01-19 14:37:44'),
('05a6c13e07e63bac002ae4bf8694505f157418d69d426a6fa7e74af8d13ddf3cc768f90b9e13581c', 32, 1, 'nApp', '[]', 0, '2021-05-19 20:38:04', '2021-05-19 20:38:04', '2022-05-20 03:38:04'),
('088de0eb0e426d3849aac7ff8634c259be3e84cdb0f80fec03ff791c9ecb068f1c33c49c3165dd03', 21, 1, 'nApp', '[]', 0, '2021-01-18 20:48:36', '2021-01-18 20:48:36', '2022-01-19 03:48:36'),
('08d5a836e409eaa05b25e5b4f8ab3cc92bd167ac1cf34c8196baf1aa1b07229ab89335718e3fce9a', 4, 1, 'nApp', '[]', 0, '2020-12-03 01:12:04', '2020-12-03 01:12:04', '2021-12-03 08:12:04'),
('099b67e6bfa30f2a717f04b283cd12d67f42f18f2f9e95d69a41526f6b603e78e0e71129e98c8fd7', 4, 1, 'nApp', '[]', 0, '2021-01-13 01:09:20', '2021-01-13 01:09:20', '2022-01-13 08:09:20'),
('09dc8ea6df922a7008959a39fa62d0fa5b45014c2e65db2c386c68f94e4b5d777c12429ee12c941d', 32, 1, 'nApp', '[]', 0, '2021-05-11 21:12:33', '2021-05-11 21:12:33', '2022-05-12 04:12:33'),
('0a041011c675b660dd4d6cf9f4918b1406893d5a0bae259a2be4dc5617314dbdf3cd89cca5b08fb5', 21, 1, 'nApp', '[]', 0, '2021-01-04 02:20:13', '2021-01-04 02:20:13', '2022-01-04 09:20:13'),
('0b32ed58cb33e48df051ce94d7140685b76a4bfabc2ea6a851324fb77ccd4abb752cdb80f17eff68', 4, 1, 'nApp', '[]', 0, '2021-01-15 01:14:41', '2021-01-15 01:14:41', '2022-01-15 08:14:41'),
('0b4cb05eed4ec32b857f370919ae52658d8c926d69a2461f7187b68f47dbcaa5b3257faf1f082891', 32, 1, 'nApp', '[]', 0, '2021-05-20 00:09:19', '2021-05-20 00:09:19', '2022-05-20 07:09:19'),
('0b70aa65e754902bc02a7d2055aa4ba04b4ead03d8d3eaed6311b5e44d36e88c30fc037ffc5eed49', 40, 1, 'nApp', '[]', 1, '2021-02-22 01:07:07', '2021-02-22 01:07:07', '2022-02-22 08:07:07'),
('0be1f2e2e1d5e3198fbdc6d033dc125f8ad70efb72d411ea382be5e1b56ddcde78a3a97c7f0fffbb', 46, 1, 'nApp', '[]', 0, '2021-05-17 20:21:56', '2021-05-17 20:21:56', '2022-05-18 03:21:56'),
('0ed4867d6a61e5c32ea4012f16f84fcc0d4bba6263080dcb0cd31c4ea8b8e4a09d102ff71e82bc2c', 42, 1, 'nApp', '[]', 1, '2021-06-01 23:38:49', '2021-06-01 23:38:49', '2022-06-02 06:38:49'),
('0f00af34c40c994d02fdd512918d2c114876580410fb44f0468f7373a34684e6dd0cc7aec76617a5', 4, 1, 'nApp', '[]', 0, '2020-12-17 19:41:43', '2020-12-17 19:41:43', '2021-12-18 02:41:43'),
('0f6c29908720348973186bebfdce7d3d2a67c11fcd69ecf7d007bc65c4a419cbe396c1a64e880ad3', 4, 1, 'nApp', '[]', 0, '2021-01-07 21:15:54', '2021-01-07 21:15:54', '2022-01-08 04:15:54'),
('0fd7e924c169f50f72c25fde5326216ddf5d1abde861cfc1224a79125e428dac8df7693e2fc206c6', 46, 1, 'nApp', '[]', 0, '2021-06-28 19:46:47', '2021-06-28 19:46:47', '2022-06-29 02:46:47'),
('10427b5ce31ad6e6bbaf6a5161b404b22320db830bf3fccb8ed04992cf15f7c97bc7128c9be72dd3', 4, 1, 'nApp', '[]', 0, '2021-01-14 00:53:08', '2021-01-14 00:53:08', '2022-01-14 07:53:08'),
('1203110a041a960581704c55aa9039f3bbb6c0723c29177927a104fcd4f72a0ddc4345ec35099061', 4, 1, 'nApp', '[]', 0, '2021-01-04 23:50:39', '2021-01-04 23:50:39', '2022-01-05 06:50:39'),
('123459e40781552b64db390a58f5cc2916a227f9e9bc82ec33cf62d9336e93496b41448a23acb3e0', 37, 1, 'nApp', '[]', 0, '2021-03-30 19:05:30', '2021-03-30 19:05:30', '2022-03-31 02:05:30'),
('123aba2d89d6870baa7de519c93a553f6c164e581df6078ae7f3b8d66c2c592ada84945d0806a496', 7, 1, 'nApp', '[]', 0, '2020-11-17 06:38:15', '2020-11-17 06:38:15', '2021-11-17 13:38:15'),
('1287e9cc6f05a50620ffc905c0a7cca2946b8d019fc386a9536a9e992760e29e71c44961d782c587', 21, 1, 'nApp', '[]', 0, '2020-12-17 21:04:19', '2020-12-17 21:04:19', '2021-12-18 04:04:19'),
('12e3aa794e5e7a3218d267532359954a8143a2ba3c553556e8cc6e1d0ff38d4e088305dd07dbc02c', 7, 1, 'nApp', '[]', 0, '2020-11-26 08:33:31', '2020-11-26 08:33:31', '2021-11-26 15:33:31'),
('13448322d9acfc4cab335f3cbcc53512bc6bc26be06b306c999dbb1c74f3b750fa4ce8590173fec4', 21, 1, 'nApp', '[]', 0, '2021-01-18 02:17:34', '2021-01-18 02:17:34', '2022-01-18 09:17:34'),
('13cc364823a6f04ae41cdba02f357ebf4b9e1705128f0e5dad4f0dcd0c9ef48f542c090092f110eb', 51, 1, 'nApp', '[]', 0, '2021-06-27 19:17:54', '2021-06-27 19:17:54', '2022-06-28 02:17:54'),
('148ba1ffb83c566f8497dc6f6f5e2f00c76c19d30930f32d450656e7dad4687f230a342a79d4d15f', 46, 1, 'nApp', '[]', 0, '2021-05-23 20:41:41', '2021-05-23 20:41:41', '2022-05-24 03:41:41'),
('152301f2dca09324be6c669c43af479e882214d0c59a132adcfdceb11de2c693798b7215fade91b2', 4, 1, 'nApp', '[]', 0, '2020-12-03 00:51:19', '2020-12-03 00:51:19', '2021-12-03 07:51:19'),
('1531d1cf1bd7c8eb0c20f82d0e8223a754e359431beced420da6d654b5dcd8e98206595ae4c83fda', 7, 1, 'nApp', '[]', 0, '2020-11-24 20:11:58', '2020-11-24 20:11:58', '2021-11-25 03:11:58'),
('166537978c28a5a3b7e77861491992ab57fbe9030ef0fdda756d774b9068d49377ed32ca5530592c', 4, 1, 'nApp', '[]', 0, '2020-12-15 23:40:36', '2020-12-15 23:40:36', '2021-12-16 06:40:36'),
('16bec460aa3b66bcb4d759b399d5e8d95e4709bf4a1a0ba71e9d069cd31e1f75606754c4cfb966e5', 46, 1, 'nApp', '[]', 0, '2021-06-13 20:08:09', '2021-06-13 20:08:09', '2022-06-14 03:08:09'),
('1728bd47e860c206259b15d81d6c862006e9ff89a4cb06f2612c23190352979f2fe8ca9a0c78e1f9', 4, 1, 'nApp', '[]', 0, '2020-12-02 00:22:00', '2020-12-02 00:22:00', '2021-12-02 07:22:00'),
('177d7b20c44a6e639d43101e9a64f6173d9056108732228fab3b14f32541daf232b052648881d0d1', 20, 1, 'nApp', '[]', 1, '2021-01-18 20:16:34', '2021-01-18 20:16:34', '2022-01-19 03:16:34'),
('17d636d09b1deaac3256c103a36f04b922bf6b088422516ec172d7db8c84e5329608e66818bffc49', 32, 1, 'nApp', '[]', 0, '2021-06-14 19:44:01', '2021-06-14 19:44:01', '2022-06-15 02:44:01'),
('182440e6caddf58dec74f43b57f1f8189a17c11801801a12b7e10e37f1ca188c24b5282db15d6851', 4, 1, 'nApp', '[]', 0, '2020-12-16 07:36:04', '2020-12-16 07:36:04', '2021-12-16 14:36:04'),
('1936b8da7db4ad55431d64c4901eca52a9ec89cd88bf8475efe0bfab638e4641e8ecc1498819b4b1', 18, 1, 'nApp', '[]', 0, '2020-12-17 20:01:25', '2020-12-17 20:01:25', '2021-12-18 03:01:25'),
('19512c3b3eb0fe5c2c2cfe9f1210f4a67c2007542270a6a8241fa50ff78d875cd50c2d2cf2842792', 21, 1, 'nApp', '[]', 1, '2021-01-17 20:38:08', '2021-01-17 20:38:08', '2022-01-18 03:38:08'),
('1964a658679732723dfc297632dc57e8de343e0eaf597b4fa10ad8194cc4221c208a0ef5139271f0', 33, 1, 'nApp', '[]', 0, '2021-02-22 00:20:59', '2021-02-22 00:20:59', '2022-02-22 07:20:59'),
('1a0d5d5d00967e98a946c4fd44ef1f95f425f1b3c5dade77be68001381468ca97c2c9bae0de5ad9d', 4, 1, 'nApp', '[]', 0, '2020-12-02 21:52:51', '2020-12-02 21:52:51', '2021-12-03 04:52:51'),
('1a21c55c823efb50aa32f3123182279705c9747a1d6007eb826be81bd3548cf6854235427311476e', 32, 1, 'nApp', '[]', 0, '2021-06-15 19:05:06', '2021-06-15 19:05:06', '2022-06-16 02:05:06'),
('1a55d9d1d08d92c07c0d8d2d17d6d0b65c933224eb953cc7fad115d37401a100b8c415313b887aee', 48, 1, 'nApp', '[]', 0, '2021-02-22 01:31:49', '2021-02-22 01:31:49', '2022-02-22 08:31:49'),
('1a6f139f8bdc67fd21c0d43eb1cbd96e2db22786ed0cfb2495e5955978141b7133aed5130e0598d2', 21, 1, 'nApp', '[]', 1, '2021-01-18 01:56:29', '2021-01-18 01:56:29', '2022-01-18 08:56:29'),
('1a7a39917d256b585ec3a015cd6d18711cb32db3f87ec5ef39f301201496e8eb1a453f2aa9adb40d', 32, 1, 'nApp', '[]', 0, '2021-05-19 03:16:03', '2021-05-19 03:16:03', '2022-05-19 10:16:03'),
('1b4fc9c1ee0a52ef6ca2288da2cd2b051e6ecb29421380ad647eef87dbe0c5d5d5ae27f962e013c3', 32, 1, 'nApp', '[]', 0, '2021-05-16 23:54:53', '2021-05-16 23:54:53', '2022-05-17 06:54:53'),
('1c0b8ea8dd0f2f2dd2197396e77b6cc14845e08def4cfb549885f14416f9b02cc4e4234db34ec6d2', 41, 1, 'nApp', '[]', 1, '2021-06-01 23:48:57', '2021-06-01 23:48:57', '2022-06-02 06:48:57'),
('1c1512957930ef198391807ff42a4642873c28a716803acec3516c92ef8ca2b7d096253ca7993bfa', 4, 1, 'nApp', '[]', 0, '2020-12-16 07:42:15', '2020-12-16 07:42:15', '2021-12-16 14:42:15'),
('1c3c826f8e3b1a5ddaa5a2e6b291a371443c8c36d7de8ec6eb73614a2ef6720c206f1a7f614d10db', 34, 1, 'nApp', '[]', 0, '2021-03-19 23:30:16', '2021-03-19 23:30:16', '2022-03-20 06:30:16'),
('1c65fa10566dc4d7b70eaddeb4cce9e72d8ebb3884fe950182fd694d382776866273844fccef8b40', 32, 1, 'nApp', '[]', 0, '2021-05-12 02:17:45', '2021-05-12 02:17:45', '2022-05-12 09:17:45'),
('1d135a4c4333a16568a9082bea80aab787392e943be758bc7fd79fe9060bae372e65753c89e02fed', 7, 1, 'nApp', '[]', 0, '2020-11-24 19:59:32', '2020-11-24 19:59:32', '2021-11-25 02:59:32'),
('1d8654738da69e273c3ebb31b27f18bfe5898c7345fd8246ffab95c5dd14f035f19f8a459f647e84', 46, 1, 'nApp', '[]', 0, '2021-06-27 21:40:51', '2021-06-27 21:40:51', '2022-06-28 04:40:51'),
('1e80199b054ab7e30b80671fd230f834ae18e204990901da1ad2c4f679bf0731b511ad46ca98a8de', 7, 1, 'nApp', '[]', 0, '2020-11-24 20:03:51', '2020-11-24 20:03:51', '2021-11-25 03:03:51'),
('1eeb052e9f5042f0280eb75f76658f0abb4c575a7902dcea35c6edd830b15cbf92c310ee557bcb94', 7, 1, 'nApp', '[]', 0, '2020-11-25 19:02:42', '2020-11-25 19:02:42', '2021-11-26 02:02:42'),
('20a9d200a0e3ec3e8f18d58dee44ed7c037d4ca4b0fa06eb22876e3b8f0064c3aa3241be917ae85b', 21, 1, 'nApp', '[]', 0, '2021-01-18 20:36:20', '2021-01-18 20:36:20', '2022-01-19 03:36:20'),
('21434ef25904f4020a73087da9e07ff686917cbe3cd4cf6437883198eda1ea809ae74992086e2dd0', 7, 1, 'nApp', '[]', 0, '2020-11-29 20:56:10', '2020-11-29 20:56:10', '2021-11-30 03:56:10'),
('21ba899540181fb8af7e02692993962a31f33cc0f69228c61411018b2027c40ceced44b2583fc350', 3, 1, 'nApp', '[]', 0, '2020-11-15 20:19:24', '2020-11-15 20:19:24', '2021-11-16 03:19:24'),
('2248d92ccadab663236c9b1e339e195c3f3ba663d8bf87f7179012038e2a6c653765de251137f509', 5, 1, 'nApp', '[]', 0, '2020-12-01 01:44:48', '2020-12-01 01:44:48', '2021-12-01 08:44:48'),
('22ff0a0e92c9f82eb4a13a59188c824baa6230b654b1a3d2ff499a9dc8a0fcb427fa54dc6f585a85', 5, 1, 'nApp', '[]', 0, '2020-11-16 19:57:55', '2020-11-16 19:57:55', '2021-11-17 02:57:55'),
('23e551c446c02468fe4d6888f03e427102d5b60543d4635626595b694dc642d14913fde35c159ae6', 44, 1, 'nApp', '[]', 0, '2021-03-21 23:57:56', '2021-03-21 23:57:56', '2022-03-22 06:57:56'),
('2522e3a9cc204c180c16cb8b7614e95e981feeaf8cec808143300b327ebf380312602f1aefa9c8a2', 32, 1, 'nApp', '[]', 0, '2021-02-07 23:01:13', '2021-02-07 23:01:13', '2022-02-08 06:01:13'),
('2526bcd8e83171e54ae8cb1d73aa2af4111551f5efacb5c0f6046b8c5f4fb04574944f23a2762760', 4, 1, 'nApp', '[]', 0, '2020-11-24 19:20:53', '2020-11-24 19:20:53', '2021-11-25 02:20:53'),
('27237a5d278a6de3a935b74b242db0092de828881450b5131bcfd950a41b0b3116a691b103ad65ad', 21, 1, 'nApp', '[]', 0, '2021-01-18 01:55:29', '2021-01-18 01:55:29', '2022-01-18 08:55:29'),
('272fe48da78051bbd89fb8ec2ac100c98e78fad1ae5eef0cd834d70665c2b889cba164cf0de4dcf6', 4, 1, 'nApp', '[]', 0, '2020-11-17 06:41:11', '2020-11-17 06:41:11', '2021-11-17 13:41:11'),
('279ce74461b9920b45f0948ae51b47f2a21335b48f86040a991266ac54842bd19d774becae7bb92e', 7, 1, 'nApp', '[]', 0, '2020-11-24 20:15:00', '2020-11-24 20:15:00', '2021-11-25 03:15:00'),
('27a334bf86af0b32c401b6acd5d95d8bd69e9ca3bf7ea31de81dfcfa0f972d5ce752c6e5babcdf8c', 21, 1, 'nApp', '[]', 0, '2020-12-20 21:29:03', '2020-12-20 21:29:03', '2021-12-21 04:29:03'),
('28c04d1ea24eee7019d0c732a035a9e80ec76b0b063acb31364fab244f5094fb46ad6b9e424b23f3', 4, 1, 'nApp', '[]', 0, '2021-01-13 07:04:31', '2021-01-13 07:04:31', '2022-01-13 14:04:31'),
('28dd63a82f699f1d85cd9aedc616569832e869131a803151f46a9563885dfd9d44d03c347467ca4f', 32, 1, 'nApp', '[]', 0, '2021-05-06 18:57:16', '2021-05-06 18:57:16', '2022-05-07 01:57:16'),
('29f29db8d0b6b0e94c8378048b5026a00ebb1dde3fbf4ece41369f835fa9eea81fbfdf7273ffc71a', 7, 1, 'nApp', '[]', 0, '2020-11-27 00:57:35', '2020-11-27 00:57:35', '2021-11-27 07:57:35'),
('2a86377407dc6ee1cba9a1f3d594a5de1324492a748035c2ea1c427bc868a0392326548840ce1496', 46, 1, 'nApp', '[]', 0, '2021-05-19 01:01:28', '2021-05-19 01:01:28', '2022-05-19 08:01:28'),
('2b005b12263a0216d84dd30ae6691ac52b0cd9876db9eae107f690940b6c8904ec36b694faa57f8a', 32, 1, 'nApp', '[]', 0, '2021-05-03 18:16:17', '2021-05-03 18:16:17', '2022-05-04 01:16:17'),
('2b340b29123f5006b75e464edc4221ee439f8a014c6f47bec74b2a008962a616044594066e8a88e4', 4, 1, 'nApp', '[]', 0, '2020-11-23 21:05:05', '2020-11-23 21:05:05', '2021-11-24 04:05:05'),
('2c08995017788b3cd33635d627498e7573f0e7097c3d609c44715d46012e084e5927c7cab57e7077', 34, 1, 'nApp', '[]', 0, '2021-03-19 01:12:20', '2021-03-19 01:12:20', '2022-03-19 08:12:20'),
('2dfc7b0d8a48e92bb32d4a3bb62666d951b8634bae23dc7f4d81102354f5887c652cde91842f7830', 32, 1, 'nApp', '[]', 0, '2021-05-19 03:21:23', '2021-05-19 03:21:23', '2022-05-19 10:21:23'),
('30210fabdfce9c9038b74b5d8c5d708ac6459f24fa5f22669c6c5fdad58430f399a191a8bd9b0d1b', 7, 1, 'nApp', '[]', 0, '2020-11-26 09:40:59', '2020-11-26 09:40:59', '2021-11-26 16:40:59'),
('30c6da76857925fec229af2f58395dda77a8278fdd5c1796e9a6e143631d851e35e8f29fbb462e58', 46, 1, 'nApp', '[]', 0, '2021-05-05 19:58:31', '2021-05-05 19:58:31', '2022-05-06 02:58:31'),
('311808b5ff50f6b7b772020e1f5041ccb67e937ee3e517ea3c6572b8a1b124befd1db602f42b7b3d', 40, 1, 'nApp', '[]', 0, '2021-03-21 23:47:57', '2021-03-21 23:47:57', '2022-03-22 06:47:57'),
('316199ca90035b8d12847fdd911574c02c88366ec95c70bb1e9fec075711934b4d6eed5bd6b0473e', 4, 1, 'nApp', '[]', 0, '2021-01-15 01:18:56', '2021-01-15 01:18:56', '2022-01-15 08:18:56'),
('31c5f0caf7d67c271c7dc2793f138e1f91339a8b2d1728105b07b9c74046e398e775df2a696179a2', 7, 1, 'nApp', '[]', 0, '2020-11-17 06:24:40', '2020-11-17 06:24:40', '2021-11-17 13:24:40'),
('331c1dda403da1acd0fa0c3364e335b290c3f53f959da5426a93d056570fc7d544ef0a7b1001a7c3', 32, 1, 'nApp', '[]', 0, '2021-02-02 03:28:06', '2021-02-02 03:28:06', '2022-02-02 10:28:06'),
('3346aca0131652530cc9aa56d499bf2fbc565cdbcf555898ca01b91cb4b1f5df4348a699bc8c25c8', 15, 1, 'nApp', '[]', 0, '2020-12-16 07:38:01', '2020-12-16 07:38:01', '2021-12-16 14:38:01'),
('33b06b5fb0be9afdd17cf2e2a1daf5aea172aa09552060ebb72e8a96e9b34c1ce1ad133b456070a9', 4, 1, 'nApp', '[]', 0, '2020-11-17 06:44:32', '2020-11-17 06:44:32', '2021-11-17 13:44:32'),
('3438e360685d3e0b93b88438bceca51ef36ac396ea6e85d3c7437fd53e72da332601127e0b5b8e9c', 32, 1, 'nApp', '[]', 0, '2021-05-05 19:25:43', '2021-05-05 19:25:43', '2022-05-06 02:25:43'),
('345df12b147fffee2cefa6314f1c90f7cc3cf6ae6da35732f670c8999435309c8ea92fa4847c94c7', 4, 1, 'nApp', '[]', 0, '2020-11-17 07:37:17', '2020-11-17 07:37:17', '2021-11-17 14:37:17'),
('3492464f624b7086d56445be4ac2f7da5a5074b830f24bf93c4a91bc4ac2d3fb59716c5dc1d0d73c', 7, 1, 'nApp', '[]', 0, '2020-11-17 00:42:32', '2020-11-17 00:42:32', '2021-11-17 07:42:32'),
('349eb6fbb8c5a6a5b5c3577b3418ff69919f8bc18c319f26e92e9a0fe69c90db85a7d907980fcac8', 7, 1, 'nApp', '[]', 0, '2020-11-24 19:47:57', '2020-11-24 19:47:57', '2021-11-25 02:47:57'),
('34dd2092e9e12525bae9b479f11cdc314df572e5a2ced5e761733be83841b321fa81e15b820bf504', 21, 1, 'nApp', '[]', 0, '2021-01-12 03:03:16', '2021-01-12 03:03:16', '2022-01-12 10:03:16'),
('3534d794c07655b06c1109a165f86ef6615a16a893a2bf95f852228b8d1ee1412fc762cc5ff745ff', 4, 1, 'nApp', '[]', 1, '2020-11-22 08:46:24', '2020-11-22 08:46:24', '2021-11-22 15:46:24'),
('35a7993b95f25b833dc7d10170b4477b9a259021e0be20a925bfcda55284bbf391b2fa0b0a27012c', 32, 1, 'nApp', '[]', 0, '2021-02-08 19:55:47', '2021-02-08 19:55:47', '2022-02-09 02:55:47'),
('35e48a96af99c38cc9e3f0c50cf4f5a5abdbf46d4269d31719badec1672e0c9af51b100a59337041', 4, 1, 'nApp', '[]', 0, '2021-01-18 01:45:35', '2021-01-18 01:45:35', '2022-01-18 08:45:35'),
('36a748b08f7cd112cae912745f60fa4195b01f3cdadc7b20234f413e5767fce1f618edfcdab64834', 3, 1, 'nApp', '[]', 0, '2020-11-16 02:08:42', '2020-11-16 02:08:42', '2021-11-16 09:08:42'),
('39da598225676fb1741f1631d16dab7e9d4be5ad70ce80d534e7eb2dfad32cc99f9ed2ba71c405c4', 32, 1, 'nApp', '[]', 0, '2021-06-16 02:21:35', '2021-06-16 02:21:35', '2022-06-16 09:21:35'),
('3a75f409919383885bc4950a2f18e40fc985ff230f333c31d1fdbd90d96bdfa1aa2d184b16548df6', 15, 1, 'nApp', '[]', 0, '2020-12-16 02:28:46', '2020-12-16 02:28:46', '2021-12-16 09:28:46'),
('3b3ebe1ec10243d0f7b398769ec745ceea6bf3e0a63427c056d9ad4d5386398b366e892a627ba974', 4, 1, 'nApp', '[]', 0, '2020-11-24 00:59:33', '2020-11-24 00:59:33', '2021-11-24 07:59:33'),
('3b7039a06f450207af76dbe31088147843e4b3f2a3b4eebcbb0a3f216c441a1839f5eb705fbcd82a', 7, 1, 'nApp', '[]', 0, '2020-11-25 02:49:17', '2020-11-25 02:49:17', '2021-11-25 09:49:17'),
('3b9a4c3903f2ded2682f0019f27748b8b6b88037f31f9f583ca6f9cf78cb294690204b0e7f7c3105', 7, 1, 'nApp', '[]', 0, '2020-11-26 08:58:31', '2020-11-26 08:58:31', '2021-11-26 15:58:31'),
('3bfea51b166a86ddd1f8a3a38d14fa88b41cc4612f1142674c155c46c8318caaa5d2b43c84dbade5', 7, 1, 'nApp', '[]', 0, '2020-11-29 21:36:02', '2020-11-29 21:36:02', '2021-11-30 04:36:02'),
('3ce6b0eba5c4f4183b22eaa6ec75d399b1eed86afb8f1a6fe20b790f1c877e97d2f4ca6c2b717a14', 7, 1, 'nApp', '[]', 1, '2020-12-01 19:47:26', '2020-12-01 19:47:26', '2021-12-02 02:47:26'),
('3e069adb13000ede69f363341a3adc4a76687e7bd68d9110d91986ba4b5d3d4031cc122df231c5cc', 51, 1, 'nApp', '[]', 0, '2021-06-27 19:48:19', '2021-06-27 19:48:19', '2022-06-28 02:48:19'),
('3e23d34d9835a84740f732f7a7e08e06dc48989cb343daa140725082ea1568b426727cf5d1972e5c', 38, 1, 'nApp', '[]', 0, '2021-03-21 23:45:30', '2021-03-21 23:45:30', '2022-03-22 06:45:30'),
('3edd82d065753c4a8c23ccb7ed5b9e630be151fe672e630957d28bb89e5e7a85a25981f042b0b0e9', 7, 1, 'nApp', '[]', 0, '2020-11-29 23:19:14', '2020-11-29 23:19:14', '2021-11-30 06:19:14'),
('3f48a4724fe20eaf3099c98ec06aea36aa7de6af5de703bb350ae84b0300e743faea79441cc27b42', 4, 1, 'nApp', '[]', 1, '2020-12-15 21:36:55', '2020-12-15 21:36:55', '2021-12-16 04:36:55'),
('3f5d451a4338fbf10002a7008b5a1a77c85318350ab516be5666ed38d9607f9d3c356daa1319e68a', 2, 1, 'nApp', '[]', 0, '2020-11-13 07:46:26', '2020-11-13 07:46:26', '2021-11-13 14:46:26'),
('3f5f546b00aff15fb73903dc09dc1fcbb6079876299d25b43ac2188222c229b162cd087c7387477b', 32, 1, 'nApp', '[]', 0, '2021-02-23 19:56:12', '2021-02-23 19:56:12', '2022-02-24 02:56:12'),
('3fcb47422edf31fbe7a5b5b0c50cef2823dc2aa43403115916f55a7eac3a261cf541817a88b3316e', 28, 1, 'nApp', '[]', 1, '2021-01-19 07:49:49', '2021-01-19 07:49:49', '2022-01-19 14:49:49'),
('3fcc5cd9ba98fa90d0949af9bbcb8411cf58798e39b42f72d59818d7b8c1d699d3e2c7334bbfc933', 4, 1, 'nApp', '[]', 0, '2020-12-06 18:30:28', '2020-12-06 18:30:28', '2021-12-07 01:30:28'),
('3fdc5e63eabf0e88bc7d524a803a3a0184180628715f818a751ddfc8a2065c4e1b2f9a1180ce7b92', 32, 1, 'nApp', '[]', 0, '2021-06-14 20:42:51', '2021-06-14 20:42:51', '2022-06-15 03:42:51'),
('4149731a7294504b617c357b17887e22aba5b1e72ff92fc6bc6d5844ec379f215d8427a311914dce', 4, 1, 'nApp', '[]', 0, '2020-12-02 21:37:39', '2020-12-02 21:37:39', '2021-12-03 04:37:39'),
('415e0374355c9c6e9b07b786b310483bb75ad155282ef12156f400ba4b17907f0fc27e15f31d26e5', 32, 1, 'nApp', '[]', 0, '2021-03-22 19:56:27', '2021-03-22 19:56:27', '2022-03-23 02:56:27'),
('4174ad75e11eb10a600a6d5594ae19cecadd7e83414c1d0def526b922b04c819dc705c3abd9d97ce', 46, 1, 'nApp', '[]', 0, '2021-01-28 00:36:21', '2021-01-28 00:36:21', '2022-01-28 07:36:21'),
('42cc90cb391bd909f22d40ea9541f7142ffd75e0e8a718baed6f7952bae9aebcaf841ae865c684f1', 4, 1, 'nApp', '[]', 0, '2021-01-05 19:20:42', '2021-01-05 19:20:42', '2022-01-06 02:20:42'),
('430a9fd808fb1b7f0279d4a8f1f37954d73248e1c67faa2d54b5997b1540bae4d340291ad788dd5c', 7, 1, 'nApp', '[]', 0, '2020-11-17 06:41:53', '2020-11-17 06:41:53', '2021-11-17 13:41:53'),
('448fac1442f65368fb1e550733dfe12572fd7eaf9c93ac4f7d7cfa1383ac23672f80d5876e481f5b', 31, 1, 'nApp', '[]', 0, '2021-01-25 21:36:15', '2021-01-25 21:36:15', '2022-01-26 04:36:15'),
('44b776833576ccf889e7e13b6cb1c7ee37c8926e01e6fb2eae0c25e4facb265bb7edfdc6ea0fccf0', 16, 1, 'nApp', '[]', 0, '2020-12-16 02:29:26', '2020-12-16 02:29:26', '2021-12-16 09:29:26'),
('45445620bab5bbfd8e4449159508f9890e6fb5f60b9e9d7d5a81053f13c9a3e11e6f3a9ee2896d36', 7, 1, 'nApp', '[]', 0, '2020-11-25 03:02:03', '2020-11-25 03:02:03', '2021-11-25 10:02:03'),
('4617b4ebb3de585748ccf3b9acb40e2aef0422333dea657a23b7e36fd9485e241ea896930dcd7ad1', 32, 1, 'nApp', '[]', 0, '2021-02-08 19:53:19', '2021-02-08 19:53:19', '2022-02-09 02:53:19'),
('46bb503d25e3c0a54c3c72a9cb36e0a8f8e038e59f319de457010b264c1fe23ce5f52607bcbf4cf6', 20, 1, 'nApp', '[]', 1, '2020-12-17 20:06:39', '2020-12-17 20:06:39', '2021-12-18 03:06:39'),
('495338b2ccc434931815ca0d34630c270647a1280d762003962304f883e8024c8133c1a547a06e29', 4, 1, 'nApp', '[]', 0, '2020-11-24 00:59:43', '2020-11-24 00:59:43', '2021-11-24 07:59:43'),
('49efc3bd37e170782c82b45751d259b03fcd3a6013bda331fb0c394fc68173fc29dfd8aa97cf6729', 3, 1, 'nApp', '[]', 0, '2020-11-15 20:07:41', '2020-11-15 20:07:41', '2021-11-16 03:07:41'),
('4a18a5ebcb3e9bedc699a640907af95cfc51abdf61e85a05da9f1b538622a834f8f46fd48a8fb01f', 21, 1, 'nApp', '[]', 0, '2021-01-11 00:13:40', '2021-01-11 00:13:40', '2022-01-11 07:13:40'),
('4a742d4c4b1207f5522b2899a6628baa84381e616cd878f8b3d7250914799bfefb85a7367d170473', 7, 1, 'nApp', '[]', 0, '2020-11-25 18:45:42', '2020-11-25 18:45:42', '2021-11-26 01:45:42'),
('4aaf1e636393beb308748d05d9a3dee345645894c6dea75d72466a63cd55e69f254232af52262e3c', 32, 1, 'nApp', '[]', 0, '2021-05-16 19:53:13', '2021-05-16 19:53:13', '2022-05-17 02:53:13'),
('4ab8647cff0308af62a47f4519231f06ba1816eae2a019e746ee26a4fb74caba27f0d0197605ffd4', 4, 1, 'nApp', '[]', 0, '2020-11-24 19:30:08', '2020-11-24 19:30:08', '2021-11-25 02:30:08'),
('4ad309fd4a3c5f90e243c973cde868f0d930012fbed98f8de2483814bdc111beeacd65305540c5c8', 3, 1, 'nApp', '[]', 0, '2020-11-16 01:28:09', '2020-11-16 01:28:09', '2021-11-16 08:28:09'),
('4b38d296240b103b139c950fa7092818e4b30924f7489e88749c901ed0600c556f8ed9cf4606ad81', 7, 1, 'nApp', '[]', 0, '2020-11-25 19:08:37', '2020-11-25 19:08:37', '2021-11-26 02:08:37'),
('4b4efd5a7c56dcd16388cb2e7fc04c0ea556b05e4d531f88841e07d8964b53b70a170a09bb4ff238', 4, 1, 'nApp', '[]', 0, '2020-11-16 19:49:50', '2020-11-16 19:49:50', '2021-11-17 02:49:50'),
('4bceb10f12458cd18472b98f2a55c72ec92a75818dff92915d22507129ef4795276c514bf8f6a908', 4, 1, 'nApp', '[]', 0, '2020-12-02 23:05:17', '2020-12-02 23:05:17', '2021-12-03 06:05:17'),
('4befeb394767e949981dd0a6c870f5845763623c7d9ba093278f37a6536d7a6f309d4b1e5b76aa0a', 4, 1, 'nApp', '[]', 0, '2020-11-24 18:23:20', '2020-11-24 18:23:20', '2021-11-25 01:23:20'),
('4d181a06b584f82b43490d7c56d634b0ef6edb6c775db42c0fb24da062c62f51cf7e5b535e93334c', 4, 1, 'nApp', '[]', 0, '2021-01-12 02:37:07', '2021-01-12 02:37:07', '2022-01-12 09:37:07'),
('4da0a75a8ec66c6315c95062961d60b5392a5bea3949dfa2075127a0f44e11d0ea4ea95e591c28e3', 14, 1, 'nApp', '[]', 0, '2020-12-17 19:43:35', '2020-12-17 19:43:35', '2021-12-18 02:43:35'),
('4e24419a69cb97dc0b3f887f79ccfbf702aecadd1957361f53368abf786d0cbb274a2faf5afc3946', 32, 1, 'nApp', '[]', 0, '2021-06-16 01:28:29', '2021-06-16 01:28:29', '2022-06-16 08:28:29'),
('4e603411f65b50f41f9025eee467e4e67a1664c800f0d59eb657417248700fdb0fc005bfb7954df5', 1, 1, 'nApp', '[]', 0, '2020-11-13 06:59:20', '2020-11-13 06:59:20', '2021-11-13 13:59:20'),
('4e91673b846894b7ee094b56ea5b62514e8ba95df97ccdfb4318a5e86084fcee849a5148407326d5', 4, 1, 'nApp', '[]', 0, '2021-01-15 01:07:34', '2021-01-15 01:07:34', '2022-01-15 08:07:34'),
('4e9c55a609da738287eced7833306a9a5fdf5d949ef79b4e43143f75dcc57e1831797bd6f38ab72b', 4, 1, 'nApp', '[]', 0, '2021-01-15 01:08:07', '2021-01-15 01:08:07', '2022-01-15 08:08:07'),
('4eccbc55f245cb7d218da3813368bd77bea472e8c5dcb4748cea2a23af492cde57e343b441906a81', 7, 1, 'nApp', '[]', 0, '2020-11-25 02:45:28', '2020-11-25 02:45:28', '2021-11-25 09:45:28'),
('4f1b970151bd4f28e9fe63d949747f248c8f98f0109b34e3abc883c6192d765e617af6f2861abe1e', 32, 1, 'nApp', '[]', 0, '2021-05-19 20:38:11', '2021-05-19 20:38:11', '2022-05-20 03:38:11'),
('4f947d5bd8734a8768e09fe79fae33d5882f085b879adb6f0702670a7f44076d945177aa234d07c1', 46, 1, 'nApp', '[]', 1, '2021-05-24 20:48:04', '2021-05-24 20:48:04', '2022-05-25 03:48:04'),
('5047d693709fae233dcf16d90d9c0073f72da1b7b01785884d24baeacda115d73ae9109b79440f76', 21, 1, 'nApp', '[]', 1, '2021-01-18 00:50:21', '2021-01-18 00:50:21', '2022-01-18 07:50:21'),
('50d1d53535cc2f610625a5c47f762db7d305f98068f89b27288f0098275912e8d2acae0b14726aa6', 32, 1, 'nApp', '[]', 0, '2021-06-14 01:24:44', '2021-06-14 01:24:44', '2022-06-14 08:24:44'),
('517787b842c0a3082bae41bafec763dde1b06bbd9a71cc29af98c422bf68ae2c4745b977ab10d531', 7, 1, 'nApp', '[]', 0, '2020-11-24 20:02:40', '2020-11-24 20:02:40', '2021-11-25 03:02:40'),
('5359de45b2a4df5875bb08525996019ed0b624f9c5e324ab847449059b331216895dcfd28110c138', 7, 1, 'nApp', '[]', 0, '2020-11-25 18:48:29', '2020-11-25 18:48:29', '2021-11-26 01:48:29'),
('546ee6b230a7ac1fe7294f660a5101d3f90a57913f03d000be52830ae0a50148148ba8bfee702a4b', 4, 1, 'nApp', '[]', 0, '2021-01-15 01:03:57', '2021-01-15 01:03:57', '2022-01-15 08:03:57'),
('54743f14fd11917f519d2d1c7be1499fafe3a13588707b7c99b8f79f18fc2d82caf1b953cfad12bf', 46, 1, 'nApp', '[]', 0, '2021-05-06 09:58:33', '2021-05-06 09:58:33', '2022-05-06 16:58:33'),
('55135eb85116889ee30ae0da004779f7c01c1bed5054f86e97cf046451cac9bee9473c829ced745a', 7, 1, 'nApp', '[]', 0, '2020-11-27 01:02:55', '2020-11-27 01:02:55', '2021-11-27 08:02:55'),
('551af357b27ceb4a70aee6f4219da07150ac5c5048c25a0ee207b5c40134edaf1084abc73bf3239a', 7, 1, 'nApp', '[]', 0, '2020-11-24 20:06:34', '2020-11-24 20:06:34', '2021-11-25 03:06:34'),
('557a0c6708e5fcc2b985f7fa4c0a2953f0f713b42ea9db6e77e62f1e2d77b9b717dec21eb4a57a71', 36, 1, 'nApp', '[]', 0, '2021-03-22 20:02:13', '2021-03-22 20:02:13', '2022-03-23 03:02:13'),
('56e1af60d2c343a793ecd1a9150b171057241e63af91e59286c02d8ad008e797ee5910bc6e1e769e', 32, 1, 'nApp', '[]', 0, '2021-03-19 22:56:10', '2021-03-19 22:56:10', '2022-03-20 05:56:10'),
('577a620dc8f6cce1bdc6d478d7b333f56373f8aaaaba1fbeca8eafa11de2b2d7fa93916b8e3df5f1', 36, 1, 'nApp', '[]', 0, '2021-05-28 21:29:37', '2021-05-28 21:29:37', '2022-05-29 04:29:37'),
('5782065879f2e341b1c6a1285e71f3203d55b855f61cb59032efa03087fa6e441b659620ed5afafb', 51, 1, 'nApp', '[]', 0, '2021-05-19 00:55:31', '2021-05-19 00:55:31', '2022-05-19 07:55:31'),
('5a35965333170153abbddccd6a952b0b2e980d9a15fe41a23c3d8c45e2583cba1fe07bc06fed2f50', 4, 1, 'nApp', '[]', 0, '2021-01-07 01:56:41', '2021-01-07 01:56:41', '2022-01-07 08:56:41'),
('5b587ca885a5814166367a867ebc8ca1c38b0f0a1b8f56d04d751a757635af4733b047059b075dd9', 46, 1, 'nApp', '[]', 1, '2021-02-01 18:47:05', '2021-02-01 18:47:05', '2022-02-02 01:47:05'),
('5bc9ab6afb93f21a33500d732d8978ebf17baff017ec8ffc74eee6489cdb67750101f3e005157b3f', 4, 1, 'nApp', '[]', 0, '2020-12-17 19:41:35', '2020-12-17 19:41:35', '2021-12-18 02:41:35'),
('5c09b9aeea2d19ddc0ea3bc35e0f0c7f384938efda29ac3c70f29f18d8323f94ca2627d49c2928a7', 4, 1, 'nApp', '[]', 0, '2021-01-12 19:00:02', '2021-01-12 19:00:02', '2022-01-13 02:00:02'),
('5c2ec2066bb5122aa6cac8212d409028a5749a73b38c65e3cbf30fe0c5c344f595413b5f8ea01ae4', 51, 1, 'nApp', '[]', 1, '2021-05-23 21:04:03', '2021-05-23 21:04:03', '2022-05-24 04:04:03'),
('5c76106944ae35cd4ca6166c32be0a15377bc36a16734a611057a43a2ed31845b8be8bfc51ac06a3', 32, 1, 'nApp', '[]', 0, '2021-06-16 02:28:57', '2021-06-16 02:28:57', '2022-06-16 09:28:57'),
('5cadc4fa3664b2b30e0f1bc23c4630dc9afe2b0b8a6f3e14e4680412c6c284c73912c5aac264f536', 21, 1, 'nApp', '[]', 0, '2020-12-22 19:09:45', '2020-12-22 19:09:45', '2021-12-23 02:09:45'),
('5d3f119458958a3215adf9523332f0d54bc56315725512503685133588da68bcabcc80d58d984aac', 4, 1, 'nApp', '[]', 0, '2020-12-16 21:29:44', '2020-12-16 21:29:44', '2021-12-17 04:29:44'),
('5d4c048440e0a627037668d912274c4d5bb07c5de0ec42bd5c152e9b2f5357c18bc92d0691b4bf38', 34, 1, 'nApp', '[]', 0, '2021-03-17 02:51:42', '2021-03-17 02:51:42', '2022-03-17 09:51:42'),
('5d794ec32331a5d176dff8577993661ab7ba45063a4d916fb60c00413d90c6c0c3b42bcf788db008', 21, 1, 'nApp', '[]', 0, '2021-01-18 00:12:05', '2021-01-18 00:12:05', '2022-01-18 07:12:05'),
('5dbb1cc6f8f62cacd1e927fe613f0b474749ff4c401b55d3076bf89a55cf20b06504aac93e7a3455', 46, 1, 'nApp', '[]', 0, '2021-02-01 19:32:51', '2021-02-01 19:32:51', '2022-02-02 02:32:51'),
('5f249e3e6a7e6c33be6b29dad7a66195f018559898dd288bca40011365b8a077387a6d008f69d740', 46, 1, 'nApp', '[]', 1, '2021-05-19 17:04:04', '2021-05-19 17:04:04', '2022-05-20 00:04:04'),
('5f6f966198f259f70ab6a916d9c9492cc1451dc51170e87021544a2c3234e77e824453f080c4de32', 21, 1, 'nApp', '[]', 1, '2021-01-18 19:56:26', '2021-01-18 19:56:26', '2022-01-19 02:56:26'),
('60289eb23606085c05ede6ac40ae058f9c77fa3d794b57c4ccad797c674e4019678c8063bb6f27df', 21, 1, 'nApp', '[]', 1, '2021-01-05 21:29:45', '2021-01-05 21:29:45', '2022-01-06 04:29:45'),
('61387494ee81d5b5227b3ef1901b4761352082ae163240c1225c0573582f9b6cb358a3d477f2f659', 21, 1, 'nApp', '[]', 0, '2020-12-22 00:07:25', '2020-12-22 00:07:25', '2021-12-22 07:07:25'),
('61626cb9a640d7c46139e1e4f8b2d645d876358e7f030b8011d3ee70e69ff1aa0908b37b738b6640', 7, 1, 'nApp', '[]', 0, '2020-11-27 01:02:02', '2020-11-27 01:02:02', '2021-11-27 08:02:02'),
('6208aa40f27c05f4ba49de71f96f67302a70f074a4774746c0cf321eb2eb623fc699ae9fae8e4769', 7, 1, 'nApp', '[]', 0, '2020-11-27 01:00:53', '2020-11-27 01:00:53', '2021-11-27 08:00:53'),
('6228a306550e5c0f0c696d327967ccc0eacc923e562a68a491c8dcafa96596ba7cc9eafc7fd818ae', 5, 1, 'nApp', '[]', 1, '2020-12-01 02:43:46', '2020-12-01 02:43:46', '2021-12-01 09:43:46'),
('625313c2e55c0d6e751319617a706b95627390f64b8ad0a7f21d307103fbe7d73348bf61fea207e9', 4, 1, 'nApp', '[]', 0, '2020-11-16 19:44:10', '2020-11-16 19:44:10', '2021-11-17 02:44:10'),
('630ddadec6d27a83abf4b8e544d6d0b8a5fc6a107b934c8575cd40c0068a302b02fd295671cd61d7', 52, 1, 'nApp', '[]', 0, '2021-06-01 23:36:54', '2021-06-01 23:36:54', '2022-06-02 06:36:54'),
('633048b8186f2442e508c4b58fad3eb8fffed80489e04b7d7f89576fe27f140bb5f9052ffe5af839', 4, 1, 'nApp', '[]', 0, '2021-01-12 03:01:54', '2021-01-12 03:01:54', '2022-01-12 10:01:54'),
('6407ac14e600d1bf785c8ccbf1fe18df2153a3c4e5d241518663b3d9100ecba6fb1c1d5cb3fd13fd', 4, 1, 'nApp', '[]', 0, '2020-11-24 19:30:46', '2020-11-24 19:30:46', '2021-11-25 02:30:46'),
('640cbb4a8322e13fdd469ea4f647c2bf92b665043ca434bf1a2e5c50515e28711213a47a25490550', 4, 1, 'nApp', '[]', 0, '2021-01-10 23:59:33', '2021-01-10 23:59:33', '2022-01-11 06:59:33'),
('64899928c04a653a71bce1f86c150787fbd4a0f3d18aa3e3a728055cae9df7a38c3287d87d5a9073', 4, 1, 'nApp', '[]', 0, '2021-01-12 19:15:20', '2021-01-12 19:15:20', '2022-01-13 02:15:20'),
('65b2b752dafe6bfbdf781f96ceb22398ab7b367177b82b8efc8e280741fe0ac37ddf050964624d66', 51, 1, 'nApp', '[]', 0, '2021-04-15 01:14:33', '2021-04-15 01:14:33', '2022-04-15 08:14:33'),
('67127ac47d9a1946853ad05e22d78bf74d7d69c868e99abd54a2fd33d23e91d845dff4c42b6d9900', 7, 1, 'nApp', '[]', 0, '2020-11-17 06:39:07', '2020-11-17 06:39:07', '2021-11-17 13:39:07'),
('6734b5b89294ba2c1fffe57ee53417211324ded77bc5b2bb5c6203438164b3ecc35df821dc3733a3', 4, 1, 'nApp', '[]', 0, '2020-11-24 19:18:41', '2020-11-24 19:18:41', '2021-11-25 02:18:41'),
('6818b9ed0070b5862dd058280ed9d0bc0922d16a964e5fc2fec911bfbaefd6deae40ef8da348f73c', 46, 1, 'nApp', '[]', 0, '2021-05-18 20:22:42', '2021-05-18 20:22:42', '2022-05-19 03:22:42'),
('68900eac72737e7df7779bace879d6d6402ffc43e36a779f6f99b063ad67439790efbbdc071e04b3', 32, 1, 'nApp', '[]', 0, '2021-06-14 20:22:38', '2021-06-14 20:22:38', '2022-06-15 03:22:38'),
('692c8124898036e57713a18dcef9eb7a4b7780269e9f72d01e3ae6c80aeb80930c2934d30e8f311f', 4, 1, 'nApp', '[]', 0, '2020-12-02 02:03:28', '2020-12-02 02:03:28', '2021-12-02 09:03:28'),
('6993f0d061f8c5949c9f27cf9dba8b0058f040d81f96d0dadefae6e712a3ff133f71b8bf74bba0f8', 4, 1, 'nApp', '[]', 0, '2020-11-24 01:49:37', '2020-11-24 01:49:37', '2021-11-24 08:49:37'),
('6af4b32164e230e30af83b9a6fbbbae5c4ec8af7eb9c694edade95ef0edf6202b27bf55799a3dcda', 16, 1, 'nApp', '[]', 0, '2020-12-16 00:10:25', '2020-12-16 00:10:25', '2021-12-16 07:10:25'),
('6b8ca5e1bcfd7195e1aedfec0c61f9b2fd5c8fc3ca18ff04068389ff7659507eb5fde1a02786d8a5', 5, 1, 'nApp', '[]', 0, '2020-11-16 20:04:37', '2020-11-16 20:04:37', '2021-11-17 03:04:37'),
('6c06a0f2c083e234f7e1936a4c76fb52abbd480c59cf3c2614c9472ad4a186b22aaf4a7a5bd79584', 7, 1, 'nApp', '[]', 1, '2020-11-30 20:52:09', '2020-11-30 20:52:09', '2021-12-01 03:52:09'),
('6d876976feb175dc7e5a46b260fd7af99eae3cd66b96270c18ab6668048b6cbda21bdaff5780fc0e', 4, 1, 'nApp', '[]', 0, '2020-12-15 23:25:50', '2020-12-15 23:25:50', '2021-12-16 06:25:50'),
('6df6e1455456b8ffb9ab157b9019a460f3ebf88a607df80fd56808a054759674b709549530254865', 7, 1, 'nApp', '[]', 0, '2020-11-24 20:02:46', '2020-11-24 20:02:46', '2021-11-25 03:02:46'),
('6ea705feeaef0ca0018bf63e4a3a69c70dca2e9b8ebe2ee21f9a41296d107e27508969eee65a329f', 7, 1, 'nApp', '[]', 0, '2020-11-27 00:54:41', '2020-11-27 00:54:41', '2021-11-27 07:54:41'),
('7023264779b96bb381ef0070d8a73f41654b93c163210a5fd18503cf63c56f9e32803fa1dfb61c4f', 7, 1, 'nApp', '[]', 0, '2020-11-27 00:59:12', '2020-11-27 00:59:12', '2021-11-27 07:59:12'),
('717c2f5c892e6e75860017fc976bf95d8d39d2d260168d8acdf28cc9b948fe58fb90528b25c9d013', 32, 1, 'nApp', '[]', 0, '2021-03-22 19:59:28', '2021-03-22 19:59:28', '2022-03-23 02:59:28'),
('71adb039fc0ef1110f61d1dc1bec627b58b41c5634a1992a1ceee9249c5feb9046681d2915473a66', 4, 1, 'nApp', '[]', 0, '2021-01-14 00:42:13', '2021-01-14 00:42:13', '2022-01-14 07:42:13'),
('7246f435067311ec938eaa5a4a7a6593b1a432ae69ed64a464a95e78008e119225d61aaa821a3fb8', 7, 1, 'nApp', '[]', 0, '2020-11-27 01:03:49', '2020-11-27 01:03:49', '2021-11-27 08:03:49'),
('7263759def73cd7b2c0647766bdef5443aa7ad73d8f52b3721049b555bbd4215be48e01af8c4886b', 46, 1, 'nApp', '[]', 0, '2021-04-12 19:49:51', '2021-04-12 19:49:51', '2022-04-13 02:49:51'),
('72af4d8493b47c8d7f58e73f43bd92dddb3aed0f3638cac01534d3c725687126b810595cd11c49ef', 4, 1, 'nApp', '[]', 0, '2020-11-24 19:30:14', '2020-11-24 19:30:14', '2021-11-25 02:30:14'),
('72d8871f8b274b226b7540eb32f4e0ff798cc664d20ab9d84ecaf47e0675bf59bbfa1115627e578b', 7, 1, 'nApp', '[]', 0, '2020-11-29 21:34:06', '2020-11-29 21:34:06', '2021-11-30 04:34:06'),
('73d2fec14539d551407edf6004daf3bc60d7a0fac65ce0c1eaec3f59578b952f481ed6ed1c106b5c', 7, 1, 'nApp', '[]', 0, '2020-11-26 08:25:50', '2020-11-26 08:25:50', '2021-11-26 15:25:50'),
('73d997eaa8e0f0f807bc83b28241891b5e0ca352ae654b4652cdde6ac719dc4c75599a23a9fdd771', 51, 1, 'nApp', '[]', 1, '2021-05-23 19:09:29', '2021-05-23 19:09:29', '2022-05-24 02:09:29'),
('7579ba779c7051ab22a893749b18c400831aa980d829f2873ad4d096b5e6e753a482396e308b1d52', 7, 1, 'nApp', '[]', 0, '2020-11-25 02:48:00', '2020-11-25 02:48:00', '2021-11-25 09:48:00'),
('7633bd6a4f8eba4b809f71d611ea7468da118926ade0be6cc5314d3b92e5f6ab4222c656e6de5719', 4, 1, 'nApp', '[]', 0, '2021-01-07 21:42:48', '2021-01-07 21:42:48', '2022-01-08 04:42:48'),
('764c8580f34bf7e951c7fddac56b31009d32fdf48c66dd16b2443e299e4926c594cbde322cef927b', 43, 1, 'nApp', '[]', 0, '2021-02-22 02:02:10', '2021-02-22 02:02:10', '2022-02-22 09:02:10'),
('76b13a9591d5c78f7e153af3aa3d5c7f8da24820f005251f2e825d362c867c0c6f4dec72fac58ace', 21, 1, 'nApp', '[]', 1, '2021-01-18 01:31:55', '2021-01-18 01:31:55', '2022-01-18 08:31:55'),
('79084788cc525cb766ea7eb24c835df819db6674b5f2e5556259bad3b5040c7b49c693cddf2a18ec', 21, 1, 'nApp', '[]', 1, '2021-01-19 07:56:06', '2021-01-19 07:56:06', '2022-01-19 14:56:06'),
('791120ee33c2dd60bc4c4e7b940f6483c1510dab246d84ff9d0e19b34558862d6af1934647c3764c', 7, 1, 'nApp', '[]', 0, '2020-11-27 02:34:30', '2020-11-27 02:34:30', '2021-11-27 09:34:30'),
('7ac5de25288e2372dd4345b1fe8d7d04abca0444ca4f7b9f794a46d0121dcd79b5ecbef22ee886d6', 7, 1, 'nApp', '[]', 0, '2020-11-17 06:44:03', '2020-11-17 06:44:03', '2021-11-17 13:44:03'),
('7c4638970e935a5d4ff6b23f0b08c234a2583304f9f8ad0b7a4650879c25d29b86e11744fb3e96a9', 4, 1, 'nApp', '[]', 0, '2021-01-14 21:21:43', '2021-01-14 21:21:43', '2022-01-15 04:21:43'),
('7d52726eb02233436658c1dcf144a972afedb37a80ad30d5682a77439d8877e63c689c882c83d3a5', 32, 1, 'nApp', '[]', 0, '2021-05-18 23:44:18', '2021-05-18 23:44:18', '2022-05-19 06:44:18'),
('7e2e2cb42ed7d3222521aa109fc2dd42ccbe78b95efc58d0f32f6d0a8f87abaf23986c678e001fae', 7, 1, 'nApp', '[]', 0, '2020-11-25 02:44:29', '2020-11-25 02:44:29', '2021-11-25 09:44:29'),
('7e7a7d3b281fc5004517ab1772b4f0982776b9e981e0a349cb0c2617c3822b2a9db70a4051605cd8', 2, 1, 'nApp', '[]', 0, '2020-11-13 07:49:30', '2020-11-13 07:49:30', '2021-11-13 14:49:30'),
('7f52b3455ae190654123eff0b72811ce8b06a1d7dec5948f33cbf028cf7569e50afb49ad6f992e5a', 4, 1, 'nApp', '[]', 0, '2021-01-15 01:16:45', '2021-01-15 01:16:45', '2022-01-15 08:16:45'),
('80c09cff5214bd23e675521cc6aa3bc0086d5e9c20076ce9f812523bc3e20d9e1274b7c2aa1ab349', 7, 1, 'nApp', '[]', 0, '2020-11-27 01:02:36', '2020-11-27 01:02:36', '2021-11-27 08:02:36'),
('810141d77ca99a13b60f48bad2e35d8e706e41cb355653e115df11d04dd89f38f81374385e2c533d', 4, 1, 'nApp', '[]', 0, '2020-11-24 01:51:56', '2020-11-24 01:51:56', '2021-11-24 08:51:56'),
('82120e5364adb7f94cb077dbb8eed2764566198ffcf41074f33cb9cc109a351caaba11235bf7276a', 4, 1, 'nApp', '[]', 0, '2020-11-24 01:23:55', '2020-11-24 01:23:55', '2021-11-24 08:23:55'),
('8272453937cdec42dd828978dfecddb73136b37a02c8422b1c823c585a01512381d9cf9a6e9cc2a5', 4, 1, 'nApp', '[]', 0, '2020-12-17 19:50:36', '2020-12-17 19:50:36', '2021-12-18 02:50:36'),
('83e24078061ce2da2b07fe19d08fa86a5e356b9bd07d54f58d859cc69694a99b1e7dc195660d2c46', 4, 1, 'nApp', '[]', 0, '2020-11-24 01:12:23', '2020-11-24 01:12:23', '2021-11-24 08:12:23'),
('851da84d5aadddeae6f850a1547cb8f2dd137baf00829cb60919a2e31638a4d06ad7ed0fc8a23c83', 3, 1, 'nApp', '[]', 0, '2020-11-15 20:06:58', '2020-11-15 20:06:58', '2021-11-16 03:06:58'),
('85c8fc3036d0596116d6e32e27f1cdb1d284ea45ab0961c58b59bb97c89b97485d54b5bfca7b131e', 4, 1, 'nApp', '[]', 0, '2021-01-18 18:51:54', '2021-01-18 18:51:54', '2022-01-19 01:51:54'),
('86020c4b27b804945712a5b4797da957cd63ec4e7ef69547f044539ab679571919aa6e924a27be3a', 40, 1, 'nApp', '[]', 0, '2021-04-04 18:52:51', '2021-04-04 18:52:51', '2022-04-05 01:52:51'),
('8760fea9093f220e7b70cba40bbf19b8850909f0600b77411246996840c82d1446c535d7064b4277', 4, 1, 'nApp', '[]', 0, '2021-01-15 00:12:55', '2021-01-15 00:12:55', '2022-01-15 07:12:55'),
('88de8c814168e2475893621c7390dc94831f1113548aef5e0ebe2460d8aa17ad0d2f6f412074b43a', 4, 1, 'nApp', '[]', 0, '2021-01-13 04:18:19', '2021-01-13 04:18:19', '2022-01-13 11:18:19'),
('891e3c0987d5073674be083673dfdc46e4a071e3daf9187751cec363f1bc3439510d3ac8a82ec718', 7, 1, 'nApp', '[]', 0, '2020-11-25 18:36:05', '2020-11-25 18:36:05', '2021-11-26 01:36:05'),
('8a6a7aa49773cc213c3123ad94da48e7adcbc86f73fb17a53abd3feec670f24dc413b8670b51c56c', 21, 1, 'nApp', '[]', 1, '2021-01-17 20:59:31', '2021-01-17 20:59:31', '2022-01-18 03:59:31'),
('8bdf897b5b4fe066edfae68cf7a693ad016184a9c83e823521eece1d4dda02607aabd51ab461e265', 21, 1, 'nApp', '[]', 0, '2020-12-30 23:39:52', '2020-12-30 23:39:52', '2021-12-31 06:39:52'),
('8ded92992885ab11e8203308d8734cc6fbce063b03b775f75f756a4f5dbb51a8a8b5575302e71a2f', 32, 1, 'nApp', '[]', 0, '2021-02-09 20:33:20', '2021-02-09 20:33:20', '2022-02-10 03:33:20'),
('8dfcfc9f8dc2ab01dd08edef0ec6a602ab9ff054f2bdc96a7b1305b8af1765837f794d202fdd19f1', 4, 1, 'nApp', '[]', 0, '2021-01-12 19:11:13', '2021-01-12 19:11:13', '2022-01-13 02:11:13'),
('8e941b793f01365b0009b0beb89a361a440583862eba181d4041a8a1f8f5fcbff560930fc54b24de', 21, 1, 'nApp', '[]', 0, '2021-01-18 00:11:21', '2021-01-18 00:11:21', '2022-01-18 07:11:21'),
('8ee44bcce1f31e60b97528fe1fc8048d6d9b0e2c06211bd9984dba78ad0ab5131ba6ad3732ea1a84', 21, 1, 'nApp', '[]', 0, '2021-01-18 21:15:07', '2021-01-18 21:15:07', '2022-01-19 04:15:07'),
('8f114f39707ae271bd60e11f6014e5c03a48f867598f1090966d8f373f47c2d83d82b53566e6d0ac', 32, 1, 'nApp', '[]', 0, '2021-01-27 19:18:54', '2021-01-27 19:18:54', '2022-01-28 02:18:54'),
('8f3362d118954acce171831530fd595bc2456b493da5aed5ff1f0ed49477bf2ba17f3387e6143c46', 32, 1, 'nApp', '[]', 0, '2021-05-06 18:55:13', '2021-05-06 18:55:13', '2022-05-07 01:55:13'),
('900cd0e7d2dde2db936fde4eaf8612283adf950b939adc9500cb09fa248b19677f7b378092f9df1f', 31, 1, 'nApp', '[]', 0, '2021-01-25 21:40:50', '2021-01-25 21:40:50', '2022-01-26 04:40:50'),
('90bb56ca8fb3276136938a3acc9eedad5a041230e0f40c3e77aed8cf60649c50ffc5801a5f9f3c50', 46, 1, 'nApp', '[]', 0, '2021-06-28 00:26:03', '2021-06-28 00:26:03', '2022-06-28 07:26:03'),
('915d5fb05bf05c23781cc659090bf00bb2592323a6af7bc41cda5a1565b3e6a7a91f3213ecba5bf3', 28, 1, 'nApp', '[]', 1, '2021-01-18 20:23:21', '2021-01-18 20:23:21', '2022-01-19 03:23:21'),
('9282a7bee0e80ca7df138e4873667d08c52155040bf7385e03c911feedec80a98b24df064a3733bf', 32, 1, 'nApp', '[]', 0, '2021-03-18 22:36:37', '2021-03-18 22:36:37', '2022-03-19 05:36:37'),
('95068b86fedd60f83f1057dd572178f9bf2f893522595476a13ecb168511992de8f26f93db4b1350', 7, 1, 'nApp', '[]', 0, '2020-11-17 00:41:07', '2020-11-17 00:41:07', '2021-11-17 07:41:07'),
('952371bee507dd0463da330a210f60a003c50a237f2ec08e177e980b6a777f53e195426e6a31930c', 4, 1, 'nApp', '[]', 0, '2021-01-19 06:15:04', '2021-01-19 06:15:04', '2022-01-19 13:15:04'),
('95ead89cb01343b76246f6b89157a8e82b281d71110fb2481400a7a237f6c638fcb62600b60e83b9', 7, 1, 'nApp', '[]', 0, '2020-11-27 00:58:04', '2020-11-27 00:58:04', '2021-11-27 07:58:04'),
('968b755e8ef311144d4cffc64ff05251dd9f7d2db2c23f4e349cb1a8fd94439e4db47ac5d82291f3', 32, 1, 'nApp', '[]', 0, '2021-05-05 23:05:56', '2021-05-05 23:05:56', '2022-05-06 06:05:56'),
('975e6ac3ed4b2f218cb80afffa2d942ee54ed0747a4557adfa7c94072f7b4fe7bc72155022705cac', 20, 1, 'nApp', '[]', 0, '2020-12-17 20:35:04', '2020-12-17 20:35:04', '2021-12-18 03:35:04'),
('97985905f7fda4f1a067e8e49bc3fdd707251bfd36e00ed979370c678cc264d82c84d8dd3abf0cb6', 51, 1, 'nApp', '[]', 0, '2021-06-24 20:09:36', '2021-06-24 20:09:36', '2022-06-25 03:09:36'),
('9b28621087f0a33090adf3c30d1be7ae66ff8237ff839a2c883160663631ef394435e38ac1052139', 4, 1, 'nApp', '[]', 0, '2020-11-24 19:19:55', '2020-11-24 19:19:55', '2021-11-25 02:19:55'),
('9b515a91be4ec5ff71708c506371fad0ce0303345910c4496b7abcdda61aac00e8d5e7011a570c92', 4, 1, 'nApp', '[]', 0, '2021-01-04 20:14:02', '2021-01-04 20:14:02', '2022-01-05 03:14:02'),
('9b5833033e0b28da774a42af58dd41267d5b669d11152e95963eecfde303bd97d3855fbe869d059d', 4, 1, 'nApp', '[]', 0, '2020-12-27 19:09:11', '2020-12-27 19:09:11', '2021-12-28 02:09:11'),
('9be85a7f0389eeee26c23eafaaad05f03f3f333b28d951ec02f28128fa2e8b8b226bdcce59a98b27', 42, 1, 'nApp', '[]', 0, '2021-06-02 01:43:50', '2021-06-02 01:43:50', '2022-06-02 08:43:50'),
('9cf8847cd956eecf6904cdcc05caf63e7d5da3654b433e79fc1a36f35e79943ea1d6b24a07d0ef41', 4, 1, 'nApp', '[]', 0, '2020-12-17 18:59:52', '2020-12-17 18:59:52', '2021-12-18 01:59:52'),
('9d58cf7f4c0a1cb8d9031c499cb02fa67340cd901aab6ee0acc9283a1462581a6ff1d11d0f693dac', 7, 1, 'nApp', '[]', 0, '2020-11-25 02:50:15', '2020-11-25 02:50:15', '2021-11-25 09:50:15'),
('9d9058dd82ad8ccb131a932a213ac9db64f8d1f73f2a3a0ed621deac856e2c5902e3a0f4257a8d54', 21, 1, 'nApp', '[]', 0, '2021-01-12 01:06:06', '2021-01-12 01:06:06', '2022-01-12 08:06:06'),
('9e8aa6e323566b242534be43c3e48a6c36c8200f8bf5e7ce8a6a9addad80cfc80540f252a5973a4c', 32, 1, 'nApp', '[]', 0, '2021-06-05 23:23:33', '2021-06-05 23:23:33', '2022-06-06 06:23:33'),
('9f19ab08ff6a9fc8c115c41d73c735f6e8ec93efb942573a553325211fd7c8d72272f75f9a7c860b', 7, 1, 'nApp', '[]', 0, '2020-11-27 01:03:22', '2020-11-27 01:03:22', '2021-11-27 08:03:22'),
('9f1c1d2f5fc0f33963d73ea56192882daa56bdc26096436d4329cef1e6fb022b7f2036acb387df27', 35, 1, 'nApp', '[]', 0, '2021-02-22 19:28:40', '2021-02-22 19:28:40', '2022-02-23 02:28:40'),
('9f8a7c8f9a6d98ff1467f15342af00b06f7992c9478b07d28204591d018ef31ef3be24bdd0518396', 4, 1, 'nApp', '[]', 0, '2021-01-15 01:18:41', '2021-01-15 01:18:41', '2022-01-15 08:18:41'),
('9fa7c42cdd6e5a254743bf382fdf4460d0c7789da270a0d82498fe3f599b386bc102d8b2eeaf1ab8', 21, 1, 'nApp', '[]', 0, '2021-01-07 01:53:23', '2021-01-07 01:53:23', '2022-01-07 08:53:23'),
('a049f9694d52f617d82a359e50fc5625b18d36872c11a058e150e7d7dda3d6c6ed5f12c247d16180', 16, 1, 'nApp', '[]', 0, '2020-12-17 20:02:20', '2020-12-17 20:02:20', '2021-12-18 03:02:20'),
('a04a50dc69a6a3cf420ccb76ad012ab3f603754c7a2f11f7f623266777cd36393c21ab7b2d7d1b09', 4, 1, 'nApp', '[]', 0, '2021-01-15 01:08:25', '2021-01-15 01:08:25', '2022-01-15 08:08:25'),
('a05cff920c96da62fd5d7f86b0410536d40c9449254f24b7f9d46fcf3db243cbd26758bc5bd7ce09', 21, 1, 'nApp', '[]', 1, '2021-01-18 01:34:32', '2021-01-18 01:34:32', '2022-01-18 08:34:32'),
('a14e5fd7e240e039599cc014fc55a81157202e54f393e9e6739f40cf3679514f633f20e2b65a2044', 4, 1, 'nApp', '[]', 0, '2021-01-07 21:35:39', '2021-01-07 21:35:39', '2022-01-08 04:35:39'),
('a2a488a0e86ec449c1622332998fecd766c41bb59ff3b095a1fd719d1f149b89e6d28c74b6ceffc9', 21, 1, 'nApp', '[]', 1, '2020-12-29 01:52:54', '2020-12-29 01:52:54', '2021-12-29 08:52:54'),
('a37d2557216901d0e78eb18fc5129cf145513e45f3ca1c1fd4fab38e6219098c79f731c29809757e', 21, 1, 'nApp', '[]', 0, '2021-01-17 21:48:26', '2021-01-17 21:48:26', '2022-01-18 04:48:26'),
('a4b0a36e8d931a260b13dc7cd9d5c4dbc051455c8cd23c46279acaa38a65e0b9ff559480563b172b', 4, 1, 'nApp', '[]', 0, '2021-01-12 20:04:39', '2021-01-12 20:04:39', '2022-01-13 03:04:39'),
('a60bffa3525da43966be856d81d0fd5bf2eb42e29aebffb47f09f424082d0c771f932b0973867ed8', 46, 1, 'nApp', '[]', 0, '2021-06-05 23:34:56', '2021-06-05 23:34:56', '2022-06-06 06:34:56'),
('a62b3f8eec761a3e2190e85ae2560b38ef034270a773ba62a61338720317ca40b2863146a7623a53', 33, 1, 'nApp', '[]', 0, '2021-04-05 00:02:48', '2021-04-05 00:02:48', '2022-04-05 07:02:48'),
('a73a56301657aa376d85253d14be36d108c13d23f1ab629728b1920ca7b1df8bdae3770871563364', 49, 1, 'nApp', '[]', 0, '2021-03-21 07:02:03', '2021-03-21 07:02:03', '2022-03-21 14:02:03'),
('a7c1ce250cf2baf34fb8b52615021e802d685c4d9b94e3613ac63b64d38836ec2c68a24ae7d54aba', 4, 1, 'nApp', '[]', 0, '2021-01-12 03:00:57', '2021-01-12 03:00:57', '2022-01-12 10:00:57'),
('a9255889c69205a06661b22f4fb0da57f39c25b41a63e1c019f36904932e24267d137026b22b8827', 21, 1, 'nApp', '[]', 1, '2021-01-18 21:15:20', '2021-01-18 21:15:20', '2022-01-19 04:15:20'),
('a92b4b20eb453ebb25c6dc8618ec6f939918ba74eb24c376f185fbd6710266e949841c0fe7fa865e', 4, 1, 'nApp', '[]', 0, '2020-12-20 19:58:50', '2020-12-20 19:58:50', '2021-12-21 02:58:50'),
('a98b28c3ccb736c0ce18aa3a66d2b572f1b05fafebfad7e28e68efb7a6f551defa70fe2d72dad86c', 4, 1, 'nApp', '[]', 0, '2020-11-24 18:49:30', '2020-11-24 18:49:30', '2021-11-25 01:49:30'),
('aab9ce30884aa9e5353b6fdf00c3cb65f85986912e92d2ae2be850fcdd8ff49576eb552beb2d7cb1', 7, 1, 'nApp', '[]', 0, '2020-11-25 18:40:19', '2020-11-25 18:40:19', '2021-11-26 01:40:19'),
('abb94e378b9ec7416075a03c16b7c4a63bae263385eb4be2ba4b1e513870ecc9fe1e25453affca27', 50, 1, 'nApp', '[]', 0, '2021-03-29 23:54:32', '2021-03-29 23:54:32', '2022-03-30 06:54:32'),
('ad908a02bb237ebf7086dba807953635cffab14199d7200005b6fd0b4f5474edc5f78c4325a6e585', 7, 1, 'nApp', '[]', 0, '2020-11-27 00:58:35', '2020-11-27 00:58:35', '2021-11-27 07:58:35'),
('adf3324655be69f4d057b245fb9a68b32efc878d29984a7ffc674ccfda1a23d6cf10533384350837', 32, 1, 'nApp', '[]', 0, '2021-02-07 18:48:09', '2021-02-07 18:48:09', '2022-02-08 01:48:09'),
('ae28e4f38a1dcbc1bbb8b034ffd061b1d43145c6d63285f5e646c6368cc390d4ecb9458eb9d817eb', 46, 1, 'nApp', '[]', 0, '2021-06-28 20:18:27', '2021-06-28 20:18:27', '2022-06-29 03:18:27'),
('ae2d8ef7d5abf9e7942c985dc90e7882cb9f99a7612dace300ea7f77b9f6e92c9d205ca94f67e73c', 7, 1, 'nApp', '[]', 0, '2020-11-17 06:39:01', '2020-11-17 06:39:01', '2021-11-17 13:39:01'),
('ae3bd51f819cd211d41909bdaed9989e618e40f3be32e27c6fffc541d697d56f23317e651b269352', 20, 1, 'nApp', '[]', 1, '2021-01-19 08:28:01', '2021-01-19 08:28:01', '2022-01-19 15:28:01'),
('af5547cec1ddfe55732a99e40c6feaf8c8c8c40fd8b7ec80bad566322881efb42e58fb18e23c7009', 32, 1, 'nApp', '[]', 0, '2021-03-19 23:03:28', '2021-03-19 23:03:28', '2022-03-20 06:03:28'),
('af7bff3cb62a9849887a26e06c3fa513d497490ab20f8f356da258635051ab6d8ed947f195359709', 41, 1, 'nApp', '[]', 0, '2021-02-22 04:55:55', '2021-02-22 04:55:55', '2022-02-22 11:55:55'),
('b01f40809c46181fcebca0cbdad2d46630db911967e064e7fe3f41c7e161f47a48a1ff7398bca1bd', 46, 1, 'nApp', '[]', 0, '2021-02-08 18:58:14', '2021-02-08 18:58:14', '2022-02-09 01:58:14'),
('b02fc127e1271c83ea84f3f78bd56d771599dc11f8d706dcf328aa7ff3e050651da4924ddcb4af6d', 21, 1, 'nApp', '[]', 1, '2021-01-19 07:26:35', '2021-01-19 07:26:35', '2022-01-19 14:26:35'),
('b0db1328ba4ebb99f012d4b10484949bf8fee2a0d6fdbbeea488cdd9ab42ddcceb1c15af1827be7e', 21, 1, 'nApp', '[]', 0, '2021-01-19 21:36:27', '2021-01-19 21:36:27', '2022-01-20 04:36:27'),
('b15ea1a1e4e32ca4cdb61f9342d7083abcd11aa9c7fe22124643f6ba5ed35c6c25164946dcf06dd0', 32, 1, 'nApp', '[]', 0, '2021-05-19 23:58:47', '2021-05-19 23:58:47', '2022-05-20 06:58:47'),
('b1755f74149c4c8f7985668d08e8afeb8d9190eb463985d20601280eeeb2af1bc8266fc9cb57222a', 4, 1, 'nApp', '[]', 0, '2020-11-24 01:25:31', '2020-11-24 01:25:31', '2021-11-24 08:25:31'),
('b2b0ac0d8ef9e311eb1dd3a03a3611282d2be0c4d07c9bccfe1b8e7f713847890b6e4ffb00b8fbe0', 28, 1, 'nApp', '[]', 0, '2021-01-19 07:48:13', '2021-01-19 07:48:13', '2022-01-19 14:48:13'),
('b3e655091edf90022b78beddf837390b0e4c8ea3bdcdbc3447995b5e4b7050806138a69ac651992f', 38, 1, 'nApp', '[]', 0, '2021-03-21 23:49:08', '2021-03-21 23:49:08', '2022-03-22 06:49:08'),
('b522e459646f79804655bde8f548d72fda7257e8205984491a4155968ec4337de6fc1e9f11b3b58e', 39, 1, 'nApp', '[]', 0, '2021-02-22 01:52:55', '2021-02-22 01:52:55', '2022-02-22 08:52:55');
INSERT INTO `oauth_access_tokens` (`id`, `user_id`, `client_id`, `name`, `scopes`, `revoked`, `created_at`, `updated_at`, `expires_at`) VALUES
('b6be925472f63e45daab27c7da1cdd0587d6ba72cec416abce8d78e0a8cc260739584c17d0f1bf65', 46, 1, 'nApp', '[]', 1, '2021-01-27 23:49:13', '2021-01-27 23:49:13', '2022-01-28 06:49:13'),
('b809481021218190acb4d784e8c47ccb40e86e3b13d90f76ae86442e17aec2cec16c80e040e171f6', 47, 1, 'nApp', '[]', 0, '2021-03-20 02:34:13', '2021-03-20 02:34:13', '2022-03-20 09:34:13'),
('b89c97d5591edf517383a70d356ec603f5650821a437d8b55617f30018234590afc88222029b2afb', 7, 1, 'nApp', '[]', 0, '2020-11-25 03:03:33', '2020-11-25 03:03:33', '2021-11-25 10:03:33'),
('b9f225767e0ead583286ccc03469fd9f14d7fd4bf8fb2f28ebb5a11798528e04c33346490ef1204e', 16, 1, 'nApp', '[]', 0, '2020-12-16 07:39:54', '2020-12-16 07:39:54', '2021-12-16 14:39:54'),
('ba17cf5c3cd5422ab4d1bdd4709f5eb6910219668e2ae5d293c86703c39e14e3984a9aa8eae5b376', 4, 1, 'nApp', '[]', 0, '2021-01-12 19:25:36', '2021-01-12 19:25:36', '2022-01-13 02:25:36'),
('ba9ecc38742b1896bad2fc93045e701ed746ffdef3538e2624dedb2c7829866e71a5118e23dae6ff', 32, 1, 'nApp', '[]', 0, '2021-05-03 18:16:11', '2021-05-03 18:16:11', '2022-05-04 01:16:11'),
('bac3b5f4dfb3788be98b6d8118b1e9ea233f152b06592f613bafccbb4d3a7225c71fd1f67317aa96', 21, 1, 'nApp', '[]', 0, '2021-01-17 21:47:48', '2021-01-17 21:47:48', '2022-01-18 04:47:48'),
('bb837d541b93d1d7821f07f0aa9c8064740802aaffbe219536ae9423d83433bfb6ed58fdb1e666d1', 21, 1, 'nApp', '[]', 0, '2021-01-03 20:27:15', '2021-01-03 20:27:15', '2022-01-04 03:27:15'),
('bb9a992a04211643b6b5943953d72ce418bf2fd246163de447eeffb416dd225e7ade25c8cb1805fd', 21, 1, 'nApp', '[]', 0, '2020-12-24 00:18:08', '2020-12-24 00:18:08', '2021-12-24 07:18:08'),
('bbf8100253ba3e2f654097757cb1e24bae40b10270b8feb5eaef536bc62068fca99335737922916b', 46, 1, 'nApp', '[]', 1, '2021-05-23 20:48:45', '2021-05-23 20:48:45', '2022-05-24 03:48:45'),
('bc7fdaac36eff9576f60a7595d591787442f6d039189f0b7809f589f2e9916a58201088ae7ace4df', 46, 1, 'nApp', '[]', 0, '2021-02-01 19:37:58', '2021-02-01 19:37:58', '2022-02-02 02:37:58'),
('bc9b327f1c824ec1d6b45a8e6aaae395ec49b3f0640f38fd097a5ca4e8d742cf45bcdedf14e2d3cc', 18, 1, 'nApp', '[]', 0, '2020-12-17 19:44:39', '2020-12-17 19:44:39', '2021-12-18 02:44:39'),
('bd8e2d37841e2fc0b1b67dfeb5d3209e8b4b861aeeb9c34912c553d9fd20717cf3e6e7483630d349', 21, 1, 'nApp', '[]', 0, '2021-01-17 21:04:46', '2021-01-17 21:04:46', '2022-01-18 04:04:46'),
('bf2f3962f38a720745648db6057d48e937aed1dc7d0b75b477f861ad9df3354b145337da33ece632', 18, 1, 'nApp', '[]', 0, '2020-12-17 19:50:59', '2020-12-17 19:50:59', '2021-12-18 02:50:59'),
('bf53e328fa89e75786707d29a3729328e1580ca0cdf31298375c0147ca83bc4a384888c48a3160e3', 28, 1, 'nApp', '[]', 1, '2021-01-19 07:04:48', '2021-01-19 07:04:48', '2022-01-19 14:04:48'),
('bf691ce20e4924d7a24a8cf05b8d11181b39862007a4a1113143b5ad98d428454009df49323449e3', 7, 1, 'nApp', '[]', 0, '2020-11-26 08:25:08', '2020-11-26 08:25:08', '2021-11-26 15:25:08'),
('c00be0c9c6ed623eb81236509f0d8ef73e7328cfdda42ea080fed6a015654670789dfbf098d1c637', 44, 1, 'nApp', '[]', 0, '2021-02-21 23:58:53', '2021-02-21 23:58:53', '2022-02-22 06:58:53'),
('c02b8cca1fa4a9b42c67e774f1a07d24f6573bd24c9b1115fcb6885ed8dcc3632e4319987a56d3d1', 21, 1, 'nApp', '[]', 0, '2021-01-15 01:10:13', '2021-01-15 01:10:13', '2022-01-15 08:10:13'),
('c0f05c4737db44bce72e0be8bad30ccace10bcf71e2a159fbde295b8a44f2e5c4dd756f9c5433b11', 4, 1, 'nApp', '[]', 0, '2021-01-07 01:47:02', '2021-01-07 01:47:02', '2022-01-07 08:47:02'),
('c0f882e0e59b01b7cd743d30d82f077872bdf0f2638c911f6c94c167ab14ecc8deaf8a1a9f789f20', 21, 1, 'nApp', '[]', 0, '2021-01-17 21:48:51', '2021-01-17 21:48:51', '2022-01-18 04:48:51'),
('c15207bc415a82c120f3cd2c2085c848c1c309a10f01b65afaf1865f4c4ab9bbde16bd441488879a', 7, 1, 'nApp', '[]', 0, '2020-11-25 03:03:06', '2020-11-25 03:03:06', '2021-11-25 10:03:06'),
('c217c4af4652cafd8e4314c785973871125e46e1386bdade2df301842491e116383e54940c05d693', 21, 1, 'nApp', '[]', 0, '2021-01-18 20:57:28', '2021-01-18 20:57:28', '2022-01-19 03:57:28'),
('c2d15b921eb6f8ba334c02e0606bd9eafce2d88e94862bb7c3ec6d7affabe4a60cbd85584b16e866', 46, 1, 'nApp', '[]', 0, '2021-06-28 21:16:56', '2021-06-28 21:16:56', '2022-06-29 04:16:56'),
('c2ef347d14926ba31046e12e10b5b91ec319852b3153574c063ac95c585ea8368414cd084807e712', 21, 1, 'nApp', '[]', 1, '2021-01-18 01:30:08', '2021-01-18 01:30:08', '2022-01-18 08:30:08'),
('c468fe28d359fb486f113be2649b7535ae3b12df5c469a0982f75d10f0f6431bbf656e24f8fb4316', 21, 1, 'nApp', '[]', 0, '2021-01-04 01:48:54', '2021-01-04 01:48:54', '2022-01-04 08:48:54'),
('c48c645678b10e16ae0067e4466ff0d0ac18d64f7deced40ef2515a9f571243ed1150bf8fcca3521', 34, 1, 'nApp', '[]', 1, '2021-02-28 18:24:52', '2021-02-28 18:24:52', '2022-03-01 01:24:52'),
('c5921cb74bdbc20f4e4b2732cf1153a19ac1f1f2a7e4114653791d99b71bb5941e3183d27b1fdbcc', 32, 1, 'nApp', '[]', 0, '2021-05-20 02:50:46', '2021-05-20 02:50:46', '2022-05-20 09:50:46'),
('c6a4faabc4fe68766168cbc8bffd2611d0611f5cd7f3739291d65f60c1c475b4352f15791fa0850f', 21, 1, 'nApp', '[]', 0, '2021-01-05 21:25:43', '2021-01-05 21:25:43', '2022-01-06 04:25:43'),
('c71b766316504215dd396f03dbf440b008e1557f8b3c34c61c25505ca89994b56147a98645cae4b5', 4, 1, 'nApp', '[]', 0, '2021-01-12 02:43:55', '2021-01-12 02:43:55', '2022-01-12 09:43:55'),
('c7b19c148fab35bea089db380313f85b340dbb3463a1d0a60928db41cb53d4bf8122f2f0327aaf26', 4, 1, 'nApp', '[]', 0, '2021-01-03 19:09:33', '2021-01-03 19:09:33', '2022-01-04 02:09:33'),
('c7fd0faed7bbd63a8cfa596152674b2b643732f9206d94c8b4b7adeed49e95d04165a73affbae9a4', 4, 1, 'nApp', '[]', 0, '2020-12-22 19:08:45', '2020-12-22 19:08:45', '2021-12-23 02:08:45'),
('c8b2befc8423adbff033b929d53131bf19871c19c8822e6fc2e0e2cab5d630466999deab90c3c944', 4, 1, 'nApp', '[]', 0, '2020-12-02 19:40:38', '2020-12-02 19:40:38', '2021-12-03 02:40:38'),
('c909db2f0361342569bea0c00f090553b815418a0636947b5b5a5fad246ffe373791633797b814a3', 21, 1, 'nApp', '[]', 1, '2021-01-19 06:19:10', '2021-01-19 06:19:10', '2022-01-19 13:19:10'),
('c94380e7bfa629589e3acc692b1c57e43cf20568a12d73cf4cf805e901d2ab689ce48ea86c7cbce9', 32, 1, 'nApp', '[]', 0, '2021-05-05 23:16:22', '2021-05-05 23:16:22', '2022-05-06 06:16:22'),
('ca6c6c561300c114cf1eecd0d21fb8d0b7a3813e76e6a4945be79033ad0bd62f5b1cca166dc6448b', 21, 1, 'nApp', '[]', 0, '2020-12-21 02:20:57', '2020-12-21 02:20:57', '2021-12-21 09:20:57'),
('ca7cf735bfb5d06483945fa514da574ae6a084cc8ab41aeed407923180cd7fa20894977a0645dbe6', 4, 1, 'nApp', '[]', 0, '2020-11-16 19:43:17', '2020-11-16 19:43:17', '2021-11-17 02:43:17'),
('ca9beb32dcbfafbc1c5c7903397747e1ba7a6cabccb98f9fe6e6d4cadf84f567be67e56b7b4eb8d0', 7, 1, 'nApp', '[]', 0, '2020-11-30 20:45:14', '2020-11-30 20:45:14', '2021-12-01 03:45:14'),
('cad99624e767dc20eb6d94c0e022882b9e75ec8bbf021b829f77be1db5e85cdd5e0d711c2657ce3a', 4, 1, 'nApp', '[]', 0, '2020-12-02 02:11:37', '2020-12-02 02:11:37', '2021-12-02 09:11:37'),
('cc8a9767947f75a3b984837cde4cb3aaa415ef02d0b7a033ae98d9d24d89031b33a2e8f5d02b0762', 16, 1, 'nApp', '[]', 0, '2020-12-17 19:44:59', '2020-12-17 19:44:59', '2021-12-18 02:44:59'),
('cc9c155d178f135fe2921341ea6b8c6797d81877928337062b48417a8650d0198bacb1b609dbf314', 21, 1, 'nApp', '[]', 1, '2021-01-11 00:10:27', '2021-01-11 00:10:27', '2022-01-11 07:10:27'),
('cd5440664088c1c2dc0c3405d2bfa18436b7d4be3cb9b54b24944c730dd55642b469dae3271826be', 7, 1, 'nApp', '[]', 0, '2020-11-27 00:59:55', '2020-11-27 00:59:55', '2021-11-27 07:59:55'),
('cd8d4ed667734d078e2a39b9e5a4956f866c9d9bff8358e02d756a7cc87105a1dfe5c0d4ff0fc914', 4, 1, 'nApp', '[]', 0, '2020-12-02 18:41:30', '2020-12-02 18:41:30', '2021-12-03 01:41:30'),
('cdb2a6c102b70c4ea4bddfd9973a6001a14e1ec52e0c8902376245c68003413b9e05b0b428787bfa', 20, 1, 'nApp', '[]', 0, '2020-12-17 20:04:57', '2020-12-17 20:04:57', '2021-12-18 03:04:57'),
('cdd908f4b0d730f62daac7fb9073db91c203f0c58fb31370896fcea022afdf1126d0acc0eb51c77e', 21, 1, 'nApp', '[]', 0, '2021-01-17 21:47:18', '2021-01-17 21:47:18', '2022-01-18 04:47:18'),
('ce73b64782b305fdba28a46b198a5fffd0dca37fa79385a2cda22fe804cd183ab17d657932714d87', 21, 1, 'nApp', '[]', 0, '2021-01-18 01:35:14', '2021-01-18 01:35:14', '2022-01-18 08:35:14'),
('cfb68d6822ddecde0f0ac57c736f7d33655abd72e86f070b0fe53b077c8d99379bdbeac600b957a1', 21, 1, 'nApp', '[]', 0, '2021-01-17 21:48:13', '2021-01-17 21:48:13', '2022-01-18 04:48:13'),
('d0b134947712e9e2f0e2beef418e1fa0c503e4632322690c5042f0df44ddfd31d5dc67fc5a12ff66', 7, 1, 'nApp', '[]', 0, '2020-11-25 19:07:47', '2020-11-25 19:07:47', '2021-11-26 02:07:47'),
('d0e66f78a0c59199e693e44c14157c3e83c2f97987dedf59ddd4430a31f0b0a7050ba3ebca7399ac', 37, 1, 'nApp', '[]', 0, '2021-03-28 20:26:24', '2021-03-28 20:26:24', '2022-03-29 03:26:24'),
('d29c71fbd6e2f52e85549bff5d40cf2d19b2104511761bcc2f41f1182fe56dc28c8ca8b373f3a7ac', 4, 1, 'nApp', '[]', 0, '2021-01-15 01:13:14', '2021-01-15 01:13:14', '2022-01-15 08:13:14'),
('d34a477289b2fc95a2a7ee0cd5f5d0cabcf3c233d6f55cb0ffed7cd49ef3b9ed804d0bab9dc53706', 4, 1, 'nApp', '[]', 0, '2020-12-21 23:02:09', '2020-12-21 23:02:09', '2021-12-22 06:02:09'),
('d3590e23f268527cec3d3bc68e3146897d151802af6fa54c47df998611f8b582f039b9395866fd73', 4, 1, 'nApp', '[]', 1, '2020-12-02 02:54:09', '2020-12-02 02:54:09', '2021-12-02 09:54:09'),
('d37a8df6bcd30b2ffdb915ad6b7051242445f7c7cde95b8f57fefd939b9e18e2a4f4de4615bbeef2', 21, 1, 'nApp', '[]', 0, '2021-01-05 21:40:20', '2021-01-05 21:40:20', '2022-01-06 04:40:20'),
('d3d77db341a4ace861943883e4a8c2a13db394d3e1053686428173cdef5581b38373e8dda28849d3', 20, 1, 'nApp', '[]', 0, '2020-12-17 20:54:13', '2020-12-17 20:54:13', '2021-12-18 03:54:13'),
('d4271ff2b0f110be686b4bfe9dbf2447755177bd26c1202f676c7fd7d73f890207667f7455e3fafe', 51, 1, 'nApp', '[]', 1, '2021-05-31 21:26:42', '2021-05-31 21:26:42', '2022-06-01 04:26:42'),
('d61b4dca3df6f4b7464973d3899b07b3d6e4a1d66c0b2033661bca05aff5a91726b027d8c7c16738', 7, 1, 'nApp', '[]', 0, '2020-11-24 19:47:09', '2020-11-24 19:47:09', '2021-11-25 02:47:09'),
('d633e807c70f2045ffaa4444e7d5ba82a892e77085ed572aeefa0e213facedb09b6984ecc5a77f0e', 4, 1, 'nApp', '[]', 0, '2020-11-17 07:07:48', '2020-11-17 07:07:48', '2021-11-17 14:07:48'),
('d73b2890e0dea4c2f583f463b8fb6b3ef93e6e62b395ce750e7e6ef819f29b31073ff1fbef064727', 7, 1, 'nApp', '[]', 0, '2020-11-29 23:42:48', '2020-11-29 23:42:48', '2021-11-30 06:42:48'),
('d817177ab24944ab7994f168c4287061f7d78dfc2997d7c512ada682a87dfe8a32781115385e04a1', 4, 1, 'nApp', '[]', 0, '2020-12-06 18:29:31', '2020-12-06 18:29:31', '2021-12-07 01:29:31'),
('d81c2aee54010f3380ec91f5bd0f000c62233e6993bb856921d5c9bea888e9b658151e6496a4a974', 21, 1, 'nApp', '[]', 0, '2021-01-19 08:13:13', '2021-01-19 08:13:13', '2022-01-19 15:13:13'),
('d9ac362ff18c605316293ffbb3fb47064465171102ea83fce258e7b0c59fa8947db3f4af522d1cc4', 51, 1, 'nApp', '[]', 1, '2021-05-19 02:41:07', '2021-05-19 02:41:07', '2022-05-19 09:41:07'),
('da04d419f4496f19851c628791d289043af498577d4b1664140b872f062684b1ce945cec9b39d799', 51, 1, 'nApp', '[]', 1, '2021-06-13 19:59:29', '2021-06-13 19:59:29', '2022-06-14 02:59:29'),
('daea2947dc03ae5ca3e3c4ccddd863895246a5c1c9a3db636da23e2aa88dee1a0fd771d0ad0f8218', 16, 1, 'nApp', '[]', 0, '2020-12-16 00:12:56', '2020-12-16 00:12:56', '2021-12-16 07:12:56'),
('db3609c640022b8644d9229b186036615e4a411dded2921f8db5c0332fe39776451254aecb1c73af', 46, 1, 'nApp', '[]', 0, '2021-06-28 18:53:13', '2021-06-28 18:53:13', '2022-06-29 01:53:13'),
('db74f971e605ed3f85029db02a5268d228b95bcb9cd05edf1299fb5f7821bb874fa781b908892c18', 16, 1, 'nApp', '[]', 0, '2020-12-17 20:02:33', '2020-12-17 20:02:33', '2021-12-18 03:02:33'),
('dbe126a06b92badaa1de667e3ab2209c29325f8c3b5673e78c8423e6b63ba130076b1cf8b42f95a7', 46, 1, 'nApp', '[]', 0, '2021-06-28 20:39:21', '2021-06-28 20:39:21', '2022-06-29 03:39:21'),
('dd615e67fa3d365873449a6da2282de8e6e4419c2eb7d913f31b337709459ef8e8feabd02be24a08', 7, 1, 'nApp', '[]', 0, '2020-11-25 02:41:27', '2020-11-25 02:41:27', '2021-11-25 09:41:27'),
('e04f55f29bf5bbf7c997de9929be35e755fc8b468d4b3795a2d9b5b9e8bcc4227653d858f5403893', 46, 1, 'nApp', '[]', 0, '2021-05-06 09:58:23', '2021-05-06 09:58:23', '2022-05-06 16:58:23'),
('e092d4e232366c11acea58430dfb45ba0f8599bf122a5048b40fe1d2f46622a944b57330d9ab8524', 4, 1, 'nApp', '[]', 0, '2021-01-13 06:58:25', '2021-01-13 06:58:25', '2022-01-13 13:58:25'),
('e0aac1248a4aba09d28c6e92ad223bb57aeb47eec2b3abd2bf8a7787a340d60793bcc869c0504899', 21, 1, 'nApp', '[]', 0, '2021-01-07 21:27:59', '2021-01-07 21:27:59', '2022-01-08 04:27:59'),
('e3d2649ff1831770c1eb20e32ac95cc3dabcf5f45e8acf68e80bc0cf91cb99d6b4ab46c205e57fff', 4, 1, 'nApp', '[]', 0, '2020-12-03 02:08:52', '2020-12-03 02:08:52', '2021-12-03 09:08:52'),
('e4bc79807ca51548fbe87e5bfc437a5a74f9c8f1eaaabb32aae823fa21e68f43ebee8186a459d845', 38, 1, 'nApp', '[]', 1, '2021-03-21 23:48:14', '2021-03-21 23:48:14', '2022-03-22 06:48:14'),
('e55aa499a6a230c63ffacab26ce235ed4a9181f45527ea4884cd905825c480dd7e169fc3c53f440b', 21, 1, 'nApp', '[]', 0, '2021-01-11 00:22:58', '2021-01-11 00:22:58', '2022-01-11 07:22:58'),
('e633164073e1f4d8814e944ed56f2bd003f4a30b69d10401b367d5f97ae99f689016b7f6f4fe0ace', 4, 1, 'nApp', '[]', 0, '2021-01-12 21:05:21', '2021-01-12 21:05:21', '2022-01-13 04:05:21'),
('e6752d4218a4b8fce8f20bfcea1cb8f7dc316bcf9902ec95b546752d909f2b04c31003cb4300efa4', 32, 1, 'nApp', '[]', 0, '2021-05-03 18:10:51', '2021-05-03 18:10:51', '2022-05-04 01:10:51'),
('e7cdc9e56e5b8a1522aef649e7332370caa79a04ab62d7ab2c06206af4add407a5c44afc1ab4c4e6', 7, 1, 'nApp', '[]', 0, '2020-11-25 03:03:54', '2020-11-25 03:03:54', '2021-11-25 10:03:54'),
('e8375eb0a00b2266dc7467e15df84d031516ef83d10ca92f16eb8cc69f0fd7941c560d950e796d08', 4, 1, 'nApp', '[]', 0, '2020-12-02 02:03:06', '2020-12-02 02:03:06', '2021-12-02 09:03:06'),
('e851f76aefeb04fbfd1053eb83375bfb1c4bacf06981109e002139923cc636c362e4648514a72dc1', 1, 1, 'nApp', '[]', 0, '2020-11-13 06:57:26', '2020-11-13 06:57:26', '2021-11-13 13:57:26'),
('e902f33afe0a28fd978d04dd19256e27e2adb7a630ad7925059cd5bbbffec6bad4b8df1388b65166', 21, 1, 'nApp', '[]', 1, '2021-01-11 00:00:19', '2021-01-11 00:00:19', '2022-01-11 07:00:19'),
('e9d93599e1772359d6d0e30f36be97e9a3ba292235795702c8ced82f56651386d4467e940470e065', 21, 1, 'nApp', '[]', 0, '2021-01-11 00:14:37', '2021-01-11 00:14:37', '2022-01-11 07:14:37'),
('ea1e80678906e8062d84946f175e9fec706f00f46a2fce556f6acbd5d20ea3bae58b5b5e8d39e552', 18, 1, 'nApp', '[]', 0, '2020-12-17 19:44:25', '2020-12-17 19:44:25', '2021-12-18 02:44:25'),
('ea961eb6068d77e95f7740e2ff097ee52864643e47d4627ba282c8fc2be27657f4f80883a7326e8d', 4, 1, 'nApp', '[]', 0, '2020-12-28 20:17:49', '2020-12-28 20:17:49', '2021-12-29 03:17:49'),
('ebc02927b9af286aaae7e842642a2a6ed387e142477a9e52bef42366f88b7e450752d5866697aa92', 7, 1, 'nApp', '[]', 0, '2020-11-25 02:54:48', '2020-11-25 02:54:48', '2021-11-25 09:54:48'),
('ec809450ad0b5d1fd4fb768e85c2845087cc14530aceb9805779793c4ab7a08533eb34072b938805', 4, 1, 'nApp', '[]', 0, '2020-12-02 19:47:33', '2020-12-02 19:47:33', '2021-12-03 02:47:33'),
('ee0ceb9e4c388016ef4a9e7539830c3c11008b0b4dd29b3e50db24e6c7e3e18f87a42c4a9cee1766', 4, 1, 'nApp', '[]', 0, '2020-11-23 21:06:50', '2020-11-23 21:06:50', '2021-11-24 04:06:50'),
('eecc505863194f79088d1db3e41f7fa1834c2aca44d7b8e3ad41012370214a422f2bf1503db04158', 32, 1, 'nApp', '[]', 0, '2021-06-15 21:25:27', '2021-06-15 21:25:27', '2022-06-16 04:25:27'),
('ef8314814ac659fde75f655b76c15da2be4567a5a6ac17f457770338712b779965e53ee2507f2628', 16, 1, 'nApp', '[]', 0, '2020-12-16 00:09:34', '2020-12-16 00:09:34', '2021-12-16 07:09:34'),
('f0c86b49738c41db8808722b9e089428d744c7ea2863208086b33eb993df1908fed824d3da756784', 21, 1, 'nApp', '[]', 0, '2021-01-18 00:01:24', '2021-01-18 00:01:24', '2022-01-18 07:01:24'),
('f11f49bce6f41745da334fb7d07f36ce0312e51c53131df4b71d81cf42d43b06e942c844622b6c9d', 32, 1, 'nApp', '[]', 0, '2021-06-28 21:25:34', '2021-06-28 21:25:34', '2022-06-29 04:25:34'),
('f1a437daec36e97925f20a312e1d2c299102f66a8bb9fd21dbd06c4273a12f3d33de54e82ee86ffa', 32, 1, 'nApp', '[]', 0, '2021-02-08 19:29:50', '2021-02-08 19:29:50', '2022-02-09 02:29:50'),
('f226146747058aa4d8025b1077574ea9c6e3f9dad09b18fb2087ab23e415216e955cd4ada4d433b1', 4, 1, 'nApp', '[]', 0, '2020-12-17 18:46:46', '2020-12-17 18:46:46', '2021-12-18 01:46:46'),
('f262e6732235af0ad50a6fb2ceb902537451326fe8b4c468c7173cda23d66699c9956eb62fd65880', 4, 1, 'nApp', '[]', 0, '2020-12-11 00:10:27', '2020-12-11 00:10:27', '2021-12-11 07:10:27'),
('f40290ea76393bbc2ed1f502b9fe99f259d1e58bf5c11c43f4f06cd1aaa47941d23c252cab3d3e16', 32, 1, 'nApp', '[]', 0, '2021-02-08 19:55:10', '2021-02-08 19:55:10', '2022-02-09 02:55:10'),
('f49b93ae13c279c0494640253e0e96f035ed685f802ba83bd6a61e233828894d2e9297b8f7f5ed12', 14, 1, 'nApp', '[]', 0, '2020-12-17 19:43:29', '2020-12-17 19:43:29', '2021-12-18 02:43:29'),
('f56f98a8fb60cabdf9b00a51d176d8df76bd75f16dfa811b9189cac0922a84426af2f4760fcf1851', 4, 1, 'nApp', '[]', 0, '2021-01-12 02:41:10', '2021-01-12 02:41:10', '2022-01-12 09:41:10'),
('f616d65cf60a52b469494d5d81465158f6526bb183a4c1e212e07269624e569d6158a6b6e1d92ff2', 14, 1, 'nApp', '[]', 0, '2020-12-15 23:30:17', '2020-12-15 23:30:17', '2021-12-16 06:30:17'),
('f709e8eedde494bd7c1e88f547a80e26e414f30612cfbef7e90064b06fece6c1f871baff39877007', 19, 1, 'nApp', '[]', 0, '2020-12-17 20:03:57', '2020-12-17 20:03:57', '2021-12-18 03:03:57'),
('f75e48d67aec97d230f7925aa8cc9c52ffff7a476efa7c486f2ff9f9a6d862d991f3ccfadc3faa22', 32, 1, 'nApp', '[]', 0, '2021-05-17 19:53:52', '2021-05-17 19:53:52', '2022-05-18 02:53:52'),
('f78a20164879fd61dd27b8865ab2c6dc295f69abe2f0b0f24679a6b7932585089afa0087d674d09c', 21, 1, 'nApp', '[]', 0, '2021-01-18 01:33:41', '2021-01-18 01:33:41', '2022-01-18 08:33:41'),
('f78e3f56ec2872f033f7d4d56726d082d12f2441aa0532372c7b13e99dd4d73ed35144e6450f7667', 4, 1, 'nApp', '[]', 0, '2020-11-30 23:42:14', '2020-11-30 23:42:14', '2021-12-01 06:42:14'),
('f7eaeb69d4a1477a3bd1ab7da38884cbf597a2cd4f9998b99fb186b81713bb641bb8d2a986e34075', 32, 1, 'nApp', '[]', 0, '2021-02-01 19:39:53', '2021-02-01 19:39:53', '2022-02-02 02:39:53'),
('f81ad706595e2194a46f81be33c8e3fc0e158b5af8ddd2cb57f6af7dcb358442240fc7e0bf3cd017', 4, 1, 'nApp', '[]', 0, '2020-12-09 19:18:47', '2020-12-09 19:18:47', '2021-12-10 02:18:47'),
('f8a49aeddca99152b0f2bf17f87837f237132606e268904a5180cd818c6996a6dd40d15298808f63', 32, 1, 'nApp', '[]', 0, '2021-02-22 20:41:50', '2021-02-22 20:41:50', '2022-02-23 03:41:50'),
('f92bbbb58fb668d79c3e23131f13fda677ab8894ccabb4a3250f0fc1d6526cccb483aecd9069efc4', 32, 1, 'nApp', '[]', 0, '2021-05-06 18:48:10', '2021-05-06 18:48:10', '2022-05-07 01:48:10'),
('fa084194edee264d131553beeb97254589ad78264f7b660e0ab187a63f9e94df0dc951b20d1db52a', 36, 1, 'nApp', '[]', 0, '2021-02-22 01:56:11', '2021-02-22 01:56:11', '2022-02-22 08:56:11'),
('fa41dc3c00617dafb258fb746628de71f8fb4666a3ff03f8b763635756a29d3511d2a6954840a6c6', 4, 1, 'nApp', '[]', 0, '2020-11-24 19:20:09', '2020-11-24 19:20:09', '2021-11-25 02:20:09'),
('fab4109b7203bf7306bb8096ea8b43542fe2637cac188e535b1a8fff594af12dfc62cf819c3047a8', 7, 1, 'nApp', '[]', 0, '2020-11-17 06:43:54', '2020-11-17 06:43:54', '2021-11-17 13:43:54'),
('fade9db78efa502d45ac5862bfdba0b1574e565a25d4c5135ec7435bb3a118b48d3bc4e35e088f0b', 21, 1, 'nApp', '[]', 0, '2021-01-06 21:01:56', '2021-01-06 21:01:56', '2022-01-07 04:01:56'),
('fba24cacf7a4830c30eb430ee28432a89b0d45a92d0c39c1491a21714ee26587c379969a78b2e517', 32, 1, 'nApp', '[]', 0, '2021-03-19 01:07:04', '2021-03-19 01:07:04', '2022-03-19 08:07:04'),
('fbf21e5149cdf474b826dd6e96f7cb47393457366abc4f84d1009a45357ef0d0a0e7da1dba783114', 3, 1, 'nApp', '[]', 0, '2020-11-16 19:42:04', '2020-11-16 19:42:04', '2021-11-17 02:42:04'),
('fd3a12ac9c872f9ddcce8cdcf2dfde15bb9422ec5877bdc0dbe748a55f08cd9145a419a08b1f0f22', 7, 1, 'nApp', '[]', 0, '2020-11-17 06:38:40', '2020-11-17 06:38:40', '2021-11-17 13:38:40'),
('fd447575f19b6a7760a6fd867827835bc2a6b51b1fc9fc30024a492761ba5aca5272a06e7b4b344f', 4, 1, 'nApp', '[]', 0, '2021-01-12 02:36:13', '2021-01-12 02:36:13', '2022-01-12 09:36:13'),
('fd583f87d0a4292940af983e7764ec0fea7244f36dba7b6b36e8841079cda3b3c06cb59852580fb3', 32, 1, 'nApp', '[]', 0, '2021-05-05 00:21:26', '2021-05-05 00:21:26', '2022-05-05 07:21:26');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_auth_codes`
--

CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_clients`
--

CREATE TABLE `oauth_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `redirect` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_clients`
--

INSERT INTO `oauth_clients` (`id`, `user_id`, `name`, `secret`, `provider`, `redirect`, `personal_access_client`, `password_client`, `revoked`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Laravel Personal Access Client', 'XtjM8GZHl3sp4VDJzQaJwSLGWvnR6DDHTgqbf7SB', NULL, 'http://localhost', 1, 0, 0, '2020-11-03 23:53:32', '2020-11-03 23:53:32'),
(2, NULL, 'Laravel Password Grant Client', 'ZIJgXob4Fb5I2y0KB4PAPtN86ndsQJDq1eSPFy2b', 'users', 'http://localhost', 0, 1, 0, '2020-11-03 23:53:32', '2020-11-03 23:53:32');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_personal_access_clients`
--

CREATE TABLE `oauth_personal_access_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_personal_access_clients`
--

INSERT INTO `oauth_personal_access_clients` (`id`, `client_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2020-11-03 23:53:32', '2020-11-03 23:53:32');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_refresh_tokens`
--

CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `organizations`
--

CREATE TABLE `organizations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `iterasi` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_str` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `organizations`
--

INSERT INTO `organizations` (`id`, `company_id`, `parent_id`, `code`, `name`, `iterasi`, `created_at`, `updated_at`, `is_str`) VALUES
(10, 1, NULL, 'CEO', 'CEO', 0, NULL, NULL, 0),
(11, 1, NULL, 'CSO', 'CSO', 0, NULL, NULL, 1),
(12, 1, NULL, 'DSO', 'Design and System Organizer', 0, NULL, NULL, 0),
(13, 1, NULL, 'CFO', 'CFO', 0, NULL, NULL, 0),
(14, 1, NULL, 'TSR', 'Treasury', 0, NULL, NULL, 0),
(16, 1, NULL, 'FC', 'Finance Control', 0, NULL, NULL, 0),
(17, 1, NULL, 'TAX', 'Tax', 0, NULL, NULL, 0),
(18, 1, NULL, 'CPO', 'CPO', 0, NULL, NULL, 0),
(19, 1, NULL, 'GA', 'General Affair', 0, NULL, NULL, 0),
(20, 1, NULL, 'PS', 'People System', 0, NULL, NULL, 0),
(21, 1, NULL, 'DRV', 'Driver', 0, NULL, NULL, 0),
(22, 1, NULL, 'COO', 'COO', 0, NULL, NULL, 0),
(23, 1, NULL, 'DIT', 'Digital & IT', 0, NULL, NULL, 0),
(24, 1, NULL, 'PM', 'Project Management', 0, NULL, NULL, 0),
(25, 1, NULL, 'CMO', 'CMO', 0, NULL, NULL, 0),
(26, 1, NULL, 'MKT', 'Marketing', 0, NULL, NULL, 0),
(30, 1, NULL, 'BC', 'Brand Communication', 0, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `test_answers`
--

CREATE TABLE `test_answers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `test_question_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_true` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `test_answers`
--

INSERT INTO `test_answers` (`id`, `test_question_id`, `name`, `is_true`, `created_at`, `updated_at`) VALUES
(137, 38, 'Akurat, Objektif, Cepat, Relevan, Interpretatif, Jelas', 1, NULL, NULL),
(138, 38, 'Akurat, Objektif, Lengkap, Selektif, Interpretatif, Jelas', 0, NULL, NULL),
(139, 38, 'Akurat, Faktual, Lengkap, Relevan, Interpretatif, Jelas', 0, NULL, NULL),
(140, 38, 'Akurat, Objektif, Lengkap, Relevan, Interpretatif, Jelas', 0, NULL, NULL),
(141, 39, 'Emosional', 0, NULL, NULL),
(142, 39, 'Kurangnya perhatian', 1, NULL, NULL),
(143, 39, 'Budaya', 0, NULL, NULL),
(144, 39, 'Semua benar', 0, NULL, NULL),
(281, 74, 'Membutuhkan tanggapan cepat', 0, NULL, NULL),
(282, 74, 'Pesan yang disampaikan sedikit', 0, NULL, NULL),
(283, 74, 'Memerlukan laporan/ dokumentasi', 1, NULL, NULL),
(284, 74, 'Pesan tidak memerlukan visualisasi', 0, NULL, NULL),
(285, 75, 'Relatif terstruktur dan terencana', 1, NULL, NULL),
(286, 75, 'Sulit dalam memahami pesan yang disampaikan', 0, NULL, NULL),
(287, 75, 'Berjalan secara statis', 0, NULL, NULL),
(288, 75, 'Memiliki sifat yang ambigu', 0, NULL, NULL),
(301, 79, 'B, C, H', 0, NULL, NULL),
(302, 79, 'A dan F', 1, NULL, NULL),
(303, 79, 'D, E, G', 0, NULL, NULL),
(304, 79, 'Jawaban B dan C benar', 0, NULL, NULL),
(305, 80, 'A, B, G', 0, NULL, NULL),
(306, 80, 'C, F, H', 0, NULL, NULL),
(307, 80, 'B, C, H', 1, NULL, NULL),
(308, 80, 'A, E, H', 0, NULL, NULL),
(309, 81, 'D dan G', 1, NULL, NULL),
(310, 81, 'B dan D', 0, NULL, NULL),
(311, 81, 'G dan E', 0, NULL, NULL),
(312, 81, 'A dan H', 0, NULL, NULL),
(313, 82, 'Opening, Body, Conclusion', 0, NULL, NULL),
(314, 82, 'Content, Design, Media', 0, NULL, NULL),
(315, 82, 'Content, Media, Delivery', 1, NULL, NULL),
(316, 82, 'Content, Design, Conclusion', 0, NULL, NULL),
(317, 83, 'Melakukan story telling', 1, NULL, NULL),
(318, 83, 'Bullet point mendetail', 0, NULL, NULL),
(319, 83, 'Melakukan banyak transisi', 0, NULL, NULL),
(320, 83, 'A dan B benar', 0, NULL, NULL),
(321, 84, '1, 2, 5', 0, NULL, NULL),
(322, 84, '1, 3, 4', 1, NULL, NULL),
(323, 84, '2, 3, 5', 0, NULL, NULL),
(324, 84, '2 dan 5', 0, NULL, NULL),
(325, 85, 'Jawab VHS 1A', 1, NULL, NULL),
(326, 85, 'Jawab VHS 1B', 0, NULL, NULL),
(327, 85, 'Jawab VHS 1C', 0, NULL, NULL),
(328, 85, 'Jawab VHS 1D', 0, NULL, NULL),
(329, 86, 'Jawab VHS 11A', 0, NULL, NULL),
(330, 86, 'Jawab VHS 11B', 0, NULL, NULL),
(331, 86, 'Jawab VHS 11C', 0, NULL, NULL),
(332, 86, 'Jawab VHS 11D', 1, NULL, NULL),
(333, 87, 'POST Test Soal 1 A', 0, NULL, NULL),
(334, 87, 'POST Test Soal 1 B', 1, NULL, NULL),
(335, 87, 'POST Test Soal 1 C', 0, NULL, NULL),
(336, 87, 'POST Test Soal 1 D', 0, NULL, NULL),
(337, 88, 'POST Test Soal 2 A', 1, NULL, NULL),
(338, 88, 'POST Test Soal 2 B', 0, NULL, NULL),
(339, 88, 'POST Test Soal 2 C', 0, NULL, NULL),
(340, 88, 'POST Test Soal 2 D', 0, NULL, NULL),
(341, 89, 'Jawab POST Soal 3A', 0, NULL, NULL),
(342, 89, 'Jawab POST Soal 3B', 0, NULL, NULL),
(343, 89, 'Jawab POST Soal 3C', 1, NULL, NULL),
(344, 89, 'Jawab POST Soal 3D', 0, NULL, NULL),
(345, 90, 'Jawab POST Soal 4A', 0, NULL, NULL),
(346, 90, 'Jawab POST Soal 4B', 0, NULL, NULL),
(347, 90, 'Jawab POST Soal 4C', 0, NULL, NULL),
(348, 90, 'Jawab POST Soal 4D', 1, NULL, NULL),
(349, 91, 'Pre 1A', 1, NULL, NULL),
(350, 91, 'Pre 1B', 0, NULL, NULL),
(351, 91, 'Pre 1C', 0, NULL, NULL),
(352, 91, 'Pre 1D', 0, NULL, NULL),
(353, 91, 'Pre 2A', 1, NULL, NULL),
(354, 92, 'Pre 2B', 0, NULL, NULL),
(355, 92, 'Pre 2C', 0, NULL, NULL),
(356, 92, 'Pre 2D', 0, NULL, NULL),
(357, 93, 'Pre 3A', 1, NULL, NULL),
(358, 93, 'Pre 3B', 0, NULL, NULL),
(359, 93, 'Pre 3C', 0, NULL, NULL),
(360, 93, 'Pre 3D', 0, NULL, NULL),
(361, 94, 'Post 1A', 1, NULL, NULL),
(362, 94, 'Post 1B', 0, NULL, NULL),
(363, 94, 'Post 1C', 0, NULL, NULL),
(364, 94, 'Post 1D', 0, NULL, NULL),
(365, 95, 'Post 2A', 1, NULL, NULL),
(366, 95, 'Post 2B', 0, NULL, NULL),
(367, 95, 'Post 2C', 0, NULL, NULL),
(368, 95, 'Post 2D', 0, NULL, NULL),
(369, 96, 'Post 3A', 1, NULL, NULL),
(370, 96, 'Post 3B', 0, NULL, NULL),
(371, 96, 'Post 3C', 0, NULL, NULL),
(372, 96, 'Post 3D', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `test_questions`
--

CREATE TABLE `test_questions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `is_pre_test` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `test_questions`
--

INSERT INTO `test_questions` (`id`, `description`, `created_at`, `updated_at`, `course_id`, `is_pre_test`) VALUES
(38, 'Kemampuan oral communication dipengaruhi oleh bakat, namun juga merupakan keterampilan yang bisa dipelajari dan dilatih. Berikut ini yang bukan merupakan kriteria oral communication yang baik adalah', NULL, NULL, 109, NULL),
(39, 'Bencana penerbangan terburuk dalam sejarah terjadi pada tahun 1977 di Tenerife di Kepulauan Kenari. Pada sore hari yang sangat berkabut, Kapten pesawat KLM mengira pengawas lalu lintas udara telah mengijinkannya lepas landas. Tetapi pengawas itu hanya bermaksud memberikan perintah keberangkatan. Walaupun bahasa yang dipakai pilot KLM Belanda dan pengawas Spanyol itu adalah bahasa Inggris, aksen yang berat dan istilah yang tidak tepat membuat kebingungan. Boeing 747 KLM menabrak Pan Am 747 dengan kecepatan penuh di Landas pacu - bencana yang disebabkan oleh miskomunikasi yang menewaskan 583 orang. Hal ini menggambarkan bagaimana miskomunikasi dapat menyebabkan akibat yang tragis. Walaupun situasi kebanyakan komunikasi tidak sedramatis itu, kenyataannya tetap bahwa komunikasi yang baik itu sangat penting bagi keefektifan semua kelompok atau organisasi. Penghalang komunikasi apakah yang ada dalam kasus di atas ?', NULL, NULL, 109, NULL),
(74, 'Komunikasi tertulis dapat dilakukan jika', NULL, NULL, 131, NULL),
(75, 'Berikut ini yang merupakan kelebihan dari komunikasi tertulis yaitu', NULL, NULL, 131, NULL),
(79, '<p class=\"ql-align-justify\">Anda adalah seorang HR Manager di sebuah perusahaan besar. Sekarang pukul 09.00 pada suatu kamis pagi dan Anda baru saja tiba di kantor. Anda tiba lebih lambat dari biasanya karena terjebakk dalam sebuah kecelakaan lalu lintas. Anda memiliki seorang HR Officer yang bergabung dua bulan lalu dan seorang admin assistant dalam tim Anda. Hal-hal berikut menanti Anda untuk diurus</p><p class=\"ql-align-justify\"><br></p><p class=\"ql-align-justify ql-indent-1\">A. Rapat: Dimulai pukul 10.00. Anda berbicara mengenai laporan yang telah Anda tulis dan sebarkan. Pembicaraan ini adalah pokok pertama dalam agenda (setelah sebelumnya permintaan maaf dan notulen rapat). Rapat dijadwalkan selesai pada pukul 13.00 tetapi tetap saja lebih dari itu</p><p><br></p><p class=\"ql-align-justify ql-indent-1\">B. Pesan dari rekan Anda: “Mohon menelepon saya sesegera mungkin – Mendesak”</p><p><br></p><p class=\"ql-align-justify ql-indent-1\">C. Pesan tertulis: “Dapatkan Anda menemui General Manager ?”</p><p><br></p><p class=\"ql-align-justify ql-indent-1\">D. Pesan tertulis:”Mohon menelepon David Jones di News berkaitan dengan sebuah artikel yang sedang ditulisnya untuk edisi besok mengenai perampokan kontroversional yang baru saja terjadi. Nomor seluler 1234567. (Pesan pukul 07.20)</p><p><br></p><p class=\"ql-align-justify ql-indent-1\">E. Email-email baru</p><p><br></p><p class=\"ql-align-justify ql-indent-1\">F. Suatu hal pada anggaran tahun depan yang direvisi merupakan agenda rapat pagi ini. Anda mengatakan akan merevisi hitungan dari departemen Anda. Anda sama sekali melupakan hal itu!. Perhitungan membutuhkan waktu 30 menit. Tidak seorangpun dapat mengumpulkannya. Anggaran merupakan agenda rapat yang ketiga</p><p><br></p><p class=\"ql-align-justify ql-indent-1\">G. Wawancara penilaian yang sudah dijadwalkan dengan anggota bagian pada pukul 14.00. Anda tidak mempunyai kesempatan untuk mempersiapkan hal ini karena adanya tekanan-tekanan lain. Anda harus menunda sekali lagi, atas permintaan Anda.</p><p><br></p><p class=\"ql-align-justify ql-indent-1\">H. Telepon berdering</p><p class=\"ql-align-justify\"><br></p><p class=\"ql-align-justify\">Berikut ini tindakan yang bisa menjadi menjadi prioritas bagi Anda untuk segera dilakukan tindakan adalah</p><p><br></p>', NULL, NULL, 131, NULL),
(80, '<p class=\"ql-align-justify\">Anda adalah seorang HR Manager di sebuah perusahaan besar. Sekarang pukul 09.00 pada suatu kamis pagi dan Anda baru saja tiba di kantor. Anda tiba lebih lambat dari biasanya karena terjebakk dalam sebuah kecelakaan lalu lintas. Anda memiliki seorang HR Officer yang bergabung dua bulan lalu dan seorang admin assistant dalam tim Anda. Hal-hal berikut menanti Anda untuk diurus</p><p class=\"ql-align-justify\"><br></p><p class=\"ql-indent-1 ql-align-justify\">A. Rapat: Dimulai pukul 10.00. Anda berbicara mengenai laporan yang telah Anda tulis dan sebarkan. Pembicaraan ini adalah pokok pertama dalam agenda (setelah sebelumnya permintaan maaf dan notulen rapat). Rapat dijadwalkan selesai pada pukul 13.00 tetapi tetap saja lebih dari itu</p><p><br></p><p class=\"ql-indent-1 ql-align-justify\">B. Pesan dari rekan Anda: “Mohon menelepon saya sesegera mungkin – Mendesak”</p><p><br></p><p class=\"ql-indent-1 ql-align-justify\">C. Pesan tertulis: “Dapatkan Anda menemui General Manager ?”</p><p><br></p><p class=\"ql-indent-1 ql-align-justify\">D. Pesan tertulis:”Mohon menelepon David Jones di News berkaitan dengan sebuah artikel yang sedang ditulisnya untuk edisi besok mengenai perampokan kontroversional yang baru saja terjadi. Nomor seluler 1234567. (Pesan pukul 07.20)</p><p><br></p><p class=\"ql-indent-1 ql-align-justify\">E. Email-email baru</p><p><br></p><p class=\"ql-indent-1 ql-align-justify\">F. Suatu hal pada anggaran tahun depan yang direvisi merupakan agenda rapat pagi ini. Anda mengatakan akan merevisi hitungan dari departemen Anda. Anda sama sekali melupakan hal itu!. Perhitungan membutuhkan waktu 30 menit. Tidak seorangpun dapat mengumpulkannya. Anggaran merupakan agenda rapat yang ketiga</p><p><br></p><p class=\"ql-indent-1 ql-align-justify\">G. Wawancara penilaian yang sudah dijadwalkan dengan anggota bagian pada pukul 14.00. Anda tidak mempunyai kesempatan untuk mempersiapkan hal ini karena adanya tekanan-tekanan lain. Anda harus menunda sekali lagi, atas permintaan Anda.</p><p><br></p><p class=\"ql-indent-1 ql-align-justify\">H. Telepon berdering</p><p class=\"ql-align-justify\"><br></p><p class=\"ql-align-justify\">Berikut ini tindakan yang bisa anda pilih untuk menghilangkan ketidakpastian adalah</p><p><br></p>', NULL, NULL, 131, NULL),
(81, '<p class=\"ql-align-justify\">Anda adalah seorang HR Manager di sebuah perusahaan besar. Sekarang pukul 09.00 pada suatu kamis pagi dan Anda baru saja tiba di kantor. Anda tiba lebih lambat dari biasanya karena terjebakk dalam sebuah kecelakaan lalu lintas. Anda memiliki seorang HR Officer yang bergabung dua bulan lalu dan seorang admin assistant dalam tim Anda. Hal-hal berikut menanti Anda untuk diurus</p><p class=\"ql-align-justify\"><br></p><p class=\"ql-align-justify ql-indent-1\">A. Rapat: Dimulai pukul 10.00. Anda berbicara mengenai laporan yang telah Anda tulis dan sebarkan. Pembicaraan ini adalah pokok pertama dalam agenda (setelah sebelumnya permintaan maaf dan notulen rapat). Rapat dijadwalkan selesai pada pukul 13.00 tetapi tetap saja lebih dari itu</p><p><br></p><p class=\"ql-align-justify ql-indent-1\">B. Pesan dari rekan Anda: “Mohon menelepon saya sesegera mungkin – Mendesak”</p><p><br></p><p class=\"ql-align-justify ql-indent-1\">C. Pesan tertulis: “Dapatkan Anda menemui General Manager ?”</p><p><br></p><p class=\"ql-align-justify ql-indent-1\">D. Pesan tertulis:”Mohon menelepon David Jones di News berkaitan dengan sebuah artikel yang sedang ditulisnya untuk edisi besok mengenai perampokan kontroversional yang baru saja terjadi. Nomor seluler 1234567. (Pesan pukul 07.20)</p><p><br></p><p class=\"ql-align-justify ql-indent-1\">E. Email-email baru</p><p><br></p><p class=\"ql-align-justify ql-indent-1\">F. Suatu hal pada anggaran tahun depan yang direvisi merupakan agenda rapat pagi ini. Anda mengatakan akan merevisi hitungan dari departemen Anda. Anda sama sekali melupakan hal itu!. Perhitungan membutuhkan waktu 30 menit. Tidak seorangpun dapat mengumpulkannya. Anggaran merupakan agenda rapat yang ketiga</p><p><br></p><p class=\"ql-align-justify ql-indent-1\">G. Wawancara penilaian yang sudah dijadwalkan dengan anggota bagian pada pukul 14.00. Anda tidak mempunyai kesempatan untuk mempersiapkan hal ini karena adanya tekanan-tekanan lain. Anda harus menunda sekali lagi, atas permintaan Anda.</p><p><br></p><p class=\"ql-align-justify ql-indent-1\">H. Telepon berdering</p><p class=\"ql-align-justify ql-indent-1\"><br></p><p class=\"ql-align-justify\">Tindakan yang tidak mendesak dan sementara bisa Anda tunda adalah</p><p><br></p>', NULL, NULL, 131, NULL),
(82, '<p class=\"ql-align-justify\">Terdapat 3 faktor utama yang harus diperhatikan dalam melakukan presentasi yang efektif, yaitu</p>', NULL, NULL, 132, NULL),
(83, '<p>Dalam melakukan presentasi kemampuan apa yang harus dimiliki untuk bisa menarik kembali fokus audiens yang mulai tidak memperhatikan materi yang kita bawakan</p>', NULL, NULL, 132, NULL),
(84, '<p>1.&nbsp;&nbsp;Memilih topik yang dikuasai, menentukan tujuan presentasi dan mengenali audiens</p><p>2.&nbsp;&nbsp;Melakukan latihan berbicara didepan cermin</p><p>3.&nbsp;&nbsp;Mengalihkan perhatian dengan melakukan aktivitas, seperti berdo’a, minum, mengatur napas, mendengarkan music, berbicara dengan teman dan melihat pemandangan</p><p>4.&nbsp;&nbsp;Memastikan laptop/ tablet sebagai media presentasi berfungsi dengan baik</p><p>5.&nbsp;&nbsp;Mencari data audiens yang hadir</p><p>Berdasarkan uraian di atas, manakah yang termasuk cara mengatasi rasa cemas ketika akan melakukan sebuah presentasi ?</p>', NULL, NULL, 132, NULL),
(85, 'Pertanyaan yang pertama VHS 1?', NULL, NULL, 136, 1),
(86, 'Pertanyaan yang kedua VHS 11', NULL, NULL, 136, 1),
(87, 'Post Test Yang Pertama VHS 1 Soal 1 POST??', NULL, NULL, 136, NULL),
(88, 'Post Test Yang Kedua VHS 1 Soal 2 POST??', NULL, NULL, 136, NULL),
(89, 'Post Test Yang Ketiga VHS 1 Soal 3 POST??', NULL, NULL, 136, NULL),
(90, 'Post Test Yang Keempat VHS 1 Soal 4 POST??', NULL, NULL, 136, NULL),
(91, 'Pre Test pertama VHS 2?', NULL, NULL, 137, 1),
(92, 'Pre Test kedua VHS 2?', NULL, NULL, 137, 1),
(93, 'Pre Test ketiga VHS 2?', NULL, NULL, 137, 1),
(94, 'Post Test pertama VHS 2?', NULL, NULL, 137, NULL),
(95, 'Post Test kedua VHS 2?', NULL, NULL, 137, NULL),
(96, 'Post Test ketiga VHS 2?', NULL, NULL, 137, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `organization_id` bigint(20) UNSIGNED NOT NULL,
  `golongan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nik` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `company_id`, `organization_id`, `golongan_id`, `nik`, `username`, `image`, `file`, `token`) VALUES
(32, 'Diah Dharmayanti', NULL, '$2y$10$4ds4kQLkBkcKG1SkB.qolODkOpjvAFgGx8QiNgDBe2.As7UT7.ubC', NULL, NULL, '2021-01-27 19:30:48', 1, 11, 1, '0102031', 'MHCSO0102031', 'user_601221d74f348.png', 'files/SX5P00D4N6.png', NULL),
(33, 'Bobby Wibowo', NULL, '$2y$10$yAZPIGWBZvTUISVzMAleS.QAuQqopUO0.i9/mLBc9Y4Bz.yNtHHsu', NULL, NULL, NULL, 1, 10, 1, '0101001', 'MHCEO0101001', 'user_60122294b034a.png', 'files/AGr7zdUbGY.png', 'dzp4mGO9mCw:APA91bHIciOuOLvVJ7_nCKAmHXjM_K7kohhBB4MCGoYkZ1IhdWZtgE9eniuuytv1yE9A9z7paIEadELGrNHJgLZGcnDE3MfXfwNLYsXJ2lanNzh5rar9-ymYJS-6rC2ZY190xE6Tiz3m'),
(34, 'Luthviana Evi Agustina', NULL, '$2y$10$A3Dl8ozOMzmavcggUpcCSOEDq7Wg6mhKBTt15H6IisCj80iabqAba', NULL, NULL, '2021-03-17 02:52:04', 1, 12, 3, '0102019', 'MHDSO0102019', 'user_601222e8d9abf.png', 'files/E93nRm4opZ.png', 'ewABglr8Ncs:APA91bGB8UWxBoKH6yDvM7XDjSoYyLx5iOt5biwEEnFP4uydJqahOn_ZHv8WpbzAMSNKCxZKgDC9svmjhT3WhqfFxzXFy6r7WqrnY2X4WnGcOR8aeR_NGWZyAEyaovMhjYHMnJYC707m'),
(35, 'Anik Primawati', NULL, '$2y$10$zDAgIw98LlET.5.4Ghk7Z.1KiuyGvCBYZOYjNKZkxvxF.3o2yMgL6', NULL, NULL, '2021-02-22 19:29:46', 1, 13, 1, '0102002', 'MHCFO0102002', 'user_60122335ce047.png', 'files/XJe0qUkMny.png', 'd5NzR7c7iLQ:APA91bFY3-c8klBOR2wsW_WVCdV2RKQ5JwfDQQmGIEUSb4ZnNvWFOAoHJGsdHOipmuiLp025a3c1AOc53uqOd3XjaKRSPpd5P0hYb3mMfGV6r6ZXfBmHfVydLC3cHFyElZUuEDNOzFoy'),
(36, 'Difit Rohkayatun', NULL, '$2y$10$g9rCpO/Mrjeo5gSup369dOzE0QyNttvmqtxYovU6/BiM9dSRorJRy', NULL, NULL, NULL, 1, 14, 3, '0102003', 'MHTSR0102003', 'user_6012238860d57.png', 'files/0Rse1OtNay.png', 'eoZ1MfXqm10:APA91bFziCTY0MGgrtqTkdpMkmzzsVU-suGhOz69qHGp3eIeldpMRaceQIQGVr6NsTq4NcCHsZvWXeJriIRFq0M2FQfXk0s67fSH4Go3Ak8yAxtajkbMF4riWWxpyxlSBQhu5rMkvRR8'),
(37, 'Dwi Artika', NULL, '$2y$10$EPsxpwxjpWlvs9E66DULt.kjRHr5lNjiDagUZDRLka2XfzQ/CinIq', NULL, NULL, NULL, 1, 16, 3, '0102005', 'MHFC0102005', 'user_601223df613f7.png', 'files/y6MLxyF2l7.png', 'erJm5kKz25o:APA91bGa3kN4nVQA5N8dtSU3MwGiKW309WyjiJvfP_jZcd9-7ZWER1MnTel84gyKlUO79Ybon3et3boktfNvjsDYW-a3qqkjRgSYUuK1X5tUPGnzLCMKLqIEDr21d-2P7GT7LD_IUZkP'),
(38, 'Endah Budiati', NULL, '$2y$10$iWtfPLcqjkiOvemF/2X0CuD.9J2l/D3Y/CmClnVo3VSQOoqLLgw9i', NULL, NULL, '2021-03-21 23:48:52', 1, 16, 5, '0102017', 'MHFC0102017', 'user_6012243a0832d.png', 'files/HJ00iCPmB5.png', 'fhhFSbd44C8:APA91bEBhVn5keKHqztSs-bSLNLqZGYh02_C52I29ABpXrbdyfsdNGTa8g0gSXIyAc8weKXJtc1A_G2-8mPO_k8QC1qa_rwQAG1igZoGkkfTlkP5RozmbaK_J1Xlt_Q4abVmNnHEhQM5'),
(39, 'Vivi Nur Damayanti', NULL, '$2y$10$ldJuThfWJoEMw9v0c1aaIuamw/LzeJY3mt3RXaCSEz1Y6Zy4k3xiW', NULL, NULL, '2021-02-22 01:53:27', 1, 16, 5, '0102022', 'MHFC0102022', 'user_601224c745726.png', 'files/Nd2JF8Hrev.png', 'd3boZCcWw3o:APA91bHsVKZ8qaRAdU0htF3WtshP-94AJR4KDDT6EUPhtbkeugpIMdSOrcK_C4g2XcJSZE6IHNu1NlDCCQ6eokihHxHO5Yh6teksyB3MKGsOIVre5io4zoDT4HMQqdh5wrfOL_P2hoKL'),
(40, 'Siska Ariyanti', NULL, '$2y$10$Rp6sGWWvom8bEWpsEYJas.Qrt0mg7KpCbRk.PvAzMS9IzaG0aNUZW', NULL, NULL, '2021-02-22 01:08:00', 1, 16, 5, '0102029', 'MHFC0102029', 'user_601225214524d.png', 'files/4eCT54ObGx.png', 'fw2J56HJJjk:APA91bE-ygOsUBgHK49vzOv4fUcqRJnQxeysBnRPdnWERe0VudtXEr8n78J12EnzY6GVnnknTwfHYHthJscugmhT2xAsEidsR7hZRjGpChQi478SwbuQJGQvNPiI-r-ygWFIOibsvfjO'),
(41, 'Siti Anis Rofiah', NULL, '$2y$10$jkLt4AahVMuScBYw7Vv7jOGUdcMW1zoNvgDaR5EyVNj697iPhJwGa', NULL, NULL, '2021-02-22 04:57:03', 1, 17, 3, '0102024', 'MHTAX0102024', 'user_6012257922d02.png', 'files/nxP05FsiiE.png', 'e7ZXTqlOxAc:APA91bG3T8-wIbhgoQIeeay5dw2rV2-lzMZZcrrKFXn8CFBN18RPXRJGKitxBGatN0NbjaC-bO82-ioZ7_XfEdlYKmyabl-0O-5RDu-YoQ_2jKPPNsPQQ0dczvh2erV6KJhqt-CiEjYS'),
(42, 'Lidya Karina Firdayani', NULL, '$2y$10$PGV3gdTE06yLInIu2EsNbeIbHDUPHTZOl4P8DxPBrIFiIjTzNoi/W', NULL, NULL, NULL, 1, 17, 3, '0102025', 'MHTAX0102025', 'user_601225cba8e9f.png', 'files/nhHE3wKtor.png', 'cOSvgngQUK4:APA91bFZfz17RRpCNQeBYahtO4OQd8ym0qjO6TQl6QR8DzpgfbEGciEY3BPha5hd3idKdGWLRI2GgWgZ3PZIXQ5z4PN_fOwhLenF-3sWVm31Hi1THxt0pLe8Qpg0k_VQtgrobNqGUE8z'),
(43, 'Sulistyowati', NULL, '$2y$10$GeGE8fkvPFCY/D7uho8jZ.KpV6UcfgTfpOJQWXvI5lo7E5wxtisUi', NULL, NULL, '2021-02-22 02:03:25', 1, 19, 3, '0102004', 'MHGA0102004', 'user_6012272166ba1.png', 'files/iM5hB5gRJS.png', 'dUt-Rgq28gE:APA91bFlc14WSpIEdHpMV69EnQnxg2PMv9xWX8gayMtAwDd2KtNaAbvacPzEj_AqNIMd7Kf3nU7UMM-u12KXurlY-BpusqTDRYKvMTh4EcOWGo3CYoZhrmKwXDP4q-UYvKK7dp-NRKCB'),
(44, 'Inggrid Mufida', NULL, '$2y$10$44dBTk.p3ExioR.uw3h0XOpXf7hd/plSSZfQLB51BV9FkLLZAx/hi', NULL, NULL, '2021-03-21 23:58:51', 1, 20, 3, '0102011', 'MHPS0102011', 'user_6012276fe67b2.png', 'files/ro0mnRJ3OE.png', 'eSm8Z8X9kkw:APA91bFQrf2DkQw0mT2CywVRrzUz2RMweY62Ys6HmOGKSTXmQmMRdpIp4qa813aM_ihM19RE3MAY1zzQediNJRIfRqp43WO2P5SVfLz74Z7VUT0BnQNOvnkjhRfiV9-gQ9z-XqCwZdem'),
(45, 'Nur Setyoko', NULL, '$2y$10$9ZW0IeJ1uRJMKMZnfnr30OVegLv81udnF6zHrPE.4fsTi3fPUQrQa', NULL, NULL, NULL, 1, 21, 5, '0102030', 'MHDRV0102030', 'user_601227cd088bc.png', 'files/a7z2iALTSZ.png', NULL),
(46, 'Fauzi Ghozali', NULL, '$2y$10$fMkSnirMbpqZ9H2wvo3N7uu3Y/Qm7HbNh3YLZW.yXtPNQ4BAQELh.', NULL, NULL, NULL, 1, 23, 3, '0102035', 'MHDIT0102035', 'user_601228171658a.png', 'files/7jADVqxfXJ.png', NULL),
(47, 'Muhammad Arif Syahbana K. Sigit', NULL, '$2y$10$91FH8/j3VZQQ1HB/sAqPgen7hwf.bKxpDiaCsq/PaFzaqARIzR.7u', NULL, NULL, NULL, 1, 24, 5, '0102034', 'MHPM0102034', 'user_601228669d037.png', 'files/W9B5k54Tww.png', 'cGi0PvtyfDE:APA91bGIjnxsDHeB38Q_MBZ9mKXtnD8UOnqqPjnCZrFU0A57sN1oWQvVcr4ZYAoWnuv9W6XJlctDdkvO0hUhwC8NKyqjPAMTb9gk9d7IK0sifysdj1qoOkdkilhzfFzn5sTVWxULsiT0'),
(48, 'Pandu Ardiansah', NULL, '$2y$10$Usc8bug6PgYtFwdLK5r5jeKY7CJubwCU4SJzWPPCAHRFNN.CW.mtu', NULL, NULL, NULL, 1, 24, 3, '0102007', 'MHPM0102007', 'user_601228b6d6803.png', 'files/NA77LcOgev.png', 'fjO0ER-XhYM:APA91bEhI7dkNw18fzKWTopGAwETco-AG6JeRa_vcMLSHP381ONQeyVOqv4WSZs_VBhkr234a1l3w3JqPSsaE6Ue5ZmVxCkDrih-R3DckN_JucqbiiAaGQe6paSFu2Wg_xG4D5T_brha'),
(49, 'Ari Widyanto', NULL, '$2y$10$gFaDHFrecmdJcmgHvxvfbuO9Vdoxh7NRj2c1jYDi/nmIcfMjgjxwK', NULL, NULL, NULL, 1, 26, 3, '0102032', 'MHMKT0102032', 'user_60122913adef4.png', 'files/gwqZPpJAbr.png', 'eSSxWI1-0Fg:APA91bGxs5FyKX0C4a9-jIzk6PdKe2LAy8BeM2NCkNx6RH0MLobNbCFu7K1SVYg_FTl8eIbtT8AphzSJ3XBoVxmMWzL4cSAHt6ztJOxQ2UxolndFjOKG1_aj0CAH8zjUKn6hatMzGyxN'),
(51, 'Nuwas Dzarrin Tantowi', NULL, '$2y$10$mhCiYfGynT2V/tF9Kiyz9O.e0CEn848HT/8xhmbzfsvqUm1hAEi0m', NULL, NULL, NULL, 1, 23, 5, '001210184', 'MHDIT001210184', 'user_60a4be7a35fb0.png', 'files/tVat7u4kWe.png', 'e7ZXTqlOxAc:APA91bG3T8-wIbhgoQIeeay5dw2rV2-lzMZZcrrKFXn8CFBN18RPXRJGKitxBGatN0NbjaC-bO82-ioZ7_XfEdlYKmyabl-0O-5RDu-YoQ_2jKPPNsPQQ0dczvh2erV6KJhqt-CiEjYS'),
(52, 'Agiem Christian', NULL, '$2y$10$NR9vExMT7ZppuYqrrnbzMOFomudVKdm1Qz.BjyxW6Y044Bf27ftqS', NULL, NULL, '2021-05-20 00:26:44', 1, 30, 4, '001210180', 'MHBC001210180', 'user_60a4becf6244b.png', 'files/wcH5Y4XlLp.png', 'fxVlxVvYj7A:APA91bGm8KltCcdYyqxH60vLTTYG8sQUvIY9YbSXVgBDjGe5Tdk4A2Onmo0fcZODi277zxzEsp6KxliOHO24PT0-wSZz5iEtACtxLJ2Vvp3XTSBHf8CE6tp8JjWj6_LScD8oIyGlXXKr');

-- --------------------------------------------------------

--
-- Table structure for table `user_scores`
--

CREATE TABLE `user_scores` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `score` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_done` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_pre_test` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_scores`
--

INSERT INTO `user_scores` (`id`, `course_id`, `user_id`, `score`, `status`, `is_done`, `created_at`, `updated_at`, `is_pre_test`) VALUES
(46, 109, 34, 40, '2', 0, NULL, NULL, NULL),
(47, 109, 36, 20, '2', 0, NULL, NULL, NULL),
(48, 109, 48, 0, '1', 0, NULL, NULL, NULL),
(49, 109, 44, 20, '2', 0, NULL, NULL, NULL),
(51, 109, 38, 0, '1', 0, NULL, NULL, NULL),
(52, 109, 40, 40, '2', 0, NULL, NULL, NULL),
(54, 109, 37, 40, '2', 0, NULL, NULL, NULL),
(55, 109, 35, 40, '2', 0, NULL, NULL, NULL),
(56, 109, 49, 40, '2', 0, NULL, NULL, NULL),
(57, 109, 33, 40, '2', 0, NULL, NULL, NULL),
(59, 109, 43, 0, '2', 0, NULL, NULL, NULL),
(85, 131, 34, 100, '2', 0, NULL, NULL, NULL),
(86, 109, 46, 40, '2', 0, NULL, NULL, NULL),
(88, 109, 51, 40, '2', 0, NULL, NULL, NULL),
(91, 131, 40, 80, '2', 0, NULL, NULL, NULL),
(95, 131, 36, 100, '2', 0, NULL, NULL, NULL),
(96, 131, 35, 100, '2', 0, NULL, NULL, NULL),
(98, 131, 38, 0, '1', 0, NULL, NULL, NULL),
(108, 132, 36, 40, '2', 0, NULL, NULL, NULL),
(109, 132, 34, 60, '2', 0, NULL, NULL, NULL),
(110, 132, 38, 0, '1', 0, NULL, NULL, NULL),
(111, 132, 40, 20, '2', 0, NULL, NULL, NULL),
(113, 132, 37, 40, '2', 0, NULL, NULL, NULL),
(114, 132, 49, 60, '2', 0, NULL, NULL, NULL),
(115, 131, 49, 100, '2', 0, NULL, NULL, NULL),
(123, 132, 46, 0, '1', 0, NULL, NULL, NULL),
(124, 132, 35, 60, '2', 0, NULL, NULL, NULL),
(127, 132, 41, 60, '2', 0, NULL, NULL, NULL),
(128, 131, 41, 80, '2', 0, NULL, NULL, NULL),
(129, 109, 41, 40, '2', 0, NULL, NULL, NULL),
(130, 132, 42, 60, '2', 0, NULL, NULL, NULL),
(131, 131, 42, 80, '2', 0, NULL, NULL, NULL),
(132, 109, 42, 40, '2', 0, NULL, NULL, NULL),
(133, 131, 46, 40, '2', 0, NULL, NULL, NULL),
(138, 136, 46, 0, '1', 0, NULL, NULL, 1),
(139, 136, 46, 0, '1', 0, NULL, NULL, 0),
(140, 137, 46, 0, '1', 0, NULL, NULL, 1),
(141, 137, 46, 0, '1', 0, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `vhs`
--

CREATE TABLE `vhs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `thumbnail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `video` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vhs`
--

INSERT INTO `vhs` (`id`, `title`, `description`, `thumbnail`, `video`, `created_at`, `updated_at`) VALUES
(17, 'video 1', 'descripsi singkat', 'files/vhs/thumbnail/J9eb6aezpYaAyRda8GRHEIl1erd27fzKfKVGOuk3.jpg', 'files/vhs/video/FcPdTU6XFMQhB9cwWzpj.mp4', '2021-06-15 21:26:14', '2021-06-15 21:26:14');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `calendars`
--
ALTER TABLE `calendars`
  ADD PRIMARY KEY (`id`),
  ADD KEY `calendars_user_id_foreign` (`user_id`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `courses_organization_id_foreign` (`organization_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `events_company_id_foreign` (`company_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `golongans`
--
ALTER TABLE `golongans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leaderboards`
--
ALTER TABLE `leaderboards`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `materis`
--
ALTER TABLE `materis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `materis_course_id_foreign` (`course_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_access_tokens`
--
ALTER TABLE `oauth_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_access_tokens_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_auth_codes`
--
ALTER TABLE `oauth_auth_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_auth_codes_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_clients_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_refresh_tokens`
--
ALTER TABLE `oauth_refresh_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`);

--
-- Indexes for table `organizations`
--
ALTER TABLE `organizations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `organizations_company_id_foreign` (`company_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `test_answers`
--
ALTER TABLE `test_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `test_answers_test_question_id_foreign` (`test_question_id`);

--
-- Indexes for table `test_questions`
--
ALTER TABLE `test_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `test_questions_course_id_foreign` (`course_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_company_id_foreign` (`company_id`),
  ADD KEY `users_organization_id_foreign` (`organization_id`),
  ADD KEY `users_golongan_id_foreign` (`golongan_id`);

--
-- Indexes for table `user_scores`
--
ALTER TABLE `user_scores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_scores_course_id_foreign` (`course_id`),
  ADD KEY `user_scores_user_id_foreign` (`user_id`);

--
-- Indexes for table `vhs`
--
ALTER TABLE `vhs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `calendars`
--
ALTER TABLE `calendars`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=138;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `golongans`
--
ALTER TABLE `golongans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `leaderboards`
--
ALTER TABLE `leaderboards`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `materis`
--
ALTER TABLE `materis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `organizations`
--
ALTER TABLE `organizations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `test_answers`
--
ALTER TABLE `test_answers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=373;

--
-- AUTO_INCREMENT for table `test_questions`
--
ALTER TABLE `test_questions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `user_scores`
--
ALTER TABLE `user_scores`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=142;

--
-- AUTO_INCREMENT for table `vhs`
--
ALTER TABLE `vhs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `calendars`
--
ALTER TABLE `calendars`
  ADD CONSTRAINT `calendars_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_organization_id_foreign` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`);

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`);

--
-- Constraints for table `materis`
--
ALTER TABLE `materis`
  ADD CONSTRAINT `materis_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`);

--
-- Constraints for table `organizations`
--
ALTER TABLE `organizations`
  ADD CONSTRAINT `organizations_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`);

--
-- Constraints for table `test_answers`
--
ALTER TABLE `test_answers`
  ADD CONSTRAINT `test_answers_test_question_id_foreign` FOREIGN KEY (`test_question_id`) REFERENCES `test_questions` (`id`);

--
-- Constraints for table `test_questions`
--
ALTER TABLE `test_questions`
  ADD CONSTRAINT `test_questions_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `users_golongan_id_foreign` FOREIGN KEY (`golongan_id`) REFERENCES `golongans` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `users_organization_id_foreign` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`);

--
-- Constraints for table `user_scores`
--
ALTER TABLE `user_scores`
  ADD CONSTRAINT `user_scores_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`),
  ADD CONSTRAINT `user_scores_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
