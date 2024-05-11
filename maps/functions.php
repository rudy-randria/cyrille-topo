<?php
require_once('../config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['demandeRun'])) {
	if ($_POST['type'] === 'cf') {
		certificatsDemande();
	} else {
		echo "Erreur";
	}
}

function certificatsDemande () {
	global $db;
	$demande = $db->prepare("INSERT INTO public.certificats(
	numcf, geom, numdemande, surface, observation, id_user)
	VALUES (:numcf, ST_Multi(ST_GeomFromText(:geom, 29702)), :numdemande, :surface, :observation, :id_user)");
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
	

 ?>
