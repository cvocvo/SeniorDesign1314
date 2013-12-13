-- MySQL dump 10.13  Distrib 5.5.34, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: wseclab
-- ------------------------------------------------------
-- Server version	5.5.34-0ubuntu0.12.04.1

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
-- Table structure for table `class_vm_types`
--

DROP TABLE IF EXISTS `class_vm_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `class_vm_types` (
  `class_id` int(11) NOT NULL,
  `vm_type_id` int(11) NOT NULL,
  PRIMARY KEY (`class_id`,`vm_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `class_vm_types`
--

LOCK TABLES `class_vm_types` WRITE;
/*!40000 ALTER TABLE `class_vm_types` DISABLE KEYS */;
INSERT INTO `class_vm_types` VALUES (1,1),(1,2),(1,3),(3,1),(3,2),(3,3);
/*!40000 ALTER TABLE `class_vm_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `classes`
--

DROP TABLE IF EXISTS `classes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `classes` (
  `class_id` int(11) NOT NULL AUTO_INCREMENT,
  `class_name` varchar(32) NOT NULL,
  PRIMARY KEY (`class_id`),
  UNIQUE KEY `class_name` (`class_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `classes`
--

LOCK TABLES `classes` WRITE;
/*!40000 ALTER TABLE `classes` DISABLE KEYS */;
INSERT INTO `classes` VALUES (3,'cpre492'),(1,'default');
/*!40000 ALTER TABLE `classes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(32) NOT NULL,
  `user_first` varchar(32) DEFAULT NULL,
  `user_last` varchar(32) DEFAULT NULL,
  `user_email` varchar(32) DEFAULT NULL,
  `user_is_admin` tinyint(1) NOT NULL,
  `user_hash` varchar(64) DEFAULT NULL,
  `user_salt` varchar(64) DEFAULT NULL,
  `user_class` int(11) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_name` (`user_name`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (15,'matt',NULL,NULL,NULL,0,'8d67222a711929f5025488a1a533ba035f000628e2c934281f53386dc8799da1','1e8218e091d5eea26fc9ca7264a70945b17ffa567fd39f75bef3603ec02927f4',3),(16,'frank',NULL,NULL,NULL,0,'b7cba96b40a19d83cea0fd08498bc2b52bb961195b4ee2008917f0ee1eb104cb','1cb2c6beaae19bfb5c0f7e5997b921728987d93259169521a9849fa03714f149',3),(17,'tahsin',NULL,NULL,NULL,0,'23ac0b35e17f803097a1d5395e7ce4d3bd6b246bdc71a15b6b94ca10257c8a18','49e99a32ae57203b41c821eca309cc055ff78d108d53ebad7d2e44812a5e778f',3),(18,'yuqi',NULL,NULL,NULL,0,'a7ed865277bb8248ce4c6a4ce0b889c10f4ea673a9bdf33805aadc7027b4fc9b','5472072e8c12a3653a6a710f1c04090aac8d531159ba02670b4f2074a89333e0',3),(19,'chris',NULL,NULL,NULL,0,'da8b6f3e560b35789c1ae3564408305b48499ace5078dadf6a4c14609e40c97c','742906f0febfe1ea5a2f78a1a253cc2524315b7857aa600e905efd7a9a62ab36',3),(20,'dustin',NULL,NULL,NULL,0,'a222c43e1dd7e457c963167aaebc7d02e1356d9f26ae222840c08d5b88c4ae4e','2cb49bf166ebedb6811138e7da6cf51348eeb4c04c65e5909d9810646173c17a',3),(21,'admin',NULL,NULL,NULL,1,'e826fcc003ef1fcf315da9dabb01c53b69adcf230135276f3ccefde8e143d271','f1c5345184408b22c5f851ce33ea6ab8dcdd48581cf5f2db89c5a6ae948492ac',1),(22,'george',NULL,NULL,NULL,1,'06b37b9d390f22aafe9a1719dea55bfb15ca9f4ea23cf43e9179072d8e0e6002','07d82fcc4d9337241be73f64bc9642c390a02710de7bf74d673186746ab60948',1);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vm_types`
--

DROP TABLE IF EXISTS `vm_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vm_types` (
  `vm_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `vm_type_name` varchar(32) NOT NULL,
  `vm_type_static` int(11) NOT NULL,
  PRIMARY KEY (`vm_type_id`),
  UNIQUE KEY `vm_type_name` (`vm_type_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vm_types`
--

LOCK TABLES `vm_types` WRITE;
/*!40000 ALTER TABLE `vm_types` DISABLE KEYS */;
INSERT INTO `vm_types` VALUES (1,'client',0),(2,'attacker',0),(3,'usrp',1);
/*!40000 ALTER TABLE `vm_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vms`
--

DROP TABLE IF EXISTS `vms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vms` (
  `vm_name` varchar(32) NOT NULL,
  `vm_type` int(11) NOT NULL,
  `vm_state` varchar(32) NOT NULL,
  `vm_owner` int(11) NOT NULL,
  `vm_expires` datetime DEFAULT NULL,
  `vm_port` int(11) DEFAULT NULL,
  `vm_locked` int(11) DEFAULT NULL,
  `vm_locked_by` varchar(32) DEFAULT NULL,
  `vm_renewed` int(11) DEFAULT NULL,
  UNIQUE KEY `vm_name` (`vm_name`),
  UNIQUE KEY `vm_port` (`vm_port`),
  UNIQUE KEY `vm_port_2` (`vm_port`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vms`
--

LOCK TABLES `vms` WRITE;
/*!40000 ALTER TABLE `vms` DISABLE KEYS */;
INSERT INTO `vms` VALUES ('admin_attacker',2,'not_deployed',21,NULL,NULL,NULL,NULL,NULL),('admin_client',1,'not_deployed',21,NULL,NULL,NULL,NULL,NULL),('chris_attacker',2,'not_deployed',19,NULL,NULL,NULL,NULL,NULL),('chris_client',1,'not_deployed',19,NULL,NULL,NULL,NULL,NULL),('dustin_attacker',2,'not_deployed',20,NULL,NULL,NULL,NULL,NULL),('dustin_client',1,'not_deployed',20,NULL,NULL,NULL,NULL,NULL),('frank_attacker',2,'not_deployed',16,NULL,NULL,NULL,NULL,NULL),('frank_client',1,'not_deployed',16,NULL,NULL,NULL,NULL,NULL),('george_attacker',2,'not_deployed',22,NULL,NULL,NULL,NULL,NULL),('george_client',1,'not_deployed',22,NULL,NULL,NULL,NULL,NULL),('matt_attacker',2,'not_deployed',15,NULL,NULL,NULL,NULL,NULL),('matt_client',1,'not_deployed',15,NULL,NULL,NULL,NULL,NULL),('tahsin_attacker',2,'not_deployed',17,NULL,5914,NULL,NULL,NULL),('tahsin_client',1,'not_deployed',17,NULL,5913,NULL,NULL,NULL),('yuqi_attacker',2,'not_deployed',18,NULL,NULL,NULL,NULL,NULL),('yuqi_client',1,'not_deployed',18,NULL,5912,NULL,NULL,NULL);
/*!40000 ALTER TABLE `vms` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-12-12 23:53:49
