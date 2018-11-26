<?php 

  	$mybgcolor = $_SESSION["bgColor"];
	$mytxtcolor = $_SESSION["txtColor"];

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
	<title><?php echo $pageTitle; ?></title>
	<style>
		body{background-color: <?php echo $mybgcolor; ?>; 
		color: <?php echo $mytxtcolor; ?>} 
	</style>
	<?php
	if(isset($scripts)){
		echo $scripts;
	}?>
  </head>
  <body>
	<div>
		
		<a href="main.php"><img src="../vp_picfiles/vp_logo_w135_h90.png" alt="vp logo"></a>
		<img src="../vp_picfiles/vp_banner.png" alt="vp bänner">
	</div>
	
    <h1><?php echo $pageTitle; ?></h1>