-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : lun. 07 avr. 2025 à 08:09
-- Version du serveur : 8.0.41-0ubuntu0.22.04.1
-- Version de PHP : 8.3.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `game`
--

-- --------------------------------------------------------

--
-- Structure de la table `game`
--

CREATE TABLE `game` (
  `id_game` int NOT NULL,
  `player_white` int DEFAULT NULL,
  `player_black` int DEFAULT NULL,
  `current_board` text COLLATE utf8mb4_general_ci,
  `turn` enum('white','black') COLLATE utf8mb4_general_ci DEFAULT 'white',
  `status` enum('waiting','ongoing','finished') COLLATE utf8mb4_general_ci DEFAULT 'waiting',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `game`
--

INSERT INTO `game` (`id_game`, `player_white`, `player_black`, `current_board`, `turn`, `status`, `created_at`) VALUES
(1, 1, 2, '{\"a1\":\"wr\",\"b1\":\"wn\",\"c1\":\"wb\",\"d1\":\"wq\",\"e1\":\"wk\",\"f1\":\"wb\",\"h1\":\"wr\",\"a2\":\"wp\",\"b2\":\"wp\",\"c2\":\"wp\",\"d2\":\"wp\",\"f2\":\"wp\",\"g2\":\"wp\",\"h2\":\"wp\",\"a7\":\"bp\",\"b7\":\"bp\",\"c7\":\"bp\",\"d7\":\"bp\",\"g7\":\"bp\",\"h7\":\"bp\",\"a8\":\"br\",\"b8\":\"bn\",\"c8\":\"bb\",\"d8\":\"bq\",\"e8\":\"bk\",\"f8\":\"bb\",\"g8\":\"bn\",\"h8\":\"br\",\"e4\":\"wp\",\"e5\":\"bp\",\"f3\":\"wn\",\"f5\":\"bp\"}', 'white', 'waiting', '2025-04-07 07:58:50');

-- --------------------------------------------------------

--
-- Structure de la table `player`
--

CREATE TABLE `player` (
  `id_player` int NOT NULL,
  `firstname_player` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `lastname_player` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nickname_player` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email_player` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password_player` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `image_player` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `player`
--

INSERT INTO `player` (`id_player`, `firstname_player`, `lastname_player`, `nickname_player`, `email_player`, `password_player`, `image_player`) VALUES
(1, 'jean', 'dupont', 'dupont55', 'jeandupont55@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$MHJVU3FqZG1sSjUwT3B5SQ$6pQvslce5hpeujiCafV8ABRN0Fq0+xEGTccU27UlQLY', '870fa91e05afd804cc36c2b9440532689212584cjean_dupont.jpeg'),
(2, 'jr', 'junior', 'junior2', 'jrjunior@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$VXI0TWFQQU42OGF1Wm5sRA$dhGXvF6Bl9oBL4nhG7uwWW+ED7debsmE7jZiNOEbXZY', 'b8e3baacb0191229caad731af744fe20cc336136jr_junior.jpeg'),
(3, 'super', 'mario', 'mariobros', 'n64@conic.com', '$2y$10$IQCEP1GB0pQa8nlrcnx9Xu4JXqKVLTiSn7WmcemZME6E9p0S6s7Ie', 'photo_67efd5780eced.jpeg'),
(4, 'yvan', 'leuté', 'LeT', 'yvanleute@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$NktKTnd1ME9FNzVza0VXSQ$cj7jYI94zJRGXlmd9UeIiCdy9ChzSKazazW70Ra2X8s', '55a514d20b4af8a8258e6aecefe2d3992b27632ayvan_leuté.jpeg');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `game`
--
ALTER TABLE `game`
  ADD PRIMARY KEY (`id_game`);

--
-- Index pour la table `player`
--
ALTER TABLE `player`
  ADD PRIMARY KEY (`id_player`),
  ADD UNIQUE KEY `email_player` (`email_player`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `game`
--
ALTER TABLE `game`
  MODIFY `id_game` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `player`
--
ALTER TABLE `player`
  MODIFY `id_player` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
