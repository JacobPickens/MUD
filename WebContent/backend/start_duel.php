<?php 

$host = "localhost";
$database = "id3595091_games";
$user = "id3595091_games";
$password = file_get_contents("res.txt");

$conn = mysqli_connect($host, $user, $password, $database);

if(!$conn) {
	die("Connection failed: " + mysqli_connect_error());
}

$attackingId = $_GET['attacker'];
$victimId = $_GET['victim'];
$gameIndex = $_GET['gameIndex'];

$query = "INSERT INTO duel$gameIndex(player1, player2, player1Cooldowns, player2Cooldowns, currentPlayer) VALUES($attackingId, $victimId, \"{}\", \"{}\", 0)";
if(!mysqli_query($conn, $query)) {
	die(mysqli_error($conn));
}

$query = "";
if(!mysqli_query($conn, $query)) {
	die(mysqli_error($conn));
}

mysqli_close($conn);

?>