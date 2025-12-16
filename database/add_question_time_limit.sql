-- Add time_limit column to questions table
ALTER TABLE `questions` ADD COLUMN `time_limit` INT(11) DEFAULT 60 COMMENT 'Time limit in seconds for this question';

-- Update existing questions to have default time limit of 60 seconds
UPDATE `questions` SET `time_limit` = 60 WHERE `time_limit` IS NULL OR `time_limit` = 0;
