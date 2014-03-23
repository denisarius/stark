-- MySQL dump 10.14  Distrib 5.5.33-MariaDB, for Linux (i686)
--
-- Host: localhost    Database: ptzweb_stark
-- ------------------------------------------------------
-- Server version	5.5.33-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES cp1251 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `menus`
--

DROP TABLE IF EXISTS `menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(254) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=cp1251;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menus`
--

LOCK TABLES `menus` WRITE;
/*!40000 ALTER TABLE `menus` DISABLE KEYS */;
INSERT INTO `menus` VALUES (1,'нЯМНБМНИ ПЮГДЕК'),(2,'пЮЯЬХПЕММНЕ ЛЕМЧ');
/*!40000 ALTER TABLE `menus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menus_items`
--

DROP TABLE IF EXISTS `menus_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menus_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(254) DEFAULT NULL,
  `url` text,
  `parent` int(11) NOT NULL DEFAULT '0',
  `sort` int(11) NOT NULL DEFAULT '0',
  `menu` int(11) NOT NULL DEFAULT '0',
  `visible` int(1) NOT NULL DEFAULT '0',
  `tag` varchar(254) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=cp1251;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menus_items`
--

LOCK TABLES `menus_items` WRITE;
/*!40000 ALTER TABLE `menus_items` DISABLE KEYS */;
INSERT INTO `menus_items` VALUES (1,'н йнлоюмхх','',0,1,1,1,NULL),(2,'бюйюмяхх','',0,2,1,1,NULL),(3,'бнопня-нрбер','/faq.html',0,3,1,1,''),(4,'ярюрэ дхкепнл','',0,4,1,1,NULL),(5,'йнмрюйрш','',0,5,1,1,NULL),(6,'онвелс лш','',1,1,1,1,NULL),(7,'мюью йнлюмдю','/staff.html',1,2,1,1,''),(8,'юйжхх, йсонмш','/actions.html',1,3,1,1,''),(9,'нргшбш йкхемрнб','/feedback.html',1,4,1,1,''),(10,'оюпрмепш','',1,5,1,1,NULL),(11,'оНРНКЙХ','',0,1,2,1,NULL),(12,'кЧЯРПШ','',0,2,2,1,NULL),(13,'фЮКЧГХ','',0,3,2,1,NULL),(14,'нЙМЮ','',0,4,2,1,NULL),(15,'н онрнкйюу','',11,1,2,1,NULL),(16,'йюй ядекюрэ гюйюг','',11,2,2,1,NULL),(17,'цнрнбше пеьемхъ','',11,3,2,1,NULL),(18,'тюйрспш','',11,4,2,1,NULL),(19,'н фюкчгх','',13,1,2,1,NULL),(20,'цнрнбше пеьемхъ','',13,2,2,1,NULL),(21,'яхярелш','',13,3,2,1,NULL),(22,'опехлсыеярбю мюръфмшу онрнкйнб','',15,1,2,1,NULL),(24,'сярюмнбйю онрнкйнб','',15,2,2,1,NULL),(25,'пелнмр','',15,3,2,1,NULL),(26,'врн декюрэ?','',16,1,2,1,NULL),(27,'гюъбйю мю гюлеп','',16,2,2,1,NULL);
/*!40000 ALTER TABLE `menus_items` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-03-23 23:45:55
