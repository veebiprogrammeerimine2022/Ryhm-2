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
	
	require_once "fnc_edit_photo.php";
	require_once "fnc_general.php";
	$photo_error = null; 
	
	
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		
		if(isset($_POST["photo_submit"])){
			
		}//if photo_submit
	}//if method==POST 
	
	if(isset($_GET["id"]) and !empty($_GET["id"]) and filter_var($_GET["id"], FILTER_VALIDATE_INT)){
		$photo_data = read_own_photo_data($_GET["id"]);
		$alt = $photo_data["alt"];
		$privacy = $photo_data["privacy"];
	}

	require_once "header.php";
	
	echo "<p>Sisse loginud: " .$_SESSION["firstname"] ." " .$_SESSION["lastname"] .".</p> \n";
?>
<ul>
	<li><a href="?logout=1">Logi välja</a></li>
	<li><a href="home.php">Avalehele</a></li>
</ul>
<img src="<?php echo $gallery_photo_normal_folder .$photo_data["filename"];?>" alt="<?php echo $alt; ?>">
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<label for="alt_input">Alternatiivtekst (alt):</label>
	<input type="text" name="alt_input" id="alt_input" placeholder="alternatiivtekst ..." value="<?php echo $alt; ?>">
	<br>
	<input type="radio" name="privacy_input" id="privacy_input_1" value="1"<?php if($privacy == 1){echo " checked";}?>>
	<label for="privacy_input_1">Privaatne (ainult ise näen)</label>
	<br>
	<input type="radio" name="privacy_input" id="privacy_input_2" value="2"<?php if($privacy == 2){echo " checked";}?>>
	<label for="privacy_input_2">Sisseloginud kasutajatele</label>
	<br>
	<input type="radio" name="privacy_input" id="privacy_input_3" value="3"<?php if($privacy == 3){echo " checked";}?>>
	<label for="privacy_input_3">Avalik (kõik näevad)</label>
	<br>
	<input type="hidden" name="photo_input" value="<?php echo $_GET["id"]; ?>">
	<input type="submit" name="photo_submit" id="photo_submit" value="Muuda">
	<span><?php echo $photo_error; ?></span>
</form>

<?php require_once "footer.php";?>