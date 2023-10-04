-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 17 fév. 2023 à 21:33
-- Version du serveur : 8.0.31
-- Version de PHP : 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `mydb`
--

-- --------------------------------------------------------

--
-- Structure de la table `article`
--

DROP TABLE IF EXISTS `article`;
CREATE TABLE IF NOT EXISTS `article` (
  `art_reference` int NOT NULL AUTO_INCREMENT,
  `art_designation` varchar(255) NOT NULL,
  `art_pu_ht` int NOT NULL,
  `art_qte_stock` int NOT NULL,
  `art_tx_tva` int DEFAULT NULL,
  `stockArticleAutre` int DEFAULT NULL,
  `TypeArticle_idTypeArticle` int NOT NULL,
  PRIMARY KEY (`art_reference`,`TypeArticle_idTypeArticle`),
  KEY `fk_Article_TypeArticle1_idx` (`TypeArticle_idTypeArticle`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `article`
--

INSERT INTO `article` (`art_reference`, `art_designation`, `art_pu_ht`, `art_qte_stock`, `art_tx_tva`, `stockArticleAutre`, `TypeArticle_idTypeArticle`) VALUES
(1, 'Chaussures de Volley', 150, 10, 20, NULL, 1),
(2, 'Maillot de bain', 50, 15, 20, NULL, 2);

-- --------------------------------------------------------

--
-- Structure de la table `articletaille`
--

DROP TABLE IF EXISTS `articletaille`;
CREATE TABLE IF NOT EXISTS `articletaille` (
  `id` int NOT NULL AUTO_INCREMENT,
  `libelle` varchar(2) DEFAULT NULL,
  `taillePointure` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

DROP TABLE IF EXISTS `commande`;
CREATE TABLE IF NOT EXISTS `commande` (
  `idCommande` int NOT NULL AUTO_INCREMENT,
  `dateCreation` datetime NOT NULL,
  `StatutCommande_idStatutCommande` int NOT NULL,
  `datePaiement` datetime DEFAULT NULL,
  PRIMARY KEY (`idCommande`,`StatutCommande_idStatutCommande`),
  KEY `fk_Commande_StatutCommande1_idx` (`StatutCommande_idStatutCommande`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`idCommande`, `dateCreation`, `StatutCommande_idStatutCommande`, `datePaiement`) VALUES
(1, '2023-02-17 00:00:00', 1, NULL),
(2, '2023-02-17 00:00:00', 1, NULL),
(3, '2023-02-17 00:00:00', 1, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `datestock`
--

DROP TABLE IF EXISTS `datestock`;
CREATE TABLE IF NOT EXISTS `datestock` (
  `idDateStock` int NOT NULL AUTO_INCREMENT,
  `dateSnapshot` datetime NOT NULL,
  PRIMARY KEY (`idDateStock`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `fournisseur`
--

DROP TABLE IF EXISTS `fournisseur`;
CREATE TABLE IF NOT EXISTS `fournisseur` (
  `idFournisseur` int NOT NULL AUTO_INCREMENT,
  `raisonSoc` varchar(100) NOT NULL,
  `telephone` varchar(18) DEFAULT NULL,
  `adresseRue` varchar(255) DEFAULT NULL,
  `adresseCP` int DEFAULT NULL,
  `adresseVille` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idFournisseur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `lignecommandeclient`
--

DROP TABLE IF EXISTS `lignecommandeclient`;
CREATE TABLE IF NOT EXISTS `lignecommandeclient` (
  `Membre_idMembre` int NOT NULL,
  `Commande_idCommande` int NOT NULL,
  `qteArticle` int NOT NULL DEFAULT '1',
  `ArticleTaille_id` int DEFAULT NULL,
  `Article_art_reference` int NOT NULL,
  PRIMARY KEY (`Membre_idMembre`,`Commande_idCommande`,`Article_art_reference`),
  KEY `fk_Membre_has_Commande_Commande1_idx` (`Commande_idCommande`),
  KEY `fk_Membre_has_Commande_Membre1_idx` (`Membre_idMembre`),
  KEY `fk_LigneCommandeClient_ArticleTaille1_idx` (`ArticleTaille_id`),
  KEY `fk_LigneCommandeClient_Article1_idx` (`Article_art_reference`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `lignecommandeclient`
--

INSERT INTO `lignecommandeclient` (`Membre_idMembre`, `Commande_idCommande`, `qteArticle`, `ArticleTaille_id`, `Article_art_reference`) VALUES
(1, 1, 2, NULL, 1),
(1, 1, 20, NULL, 2),
(1, 3, 1, NULL, 2),
(2, 2, 5, NULL, 2);

-- --------------------------------------------------------

--
-- Structure de la table `lignecommandefourn`
--

DROP TABLE IF EXISTS `lignecommandefourn`;
CREATE TABLE IF NOT EXISTS `lignecommandefourn` (
  `Fournisseur_idFournisseur` int NOT NULL,
  `Commande_idCommande` int NOT NULL,
  `qteArticle` int NOT NULL DEFAULT '1',
  `ArticleTaille_id` int DEFAULT NULL,
  `Article_art_reference` int NOT NULL,
  PRIMARY KEY (`Fournisseur_idFournisseur`,`Commande_idCommande`,`Article_art_reference`),
  KEY `fk_Fournisseur_has_Commande_Commande1_idx` (`Commande_idCommande`),
  KEY `fk_Fournisseur_has_Commande_Fournisseur1_idx` (`Fournisseur_idFournisseur`),
  KEY `fk_LigneCommandeFourn_ArticleTaille1_idx` (`ArticleTaille_id`),
  KEY `fk_LigneCommandeFourn_Article1_idx` (`Article_art_reference`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `membre`
--

DROP TABLE IF EXISTS `membre`;
CREATE TABLE IF NOT EXISTS `membre` (
  `idMembre` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `dateNaissance` datetime NOT NULL,
  `dateInscription` datetime DEFAULT NULL,
  `SectionSportive_idSectionSportive` int NOT NULL,
  `Niveau_id` int NOT NULL,
  PRIMARY KEY (`idMembre`,`SectionSportive_idSectionSportive`,`Niveau_id`),
  KEY `fk_Membre_SectionSportive1_idx` (`SectionSportive_idSectionSportive`),
  KEY `fk_Membre_Niveau1_idx` (`Niveau_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `membre`
--

INSERT INTO `membre` (`idMembre`, `nom`, `prenom`, `dateNaissance`, `dateInscription`, `SectionSportive_idSectionSportive`, `Niveau_id`) VALUES
(1, 'Widolff', 'Brice', '2000-02-15 19:14:05', '2022-12-22 17:22:48', 1, 1),
(2, 'Georget', 'Joris', '2000-02-15 19:14:29', '2022-12-22 17:22:48', 2, 3);

-- --------------------------------------------------------

--
-- Structure de la table `niveau`
--

DROP TABLE IF EXISTS `niveau`;
CREATE TABLE IF NOT EXISTS `niveau` (
  `id` int NOT NULL AUTO_INCREMENT,
  `libelle` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `niveau`
--

INSERT INTO `niveau` (`id`, `libelle`) VALUES
(1, 'Poussin'),
(2, 'Junior'),
(3, 'Senior');

-- --------------------------------------------------------

--
-- Structure de la table `sectionsportive`
--

DROP TABLE IF EXISTS `sectionsportive`;
CREATE TABLE IF NOT EXISTS `sectionsportive` (
  `idSectionSportive` int NOT NULL AUTO_INCREMENT,
  `libelle` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idSectionSportive`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `sectionsportive`
--

INSERT INTO `sectionsportive` (`idSectionSportive`, `libelle`) VALUES
(1, 'Volley'),
(2, 'Natation');

-- --------------------------------------------------------

--
-- Structure de la table `statutcommande`
--

DROP TABLE IF EXISTS `statutcommande`;
CREATE TABLE IF NOT EXISTS `statutcommande` (
  `idStatutCommande` int NOT NULL AUTO_INCREMENT,
  `libelle` varchar(45) NOT NULL,
  PRIMARY KEY (`idStatutCommande`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `statutcommande`
--

INSERT INTO `statutcommande` (`idStatutCommande`, `libelle`) VALUES
(1, 'ouverte'),
(2, 'payée'),
(3, 'livrée'),
(4, 'cloturée');

-- --------------------------------------------------------

--
-- Structure de la table `stockarticlesection`
--

DROP TABLE IF EXISTS `stockarticlesection`;
CREATE TABLE IF NOT EXISTS `stockarticlesection` (
  `Article_art_reference` int NOT NULL,
  `SectionSportive_idSectionSportive` int NOT NULL,
  `stockRestant` varchar(45) DEFAULT NULL,
  `DateStock_idDateStock` int NOT NULL,
  PRIMARY KEY (`Article_art_reference`,`SectionSportive_idSectionSportive`,`DateStock_idDateStock`),
  KEY `fk_Article_has_SectionSportive_SectionSportive1_idx` (`SectionSportive_idSectionSportive`),
  KEY `fk_Article_has_SectionSportive_Article_idx` (`Article_art_reference`),
  KEY `fk_StockArticleSection_DateStock1_idx` (`DateStock_idDateStock`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `typearticle`
--

DROP TABLE IF EXISTS `typearticle`;
CREATE TABLE IF NOT EXISTS `typearticle` (
  `idTypeArticle` int NOT NULL,
  `libelle` varchar(90) DEFAULT NULL,
  PRIMARY KEY (`idTypeArticle`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `typearticle`
--

INSERT INTO `typearticle` (`idTypeArticle`, `libelle`) VALUES
(1, 'Chaussures'),
(2, 'Vetements'),
(3, 'Accessoires');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `article`
--
ALTER TABLE `article`
  ADD CONSTRAINT `fk_Article_TypeArticle1` FOREIGN KEY (`TypeArticle_idTypeArticle`) REFERENCES `typearticle` (`idTypeArticle`);

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `fk_Commande_StatutCommande1` FOREIGN KEY (`StatutCommande_idStatutCommande`) REFERENCES `statutcommande` (`idStatutCommande`);

--
-- Contraintes pour la table `lignecommandeclient`
--
ALTER TABLE `lignecommandeclient`
  ADD CONSTRAINT `fk_LigneCommandeClient_Article1` FOREIGN KEY (`Article_art_reference`) REFERENCES `article` (`art_reference`),
  ADD CONSTRAINT `fk_LigneCommandeClient_ArticleTaille1` FOREIGN KEY (`ArticleTaille_id`) REFERENCES `articletaille` (`id`),
  ADD CONSTRAINT `fk_Membre_has_Commande_Commande1` FOREIGN KEY (`Commande_idCommande`) REFERENCES `commande` (`idCommande`),
  ADD CONSTRAINT `fk_Membre_has_Commande_Membre1` FOREIGN KEY (`Membre_idMembre`) REFERENCES `membre` (`idMembre`);

--
-- Contraintes pour la table `lignecommandefourn`
--
ALTER TABLE `lignecommandefourn`
  ADD CONSTRAINT `fk_Fournisseur_has_Commande_Commande1` FOREIGN KEY (`Commande_idCommande`) REFERENCES `commande` (`idCommande`),
  ADD CONSTRAINT `fk_Fournisseur_has_Commande_Fournisseur1` FOREIGN KEY (`Fournisseur_idFournisseur`) REFERENCES `fournisseur` (`idFournisseur`),
  ADD CONSTRAINT `fk_LigneCommandeFourn_Article1` FOREIGN KEY (`Article_art_reference`) REFERENCES `article` (`art_reference`),
  ADD CONSTRAINT `fk_LigneCommandeFourn_ArticleTaille1` FOREIGN KEY (`ArticleTaille_id`) REFERENCES `articletaille` (`id`);

--
-- Contraintes pour la table `membre`
--
ALTER TABLE `membre`
  ADD CONSTRAINT `fk_Membre_Niveau1` FOREIGN KEY (`Niveau_id`) REFERENCES `niveau` (`id`),
  ADD CONSTRAINT `fk_Membre_SectionSportive1` FOREIGN KEY (`SectionSportive_idSectionSportive`) REFERENCES `sectionsportive` (`idSectionSportive`);

--
-- Contraintes pour la table `stockarticlesection`
--
ALTER TABLE `stockarticlesection`
  ADD CONSTRAINT `fk_Article_has_SectionSportive_Article` FOREIGN KEY (`Article_art_reference`) REFERENCES `article` (`art_reference`),
  ADD CONSTRAINT `fk_Article_has_SectionSportive_SectionSportive1` FOREIGN KEY (`SectionSportive_idSectionSportive`) REFERENCES `sectionsportive` (`idSectionSportive`),
  ADD CONSTRAINT `fk_StockArticleSection_DateStock1` FOREIGN KEY (`DateStock_idDateStock`) REFERENCES `datestock` (`idDateStock`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
