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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['feedback'])) {
    $sql = "INSERT INTO Anonymous_Feedback (description) VALUES (:description)";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['description' => $_POST['feedback']]);
    echo "<p>Feedback submitted successfully.</p>";
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Anonymous Feedback</title>
</head>
<body>
    <h2>Submit Anonymous Feedback</h2>
    <form method="post">
        <textarea name="feedback" required placeholder="Your feedback..." rows="4" cols="50"></textarea><br>
        <button type="submit">Submit Feedback</button>
    </form>
    <?php
    // Display feedback
    $sql = "SELECT Id, description FROM Anonymous_Feedback ORDER BY Id DESC";
    foreach ($conn->query($sql) as $row) {
        echo "<p><b>Feedback #" . $row['Id'] . ":</b> " . htmlspecialchars($row['description']) . "</p>";
    }
    ?>
</body>
</html>