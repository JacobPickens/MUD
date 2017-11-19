<?php 

$host = "localhost";
$database = "id3595091_games";
$user = "id3595091_games";
$password = "12hotnj";

$conn = mysqli_connect($host, $user, $password, $database);

if(!$conn) {
	die("Connection failed: " + mysqli_connect_error());
}

$id = $_POST['id'];
$gameIndex = $_POST['gameIndex'];
$locationId = $_POST['locationId'];

$query = "SELECT * FROM global$gameIndex WHERE name=\"mapSize\"";
$results = mysqli_query($conn, $query);

$mapSize = 0;

while($row = mysqli_fetch_assoc($results)) {
	$mapSize = $row['value'];
}

if($locationId <= $mapSize && $locationId != 0) {
	$query = "UPDATE game$gameIndex SET location=$locationId WHERE id=$id";
	mysqli_query($conn, $query);
} else {
	echo 'notvalid';
}

mysqli_close($conn);

?>