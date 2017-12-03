<?php 

$host = "localhost";
$database = "id3595091_games";
$user = "id3595091_games";
$password = file_get_contents("res.txt");

$conn = mysqli_connect($host, $user, $password, $database);

if(!$conn) {
	die("Connection failed: " + mysqli_connect_error());
}

$attackingId = $_POST['attacker'];
$victimId = $_POST['victim'];
$location = $_POST['location'];
$gameIndex = $_POST['gameIndex'];

// Check to see if the players are in the same location
$query = "SELECT * FROM game$gameIndex";
$results = mysqli_query($conn, $query);
if(!$results) {
	die(mysqli_error($conn));
}

$attackingLocation = null;
$victimLocation = null;

if(mysqli_num_rows($results) > 0) {
	while($row = mysqli_fetch_assoc($results)) {
		if($row['id'] == $attackingId) {
			$attackingLocation = $row['location'];
		} else if($row['id'] == $victimId) {
			$victimLocation = $row['location'];
		}
	}
} else {
	die('invalidgame');
}

if($attackingLocation == $victimLocation) {
	$query = "INSERT INTO duel$gameIndex(player1, player2, player1Cooldowns, player2Cooldowns, currentPlayer, location, alive) VALUES($attackingId, $victimId, \"{}\", \"{}\", 0, $location, 1)";
	if(!mysqli_query($conn, $query)) {
		die(mysqli_error($conn));
	}
	
	$duelId = mysqli_insert_id($conn);
	
	echo $duelId;
	
	$query = "UPDATE game$gameIndex SET location=0 WHERE id=$attackingId";
	if(!mysqli_query($conn, $query)) {
		die(mysqli_error($conn));
	}
	
	$query = "UPDATE game$gameIndex SET location=0 WHERE id=$victimId";
	if(!mysqli_query($conn, $query)) {
		die(mysqli_error($conn));
	}
	
	$query = "UPDATE game$gameIndex SET duelId=$duelId WHERE id=$attackingId";
	if(!mysqli_query($conn, $query)) {
		die(mysqli_error($conn));
	}
	
	$query = "UPDATE game$gameIndex SET duelId=$duelId WHERE id=$victimId";
	if(!mysqli_query($conn, $query)) {
		die(mysqli_error($conn));
	}
} else {
	echo 'differentlocation';
}

mysqli_close($conn);

?>