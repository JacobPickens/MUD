<?php 

$host = "localhost";
$accountsDatabase = "u142423238_acco";
$gamesDatabase = "u142423238_games";
$accountsUser = "u142423238_jake";
$gamesUser = "u142423238_games";
$password = file_get_contents("res.txt");


$conn = mysqli_connect($host, $accountsUser, $password, $accountsDatabase);

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