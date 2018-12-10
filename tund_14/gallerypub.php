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
	$page = 1;
	$totalImages = findTotalPublicImages();
	$limit = 5;
	if(!isset($_GET["page"]) or $_GET["page"] < 1){
		$page = 1;
	} elseif (round(($_GET["page"] - 1) * $limit) > $totalImages){
		$page = round($totalImages / $limit) - 1;
	} else {
		$page = $_GET["page"];
	}
	//$publicThumbnails = readAllPublicPictureThumbs();
	$publicThumbnails = readAllPublicPictureThumbsPage($page, $limit);
	$pageTitle = "Pildigalerii";
	$scripts = '<link rel="stylesheet" type="text/css" href="style/modal.css">' ."\n" .'<script type="text/javascript" src="javascript/modal.js" defer></script>' ."\n";

	require("header.php");
?>


	<p>See leht on valminud <a href="http://www.tlu.ee" target="_blank">TLÜ</a> õppetöö raames ja ei oma mingisugust, mõtestatud või muul moel väärtuslikku sisu.</p>
	<hr>
	<p>Olete sisse loginud nimega: <?php echo $_SESSION["userFirstName"] ." " .$_SESSION["userLastName"]; ?>. <b><a href = "?logout=1">Logi välja!</a></b></p>
	<hr>
	<p><a href="main.php">Tagasi</a> avalehele! </br><b><a href = "?logout=1">Logi välja!</a></b></p>
	<hr>
	<!-- The Modal W3Schools eeskujul-->
	<div id="myModal" class="modal">
		<span class="close">&times;</span>
		<img class="modal-content" id="modalImg">
		<div id="caption"></div>
		<div id="rating" class="modalcaption">
			<label><input id="rate1" name="rating" type="radio" value="1">1</label>
			<label><input id="rate2" name="rating" type="radio" value="2">2</label>
			<label><input id="rate3" name="rating" type="radio" value="3">3</label>
			<label><input id="rate4" name="rating" type="radio" value="4">4</label>
			<label><input id="rate5" name="rating" type="radio" value="5">5</label>
			<input name="storeRating" type="button" value="Salvesta hinnang" id="storeRating">
			<span id = "avgRating"></span>
		</div>
	</div>
	<div id = "gallery">
	<h2><?php echo $pageTitle; ?></h2>
	 <?php
		echo "<p>";
		if ($page > 1){
			echo '<a href="?page=' .($page - 1) .'">Eelmised pildid</a> ';
		} else {
			echo "<span>Eelmised pildid</span> ";
		}
		if ($page * $limit < $totalImages){
			echo '| <a href="?page=' .($page + 1) .'">Järgmised pildid</a>';
		} else {
			echo "| <span>Järgmised pildid</span>";
		}
		echo "</p> \n";
		echo $publicThumbnails;
	?>
	</div>
  </body>
</html>