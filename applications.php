<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}
require("connect-db.php");
error_reporting(E_ALL);
session_start(); 
?>

<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user applications from the database
$sql = "SELECT Application.id, Job_Posting.role, Application.status
        FROM Application
        JOIN Job_Posting ON Application.post_id = Job_Posting.id
        WHERE Application.user_id = :user_id";
$stmt = $conn->prepare($sql);
$stmt->execute(['user_id' => $user_id]);
$applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch user information from the database
$sql = "SELECT name, DOB, school FROM Users WHERE user_id = :user_id";
$stmt = $conn->prepare($sql);
$stmt->execute(['user_id' => $user_id]);
$userInfo = $stmt->fetch(PDO::FETCH_ASSOC);
?>
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Applications</title>
    <style>
        /* Style for the top bar */
        .top-bar {
            background-color: #007bff; /* Blue color */
            color: #fff;
            padding: 10px;
            display: flex;
            align-items: center;
        }

        /* Logo style */
        .logo {
            margin-right: 10px;
            width: 100px; /* Adjust the width as needed */
        }

        /* Title style */
        .title {
            margin: 0;
            flex: 1; /* Take remaining space */
        }

        /* Style for main content */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #cde8ff; /* Lighter shade of blue */
        }

        /* Container for application forms */
        .applications-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Style for application form */
        form {
            margin-bottom: 20px;
            max-width: 400px; /* Limit form width */
            width: 100%; /* Take full width of the container */
        }

        /* Style for select dropdown */
        select {
            padding: 5px;
            width: 100%; /* Take full width of the container */
        }

        /* Style for submit button */
        input[type="submit"] {
            padding: 5px 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
            width: 100%; /* Take full width of the container */
        }

        /* Style for "Find More Jobs" button */
        .find-jobs-button {
            padding: 10px 20px;
            background-color: #007bff; /* Blue color */
            color: #fff;
            border: 1px solid #fff; /* White border */
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }

        /* Hover effect for "Find More Jobs" button */
        .find-jobs-button:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }

        /* Style for user information */
        .user-info {
            margin-bottom: 20px;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 4px;
        }

        /* Style for user information labels */
        .info-label {
            font-weight: bold;
        }

        /* Style for user information values */
        .info-value {
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <!-- Top bar with the logo, title, and "Find More Jobs" button -->
    <div class="top-bar">
        <!-- Logo -->
        <img src="data/logo.png" alt="Logo" class="logo">
        <!-- Title -->
        <h1 class="title">My Applications</h1>
        <!-- "Find More Jobs" button -->
        <a href="job-listings.php" class="find-jobs-button">Find More Jobs</a>
    </div>

    <!-- Main content -->
    <div class="applications-container">
        <!-- User Information -->
        <div class="user-info">
            <h2>User Information</h2>
            <p><span class="info-label">Name:</span> <span class="info-value"><?= htmlspecialchars($userInfo['name']) ?></span></p>
            <p><span class="info-label">Date of Birth:</span> <span class="info-value"><?= htmlspecialchars($userInfo['DOB']) ?></span></p>
            <p><span class="info-label">School:</span> <span class="info-value"><?= htmlspecialchars($userInfo['school']) ?></span></p>
        </div>

        <!-- User Applications -->
        <?php foreach ($applications as $app): ?>
            <form action="update_application_status.php" method="post">
                <input type="hidden" name="application_id" value="<?= $app['id'] ?>">
                <?= htmlspecialchars($app['role']) ?> - Status: 
                <select name="status">
                    <option <?= $app['status'] == 'Applied' ? 'selected' : '' ?>>Applied</option>
                    <option <?= $app['status'] == 'Interview' ? 'selected' : '' ?>>Interview</option>
                    <option <?= $app['status'] == 'Offer' ? 'selected' : '' ?>>Offer</option>
                </select>
                <input type="submit" value="Update Status">
            </form>
        <?php endforeach; ?>
    </div>
</body>
</html>






