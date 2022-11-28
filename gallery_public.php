<?php
	//session_start();
	require_once "classes/SessionManager.class.php";
	SessionManager::sessionStart("vp", 0, "/~rinde/vp_2022/Ryhm-2/", "greeny.cs.tlu.ee");
	//kontrollin, kas oleme sisse loginud
	if(!isset($_SESSION["user_id"])){
		header("Location: page.php");
		exit();
	}
	
	//logime välja
	if(isset($_GET["logout"])){
		session_destroy();
		header("Location: page.php");
		exit();
	}
	
	require_once "fnc_gallery.php";
	$privacy = 2;
	$page = 1;
	$limit = 5;
	$photo_count = count_photos($privacy);
	//kontrollime, mis lehel oleme ja kas selle numbriga leht on reaalne
	if(!isset($_GET["page"]) or $_GET["page"] < 1){
		$page = 1;
	} elseif(round($_GET["page"] - 1) * $limit >= $photo_count){
		$page = ceil($photo_count / $limit);
	} else {
		$page = $_GET["page"];
	}
		
	$style_sheets = ["style/gallery.css"];
	
	require_once "header.php";
	
	echo "<p>Sisse loginud: " .$_SESSION["firstname"] ." " .$_SESSION["lastname"] .".</p> \n";
?>
<ul>
	<li><a href="?logout=1">Logi välja</a></li>
	<li><a href="home.php">Avalehele</a></li>
</ul>
<hr>

<dialog id="modal" class="modalarea">
	<span id="modalclose" class="modalclose">&times;</span>
	<div class="modalhorizontal">
		<div class="modalvertical">
			<p id="modalcaption"></p>
			<img id="modalimage" src="pics/empty.png" alt="galeriipilt">
		</div>
	</div>
</dialog>

<h2>Galerii</h2>
<p>
<?php
	//Eelmine leht | Järgmine leht
	//<span>Eelmine leht</span> | <span><a href="?page=2">Järgmine leht</a></span>
	if($page > 1){
		echo '<span><a href="?page=' .($page - 1) .'">Eelmine leht</a></span>';
	} else {
		echo "<span>Eelmine leht</span>";
	}
	echo " | ";
	if($page * $limit < $photo_count){
		echo '<span><a href="?page=' .($page + 1) .'">Järgmine leht</a></span>';
	} else {
		echo "<span>Järgmine leht</span>";
	}
?>
</p>
<div class="gallery">
	<?php echo read_public_photo_thumbs($privacy, $page, $limit);?>
</div>

<?php require_once "footer.php"; ?>