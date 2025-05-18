<?php
session_start();
include 'db.php';

$type = $_GET['type'] ?? '';

function escape($str)
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// Load header image if exists
$imagePath = 'images/header.png'; // make sure this path is correct relative to this script
if (file_exists($imagePath)) {
    $imageType = pathinfo($imagePath, PATHINFO_EXTENSION);
    $imageData = file_get_contents($imagePath);
    $base64 = 'data:image/' . $imageType . ';base64,' . base64_encode($imageData);
    echo '<div style="text-align: center; margin-bottom: 20px;">
            <img src="' . $base64 . '" alt="Header Image" style="max-width: 100%; height: auto;">
          </div>';
}

switch ($type) {
    case 'total':
        $query = "SELECT p.name AS pet_name, pc.category_name, p.pet_status
              FROM pets p
              LEFT JOIN pet_categories pc ON p.category_id = pc.category_id
              ORDER BY p.name ASC";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) > 0) {
            echo '<table class="dashboard-table">';
            echo '<thead><tr><th>Pet Name</th><th>Category</th><th>Status</th></tr></thead><tbody>';
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td>' . escape($row['pet_name']) . '</td>';
                echo '<td>' . escape($row['category_name']) . '</td>';
                echo '<td>' . ucfirst(escape($row['pet_status'])) . '</td>';
                echo '</tr>';
            }
            echo '</tbody></table>';
        } else {
            echo '<p>No pets found.</p>';
        }
        break;

    case 'available':
        $query = "SELECT p.name AS pet_name, pc.category_name, p.pet_status
              FROM pets p
              LEFT JOIN pet_categories pc ON p.category_id = pc.category_id
              WHERE p.pet_status = 'available'
              ORDER BY p.name ASC";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) > 0) {
            echo '<table class="dashboard-table">';
            echo '<thead><tr><th>Pet Name</th><th>Category</th><th>Status</th></tr></thead><tbody>';
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td>' . escape($row['pet_name']) . '</td>';
                echo '<td>' . escape($row['category_name']) . '</td>';
                echo '<td>' . ucfirst(escape($row['pet_status'])) . '</td>';
                echo '</tr>';
            }
            echo '</tbody></table>';
        } else {
            echo '<p>No available pets found.</p>';
        }
        break;

    case 'pending':
        $query = "SELECT p.name AS pet_name, c.category_name AS category_name, u.fullname, u.email, u.phone_num, aa.submission_date
              FROM adoptionapplications aa
              JOIN users u ON aa.user_id = u.user_id
              JOIN pets p ON aa.pet_id = p.pet_id
              JOIN pet_categories c ON p.category_id = c.category_id
              JOIN application_statuses s ON aa.status_id = s.status_id
              WHERE s.status_name = 'pending'
              ORDER BY aa.submission_date DESC";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) > 0) {
            echo '<table class="dashboard-table">';
            echo '<thead><tr><th>Pet Name</th><th>Category</th><th>Applicant</th><th>Email</th><th>Phone</th><th>Submitted</th></tr></thead><tbody>';
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td>' . escape($row['pet_name']) . '</td>';
                echo '<td>' . escape($row['category_name']) . '</td>';
                echo '<td>' . escape($row['fullname']) . '</td>';
                echo '<td>' . escape($row['email']) . '</td>';
                echo '<td>' . escape($row['phone_num']) . '</td>';
                echo '<td>' . date('M d, Y — h:i A', strtotime($row['submission_date'])) . '</td>';
                echo '</tr>';
            }
            echo '</tbody></table>';
        } else {
            echo '<p>No pending applications found.</p>';
        }
        break;

    case 'approved':
        $query = "SELECT p.name AS pet_name, c.category_name AS category_name, u.fullname, u.email, u.phone_num, aa.submission_date
              FROM adoptionapplications aa
              JOIN users u ON aa.user_id = u.user_id
              JOIN pets p ON aa.pet_id = p.pet_id
              JOIN pet_categories c ON p.category_id = c.category_id
              JOIN application_statuses s ON aa.status_id = s.status_id
              WHERE s.status_name = 'approved'
              ORDER BY aa.submission_date DESC";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) > 0) {
            echo '<table class="dashboard-table">';
            echo '<thead><tr><th>Pet Name</th><th>Category</th><th>Applicant</th><th>Email</th><th>Phone</th><th>Submitted</th></tr></thead><tbody>';
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td>' . escape($row['pet_name']) . '</td>';
                echo '<td>' . escape($row['category_name']) . '</td>';
                echo '<td>' . escape($row['fullname']) . '</td>';
                echo '<td>' . escape($row['email']) . '</td>';
                echo '<td>' . escape($row['phone_num']) . '</td>';
                echo '<td>' . date('M d, Y — h:i A', strtotime($row['submission_date'])) . '</td>';
                echo '</tr>';
            }
            echo '</tbody></table>';
        } else {
            echo '<p>No approved applications found.</p>';
        }
        break;

    default:
        echo '<p>Invalid report type.</p>';
        break;
}
?>
