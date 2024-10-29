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

// Get the item ID from the URL
$id = $_GET['id'] ?? null;

if ($id) {
    // Fetch product details (Name, Stock, Category, Price, ExpirationDate) for the item
    $sql = "SELECT Name, Stock, Category, Price, ExpirationDate FROM stock WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $product_name = $row['Name'];
        $current_stock = $row['Stock'];
        $category = $row['Category'];
        $price = $row['Price'];
        $expiration_date = $row['ExpirationDate'];
    } else {
        echo "Product not found.";
        exit;
    }
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['restock_amount'])) {
    $restock_amount = (int)$_POST['restock_amount'];

    // Update the stock in the database
    $sql = "UPDATE stock SET Stock = Stock + ? WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $restock_amount, $id);
    
    if ($stmt->execute()) {
        // After updating stock, insert into orders table
        $currentDate = date("Y-m-d"); // Get the current date
        $sql_insert = "INSERT INTO orders (Category, Name, Quantity, Price, OrderDate, ItemStatus) 
                       VALUES (?, ?, ?, ?, ?, 'Restocked')";
        $insert_stmt = $conn->prepare($sql_insert);
        $insert_stmt->bind_param("ssids", $category, $product_name, $restock_amount, $price, $currentDate);
        
        if ($insert_stmt->execute()) {
            echo "<script>
                    alert('Stock updated and order created successfully!');
                    window.location.href = 'search.php';
                  </script>";
        } else {
            echo "Error creating order: " . $insert_stmt->error;
        }
        $insert_stmt->close();
    } else {
        echo "Error updating stock: " . $conn->error;
    }
    $stmt->close();
    
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Restock Product</title>
</head>
<body>
<link rel="stylesheet" href="restock.css">
    <div class="container">
    <h2>Restock Product - <?php echo htmlspecialchars($product_name); ?></h2>
    <p><strong>Category:</strong> <?php echo htmlspecialchars($category); ?></p>
    <p><strong>Current Stock:</strong> <?php echo htmlspecialchars($current_stock); ?></p>
    <p><strong>Price:</strong> <?php echo htmlspecialchars($price); ?></p>
    <p><strong>Expiration Date:</strong> <?php echo htmlspecialchars($expiration_date); ?></p>

    <form method="POST" action="">
        <label>Restock Amount:</label>
        <input type="number" name="restock_amount" min="1" required>
        <input type="submit" value="Restock">
    </form>

    <button onclick="window.location.href='search.php'" class="go-back-btn">Go Back</button>
    </div>
</body>
</html>