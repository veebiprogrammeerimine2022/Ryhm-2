<?php
	session_start();
	//kontrollin, kas oleme sisse loginud
	if(!isset($_SESSION["user_id"])){
		header("Location: page.php");
		exit();
	}
	
	//logime välja
	if(isset($_GET["logout"])){
		session_destroy();
		header("Location: page.php");
		exit();
	}

	require_once "header.php";
	
	echo "<p>Sisse loginud: " .$_SESSION["firstname"] ." " .$_SESSION["lastname"] .".</p> \n";
?>
<ul>
	<li><a href="?logout=1">Logi välja</a></li>
	<li><a href="user_profile.php">Minu kasutajaprofiil</a></li>
	<li><a href="gallery_photo_upload.php">Fotode galeriisse üleslaadimine</a></li>
	<li><a href="gallery_public.php">Avalike fotode galerii</a></li>
	<li><a href="gallery_own.php">Minu oma pildid</a></li>
</ul>
<?php require_once "footer.php";?>