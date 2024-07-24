<?php 
// CommuneController.php

 // envoie de formulaire pour des nouvelles demandes
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['demandeRun'])) {
    // Inclure le fichier de modèle
    require_once('../models/AmenagementModel.php');
    session_start();

    $numcf = $_POST['numcf'];
    $geom = $_POST['geom'];
    $surface = $_POST['surface'];
    $observation = $_POST['observation'];
    $id_user = $_SESSION['id_user'];

    $AmenagementModel = new AmenagementModel();
    
    if ($_POST['couche'] === 'pudi') {
        $isSendDemande =$AmenagementModel->pudiDemande($numcf, $geom, $surface, $observation, $id_user);

        if ($isSendDemande) {
            
            header("Location: ../maps/amenagement.php");
        }
    } else {
        echo "erreur";
    }
}

// envoie de formulaire pour les mises à jour et rectification
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['RunUpdate'])) {
    // Inclure le fichier de modèle
    require_once('../models/AmenagementModel.php');
    session_start();

    $gid = $_POST['gid'];
    $geom = $_POST['geom'];
    $surface = $_POST['surface'];
    $observation = $_POST['observation'];
    $couche = $_POST['couche'];

    $AmenagementModel = new AmenagementModel();
    $isSendUpdate = $AmenagementModel->certificatsUpdate ($couche, $gid, $geom, $surface, $observation);
    if ($isSendUpdate) {
        header("Location: ../maps/amenagement.php");
    }
}

// supprimer les couches refusées
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirmRefus'])) {
    require_once('../models/AmenagementModel.php');
    session_start();

    $gid = $_POST['gid'];
    $couche = $_POST['couche'];

    $AmenagementModel = new AmenagementModel();
    $isRefuseDemande = $AmenagementModel-> DeleteMap($gid, $couche);
    if ($isRefuseDemande) {
        header("Location: ../maps/amenagement.php");
    }
}


?>
