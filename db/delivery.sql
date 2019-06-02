-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 02-06-2019 a las 01:44:34
-- Versión del servidor: 5.7.26-0ubuntu0.16.04.1
-- Versión de PHP: 7.2.14-1+ubuntu16.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `delivery`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `company_profile`
--

CREATE TABLE `company_profile` (
  `id` int(11) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `cover_page` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `latitude` varchar(255) NOT NULL,
  `longitude` varchar(255) NOT NULL,
  `address` varchar(45) NOT NULL,
  `company_email` varchar(100) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `primary_phone` varchar(100) NOT NULL,
  `secondary_phone` varchar(100) NOT NULL,
  `enable_update` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `company_profile`
--

INSERT INTO `company_profile` (`id`, `logo`, `cover_page`, `path`, `company_name`, `latitude`, `longitude`, `address`, `company_email`, `user_email`, `primary_phone`, `secondary_phone`, `enable_update`) VALUES
(1, '', '', '', 'Soluciones Informaticas Barinas C.A.\r\n', '', '', '', '', 'barinas.code@gmail.com', '', '', 0),
(2, '', '', '', 'Centro Tecnologico Barinas', '', '', '', '', 'barinas.code@gmail.com', '', '', 0),
(15, '', '', '', 'my company', '8793', '9694', 'address_77901', 'company_emamil53001@email.co', 'user_email80871@email.com', '4179', '6526', 0),
(16, 'xwjxw6otegcyhvducopi.png', 'f2hl7gru5gl1skfijtpm.png', '', 'my company', '8793', '9694', 'address_77901', 'company_emamil53001@email.co', 'user_email80871@email.com', '4179', '6526', 0),
(17, 'jxglgyupbtxuwcoplrtc.png', 'ef8oyh7793lhwons2hjl.png', '', 'my company', '1131', '6039', 'address_55151', 'company_email63561@email.com', 'user_email2491@email.com', '9186', '1878', 0),
(18, 'dyrv0zz5q6mts8issmi2.png', 'f65cllex7sdtyu1d64lt.png', '', 'my company', '1131', '6039', 'address_55151', 'company_email63561@email.com', 'user_email2491@email.com', '9186', '1878', 0),
(19, '', '', '', 'my company', '8849', '8328', 'address_52541', 'company_email24621@email.com', 'user_email33261@email.com', '4638', '1346', 0),
(20, '', '', '', 'my company', '8849', '8328', 'address_52541', 'company_email24621@email.com', 'user_email33261@email.com', '4638', '1346', 0),
(21, '', '', '', 'my company', '8849', '8328', 'address_52541', 'company_email24621@email.com', 'barinas.code@gmail.com', '4638', '1346', 0),
(22, '', '', '', 'Flutter company', '8849', '8328', 'address_52541', 'company_email24621@email.com', 'barinas.code@gmail.com', '4638', '1346', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `company_users`
--

CREATE TABLE `company_users` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `company_profile_id` int(11) NOT NULL,
  `user_type` int(11) NOT NULL,
  `enable_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `company_users`
--

INSERT INTO `company_users` (`id`, `user_id`, `company_profile_id`, `user_type`, `enable_user`) VALUES
(1, 4, 1, 2, 1),
(2, 4, 2, 1, 1),
(3, 4, 2, 2, 1),
(4, 5, 18, 1, 1),
(5, 6, 19, 1, 1),
(6, 6, 20, 1, 1),
(7, 4, 21, 1, 1),
(8, 4, 22, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2019_02_18_000000_create_users_table', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `price` float NOT NULL,
  `enable` int(11) NOT NULL,
  `company_profile_id` int(11) NOT NULL,
  `tax` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `products`
--

INSERT INTO `products` (`id`, `product_name`, `price`, `enable`, `company_profile_id`, `tax`) VALUES
(3, 'cosas', 2000, 1, 2, 10),
(4, 'Random product 0.061667545657486444', 1, 0, 2, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `products_gallery`
--

CREATE TABLE `products_gallery` (
  `id` int(11) NOT NULL,
  `products_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `products_gallery`
--

INSERT INTO `products_gallery` (`id`, `products_id`, `image`) VALUES
(1, 3, 'nzsrfaamanlj1swjksqc.png'),
(2, 4, 'dutvztsmt6nmclh05bid.png'),
(3, 4, 'ceuhqjkhjkhtorsxhbq0.png'),
(4, 4, 'gc4ocybturw3dtaf8nzc.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lastName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email_token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `securityAnswer` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `securityQuestion` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `verified` tinyint(4) DEFAULT NULL,
  `userType` int(11) DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `api_token` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `birthDateMonth` int(11) NOT NULL,
  `birthDateDay` int(11) NOT NULL,
  `birthDateYear` int(11) NOT NULL,
  `originCountry` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `actualCountry` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nationality` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `otherNationality` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `primaryPhone` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `secondaryPhone` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pathAvatar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `passport` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `identificationCard` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sector` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `province` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `profilePhoto` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `preferred_role` int(11) DEFAULT NULL,
  `preferred_production` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `lastName`, `email`, `email_token`, `password`, `securityAnswer`, `securityQuestion`, `verified`, `userType`, `remember_token`, `api_token`, `birthDateMonth`, `birthDateDay`, `birthDateYear`, `originCountry`, `actualCountry`, `nationality`, `otherNationality`, `primaryPhone`, `secondaryPhone`, `avatar`, `pathAvatar`, `passport`, `identificationCard`, `address`, `sector`, `city`, `province`, `profilePhoto`, `preferred_role`, `preferred_production`, `created_at`, `updated_at`) VALUES
(1, 'carlos', 'silva', 'elmorochez22@gmail.com', '0OkarEFFXHNEi4YkW9HWAMrRc5dQj5t7xQPso1NT2DtMLV7gxOII2DJU1jJP', '$2y$10$Gd30cDaJPp/fiY16nD3hgeULI9DdAXXonPz2Q98axajWd5RhCdFlO', 'osito', 'primera mascota', 0, 0, NULL, 'fAP5cYZQkHOpyCLDL7Jzecn1hcHCwhzbNRWrJszBbG8ho5t1rwX9wqeV5hoa', 0, 0, 0, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, '2019-05-28 03:31:00', '2019-05-28 03:31:00'),
(4, 'Leonardo Alfonso Tapia Delgado', 'Leonardo Alfonso Tapia Delgado', 'barinas.code@gmail.com', 'mRMuj4kyYAMQ2lEWZWbwI3v0AStQa1vjdnAPbZRQti5LwBU2h4WzJl7YHVU7', '', '', '', 1, 2, NULL, '9rxUZNCnqyk6WeVez4LkT4RJF4Yw8QW1uI4vHQKIcyIhhivhKdJkyPDxRlQh', 0, 0, 0, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, '2019-05-29 06:31:57', '2019-05-29 06:31:57'),
(5, '', '', 'user_email2491@email.com', 'Zu0ZYcQgYW9eYzBQZArfndH8inBhF9w6v7ibbqx9ZJ7oKwoOygSIU0V6zuVn', '$2y$10$pnhsjfvirKGiIXsSAtCREuyh/ObTbBG3a57YdwemHasGOVNBNAD0u', '', '', 0, 2, NULL, 'SjN5H4JPr1lxEAxRdiEmOsXHZm3FjkEIOrIObuU1uxOjEKrFppsllao50dOQ', 0, 0, 0, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, '2019-06-02 05:29:29', '2019-06-02 05:29:29'),
(6, '', '', 'user_email33261@email.com', 'pFK0stTYlUaxZtPKph4jjL2naiZf8e3SOvCxNQGugxQTdyBBUh0SuEJRd4Bo', '$2y$10$BPQYvxiNCmD.0oNgFk9Yr..bMB7kX3O8xBjbwwqIysoKSuWhqy0Mm', '', '', 0, 2, NULL, '9vl9CqLJcl1vQB8Dt38a7htf3jVhc88g7QMRc2wNea4qTYiRt6XJMPTR7Yi7', 0, 0, 0, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, '2019-06-02 05:30:53', '2019-06-02 05:30:53');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `company_profile`
--
ALTER TABLE `company_profile`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `company_users`
--
ALTER TABLE `company_users`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `products_gallery`
--
ALTER TABLE `products_gallery`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `company_profile`
--
ALTER TABLE `company_profile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT de la tabla `company_users`
--
ALTER TABLE `company_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `products_gallery`
--
ALTER TABLE `products_gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
