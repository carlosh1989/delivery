-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 13-05-2019 a las 12:42:04
-- Versión del servidor: 5.7.26-0ubuntu0.16.04.1
-- Versión de PHP: 7.2.14-1+ubuntu16.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `rdcasting11`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `costume`
--

CREATE TABLE `costume` (
  `id` int(10) UNSIGNED NOT NULL,
  `users_bussiness_proposal_id` int(10) UNSIGNED NOT NULL,
  `filename` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `features_categories`
--

CREATE TABLE `features_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `featureCategoryName` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `featureCategoryDescription` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `featureGroup` enum('physical','talent') COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Caracteristica fisica o talento',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `features_categories`
--

INSERT INTO `features_categories` (`id`, `featureCategoryName`, `featureCategoryDescription`, `featureGroup`, `created_at`, `updated_at`) VALUES
(2, 'Talentos', 'Loren ipsun dolor site amet', 'talent', '2019-01-16 14:51:36', '2019-01-16 14:51:36'),
(3, 'Caracteristicas Fisicas', 'Loren ipsun dolor site amet', 'physical', '2019-01-16 15:27:48', '2019-01-16 15:27:48');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `features_labels`
--

CREATE TABLE `features_labels` (
  `id` int(10) UNSIGNED NOT NULL,
  `features_categories_id` int(10) UNSIGNED NOT NULL,
  `featureLabel` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `features_labels`
--

INSERT INTO `features_labels` (`id`, `features_categories_id`, `featureLabel`, `created_at`, `updated_at`) VALUES
(83, 3, 'Color de ojos', '2019-01-16 19:02:09', '2019-01-16 22:12:47'),
(84, 3, 'Color del cabello', '2019-01-16 19:02:09', '2019-01-16 19:02:09'),
(85, 3, 'Tipo de cabello', '2019-01-16 19:02:09', '2019-01-16 19:02:09'),
(86, 3, 'Tipo de piel', '2019-01-16 19:02:09', '2019-01-16 19:02:09'),
(91, 2, 'Idiomas', '2019-01-16 22:13:16', '2019-01-16 22:13:16'),
(93, 2, 'Deportes', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `features_proposal`
--

CREATE TABLE `features_proposal` (
  `id` int(10) UNSIGNED NOT NULL,
  `features_values_id` int(10) UNSIGNED NOT NULL,
  `users_bussiness_proposal_id` int(10) UNSIGNED NOT NULL,
  `featureProporsalValue` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `featureProporsalLabel` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `features_values`
--

CREATE TABLE `features_values` (
  `id` int(10) UNSIGNED NOT NULL,
  `features_labels_id` int(10) UNSIGNED NOT NULL,
  `featureValue` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `features_values`
--

INSERT INTO `features_values` (`id`, `features_labels_id`, `featureValue`, `created_at`, `updated_at`) VALUES
(1, 83, 'Marrones', '2019-01-16 19:02:09', '2019-01-16 19:02:09'),
(2, 83, 'Verdes', '2019-01-16 19:02:09', '2019-01-16 19:02:09'),
(3, 83, 'Azules', '2019-01-16 19:02:09', '2019-01-16 19:02:09'),
(4, 83, 'Negros', '2019-01-16 19:02:09', '2019-01-16 19:02:09'),
(5, 83, 'Heterocromia', '2019-01-16 19:02:09', '2019-01-16 19:02:09'),
(6, 83, 'Otro', '2019-01-16 19:02:09', '2019-01-16 19:02:09'),
(7, 84, 'Negro', '2019-01-16 19:02:09', '2019-01-16 19:02:09'),
(8, 84, 'Rubio', '2019-01-16 19:02:09', '2019-01-16 19:02:09'),
(9, 84, 'Castaño oscuro', '2019-01-16 19:02:09', '2019-01-16 19:02:09'),
(10, 84, 'Castaño claro', '2019-01-16 19:02:09', '2019-01-16 19:02:09'),
(11, 84, 'Otro', '2019-01-16 19:02:09', '2019-01-16 19:02:09'),
(12, 85, 'Lizo', '2019-01-16 19:02:09', '2019-01-16 19:02:09'),
(13, 85, 'Ondulado', '2019-01-16 19:02:09', '2019-01-16 19:02:09'),
(14, 85, 'Afro', '2019-01-16 19:02:09', '2019-01-16 19:02:09'),
(15, 85, 'Otro', '2019-01-16 19:02:09', '2019-01-16 19:02:09'),
(16, 86, 'Caucasico', '2019-01-16 19:02:09', '2019-01-16 19:02:09'),
(17, 86, 'Amarillo', '2019-01-16 19:02:09', '2019-01-16 19:02:09'),
(18, 86, 'Moreno', '2019-01-16 19:02:09', '2019-01-16 19:02:09'),
(19, 86, 'Negro', '2019-01-16 19:02:09', '2019-01-16 19:02:09'),
(20, 86, 'Otro', '2019-01-16 19:02:09', '2019-01-16 19:02:09'),
(33, 91, 'Ingles', NULL, NULL),
(34, 91, 'Español', NULL, NULL),
(35, 91, 'Ruso', NULL, NULL),
(36, 91, 'Arabe', NULL, NULL),
(37, 93, 'Basket', NULL, NULL),
(38, 93, 'Futbol', NULL, NULL);

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
(1, '2019_02_18_000000_create_users_table', 1),
(2, '2019_02_18_000001_create_features_categories_table', 1),
(3, '2019_02_18_000002_create_features_labels_table', 1),
(4, '2019_02_18_000003_create_users_bussiness_proposal_table', 1),
(5, '2019_02_18_000004_create_users_videos_table', 1),
(6, '2019_02_18_000005_create_recipients_bussiness_proposal_table', 1),
(7, '2019_02_18_000006_create_features_values_table', 1),
(8, '2019_02_18_000007_create_costume_table', 1),
(9, '2019_02_18_000008_create_proporsal_lines_table', 1),
(10, '2019_02_18_000009_create_features_proposal_table', 1),
(11, '2019_02_18_000010_create_users_features_detail_table', 1),
(12, '2019_03_15_204513_create_users_calendar_table', 1),
(13, '2019_03_20_211952_create_users_gallery_table', 1),
(14, '2019_03_20_212541_create_users_my_moment_table', 1),
(15, '2019_03_25_151553_create_proposal_validation_table', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `my_moment`
--

CREATE TABLE `my_moment` (
  `id` int(10) UNSIGNED NOT NULL,
  `users_id` int(10) UNSIGNED NOT NULL,
  `filename` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profile_gallery`
--

CREATE TABLE `profile_gallery` (
  `id` int(10) UNSIGNED NOT NULL,
  `users_id` int(10) UNSIGNED NOT NULL,
  `filename` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proporsal_lines`
--

CREATE TABLE `proporsal_lines` (
  `id` int(10) UNSIGNED NOT NULL,
  `users_bussiness_proposal_id` int(10) UNSIGNED NOT NULL,
  `filename` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proposal_validation`
--

CREATE TABLE `proposal_validation` (
  `id` int(10) UNSIGNED NOT NULL,
  `users_bussiness_proposal_id` int(10) UNSIGNED NOT NULL,
  `recipient` int(11) NOT NULL,
  `added` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `questions`
--

CREATE TABLE `questions` (
  `id` int(255) NOT NULL,
  `question` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `questions`
--

INSERT INTO `questions` (`id`, `question`) VALUES
(1, 'institución bancaria'),
(2, 'telefónica'),
(3, 'bebidas alcohólicas'),
(4, 'has leido los terminos y condiciones como talento?');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recipients_bussiness_proposal`
--

CREATE TABLE `recipients_bussiness_proposal` (
  `id` int(10) UNSIGNED NOT NULL,
  `recipient` int(10) UNSIGNED NOT NULL COMMENT 'El que recibe la propuesta',
  `users_bussiness_proposal_id` int(10) UNSIGNED NOT NULL,
  `proposal_estatus` int(1) NOT NULL COMMENT '0 en espera, 1 checkout, 2 aprobado por monica, 3 denegado',
  `accepted` int(11) DEFAULT NULL COMMENT 'Propuesta en espera 0, aceptada 1, rechazada 2',
  `renegotiatedPrice` double(8,2) DEFAULT NULL COMMENT 'Precio renegociado',
  `wasSeen` int(11) DEFAULT NULL COMMENT 'Notificacion vista',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
  `birthDate` date DEFAULT NULL,
  `securityAnswer` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `securityQuestion` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `verified` tinyint(4) DEFAULT NULL,
  `userType` int(11) DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `api_token` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `originCountry` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `actualCountry` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nationality` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `otherNationality` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pathAvatar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `passport` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `identificationCard` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sector` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `province` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `profilePhoto` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `preferred_role` int(10) NOT NULL,
  `preferred_production` int(10) NOT NULL,
  `views` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `lastName`, `email`, `email_token`, `password`, `birthDate`, `securityAnswer`, `securityQuestion`, `verified`, `userType`, `remember_token`, `api_token`, `originCountry`, `actualCountry`, `nationality`, `otherNationality`, `avatar`, `pathAvatar`, `passport`, `identificationCard`, `address`, `sector`, `city`, `province`, `profilePhoto`, `preferred_role`, `preferred_production`, `views`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin', 'admin@rdcasting.com', '3Uk69TvNt6aL2uok6Zabi4foHDWF9PIcz50AzNkQGfgOsRFpssjfcFk3nims', '$2y$10$jBN625tvTg7HYtDd5f2lqOmlY3QehmR3/rm7Oqpxh7G7IdNNuXOla', NULL, NULL, NULL, 1, 2, 'EyVlDH3CJmjiBKJflbmFTjJ3GNiKeHLIgwtm8fGIvDc9gAG3TqL3JHyxZpFZ', 'ymnGaw8hSX7T16JojOhcC93SiDpiiVAQj7knLqHY5HW1tzY9IAbnqWuarazy', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', 0, 0, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users_answers`
--

CREATE TABLE `users_answers` (
  `id` int(255) NOT NULL,
  `users_id` int(255) NOT NULL,
  `questions_id` int(255) NOT NULL,
  `answer` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users_bank_accounts`
--

CREATE TABLE `users_bank_accounts` (
  `id` int(255) NOT NULL,
  `users_id` int(255) NOT NULL,
  `owner` varchar(100) NOT NULL,
  `certificate_passport` varchar(200) NOT NULL,
  `bank` varchar(200) NOT NULL,
  `account_number` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users_bussiness_proposal`
--

CREATE TABLE `users_bussiness_proposal` (
  `id` int(10) UNSIGNED NOT NULL,
  `sender` int(10) UNSIGNED NOT NULL COMMENT 'El que crea la propuesta',
  `proposalType` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Titulo de la propuesta',
  `description` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'Descripcion de la propuesta',
  `initialPrice` double(8,2) NOT NULL COMMENT 'Precio inicial de la propuesta',
  `castingType` int(11) DEFAULT NULL,
  `rolType` enum('Extra','Principal','Secundario','One Liner') COLLATE utf8_unicode_ci DEFAULT NULL,
  `filmingDate` date DEFAULT NULL,
  `hoursFilming` int(11) DEFAULT NULL,
  `productionType` enum('Largometraje','Mediometraje','Cortometraje','Comercial','Modelaje: Modelaje','Modelaje: Fotografia','Eventos') COLLATE utf8_unicode_ci DEFAULT NULL,
  `filmingCity` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `filmingAddress` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `filmingCoords` text COLLATE utf8_unicode_ci,
  `proposal_estatus` int(1) NOT NULL COMMENT '0 en espera, 1 checkout, 2 aprobado por monica, 3 denegado',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users_calendar`
--

CREATE TABLE `users_calendar` (
  `id` int(10) UNSIGNED NOT NULL,
  `users_id` int(10) UNSIGNED NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `status` int(11) NOT NULL,
  `users_bussiness_proposal_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users_contacts`
--

CREATE TABLE `users_contacts` (
  `id` int(255) NOT NULL,
  `users_id` int(255) NOT NULL,
  `contact` varchar(100) NOT NULL,
  `whatsapp` int(1) NOT NULL,
  `priority` enum('primary','secondary','optional') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users_curriculum`
--

CREATE TABLE `users_curriculum` (
  `id` int(255) NOT NULL,
  `users_id` int(255) NOT NULL,
  `section` enum('cine','serie','teatro','modelaje') NOT NULL,
  `year` varchar(10) NOT NULL,
  `title` varchar(100) NOT NULL,
  `role` enum('protagonista','secundario','reparto','extra') NOT NULL,
  `director` varchar(100) NOT NULL,
  `productionHouse` varchar(100) NOT NULL,
  `country` enum('Afganistán','Albania','Alemania','Andorra','Angola','Antigua y Barbuda','Arabia Saudita','Argelia','Argentina','Armenia','Australia','Austria','Azerbaiyán','Bahamas','Bangladés','Barbados','Baréin','Bélgica','Belice','Benín','Bielorrusia','Birmania','Bolivia','Bosnia y Herzegovina','Botsuana','Brasil','Brunéi','Bulgaria','Burkina Faso','Burundi','Bután','Cabo Verde','Camboya','Camerún','Canadá','Catar','Chad','Chile','China','Chipre','Ciudad del Vaticano','Colombia','Comoras','Corea del Norte','Corea del Sur','Costa de Marfil','Costa Rica','Croacia','Cuba','Dinamarca','Dominica','Ecuador','Egipto','El Salvador','Emiratos Árabes Unidos','Eritrea','Eslovaquia','Eslovenia','España','Estados Unidos','Estonia','Etiopía','Filipinas','Finlandia','Fiyi','Francia','Gabón','Gambia','Georgia','Ghana','Granada','Grecia','Guatemala','Guyana','Guinea','Guinea ecuatorial','Guinea-Bisáu','Haití','Honduras','Hungría','India','Indonesia','Irak','Irán','Irlanda','Islandia','Islas Marshall','Islas Salomón','Israel','Italia','Jamaica','Japón','Jordania','Kazajistán','Kenia','Kirguistán','Kiribati','Kuwait','Laos','Lesoto','Letonia','Líbano','Liberia','Libia','Liechtenstein','Lituania','Luxemburgo','Madagascar','Malasia','Malaui','Maldivas','Malí','Malta','Marruecos','Mauricio','Mauritania','México','Micronesia','Moldavia','Mónaco','Mongolia','Montenegro','Mozambique','Namibia','Nauru','Nepal','Nicaragua','Níger','Nigeria','Noruega','Nueva Zelanda','Omán','Países Bajos','Pakistán','Palaos','Panamá','Papúa Nueva Guinea','Paraguay','Perú','Polonia','Portugal','Reino Unido','República Centroafricana','República Checa','República de Macedonia','República del Congo','República Democrática del Congo','República Dominicana','República Sudafricana','Ruanda','Rumanía','Rusia','Samoa','San Cristóbal y Nieves','San Marino','San Vicente y las Granadinas','Santa Lucía','Santo Tomé y Príncipe','Senegal','Serbia','Seychelles','Sierra Leona','Singapur','Siria','Somalia','Sri Lanka','Suazilandia','Sudán','Sudán del Sur','Suecia','Suiza','Surinam','Tailandia','Tanzania','Tayikistán','Timor Oriental','Togo','Tonga','Trinidad y Tobago','Túnez','Turkmenistán','Turquía','Tuvalu','Ucrania','Uganda','Uruguay','Uzbekistán','Vanuatu','Venezuela','Vietnam','Yemen','Yibuti','Zambia','Zimbabue') NOT NULL,
  `created_at` varchar(100) NOT NULL,
  `updated_at` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users_features_detail`
--

CREATE TABLE `users_features_detail` (
  `id` int(10) UNSIGNED NOT NULL,
  `users_id` int(10) UNSIGNED NOT NULL,
  `features_values_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users_production`
--

CREATE TABLE `users_production` (
  `id` int(255) NOT NULL,
  `users_id` int(255) NOT NULL,
  `type` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users_videos`
--

CREATE TABLE `users_videos` (
  `id` int(10) UNSIGNED NOT NULL,
  `users_id` int(10) UNSIGNED NOT NULL,
  `url` text COLLATE utf8_unicode_ci NOT NULL,
  `thumbnail` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `costume`
--
ALTER TABLE `costume`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_costume_users_bussiness_proposal1_idx` (`users_bussiness_proposal_id`);

--
-- Indices de la tabla `features_categories`
--
ALTER TABLE `features_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `features_labels`
--
ALTER TABLE `features_labels`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_labels_categories_idx` (`features_categories_id`);

--
-- Indices de la tabla `features_proposal`
--
ALTER TABLE `features_proposal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_features_proposal_users_bussiness_proposal1_idx` (`users_bussiness_proposal_id`),
  ADD KEY `fk_features_proporsal_features_values1_idx` (`features_values_id`);

--
-- Indices de la tabla `features_values`
--
ALTER TABLE `features_values`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_features_values_features_labels1_idx` (`features_labels_id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `my_moment`
--
ALTER TABLE `my_moment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_my_moment_users1_idx` (`users_id`);

--
-- Indices de la tabla `profile_gallery`
--
ALTER TABLE `profile_gallery`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_profile_gallery_users1_idx` (`users_id`);

--
-- Indices de la tabla `proporsal_lines`
--
ALTER TABLE `proporsal_lines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_proporsal_lines_users_bussiness_proposal1_idx` (`users_bussiness_proposal_id`);

--
-- Indices de la tabla `proposal_validation`
--
ALTER TABLE `proposal_validation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proposal_validation_users_bussiness_proposal_id_foreign` (`users_bussiness_proposal_id`);

--
-- Indices de la tabla `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `recipients_bussiness_proposal`
--
ALTER TABLE `recipients_bussiness_proposal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_recipients_bussiness_proporsal_users1_idx` (`recipient`),
  ADD KEY `fk_recipients_bussiness_proposal_users_bussiness_proposal1_idx` (`users_bussiness_proposal_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`);

--
-- Indices de la tabla `users_answers`
--
ALTER TABLE `users_answers`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users_bank_accounts`
--
ALTER TABLE `users_bank_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users_bussiness_proposal`
--
ALTER TABLE `users_bussiness_proposal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_bussiness_proporsal_users1_idx` (`sender`);

--
-- Indices de la tabla `users_calendar`
--
ALTER TABLE `users_calendar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_users_calendar_users1_idx` (`users_id`);

--
-- Indices de la tabla `users_contacts`
--
ALTER TABLE `users_contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users_curriculum`
--
ALTER TABLE `users_curriculum`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users_features_detail`
--
ALTER TABLE `users_features_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_users_features_detail_features_values1_idx` (`features_values_id`),
  ADD KEY `fk_user_features_details_users1_idx` (`users_id`);

--
-- Indices de la tabla `users_videos`
--
ALTER TABLE `users_videos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_users_videos_users1_idx` (`users_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `costume`
--
ALTER TABLE `costume`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `features_categories`
--
ALTER TABLE `features_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `features_labels`
--
ALTER TABLE `features_labels`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;
--
-- AUTO_INCREMENT de la tabla `features_proposal`
--
ALTER TABLE `features_proposal`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `features_values`
--
ALTER TABLE `features_values`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;
--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT de la tabla `my_moment`
--
ALTER TABLE `my_moment`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `profile_gallery`
--
ALTER TABLE `profile_gallery`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT de la tabla `proporsal_lines`
--
ALTER TABLE `proporsal_lines`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT de la tabla `proposal_validation`
--
ALTER TABLE `proposal_validation`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT de la tabla `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `recipients_bussiness_proposal`
--
ALTER TABLE `recipients_bussiness_proposal`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `users_answers`
--
ALTER TABLE `users_answers`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT de la tabla `users_bank_accounts`
--
ALTER TABLE `users_bank_accounts`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT de la tabla `users_bussiness_proposal`
--
ALTER TABLE `users_bussiness_proposal`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `users_calendar`
--
ALTER TABLE `users_calendar`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=193;
--
-- AUTO_INCREMENT de la tabla `users_contacts`
--
ALTER TABLE `users_contacts`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `users_curriculum`
--
ALTER TABLE `users_curriculum`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
--
-- AUTO_INCREMENT de la tabla `users_features_detail`
--
ALTER TABLE `users_features_detail`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `users_videos`
--
ALTER TABLE `users_videos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `costume`
--
ALTER TABLE `costume`
  ADD CONSTRAINT `fk_costume_users_bussiness_proposal1_idx` FOREIGN KEY (`users_bussiness_proposal_id`) REFERENCES `users_bussiness_proposal` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `features_labels`
--
ALTER TABLE `features_labels`
  ADD CONSTRAINT `fk_labels_categories_idx` FOREIGN KEY (`features_categories_id`) REFERENCES `features_categories` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `features_proposal`
--
ALTER TABLE `features_proposal`
  ADD CONSTRAINT `fk_features_proporsal_features_values1_idx` FOREIGN KEY (`features_values_id`) REFERENCES `features_values` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_features_proposal_users_bussiness_proposal1_idx` FOREIGN KEY (`users_bussiness_proposal_id`) REFERENCES `users_bussiness_proposal` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `features_values`
--
ALTER TABLE `features_values`
  ADD CONSTRAINT `fk_features_values_features_labels1_idx` FOREIGN KEY (`features_labels_id`) REFERENCES `features_labels` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `my_moment`
--
ALTER TABLE `my_moment`
  ADD CONSTRAINT `fk_my_moment_users1_idx` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `profile_gallery`
--
ALTER TABLE `profile_gallery`
  ADD CONSTRAINT `fk_profile_gallery_users1_idx` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `proporsal_lines`
--
ALTER TABLE `proporsal_lines`
  ADD CONSTRAINT `fk_proporsal_lines_users_bussiness_proposal1_idx` FOREIGN KEY (`users_bussiness_proposal_id`) REFERENCES `users_bussiness_proposal` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `proposal_validation`
--
ALTER TABLE `proposal_validation`
  ADD CONSTRAINT `proposal_validation_users_bussiness_proposal_id_foreign` FOREIGN KEY (`users_bussiness_proposal_id`) REFERENCES `users_bussiness_proposal` (`id`);

--
-- Filtros para la tabla `recipients_bussiness_proposal`
--
ALTER TABLE `recipients_bussiness_proposal`
  ADD CONSTRAINT `fk_recipients_bussiness_proporsal_users1_idx` FOREIGN KEY (`recipient`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_recipients_bussiness_proposal_users_bussiness_proposal1_idx` FOREIGN KEY (`users_bussiness_proposal_id`) REFERENCES `users_bussiness_proposal` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `users_bussiness_proposal`
--
ALTER TABLE `users_bussiness_proposal`
  ADD CONSTRAINT `fk_bussiness_proporsal_users1_idx` FOREIGN KEY (`sender`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `users_calendar`
--
ALTER TABLE `users_calendar`
  ADD CONSTRAINT `fk_users_calendar_users1_idx` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `users_features_detail`
--
ALTER TABLE `users_features_detail`
  ADD CONSTRAINT `fk_user_features_details_users1_idx` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_users_features_detail_features_values1_idx` FOREIGN KEY (`features_values_id`) REFERENCES `features_values` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `users_videos`
--
ALTER TABLE `users_videos`
  ADD CONSTRAINT `fk_users_videos_users1_idx` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
