<?php 

$host = "localhost";
$database = "id3595091_games";
$user = "id3595091_games";
$password = file_get_contents("res.txt");

$conn = mysqli_connect($host, $user, $password, $database);

if(!$conn) {
	die("Connection failed: " + mysqli_connect_error());
}

$username = $_POST['username'];
$gameIndex = $_POST['index'];

// Get game's status
$query = "SELECT * FROM gameList WHERE gameId=$gameIndex";
$results = mysqli_query($conn, $query);

$status = null;

while($row = mysqli_fetch_assoc($results)) {
	$status = $row['alive'];
}

if($status != 0) {
	// Get host id
	$query = "SELECT * FROM global$gameIndex WHERE name=\"host\"";
	$results = mysqli_query($conn, $query);
	
	$hostId = null;
	
	while($row = mysqli_fetch_assoc($results)) {
		$hostId = $row['value'];
	}
	
	// Get host username
	$query = "SELECT * FROM game$gameIndex WHERE id=$hostId";
	$results = mysqli_query($conn, $query);
	
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