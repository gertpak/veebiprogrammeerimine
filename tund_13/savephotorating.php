<?php
	require("../../../config.php");
	$database = "if18_gertin_pa_1";
	session_start();
	//GET meetodiga saadetavad parameetrid
	$id = $_REQUEST["id"];
	$rating = $_REQUEST["rating"];

	$mysqli = new mysqli($serverHost, $serverUsername, $serverPassword, $database);
	$stmt = $mysqli->prepare("INSERT INTO vpphotoratings (photoid, userid, rating) VALUES (?,?,?)");
	$stmt->bind_param("iii", $id, $_SESSION["userID"], $rating);
	$stmt->execute();
	$stmt->close();
	//küsime uue keskmise hinde
	$stmt = $mysqli->prepare("SELECT AVG(rating) as AvgValue FROM vpphotoratings WHERE photoid=?");
	$stmt->bind_param("i", $id);
	$stmt->bind_result($score);
	$stmt->execute();
	$stmt->fetch();
	$stmt->close();
	$mysqli->close();
	//ümardan keskmise hinde 2 kohta pärast koma ja tagastan
	echo round($score, 2);
?>