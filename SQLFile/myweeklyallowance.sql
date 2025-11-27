-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Hôte : mysql:3306
-- Généré le : jeu. 27 nov. 2025 à 10:08
-- Version du serveur : 8.0.44
-- Version de PHP : 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `myweeklyallowance`
--

-- --------------------------------------------------------

--
-- Structure de la table `bank_account`
--

CREATE TABLE `bank_account` (
  `id` int UNSIGNED NOT NULL,
  `parent_id` int UNSIGNED NOT NULL,
  `teen_id` int UNSIGNED NOT NULL,
  `balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `weekly_allowance` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `bank_account`
--

INSERT INTO `bank_account` (`id`, `parent_id`, `teen_id`, `balance`, `created_at`, `weekly_allowance`) VALUES
(1, 3, 1, 10.00, '2025-11-27 09:46:27', 42);

-- --------------------------------------------------------

--
-- Structure de la table `parent`
--

CREATE TABLE `parent` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `parent`
--

INSERT INTO `parent` (`id`, `name`, `lastname`, `email`, `password_hash`, `created_at`) VALUES
(1, 'aa', 'aa', 'kisob13404@bipochub.com', '$2y$10$wzSdVS2e3gGnlbRS0rDSCeQNQ3mwV04smAeGo9H6F56msZazl7kQG', '2025-11-26 23:05:50'),
(2, 'bb', 'bb', 'yolo.t@gmail.fr', '$2y$10$ojrWwEYnztA9SuoX0oKXjulCVr/HD6AYntPb1EwMvMKwCLoRScwVe', '2025-11-27 08:33:37'),
(3, 'aaaaa', 'aaaaaa', 't.t@t.t', '$2y$10$S.geZJumGmRLRSlDq0r6eOXJcuDo8VMi8ri0RfJSiKJ1i/cjqbyPa', '2025-11-27 09:26:57');

-- --------------------------------------------------------

--
-- Structure de la table `teen`
--

CREATE TABLE `teen` (
  `id` int UNSIGNED NOT NULL,
  `firstname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `age` tinyint UNSIGNED NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `teen`
--

INSERT INTO `teen` (`id`, `firstname`, `lastname`, `age`, `password_hash`, `created_at`) VALUES
(1, 'jean', 'dupont', 14, '$2y$10$vjHTd6O84N/dWJYWgl.vVem./Je3vTavCnHGET9NeYSrRQIR73nhW', '2025-11-27 09:46:27');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `bank_account`
--
ALTER TABLE `bank_account`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_bank_account_parent` (`parent_id`),
  ADD KEY `fk_bank_account_teen` (`teen_id`);

--
-- Index pour la table `parent`
--
ALTER TABLE `parent`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `teen`
--
ALTER TABLE `teen`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `bank_account`
--
ALTER TABLE `bank_account`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `parent`
--
ALTER TABLE `parent`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `teen`
--
ALTER TABLE `teen`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `bank_account`
--
ALTER TABLE `bank_account`
  ADD CONSTRAINT `fk_bank_account_parent` FOREIGN KEY (`parent_id`) REFERENCES `parent` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_bank_account_teen` FOREIGN KEY (`teen_id`) REFERENCES `teen` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
