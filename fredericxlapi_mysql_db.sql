-- phpMyAdmin SQL Dump
-- version OVH
-- https://www.phpmyadmin.net/
--
-- Hôte : fredericxlapi.mysql.db
-- Généré le : mer. 18 mai 2022 à 12:15
-- Version du serveur : 5.6.50-log
-- Version de PHP : 7.4.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `fredericxlapi`
--
CREATE DATABASE IF NOT EXISTS `fredericxlapi` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `fredericxlapi`;

-- --------------------------------------------------------

--
-- Structure de la table `customer`
--

CREATE TABLE `customer` (
  `id` int(11) NOT NULL,
  `login` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `society` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `customer`
--

INSERT INTO `customer` (`id`, `login`, `password`, `society`, `roles`) VALUES
(9, 'orange', '$2y$13$cjGjQaBAJX8zHuZp6rBt7.4lcgMl2HOLo1yx64/MYe2Eil2QjZteC', 'orange', NULL),
(10, 'pdgSfr', '$2y$13$r7L9GN7RtPyajWegypSMdubCH79Q2ua2xOVPp.CUkxEC3DF0acbTe', 'sfr', NULL),
(11, 'admin', '$2y$13$onqoAzNvSp6YGiP1UDtE1eFs7rEqe1MdbNUd7r17EZWVN7os2p2IS', 'admin', 'admin'),
(12, 'serviceBouygues', '$2y$13$vI9PYV7LJl618LNR/qQEHe.1DCCvuXtIe7tn7iRnWXZdGgmnVUYS6', 'bouygues', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `migration_versions`
--

CREATE TABLE `migration_versions` (
  `version` varchar(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `executed_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migration_versions`
--

INSERT INTO `migration_versions` (`version`, `executed_at`) VALUES
('20200124180048', '2020-01-24 18:01:01'),
('20200215231004', '2020-02-15 23:10:20'),
('20200215231605', '2020-02-15 23:16:16'),
('20200218154115', '2020-02-18 15:41:37'),
('20200219151146', '2020-02-19 15:11:59'),
('20200219151428', '2020-02-19 15:14:33');

-- --------------------------------------------------------

--
-- Structure de la table `phone`
--

CREATE TABLE `phone` (
  `id` int(11) NOT NULL,
  `price` double NOT NULL,
  `das` double DEFAULT NULL,
  `model` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `release_date` datetime DEFAULT NULL,
  `screen_size` double NOT NULL,
  `connexion_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `width` double NOT NULL,
  `height` double NOT NULL,
  `thickness` double DEFAULT NULL,
  `weight` double NOT NULL,
  `pixels_x` int(11) NOT NULL,
  `pixels_y` int(11) NOT NULL,
  `cameras_specifications` longtext COLLATE utf8mb4_unicode_ci,
  `battery_time` double NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `phone`
--

INSERT INTO `phone` (`id`, `price`, `das`, `model`, `created_at`, `release_date`, `screen_size`, `connexion_type`, `width`, `height`, `thickness`, `weight`, `pixels_x`, `pixels_y`, `cameras_specifications`, `battery_time`, `description`, `color`) VALUES
(1, 1380, 0.85, 'big phone 4', '2020-01-24 19:23:09', '2019-11-07 19:23:09', 9.4, '5G', 9.5, 17.3, 0.83, 243, 1542, 2739, '3 appareils : 13MPx, 20MPx, 8MPx. Vidéo : 60Hz, 4K.', 8.4, 'à mi chemin entre le smartphone et la tablette, vous êtes sur le gros modèle de notre marque.', NULL),
(2, 888, 0.9, 'light X', '2020-01-24 19:29:34', '2020-01-29 19:29:34', 4.9, '5G', 7.2, 13, 0.81, 149, 1024, 1720, '1 appareil, 21MPx, vidéo Full HD', 8.2, 'Le petit modèle, peu encombrant, ne fatigue pas les articulations du poignet.', NULL),
(4, 1200, 1, 'x8', '2020-02-27 16:16:29', '2020-10-10 00:00:00', 10, '5G', 9, 16, 0.81, 230, 1080, 2048, '3 cameras', 8, 'le prochain modèle', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `firstname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lastname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `customer_id`, `firstname`, `lastname`, `mail`, `password`) VALUES
(4, 9, NULL, NULL, 'mail@orange.fr', '$2y$13$ktELwkBEiUZRkVxXS830CevSvXYtu02rIzIqThH65SZvKfDujzhqa'),
(5, 10, NULL, NULL, 'mail@sfr.fr', '$2y$13$EklzZwJ.wGyMZvdj.HJmx.k1ON3hsElDzsflKJeVUxgT9WSwCSPbq'),
(6, 10, 'machin', 'machinmachin', 'machin2@sfr.fr', '$2y$13$Z0Z/yg2HTyblk/FZVdPwJ.ywBR7iiYFtNDZm8X7fxWBfdeqUaoYIq'),
(8, 10, 'unprenom', 'unnom', 'un@email.fr', '$2y$13$X3AKHoc29Tcf16XplA8qzuXKvSrkPcFxraJ3PJK10oAFv0NUXVBv.'),
(9, 10, 'mdp', 'encode', 'mdp@encode.com', '$2y$13$iXRpWi2iTuV6CxyZtfLTLeMsEUpVrN2/tAHT9hu/UGsuOBtRmYiGO'),
(13, 10, 'Frédéric', 'Malard', 'fred@mail.com', '$2y$13$4.eVrh7o09R4VYw31ysEB.VlnZPCxH0TLPdUxuf/mH/QJnPQ4PVca'),
(19, 10, 'Abra', 'Racourcix', 'abraracourcix@mail.com', '$2y$13$Z3bDEAovefR09N1B7n7FxOv3tKG4hmF87f5zB.f3nRI0/6yFgMgHS'),
(20, 10, 'Pano', 'Ramix', 'panoramix@mail.com', '$2y$13$xbZuwumHEaOdmArAyfBAq.6j9RpJXmxXCV7rbQ1T6Va298tAtAzIq'),
(21, 10, 'Idee', 'Fixe', 'ideefixe@mail.com', '$2y$13$IZGkaI.6x1fY.MyoOO64Tu3rzM9X6IZr7ZE4B2cEn7syaxFnrEZtu'),
(22, 10, 'amon', 'beaufils', 'amonbeaufils@mail.com', '$2y$13$BzX6bSwiX1C1t.87flbWAuLIZal7oaw9fdueLAjapljs3E5Wq76k6'),
(23, 10, 'assurance', 'tousrix', 'assurancetousrix@mail.com', '$2y$13$ADDUgwwXgClpTd.HaOIlO.7BgAH0Spbyjtm2gkDQnhDs1nQ/h3n0.'),
(24, 10, 'caius', 'bonus', 'caiusbonus@mail.com', '$2y$13$XNUhObDprBrPD6kdluLs9.19cpvZwDL95C/L2DdCELINeGKkPVOky'),
(25, 10, 'age', 'canonix', 'agecanonix@mail.com', '$2y$13$1ghK4bj9Vb2P2Zu.0K.Xa.BUQfjnwYQPghk1sGpQI.Riw5Rscr6SC'),
(26, 10, 'obe', 'lix', 'obelix@mail.com', '$2y$13$ytuiFSZRYh8ScZkddgF6/.8afjp56eftCquZWQyyapxGO2o4M2b2.'),
(27, 10, 'Tintin', 'Reporter', 'tintinReporter@mail.com', '$2y$13$95rjhRn8tITkQcloFiuibecEQNPoX1a5QbKOHEPnyI2EKtYY8jDnm'),
(28, 10, 'Capitaine', 'Hadock', 'capitainehadock@mail.com', '$2y$13$9CTIwXF/ijv4YdJfviB8WenROn8dycs9S7UuCA.Ko7cHawpLNDrai'),
(29, 10, 'Professeur', 'Tournesol', 'tournesol@mail.com', '$2y$13$lyb1iOshVGQoRY0Vy/FlOuRlHuocFTwQYNXwqbTMKmcvFN0ozSzCO'),
(30, 10, 'Nes', 'Tor', 'nestor@mail.com', '$2y$13$pOkq7UYhm3ogURaDaGMdAeMcZZiWwT6cgc1F7f/kqBo27KyQDUF/.'),
(31, 10, 'Abrico', 'Tier', 'abricotier@mail.com', '$2y$13$4UN3qFFP9OvNTOYLIx.8u.LORLEorXFl.3td5g2Syotn9Hl8bqTva'),
(32, 10, 'Pample', 'Mousse', 'pamplemousse@mail.com', '$2y$13$pLnt.d/Q41AJIFZ7OC/cmeFVJ4G8fkEhBpmHbrxTTKfPHqBOXMQPC'),
(33, 10, 'Or', 'Ange', 'orange@mail.com', '$2y$13$s4TSVS9aUlYz12HihLuLqOVWKVBs1hsTEhQbtPSRcipDnXBN6OI1y'),
(34, 10, 'Po', 'Mme', 'pomme@mail.com', '$2y$13$Gcbyoznc6b1tKRAgrrZH3ebJs/cf1HAfox1J0P.LfTlgMP8Nolvau'),
(35, 10, 'Frédéric', 'Malard', 'fredABCD@mail.com', '$2y$13$O0.nltBJP3H32LRRoNVFr.ISdb/n/s0GMxt4m9/yG7gTfcROtcwqK');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `migration_versions`
--
ALTER TABLE `migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `phone`
--
ALTER TABLE `phone`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_8D93D6499395C3F3` (`customer_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `phone`
--
ALTER TABLE `phone`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FK_8D93D6499395C3F3` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
