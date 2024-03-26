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
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
        }

        .top-bar {
            background-color: #007bff;
            color: #fff;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        h1 {
            margin: 0;
            text-align: center;
        }

        .user-info-btn {
            background-color: #007bff;
            color: #fff;
            border: 1px solid #fff; /* White border */
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
        }

        .user-info-btn:hover {
            background-color: #0056b3;
        }

        ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
            width: 80%;
            margin: auto;
        }

        li {
            background-color: #fff;
            margin: 10px 0;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        li:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        a {
            text-decoration: none;
            color: #333;
            display: block;
            padding: 15px;
        }

        a:hover {
            background-color: #f0f0f0;
        }

        .job-info {
            font-size: 18px;
            font-weight: bold;
        }

        .job-location {
            color: #777;
        }

        .date-posted {
            color: #777;
        }
    </style>
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



