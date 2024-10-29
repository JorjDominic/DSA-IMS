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

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

// Initialize variables
$search_results = [];
if (isset($_GET['success']) && $_GET['success'] == 1) {
    echo "<script>alert('Stock updated successfully!');</script>";
}

// Handle searching for records
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search_name'])) {
    $search_name = $_POST['search_name'];
    $selected_category = $_POST['category'] ?? '';
    $sort_option = $_POST['sort_option'] ?? '';

    // Build the SQL query based on the search term, selected category, and sort option
   
    $sql = "SELECT * FROM stock WHERE name LIKE '%$search_name%'";


    // Filter by category if selected
    if ($selected_category) {
        $sql .= " AND category = '$selected_category'";
    }

    // Apply sorting based on selected option
    switch ($sort_option) {
        case 'alphabetical':
            $sql .= " ORDER BY name ASC";
            break;
        case 'category':
            $sql .= " ORDER BY category ASC";
            break;
        case 'price_asc':
            $sql .= " ORDER BY price ASC";
            break;
        case 'price_desc':
            $sql .= " ORDER BY price DESC";
            break;
        default:
            break;
    }

    $result = $conn->query($sql);
} else {
    // Get all stock records if no search is performed
    $sql_report = "SELECT * FROM stock";
    $result = $conn->query($sql_report);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Stock Management</title>
    <link rel="stylesheet" href="search.css"> <!-- Link to the CSS file -->
</head>

<body class = "search">
<?php include 'sidebar.php';?>
<div class = "container1">
    <div class = "container">
    <!-- Search Form -->
    <h2>Stock Management</h2><br>
    <form id="searchForm" method="POST" action="">
        <label>Search by Product Name:</label>
        <input type="text" name="search_name">

        <label>Select Category:</label>
        <select name="category">
            <option value="">All Categories</option>
            <option value="Animal Feeds">Animal Feeds</option>
            <option value="Seeds">Seeds</option>
            <option value="Fertilizers">Fertilizers</option>
            <option value="Pesticides">Pesticides</option>
        </select>
        
        <label>Sort By:</label>
        <select name="sort_option">
            <option value="">Select Sort Option</option>
            <option value="alphabetical">Alphabetical Order</option>
            <option value="price_asc">Price: Low to High</option>
            <option value="price_desc">Price: High to Low</option>
        </select>

        <input type="submit" value="Search">
        <button type="button" onclick="window.location.href='add.php'" style="margin-left: 0px;">Add Product</button>
        <button type="button" onclick="window.location.href='expired.php'" style="margin-left: 0px;">Update </button>
        <br>
    </form>

    <h3><?php echo isset($search_name) ? "Search Results for '$search_name'" : "All Stock Records"; ?></h3>
    <div class="table-wrapper">
    <table class = "searchtable">
        <tr>
            
            <th>Category</th>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Expiration Date</th>
            <th>Action</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    
                    <td><?php echo $row['Category']; ?></td>
                    <td><?php echo $row['Name']; ?></td>
                    <td><?php echo $row['Stock']; ?></td>
                    <td><?php echo $row['Price']; ?></td>
                    <td><?php echo $row['ExpirationDate']; ?></td>
                    <td>
                        <a href="restock.php?id=<?php echo $row['ID']; ?>">Restock</a><br>
                        <a href="edit.php?id=<?php echo $row['ID']; ?>">Edit</a>
                        <br>
                        <a href="remove.php?id=<?php echo $row['ID']; ?>" onclick="return confirm('Are you sure you want to remove this item?');">Remove</a>
                    </td>
                </tr>
            <?php }
        } else {
            echo "<tr><td colspan='7'>No records found.</td></tr>";
        } ?>
    </table>
    </div>
    </div>
</div>
</body>
</html>