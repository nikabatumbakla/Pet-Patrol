<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin_name'])) {
  header('Location: adminlogin.php');
  exit();
}

// Optional filter
$filterStatus = isset($_GET['status']) ? mysqli_real_escape_string($conn, $_GET['status']) : 'all';

// Build query based on status filter
$whereClause = ($filterStatus !== 'all') ? "WHERE s.status_name = '$filterStatus'" : "";

// Updated SQL to get category name
$sql = "
    SELECT 
        aa.application_id,
        aa.user_id,
        aa.pet_id,
        aa.submission_date,
        aa.location,
        s.status_name,
        u.username,
        u.email,
        p.name AS pet_name,
        c.category_name
    FROM adoptionapplications aa
    JOIN users u ON aa.user_id = u.user_id
    JOIN pets p ON aa.pet_id = p.pet_id
    JOIN pet_categories c ON p.category_id = c.category_id
    JOIN application_statuses s ON aa.status_id = s.status_id
    $whereClause
    ORDER BY aa.submission_date DESC
";

$result = mysqli_query($conn, $sql);

if (!$result) {
  die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Manage Applications</title>
  <link rel="icon" href="images/logo.png" type="image/png">
  <link rel="stylesheet" href="style.css" />
  <style>
    .admin-container {
      max-width: 1200px;
      margin: 40px auto;
      padding: 30px;
      border-radius: 12px;
    }

    .admin-container h1 {
      font-size: 2rem;
      color: #333;
      text-align: center;
      margin-bottom: 30px;
    }

    .filter-buttons {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 10px;
      margin-bottom: 20px;
    }

    .filter-btn {
      padding: 8px 16px;
      border: none;
      border-radius: 6px;
      background-color: #f1f1f1;
      cursor: pointer;
      font-weight: 500;
      transition: background 0.3s ease;
    }

    .filter-btn:hover {
      background-color: #e4e4e4;
    }

    .filter-btn.active {
      background-color: #ffa48b;
      color: #fff;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background-color: #fff;
      font-size: 0.95rem;
      overflow-x: auto;
    }

    thead {
      background-color: #ffe0db;
    }

    thead th {
      padding: 12px;
      font-weight: bold;
      color: #333;
      text-align: center;
      border-bottom: 2px solid #ddd;
    }

    tbody td {
      padding: 12px;
      text-align: center;
      border-bottom: 1px solid #eee;
    }

    tbody tr:hover {
      background-color: #fff2ef;
    }

    .btn-action {
      padding: 6px 10px;
      margin: 2px;
      border: none;
      border-radius: 6px;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.3s ease;
      color: white;
    }

    .btn-action.approve {
      background-color: #66bb6a;
    }

    .btn-action.approve:hover {
      background-color: #57a95c;
    }

    .btn-action.reject {
      background-color: #ef5350;
    }

    .btn-action.reject:hover {
      background-color: #d84340;
    }

    .btn-action.more-info {
      background-color: #ffa726;
    }

    .btn-action.more-info:hover {
      background-color: #fb8c00;
    }

    .btn-action.download {
      background-color:rgb(235, 151, 25);
      color: white;
      padding: 10px 18px;
      border: none;
      border-radius: 6px;
      font-weight: bold;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .btn-action.download:hover {
      background-color:rgb(211, 99, 8);
    }
  </style>

  <?php if (isset($_SESSION['success_message'])): ?>
    <script>
      alert("<?php echo $_SESSION['success_message']; ?>");
    </script>
    <?php unset($_SESSION['success_message']); ?>
  <?php endif; ?>

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

  <main class="admin-container">
    <h1>Manage Adoption Applications</h1>

    <div class="filter-buttons">
      <?php
      $statuses = ['all', 'Pending', 'Approved', 'Rejected'];
      foreach ($statuses as $status) {
        $activeClass = ($filterStatus === $status) ? 'active' : '';
        echo "<a href='manage_applications.php?status=$status'><button class='filter-btn $activeClass'>$status</button></a>";
      }
      ?>
    </div>

    <div style="text-align: right; margin-bottom: 15px;">
      <form method="POST" action="generate_applications_pdf.php" target="_blank">
        <input type="hidden" name="status" value="<?= $filterStatus ?>">
        <button type="submit" class="btn-action download">📄 Download PDF</button>
      </form>
    </div>

    <table>
      <thead>
        <tr>
          <th>Pet</th>
          <th>Category</th>
          <th>Applicant</th>
          <th>Email</th>
          <th>Location</th>
          <th>Status</th>
          <th>Submitted</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
          <tr>
            <td><?= htmlspecialchars($row['pet_name']) ?></td>
            <td><?= htmlspecialchars($row['category_name']) ?></td>
            <td><?= htmlspecialchars($row['username']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['location']) ?></td>
            <td><?= htmlspecialchars($row['status_name']) ?></td>
            <td><?= $row['submission_date'] ?></td>
            <td>
              <?php if (trim($row['status_name']) === 'Pending'): ?>
                <form method="post" action="update_application_status.php" style="display:inline-block;">
                  <input type="hidden" name="application_id" value="<?= $row['application_id'] ?>">
                  <button class="btn-action approve" name="action" value="Approved">Approve</button>
                  <button class="btn-action reject" name="action" value="Rejected">Reject</button>
                </form>
              <?php endif; ?>
              <a href="info.php?application_id=<?= $row['application_id'] ?>">
                <button class="btn-action more-info" type="button">More Info</button>
              </a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </main>

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