<?php
session_start();

// Set the password
$correctPassword = "MasterSpring2024";

if (isset($_SESSION['authenticated'])) {
    unset($_SESSION['authenticated']);
}

if (isset($_POST['submit'])) {
    $password = $_POST['password'];
    if ($password == $correctPassword) {
        $_SESSION['authenticated'] = true;
    } else {
        echo "<p>Incorrect password. Please try again.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manager Access</title>
    <link rel="stylesheet" href="css/manager.css">
</head>
<body>
    <?php if (isset($_SESSION['authenticated']) && $_SESSION['authenticated']): ?>
        <div class="form-container">
            <button onclick="window.location.href='manage-db.php';">Go to Manage Database</button>
        </div>
    <?php else: ?>
        <div class="form-container">
            <form method="post">
                <label for="password">Enter password:</label>
                <input type="password" id="password" name="password" required>
                <button type="submit" name="submit">Submit</button>
            </form>
        </div>
    <?php endif; ?>

    <div class="login-button">
        <a href="login.php" class="button">Back to Login</a>
    </div>
</body>
</html>

