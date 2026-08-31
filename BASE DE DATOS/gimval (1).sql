-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 31-08-2026 a las 05:16:30
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

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `AL_01` (IN `rut_alumna` VARCHAR(12))   BEGIN
    SELECT r.id_reserva AS id_reserva, b.fecha AS fecha, b.hora_inicio AS hora_inicio, p.nombre AS nombre, r.vigente  
    FROM reserva as r 
    JOIN bloque_horario as b ON r.id_bloque = b.id_bloque
    JOIN profesor as p ON b.rut_profesor = p.rut
    WHERE rut_alumna = rut_alumna AND r.vigente = 1 AND NOW() < b.hora_inicio
    ORDER BY b.fecha ASC, b.hora_inicio ASC
    LIMIT 1;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `AL_02` ()   BEGIN
    SELECT b.id_bloque AS id_bloque, b.fecha AS fecha, b.hora_inicio AS hora_inicio, p.nombre AS nombre, b.cupos_maximos AS cupos
FROM bloque_horario AS b
JOIN profesor AS p ON b.rut_profesor = p.rut
WHERE b.vigente = 1
ORDER BY b.fecha ASC, b.hora_inicio ASC
LIMIT 3;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administrador`
--

CREATE TABLE `administrador` (
  `rut` varchar(12) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(60) NOT NULL,
  `apellido` varchar(60) NOT NULL,
  `cargo` varchar(60) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administrador`
--

