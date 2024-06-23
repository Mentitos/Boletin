-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 23-06-2024 a las 22:51:27
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `school_report_card`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `classes`
--

CREATE TABLE `classes` (
  `id` int(11) NOT NULL,
  `class_code` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `classes`
--

INSERT INTO `classes` (`id`, `class_code`) VALUES
(1, '1-1'),
(2, '1-2'),
(10, '1-3'),
(3, '2-1'),
(4, '2-2'),
(9, '3-2'),
(5, '4-2'),
(8, '5-2'),
(6, '5-3'),
(11, '6-1'),
(15, '7-1'),
(16, '7-1b'),
(14, '7-2');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grades`
--

CREATE TABLE `grades` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `grade` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `grades`
--

INSERT INTO `grades` (`id`, `student_id`, `subject_id`, `grade`) VALUES
(2, 1, 1, 7.00),
(3, 1, 1, 10.00),
(4, 1, 1, 8.00),
(5, 1, 2, 7.00),
(6, 1, 2, 7.00),
(7, 1, 2, 8.00),
(8, 2, 3, 9.00),
(9, 2, 1, 7.00),
(11, 7, 2, 10.00),
(12, 7, 3, 10.00),
(13, 8, 6, 10.00),
(14, 8, 6, 9.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `dni` varchar(8) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `students`
--

INSERT INTO `students` (`id`, `user_id`, `name`, `dni`, `class_id`) VALUES
(1, 3, 'Fabiana Cantilo', '42382956', 1),
(2, 4, 'Humberto Tello', '34876453', 2),
(4, 5, 'prueba', '56978465', 3),
(5, 6, 'prueba1', '45098456', 4),
(6, 7, 'prueba2', '87564213', 1),
(7, 8, 'pruebafuego', '56123123', 14),
(8, 9, 'Brisa Burgos', '46278954', 15),
(9, 10, 'Jose Peron', '87765523', 15);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `subjects`
--

INSERT INTO `subjects` (`id`, `name`) VALUES
(1, 'matematica'),
(2, 'lengua'),
(3, 'historia'),
(4, 'geografia'),
(5, 'ingles'),
(6, 'arte');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','teacher','student','parent') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'prueba', '$2y$10$6ynAB54vO8MbA5HPdHRnbeehFw9RUKHcmu3bhslbjlZ9ENIH5HW2q', 'admin'),
(2, 'matias', '$2y$10$q32tvmscxeTRL1yEZJvPLO033tVL0yTTiCHTdLuZA2r.FX4.9Pyny', 'student'),
(3, 'fabia', '$2y$10$CW8o9CyAyJEhrzKfWw4yveNPRPELe9LTOSjty3jNwoH1ZYXTN6ACG', 'student'),
(4, 'humber', '$2y$10$FNaaNLfIuih9hTMBeOYhh.XcRei3daYSOStOBBxyQ1rl/Cm.EfhIK', 'student'),
(5, 'prueba', '', 'admin'),
(6, 'prueba1', '$2y$10$dS1TeqT38fibtD1gfx4MS.1VzrYG3M1FSnQBbyRLjCotBR7NjPc1y', 'admin'),
(7, 'prueba2', '$2y$10$4ak3N54SA5aJWjEu59ngve7vEWud9TeTIpaWkk/wGuJo7A1/K.nRu', 'student'),
(8, 'pruebafuego', '$2y$10$/7Caj2fbLVkPPolZVpvt.upMBRwSwtzzhmXQQ5/nlz6zD8HeBVH3G', 'student'),
(9, 'Briza', '$2y$10$xv0wSMEHnNiNbPknpNwGB./kiOUtKPn6H8OIX5vF/pyhKk9ctZ8ia', 'student'),
(10, 'jose', '$2y$10$p4r610fi7Lzk0lwgeOjVg.IeLMYBiMLVouSofMmbfR8IoXVSieUam', 'student'),
(11, 'luciano', '$2y$10$mARRjPgDfp62Vsg15AxpsedoUTcefTzvb5fdG6I/UKN5EYKggCfci', 'admin');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `class_code` (`class_code`);

--
-- Indices de la tabla `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indices de la tabla `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dni` (`dni`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indices de la tabla `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `classes`
--
ALTER TABLE `classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `grades`
--
ALTER TABLE `grades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `grades`
--
ALTER TABLE `grades`
  ADD CONSTRAINT `grades_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`),
  ADD CONSTRAINT `grades_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`);

--
-- Filtros para la tabla `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `fk_class` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`),
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
