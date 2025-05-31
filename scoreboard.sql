-- MySQL dump 10.13  Distrib 8.0.42, for Linux (x86_64)
--
-- Host: localhost    Database: scoreboard
-- ------------------------------------------------------
-- Server version	8.0.42-0ubuntu0.24.04.1

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
-- Table structure for table `deck`
--

DROP TABLE IF EXISTS `deck`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `deck` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `scoreboard_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `scoreboard_id` (`scoreboard_id`),
  CONSTRAINT `deck_ibfk_1` FOREIGN KEY (`scoreboard_id`) REFERENCES `scoreboard` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `deck`
--

LOCK TABLES `deck` WRITE;
/*!40000 ALTER TABLE `deck` DISABLE KEYS */;
INSERT INTO `deck` VALUES (11,'family matters',1),(12,'forces of the imperium',1),(13,'quick draw',1),(14,'slive swarm',1),(15,'peace offering',1),(16,'squirreled away',1),(17,'explorers of the deep',1),(18,'sliver swarm',1),(19,'creative energy',1),(20,'veloci-ramp-tor',1),(26,'meteor cat',1),(27,'Familly Matters',1),(28,'peen',1),(29,'akul',1),(30,'1-up',1),(31,'jump scare',1),(32,'marchesa',1),(33,'breena',1);
/*!40000 ALTER TABLE `deck` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `game`
--

DROP TABLE IF EXISTS `game`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `game` (
  `id` int NOT NULL AUTO_INCREMENT,
  `winner_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `scoreboard_id` int NOT NULL,
  `note` text,
  PRIMARY KEY (`id`),
  KEY `winner_id` (`winner_id`),
  KEY `scoreboard_id` (`scoreboard_id`),
  CONSTRAINT `game_ibfk_1` FOREIGN KEY (`winner_id`) REFERENCES `player` (`id`),
  CONSTRAINT `game_ibfk_2` FOREIGN KEY (`scoreboard_id`) REFERENCES `scoreboard` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `game`
--

