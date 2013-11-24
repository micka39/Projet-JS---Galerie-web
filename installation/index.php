<?php
require '../class/config.php';
$pdo = connectPdo();
$sql = "SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `projetJs` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `projetJs` ;

-- -----------------------------------------------------
-- Table `projetJs`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `user` (
  `iduser` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(45) NOT NULL,
  `password` VARCHAR(45) NOT NULL,
  `time` TIMESTAMP NULL,
  `level_access` BIT NULL,
  `email` VARCHAR(255) NULL,
  PRIMARY KEY (`iduser`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `projetJs`.`category`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `category` (
  `idcategory` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL,
  `description` VARCHAR(45) NULL,
  `time` TIMESTAMP NULL,
  PRIMARY KEY (`idcategory`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `projetJs`.`image`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `image` (
  `idimage` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(45) NULL,
  `description` VARCHAR(45) NULL,
  `thumb` VARCHAR(45) NULL,
  PRIMARY KEY (`idimage`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `projetJs`.`imageCategory`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `imageCategory` (
  `category_idcategory` INT NOT NULL,
  `image_idimage` INT NOT NULL,
  PRIMARY KEY (`category_idcategory`, `image_idimage`),
  INDEX `fk_category_has_image_image1_idx` (`image_idimage` ASC),
  INDEX `fk_category_has_image_category_idx` (`category_idcategory` ASC),
  CONSTRAINT `fk_category_has_image_category`
    FOREIGN KEY (`category_idcategory`)
    REFERENCES `projetJs`.`category` (`idcategory`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_category_has_image_image1`
    FOREIGN KEY (`image_idimage`)
    REFERENCES `projetJs`.`image` (`idimage`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
";
$pdo->query($sql);
$pdo->query("INSERT INTO `user` (`username`, `password`, `level_access`, `fullname`) VALUES ('admin', 'jsiVSyV2S6NZw', 0, 'Administrateur');
");

