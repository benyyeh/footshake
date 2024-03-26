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
</head>
<body>
    <h1><?= htmlspecialchars($job['role']) ?></h1>
    <p>Location: <?= htmlspecialchars($job['location']) ?></p>
    <p>Date Posted: <?= $job['date_posted'] ?></p>
    <p>Role Type: <?= htmlspecialchars($job['role_type']) ?></p>
    <p><a href="<?= $job['app_link'] ?>">Apply Here</a></p>

    <p><a href="add_application_auto.php?post_id=<?= $id ?>">Add Application to My Applications</a></p>

</form>
</body>
</html>
