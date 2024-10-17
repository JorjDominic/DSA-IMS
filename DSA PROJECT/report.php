<?php
session_start();
if(!isset($_SESSION['username'])){
	header("location: login.php");
	exit;
}
$conn = new mysqli("localhost", "root", "", "app");
if($conn->connect_error){
	die("Connection Failed: " . $conn->connect_error);
}
$sql = "SELECT * FROM stock"; // Changed to reflect the stock table
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Stock Report</title>
	</head>
	<body>
		<h2>Stock Records</h2>
		<table border = "1">
			<tr>
				<th>ID</th>
				<th>Category</th>
				<th>Product Name</th>
				<th>Quantity</th>
				<th>Price</th>
				<th>Expiration Date</th>
				<th>Action</th>
			</tr>
			<?php 
			if($result ->num_rows > 0){
				while($row = $result->fetch_assoc()){ ?>
			<tr>
				<td><?php echo $row['ID']; ?></td>
				<td><?php echo $row['Category']; ?></td>
				<td><?php echo $row['Name']; ?></td>
				<td><?php echo $row['Stock']; ?></td> <!-- Changed "qty" to "stock" -->
				<td><?php echo $row['Price']; ?></td>
				<td><?php echo $row['ExpirationDate']; ?></td>
				<td>
					<a href = "edit.php?id=<?php echo $row['ID']; ?> ">Edit</a>
				</td>
			</tr>
		<?php } } ?>
		</table>
	</body>	
</html>