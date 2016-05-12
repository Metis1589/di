-- MySQL dump 10.13  Distrib 5.6.22, for Win64 (x86_64)
--
-- Host: server    Database: dinein_new
-- ------------------------------------------------------
-- Server version	5.6.10

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
-- Table structure for table `address`
--

DROP TABLE IF EXISTS `address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `address` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `address_type` enum('Billing','Delivery') NOT NULL DEFAULT 'Delivery',
  `address1` varchar(50) NOT NULL,
  `address2` varchar(50) DEFAULT NULL,
  `address3` varchar(50) DEFAULT NULL,
  `instructions` varchar(250) DEFAULT NULL,
  `latitude` decimal(18,15) DEFAULT NULL,
  `longitude` decimal(18,15) DEFAULT NULL,
  `address_base_id` int(11) unsigned NOT NULL,
  `city_id` int(11) unsigned NOT NULL,
  `postcode_id` int(11) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_address_address_base1_idx` (`address_base_id`),
  KEY `fk_address_city1_idx` (`city_id`),
  KEY `fk_address_postcode1_idx` (`postcode_id`),
  CONSTRAINT `fk_address_address_base1` FOREIGN KEY (`address_base_id`) REFERENCES `address_base` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_address_city1` FOREIGN KEY (`city_id`) REFERENCES `city` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_address_postcode1` FOREIGN KEY (`postcode_id`) REFERENCES `postcode` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `address`
--

