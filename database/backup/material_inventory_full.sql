-- MySQL dump 10.13  Distrib 8.4.10, for Linux (x86_64)
--
-- Host: localhost    Database: material_inventory
-- ------------------------------------------------------
-- Server version	8.4.10-0ubuntu0.26.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `activity_logs`
--

DROP TABLE IF EXISTS `activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activity_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `user_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_role` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action_description` text COLLATE utf8mb4_unicode_ci,
  `document_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `document_id` bigint unsigned DEFAULT NULL,
  `document_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `module` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pc_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `browser` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `operating_system` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `old_values` json DEFAULT NULL,
  `new_values` json DEFAULT NULL,
  `location_id` bigint unsigned DEFAULT NULL,
  `location_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activity_logs_location_id_foreign` (`location_id`),
  KEY `activity_logs_user_id_index` (`user_id`),
  KEY `activity_logs_action_type_index` (`action_type`),
  KEY `activity_logs_document_type_index` (`document_type`),
  KEY `activity_logs_document_id_index` (`document_id`),
  KEY `activity_logs_created_at_index` (`created_at`),
  CONSTRAINT `activity_logs_location_id_foreign` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE SET NULL,
  CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=160 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_logs`
--

LOCK TABLES `activity_logs` WRITE;
/*!40000 ALTER TABLE `activity_logs` DISABLE KEYS */;
INSERT INTO `activity_logs` VALUES (1,1,'Administrator','admin@mims.com','admin','CREATE','Transaction GRV created: PPC Cement 50kg (120 Bag)','TRANSACTION',74,'TRX-20260814014939-325','Transactions','127.0.0.1','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,'{\"id\": 74, \"item_id\": \"1\", \"remarks\": null, \"quantity\": \"120.00\", \"created_at\": \"2026-08-13T22:49:39.000000Z\", \"created_by\": 1, \"updated_at\": \"2026-08-13T22:49:39.000000Z\", \"to_location_id\": \"8\", \"document_number\": \"787775\", \"from_location_id\": null, \"reference_number\": \"745544\", \"transaction_date\": \"2026-08-13T21:00:00.000000Z\", \"transaction_type\": \"GRV\", \"transaction_number\": \"TRX-20260814014939-325\"}',8,'Ayat 40/60 Condominium','2026-08-13 22:49:39','2026-08-13 22:49:39'),(2,1,'Administrator','admin@mims.com','admin','VIEW','Transaction viewed: TRX-20260814014658-782','TRANSACTION',70,'TRX-20260814014658-782','Transactions','127.0.0.1','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-13 22:59:31','2026-08-13 22:59:31'),(3,1,'Administrator','admin@mims.com','admin','VIEW','Transaction viewed: TRX-20260813141932-227','TRANSACTION',68,'TRX-20260813141932-227','Transactions','127.0.0.1','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-13 22:59:44','2026-08-13 22:59:44'),(4,1,'Administrator','admin@mims.com','admin','UPDATE','Transaction updated: TRX-20260813141932-227','TRANSACTION',68,'TRX-20260813141932-227','Transactions','127.0.0.1','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux','{\"id\": 68, \"item_id\": 2, \"remarks\": null, \"quantity\": \"50.00\", \"created_at\": \"2026-08-13T11:19:32.000000Z\", \"created_by\": 1, \"deleted_at\": null, \"updated_at\": \"2026-08-13T11:19:32.000000Z\", \"updated_by\": null, \"to_location_id\": 3, \"document_number\": \"9865\", \"from_location_id\": 1, \"reference_number\": \"46546\", \"transaction_date\": \"2026-08-12T21:00:00.000000Z\", \"transaction_type\": \"TRANSFER_OUT\", \"transaction_number\": \"TRX-20260813141932-227\"}','{\"id\": 68, \"item_id\": \"2\", \"remarks\": null, \"quantity\": \"50.00\", \"created_at\": \"2026-08-13T11:19:32.000000Z\", \"created_by\": 1, \"deleted_at\": null, \"updated_at\": \"2026-08-13T23:00:04.000000Z\", \"updated_by\": 1, \"to_location_id\": \"14\", \"document_number\": \"9865\", \"from_location_id\": \"9\", \"reference_number\": \"46546\", \"transaction_date\": \"2026-08-13T21:00:00.000000Z\", \"transaction_type\": \"TRANSFER_OUT\", \"transaction_number\": \"TRX-20260813141932-227\"}',NULL,NULL,'2026-08-13 23:00:04','2026-08-13 23:00:04'),(5,1,'Administrator','admin@mims.com','admin','EXPORT','Weekly Stock Status Report exported','REPORT',NULL,'Weekly Stock Status Report','Reports','127.0.0.1','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-13 23:02:16','2026-08-13 23:02:16'),(6,1,'Administrator','admin@mims.com','admin','VIEW','Item viewed: Aggregate 20mm','ITEM',12,'Aggregate 20mm','Items Management','127.0.0.1','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-13 23:07:45','2026-08-13 23:07:45'),(7,1,'Administrator','admin@mims.com','admin','VIEW','Item viewed: Aggregate 20mm','ITEM',12,'Aggregate 20mm','Items Management','127.0.0.1','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-13 23:10:01','2026-08-13 23:10:01'),(8,1,'Administrator','admin@mims.com','admin','VIEW','Item viewed: PPC Cement 50kg','ITEM',1,'PPC Cement 50kg','Items Management','127.0.0.1','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-13 23:10:07','2026-08-13 23:10:07'),(9,1,'Administrator','admin@mims.com','admin','VIEW','Item viewed: Aggregate 10mm','ITEM',13,'Aggregate 10mm','Items Management','127.0.0.1','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-13 23:10:20','2026-08-13 23:10:20'),(10,1,'Administrator','admin@mims.com','admin','VIEW','Item viewed: OPC Cement 50kg','ITEM',2,'OPC Cement 50kg','Items Management','127.0.0.1','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-13 23:10:32','2026-08-13 23:10:32'),(11,1,'Administrator','admin@mims.com','admin','VIEW','Item viewed: Aggregate 10mm','ITEM',13,'Aggregate 10mm','Items Management','127.0.0.1','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-13 23:10:46','2026-08-13 23:10:46'),(12,1,'Administrator','admin@mims.com','admin','VIEW','Item viewed: Gravel Base Course','ITEM',14,'Gravel Base Course','Items Management','127.0.0.1','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-13 23:11:06','2026-08-13 23:11:06'),(13,1,'Administrator','admin@mims.com','admin','VIEW','Item viewed: Waterproofing Compound','ITEM',16,'Waterproofing Compound','Items Management','127.0.0.1','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-13 23:11:18','2026-08-13 23:11:18'),(14,1,'Administrator','admin@mims.com','admin','VIEW','Item viewed: Gravel Base Course','ITEM',14,'Gravel Base Course','Items Management','127.0.0.1','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-13 23:12:40','2026-08-13 23:12:40'),(15,1,'Administrator','admin@mims.com','admin','UPDATE','Item price updated: Aggregate 20mm (ETB 1800.00 → ETB 1850.00)','ITEM',12,'Aggregate 20mm','Items Management','127.0.0.1','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-13 23:13:49','2026-08-13 23:13:49'),(16,1,'Administrator','admin@mims.com','admin','VIEW','Transaction viewed: TRX-20260812123026-615','TRANSACTION',44,'TRX-20260812123026-615','Transactions','127.0.0.1','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-13 23:14:47','2026-08-13 23:14:47'),(17,1,'Administrator','admin@mims.com','admin','LOGOUT','User logged out',NULL,NULL,NULL,'Authentication','127.0.0.1','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-13 23:19:07','2026-08-13 23:19:07'),(18,8,'Master Data Manager','masterdata@mims.com','master_data','LOGIN','User logged in successfully',NULL,NULL,NULL,'Authentication','127.0.0.1','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-13 23:19:10','2026-08-13 23:19:10'),(19,8,'Master Data Manager','masterdata@mims.com','master_data','UPDATE','Item price updated: Aggregate 20mm (ETB 1850.00 → ETB 1850.00)','ITEM',12,'Aggregate 20mm','Items Management','127.0.0.1','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-13 23:20:14','2026-08-13 23:20:14'),(20,8,'Master Data Manager','masterdata@mims.com','master_data','LOGOUT','User logged out',NULL,NULL,NULL,'Authentication','127.0.0.1','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-13 23:21:03','2026-08-13 23:21:03'),(21,8,'Master Data Manager','masterdata@mims.com','master_data','LOGIN','User logged in successfully',NULL,NULL,NULL,'Authentication','127.0.0.1','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(22,8,'Master Data Manager','masterdata@mims.com','master_data','LOGOUT','User logged out',NULL,NULL,NULL,'Authentication','127.0.0.1','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-13 23:21:47','2026-08-13 23:21:47'),(23,1,'Administrator','admin@mims.com','admin','LOGIN','User logged in successfully',NULL,NULL,NULL,'Authentication','127.0.0.1','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-13 23:21:50','2026-08-13 23:21:50'),(24,1,'Administrator','admin@mims.com','admin','EXPORT','Delivery Report exported','REPORT',NULL,'Delivery Report','Reports','127.0.0.1','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-13 23:22:06','2026-08-13 23:22:06'),(25,1,'Administrator','admin@mims.com','admin','EXPORT','Delivery Report exported','REPORT',NULL,'Delivery Report','Reports','127.0.0.1','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-13 23:23:52','2026-08-13 23:23:52'),(26,1,'Administrator','admin@mims.com','admin','EXPORT','Delivery Report exported','REPORT',NULL,'Delivery Report','Reports','127.0.0.1','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-13 23:24:33','2026-08-13 23:24:33'),(27,1,'Administrator','admin@mims.com','admin','EXPORT','Delivery Report exported','REPORT',NULL,'Delivery Report','Reports','127.0.0.1','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-13 23:26:08','2026-08-13 23:26:08'),(28,1,'Administrator','admin@mims.com','admin','EXPORT','Delivery Report exported','REPORT',NULL,'Delivery Report','Reports','127.0.0.1','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-13 23:28:20','2026-08-13 23:28:20'),(29,1,'Administrator','admin@mims.com','admin','EXPORT','Delivery Report exported','REPORT',NULL,'Delivery Report','Reports','127.0.0.1','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-13 23:28:30','2026-08-13 23:28:30'),(30,1,'Administrator','admin@mims.com','admin','EXPORT','Weekly Stock Status Report exported','REPORT',NULL,'Weekly Stock Status Report','Reports','127.0.0.1','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-13 23:29:19','2026-08-13 23:29:19'),(31,1,'Administrator','admin@mims.com','admin','EXPORT','Weekly Stock Status Report exported','REPORT',NULL,'Weekly Stock Status Report','Reports','127.0.0.1','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-13 23:31:10','2026-08-13 23:31:10'),(32,1,'Administrator','admin@mims.com','admin','EXPORT','Weekly Stock Status Report exported','REPORT',NULL,'Weekly Stock Status Report','Reports','127.0.0.1','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-13 23:32:09','2026-08-13 23:32:09'),(33,1,'Administrator','admin@mims.com','admin','LOGOUT','User logged out',NULL,NULL,NULL,'Authentication','127.0.0.1','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-13 23:33:13','2026-08-13 23:33:13'),(34,1,'Administrator','admin@mims.com','admin','LOGIN','User logged in successfully',NULL,NULL,NULL,'Authentication','127.0.0.1','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-13 23:33:14','2026-08-13 23:33:14'),(35,1,'Administrator','admin@mims.com','admin','LOGOUT','User logged out',NULL,NULL,NULL,'Authentication','127.0.0.1','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-13 23:39:52','2026-08-13 23:39:52'),(36,2,'Site Storekeeper','storekeeper@mims.com','storekeeper','LOGIN','User logged in successfully',NULL,NULL,NULL,'Authentication','127.0.0.1','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-13 23:40:06','2026-08-13 23:40:06'),(37,2,'Site Storekeeper','storekeeper@mims.com','storekeeper','CREATE','Transaction ISTRV created: White Cement 25kg (55 Bag)','TRANSACTION',75,'TRX-20260814024031-315','Transactions','127.0.0.1','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,'{\"id\": 75, \"item_id\": \"3\", \"remarks\": null, \"quantity\": \"55.00\", \"created_at\": \"2026-08-13T23:40:31.000000Z\", \"created_by\": 2, \"updated_at\": \"2026-08-13T23:40:31.000000Z\", \"to_location_id\": \"84\", \"document_number\": null, \"from_location_id\": \"29\", \"reference_number\": \"gh44333\", \"transaction_date\": \"2026-08-13T21:00:00.000000Z\", \"transaction_type\": \"ISTRV\", \"transaction_number\": \"TRX-20260814024031-315\"}',84,'Sub @ Bishoftu Int. Airport','2026-08-13 23:40:31','2026-08-13 23:40:31'),(38,2,'Site Storekeeper','storekeeper@mims.com','storekeeper','VIEW','Transaction viewed: TRX-20260814024031-315','TRANSACTION',75,'TRX-20260814024031-315','Transactions','127.0.0.1','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-13 23:40:38','2026-08-13 23:40:38'),(39,2,'Site Storekeeper','storekeeper@mims.com','storekeeper','LOGOUT','User logged out',NULL,NULL,NULL,'Authentication','127.0.0.1','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-13 23:42:32','2026-08-13 23:42:32'),(40,2,'Site Storekeeper','storekeeper@mims.com','storekeeper','LOGIN','User logged in successfully',NULL,NULL,NULL,'Authentication','127.0.0.1','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-13 23:42:46','2026-08-13 23:42:46'),(41,2,'Site Storekeeper','storekeeper@mims.com','storekeeper','LOGOUT','User logged out',NULL,NULL,NULL,'Authentication','127.0.0.1','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-13 23:43:16','2026-08-13 23:43:16'),(42,1,'Administrator','admin@mims.com','admin','LOGIN','User logged in successfully',NULL,NULL,NULL,'Authentication','127.0.0.1','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-13 23:43:19','2026-08-13 23:43:19'),(43,1,'Administrator','admin@mims.com','admin','LOGOUT','User logged out',NULL,NULL,NULL,'Authentication','127.0.0.1','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-13 23:49:59','2026-08-13 23:49:59'),(44,1,'Administrator','admin@mims.com','admin','LOGIN','User logged in successfully',NULL,NULL,NULL,'Authentication','127.0.0.1','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-13 23:50:01','2026-08-13 23:50:01'),(45,1,'Administrator','admin@mims.com','admin','LOGOUT','User logged out',NULL,NULL,NULL,'Authentication','127.0.0.1','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-13 23:52:52','2026-08-13 23:52:52'),(46,1,'Administrator','admin@mims.com','admin','LOGIN','User logged in successfully',NULL,NULL,NULL,'Authentication','127.0.0.1','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-13 23:52:55','2026-08-13 23:52:55'),(47,1,'Administrator','admin@mims.com','admin','LOGIN','User logged in successfully',NULL,NULL,NULL,'Authentication','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-14 10:51:45','2026-08-14 10:51:45'),(48,1,'Administrator','admin@mims.com','admin','LOGOUT','User logged out',NULL,NULL,NULL,'Authentication','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-14 10:52:05','2026-08-14 10:52:05'),(49,1,'Administrator','admin@mims.com','admin','LOGIN','User logged in successfully',NULL,NULL,NULL,'Authentication','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-15 07:43:45','2026-08-15 07:43:45'),(50,1,'Administrator','admin@mims.com','admin','VIEW','Transaction viewed: TRX-20260814024031-315','TRANSACTION',75,'TRX-20260814024031-315','Transactions','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-15 07:46:37','2026-08-15 07:46:37'),(51,1,'Administrator','admin@mims.com','admin','EXPORT','Project Ledger exported','REPORT',NULL,'Project Material Ledger','Reports','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-15 07:47:13','2026-08-15 07:47:13'),(52,1,'Administrator','admin@mims.com','admin','LOGIN','User logged in successfully',NULL,NULL,NULL,'Authentication','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-17 06:05:40','2026-08-17 06:05:40'),(53,1,'Administrator','admin@mims.com','admin','VIEW','Transaction viewed: TRX-20260814014718-575','TRANSACTION',73,'TRX-20260814014718-575','Transactions','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-17 06:06:33','2026-08-17 06:06:33'),(54,1,'Administrator','admin@mims.com','admin','EXPORT','Delivery Report exported','REPORT',NULL,'Delivery Report','Reports','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-17 06:07:48','2026-08-17 06:07:48'),(55,1,'Administrator','admin@mims.com','admin','EXPORT','Delivery Report exported','REPORT',NULL,'Delivery Report','Reports','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-17 06:08:08','2026-08-17 06:08:08'),(56,1,'Administrator','admin@mims.com','admin','VIEW','Transaction viewed: TRX-20260813141932-227','TRANSACTION',68,'TRX-20260813141932-227','Transactions','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-17 06:23:51','2026-08-17 06:23:51'),(57,1,'Administrator','admin@mims.com','admin','VIEW','Transaction viewed: TRX-20260814024031-315','TRANSACTION',75,'TRX-20260814024031-315','Transactions','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-17 06:23:56','2026-08-17 06:23:56'),(58,1,'Administrator','admin@mims.com','admin','VIEW','Transaction viewed: TRX-20260814014939-325','TRANSACTION',74,'TRX-20260814014939-325','Transactions','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-17 06:23:59','2026-08-17 06:23:59'),(59,1,'Administrator','admin@mims.com','admin','VIEW','Transaction viewed: TRX-20260814014718-575','TRANSACTION',73,'TRX-20260814014718-575','Transactions','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-17 06:24:01','2026-08-17 06:24:01'),(60,1,'Administrator','admin@mims.com','admin','VIEW','Transaction viewed: TRX-20260812123026-353','TRANSACTION',59,'TRX-20260812123026-353','Transactions','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-17 07:58:20','2026-08-17 07:58:20'),(61,1,'Administrator','admin@mims.com','admin','VIEW','Transaction viewed: TRX-20260812140049-152','TRANSACTION',66,'TRX-20260812140049-152','Transactions','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-17 07:58:24','2026-08-17 07:58:24'),(62,1,'Administrator','admin@mims.com','admin','VIEW','Transaction viewed: TRX-20260812140049-152','TRANSACTION',66,'TRX-20260812140049-152','Transactions','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-17 07:58:28','2026-08-17 07:58:28'),(63,1,'Administrator','admin@mims.com','admin','UPDATE','Transaction updated: TRX-20260812140049-152','TRANSACTION',66,'TRX-20260812140049-152','Transactions','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux','{\"id\": 66, \"item_id\": 1, \"remarks\": null, \"quantity\": \"52.00\", \"created_at\": \"2026-08-12T11:00:49.000000Z\", \"created_by\": 1, \"deleted_at\": null, \"updated_at\": \"2026-08-13T21:01:40.000000Z\", \"updated_by\": 1, \"to_location_id\": 4, \"document_number\": \"9865\", \"from_location_id\": 4, \"reference_number\": \"46546\", \"transaction_date\": \"2026-08-13T21:00:00.000000Z\", \"transaction_type\": \"GRV\", \"transaction_number\": \"TRX-20260812140049-152\"}','{\"id\": 66, \"item_id\": \"1\", \"remarks\": null, \"quantity\": \"52.00\", \"created_at\": \"2026-08-12T11:00:49.000000Z\", \"created_by\": 1, \"deleted_at\": null, \"updated_at\": \"2026-08-17T07:58:44.000000Z\", \"updated_by\": 1, \"to_location_id\": \"5\", \"document_number\": \"9865\", \"from_location_id\": null, \"reference_number\": \"46546\", \"transaction_date\": \"2026-08-16T21:00:00.000000Z\", \"transaction_type\": \"GRV\", \"transaction_number\": \"TRX-20260812140049-152\"}',NULL,NULL,'2026-08-17 07:58:44','2026-08-17 07:58:44'),(64,1,'Administrator','admin@mims.com','admin','VIEW','Transaction viewed: TRX-20260812140049-152','TRANSACTION',66,'TRX-20260812140049-152','Transactions','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-17 07:58:50','2026-08-17 07:58:50'),(65,1,'Administrator','admin@mims.com','admin','VIEW','Transaction viewed: TRX-20260812140049-152','TRANSACTION',66,'TRX-20260812140049-152','Transactions','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-17 08:26:50','2026-08-17 08:26:50'),(66,1,'Administrator','admin@mims.com','admin','CREATE','Transaction ISTRV created: Crushed Sand Coarse (12 m3)','TRANSACTION',76,'TRX-20260817113029-628','Transactions','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,'{\"id\": 76, \"item_id\": \"11\", \"remarks\": null, \"quantity\": \"12.00\", \"created_at\": \"2026-08-17T08:30:29.000000Z\", \"created_by\": 1, \"updated_at\": \"2026-08-17T08:30:29.000000Z\", \"to_location_id\": \"7\", \"document_number\": null, \"from_location_id\": \"5\", \"reference_number\": \"59606\", \"transaction_date\": \"2026-08-16T21:00:00.000000Z\", \"transaction_type\": \"ISTRV\", \"transaction_number\": \"TRX-20260817113029-628\"}',7,'Ambo University WWT','2026-08-17 08:30:29','2026-08-17 08:30:29'),(67,1,'Administrator','admin@mims.com','admin','VIEW','Transaction viewed: TRX-20260817113029-628','TRANSACTION',76,'TRX-20260817113029-628','Transactions','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-17 08:31:48','2026-08-17 08:31:48'),(68,1,'Administrator','admin@mims.com','admin','VIEW','Transaction viewed: TRX-20260817113029-628','TRANSACTION',76,'TRX-20260817113029-628','Transactions','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-17 11:35:28','2026-08-17 11:35:28'),(69,1,'Administrator','admin@mims.com','admin','VIEW','Transaction viewed: TRX-20260817113029-628','TRANSACTION',76,'TRX-20260817113029-628','Transactions','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-17 11:35:31','2026-08-17 11:35:31'),(70,1,'Administrator','admin@mims.com','admin','LOGIN','User logged in successfully',NULL,NULL,NULL,'Authentication','192.168.1.42','Windows PC (NT 10.0)','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Windows',NULL,NULL,NULL,NULL,'2026-08-17 11:39:11','2026-08-17 11:39:11'),(71,1,'Administrator','admin@mims.com','admin','LOGOUT','User logged out',NULL,NULL,NULL,'Authentication','192.168.1.42','Windows PC (NT 10.0)','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Windows',NULL,NULL,NULL,NULL,'2026-08-17 11:44:25','2026-08-17 11:44:25'),(72,1,'Administrator','admin@mims.com','admin','LOGIN','User logged in successfully',NULL,NULL,NULL,'Authentication','192.168.1.42','Windows PC (NT 10.0)','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Windows',NULL,NULL,NULL,NULL,'2026-08-17 11:44:30','2026-08-17 11:44:30'),(73,1,'Administrator','admin@mims.com','admin','EXPORT','Delivery Report exported','REPORT',NULL,'Delivery Report','Reports','192.168.1.42','Windows PC (NT 10.0)','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Windows',NULL,NULL,NULL,NULL,'2026-08-17 11:45:42','2026-08-17 11:45:42'),(74,1,'Administrator','admin@mims.com','admin','VIEW','Transaction viewed: TRX-20260817113029-628','TRANSACTION',76,'TRX-20260817113029-628','Transactions','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-17 11:50:09','2026-08-17 11:50:09'),(75,1,'Administrator','admin@mims.com','admin','VIEW','Transaction viewed: TRX-20260812140049-152','TRANSACTION',66,'TRX-20260812140049-152','Transactions','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-17 11:50:12','2026-08-17 11:50:12'),(76,1,'Administrator','admin@mims.com','admin','VIEW','Transaction viewed: TRX-20260813142054-828','TRANSACTION',69,'TRX-20260813142054-828','Transactions','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-17 11:50:15','2026-08-17 11:50:15'),(77,1,'Administrator','admin@mims.com','admin','EXPORT','Delivery Report exported','REPORT',NULL,'Delivery Report','Reports','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-17 11:50:21','2026-08-17 11:50:21'),(78,1,'Administrator','admin@mims.com','admin','CREATE','Transaction ISTRV created: PPC Cement 50kg (51 Bag)','TRANSACTION',77,'TRX-20260817145400-667','Transactions','192.168.1.42','Windows PC (NT 10.0)','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Windows',NULL,'{\"id\": 77, \"item_id\": \"1\", \"remarks\": null, \"quantity\": \"51.00\", \"created_at\": \"2026-08-17T11:54:00.000000Z\", \"created_by\": 1, \"updated_at\": \"2026-08-17T11:54:00.000000Z\", \"to_location_id\": \"7\", \"document_number\": null, \"from_location_id\": \"5\", \"reference_number\": \"115236\", \"transaction_date\": \"2026-08-16T21:00:00.000000Z\", \"transaction_type\": \"ISTRV\", \"transaction_number\": \"TRX-20260817145400-667\"}',7,'Ambo University WWT','2026-08-17 11:54:00','2026-08-17 11:54:00'),(79,1,'Administrator','admin@mims.com','admin','VIEW','Transaction viewed: TRX-20260817145400-667','TRANSACTION',77,'TRX-20260817145400-667','Transactions','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-17 14:04:27','2026-08-17 14:04:27'),(80,1,'Administrator','admin@mims.com','admin','VIEW','Transaction viewed: TRX-20260817113029-628','TRANSACTION',76,'TRX-20260817113029-628','Transactions','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-17 14:04:30','2026-08-17 14:04:30'),(81,1,'Administrator','admin@mims.com','admin','VIEW','Transaction viewed: TRX-20260814024031-315','TRANSACTION',75,'TRX-20260814024031-315','Transactions','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-17 14:04:32','2026-08-17 14:04:32'),(82,1,'Administrator','admin@mims.com','admin','VIEW','Transaction viewed: TRX-20260813142054-828','TRANSACTION',69,'TRX-20260813142054-828','Transactions','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-17 14:04:36','2026-08-17 14:04:36'),(83,1,'Administrator','admin@mims.com','admin','VIEW','Transaction viewed: TRX-20260814014706-301','TRANSACTION',71,'TRX-20260814014706-301','Transactions','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-17 14:04:38','2026-08-17 14:04:38'),(84,1,'Administrator','admin@mims.com','admin','VIEW','Transaction viewed: TRX-20260812123026-078','TRANSACTION',9,'TRX-20260812123026-078','Transactions','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-17 14:05:37','2026-08-17 14:05:37'),(85,1,'Administrator','admin@mims.com','admin','LOGIN','User logged in successfully',NULL,NULL,NULL,'Authentication','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-18 06:33:16','2026-08-18 06:33:16'),(86,1,'Administrator','admin@mims.com','admin','VIEW','Transaction viewed: TRX-20260817145400-667','TRANSACTION',77,'TRX-20260817145400-667','Transactions','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-18 08:02:13','2026-08-18 08:02:13'),(87,1,'Administrator','admin@mims.com','admin','VIEW','Transaction viewed: TRX-20260817145400-667','TRANSACTION',77,'TRX-20260817145400-667','Transactions','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-18 08:02:23','2026-08-18 08:02:23'),(88,1,'Administrator','admin@mims.com','admin','LOGOUT','User logged out',NULL,NULL,NULL,'Authentication','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-18 08:02:45','2026-08-18 08:02:45'),(89,7,'Head Office Store','headoffice@mims.com','head_office','LOGIN','User logged in successfully',NULL,NULL,NULL,'Authentication','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-18 08:03:05','2026-08-18 08:03:05'),(90,7,'Head Office Store','headoffice@mims.com','head_office','LOGOUT','User logged out',NULL,NULL,NULL,'Authentication','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-18 10:14:17','2026-08-18 10:14:17'),(91,1,'Administrator','admin@mims.com','admin','LOGIN','User logged in successfully',NULL,NULL,NULL,'Authentication','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-18 10:14:19','2026-08-18 10:14:19'),(92,1,'Administrator','admin@mims.com','admin','LOGIN','User logged in successfully',NULL,NULL,NULL,'Authentication','192.168.1.42','Windows PC (NT 10.0)','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Windows',NULL,NULL,NULL,NULL,'2026-08-18 10:22:46','2026-08-18 10:22:46'),(93,1,'Administrator','admin@mims.com','admin','LOGOUT','User logged out',NULL,NULL,NULL,'Authentication','192.168.1.42','Windows PC (NT 10.0)','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Windows',NULL,NULL,NULL,NULL,'2026-08-18 10:25:21','2026-08-18 10:25:21'),(94,1,'Administrator','admin@mims.com','admin','CREATE','Transaction ISTRV created: Sika Floor 263','TRANSACTION',78,'TRX-20260818134259-108','Transactions','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-18 10:42:59','2026-08-18 10:42:59'),(95,1,'Administrator','admin@mims.com','admin','LOGIN','User logged in successfully',NULL,NULL,NULL,'Authentication','192.168.1.42','Windows PC (NT 10.0)','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Windows',NULL,NULL,NULL,NULL,'2026-08-18 10:48:07','2026-08-18 10:48:07'),(96,1,'Administrator','admin@mims.com','admin','CREATE','Transaction GRV created: OPC Cement 50kg','TRANSACTION',79,'TRX-20260818135630-136','Transactions','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-18 10:56:30','2026-08-18 10:56:30'),(97,1,'Administrator','admin@mims.com','admin','EXPORT','Weekly Stock Status Report exported','REPORT',NULL,'Weekly Stock Status Report','Reports','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-18 11:02:12','2026-08-18 11:02:12'),(98,1,'Administrator','admin@mims.com','admin','CREATE','Transaction ISTRV created: White Cement 25kg','TRANSACTION',80,'TRX-20260818140621-515','Transactions','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-18 11:06:21','2026-08-18 11:06:21'),(99,1,'Administrator','admin@mims.com','admin','CREATE','Transaction STORE_RETURN created: PPC Cement 50kg','TRANSACTION',81,'TRX-20260818140751-808','Transactions','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-18 11:07:51','2026-08-18 11:07:51'),(100,1,'Administrator','admin@mims.com','admin','CREATE','Transaction UMTRV created: Sika Floor 263','TRANSACTION',82,'TRX-20260818141026-525','Transactions','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-18 11:10:26','2026-08-18 11:10:26'),(101,1,'Administrator','admin@mims.com','admin','CREATE','Transaction ISTRV created: Crushed Sand Coarse','TRANSACTION',83,'TRX-20260818142002-573','Transactions','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-18 11:20:02','2026-08-18 11:20:02'),(102,1,'Administrator','admin@mims.com','admin','CREATE','Transaction ISTRV created: Crushed Sand Coarse','TRANSACTION',84,'TRX-20260818143038-353','Transactions','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-18 11:30:39','2026-08-18 11:30:39'),(103,1,'Administrator','admin@mims.com','admin','CREATE','Transaction ISTRV created: Crushed Sand Coarse','TRANSACTION',85,'TRX-20260818144041-688','Transactions','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-18 11:40:41','2026-08-18 11:40:41'),(104,1,'Administrator','admin@mims.com','admin','EXPORT','Delivery Report exported','REPORT',NULL,'Delivery Report','Reports','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-18 11:41:06','2026-08-18 11:41:06'),(105,1,'Administrator','admin@mims.com','admin','LOGOUT','User logged out',NULL,NULL,NULL,'Authentication','192.168.1.42','Windows PC (NT 10.0)','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Windows',NULL,NULL,NULL,NULL,'2026-08-18 11:59:26','2026-08-18 11:59:26'),(106,1,'Administrator','admin@mims.com','admin','UPDATE','Item price updated: Aggregate 20mm (ETB 1850.00 → ETB 1850.00)','ITEM',12,'Aggregate 20mm','Items Management','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-18 12:28:14','2026-08-18 12:28:14'),(107,1,'Administrator','admin@mims.com','admin','EXPORT','Delivery Report exported','REPORT',NULL,'Delivery Report','Reports','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-18 12:34:43','2026-08-18 12:34:43'),(108,1,'Administrator','admin@mims.com','admin','EXPORT','Delivery Report exported','REPORT',NULL,'Delivery Report','Reports','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-18 12:36:02','2026-08-18 12:36:02'),(109,1,'Administrator','admin@mims.com','admin','EXPORT','Delivery Report exported (Type: used)','REPORT',NULL,'Delivery Report','Reports','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-18 12:38:38','2026-08-18 12:38:38'),(110,1,'Administrator','admin@mims.com','admin','EXPORT','Delivery Report exported (Type: used)','REPORT',NULL,'Delivery Report','Reports','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-18 12:38:47','2026-08-18 12:38:47'),(111,1,'Administrator','admin@mims.com','admin','EXPORT','Delivery Report exported (Type: All)','REPORT',NULL,'Delivery Report','Reports','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-18 12:57:57','2026-08-18 12:57:57'),(112,1,'Administrator','admin@mims.com','admin','EXPORT','Weekly Transfer Report exported','REPORT',NULL,'Weekly Transfer Report','Reports','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-18 13:03:03','2026-08-18 13:03:03'),(113,1,'Administrator','admin@mims.com','admin','LOGIN','User logged in successfully',NULL,NULL,NULL,'Authentication','192.168.1.42','Windows PC (NT 10.0)','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Windows',NULL,NULL,NULL,NULL,'2026-08-18 13:55:55','2026-08-18 13:55:55'),(114,1,'Administrator','admin@mims.com','admin','EXPORT','Weekly Transfer Report exported','REPORT',NULL,'Weekly Transfer Report','Reports','192.168.1.42','Windows PC (NT 10.0)','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Windows',NULL,NULL,NULL,NULL,'2026-08-18 13:59:17','2026-08-18 13:59:17'),(115,1,'Administrator','admin@mims.com','admin','EXPORT','Weekly Transfer Report exported','REPORT',NULL,'Weekly Transfer Report','Reports','192.168.1.42','Windows PC (NT 10.0)','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Windows',NULL,NULL,NULL,NULL,'2026-08-18 13:59:20','2026-08-18 13:59:20'),(116,NULL,'System',NULL,NULL,'LOGIN_FAILED','Failed login attempt for email: admin@tnt-constructions.com',NULL,NULL,'admin@tnt-constructions.com','Authentication','127.0.0.1','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-18 16:16:51','2026-08-18 16:16:51'),(117,1,'Administrator','admin@mims.com','admin','LOGIN','User logged in successfully',NULL,NULL,NULL,'Authentication','127.0.0.1','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-18 16:17:18','2026-08-18 16:17:18'),(118,7,'Head Office Store','headoffice@mims.com','head_office','LOGIN','User logged in successfully',NULL,NULL,NULL,'Authentication','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-19 07:14:37','2026-08-19 07:14:37'),(119,7,'Head Office Store','headoffice@mims.com','head_office','LOGOUT','User logged out',NULL,NULL,NULL,'Authentication','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-19 07:19:14','2026-08-19 07:19:14'),(120,1,'Administrator','admin@mims.com','admin','LOGIN','User logged in successfully',NULL,NULL,NULL,'Authentication','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-19 07:19:16','2026-08-19 07:19:16'),(121,1,'Administrator','admin@mims.com','admin','LOGOUT','User logged out',NULL,NULL,NULL,'Authentication','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-19 07:40:47','2026-08-19 07:40:47'),(122,9,'amare','amare@tnt-constructions.com','gm','LOGIN','User logged in successfully',NULL,NULL,NULL,'Authentication','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-19 07:40:56','2026-08-19 07:40:56'),(123,9,'amare','amare@tnt-constructions.com','gm','LOGOUT','User logged out',NULL,NULL,NULL,'Authentication','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-19 08:34:08','2026-08-19 08:34:08'),(124,1,'Administrator','admin@mims.com','admin','LOGIN','User logged in successfully',NULL,NULL,NULL,'Authentication','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-19 08:34:11','2026-08-19 08:34:11'),(125,1,'Administrator','admin@mims.com','admin','LOGOUT','User logged out',NULL,NULL,NULL,'Authentication','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-19 08:38:42','2026-08-19 08:38:42'),(126,9,'amare','amare@tnt-constructions.com','gm','LOGIN','User logged in successfully',NULL,NULL,NULL,'Authentication','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-19 08:38:57','2026-08-19 08:38:57'),(127,9,'amare','amare@tnt-constructions.com','gm','LOGOUT','User logged out',NULL,NULL,NULL,'Authentication','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-19 10:24:55','2026-08-19 10:24:55'),(128,7,'Head Office Store','headoffice@mims.com','head_office','LOGIN','User logged in successfully',NULL,NULL,NULL,'Authentication','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-19 10:24:59','2026-08-19 10:24:59'),(129,7,'Head Office Store','headoffice@mims.com','head_office','LOGOUT','User logged out',NULL,NULL,NULL,'Authentication','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-19 10:25:56','2026-08-19 10:25:56'),(130,9,'amare','amare@tnt-constructions.com','gm','LOGIN','User logged in successfully',NULL,NULL,NULL,'Authentication','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-19 10:26:00','2026-08-19 10:26:00'),(131,9,'amare','amare@tnt-constructions.com','gm','LOGOUT','User logged out',NULL,NULL,NULL,'Authentication','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-19 10:45:14','2026-08-19 10:45:14'),(132,1,'Administrator','admin@mims.com','admin','LOGIN','User logged in successfully',NULL,NULL,NULL,'Authentication','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-19 10:45:17','2026-08-19 10:45:17'),(133,1,'Administrator','admin@mims.com','admin','CREATE','Transaction FRV created: Gas Oil (Diesel)','TRANSACTION',86,'TRX-20260819140259-731','Transactions','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-19 11:02:59','2026-08-19 11:02:59'),(134,1,'Administrator','admin@mims.com','admin','LOGOUT','User logged out',NULL,NULL,NULL,'Authentication','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-19 11:38:09','2026-08-19 11:38:09'),(135,9,'amare','amare@tnt-constructions.com','gm','LOGIN','User logged in successfully',NULL,NULL,NULL,'Authentication','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-19 11:38:12','2026-08-19 11:38:12'),(136,9,'amare','amare@tnt-constructions.com','gm','LOGOUT','User logged out',NULL,NULL,NULL,'Authentication','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-19 11:41:32','2026-08-19 11:41:32'),(137,1,'Administrator','admin@mims.com','admin','LOGIN','User logged in successfully',NULL,NULL,NULL,'Authentication','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-19 11:41:35','2026-08-19 11:41:35'),(138,1,'Administrator','admin@mims.com','admin','LOGOUT','User logged out',NULL,NULL,NULL,'Authentication','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-19 11:47:24','2026-08-19 11:47:24'),(139,3,'Project Manager','manager@mims.com','manager','LOGIN','User logged in successfully',NULL,NULL,NULL,'Authentication','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-19 11:47:31','2026-08-19 11:47:31'),(140,3,'Project Manager','manager@mims.com','manager','LOGOUT','User logged out',NULL,NULL,NULL,'Authentication','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-19 11:54:27','2026-08-19 11:54:27'),(141,1,'Administrator','admin@mims.com','admin','LOGIN','User logged in successfully',NULL,NULL,NULL,'Authentication','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-19 11:54:31','2026-08-19 11:54:31'),(142,1,'Administrator','admin@mims.com','admin','LOGOUT','User logged out',NULL,NULL,NULL,'Authentication','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-19 11:54:42','2026-08-19 11:54:42'),(143,1,'Administrator','admin@mims.com','admin','LOGIN','User logged in successfully',NULL,NULL,NULL,'Authentication','192.168.1.83','Windows PC (NT 10.0)','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0','Microsoft Edge','Windows',NULL,NULL,NULL,NULL,'2026-08-19 12:01:06','2026-08-19 12:01:06'),(144,1,'Administrator','admin@mims.com','admin','LOGIN','User logged in successfully',NULL,NULL,NULL,'Authentication','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-20 05:20:27','2026-08-20 05:20:27'),(145,1,'Administrator','admin@mims.com','admin','LOGIN','User logged in successfully',NULL,NULL,NULL,'Authentication','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-20 08:22:28','2026-08-20 08:22:28'),(146,1,'Administrator','admin@mims.com','admin','UPDATE','Item price updated: Aggregate 20mm','ITEM',12,'Aggregate 20mm','Items Management','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-20 08:55:33','2026-08-20 08:55:33'),(147,1,'Administrator','admin@mims.com','admin','CREATE','Transaction ISTRV created: OPC Cement 50kg','TRANSACTION',87,'TRX-20260820120250-416','Transactions','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-20 09:02:50','2026-08-20 09:02:50'),(148,1,'Administrator','admin@mims.com','admin','CREATE','Transaction SIV created: PPC Cement 50kg','TRANSACTION',88,'TRX-20260820130310-036','Transactions','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-20 10:03:10','2026-08-20 10:03:10'),(149,1,'Administrator','admin@mims.com','admin','CREATE','Transaction TRANSFER_OUT created: PPC Cement 50kg','TRANSACTION',89,'TRX-20260820131834-609','Transactions','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-20 10:18:34','2026-08-20 10:18:34'),(150,1,'Administrator','admin@mims.com','admin','CREATE','Transaction SIV created: PPC Cement 50kg','TRANSACTION',90,'TRX-20260820131911-573','Transactions','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-20 10:19:11','2026-08-20 10:19:11'),(151,NULL,'System',NULL,NULL,'LOGIN_FAILED','Failed login attempt for email: storekeeper@mims.com',NULL,NULL,'storekeeper@mims.com','Authentication','192.168.1.42','Windows PC (NT 10.0)','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0','Microsoft Edge','Windows',NULL,NULL,NULL,NULL,'2026-08-20 10:27:48','2026-08-20 10:27:48'),(152,2,'Site Storekeeper','storekeeper@mims.com','storekeeper','LOGIN','User logged in successfully',NULL,NULL,NULL,'Authentication','192.168.1.42','Windows PC (NT 10.0)','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0','Microsoft Edge','Windows',NULL,NULL,NULL,NULL,'2026-08-20 10:28:24','2026-08-20 10:28:24'),(153,2,'Site Storekeeper','storekeeper@mims.com','storekeeper','LOGOUT','User logged out',NULL,NULL,NULL,'Authentication','192.168.1.42','Windows PC (NT 10.0)','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0','Microsoft Edge','Windows',NULL,NULL,NULL,NULL,'2026-08-20 10:32:30','2026-08-20 10:32:30'),(154,1,'Administrator','admin@mims.com','admin','LOGIN','User logged in successfully',NULL,NULL,NULL,'Authentication','192.168.1.42','Windows PC (NT 10.0)','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0','Microsoft Edge','Windows',NULL,NULL,NULL,NULL,'2026-08-20 10:32:51','2026-08-20 10:32:51'),(155,1,'Administrator','admin@mims.com','admin','CREATE','Transaction SIV created: PPC Cement 50kg','TRANSACTION',91,'TRX-20260820133908-560','Transactions','192.168.1.42','Windows PC (NT 10.0)','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0','Microsoft Edge','Windows',NULL,NULL,NULL,NULL,'2026-08-20 10:39:08','2026-08-20 10:39:08'),(156,1,'Administrator','admin@mims.com','admin','CREATE','Transaction ISTRV created: PPC Cement 50kg','TRANSACTION',92,'TRX-20260820134147-197','Transactions','192.168.1.42','Windows PC (NT 10.0)','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0','Microsoft Edge','Windows',NULL,NULL,NULL,NULL,'2026-08-20 10:41:47','2026-08-20 10:41:47'),(157,1,'Administrator','admin@mims.com','admin','LOGOUT','User logged out',NULL,NULL,NULL,'Authentication','192.168.1.42','Windows PC (NT 10.0)','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0','Microsoft Edge','Windows',NULL,NULL,NULL,NULL,'2026-08-20 10:46:37','2026-08-20 10:46:37'),(158,1,'Administrator','admin@mims.com','admin','CREATE','Transaction UMTRV created: Ega Sheet','TRANSACTION',93,'TRX-20260820141849-915','Transactions','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-20 11:18:49','2026-08-20 11:18:49'),(159,1,'Administrator','admin@mims.com','admin','EXPORT','Delivery Report exported (Type: All)','REPORT',NULL,'Delivery Report','Reports','192.168.1.59','Linux Computer','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Google Chrome','Linux',NULL,NULL,NULL,NULL,'2026-08-20 11:20:14','2026-08-20 11:20:14');
/*!40000 ALTER TABLE `activity_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('laravel-cache-spatie.permission.cache','a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:11:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:12:\"manage items\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:7;i:2;i:8;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:17:\"manage categories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:7;i:2;i:8;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:16:\"manage locations\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:7;i:2;i:8;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:19:\"create transactions\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:3;i:2;i:6;i:3;i:7;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:17:\"view transactions\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:7;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:12:\"view reports\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:12:\"manage users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:21:\"view own transactions\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:3;i:2;i:6;}}i:8;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:16:\"view all reports\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:6:{i:0;i:1;i:1;i:2;i:2;i:4;i:3;i:5;i:4;i:7;i:5;i:8;}}i:9;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:16:\"view own reports\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:3;i:2;i:6;}}i:10;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:12:\"manage roles\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}}s:5:\"roles\";a:8:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:5:\"admin\";s:1:\"c\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";i:7;s:1:\"b\";s:11:\"head_office\";s:1:\"c\";s:3:\"web\";}i:2;a:3:{s:1:\"a\";i:8;s:1:\"b\";s:11:\"master_data\";s:1:\"c\";s:3:\"web\";}i:3;a:3:{s:1:\"a\";i:3;s:1:\"b\";s:11:\"storekeeper\";s:1:\"c\";s:3:\"web\";}i:4;a:3:{s:1:\"a\";i:6;s:1:\"b\";s:13:\"site_engineer\";s:1:\"c\";s:3:\"web\";}i:5;a:3:{s:1:\"a\";i:2;s:1:\"b\";s:7:\"manager\";s:1:\"c\";s:3:\"web\";}i:6;a:3:{s:1:\"a\";i:4;s:1:\"b\";s:2:\"gm\";s:1:\"c\";s:3:\"web\";}i:7;a:3:{s:1:\"a\";i:5;s:1:\"b\";s:7:\"checker\";s:1:\"c\";s:3:\"web\";}}}',1787311557);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Cement','CEM',NULL,1,'2026-08-12 09:30:24','2026-08-12 09:30:24',NULL),(2,'Concrete','CON',NULL,1,'2026-08-12 09:30:24','2026-08-12 09:30:24',NULL),(3,'Re-Bar','RBR',NULL,1,'2026-08-12 09:30:24','2026-08-12 09:30:24',NULL),(4,'Sand','SND',NULL,1,'2026-08-12 09:30:24','2026-08-12 09:30:24',NULL),(5,'Aggregate','AGG',NULL,1,'2026-08-12 09:30:24','2026-08-12 09:30:24',NULL),(6,'Chemicals','CHM',NULL,1,'2026-08-12 09:30:24','2026-08-12 09:30:24',NULL),(7,'Steel','STL',NULL,1,'2026-08-12 09:30:24','2026-08-12 09:30:24',NULL),(8,'Wood','WOD',NULL,1,'2026-08-12 09:30:24','2026-08-12 09:30:24',NULL),(9,'Plumbing','PLB',NULL,1,'2026-08-12 09:30:24','2026-08-12 09:30:24',NULL),(10,'Electrical','ELC',NULL,1,'2026-08-12 09:30:24','2026-08-12 09:30:24',NULL),(11,'Fuel & Oil','FUEL',NULL,1,'2026-08-19 11:00:16','2026-08-20 05:26:32',NULL),(12,'Fixed Assets','FIXED',NULL,1,'2026-08-19 11:00:16','2026-08-19 11:00:16',NULL),(13,'Used Materials','USED',NULL,1,'2026-08-19 11:00:16','2026-08-19 11:00:16',NULL),(14,'Equipment','EQP',NULL,1,'2026-08-20 05:26:32','2026-08-20 05:26:32',NULL),(15,'Tools','TOL',NULL,1,'2026-08-20 05:26:32','2026-08-20 05:26:32',NULL),(16,'Furniture','FUR',NULL,1,'2026-08-20 05:26:32','2026-08-20 05:26:32',NULL);
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `items`
--

