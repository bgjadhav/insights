-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 23, 2014 at 07:22 PM
-- Server version: 5.6.20
-- PHP Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `plan`
--

-- --------------------------------------------------------

--
-- Table structure for table `people`
--

CREATE TABLE IF NOT EXISTS `people` (
`id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `skype_id` varchar(255) NOT NULL,
  `position` int(2) NOT NULL,
  `floor` int(11) NOT NULL,
  `updated_at` date NOT NULL,
  `created_at` date NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

--
-- Dumping data for table `people`
--

INSERT INTO `people` (`id`, `full_name`, `avatar`, `description`, `skype_id`, `position`, `floor`, `updated_at`, `created_at`) VALUES
(3, 'Elliot Reeve', '', 'elliot''s description', '', 1, 5, '0000-00-00', '0000-00-00'),
(4, 'Dan Bougourd', '', 'Dan''s description', '', 2, 5, '0000-00-00', '0000-00-00'),
(5, 'Kerron Parchment', '_img/avatars/profilepic_larger.jpg', 'Kerron''s description', 'qwe', 4, 5, '2014-10-23', '0000-00-00'),
(6, 'Ross Johnson', '', 'Ross'' description', '', 5, 5, '0000-00-00', '0000-00-00'),
(7, 'Simon Harbour', '', 'Simon''s description', '', 6, 5, '0000-00-00', '0000-00-00'),
(8, 'Ben Dalgush', '', 'Ben''s description', '', 8, 5, '0000-00-00', '0000-00-00'),
(9, 'Elian Smith', '', 'Elian''s description', '', 7, 5, '0000-00-00', '0000-00-00'),
(10, 'Aaron Kenny', '', 'Aaron''s description', '', 10, 5, '0000-00-00', '0000-00-00'),
(11, 'Ravi Krishnan', '', 'Ravi''s description', '', 13, 5, '0000-00-00', '0000-00-00'),
(12, 'Alistair Wood', '', 'Alistair''s description', '', 12, 5, '0000-00-00', '0000-00-00'),
(13, 'Lilli Knox', '', 'Lilly''s description', '', 11, 5, '0000-00-00', '0000-00-00'),
(14, 'Daniel Sedlacek', '', 'Daniel''s description', '', 16, 5, '0000-00-00', '0000-00-00'),
(15, 'Sergiu Gabura', '', 'Sergiu''s description', '', 15, 5, '0000-00-00', '0000-00-00'),
(16, 'Chris Rodwell', '_img/avatars/chris_rodwell.jpg', 'Chris'' description', 'qwe', 14, 5, '2014-10-23', '0000-00-00'),
(17, 'Matt Rushby', '', 'Matt''s description', '', 18, 5, '0000-00-00', '0000-00-00'),
(18, 'Billy Brett', '', 'Billy''s description', '', 17, 5, '0000-00-00', '0000-00-00'),
(19, 'Greg Williams', '', 'Greg''s description', '', 19, 5, '0000-00-00', '0000-00-00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `people`
--
ALTER TABLE `people`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `people`
--
ALTER TABLE `people`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=20;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
