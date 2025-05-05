-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 05-05-2025 a las 03:35:21
-- Versión del servidor: 9.1.0
-- Versión de PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `minisupervariedadesescobar`
--
CREATE DATABASE IF NOT EXISTS `minisupervariedadesescobar` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `minisupervariedadesescobar`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

DROP TABLE IF EXISTS `categorias`;
CREATE TABLE IF NOT EXISTS `categorias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `descripcion`) VALUES
(1, 'Granos básicos', 'Granos básicos'),
(2, 'Lacteos', ''),
(3, 'Jugueteria', ''),
(5, 'Limpieza', ''),
(6, 'Pan dulce', 'Pan dulce artesanal'),
(7, 'mascotas', ''),
(8, 'frutas', ''),
(9, 'Verduras', ''),
(10, 'Condimentos', ''),
(11, 'Hogar', ''),
(12, 'Cuidado Personal', ' Productos de higiene, cosméticos, etc.'),
(13, 'Cosmeticos', 'Cosmeticos para las girlies');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_ventas`
--

DROP TABLE IF EXISTS `detalle_ventas`;
CREATE TABLE IF NOT EXISTS `detalle_ventas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `venta_id` int DEFAULT NULL,
  `producto_id` int DEFAULT NULL,
  `cantidad` int NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `venta_id` (`venta_id`),
  KEY `producto_id` (`producto_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

DROP TABLE IF EXISTS `productos`;
CREATE TABLE IF NOT EXISTS `productos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sku` varchar(50) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text,
  `precio_compra` decimal(10,2) NOT NULL,
  `precio_venta` decimal(10,2) NOT NULL,
  `stock` int NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `categoria_id` int DEFAULT NULL,
  `fecha_caducidad` date DEFAULT NULL,
  `creado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sku` (`sku`),
  KEY `categoria_id` (`categoria_id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `sku`, `nombre`, `descripcion`, `precio_compra`, `precio_venta`, `stock`, `imagen`, `categoria_id`, `fecha_caducidad`, `creado_en`) VALUES
(1, 'ABC123', 'Arroz Blanco Don toñito MAX', 'Arroz local excelente calidad', 0.40, 0.65, 20, NULL, 10, '2026-01-09', '2025-05-01 10:20:40'),
(2, 'DEF123', 'Frijoles Rojos Don toñito', 'Frijoles locales', 0.35, 0.50, 30, NULL, 1, '0000-00-00', '2025-05-01 10:23:24'),
(5, 'GTR123', 'Arroz Premium', 'Arroz mejorado', 0.50, 0.70, 90, 'producto1.jpg', 1, '2026-01-01', '2025-05-02 23:41:48'),
(20, 'TUL59624', 'Tripi Trapa Toy', '', 3.00, 6.00, 4, 'img_6817e85b7cdce.jpg', 3, '0000-00-00', '2025-05-04 22:21:15'),
(7, 'TUN3840', 'Tung tung', NULL, 2.00, 3.00, 12, NULL, 3, '0000-00-00', '2025-05-03 04:41:28'),
(17, '35HTF', 'sdfsd', NULL, 3.00, 4.00, 12, NULL, 12, '2025-08-12', '2025-05-04 07:20:28'),
(9, 'TRE3940', 'tralalero SE', '', 3.00, 4.00, 12, 'img_6815a7276733c.jpeg', 3, '0000-00-00', '2025-05-03 05:18:31'),
(10, 'BOM4950', 'Gusini Bombardero', NULL, 3.50, 4.50, 20, 'img_6816a39480575.jpg', 3, '0000-00-00', '2025-05-03 23:15:32'),
(13, 'TRYU354', 'Arroz La Chula', NULL, 2.30, 3.25, 14, 'img_6816b58b9b704.jpg', 1, '2025-09-25', '2025-05-04 00:32:11'),
(14, 'GAT4903', 'Comida de gato gati 1LB', NULL, 1.40, 1.65, 10, 'img_6816fe40896fd.jpg', 7, '2025-10-04', '2025-05-04 05:42:24'),
(16, 'LAP9485', 'Lapiz labial', NULL, 2.00, 2.25, 10, 'img_681701551d411.png', 13, '2026-04-17', '2025-05-04 05:55:33'),
(18, 'fdsYTGF3', 'dsfs', NULL, 3.00, 4.00, 10, 'img_681715b7aed00.jpg', 8, '2025-07-31', '2025-05-04 07:22:31'),
(19, 'fdsYTGTG', 'dsfs', NULL, 3.00, 4.00, 10, 'img_681715efd9d7f.jpg', 8, '2025-07-31', '2025-05-04 07:23:27'),
(21, 'LILI4456', 'Lirili lalila Toy', 'Elefante en el deserto que camina cual camello', 2.24, 3.35, 12, NULL, 3, '0000-00-00', '2025-05-04 22:24:30'),
(22, '34GDJF3', 'Comida Don gato', '', 2.00, 3.00, 10, 'img_6817e987a583c.jpg', 7, '2025-11-06', '2025-05-04 22:26:15'),
(23, 'DET394', 'Detergente Maxisol', 'Detergente para ropa', 2.60, 3.00, 12, 'img_68180e0d14d7e.png', 5, '2027-03-01', '2025-05-05 01:00:53');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario` varchar(50) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `rol` enum('admin','empleado') DEFAULT 'empleado',
  `creado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario` (`usuario`),
  UNIQUE KEY `correo` (`correo`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `nombre`, `correo`, `contrasena`, `rol`, `creado_en`) VALUES
(5, 'TR220316', 'Tralalero Tralala', 'tralalerito@gmail.com', '$2y$10$rGx4bY4p32o9qB.UZ6RRn.qzhcvVuoGDxg.mV7KNjiNRyTajVUmv.', 'admin', '2025-05-01 12:25:12'),
(2, 'HM225678', 'Jorge Hernandez', 'jhmpokemon@gmail.com', '$2y$10$XqXU6kY3R7Yxx5CU6DV95eoh7DL2J4esYn.30PAd6Y8sNID1q.4Fy', 'empleado', '2025-05-01 11:02:26'),
(6, 'cocobomba', 'Bombardilo Cocodrilo', 'bombardilo@gmail.com', '$2y$10$tKXWc47qGpxAKsgEEUDupu1Jr1yJSc3UbIJ9bPka1skDZe5HNRkKq', 'admin', '2025-05-01 12:26:32'),
(4, 'ME225679', 'Angel Moreno Escobar', 'morenoescobar@gmail.com', '$2y$10$RbHE919IG1x7bzpq3SGi..HL9wvifrwUws4I.M27qCYk7avXEpvwO', 'empleado', '2025-05-01 11:09:16');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

DROP TABLE IF EXISTS `ventas`;
CREATE TABLE IF NOT EXISTS `ventas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int DEFAULT NULL,
  `total` decimal(10,2) NOT NULL,
  `metodo_pago` enum('efectivo','tarjeta','transferencia','otro') NOT NULL,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
