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
	if(isset($_POST["submitPic"])) {	//Kas vajutati submit nuppu
		//kas failinimi ka olemas on
		if(!empty($_FILES["fileToUpload"]["name"])) {
			//var_dump($_FILES);
			//$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
			$imageFileType = strtolower(pathinfo(basename($_FILES["fileToUpload"]["name"]),PATHINFO_EXTENSION));
			//ajatempel
			$timeStamp = microtime(1) * 10000;
			//$target_file = $target_dir .basename($_FILES["fileToUpload"]["name"]) ."_" .$timeStamp ."." .$imageFileType;
			$target_file_name = "vp_" .$timeStamp ."." .$imageFileType;
			$target_file = $target_dir . $target_file_name;
			
			//$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
			
			// Kas on pilt
			$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
			if($check !== false) {
				$noticeForm = " Fail on pilt - " . $check["mime"] . ". ";
				$uploadOk = 1;
			} else {
				$noticeForm = " Fail ei ole pilt. ";
				$uploadOk = 0;
			}
		
			// Kas file on olemas
			if (file_exists($target_file)) {
				$noticeForm = " Kahjuks on selline pilt juba olemas. ";
				$uploadOk = 0;
			}
			// Faili suurus
			if ($_FILES["fileToUpload"]["size"] > 2500000) {
				$noticeForm = " Kahjuks on fail liiga suur. ";
				$uploadOk = 0;
			}
			// Saab muuta lubatud formaate
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
			&& $imageFileType != "gif" ) {
				$noticeForm = " Kahjuks on lubatud vaid JPG, JPEG, PNG ja GIF failid. ";
				$uploadOk = 0;
			}
			// Kui $uploadOk on muudetud 0'iks mõne errori poolt
			if ($uploadOk == 0) {
				$noticeForm = " Kahjuks seda faili ei laetud üles. ";
			// Kui kõik korras, laeme üles
			} else {
				$myPhoto = new Photoupload($_FILES["fileToUpload"]["tmp_name"], $imageFileType);
				$myPhoto->resizeImage(600, 400);
				$myPhoto->addWatermark();
				$myPhoto->addText();
				$saveResult = $myPhoto->savePhoto($target_file);
				if($saveResult == 1) {
					$noticeForm = " Üleslaadimine läks edukalt!";
					addPhotoData($target_file_name, $_POST["altText"], $_POST["privacy"]);
				} else {
					$noticeForm = " Kuskil läks midagi pahasti, proovi palun uuesti!";
				}
				unset($myPhoto);
			}
		} else {
			$noticeForm = " Lisa palun fail, taun! ";
		}
	}//Kas on nuppu vajutatud
  //Lehe päise üleslaadimise osa
	function resizeImage($image, $ow, $oh, $w, $h) {
		$newImage = imagecreatetruecolor($w,$h);
		imagecopyresampled($newImage, $image, 0, 0, 0, 0, $w, $h, $ow, $oh);
		return $newImage;
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