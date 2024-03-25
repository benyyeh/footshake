<?php
require ("connect-db.php");  
?>

<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
// Path to the JSON file
$jsonInternships = 'data/internships.json';

// Read the JSON file
$internshipJsonData = file_get_contents($jsonInternships);

// Check if the JSON data was successfully retrieved
if ($internshipJsonData === false) {
    die ("Error: Unable to read the JSON file.");
}

// Decode JSON data
$internshipData = json_decode($internshipJsonData, true);

// Check if JSON decoding was successful
if ($internshipData === null) {
    die ("Error: Unable to parse JSON data.");
}

// NEW GRAD
// Path to the JSON file
$jsonNewGrad = 'data/new_grad.json';

// Read the JSON file
$newGradJsonData = file_get_contents($jsonNewGrad);

// Check if the JSON data was successfully retrieved
if ($newGradJsonData === false) {
    die ("Error: Unable to read the JSON file.");
}

// Decode JSON data
$newGradData = json_decode($newGradJsonData, true);

// Check if JSON decoding was successful
if ($newGradData === null) {
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
// function insertJobPosting($conn, $location, $app_link, $role, $company_id, $date_posted)
// {
//     $sql = "INSERT INTO Job_Posting (location, app_link, role, company_id, date_posted) VALUES ('$location', '$app_link', '$role', $company_id, '$date_posted')";
//     if ($conn->query($sql) === FALSE) {
//         echo "Error: " . $sql . "<br>" . $conn->error;
//     }
// }
function insertJobPosting($conn, $location, $app_link, $role, $company_id, $date_posted, $role_type)
{
    try {
        // Prepare the SQL query
        $sql = "INSERT INTO Job_Posting (location, app_link, role, company_id, date_posted, role_type) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        // Bind parameters
        $stmt->bindParam(1, $location);
        $stmt->bindParam(2, $app_link);
        $stmt->bindParam(3, $role);
        $stmt->bindParam(4, $company_id);
        $stmt->bindParam(5, $date_posted);
        $stmt->bindParam(6, $role_type);
        
        // Execute the prepared statement
        if ($stmt->execute()) {
            // Get the last inserted ID
            $post_id = $conn->lastInsertId();
            // Return the post_id
            return $post_id;
        } else {
            // If there is an error, output the error message
            echo "Error: " . $stmt->errorInfo()[2];
            // Return null to indicate failure
            return null;
        }
    } catch (PDOException $e) {
        // If there is an exception, output the error message
        echo "Error: " . $e->getMessage();
        // Return null to indicate failure
        return null;
    }
}

function insertInternshipPosting($conn, $post_id, $sponsorship)
{
    $sql = "INSERT INTO Internship_Posting (post_id, sponsorship_status) VALUES ('$post_id', '$sponsorship')";
    if ($conn->query($sql) === FALSE) {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

function insertNewGradPosting($conn, $post_id, $job_type)
{
    $sql = "INSERT INTO New_Grad_Posting (post_id, job_type) VALUES ('$post_id', '$job_type')";
    if ($conn->query($sql) === FALSE) {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// POPULATE INTERNSHIPS
$icount = 0;
foreach ($internshipData as $posting) {
    if ($posting['active']){
        $company_id = insertCompany($conn, $posting['company_name']);
        $post_id = insertJobPosting($conn, $posting['locations'][0], $posting['url'], $posting['title'], $company_id, date('Y-m-d', $posting['date_posted']), 'I');
        insertInternshipPosting( $conn, $post_id, $posting['sponsorship'] );
        $icount++;
        echo"<p>Added entry. Entries: $icount</p>";
        flush();
    }

}

// POPULATE NEW GRADS
$ngcount = 0;
foreach ($newGradData as $posting) {
    if ($posting['active']){
        $company_id = insertCompany($conn, $posting['company_name']);
        $post_id = insertJobPosting($conn, $posting['locations'][0], $posting['url'], $posting['title'], $company_id, date('Y-m-d', $posting['date_posted']), 'NG');
        $job_type = explode(' ', $posting['title'])[0];
        insertNewGradPosting($conn, $post_id, $job_type);
        $ngcount++;
        echo"<p>Added entry. Entries: $ngcount</p>";
        flush();
    }
}

echo "<p>All data has been populated successfully.</p>";

?>