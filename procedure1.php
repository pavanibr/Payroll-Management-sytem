<?php
// Database configuration
$host = "localhost";
$db_name = "payroll";
$username = "root";
$password = "pavani19";

try {
    // Create a new PDO instance
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);

    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Call the stored procedure
    $stmt = $conn->prepare("CALL all_emp3()");

    // Execute the query
    $stmt->execute();

    // Fetch all results
    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Iterate through each employee and print their details
    foreach ($employees as $employee) {
        echo "First Name: " . $employee['firstname'] . "<br>";
        // echo "Middle Name: " . $employee['middlename'] . "<br>";
        echo "Last Name: " . $employee['lastname'] . "<br><br>";
    }

} catch(PDOException $e) {
    // Handle any errors
    echo "Error: " . $e->getMessage();
}

// Close the database connection
$conn = null;
?>
