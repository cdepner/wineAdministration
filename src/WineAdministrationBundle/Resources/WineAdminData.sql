-- MySQL dump 10.13  Distrib 5.6.17, for Win64 (x86_64)
--
-- Host: localhost    Database: WineAdministration
-- ------------------------------------------------------
-- Server version	5.5.5-10.0.16-MariaDB-1~trusty

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
-- Dumping data for table `City`
--

LOCK TABLES `City` WRITE;
/*!40000 ALTER TABLE `City` DISABLE KEYS */;
INSERT INTO `City` VALUES (3,'Berlin',12487),(1,'Bernkastel-Wehlen',54470),(2,'La cadière d\'Azur',83740);
/*!40000 ALTER TABLE `City` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `Client`
--

LOCK TABLES `Client` WRITE;
/*!40000 ALTER TABLE `Client` DISABLE KEYS */;
INSERT INTO `Client` VALUES (1,'Hans','Peter','Heimweg','2',3);
/*!40000 ALTER TABLE `Client` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ClientOrder`
--

LOCK TABLES `ClientOrder` WRITE;
/*!40000 ALTER TABLE `ClientOrder` DISABLE KEYS */;
INSERT INTO `ClientOrder` VALUES (1,'0000-00-00',1);
/*!40000 ALTER TABLE `ClientOrder` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ClientPhone`
--

LOCK TABLES `ClientPhone` WRITE;
/*!40000 ALTER TABLE `ClientPhone` DISABLE KEYS */;
INSERT INTO `ClientPhone` VALUES (1,'030/666666',1);
/*!40000 ALTER TABLE `ClientPhone` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `Country`
--

LOCK TABLES `Country` WRITE;
/*!40000 ALTER TABLE `Country` DISABLE KEYS */;
INSERT INTO `Country` VALUES (1,'Deutschland'),(2,'Frankreich');
/*!40000 ALTER TABLE `Country` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `Region`
--

LOCK TABLES `Region` WRITE;
/*!40000 ALTER TABLE `Region` DISABLE KEYS */;
INSERT INTO `Region` VALUES (1,'Ahr',1),(2,'Baden',1),(3,'Franken',1),(4,'Mosel',1),(5,'Pfalz',1),(6,'Rheingau',1),(7,'Reinhessen',1),(8,'Rheinland-Pfalz',1),(9,'Württemberg',1),(10,'Beaujolais',2),(11,'Bordeaux',2),(12,'Burgund',2),(13,'Elsass',2),(14,'Languedoc Roussillon',2),(15,'Provence',2),(16,'Rhône',2),(17,'Sud-Ouest',2);
/*!40000 ALTER TABLE `Region` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `Vineyard`
--

LOCK TABLES `Vineyard` WRITE;
/*!40000 ALTER TABLE `Vineyard` DISABLE KEYS */;
INSERT INTO `Vineyard` VALUES (1,'Markus Molitor',1,4),(2,'Château de Pibarnon',2,15);
/*!40000 ALTER TABLE `Vineyard` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `Wine`
--

LOCK TABLES `Wine` WRITE;
/*!40000 ALTER TABLE `Wine` DISABLE KEYS */;
INSERT INTO `Wine` VALUES (1,1,10.9,'Schiefersteil','0000-00-00',1,1,1,1),(2,1,33,'Zeltinger Sonnenuhr','0000-00-00',1,1,4,0.75),(3,0,40.1,'Rouge','0000-00-00',1,2,2,0.75);
/*!40000 ALTER TABLE `Wine` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `WineKind`
--

LOCK TABLES `WineKind` WRITE;
/*!40000 ALTER TABLE `WineKind` DISABLE KEYS */;
INSERT INTO `WineKind` VALUES (3,'Feinherb'),(2,'Halbtrocken'),(4,'Lieblich'),(1,'Trocken');
/*!40000 ALTER TABLE `WineKind` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `WineToClientOrder`
--

LOCK TABLES `WineToClientOrder` WRITE;
/*!40000 ALTER TABLE `WineToClientOrder` DISABLE KEYS */;
/*!40000 ALTER TABLE `WineToClientOrder` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `WineToWineVarietal`
--

LOCK TABLES `WineToWineVarietal` WRITE;
/*!40000 ALTER TABLE `WineToWineVarietal` DISABLE KEYS */;
INSERT INTO `WineToWineVarietal` VALUES (3,1,4),(1,2,4),(2,3,6);
/*!40000 ALTER TABLE `WineToWineVarietal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `WineType`
--

LOCK TABLES `WineType` WRITE;
/*!40000 ALTER TABLE `WineType` DISABLE KEYS */;
INSERT INTO `WineType` VALUES (1,'Rotwein'),(2,'Weißwein');
/*!40000 ALTER TABLE `WineType` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `WineVarietal`
--

LOCK TABLES `WineVarietal` WRITE;
/*!40000 ALTER TABLE `WineVarietal` DISABLE KEYS */;
INSERT INTO `WineVarietal` VALUES (3,'Barrique'),(6,'Cuvée'),(2,'Dornfelder'),(5,'Kerner'),(4,'Riesling'),(1,'Spätburgunder');
/*!40000 ALTER TABLE `WineVarietal` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-02-12 20:56:03
