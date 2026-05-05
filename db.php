<?php
// Database Configuration
$servername = getenv('DB_HOST') ?: 'db';
$database = getenv('DB_NAME') ?: 'student_tasks';
$password = getenv('DB_PASS') ?: 'buslot';
$username = getenv('DB_USER') ?: 'heydz';


// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    error_log("Database connection error: " . $conn->connect_error);
    die("Database connection failed. Please try again later.");
}

// Set charset to utf8
$conn->set_charset("utf8");
