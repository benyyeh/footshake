<?php
require ("connect-db.php");  
?>

<?php

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
    if ($stmt->execute([$company_name, $company_type])) {
        return $conn->lastInsertId(); // Return the auto-generated company ID
    } else {
        echo "Error: " . $stmt->errorInfo()[2];
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

$count = 0;
foreach ($data as $posting) {
    // Insert data into the Company table and get the auto-generated company ID
    // echo"insert company success";
    // Insert data into the Job_Posting table
    if ($posting['active'] == TRUE){
        $company_id = insertCompany($conn, $posting['company_name']);
        insertJobPosting($conn, $posting['locations'][0], $posting['url'], $posting['title'], $company_id, date('Y-m-d', $posting['date_posted']));
        $count++;
        echo"<p>Added entry. Entries: $count</p>";
        flush();
    }
    // echo"insert job posting sucess";
    // For simplicity, we're not inserting data into the Internship_Posting and New_Grad_Posting tables as their attributes are not available in the JSON
}

echo "<p>All data has been populated successfully.</p>";


?>