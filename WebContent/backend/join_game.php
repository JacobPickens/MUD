<?php 

$host = "localhost";
$accountsDatabase = "id3595091_accounts";
$gamesDatabase = "id3595091_games";
$accountsUser = "id3595091_accounts";
$gamesUser = "id3595091_games";
$password = file_get_contents("res.txt");

$accountsConn = mysqli_connect($host, $accountsUser, $password, $accountsDatabase);

if(!$accountsConn) {
	die("Connection failed: " + mysqli_connect_error());
}

$gamesConn = mysqli_connect($host, $gamesUser, $password, $gamesDatabase);

if(!$gamesConn) {
	die("Connection failed: " + mysqli_connect_error());
}

$playerId = $_POST['id'];
$joinCode = $_POST['joinCode'];

$query = "SELECT * FROM gameList WHERE joinCode=\"$joinCode\"";
$results = mysqli_query($gamesConn, $query);

if(mysqli_num_rows($results) > 0) {
	$gameIndex = null;
	$alive = 0;
	while($row = mysqli_fetch_assoc($results)) {
		$gameIndex = $row['gameId'];
		$alive = $row['alive'];
	}
	
	if($alive == 1) {
		$query = "SELECT * FROM accounts WHERE id=$playerId";
		$results = mysqli_query($accountsConn, $query);
		if(mysqli_num_rows($results) > 0) {
			$username = "";
			while($row = mysqli_fetch_assoc($results)) {
				$username = $row['username'];
			}
			
			$query = "SELECT * FROM game$gameIndex WHERE username=\"$username\"";
			$results = mysqli_query($gamesConn, $query);
			
			if(mysqli_num_rows($results) == 0) {
				// Puts account into game table
				$query = "INSERT INTO game$gameIndex(id, username) VALUES($playerId, \"$username\")";
				mysqli_query($gamesConn, $query);
				
				// Get host id
				$query = "SELECT * FROM global$gameIndex WHERE name=\"host\"";
				$results = mysqli_query($gamesConn, $query);
				
				$hostId = null;
				
				while($row = mysqli_fetch_assoc($results)) {
					$hostId = $row['value'];
				}
				
				// Get host username
				$query = "SELECT * FROM game$gameIndex WHERE id=$hostId";
				$results = mysqli_query($gamesConn, $query);
				
				$hostUsername = null;
				
				if(mysqli_num_rows($results) > 0) {
					while($row = mysqli_fetch_assoc($results)) {
						$hostUsername = $row['username'];
					}
				}
				
				// Get game's status
				$query = "SELECT * FROM gameList WHERE gameId=$gameIndex";
				$results = mysqli_query($gamesConn, $query);
				
				$status = null;
				
				while($row = mysqli_fetch_assoc($results)) {
					$status = $row['alive'];
				}
				
				// Get other information to return to pregame state
				$query = "SELECT * FROM game$gameIndex";
				$results = mysqli_query($gamesConn, $query);
					
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
			} else {
				echo "joined";
			}
		} else { // Account ID supplied is not valid
			echo 'account';
		}
	} else { // Game is over
		echo 'dead';
	}
} else { // Game doesn't exist
	echo 'no';
}

mysqli_close($accountsConn);
mysqli_close($gamesConn);

?>