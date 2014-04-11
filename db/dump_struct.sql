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
  `menu_item` int(11) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL DEFAULT '0',
  `text` text,
  `url` text,
  `link` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;
/*!40101 SET character_set_client = @saved_cs_client */;

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
-- Table structure for table `objects_details`
--

DROP TABLE IF EXISTS `objects_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `objects_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `node` int(11) NOT NULL DEFAULT '0',
  `typeId` varchar(50) DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL,
  `value` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `shop_orders`
--

DROP TABLE IF EXISTS `shop_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `public_id` char(10) DEFAULT NULL,
  `date` date NOT NULL,
  `user` int(11) NOT NULL DEFAULT '0',
  `address` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `shop_orders_goods`
--

DROP TABLE IF EXISTS `shop_orders_goods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_orders_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order` int(11) NOT NULL DEFAULT '0',
  `good` int(11) NOT NULL DEFAULT '0',
  `qty` int(11) NOT NULL DEFAULT '0',
  `price` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `shop_tree_node_details`
--

DROP TABLE IF EXISTS `shop_tree_node_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_tree_node_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `node` int(11) NOT NULL DEFAULT '0',
  `typeId` varchar(50) DEFAULT NULL,
  `type` varchar(5) DEFAULT NULL,
  `value` text,
  PRIMARY KEY (`id`),
  KEY `tv` (`typeId`,`value`(950))
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `shop_tree_nodes`
--

DROP TABLE IF EXISTS `shop_tree_nodes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_tree_nodes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu` int(11) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL DEFAULT '0',
  `parent` int(11) NOT NULL DEFAULT '0',
  `code` varchar(10) DEFAULT NULL,
  `name` varchar(254) DEFAULT NULL,
  `note` text,
  `sort` int(11) NOT NULL DEFAULT '0',
  `date` date NOT NULL,
  `visible` int(1) NOT NULL DEFAULT '0',
  `cnt_view` int(11) NOT NULL DEFAULT '0',
  `cnt_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=cp1251;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-04-11 20:58:37
