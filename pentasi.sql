-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 24, 2024 at 08:13 PM
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
-- Database: `pentasi`
--

-- --------------------------------------------------------

--
-- Table structure for table `commande`
--

CREATE TABLE `commande` (
  `ID_o` int(11) NOT NULL,
  `date_o` date DEFAULT NULL,
  `satut_o` enum('valider','en cours','en attand') DEFAULT NULL,
  `id_p` int(11) DEFAULT NULL,
  `id_c` int(11) DEFAULT NULL,
  `id_e` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `ID_c` int(11) NOT NULL,
  `nom_c` varchar(40) DEFAULT NULL,
  `address_c` varchar(40) DEFAULT NULL,
  `email_c` varchar(40) DEFAULT NULL,
  `nu_phone` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`ID_c`, `nom_c`, `address_c`, `email_c`, `nu_phone`) VALUES
(3, 'Yasser Hammouda', 'Ghardaia,alger', 'hammoudayaser40@gmail.com', 549448527),
(4, 'Yasser Hammouda', 'Ghardaia,alger', 'hammoudayaser40@gmail.com', 549448527),
(5, 'TheLittle Village', 'fdslgker', 'thelittlevillage01@gmail.com', 32545);

-- --------------------------------------------------------

--
-- Table structure for table `expidition`
--

CREATE TABLE `expidition` (
  `ID_e` int(11) NOT NULL,
  `date_e` date DEFAULT NULL,
  `address_e` varchar(40) DEFAULT NULL,
  `statut_e` enum('livré','en attand') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_products`
--

CREATE TABLE `order_products` (
  `id_p` int(11) DEFAULT NULL,
  `id_c` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `ID_p` int(11) NOT NULL,
  `descri_p` varchar(40) DEFAULT NULL,
  `prix` double DEFAULT NULL,
  `cate_p` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`ID_o`),
  ADD KEY `ké_co` (`id_c`),
  ADD KEY `ké_e` (`id_e`),
  ADD KEY `ké_pr` (`id_p`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`ID_c`);

--
-- Indexes for table `expidition`
--
ALTER TABLE `expidition`
  ADD PRIMARY KEY (`ID_e`);

--
-- Indexes for table `order_products`
--
ALTER TABLE `order_products`
  ADD KEY `ké_o_p` (`id_p`),
  ADD KEY `ké_o_c` (`id_c`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`ID_p`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `commande`
--
ALTER TABLE `commande`
  MODIFY `ID_o` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `ID_c` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `expidition`
--
ALTER TABLE `expidition`
  MODIFY `ID_e` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `ID_p` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `ké_co` FOREIGN KEY (`id_c`) REFERENCES `customer` (`ID_c`),
  ADD CONSTRAINT `ké_e` FOREIGN KEY (`id_e`) REFERENCES `expidition` (`ID_e`),
  ADD CONSTRAINT `ké_pr` FOREIGN KEY (`id_p`) REFERENCES `product` (`ID_p`);

--
-- Constraints for table `order_products`
--
ALTER TABLE `order_products`
  ADD CONSTRAINT `ké_o_c` FOREIGN KEY (`id_c`) REFERENCES `customer` (`ID_c`),
  ADD CONSTRAINT `ké_o_p` FOREIGN KEY (`id_p`) REFERENCES `product` (`ID_p`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
