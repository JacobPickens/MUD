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

$username = $_POST['username'];
$gameIndex = $_POST['index'];

// Get game's status
$query = "SELECT * FROM gameList WHERE gameId=$gameIndex";
$results = mysqli_query($conn, $query);

if(!$results) {
	die("1" . mysqli_error($conn));
}

$status = null;

while($row = mysqli_fetch_assoc($results)) {
	$status = $row['alive'];
}

if($status != 0) {
	// Get host id
	$query = "SELECT * FROM global$gameIndex WHERE name=\"host\"";
	$results = mysqli_query($conn, $query);
	
	if(!$results) {
		die("2" . mysqli_error($conn));
	}
	
	$hostId = null;
	
	while($row = mysqli_fetch_assoc($results)) {
		$hostId = $row['value'];
	}
	
	// Get host username
	$query = "SELECT * FROM game$gameIndex WHERE id=$hostId";
	$results = mysqli_query($conn, $query);
	
	if(!$results) {
		die("3" . mysqli_error($conn));
	}
	
	$hostUsername = null;
	
	if(mysqli_num_rows($results) > 0) {
		while($row = mysqli_fetch_assoc($results)) {
			$hostUsername = $row['username'];
		}
	
		// Get player info
		$query = "SELECT * FROM game$gameIndex";
		$results = mysqli_query($conn, $query);
	
		if(mysqli_num_rows($results) > 0) {
			$json = "{\"hostName\":\"$hostUsername\", \"status\":$status, \"gameIndex\":$gameIndex, \"players\":[";
			$n = 0;
			while($row = mysqli_fetch_assoc($results)) {
				$otherUsername = $row['username'];
				if($otherUsername != $username) {
					$json = $json . "\"$otherUsername\",";
				}
			}
			echo rtrim($json,',') . "]}";
		}
	}
} else { 
	echo 'deleted';
}

mysqli_close($conn);

?>