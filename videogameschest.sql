-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Mer 16 Octobre 2013 à 11:57
-- Version du serveur: 5.5.24-log
-- Version de PHP: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `videogameschest`
--

-- --------------------------------------------------------

--
-- Structure de la table `config`
--

CREATE TABLE IF NOT EXISTS `config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `_key` varchar(200) NOT NULL,
  `value` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `config`
--

INSERT INTO `config` (`id`, `_key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'review_check_date', '2013-01-17 08:20:26', '0000-00-00 00:00:00', '2013-01-17 08:20:26'),
(2, 'promotion_newsletter_check_date', '2013-01-17 00:00:00', '2013-02-01 00:00:00', '2013-02-01 00:00:00'),
(3, 'crawler_feed_links', '["http:\\/\\/machin.com"]', '2013-03-09 00:00:00', '2013-03-09 11:26:04');

-- --------------------------------------------------------

--
-- Structure de la table `laravel_migrations`
--

CREATE TABLE IF NOT EXISTS `laravel_migrations` (
  `bundle` varchar(50) NOT NULL,
  `name` varchar(200) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`bundle`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `profiles`
--

CREATE TABLE IF NOT EXISTS `profiles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `name` varchar(200) NOT NULL,
  `is_public` tinyint(1) NOT NULL DEFAULT '0',
  `description` text NOT NULL,
  `release_date` date NOT NULL,
  `links` text NOT NULL,
  `medias` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `games_id_unique` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29 ;

--
-- Contenu de la table `profiles`
--

INSERT INTO `profiles` (`id`, `created_at`, `updated_at`, `name`, `is_public`, `description`, `release_date`, `links`, `medias`) VALUES
(22, '2012-12-14 17:09:55', '2013-02-19 08:48:52', 'Game 2', 0, 'Game 2\r\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Donec eros lorem, posuere quis facilisis eget, faucibus auctor nisl. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed tincidunt leo eu diam tristique in pellentesque orci dignissim. Fusce pulvinar, risus vel pharetra varius, neque magna volutpat urna, sed scelerisque libero turpis in magna.\r\n\r\nPraesent pellentesque enim quis ligula convallis ultrices. Curabitur leo neque, condimentum ut adipiscing nec, convallis a risus. Curabitur blandit rutrum velit. Cras in sollicitudin sapien. Fusce eget massa sed nisl pretium ornare in non velit. Morbi mollis scelerisque justo vel vehicula.\r\n\r\nNam eu sem et velit tempus venenatis. Praesent sed est quam, in iaculis purus. Aliquam tristique, mauris congue pharetra vulputate, dui nibh consequat justo, vel sollicitudin dui nisi sit amet metus. Praesent vel quam nisl. \r\n\r\nVivamus aliquet justo sit amet diam elementum sit amet pellentesque lacus ullamcorper. Vivamus nulla est, tincidunt eu placerat sit amet, accumsan vitae magna. Suspendisse elementum eleifend purus, sit amet hendrerit eros feugiat aliquet. ', '0000-00-00', '', ''),
(23, '2013-01-06 11:16:59', '2013-02-15 17:45:12', 'Game 1', 0, 'Game 1\r\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Donec eros lorem, posuere quis facilisis eget, faucibus auctor nisl. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed tincidunt leo eu diam tristique in pellentesque orci dignissim. Fusce pulvinar, risus vel pharetra varius, neque magna volutpat urna, sed scelerisque libero turpis in magna.\r\n\r\nPraesent pellentesque enim quis ligula convallis ultrices. Curabitur leo neque, condimentum ut adipiscing nec, convallis a risus. Curabitur blandit rutrum velit. Cras in sollicitudin sapien. Fusce eget massa sed nisl pretium ornare in non velit. Morbi mollis scelerisque justo vel vehicula.\r\n\r\nNam eu sem et velit tempus venenatis. Praesent sed est quam, in iaculis purus. Aliquam tristique, mauris congue pharetra vulputate, dui nibh consequat justo, vel sollicitudin dui nisi sit amet metus. Praesent vel quam nisl. \r\n\r\nVivamus aliquet justo sit amet diam elementum sit amet pellentesque lacus ullamcorper. Vivamus nulla est, tincidunt eu placerat sit amet, accumsan vitae magna. Suspendisse elementum eleifend purus, sit amet hendrerit eros feugiat aliquet. ', '0000-00-00', '', ''),
(24, '2013-01-06 11:33:07', '2013-02-15 17:45:12', 'Game 3', 0, 'Game 3\r\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Donec eros lorem, posuere quis facilisis eget, faucibus auctor nisl. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed tincidunt leo eu diam tristique in pellentesque orci dignissim. Fusce pulvinar, risus vel pharetra varius, neque magna volutpat urna, sed scelerisque libero turpis in magna.\r\n\r\nPraesent pellentesque enim quis ligula convallis ultrices. Curabitur leo neque, condimentum ut adipiscing nec, convallis a risus. Curabitur blandit rutrum velit. Cras in sollicitudin sapien. Fusce eget massa sed nisl pretium ornare in non velit. Morbi mollis scelerisque justo vel vehicula.\r\n\r\nNam eu sem et velit tempus venenatis. Praesent sed est quam, in iaculis purus. Aliquam tristique, mauris congue pharetra vulputate, dui nibh consequat justo, vel sollicitudin dui nisi sit amet metus. Praesent vel quam nisl. \r\n\r\nVivamus aliquet justo sit amet diam elementum sit amet pellentesque lacus ullamcorper. Vivamus nulla est, tincidunt eu placerat sit amet, accumsan vitae magna. Suspendisse elementum eleifend purus, sit amet hendrerit eros feugiat aliquet.', '0000-00-00', '', ''),
(28, '2013-10-14 20:34:14', '2013-10-14 21:04:22', 'Profile name2', 0, 'description2', '2014-10-14', '[{"name":"link","url":"http:\\/\\/link2.com"},{"name":"link2","url":"http:\\/\\/link3.com"}]', '[{"name":"screenshots","url":"http:\\/\\/link.com"}]');

-- --------------------------------------------------------

--
-- Structure de la table `profile_tag`
--

CREATE TABLE IF NOT EXISTS `profile_tag` (
  `id` int(11) NOT NULL,
  `profile_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `profile_tag`
--

INSERT INTO `profile_tag` (`id`, `profile_id`, `tag_id`) VALUES
(0, 28, 1),
(0, 28, 4),
(0, 28, 6),
(0, 28, 7);

-- --------------------------------------------------------

--
-- Structure de la table `reports`
--

CREATE TABLE IF NOT EXISTS `reports` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `profile_id` int(11) NOT NULL,
  `message` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `suggestionfeeds`
--

CREATE TABLE IF NOT EXISTS `suggestionfeeds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `suggestions`
--

CREATE TABLE IF NOT EXISTS `suggestions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `source` varchar(200) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Contenu de la table `suggestions`
--

INSERT INTO `suggestions` (`id`, `url`, `source`, `created_at`, `updated_at`) VALUES
(1, 'https://localhost', 'https://localhost', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'http://url1.com', 'user', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'http://url1.com', 'http://localhost/videogame/', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 'http://url1.com', 'user', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 'http://test2.com', 'user', '2013-10-15 15:08:10', '2013-10-15 15:08:10');

-- --------------------------------------------------------

--
-- Structure de la table `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) CHARACTER SET utf8 NOT NULL,
  `profile_count` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=93 ;

--
-- Contenu de la table `tags`
--

INSERT INTO `tags` (`id`, `name`, `profile_count`, `created_at`, `updated_at`) VALUES
(1, 'android', 0, '2013-10-13 19:10:00', '2013-10-13 19:10:00'),
(2, 'blackberry', 0, '2013-10-13 19:12:08', '2013-10-13 19:12:08'),
(3, 'ios', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(4, 'linux', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(5, 'mac', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(6, 'windows', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(7, 'windowsphone', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(8, 'tablet', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(9, 'smartphone', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(10, 'browser', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(11, 'ipod', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(12, 'iphone', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(13, 'ipad', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(14, 'mac', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(15, 'ouya', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(16, 'pc', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(17, 'ps3', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(18, 'ps4', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(19, 'psp', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(20, 'psvita', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(21, 'surface', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(22, 'wii', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(23, 'wiiu', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(24, 'xbox360', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(25, 'xperiaplay', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(26, 'xperia', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(27, 'adventure-game-studio', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(28, 'cocos-2d', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(29, 'construct', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(30, 'craftstudio', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(31, 'cry-engine', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(32, 'custom-engine', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(33, 'flash', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(34, 'flixel', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(35, 'game-maker', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(36, 'html5', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(37, 'love', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(38, 'ogre-3d', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(39, 'rpg-maker', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(40, 'source-engine', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(41, 'stencyl', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(42, 'unreal-development-kit', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(43, 'unity-3d', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(44, 'unreal-engine', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(45, 'xna', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(46, 'single-player', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(47, 'coop', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(48, 'mmo', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(49, 'multi-player', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(50, 'pixelart', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(51, 'medieval', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(52, 'futuristic', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(53, 'sci-fi', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(54, 'space', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(55, 'modern', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(56, 'action', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(57, 'adventure', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(58, 'arcade', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(59, 'city-building', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(60, 'fighting', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(61, 'hack-and-slash', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(62, 'party', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(63, 'platformer', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(64, 'point-and-click', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(65, 'puzzle', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(66, 'racing', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(67, 'resources-management', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(68, 'roguelike', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(69, 'role-playing', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(70, 'real-time-strategy', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(71, 'shoot-em-up', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(72, 'shooter', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(73, 'simulation', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(74, 'sport', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(75, 'strategy', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(76, 'tower-defense', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(77, 'steampunk', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(78, 'first-person', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(79, 'isometric', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(80, 'third-person', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(81, 'post-apocalyptic', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(82, '2D', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(83, '2.5D', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(84, '3D', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(85, 'casual', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(86, 'crowdfunded', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(87, 'physics', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(88, 'topdown', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(89, 'turnbased', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(90, 'realtime', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(91, 'fantasy', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17'),
(92, 'sidescrolling', 0, '2013-10-13 19:18:17', '2013-10-13 19:18:17');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `url_key` varchar(200) NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'dev',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `url_key`, `type`, `created_at`, `updated_at`) VALUES
(1, 'Florent', 'florent.poujol@gmail.com', '$2y$08$HlaWOiqMYfSXMsaWhbBMs.e.Vuxexj7sPiRU1yrLC9reKJevHaduW', 'LNumnjvQbKKQaNRNDvoB', 'admin', '2012-11-01 12:00:01', '2013-10-14 10:06:37');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
