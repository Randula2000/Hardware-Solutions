<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hardware";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form data has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture form data
    $full_name = $_POST["full_name"];
    $username = $_POST["name"];
    $entered_password = $_POST["password"];
    $salt = "oimoiumoi8768756875"; // Salt for password hashing

    // Hash the password with the salt using sha1
    $hashed_password = sha1($entered_password . $salt);

    $gender = $_POST["gender"];
    $age = $_POST["age"];

    // Check if the username already exists
    $check_sql = "SELECT * FROM users WHERE username = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $username);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // Username already exists
        echo "<script>alert('Username already exists. Please choose a different username.'); window.location.href='../frontend/register.html';</script>";
    } else {
        // Prepare SQL query to insert the user
        $sql = "INSERT INTO users (full_name, username, password, gender, age) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $full_name, $username, $hashed_password, $gender, $age);

        if ($stmt->execute()) {
            echo "<script>alert('Registration successful! You can now login.'); window.location.href='../frontend/login.html';</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }

        // Close statement
        $stmt->close();
    }

    // Close check statement
    $check_stmt->close();
}

// Close connection
$conn->close();
?>
