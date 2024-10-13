-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 13, 2024 at 05:58 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `booknook`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_detail`
--

CREATE TABLE `admin_detail` (
  `id` int(11) NOT NULL,
  `admin_name` varchar(255) NOT NULL,
  `admin_password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_detail`
--

INSERT INTO `admin_detail` (`id`, `admin_name`, `admin_password`) VALUES
(1, 'admin', '$2y$10$fCZtQ5G4cxAcANpKoc.G.eT4HwNmLkDx8qTVRBi85W.E1sUfdNLGW');

-- --------------------------------------------------------

--
-- Table structure for table `authors`
--

CREATE TABLE `authors` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `authors`
--

INSERT INTO `authors` (`id`, `name`, `image`) VALUES
(2, 'Hari Bansha Acharya', 'Hari Bansha Acharya.png'),
(3, 'Madan Krishna Shrestha', 'Madan Krishna Shrestha.png'),
(4, 'Narayan Wagle', 'Narayan Wagle.png'),
(5, 'Parijat', 'Parijat.png'),
(6, 'Diamond Shumsher Rana', 'Diamond Shumsher.png'),
(7, 'Saru Bhakta', 'Saru Bhakta.png'),
(8, 'Amar Neupane', 'Amar Neupane.png'),
(9, 'Laxmi Prasad Devkota', 'Laxmi Prasad Devkota.png'),
(11, 'Sanu Sharma', 'Sanu Sharma.png');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `favorites`
--

INSERT INTO `favorites` (`id`, `user_id`, `product_id`) VALUES
(7, 6, 6),
(9, 1, 6),
(10, 1, 9),
(11, 6, 11),
(23, 0, 4);

-- --------------------------------------------------------

--
-- Table structure for table `genres`
--

CREATE TABLE `genres` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `genres`
--

INSERT INTO `genres` (`id`, `name`) VALUES
(4, 'Comedy'),
(5, 'Drama'),
(6, 'Biography'),
(7, 'Novel'),
(8, 'History'),
(9, 'Epic Poetry');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `number` varchar(255) NOT NULL,
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`id`, `user_id`, `name`, `email`, `number`, `message`) VALUES
(6, 1, 'Sushil Lamichhane', 'khatiwadanischal2015@gmail.com', '9898989898', 'ghxkcgsd'),
(7, 1, 'Sushil Lamichhane', 'lamichhanesushil56@gmail.com', '9886379409', 'this order is not received\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `placed_on` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `number` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `method` varchar(255) NOT NULL,
  `total_products` varchar(255) NOT NULL,
  `total_price` varchar(255) NOT NULL,
  `payment_status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `placed_on`, `name`, `number`, `email`, `address`, `method`, `total_products`, `total_price`, `payment_status`) VALUES
(1, 1, '23-Sep-2024', 'Sushil Lamichhane', '9886379409', 'lamichhanesushil56@gmail.com', '  Bhorle, Rasuwa', 'esewa', 'Seto Baagh (1) ', '1200', 'Completed'),
(2, 1, '24-Sep-2024', 'Sushil Lamichhane', '9863794096', 'lamichhanesushil56@gmail.com', '  Maitighar, Kathmandu', 'esewa', 'Palpasa cafe (1) ', '1450', 'Completed'),
(11, 1, '30-Sep-2024', 'Sushil Lamichhane', '9886379409', 'lamichhanesushil56@gmail.com', '  Bharta, Makwanpur', 'cash on delivery', 'China Harayeko Manchhe (1) ', '550', 'Completed'),
(16, 1, '05-Oct-2024', 'Sushil Lamichhane', '9886379409', 'lamichhanesushil56@gmail.com', '  Syafru Besi, Rasuwa', 'cash on delivery', 'Muna Madan (1) ', '200', 'Completed'),
(17, 6, '08-Oct-2024', 'Sushil Lamichhane', '9886379409', 'lamichhanesushil56@gmail.com', '  Khairahani, Chitwan', 'esewa', 'Bash and Lucy (1) ', '150', 'Completed');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `genre` varchar(255) NOT NULL,
  `price` varchar(255) NOT NULL,
  `author_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `stocks` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `genre`, `price`, `author_id`, `image`, `stocks`) VALUES
(4, 'China Harayeko Manchhe', '4', '550', 2, 'china_harayeko_manxe.jpg', '10'),
(6, 'Maha ko Ma', '4', '550', 3, 'maha ko ma.png', '10'),
(7, 'Palpasa cafe', '7', '750', 4, 'Palpasa Cafe.png', '149'),
(9, 'Seto Baagh', '8', '1200', 6, 'Seto Baagh.png', '20'),
(10, 'Seto Dharti', '7', '1500', 8, 'Seto Dharti.png', '450'),
(11, 'Muna Madan', '9', '200', 9, 'Muna Madan.png', '448'),
(13, 'Pagal Basti', '7', '1234', 7, 'Pagal Basti.png', '225'),
(15, 'Eka Deshma', '7', '170', 11, 'Ekadeshma.png', '1200'),
(16, 'Bash and Lucy', '7', '150', 7, 'Palpasa Cafe.png', '49');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `review_text` varchar(255) NOT NULL,
  `rating` int(11) NOT NULL,
  `review_image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `user_name`, `review_text`, `rating`, `review_image`) VALUES
(1, 6, 'sushil', 'This is one of the best websites', 5, ''),
(2, 1, 'Guest', 'done', 4, '');

-- --------------------------------------------------------

--
-- Table structure for table `user_details`
--

CREATE TABLE `user_details` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `contact_number` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `Status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_details`
--

INSERT INTO `user_details` (`id`, `username`, `email`, `contact_number`, `password`, `Status`) VALUES
(6, 'sushil', 'lamichhanesushil56@gmail.com', '9886379409', 'b1c3d6a4a96393a4', 'Active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_detail`
--
ALTER TABLE `admin_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `authors`
--
ALTER TABLE `authors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_details`
--
ALTER TABLE `user_details`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_detail`
--
ALTER TABLE `admin_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `authors`
--
ALTER TABLE `authors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `genres`
--
ALTER TABLE `genres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_details`
--
ALTER TABLE `user_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
