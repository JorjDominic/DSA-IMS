<?php

session_start();
if (!isset($_SESSION['username'])) {
    header("location: login.php");
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "app";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

// Initialize an array to hold grouped products by name
$products = [];

// Fetch all products, grouping them by name and getting the one with the nearest expiration date

$sql = "SELECT name, category, MIN(expirationdate) as nearest_expiration, SUM(stock) as total_stock, price 
        FROM stock 
        GROUP BY name, category, price 
        ORDER BY name ASC";
        
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

// Handle searching
$search_results = [];
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search_name'])) {
    $product_name = $_POST['search_name'];

    // SQL query to search for products by name, grouping by name and category
    $sql_search = "SELECT name, category, MIN(expirationdate) as nearest_expiration, SUM(stock) as total_stock, price 
                   FROM stock 
                   WHERE name LIKE '%$product_name%'
                   GROUP BY name, category, price 
                   ORDER BY nearest_expiration ASC";
    $search_result = $conn->query($sql_search);

    if ($search_result->num_rows > 0) {
        while ($row = $search_result->fetch_assoc()) {
            $search_results[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="order.css">

    <title>Order Products</title>

    <script>
        function orderProduct(productName, inputFieldId) {
            var quantity = document.getElementById(inputFieldId).value;
            if (quantity <= 0 || isNaN(quantity)) {
                alert("Please enter a valid quantity.");
                return;
            }
            if (confirm("Are you sure you want to order this product? Quantity: " + quantity)) {
                // Redirect to order page using FIFO for the selected product by name and quantity
                window.location.href = "fifo.php?name=" + encodeURIComponent(productName) + "&quantity=" + quantity;
            }
        }
    </script>
</head>
<body>
<?php include 'sidebar.php';?>
<div class="container1">
    <div class="container">
        <h2>Order Products</h2>
        
        <form method="POST" action="">
            <br>
            <label class = "l1">Search by Product Name:</label>
            <input type="text" name="search_name">
            <input type="submit" value="Search">
        </form>

        <?php
        // If there are search results, display them. Otherwise, display all products.
        if (!empty($search_results)) { ?>
            <h3>Search Results</h3>
            <div class = "tablewrap">
            <table>
                <tr>
                    <th>Category</th>
                    <th>Product Name</th>
                    <th>Total Stock</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Order</th>
                </tr>
                <?php foreach ($search_results as $index => $product) { ?>
                    <tr>
                        <td><?php echo $product['category']; ?></td>
                        <td><?php echo $product['name']; ?></td>
                        <td><?php echo $product['total_stock']; ?></td>
                        <td><?php echo $product['price']; ?></td>
                        <td>
                            <input type="number" id="quantity_<?php echo $index; ?>" class="quantity1" min="1" max="<?php echo $product['total_stock']; ?>" value="1">
                        </td>
                        <td>
                            <button type="button" onclick="orderProduct('<?php echo $product['name']; ?>', 'quantity_<?php echo $index; ?>')">Order</button>
                        </td>
                    </tr>
                <?php } ?>
            </table>
            </div>
        <?php } else { ?>
            <h3>All Available Products</h3>
            <div class = "tablewrap">
            <table>
                <tr>
                    <th>Category</th>
                    <th>Product Name</th>
                    <th>Total Stock</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Order</th>
                </tr>
                <?php foreach ($products as $index => $product) { ?>
                    <tr>
                        <td><?php echo $product['category']; ?></td>
                        <td><?php echo $product['name']; ?></td>
                        <td><?php echo $product['total_stock']; ?></td>
                        <td><?php echo $product['price']; ?></td>
                        <td>
                            <input type="number" id="quantity_<?php echo $index; ?>" class="quantity-input" min="1" max="<?php echo $product['total_stock']; ?>" value="1">
                        </td>
                        <td>
                            <button type="button" onclick="orderProduct('<?php echo $product['name']; ?>', 'quantity_<?php echo $index; ?>')">Order</button>
                        </td>
                    </tr>
                <?php } ?>
            </table>
            </div>
        <?php } ?>
    </div>
</div>
</body>
</html>