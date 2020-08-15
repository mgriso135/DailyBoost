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
INSERT INTO `categories` VALUES (1,'Personal','Personal category',''),(2,'Personal','Personal category',''),(3,'Nuova','',''),(4,'Nuova2','',''),(5,'Test','',''),(6,'Nuovissima','',''),(7,'Test3','',''),(8,'Personal','Personal category',''),(9,'Test new cat','','');
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
INSERT INTO `categoriestasks` VALUES (2,1),(2,2),(2,5),(2,6),(2,7),(2,8),(2,9),(2,10),(2,11),(2,12),(2,14),(2,15),(2,16),(2,17),(2,18),(2,19),(2,20),(2,42),(2,45),(2,46),(2,47),(2,49),(2,51),(2,52),(2,60),(2,61),(2,62),(2,63),(3,13),(3,43),(3,53),(4,3),(4,4),(4,21),(4,22),(4,23),(4,24),(4,25),(4,26),(4,27),(4,28),(4,29),(4,30),(4,31),(4,32),(4,33),(4,34),(4,35),(4,36),(4,37),(4,38),(4,39),(4,40),(4,41),(4,55),(4,64),(4,65),(5,50),(5,57),(6,44),(6,48),(6,54),(6,56),(6,58),(7,59);
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
  PRIMARY KEY (`idcategory`,`iduser`),
  KEY `user_fk1_idx` (`iduser`),
  CONSTRAINT `category1_fk1` FOREIGN KEY (`idcategory`) REFERENCES `categories` (`id`),
  CONSTRAINT `catusr_user_fk1` FOREIGN KEY (`iduser`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categoriesusers`
--

LOCK TABLES `categoriesusers` WRITE;
/*!40000 ALTER TABLE `categoriesusers` DISABLE KEYS */;
INSERT INTO `categoriesusers` VALUES (2,1),(3,1),(4,1),(5,1),(6,1),(7,1),(9,1),(8,2);
/*!40000 ALTER TABLE `categoriesusers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `externalcalendarscategories`
--

DROP TABLE IF EXISTS `externalcalendarscategories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `externalcalendarscategories` (
  `id` int(11) NOT NULL,
  `categoryid` int(11) NOT NULL,
  `calendartype` varchar(255) COLLATE utf8_bin NOT NULL,
  `externalaccountid` int(11) NOT NULL,
  `calendarid` varchar(255) COLLATE utf8_bin NOT NULL,
  `calendarname` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`,`categoryid`,`calendartype`,`externalaccountid`,`calendarid`),
  KEY `extcategory_fk1_idx` (`categoryid`),
  KEY `extappid_idx` (`externalaccountid`),
  CONSTRAINT `extappid` FOREIGN KEY (`externalaccountid`) REFERENCES `usersexternalaccounts` (`id`),
  CONSTRAINT `extcategory_fk1` FOREIGN KEY (`categoryid`) REFERENCES `categories` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `externalcalendarscategories`
--

