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
	$target_dir = "../vpuser_picfiles/";
	$uploadOk = 1;
	$target_file = "";
	$imageFileType = "";
	if(isset($_POST["submitInfo"])){ 
		$notice = saveUserData($_POST["description"],$_POST["bgcolor"],$_POST["txtcolor"]);
		if(!empty($_POST["description"])){
	  		$mydescription = $_POST["description"];
		}
		$mybgcolor = $_POST["bgcolor"];
		$mytxtcolor = $_POST["txtcolor"];
		if(!empty($_FILES["fileToUpload"]["name"])){
		
			$imageFileType = strtolower(pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION));
			$timeStamp = microtime(1) * 10000;
			$target_file_name = "vpuser_" .$timeStamp ."." .$imageFileType;
			$target_file = $target_dir .$target_file_name;
						
			// kas on pilt, kontrollin pildi suuruse küsimise kaudu
			$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
			if($check !== false) {
				//echo "Fail on pilt - " . $check["mime"] . ".";
				$uploadOk = 1;
			} else {
				$noticeForm = "Fail ei ole pilt.";
				$uploadOk = 0;
			}
			
			// faili suurus
			if ($_FILES["fileToUpload"]["size"] > 2500000) {
				$noticeForm = "Kahjuks on fail liiga suur!";
				$uploadOk = 0;
			}
			
			// kindlad failitüübid
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
			&& $imageFileType != "gif" ) {
				$noticeForm = "Kahjuks on lubatud vaid JPG, JPEG, PNG ja GIF failid!";
				$uploadOk = 0;
			}
			
			// kui on tekkinud viga
			if ($uploadOk == 0) {
				$noticeForm = "Vabandame, faili ei laetud üles!";
			// kui kõik korras, laeme üles
			} else {
				//sõltuvalt failitüübist, loome pildiobjekti
				if($imageFileType == "jpg" or $imageFileType == "jpeg"){
					$myTempImage = imagecreatefromjpeg($_FILES["fileToUpload"]["tmp_name"]);
				}
				if($imageFileType == "png"){
					$myTempImage = imagecreatefrompng($_FILES["fileToUpload"]["tmp_name"]);
				}
				if($imageFileType == "gif"){
					$myTempImage = imagecreatefromgif($_FILES["fileToUpload"]["tmp_name"]);
				}
				
				//vaatame pildi originaalsuuruse
				$imageWidth = imagesx($myTempImage);
				$imageHeight = imagesy($myTempImage);
				//leian vajaliku suurendusfaktori, siin arvestan, et lõikan ruuduks!!!
				if($imageWidth > $imageHeight){
					$sizeRatio = $imageHeight / 300;//ruuduks lõikamisel jagan vastupidi
				} else {
					$sizeRatio = $imageWidth / 300;
				}
				
				$newWidth = round($imageWidth / $sizeRatio);
				$newHeight = $newWidth;
				$myImage = resizeImagetoSquare($myTempImage, $imageWidth, $imageHeight, $newWidth, $newHeight);
				
				//muudetud suurusega pilt kirjutatakse pildifailiks
				if($imageFileType == "jpg" or $imageFileType == "jpeg"){
				  if(imagejpeg($myImage, $target_file, 90)){
                    $noticeForm = "Korras!";
                    $myFileName = $target_file_name;
					addUserPhotoData($target_file_name);
				  } else {
					$noticeForm = "Pahasti!";
				  }
				}
				
				imagedestroy($myTempImage);
				imagedestroy($myImage);
				//imagedestroy($waterMark);
				
			}
		}
	} else {
		$userDatas = loadUserData();
		$userPicInfo = loadUserPic();
		//var_dump($userDatas);
		if($userDatas != "error") {
			if($userDatas["desc"] != ""){
				$mydescription = $userDatas["desc"];
			}
			$mybgcolor = $userDatas["bgcol"];
			$mytxtcolor = $userDatas["txtcol"];
		}
		$userPicInfo = loadUserPic();
		if($userPicInfo != "error") {
			$myFileName = $userPicInfo["file"];
			$myAltText = $userPicInfo["alttext"];
		}
		else {
			$myFileName = "vp_user_generic.png";
			$myAltText = "Kasutaja pole pilti veel laadinud.";
		}
	}
	function resizeImageToSquare($image, $ow, $oh, $w, $h){
		$newImage = imagecreatetruecolor($w, $h);
		if($ow > $oh){
			$cropX = round(($ow - $oh) / 2);
			$cropY = 0;
			$cropSize = $oh;
		} else {
			$cropX = 0;
			$cropY = round(($oh - $ow) / 2);
			$cropSize = $ow;
		}
    	//imagecopyresampled($newImage, $image, 0, 0 , 0, 0, $w, $h, $ow, $oh);
		imagecopyresampled($newImage, $image, 0, 0, $cropX, $cropY, $w, $h, $cropSize, $cropSize); 
		return $newImage;
 	}
 	$profilePic = $target_dir . $myFileName;
	$pageTitle = "Kasutajaprofiil";
	
	require("header.php");
	
	
?>

	<p>Siin on minu <a href="http://tlu.ee/" target="_blank">TLÜ</a> õppetöö raames valminud veebilehed. Need ei oma mingit sügavat sisu ja nende kopeerimine ei oma mõtet.</p>
	<hr>
	<div style="float: right">
	<img src="<?php echo $profilePic; ?>" alt="<?php echo $myAltText ?>">	  
	</div>  
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
	<p> <?php echo $noticeForm; ?></p>
	<hr>
	<p><a href="main.php">Tagasi</a> avalehele! </br><b><a href = "?logout=1">Logi välja!</a></b></p>
</body>

</html>