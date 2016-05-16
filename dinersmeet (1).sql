-- phpMyAdmin SQL Dump
-- version 4.6.0
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 25, 2016 at 08:11 PM
-- Server version: 10.1.10-MariaDB
-- PHP Version: 7.0.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dinersmeet`
--

-- --------------------------------------------------------

--
-- Table structure for table `available_in_location`
--

CREATE TABLE `available_in_location` (
  `id` int(11) NOT NULL,
  `parent_location_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `geo_address` text,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `admin_notes` text,
  `add_date` datetime DEFAULT NULL,
  `modify_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `available_in_location`
--

INSERT INTO `available_in_location` (`id`, `parent_location_id`, `name`, `geo_address`, `status`, `admin_notes`, `add_date`, `modify_date`) VALUES
(1, NULL, 'LPU', NULL, 1, NULL, '2016-04-19 00:00:00', NULL),
(2, 1, '34 Block', NULL, 1, NULL, '2016-04-21 00:00:00', '2016-04-21 00:00:00'),
(3, 1, '28 Block Top Court', NULL, 1, NULL, '2016-04-21 00:00:00', '2016-04-21 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `admin_notes` text,
  `add_date` datetime DEFAULT NULL,
  `modify_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`, `status`, `admin_notes`, `add_date`, `modify_date`) VALUES
(1, 'North Indian', 1, NULL, '2016-04-24 00:00:00', '2016-04-24 00:00:00'),
(2, 'South Indian', 1, NULL, '2016-04-24 00:00:00', '2016-04-24 00:00:00'),
(3, 'Chinese', 1, NULL, '2016-04-24 00:00:00', '2016-04-24 00:00:00'),
(4, 'Subs', 1, NULL, '2016-04-24 00:00:00', '2016-04-24 00:00:00'),
(5, 'Snacks', 1, NULL, '2016-04-24 00:00:00', '2016-04-24 00:00:00'),
(6, 'Beverage', 1, NULL, '2016-04-24 00:00:00', '2016-04-24 00:00:00'),
(7, 'Fruits', 1, NULL, '2016-04-24 00:00:00', '2016-04-24 00:00:00'),
(8, 'Sweets and Desserts', 1, NULL, '2016-04-24 00:00:00', '2016-04-24 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `customer_address_book`
--

CREATE TABLE `customer_address_book` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `address` text,
  `address_type` enum('permanent','delivery') NOT NULL DEFAULT 'permanent',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `admin_notes` text,
  `add_date` datetime DEFAULT NULL,
  `modify_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `customer_wallet`
--

CREATE TABLE `customer_wallet` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `balance` int(11) NOT NULL DEFAULT '0',
  `admin_notes` text,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `add_date` datetime DEFAULT NULL,
  `modify_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `id` int(11) NOT NULL,
  `restaurant_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `details` text,
  `logo` varchar(255) DEFAULT NULL,
  `is_veg` tinyint(1) NOT NULL DEFAULT '1',
  `is_spicy` tinyint(1) NOT NULL DEFAULT '0',
  `price` float NOT NULL DEFAULT '0',
  `pricing_detail` varchar(255) DEFAULT NULL,
  `serving_time` int(11) DEFAULT NULL,
  `delivery_available` tinyint(1) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `admin_notes` text,
  `add_date` datetime DEFAULT NULL,
  `modify_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `delivery_address_id` int(11) NOT NULL,
  `amount` float NOT NULL DEFAULT '0',
  `serving_type` enum('pickup','delivery') NOT NULL DEFAULT 'pickup',
  `time_for_pickup` datetime DEFAULT NULL,
  `time_for_delivery` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `admin_notes` text,
  `add_date` datetime DEFAULT NULL,
  `modify_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `order_status`
--

CREATE TABLE `order_status` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `order_status` enum('awaiting_payment','payment_done','awaiting_confirmation','order_placed','order_accepted','order_preparing','order_prepared','ready_for_pickup','order_in_transit','order_delivered','order_cancelled','order_completed') NOT NULL DEFAULT 'awaiting_payment',
  `admin_notes` text,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `add_date` datetime DEFAULT NULL,
  `modify_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `restaurant`
--

CREATE TABLE `restaurant` (
  `id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `street_address` text,
  `mobile_number` varchar(25) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `admin_notes` text,
  `add_date` datetime DEFAULT NULL,
  `modify_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `admin_notes` text,
  `add_date` datetime DEFAULT NULL,
  `modify_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `name`, `status`, `admin_notes`, `add_date`, `modify_date`) VALUES
