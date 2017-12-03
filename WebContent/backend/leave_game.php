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

$query = "SELECT * FROM global$gameIndex WHERE name=\"host\"";
$results = mysqli_query($conn, $query);

$hostId = null;
while($row = mysqli_fetch_assoc($results)) {
	$hostId = $row['value'];
}

$query = "SELECT * FROM gameList WHERE gameId=$gameIndex";
$results = mysqli_query($conn, $query);

$alive = 0;

while($row = mysqli_fetch_assoc($results)) {
	$alive = $row['alive'];
}

if($hostId == $playerId && $alive != 2) { // Player is the host
	$query = "UPDATE gameList SET alive=0 WHERE gameId=$gameIndex";
	mysqli_query($conn, $query);
	
	$query = "DROP TABLE game$gameIndex; DROP TABLE global$gameIndex; DROP TABLE chat$gameIndex";
	mysqli_query($conn, $query);
} else {
	$query = "DELETE FROM game$gameIndex WHERE id=$playerId";
	mysqli_query($conn, $query);
}

mysqli_close($conn);

?>