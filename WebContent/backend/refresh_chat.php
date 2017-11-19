<?php 

$host = "localhost";
$database = "id3595091_games";
$user = "id3595091_games";
$password = file_get_contents("res.txt");

$conn = mysqli_connect($host, $user, $password, $database);

if(!$conn) {
	die("Connection failed: " + mysqli_connect_error());
}

$gameIndex = $_POST['gameIndex'];

$query = "SELECT * FROM chat$gameIndex";
$results = mysqli_query($conn, $query) or die("Error: " . mysqli_error());

if(mysqli_num_rows($results) > 0) {
	$json = "{\"chats\":[";
	while($row = mysqli_fetch_assoc($results)) {
		$id = $row['id'];
		$username = $row['username'];
		$recipient = $row['recipient'];
		$chat = $row['chat'];
		$json = $json . "{\"id\":$id, \"username\":\"$username\", \"recipient\":\"$recipient\", \"chat\":\"$chat\"},";
	}
	echo rtrim($json,',') . "]}";
}

mysqli_close($conn);

?>