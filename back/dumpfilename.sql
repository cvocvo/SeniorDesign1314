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
  `vm_type_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `class_vm_types`
--

LOCK TABLES `class_vm_types` WRITE;
/*!40000 ALTER TABLE `class_vm_types` DISABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `classes`
--

LOCK TABLES `classes` WRITE;
/*!40000 ALTER TABLE `classes` DISABLE KEYS */;
INSERT INTO `classes` VALUES (2,'cpre537'),(1,'default');
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
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (3,'matt',NULL,NULL,NULL,0,'a50e3d6e4a79e6dc983eebc002c8d03e88053909af89e60e3260a9607c1d0635','7dee7494550330986ebd4c4811c3e478af7c13d6a6acfe204e370420e28de0ee',2),(5,'george',NULL,NULL,NULL,1,'2ef4f3a468de060e66fcc05cb7c5ef3503e8a03cf218c8154036494fe31d4b7d','fc5a252900aa39dc5dadcf68e314d85ee259a487ade350c16d5a5c0f2b974f2c',1),(11,'frank',NULL,NULL,NULL,0,'adddd92701a21c441fb3ef37894fc9203bfdc32fbe79998c054ef37f81c6a8c1','e59cbec84c09316eb08b28f6cdb0b47a511b409cb105b537d00f8919342c5032',1),(12,'yuqi',NULL,NULL,NULL,0,'f1c4ab3d897cd42b560fccfd5dbd9de463097f6700599933873127480cb999b7','6524c3f36ae1bad708a33763b4bed13930fdedf10a52ff804e71969f1d2de8a9',2),(13,'tahsin',NULL,NULL,NULL,0,'7f07e91ee93dd6fb12e26184937a090bcc8ca419795af97436dadfdb3d2d4bcf','63a7843b4dd7f578eb6f2e6577cc069dc3cd7796c1bfad9416c3b8ffcf540371',2),(14,'chris',NULL,NULL,NULL,0,'a427a72436675fa67cf9e4e44e9ad39554dda1c72630d9430aeb8df054b474b0','dd54b04d27b9c7b5585332875ff5eb4236024bf228ed2cad69256524cb0c97de',1);
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
  PRIMARY KEY (`vm_type_id`),
  UNIQUE KEY `vm_type_name` (`vm_type_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vm_types`
--

LOCK TABLES `vm_types` WRITE;
/*!40000 ALTER TABLE `vm_types` DISABLE KEYS */;
INSERT INTO `vm_types` VALUES (2,'attacker'),(1,'client'),(3,'usrp');
/*!40000 ALTER TABLE `vm_types` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-12-11  0:17:00
