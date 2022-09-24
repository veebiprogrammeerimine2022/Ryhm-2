<?php
	$author_name = "Andrus Rinde";
	$full_time_now = date("d.m.Y H:i:s");
	$weekday_now = date("N");
	//echo $weekday_now;
	$weekdaynames_et = ["esmaspäev", "teisipäev", "kolmapäev", "neljapäev", "reede", "laupäev", "pühapäev"];
	//echo $weekdaynames_et[$weekday_now - 1];
	$hours_now = date("H");
	//echo $hours_now;
	$part_of_day = "suvaline päeva osa";
	//  <   > >=  <=   ==  !=
	if($weekday_now <= 5){
		if($hours_now < 7 or $hours_now >= 23){
			$part_of_day = "uneaeg";
		}
		//   and   or
		if($hours_now >= 8 and $hours_now < 18){
			$part_of_day = "koolipäev";
		}
		if($hours_now >= 18 and $hours_now < 23){
			$part_of_day = "vaba aeg";
		}
	}
	if($weekday_now == 6){
		if($hours_now < 8){
			$part_of_day = "uneaeg";
		}
		if($hours_now >= 8 and $hours_now < 23){
			$part_of_day = "vaba aeg";
		}
		if($hours_now >= 23){
			$part_of_day = "mõnusa logelemise aeg";
		}
	}
		if($weekday_now == 7){
		if($hours_now < 9){
			$part_of_day = "uneaeg";
		}
		if($hours_now >= 9 and $hours_now < 19){
			$part_of_day = "vaba aeg";
		}
		if($hours_now >= 19){
			$part_of_day = "uueks nädalaks valmistumise aeg";
		}
	}
	
	
	//uurime semestri kestmist
	$semester_begin = new DateTime("2022-9-5");
	$semester_end = new DateTime("2022-12-18");
	$semester_duration = $semester_begin->diff($semester_end);
	//echo $semester_duration;
	$semester_duration_days = $semester_duration->format("%r%a");
	$from_semester_begin = $semester_begin->diff(new DateTime("now"));
	$from_semester_begin_days = $from_semester_begin->format("%r%a");
	
	//juhuslik arv
	//küsin massiivi pikkust
	//echo count($weekdaynames_et);
	//echo mt_rand(0, count($weekdaynames_et) -1);
	//echo $weekdaynames_et[mt_rand(0, count($weekdaynames_et) -1)];
	
	$old_wisdom_list = ["Tarkus ei küsi süüa, vaid annab süüa.", "Homseks hoia leiba, mitte tööd.", "Hommik on õhtust targem.", "Kus viga näed laita, seal tule ja aita.", "Sõnahoobid on vahest valusamad, kui käehoobid.", "Ega rumalaid künta ja külvata, nemad kasvavad ise.", "Laps on perekonna peegel.", "Väikesed lapsed, väiksed mured, suured lapsed, suured mured.", "Kes hunti kardab, see ärgu metsa mingu.", "Ei meri sellest alane, kui koer äärest lakub.", "Targad sõdivad sõnaga, rumalad rusikaga.", "Ära vanasse kaevu enne sülita, kui uus valmis."];
	//$random_wisdom = $old_wisdom_list[mt_rand(0, count($old_wisdom_list) - 1)];
	
	//juhuslik foto
	$photo_dir = "photos";
	//loen kataloogi sisu
	//$all_files = scandir($photo_dir);
	$all_files = array_slice(scandir($photo_dir), 2);
	//kontrollin, kas ikka foto
	$allowed_photo_types = ["image/jpeg", "image/png"];
	//tsükkel
	//Muutuja väärtuse suurendamine   $muutuja = $muutuja + 5
	// $muutuja += 5
	//kui on vaja liita 1
	//$muutuja ++
	//sama moodi $muutuja -= 5     $muutuja --
	/*for($i = 0;$i < count($all_files); $i ++){
		echo $all_files[$i];
	}*/
	$photo_files = [];
	foreach($all_files as $filename){
		//echo $filename;
		$file_info = getimagesize($photo_dir ."/" .$filename);
		//var_dump($file_info);
		//kas on lubatud tüüpide nimekirjas
		if(isset($file_info["mime"])){
			if(in_array($file_info["mime"], $allowed_photo_types)){
				array_push($photo_files, $filename);
			}//if in_array
		}//if isset
	}//foreach
	
	
	//var_dump($photo_files);
	//   <img src="kataloog/fail" alt="tekst">
	$photo_html = '<img src="' .$photo_dir ."/" .$photo_files[mt_rand(0, count($photo_files) - 1)] .'"';
	$photo_html .= ' alt="Tallinna pilt">';
	
	//vaatame, mida vormis sisestati
	//var_dump($_POST);
	//echo $_POST["todays_adjective_input"];
	$todays_adjective = "pole midagi sisestatud";
	if(isset($_POST["todays_adjective_input"]) and !empty($_POST["todays_adjective_input"])){
		$todays_adjective = $_POST["todays_adjective_input"];
	}
	
	//loome rippmenüü valikud
	//<option value="0">tln_1.JPG</option>
	//<option value="1">tln_106.JPG</option>
	$select_html = '<option value="" selected disabled>Vali pilt</option>';
	for($i = 0;$i < count($photo_files); $i ++){
		$select_html .= '<option value="' .$i .'">';
		$select_html .= $photo_files[$i];
		$select_html .= "</option>";
	}
	
	//echo $_POST["photo_select"];
	//if(isset($_POST["photo_select"]) and !empty($_POST["photo_select"])){
	if(isset($_POST["photo_select"]) and $_POST["photo_select"] >= 0){
		echo "Valiti pilt nr:" .$_POST["photo_select"];
	}
	
?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title><?php echo $author_name;?> programmeerib veebi</title>
</head>
<body>
<img src="pics/vp_banner_gs.png" alt="bänner">
<h1><?php echo $author_name;?> programmeerib veebi</h1>
<p>See leht on loodud õppetöö raames ja ei sisalda tõsiseltvõetavat sisu!</p>
<p>Õppetöö toimus <a href="https://www.tlu.ee" target="_blank">Tallinna Ülikoolis</a> Digitehnoloogiate instituudis.</p>
<p>Lehe avamise hetk: <?php echo $weekdaynames_et[$weekday_now - 1] .", " .$full_time_now;?></p>
<p>Praegu on <?php echo $part_of_day;?>.</p>
<p>Semestri pikkus on <?php echo $semester_duration_days;?> päeva. See on kestnud juba <?php echo $from_semester_begin_days; ?> päeva.</p>
<img src="pics/tlu_39.jpg" alt="Tallinna Ülikooli ajalooline Terra õppehoone">
<p>Väike tarkusetera: <?php echo $old_wisdom_list[mt_rand(0, count($old_wisdom_list) - 1)]; ?></p>
<form method="POST">
	<input type="text" id="todays_adjective_input" name="todays_adjective_input" placeholder="Kirjuta siia omadussõna tänase päeva kohta">
	<input type="submit" id="todays_adjective_submit" name="todays_adjective_submit" value="Saada omadussõna!">
</form>
<p>Omadussõna tänase kohta: <?php echo $todays_adjective; ?></p>
<hr>
<form method="POST">
	<select id="photo_select" name="photo_select">
		<?php echo $select_html; ?>		
	</select>
	<input type="submit" id="photo_submit" name="photo_submit" value="Määra foto">
</form>
<?php echo $photo_html; ?>
<hr>

</body>
</html>