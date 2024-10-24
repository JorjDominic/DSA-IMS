<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Confirm logout with JavaScript
echo '<script>
    var confirmation = confirm("Are you sure you want to log out?");
    if (confirmation) {
        window.location.href = "login.php"; // Redirect to logout action
    } else {
        window.location.href = "dashboard.php"; // Redirect to dashboard or desired page
    }
</script>';
?>