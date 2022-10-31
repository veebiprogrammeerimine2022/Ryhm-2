<?php

	require_once "../../../config_vp2022.php";

	function read_own_photo_data($id){
		$photo_data = [];
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT filename, alttext, privacy FROM vp_photos_2 WHERE id = ?");
		echo $conn->error;
		$stmt->bind_param("i", $id);
		$stmt->bind_result($filename_from_db, $alttext_from_db, $privacy_from_db);
		$stmt->execute();
		if($stmt->fetch()){
			$photo_data["filename"] = $filename_from_db;
			$photo_data["alt"] = $alttext_from_db;
			$photo_data["privacy"] = $privacy_from_db;
		}
		$stmt->close();
		$conn->close();
		return $photo_data;
	}