<?php
	class Photoupload {
		private $photo_to_upload;
		public $file_type; //alguses saadame klassile, hiljem uurib klass selle ise välja
		private $temp_photo;
		private $new_temp_photo;
		public $error = null;
		public $file_name = null;
		
		function __construct($photo){
			$this->photo_to_upload = $photo;
			$this->check_file_type();
			if(empty($this->error)){
				$this->temp_photo = $this->create_image($this->photo_to_upload["tmp_name"], $this->file_type);
			}
		}
		
		function __destruct(){
			@imagedestroy($this->temp_photo);
			@imagedestroy($this->new_temp_photo);
		}
		
		private function check_file_type(){
			$image_check = getimagesize($this->photo_to_upload["tmp_name"]);
			if($image_check){
				//var_dump($image_check);
				if($image_check["mime"] == "image/jpeg"){
					$this->file_type = "jpg";
				}
				elseif($image_check["mime"] == "image/png"){
					$this->file_type = "png";
				}
				elseif($image_check["mime"] == "image/gif"){
					$this->file_type = "gif";
				} else {
					$this->error = "Tegemist pole veebis kasutatava failitüübiga!";
				}
			} else {
				$this->error = "See pole pildifail!";
			}
			return $this->error;
		}
		
		public function check_allowed_type($allowed_types){
			//$error = null;
			$image_check = getimagesize($this->photo_to_upload["tmp_name"]);
			if($image_check !== false){
				if(!in_array($image_check["mime"], $allowed_types)){
					//$error = "Valitud fail pole lubatud tüüpi!";
					$this->error = "Valitud fail pole lubatud tüüpi!";
				}
			}
			//$this->error = $error;
			//return $error;
			return $this->error;
		}
		
		public function check_size($limit){
			if($this->photo_to_upload["size"] > $limit){
				$this->error = "Valitud fail on liiga suur!";
			}
			return $this->error;
		}
		
		public function create_filename($name_prefix){
			$timestamp = microtime(1) * 10000;
			$this->file_name = $name_prefix .$timestamp ."." .$this->file_type;
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
		
		
		
		public function resize_photo($w, $h, $keep_orig_proportion = true){
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
		
		public function save_photo($target){
			$this->error = null;
			if($this->file_type == "jpg"){
				if(imagejpeg($this->new_temp_photo, $target, 95) == false){
					$this->error = "Pildifaili salvestamine ebaõnnestus!";
				}
			}
			if($this->file_type == "png"){
				if(imagepng($this->new_temp_photo, $target, 6) == false){
					$this->error = "Pildifaili salvestamine ebaõnnestus!";
				}
			}
			if($this->file_type == "gif"){
				if(imagegif($this->new_temp_photo, $target) == false){
					$this->error = "Pildifaili salvestamine ebaõnnestus!";
				}
			}
			//kustutan pikslikogumi, vabastan mälu
			imagedestroy($this->new_temp_photo);
			return $this->error;
		}
		
		public function move_original_photo($target){
			$this->error = null;
			if(move_uploaded_file($this->photo_to_upload["tmp_name"], $target) == false){
				$this->error = "Originaalfaili salvestamine ebaõnnestus!";
			}
			return $this->error;
		}
		
	}//class lõppeb