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
	$id = null;
	
	
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		if(isset($_POST["photo_submit"])){
			$alt = test_input($_POST["alt_input"]);
			$privacy = filter_var($_POST["privacy_input"], FILTER_VALIDATE_INT);
			if(isset($_POST["photo_input"]) and filter_var($_POST["photo_input"], FILTER_VALIDATE_INT)){
				$id = $_POST["photo_input"];
				$photo_error = update_photo_data($alt, $privacy, $_POST["photo_input"]);
				if(empty($photo_error)){
					$photo_error = "Andmed muudetud!";
				} else {
					$photo_error = "Pildi andmeid ei õnnestunud muuta!";
				}
			} else {
				$photo_error = "Pildi andmeid ei õnnestu muuta!";
			}
			
		}//if photo_submit
		if(isset($_POST["photo_delete_submit"])){
			if(isset($_POST["photo_input"]) and filter_var($_POST["photo_input"], FILTER_VALIDATE_INT)){
				$id = $_POST["photo_input"];
				$photo_error = delete_photo($id);
				if(empty($photo_error)){
					$photo_error = "Pilt kustutatud!";
				} else {
					$photo_error = "Pildi kustutamine ei õnnestunud!";
				}
			} else {
				$photo_error = "Pilti ei õnnestu kustutada!";
			}
		}
	}//if method==POST 
	
	if(isset($_GET["id"]) and !empty($_GET["id"]) and filter_var($_GET["id"], FILTER_VALIDATE_INT)){
		$photo_data = read_own_photo_data($_GET["id"]);
		$alt = $photo_data["alt"];
		$privacy = $photo_data["privacy"];
		$id = $_GET["id"];
	}

	require_once "header.php";
	
	echo "<p>Sisse loginud: " .$_SESSION["firstname"] ." " .$_SESSION["lastname"] .".</p> \n";
?>
<ul>
	<li><a href="?logout=1">Logi välja</a></li>
	<li><a href="home.php">Avalehele</a></li>
	<li><a href="gallery_own.php<?php echo "?page=" .$_SESSION["gallery_own_page"];?>">Tagasi oma piltide galeriisse</a></li>
</ul>
<img src="<?php echo $gallery_photo_normal_folder .$photo_data["filename"];?>" alt="<?php echo $alt; ?>">
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ."?id=" .$id;?>">
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
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ."?id=" .$id;?>">
	<input type="hidden" name="photo_input" value="<?php echo $_GET["id"]; ?>">
	<input type="submit" name="photo_delete_submit" id="photo_submit" value="Kustuta">
</form>
<?php require_once "footer.php";?>