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
		$target_file = $target_dir ."vp_" .$timeStamp ."." .$imageFileType;
		
		//$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		
		// Kas on pilt
		$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
		if($check !== false) {
			echo "Fail on pilt - " . $check["mime"] . ". ";
			$uploadOk = 1;
		} else {
			echo "Fail ei ole pilt. ";
			$uploadOk = 0;
		}
	
		// Kas file on olemas00
		if (file_exists($target_file)) {
			echo "Kahjuks on selline pilt juba olemas. ";
			$uploadOk = 0;
		}
		// Faili suurus
		if ($_FILES["fileToUpload"]["size"] > 2500000) {
			echo "Kahjuks on fail liiga suur. ";
			$uploadOk = 0;
		}
		// Saab muuta lubatud formaate
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		&& $imageFileType != "gif" ) {
			echo "Kahjuks on lubatud vaid JPG, JPEG, PNG ja GIF failid. ";
			$uploadOk = 0;
		}
		// Kui $uploadOk on muudetud 0'iks mõne errori poolt
		if ($uploadOk == 0) {
			echo "Kahjuks seda faili ei laetud üles. ";
		// Kui kõik korras, laeme üles
		} else {
			if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
				echo "Fail ". basename( $_FILES["fileToUpload"]["name"]). " laeti üles edukalt.";
			} else {
				echo "Kahjuks oli mõni tõrge faili üleslaadimisel. ";
			}
		}
		}
		else {
			echo "Lisa palun fail, taun! ";
		}
	}//Kas on nuppu vajutatud
  //Lehe päise üleslaadimise osa
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
    <input type="submit" value="Lae pilt üles" name="submitPic">
</form>
	
  </body>
</html>