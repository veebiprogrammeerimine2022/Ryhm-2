<?php
	require_once "../../../config_vp2022.php";
	
	function read_public_photo_thumbs($privacy, $page, $limit){
		$skip = ($page - 1) * $limit;
        $photo_html = null;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
        //$stmt = $conn->prepare("SELECT filename, alttext FROM vp_photos_2 WHERE privacy >= ? AND deleted IS NULL");
		//LIMIT x   - mitu näidata
		//LIMIT x,y - mitu vahele jätta, mitu näidata
		$stmt = $conn->prepare("SELECT vp_photos_2.filename, vp_photos_2.alttext, vp_users_2.firstname, vp_users_2.lastname FROM vp_photos_2 JOIN vp_users_2 ON vp_photos_2.userid = vp_users_2.id WHERE vp_photos_2.privacy >= ? AND vp_photos_2.deleted IS NULL GROUP BY vp_photos_2.id ORDER BY vp_photos_2.id DESC LIMIT ?,?");
        echo $conn->error;
        $stmt->bind_param("iii", $privacy, $skip, $limit);
        $stmt->bind_result($filename_from_db, $alttext_from_db, $firstname_from_db, $lastname_from_db);
        $stmt->execute();
        while($stmt->fetch()){
			$photo_html .= '<div class="thumbgallery">' ."\n";
			$photo_html .= '<img src="' .$GLOBALS["gallery_photo_thumbnail_folder"] .$filename_from_db .'" alt="';
            if(empty($alttext_from_db)){
                $photo_html .= "Üleslaetud foto";
            } else {
                $photo_html .= $alttext_from_db;
            }
            $photo_html .= '" class="thumbs">' ."\n";
            $photo_html .= "<p>" .$firstname_from_db ." " .$lastname_from_db ."</p> \n";
			$photo_html .= "</div> \n";
        }
        $stmt->close();
		$conn->close();
		return $photo_html;
    }
	
	function read_public_photos($privacy){
        $photo_html = null;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
        //$stmt = $conn->prepare("SELECT filename, alttext FROM vp_photos_2 WHERE privacy >= ? AND deleted IS NULL");
		$stmt = $conn->prepare("SELECT vp_photos_2.filename, vp_photos_2.alttext, vp_users_2.firstname, vp_users_2.lastname FROM vp_photos_2 JOIN vp_users_2 ON vp_photos_2.userid = vp_users_2.id WHERE vp_photos_2.privacy >= ? AND vp_photos_2.deleted IS NULL GROUP BY vp_photos_2.id");
        echo $conn->error;
        $stmt->bind_param("i", $privacy);
        $stmt->bind_result($filename_from_db, $alttext_from_db, $firstname_from_db, $lastname_from_db);
        $stmt->execute();
        while($stmt->fetch()){
			$photo_html .= '<img src="' .$GLOBALS["gallery_photo_normal_folder"] .$filename_from_db .'" alt="';
            if(empty($alttext_from_db)){
                $photo_html .= "Üleslaetud foto";
            } else {
                $photo_html .= $alttext_from_db;
            }
            $photo_html .= '">' ."\n";
            $photo_html .= "<p>Üles laadis: " .$firstname_from_db ." " .$lastname_from_db ."</p> \n";
        }
        $stmt->close();
		$conn->close();
		return $photo_html;
    }
	
	function count_photos($privacy){
        $photo_count = 0;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
        $stmt = $conn->prepare("SELECT COUNT(id) FROM vp_photos_2 WHERE privacy >= ? AND deleted IS NULL");
        echo $conn->error;
        $stmt->bind_param("i", $privacy);
        $stmt->bind_result($count_from_db);
        $stmt->execute();
        if($stmt->fetch()){
            $photo_count = $count_from_db;
        }
        $stmt->close();
		$conn->close();
		return $photo_count;
    }
	
	function count_own_photos(){
        $photo_count = 0;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
        $stmt = $conn->prepare("SELECT COUNT(id) FROM vp_photos_2 WHERE userid = ? AND deleted IS NULL");
        echo $conn->error;
        $stmt->bind_param("i", $_SESSION["user_id"]);
        $stmt->bind_result($count_from_db);
        $stmt->execute();
        if($stmt->fetch()){
            $photo_count = $count_from_db;
        }
        $stmt->close();
		$conn->close();
		return $photo_count;
    }
	
	function read_own_photo_thumbs($page, $limit){
		$skip = ($page - 1) * $limit;
        $photo_html = null;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
        //$stmt = $conn->prepare("SELECT filename, alttext FROM vp_photos_2 WHERE privacy >= ? AND deleted IS NULL");
		//LIMIT x   - mitu näidata
		//LIMIT x,y - mitu vahele jätta, mitu näidata
		$stmt = $conn->prepare("SELECT id, filename, alttext, privacy FROM vp_photos_2 WHERE userid = ? AND deleted IS NULL ORDER BY id DESC LIMIT ?,?");
        echo $conn->error;
        $stmt->bind_param("iii", $_SESSION["user_id"], $skip, $limit);
        $stmt->bind_result($id_from_db, $filename_from_db, $alttext_from_db, $privacy_from_db);
        $stmt->execute();
        while($stmt->fetch()){
			$photo_html .= '<div class="thumbgallery">' ."\n";
			$photo_html .= '<img src="' .$GLOBALS["gallery_photo_thumbnail_folder"] .$filename_from_db .'" alt="';
            if(empty($alttext_from_db)){
                $photo_html .= "Üleslaetud foto";
            } else {
                $photo_html .= $alttext_from_db;
            }
            $photo_html .= '" class="thumbs">' ."\n";
			//<p><a href="edit_photo_data.php?id=13">Muuda</a></p>
            $photo_html .= '<p><a href="edit_photo_data.php?id=' .$id_from_db .'">Muuda</a></p>' ."\n";
			$photo_html .= "</div> \n";
        }
        $stmt->close();
		$conn->close();
		return $photo_html;
    }