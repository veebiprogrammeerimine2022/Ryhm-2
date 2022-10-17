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
	
	require_once "fnc_photo_upload.php";

	$file_type = null;
	$photo_error = null;
	$photo_file_size_limit = 1.5 * 1024 * 1024;
	$photo_name_prefix = "vp_";
	$photo_file_name = null;
	$normal_photo_max_w = 800;
	$normal_photo_max_h = 450;
	
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		if(isset($_POST["photo_submit"])){
			//var_dump($_POST);
			//var_dump($_FILES);
			
			//kontrollime, kas on sobilik
			if(isset($_FILES["photo_input"]["tmp_name"]) and !empty($_FILES["photo_input"]["tmp_name"])){
				//failitüüp
				$file_type = check_file_type($_FILES["photo_input"]["tmp_name"]);
				//echo $file_type;
				if($file_type == 0){
					$photo_error = "Valitud fail pole sobivat tüüpi";
				}
				
				//failimaht
				if(empty($photo_error)){
					if($_FILES["photo_input"]["size"] > $photo_file_size_limit){
						$photo_error = "Valitud fail on liiga suur!";
					}
				}
				
				//genereerin failinime
				$photo_file_name = create_filename($photo_name_prefix, $file_type);
				
						
				if(empty($photo_error)){
					//teeme pildi "väiksemaks"
					//loome pikslikogumi (justkui avame foto PhotoShopis)
					$temp_photo = create_image($_FILES["photo_input"]["tmp_name"], $file_type);
					//muudame pildi suurust
					$normal_photo = resize_photo($temp_photo, $normal_photo_max_w, $normal_photo_max_h);
					//salvestame v'iksemaks tehtud pildi
					save_photo($normal_photo, "photo_upload_normal/" .$photo_file_name, $file_type);
					
					// ajutine fail: $_FILES["photo_input"]["tmp_name"]
					move_uploaded_file($_FILES["photo_input"]["tmp_name"], "photo_upload_original/" .$photo_file_name);
				}
			}
		}//if photo_submit
	}//if method==POST 

	require_once "header.php";
	
	echo "<p>Sisse loginud: " .$_SESSION["firstname"] ." " .$_SESSION["lastname"] .".</p> \n";
?>
<ul>
	<li><a href="?logout=1">Logi välja</a></li>
	<li><a href="home.php">Avalehele</a></li>
</ul>

<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
	<label for="photo_input">Vali pildifail:</label>
	<input type="file" name="photo_input" id="photo_input">
	<br>
	<label for="alt_input">Alternatiivtekst (alt):</label>
	<input type="text" name="alt_input" id="alt_input" placeholder="alternatiivtekst ...">
	<br>
	<input type="radio" name="privacy_input" id="privacy_input_1" value="1">
	<label for="privacy_input_1">Privaatne (ainult ise näen)</label>
	<br>
	<input type="radio" name="privacy_input" id="privacy_input_2" value="2">
	<label for="privacy_input_2">Sisseloginud kasutajatele</label>
	<br>
	<input type="radio" name="privacy_input" id="privacy_input_3" value="3">
	<label for="privacy_input_3">Avalik (kõik näevad)</label>
	<br>
	<input type="submit" name="photo_submit" id="photo_submit" value="Lae üles">
	<span><?php echo $photo_error; ?></span>
</form>

<?php require_once "footer.php";?>