<?php
include "db.php";

// Updated SQL with JOIN to get category name
$sql = "
    SELECT pets.*, pet_categories.category_name 
    FROM pets 
    LEFT JOIN pet_categories ON pets.category_id = pet_categories.category_id 
    WHERE pet_status = 'available'
";
$result = mysqli_query($conn, $sql);

// Get distinct species for filter buttons
$speciesQuery = mysqli_query($conn, "SELECT DISTINCT species FROM pets WHERE pet_status = 'available'");
$speciesList = [];

while ($row = mysqli_fetch_assoc($speciesQuery)) {
  $speciesList[] = $row['species'];
}
sort($speciesList); // Optional: sort species alphabetically
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pawfect Home | Pets</title>
  <link rel="icon" href="images/logo.png" type="image/png">
  <link rel="stylesheet" href="style.css" />
  <style>
    .section-heading {
      font-size: 2.5rem;
      color: var(--dark-yellow);
      margin-bottom: 10px;
    }

    .section-subheading {
      font-size: 1rem;
      color: #444;
      margin-bottom: 25px;
      max-width: 700px;
      margin-left: auto;
      margin-right: auto;
    }

    .filter-buttons {
      text-align: center;
      margin-bottom: 30px;
      flex-wrap: wrap;
      display: flex;
      justify-content: center;
      gap: 15px;
    }

    .filter-btn {
      background-color: var(--dark-yellow);
      color: white;
      border: none;
      padding: 10px 16px;
      border-radius: var(--border-radius-m);
      font-size: 1rem;
      cursor: pointer;
      transition: background-color 0.3s, transform 0.2s;
    }

    .filter-btn:hover {
      background-color: var(--primary-color);
      transform: translateY(-2px);
    }

    .filter-btn.active {
      background-color: var(--highlight-color);
      color: #333;
      font-weight: bold;
    }

    .cta-btn {
      cursor: pointer;
      display: inline-block;
      margin-top: 10px;
      padding: 10px 18px;
      background-color: rgb(234, 156, 0);
      color: white;
      border: none;
      border-radius: 8px;
      font-weight: bold;
      text-decoration: none;
    }

    .modal-overlay {
      display: none;
      position: fixed;
      z-index: 999;
      inset: 0;
      background-color: rgba(0, 0, 0, 0.4);
      backdrop-filter: blur(8px);
      justify-content: center;
      align-items: center;
    }

    .modal-content {
      background-color: white;
      padding: 30px;
      border-radius: 16px;
      width: 90%;
      max-width: 500px;
      text-align: center;
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
    }

    .modal-content img {
      width: 100%;
      max-height: 250px;
      object-fit: cover;
      border-radius: 12px;
      margin-bottom: 20px;
    }

    .modal-content h2 {
      margin-bottom: 10px;
      color: #333;
    }

    .modal-content p {
      margin: 8px 0;
      color: #555;
    }

    .close-btn {
      margin-top: 20px;
      padding: 10px 20px;
      background-color: #e67e22;
      color: white;
      border: none;
      border-radius: 8px;
      cursor: pointer;
    }

    .close-btn:hover {
      background-color: #cf711f;
    }
  </style>
</head>

<body>

  <header id="mainHeader">
    <!-- Header content -->

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
    </div>
  </header>


  <section class="available-pets-section">
    <h2 class="section-heading" style="text-align: center;">Available Pets</h2>
    <p class="section-subheading" style="text-align: center;">Meet the lovable companions currently up for adoption.</p>
    <p> Filter by species and find your perfect match!</p>

    <p>‎ </p>
    <p>‎ </p>

    <!-- Filter Buttons -->
    <div class="filter-buttons">
      <button class="filter-btn active" onclick="filterPets('all', this)">All</button>
      <?php foreach ($speciesList as $species): ?>
        <button class="filter-btn" onclick="filterPets('<?= htmlspecialchars($species); ?>', this)">
          <?= htmlspecialchars($species); ?>s
        </button>
      <?php endforeach; ?>
    </div>

    <p>‎ </p>

    <!-- Pets Grid -->
    <div class="pets-grid">
      <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <div class="pet-card" data-species="<?= htmlspecialchars($row['species']) ?>">
          <img src="<?= !empty($row['image_path']) ? htmlspecialchars($row['image_path']) : 'images/default_pet.png' ?>"
            alt="<?= htmlspecialchars($row['name']) ?>" class="pet-photo">
          <div class="pet-info">
            <h3><?= htmlspecialchars($row['name']) ?></h3>
            <p><strong>Species:</strong> <?= htmlspecialchars($row['species']) ?></p>
            <p><strong>Description:</strong> <?= htmlspecialchars($row['description']) ?></p>
            <button class="cta-btn"
              onclick='openModal(`<?= addslashes(htmlspecialchars($row["name"])) ?>`, `<?= addslashes(htmlspecialchars($row["species"])) ?>`, `<?= addslashes(htmlspecialchars($row["description"])) ?>`, `<?= !empty($row["image_path"]) ? htmlspecialchars($row["image_path"]) : "images/default_pet.png" ?>`)'>View</button>
          </div>
        </div>
      <?php } ?>
    </div>
  </section>

  <!-- Modal -->
  <div id="petModal" class="modal-overlay" onclick="closeModal(event)">
    <div class="modal-content">
      <img id="modalImage" src="" alt="Pet Image">
      <h2 id="modalName"></h2>
      <p><strong>Species:</strong> <span id="modalSpecies"></span></p>
      <p id="modalDescription"></p>
      <button class="cta-btn" onclick="location.href='login.php'">Login to view more</button>
      <p></p>
      <button class="close-btn" onclick="closeModal()">Close</button>
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
    function filterPets(species, btn) {
      const allCards = document.querySelectorAll('.pet-card');
      allCards.forEach(card => {
        const petSpecies = card.getAttribute('data-species');
        if (species === 'all' || petSpecies === species) {
          card.style.display = 'block';
        } else {
          card.style.display = 'none';
        }
      });

      // Active button UI
      document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
    }

    // Dropdown nav toggle
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

  <script>
    function openModal(name, species, description, imagePath) {
      document.getElementById('modalName').textContent = name;
      document.getElementById('modalSpecies').textContent = species;
      document.getElementById('modalDescription').textContent = description;
      document.getElementById('modalImage').src = imagePath;

      document.getElementById('petModal').style.display = 'flex';

      // Hide header
      document.getElementById('mainHeader').style.display = 'none';
    }

    function closeModal(event) {
      const modal = document.getElementById('petModal');
      if (!event || event.target === modal || event.target.classList.contains('close-btn')) {
        modal.style.display = 'none';

        // Show header back
        document.getElementById('mainHeader').style.display = 'block'; // or 'block' depending on your layout
      }
    }
  </script>

</body>

</html>