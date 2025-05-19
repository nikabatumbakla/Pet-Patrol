<?php
include "db.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pawfect Home | About</title>
  <link rel="icon" href="images/logo.png" type="image/png">
  <link rel="stylesheet" href="style.css" />
  <style>
    .about-section h2 {
      font-size: 2.5rem;
      color: var(--dark-yellow);
      margin-bottom: 20px;
      position: relative;
    }

    .image-gallery {
      display: flex;
      gap: 20px;
      justify-content: center;
      flex-wrap: wrap;
    }

    .image-gallery img {
      width: 100%;
      max-width: 350px;
      height: auto;
      border-radius: var(--border-radius-m);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
      transition: transform 0.3s ease;
    }

    .image-gallery img:hover {
      transform: scale(1.03);
      cursor: pointer;
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

  <section class="about-section">
    <h2>About Us</h2>
    <div class="image-gallery">
      <img src="images/doglovinghuman.jpg" alt="Animal 1">
      <img src="images/petinhealthcare.jpg" alt="Animal 3">
    </div>

    <div class="about-horizontal-wrapper">
      <div class="about-card">
        <h3>Our Mission</h3>
        <p class="about-description">
          This system is created with love and dedication by three girls from the Bachelor of Science in Information
          Technology program. Their mission is to build a user-friendly and compassionate platform that makes pet
          adoption easier, more transparent, and filled with joy.
          <br><br>
      </div>
      <div class="about-card">
        <h3>Our Vision</h3>
        <p class="about-description">
          🌿 <b> Every Life Matters: Pet Adoption and Application System</b> 🌿 is designed to connect loving adopters
          with pets in need. We believe in a world where every pet finds love, and every adopter finds a lifelong
          companion. 💛🐾
        </p>
      </div>
      <div class="about-card">
        <h3>Organization History</h3>
        <p class="about-description">
          The idea was born from a deep passion for animals and the desire to create a digital bridge between rescue
          shelters and caring adopters. From a simple college project to a heart-driven mission, the platform now
          supports shelters in streamlining adoptions and donations.
        </p>
      </div>
      <div class="about-card">
        <h3>PonyoSosuke Foundation</h3>
        <p class="about-description">
          Located in <b>Camarines Sur</b>, the <b>PonyoSosuke Adoption Center</b> is a beacon of hope for maltreated,
          abused, and stray animals. Inspired by the Ghibli animation "Ponyo", our foundation believes in
          transformation, healing, and love for all beings.
          <br><br>
      </div>
      <div class="about-card">
        <h3>>></h3>
        <p class="about-description">
          We rescue, rehabilitate, and rehome animals, giving them a chance at the life they deserve. With compassion as
          our core, we provide medical care, shelter, and emotional support until they find their forever homes.
        </p>
      </div>
      <div class="about-card">
        <h3>Why Pet Adoption Matters</h3>
        <p class="about-description">
          These innocent souls may not speak our language, but in their eyes and soft whispers, they express the desire
          to be loved. Adoption not only saves lives—it brings unmatched companionship into yours.
          <br><br>
      </div>
      <div class="about-card">
        <h3>>></h3>
        <p class="about-description">
          We aim to educate future pet owners, help match pets to the right homes, and make adoption more accessible
          through technology. Together, we create a world where every tail wags with happiness and every paw finds a
          home.
        </p>
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