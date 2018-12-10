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
	$error = "";
	if(isset($_POST["newsBtn"])){
		if(!empty($_POST["newsTitle"]) and !empty($_POST["newsEditor"]) and !empty($_POST["expiredate"])){
			saveNews($_POST["newsTitle"], $_POST["newsEditor"], $_POST["expiredate"]);
		} else {
			$error = " Palun täida kõik lüngad";
		}
	}
			
	//$publicThumbnails = readAllPublicPictureThumbsPage($page, $limit);
	$pageTitle = "Uudised";
	$scripts = '<script src="//cdn.tinymce.com/4/tinymce.min.js"></script><script>tinymce.init({selector:"textarea#newsEditor",plugins: "link",menubar: "edit",});</script>';

	require("header.php");
	
	$expiredateToday = date("Y-m-d");
?>


	<p>See leht on valminud <a href="http://www.tlu.ee" target="_blank">TLÜ</a> õppetöö raames ja ei oma mingisugust, mõtestatud või muul moel väärtuslikku sisu.</p>
	<hr>
	<p>Olete sisse loginud nimega: <?php echo $_SESSION["userFirstName"] ." " .$_SESSION["userLastName"]; ?>. <b><a href = "?logout=1">Logi välja!</a></b></p>
	<hr>
	<form method="POST" action="/~gertpak/veebiprogrammeerimine/tund_14/news.php">
		<label>Uudise pealkiri:</label><br><input type="text" name="newsTitle" id="newsTitle" style="width: 100%;" value=""><br>
		<label>Uudise sisu:</label><br>
		<textarea name="newsEditor" id="newsEditor"></textarea>
		<br>
		<label>Uudis nähtav kuni (kaasaarvatud)</label>
		<input type="date" name="expiredate" required pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" value="'<?php echo $expiredateToday; ?>'">
		
		<input name="newsBtn" id="newsBtn" type="submit" value="Salvesta uudis!"><?php echo $error; ?>
	</form>
	<hr>
	<h2>Päevakohased uudised</h2>
		<?php echo showNews();?>
	<hr>
	<p><a href="main.php">Tagasi</a> avalehele! </br><b><a href = "?logout=1">Logi välja!</a></b></p>
  </body>
</html>