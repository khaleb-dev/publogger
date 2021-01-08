DROP DATABASE IF EXISTS `publogger`;

CREATE DATABASE `publogger`;

USE `publogger`;

CREATE TABLE IF NOT EXISTS `user` (
    `id` INT(2) NOT NULL AUTO_INCREMENT,
	`full_name` VARCHAR(200) NOT NULL,
	`phone_no` VARCHAR(15) NULL,
	`email` VARCHAR(100) NULL,
	`profile_photo` VARCHAR(200) NULL,
	`username` VARCHAR(20) NOT NULL,
	`password` VARCHAR(225) NOT NULL,
	`pwd_reset_token` VARCHAR(256) NULL,
	`pwd_reset_token_created_at` DATETIME NULL,
    `api_key` VARCHAR(50) NOT NULL,
    `api_secret` VARCHAR(50) NOT NULL,
    `logged_in` TINYINT(1) NOT NULL DEFAULT 0,
    `auth_token` VARCHAR(50) NOT NULL,
	`auth_token_created_at` DATETIME NULL,
    `user_active` TINYINT(1) NOT NULL DEFAULT 1,
	`created_at` DATETIME NOT NULL,
	`updated_at` DATETIME NOT NULL,
	PRIMARY KEY (`id`)
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `group` (
    `id` INT(2) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `description` VARCHAR(200) NULL,
	`created_at` DATETIME NOT NULL,
	PRIMARY KEY (`id`)
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `post` (
    `id` BIGINT(18) NOT NULL AUTO_INCREMENT,
    `user_id` INT(2) NOT NULL,
    `group_id` INT(2) NOT NULL,
    `is_deleted` TINYINT(1) NOT NULL DEFAULT 0,
    `is_published` TINYINT(1) NOT NULL DEFAULT 0,
    `slug` VARCHAR(300) NOT NULL,
	`post_title` TEXT NOT NULL,
	`post_body` MEDIUMTEXT NOT NULL,
	`thumbnail_url` VARCHAR(200) NULL,
	`total_views` INT(9) NOT NULL DEFAULT 0,
	`published_on` DATETIME NOT NULL,
	`updated_on` DATETIME NOT NULL,
    PRIMARY KEY (`id`),
	FOREIGN KEY (`user_id`) REFERENCES user(`id`),
	FOREIGN KEY (`group_id`) REFERENCES group(`id`)
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `tags` (
    `id` INT(4) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `description` VARCHAR(200) NULL,
	`created_at` DATETIME NOT NULL,
	PRIMARY KEY (`id`)
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `post_tags` (
    `id` BIGINT(18) NOT NULL AUTO_INCREMENT,
    `tag_id` INT(4) NOT NULL,
    `post_id` BIGINT(18) NOT NULL,
    `created_at` DATETIME NOT NULL,
    PRIMARY KEY (`id`),
	FOREIGN KEY (`tag_id`) REFERENCES tags(`id`),
	FOREIGN KEY (`post_id`) REFERENCES post(`id`)
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `images` (
    `id` INT(8) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(200) NOT NULL,
	`created_at` DATETIME NOT NULL,
	PRIMARY KEY (`id`)
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `post_images`(
	`id` INT(8) NOT NULL AUTO_INCREMENT,
	`post_id` BIGINT(18) NOT NULL,
	`image_id` INT(8) NOT NULL,
	`created_at` DATETIME NOT NULL,
	PRIMARY KEY (`id`),
	FOREIGN KEY (`post_id`) REFERENCES post(`id`),
	FOREIGN KEY (`image_id`) REFERENCES images(`id`)
)ENGINE=InnoDB;

COMMIT;