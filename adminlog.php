<?php
session_start();
include 'db.php'; // Make sure this connects to your database

// Hardcoded valid admin credentials
$valid_admins = [
    "Anna Taduran" => "anna4sosuke",
    "Danica Agawa" => "danica4ponyo",
    "Jasmin Bobares" => "jaszy4you"
];

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = isset($_POST['admin_name']) ? trim($_POST['admin_name']) : '';
    $password = isset($_POST['admin_password']) ? trim($_POST['admin_password']) : '';

    if (!empty($username) && !empty($password)) {

        // ✅ First check against hardcoded admins
        if (isset($valid_admins[$username]) && $valid_admins[$username] === $password) {
            $_SESSION['admin_name'] = $username;
            header("Location: adminpage.php");
            exit();
        }

        // ✅ Then check against database-stored admins
        $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($admin = $result->fetch_assoc()) {
            if (password_verify($password, $admin['password'])) {
                $_SESSION['admin_name'] = $username;
                header("Location: adminpage.php");
                exit();
            }
        }

        // ❌ If neither match
        $error_message = "Invalid username or password.";
    } else {
        $error_message = "Please enter both username and password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/logo.png" type="image/png">
    <title>Developer Login - Pet Patrol</title>
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

        .login-form button:hover {
            background: #faa914;
        }
    </style>

</head>

<body>
    <div class="login-page">
        <div class="login-container">
            <h2 class="login-title">Admin Login</h2>
            <p style="text-align: center">Only Admin can sign in here!</p>
            <p style="text-align: center">ฅ^•ﻌ•^ฅ</p>

            <?php if (!empty($error_message)): ?>
                <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>

            <form class="admin-form" method="POST" action="adminlog.php">
                <input type="text" name="admin_name" placeholder="Admin Name" required>
                <input type="password" name="admin_password" placeholder="Password" required>
                <button type="submit">Login</button>
                <button class="button adoptpet" onclick="location.href='index.html'">Back to Homepage</button>
            </form>
        </div>
    </div>
</body>

</html>