<?php 
	//echo "Siin on minu esimene PHP!";
	$name = "Gertin";
	$surname = "Pakkonen";
	$todayDate = date("d.m.Y");
	$weekDayNow = date ("N");
	//echo $weekDayNow;
	$weekDayNamesEST = ["esmaspäev", "teisipäev", "kolmapäev", "neljapäev", "reede", "laupäev", "pühapäev"]; 
	//var_dump ($weekDayNamesEST);
	//echo $weekDayNamesEST[0];
	$hourNow = date ("H");
	//echo $hourNow;
	$partOfDay = "";
	if ($hourNow < 8) {
	$partOfDay = "varajane hommik"; }
	if ($hourNow >= 8 and $hourNow < 16) {
	$partOfDay = "kooliaeg"; }
	if ($hourNow >= 16) {
	$partOfDay = "vaba aeg"; }
	//Loosime juhusliku pildi
	$picNum = mt_rand(2, 43);//random
	$picURL = "http://www.cs.tlu.ee/~rinde/media/fotod/TLU_600x400/tlu_";
	$picEXT = ".jpg";
	$picFileName = $picURL .$picNum .$picEXT;
	//echo $picNum;
	
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
	  
	
	<?php echo "<p>Täna on ". $weekDayNamesEST[$weekDayNow - 1]." ning kuupäev on ". $todayDate. "</p>\n";
		echo "<p>Lehe avamise hetkel oli kell ". date("H:i") . ", käes on ". $partOfDay. ".</p>\n";?>
	<img src="../../../~rinde/veebiprogrammeerimine2018s/tlu_terra_600x400_1.jpg" alt="TLÜ Terra õppehoone">  
	<p>Mu kõrval istub <a href="../../../~sandhan/veebiprogrammeerimine/tund_3" target="_blank">Sander</a>.</p>
	<!--<img src="tlu_terra_600x400_1.jpg" alt="TLÜ Terra õppehoone"> -->
	<!--<img src="http://greeny.cs.tlu.ee/~rinde/veebiprogrammeerimine2018s/tlu_terra_600x400_1.jpg" alt="TLÜ Terra õppehoone">
	<img src="tlu_terra_600x400_1.jpg" alt="TLÜ Terra õppehoone">	 -->
	
	<img src="<?php echo $picFileName;?>" alt="juhuslik pilt TLÜ'st">
	 
	
	 
</body>

</html>