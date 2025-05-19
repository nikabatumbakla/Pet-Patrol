<?php
include "db.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pawfect Home | Developer</title>
  <link rel="icon" href="images/logo.png" type="image/png">
  <link rel="stylesheet" href="style.css" />
  <style>
    :root {
      --primary-color: #e1a800;
      --secondary-color: #ffe094;
      --highlight-color: #f6e10d;
      --dark-yellow: #c88f0a;
      --border-radius-m: 10px;
    }

    .team-section {
      background-color: rgb(255, 253, 231);
      padding: 4rem 2rem;
      text-align: center;
      color: #1a1a1a;
    }

    .section-heading {
      font-size: 2.5rem;
      color: var(--primary-color);
      margin-bottom: 0.5rem;
    }

    .section-subheading {
      font-size: 1.3rem;
      color: var(--dark-yellow);
      margin-bottom: 3rem;
    }

    .team-container {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 2rem;
    }

    .team-member {
      background: white;
      border-radius: var(--border-radius-m);
      box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
      width: 260px;
      padding: 2rem 1.5rem;
      transition: transform 0.3s, box-shadow 0.3s;
    }

    .team-member:hover {
      transform: translateY(-10px);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.25);
    }

    .team-member img {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      border: 4px solid var(--primary-color);
      object-fit: cover;
      margin-bottom: 1rem;
    }

    .member-name {
      font-size: 1.2rem;
      margin: 0.5rem 0;
      color: var(--primary-color);
      font-weight: bold;
    }

    .member-role {
      font-size: 1rem;
      color: #444;
    }

    .center-button {
      margin-top: 3rem;
    }

    .login-button {
      background-color: var(--primary-color);
      color: white;
      border: none;
      padding: 0.9rem 2rem;
      font-size: 1rem;
      font-weight: bold;
      border-radius: var(--border-radius-m);
      cursor: pointer;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
      transition: background-color 0.3s, transform 0.2s;
    }

    .login-button:hover {
      background-color: var(--dark-yellow);
      transform: scale(1.05);
    }

    @media (max-width: 768px) {
      .team-container {
        flex-direction: column;
        align-items: center;
      }
    }
  </style>
</head>

<body>

  <header>
    <div class="navbar">
      <div class="logo">
        <img src="images/logo.png" alt="Pet Patrol Logo" class="logo-img">
        🐾 Pet Patrol <span>| Where every pet finds a family</span>
      </div>

      <nav class="dropdown-nav">
        <button class="menu-toggle">☰ Menu</button>
        <ul class="dropdown-menu">
          <li><a href="index.html">Home</a></li>
          <li><a href="about.php">About Us</a></li>
          <li><a href="policies.php">Adoption Policies</a></li>
          <li><a href="petlisting.php">Available Pets</a></li>
          <li><a href="developer.php">Developer</a></li>
          <li><a href="services.php">Services</a></li>
          <li><a href="register.php">Register</a></li>
        </ul>
      </nav>

  </header>

  <section class="team-section">
    <h2 class="section-heading">Meet the Admin Team</h2>
    <h3 class="section-subheading">Creators of the Pet Patrol System – BSIT 2E</h3>

    <div class="team-container">
      <div class="team-member">
        <img src="images/danica.jpg" alt="Danica T. Agawa">
        <h3 class="member-name">Danica T. Agawa</h3>
        <p class="member-role">Back-End Developer</p>
      </div>

      <div class="team-member">
        <img src="images/jasmin.jpg" alt="Jasmin Ann T. Bobares">
        <h3 class="member-name">Jasmin Ann T. Bobares</h3>
        <p class="member-role">Front-End Developer</p>
      </div>

      <div class="team-member">
        <img src="images/anna.jpg" alt="Anna C. Taduran">
        <h3 class="member-name">Anna C. Taduran</h3>
        <p class="member-role">Full-Stack Support</p>
      </div>
    </div>

    <div class="center-button">
      <button class="login-button" onclick="location.href='adminlog.php'">Login to Admin Panel</button>
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