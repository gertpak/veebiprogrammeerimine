<?php 
	require("functions.php");
	$notice = "";
  //kui pole sisse loginud
	if(!isset($_SESSION["userID"])) {
		header("Location: index.php");
		exit();
	}

  //väljalogimine
	if(isset($_GET["logout"])) {
	  session_destroy();
	  header("Location: index.php");
	  exit();
	}
	$mydescription = "Pole midagi lisatud";
	$mybgcolor = $_SESSION["bgColor"];
	$mytxtcolor = $_SESSION["txtColor"];
	
	if(isset($_POST["submitInfo"])){ 
		$notice = saveUserData($_POST["description"],$_POST["bgcolor"],$_POST["txtcolor"]);
		$mydescription = $_POST["description"];
		$mybgcolor = $_POST["bgcolor"];
		$mytxtcolor = $_POST["txtcolor"];
	} else {
		$userDatas = loadUserData();
		//var_dump($userDatas);
		if($userDatas != "error") {
			if($userDatas["desc"] != ""){
				$mydescription = $userDatas["desc"];
			}
			$mybgcolor = $userDatas["bgcol"];
			$mytxtcolor = $userDatas["txtcol"];
		}
	}
	
	
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Sõnumi lisamine</title>
	<style>
	 body{background-color: <?php echo $mybgcolor; ?>; 
	color: <?php echo $mytxtcolor; ?>} 
	</style>
</head>
<body>
	<h1>Sõnumi lisamine</h1>
	<p>Siin on minu <a href="http://tlu.ee/" target="_blank">TLÜ</a> õppetöö raames valminud veebilehed. Need ei oma mingit sügavat sisu ja nende kopeerimine ei oma mõtet.</p>
	<hr>
	<form method = "POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<textarea rows="10" cols="80" name="description"><?php echo $mydescription; ?></textarea><br>
	<label>Minu valitud taustavärv: </label>
	<input name="bgcolor" type="color" value="<?php echo $mybgcolor; ?>"><br>
	<label>Minu valitud tekstivärv: </label>
	<input name="txtcolor" type="color" value="<?php echo $mytxtcolor; ?>"><br>
	<input name = "submitInfo" type="submit" value="Kinnita andmed">
	</form>
	<br>
	<p> <?php echo $notice; ?>
	</p>
	<hr>
	<p><a href="main.php">Tagasi</a> avalehele! </br><b><a href = "?logout=1">Logi välja!</a></b></p>
</body>

</html>