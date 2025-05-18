<?php
include "db.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pawfect Home | Services</title>
  <link rel="icon" href="images/logo.png" type="image/png">
  <link rel="stylesheet" href="style.css" />
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

  <section class="services-section">
    <h2 class="section-heading">Our Services</h2>
    <p class="section-subheading"><b>At Pet Patrol, we are dedicated to supporting every step of your journey with your
        new companion.</p>
    <p class="section-subheading">Below are the services we offer to ensure the well-being of both pets and adopters.
    </p>

    <div class="services-container">
      <div class="service-item">
        <h3>Veterinary Care</h3>
        <p>We provide initial veterinary checks and vaccinations for all adoptable pets. Ongoing partnerships with local
          clinics ensure your pet receives the medical attention they deserve.</p>
      </div>

      <div class="service-item">
        <h3>Training & Pet Education</h3>
        <p>New pet owners can access basic training guides and resources to help integrate their furry friends into
          their new homes. We also offer behavioral tips and best practices.</p>
      </div>

      <div class="service-item">
        <h3>Volunteer Opportunities</h3>
        <p>Join our community of animal lovers! Volunteers assist with pet care, adoption events, and community outreach
          programs. Your time makes a difference.</p>
      </div>

      <div class="service-item">
        <h3>Donations & Sponsorships</h3>
        <p>Support our mission by making a donation or sponsoring a pet. Contributions go directly toward food, medical
          care, and shelter improvements.</p>
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