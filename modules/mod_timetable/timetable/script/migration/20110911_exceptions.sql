CREATE TABLE `exceptions` (
  `id` int(11) NOT NULL auto_increment,
  `timetable_id` int(11) NOT NULL,
  `exception_date` datetime default NULL,
  `begin_time` varchar(5) default NULL,
  `without_lesson` tinyint(4) default NULL,
  `subject_id` int(11) default NULL,
  `type_id` int(11) default NULL,
  `classroom` varchar(20) default NULL,
  `subgroup` int(11) default NULL,
  `comment` varchar(255) default NULL,
  PRIMARY KEY  (`id`),
  KEY `timetable_id` (`timetable_id`),
  KEY `subject_id` (`subject_id`),
  KEY `type_id` (`type_id`),
  CONSTRAINT `exceptions_lesson_types` FOREIGN KEY (`type_id`) REFERENCES `lesson_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `exceptions_subjects` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `exceptions_timetables` FOREIGN KEY (`timetable_id`) REFERENCES `timetables` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;