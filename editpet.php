<?php
include "db.php";

$pet_id = $_GET['id'] ?? null;

if (!$pet_id) {
    die("Pet ID is required.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $health = $_POST['health_condition'];
    $status = $_POST['status'];
    $description = $_POST['description'];
    $vaccination = $_POST['vaccination_history'];

    $sql = "UPDATE pets SET 
            health_condition=?, pet_status=?, description=?, 
            vaccination_history=?
          WHERE pet_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssssi",
        $health,
        $status,
        $description,
        $vaccination,
        $pet_id
    );
    $stmt->execute();

    header("Location: manage_pets.php?message=updated");
    exit();

}

// Fetch current data
$sql = "SELECT pets.*, pet_categories.category_name 
        FROM pets 
        LEFT JOIN pet_categories ON pets.category_id = pet_categories.category_id 
        WHERE pet_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $pet_id);
$stmt->execute();
$result = $stmt->get_result();
$pet = $result->fetch_assoc();

if (!$pet) {
    die("Pet not found.");
}

?>

<!DOCTYPE html>
<html>
<style>
    /* Edit Pet Form Container */
    .edit-pet-form {
        max-width: 700px;
        margin: 40px auto;
        padding: 30px;
        background-color: rgb(254, 227, 227);
        border-radius: 16px;
        font-family: 'Segoe UI', sans-serif;
    }

    .edit-pet-form h2 {
        text-align: center;
        margin-bottom: 25px;
        color: #333;
    }

    /* Form Inputs */
    .edit-pet-form form {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .edit-pet-form label {
        font-weight: 600;
        margin-bottom: 5px;
        color: #444;
    }

    .edit-pet-form input[type="text"],
    .edit-pet-form input[type="number"],
    .edit-pet-form select,
    .edit-pet-form textarea {
        padding: 10px 12px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 0.95rem;
        background-color: #fff;
        transition: border-color 0.3s;
    }

    .edit-pet-form input:focus,
    .edit-pet-form textarea:focus,
    .edit-pet-form select:focus {
        border-color: #ffa07a;
        outline: none;
    }

    /* Submit Button */
    .edit-pet-form button[type="submit"] {
        padding: 12px;
        background-color: #ff7f50;
        border: none;
        color: white;
        font-size: 1rem;
        font-weight: bold;
        border-radius: 10px;
        cursor: pointer;
        margin-top: 10px;
        transition: background-color 0.3s;
    }

    .edit-pet-form button[type="submit"]:hover {
        background-color: #ff5722;
    }
</style>

<head>
    <title>Edit Pet</title>
    <link rel="icon" href="images/logo.png" type="image/png">
</head>

<body>

    <div class="edit-pet-form">
        <h1 style="text-align: center">EDIT PET</h1>
        <p>‎ </p>
        <h2><?= htmlspecialchars($pet['name']) ?></h2>
        <h2><?= htmlspecialchars($pet['category_name']) ?></h2>

        <form method="POST">
            <label>Health Condition</label>
            <input type="text" name="health_condition" value="<?= htmlspecialchars($pet['health_condition']) ?>"
                required />

            <label>Status</label>
            <select name="status" required>
                <option value="available" <?= $pet['pet_status'] == 'available' ? 'selected' : '' ?>>Available</option>
                <option value="adopted" <?= $pet['pet_status'] == 'adopted' ? 'selected' : '' ?>>Adopted</option>
                <option value="pending" <?= $pet['pet_status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
            </select>

            <label>Description</label>
            <textarea name="description"><?= htmlspecialchars($pet['description']) ?></textarea>

            <label>Vaccination History</label>
            <textarea name="vaccination_history"><?= htmlspecialchars($pet['vaccination_history']) ?></textarea>

            <button type="submit">Update Pet</button>
        </form>
    </div>


</body>

</html>