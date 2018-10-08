<?php
require("../../../config.php");
$database = "if18_gertin_pa_1";

//alustan sessiooni
session_start();

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

function signin($email, $password) {
	$notice = "";
    $mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);	
	$stmt = $mysqli->prepare("SELECT id, firstname, lastname, password FROM vpusers WHERE email=?");
	echo $mysqli->error;
    $stmt->bind_param("s", $email);
	$stmt->bind_result($idFromDb, $firstnameFromDb, $lastnameFromDb, $passwordFromDb);
	if($stmt->execute()) {
		//kui päring õnnestus
		
		if($stmt->fetch()) {
			//kasutaja on olemas
			
			if(password_verify($password, $passwordFromDb)) {
				//kui salasõna klapib
				$notice = "Logisite sisse!";
				//määran sessiooni muutujad
				$_SESSION["userID"] = $idFromDb;
				$_SESSION["userFirstName"] = $firstnameFromDb;
				$_SESSION["userLastName"] = $lastnameFromDb;
				$_SESSION["userEmail"] = $email;
				//liigume kohe sisselogitule mõeldud pealehele
				$stmt->close();
				$mysqli->close();
				header("Location: main.php");
				exit();
			} else {
				$notice = "Vale salasõna!";
			}
		} else {
			$notice = "Sellist kasutajat (" .$email .") ei leitud!"; 
		}
	} else {
		$notice = "Sisselogimisel tekkis tehniline viga!" .$stmt->error;
	}
	
	$stmt->close();
	$mysqli->close();
	return $notice;
} //sisselogimine lõppeb

function signup($name, $surname, $email, $gender, $birthDate, $password) {
    $notice = "";
    $mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $mysqli->prepare("SELECT id FROM vpusers WHERE email=?");
    echo $mysqli->error;
    $stmt->bind_param("s",$email);
    $stmt->execute();
    if ($stmt->fetch()) {
    	$notice = "Sellise kasutajatunnusega (" .$email .") kasutaja on juba olemas! Uut kasutajat ei salvestatud!";
    } else {
		$stmt->close();
    	$stmt = $mysqli->prepare("INSERT INTO vpusers (firstname, lastname, birthdate, gender, email, password) VALUES (?,?,?,?,?,?)");
    	echo $mysqli -> error;
    	//krüpteerin parooli, kasutades juhuslikku soolamisfraasi(salting string)
    	$options = [
        "cost" => 12,
        "salt" => substr(sha1(rand()), 0, 22)
    	];
    	$pwdhash = password_hash($password, PASSWORD_BCRYPT, $options);
		echo "Kuupäev: ".$birthDate;
    	$stmt->bind_param("sssiss", $name, $surname, $birthDate, $gender, $email, $pwdhash);
    	if($stmt->execute()) {
        	$notice = "OK";
    	} else {
        	$notice = "error". $stmt->error;
    	}

    }
	$stmt->close();
	$mysqli->close();
	return $notice;
    
}

//---------------------------------- Valideerimata sõnumite regamine-----------------------------------

function readallunvalidatedmessages(){
	$notice = "<ul> \n";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("SELECT id, message FROM vpamsg WHERE valid IS NULL ORDER BY id DESC");
	echo $mysqli->error;
	$stmt->bind_result($id, $msg);
	$stmt->execute();
	
	while($stmt->fetch()){
		$notice .= "<li>" .$msg .'<br><a href="validatemessage.php?id=' .$id .'">Valideeri</a>' ."</li> \n";
	}
	$stmt->close();
	$mysqli->close();
	return $notice;
  }
  
  function readmsgforvalidation($editId){
	$notice = "";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("SELECT message FROM vpamsg WHERE id = ?");
	$stmt->bind_param("i", $editId);
	$stmt->bind_result($msg);
	$stmt->execute();
	if($stmt->fetch()){
		$notice = $msg;
	}
	$stmt->close();
	$mysqli->close();
	return $notice;
  }
?>