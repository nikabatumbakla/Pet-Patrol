<?php
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'];
    $phone_num = $_POST['phone_num'];
    $street_address = $_POST['street_address'];
    $city = $_POST['city'];
    $state_province = $_POST['state_province'];
    $postal_code = $_POST['postal_code'];
    $country = $_POST['country'];
    $address_type = $_POST['address_type'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the username or email already exists
    $check_stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $check_stmt->bind_param("ss", $username, $email);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        $error_message = "Username or Email already exists!";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Insert the user details into the `users` table
        $stmt = $conn->prepare("INSERT INTO users (fullname, email, phone_num, username, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $fullname, $email, $phone_num, $username, $hashed_password);

        if ($stmt->execute()) {
            $user_id = $stmt->insert_id; // Get the user ID after insertion

            // Insert the address details into the `addresses` table
            $address_stmt = $conn->prepare("INSERT INTO addresses (user_id, street_address, city, state_province, postal_code, country, address_type) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $address_stmt->bind_param("issssss", $user_id, $street_address, $city, $state_province, $postal_code, $country, $address_type);

            if ($address_stmt->execute()) {
                header("Location: login.php");
                exit();
            } else {
                $error_message = "Failed to save address: " . $conn->error;
            }

            $address_stmt->close();
        } else {
            $error_message = "Registration error: " . $conn->error;
        }

        $stmt->close();
    }

    $check_stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/logo.png" type="image/png">
    <title>Register - Pet Patrol</title>
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

        .register-page {
            display: flex;
            min-height: 230vh;
            background: var(--dark-yellow);
            align-items: center;
            justify-content: center;
        }

        .register-container {
            background: linear-gradient(135deg, #fdfcfa, rgb(255, 255, 255));
            padding: 4rem;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 500px;
            position: relative;
        }

        .register-title {
            color: var(--dark-yellow);
            text-align: center;
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
        }

        .register-form {
            max-width: 500px;
            margin: 50px auto;
            padding: 30px 40px;
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            gap: 15px;
            font-family: 'Segoe UI', sans-serif;
        }

        .register-form input,
        .register-form select {
            padding: 12px 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 15px;
            transition: border 0.3s;
        }

        .register-form input:focus,
        .register-form select:focus {
            border-color: #f58220;
            outline: none;
        }

        .register-form button {
            padding: 12px;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .register-form button[type="submit"] {
            background-color: #f58220;
            color: white;
        }

        .register-form .button.adoptpet {
            background-color: #555;
            color: white;
        }

        .register-form button:hover {
            opacity: 0.9;
        }

        .login-link {
            text-align: center;
            margin-top: 1.5rem;
        }

        .login-link a {
            color: var(--dark-yellow);
            font-weight: 600;
            text-decoration: none;
        }

        .login-link a:hover {
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
    <div class="register-page">
        <div class="register-container">
            <h2 class="register-title">Create an Account</h2>
            <p style="text-align: center">ฅ^•ﻌ•^ฅ</p>

            <?php if (isset($error_message)): ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <form class="register-form" method="POST" action="register.php">
                <input type="text" name="fullname" placeholder="Full Name" required>
                <input type="text" name="phone_num" placeholder="Phone Number" required>

                <!-- NEW ADDRESS FIELDS -->
                <input type="text" name="street_address" placeholder="Street Address" required>
                <input type="text" name="city" placeholder="City" required>
                <input type="text" name="state_province" placeholder="State/Province" required>
                <input type="text" name="postal_code" placeholder="Postal Code" required>
                <input type="text" name="country" placeholder="Country" required>
                <select name="address_type" required>
                    <option value="home">Home</option>
                    <option value="mailing">Mailing</option>
                    <option value="work">Work</option>
                    <option value="other">Other</option>
                </select>

                <!-- EXISTING USER FIELDS -->
                <input type="text" name="username" placeholder="Username" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>

                <button type="submit">Register</button>
                <button class="button adoptpet" onclick="location.href='index.html'" type="button">Back to
                    Homepage</button>
            </form>

            <div class="login-link">
                <p>Already have an account? <a href="login.php">Login here</a></p>
            </div>
        </div>
    </div>
</body>

</html>