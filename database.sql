CREATE TABLE IF NOT EXISTS `blogwritter`.`content` (
`id` INT NOT NULL AUTO_INCREMENT ,
`author` INT NOT NULL ,
`content` VARCHAR(1024) NOT NULL ,
`created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
`updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
`deleted_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
PRIMARY KEY (`id`)) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `blogwritter`.`users` (
`id` INT NOT NULL AUTO_INCREMENT ,
`nickname` VARCHAR(20) NOT NULL ,
`email` VARCHAR(40) NOT NULL ,
`password` VARCHAR(20) NOT NULL ,
`role` INT NOT NULL ,
`created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
`updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
`deleted_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
PRIMARY KEY (`id`)) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `blogwritter`.`posts` (
`id` INT NOT NULL AUTO_INCREMENT ,
`id_content` INT NOT NULL ,
`title` VARCHAR(255) NOT NULL ,
`description` VARCHAR(1024) NOT NULL ,
`slug` VARCHAR(255) NOT NULL ,
`created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
`updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
`deleted_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
PRIMARY KEY (`id`)) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `blogwritter`.`comments` (
`id` INT NOT NULL AUTO_INCREMENT ,
`created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
`updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
`deleted_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
PRIMARY KEY (`id`)) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `blogwritter`.`reports` (
`id` INT NOT NULL AUTO_INCREMENT ,
`id_content` INT NOT NULL ,
`created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
`updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
`deleted_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
PRIMARY KEY (`id`)) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `blogwritter`.`posts_reports` (
`id` INT NOT NULL AUTO_INCREMENT ,
`id_post` INT NOT NULL ,
`id_report` INT NOT NULL ,
PRIMARY KEY (`id`)) ENGINE = InnoDB;

ALTER TABLE `content`
ADD CONSTRAINT `link_author_to_id`
FOREIGN KEY (`author`)
REFERENCES `users`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT ;

ALTER TABLE `posts`
ADD CONSTRAINT `link_id_content_to_id`
FOREIGN KEY (`id_content`)
REFERENCES `content`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE `reports`
ADD CONSTRAINT `link_reports_id_content_to_id`
FOREIGN KEY (`id_content`)
REFERENCES `content`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE `content` CHANGE `content` `content` TEXT
CHARACTER SET latin1
COLLATE latin1_swedish_ci NOT NULL;

ALTER TABLE `users`
ADD UNIQUE(`email`);

ALTER TABLE `users` CHANGE `password` `password` VARCHAR(256)
CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

ALTER TABLE `posts` CHANGE `deleted_at` `deleted_at` DATETIME NULL DEFAULT NULL;
ALTER TABLE `users` CHANGE `deleted_at` `deleted_at` DATETIME NULL DEFAULT NULL;
ALTER TABLE `comments` CHANGE `deleted_at` `deleted_at` DATETIME NULL DEFAULT NULL;
ALTER TABLE `reports` CHANGE `deleted_at` `deleted_at` DATETIME NULL DEFAULT NULL;
ALTER TABLE `content` CHANGE `deleted_at` `deleted_at` DATETIME NULL DEFAULT NULL;

ALTER TABLE `posts` DROP FOREIGN KEY `link_id_content_to_id`;
ALTER TABLE `posts` ADD CONSTRAINT `link_id_content_to_id`
FOREIGN KEY (`id_content`) REFERENCES `content`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

INSERT INTO `users` (`id`, `nickname`, `email`, `password`, `role`, `created_at`, `updated_at`, `deleted_at`)
VALUES (NULL, 'admin', 'admin@domain.com', 'admin', '1', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)

ALTER TABLE `comments` ADD `id_content` INT NOT NULL AFTER `id`;
ALTER TABLE `comments` ADD CONSTRAINT `id_content_to_content_id` FOREIGN KEY (`id_content`) REFERENCES `content`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `comments` ADD `id_post` INT NOT NULL AFTER `id_content`;
ALTER TABLE `comments` ADD CONSTRAINT `id_post_to_post_id` FOREIGN KEY (`id_post`) REFERENCES `posts`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE `posts` CHANGE `slug` `slug` VARCHAR(1024) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE `posts` CHANGE `title` `title` VARCHAR(1024) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE `reports` CHANGE `id_content` `id_user` INT(11) NOT NULL;
ALTER TABLE `reports` DROP FOREIGN KEY `link_reports_id_content_to_id`; ALTER TABLE `reports` ADD CONSTRAINT `id_user to user_id` FOREIGN KEY (`id_user`) REFERENCES `users`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE `comments_reports` ADD UNIQUE(`id_comment`);
ALTER TABLE `comments_reports` ADD CONSTRAINT `id report to report id` FOREIGN KEY (`id_report`) REFERENCES `reports`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT; ALTER TABLE `comments_reports` ADD CONSTRAINT `id comment to comment id` FOREIGN KEY (`id_comment`) REFERENCES `comments`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION; ALTER TABLE `comments_reports` ADD CONSTRAINT `id user to user id` FOREIGN KEY (`id_user`) REFERENCES `users`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE `reports` DROP FOREIGN KEY `id_user to user_id`; ALTER TABLE `reports` ADD CONSTRAINT `id_user to user_id` FOREIGN KEY (`id_user`) REFERENCES `users`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `comments_reports` ADD `id_post` INT NOT NULL AFTER `id_user`;


