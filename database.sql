CREATE TABLE [IF NOT EXISTS] `blogwritter`.`postable` (
`id` INT NOT NULL AUTO_INCREMENT ,
`author` INT NOT NULL ,
`content` VARCHAR(1024) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

CREATE TABLE [IF NOT EXISTS] `blogwritter`.`users` (
`id` INT NOT NULL AUTO_INCREMENT ,
`nickname` VARCHAR(20) NOT NULL ,
`reports` INT NOT NULL ,
`email` VARCHAR(40) NOT NULL ,
`password` VARCHAR(20) NOT NULL ,
PRIMARY KEY (`id`)) ENGINE = InnoDB;

CREATE TABLE [IF NOT EXISTS] `blogwritter`.`posts` (
`comments` INT NOT NULL ,
`id_postable` INT NOT NULL ,
`title` VARCHAR(255) NOT NULL ,
`description` VARCHAR(1024) NOT NULL ,
`reports` INT NOT NULL ,
`slug` VARCHAR(255) NOT NULL ) ENGINE = InnoDB;

CREATE TABLE [IF NOT EXISTS] `blogwritter`.`comments` (
`id_postable` INT NOT NULL ,
`reports` INT NOT NULL ) ENGINE = InnoDB;

CREATE TABLE [IF NOT EXISTS] `blogwritter`.`reports` (
`id_postable` INT NOT NULL ) ENGINE = InnoDB;

CREATE TABLE [IF NOT EXISTS] `blogwritter`.`comment_reports` (
`id` INT NOT NULL AUTO_INCREMENT ,
`id_report` INT NOT NULL ,
`id_comment` INT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

CREATE TABLE [IF NOT EXISTS] `blogwritter`.`users_comments` (
`id` INT NOT NULL AUTO_INCREMENT ,
`id_user` INT NOT NULL ,
`id_comment` INT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

