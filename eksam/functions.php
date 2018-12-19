<?php
	require("config.php");
	$database = "if18_gertin_pa_1";
	
//Funktsioonid:
function test_input($data) {
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
	return $data;
}
	
function savePlantInfo($name, $type, $place) {
	$notice = "";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("INSERT INTO vpeksam (name, plantType, place) VALUES (?, ?, ?)");
	echo $mysqli->error;
	$stmt->bind_param("sss", $name, $type, $place);
	if ($stmt->execute()) {
		$notice = " Salvestamine läks edukalt!"; 
	} else { 
		echo " Sõnumi salvestamisel tekkis tõrge: ". $stmt->error; 
	}
	$stmt->close();
	$mysqli->close();
	return $notice;
}
function showPlantInfo(){
	$notice = "";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("SELECT id, name, plantType, place FROM vpeksam");
	echo $mysqli->error;
	$stmt->bind_result($id,$name,$type,$place);
	$stmt->execute();
	while($stmt->fetch()){
		$notice .= '<p><i>Nimi: </i><b><a href="showplants.php?name=' .$name .'">' .$name ."</a> </b><i>Liik:</i><b> " .$type . " </b><i>Vaatluskoht:</i><b> " .$place ."</b></p> \n";
	}
	$stmt->close();
	$mysqli->close();
	return $notice;
  }

  
function readUserPlants($name){
	$notice = "";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("SELECT id, plantType, place, date FROM vpeksam WHERE name=?");
	$stmt->bind_param("s", $name);
	$stmt->bind_result($id,$plantType,$place,$date);
	$stmt->execute();
	while($stmt->fetch()){
		$notice .= "<p><i>Taime liik:</i><b> " .$plantType . " </b><i>Vaatluskoht:</i><b> " .$place ."</b> <i>(" . $date .")</i></p> \n";
	}
	$stmt->close();
	$mysqli->close();
	return $notice;
}
function readAllUsersPlants($editName) {
	$msghtml = "";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("SELECT DISTINCT name FROM vpeksam WHERE name !=?");
	echo $mysqli->error;
	$stmt->bind_param("s", $editName);
	$stmt->bind_result($name);
	
	$stmt2 = $mysqli->prepare("SELECT COUNT(*) FROM vpeksam WHERE name=?");
	echo $mysqli->error;
	$stmt2->bind_param("s", $name);
	$stmt2->bind_result($count);
	
	$stmt->execute();
	$stmt->store_result();
	while ($stmt->fetch()) {
		$stmt2->execute();
		while($stmt2->fetch()) {
			$msghtml .= '<p><a href="showplants.php?name=' .$name .'">' .$name ." (" .$count .")</a></p> \n";
		}
	}
	$stmt->close();
	$mysqli->close();
	return $msghtml;
}
?>