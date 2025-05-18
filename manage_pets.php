<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin_name'])) {
  header('Location: adminlogin.php');
  exit();
}

// Get list of all species from DB
$speciesResult = mysqli_query($conn, "SELECT DISTINCT species FROM pets");
$speciesList = [];

while ($row = mysqli_fetch_assoc($speciesResult)) {
  $speciesList[] = $row['species'];
}
sort($speciesList); // optional: sort alphabetically

// Get filter from GET parameter
$filter = $_GET['species'] ?? 'all';

if ($filter === 'all') {
  $pets = mysqli_query($conn, "SELECT pet_id, name, species, description, health_condition, vaccination_history, behavior_notes, adoption_requirements, pet_status AS status, image_path FROM pets");
} else {
  $stmt = $conn->prepare("SELECT pet_id, name, species, description, health_condition, vaccination_history, behavior_notes, adoption_requirements, pet_status AS status, image_path FROM pets WHERE species = ?");
  $stmt->bind_param("s", $filter);
  $stmt->execute();
  $pets = $stmt->get_result();
}


if (!$pets) {
  die("Query failed: " . mysqli_error($conn));
}


$success = ''; // Initialize success message
$error = '';   // Initialize error message

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $name = mysqli_real_escape_string($conn, $_POST['name']);
  $species = mysqli_real_escape_string($conn, $_POST['species']);
  $health_condition = mysqli_real_escape_string($conn, $_POST['health_condition']);
  $description = mysqli_real_escape_string($conn, $_POST['description']);
  $vaccination_history = mysqli_real_escape_string($conn, $_POST['vaccination_history']);
  $behavior_notes = mysqli_real_escape_string($conn, $_POST['behavior_notes']);
  $adoption_requirements = mysqli_real_escape_string($conn, $_POST['adoption_requirements']);

  $pet_status = 'available'; // default

  // Handle category
  if ($_POST['category_id'] === 'new') {
    $new_category = mysqli_real_escape_string($conn, $_POST['new_category_name']);
    $insertCat = "INSERT INTO pet_categories (category_name) VALUES ('$new_category')";
    if (mysqli_query($conn, $insertCat)) {
      $category_id = mysqli_insert_id($conn);
    } else {
      $error = "Failed to add new category: " . mysqli_error($conn);
      return; // Exit early to avoid broken insert
    }
  } else {
    $category_id = intval($_POST['category_id']);

    // Optional safety check
    $catCheck = mysqli_query($conn, "SELECT category_id FROM pet_categories WHERE category_id = $category_id");
    if (mysqli_num_rows($catCheck) === 0) {
      $error = "Selected category does not exist.";
      return;
    }
  }

  // Image Upload
  $image_name = str_replace(' ', '_', $_FILES['image']['name']);
  $image_tmp = $_FILES['image']['tmp_name'];
  $image_type = $_FILES['image']['type'];
  $upload_dir = 'images/uploads/';
  $image_path = $upload_dir . basename($image_name);

  if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
  }

  $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
  if (!in_array($image_type, $allowed_types)) {
    $error = "Only JPG, PNG, and GIF formats are allowed.";
  } elseif (move_uploaded_file($image_tmp, $image_path)) {
    // Proceed with insert
    $query = "INSERT INTO pets (
            name, species, health_condition, pet_status, description,
            category_id, image_path, vaccination_history, behavior_notes, adoption_requirements
        ) VALUES (
            '$name', '$species', '$health_condition', '$pet_status', '$description',
            $category_id, '$image_path', '$vaccination_history', '$behavior_notes', '$adoption_requirements'
        )";

    if (mysqli_query($conn, $query)) {
      $success = "Pet added successfully.";
      header("Location: manage_pets.php");
      exit();
    } else {
      $error = "Database error: " . mysqli_error($conn);
    }
  } else {
    $error = "Image upload failed. Check folder permissions.";
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Manage Pets | Admin Panel</title>
  <link rel="icon" href="images/logo.png" type="image/png">
  <link rel="stylesheet" href="style.css" />
  <style>
    .admin-container {
      max-width: 1500px;
      margin: 20px auto;
      padding: 30px;
      border-radius: 12px;
    }

    /* Main Heading */
    .admin-container h1 {
      font-size: 2rem;
      margin-bottom: 25px;
      color: #333;
      text-align: center;
    }

    /* Filter Buttons */
    .filter-buttons {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin-bottom: 20px;
      justify-content: center;
    }

    .filter-btn {
      padding: 8px 16px;
      background-color: #f2f2f2;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-weight: 500;
      transition: background 0.3s ease;
    }

    .filter-btn:hover {
      background-color: #e7e7e7;
    }

    .filter-btn.active {
      background-color: #ff9f87;
      color: white;
    }

    /* Add Pet Button */
    .btn-primary {
      display: inline-block;
      background-color: #ff7e5f;
      justify-content: center;
      color: #fff;
      padding: 10px 18px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 600;
      margin: 20px 0;
      transition: background 0.3s ease;
    }

    .btn-primary:hover {
      background-color: #e7684a;
    }

    .table-container {
      display: flex;
      justify-content: center;
      margin-top: 1px;
      align-items: center;
      min-height: 100vh;
      overflow-x: auto;
    }

    .admin-table {
      width: 50%;
      text-align: center;
      font-size: 14px;
      max-width: 1200px;
      border-collapse: separate;
      margin-top: 50px;
      border-spacing: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #fff;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 8px 24px rgba(255, 175, 175, 0.69);
    }

    .admin-table thead {
      background-color: #f88379;
      /* Soft coral header */
      color: white;
    }

    .admin-table th,
    .admin-table td {
      padding: 14px 16px;
      text-align: left;
      border-bottom: 1px solid #eee;
      vertical-align: top;
    }

    .admin-table tbody tr:hover {
      background-color: #fff5f5;
    }

    .admin-table img.pet-thumbnail {
      width: 80px;
      height: 80px;
      object-fit: cover;
      border-radius: 10px;
      border: 2px solid #ffa07a;
    }

    /* Action Buttons */
    .btn-edit,
    .btn-delete {
      display: inline-block;
      padding: 6px 20px;
      font-size: 0.9rem;
      font-weight: 500;
      border-radius: 8px;
      text-decoration: none;
      margin-right: 6px;
      transition: background-color 0.3s, color 0.3s;
    }

    .btn-edit {
      background-color: #4caf50;
      color: white;
    }

    .btn-edit:hover {
      background-color: #388e3c;
    }

    .btn-delete {
      background-color: #f44336;
      color: white;
    }

    .btn-delete:hover {
      background-color: #d32f2f;
    }

    /* Pet Thumbnail */
    .pet-thumbnail {
      width: 60px;
      height: 60px;
      object-fit: cover;
      border-radius: 8px;
      border: 1px solid #ccc;
    }

    /* Edit/Delete Buttons */
    .btn-edit,
    .btn-delete {
      padding: 6px 12px;
      margin: 0 4px;
      border: none;
      border-radius: 6px;
      font-size: 0.9rem;
      text-decoration: none;
      color: white;
      transition: background 0.3s ease;
    }

    .btn-edit {
      background-color: #6cbf84;
    }

    .btn-edit:hover {
      background-color: #57a96e;
    }

    .btn-delete {
      background-color: #e57373;
    }

    .btn-delete:hover {
      background-color: #d05c5c;
    }

    .modal-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      backdrop-filter: blur(6px);
      background-color: rgba(0, 0, 0, 0.4);
      display: none;
      justify-content: center;
      align-items: center;
      z-index: 9999;
    }

    .modal-content {
      background: #fff;
      padding: 30px;
      border-radius: 10px;
      max-width: 600px;
      width: 90%;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
      position: relative;
      overflow-y: auto;
      max-height: 90vh;
    }

    .close-btn {
      position: absolute;
      top: 15px;
      right: 20px;
      font-size: 24px;
      cursor: pointer;
      color: #444;
    }

    .modal-content h2 {
      margin-top: 0;
    }

    .modal-content input,
    .modal-content textarea,
    .modal-content select,
    .modal-content button {
      width: 100%;
      margin-bottom: 15px;
      padding: 10px;
      border-radius: 6px;
      border: 1px solid #ccc;
    }

    .modal-content button {
      background-color: #e1a800;
      color: white;
      border: none;
      cursor: pointer;
      font-weight: bold;
      transition: background-color 0.1s;
    }

    .modal-content button:hover {
      background-color: #c88f0a;
    }

    .success-alert {
      background-color: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
      padding: 12px 20px;
      margin: 20px auto;
      text-align: center;
      border-radius: 8px;
      width: fit-content;
      max-width: 90%;
      font-weight: bold;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      animation: fadeOut 4s ease-in-out forwards;
    }

    @keyframes fadeOut {
      0% {
        opacity: 1;
      }

      75% {
        opacity: 1;
      }

      100% {
        opacity: 0;
        display: none;
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
          <span>👋 Welcome, <?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'User'); ?>!</span>
        </div>
      </div>

      <nav class="user-nav">
        <ul>
          <li><a href="adminpage.php">Dashboard</a></li>
          <li><a href="manage_pets.php">Manage Pets</a></li>
          <li><a href="manage_applications.php">Applications</a></li>
          <li><a href="manage_users.php">User Roles</a></li>
          <li><a href="logout.php" class="logout-btn">Logout</a></li>
        </ul>
      </nav>
  </header>

  <main class="admin-container">

    <?php if (isset($_GET['message']) && $_GET['message'] === 'deleted'): ?>
      <div class="success-alert">🐾 Pet deleted successfully.</div>
    <?php endif; ?>

    <?php if (isset($_GET['message']) && $_GET['message'] === 'updated'): ?>
      <div class="success-alert">🐾 Pet updated successfully.</div>
    <?php endif; ?>


    <h1>Manage Pets</h1>

    <!-- Filter Buttons -->
    <div class="filter-buttons">
      <a href="manage_pets.php?species=all">
        <button class="filter-btn <?= ($filter === 'all') ? 'active' : '' ?>">All</button>
      </a>
      <?php foreach ($speciesList as $species): ?>
        <a href="manage_pets.php?species=<?= urlencode($species); ?>">
          <button class="filter-btn <?= ($filter === $species) ? 'active' : '' ?>">
            <?= htmlspecialchars($species); ?>
          </button>
        </a>
      <?php endforeach; ?>
    </div>



    <div style="text-align: center;">
      <button onclick="openAddPetModal()" class="btn-primary">Add New Pet</button>
    </div>

    <div style="text-align: center; margin-bottom: 1px;">
      <a href="generate_pet_report.php" target="_blank" class="btn-primary">📄 Download Pet Report (PDF)</a>
    </div>


    <!-- Modal Form Wrapper -->
    <div id="addPetModal" class="modal-overlay">
      <div class="modal-content">
        <span class="close-btn" onclick="closeAddPetModal()">&times;</span>
        <form method="POST" enctype="multipart/form-data">
          <h2>Add New Pet</h2>

          <?php if ($success): ?>
            <p class="success-msg"><?php echo $success; ?></p>
          <?php elseif ($error): ?>
            <p class="error-msg"><?php echo $error; ?></p>
          <?php endif; ?>

          <label>Pet Name</label>
          <input type="text" name="name" required>

          <label>Species</label>
          <input type="text" name="species" required>

          <label>Category</label>
          <select name="category_id" id="category_select" onchange="toggleNewCategoryField()">
            <option value="">-- Select Category --</option>
            <?php
            $categories = mysqli_query($conn, "SELECT category_id, category_name FROM pet_categories");
            while ($cat = mysqli_fetch_assoc($categories)) {
              echo "<option value='" . $cat['category_id'] . "'>" . htmlspecialchars($cat['category_name']) . "</option>";
            }
            ?>
            <option value="new">+ Add New Category</option>
          </select>

          <!-- Hidden field for new category -->
          <div id="new_category_container" style="display: none;">
            <label>New Category Name</label>
            <input type="text" name="new_category_name" id="new_category_input">
          </div>



          <label>Health Condition</label>
          <select name="health_condition" required>
            <option value="healthy">Healthy</option>
            <option value="needs_attention">Needs Attention</option>
            <option value="ill">Ill</option>
          </select>

          <label>Description</label>
          <textarea name="description" rows="3" required></textarea>

          <label>Vaccination History</label>
          <textarea name="vaccination_history" rows="3"></textarea>

          <label>Behavior Notes</label>
          <textarea name="behavior_notes" rows="3"></textarea>

          <label>Adoption Requirements</label>
          <textarea name="adoption_requirements" rows="3"></textarea>

          <label>Pet Image</label>
          <input type="file" name="image" accept="image/*" required>

          <button type="submit" class="btn">Add Pet</button>
        </form>
      </div>
    </div>



    <div class="table-container">
      <table class="admin-table">
        <thead>
          <tr>
            <th>Photo</th>
            <th>Name</th>
            <th>Species</th>
            <th>Health Condition</th>
            <th>Description</th>
            <th>Vaccination History</th>
            <th>Behavior Notes</th>
            <th>Adoption Requirements</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = mysqli_fetch_assoc($pets)): ?>
            <tr>
              <td>
                <?php if (!empty($row['image_path'])): ?>
                  <img src="<?= htmlspecialchars($row['image_path']); ?>" alt="Pet" class="pet-thumbnail">
                <?php else: ?>
                  No Image
                <?php endif; ?>
              </td>
              <td><?= htmlspecialchars($row['name']); ?></td>
              <td><?= htmlspecialchars($row['species']); ?></td>
              <td><?= htmlspecialchars($row['health_condition']); ?></td>
              <td><?= htmlspecialchars($row['description']); ?></td>
              <td><?= htmlspecialchars($row['vaccination_history']); ?></td>
              <td><?= htmlspecialchars($row['behavior_notes']); ?></td>
              <td><?= htmlspecialchars($row['adoption_requirements']); ?></td>
              <td><?= ucfirst($row['status']); ?></td>
              <td>
                <a href="editpet.php?id=<?= $row['pet_id']; ?>" class="btn-edit">Edit</a>
                <p>‎ </p>
                <a href="deletepet.php?id=<?= $row['pet_id']; ?>" class="btn-delete"
                  onclick="return confirm('Are you sure you want to delete this pet?');">Delete</a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </main>

  <footer class="site-footer">
    <div class="footer-content">
      <p>Reach us at:
        <a href="mailto:ponyososukesheltercenter@gmail.com">ponyososukesheltercenter@gmail.com</a>
      </p>
      <p>&copy; 2025 ALL RIGHTS RESERVED.</p>
    </div>
  </footer>

  <script>
    function openEditForm(petId) {
      // Optionally: Load data via AJAX using petId
      document.getElementById('editModal' + petId).style.display = 'flex';
    }

    function closeEditForm(petId) {
      // Close the modal for the specific pet
      document.getElementById('editModal' + petId).style.display = 'none';
    }

    // Close modal when clicking outside the modal content
    window.addEventListener('click', function (event) {
      const modal = document.querySelector('.modal-overlay');
      if (event.target === modal) {
        modal.style.display = 'none';
      }
    });

  </script>

  <script>
    // Open Modal Function
    function openAddPetModal() {
      document.getElementById('addPetModal').style.display = 'flex';
    }

    // Close Modal Function
    function closeAddPetModal() {
      document.getElementById('addPetModal').style.display = 'none';
    }

    // Close modal when clicking outside the modal content
    window.addEventListener('click', function (event) {
      const modal = document.getElementById('addPetModal');
      if (event.target === modal) {
        closeAddPetModal();
      }
    });

  </script>

  <script>
    function toggleNewCategoryField() {
      const select = document.getElementById("category_select");
      const newCategoryField = document.getElementById("new_category_container");

      if (select.value === "new") {
        newCategoryField.style.display = "block";
        document.getElementById("new_category_input").required = true;
      } else {
        newCategoryField.style.display = "none";
        document.getElementById("new_category_input").required = false;
      }
    }
  </script>

  <script>
    setTimeout(function () {
      const msg = document.querySelector('.success-msg');
      if (msg) {
        msg.style.transition = 'opacity 1s';
        msg.style.opacity = 0;
        setTimeout(() => msg.remove(), 1000); // Remove from DOM
      }
    }, 2000); // 3 seconds
  </script>


</body>

</html>