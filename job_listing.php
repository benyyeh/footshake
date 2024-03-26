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
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$stmt = $conn->prepare("SELECT * FROM Job_Posting WHERE id = :id");
$stmt->execute(['id' => $id]);
$job = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$job) {
    echo "Job posting not found.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($job['role']) ?> - Job Listing</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
        }

        h1 {
            background-color: #007bff;
            color: #fff;
            padding: 20px;
            margin: 0;
            text-align: center;
        }

        .job-details {
            background-color: #fff;
            width: 80%;
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        p {
            margin: 10px 0;
        }

        a {
            text-decoration: none;
            color: #007bff;
        }

        a:hover {
            text-decoration: underline;
        }

        .apply-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .apply-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1><?= htmlspecialchars($job['role']) ?></h1>
    <div class="job-details">
        <p><strong>Location:</strong> <?= htmlspecialchars($job['location']) ?></p>
        <p><strong>Date Posted:</strong> <?= $job['date_posted'] ?></p>
        <p><strong>Role Type:</strong> <?= htmlspecialchars($job['role_type']) ?></p>
        <p><a class="apply-btn" href="<?= $job['app_link'] ?>">Apply Here</a></p>
        <p><a href="add_application_auto.php?post_id=<?= $id ?>">Add Application to My Applications</a></p>
    </div>
</body>
</html>

