-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 08, 2026 at 10:53 PM
-- Server version: 11.8.6-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u957237009_nkactusadmin`
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `category` varchar(100) NOT NULL,
  `image` varchar(255) NOT NULL,
  `date_added` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `category`, `image`, `date_added`) VALUES
(1, 'Fiddle Leaf Fig', 'Popular indoor tree with large violin-shaped leaves', 89.00, 'Indoor Plants', 'fiddle-leaf-fig.jpg', '2026-04-08'),
(2, 'Monstera Deliciosa', 'Split-leaf tropical plant perfect for modern decor', 65.00, 'Indoor Plants', 'monstera.jpg', '2026-04-08'),
(3, 'Snake Plant', 'Low-maintenance plant that thrives in low light', 45.00, 'Indoor Plants', 'snake-plant.jpg', '2026-04-08'),
(4, 'Golden Pothos', 'Trailing vine plant ideal for beginners', 32.00, 'Vines', 'pothos.jpg', '2026-04-08'),
(5, 'Peace Lily', 'Flowering plant known for air-purifying qualities', 48.00, 'Flowering Plants', 'peace-lily.jpg', '2026-04-08'),
(6, 'Bird of Paradise', 'Tall tropical plant with dramatic foliage', 110.00, 'Indoor Plants', 'bird-of-paradise.jpg', '2026-04-08'),
(7, 'Jade Plant', 'Succulent plant with thick glossy leaves', 28.00, 'Succulents', 'jade-plant.jpg', '2026-04-08'),
(8, 'ZZ Plant', 'Extremely hardy plant perfect for low-light spaces', 52.00, 'Indoor Plants', 'zz-plant.jpg', '2026-04-08'),
(9, 'Alocasia Polly', 'Tropical plant with bold arrow-shaped leaves', 72.00, 'Indoor Plants', 'alocasia.jpg', '2026-04-08'),
(10, 'Calathea Orbifolia', 'Decorative plant with striped round leaves', 58.00, 'Indoor Plants', 'calathea.jpg', '2026-04-08'),
(11, 'Pink Princess Philodendron', 'Rare plant with pink variegated leaves', 145.00, 'Indoor Plants', 'philodendron.jpg', '2026-04-08'),
(12, 'Rubber Plant', 'Glossy leaves with a bold modern look', 55.00, 'Indoor Plants', 'rubber-plant.jpg', '2026-04-08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
