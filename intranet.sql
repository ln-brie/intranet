-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  jeu. 15 avr. 2021 à 07:41
-- Version du serveur :  10.4.10-MariaDB
-- Version de PHP :  7.4.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `intranet`
--

-- --------------------------------------------------------

--
-- Structure de la table `emplacement`
--

DROP TABLE IF EXISTS `emplacement`;
CREATE TABLE IF NOT EXISTS `emplacement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `service` varchar(50) NOT NULL,
  `section` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `emplacement`
--

INSERT INTO `emplacement` (`id`, `service`, `section`) VALUES
(1, 'direction', 'memo'),
(2, 'ressourcesh', 'memo'),
(3, 'communication', 'memo'),
(4, 'secuenv', 'memo'),
(5, 'qualite', 'memo'),
(6, 'informatique', 'memo'),
(7, 'plansprod', 'memo'),
(8, 'comptabilite', 'memo'),
(9, 'logistique', 'memo'),
(10, 'achats', 'memo'),
(11, 'home', 'annuaires'),
(12, 'home', 'video1'),
(13, 'home', 'video2'),
(14, 'home', 'video3'),
(15, 'home', 'video4'),
(16, 'direction', 'lorem ipsum'),
(17, 'direction', 'en construction'),
(18, 'galerie', 'images'),
(19, 'applications', 'appli1'),
(20, 'applications', 'appli2'),
(21, 'communication', 'en construction'),
(22, 'informatique', 'infos réseau'),
(23, 'qualite', 'en construction'),
(24, 'plansprod', 'en construction'),
(25, 'comptabilite', 'en construction'),
(26, 'applications', 'appli3'),
(27, 'applications', 'appli4');

-- --------------------------------------------------------

--
-- Structure de la table `post`
--

DROP TABLE IF EXISTS `post`;
CREATE TABLE IF NOT EXISTS `post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(50) NOT NULL,
  `contenu` mediumtext NOT NULL,
  `exclu` tinyint(1) NOT NULL DEFAULT 0,
  `date_ajout` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_update` timestamp NULL DEFAULT NULL,
  `home` tinyint(1) NOT NULL DEFAULT 0,
  `diffusion` tinyint(1) NOT NULL DEFAULT 0,
  `brouillon` tinyint(1) NOT NULL DEFAULT 0,
  `id_emplacement` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `Post_emplacement_FK` (`id_emplacement`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `post`
--

INSERT INTO `post` (`id`, `titre`, `contenu`, `exclu`, `date_ajout`, `date_update`, `home`, `diffusion`, `brouillon`, `id_emplacement`) VALUES
(1, 'Notes', '<p>La direction vous souhaite de bonnes vacances<br></p>', 0, '2021-04-11 22:00:00', '2021-04-11 22:00:00', 0, 0, 0, 1),
(2, 'Notes', 'Notes du service ressources humaines', 1, '2021-04-11 22:00:00', NULL, 0, 0, 0, 2),
(3, 'Notes', 'Notes du service communication', 1, '2021-04-11 22:00:00', NULL, 0, 0, 0, 3),
(4, 'Notes', 'Notes du service sécurité et environnement', 1, '2021-04-11 22:00:00', NULL, 0, 0, 0, 4),
(5, 'Notes', 'Notes du service qualité', 1, '2021-04-11 22:00:00', NULL, 0, 0, 0, 5),
(6, 'Notes', 'Notes du service informatique', 1, '2021-04-11 22:00:00', NULL, 0, 0, 0, 6),
(7, 'Notes', 'Notes du service achats', 1, '2021-04-11 22:00:00', NULL, 0, 0, 0, 10),
(8, 'Notes', 'Notes du service plans produits', 1, '2021-04-11 22:00:00', NULL, 0, 0, 0, 7),
(9, 'Notes', 'Notes du service logistique', 1, '2021-04-11 22:00:00', NULL, 0, 0, 0, 9),
(10, 'Notes', 'Notes du service comptabilité', 1, '2021-04-11 22:00:00', NULL, 0, 0, 0, 8),
(11, 'Lorem ipsum', '<p><br><strong>Cumque pertinacius ut legum gnarus accusatorem flagitaret atque \r\nsollemnia, doctus id Caesar libertatemque superbiam ratus tamquam \r\nobtrectatorem audacem excarnificari praecepit<br></strong></p>', 0, '2021-04-11 22:00:00', '2021-04-12 22:00:00', 0, 1, 0, 16),
(12, 'lorem', '<p>ipsum</p><p><br></p><p><a href=\"docs/programme-formation-developpeur-web-a-distance-cefii.pdf\" target=\"_blank\">fichier joint</a><br></p>', 0, '2021-04-11 22:00:00', '2021-04-11 22:00:00', 0, 1, 0, 17),
(14, 'Lorem ipsum', 'https://cdn.pixabay.com/photo/2021/04/07/05/56/woman-6158131_960_720.jpg', 0, '2021-04-11 22:00:00', NULL, 0, 0, 0, 18),
(15, 'Documentation en ligne', 'https://help.salesforce.com/articleView?id=sf.salesforce_help_map.htm&type=5', 0, '2021-04-11 22:00:00', NULL, 0, 0, 0, 20),
(19, 'clavier', 'videos/Keyboard - 10822.mp4', 0, '2021-04-11 22:00:00', NULL, 0, 0, 0, 15),
(20, 'Lorem ipsum', '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. </p>', 0, '2021-04-12 22:00:00', NULL, 0, 0, 0, 22),
(21, 'Annuaire ARTS', 'https://www.google.fr', 0, '2021-04-12 22:00:00', NULL, 0, 0, 0, 11),
(22, 'Annuaire 2', 'https://www.google.fr', 0, '2021-04-12 22:00:00', NULL, 0, 0, 0, 11),
(23, 'Information 1', '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. </p>', 1, '2021-04-12 22:00:00', '2021-04-12 22:00:00', 1, 0, 0, 23),
(24, 'informations plans produits', '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua </p>', 0, '2021-04-12 22:00:00', '2021-04-12 22:00:00', 1, 0, 0, 24),
(25, 'info compta', '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. </p>', 0, '2021-04-13 10:19:33', NULL, 0, 0, 0, 25),
(26, 'Information importante', '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr<span style=\"background-color: rgb(192, 80, 77);\"><strong>, sed diam nonumy eirmod </strong></span>tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. </p>', 0, '2021-04-13 10:24:59', NULL, 0, 0, 0, 16),
(27, 'cathedrale', 'img/gallery/cathedral-5965374_640.jpg', 0, '2021-04-15 07:39:15', NULL, 0, 0, 0, 18);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `Post_emplacement_FK` FOREIGN KEY (`id_emplacement`) REFERENCES `emplacement` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
