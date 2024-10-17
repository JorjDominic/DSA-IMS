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

// Check if the product name is passed in the query string
if (isset($_GET['name'])) {
    $product_name = $_GET['name'];

    // Fetch the product with the nearest expiration date using FIFO
    $sql = "SELECT * FROM stock 
            WHERE name = '$product_name'
            ORDER BY expirationdate ASC
            LIMIT 1"; // FIFO: Get the product with the nearest expiration date
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $product_id = $row['ID'];
        $category = $row['Category'];
        $stock = $row['Stock'];
        $price = $row['Price'];
        $expiration_date = $row['ExpirationDate'];

        // Simulate ordering a specific quantity (you can adjust this to user input later)
        $ordered_quantity = 1; // For example, assume ordering 1 item

        // Check if stock is enough
        if ($stock >= $ordered_quantity) {
            // Update the stock in the stock table (reduce the quantity)
            $new_stock = $stock - $ordered_quantity;
            if ($new_stock == 0) {
                // If no more stock left, remove the product from stock table
                $sql_update_stock = "DELETE FROM stock WHERE ID = $product_id";
            } else {
                // If there's remaining stock, update the quantity
                $sql_update_stock = "UPDATE stock SET stock = $new_stock WHERE ID = $product_id";
            }
            $conn->query($sql_update_stock);

            // Insert the ordered product into the orders table
            $order_date = date('Y-m-d H:i:s');
            $sql_insert_order = "INSERT INTO orders (name, category, quantity, price, expirationdate, order_date) 
                                 VALUES ('$product_name', '$category', $ordered_quantity, $price, '$expiration_date', '$order_date')";
            $conn->query($sql_insert_order);

            echo "Order successfully placed!";
        } else {
            echo "Not enough stock available!";
        }
    } else {
        echo "Product not found!";
    }
} else {
    echo "No product selected!";
}
?>