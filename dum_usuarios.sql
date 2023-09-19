-- MySQL dump 10.13  Distrib 8.0.34, for Linux (x86_64)
--
-- Host: 31.170.161.64    Database: u682444666_stock
-- ------------------------------------------------------
-- Server version	5.5.5-10.5.19-MariaDB-cll-lve

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
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `usuario` varchar(100) NOT NULL,
  `nombre` varchar(200) DEFAULT NULL,
  `pass` varchar(50) DEFAULT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `estado` varchar(20) DEFAULT 'Activo',
  PRIMARY KEY (`usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES ('Accesotemp','Temporal','2023','operativo',NULL,'Activo'),('acsierra','Alberto Carlos Sierra','2080','administrativo','auxadministrativo@sudicol.com.co','Activo'),('aflorez','Alex florez','1234','administrativo','aflorez@lecta.com.co','Activo'),('apadilla','Angelica Padilla','1234','administrativo','contabilidad@sudicol.com.co','Activo'),('Desirethp','Desireth Perez','sudicol2023','operativo','logistica@sudicol.com.co','Activo'),('kathep','Katherine Padilla','1716','operativo','servicioalcliente@sudicol.com.co','Activo'),('lumens','Milton','123','operativo','milto','Activo'),('marisellh','Marisell Hernandez','sudicol2023','operativo','taller@sudicol.com.co','Activo'),('MOISESM','MOISES','Isaacjuan02','administrativo','diradministrativo@sudicol.com.co','Activo');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-09-19 13:41:46
