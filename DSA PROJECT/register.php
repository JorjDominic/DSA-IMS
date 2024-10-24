<?php
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
	$email = $_POST['email'];
	$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

	$sql = "INSERT INTO Users (username, email, password) 
	VALUES ('$username', '$email', '$password') ";

	if($conn -> query($sql)=== TRUE){
		echo "Registration successful";
	}else{
		echo "Error: " . $sql . "<br>" . $conn->error; 
	}
} 
?>
<!DOCTYPE html>
<html>
<head>
	<title>Register</title>
	<style>
		body {font-family: Arial;}
		form {width: 300px; margin: 0 auto;} 
	</style>
</head>
<body>
	<h2>Register</h2>
	<form method = "POST" action = "">
		<label> Username: </label><br>
		<input type = "text" name = "username" required><br><br>
		<label> Email: </label><br>
		<input type = "text" name = "email" required><br><br>
		<label> Password: </label><br>
		<input type = "text" name = "password" required><br><br>
 		<input type = "submit" value = "Register">
</body>

</html>
