<?php
session_start();
header("Cache-Control: no-cache, must-revalidate, no-store, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="dashboard.css"> <!-- Link to the external CSS file -->
</head>
<body>
    <?php include 'sidebar.php';?>
    <div class="container1">
        <div class="container">
            <br><br><br>
            <h1>Welcome to Adriana's Marketing Inventory Management System</h1><br><br>
            <img src= "store.jpg"class = "homepic"alt="Store Image">
        
        </div>
        
    </div>
    <script>
        // Check if the session is active on the client-side
        if (!<?php echo isset($_SESSION['username']) ? 'true' : 'false'; ?>) {
            // If not logged in, redirect to login page
            window.location.href = 'login.php';
        }
    </script>
</body>
</html>

