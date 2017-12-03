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

$gameIndex = $_POST['gameIndex'];

$query = "SELECT * FROM global$gameIndex WHERE name=\"mapSize\"";
$results = mysqli_query($conn, $query);

$mapSize = 0;

while($row = mysqli_fetch_assoc($results)) {
	$mapSize = $row['value'];
}

$query = "SELECT * FROM map$gameIndex";
$results = mysqli_query($conn, $query);

if(mysqli_num_rows($results) > 0) {
	$json = "{\"mapSize\":$mapSize, \"locations\":[";
	while($row = mysqli_fetch_assoc($results)) {
		$id = $row['id'];
		$type = $row['type'];
		$x = $row['x'];
		$y = $row['y'];
		$name = $row['name'];
		$numberOfSublocation = $row['numberOfSublocations'];
		$sublocationJSON = $row['sublocationJSON'];
		$fixed = str_replace("\"", "\\\"", $sublocationJSON);
		$json = $json . "{\"id\":$id, \"type\":\"$type\", \"x\":$x, \"y\":$y, \"name\":\"$name\", \"numberOfSublocations\":$numberOfSublocation, \"sublocationJSON\":\"$fixed\"},";
	}
	echo rtrim($json,',') . "]}";
} else { // No map data found. Gunna have to have a game crash
	echo 'nomap';
}

mysqli_close($conn);

?>