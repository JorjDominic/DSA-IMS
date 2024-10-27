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

// Handle the 'ID' consistently as uppercase since your database column is 'ID'
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);  // Ensure it's an integer
} else {
    echo "Invalid ID";
    exit;
}

// Use uppercase 'ID' to match the column name in your database
$sql = "SELECT * FROM stock WHERE ID = $id";  
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    echo "No Records Found!";
    exit;
}

// Handle updating the record
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category = $_POST['category'];
    $productName = $_POST['name'];
    $qty = $_POST['qty'];
    $price = $_POST['price'];
    $date = $_POST['expdate'];

    // Server-side validation for the expiration date
    if (strtotime($date) <= time()) {
        echo "<script>alert('Expiration date must be greater than the current date.');</script>";
    } else {
        $update_sql = "UPDATE stock SET category = '$category', name = '$productName', stock = '$qty', price = '$price', expirationdate = '$date' WHERE ID = $id";  // Use uppercase 'ID'
        if ($conn->query($update_sql) === TRUE) {
            echo "Record updated Successfully!";
            header("Location: search.php");
            exit;
        } else {
            echo "Error updating record: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Record</title>
    <link rel="stylesheet" href="edit.css">
    <script>
        function validateExpirationDate() {
            const expDateInput = document.querySelector('input[name="expdate"]');
            const expDate = new Date(expDateInput.value);
            const currentDate = new Date();
            // Set the current date to 00:00:00 to only compare the date part
            currentDate.setHours(0, 0, 0, 0);

            if (expDate <= currentDate) {
                alert("Expiration date must be greater than the current date.");
                expDateInput.value = ""; // Clear the invalid input
                expDateInput.focus(); // Focus back to the input
                return false; // Prevent form submission
            }
            return true; // Allow form submission
        }
    </script>
</head>
<body class="add1">
    <div class="add-record-container">
        <h3>Edit Record</h3>
        <form method="POST" action="" onsubmit="return validateExpirationDate();">
            <label>Category:</label><br>
            <select name="category" id="category" required>
                <option value="Animal Feeds" <?php echo $row['Category'] == 'Animal Feeds' ? 'selected' : ''; ?>>Animal Feeds</option>
                <option value="Seeds" <?php echo $row['Category'] == 'Seeds' ? 'selected' : ''; ?>>Seeds</option>
                <option value="Fertilizer" <?php echo $row['Category'] == 'Fertilizer' ? 'selected' : ''; ?>>Fertilizer</option>
                <option value="Pesticide" <?php echo $row['Category'] == 'Pesticide' ? 'selected' : ''; ?>>Pesticide</option>
            </select><br><br>

            <label>Product Name:</label>
            <input type="text" name="name" value="<?php echo $row['Name']; ?>" required>

            <label>Quantity:</label>
            <input type="number" name="qty" value="<?php echo $row['Stock']; ?>" required>

            <label>Price:</label>
            <input type="number" name="price" value="<?php echo $row['Price']; ?>" step="0.01" required>

            <label>Expiration Date:</label>
            <input type="date" name="expdate" value="<?php echo $row['ExpirationDate']; ?>" required>
            <hr>
            <input type="submit" value="Update"><br>
            <button class="dashboard-button" onclick="window.location.href='dashboard.php'">Go to Dashboard</button>
        </form>
    </div>
</body>
</html>