-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 04, 2026 at 02:41 AM
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
-- Database: `shepherd`
--

-- --------------------------------------------------------

--
-- Table structure for table `coordinators`
--

CREATE TABLE `coordinators` (
  `faculty_id` varchar(30) NOT NULL,
  `coordinator_name` varchar(150) NOT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `coordinators`
--

INSERT INTO `coordinators` (`faculty_id`, `coordinator_name`, `contact_number`, `email`, `is_active`, `created_at`, `updated_at`) VALUES
('20SAI51', 'Lenin A', '9876543210', 'lenin@mail.sjctni.edu', 1, '2026-10-03 14:36:05', '2026-10-03 14:36:05');

-- --------------------------------------------------------

--
-- Table structure for table `group_members`
--

CREATE TABLE `group_members` (
  `group_id` int(10) UNSIGNED NOT NULL,
  `regno` varchar(30) NOT NULL,
  `is_leader` tinyint(1) NOT NULL DEFAULT 0,
  `joined_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `group_members`
--

INSERT INTO `group_members` (`group_id`, `regno`, `is_leader`, `joined_at`) VALUES
(1, '25PAI818', 0, '2026-10-03 14:43:34'),
(1, '25PAI821', 1, '2026-10-03 14:43:34'),
(1, '25PAI822', 0, '2026-10-03 14:43:34');

-- --------------------------------------------------------

--
-- Table structure for table `shepherd_groups`
--

CREATE TABLE `shepherd_groups` (
  `group_id` int(10) UNSIGNED NOT NULL,
  `group_name` varchar(150) NOT NULL,
  `village_name` varchar(50) DEFAULT NULL,
  `orientation_date` date DEFAULT NULL,
  `one_day_visit` date DEFAULT NULL,
  `camp_date` date DEFAULT NULL,
  `total_hours` decimal(6,2) NOT NULL DEFAULT 0.00,
  `completed_hours` decimal(6,2) NOT NULL DEFAULT 0.00,
  `faculty_id` varchar(30) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shepherd_groups`
--

INSERT INTO `shepherd_groups` (`group_id`, `group_name`, `village_name`, `orientation_date`, `one_day_visit`, `camp_date`, `total_hours`, `completed_hours`, `faculty_id`, `created_at`, `updated_at`) VALUES
(1, 'Education', 'Pillaimanagar', '2026-10-05', '2026-10-10', '2026-11-15', 70.00, 10.00, '20SAI51', '2026-10-03 14:38:33', '2026-10-03 17:44:18'),
(2, 'Environment', 'Pillaimanagar', '2026-10-06', '2026-10-11', '2026-11-16', 70.00, 0.00, '20SAI51', '2026-10-03 14:38:33', '2026-10-03 14:52:34'),
(3, 'Health', 'Pillaimanagar', '2026-10-07', '2026-10-12', '2026-11-17', 70.00, 0.00, '20SAI51', '2026-10-03 14:38:33', '2026-10-03 14:52:38');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `regno` varchar(30) NOT NULL,
  `stud_name` varchar(150) NOT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `dept` varchar(100) NOT NULL,
  `year` varchar(20) NOT NULL,
  `section` varchar(20) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`regno`, `stud_name`, `contact_number`, `dept`, `year`, `section`, `created_at`, `updated_at`) VALUES
('25PAI818', 'William James', '9876543210', 'AI', 'II', 'A', '2026-10-03 14:41:52', '2026-10-03 14:41:52'),
('25PAI821', 'Jeeva', '9876543211', 'AI', 'II', 'A', '2026-10-03 14:41:52', '2026-10-03 14:41:52'),
('25PAI822', 'Side Savari', '9876543212', 'AI', 'II', 'A', '2026-10-03 14:41:52', '2026-10-03 14:41:52');

-- --------------------------------------------------------

--
-- Table structure for table `visit_bills`
--

CREATE TABLE `visit_bills` (
  `bill_id` int(10) UNSIGNED NOT NULL,
  `visit_id` int(10) UNSIGNED NOT NULL,
  `bill_proof_path` varchar(500) DEFAULT NULL,
  `bill_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `bill_status` varchar(20) NOT NULL DEFAULT 'pending',
  `transaction_id` varchar(150) DEFAULT NULL,
  `transaction_date` date DEFAULT NULL,
  `verified_by` varchar(30) DEFAULT NULL,
  `verified_at` datetime DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `visit_bills`
--

INSERT INTO `visit_bills` (`bill_id`, `visit_id`, `bill_proof_path`, `bill_amount`, `bill_status`, `transaction_id`, `transaction_date`, `verified_by`, `verified_at`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 5, 'uploads/bill_5_1791029015.pdf', 100.00, 'approved', NULL, NULL, '20SAI51', '2026-10-03 17:44:18', '', '2026-10-03 17:33:35', '2026-10-03 17:44:18');

-- --------------------------------------------------------

--
-- Table structure for table `visit_plans`
--

CREATE TABLE `visit_plans` (
  `visit_id` int(10) UNSIGNED NOT NULL,
  `group_id` int(10) UNSIGNED NOT NULL,
  `faculty_id` varchar(30) NOT NULL,
  `date_of_visit` date NOT NULL,
  `objective` text NOT NULL,
  `plan` text NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `remarks` text DEFAULT NULL,
  `allocated_hours` decimal(6,2) NOT NULL DEFAULT 0.00,
  `created_by` varchar(30) NOT NULL,
  `approved_by` varchar(30) DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `verified_by` varchar(30) DEFAULT NULL,
  `verified_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `visit_plans`
--

INSERT INTO `visit_plans` (`visit_id`, `group_id`, `faculty_id`, `date_of_visit`, `objective`, `plan`, `status`, `remarks`, `allocated_hours`, `created_by`, `approved_by`, `approved_at`, `verified_by`, `verified_at`, `created_at`, `updated_at`) VALUES
(2, 1, '20SAI51', '2026-10-04', 'test', 'test', 'pending', NULL, 0.00, '25PAI821', NULL, NULL, NULL, NULL, '2026-10-03 16:35:35', '2026-10-03 16:35:35'),
(5, 1, '20SAI51', '2026-10-04', 'test', 'test', 'verified', NULL, 10.00, '25PAI821', '20SAI51', '2026-10-03 17:04:01', '20SAI51', '2026-10-03 17:44:18', '2026-10-03 16:49:28', '2026-10-03 17:44:18');

-- --------------------------------------------------------

--
-- Table structure for table `visit_proofs`
--

CREATE TABLE `visit_proofs` (
  `proof_id` int(10) UNSIGNED NOT NULL,
  `visit_id` int(10) UNSIGNED NOT NULL,
  `proof_type` varchar(30) NOT NULL DEFAULT 'visit',
  `image_path` varchar(500) NOT NULL,
  `uploaded_by` varchar(30) NOT NULL,
  `uploaded_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `visit_proofs`
--

INSERT INTO `visit_proofs` (`proof_id`, `visit_id`, `proof_type`, `image_path`, `uploaded_by`, `uploaded_at`) VALUES
(1, 5, 'pdf', 'uploads/visit_5_1791029015.pdf', '25PAI821', '2026-10-03 17:33:35');

-- --------------------------------------------------------

--
-- Table structure for table `visit_submissions`
--

CREATE TABLE `visit_submissions` (
  `submission_id` int(10) UNSIGNED NOT NULL,
  `visit_id` int(10) UNSIGNED NOT NULL,
  `submitted_by` varchar(30) NOT NULL,
  `submitted_at` datetime NOT NULL DEFAULT current_timestamp(),
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `visit_submissions`
--

INSERT INTO `visit_submissions` (`submission_id`, `visit_id`, `submitted_by`, `submitted_at`, `notes`) VALUES
(1, 5, '25PAI821', '2026-10-03 17:33:35', 'test');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `coordinators`
--
ALTER TABLE `coordinators`
  ADD PRIMARY KEY (`faculty_id`);

--
-- Indexes for table `group_members`
--
ALTER TABLE `group_members`
  ADD PRIMARY KEY (`group_id`,`regno`),
  ADD KEY `idx_group_members_regno` (`regno`);

--
-- Indexes for table `shepherd_groups`
--
ALTER TABLE `shepherd_groups`
  ADD PRIMARY KEY (`group_id`),
  ADD KEY `idx_groups_faculty` (`faculty_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`regno`);

--
-- Indexes for table `visit_bills`
--
ALTER TABLE `visit_bills`
  ADD PRIMARY KEY (`bill_id`),
  ADD UNIQUE KEY `uq_bill_visit` (`visit_id`),
  ADD KEY `idx_bill_status` (`bill_status`),
  ADD KEY `idx_bill_verified_by` (`verified_by`);

--
-- Indexes for table `visit_plans`
--
ALTER TABLE `visit_plans`
  ADD PRIMARY KEY (`visit_id`),
  ADD KEY `idx_visit_group` (`group_id`),
  ADD KEY `idx_visit_faculty` (`faculty_id`),
  ADD KEY `idx_visit_status` (`status`),
  ADD KEY `idx_visit_date` (`date_of_visit`),
  ADD KEY `idx_visit_created_by` (`created_by`),
  ADD KEY `fk_visit_approved_by` (`approved_by`),
  ADD KEY `fk_visit_verified_by` (`verified_by`);

--
-- Indexes for table `visit_proofs`
--
ALTER TABLE `visit_proofs`
  ADD PRIMARY KEY (`proof_id`),
  ADD KEY `idx_proofs_visit` (`visit_id`),
  ADD KEY `idx_proofs_uploaded_by` (`uploaded_by`);

--
-- Indexes for table `visit_submissions`
--
ALTER TABLE `visit_submissions`
  ADD PRIMARY KEY (`submission_id`),
  ADD UNIQUE KEY `uq_submission_visit` (`visit_id`),
  ADD KEY `idx_submission_student` (`submitted_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `shepherd_groups`
--
ALTER TABLE `shepherd_groups`
  MODIFY `group_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `visit_bills`
--
ALTER TABLE `visit_bills`
  MODIFY `bill_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `visit_plans`
--
ALTER TABLE `visit_plans`
  MODIFY `visit_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `visit_proofs`
--
ALTER TABLE `visit_proofs`
  MODIFY `proof_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `visit_submissions`
--
ALTER TABLE `visit_submissions`
  MODIFY `submission_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `group_members`
--
ALTER TABLE `group_members`
  ADD CONSTRAINT `fk_group_members_group` FOREIGN KEY (`group_id`) REFERENCES `shepherd_groups` (`group_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_group_members_student` FOREIGN KEY (`regno`) REFERENCES `students` (`regno`) ON UPDATE CASCADE;

--
-- Constraints for table `shepherd_groups`
--
ALTER TABLE `shepherd_groups`
  ADD CONSTRAINT `fk_groups_coordinator` FOREIGN KEY (`faculty_id`) REFERENCES `coordinators` (`faculty_id`) ON UPDATE CASCADE;

--
-- Constraints for table `visit_bills`
--
ALTER TABLE `visit_bills`
  ADD CONSTRAINT `fk_bill_verified_by` FOREIGN KEY (`verified_by`) REFERENCES `coordinators` (`faculty_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_bill_visit` FOREIGN KEY (`visit_id`) REFERENCES `visit_plans` (`visit_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `visit_plans`
--
ALTER TABLE `visit_plans`
  ADD CONSTRAINT `fk_visit_approved_by` FOREIGN KEY (`approved_by`) REFERENCES `coordinators` (`faculty_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_visit_created_by` FOREIGN KEY (`created_by`) REFERENCES `students` (`regno`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_visit_faculty` FOREIGN KEY (`faculty_id`) REFERENCES `coordinators` (`faculty_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_visit_group` FOREIGN KEY (`group_id`) REFERENCES `shepherd_groups` (`group_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_visit_verified_by` FOREIGN KEY (`verified_by`) REFERENCES `coordinators` (`faculty_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `visit_proofs`
--
ALTER TABLE `visit_proofs`
  ADD CONSTRAINT `fk_proof_student` FOREIGN KEY (`uploaded_by`) REFERENCES `students` (`regno`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_proof_visit` FOREIGN KEY (`visit_id`) REFERENCES `visit_plans` (`visit_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `visit_submissions`
--
ALTER TABLE `visit_submissions`
  ADD CONSTRAINT `fk_submission_student` FOREIGN KEY (`submitted_by`) REFERENCES `students` (`regno`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_submission_visit` FOREIGN KEY (`visit_id`) REFERENCES `visit_plans` (`visit_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
