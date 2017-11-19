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

$query = "SELECT * FROM game$gameIndex";
$results = mysqli_query($conn, $query);

if(mysqli_num_rows($results) > 0) {
	$json = "{\"players\":[";
	while($row = mysqli_fetch_assoc($results)) {
		$id = $row['id'];
		$username = $row['username'];
		$location = $row['location'];
		$json = $json . "{\"id\":$id, \"username\":\"$username\", \"location\":$location},";
	}
	echo rtrim($json,',') . "]}";
}

mysqli_close($conn);

?>