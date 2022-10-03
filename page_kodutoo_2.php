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
<p>Väike tarkusetera: <?php echo $old_wisdom_list[mt_rand(0, count($old_wisdom_list) - 1)]; ?>
</body>
</html>