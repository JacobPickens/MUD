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