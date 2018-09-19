<?php 
	$name = "Tundmatu";
	$surname = "inimene";
	$eriala = "teadmata";
	
	//var_dump($_POST);
	if (isset($_POST["firstName"])) {
		$name = $_POST["firstName"];}
		
	if (isset($_POST["surName"])) {
		$surname = $_POST["surName"];}
		
	if (isset($_POST["eriala"])) {	
		$eriala = $_POST["eriala"];}
	
	
	?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>
	<?php
		echo $name;
		echo " ";
		echo $surname;
		echo ",";?> õppetöö</title>

</head>
<body>
	<h1><?php echo $name . " " . $surname.", ". $eriala. " tudeng."; ?></h1>
	<p>Siin on minu <a href="http://tlu.ee/" target="_blank">TLÜ</a> õppetöö raames valminud veebilehed. Need ei oma mingit sügavat sisu ja nende kopeerimine ei oma mõtet.</p>
	<hr>
	<form method = "POST">
		<label>Eesnimi: </label>
		<input name = "firstName" type="text" value="">
		<label for="surName">Perekonnanimi: </label>
		<input name = "surName" type="text" value="">
		<label for= "birthYear">Sünniaasta: </label>
		<input name = "birthYear" type = "number" min = "1924" max = "2003" value = "1996">
	
		
		<label for="sunnikuu">Sünnikuu: </label>
		<select id="sunnikuu" name="sunnikuu">
			<option value="jaanuar">jaanuar</option>
			<option value="veebruar">veebruar</option>
			<option value="marts">märts</option>
			<option value="aprill">aprill</option>
			<option value="mai">mai</option>
			<option value="juuni">juuni</option>
			<option value="juuli">juuli</option>
			<option value="august">august</option>
			<option selected = "selected" value="september">september</option>
			<option value="oktoober">oktoober</option>
			<option value="november">november</option>
			<option value="detsember">detsember</option>
		</select>
			
		<label for="eriala">Eriala: </label>
		<select id="eriala" name="eriala">
			<option value="informaatika">informaatika</option>
			<option value="infoteadus">infoteadus</option>
			<option value="matemaatika">matemaatika</option>
		</select><br>
			
		</select><br>
		
		<input name = "submitUserData" type="submit" value="Saada andmed">
	
	</form>
	
	<?php 
		if (isset($_POST["firstName"]))
		{
			echo "<br>Olete sündinud ". $_POST["sunnikuu"]." ". $_POST["birthYear"].". aastal.";
			echo "<br><p>Olete elanud järgnevatel aastatel:</p>";
			echo "<ul> \n";
			for ($i = $_POST["birthYear"]; $i <= date("Y"); $i ++)
			{
				echo "<li>". $i ."</li> \n"; 
			}
		
			echo "</ul> \n";
		}
	?>
	
</body>

</html>