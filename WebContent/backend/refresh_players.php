<?php 

$host = "localhost";
$accountsDatabase = "u142423238_acco";
$gamesDatabase = "u142423238_games";
$accountsUser = "u142423238_jake";
$gamesUser = "u142423238_games";
$password = file_get_contents("res.txt");

$conn = mysqli_connect($host, $user, $password, $database);

if(!$conn) {
	die("Connection failed: " + mysqli_connect_error());
}

$gameIndex = $_POST['gameIndex'];

$query = "SELECT * FROM game$gameIndex";
$results = mysqli_query($conn, $query);

if(mysqli_num_rows($results) > 0) {
	$json = "{\"players\":[";
	while($row = mysqli_fetch_assoc($results)) {
		$id = $row['id'];
		$username = $row['username'];
		$location = $row['location'];
		$duelId = $row['duelId'];
		$json = $json . "{\"id\":$id, \"username\":\"$username\", \"location\":$location, \"duelId\":$duelId},";
	}
	echo rtrim($json,',') . "]}";
}

mysqli_close($conn);

?>