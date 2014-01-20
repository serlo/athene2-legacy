SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

DROP SCHEMA IF EXISTS `serlo` ;
CREATE SCHEMA IF NOT EXISTS `serlo` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `serlo` ;

-- -----------------------------------------------------
-- Table `serlo`.`permission`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo`.`permission` ;

CREATE TABLE IF NOT EXISTS `serlo`.`permission` (
  `id` INT NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo`.`language`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo`.`language` ;

CREATE TABLE IF NOT EXISTS `serlo`.`language` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `permission_id` INT NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `code` VARCHAR(2) NOT NULL,
  `dateformat` VARCHAR(45) NOT NULL,
  `timeformat` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC),
  UNIQUE INDEX `code_UNIQUE` (`code` ASC),
  INDEX `fk_language_permission1_idx` (`permission_id` ASC),
  CONSTRAINT `fk_language_permission1`
    FOREIGN KEY (`permission_id`)
    REFERENCES `serlo`.`permission` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo`.`role`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo`.`role` ;

CREATE TABLE IF NOT EXISTS `serlo`.`role` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(32) NOT NULL,
  `parent_id` INT(11) NULL,
  `description` VARCHAR(255) NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `uniq_name` (`name` ASC),
  INDEX `fk_role_role1_idx` (`parent_id` ASC),
  CONSTRAINT `fk_role_role1`
    FOREIGN KEY (`parent_id`)
    REFERENCES `serlo`.`role` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo`.`uuid`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo`.`uuid` ;

CREATE TABLE IF NOT EXISTS `serlo`.`uuid` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `uuid` VARCHAR(30) NOT NULL,
  `trashed` TINYINT(1) NOT NULL DEFAULT FALSE,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `uuid_UNIQUE` (`uuid` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo`.`user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo`.`user` ;

