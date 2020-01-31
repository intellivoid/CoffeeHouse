
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
