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
-- Table structure for table `_files_data`
--

DROP TABLE IF EXISTS `_files_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `_files_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `path` text,
  `size` int(11) NOT NULL DEFAULT '0',
  `timestamp` int(11) NOT NULL DEFAULT '0',
  `md5` varchar(32) DEFAULT NULL,
  `content` mediumblob,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `_files_data`
--

LOCK TABLES `_files_data` WRITE;
/*!40000 ALTER TABLE `_files_data` DISABLE KEYS */;
/*!40000 ALTER TABLE `_files_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `_temp_files`
--

DROP TABLE IF EXISTS `_temp_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `_temp_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file` varchar(254) DEFAULT NULL,
  `created` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `_temp_files`
--

LOCK TABLES `_temp_files` WRITE;
/*!40000 ALTER TABLE `_temp_files` DISABLE KEYS */;
/*!40000 ALTER TABLE `_temp_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `_vfs`
--

DROP TABLE IF EXISTS `_vfs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `_vfs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `real_name` varchar(64) DEFAULT NULL,
  `real_path` text,
  `virtual_name` varchar(64) DEFAULT NULL,
  `virtual_path` text,
  PRIMARY KEY (`id`),
  KEY `virtual_name` (`virtual_name`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `_vfs`
--

LOCK TABLES `_vfs` WRITE;
/*!40000 ALTER TABLE `_vfs` DISABLE KEYS */;
/*!40000 ALTER TABLE `_vfs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `banners`
--

DROP TABLE IF EXISTS `banners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `banners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language` varchar(5) DEFAULT NULL,
  `file` varchar(21) DEFAULT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  `visible` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banners`
--

LOCK TABLES `banners` WRITE;
/*!40000 ALTER TABLE `banners` DISABLE KEYS */;
/*!40000 ALTER TABLE `banners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `constants`
--

DROP TABLE IF EXISTS `constants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `constants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(254) DEFAULT NULL,
  `value` text,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `constants`
--

LOCK TABLES `constants` WRITE;
/*!40000 ALTER TABLE `constants` DISABLE KEYS */;
/*!40000 ALTER TABLE `constants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `directories`
--

DROP TABLE IF EXISTS `directories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `directories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(254) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `directories`
--

LOCK TABLES `directories` WRITE;
/*!40000 ALTER TABLE `directories` DISABLE KEYS */;
/*!40000 ALTER TABLE `directories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `directories_data`
--

DROP TABLE IF EXISTS `directories_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `directories_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dir` int(11) NOT NULL DEFAULT '0',
  `content` varchar(254) DEFAULT NULL,
  `linked` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `dc` (`dir`,`content`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `directories_data`
--

LOCK TABLES `directories_data` WRITE;
/*!40000 ALTER TABLE `directories_data` DISABLE KEYS */;
/*!40000 ALTER TABLE `directories_data` ENABLE KEYS */;
UNLOCK TABLES;

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
INSERT INTO `menus` VALUES (1,'ŒÒÌÓ‚ÌÓÈ ‡Á‰ÂÎ'),(2,'–‡Ò¯ËÂÌÌÓÂ ÏÂÌ˛');
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
INSERT INTO `menus_items` VALUES (1,'Œ  ŒÃœ¿Õ»»','',0,1,1,1,NULL),(2,'¬¿ ¿Õ—»»','',0,2,1,1,NULL),(3,'¬Œœ–Œ—-Œ“¬≈“','/faq.html',0,3,1,1,''),(4,'—“¿“‹ ƒ»À≈–ŒÃ','',0,4,1,1,NULL),(5,' ŒÕ“¿ “€','',0,5,1,1,NULL),(6,'œŒ◊≈Ã” Ã€','',1,1,1,1,NULL),(7,'Õ¿ÿ¿  ŒÃ¿Õƒ¿','/staff.html',1,2,1,1,''),(8,'¿ ÷»»,  ”œŒÕ€','/actions.html',1,3,1,1,''),(9,'Œ“«€¬€  À»≈Õ“Œ¬','/feedback.html',1,4,1,1,''),(10,'œ¿–“Õ≈–€','',1,5,1,1,NULL),(11,'œÓÚÓÎÍË','',0,1,2,1,NULL),(12,'À˛ÒÚ˚','',0,2,2,1,NULL),(13,'∆‡Î˛ÁË','',0,3,2,1,NULL),(14,'ŒÍÌ‡','',0,4,2,1,NULL),(15,'Œ œŒ“ŒÀ ¿’','',11,1,2,1,NULL),(16,' ¿  —ƒ≈À¿“‹ «¿ ¿«','',11,2,2,1,NULL),(17,'√Œ“Œ¬€≈ –≈ÿ≈Õ»ﬂ','',11,3,2,1,NULL),(18,'‘¿ “”–€','',11,4,2,1,NULL),(19,'Œ ∆¿Àﬁ«»','',13,1,2,1,NULL),(20,'√Œ“Œ¬€≈ –≈ÿ≈Õ»ﬂ','',13,2,2,1,NULL),(21,'—»—“≈Ã€','',13,3,2,1,NULL),(22,'œ–≈»Ã”Ÿ≈—“¬¿ Õ¿“ﬂ∆Õ€’ œŒ“ŒÀ Œ¬','',15,1,2,1,NULL),(24,'”—“¿ÕŒ¬ ¿ œŒ“ŒÀ Œ¬','',15,2,2,1,NULL),(25,'–≈ÃŒÕ“','',15,3,2,1,NULL),(26,'◊“Œ ƒ≈À¿“‹?','',16,1,2,1,NULL),(27,'«¿ﬂ¬ ¿ Õ¿ «¿Ã≈–','',16,2,2,1,NULL);
/*!40000 ALTER TABLE `menus_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `objects`
--

DROP TABLE IF EXISTS `objects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `objects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_item` int(11) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL DEFAULT '0',
  `name` varchar(254) DEFAULT NULL,
  `note` text,
  `image` varchar(254) DEFAULT NULL,
  `gallery` int(11) NOT NULL DEFAULT '0',
  `sort` int(11) NOT NULL DEFAULT '0',
  `date` date DEFAULT NULL,
  `visible` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  FULLTEXT KEY `ft_name` (`name`,`note`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `objects`
--

LOCK TABLES `objects` WRITE;
/*!40000 ALTER TABLE `objects` DISABLE KEYS */;
/*!40000 ALTER TABLE `objects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `objects_details`
--

DROP TABLE IF EXISTS `objects_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `objects_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `node` int(11) NOT NULL DEFAULT '0',
  `typeId` varchar(50) DEFAULT NULL,
  `type` varchar(5) DEFAULT NULL,
  `value` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `objects_details`
--

LOCK TABLES `objects_details` WRITE;
/*!40000 ALTER TABLE `objects_details` DISABLE KEYS */;
/*!40000 ALTER TABLE `objects_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `text_parts`
--

DROP TABLE IF EXISTS `text_parts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `text_parts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(11) NOT NULL DEFAULT '0',
  `node` int(11) NOT NULL DEFAULT '0',
  `date` date NOT NULL,
  `title` varchar(254) DEFAULT NULL,
  `image` varchar(50) DEFAULT NULL,
  `content` longtext,
  `sort` int(11) NOT NULL DEFAULT '0',
  `visible` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `tnvs` (`type`,`node`,`visible`,`sort`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `text_parts`
--

LOCK TABLES `text_parts` WRITE;
/*!40000 ALTER TABLE `text_parts` DISABLE KEYS */;
/*!40000 ALTER TABLE `text_parts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `texts`
--

DROP TABLE IF EXISTS `texts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `texts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `signature` varchar(10) DEFAULT NULL,
  `menu_item` int(11) NOT NULL DEFAULT '0',
  `date` date NOT NULL,
  `title` varchar(254) DEFAULT NULL,
  `keywords` text,
  `descr` text,
  `content` longtext,
  `visible` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=cp1251;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `texts`
--

LOCK TABLES `texts` WRITE;
/*!40000 ALTER TABLE `texts` DISABLE KEYS */;
INSERT INTO `texts` VALUES (1,'OKMPN0',1,'2014-03-23','Œ ÍÓÏÔ‡ÌËË','','','<p>\n	Ã˚ Ó˜ÂÌ¸ ıÓÓ¯‡ˇ ÍÓÏÔ‡ÌËˇ. —ÏÓÚËÚÂ Ò‡ÏË!</p>\n',1);
/*!40000 ALTER TABLE `texts` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-03-24 21:47:44
