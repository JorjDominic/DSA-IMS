<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "app";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

// Initialize variables
$search_results = [];
$record_to_edit = null;

// Handle adding a new record
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_record'])) {
    $category = $_POST['category'];
    $productName = $_POST['name'];
    $qty = $_POST['qty'];
    $price = $_POST['price'];
    $date = $_POST['expdate'];

    $sql = "INSERT INTO stock (category, name, stock, price, expirationdate) VALUES ('$category', '$productName', '$qty','$price','$date')";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Record added Successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Data Management</title>
    <link rel="stylesheet" href="add.css">
</head>
<body class = "add1">

    <div class="table">
   
    <link rel="stylesheet" href="add.css">
        <div class="add-record-container">
            <form method="POST" action="">
                <h3 class = "add">Add Record</h3>
                <label>Category:</label>

                <select name = "category" id = "category">
                    <option value="Animal Feeds">Animal Feeds</option>
                    <option value="Seeds">Seeds</option>
                    <option value="Fertilizer">Fertilizer</option>
                    <option value="Pesticide">Pesticide</option>
                </select>

                <label> Product Name:</label>
                <input type="text" name="name" required>
                <label>Quantity:</label>
                <input type= "number" name= "qty" required>
                <label>Price:</label>
                <input type= "number" name= "price" step="0.01"required>
                <label>Expiration Date:</label>
                <input type="date" name="expdate" required><br>
                <hr>
                <input type="submit" name="add_record" value="Add Record">
                
                <button class="dashboard-button" onclick="window.location.href='dashboard.php'">Go to Dashboard</button>
            </form>
            </form>
        </div>
</body>
</html>