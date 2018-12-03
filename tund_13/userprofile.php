<?php 
	require("functions.php");
	require("classes/Photoupload.class.php");
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
	$profilePicId = NULL;
	$picSize = 200;
	$imageNamePrefix = "vpuser_";
	$addedPhotoId = null;
  
	$target_file = "";
	$uploadOk = 1;
	if(isset($_POST["submitInfo"])){ 
		$notice = saveUserData($_POST["description"],$_POST["bgcolor"],$_POST["txtcolor"]);
		if(!empty($_POST["description"])){
	  		$mydescription = $_POST["description"];
		}
		$mybgcolor = $_POST["bgcolor"];
		$mytxtcolor = $_POST["txtcolor"];
		if(!empty($_FILES["fileToUpload"]["name"])){
		
			$myPhoto = new Photoupload($_FILES["fileToUpload"]);
			$myPhoto->makeFileName($imageNamePrefix);
			$target_file = $profilePicDir .$myPhoto->fileName;
						
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
				$notice = "Vabandame, profiilipildi faili ei laetud üles!";
				
			} else {
				$saveSuccess = $myPhoto->createThumbnail($profilePicDir, $picSize);
				$addedPhotoId = addUserPhotoData($myPhoto->fileName);
				$profilePic = $target_file;
			}
		} else {
			$profilePic = $_POST["profilepic"];
		}
		$notice = saveUserData($_POST["description"], $_POST["bgcolor"], $_POST["txtcolor"]);

			
	} else {
		$userDatas = loadUserData();
		//$userPicInfo = loadUserPic();
		//var_dump($userDatas);
		if($userDatas != "error") {
			if($userDatas["desc"] != ""){
				$mydescription = $userDatas["desc"];
			}
			$mybgcolor = $userDatas["bgcol"];
			$mytxtcolor = $userDatas["txtcol"];
		}
		$userPicInfo = loadUserPic();
		if($userPicInfo != "error" and $userPicInfo != "") {
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
	<input name = "submitInfo" type="submit" value="Kinnita andmed"><br>
	</form>
	<br><br>
	<p> <?php echo $noticeForm; ?></p>
	<hr>
	<p><a href="main.php">Tagasi</a> avalehele! </br><b><a href = "?logout=1">Logi välja!</a></b></p>
</body>

</html>