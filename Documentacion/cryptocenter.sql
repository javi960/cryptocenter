-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 12-12-2021 a las 15:02:29
-- Versión del servidor: 10.5.12-MariaDB-cll-lve
-- Versión de PHP: 7.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `puuzxume_cryptocenter`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `beneficios_plataforma`
--

CREATE TABLE IF NOT EXISTS `beneficios_plataforma` (
  `id_beneficioP` int(11) NOT NULL AUTO_INCREMENT,
  `cantidad` float NOT NULL,
  `id_compraVenta` int(11) NOT NULL,
  PRIMARY KEY (`id_beneficioP`),
  KEY `id_compraVenta` (`id_compraVenta`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `beneficios_plataforma`
--

INSERT INTO `beneficios_plataforma` (`id_beneficioP`, `cantidad`, `id_compraVenta`) VALUES
(23, 2, 39),
(24, 0.113705, 40),
(25, 20, 41),
(26, 60, 42),
(27, 20, 43),
(28, 1.1961, 44);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra_venta`
--

CREATE TABLE IF NOT EXISTS `compra_venta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `moneda` varchar(15) NOT NULL,
  `valor` float NOT NULL,
  `fecha_c` varchar(20) NOT NULL,
  `t_accion` varchar(50) NOT NULL,
  `cantidad` float NOT NULL,
  `dinero` float NOT NULL,
  `n_usuario` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `n_usuario` (`n_usuario`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `compra_venta`
--

INSERT INTO `compra_venta` (`id`, `moneda`, `valor`, `fecha_c`, `t_accion`, `cantidad`, `dinero`, `n_usuario`) VALUES
(39, 'ADA', 1.13392, '11/12/2021 13:48:4', 'comprar', 86.4258, 98, 'cacimi'),
(40, 'ADA', 1.13705, '11/12/2021 13:56:25', 'vender', 10, 11.2568, 'cacimi'),
(41, 'ADA', 1.15789, '11/12/2021 19:36:51', 'comprar', 846.37, 980, 'usuario'),
(42, 'ETH', 3575.79, '11/12/2021 19:36:59', 'comprar', 0.542537, 1940, 'usuario'),
(43, 'ADA', 1.15769, '11/12/2021 19:38:1', 'comprar', 846.514, 980, 'cacimi'),
(44, 'ADA', 1.1961, '12/12/2021 14:15:40', 'vender', 100, 118.414, 'usuario');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `datos_usuario`
--

CREATE TABLE IF NOT EXISTS `datos_usuario` (
  `dni` varchar(9) NOT NULL,
  `nombre_u` varchar(20) NOT NULL,
  `apellido1` varchar(20) NOT NULL,
  `apellido2` varchar(20) DEFAULT NULL,
  `telefono` int(9) NOT NULL,
  `direccion` varchar(50) NOT NULL,
  `poblacion` varchar(20) NOT NULL,
  `provincia` varchar(20) NOT NULL,
  `pais` varchar(20) NOT NULL,
  `n_usuario` varchar(30) NOT NULL,
  UNIQUE KEY `id_usuario` (`n_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `datos_usuario`
--

INSERT INTO `datos_usuario` (`dni`, `nombre_u`, `apellido1`, `apellido2`, `telefono`, `direccion`, `poblacion`, `provincia`, `pais`, `n_usuario`) VALUES
('68461860Z', 'FERNANDO', 'FERNANDEZ', 'FERNANDEZ', 123456789, 'C/ LLANA 5', 'MADRID', 'MADRID', 'ESPAÑA', 'anfico'),
('15984103T', 'PABLO', 'LOPEZ', 'LOPEZ', 123456789, 'CARRETERA DEL MUELLE 6', 'BARCELONA', 'BARCELONA', 'ESPAÑA', 'bagola'),
('28309096Y', 'LUCAS', 'SANCHEZ', 'ABADIA', 789666555, 'CARRETERA DEL MUELLE 4', 'BARCELONA', 'BARCELONA', 'ESPAÑA', 'cacimi'),
('87209322S', 'TOMAS', 'FERNANDEZ', 'FERNANDEZ', 666333999, 'C/ LLANA 6', 'CÁCERES', 'CÁCERES', 'ESPAÑA', 'usuario');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fondos`
--

CREATE TABLE IF NOT EXISTS `fondos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `n_fondos` float NOT NULL,
  `moneda_fondo` varchar(5) NOT NULL,
  `n_usuario` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `n_usuario` (`n_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `fondos`
--

INSERT INTO `fondos` (`id`, `n_fondos`, `moneda_fondo`, `n_usuario`) VALUES
(5, 0, 'EUR', 'anfico'),
(8, 7198.41, 'EUR', 'usuario'),
(13, 7933.26, 'EUR', 'cacimi'),
(14, 0, 'EUR', 'bagola');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `monedas`
--

CREATE TABLE IF NOT EXISTS `monedas` (
  `id_moneda` varchar(10) NOT NULL,
  `nombre` varchar(20) DEFAULT NULL,
  `BenfCompra` float DEFAULT NULL,
  `BenfVenta` float DEFAULT NULL,
  `activo` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`id_moneda`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `monedas`
--

INSERT INTO `monedas` (`id_moneda`, `nombre`, `BenfCompra`, `BenfVenta`, `activo`) VALUES
('$PAC', 'PACcoin', 3, 3, '1'),
('0X', '0X', 0, 0, '0'),
('10SET', '10SET', 2, 3, '1'),
('ADA', 'Cardano', 2, 1, '1'),
('BTC', 'Bitcoin', 5, 7, '1'),
('ETH', 'Ethereum', 3, 6, '1'),
('LTC', 'Litecoin', 6, 4, '1'),
('VEN', 'VeChain (pre-swap)', 4, 6, '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `monedas_cartera`
--

CREATE TABLE IF NOT EXISTS `monedas_cartera` (
  `id_monedaC` int(11) NOT NULL AUTO_INCREMENT,
  `cantidad` float NOT NULL,
  `id_moneda` varchar(30) NOT NULL,
  `n_usuario` varchar(30) NOT NULL,
  PRIMARY KEY (`id_monedaC`),
  KEY `n_usuario` (`n_usuario`) USING BTREE,
  KEY `id_moneda` (`id_moneda`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `monedas_cartera`
--

INSERT INTO `monedas_cartera` (`id_monedaC`, `cantidad`, `id_moneda`, `n_usuario`) VALUES
(19, 746.37, 'ADA', 'usuario'),
(20, 0.542537, 'ETH', 'usuario'),
(21, 846.514, 'ADA', 'cacimi');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `monedas_seguimiento`
--

CREATE TABLE IF NOT EXISTS `monedas_seguimiento` (
  `id_seguido` int(11) NOT NULL AUTO_INCREMENT,
  `id_moneda` varchar(15) NOT NULL,
  `n_usuario` varchar(20) NOT NULL,
  PRIMARY KEY (`id_seguido`),
  UNIQUE KEY `id_moneda` (`id_moneda`,`n_usuario`),
  KEY `n_usuario` (`n_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `monedas_seguimiento`
--

INSERT INTO `monedas_seguimiento` (`id_seguido`, `id_moneda`, `n_usuario`) VALUES
(22, '10SET', 'metaci'),
(25, 'ADA', 'cacimi'),
(20, 'ADA', 'metaci'),
(23, 'ADA', 'usuario'),
(21, 'ETH', 'metaci'),
(24, 'ETH', 'usuario');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro_fondo`
--

CREATE TABLE IF NOT EXISTS `registro_fondo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `accion` varchar(20) NOT NULL,
  `cantidad_fondo` varchar(10) NOT NULL,
  `fecha` varchar(40) NOT NULL,
  `id_usuario` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `registro_fondo`
--

INSERT INTO `registro_fondo` (`id`, `accion`, `cantidad_fondo`, `fecha`, `id_usuario`) VALUES
(10, 'cargar', '10000', '11/12/2021 13:44:26', 'cacimi'),
(11, 'retirar', '1000', '11/12/2021 13:45:20', 'cacimi'),
(12, 'cargar', '10000', '11/12/2021 19:36:40', 'usuario');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
  `n_usuario` varchar(30) NOT NULL,
  `pass` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  `rol` varchar(20) DEFAULT NULL,
  `Activo` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`n_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`n_usuario`, `pass`, `email`, `rol`, `Activo`) VALUES
('admin', '$2y$10$8tnnCy3W7W5Uy7StKc8Ro.QB5NJwwfwmA4nfbRtA9COjNCEQSK/CC', 'admin2@admin.es', 'Administrador', '1'),
('anfico', '$2y$10$jW9MFEjMw4pIcPE0FEsaVOqnQAgfsdN0p7mS3zZ8WCIXQLpIKI3z.', 'anfico@anfico.es', 'Usuario', '1'),
('bagola', '$2y$10$FqR7oFNzl87LXIPyOpdc0OAqlgd7aA5Q7cQp5xx3E65puo/dmGdLS', 'bagola@bagola.es', 'Invitado', '1'),
('beratede', '$2y$10$Go4LJ4UKFl1w6AljMs1VDO4XHs06NEWaCFBivO7rtvGhBM1naeHaW', 'beratede@beratede.es', 'Invitado', '1'),
('cacimi', '$2y$10$Eo.ueQE.auQBQYiNYQYgauDasv8wpykShwOJR.7z44m5YEx8VXUgK', 'cacimi@cacimi.es', 'Usuario', '1'),
('invitado', '$2y$10$OVswwoKOz9IKIhappIe0yuOVqfZkxw5gnqMZEdROKPbXQoihsmKb2', 'invitado@invitado.es', 'Invitado', '1'),
('metaci', '$2y$10$aMG9cz2rUa3meTwiJZeuXebsL8It/tAZ97a3CFL2Fr2zAlk30LPdC', 'metaci@metaci.es', 'Invitado', '1'),
('usuario', '$2y$10$wWnxr0nYwgScFETYfCBDxenledUxqvwVFsr0AlBPd5qN2DlqXgUhy', 'usuario@usuario.es', 'Usuario', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `validaciones`
--

CREATE TABLE IF NOT EXISTS `validaciones` (
  `id_validacion` int(11) NOT NULL AUTO_INCREMENT,
  `validado` tinyint(1) NOT NULL,
  `n_usuario` varchar(30) NOT NULL,
  PRIMARY KEY (`id_validacion`),
  UNIQUE KEY `n_usuario` (`n_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `validaciones`
--

INSERT INTO `validaciones` (`id_validacion`, `validado`, `n_usuario`) VALUES
(2, 1, 'anfico'),
(4, 1, 'usuario'),
(5, 1, 'cacimi'),
(9, 0, 'bagola');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `datos_usuario`
--
ALTER TABLE `datos_usuario`
  ADD CONSTRAINT `datos_usuario_ibfk_1` FOREIGN KEY (`n_usuario`) REFERENCES `usuarios` (`n_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `fondos`
--
ALTER TABLE `fondos`
  ADD CONSTRAINT `fondos_ibfk_1` FOREIGN KEY (`n_usuario`) REFERENCES `usuarios` (`n_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `monedas_cartera`
--
ALTER TABLE `monedas_cartera`
  ADD CONSTRAINT `monedas_cartera_ibfk_1` FOREIGN KEY (`n_usuario`) REFERENCES `usuarios` (`n_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `monedas_cartera_ibfk_2` FOREIGN KEY (`id_moneda`) REFERENCES `monedas_seguimiento` (`id_moneda`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `monedas_seguimiento`
--
ALTER TABLE `monedas_seguimiento`
  ADD CONSTRAINT `monedas_seguimiento_ibfk_2` FOREIGN KEY (`n_usuario`) REFERENCES `usuarios` (`n_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `monedas_seguimiento_ibfk_3` FOREIGN KEY (`id_moneda`) REFERENCES `monedas` (`id_moneda`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `registro_fondo`
--
ALTER TABLE `registro_fondo`
  ADD CONSTRAINT `registro_fondo_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`n_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `validaciones`
--
ALTER TABLE `validaciones`
  ADD CONSTRAINT `validaciones_ibfk_1` FOREIGN KEY (`n_usuario`) REFERENCES `usuarios` (`n_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
