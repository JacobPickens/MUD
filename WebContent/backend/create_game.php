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

$hostID = $_POST['hostID'];

// Get current gameIndex
$query = "SELECT * FROM constants WHERE name=\"gameIndex\"";
$results = mysqli_query($accountsConn, $query);

$currentGameIndex = 0;

if(mysqli_num_rows($results) > 0) {
	while($row = mysqli_fetch_assoc($results)) {
		$currentGameIndex = $row['value'];
	}
}

// Create game tables
$query = "CREATE TABLE game$currentGameIndex (id bigint(20), username varchar(256), location int, inventory text);";
if(!mysqli_query($gamesConn, $query)) {
	echo "Error: " . mysqli_error($gamesConn);
}

$query = 	"CREATE TABLE global$currentGameIndex (" .
			"name varchar(256)," .
			"value int" .
			");";
mysqli_query($gamesConn, $query);

$query = 	"CREATE TABLE chat$currentGameIndex (" .
			"id int NOT NULL AUTO_INCREMENT," .
			"username varchar(256)," .
			"recipient text," . 
			"chat text," .
			"PRIMARY KEY (id)" .
			");";
mysqli_query($gamesConn, $query);

$query = 	"CREATE TABLE map$currentGameIndex (" .
			"id int NOT NULL AUTO_INCREMENT," .
			"type varchar(256)," .
			"x int," .
			"y int," .
			"name varchar(256)," .
			"numberOfSublocations int," .
			"sublocationJSON text," . 
			"PRIMARY KEY (id)" .
			");";
mysqli_query($gamesConn, $query);

// Add game to gameList
$joinCode = hash('sha256', $currentGameIndex);
echo "{\"gameIndex\":$currentGameIndex, \"joinCode\":\"$joinCode\"}";

$query = "INSERT INTO gameList(gameId, joinCode) VALUES($currentGameIndex, \"$joinCode\")";
if(!mysqli_query($gamesConn, $query)) {
	echo mysqli_error($gamesConn);
}

// Transfer host player over
$query = "SELECT * FROM accounts WHERE id=$hostID";
$results = mysqli_query($accountsConn, $query);

$hostUsername = "";
while($row = mysqli_fetch_assoc($results)) {
	$hostUsername = $row['username'];
}

$query = "INSERT INTO game$currentGameIndex(id, username, location, inventory) VALUES($hostID, \"$hostUsername\", 1, \"{\\\"inventory\\\":[]}\")";
if(!mysqli_query($gamesConn, $query)) {
	echo mysqli_error($gamesConn);
}

$query = "INSERT INTO global$currentGameIndex(name, value) VALUES(\"host\", $hostID); INSERT INTO global$currentGameIndex(name, value) VALUES(\"mapSize\", 3)";
mysqli_query($gamesConn, $query);

// Incrememnt gameIndex
$newGameIndex = $currentGameIndex + 1;
$query = "UPDATE constants SET value=$newGameIndex WHERE name=\"gameIndex\"";
mysqli_query($accountsConn, $query);

mysqli_close($accountsConn);
mysqli_close($gamesConn);
?>