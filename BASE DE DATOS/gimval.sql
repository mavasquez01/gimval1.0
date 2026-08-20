-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-08-2026 a las 02:25:16
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `gimval`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bloque_horario`
--

CREATE TABLE `bloque_horario` (
  `id_bloque` int(11) NOT NULL,
  `rut_profesor` varchar(12) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `hora_inicio` time DEFAULT NULL,
  `hora_termino` time DEFAULT NULL,
  `cupos_maximos` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `convenio`
--

CREATE TABLE `convenio` (
  `id_convenio` int(11) NOT NULL,
  `nombre_comercio` varchar(250) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `codigo_promocional` varchar(30) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_rutina`
--

CREATE TABLE `detalle_rutina` (
  `id_detalle_rutina` int(11) NOT NULL,
  `id_rutina` int(11) DEFAULT NULL,
  `id_ejercicio` int(11) DEFAULT NULL,
  `series` int(11) DEFAULT NULL,
  `repeticiones` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ejercicio`
--

CREATE TABLE `ejercicio` (
  `id_ejercicio` int(11) NOT NULL,
  `nombre_ejercicio` varchar(120) DEFAULT NULL,
  `descripción` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `plan`
--

CREATE TABLE `plan` (
  `id_plan` int(11) NOT NULL,
  `nombre_plan` varchar(120) DEFAULT NULL,
  `cantidad_clases` int(11) DEFAULT NULL,
  `precio` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `plan_alumna`
--

CREATE TABLE `plan_alumna` (
  `id_plan_alumna` int(11) NOT NULL,
  `rut_alumna` varchar(12) DEFAULT NULL,
  `id_plan` int(11) DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_termino` date DEFAULT NULL,
  `clases_restantes` int(11) DEFAULT NULL,
  `estado` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `progreso_alumna`
--

CREATE TABLE `progreso_alumna` (
  `id_progreso` int(11) NOT NULL,
  `rut_alumna` varchar(12) DEFAULT NULL,
  `id_ejercicio` int(11) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `peso_kg` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reserva`
--

CREATE TABLE `reserva` (
  `id_reserva` int(11) NOT NULL,
  `id_bloque` int(11) DEFAULT NULL,
  `rut_alumna` varchar(12) DEFAULT NULL,
  `fecha_reserva` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `asistencia` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rutina`
--

CREATE TABLE `rutina` (
  `id_rutina` int(11) NOT NULL,
  `id_bloque` int(11) DEFAULT NULL,
  `rut_profesor` varchar(12) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `rut` varchar(12) NOT NULL,
  `nombre` varchar(60) DEFAULT NULL,
  `apellido` varchar(60) DEFAULT NULL,
  `edad` int(11) DEFAULT NULL,
  `correo` varchar(120) DEFAULT NULL,
  `contraseña` varchar(120) DEFAULT NULL,
  `rol` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `bloque_horario`
--
ALTER TABLE `bloque_horario`
  ADD PRIMARY KEY (`id_bloque`),
  ADD KEY `fk_idUsuario` (`rut_profesor`);

--
-- Indices de la tabla `convenio`
--
ALTER TABLE `convenio`
  ADD PRIMARY KEY (`id_convenio`);

--
-- Indices de la tabla `detalle_rutina`
--
ALTER TABLE `detalle_rutina`
  ADD PRIMARY KEY (`id_detalle_rutina`),
  ADD KEY `fk_detalle_idRutina` (`id_rutina`),
  ADD KEY `fk_detalle_idejercicio` (`id_ejercicio`);

--
-- Indices de la tabla `ejercicio`
--
ALTER TABLE `ejercicio`
  ADD PRIMARY KEY (`id_ejercicio`);

--
-- Indices de la tabla `plan`
--
ALTER TABLE `plan`
  ADD PRIMARY KEY (`id_plan`);

--
-- Indices de la tabla `plan_alumna`
--
ALTER TABLE `plan_alumna`
  ADD PRIMARY KEY (`id_plan_alumna`),
  ADD KEY `fk_plan_idUsuario` (`rut_alumna`),
  ADD KEY `fk_plan_idPlan` (`id_plan`);

--
-- Indices de la tabla `progreso_alumna`
--
ALTER TABLE `progreso_alumna`
  ADD PRIMARY KEY (`id_progreso`),
  ADD KEY `fk_progreso_idUsuario` (`rut_alumna`),
  ADD KEY `fk_progreso_idEjercicio` (`id_ejercicio`);

--
-- Indices de la tabla `reserva`
--
ALTER TABLE `reserva`
  ADD PRIMARY KEY (`id_reserva`),
  ADD KEY `fk_idBloque` (`id_bloque`),
  ADD KEY `fk_idRutAlumna` (`rut_alumna`);

--
-- Indices de la tabla `rutina`
--
ALTER TABLE `rutina`
  ADD PRIMARY KEY (`id_rutina`),
  ADD KEY `fk_rutina_idUsuario` (`rut_profesor`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`rut`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `bloque_horario`
--
ALTER TABLE `bloque_horario`
  MODIFY `id_bloque` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `convenio`
--
ALTER TABLE `convenio`
  MODIFY `id_convenio` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_rutina`
--
ALTER TABLE `detalle_rutina`
  MODIFY `id_detalle_rutina` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ejercicio`
--
ALTER TABLE `ejercicio`
  MODIFY `id_ejercicio` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `plan`
--
ALTER TABLE `plan`
  MODIFY `id_plan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `plan_alumna`
--
ALTER TABLE `plan_alumna`
  MODIFY `id_plan_alumna` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `progreso_alumna`
--
ALTER TABLE `progreso_alumna`
  MODIFY `id_progreso` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reserva`
--
ALTER TABLE `reserva`
  MODIFY `id_reserva` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `rutina`
--
ALTER TABLE `rutina`
  MODIFY `id_rutina` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `bloque_horario`
--
ALTER TABLE `bloque_horario`
  ADD CONSTRAINT `fk_idUsuario` FOREIGN KEY (`rut_profesor`) REFERENCES `usuario` (`rut`);

--
-- Filtros para la tabla `detalle_rutina`
--
ALTER TABLE `detalle_rutina`
  ADD CONSTRAINT `fk_detalle_idRutina` FOREIGN KEY (`id_rutina`) REFERENCES `rutina` (`id_rutina`),
  ADD CONSTRAINT `fk_detalle_idejercicio` FOREIGN KEY (`id_ejercicio`) REFERENCES `ejercicio` (`id_ejercicio`);

--
-- Filtros para la tabla `plan_alumna`
--
ALTER TABLE `plan_alumna`
  ADD CONSTRAINT `fk_plan_idPlan` FOREIGN KEY (`id_plan`) REFERENCES `plan` (`id_plan`),
  ADD CONSTRAINT `fk_plan_idUsuario` FOREIGN KEY (`rut_alumna`) REFERENCES `usuario` (`rut`);

--
-- Filtros para la tabla `progreso_alumna`
--
ALTER TABLE `progreso_alumna`
  ADD CONSTRAINT `fk_progreso_idEjercicio` FOREIGN KEY (`id_ejercicio`) REFERENCES `ejercicio` (`id_ejercicio`),
  ADD CONSTRAINT `fk_progreso_idUsuario` FOREIGN KEY (`rut_alumna`) REFERENCES `usuario` (`rut`);

--
-- Filtros para la tabla `reserva`
--
ALTER TABLE `reserva`
  ADD CONSTRAINT `fk_idBloque` FOREIGN KEY (`id_bloque`) REFERENCES `bloque_horario` (`id_bloque`),
  ADD CONSTRAINT `fk_idRutAlumna` FOREIGN KEY (`rut_alumna`) REFERENCES `usuario` (`rut`);

--
-- Filtros para la tabla `rutina`
--
ALTER TABLE `rutina`
  ADD CONSTRAINT `fk_rutina_idUsuario` FOREIGN KEY (`rut_profesor`) REFERENCES `usuario` (`rut`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
