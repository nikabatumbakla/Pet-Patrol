-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 06, 2025 at 05:41 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12
SET
  SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

START TRANSACTION;

SET
  time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;

/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;

/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;

/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `adsfinals`
--
-- --------------------------------------------------------
--
-- Table structure for table `addresses`
--
CREATE TABLE
  `addresses` (
    `address_id` int (11) NOT NULL,
    `user_id` int (11) NOT NULL,
    `street_address` varchar(255) NOT NULL,
    `city` varchar(100) NOT NULL,
    `state_province` varchar(100) NOT NULL,
    `postal_code` varchar(20) NOT NULL,
    `country` varchar(50) NOT NULL DEFAULT 'DefaultCountry',
    `address_type` enum ('home', 'mailing', 'work', 'other') DEFAULT 'home'
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `admins`
--
CREATE TABLE
  `admins` (
    `id` int (11) NOT NULL,
    `fullName` varchar(50) DEFAULT NULL,
    `username` varchar(50) NOT NULL,
    `password` varchar(255) NOT NULL
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--
INSERT INTO
  `admins` (`id`, `fullName`, `username`, `password`)
VALUES
  (
    1,
    NULL,
    'Anna',
    '8d6612ec19dc79c4f69e8ea574d5e7026fca713e7691f4963775c82e98fcac96'
  ),
  (
    2,
    NULL,
    'Danica',
    '65acb8447e518eb23878295fee70f67fb73989628d5218a2db5b344cb1b0fa51'
  ),
  (
    3,
    NULL,
    'Jasmin',
    '30f2b75b3315a0d77807bba2c9835c15672aeea1b3fffb9b5b7b0a01e52a77de'
  );

-- --------------------------------------------------------
--
-- Table structure for table `admin_logs`
--
CREATE TABLE
  `admin_logs` (
    `log_id` int (11) NOT NULL,
    `admin_id` int (11) NOT NULL,
    `action` text DEFAULT NULL,
    `log_time` timestamp NOT NULL DEFAULT current_timestamp()
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `adoptionapplications`
--
CREATE TABLE
  `adoptionapplications` (
    `application_id` int (11) NOT NULL,
    `user_id` int (11) NOT NULL,
    `pet_id` int (11) NOT NULL,
    `status_id` int (11) NOT NULL,
    `submission_date` timestamp NOT NULL DEFAULT current_timestamp(),
    `application_address_id` int (11) DEFAULT NULL,
    `location` varchar(50) DEFAULT NULL,
    `living_conditions` text DEFAULT NULL,
    `pet_experience` text DEFAULT NULL,
    `home_type` varchar(50) DEFAULT NULL,
    `has_children` enum ('yes', 'no', 'sometimes') DEFAULT NULL,
    `hours_alone` varchar(50) DEFAULT NULL,
    `has_other_pets` enum ('yes', 'no') DEFAULT NULL,
    `willing_vet_care` enum ('yes', 'no', 'unsure') DEFAULT NULL
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `application_statuses`
--
CREATE TABLE
  `application_statuses` (
    `status_id` int (11) NOT NULL,
    `status_name` enum (
      'pending_review',
      'interview_scheduled',
      'home_visit_pending',
      'approved',
      'rejected',
      'withdrawn'
    ) DEFAULT 'pending_review'
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `appointments`
--
CREATE TABLE
  `appointments` (
    `appointment_id` int (11) NOT NULL,
    `application_id` int (11) NOT NULL,
    `appointment_date` datetime NOT NULL,
    `status` enum (
      'scheduled',
      'completed',
      'canceled',
      'rescheduled',
      'no_show'
    ) DEFAULT 'scheduled'
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `pets`
--
CREATE TABLE
  `pets` (
    `pet_id` int (11) NOT NULL,
    `name` varchar(100) NOT NULL,
    `species` varchar(50) NOT NULL,
    `health_condition` enum ('healthy', 'needs_attention', 'ill') DEFAULT 'healthy',
    `pet_status` enum ('available', 'pending', 'adopted') DEFAULT 'available',
    `description` text DEFAULT NULL,
    `category_id` int (11) DEFAULT NULL,
    `image_path` varchar(255) DEFAULT NULL,
    `vaccination_history` text DEFAULT NULL,
    `behavior_notes` text DEFAULT NULL,
    `adoption_requirements` text DEFAULT NULL
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `pets`
--
INSERT INTO
  `pets` (
    `pet_id`,
    `name`,
    `species`,
    `health_condition`,
    `pet_status`,
    `description`,
    `category_id`,
    `image_path`,
    `vaccination_history`,
    `behavior_notes`,
    `adoption_requirements`
  )
VALUES
  (
    1,
    'Buddy',
    'Dog',
    'healthy',
    'available',
    'Friendly and energetic dog',
    1,
    'images/dog/1.jpg',
    'Rabies, Distemper, Parvovirus',
    'Very friendly and playful with kids.',
    'Requires a fenced yard, home visit required'
  ),
  (
    2,
    'Rocky',
    'Dog',
    'healthy',
    'available',
    'Loves playing fetch',
    1,
    'images/dog/2.jpg',
    'Rabies, Bordetella',
    'Loves fetch and outdoor games.',
    'Active family preferred'
  ),
  (
    3,
    'Bella',
    'Dog',
    'healthy',
    'available',
    'Small and affectionate',
    1,
    'images/dog/3.jpg',
    'Rabies, Parvovirus',
    'Affectionate lap dog.',
    'Best with small household'
  ),
  (
    4,
    'Max',
    'Dog',
    'healthy',
    'available',
    'Great family dog',
    1,
    'images/dog/4.jpg',
    'Rabies, Distemper',
    'Calm and great with children.',
    'Fenced yard, indoor space'
  ),
  (
    5,
    'Charlie',
    'Dog',
    'healthy',
    'available',
    'Loyal and intelligent',
    1,
    'images/dog/5.jpg',
    'Rabies, Lyme Disease',
    'Smart and obedient.',
    'Owner with dog experience'
  ),
  (
    6,
    'Milo',
    'Cat',
    'healthy',
    'available',
    'Loves to cuddle',
    2,
    'images/cat/1.jpg',
    'FVRCP, Rabies',
    'Loves cuddles and soft beds.',
    'Quiet home environment'
  ),
  (
    7,
    'Luna',
    'Cat',
    'healthy',
    'available',
    'Very playful kitten',
    2,
    'images/cat/2.jpg',
    'FVRCP, Dewormed',
    'Playful and curious kitten.',
    'Must be kept indoors'
  ),
  (
    8,
    'Whiskers',
    'Cat',
    'healthy',
    'available',
    'Independent but loving',
    2,
    'images/cat/3.jpg',
    'Rabies, Feline Leukemia',
    'Independent but cuddly.',
    'Ideal for apartment living'
  ),
  (
    9,
    'Shadow',
    'Cat',
    'healthy',
    'available',
    'Loves climbing on furniture',
    2,
    'images/cat/4.jpg',
    'FVRCP, Rabies',
    'Loves heights and climbing.',
    'Cat tree recommended'
  ),
  (
    10,
    'Oreo',
    'Cat',
    'healthy',
    'available',
    'Curious and friendly',
    2,
    'images/cat/5.jpg',
    'FVRCP, Dewormed',
    'Very friendly and loves attention.',
    'Must be indoor-only'
  ),
  (
    11,
    'Kiwi',
    'Bird',
    'healthy',
    'available',
    'Colorful and talkative parrot',
    3,
    'images/bird/1.jpg',
    'Wing Clipped, Dewormed',
    'Talkative and social bird.',
    'Large cage, experienced owner'
  ),
  (
    12,
    'Sunny',
    'Bird',
    'healthy',
    'available',
    'Loves to sing in the morning',
    3,
    'images/bird/2.jpg',
    'Wing Clipped',
    'Sings in the morning, very active.',
    'Bird-safe home, no cats'
  ),
  (
    13,
    'Blue',
    'Bird',
    'healthy',
    'available',
    'Vibrant blue feathers',
    3,
    'images/bird/3.jpg',
    'Wing Clipped, Dewormed',
    'Loves interacting with humans.',
    'Spacious cage and toys'
  ),
  (
    14,
    'Coco',
    'Bird',
    'healthy',
    'available',
    'Very friendly and social',
    3,
    'images/bird/4.jpg',
    'Wing Clipped, Healthy',
    'Sociable and curious.',
    'Must not be caged alone'
  ),
  (
    15,
    'Tweety',
    'Bird',
    'healthy',
    'available',
    'Loves flying around',
    3,
    'images/bird/5.jpg',
    'Wing Clipped, Healthy',
    'Energetic and loves flying.',
    'Flight space or aviary needed'
  ),
  (
    16,
    'Nemo',
    'Fish',
    'healthy',
    'available',
    'Bright orange clownfish',
    4,
    'images/fish/1.jpg',
    'Parasite-Free, Clean Tank',
    'Peaceful and colorful.',
    '10-gallon tank minimum'
  ),
  (
    17,
    'Bubbles',
    'Fish',
    'healthy',
    'available',
    'Loves swimming in circles',
    4,
    'images/fish/2.jpg',
    'Parasite-Free, Water Treated',
    'Energetic and loves bubbles.',
    'Filter and proper tank decor'
  ),
  (
    18,
    'Goldie',
    'Fish',
    'healthy',
    'available',
    'Golden scales that shine',
    4,
    'images/fish/3.jpg',
    'Clean Water, No illness history',
    'Graceful swimmer.',
    'Must not be overcrowded'
  ),
  (
    19,
    'Splash',
    'Fish',
    'healthy',
    'available',
    'Very active in the tank',
    4,
    'images/fish/4.jpg',
    'Healthy, Clean Water Conditions',
    'Active and playful in tank.',
    'Requires regular tank cleaning'
  ),
  (
    20,
    'Finny',
    'Fish',
    'healthy',
    'available',
    'Loves hiding in coral',
    4,
    'images/fish/5.jpg',
    'Healthy, Clean Tank',
    'Hides often but playful.',
    'Plenty of hiding spaces'
  ),
  (
    21,
    'Peanut',
    'Hamster',
    'healthy',
    'available',
    'Tiny and fluffy',
    5,
    'images/hamster/1.jpg',
    'Clean, No illnesses',
    'Tiny and loves tunnels.',
    'Cage with exercise wheel'
  ),
  (
    22,
    'Gizmo',
    'Hamster',
    'healthy',
    'available',
    'Very curious and active',
    5,
    'images/hamster/2.jpg',
    'Dewormed, Healthy',
    'Curious and active.',
    'Escape-proof cage'
  ),
  (
    23,
    'Marshmallow',
    'Hamster',
    'healthy',
    'available',
    'Soft white fur',
    5,
    'images/hamster/3.jpg',
    'Dewormed',
    'Calm and cuddly.',
    'Soft bedding required'
  ),
  (
    24,
    'Cinnamon',
    'Hamster',
    'healthy',
    'available',
    'Loves to run on the wheel',
    5,
    'images/hamster/4.jpg',
    'No known illness',
    'Loves to run at night.',
    'Spacious cage and wheel'
  ),
  (
    25,
    'Pumpkin',
    'Hamster',
    'healthy',
    'available',
    'Adorable little buddy',
    5,
    'images/hamster/5.jpg',
    'Healthy',
    'Sweet and docile.',
    'Daily social interaction'
  );

-- --------------------------------------------------------
--
-- Table structure for table `pet_categories`
--
CREATE TABLE
  `pet_categories` (
    `category_id` int (11) NOT NULL,
    `category_name` varchar(100) DEFAULT NULL
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `pet_categories`
--
INSERT INTO
  `pet_categories` (`category_id`, `category_name`)
VALUES
  (1, 'Dogs'),
  (2, 'Cats'),
  (3, 'Birds'),
  (4, 'Fish'),
  (5, 'Hamsters');

-- --------------------------------------------------------
--
-- Table structure for table `termsandconditions`
--
CREATE TABLE
  `termsandconditions` (
    `terms_id` int (11) NOT NULL,
    `user_id` int (11) NOT NULL,
    `agreed` tinyint (1) NOT NULL DEFAULT 0,
    `agreed_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `users`
--
CREATE TABLE
  `users` (
    `user_id` int (11) NOT NULL,
    `fullname` varchar(50) DEFAULT NULL,
    `email` varchar(100) NOT NULL,
    `phone_num` varchar(50) NOT NULL,
    `username` varchar(100) NOT NULL,
    `password` varchar(100) NOT NULL,
    `date_registered` timestamp NOT NULL DEFAULT current_timestamp()
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Indexes for dumped tables
--
--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses` ADD PRIMARY KEY (`address_id`),
ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins` ADD PRIMARY KEY (`id`),
ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `admin_logs`
--
ALTER TABLE `admin_logs` ADD PRIMARY KEY (`log_id`),
ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `adoptionapplications`
--
ALTER TABLE `adoptionapplications` ADD PRIMARY KEY (`application_id`),
ADD KEY `user_id` (`user_id`),
ADD KEY `pet_id` (`pet_id`),
ADD KEY `status_id` (`status_id`),
ADD KEY `application_address_id` (`application_address_id`);

--
-- Indexes for table `application_statuses`
--
ALTER TABLE `application_statuses` ADD PRIMARY KEY (`status_id`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments` ADD PRIMARY KEY (`appointment_id`),
ADD KEY `application_id` (`application_id`);

--
-- Indexes for table `pets`
--
ALTER TABLE `pets` ADD PRIMARY KEY (`pet_id`),
ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `pet_categories`
--
ALTER TABLE `pet_categories` ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `termsandconditions`
--
ALTER TABLE `termsandconditions` ADD PRIMARY KEY (`terms_id`),
ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users` ADD PRIMARY KEY (`user_id`),
ADD UNIQUE KEY `email` (`email`),
ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--
--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses` MODIFY `address_id` int (11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins` MODIFY `id` int (11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 4;

--
-- AUTO_INCREMENT for table `admin_logs`
--
ALTER TABLE `admin_logs` MODIFY `log_id` int (11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `adoptionapplications`
--
ALTER TABLE `adoptionapplications` MODIFY `application_id` int (11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `application_statuses`
--
ALTER TABLE `application_statuses` MODIFY `status_id` int (11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments` MODIFY `appointment_id` int (11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pets`
--
ALTER TABLE `pets` MODIFY `pet_id` int (11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 26;

--
-- AUTO_INCREMENT for table `pet_categories`
--
ALTER TABLE `pet_categories` MODIFY `category_id` int (11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 6;

--
-- AUTO_INCREMENT for table `termsandconditions`
--
ALTER TABLE `termsandconditions` MODIFY `terms_id` int (11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users` MODIFY `user_id` int (11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--
--
-- Constraints for table `addresses`
--
ALTER TABLE `addresses` ADD CONSTRAINT `addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `admin_logs`
--
ALTER TABLE `admin_logs` ADD CONSTRAINT `admin_logs_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `adoptionapplications`
--
ALTER TABLE `adoptionapplications` ADD CONSTRAINT `adoptionapplications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
ADD CONSTRAINT `adoptionapplications_ibfk_2` FOREIGN KEY (`pet_id`) REFERENCES `pets` (`pet_id`),
ADD CONSTRAINT `adoptionapplications_ibfk_3` FOREIGN KEY (`status_id`) REFERENCES `application_statuses` (`status_id`),
ADD CONSTRAINT `adoptionapplications_ibfk_4` FOREIGN KEY (`application_address_id`) REFERENCES `addresses` (`address_id`) ON DELETE SET NULL;

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments` ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `adoptionapplications` (`application_id`) ON DELETE CASCADE;

--
-- Constraints for table `pets`
--
ALTER TABLE `pets` ADD CONSTRAINT `pets_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `pet_categories` (`category_id`) ON DELETE SET NULL;

--
-- Constraints for table `termsandconditions`
--
ALTER TABLE `termsandconditions` ADD CONSTRAINT `termsandconditions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;

/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;