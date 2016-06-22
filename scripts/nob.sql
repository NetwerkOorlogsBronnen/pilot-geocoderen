-- phpMyAdmin SQL Dump
-- version 4.4.10
-- http://www.phpmyadmin.net
--
-- Host: localhost:8889
-- Generation Time: Jun 22, 2016 at 09:27 AM
-- Server version: 5.5.42
-- PHP Version: 5.6.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `niod_nob`
--

-- --------------------------------------------------------

--
-- Table structure for table `oai_harvest`
--

CREATE TABLE `oai_harvest` (
  `id` int(11) NOT NULL,
  `identifier` char(255) NOT NULL,
  `datestamp` datetime DEFAULT NULL,
  `recordxml` text,
  `title` text,
  `dates` text,
  `subject` text,
  `coverage` text,
  `provenance` varchar(50) DEFAULT NULL,
  `dc_id` varchar(255) DEFAULT NULL,
  `json` text,
  `description` text,
  `thumb` varchar(255) NOT NULL,
  `is_shown_at` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `o_x_t`
--

CREATE TABLE `o_x_t` (
  `id` int(11) NOT NULL,
  `term_id` int(11) NOT NULL,
  `identifier` varchar(25) NOT NULL,
  `collection` varchar(25) NOT NULL,
  `found_in` varchar(20) NOT NULL,
  `in_title` int(11) NOT NULL,
  `in_description` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `terms`
--

CREATE TABLE `terms` (
  `id` int(11) NOT NULL,
  `term` varchar(255) NOT NULL,
  `found_in` varchar(20) NOT NULL,
  `geocoded` enum('no','yes') NOT NULL,
  `gn_geocoded` enum('no','yes') NOT NULL,
  `result` enum('none','one','multiple','rejected') NOT NULL COMMENT 'rejected = false pos (former one)',
  `normalized` varchar(255) NOT NULL,
  `identifier` varchar(255) NOT NULL,
  `type` varchar(40) NOT NULL,
  `lat` float NOT NULL,
  `lon` float NOT NULL,
  `rec_count` int(11) NOT NULL,
  `rec_count_txt` int(11) NOT NULL,
  `rec_count_cov` int(11) NOT NULL,
  `rec_count_sub` int(11) NOT NULL,
  `h_place_name` varchar(255) NOT NULL,
  `h_place_uri` varchar(255) NOT NULL,
  `h_municipality_name` varchar(255) NOT NULL,
  `h_municipality_uri` varchar(255) NOT NULL,
  `h_province_name` varchar(255) NOT NULL,
  `h_province_uri` varchar(255) NOT NULL,
  `h_country_name` varchar(255) NOT NULL,
  `h_country_uri` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `oai_harvest`
--
ALTER TABLE `oai_harvest`
  ADD PRIMARY KEY (`id`),
  ADD KEY `identifier` (`identifier`),
  ADD KEY `provenance` (`provenance`);

--
-- Indexes for table `o_x_t`
--
ALTER TABLE `o_x_t`
  ADD PRIMARY KEY (`id`),
  ADD KEY `identifier` (`identifier`),
  ADD KEY `found_in` (`found_in`),
  ADD KEY `term_id` (`term_id`);

--
-- Indexes for table `terms`
--
ALTER TABLE `terms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `term` (`term`),
  ADD KEY `h_place_uri` (`h_place_uri`),
  ADD KEY `rec_count_txt` (`rec_count_txt`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `oai_harvest`
--
ALTER TABLE `oai_harvest`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `o_x_t`
--
ALTER TABLE `o_x_t`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `terms`
--
ALTER TABLE `terms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
