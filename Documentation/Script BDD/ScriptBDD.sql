-- MySQL Script generated by MySQL Workbench
-- Fri May  5 13:47:00 2023
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema LocHabitat
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema LocHabitat
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `LocHabitat` DEFAULT CHARACTER SET utf8 ;
USE `LocHabitat` ;

-- -----------------------------------------------------
-- Table `LocHabitat`.`users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `LocHabitat`.`users` ;

CREATE TABLE IF NOT EXISTS `LocHabitat`.`users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `lastname` VARCHAR(80) NOT NULL,
  `firstname` VARCHAR(80) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `phonenumber` INT(15) NOT NULL,
  `password` VARCHAR(60) NOT NULL,
  `usertype` INT(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `UniqueUser` (`email` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `LocHabitat`.`locations`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `LocHabitat`.`locations` ;

CREATE TABLE IF NOT EXISTS `LocHabitat`.`locations` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `place` VARCHAR(130) NOT NULL,
  `description` VARCHAR(500) NOT NULL,
  `housingtype` VARCHAR(20) NOT NULL,
  `maximumnbofclients` INT(2) NOT NULL,
  `pricepernight` DECIMAL(6,2) NOT NULL,
  `user_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `UniqueLocation` (`id` ASC) VISIBLE,
  INDEX `fk_locations_users1_idx` (`user_id` ASC) VISIBLE,
  CONSTRAINT `fk_locations_users1`
    FOREIGN KEY (`user_id`)
    REFERENCES `LocHabitat`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `LocHabitat`.`reservations`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `LocHabitat`.`reservations` ;

CREATE TABLE IF NOT EXISTS `LocHabitat`.`reservations` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `startdate` DATE NOT NULL,
  `enddate` DATE NOT NULL,
  `location_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `UniqueReservation` (`id` ASC) VISIBLE,
  INDEX `fk_reservations_locations1_idx` (`location_id` ASC) VISIBLE,
  INDEX `fk_reservations_users1_idx` (`user_id` ASC) VISIBLE,
  CONSTRAINT `fk_reservations_locations1`
    FOREIGN KEY (`location_id`)
    REFERENCES `LocHabitat`.`locations` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_reservations_users1`
    FOREIGN KEY (`user_id`)
    REFERENCES `LocHabitat`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `LocHabitat`.`images`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `LocHabitat`.`images` ;

CREATE TABLE IF NOT EXISTS `LocHabitat`.`images` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `location_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `UniqueImage` (`name` ASC) VISIBLE,
  INDEX `fk_images_locations_idx` (`location_id` ASC) VISIBLE,
  CONSTRAINT `fk_images_locations`
    FOREIGN KEY (`location_id`)
    REFERENCES `LocHabitat`.`locations` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
