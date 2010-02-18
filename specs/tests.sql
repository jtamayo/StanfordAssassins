-- MySQL dump 10.13  Distrib 5.1.43, for unknown-linux-gnu (x86_64)
--
-- Host: localhost    Database: oguievet_assassins
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
  `state` enum('PENDING','SUCCESS','SELF_DEFENSE','FAIL','KICKED') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'PENDING',
  `startDate` datetime NOT NULL,
  `endDate` datetime NOT NULL,
  `details` varchar(2000) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#',
  PRIMARY KEY (`assassinationId`),
  UNIQUE KEY `gameId` (`gameId`,`assassinId`,`victimId`)
) ENGINE=MyISAM AUTO_INCREMENT=85 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `assassinations`
--

LOCK TABLES `assassinations` WRITE;
/*!40000 ALTER TABLE `assassinations` DISABLE KEYS */;
INSERT INTO `assassinations` (`assassinationId`, `gameId`, `assassinId`, `victimId`, `state`, `startDate`, `endDate`, `details`) VALUES (20,2,1,12,'SUCCESS','2010-01-29 07:23:27','2010-01-29 19:48:16','Hell yeah, he dead!'),(19,2,1,10,'SUCCESS','2010-01-29 07:17:01','2010-01-29 07:23:27','Boom headshot'),(18,2,12,1,'FAIL','2010-01-29 07:17:01','2010-01-29 19:48:16','#'),(17,2,10,12,'FAIL','2010-01-29 07:17:01','2010-01-29 07:23:27','#'),(21,3,11,1,'FAIL','2010-01-30 07:31:56','2010-01-30 07:54:36','#'),(22,3,1,12,'SUCCESS','2010-01-30 07:31:56','2010-01-30 07:38:38','#'),(23,3,12,10,'FAIL','2010-01-30 07:31:56','2010-01-30 07:38:38','#'),(24,3,10,11,'FAIL','2010-01-30 07:31:56','2010-01-30 07:48:27','#'),(25,3,1,10,'SUCCESS','2010-01-30 07:38:38','2010-01-30 07:48:27','#'),(26,3,1,11,'SUCCESS','2010-01-30 07:48:27','2010-01-30 07:54:36','#'),(27,4,1,12,'SUCCESS','2010-01-30 08:04:52','2010-01-30 08:06:14','It seems that vadim is not shuffling anything, is he?'),(28,4,12,10,'FAIL','2010-01-30 08:04:52','2010-01-30 08:06:14','#'),(29,4,10,11,'FAIL','2010-01-30 08:04:52','2010-01-30 08:08:47','#'),(30,4,11,1,'FAIL','2010-01-30 08:04:52','2010-01-30 08:12:03','#'),(31,4,1,10,'SUCCESS','2010-01-30 08:06:14','2010-01-30 08:08:47','Good, it seems like I\'m winning every game!'),(32,4,1,11,'SUCCESS','2010-01-30 08:08:47','2010-01-30 08:12:03','There you go, andreas is DEAD'),(42,5,11,1,'SUCCESS','2010-02-02 08:20:36','2010-02-02 08:22:02','#'),(39,5,10,1,'FAIL','2010-02-02 08:20:00','2010-02-02 08:20:36','#'),(40,5,1,11,'FAIL','2010-02-02 08:20:00','2010-02-02 08:22:02','#'),(41,5,11,10,'SUCCESS','2010-02-02 08:20:00','2010-02-02 08:20:36','He didn\'t know what hit him'),(43,6,1,12,'FAIL','2010-02-02 17:55:14','2010-02-02 18:13:22','#'),(44,6,12,2,'SUCCESS','2010-02-02 17:55:14','2010-02-02 18:38:11','#'),(45,6,2,10,'SUCCESS','2010-02-02 17:55:14','2010-02-02 18:16:05','That was fun'),(46,6,10,1,'SUCCESS','2010-02-02 17:55:14','2010-02-02 18:13:22','Yes! \'tis my first kill eyy!'),(47,6,10,12,'FAIL','2010-02-02 18:13:22','2010-02-02 18:16:05','#'),(48,6,2,12,'FAIL','2010-02-02 18:16:05','2010-02-02 18:38:11','#'),(49,7,12,1,'FAIL','2010-02-02 18:53:20','2010-02-02 18:54:20','#'),(50,7,1,10,'SUCCESS','2010-02-02 18:53:20','2010-02-02 18:55:38','#'),(51,7,10,12,'SUCCESS','2010-02-02 18:53:20','2010-02-02 18:54:20','that was nice'),(52,7,10,1,'FAIL','2010-02-02 18:54:20','2010-02-02 18:55:38','#'),(53,8,1,12,'SUCCESS','2010-02-02 18:59:03','2010-02-02 19:01:40','And I JUST WON!!!!'),(54,8,12,10,'SUCCESS','2010-02-02 18:59:03','2010-02-02 19:00:50','Oops, my mistake'),(55,8,10,1,'FAIL','2010-02-02 18:59:03','2010-02-02 19:00:50','#'),(56,8,12,1,'FAIL','2010-02-02 19:00:50','2010-02-02 19:01:40','#'),(57,9,16,17,'SUCCESS','2010-02-03 04:00:08','2010-02-04 20:39:15','I had some good news and some bad news...'),(58,9,17,19,'FAIL','2010-02-03 04:00:08','2010-02-04 20:39:15','#'),(59,9,19,21,'SUCCESS','2010-02-03 04:00:08','2010-02-04 01:09:27','The spoon is mightier than the nose.'),(60,9,21,12,'FAIL','2010-02-03 04:00:08','2010-02-04 01:09:27','#'),(61,9,12,10,'SUCCESS','2010-02-03 04:00:08','2010-02-03 04:06:25','What are the odds of finding him so easily?'),(62,9,10,11,'SUCCESS','2010-02-03 04:00:08','2010-02-03 04:04:40','He banged his head on the table trying to avoid my spoon of doom!'),(63,9,11,13,'FAIL','2010-02-03 04:00:08','2010-02-03 04:04:40','#'),(64,9,13,23,'SUCCESS','2010-02-03 04:00:08','2010-02-05 01:00:18','Killed with Sep\'s sock!'),(65,9,23,15,'FAIL','2010-02-03 04:00:08','2010-02-05 01:00:18','#'),(66,9,15,25,'SUCCESS','2010-02-03 04:00:08','2010-02-05 01:01:31','#'),(67,9,25,20,'SUCCESS','2010-02-03 04:00:08','2010-02-04 18:39:35','socked with sock by sock'),(68,9,20,14,'FAIL','2010-02-03 04:00:08','2010-02-04 18:39:35','#'),(69,9,14,24,'PENDING','2010-02-03 04:00:08','2010-02-07 04:00:08','#'),(70,9,24,22,'PENDING','2010-02-03 04:00:08','2010-02-07 04:00:08','#'),(71,9,22,26,'PENDING','2010-02-03 04:00:08','2010-02-07 04:00:08','#'),(72,9,26,18,'SUCCESS','2010-02-03 04:00:08','2010-02-04 23:16:05','Beware these Socks of justice'),(73,9,18,16,'FAIL','2010-02-03 04:00:08','2010-02-04 23:16:05','#'),(74,9,10,13,'FAIL','2010-02-03 04:04:40','2010-02-03 04:06:25','#'),(75,9,12,13,'PENDING','2010-02-03 04:06:25','2010-02-07 04:06:25','#'),(76,9,19,12,'FAIL','2010-02-04 01:09:27','2010-02-04 23:01:33','#'),(77,9,25,14,'FAIL','2010-02-04 18:39:35','2010-02-05 01:01:31','#'),(78,9,16,19,'SUCCESS','2010-02-04 20:39:15','2010-02-04 23:01:33','Early to bed, early to rise, makes a man healthy, wealthy, and wise. Early to class gets a man killed in assassins.'),(79,9,16,12,'FAIL','2010-02-04 23:01:33','2010-02-05 01:03:33','#'),(80,9,26,16,'SUCCESS','2010-02-04 23:16:05','2010-02-05 01:03:33','Sitting in class, working so earnestly...sock of justice falls from heaven...'),(81,9,13,15,'SUCCESS','2010-02-05 01:00:18','2010-02-05 01:02:11','With Vadim\'s spoon!'),(82,9,15,14,'FAIL','2010-02-05 01:01:31','2010-02-05 01:02:11','#'),(83,9,13,14,'PENDING','2010-02-05 01:02:11','2010-02-09 01:02:11','#'),(84,9,26,12,'PENDING','2010-02-05 01:03:33','2010-02-09 01:03:33','#');
/*!40000 ALTER TABLE `assassinations` ENABLE KEYS */;
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
) ENGINE=MyISAM AUTO_INCREMENT=182 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `errors`
--

