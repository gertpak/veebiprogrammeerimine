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
	$noticeForm = " ";
	$mydescription = "Pole midagi lisatud, lisa siia enda kirjeldus.";
	$target_dir = "../userprofileuploads/";
	$uploadOk = 1;
	if(isset($_POST["submitInfo"])){ 
		$notice = saveUserData($_POST["description"],$_POST["bgcolor"],$_POST["txtcolor"]);
		$mydescription = $_POST["description"];
		$mybgcolor = $_POST["bgcolor"];
		$mytxtcolor = $_POST["txtcolor"];
		if(!empty($_FILES["fileToUpload"]["name"])){
		
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
		
			// Kas file on olemas00
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
				//sõltuvalt faili tüübist, loome pildiobjektid
				if($imageFileType == "jpg" or $imageFileType == "jpeg") {
					$myTempImage = imagecreatefromjpeg($_FILES["fileToUpload"]["tmp_name"]);
				}
				if($imageFileType == "png") {
					$myTempImage = imagecreatefrompng($_FILES["fileToUpload"]["tmp_name"]);
				}
				if($imageFileType == "gif") {
					$myTempImage = imagecreatefromgif($_FILES["fileToUpload"]["tmp_name"]);
				}
				//vaatame pildi originaalsuuruse
				$imageWidth = imagesx($myTempImage);
				$imageHeight = imagesy($myTempImage);
				//leian vajalikud suurendusfaktori
				if($imageWidth > $imageHeight) {
					$sizeRatio = $imageWidth / 600;
				} else {
					$sizeRatio = $imageHeight / 400;
				}
				$newWidth = round($imageWidth / $sizeRatio);
				$newHeight = round($imageHeight / $sizeRatio);	
				$myImage = resizeImage($myTempImage, $imageWidth, $imageHeight, $newWidth, $newHeight);
				
				//muudetud suurusega pilt kujutatakse pildifailiks
				if($imageFileType == "jpg" or $imageFileType == "jpeg") {
					if(imagejpeg($myImage, $target_file, 90)) {
						$noticeForm = " Korras! ";
						//kui pilt salvestati, siis lisame andmebaasi:
						addUserPhotoData($target_file_name);
					} else {
						$noticeForm = " Pahasti!ssss";
					}
				}
				if($imageFileType == "png") {
					if(imagepng($myImage, $target_file, 6)) {
						$noticeForm = " Korras!";
						addUserPhotoData($target_file_name);
					} else {
						$noticeForm = " Pahasti!";
					}
				}
				if($imageFileType == "gif") {
					if(imagegif($myImage, $target_file)) {
						$noticeForm = " Korras!";
						addUserPhotoData($target_file_name);
					} else {
						$noticeForm = " Pahasti!";
					}
				}
				imagedestroy($myTempImage);
				imagedestroy($myImage);
			}
		}
		else {
			$noticeForm = " Lisa palun fail, taun! ";
		}
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
	function resizeImage($image, $ow, $oh, $w, $h) {
		$newImage = imagecreatetruecolor($w,$h);
		imagecopyresampled($newImage, $image, 0, 0, 0, 0, $w, $h, $ow, $oh);
		return $newImage;
	}
	$pageTitle = "Kasutajaprofiil";
	
	require("header.php");
	
	
?>

	<p>Siin on minu <a href="http://tlu.ee/" target="_blank">TLÜ</a> õppetöö raames valminud veebilehed. Need ei oma mingit sügavat sisu ja nende kopeerimine ei oma mõtet.</p>
	<hr>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
	<textarea rows="10" cols="80" name="description"><?php echo $mydescription; ?></textarea><br>
	<label>Minu valitud taustavärv: </label>
	<input name="bgcolor" type="color" value="<?php echo $mybgcolor; ?>"><br>
	<label>Minu valitud tekstivärv: </label>
	<input name="txtcolor" type="color" value="<?php echo $mytxtcolor; ?>"><br>
	<label>Kasutaja foto:</label>
    <input type="file" name="fileToUpload" id="fileToUpload"><br>
	<input name = "submitInfo" type="submit" value="Kinnita andmed">
	</form>
	<br>
	<p> <?php echo $noticeForm; ?>
	</p>
	<hr>
	<p><a href="main.php">Tagasi</a> avalehele! </br><b><a href = "?logout=1">Logi välja!</a></b></p>
</body>

</html>