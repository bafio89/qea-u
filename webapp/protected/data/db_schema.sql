SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `qbase` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `qbase` ;

-- -----------------------------------------------------
-- Table `qbase`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qbase`.`users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(200) NULL,
  `nickname` VARCHAR(25) NULL,
  `password` VARCHAR(64) NULL,
  `slug` VARCHAR(250) NULL,
  `status` TINYINT UNSIGNED ZEROFILL NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC),
  UNIQUE INDEX `nickname_UNIQUE` (`nickname` ASC),
  UNIQUE INDEX `slug_UNIQUE` (`slug` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qbase`.`users_personal_info`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qbase`.`users_personal_info` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `first_name` VARCHAR(100) NULL,
  `last_name` VARCHAR(100) NULL,
  `gender` ENUM('M','F') NULL,
  `birthdate` DATE NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `user_id_UNIQUE` (`user_id` ASC),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  CONSTRAINT `fk_users_personal_info_1`
    FOREIGN KEY (`user_id`)
    REFERENCES `qbase`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qbase`.`users_metadata`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qbase`.`users_metadata` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `key` VARCHAR(50) NOT NULL,
  `value` TEXT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  CONSTRAINT `fk_users_metadata_1`
    FOREIGN KEY (`user_id`)
    REFERENCES `qbase`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qbase`.`users_settings`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qbase`.`users_settings` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `key` VARCHAR(50) NOT NULL,
  `value` TEXT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  CONSTRAINT `fk_users_settings_1`
    FOREIGN KEY (`user_id`)
    REFERENCES `qbase`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qbase`.`users_additional_info`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qbase`.`users_additional_info` (
  `id` BIGINT NOT NULL,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `key` VARCHAR(50) NOT NULL,
  `value` TEXT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_users_additional_info_1`
    FOREIGN KEY (`user_id`)
    REFERENCES `qbase`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qbase`.`addresses`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qbase`.`addresses` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `country` VARCHAR(100) NULL,
  `state` VARCHAR(100) NULL,
  `city` VARCHAR(100) NULL,
  `street_name` VARCHAR(250) NULL,
  `postal_code` VARCHAR(10) NULL,
  `gps_longitude` FLOAT(10,6) NULL,
  `gps_latitude` FLOAT(10,6) NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qbase`.`users_addresses`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qbase`.`users_addresses` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `key` VARCHAR(50) NOT NULL,
  `address_id` BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_users_addresses_2_idx` (`address_id` ASC),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  CONSTRAINT `fk_users_addresses_1`
    FOREIGN KEY (`user_id`)
    REFERENCES `qbase`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_users_addresses_2`
    FOREIGN KEY (`address_id`)
    REFERENCES `qbase`.`addresses` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qbase`.`users_oauth_accounts`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qbase`.`users_oauth_accounts` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `oauth_provider` VARCHAR(50) NOT NULL,
  `oauth_token` TEXT NULL,
  `oauth_token_type` VARCHAR(50) NULL,
  `oauth_token_expires` DATETIME NULL,
  `oauth_id` TEXT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  CONSTRAINT `fk_users_oauth_accounts_1`
    FOREIGN KEY (`user_id`)
    REFERENCES `qbase`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qbase`.`groups`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qbase`.`groups` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `object_type` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qbase`.`users_groups`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qbase`.`users_groups` (
  `user_id` BIGINT UNSIGNED NOT NULL,
  `group_id` BIGINT UNSIGNED NOT NULL,
  INDEX `fk_users_groups_2_idx` (`group_id` ASC),
  CONSTRAINT `fk_users_groups_1`
    FOREIGN KEY (`user_id`)
    REFERENCES `qbase`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_users_groups_2`
    FOREIGN KEY (`group_id`)
    REFERENCES `qbase`.`groups` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qbase`.`files`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qbase`.`files` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(250) NULL,
  `description` TEXT NULL,
  `mime_type` VARCHAR(25) NOT NULL,
  `created_time` DATETIME NOT NULL,
  `local_file_path` TEXT NULL,
  `local_file_name` TEXT NULL,
  `local_file_extension` TEXT NULL,
  `local_file_size` INT UNSIGNED ZEROFILL NULL DEFAULT 0,
  `remote_file_path` TEXT NULL,
  `remote_file_name` TEXT NULL,
  `remote_file_extension` TEXT NULL,
  `remote_file_size` INT UNSIGNED ZEROFILL NULL DEFAULT 0,
  `url` TEXT NULL,
  `visibility` TINYINT UNSIGNED ZEROFILL NOT NULL DEFAULT 0,
  `status` TINYINT UNSIGNED ZEROFILL NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `qbase`.`users_avatars`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qbase`.`users_avatars` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `file_id` BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `user_id_UNIQUE` (`user_id` ASC),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  UNIQUE INDEX `file_id_UNIQUE` (`file_id` ASC),
  CONSTRAINT `fk_users_avatars_1`
    FOREIGN KEY (`user_id`)
    REFERENCES `qbase`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_users_avatars_2`
    FOREIGN KEY (`file_id`)
    REFERENCES `qbase`.`files` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `qbase`.`permissions_rules`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qbase`.`permissions_rules` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(250) NOT NULL,
  `description` TEXT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `qbase`.`permissions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qbase`.`permissions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `subject_type` ENUM('users','groups') NOT NULL,
  `subject_id` BIGINT NOT NULL,
  `rule_id` INT UNSIGNED NOT NULL,
  `object_type` VARCHAR(250) NULL,
  `object_id` BIGINT UNSIGNED NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `fk_permissions_1_idx` (`rule_id` ASC),
  CONSTRAINT `fk_permissions_1`
    FOREIGN KEY (`rule_id`)
    REFERENCES `qbase`.`permissions_rules` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
