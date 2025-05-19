<?php
include "db.php";
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

// Get distinct species
$speciesResult = mysqli_query($conn, "SELECT DISTINCT species FROM pets WHERE pet_status = 'available'");
$speciesList = [];
while ($row = mysqli_fetch_assoc($speciesResult)) {
  $speciesList[] = $row['species'];
}
sort($speciesList);

// Get distinct pet categories (breeds)
$categoryResult = mysqli_query($conn, "
    SELECT DISTINCT pc.category_name
    FROM pet_categories pc
    INNER JOIN pets p ON p.category_id = pc.category_id
    WHERE p.pet_status = 'available'
");
$categoryList = [];
while ($row = mysqli_fetch_assoc($categoryResult)) {
  $categoryList[] = $row['category_name'];
}
sort($categoryList);

// Handle filters
$filterSpecies = isset($_GET['species']) ? mysqli_real_escape_string($conn, $_GET['species']) : 'all';
$filterCategory = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : 'all';

// SQL with JOIN and filters
$sql = "
    SELECT pets.*, pet_categories.category_name 
    FROM pets 
    LEFT JOIN pet_categories ON pets.category_id = pet_categories.category_id 
    WHERE pet_status = 'available'
";

if ($filterSpecies !== 'all') {
  $sql .= " AND species = '$filterSpecies'";
}
if ($filterCategory !== 'all') {
  $sql .= " AND pet_categories.category_name = '$filterCategory'";
}


$pets = mysqli_query($conn, $sql);
if (!$pets) {
  die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pawfect Home | Pets</title>
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

  <section class="available-pets-container">
    <style>
      .available-pets-container {
        padding: 30px;
        background-color: #fff8e7;
        max-width: 1500px;
        margin: 0 auto;
        font-size: 0.8rem;
      }

      .section-headings {
        font-size: 2rem;
        margin-bottom: 8px;
        color: #333;
        text-align: center;
      }

      .section-subheadings {
        font-size: 1.1rem;
        color: #666;
        margin-bottom: 30px;
        text-align: center;
      }

      .filter-buttons {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 10px;
        margin-bottom: 20px;
      }

      .filter-btn {
        padding: 10px 20px;
        border: 2px solid #ff7e5f;
        background-color: white;
        color: #ff7e5f;
        font-weight: bold;
        border-radius: 20px;
        cursor: pointer;
        transition: all 0.3s ease;
      }

      .filter-btn:hover,
      .filter-btn.active {
        background-color: #ff7e5f;
        color: white;
      }

      .pets-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
      }

      .pet-card {
        border: 1px solid #ddd;
        border-radius: 12px;
        padding: 15px;
        background-color: #fafafa;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        transition: transform 0.2s ease;
      }

      .pet-card:hover {
        transform: translateY(-5px);
      }

      .pet-photo {
        width: 100%;
        height: 220px;
        object-fit: cover;
        border-radius: 8px;
        margin-bottom: 15px;
      }

      .pet-info h3 {
        margin: 0;
        font-size: 1.3rem;
        color: #333;
      }

      .pet-info p {
        margin: 5px 0;
        color: #555;
      }

      .cta-btn {
        display: inline-block;
        margin-top: 10px;
        background-color: #ff7e5f;
        color: #fff;
        padding: 8px 16px;
        border: none;
        border-radius: 8px;
        text-decoration: none;
        font-weight: bold;
        transition: background-color 0.3s ease;
      }

      .cta-btn:hover {
        background-color: #eb6841;
      }

      @media screen and (max-width: 600px) {
        .pet-photo {
          height: 180px;
        }
      }
    </style>

    <h2 class="section-headings">🐾 Browse Available Pets</h2>
    <p class="section-subheadings">Filter by species or breed to find your perfect companion!</p>

    <!-- Category Filter -->
    <div class="filter-buttons">
      <a href="petlist.php?species=<?= urlencode($filterSpecies); ?>">
        <button class="filter-btn <?= ($filterCategory === 'all') ? 'active' : '' ?>">All</button>
      </a>
      <?php foreach ($categoryList as $category): ?>
        <a href="petlist.php?species=<?= urlencode($filterSpecies); ?>&category=<?= urlencode($category); ?>">
          <button class="filter-btn <?= ($filterCategory === $category) ? 'active' : '' ?>">
            <?= htmlspecialchars($category); ?>
          </button>
        </a>
      <?php endforeach; ?>
    </div>

    <!-- Pets Grid -->
    <div class="pets-grid">
      <?php if (mysqli_num_rows($pets) > 0): ?>
        <?php while ($pet = mysqli_fetch_assoc($pets)): ?>
          <div class="pet-card" data-species="<?= htmlspecialchars($pet['species']) ?>">
            <img src="<?= !empty($pet['image_path']) ? htmlspecialchars($pet['image_path']) : 'images/default_pet.png' ?>"
              alt="<?= htmlspecialchars($pet['name']) ?>" class="pet-photo">
            <div class="pet-info">
              <h3><?= htmlspecialchars($pet['name']) ?></h3>
              <p><strong>Species:</strong> <?= htmlspecialchars($pet['species']) ?></p>
              <p><strong>Breed:</strong> <?= htmlspecialchars($pet['category_name']) ?></p>
              <p><strong>Description:</strong> <?= htmlspecialchars($pet['description'] ?? 'Unknown') ?></p>
              <p><strong>Health Condition:</strong> <?= htmlspecialchars($pet['health_condition']) ?></p>
              <a href="petdetails.php?id=<?= $pet['pet_id']; ?>" class="cta-btn">View Details</a>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p style="text-align: center;">😿 No pets found in this category.</p>
      <?php endif; ?>
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