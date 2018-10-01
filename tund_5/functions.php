<?php
require("../../../config.php");
//echo $GLOBALS["serverHost"];
//echo $GLOBALS["serverUsername"];
//echo $GLOBALS["serverPassword"];
$database = "if18_gertin_pa_1";

function test_input($data) {
    //echo "koristan!\n";
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
return $data;}

// --------------------------------------- Sõnumi loomine ------------------------------------------
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

function readallmessages(){
	$notice = "";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("SELECT message FROM vpamsg");
	echo $mysqli->error;
	$stmt->bind_result($msg);
	$stmt->execute();
	while($stmt->fetch()){
		$notice .= "<p>" .$msg ."</p> \n";
	}
	$stmt->close();
	$mysqli->close();
	return $notice;
  }


//---------------------------------------- Kassi osa ----------------------------------------------------

function saveACatData($catName, $catColor, $catLength){
	//echo "Töötab!";
	$notice = ""; //See on  teade, mis antakse salvestamise kohta
	//loome ühenduse andmebaasiserveriga
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	//Valmistame ette SQL päringu
	$stmt = $mysqli->prepare("INSERT INTO kiisu (nimi, v2rvus, saba) VALUES (?, ?, ?)");
	echo $mysqli->error;
	$stmt->bind_param("ssi", $catName, $catColor, $catLength);//s - string, i - integer, d - decimal
	if ($stmt->execute()) {
		$notice = 'Kass "'. $catName .'" on salvestatud. (värvus: '. $catColor .', saba pikkus: '. $catLength .' sentimeetrit.)'; 
	} 
	else { 
		$notice = "Sõnumi salvestamisel tekkis tõrge: ". $stmt->error; 
	}
	$stmt->close();
	$mysqli->close();
	return $notice;
}
function readCatmessages(){
	$tabel = "";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("SELECT nimi, v2rvus, saba FROM kiisu");
	echo $mysqli->error;
	$stmt->bind_result($readcatname, $readcatcolor, $readcattaillength);
	$stmt->execute();
	while($stmt->fetch()){
		$tabel .= "<p>Nimi:   " .$readcatname .", värvus:   ". $readcatcolor ." ja saba pikkus(cm):   ". $readcattaillength ."</p> \n";
	}
	$stmt->close();
	$mysqli->close();
	return $tabel;
  }

//--------------------------------- Logimissüsteemi loomine -----------------------------------------------------


function signup($name, $surname, $email, $gender, $birthDate, $password) {
    $notice = "";
    $mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $mysqli->prepare("INSERT INTO vpusers (firstname, lastname, birthdate, gender, email, password) VALUES (?,?,?,?,?,?)");
    echo $mysqli -> error;
    //krüpteerin parooli, kasutades juhuslikku soolamisfraasi(salting string)
    $options = [
        "cost" => 12,
        "salt" => substr(sha1(rand()), 0, 22)
    ];
    $pwdhash = password_hash($password, PASSWORD_BCRYPT, $options);
    $stmt->bind_param("sssiss", $name, $surname, $email, $gender, $birthDate, $pwdhash);
    if($stmt->execute()) {
        $notice = "OK";
    } else {
        $notice = "error". $stmt->error;
    }
    $stmt->close();
	$mysqli->close();
	return $notice;
    
}
?>