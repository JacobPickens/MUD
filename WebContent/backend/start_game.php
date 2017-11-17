<?php 

$host = "localhost";
$database = "id3595091_games";
$user = "id3595091_games";
$password = "12hotnj";

$conn = mysqli_connect($host, $user, $password, $database);

if(!$conn) {
	die("Connection failed: " + mysqli_connect_error());
}

$playerId = $_POST['id'];
$gameIndex = $_POST["index"];

$query = "SELECT * FROM global$gameIndex WHERE name=\"host\"";
$results = mysqli_query($conn, $query);

$hostId = null;

while($row = mysqli_fetch_assoc($results)) {
	$hostId = $row['value'];
}

if($hostId == $playerId) {
	// Generate map
	$query = "SELECT * FROM global$gameIndex WHERE name=\"mapSize\"";
	$results = mysqli_query($conn, $query);
	
	$mapSize = 0;
	
	while($row = mysqli_fetch_assoc($results)) {
		$mapSize = $row['value'];
	}
	
	for($i = 0; $i < $mapSize; $i++) {
		$query = "SELECT * FROM locationTypes ORDER BY RAND() LIMIT 1";
		$results = mysqli_query($conn, $query);
		
		$type = "";
		$possibleSublocations = [];
		$numberOfSublocations = 0;
		$sublocations = [];
		
		while($row = mysqli_fetch_assoc($results)) {
			$type = $row['name'];
			$numberOfSublocations = $row['averageNumberOfSublocations'];
			$possibleSublocations = preg_split("/[\s,]+/", $row['sublocations']);
		}
		
		for($ii = 0; $ii < $numberOfSublocations; $ii++) {
			array_push($sublocations, $possibleSublocations[rand(0,$numberOfSublocations-1)]);
		}
		
		$query = "SELECT * FROM names ORDER BY RAND() LIMIT 1";
		$results = mysqli_query($conn, $query);
		
		$chosenName = "";
		$name = "";
		
		while($row = mysqli_fetch_assoc($results)) {
			$chosenName = $row['name'];
		}
		
		switch($type) {
			case "town":
				$name = "Town of " . $chosenName;
				break;
			case "forest":
				$name = $chosenName . " Forest";
				break;
			case "cave":
				$name = $chosenName . " Cave";
				break;
			default:
				$name = $chosenName;
				break;
		}
		
		$json = "{\\\"sublocations\\\":[";
		for($ii = 0; $ii < $numberOfSublocations; $ii++) {
			$json = $json . "{\\\"type\\\":\\\"$sublocations[$ii]\\\"},";
		}
		$json = rtrim($json,',') . "]}";
		
		// X and Y
		$query = "SELECT * FROM map$gameIndex";
		$results = mysqli_query($conn, $query);
		
		$x = rand(1, 10);
		$y = rand(1, 10);
		
		if(mysqli_num_rows($results)) {
			while($row = mysqli_fetch_assoc($results)) {
				$oX = $row['x'];
				$oY = $row['y'];
				
				while($x == $oX) {
					$x = rand(1, 10);
				}
				
				while($y== $oY) {
					$y = rand(1, 10);
				}
			}
		}
		
		$query = "INSERT INTO map$gameIndex(type, x, y, name, numberOfSublocations, sublocationJSON) VALUES(\"$type\", $x, $y, \"$name\", $numberOfSublocations, \"$json\")";
		if(!mysqli_query($conn, $query)){
			echo mysqli_error($conn);
		}
	}
	
	// Start game
	$query = "UPDATE gameList SET alive=2 WHERE gameId=$gameIndex";
	mysqli_query($conn, $query);
} else {
	echo 'nothost';
}

mysqli_close($conn);

?>