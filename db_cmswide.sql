-- MySQL dump 10.13  Distrib 5.6.24, for linux-glibc2.5 (x86_64)
--
-- Host: localhost    Database: admwide_wide
-- ------------------------------------------------------
-- Server version	5.5.47-0ubuntu0.14.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `wd_files`
--

DROP TABLE IF EXISTS `wd_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wd_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file` varchar(100) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wd_files`
--

LOCK TABLES `wd_files` WRITE;
/*!40000 ALTER TABLE `wd_files` DISABLE KEYS */;
/*!40000 ALTER TABLE `wd_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wd_nav`
--

DROP TABLE IF EXISTS `wd_nav`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wd_nav` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `slug` varchar(45) NOT NULL,
  `icon` varchar(45) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `order` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wd_nav`
--

LOCK TABLES `wd_nav` WRITE;
/*!40000 ALTER TABLE `wd_nav` DISABLE KEYS */;
INSERT INTO `wd_nav` VALUES (1,'Projetos','projects','fa-paste',1,1),(2,'Minha conta','my-account','fa-user',1,4),(3,'Configurações','config','fa-cog',0,3),(5,'WIDE Store','wide-store','fa-shopping-cart',0,6),(6,'Usuários','users','fa-group',1,5),(7,'Galeria','gallery','fa-picture-o',1,2);
/*!40000 ALTER TABLE `wd_nav` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wd_nav_perm`
--

DROP TABLE IF EXISTS `wd_nav_perm`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wd_nav_perm` (
  `id` int(11) NOT NULL,
  `fk_nav` int(11) NOT NULL,
  `fk_user` int(11) NOT NULL,
  `status` int(11) NOT NULL COMMENT '1 = yes\n0 = no',
  PRIMARY KEY (`id`),
  KEY `fk_wd_nav_perm_1_idx` (`fk_nav`),
  KEY `fk_wd_nav_perm_2_idx` (`fk_user`),
  CONSTRAINT `fk_wd_nav_perm_1` FOREIGN KEY (`fk_nav`) REFERENCES `wd_nav` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_wd_nav_perm_2` FOREIGN KEY (`fk_user`) REFERENCES `wd_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wd_nav_perm`
--

LOCK TABLES `wd_nav_perm` WRITE;
/*!40000 ALTER TABLE `wd_nav_perm` DISABLE KEYS */;
/*!40000 ALTER TABLE `wd_nav_perm` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wd_pages`
--

DROP TABLE IF EXISTS `wd_pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wd_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `directory` varchar(100) NOT NULL,
  `fk_user` int(11) NOT NULL,
  `fk_project` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `order` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `fk_wd_pages_1_idx` (`fk_project`),
  KEY `fk_wd_pages_1_id` (`fk_project`),
  CONSTRAINT `fk_wd_pages_1` FOREIGN KEY (`fk_project`) REFERENCES `wd_projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wd_pages`
--

LOCK TABLES `wd_pages` WRITE;
/*!40000 ALTER TABLE `wd_pages` DISABLE KEYS */;
/*!40000 ALTER TABLE `wd_pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wd_projects`
--

DROP TABLE IF EXISTS `wd_projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wd_projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `directory` varchar(45) NOT NULL,
  `fk_user` int(11) NOT NULL,
  `slug` varchar(45) NOT NULL,
  `main` int(11) DEFAULT '0' COMMENT '1 = yes\n0 = no',
  `suffix` varchar(10) DEFAULT NULL,
  `status` int(11) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wd_projects`
--

LOCK TABLES `wd_projects` WRITE;
/*!40000 ALTER TABLE `wd_projects` DISABLE KEYS */;
INSERT INTO `wd_projects` VALUES (11,'Site principal','site',1,'site',1,'',1);
/*!40000 ALTER TABLE `wd_projects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wd_sections`
--

DROP TABLE IF EXISTS `wd_sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wd_sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_page` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `directory` varchar(100) NOT NULL,
  `table` varchar(45) NOT NULL,
  `order` int(11) DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '1',
  `slug` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `fk_wd_sections_1_idx` (`fk_page`),
  CONSTRAINT `fk_wd_sections_1` FOREIGN KEY (`fk_page`) REFERENCES `wd_pages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wd_sections`
--

LOCK TABLES `wd_sections` WRITE;
/*!40000 ALTER TABLE `wd_sections` DISABLE KEYS */;
/*!40000 ALTER TABLE `wd_sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wd_users`
--

DROP TABLE IF EXISTS `wd_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wd_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(45) CHARACTER SET latin1 NOT NULL,
  `email` varchar(100) CHARACTER SET latin1 NOT NULL,
  `password` varchar(100) CHARACTER SET latin1 NOT NULL,
  `status` tinyint(1) NOT NULL COMMENT '0 = disabled\n1 = enabled\n2 = root',
  `name` varchar(45) CHARACTER SET latin1 NOT NULL,
  `last_name` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `image` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `dev_mode` int(11) NOT NULL DEFAULT '0' COMMENT '1 = true\n0 = false',
  `allow_dev` int(11) NOT NULL DEFAULT '0',
  `root` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wd_users`
--

LOCK TABLES `wd_users` WRITE;
/*!40000 ALTER TABLE `wd_users` DISABLE KEYS */;
INSERT INTO `wd_users` VALUES (1,'root','contato@widedevelop.com','$2a$08$GoAtuNEYzLOBvyaYiWzfRu9cLQ14yXVq1w3xG1gPJ/F0u3W9NsrkS',1,'Root','','2013-01-16 02:00:00','',1,1,1);
/*!40000 ALTER TABLE `wd_users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-03-30 15:44:27
