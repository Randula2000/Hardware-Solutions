



    <?php
// Database connection
$servername = "localhost"; // Server name
$username = "root"; // Database username
$password = ""; // Database password
$dbname = "hardware"; // Database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all products from the database
$sql = "SELECT id, name, quantity, price, description, product_type, image_url FROM products";
$result = $conn->query($sql);

$products = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $products[] = $row; // Store each product in the products array
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Catalog</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        /* General Styles */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            display: flex;
            flex-direction: row;
        }
         .sidebar {
            width: 250px;
            background-color: #003366;
            padding-top: 100px;
            position: fixed;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-shadow: 2px 0 5px rgba(0,0,0,0.2);
        }

        .sidebar img {
            width: 120px;
            height: auto;
            margin-bottom: 20px;
            border-radius: 50%;
            border: 3px solid #fff;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            font-size: 16px;
            width: 90%;
            text-align: center;
            padding: 12px;
            margin: 5px 0;
            background-color: #00509e;
            border-radius: 5px;
            transition: background 0.3s;
            font-weight: bold;
        }

        .sidebar a:hover {
            background-color: #003366;
        }
        .container {
            width: 70%;
            margin-left: 270px; /* Adjusted for sidebar */
            padding: 20px;
            max-width: 1200px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        .product {
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 15px;
            margin: 10px;
            text-align: center;
            width: 220px;
            cursor: pointer;
            background-color: #fff;
            transition: box-shadow 0.3s;
            display: inline-block;
        }
        .product:hover {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .product img {
            width: 100px;
            height: auto;
            border-radius: 8px;
        }
        .button-group {
            text-align: center;
            margin-bottom: 20px;
        }
        .button-group button {
            padding: 10px 15px;
            margin: 5px;
            cursor: pointer;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .button-group button:hover {
            background-color: #0056b3;
        }
        #cart {
            width: 300px;
            margin-left: 20px;
            padding: 20px;
            background-color: #f1f1f1;
            border: 1px solid #ccc;
            border-radius: 10px;
            position: sticky;
            top: 20px;
        }
        #totalPrice {
            font-weight: bold;
            margin-top: 15px;
            font-size: 1.2em;
        }
        .payment-method {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
        }
        .checkout {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .checkout:hover {
            background-color: #218838;
        }
        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
        }
        .delete-button {
            background-color: transparent;
            color: #dc3545;
            border: none;
            cursor: pointer;
            font-size: 20px;
            margin-left: 10px;
        }
        .delete-button:hover {
            color: #c82333;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <img src="https://cbx-prod.b-cdn.net/COLOURBOX54037532.jpg?width=800&height=800&quality=70" alt="Icon">
        <a href="../frontend/dashboard.html">Dashboard</a>
        <a href="../frontend/products.html">Products</a>
        <a href="../backend/cart.php">Cart</a>
        <a href="../frontend/contacts.html">Contacts</a>
        <a href="../frontend/logout.html">Log Out</a>
    </div>

    <div class="container">
         <div class="button-group">
            <button onclick="filterProducts('Hand Tools')">Hand Tools</button>
            <button onclick="filterProducts('Power Tools')">Power Tools</button>
            <button onclick="filterProducts('Measuring Tools')">Measuring Tools</button>
            <button onclick="filterProducts('Cutting and Shaping Tools')">Cutting and Shaping Tools</button>
            <button onclick="filterProducts('Assembly Tools')">Assembly Tools</button>
            <button onclick="filterProducts('Specialized Tools')">Specialized Tools</button>
            <button onclick="filterProducts('Safety Equipment')">Safety Equipment</button>
            <button onclick="filterProducts('Tool Storage')">Tool Storage</button>
        </div>

        <div id="productList" class="product-list"></div>
    </div>

    <div id="cart">
        <h2>Shopping Cart</h2>
        <div id="cartItems"></div>
        <div id="totalPrice"></div>
        <div class="payment-method">
            <label for="paymentMethod">Choose a payment method:</label>
            <select id="paymentMethod">
                <option value="creditCard">Credit Card</option>
                <option value="paypal">PayPal</option>
                <option value="bankTransfer">Bank Transfer</option>
            </select>
        </div>
        <button class="checkout" onclick="checkout()">Proceed to Checkout</button>
    </div>
 <script>
        const products = <?php echo json_encode($products); ?>;
        let cart = JSON.parse(localStorage.getItem('cart')) || [];

        // Load products on page load
        displayProducts(products);

        function filterProducts(productType) {
            const filteredProducts = products.filter(product => product.product_type === productType);
            displayProducts(filteredProducts);
        }

        function displayProducts(filteredProducts) {
            const productList = document.getElementById('productList');
            productList.innerHTML = '';

            if (filteredProducts.length > 0) {
                filteredProducts.forEach(product => {
                    const productDiv = document.createElement('div');
                    productDiv.classList.add('product');
                    productDiv.innerHTML = `
                        <img src="${product.image_url}" alt="${product.name}">
                        <h3>${product.name}</h3>
                        <p>Price: $${product.price}</p>
                        <button onclick="addToCart(${product.id}, '${product.name}', ${product.price})">Add to Cart</button>
                    `;
                    productList.appendChild(productDiv);
                });
            } else {
                productList.innerHTML = '<p>No products available for this type.</p>';
            }
            updateCartDisplay();
        }

        function addToCart(id, name, price) {
            const existingProductIndex = cart.findIndex(item => item.id === id);
            if (existingProductIndex > -1) {
                cart[existingProductIndex].quantity += 1;
            } else {
                cart.push({ id, name, price, quantity: 1 });
            }
            localStorage.setItem('cart', JSON.stringify(cart));
            updateCartDisplay();
        }

        function updateCartDisplay() {
            const cartItems = document.getElementById('cartItems');
            const totalPrice = document.getElementById('totalPrice');
            cartItems.innerHTML = '';
            let total = 0;

            if (cart.length === 0) {
                cartItems.innerHTML = '<p>Your cart is empty.</p>';
                totalPrice.innerHTML = '';
            } else {
                cart.forEach(item => {
                    const cartItemDiv = document.createElement('div');
                    const itemTotal = item.price * item.quantity;
                    total += itemTotal;
                    cartItemDiv.classList.add('cart-item');
                    cartItemDiv.innerHTML = `
                        <span>${item.name} - $${item.price} x ${item.quantity} = $${itemTotal.toFixed(2)}</span>
                        <button class="delete-button" onclick="removeFromCart(${item.id})"><i class="fas fa-times"></i></button>
                    `;
                    cartItems.appendChild(cartItemDiv);
                });
                totalPrice.innerHTML = `Total: $${total.toFixed(2)}`;
            }
        }

        function removeFromCart(id) {
            cart = cart.filter(item => item.id !== id); // Remove item from cart
            localStorage.setItem('cart', JSON.stringify(cart)); // Update local storage
            updateCartDisplay(); // Refresh cart display
        }

         function checkout() {
    if (cart.length === 0) {
        alert('Your cart is empty. Please add items to the cart before checking out.');
        return;
    }

    const paymentMethod = document.getElementById('paymentMethod').value;
    const orderDetails = cart.map(item => ({
        product_id: item.id,
        product_name: item.name,
        quantity: item.quantity,
        total_price: item.price * item.quantity,
        payment_method: paymentMethod
    }));

    fetch('process_order.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(orderDetails),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Order placed successfully!');
            localStorage.removeItem('cart'); // Clear cart
            cart = []; // Clear cart variable
            updateCartDisplay(); // Refresh cart display
        } else {
            alert('Failed to place order: ' + data.message);
        }
    })
    .catch((error) => {
        console.error('Error:', error);
    });
}

    </script>
    
</body>
</html>
