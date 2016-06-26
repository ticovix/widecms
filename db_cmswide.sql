CREATE DATABASE  IF NOT EXISTS `cmswide` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `cmswide`;
-- MySQL dump 10.13  Distrib 5.5.49, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: cmswide
-- ------------------------------------------------------
-- Server version	5.5.49-0ubuntu0.14.04.1

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
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wd_files`
--

LOCK TABLES `wd_files` WRITE;
/*!40000 ALTER TABLE `wd_files` DISABLE KEYS */;
INSERT INTO `wd_files` VALUES (5,'-love-linux.png','Perfil',NULL);
/*!40000 ALTER TABLE `wd_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wd_history`
--

DROP TABLE IF EXISTS `wd_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wd_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message` varchar(255) NOT NULL,
  `app` varchar(45) DEFAULT NULL,
  `fk_user` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_wd_history_1_idx` (`fk_user`),
  CONSTRAINT `fk_wd_history_1` FOREIGN KEY (`fk_user`) REFERENCES `wd_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wd_history`
--

LOCK TABLES `wd_history` WRITE;
/*!40000 ALTER TABLE `wd_history` DISABLE KEYS */;
INSERT INTO `wd_history` VALUES (2,'Atualizou o perfil.','my_account',1,'2016-05-16 22:57:47'),(3,'Inseriu um novo arquivo aptura_de_tela_de_2016-05-15_213609.png','gallery',1,'2016-05-16 23:01:23'),(4,'Removeu o arquivo aptura_de_tela_de_2016-05-15_213609.png','gallery',1,'2016-05-16 23:02:08'),(5,'Alterou o título do arquivo para Perfil','gallery',1,'2016-05-16 23:05:11'),(6,'Criou o usuário Patrick Oliveira','users',1,'2016-05-22 14:31:08'),(9,'Editou o usuário Patrick','users',1,'2016-05-23 13:37:34'),(10,'Editou o usuário Patrick','users',1,'2016-05-23 13:38:34'),(11,'Editou o usuário Patrick','users',1,'2016-05-23 13:39:28'),(12,'Editou o usuário Patrick','users',1,'2016-05-23 13:40:49'),(13,'Editou o usuário Rooot','users',1,'2016-05-23 13:42:13'),(14,'Editou o usuário Root','users',1,'2016-05-23 13:42:19'),(15,'Editou o usuário Patrick','users',1,'2016-05-23 14:54:25'),(16,'Editou o usuário Patrick','users',1,'2016-05-23 14:58:07'),(17,'Editou o usuário Patrick','users',1,'2016-05-23 15:29:47'),(18,'Editou o usuário Patrick','users',1,'2016-05-23 16:12:10'),(19,'Atualizou o perfil','my_account',1,'2016-05-23 22:28:06'),(20,'Inseriu um novo arquivo \"aptura_de_tela_de_2016-05-08_185039.png\"','gallery',1,'2016-05-24 19:15:36'),(21,'Removeu o arquivo \"aptura_de_tela_de_2016-05-08_185039.png\"','gallery',1,'2016-05-24 19:17:32');
/*!40000 ALTER TABLE `wd_history` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
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
  `preffix` varchar(10) DEFAULT NULL,
  `status` int(11) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wd_projects`
--

LOCK TABLES `wd_projects` WRITE;
/*!40000 ALTER TABLE `wd_projects` DISABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;
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
  `about` varchar(255) DEFAULT NULL,
  `recovery_token` varchar(255) DEFAULT NULL,
  `limit_recovery_token` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wd_users`
--

LOCK TABLES `wd_users` WRITE;
/*!40000 ALTER TABLE `wd_users` DISABLE KEYS */;
INSERT INTO `wd_users` VALUES (1,'root','contato@widedevelop.com','$2a$08$sjADIB0xPHbiEQuDYqDUN.RuI9g1JBZl.UpCZoW.g5ED4APUxUpRO',1,'Root','','2013-01-16 02:00:00','-love-linux.png',1,1,1,'tester','','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `wd_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wd_users_perm`
--

DROP TABLE IF EXISTS `wd_users_perm`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wd_users_perm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app` varchar(200) NOT NULL,
  `fk_user` int(11) NOT NULL,
  `page` varchar(100) DEFAULT NULL,
  `method` varchar(100) DEFAULT NULL,
  `status` int(11) NOT NULL COMMENT '1 = yes\n0 = no',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `fk_wd_nav_perm_1_idx` (`app`)
) ENGINE=InnoDB AUTO_INCREMENT=197 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wd_users_perm`
--

LOCK TABLES `wd_users_perm` WRITE;
/*!40000 ALTER TABLE `wd_users_perm` DISABLE KEYS */;
INSERT INTO `wd_users_perm` VALUES (107,'gallery',1,'','',0),(108,'gallery',1,'','upload',0),(109,'gallery',1,'','view_files',0),(110,'users',1,'','',0),(111,'users',1,'edit/.*','edit',0),(112,'users',1,'0','edit-permission',0),(113,'users',1,'create','create',0),(114,'users',1,'delete','delete',0),(115,'projects',1,'','',0),(116,'projects',1,'site/teste/teste','site-teste-teste',0),(117,'projects',1,'site/teste/teste/edit','site-teste-teste-edit',0),(118,'projects',1,'site/teste/tester','site-teste-tester',0),(119,'projects',1,'site/teste/tester/edit','site-teste-tester-edit',0),(120,'projects',1,'site/teste/tester/create','site-teste-tester-create',0),(121,'projects',1,'site/teste/tester/tester/remove','site-teste-tester-remove',0),(122,'my_account',1,'','',0),(123,'my_account',1,'','edit',0),(179,'gallery',4,'','',1),(180,'gallery',4,'','upload',0),(181,'gallery',4,'','view-files',1),(182,'gallery',4,'','remove',1),(183,'gallery',4,'','edit',0),(184,'users',4,'','',1),(185,'users',4,'edit/.*','edit',0),(186,'users',4,'0','edit-permission',0),(187,'users',4,'create','create',1),(188,'users',4,'delete','delete',1),(189,'projects',4,'','',1),(190,'projects',4,'project/site/teste/teste','site-teste-teste',0),(191,'projects',4,'project/site/teste/tester','site-teste-tester',1),(192,'projects',4,'project/site/teste/tester/create','site-teste-tester-create',1),(193,'projects',4,'project/site/teste/tester/edit/.*','site-teste-tester-edit',1),(194,'projects',4,'project/site/teste/tester/tester/remove/.*','site-teste-tester-remove',1),(195,'my_account',4,'','',1),(196,'my_account',4,'','edit',1);
/*!40000 ALTER TABLE `wd_users_perm` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-06-26 19:30:02
