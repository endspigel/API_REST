-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 31 mars 2023 à 18:53
-- Version du serveur : 5.7.36
-- Version de PHP : 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `api-auth`
--

-- --------------------------------------------------------

--
-- Structure de la table `article`
--

DROP TABLE IF EXISTS `article`;
CREATE TABLE IF NOT EXISTS `article` (
  `id_article` varchar(50) NOT NULL,
  `date_publication` date DEFAULT NULL,
  `contenu` varchar(50) DEFAULT NULL,
  `login` varchar(50) NOT NULL,
  PRIMARY KEY (`id_article`),
  KEY `login` (`login`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `article`
--

INSERT INTO `article` (`id_article`, `date_publication`, `contenu`, `login`) VALUES
('ART01', '2022-01-01', 'Spiderman saves the day again', 'spiderman'),
('ART02', '2022-02-01', 'Deadpool causes chaos in the city', 'deadpool'),
('ART03', '2022-02-15', 'Batman foils the Joker\'s latest scheme', 'batman'),
('ART04', '2022-03-01', 'Citizen1\'s guide to surviving the apocalypse', 'citizen1');

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

DROP TABLE IF EXISTS `role`;
CREATE TABLE IF NOT EXISTS `role` (
  `id` varchar(50) NOT NULL,
  `nom` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `login` varchar(50) NOT NULL,
  `mdp` varchar(50) DEFAULT NULL,
  `id` varchar(50) NOT NULL,
  PRIMARY KEY (`login`),
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`login`, `mdp`, `id`) VALUES
('deadpool', 'chimichanga', 'ROLE02'),
('spiderman', 'webslinger', 'ROLE01'),
('batman', 'darkknight', 'ROLE01'),
('joker', 'haHaHa', 'ROLE02'),
('citizen1', 'password', 'ROLE02');

-- --------------------------------------------------------

--
-- Structure de la table `voter`
--

DROP TABLE IF EXISTS `voter`;
CREATE TABLE IF NOT EXISTS `voter` (
  `id_article` varchar(50) NOT NULL,
  `login` varchar(50) NOT NULL,
  `is_like` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id_article`,`login`),
  KEY `login` (`login`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `voter`
--

INSERT INTO `voter` (`id_article`, `login`, `is_like`) VALUES
('ART01', 'spiderman', 1),
('ART02', 'deadpool', 1),
('ART02', 'citizen1', 0),
('ART03', 'batman', 1),
('ART03', 'joker', 0),
('ART04', 'citizen1', 1),
('ART04', 'batman', 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
