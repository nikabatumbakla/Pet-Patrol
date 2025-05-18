<?php
// Assume session and DB connection already established
include "db.php";
session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pawfect Home | Donations</title>
  <link rel="icon" href="images/logo.png" type="image/png">
  <link rel="stylesheet" href="style.css" />
  <style>
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
          <span>👋 Welcome, <?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?>!</span>
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

  <section class="donation-animals">
    <style>
      .donation-animals {
        background: #fff9e6;
        /* light yellow background */
        padding: 50px 20px;
        text-align: center;
      }

      .donation-header {
        margin-bottom: 40px;
      }

      .donation-header img {
        width: 50%;
        max-width: 500px;
        height: auto;
        border-radius: 20px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
      }

      .donation-animals-container {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: center;
        gap: 40px;
      }

      .donation-image {
        text-align: center;
      }

      .donation-image img {
        width: 200px;
        height: auto;
        border-radius: 15px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
      }

      .donation-image p {
        margin-top: 10px;
        font-size: 1.2rem;
        font-weight: bold;
        color: #333;
      }

      .animal-text {
        max-width: 400px;
        text-align: left;
      }

      .animal-text h2 {
        font-size: 2rem;
        color: #333;
      }

      .animal-text p {
        font-size: 1.1rem;
        color: #555;
        margin: 10px 0;
      }
    </style>

    <div class="donation-header">
      <img src="images/donationHeader.jpg" alt="Animals">
    </div>

    <div class="donation-animals-container">
      <div class="donation-image">
        <img src="images/donationQR.jfif" alt="Donate to Save Lives">

      </div>

      <div class="animal-text">
        <h2>Support Their Journey Home 🐾</h2>
        <p>Your donations provide food, shelter, and medical care to pets in need.
          Every small act of kindness brings them closer to a forever family.
          Help us make a difference today!</p>
        <p>Donate Now!</p>

      </div>
    </div>
  </section>

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