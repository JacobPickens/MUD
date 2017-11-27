<?php 

$host = "localhost";
$database = "id3595091_games";
$user = "id3595091_games";
$password = file_get_contents("res.txt");

$conn = mysqli_connect($host, $user, $password, $database);

if(!$conn) {
	die("Connection failed: " + mysqli_connect_error());
}

$playerId = $_GET['id'];
$gameIndex = $_GET['gameIndex'];
$location = $_GET['location'];
$sublocation = $_GET['sublocation'];

$query = "SELECT * FROM map$gameIndex WHERE id=$location";
$results = mysqli_query($conn, $query) or die("Error: " . mysqli_error());

if(mysqli_num_rows($results) > 0) {
	$sublocationJSON = "";
	while($row = mysqli_fetch_assoc($results)) {
		$sublocationJSON = $row['sublocationJSON'];
	}
	
	if($sublocationJSON != "") {
		$sublocationObject = json_decode($sublocationJSON);
		$sublocations = $sublocationObject->sublocations;
		for($i = 0; $i < sizeof($sublocations); $i++) {
			if($sublocations[$i]->id == $sublocation) { // Found sublocation
				$query = "SELECT * FROM game$gameIndex WHERE id=$playerId";
				$results = mysqli_query($conn, $query) or die("Error: " . mysqli_error());
				
				$inventoryJSON = "";
				
				if(mysqli_num_rows($results) > 0) {
					while($row = mysqli_fetch_assoc($results)) {
						$inventoryJSON = $row['inventory'];
					}
					
					$inventoryObject = json_decode($inventoryJSON);
					
					// Generate Item
					$query = "SELECT * FROM items WHERE id=1";
					$results = mysqli_query($conn, $query) or die("Error: " . mysqli_error());
					
					$id = null;
					$name = null;
					$image = null;
					while($row = mysqli_fetch_assoc($results)) {
						$id = $row['id'];
						$name = $row['name'];
						$image = $row['image'];
					}
					
					if($id != null && $name != null && $image != null) {
						$itemObject = new stdClass();
						$itemObject->id = $id;
						$itemObject->name = $name;
						$itemObject->image = $image;
						
						array_push($inventoryObject->inventory, $itemObject);
						$newInventory = str_replace("\"", "\\\"", json_encode($inventoryObject));
						
						$query = "UPDATE game$gameIndex SET inventory=\"$newInventory\" WHERE id=$playerId";
						if(!mysqli_query($conn, $query)) {
							echo mysqli_error($conn);
						}
						
						echo "You found a " . $name . "!";
					} else {
						echo 'invaliditem';
					}
				} else {
					echo 'invalidplayer';
				}
			}
		}
	} else {
		echo 'invalidlocal';
	}
} else {
	echo 'invalidglobal';
}

mysqli_close($conn);

?>