DROP TABLE IF EXISTS `items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `category_id` bigint unsigned NOT NULL,
  `unit` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `item_type` enum('regular','fixed_asset','used_material','fuel') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'regular' COMMENT 'Item classification: regular, fixed_asset, used_material, fuel',
  `unit_price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `min_stock_level` decimal(10,2) NOT NULL DEFAULT '0.00',
  `max_stock_level` decimal(10,2) NOT NULL DEFAULT '0.00',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `items_code_unique` (`code`),
  KEY `items_category_id_foreign` (`category_id`),
  CONSTRAINT `items_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `items`
--

LOCK TABLES `items` WRITE;
/*!40000 ALTER TABLE `items` DISABLE KEYS */;
INSERT INTO `items` VALUES (1,'CEM-001','PPC Cement 50kg',NULL,1,'Bag','regular',850.00,50.00,500.00,1,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(2,'CEM-002','OPC Cement 50kg',NULL,1,'Bag','regular',780.00,40.00,400.00,1,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(3,'CEM-003','White Cement 25kg',NULL,1,'Bag','regular',450.00,20.00,200.00,1,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(4,'CON-001','Ready Mix Concrete C25',NULL,2,'m3','regular',5500.00,10.00,100.00,1,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(5,'CON-002','Concrete Additive Sika',NULL,2,'Ltr','regular',350.00,20.00,200.00,1,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(6,'RBR-001','Rebar Diameter 8mm',NULL,3,'Qtl','regular',5200.00,30.00,300.00,1,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(7,'RBR-002','Rebar Diameter 10mm',NULL,3,'Qtl','regular',5100.00,25.00,250.00,1,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(8,'RBR-003','Rebar Diameter 12mm',NULL,3,'Qtl','regular',5050.00,20.00,200.00,1,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(9,'RBR-004','Rebar Diameter 16mm',NULL,3,'Qtl','regular',5000.00,15.00,150.00,1,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(10,'SND-001','River Sand Fine',NULL,4,'m3','regular',1200.00,50.00,500.00,1,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(11,'SND-002','Crushed Sand Coarse',NULL,4,'m3','regular',1000.00,40.00,400.00,1,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(12,'AGG-001','Aggregate 20mm',NULL,5,'m3','regular',1800.00,30.00,300.00,1,'2026-08-12 09:30:26','2026-08-20 08:55:33',NULL),(13,'AGG-002','Aggregate 10mm',NULL,5,'m3','regular',1900.00,25.00,250.00,1,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(14,'AGG-003','Gravel Base Course',NULL,5,'m3','regular',1500.00,20.00,200.00,1,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(15,'CHM-001','Sika Floor 263',NULL,6,'Ltr','regular',450.00,10.00,100.00,1,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(16,'CHM-002','Waterproofing Compound',NULL,6,'Kg','regular',280.00,15.00,150.00,1,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(17,'CHM-003','Curing Compound',NULL,6,'Ltr','regular',180.00,20.00,200.00,1,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(18,'STL-001','Steel Plate 6mm',NULL,7,'Pcs','regular',3500.00,10.00,100.00,1,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(19,'STL-002','Steel Angle 50x50x6',NULL,7,'Pcs','regular',1200.00,15.00,150.00,1,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(20,'WOD-001','Plywood 18mm',NULL,8,'Pcs','regular',2200.00,20.00,200.00,1,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(21,'WOD-002','Timber 2x4',NULL,8,'Pcs','regular',850.00,30.00,300.00,1,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(22,'PLB-001','PVC Pipe 110mm',NULL,9,'Mtr','regular',350.00,50.00,500.00,1,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(23,'PLB-002','PVC Pipe 50mm',NULL,9,'Mtr','regular',180.00,40.00,400.00,1,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(24,'ELC-001','Electrical Cable 2.5mm',NULL,10,'Mtr','regular',65.00,100.00,1000.00,1,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(25,'ELC-002','LED Panel Light 60W',NULL,10,'Pcs','regular',850.00,20.00,200.00,1,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(26,'FUEL-001','Gas Oil (Diesel)',NULL,11,'Ltr','fuel',80.00,0.00,0.00,1,'2026-08-19 11:00:16','2026-08-20 06:01:04',NULL),(27,'FUEL-002','Benzene (Petrol)',NULL,11,'Ltr','fuel',75.00,0.00,0.00,1,'2026-08-19 11:00:16','2026-08-20 06:01:04',NULL),(28,'FUEL-003','Engine Oil',NULL,11,'Ltr','fuel',450.00,0.00,0.00,1,'2026-08-19 11:00:16','2026-08-20 06:01:04',NULL),(29,'FUEL-004','Hydraulic Oil',NULL,11,'Ltr','fuel',350.00,0.00,0.00,1,'2026-08-19 11:00:16','2026-08-20 06:01:04',NULL),(30,'FUEL-005','Grease',NULL,11,'Kg','fuel',250.00,0.00,0.00,1,'2026-08-19 11:00:16','2026-08-20 06:01:04',NULL),(31,'FA-001','Metal Container',NULL,16,'Pcs','fixed_asset',85000.00,0.00,0.00,1,'2026-08-19 11:00:16','2026-08-20 06:01:04',NULL),(32,'FA-002','Total Station',NULL,14,'Set','fixed_asset',450000.00,0.00,0.00,1,'2026-08-19 11:00:16','2026-08-20 06:01:04',NULL),(33,'FA-003','Total Station Accessories',NULL,14,'Pcs','fixed_asset',15000.00,0.00,0.00,1,'2026-08-19 11:00:16','2026-08-20 06:01:04',NULL),(34,'FA-004','Reflector Pole',NULL,14,'Pcs','fixed_asset',3500.00,0.00,0.00,1,'2026-08-19 11:00:16','2026-08-20 06:01:04',NULL),(35,'FA-005','Managerial Chair',NULL,16,'Pcs','fixed_asset',8500.00,0.00,0.00,1,'2026-08-19 11:00:16','2026-08-20 06:01:04',NULL),(36,'FA-006','Grease Gun',NULL,14,'Pcs','fixed_asset',2500.00,0.00,0.00,1,'2026-08-19 11:00:16','2026-08-20 06:01:04',NULL),(37,'FA-007','Diesel Water Pump 4 inch',NULL,14,'Pcs','fixed_asset',89373.91,0.00,0.00,1,'2026-08-19 11:00:16','2026-08-20 06:01:04',NULL),(38,'FA-008','Plastic Tanker 10000 ltr',NULL,16,'Pcs','fixed_asset',115000.00,0.00,0.00,1,'2026-08-19 11:00:16','2026-08-20 06:01:04',NULL),(39,'FA-009','Ppr Welding Machine',NULL,14,'Pcs','fixed_asset',15000.00,0.00,0.00,1,'2026-08-19 11:00:16','2026-08-20 06:01:04',NULL),(40,'FA-010','Surface Mounted Metalic Board',NULL,16,'Pcs','fixed_asset',222750.00,0.00,0.00,1,'2026-08-19 11:00:16','2026-08-20 06:01:04',NULL),(41,'UM-001','Ega Sheet',NULL,13,'Pcs','used_material',350.00,0.00,0.00,1,'2026-08-19 11:00:16','2026-08-20 06:01:04',NULL),(42,'UM-002','Blanket',NULL,13,'Pcs','used_material',500.00,0.00,0.00,1,'2026-08-19 11:00:17','2026-08-20 06:01:04',NULL),(43,'UM-003','Mattress',NULL,13,'Pcs','used_material',800.00,0.00,0.00,1,'2026-08-19 11:00:17','2026-08-20 06:01:04',NULL),(44,'UM-004','Metal Bermel 200Ltr',NULL,13,'Pcs','regular',1200.00,0.00,0.00,1,'2026-08-19 11:00:17','2026-08-19 11:00:17',NULL),(45,'UM-005','Iron Sheet',NULL,13,'Pcs','regular',250.00,0.00,0.00,1,'2026-08-19 11:00:17','2026-08-19 11:00:17',NULL),(46,'UM-006','Dijino (Crow Bar)',NULL,13,'Pcs','regular',350.00,0.00,0.00,1,'2026-08-19 11:00:17','2026-08-19 11:00:17',NULL),(47,'UM-007','Used Eucalyptus',NULL,13,'Trip','used_material',2500.00,0.00,0.00,1,'2026-08-19 11:00:17','2026-08-20 06:01:04',NULL),(48,'UM-008','Ply Wood & Purline',NULL,13,'Trip','regular',3000.00,0.00,0.00,1,'2026-08-19 11:00:17','2026-08-19 11:00:17',NULL),(49,'UM-009','Ply Wood & Eucalyptus',NULL,13,'Trip','regular',2800.00,0.00,0.00,1,'2026-08-19 11:00:17','2026-08-19 11:00:17',NULL),(50,'UM-010','Ega Sheet 220-450',NULL,13,'Pcs','used_material',400.00,0.00,0.00,1,'2026-08-19 11:00:17','2026-08-20 06:01:04',NULL);
/*!40000 ALTER TABLE `items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `locations`
--

