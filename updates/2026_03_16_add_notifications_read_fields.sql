ALTER TABLE `notifications`
  ADD COLUMN `type` VARCHAR(50) NOT NULL DEFAULT 'general' AFTER `content`,
  ADD COLUMN `is_read` TINYINT(1) NOT NULL DEFAULT 0 AFTER `type`,
  ADD COLUMN `read_at` DATETIME NULL DEFAULT NULL AFTER `is_read`;

ALTER TABLE `notifications`
  ADD KEY `client_id` (`client_id`),
  ADD KEY `is_read` (`is_read`);
