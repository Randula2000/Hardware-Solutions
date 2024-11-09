<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>More Products - New Rathna Traders</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
            color: #333;
        }

        .category-heading {
            text-align: center;
            margin: 20px 0;
            font-size: 2.5em;
            color: #005b96;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .products {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .product {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .product:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .product-image {
            width: 100%;
            height: auto;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .see-more-button {
            text-align: center;
            margin-top: 20px;
        }

        .see-more-button button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border: none;
            background-color: #005b96;
            color: white;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .see-more-button button:hover {
            background-color: #004080;
        }
    </style>
</head>
<body>

    <h2 class="category-heading">More Products</h2>
    <div class="products">
        <div class="product">
            <img src=" https://cdn.baurs.com/images/agriculture/innerpages/fert.jpg" alt="Product 3" class="product-image">
            <h3>Product - 3</h3>
            <p>Description: Detailed description of Product 3.</p>
        </div>
        <div class="product">
            <img src="https://ae01.alicdn.com/kf/S0caae0bdab0b46d8b4e09a9caf0d44187/Dethatcher-Rake-High-Quality-Carbon-Steel-Hand-Rake-Tines-Rake-Dethatching-Weeding-Acreage-Rake-Rock-Gravel.jpg" alt="Product 4" class="product-image">
            <h3>Product - 4</h3>
            <p>Description: Detailed description of Product 4.</p>
        </div>
        <!-- Add more products as needed -->
    </div>

    <div class="see-more-button">
        <button onclick="location.href='../frontend/products.html'">Back to Products</button>
    </div>

</body>
</html>
