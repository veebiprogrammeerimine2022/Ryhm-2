<?php
	class Photoupload {
		private $photo_to_upload;
		private $file_type; //alguses saadame klassile, hiljem uurib klass selle ise välja
		private $temp_photo;
		private $new_temp_photo;
		
		function __construct($photo, $type){
			$this->photo_to_upload = $photo;
			$this->file_type = $type; //hiljem on selle jaoks klassil oma funktsioon
			$this->temp_photo = $this->create_image($this->photo_to_upload["tmp_name"], $this->file_type); 
		}
		
		private function create_image($file, $file_type){
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
		
		function resize_photo($w, $h, $keep_orig_proportion = true){
			$image_w = imagesx($this->temp_photo);
			$image_h = imagesy($this->temp_photo);
			$new_w = $w;
			$new_h = $h;
			//uued muutujad, mis on seotud proportsioonide muutmisega, kärpimisega (crop)
			$cut_x = 0;
			$cut_y = 0;
			$cut_size_w = $image_w;
			$cut_size_h = $image_h;
			
			
			if ($keep_orig_proportion){//säilitan originaalproportsioonid
				if($image_w / $w > $image_h / $h){
					$new_h = round($image_h / ($image_w / $w));
				} else {
					$new_w = round($image_w / ($image_h / $h));
				}
			} else { //kui on vaja kindlat suurust, kärpimist

				if($image_w > $image_h){
					$cut_size_w = $image_h;
					$cut_x = round(($image_w - $cut_size_w) / 2);
				} else {
					$cut_size_h = $image_w;
					$cut_y = round(($image_h - $cut_size_h) / 2);
				}
			}
			
			$this->new_temp_photo = imagecreatetruecolor($new_w, $new_h);
			//säilitame vajadusel läbipaistvuse (png ja gif piltide jaoks
			imagesavealpha($this->new_temp_photo, true);
			$trans_color = imagecolorallocatealpha($this->new_temp_photo, 0, 0, 0, 127);
			imagefill($this->new_temp_photo, 0, 0, $trans_color);
			//teeme originaalist väiksele koopia
			imagecopyresampled($this->new_temp_photo, $this->temp_photo, 0, 0, $cut_x, $cut_y, $new_w, $new_h, $cut_size_w, $cut_size_h);
		}
		
	}//class lõppeb