DROP TABLE IF EXISTS `locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `locations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('head_office','project','site','store') COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `contact_person` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `locations_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=90 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `locations`
--

LOCK TABLES `locations` WRITE;
/*!40000 ALTER TABLE `locations` DISABLE KEYS */;
INSERT INTO `locations` VALUES (1,'Head Office','HO','head_office',NULL,NULL,NULL,0,'2026-08-12 09:30:24','2026-08-13 20:24:26','2026-08-13 20:24:26'),(2,'Nefas Silk','NS','site',NULL,NULL,NULL,0,'2026-08-12 09:30:24','2026-08-13 20:21:59','2026-08-13 20:21:59'),(3,'EAU South Campus Project','EAU-SC','project',NULL,NULL,NULL,0,'2026-08-12 09:30:24','2026-08-13 20:25:02','2026-08-13 20:25:02'),(4,'Main Store','0002','store',NULL,NULL,NULL,0,'2026-08-12 09:30:24','2026-08-13 22:57:49',NULL),(5,'Head Office','0001','head_office',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(6,'Diredawa - Federal Prison Administration','0010','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(7,'Ambo University WWT','0019','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(8,'Ayat 40/60 Condominium','0020','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(9,'Mettu Expansion','0027','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(10,'Chiro Infrastructure','0028','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(11,'TVET Residential Building /ቲቪቲ የመኖርያ ህንፃ','0029','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(12,'CP Cadila Pharmaceuticals','0030','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(13,'EPRDF Head Office Building','0032','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(14,'Ambo Stadium','0038','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(15,'Arbaminch Abaya & Chamo','0039','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(16,'Chiro Building Project','0041','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(17,'Bole Lemi Water Supply','0043','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(18,'AASTU-Commercial Building','0044','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(19,'Diredawa Waste Water','0046','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(20,'Kentiba W/Tsadik Green Park','0047','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(21,'Ambo Teaching & Referral Hospital Project','0049','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(22,'Arbaminch UG & PG Class Room','0050','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(23,'Bulbula Waste Water Treatment Plant','0052','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(24,'Bensa Waste Water Treatment Plant','0054','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(25,'Dilla Waste Water Treatment Plant','0055','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(26,'Shashemene Waste Water Treatment Plant','0056','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(27,'Yirgachefe WWTP','0057','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(28,'Augusta Weyra Junction Road Project','0058','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(29,'Zambiya Embassy Chancery Project','0060','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(30,'Debre Birhan Waste Water Treatment Plant','0061','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(31,'Bahirdar Waste Water Treatment Plant','0062','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(32,'Debre Markos University Teaching Hospital','0063','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(33,'Meki Waste Water Treatment Plant','0064','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(34,'Bale Robe Waste Water Treatment Plant','0065','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(35,'Ethiopian Enviro & Forest Rese','0066','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(36,'Ethiopian Sugar Corporation Head Quarter Building','0067','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(37,'Debrebirhan University Class Room & Computing School A','0068','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(38,'Arbaminch University Waste Water Treatment Plant','0069','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(39,'AACGRB Kechene & Shegole','0070','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(40,'Addis Ababa Small & Medium Manufacturing Industry(Mega project)','0071','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(41,'Bonga Waste Water Treatment Plant','0072','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(42,'City Administration of Addis Ababa Women Rehabilitation & Skill','0074','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(43,'Chiro UniversityTwo Seminar Halls & Two L Phase 3','0075','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(44,'Head Office - One','0076','head_office',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(45,'Head Office -RW','0077','head_office',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(46,'Head Office - Charity','0078','head_office',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(47,'Head Office-Wereda 6','0079','head_office',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(48,'Equipment Administration Store - Head Office','0080','store',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(49,'Ambo University DB WWTP','0081','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(50,'Chiro Oda Bultum University Main Gate Access Road & Bridge Struc','0082','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(51,'Head Office-4killo Meskerem School','0083','head_office',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(52,'KOLFE KERANIYO GENERAL HOSPITAL PROJECT LOT 1','0084','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(53,'NEFAS SILK GENERAL HOSPITAL PROJECT LOT 1','0085','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(54,'Addis Ababa Corridor Dev\'t Project-Tewdros Roundabout Parking','0086','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(55,'Addis Ababa Corridor Dev\'t Project- Basha Wolde Chilot Parking','0087','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(56,'Addis Ababa Corridor Dev\'t Project- Mexico Parking','0088','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(57,'Addis Ababa Corridor Dev\'t Project- Bole Noc Parking','0089','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(58,'Addis Ababa Corridor Dev\'t Project- Megenagna Parking','0090','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(59,'Addis Ababa Corridor Dev\'t Project- Bole Japan Embassy Parking','0091','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(60,'Addis Ababa Lideta Charity Project','0092','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(61,'KOLFE KERANIYO GENERAL HOSPITAL PROJECT LOT 2','0093','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(62,'NEFAS SILK GENERAL HOSPITAL PROJECT LOT 2','0094','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(63,'Entoto to Pickock Park','0095','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(64,'ETH AirLines Bishoftu 340 Villa 3A','0096','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(65,'ETH AirLines Bishoftu 340 Villa 3B','0097','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(66,'ETH AirLines Bishoftu 340 Villa 3C','0098','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(67,'ETH AirLines Bishoftu 340 Villa 3D','0099','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(68,'ETH AirLines Bishoftu 340 Villa 3E','0100','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(69,'FDRE Skills Development Park Project','0101','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(70,'ETH AirLines Bishoftu 340 Villa Central','0102','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(71,'Addis Ababa around Amasader Area','0103','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(72,'Chaina 00-Meketeya Riverside Project','0104','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(73,'Guto Meda Korea Park Riverside Project','0105','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(74,'Kechene Menen Riverside Project','0106','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(75,'Ambasder Park Riverside Project','0107','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(76,'Bambis Bridge Riverside Project','0108','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(77,'Kebena Riverside Project','0109','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(78,'PetrosePawelose Riverside Project','0110','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(79,'Fileweha Riverside Project','0111','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(80,'Peacock commercial building Project','0112','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(81,'Peacock Riverside Project','0113','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(82,'EAU South Campus፡ Lot-1','0114','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(83,'ETH Airlines -Bole Project','0115','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(84,'Sub @ Bishoftu Int. Airport','0116','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(85,'Tewodros Round','0117','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(86,'DESIGN AND BUILD OF ANRS PRESIDENT OFFICE BUILDING PROJECT','0118','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(87,'G+8 residential building','0119','project',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(88,'MAIN STORE','MAIN','store',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL),(89,'STOCK IN TRANSFER','SIT','store',NULL,NULL,NULL,1,'2026-08-13 11:52:47','2026-08-13 11:52:47',NULL);
/*!40000 ALTER TABLE `locations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2024_01_01_000000_create_permission_tables',1),(5,'2026_08_12_120400_create_material_inventory_tables',1),(6,'2026_08_13_000001_create_user_project_assignments_table',2),(7,'2024_01_01_000008_add_reset_fields_to_users',3),(8,'2026_08_14_000001_create_activity_logs_table',3),(9,'2026_08_18_000001_update_transaction_types',4),(10,'2026_08_20_000001_add_item_type_to_items',5);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_permissions`
--

