-- MySQL dump 10.13  Distrib 8.0.11, for Win64 (x86_64)
--
-- Host: localhost    Database: dailyboost
-- ------------------------------------------------------
-- Server version	8.0.11

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
 SET NAMES utf8mb4 ;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `accounts`
--

DROP TABLE IF EXISTS `accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `businessname` varchar(255) DEFAULT NULL,
  `vatnumber` varchar(255) DEFAULT NULL,
  `doctype` varchar(45) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `zipcode` varchar(15) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `telephone` varchar(255) DEFAULT NULL,
  `enabled` bit(1) DEFAULT NULL,
  `expirydate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `accounts`
--

LOCK TABLES `accounts` WRITE;
/*!40000 ALTER TABLE `accounts` DISABLE KEYS */;
INSERT INTO `accounts` VALUES (1,'mgrisoster@gmail.com','mgrisoster@gmail.com','','','','','','','mgrisoster@gmail.com','','','2020-06-26 07:06:21'),(2,'mgrisovr@gmail.com','mgrisovr@gmail.com','','','','','','','mgrisovr@gmail.com','','','2020-07-02 01:43:47');
/*!40000 ALTER TABLE `accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `active` bit(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Personal','Personal category',''),(2,'Personal','Personal category',''),(3,'Nuova','',''),(4,'Nuova2','',''),(5,'Test','',''),(6,'Nuovissima','',''),(7,'Test3','',''),(8,'Personal','Personal category','');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categoriesaccounts`
--

DROP TABLE IF EXISTS `categoriesaccounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `categoriesaccounts` (
  `idcategory` int(11) NOT NULL,
  `idaccount` int(11) NOT NULL,
  PRIMARY KEY (`idcategory`,`idaccount`),
  KEY `account_FK1_idx` (`idaccount`),
  CONSTRAINT `account_FK1` FOREIGN KEY (`idaccount`) REFERENCES `accounts` (`id`),
  CONSTRAINT `category_FK1` FOREIGN KEY (`idcategory`) REFERENCES `categories` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categoriesaccounts`
--

LOCK TABLES `categoriesaccounts` WRITE;
/*!40000 ALTER TABLE `categoriesaccounts` DISABLE KEYS */;
/*!40000 ALTER TABLE `categoriesaccounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categoriestasks`
--

DROP TABLE IF EXISTS `categoriestasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `categoriestasks` (
  `categoryid` int(11) NOT NULL,
  `taskid` int(11) NOT NULL,
  PRIMARY KEY (`taskid`,`categoryid`),
  KEY `category_FK2_idx` (`categoryid`),
  CONSTRAINT `category_FK2` FOREIGN KEY (`categoryid`) REFERENCES `categories` (`id`),
  CONSTRAINT `task_FK1` FOREIGN KEY (`taskid`) REFERENCES `tasks` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categoriestasks`
--

LOCK TABLES `categoriestasks` WRITE;
/*!40000 ALTER TABLE `categoriestasks` DISABLE KEYS */;
INSERT INTO `categoriestasks` VALUES (2,1),(2,2),(2,5),(2,6),(2,7),(2,8),(2,9),(2,10),(2,11),(2,12),(4,3),(4,4);
/*!40000 ALTER TABLE `categoriestasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categoriesusers`
--

DROP TABLE IF EXISTS `categoriesusers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `categoriesusers` (
  `idcategory` int(11) NOT NULL,
  `iduser` int(11) NOT NULL,
  PRIMARY KEY (`idcategory`,`iduser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categoriesusers`
--

LOCK TABLES `categoriesusers` WRITE;
/*!40000 ALTER TABLE `categoriesusers` DISABLE KEYS */;
INSERT INTO `categoriesusers` VALUES (2,1),(3,1),(4,1),(5,1),(6,1),(7,1),(8,2);
/*!40000 ALTER TABLE `categoriesusers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log`
--

DROP TABLE IF EXISTS `log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `log` (
  `timestamp` timestamp NOT NULL,
  `accountid` int(11) DEFAULT NULL,
  `userid` int(11) DEFAULT NULL,
  `username` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `ip` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `page` varchar(255) COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log`
--

LOCK TABLES `log` WRITE;
/*!40000 ALTER TABLE `log` DISABLE KEYS */;
INSERT INTO `log` VALUES ('2020-05-15 05:12:25',1,2,'username','ip_address','description','page'),('2020-05-15 16:06:56',1,2,'username','ip_address','description','page'),('2020-05-15 16:18:09',1,2,'username','ip_address','description','page'),('2020-05-15 16:28:38',1,2,'username','ip_address','description','page'),('2020-05-15 16:29:01',1,2,'username','ip_address','description','page'),('2020-05-15 16:29:55',1,2,'username','ip_address','description','page'),('2020-05-15 16:29:56',1,2,'username','ip_address','description','page'),('2020-05-15 16:29:57',1,2,'username','ip_address','description','page'),('2020-05-15 16:29:57',1,2,'username','ip_address','description','page'),('2020-05-15 16:29:57',1,2,'username','ip_address','description','page'),('2020-05-15 16:29:57',1,2,'username','ip_address','description','page'),('2020-05-15 16:29:57',1,2,'username','ip_address','description','page'),('2020-05-15 16:29:59',1,2,'username','ip_address','description','page'),('2020-05-15 16:33:49',1,2,'username','ip_address','description','page'),('2020-05-15 16:33:53',1,2,'username','ip_address','description','page'),('2020-05-15 16:34:26',1,2,'username','ip_address','description','page'),('2020-05-15 16:34:28',1,2,'username','ip_address','description','page'),('2020-05-15 16:34:31',1,2,'username','ip_address','description','page'),('2020-05-15 16:34:42',1,2,'username','ip_address','description','page');
/*!40000 ALTER TABLE `log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `taskassignments`
--

DROP TABLE IF EXISTS `taskassignments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `taskassignments` (
  `taskid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `assignedby` int(11) NOT NULL,
  PRIMARY KEY (`taskid`,`userid`),
  KEY `user_fk3_idx` (`userid`),
  KEY `user_fk4_idx` (`assignedby`),
  CONSTRAINT `task_fk2` FOREIGN KEY (`taskid`) REFERENCES `tasks` (`id`),
  CONSTRAINT `user_fk3` FOREIGN KEY (`userid`) REFERENCES `users` (`id`),
  CONSTRAINT `user_fk4` FOREIGN KEY (`assignedby`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `taskassignments`
--

LOCK TABLES `taskassignments` WRITE;
/*!40000 ALTER TABLE `taskassignments` DISABLE KEYS */;
/*!40000 ALTER TABLE `taskassignments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tasks`
--

DROP TABLE IF EXISTS `tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `description` text COLLATE utf8_bin,
  `status` char(1) COLLATE utf8_bin DEFAULT NULL,
  `neverending` bit(1) DEFAULT NULL,
  `plannedcycletime` int(11) DEFAULT NULL,
  `earlystart` datetime DEFAULT NULL,
  `latestart` datetime DEFAULT NULL,
  `earlyfinish` datetime DEFAULT NULL,
  `latefinish` datetime DEFAULT NULL,
  `leadtime` int(11) DEFAULT NULL,
  `workingtime` int(11) DEFAULT NULL,
  `delay` int(11) DEFAULT NULL,
  `realenddate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tasks`
--

LOCK TABLES `tasks` WRITE;
/*!40000 ALTER TABLE `tasks` DISABLE KEYS */;
INSERT INTO `tasks` VALUES (1,'Start','','F','\0',0,'1970-01-01 00:00:00','1970-01-01 00:00:00','1970-01-01 00:00:00','1970-01-01 00:00:00',3,3,0,'2020-06-04 23:02:49'),(2,'asdaas','','F','\0',0,'1970-01-01 00:00:00','1970-01-01 00:00:00','1970-01-01 00:00:00','1970-01-01 00:00:00',2,2,0,'2020-06-04 23:03:25'),(3,'dsfsdfds','','F','\0',0,'1970-01-01 00:00:00','1970-01-01 00:00:00','1970-01-01 00:00:00','1970-01-01 00:00:00',2,2,0,'2020-06-04 23:04:22'),(4,'E adesso','','F','\0',0,'1970-01-01 00:00:00','1970-01-01 00:00:00','1970-01-01 00:00:00','1970-01-01 00:00:00',5,5,0,'2020-06-04 23:07:02'),(5,'Extensive test','','F','\0',0,'1970-01-01 00:00:00','1970-01-01 00:00:00','1970-01-01 00:00:00','1970-01-01 00:00:00',345613,345613,0,'2020-06-08 23:08:39'),(6,'ExtensivePauseEnd','','F','\0',0,'1970-01-01 00:00:00','1970-01-01 00:00:00','1970-01-01 00:00:00','1970-01-01 00:00:00',777662,777619,0,'2020-06-13 23:10:53'),(7,'Check if dc','','F','\0',0,'1970-01-01 00:00:00','1970-01-01 00:00:00','1970-01-01 00:00:00','1970-01-01 00:00:00',604849,604823,0,'2020-06-11 23:13:19'),(8,'adaasdsa','','F','\0',0,'1970-01-01 00:00:00','1970-01-01 00:00:00','1970-01-01 00:00:00','1970-01-01 00:00:00',259208,259208,0,'2020-06-07 23:15:00'),(9,'sdfsdfsd','','F','\0',0,'1970-01-01 00:00:00','1970-01-01 00:00:00','1970-01-01 00:00:00','1970-01-01 00:00:00',259209,259209,0,'2020-06-07 23:16:28'),(10,'jhjgfsdsdgfg','','F','\0',0,'1970-01-01 00:00:00','1970-01-01 00:00:00','1970-01-01 00:00:00','1970-01-01 00:00:00',345613,345613,0,'2020-06-08 23:18:12'),(11,'asfsasdas','','F','\0',0,'1970-01-01 00:00:00','1970-01-01 00:00:00','1970-01-01 00:00:00','1970-01-01 00:00:00',259212,259212,0,'2020-06-11 23:19:47'),(12,'Estensive last test','','F','\0',0,'1970-01-01 00:00:00','1970-01-01 00:00:00','1970-01-01 00:00:00','1970-01-01 00:00:00',518435,518420,0,'2020-06-10 23:22:23');
/*!40000 ALTER TABLE `tasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tasksevents`
--

DROP TABLE IF EXISTS `tasksevents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `tasksevents` (
  `id` bigint(20) NOT NULL,
  `userid` int(11) NOT NULL,
  `taskid` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `timezone` varchar(45) COLLATE utf8_bin NOT NULL,
  `eventtype` char(1) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_fk5_idx` (`userid`),
  KEY `task_event_fk_idx` (`taskid`),
  CONSTRAINT `task_event_fk` FOREIGN KEY (`taskid`) REFERENCES `tasks` (`id`),
  CONSTRAINT `user_fk5` FOREIGN KEY (`userid`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tasksevents`
--

LOCK TABLES `tasksevents` WRITE;
/*!40000 ALTER TABLE `tasksevents` DISABLE KEYS */;
INSERT INTO `tasksevents` VALUES (1,1,1,'2020-06-04 23:02:46','America/Argentina/Buenos_Aires','S'),(2,1,1,'2020-06-04 23:02:49','America/Argentina/Buenos_Aires','F'),(3,1,2,'2020-06-04 23:03:23','America/Argentina/Buenos_Aires','S'),(4,1,2,'2020-06-04 23:03:25','America/Argentina/Buenos_Aires','F'),(5,1,3,'2020-06-04 23:04:20','America/Argentina/Buenos_Aires','S'),(6,1,3,'2020-06-04 23:04:22','America/Argentina/Buenos_Aires','F'),(7,1,4,'2020-06-04 23:06:57','America/Argentina/Buenos_Aires','S'),(8,1,4,'2020-06-04 23:07:02','America/Argentina/Buenos_Aires','F'),(9,1,5,'2020-06-04 23:08:26','America/Argentina/Buenos_Aires','S'),(10,1,5,'2020-06-08 23:08:39','America/Argentina/Buenos_Aires','F'),(11,1,6,'2020-06-04 23:09:51','America/Argentina/Buenos_Aires','S'),(12,1,6,'2020-06-08 23:10:01','America/Argentina/Buenos_Aires','P'),(13,1,6,'2020-06-08 23:10:44','America/Argentina/Buenos_Aires','R'),(14,1,6,'2020-06-13 23:10:53','America/Argentina/Buenos_Aires','F'),(15,1,7,'2020-06-04 23:12:30','America/Argentina/Buenos_Aires','S'),(16,1,7,'2020-06-08 23:12:41','America/Argentina/Buenos_Aires','P'),(17,1,7,'2020-06-08 23:13:07','America/Argentina/Buenos_Aires','R'),(18,1,7,'2020-06-11 23:13:19','America/Argentina/Buenos_Aires','F'),(19,1,8,'2020-06-04 23:14:52','America/Argentina/Buenos_Aires','S'),(20,1,8,'2020-06-07 23:15:00','America/Argentina/Buenos_Aires','F'),(21,1,9,'2020-06-04 23:16:19','America/Argentina/Buenos_Aires','S'),(22,1,9,'2020-06-07 23:16:28','America/Argentina/Buenos_Aires','F'),(23,1,10,'2020-06-04 23:17:59','America/Argentina/Buenos_Aires','S'),(24,1,10,'2020-06-08 23:18:12','America/Argentina/Buenos_Aires','F'),(25,1,11,'2020-06-08 23:19:35','America/Argentina/Buenos_Aires','S'),(26,1,11,'2020-06-11 23:19:47','America/Argentina/Buenos_Aires','F'),(27,1,12,'2020-06-04 23:21:48','America/Argentina/Buenos_Aires','S'),(28,1,12,'2020-06-07 23:21:59','America/Argentina/Buenos_Aires','P'),(29,1,12,'2020-06-07 23:22:14','America/Argentina/Buenos_Aires','R'),(30,1,12,'2020-06-10 23:22:23','America/Argentina/Buenos_Aires','F');
/*!40000 ALTER TABLE `tasksevents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `taskstimespans`
--

DROP TABLE IF EXISTS `taskstimespans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `taskstimespans` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `taskid` int(11) NOT NULL,
  `starteventid` bigint(20) NOT NULL,
  `starteventdate` datetime NOT NULL,
  `starteventtype` char(1) COLLATE utf8_bin NOT NULL,
  `endeventid` bigint(20) NOT NULL,
  `endeventdate` datetime NOT NULL,
  `endeventtype` char(1) COLLATE utf8_bin NOT NULL,
  `timezone` varchar(255) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  KEY `taskstimespans_FK1_idx` (`userid`),
  KEY `taskstimespans_FK2_idx` (`taskid`),
  KEY `taskstimespans_FK3_idx` (`starteventid`),
  KEY `taskstimespans_FK4_idx` (`endeventid`),
  CONSTRAINT `taskstimespans_FK1` FOREIGN KEY (`userid`) REFERENCES `users` (`id`),
  CONSTRAINT `taskstimespans_FK2` FOREIGN KEY (`taskid`) REFERENCES `tasks` (`id`),
  CONSTRAINT `taskstimespans_FK3` FOREIGN KEY (`starteventid`) REFERENCES `tasksevents` (`id`),
  CONSTRAINT `taskstimespans_FK4` FOREIGN KEY (`endeventid`) REFERENCES `tasksevents` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `taskstimespans`
--

LOCK TABLES `taskstimespans` WRITE;
/*!40000 ALTER TABLE `taskstimespans` DISABLE KEYS */;
INSERT INTO `taskstimespans` VALUES (1,1,4,7,'2020-06-04 23:06:57','S',8,'2020-06-04 23:07:02','F','America/Argentina/Buenos_Aires'),(2,1,5,9,'2020-06-04 23:08:26','S',10,'2020-06-05 00:00:00','F','America/Argentina/Buenos_Aires'),(3,1,5,9,'2020-06-05 00:00:00','R',10,'2020-06-06 00:00:00','F','America/Argentina/Buenos_Aires'),(4,1,5,9,'2020-06-06 00:00:00','R',10,'2020-06-07 00:00:00','F','America/Argentina/Buenos_Aires'),(5,1,5,9,'2020-06-07 00:00:00','R',10,'2020-06-08 00:00:00','F','America/Argentina/Buenos_Aires'),(6,1,5,9,'2020-06-08 00:00:00','R',10,'2020-06-08 23:08:39','F','America/Argentina/Buenos_Aires'),(7,1,6,11,'2020-06-04 23:09:51','S',12,'2020-06-05 00:00:00','P','America/Argentina/Buenos_Aires'),(8,1,6,11,'2020-06-05 00:00:00','R',12,'2020-06-06 00:00:00','P','America/Argentina/Buenos_Aires'),(9,1,6,11,'2020-06-06 00:00:00','R',12,'2020-06-07 00:00:00','P','America/Argentina/Buenos_Aires'),(10,1,6,11,'2020-06-07 00:00:00','R',12,'2020-06-08 00:00:00','P','America/Argentina/Buenos_Aires'),(11,1,6,11,'2020-06-08 00:00:00','R',12,'2020-06-08 23:10:01','P','America/Argentina/Buenos_Aires'),(12,1,6,13,'2020-06-08 23:10:44','R',14,'2020-06-09 00:00:00','F','America/Argentina/Buenos_Aires'),(13,1,6,13,'2020-06-09 00:00:00','R',14,'2020-06-10 00:00:00','F','America/Argentina/Buenos_Aires'),(14,1,6,13,'2020-06-10 00:00:00','R',14,'2020-06-11 00:00:00','F','America/Argentina/Buenos_Aires'),(15,1,6,13,'2020-06-11 00:00:00','R',14,'2020-06-12 00:00:00','F','America/Argentina/Buenos_Aires'),(16,1,6,13,'2020-06-12 00:00:00','R',14,'2020-06-13 00:00:00','F','America/Argentina/Buenos_Aires'),(17,1,6,13,'2020-06-13 00:00:00','R',14,'2020-06-13 23:10:53','F','America/Argentina/Buenos_Aires'),(18,1,7,15,'2020-06-04 23:12:30','S',16,'2020-06-05 00:00:00','P','America/Argentina/Buenos_Aires'),(19,1,7,15,'2020-06-05 00:00:00','R',16,'2020-06-06 00:00:00','P','America/Argentina/Buenos_Aires'),(20,1,7,15,'2020-06-06 00:00:00','R',16,'2020-06-07 00:00:00','P','America/Argentina/Buenos_Aires'),(21,1,7,15,'2020-06-07 00:00:00','R',16,'2020-06-08 00:00:00','P','America/Argentina/Buenos_Aires'),(22,1,7,15,'2020-06-08 00:00:00','R',16,'2020-06-08 23:12:41','P','America/Argentina/Buenos_Aires'),(23,1,7,17,'2020-06-08 23:13:07','R',18,'2020-06-09 00:00:00','F','America/Argentina/Buenos_Aires'),(24,1,7,17,'2020-06-09 00:00:00','R',18,'2020-06-10 00:00:00','F','America/Argentina/Buenos_Aires'),(25,1,7,17,'2020-06-10 00:00:00','R',18,'2020-06-11 00:00:00','F','America/Argentina/Buenos_Aires'),(26,1,7,17,'2020-06-11 00:00:00','R',18,'2020-06-11 23:13:19','F','America/Argentina/Buenos_Aires'),(27,1,8,19,'2020-06-04 23:14:52','S',20,'2020-06-05 00:00:00','F','America/Argentina/Buenos_Aires'),(28,1,8,19,'2020-06-05 00:00:00','R',20,'2020-06-06 00:00:00','F','America/Argentina/Buenos_Aires'),(29,1,8,19,'2020-06-06 00:00:00','R',20,'2020-06-07 00:00:00','F','America/Argentina/Buenos_Aires'),(30,1,8,19,'2020-06-07 00:00:00','R',20,'2020-06-07 23:15:00','F','America/Argentina/Buenos_Aires'),(31,1,9,21,'2020-06-04 23:16:19','S',22,'2020-06-05 00:00:00','F','America/Argentina/Buenos_Aires'),(32,1,9,21,'2020-06-05 00:00:00','R',22,'2020-06-06 00:00:00','F','America/Argentina/Buenos_Aires'),(33,1,9,21,'2020-06-06 00:00:00','R',22,'2020-06-07 00:00:00','F','America/Argentina/Buenos_Aires'),(34,1,9,21,'2020-06-07 00:00:00','R',22,'2020-06-07 23:16:28','F','America/Argentina/Buenos_Aires'),(35,1,10,23,'2020-06-04 23:17:59','S',24,'2020-06-05 00:00:00','P','America/Argentina/Buenos_Aires'),(36,1,10,23,'2020-06-05 00:00:00','R',24,'2020-06-06 00:00:00','P','America/Argentina/Buenos_Aires'),(37,1,10,23,'2020-06-06 00:00:00','R',24,'2020-06-07 00:00:00','P','America/Argentina/Buenos_Aires'),(38,1,10,23,'2020-06-07 00:00:00','R',24,'2020-06-08 00:00:00','P','America/Argentina/Buenos_Aires'),(39,1,10,23,'2020-06-08 00:00:00','R',24,'2020-06-08 23:18:12','P','America/Argentina/Buenos_Aires'),(40,1,11,25,'2020-06-08 23:19:35','S',26,'2020-06-09 00:00:00','P','America/Argentina/Buenos_Aires'),(41,1,11,25,'2020-06-09 00:00:00','R',26,'2020-06-10 00:00:00','P','America/Argentina/Buenos_Aires'),(42,1,11,25,'2020-06-10 00:00:00','R',26,'2020-06-11 00:00:00','P','America/Argentina/Buenos_Aires'),(43,1,11,25,'2020-06-11 00:00:00','R',26,'2020-06-11 23:19:47','F','America/Argentina/Buenos_Aires'),(44,1,12,27,'2020-06-04 23:21:48','S',28,'2020-06-05 00:00:00','P','America/Argentina/Buenos_Aires'),(45,1,12,27,'2020-06-05 00:00:00','R',28,'2020-06-06 00:00:00','P','America/Argentina/Buenos_Aires'),(46,1,12,27,'2020-06-06 00:00:00','R',28,'2020-06-07 00:00:00','P','America/Argentina/Buenos_Aires'),(47,1,12,27,'2020-06-07 00:00:00','R',28,'2020-06-07 23:21:59','P','America/Argentina/Buenos_Aires'),(48,1,12,29,'2020-06-07 23:22:14','R',30,'2020-06-08 00:00:00','P','America/Argentina/Buenos_Aires'),(49,1,12,29,'2020-06-08 00:00:00','R',30,'2020-06-09 00:00:00','P','America/Argentina/Buenos_Aires'),(50,1,12,29,'2020-06-09 00:00:00','R',30,'2020-06-10 00:00:00','P','America/Argentina/Buenos_Aires'),(51,1,12,29,'2020-06-10 00:00:00','R',30,'2020-06-10 23:22:23','F','America/Argentina/Buenos_Aires');
/*!40000 ALTER TABLE `taskstimespans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `useraccounts`
--

DROP TABLE IF EXISTS `useraccounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `useraccounts` (
  `accountid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `creationdate` datetime DEFAULT NULL,
  `role` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `checksum` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `enabled` bit(1) DEFAULT NULL,
  PRIMARY KEY (`accountid`,`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `useraccounts`
--

LOCK TABLES `useraccounts` WRITE;
/*!40000 ALTER TABLE `useraccounts` DISABLE KEYS */;
INSERT INTO `useraccounts` VALUES (1,1,'2020-05-26 07:06:21','Account manager','lsolta@.loiioisgs.otgoommlcgm.omgrt1miamisglg',''),(2,2,'2020-06-02 01:43:47','Account manager','aogr2miirgi2oigm.om.clvim.or2rmir2.cogv@o2vs@','');
/*!40000 ALTER TABLE `useraccounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `userconfiguration`
--

DROP TABLE IF EXISTS `userconfiguration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `userconfiguration` (
  `userid` int(11) NOT NULL,
  `section` varchar(45) COLLATE utf8_bin NOT NULL,
  `parameter` varchar(45) COLLATE utf8_bin NOT NULL,
  `value` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`userid`,`section`,`parameter`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `userconfiguration`
--

LOCK TABLES `userconfiguration` WRITE;
/*!40000 ALTER TABLE `userconfiguration` DISABLE KEYS */;
INSERT INTO `userconfiguration` VALUES (1,'Main','Timezone','America/Argentina/Buenos_Aires'),(1,'Main','default_task_id','-1'),(1,'Main','max_tasks_in_execution','3');
/*!40000 ALTER TABLE `userconfiguration` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `GoogleID` varchar(255) DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `language` varchar(45) DEFAULT NULL,
  `region` varchar(45) DEFAULT NULL,
  `creationdate` datetime DEFAULT NULL,
  `enabled` bit(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username_UNIQUE` (`username`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'','mgrisoster@gmail.com','mgrisoster@gmail.com','Matteo','Griso','b940274650682570f56e7a64cc25dff8','en','AR','2020-05-26 07:06:21',''),(2,'','mgrisovr@gmail.com','mgrisovr@gmail.com','Matteo','Griso','70873e8580c9900986939611618d7b1e','en','AR','2020-06-02 01:43:47','');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-06-11 21:53:28
