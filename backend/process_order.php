<?php
// Database connection
$servername = "localhost"; // Server name
$username = "root"; // Database username
$password = ""; // Database password
$dbname = "hardware"; // Database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]));
}

// Get the order details from the request
$orderDetails = json_decode(file_get_contents('php://input'), true);

// Prepare the SQL statement
$stmt = $conn->prepare("INSERT INTO orders (product_id, product_name, quantity, total_price, payment_method) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("isids", $product_id, $product_name, $quantity, $total_price, $payment_method);

// Loop through each order detail and execute the insert
foreach ($orderDetails as $order) {
    $product_id = $order['product_id'];
    $product_name = $order['product_name'];
    $quantity = $order['quantity'];
    $total_price = $order['total_price'];
    $payment_method = $order['payment_method'];

    if (!$stmt->execute()) {
        echo json_encode(['success' => false, 'message' => 'Failed to insert order: ' . $stmt->error]);
        exit();
    }
}

// Close statement and connection
$stmt->close();
$conn->close();

// Return success response
echo json_encode(['success' => true]);
?>
