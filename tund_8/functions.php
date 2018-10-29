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
		$stmt->store_result();
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
				$stmt2 = $mysqli->prepare("SELECT bgcolor, txtcolor FROM vpuserprofiles WHERE userid = ?");
				echo $mysqli->error;
				$stmt2->bind_param("i", $_SESSION["userID"]);
				$stmt2->bind_result($bgcolFromDb, $txtcolFromDb);
				$stmt2->execute();
				if($stmt2->fetch()){
					$_SESSION["bgColor"] = $bgcolFromDb;
					$_SESSION["txtColor"] = $txtcolFromDb;
				}
				else {
					$_SESSION["bgColor"] = "#FFFFFF";
					$_SESSION["txtColor"] = "#000000";
				}

				//liigume kohe sisselogitule mõeldud pealehele
				$stmt2->close();
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
  
 function validatemsg($idFroms, $validation){
	$notice = "";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("UPDATE vpamsg SET validator=?, valid=?, validated=now() WHERE id=?");
	echo $mysqli->error;
	$stmt->bind_param("iii",$_SESSION["userID"],$validation,$idFroms);
	if($stmt->execute()) {
        $notice = "OK";

    } else {
        $notice = "error". $stmt->error;
    }
    
	$stmt->close();
	$mysqli->close();
	header("Location: validatemsg.php");
	$notice = "Teie otsus on arvesse läinud, märge on salvestatud.";
	return $notice;
 }

//--------------------------------------valideeritud sõnumite lugemis koht-----------------------------------
function allvalidmessages() {
	$notice = "";
	$vaartus = 1;
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("SELECT message FROM vpamsg WHERE valid=? ORDER BY validated DESC");
	echo $mysqli->error;
	$stmt->bind_param("i",$vaartus);
	$stmt->bind_result($msg);
	$stmt->execute();
	while($stmt->fetch()){
		$notice .= "<li>" .$msg ."</li> \n";
	}
	$stmt->close();
	$mysqli->close();
	return $notice;

}
function readallvalidatedmessagesbyuser() {
	$msghtml = "";
	$msghtmlcorrect = "";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("SELECT id, firstname, lastname FROM vpusers");
	echo $mysqli->error;
	$stmt->bind_result($idFromDb, $firstnameFromDb, $lastnameFromDb);
	
	$stmt2 = $mysqli->prepare("SELECT message, valid FROM vpamsg WHERE validator=?");
	echo $mysqli->error;
	$stmt2->bind_param("i",$idFromDb);
	$stmt2->bind_result($msgFromDb, $validFromDb);
	
	$stmt->execute();
	$stmt->store_result(); // jätab saadu pikemalt meelde, nii saab ka järgmine päring seda kasutada.
	while ($stmt->fetch()) {
		//panen valideerija nime paika
		$msghtml .= "<h3>" .$firstnameFromDb ." " .$lastnameFromDb ."</h3> \n";
		$loendurUser = 0;
		$stmt2->execute();
		while($stmt2->fetch()) {
			$msghtml .="<p><b>";
			if($validFromDb == 0) {			
				$msghtml .= "Keelatud: ";
			} else {
				$msghtml .= "Lubatud: ";
			}
			$msghtml .= "</b>" .$msgFromDb ."</p> \n";
			$loendurUser ++;			
		}
		if($loendurUser > 0) {
			$msghtmlcorrect .= $msghtml;
		}
		$msghtml = "";
	}
	$stmt->close();
	$stmt2->close();
	$mysqli->close();
	return $msghtmlcorrect;
}

//---------------------------------------------Kasutajate liides --------------------------------------------------

function allusers() {
	$notice = "";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("SELECT firstname, lastname, email FROM vpusers WHERE id != ".$_SESSION['userID']."");
	echo $mysqli->error;
	//$stmt->bind_param("i",$id);
	$stmt->bind_result($firstname,$lastname,$email);
	$stmt->execute();
	while($stmt->fetch()) {
			$notice .= "<li>Nimi:" .$firstname ." ".$lastname .", email: ".$email ."</li> \n";
	}
	$stmt->close();
	$mysqli->close();
	return $notice;
}
function saveUserData($description,$bgcolor,$txtcolor) {
	$notice = "";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $mysqli->prepare("SELECT description, bgcolor, txtcolor FROM vpuserprofiles WHERE userid=?");
	echo $mysqli->error;
	$stmt->bind_param("i", $_SESSION["userID"]);
	$stmt->bind_result($descriptionFromDb, $bgcolorFromDb, $txtcolorFromDb);
	$stmt->execute();
	if($stmt->fetch()){
		$stmt->close();
		$stmt = $mysqli->prepare("UPDATE vpuserprofiles SET description=?, bgcolor=?, txtcolor=? WHERE userid=?");
		echo $mysqli->error;
		$stmt->bind_param("sssi", $description, $bgcolor, $txtcolor, $_SESSION["userID"]);
		if($stmt->execute()){
			$notice = "Profiil uuendatud!";
			$_SESSION["bgColor"] = $bgcolor;
			$_SESSION["txtColor"] = $txtcolor;
		} else {
			$notice = "Profiili uuendamisel tekkis viga! " .$stmt->error;
		}
	} else {
		$stmt->close();
		$stmt = $mysqli->prepare("INSERT INTO vpuserprofiles (userid, description, bgcolor, txtcolor) VALUES(?,?,?,?)");
		echo $mysqli->error;
		$stmt->bind_param("isss", $_SESSION["userID"], $description, $bgcolor, $txtcolor);
		if($stmt->execute()){
			$notice = "Profiil uuendatud!";
			$_SESSION["bgColor"] = $bgcolor;
			$_SESSION["txtColor"] = $txtcolor;
		} else {
			$notice = "Profiili uuendamisel tekkis viga! " .$stmt->error;
		}
	}
	$stmt->close();
	$mysqli->close();
	return $notice;
	
}
function loadUserData(){
	$profile = "";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("SELECT description,bgcolor,txtcolor FROM vpuserprofiles WHERE userid=?");
	echo $mysqli->error;
	$stmt->bind_param("i",$_SESSION["userID"]);
	$stmt->bind_result($descriptionFromDb,$bgcolorFromDb,$txtcolorFromDb);
	$stmt->execute();
	if($stmt->fetch()) {
		$profile = ["desc" => $descriptionFromDb,"bgcol" => $bgcolorFromDb,"txtcol" => $txtcolorFromDb];
		
    } else {
        $profile = "error". $stmt->error;
    }
	$stmt->close();
	$mysqli->close();
	return $profile;
}


?>