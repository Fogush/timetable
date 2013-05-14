CREATE TABLE `role_categories` (
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(50) NULL DEFAULT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;

INSERT INTO `role_categories` (`id`, `name`) VALUES 
	(1, 'guest'),
	(2, 'admin'),
	(3, 'register');

CREATE TABLE `users` (
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	`group_id` INT(11) NULL DEFAULT NULL,
	`role_id` INT(11) NULL DEFAULT NULL,
	`first_name` VARCHAR(255) NULL DEFAULT NULL,
	`last_name` VARCHAR(255) NULL DEFAULT NULL,
	`nick` VARCHAR(255) NULL DEFAULT NULL,
	`email` VARCHAR(255) NULL DEFAULT NULL,
	`password` VARCHAR(255) NULL DEFAULT NULL,
	`phpbb_user_id` INT(11) NULL DEFAULT NULL,
	PRIMARY KEY (`id`),
	INDEX `FK_users_role_categories` (`role_id`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;

INSERT INTO `users` (`id`, `group_id`, `role_id`, `first_name`, `last_name`, `nick`, `email`, `password`, `phpbb_user_id`) VALUES 
	(1, NULL, 1, 'Гость', NULL, NULL, NULL, NULL, NULL),
	(2, 2, 2, 'Денис', 'Гуров', NULL, 'fogusw@yandex.ru', '24246a1904446e60481c4b849996d02d', 53),
	(3, 3, 3, 'Аня', 'Ефимова', NULL, '981013@gmail.com', 'a76cbff1893d414a01c9846cb437a2b3', 346),
	(4, 1, 3, 'Вадим', 'Воробьев', NULL, '981011@gmail.com', '0690ca08df00bd1ce72a5efdb99231ab', 347);


CREATE TABLE `action_log` (
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	`description` VARCHAR(255) NULL DEFAULT NULL,
	`user_id` INT(10) NULL DEFAULT NULL,
	`date_created` DATETIME NULL DEFAULT NULL,
	PRIMARY KEY (`id`),
	INDEX `FK_action_log_users` (`user_id`),
	CONSTRAINT `FK_action_log_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE ON DELETE SET NULL
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;

