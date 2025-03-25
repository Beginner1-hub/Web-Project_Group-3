-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 24, 2025 at 10:07 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `student_course_hub`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `AdminID` int(11) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `PasswordHash` varchar(255) NOT NULL,
  `Role` enum('SuperAdmin','ProgramManager','StudentManager') DEFAULT 'SuperAdmin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`AdminID`, `Username`, `PasswordHash`, `Role`) VALUES
(3, 'adminuser', '$2y$10$TGcyavSMaTRWM44cDF8.Ru2mtc2.ocA0lazbgdEU9Ti/bSU8vR.k6', 'SuperAdmin');

-- --------------------------------------------------------

--
-- Table structure for table `interestedstudents`
--

CREATE TABLE `interestedstudents` (
  `InterestID` int(11) NOT NULL,
  `ProgrammeID` int(11) NOT NULL,
  `StudentEmail` varchar(255) NOT NULL,
  `StudentName` varchar(255) DEFAULT NULL,
  `InterestedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `interestedstudents`
--

INSERT INTO `interestedstudents` (`InterestID`, `ProgrammeID`, `StudentEmail`, `StudentName`, `InterestedAt`) VALUES
(22, 1, 'susmabasnet653@gmail.com', 'Sushma Basnet', '2025-03-22 00:20:13'),
(23, 10, 'manoj@gmail.com', 'Manoj', '2025-03-22 00:21:11'),
(24, 2, 'gunjan@gmail.com', 'Gunjan', '2025-03-22 00:29:19'),
(28, 2, 'suman@gmail.com', 'Suman', '2025-03-22 00:59:30'),
(29, 6, 'manab@gmail.com', 'manab', '2025-03-22 01:52:45'),
(30, 2, 'susmabasnet653@gmail.com', 'Suman', '2025-03-22 09:52:13');

-- --------------------------------------------------------

--
-- Table structure for table `levels`
--

CREATE TABLE `levels` (
  `LevelID` int(11) NOT NULL,
  `LevelName` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `levels`
--

INSERT INTO `levels` (`LevelID`, `LevelName`) VALUES
(1, 'Undergraduate'),
(2, 'Postgraduate');

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE `modules` (
  `ModuleID` int(11) NOT NULL,
  `ModuleName` text NOT NULL,
  `ModuleLeaderID` int(11) DEFAULT NULL,
  `Description` text DEFAULT NULL,
  `Image` text DEFAULT NULL,
  `ModuleLeader` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`ModuleID`, `ModuleName`, `ModuleLeaderID`, `Description`, `Image`, `ModuleLeader`) VALUES
(1, 'Introduction to Programming  ', 1, 'Covers the fundamentals of programming using Python and Java.', '76.jpg', ''),
(2, 'Mathematics for Computer Science', 2, 'Teaches discrete mathematics, linear algebra, and probability theory.', '77.jpg', ''),
(3, 'Computer Systems & Architecture', 3, 'Explores CPU design, memory management, and assembly language.', '78.jpg', ''),
(4, 'Databases', 4, 'Covers SQL, relational database design, and NoSQL systems.', '79.jpg', ''),
(5, 'Software Engineering', 5, 'Focuses on agile development, design patterns, and project management.', '80.jpg', ''),
(6, 'Algorithms & Data Structures', 6, 'Examines sorting, searching, graphs, and complexity analysis.', '81.jpg', ''),
(7, 'Cyber Security Fundamentals', 7, 'Provides an introduction to network security, cryptography, and vulnerabilities.', '82.jpg', ''),
(8, 'Artificial Intelligence', 8, 'Introduces AI concepts such as neural networks, expert systems, and robotics.', '83.jpg', ''),
(9, 'Machine Learning', 9, 'Explores supervised and unsupervised learning, including decision trees and clustering.', '84.jpg', ''),
(10, 'Ethical Hacking', 10, 'Covers penetration testing, security assessments, and cybersecurity laws.', '85.jpg', ''),
(11, 'Computer Networks', 1, 'Teaches TCP/IP, network layers, and wireless communication.', '86.jpg', ''),
(12, 'Software Testing & Quality Assurance', 2, 'Focuses on automated testing, debugging, and code reliability.', '87.jpg', ''),
(13, 'Embedded Systems', 3, 'Examines microcontrollers, real-time OS, and IoT applications.', '88.jpg', ''),
(14, 'Human-Computer Interaction', 4, 'Studies UI/UX design, usability testing, and accessibility.', '89.jpg', ''),
(15, 'Blockchain Technologies', 5, 'Covers distributed ledgers, consensus mechanisms, and smart contracts.', '90.jpg', ''),
(16, 'Cloud Computing', 6, 'Introduces cloud services, virtualization, and distributed systems.', '91.jpg', ''),
(17, 'Digital Forensics', 7, 'Teaches forensic investigation techniques for cybercrime.', '92.jpg', ''),
(18, 'Final Year Project', 8, 'A major independent project where students develop a software solution.', '93.jpg', ''),
(19, 'Advanced Machine Learning', 11, 'Covers deep learning, reinforcement learning, and cutting-edge AI techniques.', '94.jpg', ''),
(20, 'Cyber Threat Intelligence', 12, 'Focuses on cybersecurity risk analysis, malware detection, and threat mitigation.', '95.jpg', ''),
(21, 'Big Data Analytics', 13, 'Explores data mining, distributed computing, and AI-driven insights.', '96.jpg', ''),
(22, 'Cloud & Edge Computing', 14, 'Examines scalable cloud platforms, serverless computing, and edge networks.', '97.jpg', ''),
(23, 'Blockchain & Cryptography', 15, 'Covers decentralized applications, consensus algorithms, and security measures.', '98.jpg', ''),
(24, 'AI Ethics & Society', 16, 'Analyzes ethical dilemmas in AI, fairness, bias, and regulatory considerations.', '99.jpg', ''),
(25, 'Quantum Computing', 17, 'Introduces quantum algorithms, qubits, and cryptographic applications.', '100.jpg', ''),
(26, 'Cybersecurity Law & Policy', 18, 'Explores digital privacy, GDPR, and international cyber law.', '30.jpg', ''),
(27, 'Neural Networks & Deep Learning', 19, 'Delves into convolutional networks, GANs, and AI advancements.', '73.jpeg', ''),
(28, 'Human-AI Interaction', 20, 'Studies AI usability, NLP systems, and social robotics.', '74.jpg', ''),
(29, 'Autonomous Systems', 11, 'Focuses on self-driving technology, robotics, and intelligent agents.', '72.jpg', ''),
(30, 'Digital Forensics & Incident Response', 12, 'Teaches forensic analysis, evidence gathering, and threat mitigation.', '71.jpg', ''),
(31, 'Postgraduate Dissertation', 13, 'A major research project where students explore advanced topics in computing.', '70.jpg', '');

-- --------------------------------------------------------

--
-- Table structure for table `programmemodules`
--

CREATE TABLE `programmemodules` (
  `ProgrammeModuleID` int(11) NOT NULL,
  `ProgrammeID` int(11) DEFAULT NULL,
  `ModuleID` int(11) DEFAULT NULL,
  `Year` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `programmemodules`
--

INSERT INTO `programmemodules` (`ProgrammeModuleID`, `ProgrammeID`, `ModuleID`, `Year`) VALUES
(1, 1, 1, 1),
(2, 1, 2, 1),
(3, 1, 3, 1),
(4, 1, 4, 1),
(5, 2, 1, 1),
(6, 2, 2, 1),
(7, 2, 3, 1),
(8, 2, 4, 1),
(9, 3, 1, 1),
(10, 3, 2, 1),
(11, 3, 3, 1),
(12, 3, 4, 1),
(13, 4, 1, 1),
(14, 4, 2, 1),
(15, 4, 3, 1),
(16, 4, 4, 1),
(17, 5, 1, 1),
(18, 5, 2, 1),
(19, 5, 3, 1),
(20, 5, 4, 1),
(21, 1, 5, 2),
(22, 1, 6, 2),
(23, 1, 7, 2),
(24, 1, 8, 2),
(25, 2, 5, 2),
(26, 2, 6, 2),
(27, 2, 12, 2),
(28, 2, 14, 2),
(29, 3, 5, 2),
(30, 3, 9, 2),
(31, 3, 8, 2),
(32, 3, 10, 2),
(33, 4, 7, 2),
(34, 4, 10, 2),
(35, 4, 11, 2),
(36, 4, 17, 2),
(37, 5, 5, 2),
(38, 5, 6, 2),
(39, 5, 9, 2),
(40, 5, 16, 2),
(41, 1, 11, 3),
(42, 1, 13, 3),
(43, 1, 15, 3),
(44, 1, 18, 3),
(45, 2, 13, 3),
(46, 2, 15, 3),
(47, 2, 16, 3),
(48, 2, 18, 3),
(49, 3, 13, 3),
(50, 3, 15, 3),
(51, 3, 16, 3),
(52, 3, 18, 3),
(53, 4, 15, 3),
(54, 4, 16, 3),
(55, 4, 17, 3),
(56, 4, 18, 3),
(57, 5, 9, 3),
(58, 5, 14, 3),
(59, 5, 16, 3),
(60, 5, 18, 3),
(61, 6, 19, 1),
(62, 6, 24, 1),
(63, 6, 27, 1),
(64, 6, 29, 1),
(65, 6, 31, 1),
(66, 7, 20, 1),
(67, 7, 26, 1),
(68, 7, 30, 1),
(69, 7, 23, 1),
(70, 7, 31, 1),
(71, 8, 21, 1),
(72, 8, 22, 1),
(73, 8, 27, 1),
(74, 8, 28, 1),
(75, 8, 31, 1),
(76, 9, 19, 1),
(77, 9, 24, 1),
(78, 9, 28, 1),
(79, 9, 29, 1),
(80, 9, 31, 1),
(81, 10, 23, 1),
(82, 10, 22, 1),
(83, 10, 25, 1),
(84, 10, 26, 1),
(85, 10, 31, 1);

-- --------------------------------------------------------

--
-- Table structure for table `programmes`
--

CREATE TABLE `programmes` (
  `ProgrammeID` int(11) NOT NULL,
  `ProgrammeName` text NOT NULL,
  `LevelID` int(11) DEFAULT NULL,
  `ProgrammeLeaderID` int(11) DEFAULT NULL,
  `Description` text DEFAULT NULL,
  `Image` text DEFAULT NULL,
  `Status` varchar(20) DEFAULT 'Published'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `programmes`
--

INSERT INTO `programmes` (`ProgrammeID`, `ProgrammeName`, `LevelID`, `ProgrammeLeaderID`, `Description`, `Image`, `Status`) VALUES
(1, 'BSc Computer Science', 1, 1, 'A broad computer science degree covering programming, AI, cybersecurity, and software engineering.', 'bsc.jpg', 'Published'),
(2, 'BSc Software Engineering', 1, 2, 'A specialized degree focusing on the development and lifecycle of software applications.', 'se1.jpg', 'Published'),
(3, 'BSc Artificial Intelligence', 1, 3, 'Focuses on machine learning, deep learning, and AI applications.', 'ai.jpg\r\n', 'Published'),
(4, 'BSc Cyber Security', 1, 4, 'Explores network security, ethical hacking, and digital forensics.', 'cs2.jpg', 'Published'),
(5, 'BSc Data Science', 1, 5, 'Covers big data, machine learning, and statistical computing.', 'ds.png', 'Published'),
(6, 'MSc Machine Learning', 2, 11, 'A postgraduate degree focusing on deep learning, AI ethics, and neural networks.', 'ml1.jpg', 'Published'),
(7, 'MSc Cyber Security', 2, 12, 'A specialized programme covering digital forensics, cyber threat intelligence, and security policy.', 'cs1.jpg', 'Published'),
(8, 'MSc Data Science', 2, 13, 'Focuses on big data analytics, cloud computing, and AI-driven insights.', 'ds3.jpg', 'Published'),
(9, 'MSc Artificial Intelligence', 2, 14, 'Explores autonomous systems, AI ethics, and deep learning technologies.', 'ok.avif', 'Published'),
(10, 'MSc Software Engineering ', 2, 15, 'Emphasizes software design, blockchain applications, and cutting-edge methodologies.', 'se2.avif', 'Published');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `StaffID` int(11) NOT NULL,
  `Name` text NOT NULL,
  `Username` varchar(100) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `ResetToken` varchar(255) DEFAULT NULL,
  `TokenExpiry` datetime DEFAULT NULL,
  `JobTitle` varchar(100) DEFAULT NULL,
  `Department` varchar(100) DEFAULT NULL,
  `ProfilePhoto` varchar(255) DEFAULT NULL,
  `Bio` text DEFAULT NULL,
  `PhoneNumber` varchar(20) DEFAULT NULL,
  `OfficeLocation` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`StaffID`, `Name`, `Username`, `Password`, `ResetToken`, `TokenExpiry`, `JobTitle`, `Department`, `ProfilePhoto`, `Bio`, `PhoneNumber`, `OfficeLocation`) VALUES
(1, 'Dr. Alice Johnson', 'alice.johnson', '$2y$10$0Kdn3cyax9tEM09/4pgqIOLfF1IdITlaUt/9mjCUoRMRM56ovHoBS', NULL, NULL, 'Professor', 'Computer Science', 'uploads/c.avif', 'Expert in software systems and algorithms.', '+45 1010 1010', 'Room A-101'),
(2, 'Dr. Brian Lee', 'brian.lee', '$2y$10$Jck/FHq1lhxFJ3BdUgyoFeWfknaoIZfkPYpwOj2CiSMTeNsHIl1LC', NULL, NULL, 'Senior Lecturer', 'Mathematics', 'uploads/d.avif', 'Focuses on discrete maths and logic.', '+45 1111 ', 'Room B-102'),
(3, 'Dr. Carol White', 'carol.white', '$2y$10$0Kdn3cyax9tEM09/4pgqIOLfF1IdITlaUt/9mjCUoRMRM56ovHoBS', NULL, NULL, 'Lecturer', 'Computer Architecture', 'uploads/e.jpg', 'Passionate about CPUs and performance.', '+45 1212 1212', 'Room C-103'),
(4, 'Dr. David Green', 'david.green', '$2y$10$0Kdn3cyax9tEM09/4pgqIOLfF1IdITlaUt/9mjCUoRMRM56ovHoBS', NULL, NULL, 'Programme Leader', 'Cybersecurity', 'uploads/f.jpg', 'Research in cryptography and forensics.', '+45 1313 1313', 'Room D-104'),
(5, 'Dr. Emma Scott', 'emma.scott', '$2y$10$0Kdn3cyax9tEM09/4pgqIOLfF1IdITlaUt/9mjCUoRMRM56ovHoBS', NULL, NULL, 'Lecturer', 'Software Engineering', 'uploads/g.png', 'Agile development and testing.', '+45 1414 1414', 'Room E-105'),
(6, 'Dr. Frank Moore', 'frank.moore', '$2y$10$0Kdn3cyax9tEM09/4pgqIOLfF1IdITlaUt/9mjCUoRMRM56ovHoBS', NULL, NULL, 'Senior Lecturer', 'Data Science', 'uploads/h.avif', 'Focus on machine learning and big data.', '+45 1515 1515', 'Room F-106'),
(7, 'Dr. Grace Adams', 'grace.adams', '$2y$10$0Kdn3cyax9tEM09/4pgqIOLfF1IdITlaUt/9mjCUoRMRM56ovHoBS', NULL, NULL, 'Assistant Professor', 'AI and Robotics', 'uploads/i.jpg', 'Robotics and natural language processing.', '+45 1616 1616', 'Room G-107'),
(8, 'Dr. Henry Clark', 'henry.clark', '$2y$10$0Kdn3cyax9tEM09/4pgqIOLfF1IdITlaUt/9mjCUoRMRM56ovHoBS', NULL, NULL, 'Lecturer', 'Machine Learning', 'uploads/j.avif', 'Teaches decision trees and supervised ML.', '+45 1717 1717', 'Room H-108'),
(9, 'Dr. Irene Hall', 'irene.hall', '$2y$10$0Kdn3cyax9tEM09/4pgqIOLfF1IdITlaUt/9mjCUoRMRM56ovHoBS', NULL, NULL, 'Teaching Assistant', 'Networking', 'uploads/k.jpg', 'Helps with lab work and practicals.', '+45 1818 1818', 'Room I-109'),
(10, 'Dr. James Wright', 'james.wright', '$2y$10$0Kdn3cyax9tEM09/4pgqIOLfF1IdITlaUt/9mjCUoRMRM56ovHoBS', NULL, NULL, 'Lecturer', 'UX Design', 'uploads/l.avif', 'Specialist in HCI and user-centered design.', '+45 1919 1919', 'Room J-110'),
(11, 'Dr. Sophia Miller', 'sophia.miller', '$2y$10$0Kdn3cyax9tEM09/4pgqIOLfF1IdITlaUt/9mjCUoRMRM56ovHoBS', NULL, NULL, 'Senior Lecturer', 'Blockchain', 'uploads/m.avif', 'Focus on distributed technologies.', '+45 2020 2020', 'Room A-201'),
(12, 'Dr. Benjamin Carter', 'benjamin.carter', '$2y$10$0Kdn3cyax9tEM09/4pgqIOLfF1IdITlaUt/9mjCUoRMRM56ovHoBS', NULL, NULL, 'Professor', 'Cloud Computing', 'uploads/n.avif', 'Expert in distributed systems.', '+45 2121 2121', 'Room B-202'),
(13, 'Dr. Chloe Thompson', 'chloe.thompson', '$2y$10$0Kdn3cyax9tEM09/4pgqIOLfF1IdITlaUt/9mjCUoRMRM56ovHoBS', NULL, NULL, 'Lecturer', 'Cyber Law', 'uploads/o.avif', 'Legal aspects of tech and GDPR.', '+45 2222 2222', 'Room C-203'),
(14, 'Dr. Daniel Robinson', 'daniel.robinson', '$2y$10$0Kdn3cyax9tEM09/4pgqIOLfF1IdITlaUt/9mjCUoRMRM56ovHoBS', NULL, NULL, 'Project Coordinator', 'Research', 'uploads/p.avif', 'Leads student capstone projects.', '+45 2323 2323', 'Room D-204'),
(15, 'Dr. Emily Davis', 'emily.davis', '$2y$10$0Kdn3cyax9tEM09/4pgqIOLfF1IdITlaUt/9mjCUoRMRM56ovHoBS', NULL, NULL, 'Lecturer', 'AI', 'uploads/q.avif', 'NLP, deep learning and ethics.', '+45 2424 2424', 'Room E-205'),
(16, 'Dr. Nathan Hughes', 'nathan.hughes', '$2y$10$0Kdn3cyax9tEM09/4pgqIOLfF1IdITlaUt/9mjCUoRMRM56ovHoBS', NULL, NULL, 'Lecturer', 'Cryptography', 'uploads/r.avif', 'Encryption and secure systems.', '+45 2525 2525', 'Room F-206'),
(17, 'Dr. Olivia Martin', 'olivia.martin', '$2y$10$0Kdn3cyax9tEM09/4pgqIOLfF1IdITlaUt/9mjCUoRMRM56ovHoBS', NULL, NULL, 'Assistant Lecturer', 'Cloud Infrastructure', 'uploads/s.avif', 'Serverless and edge computing.', '+45 2626 2626', 'Room G-207'),
(18, 'Dr. Samuel Anderson', 'samuel.anderson', '$2y$10$0Kdn3cyax9tEM09/4pgqIOLfF1IdITlaUt/9mjCUoRMRM56ovHoBS', NULL, NULL, 'Research Fellow', 'AI Research', 'uploads/t.png', 'AI in healthcare and education.', '+45 2727 2727', 'Room H-208'),
(19, 'Dr. Victoria Hall', 'victoria.hall', '$2y$10$0Kdn3cyax9tEM09/4pgqIOLfF1IdITlaUt/9mjCUoRMRM56ovHoBS', NULL, NULL, 'Department Head', 'Cybersecurity', 'uploads/u.avif', 'Oversees teaching and faculty.', '+45 2828 2828', 'Room I-209'),
(20, 'Dr. William Scott', 'william.scott', '$2y$10$0Kdn3cyax9tEM09/4pgqIOLfF1IdITlaUt/9mjCUoRMRM56ovHoBS', NULL, NULL, 'Programme Director', 'Data Analytics', 'uploads/v.avif', 'Leads MSc Data Science programme.', '+45 2929 2929', 'Room J-210');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`AdminID`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- Indexes for table `interestedstudents`
--
ALTER TABLE `interestedstudents`
  ADD PRIMARY KEY (`InterestID`),
  ADD KEY `ProgrammeID` (`ProgrammeID`),
  ADD KEY `StudentEmail` (`StudentEmail`);

--
-- Indexes for table `levels`
--
ALTER TABLE `levels`
  ADD PRIMARY KEY (`LevelID`);

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`ModuleID`),
  ADD KEY `ModuleLeaderID` (`ModuleLeaderID`);

--
-- Indexes for table `programmemodules`
--
ALTER TABLE `programmemodules`
  ADD PRIMARY KEY (`ProgrammeModuleID`),
  ADD KEY `ProgrammeID` (`ProgrammeID`),
  ADD KEY `ModuleID` (`ModuleID`);

--
-- Indexes for table `programmes`
--
ALTER TABLE `programmes`
  ADD PRIMARY KEY (`ProgrammeID`),
  ADD KEY `LevelID` (`LevelID`),
  ADD KEY `ProgrammeLeaderID` (`ProgrammeLeaderID`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`StaffID`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `AdminID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `interestedstudents`
--
ALTER TABLE `interestedstudents`
  MODIFY `InterestID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `modules`
--
ALTER TABLE `modules`
  MODIFY `ModuleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `programmemodules`
--
ALTER TABLE `programmemodules`
  MODIFY `ProgrammeModuleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `programmes`
--
ALTER TABLE `programmes`
  MODIFY `ProgrammeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `interestedstudents`
--
ALTER TABLE `interestedstudents`
  ADD CONSTRAINT `interestedstudents_ibfk_1` FOREIGN KEY (`ProgrammeID`) REFERENCES `programmes` (`ProgrammeID`);

--
-- Constraints for table `modules`
--
ALTER TABLE `modules`
  ADD CONSTRAINT `modules_ibfk_1` FOREIGN KEY (`ModuleLeaderID`) REFERENCES `staff` (`StaffID`);

--
-- Constraints for table `programmemodules`
--
ALTER TABLE `programmemodules`
  ADD CONSTRAINT `programmemodules_ibfk_1` FOREIGN KEY (`ProgrammeID`) REFERENCES `programmes` (`ProgrammeID`),
  ADD CONSTRAINT `programmemodules_ibfk_2` FOREIGN KEY (`ModuleID`) REFERENCES `modules` (`ModuleID`);

--
-- Constraints for table `programmes`
--
ALTER TABLE `programmes`
  ADD CONSTRAINT `programmes_ibfk_1` FOREIGN KEY (`LevelID`) REFERENCES `levels` (`LevelID`),
  ADD CONSTRAINT `programmes_ibfk_2` FOREIGN KEY (`ProgrammeLeaderID`) REFERENCES `staff` (`StaffID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
