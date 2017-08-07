-- MySQL Script generated by MySQL Workbench
-- Mon Aug  7 04:07:28 2017
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema ramayana
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema ramayana
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `ramayana` DEFAULT CHARACTER SET utf8 ;
USE `ramayana` ;

-- -----------------------------------------------------
-- Table `ramayana`.`textos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ramayana`.`textos` (
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
-- Table `ramayana`.`aulas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ramayana`.`aulas` (
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
-- Table `ramayana`.`sobre`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ramayana`.`sobre` (
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
-- Table `ramayana`.`sitios`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ramayana`.`sitios` (
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
-- Table `ramayana`.`informes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ramayana`.`informes` (
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
-- Table `ramayana`.`videos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ramayana`.`videos` (
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
-- Table `ramayana`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ramayana`.`user` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `first_name` VARCHAR(45) NULL,
  `last_name` VARCHAR(45) NULL,
  `facebook_id` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`));


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
