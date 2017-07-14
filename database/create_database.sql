-- MySQL Script generated by MySQL Workbench
-- Thu Jul 13 21:33:00 2017
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema cruds
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema cruds
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `cruds` DEFAULT CHARACTER SET utf8 ;
USE `cruds` ;

-- -----------------------------------------------------
-- Table `cruds`.`crud1`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cruds`.`crud1` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `description` VARCHAR(3000) NOT NULL,
  `text` TEXT NOT NULL,
  `image` VARCHAR(3000) NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cruds`.`crud2`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cruds`.`crud2` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `description` VARCHAR(3000) NOT NULL,
  `text` TEXT NOT NULL,
  `image` VARCHAR(3000) NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cruds`.`crud5`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cruds`.`crud5` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `description` VARCHAR(3000) NOT NULL,
  `text` TEXT NOT NULL,
  `image` VARCHAR(3000) NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cruds`.`crud4`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cruds`.`crud4` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `description` VARCHAR(3000) NOT NULL,
  `text` TEXT NOT NULL,
  `image` VARCHAR(3000) NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cruds`.`crud3`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cruds`.`crud3` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `description` VARCHAR(3000) NOT NULL,
  `text` TEXT NOT NULL,
  `image` VARCHAR(3000) NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
