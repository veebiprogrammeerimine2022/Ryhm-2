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
?>
<ul>
	<li><a href="?logout=1">Logi välja</a></li>
</ul>
<?php require_once "footer.php";?>