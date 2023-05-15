-- --------------------------------------------------------
-- Hostiteľ:                     127.0.0.1
-- Verze serveru:                10.6.4-MariaDB - mariadb.org binary distribution
-- OS serveru:                   Win32
-- HeidiSQL Verzia:              11.3.0.6295
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Exportování struktury databáze pro
CREATE DATABASE IF NOT EXISTS `ecommerce1` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `ecommerce1`;

-- Exportování struktury pro tabulka ecommerce1.categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `category` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `category` (`category`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

-- Exportování dat pro tabulku ecommerce1.categories: 0 rows
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;

-- Exportování struktury pro tabulka ecommerce1.orders
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `transaction_id` varchar(19) NOT NULL,
  `payment_status` varchar(15) NOT NULL,
  `payment_amount` decimal(6,2) unsigned NOT NULL,
  `payment_date_time` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

-- Exportování dat pro tabulku ecommerce1.orders: 0 rows
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;

-- Exportování struktury pro tabulka ecommerce1.pages
CREATE TABLE IF NOT EXISTS `pages` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` smallint(5) unsigned NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` tinytext NOT NULL,
  `content` longtext NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  KEY `creation_date` (`date_created`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

-- Exportování dat pro tabulku ecommerce1.pages: 0 rows
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;

-- Exportování struktury pro tabulka ecommerce1.pdfs
CREATE TABLE IF NOT EXISTS `pdfs` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `tmp_name` char(40) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` tinytext NOT NULL,
  `file_name` varchar(40) NOT NULL,
  `size` mediumint(8) unsigned NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `tmp_name` (`tmp_name`),
  KEY `date_created` (`date_created`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3;

-- Exportování dat pro tabulku ecommerce1.pdfs: 4 rows
/*!40000 ALTER TABLE `pdfs` DISABLE KEYS */;
INSERT INTO `pdfs` (`id`, `tmp_name`, `title`, `description`, `file_name`, `size`, `date_created`) VALUES
	(1, '', 'Mona Lisa', 'La Jacond messieurs', '', 0, '2022-03-02 22:57:34');
INSERT INTO `pdfs` (`id`, `tmp_name`, `title`, `description`, `file_name`, `size`, `date_created`) VALUES
	(2, 'b6d8d3e715fefaa998017a2dc6e341a11364b96c', 'Mona Lisa', 'La Jaconde messieurs', '003_odpor.pdf', 0, '2022-03-02 23:33:13');
INSERT INTO `pdfs` (`id`, `tmp_name`, `title`, `description`, `file_name`, `size`, `date_created`) VALUES
	(3, '65e1b1d4902a6417980e93020df3192e94f8a21c', 'Mona Lisa', 'La Jaconde messieurs', '003_odpor.pdf', 638, '2022-03-02 23:33:58');
INSERT INTO `pdfs` (`id`, `tmp_name`, `title`, `description`, `file_name`, `size`, `date_created`) VALUES
	(4, 'df5d80e30a9a1a78ebb9799932b1ccfc26c0d9ab', 'Mona Lisa', 'La Jaconde messieurs', '003_odpor.pdf', 638, '2022-03-02 23:37:04');
/*!40000 ALTER TABLE `pdfs` ENABLE KEYS */;

-- Exportování struktury pro tabulka ecommerce1.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('member','admin') NOT NULL,
  `username` varchar(30) NOT NULL,
  `email` varchar(80) NOT NULL,
  `pass` varbinary(32) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(40) NOT NULL,
  `date_expires` date NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb3;

-- Exportování dat pro tabulku ecommerce1.users: 20 rows
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `type`, `username`, `email`, `pass`, `first_name`, `last_name`, `date_expires`, `date_created`, `date_modified`) VALUES
	(1, 'member', 'test_u', 'test_e', _binary 0x746573745f70617373, 'test_fn', 'test_ln', '2022-03-02', '2022-03-02 01:47:40', '0000-00-00 00:00:00');
INSERT INTO `users` (`id`, `type`, `username`, `email`, `pass`, `first_name`, `last_name`, `date_expires`, `date_created`, `date_modified`) VALUES
	(2, 'member', '2test_u', '2test_e', _binary 0x32746573745f70617373, '2test_fn', '2test_ln', '2022-03-02', '2022-03-02 14:38:33', '0000-00-00 00:00:00');
INSERT INTO `users` (`id`, `type`, `username`, `email`, `pass`, `first_name`, `last_name`, `date_expires`, `date_created`, `date_modified`) VALUES
	(3, 'member', '3test_u', '3test_e', _binary 0x33746573745f70617373, '3test_fn', '3test_ln', '2022-03-02', '2022-03-02 15:18:31', '0000-00-00 00:00:00');
INSERT INTO `users` (`id`, `type`, `username`, `email`, `pass`, `first_name`, `last_name`, `date_expires`, `date_created`, `date_modified`) VALUES
	(4, 'member', '4test_u', '4test_e', _binary 0x34746573745f70617373, '4test_fn', '4test_ln', '2022-03-02', '2022-03-02 15:36:04', '0000-00-00 00:00:00');
INSERT INTO `users` (`id`, `type`, `username`, `email`, `pass`, `first_name`, `last_name`, `date_expires`, `date_created`, `date_modified`) VALUES
	(5, 'member', '5test_u', '5test_e', _binary 0x35746573745f70617373, '5test_fn', '5test_ln', '2022-03-02', '2022-03-02 15:37:22', '0000-00-00 00:00:00');
INSERT INTO `users` (`id`, `type`, `username`, `email`, `pass`, `first_name`, `last_name`, `date_expires`, `date_created`, `date_modified`) VALUES
	(6, 'member', '6test_u', '6test_e', _binary 0x36746573745f70, '6test_fn', '6test_ln', '2022-03-02', '2022-03-02 15:46:04', '0000-00-00 00:00:00');
INSERT INTO `users` (`id`, `type`, `username`, `email`, `pass`, `first_name`, `last_name`, `date_expires`, `date_created`, `date_modified`) VALUES
	(7, 'member', '7test_u', '7test_e', _binary 0x37746573745f70, '7test_fn', '7test_ln', '2022-03-02', '2022-03-02 15:55:28', '0000-00-00 00:00:00');
INSERT INTO `users` (`id`, `type`, `username`, `email`, `pass`, `first_name`, `last_name`, `date_expires`, `date_created`, `date_modified`) VALUES
	(8, 'member', '8test_u', '8test_e', _binary 0x38746573745f70, '8test_fn', '8test_ln', '2022-03-02', '2022-03-02 16:06:13', '0000-00-00 00:00:00');
INSERT INTO `users` (`id`, `type`, `username`, `email`, `pass`, `first_name`, `last_name`, `date_expires`, `date_created`, `date_modified`) VALUES
	(9, 'member', '9test_u', '9test_e', _binary 0x39746573745f70, '9test_fn', '9test_ln', '2022-04-02', '2022-03-02 16:12:29', '0000-00-00 00:00:00');
INSERT INTO `users` (`id`, `type`, `username`, `email`, `pass`, `first_name`, `last_name`, `date_expires`, `date_created`, `date_modified`) VALUES
	(10, 'member', '10test_u', '10test_e', _binary 0x3130746573745f70, '10test_fn', '10test_ln', '2022-04-02', '2022-03-02 16:17:29', '0000-00-00 00:00:00');
INSERT INTO `users` (`id`, `type`, `username`, `email`, `pass`, `first_name`, `last_name`, `date_expires`, `date_created`, `date_modified`) VALUES
	(11, 'member', '11test_u', '11test_e', _binary 0x302461464c6d794936705a57396a4c77786753376d55512e3452514c3948, '11test_fn', '11test_ln', '2022-04-02', '2022-03-02 16:31:18', '0000-00-00 00:00:00');
INSERT INTO `users` (`id`, `type`, `username`, `email`, `pass`, `first_name`, `last_name`, `date_expires`, `date_created`, `date_modified`) VALUES
	(12, 'admin', 'admin', 'cordelfenevall@gmail.com     ', _binary 0x3024697a4345715a6e77656c56614253516f547a6771582e4d306f704161, 'cordel', 'fenevall', '2022-04-02', '2022-03-02 16:45:11', '0000-00-00 00:00:00');
INSERT INTO `users` (`id`, `type`, `username`, `email`, `pass`, `first_name`, `last_name`, `date_expires`, `date_created`, `date_modified`) VALUES
	(13, 'member', 'testus', 'cordelfenevall2@gmail.com', _binary 0x30246c486b676430566271646a6d4d6e2e6d6947624d324f6f5866514f68, 'testfna', 'testlna', '2022-04-02', '2022-03-02 17:02:58', '0000-00-00 00:00:00');
INSERT INTO `users` (`id`, `type`, `username`, `email`, `pass`, `first_name`, `last_name`, `date_expires`, `date_created`, `date_modified`) VALUES
	(14, 'member', 'feeri', 'feri@mrkvicka.com', _binary 0x3024427a7a4a524d7a41672f396b5276566c77542f42377572343059655a, 'feeri', 'mrkvicka', '2022-04-02', '2022-03-02 19:08:50', '0000-00-00 00:00:00');
INSERT INTO `users` (`id`, `type`, `username`, `email`, `pass`, `first_name`, `last_name`, `date_expires`, `date_created`, `date_modified`) VALUES
	(15, 'member', 'zemi', 'peter@popara.com', _binary 0x30244731674d676855624d6b4a346c5778615447356d4c65386c7573586c, 'peter', 'popara', '2022-03-08', '2022-03-09 18:41:10', '0000-00-00 00:00:00');
INSERT INTO `users` (`id`, `type`, `username`, `email`, `pass`, `first_name`, `last_name`, `date_expires`, `date_created`, `date_modified`) VALUES
	(16, 'member', 'johnny', 'johnny@bgood.com', _binary 0x30245343745267366c616d3479725363636a4d4a6850414f494c55714444, 'johnny', 'bgood', '2022-03-08', '2022-03-09 19:31:26', '0000-00-00 00:00:00');
INSERT INTO `users` (`id`, `type`, `username`, `email`, `pass`, `first_name`, `last_name`, `date_expires`, `date_created`, `date_modified`) VALUES
	(17, 'member', 'kokotkokaty', 'kokot@kokaty.com', _binary 0x3024696f3637534a6a4d584a4142317934484d557a58612e344e4c783453, 'kokot', 'kokaty', '2022-03-27', '2022-03-28 21:47:46', '0000-00-00 00:00:00');
INSERT INTO `users` (`id`, `type`, `username`, `email`, `pass`, `first_name`, `last_name`, `date_expires`, `date_created`, `date_modified`) VALUES
	(18, 'member', 'jebulko', 'jebko@jebinko.com', _binary 0x3024636b414d62566138387951516570476254442e55304f635743787333, 'jebko', 'jebinko', '2022-03-28', '2022-03-29 12:56:05', '0000-00-00 00:00:00');
INSERT INTO `users` (`id`, `type`, `username`, `email`, `pass`, `first_name`, `last_name`, `date_expires`, `date_created`, `date_modified`) VALUES
	(19, 'member', 'drblina', 'drblina@jeblinger.com', _binary 0x30245641596c746d4e37537042725871453451483977332e73356f623366, 'drblina', 'jeblinger', '2022-03-28', '2022-03-29 21:19:15', '0000-00-00 00:00:00');
INSERT INTO `users` (`id`, `type`, `username`, `email`, `pass`, `first_name`, `last_name`, `date_expires`, `date_created`, `date_modified`) VALUES
	(20, 'member', 'weissmann', 'jonathan@janosik.com', _binary 0x3024615934555847414c654c74444b654f2f68304568564f765769332f7a, 'jonathan', 'janosik', '2022-03-29', '2022-03-30 00:34:03', '0000-00-00 00:00:00');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
