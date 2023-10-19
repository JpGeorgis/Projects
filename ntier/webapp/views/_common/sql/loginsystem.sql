-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : sam. 08 avr. 2023 à 19:52
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
-- Base de données : `loginsystem`
--

-- --------------------------------------------------------

--
-- Structure de la table `class`
--

DROP TABLE IF EXISTS `class`;
CREATE TABLE IF NOT EXISTS `class` (
  `class_id` int NOT NULL AUTO_INCREMENT,
  `Designation` varchar(100) NOT NULL,
  `Num` int NOT NULL,
  PRIMARY KEY (`class_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `class`
--

INSERT INTO `class` (`class_id`, `Designation`, `Num`) VALUES
(1, 'MIAGE', 2),
(2, 'IM', 2),
(5, 'ECO', 0);

-- --------------------------------------------------------

--
-- Structure de la table `courses`
--

DROP TABLE IF EXISTS `courses`;
CREATE TABLE IF NOT EXISTS `courses` (
  `course_id` int NOT NULL AUTO_INCREMENT,
  `course_name` varchar(255) NOT NULL,
  `course_description` text NOT NULL,
  `teacher` int NOT NULL,
  `classes` varchar(100) NOT NULL,
  PRIMARY KEY (`course_id`),
  KEY `teacher` (`teacher`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `courses`
--

INSERT INTO `courses` (`course_id`, `course_name`, `course_description`, `teacher`, `classes`) VALUES
(1, 'Informatique pratique 2', 'blabla', 5, 'a:2:{i:1;s:5:\"MIAGE\";i:2;s:2:\"IM\";}'),
(2, 'Maths', 'Maths maths', 5, 'a:1:{i:1;s:5:\"MIAGE\";}'),
(27, 'Français', 'bla bla', 5, 'a:1:{i:1;s:7:\"MIAGE 2\";}'),
(15, 'Algorithmie', 'bla bla', 5, 'a:1:{i:1;s:5:\"MIAGE\";}');

-- --------------------------------------------------------

--
-- Structure de la table `enrollments`
--

DROP TABLE IF EXISTS `enrollments`;
CREATE TABLE IF NOT EXISTS `enrollments` (
  `enrollment_id` int NOT NULL AUTO_INCREMENT,
  `class_id` int NOT NULL,
  `course_id` int NOT NULL,
  PRIMARY KEY (`enrollment_id`),
  KEY `student_id` (`class_id`),
  KEY `course_id` (`course_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `enrollments`
--

INSERT INTO `enrollments` (`enrollment_id`, `class_id`, `course_id`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` enum('student','admin','teacher') NOT NULL,
  `class` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `password`, `name`, `type`, `class`) VALUES
(1, 'bombadu68@gmail.com', '$2y$10$4uGUwCWzzKBqXH9oflvPSOxcG4hdfXXmbBY/LrqlU.2KyH5rcj4xG', 'Admin', 'admin', NULL),
(4, 'jorisgeorget@gmail.com', '$2y$10$SozWQ3fksefaNbcbMCn8keOP9nCkcebkVw3zaQUR/ZxcyaKaRkV9q', 'Georget Joris 2', 'student', 2),
(5, 'william@gmail.com', '$2y$10$egoLk8.NibLte3f9D65y/.aQL6wEn7eqtwDVbQA3.ODzXkqVr.PsO', 'William Longside 2', 'teacher', NULL),
(6, 'bricewidolff@gmail.com', '$2y$10$4uGUwCWzzKBqXH9oflvPSOxcG4hdfXXmbBY/LrqlU.2KyH5rcj4xG', 'Brice Widolff 2', 'student', 2),
(28, 'jorisgeorget@gmail.com', '$2y$10$YHDD.4R7KVqFBUPT2vgosuvXGmG61quXUPpeABLM8FITWPhUWHEhK', 'Informatique pratique', 'student', 1),
(18, 'jorisgeorget@gmail.com', '$2y$10$sDbdiCnmJp4366XPRxOJquK8kn.XCc5gpXp3jT7FUfv4fbAtwMR2S', 'Georget Joris ', 'teacher', NULL),
(29, 'jorisgeorget@gmail.com', '$2y$10$cXJfGuqUHVijO2w1XhQMrOit9Fd83DEjSy25ZcPhlE413PbopcIUO', 'Informatique pratique', 'student', 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
