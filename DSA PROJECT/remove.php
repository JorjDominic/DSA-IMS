<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("location: login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "app");
if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

// Check if the ID is set and valid
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    // Fetch the record from the stock table
    $sql_fetch = "SELECT * FROM stock WHERE ID = $id";
    $result_fetch = $conn->query($sql_fetch);

    if ($result_fetch->num_rows > 0) {
        $row = $result_fetch->fetch_assoc();

        // Prepare the SQL statement to insert into the orders table
        $category = $conn->real_escape_string($row['Category']);
        $name = $conn->real_escape_string($row['Name']);
        $stock = $row['Stock']; // Assuming this is the quantity to move
        $price = $row['Price'];
       

        // Get the current date for the OrderDate
        $currentDate = date('Y-m-d');

        // Insert the record into the orders table with the current date
        $sql_insert = "INSERT INTO orders (Category, Name, Quantity, Price, OrderDate, ItemStatus) 
                       VALUES ('$category', '$name', '$stock', '$price', '$currentDate', 'Removed')";
        
        if ($conn->query($sql_insert) === TRUE) {
            // Now delete the record from the stock table
            $sql_delete = "DELETE FROM stock WHERE ID = $id";
            if ($conn->query($sql_delete) === TRUE) {
                // Successfully removed and moved to orders
                header("Location: search.php?message=Record moved to orders successfully");
                exit;
            } else {
                echo "Error deleting record from stock: " . $conn->error;
            }
        } else {
            echo "Error inserting record into orders: " . $conn->error;
        }
    } else {
        echo "No record found with ID: $id";
    }
} else {
    echo "Invalid ID.";
}
$conn->close();
?>