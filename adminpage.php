<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin_name'])) {
  header('Location: adminlogin.php');
  exit();
}

// Total pets
$totalPets = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM pets"))['count'];

// Available pets
$availablePets = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM pets WHERE pet_status = 'available'"))['count'];

// Pending applications
$pendingAppsResult = mysqli_query($conn, "
  SELECT COUNT(*) AS count
  FROM adoptionapplications aa
  JOIN application_statuses s ON aa.status_id = s.status_id
  WHERE s.status_name = 'pending'
");
$pendingApps = mysqli_fetch_assoc($pendingAppsResult)['count'];

// Approved applications
$approvedAppsResult = mysqli_query($conn, "
  SELECT COUNT(*) AS count
  FROM adoptionapplications aa
  JOIN application_statuses s ON aa.status_id = s.status_id
  WHERE s.status_name = 'approved'
");
$approvedApps = mysqli_fetch_assoc($approvedAppsResult)['count'];

// Incomplete pets
$missingPets = mysqli_fetch_assoc(mysqli_query($conn, "
  SELECT COUNT(*) AS total
  FROM pets
  WHERE image_path IS NULL OR image_path = ''
     OR category_id IS NULL
     OR description IS NULL OR description = ''
"))['total'];

// New users in last 24 hours
$newUsers = mysqli_fetch_assoc(mysqli_query($conn, "
  SELECT COUNT(*) AS total
  FROM users
  WHERE date_registered >= NOW() - INTERVAL 1 DAY
"))['total'];

// Recent Applications (5 most recent)
$query = "
    SELECT 
        aa.application_id, 
        u.fullname, u.email, u.phone_num,
        p.name AS pet_name, p.image_path,
        pc.category_name,
        s.status_name,
        aa.submission_date
    FROM adoptionapplications aa
    JOIN users u ON aa.user_id = u.user_id
    JOIN pets p ON aa.pet_id = p.pet_id
    LEFT JOIN pet_categories pc ON p.category_id = pc.category_id
    JOIN application_statuses s ON aa.status_id = s.status_id
    ORDER BY aa.submission_date DESC
";


$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pawfect Home | Admin Dashboard</title>
  <link rel="icon" href="images/logo.png" type="image/png">
  <link rel="stylesheet" href="style.css" />
  <style>
    body.modal-active main {
      filter: blur(5px);
      pointer-events: none;
    }

    .modal {
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      background: rgba(0, 0, 0, 0.6);
      backdrop-filter: blur(5px);
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 9999;
      overflow-y: auto;
      padding: 2rem 1rem;
    }

    .modal.hidden {
      display: none;
    }

    .modal-content {
      background: #fff;
      padding: 2rem;
      border-radius: 1.5rem;
      max-width: 600px;
      width: 100%;
      max-height: 90vh;
      /* KEY: allows scroll */
      overflow-y: auto;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
      position: relative;
      animation: slideIn 0.4s ease forwards;
      font-family: 'Segoe UI', sans-serif;
    }

    @keyframes slideIn {
      from {
        transform: translateY(40px);
        opacity: 0;
      }

      to {
        transform: translateY(0);
        opacity: 1;
      }
    }

    .close-btn {
      position: absolute;
      top: 1rem;
      right: 1rem;
      background: none;
      font-size: 1.5rem;
      color: #555;
      border: none;
      cursor: pointer;
      transition: transform 0.2s ease;
    }

    .close-btn:hover {
      transform: scale(1.2);
      color: #e74c3c;
    }

    #modalDetails p {
      margin: 0.7rem 0;
      font-size: 1rem;
      line-height: 1.5;
    }

    #modalDetails strong {
      color: #2c3e50;
    }

    #modalDetails span {
      color: #34495e;
      font-weight: 500;
    }

    #modalPetImage {
      width: 100%;
      max-height: 280px;
      object-fit: cover;
      margin: 1rem 0;
      border-radius: 1rem;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
      transition: transform 0.3s ease;
    }

    #modalPetImage:hover {
      transform: scale(1.02);
    }

    h2 {
      text-align: center;
      color: #2c3e50;
      margin-bottom: 1.5rem;
      font-size: 1.8rem;
      border-bottom: 2px solid #eee;
      padding-bottom: 0.5rem;
    }

    .modal {
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      background: rgba(0, 0, 0, 0.6);
      backdrop-filter: blur(3px);
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 9999;
    }

    .modal.hidden {
      display: none;
    }

    .modal-content {
      background: white;
      padding: 2rem;
      border-radius: 1rem;
      max-width: 800px;
      width: 90%;
      max-height: 80vh;
      overflow-y: auto;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
      position: relative;
    }

    .dashboard-alerts {
      padding: 2rem;
      font-family: var(--font-family);
    }

    .dashboard-alerts h2 {
      font-size: 1.8rem;
      color: var(--primary-color);
      margin-bottom: 1.5rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .alert-card {
      border-radius: var(--border-radius-m);
      padding: 1rem;
      margin-bottom: 1rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      position: relative;
    }

    .alert-card p {
      margin: 0;
      color: var(--dark-yellow);
      font-size: 1.1rem;
    }

    .alert-card strong {
      color: var(--primary-color);
    }

    .dismiss-btn {
      background: none;
      border: none;
      font-size: 1.5rem;
      color: var(--dark-yellow);
      cursor: pointer;
      transition: color 0.3s;
    }

    .dismiss-btn:hover {
      color: var(--primary-color);
    }

    .alert-card:hover {
      background-color: #fff9e6;
      ;
      transform: translateY(-3px);
    }

    .recent-applications {
      padding: 2rem;
      font-family: var(--font-family);
    }

    .recent-applications h2 {
      font-size: 1.8rem;
      color: var(--primary-color);
      margin-bottom: 1.5rem;
    }

    .application-card {
      background-color: var(--secondary-color);
      border-radius: var(--border-radius-m);
      padding: 1.2rem;
      margin-bottom: 1rem;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      position: relative;
      transition: background-color 0.3s, transform 0.3s;
    }

    .application-card:hover {
      background-color: var(--highlight-color);
      transform: translateY(-4px);
    }

    .application-card p {
      margin: 0.4rem 0;
      color: var(--dark-yellow);
      font-size: 1rem;
    }

    .application-card strong {
      color: var(--primary-color);
    }

    .application-card .btn-small {
      background-color: var(--primary-color);
      color: white;
      border: none;
      border-radius: var(--border-radius-m);
      padding: 0.4rem 0.8rem;
      font-size: 0.9rem;
      cursor: pointer;
      margin-top: 0.6rem;
      transition: background-color 0.3s;
    }

    .application-card .btn-small:hover {
      background-color: var(--dark-yellow);
    }

    .btn-export {
      background-color: rgb(255, 157, 0);
      color: white;
      border: none;
      padding: 10px 16px;
      font-size: 16px;
      cursor: pointer;
      border-radius: 6px;
    }

    .btn-export:hover {
      background-color: rgb(213, 158, 5);
    }
  </style>

</head>

<body>

  <!-- STAT REPORT MODAL -->
  <div id="statReportModal" class="modal hidden">
    <div class="modal-content" style="max-height: 80vh; overflow-y: auto;">
      <button class="close-btn" onclick="closeStatModal()">✖</button>
      <h2 id="statTitle">Report</h2>
      <div id="statReportDetails">
        <!-- Dynamic content will be inserted here -->
      </div>
    </div>
  </div>


  <header>
    <div class="navbar">
      <div class="logo">
        <img src="images/logo.png" alt="Pet Patrol Logo" class="logo-img">
        <div class="user-greeting">
          <span>👋 Welcome, <?php echo htmlspecialchars($_SESSION['admin_name']); ?>!</span>
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

  <main class="dashboard-container">
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['admin_name']); ?>!</h1>
    <p class="dashboard-subtext">Here’s an overview of your adoption center activities.</p>

    <form action="export_report.php" method="post" target="_blank" style="text-align: right; margin-bottom: 1rem;">
      <button type="submit" class="btn-export">📄 Export as PDF</button>
    </form>


    <!-- Quick Stats -->
    <section style="margin-top: 2.5rem;" class="dashboard-stats">
      <div class="stat-card" onclick="openStatModal('total')">
        <h2><?php echo $totalPets; ?></h2><br>
        <p style="font-weight: bold; margin-bottom: 0.5rem; color: var(--primary-color);">TOTAL PETS</p>
        <p
          style="font-size: 0.9rem; cursor: pointer; background-color: var(--dark-yellow); color: #fff; padding: 0.3rem; border-radius: 0.5rem;">
          Click to View..</p>
      </div>
      <div class="stat-card" onclick="openStatModal('available')">
        <h2><?php echo $availablePets; ?></h2><br>
        <p style="font-weight: bold; margin-bottom: 0.5rem; color: var(--primary-color);">AVAILABLE PETS</p>
        <p
          style="font-size: 0.9rem; cursor: pointer; background-color: var(--dark-yellow); color: #fff; padding: 0.3rem; border-radius: 0.5rem;">
          Click to View..</p>
      </div>
      <div class="stat-card" onclick="openStatModal('pending')">
        <h2><?php echo $pendingApps; ?></h2><br>
        <p style="font-weight: bold; margin-bottom: 0.5rem; color: var(--primary-color);">PENDING APPLICATIONS</p>
        <p
          style="font-size: 0.9rem; cursor: pointer; background-color: var(--dark-yellow); color: #fff; padding: 0.3rem; border-radius: 0.5rem;">
          Click to View..</p>
      </div>
      <div class="stat-card" onclick="openStatModal('approved')">
        <h2><?php echo $approvedApps; ?></h2><br>
        <p style="font-weight: bold; margin-bottom: 0.5rem; color: var(--primary-color);">APPROVED APPLICATIONS</p>
        <p
          style="font-size: 0.9rem; cursor: pointer; background-color: var(--dark-yellow); color: #fff; padding: 0.3rem; border-radius: 0.5rem;">
          Click to View..</p>
      </div>
    </section>



    <section class="recent-applications">
      <h2>Recent Adoption Applications</h2>
      <?php if (mysqli_num_rows($result) > 0): ?>

        <table class="dashboard-table">
          <thead>
            <tr>
              <th>Applicant</th>
              <th>Pet Name</th>
              <th>Category</th>
              <th>Status</th>
              <th>Date Submitted</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>

              <tr>
                <td><?= htmlspecialchars($row['fullname']) ?></td>
                <td><?= htmlspecialchars($row['pet_name']) ?></td>
                <td><?= htmlspecialchars($row['category_name']) ?></td>
                <td><?= ucfirst(htmlspecialchars($row['status_name'])) ?></td>
                <td><?= date('M d, Y — h:i A', strtotime($row['submission_date'])) ?></td>
                <td>
                  <button class="btn-small" onclick="openModal(
  <?= $row['application_id'] ?>,
  '<?= htmlspecialchars($row['fullname'], ENT_QUOTES) ?>',
  '<?= htmlspecialchars($row['email'], ENT_QUOTES) ?>',
  '<?= htmlspecialchars($row['phone_num'], ENT_QUOTES) ?>',
  '<?= htmlspecialchars($row['pet_name'], ENT_QUOTES) ?>',
  '<?= htmlspecialchars($row['image_path'], ENT_QUOTES) ?>',
  '<?= htmlspecialchars($row['category_name'], ENT_QUOTES) ?>',
  '<?= htmlspecialchars($row['status_name'], ENT_QUOTES) ?>',
  '<?= date('M d, Y — h:i A', strtotime($row['submission_date'])) ?>'
)">Review</button>


                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      <?php else: ?>
        <p>No recent applications.</p>
      <?php endif; ?>
    </section>


    <section class="dashboard-alerts">
      <h2>🔔 Pending Tasks</h2>

      <?php if ($pendingApps > 0): ?>
        <div class="alert-card">
          <button class="dismiss-btn" onclick="this.parentElement.remove()">✖</button>
          <p><strong><?= $pendingApps ?></strong> application(s) awaiting review</p>
        </div>
      <?php endif; ?>

      <?php if ($availablePets == 0): ?>
        <div class="alert-card">
          <button class="dismiss-btn" onclick="this.parentElement.remove()">✖</button>
          <p>No pets currently available for adoption. Consider adding more.</p>
        </div>
      <?php endif; ?>
    </section>

    <section class="dashboard-alerts">
      <h2>🔔 Notifications & Alerts</h2>

      <div class="alert-card">
        <button class="dismiss-btn" onclick="this.parentElement.remove()">✖</button>
        <p><strong><?= $pendingApps ?></strong> application(s) awaiting review</p>
      </div>

      <div class="alert-card">
        <button class="dismiss-btn" onclick="this.parentElement.remove()">✖</button>
        <p><strong><?= $missingPets ?></strong> pet(s) have missing details</p>
      </div>

      <div class="alert-card">
        <button class="dismiss-btn" onclick="this.parentElement.remove()">✖</button>
        <p><strong><?= $newUsers ?></strong> new user(s) registered in the last 24 hours</p>
      </div>
    </section>
  </main>

  <!-- MODAL -->
  <div id="appModal" class="modal hidden">
    <div class="modal-content">
      <button class="close-btn" onclick="closeModal()">✖</button>
      <h2>Application Summary</h2>
      <div id="modalDetails">
        <p><strong>Applicant:</strong> <span id="modalUser"></span></p>
        <p><strong>Email:</strong> <span id="modalEmail"></span></p>
        <p><strong>Phone:</strong> <span id="modalPhone"></span></p>

        <p><strong>Pet:</strong> <span id="modalPet"></span></p>
        <img id="modalPetImage" src="" alt="Pet Image"
          style="width: 100%; max-height: 300px; object-fit: cover; margin-bottom: 1rem; border-radius: 0.5rem;" />

        <p><strong>Category:</strong> <span id="modalCategory"></span></p>
        <p><strong>Status:</strong> <span id="modalStatus"></span></p>
        <p><strong>Submitted:</strong> <span id="modalDate"></span></p>
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
    function openModal(appId, user, email, phone, pet, imagePath, category, status, date) {
      document.getElementById('modalUser').textContent = user;
      document.getElementById('modalEmail').textContent = email;
      document.getElementById('modalPhone').textContent = phone;
      document.getElementById('modalPet').textContent = pet;
      document.getElementById('modalCategory').textContent = category;
      document.getElementById('modalStatus').textContent = status;
      document.getElementById('modalDate').textContent = date;

      // Show image
      document.getElementById('modalPetImage').src = imagePath;

      document.getElementById('appModal').classList.remove('hidden');
      document.body.classList.add('modal-active');
    }



    function closeModal() {
      document.getElementById('appModal').classList.add('hidden');
      document.body.classList.remove('modal-active');
    }

    window.onclick = function (e) {
      if (e.target.id === 'appModal') closeModal();
    }
  </script>

  <script>
    function openStatModal(type) {
      const title = document.getElementById('statTitle');
      const content = document.getElementById('statReportDetails');

      // Set modal title based on the type
      switch (type) {
        case 'total':
          title.textContent = 'Total Pets Report';
          break;
        case 'available':
          title.textContent = 'Available Pets Report';
          break;
        case 'pending':
          title.textContent = 'Pending Applications Report';
          break;
        case 'approved':
          title.textContent = 'Approved Applications Report';
          break;
      }

      // Fetch data from the server
      fetch('get_stat_report.php?type=' + type)
        .then(response => response.text())
        .then(data => {
          content.innerHTML = data;
          document.getElementById('statReportModal').classList.remove('hidden');
          document.body.classList.add('modal-active');
        })
        .catch(error => {
          content.innerHTML = '<p>Error loading data.</p>';
          console.error('Error fetching report:', error);
        });
    }

    function closeStatModal() {
      document.getElementById('statReportModal').classList.add('hidden');
      document.body.classList.remove('modal-active');
    }

    // Close modal when clicking outside the content
    window.onclick = function (e) {
      if (e.target.id === 'statReportModal') closeStatModal();
    };
  </script>


</body>

</html>