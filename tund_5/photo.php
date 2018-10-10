<?php 
	$name = "Gertin";
	$surname = "Pakkonen";
	$dirToRead = "../../pics/";
	$allFiles = array_slice(scandir($dirToRead), 2);
	//var_dump($allFiles);
	
	
	
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
		echo "'i";?> piltide leht</title>

</head>
<body>
	<h1><?php echo $name . " " . $surname; ?></h1>
	<p>Siin on minu <a href="http://tlu.ee/" target="_blank">TLÜ</a> õppetöö raames valminud veebilehed. Need ei oma mingit sügavat sisu ja nende kopeerimine ei oma mõtet.</p>
	  
	<!--<img src="<?php echo $picFileName;?>" alt="juhuslik pilt TLÜ'st"></br>
	<img src="<?php echo $dirToRead .$allFiles[1];?>" alt="juhuslik pilt TLÜ'st"></br>-->
	<?php
	for ($i = 0; $i < count ($allFiles); $i ++){ 
	echo '<img src="' .$dirToRead .$allFiles[$i] .'" alt="pilt"><br>'; }
	?>
	 
	
	 
</body>

</html>