<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
  die("Unauthorized access.");
}

$user_id = $_SESSION['user_id'];
$statusFilter = $_GET['status'] ?? 'all';

// SQL query with JOINs and optional filter
$sql = "
SELECT 
  a.application_id,
  p.name AS pet_name,
  p.species,
  a.living_conditions,
  a.pet_experience,
  s.status_name AS status,
  a.submission_date AS created_at,
  ap.appointment_date,
  ap.status AS appointment_status
FROM adoptionapplications a
JOIN pets p ON a.pet_id = p.pet_id
JOIN application_statuses s ON a.status_id = s.status_id
LEFT JOIN appointments ap ON a.application_id = ap.application_id
WHERE a.user_id = ?
";

if ($statusFilter !== 'all') {
  $sql .= " AND s.status_name = ?";
}

$sql .= " ORDER BY a.submission_date DESC";

$stmt = mysqli_prepare($conn, $sql);

if ($statusFilter !== 'all') {
  mysqli_stmt_bind_param($stmt, "is", $user_id, $statusFilter);
} else {
  mysqli_stmt_bind_param($stmt, "i", $user_id);
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pawfect Home | Applications</title>
  <link rel="icon" href="images/logo.png" type="image/png">
  <link rel="stylesheet" href="style.css" />
</head>

<body>
  <!-- Inline Styles -->
  <style>
    /* Basic Styles */
    h2 {
      text-align: center;
      margin-top: 40px;
      font-size: 2rem;
    }

    .filter-container {
      display: flex;
      justify-content: center;
      margin: 30px 0;
    }

    .filter-form {
      background-color: rgba(255, 141, 112, 0.3);
      padding: 20px 65px;
      border-radius: 12px;
      box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .filter-label {
      font-weight: 600;
      font-size: 1rem;
      color: #444;
    }

    .filter-select {
      padding: 10px 15px;
      font-size: 16px;
      border-radius: 8px;
      border: 1px solid #ccc;
      background-color: #fdfdfd;
      color: #333;
      transition: border-color 0.3s ease, box-shadow 0.3s ease;
      appearance: none;
      -webkit-appearance: none;
      -moz-appearance: none;
      cursor: pointer;
    }

    .filter-select:hover {
      border-color: #ffa500;
      box-shadow: 0 0 5px rgba(255, 165, 0, 0.3);
    }

    .filter-select:focus {
      border-color: #ff8800;
      box-shadow: 0 0 6px rgba(255, 136, 0, 0.4);
      outline: none;
    }

    table {
      width: 95%;
      max-width: 1100px;
      margin: 20px auto 40px;
      border-collapse: collapse;
      background: #fff;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
    }

    thead {
      background-color: #ff7e5f;
      color: white;
    }

    th,
    td {
      padding: 15px 20px;
      text-align: left;
      vertical-align: top;
    }

    tbody tr:nth-child(even) {
      background-color: #fafafa;
    }

    .status-badge {
      display: inline-block;
      padding: 6px 12px;
      font-size: 0.9rem;
      font-weight: bold;
      border-radius: 20px;
      text-transform: capitalize;
    }

    .status-pending {
      background-color: rgb(255, 224, 123);
      color: #856404;
    }

    .status-approved {
      background-color: #d4edda;
      color: #155724;
    }

    .status-rejected {
      background-color: #f8d7da;
      color: #721c24;
    }

    p {
      text-align: center;
      font-size: 1.1rem;
      margin-top: 30px;
    }

    .badge {
      padding: 4px 10px;
      border-radius: 12px;
      font-size: 0.8rem;
      font-weight: bold;
      display: inline-block;
      color: white;
    }

    .badge.scheduled {
      background-color: #3498db;
    }

    .badge.completed {
      background-color: #2ecc71;
    }

    .badge.canceled {
      background-color: #e74c3c;
    }

    .badge.no-appointment {
      background-color: #888;
    }

    /* Modal Styles */
    .modal {
      display: none;
      /* Hidden by default */
      position: fixed;
      z-index: 1;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0, 0, 0, 0.4);
      backdrop-filter: blur(10px);
      /* Blur the background */
    }

    .modal-content {
      background-color: #fff;
      margin: 10% auto;
      padding: 20px;
      border-radius: 12px;
      width: 80%;
      max-width: 900px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
      font-size: 1.5rem;
      font-weight: bold;
    }

    .modal-body {
      font-size: 1rem;
      margin-top: 10px;
    }

    .close {
      color: #aaa;
      font-size: 28px;
      font-weight: bold;
      position: absolute;
      top: 5px;
      right: 15px;
    }

    .close:hover,
    .close:focus {
      color: black;
      text-decoration: none;
      cursor: pointer;
    }

    .application-row {
      cursor: pointer;
      transition: background-color 0.2s ease;
    }

    .application-row:hover {
      background-color: rgb(255, 244, 241);
      /* Light orange shade to match your theme */
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

    .badge.no-appointment {
      display: inline-block;
      background-color: #ffcccc;
      /* soft red/pink */
      color: #a70000;
      /* dark red text */
      padding: 6px 12px;
      font-size: 14px;
      font-weight: 600;
      border-radius: 20px;
      text-align: center;
      white-space: nowrap;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .badge.no-appointment:hover {
      background-color: #ff9999;
      cursor: default;
    }

    .status-badge {
      display: inline-block;
      min-width: 100px;
      text-align: center;
      padding: 6px 12px;
      font-size: 14px;
      font-weight: 600;
      border-radius: 20px;
      color: #fff;
      text-transform: capitalize;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
      line-height: 1.4;
    }

    /* Status-specific colors */
    .status-badge.status-pending {
      background-color: #f0ad4e;
    }

    .status-badge.status-approved {
      background-color: #5cb85c;
    }

    .status-badge.status-rejected {
      background-color: #d9534f;
    }

    .badge {
      display: inline-block;
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 0.875rem;
      font-weight: 500;
      text-align: center;
      white-space: nowrap;
      text-decoration: none;
      transition: background-color 0.3s ease, color 0.3s ease;
    }

    .badge-scheduled {
      background-color: #28a745;
      /* green */
      color: white;
    }

    .badge-scheduled:hover {
      background-color: #218838;
      text-decoration: none;
    }

    .badge-unscheduled {
      background-color: #dc3545;
      /* red */
      color: white;
      cursor: default;
    }
  </style>
  </head>

  <body>

    <header id="mainHeader">
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

    <!-- Application Content -->
    <h2>📄 My Adoption Applications</h2>

    <!-- Filter by Status -->
    <div class="filter-container">
      <form method="GET" action="applications.php" class="filter-form">
        <label for="status" class="filter-label">🔍 Filter by Status:</label>
        <select name="status" id="status" class="filter-select" onchange="this.form.submit()">
          <option value="all" <?= ($statusFilter === 'all') ? 'selected' : '' ?>>All</option>
          <option value="pending" <?= ($statusFilter === 'pending') ? 'selected' : '' ?>>Pending</option>
          <option value="approved" <?= ($statusFilter === 'approved') ? 'selected' : '' ?>>Approved</option>
          <option value="rejected" <?= ($statusFilter === 'rejected') ? 'selected' : '' ?>>Rejected</option>
        </select>
      </form>
    </div>

    <!-- Application Table -->
    <?php if (mysqli_num_rows($result) > 0): ?>
      <table>
        <thead>
          <tr>
            <th>Pet Name</th>
            <th>Species</th>
            <th>Living Conditions</th>
            <th>Experience</th>
            <th>Status</th>
            <th>Submitted At</th>
            <th>Appointment</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($app = mysqli_fetch_assoc($result)): ?>
            <tr class="application-row" data-application-id="<?= $app['application_id'] ?>">
              <td><?= htmlspecialchars($app['pet_name']) ?></td>
              <td><?= htmlspecialchars($app['species']) ?></td>
              <td><?= nl2br(htmlspecialchars($app['living_conditions'])) ?></td>
              <td><?= nl2br(htmlspecialchars($app['pet_experience'])) ?></td>
              <td>
                <span class="status-badge status-<?= strtolower($app['status']) ?>">
                  <?= htmlspecialchars(ucfirst($app['status'])) ?>
                </span>
              </td>
              <td><?= date("M d, Y H:i", strtotime($app['created_at'])) ?></td>

              <td>
                <?php if (!empty($app['appointment_date'])): ?>
                  <a href="view_schedule.php?application_id=<?= $app['application_id'] ?>" class="badge badge-scheduled">View
                    Schedule</a>
                <?php else: ?>
                  <span class="badge badge-unscheduled">Not Scheduled</span>
                <?php endif; ?>
              </td>


            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p class="section-subheading">😿 No applications found in this category.</p>
    <?php endif; ?>

    <!-- Modal for Viewing Application Details -->
    <div id="applicationModal" class="modal">
      <div class="modal-content">
        <span class="close">&times;</span>
        <div class="modal-header">
          <h2>Application Details</h2>
        </div>
        <div class="modal-body" id="applicationDetails">
          <!-- Application details will be dynamically loaded here -->
        </div>
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
      // Get modal
      var modal = document.getElementById('applicationModal');
      var closeModal = document.getElementsByClassName('close')[0];

      // When the user clicks on a row, open the modal
      document.querySelectorAll('.application-row').forEach(function (row) {
        row.addEventListener('click', function () {
          var appId = this.getAttribute('data-application-id');

          // Fetch application details using AJAX
          var xhr = new XMLHttpRequest();
          xhr.open('GET', 'fetch_application_details.php?application_id=' + appId, true);
          xhr.onload = function () {
            if (xhr.status === 200) {
              document.getElementById('applicationDetails').innerHTML = xhr.responseText;
              modal.style.display = "block";
            }
          };
          xhr.send();
        });
      });

      // When the user clicks on the close button, close the modal
      closeModal.onclick = function () {
        modal.style.display = "none";
      };

      // When the user clicks anywhere outside the modal, close it
      window.onclick = function (event) {
        if (event.target === modal) {
          modal.style.display = "none";
        }
      };
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

  </body>

</html>