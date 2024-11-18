<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection settings
$servername = "localhost"; // Adjust as needed
$username = "root"; // Adjust as needed
$password = ""; // Adjust as needed
$dbname = "hardware"; // Ensure the database name is correct

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
    $email = $_POST["email"];
    $plain_password = $_POST["password"]; // Get the plain password
    $gender = $_POST["gender"];
    $age = $_POST["age"];

    // Generate a unique salt for this user (e.g., a random string)
    $salt = bin2hex(random_bytes(16)); // 16 bytes => 32 characters in hex

    // Combine the password and the salt
    $password_with_salt = $plain_password . $salt; // Concatenate password and salt

    // Hash the combined password + salt
    $hashed_password = password_hash($password_with_salt, PASSWORD_DEFAULT); // Secure password hashing

    // Check if the username or email already exists
    $check_sql = "SELECT * FROM users WHERE username = ? OR email = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ss", $username, $email);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // Username or email already exists
        $existing_user = $check_result->fetch_assoc();
        if ($existing_user['username'] == $username) {
            echo "<script>alert('Username already exists. Please choose a different username.'); window.location.href='../frontend/register.html';</script>";
        } elseif ($existing_user['email'] == $email) {
            echo "<script>alert('Email already exists. Please use a different email.'); window.location.href='../frontend/register.html';</script>";
        }
    } else {
        // Prepare SQL query to insert the new user
        $sql = "INSERT INTO users (full_name, username, password, gender, age, email, salt) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $full_name, $username, $hashed_password, $gender, $age, $email, $salt);

        if ($stmt->execute()) {
            echo "<script>alert('Registration successful! You can now login.'); window.location.href='../frontend/login.html';</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }

        // Close statement
        $stmt->close();
    }

    // Close check statement and connection
    $check_stmt->close();
}

// Close connection
$conn->close();
?>
