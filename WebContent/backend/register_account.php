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
	echo 'exists';
} else {
	$query = "INSERT INTO accounts(username, password) VALUES(\"$username\", \"$password\")";
	
	if(mysqli_query($conn, $query)) {
		echo '0';
	} else {
		echo "MySQL Error: " . mysqli_error($conn);
	}
}

mysqli_close($conn);

?>