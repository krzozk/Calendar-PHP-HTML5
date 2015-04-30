-- phpMyAdmin SQL Dump
-- version 4.3.0-beta1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 30-04-2015 a las 17:26:09
-- Versión del servidor: 5.6.22-log
-- Versión de PHP: 5.6.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de datos: `calendariocursos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

CREATE TABLE IF NOT EXISTS `cursos` (
`id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `creado` date NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `cursos`
--

INSERT INTO `cursos` (`id`, `nombre`, `creado`) VALUES
(1, 'Fundamentos de ITIL', '2015-04-30'),
(2, 'COBIT', '2015-04-30'),
(3, 'Preparación PMP', '2015-04-29'),
(4, 'Fundamentos de COBIT', '2015-04-29'),
(5, 'SCRUM Master', '2015-04-29'),
(6, 'Fundamentos ISO 27002', '2015-04-29'),
(7, 'Fundamentos ISO 20000', '2015-04-29'),
(8, 'ISRM ISO 27005', '2015-04-29'),
(9, 'Fundamentos ISO 22301', '2015-04-29'),
(10, 'Fundamentos de ITIL (virtual)', '2015-04-29'),
(11, 'Fundamentos de COBIT (virtual)', '2015-04-29'),
(13, 'Fundamentos Cloud Computing', '2015-04-30');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cursos`
--
ALTER TABLE `cursos`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cursos`
--
ALTER TABLE `cursos`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;