LOCK TABLES `externalcalendarscategories` WRITE;
/*!40000 ALTER TABLE `externalcalendarscategories` DISABLE KEYS */;
INSERT INTO `externalcalendarscategories` VALUES (1,2,'Google Calendar',1,'mgrisoster@gmail.com','mgrisoster@gmail.com'),(2,2,'Google Calendar',2,'matteo.griso@virtualchief.net','matteo.griso@virtualchief.net'),(3,3,'Google Calendar',2,'matteo.griso@virtualchief.net','matteo.griso@virtualchief.net');
/*!40000 ALTER TABLE `externalcalendarscategories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `externalcalendartask`
--

DROP TABLE IF EXISTS `externalcalendartask`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `externalcalendartask` (
  `id` int(11) NOT NULL,
  `categoryid` int(11) NOT NULL,
  `externalcalendarid` int(11) NOT NULL,
  `internaltaskid` int(11) NOT NULL,
  `externaltaskid` varchar(255) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  KEY `extcal_task_fk1_idx` (`internaltaskid`),
  KEY `extcal_calext_fk1_idx` (`externalcalendarid`),
  KEY `extcal_task_fk2_idx` (`categoryid`),
  CONSTRAINT `extcal_calext_fk1` FOREIGN KEY (`externalcalendarid`) REFERENCES `externalcalendarscategories` (`id`),
  CONSTRAINT `extcal_categoriesusers_fk1` FOREIGN KEY (`categoryid`) REFERENCES `categoriesusers` (`idcategory`),
  CONSTRAINT `extcal_task_fk1` FOREIGN KEY (`internaltaskid`) REFERENCES `tasks` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `externalcalendartask`
--

LOCK TABLES `externalcalendartask` WRITE;
/*!40000 ALTER TABLE `externalcalendartask` DISABLE KEYS */;
INSERT INTO `externalcalendartask` VALUES (1,3,3,43,'vkcgfbuj5sc4amuq77t75d9fqs');
/*!40000 ALTER TABLE `externalcalendartask` ENABLE KEYS */;
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
INSERT INTO `tasks` VALUES (1,'Start','','F','\0',0,'1970-01-01 00:00:00','1970-01-01 00:00:00','1970-01-01 00:00:00','1970-01-01 00:00:00',3,3,0,'2020-06-04 23:02:49'),(2,'asdaas','','F','\0',0,'1970-01-01 00:00:00','1970-01-01 00:00:00','1970-01-01 00:00:00','1970-01-01 00:00:00',2,2,0,'2020-06-04 23:03:25'),(3,'dsfsdfds','','F','\0',0,'1970-01-01 00:00:00','1970-01-01 00:00:00','1970-01-01 00:00:00','1970-01-01 00:00:00',2,2,0,'2020-06-04 23:04:22'),(4,'E adesso','','F','\0',0,'1970-01-01 00:00:00','1970-01-01 00:00:00','1970-01-01 00:00:00','1970-01-01 00:00:00',5,5,0,'2020-06-04 23:07:02'),(5,'Extensive test','','F','\0',0,'1970-01-01 00:00:00','1970-01-01 00:00:00','1970-01-01 00:00:00','1970-01-01 00:00:00',345613,345613,0,'2020-06-08 23:08:39'),(6,'ExtensivePauseEnd','','F','\0',0,'1970-01-01 00:00:00','1970-01-01 00:00:00','1970-01-01 00:00:00','1970-01-01 00:00:00',777662,777619,0,'2020-06-13 23:10:53'),(7,'Check if dc','','F','\0',0,'1970-01-01 00:00:00','1970-01-01 00:00:00','1970-01-01 00:00:00','1970-01-01 00:00:00',604849,604823,0,'2020-06-11 23:13:19'),(8,'adaasdsa','','F','\0',0,'1970-01-01 00:00:00','1970-01-01 00:00:00','1970-01-01 00:00:00','1970-01-01 00:00:00',259208,259208,0,'2020-06-07 23:15:00'),(9,'sdfsdfsd','','F','\0',0,'1970-01-01 00:00:00','1970-01-01 00:00:00','1970-01-01 00:00:00','1970-01-01 00:00:00',259209,259209,0,'2020-06-07 23:16:28'),(10,'jhjgfsdsdgfg','','F','\0',0,'1970-01-01 00:00:00','1970-01-01 00:00:00','1970-01-01 00:00:00','1970-01-01 00:00:00',345613,345613,0,'2020-06-08 23:18:12'),(11,'asfsasdas','','F','\0',0,'1970-01-01 00:00:00','1970-01-01 00:00:00','1970-01-01 00:00:00','1970-01-01 00:00:00',259212,259212,0,'2020-06-11 23:19:47'),(12,'Estensive last test','','F','\0',0,'1970-01-01 00:00:00','1970-01-01 00:00:00','1970-01-01 00:00:00','1970-01-01 00:00:00',518435,518420,0,'2020-06-10 23:22:23'),(13,'null exq','Test','F','\0',0,'2020-07-08 23:00:00','2020-07-08 23:00:00','2020-07-09 00:00:00','2020-07-09 00:00:00',12612,12612,0,'2020-07-01 01:55:12'),(14,'ex null','Test','N','',0,'2020-08-16 16:55:23','2020-08-16 16:55:23','2020-08-16 19:57:25','2020-08-16 19:57:25',0,0,0,NULL),(15,'Test sync','','N','',0,'2020-08-15 18:55:23','2020-08-15 18:55:23','2020-08-15 22:00:00','2020-08-15 22:00:00',0,0,0,NULL),(16,'Test1','','F','\0',0,'2020-06-19 00:00:00','2020-06-19 00:00:00','2020-06-23 18:00:00','2020-06-23 18:00:00',1,1,0,'2020-06-15 18:21:40'),(17,'Test2','','N','\0',0,'2020-06-19 00:00:00','2020-06-19 00:00:00','2020-06-23 18:00:00','2020-06-23 18:00:00',0,0,0,NULL),(18,'Test21','aaa','N','\0',0,'2020-06-19 00:00:00','2020-06-19 00:00:00','2020-06-23 18:00:00','2020-06-23 18:00:00',0,0,0,NULL),(19,'Test2','','N','\0',0,'2020-06-19 00:00:00','2020-06-19 00:00:00','2020-06-23 18:00:00','2020-06-23 18:00:00',0,0,0,NULL),(20,'Test2','','N','\0',0,'2020-06-19 00:00:00','2020-06-19 00:00:00','2020-06-23 18:00:00','2020-06-23 18:00:00',0,0,0,NULL),(21,'s','','F','\0',0,'2020-06-12 17:03:58','2020-06-12 17:03:58','2020-06-13 17:03:58','2020-06-13 17:03:58',1,1,0,'2020-06-15 18:21:15'),(22,'s','','F','\0',0,'2020-06-12 17:03:58','2020-06-12 17:03:58','2020-06-13 17:03:58','2020-06-13 17:03:58',1,1,0,'2020-06-15 18:21:19'),(23,'s','','F','\0',0,'2020-06-12 17:03:58','2020-06-12 17:03:58','2020-06-13 17:03:58','2020-06-13 17:03:58',1,1,0,'2020-06-15 18:21:23'),(24,'s','','F','\0',0,'2020-06-12 17:03:58','2020-06-12 17:03:58','2020-06-13 17:03:58','2020-06-13 17:03:58',1,1,0,'2020-06-15 18:21:27'),(25,'s','','P','',0,'2020-06-12 17:03:58','2020-06-12 17:03:58','2020-06-13 17:03:58','2020-06-13 17:03:58',3,3,0,NULL),(26,'s','','N','',0,'2020-06-12 17:03:58','2020-06-12 17:03:58','2020-06-13 17:03:58','2020-06-13 17:03:58',0,0,0,NULL),(27,'s','','F','\0',0,'2020-06-12 17:03:58','2020-06-12 17:03:58','2020-06-13 17:03:58','2020-06-13 17:03:58',3121,65,0,'2020-07-04 20:27:31'),(28,'s','','F','\0',0,'2020-06-12 17:03:58','2020-06-12 17:03:58','2020-06-13 17:03:58','2020-06-13 17:03:58',3,3,0,'2020-07-04 20:27:35'),(29,'s','','N','',0,'2020-06-12 17:03:58','2020-06-12 17:03:58','2020-06-13 17:03:58','2020-06-13 17:03:58',0,0,0,NULL),(30,'s','','N','',0,'2020-06-12 17:03:58','2020-06-12 17:03:58','2020-06-13 17:03:58','2020-06-13 17:03:58',0,0,0,NULL),(31,'s','','N','',0,'2020-06-12 17:03:58','2020-06-12 17:03:58','2020-06-13 17:03:58','2020-06-13 17:03:58',0,0,0,NULL),(32,'s','','N','',0,'2020-06-12 17:03:58','2020-06-12 17:03:58','2020-06-13 17:03:58','2020-06-13 17:03:58',0,0,0,NULL),(33,'s','','N','',0,'2020-06-12 17:03:58','2020-06-12 17:03:58','2020-06-13 17:03:58','2020-06-13 17:03:58',0,0,0,NULL),(34,'s','','N','',0,'2020-06-12 17:03:58','2020-06-12 17:03:58','2020-06-13 17:03:58','2020-06-13 17:03:58',0,0,0,NULL),(35,'s','','F','\0',0,'2020-06-12 17:03:58','2020-06-12 17:03:58','2020-06-13 17:03:58','2020-06-13 17:03:58',2,2,0,'2020-07-04 20:28:08'),(36,'s','','F','\0',0,'2020-06-12 17:03:58','2020-06-12 17:03:58','2020-06-13 17:03:58','2020-06-13 17:03:58',2,2,0,'2020-07-04 20:28:13'),(37,'s','','F','\0',0,'2020-06-12 17:03:58','2020-06-12 17:03:58','2020-06-13 17:03:58','2020-06-13 17:03:58',6,6,0,'2020-07-04 20:28:42'),(38,'s','','F','\0',0,'2020-06-12 17:03:58','2020-06-12 17:03:58','2020-06-13 17:03:58','2020-06-13 17:03:58',180162,180157,0,'2020-07-06 22:31:20'),(39,'s','','P','\0',0,'2020-06-12 17:03:58','2020-06-12 17:03:58','2020-06-13 17:03:58','2020-06-13 17:03:58',9,7,0,NULL),(40,'s','','F','\0',0,'2020-06-12 17:03:58','2020-06-12 17:03:58','2020-06-13 17:03:58','2020-06-13 17:03:58',2,2,0,'2020-07-04 20:28:16'),(41,'s','','N','',0,'2020-06-12 17:03:58','2020-06-12 17:03:58','2020-06-13 17:03:58','2020-06-13 17:03:58',0,0,0,NULL),(42,'a','','N','',0,'2020-07-07 18:18:37','2020-07-07 18:18:37','2020-07-07 19:18:37','2020-07-07 19:18:37',0,0,0,NULL),(43,'a','','P','',0,'2020-08-18 17:18:37','2020-08-18 17:18:37','2020-08-18 18:18:37','2020-08-18 18:18:37',325929,325929,0,NULL),(44,'asdasas11','Test desc','N','\0',0,'2020-07-08 17:19:21','2020-07-08 17:19:21','2020-07-08 18:19:21','2020-07-08 18:19:21',0,0,0,NULL),(45,'fxbnvb','','F','\0',0,'2020-06-12 17:19:49','2020-06-12 17:19:49','2020-06-13 17:19:49','2020-06-13 17:19:49',1,1,0,'2020-06-15 18:21:47'),(46,'fxbnvb','','F','\0',0,'2020-06-12 17:19:49','2020-06-12 17:19:49','2020-06-13 17:19:49','2020-06-13 17:19:49',25,25,0,'2020-07-04 20:28:03'),(47,'fxbnvb','','F','\0',0,'2020-06-12 17:19:49','2020-06-12 17:19:49','2020-06-13 17:19:49','2020-06-13 17:19:49',15,15,0,'2020-07-04 20:28:03'),(48,'ss','','F','\0',0,'2020-06-12 20:53:24','2020-06-12 20:53:24','2020-06-13 17:53:24','2020-06-13 17:53:24',943500,943500,0,'2020-06-26 17:05:31'),(49,'Test','','F','\0',0,'2020-06-15 20:40:45','2020-06-15 20:40:45','2020-06-16 20:40:45','2020-06-16 20:40:45',20,20,0,'2020-07-04 20:28:04'),(50,'Today 15/6','','N','\0',0,'2020-08-15 20:41:46','2020-08-15 20:41:46','2020-08-15 23:45:00','2020-08-15 23:45:00',0,0,0,NULL),(51,'sfasfsdf','','N','\0',0,'2020-06-15 20:43:00','2020-06-15 20:43:00','2020-06-16 20:43:00','2020-06-16 20:43:00',0,0,0,NULL),(52,'sfasfsdf','','N','\0',0,'2020-06-15 20:43:00','2020-06-15 20:43:00','2020-06-16 20:43:00','2020-06-16 20:43:00',0,0,0,NULL),(53,'trewq','','N','\0',0,'2020-06-15 20:43:32','2020-06-15 20:43:32','2020-06-16 20:43:32','2020-06-16 20:43:32',0,0,0,NULL),(54,'Test','','F','\0',0,'2020-06-15 20:44:26','2020-06-15 20:44:26','2020-06-16 20:44:26','2020-06-16 20:44:26',683,683,0,'2020-07-06 22:31:40'),(55,'aaaa','','P','\0',0,'2020-06-15 20:44:56','2020-06-15 20:44:56','2020-06-16 20:44:56','2020-06-16 20:44:56',3,3,0,NULL),(56,'fredcv','','N','\0',0,'2020-06-15 20:45:49','2020-06-15 20:45:49','2020-06-16 20:45:49','2020-06-16 20:45:49',0,0,0,NULL),(57,'Test2','','N','\0',0,'2020-06-15 20:45:49','2020-06-15 20:45:49','2020-06-16 20:45:49','2020-06-16 20:45:49',0,0,0,NULL),(58,'New task','','N','\0',0,'2020-06-30 21:00:00','2020-06-30 21:00:00','2020-06-30 22:00:00','2020-06-30 22:00:00',0,0,0,NULL),(59,'cngvb','','F','\0',0,'2020-07-06 22:30:42','2020-07-06 22:30:42','2020-07-06 23:30:42','2020-07-06 23:30:42',62,62,0,'2020-07-06 22:31:45'),(60,'xxxxxxxx','','N','\0',0,'2020-07-06 22:31:16','2020-07-06 22:31:16','2020-07-06 23:31:16','2020-07-06 23:31:16',0,0,0,NULL),(61,'xxx','','F','\0',0,'2020-07-06 22:31:24','2020-07-06 22:31:24','2020-07-06 23:31:24','2020-07-06 23:31:24',17,17,0,'2020-07-06 22:31:41'),(62,'x2x','','N','\0',0,'2020-07-06 22:31:37','2020-07-06 22:31:37','2020-07-06 23:31:37','2020-07-06 23:31:37',0,0,0,NULL),(63,'x3x','','F','\0',0,'2020-07-06 22:31:53','2020-07-06 22:31:53','2020-07-06 23:31:53','2020-07-06 23:31:53',781220,781220,0,'2020-07-15 23:32:13'),(64,'Testh','','F','\0',0,'2020-07-06 22:33:44','2020-07-06 22:33:44','2020-07-06 23:33:44','2020-07-06 23:33:44',156,156,0,'2020-07-06 22:36:20'),(65,'Lets go','','F','\0',0,'2020-07-06 22:36:16','2020-07-06 22:36:16','2020-07-06 23:36:16','2020-07-06 23:36:16',780958,780958,0,'2020-07-15 23:32:14');
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
INSERT INTO `tasksevents` VALUES (1,1,1,'2020-06-04 23:02:46','America/Argentina/Buenos_Aires','S'),(2,1,1,'2020-06-04 23:02:49','America/Argentina/Buenos_Aires','F'),(3,1,2,'2020-06-04 23:03:23','America/Argentina/Buenos_Aires','S'),(4,1,2,'2020-06-04 23:03:25','America/Argentina/Buenos_Aires','F'),(5,1,3,'2020-06-04 23:04:20','America/Argentina/Buenos_Aires','S'),(6,1,3,'2020-06-04 23:04:22','America/Argentina/Buenos_Aires','F'),(7,1,4,'2020-06-04 23:06:57','America/Argentina/Buenos_Aires','S'),(8,1,4,'2020-06-04 23:07:02','America/Argentina/Buenos_Aires','F'),(9,1,5,'2020-06-04 23:08:26','America/Argentina/Buenos_Aires','S'),(10,1,5,'2020-06-08 23:08:39','America/Argentina/Buenos_Aires','F'),(11,1,6,'2020-06-04 23:09:51','America/Argentina/Buenos_Aires','S'),(12,1,6,'2020-06-08 23:10:01','America/Argentina/Buenos_Aires','P'),(13,1,6,'2020-06-08 23:10:44','America/Argentina/Buenos_Aires','R'),(14,1,6,'2020-06-13 23:10:53','America/Argentina/Buenos_Aires','F'),(15,1,7,'2020-06-04 23:12:30','America/Argentina/Buenos_Aires','S'),(16,1,7,'2020-06-08 23:12:41','America/Argentina/Buenos_Aires','P'),(17,1,7,'2020-06-08 23:13:07','America/Argentina/Buenos_Aires','R'),(18,1,7,'2020-06-11 23:13:19','America/Argentina/Buenos_Aires','F'),(19,1,8,'2020-06-04 23:14:52','America/Argentina/Buenos_Aires','S'),(20,1,8,'2020-06-07 23:15:00','America/Argentina/Buenos_Aires','F'),(21,1,9,'2020-06-04 23:16:19','America/Argentina/Buenos_Aires','S'),(22,1,9,'2020-06-07 23:16:28','America/Argentina/Buenos_Aires','F'),(23,1,10,'2020-06-04 23:17:59','America/Argentina/Buenos_Aires','S'),(24,1,10,'2020-06-08 23:18:12','America/Argentina/Buenos_Aires','F'),(25,1,11,'2020-06-08 23:19:35','America/Argentina/Buenos_Aires','S'),(26,1,11,'2020-06-11 23:19:47','America/Argentina/Buenos_Aires','F'),(27,1,12,'2020-06-04 23:21:48','America/Argentina/Buenos_Aires','S'),(28,1,12,'2020-06-07 23:21:59','America/Argentina/Buenos_Aires','P'),(29,1,12,'2020-06-07 23:22:14','America/Argentina/Buenos_Aires','R'),(30,1,12,'2020-06-10 23:22:23','America/Argentina/Buenos_Aires','F'),(31,1,21,'2020-06-15 18:21:14','America/Argentina/Buenos_Aires','S'),(32,1,21,'2020-06-15 18:21:15','America/Argentina/Buenos_Aires','F'),(33,1,22,'2020-06-15 18:21:18','America/Argentina/Buenos_Aires','S'),(34,1,22,'2020-06-15 18:21:19','America/Argentina/Buenos_Aires','F'),(35,1,23,'2020-06-15 18:21:22','America/Argentina/Buenos_Aires','S'),(36,1,23,'2020-06-15 18:21:23','America/Argentina/Buenos_Aires','F'),(37,1,24,'2020-06-15 18:21:26','America/Argentina/Buenos_Aires','S'),(38,1,24,'2020-06-15 18:21:27','America/Argentina/Buenos_Aires','F'),(39,1,25,'2020-06-15 18:21:31','America/Argentina/Buenos_Aires','S'),(40,1,25,'2020-06-15 18:21:34','America/Argentina/Buenos_Aires','P'),(41,1,16,'2020-06-15 18:21:39','America/Argentina/Buenos_Aires','S'),(42,1,16,'2020-06-15 18:21:40','America/Argentina/Buenos_Aires','F'),(43,1,45,'2020-06-15 18:21:46','America/Argentina/Buenos_Aires','S'),(44,1,45,'2020-06-15 18:21:47','America/Argentina/Buenos_Aires','F'),(45,1,48,'2020-06-15 19:00:31','America/Argentina/Buenos_Aires','S'),(46,1,48,'2020-06-26 17:05:31','America/Argentina/Buenos_Aires','F'),(47,1,13,'2020-06-30 22:24:59','America/Argentina/Buenos_Aires','S'),(48,1,13,'2020-07-01 01:55:11','America/Argentina/Buenos_Aires','F'),(49,1,43,'2020-07-01 01:55:15','America/Argentina/Buenos_Aires','S'),(50,1,27,'2020-07-04 19:35:29','America/Argentina/Buenos_Aires','S'),(51,1,27,'2020-07-04 19:36:30','America/Argentina/Buenos_Aires','P'),(52,1,55,'2020-07-04 19:36:40','America/Argentina/Buenos_Aires','S'),(53,1,55,'2020-07-04 19:36:43','America/Argentina/Buenos_Aires','P'),(54,1,43,'2020-07-04 20:27:24','America/Argentina/Buenos_Aires','P'),(55,1,27,'2020-07-04 20:27:26','America/Argentina/Buenos_Aires','R'),(56,1,27,'2020-07-04 20:27:30','America/Argentina/Buenos_Aires','F'),(57,1,28,'2020-07-04 20:27:32','America/Argentina/Buenos_Aires','S'),(58,1,28,'2020-07-04 20:27:35','America/Argentina/Buenos_Aires','F'),(59,1,46,'2020-07-04 20:27:37','America/Argentina/Buenos_Aires','S'),(60,1,49,'2020-07-04 20:27:44','America/Argentina/Buenos_Aires','S'),(61,1,47,'2020-07-04 20:27:48','America/Argentina/Buenos_Aires','S'),(62,1,46,'2020-07-04 20:28:02','America/Argentina/Buenos_Aires','F'),(63,1,47,'2020-07-04 20:28:03','America/Argentina/Buenos_Aires','F'),(64,1,49,'2020-07-04 20:28:04','America/Argentina/Buenos_Aires','F'),(65,1,35,'2020-07-04 20:28:06','America/Argentina/Buenos_Aires','S'),(66,1,35,'2020-07-04 20:28:08','America/Argentina/Buenos_Aires','F'),(67,1,36,'2020-07-04 20:28:11','America/Argentina/Buenos_Aires','S'),(68,1,36,'2020-07-04 20:28:13','America/Argentina/Buenos_Aires','F'),(69,1,40,'2020-07-04 20:28:14','America/Argentina/Buenos_Aires','S'),(70,1,40,'2020-07-04 20:28:16','America/Argentina/Buenos_Aires','F'),(71,1,37,'2020-07-04 20:28:36','America/Argentina/Buenos_Aires','S'),(72,1,38,'2020-07-04 20:28:38','America/Argentina/Buenos_Aires','S'),(73,1,39,'2020-07-04 20:28:40','America/Argentina/Buenos_Aires','S'),(74,1,37,'2020-07-04 20:28:42','America/Argentina/Buenos_Aires','F'),(75,1,39,'2020-07-04 20:28:44','America/Argentina/Buenos_Aires','P'),(76,1,38,'2020-07-04 20:28:45','America/Argentina/Buenos_Aires','P'),(77,1,39,'2020-07-04 20:28:46','America/Argentina/Buenos_Aires','R'),(78,1,39,'2020-07-04 20:28:49','America/Argentina/Buenos_Aires','P'),(79,1,38,'2020-07-04 20:28:50','America/Argentina/Buenos_Aires','R'),(80,1,54,'2020-07-06 22:20:17','America/Argentina/Buenos_Aires','S'),(81,1,59,'2020-07-06 22:30:43','America/Argentina/Buenos_Aires','S'),(82,1,38,'2020-07-06 22:31:20','America/Argentina/Buenos_Aires','F'),(83,1,61,'2020-07-06 22:31:24','America/Argentina/Buenos_Aires','S'),(84,1,54,'2020-07-06 22:31:40','America/Argentina/Buenos_Aires','F'),(85,1,61,'2020-07-06 22:31:41','America/Argentina/Buenos_Aires','F'),(86,1,59,'2020-07-06 22:31:45','America/Argentina/Buenos_Aires','F'),(87,1,63,'2020-07-06 22:31:53','America/Argentina/Buenos_Aires','S'),(88,1,64,'2020-07-06 22:33:44','America/Argentina/Buenos_Aires','S'),(89,1,65,'2020-07-06 22:36:16','America/Argentina/Buenos_Aires','S'),(90,1,64,'2020-07-06 22:36:20','America/Argentina/Buenos_Aires','F'),(91,1,63,'2020-07-15 23:32:13','America/Argentina/Buenos_Aires','F'),(92,1,65,'2020-07-15 23:32:14','America/Argentina/Buenos_Aires','F');
/*!40000 ALTER TABLE `tasksevents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tasksnotes`
--

DROP TABLE IF EXISTS `tasksnotes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `tasksnotes` (
  `id` int(11) NOT NULL,
  `taskid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `note` text COLLATE utf8_bin,
  `private` bit(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `usernote_FK1_idx` (`userid`),
  KEY `tasknote_FK2_idx` (`taskid`),
  CONSTRAINT `tasknote_FK2` FOREIGN KEY (`taskid`) REFERENCES `tasks` (`id`),
  CONSTRAINT `usernote_FK1` FOREIGN KEY (`userid`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tasksnotes`
--

LOCK TABLES `tasksnotes` WRITE;
/*!40000 ALTER TABLE `tasksnotes` DISABLE KEYS */;
INSERT INTO `tasksnotes` VALUES (1,13,1,'2020-07-01 01:26:33','sfsfsd','\0'),(2,13,1,'2020-07-01 01:27:52','fsfsdfssd','\0'),(3,13,1,'2020-07-01 01:28:19','fdasfsf','\0'),(4,13,1,'2020-07-01 01:29:03','sfsds','\0'),(5,13,1,'2020-07-01 01:30:03','dasadas',''),(6,13,1,'2020-07-01 01:30:50','asdada','\0'),(7,13,1,'2020-07-01 01:31:29','asdadsa','\0'),(8,13,1,'2020-07-01 01:41:43','Test',''),(9,13,1,'2020-07-01 01:49:10','Trentatre trentini venivan giu da trento tutti e trentatre trotterellando',''),(10,13,1,'2020-07-01 01:51:53','Test append','\0'),(11,13,1,'2020-07-01 01:52:05','Test append','\0'),(12,13,1,'2020-07-01 01:52:47','asdassa','\0'),(13,13,1,'2020-07-01 01:53:24','asdas','\0'),(14,13,1,'2020-07-01 01:53:56','dasdassa','\0'),(15,13,1,'2020-07-01 01:55:01','asdas','\0'),(16,43,1,'2020-07-01 01:55:19','New note','\0'),(17,43,1,'2020-07-01 01:55:59','sdfghjh,gfds','\0'),(18,43,1,'2020-07-01 01:56:32','adasdasda','\0');
/*!40000 ALTER TABLE `tasksnotes` ENABLE KEYS */;
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
INSERT INTO `taskstimespans` VALUES (1,1,4,7,'2020-06-04 23:06:57','S',8,'2020-06-04 23:07:02','F','America/Argentina/Buenos_Aires'),(2,1,5,9,'2020-06-04 23:08:26','S',10,'2020-06-05 00:00:00','F','America/Argentina/Buenos_Aires'),(3,1,5,9,'2020-06-05 00:00:00','R',10,'2020-06-06 00:00:00','F','America/Argentina/Buenos_Aires'),(4,1,5,9,'2020-06-06 00:00:00','R',10,'2020-06-07 00:00:00','F','America/Argentina/Buenos_Aires'),(5,1,5,9,'2020-06-07 00:00:00','R',10,'2020-06-08 00:00:00','F','America/Argentina/Buenos_Aires'),(6,1,5,9,'2020-06-08 00:00:00','R',10,'2020-06-08 23:08:39','F','America/Argentina/Buenos_Aires'),(7,1,6,11,'2020-06-04 23:09:51','S',12,'2020-06-05 00:00:00','P','America/Argentina/Buenos_Aires'),(8,1,6,11,'2020-06-05 00:00:00','R',12,'2020-06-06 00:00:00','P','America/Argentina/Buenos_Aires'),(9,1,6,11,'2020-06-06 00:00:00','R',12,'2020-06-07 00:00:00','P','America/Argentina/Buenos_Aires'),(10,1,6,11,'2020-06-07 00:00:00','R',12,'2020-06-08 00:00:00','P','America/Argentina/Buenos_Aires'),(11,1,6,11,'2020-06-08 00:00:00','R',12,'2020-06-08 23:10:01','P','America/Argentina/Buenos_Aires'),(12,1,6,13,'2020-06-08 23:10:44','R',14,'2020-06-09 00:00:00','F','America/Argentina/Buenos_Aires'),(13,1,6,13,'2020-06-09 00:00:00','R',14,'2020-06-10 00:00:00','F','America/Argentina/Buenos_Aires'),(14,1,6,13,'2020-06-10 00:00:00','R',14,'2020-06-11 00:00:00','F','America/Argentina/Buenos_Aires'),(15,1,6,13,'2020-06-11 00:00:00','R',14,'2020-06-12 00:00:00','F','America/Argentina/Buenos_Aires'),(16,1,6,13,'2020-06-12 00:00:00','R',14,'2020-06-13 00:00:00','F','America/Argentina/Buenos_Aires'),(17,1,6,13,'2020-06-13 00:00:00','R',14,'2020-06-13 23:10:53','F','America/Argentina/Buenos_Aires'),(18,1,7,15,'2020-06-04 23:12:30','S',16,'2020-06-05 00:00:00','P','America/Argentina/Buenos_Aires'),(19,1,7,15,'2020-06-05 00:00:00','R',16,'2020-06-06 00:00:00','P','America/Argentina/Buenos_Aires'),(20,1,7,15,'2020-06-06 00:00:00','R',16,'2020-06-07 00:00:00','P','America/Argentina/Buenos_Aires'),(21,1,7,15,'2020-06-07 00:00:00','R',16,'2020-06-08 00:00:00','P','America/Argentina/Buenos_Aires'),(22,1,7,15,'2020-06-08 00:00:00','R',16,'2020-06-08 23:12:41','P','America/Argentina/Buenos_Aires'),(23,1,7,17,'2020-06-08 23:13:07','R',18,'2020-06-09 00:00:00','F','America/Argentina/Buenos_Aires'),(24,1,7,17,'2020-06-09 00:00:00','R',18,'2020-06-10 00:00:00','F','America/Argentina/Buenos_Aires'),(25,1,7,17,'2020-06-10 00:00:00','R',18,'2020-06-11 00:00:00','F','America/Argentina/Buenos_Aires'),(26,1,7,17,'2020-06-11 00:00:00','R',18,'2020-06-11 23:13:19','F','America/Argentina/Buenos_Aires'),(27,1,8,19,'2020-06-04 23:14:52','S',20,'2020-06-05 00:00:00','F','America/Argentina/Buenos_Aires'),(28,1,8,19,'2020-06-05 00:00:00','R',20,'2020-06-06 00:00:00','F','America/Argentina/Buenos_Aires'),(29,1,8,19,'2020-06-06 00:00:00','R',20,'2020-06-07 00:00:00','F','America/Argentina/Buenos_Aires'),(30,1,8,19,'2020-06-07 00:00:00','R',20,'2020-06-07 23:15:00','F','America/Argentina/Buenos_Aires'),(31,1,9,21,'2020-06-04 23:16:19','S',22,'2020-06-05 00:00:00','F','America/Argentina/Buenos_Aires'),(32,1,9,21,'2020-06-05 00:00:00','R',22,'2020-06-06 00:00:00','F','America/Argentina/Buenos_Aires'),(33,1,9,21,'2020-06-06 00:00:00','R',22,'2020-06-07 00:00:00','F','America/Argentina/Buenos_Aires'),(34,1,9,21,'2020-06-07 00:00:00','R',22,'2020-06-07 23:16:28','F','America/Argentina/Buenos_Aires'),(35,1,10,23,'2020-06-04 23:17:59','S',24,'2020-06-05 00:00:00','P','America/Argentina/Buenos_Aires'),(36,1,10,23,'2020-06-05 00:00:00','R',24,'2020-06-06 00:00:00','P','America/Argentina/Buenos_Aires'),(37,1,10,23,'2020-06-06 00:00:00','R',24,'2020-06-07 00:00:00','P','America/Argentina/Buenos_Aires'),(38,1,10,23,'2020-06-07 00:00:00','R',24,'2020-06-08 00:00:00','P','America/Argentina/Buenos_Aires'),(39,1,10,23,'2020-06-08 00:00:00','R',24,'2020-06-08 23:18:12','P','America/Argentina/Buenos_Aires'),(40,1,11,25,'2020-06-08 23:19:35','S',26,'2020-06-09 00:00:00','P','America/Argentina/Buenos_Aires'),(41,1,11,25,'2020-06-09 00:00:00','R',26,'2020-06-10 00:00:00','P','America/Argentina/Buenos_Aires'),(42,1,11,25,'2020-06-10 00:00:00','R',26,'2020-06-11 00:00:00','P','America/Argentina/Buenos_Aires'),(43,1,11,25,'2020-06-11 00:00:00','R',26,'2020-06-11 23:19:47','F','America/Argentina/Buenos_Aires'),(44,1,12,27,'2020-06-04 23:21:48','S',28,'2020-06-05 00:00:00','P','America/Argentina/Buenos_Aires'),(45,1,12,27,'2020-06-05 00:00:00','R',28,'2020-06-06 00:00:00','P','America/Argentina/Buenos_Aires'),(46,1,12,27,'2020-06-06 00:00:00','R',28,'2020-06-07 00:00:00','P','America/Argentina/Buenos_Aires'),(47,1,12,27,'2020-06-07 00:00:00','R',28,'2020-06-07 23:21:59','P','America/Argentina/Buenos_Aires'),(48,1,12,29,'2020-06-07 23:22:14','R',30,'2020-06-08 00:00:00','P','America/Argentina/Buenos_Aires'),(49,1,12,29,'2020-06-08 00:00:00','R',30,'2020-06-09 00:00:00','P','America/Argentina/Buenos_Aires'),(50,1,12,29,'2020-06-09 00:00:00','R',30,'2020-06-10 00:00:00','P','America/Argentina/Buenos_Aires'),(51,1,12,29,'2020-06-10 00:00:00','R',30,'2020-06-10 23:22:23','F','America/Argentina/Buenos_Aires'),(52,1,21,31,'2020-06-15 18:21:14','S',32,'2020-06-15 18:21:15','F','America/Argentina/Buenos_Aires'),(53,1,22,33,'2020-06-15 18:21:18','S',34,'2020-06-15 18:21:19','F','America/Argentina/Buenos_Aires'),(54,1,23,35,'2020-06-15 18:21:22','S',36,'2020-06-15 18:21:23','F','America/Argentina/Buenos_Aires'),(55,1,24,37,'2020-06-15 18:21:26','S',38,'2020-06-15 18:21:27','F','America/Argentina/Buenos_Aires'),(56,1,25,39,'2020-06-15 18:21:31','S',40,'2020-06-15 18:21:34','P','America/Argentina/Buenos_Aires'),(57,1,16,41,'2020-06-15 18:21:39','S',42,'2020-06-15 18:21:40','F','America/Argentina/Buenos_Aires'),(58,1,45,43,'2020-06-15 18:21:46','S',44,'2020-06-15 18:21:47','F','America/Argentina/Buenos_Aires'),(59,1,48,45,'2020-06-15 19:00:31','S',46,'2020-06-16 00:00:00','P','America/Argentina/Buenos_Aires'),(60,1,48,45,'2020-06-16 00:00:00','R',46,'2020-06-17 00:00:00','P','America/Argentina/Buenos_Aires'),(61,1,48,45,'2020-06-17 00:00:00','R',46,'2020-06-18 00:00:00','P','America/Argentina/Buenos_Aires'),(62,1,48,45,'2020-06-18 00:00:00','R',46,'2020-06-19 00:00:00','P','America/Argentina/Buenos_Aires'),(63,1,48,45,'2020-06-19 00:00:00','R',46,'2020-06-20 00:00:00','P','America/Argentina/Buenos_Aires'),(64,1,48,45,'2020-06-20 00:00:00','R',46,'2020-06-21 00:00:00','P','America/Argentina/Buenos_Aires'),(65,1,48,45,'2020-06-21 00:00:00','R',46,'2020-06-22 00:00:00','P','America/Argentina/Buenos_Aires'),(66,1,48,45,'2020-06-22 00:00:00','R',46,'2020-06-23 00:00:00','P','America/Argentina/Buenos_Aires'),(67,1,48,45,'2020-06-23 00:00:00','R',46,'2020-06-24 00:00:00','P','America/Argentina/Buenos_Aires'),(68,1,48,45,'2020-06-24 00:00:00','R',46,'2020-06-25 00:00:00','P','America/Argentina/Buenos_Aires'),(69,1,48,45,'2020-06-25 00:00:00','R',46,'2020-06-26 00:00:00','P','America/Argentina/Buenos_Aires'),(70,1,48,45,'2020-06-26 00:00:00','R',46,'2020-06-26 17:05:31','F','America/Argentina/Buenos_Aires'),(71,1,13,47,'2020-06-30 22:24:59','S',48,'2020-07-01 00:00:00','P','America/Argentina/Buenos_Aires'),(72,1,13,47,'2020-07-01 00:00:00','S',48,'2020-07-01 01:55:11','F','America/Argentina/Buenos_Aires'),(73,1,27,50,'2020-07-04 19:35:29','S',51,'2020-07-04 19:36:30','P','America/Argentina/Buenos_Aires'),(74,1,55,52,'2020-07-04 19:36:40','S',53,'2020-07-04 19:36:43','P','America/Argentina/Buenos_Aires'),(75,1,43,49,'2020-07-01 01:55:15','S',54,'2020-07-02 00:00:00','P','America/Argentina/Buenos_Aires'),(76,1,43,49,'2020-07-02 00:00:00','R',54,'2020-07-03 00:00:00','P','America/Argentina/Buenos_Aires'),(77,1,43,49,'2020-07-03 00:00:00','R',54,'2020-07-04 00:00:00','P','America/Argentina/Buenos_Aires'),(78,1,43,49,'2020-07-04 00:00:00','R',54,'2020-07-04 20:27:24','P','America/Argentina/Buenos_Aires'),(79,1,27,55,'2020-07-04 20:27:26','R',56,'2020-07-04 20:27:30','F','America/Argentina/Buenos_Aires'),(80,1,28,57,'2020-07-04 20:27:32','S',58,'2020-07-04 20:27:35','F','America/Argentina/Buenos_Aires'),(81,1,46,59,'2020-07-04 20:27:37','S',62,'2020-07-04 20:28:02','F','America/Argentina/Buenos_Aires'),(82,1,47,61,'2020-07-04 20:27:48','S',63,'2020-07-04 20:28:03','F','America/Argentina/Buenos_Aires'),(83,1,49,60,'2020-07-04 20:27:44','S',64,'2020-07-04 20:28:04','F','America/Argentina/Buenos_Aires'),(84,1,35,65,'2020-07-04 20:28:06','S',66,'2020-07-04 20:28:08','F','America/Argentina/Buenos_Aires'),(85,1,36,67,'2020-07-04 20:28:11','S',68,'2020-07-04 20:28:13','F','America/Argentina/Buenos_Aires'),(86,1,40,69,'2020-07-04 20:28:14','S',70,'2020-07-04 20:28:16','F','America/Argentina/Buenos_Aires'),(87,1,37,71,'2020-07-04 20:28:36','S',74,'2020-07-04 20:28:42','F','America/Argentina/Buenos_Aires'),(88,1,39,73,'2020-07-04 20:28:40','S',75,'2020-07-04 20:28:44','P','America/Argentina/Buenos_Aires'),(89,1,38,72,'2020-07-04 20:28:38','S',76,'2020-07-04 20:28:45','P','America/Argentina/Buenos_Aires'),(90,1,39,77,'2020-07-04 20:28:46','R',78,'2020-07-04 20:28:49','P','America/Argentina/Buenos_Aires'),(91,1,38,79,'2020-07-04 20:28:50','R',82,'2020-07-05 00:00:00','P','America/Argentina/Buenos_Aires'),(92,1,38,79,'2020-07-05 00:00:00','R',82,'2020-07-06 00:00:00','P','America/Argentina/Buenos_Aires'),(93,1,38,79,'2020-07-06 00:00:00','R',82,'2020-07-06 22:31:20','F','America/Argentina/Buenos_Aires'),(94,1,54,80,'2020-07-06 22:20:17','S',84,'2020-07-06 22:31:40','F','America/Argentina/Buenos_Aires'),(95,1,61,83,'2020-07-06 22:31:24','S',85,'2020-07-06 22:31:41','F','America/Argentina/Buenos_Aires'),(96,1,59,81,'2020-07-06 22:30:43','S',86,'2020-07-06 22:31:45','F','America/Argentina/Buenos_Aires'),(97,1,64,88,'2020-07-06 22:33:44','S',90,'2020-07-06 22:36:20','F','America/Argentina/Buenos_Aires'),(98,1,63,87,'2020-07-06 22:31:53','S',91,'2020-07-07 00:00:00','P','America/Argentina/Buenos_Aires'),(99,1,63,87,'2020-07-07 00:00:00','R',91,'2020-07-08 00:00:00','P','America/Argentina/Buenos_Aires'),(100,1,63,87,'2020-07-08 00:00:00','R',91,'2020-07-09 00:00:00','P','America/Argentina/Buenos_Aires'),(101,1,63,87,'2020-07-09 00:00:00','R',91,'2020-07-10 00:00:00','P','America/Argentina/Buenos_Aires'),(102,1,63,87,'2020-07-10 00:00:00','R',91,'2020-07-11 00:00:00','P','America/Argentina/Buenos_Aires'),(103,1,63,87,'2020-07-11 00:00:00','R',91,'2020-07-12 00:00:00','P','America/Argentina/Buenos_Aires'),(104,1,63,87,'2020-07-12 00:00:00','R',91,'2020-07-13 00:00:00','P','America/Argentina/Buenos_Aires'),(105,1,63,87,'2020-07-13 00:00:00','R',91,'2020-07-14 00:00:00','P','America/Argentina/Buenos_Aires'),(106,1,63,87,'2020-07-14 00:00:00','R',91,'2020-07-15 00:00:00','P','America/Argentina/Buenos_Aires'),(107,1,63,87,'2020-07-15 00:00:00','R',91,'2020-07-15 23:32:13','F','America/Argentina/Buenos_Aires'),(108,1,65,89,'2020-07-06 22:36:16','S',92,'2020-07-07 00:00:00','P','America/Argentina/Buenos_Aires'),(109,1,65,89,'2020-07-07 00:00:00','R',92,'2020-07-08 00:00:00','P','America/Argentina/Buenos_Aires'),(110,1,65,89,'2020-07-08 00:00:00','R',92,'2020-07-09 00:00:00','P','America/Argentina/Buenos_Aires'),(111,1,65,89,'2020-07-09 00:00:00','R',92,'2020-07-10 00:00:00','P','America/Argentina/Buenos_Aires'),(112,1,65,89,'2020-07-10 00:00:00','R',92,'2020-07-11 00:00:00','P','America/Argentina/Buenos_Aires'),(113,1,65,89,'2020-07-11 00:00:00','R',92,'2020-07-12 00:00:00','P','America/Argentina/Buenos_Aires'),(114,1,65,89,'2020-07-12 00:00:00','R',92,'2020-07-13 00:00:00','P','America/Argentina/Buenos_Aires'),(115,1,65,89,'2020-07-13 00:00:00','R',92,'2020-07-14 00:00:00','P','America/Argentina/Buenos_Aires'),(116,1,65,89,'2020-07-14 00:00:00','R',92,'2020-07-15 00:00:00','P','America/Argentina/Buenos_Aires'),(117,1,65,89,'2020-07-15 00:00:00','R',92,'2020-07-15 23:32:14','F','America/Argentina/Buenos_Aires');
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
INSERT INTO `userconfiguration` VALUES (1,'Main','Timezone','America/Argentina/Buenos_Aires'),(1,'Main','default_task_id','-1'),(1,'Main','max_tasks_in_execution','2');
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

