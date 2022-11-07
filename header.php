<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title>Vinge veebisüsteem</title>
	<style>
		body {
			background-color: <?php echo $_SESSION["user_bg_color"]; ?>;
			color: <?php echo $_SESSION["user_txt_color"]; ?>
		}
	</style>
	<?php
		if(isset($style_sheets) and !empty($style_sheets)){
			foreach($style_sheets as $style){
				echo '<link rel="stylesheet" href="';
				echo $style;
				echo '">' ."\n";
			}
		}
	?>
</head>
<body>
<img src="pics/vp_banner_gs.png" alt="bänner">
<h1>Vinge veebisüsteem</h1>
<p>See leht on loodud õppetöö raames ja ei sisalda tõsiseltvõetavat sisu!</p>
<p>Õppetöö toimus <a href="https://www.tlu.ee" target="_blank">Tallinna Ülikoolis</a> Digitehnoloogiate instituudis.</p>