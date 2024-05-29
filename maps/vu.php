<?php 
require_once('../config.php'); //connexion à la base de données

$couche = $_POST['couche'] ?? '';
$gid = $_POST['gid'] ?? '';
$action = $_POST['action'] ?? '';
$attribute = $_POST['attribute']?? '';

if ($action === 'newMessage') {
	newMessage($attribute);
} elseif ($action === 'setvu') {
	setvu($couche, $gid);
}



function newMessage ($attribute ) {
	global $db;
	$stmt = $db -> query("SELECT gid FROM certificats WHERE vu = false and $attribute = true 
		UNION ALL 
		SELECT gid FROM permis WHERE vu = false and $attribute = true");

	$length = $stmt->rowCount();
		echo json_encode($length);
	
}

function setVu($couche, $gid) {
	global $db;
	$stmt = $db -> query("UPDATE $couche SET vu = true WHERE gid = $gid");
}

?>