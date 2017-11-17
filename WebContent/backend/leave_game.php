<?php 

$host = "localhost";
$database = "id3595091_games";
$user = "id3595091_games";
$password = "12hotnj";

$conn = mysqli_connect($host, $user, $password, $database);

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

if($hostId == $playerId) { // Player is the host
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