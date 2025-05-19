<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin_name'])) {
    header('Location: adminlogin.php');
    exit();
}

if (!isset($_GET['application_id'])) {
    die("Application ID missing.");
}

$application_id = intval($_GET['application_id']);

$query = "
    SELECT 
        aa.*, 
        u.fullname, u.email, 
        p.name AS pet_name,
        p.description AS pet_description,
        c.category_name AS pet_category,  /* Join with pet_categories table to get the category name */
        p.image_path AS pet_image,  /* Assuming there's an 'image_path' column for the pet image */
        s.status_name
    FROM adoptionapplications aa
    JOIN users u ON aa.user_id = u.user_id
    JOIN pets p ON aa.pet_id = p.pet_id
    LEFT JOIN pet_categories c ON p.category_id = c.category_id  /* Join to get category name */
    LEFT JOIN application_statuses s ON aa.status_id = s.status_id
    WHERE aa.application_id = $application_id
    LIMIT 1
";


$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    die("Application not found.");
}

$app = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Application Info Overlay</title>
    <link rel="icon" href="images/logo.png" type="image/png">
    <style>
        html,
        body {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0f2f5;
        }

        .background-frame {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            border: none;
            z-index: 0;
            filter: blur(5px) brightness(0.85);
            pointer-events: none;
        }

        .info-panel {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            max-width: 800px;
            width: 90%;
            background: #ffffff;
            border-radius: 20px;
            padding: 40px 30px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
            z-index: 2;
            overflow-y: auto;
            max-height: 90vh;
        }

        .info-panel h1 {
            text-align: center;
            font-size: 2rem;
            margin-bottom: 25px;
            color: #2c3e50;
        }

        .info-details {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #eee;
            padding-bottom: 6px;
        }

        .info-label {
            font-weight: 600;
            color: #555;
            flex: 1;
        }

        .info-value {
            color: #222;
            flex: 2;
            text-align: right;
            word-break: break-word;
        }

        .pet-image-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .pet-image {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-group {
            margin-top: 35px;
            text-align: center;
        }

        .btn-action {
            padding: 12px 24px;
            margin: 0 10px;
            font-size: 1rem;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
            color: white;
            min-width: 120px;
        }

        .btn-action:hover {
            transform: scale(1.05);
        }

        .application-action-form {
            display: flex;
            justify-content: center;
            gap: 10px;
            /* spacing between buttons */
            margin-top: 15px;
        }

        .application-action-form button {
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        .application-action-form .approve {
            background-color: #4CAF50;
            color: white;
        }

        .application-action-form .reject {
            background-color: #f44336;
            color: white;
        }

        .application-action-form button:hover {
            opacity: 0.9;
        }


        @media (max-width: 768px) {
            .info-row {
                flex-direction: column;
                align-items: flex-start;
            }

            .info-value {
                text-align: left;
            }

            .btn-group {
                display: flex;
                flex-direction: column;
                gap: 12px;
            }

            .btn-action {
                width: 100%;
            }
        }

        .btn-action.cancel {
            display: inline-block;
            background-color: #6c757d;
            /* gray */
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            text-align: center;
            margin: 20px auto;
        }

        .center-cancel {
            display: flex;
            justify-content: center;
        }
    </style>
</head>

<body>

    <iframe src="manage_applications.php" class="background-frame"></iframe>

    <div class="info-panel">
        <h1>Application Details</h1>

        <div class="pet-image-container">
            <img src="<?= htmlspecialchars($app['pet_image']) ?>" alt="Pet Image" class="pet-image">
        </div>

        <div class="info-details">
            <div class="info-row">
                <div class="info-label">Pet Name:</div>
                <div class="info-value"><?= htmlspecialchars($app['pet_name']) ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Pet Category:</div>
                <div class="info-value"><?= htmlspecialchars($app['pet_category']) ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Pet Description:</div>
                <div class="info-value"><?= nl2br(htmlspecialchars($app['pet_description'])) ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Applicant:</div>
                <div class="info-value"><?= htmlspecialchars($app['fullname']) ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Email:</div>
                <div class="info-value"><?= htmlspecialchars($app['email']) ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Location:</div>
                <div class="info-value"><?= htmlspecialchars($app['location']) ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Living Conditions:</div>
                <div class="info-value"><?= nl2br(htmlspecialchars($app['living_conditions'])) ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Pet Experience:</div>
                <div class="info-value"><?= nl2br(htmlspecialchars($app['pet_experience'])) ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Home Type:</div>
                <div class="info-value"><?= htmlspecialchars($app['home_type']) ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Has Children:</div>
                <div class="info-value"><?= htmlspecialchars($app['has_children']) ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Hours Pet Left Alone:</div>
                <div class="info-value"><?= htmlspecialchars($app['hours_alone']) ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Has Other Pets:</div>
                <div class="info-value"><?= htmlspecialchars($app['has_other_pets']) ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Willing to Provide Vet Care:</div>
                <div class="info-value"><?= htmlspecialchars($app['willing_vet_care']) ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Submission Date:</div>
                <div class="info-value"><?= htmlspecialchars($app['submission_date']) ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Status:</div>
                <div class="info-value"><?= htmlspecialchars($app['status_name']) ?></div>
            </div>
        </div>

        <p>‎ </p>

        <form method="POST" action="update_application_status.php" class="application-action-form">
            <input type="hidden" name="application_id" value="<?= $app['application_id'] ?>">
            <button type="submit" class="btn-action approve" name="action" value="Approved">Approve</button>
            <button type="submit" class="btn-action reject" name="action" value="Rejected">Reject</button>
        </form>

        <div class="center-cancel">
            <a href="manage_applications.php" class="btn-action cancel">Cancel</a>
        </div>

    </div>
    </div>

</body>

</html>