--
-- Table structure for table `usersexternalaccounts`
--

DROP TABLE IF EXISTS `usersexternalaccounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `usersexternalaccounts` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `ExternalAccountType` varchar(45) COLLATE utf8_bin NOT NULL,
  `ExternalAccountName` varchar(45) COLLATE utf8_bin NOT NULL,
  `AccountName` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `token_type` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `scope` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `id_token` text COLLATE utf8_bin,
  `access_token` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `refresh_token` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `created` bigint(20) DEFAULT NULL,
  `expires_in` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `extappuserFK_idx` (`userid`),
  CONSTRAINT `extappuserFK` FOREIGN KEY (`userid`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usersexternalaccounts`
--

LOCK TABLES `usersexternalaccounts` WRITE;
/*!40000 ALTER TABLE `usersexternalaccounts` DISABLE KEYS */;
INSERT INTO `usersexternalaccounts` VALUES (1,1,'Google','Google Calendar','mgrisoster@gmail.com','Bearer','openid https://www.googleapis.com/auth/calendar https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email','eyJhbGciOiJSUzI1NiIsImtpZCI6Ijc0NGY2MGU5ZmI1MTVhMmEwMWMxMWViZWIyMjg3MTI4NjA1NDA3MTEiLCJ0eXAiOiJKV1QifQ.eyJpc3MiOiJodHRwczovL2FjY291bnRzLmdvb2dsZS5jb20iLCJhenAiOiI2MDE2ODQwODI4NjktY2QwZHRocTNwYzhyOGVwczlub2prdDZuMmVnbW1vb3IuYXBwcy5nb29nbGV1c2VyY29udGVudC5jb20iLCJhdWQiOiI2MDE2ODQwODI4NjktY2QwZHRocTNwYzhyOGVwczlub2prdDZuMmVnbW1vb3IuYXBwcy5nb29nbGV1c2VyY29udGVudC5jb20iLCJzdWIiOiIxMDY3MDgxNjQ3NzI1NzU0MTcyMDUiLCJlbWFpbCI6Im1ncmlzb3N0ZXJAZ21haWwuY29tIiwiZW1haWxfdmVyaWZpZWQiOnRydWUsImF0X2hhc2giOiJNZlVFa204LXlLQnRpczBtNHRLT2VBIiwibmFtZSI6Ik1hdHRlbyBHcmlzbyIsInBpY3R1cmUiOiJodHRwczovL2xoMy5nb29nbGV1c2VyY29udGVudC5jb20vYS0vQU9oMTRHZ2szV3FiNXl5cGE1LUlISjlMQm1UNEl3eTlxZzd4Zl9ab1czdWNlZz1zOTYtYyIsImdpdmVuX25hbWUiOiJNYXR0ZW8iLCJmYW1pbHlfbmFtZSI6IkdyaXNvIiwibG9jYWxlIjoiZW4iLCJpYXQiOjE1OTcyMzcwMDAsImV4cCI6MTU5NzI0MDYwMH0.iZp10oOJHHJbJoIe9Gpv1O5iWGrDkV6nThLYZ4lXl9fBRbjf47GXUZDxTIuQ567VZl8naNwsAn9XBJv3GIYvFTafx4SIp1lHrVY_Mnhb8fGp5qAul32FeV8njTHw3PFJFYxe-zz2u2Rh9bkFvXp3ttvX_QXppLlaKJC426EnbYMZGoFvNSNhxz8wa1qiY0h3A981TTDm4MAN4933yf5l0e6sp8ZgSqqZBkN_d6QVqG5UJiJa07EIYYIf0FeXQmtldR4edqzSQX7xPel01vh6lb71Syn34eDVhbmN3gspBZAZTtpuhlZuXx-ZKXKryvQFDNbzWOwzTs0SBR7AfRGFdQ','ya29.a0AfH6SMB86q4U4XCr9NnVP3LMaW3j3CQIlW8Hw-J929c2zEFpkvi9UUQ2RwULOVgILpLMlzEuG-llh2EA9RYk9dwus02p8TCGkdVjmfQnXrgR6Fk1Bn7EjR2uNWRiu1PXgF8oCF9xJWD4NJ3UPN6XpyqiDWjUUoE25mo','1//05on7v6fxcC_7CgYIARAAGAUSNwF-L9IrfB9oznNj53Bo3YN9Ow4A3Ei1Ey1jFAiLAglGdyfYIyOLgYiKiW_7NnjsVl0aDtJIVbk',1597237000,3598),(2,1,'Google','Google Calendar','matteo.griso@virtualchief.net','Bearer','https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/calendar openid','eyJhbGciOiJSUzI1NiIsImtpZCI6Ijc0NGY2MGU5ZmI1MTVhMmEwMWMxMWViZWIyMjg3MTI4NjA1NDA3MTEiLCJ0eXAiOiJKV1QifQ.eyJpc3MiOiJodHRwczovL2FjY291bnRzLmdvb2dsZS5jb20iLCJhenAiOiI2MDE2ODQwODI4NjktY2QwZHRocTNwYzhyOGVwczlub2prdDZuMmVnbW1vb3IuYXBwcy5nb29nbGV1c2VyY29udGVudC5jb20iLCJhdWQiOiI2MDE2ODQwODI4NjktY2QwZHRocTNwYzhyOGVwczlub2prdDZuMmVnbW1vb3IuYXBwcy5nb29nbGV1c2VyY29udGVudC5jb20iLCJzdWIiOiIxMDI0Mzg0NjEwMDg3NDg2MDA3NDMiLCJoZCI6InZpcnR1YWxjaGllZi5uZXQiLCJlbWFpbCI6Im1hdHRlby5ncmlzb0B2aXJ0dWFsY2hpZWYubmV0IiwiZW1haWxfdmVyaWZpZWQiOnRydWUsImF0X2hhc2giOiItcENuNk5WZ1dCX2dmREd1cEZaV1NBIiwibmFtZSI6Ik1hdHRlbyBHcmlzbyIsImdpdmVuX25hbWUiOiJNYXR0ZW8iLCJmYW1pbHlfbmFtZSI6IkdyaXNvIiwibG9jYWxlIjoiZW4iLCJpYXQiOjE1OTcyMzkwNjQsImV4cCI6MTU5NzI0MjY2NH0.h_yF1zwlh_pDV2ZfQf6VVNXc9JspklnhAcxfeacmrtZU58TAIPR2XtFj_Nwh05xDGO10YmCvDLifpLnvs5YWAm5g5qc2w-nuk8rpyZg8AiGE_xqta6tNYUCRAi9vyHIpvmoJ3DXvWBGzOvYWx9IEAxZt0PFNUtDQu1mQNkg1zu8EAp8S4crIWz2Z-u_U_1btu7FdZIUEy6fxVRBNrmy3E1ySqTywsNeoJO4aXk4hBJK3LnIOB9FYJ0k4PINdkrn46Sswej92MkJIzjNTnrlEGWiXArJHzXktMuJMyfTPSyzD-CEFmSeRxhEQTRAIp_QJuESSZDBwu5x7cfrPBnXImQ','ya29.a0AfH6SMDoWnvlvgKK_yNt_KBBTxhFSMDBBLzGq_PmjnLTGyqq21Fx8hAKvvh__4fWBmSzFHTbAcx5VkV7I8Ieq_NXKkn_IPY_eRQXJK5r0c8MJ1JVVMdJ87gW90vbwHjJzC6F-u46VMnjBMP-GJ4XPuCaN3GPcNxU-ls','1//0dGemcpbFORi3CgYIARAAGA0SNwF-L9Ir1r7owHGnLOCj-MrVbo2zV9eqvEnUZIxZYB9t9BIzVOXLyKkPYbpETRDbmSejtyLKGFI',1597239064,3598);
/*!40000 ALTER TABLE `usersexternalaccounts` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-08-15 18:51:57
