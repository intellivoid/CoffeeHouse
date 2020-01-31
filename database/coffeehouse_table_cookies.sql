
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
