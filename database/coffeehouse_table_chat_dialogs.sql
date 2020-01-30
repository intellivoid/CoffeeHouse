
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
