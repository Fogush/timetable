CREATE TABLE `groups_subjects` (
  `id` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `group_id` INTEGER(11) NOT NULL,
  `subject_id` INTEGER(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`),
  KEY `subject_id` (`subject_id`)
)ENGINE=InnoDB
AUTO_INCREMENT=25 CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';
COMMIT;



/* Data for the `groups_subjects` table  (Records 1 - 24) */

INSERT INTO `groups_subjects` (`id`, `group_id`, `subject_id`) VALUES 
  (1, 1, 3),
  (2, 1, 4),
  (3, 1, 5),
  (4, 1, 6),
  (5, 1, 7),
  (6, 1, 8),
  (7, 1, 9),
  (8, 1, 10),
  (9, 2, 3),
  (10, 2, 4),
  (11, 2, 5),
  (12, 2, 6),
  (13, 2, 7),
  (14, 2, 8),
  (15, 2, 9),
  (16, 2, 10),
  (17, 3, 3),
  (18, 3, 4),
  (19, 3, 5),
  (20, 3, 6),
  (21, 3, 7),
  (22, 3, 8),
  (23, 3, 9),
  (24, 3, 10);