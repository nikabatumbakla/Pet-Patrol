<?php
session_start();
include('db.php');

$error_message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prevent SQL Injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if ($user) {
        // Check if password is hashed or stored in plain text
        if (password_verify($password, $user['password']) || $password == $user['password']) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            header("Location: home.php");
            exit();
        }
    }

    $error_message = "Invalid username or password.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/logo.png" type="image/png">
    <title>Login - Pet Patrol</title>
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

        /* Login Page Styles */
        .login-page {
            display: flex;
            min-height: 120vh;
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

        .login-form {
            max-width: 400px;
            margin: 30px auto;
            padding: 30px 35px;
            background-color: #ffffff;
            border-radius: 15px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            gap: 18px;
            font-family: 'Segoe UI', sans-serif;
        }

        .login-form input {
            padding: 12px 15px;
            font-size: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            transition: border 0.3s;
        }

        .login-form input:focus {
            border-color: #f58220;
            outline: none;
        }

        .login-form button {
            padding: 12px;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .login-form button[type="submit"] {
            background-color: #f58220;
            color: white;
        }

        .login-form .back-btn {
            background-color: #444;
            color: white;
        }

        .login-form button:hover {
            opacity: 0.9;
        }


        .register-link {
            text-align: center;
            margin-top: 1.5rem;
        }

        .register-link a {
            color: var(--dark-yellow);
            font-weight: 600;
            text-decoration: none;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        .error-message {
            color: #dc3545;
            background: #ffe6e6;
            padding: 0.8rem;
            border-radius: 5px;
            margin-bottom: 1rem;
            text-align: center;
            font-size: 0.9rem;
        }
    </style>
</head>

<body>
    <div class="login-page">
        <div class="login-container">
            <h2 class="login-title">Welcome!</h2>
            <p style="text-align: center">ฅ^•ﻌ•^ฅ</p>
            <?php if ($error_message): ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <form class="login-form" method="POST" action="login.php">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Login</button>
                <button class="back-btn" onclick="window.history.back()">Go Back</button>
            </form>

            <div class="register-link">
                <p>Don't have an account? <a href="register.php">Register here</a></p>
            </div>
        </div>
    </div>
</body>

</html>