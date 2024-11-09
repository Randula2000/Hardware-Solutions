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
    $name = $_POST["name"];
    $entered_password = $_POST["password"];

    // Define the salt used during registration
    $salt = "oimoiumoi8768756875";

    // Hash the entered password with the salt using sha1
    $hashed_entered_password = sha1($entered_password . $salt);

    // Prepare the SQL query to select the user
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a user was found
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Compare the hashed entered password with the stored password
        if ($hashed_entered_password === $user['password']) {
            echo "<script>alert('Login successful! Welcome, " . htmlspecialchars($user['full_name']) . "'); window.location.href='../frontend/dashboard.html';</script>";
        } else {
            echo "<script>alert('Invalid password.'); window.location.href='login.html';</script>";
        }
    } else {
        echo "<script>alert('No user found with that username.'); window.location.href='login.html';</script>";
    }

    // Close statement and connection
    $stmt->close();
}

$conn->close();
?>
