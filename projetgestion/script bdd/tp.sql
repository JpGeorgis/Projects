-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 16 fév. 2023 à 13:50
-- Version du serveur : 5.7.36
-- Version de PHP : 8.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `tp`
--

-- --------------------------------------------------------

--
-- Structure de la table `article`
--

DROP TABLE IF EXISTS `article`;
CREATE TABLE IF NOT EXISTS `article` (
  `art_reference` int(11) NOT NULL AUTO_INCREMENT,
  `art_designation` varchar(255) NOT NULL,
  `art_pu_ht` int(11) NOT NULL,
  `art_qte_stock` int(11) NOT NULL,
  `art_tx_tva` int(11) DEFAULT NULL,
  `stockArticleAutre` int(11) DEFAULT NULL,
  `TypeArticle_idTypeArticle` int(11) NOT NULL,
  PRIMARY KEY (`art_reference`,`TypeArticle_idTypeArticle`),
  KEY `fk_Article_TypeArticle1_idx` (`TypeArticle_idTypeArticle`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `article`
--

INSERT INTO `article` (`art_reference`, `art_designation`, `art_pu_ht`, `art_qte_stock`, `art_tx_tva`, `stockArticleAutre`, `TypeArticle_idTypeArticle`) VALUES
(2, 'Raquette de tennis', 10, 10, 12, NULL, 1);

-- --------------------------------------------------------

--
-- Structure de la table `articletaille`
--

DROP TABLE IF EXISTS `articletaille`;
CREATE TABLE IF NOT EXISTS `articletaille` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(2) DEFAULT NULL,
  `taillePointure` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `articletaille`
--

INSERT INTO `articletaille` (`id`, `libelle`, `taillePointure`) VALUES
(1, 'M', 35);

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

DROP TABLE IF EXISTS `commande`;
CREATE TABLE IF NOT EXISTS `commande` (
  `idCommandeFourn` int(11) NOT NULL AUTO_INCREMENT,
  `dateCreation` datetime NOT NULL,
  `StatutCommande_idStatutCommande` int(11) NOT NULL,
  `datePaiement` datetime DEFAULT NULL,
  PRIMARY KEY (`idCommandeFourn`,`StatutCommande_idStatutCommande`),
  KEY `fk_Commande_StatutCommande1_idx` (`StatutCommande_idStatutCommande`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`idCommandeFourn`, `dateCreation`, `StatutCommande_idStatutCommande`, `datePaiement`) VALUES
(1, '2023-02-16 13:05:47', 1, '2023-02-16 13:05:47');

-- --------------------------------------------------------

--
-- Structure de la table `datestock`
--

DROP TABLE IF EXISTS `datestock`;
CREATE TABLE IF NOT EXISTS `datestock` (
  `idDateStock` int(11) NOT NULL AUTO_INCREMENT,
  `dateSnapshot` datetime NOT NULL,
  PRIMARY KEY (`idDateStock`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `fournisseur`
--

DROP TABLE IF EXISTS `fournisseur`;
CREATE TABLE IF NOT EXISTS `fournisseur` (
  `idFournisseur` int(11) NOT NULL AUTO_INCREMENT,
  `raisonSoc` varchar(100) NOT NULL,
  `telephone` varchar(18) DEFAULT NULL,
  `adresseRue` varchar(255) DEFAULT NULL,
  `adresseCP` int(11) DEFAULT NULL,
  `adresseVille` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idFournisseur`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `fournisseur`
--

INSERT INTO `fournisseur` (`idFournisseur`, `raisonSoc`, `telephone`, `adresseRue`, `adresseCP`, `adresseVille`) VALUES
(1, 'Chef', '0389452389', '14 place de la fontaine', 68100, 'JeSaisPas');

-- --------------------------------------------------------

--
-- Structure de la table `lignecommandeclient`
--

DROP TABLE IF EXISTS `lignecommandeclient`;
CREATE TABLE IF NOT EXISTS `lignecommandeclient` (
  `Membre_idMembre` int(11) NOT NULL,
  `Commande_idCommandeFourn` int(11) NOT NULL,
  `qteArticle` int(11) NOT NULL DEFAULT '1',
  `ArticleTaille_id` int(11) DEFAULT NULL,
  `Article_art_reference` int(11) NOT NULL,
  PRIMARY KEY (`Membre_idMembre`,`Commande_idCommandeFourn`,`Article_art_reference`),
  KEY `fk_Membre_has_Commande_Commande1_idx` (`Commande_idCommandeFourn`),
  KEY `fk_Membre_has_Commande_Membre1_idx` (`Membre_idMembre`),
  KEY `fk_LigneCommandeClient_ArticleTaille1_idx` (`ArticleTaille_id`),
  KEY `fk_LigneCommandeClient_Article1_idx` (`Article_art_reference`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `lignecommandeclient`
--

INSERT INTO `lignecommandeclient` (`Membre_idMembre`, `Commande_idCommandeFourn`, `qteArticle`, `ArticleTaille_id`, `Article_art_reference`) VALUES
(2, 1, 2, 1, 2);

-- --------------------------------------------------------

--
-- Structure de la table `lignecommandefourn`
--

DROP TABLE IF EXISTS `lignecommandefourn`;
CREATE TABLE IF NOT EXISTS `lignecommandefourn` (
  `Fournisseur_idFournisseur` int(11) NOT NULL,
  `Commande_idCommandeFourn` int(11) NOT NULL,
  `qteArticle` int(11) NOT NULL DEFAULT '1',
  `ArticleTaille_id` int(11) DEFAULT NULL,
  `Article_art_reference` int(11) NOT NULL,
  PRIMARY KEY (`Fournisseur_idFournisseur`,`Commande_idCommandeFourn`,`Article_art_reference`),
  KEY `fk_Fournisseur_has_Commande_Commande1_idx` (`Commande_idCommandeFourn`),
  KEY `fk_Fournisseur_has_Commande_Fournisseur1_idx` (`Fournisseur_idFournisseur`),
  KEY `fk_LigneCommandeFourn_ArticleTaille1_idx` (`ArticleTaille_id`),
  KEY `fk_LigneCommandeFourn_Article1_idx` (`Article_art_reference`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `lignecommandefourn`
--

INSERT INTO `lignecommandefourn` (`Fournisseur_idFournisseur`, `Commande_idCommandeFourn`, `qteArticle`, `ArticleTaille_id`, `Article_art_reference`) VALUES
(1, 1, 2, 1, 2);

-- --------------------------------------------------------

--
-- Structure de la table `membre`
--

DROP TABLE IF EXISTS `membre`;
CREATE TABLE IF NOT EXISTS `membre` (
  `idMembre` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `dateNaissance` datetime NOT NULL,
  `dateInscription` datetime DEFAULT NULL,
  `SectionSportive_idSectionSportive` int(11) NOT NULL,
  `Niveau_id` int(11) NOT NULL,
  PRIMARY KEY (`idMembre`,`SectionSportive_idSectionSportive`,`Niveau_id`),
  KEY `fk_Membre_SectionSportive1_idx` (`SectionSportive_idSectionSportive`),
  KEY `fk_Membre_Niveau1_idx` (`Niveau_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `membre`
--

INSERT INTO `membre` (`idMembre`, `nom`, `prenom`, `dateNaissance`, `dateInscription`, `SectionSportive_idSectionSportive`, `Niveau_id`) VALUES
(2, 'Pierre', 'Jean', '2023-02-16 12:44:20', '2023-02-16 12:44:20', 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `niveau`
--

DROP TABLE IF EXISTS `niveau`;
CREATE TABLE IF NOT EXISTS `niveau` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `niveau`
--

INSERT INTO `niveau` (`id`, `libelle`) VALUES
(1, 'Coach');

-- --------------------------------------------------------

--
-- Structure de la table `sectionsportive`
--

DROP TABLE IF EXISTS `sectionsportive`;
CREATE TABLE IF NOT EXISTS `sectionsportive` (
  `idSectionSportive` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idSectionSportive`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `sectionsportive`
--

INSERT INTO `sectionsportive` (`idSectionSportive`, `libelle`) VALUES
(1, 'Tennis');

-- --------------------------------------------------------

--
-- Structure de la table `statutcommande`
--

DROP TABLE IF EXISTS `statutcommande`;
CREATE TABLE IF NOT EXISTS `statutcommande` (
  `idStatutCommande` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(45) NOT NULL,
  PRIMARY KEY (`idStatutCommande`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `statutcommande`
--

INSERT INTO `statutcommande` (`idStatutCommande`, `libelle`) VALUES
(1, 'En cours'),
(2, 'Payée'),
(3, 'Livrée'),
(4, 'Clôturée');

-- --------------------------------------------------------

--
-- Structure de la table `stockarticlesection`
--

DROP TABLE IF EXISTS `stockarticlesection`;
CREATE TABLE IF NOT EXISTS `stockarticlesection` (
  `Article_art_reference` int(11) NOT NULL,
  `SectionSportive_idSectionSportive` int(11) NOT NULL,
  `stockRestant` varchar(45) DEFAULT NULL,
  `DateStock_idDateStock` int(11) NOT NULL,
  PRIMARY KEY (`Article_art_reference`,`SectionSportive_idSectionSportive`,`DateStock_idDateStock`),
  KEY `fk_Article_has_SectionSportive_SectionSportive1_idx` (`SectionSportive_idSectionSportive`),
  KEY `fk_Article_has_SectionSportive_Article_idx` (`Article_art_reference`),
  KEY `fk_StockArticleSection_DateStock1_idx` (`DateStock_idDateStock`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `typearticle`
--

DROP TABLE IF EXISTS `typearticle`;
CREATE TABLE IF NOT EXISTS `typearticle` (
  `idTypeArticle` int(11) NOT NULL,
  `libelle` varchar(90) DEFAULT NULL,
  PRIMARY KEY (`idTypeArticle`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `typearticle`
--

INSERT INTO `typearticle` (`idTypeArticle`, `libelle`) VALUES
(1, 'Tennis');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `article`
--
ALTER TABLE `article`
  ADD CONSTRAINT `fk_Article_TypeArticle1` FOREIGN KEY (`TypeArticle_idTypeArticle`) REFERENCES `typearticle` (`idTypeArticle`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `fk_Commande_StatutCommande1` FOREIGN KEY (`StatutCommande_idStatutCommande`) REFERENCES `statutcommande` (`idStatutCommande`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `lignecommandeclient`
--
ALTER TABLE `lignecommandeclient`
  ADD CONSTRAINT `fk_LigneCommandeClient_Article1` FOREIGN KEY (`Article_art_reference`) REFERENCES `article` (`art_reference`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_LigneCommandeClient_ArticleTaille1` FOREIGN KEY (`ArticleTaille_id`) REFERENCES `articletaille` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Membre_has_Commande_Commande1` FOREIGN KEY (`Commande_idCommandeFourn`) REFERENCES `commande` (`idCommandeFourn`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Membre_has_Commande_Membre1` FOREIGN KEY (`Membre_idMembre`) REFERENCES `membre` (`idMembre`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `lignecommandefourn`
--
ALTER TABLE `lignecommandefourn`
  ADD CONSTRAINT `fk_Fournisseur_has_Commande_Commande1` FOREIGN KEY (`Commande_idCommandeFourn`) REFERENCES `commande` (`idCommandeFourn`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Fournisseur_has_Commande_Fournisseur1` FOREIGN KEY (`Fournisseur_idFournisseur`) REFERENCES `fournisseur` (`idFournisseur`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_LigneCommandeFourn_Article1` FOREIGN KEY (`Article_art_reference`) REFERENCES `article` (`art_reference`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_LigneCommandeFourn_ArticleTaille1` FOREIGN KEY (`ArticleTaille_id`) REFERENCES `articletaille` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `membre`
--
ALTER TABLE `membre`
  ADD CONSTRAINT `fk_Membre_Niveau1` FOREIGN KEY (`Niveau_id`) REFERENCES `niveau` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Membre_SectionSportive1` FOREIGN KEY (`SectionSportive_idSectionSportive`) REFERENCES `sectionsportive` (`idSectionSportive`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `stockarticlesection`
--
ALTER TABLE `stockarticlesection`
  ADD CONSTRAINT `fk_Article_has_SectionSportive_Article` FOREIGN KEY (`Article_art_reference`) REFERENCES `article` (`art_reference`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Article_has_SectionSportive_SectionSportive1` FOREIGN KEY (`SectionSportive_idSectionSportive`) REFERENCES `sectionsportive` (`idSectionSportive`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_StockArticleSection_DateStock1` FOREIGN KEY (`DateStock_idDateStock`) REFERENCES `datestock` (`idDateStock`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
