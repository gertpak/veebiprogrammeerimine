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
	$uploadOk = 1;
	//$imageFileType = "";
	$imageNamePrefix = "vp_";
    $pathToWatermark = "../vp_picfiles/vp_logo_color_w100_overlay.png";
	if(isset($_POST["submitPic"])) {
		if(!empty($_FILES["fileToUpload"]["name"])){
		
            $myPhoto = new Photoupload($_FILES["fileToUpload"]);
			$myPhoto->readExif();
			$myPhoto->makeFileName($imageNamePrefix);
			$target_file = $target_dir .$myPhoto->fileName;
			
			$uploadOk = $myPhoto->checkForImage();
			if($uploadOk == 1){
				$uploadOk = $myPhoto->checkForFileType();
			}

			if($uploadOk == 1){
				$uploadOk = $myPhoto->checkForFileSize($_FILES["fileToUpload"], 2500000);
			}

			if($uploadOk == 1){
				$uploadOk = $myPhoto->checkIfExists($target_file);
			}
					
			if ($uploadOk == 0) {
				$noticeForm = "Vabandame, faili ei laetud üles! Tekkisid vead: ".$myPhoto->errorsForUpload;
			} else {
				$myPhoto->resizeImage(600, 400);
				$myPhoto->addWatermark($pathToWatermark, "ld");
				//$myPhoto->addText($_SESSION["userFirstName"] ." ". $_SESSION["userLastName"]);
				$myPhoto->addText("Pildil puudub kuupäev");
				$saveResult = $myPhoto->savePhoto($target_file);
				if($saveResult == 1){
					$myPhoto->createThumbnail("../thumbuploads/",200);
					$noticeForm = " Üleslaadimine läks edukalt! ";
					$noticeForm .= addPhotoData($myPhoto->fileName, $_POST["altText"], $_POST["privacy"]);
				} else {
					$noticeForm .= "Foto lisamisel andmebaasi tekkis viga! ";
				}
				
			}
			unset($myPhoto);
		}
	}
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