INSERT INTO `administrador` (`rut`, `id_usuario`, `nombre`, `apellido`, `cargo`, `activo`) VALUES
('11111111-1', 1, 'Camila', 'Rojas', 'Gerente general', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumna`
--

CREATE TABLE `alumna` (
  `rut` varchar(12) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(60) NOT NULL,
  `apellido` varchar(60) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `fecha_registro` date NOT NULL DEFAULT curdate(),
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `alumna`
--

INSERT INTO `alumna` (`rut`, `id_usuario`, `nombre`, `apellido`, `fecha_nacimiento`, `telefono`, `fecha_registro`, `activo`) VALUES
('44444444-4', 4, 'Javiera', 'Fernández', '1998-05-12', '+56911111111', '2025-01-10', 1),
('55555555-5', 5, 'Antonia', 'López', '1995-09-23', '+56922222222', '2025-02-20', 1),
('66666666-6', 6, 'Fernanda', 'Castro', '2000-11-02', '+56933333333', '2025-03-05', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bloque_horario`
--

CREATE TABLE `bloque_horario` (
  `id_bloque` int(11) NOT NULL,
  `rut_profesor` varchar(12) NOT NULL,
  `fecha` date NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_termino` time NOT NULL,
  `cupos_maximos` int(11) NOT NULL,
  `vigente` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `bloque_horario`
--

INSERT INTO `bloque_horario` (`id_bloque`, `rut_profesor`, `fecha`, `hora_inicio`, `hora_termino`, `cupos_maximos`, `vigente`) VALUES
(1, '22222222-2', '2026-09-05', '09:00:00', '10:00:00', 15, 1),
(2, '33333333-3', '2026-09-06', '22:00:00', '19:00:00', 10, 1),
(3, '22222222-2', '2026-08-20', '09:00:00', '10:00:00', 15, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `consentimiento`
--

CREATE TABLE `consentimiento` (
  `id_consentimiento` int(11) NOT NULL,
  `rut_alumna` varchar(12) NOT NULL,
  `tipo_dato` varchar(60) NOT NULL,
  `fecha_otorgamiento` datetime NOT NULL,
  `fecha_revocacion` datetime DEFAULT NULL,
  `vigente` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `consentimiento`
--

INSERT INTO `consentimiento` (`id_consentimiento`, `rut_alumna`, `tipo_dato`, `fecha_otorgamiento`, `fecha_revocacion`, `vigente`) VALUES
(1, '44444444-4', 'datos_fisicos_salud', '2025-01-10 10:00:00', NULL, 1),
(2, '55555555-5', 'datos_fisicos_salud', '2025-02-20 11:30:00', NULL, 1),
(3, '66666666-6', 'datos_fisicos_salud', '2025-03-05 09:15:00', '2026-06-01 00:00:00', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `convenio`
--

CREATE TABLE `convenio` (
  `id_convenio` int(11) NOT NULL,
  `nombre_comercio` varchar(250) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `codigo_promocional` varchar(30) NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `convenio`
--

INSERT INTO `convenio` (`id_convenio`, `nombre_comercio`, `descripcion`, `codigo_promocional`, `estado`) VALUES
(1, 'Farmacia VidaSana', 'Descuento en suplementos y productos deportivos.', 'GIMVAL10', 1),
(2, 'Tienda deportiva RunFit', 'Descuento en ropa e implementos deportivos.', 'GIMVAL15OFF', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `datos_fisicos_alumna`
--

CREATE TABLE `datos_fisicos_alumna` (
  `id_dato_fisico` int(11) NOT NULL,
  `rut_alumna` varchar(12) NOT NULL,
  `peso_kg` decimal(5,2) DEFAULT NULL,
  `altura_cm` decimal(5,2) DEFAULT NULL,
  `fecha_registro` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `datos_fisicos_alumna`
--

INSERT INTO `datos_fisicos_alumna` (`id_dato_fisico`, `rut_alumna`, `peso_kg`, `altura_cm`, `fecha_registro`) VALUES
(1, '44444444-4', 62.50, 165.00, '2026-08-01'),
(2, '55555555-5', 58.30, 160.00, '2026-07-15');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_rutina`
--

CREATE TABLE `detalle_rutina` (
  `id_detalle_rutina` int(11) NOT NULL,
  `id_rutina` int(11) NOT NULL,
  `id_ejercicio` int(11) NOT NULL,
  `orden` int(11) NOT NULL DEFAULT 1,
  `series` int(11) NOT NULL,
  `repeticiones` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_rutina`
--

INSERT INTO `detalle_rutina` (`id_detalle_rutina`, `id_rutina`, `id_ejercicio`, `orden`, `series`, `repeticiones`) VALUES
(1, 1, 1, 1, 4, 12),
(2, 1, 2, 2, 3, 10),
(3, 1, 3, 3, 3, 30),
(4, 2, 4, 1, 3, 15);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ejercicio`
--

CREATE TABLE `ejercicio` (
  `id_ejercicio` int(11) NOT NULL,
  `nombre_ejercicio` varchar(120) NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ejercicio`
--

INSERT INTO `ejercicio` (`id_ejercicio`, `nombre_ejercicio`, `descripcion`) VALUES
(1, 'Sentadilla', 'Ejercicio compuesto para tren inferior, foco en cuádriceps y glúteos.'),
(2, 'Press banca', 'Ejercicio de empuje horizontal para pectorales, hombros y tríceps.'),
(3, 'Plancha', 'Ejercicio isométrico de estabilidad para el core.'),
(4, 'Zancadas', 'Ejercicio unilateral para tren inferior y equilibrio.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_plan`
--

CREATE TABLE `estado_plan` (
  `id_estado_plan` int(11) NOT NULL,
  `nombre_estado` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado_plan`
--

INSERT INTO `estado_plan` (`id_estado_plan`, `nombre_estado`) VALUES
(1, 'activo'),
(3, 'suspendido'),
(2, 'vencido');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `plan`
--

CREATE TABLE `plan` (
  `id_plan` int(11) NOT NULL,
  `nombre_plan` varchar(120) NOT NULL,
  `cantidad_clases` int(11) NOT NULL,
  `precio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `plan`
--

INSERT INTO `plan` (`id_plan`, `nombre_plan`, `cantidad_clases`, `precio`) VALUES
(1, 'Plan mensual 8 clases', 8, 25000),
(2, 'Plan mensual ilimitado', 999, 45000);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `plan_alumna`
--

CREATE TABLE `plan_alumna` (
  `id_plan_alumna` int(11) NOT NULL,
  `rut_alumna` varchar(12) NOT NULL,
  `id_plan` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_termino` date NOT NULL,
  `clases_restantes` int(11) NOT NULL,
  `id_estado_plan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `plan_alumna`
--

INSERT INTO `plan_alumna` (`id_plan_alumna`, `rut_alumna`, `id_plan`, `fecha_inicio`, `fecha_termino`, `clases_restantes`, `id_estado_plan`) VALUES
(1, '44444444-4', 2, '2026-08-15', '2026-09-15', 20, 1),
(2, '55555555-5', 1, '2026-07-01', '2026-07-31', 0, 2),
(3, '66666666-6', 1, '2026-08-20', '2026-09-20', 6, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesor`
--

CREATE TABLE `profesor` (
  `rut` varchar(12) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(60) NOT NULL,
  `apellido` varchar(60) NOT NULL,
  `especialidad` varchar(100) DEFAULT NULL,
  `fecha_contratacion` date DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `profesor`
--

INSERT INTO `profesor` (`rut`, `id_usuario`, `nombre`, `apellido`, `especialidad`, `fecha_contratacion`, `activo`) VALUES
('22222222-2', 2, 'Francisco', 'Muñoz', 'Musculación', '2023-03-01', 1),
('33333333-3', 3, 'Valentina', 'Soto', 'Pilates', '2024-01-15', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `progreso_alumna`
--

CREATE TABLE `progreso_alumna` (
  `id_progreso` int(11) NOT NULL,
  `rut_alumna` varchar(12) NOT NULL,
  `id_ejercicio` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `peso_kg` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `progreso_alumna`
--

INSERT INTO `progreso_alumna` (`id_progreso`, `rut_alumna`, `id_ejercicio`, `fecha`, `peso_kg`) VALUES
(1, '44444444-4', 1, '2026-08-20', 40.00),
(2, '44444444-4', 2, '2026-08-20', 25.00),
(3, '55555555-5', 1, '2026-08-15', 30.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reserva`
--

CREATE TABLE `reserva` (
  `id_reserva` int(11) NOT NULL,
  `id_bloque` int(11) NOT NULL,
  `rut_alumna` varchar(12) NOT NULL,
  `fecha_reserva` timestamp NOT NULL DEFAULT current_timestamp(),
  `asistencia` tinyint(1) NOT NULL DEFAULT 0,
  `vigente` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reserva`
--

INSERT INTO `reserva` (`id_reserva`, `id_bloque`, `rut_alumna`, `fecha_reserva`, `asistencia`, `vigente`) VALUES
(1, 1, '44444444-4', '2026-08-25 16:00:00', 0, 1),
(2, 2, '44444444-4', '2026-08-26 19:30:00', 0, 1),
(3, 3, '55555555-5', '2026-08-15 13:00:00', 1, 0),
(4, 1, '66666666-6', '2026-08-27 12:00:00', 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id_rol` int(11) NOT NULL,
  `nombre_rol` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id_rol`, `nombre_rol`) VALUES
(3, 'administrador'),
(1, 'alumna'),
(2, 'profesor');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rutina`
--

CREATE TABLE `rutina` (
  `id_rutina` int(11) NOT NULL,
  `id_bloque` int(11) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `vigente` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rutina`
--

INSERT INTO `rutina` (`id_rutina`, `id_bloque`, `fecha`, `vigente`) VALUES
(1, 1, '2026-09-05 09:00:00', 1),
(2, 3, '2026-08-20 09:00:00', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `email` varchar(120) NOT NULL,
  `contrasena_hash` varchar(255) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `fecha_creacion` datetime NOT NULL DEFAULT current_timestamp(),
  `ultimo_acceso` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `email`, `contrasena_hash`, `id_rol`, `activo`, `fecha_creacion`, `ultimo_acceso`) VALUES
(1, 'admin@gimval.cl', '$2b$12$dummyhash.admin000000000000000000000000000', 3, 1, '2026-08-30 21:09:37', NULL),
(2, 'fmunoz@gimval.cl', '$2b$12$dummyhash.profesor10000000000000000000000', 2, 1, '2026-08-30 21:09:37', NULL),
(3, 'vsoto@gimval.cl', '$2b$12$dummyhash.profesor20000000000000000000000', 2, 1, '2026-08-30 21:09:37', NULL),
(4, 'jfernandez@gimval.cl', '$2b$12$dummyhash.alumna1000000000000000000000000', 1, 1, '2026-08-30 21:09:37', NULL),
(5, 'alopez@gimval.cl', '$2b$12$dummyhash.alumna2000000000000000000000000', 1, 1, '2026-08-30 21:09:37', NULL),
(6, 'fcastro@gimval.cl', '$2b$12$dummyhash.alumna3000000000000000000000000', 1, 1, '2026-08-30 21:09:37', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administrador`
--
ALTER TABLE `administrador`
  ADD PRIMARY KEY (`rut`),
  ADD UNIQUE KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `alumna`
--
ALTER TABLE `alumna`
  ADD PRIMARY KEY (`rut`),
  ADD UNIQUE KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `bloque_horario`
--
ALTER TABLE `bloque_horario`
  ADD PRIMARY KEY (`id_bloque`),
  ADD KEY `fk_bloque_profesor` (`rut_profesor`);

--
-- Indices de la tabla `consentimiento`
--
ALTER TABLE `consentimiento`
  ADD PRIMARY KEY (`id_consentimiento`),
  ADD KEY `fk_consentimiento_alumna` (`rut_alumna`);

--
-- Indices de la tabla `convenio`
--
ALTER TABLE `convenio`
  ADD PRIMARY KEY (`id_convenio`),
  ADD UNIQUE KEY `codigo_promocional` (`codigo_promocional`);

--
-- Indices de la tabla `datos_fisicos_alumna`
--
ALTER TABLE `datos_fisicos_alumna`
  ADD PRIMARY KEY (`id_dato_fisico`),
  ADD KEY `fk_datosfisicos_alumna` (`rut_alumna`);

--
-- Indices de la tabla `detalle_rutina`
--
ALTER TABLE `detalle_rutina`
  ADD PRIMARY KEY (`id_detalle_rutina`),
  ADD KEY `fk_detalle_rutina` (`id_rutina`),
  ADD KEY `fk_detalle_ejercicio` (`id_ejercicio`);

--
-- Indices de la tabla `ejercicio`
--
ALTER TABLE `ejercicio`
  ADD PRIMARY KEY (`id_ejercicio`);

--
-- Indices de la tabla `estado_plan`
--
ALTER TABLE `estado_plan`
  ADD PRIMARY KEY (`id_estado_plan`),
  ADD UNIQUE KEY `nombre_estado` (`nombre_estado`);

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
  ADD KEY `fk_planalumna_alumna` (`rut_alumna`),
  ADD KEY `fk_planalumna_plan` (`id_plan`),
  ADD KEY `fk_planalumna_estado` (`id_estado_plan`);

--
-- Indices de la tabla `profesor`
--
ALTER TABLE `profesor`
  ADD PRIMARY KEY (`rut`),
  ADD UNIQUE KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `progreso_alumna`
--
ALTER TABLE `progreso_alumna`
  ADD PRIMARY KEY (`id_progreso`),
  ADD KEY `fk_progreso_alumna` (`rut_alumna`),
  ADD KEY `fk_progreso_ejercicio` (`id_ejercicio`);

--
-- Indices de la tabla `reserva`
--
ALTER TABLE `reserva`
  ADD PRIMARY KEY (`id_reserva`),
  ADD KEY `fk_reserva_bloque` (`id_bloque`),
  ADD KEY `fk_reserva_alumna` (`rut_alumna`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id_rol`),
  ADD UNIQUE KEY `nombre_rol` (`nombre_rol`);

--
-- Indices de la tabla `rutina`
--
ALTER TABLE `rutina`
  ADD PRIMARY KEY (`id_rutina`),
  ADD KEY `fk_rutina_bloque` (`id_bloque`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_usuario_rol` (`id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `bloque_horario`
--
ALTER TABLE `bloque_horario`
  MODIFY `id_bloque` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `consentimiento`
--
ALTER TABLE `consentimiento`
  MODIFY `id_consentimiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `convenio`
--
ALTER TABLE `convenio`
  MODIFY `id_convenio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `datos_fisicos_alumna`
--
ALTER TABLE `datos_fisicos_alumna`
  MODIFY `id_dato_fisico` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `detalle_rutina`
--
ALTER TABLE `detalle_rutina`
  MODIFY `id_detalle_rutina` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `ejercicio`
--
ALTER TABLE `ejercicio`
  MODIFY `id_ejercicio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `estado_plan`
--
ALTER TABLE `estado_plan`
  MODIFY `id_estado_plan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `plan`
--
ALTER TABLE `plan`
  MODIFY `id_plan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `plan_alumna`
--
ALTER TABLE `plan_alumna`
  MODIFY `id_plan_alumna` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `progreso_alumna`
--
ALTER TABLE `progreso_alumna`
  MODIFY `id_progreso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `reserva`
--
ALTER TABLE `reserva`
  MODIFY `id_reserva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `rutina`
--
ALTER TABLE `rutina`
  MODIFY `id_rutina` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `administrador`
--
ALTER TABLE `administrador`
  ADD CONSTRAINT `fk_admin_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);

--
-- Filtros para la tabla `alumna`
--
ALTER TABLE `alumna`
  ADD CONSTRAINT `fk_alumna_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);

--
-- Filtros para la tabla `bloque_horario`
--
ALTER TABLE `bloque_horario`
  ADD CONSTRAINT `fk_bloque_profesor` FOREIGN KEY (`rut_profesor`) REFERENCES `profesor` (`rut`);

--
-- Filtros para la tabla `consentimiento`
--
ALTER TABLE `consentimiento`
  ADD CONSTRAINT `fk_consentimiento_alumna` FOREIGN KEY (`rut_alumna`) REFERENCES `alumna` (`rut`);

--
-- Filtros para la tabla `datos_fisicos_alumna`
--
ALTER TABLE `datos_fisicos_alumna`
  ADD CONSTRAINT `fk_datosfisicos_alumna` FOREIGN KEY (`rut_alumna`) REFERENCES `alumna` (`rut`);

--
-- Filtros para la tabla `detalle_rutina`
--
ALTER TABLE `detalle_rutina`
  ADD CONSTRAINT `fk_detalle_ejercicio` FOREIGN KEY (`id_ejercicio`) REFERENCES `ejercicio` (`id_ejercicio`),
  ADD CONSTRAINT `fk_detalle_rutina` FOREIGN KEY (`id_rutina`) REFERENCES `rutina` (`id_rutina`);

--
-- Filtros para la tabla `plan_alumna`
--
ALTER TABLE `plan_alumna`
  ADD CONSTRAINT `fk_planalumna_alumna` FOREIGN KEY (`rut_alumna`) REFERENCES `alumna` (`rut`),
  ADD CONSTRAINT `fk_planalumna_estado` FOREIGN KEY (`id_estado_plan`) REFERENCES `estado_plan` (`id_estado_plan`),
  ADD CONSTRAINT `fk_planalumna_plan` FOREIGN KEY (`id_plan`) REFERENCES `plan` (`id_plan`);

--
-- Filtros para la tabla `profesor`
--
ALTER TABLE `profesor`
  ADD CONSTRAINT `fk_profesor_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);

--
-- Filtros para la tabla `progreso_alumna`
--
ALTER TABLE `progreso_alumna`
  ADD CONSTRAINT `fk_progreso_alumna` FOREIGN KEY (`rut_alumna`) REFERENCES `alumna` (`rut`),
  ADD CONSTRAINT `fk_progreso_ejercicio` FOREIGN KEY (`id_ejercicio`) REFERENCES `ejercicio` (`id_ejercicio`);

--
-- Filtros para la tabla `reserva`
--
ALTER TABLE `reserva`
  ADD CONSTRAINT `fk_reserva_alumna` FOREIGN KEY (`rut_alumna`) REFERENCES `alumna` (`rut`),
  ADD CONSTRAINT `fk_reserva_bloque` FOREIGN KEY (`id_bloque`) REFERENCES `bloque_horario` (`id_bloque`);

--
-- Filtros para la tabla `rutina`
--
ALTER TABLE `rutina`
  ADD CONSTRAINT `fk_rutina_bloque` FOREIGN KEY (`id_bloque`) REFERENCES `bloque_horario` (`id_bloque`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `fk_usuario_rol` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
