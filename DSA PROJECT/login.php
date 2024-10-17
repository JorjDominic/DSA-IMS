<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "app";

$conn = new mysqli($servername, $username, $password, $dbname);

if($conn->connect_error){
	die("Connection Failed: " . $conn->connect_error);
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
	$username = $_POST['username'];
	$password = $_POST['password'];

	$sql = "SELECT * FROM Users WHERE username = '$username'";
	$result = $conn->query($sql);

	if($result->num_rows > 0){
		$row = $result->fetch_assoc();
		if(password_verify($password, $row['Password'])){
			$_SESSION['username'] = $username;
			header("Location: dashboard.php");
		}else{
			echo "Invalid password";
		}
	}else{
			echo "No user found!";
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
        <form id="loginForm" class="form active" method="POST">
        <h2>Login</h2>
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" id="password" name="password" placeholder="Password" required><br>
            <input type="checkbox" id="togglePassword"> <label>Show Password</label>
            <br>
            <button type="submit" name="action" value="login">Login</button>
        </form>
        <form id="registerForm" class="form" method="POST">
            <h2>Register</h2>
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit" name="action" value="register">Register</button>
        </form>
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
        if (this.checked) {
            passwordInput.setAttribute('type', 'text'); // Show password
        } else {
            passwordInput.setAttribute('type', 'password'); // Hide password
        }
    });
</script>
</body>
</html>
