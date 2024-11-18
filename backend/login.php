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
    $email = $_POST["email"];
    $entered_password = $_POST["password"];

    // Prepare the SQL query to select the user by email
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a user was found
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Retrieve the stored salt for the user
        $stored_salt = $user['salt'];

        // Concatenate the entered password with the stored salt
        $password_with_salt = $entered_password . $stored_salt;

        // Verify the entered password with the stored hashed password
        if (password_verify($password_with_salt, $user['password'])) {
            // Success: Password is correct
            echo "<html><body style='background-color: #e0ffe0; text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100vh;'>
                    <div style='max-width: 400px; padding: 20px; background-color: #ffffff; border-radius: 8px; box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);'>
                        <h2 style='color: #4CAF50; font-size: 24px; font-family: Arial, sans-serif;'>Login Successful!</h2>
                        <p style='color: #333; font-family: Arial, sans-serif;'>Welcome, " . htmlspecialchars($user['full_name']) . "</p>
                        <button onclick='window.location.href=\"../frontend/dashboard.html\"' style='background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 5px; font-size: 16px; cursor: pointer; font-family: Arial, sans-serif;'>Go to Dashboard</button>
                    </div>
                  </body></html>";
        } else {
            // Error: Invalid password
            echo "<html><body style='background-color: #ffe0e0; text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100vh;'>
                    <div style='max-width: 400px; padding: 20px; background-color: #ffffff; border-radius: 8px; box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);'>
                        <h2 style='color: #FF5722; font-size: 24px; font-family: Arial, sans-serif;'>Invalid Password</h2>
                        <p style='color: #333; font-family: Arial, sans-serif;'>Please check your password and try again.</p>
                        <button onclick='window.location.href=\"../frontend/login.html\"' style='background-color: #FF5722; color: white; padding: 10px 20px; border: none; border-radius: 5px; font-size: 16px; cursor: pointer; font-family: Arial, sans-serif;'>Try Again</button>
                    </div>
                  </body></html>";
        }
    } else {
        // Error: No user found with the given email
        echo "<html><body style='background-color: #ffe0e0; text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100vh;'>
                <div style='max-width: 400px; padding: 20px; background-color: #ffffff; border-radius: 8px; box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);'>
                    <h2 style='color: #FF5722; font-size: 24px; font-family: Arial, sans-serif;'>No User Found</h2>
                    <p style='color: #333; font-family: Arial, sans-serif;'>No account is associated with this email.</p>
                    <button onclick='window.location.href=\"../frontend/login.html\"' style='background-color: #FF5722; color: white; padding: 10px 20px; border: none; border-radius: 5px; font-size: 16px; cursor: pointer; font-family: Arial, sans-serif;'>Try Again</button>
                </div>
              </body></html>";
    }

    // Close statement and connection
    $stmt->close();
}

// Close the connection
$conn->close();
?>
