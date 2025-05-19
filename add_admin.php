<?php
include "db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username']);
  $password = trim($_POST['password']);

  if (!empty($username) && !empty($password)) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hashedPassword);

    if ($stmt->execute()) {
      header("Location: manage_users.php?success=1");
      exit();
    } else {
      $error_message = "Error adding admin: " . $stmt->error;
    }
  } else {
    $error_message = "Username and password cannot be empty.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Add New Admin - Pet Patrol</title>
  <link rel="icon" href="images/logo.png" type="image/png">
  <link rel="stylesheet" href="style.css">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Miniver&family=Poppins:ital,wght@0,400;0,600;0,700;1,300;1,400;1,500&display=swap');

    :root {
      --primary-color: #e1a800;
      --secondary-color: #ffe094;
      --highlight-color: #f6e10d;
      --dark-yellow: #c88f0a;
      --border-radius-m: 8px;
    }

    body {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Poppins", sans-serif;
    }

    .login-page {
      display: flex;
      min-height: 100vh;
      background: var(--dark-yellow);
      align-items: center;
      justify-content: center;
    }

    .login-container {
      background: linear-gradient(135deg, #fdfcfa, rgb(255, 255, 255));
      padding: 2rem;
      border-radius: 10px;
      box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
      width: 90%;
      max-width: 400px;
      position: relative;
    }

    .login-title {
      color: var(--dark-yellow);
      text-align: center;
      margin-bottom: 1.5rem;
      font-size: 1.8rem;
    }

    .admin-form input {
      width: 100%;
      padding: 12px;
      margin: 8px 0;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 1rem;
    }

    .admin-form button {
      width: 100%;
      padding: 12px;
      background: var(--dark-yellow);
      color: white;
      font-size: 1.1rem;
      border: none;
      border-radius: 5px;
      margin-top: 1rem;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .admin-form button:hover {
      background: #faa914;
    }

    .error-message {
      background-color: #ffd6d6;
      color: #b70000;
      padding: 10px;
      margin-bottom: 1rem;
      border-radius: 5px;
      text-align: center;
    }

    .back-link {
      display: block;
      text-align: center;
      margin-top: 1rem;
      color: var(--primary-color);
      text-decoration: none;
    }

    .back-link:hover {
      text-decoration: underline;
    }
  </style>
</head>

<body>
  <div class="login-page">
    <div class="login-container">
      <h2 class="login-title">Add New Admin</h2>
      <p style="text-align: center;">Securely create a new admin account</p>
      <p style="text-align: center">ฅ^•ﻌ•^ฅ</p>

      <?php if (!empty($error_message)): ?>
        <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
      <?php endif; ?>

      <form class="admin-form" method="POST" action="">
        <input type="text" name="username" placeholder="New Admin Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Add Admin</button>
      </form>

      <a class="back-link" href="manage_users.php">Back to User Management</a>
    </div>
  </div>
</body>

</html>