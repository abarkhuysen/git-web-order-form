-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 27, 2013 at 02:12 PM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `orderdemo`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `items` bigint(20) NOT NULL,
  `total_ex_vat` decimal(10,2) NOT NULL,
  `total_incl_vat` decimal(10,2) NOT NULL,
  `delivery` decimal(10,2) NOT NULL,
  `grand_total` decimal(10,2) NOT NULL,
  `status` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_id`, `items`, `total_ex_vat`, `total_incl_vat`, `delivery`, `grand_total`, `status`, `created`, `modified`) VALUES
(1, 1, 1010, 100500.00, 110550.00, 10050.00, 126027.00, 'Open', '2013-06-24 00:00:00', '2013-06-24 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE IF NOT EXISTS `order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quote_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `qty` bigint(20) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `quote_id`, `product_id`, `price`, `qty`, `created`, `modified`) VALUES
(1, 1, 2, 50.00, 10, '2013-06-24 00:00:00', '2013-06-24 00:00:00'),
(2, 1, 1, 100.00, 1000, '2013-06-24 00:00:00', '2013-06-24 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(6) NOT NULL,
  `features` varchar(2555) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `code`, `features`, `price`) VALUES
(1, 'AYZ789', 'Ut rhoncus purus eu ipsum pretium adipiscing', 100.00),
(2, 'ABC123', 'Fusce mattis in ante cursus pellentesque', 50.00),
(3, 'A1234A', 'Praesent nec nulla elit. In quis diam metus.', 599.00),
(4, 'ACC345', 'Fusce consequat placerat dolor.', 1.75);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
