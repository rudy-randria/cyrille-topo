<?php 
require_once('../config.php'); //connexion à la base de données

$couche = $_POST['couche'] ?? '';
$gid = $_POST['gid'] ?? '';
$action = $_POST['action'] ?? '';
$attribute = $_POST['attribute']?? '';

if ($action === 'newMessageCommune') {
	newMessageCommune($attribute);
}
elseif ($action === 'newMessageSF') {
	newMessageSF($attribute);
}
elseif ($action === 'newMessageComite') {
	newMessageComite($attribute);
}
 elseif ($action === 'setvu') {
	setvu($couche, $gid);
}
elseif ($action === 'newMessageAmenagement') {
	newMessageAmenagement($attribute);
}

function newMessageComite ($attribute ) {
	global $db;
	$stmt = $db -> query("SELECT gid FROM certificats WHERE vu = false and $attribute = true 
		UNION ALL 
		SELECT gid FROM permis WHERE vu = false and $attribute = true
		UNION ALL
		SELECT gid FROM dpe WHERE vu = false and $attribute = true
		UNION ALL
		SELECT gid FROM pudi WHERE vu = false and $attribute = true");

	$length = $stmt->rowCount();
		echo json_encode($length);
	
}

function newMessageCommune ($attribute ) {
	global $db;
	$stmt = $db -> query("SELECT gid FROM certificats WHERE vu = false and $attribute = true 
		UNION ALL 
		SELECT gid FROM permis WHERE vu = false and $attribute = true");

	$length = $stmt->rowCount();
		echo json_encode($length);
	
}

function newMessageAmenagement ($attribute ) {
	global $db;
	$stmt = $db -> query("SELECT gid FROM pudi WHERE vu = false and $attribute = true 
	");

	$length = $stmt->rowCount();
		echo json_encode($length);
}


function newMessageSF ($attribute ) {
	global $db;
	$stmt = $db -> query(" 
		SELECT gid FROM dpe WHERE vu = false and $attribute = true");

	$length = $stmt->rowCount();
		echo json_encode($length);
	
}

function setVu($couche, $gid) {
	global $db;
	$stmt = $db -> query("UPDATE $couche SET vu = true WHERE gid = $gid");
}

?>