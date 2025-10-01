-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 01, 2025 at 02:34 PM
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
-- Database: `hr_system1`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `Username` varchar(255) DEFAULT NULL,
  `passward` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `Username`, `passward`) VALUES
(1, 'admin12', '$2a$12$I3I33mQWB8X.x0RkXAKbfuQDFDZdbeq6PbB2m5s4/EzoXfKElIz1S');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `attendance_date` date NOT NULL,
  `status` enum('Present','Absent') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `employee_id`, `attendance_date`, `status`) VALUES
(1, 2, '2025-02-09', 'Present'),
(3, 4, '2025-10-01', 'Present'),
(4, 1, '2025-10-01', 'Absent');

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`id`, `name`) VALUES
(1, 'HR'),
(2, 'Finance'),
(3, 'IT'),
(4, 'Sales'),
(5, 'Project Manager');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `id` int(11) NOT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `hire_date` date DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `salary` decimal(10,4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`id`, `firstname`, `lastname`, `hire_date`, `department_id`, `salary`) VALUES
(1, 'Sara', 'Tariq', '2025-10-01', 5, 450.0000),
(2, 'Ahmad', 'Sami', '2024-01-05', 2, 300.0000),
(3, 'Mohammed', 'Ahmad', '2025-10-02', 2, 300.0000),
(4, 'Naser', 'Sami', '2025-10-01', 1, 400.0000);

-- --------------------------------------------------------

--
-- Table structure for table `leave_requests`
--

CREATE TABLE `leave_requests` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leave_requests`
--

INSERT INTO `leave_requests` (`id`, `employee_id`, `start_date`, `end_date`, `reason`, `status`) VALUES
(1, 1, '2025-09-29', '2025-09-30', 'I want to go to hospital', 'Pending'),
(2, 2, '2025-05-22', '2025-06-29', 'go to vacation', 'Approved'),
(3, 2, '2025-10-01', '2025-12-01', 'treatment ', 'Rejected');

-- --------------------------------------------------------

--
-- Table structure for table `payroll2`
--

CREATE TABLE `payroll2` (
  `employee_id` int(11) DEFAULT NULL,
  `payroll_date` date DEFAULT NULL,
  `basic_salary` decimal(10,4) DEFAULT NULL,
  `allowances` decimal(10,4) DEFAULT NULL,
  `Deductions` decimal(10,4) DEFAULT NULL,
  `net_salary` decimal(10,2) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payroll2`
--

INSERT INTO `payroll2` (`employee_id`, `payroll_date`, `basic_salary`, `allowances`, `Deductions`, `net_salary`, `id`) VALUES
(2, '2025-08-22', 300.0000, 16.0000, 20.0000, 296.00, 1),
(4, '2025-10-22', 400.0000, 10.0000, 10.0000, 400.00, 2),
(3, '2025-10-22', 300.0000, 10.0000, 10.0000, 300.00, 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `payroll2`
--
ALTER TABLE `payroll2`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `leave_requests`
--
ALTER TABLE `leave_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `payroll2`
--
ALTER TABLE `payroll2`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`id`);

--
-- Constraints for table `employee`
--
ALTER TABLE `employee`
  ADD CONSTRAINT `employee_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `department` (`id`);

--
-- Constraints for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD CONSTRAINT `leave_requests_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
