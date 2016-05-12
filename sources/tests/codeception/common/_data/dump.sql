-- MySQL dump 10.13  Distrib 5.6.19, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: new
-- ------------------------------------------------------
-- Server version	5.6.19-0ubuntu0.14.04.1

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
  `country_id` smallint(5) unsigned NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `title` varchar(45) DEFAULT 'Mr',
  `city` varchar(255) NOT NULL,
  `postcode` varchar(45) NOT NULL,
  `address1` varchar(50) NOT NULL,
  `address2` varchar(50) DEFAULT NULL,
  `address3` varchar(50) DEFAULT NULL,
  `instructions` varchar(250) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `email` varchar(250) DEFAULT NULL,
  `building_number` varchar(50) DEFAULT NULL,
  `latitude` decimal(18,15) DEFAULT NULL,
  `longitude` decimal(18,15) DEFAULT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_address_country1_idx` (`country_id`),
  CONSTRAINT `fk_address_country1` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=905 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `address_base`
--

DROP TABLE IF EXISTS `address_base`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `address_base` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `postcode` varchar(45) NOT NULL,
  `latitude` decimal(18,15) DEFAULT NULL,
  `longitude` decimal(18,15) DEFAULT NULL,
  `name` varchar(250) NOT NULL,
  `delivery_delay_time` time NOT NULL,
  `max_delivery_distance` float DEFAULT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=95 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `allergy`
--

DROP TABLE IF EXISTS `allergy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `allergy` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name_key` varchar(255) DEFAULT NULL,
  `description_key` varchar(255) DEFAULT NULL,
  `symbol_key` varchar(255) DEFAULT NULL,
  `image_file_name` varchar(500) DEFAULT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cache_queue`
--

DROP TABLE IF EXISTS `cache_queue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_queue` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `action` text NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5634 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `client`
--

DROP TABLE IF EXISTS `client`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `voucher_id` int(11) unsigned DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `key` varchar(255) NOT NULL,
  `description` varchar(500) DEFAULT NULL,
  `url` varchar(100) DEFAULT NULL,
  `contact_email` varchar(255) NOT NULL,
  `loyalty_points_per_currency` smallint(5) DEFAULT NULL,
  `loyalty_points_per_voucher` smallint(5) DEFAULT NULL,
  `mc_host` varchar(255) DEFAULT NULL,
  `mc_api_key` varchar(255) DEFAULT NULL,
  `mc_default_city_list_name` varchar(255) DEFAULT NULL,
  `mc_default_restaurant_list_name` varchar(255) DEFAULT NULL,
  `payment_merchant_account` varchar(100) DEFAULT NULL,
  `payment_skin_code` varchar(100) DEFAULT NULL,
  `payment_hmac_key` varchar(255) DEFAULT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`),
  KEY `fk_client_voucher1_idx` (`voucher_id`),
  CONSTRAINT `fk_client_voucher1` FOREIGN KEY (`voucher_id`) REFERENCES `voucher` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `code`
--

DROP TABLE IF EXISTS `code`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `code` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` int(11) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `daily_limit` float DEFAULT NULL,
  `weekly_limit` float DEFAULT NULL,
  `monthly_limit` float DEFAULT NULL,
  `limit_type` enum('Soft','Hard') NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_project_company1_idx` (`company_id`),
  CONSTRAINT `fk_project_company100` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  `is_vat_exclusive` tinyint(1) NOT NULL DEFAULT '1',
  `daily_limit` float NOT NULL,
  `weekly_limit` float NOT NULL,
  `monthly_limit` float NOT NULL,
  `limit_type` enum('Soft','Hard') NOT NULL,
  `vat_number` varchar(25) DEFAULT NULL,
  `min_order_morning_time_from` time DEFAULT NULL,
  `min_order_morning_time_to` time DEFAULT NULL,
  `min_order_evening_time_from` time DEFAULT NULL,
  `min_order_evening_time_to` time DEFAULT NULL,
  `min_order_morning_amount` float DEFAULT NULL,
  `min_order_evening_amount` float DEFAULT NULL,
  `client_id` smallint(5) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_company_client1_idx` (`client_id`),
  CONSTRAINT `fk_company_client1` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `company_address`
--

DROP TABLE IF EXISTS `company_address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `company_address` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `address_id` bigint(20) unsigned NOT NULL,
  `company_id` int(11) unsigned NOT NULL,
  `address_type` enum('Billing','Delivery','Physical') NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_address_has_company_company1_idx` (`company_id`),
  KEY `fk_address_has_company_address1_idx` (`address_id`),
  CONSTRAINT `fk_address_has_company_address1` FOREIGN KEY (`address_id`) REFERENCES `address` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_address_has_company_company1` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=150 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `company_domain`
--

DROP TABLE IF EXISTS `company_domain`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `company_domain` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `domain` varchar(150) NOT NULL,
  `company_id` int(11) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE domain` (`domain`,`company_id`),
  KEY `fk_company_domain_company1_idx` (`company_id`),
  CONSTRAINT `fk_company_domain_company1` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `company_user_group`
--

