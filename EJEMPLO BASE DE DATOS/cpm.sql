-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 25-01-2021 a las 14:02:35
-- Versión del servidor: 10.4.13-MariaDB
-- Versión de PHP: 7.4.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `cpm`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ordenadores`
--

CREATE TABLE `ordenadores` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` text NOT NULL,
  `ip` text NOT NULL,
  `ubicacion` text NOT NULL,
  `last_status` text NOT NULL,
  `status_date` text NOT NULL,
  `icono` text NOT NULL,
  `tipo` text NOT NULL,
  `cpu` text NOT NULL,
  `ram` text NOT NULL,
  `disco` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `ordenadores`
--

INSERT INTO `ordenadores` (`id`, `nombre`, `ip`, `ubicacion`, `last_status`, `status_date`, `icono`, `tipo`, `cpu`, `ram`, `disco`) VALUES
(1, 'EE-01', '192.168.151.1', 'Aula 12', 'Conectado', '1611579308', 'fas fa-desktop', 'ordenador', 'i5', '8G', '250 SSD'),
(2, 'FlatCloud', '192.168.151.110', 'Aula 10', 'Conectado', '1611573383', 'fas fa-server', 'servidor', 'Intel(R) Pentium(R) CPU G3240', '16Gb', '250 SSD 500HDD'),
(3, 'Homeassistant', 'brunohouse.duckdns.org', 'Casa', 'Desconectado', '1611567876', 'fab fa-raspberry-pi', 'servidor', 'Raspberry PI 4', '4G', '32SD');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tecnicos`
--

CREATE TABLE `tecnicos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` text NOT NULL,
  `apellidos` text NOT NULL,
  `telefono` text NOT NULL,
  `telegram` text NOT NULL,
  `mail` text NOT NULL,
  `clave` text NOT NULL,
  `user` text NOT NULL,
  `imagen` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tecnicos`
--

INSERT INTO `tecnicos` (`id`, `nombre`, `apellidos`, `telefono`, `telegram`, `mail`, `clave`, `user`, `imagen`) VALUES
(1, 'Abraham', 'Leiro Fernandez', '634384415', '', 'abraham@cpsoftware.es', '$2y$10$/8Y8T4pz0kcCB3j9vFB0VuO7Y4cU10tpOhScYPS7nzr4aDi.ir01m', 'aleiro', ''),
(2, 'ej', 'ej', '0', '0', 'ej@ej.com', '$2y$10$GrtQ.5S47.IT/x5DSMEQSesIxUJFoI9HjOmNSUzLXnyLGYJw9r3ly', 'ej', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ticket`
--

CREATE TABLE `ticket` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `aparato` text NOT NULL,
  `usuario` text NOT NULL,
  `tipo_error` text NOT NULL,
  `descripcion` text NOT NULL,
  `tecnico` text NOT NULL,
  `fecha` text NOT NULL,
  `estado` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `ticket`
--

INSERT INTO `ticket` (`id`, `aparato`, `usuario`, `tipo_error`, `descripcion`, `tecnico`, `fecha`, `estado`) VALUES
(2, '1', 'Profesor de ejemplo', 'No enciende', 'El ordenador no enciende de ninguna manera', '1', '1611577894', 'pendiente');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `ordenadores`
--
ALTER TABLE `ordenadores`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indices de la tabla `tecnicos`
--
ALTER TABLE `tecnicos`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indices de la tabla `ticket`
--
ALTER TABLE `ticket`
  ADD UNIQUE KEY `id` (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `ordenadores`
--
ALTER TABLE `ordenadores`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tecnicos`
--
ALTER TABLE `tecnicos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `ticket`
--
ALTER TABLE `ticket`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
