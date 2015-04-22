Calendar PHP/HTML5
==================

Calendario muy básico desarrollado sobre PHP y HTML5, que nos muestra el mes actual con la posibilidad de elegir otro.

Más información
---------------
-- phpMyAdmin SQL Dump
-- version 4.3.0-beta1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 22-04-2015 a las 16:10:57
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `curso_fechas`
--

CREATE TABLE IF NOT EXISTS `curso_fechas` (
`id` int(11) NOT NULL,
  `curso_id` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `color` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `curso_persona`
--

CREATE TABLE IF NOT EXISTS `curso_persona` (
`id` int(11) NOT NULL,
  `curso_fechas_id` int(11) NOT NULL,
  `persona_id` int(11) NOT NULL,
  `participantes` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personas`
--

CREATE TABLE IF NOT EXISTS `personas` (
`id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `apellido_paterno` varchar(255) DEFAULT NULL,
  `apellido_materno` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `personas`
--

INSERT INTO `personas` (`id`, `nombre`, `apellido_paterno`, `apellido_materno`) VALUES
(4, 'Manuel F', 'García', ''),
(5, 'Raúl', 'Aquino', NULL),
(6, 'Nohemi', 'López', NULL),
(7, 'Héctor', 'Villagómez', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cursos`
--
ALTER TABLE `cursos`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `curso_fechas`
--
ALTER TABLE `curso_fechas`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `curso_persona`
--
ALTER TABLE `curso_persona`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `personas`
--
ALTER TABLE `personas`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cursos`
--
ALTER TABLE `cursos`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `curso_fechas`
--
ALTER TABLE `curso_fechas`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `curso_persona`
--
ALTER TABLE `curso_persona`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `personas`
--
ALTER TABLE `personas`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19;
