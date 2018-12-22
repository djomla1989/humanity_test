CREATE DATABASE IF NOT EXISTS `user_vacation`;

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `vacation_days` int(11) NOT NULL DEFAULT 20,
  `created` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `modified` DATETIME ON UPDATE CURRENT_TIMESTAMP,
   PRIMARY KEY  (`id`)
);


CREATE TABLE IF NOT EXISTS `user_vacation_request` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `approver_id` int(11) NOT NULL,
  `date_from` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_to`   DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `number_of_days` int(11) NOT NULL DEFAULT 0,
  `status` ENUM('pending' , 'approved', 'declined') DEFAULT 'pending',
  `created` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `modified` DATETIME ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  CONSTRAINT `user_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `user_ibfk_2` FOREIGN KEY (`approver_id`) REFERENCES `user` (`id`)
);

INSERT INTO user_vacation.`user`
(name)
VALUES('Petar Petrovic'), ('Sima Simic'), ('Laza Lazic');
