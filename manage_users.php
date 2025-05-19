<?php
include "db.php";
session_start();

$successMessage = '';
if (isset($_GET['success']) && $_GET['success'] == '1') {
  if (isset($_GET['deleted']) && $_GET['deleted'] === 'admin') {
    $successMessage = "Admin account has been successfully deleted. ";
  } else {
    $successMessage = "Admin account has been successfully added.";
  }
}

// Filters
$typeFilter = $_GET['type'] ?? 'users';
$search = $_GET['search'] ?? '';
$page = max(1, intval($_GET['page'] ?? 1));
$limit = 5;
$offset = ($page - 1) * $limit;

// Build query based on type
if ($typeFilter === 'admins') {
  $countQuery = "SELECT COUNT(*) AS total FROM admins";
  $totalUsers = mysqli_fetch_assoc(mysqli_query($conn, $countQuery))['total'];
  $totalPages = ceil($totalUsers / $limit);

  $query = "SELECT * FROM admins ORDER BY id DESC LIMIT $limit OFFSET $offset";
  $users = mysqli_query($conn, $query);
} else {
  $where = [];
  if (!empty($search)) {
    $searchTerm = mysqli_real_escape_string($conn, $search);
    $where[] = "(fullname LIKE '%$searchTerm%' OR email LIKE '%$searchTerm%')";
  }
  $whereSQL = count($where) ? "WHERE " . implode(" AND ", $where) : "";

  $countQuery = "SELECT COUNT(*) AS total FROM users $whereSQL";
  $totalUsers = mysqli_fetch_assoc(mysqli_query($conn, $countQuery))['total'];
  $totalPages = ceil($totalUsers / $limit);

  $query = "SELECT * FROM users $whereSQL ORDER BY date_registered DESC LIMIT $limit OFFSET $offset";
  $users = mysqli_query($conn, $query);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Manage Users</title>
  <link rel="icon" href="images/logo.png" type="image/png">
  <link rel="stylesheet" href="style.css">
  <style>
    .success-message {
      text-align: center;
      margin: 20px auto;
      padding: 15px 25px;
      background-color: #d1e7dd;
      color: #0f5132;
      font-weight: 600;
      border: 1px solid #badbcc;
      border-radius: 8px;
      max-width: 600px;
      font-family: 'Segoe UI', sans-serif;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    h1 {
      text-align: center;
      margin: 2rem 0 1rem;
      color: var(--dark-yellow);
    }

    .filter-buttons {
      display: flex;
      justify-content: center;
      gap: 10px;
      margin-bottom: 1rem;
    }

    .filter-btn {
      background-color: var(--dark-yellow);
      color: white;
      padding: 10px 16px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 1rem;
    }

    .filter-btn.active {
      background-color: #faa914;
    }

    .user-card {
      background: #fff8e1;
      border: 1px solid var(--primary-color);
      border-left: 5px solid var(--dark-yellow);
      padding: 1rem 1.5rem;
      margin: 1rem auto;
      border-radius: var(--border-radius-m);
      max-width: 800px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .user-card h4 {
      margin: 0 0 0.5rem;
      font-size: 1.3rem;
      color: #333;
    }

    .user-card p {
      margin: 0.25rem 0;
      color: #555;
    }

    .user-card details summary {
      font-weight: 600;
      cursor: pointer;
      color: var(--dark-yellow);
      margin-top: 0.75rem;
    }

    .user-card details ul {
      margin: 0.5rem 0 0;
      padding-left: 1.5rem;
      color: #333;
      font-size: 0.95rem;
    }

    .user-card .badge.admin {
      background: var(--primary-color);
      color: white;
      padding: 4px 10px;
      border-radius: 12px;
      font-size: 0.75rem;
      margin-left: 0.5rem;
    }

    .user-actions {
      margin-top: 1rem;
    }

    .user-actions button {
      background-color: #d9534f;
      color: white;
      border: none;
      padding: 8px 16px;
      border-radius: var(--border-radius-m);
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .user-actions button:hover {
      background-color: #c9302c;
    }

    details {
      margin-top: 10px;
      background: rgba(255, 186, 117, 0.27);
      padding: 8px;
      border-radius: 5px;
    }

    #success-message {
      background-color: #d4edda;
      color: #155724;
      padding: 12px 20px;
      border-radius: 6px;
      margin: 20px auto;
      max-width: 600px;
      border: 1px solid #c3e6cb;
      text-align: center;
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
    </div>
  </header>
  <p>‎ </p>
  <p>‎ </p>

  <?php if (!empty($successMessage)): ?>
    <div id="success-message" style="text-align: center;
  margin: 20px auto;
  padding: 15px 25px;
  background-color: #d1e7dd;
  color: #0f5132;
  font-weight: 600;
  border: 1px solid #badbcc;
  border-radius: 8px;
  max-width: 600px;
  font-family: 'Segoe UI', sans-serif;
  box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
      <?= $successMessage ?>
    </div>
    <script>
      setTimeout(() => {
        const msg = document.getElementById('success-message');
        if (msg) {
          msg.style.opacity = '0';
          setTimeout(() => msg.remove(), 500);
        }
      }, 3000);
    </script>
  <?php endif; ?>

  <h1>Manage User Roles</h1>

  <p>‎ </p>

  <?php
  $typeFilter = $_GET['type'] ?? 'users';
  ?>

  <!-- Filter Buttons -->
  <div class="filter-buttons">
    <a href="manage_users.php?type=users">
      <button class="filter-btn <?= ($typeFilter === 'users') ? 'active' : '' ?>">Users</button>
    </a>
    <a href="manage_users.php?type=admins">
      <button class="filter-btn <?= ($typeFilter === 'admins') ? 'active' : '' ?>">Admins</button>
    </a>
  </div>

  <!-- Add Admin Button -->
  <div style="margin-top: 10px; text-align: center;">
    <a href="add_admin.php">
      <button class="filter-btn">Add New Admin</button>
    </a>
  </div>
  <p>‎ </p>


  <?php while ($user = mysqli_fetch_assoc($users)) { ?>
    <div class="user-card">
      <?php if ($typeFilter === 'admins') { ?>
        <h4><?= htmlspecialchars($user['username']) ?> <span class="badge admin">Admin</span></h4>
      <?php } else { ?>
        <h4><?= htmlspecialchars($user['fullname']) ?></h4>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone_num']) ?></p>
        <p><strong>Joined:</strong> <?= htmlspecialchars($user['date_registered']) ?></p>
      <?php } ?>

      <?php if ($typeFilter === 'users') { ?>
        <details>
          <summary>Activity Log</summary>
          <ul>
            <?php
            $uid = $user['user_id'];
            $logQuery = "
  SELECT aa.*, s.status_name
  FROM adoptionapplications aa
  JOIN application_statuses s ON aa.status_id = s.status_id
  WHERE aa.user_id = $uid
  ORDER BY aa.submission_date DESC
  LIMIT 3
";
            $logs = mysqli_query($conn, $logQuery);
            while ($log = mysqli_fetch_assoc($logs)) {
              echo "<p>Applied for Pet Number {$log['pet_id']} on {$log['submission_date']} (Status: {$log['status_name']})</p>";
            }
            ?>
          </ul>
        </details>
      <?php } ?>

      <div class="user-actions">
        <form method="post" action="user_actions.php">
          <input type="hidden" name="user_id" value="<?= $typeFilter === 'admins' ? $user['id'] : $user['user_id'] ?>">
          <input type="hidden" name="type" value="<?= $typeFilter ?>">
          <button name="action" value="delete" onclick="return confirm('Are you sure?')">Delete Account</button>
        </form>

      </div>
    </div>
  <?php } ?>

  <p>‎ </p>
  <p>‎ </p>

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