<?php
session_start();
include 'db.php'; // Assumes db.php connects to your DB using $conn

if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to apply for adoption.");
}

$user_id = $_SESSION['user_id'];
$pet_id = intval($_POST['pet_id'] ?? $_GET['pet_id'] ?? 0);

if (!$pet_id) {
    die("Pet not specified.");
}

// Fetch pet details
$stmt = $conn->prepare("SELECT name, species, description, health_condition, pet_status, image_path FROM pets WHERE pet_id = ?");
$stmt->bind_param("i", $pet_id);
$stmt->execute();
$pet_result = $stmt->get_result();
$pet = $pet_result->fetch_assoc();

if (!$pet) {
    die("Pet not found.");
}

// Fetch the status_id for 'pending'
$status_stmt = $conn->prepare("SELECT status_id FROM application_statuses WHERE status_name = 'pending'");
$status_stmt->execute();
$status_result = $status_stmt->get_result();
$status_row = $status_result->fetch_assoc();

if (!$status_row) {
    die("Pending status not configured in the database.");
}
$pending_status_id = $status_row['status_id'];

// Fetch addresses of the current user
$address_stmt = $conn->prepare("SELECT address_id, street_address, city, state_province, postal_code FROM addresses WHERE user_id = ?");
$address_stmt->bind_param("i", $user_id);
$address_stmt->execute();
$address_result = $address_stmt->get_result();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if user already applied
    $existing = $conn->prepare("SELECT application_id FROM adoptionapplications WHERE user_id = ? AND pet_id = ?");
    $existing->bind_param("ii", $user_id, $pet_id);
    $existing->execute();
    $existing_result = $existing->get_result();

    if ($existing_result->num_rows > 0) {
        echo "<script>alert('You have already applied for this pet.'); window.location.href='applications.php';</script>";
        exit();
    }

    // Form data
    $location = $_POST['location'] ?? '';
    $living_conditions = $_POST['living_conditions'] ?? '';
    $pet_experience = $_POST['pet_experience'] ?? '';
    $home_type = $_POST['home_type'] ?? '';
    $has_children = $_POST['has_children'] ?? '';
    $hours_alone = $_POST['hours_alone'] ?? '';
    $has_other_pets = $_POST['has_other_pets'] ?? '';
    $willing_vet_care = $_POST['willing_vet_care'] ?? '';
    $application_address_id = isset($_POST['application_address_id']) ? intval($_POST['application_address_id']) : null;

    $insert = $conn->prepare("
        INSERT INTO adoptionapplications (
            user_id, pet_id, status_id, application_address_id, location, living_conditions, 
            pet_experience, home_type, has_children, hours_alone, has_other_pets, willing_vet_care
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $insert->bind_param(
        "iiiissssssss",
        $user_id,
        $pet_id,
        $pending_status_id,
        $application_address_id,
        $location,
        $living_conditions,
        $pet_experience,
        $home_type,
        $has_children,
        $hours_alone,
        $has_other_pets,
        $willing_vet_care
    );

    if ($insert->execute()) {
        echo "<script>alert('Application submitted successfully!'); window.location.href='applications.php';</script>";
        exit();
    } else {
        echo "Error: " . $insert->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Adoption Application</title>
    <link rel="icon" href="images/logo.png" type="image/png">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

        :root {
            --primary-color: #e1a800;
            --secondary-color: #ffe094;
            --highlight-color: #f6e10d;
            --dark-yellow: #c88f0a;
        }

        body {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
            background: var(--dark-yellow);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 180vh;
        }

        .form-container {
            max-width: 700px;
            width: 90%;
            background: linear-gradient(135deg, #fdfcfa, #fff);
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            padding: 2rem;
            border-radius: 12px;
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: var(--dark-yellow);
        }

        .pet-info {
            text-align: center;
            margin-bottom: 20px;
        }

        .pet-info img {
            max-width: 300px;
            height: auto;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-top: 15px;
            font-weight: 600;
            color: #333;
        }

        input[type="text"],
        select,
        textarea {
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-top: 6px;
            font-size: 1rem;
            resize: vertical;
        }

        input[type="text"]:focus,
        select:focus,
        textarea:focus {
            border-color: var(--primary-color);
            outline: none;
        }

        button[type="submit"] {
            margin-top: 25px;
            padding: 12px;
            background-color: var(--dark-yellow);
            color: white;
            font-size: 1rem;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #faa914;
        }

        @media (max-width: 600px) {
            .form-container {
                padding: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h2>Adoption Application</h2>

        <div class="pet-info">
            <?php if (!empty($pet['image_path'])): ?>
                <img src="<?= htmlspecialchars($pet['image_path']) ?>" alt="Pet Image">
            <?php endif; ?>
            <h4><?= htmlspecialchars($pet['name']) ?> (<?= htmlspecialchars($pet['species']) ?>)</h4>
            <p><strong>Status:</strong> <?= htmlspecialchars($pet['pet_status']) ?></p>
            <p><strong>Health:</strong> <?= htmlspecialchars($pet['health_condition']) ?></p>
            <p><?= nl2br(htmlspecialchars($pet['description'])) ?></p>
        </div>

        <form method="POST">
            <input type="hidden" name="pet_id" value="<?= $pet_id ?>">

            <label>Select Address for this Application</label>
            <select name="application_address_id" required>
                <option value="">Choose an address</option>
                <?php while ($row = $address_result->fetch_assoc()): ?>
                    <option value="<?= $row['address_id'] ?>">
                        <?= htmlspecialchars($row['street_address'] . ', ' . $row['city'] . ', ' . $row['state_province'] . ' ' . $row['postal_code']) ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label>Your Location</label>
            <input type="text" name="location" required>

            <label>Living Conditions</label>
            <textarea name="living_conditions" rows="3" required></textarea>

            <label>Pet Experience</label>
            <textarea name="pet_experience" rows="3" required></textarea>

            <label>Home Type</label>
            <select name="home_type" required>
                <option value="">Select</option>
                <option>House</option>
                <option>Apartment</option>
                <option>Condo</option>
                <option>Other</option>
            </select>

            <label>Has Children?</label>
            <select name="has_children" required>
                <option value="">Select</option>
                <option value="yes">Yes</option>
                <option value="no">No</option>
                <option value="sometimes">Sometimes</option>
            </select>

            <label>Hours Alone Daily</label>
            <input type="text" name="hours_alone" required>

            <label>Has Other Pets?</label>
            <select name="has_other_pets" required>
                <option value="">Select</option>
                <option value="yes">Yes</option>
                <option value="no">No</option>
            </select>

            <label>Willing to Provide Vet Care?</label>
            <select name="willing_vet_care" required>
                <option value="">Select</option>
                <option value="yes">Yes</option>
                <option value="no">No</option>
                <option value="unsure">Unsure</option>
            </select>

            <button type="submit">Submit Application</button>
        </form>
    </div>
</body>

</html>