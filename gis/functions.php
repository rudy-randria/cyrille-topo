<?php 
require_once __DIR__ . '/../config.php';


$couche = $_GET['couche'] ?? '';
$status = $_GET['status'] ?? '';

if ($couche == 'cercificats') {
	if ($status == 'attente') {
		recupDemande('true');
	} elseif ($status == 'a_rectifier') {
		recupUpdate();
	} elseif ($status == 'refusee') {
		recupRefusee();
	} elseif ($status == 'acceptee') {
		recupAcceptee();
	}
	 else {
		echo "erreur";
	}
}  
else {
	echo "Erreur";
}

// fonction pour récupérer les demandes pour commune
function recupDemande ($status) {
	global $db;
	$rec = $db->prepare("SELECT c.*, ST_AsText(ST_Centroid(c.geom)) AS centroid, e.name as entity
		FROM certificats c
		JOIN user_lp u on c.id_user = u.id_user
		JOIN entity e on u.id_entity = e.id_entity
		WHERE updated_or_new = '$status'");
	$rec -> execute();
	$demande = $rec->fetchAll(PDO::FETCH_ASSOC);
	echo json_encode($demande);
}

function recupUpdate () {
	global $db;
	$rec = $db->prepare("SELECT c.*, ST_AsText(ST_Centroid(c.geom)) AS centroid, e.name as entity
		FROM certificats c
		JOIN user_lp u on c.id_user = u.id_user
		JOIN entity e on u.id_entity = e.id_entity
		WHERE a_rectifier = true");
	$rec -> execute();
	$demande = $rec->fetchAll(PDO::FETCH_ASSOC);
	echo json_encode($demande);
}

function recupRefusee () {
	global $db;
	$rec = $db->prepare("SELECT c.*, ST_AsText(ST_Centroid(c.geom)) AS centroid, e.name as entity
		FROM certificats c
		JOIN user_lp u on c.id_user = u.id_user
		JOIN entity e on u.id_entity = e.id_entity
		WHERE validee_publiee = false");
	$rec -> execute();
	$demande = $rec->fetchAll(PDO::FETCH_ASSOC);
	echo json_encode($demande);
}

function recupAcceptee () {
	global $db;
	$rec = $db->prepare("SELECT c.*, ST_AsText(ST_Centroid(c.geom)) AS centroid, e.name as entity
		FROM certificats c
		JOIN user_lp u on c.id_user = u.id_user
		JOIN entity e on u.id_entity = e.id_entity
		WHERE validee_publiee = true");
	$rec -> execute();
	$demande = $rec->fetchAll(PDO::FETCH_ASSOC);
	echo json_encode($demande);
}

?>