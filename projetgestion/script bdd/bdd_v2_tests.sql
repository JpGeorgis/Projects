
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
DROP SCHEMA `mydb`;
-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 ;
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`TypeArticle`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`TypeArticle` (
  `idTypeArticle` INT NOT NULL AUTO_INCREMENT,
  `libelle` VARCHAR(90) NULL,
  PRIMARY KEY (`idTypeArticle`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Article`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Article` (
  `art_reference` INT NOT NULL AUTO_INCREMENT,
  `art_designation` VARCHAR(255) NOT NULL,
  `art_pu_ht` INT NOT NULL,
  `art_qte_stock` INT NOT NULL,
  `art_tx_tva` INT NULL,
  `stockArticleAutre` INT NULL,
  `TypeArticle_idTypeArticle` INT NOT NULL,
  PRIMARY KEY (`art_reference`),
  INDEX `fk_Article_TypeArticle1_idx` (`TypeArticle_idTypeArticle` ASC),
  CONSTRAINT `fk_Article_TypeArticle1`
    FOREIGN KEY (`TypeArticle_idTypeArticle`)
    REFERENCES `mydb`.`TypeArticle` (`idTypeArticle`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`ArticleTaille`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`ArticleTaille` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `libelle` VARCHAR(4) NULL,
  `taillePointure` INT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`SectionSportive`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`SectionSportive` (
  `idSectionSportive` INT NOT NULL AUTO_INCREMENT,
  `libelle` VARCHAR(45) NULL,
  PRIMARY KEY (`idSectionSportive`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Niveau`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Niveau` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `libelle` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Membre`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Membre` (
  `idMembre` INT NOT NULL AUTO_INCREMENT,
  `nom` VARCHAR(255) NOT NULL,
  `prenom` VARCHAR(255) NOT NULL,
  `dateNaissance` DATETIME NOT NULL,
  `dateInscription` DATETIME NULL,
  `SectionSportive_idSectionSportive` INT NOT NULL,
  `Niveau_id` INT NOT NULL,
  PRIMARY KEY (`idMembre`),
  INDEX `fk_Membre_SectionSportive1_idx` (`SectionSportive_idSectionSportive` ASC),
  INDEX `fk_Membre_Niveau1_idx` (`Niveau_id` ASC),
  CONSTRAINT `fk_Membre_SectionSportive1`
    FOREIGN KEY (`SectionSportive_idSectionSportive`)
    REFERENCES `mydb`.`SectionSportive` (`idSectionSportive`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Membre_Niveau1`
    FOREIGN KEY (`Niveau_id`)
    REFERENCES `mydb`.`Niveau` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`DateStock`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`DateStock` (
  `idDateStock` INT NOT NULL AUTO_INCREMENT,
  `dateSnapshot` DATETIME NOT NULL,
  PRIMARY KEY (`idDateStock`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`StockArticleSection`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`StockArticleSection` (
  `Article_art_reference` INT NOT NULL,
  `SectionSportive_idSectionSportive` INT NOT NULL,
  `stockRestant` VARCHAR(45) NULL,
  `DateStock_idDateStock` INT NOT NULL,
  PRIMARY KEY (`Article_art_reference`, `SectionSportive_idSectionSportive`, `DateStock_idDateStock`),
  INDEX `fk_Article_has_SectionSportive_SectionSportive1_idx` (`SectionSportive_idSectionSportive` ASC),
  INDEX `fk_Article_has_SectionSportive_Article_idx` (`Article_art_reference` ASC),
  INDEX `fk_StockArticleSection_DateStock1_idx` (`DateStock_idDateStock` ASC),
  CONSTRAINT `fk_Article_has_SectionSportive_Article`
    FOREIGN KEY (`Article_art_reference`)
    REFERENCES `mydb`.`Article` (`art_reference`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Article_has_SectionSportive_SectionSportive1`
    FOREIGN KEY (`SectionSportive_idSectionSportive`)
    REFERENCES `mydb`.`SectionSportive` (`idSectionSportive`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_StockArticleSection_DateStock1`
    FOREIGN KEY (`DateStock_idDateStock`)
    REFERENCES `mydb`.`DateStock` (`idDateStock`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`StatutCommande`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`StatutCommande` (
  `idStatutCommande` INT NOT NULL AUTO_INCREMENT,
  `libelle` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idStatutCommande`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Commande`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Commande` (
  `idCommande` INT NOT NULL AUTO_INCREMENT,
  `dateCreation` DATETIME NOT NULL,
  `StatutCommande_idStatutCommande` INT NOT NULL,
  `datePaiement` DATETIME NULL,
  PRIMARY KEY (`idCommande`),
  INDEX `fk_Commande_StatutCommande1_idx` (`StatutCommande_idStatutCommande` ASC),
  CONSTRAINT `fk_Commande_StatutCommande1`
    FOREIGN KEY (`StatutCommande_idStatutCommande`)
    REFERENCES `mydb`.`StatutCommande` (`idStatutCommande`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`LigneCommandeClient`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`LigneCommandeClient` (
  `Membre_idMembre` INT NOT NULL,
  `Commande_idCommande` INT NOT NULL,
  `qteArticle` INT NOT NULL DEFAULT 1,
  `ArticleTaille_id` INT NULL,
  `Article_art_reference` INT NOT NULL,
  PRIMARY KEY (`Membre_idMembre`, `Commande_idCommande`, `Article_art_reference`),
  INDEX `fk_Membre_has_Commande_Commande1_idx` (`Commande_idCommande` ASC),
  INDEX `fk_Membre_has_Commande_Membre1_idx` (`Membre_idMembre` ASC),
  INDEX `fk_LigneCommandeClient_ArticleTaille1_idx` (`ArticleTaille_id` ASC),
  INDEX `fk_LigneCommandeClient_Article1_idx` (`Article_art_reference` ASC),
  CONSTRAINT `fk_Membre_has_Commande_Membre1`
    FOREIGN KEY (`Membre_idMembre`)
    REFERENCES `mydb`.`Membre` (`idMembre`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Membre_has_Commande_Commande1`
    FOREIGN KEY (`Commande_idCommande`)
    REFERENCES `mydb`.`Commande` (`idCommande`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_LigneCommandeClient_ArticleTaille1`
    FOREIGN KEY (`ArticleTaille_id`)
    REFERENCES `mydb`.`ArticleTaille` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_LigneCommandeClient_Article1`
    FOREIGN KEY (`Article_art_reference`)
    REFERENCES `mydb`.`Article` (`art_reference`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Fournisseur`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Fournisseur` (
  `idFournisseur` INT NOT NULL AUTO_INCREMENT,
  `raisonSoc` VARCHAR(100) NOT NULL,
  `telephone` VARCHAR(18) NULL,
  `adresseRue` VARCHAR(255) NULL,
  `adresseCP` INT NULL,
  `adresseVille` VARCHAR(45) NULL,
  PRIMARY KEY (`idFournisseur`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`LigneCommandeFourn`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`LigneCommandeFourn` (
  `Fournisseur_idFournisseur` INT NOT NULL,
  `Commande_idCommande` INT NOT NULL,
  `qteArticle` INT NOT NULL DEFAULT 1,
  `ArticleTaille_id` INT NULL,
  `Article_art_reference` INT NOT NULL,
  PRIMARY KEY (`Fournisseur_idFournisseur`, `Commande_idCommande`, `Article_art_reference`),
  INDEX `fk_Fournisseur_has_Commande_Commande1_idx` (`Commande_idCommande` ASC),
  INDEX `fk_Fournisseur_has_Commande_Fournisseur1_idx` (`Fournisseur_idFournisseur` ASC),
  INDEX `fk_LigneCommandeFourn_ArticleTaille1_idx` (`ArticleTaille_id` ASC),
  INDEX `fk_LigneCommandeFourn_Article1_idx` (`Article_art_reference` ASC),
  CONSTRAINT `fk_Fournisseur_has_Commande_Fournisseur1`
    FOREIGN KEY (`Fournisseur_idFournisseur`)
    REFERENCES `mydb`.`Fournisseur` (`idFournisseur`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Fournisseur_has_Commande_Commande1`
    FOREIGN KEY (`Commande_idCommande`)
    REFERENCES `mydb`.`Commande` (`idCommande`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_LigneCommandeFourn_ArticleTaille1`
    FOREIGN KEY (`ArticleTaille_id`)
    REFERENCES `mydb`.`ArticleTaille` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_LigneCommandeFourn_Article1`
    FOREIGN KEY (`Article_art_reference`)
    REFERENCES `mydb`.`Article` (`art_reference`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;



delete from TypeArticle;
alter table Article AUTO_INCREMENT=0;

insert into TypeArticle(libelle) values ("Chaussures crampon");
insert into TypeArticle(libelle) values ("Haut");
insert into TypeArticle(libelle) values ("Short");

insert into Article(art_designation,art_pu_ht,art_qte_stock,art_tx_tva,stockArticleAutre,TypeArticle_idTypeArticle) values ("Nike air",59,4,20,NULL,1);
insert into Article(art_designation,art_pu_ht,art_qte_stock,art_tx_tva,stockArticleAutre,TypeArticle_idTypeArticle) values ("Adidas",45,2,20,NULL,1);
insert into Article(art_designation,art_pu_ht,art_qte_stock,art_tx_tva,stockArticleAutre,TypeArticle_idTypeArticle) values ("Adidas Originals",80,1,20,NULL,1);
insert into Article(art_designation,art_pu_ht,art_qte_stock,art_tx_tva,stockArticleAutre,TypeArticle_idTypeArticle) values ("Adidas Speedflow",90,0,20,NULL,1);
insert into Article(art_designation,art_pu_ht,art_qte_stock,art_tx_tva,stockArticleAutre,TypeArticle_idTypeArticle) values ("Nike Superfly",99,10,20,NULL,1);

insert into SectionSportive(libelle) values ("Jeunes");
insert into SectionSportive(libelle) values ("Senior");

insert into Niveau(libelle) values ("Départemental");
insert into Niveau(libelle) values ("Régional");
insert into Niveau(libelle) values ("National");

insert into StatutCommande(libelle) values("Initiée");
insert into StatutCommande(libelle) values("En Cours");
insert into StatutCommande(libelle) values("Payée");
insert into StatutCommande(libelle) values("Terminée");

insert into Fournisseur(adresseCP,adresseRue,adresseVille,raisonSoc,telephone) values(68100,"11 passage central","MULHOUSE","ESPACE FOOT","0389591903");
insert into Fournisseur(adresseCP,adresseRue,adresseVille,raisonSoc,telephone) values(68200,"45 Boulevard Charles Stoessel","MULHOUSE","Football Club Mulhouse","");

insert into ArticleTaille(libelle,taillePointure) values("S",NULL);
insert into ArticleTaille(libelle,taillePointure) values("M",NULL);
insert into ArticleTaille(libelle,taillePointure) values("L",NULL);
insert into ArticleTaille(libelle,taillePointure) values("XL",NULL);
insert into ArticleTaille(libelle,taillePointure) values("XXL",NULL);
insert into ArticleTaille(libelle,taillePointure) values(NULL,40);
insert into ArticleTaille(libelle,taillePointure) values(NULL,41);
insert into ArticleTaille(libelle,taillePointure) values(NULL,42);
insert into ArticleTaille(libelle,taillePointure) values(NULL,43);
insert into ArticleTaille(libelle,taillePointure) values(NULL,44);
insert into ArticleTaille(libelle,taillePointure) values(NULL,45);

insert into Membre(dateInscription,dateNaissance,Niveau_id,nom,prenom,SectionSportive_idSectionSportive) values(NOW(),NOW(),1,"CHUPIN","Pierre",1);
insert into Membre(dateInscription,dateNaissance,Niveau_id,nom,prenom,SectionSportive_idSectionSportive) values(NOW(),NOW(),1,"JACQUES","Paul",1);	