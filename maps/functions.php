<?php
require_once __DIR__ . '/../config.php';

if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
	session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['demandeRun'])) {
	if ($_POST['type'] === 'cf') {
		certificatsDemande();
	} else {
		echo "Erreur";
	}
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['CheckRun'])) {
	if ($_POST['type'] === 'cf' && $_POST['resultat'] == 'rectifier') {
		DemandArectifier($_POST['gid']);
	} elseif ($_POST['type'] === 'cf' && $_POST['resultat'] == 'valider') {
		DemandValider($_POST['gid']);
	} elseif ($_POST['type'] === 'cf' && $_POST['resultat'] == 'refuser') {
		DemandRefuser($_POST['gid']);
	} else {
		echo "Erreur";
	}
}
/*
function certificatsDemande () {
	
	global $db;
	$demande = $db->prepare("INSERT INTO public.certificats(
	numcf, geom, numdemande, surface, observation, id_user, updated_or_new)
	VALUES (:numcf, ST_Multi(ST_GeomFromText(:geom, 29702)), :numdemande, :surface, :observation, :id_user, true)");
	$demande->execute([
		'numcf' => $_POST['numcf'],
		'geom' => $_POST['geom'],
		'numdemande' => $_POST['numdemande'],
		'surface' => $_POST['surface'],
		'observation' => $_POST['observation'],
		'id_user' => $_SESSION['id_user']
	]);
	echo "Demande envoyée";
}
*/

function certificatsDemande($db = null) {
    if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
        session_start();
    }

    if ($db === null) {
        global $db;
    }

    $demande = $db->prepare("INSERT INTO public.certificats(
    numcf, geom, numdemande, surface, observation, id_utilisateur, updated_or_new)
    VALUES (:numcf, ST_Multi(ST_GeomFromText(:geom, 29702)), :numdemande, :surface, :observation, :id_user, true)");
    $demande->execute([
        'numcf' => $_POST['numcf'],
        'geom' => $_POST['geom'],
        'numdemande' => $_POST['numdemande'],
        'surface' => $_POST['surface'],
        'observation' => $_POST['observation'],
        'id_user' => $_SESSION['id_user']
    ]);
    echo "Demande envoyée";
}

function permisDemande () {
	global $db;
	$demande = $db->prepare("INSERT INTO public.certificats(
	numcf, geom, numdemande, surface, observation, id_user, updated_or_new)
	VALUES (:numcf, ST_Multi(ST_GeomFromText(:geom, 29702)), :numdemande, :surface, :observation, :id_user, false)");
	$demande->execute([
		'numcf' => $_POST['numcf'],
		'geom' => $_POST['geom'],
		'numdemande' => $_POST['numdemande'],
		'surface' => $_POST['surface'],
		'observation' => $_POST['observation'],
		'id_user' => $_SESSION['id_user']
	]);
	echo "Demande envoyée";
}

function DemandArectifier ($gid) {
	global $db;
	$update = $db -> prepare("UPDATE public.certificats
	SET observation=:observation, a_rectifier=true, updated_or_new = NULL
	WHERE gid = :gid");
	$update->execute(['gid' => $gid , 'observation' => $_POST['remarque']]);
	echo "Remarques envoyées";
}

function DemandValider ($gid) {
	global $db;
	$update = $db -> prepare("UPDATE public.certificats
	SET observation=:observation, validee_publiee = true, a_rectifier= NULL, updated_or_new = NULL
	WHERE gid = :gid");
	$update->execute(['gid' => $gid , 'observation' => $_POST['remarque']]);
	echo "Remarques envoyées";
}

function DemandRefuser ($gid) {
	global $db;
	$update = $db -> prepare("UPDATE public.certificats
	SET observation=:observation, validee_publiee = false, a_rectifier= NULL, updated_or_new = NULL
	WHERE gid = :gid");
	$update->execute(['gid' => $gid , 'observation' => $_POST['remarque']]);
	echo "Remarques envoyées";
}
	

 ?>
