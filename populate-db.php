<?php

// Database connection parameters
$username = 'jlz8fv';
$password = 'Spring2024';
$host = 'mysql01.cs.virginia.edu';
$dbname = 'jlz8fv';
$dsn = "mysql:host=$host;dbname=$dbname";
////////////////////////////////////////////

/** connect to the database **/
try {
    //  $db = new PDO("mysql:host=$hostname;dbname=db-demo", $username, $password);
    $conn = new PDO($dsn, $username, $password);

    // dispaly a message to let us know that we are connected to the database 

    echo "<p>You are connected to the database -- host=$host</p>";
} catch (PDOException $e)     // handle a PDO exception (errors thrown by the PDO library)
{
    // Call a method from any object, use the object's name followed by -> and then method's name
    // All exception objects provide a getMessage() method that returns the error message 
    $error_message = $e->getMessage();
    echo "<p>An error occurred while connecting to the database: $error_message </p>";
} catch (Exception $e)       // handle any type of exception
{
    $error_message = $e->getMessage();
    echo "<p>Error message: $error_message </p>";
}

// Path to the JSON file
$jsonFile = 'data/internships.json';

// Read the JSON file
$jsonData = file_get_contents($jsonFile);

// Check if the JSON data was successfully retrieved
if ($jsonData === false) {
    die ("Error: Unable to read the JSON file.");
}

// Decode JSON data
$data = json_decode($jsonData, true);

// Check if JSON decoding was successful
if ($data === null) {
    die ("Error: Unable to parse JSON data.");
}

// Function to insert data into Company table
// Function to insert data into Company table
function insertCompany($conn, $company_name)
{
    $company_type = 'n/a'; // Company type is not available in JSON
    $sql = "INSERT INTO Company (company_name, company_type) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $company_name, $company_type);
    if ($stmt->execute() === TRUE) {
        return $conn->lastInsertId(); // Return the auto-generated company ID
    } else {
        echo "Error: " . $stmt->error;
        return null;
    }
}

// Function to insert data into Job_Posting table
function insertJobPosting($conn, $location, $app_link, $role, $company_id, $date_posted)
{
    $sql = "INSERT INTO Job_Posting (location, app_link, role, company_id, date_posted) VALUES ('$location', '$app_link', '$role', $company_id, '$date_posted')";
    if ($conn->query($sql) === FALSE) {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Loop through each JSON object and insert data into the database
foreach ($data as $posting) {
    // Insert data into the Company table and get the auto-generated company ID
    $company_id = insertCompany($conn, $posting['company_name']);

    // Insert data into the Job_Posting table
    insertJobPosting($conn, $posting['locations'][0], $posting['url'], $posting['title'], $company_id, date('Y-m-d', $posting['date_posted']));

    // For simplicity, we're not inserting data into the Internship_Posting and New_Grad_Posting tables as their attributes are not available in the JSON
}

?>