<?php
header('Content-Type: text/plain; charset=utf-8');

require_once('db_login.php');

$date = gmdate("Y-m-d H:i:s");
$dateDayAgo = gmdate("Y-m-d H:i:s", time()-24*60*60);

if(isset($_GET['cmd'])) {
	switch($_GET['cmd']) {
		case 'reset':
			toBackUp();
			break;
			
		case 'join':
			joinPlayers();
			break;
			
		case 'rollback':
			rollBack();
			break;

		default:
			echo "Invalid cmd";
			break;
	}
} else {
	echo "Nothing to do..";
}

function joinPlayers() {
	global $date;
	global $dateDayAgo;
	
	$sql = <<<SQL_END
UPDATE `players` SET  `state` =  'WAITING', `waitingAlias` =  'TestU2', `tokens` =  'computerscience;terra' WHERE  `players`.`playerId` =2;
UPDATE `players` SET  `state` =  'WAITING', `waitingAlias` =  'TestD', `tokens` =  'computerscience' WHERE  `players`.`playerId` =9;
UPDATE `players` SET  `state` =  'WAITING', `waitingAlias` =  'VO', `tokens` =  'computerscience' WHERE  `players`.`playerId` =10;
UPDATE `players` SET  `state` =  'WAITING', `waitingAlias` =  'AND', `tokens` =  'computerscience' WHERE  `players`.`playerId` =11;
UPDATE `players` SET  `state` =  'WAITING', `waitingAlias` =  'JT', `tokens` =  'computerscience' WHERE  `players`.`playerId` =12;
SQL_END;

	sendquery($sql);
	echo "Players joined.\n";
}

function rollBack() {
	global $date;
	global $dateDayAgo;
	
	$sql = "UPDATE games SET startDate=DATE_SUB(startDate, INTERVAL 24 HOUR) WHERE state = 'PENDING';";
	mysql_query($sql) or die(mysql_error());
	$games = mysql_affected_rows();
	
	echo "$games game rolled back.\n";
	
	sendquery($sql);
	echo "Players joined.\n";
}