LOCK TABLES `errors` WRITE;
/*!40000 ALTER TABLE `errors` DISABLE KEYS */;
INSERT INTO `errors` (`errorId`, `type`, `error`, `extra`, `date`) VALUES (166,'USER','null\ncom.stanfordassassins.client.MyGame.updateTimerLabel(MyGame.java:89)\ncom.stanfordassassins.client.MyGame$1.run(MyGame.java:81)\ncom.google.gwt.user.client.Timer.fire(Timer.java:141)\nsun.reflect.GeneratedMethodAccessor11.invoke(Unknown Source)\nsun.reflect.DelegatingMethodAccessorImpl.invoke(DelegatingMethodAccessorImpl.java:25)\njava.lang.reflect.Method.invoke(Method.java:592)\ncom.google.gwt.dev.shell.MethodAdaptor.invoke(MethodAdaptor.java:103)\ncom.google.gwt.dev.shell.MethodDispatch.invoke(MethodDispatch.java:71)\ncom.google.gwt.dev.shell.OophmSessionHandler.invoke(OophmSessionHandler.java:157)\ncom.google.gwt.dev.shell.BrowserChannel.reactToMessagesWhileWaitingForReturn(BrowserChannel.java:1713)\ncom.google.gwt.dev.shell.BrowserChannelServer.invokeJavascript(BrowserChannelServer.java:165)\ncom.google.gwt.dev.shell.ModuleSpaceOOPHM.doInvoke(ModuleSpaceOOPHM.java:120)\ncom.google.gwt.dev.shell.ModuleSpace.invokeNative(ModuleSpace.java:507)\ncom.google.gwt.dev.shell.ModuleSpace.invokeNativeObject(ModuleSpace.java:264)\ncom.google.gwt.dev.shell.JavaScriptHost.invokeNativeObject(JavaScriptHost.java:91)\ncom.google.gwt.core.client.impl.Impl.apply(Impl.java)\ncom.google.gwt.core.client.impl.Impl.entry0(Impl.java:188)\nsun.reflect.GeneratedMethodAccessor10.invoke(Unknown Source)\nsun.reflect.DelegatingMethodAccessorImpl.invoke(DelegatingMethodAccessorImpl.java:25)\njava.lang.reflect.Method.invoke(Method.java:592)\ncom.google.gwt.dev.shell.MethodAdaptor.invoke(MethodAdaptor.java:103)\ncom.google.gwt.dev.shell.MethodDispatch.invoke(MethodDispatch.java:71)\ncom.google.gwt.dev.shell.OophmSessionHandler.invoke(OophmSessionHandler.java:157)\ncom.google.gwt.dev.shell.BrowserChannel.reactToMessages(BrowserChannel.java:1668)\ncom.google.gwt.dev.shell.BrowserChannelServer.processConnection(BrowserChannelServer.java:401)\ncom.google.gwt.dev.shell.BrowserChannelServer.run(BrowserChannelServer.java:222)\njava.lang.Thread.run(Thread.java:613)\n','1','2010-02-06 07:50:50'),(167,'USER','null\ncom.stanfordassassins.client.MyGame.updateTimerLabel(MyGame.java:89)\ncom.stanfordassassins.client.MyGame$1.run(MyGame.java:81)\ncom.google.gwt.user.client.Timer.fire(Timer.java:141)\nsun.reflect.GeneratedMethodAccessor11.invoke(Unknown Source)\nsun.reflect.DelegatingMethodAccessorImpl.invoke(DelegatingMethodAccessorImpl.java:25)\njava.lang.reflect.Method.invoke(Method.java:592)\ncom.google.gwt.dev.shell.MethodAdaptor.invoke(MethodAdaptor.java:103)\ncom.google.gwt.dev.shell.MethodDispatch.invoke(MethodDispatch.java:71)\ncom.google.gwt.dev.shell.OophmSessionHandler.invoke(OophmSessionHandler.java:157)\ncom.google.gwt.dev.shell.BrowserChannel.reactToMessagesWhileWaitingForReturn(BrowserChannel.java:1713)\ncom.google.gwt.dev.shell.BrowserChannelServer.invokeJavascript(BrowserChannelServer.java:165)\ncom.google.gwt.dev.shell.ModuleSpaceOOPHM.doInvoke(ModuleSpaceOOPHM.java:120)\ncom.google.gwt.dev.shell.ModuleSpace.invokeNative(ModuleSpace.java:507)\ncom.google.gwt.dev.shell.ModuleSpace.invokeNativeObject(ModuleSpace.java:264)\ncom.google.gwt.dev.shell.JavaScriptHost.invokeNativeObject(JavaScriptHost.java:91)\ncom.google.gwt.core.client.impl.Impl.apply(Impl.java)\ncom.google.gwt.core.client.impl.Impl.entry0(Impl.java:188)\nsun.reflect.GeneratedMethodAccessor10.invoke(Unknown Source)\nsun.reflect.DelegatingMethodAccessorImpl.invoke(DelegatingMethodAccessorImpl.java:25)\njava.lang.reflect.Method.invoke(Method.java:592)\ncom.google.gwt.dev.shell.MethodAdaptor.invoke(MethodAdaptor.java:103)\ncom.google.gwt.dev.shell.MethodDispatch.invoke(MethodDispatch.java:71)\ncom.google.gwt.dev.shell.OophmSessionHandler.invoke(OophmSessionHandler.java:157)\ncom.google.gwt.dev.shell.BrowserChannel.reactToMessages(BrowserChannel.java:1668)\ncom.google.gwt.dev.shell.BrowserChannelServer.processConnection(BrowserChannelServer.java:401)\ncom.google.gwt.dev.shell.BrowserChannelServer.run(BrowserChannelServer.java:222)\njava.lang.Thread.run(Thread.java:613)\n','1','2010-02-06 07:50:50'),(168,'USER','null\ncom.stanfordassassins.client.MyGame.updateTimerLabel(MyGame.java:89)\ncom.stanfordassassins.client.MyGame$1.run(MyGame.java:81)\ncom.google.gwt.user.client.Timer.fire(Timer.java:141)\nsun.reflect.GeneratedMethodAccessor11.invoke(Unknown Source)\nsun.reflect.DelegatingMethodAccessorImpl.invoke(DelegatingMethodAccessorImpl.java:25)\njava.lang.reflect.Method.invoke(Method.java:592)\ncom.google.gwt.dev.shell.MethodAdaptor.invoke(MethodAdaptor.java:103)\ncom.google.gwt.dev.shell.MethodDispatch.invoke(MethodDispatch.java:71)\ncom.google.gwt.dev.shell.OophmSessionHandler.invoke(OophmSessionHandler.java:157)\ncom.google.gwt.dev.shell.BrowserChannel.reactToMessagesWhileWaitingForReturn(BrowserChannel.java:1713)\ncom.google.gwt.dev.shell.BrowserChannelServer.invokeJavascript(BrowserChannelServer.java:165)\ncom.google.gwt.dev.shell.ModuleSpaceOOPHM.doInvoke(ModuleSpaceOOPHM.java:120)\ncom.google.gwt.dev.shell.ModuleSpace.invokeNative(ModuleSpace.java:507)\ncom.google.gwt.dev.shell.ModuleSpace.invokeNativeObject(ModuleSpace.java:264)\ncom.google.gwt.dev.shell.JavaScriptHost.invokeNativeObject(JavaScriptHost.java:91)\ncom.google.gwt.core.client.impl.Impl.apply(Impl.java)\ncom.google.gwt.core.client.impl.Impl.entry0(Impl.java:188)\nsun.reflect.GeneratedMethodAccessor10.invoke(Unknown Source)\nsun.reflect.DelegatingMethodAccessorImpl.invoke(DelegatingMethodAccessorImpl.java:25)\njava.lang.reflect.Method.invoke(Method.java:592)\ncom.google.gwt.dev.shell.MethodAdaptor.invoke(MethodAdaptor.java:103)\ncom.google.gwt.dev.shell.MethodDispatch.invoke(MethodDispatch.java:71)\ncom.google.gwt.dev.shell.OophmSessionHandler.invoke(OophmSessionHandler.java:157)\ncom.google.gwt.dev.shell.BrowserChannel.reactToMessages(BrowserChannel.java:1668)\ncom.google.gwt.dev.shell.BrowserChannelServer.processConnection(BrowserChannelServer.java:401)\ncom.google.gwt.dev.shell.BrowserChannelServer.run(BrowserChannelServer.java:222)\njava.lang.Thread.run(Thread.java:613)\n','1','2010-02-06 07:50:51'),(169,'USER','null\ncom.stanfordassassins.client.MyGame.updateTimerLabel(MyGame.java:89)\ncom.stanfordassassins.client.MyGame$1.run(MyGame.java:81)\ncom.google.gwt.user.client.Timer.fire(Timer.java:141)\nsun.reflect.GeneratedMethodAccessor11.invoke(Unknown Source)\nsun.reflect.DelegatingMethodAccessorImpl.invoke(DelegatingMethodAccessorImpl.java:25)\njava.lang.reflect.Method.invoke(Method.java:592)\ncom.google.gwt.dev.shell.MethodAdaptor.invoke(MethodAdaptor.java:103)\ncom.google.gwt.dev.shell.MethodDispatch.invoke(MethodDispatch.java:71)\ncom.google.gwt.dev.shell.OophmSessionHandler.invoke(OophmSessionHandler.java:157)\ncom.google.gwt.dev.shell.BrowserChannel.reactToMessagesWhileWaitingForReturn(BrowserChannel.java:1713)\ncom.google.gwt.dev.shell.BrowserChannelServer.invokeJavascript(BrowserChannelServer.java:165)\ncom.google.gwt.dev.shell.ModuleSpaceOOPHM.doInvoke(ModuleSpaceOOPHM.java:120)\ncom.google.gwt.dev.shell.ModuleSpace.invokeNative(ModuleSpace.java:507)\ncom.google.gwt.dev.shell.ModuleSpace.invokeNativeObject(ModuleSpace.java:264)\ncom.google.gwt.dev.shell.JavaScriptHost.invokeNativeObject(JavaScriptHost.java:91)\ncom.google.gwt.core.client.impl.Impl.apply(Impl.java)\ncom.google.gwt.core.client.impl.Impl.entry0(Impl.java:188)\nsun.reflect.GeneratedMethodAccessor10.invoke(Unknown Source)\nsun.reflect.DelegatingMethodAccessorImpl.invoke(DelegatingMethodAccessorImpl.java:25)\njava.lang.reflect.Method.invoke(Method.java:592)\ncom.google.gwt.dev.shell.MethodAdaptor.invoke(MethodAdaptor.java:103)\ncom.google.gwt.dev.shell.MethodDispatch.invoke(MethodDispatch.java:71)\ncom.google.gwt.dev.shell.OophmSessionHandler.invoke(OophmSessionHandler.java:157)\ncom.google.gwt.dev.shell.BrowserChannel.reactToMessages(BrowserChannel.java:1668)\ncom.google.gwt.dev.shell.BrowserChannelServer.processConnection(BrowserChannelServer.java:401)\ncom.google.gwt.dev.shell.BrowserChannelServer.run(BrowserChannelServer.java:222)\njava.lang.Thread.run(Thread.java:613)\n','1','2010-02-06 07:50:51'),(170,'USER','null\ncom.stanfordassassins.client.MyGame.updateTimerLabel(MyGame.java:89)\ncom.stanfordassassins.client.MyGame$1.run(MyGame.java:81)\ncom.google.gwt.user.client.Timer.fire(Timer.java:141)\nsun.reflect.GeneratedMethodAccessor11.invoke(Unknown Source)\nsun.reflect.DelegatingMethodAccessorImpl.invoke(DelegatingMethodAccessorImpl.java:25)\njava.lang.reflect.Method.invoke(Method.java:592)\ncom.google.gwt.dev.shell.MethodAdaptor.invoke(MethodAdaptor.java:103)\ncom.google.gwt.dev.shell.MethodDispatch.invoke(MethodDispatch.java:71)\ncom.google.gwt.dev.shell.OophmSessionHandler.invoke(OophmSessionHandler.java:157)\ncom.google.gwt.dev.shell.BrowserChannel.reactToMessagesWhileWaitingForReturn(BrowserChannel.java:1713)\ncom.google.gwt.dev.shell.BrowserChannelServer.invokeJavascript(BrowserChannelServer.java:165)\ncom.google.gwt.dev.shell.ModuleSpaceOOPHM.doInvoke(ModuleSpaceOOPHM.java:120)\ncom.google.gwt.dev.shell.ModuleSpace.invokeNative(ModuleSpace.java:507)\ncom.google.gwt.dev.shell.ModuleSpace.invokeNativeObject(ModuleSpace.java:264)\ncom.google.gwt.dev.shell.JavaScriptHost.invokeNativeObject(JavaScriptHost.java:91)\ncom.google.gwt.core.client.impl.Impl.apply(Impl.java)\ncom.google.gwt.core.client.impl.Impl.entry0(Impl.java:188)\nsun.reflect.GeneratedMethodAccessor10.invoke(Unknown Source)\nsun.reflect.DelegatingMethodAccessorImpl.invoke(DelegatingMethodAccessorImpl.java:25)\njava.lang.reflect.Method.invoke(Method.java:592)\ncom.google.gwt.dev.shell.MethodAdaptor.invoke(MethodAdaptor.java:103)\ncom.google.gwt.dev.shell.MethodDispatch.invoke(MethodDispatch.java:71)\ncom.google.gwt.dev.shell.OophmSessionHandler.invoke(OophmSessionHandler.java:157)\ncom.google.gwt.dev.shell.BrowserChannel.reactToMessages(BrowserChannel.java:1668)\ncom.google.gwt.dev.shell.BrowserChannelServer.processConnection(BrowserChannelServer.java:401)\ncom.google.gwt.dev.shell.BrowserChannelServer.run(BrowserChannelServer.java:222)\njava.lang.Thread.run(Thread.java:613)\n','1','2010-02-06 07:50:51'),(171,'USER','null\ncom.stanfordassassins.client.MyGame.updateTimerLabel(MyGame.java:89)\ncom.stanfordassassins.client.MyGame$1.run(MyGame.java:81)\ncom.google.gwt.user.client.Timer.fire(Timer.java:141)\nsun.reflect.GeneratedMethodAccessor11.invoke(Unknown Source)\nsun.reflect.DelegatingMethodAccessorImpl.invoke(DelegatingMethodAccessorImpl.java:25)\njava.lang.reflect.Method.invoke(Method.java:592)\ncom.google.gwt.dev.shell.MethodAdaptor.invoke(MethodAdaptor.java:103)\ncom.google.gwt.dev.shell.MethodDispatch.invoke(MethodDispatch.java:71)\ncom.google.gwt.dev.shell.OophmSessionHandler.invoke(OophmSessionHandler.java:157)\ncom.google.gwt.dev.shell.BrowserChannel.reactToMessagesWhileWaitingForReturn(BrowserChannel.java:1713)\ncom.google.gwt.dev.shell.BrowserChannelServer.invokeJavascript(BrowserChannelServer.java:165)\ncom.google.gwt.dev.shell.ModuleSpaceOOPHM.doInvoke(ModuleSpaceOOPHM.java:120)\ncom.google.gwt.dev.shell.ModuleSpace.invokeNative(ModuleSpace.java:507)\ncom.google.gwt.dev.shell.ModuleSpace.invokeNativeObject(ModuleSpace.java:264)\ncom.google.gwt.dev.shell.JavaScriptHost.invokeNativeObject(JavaScriptHost.java:91)\ncom.google.gwt.core.client.impl.Impl.apply(Impl.java)\ncom.google.gwt.core.client.impl.Impl.entry0(Impl.java:188)\nsun.reflect.GeneratedMethodAccessor10.invoke(Unknown Source)\nsun.reflect.DelegatingMethodAccessorImpl.invoke(DelegatingMethodAccessorImpl.java:25)\njava.lang.reflect.Method.invoke(Method.java:592)\ncom.google.gwt.dev.shell.MethodAdaptor.invoke(MethodAdaptor.java:103)\ncom.google.gwt.dev.shell.MethodDispatch.invoke(MethodDispatch.java:71)\ncom.google.gwt.dev.shell.OophmSessionHandler.invoke(OophmSessionHandler.java:157)\ncom.google.gwt.dev.shell.BrowserChannel.reactToMessages(BrowserChannel.java:1668)\ncom.google.gwt.dev.shell.BrowserChannelServer.processConnection(BrowserChannelServer.java:401)\ncom.google.gwt.dev.shell.BrowserChannelServer.run(BrowserChannelServer.java:222)\njava.lang.Thread.run(Thread.java:613)\n','1','2010-02-06 07:50:51'),(172,'USER','null\ncom.stanfordassassins.client.MyGame.updateTimerLabel(MyGame.java:89)\ncom.stanfordassassins.client.MyGame$1.run(MyGame.java:81)\ncom.google.gwt.user.client.Timer.fire(Timer.java:141)\nsun.reflect.GeneratedMethodAccessor11.invoke(Unknown Source)\nsun.reflect.DelegatingMethodAccessorImpl.invoke(DelegatingMethodAccessorImpl.java:25)\njava.lang.reflect.Method.invoke(Method.java:592)\ncom.google.gwt.dev.shell.MethodAdaptor.invoke(MethodAdaptor.java:103)\ncom.google.gwt.dev.shell.MethodDispatch.invoke(MethodDispatch.java:71)\ncom.google.gwt.dev.shell.OophmSessionHandler.invoke(OophmSessionHandler.java:157)\ncom.google.gwt.dev.shell.BrowserChannel.reactToMessagesWhileWaitingForReturn(BrowserChannel.java:1713)\ncom.google.gwt.dev.shell.BrowserChannelServer.invokeJavascript(BrowserChannelServer.java:165)\ncom.google.gwt.dev.shell.ModuleSpaceOOPHM.doInvoke(ModuleSpaceOOPHM.java:120)\ncom.google.gwt.dev.shell.ModuleSpace.invokeNative(ModuleSpace.java:507)\ncom.google.gwt.dev.shell.ModuleSpace.invokeNativeObject(ModuleSpace.java:264)\ncom.google.gwt.dev.shell.JavaScriptHost.invokeNativeObject(JavaScriptHost.java:91)\ncom.google.gwt.core.client.impl.Impl.apply(Impl.java)\ncom.google.gwt.core.client.impl.Impl.entry0(Impl.java:188)\nsun.reflect.GeneratedMethodAccessor10.invoke(Unknown Source)\nsun.reflect.DelegatingMethodAccessorImpl.invoke(DelegatingMethodAccessorImpl.java:25)\njava.lang.reflect.Method.invoke(Method.java:592)\ncom.google.gwt.dev.shell.MethodAdaptor.invoke(MethodAdaptor.java:103)\ncom.google.gwt.dev.shell.MethodDispatch.invoke(MethodDispatch.java:71)\ncom.google.gwt.dev.shell.OophmSessionHandler.invoke(OophmSessionHandler.java:157)\ncom.google.gwt.dev.shell.BrowserChannel.reactToMessages(BrowserChannel.java:1668)\ncom.google.gwt.dev.shell.BrowserChannelServer.processConnection(BrowserChannelServer.java:401)\ncom.google.gwt.dev.shell.BrowserChannelServer.run(BrowserChannelServer.java:222)\njava.lang.Thread.run(Thread.java:613)\n','1','2010-02-06 07:50:52'),(173,'USER','null\ncom.stanfordassassins.client.MyGame.updateTimerLabel(MyGame.java:89)\ncom.stanfordassassins.client.MyGame$1.run(MyGame.java:81)\ncom.google.gwt.user.client.Timer.fire(Timer.java:141)\nsun.reflect.GeneratedMethodAccessor11.invoke(Unknown Source)\nsun.reflect.DelegatingMethodAccessorImpl.invoke(DelegatingMethodAccessorImpl.java:25)\njava.lang.reflect.Method.invoke(Method.java:592)\ncom.google.gwt.dev.shell.MethodAdaptor.invoke(MethodAdaptor.java:103)\ncom.google.gwt.dev.shell.MethodDispatch.invoke(MethodDispatch.java:71)\ncom.google.gwt.dev.shell.OophmSessionHandler.invoke(OophmSessionHandler.java:157)\ncom.google.gwt.dev.shell.BrowserChannel.reactToMessagesWhileWaitingForReturn(BrowserChannel.java:1713)\ncom.google.gwt.dev.shell.BrowserChannelServer.invokeJavascript(BrowserChannelServer.java:165)\ncom.google.gwt.dev.shell.ModuleSpaceOOPHM.doInvoke(ModuleSpaceOOPHM.java:120)\ncom.google.gwt.dev.shell.ModuleSpace.invokeNative(ModuleSpace.java:507)\ncom.google.gwt.dev.shell.ModuleSpace.invokeNativeObject(ModuleSpace.java:264)\ncom.google.gwt.dev.shell.JavaScriptHost.invokeNativeObject(JavaScriptHost.java:91)\ncom.google.gwt.core.client.impl.Impl.apply(Impl.java)\ncom.google.gwt.core.client.impl.Impl.entry0(Impl.java:188)\nsun.reflect.GeneratedMethodAccessor10.invoke(Unknown Source)\nsun.reflect.DelegatingMethodAccessorImpl.invoke(DelegatingMethodAccessorImpl.java:25)\njava.lang.reflect.Method.invoke(Method.java:592)\ncom.google.gwt.dev.shell.MethodAdaptor.invoke(MethodAdaptor.java:103)\ncom.google.gwt.dev.shell.MethodDispatch.invoke(MethodDispatch.java:71)\ncom.google.gwt.dev.shell.OophmSessionHandler.invoke(OophmSessionHandler.java:157)\ncom.google.gwt.dev.shell.BrowserChannel.reactToMessages(BrowserChannel.java:1668)\ncom.google.gwt.dev.shell.BrowserChannelServer.processConnection(BrowserChannelServer.java:401)\ncom.google.gwt.dev.shell.BrowserChannelServer.run(BrowserChannelServer.java:222)\njava.lang.Thread.run(Thread.java:613)\n','1','2010-02-06 07:50:52'),(174,'USER','null\ncom.stanfordassassins.client.MyGame.updateTimerLabel(MyGame.java:89)\ncom.stanfordassassins.client.MyGame$1.run(MyGame.java:81)\ncom.google.gwt.user.client.Timer.fire(Timer.java:141)\nsun.reflect.GeneratedMethodAccessor11.invoke(Unknown Source)\nsun.reflect.DelegatingMethodAccessorImpl.invoke(DelegatingMethodAccessorImpl.java:25)\njava.lang.reflect.Method.invoke(Method.java:592)\ncom.google.gwt.dev.shell.MethodAdaptor.invoke(MethodAdaptor.java:103)\ncom.google.gwt.dev.shell.MethodDispatch.invoke(MethodDispatch.java:71)\ncom.google.gwt.dev.shell.OophmSessionHandler.invoke(OophmSessionHandler.java:157)\ncom.google.gwt.dev.shell.BrowserChannel.reactToMessagesWhileWaitingForReturn(BrowserChannel.java:1713)\ncom.google.gwt.dev.shell.BrowserChannelServer.invokeJavascript(BrowserChannelServer.java:165)\ncom.google.gwt.dev.shell.ModuleSpaceOOPHM.doInvoke(ModuleSpaceOOPHM.java:120)\ncom.google.gwt.dev.shell.ModuleSpace.invokeNative(ModuleSpace.java:507)\ncom.google.gwt.dev.shell.ModuleSpace.invokeNativeObject(ModuleSpace.java:264)\ncom.google.gwt.dev.shell.JavaScriptHost.invokeNativeObject(JavaScriptHost.java:91)\ncom.google.gwt.core.client.impl.Impl.apply(Impl.java)\ncom.google.gwt.core.client.impl.Impl.entry0(Impl.java:188)\nsun.reflect.GeneratedMethodAccessor10.invoke(Unknown Source)\nsun.reflect.DelegatingMethodAccessorImpl.invoke(DelegatingMethodAccessorImpl.java:25)\njava.lang.reflect.Method.invoke(Method.java:592)\ncom.google.gwt.dev.shell.MethodAdaptor.invoke(MethodAdaptor.java:103)\ncom.google.gwt.dev.shell.MethodDispatch.invoke(MethodDispatch.java:71)\ncom.google.gwt.dev.shell.OophmSessionHandler.invoke(OophmSessionHandler.java:157)\ncom.google.gwt.dev.shell.BrowserChannel.reactToMessages(BrowserChannel.java:1668)\ncom.google.gwt.dev.shell.BrowserChannelServer.processConnection(BrowserChannelServer.java:401)\ncom.google.gwt.dev.shell.BrowserChannelServer.run(BrowserChannelServer.java:222)\njava.lang.Thread.run(Thread.java:613)\n','1','2010-02-06 07:50:52'),(175,'USER','null\ncom.stanfordassassins.client.MyGame.updateTimerLabel(MyGame.java:89)\ncom.stanfordassassins.client.MyGame$1.run(MyGame.java:81)\ncom.google.gwt.user.client.Timer.fire(Timer.java:141)\nsun.reflect.GeneratedMethodAccessor11.invoke(Unknown Source)\nsun.reflect.DelegatingMethodAccessorImpl.invoke(DelegatingMethodAccessorImpl.java:25)\njava.lang.reflect.Method.invoke(Method.java:592)\ncom.google.gwt.dev.shell.MethodAdaptor.invoke(MethodAdaptor.java:103)\ncom.google.gwt.dev.shell.MethodDispatch.invoke(MethodDispatch.java:71)\ncom.google.gwt.dev.shell.OophmSessionHandler.invoke(OophmSessionHandler.java:157)\ncom.google.gwt.dev.shell.BrowserChannel.reactToMessagesWhileWaitingForReturn(BrowserChannel.java:1713)\ncom.google.gwt.dev.shell.BrowserChannelServer.invokeJavascript(BrowserChannelServer.java:165)\ncom.google.gwt.dev.shell.ModuleSpaceOOPHM.doInvoke(ModuleSpaceOOPHM.java:120)\ncom.google.gwt.dev.shell.ModuleSpace.invokeNative(ModuleSpace.java:507)\ncom.google.gwt.dev.shell.ModuleSpace.invokeNativeObject(ModuleSpace.java:264)\ncom.google.gwt.dev.shell.JavaScriptHost.invokeNativeObject(JavaScriptHost.java:91)\ncom.google.gwt.core.client.impl.Impl.apply(Impl.java)\ncom.google.gwt.core.client.impl.Impl.entry0(Impl.java:188)\nsun.reflect.GeneratedMethodAccessor10.invoke(Unknown Source)\nsun.reflect.DelegatingMethodAccessorImpl.invoke(DelegatingMethodAccessorImpl.java:25)\njava.lang.reflect.Method.invoke(Method.java:592)\ncom.google.gwt.dev.shell.MethodAdaptor.invoke(MethodAdaptor.java:103)\ncom.google.gwt.dev.shell.MethodDispatch.invoke(MethodDispatch.java:71)\ncom.google.gwt.dev.shell.OophmSessionHandler.invoke(OophmSessionHandler.java:157)\ncom.google.gwt.dev.shell.BrowserChannel.reactToMessages(BrowserChannel.java:1668)\ncom.google.gwt.dev.shell.BrowserChannelServer.processConnection(BrowserChannelServer.java:401)\ncom.google.gwt.dev.shell.BrowserChannelServer.run(BrowserChannelServer.java:222)\njava.lang.Thread.run(Thread.java:613)\n','1','2010-02-06 07:50:52'),(176,'USER','null\ncom.stanfordassassins.client.MyGame.updateTimerLabel(MyGame.java:89)\ncom.stanfordassassins.client.MyGame$1.run(MyGame.java:81)\ncom.google.gwt.user.client.Timer.fire(Timer.java:141)\nsun.reflect.GeneratedMethodAccessor11.invoke(Unknown Source)\nsun.reflect.DelegatingMethodAccessorImpl.invoke(DelegatingMethodAccessorImpl.java:25)\njava.lang.reflect.Method.invoke(Method.java:592)\ncom.google.gwt.dev.shell.MethodAdaptor.invoke(MethodAdaptor.java:103)\ncom.google.gwt.dev.shell.MethodDispatch.invoke(MethodDispatch.java:71)\ncom.google.gwt.dev.shell.OophmSessionHandler.invoke(OophmSessionHandler.java:157)\ncom.google.gwt.dev.shell.BrowserChannel.reactToMessagesWhileWaitingForReturn(BrowserChannel.java:1713)\ncom.google.gwt.dev.shell.BrowserChannelServer.invokeJavascript(BrowserChannelServer.java:165)\ncom.google.gwt.dev.shell.ModuleSpaceOOPHM.doInvoke(ModuleSpaceOOPHM.java:120)\ncom.google.gwt.dev.shell.ModuleSpace.invokeNative(ModuleSpace.java:507)\ncom.google.gwt.dev.shell.ModuleSpace.invokeNativeObject(ModuleSpace.java:264)\ncom.google.gwt.dev.shell.JavaScriptHost.invokeNativeObject(JavaScriptHost.java:91)\ncom.google.gwt.core.client.impl.Impl.apply(Impl.java)\ncom.google.gwt.core.client.impl.Impl.entry0(Impl.java:188)\nsun.reflect.GeneratedMethodAccessor10.invoke(Unknown Source)\nsun.reflect.DelegatingMethodAccessorImpl.invoke(DelegatingMethodAccessorImpl.java:25)\njava.lang.reflect.Method.invoke(Method.java:592)\ncom.google.gwt.dev.shell.MethodAdaptor.invoke(MethodAdaptor.java:103)\ncom.google.gwt.dev.shell.MethodDispatch.invoke(MethodDispatch.java:71)\ncom.google.gwt.dev.shell.OophmSessionHandler.invoke(OophmSessionHandler.java:157)\ncom.google.gwt.dev.shell.BrowserChannel.reactToMessages(BrowserChannel.java:1668)\ncom.google.gwt.dev.shell.BrowserChannelServer.processConnection(BrowserChannelServer.java:401)\ncom.google.gwt.dev.shell.BrowserChannelServer.run(BrowserChannelServer.java:222)\njava.lang.Thread.run(Thread.java:613)\n','1','2010-02-06 07:50:52'),(177,'USER','null\ncom.stanfordassassins.client.MyGame.updateTimerLabel(MyGame.java:89)\ncom.stanfordassassins.client.MyGame$1.run(MyGame.java:81)\ncom.google.gwt.user.client.Timer.fire(Timer.java:141)\nsun.reflect.GeneratedMethodAccessor11.invoke(Unknown Source)\nsun.reflect.DelegatingMethodAccessorImpl.invoke(DelegatingMethodAccessorImpl.java:25)\njava.lang.reflect.Method.invoke(Method.java:592)\ncom.google.gwt.dev.shell.MethodAdaptor.invoke(MethodAdaptor.java:103)\ncom.google.gwt.dev.shell.MethodDispatch.invoke(MethodDispatch.java:71)\ncom.google.gwt.dev.shell.OophmSessionHandler.invoke(OophmSessionHandler.java:157)\ncom.google.gwt.dev.shell.BrowserChannel.reactToMessagesWhileWaitingForReturn(BrowserChannel.java:1713)\ncom.google.gwt.dev.shell.BrowserChannelServer.invokeJavascript(BrowserChannelServer.java:165)\ncom.google.gwt.dev.shell.ModuleSpaceOOPHM.doInvoke(ModuleSpaceOOPHM.java:120)\ncom.google.gwt.dev.shell.ModuleSpace.invokeNative(ModuleSpace.java:507)\ncom.google.gwt.dev.shell.ModuleSpace.invokeNativeObject(ModuleSpace.java:264)\ncom.google.gwt.dev.shell.JavaScriptHost.invokeNativeObject(JavaScriptHost.java:91)\ncom.google.gwt.core.client.impl.Impl.apply(Impl.java)\ncom.google.gwt.core.client.impl.Impl.entry0(Impl.java:188)\nsun.reflect.GeneratedMethodAccessor10.invoke(Unknown Source)\nsun.reflect.DelegatingMethodAccessorImpl.invoke(DelegatingMethodAccessorImpl.java:25)\njava.lang.reflect.Method.invoke(Method.java:592)\ncom.google.gwt.dev.shell.MethodAdaptor.invoke(MethodAdaptor.java:103)\ncom.google.gwt.dev.shell.MethodDispatch.invoke(MethodDispatch.java:71)\ncom.google.gwt.dev.shell.OophmSessionHandler.invoke(OophmSessionHandler.java:157)\ncom.google.gwt.dev.shell.BrowserChannel.reactToMessages(BrowserChannel.java:1668)\ncom.google.gwt.dev.shell.BrowserChannelServer.processConnection(BrowserChannelServer.java:401)\ncom.google.gwt.dev.shell.BrowserChannelServer.run(BrowserChannelServer.java:222)\njava.lang.Thread.run(Thread.java:613)\n','1','2010-02-06 07:50:53'),(178,'USER','null\ncom.stanfordassassins.client.StanfordAssassins.login(StanfordAssassins.java:117)\ncom.stanfordassassins.client.StanfordAssassins$1.onResponseReceived(StanfordAssassins.java:75)\ncom.stanfordassassins.client.StanfordAssassins$7.onResponseReceived(StanfordAssassins.java:315)\ncom.google.gwt.http.client.Request.fireOnResponseReceived(Request.java:287)\ncom.google.gwt.http.client.RequestBuilder$1.onReadyStateChange(RequestBuilder.java:396)\nsun.reflect.NativeMethodAccessorImpl.invoke0(Native Method)\nsun.reflect.NativeMethodAccessorImpl.invoke(NativeMethodAccessorImpl.java:39)\nsun.reflect.DelegatingMethodAccessorImpl.invoke(DelegatingMethodAccessorImpl.java:25)\njava.lang.reflect.Method.invoke(Method.java:592)\ncom.google.gwt.dev.shell.MethodAdaptor.invoke(MethodAdaptor.java:103)\ncom.google.gwt.dev.shell.MethodDispatch.invoke(MethodDispatch.java:71)\ncom.google.gwt.dev.shell.OophmSessionHandler.invoke(OophmSessionHandler.java:157)\ncom.google.gwt.dev.shell.BrowserChannel.reactToMessagesWhileWaitingForReturn(BrowserChannel.java:1713)\ncom.google.gwt.dev.shell.BrowserChannelServer.invokeJavascript(BrowserChannelServer.java:165)\ncom.google.gwt.dev.shell.ModuleSpaceOOPHM.doInvoke(ModuleSpaceOOPHM.java:120)\ncom.google.gwt.dev.shell.ModuleSpace.invokeNative(ModuleSpace.java:507)\ncom.google.gwt.dev.shell.ModuleSpace.invokeNativeObject(ModuleSpace.java:264)\ncom.google.gwt.dev.shell.JavaScriptHost.invokeNativeObject(JavaScriptHost.java:91)\ncom.google.gwt.core.client.impl.Impl.apply(Impl.java)\ncom.google.gwt.core.client.impl.Impl.entry0(Impl.java:188)\nsun.reflect.NativeMethodAccessorImpl.invoke0(Native Method)\nsun.reflect.NativeMethodAccessorImpl.invoke(NativeMethodAccessorImpl.java:39)\nsun.reflect.DelegatingMethodAccessorImpl.invoke(DelegatingMethodAccessorImpl.java:25)\njava.lang.reflect.Method.invoke(Method.java:592)\ncom.google.gwt.dev.shell.MethodAdaptor.invoke(MethodAdaptor.java:103)\ncom.google.gwt.dev.shell.MethodDispatch.invoke(MethodDispatch.java:71)\ncom.google.gwt.dev.shell.OophmSessionHandler.invoke(OophmSessionHandler.java:157)\ncom.google.gwt.dev.shell.BrowserChannel.reactToMessages(BrowserChannel.java:1668)\ncom.google.gwt.dev.shell.BrowserChannelServer.processConnection(BrowserChannelServer.java:401)\ncom.google.gwt.dev.shell.BrowserChannelServer.run(BrowserChannelServer.java:222)\njava.lang.Thread.run(Thread.java:613)\n','1','2010-02-06 07:54:51'),(179,'USER','null\ncom.stanfordassassins.client.StanfordAssassins.login(StanfordAssassins.java:117)\ncom.stanfordassassins.client.StanfordAssassins$1.onResponseReceived(StanfordAssassins.java:75)\ncom.stanfordassassins.client.StanfordAssassins$7.onResponseReceived(StanfordAssassins.java:315)\ncom.google.gwt.http.client.Request.fireOnResponseReceived(Request.java:287)\ncom.google.gwt.http.client.RequestBuilder$1.onReadyStateChange(RequestBuilder.java:396)\nsun.reflect.NativeMethodAccessorImpl.invoke0(Native Method)\nsun.reflect.NativeMethodAccessorImpl.invoke(NativeMethodAccessorImpl.java:39)\nsun.reflect.DelegatingMethodAccessorImpl.invoke(DelegatingMethodAccessorImpl.java:25)\njava.lang.reflect.Method.invoke(Method.java:592)\ncom.google.gwt.dev.shell.MethodAdaptor.invoke(MethodAdaptor.java:103)\ncom.google.gwt.dev.shell.MethodDispatch.invoke(MethodDispatch.java:71)\ncom.google.gwt.dev.shell.OophmSessionHandler.invoke(OophmSessionHandler.java:157)\ncom.google.gwt.dev.shell.BrowserChannel.reactToMessagesWhileWaitingForReturn(BrowserChannel.java:1713)\ncom.google.gwt.dev.shell.BrowserChannelServer.invokeJavascript(BrowserChannelServer.java:165)\ncom.google.gwt.dev.shell.ModuleSpaceOOPHM.doInvoke(ModuleSpaceOOPHM.java:120)\ncom.google.gwt.dev.shell.ModuleSpace.invokeNative(ModuleSpace.java:507)\ncom.google.gwt.dev.shell.ModuleSpace.invokeNativeObject(ModuleSpace.java:264)\ncom.google.gwt.dev.shell.JavaScriptHost.invokeNativeObject(JavaScriptHost.java:91)\ncom.google.gwt.core.client.impl.Impl.apply(Impl.java)\ncom.google.gwt.core.client.impl.Impl.entry0(Impl.java:188)\nsun.reflect.NativeMethodAccessorImpl.invoke0(Native Method)\nsun.reflect.NativeMethodAccessorImpl.invoke(NativeMethodAccessorImpl.java:39)\nsun.reflect.DelegatingMethodAccessorImpl.invoke(DelegatingMethodAccessorImpl.java:25)\njava.lang.reflect.Method.invoke(Method.java:592)\ncom.google.gwt.dev.shell.MethodAdaptor.invoke(MethodAdaptor.java:103)\ncom.google.gwt.dev.shell.MethodDispatch.invoke(MethodDispatch.java:71)\ncom.google.gwt.dev.shell.OophmSessionHandler.invoke(OophmSessionHandler.java:157)\ncom.google.gwt.dev.shell.BrowserChannel.reactToMessages(BrowserChannel.java:1668)\ncom.google.gwt.dev.shell.BrowserChannelServer.processConnection(BrowserChannelServer.java:401)\ncom.google.gwt.dev.shell.BrowserChannelServer.run(BrowserChannelServer.java:222)\njava.lang.Thread.run(Thread.java:613)\n','1','2010-02-06 08:02:56'),(180,'USER','NO_PALYER\nUnknown.anonymous(Unknown source:0)\n','3','2010-02-08 20:39:24'),(181,'USER','NO_PALYER\nUnknown.anonymous(Unknown source:0)\n','3','2010-02-08 20:40:04');
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
INSERT INTO `gameNameAdj` (`word`) VALUES ('gold'),('green'),('white'),('yellow');
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
INSERT INTO `gameNameNoun` (`word`) VALUES ('Assassin'),('Tiger'),('Tomatoe');
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
  PRIMARY KEY (`gameId`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `games`
--

LOCK TABLES `games` WRITE;
/*!40000 ALTER TABLE `games` DISABLE KEYS */;
INSERT INTO `games` (`gameId`, `name`, `state`, `startDate`, `endDate`, `winnerId`) VALUES (2,'Operation Git Hub','FINISHED','2010-01-29 07:17:01','2010-01-29 19:48:16',1),(3,'Operarion Kill Bill','FINISHED','2010-01-30 07:31:56','2010-01-30 07:54:36',1),(4,'Operation Too Good','FINISHED','2010-01-30 08:04:52','2010-01-30 08:12:03',1),(5,'Operation Late Night','FINISHED','2010-02-02 08:20:00','2010-02-02 08:22:02',11),(6,'Operation Please Work','FINISHED','2010-02-02 17:55:14','2010-02-02 18:38:11',12),(7,'Operation Go On','FINISHED','2010-02-02 18:53:20','2010-02-02 18:55:38',1),(8,'Operation This Works','FINISHED','2010-02-02 18:59:03','2010-02-02 19:01:40',1),(9,'Operation Good Grades','ACTIVE','2010-02-03 04:00:08','0000-00-00 00:00:00',0);
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
  UNIQUE KEY `assassinationId` (`assassinationId`,`playerId`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `likes`
--

LOCK TABLES `likes` WRITE;
/*!40000 ALTER TABLE `likes` DISABLE KEYS */;
INSERT INTO `likes` (`likeId`, `assassinationId`, `playerId`) VALUES (1,80,12),(2,57,25),(3,81,13),(4,64,13),(5,81,22),(6,72,22),(7,64,22),(8,66,22),(9,80,22),(10,57,16);
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
  `state` enum('ACTIVE','ASSASSINATED','SUICIDE','WON') COLLATE utf8_unicode_ci NOT NULL,
  `alias` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `codeword` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`participationId`),
  UNIQUE KEY `gameId` (`gameId`,`playerId`)
) ENGINE=MyISAM AUTO_INCREMENT=61 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `participations`
--

