-- MySQL Script generated by MySQL Workbench
-- Fri Mar  1 17:26:46 2019
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE=''ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'';

-- -----------------------------------------------------
-- Schema literature
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Table `literature_sets`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `literature_sets` ;

CREATE TABLE IF NOT EXISTS `literature_sets` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `period` VARCHAR(255) NOT NULL,
  `required_book_count` INT NOT NULL,
  `author_max_count` INT NOT NULL,
  `is_active` TINYINT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `period_UNIQUE` (`period` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `literature_groups`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `literature_groups` ;

CREATE TABLE IF NOT EXISTS `literature_groups` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `literature_set_id` INT NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `min_count` INT NOT NULL,
  `sort_order` INT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  INDEX `fk_literature_groups_literature_sets1_idx` (`literature_set_id` ASC),
  UNIQUE INDEX `set_title_unique` (`literature_set_id` ASC, `title` ASC),
  CONSTRAINT `fk_literature_groups_literature_sets1`
    FOREIGN KEY (`literature_set_id`)
    REFERENCES `literature_sets` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `authors`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `authors` ;

CREATE TABLE IF NOT EXISTS `authors` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `full_name` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `full_name_UNIQUE` (`full_name` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `literature_forms`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `literature_forms` ;

CREATE TABLE IF NOT EXISTS `literature_forms` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `books`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `books` ;

CREATE TABLE IF NOT EXISTS `books` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `author_id` INT NOT NULL,
  `literature_form_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_literature_set_items_authors_idx` (`author_id` ASC),
  INDEX `fk_literature_set_items_genres1_idx` (`literature_form_id` ASC),
  UNIQUE INDEX `title_UNIQUE` (`title` ASC),
  CONSTRAINT `fk_literature_set_items_authors`
    FOREIGN KEY (`author_id`)
    REFERENCES `authors` (`id`)
    ON DELETE RESTRICT
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_literature_set_items_genres1`
    FOREIGN KEY (`literature_form_id`)
    REFERENCES `literature_forms` (`id`)
    ON DELETE RESTRICT
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `literature_sets_required_literature_forms`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `literature_sets_required_literature_forms` ;

CREATE TABLE IF NOT EXISTS `literature_sets_required_literature_forms` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `literature_sets_id` INT NOT NULL,
  `literature_forms_id` INT NOT NULL,
  `min_count` INT NOT NULL,
  INDEX `fk_literature_sets_has_genres_genres1_idx` (`literature_forms_id` ASC),
  INDEX `fk_literature_sets_has_genres_literature_sets1_idx` (`literature_sets_id` ASC),
  PRIMARY KEY (`id`, `literature_sets_id`, `literature_forms_id`),
  CONSTRAINT `fk_literature_sets_has_genres_literature_sets1`
    FOREIGN KEY (`literature_sets_id`)
    REFERENCES `literature_sets` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_literature_sets_has_genres_genres1`
    FOREIGN KEY (`literature_forms_id`)
    REFERENCES `literature_forms` (`id`)
    ON DELETE RESTRICT
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `literature_groups_has_books`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `literature_groups_has_books` ;

CREATE TABLE IF NOT EXISTS `literature_groups_has_books` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `literature_groups_id` INT NOT NULL,
  `books_id` INT NOT NULL,
  `sort_order` INT NULL DEFAULT 0,
  PRIMARY KEY (`id`, `literature_groups_id`, `books_id`),
  INDEX `fk_literature_groups_has_books_books1_idx` (`books_id` ASC),
  INDEX `fk_literature_groups_has_books_literature_groups1_idx` (`literature_groups_id` ASC),
  CONSTRAINT `fk_literature_groups_has_books_literature_groups1`
    FOREIGN KEY (`literature_groups_id`)
    REFERENCES `literature_groups` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_literature_groups_has_books_books1`
    FOREIGN KEY (`books_id`)
    REFERENCES `books` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
