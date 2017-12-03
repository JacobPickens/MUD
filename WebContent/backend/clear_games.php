<?php 

$host = "localhost";
$accountsDatabase = "u142423238_acco";
$gamesDatabase = "u142423238_games";
$accountsUser = "u142423238_jake";
$gamesUser = "u142423238_games";
$password = file_get_contents("res.txt");

$accountsConn = mysqli_connect($host, $accountsUser, $password, $accountsDatabase);

if(!$accountsConn) {
	die("Connection failed: " + mysqli_connect_error());
}

$gamesConn = mysqli_connect($host, $gamesUser, $password, $gamesDatabase);

if(!$gamesConn) {
	die("Connection failed: " + mysqli_connect_error());
}

$password = $_GET['password'];

if($password == "957AE1BCD26380FE") {
	$query = "SELECT * FROM constants WHERE name=\"gameIndex\"";
	$results = mysqli_query($accountsConn, $query);
	
	$currentIndex = 0;
	
	while($row = mysqli_fetch_assoc($results)) {
		$currentIndex = $row['value'];
	}
	
	for($i = 0; $i < $currentIndex+1; $i++) {
		$query = "SELECT alive FROM gameList WHERE gameId=$i";
		$results = mysqli_query($gamesConn, $query);
		
		if(!$results) {
			echo 'Error: ' . mysqli_error($gamesConn);
		}
		
		$alive = 0;
		
		while($row = mysqli_fetch_assoc($results)) {
			$alive = $row['alive'];
		}
		
		if($alive != 0) {
			$query = "DROP TABLE game$i; DROP TABLE global$i; DROP TABLE chat$i; DROP TABLE map$i";
			mysqli_query($gamesConn, $query);
		}
	}

	$query = "TRUNCATE TABLE gameList";
	if(!mysqli_query($gamesConn, $query)) {
		echo mysqli_error($gamesConn);
	}	
	
	$query = "UPDATE constants SET value=0 WHERE name=\"gameIndex\"";
	if(!mysqli_query($accountsConn, $query)) {
		echo mysqli_error($accountsConn);
	}
} else {
	echo 'Authentification Error';
}

mysqli_close($accountsConn);
mysqli_close($gamesConn);

?>