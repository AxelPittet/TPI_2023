-- MySQL Script generated by MySQL Workbench
-- Mon May  8 14:51:55 2023
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema lochab_ap2_BDD
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema lochab_ap2_BDD
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `lochab_ap2_BDD` DEFAULT CHARACTER SET utf8 ;
USE `lochab_ap2_BDD` ;

-- -----------------------------------------------------
-- Table `lochab_ap2_BDD`.`users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `lochab_ap2_BDD`.`users` ;

CREATE TABLE IF NOT EXISTS `lochab_ap2_BDD`.`users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `lastname` VARCHAR(80) NOT NULL,
  `firstname` VARCHAR(80) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `phoneNumber` INT(15) NOT NULL,
  `password` VARCHAR(60) NOT NULL,
  `userType` TINYINT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `UniqueUser` (`email` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `lochab_ap2_BDD`.`locations`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `lochab_ap2_BDD`.`locations` ;

CREATE TABLE IF NOT EXISTS `lochab_ap2_BDD`.`locations` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `locationNumber` INT NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `place` VARCHAR(130) NOT NULL,
  `description` VARCHAR(500) NOT NULL,
  `housingType` VARCHAR(20) NOT NULL,
  `maximumNbOfClients` INT(2) NOT NULL,
  `pricePerNight` DECIMAL(6,2) NOT NULL,
  `user_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_locations_users1_idx` (`user_id` ASC) VISIBLE,
  UNIQUE INDEX `locationNumber_UNIQUE` (`locationNumber` ASC) VISIBLE,
  CONSTRAINT `fk_locations_users1`
    FOREIGN KEY (`user_id`)
    REFERENCES `lochab_ap2_BDD`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `lochab_ap2_BDD`.`reservations`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `lochab_ap2_BDD`.`reservations` ;

CREATE TABLE IF NOT EXISTS `lochab_ap2_BDD`.`reservations` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `reservationNumber` INT NOT NULL,
  `startDate` DATE NOT NULL,
  `endDate` DATE NOT NULL,
  `price` DECIMAL(7,2) NOT NULL,
  `location_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_reservations_locations1_idx` (`location_id` ASC) VISIBLE,
  INDEX `fk_reservations_users1_idx` (`user_id` ASC) VISIBLE,
  UNIQUE INDEX `reservationNumber_UNIQUE` (`reservationNumber` ASC) VISIBLE,
  CONSTRAINT `fk_reservations_locations1`
    FOREIGN KEY (`location_id`)
    REFERENCES `lochab_ap2_BDD`.`locations` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_reservations_users1`
    FOREIGN KEY (`user_id`)
    REFERENCES `lochab_ap2_BDD`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `lochab_ap2_BDD`.`images`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `lochab_ap2_BDD`.`images` ;

CREATE TABLE IF NOT EXISTS `lochab_ap2_BDD`.`images` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `location_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `UniqueImage` (`name` ASC) VISIBLE,
  INDEX `fk_images_locations_idx` (`location_id` ASC) VISIBLE,
  CONSTRAINT `fk_images_locations`
    FOREIGN KEY (`location_id`)
    REFERENCES `lochab_ap2_BDD`.`locations` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = INNODB;


-- -----------------------------------------------------
-- Datas `lochab_ap2_BDD`.`users`
-- -----------------------------------------------------
INSERT INTO users (id, lastname, firstname, email, phonenumber, `password`, usertype) VALUES (1, 'LocHabitat', 'Admin', 'Admin@LocHabitat.ch', '0123456789', '$2y$10$W2qSG9bjnUaD76moFkScXOg75r8G/E48IY.kCpmjgBYcAtepOaKaC', '2'); 


-- -----------------------------------------------------
-- Datas `lochab_ap2_BDD`.`locations`
-- -----------------------------------------------------
INSERT INTO locations (id, locationNumber, name, place, description, housingType, maximumNbOfClients, pricePerNight, user_id) VALUES (1, 975188771, 'Appartement sur lausanne', "Chemin d'ouchy 18, 1200 Lausanne", 'Magnifique 50m*2 au bord du lac léman.', 'Appartement', 3, 40.0, 1),
(2, 392223435, 'Château en périphérie de Paris', 'Chemin de Versailles 1, Versailles', 'Ancienne habitation des rois de France', 'Maison', 60, 800.0, 1),
(3, 835453766, 'Jolie maison à Yverdon', 'Chemin des rives 3, 1400 Yverdon', "Maison moderne en centre d'Yverdon", 'Maison', 6, 80.0, 1),
(4, 61425554, 'Grand appartement moderne à Orbe', 'Route du Signal 4, 1350 Orbe', "Spacieux 3.5 pièces de 76 m2 en ville d'Orbe avec une place de parc et un étage", 'Appartement', 4, 65.0, 1);


-- -----------------------------------------------------
-- Datas `lochab_ap2_BDD`.`images`
-- -----------------------------------------------------
INSERT INTO images (id, name, location_id) VALUES (1, 'view/img/291847011.jpg', 1),
(2, 'view/img/chateau-versailles.jpg', 2),
(3, 'view/img/versailles-chateau-2916790-jpg_2551712_1250x625.jpg', 2),
(4, 'view/img/maison.jpg', 3),
(5, 'view/img/amenagement-interieur-conseils-et-solutions-00.jpg', 3),
(6, 'view/img/chambre-moderne-gris-blanc-la-maison-saint-gobain.jpg', 3),
(7, 'view/img/big__apartment-for-sale-3-rooms-orbe-5e01a4e56b0a6.jpg', 4),
(8, 'view/img/big__apartment-for-sale-3-rooms-orbe-5e019c5bd9fa4.jpg', 4),
(9, 'view/img/big__apartment-for-sale-3-rooms-orbe-5e019c5ae17c3.jpg', 4); 



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
