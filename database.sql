CREATE TABLE IF NOT EXISTS `blogwritter`.`postable` (
`id` INT NOT NULL AUTO_INCREMENT ,
`author` INT NOT NULL ,
`content` VARCHAR(1024) NOT NULL ,
PRIMARY KEY (`id`)) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `blogwritter`.`users` (
`id` INT NOT NULL AUTO_INCREMENT ,
`nickname` VARCHAR(20) NOT NULL ,
`email` VARCHAR(40) NOT NULL ,
`password` VARCHAR(20) NOT NULL ,
`created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
`updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
`deleted_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
PRIMARY KEY (`id`)) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `blogwritter`.`posts` (
`id` INT NOT NULL AUTO_INCREMENT ,
`id_postable` INT NOT NULL ,
`title` VARCHAR(255) NOT NULL ,
`description` VARCHAR(1024) NOT NULL ,
`slug` VARCHAR(255) NOT NULL ,
`created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
`updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
`deleted_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
PRIMARY KEY (`id`)) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `blogwritter`.`comments` (
`id` INT NOT NULL AUTO_INCREMENT ,
`id_postable` INT NOT NULL ,
`created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
`updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
`deleted_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
PRIMARY KEY (`id`)) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `blogwritter`.`reports` (
`id` INT NOT NULL AUTO_INCREMENT ,
`id_postable` INT NOT NULL ,
`created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
`updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
`deleted_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
PRIMARY KEY (`id`)) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `blogwritter`.`comments_reports` (
`id` INT NOT NULL AUTO_INCREMENT ,
`id_report` INT NOT NULL ,
`id_comment` INT NOT NULL ,
PRIMARY KEY (`id`)) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `blogwritter`.`posts_comments` (
`id` INT NOT NULL AUTO_INCREMENT ,
`id_post` INT NOT NULL ,
`id_comment` INT NOT NULL ,
PRIMARY KEY (`id`)) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `blogwritter`.`posts_reports` (
`id` INT NOT NULL AUTO_INCREMENT ,
`id_post` INT NOT NULL ,
`id_report` INT NOT NULL ,
PRIMARY KEY (`id`)) ENGINE = InnoDB;

ALTER TABLE `postable`
ADD CONSTRAINT `link_author_to_id`
FOREIGN KEY (`author`)
REFERENCES `users`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE `posts`
ADD CONSTRAINT `link_id_postable_to_id`
FOREIGN KEY (`id_postable`)
REFERENCES `postable`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE `comments`
ADD CONSTRAINT `link_id_postable_in_comments_to_id`
FOREIGN KEY (`id_postable`)
REFERENCES `postable`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE `reports`
ADD CONSTRAINT `link_reports_id_postable_to_id`
FOREIGN KEY (`id_postable`)
REFERENCES `postable`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE `posts_reports`
ADD CONSTRAINT `link_id_post_to_id`
FOREIGN KEY (`id_post`)
REFERENCES `posts`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE `posts_reports`
ADD CONSTRAINT `link_id_report_to_id`
FOREIGN KEY (`id_report`)
REFERENCES `reports`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE `comments_reports`
ADD CONSTRAINT `link_comments_reports_id_report_to_id`
FOREIGN KEY (`id_report`)
REFERENCES `reports`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE `comments_reports`
ADD CONSTRAINT `link_comments_reports_id_comment_to_id`
FOREIGN KEY (`id_comment`)
REFERENCES `comments`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE `posts_comments`
ADD CONSTRAINT `link_id_comment_to_comments_id`
FOREIGN KEY (`id_comment`)
REFERENCES `comments`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE `posts_comments`
ADD CONSTRAINT `link_posts_comments_id_post_to_id`
FOREIGN KEY (`id_post`)
REFERENCES `posts`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

