<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin_name'])) {
    header('Location: adminlogin.php');
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid request.");
}

$pet_id = intval($_GET['id']);

$petQuery = mysqli_query($conn, "SELECT image_path FROM pets WHERE pet_id = $pet_id");
if (!$petQuery || mysqli_num_rows($petQuery) === 0) {
    die("Pet not found.");
}

$pet = mysqli_fetch_assoc($petQuery);
$image_path = $pet['image_path'];

$applicantQuery = mysqli_query($conn, "SELECT DISTINCT user_id FROM adoptionapplications WHERE pet_id = $pet_id");
$affected_users = [];

if ($applicantQuery) {
    while ($row = mysqli_fetch_assoc($applicantQuery)) {
        $affected_users[] = $row['user_id'];
    }
}

if (!empty($affected_users)) {
    $_SESSION['pet_deleted_users'] = $affected_users;
}

$deleteApps = mysqli_query($conn, "DELETE FROM adoptionapplications WHERE pet_id = $pet_id");

$deletePet = mysqli_query($conn, "DELETE FROM pets WHERE pet_id = $pet_id");

if ($deletePet) {
    if (!empty($image_path) && file_exists($image_path)) {
        unlink($image_path);
    }

    header("Location: manage_pets.php?message=deleted");
    exit();

} else {
    die("Error deleting pet: " . mysqli_error($conn));
}
?>