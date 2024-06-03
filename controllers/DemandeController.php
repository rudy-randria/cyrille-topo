<?php 
// DemandeController.php

 // envoie de formulaire pour des nouvelles demandes
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['demandeRun'])) {
	// Inclure le fichier de modèle
    require_once('../models/DemandeModel.php');
    session_start();

    $numcf = $_POST['numcf'];
    $geom = $_POST['geom'];
    $numdemande = $_POST['numdemande'];
    $surface = $_POST['surface'];
    $observation = $_POST['observation'];
    $id_user = $_SESSION['id_user'];

    $DemandeModel = new DemandeModel();
    
	if ($_POST['couche'] === 'certificats') {
		$isSendDemande =$DemandeModel->certificatsDemande($numcf, $geom, $numdemande, $surface, $observation, $id_user);

		if ($isSendDemande) {
			
			header("Location: ../maps/maps.php");
		}
	} elseif ($_POST['couche'] === 'permis') {
		$isSendDemande =$DemandeModel->permisDemande($numcf, $geom, $surface, $observation, $id_user);

		if ($isSendDemande) {
			header("Location: ../maps/maps.php");
			$message = "Demande envoyée !";
		}
	} else {
		echo "erreur";
	}
}


?>