LOCK TABLES `address` WRITE;
/*!40000 ALTER TABLE `address` DISABLE KEYS */;
/*!40000 ALTER TABLE `address` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `address_base`
--

DROP TABLE IF EXISTS `address_base`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `address_base` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `delivery_delay_time` time NOT NULL,
  `max_delivery` float DEFAULT NULL,
  `client_id` smallint(5) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_address_base_client1_idx` (`client_id`),
  CONSTRAINT `fk_address_base_client1` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `address_base`
--

LOCK TABLES `address_base` WRITE;
/*!40000 ALTER TABLE `address_base` DISABLE KEYS */;
/*!40000 ALTER TABLE `address_base` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `affiliate`
--

DROP TABLE IF EXISTS `affiliate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `affiliate` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `code` varchar(150) NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `affiliate`
--

LOCK TABLES `affiliate` WRITE;
/*!40000 ALTER TABLE `affiliate` DISABLE KEYS */;
/*!40000 ALTER TABLE `affiliate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `area_address`
--

DROP TABLE IF EXISTS `area_address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `area_address` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name_key` varchar(50) NOT NULL,
  `native_name` varchar(50) NOT NULL,
  `country_id` smallint(5) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_area_address_country1_idx` (`country_id`),
  CONSTRAINT `fk_area_address_country1` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `area_address`
--

LOCK TABLES `area_address` WRITE;
/*!40000 ALTER TABLE `area_address` DISABLE KEYS */;
/*!40000 ALTER TABLE `area_address` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `best_for_item`
--

DROP TABLE IF EXISTS `best_for_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `best_for_item` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name_key` varchar(255) DEFAULT NULL,
  `client_id` smallint(5) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_best_for_item_client1_idx` (`client_id`),
  CONSTRAINT `fk_best_for_item_client1` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `best_for_item`
--

LOCK TABLES `best_for_item` WRITE;
/*!40000 ALTER TABLE `best_for_item` DISABLE KEYS */;
/*!40000 ALTER TABLE `best_for_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `city`
--

DROP TABLE IF EXISTS `city`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `city` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name_key` varchar(50) NOT NULL,
  `native_name` varchar(50) NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `city`
--

LOCK TABLES `city` WRITE;
/*!40000 ALTER TABLE `city` DISABLE KEYS */;
/*!40000 ALTER TABLE `city` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client`
--

DROP TABLE IF EXISTS `client`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `description` varchar(500) DEFAULT NULL,
  `url` varchar(100) DEFAULT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client`
--

LOCK TABLES `client` WRITE;
/*!40000 ALTER TABLE `client` DISABLE KEYS */;
/*!40000 ALTER TABLE `client` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_contact`
--

DROP TABLE IF EXISTS `client_contact`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client_contact` (
  `client_id` smallint(5) unsigned NOT NULL,
  `contact_id` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`client_id`,`contact_id`),
  KEY `fk_client_has_contact_contact1_idx` (`contact_id`),
  KEY `fk_client_has_contact_client1_idx` (`client_id`),
  CONSTRAINT `fk_client_has_contact_client1` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_client_has_contact_contact1` FOREIGN KEY (`contact_id`) REFERENCES `contact` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_contact`
--

LOCK TABLES `client_contact` WRITE;
/*!40000 ALTER TABLE `client_contact` DISABLE KEYS */;
/*!40000 ALTER TABLE `client_contact` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `company`
--

DROP TABLE IF EXISTS `company`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `company` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `code` varchar(255) NOT NULL,
  `payment_frequency` varchar(10) NOT NULL,
  `payment_frequency_amount` float NOT NULL,
  `sales_fee` float NOT NULL DEFAULT '0',
  `is_vat_exclusive` bit(1) NOT NULL DEFAULT b'1',
  `daily_limit` float NOT NULL,
  `weekly_limit` float NOT NULL,
  `monthly_limit` float NOT NULL,
  `limit_type` enum('Soft','Hard') NOT NULL,
  `vat_number` varchar(25) DEFAULT NULL,
  `client_id` smallint(5) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_company_client1_idx` (`client_id`),
  CONSTRAINT `fk_company_client1` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `company`
--

LOCK TABLES `company` WRITE;
/*!40000 ALTER TABLE `company` DISABLE KEYS */;
/*!40000 ALTER TABLE `company` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `company_address`
--

DROP TABLE IF EXISTS `company_address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `company_address` (
  `address_id` bigint(20) unsigned NOT NULL,
  `company_id` int(11) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`address_id`,`company_id`),
  KEY `fk_address_has_company_company1_idx` (`company_id`),
  KEY `fk_address_has_company_address1_idx` (`address_id`),
  CONSTRAINT `fk_address_has_company_address1` FOREIGN KEY (`address_id`) REFERENCES `address` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_address_has_company_company1` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `company_address`
--

LOCK TABLES `company_address` WRITE;
/*!40000 ALTER TABLE `company_address` DISABLE KEYS */;
/*!40000 ALTER TABLE `company_address` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `company_contact`
--

DROP TABLE IF EXISTS `company_contact`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `company_contact` (
  `company_id` int(11) unsigned NOT NULL,
  `contact_id` smallint(5) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`company_id`,`contact_id`),
  KEY `fk_company_has_contact_contact1_idx` (`contact_id`),
  KEY `fk_company_has_contact_company1_idx` (`company_id`),
  CONSTRAINT `fk_company_has_contact_company1` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_company_has_contact_contact1` FOREIGN KEY (`contact_id`) REFERENCES `contact` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `company_contact`
--

LOCK TABLES `company_contact` WRITE;
/*!40000 ALTER TABLE `company_contact` DISABLE KEYS */;
/*!40000 ALTER TABLE `company_contact` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `company_domain`
--

DROP TABLE IF EXISTS `company_domain`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `company_domain` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `domain` varchar(50) NOT NULL,
  `company_id` int(11) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE domain` (`domain`,`company_id`),
  KEY `fk_company_domain_company1_idx` (`company_id`),
  CONSTRAINT `fk_company_domain_company1` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `company_domain`
--

LOCK TABLES `company_domain` WRITE;
/*!40000 ALTER TABLE `company_domain` DISABLE KEYS */;
/*!40000 ALTER TABLE `company_domain` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `company_expense_type`
--

DROP TABLE IF EXISTS `company_expense_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `company_expense_type` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `limi_order` float NOT NULL,
  `limit_type` enum('Soft','Hard') NOT NULL,
  `soft_limit_max` float NOT NULL,
  `expense_type_id` int(11) unsigned NOT NULL,
  `company_id` int(11) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_company_expense_type_expense_type1_idx` (`expense_type_id`),
  KEY `fk_company_expense_type_company1_idx` (`company_id`),
  CONSTRAINT `fk_company_expense_type_expense_type1` FOREIGN KEY (`expense_type_id`) REFERENCES `expense_type` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_company_expense_type_company1` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `company_expense_type`
--

LOCK TABLES `company_expense_type` WRITE;
/*!40000 ALTER TABLE `company_expense_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `company_expense_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `company_phone`
--

DROP TABLE IF EXISTS `company_phone`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `company_phone` (
  `phone_id` int(11) unsigned NOT NULL,
  `company_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`phone_id`,`company_id`),
  KEY `fk_phone_has_company_company1_idx` (`company_id`),
  KEY `fk_phone_has_company_phone1_idx` (`phone_id`),
  CONSTRAINT `fk_phone_has_company_phone1` FOREIGN KEY (`phone_id`) REFERENCES `phone` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_phone_has_company_company1` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `company_phone`
--

LOCK TABLES `company_phone` WRITE;
/*!40000 ALTER TABLE `company_phone` DISABLE KEYS */;
/*!40000 ALTER TABLE `company_phone` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `company_schedule`
--

DROP TABLE IF EXISTS `company_schedule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `company_schedule` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `min` float NOT NULL,
  `schedule_id` smallint(5) unsigned NOT NULL,
  `company_id` int(11) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_company_schedule_schedule1_idx` (`schedule_id`),
  KEY `fk_company_schedule_company1_idx` (`company_id`),
  CONSTRAINT `fk_company_schedule_schedule1` FOREIGN KEY (`schedule_id`) REFERENCES `schedule` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_company_schedule_company1` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `company_schedule`
--

LOCK TABLES `company_schedule` WRITE;
/*!40000 ALTER TABLE `company_schedule` DISABLE KEYS */;
/*!40000 ALTER TABLE `company_schedule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `company_user_group`
--

DROP TABLE IF EXISTS `company_user_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `company_user_group` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `company_id` int(11) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_company_user_group_company1_idx` (`company_id`),
  CONSTRAINT `fk_company_user_group_company1` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `company_user_group`
--

LOCK TABLES `company_user_group` WRITE;
/*!40000 ALTER TABLE `company_user_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `company_user_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `company_user_group_user`
--

DROP TABLE IF EXISTS `company_user_group_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `company_user_group_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `company_user_group_id` smallint(5) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_company_user_group_user_users1_idx` (`user_id`),
  KEY `fk_company_user_group_user_company_user_group1_idx` (`company_user_group_id`),
  CONSTRAINT `fk_company_user_group_user_users1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_company_user_group_user_company_user_group1` FOREIGN KEY (`company_user_group_id`) REFERENCES `company_user_group` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `company_user_group_user`
--

LOCK TABLES `company_user_group_user` WRITE;
/*!40000 ALTER TABLE `company_user_group_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `company_user_group_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact`
--

DROP TABLE IF EXISTS `contact`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contact` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(150) DEFAULT NULL,
  `is_opt_in` bit(1) DEFAULT b'0',
  `phone_id` int(11) unsigned DEFAULT NULL,
  `address_id` bigint(20) unsigned DEFAULT NULL,
  `person_id` int(11) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_contact_person1_idx` (`person_id`),
  KEY `fk_contact_phone1_idx` (`phone_id`),
  KEY `fk_contact_address1_idx` (`address_id`),
  CONSTRAINT `fk_contact_person1` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_contact_phone1` FOREIGN KEY (`phone_id`) REFERENCES `phone` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_contact_address1` FOREIGN KEY (`address_id`) REFERENCES `address` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact`
--

LOCK TABLES `contact` WRITE;
/*!40000 ALTER TABLE `contact` DISABLE KEYS */;
/*!40000 ALTER TABLE `contact` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `corporate_order`
--

DROP TABLE IF EXISTS `corporate_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `corporate_order` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `limit_type` enum('Soft','Hard') NOT NULL,
  `limit_max` float NOT NULL,
  `allocation_limit` float NOT NULL,
  `allocation` float NOT NULL,
  `total_allocated` float NOT NULL,
  `order_id` smallint(5) unsigned NOT NULL,
  `company_expense_type_id` smallint(5) unsigned NOT NULL,
  `project_id` int(11) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_corporate_order_order1_idx` (`order_id`),
  KEY `fk_corporate_order_company_expense_type1_idx` (`company_expense_type_id`),
  KEY `fk_corporate_order_project1_idx` (`project_id`),
  CONSTRAINT `fk_corporate_order_order1` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_corporate_order_company_expense_type1` FOREIGN KEY (`company_expense_type_id`) REFERENCES `company_expense_type` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_corporate_order_project1` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `corporate_order`
--

LOCK TABLES `corporate_order` WRITE;
/*!40000 ALTER TABLE `corporate_order` DISABLE KEYS */;
/*!40000 ALTER TABLE `corporate_order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `country`
--

DROP TABLE IF EXISTS `country`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `country` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name_key` varchar(50) NOT NULL,
  `native_name` varchar(50) NOT NULL,
  `iso_code` char(2) NOT NULL COMMENT 'ISO 3166-1-alpha-2 code',
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_key_UNIQUE` (`name_key`),
  UNIQUE KEY `native_name_UNIQUE` (`native_name`),
  UNIQUE KEY `iso_code_UNIQUE` (`iso_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `country`
--

LOCK TABLES `country` WRITE;
/*!40000 ALTER TABLE `country` DISABLE KEYS */;
/*!40000 ALTER TABLE `country` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cuisine`
--

DROP TABLE IF EXISTS `cuisine`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cuisine` (
  `id` int(11) unsigned NOT NULL,
  `client_id` smallint(5) unsigned NOT NULL,
  `name_key` varchar(150) NOT NULL,
  `description_key` varchar(150) DEFAULT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_cuisine_client1_idx` (`client_id`),
  CONSTRAINT `fk_cuisine_client1` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cuisine`
--

LOCK TABLES `cuisine` WRITE;
/*!40000 ALTER TABLE `cuisine` DISABLE KEYS */;
/*!40000 ALTER TABLE `cuisine` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `currency`
--

DROP TABLE IF EXISTS `currency`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `currency` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `code` varchar(150) NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE key` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `currency`
--

LOCK TABLES `currency` WRITE;
/*!40000 ALTER TABLE `currency` DISABLE KEYS */;
/*!40000 ALTER TABLE `currency` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `default_delivery_charges`
--

DROP TABLE IF EXISTS `default_delivery_charges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `default_delivery_charges` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mile` float DEFAULT NULL,
  `charge` float DEFAULT NULL,
  `client_id` smallint(5) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_default_delivery_charges_client1_idx` (`client_id`),
  CONSTRAINT `fk_default_delivery_charges_client1` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `default_delivery_charges`
--

LOCK TABLES `default_delivery_charges` WRITE;
/*!40000 ALTER TABLE `default_delivery_charges` DISABLE KEYS */;
/*!40000 ALTER TABLE `default_delivery_charges` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `driver`
--

DROP TABLE IF EXISTS `driver`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `driver` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `dispatch_id` varchar(150) NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE key` (`dispatch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `driver`
--

LOCK TABLES `driver` WRITE;
/*!40000 ALTER TABLE `driver` DISABLE KEYS */;
/*!40000 ALTER TABLE `driver` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `expense_type`
--

DROP TABLE IF EXISTS `expense_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `expense_type` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `client_id` smallint(5) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_expense_type_client1_idx` (`client_id`),
  CONSTRAINT `fk_expense_type_client1` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `expense_type`
--

LOCK TABLES `expense_type` WRITE;
/*!40000 ALTER TABLE `expense_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `expense_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `expense_type_schedule`
--

DROP TABLE IF EXISTS `expense_type_schedule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `expense_type_schedule` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `day` enum('Sunday','Monday','Thuesday','Wednesday','Thursday','Friday','Saturday') NOT NULL,
  `schedule_id` smallint(5) unsigned NOT NULL,
  `expense_type_id` int(11) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE schedule` (`schedule_id`,`expense_type_id`,`day`),
  KEY `fk_expense_type_schedule_schedule1_idx` (`schedule_id`),
  KEY `fk_expense_type_schedule_expense_type1_idx` (`expense_type_id`),
  CONSTRAINT `fk_expense_type_schedule_schedule1` FOREIGN KEY (`schedule_id`) REFERENCES `schedule` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_expense_type_schedule_expense_type1` FOREIGN KEY (`expense_type_id`) REFERENCES `expense_type` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `expense_type_schedule`
--

LOCK TABLES `expense_type_schedule` WRITE;
/*!40000 ALTER TABLE `expense_type_schedule` DISABLE KEYS */;
/*!40000 ALTER TABLE `expense_type_schedule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `feedback`
--

DROP TABLE IF EXISTS `feedback`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `feedback` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `text` varchar(500) NOT NULL,
  `restaurant_id` smallint(5) unsigned NOT NULL,
  `feedback_type_id` int(11) unsigned NOT NULL,
  `order_id` smallint(5) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_review_restaurant2_idx` (`restaurant_id`),
  KEY `fk_review_order2_idx` (`order_id`),
  KEY `fk_feedback_feedback_type1_idx` (`feedback_type_id`),
  CONSTRAINT `fk_review_restaurant2` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_review_order2` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_feedback_feedback_type1` FOREIGN KEY (`feedback_type_id`) REFERENCES `feedback_type` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `feedback`
--

LOCK TABLES `feedback` WRITE;
/*!40000 ALTER TABLE `feedback` DISABLE KEYS */;
/*!40000 ALTER TABLE `feedback` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `feedback_type`
--

DROP TABLE IF EXISTS `feedback_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `feedback_type` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `client_id` smallint(5) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_feedback_type_client1_idx` (`client_id`),
  CONSTRAINT `fk_feedback_type_client1` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `feedback_type`
--

LOCK TABLES `feedback_type` WRITE;
/*!40000 ALTER TABLE `feedback_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `feedback_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `group_order_contact`
--

DROP TABLE IF EXISTS `group_order_contact`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `group_order_contact` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` smallint(5) unsigned NOT NULL,
  `contact_id` smallint(5) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_group_order_contact_order1_idx` (`order_id`),
  KEY `fk_group_order_contact_contact1_idx` (`contact_id`),
  CONSTRAINT `fk_group_order_contact_order1` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_group_order_contact_contact1` FOREIGN KEY (`contact_id`) REFERENCES `contact` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `group_order_contact`
--

LOCK TABLES `group_order_contact` WRITE;
/*!40000 ALTER TABLE `group_order_contact` DISABLE KEYS */;
/*!40000 ALTER TABLE `group_order_contact` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `label`
--

DROP TABLE IF EXISTS `label`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `label` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(190) NOT NULL,
  `description` varchar(250) NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE key` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `label`
--

LOCK TABLES `label` WRITE;
/*!40000 ALTER TABLE `label` DISABLE KEYS */;
/*!40000 ALTER TABLE `label` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `label_language`
--

DROP TABLE IF EXISTS `label_language`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `label_language` (
  `language_id` int(11) unsigned NOT NULL,
  `label_id` bigint(20) unsigned NOT NULL,
  `value` varchar(500) NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`language_id`,`label_id`),
  KEY `fk_language_has_label_label1_idx` (`label_id`),
  KEY `fk_language_has_label_language1_idx` (`language_id`),
  CONSTRAINT `fk_language_has_label_language1` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_language_has_label_label1` FOREIGN KEY (`label_id`) REFERENCES `label` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `label_language`
--

LOCK TABLES `label_language` WRITE;
/*!40000 ALTER TABLE `label_language` DISABLE KEYS */;
/*!40000 ALTER TABLE `label_language` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `language`
--

DROP TABLE IF EXISTS `language`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `language` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `iso_code` char(2) NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `language`
--

LOCK TABLES `language` WRITE;
/*!40000 ALTER TABLE `language` DISABLE KEYS */;
/*!40000 ALTER TABLE `language` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu`
--

DROP TABLE IF EXISTS `menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name_key` varchar(250) NOT NULL,
  `restaurant_id` smallint(5) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_menu_restaurant1_idx` (`restaurant_id`),
  CONSTRAINT `fk_menu_restaurant1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu`
--

LOCK TABLES `menu` WRITE;
/*!40000 ALTER TABLE `menu` DISABLE KEYS */;
/*!40000 ALTER TABLE `menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu_allergy`
--

DROP TABLE IF EXISTS `menu_allergy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu_allergy` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name_key` varchar(250) DEFAULT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu_allergy`
--

LOCK TABLES `menu_allergy` WRITE;
/*!40000 ALTER TABLE `menu_allergy` DISABLE KEYS */;
/*!40000 ALTER TABLE `menu_allergy` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu_bundle`
--

DROP TABLE IF EXISTS `menu_bundle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu_bundle` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name_key` varchar(250) NOT NULL,
  `description_key` varchar(250) DEFAULT NULL,
  `restaurant_price` float DEFAULT NULL,
  `web_price` float NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu_bundle`
--

LOCK TABLES `menu_bundle` WRITE;
/*!40000 ALTER TABLE `menu_bundle` DISABLE KEYS */;
/*!40000 ALTER TABLE `menu_bundle` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu_category`
--

DROP TABLE IF EXISTS `menu_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu_category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name_key` varchar(250) NOT NULL,
  `is_optional` bit(1) NOT NULL DEFAULT b'0',
  `sort_order` int(11) NOT NULL DEFAULT '1',
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu_category`
--

LOCK TABLES `menu_category` WRITE;
/*!40000 ALTER TABLE `menu_category` DISABLE KEYS */;
/*!40000 ALTER TABLE `menu_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu_item`
--

DROP TABLE IF EXISTS `menu_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu_item` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name_key` varchar(250) DEFAULT NULL,
  `restaurant_price` float DEFAULT NULL,
  `web_price` float NOT NULL,
  `description` text,
  `size` varchar(255) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT '1',
  `menu_id` smallint(5) unsigned NOT NULL,
  `nutritional` varchar(500) DEFAULT NULL,
  `menu_allergy_id` smallint(5) unsigned NOT NULL,
  `vat_id` int(11) unsigned NOT NULL,
  `menu_option_id` bigint(20) unsigned DEFAULT NULL,
  `is_imported` bit(1) NOT NULL DEFAULT b'0',
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_menu_item_menu1_idx` (`menu_id`),
  KEY `fk_menu_item_menu_allergy1_idx` (`menu_allergy_id`),
  KEY `fk_menu_item_vat1_idx` (`vat_id`),
  KEY `fk_menu_item_menu_option1_idx` (`menu_option_id`),
  CONSTRAINT `fk_menu_item_menu1` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_menu_item_menu_allergy1` FOREIGN KEY (`menu_allergy_id`) REFERENCES `menu_allergy` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_menu_item_vat1` FOREIGN KEY (`vat_id`) REFERENCES `vat` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_menu_item_menu_option1` FOREIGN KEY (`menu_option_id`) REFERENCES `menu_option` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu_item`
--

LOCK TABLES `menu_item` WRITE;
/*!40000 ALTER TABLE `menu_item` DISABLE KEYS */;
/*!40000 ALTER TABLE `menu_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu_item_like`
--

DROP TABLE IF EXISTS `menu_item_like`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu_item_like` (
  `menu_item_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`menu_item_id`,`user_id`),
  KEY `fk_menu_item_has_user_user1_idx` (`user_id`),
  KEY `fk_menu_item_has_user_menu_item1_idx` (`menu_item_id`),
  CONSTRAINT `fk_menu_item_has_user_menu_item1` FOREIGN KEY (`menu_item_id`) REFERENCES `menu_item` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_menu_item_has_user_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu_item_like`
--

LOCK TABLES `menu_item_like` WRITE;
/*!40000 ALTER TABLE `menu_item_like` DISABLE KEYS */;
/*!40000 ALTER TABLE `menu_item_like` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu_item_menu_bundle`
--

DROP TABLE IF EXISTS `menu_item_menu_bundle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu_item_menu_bundle` (
  `menu_item_id` bigint(20) unsigned NOT NULL,
  `menu_bundle_id` int(11) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`menu_item_id`,`menu_bundle_id`),
  KEY `fk_menu_item_has_menu_bundle_menu_bundle_idx` (`menu_bundle_id`),
  KEY `fk_menu_item_has_menu_bundle_menu_item_idx` (`menu_item_id`),
  CONSTRAINT `fk_menu_item_has_menu_bundle_menu_item` FOREIGN KEY (`menu_item_id`) REFERENCES `menu_item` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_menu_item_has_menu_bundle_menu_bundle` FOREIGN KEY (`menu_bundle_id`) REFERENCES `menu_bundle` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu_item_menu_bundle`
--

LOCK TABLES `menu_item_menu_bundle` WRITE;
/*!40000 ALTER TABLE `menu_item_menu_bundle` DISABLE KEYS */;
/*!40000 ALTER TABLE `menu_item_menu_bundle` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu_item_menu_category`
--

DROP TABLE IF EXISTS `menu_item_menu_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu_item_menu_category` (
  `menu_item_id` bigint(20) unsigned NOT NULL,
  `menu_category_id` int(11) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`menu_item_id`,`menu_category_id`),
  KEY `fk_menu_item_has_menu_category_menu_category1_idx` (`menu_category_id`),
  KEY `fk_menu_item_has_menu_category_menu_item1_idx` (`menu_item_id`),
  CONSTRAINT `fk_menu_item_has_menu_category_menu_item1` FOREIGN KEY (`menu_item_id`) REFERENCES `menu_item` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_menu_item_has_menu_category_menu_category1` FOREIGN KEY (`menu_category_id`) REFERENCES `menu_category` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu_item_menu_category`
--

LOCK TABLES `menu_item_menu_category` WRITE;
/*!40000 ALTER TABLE `menu_item_menu_category` DISABLE KEYS */;
/*!40000 ALTER TABLE `menu_item_menu_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu_item_menu_type`
--

DROP TABLE IF EXISTS `menu_item_menu_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu_item_menu_type` (
  `menu_item_id` bigint(20) unsigned NOT NULL,
  `menu_type_id` int(11) unsigned NOT NULL,
  `restaurant_price` float NOT NULL,
  `web_price` float NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`menu_item_id`,`menu_type_id`),
  KEY `fk_menu_item_has_menu_type_menu_type1_idx` (`menu_type_id`),
  KEY `fk_menu_item_has_menu_type_menu_item1_idx` (`menu_item_id`),
  CONSTRAINT `fk_menu_item_has_menu_type_menu_item1` FOREIGN KEY (`menu_item_id`) REFERENCES `menu_item` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_menu_item_has_menu_type_menu_type1` FOREIGN KEY (`menu_type_id`) REFERENCES `menu_type` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu_item_menu_type`
--

LOCK TABLES `menu_item_menu_type` WRITE;
/*!40000 ALTER TABLE `menu_item_menu_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `menu_item_menu_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu_item_similar`
--

DROP TABLE IF EXISTS `menu_item_similar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu_item_similar` (
  `menu_item_id` bigint(20) unsigned NOT NULL,
  `menu_item_similar_id` bigint(20) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`menu_item_id`,`menu_item_similar_id`),
  KEY `fk_menu_item_has_menu_item_menu_item2_idx` (`menu_item_similar_id`),
  KEY `fk_menu_item_has_menu_item_menu_item1_idx` (`menu_item_id`),
  CONSTRAINT `fk_menu_item_has_menu_item_menu_item1` FOREIGN KEY (`menu_item_id`) REFERENCES `menu_item` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_menu_item_has_menu_item_menu_item2` FOREIGN KEY (`menu_item_similar_id`) REFERENCES `menu_item` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu_item_similar`
--

LOCK TABLES `menu_item_similar` WRITE;
/*!40000 ALTER TABLE `menu_item_similar` DISABLE KEYS */;
/*!40000 ALTER TABLE `menu_item_similar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu_option`
--

DROP TABLE IF EXISTS `menu_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu_option` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name_key` varchar(250) DEFAULT NULL,
  `web_price` float DEFAULT NULL,
  `restaurant_price` float DEFAULT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu_option`
--

LOCK TABLES `menu_option` WRITE;
/*!40000 ALTER TABLE `menu_option` DISABLE KEYS */;
/*!40000 ALTER TABLE `menu_option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu_option_category`
--

DROP TABLE IF EXISTS `menu_option_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu_option_category` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name_key` varchar(250) NOT NULL,
  `web_price` float DEFAULT NULL,
  `restaurant_price` float DEFAULT NULL,
  `items_limit` smallint(5) DEFAULT NULL,
  `description_key` varchar(250) DEFAULT NULL,
  `view_type` enum('Dropdown','RadioButton') NOT NULL DEFAULT 'Dropdown',
  `price_calc_type` enum('Addition','Fixed') NOT NULL DEFAULT 'Addition',
  `menu_option_id` bigint(20) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_menu_option_category_menu_option1_idx` (`menu_option_id`),
  CONSTRAINT `fk_menu_option_category_menu_option1` FOREIGN KEY (`menu_option_id`) REFERENCES `menu_option` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu_option_category`
--

LOCK TABLES `menu_option_category` WRITE;
/*!40000 ALTER TABLE `menu_option_category` DISABLE KEYS */;
/*!40000 ALTER TABLE `menu_option_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu_option_category_item`
--

DROP TABLE IF EXISTS `menu_option_category_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu_option_category_item` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `menu_option_item_id` bigint(20) unsigned NOT NULL,
  `menu_option_category_id` bigint(20) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_menu_option_category_item_menu_option_item1_idx` (`menu_option_item_id`),
  KEY `fk_menu_option_category_item_menu_option_category1_idx` (`menu_option_category_id`),
  CONSTRAINT `fk_menu_option_category_item_menu_option_item1` FOREIGN KEY (`menu_option_item_id`) REFERENCES `menu_option_item` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_menu_option_category_item_menu_option_category1` FOREIGN KEY (`menu_option_category_id`) REFERENCES `menu_option_category` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu_option_category_item`
--

LOCK TABLES `menu_option_category_item` WRITE;
/*!40000 ALTER TABLE `menu_option_category_item` DISABLE KEYS */;
/*!40000 ALTER TABLE `menu_option_category_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu_option_item`
--

DROP TABLE IF EXISTS `menu_option_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu_option_item` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name_key` varchar(250) NOT NULL,
  `description_key` varchar(250) DEFAULT NULL,
  `menu_category_id` int(11) unsigned NOT NULL,
  `web_price` float DEFAULT NULL,
  `restaurant_price` float DEFAULT NULL,
  `order` smallint(5) DEFAULT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_menu_option_item_menu_category_idx` (`menu_category_id`),
  CONSTRAINT `fk_menu_option_item_menu_category` FOREIGN KEY (`menu_category_id`) REFERENCES `menu_category` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu_option_item`
--

LOCK TABLES `menu_option_item` WRITE;
/*!40000 ALTER TABLE `menu_option_item` DISABLE KEYS */;
/*!40000 ALTER TABLE `menu_option_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu_type`
--

DROP TABLE IF EXISTS `menu_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu_type` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name_key` varchar(250) NOT NULL,
  `is_option` bit(1) NOT NULL DEFAULT b'1',
  `client_id` smallint(5) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_menu_type_client1_idx` (`client_id`),
  CONSTRAINT `fk_menu_type_client1` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu_type`
--

LOCK TABLES `menu_type` WRITE;
/*!40000 ALTER TABLE `menu_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `menu_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `navigation`
--

DROP TABLE IF EXISTS `navigation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `navigation` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `value` varchar(30) NOT NULL,
  `position` enum('Footer','Header') NOT NULL DEFAULT 'Header',
  `order` tinyint(3) NOT NULL DEFAULT '0',
  `open_from` datetime NOT NULL,
  `open_to` datetime NOT NULL,
  `client_id` smallint(5) unsigned NOT NULL,
  `language_id` int(11) unsigned NOT NULL,
  `parent_id` int(11) unsigned NOT NULL,
  `page_id` int(11) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_navigation_client1_idx` (`client_id`),
  KEY `fk_navigation_language1_idx` (`language_id`),
  KEY `fk_navigation_navigation1_idx` (`parent_id`),
  KEY `fk_navigation_page1_idx` (`page_id`),
  CONSTRAINT `fk_navigation_client1` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_navigation_language1` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_navigation_navigation1` FOREIGN KEY (`parent_id`) REFERENCES `navigation` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_navigation_page1` FOREIGN KEY (`page_id`) REFERENCES `page` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `navigation`
--

LOCK TABLES `navigation` WRITE;
/*!40000 ALTER TABLE `navigation` DISABLE KEYS */;
/*!40000 ALTER TABLE `navigation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order`
--

DROP TABLE IF EXISTS `order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `order_number` varchar(50) NOT NULL,
  `delivery_type` enum('Now','Later') DEFAULT NULL,
  `later_date` timestamp NULL DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `status` enum('InProgress','FoodRecieved','FoodPrepare','Cancel','EstDelivery','FoodReady','TransToReset','DeliveryAccept','ReadyBy','ArrivedAtCustomer','DriverAssigned') NOT NULL DEFAULT 'InProgress',
  `previous_status` enum('FoodRecieved','FoodPrepare','Cancel','EstDelivery','FoodReady','TransToReset','DeliveryAccept','ReadyBy','ArrivedAtCustomer','DriverAssigned') DEFAULT NULL,
  `is_amend` bit(1) NOT NULL DEFAULT b'0',
  `is_term_cond` bit(1) NOT NULL DEFAULT b'0',
  `is_term_cond_web_use` bit(1) NOT NULL DEFAULT b'0',
  `is_term_cond_acc_pol` bit(1) NOT NULL DEFAULT b'0',
  `reataurant_notes` varchar(500) DEFAULT NULL,
  `pickup_step` enum('1','2') DEFAULT NULL,
  `drop_off_step` enum('1','2') DEFAULT NULL,
  `size` varchar(50) DEFAULT NULL,
  `is_in_dispatch` bit(1) NOT NULL DEFAULT b'0',
  `delivery_charges` float DEFAULT NULL,
  `driver_charges` float DEFAULT NULL,
  `voucher_id` int(11) unsigned NOT NULL,
  `contact_id` smallint(5) unsigned NOT NULL,
  `order_payment_id` int(11) unsigned NOT NULL,
  `affiliate_id` int(11) unsigned NOT NULL,
  `estimated_time` time DEFAULT NULL,
  `driver_id` bigint(20) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE number` (`order_number`),
  KEY `fk_order_voucher1_idx` (`voucher_id`),
  KEY `fk_order_contact1_idx` (`contact_id`),
  KEY `fk_order_order_payment1_idx` (`order_payment_id`),
  KEY `fk_order_affeliate1_idx` (`affiliate_id`),
  KEY `fk_order_driver1_idx` (`driver_id`),
  CONSTRAINT `fk_order_voucher1` FOREIGN KEY (`voucher_id`) REFERENCES `voucher` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_order_contact1` FOREIGN KEY (`contact_id`) REFERENCES `contact` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_order_order_payment1` FOREIGN KEY (`order_payment_id`) REFERENCES `order_payment` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_order_affeliate1` FOREIGN KEY (`affiliate_id`) REFERENCES `affiliate` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_order_driver1` FOREIGN KEY (`driver_id`) REFERENCES `driver` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order`
--

LOCK TABLES `order` WRITE;
/*!40000 ALTER TABLE `order` DISABLE KEYS */;
/*!40000 ALTER TABLE `order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_contact`
--

DROP TABLE IF EXISTS `order_contact`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_contact` (
  `order_id` smallint(5) unsigned NOT NULL,
  `contact_id` smallint(5) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`order_id`,`contact_id`),
  KEY `fk_order_has_contact_contact1_idx` (`contact_id`),
  KEY `fk_order_has_contact_order1_idx` (`order_id`),
  CONSTRAINT `fk_order_has_contact_order1` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_order_has_contact_contact1` FOREIGN KEY (`contact_id`) REFERENCES `contact` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_contact`
--

LOCK TABLES `order_contact` WRITE;
/*!40000 ALTER TABLE `order_contact` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_contact` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_history`
--

DROP TABLE IF EXISTS `order_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_history` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `history` text NOT NULL,
  `v_sms` int(11) NOT NULL DEFAULT '0',
  `order_id` smallint(5) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_orderhistory_order1_idx` (`order_id`),
  KEY `fk_orderhistory_user1_idx` (`user_id`),
  CONSTRAINT `fk_orderhistory_order1` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_orderhistory_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_history`
--

LOCK TABLES `order_history` WRITE;
/*!40000 ALTER TABLE `order_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_item`
--

DROP TABLE IF EXISTS `order_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_item` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` smallint(5) unsigned NOT NULL,
  `size` varchar(255) DEFAULT NULL,
  `web_price` float DEFAULT NULL,
  `restaurant_price` float NOT NULL,
  `quantity` int(11) DEFAULT '1',
  `vat_amount` int(11) DEFAULT '15',
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_order_item_order1_idx` (`order_id`),
  CONSTRAINT `fk_order_item_order1` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_item`
--

LOCK TABLES `order_item` WRITE;
/*!40000 ALTER TABLE `order_item` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_item_group_order_contact`
--

DROP TABLE IF EXISTS `order_item_group_order_contact`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_item_group_order_contact` (
  `order_item_id` smallint(5) unsigned NOT NULL,
  `group_order_contact_id` smallint(5) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`order_item_id`,`group_order_contact_id`),
  KEY `fk_order_item_has_group_order_contact_group_order_contact1_idx` (`group_order_contact_id`),
  KEY `fk_order_item_has_group_order_contact_order_item1_idx` (`order_item_id`),
  CONSTRAINT `fk_order_item_has_group_order_contact_order_item1` FOREIGN KEY (`order_item_id`) REFERENCES `order_item` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_order_item_has_group_order_contact_group_order_contact1` FOREIGN KEY (`group_order_contact_id`) REFERENCES `group_order_contact` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_item_group_order_contact`
--

LOCK TABLES `order_item_group_order_contact` WRITE;
/*!40000 ALTER TABLE `order_item_group_order_contact` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_item_group_order_contact` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_ivr`
--

DROP TABLE IF EXISTS `order_ivr`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_ivr` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ivr_type` enum('1','2','3') NOT NULL,
  `order_id` smallint(5) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_order_ivr_order1_idx` (`order_id`),
  CONSTRAINT `fk_order_ivr_order1` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_ivr`
--

LOCK TABLES `order_ivr` WRITE;
/*!40000 ALTER TABLE `order_ivr` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_ivr` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_ivr_history`
--

DROP TABLE IF EXISTS `order_ivr_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_ivr_history` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `call_status` enum('Queued','Completed','NoAnswer','Busy','Failed') NOT NULL,
  `callsid` varchar(255) NOT NULL,
  `phone_number` varchar(255) NOT NULL,
  `duration` float NOT NULL,
  `unit_price` double NOT NULL COMMENT 'price per second',
  `twillo_call_cost` double NOT NULL,
  `twillo_cost_unit` text NOT NULL,
  `order_id` smallint(5) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_order_ivr_history_order1_idx` (`order_id`),
  CONSTRAINT `fk_order_ivr_history_order1` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_ivr_history`
--

LOCK TABLES `order_ivr_history` WRITE;
/*!40000 ALTER TABLE `order_ivr_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_ivr_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_payment`
--

DROP TABLE IF EXISTS `order_payment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_payment` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `credit_card_type` enum('VisaDebit','VisaElectron','VisaCredit','MasterCard','Maestro','Amex') NOT NULL,
  `commision_rate` float DEFAULT NULL,
  `payment_cost` float NOT NULL,
  `payment_cost_type` enum('Fixed','Percent') NOT NULL,
  `customer_cost_type` enum('Fixed','Percent') NOT NULL,
  `customer_cost` float DEFAULT NULL,
  `payment_charge` float DEFAULT NULL,
  `total` float DEFAULT NULL,
  `restaurant_total` float DEFAULT NULL,
  `msd_cost` float DEFAULT NULL,
  `delivary_charge` float DEFAULT NULL,
  `refund_amount` float DEFAULT NULL,
  `restaurant_charge` float DEFAULT NULL,
  `restaurant_refund` float DEFAULT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_payment`
--

LOCK TABLES `order_payment` WRITE;
/*!40000 ALTER TABLE `order_payment` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_payment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_phone`
--

DROP TABLE IF EXISTS `order_phone`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_phone` (
  `order_id` smallint(5) unsigned NOT NULL,
  `phone_id` int(11) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`order_id`,`phone_id`),
  KEY `fk_order_has_phone_phone1_idx` (`phone_id`),
  KEY `fk_order_has_phone_order1_idx` (`order_id`),
  CONSTRAINT `fk_order_has_phone_order1` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_order_has_phone_phone1` FOREIGN KEY (`phone_id`) REFERENCES `phone` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_phone`
--

LOCK TABLES `order_phone` WRITE;
/*!40000 ALTER TABLE `order_phone` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_phone` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_time_track`
--

DROP TABLE IF EXISTS `order_time_track`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_time_track` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` smallint(5) unsigned NOT NULL,
  `type` enum('TransferAt','ConfirmAt','FoodReadyAt','FoodPrepareAt','FoodDeliveryAt','DeliveryAcceptedAt','DeliveryAssignedAt','FoodPickAt','FoodEstimatedAt','FoodRecievedAt','CancelAt','OnWatRestaurantAt','ArrivedToRestaurantAt','WaitingForFoodAt','PickedUpAt','FoodRouteAt','ArrivedAtCustomerAt','SendToDriverAt') NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_order_time_track_order1_idx` (`order_id`),
  CONSTRAINT `fk_order_time_track_order1` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_time_track`
--

LOCK TABLES `order_time_track` WRITE;
/*!40000 ALTER TABLE `order_time_track` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_time_track` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page`
--

DROP TABLE IF EXISTS `page`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `language_id` int(11) unsigned NOT NULL,
  `title` varchar(150) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `description` varchar(150) DEFAULT NULL,
  `robots` varchar(30) DEFAULT NULL,
  `open_from` datetime NOT NULL,
  `open_to` datetime NOT NULL,
  `client_id` smallint(5) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_page_language1_idx` (`language_id`),
  KEY `fk_page_client1_idx` (`client_id`),
  CONSTRAINT `fk_page_language1` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_page_client1` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page`
--

LOCK TABLES `page` WRITE;
/*!40000 ALTER TABLE `page` DISABLE KEYS */;
/*!40000 ALTER TABLE `page` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment_method`
--

DROP TABLE IF EXISTS `payment_method`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment_method` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(250) DEFAULT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_method`
--

LOCK TABLES `payment_method` WRITE;
/*!40000 ALTER TABLE `payment_method` DISABLE KEYS */;
/*!40000 ALTER TABLE `payment_method` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `person`
--

DROP TABLE IF EXISTS `person`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `person` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) DEFAULT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `title` enum('Ms','Mrs','Mr') DEFAULT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `person`
--

LOCK TABLES `person` WRITE;
/*!40000 ALTER TABLE `person` DISABLE KEYS */;
/*!40000 ALTER TABLE `person` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `phone`
--

DROP TABLE IF EXISTS `phone`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `phone` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `number` varchar(50) NOT NULL,
  `type` enum('Mobile','Phone') NOT NULL DEFAULT 'Mobile',
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `phone`
--

LOCK TABLES `phone` WRITE;
/*!40000 ALTER TABLE `phone` DISABLE KEYS */;
/*!40000 ALTER TABLE `phone` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `postcode`
--

DROP TABLE IF EXISTS `postcode`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `postcode` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(150) DEFAULT NULL,
  `latitude` decimal(18,15) DEFAULT NULL,
  `longitude` decimal(18,15) DEFAULT NULL,
  `country_id` smallint(5) unsigned NOT NULL,
  `area_address_id` int(11) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_postcode_country_idx` (`country_id`),
  KEY `fk_postcode_area_address_idx` (`area_address_id`),
  CONSTRAINT `fk_postcode_country` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_postcode_area_address` FOREIGN KEY (`area_address_id`) REFERENCES `area_address` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `postcode`
--

LOCK TABLES `postcode` WRITE;
/*!40000 ALTER TABLE `postcode` DISABLE KEYS */;
/*!40000 ALTER TABLE `postcode` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project`
--

DROP TABLE IF EXISTS `project`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `project` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `daily_limit` float NOT NULL,
  `weekly_limit` float NOT NULL,
  `monthly_limit` float NOT NULL,
  `limit_type` enum('Fixed','Percent') NOT NULL DEFAULT 'Fixed',
  `company_id` int(11) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_project_company1_idx` (`company_id`),
  KEY `fk_project_user1_idx` (`user_id`),
  CONSTRAINT `fk_project_company1` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_project_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project`
--

LOCK TABLES `project` WRITE;
/*!40000 ALTER TABLE `project` DISABLE KEYS */;
/*!40000 ALTER TABLE `project` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rating`
--

DROP TABLE IF EXISTS `rating`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rating` (
  `review_id` bigint(20) unsigned NOT NULL,
  `factor` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`review_id`),
  KEY `fk_rating_review1_idx` (`review_id`),
  CONSTRAINT `fk_rating_review1` FOREIGN KEY (`review_id`) REFERENCES `feedback` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rating`
--

LOCK TABLES `rating` WRITE;
/*!40000 ALTER TABLE `rating` DISABLE KEYS */;
/*!40000 ALTER TABLE `rating` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `restaurant`
--

DROP TABLE IF EXISTS `restaurant`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `restaurant` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `opening_day` timestamp NULL DEFAULT NULL,
  `avg_prepare_time` int(11) NOT NULL,
  `min_order` float NOT NULL,
  `about_delivery` varchar(255) NOT NULL,
  `is_newest` bit(1) NOT NULL,
  `seo_title` varchar(255) NOT NULL,
  `meta_text` varchar(1000) NOT NULL,
  `meta_description` varchar(255) NOT NULL,
  `delivery_algo` text NOT NULL,
  `delivery_model` varchar(100) NOT NULL,
  `default_food_prep_time` int(11) DEFAULT NULL,
  `current_food_prep_time` int(11) DEFAULT NULL,
  `driver_deley_time` float NOT NULL,
  `have_app` tinyint(1) NOT NULL DEFAULT '0',
  `is_featured` bit(1) NOT NULL,
  `is_from_signup` bit(1) DEFAULT NULL,
  `address_base_id` int(11) unsigned NOT NULL,
  `payment_method_id` int(11) unsigned NOT NULL,
  `restaurant_group_id` bigint(20) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_restaurant_address_base1_idx` (`address_base_id`),
  KEY `fk_restaurant_payment_method1_idx` (`payment_method_id`),
  KEY `fk_restaurant_restaurant_group1_idx` (`restaurant_group_id`),
  CONSTRAINT `fk_restaurant_address_base1` FOREIGN KEY (`address_base_id`) REFERENCES `address_base` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_restaurant_payment_method1` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_method` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_restaurant_restaurant_group1` FOREIGN KEY (`restaurant_group_id`) REFERENCES `restaurant_group` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `restaurant`
--

LOCK TABLES `restaurant` WRITE;
/*!40000 ALTER TABLE `restaurant` DISABLE KEYS */;
/*!40000 ALTER TABLE `restaurant` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `restaurant_best_for_item`
--

DROP TABLE IF EXISTS `restaurant_best_for_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `restaurant_best_for_item` (
  `restaurant_id` smallint(5) unsigned NOT NULL,
  `best_for_item_id` int(11) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`restaurant_id`,`best_for_item_id`),
  KEY `fk_restaurant_has_best_for_item_best_for_item1_idx` (`best_for_item_id`),
  KEY `fk_restaurant_has_best_for_item_restaurant1_idx` (`restaurant_id`),
  CONSTRAINT `fk_restaurant_has_best_for_item_restaurant1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_restaurant_has_best_for_item_best_for_item1` FOREIGN KEY (`best_for_item_id`) REFERENCES `best_for_item` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `restaurant_best_for_item`
--

LOCK TABLES `restaurant_best_for_item` WRITE;
/*!40000 ALTER TABLE `restaurant_best_for_item` DISABLE KEYS */;
/*!40000 ALTER TABLE `restaurant_best_for_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `restaurant_chain`
--

DROP TABLE IF EXISTS `restaurant_chain`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `restaurant_chain` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name_key` varchar(150) NOT NULL,
  `client_id` smallint(5) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE key` (`name_key`),
  KEY `fk_restaurant_chain_client1_idx` (`client_id`),
  CONSTRAINT `fk_restaurant_chain_client1` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `restaurant_chain`
--

LOCK TABLES `restaurant_chain` WRITE;
/*!40000 ALTER TABLE `restaurant_chain` DISABLE KEYS */;
/*!40000 ALTER TABLE `restaurant_chain` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `restaurant_chain_user`
--

DROP TABLE IF EXISTS `restaurant_chain_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `restaurant_chain_user` (
  `user_id` bigint(20) unsigned NOT NULL,
  `restaurant_chain_id` bigint(20) unsigned NOT NULL,
  `role` enum('Admin') NOT NULL DEFAULT 'Admin',
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`,`restaurant_chain_id`),
  KEY `fk_user_has_restaurant_chain_restaurant_chain1_idx` (`restaurant_chain_id`),
  KEY `fk_user_has_restaurant_chain_user1_idx` (`user_id`),
  CONSTRAINT `fk_user_has_restaurant_chain_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_has_restaurant_chain_restaurant_chain1` FOREIGN KEY (`restaurant_chain_id`) REFERENCES `restaurant_chain` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `restaurant_chain_user`
--

LOCK TABLES `restaurant_chain_user` WRITE;
/*!40000 ALTER TABLE `restaurant_chain_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `restaurant_chain_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `restaurant_contact`
--

DROP TABLE IF EXISTS `restaurant_contact`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `restaurant_contact` (
  `restaurant_id` smallint(5) unsigned NOT NULL,
  `contact_id` smallint(5) unsigned NOT NULL,
  `role` enum('Manager','Owner','Partner','Director') DEFAULT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`restaurant_id`,`contact_id`),
  KEY `fk_restaurant_has_contact_contact1_idx` (`contact_id`),
  KEY `fk_restaurant_has_contact_restaurant1_idx` (`restaurant_id`),
  CONSTRAINT `fk_restaurant_has_contact_restaurant1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_restaurant_has_contact_contact1` FOREIGN KEY (`contact_id`) REFERENCES `contact` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `restaurant_contact`
--

LOCK TABLES `restaurant_contact` WRITE;
/*!40000 ALTER TABLE `restaurant_contact` DISABLE KEYS */;
/*!40000 ALTER TABLE `restaurant_contact` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `restaurant_contact_order`
--

DROP TABLE IF EXISTS `restaurant_contact_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `restaurant_contact_order` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('SMS','PhoneCall','Email','VoiceConfiramtion','IVR') NOT NULL,
  `charge` float DEFAULT NULL,
  `alert` bit(1) NOT NULL,
  `delay_in_min` int(5) DEFAULT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `contact_id` smallint(5) unsigned NOT NULL,
  `restaurant_id` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_restaurant_contact_order_contact1_idx` (`contact_id`),
  KEY `fk_restaurant_contact_order_restaurant1_idx` (`restaurant_id`),
  CONSTRAINT `fk_restaurant_contact_order_contact1` FOREIGN KEY (`contact_id`) REFERENCES `contact` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_restaurant_contact_order_restaurant1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `restaurant_contact_order`
--

LOCK TABLES `restaurant_contact_order` WRITE;
/*!40000 ALTER TABLE `restaurant_contact_order` DISABLE KEYS */;
/*!40000 ALTER TABLE `restaurant_contact_order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `restaurant_cuisine`
--

DROP TABLE IF EXISTS `restaurant_cuisine`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `restaurant_cuisine` (
  `restaurant_id` smallint(5) unsigned NOT NULL,
  `cuisine_id` int(11) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`restaurant_id`,`cuisine_id`),
  KEY `fk_restaurant_has_cuisine_cuisine1_idx` (`cuisine_id`),
  KEY `fk_restaurant_has_cuisine_restaurant1_idx` (`restaurant_id`),
  CONSTRAINT `fk_restaurant_has_cuisine_restaurant1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_restaurant_has_cuisine_cuisine1` FOREIGN KEY (`cuisine_id`) REFERENCES `cuisine` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `restaurant_cuisine`
--

LOCK TABLES `restaurant_cuisine` WRITE;
/*!40000 ALTER TABLE `restaurant_cuisine` DISABLE KEYS */;
/*!40000 ALTER TABLE `restaurant_cuisine` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `restaurant_delivery`
--

DROP TABLE IF EXISTS `restaurant_delivery`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `restaurant_delivery` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `description_key` varchar(150) DEFAULT NULL,
  `algo` varchar(50) DEFAULT NULL,
  `type` enum('CloudDriver','DedicatedDriver') NOT NULL,
  `has_collection` bit(1) NOT NULL,
  `has_dinein` bit(1) NOT NULL,
  `has_own` bit(1) NOT NULL,
  `collect_time_in_min` float DEFAULT NULL,
  `driver_delay_time` time DEFAULT NULL,
  `delivery_per_week` int(5) DEFAULT NULL,
  `restaurant_id` smallint(5) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`,`restaurant_id`),
  KEY `fk_deliverycharges_restaurant1_idx` (`restaurant_id`),
  CONSTRAINT `fk_deliverycharges_restaurant10` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `restaurant_delivery`
--

LOCK TABLES `restaurant_delivery` WRITE;
/*!40000 ALTER TABLE `restaurant_delivery` DISABLE KEYS */;
/*!40000 ALTER TABLE `restaurant_delivery` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `restaurant_delivery_charges`
--

DROP TABLE IF EXISTS `restaurant_delivery_charges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `restaurant_delivery_charges` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `distance_in_mile` float DEFAULT NULL,
  `charge` float DEFAULT NULL,
  `restaurant_delivery_id` int(11) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_restaurant_delivery_charges_restaurant_delivery1_idx` (`restaurant_delivery_id`),
  CONSTRAINT `fk_restaurant_delivery_charges_restaurant_delivery1` FOREIGN KEY (`restaurant_delivery_id`) REFERENCES `restaurant_delivery` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `restaurant_delivery_charges`
--

LOCK TABLES `restaurant_delivery_charges` WRITE;
/*!40000 ALTER TABLE `restaurant_delivery_charges` DISABLE KEYS */;
/*!40000 ALTER TABLE `restaurant_delivery_charges` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `restaurant_group`
--

DROP TABLE IF EXISTS `restaurant_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `restaurant_group` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name_key` varchar(150) NOT NULL,
  `restaurant_chain_id` bigint(20) unsigned NOT NULL,
  `currency_id` bigint(20) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE key` (`name_key`),
  KEY `fk_restaurant_group_restaurant_chain1_idx` (`restaurant_chain_id`),
  KEY `fk_restaurant_group_currency1_idx` (`currency_id`),
  CONSTRAINT `fk_restaurant_group_restaurant_chain1` FOREIGN KEY (`restaurant_chain_id`) REFERENCES `restaurant_chain` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_restaurant_group_currency1` FOREIGN KEY (`currency_id`) REFERENCES `currency` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `restaurant_group`
--

LOCK TABLES `restaurant_group` WRITE;
/*!40000 ALTER TABLE `restaurant_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `restaurant_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `restaurant_group_user`
--

DROP TABLE IF EXISTS `restaurant_group_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `restaurant_group_user` (
  `user_id` bigint(20) unsigned NOT NULL,
  `restaurant_group_id` bigint(20) unsigned NOT NULL,
  `role` enum('Admin') NOT NULL DEFAULT 'Admin',
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`,`restaurant_group_id`),
  KEY `fk_user_has_restaurant_group_restaurant_group1_idx` (`restaurant_group_id`),
  KEY `fk_user_has_restaurant_group_user1_idx` (`user_id`),
  CONSTRAINT `fk_user_has_restaurant_group_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_has_restaurant_group_restaurant_group1` FOREIGN KEY (`restaurant_group_id`) REFERENCES `restaurant_group` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `restaurant_group_user`
--

LOCK TABLES `restaurant_group_user` WRITE;
/*!40000 ALTER TABLE `restaurant_group_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `restaurant_group_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `restaurant_like`
--

DROP TABLE IF EXISTS `restaurant_like`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `restaurant_like` (
  `restaurant_id` smallint(5) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`restaurant_id`,`user_id`),
  KEY `fk_restaurant_has_user_user1_idx` (`user_id`),
  KEY `fk_restaurant_has_user_restaurant1_idx` (`restaurant_id`),
  CONSTRAINT `fk_restaurant_has_user_restaurant1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_restaurant_has_user_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `restaurant_like`
--

LOCK TABLES `restaurant_like` WRITE;
/*!40000 ALTER TABLE `restaurant_like` DISABLE KEYS */;
/*!40000 ALTER TABLE `restaurant_like` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `restaurant_payment`
--

DROP TABLE IF EXISTS `restaurant_payment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `restaurant_payment` (
  `restaurant_id` smallint(5) unsigned NOT NULL,
  `frequency_type` enum('BiMonthly','Monthly','Weekly','Daily','IVR') NOT NULL,
  `delay_in_min` int(5) DEFAULT NULL,
  `charge_value` float NOT NULL,
  `fee_value` float NOT NULL,
  `fee_type` enum('VATExclusive','VATInclusive') NOT NULL,
  `charge_type` enum('WebPrice','RestaurantPrice') NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`restaurant_id`),
  UNIQUE KEY `UNIQUE key` (`frequency_type`),
  KEY `fk_restaurant_payment_restaurant1_idx` (`restaurant_id`),
  CONSTRAINT `fk_restaurant_payment_restaurant1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `restaurant_payment`
--

LOCK TABLES `restaurant_payment` WRITE;
/*!40000 ALTER TABLE `restaurant_payment` DISABLE KEYS */;
/*!40000 ALTER TABLE `restaurant_payment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `restaurant_payment_bank`
--

DROP TABLE IF EXISTS `restaurant_payment_bank`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `restaurant_payment_bank` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `account_holder_name` varchar(150) NOT NULL,
  `bank_name` varchar(150) NOT NULL,
  `sort_code` varchar(50) NOT NULL,
  `restaurant_payment_restaurant_id` smallint(5) unsigned NOT NULL,
  `account_number` varchar(50) DEFAULT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE key` (`account_holder_name`),
  KEY `fk_restaurant_payment_bank_restaurant_payment1_idx` (`restaurant_payment_restaurant_id`),
  CONSTRAINT `fk_restaurant_payment_bank_restaurant_payment1` FOREIGN KEY (`restaurant_payment_restaurant_id`) REFERENCES `restaurant_payment` (`restaurant_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `restaurant_payment_bank`
--

LOCK TABLES `restaurant_payment_bank` WRITE;
/*!40000 ALTER TABLE `restaurant_payment_bank` DISABLE KEYS */;
/*!40000 ALTER TABLE `restaurant_payment_bank` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `restaurant_payment_cc`
--

DROP TABLE IF EXISTS `restaurant_payment_cc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `restaurant_payment_cc` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `cc_name` varchar(150) NOT NULL,
  `restaurant_payment_restaurant_id` smallint(5) unsigned NOT NULL,
  `start_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `end_date` timestamp NULL DEFAULT NULL,
  `security_code` varchar(50) NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE key` (`cc_name`),
  KEY `fk_restaurant_payment_bank_restaurant_payment1_idx` (`restaurant_payment_restaurant_id`),
  CONSTRAINT `fk_restaurant_payment_bank_restaurant_payment100` FOREIGN KEY (`restaurant_payment_restaurant_id`) REFERENCES `restaurant_payment` (`restaurant_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `restaurant_payment_cc`
--

LOCK TABLES `restaurant_payment_cc` WRITE;
/*!40000 ALTER TABLE `restaurant_payment_cc` DISABLE KEYS */;
/*!40000 ALTER TABLE `restaurant_payment_cc` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `restaurant_payment_paypal`
--

DROP TABLE IF EXISTS `restaurant_payment_paypal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `restaurant_payment_paypal` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(150) NOT NULL,
  `restaurant_payment_restaurant_id` smallint(5) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE key` (`email`),
  KEY `fk_restaurant_payment_bank_restaurant_payment1_idx` (`restaurant_payment_restaurant_id`),
  CONSTRAINT `fk_restaurant_payment_bank_restaurant_payment10` FOREIGN KEY (`restaurant_payment_restaurant_id`) REFERENCES `restaurant_payment` (`restaurant_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `restaurant_payment_paypal`
--

LOCK TABLES `restaurant_payment_paypal` WRITE;
/*!40000 ALTER TABLE `restaurant_payment_paypal` DISABLE KEYS */;
/*!40000 ALTER TABLE `restaurant_payment_paypal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `restaurant_photo`
--

DROP TABLE IF EXISTS `restaurant_photo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `restaurant_photo` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `image_name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `order` int(11) NOT NULL,
  `restaurant_id` smallint(5) unsigned NOT NULL,
  `is_default` bit(1) DEFAULT b'1',
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_restaurant_photo_restaurant1_idx` (`restaurant_id`),
  CONSTRAINT `fk_restaurant_photo_restaurant1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `restaurant_photo`
--

LOCK TABLES `restaurant_photo` WRITE;
/*!40000 ALTER TABLE `restaurant_photo` DISABLE KEYS */;
/*!40000 ALTER TABLE `restaurant_photo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `restaurant_schedule`
--

DROP TABLE IF EXISTS `restaurant_schedule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `restaurant_schedule` (
  `restaurant_id` smallint(5) unsigned NOT NULL,
  `schedule_id` smallint(5) unsigned NOT NULL,
  `type` enum('OpenTime','CloseTime','DeliveryTime') NOT NULL,
  `open_day` enum('Sunday','Monday','Thuesday','Wednesday','Thursday','Friday','Saturaday') NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`restaurant_id`,`schedule_id`),
  KEY `fk_restaurant_has_schedule_schedule1_idx` (`schedule_id`),
  KEY `fk_restaurant_has_schedule_restaurant1_idx` (`restaurant_id`),
  CONSTRAINT `fk_restaurant_has_schedule_restaurant1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_restaurant_has_schedule_schedule1` FOREIGN KEY (`schedule_id`) REFERENCES `schedule` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `restaurant_schedule`
--

LOCK TABLES `restaurant_schedule` WRITE;
/*!40000 ALTER TABLE `restaurant_schedule` DISABLE KEYS */;
/*!40000 ALTER TABLE `restaurant_schedule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `restaurant_suggested`
--

DROP TABLE IF EXISTS `restaurant_suggested`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `restaurant_suggested` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `cuisine_id` int(11) DEFAULT NULL,
  `addDate` datetime DEFAULT NULL,
  `sugRank` int(11) NOT NULL DEFAULT '10000',
  `contact_id` smallint(5) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_restaurant_suggested_contact1_idx` (`contact_id`),
  CONSTRAINT `fk_restaurant_suggested_contact1` FOREIGN KEY (`contact_id`) REFERENCES `contact` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `restaurant_suggested`
--

LOCK TABLES `restaurant_suggested` WRITE;
/*!40000 ALTER TABLE `restaurant_suggested` DISABLE KEYS */;
/*!40000 ALTER TABLE `restaurant_suggested` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `restaurant_suggested_rank`
--

DROP TABLE IF EXISTS `restaurant_suggested_rank`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `restaurant_suggested_rank` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ranking` int(11) DEFAULT '1',
  `restaurant_suggested_id` int(11) NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_resturant_suggested_rank_restaurant_suggested1_idx` (`restaurant_suggested_id`),
  CONSTRAINT `fk_resturant_suggested_rank_restaurant_suggested1` FOREIGN KEY (`restaurant_suggested_id`) REFERENCES `restaurant_suggested` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `restaurant_suggested_rank`
--

LOCK TABLES `restaurant_suggested_rank` WRITE;
/*!40000 ALTER TABLE `restaurant_suggested_rank` DISABLE KEYS */;
/*!40000 ALTER TABLE `restaurant_suggested_rank` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `restaurant_user`
--

DROP TABLE IF EXISTS `restaurant_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `restaurant_user` (
  `restaurant_id` smallint(5) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `role` enum('Admin') NOT NULL DEFAULT 'Admin',
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`restaurant_id`,`user_id`),
  KEY `fk_restaurant_has_user_user2_idx` (`user_id`),
  KEY `fk_restaurant_has_user_restaurant2_idx` (`restaurant_id`),
  CONSTRAINT `fk_restaurant_has_user_restaurant2` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_restaurant_has_user_user2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `restaurant_user`
--

LOCK TABLES `restaurant_user` WRITE;
/*!40000 ALTER TABLE `restaurant_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `restaurant_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `review`
--

DROP TABLE IF EXISTS `review`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `review` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `comment` text,
  `title` varchar(255) DEFAULT NULL,
  `restaurant_id` smallint(5) unsigned NOT NULL,
  `order_id` smallint(5) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_review_restaurant1_idx` (`restaurant_id`),
  KEY `fk_review_order1_idx` (`order_id`),
  CONSTRAINT `fk_review_restaurant1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_review_order1` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `review`
--

LOCK TABLES `review` WRITE;
/*!40000 ALTER TABLE `review` DISABLE KEYS */;
/*!40000 ALTER TABLE `review` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `schedule`
--

DROP TABLE IF EXISTS `schedule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `schedule` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `from` datetime NOT NULL,
  `to` datetime NOT NULL,
  `menu_type_id` int(11) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_schedule_menu_type1_idx` (`menu_type_id`),
  CONSTRAINT `fk_schedule_menu_type1` FOREIGN KEY (`menu_type_id`) REFERENCES `menu_type` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `schedule`
--

LOCK TABLES `schedule` WRITE;
/*!40000 ALTER TABLE `schedule` DISABLE KEYS */;
/*!40000 ALTER TABLE `schedule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sms_cost`
--

DROP TABLE IF EXISTS `sms_cost`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sms_cost` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cost` float NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sms_cost`
--

LOCK TABLES `sms_cost` WRITE;
/*!40000 ALTER TABLE `sms_cost` DISABLE KEYS */;
/*!40000 ALTER TABLE `sms_cost` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sms_record`
--

DROP TABLE IF EXISTS `sms_record`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sms_record` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mobile` varchar(255) NOT NULL,
  `message` varchar(255) NOT NULL,
  `send_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `cost` float NOT NULL,
  `sms_type` enum('R','D') NOT NULL COMMENT 'R=Restaurant,D=Driver',
  `order_id` smallint(5) unsigned NOT NULL,
  `restaurant_id` smallint(5) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_smsrecord_order1_idx` (`order_id`),
  KEY `fk_smsrecord_restaurant1_idx` (`restaurant_id`),
  CONSTRAINT `fk_smsrecord_order1` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_smsrecord_restaurant1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sms_record`
--

LOCK TABLES `sms_record` WRITE;
/*!40000 ALTER TABLE `sms_record` DISABLE KEYS */;
/*!40000 ALTER TABLE `sms_record` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `token` text NOT NULL,
  `user_type` enum('Admin','User','RestaurantOwner','RestaurantApp','CorporateUser','RestaurantTeam','Dispatcher','CorporateAdmin','RestaurantGroupAdmin','Finance') NOT NULL,
  `lastvisit` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `activation_hash` varchar(255) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `dob` date NOT NULL,
  `know_about` varchar(255) NOT NULL,
  `term_and_cond` bit(1) NOT NULL,
  `term_and_cond_web` bit(1) NOT NULL,
  `term_and_cond_acc_pol` bit(1) NOT NULL,
  `api_token` char(32) DEFAULT NULL,
  `reset_password_hash` varchar(255) DEFAULT NULL,
  `affiliate_id` int(11) unsigned DEFAULT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_user_affiliate1_idx` (`affiliate_id`),
  CONSTRAINT `fk_user_affiliate1` FOREIGN KEY (`affiliate_id`) REFERENCES `affiliate` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_contact`
--

DROP TABLE IF EXISTS `user_contact`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_contact` (
  `user_id` bigint(20) unsigned NOT NULL,
  `contact_id` smallint(5) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`,`contact_id`),
  KEY `fk_user_has_contact_contact1_idx` (`contact_id`),
  KEY `fk_user_has_contact_user1_idx` (`user_id`),
  CONSTRAINT `fk_user_has_contact_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_has_contact_contact1` FOREIGN KEY (`contact_id`) REFERENCES `contact` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_contact`
--

LOCK TABLES `user_contact` WRITE;
/*!40000 ALTER TABLE `user_contact` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_contact` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_group_code`
--

DROP TABLE IF EXISTS `user_group_code`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_group_code` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(50) DEFAULT NULL,
  `company_user_group_id` smallint(5) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_user_group_code_company_user_group1_idx` (`company_user_group_id`),
  CONSTRAINT `fk_user_group_code_company_user_group1` FOREIGN KEY (`company_user_group_id`) REFERENCES `company_user_group` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_group_code`
--

LOCK TABLES `user_group_code` WRITE;
/*!40000 ALTER TABLE `user_group_code` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_group_code` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vat`
--

DROP TABLE IF EXISTS `vat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vat` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('Zero','Stardard') NOT NULL,
  `value` float NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vat`
--

LOCK TABLES `vat` WRITE;
/*!40000 ALTER TABLE `vat` DISABLE KEYS */;
/*!40000 ALTER TABLE `vat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `voucher`
--

DROP TABLE IF EXISTS `voucher`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `voucher` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `dicsount_value` float NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `value_type` enum('Fixed','Percent') NOT NULL,
  `price_value` float NOT NULL,
  `item_quantity` int(11) NOT NULL,
  `description` text NOT NULL,
  `order_after` varchar(255) NOT NULL,
  `max_times_per_user` int(11) DEFAULT NULL,
  `generate_by` enum('M','A') NOT NULL DEFAULT 'M',
  `total_limit` int(11) DEFAULT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `voucher`
--

LOCK TABLES `voucher` WRITE;
/*!40000 ALTER TABLE `voucher` DISABLE KEYS */;
/*!40000 ALTER TABLE `voucher` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `voucher_menu_item`
--

DROP TABLE IF EXISTS `voucher_menu_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `voucher_menu_item` (
  `voucher_id` int(11) unsigned NOT NULL,
  `menu_item_id` bigint(20) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `buy_quantity` int(11) NOT NULL,
  PRIMARY KEY (`voucher_id`,`menu_item_id`),
  KEY `fk_voucher_has_menu_item_menu_item1_idx` (`menu_item_id`),
  KEY `fk_voucher_has_menu_item_voucher1_idx` (`voucher_id`),
  CONSTRAINT `fk_voucher_has_menu_item_voucher1` FOREIGN KEY (`voucher_id`) REFERENCES `voucher` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_voucher_has_menu_item_menu_item1` FOREIGN KEY (`menu_item_id`) REFERENCES `menu_item` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `voucher_menu_item`
--

LOCK TABLES `voucher_menu_item` WRITE;
/*!40000 ALTER TABLE `voucher_menu_item` DISABLE KEYS */;
/*!40000 ALTER TABLE `voucher_menu_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `voucher_restaurant`
--

DROP TABLE IF EXISTS `voucher_restaurant`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `voucher_restaurant` (
  `voucher_id` int(11) unsigned NOT NULL,
  `restaurant_id` smallint(5) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`voucher_id`,`restaurant_id`),
  KEY `fk_voucher_has_restaurant_restaurant1_idx` (`restaurant_id`),
  KEY `fk_voucher_has_restaurant_voucher1_idx` (`voucher_id`),
  CONSTRAINT `fk_voucher_has_restaurant_voucher1` FOREIGN KEY (`voucher_id`) REFERENCES `voucher` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_voucher_has_restaurant_restaurant1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `voucher_restaurant`
--

LOCK TABLES `voucher_restaurant` WRITE;
/*!40000 ALTER TABLE `voucher_restaurant` DISABLE KEYS */;
/*!40000 ALTER TABLE `voucher_restaurant` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `voucher_restaurant_chain`
--

DROP TABLE IF EXISTS `voucher_restaurant_chain`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `voucher_restaurant_chain` (
  `voucher_id` int(11) unsigned NOT NULL,
  `restaurant_chain_id` bigint(20) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`voucher_id`,`restaurant_chain_id`),
  KEY `fk_voucher_has_restaurant_chain_restaurant_chain1_idx` (`restaurant_chain_id`),
  KEY `fk_voucher_has_restaurant_chain_voucher1_idx` (`voucher_id`),
  CONSTRAINT `fk_voucher_has_restaurant_chain_voucher1` FOREIGN KEY (`voucher_id`) REFERENCES `voucher` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_voucher_has_restaurant_chain_restaurant_chain1` FOREIGN KEY (`restaurant_chain_id`) REFERENCES `restaurant_chain` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `voucher_restaurant_chain`
--

LOCK TABLES `voucher_restaurant_chain` WRITE;
/*!40000 ALTER TABLE `voucher_restaurant_chain` DISABLE KEYS */;
/*!40000 ALTER TABLE `voucher_restaurant_chain` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `voucher_restaurant_group`
--

DROP TABLE IF EXISTS `voucher_restaurant_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `voucher_restaurant_group` (
  `voucher_id` int(11) unsigned NOT NULL,
  `restaurant_group_id` bigint(20) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`voucher_id`,`restaurant_group_id`),
  KEY `fk_voucher_has_restaurant_group_restaurant_group1_idx` (`restaurant_group_id`),
  KEY `fk_voucher_has_restaurant_group_voucher1_idx` (`voucher_id`),
  CONSTRAINT `fk_voucher_has_restaurant_group_voucher1` FOREIGN KEY (`voucher_id`) REFERENCES `voucher` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_voucher_has_restaurant_group_restaurant_group1` FOREIGN KEY (`restaurant_group_id`) REFERENCES `restaurant_group` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `voucher_restaurant_group`
--

LOCK TABLES `voucher_restaurant_group` WRITE;
/*!40000 ALTER TABLE `voucher_restaurant_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `voucher_restaurant_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `voucher_use_history`
--

DROP TABLE IF EXISTS `voucher_use_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `voucher_use_history` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `voucher_id` int(11) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_voucher_use_history_voucher1_idx` (`voucher_id`),
  KEY `fk_voucher_use_history_user1_idx` (`user_id`),
  CONSTRAINT `fk_voucher_use_history_voucher1` FOREIGN KEY (`voucher_id`) REFERENCES `voucher` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_voucher_use_history_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `voucher_use_history`
--

LOCK TABLES `voucher_use_history` WRITE;
/*!40000 ALTER TABLE `voucher_use_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `voucher_use_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `voucher_user`
--

DROP TABLE IF EXISTS `voucher_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `voucher_user` (
  `id` bigint(11) unsigned NOT NULL AUTO_INCREMENT,
  `emark_type` enum('NeverOrder','LastVisit') NOT NULL DEFAULT 'NeverOrder',
  `promo_used` enum('0','1') NOT NULL DEFAULT '0',
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `template_type` int(11) NOT NULL,
  `applicable_offer` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `voucher_id` int(11) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_voucher_user_user1_idx` (`user_id`),
  KEY `fk_voucher_user_voucher1_idx` (`voucher_id`),
  CONSTRAINT `fk_voucher_user_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_voucher_user_voucher1` FOREIGN KEY (`voucher_id`) REFERENCES `voucher` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `voucher_user`
--

LOCK TABLES `voucher_user` WRITE;
/*!40000 ALTER TABLE `voucher_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `voucher_user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-02-02 22:04:31
