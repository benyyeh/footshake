<?php
session_start();
require 'connect-db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$application_id = $_POST['application_id'];
$status = $_POST['status'];

// Update application status in the database
$sql = "UPDATE Application SET status = :status WHERE id = :application_id AND user_id = :user_id";
$stmt = $conn->prepare($sql);
if ($stmt->execute(['status' => $status, 'application_id' => $application_id, 'user_id' => $_SESSION['user_id']])) {
    header("Location: applications.php"); // Redirect back to the applications list
    exit();
} else {
    echo "Error updating application status.";
}
