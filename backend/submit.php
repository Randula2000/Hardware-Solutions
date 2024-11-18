<?php
// Database connection variables
$servername = "localhost";
$username = "root"; // Replace with your actual database username
$password = ""; // Replace with your actual database password
$dbname = "hardware"; // Ensure this matches the database name exactly

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $conn->real_escape_string($_POST["name"]);
    $email = $conn->real_escape_string($_POST["email"]);
    $message = $conn->real_escape_string($_POST["message"]);

    // Insert data into database
    $sql = "INSERT INTO contacts (name, email, message) VALUES ('$name', '$email', '$message')";

    if ($conn->query($sql) === TRUE) {
        // Success message
        echo "<script>alert('Message sent successfully!'); window.history.go(-1);</script>";
    } else {
        // Error message
        echo "<script>alert('Error: " . $conn->error . "'); window.history.go(-1);</script>";
    }
}

// Close the connection
$conn->close();
?>
