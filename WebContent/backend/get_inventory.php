<?php 

$host = "localhost";
$accountsDatabase = "u142423238_acco";
$gamesDatabase = "u142423238_games";
$accountsUser = "u142423238_jake";
$gamesUser = "u142423238_games";
$password = file_get_contents("res.txt");

$conn = mysqli_connect($host, $gamesUser, $password, $gamesDatabase);

if(!$conn) {
	die("Connection failed: " + mysqli_connect_error());
}

$playerId = $_POST['id'];
$gameIndex = $_POST['gameIndex'];

$query = "SELECT * FROM game$gameIndex WHERE id=$playerId";
$results = mysqli_query($conn, $query) or die("Error: " . mysqli_error());

if(mysqli_num_rows($results) > 0) {
	$json = "";
	while($row = mysqli_fetch_assoc($results)) {
		$json = $row['inventory'];
	}
	echo $json;
}

mysqli_close($conn);

?>