CREATE TABLE IF NOT EXISTS `serlo`.`user` (
  `id` BIGINT NOT NULL,
  `email` VARCHAR(127) NOT NULL,
  `username` VARCHAR(32) NOT NULL DEFAULT '',
  `password` CHAR(50) NOT NULL,
  `logins` INT(10) NOT NULL DEFAULT '0',
  `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ads_enabled` TINYINT(1) NOT NULL DEFAULT 0,
  `token` VARCHAR(32) NOT NULL,
  `last_login` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `uniq_username` (`username` ASC),
  UNIQUE INDEX `uniq_email` (`email` ASC),
  INDEX `fk_user_uuid1_idx` (`id` ASC),
  UNIQUE INDEX `token_UNIQUE` (`token` ASC),
  CONSTRAINT `fk_user_uuid1`
    FOREIGN KEY (`id`)
    REFERENCES `serlo`.`uuid` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo`.`user_token`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo`.`user_token` ;

CREATE TABLE IF NOT EXISTS `serlo`.`user_token` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `user_agent` VARCHAR(40) NOT NULL,
  `token` VARCHAR(32) NOT NULL,
  `created` INT(10) NOT NULL,
  `expires` INT(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `uniq_token` (`token` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `serlo`.`type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo`.`type` ;

CREATE TABLE IF NOT EXISTS `serlo`.`type` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `className_UNIQUE` (`name` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo`.`license`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo`.`license` ;

CREATE TABLE IF NOT EXISTS `serlo`.`license` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `language_id` INT NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `url` VARCHAR(255) NOT NULL,
  `content` TEXT NULL,
  `icon_href` VARCHAR(255) NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_license_language1_idx` (`language_id` ASC),
  UNIQUE INDEX `title_UNIQUE` (`title` ASC, `language_id` ASC),
  CONSTRAINT `fk_license_language1`
    FOREIGN KEY (`language_id`)
    REFERENCES `serlo`.`language` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo`.`entity`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo`.`entity` ;

CREATE TABLE IF NOT EXISTS `serlo`.`entity` (
  `id` BIGINT NOT NULL,
  `type_id` INT NOT NULL,
  `language_id` INT NOT NULL,
  `license_id` INT NOT NULL,
  `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `current_revision_id` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_entity_language1_idx` (`language_id` ASC),
  INDEX `fk_entity_entity_factory1_idx` (`type_id` ASC),
  INDEX `fk_entity_uuid_idx` (`id` ASC),
  INDEX `fk_entity_license1_idx` (`license_id` ASC),
  CONSTRAINT `fk_entity_language1`
    FOREIGN KEY (`language_id`)
    REFERENCES `serlo`.`language` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_entity_entity_factory1`
    FOREIGN KEY (`type_id`)
    REFERENCES `serlo`.`type` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_entity_uuid`
    FOREIGN KEY (`id`)
    REFERENCES `serlo`.`uuid` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_entity_license1`
    FOREIGN KEY (`license_id`)
    REFERENCES `serlo`.`license` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo`.`entity_link`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo`.`entity_link` ;

CREATE TABLE IF NOT EXISTS `serlo`.`entity_link` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `parent_id` BIGINT NOT NULL,
  `child_id` BIGINT NOT NULL,
  `type_id` INT NOT NULL,
  `order` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_entity_link_entity1_idx` (`parent_id` ASC),
  INDEX `fk_entity_link_entity2_idx` (`child_id` ASC),
  UNIQUE INDEX `uq_entity_link` (`parent_id` ASC, `child_id` ASC),
  INDEX `fk_entity_link_type1_idx` (`type_id` ASC),
  CONSTRAINT `fk_entity_link_entity1`
    FOREIGN KEY (`parent_id`)
    REFERENCES `serlo`.`entity` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_entity_link_entity2`
    FOREIGN KEY (`child_id`)
    REFERENCES `serlo`.`entity` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_entity_link_type1`
    FOREIGN KEY (`type_id`)
    REFERENCES `serlo`.`type` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo`.`page_repository`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo`.`page_repository` ;

CREATE TABLE IF NOT EXISTS `serlo`.`page_repository` (
  `id` BIGINT NOT NULL,
  `language_id` INT NOT NULL,
  `license_id` INT NOT NULL DEFAULT 1,
  `current_revision_id` INT NULL,
  INDEX `fk_page_repository_uuid2_idx` (`id` ASC),
  INDEX `fk_page_repository_language1_idx` (`language_id` ASC),
  PRIMARY KEY (`id`),
  INDEX `fk_page_repository_license1_idx` (`license_id` ASC),
  CONSTRAINT `fk_page_repository_uuid2`
    FOREIGN KEY (`id`)
    REFERENCES `serlo`.`uuid` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_page_repository_language1`
    FOREIGN KEY (`language_id`)
    REFERENCES `serlo`.`language` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_page_repository_license1`
    FOREIGN KEY (`license_id`)
    REFERENCES `serlo`.`license` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo`.`page_revision`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo`.`page_revision` ;

CREATE TABLE IF NOT EXISTS `serlo`.`page_revision` (
  `id` BIGINT NOT NULL,
  `author_id` BIGINT NOT NULL,
  `page_repository_id` BIGINT NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `content` LONGTEXT NOT NULL,
  `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `trashed` TINYINT(1) NOT NULL DEFAULT FALSE,
  PRIMARY KEY (`id`),
  INDEX `fk_page_revision_page_repository1_idx` (`page_repository_id` ASC),
  INDEX `fk_page_revision_user1_idx` (`author_id` ASC),
  INDEX `fk_page_revision_uuid1_idx` (`id` ASC),
  CONSTRAINT `fk_page_revision_page_repository1`
    FOREIGN KEY (`page_repository_id`)
    REFERENCES `serlo`.`page_repository` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_page_revision_user1`
    FOREIGN KEY (`author_id`)
    REFERENCES `serlo`.`user` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_page_revision_uuid1`
    FOREIGN KEY (`id`)
    REFERENCES `serlo`.`uuid` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo`.`taxonomy`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo`.`taxonomy` ;

CREATE TABLE IF NOT EXISTS `serlo`.`taxonomy` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `type_id` INT NOT NULL,
  `language_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_taxonomy_language1_idx` (`language_id` ASC),
  INDEX `fk_taxonomy_type1_idx` (`type_id` ASC),
  CONSTRAINT `fk_taxonomy_language1`
    FOREIGN KEY (`language_id`)
    REFERENCES `serlo`.`language` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_taxonomy_type1`
    FOREIGN KEY (`type_id`)
    REFERENCES `serlo`.`type` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo`.`term`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo`.`term` ;

CREATE TABLE IF NOT EXISTS `serlo`.`term` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `language_id` INT NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `uq_term_name_language` (`name` ASC),
  UNIQUE INDEX `uq_term_slug_language` (`slug` ASC),
  INDEX `fk_term_language1_idx` (`language_id` ASC),
  CONSTRAINT `fk_term_language1`
    FOREIGN KEY (`language_id`)
    REFERENCES `serlo`.`language` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo`.`term_taxonomy`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo`.`term_taxonomy` ;

CREATE TABLE IF NOT EXISTS `serlo`.`term_taxonomy` (
  `id` BIGINT NOT NULL,
  `taxonomy_id` INT NOT NULL,
  `term_id` BIGINT NOT NULL,
  `parent_id` BIGINT NULL DEFAULT NULL,
  `description` TEXT NULL DEFAULT NULL,
  `weight` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_term_taxonomy_taxonomy1_idx` (`taxonomy_id` ASC),
  INDEX `fk_term_taxonomy_term1_idx` (`term_id` ASC),
  INDEX `fk_term_taxonomy_term_taxonomy1_idx` (`parent_id` ASC),
  INDEX `fk_term_taxonomy_uuid_idx` (`id` ASC),
  CONSTRAINT `fk_term_taxonomy_taxonomy1`
    FOREIGN KEY (`taxonomy_id`)
    REFERENCES `serlo`.`taxonomy` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_term_taxonomy_term1`
    FOREIGN KEY (`term_id`)
    REFERENCES `serlo`.`term` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_term_taxonomy_term_taxonomy1`
    FOREIGN KEY (`parent_id`)
    REFERENCES `serlo`.`term_taxonomy` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_term_taxonomy_uuid`
    FOREIGN KEY (`id`)
    REFERENCES `serlo`.`uuid` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo`.`blog_post`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo`.`blog_post` ;

CREATE TABLE IF NOT EXISTS `serlo`.`blog_post` (
  `id` BIGINT NOT NULL,
  `author_id` BIGINT NOT NULL,
  `category_id` BIGINT NOT NULL,
  `language_id` INT NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `content` LONGTEXT NOT NULL,
  `publish` TIMESTAMP NULL DEFAULT NULL,
  INDEX `fk_blog_post_user1_idx` (`author_id` ASC),
  INDEX `fk_blog_post_term_taxonomy1_idx` (`category_id` ASC),
  INDEX `fk_blog_post_uuid1_idx` (`id` ASC),
  PRIMARY KEY (`id`),
  INDEX `fk_blog_post_language1_idx` (`language_id` ASC),
  CONSTRAINT `fk_blog_post_user1`
    FOREIGN KEY (`author_id`)
    REFERENCES `serlo`.`user` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_blog_post_term_taxonomy1`
    FOREIGN KEY (`category_id`)
    REFERENCES `serlo`.`term_taxonomy` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_blog_post_uuid1`
    FOREIGN KEY (`id`)
    REFERENCES `serlo`.`uuid` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_blog_post_language1`
    FOREIGN KEY (`language_id`)
    REFERENCES `serlo`.`language` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo`.`entity_revision`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo`.`entity_revision` ;

CREATE TABLE IF NOT EXISTS `serlo`.`entity_revision` (
  `id` BIGINT NOT NULL,
  `author_id` BIGINT NOT NULL,
  `repository_id` BIGINT NOT NULL,
  `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `fk_revision_entity1_idx` (`repository_id` ASC),
  INDEX `fk_entity_revision_user2_idx` (`author_id` ASC),
  INDEX `fk_entity_revision_uuid1_idx` (`id` ASC),
  CONSTRAINT `fk_revision_entity1`
    FOREIGN KEY (`repository_id`)
    REFERENCES `serlo`.`entity` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_entity_revision_user2`
    FOREIGN KEY (`author_id`)
    REFERENCES `serlo`.`user` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_entity_revision_uuid1`
    FOREIGN KEY (`id`)
    REFERENCES `serlo`.`uuid` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo`.`entity_revision_field`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo`.`entity_revision_field` ;

CREATE TABLE IF NOT EXISTS `serlo`.`entity_revision_field` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `field` VARCHAR(255) NOT NULL,
  `entity_revision_id` BIGINT NOT NULL,
  `value` LONGTEXT NOT NULL,
  PRIMARY KEY (`id`, `field`),
  INDEX `fk_entity_revision_field_entity_revision1_idx` (`entity_revision_id` ASC),
  CONSTRAINT `fk_entity_revision_field_entity_revision1`
    FOREIGN KEY (`entity_revision_id`)
    REFERENCES `serlo`.`entity_revision` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo`.`role_user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo`.`role_user` ;

CREATE TABLE IF NOT EXISTS `serlo`.`role_user` (
  `user_id` BIGINT NOT NULL,
  `role_id` INT(11) NOT NULL,
  PRIMARY KEY (`role_id`, `user_id`),
  INDEX `fk_role_user_role1_idx` (`role_id` ASC),
  INDEX `fk_role_user_user1_idx` (`user_id` ASC),
  UNIQUE INDEX `user_id_UNIQUE` (`user_id` ASC, `role_id` ASC),
  CONSTRAINT `fk_role_user_role1`
    FOREIGN KEY (`role_id`)
    REFERENCES `serlo`.`role` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_role_user_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `serlo`.`user` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo`.`role_permission`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo`.`role_permission` ;

CREATE TABLE IF NOT EXISTS `serlo`.`role_permission` (
  `role_id` INT(11) NOT NULL,
  `permission_id` INT NOT NULL,
  PRIMARY KEY (`role_id`, `permission_id`),
  INDEX `fk_role_has_permission_permission1_idx` (`permission_id` ASC),
  INDEX `fk_role_has_permission_role1_idx` (`role_id` ASC),
  CONSTRAINT `fk_role_has_permission_role1`
    FOREIGN KEY (`role_id`)
    REFERENCES `serlo`.`role` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_role_has_permission_permission1`
    FOREIGN KEY (`permission_id`)
    REFERENCES `serlo`.`permission` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo`.`term_taxonomy_entity`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo`.`term_taxonomy_entity` ;

CREATE TABLE IF NOT EXISTS `serlo`.`term_taxonomy_entity` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `entity_id` BIGINT NOT NULL,
  `term_taxonomy_id` BIGINT NOT NULL,
  `position` SMALLINT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  INDEX `fk_entity_has_term_taxonomy_term_taxonomy1_idx` (`term_taxonomy_id` ASC),
  INDEX `fk_entity_has_term_taxonomy_entity1_idx` (`entity_id` ASC),
  CONSTRAINT `fk_entity_has_term_taxonomy_entity1`
    FOREIGN KEY (`entity_id`)
    REFERENCES `serlo`.`entity` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_entity_has_term_taxonomy_term_taxonomy1`
    FOREIGN KEY (`term_taxonomy_id`)
    REFERENCES `serlo`.`term_taxonomy` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo`.`comment`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo`.`comment` ;

CREATE TABLE IF NOT EXISTS `serlo`.`comment` (
  `id` BIGINT NOT NULL,
  `language_id` INT NOT NULL,
  `author_id` BIGINT NOT NULL,
  `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `archived` TINYINT(1) NOT NULL DEFAULT FALSE,
  `uuid_id` BIGINT NULL,
  `parent_id` BIGINT NULL,
  `title` VARCHAR(255) NULL,
  `content` LONGTEXT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_comment_uuid_idx` (`id` ASC),
  INDEX `fk_comment_language1_idx` (`language_id` ASC),
  INDEX `fk_comment_uuid2_idx` (`uuid_id` ASC),
  INDEX `fk_comment_user1_idx` (`author_id` ASC),
  INDEX `fk_comment_comment1_idx` (`parent_id` ASC),
  CONSTRAINT `fk_comment_uuid1`
    FOREIGN KEY (`id`)
    REFERENCES `serlo`.`uuid` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_comment_language1`
    FOREIGN KEY (`language_id`)
    REFERENCES `serlo`.`language` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_comment_uuid2`
    FOREIGN KEY (`uuid_id`)
    REFERENCES `serlo`.`uuid` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_comment_user1`
    FOREIGN KEY (`author_id`)
    REFERENCES `serlo`.`user` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_comment_comment1`
    FOREIGN KEY (`parent_id`)
    REFERENCES `serlo`.`comment` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo`.`page_repository_role`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo`.`page_repository_role` ;

CREATE TABLE IF NOT EXISTS `serlo`.`page_repository_role` (
  `page_repository_id` BIGINT NOT NULL,
  `role_id` INT(11) NOT NULL,
  PRIMARY KEY (`page_repository_id`, `role_id`),
  INDEX `fk_page_repository_has_role_role2_idx` (`role_id` ASC),
  INDEX `fk_page_repository_has_role_page_repository1_idx` (`page_repository_id` ASC),
  CONSTRAINT `fk_page_repository_has_role_page_repository1`
    FOREIGN KEY (`page_repository_id`)
    REFERENCES `serlo`.`page_repository` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_page_repository_has_role_role2`
    FOREIGN KEY (`role_id`)
    REFERENCES `serlo`.`role` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo`.`event`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo`.`event` ;

CREATE TABLE IF NOT EXISTS `serlo`.`event` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo`.`event_log`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo`.`event_log` ;

CREATE TABLE IF NOT EXISTS `serlo`.`event_log` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `actor_id` BIGINT NOT NULL,
  `event_id` INT NOT NULL,
  `uuid_id` BIGINT NOT NULL,
  `language_id` INT NOT NULL,
  `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `fk_event_fired_event1_idx` (`event_id` ASC),
  INDEX `fk_event_log_uuid1_idx` (`uuid_id` ASC),
  INDEX `fk_event_log_language1_idx` (`language_id` ASC),
  INDEX `fk_event_log_user1_idx` (`actor_id` ASC),
  CONSTRAINT `fk_event_fired_event1`
    FOREIGN KEY (`event_id`)
    REFERENCES `serlo`.`event` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_event_log_uuid1`
    FOREIGN KEY (`uuid_id`)
    REFERENCES `serlo`.`uuid` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_event_log_language1`
    FOREIGN KEY (`language_id`)
    REFERENCES `serlo`.`language` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_event_log_user1`
    FOREIGN KEY (`actor_id`)
    REFERENCES `serlo`.`user` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo`.`notification`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo`.`notification` ;

CREATE TABLE IF NOT EXISTS `serlo`.`notification` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT NOT NULL,
  `seen` TINYINT(1) NOT NULL DEFAULT FALSE,
  `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `fk_notification_user1_idx` (`user_id` ASC),
  CONSTRAINT `fk_notification_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `serlo`.`user` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo`.`subscription`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo`.`subscription` ;

CREATE TABLE IF NOT EXISTS `serlo`.`subscription` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `uuid_id` BIGINT NOT NULL,
  `user_id` BIGINT NOT NULL,
  `notify_mailman` TINYINT(1) NOT NULL DEFAULT FALSE,
  PRIMARY KEY (`id`),
  INDEX `fk_subscription_uuid1_idx` (`uuid_id` ASC),
  INDEX `fk_subscription_user1_idx` (`user_id` ASC),
  UNIQUE INDEX `uuid_id_UNIQUE` (`uuid_id` ASC, `user_id` ASC),
  CONSTRAINT `fk_subscription_uuid1`
    FOREIGN KEY (`uuid_id`)
    REFERENCES `serlo`.`uuid` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_subscription_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `serlo`.`user` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo`.`url_alias`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo`.`url_alias` ;

CREATE TABLE IF NOT EXISTS `serlo`.`url_alias` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `language_id` INT NOT NULL,
  `uuid_id` BIGINT NOT NULL,
  `source` VARCHAR(255) NOT NULL,
  `alias` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `alias_UNIQUE` (`alias` ASC),
  INDEX `fk_url_alias_language1_idx` (`language_id` ASC),
  INDEX `fk_url_alias_uuid1_idx` (`uuid_id` ASC),
  CONSTRAINT `fk_url_alias_language1`
    FOREIGN KEY (`language_id`)
    REFERENCES `serlo`.`language` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_url_alias_uuid1`
    FOREIGN KEY (`uuid_id`)
    REFERENCES `serlo`.`uuid` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo`.`comment_vote`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo`.`comment_vote` ;

CREATE TABLE IF NOT EXISTS `serlo`.`comment_vote` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `comment_id` BIGINT NOT NULL,
  `user_id` BIGINT NOT NULL,
  `vote` TINYINT NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  INDEX `fk_comment_has_user_user1_idx` (`user_id` ASC),
  INDEX `fk_comment_has_user_comment1_idx` (`comment_id` ASC),
  UNIQUE INDEX `comment_id_UNIQUE` (`comment_id` ASC, `user_id` ASC),
  CONSTRAINT `fk_comment_has_user_comment1`
    FOREIGN KEY (`comment_id`)
    REFERENCES `serlo`.`comment` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_comment_has_user_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `serlo`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo`.`term_taxonomy_comment`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo`.`term_taxonomy_comment` ;

CREATE TABLE IF NOT EXISTS `serlo`.`term_taxonomy_comment` (
  `comment_id` BIGINT NOT NULL,
  `term_taxonomy_id` BIGINT NOT NULL,
  PRIMARY KEY (`comment_id`, `term_taxonomy_id`),
  INDEX `fk_comment_has_term_taxonomy_term_taxonomy2_idx` (`term_taxonomy_id` ASC),
  INDEX `fk_comment_has_term_taxonomy_comment2_idx` (`comment_id` ASC),
  CONSTRAINT `fk_comment_has_term_taxonomy_comment2`
    FOREIGN KEY (`comment_id`)
    REFERENCES `serlo`.`comment` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_comment_has_term_taxonomy_term_taxonomy2`
    FOREIGN KEY (`term_taxonomy_id`)
    REFERENCES `serlo`.`term_taxonomy` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo`.`attachment`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo`.`attachment` ;

CREATE TABLE IF NOT EXISTS `serlo`.`attachment` (
  `id` BIGINT NOT NULL,
  `language_id` INT NOT NULL,
  INDEX `fk_upload_uuid1_idx` (`id` ASC),
  PRIMARY KEY (`id`),
  INDEX `fk_upload_language1_idx` (`language_id` ASC),
  CONSTRAINT `fk_upload_uuid1`
    FOREIGN KEY (`id`)
    REFERENCES `serlo`.`uuid` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_upload_language1`
    FOREIGN KEY (`language_id`)
    REFERENCES `serlo`.`language` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo`.`related_content_container`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo`.`related_content_container` ;

CREATE TABLE IF NOT EXISTS `serlo`.`related_content_container` (
  `id` BIGINT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_related_uuid1_idx` (`id` ASC),
  CONSTRAINT `fk_related_uuid1`
    FOREIGN KEY (`id`)
    REFERENCES `serlo`.`uuid` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo`.`related_content`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo`.`related_content` ;

CREATE TABLE IF NOT EXISTS `serlo`.`related_content` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `container_id` BIGINT NOT NULL,
  `position` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  INDEX `fk_related_content_related_content_container1_idx` (`container_id` ASC),
  CONSTRAINT `fk_related_content_related_content_container1`
    FOREIGN KEY (`container_id`)
    REFERENCES `serlo`.`related_content_container` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo`.`related_content_internal`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo`.`related_content_internal` ;

CREATE TABLE IF NOT EXISTS `serlo`.`related_content_internal` (
  `id` INT NOT NULL,
  `reference_id` BIGINT NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_related_internal_uuid1_idx` (`reference_id` ASC),
  INDEX `fk_related_content_internal_related_content1_idx` (`id` ASC),
  CONSTRAINT `fk_related_internal_uuid1`
    FOREIGN KEY (`reference_id`)
    REFERENCES `serlo`.`uuid` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_related_content_internal_related_content1`
    FOREIGN KEY (`id`)
    REFERENCES `serlo`.`related_content` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo`.`related_content_external`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo`.`related_content_external` ;

CREATE TABLE IF NOT EXISTS `serlo`.`related_content_external` (
  `id` INT NOT NULL,
  `url` VARCHAR(255) NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_related_content_external_related_content1_idx` (`id` ASC),
  CONSTRAINT `fk_related_content_external_related_content1`
    FOREIGN KEY (`id`)
    REFERENCES `serlo`.`related_content` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo`.`related_content_category`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo`.`related_content_category` ;

CREATE TABLE IF NOT EXISTS `serlo`.`related_content_category` (
  `id` INT NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_related_content_category_related_content1_idx` (`id` ASC),
  CONSTRAINT `fk_related_content_category_related_content1`
    FOREIGN KEY (`id`)
    REFERENCES `serlo`.`related_content` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo`.`context`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo`.`context` ;

CREATE TABLE IF NOT EXISTS `serlo`.`context` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `uuid_id` BIGINT NOT NULL,
  `type_id` INT NOT NULL,
  `language_id` INT NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_context_uuid1_idx` (`uuid_id` ASC),
  INDEX `fk_context_type1_idx` (`type_id` ASC),
  INDEX `fk_context_language1_idx` (`language_id` ASC),
  CONSTRAINT `fk_context_uuid1`
    FOREIGN KEY (`uuid_id`)
    REFERENCES `serlo`.`uuid` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_context_type1`
    FOREIGN KEY (`type_id`)
    REFERENCES `serlo`.`type` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_context_language1`
    FOREIGN KEY (`language_id`)
    REFERENCES `serlo`.`language` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo`.`context_route`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo`.`context_route` ;

CREATE TABLE IF NOT EXISTS `serlo`.`context_route` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `context_id` INT NOT NULL,
  `route_name` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_context_route_context1_idx` (`context_id` ASC),
  CONSTRAINT `fk_context_route_context1`
    FOREIGN KEY (`context_id`)
    REFERENCES `serlo`.`context` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo`.`context_route_parameter`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo`.`context_route_parameter` ;

CREATE TABLE IF NOT EXISTS `serlo`.`context_route_parameter` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `context_route_id` INT NOT NULL,
  `key` VARCHAR(255) NOT NULL,
  `value` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_context_route_parameter_context_route1_idx` (`context_route_id` ASC),
  CONSTRAINT `fk_context_route_parameter_context_route1`
    FOREIGN KEY (`context_route_id`)
    REFERENCES `serlo`.`context_route` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo`.`flag`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo`.`flag` ;

CREATE TABLE IF NOT EXISTS `serlo`.`flag` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `uuid_id` BIGINT NOT NULL,
  `type_id` INT NOT NULL,
  `reporter_id` BIGINT NOT NULL,
  `language_id` INT NOT NULL,
  `content` TEXT NOT NULL,
  `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `fk_Flag_uuid1_idx` (`uuid_id` ASC),
  INDEX `fk_flag_user1_idx` (`reporter_id` ASC),
  INDEX `fk_flag_type1_idx` (`type_id` ASC),
  INDEX `fk_flag_language1_idx` (`language_id` ASC),
  CONSTRAINT `fk_Flag_uuid1`
    FOREIGN KEY (`uuid_id`)
    REFERENCES `serlo`.`uuid` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_flag_user1`
    FOREIGN KEY (`reporter_id`)
    REFERENCES `serlo`.`user` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_flag_type1`
    FOREIGN KEY (`type_id`)
    REFERENCES `serlo`.`type` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_flag_language1`
    FOREIGN KEY (`language_id`)
    REFERENCES `serlo`.`language` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo`.`event_parameter_name`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo`.`event_parameter_name` ;

CREATE TABLE IF NOT EXISTS `serlo`.`event_parameter_name` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo`.`event_parameter`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo`.`event_parameter` ;

CREATE TABLE IF NOT EXISTS `serlo`.`event_parameter` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `log_id` BIGINT NOT NULL,
  `name_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_event_parameter_event_log1_idx` (`log_id` ASC),
  INDEX `fk_event_parameter_event_parameter_name1_idx` (`name_id` ASC),
  UNIQUE INDEX `name_id_UNIQUE` (`name_id` ASC, `log_id` ASC),
  CONSTRAINT `fk_event_parameter_event_log1`
    FOREIGN KEY (`log_id`)
    REFERENCES `serlo`.`event_log` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_event_parameter_event_parameter_name1`
    FOREIGN KEY (`name_id`)
    REFERENCES `serlo`.`event_parameter_name` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo`.`notification_event`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo`.`notification_event` ;

CREATE TABLE IF NOT EXISTS `serlo`.`notification_event` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `notification_id` INT NOT NULL,
  `event_log_id` BIGINT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_notification_event_notification1_idx` (`notification_id` ASC),
  INDEX `fk_notification_event_event_log1_idx` (`event_log_id` ASC),
  CONSTRAINT `fk_notification_event_notification1`
    FOREIGN KEY (`notification_id`)
    REFERENCES `serlo`.`notification` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_notification_event_event_log1`
    FOREIGN KEY (`event_log_id`)
    REFERENCES `serlo`.`event_log` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo`.`metadata_key`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo`.`metadata_key` ;

CREATE TABLE IF NOT EXISTS `serlo`.`metadata_key` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `key_UNIQUE` (`name` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo`.`metadata`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo`.`metadata` ;

CREATE TABLE IF NOT EXISTS `serlo`.`metadata` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `uuid_id` BIGINT NOT NULL,
  `key_id` INT NOT NULL,
  `value` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_metadata_uuid1_idx` (`uuid_id` ASC),
  INDEX `fk_metadata_metadata_key1_idx` (`key_id` ASC),
  CONSTRAINT `fk_metadata_uuid1`
    FOREIGN KEY (`uuid_id`)
    REFERENCES `serlo`.`uuid` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_metadata_metadata_key1`
    FOREIGN KEY (`key_id`)
    REFERENCES `serlo`.`metadata_key` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo`.`html_cache`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo`.`html_cache` ;

CREATE TABLE IF NOT EXISTS `serlo`.`html_cache` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `guid` VARCHAR(255) NOT NULL,
  `content` LONGTEXT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `html_cache_guid` (`guid` ASC),
  UNIQUE INDEX `guid_UNIQUE` (`guid` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo`.`ads`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo`.`ads` ;

CREATE TABLE IF NOT EXISTS `serlo`.`ads` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `language_id` INT NOT NULL,
  `image_id` BIGINT NOT NULL,
  `author_id` BIGINT NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `content` TEXT NOT NULL,
  `frequency` FLOAT NOT NULL DEFAULT 1,
  `clicks` INT NOT NULL DEFAULT 0,
  `views` INT NOT NULL DEFAULT 0,
  `url` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_ads_language1_idx` (`language_id` ASC),
  INDEX `fk_ads_upload1_idx` (`image_id` ASC),
  INDEX `fk_ads_user1_idx` (`author_id` ASC),
  CONSTRAINT `fk_ads_language1`
    FOREIGN KEY (`language_id`)
    REFERENCES `serlo`.`language` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ads_upload1`
    FOREIGN KEY (`image_id`)
    REFERENCES `serlo`.`attachment` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ads_user1`
    FOREIGN KEY (`author_id`)
    REFERENCES `serlo`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo`.`attachement_file`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo`.`attachement_file` ;

CREATE TABLE IF NOT EXISTS `serlo`.`attachement_file` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `attachment_id` BIGINT NOT NULL,
  `size` INT NOT NULL,
  `location` VARCHAR(255) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `type` VARCHAR(40) NOT NULL,
  `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `fk_attachement_file_attachment1_idx` (`attachment_id` ASC),
  CONSTRAINT `fk_attachement_file_attachment1`
    FOREIGN KEY (`attachment_id`)
    REFERENCES `serlo`.`attachment` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo`.`event_parameter_uuid`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo`.`event_parameter_uuid` ;

CREATE TABLE IF NOT EXISTS `serlo`.`event_parameter_uuid` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `uuid_id` BIGINT NOT NULL,
  `event_parameter_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_table1_uuid1_idx` (`uuid_id` ASC),
  INDEX `fk_event_parameter_uuid_event_parameter1_idx` (`event_parameter_id` ASC),
  CONSTRAINT `fk_table1_uuid1`
    FOREIGN KEY (`uuid_id`)
    REFERENCES `serlo`.`uuid` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_event_parameter_uuid_event_parameter1`
    FOREIGN KEY (`event_parameter_id`)
    REFERENCES `serlo`.`event_parameter` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `serlo`.`event_parameter_text`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `serlo`.`event_parameter_text` ;

CREATE TABLE IF NOT EXISTS `serlo`.`event_parameter_text` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `event_parameter_id` INT NOT NULL,
  `content` TEXT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_event_parameter_text_event_parameter1_idx` (`event_parameter_id` ASC),
  CONSTRAINT `fk_event_parameter_text_event_parameter1`
    FOREIGN KEY (`event_parameter_id`)
    REFERENCES `serlo`.`event_parameter` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Data for table `serlo`.`permission`
-- -----------------------------------------------------
START TRANSACTION;
USE `serlo`;
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (1, 'blog.post.create');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (2, 'blog.post.update');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (3, 'blog.post.trash');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (4, 'blog.post.purge');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (5, 'german');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (6, 'english');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (7, 'contexter.route.remove');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (8, 'contexter.context.remove');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (9, 'contexter.context.add');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (10, 'contexter.route.add');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (11, 'login');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (12, 'authorization.role.identity.modify');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (13, 'authorization.role.sysadmin.identity.modify');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (14, 'authorization.role.german.identity.modify');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (15, 'authorization.role.english.identity.modify');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (16, 'authorization.permission.add');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (17, 'authorization.permission.remove');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (18, 'contexter.context.manage');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (19, 'blog.posts.view_all');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (20, 'discussion.create');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (21, 'discussion.trash');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (22, 'discussion.purge');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (23, 'discussion.vote');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (24, 'discussion.archive');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (25, 'discussion.comment.create');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (26, 'discussion.comment.trash');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (27, 'discussion.comment.purge');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (28, 'entity.create');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (29, 'entity.trash');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (30, 'entity.purge');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (31, 'entity.revision.create');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (32, 'entity.revision.trash');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (33, 'entity.revision.purge');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (34, 'entity.revision.checkout');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (35, 'entity.license.update');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (36, 'entity.link.create');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (37, 'entity.link.purge');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (38, 'flag.create');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (39, 'flag.purge');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (40, 'license.create');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (41, 'license.update');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (42, 'license.purge');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (43, 'licenses.manage');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (44, 'related_content.add');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (45, 'related_content.purge');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (46, 'related_content.sort');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (47, 'taxonomy.create');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (48, 'taxonomy.purge');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (49, 'taxonomy.term.create');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (50, 'taxonomy.term.update');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (51, 'taxonomy.term.trash');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (52, 'taxonomy.term.purge');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (53, 'taxonomy.term.associate');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (54, 'taxonomy.term.dissociate');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (55, 'taxonomy.term.sort');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (56, 'upload.create');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (57, 'upload.purge');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (58, 'uuid.create');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (59, 'uuid.purge');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (60, 'page.create');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (61, 'page.update');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (62, 'page.trash');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (63, 'page.purge');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (64, 'page.revision.create');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (65, 'page.revision.trash');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (66, 'page.revision.purge');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (67, 'page.revision.checkout');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (68, 'contexter.context.manage');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (69, 'entity.link.order');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (70, 'entity.restore');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (71, 'entity.revision.restore');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (72, 'page.restore');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (73, 'page.revision.restore');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (74, 'discussion.restore');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (75, 'discussion.comment.restore');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (76, 'blog.post.restore');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (77, 'taxonomy.term.restore');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (78, 'uuid.restore');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (79, 'related_content.manage');
INSERT INTO `serlo`.`permission` (`id`, `name`) VALUES (80, 'taxonomy.term.associated.sort');

COMMIT;


-- -----------------------------------------------------
-- Data for table `serlo`.`language`
-- -----------------------------------------------------
START TRANSACTION;
USE `serlo`;
INSERT INTO `serlo`.`language` (`id`, `permission_id`, `name`, `code`, `dateformat`, `timeformat`) VALUES (1, 5, 'Deutsch', 'de', 'DMY', 'Y:M');
INSERT INTO `serlo`.`language` (`id`, `permission_id`, `name`, `code`, `dateformat`, `timeformat`) VALUES (2, 6, 'English', 'en', 'MDY', 'Y:M');

COMMIT;


-- -----------------------------------------------------
-- Data for table `serlo`.`role`
-- -----------------------------------------------------
START TRANSACTION;
USE `serlo`;
INSERT INTO `serlo`.`role` (`id`, `name`, `parent_id`, `description`) VALUES (1, 'guest', 2, NULL);
INSERT INTO `serlo`.`role` (`id`, `name`, `parent_id`, `description`) VALUES (2, 'login', 3, NULL);
INSERT INTO `serlo`.`role` (`id`, `name`, `parent_id`, `description`) VALUES (3, 'reviewer', 4, NULL);
INSERT INTO `serlo`.`role` (`id`, `name`, `parent_id`, `description`) VALUES (4, 'helper', 5, NULL);
INSERT INTO `serlo`.`role` (`id`, `name`, `parent_id`, `description`) VALUES (5, 'admin', 7, NULL);
INSERT INTO `serlo`.`role` (`id`, `name`, `parent_id`, `description`) VALUES (6, 'horizonhelper', NULL, NULL);
INSERT INTO `serlo`.`role` (`id`, `name`, `parent_id`, `description`) VALUES (7, 'moderator', 8, NULL);
INSERT INTO `serlo`.`role` (`id`, `name`, `parent_id`, `description`) VALUES (8, 'ambassador', 9, NULL);
INSERT INTO `serlo`.`role` (`id`, `name`, `parent_id`, `description`) VALUES (9, 'langhelper', 10, NULL);
INSERT INTO `serlo`.`role` (`id`, `name`, `parent_id`, `description`) VALUES (10, 'langadmin', 11, NULL);
INSERT INTO `serlo`.`role` (`id`, `name`, `parent_id`, `description`) VALUES (11, 'sysadmin', NULL, NULL);
INSERT INTO `serlo`.`role` (`id`, `name`, `parent_id`, `description`) VALUES (12, 'german', NULL, NULL);
INSERT INTO `serlo`.`role` (`id`, `name`, `parent_id`, `description`) VALUES (13, 'english', NULL, NULL);

COMMIT;


-- -----------------------------------------------------
-- Data for table `serlo`.`uuid`
-- -----------------------------------------------------
START TRANSACTION;
USE `serlo`;
INSERT INTO `serlo`.`uuid` (`id`, `uuid`, `trashed`) VALUES (1, 'aeneasr', 0);
INSERT INTO `serlo`.`uuid` (`id`, `uuid`, `trashed`) VALUES (2, 'devuser', 0);
INSERT INTO `serlo`.`uuid` (`id`, `uuid`, `trashed`) VALUES (3, 'id3', 0);
INSERT INTO `serlo`.`uuid` (`id`, `uuid`, `trashed`) VALUES (4, 'id4', 0);
INSERT INTO `serlo`.`uuid` (`id`, `uuid`, `trashed`) VALUES (5, 'id5', 0);
INSERT INTO `serlo`.`uuid` (`id`, `uuid`, `trashed`) VALUES (6, 'id6', 0);
INSERT INTO `serlo`.`uuid` (`id`, `uuid`, `trashed`) VALUES (7, 'id7', 0);
INSERT INTO `serlo`.`uuid` (`id`, `uuid`, `trashed`) VALUES (8, 'id8', 0);
INSERT INTO `serlo`.`uuid` (`id`, `uuid`, `trashed`) VALUES (9, 'id9', 0);

COMMIT;


-- -----------------------------------------------------
-- Data for table `serlo`.`user`
-- -----------------------------------------------------
START TRANSACTION;
USE `serlo`;
INSERT INTO `serlo`.`user` (`id`, `email`, `username`, `password`, `logins`, `date`, `ads_enabled`, `token`, `last_login`) VALUES (1, 'aeneas@q-mail.me', 'arekkas', '37fe351ad34e2398b82f97295c3817ba02dd8e1d5777e8467a', 486, NULL, 0, '1234', NULL);
INSERT INTO `serlo`.`user` (`id`, `email`, `username`, `password`, `logins`, `date`, `ads_enabled`, `token`, `last_login`) VALUES (2, 'dev@serlo.org', 'devuser', '8a534960a8a4c8e348150a0ae3c7f4b857bfead4f02c8cbf0d', 0, NULL, 0, '12345', NULL);
INSERT INTO `serlo`.`user` (`id`, `email`, `username`, `password`, `logins`, `date`, `ads_enabled`, `token`, `last_login`) VALUES (7, 'legacy@serlo.org', 'Legacy', '8a534960a8a4c8e348150a0ae3c7f4b857bfead4f02c8cbf0d', 0, NULL, 0, '7646', NULL);

COMMIT;


-- -----------------------------------------------------
-- Data for table `serlo`.`type`
-- -----------------------------------------------------
START TRANSACTION;
USE `serlo`;
INSERT INTO `serlo`.`type` (`id`, `name`) VALUES (1, 'text-exercise');
INSERT INTO `serlo`.`type` (`id`, `name`) VALUES (2, 'text-solution');
INSERT INTO `serlo`.`type` (`id`, `name`) VALUES (3, 'article');
INSERT INTO `serlo`.`type` (`id`, `name`) VALUES (4, 'exercise-group');
INSERT INTO `serlo`.`type` (`id`, `name`) VALUES (5, 'grouped-text-exercise');
INSERT INTO `serlo`.`type` (`id`, `name`) VALUES (6, 'video');
INSERT INTO `serlo`.`type` (`id`, `name`) VALUES (7, 'module');
INSERT INTO `serlo`.`type` (`id`, `name`) VALUES (8, 'module-page');
INSERT INTO `serlo`.`type` (`id`, `name`) VALUES (9, 'link');
INSERT INTO `serlo`.`type` (`id`, `name`) VALUES (10, 'dependency');
INSERT INTO `serlo`.`type` (`id`, `name`) VALUES (11, 'abstract-topic');
INSERT INTO `serlo`.`type` (`id`, `name`) VALUES (12, 'topic');
INSERT INTO `serlo`.`type` (`id`, `name`) VALUES (13, 'subject');
INSERT INTO `serlo`.`type` (`id`, `name`) VALUES (14, 'curriculum');
INSERT INTO `serlo`.`type` (`id`, `name`) VALUES (15, 'school-type');
INSERT INTO `serlo`.`type` (`id`, `name`) VALUES (16, 'curriculum-folder');
INSERT INTO `serlo`.`type` (`id`, `name`) VALUES (17, 'root');
INSERT INTO `serlo`.`type` (`id`, `name`) VALUES (18, 'forum-category');
INSERT INTO `serlo`.`type` (`id`, `name`) VALUES (19, 'forum');
INSERT INTO `serlo`.`type` (`id`, `name`) VALUES (20, 'topic-folder');
INSERT INTO `serlo`.`type` (`id`, `name`) VALUES (21, 'blog');
INSERT INTO `serlo`.`type` (`id`, `name`) VALUES (22, 'spam');
INSERT INTO `serlo`.`type` (`id`, `name`) VALUES (23, 'offensive');
INSERT INTO `serlo`.`type` (`id`, `name`) VALUES (24, 'other');
INSERT INTO `serlo`.`type` (`id`, `name`) VALUES (25, 'help');
INSERT INTO `serlo`.`type` (`id`, `name`) VALUES (26, 'guideline');

COMMIT;


-- -----------------------------------------------------
-- Data for table `serlo`.`license`
-- -----------------------------------------------------
START TRANSACTION;
USE `serlo`;
INSERT INTO `serlo`.`license` (`id`, `language_id`, `title`, `url`, `content`, `icon_href`) VALUES (1, 1, 'cc-by-sa-3.0', 'http://creativecommons.org/licenses/by-sa/3.0/', 'cc-by-sa erklärt', 'http://mirrors.creativecommons.org/presskit/buttons/88x31/png/by-sa.png');

COMMIT;


-- -----------------------------------------------------
-- Data for table `serlo`.`taxonomy`
-- -----------------------------------------------------
START TRANSACTION;
USE `serlo`;
INSERT INTO `serlo`.`taxonomy` (`id`, `type_id`, `language_id`) VALUES (1, 17, 1);
INSERT INTO `serlo`.`taxonomy` (`id`, `type_id`, `language_id`) VALUES (2, 18, 1);
INSERT INTO `serlo`.`taxonomy` (`id`, `type_id`, `language_id`) VALUES (3, 13, 1);
INSERT INTO `serlo`.`taxonomy` (`id`, `type_id`, `language_id`) VALUES (4, 11, 1);
INSERT INTO `serlo`.`taxonomy` (`id`, `type_id`, `language_id`) VALUES (5, 12, 1);

COMMIT;


-- -----------------------------------------------------
-- Data for table `serlo`.`term`
-- -----------------------------------------------------
START TRANSACTION;
USE `serlo`;
INSERT INTO `serlo`.`term` (`id`, `language_id`, `name`, `slug`) VALUES (1, 1, 'Root', 'root');
INSERT INTO `serlo`.`term` (`id`, `language_id`, `name`, `slug`) VALUES (2, 1, 'Discussions', 'discussions');
INSERT INTO `serlo`.`term` (`id`, `language_id`, `name`, `slug`) VALUES (3, 1, 'Mathe', 'mathe');
INSERT INTO `serlo`.`term` (`id`, `language_id`, `name`, `slug`) VALUES (4, 1, 'Legacy', 'legacy');
INSERT INTO `serlo`.`term` (`id`, `language_id`, `name`, `slug`) VALUES (5, 1, 'Articles', 'articles');

COMMIT;


-- -----------------------------------------------------
-- Data for table `serlo`.`term_taxonomy`
-- -----------------------------------------------------
START TRANSACTION;
USE `serlo`;
INSERT INTO `serlo`.`term_taxonomy` (`id`, `taxonomy_id`, `term_id`, `parent_id`, `description`, `weight`) VALUES (3, 1, 1, NULL, NULL, NULL);
INSERT INTO `serlo`.`term_taxonomy` (`id`, `taxonomy_id`, `term_id`, `parent_id`, `description`, `weight`) VALUES (4, 2, 2, 3, NULL, NULL);
INSERT INTO `serlo`.`term_taxonomy` (`id`, `taxonomy_id`, `term_id`, `parent_id`, `description`, `weight`) VALUES (5, 3, 3, 3, NULL, NULL);
INSERT INTO `serlo`.`term_taxonomy` (`id`, `taxonomy_id`, `term_id`, `parent_id`, `description`, `weight`) VALUES (8, 4, 4, 5, 'Inhalte aus Serlo1', NULL);
INSERT INTO `serlo`.`term_taxonomy` (`id`, `taxonomy_id`, `term_id`, `parent_id`, `description`, `weight`) VALUES (9, 5, 5, 8, 'Artikel aus Serlo1', NULL);

COMMIT;


-- -----------------------------------------------------
-- Data for table `serlo`.`role_user`
-- -----------------------------------------------------
START TRANSACTION;
USE `serlo`;
INSERT INTO `serlo`.`role_user` (`user_id`, `role_id`) VALUES (1, 11);
INSERT INTO `serlo`.`role_user` (`user_id`, `role_id`) VALUES (2, 11);
INSERT INTO `serlo`.`role_user` (`user_id`, `role_id`) VALUES (1, 2);
INSERT INTO `serlo`.`role_user` (`user_id`, `role_id`) VALUES (2, 2);

COMMIT;


-- -----------------------------------------------------
-- Data for table `serlo`.`role_permission`
-- -----------------------------------------------------
START TRANSACTION;
USE `serlo`;
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (9, 1);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (9, 2);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (9, 3);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (9, 4);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (12, 5);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (13, 6);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (10, 7);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (10, 8);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (10, 9);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (10, 10);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (2, 11);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (11, 5);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (11, 6);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (10, 12);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (11, 13);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (12, 14);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (13, 15);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (11, 16);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (11, 17);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (10, 18);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (10, 19);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (2, 20);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (7, 21);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (10, 22);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (2, 23);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (7, 24);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (2, 25);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (7, 26);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (10, 27);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (2, 28);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (5, 29);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (10, 30);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (2, 31);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (3, 32);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (10, 33);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (3, 34);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (5, 35);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (2, 36);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (4, 37);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (2, 38);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (7, 39);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (5, 40);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (5, 41);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (11, 42);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (5, 43);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (4, 44);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (4, 45);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (4, 46);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (10, 47);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (10, 48);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (4, 49);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (4, 50);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (4, 51);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (10, 52);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (2, 53);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (4, 54);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (4, 55);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (2, 56);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (10, 57);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (2, 58);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (11, 59);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (10, 60);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (10, 61);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (10, 62);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (11, 63);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (2, 64);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (2, 65);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (11, 66);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (2, 67);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (10, 68);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (4, 69);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (4, 70);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (3, 71);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (10, 72);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (2, 73);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (7, 74);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (7, 75);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (9, 76);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (4, 77);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (11, 78);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (4, 79);
INSERT INTO `serlo`.`role_permission` (`role_id`, `permission_id`) VALUES (4, 80);

COMMIT;


-- -----------------------------------------------------
-- Data for table `serlo`.`metadata_key`
-- -----------------------------------------------------
START TRANSACTION;
USE `serlo`;
INSERT INTO `serlo`.`metadata_key` (`id`, `name`) VALUES (1, 'subject');
INSERT INTO `serlo`.`metadata_key` (`id`, `name`) VALUES (2, 'keywords');
INSERT INTO `serlo`.`metadata_key` (`id`, `name`) VALUES (3, 'description');
INSERT INTO `serlo`.`metadata_key` (`id`, `name`) VALUES (4, 'license');
INSERT INTO `serlo`.`metadata_key` (`id`, `name`) VALUES (5, 'author');

COMMIT;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
