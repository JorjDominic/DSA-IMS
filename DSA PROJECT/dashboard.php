<?php
session_start();
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
            <h1>Welcome to Adriana's Marketing Inventory Management System</h1><br><br>
            <img src= "store.jpg"class = "homepic"alt="Store Image">
        
        </div>
        
    </div>
</body>
</html>

