<?php
	function check_file_type($image_file){
		$image_type = 0;
		$image_check = getimagesize($image_file);
		if($image_check !== false){
			if($image_check["mime"] == "image/jpeg"){
				$image_type = "jpg";
			}
			if($image_check["mime"] == "image/png"){
				$image_type = "png";
			}
			if($image_check["mime"] == "image/gif"){
				$image_type = "gif";
			}
		}
		return $image_type;
	}
	
	function create_filename($name_prefix, $file_type){
		$timestamp = microtime(1) * 10000;
		return $name_prefix .$timestamp ."." .$file_type;
	}
	
	function create_image($file, $file_type){
		$temp_image = null;
		if($file_type == "jpg"){
			$temp_image = imagecreatefromjpeg($file);
		}
		if($file_type == "png"){
			$temp_image = imagecreatefrompng($file);
		}
		if($file_type == "gif"){
			$temp_image = imagecreatefromgif($file);
		}
		return $temp_image;
	}
	
	function resize_photo($temp_photo, $normal_photo_max_w, $normal_photo_max_h){
		$image_w = imagesx($temp_photo);
		$image_h = imagesy($temp_photo);
		$new_w = $normal_photo_max_w;
		$new_h = $normal_photo_max_h;
		//säilitan originaalproportsioonid
		if($image_w / $normal_photo_max_w > $image_h / $normal_photo_max_h){
			$new_h = round($image_h / ($image_w / $normal_photo_max_w));
		} else {
			$new_w = round($image_w / ($image_h / $normal_photo_max_h));
		}
		$temp_image = imagecreatetruecolor($new_w, $new_h);
		//teeme originaalist väiksele koopia
		imagecopyresampled($temp_image, $temp_photo, 0, 0, 0, 0, $new_w, $new_h, $image_w,$image_h);
		return $temp_image;
	}
	
	function save_photo($image, $target, $file_type){
		if($file_type == "jpg"){
			imagejpeg($image, $target, 95);
		}
		if($file_type == "png"){
			imagepng($image, $target, 6);
		}
		if($file_type == "gif"){
			imagegif($image, $target);
		}
	}