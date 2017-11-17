<?php 

$host = "localhost";
$database = "id3595091_accounts";
$user = "id3595091_accounts";
$password = file_get_contents("res.txt");

$conn = mysqli_connect($host, $user, $password, $database);

if(!$conn) {
	die("Connection failed: " + mysqli_connect_error());
}

$username = mysqli_real_escape_string($conn, $_POST['username']);
$password = mysqli_real_escape_string($conn, $_POST['password']);

$query = "SELECT * FROM accounts WHERE username=\"$username\"";
$results = mysqli_query($conn, $query);

if(mysqli_num_rows($results) > 0) {
	$id = 0;
	while($row = mysqli_fetch_assoc($results)) {
		$id = $row['id'];
	}
	
	// Login
	$query = "SELECT * FROM accounts WHERE id=$id";
	$results = mysqli_query($conn, $query);
	
	$returnedPassword = "";
	while($row = mysqli_fetch_assoc($results)) {
		$returnedPassword = $row['password'];
	}
	
	if($password == $returnedPassword) {
		echo $id;
	} else {
		echo 'failed'; // Password incorrect
	}
} else {
	echo 'failed'; // User doesn't exist
}

mysqli_close($conn);

?>