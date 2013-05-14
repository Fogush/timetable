CREATE TABLE `teachers` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `first_name` VARCHAR(255) NULL DEFAULT NULL,
    `last_name` VARCHAR(255) NULL DEFAULT NULL,
    `middle_name` VARCHAR(255) NULL DEFAULT NULL,
    `phone` VARCHAR(255) NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
)
ENGINE=InnoDB;

/* Данные для 7-го семестра 981011-13 */
INSERT INTO `teachers` (`first_name`, `last_name`, `middle_name`, `phone`) VALUES 
    ('Анатолий', 'Митюхин', 'Иванович', '+375 29 1112057'),
    ('Елена', 'Лашкевич', 'Михайловна', '+375 29 7773472'),
    ('Дмитрий', 'Горбачев', 'Владимирович', '+375 29 7766889 / +375 29 3905578'),
    ('Ирина', 'Коренская', 'Николаевна', '+375447309588'),
    ('Георгий', 'Сечко', 'Владимирович', 'vel. 6035413, мтс 8655413, дом. 2435413'),
    ('Елена', 'Моженкова', 'Викторовна', '+375 29 1132283');

CREATE TABLE `subjects_lesstypes_teachers` (
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`subject_id` INT(11) NOT NULL,
	`lesson_type_id` INT(11) NOT NULL,
	`teacher_id` INT(11) NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `FK_subjects_lesstypes_teachers_subjects` (`subject_id`),
	INDEX `FK_subjects_lesstypes_teachers_lesson_types` (`lesson_type_id`),
	INDEX `FK_subjects_lesstypes_teachers_teachers` (`teacher_id`),
	CONSTRAINT `FK_subjects_lesstypes_teachers_lesson_types` FOREIGN KEY (`lesson_type_id`) REFERENCES `lesson_types` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT `FK_subjects_lesstypes_teachers_subjects` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT `FK_subjects_lesstypes_teachers_teachers` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
)
ENGINE=InnoDB;

/* Данные для 7-го семестра 981011-13 */
INSERT INTO `subjects_lesstypes_teachers` (`subject_id`, `lesson_type_id`, `teacher_id`) VALUES 
	(23, 1, 1),
	(23, 2, 1),
	(19, 1, 2),
	(19, 2, 2),
	(17, 1, 3),
	(17, 2, 3),
	(20, 1, 3),
	(20, 2, 3),
	(18, 1, 4),
	(18, 2, 4),
	(21, 1, 5),
	(22, 1, 5),
	(24, 1, 5),
	(21, 2, 6),
	(22, 2, 6);