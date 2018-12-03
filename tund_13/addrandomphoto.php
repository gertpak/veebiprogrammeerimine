<?php
	require("../../../config.php");
	$database = "if18_gertin_pa_1";
	$privacy = 2;
	$limit = 10;
	$html = NULL;
	$photoList = [];
	
	$mysqli = new mysqli($serverHost, $serverUsername, $serverPassword, $database);
	$stmt = $mysqli->prepare("SELECT filename, alttext FROM vpphotos WHERE privacy<=? AND deleted IS NULL ORDER BY id DESC LIMIT ?");
	$stmt->bind_param("ii", $privacy, $limit);
	$stmt->bind_result($filenameFromDB,$altFromDB);
	$stmt->execute();
	while($stmt->fetch()) {
		$photo = new StdClass();
		$photo->filename = $filenameFromDB;
		$photo->alttext = $altFromDB;
		array_push($photoList, $photo);
	}
	if(count($photoList)>0) {
		$picNum = mt_rand(0, count($photoList)-1);
		$html = '<img src="' .$picDir .$photoList[$picNum]->filename .'" alt="' .$photoList[$picNum]->alttext .'">' ."\n";
		//lisame nimekirja kõigist leitud piltidest
		foreach($photoList as $myPhoto) {
			$html.="<p>" .$myPhoto->filename ."<p> \n";
		}		
	}
	$stmt->close();
	$mysqli->close();
	echo $html;
?>