-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 25, 2015 at 06:10 
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `support`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_category`
--

CREATE TABLE IF NOT EXISTS `tbl_category` (
`id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_category`
--

INSERT INTO `tbl_category` (`id`, `name`) VALUES
(2, 'Программное обеспечение'),
(1, 'Торговое оборудование');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_group`
--

CREATE TABLE IF NOT EXISTS `tbl_group` (
`id` int(11) NOT NULL,
  `code` varchar(100) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_group`
--

INSERT INTO `tbl_group` (`id`, `code`, `name`) VALUES
(1, 'admin', 'Администратор'),
(2, 'manager', 'Менеджер'),
(3, 'user', 'Пользователь');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order`
--

CREATE TABLE IF NOT EXISTS `tbl_order` (
`id` int(11) NOT NULL,
  `user_sender` int(11) DEFAULT NULL,
  `user_answer` int(11) DEFAULT NULL,
  `priority_id` int(11) DEFAULT NULL,
  `date_create` datetime DEFAULT NULL,
  `date_finish` datetime DEFAULT NULL,
  `date_update` datetime DEFAULT CURRENT_TIMESTAMP,
  `date_deadline` datetime DEFAULT NULL,
  `date_start` datetime DEFAULT NULL,
  `status_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` longtext,
  `category_id` int(11) DEFAULT NULL,
  `model` varchar(50) DEFAULT NULL,
  `serial_number` varchar(50) DEFAULT NULL,
  `sender_location` varchar(255) DEFAULT NULL,
  `sender_name` varchar(100) DEFAULT NULL,
  `sender_position` varchar(100) DEFAULT NULL,
  `time_hours` int(11) DEFAULT NULL,
  `complexity` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_order`
--

INSERT INTO `tbl_order` (`id`, `user_sender`, `user_answer`, `priority_id`, `date_create`, `date_finish`, `date_update`, `date_deadline`, `date_start`, `status_id`, `name`, `description`, `category_id`, `model`, `serial_number`, `sender_location`, `sender_name`, `sender_position`, `time_hours`, `complexity`) VALUES
(9, 6, 3, 1, '2015-05-25 05:31:45', NULL, '2015-05-25 05:52:00', '0000-00-00 00:00:00', '2015-05-25 05:48:28', 1, 'Тест 1', 'Тест 1', 2, NULL, NULL, 'Тест 1', 'Тест 1', 'Тест 1', 3, 4),
(10, 7, NULL, 2, '2015-05-25 05:45:35', NULL, '2015-05-25 05:45:35', '0000-00-00 00:00:00', NULL, 5, 'Тест 2', 'Тест 2', 1, NULL, NULL, 'Тест 2', 'Тест 2', 'Тест 2', NULL, NULL),
(11, 8, NULL, 3, '2015-05-25 05:46:47', NULL, '2015-05-25 05:46:47', '0000-00-00 00:00:00', NULL, 5, 'Тест 3', 'Тест 3', 2, NULL, NULL, 'Тест 3', 'Тест 3', 'Тест 3', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_priority`
--

CREATE TABLE IF NOT EXISTS `tbl_priority` (
`id` int(11) NOT NULL,
  `code` varchar(16) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_priority`
--

INSERT INTO `tbl_priority` (`id`, `code`, `name`) VALUES
(1, 'low', 'Низкий'),
(2, 'middle', 'Средний'),
(3, 'high', 'Высокий');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_status`
--

CREATE TABLE IF NOT EXISTS `tbl_status` (
`id` int(11) NOT NULL,
  `code` varchar(16) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_status`
--

INSERT INTO `tbl_status` (`id`, `code`, `name`) VALUES
(1, 'accept', 'Принята'),
(2, 'work', 'В работе'),
(3, 'cancel', 'Отклонена'),
(4, 'done', 'Выполнена'),
(5, 'new', 'Новый');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE IF NOT EXISTS `tbl_user` (
`id` int(11) NOT NULL,
  `login` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `password_reset_token` varchar(255) NOT NULL,
  `auth_key` varchar(32) NOT NULL,
  `name` varchar(50) NOT NULL,
  `second_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `position` varchar(255) NOT NULL,
  `workplace` varchar(255) NOT NULL,
  `group_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`id`, `login`, `password`, `password_reset_token`, `auth_key`, `name`, `second_name`, `last_name`, `position`, `workplace`, `group_id`) VALUES
(1, 'admin@admin.ru', '$2y$13$OY3FSAHoeg8z3tgI8FFe..2irXtItaLKZayfzmIQiarLl6yy5qd9W', '', 'VP9izXlQSH6owT8KVvFP9MoUCwLMyxEq', 'Админ', 'Админов', 'Админович', '', '', 1),
(3, 'manager@manager.ru', '$2y$13$B/nXE0F2Lkv1QUtx/FL7TedZj7iISr93lseyx7TZ8O290z5VLthpm', '', 'cMnVkj0yAaGYnNYCU6-TIuuRtJ_YRow6', 'Андрей', 'Пупкин', 'Пупкович', 'Менеджер отдела продаж', 'Офис 404', 2),
(6, 'user@user.ru', '$2y$13$FjfgPQBPFX9bMZgj.K1tV.Faf09hiS1WnlqQGjwHzaQ2h8cE0plzy', '', 'OSZjbAW2mNPDjMQlJWFEkA-weyGHnTnH', 'Татьяна', 'Фоминская', 'Олеговна', 'кассир-оператор', 'магазин №39', 3),
(7, 'user2@user2.ru', '$2y$13$VNIQbe6Oc0gvJ.SrNR7qFeZYqPXWROlKevrQFgYHqAHJmNP7UXmuO', '', 'aIw8ZhELLLweMmlavKX3VXQ3qdBwJMS9', 'Григорий', 'Фоминский', 'Фамильярович', 'стажер', 'стол', 3),
(8, 'user3@user3.ru', '$2y$13$24fPDqP8zYLIIK8RBrNdH.loVn5BcGnX1EeqcPfB8UlhjlYciXsKO', '', 'rb2KfELLaBHp78KVB8tWAEEhYgkThTuB', 'Сергей', 'Кожанин', 'Морпехович', 'специалист', 'стул', 3),
(9, 'manager2@maneger.ru', '$2y$13$usydp8isePgSi4ZMkRH70.8oZ.GCvAP32TMKR.ucLWhNr7Vgj42pe', '', 'VS27Mxx0So58csMVcmy4-r-J19WcyE3Y', 'Артем', 'Бобров', 'Антонович', 'начальник технического отдела', 'офис 405', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_category`
--
ALTER TABLE `tbl_category`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `tbl_group`
--
ALTER TABLE `tbl_group`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `tbl_order`
--
ALTER TABLE `tbl_order`
 ADD PRIMARY KEY (`id`), ADD KEY `priority_id` (`priority_id`), ADD KEY `user_sender` (`user_sender`), ADD KEY `user_answer` (`user_answer`), ADD KEY `priority_id_2` (`priority_id`), ADD KEY `status_id` (`status_id`), ADD KEY `category_id` (`category_id`), ADD KEY `complexity` (`complexity`);

--
-- Indexes for table `tbl_priority`
--
ALTER TABLE `tbl_priority`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_status`
--
ALTER TABLE `tbl_status`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `login` (`login`), ADD UNIQUE KEY `login_2` (`login`), ADD KEY `group_id` (`group_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_category`
--
ALTER TABLE `tbl_category`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `tbl_group`
--
ALTER TABLE `tbl_group`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `tbl_order`
--
ALTER TABLE `tbl_order`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `tbl_priority`
--
ALTER TABLE `tbl_priority`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `tbl_status`
--
ALTER TABLE `tbl_status`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_order`
--
ALTER TABLE `tbl_order`
ADD CONSTRAINT `tbl_order_ibfk_1` FOREIGN KEY (`priority_id`) REFERENCES `tbl_priority` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `tbl_order_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `tbl_category` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `tbl_order_ibfk_3` FOREIGN KEY (`status_id`) REFERENCES `tbl_status` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `tbl_order_ibfk_4` FOREIGN KEY (`user_sender`) REFERENCES `tbl_user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `tbl_order_ibfk_5` FOREIGN KEY (`user_answer`) REFERENCES `tbl_user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tbl_user`
--
ALTER TABLE `tbl_user`
ADD CONSTRAINT `tbl_user_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `tbl_group` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
