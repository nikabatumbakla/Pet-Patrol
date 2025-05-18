<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['category_name']);
    $query = "INSERT INTO pet_categories (category_name) VALUES ('$name')";
    mysqli_query($conn, $query);
    header('Location: manage_pets.php');
}
?>