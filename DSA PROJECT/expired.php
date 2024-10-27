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

// Get the current date
$currentDate = date('Y-m-d');

// Fetch expired items from the stock table
$sql_fetch = "SELECT * FROM stock WHERE ExpirationDate < '$currentDate'";
$result_fetch = $conn->query($sql_fetch);

if ($result_fetch->num_rows > 0) {
    while ($row = $result_fetch->fetch_assoc()) {
        // Prepare the SQL statement to insert into the orders table
        $category = $conn->real_escape_string($row['Category']);
        $name = $conn->real_escape_string($row['Name']);
        $stock = $row['Stock']; // Assuming this is the quantity to move
        $price = $row['Price'];

        // Insert the record into the orders table with the current date
        $sql_insert = "INSERT INTO orders (Category, Name, Quantity, Price, OrderDate, ItemStatus) 
                       VALUES ('$category', '$name', '$stock', '$price', '$currentDate', 'Expired')";
        
        if ($conn->query($sql_insert) === TRUE) {
            // Now delete the record from the stock table
            $sql_delete = "DELETE FROM stock WHERE ID = " . intval($row['ID']);
            $conn->query($sql_delete); // Deleting expired items
        } else {
            echo "Error inserting record into orders: " . $conn->error;
        }
    }

    header("Location: search.php?message=Expired records moved to orders successfully");
    exit;
} else {
    header("Location: search.php?message=No expired records found");
    exit;
}

$conn->close();
?>