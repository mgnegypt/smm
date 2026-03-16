CREATE TABLE IF NOT EXISTS `theme_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `theme_dirname` varchar(120) NOT NULL,
  `token_primary` varchar(20) NOT NULL DEFAULT '#73a7ff',
  `card_style` enum('soft','sharp','glass') NOT NULL DEFAULT 'glass',
  `ui_density` enum('compact','comfortable','spacious') NOT NULL DEFAULT 'comfortable',
  `token_payload` text DEFAULT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_theme_dirname` (`theme_dirname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
