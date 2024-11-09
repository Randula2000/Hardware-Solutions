 <?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hardware";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the product type from the AJAX request
$productType = $_POST['product_type'] ?? '';

// Fetch products based on the selected type
$sql = "SELECT id, name, price, image_url FROM products WHERE product_type = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $productType);
$stmt->execute();
$result = $stmt->get_result();

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

// Return products as JSON
echo json_encode($products);

$stmt->close();
$conn->close();
?>