LOCK TABLES `game` WRITE;
/*!40000 ALTER TABLE `game` DISABLE KEYS */;
INSERT INTO `game` VALUES (1,10,'2024-08-02 16:04:18',1,NULL),(2,13,'2024-08-02 16:05:07',1,NULL),(3,13,'2024-08-02 16:06:20',1,NULL),(4,10,'2024-08-02 16:07:13',1,NULL),(5,10,'2024-08-02 16:08:29',1,NULL),(6,8,'2024-08-03 20:07:36',1,NULL),(7,11,'2024-08-06 20:26:37',1,'first game of the day of magic.'),(8,11,'2024-08-06 22:47:19',1,'at fire and dice after upgrading. daniel did a $150 upgrade.'),(9,8,'2024-08-07 00:27:00',1,'at josh\'s house after getting back from fire and dice'),(10,11,'2024-08-07 01:52:06',1,'Null game because Jason should have made Daniel’s devil enter tapped, but it didn’t and Daniel swung on Dawson for 4000 with trample. at josh\'s house while josh was napping'),(11,13,'2024-08-07 04:58:06',1,'at josh\'s house before taco bell'),(12,12,'2024-08-07 09:55:46',1,'4 hour game at josh\'s house from 10 to 2 am. near the end daniel took a roughly 30 minute turn, only to kill jason and remove the enchantment he needed to make sure satya couldn\'t attack. dawson killed daniel and ardoon and thought he won, only for josh to draw 3 and pull second son to win the game with 6 life remaining while dawson had 46.'),(13,8,'2024-08-08 00:03:51',1,'quick game at the store on sawtelle, dawson top decked guide of souls, then got to instantly revive it. daniel almost had lethal on the whole board out of nowhere but not enough.'),(14,8,'2024-08-08 04:42:50',1,'played at dawson\'s backyard table. dawson was the immediate threat for most of the game, daniel killed jason and then dawson won with tap all opponent creatures and gain lifelink.'),(15,11,'2024-08-13 23:47:04',1,'drilled ojer taq, almost one tapped jason \"kielbasa\" khon, misplayed on not enough energy, died AGAIN to the devilish uber driver and the token maker. jason killed all non dinos with wakening suns avatar and won. nvm NULL GAME! it only works if you play it from your hand and he didnt.'),(16,13,'2024-08-13 23:47:33',1,'we learned \"when creature attacks\" doesnt trigger when a creatures enters attacking. so every game prior is null. daniel was copying one million satya\'s, and thought he went infinite.'),(17,8,'2024-08-13 23:48:03',1,'morning after sleepover at josh\'s. dawson killed josh early, and eventually killed jason after a board wipe.'),(18,11,'2024-08-13 23:48:46',1,'josh killed me early with the big dragon that gains +1/+1s every spell played. Then jason went against him for a while, i thought about shuffling techniques. josh was one turn away from drawing second sun, but jason exiled it from the top of his library.'),(19,8,'2024-08-14 06:43:36',1,'first game with chatterfang, had academy manufacturer, pippin and scavengers talent for crazy shit. finished game with beatmaster ascension and second harvest on turn 5.'),(20,13,'2024-08-14 07:46:06',1,'Fair game until Daniel searches deck for the devilish valet and the rest of the combo. Triple offspringed it for triple 64/128 to end game 0 to 100. Third time with the same combo by the way!'),(21,11,'2024-08-20 00:52:33',1,NULL),(22,13,'2024-08-20 00:52:49',1,NULL),(23,11,'2024-08-20 00:53:09',1,NULL),(24,11,'2024-08-25 18:03:27',1,NULL),(25,13,'2024-08-25 18:03:51',1,NULL),(26,8,'2024-12-16 01:33:12',1,'chatterfang plus myriad squirrels for death'),(27,11,'2024-12-16 01:35:09',1,'too many big dinos. heath played paper planeswalker and got targeted; complained.'),(28,13,'2024-12-16 01:36:03',1,'daniel stole all commanders abilities and more'),(29,8,'2024-12-24 04:34:02',1,'heath left halfway through, myriad squirrel new rules were discoevered'),(30,13,'2024-12-24 04:43:52',1,'1 million dollar board state tunr 3 from daniel'),(31,8,'2024-12-27 19:00:49',1,'Jason and Heath conceded when Daniel arrived'),(32,13,'2024-12-27 22:57:47',1,'Long ass game! Danuel wins with wild fire awakened and the valet…'),(33,8,'2024-12-31 17:54:28',1,'Jason had the whole Jurassic park and Heath Comeuppanced his ass. I reset the board state and Heath surrendered.'),(34,13,'2025-01-02 00:36:09',1,NULL);
/*!40000 ALTER TABLE `game` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `game_entry`
--

DROP TABLE IF EXISTS `game_entry`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `game_entry` (
  `game_id` int NOT NULL,
  `player_id` int NOT NULL,
  `deck_id` int NOT NULL,
  KEY `game_id` (`game_id`),
  KEY `player_id` (`player_id`),
  KEY `deck_id` (`deck_id`),
  CONSTRAINT `game_entry_ibfk_1` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`),
  CONSTRAINT `game_entry_ibfk_2` FOREIGN KEY (`player_id`) REFERENCES `player` (`id`),
  CONSTRAINT `game_entry_ibfk_3` FOREIGN KEY (`deck_id`) REFERENCES `deck` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `game_entry`
--

LOCK TABLES `game_entry` WRITE;
/*!40000 ALTER TABLE `game_entry` DISABLE KEYS */;
INSERT INTO `game_entry` VALUES (1,8,11),(1,9,12),(1,10,13),(3,8,15),(3,12,12),(3,13,17),(3,11,18),(4,8,16),(4,10,17),(4,12,15),(4,14,11),(5,10,16),(5,8,15),(5,13,11),(6,8,19),(6,11,20),(6,13,17),(6,12,15),(13,8,19),(13,11,20),(13,13,11),(13,10,16),(14,8,19),(14,10,18),(14,11,20),(14,13,11),(11,8,19),(11,11,20),(11,13,11),(11,10,15),(9,8,19),(9,11,20),(9,12,15),(9,13,11),(8,11,20),(8,13,11),(8,12,15),(8,8,16),(7,11,20),(7,13,17),(7,8,19),(7,12,12),(12,8,19),(12,11,20),(12,13,11),(12,12,15),(12,10,18),(10,11,20),(10,13,11),(10,8,19),(15,11,20),(15,13,11),(15,8,19),(15,12,15),(16,13,11),(16,8,19),(16,11,20),(16,12,15),(17,8,19),(17,11,18),(17,12,15),(18,11,20),(18,8,19),(18,12,15),(2,11,18),(2,12,15),(2,13,16),(19,8,16),(19,13,11),(19,10,19),(19,11,20),(20,8,16),(20,13,11),(20,10,19),(20,11,20),(21,11,20),(21,12,15),(21,13,11),(22,13,11),(22,12,15),(22,11,20),(23,11,13),(23,13,17),(23,12,15),(24,11,20),(24,12,15),(24,13,17),(25,11,20),(25,13,11),(25,12,15),(26,8,16),(26,11,20),(26,12,18),(26,14,26),(28,11,13),(28,8,16),(28,13,11),(27,8,19),(27,11,20),(27,14,26),(27,12,18),(27,13,11),(29,8,19),(29,13,11),(29,12,16),(29,14,29),(29,11,30),(30,8,19),(30,11,30),(30,13,11),(31,8,31),(31,11,16),(31,14,29),(32,8,31),(32,11,20),(32,14,29),(32,13,11),(33,8,32),(33,14,33),(33,11,20),(33,13,30),(34,8,32),(34,11,30),(34,14,33),(34,13,11);
/*!40000 ALTER TABLE `game_entry` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `player`
--

DROP TABLE IF EXISTS `player`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `player` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `scoreboard_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `scoreboard_id` (`scoreboard_id`),
  CONSTRAINT `player_ibfk_1` FOREIGN KEY (`scoreboard_id`) REFERENCES `scoreboard` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `player`
--

LOCK TABLES `player` WRITE;
/*!40000 ALTER TABLE `player` DISABLE KEYS */;
INSERT INTO `player` VALUES (8,'dawson',1),(9,'jack',1),(10,'ardoon',1),(11,'jason',1),(12,'josh',1),(13,'daniel',1),(14,'heath',1);
/*!40000 ALTER TABLE `player` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `scoreboard`
--

DROP TABLE IF EXISTS `scoreboard`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `scoreboard` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `owner_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `owner_id` (`owner_id`),
  CONSTRAINT `scoreboard_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `scoreboard`
--

LOCK TABLES `scoreboard` WRITE;
/*!40000 ALTER TABLE `scoreboard` DISABLE KEYS */;
INSERT INTO `scoreboard` VALUES (1,'Dawson\'s Scoreboard',1),(2,'Test board',1),(3,'Online',9);
/*!40000 ALTER TABLE `scoreboard` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `scoreboard_user`
--

DROP TABLE IF EXISTS `scoreboard_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `scoreboard_user` (
  `scoreboard_id` int NOT NULL,
  `user_id` int NOT NULL,
  PRIMARY KEY (`scoreboard_id`,`user_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `scoreboard_user_ibfk_1` FOREIGN KEY (`scoreboard_id`) REFERENCES `scoreboard` (`id`),
  CONSTRAINT `scoreboard_user_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `scoreboard_user`
--

LOCK TABLES `scoreboard_user` WRITE;
/*!40000 ALTER TABLE `scoreboard_user` DISABLE KEYS */;
INSERT INTO `scoreboard_user` VALUES (1,1),(2,1),(1,2),(1,3),(1,4),(1,5),(3,9);
/*!40000 ALTER TABLE `scoreboard_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `email` varchar(128) NOT NULL,
  `password_hash` varchar(256) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'Dawson','a@b.c','$2y$10$czjN0kYl4BlOSj0rOtqyr.BLcplSjSYs6eQl7ZoBXlrl8JRQHoU1S','2024-08-02 08:44:10'),(2,'dawson2','dawson.dwm@gmail.com','$2y$10$PzG1KzQW2CcLpchuTgVEjOV6bM9G2gdqfcOgb3NUE8SDv9irhGDci','2024-08-02 08:46:01'),(3,'Croncodile','realjk0187@gmail.com','$2y$10$oBgWFberKv2dPzub5FV0KOIkwT3yfsKH7QIGE6hCGHIm7CzsOWJdC','2024-08-05 19:19:59'),(4,'hmsdiscord','hmsnorcal@gmail.com','$2y$10$n8BapaQ8kecQo1MqXz.QD.BkQ5cxiZ5wurUHZmMyo8jV2yi2HWB.q','2024-08-07 02:01:03'),(5,'dawsani','dawsonwmatthews@gmail.com','$2y$10$wKQURiXKtj9yGSv3syyYB.09W1sifCNQISwfZUg8EWRxWK49wNc/G','2024-12-03 21:55:19'),(6,'StephenRhino','alllinks30.01@gmail.com ','$2y$10$0kBLqAsM/QtkHLSzbiWIHeoDpQUvtM/HQO6C/24nCr2fW0.ht.Gpi','2025-02-21 07:58:04'),(7,'PhillipTog','mjohnston911@att.net','$2y$10$IPvTvUNQpoCsTtcLW/jaieYlaw.oL1zRS38lyNfEaeRigSrHTMuYO','2025-03-20 17:31:12'),(8,'Opdwodowkdwiidwok dj','nomin.momin+307v1@mail.ru','$2y$10$B4TddqFlrzYWGrwv.dJ4a.vpx2NM0YhJ4LyNZAsvV7IJ7n1vCvCqm','2025-03-29 07:10:54'),(9,'dawsonm','dawson@discordultimate.com','$2y$10$sKjYq6.T5lb6Q8DFGHDBC.W7XovcY55edF2ypWGUa.i08SFk7WgeW','2025-04-06 20:06:16');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-05-31 14:21:20