LOCK TABLES `model_has_permissions` WRITE;
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_roles`
--

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
INSERT INTO `model_has_roles` VALUES (1,'App\\Models\\User',1),(3,'App\\Models\\User',2),(2,'App\\Models\\User',3),(4,'App\\Models\\User',4),(5,'App\\Models\\User',5),(6,'App\\Models\\User',6),(7,'App\\Models\\User',7),(8,'App\\Models\\User',8),(4,'App\\Models\\User',9);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'manage items','web','2026-08-12 09:30:25','2026-08-12 09:30:25'),(2,'manage categories','web','2026-08-12 09:30:25','2026-08-12 09:30:25'),(3,'manage locations','web','2026-08-12 09:30:25','2026-08-12 09:30:25'),(4,'create transactions','web','2026-08-12 09:30:25','2026-08-12 09:30:25'),(5,'view transactions','web','2026-08-12 09:30:25','2026-08-12 09:30:25'),(6,'view reports','web','2026-08-12 09:30:25','2026-08-12 09:30:25'),(7,'manage users','web','2026-08-12 09:30:25','2026-08-12 09:30:25'),(8,'view own transactions','web','2026-08-13 11:04:40','2026-08-13 11:04:40'),(9,'view all reports','web','2026-08-13 11:04:40','2026-08-13 11:04:40'),(10,'view own reports','web','2026-08-13 11:04:40','2026-08-13 11:04:40'),(11,'manage roles','web','2026-08-13 11:04:40','2026-08-13 11:04:40');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_has_permissions`
--

LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
INSERT INTO `role_has_permissions` VALUES (1,1),(2,1),(3,1),(4,1),(5,1),(6,1),(7,1),(8,1),(9,1),(10,1),(11,1),(9,2),(4,3),(8,3),(10,3),(9,4),(9,5),(4,6),(8,6),(10,6),(1,7),(2,7),(3,7),(4,7),(5,7),(9,7),(1,8),(2,8),(3,8),(9,8);
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'admin','web','2026-08-12 09:30:25','2026-08-12 09:30:25'),(2,'manager','web','2026-08-12 09:30:25','2026-08-12 09:30:25'),(3,'storekeeper','web','2026-08-12 09:30:25','2026-08-12 09:30:25'),(4,'gm','web','2026-08-13 11:04:40','2026-08-13 11:04:40'),(5,'checker','web','2026-08-13 11:04:40','2026-08-13 11:04:40'),(6,'site_engineer','web','2026-08-13 11:04:40','2026-08-13 11:04:40'),(7,'head_office','web','2026-08-13 22:12:45','2026-08-13 22:12:45'),(8,'master_data','web','2026-08-13 22:12:45','2026-08-13 22:12:45');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('eFKrsLOgLr6Ytt6fCdf9nukiibRFIl5xNCiuKp9R',NULL,'192.168.1.42','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0','eyJfdG9rZW4iOiJvM1V6UVlHQVZEdFFvdEdzTzBnYm10TUV6U0dnZUw0N3VLWWxZWTR2IiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTkyLjE2OC4xLjU5OjgwMDBcL2xvZ2luIiwicm91dGUiOiJsb2dpbiJ9fQ==',1787222797),('OqN2l3ngYgBpbjVX9QQHG3GOFLj2OukayHS3NYuu',1,'192.168.1.59','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJhMEk5STN5WUZ0dERUN1FoR3cxd1NiQk1wWkk1c3Blc2gwTXdQZzFMIiwidXJsIjpbXSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzE5Mi4xNjguMS41OTo4MDAwXC9yZXBvcnRzXC9kZWxpdmVyeSIsInJvdXRlIjoicmVwb3J0cy5kZWxpdmVyeSJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxfQ==',1787227705);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_balances`
--

DROP TABLE IF EXISTS `stock_balances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stock_balances` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `item_id` bigint unsigned NOT NULL,
  `location_id` bigint unsigned NOT NULL,
  `balance_date` date NOT NULL,
  `opening_balance` decimal(12,2) NOT NULL DEFAULT '0.00',
  `grv_quantity` decimal(12,2) NOT NULL DEFAULT '0.00',
  `istrv_quantity` decimal(12,2) NOT NULL DEFAULT '0.00',
  `siv_quantity` decimal(12,2) NOT NULL DEFAULT '0.00',
  `transfer_out_quantity` decimal(12,2) NOT NULL DEFAULT '0.00',
  `store_return_quantity` decimal(12,2) NOT NULL DEFAULT '0.00',
  `closing_balance` decimal(12,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `stock_balances_item_id_location_id_balance_date_unique` (`item_id`,`location_id`,`balance_date`),
  KEY `stock_balances_location_id_foreign` (`location_id`),
  CONSTRAINT `stock_balances_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  CONSTRAINT `stock_balances_location_id_foreign` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_balances`
--

LOCK TABLES `stock_balances` WRITE;
/*!40000 ALTER TABLE `stock_balances` DISABLE KEYS */;
/*!40000 ALTER TABLE `stock_balances` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_transactions`
--

