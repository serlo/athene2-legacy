SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

DROP SCHEMA IF EXISTS `serlo_test` ;
CREATE SCHEMA IF NOT EXISTS `serlo_test` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `serlo_test` ;

-- -----------------------------------------------------
-- Table `serlo_test`.`language`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo_test`.`language` ;

CREATE TABLE IF NOT EXISTS `serlo_test`.`language` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `code` VARCHAR(2) NOT NULL,
  `dateformat` VARCHAR(45) NOT NULL,
  `timeformat` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC),
  UNIQUE INDEX `code_UNIQUE` (`code` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo_test`.`role`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo_test`.`role` ;

CREATE TABLE IF NOT EXISTS `serlo_test`.`role` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(32) NOT NULL,
  `parent_id` INT(11) UNSIGNED NULL,
  `description` VARCHAR(255) NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `uniq_name` (`name` ASC),
  INDEX `fk_role_role1_idx` (`parent_id` ASC),
  CONSTRAINT `fk_role_role1`
    FOREIGN KEY (`parent_id`)
    REFERENCES `serlo_test`.`role` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `serlo_test`.`user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo_test`.`user` ;

CREATE TABLE IF NOT EXISTS `serlo_test`.`user` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(127) NOT NULL,
  `username` VARCHAR(32) NOT NULL DEFAULT '',
  `password` CHAR(50) NOT NULL,
  `logins` INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `last_login` TIMESTAMP NULL,
  `language_id` INT UNSIGNED NOT NULL DEFAULT 1,
  `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `givenname` VARCHAR(255) NULL,
  `lastname` VARCHAR(255) NULL,
  `gender` VARCHAR(1) NOT NULL DEFAULT 'n',
  `ads_enabled` TINYINT(1) NOT NULL DEFAULT 0,
  `removed` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `uniq_username` (`username` ASC),
  UNIQUE INDEX `uniq_email` (`email` ASC),
  INDEX `fk_user_language1_idx` (`language_id` ASC),
  CONSTRAINT `fk_user_language1`
    FOREIGN KEY (`language_id`)
    REFERENCES `serlo_test`.`language` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `serlo_test`.`user_token`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo_test`.`user_token` ;

CREATE TABLE IF NOT EXISTS `serlo_test`.`user_token` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) UNSIGNED NOT NULL,
  `user_agent` VARCHAR(40) NOT NULL,
  `token` VARCHAR(32) NOT NULL,
  `created` INT(10) UNSIGNED NOT NULL,
  `expires` INT(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `uniq_token` (`token` ASC),
  INDEX `fk_user_id` (`user_id` ASC),
  CONSTRAINT `user_tokens_ibfk_1`
    FOREIGN KEY (`user_id`)
    REFERENCES `serlo_test`.`user` (`id`)
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `serlo_test`.`license`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo_test`.`license` ;

CREATE TABLE IF NOT EXISTS `serlo_test`.`license` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `language_id` INT UNSIGNED NOT NULL,
  `content` TEXT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_license_language1_idx` (`language_id` ASC),
  CONSTRAINT `fk_license_language1`
    FOREIGN KEY (`language_id`)
    REFERENCES `serlo_test`.`language` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo_test`.`entity_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo_test`.`entity_type` ;

