-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Client :  localhost:3306
-- Généré le :  Mer 05 Juin 2019 à 22:10
-- Version du serveur :  5.7.26-0ubuntu0.18.04.1
-- Version de PHP :  7.2.17-0ubuntu0.18.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `ardesign`
--


-- --------------------------------------------------------

--
-- Structure de la table `objects`
--

CREATE TABLE `objects` (
  `id` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `typeObject`varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `objects`
--

INSERT INTO `objects` (`id`, `idUser`,`typeObject`) VALUES
(1, 1, 'furniture'),
(2, 1, 'furniture'),
(3, 1, 'wall'),
(4, 1, 'wall');

-- --------------------------------------------------------

--
-- Structure de la table `furnitures`
--

CREATE TABLE `furnitures` (
  `id` int(11) NOT NULL,
  `width` varchar(100) NOT NULL,
  `height` varchar(100) NOT NULL,
  `length` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `furnitures` (`id`,  `width`, `height`, `length`) VALUES
(1, '60', '40', '120'),
(2, '30', '200', '200');

-- --------------------------------------------------------

--
-- Structure de la table `furnitures`
--

CREATE TABLE `walls` (
  `id` int(11) NOT NULL,
  `width` varchar(100) NOT NULL,
  `height` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `walls` (`id`,  `width`, `height`) VALUES
(3, '80', '120'),
(4, '75', '38.8');

-- --------------------------------------------------------

--
-- Structure de la table `standardFurniture`
--

CREATE TABLE `standardFurnitures` (
  `id` int(11) NOT NULL,
  `width` varchar(100) NOT NULL,
  `height` varchar(100) NOT NULL,
  `length` varchar(100) NOT NULL,
  `url` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `standardFurnitures`
--

INSERT INTO `standardFurnitures` (`id`, `width`, `height`, `length`, `url`) VALUES
(1, '60', '40', '120', 'www.google.com'),
(2, '30', '20', '200', 'www.google.com'),
(3, '80', '120', '100', 'www.google.com'),
(4, '75', '38.8', '100', 'www.google.com');


-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `mail`varchar(200) NOT NULL,
  `password` varchar(100) NOT NULL,
  `hash` varchar(100) NOT NULL DEFAULT 'hash'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`id`, `name`, `lastname`, `mail`, `password`, `hash`) VALUES
(1, 'tom', 'wu', 'tom.wu@gmail.com', 'web', '10bca641466d835d3db9be02ab6e1d08'),
(2, 'isa', 'gomez','isa.gomez@gmail.com', 'bdd', 'b9edda3aacebbf26bdfb708540070c05');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `objects`
--
ALTER TABLE `objects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idUser` (`idUser`);

--
-- Index pour la table `furniture`
--
ALTER TABLE `furniture`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `standardFurnitures`
--
ALTER TABLE `tandardFurnitures`
  ADD PRIMARY KEY (`id`);


--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);



--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `objects`
--
ALTER TABLE `objects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `furnitures`
--
ALTER TABLE `furnitures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `furnitures`
--
ALTER TABLE `walls`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--

-- AUTO_INCREMENT pour la table `standardFurnitures`
--
ALTER TABLE `standardFurnitures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;


--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `objects`
--
ALTER TABLE `objects`
  ADD CONSTRAINT `FK_users` FOREIGN KEY (`idUser`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `furnitures`
--
ALTER TABLE `furnitures`
  ADD CONSTRAINT `FK_objects` FOREIGN KEY (`id`) REFERENCES `objects` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `furnitures`
--
ALTER TABLE `walls`
  ADD CONSTRAINT `FK_objects` FOREIGN KEY (`id`) REFERENCES `objects` (`id`) ON DELETE CASCADE;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
