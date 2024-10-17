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

// Get today's date
$today = date('Y-m-d');

// Move expired items to product_history table with status 'Expired'
$sql = "INSERT INTO product_history (name, category, expirationdate, quantity, item_status) 
        SELECT name, category, expirationdate, stock, 'Expired' 
        FROM stock 
        WHERE expirationdate < '$today'";
$conn->query($sql);

// Remove expired items from stock table
$sql_delete = "DELETE FROM stock WHERE expirationdate < '$today'";
$conn->query($sql_delete);

// Fetch all items (both sold and expired) from product_history table
$sql_history = "SELECT * FROM product_history ORDER BY order_date DESC, expirationdate DESC";
$history_result = $conn->query($sql_history);

// If ordering a product (FIFO-based)
if (isset($_GET['name'])) {
    $product_name = $_GET['name'];

    // Process the order here (fetch the product with the nearest expiration date)
    $sql_order = "SELECT * FROM stock 
                  WHERE name = '$product_name' 
                  ORDER BY expirationdate ASC LIMIT 1";  // FIFO: earliest expiration date
    $order_result = $conn->query($sql_order);

    if ($order_result->num_rows > 0) {
        $product = $order_result->fetch_assoc();
        $quantity = 1; // Example quantity for order

        // Insert into product_history with status 'Sold'
        $sql_insert = "INSERT INTO product_history (name, category, expirationdate, quantity, price, order_date, item_status) 
                       VALUES ('{$product['name']}', '{$product['category']}', '{$product['expirationdate']}', 
                               '$quantity', '{$product['price']}', NOW(), 'Sold')";
        $conn->query($sql_insert);

        // Update stock (reduce the quantity in stock)
        $new_stock = $product['stock'] - $quantity;
        if ($new_stock > 0) {
            $sql_update_stock = "UPDATE stock SET stock = $new_stock WHERE id = {$product['id']}";
            $conn->query($sql_update_stock);
        } else {
            $sql_delete_stock = "DELETE FROM stock WHERE id = {$product['id']}";
            $conn->query($sql_delete_stock);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product History (Sold and Expired)</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        h2 {
            margin-top: 20px;
        }
        tr:hover {
            background-color: #f5f5f5;
            cursor: pointer;
        }
    </style>
    <script>
        function orderProduct(productName) {
            if (confirm("Are you sure you want to order this product?")) {
                // Redirect to the same page with the product name in the query string
                window.location.href = "?name=" + encodeURIComponent(productName);
            }
        }
    </script>
</head>
<body>
<?php include 'sidebar.php';?>
<h2>Product History (Sold and Expired)</h2>

<!-- Display History Table -->
<table>
    <tr>
        <th>Product Name</th>
        <th>Category</th>
        <th>Quantity</th>
        <th>Price</th>
        <th>Expiration Date</th>
        <th>Order Date</th>
        <th>Status</th> <!-- Shows whether Sold or Expired -->
    </tr>
    <?php if ($history_result->num_rows > 0) {
        while ($row = $history_result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['Name']; ?></td>
                <td><?php echo $row['Category']; ?></td>
                <td><?php echo $row['Quantity']; ?></td>
                <td><?php echo $row['Price']; ?></td>
                <td><?php echo $row['ExpirationDate']; ?></td>
                <td><?php echo $row['OrderDate']; ?></td>
                <td><?php echo $row['ItemStatus']; ?></td> <!-- Sold or Expired -->
            </tr>
    <?php } } else { ?>
        <tr><td colspan="7">No items found in history.</td></tr>
    <?php } ?>
</table>

<!-- Back to Dashboard -->
<a href="dashboard.php">Go Back</a>

</body>
</html>
