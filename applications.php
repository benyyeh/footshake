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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Applications</title>
</head>
<body>
    <h1>My Applications</h1>
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
</body>
</html>

