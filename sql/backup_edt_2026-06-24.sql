-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: db_universite
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `edt`
--

DROP TABLE IF EXISTS `edt`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edt` (
  `id_edt` int(11) NOT NULL AUTO_INCREMENT,
  `date_creation` date NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `heure_total` int(11) NOT NULL,
  `statut` tinyint(4) NOT NULL,
  `id_module` int(11) NOT NULL,
  `id_salle` int(11) DEFAULT NULL,
  `id_filiere` int(11) NOT NULL,
  `id_promotion` int(11) NOT NULL,
  `id_semestre` int(11) NOT NULL,
  `id_periode` int(11) NOT NULL,
  PRIMARY KEY (`id_edt`),
  KEY `edt_ibfk_1` (`id_module`),
  KEY `edt_ibfk_3` (`id_salle`),
  KEY `edt_ibfk_4` (`id_filiere`),
  KEY `edt_ibfk_5` (`id_promotion`),
  KEY `id_semestre` (`id_semestre`),
  KEY `id_periode` (`id_periode`),
  CONSTRAINT `edt_ibfk_1` FOREIGN KEY (`id_module`) REFERENCES `ue_module` (`id_ue_module`),
  CONSTRAINT `edt_ibfk_3` FOREIGN KEY (`id_salle`) REFERENCES `salle` (`id_salle`),
  CONSTRAINT `edt_ibfk_5` FOREIGN KEY (`id_promotion`) REFERENCES `promotion` (`id_promotion`),
  CONSTRAINT `edt_ibfk_6` FOREIGN KEY (`id_semestre`) REFERENCES `parcours` (`id_parcours`),
  CONSTRAINT `edt_ibfk_7` FOREIGN KEY (`id_periode`) REFERENCES `periode` (`id_periode`),
  CONSTRAINT `edt_ibfk_8` FOREIGN KEY (`id_filiere`) REFERENCES `filiere` (`id_filiere`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `edt`
--

LOCK TABLES `edt` WRITE;
/*!40000 ALTER TABLE `edt` DISABLE KEYS */;
INSERT INTO `edt` VALUES (1,'2026-05-27','2026-05-25','2026-06-01',75,1,244,NULL,1,83,19,5);
/*!40000 ALTER TABLE `edt` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `enseignant_edt`
--

DROP TABLE IF EXISTS `enseignant_edt`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `enseignant_edt` (
  `id_enseignant_edt` int(11) NOT NULL AUTO_INCREMENT,
  `id_edt` int(11) NOT NULL,
  `id_enseignant` int(11) NOT NULL,
  `groupe` varchar(250) NOT NULL,
  `type_cours` varchar(100) NOT NULL,
  `nombre_heure` int(100) NOT NULL,
  `id_salle` int(11) NOT NULL,
  PRIMARY KEY (`id_enseignant_edt`),
  KEY `id_enseigant` (`id_enseignant`),
  KEY `enseignant_edt_ibfk_1` (`id_edt`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `enseignant_edt`
--

LOCK TABLES `enseignant_edt` WRITE;
/*!40000 ALTER TABLE `enseignant_edt` DISABLE KEYS */;
INSERT INTO `enseignant_edt` VALUES (4,6,2,'GP','cm-td-tp',50,1),(5,1,18,'Gr I','cm-td-tp',47,3),(9,5,12,'Gr I','cm-td-tp',30,1),(10,5,5,'Gr II','cm-td-tp',30,2),(11,6,106,'GP','cm-td-tp',30,1),(12,1,123,'GP','cm-td-tp',30,1),(13,1,110,'GP','cm-td-tp',30,1);
/*!40000 ALTER TABLE `enseignant_edt` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `horaire`
--

DROP TABLE IF EXISTS `horaire`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `horaire` (
  `id_horaire` int(11) NOT NULL AUTO_INCREMENT,
  `heure_debut` time NOT NULL,
  `heure_fin` time NOT NULL,
  `id_edt` int(11) NOT NULL,
  PRIMARY KEY (`id_horaire`),
  KEY `id_edt` (`id_edt`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `horaire`
--

LOCK TABLES `horaire` WRITE;
/*!40000 ALTER TABLE `horaire` DISABLE KEYS */;
INSERT INTO `horaire` VALUES (1,'08:00:00','10:00:00',1),(2,'10:15:00','13:15:00',1),(3,'14:00:00','16:00:00',1),(4,'16:00:00','19:00:00',1);
/*!40000 ALTER TABLE `horaire` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tache`
--

DROP TABLE IF EXISTS `tache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tache` (
  `id_tache` int(11) NOT NULL AUTO_INCREMENT,
  `type_tache` varchar(50) NOT NULL,
  `id_horaire` int(11) NOT NULL,
  `id_jour` int(11) NOT NULL,
  PRIMARY KEY (`id_tache`),
  KEY `tache_ibfk_1` (`id_jour`),
  KEY `tache_ibfk_2` (`id_horaire`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tache`
--

LOCK TABLES `tache` WRITE;
/*!40000 ALTER TABLE `tache` DISABLE KEYS */;
INSERT INTO `tache` VALUES (1,'cm',1,1),(2,'td',1,2),(3,'tp',1,3),(4,'x',1,4),(5,'x',1,5),(6,'examen',1,6),(7,'cm',2,1),(8,'td',2,2),(9,'tp',2,3),(10,'x',2,4),(11,'x',2,5),(12,'x',2,6),(13,'cm',3,1),(14,'td',3,2),(15,'tp',3,3),(16,'x',3,4),(17,'x',3,5),(18,'x',3,6),(19,'cm',4,1),(20,'td',4,2),(21,'tp',4,3),(22,'x',4,4),(23,'x',4,5),(24,'x',4,6),(25,'CM',5,1);
/*!40000 ALTER TABLE `tache` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-06-24  2:51:59
