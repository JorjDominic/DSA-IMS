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

// Check if the product name and quantity are passed in the query string
if (isset($_GET['name']) && isset($_GET['quantity'])) {
    $product_name = $conn->real_escape_string($_GET['name']); // Escape input for security
    $ordered_quantity = intval($_GET['quantity']); // Get the quantity from user input

    if ($ordered_quantity <= 0) {
        echo "<script>alert('Invalid quantity!'); window.location.href='order.php';</script>";
        exit;
    }

    // Fetch the products with the same name, ordered by expiration date using FIFO
    $sql = "SELECT * FROM stock WHERE name = '$product_name' ORDER BY expirationdate ASC";
    $result = $conn->query($sql);

    function moveExpiredProducts($conn) {
        $current_date = date('Y-m-d');
        $sql_expired = "SELECT * FROM stock WHERE expirationdate < '$current_date'";
        $expired_result = $conn->query($sql_expired);
    
        if ($expired_result->num_rows > 0) {
            while ($row = $expired_result->fetch_assoc()) {
                $product_name = $row['Name'];
                $category = $row['Category'];
                $stock = $row['Stock'];
                $price = $row['Price'];
                $order_date = date('Y-m-d H:i:s');
    
                // Insert expired product into orders table
                $sql_insert_order = "INSERT INTO orders (name, category, quantity, price, OrderDate, ItemStatus) 
                                     VALUES ('$product_name', '$category', $stock, $price, '$order_date', 'Expired')";
                $conn->query($sql_insert_order);
    
                // Remove the expired product from stock
                $sql_delete_stock = "DELETE FROM stock WHERE ID = " . $row['ID'];
                $conn->query($sql_delete_stock);
            }
        }
    }
    
    // Move expired products to orders
    moveExpiredProducts($conn);

    // Continue processing the regular order
    if ($result->num_rows > 0) {
        $total_available_stock = 0;
        $ordered_stock = $ordered_quantity;

        // Loop through products to accumulate stock
        while ($row = $result->fetch_assoc()) {
            $product_id = $row['ID'];
            $stock = $row['Stock'];
            $category = $row['Category'];
            $price = $row['Price'];

            $total_available_stock += $stock;

            // Check if we have enough stock for the order
            if ($ordered_stock <= $stock) {
                // Update the stock in the stock table (reduce the quantity)
                $new_stock = $stock - $ordered_stock;

                if ($new_stock == 0) {
                    // If no more stock left, remove the product from the stock table
                    $sql_update_stock = "DELETE FROM stock WHERE ID = $product_id";
                } else {
                    // If there's remaining stock, update the quantity
                    $sql_update_stock = "UPDATE stock SET Stock = $new_stock WHERE ID = $product_id";
                }
                $conn->query($sql_update_stock);

                // Insert the ordered product into the orders table
                $order_date = date('Y-m-d H:i:s');
                $sql_insert_order = "INSERT INTO orders (name, category, quantity, price, OrderDate, ItemStatus) 
                                     VALUES ('$product_name', '$category', $ordered_quantity, $price, '$order_date', 'Sold')";
                $conn->query($sql_insert_order);

                echo "<script>alert('Order successfully placed for $ordered_quantity items!'); window.location.href='order.php';</script>";
                exit;
            } else {
                // If ordered stock exceeds this product's stock, reduce ordered_stock
                $ordered_stock -= $stock;

                // Remove the product from stock table if it's all sold
                $sql_update_stock = "DELETE FROM stock WHERE ID = $product_id";
                $conn->query($sql_update_stock);
            }
        }

        // If we exit the loop and still have ordered stock left
        if ($ordered_stock > 0) {
            echo "<script>alert('Not enough stock available! Only $total_available_stock items left.'); window.location.href='order.php';</script>";
        }
    } else {
        echo "<script>alert('Product not found!'); window.location.href='order.php';</script>";
    }
} else {
    echo "<script>alert('No product selected or quantity not provided!'); window.location.href='order.php';</script>";
}
?>