
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
