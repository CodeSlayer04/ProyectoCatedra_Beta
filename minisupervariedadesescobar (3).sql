-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 12-05-2025 a las 15:20:56
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
CREATE DATABASE IF NOT EXISTS `minisupervariedadesescobar` DEFAULT CHARACTER SET utf8mb4;
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
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4;

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
(13, 'Cosmeticos', 'Cosmeticos para las girlies'),
(14, 'Carnes', 'alimentos provenientes de animal'),
(15, 'optica', 'variedad de productos como lentes');

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
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `detalle_ventas`
--

INSERT INTO `detalle_ventas` (`id`, `venta_id`, `producto_id`, `cantidad`, `precio_unitario`, `subtotal`) VALUES
(1, 3, 1, 1, 0.65, 0.65),
(2, 4, 5, 1, 0.70, 0.70),
(3, 4, 20, 1, 6.00, 6.00),
(4, 5, 22, 1, 3.00, 3.00),
(5, 6, 1, 1, 0.65, 0.65),
(6, 7, 1, 1, 0.65, 0.65),
(7, 8, 2, 1, 0.50, 0.50),
(8, 9, 1, 1, 0.65, 0.65),
(9, 10, 14, 1, 1.65, 1.65),
(10, 10, 16, 1, 2.25, 2.25),
(11, 10, 18, 1, 4.00, 4.00),
(12, 11, 1, 1, 0.65, 0.65),
(13, 12, 1, 1, 0.65, 0.65),
(14, 13, 1, 1, 0.65, 0.65),
(15, 14, 1, 1, 0.65, 0.65),
(16, 15, 1, 1, 0.65, 0.65),
(17, 16, 1, 1, 0.65, 0.65),
(18, 17, 24, 10, 3.75, 37.50);

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
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `sku`, `nombre`, `descripcion`, `precio_compra`, `precio_venta`, `stock`, `imagen`, `categoria_id`, `fecha_caducidad`, `creado_en`) VALUES
(1, 'ABC123', 'Arroz Blanco Don toñito MAX', 'Arroz local excelente calidad', 0.40, 0.65, 4, 'img_6821fd2d45845.jpeg', 1, '2026-01-09', '2025-05-01 10:20:40'),
(2, 'DEF123', 'Frijoles Rojos Don toñito', 'Frijoles locales', 0.35, 0.50, 30, 'img_68217774d16c1.jpeg', 1, '0000-00-00', '2025-05-01 10:23:24'),
(5, 'GTR123', 'Arroz Premium', 'Arroz mejorado', 0.58, 0.70, 90, 'img_6821762a8ea95.jpeg', 1, '2026-01-01', '2025-05-02 23:41:48'),
(20, 'TUL59624', 'Tripi Trapa Toy', '', 3.00, 6.00, 4, 'img_68216b842dfdf.jpeg', 3, '0000-00-00', '2025-05-04 22:21:15'),
(7, 'TUN3840', 'Tung tung', '', 2.00, 3.00, 12, 'img_6821781427be8.jpeg', 3, '0000-00-00', '2025-05-03 04:41:28'),
(9, 'TRE3940', 'tralalero SE', '', 3.00, 4.00, 12, 'img_6821786c08e23.jpeg', 3, '0000-00-00', '2025-05-03 05:18:31'),
(10, 'BOM4950', 'Gusini Bombardero', '', 3.50, 4.50, 20, 'img_682179080d8ba.jpeg', 3, '0000-00-00', '2025-05-03 23:15:32'),
(13, 'TRYU354', 'Arroz La Chula', '', 2.30, 3.25, 14, 'img_68217998595d4.jpeg', 1, '2025-09-25', '2025-05-04 00:32:11'),
(14, 'GAT4903', 'Comida de gato gati 1LB', '', 1.40, 1.65, 10, 'img_682179edc322c.jpeg', 7, '2025-10-04', '2025-05-04 05:42:24'),
(16, 'LAP9485', 'Lapiz labial', '', 2.00, 2.25, 10, 'img_68217a45a6237.jpeg', 13, '2026-04-17', '2025-05-04 05:55:33'),
(18, 'fdsYTGF3', 'Durián', '', 3.00, 4.00, 10, 'img_68217aa95b085.jpeg', 8, '2025-07-31', '2025-05-04 07:22:31'),
(19, 'fdsYTGTG', 'Melon', '', 3.00, 4.00, 10, 'img_68217b2871712.jpeg', 8, '2025-07-31', '2025-05-04 07:23:27'),
(21, 'LILI4456', 'Lirili lalila Toy', 'Elefante en el deserto que camina cual camello', 2.24, 3.35, 12, 'img_6821fc872c87c.jpeg', 3, '0000-00-00', '2025-05-04 22:24:30'),
(22, '34GDJF3', 'Comida Don gato', '', 2.00, 3.00, 10, 'img_6821fc456158e.jpeg', 7, '2025-11-06', '2025-05-04 22:26:15'),
(23, 'DET394', 'Detergente Maxisol', 'Detergente para ropa', 2.60, 3.00, 12, 'img_6821fbf0df89f.png', 5, '2027-03-01', '2025-05-05 01:00:53'),
(24, '123456789', 'shampoo', 'pantene', 2.50, 3.75, 90, 'img_6821fb728f42f.jpeg', 12, '2029-05-15', '2025-05-05 15:27:30'),
(25, 'L3CH344', 'leche', 'leche', 2.00, 2.75, 30, 'img_6821feb8c6cfb.jpeg', 2, '0000-00-00', '2025-05-12 13:59:20'),
(26, 'C4RN317', 'Carne', 'carne', 3.00, 4.50, 25, 'img_6821ff55bfa39.jpeg', 14, '2025-05-31', '2025-05-12 14:01:57'),
(27, 'V3RDUR4', 'zanahoria', 'que hay de nuevo viejo', 0.50, 0.75, 40, 'img_6821fff9235f1.jpeg', 9, '2025-05-19', '2025-05-12 14:04:41'),
(28, 'P1CH420', 'pichardin', 'pichardin en catalan', 0.25, 0.30, 10, 'img_682201473642a.jpeg', 6, '0000-00-00', '2025-05-12 14:10:15'),
(29, 'P1M13N', 'pimienta', 'wawawawa', 0.75, 1.00, 38, 'img_682202364db37.jpeg', 10, '2026-06-10', '2025-05-12 14:14:14'),
(30, 'V3L467', 'vela', '', 1.00, 1.15, 40, 'img_682204411d168.jpeg', 11, '0000-00-00', '2025-05-12 14:22:57'),
(31, 'FR0Z0', 'lentes de sol', 'frozono', 5.00, 7.50, 10, 'img_68220576cc1b6.jpeg', 15, '0000-00-00', '2025-05-12 14:28:06');

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
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `nombre`, `correo`, `contrasena`, `rol`, `creado_en`) VALUES
(5, 'TR220316', 'Tralalero Tralala', 'tralalerito@gmail.com', '$2y$10$rGx4bY4p32o9qB.UZ6RRn.qzhcvVuoGDxg.mV7KNjiNRyTajVUmv.', 'admin', '2025-05-01 12:25:12'),
(2, 'HM225678', 'Jorge Hernandez', 'jhmpokemon@gmail.com', '$2y$10$XqXU6kY3R7Yxx5CU6DV95eoh7DL2J4esYn.30PAd6Y8sNID1q.4Fy', 'empleado', '2025-05-01 11:02:26'),
(6, 'cocobomba', 'Bombardilo Cocodrilo', 'bombardilo@gmail.com', '$2y$10$tKXWc47qGpxAKsgEEUDupu1Jr1yJSc3UbIJ9bPka1skDZe5HNRkKq', 'admin', '2025-05-01 12:26:32'),
(4, 'ME225679', 'Angel Moreno Escobar', 'morenoescobar@gmail.com', '$2y$10$RbHE919IG1x7bzpq3SGi..HL9wvifrwUws4I.M27qCYk7avXEpvwO', 'empleado', '2025-05-01 11:09:16'),
(7, 'Poio', 'Ricardo Escobar', 'wawawa@gmail.com', '$2y$10$tEG.uogUqHdRDvWbfWrpvuFVdUmFXvRMkR9ZxSgHNe8kHxAQd5BOS', 'empleado', '2025-05-05 15:35:10'),
(10, 'admin', 'Su nombre', 'sucorreo@dominio.com', '$2y$10$cg.bLLTZ8TBNSapKLXh8n.AsmvWNcZgrGyY3eL6e.JX4SfaDYLj7y', 'admin', '2025-05-12 11:15:07');
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
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id`, `usuario_id`, `total`, `metodo_pago`, `fecha`) VALUES
(1, 12, 20.50, 'efectivo', '2025-05-09 19:48:03'),
(2, 0, 30.40, 'tarjeta', '2025-05-09 20:32:16'),
(3, 6, 0.65, 'efectivo', '2025-05-10 04:45:42'),
(4, 6, 6.70, 'efectivo', '2025-05-10 04:51:04'),
(5, 6, 3.00, 'tarjeta', '2025-05-10 04:52:12'),
(6, 6, 0.65, 'tarjeta', '2025-05-10 04:56:39'),
(7, 6, 0.65, 'tarjeta', '2025-05-10 05:02:38'),
(8, 6, 0.50, 'tarjeta', '2025-05-10 05:06:57'),
(9, 6, 0.65, 'efectivo', '2025-05-10 05:11:07'),
(10, 6, 7.90, 'efectivo', '2025-05-10 05:21:28'),
(11, 6, 0.65, 'efectivo', '2025-05-11 03:43:26'),
(12, 6, 0.65, 'efectivo', '2025-05-11 03:44:29'),
(13, 6, 0.65, 'efectivo', '2025-05-11 03:49:21'),
(14, 6, 0.65, 'efectivo', '2025-05-11 03:56:12'),
(15, 6, 0.65, 'efectivo', '2025-05-11 04:05:23'),
(16, 6, 0.65, 'efectivo', '2025-05-11 04:07:33'),
(17, 6, 37.50, 'efectivo', '2025-05-11 16:53:00');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
