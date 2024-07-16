<?php 
// DemandeController.php

 // envoie de formulaire pour des nouvelles demandes
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['CheckRun'])) {
	// Inclure le fichier de modèle
    require_once('../models/ComiteModel.php');
    session_start();

    $gid = $_POST['gid'];
    $couche = $_POST['couche'];
    $remarque = $_POST['remarque'];

    $ComiteModel = new ComiteModel();

    if ($_POST['resultat'] == 'rectifier') {
    	$isSendRemark = $ComiteModel-> DemandArectifier($gid, $couche, $remarque);
		if ($isSendRemark) {
			header ("Location:../maps/comite.php");
		}
	} elseif ($_POST['resultat'] == 'valider') {
		$isSendRemark = $ComiteModel-> DemandValider($gid, $couche, $remarque);
		if ($isSendRemark) {
			header ("Location:../maps/comite.php");
		}
	} elseif ($_POST['resultat'] == 'refuser') {
		$isSendRemark = $ComiteModel-> DemandRefuser($gid, $couche, $remarque);
		if ($isSendRemark) {
			header ("Location:../maps/comite.php");
		}
	} else {
		echo "Erreur";
	}
}


?>
