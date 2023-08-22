-- --------------------------------------------------------
-- Hôte:                         127.0.0.1
-- Version du serveur:           8.0.30 - MySQL Community Server - GPL
-- SE du serveur:                Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Listage de la structure de la base pour lechoppe
CREATE DATABASE IF NOT EXISTS `lechoppe` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `lechoppe`;

-- Listage de la structure de table lechoppe. beer_type
CREATE TABLE IF NOT EXISTS `beer_type` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table lechoppe.beer_type : ~11 rows (environ)
INSERT INTO `beer_type` (`id`, `name`) VALUES
	(1, 'Pils'),
	(2, 'Läger'),
	(3, 'I.P.A.'),
	(4, 'Witbier'),
	(5, 'Ambrée'),
	(6, 'Blonde'),
	(7, 'Blanche'),
	(8, 'Weissbier'),
	(9, 'Gose'),
	(10, 'Stout'),
	(11, 'Pale Ale'),
	(12, 'Rousse'),
	(13, 'Brune'),
	(14, 'Noire'),
	(15, 'Dry'),
	(16, 'Impérial'),
	(17, 'Oatmeal'),
	(18, 'Sweet'),
	(19, 'Porter'),
	(20, 'Robuste'),
	(21, 'Baltic'),
	(22, 'Scotch Ale'),
	(23, 'Smocked'),
	(24, 'Hefeweizen'),
	(25, 'Dunker Weizen'),
	(26, 'Berlinweisse'),
	(27, 'Belgians'),
	(28, 'Flanders'),
	(29, 'Lambic'),
	(30, 'Bière de garde'),
	(31, 'Abbaye/Trappiste'),
	(32, 'Triple'),
	(33, 'Quadrupel'),
	(34, 'Double'),
	(35, 'Dark Lager'),
	(36, 'Schwazbier'),
	(37, 'Helles'),
	(38, 'Dunkel'),
	(39, 'Pilsner'),
	(40, 'India Pale Lager'),
	(41, 'American Light Lager'),
	(42, 'West-Coast'),
	(43, 'Brut IPA'),
	(44, 'Black IPA'),
	(45, 'New-England IPA'),
	(46, 'Session IPA'),
	(47, 'White IPA'),
	(48, 'Rye Ale');

-- Listage de la structure de table lechoppe. doctrine_migration_versions
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Listage des données de la table lechoppe.doctrine_migration_versions : ~4 rows (environ)
INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
	('DoctrineMigrations\\Version20230821123009', '2023-08-21 12:30:29', 109),
	('DoctrineMigrations\\Version20230821181133', '2023-08-21 18:11:43', 20),
	('DoctrineMigrations\\Version20230822075844', '2023-08-22 07:59:42', 45),
	('DoctrineMigrations\\Version20230822093908', '2023-08-22 09:39:19', 24),
	('DoctrineMigrations\\Version20230822142033', '2023-08-22 14:20:38', 18);

-- Listage de la structure de table lechoppe. messenger_messages
CREATE TABLE IF NOT EXISTS `messenger_messages` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table lechoppe.messenger_messages : ~0 rows (environ)

