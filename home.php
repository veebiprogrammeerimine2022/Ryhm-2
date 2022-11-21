<?php
	//session_start();
	require_once "classes/SessionManager.class.php";
	SessionManager::sessionStart("vp", 0, "/~rinde/vp_2022/Ryhm-2/", "greeny.cs.tlu.ee");
	
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
	
	
	//küpsised, enne veebilehe algust!!!!
	//cookie nimi, väärtus, aegumine sekundites, kataloog serveris, domeen, kas https, kas juurdepääs ainult üle veebi
	//https jaoks saab  teha ka nii, kui pole kindel: isset($_SERVER["HTTPS"])
	setcookie("lastvisitor", $_SESSION["firstname"] ." " .$_SESSION["lastname"], (86400 * 7), "/~rinde/vp_2022/Ryhm-2/", "greeny.cs.tlu.ee", true, true);
	//cookie kustutamine
	//setcookie     aegumine negattivne:   time() - 3000
	$last_visitor = "pole teada";
	//var_dump($_COOKIE);
	if(isset($_COOKIE["lastvisitor"]) and !empty($_COOKIE["lastvisitor"])){
		$last_visitor = $_COOKIE["lastvisitor"];
	}
	//echo $_COOKIE["lastvisitor"];
	

	require_once "header.php";
	
	echo "<p>Sisse loginud: " .$_SESSION["firstname"] ." " .$_SESSION["lastname"] .".</p> \n";
	echo "<p>Viimane külastaja: " .$last_visitor .".</p> \n";
?>
<ul>
	<li><a href="?logout=1">Logi välja</a></li>
	<li><a href="user_profile.php">Minu kasutajaprofiil</a></li>
	<li><a href="gallery_photo_upload.php">Fotode galeriisse üleslaadimine</a></li>
	<li><a href="gallery_public.php">Avalike fotode galerii</a></li>
	<li><a href="gallery_own.php">Minu oma pildid</a></li>
</ul>
<?php require_once "footer.php";?>