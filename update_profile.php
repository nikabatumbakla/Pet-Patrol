<?php
include "db.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Sanitize inputs
$fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$phone_num = mysqli_real_escape_string($conn, $_POST['phone_num']);
$street_address = mysqli_real_escape_string($conn, $_POST['street_address']);
$city = mysqli_real_escape_string($conn, $_POST['city']);
$state_province = mysqli_real_escape_string($conn, $_POST['state_province']);
$postal_code = mysqli_real_escape_string($conn, $_POST['postal_code']);
$country = mysqli_real_escape_string($conn, $_POST['country']);

// Update users table
$user_update = "UPDATE users SET fullname='$fullname', email='$email', phone_num='$phone_num' WHERE user_id='$user_id'";
mysqli_query($conn, $user_update);

// Check if address exists for the user
$check_address = mysqli_query($conn, "SELECT * FROM addresses WHERE user_id='$user_id'");

if (mysqli_num_rows($check_address) > 0) {
    // Update existing address
    $address_update = "
        UPDATE addresses SET
            street_address='$street_address',
            city='$city',
            state_province='$state_province',
            postal_code='$postal_code',
            country='$country'
        WHERE user_id='$user_id'";
    mysqli_query($conn, $address_update);
} else {
    // Insert new address
    $address_insert = "
        INSERT INTO addresses (
            user_id, street_address, city, state_province, postal_code, country
        ) VALUES (
            '$user_id', '$street_address', '$city', '$state_province', '$postal_code', '$country'
        )";
    mysqli_query($conn, $address_insert);
}

// Redirect back to profile page
header("Location: profile.php");
exit;
?>