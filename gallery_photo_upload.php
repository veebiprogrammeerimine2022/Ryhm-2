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
	require_once "fnc_general.php";

	$file_type = null;
	$photo_error = null;
	$photo_file_name = null;
	$alt = null;
	$privacy = 1;
	
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		
		if(isset($_POST["photo_submit"])){
			$alt = test_input($_POST["alt_input"]);
			$privacy = filter_var($_POST["privacy_input"], FILTER_VALIDATE_INT);
			
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
					$photo_error = save_photo($normal_photo, $gallery_photo_normal_folder .$photo_file_name, $file_type);
					if(empty($photo_error)){
						//teeme pisipildi (thumbnail)
						$thumbnail = resize_photo($temp_photo, $thumbnail_photo_w, $thumbnail_photo_h, false);
						$photo_error = save_photo($thumbnail, $gallery_photo_thumbnail_folder .$photo_file_name, $file_type);
					}
					
					if(empty($photo_error)){
					// ajutine fail: $_FILES["photo_input"]["tmp_name"]
						if(move_uploaded_file($_FILES["photo_input"]["tmp_name"], $gallery_photo_original_folder .$photo_file_name) == false){
							$photo_error = 1;
						}
					}
					
					if(empty($photo_error)){
						$photo_error = store_photo_data($photo_file_name, $alt, $privacy);
					}
					if(empty($photo_error)){
						$photo_error = "Pilt edukalt üles laetud!";
						$alt = null;
						$privacy = 1;
					} else {
						$photo_error = "Pildi üleslaadimisel tekkis tõrkeid!";
					}
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
	<input type="submit" name="photo_submit" id="photo_submit" value="Lae üles">
	<span><?php echo $photo_error; ?></span>
</form>

<?php require_once "footer.php";?>