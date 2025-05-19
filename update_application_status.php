<?php
include 'db.php';
session_start();

if (!isset($_SESSION['admin_name'])) {
    header('Location: adminlogin.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate and sanitize inputs
    $application_id = isset($_POST['application_id']) ? intval($_POST['application_id']) : 0;
    $action = $_POST['action'] ?? '';

    if ($application_id <= 0 || !in_array($action, ['Approved', 'Rejected'])) {
        die("Invalid input.");
    }

    if ($action === 'Approved') {
        $status_id = 2; // Approved

        // 1. Update application status to Approved
        $stmt = mysqli_prepare($conn, "UPDATE adoptionapplications SET status_id = ? WHERE application_id = ?");
        mysqli_stmt_bind_param($stmt, "ii", $status_id, $application_id);
        mysqli_stmt_execute($stmt);

        // 2. Get pet_id linked to this application
        $stmt = mysqli_prepare($conn, "SELECT pet_id FROM adoptionapplications WHERE application_id = ?");
        mysqli_stmt_bind_param($stmt, "i", $application_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (!$result || mysqli_num_rows($result) === 0) {
            die("No pet found for this application.");
        }

        $row = mysqli_fetch_assoc($result);
        $pet_id = intval($row['pet_id']);

        // 3. Set pet status to 'adopted'
        $stmt = mysqli_prepare($conn, "UPDATE pets SET pet_status = 'adopted' WHERE pet_id = ?");
        mysqli_stmt_bind_param($stmt, "i", $pet_id);
        mysqli_stmt_execute($stmt);

        // 4. Reject all other pending applications for this pet
        $status_rejected = 3;
        $status_pending = 1;
        $stmt = mysqli_prepare($conn, "UPDATE adoptionapplications SET status_id = ? WHERE pet_id = ? AND application_id != ? AND status_id = ?");
        mysqli_stmt_bind_param($stmt, "iiii", $status_rejected, $pet_id, $application_id, $status_pending);
        mysqli_stmt_execute($stmt);

        // Redirect to appointment creation
        header("Location: appointment.php?application_id=$application_id");
        exit();

    } elseif ($action === 'Rejected') {
        $status_id = 3; // Rejected

        $stmt = mysqli_prepare($conn, "UPDATE adoptionapplications SET status_id = ? WHERE application_id = ?");
        mysqli_stmt_bind_param($stmt, "ii", $status_id, $application_id);
        mysqli_stmt_execute($stmt);

        header('Location: manage_applications.php');
        exit();
    }
}
?>
