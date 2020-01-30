
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
