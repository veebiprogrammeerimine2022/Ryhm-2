<?php
	require_once "../../../config_vp2022.php";
	//kõik muutujad, mis deklareeritud väljaspool funktsiooni, on globaalsed
	//ja kättesaadavd massivist $GLOBALS
	
	function sign_up($first_name, $last_name, $birth_date, $gender, $email, $password){
		$notice = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT id FROM vp_users_2 WHERE email = ?");
		echo $conn->error;
		$stmt->bind_param("s", $email);
		$stmt->bind_result($id_from_db);
		$stmt->execute();
		if($stmt->fetch()){
			$notice = "Selline kasutaja on juba olemas!";
		} else {
			$stmt->close();
			$stmt = $conn->prepare("INSERT INTO vp_users_2 (firstname, lastname, birthdate, gender, email, password) values(?,?,?,?,?,?)");
			echo $conn->error;
			//krüpteerime parooli
			$pwd_hash = password_hash($password, PASSWORD_DEFAULT); 
			$stmt->bind_param("sssiss", $first_name, $last_name, $birth_date, $gender, $email, $pwd_hash);
			if($stmt->execute()){
				$notice = "Uus kasutaja loodud!";
			} else {
				$notice = "Kasutaja loomisel tekkis tehniline tõrge: " .$stmt->error;
			}
		}
		$stmt->close();
		$conn->close();
		return $notice;
	}
	
	function sign_in($email, $password){
		$login_error = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT password FROM vp_users_2 WHERE email = ?");
        echo $conn->error;
        $stmt->bind_param("s", $email);
        $stmt->bind_result($password_from_db);
        $stmt->execute();
        if($stmt->fetch()){
            //kasutaja on olemas, parool tuli ...
            if(password_verify($password, $password_from_db)){
				$stmt->close();
				$stmt = $conn->prepare("SELECT id, firstname, lastname FROM vp_users_2 WHERE email = ?");
				$stmt->bind_param("s", $email);
				$stmt->bind_result($id_from_db, $firstname_from_db, $lastname_from_db);
				$stmt->execute();
				if($stmt->fetch()){
					//parool õige, oleme sees!
					//määran sessioonimuutujad
					$_SESSION["user_id"] = $id_from_db;
					$_SESSION["firstname"] = $firstname_from_db;
					$_SESSION["lastname"] = $lastname_from_db;
					//lisame natuke lehe välimust
					$_SESSION["user_bg_color"] = "#DDDDDD";
					$_SESSION["user_txt_color"] = "#333399";
					//=================================================
					$stmt->close();
					$stmt = $conn->prepare("SELECT bgcolor, txtcolor FROM vp_userprofiles_2 WHERE userid = ?");
					$stmt->bind_param("i", $_SESSION["user_id"]);
					$stmt->bind_result($bg_color_from_db, $txt_color_from_db);
					$stmt->execute();
					if($stmt->fetch()){
						if(!empty($txt_color_from_db)){
							$_SESSION["user_txt_color"] = $txt_color_from_db;
						}
						if(!empty($bg_color_from_db)){
							$_SESSION["user_bg_color"] = $bg_color_from_db;
						}
					}
					//=================================================
					$stmt->close();
					$conn->close();
					header("Location: home.php");
					exit();
				} else {
					$login_error = "Sisselogimisel tekkis tõrge!";
				}
            } else {
                $login_error = "Kasutajatunnus või salasõna oli vale!";
            }
        } else {
            $login_error = "Kasutajatunnus või salasõna oli vale!";
        }
        
        $stmt->close();
        $conn->close();
		
		return $login_error;
	}
	
	function read_user_description(){
		//kui profiil on olemas, loeb kasutaja lühitutvustuse
		$description = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		//vaatame, kas on profiil olemas
		$stmt = $conn->prepare("SELECT description FROM vp_userprofiles_2 WHERE userid = ?");
		echo $conn->error;
		$stmt->bind_param("i", $_SESSION["user_id"]);
		$stmt->bind_result($description_from_db);
		$stmt->execute();
		if($stmt->fetch()){
			$description = $description_from_db;
		}
		$stmt->close();
		$conn->close();
		return $description;
	}
	
	function store_user_profile($description, $bg_color, $txt_color){
		$notice = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		//vaatame, kas on profiil olemas
		$stmt = $conn->prepare("SELECT id FROM vp_userprofiles_2 WHERE userid = ?");
		echo $conn->error;
		$stmt->bind_param("i", $_SESSION["user_id"]);
		$stmt->bind_result($id_from_db);
		$stmt->execute();
		if($stmt->fetch()){
			$stmt->close();
			//uuendame profiili
			$stmt= $conn->prepare("UPDATE vp_userprofiles_2 SET description = ?, bgcolor = ?, txtcolor = ? WHERE userid = ?");
			echo $conn->error;
			$stmt->bind_param("sssi", $description, $bg_color, $txt_color, $_SESSION["user_id"]);
		} else {
			$stmt->close();
			//tekitame uue profiili
			$stmt = $conn->prepare("INSERT INTO vp_userprofiles_2 (userid, description, bgcolor, txtcolor) VALUES(?,?,?,?)");
			echo $conn->error;
			$stmt->bind_param("isss", $_SESSION["user_id"], $description, $bg_color, $txt_color);
		}
		if($stmt->execute()){
			$_SESSION["user_bg_color"] = $bg_color;
			$_SESSION["user_txt_color"] = $txt_color;
			$notice = "Profiil salvestatud!";
		} else {
			$notice = "Profiili salvestamisel tekkis viga: " .$stmt->error;
		}
		$stmt->close();
		$conn->close();
		return $notice;
	}