<?php
  require("functions.php");
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
  $notice = allusers();
  $mybgcolor = $_SESSION["bgColor"];
  $mytxtcolor = $_SESSION["txtColor"];
  
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
	<title>Pealeht</title>
	<style>
    body{background-color: <?php echo $mybgcolor; ?>; 
    color: <?php echo $mytxtcolor; ?>} 
  </style>
  </head>
  <body>
    <h1>Pealeht</h1>
	<p>See leht on valminud <a href="http://www.tlu.ee" target="_blank">TLÜ</a> õppetöö raames ja ei oma mingisugust, mõtestatud või muul moel väärtuslikku sisu.</p>
	<hr>
	<p>Olete sisse loginud nimega: <?php echo $_SESSION["userFirstName"] ." " .$_SESSION["userLastName"]; ?>. <b><a href = "?logout=1">Logi välja!</a></b></p>
	<hr>
	<h2>Kasutajad sellel saidil:</h2>
	 <p> <?php echo $notice; ?></p>
	<hr>
	<p><a href="main.php">Tagasi</a> avalehele! </br><b><a href = "?logout=1">Logi välja!</a></b></p>
  </body>
</html>