LOCK TABLES `participations` WRITE;
/*!40000 ALTER TABLE `participations` DISABLE KEYS */;
INSERT INTO `participations` (`participationId`, `gameId`, `playerId`, `state`, `alias`, `codeword`) VALUES (18,2,12,'ASSASSINATED','Toenail','blow'),(17,2,10,'ASSASSINATED','PowerRanger','bird'),(16,2,1,'WON','AntiAliasing','band'),(19,3,1,'WON','BeerCap','car'),(20,3,10,'ASSASSINATED','PowerPenis','bread'),(21,3,11,'ASSASSINATED','GayLord','body'),(22,3,12,'ASSASSINATED','Dragon','blow'),(23,4,1,'WON','Dave Mathews','body'),(24,4,10,'ASSASSINATED','MegaMan','book'),(25,4,11,'ASSASSINATED','Paparas','answer'),(26,4,12,'ASSASSINATED','Aladin','beauty'),(33,5,11,'WON','Teminator2','bird'),(32,5,10,'ASSASSINATED','poopi','call'),(31,5,1,'ASSASSINATED','AlwaysReady','board'),(34,6,1,'ASSASSINATED','FairlyGoodQuestion','baby'),(35,6,2,'ASSASSINATED','Go\'olie','box'),(36,6,10,'ASSASSINATED','O\'Toole','animal'),(37,6,12,'WON','StartUp','bottom'),(38,7,1,'WON','Kantleen','answer'),(39,7,10,'ASSASSINATED','Tesla','bread'),(40,7,12,'ASSASSINATED','ComeOn!','bit'),(41,8,1,'WON','CanYoujoin','box'),(42,8,10,'ASSASSINATED','Poo','baby'),(43,8,12,'ASSASSINATED','ASYourself','animal'),(44,9,10,'ASSASSINATED','HoboKiller','age'),(45,9,11,'ASSASSINATED','Bill Gates','base'),(46,9,12,'ACTIVE','Inspector Gadget','ball'),(47,9,13,'ACTIVE','TerraIncognito','baby'),(48,9,14,'ACTIVE','aPinkElephant','blow'),(49,9,15,'ASSASSINATED','theassassinator','art'),(50,9,16,'ASSASSINATED','Segmentation_Fault','car'),(51,9,17,'ASSASSINATED','paarfi','bell'),(52,9,18,'ASSASSINATED','cakeeater','board'),(53,9,19,'ASSASSINATED','walrus','area'),(54,9,20,'ASSASSINATED','Voldemort','bottom'),(55,9,21,'ASSASSINATED','TheBigNoseinator','capital'),(56,9,22,'ACTIVE','Ghost','anger'),(57,9,23,'ASSASSINATED','gimme_a_sponge_bath','arm'),(58,9,24,'ACTIVE','Vadim?','box'),(59,9,25,'ASSASSINATED','sock','bit'),(60,9,26,'ACTIVE','johnwilkesthebooth','break');
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
  PRIMARY KEY (`playerId`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `players`
--

LOCK TABLES `players` WRITE;
/*!40000 ALTER TABLE `players` DISABLE KEYS */;
INSERT INTO `players` (`playerId`, `email`, `name`, `rule`, `dateCreated`, `lastLogin`, `state`, `waitingAlias`, `waitingStart`) VALUES (1,'test1@stanford.edu','Test1 Uset1','','0000-00-00 00:00:00','0000-00-00 00:00:00','NOTHING','','0000-00-00 00:00:00'),(2,'test2@stanford.edu','Test2 User2','','0000-00-00 00:00:00','0000-00-00 00:00:00','NOTHING','','0000-00-00 00:00:00'),(9,'demo@stanford.edu','Demo','','0000-00-00 00:00:00','0000-00-00 00:00:00','NOTHING','','0000-00-00 00:00:00'),(10,'vad@stanford.edu','Vadim Ogievetsky','stanford:student','0000-00-00 00:00:00','2010-02-11 19:07:59','NOTHING','','0000-00-00 00:00:00'),(11,'anomikos@stanford.edu','Andreas Nomikos','stanford:student','0000-00-00 00:00:00','2010-02-10 05:50:32','NOTHING','','0000-00-00 00:00:00'),(12,'jtamayo@stanford.edu','Juan Manuel Tamayo Gil','stanford:student','2010-01-28 06:38:26','2010-02-18 04:59:10','PLAYING','','0000-00-00 00:00:00'),(13,'jheer@cs.stanford.edu','Jeffrey Heer','stanford:administrative','2010-01-29 00:43:13','2010-02-11 23:57:42','PLAYING','','0000-00-00 00:00:00'),(14,'trcarden@stanford.edu','Tim Cardenas','stanford:student','2010-01-29 00:48:02','2010-02-03 02:30:19','PLAYING','','0000-00-00 00:00:00'),(15,'sdkamvar@stanford.edu','Sepandar David Kamvar','stanford:administrative','2010-01-29 00:48:21','2010-02-05 01:01:22','NOTHING','','0000-00-00 00:00:00'),(16,'mlinsey@stanford.edu','Mark Jacob Linsey','stanford:student','2010-01-29 03:12:32','2010-02-13 07:32:01','WAITING','Renegade','2010-02-05 08:46:43'),(17,'malee@stanford.edu','Marcia Anne Lee','stanford:student','2010-02-02 23:20:34','2010-02-04 20:38:39','NOTHING','','0000-00-00 00:00:00'),(18,'saahmad@stanford.edu','Salman Azeem Ahmad','stanford:student','2010-02-02 23:20:43','2010-02-02 23:21:09','NOTHING','','0000-00-00 00:00:00'),(19,'kcm@stanford.edu','Keith Clifton McDaniel','stanford:student','2010-02-02 23:20:44','2010-02-04 01:09:22','NOTHING','','0000-00-00 00:00:00'),(20,'knigh@stanford.edu','Kelly Lauren Nigh','stanford:student','2010-02-02 23:21:15','2010-02-05 01:01:51','NOTHING','','0000-00-00 00:00:00'),(21,'neema@stanford.edu','Neema Mortazavi Moraveji','stanford:student','2010-02-02 23:21:40','2010-02-03 20:02:12','NOTHING','','0000-00-00 00:00:00'),(22,'namwkim@stanford.edu','Nam Wook Kim','stanford:student','2010-02-02 23:22:02','2010-02-12 00:32:49','PLAYING','','0000-00-00 00:00:00'),(23,'iantien@stanford.edu','Ian Tien','stanford:student','2010-02-02 23:24:32','2010-02-11 23:56:27','WAITING','gimme_a_sponge_bath','2010-02-11 23:56:39'),(24,'fxchen@stanford.edu','Frank Xiaoxiao Chen','stanford:student','2010-02-02 23:24:49','2010-02-03 02:43:40','PLAYING','','0000-00-00 00:00:00'),(27,'ckita@stanford.edu','Chigusa Kita','stanford:administrative','2010-02-03 06:32:32','2010-02-03 06:32:32','WAITING','wildflowers','2010-02-03 06:32:59'),(25,'cabryant@stanford.edu','Coram Abiel Bryant','stanford:student','2010-02-03 01:22:24','2010-02-13 16:07:46','WAITING','Funk Art','2010-02-13 16:04:04'),(26,'jklein@stanford.edu','Jacob Aaron Klein','stanford:student','2010-02-03 01:25:25','2010-02-05 01:03:29','PLAYING','','0000-00-00 00:00:00'),(28,'juhokim@stanford.edu','Ju Ho Kim','stanford:student','2010-02-04 03:02:49','2010-02-04 03:02:49','WAITING','mcpanic','2010-02-04 03:02:56'),(29,'dtran320@stanford.edu','David Tran','stanford:student','2010-02-04 06:11:23','2010-02-10 22:28:24','NOTHING','','0000-00-00 00:00:00'),(30,'kpoppen@stanford.edu','Keegan Aaron Poppen','stanford:student','2010-02-04 06:33:10','2010-02-04 06:33:10','NOTHING','','0000-00-00 00:00:00'),(31,'gneokleo@stanford.edu','Giannis Neokleous','stanford:student','2010-02-05 08:18:25','2010-02-05 08:18:25','WAITING','giggidy','2010-02-05 08:19:22'),(32,'akothari@stanford.edu','Akshay Deepak Kothari','stanford:student','2010-02-05 20:49:13','2010-02-05 20:49:13','WAITING','Shay','2010-02-05 20:49:38');
/*!40000 ALTER TABLE `players` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2010-02-17 22:12:45

ALTER TABLE `participations` CHANGE `state` `state` ENUM( 'ACTIVE', 'ASSASSINATED', 'SUICIDE', 'WON', 'KICKED' ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ;

-- Script:

UPDATE players SET email = REPLACE(email, 'stanford.edu', 'test.edu') WHERE 1=1;
UPDATE players SET email='jtamayo@cs.stanford.edu' WHERE playerId = 12 LIMIT 1;
-- UPDATE assassinations SET endDate = '2010-02-19 12:12:12' WHERE assassinId = 12 AND state = 'PENDING' LIMIT 1;
-- UPDATE assassinations SET endDate = '2010-02-19 13:13:13' WHERE assassinId = 13 AND state = 'PENDING' LIMIT 1;
-- UPDATE assassinations SET endDate = '2010-02-19 14:14:14' WHERE assassinId = 14 AND state = 'PENDING' LIMIT 1;
-- UPDATE assassinations SET endDate = '2010-02-19 22:22:22' WHERE assassinId = 22 AND state = 'PENDING' LIMIT 1;