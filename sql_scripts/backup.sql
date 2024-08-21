-- MySQL dump 10.13  Distrib 8.0.39, for Linux (x86_64)
--
-- Host: localhost    Database: scoreboard
-- ------------------------------------------------------
-- Server version	8.0.39-0ubuntu0.22.04.1

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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `deck`
--

LOCK TABLES `deck` WRITE;
/*!40000 ALTER TABLE `deck` DISABLE KEYS */;
INSERT INTO `deck` VALUES (1,'family matters'),(2,'forces of the imperium'),(3,'quick draw'),(4,'slive swarm'),(5,'peace offering'),(6,'squirreled away'),(7,'explorers of the deep'),(8,'sliver swarm'),(9,'creative energy'),(10,'veloci-ramp-tor');
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
  PRIMARY KEY (`id`),
  KEY `winner_id` (`winner_id`),
  CONSTRAINT `game_ibfk_1` FOREIGN KEY (`winner_id`) REFERENCES `player` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `game`
--

LOCK TABLES `game` WRITE;
/*!40000 ALTER TABLE `game` DISABLE KEYS */;
INSERT INTO `game` VALUES (1,3,'2024-08-02 16:04:18'),(2,6,'2024-08-02 16:05:07'),(3,6,'2024-08-02 16:06:20'),(4,3,'2024-08-02 16:07:13'),(5,3,'2024-08-02 16:08:29'),(6,1,'2024-08-03 20:07:36');
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
INSERT INTO `game_entry` VALUES (1,1,1),(1,2,2),(1,3,3),(2,4,4),(2,5,5),(2,6,6),(3,1,5),(3,5,2),(3,6,7),(3,4,8),(4,1,6),(4,3,7),(4,5,5),(4,7,1),(5,3,6),(5,1,5),(5,6,1),(6,1,9),(6,4,10),(6,6,7),(6,5,5);
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `player`
--

LOCK TABLES `player` WRITE;
/*!40000 ALTER TABLE `player` DISABLE KEYS */;
INSERT INTO `player` VALUES (1,'dawson'),(2,'jack'),(3,'ardoon'),(4,'jason'),(5,'josh'),(6,'daniel'),(7,'heath');
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `scoreboard`
--

LOCK TABLES `scoreboard` WRITE;
/*!40000 ALTER TABLE `scoreboard` DISABLE KEYS */;
INSERT INTO `scoreboard` VALUES (1,'Score late 1',1);
/*!40000 ALTER TABLE `scoreboard` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `scoreboard_game`
--

DROP TABLE IF EXISTS `scoreboard_game`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `scoreboard_game` (
  `scoreboard_id` int NOT NULL,
  `game_id` int NOT NULL,
  KEY `scoreboard_id` (`scoreboard_id`),
  KEY `game_id` (`game_id`),
  CONSTRAINT `scoreboard_game_ibfk_1` FOREIGN KEY (`scoreboard_id`) REFERENCES `scoreboard` (`id`),
  CONSTRAINT `scoreboard_game_ibfk_2` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `scoreboard_game`
--

LOCK TABLES `scoreboard_game` WRITE;
/*!40000 ALTER TABLE `scoreboard_game` DISABLE KEYS */;
INSERT INTO `scoreboard_game` VALUES (1,1),(1,2),(1,3),(1,4),(1,5),(1,6);
/*!40000 ALTER TABLE `scoreboard_game` ENABLE KEYS */;
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
INSERT INTO `scoreboard_user` VALUES (1,1),(1,2);
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'Dawson','a@b.c','$2y$10$czjN0kYl4BlOSj0rOtqyr.BLcplSjSYs6eQl7ZoBXlrl8JRQHoU1S','2024-08-02 08:44:10'),(2,'dawson2','dawson.dwm@gmail.com','$2y$10$PzG1KzQW2CcLpchuTgVEjOV6bM9G2gdqfcOgb3NUE8SDv9irhGDci','2024-08-02 08:46:01');
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

-- Dump completed on 2024-08-03 19:05:01
