ALTER TABLE clients
  ADD COLUMN display_name VARCHAR(225) NULL AFTER currency,
  ADD COLUMN avatar VARCHAR(255) NULL AFTER display_name,
  ADD COLUMN bio TEXT NULL AFTER avatar;
