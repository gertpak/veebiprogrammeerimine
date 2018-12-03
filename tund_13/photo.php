<?php 
	$dirToRead = "../thumbuploads/";
	$allFiles = array_slice(scandir($dirToRead), 2);
	$pageTitle = "Piltide leht";
	require("functions.php");
	require("header.php");

  
  
?>


	<p>Siin on minu <a href="http://tlu.ee/" target="_blank">TLÜ</a> õppetöö raames valminud veebilehed. Need ei oma mingit sügavat sisu ja nende kopeerimine ei oma mõtet.</p><hr>
	  
	<!--<img src="<?php echo $picFileName;?>" alt="juhuslik pilt TLÜ'st"></br>
	<img src="<?php echo $dirToRead .$allFiles[1];?>" alt="juhuslik pilt TLÜ'st"></br>-->
	<?php
	for ($i = 0; $i < count ($allFiles); $i ++){ 
		echo '<img src="' .$dirToRead .$allFiles[$i] .'" alt="pilt"><br>'; 
	}
	?>
	 
	
	 
</body>

</html>