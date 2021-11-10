-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 09, 2021 at 06:28 AM
-- Server version: 8.0.13-4
-- PHP Version: 7.2.24-0ubuntu0.18.04.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rest_api`
--

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(10) UNSIGNED NOT NULL,
  `method` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(48) COLLATE utf8_unicode_ci NOT NULL,
  `request` text NOT NULL,
  `response` text NOT NULL,
  `failure_response` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `method`, `url`, `request`, `response`, `failure_response`) VALUES
(1, 'post', 'web_app_login', '{\"email_id\": \"balaji@infinitisoftware.net\", \"password\": \"Infi@123\"}', '{\"response\": {\"data\": {\"user_id\": 1, \"email_id\": \"balaji@infinitisoftware.net\", \"user_type\": \"Super user\"}, \"Message\": \"Success\"}, \"responseCode\": 0}', '{\"response\": {\"Message\": \"Autherizaton failed\"}, \"responseCode\": 1}'),
(2, 'get', 'checksession', '[]', '{\"responseCode\": 200}', '{\"responseCode\": 403}'),
(3, 'get', 'web_app_logout', '[]', '{\"responseCode\": 200}', '[]'),
(4, 'post', 'forgotpassword', '{\"email_id\": \"balaji@infinitisoftware.net\"}', '{\"response\": {\"Message\": \"password reset request initiated successfully!!!!\"}, \"responseCode\": 0}', '{\"response\": {\"Message\": \"Account not found\"}, \"responseCode\": 1}'),
(5, 'get', 'reset_link', '{\"token\": \"5b8d2ec940b1099b2bc1802604d15d97\"}', '{\"response\": {\"data\": {\"email\": \"balaji@infinitisoftware.net\", \"token\": \"5b8d2ec940b1099b2bc1802604d15d97\"}, \"Message\": \"Success\"}, \"responseCode\": 0}', '{\"response\": {\"Message\": \"Invalid token\"}, \"responseCode\": 1}'),
(6, 'post', 'resetpassword', '{\"email\": \"balaji@infinitisoftware.net\", \"token\": \"5b8d2ec940b1099b2bc1802604d15d97\", \"password\": \"Infi@123\", \"confirm_password\": \"Infi@123\"}', '{\"response\": {\"Message\": \"password updated successfully!!!\"}, \"responseCode\": 0}', '{\"response\": {\"Message\": \"password mismatch\"}, \"responseCode\": 1}'),
(7, 'get', 'dashboard', '[]', '{\"response\": {\"data\": {\"emails_in_queue\": \"35\", \"total_templates\": \"100\", \"active_templates\": \"30\", \"created_templates\": \"12\", \"total_emails_sent\": 1, \"in_active_templates\": \"70\", \"settings_wise_usages\": [{\"setting_name\": \"Infiniti mail server\", \"email_instances\": 150, \"total_api_request\": 100}, {\"setting_name\": \"Infiniti mail server1\", \"email_instances\": 150, \"total_api_request\": 100}]}, \"Message\": \"Success\"}, \"responseCode\": 0}', '[]'),
(8, 'get', 'project', '[]', '{\"response\": {\"data\": {\"count\": 1, \"links\": {\"next\": null, \"previous\": null}, \"results\": [{\"status\": \"1\", \"actions\": [\"New request adhoc\", \"New request adhoc airline\"], \"created_at\": \"2021-02-10 16:56:25\", \"masterInfo\": {\"status\": [{\"label\": \"Active\", \"value\": 1}, {\"label\": \"In-active\", \"value\": 1}]}, \"project_id\": 1, \"action_count\": 2, \"project_name\": \"GRM\", \"project_title\": \"Test\"}]}, \"Message\": \"Success\"}, \"responseCode\": 0}', '[]'),
(9, 'post', 'project', '{\"add_project\": {\"status\": \"1\", \"actions\": [{\"status\": \"1\", \"action_name\": \"registration\"}, {\"status\": \"1\", \"action_name\": \"registration corporate\"}], \"project_name\": \"AYP\"}}', '{\"response\": {\"data\": {\"status\": \"1\", \"actions\": [{\"status\": \"1\", \"action_id\": 1, \"action_name\": \"registration\", \"unique_name\": \"grm_registration\", \"created_date\": \"2021-02-10 16:56:25\"}, {\"status\": \"1\", \"action_id\": 2, \"action_name\": \"registration corporate\", \"unique_name\": \"grm_registration_corporate\", \"created_date\": \"2021-02-10 16:56:25\"}], \"created_at\": \"2021-02-10 16:56:25\", \"masterInfo\": {\"status\": [{\"label\": \"Active\", \"value\": 1}, {\"label\": \"In-active\", \"value\": 1}]}, \"project_id\": 2, \"action_count\": 2, \"project_name\": \"AYP\"}, \"Message\": \"Success\"}, \"responseCode\": 0}', '[]'),
(10, 'get', 'project/1', '[]', '{\"response\": {\"data\": {\"status\": \"1\", \"actions\": [{\"status\": \"1\", \"action_id\": 1, \"action_name\": \"New request adhoc\", \"unique_name\": \"grm_new_request_adhoc\", \"created_date\": \"2021-02-10 16:56:25\"}, {\"status\": \"1\", \"action_id\": 2, \"action_name\": \"New request adhoc airline\", \"unique_name\": \"grm_new_request_adhoc_airline\", \"created_date\": \"2021-02-10 16:56:25\"}], \"created_at\": \"2021-02-10 16:56:25\", \"masterInfo\": {\"status\": [{\"label\": \"Active\", \"value\": 1}, {\"label\": \"In-active\", \"value\": 1}]}, \"project_id\": 1, \"action_count\": 2, \"project_code\": \"GRM\", \"project_title\": \"Test\"}, \"Message\": \"Success\"}, \"responseCode\": 0}', '[]'),
(11, 'put', 'project/1', '{\"update_project\": {\"status\": \"1\", \"actions\": [{\"status\": \"1\", \"action_id\": 1, \"action_name\": \"New request adhoc\"}, {\"status\": \"2\", \"action_id\": 2, \"action_name\": \"New request adhoc airline\"}, {\"status\": \"1\", \"action_name\": \"New request series\"}, {\"status\": \"1\", \"action_name\": \"New request series airline\"}], \"project_name\": \"GRM\"}}', '{\"response\": {\"data\": {\"status\": \"1\", \"actions\": [{\"status\": \"1\", \"action_id\": 1, \"action_name\": \"New request adhoc\", \"unique_name\": \"grm_new_request_adhoc\", \"created_date\": \"2021-02-10 16:56:25\"}, {\"status\": \"1\", \"action_id\": 2, \"action_name\": \"New request adhoc airline\", \"unique_name\": \"grm_new_request_adhoc_airline\", \"created_date\": \"2021-02-10 16:56:25\"}, {\"status\": \"1\", \"action_id\": 3, \"action_name\": \"New request series\", \"unique_name\": \"grm_new_request_series\"}, {\"status\": \"1\", \"action_id\": 4, \"action_name\": \"New request series airline\", \"unique_name\": \"grm_new_request_series_airline\"}], \"created_at\": \"2021-02-10 16:56:25\", \"masterInfo\": {\"status\": [{\"label\": \"Active\", \"value\": 1}, {\"label\": \"In-active\", \"value\": 1}]}, \"project_id\": 1, \"action_count\": 4, \"project_name\": \"GRM\"}, \"Message\": \"Success\"}, \"responseCode\": 0}', '[]'),
(12, 'delete', 'project/1', '[]', '{\"responseCode\": 204}', '[]'),
(13, 'put', 'project/status', '{\"status\": 1, \"project_id\": 3}', '{\"response\": {\"data\": {\"status\": 1, \"project_id\": 3}, \"Message\": \"Success\"}, \"responseCode\": 0}', '[]'),
(14, 'get', 'setting', '[]', '{\"response\": {\"data\": {\"count\": 2, \"links\": {\"next\": null, \"previous\": null}, \"results\": [{\"port\": \"441\", \"status\": \"1\", \"end_point\": \"mail.infinitisoftware.net\", \"user_name\": \"kaviyarasan.a@infinitisoftware.net\", \"created_at\": \"2021-03-22T10:22:44Z\", \"masterInfo\": {\"status\": [{\"label\": \"Active\", \"value\": 1}, {\"label\": \"In-active\", \"value\": 1}]}, \"setting_id\": 1, \"project_name\": \"GRM\", \"setting_name\": \"Infiniti mail server\", \"setting_type\": \"SMTP\", \"from_email_id\": \"kaviyarasan.a@infinitisoftware.net\"}, {\"port\": \"\", \"status\": \"1\", \"end_point\": \"\", \"user_name\": \"\", \"created_at\": \"2021-03-22T10:22:44Z\", \"setting_id\": 2, \"project_name\": \"GRM\", \"setting_name\": \"GRM mail API\", \"setting_type\": \"Mail\", \"from_email_id\": \"kaviyarasan.a@infinitisoftware.net\"}]}, \"Message\": \"Success\"}, \"responseCode\": 0}', '[]'),
(15, 'post', 'setting', '{\"add_setting\": {\"status\": \"1\", \"password\": \"Infiniti@123\", \"end_point\": \"mail.infinitisoftware.net\", \"user_name\": \"kaviyarasan.a@infinitisoftware.net\", \"project_id\": \"3\", \"port_number\": \"465\", \"setting_name\": \"Test KM\", \"setting_type\": \"1\", \"from_email_id\": \"kaviyarasan.a@infinitisoftware.net\"}}', '{\"response\": {\"data\": {\"port\": \"465\", \"status\": \"1\", \"end_point\": \"mail.infinitisoftware.net\", \"user_name\": \"kaviyarasan.a@infinitisoftware.net\", \"created_at\": \"2021-03-22T10:22:44Z\", \"masterInfo\": {\"status\": [{\"label\": \"Active\", \"value\": 1}, {\"label\": \"In-active\", \"value\": 1}], \"setting_type\": [{\"setting_type\": \"SMTP\", \"setting_type_id\": \"1\"}, {\"setting_type\": \"Mail\", \"setting_type_id\": \"2\"}]}, \"setting_id\": 3, \"project_name\": \"GRM\", \"setting_name\": \"Test KM\", \"setting_type\": \"1\", \"from_email_id\": \"kaviyarasan.a@infinitisoftware.net\"}, \"Message\": \"Success\"}, \"responseCode\": 0}', '[]'),
(16, 'delete', 'setting/1', '[]', '{\"responseCode\": 204}', '[]'),
(17, 'put', 'setting/1', '{\"update_setting\": {\"status\": \"2\", \"password\": \"Infi@123\", \"end_point\": \"mail.b6.net\", \"user_name\": \"testmail\", \"port_number\": \"23\", \"setting_name\": \"Test B63\", \"setting_type\": \"1\", \"from_email_id\": \"testmail@b6.com\"}}', '{\"response\": {\"data\": {\"port\": \"23\", \"status\": \"1\", \"end_point\": \"mail.b6.net\", \"user_name\": \"testmail\", \"created_at\": \"2021-03-22T10:22:44Z\", \"masterInfo\": {\"status\": [{\"label\": \"Active\", \"value\": 1}, {\"label\": \"In-active\", \"value\": 1}], \"setting_type\": [{\"setting_type\": \"SMTP\", \"setting_type_id\": \"1\"}, {\"setting_type\": \"Mail\", \"setting_type_id\": \"2\"}]}, \"setting_id\": 2, \"project_name\": \"GRM\", \"setting_name\": \"Test B63\", \"setting_type\": \"1\", \"from_email_id\": \"testmail@b6.com\"}, \"Message\": \"Success\"}, \"responseCode\": 0}', '[]'),
(18, 'get', 'setting/1', '[]', '{\"response\": {\"data\": {\"port\": \"441\", \"status\": \"1\", \"end_point\": \"mail.infinitisoftware.net\", \"user_name\": \"kaviyarasan.a@infinitisoftware.net\", \"created_at\": \"2021-03-22T10:22:44Z\", \"masterInfo\": {\"status\": [{\"label\": \"Active\", \"value\": 1}, {\"label\": \"In-active\", \"value\": 1}], \"setting_type\": [{\"setting_type\": \"SMTP\", \"setting_type_id\": \"1\"}, {\"setting_type\": \"Mail\", \"setting_type_id\": \"2\"}]}, \"setting_id\": 1, \"project_name\": \"GRM\", \"setting_name\": \"Infiniti mail server\", \"setting_type\": \"1\", \"from_email_id\": \"kaviyarasan.a@infinitisoftware.net\"}, \"Message\": \"Success\"}, \"responseCode\": 0}', '[]');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
