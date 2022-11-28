<?php
    //alustame sessiooni
    require_once "classes/SessionManager.class.php";
	SessionManager::sessionStart("vp", 0, "/~rinde/vp_2022/Ryhm-2/", "greeny.cs.tlu.ee");
	//kontrollin, kas oleme sisse loginud
	if(!isset($_SESSION["user_id"])){
		header("Location: page.php");
		exit();
	}
    
	if(isset($_GET["photo"]) and !empty($_GET["photo"])){
		$id = filter_var($_GET["photo"], FILTER_VALIDATE_INT);
	}
	if(isset($_GET["rating"]) and !empty($_GET["rating"])){
		$rating = filter_var($_GET["rating"], FILTER_VALIDATE_INT);
	}
	
	$response = "Hinne teadmata!";
	
	if(!empty($id)){
        require_once "../../../config_vp2022.php";
        $conn = new mysqli($server_host, $server_user_name, $server_password, $database);
		$conn->set_charset("utf8");
		if(!empty($rating)){
			$stmt = $conn->prepare("INSERT INTO vp_photoratings_2 (photoid, userid, rating) VALUES(?, ?, ?)");
			echo $conn->error;
			$stmt->bind_param("iii", $id, $_SESSION["user_id"], $rating);
			$stmt->execute();
			$stmt->close();
		}
		//loeme keskmise hinde
		$stmt = $conn->prepare("SELECT AVG(rating) as avgValue FROM vp_photoratings_2 WHERE photoid = ?");
		echo $conn->error;
		$stmt->bind_param("i", $id);
		$stmt->bind_result($score);
		$stmt->execute();
		if($stmt->fetch()){
			$response = "Hinne: " .round($score, 2);
		}
		$stmt->close();
		$conn->close();
	}
    echo $response;