<?php
session_start();
require("connect-db.php");
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ensure that user_id is available in the session
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];

        try {
            require("connect-db.php"); 

            $stmt = $conn->prepare("SELECT db_username FROM Users WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                $username = $user['db_username'];
                $revokeSql = "REVOKE CREATE, ALTER, DROP, INDEX, LOCK TABLES ON jlz8fv.* FROM '$username'@'localhost'";
                $conn->exec($revokeSql);
                $conn->exec("FLUSH PRIVILEGES");
                echo "Privileges successfully revoked for $username.";
            } 
        } catch (PDOException $e) {
            echo "Error revoking privileges: " . $e->getMessage();
        }
    }   echo "No user session found.";

}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $dob = $_POST['dob'];
    $school = $_POST['school'];
    $userId = $_SESSION['user_id']; // Get user_id from session

    // Insert the user information into the Users table
    $sql = "INSERT INTO Users (name, DOB, school, user_id) VALUES (:name, :dob, :school, :user_id)";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':name', $name);
    $stmt->bindValue(':dob', $dob);
    $stmt->bindValue(':school', $school);
    $stmt->bindValue(':user_id', $userId);

    if ($stmt->execute()) {
        // Redirect to applications.php after successful submission
        header("Location: applications.php");
        exit();
    } else {
        echo "<p>Error saving information.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Footshake</title>
    <link rel="stylesheet" href="css/user-info.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Top bar with the logo, title, and menu -->
    <div class="top-bar">
        <!-- Logo -->
        <img src="data/logo.png" alt="Logo" class="logo">
        <!-- Title -->
        <h1 class="title">Footshake</h1>
        <!-- Menu container -->
        <div class="menu-container">
            <!-- "Search for Jobs" button -->
            <a href="job-listings.php" class="menu-item">Search for Jobs</a>
        </div>
    </div>

    <!-- Main content -->
    <h2>User Information</h2>
    <form action="user_info.php" method="post">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
        <label for="dob">Date of Birth:</label>
        <input type="date" id="dob" name="dob" required>
        <label for="school">School:</label>
        <input type="text" id="school" name="school" required>
        <input type="submit" value="Submit">
    </form>
</body>
</html>




