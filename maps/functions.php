<?php
// maps/functions.php
require_once __DIR__ . '/../config.php';

if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
	session_start();
}

// envoie de formulaire pour des nouvelles demandes
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['demandeRun'])) {
	if ($_POST['couche'] === 'certificats') {
		certificatsDemande();
	} elseif ($_POST['couche'] === 'permis') {
		permisDemande();
	} else {
		echo "erreur";
	}
}

// envoie de formulaire pour les mises à jour et rectification
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['RunUpdate'])) {
	certificatsUpdate($_POST['gid']);
}

//envoie de formulaire pour repondre une demande
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['CheckRun'])) {
	if ($_POST['couche'] === 'cf' && $_POST['resultat'] == 'rectifier') {
		DemandArectifier($_POST['gid']);
	} elseif ($_POST['couche'] === 'cf' && $_POST['resultat'] == 'valider') {
		DemandValider($_POST['gid']);
	} elseif ($_POST['couche'] === 'cf' && $_POST['resultat'] == 'refuser') {
		DemandRefuser($_POST['gid']);
	} else {
		echo "Erreur";
	}
}

// supprimer de la carte la couche sur laquelle sa demande est refusée par le comité
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirmRefus'])) {
	DeleteMap($_POST['gid'], $_POST['couche']);
}

// envoie d'une demande d'un certificat foncier par l'entite commune
function certificatsDemande($db = null) {
    if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
        session_start();
    }

    if ($db === null) {
        global $db;
    }
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

// envoie d'une demande d'un permis de construire par l'entite commune
function permisDemande($db = null) {
    if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
        session_start();
    }

    if ($db === null) {
        global $db;
    }
    
    $demande = $db->prepare("INSERT INTO public.permis(
	numero, geom, surface, observation, id_user, updated_or_new)
	VALUES (:numcf, ST_Multi(ST_GeomFromText(:geom, 29702)), :surface, :observation, :id_user, true)");
	$demande->execute([
		'numcf' => $_POST['numcf'],
		'geom' => $_POST['geom'],
		'surface' => $_POST['surface'],
		'observation' => $_POST['observation'],
		'id_user' => $_SESSION['id_user']
	]);
    
    echo "Demande envoyée";
}

// envoie d'une mise à jour après rectification d'un certificat foncier par l'entite commune
function certificatsUpdate ($gid, $db = null) {
	if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
        session_start();
    }

    if ($db === null) {
        global $db;
    }
	$demande = $db->prepare("UPDATE public.certificats
	SET geom=ST_Multi(ST_GeomFromText(:geom, 29702)), surface=:surface, observation=:observation, validee_publiee= NULL, updated_or_new=TRUE,a_rectifier=NULL, vu=false
	WHERE gid = :gid");
	$demande->execute([
		'geom' => $_POST['geom'],
		'surface' => $_POST['surface'],
		'observation' => $_POST['observation'],
		'gid' => $gid
	]);
	echo "Mise à jour faite !";
}

// envoi des rectifications recommandées
function DemandArectifier ($gid, $db = null) {
	if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
        session_start();
    }

    if ($db === null) {
        global $db;
    }
	
	$update = $db -> prepare("UPDATE public.certificats
	SET observation=:observation, a_rectifier=true, updated_or_new = NULL
	WHERE gid = :gid");
	$update->execute(['gid' => $gid , 'observation' => $_POST['remarque']]);
	echo "Remarques envoyées";
}

//validation des demandes
function DemandValider ($gid, $db = null) {
	if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
        session_start();
    }

    if ($db === null) {
        global $db;
    }
	$update = $db -> prepare("UPDATE public.certificats
	SET observation=:observation, validee_publiee = true, a_rectifier= NULL, updated_or_new = NULL
	WHERE gid = :gid");
	$update->execute(['gid' => $gid , 'observation' => $_POST['remarque']]);
	echo "Remarques envoyées";
}

// demande refusée
function DemandRefuser ($gid, $db = null) {
	if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
        session_start();
    }

    if ($db === null) {
        global $db;
    }
	$update = $db -> prepare("UPDATE public.certificats
	SET observation=:observation, validee_publiee = false, a_rectifier= NULL, updated_or_new = NULL
	WHERE gid = :gid");
	$update->execute(['gid' => $gid , 'observation' => $_POST['remarque']]);
	echo "Remarques envoyées";
}

// supprimer les couches refusées sur la carte	
function DeleteMap($gid, $couche) {
	if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
        session_start();
    }

    if ($db === null) {
        global $db;
    }
	$stmt = $db->prepare("DELETE FROM $couche WHERE gid = $gid");
	$stmt->execute();
	echo "Couche supprimée avec succes !";
}
 ?>
