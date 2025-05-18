<?php
include "db.php";
session_start();

// Step 1: Check if pet_id is provided
if (!isset($_GET['id'])) {
  die("Pet ID is missing.");
}

$pet_id = $_GET['id'];

// Step 2: Prepare and run the query safely
$stmt = $conn->prepare("SELECT * FROM pets WHERE pet_id = ?");
$stmt->bind_param("i", $pet_id);
$stmt->execute();
$result = $stmt->get_result();

// Step 3: Check if pet exists
if ($result->num_rows === 0) {
  die("No pet found with this ID.");
}

$pet = $result->fetch_assoc(); // Now $pet is defined and ready
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" href="images/logo.png" type="image/png">
  <title>Pawfect Home | Pet Adoption</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    .pet-detail-container {
      max-width: 1000px;
      margin: 40px auto;
      background: #fff8e7;
      padding: 30px;
      border-radius: 18px;
      box-shadow: 0 4px 25px rgba(0, 0, 0, 0.1);
      display: flex;
      flex-direction: row;
      /* Ensures image comes first */
      gap: 40px;
      align-items: flex-start;
    }

    .pet-detail-container img {
      flex: 1 1 300px;
      max-width: 320px;
      border-radius: 12px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
      object-fit: cover;
    }

    .pet-info {
      flex: 2 1 700px;
    }

    .pet-info h2 {
      font-size: 2.5rem;
      margin-bottom: 5px;
      color: #333;
    }

    .pet-info p {
      margin: 10px 0;
      font-size: 1.1rem;
      line-height: 1.5;
    }

    .btn {
      display: inline-block;
      margin-top: 25px;
      background-color: #ff7e5f;
      color: white;
      padding: 12px 24px;
      border: none;
      border-radius: 10px;
      font-size: 1.1rem;
      font-weight: bold;
      cursor: pointer;
      text-decoration: none;
      text-align: center;
      transition: background-color 0.3s ease;
    }

    .btn:hover {
      background-color: #eb6a4f;
    }

    @media (max-width: 768px) {
      .pet-detail-container {
        flex-direction: column;
        text-align: center;
      }

      .pet-detail-container img {
        margin: 0 auto 20px;
      }
    }

    .user-nav {
      background-color: #ffffff;
      border-bottom: 5px solidrgb(255, 225, 0);
      padding: 1rem 2rem;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .user-nav ul {
      list-style: none;
      display: flex;
      justify-content: flex-end;
      align-items: center;
      gap: 30px;
      margin: 0;
      padding: 0;
    }

    .user-nav ul li {
      position: relative;
    }

    /* Navigation Links */
    .user-nav ul li a {
      text-decoration: none;
      color: #333;
      font-size: 16px;
      font-weight: 500;
      padding: 8px 14px;
      border-radius: 6px;
      transition: background-color 0.25s ease, color 0.25s ease;
    }

    .user-nav ul li a:hover {
      background-color: var(--primary-color);
      color: #fff;
    }

    /* Dropdown Toggle Button */
    .menu-toggle {
      border: 1px solid white;
      font-weight: 550;
      padding: 0.5rem 1rem;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .menu-toggle:hover {
      background-color: var(--primary-color);
      color: #ffffff;
    }

    .dropdown-menu {
      display: none;
      position: absolute;
      top: 130%;
      right: 0;
      background-color: #fff;
      min-width: 250px;
      list-style: none;
      padding: 0.5rem 0;
      margin-top: 0.5rem;
      opacity: 0;
      transform: translateY(-10px);
      transition: opacity 0.25s ease, transform 0.25s ease;
      z-index: 1000;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .dropdown-menu li {
      border-bottom: 1px solid #f2f2f2;
    }

    .dropdown-menu li:last-child {
      border-bottom: none;
    }

    .dropdown-menu li a {
      display: block;
      padding: 0.75rem 1rem;
      color: #333;
      text-decoration: none;
      font-size: 15px;
      font-weight: 500;
      transition: background-color 0.2s ease, color 0.2s ease;
    }

    .dropdown-menu li a:hover {
      background-color: rgb(255, 255, 255);
      color: #000;
    }

    /* Show dropdown when active */
    .dropdown-nav.active .dropdown-menu {
      display: block;
      opacity: 1;
      transform: translateY(0);
    }

    .dropdown-menu {
      transition: opacity 0.3s ease, transform 0.3s ease;
      animation: fadeSlideDown 0.35s ease forwards;
    }

    @keyframes fadeSlideDown {
      0% {
        opacity: 0;
        transform: translateY(-10px);
      }
    }
  </style>

</head>

<body>

  <header>
    <div class="navbar">
      <div class="logo">
        <img src="images/logo.png" alt="Pet Patrol Logo" class="logo-img">
        <div class="user-greeting">
          <span>👋 Welcome, <?php echo htmlspecialchars($_SESSION['username'] ?? 'username'); ?>!</span>
        </div>
      </div>

      <nav class="user-nav">
        <ul>
          <li><a href="petlist.php">Browse Pets</a></li>
          <li><a href="applications.php">My Applications</a></li>
          <li><a href="profile.php">My Profile</a></li>
          <li><a href="donations.php">Donations</a></li>

          <li class="dropdown-nav">
            <button class="menu-toggle">
              <i class="fas fa-bars"></i> Menu
            </button>
            <ul class="dropdown-menu">
              <li><a href="home.php">Home</a></li>
              <li><a href="userabout.php">About Us</a></li>
              <li><a href="userpolicies.php">Adoption Policies</a></li>
              <li><a href="userservices.php">Services</a></li>
              <li><a href="logout.php" class="logout">Logout</a></li>
            </ul>
          </li>
        </ul>
      </nav>

  </header>

  <div class="pet-detail-container">
    <img src="<?= htmlspecialchars($pet['image_path']) ?>" alt="<?= htmlspecialchars($pet['name']) ?>">

    <div class="pet-info">
      <h2>🐾 <?= htmlspecialchars($pet['name']) ?></h2>
      <p><strong>Species:</strong> <?= htmlspecialchars($pet['species']) ?></p>
      <p><strong>Health Condition:</strong> <?= htmlspecialchars($pet['health_condition']) ?></p>
      <p><strong>Vaccination History:</strong> <?= nl2br(htmlspecialchars($pet['vaccination_history'])) ?></p>
      <p><strong>Behavior Notes:</strong> <?= nl2br(htmlspecialchars($pet['behavior_notes'])) ?></p>
      <p><strong>Adoption Requirements:</strong> <?= nl2br(htmlspecialchars($pet['adoption_requirements'])) ?></p>
      <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($pet['description'])) ?></p>

      <a href="terms.php?pet_id=<?= $pet['pet_id'] ?>" class="btn">Apply to Adopt 💌</a>
      <a href="petlist.php" class="btn" style="background-color:#ccc; color:#333;">⬅ Back to Browse</a>

    </div>
  </div>

  <footer class="site-footer">
    <div class="footer-content">
      <p>Reach us at:
        <a href="mailto:ponyososukesheltercenter@gmail.com">ponyososukesheltercenter@gmail.com</a>
      </p>
      <p>&copy; 2025 ALL RIGHTS RESERVED.</p>
    </div>
  </footer>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const toggleButton = document.querySelector('.menu-toggle');
      const dropdownNav = document.querySelector('.dropdown-nav');

      // Toggle menu
      toggleButton.addEventListener('click', function (event) {
        event.stopPropagation();
        dropdownNav.classList.toggle('active');
      });

      // Click outside to close
      document.addEventListener('click', function (event) {
        if (!dropdownNav.contains(event.target)) {
          dropdownNav.classList.remove('active');
        }
      });

      // Close on scroll
      window.addEventListener('scroll', function () {
        dropdownNav.classList.remove('active');
      });
    });
  </script>

</body>

</html>