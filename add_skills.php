<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<?php
require ("connect-db.php");  
?>

<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $skillDescription = $_POST['skill_description'];
    $userId = $_SESSION['user_id']; // Get user_id from session

    // Check if the skill already exists
    $stmt = $conn->prepare("SELECT id FROM Skills WHERE description = :description");
    $stmt->execute(['description' => $skillDescription]);
    if ($stmt->rowCount() > 0) {
        $skill = $stmt->fetch();
        $skillId = $skill['id'];
    } else {
        // Insert the new skill into Skills table
        $stmt = $conn->prepare("INSERT INTO Skills (description) VALUES (:description)");
        $stmt->execute(['description' => $skillDescription]);
        $skillId = $conn->lastInsertId(); // Get the ID of the newly inserted skill
    }

    // Link the user to the skill in User_Skill table
    $stmt = $conn->prepare("INSERT IGNORE INTO User_Skill (user_id, skill_id) VALUES (:user_id, :skill_id)");
    if ($stmt->execute(['user_id' => $userId, 'skill_id' => $skillId])) {
        echo "<p>Skill added successfully.</p>";
    } else {
        echo "<p>Error adding skill.</p>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Skills</title>
</head>
<body>
    <h2>Add Skill</h2>
    <form action="add_skills.php" method="post">
        Skill Description: <input type="text" name="skill_description" required><br>
        <input type="submit" value="Add Skill">
    </form>
</body>
</html>
