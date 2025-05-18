<?php
include "db.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access.");
}

if (!isset($_GET['application_id'])) {
    die("No application selected.");
}

$application_id = intval($_GET['application_id']);

// Fetch appointment
$sql = "SELECT ap.appointment_date, ap.status
        FROM appointments ap
        WHERE ap.application_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $application_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Fetch user's fullname
$user_id = $_SESSION['user_id'];

$sql_user = "SELECT fullname FROM users WHERE user_id = ?";
$stmt_user = mysqli_prepare($conn, $sql_user);
mysqli_stmt_bind_param($stmt_user, "i", $user_id);
mysqli_stmt_execute($stmt_user);
$result_user = mysqli_stmt_get_result($stmt_user);

$application = mysqli_fetch_assoc($result_user); // this will contain ['fullname'] now


if ($row = mysqli_fetch_assoc($result)):
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <title>View Appointment Schedule</title>
        <link rel="icon" href="images/logo.png" type="image/png">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            body {
                background-color: #fff7f0;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                padding: 30px;
                margin: 0;
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
                color: #ff7e5f;
                text-align: center;
                margin-bottom: 25px;
            }

            h3 {
                margin-top: 30px;
                color: #444;
            }

            p {
                color: #555;
                font-size: 1rem;
                line-height: 1.6;
                margin-bottom: 10px;
            }

            strong {
                color: #333;
            }

            a.download-link {
                display: inline-block;
                margin-top: 10px;
                background-color: #ff7e5f;
                color: white;
                padding: 10px 20px;
                border-radius: 8px;
                text-decoration: none;
                font-weight: bold;
                transition: background-color 0.3s ease;
            }

            a.download-link:hover {
                background-color: #e5674b;
            }

            .back-link {
                display: inline-block;
                margin-top: 30px;
                text-decoration: none;
                font-weight: bold;
                color: #444;
            }

            .back-link:hover {
                text-decoration: underline;
            }
        </style>
    </head>

    <body>

        <div class="container">
            <h2>📅 Appointment Details</h2>
            <p>‎ </p>

            <p><strong>Scheduled Date & Time:</strong> <?= date("M d, Y H:i", strtotime($row['appointment_date'])) ?></p>
            <p><strong>Appointment Status:</strong> <?= ucfirst($row['status']) ?></p>

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

            <h3>📎 Attached Document</h3>
            <a class="download-link" href="uploads/PET_APPLICANTS_DOCUMENT.pdf" download>⬇️ Download Document</a>

            <br><a class="back-link" href="applications.php">← Back to My Applications</a>
        </div>

    </body>

    </html>

<?php else: ?>
    <p>No appointment found.</p>
<?php endif; ?>