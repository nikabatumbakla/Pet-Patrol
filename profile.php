<?php
include "db.php";
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

$user_id = $_SESSION['user_id'];
$query = mysqli_query($conn, "SELECT u.*, a.street_address, a.city, a.state_province, a.postal_code, a.country
                              FROM users u
                              LEFT JOIN addresses a ON u.user_id = a.user_id
                              WHERE u.user_id = '$user_id'");
$user = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pawfect Home | My Profile</title>
  <link rel="icon" href="images/logo.png" type="image/png">
  <link rel="stylesheet" href="style.css" />
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      background: rgb(255, 245, 245);
    }

    .profile-container {
      background-color: rgb(254, 227, 227);
      max-width: 1000px;
      margin: 40px auto;
      padding: 20px;
      border-radius: 16px;
      box-shadow: 0 5px 28px rgba(255, 236, 236, 0.1);
      text-align: center;
    }

    /* Headings */
    .section-heading {
      font-size: 2.1rem;
      font-weight: bold;
      color: #333;
      margin-top: 1px;
      margin-bottom: 15px;
    }

    /* Profile card */
    .profile-card {
      background: white;
      padding: 25px;
      border-radius: 12px;
      text-align: left;
    }

    .profile-info p {
      font-size: 1.1rem;
      margin: 12px 0;
      color: #444;
    }

    .profile-info strong {
      font-weight: 600;
      color: #222;
    }

    /* Modal styling */
    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0, 0, 0, 0.3);
      backdrop-filter: blur(3px);
      /* subtle blur outside */
    }

    .modal-content {
      background-color: white;
      margin: 8% auto;
      padding: 30px;
      border-radius: 12px;
      max-width: 500px;
      position: relative;
      box-shadow: 0 8px 24px rgba(255, 215, 215, 0.15);
    }

    .modal-content h2 {
      margin-top: 0;
      font-size: 1.8rem;
      color: #333;
      margin-bottom: 20px;
    }

    .form-group {
      margin-bottom: 15px;
      text-align: left;
    }

    .form-group label {
      display: block;
      margin-bottom: 6px;
      font-weight: 600;
      color: #333;
    }

    .form-group input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 1rem;
    }

    /* Close (X) button */
    .close {
      position: absolute;
      right: 20px;
      top: 15px;
      font-size: 1.5rem;
      color: #888;
      cursor: pointer;
    }

    .close:hover {
      color: #444;
    }

    /* Blur background when modal opens */
    .blur {
      filter: blur(5px);
      transition: filter 0.3s ease;
    }

    .form-group {
      margin-bottom: 15px;
    }

    .form-group label {
      font-weight: bold;
      display: block;
    }

    .form-group input {
      width: 100%;
      padding: 8px;
      border-radius: 6px;
      border: 1px solid #ccc;
    }

    .save-btn {
      background-color: rgb(247, 190, 56);
      color: #fff;
      padding: 10px 20px;
      border: none;
      border-radius: 8px;
      font-weight: bold;
      cursor: pointer;
    }

    .save-btn:hover {
      background-color: #1e874b;
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

    .action-buttons {
      display: flex;
      flex-direction: column;
      align-items: left;
      gap: 25px;
      margin-top: 30px;
    }

    .profile-title {
      display: flex;
      align-items: left;
      justify-content: left;
      gap: 30px;
      /* space between image and text */
      margin-bottom: 30px;
    }

    .profile-title-logo {
      width: 80px;
      height: 80px;
      object-fit: contain;
    }

    .section-heading {
      font-size: 2.2rem;
      font-weight: bold;
      color: #333;
      margin: 0;
    }

    .profile-actions {
      display: flex;
      justify-content: flex-end;
      /* Align to the left */
      gap: 15px;
      /* Space between buttons */
      margin-top: 30px;
      padding-left: 10px;
      /* Optional: indent from the card border */
    }

    .edit-btn,
    .action-btn {
      padding: 10px 20px;
      font-size: 15px;
      font-weight: 600;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: all 0.3s ease;
      min-width: 140px;
      text-align: center;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .edit-btn {
      background-color: #ff7e5f;
      color: #fff;
    }

    .edit-btn:hover {
      background-color: #e76447;
    }

    .action-btn.quiz {
      background-color: #ffc107;
      color: white;
    }

    .action-btn.quiz:hover {
      background-color: #e0a800;
    }
  </style>
</head>

<body>

  <div id="blur-background">
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

    <section>
      <div class="profile-container">
        <div class="profile-actions">
          <button id="editProfileBtn" class="edit-btn">✏️ Edit Profile</button>
          <button id="openQuizModal" class="action-btn quiz">Quiz Now</button>
        </div>
        <div class="profile-title-container">
          <div class="profile-title">
            <img src="images/profile1.png" alt="Logo" class="profile-title-logo">
            <h2 class="section-heading">My Profile</h2>
          </div>

          <div class="profile-card">
            <div class="profile-info">
              <p><strong>Full Name:</strong> <?= htmlspecialchars($user['fullname']); ?></p>
              <p><strong>Email:</strong> <?= htmlspecialchars($user['email']); ?></p>
              <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone_num']); ?></p>
              <p><strong>Address:</strong>
                <?= htmlspecialchars($user['street_address'] . ', ' . $user['city'] . ', ' . $user['state_province'] . ', ' . $user['postal_code'] . ', ' . $user['country']); ?>
              </p>
              <p><strong>Username:</strong> <?= htmlspecialchars($user['username']); ?></p>
            </div>
          </div>
        </div>
    </section>
  </div>

  <!-- Quiz Modal -->
  <div id="quizModal" class="modal">
    <div class="modal-content">
      <span class="close" id="closeQuizModal">&times;</span>
      <h2>QUIZ PET TRIVIA!!!</h2>
      <p>HELLO!!1 we have an awesome quiz trivia about pets. Just click <strong>Quiz Now</strong> if you want
        to join, or <strong>Cancel</strong> if you don't 😊</p>
      <div style="text-align: right; margin-top: 20px;">
        <button id="quizNowBtn" class="save-btn">Quiz Now</button>
        <button id="cancelQuizBtn" class="edit-btn" style="margin-left: 10px;">Cancel</button>
      </div>
    </div>
  </div>


  <!-- Edit Modal -->
  <div id="editModal" class="modal">
    <div class="modal-content">
      <span class="close" id="closeModal">&times;</span>
      <h2>Edit Profile</h2>
      <form method="POST" action="update_profile.php">
        <div class="form-group">
          <label>Full Name</label>
          <input type="text" name="fullname" value="<?= htmlspecialchars($user['fullname']) ?>" required />
        </div>
        <div class="form-group">
          <label>Email</label>
          <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required />
        </div>
        <div class="form-group">
          <label>Phone</label>
          <input type="text" name="phone_num" value="<?= htmlspecialchars($user['phone_num']) ?>" required />
        </div>
        <div class="form-group">
          <label>Street Address</label>
          <input type="text" name="street_address" value="<?= htmlspecialchars($user['street_address']) ?>" />
        </div>
        <div class="form-group">
          <label>City</label>
          <input type="text" name="city" value="<?= htmlspecialchars($user['city']) ?>" />
        </div>
        <div class="form-group">
          <label>State/Province</label>
          <input type="text" name="state_province" value="<?= htmlspecialchars($user['state_province']) ?>" />
        </div>
        <div class="form-group">
          <label>Postal Code</label>
          <input type="text" name="postal_code" value="<?= htmlspecialchars($user['postal_code']) ?>" />
        </div>
        <div class="form-group">
          <label>Country</label>
          <input type="text" name="country" value="<?= htmlspecialchars($user['country']) ?>" />
        </div>
        <div style="text-align: right;">
          <button type="submit" class="save-btn">Save Changes</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    const modal = document.getElementById("editModal");
    const openBtn = document.getElementById("editProfileBtn");
    const closeBtn = document.getElementById("closeModal");
    const blurWrapper = document.getElementById("blur-background");

    openBtn.onclick = function () {
      modal.style.display = "block";
      blurWrapper.classList.add("blur");
    }

    closeBtn.onclick = function () {
      modal.style.display = "none";
      blurWrapper.classList.remove("blur");
    }

    window.onclick = function (event) {
      if (event.target === modal) {
        modal.style.display = "none";
        blurWrapper.classList.remove("blur");
      }
    }
  </script>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const quizModal = document.getElementById('quizModal');
      const blurBackground = document.getElementById('blur-background');

      document.getElementById('openQuizModal').addEventListener('click', function () {
        quizModal.style.display = 'block';
        blurBackground.classList.add('blur');
      });

      document.getElementById('closeQuizModal').addEventListener('click', closeQuizModal);
      document.getElementById('cancelQuizBtn').addEventListener('click', closeQuizModal);

      document.getElementById('quizNowBtn').addEventListener('click', function () {
        window.location.href = 'quiz.php'; // Change this to your quiz page
      });

      function closeQuizModal() {
        quizModal.style.display = 'none';
        blurBackground.classList.remove('blur');
      }
    });
  </script>

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

  <footer class="site-footer">
    <div class="footer-content">
      <p>Reach us at:
        <a href="mailto:ponyososukesheltercenter@gmail.com">ponyososukesheltercenter@gmail.com</a>
      </p>
      <p>&copy; 2025 ALL RIGHTS RESERVED.</p>
    </div>
  </footer>

</body>

</html>