<?php
// Read database details from environment variables
$db_host = getenv('RDS_HOSTNAME');
$db_username = getenv('RDS_USERNAME');
$db_password = getenv('RDS_PASSWORD');
$db_name = getenv('RDS_DB_NAME');

// Check if all required environment variables are set
if (!$db_host || !$db_username || !$db_password || !$db_name) {
    die("Error: Please set all required environment variables.");
}

// Establish a connection to the MySQL server
$connection = mysqli_connect($db_host, $db_username, $db_password);

// Check connection
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create the database if it does not exist
$create_database_query = "CREATE DATABASE IF NOT EXISTS $db_name";
if (!mysqli_query($connection, $create_database_query)) {
    die("Error creating database: " . mysqli_error($connection));
}

// Select the database
mysqli_select_db($connection, $db_name);

// Create the table if it does not exist
$create_table_query = "
CREATE TABLE IF NOT EXISTS employees (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    emp_id VARCHAR(30) NOT NULL,
    name VARCHAR(100) NOT NULL,
    dob DATE NOT NULL,
    department VARCHAR(100) NOT NULL
)";
if (!mysqli_query($connection, $create_table_query)) {
    die("Error creating table: " . mysqli_error($connection));
}

// Function to sanitize input data
function sanitize($data) {
    global $connection;
    return mysqli_real_escape_string($connection, $data);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    $emp_id = sanitize($_POST["emp_id"]);
    $name = sanitize($_POST["name"]);
    $dob = sanitize($_POST["dob"]);
    $department = sanitize($_POST["department"]);

    // Insert data into database
    $insert_query = "INSERT INTO employees (emp_id, name, dob, department) VALUES ('$emp_id', '$name', '$dob', '$department')";
    if (mysqli_query($connection, $insert_query)) {
        echo "Data uploaded successfully.";
    } else {
        echo "Error: " . $insert_query . "<br>" . mysqli_error($connection);
    }
}

// Close connection
mysqli_close($connection);
?>