DROP TABLE IF EXISTS `stock_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stock_transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `transaction_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_date` date NOT NULL,
  `transaction_type` enum('GRV','ISTRV','SIV','TRANSFER_OUT','STORE_RETURN','BEGINNING_BALANCE','SRV','FIV','UMIV','TTRV','FARV','UMTV','UMTRV','FGRV','FRV') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_id` bigint unsigned NOT NULL,
  `from_location_id` bigint unsigned DEFAULT NULL,
  `to_location_id` bigint unsigned DEFAULT NULL,
  `quantity` decimal(12,2) NOT NULL,
  `reference_number` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `document_number` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned NOT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `stock_transactions_transaction_number_unique` (`transaction_number`),
  KEY `stock_transactions_item_id_foreign` (`item_id`),
  KEY `stock_transactions_from_location_id_foreign` (`from_location_id`),
  KEY `stock_transactions_to_location_id_foreign` (`to_location_id`),
  KEY `stock_transactions_created_by_foreign` (`created_by`),
  KEY `stock_transactions_updated_by_foreign` (`updated_by`),
  CONSTRAINT `stock_transactions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `stock_transactions_from_location_id_foreign` FOREIGN KEY (`from_location_id`) REFERENCES `locations` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `stock_transactions_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `stock_transactions_to_location_id_foreign` FOREIGN KEY (`to_location_id`) REFERENCES `locations` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `stock_transactions_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=94 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_transactions`
