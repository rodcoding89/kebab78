-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 22 sep. 2021 à 17:49
-- Version du serveur : 10.4.19-MariaDB
-- Version de PHP : 8.0.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `restokebab`
--

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE `categorie` (
  `categorie_id` int(11) NOT NULL,
  `categorie_name` varchar(255) NOT NULL,
  `img_url` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `categorie`
--

INSERT INTO `categorie` (`categorie_id`, `categorie_name`, `img_url`) VALUES
(1, 'tacos', 'assets/images/product-images/6149cfb77e8c3_cuisse_grille.jpg'),
(2, 'burgers', 'assets/images/product-images/6149d00f501ba_chicken_burger.png'),
(3, 'sandwichs', 'assets/images/product-images/6149d04d2e2c6_cuisse_grille.jpg'),
(4, 'crêpes', 'assets/images/product-images/6149d07f9b94d_cuisse_grille.jpg'),
(5, 'desserts', 'assets/images/product-images/6149d0945b3c1_cuisse_grille.jpg'),
(6, 'boissons', 'assets/images/product-images/6149d0aa8673d_cuisse_grille.jpg'),
(7, 'formules tacos', 'assets/images/product-images/6149d06c0a53a_cuisse_grille.jpg'),
(8, 'formules sandwichs', 'assets/images/product-images/6149cf2cc6140_cuisse_grille.jpg'),
(9, 'formules burgers', 'assets/images/product-images/6149cefd3613b_cuisse_grille.jpg'),
(10, 'test2', 'assets/images/product-images/6149cda8409f0_riz_thai_poulet_450.png'),
(12, 'test3', 'assets/images/product-images/6149bd83d3ea4_cuisse_grille.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `extrat`
--

CREATE TABLE `extrat` (
  `extrat_id` int(11) NOT NULL,
  `extrat_name` varchar(255) NOT NULL,
  `extrat_categ` varchar(255) NOT NULL,
  `prix` double DEFAULT NULL,
  `img_url` text NOT NULL,
  `categ_ref` int(11) NOT NULL,
  `s_create_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `extrat`
--

INSERT INTO `extrat` (`extrat_id`, `extrat_name`, `extrat_categ`, `prix`, `img_url`, `categ_ref`, `s_create_date`) VALUES
(2, 'Steak', 'Viande', 0, 'assets/images/product-images/61447ea80f5f9_pommes_frites.png', 1, '2021-09-17'),
(3, 'Cordon bleu', 'Viande', 0, 'assets/images/product-images/61447eec1fd83_pommes_frites.png', 1, '2021-09-17'),
(4, 'Kefta', 'Viande', 0, 'assets/images/product-images/61447f1080611_Sandwich.png', 1, '2021-09-17'),
(5, 'Chicken curry', 'Viande', 0, 'assets/images/product-images/61447f449c546_pommes_frites.png', 1, '2021-09-17'),
(6, 'Chicken paprika', 'Viande', 0, 'assets/images/product-images/61447f6d35a91_pommes_frites.png', 1, '2021-09-17'),
(8, 'Oignons', 'Composition', 0, 'assets/images/product-images/614b28b027586_tiramisu.jpg', 3, '2021-09-22'),
(9, 'Salade', 'Composition', 0, 'assets/images/product-images/614b28cfd392a_tiramisu.jpg', 3, '2021-09-22'),
(10, 'Tomates', 'Composition', 0, 'assets/images/product-images/614b28f2428bd_tiramisu.jpg', 3, '2021-09-22'),
(11, 'Oignons', 'Composition', 0, 'assets/images/product-images/614b2ee1efb92_tiramisu.jpg', 2, '2021-09-22'),
(12, 'Salade', 'Composition', 0, 'assets/images/product-images/614b2f02e8afa_tiramisu.jpg', 2, '2021-09-22'),
(13, 'Tomates', 'Composition', 0, 'assets/images/product-images/614b2f5d04bbc_tiramisu.jpg', 2, '2021-09-22');

-- --------------------------------------------------------

--
-- Structure de la table `product`
--

CREATE TABLE `product` (
  `product_id` bigint(20) NOT NULL,
  `categorie` int(11) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `img_url` text DEFAULT NULL,
  `prix_sans_livraison` double DEFAULT NULL,
  `prix_avec_livraison` double DEFAULT NULL,
  `product_create_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `product`
--

INSERT INTO `product` (`product_id`, `categorie`, `product_name`, `description`, `img_url`, `prix_sans_livraison`, `prix_avec_livraison`, `product_create_date`) VALUES
(1, 1, '1 viande', '', 'assets/images/product-images/614352da1259c_pommes_frites.png', 10.5, 8.5, '2021-09-16'),
(2, 1, '2 viandes', 'tacos', 'assets/images/product-images/61435356277fd_cuisse.png', 12.5, 9.5, '2021-09-16'),
(4, 2, 'Hamburger', 'Découvrez nos burgers', 'assets/images/product-images/614359895e02f_pommes_frites.png', 8.5, 6, '2021-09-16'),
(5, 2, 'Big burger', 'le big', 'assets/images/product-images/61435ac3911a3_pommes_frites.png', 10.5, 9.5, '2021-09-16'),
(6, 5, 'Gateau oriental', 'nos desserts', 'assets/images/product-images/614461827f1e5_Sandwich.png', 5.5, 3.5, '2021-09-17'),
(7, 2, 'Cheese', 'Nos cheeses', 'assets/images/product-images/614b2e607eefa_cheeseburger.png', 7.5, 4.5, '2021-09-22');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `user_id` bigint(20) NOT NULL,
  `identifiant` varchar(255) DEFAULT NULL,
  `mdp` text NOT NULL,
  `statut` enum('user','admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`user_id`, `identifiant`, `mdp`, `statut`) VALUES
(1, 'kebab78-1', '$2y$10$zHh9uRdPbyPYf7M9vROWH.ywUmWN1YXPRHabtSpkbcubQgve5F8ra', 'admin'),
(2, 'kebab78-2', '$2y$10$YqD.cq2PxrMn0Lc6OTOwG.V8RvN0M741BjHbPfG6ANV64UHb5T93K', 'user');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`categorie_id`);

--
-- Index pour la table `extrat`
--
ALTER TABLE `extrat`
  ADD PRIMARY KEY (`extrat_id`),
  ADD KEY `categ_ref` (`categ_ref`);

--
-- Index pour la table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `FK_categ` (`categorie`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `categorie`
--
ALTER TABLE `categorie`
  MODIFY `categorie_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `extrat`
--
ALTER TABLE `extrat`
  MODIFY `extrat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `extrat`
--
ALTER TABLE `extrat`
  ADD CONSTRAINT `extrat_ibfk_1` FOREIGN KEY (`categ_ref`) REFERENCES `categorie` (`categorie_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `FK_categ` FOREIGN KEY (`categorie`) REFERENCES `categorie` (`categorie_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
