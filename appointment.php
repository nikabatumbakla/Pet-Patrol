<?php
include 'db.php';
session_start();

if (!isset($_SESSION['admin_name'])) {
    header('Location: adminlogin.php');
    exit();
}

if (!isset($_GET['application_id'])) {
    die("No application selected.");
}

$application_id = intval($_GET['application_id']);

// ✅ FETCH PET NAME HERE
$user_query = $conn->prepare("
    SELECT users.fullname 
    FROM adoptionapplications 
    JOIN users ON adoptionapplications.user_id = users.user_id 
    WHERE adoptionapplications.application_id = ?
");
$user_query->bind_param("i", $application_id);
$user_query->execute();
$user_result = $user_query->get_result();
$application = $user_result->fetch_assoc();

// ✅ INSERT APPOINTMENT IF FORM IS SUBMITTED
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $appointment_date = $_POST['appointment_date'];

    $insert = "INSERT INTO appointments (application_id, appointment_date) VALUES ($application_id, '$appointment_date')";
    if (mysqli_query($conn, $insert)) {
        $_SESSION['success_message'] = "✅ Appointment Saved Successfully!";
        header('Location: manage_applications.php');
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <title>Set Appointment</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/logo.png" type="image/png">
    <style>
        body {
            background: #fff7f0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 700px;
            background: #ffffff;
            margin: 50px auto;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #ff7e5f;
            margin-bottom: 30px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-weight: 600;
            color: #444;
        }

        input[type="datetime-local"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 1rem;
        }

        button {
            padding: 12px;
            background-color: #ff7e5f;
            border: none;
            color: white;
            font-size: 1rem;
            font-weight: bold;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #e5674b;
        }

        h3 {
            margin-top: 40px;
            color: #444;
        }

        p {
            color: #555;
            font-size: 1rem;
            line-height: 1.6;
        }

        strong {
            color: #333;
        }

        .download-link {
            display: inline-block;
            margin-top: 10px;
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .download-link:hover {
            background-color: #2c80b4;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>📅 Set Appointment for <?= htmlspecialchars($application['fullname']) ?></h2>

        <form method="post">
            <label for="appointment_date">Select Appointment Date & Time:</label>
            <input type="datetime-local" name="appointment_date" id="appointment_date" required>
            <button type="submit">Save Appointment</button>
        </form>
        <p>‎ </p>
        <h3>Subject: Your Pet Adoption Application Has Been Approved!</h3>
        <p>‎ </p>
        <p>Dear, <strong> <?= htmlspecialchars($application['fullname']) ?></strong></p>
        <p>‎ </p>
        <p>
            We are pleased to inform you that your application to adopt a pet has been <strong>approved</strong>!
        </p>

        <p>
            At this stage, we invite you to visit our shelter to meet with us personally. Please ensure you come at the
            scheduled time as we aim to provide a thorough and efficient process.
        </p>

        <p>
            Additionally, we kindly request that you download the document below, which contains another application
            form necessary for your adoption process at our shelter.
        </p>

        <p>
            We look forward to meeting you soon and assisting you in welcoming your new companion!
        </p>
        <p>‎ </p>
        <p>
            Warm regards,<br>
            <strong>PonyoSosuke Adoption Center</strong><br>
            Email: <a href="mailto:ponyososuke@gmail.com">ponyososuke@gmail.com</a><br>
            Phone: 09924314989
        </p>

    </div>

</body>

</html>