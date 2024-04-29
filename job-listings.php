<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}
require("connect-db.php");
error_reporting(E_ALL);

// Initialize filters
$role = isset($_GET['role']) ? $_GET['role'] : '';
$location = isset($_GET['location']) ? $_GET['location'] : '';

// Call the stored procedure
$sql = "CALL FilterJobs(:role, :location)";
$params = ['role' => $role, 'location' => $location];

$stmt = $conn->prepare($sql);
$stmt->execute($params);
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

    <!-- Filter Form -->
    <form action="" method="get">
        <label for="role">Filter by Role:</label>
        <input type="text" id="role" name="role" placeholder="Enter role" value="<?= htmlspecialchars($_GET['role'] ?? '') ?>">
        <label for="location">Filter by Location:</label>
        <input type="text" id="location" name="location" placeholder="Enter location" value="<?= htmlspecialchars($_GET['location'] ?? '') ?>">
        <button type="submit">Filter</button>
    </form>

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