--

LOCK TABLES `stock_transactions` WRITE;
/*!40000 ALTER TABLE `stock_transactions` DISABLE KEYS */;
INSERT INTO `stock_transactions` VALUES (1,'TRX-20260812123026-770','2026-07-13','BEGINNING_BALANCE',1,NULL,1,44.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(2,'TRX-20260812123026-447','2026-07-13','BEGINNING_BALANCE',1,NULL,2,34.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(3,'TRX-20260812123026-579','2026-07-13','BEGINNING_BALANCE',1,NULL,3,70.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(4,'TRX-20260812123026-632','2026-07-13','BEGINNING_BALANCE',2,NULL,1,63.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(5,'TRX-20260812123026-027','2026-07-13','BEGINNING_BALANCE',2,NULL,2,87.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(6,'TRX-20260812123026-343','2026-07-13','BEGINNING_BALANCE',2,NULL,3,45.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(7,'TRX-20260812123026-953','2026-07-13','BEGINNING_BALANCE',3,NULL,1,86.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(8,'TRX-20260812123026-466','2026-07-13','BEGINNING_BALANCE',3,NULL,2,70.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(9,'TRX-20260812123026-078','2026-07-13','BEGINNING_BALANCE',3,NULL,3,30.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(10,'TRX-20260812123026-796','2026-07-13','BEGINNING_BALANCE',4,NULL,1,96.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(11,'TRX-20260812123026-764','2026-07-13','BEGINNING_BALANCE',4,NULL,2,80.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(12,'TRX-20260812123026-922','2026-07-13','BEGINNING_BALANCE',4,NULL,3,40.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(13,'TRX-20260812123026-607','2026-07-13','BEGINNING_BALANCE',5,NULL,1,63.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(14,'TRX-20260812123026-793','2026-07-13','BEGINNING_BALANCE',5,NULL,2,47.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(15,'TRX-20260812123026-491','2026-07-13','BEGINNING_BALANCE',5,NULL,3,28.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(16,'TRX-20260812123026-222','2026-07-13','BEGINNING_BALANCE',6,NULL,1,31.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(17,'TRX-20260812123026-555','2026-07-13','BEGINNING_BALANCE',6,NULL,2,29.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(18,'TRX-20260812123026-872','2026-07-13','BEGINNING_BALANCE',6,NULL,3,51.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(19,'TRX-20260812123026-058','2026-07-13','BEGINNING_BALANCE',7,NULL,1,25.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(20,'TRX-20260812123026-494','2026-07-13','BEGINNING_BALANCE',7,NULL,2,48.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(21,'TRX-20260812123026-877','2026-07-13','BEGINNING_BALANCE',7,NULL,3,51.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(22,'TRX-20260812123026-602','2026-07-13','BEGINNING_BALANCE',8,NULL,1,93.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(23,'TRX-20260812123026-546','2026-07-13','BEGINNING_BALANCE',8,NULL,2,81.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(24,'TRX-20260812123026-854','2026-07-13','BEGINNING_BALANCE',8,NULL,3,62.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(25,'TRX-20260812123026-136','2026-07-13','BEGINNING_BALANCE',9,NULL,1,50.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(26,'TRX-20260812123026-255','2026-07-13','BEGINNING_BALANCE',9,NULL,2,68.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(27,'TRX-20260812123026-758','2026-07-13','BEGINNING_BALANCE',9,NULL,3,57.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(28,'TRX-20260812123026-657','2026-07-13','BEGINNING_BALANCE',10,NULL,1,36.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(29,'TRX-20260812123026-056','2026-07-13','BEGINNING_BALANCE',10,NULL,2,44.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(30,'TRX-20260812123026-217','2026-07-13','BEGINNING_BALANCE',10,NULL,3,44.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(31,'TRX-20260812123026-378','2026-07-13','BEGINNING_BALANCE',11,NULL,1,53.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(32,'TRX-20260812123026-243','2026-07-13','BEGINNING_BALANCE',11,NULL,2,30.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(33,'TRX-20260812123026-091','2026-07-13','BEGINNING_BALANCE',11,NULL,3,24.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(34,'TRX-20260812123026-706','2026-07-13','BEGINNING_BALANCE',12,NULL,1,56.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(35,'TRX-20260812123026-345','2026-07-13','BEGINNING_BALANCE',12,NULL,2,37.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(36,'TRX-20260812123026-768','2026-07-13','BEGINNING_BALANCE',12,NULL,3,44.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(37,'TRX-20260812123026-172','2026-07-13','BEGINNING_BALANCE',13,NULL,1,74.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(38,'TRX-20260812123026-452','2026-07-13','BEGINNING_BALANCE',13,NULL,2,50.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(39,'TRX-20260812123026-878','2026-07-13','BEGINNING_BALANCE',13,NULL,3,92.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(40,'TRX-20260812123026-765','2026-07-13','BEGINNING_BALANCE',14,NULL,1,21.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(41,'TRX-20260812123026-635','2026-07-13','BEGINNING_BALANCE',14,NULL,2,96.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(42,'TRX-20260812123026-480','2026-07-13','BEGINNING_BALANCE',14,NULL,3,33.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(43,'TRX-20260812123026-715','2026-07-13','BEGINNING_BALANCE',15,NULL,1,22.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(44,'TRX-20260812123026-615','2026-07-13','BEGINNING_BALANCE',15,NULL,2,51.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(45,'TRX-20260812123026-924','2026-07-13','BEGINNING_BALANCE',15,NULL,3,82.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(46,'TRX-20260812123026-018','2026-07-13','BEGINNING_BALANCE',16,NULL,1,39.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(47,'TRX-20260812123026-215','2026-07-13','BEGINNING_BALANCE',16,NULL,2,37.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(48,'TRX-20260812123026-784','2026-07-13','BEGINNING_BALANCE',16,NULL,3,96.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(49,'TRX-20260812123026-809','2026-07-13','BEGINNING_BALANCE',17,NULL,1,84.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(50,'TRX-20260812123026-928','2026-07-13','BEGINNING_BALANCE',17,NULL,2,75.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(51,'TRX-20260812123026-200','2026-07-13','BEGINNING_BALANCE',17,NULL,3,84.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(52,'TRX-20260812123026-686','2026-07-13','BEGINNING_BALANCE',18,NULL,1,57.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(53,'TRX-20260812123026-401','2026-07-13','BEGINNING_BALANCE',18,NULL,2,23.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(54,'TRX-20260812123026-445','2026-07-13','BEGINNING_BALANCE',18,NULL,3,58.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(55,'TRX-20260812123026-032','2026-07-13','BEGINNING_BALANCE',19,NULL,1,53.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(56,'TRX-20260812123026-550','2026-07-13','BEGINNING_BALANCE',19,NULL,2,58.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(57,'TRX-20260812123026-822','2026-07-13','BEGINNING_BALANCE',19,NULL,3,50.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(58,'TRX-20260812123026-064','2026-07-13','BEGINNING_BALANCE',20,NULL,1,36.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(59,'TRX-20260812123026-353','2026-07-13','BEGINNING_BALANCE',20,NULL,2,76.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(60,'TRX-20260812123026-663','2026-07-13','BEGINNING_BALANCE',20,NULL,3,100.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(61,'TRX-20260812123026-497','2026-07-13','BEGINNING_BALANCE',21,NULL,1,94.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(62,'TRX-20260812123026-305','2026-07-13','BEGINNING_BALANCE',21,NULL,2,54.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(63,'TRX-20260812123026-386','2026-07-13','BEGINNING_BALANCE',21,NULL,3,51.00,'OPEN-BAL-2026',NULL,'Opening balance',1,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(65,'TRX-20260812123702-309','2026-08-12','GRV',5,3,3,45.00,'46546','9865',NULL,1,NULL,'2026-08-12 09:37:02','2026-08-12 09:37:02',NULL),(66,'TRX-20260812140049-152','2026-08-17','GRV',1,NULL,5,52.00,'46546','9865',NULL,1,1,'2026-08-12 11:00:49','2026-08-17 07:58:44',NULL),(67,'TRX-20260813114912-227','2026-08-13','ISTRV',1,1,2,101.00,'59606',NULL,NULL,1,NULL,'2026-08-13 08:49:12','2026-08-13 08:49:12',NULL),(68,'TRX-20260813141932-227','2026-08-18','TRANSFER_OUT',2,9,14,50.00,'46546','9865',NULL,1,1,'2026-08-13 11:19:32','2026-08-18 13:36:55',NULL),(69,'TRX-20260813142054-828','2026-08-18','TRANSFER_OUT',2,5,NULL,50.00,'46546','9865',NULL,1,1,'2026-08-13 11:20:54','2026-08-18 13:37:01',NULL),(70,'TRX-20260814014658-782','2026-08-14','GRV',1,NULL,8,120.00,'745544','787775',NULL,1,NULL,'2026-08-13 22:46:58','2026-08-13 22:46:58',NULL),(71,'TRX-20260814014706-301','2026-08-14','GRV',1,NULL,8,120.00,'745544','787775',NULL,1,NULL,'2026-08-13 22:47:06','2026-08-13 22:47:06',NULL),(72,'TRX-20260814014712-406','2026-08-14','GRV',1,NULL,8,120.00,'745544','787775',NULL,1,NULL,'2026-08-13 22:47:12','2026-08-13 22:47:12',NULL),(73,'TRX-20260814014718-575','2026-08-14','GRV',1,NULL,8,120.00,'745544','787775',NULL,1,NULL,'2026-08-13 22:47:18','2026-08-13 22:47:18',NULL),(74,'TRX-20260814014939-325','2026-08-14','GRV',1,NULL,8,120.00,'745544','787775',NULL,1,NULL,'2026-08-13 22:49:39','2026-08-13 22:49:39',NULL),(75,'TRX-20260814024031-315','2026-08-14','ISTRV',3,29,84,55.00,'gh44333',NULL,NULL,2,NULL,'2026-08-13 23:40:31','2026-08-13 23:40:31',NULL),(76,'TRX-20260817113029-628','2026-08-17','ISTRV',11,5,7,12.00,'59606',NULL,NULL,1,NULL,'2026-08-17 08:30:29','2026-08-17 08:30:29',NULL),(77,'TRX-20260817145400-667','2026-08-17','ISTRV',1,5,7,51.00,'115236',NULL,NULL,1,NULL,'2026-08-17 11:54:00','2026-08-17 11:54:00',NULL),(78,'TRX-20260818134259-108','2026-08-18','ISTRV',15,NULL,9,444.00,'59606','9865','rwsfsf',1,NULL,'2026-08-18 10:42:59','2026-08-18 10:42:59',NULL),(79,'TRX-20260818135630-136','2026-08-18','GRV',2,NULL,7,55.00,'59606','787775',NULL,1,NULL,'2026-08-18 10:56:30','2026-08-18 10:56:30',NULL),(80,'TRX-20260818140621-515','2026-08-18','ISTRV',3,NULL,39,100.00,'46546','9865','fpjhjjgbjbgb',1,NULL,'2026-08-18 11:06:21','2026-08-18 11:06:21',NULL),(81,'TRX-20260818140751-808','2026-08-18','STORE_RETURN',1,8,7,44.00,'59606','hgvbnb  v','545948',1,1,'2026-08-18 11:07:51','2026-08-18 11:09:21',NULL),(82,'TRX-20260818141026-525','2026-08-18','UMTRV',15,NULL,25,44.00,'46546','7666hhf','ggdgshxf',1,1,'2026-08-18 11:10:26','2026-08-18 11:10:40',NULL),(83,'TRX-20260818142002-573','2026-08-18','ISTRV',11,5,7,23.00,'4545','45778','gjhbkjhgh',1,NULL,'2026-08-18 11:20:02','2026-08-18 11:20:02',NULL),(84,'TRX-20260818143038-353','2026-08-18','ISTRV',11,5,69,80.00,'4543','45779','54666',1,NULL,'2026-08-18 11:30:38','2026-08-18 11:30:38',NULL),(85,'TRX-20260818144041-688','2026-08-18','ISTRV',11,5,8,55.00,'4545','45778,6655,4545,5666','kjhlkljbh',1,NULL,'2026-08-18 11:40:41','2026-08-18 11:40:41',NULL),(86,'TRX-20260819140259-731','2026-08-19','FRV',26,NULL,10,14.99,'59606','gvb jnbngb','654545',1,1,'2026-08-19 11:02:59','2026-08-19 11:20:56',NULL),(87,'TRX-20260820120250-416','2026-08-20','ISTRV',2,88,84,15.00,'545454','84545','dghbvhg',1,NULL,'2026-08-20 09:02:50','2026-08-20 09:02:50',NULL),(88,'TRX-20260820130310-036','2026-08-20','SIV',1,5,NULL,50.00,'59606','655','cgfc',1,NULL,'2026-08-20 10:03:10','2026-08-20 10:13:53','2026-08-20 10:13:53'),(89,'TRX-20260820131834-609','2026-08-20','TRANSFER_OUT',1,5,84,20.00,'3555','9865','dghbvhg',1,NULL,'2026-08-20 10:18:34','2026-08-20 10:18:34',NULL),(90,'TRX-20260820131911-573','2026-08-20','SIV',1,5,NULL,10.00,'44333','5445','dghbvhg',1,NULL,'2026-08-20 10:19:11','2026-08-20 10:19:11',NULL),(91,'TRX-20260820133908-560','2026-08-20','SIV',1,5,NULL,22.00,'59606',NULL,'fdgfd',1,NULL,'2026-08-20 10:39:08','2026-08-20 10:39:08',NULL),(92,'TRX-20260820134147-197','2026-08-20','ISTRV',1,5,84,10.00,'4444','454545','sdfgdgv',1,NULL,'2026-08-20 10:41:47','2026-08-20 10:41:47',NULL),(93,'TRX-20260820141849-915','2026-08-20','UMTRV',41,5,6,12.00,'545454','84545','54454',1,NULL,'2026-08-20 11:18:49','2026-08-20 11:18:49',NULL);
/*!40000 ALTER TABLE `stock_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_project_assignments`
--

DROP TABLE IF EXISTS `user_project_assignments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_project_assignments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `location_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_project_assignments_user_id_location_id_unique` (`user_id`,`location_id`),
  KEY `user_project_assignments_user_id_index` (`user_id`),
  KEY `user_project_assignments_location_id_index` (`location_id`),
  CONSTRAINT `user_project_assignments_location_id_foreign` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_project_assignments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_project_assignments`
--

LOCK TABLES `user_project_assignments` WRITE;
/*!40000 ALTER TABLE `user_project_assignments` DISABLE KEYS */;
INSERT INTO `user_project_assignments` VALUES (6,6,39,'2026-08-13 20:33:29','2026-08-13 20:33:29'),(7,6,71,'2026-08-13 20:33:29','2026-08-13 20:33:29'),(8,6,57,'2026-08-13 20:33:29','2026-08-13 20:33:29'),(9,6,19,'2026-08-13 22:27:15','2026-08-13 22:27:15'),(10,6,82,'2026-08-13 22:27:15','2026-08-13 22:27:15'),(11,2,84,'2026-08-13 23:18:52','2026-08-13 23:18:52'),(12,2,27,'2026-08-13 23:18:52','2026-08-13 23:18:52'),(13,2,29,'2026-08-13 23:18:52','2026-08-13 23:18:52');
/*!40000 ALTER TABLE `user_project_assignments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_id` bigint unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hint` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `security_question` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `security_answer` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_location_id_foreign` (`location_id`),
  CONSTRAINT `users_location_id_foreign` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Administrator','admin@mims.com','+251911234567',1,1,NULL,'$2y$12$pZdREBxoEz7Yh/kNr0CCsO42kDY6uqkFfzpQ3YWhJOUvcV5axSkVi',NULL,NULL,NULL,NULL,'2026-08-12 09:30:25','2026-08-12 09:30:25',NULL),(2,'Site Storekeeper','storekeeper@mims.com','+251922345678',NULL,1,NULL,'$2y$12$M/SN6g1uthkzhAhCOmsYCOCM.GhbuVAqLlhzPpRP0HNUY06buaHO6',NULL,NULL,NULL,NULL,'2026-08-12 09:30:26','2026-08-13 23:18:52',NULL),(3,'Project Manager','manager@mims.com','+251933456789',2,1,NULL,'$2y$12$MD18pAeg5Jbfq.NmUGQ7f.kZSNiBU.MmB1S5qOIwoDjJKvC4r9v1e',NULL,NULL,NULL,NULL,'2026-08-12 09:30:26','2026-08-12 09:30:26',NULL),(4,'General Manager','gm@mims.com','+251922345678',1,1,NULL,'$2y$12$FH6YvhMpCbvozkWBYo1G2.QvsDEPaegycQWbE1HmrozkjIZFM6tD.',NULL,NULL,NULL,NULL,'2026-08-13 11:04:40','2026-08-13 11:04:40',NULL),(5,'Stock Checker','checker@mims.com','+251944567890',1,1,NULL,'$2y$12$D8aV2.OOBaxgMtJWgAsSB.Gpj0c60oiOHM5aYJiF6diXinkpuIsYO',NULL,NULL,NULL,NULL,'2026-08-13 11:04:41','2026-08-13 11:04:41',NULL),(6,'Site Engineer','engineer@mims.com','+251966789012',NULL,1,NULL,'$2y$12$m7QNdaG72eNcXBhYxRdgOuaSSx7NP52bqsEV8E7mV6NPApfTJWaGG',NULL,NULL,NULL,NULL,'2026-08-13 11:04:41','2026-08-13 20:33:29',NULL),(7,'Head Office Store','headoffice@mims.com','+251977123456',5,1,NULL,'$2y$12$LLti8RsF1wGnkV2F/UH9C.7NZDhzvmL8M1/diC9f01noLCXqnJTV.',NULL,NULL,NULL,NULL,'2026-08-13 22:12:45','2026-08-13 22:12:45',NULL),(8,'Master Data Manager','masterdata@mims.com','+251988123456',5,1,NULL,'$2y$12$HQDePgQvhmJ4btYWJ5DFfucwGtGA7P7vDF9uiCe/hjd7ck8HqDx3y',NULL,NULL,NULL,NULL,'2026-08-13 22:12:46','2026-08-13 22:12:46',NULL),(9,'amare','amare@tnt-constructions.com',NULL,5,1,NULL,'$2y$12$mIGo5Vl2l1h/3HM9oy3wq.I1WKK8B3AmiEGaJc4Mi9WZNkbsFDgkO',NULL,NULL,NULL,NULL,'2026-08-19 07:40:21','2026-08-19 07:40:21',NULL);
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

-- Dump completed on 2026-08-20 15:16:13
