-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: uhalisia_portal
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Dumping data for table `assignments`
--

LOCK TABLES `assignments` WRITE;
/*!40000 ALTER TABLE `assignments` DISABLE KEYS */;
/*!40000 ALTER TABLE `assignments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `booking_days`
--

LOCK TABLES `booking_days` WRITE;
/*!40000 ALTER TABLE `booking_days` DISABLE KEYS */;
INSERT INTO `booking_days` VALUES (1,1,1,'2026-11-10','Arrival in Arusha','Day 1: Arrival in Arusha. Experience Airport reception, transfer to hotel, safari orientation. Staying at The African Tulip Hotel.','Arusha','The African Tulip Hotel','Dinner','Airport reception, transfer to hotel, safari orientation.',NULL,'2026-10-26 08:16:40','2026-10-26 08:16:40'),(2,1,2,'2026-11-11','Arusha to Tarangire National Park','Day 2: Arusha to Tarangire National Park. Experience Game drive along Tarangire river, elephant sightings, birding. Staying at Maramboi Tented Camp.','Tarangire','Maramboi Tented Camp','Breakfast, Lunch, Dinner','Game drive along Tarangire river, elephant sightings, birding.',NULL,'2026-10-26 08:16:40','2026-10-26 08:16:40'),(3,1,3,'2026-11-12','Tarangire to Lake Manyara National Park','Day 3: Tarangire to Lake Manyara National Park. Experience Groundwater forest canopy walk, baboon troops, flamingo viewing. Staying at Escarpment Luxury Lodge.','Lake Manyara','Escarpment Luxury Lodge','Breakfast, Lunch, Dinner','Groundwater forest canopy walk, baboon troops, flamingo viewing.',NULL,'2026-10-26 08:16:40','2026-10-26 08:16:40'),(4,1,4,'2026-11-13','Lake Manyara to Serengeti (Seronera)','Day 4: Lake Manyara to Serengeti (Seronera). Experience Cross Ngorongoro conservation area, enter endless plains, kopjes game drive. Staying at Osinon Camp Serengeti.','Central Serengeti','Osinon Camp Serengeti','Breakfast, Lunch, Dinner','Cross Ngorongoro conservation area, enter endless plains, kopjes game drive.',NULL,'2026-10-26 08:16:40','2026-10-26 08:16:40'),(5,1,5,'2026-11-14','Full Day Serengeti Big Cats Tracking','Day 5: Full Day Serengeti Big Cats Tracking. Experience Sunrise game drive, Retima hippo pool, predator tracking. Staying at Osinon Camp Serengeti.','Central Serengeti','Osinon Camp Serengeti','Breakfast, Lunch, Dinner','Sunrise game drive, Retima hippo pool, predator tracking.',NULL,'2026-10-26 08:16:40','2026-10-26 08:16:40'),(6,1,6,'2026-11-15','Serengeti Wildlife & Migration Corridors','Day 6: Serengeti Wildlife & Migration Corridors. Experience Following the Great Migration herds across the central savannah. Staying at Osinon Camp Serengeti.','Serengeti','Osinon Camp Serengeti','Breakfast, Lunch, Dinner','Following the Great Migration herds across the central savannah.',NULL,'2026-10-26 08:16:40','2026-10-26 08:16:40'),(7,1,7,'2026-11-16','Serengeti to Ngorongoro Conservation Area','Day 7: Serengeti to Ngorongoro Conservation Area. Experience Visit Olduvai Gorge anthropological museum, crater rim view. Staying at Rhino Lodge.','Ngorongoro Highlands','Rhino Lodge','Breakfast, Lunch, Dinner','Visit Olduvai Gorge anthropological museum, crater rim view.',NULL,'2026-10-26 08:16:40','2026-10-26 08:16:40'),(8,1,8,'2026-11-17','Ngorongoro Crater Floor Safari','Day 8: Ngorongoro Crater Floor Safari. Experience Descent 600m to crater floor, Big Five tracking, lakeside picnic. Staying at Bougainvillea Safari Lodge.','Ngorongoro Crater','Bougainvillea Safari Lodge','Breakfast, Lunch, Dinner','Descent 600m to crater floor, Big Five tracking, lakeside picnic.',NULL,'2026-10-26 08:16:40','2026-10-26 08:16:40'),(9,1,9,'2026-11-18','Karatu Coffee Tour & Mto wa Mbu Cultural Walk','Day 9: Karatu Coffee Tour & Mto wa Mbu Cultural Walk. Experience Coffee farm walking tour, village banana plantation tour, local lunch. Staying at Ilboru Safari Lodge.','Karatu / Mto wa Mbu','Ilboru Safari Lodge','Breakfast, Lunch, Dinner','Coffee farm walking tour, village banana plantation tour, local lunch.',NULL,'2026-10-26 08:16:40','2026-10-26 08:16:40'),(10,1,10,'2026-11-19','Arusha City Tour & Departure','Day 10: Arusha City Tour & Departure. Experience Tanzanite experience museum, souvenir craft market, airport drop-off. Staying at Departure.','Arusha / JRO','Departure','Breakfast, Lunch','Tanzanite experience museum, souvenir craft market, airport drop-off.',NULL,'2026-10-26 08:16:40','2026-10-26 08:16:40'),(11,2,1,'2026-10-24','Welcome to Tanzania (Arusha)','Day 1: Welcome to Tanzania (Arusha). Experience Meet and greet at airport, brief and overnight in Arusha. Staying at Kibo Palace Hotel.','Arusha','Kibo Palace Hotel','Dinner','Meet and greet at airport, brief and overnight in Arusha.',NULL,'2026-10-26 08:16:41','2026-10-26 08:16:41'),(12,2,2,'2026-10-25','Arusha to Tarangire National Park','Day 2: Arusha to Tarangire National Park. Experience Game drive with picnic lunch, baobab trees and tree climbing pythons. Staying at Sangaiwe Tented Lodge.','Tarangire','Sangaiwe Tented Lodge','Breakfast, Lunch, Dinner','Game drive with picnic lunch, baobab trees and tree climbing pythons.',NULL,'2026-10-26 08:16:41','2026-10-26 08:16:41'),(13,2,3,'2026-10-26','Tarangire to Lake Manyara','Day 3: Tarangire to Lake Manyara. Experience Afternoon game drive in Lake Manyara National Park. Staying at Farm House Valley Karatu.','Lake Manyara','Farm House Valley Karatu','Breakfast, Lunch, Dinner','Afternoon game drive in Lake Manyara National Park.',NULL,'2026-10-26 08:16:41','2026-10-26 08:16:41'),(14,2,4,'2026-10-27','Karatu to Central Serengeti','Day 4: Karatu to Central Serengeti. Experience Transit via Ngorongoro highlands into the great Serengeti plains. Staying at Kati Kati Tented Camp.','Serengeti','Kati Kati Tented Camp','Breakfast, Lunch, Dinner','Transit via Ngorongoro highlands into the great Serengeti plains.',NULL,'2026-10-26 08:16:41','2026-10-26 08:16:41'),(15,2,5,'2026-10-28','Serengeti Full Day Game Viewing','Day 5: Serengeti Full Day Game Viewing. Experience Full day tracking lions, cheetahs, leopards, zebras and wildebeest. Staying at Kati Kati Tented Camp.','Serengeti','Kati Kati Tented Camp','Breakfast, Lunch, Dinner','Full day tracking lions, cheetahs, leopards, zebras and wildebeest.',NULL,'2026-10-26 08:16:41','2026-10-26 08:16:41'),(16,2,6,'2026-10-29','Serengeti to Western or Northern Sector','Day 6: Serengeti to Western or Northern Sector. Experience Following migratory movements, sunset sundowner on the savannah. Staying at Kati Kati Tented Camp.','Serengeti','Kati Kati Tented Camp','Breakfast, Lunch, Dinner','Following migratory movements, sunset sundowner on the savannah.',NULL,'2026-10-26 08:16:41','2026-10-26 08:16:41'),(17,2,7,'2026-10-30','Serengeti to Ngorongoro Conservation Area','Day 7: Serengeti to Ngorongoro Conservation Area. Experience Morning game drive, afternoon drive to Karatu highlands. Staying at Ngorongoro Farm House.','Ngorongoro','Ngorongoro Farm House','Breakfast, Lunch, Dinner','Morning game drive, afternoon drive to Karatu highlands.',NULL,'2026-10-26 08:16:41','2026-10-26 08:16:41'),(18,2,8,'2026-10-31','Full Day Ngorongoro Crater Tour','Day 8: Full Day Ngorongoro Crater Tour. Experience Crater descent, high wildlife density observation, return to lodge. Staying at Ngorongoro Farm House.','Ngorongoro Crater','Ngorongoro Farm House','Breakfast, Lunch, Dinner','Crater descent, high wildlife density observation, return to lodge.',NULL,'2026-10-26 08:16:41','2026-10-26 08:16:41'),(19,2,9,'2026-11-01','Return to Arusha & Flight Connection','Day 9: Return to Arusha & Flight Connection. Experience Scenic drive back to Arusha, day room and airport transfer. Staying at Departure.','Arusha','Departure','Breakfast, Lunch','Scenic drive back to Arusha, day room and airport transfer.',NULL,'2026-10-26 08:16:41','2026-10-26 08:16:41');
/*!40000 ALTER TABLE `booking_days` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `bookings`
--

LOCK TABLES `bookings` WRITE;
/*!40000 ALTER TABLE `bookings` DISABLE KEYS */;
INSERT INTO `bookings` VALUES (1,'UWT-2026-0001',1,1,2,'10 Days Northern Circuit Safari - Jenkins Couple','2026-11-10','2026-11-19',2,0,'confirmed','company_handles',6900.00,200.00,'Complimentary bottle of sparkling wine in Serengeti for honeymoon celebration.','BWWTW9F3uPDgAflznoXAZPQtx8m4QMoy9YO0uLH1nUc4LpE2czDUbzUA3FZAIG5A',2,NULL,'2026-10-26 08:16:40','2026-10-26 08:16:40'),(2,'UWT-2026-0002',2,NULL,3,'9 Days Migration Safari - Schmidt Party','2026-10-24','2026-11-01',2,0,'in_progress','company_handles',6300.00,0.00,'Currently in Serengeti, requested extra packed lunch for full-day game drive.','dUeXF49l5GtchRuAWbydYWeRKyqJeKHlVTJOS3BaMkrSSsF2zum3338EWARAVinU',2,NULL,'2026-10-26 08:16:41','2026-10-26 08:16:41');
/*!40000 ALTER TABLE `bookings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `clients`
--

LOCK TABLES `clients` WRITE;
/*!40000 ALTER TABLE `clients` DISABLE KEYS */;
INSERT INTO `clients` VALUES (1,'Sarah Jenkins','sarah.jenkins@example.com','+44 7700 900123','United Kingdom','Honeymoon couple, interested in big cats and luxury tented camps.',NULL,'2026-10-26 08:16:40','2026-10-26 08:16:40'),(2,'Johann Schmidt','johann.schmidt@example.de','+49 151 23456789','Germany','Photography enthusiast with 600mm lens, requested window seats.',NULL,'2026-10-26 08:16:40','2026-10-26 08:16:40'),(3,'Michelle Dupont','michelle.dupont@example.fr','+33 6 12 34 56 78','France','Vegetarian dietary requirement, requested French-speaking guide if available.',NULL,'2026-10-26 08:16:40','2026-10-26 08:16:40'),(4,'Demo','demo@gmail.com','+255754215859','Tanzania',NULL,NULL,'2026-10-27 04:34:57','2026-10-27 04:34:57');
/*!40000 ALTER TABLE `clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `documents`
--

LOCK TABLES `documents` WRITE;
/*!40000 ALTER TABLE `documents` DISABLE KEYS */;
/*!40000 ALTER TABLE `documents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `drivers`
--

LOCK TABLES `drivers` WRITE;
/*!40000 ALTER TABLE `drivers` DISABLE KEYS */;
INSERT INTO `drivers` VALUES (1,'Emmanuel Mushi','+255 754 112 233','English, Swahili','DL-TZ-88410',1,'2026-10-26 08:16:40','2026-10-26 08:16:40'),(2,'Juma Rashid','+255 765 223 344','English, Swahili, French','DL-TZ-77192',1,'2026-10-26 08:16:40','2026-10-26 08:16:40'),(3,'David Mollel','+255 784 334 455','English, Swahili, German','DL-TZ-66281',1,'2026-10-26 08:16:40','2026-10-26 08:16:40'),(4,'Salim Bakari','+255 713 445 566','English, Swahili','DL-TZ-55374',1,'2026-10-26 08:16:40','2026-10-26 08:16:40');
/*!40000 ALTER TABLE `drivers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `exchange_rates`
--

LOCK TABLES `exchange_rates` WRITE;
/*!40000 ALTER TABLE `exchange_rates` DISABLE KEYS */;
INSERT INTO `exchange_rates` VALUES (1,2026,1,2580.00,'2026-10-26 08:16:40','2026-10-26 08:16:40'),(2,2026,2,2590.00,'2026-10-26 08:16:40','2026-10-26 08:16:40'),(3,2026,3,2600.00,'2026-10-26 08:16:40','2026-10-26 08:16:40'),(4,2026,4,2610.00,'2026-10-26 08:16:40','2026-10-26 08:16:40'),(5,2026,5,2615.00,'2026-10-26 08:16:40','2026-10-26 08:16:40'),(6,2026,6,2620.00,'2026-10-26 08:16:40','2026-10-26 08:16:40'),(7,2026,7,2630.00,'2026-10-26 08:16:40','2026-10-26 08:16:40'),(8,2026,8,2640.00,'2026-10-26 08:16:40','2026-10-26 08:16:40'),(9,2026,9,2645.00,'2026-10-26 08:16:40','2026-10-26 08:16:40'),(10,2026,10,2650.00,'2026-10-26 08:16:40','2026-10-26 08:16:40'),(11,2026,11,2655.00,'2026-10-26 08:16:40','2026-10-26 08:16:40'),(12,2026,12,2660.00,'2026-10-26 08:16:40','2026-10-26 08:16:40');
/*!40000 ALTER TABLE `exchange_rates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `expenses`
--

LOCK TABLES `expenses` WRITE;
/*!40000 ALTER TABLE `expenses` DISABLE KEYS */;
/*!40000 ALTER TABLE `expenses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `guides`
--

LOCK TABLES `guides` WRITE;
/*!40000 ALTER TABLE `guides` DISABLE KEYS */;
INSERT INTO `guides` VALUES (1,'Peter Kimaro','+255 782 556 677','English, Swahili, Spanish','KILI-GUIDE-3310',1,'2026-10-26 08:16:40','2026-10-26 08:16:40'),(2,'Baraka Mrema','+255 759 667 788','English, Swahili, Italian','TANAPA-NAT-1402',1,'2026-10-26 08:16:40','2026-10-26 08:16:40'),(3,'Goodluck Shirima','+255 768 778 899','English, Swahili','TANAPA-NAT-1598',1,'2026-10-26 08:16:40','2026-10-26 08:16:40');
/*!40000 ALTER TABLE `guides` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `leads`
--

LOCK TABLES `leads` WRITE;
/*!40000 ALTER TABLE `leads` DISABLE KEYS */;
INSERT INTO `leads` VALUES (1,1,'Sarah Jenkins','sarah.jenkins@example.com','United Kingdom','10-days-safaris','Looking for a 10-day northern circuit safari for two adults next month.','website_quote_form','google','cpc','won',2,NULL,'2026-10-26 08:16:40','2026-10-26 08:16:40'),(2,NULL,'Alessandro Rossi','alex.rossi@example.it','Italy','6-days-umbwe-route','Interested in climbing Kilimanjaro via Umbwe route with a private guide.','book_form','tripadvisor','referral','new',2,NULL,'2026-10-26 08:16:40','2026-10-26 08:16:40'),(3,NULL,'Emily Clark','emily.clark@example.com','United States','bush-to-beach','Family vacation with 2 kids, wildlife safari plus 4 days in Zanzibar.','website_quote_form','facebook','social','quoted',2,NULL,'2026-10-26 08:16:40','2026-10-26 08:16:40'),(4,4,'Demo','demo@gmail.com','Tanzania','5 Days Mount Kilimanjaro Climb (Marangu Route) (Migration)','Preferred Start Date: 2026-10-28\nTravelers: 2 Adult(s), 1 Child(ren)\nPhone / WhatsApp: +255754215859','book_form',NULL,NULL,'new',NULL,'{\"utm_source\":null,\"utm_medium\":null,\"tour_slug\":\"5-days-marangu-route\",\"safari_interest\":\"Migration\",\"start_date\":\"2026-10-28\",\"adults\":\"2\",\"children\":\"1\",\"name\":\"Demo\",\"email\":\"demo@gmail.com\",\"phone\":\"+255754215859\",\"country\":\"Tanzania\",\"message\":null}','2026-10-27 04:34:57','2026-10-27 04:34:57');
/*!40000 ALTER TABLE `leads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_10_26_000001_create_clients_and_leads_tables',1),(5,'2026_10_26_000002_create_tours_and_tour_days_tables',1),(6,'2026_10_26_000003_create_bookings_and_booking_days_tables',1),(7,'2026_10_26_000004_create_travelers_table',1),(8,'2026_10_26_000005_create_resources_and_assignments_tables',1),(9,'2026_10_26_000006_create_exchange_rates_and_payments_tables',1),(10,'2026_10_26_000007_create_expenses_table',1),(11,'2026_10_26_000008_create_documents_table',1),(12,'2026_10_26_000009_create_status_history_and_settings_tables',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
INSERT INTO `payments` VALUES (1,1,'2026-10-21',2010.00,'USD',2650.00,2010.00,5326500.00,'bank','BNK-2026-0491',3,'30% deposit received via wire transfer.','deposit','2026-10-26 08:16:41','2026-10-26 08:16:41'),(2,2,'2026-10-06',6300.00,'USD',2640.00,6300.00,16632000.00,'bank','BNK-2026-0312',3,'Paid in full prior to arrival.','balance','2026-10-26 08:16:41','2026-10-26 08:16:41');
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('58kTGAeZk7iqsPRE6gtKAfmbZlDInudDun4HdwMp',NULL,'127.0.0.1','','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTVhsbUJra3U5d1FKY0FUaGxCVnRXdlo5OGtaUzBydWt1SWFMaU1OMSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTE6Imh0dHA6Ly8xMjcuMC4wLjEvdWhhbGlzaWEtcG9ydGFsL3B1YmxpYy9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoxMToiYWRtaW4ubG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1793084908),('AsBmifPpOwlBtQjZsaTfNIoznmVIBWoO7HqxV5oz',NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiaE5mSnR0N2ZPSUFBaWxFWTdMdnVqS3FCZU1FOGpkS2pKZHpOandNeSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDY6Imh0dHA6Ly9sb2NhbGhvc3QvdWhhbGlzaWEtcG9ydGFsL3B1YmxpYy9jbGllbnQiO3M6NToicm91dGUiO3M6MTI6ImNsaWVudC5sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1793090263),('eJtALDCnsReBauxSkWorrzHeb2HCCfh6IA8KAiN9',NULL,'127.0.0.1','','YTozOntzOjY6Il90b2tlbiI7czo0MDoicDZmaDB0MDFScUQyRFJOc1pZd0l4WWhHelE5dnVKUnhSZDdXVEtYOCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly8xMjcuMC4wLjEvdWhhbGlzaWEtcG9ydGFsL3B1YmxpYyI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1793085580),('ghbz91beX6ieBLoFNC5DbLQ4QTS6l8pHoHFdIyfG',NULL,'127.0.0.1','','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQTI2QXVlWkRzdEc4alpYOVg5MUJVcms1TVFQMFZpb1hwZE42MlJKRyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDQ6Imh0dHA6Ly8xMjcuMC4wLjEvdWhhbGlzaWEtcG9ydGFsL3B1YmxpYy9ib29rIjtzOjU6InJvdXRlIjtzOjk6ImJvb2suc2hvdyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1793084834),('iAVhp1ceFhIRnZRSc4XyRDFAgxz64ejuTcHXOjGb',NULL,'127.0.0.1','','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQldjZ2FPMWhyaUxvZHp3djVhemRLUFV4cmJic3ZDZXFuNkxaRjZoWiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDQ6Imh0dHA6Ly8xMjcuMC4wLjEvdWhhbGlzaWEtcG9ydGFsL3B1YmxpYy9ib29rIjtzOjU6InJvdXRlIjtzOjk6ImJvb2suc2hvdyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1793085580),('q7mQn3W4pBQmA23G99cpS8yK0LtKGMGzNUznDYF9',NULL,'127.0.0.1','','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZ0o0TkJJU3BSem04RjViUnB5T2tNaVFwTEhjSTJ3MEk2RWE2UHhDbyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDQ6Imh0dHA6Ly8xMjcuMC4wLjEvdWhhbGlzaWEtcG9ydGFsL3B1YmxpYy9ib29rIjtzOjU6InJvdXRlIjtzOjk6ImJvb2suc2hvdyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1793085723),('wrQimcpa77dKPEdpVxu8xcewroLhL6i31jG4Vaoy',2,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiTUlmcUVySUF1ZFdndzJ5VTRieTRXTGYzNlZRcmhOaHd1WGdVdUJOUSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTY6Imh0dHA6Ly9sb2NhbGhvc3QvdWhhbGlzaWEtcG9ydGFsL3B1YmxpYy9hZG1pbi9ib29raW5ncy8xIjtzOjU6InJvdXRlIjtzOjE5OiJhZG1pbi5ib29raW5ncy5zaG93Ijt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjtzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2NDoiNzU4MjE3ZWQzMDQzNDU3NmZmYTg3NjZkNWYxZjFhMWUzZDUwOTRjN2E0ZTFjMGZjNjVkZWE1YjIzMzlmZTJlZiI7fQ==',1793091075);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'company_name','Uhalisia wa Tanzania Company Ltd','2026-10-26 08:16:39','2026-10-26 08:16:39'),(2,'company_tagline','Safaris and Tours','2026-10-26 08:16:39','2026-10-26 08:16:39'),(3,'company_email','info@uhalisiawatanzania.com','2026-10-26 08:16:39','2026-10-26 08:16:39'),(4,'company_phone','+255 784 590 722','2026-10-26 08:16:39','2026-10-26 08:16:39'),(5,'company_website','www.uhalisiawatanzania.com','2026-10-26 08:16:39','2026-10-26 08:16:39'),(6,'company_offices','Arusha & Dar es Salaam, Tanzania','2026-10-26 08:16:39','2026-10-26 08:16:39'),(7,'quote_validity_days','14','2026-10-26 08:16:39','2026-10-26 08:16:39'),(8,'default_deposit_percent','30','2026-10-26 08:16:39','2026-10-26 08:16:39'),(9,'email_signature','Warm regards from Tanzania,\n\nUhalisia wa Tanzania Company Ltd (Safaris and Tours)\nArusha & Dar es Salaam, Tanzania\nPhone: +255 784 590 722 | Email: info@uhalisiawatanzania.com\nWebsite: www.uhalisiawatanzania.com','2026-10-26 08:16:39','2026-10-26 08:16:39'),(10,'quote_terms','1. A deposit of 30% is required to secure park permits and lodge bookings.\n2. The balance is due 30 days prior to safari commencement.\n3. Quoted prices include 4x4 safari vehicle with pop-up roof, experienced English-speaking driver/guide, national park entrance fees, accommodation as specified, and meals on safari.\n4. Excludes international flights, Tanzanian entry visa, personal travel insurance, and driver/guide gratuities.','2026-10-26 08:16:39','2026-10-26 08:16:39');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `status_history`
--

LOCK TABLES `status_history` WRITE;
/*!40000 ALTER TABLE `status_history` DISABLE KEYS */;
INSERT INTO `status_history` VALUES (1,'booking',1,'quote_sent','confirmed',2,'Deposit received, booking confirmed.','2026-10-26 08:16:41'),(2,'booking',1,'confirmed','confirmed',1,'Quote PDF re-sent to client email: sarah.jenkins@example.com','2026-10-27 05:02:52');
/*!40000 ALTER TABLE `status_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tour_days`
--

LOCK TABLES `tour_days` WRITE;
/*!40000 ALTER TABLE `tour_days` DISABLE KEYS */;
INSERT INTO `tour_days` VALUES (1,1,1,'Arrival at Kilimanjaro International Airport (JRO)','Day 1: Arrival at Kilimanjaro International Airport (JRO). Experience Airport pickup, safari briefing, rest and acclimatisation. Staying at Arusha Serena Hotel.','Arusha','Arusha Serena Hotel','Dinner','Airport pickup, safari briefing, rest and acclimatisation.','2026-10-26 08:16:40','2026-10-26 08:16:40'),(2,1,2,'Arusha to Tarangire National Park','Day 2: Arusha to Tarangire National Park. Experience Game drive among giant baobabs and large elephant herds. Staying at Tarangire Safari Lodge.','Tarangire','Tarangire Safari Lodge','Breakfast, Lunch, Dinner','Game drive among giant baobabs and large elephant herds.','2026-10-26 08:16:40','2026-10-26 08:16:40'),(3,1,3,'Full Day in Tarangire National Park','Day 3: Full Day in Tarangire National Park. Experience River exploration, predators tracking, bird watching. Staying at Tarangire Safari Lodge.','Tarangire','Tarangire Safari Lodge','Breakfast, Lunch, Dinner','River exploration, predators tracking, bird watching.','2026-10-26 08:16:40','2026-10-26 08:16:40'),(4,1,4,'Tarangire to Lake Manyara National Park','Day 4: Tarangire to Lake Manyara National Park. Experience Tree-climbing lions, flamingo flocks on alkaline soda lake, groundwater forest. Staying at Manyara Secret Lodge.','Lake Manyara','Manyara Secret Lodge','Breakfast, Lunch, Dinner','Tree-climbing lions, flamingo flocks on alkaline soda lake, groundwater forest.','2026-10-26 08:16:40','2026-10-26 08:16:40'),(5,1,5,'Lake Manyara to Lake Eyasi (Cultural Tour)','Day 5: Lake Manyara to Lake Eyasi (Cultural Tour). Experience Hadzabe hunter-gatherers archery, Datoga blacksmiths. Staying at Kisima Ngeda Tented Camp.','Lake Eyasi','Kisima Ngeda Tented Camp','Breakfast, Lunch, Dinner','Hadzabe hunter-gatherers archery, Datoga blacksmiths.','2026-10-26 08:16:40','2026-10-26 08:16:40'),(6,1,6,'Lake Eyasi to Central Serengeti (Seronera)','Day 6: Lake Eyasi to Central Serengeti (Seronera). Experience Scenic drive through Ngorongoro Highlands, afternoon game drive in Seronera valley. Staying at Kubukubu Tented Lodge.','Central Serengeti','Kubukubu Tented Lodge','Breakfast, Lunch, Dinner','Scenic drive through Ngorongoro Highlands, afternoon game drive in Seronera valley.','2026-10-26 08:16:40','2026-10-26 08:16:40'),(7,1,7,'Central Serengeti Full Day Exploration','Day 7: Central Serengeti Full Day Exploration. Experience Tracking big cats (lions, leopards, cheetahs) along the Seronera River. Staying at Kubukubu Tented Lodge.','Central Serengeti','Kubukubu Tented Lodge','Breakfast, Lunch, Dinner','Tracking big cats (lions, leopards, cheetahs) along the Seronera River.','2026-10-26 08:16:40','2026-10-26 08:16:40'),(8,1,8,'Central Serengeti to Northern Serengeti','Day 8: Central Serengeti to Northern Serengeti. Experience Game drive heading north along the migration corridor. Staying at Mara Under Canvas.','North Serengeti','Mara Under Canvas','Breakfast, Lunch, Dinner','Game drive heading north along the migration corridor.','2026-10-26 08:16:40','2026-10-26 08:16:40'),(9,1,9,'Mara River Crossing & Northern Serengeti','Day 9: Mara River Crossing & Northern Serengeti. Experience Witnessing wildebeest river crossings and Nile crocodile encounters. Staying at Mara Under Canvas.','North Serengeti','Mara Under Canvas','Breakfast, Lunch, Dinner','Witnessing wildebeest river crossings and Nile crocodile encounters.','2026-10-26 08:16:40','2026-10-26 08:16:40'),(10,1,10,'Northern Serengeti to Ngorongoro Crater Rim','Day 10: Northern Serengeti to Ngorongoro Crater Rim. Experience En-route game drive with panoramic crater rim sunset views. Staying at Ngorongoro Serena Safari Lodge.','Ngorongoro Rim','Ngorongoro Serena Safari Lodge','Breakfast, Lunch, Dinner','En-route game drive with panoramic crater rim sunset views.','2026-10-26 08:16:40','2026-10-26 08:16:40'),(11,1,11,'Ngorongoro Crater Floor Tour','Day 11: Ngorongoro Crater Floor Tour. Experience 6-hour crater descent, black rhino tracking, hippo pool lunch. Staying at Country Lodge Karatu.','Ngorongoro Crater','Country Lodge Karatu','Breakfast, Lunch, Dinner','6-hour crater descent, black rhino tracking, hippo pool lunch.','2026-10-26 08:16:40','2026-10-26 08:16:40'),(12,1,12,'Karatu to Arusha National Park','Day 12: Karatu to Arusha National Park. Experience Walking safari with an armed ranger, colobus monkeys, Momella Lakes. Staying at Mount Meru Game Lodge.','Arusha National Park','Mount Meru Game Lodge','Breakfast, Lunch, Dinner','Walking safari with an armed ranger, colobus monkeys, Momella Lakes.','2026-10-26 08:16:40','2026-10-26 08:16:40'),(13,1,13,'Cultural Shopping & Departure Transfer','Day 13: Cultural Shopping & Departure Transfer. Experience Curio market visit, Maasai craft center, transfer to airport. Staying at Day Room available.','Arusha / JRO','Day Room available','Breakfast, Lunch','Curio market visit, Maasai craft center, transfer to airport.','2026-10-26 08:16:40','2026-10-26 08:16:40'),(14,2,1,'Arrival in Arusha','Day 1: Arrival in Arusha. Experience Airport reception, transfer to hotel, safari orientation. Staying at The African Tulip Hotel.','Arusha','The African Tulip Hotel','Dinner','Airport reception, transfer to hotel, safari orientation.','2026-10-26 08:16:40','2026-10-26 08:16:40'),(15,2,2,'Arusha to Tarangire National Park','Day 2: Arusha to Tarangire National Park. Experience Game drive along Tarangire river, elephant sightings, birding. Staying at Maramboi Tented Camp.','Tarangire','Maramboi Tented Camp','Breakfast, Lunch, Dinner','Game drive along Tarangire river, elephant sightings, birding.','2026-10-26 08:16:40','2026-10-26 08:16:40'),(16,2,3,'Tarangire to Lake Manyara National Park','Day 3: Tarangire to Lake Manyara National Park. Experience Groundwater forest canopy walk, baboon troops, flamingo viewing. Staying at Escarpment Luxury Lodge.','Lake Manyara','Escarpment Luxury Lodge','Breakfast, Lunch, Dinner','Groundwater forest canopy walk, baboon troops, flamingo viewing.','2026-10-26 08:16:40','2026-10-26 08:16:40'),(17,2,4,'Lake Manyara to Serengeti (Seronera)','Day 4: Lake Manyara to Serengeti (Seronera). Experience Cross Ngorongoro conservation area, enter endless plains, kopjes game drive. Staying at Osinon Camp Serengeti.','Central Serengeti','Osinon Camp Serengeti','Breakfast, Lunch, Dinner','Cross Ngorongoro conservation area, enter endless plains, kopjes game drive.','2026-10-26 08:16:40','2026-10-26 08:16:40'),(18,2,5,'Full Day Serengeti Big Cats Tracking','Day 5: Full Day Serengeti Big Cats Tracking. Experience Sunrise game drive, Retima hippo pool, predator tracking. Staying at Osinon Camp Serengeti.','Central Serengeti','Osinon Camp Serengeti','Breakfast, Lunch, Dinner','Sunrise game drive, Retima hippo pool, predator tracking.','2026-10-26 08:16:40','2026-10-26 08:16:40'),(19,2,6,'Serengeti Wildlife & Migration Corridors','Day 6: Serengeti Wildlife & Migration Corridors. Experience Following the Great Migration herds across the central savannah. Staying at Osinon Camp Serengeti.','Serengeti','Osinon Camp Serengeti','Breakfast, Lunch, Dinner','Following the Great Migration herds across the central savannah.','2026-10-26 08:16:40','2026-10-26 08:16:40'),(20,2,7,'Serengeti to Ngorongoro Conservation Area','Day 7: Serengeti to Ngorongoro Conservation Area. Experience Visit Olduvai Gorge anthropological museum, crater rim view. Staying at Rhino Lodge.','Ngorongoro Highlands','Rhino Lodge','Breakfast, Lunch, Dinner','Visit Olduvai Gorge anthropological museum, crater rim view.','2026-10-26 08:16:40','2026-10-26 08:16:40'),(21,2,8,'Ngorongoro Crater Floor Safari','Day 8: Ngorongoro Crater Floor Safari. Experience Descent 600m to crater floor, Big Five tracking, lakeside picnic. Staying at Bougainvillea Safari Lodge.','Ngorongoro Crater','Bougainvillea Safari Lodge','Breakfast, Lunch, Dinner','Descent 600m to crater floor, Big Five tracking, lakeside picnic.','2026-10-26 08:16:40','2026-10-26 08:16:40'),(22,2,9,'Karatu Coffee Tour & Mto wa Mbu Cultural Walk','Day 9: Karatu Coffee Tour & Mto wa Mbu Cultural Walk. Experience Coffee farm walking tour, village banana plantation tour, local lunch. Staying at Ilboru Safari Lodge.','Karatu / Mto wa Mbu','Ilboru Safari Lodge','Breakfast, Lunch, Dinner','Coffee farm walking tour, village banana plantation tour, local lunch.','2026-10-26 08:16:40','2026-10-26 08:16:40'),(23,2,10,'Arusha City Tour & Departure','Day 10: Arusha City Tour & Departure. Experience Tanzanite experience museum, souvenir craft market, airport drop-off. Staying at Departure.','Arusha / JRO','Departure','Breakfast, Lunch','Tanzanite experience museum, souvenir craft market, airport drop-off.','2026-10-26 08:16:40','2026-10-26 08:16:40'),(24,3,1,'Welcome to Tanzania (Arusha)','Day 1: Welcome to Tanzania (Arusha). Experience Meet and greet at airport, brief and overnight in Arusha. Staying at Kibo Palace Hotel.','Arusha','Kibo Palace Hotel','Dinner','Meet and greet at airport, brief and overnight in Arusha.','2026-10-26 08:16:40','2026-10-26 08:16:40'),(25,3,2,'Arusha to Tarangire National Park','Day 2: Arusha to Tarangire National Park. Experience Game drive with picnic lunch, baobab trees and tree climbing pythons. Staying at Sangaiwe Tented Lodge.','Tarangire','Sangaiwe Tented Lodge','Breakfast, Lunch, Dinner','Game drive with picnic lunch, baobab trees and tree climbing pythons.','2026-10-26 08:16:40','2026-10-26 08:16:40'),(26,3,3,'Tarangire to Lake Manyara','Day 3: Tarangire to Lake Manyara. Experience Afternoon game drive in Lake Manyara National Park. Staying at Farm House Valley Karatu.','Lake Manyara','Farm House Valley Karatu','Breakfast, Lunch, Dinner','Afternoon game drive in Lake Manyara National Park.','2026-10-26 08:16:40','2026-10-26 08:16:40'),(27,3,4,'Karatu to Central Serengeti','Day 4: Karatu to Central Serengeti. Experience Transit via Ngorongoro highlands into the great Serengeti plains. Staying at Kati Kati Tented Camp.','Serengeti','Kati Kati Tented Camp','Breakfast, Lunch, Dinner','Transit via Ngorongoro highlands into the great Serengeti plains.','2026-10-26 08:16:40','2026-10-26 08:16:40'),(28,3,5,'Serengeti Full Day Game Viewing','Day 5: Serengeti Full Day Game Viewing. Experience Full day tracking lions, cheetahs, leopards, zebras and wildebeest. Staying at Kati Kati Tented Camp.','Serengeti','Kati Kati Tented Camp','Breakfast, Lunch, Dinner','Full day tracking lions, cheetahs, leopards, zebras and wildebeest.','2026-10-26 08:16:40','2026-10-26 08:16:40'),(29,3,6,'Serengeti to Western or Northern Sector','Day 6: Serengeti to Western or Northern Sector. Experience Following migratory movements, sunset sundowner on the savannah. Staying at Kati Kati Tented Camp.','Serengeti','Kati Kati Tented Camp','Breakfast, Lunch, Dinner','Following migratory movements, sunset sundowner on the savannah.','2026-10-26 08:16:40','2026-10-26 08:16:40'),(30,3,7,'Serengeti to Ngorongoro Conservation Area','Day 7: Serengeti to Ngorongoro Conservation Area. Experience Morning game drive, afternoon drive to Karatu highlands. Staying at Ngorongoro Farm House.','Ngorongoro','Ngorongoro Farm House','Breakfast, Lunch, Dinner','Morning game drive, afternoon drive to Karatu highlands.','2026-10-26 08:16:40','2026-10-26 08:16:40'),(31,3,8,'Full Day Ngorongoro Crater Tour','Day 8: Full Day Ngorongoro Crater Tour. Experience Crater descent, high wildlife density observation, return to lodge. Staying at Ngorongoro Farm House.','Ngorongoro Crater','Ngorongoro Farm House','Breakfast, Lunch, Dinner','Crater descent, high wildlife density observation, return to lodge.','2026-10-26 08:16:40','2026-10-26 08:16:40'),(32,3,9,'Return to Arusha & Flight Connection','Day 9: Return to Arusha & Flight Connection. Experience Scenic drive back to Arusha, day room and airport transfer. Staying at Departure.','Arusha','Departure','Breakfast, Lunch','Scenic drive back to Arusha, day room and airport transfer.','2026-10-26 08:16:40','2026-10-26 08:16:40'),(33,4,1,'Umbwe Gate (1,600m) to Umbwe Cave Camp (2,850m)','Day 1: Umbwe Gate (1,600m) to Umbwe Cave Camp (2,850m). Experience Rainforest trekking along steep ridge between Lonzo and Umbwe rivers. Staying at Umbwe Cave Camp (Mountain Tents).','Mount Kilimanjaro','Umbwe Cave Camp (Mountain Tents)','Lunch, Dinner','Rainforest trekking along steep ridge between Lonzo and Umbwe rivers.','2026-10-26 08:16:40','2026-10-26 08:16:40'),(34,4,2,'Umbwe Cave Camp to Barranco Camp (3,900m)','Day 2: Umbwe Cave Camp to Barranco Camp (3,900m). Experience Ascend through heath and moorland, giant senecios, view of Kibo peak. Staying at Barranco Camp (Mountain Tents).','Mount Kilimanjaro','Barranco Camp (Mountain Tents)','Breakfast, Lunch, Dinner','Ascend through heath and moorland, giant senecios, view of Kibo peak.','2026-10-26 08:16:40','2026-10-26 08:16:40'),(35,4,3,'Barranco Camp to Karanga Camp (3,995m)','Day 3: Barranco Camp to Karanga Camp (3,995m). Experience Scramble up the famous Barranco Breakfast Wall, traverse alpine valleys. Staying at Karanga Camp (Mountain Tents).','Mount Kilimanjaro','Karanga Camp (Mountain Tents)','Breakfast, Lunch, Dinner','Scramble up the famous Barranco Breakfast Wall, traverse alpine valleys.','2026-10-26 08:16:40','2026-10-26 08:16:40'),(36,4,4,'Karanga Camp to Barafu Camp (4,673m)','Day 4: Karanga Camp to Barafu Camp (4,673m). Experience Ascend to base camp on rocky alpine ridge, early dinner and rest for summit. Staying at Barafu Camp (Mountain Tents).','Mount Kilimanjaro','Barafu Camp (Mountain Tents)','Breakfast, Lunch, Dinner','Ascend to base camp on rocky alpine ridge, early dinner and rest for summit.','2026-10-26 08:16:40','2026-10-26 08:16:40'),(37,4,5,'Summit Night (Uhuru Peak 5,895m) & Descent to Mweka Camp (3,100m)','Day 5: Summit Night (Uhuru Peak 5,895m) & Descent to Mweka Camp (3,100m). Experience Midnight summit push, sunrise at Stella Point, Uhuru summit celebration, long descent. Staying at Mweka Camp (Mountain Tents).','Uhuru Peak','Mweka Camp (Mountain Tents)','Breakfast, Lunch, Dinner','Midnight summit push, sunrise at Stella Point, Uhuru summit celebration, long descent.','2026-10-26 08:16:40','2026-10-26 08:16:40'),(38,4,6,'Mweka Camp to Mweka Gate (1,640m) & Arusha Transfer','Day 6: Mweka Camp to Mweka Gate (1,640m) & Arusha Transfer. Experience Descent through rainforest, receive summit certificates, celebration with mountain crew. Staying at Hotel in Arusha.','Moshi / Arusha','Hotel in Arusha','Breakfast, Lunch','Descent through rainforest, receive summit certificates, celebration with mountain crew.','2026-10-26 08:16:40','2026-10-26 08:16:40'),(39,5,1,'Marangu Gate (1,870m) to Mandara Hut (2,720m)','Day 1: Marangu Gate (1,870m) to Mandara Hut (2,720m). Experience Registration at Marangu Gate, steady walk through lush montane rainforest. Staying at Mandara Huts.','Mount Kilimanjaro','Mandara Huts','Lunch, Dinner','Registration at Marangu Gate, steady walk through lush montane rainforest.','2026-10-26 08:16:40','2026-10-26 08:16:40'),(40,5,2,'Mandara Hut to Horombo Hut (3,720m)','Day 2: Mandara Hut to Horombo Hut (3,720m). Experience Trek past Maundi Crater into open moorland, expansive views of Mawenzi peak. Staying at Horombo Huts.','Mount Kilimanjaro','Horombo Huts','Breakfast, Lunch, Dinner','Trek past Maundi Crater into open moorland, expansive views of Mawenzi peak.','2026-10-26 08:16:40','2026-10-26 08:16:40'),(41,5,3,'Horombo Hut to Kibo Hut (4,700m)','Day 3: Horombo Hut to Kibo Hut (4,700m). Experience Cross the alpine desert Saddle between Mawenzi and Kibo, summit prep. Staying at Kibo Huts.','Mount Kilimanjaro','Kibo Huts','Breakfast, Lunch, Dinner','Cross the alpine desert Saddle between Mawenzi and Kibo, summit prep.','2026-10-26 08:16:40','2026-10-26 08:16:40'),(42,5,4,'Summit Attempt (Uhuru Peak 5,895m) & Descend to Horombo','Day 4: Summit Attempt (Uhuru Peak 5,895m) & Descend to Horombo. Experience Midnight start via Gilman Point to Uhuru Peak, celebrate summit, descend to Horombo. Staying at Horombo Huts.','Uhuru Peak','Horombo Huts','Breakfast, Lunch, Dinner','Midnight start via Gilman Point to Uhuru Peak, celebrate summit, descend to Horombo.','2026-10-26 08:16:40','2026-10-26 08:16:40'),(43,5,5,'Horombo Hut to Marangu Gate & Return to Arusha','Day 5: Horombo Hut to Marangu Gate & Return to Arusha. Experience Final descent to park gate, certificate ceremony, return drive to hotel. Staying at Hotel in Arusha.','Marangu / Arusha','Hotel in Arusha','Breakfast, Lunch','Final descent to park gate, certificate ceremony, return drive to hotel.','2026-10-26 08:16:40','2026-10-26 08:16:40'),(44,6,1,'Arrival in Arusha','Day 1: Arrival in Arusha. Experience Arrive in Tanzania, transfer to luxury lodge, evening safari briefing. Staying at Gran Melia Arusha.','Arusha','Gran Melia Arusha','Dinner','Arrive in Tanzania, transfer to luxury lodge, evening safari briefing.','2026-10-26 08:16:40','2026-10-26 08:16:40'),(45,6,2,'Arusha to Tarangire National Park','Day 2: Arusha to Tarangire National Park. Experience Game drive with packed lunch, elephant breeding herds and big cats. Staying at Tarangire Balloon Camp.','Tarangire','Tarangire Balloon Camp','Breakfast, Lunch, Dinner','Game drive with packed lunch, elephant breeding herds and big cats.','2026-10-26 08:16:40','2026-10-26 08:16:40'),(46,6,3,'Tarangire to Ngorongoro Conservation Area','Day 3: Tarangire to Ngorongoro Conservation Area. Experience Morning game drive, scenic drive to the crater rim. Staying at Ngorongoro Lion\'s Paw Camp.','Ngorongoro Rim','Ngorongoro Lion\'s Paw Camp','Breakfast, Lunch, Dinner','Morning game drive, scenic drive to the crater rim.','2026-10-26 08:16:40','2026-10-26 08:16:40'),(47,6,4,'Ngorongoro Crater Floor Safari','Day 4: Ngorongoro Crater Floor Safari. Experience Full crater descent, black rhino tracking, hippo pool picnic. Staying at Acacia Farm Lodge.','Ngorongoro Crater','Acacia Farm Lodge','Breakfast, Lunch, Dinner','Full crater descent, black rhino tracking, hippo pool picnic.','2026-10-26 08:16:40','2026-10-26 08:16:40'),(48,6,5,'Ngorongoro to Serengeti National Park','Day 5: Ngorongoro to Serengeti National Park. Experience Game drive across the endless savannah, tracking cheetahs and lions. Staying at Serengeti Serena Safari Lodge.','Serengeti','Serengeti Serena Safari Lodge','Breakfast, Lunch, Dinner','Game drive across the endless savannah, tracking cheetahs and lions.','2026-10-26 08:16:40','2026-10-26 08:16:40'),(49,6,6,'Full Day Serengeti Safari Adventure','Day 6: Full Day Serengeti Safari Adventure. Experience Sunrise hot air balloon option, wildlife tracking along Seronera valley. Staying at Serengeti Serena Safari Lodge.','Serengeti','Serengeti Serena Safari Lodge','Breakfast, Lunch, Dinner','Sunrise hot air balloon option, wildlife tracking along Seronera valley.','2026-10-26 08:16:40','2026-10-26 08:16:40'),(50,6,7,'Fly Serengeti to Zanzibar Island','Day 7: Fly Serengeti to Zanzibar Island. Experience Airstrip game drive, flight to Zanzibar, sunset dhow cruise in Stone Town. Staying at Park Hyatt Zanzibar.','Stone Town, Zanzibar','Park Hyatt Zanzibar','Breakfast, Dinner','Airstrip game drive, flight to Zanzibar, sunset dhow cruise in Stone Town.','2026-10-26 08:16:40','2026-10-26 08:16:40'),(51,6,8,'Stone Town Walking Tour & Spice Farm to Nungwi Beach','Day 8: Stone Town Walking Tour & Spice Farm to Nungwi Beach. Experience Historic Stone Town UNESCO tour, fragrant spice plantation, transfer to beach. Staying at Zuri Zanzibar Resort.','Zanzibar Beach','Zuri Zanzibar Resort','Breakfast, Lunch, Dinner','Historic Stone Town UNESCO tour, fragrant spice plantation, transfer to beach.','2026-10-26 08:16:40','2026-10-26 08:16:40'),(52,6,9,'Zanzibar Beach Relaxation & Coral Reef Snorkelling','Day 9: Zanzibar Beach Relaxation & Coral Reef Snorkelling. Experience Snorkelling trip to Mnemba Atoll, swimming in crystal blue waters. Staying at Zuri Zanzibar Resort.','Nungwi / Kendwa','Zuri Zanzibar Resort','Breakfast, Lunch, Dinner','Snorkelling trip to Mnemba Atoll, swimming in crystal blue waters.','2026-10-26 08:16:40','2026-10-26 08:16:40'),(53,6,10,'Leisure Day on Zanzibar Coast','Day 10: Leisure Day on Zanzibar Coast. Experience Water sports, coastal walk, fresh seafood dinner on the white sand. Staying at Zuri Zanzibar Resort.','Zanzibar Coast','Zuri Zanzibar Resort','Breakfast, Lunch, Dinner','Water sports, coastal walk, fresh seafood dinner on the white sand.','2026-10-26 08:16:40','2026-10-26 08:16:40'),(54,6,11,'Departure from Zanzibar Airport (ZNZ)','Day 11: Departure from Zanzibar Airport (ZNZ). Experience Final beach morning, transfer to Zanzibar International Airport for flight home. Staying at Departure.','Zanzibar Airport','Departure','Breakfast','Final beach morning, transfer to Zanzibar International Airport for flight home.','2026-10-26 08:16:40','2026-10-26 08:16:40');
/*!40000 ALTER TABLE `tour_days` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tours`
--

LOCK TABLES `tours` WRITE;
/*!40000 ALTER TABLE `tours` DISABLE KEYS */;
INSERT INTO `tours` VALUES (1,'13-days-safaris','13 Days Grand Tanzania Wildlife & Cultural Safari','combo',13,'Comprehensive journey across Tarangire, Lake Manyara, Serengeti, Ngorongoro Crater, Lake Eyasi Hadzabe tribe, and Arusha National Park.',4550.00,1,'2026-10-26 08:16:40','2026-10-26 08:16:40'),(2,'10-days-safaris','10 Days Classic Northern Circuit Wildlife Safari','safari',10,'The ultimate safari experience combining Tarangire elephants, Lake Manyara, deep Serengeti exploration, and the Ngorongoro Caldera.',3450.00,1,'2026-10-26 08:16:40','2026-10-26 08:16:40'),(3,'9-days-safaris','9 Days Migration Highlights & Crater Safari','safari',9,'Focused wildlife safari highlighting the Serengeti ecosystem, Ngorongoro Crater, and Tarangire National Park with premium tented camps.',3150.00,1,'2026-10-26 08:16:40','2026-10-26 08:16:40'),(4,'6-days-umbwe-route','6 Days Kilimanjaro Climb via Umbwe Route','climb',6,'The most direct and dramatic climbing route on Mount Kilimanjaro, suited for fit trekkers seeking steep ridges and spectacular mountain vistas.',2200.00,1,'2026-10-26 08:16:40','2026-10-26 08:16:40'),(5,'5-days-marangu-route','5 Days Mount Kilimanjaro Climb (Marangu Route)','climb',5,'Known as the Coca-Cola Route, Marangu provides comfortable A-frame wooden hut accommodations throughout the ascent to Uhuru Peak.',1950.00,1,'2026-10-26 08:16:40','2026-10-26 08:16:40'),(6,'bush-to-beach','11 Days Bush to Beach (Serengeti Safari & Zanzibar Paradise)','combo',11,'The quintessential Tanzanian adventure: Tarangire, Ngorongoro Crater, and Serengeti wildlife safari followed by pristine turquoise waters and spice tours in Zanzibar.',3890.00,1,'2026-10-26 08:16:40','2026-10-26 08:16:40');
/*!40000 ALTER TABLE `tours` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `travelers`
--

LOCK TABLES `travelers` WRITE;
/*!40000 ALTER TABLE `travelers` DISABLE KEYS */;
INSERT INTO `travelers` VALUES (1,1,'Sarah Jenkins','British','eyJpdiI6ImFRNjQvTGZCUHVhSXZaUW9iSmpnbHc9PSIsInZhbHVlIjoiZTRYdWhLNE5GN2hTbTFDRytac29mdz09IiwibWFjIjoiY2ExZTllMzc4NDhkZDY4NDg0NjM5NzgzM2I1YzZkNWE4NmNhMjIzNTBjNmEwYzIyOWU3NTkxZDc2ZDE5NTM0NyIsInRhZyI6IiJ9','1992-06-14','None','Mother: +44 7700 900999','2026-10-26 08:16:40','2026-10-26 08:16:40'),(2,1,'Thomas Jenkins','British','eyJpdiI6IktCSnpSNVNVNy9vRDllNHhFd2lUMlE9PSIsInZhbHVlIjoidjlUUGZ2RWhVUktiV1BXakl6NjVoZz09IiwibWFjIjoiZDRkNzUxZmY1ZjQxMDc3OTkwMzE3ZWM0ZTBlMGE5N2I1YTc2MjY2MzZjZGJkNzUyYTc4YzExOGUyNjVkOTljNSIsInRhZyI6IiJ9','1990-11-23','Gluten-free preference','Father: +44 7700 900888','2026-10-26 08:16:40','2026-10-26 08:16:40');
/*!40000 ALTER TABLE `travelers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Portal Administrator','admin@uhalisiawatanzania.com',NULL,'$2y$12$puuVZGF.HjnGE2IEJ71BQ.WO5MYMS0L.nPcBPKf3L6bBtGSkDlhbu','admin',1,'l9uO2QfHdU1um7dfmuwMXDvkAXkRE7ZAGl2dUTGecDN6PwUOsutzVFjP6zwQ','2026-10-26 08:16:40','2026-10-26 08:16:40'),(2,'Safari Tour Agent','agent@uhalisiawatanzania.com',NULL,'$2y$12$Hgl8lwJcQdX7oCqz5Uh7KOuNKT7ttogex5k5GITtGaCrbIX5WQKj6','agent',1,NULL,'2026-10-26 08:16:40','2026-10-26 08:16:40'),(3,'Chief Accountant','accountant@uhalisiawatanzania.com',NULL,'$2y$12$HB1fTdD7Dk7/LN2UuudHo.n2prd1bQ3/Tqc1OszKeAwFMwTKDNPHS','accountant',1,NULL,'2026-10-26 08:16:40','2026-10-26 08:16:40');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `vehicles`
--

LOCK TABLES `vehicles` WRITE;
/*!40000 ALTER TABLE `vehicles` DISABLE KEYS */;
INSERT INTO `vehicles` VALUES (1,'Serengeti Cruiser 01','T 142 DAA','Toyota Land Cruiser 4x4 (Extended)',7,1,'Equipped with pop-up roof, inverter, fridge, and HF radio.','2026-10-26 08:16:40','2026-10-26 08:16:40'),(2,'Ngorongoro Cruiser 02','T 285 EBB','Toyota Land Cruiser 4x4 (Extended)',7,1,'Dual spare tires, high ground clearance, USB charging ports at every seat.','2026-10-26 08:16:40','2026-10-26 08:16:40'),(3,'Kilimanjaro Cruiser 03','T 519 DCC','Toyota Land Cruiser 4x4 (Standard)',5,1,'Perfect for private couples or small family groups.','2026-10-26 08:16:40','2026-10-26 08:16:40'),(4,'Tarangire Safari Van 04','T 390 EDD','Toyota HiAce 4x4',8,1,'Used for airport transfers and cultural day tours around Arusha.','2026-10-26 08:16:40','2026-10-26 08:16:40');
/*!40000 ALTER TABLE `vehicles` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-10-27 12:03:19
