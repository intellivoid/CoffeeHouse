-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 20, 2020 at 04:18 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.2.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `coffeehouse`
--

-- --------------------------------------------------------

--
-- Table structure for table `chat_dialogs`
--

CREATE TABLE `chat_dialogs` (
  `id` int(255) NOT NULL COMMENT 'Internal Database ID for this message',
  `session_id` varchar(255) DEFAULT NULL COMMENT 'The session that''s associated with this message',
  `step` int(255) DEFAULT NULL COMMENT 'The dialog step which leaded up to this message',
  `input` text DEFAULT NULL COMMENT 'The user input',
  `output` text DEFAULT NULL COMMENT 'AI Output',
  `timestamp` int(255) DEFAULT NULL COMMENT 'Unix Timestamp of this record'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Table history of chat conversations by dialog steps';

-- --------------------------------------------------------

--
-- Table structure for table `cookies`
--

CREATE TABLE `cookies` (
  `id` int(11) NOT NULL COMMENT 'Cookie ID',
  `date_creation` int(11) DEFAULT NULL COMMENT 'The unix timestamp of when the cookie was created',
  `disposed` tinyint(1) DEFAULT NULL COMMENT 'Flag for if the cookie was disposed',
  `name` varchar(255) DEFAULT NULL COMMENT 'The name of the Cookie (Public)',
  `token` varchar(255) DEFAULT NULL COMMENT 'The public token of the cookie which uniquely identifies it',
  `expires` int(11) DEFAULT NULL COMMENT 'The Unix Timestamp of when the cookie should expire',
  `ip_tied` tinyint(1) DEFAULT NULL COMMENT 'If the cookie should be strictly tied to the client''s IP Address',
  `client_ip` varchar(255) DEFAULT NULL COMMENT 'The client''s IP Address of the cookie is tied to the IP',
  `data` blob DEFAULT NULL COMMENT 'ZiProto Encoded Data associated with the cookie'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='The main database for Secured Web Sessions library';

-- --------------------------------------------------------

--
-- Table structure for table `foreign_sessions`
--

CREATE TABLE `foreign_sessions` (
  `id` int(255) NOT NULL COMMENT 'Internal Session ID',
  `session_id` varchar(255) DEFAULT NULL COMMENT 'Public Session ID to identify this session and it''s properties',
  `headers` blob DEFAULT NULL COMMENT 'ZiProto encoded HTTP Headers that are used in this session',
  `cookies` blob DEFAULT NULL COMMENT 'ZiProto encoded HTTP Cookies that are used in this session',
  `variables` blob DEFAULT NULL COMMENT 'ZiProto encoded HTTP Body request variables that are used in this session',
  `language` varchar(20) DEFAULT NULL COMMENT 'The language that this session is based in',
  `available` tinyint(1) DEFAULT NULL COMMENT 'Indicates if this session has been expired by force',
  `messages` int(255) DEFAULT NULL COMMENT 'The total amount of messages that has been sent to this session',
  `expires` int(255) DEFAULT NULL COMMENT 'Unix Timestamp of when this session expires',
  `last_updated` int(255) DEFAULT NULL COMMENT 'The Unix Timestamp of when this session was last updated',
  `created` int(255) DEFAULT NULL COMMENT 'Unix Timestamp of when this session has been created'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Table for foreign chat sessions (eg; third party bots)';

-- --------------------------------------------------------

--
-- Table structure for table `user_subscriptions`
--

CREATE TABLE `user_subscriptions` (
  `id` int(255) NOT NULL COMMENT 'Primary unique internal Database ID for this record',
  `account_id` int(255) DEFAULT NULL COMMENT 'The ID of the user''s Intellivoid Account',
  `subscription_id` int(255) DEFAULT NULL COMMENT 'The ID of the subscription that this user is associated to',
  `access_record_id` int(255) DEFAULT NULL COMMENT 'The ID of the access record ID used for the API',
  `status` int(255) DEFAULT NULL COMMENT 'The status of this user subscription',
  `created_timestamp` int(255) DEFAULT NULL COMMENT 'The Unix Timestamp of when this record was created'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Table of user subscriptions to keep track of the components of the IVA System';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chat_dialogs`
--
ALTER TABLE `chat_dialogs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `chat_dialogs_id_uindex` (`id`);

--
-- Indexes for table `foreign_sessions`
--
ALTER TABLE `foreign_sessions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `foreign_sessions_id_uindex` (`id`);

--
-- Indexes for table `user_subscriptions`
--
ALTER TABLE `user_subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_subscriptions_id_uindex` (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chat_dialogs`
--
ALTER TABLE `chat_dialogs`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT COMMENT 'Internal Database ID for this message';

--
-- AUTO_INCREMENT for table `foreign_sessions`
--
ALTER TABLE `foreign_sessions`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT COMMENT 'Internal Session ID';

--
-- AUTO_INCREMENT for table `user_subscriptions`
--
ALTER TABLE `user_subscriptions`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT COMMENT 'Primary unique internal Database ID for this record';
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
