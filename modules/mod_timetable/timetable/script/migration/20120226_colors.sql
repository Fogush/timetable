ALTER TABLE `lesson_types`
    ADD COLUMN `color` VARCHAR(50) NULL AFTER `shortname`;

UPDATE `lesson_types` SET `color`='red' WHERE `id`=2 LIMIT 1;
UPDATE `lesson_types` SET `color`='blue' WHERE `id`=3 LIMIT 1;