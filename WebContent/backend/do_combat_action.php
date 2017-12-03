<?php 

$host = "localhost";
$database = "id3595091_games";
$user = "id3595091_games";
$password = file_get_contents("res.txt");

$conn = mysqli_connect($host, $user, $password, $database);

if(!$conn) {
	die("Connection failed: " + mysqli_connect_error());
}

$actionId = $_GET['action'];
$playerId = $_GET['id'];
$duelId = $_GET['duelId'];
$gameIndex = $_GET['gameIndex'];

$query = "SELECT * FROM duel$gameIndex WHERE id=$duelId";
$results = mysqli_query($conn, $query);

if(!$results) {
	die(mysqli_error($conn));
}

$currentTurn = null;
$player1Id = null;
$player2Id = null;

if(mysqli_num_rows($results) > 0) {
	while($row = mysqli_fetch_assoc($results)) {
		$currentTurn = $row['currentPlayer'];
		$player1Id = $row['player1'];
		$player2Id = $row['player2'];
	}
	
	if($currentTurn == 0) {
		if($player1Id == $playerId) { // Player 1's turn
			switch($actionId) {
				case 0: // Basic Attack
					$query = "SELECT * FROM game$gameIndex WHERE id=$player2Id";
					$results = mysqli_query($conn, $query);
					
					$stats = null;
					
					while($row = mysqli_fetch_assoc($results)) {
						$stats = json_decode($row['combatStats']);
					}
					
					$stats->stats[0] -= 5;
					
					if($stats->stats[0] <= 0) { // Player Died
						echo 'gameover';
					} else {
						$newStats = str_replace("\"", "\\\"", json_encode($stats));
							
						$query = "UPDATE game$gameIndex SET combatStats=\"$newStats\" WHERE id=$player2Id";
						mysqli_query($conn, $query);
					}
					
					$query = "UPDATE duel$gameIndex SET currentPlayer=1 WHERE id=$duelId";
					mysqli_query($conn, $query);
					break;
			}
		} else {
			echo 'notturn';
		}
	} else if($currentTurn == 1) { // Player 2's turn
		if($player2Id == $playerId) {
		switch($actionId) {
				case 0: // Basic Attack
					$query = "SELECT * FROM game$gameIndex WHERE id=$player1Id";
					$results = mysqli_query($conn, $query);
					
					$stats = null;
					
					while($row = mysqli_fetch_assoc($results)) {
						$stats = json_decode($row['combatStats']);
					}
					
					$stats->stats[0] -= 5;
					
					if($stats->stats[0] <= 0) { // Player Died
						$query = "DELETE FROM game$gameIndex WHERE id=$player1Id";
						mysqli_query($conn, $query);
						
						$query = "UPDATE duel$gameIndex SET alive=0 WHERE id=$duelId";
						mysqli_query($conn, $query);
						
						$location = null;
						
						$query = "SELECT * FROM duel$gameIndex WHERE id=$duelId";
						$results = mysqli_query($conn, $query);
						
						if(!$results) {
							echo mysqli_error($conn);
						}
						
						while($row = mysqli_fetch_assoc($results)) {
							$location = json_decode($row['location']);
						}
						
						$query = "UPDATE game$gameIndex SET location=$location WHERE id=$player2Id";
						mysqli_query($conn, $query);
						
						echo 'win';
					} else {
						$newStats = str_replace("\"", "\\\"", json_encode($stats));
							
						$query = "UPDATE game$gameIndex SET combatStats=\"$newStats\" WHERE id=$player1Id";
						mysqli_query($conn, $query);
					}
					
					$query = "UPDATE duel$gameIndex SET currentPlayer=0 WHERE id=$duelId";
					mysqli_query($conn, $query);
					break;
			}
		} else {
			echo 'notturn';
		}
	}
}

mysqli_close($conn);

?>