-- Listage de la structure de table lechoppe. product
CREATE TABLE IF NOT EXISTS `product` (
  `id` int NOT NULL AUTO_INCREMENT,
  `provider_id` int NOT NULL,
  `production_type_id` int NOT NULL,
  `designation` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `price` double NOT NULL,
  `quantity` int NOT NULL,
  `stock` int DEFAULT NULL,
  `available` tinyint(1) NOT NULL,
  `volume` double NOT NULL,
  `ingredients` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `alcohol_level` double NOT NULL,
  `bitterness` double DEFAULT NULL,
  `slug` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `imagefile` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D34A04ADA53A8AA` (`provider_id`),
  KEY `IDX_D34A04ADD059014E` (`production_type_id`),
  CONSTRAINT `FK_D34A04ADA53A8AA` FOREIGN KEY (`provider_id`) REFERENCES `provider` (`id`),
  CONSTRAINT `FK_D34A04ADD059014E` FOREIGN KEY (`production_type_id`) REFERENCES `production_type` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table lechoppe.product : ~6 rows (environ)
INSERT INTO `product` (`id`, `provider_id`, `production_type_id`, `designation`, `created_at`, `description`, `price`, `quantity`, `stock`, `available`, `volume`, `ingredients`, `alcohol_level`, `bitterness`, `slug`, `imagefile`) VALUES
	(2, 3, 2, 'La blonde test', '2023-08-22 08:50:36', 'La blonde test de la brasserie test', 100, 1, 100, 1, 25, 'malt, houblon', 5, NULL, 'la-blonde-test-25', 'f9bc8973ffcae0c7c2ab03ffd205cba782e28949.png'),
	(3, 3, 2, 'Bière brune test', '2023-08-22 14:45:51', 'Un nouvelle ambrée vient d\'arrive chez la brasserie Test! Savoureuse, légèrement amer.', 330, 1, 50, 1, 33, 'Malt un peu torréfié, houblons, d\'autres ingrédients', 6, 20, 'biere-brune-test-33', '8de27754f01c2026abaae6329b5f8685a6b505a6.jpg'),
	(4, 3, 2, 'La blanche test', '2023-08-22 14:59:54', 'C\'est une bière blanche tout ce qu\'il y a de plus classique. Légère et rafraichissante, "se boit comme du p\'tit lait!"', 220, 1, 50, 0, 50, 'Malt, blé, houblons', 4.9, NULL, 'la-blanche-test-50', '8b70ca85491b53daeae5af9fbf89073255d46cee.png'),
	(5, 3, 2, 'La I.P.A - Pour faire comme tout le monde', '2023-08-22 15:02:25', 'La bière I.P.A., parce que de nous jours une brasserie n\'est plus une brasserie si elle ne fait pas de I.P.A.', 390, 1, 8, 1, 40, 'Orge, plein de houblons', 5.5, 30, 'la-ipa-pour-faire-comme-tout-le-monde-40', '61526b5400c41bc45c78c9215c0da4590d2a2980.jpg'),
	(6, 3, 3, 'Y\'a plus d\'saison', '2023-08-22 17:00:34', 'Une bière de saison, mais on ne sait pas trop laquelle...', 370, 1, 20, 1, 25, 'orge, blé, houblon, fruits', 5.4, 8, 'ya-plus-dsaison-25', '628508ce9ceb98f54d078b334cff54e128be5676.png'),
	(7, 3, 1, 'Pack découverte', '2023-08-22 17:09:15', 'La brasserie test vous propose un pack découverte incluant  leurs meilleurs bières.\r\n\r\nEmballage au design original!', 1250, 6, 5, 1, 33, 'Malt d\'orge, houblons\r\nMalt d\'orge, houblons, blé\r\nMalt d\'orge, houblons, fruits', 5, NULL, 'pack-decouverte-33', 'd116b947d4cad4c3f6c8e3bbb98154d432fdf96b.jpg'),
	(8, 4, 2, '"On va juste boire un coup"', '2023-08-22 20:39:44', 'Bière blonde douce et légère composée de houblons aromatiques. Légère amertume. \r\n\r\nSe boit facilement...La bière parfaite pour se faire "traquenarder"', 590, 1, 12, 1, 75, 'Malt d\'orge, houblons (Elixir, Mistral, Hallertau Blanc).', 5, 25, 'on-va-juste-boire-un-coup-75', '62461828f740adf994e610343bc1db23706c5e8a.jpg'),
	(9, 4, 2, '"On va juste boire un coup"', '2023-08-22 20:42:22', 'Bière blonde douce et légère composée de houblons aromatiques. Légère amertume. \r\n\r\nSe boit facilement...La bière parfaite pour se faire "traquenarder"', 300, 1, 8, 1, 33, 'Malt d\'orge, houblons (Elixir, Mistral, Hallertau Blanc).', 5, 25, 'on-va-juste-boire-un-coup-33', 'a525e46813f720b451251d88f4c943afc558ac14.jpg'),
	(10, 4, 1, 'La lorem Ipsum', '2023-08-22 20:47:04', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam sed eleifend ligula. Ut scelerisque aliquam sem, et mollis tortor lacinia ac. Quisque nec mauris tristique, tincidunt sem sit amet, hendrerit mi. Aenean vehicula odio enim, id venenatis quam finibus vel. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Aliquam consectetur efficitur nulla, dignissim mattis nisi egestas id. Nulla elementum augue risus, sed varius nunc rutrum sit amet. Mauris semper egestas lorem, non venenatis metus laoreet in. Integer interdum iaculis felis non faucibus. Mauris quis porttitor leo. Quisque vitae libero quis ex elementum sollicitudin.\r\n\r\nPellentesque eleifend ligula vel mi viverra tristique. Donec dolor odio, rhoncus rutrum leo lobortis, tristique tincidunt ipsum. Integer sodales diam non tristique rutrum. In lacinia, nulla et varius pulvinar, lorem dolor tempus neque, vitae rutrum nulla lorem at ex. Maecenas dignissim bibendum felis quis dignissim. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Mauris quam enim.', 1200, 1, 5, 1, 75, 'Malt de blé, coriandre, sel', 3.5, 35, 'la-lorem-ipsum-75', NULL),
	(11, 4, 2, 'La Sbroff', '2023-08-22 20:50:06', 'Est-ce que...sbroff?', 550, 1, 3, 1, 33, 'orge et plein d\'autres trucs', 12.8, 30, 'la-sbroff-33', '6c5c9073bafc596b5bf8b5fb4a74851a59b695fd.jpg'),
	(12, 4, 1, 'La Lorem Ipsum blanche', '2023-08-22 20:53:00', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras eu lacus hendrerit, dignissim est vel, tincidunt sapien. Phasellus ipsum felis, blandit sit amet nisl in, consectetur maximus ex. Duis id tellus tortor. Pellentesque efficitur felis ut odio lobortis, non sodales massa molestie. Ut at orci sed lacus eleifend tempus. Nulla nec dolor a leo porttitor iaculis in fermentum leo. Nulla id gravida est, hendrerit eleifend justo. Mauris ligula arcu, commodo eget ultrices iaculis, scelerisque in orci. In et pharetra sem, eget dictum arcu. Nullam volutpat iaculis tellus. Donec ac nibh et lacus consequat suscipit. Pellentesque vel ullamcorper felis. Morbi ac sapien id mi imperdiet facilisis id id tortor. Vestibulum vel lectus fermentum ex suscipit porttitor. Cras fermentum efficitur felis.\r\n\r\nPellentesque eleifend ligula vel mi viverra tristique. Donec dolor odio, rhoncus rutrum leo lobortis, tristique tincidunt ipsum. Integer sodales diam non tristique rutrum. In lacinia, nulla et varius pulvinar, lorem dolor tempus neque, vitae rutrum nulla lorem at ex. Maecenas dignissim bibendum felis quis dignissim. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Mauris quam enim.', 800, 1, 6, 1, 75, 'orge, blé, houblons,', 4.8, NULL, 'la-lorem-ipsum-blanche-75', NULL),
	(13, 4, 2, 'La Lorem Ipsum black IPA', '2023-08-22 20:54:51', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras eu lacus hendrerit, dignissim est vel, tincidunt sapien. Phasellus ipsum felis, blandit sit amet nisl in, consectetur maximus ex. Duis id tellus tortor. Pellentesque efficitur felis ut odio lobortis, non sodales massa molestie. Ut at orci sed lacus eleifend tempus. Nulla nec dolor a leo porttitor iaculis in fermentum leo. Nulla id gravida est, hendrerit eleifend justo. Mauris ligula arcu, commodo eget ultrices iaculis, scelerisque in orci. In et pharetra sem, eget dictum arcu. Nullam volutpat iaculis tellus. Donec ac nibh et lacus consequat suscipit. Pellentesque vel ullamcorper felis. Morbi ac sapien id mi imperdiet facilisis id id tortor. Vestibulum vel lectus fermentum ex suscipit porttitor. Cras fermentum efficitur felis.\r\n\r\nPellentesque eleifend ligula vel mi viverra tristique. Donec dolor odio, rhoncus rutrum leo lobortis, tristique tincidunt ipsum. Integer sodales dia\r\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Cras eu lacus hendrerit, dignissim est vel, tincidunt sapien. Phasellus ipsum felis, blandit sit amet nisl in, consectetur maximus ex. Duis id tellus tortor. Pellentesque efficitur felis ut odio lobortis, non sodales massa molestie. Ut at orci sed lacus eleifend tempus. Nulla nec dolor a leo porttitor iaculis in fermentum leo. Nulla id gravida est, hendrerit eleifend justo. Mauris ligula arcu, commodo eget ultrices iaculis, scelerisque in orci. In et pharetra sem, eget dictum arcu. Nullam volutpat iaculis tellus. Donec ac nibh et lacus consequat suscipit. Pellentesque vel ullamcorper felis. Morbi ac sapien id mi imperdiet facilisis id id tortor. Vestibulum vel lectus fermentum ex suscipit porttitor. Cras fermentum efficitur felis.\r\n\r\nPellentesque eleifend ligula vel mi viverra tristique. Donec dolor odio, rhoncus rutrum leo lobortis, tristique tincidunt ipsum. Integer sodales diam non tristique rutrum. In lacinia, nulla et varius pulvinar, lorem dolor tempus neque, vitae rutrum nulla lorem at ex. Maecenas dignissim bibendum felis quis dignissim. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Mauris quam enim.', 900, 1, 12, 1, 75, 'Pellentesque eleifend ligula vel mi viverra tristique. Donec dolor odio, rhoncus rutrum leo lobortis, tristique tincidunt ipsum. Integer sodales diam non tristique rutrum. In lacinia, nulla et varius pulvinar, lorem dolor tempus neque, vitae rutrum nulla lorem at ex. Maecenas dignissim bibendum felis quis dignissim. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Mauris quam enim.', 9, 40, 'la-lorem-ipsum-black-ipa-75', NULL);

-- Listage de la structure de table lechoppe. production_type
CREATE TABLE IF NOT EXISTS `production_type` (
  `id` int NOT NULL AUTO_INCREMENT,
  `production_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table lechoppe.production_type : ~5 rows (environ)
INSERT INTO `production_type` (`id`, `production_type`) VALUES
	(1, 'Ephémère'),
	(2, 'Permanente'),
	(3, 'Saisonnière'),
	(4, 'Exeptionnelle');

-- Listage de la structure de table lechoppe. product_beer_type
CREATE TABLE IF NOT EXISTS `product_beer_type` (
  `product_id` int NOT NULL,
  `beer_type_id` int NOT NULL,
  PRIMARY KEY (`product_id`,`beer_type_id`),
  KEY `IDX_EA8ABA794584665A` (`product_id`),
  KEY `IDX_EA8ABA79A3829862` (`beer_type_id`),
  CONSTRAINT `FK_EA8ABA794584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_EA8ABA79A3829862` FOREIGN KEY (`beer_type_id`) REFERENCES `beer_type` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table lechoppe.product_beer_type : ~2 rows (environ)
INSERT INTO `product_beer_type` (`product_id`, `beer_type_id`) VALUES
	(2, 1),
	(3, 5),
	(4, 7),
	(5, 3),
	(5, 6),
	(6, 8),
	(7, 1),
	(7, 3),
	(7, 4),
	(7, 8),
	(8, 6),
	(8, 11),
	(9, 11),
	(10, 9),
	(11, 16),
	(12, 7),
	(12, 8),
	(13, 44);

-- Listage de la structure de table lechoppe. provider
CREATE TABLE IF NOT EXISTS `provider` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adress` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cp` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `website` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `social_network` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table lechoppe.provider : ~1 rows (environ)
INSERT INTO `provider` (`id`, `name`, `adress`, `cp`, `city`, `website`, `social_network`) VALUES
	(3, 'Brasserie test', '101, rue du test', '67200', 'Schlagbourg', 'http://Brasserie-test.fr', 'http://facebook.com/labrasserietest'),
	(4, 'Schlagbrew', '79, rue de l\'impasse', '67500', 'Niederschaeffolsheim', NULL, NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
