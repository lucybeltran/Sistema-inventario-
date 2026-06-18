-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: localhost    Database: inventario_mina
-- ------------------------------------------------------
-- Server version	8.0.30

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
-- Table structure for table `articulos`
--

DROP TABLE IF EXISTS `articulos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `articulos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `codigo` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unidad` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `grupo_id` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cantidad` decimal(10,2) NOT NULL DEFAULT '0.00',
  `precio` decimal(12,2) NOT NULL DEFAULT '0.00',
  `imagen` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `articulos_codigo_unique` (`codigo`),
  KEY `articulos_grupo_id_foreign` (`grupo_id`),
  CONSTRAINT `articulos_grupo_id_foreign` FOREIGN KEY (`grupo_id`) REFERENCES `grupos` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=143 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `articulos`
--

LOCK TABLES `articulos` WRITE;
/*!40000 ALTER TABLE `articulos` DISABLE KEYS */;
INSERT INTO `articulos` VALUES (1,'G-1/0001','NITRATO','KILOS','G-1',180.00,10.00,'articulos/G-1_0001_1779384886.jpg','2026-05-14 00:34:42','2026-05-25 03:20:06'),(2,'G-1/0002','DINAMITA','UNIDAD','G-1',0.00,7.00,'articulos/G-1_0002_1779384963.jpg','2026-05-14 00:34:42','2026-05-21 21:36:03'),(3,'G-1/0003','FULMINANTE','UNIDAD','G-1',0.00,0.00,'articulos/G-1_0003_1779385095.jpg','2026-05-14 00:34:42','2026-05-21 21:38:15'),(4,'G-1/0004','GUIA','METROS','G-1',0.00,0.00,'articulos/G-1_0004_1779385146.jpg','2026-05-14 00:34:42','2026-05-21 21:39:06'),(5,'G-1/0005','BARRA 0,80','UNIDAD','G-1',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(6,'G-1/0006','BARRA 1,20','UNIDAD','G-1',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(7,'G-1/0007','BARRA 1,80','UNIDAD','G-1',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(8,'G-1/0008','BARRENO 0,80','UNIDAD','G-1',0.00,0.00,'articulos/G-1_0008_1779385278.jpg','2026-05-14 00:34:42','2026-05-21 21:41:18'),(9,'G-1/0009','BARRENO 1,20','UNIDAD','G-1',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(10,'G-1/0010','BARRENO 1,80','UNIDAD','G-1',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(11,'G-1/0011','BROCA N° 39 mm','UNIDAD','G-1',0.00,0.00,'articulos/G-1_0011_1779385370.jpg','2026-05-14 00:34:42','2026-05-21 21:42:50'),(12,'G-1/0012','BROCA N° 41 mm','UNIDAD','G-1',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(13,'G-1/0013','GARRA \'1\' CON ESPIGA 3/4','UNIDAD','G-1',0.00,0.00,'articulos/G-1_0013_1779385555.webp','2026-05-14 00:34:42','2026-05-21 21:45:55'),(14,'G-1/0014','GARRA \'1\' CON ROSCA EXTERIOR 3/4','UNIDAD','G-1',0.00,0.00,'articulos/G-1_0014_1779385679.jpg','2026-05-14 00:34:42','2026-05-21 21:47:59'),(15,'G-1/0015','GARRA DE \'1\' CON ROSCA INTERIOR \'1\'','UNIDAD','G-1',0.00,0.00,'articulos/G-1_0015_1779385739.png','2026-05-14 00:34:42','2026-05-21 21:48:59'),(16,'G-1/0016','CARGADOR DE ANFO CON SPIGA DE 3/4','UNIDAD','G-1',0.00,0.00,'articulos/G-1_0016_1779385789.jpg','2026-05-14 00:34:42','2026-05-21 21:49:49'),(17,'G-2/0001','LLAVE DE PASO \'1\'','UNIDAD','G-2',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(18,'G-2/0002','LLAVE DE PASO \'1\' CORTINA','UNIDAD','G-2',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(19,'G-2/0003','LLAVE DE PASO \'2\'','UNIDAD','G-2',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(20,'G-2/0004','LLAVE DE PASO \'2\' CORTINA','UNIDAD','G-2',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(21,'G-2/0005','UNION PATENTE \'1\'','UNIDAD','G-2',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(22,'G-2/0006','UNION PATENTE \'2\'','UNIDAD','G-2',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(23,'G-2/0007','NIPLE DE \'1\'','UNIDAD','G-2',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(24,'G-2/0008','NIPLE DE \'2\'','UNIDAD','G-2',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(25,'G-2/0009','COPLA DE \'1\'','UNIDAD','G-2',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(26,'G-2/0010','COPLA DE \'2\'','UNIDAD','G-2',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(27,'G-2/0011','CODO DE \'2\'','UNIDAD','G-2',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(28,'G-2/0012','T DE \'1\'','UNIDAD','G-2',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(29,'G-2/0013','T DE \'2\'','UNIDAD','G-2',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(30,'G-2/0014','Y DE \'1\'','UNIDAD','G-2',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(31,'G-2/0015','Y DE \'2\'','UNIDAD','G-2',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(32,'G-2/0016','CANOTO CON SPIGA DE \'1\'','UNIDAD','G-2',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(33,'G-2/0017','CANOTO A ROSCA DE \'1\'','UNIDAD','G-2',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(34,'G-2/0018','CANOTO CON SPIGA DE \'2\'','UNIDAD','G-2',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(35,'G-2/0019','CANOTO A ROSCA DE \'2\'','UNIDAD','G-2',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(36,'G-2/0020','REDUCCION DE \'2\' A \'1\'','UNIDAD','G-2',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(37,'G-2/0021','REDUCCION DE \'2\' A \'1,5\'','UNIDAD','G-2',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(38,'G-2/0022','REDUCCION CON SPIGA DE \'2\' A \'1,5\'','UNIDAD','G-2',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(39,'G-2/0023','CLAVOS \'7\'','BOLSAS','G-2',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(40,'G-2/0024','CLAVOS \'6\'','BOLSAS','G-2',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(41,'G-2/0025','CLAVOS \'5\'','BOLSAS','G-2',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(42,'G-2/0026','CLAVOS \'4\'','BOLSAS','G-2',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(43,'G-2/0027','BARILLA DE 3/8','METROS','G-2',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(44,'G-2/0028','BARILLA DE 1/2','METROS','G-2',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(45,'G-2/0029','VOLANDAS PLANA DE 3/8','UNIDAD','G-2',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(46,'G-2/0030','TUERCA DE 3/8','UNIDAD','G-2',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(47,'G-2/0031','VOLANDAS PLANA DE 1/2','UNIDAD','G-2',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(48,'G-2/0032','TUERCA DE 1/2','UNIDAD','G-2',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(49,'G-2/0033','PERNOS DE 3/8 X \'2\'','UNIDAD','G-2',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(50,'G-2/0034','RODAMIENTO A BOLA /6209-2RS','UNIDAD','G-2',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(51,'G-2/0035','RODAMIENTO A BOLA /63092RSC3','UNIDAD','G-2',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(52,'G-2/0036','RADIO \'JANDI\'','UNIDAD','G-2',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(53,'G-3/0001','PICOTA CON PALA ANCHA','UNIDAD','G-3',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(54,'G-3/0002','PICOTA NORMAL','UNIDAD','G-3',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(55,'G-3/0003','PALA PUNTA HUEVO','UNIDAD','G-3',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(56,'G-3/0004','COMBO DE 2K','UNIDAD','G-3',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(57,'G-3/0005','COMBO DE 12 LB','UNIDAD','G-3',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(58,'G-3/0006','STYLSON # 24','UNIDAD','G-3',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(59,'G-3/0007','STYLSON # 14','UNIDAD','G-3',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(60,'G-3/0008','CIERRA MECANICA \'12\'','UNIDAD','G-3',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(61,'G-3/0009','CURVINA \'24\'','UNIDAD','G-3',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(62,'G-3/0010','DISCO DE DESGASTE DE \'9\'','UNIDAD','G-3',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(63,'G-3/0011','DISCO DE DESGASTE DE \'4,5\'','UNIDAD','G-3',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(64,'G-3/0012','DISCO DE CORTE \'7\'','UNIDAD','G-3',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(65,'G-3/0013','DISCO DE CORTE \'9\'','UNIDAD','G-3',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(66,'G-3/0014','DISCO DE CORTE \'4,5\'','UNIDAD','G-3',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(67,'G-3/0015','ELECTRODO E6013','UNIDAD','G-3',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(68,'G-3/0016','ELECTRODO E7018','UNIDAD','G-3',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(69,'G-3/0017','CABLE DE ACERO 1/2','METROS','G-3',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(70,'G-3/0018','CABLE DE ACERO 3/8','METROS','G-3',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(71,'G-3/0019','SOGA 3/4','METROS','G-3',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(72,'G-3/0020','SOGA 1/2','METROS','G-3',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(73,'G-3/0021','CEPILLO DE ACERO','UNIDAD','G-3',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(74,'G-3/0022','FLEXOMETRO DE 5mtrs','UNIDAD','G-3',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(75,'G-4/0001','ACEITE DE MAQUINA','LITROS','G-4',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(76,'G-4/0002','ACEITE MOTOR 15W40 DIESEL','LITROS','G-4',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(77,'G-4/0003','ACEITE TELLUS 2M / 68','LITROS','G-4',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(78,'G-4/0004','ACEITE HIDRAULICO ISO/68','LITROS','G-4',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(79,'G-4/0005','GASOLINA','LITROS','G-4',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(80,'G-4/0006','DIESEL','LITROS','G-4',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(81,'G-4/0007','GRASA DE RODAMIENTOS','KILOS','G-4',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(82,'G-5/0001','FILTRO DE AIRE C23610 (compresora)','UNIDAD','G-5',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(83,'G-5/0002','FILTRO DE AIRE C20500 (compresora)','UNIDAD','G-5',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(84,'G-5/0003','FILTRO DE AIRE SFA1107H (gen. Azul)','UNIDAD','G-5',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(85,'G-5/0004','FILTRO DE AIRE SFA1196H (gen. Blanco)','UNIDAD','G-5',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(86,'G-5/0005','FILTRO DE ACEITE PSL962 (compresora)','UNIDAD','G-5',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(87,'G-5/0006','FILTRO DE ACEITE 1R-0739 (pala)','UNIDAD','G-5',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(88,'G-5/0007','FILTRO DE DIESEL P551010','UNIDAD','G-5',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(89,'G-5/0008','CORREA 17x2845 B-112 (winche)','UNIDAD','G-5',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(90,'G-5/0009','CORREA Ax-32 (pala)','UNIDAD','G-5',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(91,'G-5/0010','CORREA A-52 (pala)','UNIDAD','G-5',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(92,'G-5/0011','CORREA A-72 (pala)','UNIDAD','G-5',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(93,'G-6/0001','SACO IMPERMEABLE TALLA \'M\'','UNIDAD','G-6',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(94,'G-6/0002','SACO IMPERMEABLE TALLA \'L\'','UNIDAD','G-6',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(95,'G-6/0003','PANTALON IMPERMEABLE TALLA \'M\'','UNIDAD','G-6',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(96,'G-6/0004','PANTALON IMPERMEABLE TALLA \'L\'','UNIDAD','G-6',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(97,'G-6/0005','OVEROLES TALLA \'M\'','UNIDAD','G-6',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(98,'G-6/0006','OVEROLES TALLA \'L\'','UNIDAD','G-6',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(99,'G-6/0007','OVEROLES TALLA \'XL\'','UNIDAD','G-6',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(100,'G-6/0008','CASCO MINERO BLANCO','UNIDAD','G-6',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(101,'G-6/0009','CASCO MINERO CAFÉ','UNIDAD','G-6',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(102,'G-6/0010','BOTAS DE GOMA \'38\'','UNIDAD','G-6',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(103,'G-6/0011','BOTAS DE GOMA \'39\'','UNIDAD','G-6',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(104,'G-6/0012','BOTAS DE GOMA \'40\'','UNIDAD','G-6',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(105,'G-6/0013','BOTAS DE GOMA \'41\'','UNIDAD','G-6',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(106,'G-6/0014','ARNES DE SEGURIDAD','UNIDAD','G-6',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(107,'G-6/0015','GUANTES CON GOMA','UNIDAD','G-6',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(108,'G-6/0016','LAMPARAS','UNIDAD','G-6',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(109,'G-7/0001','ARCO DE SOLDAR CROWN','UNIDAD','G-7',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(110,'G-7/0002','CARGADOR DE BATERIAS CD-530','UNIDAD','G-7',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(111,'G-7/0003','TUBO DE OXIGENO MEDIANO','UNIDAD','G-7',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(112,'G-7/0004','WINCHE TAMAÑO PEQUEÑO','UNIDAD','G-7',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(113,'G-7/0005','WINCHE TAMAÑO GRANDE','UNIDAD','G-7',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(114,'G-7/0006','AMOLADORA TAMAÑO GRANDE','UNIDAD','G-7',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(115,'G-7/0007','AMOLADORA TAMAÑO PEQUEÑO','UNIDAD','G-7',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(116,'G-7/0008','MOTO CIERRA (ineco)','UNIDAD','G-7',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(117,'G-7/0009','SOPLETE MANUAL','UNIDAD','G-7',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(118,'G-7/0010','SOPLETE DE PINTURA','UNIDAD','G-7',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(119,'G-8/0001','ANTICONGELANTE','LITROS','G-8',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(120,'G-8/0002','THINNER 900cc','LITROS','G-8',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(121,'G-8/0003','DESENGRASANTE DE MOTOR','LITROS','G-8',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(122,'G-8/0004','MONOPOL NEGRO','LITROS','G-8',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(123,'G-8/0005','MONOPOL AMARILLO','LITROS','G-8',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(124,'G-8/0006','MONOPOL AZUL','LITROS','G-8',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(125,'G-8/0007','AEROSOL VERDE','UNIDAD','G-8',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(126,'G-8/0008','AEROSOL AZUL','UNIDAD','G-8',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(127,'G-8/0009','AEROSOL ROJO','UNIDAD','G-8',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(128,'G-8/0010','AEROSOL AMARILLO','UNIDAD','G-8',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(129,'G-8/0011','LIMPIA CONTACTO','UNIDAD','G-8',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(130,'G-8/0012','PEGATANKE','UNIDAD','G-8',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(131,'G-9/0001','BOTIQUIN MEDICO','UNIDAD','G-9',0.00,0.00,NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(133,'G-10/0001','CALLAPOS','METROS','G-10',2.50,0.00,'articulos/G-10_0001_1779385860.jpg','2026-05-14 20:49:50','2026-05-21 21:51:00'),(134,'G-10/0002','CHAJLLA REDONDA D','METROS','G-10',2.50,0.00,NULL,'2026-05-14 20:51:29','2026-05-14 20:51:29'),(135,'G-10/0003','CHAJLLA RALLADA','METROS','G-10',2.50,0.00,NULL,'2026-05-14 20:52:07','2026-05-14 20:52:07'),(136,'G-10/0004','MADERA LABRADA DE 3X6','METROS','G-10',2.50,0.00,NULL,'2026-05-14 20:52:50','2026-05-14 20:52:50'),(137,'G-10/0005','DURMIENTE DE 3X6X','METROS','G-10',1.00,0.00,NULL,'2026-05-14 20:54:06','2026-05-14 20:54:06'),(138,'G-10/0006','LINEA DE MADERA DE 3X3X','METROS','G-10',4.00,0.00,NULL,'2026-05-14 20:56:27','2026-05-14 20:56:27'),(139,'G-10/0007','ESCALERA DE MADERA','METROS','G-10',4.00,0.00,NULL,'2026-05-14 20:57:31','2026-05-14 20:57:31'),(140,'G-10/0008','TABLON DE MADERA 1.5X3X','METROS','G-10',2.50,0.00,NULL,'2026-05-14 20:58:41','2026-05-14 20:58:41');
/*!40000 ALTER TABLE `articulos` ENABLE KEYS */;
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
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
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
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
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
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
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
-- Table structure for table `grupos`
--

DROP TABLE IF EXISTS `grupos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `grupos` (
  `id` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grupos`
--

LOCK TABLES `grupos` WRITE;
/*!40000 ALTER TABLE `grupos` DISABLE KEYS */;
INSERT INTO `grupos` VALUES ('G-1','MATERIAL EXPLOSIVO','2026-05-14 00:34:42','2026-05-14 00:34:42'),('G-10','MADERAS Y TABLONES','2026-05-14 20:24:20','2026-05-18 09:09:04'),('G-2','ACCESORIOS','2026-05-14 00:34:42','2026-05-14 00:34:42'),('G-3','HERRAMIENTAS','2026-05-14 00:34:42','2026-05-14 00:34:42'),('G-4','LUBRICANTES','2026-05-14 00:34:42','2026-05-14 00:34:42'),('G-5','FILTROS Y CORREAS','2026-05-14 00:34:42','2026-05-14 00:34:42'),('G-6','E.P.P.','2026-05-14 00:34:42','2026-05-14 00:34:42'),('G-7','HERRAMIENTAS DE MECANICA','2026-05-14 00:34:42','2026-05-14 00:34:42'),('G-8','PINTURAS Y ANTICONGELANTES','2026-05-14 00:34:42','2026-05-14 00:34:42'),('G-9','BOTIQUIN','2026-05-14 00:34:42','2026-05-14 00:34:42');
/*!40000 ALTER TABLE `grupos` ENABLE KEYS */;
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
  `attempts` smallint unsigned NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_05_13_202520_create_grupos_table',2),(5,'2026_05_13_202525_create_articulos_table',2),(6,'2026_05_13_202528_create_movimientos_table',2),(7,'2026_05_13_202533_add_rol_to_users_table',2),(8,'2026_05_14_124237_add_precio_to_articulos_table',3),(9,'2026_05_14_130727_create_trabajadores_table',4),(10,'2026_05_14_171051_add_trabajador_to_movimientos_table',5),(11,'2026_05_18_044004_cambiar_roles_usuarios',6),(12,'2026_05_24_230757_agregar_numero_nota_a_movimientos',7);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `movimientos`
--

DROP TABLE IF EXISTS `movimientos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `movimientos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `numero_nota` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `articulo_id` bigint unsigned NOT NULL,
  `tipo` enum('entrada','salida') COLLATE utf8mb4_unicode_ci NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `fecha` date NOT NULL,
  `notas` text COLLATE utf8mb4_unicode_ci,
  `user_id` bigint unsigned DEFAULT NULL,
  `trabajador_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `movimientos_articulo_id_foreign` (`articulo_id`),
  KEY `movimientos_user_id_foreign` (`user_id`),
  KEY `fk_movimientos_trabajador` (`trabajador_id`),
  CONSTRAINT `fk_movimientos_trabajador` FOREIGN KEY (`trabajador_id`) REFERENCES `trabajadores` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `movimientos_articulo_id_foreign` FOREIGN KEY (`articulo_id`) REFERENCES `articulos` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `movimientos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `movimientos`
--

LOCK TABLES `movimientos` WRITE;
/*!40000 ALTER TABLE `movimientos` DISABLE KEYS */;
INSERT INTO `movimientos` VALUES (1,'000001',1,'entrada',50.00,'2026-05-14',NULL,1,NULL,'2026-05-14 07:12:01','2026-05-14 07:12:01'),(2,'000002',2,'entrada',7.00,'2026-05-14',NULL,1,NULL,'2026-05-14 08:16:56','2026-05-14 08:16:56'),(3,'000003',1,'salida',25.00,'2026-05-14',NULL,1,NULL,'2026-05-14 17:31:39','2026-05-14 17:31:39'),(4,'000004',2,'salida',3.00,'2026-05-14',NULL,1,NULL,'2026-05-14 18:43:29','2026-05-14 18:43:29'),(5,'000005',1,'salida',4.99,'2026-05-14',NULL,1,1,'2026-05-14 21:32:43','2026-05-14 21:32:43'),(6,'000006',1,'salida',5.00,'2026-05-14',NULL,1,1,'2026-05-14 22:38:48','2026-05-14 22:38:48'),(7,'000007',2,'salida',0.99,'2026-05-14',NULL,1,1,'2026-05-14 22:41:09','2026-05-14 22:41:09'),(8,'000008',2,'salida',0.99,'2026-05-14',NULL,1,1,'2026-05-14 22:42:09','2026-05-14 22:42:09'),(9,'000009',2,'salida',1.02,'2026-05-14',NULL,1,1,'2026-05-14 22:42:32','2026-05-14 22:42:32'),(10,'000010',1,'salida',1.00,'2026-05-18',NULL,4,1,'2026-05-18 09:09:58','2026-05-18 09:09:58'),(11,'000011',2,'salida',1.00,'2026-05-18',NULL,4,1,'2026-05-18 09:10:20','2026-05-18 09:10:20'),(12,'000012',1,'entrada',200.00,'2026-05-18',NULL,1,NULL,'2026-05-19 01:20:09','2026-05-19 01:20:09'),(13,'000013',1,'salida',22.00,'2026-05-18','rajo minea copia',1,2,'2026-05-19 01:21:31','2026-05-19 01:21:31'),(14,'000014',1,'salida',2.00,'2026-05-24',NULL,1,2,'2026-05-25 03:12:33','2026-05-25 03:12:33'),(15,'000015',1,'salida',10.00,'2026-05-24',NULL,1,2,'2026-05-25 03:20:06','2026-05-25 03:20:06');
/*!40000 ALTER TABLE `movimientos` ENABLE KEYS */;
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
INSERT INTO `password_reset_tokens` VALUES ('admin@mina.local','$2y$12$TSkQdh/y260TsBWWZJp1pO7ql69MpMaxcxGey208CijUHdCxvB7nW','2026-05-26 07:39:51');
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
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
INSERT INTO `sessions` VALUES ('IDmd2hvVhZ2G7aTy3ioJIKqxWhrYYT95CHHhN0ZZ',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJadnBmQkNWYkFsVnplakRJMVNSb01yOEZyODBFZ0lmeE0ybGR0M1ZUIiwidXJsIjpbXSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2ludmVudGFyaW8tbWluYS50ZXN0XC9pbnZlbnRhcmlvIiwicm91dGUiOiJpbnZlbnRhcmlvLmluZGV4In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjF9',1779722488),('kRLsy5b8gZWkAV8GgKXriVvFIsmbJiwNx705Z1zB',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJxUklrQmI4MUhQNTF2TXNJUExsMFU5enZvREdreWJNcUM4TkdXZk1UIiwidXJsIjpbXSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2ludmVudGFyaW8tbWluYS50ZXN0XC9pbnZlbnRhcmlvIiwicm91dGUiOiJpbnZlbnRhcmlvLmluZGV4In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjF9',1779687508),('wtq26ptY7HedFuGDhzrHJuk30HjLwRW5DMDVlz8i',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','eyJfdG9rZW4iOiI3MzNOVzMzd2lER3plUTNaTHlJV3E5RzFNdGRnVWlJTUFOR0ZuMThyIiwidXJsIjpbXSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2ludmVudGFyaW8tbWluYS50ZXN0XC9pbnZlbnRhcmlvIiwicm91dGUiOiJpbnZlbnRhcmlvLmluZGV4In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjF9',1779768373);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `trabajadores`
--

DROP TABLE IF EXISTS `trabajadores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `trabajadores` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ci` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cargo` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `trabajadores_ci_unique` (`ci`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trabajadores`
--

LOCK TABLES `trabajadores` WRITE;
/*!40000 ALTER TABLE `trabajadores` DISABLE KEYS */;
INSERT INTO `trabajadores` VALUES (1,'JUAN PEREZ MAMANI','1234567','MINERO','71234568',1,'2026-05-14 17:17:12','2026-05-25 09:22:50'),(2,'LUIS QUISPE','1233445','PERFORISTA','78595959',1,'2026-05-14 17:24:32','2026-05-14 20:31:25');
/*!40000 ALTER TABLE `trabajadores` ENABLE KEYS */;
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
  `rol` enum('admin','almacenero','reportes') COLLATE utf8mb4_unicode_ci DEFAULT 'almacenero',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Administrador','admin@mina.local','admin',NULL,'$2y$12$6sTeAWSmUvbQcvqrWCGUGO.O3FL0l6t6WpzGXXqxnyUOgmYtt8YXe',NULL,'2026-05-14 00:34:42','2026-05-14 00:34:42'),(4,'Almacenero','almacenero@mina.local','almacenero',NULL,'$2y$12$KbZbHYEUTeid8b.F2Wmn9OrkMYH3lsvWOq0vY2B2h9ahpcoYotlV2',NULL,'2026-05-18 08:45:21','2026-05-18 08:45:21'),(5,'Reportes','reportes@mina.local','reportes',NULL,'$2y$12$tW8b6uE8Q3wdQ7Rni1JwkeW5jP3Y92KVMme5atUbt8FVy0d26.EvC',NULL,'2026-05-18 08:45:30','2026-05-18 08:45:30');
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

-- Dump completed on 2026-05-26  0:10:27
