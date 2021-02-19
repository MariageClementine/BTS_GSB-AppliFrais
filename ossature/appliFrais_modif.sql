SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

DROP SCHEMA IF EXISTS `appliFrais` ;
CREATE SCHEMA IF NOT EXISTS `appliFrais` ;
USE `appliFrais` ;

-- -----------------------------------------------------
-- Table `appliFrais`.`FraisForfait`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `appliFrais`.`FraisForfait` ;

CREATE  TABLE IF NOT EXISTS `appliFrais`.`FraisForfait` (
  `id` CHAR(3) NOT NULL ,
  `libelle` CHAR(20) NULL DEFAULT NULL ,
  `montant` DECIMAL(5,2) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `appliFrais`.`Etat`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `appliFrais`.`Etat` ;

CREATE  TABLE IF NOT EXISTS `appliFrais`.`Etat` (
  `id` CHAR(2) NOT NULL ,
  `libelle` VARCHAR(30) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `appliFrais`.`Visiteur`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `appliFrais`.`Visiteur` ;

CREATE  TABLE IF NOT EXISTS `appliFrais`.`Visiteur` (
  `id` CHAR(4) NOT NULL ,
  `nom` CHAR(30) NULL DEFAULT NULL ,
  `prenom` CHAR(30) NULL DEFAULT NULL ,
  `login` CHAR(20) NULL DEFAULT NULL ,
  `mdp` CHAR(20) NULL DEFAULT NULL ,
  `adresse` CHAR(30) NULL DEFAULT NULL ,
  `cp` CHAR(5) NULL DEFAULT NULL ,
  `ville` CHAR(30) NULL DEFAULT NULL ,
  `dateEmbauche` DATE NULL DEFAULT NULL ,
  `comptable` TINYINT(1) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `appliFrais`.`FicheFrais`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `appliFrais`.`FicheFrais` ;

CREATE  TABLE IF NOT EXISTS `appliFrais`.`FicheFrais` (
  `idVisiteur` CHAR(4) NOT NULL ,
  `mois` CHAR(6) NOT NULL ,
  `nbJustificatifs` INT(11) NULL DEFAULT NULL ,
  `montantValide` DECIMAL(10,2) NULL DEFAULT NULL ,
  `dateModif` DATE NULL DEFAULT NULL ,
  `idEtat` CHAR(2) NULL DEFAULT 'CR' ,
  PRIMARY KEY (`idVisiteur`, `mois`) ,
  INDEX `fk_bd61889c-ba62-11e3-8d58-080027274fe6` (`idEtat` ASC) ,
  CONSTRAINT `fk_bd61889c-ba62-11e3-8d58-080027274fe6`
    FOREIGN KEY (`idEtat` )
    REFERENCES `appliFrais`.`Etat` (`id` ),
  CONSTRAINT `fk_bd618e1e-ba62-11e3-8d58-080027274fe6`
    FOREIGN KEY (`idVisiteur` )
    REFERENCES `appliFrais`.`Visiteur` (`id` ))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `appliFrais`.`FicheFrais`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `appliFrais`.`FicheFrais` ;

CREATE  TABLE IF NOT EXISTS `appliFrais`.`FicheFrais` (
  `idVisiteur` CHAR(4) NOT NULL ,
  `mois` CHAR(6) NOT NULL ,
  `nbJustificatifs` INT(11) NULL DEFAULT NULL ,
  `montantValide` DECIMAL(10,2) NULL DEFAULT NULL ,
  `dateModif` DATE NULL DEFAULT NULL ,
  `idEtat` CHAR(2) NULL DEFAULT 'CR' ,
  PRIMARY KEY (`idVisiteur`, `mois`) ,
  INDEX `fk_bd61889c-ba62-11e3-8d58-080027274fe6` (`idEtat` ASC) ,
  CONSTRAINT `fk_bd61889c-ba62-11e3-8d58-080027274fe6`
    FOREIGN KEY (`idEtat` )
    REFERENCES `appliFrais`.`Etat` (`id` ),
  CONSTRAINT `fk_bd618e1e-ba62-11e3-8d58-080027274fe6`
    FOREIGN KEY (`idVisiteur` )
    REFERENCES `appliFrais`.`Visiteur` (`id` ))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `appliFrais`.`LigneFraisForfait`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `appliFrais`.`LigneFraisForfait` ;

CREATE  TABLE IF NOT EXISTS `appliFrais`.`LigneFraisForfait` (
  `idVisiteur` CHAR(4) NOT NULL ,
  `mois` CHAR(6) NOT NULL ,
  `idFraisForfait` CHAR(3) NOT NULL ,
  `quantite` INT(11) NULL DEFAULT NULL ,
  `etat` BOOL DEFAULT 0,
  PRIMARY KEY (`idVisiteur`, `mois`, `idFraisForfait`) ,
  INDEX `fk_bd620812-ba62-11e3-8d58-080027274fe6` (`idFraisForfait` ASC) ,
  INDEX `fk_LigneFraisForfait_Visiteur1_idx` (`idVisiteur` ASC) ,
  CONSTRAINT `fk_bd6203c6-ba62-11e3-8d58-080027274fe6`
    FOREIGN KEY (`idVisiteur` , `mois` )
    REFERENCES `appliFrais`.`FicheFrais` (`idVisiteur` , `mois` ),
  CONSTRAINT `fk_bd620812-ba62-11e3-8d58-080027274fe6`
    FOREIGN KEY (`idFraisForfait` )
    REFERENCES `appliFrais`.`FraisForfait` (`id` ),
  CONSTRAINT `fk_LigneFraisForfait_Visiteur1`
    FOREIGN KEY (`idVisiteur` )
    REFERENCES `appliFrais`.`Visiteur` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `appliFrais`.`LigneFraisHorsForfait`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `appliFrais`.`LigneFraisHorsForfait` ;

CREATE  TABLE IF NOT EXISTS `appliFrais`.`LigneFraisHorsForfait` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `idVisiteur` CHAR(4) NOT NULL ,
  `mois` CHAR(6) NOT NULL ,
  `libelle` VARCHAR(100) NULL DEFAULT NULL ,
  `date` DATE NULL DEFAULT NULL ,
  `montant` DECIMAL(10,2) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_bd626dd4-ba62-11e3-8d58-080027274fe6` (`idVisiteur` ASC, `mois` ASC) ,
  INDEX `fk_LigneFraisHorsForfait_Visiteur1_idx` (`idVisiteur` ASC) ,
  CONSTRAINT `fk_bd626dd4-ba62-11e3-8d58-080027274fe6`
    FOREIGN KEY (`idVisiteur` , `mois` )
    REFERENCES `appliFrais`.`FicheFrais` (`idVisiteur` , `mois` ),
  CONSTRAINT `fk_LigneFraisHorsForfait_Visiteur1`
    FOREIGN KEY (`idVisiteur` )
    REFERENCES `appliFrais`.`Visiteur` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

USE `appliFrais` ;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
