<?php
require ("connect-db.php");
?>

<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset ($_POST['dropTables'])) {
        dropAllTables();
        echo "<p>Tables dropped successfully.</p>";
    } elseif (isset ($_POST['createTables'])) {
        createTables();
        echo "<p>Tables created successfully.</p>";
    } elseif (isset ($_POST['emptyTables'])) {
        emptyTables();
        echo "<p>Tables emptied successfully.</p>";
    }
}


function dropAllTables()
{
    global $conn;
    try {
        // Temporarily disable foreign key checks
        $conn->exec("SET FOREIGN_KEY_CHECKS=0;");
        $query =
            "USE jlz8fv;
        DROP TABLE IF EXISTS Company, Job_Posting, Internship_Posting, 
                                New_Grad_Posting, Users, Application, 
                                Skills, User_Skill, Account, Anonymous_Feedback;";
        $conn->exec($query);
        // Re-enable foreign key checks
        $conn->exec("SET FOREIGN_KEY_CHECKS=1;");
    } catch (PDOException $e) {
        echo "<p>Error dropping tables: " . $e->getMessage() . "</p>";
    }
}

function emptyTables()
{
    global $conn;
    try {
        // Begin transaction
        $conn->beginTransaction();
        $conn->exec("SET FOREIGN_KEY_CHECKS=0;");
        $tablesToEmpty = [
            'Company',
            'Job_Posting',
            'Internship_Posting',
            'New_Grad_Posting',
            'Users',
            'Application',
            'Skills',
            'User_Skill',
            'Account',
            'Anonymous_Feedback'
        ];

        foreach ($tablesToEmpty as $table) {
            $conn->exec("DELETE FROM `$table`");
        }

        // Commit transaction
        $conn->commit();
        $conn->exec("SET FOREIGN_KEY_CHECKS=1;");
    } catch (PDOException $e) {
        // Rollback transaction in case of error
        $conn->rollback();
        echo "<p>Error emptying tables: " . $e->getMessage() . "</p>";
    }
}

function createTables()
{
    global $conn;
    $query = "USE jlz8fv;
SET GLOBAL innodb_strict_mode = ON;

    CREATE TABLE Company (
            id INT AUTO_INCREMENT PRIMARY KEY,
            company_name VARCHAR(255),
            company_type VARCHAR(255)
    );   
    CREATE TABLE Job_Posting (
        id INT AUTO_INCREMENT PRIMARY KEY,
        location VARCHAR(255),
        app_link VARCHAR(1012),
        role VARCHAR(255),
        company_id INT,
        date_posted DATE,
        role_type VARCHAR(255),
        FOREIGN KEY (company_id) REFERENCES Company(id)
    );
    
    CREATE TABLE Internship_Posting (
        post_id INT PRIMARY KEY,
        sponsorship_status VARCHAR(255),
        FOREIGN KEY (post_id) REFERENCES Job_Posting(id)
    );
    
    CREATE TABLE New_Grad_Posting (
        post_id INT AUTO_INCREMENT PRIMARY KEY,
        job_type VARCHAR(255),
        FOREIGN KEY (post_id) REFERENCES Job_Posting(id)
    );
        CREATE TABLE Account (
        email VARCHAR(255),
        id INT AUTO_INCREMENT PRIMARY KEY,
        password VARCHAR(255)
    );
        CREATE TABLE Users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255),
        DOB DATE,
	CONSTRAINT legal_age
		  CHECK (DOB<'2006-01-01'),
        school VARCHAR(255),
        user_id INT,
        FOREIGN KEY (user_id) REFERENCES Account(id)
    );
    CREATE TABLE Application (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        post_id INT,
        status VARCHAR(50),
        FOREIGN KEY (user_id) REFERENCES Account(id),
        FOREIGN KEY (post_id) REFERENCES Job_Posting(id)
    );
    
    CREATE TABLE Skills (
        id INT AUTO_INCREMENT PRIMARY KEY,
        description VARCHAR(255)
    );
    
    CREATE TABLE User_Skill (
        user_id INT,
        skill_id INT,
        PRIMARY KEY (user_id, skill_id),
        FOREIGN KEY (user_id) REFERENCES Account(id),
        FOREIGN KEY (skill_id) REFERENCES Skills(id)
    );
    
    
    CREATE TABLE Anonymous_Feedback (
        Id INT AUTO_INCREMENT PRIMARY KEY,
        description TEXT
    );

    DELIMITER $$

CREATE PROCEDURE SelectAllJobs (IN parameter_location VARCHAR(255))
    MODIFIES SQL DATA
BEGIN
    SELECT * FROM Job_Posting WHERE `location` = parameter_location;
END $$

DELIMITER ;

    
    ";
    $statement = $conn->prepare($query);
    $statement->execute();
}

?>

<h3>Select an option</h3>
<form method="post">
    <input type="submit" name="dropTables" value="Drop Tables"
        onclick="return confirm('Are you sure you want to drop all tables?');" />
    <input type="submit" name="emptyTables" value="Empty Tables"
        onclick="return confirm('Are you sure you want to empty all tables?');" />
    <input type="submit" name="createTables" value="Create Tables" />
</form>