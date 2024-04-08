<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}
require("connect-db.php");
error_reporting(E_ALL);
session_start(); 
$stmt = $conn->query("SELECT id, location, role, company_id, date_posted, role_type FROM Job_Posting");
$jobPostings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Job Listings</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/job-listings.css">
</head>
<body>
    <!-- Top bar -->
    <div class="top-bar">
        <h1>Job Listings</h1>
        <a href="user_info.php" class="user-info-btn">User Info</a>
    </div>

    <!-- Job Listings -->
    <ul>
        <?php foreach ($jobPostings as $job): ?>
            <li>
                <a href="/footshake/job_listing.php?id=<?= $job['id'] ?>">
                    <div class="job-info"><?= htmlspecialchars($job['role']) ?></div>
                    <div class="job-location"><?= htmlspecialchars($job['location']) ?></div>
                    <div class="date-posted">Posted on <?= $job['date_posted'] ?></div>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>



