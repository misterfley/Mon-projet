-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : jeu. 10 avr. 2025 à 06:55
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
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `white_replay_request` tinyint(1) DEFAULT '0',
  `black_replay_request` tinyint(1) DEFAULT '0',
  `whiteKingMoved` tinyint(1) DEFAULT '0',
  `blackKingMoved` tinyint(1) DEFAULT '0',
  `whiteRookLeftMoved` tinyint(1) DEFAULT '0',
  `whiteRookRightMoved` tinyint(1) DEFAULT '0',
  `blackRookLeftMoved` tinyint(1) DEFAULT '0',
  `blackRookRightMoved` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `game`
--

INSERT INTO `game` (`id_game`, `player_white`, `player_black`, `current_board`, `turn`, `status`, `created_at`, `white_replay_request`, `black_replay_request`, `whiteKingMoved`, `blackKingMoved`, `whiteRookLeftMoved`, `whiteRookRightMoved`, `blackRookLeftMoved`, `blackRookRightMoved`) VALUES
(1, 1, 2, '{\"a1\":\"wr\",\"b1\":\"wn\",\"c1\":\"wb\",\"d1\":\"wq\",\"e1\":\"wk\",\"f1\":\"wb\",\"h1\":\"wr\",\"a2\":\"wp\",\"b2\":\"wp\",\"c2\":\"wp\",\"f2\":\"wp\",\"g2\":\"wp\",\"h2\":\"wp\",\"a7\":\"bp\",\"b7\":\"bp\",\"c7\":\"bp\",\"d7\":\"bp\",\"g7\":\"bp\",\"h7\":\"bp\",\"a8\":\"br\",\"b8\":\"bn\",\"c8\":\"bb\",\"d8\":\"bq\",\"e8\":\"bk\",\"f8\":\"bb\",\"g8\":\"bn\",\"h8\":\"br\",\"f3\":\"wn\",\"f5\":\"wp\",\"d4\":\"bp\"}', 'black', 'waiting', '2025-04-07 07:58:50', 0, 0, 0, 0, 0, 0, 0, 0),
(2, 3, NULL, '{\"a1\":\"wr\",\"b1\":\"wn\",\"c1\":\"wb\",\"d1\":\"wq\",\"e1\":\"wk\",\"f1\":\"wb\",\"g1\":\"wn\",\"h1\":\"wr\",\"a2\":\"wp\",\"b2\":\"wp\",\"c2\":\"wp\",\"e2\":\"wp\",\"f2\":\"wp\",\"g2\":\"wp\",\"h2\":\"wp\",\"a7\":\"bp\",\"b7\":\"bp\",\"c7\":\"bp\",\"d7\":\"bp\",\"e7\":\"bp\",\"f7\":\"bp\",\"g7\":\"bp\",\"h7\":\"bp\",\"a8\":\"br\",\"b8\":\"bn\",\"c8\":\"bb\",\"d8\":\"bq\",\"e8\":\"bk\",\"f8\":\"bb\",\"g8\":\"bn\",\"h8\":\"br\",\"d4\":\"wp\"}', 'black', 'waiting', '2025-04-08 07:14:55', 0, 0, 0, 0, 0, 0, 0, 0),
(3, 3, 4, '{\"a1\":\"wr\",\"b1\":\"wn\",\"c1\":\"wb\",\"e1\":\"wk\",\"h1\":\"wr\",\"a2\":\"wp\",\"b2\":\"wp\",\"c2\":\"wp\",\"d2\":\"wp\",\"f2\":\"wp\",\"g2\":\"wp\",\"h2\":\"wp\",\"a7\":\"bp\",\"b7\":\"bp\",\"f7\":\"bp\",\"g7\":\"bp\",\"h7\":\"bp\",\"a8\":\"br\",\"b8\":\"bn\",\"c8\":\"bb\",\"d8\":\"bq\",\"e8\":\"bk\",\"f8\":\"bb\",\"g8\":\"bn\",\"h8\":\"br\",\"e4\":\"wp\",\"e5\":\"bp\",\"f3\":\"wn\",\"d5\":\"bp\",\"b5\":\"wb\",\"h5\":\"wq\",\"c5\":\"bp\"}', 'white', 'waiting', '2025-04-08 07:15:00', 0, 0, 0, 0, 0, 0, 0, 0),
(4, 3, 4, '{\"a1\":\"wr\",\"b1\":\"wn\",\"c1\":\"wb\",\"e1\":\"wk\",\"g1\":\"wn\",\"h1\":\"wr\",\"a2\":\"wp\",\"b2\":\"wp\",\"c2\":\"wp\",\"d2\":\"wp\",\"f2\":\"wp\",\"g2\":\"wp\",\"h2\":\"wp\",\"a7\":\"bp\",\"b7\":\"bp\",\"c7\":\"bp\",\"d7\":\"bp\",\"f7\":\"wq\",\"g7\":\"bp\",\"a8\":\"br\",\"c8\":\"bb\",\"d8\":\"bq\",\"e8\":\"bk\",\"f8\":\"bb\",\"g8\":\"bn\",\"h8\":\"br\",\"e4\":\"wp\",\"e5\":\"bp\",\"c4\":\"wb\",\"c6\":\"bn\",\"h6\":\"bp\"}', 'black', 'waiting', '2025-04-08 08:10:00', 0, 0, 0, 0, 0, 0, 0, 0),
(5, 4, 3, '{\"a1\":\"wr\",\"b1\":\"wn\",\"c1\":\"wb\",\"e1\":\"wk\",\"g1\":\"wn\",\"h1\":\"wr\",\"a2\":\"wp\",\"b2\":\"wp\",\"c2\":\"wp\",\"d2\":\"wp\",\"f2\":\"wp\",\"g2\":\"wp\",\"h2\":\"wp\",\"b7\":\"bp\",\"c7\":\"bp\",\"d7\":\"bp\",\"f7\":\"wq\",\"g7\":\"bp\",\"a8\":\"br\",\"b8\":\"bn\",\"c8\":\"bb\",\"d8\":\"bq\",\"e8\":\"bk\",\"f8\":\"bb\",\"g8\":\"bn\",\"h8\":\"br\",\"e4\":\"wp\",\"e5\":\"bp\",\"a6\":\"bp\",\"c4\":\"wb\",\"h3\":\"bp\"}', 'black', 'waiting', '2025-04-08 08:18:43', 0, 0, 0, 0, 0, 0, 0, 0),
(6, 4, 3, '{\"a1\":\"wr\",\"b1\":\"wn\",\"c1\":\"wb\",\"e1\":\"wk\",\"g1\":\"wn\",\"h1\":\"wr\",\"a2\":\"wp\",\"b2\":\"wp\",\"c2\":\"wp\",\"d2\":\"wp\",\"f2\":\"wp\",\"g2\":\"wp\",\"h2\":\"wp\",\"a7\":\"bp\",\"b7\":\"bp\",\"c7\":\"bp\",\"d7\":\"bp\",\"f7\":\"wq\",\"g7\":\"bp\",\"a8\":\"br\",\"b8\":\"bn\",\"c8\":\"bb\",\"d8\":\"bq\",\"e8\":\"bk\",\"f8\":\"bb\",\"g8\":\"bn\",\"h8\":\"br\",\"e4\":\"wp\",\"e5\":\"bp\",\"c4\":\"wb\",\"h4\":\"bp\"}', 'black', 'waiting', '2025-04-08 08:24:26', 0, 0, 0, 0, 0, 0, 0, 0),
(7, 3, 4, '{\"a1\":\"wr\",\"b1\":\"wn\",\"c1\":\"wb\",\"e1\":\"wk\",\"g1\":\"wn\",\"h1\":\"wr\",\"a2\":\"wp\",\"b2\":\"wp\",\"c2\":\"wp\",\"d2\":\"wp\",\"f2\":\"wp\",\"g2\":\"wp\",\"h2\":\"wp\",\"a7\":\"bp\",\"b7\":\"bp\",\"c7\":\"bp\",\"d7\":\"bp\",\"f7\":\"wq\",\"g7\":\"bp\",\"a8\":\"br\",\"b8\":\"bn\",\"c8\":\"bb\",\"d8\":\"bq\",\"e8\":\"bk\",\"f8\":\"bb\",\"g8\":\"bn\",\"e4\":\"wp\",\"e5\":\"bp\",\"c4\":\"wb\",\"h6\":\"bp\",\"h7\":\"br\"}', 'black', 'waiting', '2025-04-08 08:26:05', 0, 0, 0, 0, 0, 0, 0, 0),
(8, 3, 4, '{\"a1\":\"wr\",\"b1\":\"wn\",\"c1\":\"wb\",\"e1\":\"wk\",\"g1\":\"wn\",\"h1\":\"wr\",\"a2\":\"wp\",\"b2\":\"wp\",\"c2\":\"wp\",\"d2\":\"wp\",\"f2\":\"wp\",\"g2\":\"wp\",\"h2\":\"wp\",\"a7\":\"bp\",\"b7\":\"bp\",\"c7\":\"bp\",\"d7\":\"bp\",\"f7\":\"wq\",\"g7\":\"bp\",\"a8\":\"br\",\"b8\":\"bn\",\"c8\":\"bb\",\"d8\":\"bq\",\"e8\":\"bk\",\"f8\":\"bb\",\"g8\":\"bn\",\"e4\":\"wp\",\"e5\":\"bp\",\"c4\":\"wb\",\"h6\":\"bp\",\"h7\":\"br\"}', 'black', 'waiting', '2025-04-08 08:29:36', 0, 0, 0, 0, 0, 0, 0, 0),
(9, 3, 4, '{\"a1\":\"wr\",\"b1\":\"wn\",\"c1\":\"wb\",\"d1\":\"wq\",\"e1\":\"wk\",\"h1\":\"wr\",\"b2\":\"wp\",\"c2\":\"wp\",\"d2\":\"wp\",\"f2\":\"bq\",\"g2\":\"wp\",\"h2\":\"wp\",\"a7\":\"bp\",\"b7\":\"bp\",\"c7\":\"bp\",\"d7\":\"bp\",\"f7\":\"bp\",\"g7\":\"bp\",\"h7\":\"bp\",\"a8\":\"br\",\"b8\":\"bn\",\"c8\":\"bb\",\"e8\":\"bk\",\"g8\":\"bn\",\"h8\":\"br\",\"e4\":\"wp\",\"e5\":\"bp\",\"b5\":\"wb\",\"c5\":\"bb\",\"h4\":\"wn\",\"a3\":\"wp\"}', 'white', 'waiting', '2025-04-08 08:39:45', 0, 0, 0, 0, 0, 0, 0, 0),
(10, 4, 3, '{\"a1\":\"wr\",\"b1\":\"wn\",\"c1\":\"wb\",\"e1\":\"wk\",\"g1\":\"wn\",\"h1\":\"wr\",\"a2\":\"wp\",\"b2\":\"wp\",\"c2\":\"wp\",\"d2\":\"wp\",\"f2\":\"wp\",\"g2\":\"wp\",\"h2\":\"wp\",\"a7\":\"bp\",\"b7\":\"bp\",\"c7\":\"bp\",\"d7\":\"bp\",\"f7\":\"wq\",\"g7\":\"bp\",\"a8\":\"br\",\"b8\":\"bn\",\"c8\":\"bb\",\"d8\":\"bq\",\"e8\":\"bk\",\"f8\":\"bb\",\"g8\":\"bn\",\"h8\":\"br\",\"e4\":\"wp\",\"e5\":\"bp\",\"c4\":\"wb\",\"h5\":\"bp\"}', 'black', 'waiting', '2025-04-08 08:44:44', 0, 0, 0, 0, 0, 0, 0, 0),
(11, 3, 4, '{\"a1\":\"wr\",\"b1\":\"wn\",\"c1\":\"wb\",\"d1\":\"wq\",\"e1\":\"wk\",\"f1\":\"wb\",\"h1\":\"wr\",\"b2\":\"wp\",\"c2\":\"wp\",\"d2\":\"wp\",\"f2\":\"bq\",\"g2\":\"wp\",\"a7\":\"bp\",\"b7\":\"bp\",\"c7\":\"bp\",\"d7\":\"bp\",\"f7\":\"bp\",\"g7\":\"bp\",\"h7\":\"bp\",\"a8\":\"br\",\"b8\":\"bn\",\"c8\":\"bb\",\"e8\":\"bk\",\"g8\":\"bn\",\"h8\":\"br\",\"e4\":\"wp\",\"e5\":\"bp\",\"f3\":\"wn\",\"a5\":\"wp\",\"c5\":\"bb\",\"h3\":\"wp\"}', 'white', 'waiting', '2025-04-08 08:55:07', 0, 0, 0, 0, 0, 0, 0, 0),
(12, 4, 3, '{\"a1\":\"wr\",\"b1\":\"wn\",\"c1\":\"wb\",\"e1\":\"wk\",\"g1\":\"wn\",\"h1\":\"wr\",\"a2\":\"wp\",\"b2\":\"wp\",\"c2\":\"wp\",\"d2\":\"wp\",\"f2\":\"wp\",\"g2\":\"wp\",\"h2\":\"wp\",\"a7\":\"bp\",\"b7\":\"bp\",\"c7\":\"bp\",\"d7\":\"bp\",\"f7\":\"wq\",\"g7\":\"bp\",\"a8\":\"br\",\"b8\":\"bn\",\"c8\":\"bb\",\"d8\":\"bq\",\"e8\":\"bk\",\"f8\":\"bb\",\"g8\":\"bn\",\"h8\":\"br\",\"e4\":\"wp\",\"e5\":\"bp\",\"c4\":\"wb\",\"h5\":\"bp\"}', 'black', 'waiting', '2025-04-08 08:56:57', 0, 0, 0, 0, 0, 0, 0, 0),
(13, 3, 4, '{\"a1\":\"wr\",\"b1\":\"wn\",\"e1\":\"wk\",\"g1\":\"wn\",\"h1\":\"wr\",\"a2\":\"wp\",\"b2\":\"wp\",\"c2\":\"wp\",\"f2\":\"wp\",\"g2\":\"bq\",\"h2\":\"wp\",\"a7\":\"bp\",\"b7\":\"bp\",\"c7\":\"bp\",\"d7\":\"bp\",\"g7\":\"bp\",\"h7\":\"bp\",\"a8\":\"br\",\"b8\":\"bn\",\"c8\":\"bb\",\"g8\":\"bn\",\"h8\":\"br\",\"e4\":\"wp\",\"e5\":\"bp\",\"c4\":\"wb\",\"d8\":\"bk\",\"d3\":\"wp\",\"g5\":\"wb\",\"e7\":\"bb\",\"f8\":\"wq\"}', 'black', 'waiting', '2025-04-08 09:07:40', 0, 0, 0, 0, 0, 0, 0, 0),
(14, 4, 3, '{\"a1\":\"wr\",\"b1\":\"wn\",\"c1\":\"wb\",\"e1\":\"wk\",\"g1\":\"wn\",\"h1\":\"wr\",\"a2\":\"wp\",\"b2\":\"wp\",\"c2\":\"wp\",\"d2\":\"wp\",\"f2\":\"wp\",\"g2\":\"wp\",\"h2\":\"wp\",\"a7\":\"bp\",\"b7\":\"bp\",\"c7\":\"bp\",\"d7\":\"bp\",\"f7\":\"wq\",\"g7\":\"bp\",\"a8\":\"br\",\"b8\":\"bn\",\"c8\":\"bb\",\"d8\":\"bq\",\"e8\":\"bk\",\"f8\":\"bb\",\"g8\":\"bn\",\"e4\":\"wp\",\"e5\":\"bp\",\"c4\":\"wb\",\"h6\":\"bp\",\"h7\":\"br\"}', 'black', 'waiting', '2025-04-08 09:11:09', 0, 0, 0, 0, 0, 0, 0, 0),
(15, 4, 3, '{\"a1\":\"wr\",\"b1\":\"wn\",\"c1\":\"wb\",\"e1\":\"wk\",\"g1\":\"wn\",\"h1\":\"wr\",\"a2\":\"wp\",\"b2\":\"wp\",\"c2\":\"wp\",\"d2\":\"wp\",\"f2\":\"wp\",\"g2\":\"wp\",\"h2\":\"wp\",\"a7\":\"bp\",\"b7\":\"bp\",\"c7\":\"bp\",\"d7\":\"bp\",\"f7\":\"wq\",\"g7\":\"bp\",\"a8\":\"br\",\"b8\":\"bn\",\"c8\":\"bb\",\"d8\":\"bq\",\"e8\":\"bk\",\"f8\":\"bb\",\"g8\":\"bn\",\"e4\":\"wp\",\"e5\":\"bp\",\"h6\":\"bp\",\"c4\":\"wb\",\"h7\":\"br\"}', 'black', 'waiting', '2025-04-08 09:16:17', 0, 0, 0, 0, 0, 0, 0, 0),
(16, 3, 4, '{\"a1\":\"wr\",\"b1\":\"wn\",\"c1\":\"wb\",\"e1\":\"wk\",\"g1\":\"wn\",\"h1\":\"wr\",\"a2\":\"wp\",\"b2\":\"wp\",\"c2\":\"wp\",\"d2\":\"wp\",\"f2\":\"wp\",\"g2\":\"wp\",\"h2\":\"wp\",\"b7\":\"bp\",\"c7\":\"bp\",\"d7\":\"bp\",\"e7\":\"bp\",\"a8\":\"br\",\"b8\":\"bn\",\"c8\":\"bb\",\"d8\":\"bq\",\"e8\":\"bk\",\"f8\":\"bb\",\"g8\":\"bn\",\"h8\":\"br\",\"e4\":\"wp\",\"f6\":\"bp\",\"c4\":\"wb\",\"h6\":\"bp\",\"g6\":\"bp\",\"a6\":\"bp\",\"f7\":\"wq\"}', 'black', 'waiting', '2025-04-08 09:54:34', 0, 0, 0, 0, 0, 0, 0, 0),
(17, 3, 4, '{\"a1\":\"wr\",\"b1\":\"wn\",\"c1\":\"wb\",\"d1\":\"wq\",\"e1\":\"wk\",\"f1\":\"wb\",\"g1\":\"wn\",\"h1\":\"wr\",\"a2\":\"wp\",\"b2\":\"wp\",\"c2\":\"wp\",\"d2\":\"wp\",\"f2\":\"wp\",\"g2\":\"wp\",\"h2\":\"wp\",\"a7\":\"bp\",\"b7\":\"bp\",\"c7\":\"bp\",\"d7\":\"bp\",\"e7\":\"bp\",\"f7\":\"bp\",\"g7\":\"bp\",\"h7\":\"bp\",\"a8\":\"br\",\"b8\":\"bn\",\"c8\":\"bb\",\"d8\":\"bq\",\"e8\":\"bk\",\"f8\":\"bb\",\"g8\":\"bn\",\"h8\":\"br\",\"e4\":\"wp\"}', 'black', 'waiting', '2025-04-08 10:17:39', 0, 0, 0, 0, 0, 0, 0, 0),
(18, 4, 3, '{\"a1\":\"wr\",\"b1\":\"wn\",\"c1\":\"wb\",\"e1\":\"wk\",\"g1\":\"wn\",\"h1\":\"wr\",\"a2\":\"wp\",\"b2\":\"wp\",\"c2\":\"wp\",\"d2\":\"wp\",\"f2\":\"wp\",\"g2\":\"wp\",\"h2\":\"wp\",\"a7\":\"bp\",\"b7\":\"bp\",\"c7\":\"bp\",\"f7\":\"wq\",\"g7\":\"bp\",\"a8\":\"br\",\"b8\":\"bn\",\"c8\":\"bb\",\"d8\":\"bq\",\"e8\":\"bk\",\"f8\":\"bb\",\"g8\":\"bn\",\"h8\":\"br\",\"e4\":\"wp\",\"e5\":\"bp\",\"c4\":\"wb\",\"h6\":\"bp\",\"d6\":\"bp\"}', 'black', 'waiting', '2025-04-09 14:27:46', 0, 0, 0, 0, 0, 0, 0, 0),
(19, 3, 4, '{\"a1\":\"wr\",\"b1\":\"wn\",\"c1\":\"wb\",\"d1\":\"wq\",\"e1\":\"wk\",\"f1\":\"wb\",\"h1\":\"wr\",\"b2\":\"wp\",\"c2\":\"wp\",\"d2\":\"wp\",\"f2\":\"bq\",\"g2\":\"wp\",\"a7\":\"bp\",\"b7\":\"bp\",\"c7\":\"bp\",\"d7\":\"bp\",\"f7\":\"bp\",\"g7\":\"bp\",\"h7\":\"bp\",\"a8\":\"br\",\"b8\":\"bn\",\"c8\":\"bb\",\"e8\":\"bk\",\"g8\":\"bn\",\"h8\":\"br\",\"e4\":\"wp\",\"e5\":\"bp\",\"f3\":\"wn\",\"a3\":\"wp\",\"c5\":\"bb\",\"h3\":\"wp\"}', 'white', 'waiting', '2025-04-09 14:30:43', 0, 0, 0, 0, 0, 0, 0, 0),
(20, 3, 4, '{\"a1\":\"wr\",\"b1\":\"wn\",\"c1\":\"wb\",\"e1\":\"wk\",\"g1\":\"wn\",\"h1\":\"wr\",\"a2\":\"wp\",\"b2\":\"wp\",\"c2\":\"wp\",\"d2\":\"wp\",\"f2\":\"wp\",\"g2\":\"wp\",\"h2\":\"wp\",\"b7\":\"bp\",\"c7\":\"bp\",\"d7\":\"bp\",\"f7\":\"wq\",\"g7\":\"bp\",\"a8\":\"br\",\"b8\":\"bn\",\"c8\":\"bb\",\"d8\":\"bq\",\"e8\":\"bk\",\"f8\":\"bb\",\"g8\":\"bn\",\"e4\":\"wp\",\"e5\":\"bp\",\"h6\":\"bp\",\"h7\":\"br\",\"c4\":\"wb\",\"a6\":\"bp\"}', 'black', 'waiting', '2025-04-09 14:48:11', 0, 0, 0, 0, 0, 0, 0, 0),
(21, 3, 4, '{\"a1\":\"wr\",\"b1\":\"wn\",\"c1\":\"wb\",\"d1\":\"wq\",\"h1\":\"wr\",\"a2\":\"wp\",\"b2\":\"wp\",\"c2\":\"wp\",\"d2\":\"wp\",\"f2\":\"wp\",\"g2\":\"wp\",\"h2\":\"wp\",\"a7\":\"bp\",\"b7\":\"bp\",\"c7\":\"bp\",\"d7\":\"bp\",\"f7\":\"bp\",\"g7\":\"bp\",\"a8\":\"br\",\"b8\":\"bn\",\"c8\":\"bb\",\"d8\":\"bq\",\"e8\":\"bk\",\"f8\":\"bb\",\"g8\":\"bn\",\"h8\":\"br\",\"e4\":\"wp\",\"e5\":\"bp\",\"c4\":\"wb\",\"f3\":\"wn\",\"h5\":\"bp\",\"g1\":\"wk\"}', 'black', 'waiting', '2025-04-09 14:54:37', 0, 0, 0, 0, 0, 0, 0, 0),
(22, 4, 3, '{\"a1\":\"wr\",\"b1\":\"wn\",\"c1\":\"wb\",\"d1\":\"wq\",\"e1\":\"wk\",\"f1\":\"wb\",\"g1\":\"wn\",\"h1\":\"wr\",\"a2\":\"wp\",\"b2\":\"wp\",\"c2\":\"wp\",\"d2\":\"wp\",\"e2\":\"wp\",\"f2\":\"wp\",\"g2\":\"wp\",\"h2\":\"wp\",\"a7\":\"bp\",\"b7\":\"bp\",\"c7\":\"bp\",\"d7\":\"bp\",\"e7\":\"bp\",\"f7\":\"bp\",\"g7\":\"bp\",\"h7\":\"bp\",\"a8\":\"br\",\"b8\":\"bn\",\"c8\":\"bb\",\"d8\":\"bq\",\"e8\":\"bk\",\"f8\":\"bb\",\"g8\":\"bn\",\"h8\":\"br\"}', 'white', 'waiting', '2025-04-09 15:04:42', 0, 0, 0, 0, 0, 0, 0, 0),
(23, 4, 3, '{\"a1\":\"wr\",\"b1\":\"wn\",\"c1\":\"wb\",\"d1\":\"wq\",\"h1\":\"wr\",\"b2\":\"wp\",\"c2\":\"wp\",\"d2\":\"wp\",\"f2\":\"wp\",\"g2\":\"wp\",\"h2\":\"wp\",\"b7\":\"bp\",\"c7\":\"bp\",\"d7\":\"bp\",\"g7\":\"bp\",\"h7\":\"bp\",\"a8\":\"br\",\"b8\":\"bn\",\"c8\":\"bb\",\"d8\":\"bq\",\"e8\":\"bk\",\"h8\":\"br\",\"e4\":\"wp\",\"e5\":\"bp\",\"f3\":\"wn\",\"f6\":\"bp\",\"b5\":\"wb\",\"a6\":\"bp\",\"f1\":\"wk\",\"c5\":\"bb\",\"h6\":\"bn\",\"a4\":\"wp\"}', 'black', 'waiting', '2025-04-09 15:05:24', 0, 0, 0, 0, 0, 0, 0, 0),
(24, 4, 3, '{\"a1\":\"wr\",\"b1\":\"wn\",\"c1\":\"wb\",\"d1\":\"wq\",\"e1\":\"wk\",\"f1\":\"wb\",\"g1\":\"wn\",\"h1\":\"wr\",\"a2\":\"wp\",\"b2\":\"wp\",\"c2\":\"wp\",\"d2\":\"wp\",\"e2\":\"wp\",\"f2\":\"wp\",\"g2\":\"wp\",\"h2\":\"wp\",\"a7\":\"bp\",\"b7\":\"bp\",\"c7\":\"bp\",\"d7\":\"bp\",\"e7\":\"bp\",\"f7\":\"bp\",\"g7\":\"bp\",\"h7\":\"bp\",\"a8\":\"br\",\"b8\":\"bn\",\"c8\":\"bb\",\"d8\":\"bq\",\"e8\":\"bk\",\"f8\":\"bb\",\"g8\":\"bn\",\"h8\":\"br\"}', 'white', 'waiting', '2025-04-09 15:26:42', 0, 0, 0, 0, 0, 0, 0, 0),
(25, 4, 3, '{\"a1\":\"wr\",\"b1\":\"wn\",\"c1\":\"wb\",\"d1\":\"wq\",\"e1\":\"wk\",\"f1\":\"wb\",\"g1\":\"wn\",\"h1\":\"wr\",\"a2\":\"wp\",\"b2\":\"wp\",\"c2\":\"wp\",\"d2\":\"wp\",\"e2\":\"wp\",\"f2\":\"wp\",\"g2\":\"wp\",\"h2\":\"wp\",\"a7\":\"bp\",\"b7\":\"bp\",\"c7\":\"bp\",\"d7\":\"bp\",\"e7\":\"bp\",\"f7\":\"bp\",\"g7\":\"bp\",\"h7\":\"bp\",\"a8\":\"br\",\"b8\":\"bn\",\"c8\":\"bb\",\"d8\":\"bq\",\"e8\":\"bk\",\"f8\":\"bb\",\"g8\":\"bn\",\"h8\":\"br\"}', 'white', 'waiting', '2025-04-09 15:38:05', 0, 0, 0, 0, 0, 0, 0, 0),
(26, 3, 4, '{\"a1\":\"wr\",\"b1\":\"wn\",\"c1\":\"wb\",\"d1\":\"wq\",\"e1\":\"wk\",\"f1\":\"wb\",\"g1\":\"wn\",\"h1\":\"wr\",\"a2\":\"wp\",\"b2\":\"wp\",\"c2\":\"wp\",\"d2\":\"wp\",\"e2\":\"wp\",\"f2\":\"wp\",\"g2\":\"wp\",\"h2\":\"wp\",\"a7\":\"bp\",\"b7\":\"bp\",\"c7\":\"bp\",\"d7\":\"bp\",\"e7\":\"bp\",\"f7\":\"bp\",\"g7\":\"bp\",\"h7\":\"bp\",\"a8\":\"br\",\"b8\":\"bn\",\"c8\":\"bb\",\"d8\":\"bq\",\"e8\":\"bk\",\"f8\":\"bb\",\"g8\":\"bn\",\"h8\":\"br\"}', 'white', 'waiting', '2025-04-09 15:48:37', 0, 0, 0, 0, 0, 0, 0, 0),
(27, 3, 4, '{\"a1\":\"wr\",\"b1\":\"wn\",\"c1\":\"wb\",\"d1\":\"wq\",\"e1\":\"wk\",\"f1\":\"wb\",\"g1\":\"wn\",\"h1\":\"wr\",\"a2\":\"wp\",\"b2\":\"wp\",\"c2\":\"wp\",\"d2\":\"wp\",\"e2\":\"wp\",\"f2\":\"wp\",\"g2\":\"wp\",\"h2\":\"wp\",\"a7\":\"bp\",\"b7\":\"bp\",\"c7\":\"bp\",\"d7\":\"bp\",\"e7\":\"bp\",\"f7\":\"bp\",\"g7\":\"bp\",\"h7\":\"bp\",\"a8\":\"br\",\"b8\":\"bn\",\"c8\":\"bb\",\"d8\":\"bq\",\"e8\":\"bk\",\"f8\":\"bb\",\"g8\":\"bn\",\"h8\":\"br\"}', 'white', 'waiting', '2025-04-09 15:51:30', 0, 0, 0, 0, 0, 0, 0, 0),
(28, 3, 4, '{\"a1\":\"wr\",\"b1\":\"wn\",\"c1\":\"wb\",\"d1\":\"wq\",\"e1\":\"wk\",\"h1\":\"wr\",\"a2\":\"wp\",\"b2\":\"wp\",\"c2\":\"wp\",\"d2\":\"wp\",\"f2\":\"wp\",\"g2\":\"wp\",\"h2\":\"wp\",\"a7\":\"bp\",\"b7\":\"bp\",\"c7\":\"bp\",\"d7\":\"bp\",\"g7\":\"bp\",\"h7\":\"bp\",\"a8\":\"br\",\"b8\":\"bn\",\"c8\":\"bb\",\"d8\":\"bq\",\"e8\":\"bk\",\"g8\":\"bn\",\"h8\":\"br\",\"e4\":\"wp\",\"e5\":\"bp\",\"f3\":\"wn\",\"f6\":\"bp\",\"c4\":\"wb\",\"e7\":\"bb\"}', 'white', 'waiting', '2025-04-09 16:02:47', 0, 0, 0, 0, 0, 0, 0, 0),
(29, 3, 4, '{\"a1\":\"wr\",\"b1\":\"wn\",\"c1\":\"wb\",\"d1\":\"wq\",\"e1\":\"wk\",\"h1\":\"wr\",\"a2\":\"wp\",\"b2\":\"wp\",\"c2\":\"wp\",\"d2\":\"wp\",\"f2\":\"wp\",\"g2\":\"wp\",\"h2\":\"wp\",\"a7\":\"bp\",\"b7\":\"bp\",\"c7\":\"bp\",\"d7\":\"bp\",\"f7\":\"bp\",\"g7\":\"bp\",\"a8\":\"br\",\"b8\":\"bn\",\"c8\":\"bb\",\"d8\":\"bq\",\"e8\":\"bk\",\"f8\":\"bb\",\"g8\":\"bn\",\"h8\":\"br\",\"e4\":\"wp\",\"e5\":\"bp\",\"f3\":\"wn\",\"c4\":\"wb\",\"h5\":\"bp\"}', 'white', 'waiting', '2025-04-10 06:53:47', 0, 0, 0, 0, 0, 0, 0, 0);

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
  MODIFY `id_game` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT pour la table `player`
--
ALTER TABLE `player`
  MODIFY `id_player` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
