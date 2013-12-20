<?php
require '../class/config.php';
$pdo = connectPdo();
$sql = "
-- -----------------------------------------------------
-- Table `projetJs`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `user` (
  `iduser` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(45) NOT NULL,
  `password` VARCHAR(45) NOT NULL,
  `time` TIMESTAMP NULL,
  `level_access` TINYINT NULL,
  `email` VARCHAR(255) NULL,
  PRIMARY KEY (`iduser`))
ENGINE = InnoDB
";
$pdo->query($sql);
$sql = "-- -----------------------------------------------------
-- Table `projetJs`.`category`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `category` (
  `idcategory` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL,
  `description` VARCHAR(45) NULL,
  `time` TIMESTAMP NULL,
  PRIMARY KEY (`idcategory`))
ENGINE = InnoDB
";
$pdo->query($sql);
$sql ="-- -----------------------------------------------------
-- Table `projetJs`.`image`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `image` (
  `idimage` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(45) NULL,
  `description` VARCHAR(45) NULL,
  `file_name` VARCHAR(45) NULL,
  `extension` VARCHAR(5) NULL,
  PRIMARY KEY (`idimage`))
ENGINE = InnoDB
";$pdo->query($sql);
$sql = "-- -----------------------------------------------------
-- Table `projetJs`.`imagecategory`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `imagecategory` (
  `category_idcategory` INT NOT NULL,
  `image_idimage` INT NOT NULL,
  PRIMARY KEY (`category_idcategory`, `image_idimage`),
  INDEX `fk_category_has_image_image1_idx` (`image_idimage` ASC),
  INDEX `fk_category_has_image_category_idx` (`category_idcategory` ASC),
  CONSTRAINT `fk_category_has_image_category`
    FOREIGN KEY (`category_idcategory`)
    REFERENCES `category` (`idcategory`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_category_has_image_image1`
    FOREIGN KEY (`image_idimage`)
    REFERENCES `image` (`idimage`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB

";
$pdo->query($sql);
$pdo->query("INSERT INTO `user` (`username`, `password`, `level_access`, `email`) VALUES ('admin', 'jsiVSyV2S6NZw', 0, 'mickael.puyfages@univ-lyon1.fr');
");
$pdo->query("INSERT INTO `category` (`name`, `description`) VALUES ('Non catégorisé', 'Catégorie par défaut');");
header('Location: ../category/index.php'); 