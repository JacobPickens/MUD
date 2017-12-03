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
$username = $_POST['username'];
$recipient = mysqli_real_escape_string($conn, $_POST['recipient']);
$chat = mysqli_real_escape_string($conn, $_POST['chat']);

// Eventually we should check to see if the player is actually in the game

$query = "INSERT INTO chat$gameIndex(username, recipient, chat) VALUES(\"$username\", \"$recipient\", \"$chat\")";
mysqli_query($conn, $query);

mysqli_close($conn);

?>