<?php 

    require("functions.php");
	$name = "Tundmatu";
	$surname = "inimene";
	$eriala = "teadmata";
	$fullName = $name ." ". $surname;
	$birthMonth = date ("m");
	$monthNamesET = ["jaanuar", "veebruar", "märts", "aprill", "mai", "juuni", "juuli", "august", "september", "oktoober", "november", "detsember"]; 
	
	//var_dump($_POST);
	if (isset($_POST["firstName"])) {
		//$name = $_POST["firstName"];}
		$name = test_input($_POST["firstName"]);}
		
	if (isset($_POST["surName"])) {
		$surname = test_input($_POST["surName"]);}
		
	if (isset($_POST["eriala"])) {	
		$eriala = $_POST["eriala"];}
		
	
	function fullName() {
		$GLOBALS["fullName"] = $GLOBALS["name"]. " " .$GLOBALS["surname"];}
		//echo $fullName;
	
	
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
	<form method = "POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<label>Eesnimi: </label>
		<input name = "firstName" type="text" value="">
		<label for="surName">Perekonnanimi: </label>
		<input name = "surName" type="text" value="">
		<label for= "birthYear">Sünniaasta: </label>
		<input name = "birthYear" type = "number" min = "1924" max = "2003" value = "1996">
	
		
		<label for="sunnikuu">Sünnikuu: </label>
		<form method = "selected">
		<?php
			echo '<select name="birthMonth">' ."\n";
			for ($i = 1; $i < 13; $i ++){
				echo '<option value="' .$i .'"';
				if ($i == $birthMonth){
					echo " selected ";
				}
				echo ">" .$monthNamesET[$i - 1] ."</option> \n";
			}
			echo "</select> \n";
		?>
 			<!--<option value="1">jaanuar</option>
			<option value="2">veebruar</option>
			<option value="3">märts</option>
			<option value="4">aprill</option>
			<option value="5">mai</option>
			<option value="6">juuni</option>
			<option value="7">juuli</option>
			<option value="8">august</option>
			<option selected = "selected" value="9">september</option>
			<option value="10">oktoober</option>
			<option value="11">november</option>
			<option value="12">detsember</option>
		</select>-->
			
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
			//demoks üks funktsioon
			fullname();
			echo "<br><p>". $fullName .". Olete sündinud ". $_POST["birthYear"].". aastal.</p>";
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