<?php
	/* require_once "classes/SessionManager.class.php";
	SessionManager::sessionStart("vp", 0, "/~rinde/vp_2022/Ryhm-2/", "greeny.cs.tlu.ee");
	//kontrollin, kas oleme sisse loginud
	if(!isset($_SESSION["user_id"])){
		header("Location: page.php");
		exit();
	} */
	
	$id = null;
	$type = "image/png";
	$output = "pics/wrong.png";
	$privacy = 3;
	
	if(isset($_GET["photo"]) and !empty($_GET["photo"])){
		$id = filter_var($_GET["photo"], FILTER_VALIDATE_INT);
	}
	
	if(!empty($id)){
		require_once "../../../config_vp2022.php";
		$conn = new mysqli($server_host, $server_user_name, $server_password, $database);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT filename from vp_photos_2 WHERE id = ? AND privacy = ? AND deleted is NULL");
		echo $conn->error;
		$stmt->bind_param("ii", $id, $privacy);
		$stmt->bind_result($filename_from_db);
		$stmt->execute();
		if($stmt->fetch()){
			$output = $gallery_photo_normal_folder .$filename_from_db;
			$check = getimagesize($output);
			$type = $check["mime"];
		}
		$stmt->close();
		$conn->close();
	}
	
	//väljastan pildi
	header("Content-type: " .$type);
	readfile($output);