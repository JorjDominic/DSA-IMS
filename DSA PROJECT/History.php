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

// Initialize the SQL query
$sql_history = "SELECT * FROM orders";

// Check if a search term is provided
if (isset($_GET['search_name'])) {
    $search_name = $conn->real_escape_string($_GET['search_name']); // Escape input for security
    $sql_history .= " WHERE Name LIKE '%$search_name%'"; // Add WHERE clause for search
}

// Add ordering based on sorting options
$order_by = "OrderDate DESC"; // Default sorting by date
// Filter by Item Status based on the selected option (Sold, Removed, or Expired)
if (isset($_GET['item_status']) && $_GET['item_status'] != '') {
    $item_status = $conn->real_escape_string($_GET['item_status']);
    $sql_history .= " AND ItemStatus = '$item_status'";
}
// Check for sorting by item status or date
if (isset($_GET['sort_option'])) {
    $sort_option = $_GET['sort_option'];
    switch ($sort_option) {
        case 'date_asc':
            $order_by = "OrderDate ASC";
            break;
        case 'date_desc':
            $order_by = "OrderDate DESC";
            break;
        default:
            $order_by = "OrderDate DESC"; // Default sorting by date
    }
}

// Modify the SQL query with the sorting option
$sql_history .= " ORDER BY $order_by";

// Execute the query
$history_result = $conn->query($sql_history);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product History (Sold and Expired)</title>
    <link rel="stylesheet" href="history.css">
</head>
<body>

<?php include 'sidebar.php'; ?>
<div class="container1">
<div class="container">

<h2>Product History</h2><br>

<!-- Search Form -->
<form method="GET" action="">
    <label class="label1">Search by Product Name:</label>
    <input type="text" name="search_name" >
    
    <!-- Sorting Options -->
    <label>Sort by:</label>
    <select name="sort_option">
        <option value="date_desc">Newest to Oldest</option>
        <option value="date_asc">Oldest to Newest</option>
    </select>
     <!-- Item Status Filter -->
     <label>Filter by Item Status:</label>
            <select name="item_status">
                <option value="">All Statuses</option>
                <option value="Sold">Sold</option>
                <option value="Removed">Removed</option>
                <option value="Expired">Expired</option>
            </select>
    <input type="submit" value="Search">
</form>
<br>

<!-- Display History Table -->
<table border="1">
    <tr>
        <th>Category</th>
        <th>Product Name</th>
        <th>Quantity</th>
        <th>Price</th>
        <th>Date</th>
        <th>Status</th> <!-- Shows whether Sold, Removed, or Expired -->
    </tr>
    <?php if ($history_result->num_rows > 0) {
        while ($row = $history_result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['Category']; ?></td>
                <td><?php echo $row['Name']; ?></td>
                <td><?php echo $row['Quantity']; ?></td>
                <td><?php echo $row['Price']; ?></td>
                <td><?php echo $row['OrderDate']; ?></td>
                <td><?php echo $row['ItemStatus']; ?></td> <!-- Sold, Removed, or Expired -->
            </tr>
    <?php } } else { ?>
        <tr><td colspan="6">No items found in history.</td></tr>
    <?php } ?>
</table>

</div>
</div>
</body>
</html>

<?php
$conn->close();
?>