function toBackUp() {
	global $date;
	global $dateDayAgo;
	
	echo "Reseting...\n";
	$sql = <<<BACKUP
-- MySQL dump 10.13  Distrib 5.1.43, for unknown-linux-gnu (x86_64)
--
-- Host: localhost    Database: oguievet_assdev
-- ------------------------------------------------------
-- Server version	5.1.43-log

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
-- Table structure for table `assassinations`
--

DROP TABLE IF EXISTS `assassinations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `assassinations` (
  `assassinationId` int(11) NOT NULL AUTO_INCREMENT,
  `gameId` int(11) NOT NULL,
  `assassinId` int(11) NOT NULL,
  `victimId` int(11) NOT NULL,
  `state` enum('PENDING','SUCCESS','SELF_DEFENSE','FAIL','KICKED','WANTED') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'PENDING',
  `startDate` datetime NOT NULL,
  `endDate` datetime NOT NULL,
  `details` varchar(2000) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `detailsState` enum('NONE','ADDED','TIMEOUT') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'NONE',
  PRIMARY KEY (`assassinationId`),
  UNIQUE KEY `gameId` (`gameId`,`assassinId`,`victimId`)
) ENGINE=MyISAM AUTO_INCREMENT=93 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `assassinations`
--

LOCK TABLES `assassinations` WRITE;
/*!40000 ALTER TABLE `assassinations` DISABLE KEYS */;
INSERT INTO `assassinations` (`assassinationId`, `gameId`, `assassinId`, `victimId`, `state`, `startDate`, `endDate`, `details`, `detailsState`) VALUES (20,2,1,12,'SUCCESS','2010-01-29 07:23:27','2010-01-29 19:48:16','Hell yeah, he dead!','ADDED'),(19,2,1,10,'SUCCESS','2010-01-29 07:17:01','2010-01-29 07:23:27','Boom headshot','ADDED'),(18,2,12,1,'FAIL','2010-01-29 07:17:01','2010-01-29 19:48:16','','NONE'),(17,2,10,12,'FAIL','2010-01-29 07:17:01','2010-01-29 07:23:27','','NONE'),(21,3,11,1,'FAIL','2010-01-30 07:31:56','2010-01-30 07:54:36','','NONE'),(22,3,1,12,'SUCCESS','2010-01-30 07:31:56','2010-01-30 07:38:38','','TIMEOUT'),(23,3,12,10,'FAIL','2010-01-30 07:31:56','2010-01-30 07:38:38','','NONE'),(24,3,10,11,'FAIL','2010-01-30 07:31:56','2010-01-30 07:48:27','','NONE'),(25,3,1,10,'SUCCESS','2010-01-30 07:38:38','2010-01-30 07:48:27','','TIMEOUT'),(26,3,1,11,'SUCCESS','2010-01-30 07:48:27','2010-01-30 07:54:36','','TIMEOUT'),(27,4,1,12,'SUCCESS','2010-01-30 08:04:52','2010-01-30 08:06:14','It seems that vadim is not shuffling anything, is he?','ADDED'),(28,4,12,10,'FAIL','2010-01-30 08:04:52','2010-01-30 08:06:14','','NONE'),(29,4,10,11,'FAIL','2010-01-30 08:04:52','2010-01-30 08:08:47','','NONE'),(30,4,11,1,'FAIL','2010-01-30 08:04:52','2010-01-30 08:12:03','','NONE'),(31,4,1,10,'SUCCESS','2010-01-30 08:06:14','2010-01-30 08:08:47','Good, it seems like I\'m winning every game!','ADDED'),(32,4,1,11,'SUCCESS','2010-01-30 08:08:47','2010-01-30 08:12:03','There you go, andreas is DEAD','ADDED'),(42,5,11,1,'SUCCESS','2010-02-02 08:20:36','2010-02-02 08:22:02','','TIMEOUT'),(39,5,10,1,'FAIL','2010-02-02 08:20:00','2010-02-02 08:20:36','','NONE'),(40,5,1,11,'FAIL','2010-02-02 08:20:00','2010-02-02 08:22:02','','NONE'),(41,5,11,10,'SUCCESS','2010-02-02 08:20:00','2010-02-02 08:20:36','He didn\'t know what hit him','ADDED'),(43,6,1,12,'FAIL','2010-02-02 17:55:14','2010-02-02 18:13:22','','NONE'),(44,6,12,2,'SUCCESS','2010-02-02 17:55:14','2010-02-02 18:38:11','','TIMEOUT'),(45,6,2,10,'SUCCESS','2010-02-02 17:55:14','2010-02-02 18:16:05','That was fun','ADDED'),(46,6,10,1,'SUCCESS','2010-02-02 17:55:14','2010-02-02 18:13:22','Yes! \'tis my first kill eyy!','ADDED'),(47,6,10,12,'FAIL','2010-02-02 18:13:22','2010-02-02 18:16:05','','NONE'),(48,6,2,12,'FAIL','2010-02-02 18:16:05','2010-02-02 18:38:11','','NONE'),(49,7,12,1,'FAIL','2010-02-02 18:53:20','2010-02-02 18:54:20','','NONE'),(50,7,1,10,'SUCCESS','2010-02-02 18:53:20','2010-02-02 18:55:38','','TIMEOUT'),(51,7,10,12,'SUCCESS','2010-02-02 18:53:20','2010-02-02 18:54:20','that was nice','ADDED'),(52,7,10,1,'FAIL','2010-02-02 18:54:20','2010-02-02 18:55:38','','NONE'),(53,8,1,12,'SUCCESS','2010-02-02 18:59:03','2010-02-02 19:01:40','And I JUST WON!!!!','ADDED'),(54,8,12,10,'SUCCESS','2010-02-02 18:59:03','2010-02-02 19:00:50','Oops, my mistake','ADDED'),(55,8,10,1,'FAIL','2010-02-02 18:59:03','2010-02-02 19:00:50','','NONE'),(56,8,12,1,'FAIL','2010-02-02 19:00:50','2010-02-02 19:01:40','','NONE'),(57,9,16,17,'SUCCESS','2010-02-03 04:00:08','2010-02-04 20:39:15','I had some good news and some bad news...','ADDED'),(58,9,17,19,'FAIL','2010-02-03 04:00:08','2010-02-04 20:39:15','','NONE'),(59,9,19,21,'SUCCESS','2010-02-03 04:00:08','2010-02-04 01:09:27','The spoon is mightier than the nose.','ADDED'),(60,9,21,12,'FAIL','2010-02-03 04:00:08','2010-02-04 01:09:27','','NONE'),(61,9,12,10,'SUCCESS','2010-02-03 04:00:08','2010-02-03 04:06:25','What are the odds of finding him so easily?','ADDED'),(62,9,10,11,'SUCCESS','2010-02-03 04:00:08','2010-02-03 04:04:40','He banged his head on the table trying to avoid my spoon of doom!','ADDED'),(63,9,11,13,'FAIL','2010-02-03 04:00:08','2010-02-03 04:04:40','','NONE'),(64,9,13,23,'SUCCESS','2010-02-03 04:00:08','2010-02-05 01:00:18','Killed with Sep\'s sock!','ADDED'),(65,9,23,15,'FAIL','2010-02-03 04:00:08','2010-02-05 01:00:18','','NONE'),(66,9,15,25,'SUCCESS','2010-02-03 04:00:08','2010-02-05 01:01:31','','TIMEOUT'),(67,9,25,20,'SUCCESS','2010-02-03 04:00:08','2010-02-04 18:39:35','socked with sock by sock','ADDED'),(68,9,20,14,'FAIL','2010-02-03 04:00:08','2010-02-04 18:39:35','','NONE'),(69,9,14,24,'KICKED','2010-02-03 04:00:08','2010-02-07 04:00:08','','NONE'),(70,9,24,22,'KICKED','2010-02-03 04:00:08','2010-02-07 04:00:08','','NONE'),(71,9,22,26,'KICKED','2010-02-03 04:00:08','2010-02-07 04:00:08','','NONE'),(72,9,26,18,'SUCCESS','2010-02-03 04:00:08','2010-02-04 23:16:05','Beware these Socks of justice','ADDED'),(73,9,18,16,'FAIL','2010-02-03 04:00:08','2010-02-04 23:16:05','','NONE'),(74,9,10,13,'FAIL','2010-02-03 04:04:40','2010-02-03 04:06:25','','NONE'),(75,9,12,13,'KICKED','2010-02-03 04:06:25','2010-02-07 04:06:25','','NONE'),(76,9,19,12,'FAIL','2010-02-04 01:09:27','2010-02-04 23:01:33','','NONE'),(77,9,25,14,'FAIL','2010-02-04 18:39:35','2010-02-05 01:01:31','','NONE'),(78,9,16,19,'SUCCESS','2010-02-04 20:39:15','2010-02-04 23:01:33','Early to bed, early to rise, makes a man healthy, wealthy, and wise. Early to class gets a man killed in assassins.','ADDED'),(79,9,16,12,'FAIL','2010-02-04 23:01:33','2010-02-05 01:03:33','','NONE'),(80,9,26,16,'SUCCESS','2010-02-04 23:16:05','2010-02-05 01:03:33','Sitting in class, working so earnestly...sock of justice falls from heaven...','ADDED'),(81,9,13,15,'SUCCESS','2010-02-05 01:00:18','2010-02-05 01:02:11','With Vadim\'s spoon!','ADDED'),(82,9,15,14,'FAIL','2010-02-05 01:01:31','2010-02-05 01:02:11','','NONE'),(83,9,13,14,'KICKED','2010-02-05 01:02:11','2010-02-09 01:02:11','','NONE'),(84,9,26,12,'FAIL','2010-02-05 01:03:33','2010-02-09 01:03:33','','NONE'),(85,10,9,1,'KICKED','2010-02-08 04:54:45','2010-02-12 04:54:45','','NONE'),(86,10,1,2,'KICKED','2010-02-08 04:54:45','2010-02-12 04:54:45','','NONE'),(87,10,2,9,'KICKED','2010-02-08 04:54:45','2010-02-12 04:54:45','','NONE');
/*!40000 ALTER TABLE `assassinations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `disputes`
--

DROP TABLE IF EXISTS `disputes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `disputes` (
  `disputeId` int(11) NOT NULL AUTO_INCREMENT,
  `gameId` int(11) NOT NULL,
  `accuserId` int(11) NOT NULL,
  `defendantId` int(11) NOT NULL,
  `won` enum('ACC','DEF') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'DEF',
  `status` enum('CREATED','REBUTED','RESOLVED_ACC','RESOLVED_DEF') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'CREATED',
  `accusation` varchar(10000) COLLATE utf8_unicode_ci NOT NULL,
  `defense` varchar(10000) COLLATE utf8_unicode_ci NOT NULL,
  `createdTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `rebutedTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `resolvedTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`disputeId`),
  UNIQUE KEY `gameId` (`gameId`,`accuserId`,`defendantId`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `disputes`
--

LOCK TABLES `disputes` WRITE;
/*!40000 ALTER TABLE `disputes` DISABLE KEYS */;
/*!40000 ALTER TABLE `disputes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `errors`
--

DROP TABLE IF EXISTS `errors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `errors` (
  `errorId` int(11) NOT NULL AUTO_INCREMENT,
  `type` enum('SQL','USER') COLLATE utf8_unicode_ci NOT NULL,
  `error` varchar(10000) COLLATE utf8_unicode_ci NOT NULL,
  `extra` varchar(10000) COLLATE utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`errorId`)
) ENGINE=MyISAM AUTO_INCREMENT=377 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `errors`
--

LOCK TABLES `errors` WRITE;
/*!40000 ALTER TABLE `errors` DISABLE KEYS */;
/*!40000 ALTER TABLE `errors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gameNameAdj`
--

DROP TABLE IF EXISTS `gameNameAdj`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gameNameAdj` (
  `word` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  UNIQUE KEY `word` (`word`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gameNameAdj`
--

LOCK TABLES `gameNameAdj` WRITE;
/*!40000 ALTER TABLE `gameNameAdj` DISABLE KEYS */;
INSERT INTO `gameNameAdj` (`word`) VALUES ('Aqua'),('Aquamarine'),('Azure'),('Beige'),('Bisque'),('Black'),('Blue'),('Brown'),('Chartreuse'),('Chocolate'),('Coral'),('Cornsilk'),('Crimson'),('Cyan'),('Darkorange'),('Fuchsia'),('Gainsboro'),('Gold'),('Gray'),('Green'),('Indigo'),('Ivory'),('Khaki'),('Lavender'),('Lime'),('Linen'),('Magenta'),('Maroon'),('Moccasin'),('Navy'),('Olive'),('Orange'),('Orchid'),('Peru'),('Pink'),('Plum'),('Purple'),('Red'),('Salmon'),('Sienna'),('Silver'),('Snow'),('Tan'),('Teal'),('Thistle'),('Tomato'),('Turquoise'),('Violet'),('Wheat'),('White'),('Yellow');
/*!40000 ALTER TABLE `gameNameAdj` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gameNameNoun`
--

DROP TABLE IF EXISTS `gameNameNoun`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gameNameNoun` (
  `word` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  UNIQUE KEY `word` (`word`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gameNameNoun`
--

LOCK TABLES `gameNameNoun` WRITE;
/*!40000 ALTER TABLE `gameNameNoun` DISABLE KEYS */;
INSERT INTO `gameNameNoun` (`word`) VALUES ('Alligator'),('Ant'),('Antelope'),('Ape'),('Baboon'),('Badger'),('Bat'),('Bear'),('Beaver'),('Bee'),('Bison'),('Butterfly'),('Camel'),('Cat'),('Chamois'),('Cheetah'),('Chicken'),('Cobra'),('Cockroach'),('Cormorant'),('Coyote'),('Crab'),('Crane'),('Crocodile'),('Crow'),('Deer'),('Dog'),('Dogfish'),('Dolphin'),('Donkey'),('Dove'),('Duck'),('Eagle'),('Echidna'),('Eel'),('Eland'),('Elephant'),('Falcon'),('Ferret'),('Finch'),('Fly'),('Fox'),('Frog'),('Gazelle'),('Gerbil'),('Giraffe'),('Gnu'),('Goat'),('Goose'),('Gorilla'),('Guanaco'),('Gull'),('Hamster'),('Hare'),('Hawk'),('Heron'),('Hippopotamus'),('Hornet'),('Human'),('Hyena'),('Jackal'),('Jaguar'),('Jellyfish'),('Kangaroo'),('Kudu'),('Lark'),('Leopard'),('Lion'),('Llama'),('Lobster'),('Louse'),('Lyrebird'),('Magpie'),('Mallard'),('Manatee'),('Mink'),('Mole'),('Monkey'),('Moose'),('Mosquito'),('Mouse'),('Mule'),('Nightingale'),('Oryx'),('Ostrich'),('Otter'),('Owl'),('Ox'),('Oyster'),('Panther'),('Partridge'),('Peafowl'),('Pelican'),('Pigeon'),('Pony'),('Porcupine'),('Rabbit'),('Raccoon'),('Rail'),('Ram'),('Rat'),('Raven'),('Rhinoceros'),('Seal'),('Seastar'),('Shark'),('Sheep'),('Skunk'),('Snail'),('Snake'),('Spider'),('Squirrel'),('Swan'),('Tiger'),('Toad'),('Turkey'),('Turtle'),('Weasel'),('Whale'),('Wolf'),('Worm'),('Yak'),('Zebra');
/*!40000 ALTER TABLE `gameNameNoun` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `games`
--

DROP TABLE IF EXISTS `games`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `games` (
  `gameId` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `state` enum('PENDING','ACTIVE','FINISHED') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'PENDING',
  `startDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `endDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `winnerId` int(11) NOT NULL DEFAULT '0',
  `token` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `timeoutHours` int(11) NOT NULL DEFAULT '144',
  `wantedHours` int(11) NOT NULL DEFAULT '72',
  PRIMARY KEY (`gameId`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `games`
--

LOCK TABLES `games` WRITE;
/*!40000 ALTER TABLE `games` DISABLE KEYS */;
INSERT INTO `games` (`gameId`, `name`, `state`, `startDate`, `endDate`, `winnerId`, `token`, `timeoutHours`, `wantedHours`) VALUES (2,'Operation Git Hub','FINISHED','2010-01-29 07:17:01','2010-01-29 19:48:16',1,'',144,72),(3,'Operarion Kill Bill','FINISHED','2010-01-30 07:31:56','2010-01-30 07:54:36',1,'',144,72),(4,'Operation Too Good','FINISHED','2010-01-30 08:04:52','2010-01-30 08:12:03',1,'',144,72),(5,'Operation Late Night','FINISHED','2010-02-02 08:20:00','2010-02-02 08:22:02',11,'',144,72),(6,'Operation Please Work','FINISHED','2010-02-02 17:55:14','2010-02-02 18:38:11',12,'',144,72),(7,'Operation Go On','FINISHED','2010-02-02 18:53:20','2010-02-02 18:55:38',1,'',144,72),(8,'Operation This Works','FINISHED','2010-02-02 18:59:03','2010-02-02 19:01:40',1,'',144,72),(9,'Operation Good Grades','FINISHED','2010-02-03 04:00:08','2010-02-13 04:00:08',0,'',144,72),(10,'Operation Test Me','FINISHED','2010-02-08 04:54:45','2010-02-20 23:09:27',0,'',144,72);
/*!40000 ALTER TABLE `games` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `likes`
--

DROP TABLE IF EXISTS `likes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `likes` (
  `likeId` int(11) NOT NULL AUTO_INCREMENT,
  `assassinationId` int(11) NOT NULL,
  `playerId` int(11) NOT NULL,
  PRIMARY KEY (`likeId`),
  UNIQUE KEY `assassinationIn` (`assassinationId`,`playerId`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `likes`
--

LOCK TABLES `likes` WRITE;
/*!40000 ALTER TABLE `likes` DISABLE KEYS */;
INSERT INTO `likes` (`likeId`, `assassinationId`, `playerId`) VALUES (1,53,1),(2,54,1),(3,42,1);
/*!40000 ALTER TABLE `likes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `participations`
--

DROP TABLE IF EXISTS `participations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `participations` (
  `participationId` int(11) NOT NULL AUTO_INCREMENT,
  `gameId` int(11) NOT NULL,
  `playerId` int(11) NOT NULL,
  `state` enum('ACTIVE','ASSASSINATED','SUICIDE','WON','KICKED') COLLATE utf8_unicode_ci NOT NULL,
  `alias` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `codeword` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `disputes` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`participationId`),
  UNIQUE KEY `gameId` (`gameId`,`playerId`)
) ENGINE=MyISAM AUTO_INCREMENT=69 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `participations`
--

LOCK TABLES `participations` WRITE;
/*!40000 ALTER TABLE `participations` DISABLE KEYS */;
INSERT INTO `participations` (`participationId`, `gameId`, `playerId`, `state`, `alias`, `codeword`, `disputes`) VALUES (18,2,12,'ASSASSINATED','Toenail','blow',0),(17,2,10,'ASSASSINATED','PowerRanger','bird',0),(16,2,1,'WON','AntiAliasing','band',0),(19,3,1,'WON','BeerCap','car',0),(20,3,10,'ASSASSINATED','PowerPenis','bread',0),(21,3,11,'ASSASSINATED','GayLord','body',0),(22,3,12,'ASSASSINATED','Dragon','blow',0),(23,4,1,'WON','Dave Mathews','body',0),(24,4,10,'ASSASSINATED','MegaMan','book',0),(25,4,11,'ASSASSINATED','Paparas','answer',0),(26,4,12,'ASSASSINATED','Aladin','beauty',0),(33,5,11,'WON','Teminator2','bird',0),(32,5,10,'ASSASSINATED','poopi','call',0),(31,5,1,'ASSASSINATED','AlwaysReady','board',0),(34,6,1,'ASSASSINATED','FairlyGoodQuestion','baby',0),(35,6,2,'ASSASSINATED','Go\'olie','box',0),(36,6,10,'ASSASSINATED','O\'Toole','animal',0),(37,6,12,'WON','StartUp','bottom',0),(38,7,1,'WON','Kantleen','answer',0),(39,7,10,'ASSASSINATED','Tesla','bread',0),(40,7,12,'ASSASSINATED','ComeOn!','bit',0),(41,8,1,'WON','CanYoujoin','box',0),(42,8,10,'ASSASSINATED','Poo','baby',0),(43,8,12,'ASSASSINATED','ASYourself','animal',0),(44,9,10,'ASSASSINATED','HoboKiller','age',0),(45,9,11,'ASSASSINATED','Bill Gates','base',0),(46,9,12,'KICKED','Inspector Gadget','ball',0),(47,9,13,'KICKED','TerraIncognito','baby',0),(48,9,14,'KICKED','aPinkElephant','blow',0),(49,9,15,'ASSASSINATED','theassassinator','art',0),(50,9,16,'ASSASSINATED','Segmentation_Fault','car',0),(51,9,17,'ASSASSINATED','paarfi','bell',0),(52,9,18,'ASSASSINATED','cakeeater','board',0),(53,9,19,'ASSASSINATED','walrus','area',0),(54,9,20,'ASSASSINATED','Voldemort','bottom',0),(55,9,21,'ASSASSINATED','TheBigNoseinator','capital',0),(56,9,22,'KICKED','Ghost','anger',0),(57,9,23,'ASSASSINATED','gimme_a_sponge_bath','arm',0),(58,9,24,'KICKED','Vadim?','box',0),(59,9,25,'ASSASSINATED','sock','bit',0),(60,9,26,'ASSASSINATED','johnwilkesthebooth','break',0),(61,10,1,'KICKED','OneOfThese','ball',0),(62,10,2,'KICKED','test2','brother',0),(63,10,9,'KICKED','demo','bird',0);
/*!40000 ALTER TABLE `participations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `players`
--

DROP TABLE IF EXISTS `players`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `players` (
  `playerId` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `rule` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `dateCreated` datetime NOT NULL,
  `lastLogin` datetime NOT NULL,
  `state` enum('NOTHING','WAITING','PLAYING') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'NOTHING',
  `waitingAlias` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `waitingStart` datetime NOT NULL,
  `tokens` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`playerId`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `players`
--

LOCK TABLES `players` WRITE;
/*!40000 ALTER TABLE `players` DISABLE KEYS */;
INSERT INTO `players` (`playerId`, `email`, `name`, `rule`, `dateCreated`, `lastLogin`, `state`, `waitingAlias`, `waitingStart`, `tokens`) VALUES (1,'test1@test.edu','Test1 Uset1','','0000-00-00 00:00:00','0000-00-00 00:00:00','NOTHING','','0000-00-00 00:00:00',''),(2,'test2@test.edu','Test2 User2','','0000-00-00 00:00:00','0000-00-00 00:00:00','NOTHING','','0000-00-00 00:00:00',''),(9,'demo@test.edu','Demo','','0000-00-00 00:00:00','0000-00-00 00:00:00','NOTHING','','0000-00-00 00:00:00',''),(10,'vad@stanford.edu','Vadim Ogievetsky','stanford:student','0000-00-00 00:00:00','2010-02-05 20:53:07','NOTHING','','0000-00-00 00:00:00',''),(11,'anomikos@stanford.edu','Andreas Nomikos','stanford:student','0000-00-00 00:00:00','2010-02-06 06:18:33','NOTHING','','0000-00-00 00:00:00',''),(12,'jtamayo@stanford.edu','Juan Manuel Tamayo Gil','stanford:student','2010-01-28 06:38:26','2010-02-06 06:14:53','NOTHING','','0000-00-00 00:00:00',''),(13,'jheer@cs.test.edu','Jeffrey Heer','stanford:administrative','2010-01-29 00:43:13','2010-02-05 00:56:52','NOTHING','','0000-00-00 00:00:00',''),(14,'trcarden@test.edu','Tim Cardenas','stanford:student','2010-01-29 00:48:02','2010-02-03 02:30:19','NOTHING','','0000-00-00 00:00:00',''),(15,'sdkamvar@test.edu','Sepandar David Kamvar','stanford:administrative','2010-01-29 00:48:21','2010-02-05 01:01:22','NOTHING','','0000-00-00 00:00:00',''),(17,'malee@test.edu','Marcia Anne Lee','stanford:student','2010-02-02 23:20:34','2010-02-04 20:38:39','NOTHING','','0000-00-00 00:00:00',''),(18,'saahmad@test.edu','Salman Azeem Ahmad','stanford:student','2010-02-02 23:20:43','2010-02-02 23:21:09','NOTHING','','0000-00-00 00:00:00',''),(19,'kcm@test.edu','Keith Clifton McDaniel','stanford:student','2010-02-02 23:20:44','2010-02-04 01:09:22','NOTHING','','0000-00-00 00:00:00',''),(20,'knigh@test.edu','Kelly Lauren Nigh','stanford:student','2010-02-02 23:21:15','2010-02-05 01:01:51','NOTHING','','0000-00-00 00:00:00',''),(21,'neema@test.edu','Neema Mortazavi Moraveji','stanford:student','2010-02-02 23:21:40','2010-02-03 20:02:12','NOTHING','','0000-00-00 00:00:00',''),(22,'namwkim@test.edu','Nam Wook Kim','stanford:student','2010-02-02 23:22:02','2010-02-04 05:57:13','NOTHING','','0000-00-00 00:00:00',''),(23,'iantien@test.edu','Ian Tien','stanford:student','2010-02-02 23:24:32','2010-02-05 00:58:14','NOTHING','','0000-00-00 00:00:00',''),(24,'fxchen@test.edu','Frank Xiaoxiao Chen','stanford:student','2010-02-02 23:24:49','2010-02-03 02:43:40','NOTHING','','0000-00-00 00:00:00','');
/*!40000 ALTER TABLE `players` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tokens`
--

DROP TABLE IF EXISTS `tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tokens` (
  `id` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` enum('DORM','DEPARTMENT') NOT NULL DEFAULT 'DORM',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tokens`
--

LOCK TABLES `tokens` WRITE;
/*!40000 ALTER TABLE `tokens` DISABLE KEYS */;
INSERT INTO `tokens` (`id`, `name`, `type`) VALUES ('evbuildings','EV buildings 1-144','DORM'),('abrams','Abrams','DORM'),('barnes','Barnes','DORM'),('blackwelder','Blackwelder','DORM'),('hoskins','Hoskins','DORM'),('hulme','Hulme','DORM'),('mcfarland','McFarland','DORM'),('quillen','Quillen','DORM'),('evstudio1','EV Studio 1','DORM'),('evstudio2','EV Studio 2','DORM'),('evstudio3','EV Studio 3','DORM'),('evstudio4','EV Studio 4','DORM'),('evstudio5','EV Studio 5','DORM'),('evstudio6','EV Studio 6','DORM'),('rains','Rains','DORM'),('lyman','Lyman','DORM'),('munger','Munger','DORM'),('schwab','Schwab','DORM'),('brannerhall','Branner Hall','DORM'),('crothershall','Crothers Hall','DORM'),('florencemoorehall','Florence Moore Hall','DORM'),('governorscorner','Governor\'s Corner','DORM'),('lagunitacourt','Lagunita Court','DORM'),('manzanitapark','Manzanita Park','DORM'),('roblehall','Roble Hall','DORM'),('sternhall','Stern Hall','DORM'),('toyonhall','Toyon Hall','DORM'),('wilburhall','Wilbur Hall','DORM'),('mirrieleeshouse','Mirrielees House','DORM'),('oakcreekapartments','Oak Creek Apartments','DORM'),('chithetachi','Chi Theta Chi','DORM'),('columbae','Columbae','DORM'),('ebf','Enchanted Broccoli Forest','DORM'),('hammarskjold','Hammarskjold','DORM'),('kairos','Kairos','DORM'),('synergy','Synergy','DORM'),('terra','Terra','DORM'),('kappaalpha','Kappa Alpha','DORM'),('kappasigma','Kappa Sigma','DORM'),('phikappapsi','Phi Kappa Psi','DORM'),('sigmaalphaepsilon','Sigma Alpha Epsilon','DORM'),('sigmanu','Sigma Nu','DORM'),('sigmachi','Sigma Chi','DORM'),('thetadeltachi','Theta Delta Chi','DORM'),('deltadeltadelta','Delta Delta Delta','DORM'),('kappaalphatheta','Kappa Alpha Theta','DORM'),('pibetaphi','Pi Beta Phi','DORM'),('aeronautics','Aeronautics & Astronautics','DEPARTMENT'),('anesthesia','Anesthesia','DEPARTMENT'),('anthropology','Anthropology','DEPARTMENT'),('appliedphysics','Applied Physics','DEPARTMENT'),('art','Art & Art History','DEPARTMENT'),('asianlanguages','Asian Languages','DEPARTMENT'),('biochemistry','Biochemistry','DEPARTMENT'),('bioengineering','Bioengineering','DEPARTMENT'),('biology','Biology','DEPARTMENT'),('business','Business, Graduate School of','DEPARTMENT'),('cardiothoracicsurger','Cardiothoracic Surgery','DEPARTMENT'),('chemicalandsystemsbi','Chemical and Systems Biology','DEPARTMENT'),('chemicalengineering','Chemical Engineering','DEPARTMENT'),('chemistry','Chemistry','DEPARTMENT'),('civil','Civil & Environmental Engineering','DEPARTMENT'),('classics','Classics','DEPARTMENT'),('communication','Communication','DEPARTMENT'),('comparativeliteratur','Comparative Literature','DEPARTMENT'),('comparativemedicine','Comparative Medicine','DEPARTMENT'),('computerscience','Computer Science','DEPARTMENT'),('dermatology','Dermatology','DEPARTMENT'),('developmentalbiology','Developmental Biology','DEPARTMENT'),('drama','Drama','DEPARTMENT'),('economics','Economics','DEPARTMENT'),('education','Education, School of','DEPARTMENT'),('electricalengineerin','Electrical Engineering','DEPARTMENT'),('energyresourcesengin','Energy Resources Engineering','DEPARTMENT'),('english','English','DEPARTMENT'),('environmentalearthsy','Environmental Earth System Science','DEPARTMENT'),('genetics','Genetics','DEPARTMENT'),('geological','Geological & Environmental Sciences','DEPARTMENT'),('geophysics','Geophysics','DEPARTMENT'),('germanstudies','German Studies','DEPARTMENT'),('healthresearch','Health Research & Policy','DEPARTMENT'),('history','History','DEPARTMENT'),('iberian','Iberian & Latin American Cultures','DEPARTMENT'),('lawschool','Law School','DEPARTMENT'),('linguistics','Linguistics','DEPARTMENT'),('managementscience','Management Science & Engineering','DEPARTMENT'),('materialsscience','Materials Science & Engineering','DEPARTMENT'),('mathematics','Mathematics','DEPARTMENT'),('mechanicalengineerin','Mechanical Engineering','DEPARTMENT'),('medicine','Medicine','DEPARTMENT'),('microbiology','Microbiology & Immunology','DEPARTMENT'),('molecular','Molecular & Cellular Physiology','DEPARTMENT'),('music','Music','DEPARTMENT'),('neurology','Neurology & Neurological Sciences','DEPARTMENT'),('neurosurgery','Neurosurgery','DEPARTMENT'),('obstetricsandgynecol','Obstetrics and Gynecology','DEPARTMENT'),('ophthalmology','Ophthalmology','DEPARTMENT'),('orthopaedicsurgery','Orthopaedic Surgery','DEPARTMENT'),('otolaryngology','Otolaryngology','DEPARTMENT'),('slac','SLAC','DEPARTMENT'),('pathology','Pathology','DEPARTMENT'),('pediatrics','Pediatrics','DEPARTMENT'),('philosophy','Philosophy','DEPARTMENT'),('physics','Physics','DEPARTMENT'),('politicalscience','Political Science','DEPARTMENT'),('psychiatryandbehavio','Psychiatry and Behavioral Sciences','DEPARTMENT'),('psychology','Psychology','DEPARTMENT'),('radiationoncology','Radiation Oncology','DEPARTMENT'),('radiology','Radiology','DEPARTMENT'),('religiousstudies','Religious Studies','DEPARTMENT'),('slaviclanguagesandli','Slavic Languages and Literature','DEPARTMENT'),('sociology','Sociology','DEPARTMENT'),('statistics','Statistics','DEPARTMENT'),('structuralbiology','Structural Biology','DEPARTMENT'),('surgery','Surgery','DEPARTMENT'),('urology','Urology','DEPARTMENT');
/*!40000 ALTER TABLE `tokens` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2010-03-03 20:38:01

UPDATE players SET tokens='computerscience;munger' WHERE playerId='1' LIMIT 1;
BACKUP;

	sendquery($sql);
	echo "Reset.\n";
}

function sendquery($query) {
	$array = explode(";\n", $query );
	foreach( $array as $value ) {
		if( !$result = mysql_query( $value )) {
			echo "SQL: $value\n";
			echo mysql_error();
			break;
		}
	}
	return $result;
}
?>