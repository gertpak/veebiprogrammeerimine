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
  
  $notice = readallunvalidatedmessages();
  $pageTitle = "Anonüümsed sõnumid";
  
  require("header.php");

  
  
?>


  <p>Siin on minu <a href="http://www.tlu.ee">TLÜ</a> õppetöö raames valminud veebilehed. Need ei oma mingit sügavat sisu ja nende kopeerimine ei oma mõtet.</p>
  <hr>
  <h2>Valideerimata sõnumid:</h2>
  <p><?php echo $notice; ?></p>
  <hr>
  <p><a href="main.php">Tagasi</a> avalehele! </br><b><a href = "?logout=1">Logi välja!</a></b></p>
</body>

</html>