<?php
require 'connect-db.php'; // Adjust this line to use your actual database connection script
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the email is already registered
    $sql = "SELECT * FROM Account WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo "Email already registered. Please use a different email.";
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert new account into the database
        $sql = "INSERT INTO Account (email, password) VALUES (:email, :password)";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':password', $hashedPassword);

        if ($stmt->execute()) {
            echo "Registration successful. <a href='login.php'>Login here</a>.";
        } else {
            echo "Registration failed. Please try again.";
        }
    }
}
?>
<!-- signup.html or the HTML part of register.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="css/register.css">
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        <form action="register.php" method="post">
            <label for="email">Email:</label><br>
            <input type="email" name="email" id="email" required><br>
            <label for="password">Password:</label><br>
            <input type="password" name="password" id="password" required><br>
            <input type="submit" value="Register">
        </form>
        <?php if (isset($registrationMessage)): ?>
            <p class="success-message"><?= $registrationMessage ?></p>
        <?php endif; ?>
        <a href="login.php" class="login-link">Login here</a>
    </div>
</body>
</html>


