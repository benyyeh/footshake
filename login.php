<?php
require("connect-db.php");
?>

<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start(); // Start a new session or resume the existing one

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // SQL to check if the email exists
    $sql = "SELECT * FROM Account WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Password is correct, start user session
            $_SESSION['user_id'] = $user['id']; // Store user id in session
            $_SESSION['email'] = $user['email']; // Store email in session
            header("Location: user_info.php"); // Redirect to a logged-in page
            exit();
        } else {
            $errorMessage = "Incorrect password.";
        }
    } else {
        $errorMessage = "Incorrect email";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/login.css">
    <title>Login</title>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form method="post" action="login.php">
            <label for="email">Email:</label><br>
            <input type="text" name="email" id="email" required><br>
            <label for="password">Password:</label><br>
            <input type="password" name="password" id="password" required><br>
            <input type="submit" value="Login">
        </form>
        <?php if (isset($errorMessage)): ?>
            <p class="error-message"><?= $errorMessage ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
