<?php 


    require("functions.php");
    
    
    $tabel = readCatmessages();

	$notice = null;
    $catCol = null;
	//var_dump($_POST);
	if (isset($_POST["catName"])) {
		//$name = $_POST["firstName"];}
		$name = test_input($_POST["catName"]);}
		
	if (isset($_POST["catColor"])) {
		$surname = test_input($_POST["catColor"]);}
		
    
    if (isset($_POST["submitCatData"])){
		if(!empty($_POST["catName"]) and !empty($_POST["catColor"]) and !empty($_POST["catLenght"])) {
			$notice = "Sõnum olemas!";
            $notice = saveACatData($_POST["catName"], $_POST["catColor"], $_POST["catLenght"]);
			//$notice = saveAMsg($_POST["message"]);
		}
		else 
        {
			$notice = "Palun kirjutage sõnum!";
        }
    }


	
	
	?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>kiisud</title>

</head>
<body>
	<h1>Kiisude leht</h1>
	<p>Siin on minu <a href="http://tlu.ee/" target="_blank">TLÜ</a> õppetöö raames valminud veebilehed. Need ei oma mingit sügavat sisu ja nende kopeerimine ei oma mõtet.</p>
	<hr>
	<form method = "POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<label for="catName">Kiisu nimi: </label>
		<input name = "catName" type="text" value="">
		<label for="catColor">Värvus: </label>
		<input name = "catColor" type="text" value="">
		<label for="catLenght">Saba pikkus: </label>
		<input name = "catLenght" type="number" value="">
		
		<input name = "submitCatData" type="submit" value="Saada andmed">
	
	</form>
    <?php
		echo $notice;
    ?><hr><?php
        echo $tabel;
	?>

	
</body>

</html>