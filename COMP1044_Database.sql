-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 28, 2025 at 02:59 PM
-- Server version: 5.7.24
-- PHP Version: 8.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `coursework`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `Customer_id` int(11) NOT NULL,
  `User_id` int(11) DEFAULT NULL,
  `Name` varchar(100) NOT NULL,
  `Company` varchar(100) DEFAULT NULL,
  `Email` varchar(100) NOT NULL,
  `Phone_no` varchar(10) DEFAULT NULL,
  `Address` text,
  `Lead_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`Customer_id`, `User_id`, `Name`, `Company`, `Email`, `Phone_no`, `Address`, `Lead_id`) VALUES
(1, 1, 'Alice Johnson', 'Alpha Corp', 'contact@alphacorp.com', '9876543210', '123 Alpha Street', NULL),
(2, 3, 'Brian Smith', 'Beta Solutions', 'info@betasolutions.com', '9876543211', '456 Beta Avenue', NULL),
(3, 5, 'Daniel Kim', 'Delta Enterprises', 'support@deltaenterprises.com', '9876543213', '321 Delta Blvd', NULL),
(4, 6, 'Ella Brown', 'Epsilon Tech', 'hello@epsilontech.com', '9876543214', '654 Epsilon Lane', NULL),
(5, 8, 'Grace Wilson', 'Eta Health', 'info@etahealth.com', '9876543216', '147 Eta Plaza', NULL),
(6, 9, 'Henry Davis', 'Theta Communications', 'sales@thetacomms.com', '9876543217', '258 Theta Street', NULL),
(7, 11, 'Jack Martinez', 'Kappa Foods', 'hello@kappafoods.com', '9876543219', '159 Kappa Street', NULL),
(8, 12, 'Kylie Robinson', 'Lambda Logistics', 'contact@lambdalogistics.com', '9876543220', '753 Lambda Avenue', NULL),
(9, 14, 'Mia Rodriguez', 'Nu Builders', 'sales@nubuilders.com', '9876543222', '951 Nu Lane', NULL),
(10, 15, 'Noah Lewis', 'Xi Digital', 'support@xidigital.com', '9876543223', '357 Xi Circle', NULL),
(11, 1, 'Paul Hall', 'Pi Financial', 'contact@pifinancial.com', '9876543225', '147 Pi Street', NULL),
(12, 18, 'Quinn Allen', 'Rho Fitness', 'info@rhofitness.com', '9876543226', '369 Rho Market', NULL),
(13, 19, 'Ruby Young', 'Sigma Travels', 'sales@sigmatravels.com', '9876543227', '753 Sigma Plaza', NULL),
(14, 20, 'Samuel Hernandez', 'Tau Technologies', 'support@tautech.com', '9876543228', '951 Tau Street', NULL),
(15, 2, 'Tina King', 'Upsilon Ventures', 'hello@upsilonventures.com', '9876543229', '654 Upsilon Avenue', NULL),
(17, 1, 'Emma Johnson', 'Maple Tech', 'emma.johnson@mapletech.com', '5551234567', '45 Maple Street, Toronto', NULL),
(19, 1, 'Liam Smith', 'Oakwood Solutions', 'liam.smith@oakwoodsol.com', '5552345678', '78 Oak Avenue, Vancouver', 26),
(20, 1, 'Sophia Brown', 'Pine Systems', 'sophia.brown@pinesys.com', '5553456789', '12 Pine Road, Calgary', NULL),
(21, 1, 'Noah Williams', 'Birch Innovations', 'noah.williams@birchinnov.com', '5554567890', '	89 Birch Lane, Edmonton', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `interaction`
--

CREATE TABLE `interaction` (
  `Interaction_id` int(11) NOT NULL,
  `Customer_id` int(11) DEFAULT NULL,
  `User_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `type` varchar(10) NOT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `interaction`
--

INSERT INTO `interaction` (`Interaction_id`, `Customer_id`, `User_id`, `date`, `type`, `description`) VALUES
(1, 1, 1, '2025-04-01', 'Call', 'Discussed initial requirements'),
(2, 2, 3, '2025-04-02', 'Email', 'Sent proposal details'),
(4, 4, 5, '2025-04-04', 'Call', 'Follow-up on pricing'),
(5, 5, 6, '2025-04-05', 'Email', 'Shared additional documents'),
(7, 7, 8, '2025-04-07', 'Call', 'Scheduled next steps'),
(8, 8, 9, '2025-04-08', 'Email', 'Clarified project timeline'),
(10, 10, 11, '2025-04-10', 'Call', 'Checked on decision-making process'),
(11, 11, 12, '2025-04-11', 'Email', 'Sent updated proposal'),
(13, 13, 14, '2025-04-13', 'Call', 'Discussed technical doubts'),
(14, 14, 15, '2025-04-14', 'Email', 'Follow-up after product demo'),
(17, 17, 18, '2025-04-17', 'Email', 'Confirmed payment process'),
(18, 18, 19, '2025-04-18', 'Meeting', 'Kickoff meeting scheduled'),
(19, 19, 20, '2025-04-19', 'Call', 'Confirmed delivery expectations'),
(20, 20, 2, '2025-04-20', 'Email', 'Closed the deal and next steps'),
(21, 5, 1, '2025-04-08', 'Meeting', 'talked about product'),
(22, 11, 1, '2025-05-05', 'Email', 'Email details');

