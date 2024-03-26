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
</head>
<body>
    <h1>Job Listings</h1>
    <ul>
        <?php foreach ($jobPostings as $job): ?>
            <li>
                <a href="/footshake/job_listing.php?id=<?= $job['id'] ?>">
                    <?= htmlspecialchars($job['role']) ?> - <?= htmlspecialchars($job['location']) ?> (Posted on <?= $job['date_posted'] ?>)
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>

