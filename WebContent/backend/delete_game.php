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

$gameIndex = $_POST["index"];

$query = "UPDATE gameList SET alive=0 WHERE gameId=$gameIndex";
if(!mysqli_query($conn, $query)) {
	echo mysqli_error($conn);
}

$query = "DROP TABLE game$gameIndex; DROP TABLE global$gameIndex; DROP TABLE chat$gameIndex";
mysqli_query($conn, $query);

mysqli_close($conn);

?>