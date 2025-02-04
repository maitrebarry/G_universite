-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 24 jan. 2025 à 09:26
-- Version du serveur :  10.4.11-MariaDB
-- Version de PHP : 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `db_universite`
--

-- --------------------------------------------------------

--
-- Structure de la table `anne_universitaire`
--

CREATE TABLE `anne_universitaire` (
  `id_anne` int(11) NOT NULL,
  `anne_scolaire` varchar(255) NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `anne_universitaire`
--

INSERT INTO `anne_universitaire` (`id_anne`, `anne_scolaire`, `date_debut`, `date_fin`) VALUES
(1, '2024-2025', '2024-01-01', '2025-01-01');

-- --------------------------------------------------------

--
-- Structure de la table `edt`
--

CREATE TABLE `edt` (
  `id_edt` int(11) NOT NULL,
  `date_creation` date NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `heure_total` int(11) NOT NULL,
  `statut` tinyint(4) NOT NULL,
  `id_module` int(11) NOT NULL,
  `id_enseignant` int(11) NOT NULL,
  `id_salle` int(11) NOT NULL,
  `id_filiere` int(11) NOT NULL,
  `id_promotion` int(11) NOT NULL,
  `id_periode` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `edt`
--

INSERT INTO `edt` (`id_edt`, `date_creation`, `date_debut`, `date_fin`, `heure_total`, `statut`, `id_module`, `id_enseignant`, `id_salle`, `id_filiere`, `id_promotion`, `id_periode`) VALUES
(1, '2025-01-17', '2025-01-17', '2025-01-24', 0, 0, 5, 1, 1, 1, 11, 1),
(2, '2025-01-17', '2025-01-17', '2025-01-24', 60, 0, 8, 2, 2, 3, 12, 1),
(3, '2025-01-17', '2025-01-17', '2025-01-24', 90, 0, 9, 2, 2, 3, 12, 1),
(4, '2025-01-19', '2025-01-19', '2025-01-26', 180, 0, 7, 3, 2, 2, 13, 1),
(5, '2025-01-19', '2025-01-23', '2025-01-30', 100, 0, 1, 4, 1, 1, 9, 1),
(6, '2025-01-19', '2025-07-19', '2025-07-26', 200, 0, 7, 5, 2, 2, 10, 2),
(7, '2025-01-19', '2025-07-19', '2025-07-26', 50, 0, 5, 5, 1, 1, 11, 2),
(8, '2025-01-19', '2025-09-19', '2025-09-26', 90, 0, 1, 2, 2, 1, 9, 2),
(9, '2025-01-19', '2025-01-19', '2025-01-26', 20, 0, 7, 2, 1, 2, 13, 1),
(10, '2025-01-19', '2025-02-20', '2025-02-27', 100, 0, 7, 2, 2, 2, 13, 1),
(11, '2025-01-19', '2025-01-19', '2025-01-26', 0, 0, 9, 1, 2, 3, 12, 0);

-- --------------------------------------------------------

--
-- Structure de la table `emargement`
--

CREATE TABLE `emargement` (
  `id_emargement` int(11) NOT NULL,
  `id_enseignant` int(11) NOT NULL,
  `id_filiere` int(11) NOT NULL,
  `id_semestre` int(11) NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `nh_programme` int(11) NOT NULL,
  `heures_supp` int(11) DEFAULT 0,
  `heures_dues` int(11) DEFAULT NULL,
  `statut` enum('1','2') NOT NULL,
  `grade` enum('assistant','maitre_assistant','maitre_conference','professeur') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `emargement`
--

INSERT INTO `emargement` (`id_emargement`, `id_enseignant`, `id_filiere`, `id_semestre`, `date_debut`, `date_fin`, `nh_programme`, `heures_supp`, `heures_dues`, `statut`, `grade`) VALUES
(1, 2, 1, 1, '2025-01-10', '2025-01-18', 100, 0, 140, '1', '');

-- --------------------------------------------------------

--
-- Structure de la table `enseignants`
--

CREATE TABLE `enseignants` (
  `enseignant_id` int(11) NOT NULL,
  `enseignant_statut` varchar(50) NOT NULL,
  `enseignant_matricule` varchar(50) DEFAULT NULL,
  `enseignant_nom` varchar(100) NOT NULL,
  `enseignant_prenom` varchar(100) NOT NULL,
  `enseignant_date_naissance` date NOT NULL,
  `enseignant_telephone` varchar(20) NOT NULL,
  `enseignant_diplome` varchar(50) NOT NULL,
  `enseignant_cv` varchar(255) DEFAULT NULL,
  `enseignant_email` varchar(255) NOT NULL,
  `id_grade` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `enseignants`
--

INSERT INTO `enseignants` (`enseignant_id`, `enseignant_statut`, `enseignant_matricule`, `enseignant_nom`, `enseignant_prenom`, `enseignant_date_naissance`, `enseignant_telephone`, `enseignant_diplome`, `enseignant_cv`, `enseignant_email`, `id_grade`) VALUES
(1, 'PERMANANT', 'Mle0M-KAT-CDI', 'BARRY', 'Moustapha', '2025-01-15', '74 74 56 69', 'Doctorat', NULL, 'barrymoustapha908@gmail.com', 2),
(2, 'NON_PERMANANT', NULL, 'BARRY', 'Moussa', '2025-01-15', '74 74 56 60', 'Master', '/cv_enseignant/RECUP.DOC', 'admin@admin.com', NULL),
(3, 'PERMANANT', 'Mle26F-BAM-CDI', 'SOW', 'Alimata', '2011-12-11', '67 23 45 12', 'Doctorat', NULL, 'alimatasow@gmail.com', 4),
(4, 'NON_PERMANANT', NULL, 'SIDIBE', 'Siaka', '1888-03-12', '61 23 45 90', 'Master', '/cv_enseignant/default_cv.pdf', 'sididoucoure228@gmail.com', NULL),
(5, 'NON_PERMANANT', NULL, 'BA', 'Moustapha', '2025-01-19', '74 74 56 62', 'Doctorat', '/cv_enseignant/default_cv.pdf', 'alimatasow1@gmail.com', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `enseigner`
--

CREATE TABLE `enseigner` (
  `id_enseigner` int(11) NOT NULL,
  `id_module` int(11) NOT NULL,
  `id_enseignant` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `etudiant`
--

CREATE TABLE `etudiant` (
  `id_etudiant` int(11) NOT NULL,
  `nom_prenom_etudiant` varchar(250) NOT NULL,
  `date_naissance_etudiant` varchar(250) NOT NULL,
  `lieu_naissance_etudiant` varchar(250) NOT NULL,
  `genre_etudiant` varchar(50) NOT NULL,
  `matricule_etudiant` varchar(250) NOT NULL,
  `contact_etudiant` varchar(50) NOT NULL,
  `diplome` varchar(255) NOT NULL,
  `id_statut` varchar(255) NOT NULL,
  `annee` varchar(255) NOT NULL,
  `id_filiere` int(11) NOT NULL,
  `numetudiant` varchar(255) NOT NULL,
  `prenompere` varchar(255) NOT NULL,
  `prenomnommere` varchar(255) NOT NULL,
  `cercleNais` varchar(255) NOT NULL,
  `commNais` varchar(255) NOT NULL,
  `nationnalite` varchar(255) NOT NULL,
  `anneediplome` varchar(255) NOT NULL,
  `serie` varchar(255) NOT NULL,
  `pays` varchar(255) NOT NULL,
  `academie` varchar(2555) NOT NULL,
  `lieuresidenceparents` varchar(255) NOT NULL,
  `adresseactuel` varchar(255) NOT NULL,
  `numplace` varchar(255) NOT NULL,
  `profilname` varchar(255) NOT NULL,
  `id_promotion` int(11) NOT NULL,
  `montant` int(255) NOT NULL,
  `total_frais` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `etudiant`
--

INSERT INTO `etudiant` (`id_etudiant`, `nom_prenom_etudiant`, `date_naissance_etudiant`, `lieu_naissance_etudiant`, `genre_etudiant`, `matricule_etudiant`, `contact_etudiant`, `diplome`, `id_statut`, `annee`, `id_filiere`, `numetudiant`, `prenompere`, `prenomnommere`, `cercleNais`, `commNais`, `nationnalite`, `anneediplome`, `serie`, `pays`, `academie`, `lieuresidenceparents`, `adresseactuel`, `numplace`, `profilname`, `id_promotion`, `montant`, `total_frais`) VALUES
(5, 'ibrahima Diakite', '2025-01-18', 'hgfbgfb', 'Féminin', 'adbnq', '92190993', 'Bac', '0', '', 2, '62662', 'ibrahima', 'Diakite', 'xdvgv', 'Segou', 'zadsffnhgf', '5848', 'dffc', 'Mali', 'ghdv', '', '', '5989', '/profile/Capture d’écran (1).png', 12, 0, 0),
(6, 'ibrahima Diakite', '2025-01-18', 'hgfbgfb', 'Féminin', 'adbnq', '92190993', 'Bac', '0', '', 2, '62662', 'ibrahima', 'Diakite', 'xdvgv', 'Segou', 'zadsffnhgf', '5848', 'dffc', 'Mali', 'ghdv', '', '', '5989', '/profile/Capture d’écran (1).png', 12, 0, 0),
(7, 'ibrahima Diakite', '2025-01-18', 'hgfbgfb', 'Féminin', 'adbnq', '92190993', 'Bac', '0', '', 2, '62662', 'ibrahima', 'Diakite', 'xdvgv', 'Segou', 'zadsffnhgf', '5848', 'dffc', 'Mali', 'ghdv', '', '', '5989', '/profile/Capture d’écran (1).png', 12, 0, 0),
(8, 'ibrahima Diakite', '2025-01-18', 'hgfbgfb', 'Féminin', 'adbnq', '92190993', 'Bac', '0', '', 2, '62662', 'ibrahima', 'Diakite', 'xdvgv', 'Segou', 'zadsffnhgf', '5848', 'dffc', 'Mali', 'ghdv', '', '', '5989', '/profile/Capture d’écran (1).png', 12, 0, 0),
(9, 'ibrahima Diakite', '2025-01-18', 'hgfbgfb', 'Féminin', 'adbnq', '92190993', 'Bac', '0', '', 2, '62662', 'ibrahima', 'Diakite', 'xdvgv', 'Segou', 'zadsffnhgf', '5848', 'dffc', 'Mali', 'ghdv', '', '', '5989', '/profile/Capture d’écran (1).png', 12, 0, 0),
(10, 'ibrahima Diakite', '2025-01-18', 'hgfbgfb', 'Féminin', 'adbnq', '92190993', 'Bac', '0', '', 2, '62662', 'ibrahima', 'Diakite', 'xdvgv', 'Segou', 'zadsffnhgf', '5848', 'dffc', 'Mali', 'ghdv', '', '', '5989', '/profile/Capture d’écran (1).png', 12, 0, 0),
(11, 'ibrahima Diakite', '2025-01-18', 'hgfbgfb', 'Féminin', 'adbnq', '92190993', 'Bac', '0', '', 2, '62662', 'ibrahima', 'Diakite', 'xdvgv', 'Segou', 'zadsffnhgf', '5848', 'dffc', 'Mali', 'ghdv', '', '', '5989', '/profile/Capture d’écran (1).png', 12, 0, 0),
(12, 'ibrahima Diakite', '2025-01-18', 'hgfbgfb', 'Féminin', 'adbnq', '92190993', 'Bac', '0', '', 2, '62662', 'ibrahima', 'Diakite', 'xdvgv', 'Segou', 'zadsffnhgf', '5848', 'dffc', 'Mali', 'ghdv', '', '', '5989', '/profile/Capture d’écran (1).png', 12, 0, 0),
(14, 'ibrahima Diakite', '2025-01-15', 'hgfbgfb', 'Féminin', 'adbnq', '92190993', 'Bac', '0', '', 1, '62662', 'ibrahima', 'Diakite', '', 'Segou', 'zadsffnhgf', '5848', 'dffc', 'Mali', 'ghdv', '', '', '5989', '/profile/Capture d’écran 2024-12-10 105059.jpg', 12, 0, 0),
(15, 'Guem Oumar', '2025-01-15', 'hgfbgfb', 'Féminin', 'adbnq', '92190997', 'BT', 'Regle', '', 2, '6266255', 'ibrahima', 'Diakite', 'xdvgv', 'Segou', 'zadsffnhgf', '5848', 'dffc', 'Mali', 'ghdv', '', '', '5989', '/profile/Capture d’écran 2025-01-05 0003444.jpg', 12, 0, 0),
(16, 'Guem Oumar', '2025-01-15', 'hgfbgfb', 'Masculin', 'adbnq', '92190997', 'Bac', 'Regle', '', 1, '6266255', 'ibrahima', 'Diakite', 'xdvgv', 'Segou', 'zadsffnhgf', '5848', 'dffc', 'Mali', 'ghdv', '', '', '5989', '/profile/Capture d’écran 2024-12-10 105059.jpg', 12, 0, 0),
(17, 'Guem Oumar', '2025-01-22', 'hgfbgfb', 'Masculin', 'adbnq', '92190997', 'Bac', 'Cl', '', 1, '6266255', 'ibrahima', 'Diakite', '', 'Segou', 'zadsffnhgf', '5848', 'dffc', 'Mali', 'ghdv', '', '', '5989', '/profile/Capture d’écran 2024-12-10 105059.jpg', 12, 0, 21000),
(20, 'Guem Oumar', '2025-01-15', 'hgfbgfb', 'Féminin', 'adbnq', '92190997', 'Bac', 'Regle', '', 1, '6266255', 'ibrahima', 'Diakite', 'xdvgv', 'Segou', 'zadsffnhgf', '5848', 'dffc', 'Mali', 'ghdv', '', '', '5989', '/profile/Capture d’écran 2025-01-05 000148.jpg', 12, 0, 26000),
(21, 'Guem Oumar', '2025-01-17', 'hgfbgfb', 'Féminin', 'adbnq', '92190997', 'Bac', 'Regle', '', 1, '6266255', 'ibrahima', 'Diakite', 'xdvgv', 'Segou', 'zadsffnhgf', '5848', 'dffc', 'Mali', 'ghdv', '', '', '5989', '/profile/Capture d’écran 2025-01-05 0003444.jpg', 12, 0, 6000),
(27, 'Guem Oumar', '2025-01-16', 'hgfbgfb', 'Féminin', 'adbnq', '92190997', 'Bac', 'Cl', '', 1, '6266255', 'ibrahima', 'Diakite', 'xdvgv', 'Segou', 'zadsffnhgf', '5848', 'dffc', 'Mali', 'ghdv', '', '', '5989', '/profile/Capture d’écran (1).png', 12, 0, 81000),
(28, 'Guem Oumar', '2025-01-16', 'hgfbgfb', 'Féminin', 'adbnq', '92190997', 'Bac', 'Regle', '', 1, '6266255', 'ibrahima', 'Diakite', 'xdvgv', 'Segou', 'zadsffnhgf', '5848', 'dffc', 'Mali', 'ghdv', '', '', '5989', '/profile/Capture d’écran 2025-01-05 000148.jpg', 12, 0, 6000),
(31, 'Guem Oumar', '2025-01-18', 'hgfbgfb', 'Féminin', 'adbnq', '92190997', 'Bac', 'Regle', '', 2, '6266255', 'ibrahima', 'Diakite', 'xdvgv', 'Segou', 'zadsffnhgf', '5848', 'dffc', 'Mali', 'ghdv', '', '', '5989', '/profile/Capture d’écran 2025-01-05 000231.jpg', 12, 0, 6000);

-- --------------------------------------------------------

--
-- Structure de la table `filiere`
--

CREATE TABLE `filiere` (
  `id_filiere` int(11) NOT NULL,
  `nom_filiere` varchar(250) NOT NULL,
  `sigle_filiere` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `filiere`
--

INSERT INTO `filiere` (`id_filiere`, `nom_filiere`, `sigle_filiere`) VALUES
(1, 'GENIE INFORMATIQUE', 'GI'),
(2, 'Marketing Communication', 'MC'),
(3, 'ASSITANT DE GESTION', 'AG');

-- --------------------------------------------------------

--
-- Structure de la table `grade`
--

CREATE TABLE `grade` (
  `id_grade` int(11) NOT NULL,
  `nom_grade` varchar(50) NOT NULL,
  `heures_dues` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `grade`
--

INSERT INTO `grade` (`id_grade`, `nom_grade`, `heures_dues`) VALUES
(1, 'Assistant', 168),
(2, 'Maître Assistant', 140),
(3, 'Maître de Conférences', 112),
(4, 'Professeur', 82);

-- --------------------------------------------------------

--
-- Structure de la table `horaire`
--

CREATE TABLE `horaire` (
  `id_horaire` int(11) NOT NULL,
  `heure_debut` time NOT NULL,
  `heure_fin` time NOT NULL,
  `id_edt` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `horaire`
--

INSERT INTO `horaire` (`id_horaire`, `heure_debut`, `heure_fin`, `id_edt`) VALUES
(1, '08:00:00', '10:00:00', 1),
(2, '11:00:00', '12:00:00', 1),
(3, '12:00:00', '14:00:00', 1),
(4, '10:00:00', '12:00:00', 2),
(5, '08:00:00', '10:00:00', 3),
(6, '10:15:00', '13:15:00', 3),
(7, '14:00:00', '16:00:00', 3),
(8, '16:00:00', '18:00:00', 3),
(9, '08:00:00', '10:00:00', 4),
(10, '10:15:00', '13:15:00', 4),
(11, '14:00:00', '16:00:00', 4),
(12, '16:00:00', '19:00:00', 4),
(13, '08:00:00', '10:00:00', 5),
(14, '08:00:00', '10:00:00', 6),
(15, '10:15:00', '13:15:00', 6),
(16, '14:00:00', '16:00:00', 6),
(17, '08:00:00', '10:00:00', 7),
(18, '10:15:00', '13:15:00', 7),
(19, '14:00:00', '16:00:00', 7),
(20, '08:00:00', '10:00:00', 8),
(21, '10:15:00', '13:15:00', 8),
(22, '14:00:00', '16:00:00', 8),
(23, '16:00:00', '19:00:00', 8),
(24, '08:00:00', '10:00:00', 9),
(25, '10:15:00', '13:15:00', 9),
(26, '14:00:00', '16:00:00', 9),
(27, '08:00:00', '10:00:00', 1),
(28, '10:15:00', '13:15:00', 1),
(29, '14:00:00', '16:00:00', 1),
(30, '16:00:00', '19:00:00', 1),
(31, '08:00:00', '10:00:00', 2),
(32, '10:15:00', '13:15:00', 2),
(33, '14:00:00', '16:00:00', 2),
(34, '16:00:00', '19:00:00', 2),
(35, '08:00:00', '10:00:00', 3),
(36, '08:00:00', '10:00:00', 4),
(37, '08:00:00', '10:00:00', 5),
(38, '10:15:00', '13:15:00', 5),
(39, '14:00:00', '16:00:00', 5),
(40, '16:00:00', '19:00:00', 5),
(41, '08:00:00', '10:00:00', 6),
(42, '10:15:00', '13:15:00', 6),
(43, '14:00:00', '16:00:00', 6),
(44, '16:00:00', '19:00:00', 6),
(45, '08:00:00', '10:00:00', 7),
(46, '10:15:00', '13:15:00', 7),
(47, '14:00:00', '16:00:00', 7),
(48, '16:00:00', '19:00:00', 7),
(49, '08:00:00', '10:00:00', 8),
(50, '10:15:00', '13:15:00', 8),
(51, '14:00:00', '16:00:00', 8),
(52, '08:00:00', '10:00:00', 1),
(53, '10:15:00', '13:15:00', 1),
(54, '14:00:00', '16:00:00', 1),
(55, '16:00:00', '19:00:00', 1),
(56, '08:00:00', '10:00:00', 2),
(57, '10:15:00', '13:15:00', 2),
(58, '14:00:00', '16:00:00', 2),
(59, '16:00:00', '19:00:00', 2),
(60, '08:00:00', '10:00:00', 3),
(61, '10:15:00', '13:15:00', 3),
(62, '14:00:00', '16:00:00', 3),
(63, '16:00:00', '19:00:00', 3),
(64, '08:00:00', '10:00:00', 4),
(65, '10:15:00', '13:15:00', 4),
(66, '08:00:00', '10:00:00', 5),
(67, '08:00:00', '10:00:00', 6),
(68, '10:15:00', '13:15:00', 6),
(69, '08:00:00', '10:00:00', 7),
(70, '10:15:00', '13:15:00', 7),
(71, '14:00:00', '16:00:00', 7),
(72, '16:00:00', '19:00:00', 7),
(73, '08:00:00', '10:00:00', 8),
(74, '08:00:00', '10:00:00', 9),
(75, '10:15:00', '13:15:00', 9),
(76, '08:00:00', '10:00:00', 10),
(77, '10:15:00', '13:15:00', 10),
(78, '08:00:00', '10:00:00', 11),
(79, '10:15:00', '13:15:00', 11),
(80, '14:00:00', '16:00:00', 11),
(81, '16:00:00', '19:00:00', 11);

-- --------------------------------------------------------

--
-- Structure de la table `jour`
--

CREATE TABLE `jour` (
  `id_jour` int(11) NOT NULL,
  `nom_jour` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `jour`
--

INSERT INTO `jour` (`id_jour`, `nom_jour`) VALUES
(1, 'Lundi'),
(2, 'Mardi'),
(3, 'Mercredi'),
(4, 'Jeudi'),
(5, 'Vendredi'),
(6, 'Samedi');

-- --------------------------------------------------------

--
-- Structure de la table `module`
--

CREATE TABLE `module` (
  `id_module` int(11) NOT NULL,
  `nom_module` varchar(250) NOT NULL,
  `sigle_module` varchar(50) NOT NULL,
  `id_filiere` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `module`
--

INSERT INTO `module` (`id_module`, `nom_module`, `sigle_module`, `id_filiere`) VALUES
(1, 'Mathematique', 'Maths', 0),
(2, 'Programmation Orienté Objet', 'POO', 0),
(3, 'Anglais ', 'Ag', 0),
(4, 'Français ', 'Fr', 0);

-- --------------------------------------------------------

--
-- Structure de la table `niveau_attribuer`
--

CREATE TABLE `niveau_attribuer` (
  `id_niveau_atribuer` int(11) NOT NULL,
  `id_enseignant` int(11) NOT NULL,
  `id_semestre` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `note_etudiant`
--

CREATE TABLE `note_etudiant` (
  `id_note` int(11) NOT NULL,
  `note_devoir` decimal(10,0) DEFAULT NULL,
  `note_evaluation` decimal(10,0) DEFAULT NULL,
  `note_session` decimal(10,0) DEFAULT NULL,
  `id_etudiant` int(11) NOT NULL,
  `id_promotion` int(11) NOT NULL,
  `id_module` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `note_etudiant`
--

INSERT INTO `note_etudiant` (`id_note`, `note_devoir`, `note_evaluation`, `note_session`, `id_etudiant`, `id_promotion`, `id_module`) VALUES
(1, '15', '17', '0', 15, 13, 6);

-- --------------------------------------------------------

--
-- Structure de la table `parcours`
--

CREATE TABLE `parcours` (
  `id_parcours` int(11) NOT NULL,
  `id_filiere` int(11) NOT NULL,
  `id_semestre` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `parcours`
--

INSERT INTO `parcours` (`id_parcours`, `id_filiere`, `id_semestre`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 2, 1),
(4, 3, 3);

-- --------------------------------------------------------

--
-- Structure de la table `payement`
--

CREATE TABLE `payement` (
  `idPayem` int(11) NOT NULL,
  `montant_paye` int(255) NOT NULL,
  `idEtudt` int(11) NOT NULL,
  `annee` int(255) NOT NULL,
  `idPromo` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `payement`
--

INSERT INTO `payement` (`idPayem`, `montant_paye`, `idEtudt`, `annee`, `idPromo`, `date`) VALUES
(1, 111, 14, 2025, 0, '2025-01-15 00:00:00'),
(2, 58888, 15, 2025, 0, '2025-01-15 00:00:00'),
(3, 20000, 16, 2025, 0, '2025-01-15 00:00:00'),
(4, 15000, 17, 2025, 0, '2025-01-15 00:00:00'),
(5, 20000, 20, 2025, 0, '2025-01-15 00:00:00'),
(6, 0, 21, 2025, 0, '2025-01-16 00:00:00'),
(7, 81000, 27, 2025, 0, '2025-01-16 00:00:00'),
(8, 5000, 28, 2025, 0, '2025-01-16 00:00:00'),
(9, 6000, 31, 2025, 0, '2025-01-18 18:17:00'),
(10, 5000, 17, 0, 0, '2025-01-19 01:55:41'),
(11, 1000, 17, 0, 0, '2025-01-19 01:57:20'),
(12, 2000, 31, 0, 0, '2025-01-19 02:00:39'),
(13, 500, 28, 0, 0, '2025-01-19 02:01:06'),
(14, 500, 28, 0, 0, '2025-01-19 02:01:20'),
(15, 100, 21, 0, 0, '2025-01-19 02:02:44'),
(16, 5000, 21, 0, 0, '2025-01-19 02:02:59'),
(17, 900, 21, 0, 0, '2025-01-19 02:03:15'),
(18, 151, 5, 0, 0, '2025-01-20 13:52:16'),
(19, -151, 5, 0, 0, '2025-01-20 13:53:24');

-- --------------------------------------------------------

--
-- Structure de la table `periode`
--

CREATE TABLE `periode` (
  `id_periode` int(11) NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `status` varchar(10) DEFAULT 'inachevé'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `periode`
--

INSERT INTO `periode` (`id_periode`, `date_debut`, `date_fin`, `status`) VALUES
(1, '2025-01-17', '2025-07-17', 'achevé'),
(2, '2025-07-17', '2025-12-17', 'inachevé ');

-- --------------------------------------------------------

--
-- Structure de la table `promotion`
--

CREATE TABLE `promotion` (
  `id_promotion` int(11) NOT NULL,
  `annee_universitaire` varchar(250) NOT NULL,
  `statut` tinyint(4) NOT NULL,
  `id_filiere` int(11) NOT NULL,
  `id_parcours` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `promotion`
--

INSERT INTO `promotion` (`id_promotion`, `annee_universitaire`, `statut`, `id_filiere`, `id_parcours`) VALUES
(9, '2024-2025', 0, 1, 1),
(10, '2023-2024', 0, 2, 3),
(11, '2022-2023', 0, 1, 2),
(12, '2021-2022', 0, 3, 4),
(13, '2020-2021', 0, 2, 3);

-- --------------------------------------------------------

--
-- Structure de la table `salle`
--

CREATE TABLE `salle` (
  `id_salle` int(11) NOT NULL,
  `nom_salle` varchar(250) NOT NULL,
  `capacite_salle` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `salle`
--

INSERT INTO `salle` (`id_salle`, `nom_salle`, `capacite_salle`) VALUES
(1, 'E2-1', 12),
(2, 'E2-2', 20);

-- --------------------------------------------------------

--
-- Structure de la table `semestre`
--

CREATE TABLE `semestre` (
  `id_semestre` int(11) NOT NULL,
  `sigle_semestre` varchar(100) NOT NULL,
  `nom_semestre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `semestre`
--

INSERT INTO `semestre` (`id_semestre`, `sigle_semestre`, `nom_semestre`) VALUES
(1, 'S1', 'Semestre1'),
(2, 'S2', 'Semestre2'),
(3, 'S5', 'Semestre5');

-- --------------------------------------------------------

--
-- Structure de la table `tache`
--

CREATE TABLE `tache` (
  `id_tache` int(11) NOT NULL,
  `type_tache` varchar(50) NOT NULL,
  `id_horaire` int(11) NOT NULL,
  `id_jour` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `tache`
--

INSERT INTO `tache` (`id_tache`, `type_tache`, `id_horaire`, `id_jour`) VALUES
(1, 'cm', 1, 1),
(2, 'cm', 1, 2),
(3, 'cm', 1, 3),
(4, 'cm', 1, 4),
(5, 'td', 1, 5),
(6, 'examen', 1, 6),
(7, 'cm', 2, 1),
(8, 'cm', 2, 2),
(9, 'cm', 2, 3),
(10, 'cm', 2, 4),
(11, 'td', 2, 5),
(12, 'x', 2, 6),
(13, 'cm', 3, 1),
(14, 'cm', 3, 2),
(15, 'cm', 3, 3),
(16, 'cm', 3, 4),
(17, 'td', 3, 5),
(18, 'x', 3, 6),
(19, 'cm', 4, 1),
(20, 'cm', 4, 2),
(21, 'cm', 4, 3),
(22, 'cm', 4, 4),
(23, 'cm', 4, 5),
(24, 'examen', 4, 6),
(25, 'cm', 5, 1),
(26, 'cm', 5, 2),
(27, 'cm', 5, 3),
(28, 'cm', 5, 4),
(29, 'cm', 5, 5),
(30, 'examen', 5, 6),
(31, 'cm', 6, 1),
(32, 'cm', 6, 2),
(33, 'cm', 6, 3),
(34, 'cm', 6, 4),
(35, 'cm', 6, 5),
(36, 'x', 6, 6),
(37, 'cm', 7, 1),
(38, 'cm', 7, 2),
(39, 'cm', 7, 3),
(40, 'cm', 7, 4),
(41, 'cm', 7, 5),
(42, 'x', 7, 6),
(43, 'cm', 8, 1),
(44, 'cm', 8, 2),
(45, 'cm', 8, 3),
(46, 'cm', 8, 4),
(47, 'cm', 8, 5),
(48, 'x', 8, 6),
(49, 'td', 9, 1),
(50, 'td', 9, 2),
(51, 'td', 9, 3),
(52, 'td', 9, 4),
(53, 'td', 9, 5),
(54, 'examen', 9, 6),
(55, 'td', 10, 1),
(56, 'td', 10, 2),
(57, 'td', 10, 3),
(58, 'td', 10, 4),
(59, 'td', 10, 5),
(60, 'x', 10, 6),
(61, 'td', 11, 1),
(62, 'td', 11, 2),
(63, 'td', 11, 3),
(64, 'td', 11, 4),
(65, 'td', 11, 5),
(66, 'x', 11, 6),
(67, 'td', 12, 1),
(68, 'td', 12, 2),
(69, 'td', 12, 3),
(70, 'td', 12, 4),
(71, 'td', 12, 5),
(72, 'x', 12, 6),
(73, 'td', 13, 1),
(74, 'td', 13, 2),
(75, 'td', 13, 3),
(76, 'td', 13, 4),
(77, 'td', 13, 5),
(78, 'examen', 13, 6),
(79, 'td', 14, 1),
(80, 'td', 14, 2),
(81, 'td', 14, 3),
(82, 'td', 14, 4),
(83, 'td', 14, 5),
(84, 'examen', 14, 6),
(85, 'td', 15, 1),
(86, 'td', 15, 2),
(87, 'td', 15, 3),
(88, 'td', 15, 4),
(89, 'td', 15, 5),
(90, 'x', 15, 6),
(91, 'td', 16, 1),
(92, 'td', 16, 2),
(93, 'td', 16, 3),
(94, 'td', 16, 4),
(95, 'td', 16, 5),
(96, 'x', 16, 6),
(97, 'td', 17, 1),
(98, 'td', 17, 2),
(99, 'x', 17, 3),
(100, 'x', 17, 4),
(101, 'x', 17, 5),
(102, 'examen', 17, 6),
(103, 'td', 18, 1),
(104, 'td', 18, 2),
(105, 'x', 18, 3),
(106, 'x', 18, 4),
(107, 'x', 18, 5),
(108, 'x', 18, 6),
(109, 'td', 19, 1),
(110, 'td', 19, 2),
(111, 'x', 19, 3),
(112, 'x', 19, 4),
(113, 'x', 19, 5),
(114, 'x', 19, 6),
(115, 'td', 20, 1),
(116, 'td', 20, 2),
(117, 'td', 20, 3),
(118, 'td', 20, 4),
(119, 'td', 20, 5),
(120, 'examen', 20, 6),
(121, 'td', 21, 1),
(122, 'td', 21, 2),
(123, 'td', 21, 3),
(124, 'td', 21, 4),
(125, 'td', 21, 5),
(126, 'x', 21, 6),
(127, 'td', 22, 1),
(128, 'td', 22, 2),
(129, 'td', 22, 3),
(130, 'td', 22, 4),
(131, 'td', 22, 5),
(132, 'x', 22, 6),
(133, 'td', 23, 1),
(134, 'td', 23, 2),
(135, 'td', 23, 3),
(136, 'td', 23, 4),
(137, 'td', 23, 5),
(138, 'x', 23, 6),
(139, 'td', 24, 1),
(140, 'td', 24, 2),
(141, 'td', 24, 3),
(142, 'td', 24, 4),
(143, 'td', 24, 5),
(144, 'examen', 24, 6),
(145, 'td', 25, 1),
(146, 'td', 25, 2),
(147, 'td', 25, 3),
(148, 'td', 25, 4),
(149, 'td', 25, 5),
(150, 'x', 25, 6),
(151, 'td', 26, 1),
(152, 'td', 26, 2),
(153, 'td', 26, 3),
(154, 'td', 26, 4),
(155, 'td', 26, 5),
(156, 'x', 26, 6),
(157, 'cm', 27, 1),
(158, 'td', 27, 2),
(159, 'td', 27, 3),
(160, 'td', 27, 4),
(161, 'td', 27, 5),
(162, 'examen', 27, 6),
(163, 'td', 28, 1),
(164, 'td', 28, 2),
(165, 'td', 28, 3),
(166, 'td', 28, 4),
(167, 'td', 28, 5),
(168, 'x', 28, 6),
(169, 'td', 29, 1),
(170, 'td', 29, 2),
(171, 'td', 29, 3),
(172, 'td', 29, 4),
(173, 'td', 29, 5),
(174, 'x', 29, 6),
(175, 'td', 30, 1),
(176, 'td', 30, 2),
(177, 'td', 30, 3),
(178, 'td', 30, 4),
(179, 'td', 30, 5),
(180, 'x', 30, 6),
(181, 'td', 31, 1),
(182, 'td', 31, 2),
(183, 'td', 31, 3),
(184, 'td', 31, 4),
(185, 'td', 31, 5),
(186, 'examen', 31, 6),
(187, 'td', 32, 1),
(188, 'td', 32, 2),
(189, 'td', 32, 3),
(190, 'td', 32, 4),
(191, 'td', 32, 5),
(192, 'x', 32, 6),
(193, 'td', 33, 1),
(194, 'td', 33, 2),
(195, 'td', 33, 3),
(196, 'td', 33, 4),
(197, 'td', 33, 5),
(198, 'x', 33, 6),
(199, 'td', 34, 1),
(200, 'td', 34, 2),
(201, 'td', 34, 3),
(202, 'td', 34, 4),
(203, 'td', 34, 5),
(204, 'x', 34, 6),
(205, 'td', 35, 1),
(206, 'td', 35, 2),
(207, 'td', 35, 3),
(208, 'td', 35, 4),
(209, 'td', 35, 5),
(210, 'examen', 35, 6),
(211, 'td', 36, 1),
(212, 'td', 36, 2),
(213, 'td', 36, 3),
(214, 'td', 36, 4),
(215, 'td', 36, 5),
(216, 'examen', 36, 6),
(217, 'td', 37, 1),
(218, 'td', 37, 2),
(219, 'td', 37, 3),
(220, 'td', 37, 4),
(221, 'td', 37, 5),
(222, 'examen', 37, 6),
(223, 'td', 38, 1),
(224, 'td', 38, 2),
(225, 'td', 38, 3),
(226, 'td', 38, 4),
(227, 'td', 38, 5),
(228, 'x', 38, 6),
(229, 'td', 39, 1),
(230, 'td', 39, 2),
(231, 'td', 39, 3),
(232, 'td', 39, 4),
(233, 'td', 39, 5),
(234, 'x', 39, 6),
(235, 'td', 40, 1),
(236, 'td', 40, 2),
(237, 'td', 40, 3),
(238, 'td', 40, 4),
(239, 'td', 40, 5),
(240, 'x', 40, 6),
(241, 'td', 41, 1),
(242, 'td', 41, 2),
(243, 'td', 41, 3),
(244, 'td', 41, 4),
(245, 'td', 41, 5),
(246, 'examen', 41, 6),
(247, 'td', 42, 1),
(248, 'td', 42, 2),
(249, 'td', 42, 3),
(250, 'td', 42, 4),
(251, 'td', 42, 5),
(252, 'x', 42, 6),
(253, 'td', 43, 1),
(254, 'td', 43, 2),
(255, 'td', 43, 3),
(256, 'td', 43, 4),
(257, 'td', 43, 5),
(258, 'x', 43, 6),
(259, 'td', 44, 1),
(260, 'td', 44, 2),
(261, 'td', 44, 3),
(262, 'td', 44, 4),
(263, 'td', 44, 5),
(264, 'x', 44, 6),
(265, 'td', 45, 1),
(266, 'td', 45, 2),
(267, 'td', 45, 3),
(268, 'td', 45, 4),
(269, 'td', 45, 5),
(270, 'examen', 45, 6),
(271, 'td', 46, 1),
(272, 'td', 46, 2),
(273, 'td', 46, 3),
(274, 'td', 46, 4),
(275, 'td', 46, 5),
(276, 'x', 46, 6),
(277, 'td', 47, 1),
(278, 'td', 47, 2),
(279, 'td', 47, 3),
(280, 'td', 47, 4),
(281, 'td', 47, 5),
(282, 'x', 47, 6),
(283, 'td', 48, 1),
(284, 'td', 48, 2),
(285, 'td', 48, 3),
(286, 'td', 48, 4),
(287, 'td', 48, 5),
(288, 'x', 48, 6),
(289, 'td', 49, 1),
(290, 'td', 49, 2),
(291, 'td', 49, 3),
(292, 'td', 49, 4),
(293, 'td', 49, 5),
(294, 'examen', 49, 6),
(295, 'td', 50, 1),
(296, 'td', 50, 2),
(297, 'td', 50, 3),
(298, 'td', 50, 4),
(299, 'td', 50, 5),
(300, 'x', 50, 6),
(301, 'td', 51, 1),
(302, 'td', 51, 2),
(303, 'td', 51, 3),
(304, 'td', 51, 4),
(305, 'td', 51, 5),
(306, 'x', 51, 6),
(307, 'td', 52, 1),
(308, 'td', 52, 2),
(309, 'td', 52, 3),
(310, 'td', 52, 4),
(311, 'td', 52, 5),
(312, 'examen', 52, 6),
(313, 'td', 53, 1),
(314, 'td', 53, 2),
(315, 'td', 53, 3),
(316, 'td', 53, 4),
(317, 'td', 53, 5),
(318, 'x', 53, 6),
(319, 'td', 54, 1),
(320, 'td', 54, 2),
(321, 'td', 54, 3),
(322, 'td', 54, 4),
(323, 'td', 54, 5),
(324, 'x', 54, 6),
(325, 'td', 55, 1),
(326, 'td', 55, 2),
(327, 'td', 55, 3),
(328, 'td', 55, 4),
(329, 'td', 55, 5),
(330, 'x', 55, 6),
(331, 'td', 56, 1),
(332, 'td', 56, 2),
(333, 'td', 56, 3),
(334, 'td', 56, 4),
(335, 'td', 56, 5),
(336, 'examen', 56, 6),
(337, 'td', 57, 1),
(338, 'td', 57, 2),
(339, 'td', 57, 3),
(340, 'td', 57, 4),
(341, 'td', 57, 5),
(342, 'x', 57, 6),
(343, 'td', 58, 1),
(344, 'td', 58, 2),
(345, 'td', 58, 3),
(346, 'td', 58, 4),
(347, 'td', 58, 5),
(348, 'x', 58, 6),
(349, 'td', 59, 1),
(350, 'td', 59, 2),
(351, 'td', 59, 3),
(352, 'td', 59, 4),
(353, 'td', 59, 5),
(354, 'x', 59, 6),
(355, 'td', 60, 1),
(356, 'td', 60, 2),
(357, 'td', 60, 3),
(358, 'td', 60, 4),
(359, 'td', 60, 5),
(360, 'examen', 60, 6),
(361, 'td', 61, 1),
(362, 'td', 61, 2),
(363, 'td', 61, 3),
(364, 'td', 61, 4),
(365, 'td', 61, 5),
(366, 'x', 61, 6),
(367, 'td', 62, 1),
(368, 'td', 62, 2),
(369, 'td', 62, 3),
(370, 'td', 62, 4),
(371, 'td', 62, 5),
(372, 'x', 62, 6),
(373, 'td', 63, 1),
(374, 'td', 63, 2),
(375, 'td', 63, 3),
(376, 'td', 63, 4),
(377, 'td', 63, 5),
(378, 'x', 63, 6),
(379, 'td', 64, 1),
(380, 'td', 64, 2),
(381, 'td', 64, 3),
(382, 'td', 64, 4),
(383, 'td', 64, 5),
(384, 'examen', 64, 6),
(385, 'td', 65, 1),
(386, 'td', 65, 2),
(387, 'td', 65, 3),
(388, 'td', 65, 4),
(389, 'td', 65, 5),
(390, 'x', 65, 6),
(391, 'td', 66, 1),
(392, 'td', 66, 2),
(393, 'td', 66, 3),
(394, 'td', 66, 4),
(395, 'td', 66, 5),
(396, 'examen', 66, 6),
(397, 'td', 67, 1),
(398, 'td', 67, 2),
(399, 'td', 67, 3),
(400, 'td', 67, 4),
(401, 'td', 67, 5),
(402, 'examen', 67, 6),
(403, 'td', 68, 1),
(404, 'td', 68, 2),
(405, 'td', 68, 3),
(406, 'td', 68, 4),
(407, 'td', 68, 5),
(408, 'x', 68, 6),
(409, 'td', 69, 1),
(410, 'td', 69, 2),
(411, 'td', 69, 3),
(412, 'td', 69, 4),
(413, 'td', 69, 5),
(414, 'examen', 69, 6),
(415, 'td', 70, 1),
(416, 'td', 70, 2),
(417, 'td', 70, 3),
(418, 'td', 70, 4),
(419, 'td', 70, 5),
(420, 'x', 70, 6),
(421, 'td', 71, 1),
(422, 'td', 71, 2),
(423, 'td', 71, 3),
(424, 'td', 71, 4),
(425, 'td', 71, 5),
(426, 'x', 71, 6),
(427, 'td', 72, 1),
(428, 'td', 72, 2),
(429, 'td', 72, 3),
(430, 'td', 72, 4),
(431, 'td', 72, 5),
(432, 'x', 72, 6),
(433, 'td', 73, 1),
(434, 'td', 73, 2),
(435, 'td', 73, 3),
(436, 'td', 73, 4),
(437, 'td', 73, 5),
(438, 'examen', 73, 6),
(439, 'td', 74, 1),
(440, 'td', 74, 2),
(441, 'td', 74, 3),
(442, 'td', 74, 4),
(443, 'td', 74, 5),
(444, 'examen', 74, 6),
(445, 'td', 75, 1),
(446, 'td', 75, 2),
(447, 'td', 75, 3),
(448, 'td', 75, 4),
(449, 'td', 75, 5),
(450, 'x', 75, 6),
(451, 'td', 76, 1),
(452, 'td', 76, 2),
(453, 'td', 76, 3),
(454, 'td', 76, 4),
(455, 'td', 76, 5),
(456, 'examen', 76, 6),
(457, 'td', 77, 1),
(458, 'td', 77, 2),
(459, 'td', 77, 3),
(460, 'td', 77, 4),
(461, 'td', 77, 5),
(462, 'x', 77, 6),
(463, 'td', 78, 1),
(464, 'td', 78, 2),
(465, 'td', 78, 3),
(466, 'td', 78, 4),
(467, 'td', 78, 5),
(468, 'examen', 78, 6),
(469, 'td', 79, 1),
(470, 'td', 79, 2),
(471, 'td', 79, 3),
(472, 'td', 79, 4),
(473, 'td', 79, 5),
(474, 'x', 79, 6),
(475, 'td', 80, 1),
(476, 'td', 80, 2),
(477, 'td', 80, 3),
(478, 'td', 80, 4),
(479, 'td', 80, 5),
(480, 'x', 80, 6),
(481, 'td', 81, 1),
(482, 'td', 81, 2),
(483, 'td', 81, 3),
(484, 'td', 81, 4),
(485, 'td', 81, 5),
(486, 'x', 81, 6);

-- --------------------------------------------------------

--
-- Structure de la table `ue`
--

CREATE TABLE `ue` (
  `id_ue` int(11) NOT NULL,
  `nom_ue` varchar(250) NOT NULL,
  `sigle_ue` varchar(50) NOT NULL,
  `id_parcours` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `ue`
--

INSERT INTO `ue` (`id_ue`, `nom_ue`, `sigle_ue`, `id_parcours`) VALUES
(1, 'PHQ', '', 1),
(3, 'PhCh', '', 3),
(4, 'ue1', '', 2),
(5, 'TECE', '', 4);

-- --------------------------------------------------------

--
-- Structure de la table `ue_module`
--

CREATE TABLE `ue_module` (
  `id_ue_module` int(11) NOT NULL,
  `id_ue` int(11) NOT NULL,
  `id_module` int(11) NOT NULL,
  `code_module` varchar(250) NOT NULL,
  `coeficient` int(11) NOT NULL,
  `cm` int(11) DEFAULT NULL,
  `td` int(11) DEFAULT NULL,
  `tp` int(11) DEFAULT NULL,
  `tpe` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `ue_module`
--

INSERT INTO `ue_module` (`id_ue_module`, `id_ue`, `id_module`, `code_module`, `coeficient`, `cm`, `td`, `tp`, `tpe`) VALUES
(1, 1, 1, 'hh', 4, 2, 3, 3, 1),
(2, 1, 2, 'ff', 4, 2, 2, 2, 2),
(4, 3, 2, 'PhCh1', 10, 10, 10, 10, 10),
(5, 4, 2, 'sfdf', 2, 10, 10, 10, 10),
(6, 3, 1, 'Phch2', 2, 30, 0, 0, 0),
(7, 3, 1, 'phCh-3', 1, 20, 0, 0, 0),
(8, 5, 3, 'tec', 3, 60, 0, 0, 0),
(9, 5, 4, 'tec', 3, 60, 0, 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `unite_enseigner`
--

CREATE TABLE `unite_enseigner` (
  `id_niveau_enseigner` int(11) NOT NULL,
  `id_niveau_accademique` int(11) NOT NULL,
  `id_ue` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id_utilisateur` int(11) NOT NULL,
  `nom_prenom` varchar(200) NOT NULL,
  `contact_utilisateur` varchar(70) NOT NULL,
  `email_utilisateurs` varchar(100) NOT NULL,
  `mot_passe` varchar(100) NOT NULL,
  `role` varchar(100) NOT NULL,
  `signature` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_utilisateur`, `nom_prenom`, `contact_utilisateur`, `email_utilisateurs`, `mot_passe`, `role`, `signature`) VALUES
(1, 'Fatoumata Tangara', '75484134', 'tangara@gmail.com', '123456', 'SupAdmin', ''),
(2, 'Amadou Karembé', '789654', 'amadou@gmail.com', '$2y$10$CdoKgRgM16rtuVOQNjP7A.uwySLRDt6KAvPd1RPzZA/vcoRgYbFC6', 'SupAdmin', '/signature/profile_678e33fa1e69d5.17850309.jpg'),
(3, 'Barry Moustapha', '789654', 'barry@mail.com', '$2y$10$IPSaH8iiARkEtclWe2nN4uvb8FOn3.TkUyQOHEjkahnmljWAymAti', 'DR', '/signature/profile_678f8e1589b658.73785492.jpg'),
(4, 'Fatoumata Tangara', '75484134', 'tangara@gmail.com', '123456', 'SupAdmin', ''),
(5, 'Amadou Karembé', '789654', 'amadou@gmail.com', '$2y$10$CdoKgRgM16rtuVOQNjP7A.uwySLRDt6KAvPd1RPzZA/vcoRgYbFC6', 'SupAdmin', '/signature/profile_678e33fa1e69d5.17850309.jpg'),
(6, 'Barry Moustapha', '789654', 'barry@mail.com', '$2y$10$IPSaH8iiARkEtclWe2nN4uvb8FOn3.TkUyQOHEjkahnmljWAymAti', 'DR', '/signature/profile_678f8e1589b658.73785492.jpg'),
(7, 'Abdoulaye Koné', '75659842', 'kkabdoulaye514@gmail.com', '123456', 'Enseignant', 'sdsdsd'),
(8, 'Abdoulaye Koné', '75659842', 'kkabdoulaye514@gmail.com', '123456', 'Enseignant', 'sdsdsd');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `anne_universitaire`
--
ALTER TABLE `anne_universitaire`
  ADD PRIMARY KEY (`id_anne`);

--
-- Index pour la table `edt`
--
ALTER TABLE `edt`
  ADD PRIMARY KEY (`id_edt`),
  ADD KEY `edt_ibfk_1` (`id_module`),
  ADD KEY `edt_ibfk_2` (`id_enseignant`),
  ADD KEY `edt_ibfk_3` (`id_salle`),
  ADD KEY `edt_ibfk_4` (`id_filiere`),
  ADD KEY `edt_ibfk_5` (`id_promotion`);

--
-- Index pour la table `emargement`
--
ALTER TABLE `emargement`
  ADD PRIMARY KEY (`id_emargement`),
  ADD KEY `id_enseignant` (`id_enseignant`),
  ADD KEY `id_filiere` (`id_filiere`),
  ADD KEY `id_semestre` (`id_semestre`);

--
-- Index pour la table `enseignants`
--
ALTER TABLE `enseignants`
  ADD PRIMARY KEY (`enseignant_id`),
  ADD KEY `id_grade` (`id_grade`);

--
-- Index pour la table `enseigner`
--
ALTER TABLE `enseigner`
  ADD PRIMARY KEY (`id_enseigner`),
  ADD KEY `id_enseignant` (`id_enseignant`),
  ADD KEY `id_module` (`id_module`);

--
-- Index pour la table `etudiant`
--
ALTER TABLE `etudiant`
  ADD PRIMARY KEY (`id_etudiant`),
  ADD KEY `id_promotion` (`id_promotion`);

--
-- Index pour la table `filiere`
--
ALTER TABLE `filiere`
  ADD PRIMARY KEY (`id_filiere`);

--
-- Index pour la table `grade`
--
ALTER TABLE `grade`
  ADD PRIMARY KEY (`id_grade`);

--
-- Index pour la table `horaire`
--
ALTER TABLE `horaire`
  ADD PRIMARY KEY (`id_horaire`),
  ADD KEY `horaire_ibfk_1` (`id_edt`);

--
-- Index pour la table `jour`
--
ALTER TABLE `jour`
  ADD PRIMARY KEY (`id_jour`);

--
-- Index pour la table `module`
--
ALTER TABLE `module`
  ADD PRIMARY KEY (`id_module`),
  ADD KEY `id_filiere` (`id_filiere`);

--
-- Index pour la table `niveau_attribuer`
--
ALTER TABLE `niveau_attribuer`
  ADD PRIMARY KEY (`id_niveau_atribuer`),
  ADD KEY `id_enseignant` (`id_enseignant`,`id_semestre`) USING BTREE,
  ADD KEY `niveau_attribuer_ibfk_2` (`id_semestre`);

--
-- Index pour la table `note_etudiant`
--
ALTER TABLE `note_etudiant`
  ADD PRIMARY KEY (`id_note`),
  ADD KEY `id_etudiant` (`id_etudiant`),
  ADD KEY `id_module` (`id_module`),
  ADD KEY `id_promotion` (`id_promotion`);

--
-- Index pour la table `parcours`
--
ALTER TABLE `parcours`
  ADD PRIMARY KEY (`id_parcours`),
  ADD KEY `parcours_ibfk_1` (`id_filiere`),
  ADD KEY `parcours__ibfk_2` (`id_semestre`);

--
-- Index pour la table `payement`
--
ALTER TABLE `payement`
  ADD PRIMARY KEY (`idPayem`),
  ADD KEY `idEtudt` (`idEtudt`),
  ADD KEY `idPromo` (`idPromo`);

--
-- Index pour la table `periode`
--
ALTER TABLE `periode`
  ADD PRIMARY KEY (`id_periode`),
  ADD UNIQUE KEY `date_debut` (`date_debut`,`date_fin`),
  ADD UNIQUE KEY `date_debut_2` (`date_debut`,`date_fin`);

--
-- Index pour la table `promotion`
--
ALTER TABLE `promotion`
  ADD PRIMARY KEY (`id_promotion`),
  ADD KEY `promotion_ibfk_1` (`id_filiere`),
  ADD KEY `promotion_ibfk_2` (`id_parcours`);

--
-- Index pour la table `salle`
--
ALTER TABLE `salle`
  ADD PRIMARY KEY (`id_salle`);

--
-- Index pour la table `semestre`
--
ALTER TABLE `semestre`
  ADD PRIMARY KEY (`id_semestre`);

--
-- Index pour la table `tache`
--
ALTER TABLE `tache`
  ADD PRIMARY KEY (`id_tache`),
  ADD KEY `jour_ibfk_1` (`id_horaire`),
  ADD KEY `jour_ibfk_2` (`id_jour`);

--
-- Index pour la table `ue`
--
ALTER TABLE `ue`
  ADD PRIMARY KEY (`id_ue`),
  ADD KEY `id_module` (`id_parcours`);

--
-- Index pour la table `ue_module`
--
ALTER TABLE `ue_module`
  ADD PRIMARY KEY (`id_ue_module`),
  ADD KEY `ue_module__ibfk_1` (`id_ue`),
  ADD KEY `ue_module_ibfk_2` (`id_module`);

--
-- Index pour la table `unite_enseigner`
--
ALTER TABLE `unite_enseigner`
  ADD PRIMARY KEY (`id_niveau_enseigner`),
  ADD KEY `id_ue` (`id_ue`),
  ADD KEY `id_niveau_accademique` (`id_niveau_accademique`,`id_ue`) USING BTREE;

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id_utilisateur`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `anne_universitaire`
--
ALTER TABLE `anne_universitaire`
  MODIFY `id_anne` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `edt`
--
ALTER TABLE `edt`
  MODIFY `id_edt` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `emargement`
--
ALTER TABLE `emargement`
  MODIFY `id_emargement` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `enseignants`
--
ALTER TABLE `enseignants`
  MODIFY `enseignant_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `enseigner`
--
ALTER TABLE `enseigner`
  MODIFY `id_enseigner` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `etudiant`
--
ALTER TABLE `etudiant`
  MODIFY `id_etudiant` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT pour la table `filiere`
--
ALTER TABLE `filiere`
  MODIFY `id_filiere` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `grade`
--
ALTER TABLE `grade`
  MODIFY `id_grade` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `horaire`
--
ALTER TABLE `horaire`
  MODIFY `id_horaire` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT pour la table `jour`
--
ALTER TABLE `jour`
  MODIFY `id_jour` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `module`
--
ALTER TABLE `module`
  MODIFY `id_module` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `niveau_attribuer`
--
ALTER TABLE `niveau_attribuer`
  MODIFY `id_niveau_atribuer` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `note_etudiant`
--
ALTER TABLE `note_etudiant`
  MODIFY `id_note` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `parcours`
--
ALTER TABLE `parcours`
  MODIFY `id_parcours` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `payement`
--
ALTER TABLE `payement`
  MODIFY `idPayem` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT pour la table `periode`
--
ALTER TABLE `periode`
  MODIFY `id_periode` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `promotion`
--
ALTER TABLE `promotion`
  MODIFY `id_promotion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `salle`
--
ALTER TABLE `salle`
  MODIFY `id_salle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `semestre`
--
ALTER TABLE `semestre`
  MODIFY `id_semestre` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `tache`
--
ALTER TABLE `tache`
  MODIFY `id_tache` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=487;

--
-- AUTO_INCREMENT pour la table `ue`
--
ALTER TABLE `ue`
  MODIFY `id_ue` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `ue_module`
--
ALTER TABLE `ue_module`
  MODIFY `id_ue_module` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `unite_enseigner`
--
ALTER TABLE `unite_enseigner`
  MODIFY `id_niveau_enseigner` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id_utilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `edt`
--
ALTER TABLE `edt`
  ADD CONSTRAINT `edt_ibfk_1` FOREIGN KEY (`id_module`) REFERENCES `ue_module` (`id_ue_module`),
  ADD CONSTRAINT `edt_ibfk_2` FOREIGN KEY (`id_enseignant`) REFERENCES `enseignants` (`enseignant_id`),
  ADD CONSTRAINT `edt_ibfk_3` FOREIGN KEY (`id_salle`) REFERENCES `salle` (`id_salle`),
  ADD CONSTRAINT `edt_ibfk_4` FOREIGN KEY (`id_filiere`) REFERENCES `filiere` (`id_filiere`),
  ADD CONSTRAINT `edt_ibfk_5` FOREIGN KEY (`id_promotion`) REFERENCES `promotion` (`id_promotion`);

--
-- Contraintes pour la table `enseignants`
--
ALTER TABLE `enseignants`
  ADD CONSTRAINT `enseignants_ibfk_1` FOREIGN KEY (`id_grade`) REFERENCES `grade` (`id_grade`);

--
-- Contraintes pour la table `etudiant`
--
ALTER TABLE `etudiant`
  ADD CONSTRAINT `etudiant_ibfk_1` FOREIGN KEY (`id_promotion`) REFERENCES `promotion` (`id_promotion`);

--
-- Contraintes pour la table `note_etudiant`
--
ALTER TABLE `note_etudiant`
  ADD CONSTRAINT `note_etudiant_ibfk_1` FOREIGN KEY (`id_etudiant`) REFERENCES `etudiant` (`id_etudiant`),
  ADD CONSTRAINT `note_etudiant_ibfk_2` FOREIGN KEY (`id_module`) REFERENCES `ue_module` (`id_ue_module`),
  ADD CONSTRAINT `note_etudiant_ibfk_3` FOREIGN KEY (`id_promotion`) REFERENCES `promotion` (`id_promotion`);

--
-- Contraintes pour la table `parcours`
--
ALTER TABLE `parcours`
  ADD CONSTRAINT `parcours__ibfk_2` FOREIGN KEY (`id_semestre`) REFERENCES `semestre` (`id_semestre`),
  ADD CONSTRAINT `parcours_ibfk_1` FOREIGN KEY (`id_filiere`) REFERENCES `filiere` (`id_filiere`);

--
-- Contraintes pour la table `payement`
--
ALTER TABLE `payement`
  ADD CONSTRAINT `payement_ibfk_1` FOREIGN KEY (`idEtudt`) REFERENCES `etudiant` (`id_etudiant`);

--
-- Contraintes pour la table `ue_module`
--
ALTER TABLE `ue_module`
  ADD CONSTRAINT `ue_module_ibfk_1` FOREIGN KEY (`id_module`) REFERENCES `module` (`id_module`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
