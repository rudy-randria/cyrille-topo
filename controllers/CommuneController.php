<?php 
// CommuneController.php

 // envoie de formulaire pour des nouvelles demandes
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['demandeRun'])) {
	// Inclure le fichier de modèle
    require_once('../models/CommuneModel.php');
    session_start();

    $numcf = $_POST['numcf'];
    $geom = $_POST['geom'];
    $numdemande = $_POST['numdemande'];
    $surface = $_POST['surface'];
    $observation = $_POST['observation'];
    $id_user = $_SESSION['id_user'];

    $CommuneModel = new CommuneModel();
    
	if ($_POST['couche'] === 'certificats') {
		$isSendDemande =$CommuneModel->certificatsDemande($numcf, $geom, $numdemande, $surface, $observation, $id_user);

		if ($isSendDemande) {
			
			header("Location: ../maps/commune.php");
		}
	} elseif ($_POST['couche'] === 'permis') {
		$isSendDemande =$CommuneModel->permisDemande($numcf, $geom, $surface, $observation, $id_user);

		if ($isSendDemande) {
			header("Location: ../maps/commune.php");
			$message = "Demande envoyée !";
		}
	} elseif ($_POST['couche'] === 'cadastre') {
		$isSendDemande =$serviceFoncierModel->cadastreDemande($numcf, $geom, $surface, $observation, $id_user);

		if ($isSendDemande) {
			
			header("Location: ../maps/commune.php");
		}
	} else {
		echo "erreur";
	}
}

// envoie de formulaire pour les mises à jour et rectification
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['RunUpdate'])) {
	// Inclure le fichier de modèle
    require_once('../models/CommuneModel.php');
    session_start();

    $gid = $_POST['gid'];
    $geom = $_POST['geom'];
    $surface = $_POST['surface'];
    $observation = $_POST['observation'];
    $couche = $_POST['couche'];

    $CommuneModel = new CommuneModel();
    $isSendUpdate = $CommuneModel->certificatsUpdate ($couche, $gid, $geom, $surface, $observation);
    if ($isSendUpdate) {
    	header("Location: ../maps/commune.php");
    }
}

// supprimer les couches refusées
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirmRefus'])) {
	require_once('../models/CommuneModel.php');
    session_start();

    $gid = $_POST['gid'];
	$couche = $_POST['couche'];

	$CommuneModel = new CommuneModel();
	$isRefuseDemande = $CommuneModel-> DeleteMap($gid, $couche);
	if ($isRefuseDemande) {
    	header("Location: ../maps/commune.php");
    }
}


?>
