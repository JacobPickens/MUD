<?php 

$host = "localhost";
$database = "id3595091_games";
$user = "id3595091_games";
$password = "12hotnj";

$conn = mysqli_connect($host, $user, $password, $database);

if(!$conn) {
	die("Connection failed: " + mysqli_connect_error());
}

$gameIndex = $_POST['gameIndex'];
$username = $_POST['username'];
$chat = mysqli_real_escape_string($conn, $_POST['chat']);

// Eventually we should check to see if the player is actually in the game

$query = "INSERT INTO chat$gameIndex(username, chat) VALUES(\"$username\", \"$chat\")";
mysqli_query($conn, $query);

mysqli_close($conn);

?>