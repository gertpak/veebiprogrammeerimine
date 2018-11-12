<?php
	require("functions.php");
	//kui pole sisse loginud
	if(!isset($_SESSION["userID"])) {
		header("Location: index.php");
		exit();
	}

	require("classes/Photoupload.class.php");
	
	$noticeForm = " ";
  //väljalogimine
	if(isset($_GET["logout"])) {
		session_destroy();
		header("Location: index.php");
		exit();
	}
	//Pildi üleslaadimise osa
	$target_dir = "../picuploads/";
	$ifSuccess = 1;
	if(isset($_POST["submitPic"])) {
		if(!empty($_FILES["fileToUpload"]["name"])) {
			$imageFileType = strtolower(pathinfo(basename($_FILES["fileToUpload"]["name"]),PATHINFO_EXTENSION));
			$timeStamp = microtime(1) * 10000;
			$target_file_name = "vp_" .$timeStamp ."." .$imageFileType;
			$target_file = $target_dir . $target_file_name;
		
			$myPhoto = new Photoupload($_FILES["fileToUpload"]["tmp_name"], $imageFileType);
			$ifSuccess = $myPhoto->checkIfPicture($target_file, $_FILES["fileToUpload"]["size"]);
			if ($ifSuccess == 0) {
				$noticeForm = " Kahjuks tekkis kuskil mingi viga. Kontrollige oma faili formaati ja suurust!";
			} else {
				$myPhoto->resizeImage(600, 400);
				$myPhoto->addWatermark("ld"); //ld - left down, rd - right down, c - center, lu - left up, ru - right up
				$myPhoto->addText($_SESSION["userFirstName"] ." ". $_SESSION["userLastName"]);
				$saveResult = $myPhoto->savePhoto($target_file);
				if($saveResult == 1) {
					$noticeForm = " Üleslaadimine läks edukalt!";
					addPhotoData($target_file_name, $_POST["altText"], $_POST["privacy"]);
				} else {
					$noticeForm = " Kuskil läks midagi pahasti, proovi palun uuesti!";
				}
			}
			unset($myPhoto);
		} else {
			$noticeForm = " Lisa palun fail, taun! ";
		}
	}//Kas on nuppu vajutatud
	$pageTitle = "Fotode üleslaadimine";
  
	require("header.php");
  
?>


	<p>See leht on valminud <a href="http://www.tlu.ee" target="_blank">TLÜ</a> õppetöö raames ja ei oma mingisugust, mõtestatud või muul moel väärtuslikku sisu.</p>
	<hr>
	<p><b><a href = "?logout=1">Logi välja!</a></b></p>
	<h2>Foto üleslaadimine:</h2>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
    <label>Vali üleslaetav pilt:</label>
    <input type="file" name="fileToUpload" id="fileToUpload"><br>
	<label>Kirjeldus: </label><input type="text" name = "altText"><br>
	<label>Privaatsus: </label><br><input type="radio" name = "privacy" value="1"><label>Avalik</label>&nbsp; <input type="radio" name = "privacy" value="2"><label>Sisseloginud kasutajatele</label>&nbsp; <input type="radio" name = "privacy" value="3" checked><label>Isiklik</label><br> 
    <input type="submit" value="Lae pilt üles" name="submitPic"><span><?php echo $noticeForm; ?></span><br>
</form>
	
  </body>
</html>