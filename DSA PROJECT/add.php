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

    // Add server-side validation for the expiration date
    if (strtotime($date) <= time()) {
        echo "<script>alert('Expiration date must be greater than the current date.');</script>";
    } else {
        $sql = "INSERT INTO stock (category, name, stock, price, expirationdate) VALUES ('$category', '$productName', '$qty','$price','$date')";
        if ($conn->query($sql) === TRUE) {
            echo "<script type='text/javascript'>
                alert('Product added successfully!');
                window.location.href = 'search.php';
              </script>";
        } else {
            echo "<script>alert('Error: " . $conn->error . "');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Data Management</title>
    <link rel="stylesheet" href="add.css">
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
    <div class="table">
        <div class="add-record-container">
            <form method="POST" action="" onsubmit="return validateExpirationDate();">
                <h3 class="add">Add Record</h3>
                <label>Category:</label>
                <select name="category" id="category">
                    <option value="Animal Feeds">Animal Feeds</option>
                    <option value="Seeds">Seeds</option>
                    <option value="Fertilizer">Fertilizer</option>
                    <option value="Pesticide">Pesticide</option>
                </select>

                <label>Product Name:</label>
                <input type="text" name="name" required>
                <label>Quantity:</label>
                <input type="number" name="qty" required>
                <label>Price:</label>
                <input type="number" name="price" step="0.01" required>
                <label>Expiration Date:</label>
                <input type="date" name="expdate" required><br>
                <hr>
                <input type="submit" name="add_record" value="Add Record">
                <button class="dashboard-button" onclick="window.location.href='search.php'">Go back</button>
            </form>
        </div>
    </div>
</body>
</html>