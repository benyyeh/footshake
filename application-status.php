<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}
require("connect-db.php");
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User and Post Information</title>
</head>
<body>
    <h1>User and Post Information</h1>
    <div id="userInfo">
        <p><strong>User ID:</strong> <span id="userId"> Loading...</span></p>
        <p><strong>Post ID:</strong> <span id="postId">Loading...</span></p>
        <p><strong>Status:</strong> <span id="status">Loading...</span></p>
    </div>

</body>
</html>

