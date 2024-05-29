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
function recupDemande ($status, $db = null) {
	if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
        session_start();
    }

    if ($db === null) {
        global $db;
    }
	$rec = $db->prepare("SELECT 'certificats' as couche, c.gid, c.numcf, c.numdemande, c.surface, c.observation, c.vu, ST_AsText(ST_Centroid(c.geom)) AS centroid, e.name as entity
		FROM certificats c
		JOIN user_lp u on c.id_user = u.id_user
		JOIN entity e on u.id_entity = e.id_entity
		WHERE updated_or_new = '$status'
		UNION ALL
		SELECT 'permis' as couche, p.gid, p.numero as numcf, p.numero as numdemande, p.surface, p.observation, p.vu, ST_AsText(ST_Centroid(p.geom)) AS centroid, e.name as entity
		FROM permis p
		JOIN user_lp u on p.id_user = u.id_user
		JOIN entity e on u.id_entity = e.id_entity
		WHERE updated_or_new = '$status'
		");
	$rec -> execute();
	$demande = $rec->fetchAll(PDO::FETCH_ASSOC);
	echo json_encode($demande);
}

function recupUpdate ($db = null) {
	if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
        session_start();
    }

    if ($db === null) {
        global $db;
    }
	$rec = $db->prepare("SELECT c.gid, c.numcf, c.numdemande, c.surface, c.observation, c.vu, ST_AsText(ST_Centroid(c.geom)) AS centroid, e.name as entity
		FROM certificats c
		JOIN user_lp u on c.id_user = u.id_user
		JOIN entity e on u.id_entity = e.id_entity
		WHERE a_rectifier = true
		UNION ALL
		SELECT p.gid, p.numero as numcf, p.numero as numdemande, p.surface, p.observation, p.vu, ST_AsText(ST_Centroid(p.geom)) AS centroid,e.name as entity
		FROM permis p
		JOIN user_lp u on p.id_user = u.id_user
		JOIN entity e on u.id_entity = e.id_entity
		WHERE a_rectifier = true
		");
	$rec -> execute();
	$demande = $rec->fetchAll(PDO::FETCH_ASSOC);
	echo json_encode($demande);
}

function recupRefusee ($db = null) {
	if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
        session_start();
    }

    if ($db === null) {
        global $db;
    }
	$rec = $db->prepare("SELECT c.*, ST_AsText(ST_Centroid(c.geom)) AS centroid, e.name as entity
		FROM certificats c
		JOIN user_lp u on c.id_user = u.id_user
		JOIN entity e on u.id_entity = e.id_entity
		WHERE validee_publiee = false");
	$rec -> execute();
	$demande = $rec->fetchAll(PDO::FETCH_ASSOC);
	echo json_encode($demande);
}

function recupAcceptee ($db = null) {
	if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
        session_start();
    }

    if ($db === null) {
        global $db;
    }
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