-- --------------------------------------------------------

--
-- Table structure for table `lead`
--

CREATE TABLE `lead` (
  `Lead_id` int(11) NOT NULL,
  `User_id` int(11) NOT NULL,
  `Status` varchar(20) NOT NULL,
  `follow_up_date` date DEFAULT NULL,
  `lead_notes` text,
  `Customer_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lead`
--

INSERT INTO `lead` (`Lead_id`, `User_id`, `Status`, `follow_up_date`, `lead_notes`, `Customer_id`) VALUES
(1, 1, 'In Progress', '2025-05-05', 'New inquiry about services, follow-up scheduled', NULL),
(2, 3, 'New', '2025-05-06', 'Potential customer - asked for a call back', NULL),
(3, 6, 'New', '2025-05-07', 'Wants product details, follow-up pending', NULL),
(4, 1, 'New', '2025-05-08', 'Interested in pricing options, will call back', NULL),
(5, 11, 'New', '2025-05-09', 'Needs a quotation sent, waiting for confirmation', NULL),
(6, 5, 'In progress', '2025-05-10', 'Follow up for second meeting', NULL),
(7, 8, 'In progress', '2025-05-12', 'Demo scheduled', NULL),
(8, 12, 'In progress', '2025-05-15', 'Negotiation discussion', NULL),
(9, 15, 'In progress', '2025-05-18', 'Send updated proposal', NULL),
(10, 18, 'In progress', '2025-05-20', 'Final discussion on contract', NULL),
(11, 14, 'In progress', '2025-04-10', 'Missed follow-up call', NULL),
(12, 17, 'In progress', '2025-04-12', 'Customer needed update', NULL),
(13, 1, 'In progress', '2025-04-14', 'Forgot to send proposal', NULL),
(14, 20, 'In progress', '2025-04-16', 'Requested demo, not followed up', NULL),
(15, 2, 'In progress', '2025-04-18', 'Meeting rescheduling needed', NULL),
(22, 1, 'Closed', '2025-04-22', 'Product Completed', 16),
(25, 1, 'Closed', '2025-04-01', 'Completed', 18),
(26, 1, 'Closed', '2025-04-04', 'Product launched', 19),
(27, 1, 'New', '2025-05-08', 'Discussion of product', NULL),
(28, 1, 'In Progress', '2025-05-10', 'Product Design', NULL),
(29, 1, 'In Progress', '2025-05-06', 'Done', NULL),
(30, 1, 'Closed', '2025-04-28', 'Closed lead with Nottingham', 22),
(31, 1, 'In Progress', '2025-05-08', 'Meeting', NULL),
(32, 1, 'Closed', '2025-04-27', 'Closed deal', 23);

-- --------------------------------------------------------

--
-- Table structure for table `reminder`
--

CREATE TABLE `reminder` (
  `Reminder_id` int(11) NOT NULL,
  `User_id` int(11) DEFAULT NULL,
  `Lead_id` int(11) DEFAULT NULL,
  `Date_sent` date DEFAULT NULL,
  `Message` varchar(500) DEFAULT NULL,
  `Reminder_status` enum('Pending','Done') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `reminder`
--

INSERT INTO `reminder` (`Reminder_id`, `User_id`, `Lead_id`, `Date_sent`, `Message`, `Reminder_status`) VALUES
(1, 1, 1, '2025-04-01', 'Follow up with lead regarding proposal.', 'Done'),
(2, 2, 2, '2025-04-02', 'Send updated pricing details.', 'Pending'),
(3, 3, 3, '2025-04-03', 'Confirm meeting schedule.', 'Done'),
(4, 1, 4, '2025-04-04', 'Discuss project requirements.', 'Pending'),
(5, 5, 5, '2025-04-05', 'Follow up on feedback.', 'Done'),
(6, 6, 6, '2025-04-06', 'Send technical documents.', 'Pending'),
(7, 7, 7, '2025-04-07', 'Set up second meeting.', 'Done'),
(8, 8, 8, '2025-04-08', 'Follow up about budget approval.', 'Pending'),
(9, 1, 9, '2025-04-09', 'Confirm order placement.', 'Done'),
(10, 10, 10, '2025-04-10', 'Check if more documents needed.', 'Pending'),
(11, 11, 11, '2025-04-11', 'Send thank you note.', 'Done'),
(12, 12, 12, '2025-04-12', 'Finalize contract details.', 'Pending'),
(13, 13, 13, '2025-04-13', 'Ask about project timeline.', 'Done'),
(14, 14, 14, '2025-04-14', 'Send demo recording.', 'Pending'),
(15, 15, 15, '2025-04-15', 'Confirm delivery address.', 'Done'),
(16, 16, 16, '2025-04-16', 'Request approval for final version.', 'Pending'),
(17, 17, 17, '2025-04-17', 'Clarify payment terms.', 'Done'),
(18, 1, 18, '2025-04-18', 'Schedule kickoff meeting.', 'Pending'),
(19, 19, 19, '2025-04-19', 'Send follow-up email.', 'Done'),
(20, 20, 20, '2025-04-20', 'Confirm contract signing.', 'Pending'),
(22, 1, 26, '2025-04-01', 'Reminder', 'Pending'),
(23, 1, 26, '2025-05-01', 'Call', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `User_id` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Phone_no` varchar(10) DEFAULT NULL,
  `Password` varchar(50) DEFAULT '000',
  `Role` enum('Admin','Sales') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`User_id`, `Name`, `Email`, `Phone_no`, `Password`, `Role`) VALUES
(1, 'Navya Binoy', 'hcynv2@nottingham.edu.my', '12345677', 'navya123', 'Sales'),
(2, 'shreya', 'shreya@gmail.com', '011234987', '000', 'Admin'),
(3, 'Yumna', 'yumnakif@yahoo.com', '011238758', '12345', 'Sales'),
(4, 'new', 'newuser@gmail.com', '0542348541', 'newuser123', 'Sales'),
(5, 'Alice Johnson', 'alice.johnson@gmail.com', '5551234567', '000', 'Sales'),
(6, 'Bob Smith', 'bob.smith@gmail.com', '5552345678', '000', 'Admin'),
(7, 'Carol Adams', 'carol.adams@gmail.com', '5553456789', '000', 'Sales'),
(8, 'David Turner', 'david.turner@yahoo.com', '5554567890', '000', 'Sales'),
(9, 'Ella Roberts', 'ella.roberts@yahoo.com', '5555678901', '000', 'Admin'),
(10, 'Frank Nelson', 'frank.nelson@yahoo.com', '5556789012', '000', 'Sales'),
(11, 'Grace Lewis', 'grace.lewis@yahoo.com', '5557890123', '000', 'Sales'),
(12, 'Henry Clark', 'henry.clark@gmail.com', '5558901234', '000', 'Admin'),
(13, 'Irene Martinez', 'irene.martinez@yahoo.com', '5559012345', '000', 'Sales'),
(14, 'Jack Hall', 'jack.hall@yahoo.com', '5550123456', '000', 'Sales'),
(15, 'Karen Baker', 'karen.baker@gmail.com', '5551234568', '000', 'Admin'),
(16, 'Leo Walker', 'leo.walker@gmail.com', '5552345679', '000', 'Sales'),
(17, 'Mia Allen', 'mia.allen@gmail.com', '5553456780', '000', 'Sales'),
(18, 'Noah Wright', 'noah.wright@gmail.com', '5554567891', '000', 'Sales'),
(19, 'Olivia Scott', 'olivia.scott@gmail.com', '5555678902', '000', 'Admin'),
(20, 'Peter King', 'peter.king@gmaile.com', '5556789013', '000', 'Sales'),
(21, 'Quinn Young', 'quinn.young@yahoo.com', '5557890124', '000', 'Sales'),
(22, 'Rachel Hill', 'rachel.hill@yahoo.com', '5558901235', '000', 'Admin'),
(23, 'Sam Green', 'sam.green@yahoo.com', '5559012346', '000', 'Sales'),
(24, 'Tina Reed', 'tina.reed@gmail.com', '5550123457', '000', 'Sales');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`Customer_id`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD KEY `User_id` (`User_id`),
  ADD KEY `fk_customer_lead` (`Lead_id`);

--
-- Indexes for table `interaction`
--
ALTER TABLE `interaction`
  ADD PRIMARY KEY (`Interaction_id`),
  ADD KEY `User_id` (`User_id`),
  ADD KEY `Customer_id` (`Customer_id`);

--
-- Indexes for table `lead`
--
ALTER TABLE `lead`
  ADD PRIMARY KEY (`Lead_id`),
  ADD KEY `fk_lead_user` (`User_id`),
  ADD KEY `fk_customer` (`Customer_id`);

--
-- Indexes for table `reminder`
--
ALTER TABLE `reminder`
  ADD PRIMARY KEY (`Reminder_id`),
  ADD KEY `User_id` (`User_id`),
  ADD KEY `Lead_id` (`Lead_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`User_id`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `Customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1028;

--
-- AUTO_INCREMENT for table `interaction`
--
ALTER TABLE `interaction`
  MODIFY `Interaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `lead`
--
ALTER TABLE `lead`
  MODIFY `Lead_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `reminder`
--
ALTER TABLE `reminder`
  MODIFY `Reminder_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `User_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
