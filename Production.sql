-- MySQL dump 10.13  Distrib 5.6.24, for osx10.8 (x86_64)
--
-- Host: localhost    Database: Production
-- ------------------------------------------------------
-- Server version	5.6.24

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
-- Table structure for table `affiliate_aggregation`
--

DROP TABLE IF EXISTS `affiliate_aggregation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `affiliate_aggregation` (
  `affiliate_id` int(11) NOT NULL,
  `revenue` int(11) DEFAULT NULL,
  PRIMARY KEY (`affiliate_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `affiliates`
--

DROP TABLE IF EXISTS `affiliates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `affiliates` (
  `affiliate_id` int(11) NOT NULL,
  `company` varchar(45) DEFAULT NULL,
  `twitterUsername` varchar(45) DEFAULT NULL,
  `blogUrl` varchar(45) DEFAULT NULL,
  `website` varchar(45) DEFAULT NULL,
  `status` varchar(45) DEFAULT NULL,
  `joined` datetime DEFAULT NULL,
  `dateOfBirth` datetime DEFAULT NULL,
  `signupIp` varchar(45) DEFAULT NULL,
  `facebookpage` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`affiliate_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `test`
--

DROP TABLE IF EXISTS `test`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `test` (
  `id` int(11) NOT NULL,
  `test` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `transaction_aggregation`
--

DROP TABLE IF EXISTS `transaction_aggregation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transaction_aggregation` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `month` int(30) DEFAULT '0',
  `day` int(30) DEFAULT '0',
  `last_transaction_id` int(30) DEFAULT '0',
  `commission_average` int(30) DEFAULT '0',
  `commission_sum` int(30) DEFAULT '0',
  `sale_average` int(30) DEFAULT '0',
  `sale_sum` int(30) DEFAULT '0',
  `transaction_count` int(30) DEFAULT '0',
  `last_aggregated_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `transaction_spool`
--

DROP TABLE IF EXISTS `transaction_spool`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transaction_spool` (
  `id` int(11) NOT NULL,
  `affiliate_id` int(11) NOT NULL,
  `merchant_id` int(11) NOT NULL,
  `groupID` int(11) NOT NULL,
  `bannerID` int(11) NOT NULL,
  `product_id` int(30) NOT NULL,
  `clickThroughTime` datetime NOT NULL,
  `date` datetime NOT NULL,
  `commission_status` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `ip` varchar(45) NOT NULL,
  `referer` varchar(45) DEFAULT NULL,
  `commission` double NOT NULL,
  `extra` varchar(45) DEFAULT NULL,
  `paid_status` int(11) NOT NULL,
  `click_ref` varchar(45) DEFAULT NULL,
  `com_date` datetime DEFAULT NULL,
  `second_tier_ref` int(11) NOT NULL,
  `sale_amount` double DEFAULT NULL,
  `payment_id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `source` int(11) DEFAULT NULL,
  `is_membership_soft` int(11) NOT NULL,
  `platform` varchar(45) DEFAULT NULL,
  `checksum` varchar(45) NOT NULL,
  `voucher_codes` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `transactions201603`
--

DROP TABLE IF EXISTS `transactions201603`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transactions201603` (
  `id` int(11) NOT NULL,
  `affiliate_id` int(11) NOT NULL,
  `merchant_id` int(11) NOT NULL,
  `groupID` int(11) NOT NULL,
  `bannerID` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `clickThroughTime` datetime NOT NULL,
  `date` datetime NOT NULL,
  `commission_status` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `referer` varchar(45) DEFAULT NULL,
  `commission` int(11) NOT NULL,
  `extra` varchar(45) NOT NULL,
  `paid_status` int(11) DEFAULT NULL,
  `click_ref` varchar(45) DEFAULT NULL,
  `com_date` datetime DEFAULT NULL,
  `second_tier` int(11) DEFAULT NULL,
  `sale_amount` int(11) NOT NULL,
  `payment_id` int(11) DEFAULT NULL,
  `invoice_id` int(11) DEFAULT NULL,
  `source` int(11) NOT NULL,
  `is_membership_soft` int(11) DEFAULT NULL,
  `platform` varchar(45) NOT NULL,
  `checksum` varchar(45) DEFAULT NULL,
  `voucher_code` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-03-14 19:54:33