CREATE TABLE IF NOT EXISTS `serlo_test`.`entity_type` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `className_UNIQUE` (`name` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo_test`.`entity_revision`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo_test`.`entity_revision` ;

CREATE TABLE IF NOT EXISTS `serlo_test`.`entity_revision` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `author_id` INT(11) UNSIGNED NOT NULL,
  `repository_id` BIGINT UNSIGNED NOT NULL,
  `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `trashed` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  INDEX `fk_revision_entity1_idx` (`repository_id` ASC),
  INDEX `fk_entity_revision_user1_idx` (`author_id` ASC),
  CONSTRAINT `fk_revision_entity1`
    FOREIGN KEY (`repository_id`)
    REFERENCES `serlo_test`.`entity` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_entity_revision_user1`
    FOREIGN KEY (`author_id`)
    REFERENCES `serlo_test`.`user` (`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo_test`.`uuid`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo_test`.`uuid` ;

CREATE TABLE IF NOT EXISTS `serlo_test`.`uuid` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` VARCHAR(30) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `uuid_UNIQUE` (`uuid` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo_test`.`entity`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo_test`.`entity` ;

CREATE TABLE IF NOT EXISTS `serlo_test`.`entity` (
  `id` BIGINT UNSIGNED NOT NULL,
  `language_id` INT UNSIGNED NOT NULL,
  `entity_type_id` INT UNSIGNED NOT NULL,
  `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `trashed` TINYINT(1) NOT NULL DEFAULT 0,
  `current_revision_id` INT UNSIGNED NULL,
  `license_id` INT UNSIGNED NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_entity_language1_idx` (`language_id` ASC),
  INDEX `fk_entity_license1_idx` (`license_id` ASC),
  INDEX `fk_entity_entity_factory1_idx` (`entity_type_id` ASC),
  INDEX `fk_entity_entity_revision1_idx` (`current_revision_id` ASC),
  INDEX `fk_entity_uuid_idx` (`id` ASC),
  CONSTRAINT `fk_entity_language1`
    FOREIGN KEY (`language_id`)
    REFERENCES `serlo_test`.`language` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_entity_license1`
    FOREIGN KEY (`license_id`)
    REFERENCES `serlo_test`.`license` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_entity_entity_factory1`
    FOREIGN KEY (`entity_type_id`)
    REFERENCES `serlo_test`.`entity_type` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_entity_entity_revision1`
    FOREIGN KEY (`current_revision_id`)
    REFERENCES `serlo_test`.`entity_revision` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_entity_uuid`
    FOREIGN KEY (`id`)
    REFERENCES `serlo_test`.`uuid` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo_test`.`entity_link_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo_test`.`entity_link_type` ;

CREATE TABLE IF NOT EXISTS `serlo_test`.`entity_link_type` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo_test`.`entity_link`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo_test`.`entity_link` ;

CREATE TABLE IF NOT EXISTS `serlo_test`.`entity_link` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `entity_link_type_id` INT UNSIGNED NOT NULL,
  `parent_id` BIGINT UNSIGNED NOT NULL,
  `child_id` BIGINT UNSIGNED NOT NULL,
  `order` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_entity_link_entity1_idx` (`parent_id` ASC),
  INDEX `fk_entity_link_entity2_idx` (`child_id` ASC),
  INDEX `fk_entity_link_link_type1_idx` (`entity_link_type_id` ASC),
  UNIQUE INDEX `uq_entity_link` (`entity_link_type_id` ASC, `parent_id` ASC, `child_id` ASC),
  CONSTRAINT `fk_entity_link_entity1`
    FOREIGN KEY (`parent_id`)
    REFERENCES `serlo_test`.`entity` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_entity_link_entity2`
    FOREIGN KEY (`child_id`)
    REFERENCES `serlo_test`.`entity` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_entity_link_link_type1`
    FOREIGN KEY (`entity_link_type_id`)
    REFERENCES `serlo_test`.`entity_link_type` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo_test`.`page_revision`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo_test`.`page_revision` ;

CREATE TABLE IF NOT EXISTS `serlo_test`.`page_revision` (
  `id` INT NOT NULL,
  `author_id` INT(11) UNSIGNED NOT NULL,
  `page_repository` BIGINT UNSIGNED NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `content` LONGTEXT NOT NULL,
  `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `trashed` TINYINT(1) NOT NULL DEFAULT FALSE,
  PRIMARY KEY (`id`),
  INDEX `fk_page_revision_user1_idx` (`author_id` ASC),
  INDEX `fk_page_revision_page1_idx` (`page_repository` ASC),
  CONSTRAINT `fk_page_revision_user1`
    FOREIGN KEY (`author_id`)
    REFERENCES `serlo_test`.`user` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_page_revision_page1`
    FOREIGN KEY (`page_repository`)
    REFERENCES `serlo_test`.`page` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo_test`.`page`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo_test`.`page` ;

CREATE TABLE IF NOT EXISTS `serlo_test`.`page` (
  `id` BIGINT UNSIGNED NOT NULL,
  `language_id` INT UNSIGNED NOT NULL,
  `slug` VARCHAR(255) NOT NULL,
  `role_id` INT(11) UNSIGNED NULL,
  `current_revision_id` INT NULL,
  INDEX `fk_page_translation_language1_idx` (`language_id` ASC),
  INDEX `fk_page_uuid1_idx` (`id` ASC),
  PRIMARY KEY (`id`),
  INDEX `fk_page_page_revision2_idx` (`current_revision_id` ASC),
  INDEX `fk_page_role1_idx` (`role_id` ASC),
  UNIQUE INDEX `slug_UNIQUE` (`slug` ASC, `language_id` ASC),
  CONSTRAINT `fk_page_translation_language1`
    FOREIGN KEY (`language_id`)
    REFERENCES `serlo_test`.`language` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_page_uuid1`
    FOREIGN KEY (`id`)
    REFERENCES `serlo_test`.`uuid` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_page_page_revision2`
    FOREIGN KEY (`current_revision_id`)
    REFERENCES `serlo_test`.`page_revision` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_page_role1`
    FOREIGN KEY (`role_id`)
    REFERENCES `serlo_test`.`role` (`id`)
    ON DELETE SET NULL
    ON UPDATE SET NULL)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo_test`.`blog_post`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo_test`.`blog_post` ;

CREATE TABLE IF NOT EXISTS `serlo_test`.`blog_post` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) UNSIGNED NOT NULL,
  `language_id` INT UNSIGNED NOT NULL,
  `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `slug` VARCHAR(255) NOT NULL,
  `sticky` TINYINT(1) NOT NULL DEFAULT FALSE,
  `title` VARCHAR(255) NOT NULL,
  `content` LONGTEXT NOT NULL,
  `publish` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_blog_posts_user1_idx` (`user_id` ASC),
  INDEX `fk_blog_posts_language1_idx` (`language_id` ASC),
  UNIQUE INDEX `uq_blog_post_slug_language` (`language_id` ASC, `slug` ASC),
  CONSTRAINT `fk_blog_posts_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `serlo_test`.`user` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_blog_posts_language1`
    FOREIGN KEY (`language_id`)
    REFERENCES `serlo_test`.`language` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo_test`.`navigation`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo_test`.`navigation` ;

CREATE TABLE IF NOT EXISTS `serlo_test`.`navigation` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `chronology` INT NOT NULL,
  `uri` VARCHAR(255) NOT NULL,
  `match` VARCHAR(255) NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `navigation_id` INT UNSIGNED NULL,
  `role_id` INT(11) UNSIGNED NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_navigation_navigation2_idx` (`navigation_id` ASC),
  INDEX `fk_navigation_role2_idx` (`role_id` ASC),
  CONSTRAINT `fk_navigation_navigation2`
    FOREIGN KEY (`navigation_id`)
    REFERENCES `serlo_test`.`navigation` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_navigation_role2`
    FOREIGN KEY (`role_id`)
    REFERENCES `serlo_test`.`role` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo_test`.`entity_revision_field`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo_test`.`entity_revision_field` ;

CREATE TABLE IF NOT EXISTS `serlo_test`.`entity_revision_field` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `field` VARCHAR(255) NOT NULL,
  `entity_revision_id` INT UNSIGNED NOT NULL,
  `value` LONGTEXT NOT NULL,
  PRIMARY KEY (`id`, `field`),
  INDEX `fk_entity_revision_value_entity_revision1_idx` (`entity_revision_id` ASC),
  CONSTRAINT `fk_entity_revision_value_entity_revision1`
    FOREIGN KEY (`entity_revision_id`)
    REFERENCES `serlo_test`.`entity_revision` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo_test`.`role_user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo_test`.`role_user` ;

CREATE TABLE IF NOT EXISTS `serlo_test`.`role_user` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) UNSIGNED NOT NULL,
  `role_id` INT(11) UNSIGNED NOT NULL,
  `language_id` INT UNSIGNED NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_role_user_user1_idx` (`user_id` ASC),
  INDEX `fk_role_user_role1_idx` (`role_id` ASC),
  INDEX `fk_role_user_language1_idx` (`language_id` ASC),
  CONSTRAINT `fk_role_user_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `serlo_test`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_role_user_role1`
    FOREIGN KEY (`role_id`)
    REFERENCES `serlo_test`.`role` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_role_user_language1`
    FOREIGN KEY (`language_id`)
    REFERENCES `serlo_test`.`language` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo_test`.`user_log`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo_test`.`user_log` ;

CREATE TABLE IF NOT EXISTS `serlo_test`.`user_log` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) UNSIGNED NOT NULL,
  `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `action` VARCHAR(255) NOT NULL,
  `event` VARCHAR(255) NULL,
  `source` VARCHAR(255) NULL,
  `ref_id` INT NULL,
  `ref` VARCHAR(255) NULL,
  `note` TEXT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_user_log_user1_idx` (`user_id` ASC),
  INDEX `user_log_event1_idx` (`event` ASC),
  INDEX `user_log_source1_idx` (`source` ASC),
  INDEX `user_log_ref_id1_idx` (`ref_id` ASC),
  INDEX `user_log_ref1_idx` (`ref` ASC),
  INDEX `user_log_action1_idx` (`action` ASC),
  CONSTRAINT `fk_user_log_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `serlo_test`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo_test`.`taxonomy_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo_test`.`taxonomy_type` ;

CREATE TABLE IF NOT EXISTS `serlo_test`.`taxonomy_type` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `type_UNIQUE` (`name` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo_test`.`taxonomy`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo_test`.`taxonomy` ;

CREATE TABLE IF NOT EXISTS `serlo_test`.`taxonomy` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `taxonomy_type_id` INT UNSIGNED NOT NULL,
  `language_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_taxonomy_taxonomy_factory1_idx` (`taxonomy_type_id` ASC),
  INDEX `fk_taxonomy_language1_idx` (`language_id` ASC),
  CONSTRAINT `fk_taxonomy_taxonomy_factory1`
    FOREIGN KEY (`taxonomy_type_id`)
    REFERENCES `serlo_test`.`taxonomy_type` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_taxonomy_language1`
    FOREIGN KEY (`language_id`)
    REFERENCES `serlo_test`.`language` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo_test`.`permission`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo_test`.`permission` ;

CREATE TABLE IF NOT EXISTS `serlo_test`.`permission` (
  `id` INT UNSIGNED NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo_test`.`role_permission`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo_test`.`role_permission` ;

CREATE TABLE IF NOT EXISTS `serlo_test`.`role_permission` (
  `role_id` INT(11) UNSIGNED NOT NULL,
  `permission_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`, `permission_id`),
  INDEX `fk_role_has_permission_permission1_idx` (`permission_id` ASC),
  INDEX `fk_role_has_permission_role1_idx` (`role_id` ASC),
  CONSTRAINT `fk_role_has_permission_role1`
    FOREIGN KEY (`role_id`)
    REFERENCES `serlo_test`.`role` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_role_has_permission_permission1`
    FOREIGN KEY (`permission_id`)
    REFERENCES `serlo_test`.`permission` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `serlo_test`.`term`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo_test`.`term` ;

CREATE TABLE IF NOT EXISTS `serlo_test`.`term` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `language_id` INT UNSIGNED NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_term_language1_idx` (`language_id` ASC),
  UNIQUE INDEX `uq_term_name_language` (`name` ASC, `language_id` ASC),
  UNIQUE INDEX `uq_term_slug_language` (`slug` ASC, `language_id` ASC),
  CONSTRAINT `fk_term_language1`
    FOREIGN KEY (`language_id`)
    REFERENCES `serlo_test`.`language` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo_test`.`term_taxonomy`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo_test`.`term_taxonomy` ;

CREATE TABLE IF NOT EXISTS `serlo_test`.`term_taxonomy` (
  `id` BIGINT UNSIGNED NOT NULL,
  `taxonomy_id` INT UNSIGNED NOT NULL,
  `term_id` BIGINT UNSIGNED NOT NULL,
  `parent_id` BIGINT UNSIGNED NULL,
  `description` TEXT NULL,
  `weight` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_term_taxonomy_taxonomy1_idx` (`taxonomy_id` ASC),
  INDEX `fk_term_taxonomy_term1_idx` (`term_id` ASC),
  INDEX `fk_term_taxonomy_term_taxonomy1_idx` (`parent_id` ASC),
  INDEX `fk_term_taxonomy_uuid_idx` (`id` ASC),
  CONSTRAINT `fk_term_taxonomy_taxonomy1`
    FOREIGN KEY (`taxonomy_id`)
    REFERENCES `serlo_test`.`taxonomy` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_term_taxonomy_term1`
    FOREIGN KEY (`term_id`)
    REFERENCES `serlo_test`.`term` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_term_taxonomy_term_taxonomy1`
    FOREIGN KEY (`parent_id`)
    REFERENCES `serlo_test`.`term_taxonomy` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_term_taxonomy_uuid`
    FOREIGN KEY (`id`)
    REFERENCES `serlo_test`.`uuid` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo_test`.`term_taxonomy_entity`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo_test`.`term_taxonomy_entity` ;

CREATE TABLE IF NOT EXISTS `serlo_test`.`term_taxonomy_entity` (
  `entity_id` BIGINT UNSIGNED NOT NULL,
  `term_taxonomy_id` BIGINT UNSIGNED NOT NULL,
  `weight` INT NULL,
  PRIMARY KEY (`entity_id`, `term_taxonomy_id`),
  INDEX `fk_entity_has_term_taxonomy_term_taxonomy1_idx` (`term_taxonomy_id` ASC),
  INDEX `fk_entity_has_term_taxonomy_entity1_idx` (`entity_id` ASC),
  CONSTRAINT `fk_entity_has_term_taxonomy_entity1`
    FOREIGN KEY (`entity_id`)
    REFERENCES `serlo_test`.`entity` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_entity_has_term_taxonomy_term_taxonomy1`
    FOREIGN KEY (`term_taxonomy_id`)
    REFERENCES `serlo_test`.`term_taxonomy` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo_test`.`comment`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo_test`.`comment` ;

CREATE TABLE IF NOT EXISTS `serlo_test`.`comment` (
  `id` BIGINT UNSIGNED NOT NULL,
  `on_id` BIGINT UNSIGNED NULL,
  `author_id` INT(11) UNSIGNED NOT NULL,
  `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` VARCHAR(3) NOT NULL,
  `title` VARCHAR(255) NULL,
  `content` LONGTEXT NULL,
  INDEX `fk_comment_user1_idx` (`author_id` ASC),
  PRIMARY KEY (`id`),
  INDEX `fk_comment_uuid_idx` (`id` ASC),
  INDEX `fk_comment_uuid2_idx` (`on_id` ASC),
  CONSTRAINT `fk_comment_user1`
    FOREIGN KEY (`author_id`)
    REFERENCES `serlo_test`.`user` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_comment_uuid1`
    FOREIGN KEY (`id`)
    REFERENCES `serlo_test`.`uuid` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_comment_uuid2`
    FOREIGN KEY (`on_id`)
    REFERENCES `serlo_test`.`uuid` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo_test`.`term_taxonomy_comment`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo_test`.`term_taxonomy_comment` ;

CREATE TABLE IF NOT EXISTS `serlo_test`.`term_taxonomy_comment` (
  `comment_id` BIGINT UNSIGNED NOT NULL,
  `term_taxonomy_id` BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`comment_id`, `term_taxonomy_id`),
  INDEX `fk_comment_has_term_taxonomy_term_taxonomy1_idx` (`term_taxonomy_id` ASC),
  INDEX `fk_comment_has_term_taxonomy_comment1_idx` (`comment_id` ASC),
  CONSTRAINT `fk_comment_has_term_taxonomy_comment1`
    FOREIGN KEY (`comment_id`)
    REFERENCES `serlo_test`.`comment` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_comment_has_term_taxonomy_term_taxonomy1`
    FOREIGN KEY (`term_taxonomy_id`)
    REFERENCES `serlo_test`.`term_taxonomy` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Data for table `serlo_test`.`language`
-- -----------------------------------------------------
START TRANSACTION;
USE `serlo_test`;
INSERT INTO `serlo_test`.`language` (`id`, `name`, `code`, `dateformat`, `timeformat`) VALUES (1, 'Deutsch', 'de', 'DMY', 'Y:M');
INSERT INTO `serlo_test`.`language` (`id`, `name`, `code`, `dateformat`, `timeformat`) VALUES (2, 'English', 'en', 'MDY', 'Y:M');

COMMIT;


-- -----------------------------------------------------
-- Data for table `serlo_test`.`role`
-- -----------------------------------------------------
START TRANSACTION;
USE `serlo_test`;
INSERT INTO `serlo_test`.`role` (`id`, `name`, `parent_id`, `description`) VALUES (6, 'sysadmin', NULL, NULL);
INSERT INTO `serlo_test`.`role` (`id`, `name`, `parent_id`, `description`) VALUES (5, 'admin', 6, NULL);
INSERT INTO `serlo_test`.`role` (`id`, `name`, `parent_id`, `description`) VALUES (4, 'moderator', 5, NULL);
INSERT INTO `serlo_test`.`role` (`id`, `name`, `parent_id`, `description`) VALUES (3, 'helper', 4, NULL);
INSERT INTO `serlo_test`.`role` (`id`, `name`, `parent_id`, `description`) VALUES (2, 'login', 3, NULL);
INSERT INTO `serlo_test`.`role` (`id`, `name`, `parent_id`, `description`) VALUES (1, 'guest', 2, NULL);

COMMIT;


-- -----------------------------------------------------
-- Data for table `serlo_test`.`user`
-- -----------------------------------------------------
START TRANSACTION;
USE `serlo_test`;
INSERT INTO `serlo_test`.`user` (`id`, `email`, `username`, `password`, `logins`, `last_login`, `language_id`, `date`, `givenname`, `lastname`, `gender`, `ads_enabled`, `removed`) VALUES (1, 'aeneas@q-mail.me', 'arekkas', '37fe351ad34e2398b82f97295c3817ba02dd8e1d5777e8467a', 486, NULL, 1, NULL, NULL, NULL, 'n', 0, 0);
INSERT INTO `serlo_test`.`user` (`id`, `email`, `username`, `password`, `logins`, `last_login`, `language_id`, `date`, `givenname`, `lastname`, `gender`, `ads_enabled`, `removed`) VALUES (2, 'dev@serlo.org', 'devuser', '145f891a484034cfd94fde4db0b0ef69a23789569b26969351', 0, NULL, 1, NULL, NULL, NULL, 'n', 0, 0);

COMMIT;


-- -----------------------------------------------------
-- Data for table `serlo_test`.`license`
-- -----------------------------------------------------
START TRANSACTION;
USE `serlo_test`;
INSERT INTO `serlo_test`.`license` (`id`, `language_id`, `content`) VALUES (1, 1, 'DAMN DAT LICENSE <b>asdf</b>');
INSERT INTO `serlo_test`.`license` (`id`, `language_id`, `content`) VALUES (2, 1, 'OTHER LICENSE <b>asdfok</b>');

COMMIT;


-- -----------------------------------------------------
-- Data for table `serlo_test`.`entity_type`
-- -----------------------------------------------------
START TRANSACTION;
USE `serlo_test`;
INSERT INTO `serlo_test`.`entity_type` (`id`, `name`) VALUES (1, 'text-exercise');
INSERT INTO `serlo_test`.`entity_type` (`id`, `name`) VALUES (2, 'text-solution');
INSERT INTO `serlo_test`.`entity_type` (`id`, `name`) VALUES (3, 'article');
INSERT INTO `serlo_test`.`entity_type` (`id`, `name`) VALUES (4, 'exercise-group');
INSERT INTO `serlo_test`.`entity_type` (`id`, `name`) VALUES (5, 'grouped-text-exercise');

COMMIT;


-- -----------------------------------------------------
-- Data for table `serlo_test`.`entity_revision`
-- -----------------------------------------------------
START TRANSACTION;
USE `serlo_test`;
INSERT INTO `serlo_test`.`entity_revision` (`id`, `author_id`, `repository_id`, `date`, `trashed`) VALUES (1, 1, 1, NULL, FALSE);
INSERT INTO `serlo_test`.`entity_revision` (`id`, `author_id`, `repository_id`, `date`, `trashed`) VALUES (2, 1, 1, NULL, FALSE);
INSERT INTO `serlo_test`.`entity_revision` (`id`, `author_id`, `repository_id`, `date`, `trashed`) VALUES (3, 1, 1, NULL, FALSE);
INSERT INTO `serlo_test`.`entity_revision` (`id`, `author_id`, `repository_id`, `date`, `trashed`) VALUES (4, 1, 2, NULL, FALSE);
INSERT INTO `serlo_test`.`entity_revision` (`id`, `author_id`, `repository_id`, `date`, `trashed`) VALUES (5, 1, 2, NULL, FALSE);
INSERT INTO `serlo_test`.`entity_revision` (`id`, `author_id`, `repository_id`, `date`, `trashed`) VALUES (6, 1, 3, NULL, FALSE);

COMMIT;


-- -----------------------------------------------------
-- Data for table `serlo_test`.`uuid`
-- -----------------------------------------------------
START TRANSACTION;
USE `serlo_test`;
INSERT INTO `serlo_test`.`uuid` (`id`, `uuid`) VALUES (1, 'u1');
INSERT INTO `serlo_test`.`uuid` (`id`, `uuid`) VALUES (2, 'u2');
INSERT INTO `serlo_test`.`uuid` (`id`, `uuid`) VALUES (3, 'u3');
INSERT INTO `serlo_test`.`uuid` (`id`, `uuid`) VALUES (4, 'u4');
INSERT INTO `serlo_test`.`uuid` (`id`, `uuid`) VALUES (5, 'u5');
INSERT INTO `serlo_test`.`uuid` (`id`, `uuid`) VALUES (6, 'u6');
INSERT INTO `serlo_test`.`uuid` (`id`, `uuid`) VALUES (7, 'u7');
INSERT INTO `serlo_test`.`uuid` (`id`, `uuid`) VALUES (8, 'u8');
INSERT INTO `serlo_test`.`uuid` (`id`, `uuid`) VALUES (9, 'u9');
INSERT INTO `serlo_test`.`uuid` (`id`, `uuid`) VALUES (10, 'u10');
INSERT INTO `serlo_test`.`uuid` (`id`, `uuid`) VALUES (11, 'u11');
INSERT INTO `serlo_test`.`uuid` (`id`, `uuid`) VALUES (12, 'u12');
INSERT INTO `serlo_test`.`uuid` (`id`, `uuid`) VALUES (13, 'u13');
INSERT INTO `serlo_test`.`uuid` (`id`, `uuid`) VALUES (14, 'u14');
INSERT INTO `serlo_test`.`uuid` (`id`, `uuid`) VALUES (15, 'u15');
INSERT INTO `serlo_test`.`uuid` (`id`, `uuid`) VALUES (16, 'u16');
INSERT INTO `serlo_test`.`uuid` (`id`, `uuid`) VALUES (17, 'u17');
INSERT INTO `serlo_test`.`uuid` (`id`, `uuid`) VALUES (18, 'u18');
INSERT INTO `serlo_test`.`uuid` (`id`, `uuid`) VALUES (19, 'u19');
INSERT INTO `serlo_test`.`uuid` (`id`, `uuid`) VALUES (20, 'u20');
INSERT INTO `serlo_test`.`uuid` (`id`, `uuid`) VALUES (21, 'p21');
INSERT INTO `serlo_test`.`uuid` (`id`, `uuid`) VALUES (22, 'p22');
INSERT INTO `serlo_test`.`uuid` (`id`, `uuid`) VALUES (23, 'c23');
INSERT INTO `serlo_test`.`uuid` (`id`, `uuid`) VALUES (24, 'c24');
INSERT INTO `serlo_test`.`uuid` (`id`, `uuid`) VALUES (25, 'af23f');
INSERT INTO `serlo_test`.`uuid` (`id`, `uuid`) VALUES (26, 'a525t');
INSERT INTO `serlo_test`.`uuid` (`id`, `uuid`) VALUES (27, '234ft');
INSERT INTO `serlo_test`.`uuid` (`id`, `uuid`) VALUES (28, '78ja3');
INSERT INTO `serlo_test`.`uuid` (`id`, `uuid`) VALUES (29, 'sdf23');
INSERT INTO `serlo_test`.`uuid` (`id`, `uuid`) VALUES (30, '534rag');
INSERT INTO `serlo_test`.`uuid` (`id`, `uuid`) VALUES (31, '5645g');
INSERT INTO `serlo_test`.`uuid` (`id`, `uuid`) VALUES (32, '44444');
INSERT INTO `serlo_test`.`uuid` (`id`, `uuid`) VALUES (33, '7rfhr56z');
INSERT INTO `serlo_test`.`uuid` (`id`, `uuid`) VALUES (34, 'fghj56');
INSERT INTO `serlo_test`.`uuid` (`id`, `uuid`) VALUES (35, '47shs4');
INSERT INTO `serlo_test`.`uuid` (`id`, `uuid`) VALUES (36, '4labn4');
INSERT INTO `serlo_test`.`uuid` (`id`, `uuid`) VALUES (37, 'fkari');
INSERT INTO `serlo_test`.`uuid` (`id`, `uuid`) VALUES (38, '35usc');
INSERT INTO `serlo_test`.`uuid` (`id`, `uuid`) VALUES (39, '7fn4t');
INSERT INTO `serlo_test`.`uuid` (`id`, `uuid`) VALUES (40, 'aauu442z');
INSERT INTO `serlo_test`.`uuid` (`id`, `uuid`) VALUES (41, '136gui');

COMMIT;


-- -----------------------------------------------------
-- Data for table `serlo_test`.`entity`
-- -----------------------------------------------------
START TRANSACTION;
USE `serlo_test`;
INSERT INTO `serlo_test`.`entity` (`id`, `language_id`, `entity_type_id`, `date`, `trashed`, `current_revision_id`, `license_id`) VALUES (1, 1, 1, NULL, FALSE, 1, NULL);
INSERT INTO `serlo_test`.`entity` (`id`, `language_id`, `entity_type_id`, `date`, `trashed`, `current_revision_id`, `license_id`) VALUES (2, 1, 1, NULL, FALSE, 4, 1);
INSERT INTO `serlo_test`.`entity` (`id`, `language_id`, `entity_type_id`, `date`, `trashed`, `current_revision_id`, `license_id`) VALUES (3, 1, 2, NULL, FALSE, 5, NULL);

COMMIT;


-- -----------------------------------------------------
-- Data for table `serlo_test`.`entity_link_type`
-- -----------------------------------------------------
START TRANSACTION;
USE `serlo_test`;
INSERT INTO `serlo_test`.`entity_link_type` (`id`, `name`) VALUES (1, 'link');
INSERT INTO `serlo_test`.`entity_link_type` (`id`, `name`) VALUES (2, 'dependency');

COMMIT;


-- -----------------------------------------------------
-- Data for table `serlo_test`.`entity_link`
-- -----------------------------------------------------
START TRANSACTION;
USE `serlo_test`;
INSERT INTO `serlo_test`.`entity_link` (`id`, `entity_link_type_id`, `parent_id`, `child_id`, `order`) VALUES (1, 1, 2, 3, NULL);

COMMIT;


-- -----------------------------------------------------
-- Data for table `serlo_test`.`page_revision`
-- -----------------------------------------------------
START TRANSACTION;
USE `serlo_test`;
INSERT INTO `serlo_test`.`page_revision` (`id`, `author_id`, `page_repository`, `title`, `content`, `date`, `trashed`) VALUES (1, 1, 21, 'testpage', 'r1', NULL, FALSE);
INSERT INTO `serlo_test`.`page_revision` (`id`, `author_id`, `page_repository`, `title`, `content`, `date`, `trashed`) VALUES (2, 1, 22, 'othertest', 'r2', NULL, FALSE);
INSERT INTO `serlo_test`.`page_revision` (`id`, `author_id`, `page_repository`, `title`, `content`, `date`, `trashed`) VALUES (3, 1, 21, 'oldrevision', 'r3', NULL, FALSE);

COMMIT;


-- -----------------------------------------------------
-- Data for table `serlo_test`.`page`
-- -----------------------------------------------------
START TRANSACTION;
USE `serlo_test`;
INSERT INTO `serlo_test`.`page` (`id`, `language_id`, `slug`, `role_id`, `current_revision_id`) VALUES (21, 1, 'test-page', NULL, NULL);
INSERT INTO `serlo_test`.`page` (`id`, `language_id`, `slug`, `role_id`, `current_revision_id`) VALUES (22, 1, 'other', 5, NULL);

COMMIT;


-- -----------------------------------------------------
-- Data for table `serlo_test`.`blog_post`
-- -----------------------------------------------------
START TRANSACTION;
USE `serlo_test`;
INSERT INTO `serlo_test`.`blog_post` (`id`, `user_id`, `language_id`, `date`, `slug`, `sticky`, `title`, `content`, `publish`) VALUES (1, 1, 1, NULL, 'eintrag1', TRUE, 'erster blog', 'eintrag ist super', NULL);

COMMIT;


-- -----------------------------------------------------
-- Data for table `serlo_test`.`entity_revision_field`
-- -----------------------------------------------------
START TRANSACTION;
USE `serlo_test`;
INSERT INTO `serlo_test`.`entity_revision_field` (`id`, `field`, `entity_revision_id`, `value`) VALUES (1, 'content', 1, 'r1');
INSERT INTO `serlo_test`.`entity_revision_field` (`id`, `field`, `entity_revision_id`, `value`) VALUES (2, 'title', 1, 't1');
INSERT INTO `serlo_test`.`entity_revision_field` (`id`, `field`, `entity_revision_id`, `value`) VALUES (3, 'content', 2, 'r2');
INSERT INTO `serlo_test`.`entity_revision_field` (`id`, `field`, `entity_revision_id`, `value`) VALUES (4, 'title', 2, 't2');
INSERT INTO `serlo_test`.`entity_revision_field` (`id`, `field`, `entity_revision_id`, `value`) VALUES (5, 'content', 3, 'r3');
INSERT INTO `serlo_test`.`entity_revision_field` (`id`, `field`, `entity_revision_id`, `value`) VALUES (6, 'title', 3, 't3');
INSERT INTO `serlo_test`.`entity_revision_field` (`id`, `field`, `entity_revision_id`, `value`) VALUES (7, 'content', 4, 'rr1');
INSERT INTO `serlo_test`.`entity_revision_field` (`id`, `field`, `entity_revision_id`, `value`) VALUES (8, 'title', 4, 'tt1');
INSERT INTO `serlo_test`.`entity_revision_field` (`id`, `field`, `entity_revision_id`, `value`) VALUES (9, 'content', 5, 'rr2');
INSERT INTO `serlo_test`.`entity_revision_field` (`id`, `field`, `entity_revision_id`, `value`) VALUES (10, 'title', 5, 'tt2');
INSERT INTO `serlo_test`.`entity_revision_field` (`id`, `field`, `entity_revision_id`, `value`) VALUES (11, 'hint', 6, 'hr1');
INSERT INTO `serlo_test`.`entity_revision_field` (`id`, `field`, `entity_revision_id`, `value`) VALUES (12, 'content', 6, 'sr1');

COMMIT;


-- -----------------------------------------------------
-- Data for table `serlo_test`.`role_user`
-- -----------------------------------------------------
START TRANSACTION;
USE `serlo_test`;
INSERT INTO `serlo_test`.`role_user` (`id`, `user_id`, `role_id`, `language_id`) VALUES (1, 1, 2, NULL);
INSERT INTO `serlo_test`.`role_user` (`id`, `user_id`, `role_id`, `language_id`) VALUES (2, 1, 6, NULL);

COMMIT;


-- -----------------------------------------------------
-- Data for table `serlo_test`.`taxonomy_type`
-- -----------------------------------------------------
START TRANSACTION;
USE `serlo_test`;
INSERT INTO `serlo_test`.`taxonomy_type` (`id`, `name`) VALUES (1, 'topic');
INSERT INTO `serlo_test`.`taxonomy_type` (`id`, `name`) VALUES (2, 'topic-folder');
INSERT INTO `serlo_test`.`taxonomy_type` (`id`, `name`) VALUES (3, 'subject');
INSERT INTO `serlo_test`.`taxonomy_type` (`id`, `name`) VALUES (4, 'curriculum');
INSERT INTO `serlo_test`.`taxonomy_type` (`id`, `name`) VALUES (5, 'school-type');
INSERT INTO `serlo_test`.`taxonomy_type` (`id`, `name`) VALUES (6, 'curriculum-folder');
INSERT INTO `serlo_test`.`taxonomy_type` (`id`, `name`) VALUES (7, 'root');

COMMIT;


-- -----------------------------------------------------
-- Data for table `serlo_test`.`taxonomy`
-- -----------------------------------------------------
START TRANSACTION;
USE `serlo_test`;
INSERT INTO `serlo_test`.`taxonomy` (`id`, `taxonomy_type_id`, `language_id`) VALUES (1, 1, 1);
INSERT INTO `serlo_test`.`taxonomy` (`id`, `taxonomy_type_id`, `language_id`) VALUES (2, 2, 1);
INSERT INTO `serlo_test`.`taxonomy` (`id`, `taxonomy_type_id`, `language_id`) VALUES (3, 3, 1);
INSERT INTO `serlo_test`.`taxonomy` (`id`, `taxonomy_type_id`, `language_id`) VALUES (4, 1, 2);
INSERT INTO `serlo_test`.`taxonomy` (`id`, `taxonomy_type_id`, `language_id`) VALUES (5, 2, 2);
INSERT INTO `serlo_test`.`taxonomy` (`id`, `taxonomy_type_id`, `language_id`) VALUES (6, 3, 2);
INSERT INTO `serlo_test`.`taxonomy` (`id`, `taxonomy_type_id`, `language_id`) VALUES (7, 4, 1);
INSERT INTO `serlo_test`.`taxonomy` (`id`, `taxonomy_type_id`, `language_id`) VALUES (8, 5, 1);
INSERT INTO `serlo_test`.`taxonomy` (`id`, `taxonomy_type_id`, `language_id`) VALUES (9, 6, 1);
INSERT INTO `serlo_test`.`taxonomy` (`id`, `taxonomy_type_id`, `language_id`) VALUES (10, 7, 1);

COMMIT;


-- -----------------------------------------------------
-- Data for table `serlo_test`.`permission`
-- -----------------------------------------------------
START TRANSACTION;
USE `serlo_test`;
INSERT INTO `serlo_test`.`permission` (`id`, `name`) VALUES (1, 'view/entity/plugin/repository/compare-revision::checkout');
INSERT INTO `serlo_test`.`permission` (`id`, `name`) VALUES (2, 'view/entity/plugin/repository/compare-revision::trash');
INSERT INTO `serlo_test`.`permission` (`id`, `name`) VALUES (3, 'view/entity/plugin/repository/compare-revision::purge');

COMMIT;


-- -----------------------------------------------------
-- Data for table `serlo_test`.`role_permission`
-- -----------------------------------------------------
START TRANSACTION;
USE `serlo_test`;
INSERT INTO `serlo_test`.`role_permission` (`role_id`, `permission_id`) VALUES (3, 1);
INSERT INTO `serlo_test`.`role_permission` (`role_id`, `permission_id`) VALUES (3, 2);
INSERT INTO `serlo_test`.`role_permission` (`role_id`, `permission_id`) VALUES (4, 3);

COMMIT;


-- -----------------------------------------------------
-- Data for table `serlo_test`.`term`
-- -----------------------------------------------------
START TRANSACTION;
USE `serlo_test`;
INSERT INTO `serlo_test`.`term` (`id`, `language_id`, `name`, `slug`) VALUES (1, 1, 'Analysis', 'analysis');
INSERT INTO `serlo_test`.`term` (`id`, `language_id`, `name`, `slug`) VALUES (2, 1, 'Geometrie', 'geometrie');
INSERT INTO `serlo_test`.`term` (`id`, `language_id`, `name`, `slug`) VALUES (3, 1, 'Kurvendiskussion', 'kurvendiskussion');
INSERT INTO `serlo_test`.`term` (`id`, `language_id`, `name`, `slug`) VALUES (4, 1, 'Funktionen', 'funktionen');
INSERT INTO `serlo_test`.`term` (`id`, `language_id`, `name`, `slug`) VALUES (5, 1, 'Räumliche Figuren', 'raeumliche-figuren');
INSERT INTO `serlo_test`.`term` (`id`, `language_id`, `name`, `slug`) VALUES (6, 1, 'Quader', 'quader');
INSERT INTO `serlo_test`.`term` (`id`, `language_id`, `name`, `slug`) VALUES (7, 1, 'Kugel', 'kugel');
INSERT INTO `serlo_test`.`term` (`id`, `language_id`, `name`, `slug`) VALUES (8, 1, 'Einfache Aufgaben', 'einfache-aufgaben');
INSERT INTO `serlo_test`.`term` (`id`, `language_id`, `name`, `slug`) VALUES (9, 1, 'Normale Aufgaben', 'normale-aufgaben');
INSERT INTO `serlo_test`.`term` (`id`, `language_id`, `name`, `slug`) VALUES (10, 1, 'Schwere Aufgaben', 'schwere-aufgaben');
INSERT INTO `serlo_test`.`term` (`id`, `language_id`, `name`, `slug`) VALUES (11, 1, 'Mathe', 'mathe');
INSERT INTO `serlo_test`.`term` (`id`, `language_id`, `name`, `slug`) VALUES (12, 1, 'Physik', 'physik');
INSERT INTO `serlo_test`.`term` (`id`, `language_id`, `name`, `slug`) VALUES (13, 2, 'Math', 'math');
INSERT INTO `serlo_test`.`term` (`id`, `language_id`, `name`, `slug`) VALUES (14, 2, 'Physics', 'physics');
INSERT INTO `serlo_test`.`term` (`id`, `language_id`, `name`, `slug`) VALUES (15, 2, 'Algebra', 'algebra');
INSERT INTO `serlo_test`.`term` (`id`, `language_id`, `name`, `slug`) VALUES (16, 2, 'Arithmetic', 'arithmetic');
INSERT INTO `serlo_test`.`term` (`id`, `language_id`, `name`, `slug`) VALUES (17, 2, 'Linear equoations', 'linear-equoations');
INSERT INTO `serlo_test`.`term` (`id`, `language_id`, `name`, `slug`) VALUES (18, 2, 'One-dimensional Motion', 'one-dimensional-motion');
INSERT INTO `serlo_test`.`term` (`id`, `language_id`, `name`, `slug`) VALUES (19, 1, 'Eindimensionale Bewegung', 'eindimensionale-bewegung');
INSERT INTO `serlo_test`.`term` (`id`, `language_id`, `name`, `slug`) VALUES (20, 1, 'Deutschland', 'deutschland');
INSERT INTO `serlo_test`.`term` (`id`, `language_id`, `name`, `slug`) VALUES (21, 1, 'Bayern', 'bayern');
INSERT INTO `serlo_test`.`term` (`id`, `language_id`, `name`, `slug`) VALUES (22, 1, 'Sachsen', 'sachsen');
INSERT INTO `serlo_test`.`term` (`id`, `language_id`, `name`, `slug`) VALUES (23, 1, 'G8', 'g8');
INSERT INTO `serlo_test`.`term` (`id`, `language_id`, `name`, `slug`) VALUES (24, 1, 'Realschule', 'realschule');
INSERT INTO `serlo_test`.`term` (`id`, `language_id`, `name`, `slug`) VALUES (25, 1, '5. Klasse', '5-klasse');
INSERT INTO `serlo_test`.`term` (`id`, `language_id`, `name`, `slug`) VALUES (26, 1, '6. Klasse', '6-klasse');
INSERT INTO `serlo_test`.`term` (`id`, `language_id`, `name`, `slug`) VALUES (27, 1, '7. klasse', '7-klasse');
INSERT INTO `serlo_test`.`term` (`id`, `language_id`, `name`, `slug`) VALUES (28, 1, 'Rekapitulation', 'rekapitulation');
INSERT INTO `serlo_test`.`term` (`id`, `language_id`, `name`, `slug`) VALUES (29, 1, 'Root', 'root');

COMMIT;


-- -----------------------------------------------------
-- Data for table `serlo_test`.`term_taxonomy`
-- -----------------------------------------------------
START TRANSACTION;
USE `serlo_test`;
INSERT INTO `serlo_test`.`term_taxonomy` (`id`, `taxonomy_id`, `term_id`, `parent_id`, `description`, `weight`) VALUES (25, 3, 11, 43, NULL, NULL);
INSERT INTO `serlo_test`.`term_taxonomy` (`id`, `taxonomy_id`, `term_id`, `parent_id`, `description`, `weight`) VALUES (26, 3, 12, 43, NULL, NULL);
INSERT INTO `serlo_test`.`term_taxonomy` (`id`, `taxonomy_id`, `term_id`, `parent_id`, `description`, `weight`) VALUES (27, 6, 13, NULL, NULL, NULL);
INSERT INTO `serlo_test`.`term_taxonomy` (`id`, `taxonomy_id`, `term_id`, `parent_id`, `description`, `weight`) VALUES (28, 6, 14, NULL, NULL, NULL);
INSERT INTO `serlo_test`.`term_taxonomy` (`id`, `taxonomy_id`, `term_id`, `parent_id`, `description`, `weight`) VALUES (11, 1, 1, 25, NULL, NULL);
INSERT INTO `serlo_test`.`term_taxonomy` (`id`, `taxonomy_id`, `term_id`, `parent_id`, `description`, `weight`) VALUES (12, 1, 2, 26, NULL, NULL);
INSERT INTO `serlo_test`.`term_taxonomy` (`id`, `taxonomy_id`, `term_id`, `parent_id`, `description`, `weight`) VALUES (13, 2, 3, 11, NULL, NULL);
INSERT INTO `serlo_test`.`term_taxonomy` (`id`, `taxonomy_id`, `term_id`, `parent_id`, `description`, `weight`) VALUES (14, 2, 4, 11, NULL, NULL);
INSERT INTO `serlo_test`.`term_taxonomy` (`id`, `taxonomy_id`, `term_id`, `parent_id`, `description`, `weight`) VALUES (15, 2, 5, 12, NULL, NULL);
INSERT INTO `serlo_test`.`term_taxonomy` (`id`, `taxonomy_id`, `term_id`, `parent_id`, `description`, `weight`) VALUES (16, 2, 6, 12, NULL, NULL);
INSERT INTO `serlo_test`.`term_taxonomy` (`id`, `taxonomy_id`, `term_id`, `parent_id`, `description`, `weight`) VALUES (17, 2, 7, 12, NULL, NULL);
INSERT INTO `serlo_test`.`term_taxonomy` (`id`, `taxonomy_id`, `term_id`, `parent_id`, `description`, `weight`) VALUES (18, 2, 8, 17, NULL, NULL);
INSERT INTO `serlo_test`.`term_taxonomy` (`id`, `taxonomy_id`, `term_id`, `parent_id`, `description`, `weight`) VALUES (19, 2, 9, 17, NULL, NULL);
INSERT INTO `serlo_test`.`term_taxonomy` (`id`, `taxonomy_id`, `term_id`, `parent_id`, `description`, `weight`) VALUES (20, 2, 10, 17, NULL, NULL);
INSERT INTO `serlo_test`.`term_taxonomy` (`id`, `taxonomy_id`, `term_id`, `parent_id`, `description`, `weight`) VALUES (29, 4, 15, 27, NULL, NULL);
INSERT INTO `serlo_test`.`term_taxonomy` (`id`, `taxonomy_id`, `term_id`, `parent_id`, `description`, `weight`) VALUES (30, 4, 16, 27, NULL, NULL);
INSERT INTO `serlo_test`.`term_taxonomy` (`id`, `taxonomy_id`, `term_id`, `parent_id`, `description`, `weight`) VALUES (31, 5, 17, 30, NULL, NULL);
INSERT INTO `serlo_test`.`term_taxonomy` (`id`, `taxonomy_id`, `term_id`, `parent_id`, `description`, `weight`) VALUES (32, 4, 18, 28, NULL, NULL);
INSERT INTO `serlo_test`.`term_taxonomy` (`id`, `taxonomy_id`, `term_id`, `parent_id`, `description`, `weight`) VALUES (33, 1, 19, 26, NULL, NULL);
INSERT INTO `serlo_test`.`term_taxonomy` (`id`, `taxonomy_id`, `term_id`, `parent_id`, `description`, `weight`) VALUES (34, 8, 20, 25, NULL, NULL);
INSERT INTO `serlo_test`.`term_taxonomy` (`id`, `taxonomy_id`, `term_id`, `parent_id`, `description`, `weight`) VALUES (35, 8, 21, 34, NULL, NULL);
INSERT INTO `serlo_test`.`term_taxonomy` (`id`, `taxonomy_id`, `term_id`, `parent_id`, `description`, `weight`) VALUES (36, 8, 22, 34, NULL, NULL);
INSERT INTO `serlo_test`.`term_taxonomy` (`id`, `taxonomy_id`, `term_id`, `parent_id`, `description`, `weight`) VALUES (37, 8, 23, 35, NULL, NULL);
INSERT INTO `serlo_test`.`term_taxonomy` (`id`, `taxonomy_id`, `term_id`, `parent_id`, `description`, `weight`) VALUES (38, 8, 24, 36, NULL, NULL);
INSERT INTO `serlo_test`.`term_taxonomy` (`id`, `taxonomy_id`, `term_id`, `parent_id`, `description`, `weight`) VALUES (39, 7, 25, 37, NULL, NULL);
INSERT INTO `serlo_test`.`term_taxonomy` (`id`, `taxonomy_id`, `term_id`, `parent_id`, `description`, `weight`) VALUES (40, 7, 26, 37, NULL, NULL);
INSERT INTO `serlo_test`.`term_taxonomy` (`id`, `taxonomy_id`, `term_id`, `parent_id`, `description`, `weight`) VALUES (41, 7, 27, 38, NULL, NULL);
INSERT INTO `serlo_test`.`term_taxonomy` (`id`, `taxonomy_id`, `term_id`, `parent_id`, `description`, `weight`) VALUES (42, 9, 28, 39, NULL, NULL);
INSERT INTO `serlo_test`.`term_taxonomy` (`id`, `taxonomy_id`, `term_id`, `parent_id`, `description`, `weight`) VALUES (43, 10, 29, NULL, NULL, NULL);

COMMIT;


-- -----------------------------------------------------
-- Data for table `serlo_test`.`term_taxonomy_entity`
-- -----------------------------------------------------
START TRANSACTION;
USE `serlo_test`;
INSERT INTO `serlo_test`.`term_taxonomy_entity` (`entity_id`, `term_taxonomy_id`, `weight`) VALUES (1, 13, NULL);
INSERT INTO `serlo_test`.`term_taxonomy_entity` (`entity_id`, `term_taxonomy_id`, `weight`) VALUES (2, 14, NULL);
INSERT INTO `serlo_test`.`term_taxonomy_entity` (`entity_id`, `term_taxonomy_id`, `weight`) VALUES (1, 42, NULL);
INSERT INTO `serlo_test`.`term_taxonomy_entity` (`entity_id`, `term_taxonomy_id`, `weight`) VALUES (2, 42, NULL);

COMMIT;


-- -----------------------------------------------------
-- Data for table `serlo_test`.`comment`
-- -----------------------------------------------------
START TRANSACTION;
USE `serlo_test`;
INSERT INTO `serlo_test`.`comment` (`id`, `on_id`, `author_id`, `date`, `status`, `title`, `content`) VALUES (23, 21, 1, NULL, 'del', 'Ok', 'Das ist richtig');
INSERT INTO `serlo_test`.`comment` (`id`, `on_id`, `author_id`, `date`, `status`, `title`, `content`) VALUES (24, 22, 1, NULL, 'on', 'NEIN', 'Das doch falsch');

COMMIT;


-- -----------------------------------------------------
-- Data for table `serlo_test`.`term_taxonomy_comment`
-- -----------------------------------------------------
START TRANSACTION;
USE `serlo_test`;
INSERT INTO `serlo_test`.`term_taxonomy_comment` (`comment_id`, `term_taxonomy_id`) VALUES (24, 11);

COMMIT;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