DROP TABLE IF EXISTS `company_user_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `company_user_group` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` int(11) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `max_order_per_day_per_user` int(11) DEFAULT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_cug_company1_idx` (`company_id`),
  CONSTRAINT `fk_company_user_group_company1` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `company_user_group_code`
--

DROP TABLE IF EXISTS `company_user_group_code`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `company_user_group_code` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `company_user_group_id` smallint(5) unsigned NOT NULL,
  `code_id` int(11) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_company_user_group_code_company_user_group1_idx` (`company_user_group_id`),
  KEY `fk_company_user_group_code_code1_idx` (`code_id`),
  CONSTRAINT `fk_company_user_group_code_code1` FOREIGN KEY (`code_id`) REFERENCES `code` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_company_user_group_code_company_user_group1` FOREIGN KEY (`company_user_group_id`) REFERENCES `company_user_group` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `corporate_order`
--

DROP TABLE IF EXISTS `corporate_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `corporate_order` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned zerofill DEFAULT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `code_data` text,
  `company_user_group_data` text NOT NULL,
  `allocation` float NOT NULL,
  `comment` varchar(500) DEFAULT NULL,
  `company` varchar(255) NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_corporate_order_order1_idx` (`order_id`),
  KEY `fk_corporate_order_user1_idx` (`user_id`),
  CONSTRAINT `fk_corporate_order_order` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_corporate_order_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `country`
--

DROP TABLE IF EXISTS `country`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `country` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name_key` varchar(190) NOT NULL,
  `native_name` varchar(50) NOT NULL,
  `iso_code` char(2) NOT NULL COMMENT 'ISO 3166-1-alpha-2 code',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`alex.popov`@`%`*/ /*!50003 TRIGGER `country_AFTER_UPDATE`
AFTER UPDATE ON `country`
FOR EACH ROW
BEGIN
	IF NEW.record_type = 'Deleted' AND OLD.record_type <> 'Deleted' THEN
		BEGIN
			DECLARE default_country_id bigint(20);
			SET default_country_id = (select id from country where is_default = 1);
            IF (default_country_id IS NOT NULL) THEN 
				BEGIN
					
					update address set country_id = default_country_id where country_id = NEW.id;
				END;
			END IF;
		END;
	END IF;

END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `cuisine`
--

DROP TABLE IF EXISTS `cuisine`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cuisine` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name_key` varchar(190) NOT NULL,
  `seo_name` varchar(255) NOT NULL,
  `description_key` varchar(190) DEFAULT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `currency`
--

DROP TABLE IF EXISTS `currency`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `currency` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) CHARACTER SET utf8mb4 NOT NULL,
  `code` varchar(150) CHARACTER SET utf8mb4 NOT NULL,
  `symbol` varchar(1) NOT NULL DEFAULT '',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `record_type` enum('Active','Inactive','Deleted') CHARACTER SET utf8mb4 NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE key` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `default_delivery_charge`
--

DROP TABLE IF EXISTS `default_delivery_charge`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `default_delivery_charge` (
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
-- Table structure for table `email_template`
--

DROP TABLE IF EXISTS `email_template`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `email_template` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` smallint(5) unsigned NOT NULL,
  `language_id` int(11) unsigned NOT NULL,
  `email_type` enum('NewUserRegistration','ForgotPassword','TransferringToRestaurant','OrderConfirmed','FoodEnRoute','Delivered','Cancellation','ContactUs','SuggestRestaurant','RestarauntSignup') NOT NULL,
  `title` varchar(255) NOT NULL,
  `cc` varchar(255) DEFAULT NULL,
  `bcc` varchar(255) DEFAULT NULL,
  `from_email` varchar(255) NOT NULL,
  `from_name` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_email_template_client1_idx` (`client_id`),
  KEY `fk_email_template_language1_idx` (`language_id`),
  CONSTRAINT `fk_email_template_client1` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_email_template_language1` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=111 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `expense_type`
--

DROP TABLE IF EXISTS `expense_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `expense_type` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `company_user_group_id` smallint(5) unsigned NOT NULL,
  `name` varchar(250) NOT NULL,
  `limit_per_order` float NOT NULL,
  `limit_type` enum('Soft','Hard') NOT NULL,
  `soft_limit_max` float DEFAULT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_expense_type_company_user_group1_idx` (`company_user_group_id`),
  CONSTRAINT `fk_expense_type_company_user_group10` FOREIGN KEY (`company_user_group_id`) REFERENCES `company_user_group` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `expense_type_schedule`
--

DROP TABLE IF EXISTS `expense_type_schedule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `expense_type_schedule` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `expense_type_id` smallint(5) unsigned NOT NULL,
  `from` time DEFAULT NULL,
  `to` time DEFAULT NULL,
  `day` enum('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday') NOT NULL,
  `day_time_type` enum('Morning','Evening') NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_expense_type_schedule_expense_type1_idx` (`expense_type_id`),
  CONSTRAINT `fk_expense_type_schedule_expense_type1` FOREIGN KEY (`expense_type_id`) REFERENCES `expense_type` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `feedback`
--

DROP TABLE IF EXISTS `feedback`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `feedback` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `restaurant_id` smallint(5) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `order_id` bigint(20) unsigned NOT NULL,
  `title` varchar(100) NOT NULL,
  `text` varchar(500) NOT NULL,
  `rating` int(11) NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_feedback_restaurant1_idx` (`restaurant_id`),
  KEY `fk_feedback_user1_idx` (`user_id`),
  KEY `fk_feedback_order1_idx` (`order_id`),
  CONSTRAINT `fk_feedback_restaurant1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_feedback_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_feedback_order1` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `group_order_contact`
--

DROP TABLE IF EXISTS `group_order_contact`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `group_order_contact` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `contact_id` smallint(5) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_group_order_contact_order1_idx` (`order_id`),
  CONSTRAINT `fk_group_order_contact_order1` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `label`
--

DROP TABLE IF EXISTS `label`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `label` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` smallint(5) unsigned DEFAULT NULL,
  `code` varchar(190) NOT NULL,
  `description` varchar(250) NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_label_client1_idx` (`client_id`),
  CONSTRAINT `fk_label_client1` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=22894 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `label_language`
--

DROP TABLE IF EXISTS `label_language`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `label_language` (
  `language_id` int(11) unsigned NOT NULL,
  `label_id` bigint(20) unsigned NOT NULL,
  `value` longtext NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`language_id`,`label_id`),
  KEY `fk_language_has_label_label1_idx` (`label_id`),
  KEY `fk_language_has_label_language1_idx` (`language_id`),
  CONSTRAINT `fk_language_has_label_label1` FOREIGN KEY (`label_id`) REFERENCES `label` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_language_has_label_language1` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `menu`
--

DROP TABLE IF EXISTS `menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` smallint(5) unsigned NOT NULL,
  `name_key` varchar(250) NOT NULL,
  `reference_name` varchar(250) NOT NULL,
  `from` time NOT NULL,
  `to` time NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_menu_client1_idx` (`client_id`),
  CONSTRAINT `fk_menu_client1` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=182 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `menu_assignment`
--

DROP TABLE IF EXISTS `menu_assignment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu_assignment` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` smallint(5) unsigned DEFAULT NULL,
  `restaurant_chain_id` bigint(20) unsigned DEFAULT NULL,
  `restaurant_group_id` bigint(20) unsigned DEFAULT NULL,
  `restaurant_id` smallint(5) unsigned DEFAULT NULL,
  `menu_id` smallint(5) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_menu_assignment_restaurant1_idx` (`restaurant_id`),
  KEY `fk_menu_assignment_client1_idx` (`client_id`),
  KEY `fk_menu_assignment_restaurant_group1_idx` (`restaurant_group_id`),
  KEY `fk_menu_assignment_restaurant_chain1_idx` (`restaurant_chain_id`),
  KEY `fk_menu_assignment_menu1_idx` (`menu_id`),
  CONSTRAINT `fk_menu_assignment_client1` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_menu_assignment_menu1` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_menu_assignment_restaurant1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_menu_assignment_restaurant_chain1` FOREIGN KEY (`restaurant_chain_id`) REFERENCES `restaurant_chain` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=12727 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `menu_category`
--

DROP TABLE IF EXISTS `menu_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu_category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `menu_id` smallint(5) unsigned NOT NULL,
  `name_key` varchar(250) NOT NULL,
  `reference_name` varchar(250) NOT NULL,
  `description_key` varchar(250) DEFAULT NULL,
  `image_file_name` varchar(500) DEFAULT NULL,
  `is_optional` tinyint(1) NOT NULL DEFAULT '0',
  `sort_order` int(11) NOT NULL DEFAULT '1',
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_menu_category_menu1_idx` (`menu_id`),
  CONSTRAINT `fk_menu_category_menu1` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1652 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `menu_item`
--

DROP TABLE IF EXISTS `menu_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu_item` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `vat_id` int(11) unsigned NOT NULL,
  `menu_category_id` int(11) unsigned NOT NULL,
  `name_key` varchar(250) DEFAULT NULL,
  `restaurant_price` float DEFAULT NULL,
  `web_price` float NOT NULL,
  `description_key` varchar(250) DEFAULT NULL,
  `image_file_name` varchar(500) DEFAULT NULL,
  `nutritional` varchar(500) DEFAULT NULL,
  `cook_time` int(11) DEFAULT NULL,
  `is_imported` tinyint(1) NOT NULL DEFAULT '0',
  `is_alcohol` tinyint(1) NOT NULL DEFAULT '0',
  `sort_order` int(11) NOT NULL DEFAULT '1',
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_menu_item_vat1_idx` (`vat_id`),
  KEY `fk_menu_item_menu_category1_idx` (`menu_category_id`),
  CONSTRAINT `fk_menu_item_menu_category1` FOREIGN KEY (`menu_category_id`) REFERENCES `menu_category` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_menu_item_vat1` FOREIGN KEY (`vat_id`) REFERENCES `vat` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=9951 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `menu_item_allergy`
--

DROP TABLE IF EXISTS `menu_item_allergy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu_item_allergy` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `allergy_id` smallint(5) unsigned NOT NULL,
  `menu_item_id` bigint(20) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_menu_item_allergy_menu_item1_idx` (`menu_item_id`),
  KEY `fk_menu_item_allergy_allergy1_idx1` (`allergy_id`),
  CONSTRAINT `fk_menu_item_allergy_allergy1` FOREIGN KEY (`allergy_id`) REFERENCES `allergy` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_menu_item_allergy_menu_item1` FOREIGN KEY (`menu_item_id`) REFERENCES `menu_item` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=657 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  CONSTRAINT `fk_menu_item_has_user_menu_item1` FOREIGN KEY (`menu_item_id`) REFERENCES `menu_item` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

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
-- Table structure for table `menu_option`
--

DROP TABLE IF EXISTS `menu_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu_option` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` bigint(20) unsigned DEFAULT NULL,
  `copied_from_id` bigint(20) unsigned DEFAULT NULL,
  `menu_item_id` bigint(20) unsigned NOT NULL,
  `menu_option_category_type_id` bigint(20) unsigned DEFAULT NULL,
  `max_category_items` int(11) DEFAULT NULL,
  `name_key` varchar(250) DEFAULT NULL,
  `description_key` varchar(250) DEFAULT NULL,
  `web_price` float DEFAULT NULL,
  `restaurant_price` float DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `sort_order` int(11) NOT NULL DEFAULT '1',
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_menu_option_menu_item1_idx` (`menu_item_id`),
  KEY `fk_menu_option_menu_option_category_type1_idx` (`menu_option_category_type_id`),
  KEY `fk_menu_option_menu_option1_idx` (`parent_id`),
  KEY `fk_menu_option_menu_option2_idx` (`copied_from_id`),
  CONSTRAINT `fk_menu_option_menu_item1` FOREIGN KEY (`menu_item_id`) REFERENCES `menu_item` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_menu_option_menu_option1` FOREIGN KEY (`parent_id`) REFERENCES `menu_option` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_menu_option_menu_option2` FOREIGN KEY (`copied_from_id`) REFERENCES `menu_option` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_menu_option_menu_option_category_type1` FOREIGN KEY (`menu_option_category_type_id`) REFERENCES `menu_option_category_type` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=7082 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `menu_option_category_type`
--

DROP TABLE IF EXISTS `menu_option_category_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu_option_category_type` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name_key` varchar(250) NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `order`
--

DROP TABLE IF EXISTS `order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `restaurant_id` smallint(5) unsigned NOT NULL,
  `restaurant_name` varchar(255) NOT NULL DEFAULT '',
  `delivery_provider` enum('Restaurant','Client') NOT NULL DEFAULT 'Client',
  `sales_fee_value` float NOT NULL DEFAULT '0',
  `sales_fee_type` enum('VatExclusive','VatInclusive') NOT NULL DEFAULT 'VatExclusive',
  `sales_charge_type` enum('WebPrice','RestaurantPrice') NOT NULL DEFAULT 'WebPrice',
  `collection_fee_value` float NOT NULL DEFAULT '0',
  `collection_fee_type` enum('VatExclusive','VatInclusive') NOT NULL DEFAULT 'VatExclusive',
  `collection_charge_type` enum('WebPrice','RestaurantPrice') NOT NULL DEFAULT 'WebPrice',
  `vat_value` float NOT NULL DEFAULT '0',
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `order_number` varchar(50) CHARACTER SET utf8mb4 DEFAULT NULL,
  `is_amend` tinyint(1) NOT NULL DEFAULT '0',
  `postcode` varchar(45) CHARACTER SET utf8mb4 NOT NULL,
  `delivery_type` enum('DeliveryAsap','DeliveryLater','CollectionAsap','CollectionLater') CHARACTER SET utf8mb4 NOT NULL,
  `later_date_from` timestamp NULL DEFAULT NULL,
  `later_date_to` timestamp NULL DEFAULT NULL,
  `member_comment` varchar(500) CHARACTER SET utf8mb4 DEFAULT NULL,
  `restaurant_comment` varchar(500) CHARACTER SET utf8mb4 DEFAULT NULL,
  `internal_comment` varchar(500) DEFAULT NULL,
  `delivery_address_data` text,
  `billing_address_data` text CHARACTER SET utf8mb4,
  `is_utensils` tinyint(1) NOT NULL DEFAULT '0',
  `status` enum('ProcessingPayment','PaymentReceived','TransferringToRestaurant','ReadyBy','OrderConfirmed','FoodPreparing','FoodIsReady','AssignedToDriver','RequestDriver','AcceptByDriver','EstimatedDeliveryTime','WayToPickUp','DriverAtRestaurant','DriverWaiting','DriverPickedUp','FoodEnRoute','ArrivedAtCustomer','Delivered','OrderCancelled','Collected') CHARACTER SET utf8mb4 NOT NULL DEFAULT 'ProcessingPayment',
  `is_corporate` tinyint(1) NOT NULL DEFAULT '0',
  `is_term_cond` tinyint(1) NOT NULL DEFAULT '0',
  `is_term_cond_acc_pol` tinyint(1) NOT NULL DEFAULT '0',
  `is_subscribe_own` tinyint(1) NOT NULL DEFAULT '0',
  `is_subscribe_other` tinyint(1) NOT NULL DEFAULT '0',
  `is_in_dispatch` tinyint(1) NOT NULL DEFAULT '0',
  `food_preparation_time` time DEFAULT NULL,
  `voucher_data` text CHARACTER SET utf8mb4,
  `currency_code` varchar(10) CHARACTER SET utf8mb4 NOT NULL,
  `currency_symbol` varchar(2) NOT NULL DEFAULT '',
  `delivery_charge` float NOT NULL,
  `driver_charge` float DEFAULT NULL,
  `subtotal` float NOT NULL,
  `discount_value` float NOT NULL,
  `total` float NOT NULL,
  `refund_amount` float DEFAULT NULL,
  `restaurant_subtotal` float NOT NULL,
  `restaurant_discount_value` float NOT NULL,
  `restaurant_total` float NOT NULL,
  `restaurant_refund_amount` float DEFAULT NULL,
  `payment_charge` float DEFAULT NULL,
  `estimated_time` time DEFAULT NULL,
  `ready_by` datetime DEFAULT NULL,
  `client_refund` float DEFAULT NULL,
  `restaurant_charge` float DEFAULT NULL,
  `restaurant_refund` float DEFAULT NULL,
  `corporate_client_refund` float DEFAULT NULL,
  `corporate_restaurant_refund` float DEFAULT NULL,
  `client_cost` float DEFAULT NULL,
  `client_received` float DEFAULT NULL,
  `restaurant_credit` float DEFAULT NULL,
  `corp_expense_type_data` text,
  `corp_total_allocated` float DEFAULT NULL,
  `corp_company_data` text,
  `paid` float DEFAULT NULL COMMENT 'Paid by credit card',
  `loyalty_points` smallint(5) DEFAULT NULL,
  `cancellation_reason` varchar(500) DEFAULT NULL,
  `auth_result` varchar(100) DEFAULT NULL,
  `psp_reference` varchar(100) DEFAULT NULL,
  `merchant_reference` varchar(250) DEFAULT NULL,
  `skin_code` varchar(100) DEFAULT NULL,
  `merchant_sig` varchar(250) DEFAULT NULL,
  `payment_method` varchar(100) DEFAULT NULL,
  `record_type` enum('Active','Inactive','Deleted') CHARACTER SET utf8mb4 NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_number_UNIQUE` (`order_number`),
  KEY `fk_order_restaurant1_idx` (`restaurant_id`),
  KEY `fk_order_user1_idx` (`user_id`),
  CONSTRAINT `fk_order_restaurant1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_order_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=125 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`alex.popov`@`%`*/ /*!50003 TRIGGER `order_BEFORE_INSERT`
BEFORE INSERT ON `order`
FOR EACH ROW
BEGIN
	SET new.order_number = (SELECT CONCAT(
		RIGHT(YEAR(CURDATE()),1),
		DATE_FORMAT(CURRENT_TIMESTAMP,'%m%d%H%i'),
		(COUNT(*) + 1)
	)
	FROM `order` where YEAR(CURDATE()) = YEAR(create_on) and MONTH(CURDATE()) = MONTH(create_on) and DAY(CURDATE()) = DAY(create_on));
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `order_contact_history`
--

DROP TABLE IF EXISTS `order_contact_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_contact_history` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('Sms','Phone','Email','Ivr') NOT NULL,
  `name` varchar(255) NOT NULL,
  `number` varchar(50) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `role` varchar(255) NOT NULL,
  `charge` float DEFAULT NULL,
  `delay_in_min` int(5) DEFAULT NULL,
  `is_succeeded` tinyint(1) NOT NULL DEFAULT '0',
  `order_id` bigint(20) unsigned NOT NULL,
  `status` varchar(255) NOT NULL,
  `price` double DEFAULT NULL COMMENT 'price per second',
  `sid` varchar(255) DEFAULT NULL,
  `duration` varchar(255) DEFAULT NULL,
  `price_unit` varchar(5) DEFAULT NULL COMMENT 'price per second',
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_order_ivr_order1_idx` (`order_id`),
  CONSTRAINT `fk_order_ivr_order1` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `order_history`
--

DROP TABLE IF EXISTS `order_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_history` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `status` enum('ProcessingPayment','PaymentReceived','TransferringToRestaurant','ReadyBy','OrderConfirmed','FoodPreparing','FoodIsReady','AssignedToDriver','RequestDriver','AcceptByDriver','EstimatedDeliveryTime','WayToPickUp','DriverAtRestaurant','DriverWaiting','DriverPickedUp','FoodEnRoute','ArrivedAtCustomer','Delivered','OrderCancelled','Collected') NOT NULL DEFAULT 'ProcessingPayment',
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_orderhistory_order1_idx` (`order_id`),
  KEY `fk_orderhistory_user1_idx` (`user_id`),
  CONSTRAINT `fk_orderhistory_order1` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=249 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `order_item`
--

DROP TABLE IF EXISTS `order_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_item` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `menu_item_id` bigint(20) unsigned NOT NULL,
  `web_price` float NOT NULL,
  `restaurant_price` float NOT NULL,
  `is_alcohol` tinyint(1) NOT NULL DEFAULT '0',
  `quantity` int(11) NOT NULL,
  `web_total` float NOT NULL,
  `restaurant_total` float NOT NULL,
  `cook_time` time DEFAULT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_order_item_order1_idx` (`order_id`),
  KEY `fk_order_item_menu_item1_idx` (`menu_item_id`),
  CONSTRAINT `fk_order_item_menu_item1` FOREIGN KEY (`menu_item_id`) REFERENCES `menu_item` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_order_item_order1` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `order_item_group_order_contact`
--

DROP TABLE IF EXISTS `order_item_group_order_contact`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_item_group_order_contact` (
  `order_item_id` bigint(20) unsigned NOT NULL,
  `group_order_contact_id` smallint(5) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`order_item_id`,`group_order_contact_id`),
  KEY `fk_order_item_has_group_order_contact_group_order_contact1_idx` (`group_order_contact_id`),
  CONSTRAINT `fk_order_item_has_group_order_contact_group_order_contact1` FOREIGN KEY (`group_order_contact_id`) REFERENCES `group_order_contact` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_order_item_group_order_contact_order_item1` FOREIGN KEY (`order_item_id`) REFERENCES `order_item` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `order_option`
--

DROP TABLE IF EXISTS `order_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_option` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_item_id` bigint(20) unsigned NOT NULL,
  `menu_option_id` bigint(20) unsigned NOT NULL,
  `web_price` float DEFAULT NULL,
  `restaurant_price` float DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_order_option_menu_option1_idx` (`menu_option_id`),
  KEY `fk_order_option_order_item1_idx` (`order_item_id`),
  CONSTRAINT `fk_order_option_menu_option1` FOREIGN KEY (`menu_option_id`) REFERENCES `menu_option` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_order_option_order_item1` FOREIGN KEY (`order_item_id`) REFERENCES `order_item` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `order_time_track`
--

DROP TABLE IF EXISTS `order_time_track`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_time_track` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
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
-- Table structure for table `page`
--

DROP TABLE IF EXISTS `page`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` smallint(5) unsigned NOT NULL,
  `language_id` int(11) unsigned NOT NULL,
  `title` varchar(150) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `description` varchar(150) DEFAULT NULL,
  `robots` varchar(30) DEFAULT NULL,
  `open_from` datetime NOT NULL,
  `open_to` datetime NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_page_language1_idx` (`language_id`),
  KEY `fk_page_client1_idx` (`client_id`),
  CONSTRAINT `fk_page_client1` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_page_language1` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `payment_notification_history`
--

DROP TABLE IF EXISTS `payment_notification_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment_notification_history` (
  `psp_reference` varchar(100) NOT NULL,
  `merchant_reference` varchar(250) DEFAULT NULL,
  `original_reference` varchar(250) DEFAULT NULL,
  `event_code` varchar(50) NOT NULL,
  `merchant_account_code` varchar(100) DEFAULT NULL,
  `event_date` datetime NOT NULL,
  `success` tinyint(1) NOT NULL,
  `operations` varchar(250) DEFAULT NULL,
  `reason` varchar(250) DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `value` float DEFAULT NULL,
  `live` tinyint(1) NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `postcode`
--

DROP TABLE IF EXISTS `postcode`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `postcode` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `postcode` varchar(20) NOT NULL,
  `latitude` float NOT NULL,
  `longitude` float NOT NULL COMMENT 'ISO 3166-1-alpha-2 code',
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=458897 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `property_assignment`
--

DROP TABLE IF EXISTS `property_assignment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `property_assignment` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` smallint(5) unsigned DEFAULT NULL,
  `restaurant_chain_id` bigint(20) unsigned DEFAULT NULL,
  `restaurant_group_id` bigint(20) unsigned DEFAULT NULL,
  `restaurant_id` smallint(5) unsigned DEFAULT NULL,
  `max_delivery_order_value` int(11) unsigned DEFAULT NULL,
  `min_delivery_order_value` int(11) unsigned DEFAULT NULL,
  `max_delivery_order_amount` int(11) unsigned DEFAULT NULL,
  `min_delivery_order_amount` int(11) unsigned DEFAULT NULL,
  `max_collection_order_value` int(11) unsigned DEFAULT NULL,
  `min_collection_order_value` int(11) unsigned DEFAULT NULL,
  `max_collection_order_amount` int(11) unsigned DEFAULT NULL,
  `min_collection_order_amount` int(11) unsigned DEFAULT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_property_assignment_client1_idx` (`client_id`),
  KEY `fk_property_assignment_restaurant_chain1_idx` (`restaurant_chain_id`),
  KEY `fk_property_assignment_restaurant_group1_idx` (`restaurant_group_id`),
  KEY `fk_property_assignment_restaurant1_idx` (`restaurant_id`),
  CONSTRAINT `fk_property_assignment_client1` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_property_assignment_restaurant1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_property_assignment_restaurant_chain1` FOREIGN KEY (`restaurant_chain_id`) REFERENCES `restaurant_chain` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `report_order`
--

DROP TABLE IF EXISTS `report_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `report_order` (
  `id` bigint(20) unsigned NOT NULL,
  `restaurant_id` smallint(5) unsigned NOT NULL,
  `restaurant_name` varchar(255) NOT NULL DEFAULT '',
  `delivery_provider` enum('Restaurant','Client') NOT NULL DEFAULT 'Client',
  `sales_fee_value` float NOT NULL DEFAULT '0',
  `sales_fee_type` enum('VatExclusive','VatInclusive') NOT NULL DEFAULT 'VatExclusive',
  `sales_charge_type` enum('WebPrice','RestaurantPrice') NOT NULL DEFAULT 'WebPrice',
  `collection_fee_value` float NOT NULL DEFAULT '0',
  `collection_fee_type` enum('VatExclusive','VatInclusive') NOT NULL DEFAULT 'VatExclusive',
  `collection_charge_type` enum('WebPrice','RestaurantPrice') NOT NULL DEFAULT 'WebPrice',
  `vat_value` float NOT NULL DEFAULT '0',
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `order_number` varchar(50) CHARACTER SET utf8mb4 DEFAULT NULL,
  `is_amend` tinyint(1) NOT NULL DEFAULT '0',
  `postcode` varchar(45) CHARACTER SET utf8mb4 NOT NULL,
  `delivery_type` enum('DeliveryAsap','DeliveryLater','CollectionAsap','CollectionLater') CHARACTER SET utf8mb4 NOT NULL,
  `later_date_from` timestamp NULL DEFAULT NULL,
  `later_date_to` timestamp NULL DEFAULT NULL,
  `member_comment` varchar(500) CHARACTER SET utf8mb4 DEFAULT NULL,
  `restaurant_comment` varchar(500) CHARACTER SET utf8mb4 DEFAULT NULL,
  `internal_comment` varchar(500) DEFAULT NULL,
  `delivery_address_data` text,
  `billing_address_data` text CHARACTER SET utf8mb4,
  `is_utensils` tinyint(1) NOT NULL DEFAULT '0',
  `status` enum('ProcessingPayment','PaymentReceived','TransferringToRestaurant','ReadyBy','OrderConfirmed','FoodPreparing','FoodIsReady','AssignedToDriver','RequestDriver','AcceptByDriver','EstimatedDeliveryTime','WayToPickUp','DriverAtRestaurant','DriverWaiting','DriverPickedUp','FoodEnRoute','ArrivedAtCustomer','Delivered','OrderCancelled','Collected') CHARACTER SET utf8mb4 NOT NULL DEFAULT 'ProcessingPayment',
  `is_corporate` tinyint(1) NOT NULL DEFAULT '0',
  `is_term_cond` tinyint(1) NOT NULL DEFAULT '0',
  `is_term_cond_acc_pol` tinyint(1) NOT NULL DEFAULT '0',
  `is_subscribe_own` tinyint(1) NOT NULL DEFAULT '0',
  `is_subscribe_other` tinyint(1) NOT NULL DEFAULT '0',
  `is_in_dispatch` tinyint(1) NOT NULL DEFAULT '0',
  `food_preparation_time` time DEFAULT NULL,
  `voucher_data` text CHARACTER SET utf8mb4,
  `currency_code` varchar(10) CHARACTER SET utf8mb4 NOT NULL,
  `currency_symbol` varchar(2) NOT NULL DEFAULT '',
  `delivery_charge` float NOT NULL,
  `driver_charge` float DEFAULT NULL,
  `subtotal` float NOT NULL,
  `discount_value` float NOT NULL,
  `total` float NOT NULL,
  `refund_amount` float DEFAULT NULL,
  `restaurant_subtotal` float NOT NULL,
  `restaurant_discount_value` float NOT NULL,
  `restaurant_total` float NOT NULL,
  `restaurant_refund_amount` float DEFAULT NULL,
  `payment_charge` float DEFAULT NULL,
  `estimated_time` time NOT NULL,
  `ready_by` datetime DEFAULT NULL,
  `client_refund` float DEFAULT NULL,
  `restaurant_charge` float DEFAULT NULL,
  `restaurant_refund` float DEFAULT NULL,
  `corporate_client_refund` float DEFAULT NULL,
  `corporate_restaurant_refund` float DEFAULT NULL,
  `client_cost` float DEFAULT NULL,
  `client_received` float DEFAULT NULL,
  `restaurant_credit` float DEFAULT NULL,
  `corp_expense_type_data` text,
  `corp_total_allocated` float DEFAULT NULL,
  `corp_company_data` text,
  `paid` float DEFAULT NULL COMMENT 'Paid by credit card',
  `loyalty_points` smallint(5) DEFAULT NULL,
  `cancellation_reason` varchar(500) DEFAULT NULL,
  `auth_result` varchar(100) DEFAULT NULL,
  `psp_reference` varchar(100) DEFAULT NULL,
  `merchant_reference` varchar(250) DEFAULT NULL,
  `skin_code` varchar(100) DEFAULT NULL,
  `merchant_sig` varchar(250) DEFAULT NULL,
  `payment_method` varchar(100) DEFAULT NULL,
  `record_type` enum('Active','Inactive','Deleted') CHARACTER SET utf8mb4 NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_number_UNIQUE` (`order_number`),
  KEY `fk_order_restaurant1_idx` (`restaurant_id`),
  KEY `fk_order_user1_idx` (`user_id`),
  CONSTRAINT `fk_order_restaurant10` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_order_user10` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `restaurant`
--

DROP TABLE IF EXISTS `restaurant`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `restaurant` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` smallint(5) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `slug` varchar(255) DEFAULT NULL,
  `price_range` int(5) NOT NULL,
  `vat_number` varchar(255) NOT NULL,
  `opening_day` timestamp NULL DEFAULT NULL,
  `trading_name` varchar(255) NOT NULL,
  `default_preparation_time` time NOT NULL,
  `default_cook_time` time NOT NULL,
  `logo_file_name` varchar(255) DEFAULT NULL,
  `is_newest` tinyint(1) NOT NULL,
  `seo_title` varchar(255) NOT NULL,
  `meta_text` varchar(1000) NOT NULL,
  `meta_description` varchar(255) NOT NULL,
  `have_app` tinyint(1) NOT NULL DEFAULT '0',
  `is_featured` tinyint(1) NOT NULL,
  `is_from_signup` tinyint(1) DEFAULT NULL,
  `currency_id` bigint(20) unsigned NOT NULL,
  `address_base_id` int(11) unsigned NOT NULL,
  `restaurant_group_id` bigint(20) unsigned DEFAULT NULL,
  `seo_area_id` int(11) unsigned NOT NULL,
  `dispatch_id` bigint(20) DEFAULT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_restaurant_address_base1_idx` (`address_base_id`),
  KEY `fk_restaurant_restaurant_group1_idx` (`restaurant_group_id`),
  KEY `fk_restaurant_seo_area1_idx` (`seo_area_id`),
  KEY `fk_restaurant_currency1_idx` (`currency_id`),
  KEY `fk_restaurant_client1_idx` (`client_id`),
  CONSTRAINT `fk_restaurant_address_base1` FOREIGN KEY (`address_base_id`) REFERENCES `address_base` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_restaurant_client1` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_restaurant_currency1` FOREIGN KEY (`currency_id`) REFERENCES `currency` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_restaurant_seo_area1` FOREIGN KEY (`seo_area_id`) REFERENCES `seo_area` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=314 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `restaurant_address`
--

DROP TABLE IF EXISTS `restaurant_address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `restaurant_address` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `restaurant_id` smallint(5) unsigned NOT NULL,
  `address_id` bigint(20) unsigned NOT NULL,
  `address_type` enum('Physical','Pickup') NOT NULL DEFAULT 'Physical',
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_restaurant_has_address_address1_idx` (`address_id`),
  KEY `fk_restaurant_address_restaurant1_idx` (`restaurant_id`),
  CONSTRAINT `fk_restaurant_address_restaurant1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_restaurant_has_address_address1` FOREIGN KEY (`address_id`) REFERENCES `address` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=561 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `restaurant_best_for_item`
--

DROP TABLE IF EXISTS `restaurant_best_for_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `restaurant_best_for_item` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `restaurant_id` smallint(5) unsigned NOT NULL,
  `best_for_item_id` int(11) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_restaurant_has_best_for_item_best_for_item1_idx` (`best_for_item_id`),
  KEY `fk_restaurant_has_best_for_item_restaurant1_idx` (`restaurant_id`),
  CONSTRAINT `fk_restaurant_has_best_for_item_best_for_item1` FOREIGN KEY (`best_for_item_id`) REFERENCES `best_for_item` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `restaurant_contact`
--

DROP TABLE IF EXISTS `restaurant_contact`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `restaurant_contact` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `restaurant_id` smallint(5) unsigned NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `contact_role` varchar(50) NOT NULL,
  `number` varchar(50) DEFAULT NULL,
  `role` enum('Contact','Billing') NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_restaurant_contact_restaurant1_idx` (`restaurant_id`),
  CONSTRAINT `fk_restaurant_contact_restaurant1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=587 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `restaurant_contact_email`
--

DROP TABLE IF EXISTS `restaurant_contact_email`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `restaurant_contact_email` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(150) NOT NULL,
  `restaurant_contact_id` bigint(20) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_restaurant_contact_email_restaurant_contact1_idx` (`restaurant_contact_id`),
  CONSTRAINT `fk_restaurant_contact_email_restaurant_contact1` FOREIGN KEY (`restaurant_contact_id`) REFERENCES `restaurant_contact` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2505 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `restaurant_contact_order`
--

DROP TABLE IF EXISTS `restaurant_contact_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `restaurant_contact_order` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `restaurant_id` smallint(5) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `number` varchar(50) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `role` varchar(255) NOT NULL,
  `type` enum('Sms','Phone','Email','Ivr') NOT NULL,
  `charge` float DEFAULT NULL,
  `delay_in_min` int(5) DEFAULT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_restaurant_contact_order_restaurant1_idx` (`restaurant_id`),
  CONSTRAINT `fk_restaurant_contact_order_restaurant1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=434 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `restaurant_cuisine`
--

DROP TABLE IF EXISTS `restaurant_cuisine`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `restaurant_cuisine` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `restaurant_id` smallint(5) unsigned NOT NULL,
  `cuisine_id` int(11) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_restaurant_has_cuisine_cuisine1_idx` (`cuisine_id`),
  KEY `fk_restaurant_cuisine_restaurant1_idx` (`restaurant_id`),
  CONSTRAINT `fk_restaurant_has_cuisine_cuisine1` FOREIGN KEY (`cuisine_id`) REFERENCES `cuisine` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_restaurant_cuisine_restaurant1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=93 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `restaurant_delivery`
--

DROP TABLE IF EXISTS `restaurant_delivery`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `restaurant_delivery` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` smallint(5) unsigned DEFAULT NULL,
  `restaurant_chain_id` bigint(20) unsigned DEFAULT NULL,
  `restaurant_group_id` bigint(20) unsigned DEFAULT NULL,
  `restaurant_id` smallint(5) unsigned DEFAULT NULL,
  `driver_instructions` varchar(500) DEFAULT NULL,
  `driving_instructions` varchar(500) DEFAULT NULL,
  `has_collection` tinyint(1) NOT NULL DEFAULT '0',
  `has_dinein` tinyint(1) NOT NULL DEFAULT '0',
  `has_own` tinyint(1) NOT NULL DEFAULT '0',
  `range` float NOT NULL,
  `fixed_charge` float DEFAULT NULL,
  `collect_time_in_min` float DEFAULT NULL,
  `rate_type` enum('Free','Fixed','Float') DEFAULT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_restaurant_delivery_restaurant1_idx` (`restaurant_id`),
  KEY `fk_restaurant_delivery_restaurant_group1_idx` (`restaurant_group_id`),
  KEY `fk_restaurant_delivery_restaurant_chain1_idx` (`restaurant_chain_id`),
  KEY `fk_restaurant_delivery_client1_idx` (`client_id`),
  CONSTRAINT `fk_restaurant_delivery_client1` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_restaurant_delivery_restaurant1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_restaurant_delivery_restaurant_chain1` FOREIGN KEY (`restaurant_chain_id`) REFERENCES `restaurant_chain` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_restaurant_delivery_restaurant_group1` FOREIGN KEY (`restaurant_group_id`) REFERENCES `restaurant_group` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=95 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `restaurant_delivery_charge`
--

DROP TABLE IF EXISTS `restaurant_delivery_charge`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `restaurant_delivery_charge` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `distance_in_miles` float NOT NULL,
  `charge` float NOT NULL,
  `restaurant_delivery_id` bigint(20) unsigned NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_restaurant_delivery_charges_restaurant_delivery1_idx` (`restaurant_delivery_id`),
  CONSTRAINT `fk_restaurant_delivery_charges_restaurant_delivery1` FOREIGN KEY (`restaurant_delivery_id`) REFERENCES `restaurant_delivery` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=130 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `restaurant_group`
--

DROP TABLE IF EXISTS `restaurant_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `restaurant_group` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `restaurant_chain_id` bigint(20) unsigned NOT NULL,
  `parent_id` bigint(20) unsigned DEFAULT NULL,
  `name_key` varchar(150) NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE key` (`name_key`),
  KEY `fk_restaurant_group_restaurant_chain1_idx` (`restaurant_chain_id`),
  KEY `fk_restaurant_group_restaurant_group1_idx` (`parent_id`),
  CONSTRAINT `fk_restaurant_group_restaurant_chain1` FOREIGN KEY (`restaurant_chain_id`) REFERENCES `restaurant_chain` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_restaurant_group_restaurant_group1` FOREIGN KEY (`parent_id`) REFERENCES `restaurant_group` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=108 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `restaurant_payment`
--

DROP TABLE IF EXISTS `restaurant_payment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `restaurant_payment` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `restaurant_id` smallint(5) unsigned NOT NULL,
  `type` enum('Bank','Cash') NOT NULL,
  `account_holder_name` varchar(150) DEFAULT NULL,
  `bank_name` varchar(150) DEFAULT NULL,
  `sort_code` varchar(50) DEFAULT NULL,
  `account_number` varchar(50) DEFAULT NULL,
  `sales_fee_value` float NOT NULL,
  `sales_fee_type` enum('VatExclusive','VatInclusive') NOT NULL,
  `sales_charge_type` enum('WebPrice','RestaurantPrice') NOT NULL,
  `collection_fee_value` float NOT NULL,
  `collection_fee_type` enum('VatExclusive','VatInclusive') NOT NULL,
  `collection_charge_type` enum('WebPrice','RestaurantPrice') NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_restaurant_payment_restaurant1_idx` (`restaurant_id`),
  CONSTRAINT `fk_restaurant_payment_restaurant1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=240 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `restaurant_photo`
--

DROP TABLE IF EXISTS `restaurant_photo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `restaurant_photo` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `restaurant_id` smallint(5) unsigned NOT NULL,
  `image_name` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_restaurant_photo_restaurant1_idx1` (`restaurant_id`),
  CONSTRAINT `fk_restaurant_photo_restaurant1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=298 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `restaurant_schedule`
--

DROP TABLE IF EXISTS `restaurant_schedule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `restaurant_schedule` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` smallint(5) unsigned DEFAULT NULL,
  `restaurant_chain_id` bigint(20) unsigned DEFAULT NULL,
  `restaurant_group_id` bigint(20) unsigned DEFAULT NULL,
  `restaurant_id` smallint(5) unsigned DEFAULT NULL,
  `from` time DEFAULT NULL,
  `to` time DEFAULT NULL,
  `type` enum('OpenTime','DeliveryTime') NOT NULL,
  `day` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') NOT NULL,
  `day_time_type` enum('Morning','Evening') NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_restaurant_schedule_restaurant1_idx` (`restaurant_id`),
  KEY `fk_restaurant_schedule_restaurant_group1_idx` (`restaurant_group_id`),
  KEY `fk_restaurant_schedule_restaurant_chain1_idx` (`restaurant_chain_id`),
  KEY `fk_restaurant_schedule_client1_idx` (`client_id`),
  CONSTRAINT `fk_restaurant_schedule_client1` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_restaurant_schedule_restaurant1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_restaurant_schedule_restaurant_chain1` FOREIGN KEY (`restaurant_chain_id`) REFERENCES `restaurant_chain` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_restaurant_schedule_restaurant_group1` FOREIGN KEY (`restaurant_group_id`) REFERENCES `restaurant_group` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3146 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `seo_area`
--

DROP TABLE IF EXISTS `seo_area`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `seo_area` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `seo_name` varchar(255) NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=190 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` smallint(5) unsigned DEFAULT NULL,
  `company_id` int(11) unsigned DEFAULT NULL,
  `restaurant_chain_id` bigint(20) unsigned DEFAULT NULL,
  `restaurant_group_id` bigint(20) unsigned DEFAULT NULL,
  `company_user_group_id` smallint(5) unsigned DEFAULT NULL,
  `restaurant_id` smallint(5) unsigned DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` enum('Member','CorporateMember','Admin','RestaurantAdmin','RestaurantGroupAdmin','RestaurantChainAdmin','CorporateAdmin','RestaurantTeam','Finance','ClientAdmin') NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `title` varchar(45) DEFAULT NULL,
  `last_visit` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `activation_hash` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `know_about` varchar(255) DEFAULT NULL,
  `term_and_cond` tinyint(1) NOT NULL DEFAULT '0',
  `term_and_cond_web` tinyint(1) NOT NULL DEFAULT '0',
  `term_and_cond_acc_pol` tinyint(1) NOT NULL DEFAULT '0',
  `is_corporate_approved` tinyint(1) NOT NULL DEFAULT '0',
  `api_token` varchar(255) DEFAULT NULL,
  `reset_password_hash` varchar(255) DEFAULT NULL,
  `affiliate_id` int(11) unsigned DEFAULT NULL,
  `loyalty_points` smallint(5) DEFAULT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_user_restaurant1_idx` (`restaurant_id`),
  KEY `fk_user_restaurant_chain1_idx` (`restaurant_chain_id`),
  KEY `fk_user_restaurant_group1_idx` (`restaurant_group_id`),
  KEY `fk_user_client1_idx` (`client_id`),
  KEY `fk_user_company1_idx` (`company_id`),
  KEY `fk_user_company_user_group1_idx` (`company_user_group_id`),
  CONSTRAINT `fk_user_client1` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_company1` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_company_user_group1` FOREIGN KEY (`company_user_group_id`) REFERENCES `company_user_group` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_restaurant1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_restaurant_chain1` FOREIGN KEY (`restaurant_chain_id`) REFERENCES `restaurant_chain` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=255 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_address`
--

DROP TABLE IF EXISTS `user_address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_address` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `address_id` bigint(20) unsigned NOT NULL,
  `address_type` enum('Billing','Delivery','Primary') NOT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_user_has_address_address1_idx` (`address_id`),
  KEY `fk_user_has_address_user1_idx` (`user_id`),
  CONSTRAINT `fk_user_has_address_address1` FOREIGN KEY (`address_id`) REFERENCES `address` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_has_address_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=183 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `vat`
--

DROP TABLE IF EXISTS `vat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vat` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('Zero','Standard') NOT NULL,
  `value` float NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `voucher`
--

DROP TABLE IF EXISTS `voucher`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `voucher` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `menu_item_id` bigint(20) unsigned DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `client_id` smallint(5) unsigned DEFAULT NULL,
  `restaurant_id` smallint(5) unsigned DEFAULT NULL,
  `restaurant_chain_id` bigint(20) unsigned DEFAULT NULL,
  `restaurant_group_id` bigint(20) unsigned DEFAULT NULL,
  `code` varchar(255) NOT NULL,
  `category` enum('Free','Delivery','Wine','Food','All','Menu Items','Eat food late','Food Price') NOT NULL DEFAULT 'All',
  `discount_value` float DEFAULT NULL,
  `discount_type` enum('Discount','Price') DEFAULT NULL,
  `promotion_type` enum('Client','Restaurant') NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `value_type` enum('Fixed','Percent') DEFAULT NULL,
  `price_value` float DEFAULT NULL,
  `item_quantity` int(11) DEFAULT NULL,
  `description` text NOT NULL,
  `order_after` varchar(255) DEFAULT NULL,
  `max_times_per_user` int(11) NOT NULL,
  `generate_by` enum('M','A') NOT NULL DEFAULT 'M',
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_voucher_menu_item1_idx` (`menu_item_id`),
  KEY `fk_voucher_user1_idx` (`user_id`),
  KEY `fk_voucher_client1_idx` (`client_id`),
  KEY `fk_voucher_restaurant1_idx` (`restaurant_id`),
  KEY `fk_voucher_restaurant_chain1_idx` (`restaurant_chain_id`),
  KEY `fk_voucher_restaurant_group1_idx` (`restaurant_group_id`),
  CONSTRAINT `fk_voucher_client1` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_voucher_menu_item1` FOREIGN KEY (`menu_item_id`) REFERENCES `menu_item` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_voucher_restaurant1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_voucher_restaurant_chain1` FOREIGN KEY (`restaurant_chain_id`) REFERENCES `restaurant_chain` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_voucher_restaurant_group1` FOREIGN KEY (`restaurant_group_id`) REFERENCES `restaurant_group` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_voucher_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=587 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `voucher_use_history`
--

DROP TABLE IF EXISTS `voucher_use_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `voucher_use_history` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `voucher_id` int(11) unsigned DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `record_type` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `create_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_voucher_use_history_voucher1_idx` (`voucher_id`),
  KEY `fk_voucher_use_history_user1_idx` (`user_id`),
  CONSTRAINT `fk_voucher_use_history_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_voucher_use_history_voucher1` FOREIGN KEY (`voucher_id`) REFERENCES `voucher` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-06-11 12:23:23
