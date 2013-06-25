-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 25, 2013 at 09:23 PM
-- Server version: 5.1.44
-- PHP Version: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `orderdemo`
--

-- --------------------------------------------------------

--
-- Table structure for table `items_table`
--

CREATE TABLE IF NOT EXISTS `items_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `code` varchar(6) NOT NULL,
  `features` varchar(2555) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `items_table`
--

INSERT INTO `items_table` (`id`, `product_id`, `code`, `features`) VALUES
(1, 2, 'XYZ789', 'Ut rhoncus purus eu ipsum pretium adipiscing'),
(2, 15, 'ABC123', 'Fusce mattis in ante cursus pellentesque');

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
(1, 1, 17, 1550.00, 1767.00, 155.00, 1922.00, 'Open', '2013-06-24 00:00:00', '2013-06-24 00:00:00');

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
(1, 1, 2, 50.00, 3, '2013-06-24 00:00:00', '2013-06-24 00:00:00'),
(2, 1, 15, 100.00, 14, '2013-06-24 00:00:00', '2013-06-24 00:00:00');
