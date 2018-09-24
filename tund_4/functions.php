<?php
require("../../../config.php");
//echo $GLOBALS["serverHost"];
//echo $GLOBALS["serverUsername"];
//echo $GLOBALS["serverPassword"];
$database = "if18_gertin_pa_1";

function saveAMsg($msg){
	//echo "Töötab!";
	$notice = ""; //See on  teade, mis antakse salvestamise kohta
	//loome ühenduse andmebaasiserveriga
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	//Valmistame ette SQL päringu
	$stmt = $mysqli->prepare("INSERT INTO vpamsg (message) VALUES (?)");
	echo $mysqli->error;
	$stmt->bind_param("s", $msg);//s - string, i - integer, d - decimal
	if ($stmt->execute()) {
		$notice = 'Sõnum: "'. $msg .'" on salvestatud.'; 
	} 
	else { 
		$notice = "Sõnumi salvestamisel tekkis tõrge: ". $stmt->error; 
	}
	$stmt->close();
	$mysqli->close();
	return $notice;
}
?>