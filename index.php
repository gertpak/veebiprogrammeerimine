<?php 
	//echo "Siin on minu esimene PHP!";
	$name = "Gertin";
	$surname = "Pakkonen";
	$todayDate = date("l, d.m.Y");
	$hourNow = date ("H");
	//echo $hourNow;
	$partOfDay = "";
	if ($hourNow < 8) {
	$partOfDay = "varajane hommik"; }
	if ($hourNow >= 8 and $hourNow < 16) {
	$partOfDay = "kooliaeg"; }
	if ($hourNow >= 16) {
	$partOfDay = "vaba aeg"; }
	
	
	?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>
	<?php
		echo $name;
		echo " ";
		echo $surname;
		echo "'i";?> veebileht</title>

</head>
<body>
	<h1><?php echo $name . " " . $surname; ?></h1>
	<p>Siin on minu <a href="http://tlu.ee/" target="_blank">TLÜ</a> õppetöö raames valminud veebilehed. Need ei oma mingit sügavat sisu ja nende kopeerimine ei oma mõtet.</p>
	<p>Kodutöö on ilusti tehtud</p>   
	<p>Mu kõrval istub <a href="../../~sandhan/" target="_blank">Sander</a>.</p>
	<?php echo "<p>Tänane kuupäev: ". $todayDate. "</p>\n";
		echo "<p>Lehe avamise hetkel oli kell ". date("H:i") . ", käes on ". $partOfDay. ".</p>\n";?> 
	<!--<img src="tlu_terra_600x400_1.jpg" alt="TLÜ Terra õppehoone"> -->
	<!--<img src="http://greeny.cs.tlu.ee/~rinde/veebiprogrammeerimine2018s/tlu_terra_600x400_1.jpg" alt="TLÜ Terra õppehoone">
	<img src="tlu_terra_600x400_1.jpg" alt="TLÜ Terra õppehoone">	 -->
	<img src="../../~rinde/veebiprogrammeerimine2018s/tlu_terra_600x400_1.jpg" alt="TLÜ Terra õppehoone">
	<img src="https://i.imgflip.com/u9pv5.jpg" alt="MEME">
	 
	
	 
</body>

</html>