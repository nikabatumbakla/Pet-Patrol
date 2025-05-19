<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: quiz.php");
  exit();
}
$username = $_SESSION['username']; // assuming you store user's name in session

if (!empty($_SESSION['pet_deleted_users']) && in_array($user_id, $_SESSION['pet_deleted_users'])) {
  echo '
    <div class="popup-notification">
        The pet you applied for was removed by the admin.
        <button onclick="this.parentElement.style.display=\'none\'">✖</button>
    </div>';

  $_SESSION['pet_deleted_users'] = array_diff($_SESSION['pet_deleted_users'], [$user_id]);
  if (empty($_SESSION['pet_deleted_users'])) {
    unset($_SESSION['pet_deleted_users']);
  }
}

if (!empty($_SESSION['flash_message'])) {
  echo '
    <div class="popup-notification">
        ' . htmlspecialchars($_SESSION['flash_message']) . '
        <button onclick="this.parentElement.style.display=\'none\'">✖</button>
    </div>';
  unset($_SESSION['flash_message']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pawfect Home</title>
  <link rel="icon" href="images/logo.png" type="image/png">
  <link rel="stylesheet" href="style.css" />
  <style>
    .popup-notification {
      background-color: #ffe094;
      color: #333;
      border-left: 5px solid #e1a800;
      padding: 16px;
      margin: 20px;
      border-radius: 8px;
      position: relative;
      font-size: 16px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      animation: fadeIn 0.4s ease-in-out;
    }

    .popup-notification button {
      position: absolute;
      top: 10px;
      right: 12px;
      background: none;
      border: none;
      font-size: 18px;
      cursor: pointer;
      color: #555;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(-8px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .faq-section {
      max-width: 2000px;
      margin: 0px auto;
      padding: 30px;
      background-color: white;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
      font-family: 'Segoe UI', sans-serif;
    }

    .faq-section h2 {
      text-align: center;
      font-size: 32px;
      margin-bottom: 40px;
      color: #333;
    }

    .faq {
      display: flex;
      gap: 20px;
      align-items: flex-start;
      margin-bottom: 30px;
      padding: 20px;
      border-radius: 14px;
      background: rgb(237, 183, 96);
      transition: all 0.3s ease;
    }

    .faq:hover {
      background: rgb(255, 255, 255);
    }

    .faq img {
      width: 80px;
      height: 80px;
      object-fit: contain;
      border-radius: 12px;
      flex-shrink: 0;
    }

    .faq-content {
      flex: 1;
    }

    .faq-content h3 {
      font-size: 20px;
      margin: 0 0 10px;
      color: #f58220;
      cursor: pointer;
    }

    .faq-content p {
      margin: 0;
      color: #444;
      line-height: 1.6;
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

  <main>
    <section class="hero-section">
      <div class="section-content">
        <div class="hero-details">
          <h2 class="title" style="color: white;">ALL LIVES MATTER</h2>
          <h3 class="subtitle" style="color: white;">A variety of lovely pets that need your care and affection</h3>
          <p class="description" style="color: white;">Be the hero they’ve been waiting for. Your future best friend is
            just one step away.</p>
          <div class="buttons">
            <button class="button adoptpet" onclick="location.href='petlist.php'">View Pets</button>
          </div>
        </div>
        <div class="hero-image-wrapper">
          <img src="images/landingpage.jpg" alt="Hero Image" class="hero-image">
        </div>
      </div>
    </section>

    <section class="pet-hero-stories">
      <h2 class="story-title">Heroic Pets & Their Inspiring Stories</h2>
      <div class="story-container">
        <div class="story-item">
          <img src="images/kabang (1).jpg" alt="Brave Dog">
          <h3>Kabang - The Hero Dog</h3>
          <p>Kabang, a mixed-breed Aspin from Zamboanga City, saved two children from an oncoming motorcycle in 2011.
            She bravely leaped in front of the vehicle, losing her snout but surviving.</p>
        </div>
        <div class="story-item">
          <img src="images/sergio.jpg" alt="Dog">
          <h3> Sergio – Military Dog Hero</h3>
          <p>A Belgian Malinois serving with the Philippine Army, Sergio sniffed out IEDs and protected troops during
            operations. He saved countless lives and was honored with a military burial.</p>
        </div>
        <div class="story-item">
          <img src="images/shadow.jpg" alt="dog">
          <h3> Dog “Shadow” – Rescuer Dog in Yolanda </h3>
          <p>Shadow, a volunteer dog, helped sniff survivors and bodies during the aftermath of Typhoon Yolanda in 2013.
          </p>
        </div>
        <div class="story-item">
          <img src="images/tisay.jpg" alt="Mother Cat">
          <h3>Tisay the Cat – House Fire Alarm</h3>
          <p> In Quezon City, Tisay meowed loudly and persistently during a house fire in 2022, waking her family just
            in time to escape. The family credits her for saving their lives.
        </div>
      </div>
    </section>

    <section class="how-to-adopt">
      <h2 class="adopt-title">How to Adopt a Pet</h2>
      <div class="adopt-container">
        <div class="adopt-step">
          <img src="images/search (1).png" alt="Search Icon">
          <h3>Find Your Pet</h3>
          <p>Browse our list of adorable pets looking for a loving home.</p>
        </div>
        <div class="adopt-step">
          <img src="images/form.png" alt="Form Icon">
          <h3>Submit an Application</h3>
          <p>Fill out an easy adoption form and tell us about yourself.</p>
        </div>
        <div class="adopt-step">
          <img src="images/home (1).png" alt="Home Icon">
          <h3>Welcome Home</h3>
          <p>Once approved, bring your new best friend home with love.</p>
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