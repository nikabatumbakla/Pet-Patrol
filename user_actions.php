<?php
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = intval($_POST['user_id'] ?? 0);
    $type = $_POST['type'] ?? 'users';
    $action = $_POST['action'] ?? '';

    if ($userId <= 0 || !in_array($type, ['users', 'admins']) || $action !== 'delete') {
        die("Invalid request.");
    }

    // Choose the correct table and column
    $table = $type === 'admins' ? 'admins' : 'users';
    $idField = $type === 'admins' ? 'id' : 'user_id';

    // Prepare delete statement
    $stmt = $conn->prepare("DELETE FROM `$table` WHERE `$idField` = ?");
    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        $deletedType = $type === 'admins' ? 'admin' : 'user';
        header("Location: manage_users.php?success=1&deleted=$deletedType");
        exit();
    } else {
        die("Database error: " . $stmt->error);
    }
} else {
    header("Location: manage_users.php");
    exit();
}
