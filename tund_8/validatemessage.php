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
  
  if(isset($_GET["id"])) {
	  $msg = readmsgforvalidation($_GET["id"]);
  }
  if(isset($_POST["submitValidation"])){
	$notice = "Muudatus salvestatud!";
	$notice = validatemsg($_POST["id"],$_POST["validation"]);
	 //header("Location: validatemsg.php");
  }
    $pageTitle = "Sõnumid";
  
  require("header.php");

  
  
?>


  <p>Siin on minu <a href="http://www.tlu.ee">TLÜ</a> õppetöö raames valminud veebilehed. Need ei oma mingit sügavat sisu ja nende kopeerimine ei oma mõtet.</p>
  <hr>
  <ul>

	<li><a href="validatemsg.php">Tagasi</a> sõnumite lehele!</li>
  </ul>
  <hr>
  <h2>Valideeri see sõnum:</h2>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <input name="id" type="hidden" value="<?php echo $_GET["id"]; ?>">
    <p><?php echo $msg; ?></p>
    <input type="radio" name="validation" value="0" checked><label>Keela näitamine</label><br>
    <input type="radio" name="validation" value="1"><label>Luba näitamine</label><br>
    <input type="submit" value="Kinnita" name="submitValidation">
  </form>

  <hr>

</body>
</html>
