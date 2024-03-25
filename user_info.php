<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}
?>

<?php
require("connect-db.php");
ini_set('display_errors', 1);
error_reporting(E_ALL);

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
        echo "<p>Information saved successfully.</p>";
    } else {
        echo "<p>Error saving information.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Information</title>
</head>
<body>
    <h2>User Information</h2>
    <form action="user_info.php" method="post">
        Name: <input type="text" name="name" required><br>
        Date of Birth: <input type="date" name="dob" required><br>
        School: <input type="text" name="school" required><br>
        <input type="submit" value="Submit">
    </form>
</body>
</html>
