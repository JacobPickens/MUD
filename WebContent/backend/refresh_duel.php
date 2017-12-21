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

$duelId = $_POST['duelId'];
$gameIndex = $_POST['gameIndex'];

$attackerId = null;
$victimId = null;
$currentPlayer = null;
$alive = null;

$query = "SELECT * FROM duel$gameIndex";
$results = mysqli_query($conn, $query);

if(!$results) {
	die(mysqli_error($conn));
}

if(mysqli_num_rows($results) > 0) {
	while($row = mysqli_fetch_assoc($results)) {
		if($row['id'] == $duelId) {
			$attackerId = $row['player1'];
			$victimId = $row['player2'];
			$currentPlayer = $row['currentPlayer'];
			$alive = $row['alive'];
		}
	}
}

// Get player combat stats
$duelObject = new stdClass();
$duelObject->currentPlayer = $currentPlayer;
$duelObject->alive = $alive;
$duelObject->player1 = new stdClass();
$duelObject->player2 = new stdClass();

$query = "SELECT * FROM game$gameIndex WHERE id=$attackerId";
$results = mysqli_query($conn, $query);

if(!$results) {
	die(mysqli_error($conn));
}

$attackerUsername = null;
$attackerStats = null;

while($row = mysqli_fetch_assoc($results)) {
	$attackerUsername = $row['username'];
	$attackerStats = json_decode($row['combatStats']);
}

$duelObject->player1->id = $attackerId;
$duelObject->player1->username = $attackerUsername;
$duelObject->player1->health = $attackerStats->stats[0];
$duelObject->player1->damage = $attackerStats->stats[1];

$query = "SELECT * FROM game$gameIndex WHERE id=$victimId";
$results = mysqli_query($conn, $query);

if(!$results) {
	die(mysqli_error($conn));
}

$victimUsername = null;
$victimStats = null;

while($row = mysqli_fetch_assoc($results)) {
	$victimUsername = $row['username'];
	$victimStats = json_decode($row['combatStats']);
}

$duelObject->player2->id = $victimId;
$duelObject->player2->username = $victimUsername;
$duelObject->player2->health = $victimStats->stats[0];
$duelObject->player2->damage = $victimStats->stats[1];

echo json_encode($duelObject);

mysqli_close($conn);

?>