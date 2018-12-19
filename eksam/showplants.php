<?php
	require("functions.php");
	
	if(isset($_GET["name"])) {
		$userInfo = readUserPlants($_GET["name"]);
	}
	$otherUsersInfo = readAllUsersPlants($_GET["name"]);
 ?>
 
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title><?php echo $_GET["name"]; ?></title>
	</head>
	<body>
		<h1><?php echo $_GET["name"]; ?> lisatud andmed:</h1>
		<p><?php echo $userInfo; ?></p>
		<hr>
		<h2>Teisi loodushuvilisi sellel lehel: </h2>
		<p><?php echo $otherUsersInfo; ?></p>
		<hr>
		<p><a href="index.php">Tagasi</a> avalehele!</p>
	</body>
</html>