(1, 'admin', 1, NULL, '2016-03-27 00:00:00', NULL),
(2, 'customer', 1, NULL, '2016-03-27 00:00:00', NULL),
(3, 'vendor', 1, NULL, '2016-03-27 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `shopping_cart`
--

CREATE TABLE `shopping_cart` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `admin_notes` text,
  `add_date` datetime DEFAULT NULL,
  `modify_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `shopping_cart_has_items`
--

CREATE TABLE `shopping_cart_has_items` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `shopping_cart_id` int(11) NOT NULL,
  `item_quantity` int(11) NOT NULL DEFAULT '1',
  `item_cost` float NOT NULL DEFAULT '0',
  `admin_notes` text,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `add_date` datetime DEFAULT NULL,
  `modify_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `mobile_number` varchar(25) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT '0',
  `email_verification_hash` varchar(255) DEFAULT NULL,
  `otp_verification` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `admin_notes` text,
  `add_date` datetime DEFAULT NULL,
  `modify_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `role_id`, `first_name`, `last_name`, `profile_image`, `email`, `mobile_number`, `password`, `is_verified`, `email_verification_hash`, `otp_verification`, `status`, `admin_notes`, `add_date`, `modify_date`) VALUES
(1, 2, NULL, NULL, NULL, 'nishaakashona@gmail.com', '9026606400', 'MTIzNDU2', 1, NULL, '701043', 1, NULL, '2016-04-25 23:11:22', '2016-04-25 23:11:22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `available_in_location`
--
ALTER TABLE `available_in_location`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `parent_location_id` (`parent_location_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `customer_address_book`
--
ALTER TABLE `customer_address_book`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `customer_wallet`
--
ALTER TABLE `customer_wallet`
  ADD PRIMARY KEY (`id`,`customer_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `restaurant_id` (`restaurant_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`,`package_id`,`delivery_address_id`),
  ADD KEY `orders_ibfk_4` (`package_id`),
  ADD KEY `orders_ibfk_3` (`delivery_address_id`);

--
-- Indexes for table `order_status`
--
ALTER TABLE `order_status`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `restaurant`
--
ALTER TABLE `restaurant`
  ADD PRIMARY KEY (`id`,`location_id`,`vendor_id`),
  ADD KEY `restaurant_ibfk_1` (`location_id`),
  ADD KEY `restaurant_ibfk_2` (`vendor_id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `shopping_cart`
--
ALTER TABLE `shopping_cart`
  ADD PRIMARY KEY (`id`,`customer_id`),
  ADD UNIQUE KEY `customer_id` (`customer_id`);

--
-- Indexes for table `shopping_cart_has_items`
--
ALTER TABLE `shopping_cart_has_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`,`shopping_cart_id`),
  ADD KEY `shopping_cart_id` (`shopping_cart_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`,`mobile_number`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `available_in_location`
--
ALTER TABLE `available_in_location`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `customer_address_book`
--
ALTER TABLE `customer_address_book`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `customer_wallet`
--
ALTER TABLE `customer_wallet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `order_status`
--
ALTER TABLE `order_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `restaurant`
--
ALTER TABLE `restaurant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `shopping_cart`
--
ALTER TABLE `shopping_cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `shopping_cart_has_items`
--
ALTER TABLE `shopping_cart_has_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `available_in_location`
--
ALTER TABLE `available_in_location`
  ADD CONSTRAINT `available_in_location_ibfk_1` FOREIGN KEY (`parent_location_id`) REFERENCES `available_in_location` (`id`);

--
-- Constraints for table `customer_address_book`
--
ALTER TABLE `customer_address_book`
  ADD CONSTRAINT `customer_address_book_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `customer_wallet`
--
ALTER TABLE `customer_wallet`
  ADD CONSTRAINT `customer_wallet_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `item_ibfk_1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant` (`id`),
  ADD CONSTRAINT `item_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`delivery_address_id`) REFERENCES `customer_address_book` (`id`),
  ADD CONSTRAINT `orders_ibfk_4` FOREIGN KEY (`package_id`) REFERENCES `shopping_cart_has_items` (`id`);

--
-- Constraints for table `order_status`
--
ALTER TABLE `order_status`
  ADD CONSTRAINT `order_status_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Constraints for table `restaurant`
--
ALTER TABLE `restaurant`
  ADD CONSTRAINT `restaurant_ibfk_1` FOREIGN KEY (`location_id`) REFERENCES `available_in_location` (`id`),
  ADD CONSTRAINT `restaurant_ibfk_2` FOREIGN KEY (`vendor_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `shopping_cart`
--
ALTER TABLE `shopping_cart`
  ADD CONSTRAINT `shopping_cart_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `shopping_cart_has_items`
--
ALTER TABLE `shopping_cart_has_items`
  ADD CONSTRAINT `shopping_cart_has_items_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`),
  ADD CONSTRAINT `shopping_cart_has_items_ibfk_2` FOREIGN KEY (`shopping_cart_id`) REFERENCES `shopping_cart` (`id`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
