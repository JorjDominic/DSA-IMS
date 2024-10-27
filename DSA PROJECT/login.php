<?php

session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "app";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if($conn->connect_error){
    die("Connection Failed: " . $conn->connect_error);
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if($_POST['action'] == 'login'){
        // Login code
        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM Users WHERE username = '$username'";
        $result = $conn->query($sql);

        if($result->num_rows > 0){
            $row = $result->fetch_assoc();
            if(password_verify($password, $row['Password'])){
                $_SESSION['username'] = $username;
                header("Location: dashboard.php");
            } else {
                echo "<script>alert('Invalid password'); window.location.href='login.php';</script>";
            }
        } else {
            echo "<script>alert('No user found!'); window.location.href='login.php';</script>";
        }
    } elseif($_POST['action'] == 'register'){
        // Registration code
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Check if username or email already exists
        $check_sql = "SELECT * FROM Users WHERE username = '$username' OR email = '$email'";
        $check_result = $conn->query($check_sql);

        if($check_result->num_rows > 0){
            echo "<script>alert('Username or Email already exists!'); window.location.href='login.php';</script>";
        } else {
            // Insert new user into database
            $sql = "INSERT INTO Users (username, email, password) VALUES ('$username', '$email', '$password')";

            if($conn->query($sql) === TRUE){
                echo "<script>alert('Registration successful!'); window.location.href='login.php';</script>";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login/Register</title>
    <link rel="stylesheet" href="login.css"> <!-- Link to the external CSS file -->
</head>
<body>
    <div class="container">
        <!-- Login Form -->
        <form id="loginForm" class="form active" method="POST">
            <h1>Adriana's Marketing</h1>
            <h2>Login</h2>
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" id="password" name="password" placeholder="Password" required><br>
            <input type="checkbox" id="togglePassword"> <label>Show Password</label><br>
            <button type="submit" name="action" value="login">Login</button>
        </form>

        <!-- Registration Form -->
        <form id="registerForm" class="form" method="POST">
            <h2>Register</h2>
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit" name="action" value="register">Register</button>
        </form>

        <!-- Toggle Links -->
        <div class="toggle">
            <a id="showLogin">Login</a> <a id="showRegister">Register</a>
        </div>
    </div>

    <script>
        document.getElementById('showLogin').addEventListener('click', function() {
            document.getElementById('loginForm').classList.add('active');
            document.getElementById('registerForm').classList.remove('active');
            toggleLinksVisibility();
        });

        document.getElementById('showRegister').addEventListener('click', function() {
            document.getElementById('registerForm').classList.add('active');
            document.getElementById('loginForm').classList.remove('active');
            toggleLinksVisibility();
        });

        function toggleLinksVisibility() {
            const loginActive = document.getElementById('loginForm').classList.contains('active');
            const registerActive = document.getElementById('registerForm').classList.contains('active');
            document.getElementById('showLogin').style.display = registerActive ? 'inline' : 'none';
            document.getElementById('showRegister').style.display = loginActive ? 'inline' : 'none';
        }
        toggleLinksVisibility();

        document.getElementById('togglePassword').addEventListener('change', function() {
            const passwordInput = document.getElementById('password');
            passwordInput.type = this.checked ? 'text' : 'password';
        });
    </script>
</body>
</html>