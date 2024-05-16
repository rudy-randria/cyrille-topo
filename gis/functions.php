<?php 
require_once('../config.php');

$couche = $_GET['couche'] ?? '';
$status = $_GET['status'] ?? '';

if ($couche == 'cercificats') {
	if ($status == 'attente') {
		recupDemande('false');
	} elseif ($status == 'validee') {
		recupDemande('true');
	} else {
		echo "erreur";
	}
} else {
	echo "Erreur";
}

// fonction pour récupérer les demandes pour commune
function recupDemande ($status) {
	global $db;
	$rec = $db->prepare("SELECT *, ST_AsText(ST_Centroid(geom)) AS centroid FROM certificats WHERE validee_publiee = '$status'");
	$rec -> execute();
	$demande = $rec->fetchAll(PDO::FETCH_ASSOC);
	echo json_encode($demande);
}

?>