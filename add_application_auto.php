<?php
session_start();
require 'connect-db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['post_id'])) {
    $user_id = $_SESSION['user_id'];
    $post_id = $_GET['post_id'];
    $default_status = 'Applied'; // Default status

    // Insert application into the database
    $sql = "INSERT INTO Application (user_id, post_id, status) VALUES (:user_id, :post_id, :status)";
    $stmt = $conn->prepare($sql);

    if ($stmt->execute(['user_id' => $user_id, 'post_id' => $post_id, 'status' => $default_status])) {
        header("Location: applications.php"); // Redirect to applications list page
        exit();
    } else {
        echo "Error adding application.";
    }
} else {
    echo "No job selected.";
}
