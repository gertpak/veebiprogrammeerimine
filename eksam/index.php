<?php
// Gertin Pakkonen - varjant 11 (Taimevaatlusmärkmik)
	require("functions.php");
 // Vajalikud muutujad:
	$name = "";
	$nameError = "";
	$type = "";
	$typeError = "";
	$plantPlace = "";
	$plantPlaceError = "";
	$notice = "";
	
//Kui saadab info ja kõik on korras:
	if(isset($_POST["submitInfo"])) {
		if(isset($_POST["name"]) and !empty($_POST["name"])) {
			$name = test_input($_POST["name"]);
		} else {
			$nameError = " Palun sisesta nimi!";
		}
		if(isset($_POST["plantType"]) and !empty($_POST["plantType"])) {
			$type = test_input($_POST["plantType"]);
		} else {
			$typeError = " Palun sisesta taime liik!";
		}
		if(isset($_POST["place"]) and !empty($_POST["place"])) {
			$plantPlace = test_input($_POST["place"]);
		} else {
			$plantPlaceError = " Palun sisesta taime vaatluskoht!";
		}
		if(empty($nameError) and empty($typeError) and empty($plantPlaceError)) {
			$notice = savePlantInfo($name, $type, $plantPlace);    
		}
	}
 ?>
 
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Taimevaatlusmärkmik - Gertin Pakkonen'i koduleht</title>
	</head>
	<body>
		<h1>Andmete sisestus</h1>
		<p>Siia saate lisada taimede kohta infot:</p>
		<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
			<label>Vaatleja nimi:</label>
			<input type="text" name="name" value="<?php echo $name; ?>">
			<label>Taime liik:</label>
			<input name="plantType" type="text" value="<?php echo $type; ?>">
			<label>Vaaltuskoht:</label>
			<input name="place" type="text" value="<?php echo $plantPlace; ?>"><br>
			<input name="submitInfo" type="submit" value="Saada info">&nbsp;<span><?php echo $notice; echo $plantPlaceError; echo $typeError; echo $nameError; ?></span>
		</form>
		<hr>
		<h1>Taimevaatlusmärkmik</h1>
		<div>
			<?php echo showPlantInfo(); ?>
		</div>
	</body>
</html>