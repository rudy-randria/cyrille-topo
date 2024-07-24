<?php 
// CommuneController.php

// Envoie de formulaire pour des nouvelles demandes
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['demandeRun'])) {
    // Inclure le fichier de modèle
    require_once('../models/servicefoncierModel.php');
    session_start();

    $code = $_POST['code'];
    $geom = $_POST['geom'];
    $numdemande = $_POST['numdemande'];
    $surface = $_POST['surface'];
    $observation = $_POST['observation'];
    $id_user = $_SESSION['id_user'];

    $servicefoncierModel = new servicefoncierModel();

    if (empty($geom)) {
        echo "Erreur : La géométrie ne peut pas être vide.";
        exit;
    }

    if ($_POST['couche'] === 'dpe') {
        $isSendDemande = $servicefoncierModel->dpeDemande($code, $geom, $surface, $observation, $id_user);
        if ($isSendDemande) {
            header("Location: ../maps/service-foncier.php");
            exit;
        }
    } elseif ($_POST['couche'] === 'permis') {
        $isSendDemande = $servicefoncierModel->permisDemande($numcf, $geom, $surface, $observation, $id_user);
        if ($isSendDemande) {
            header("Location: ../maps/service-foncier.php");
            exit;
        }
    } elseif ($_POST['couche'] === 'cadastre') {
        $isSendDemande = $servicefoncierModel->cadastreDemande($numcf, $geom, $surface, $observation, $id_user);
        if ($isSendDemande) {
            header("Location: ../maps/service-foncier.php");
            exit;
        }
    } else {
        echo "Erreur : Couche non reconnue.";
    }
}

// Envoie de formulaire pour les mises à jour et rectification
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['RunUpdate'])) {
    // Inclure le fichier de modèle
    require_once('../models/servicefoncierModel.php');
    session_start();

    $gid = $_POST['gid'];
    $geom = $_POST['geom'];
    $surface = $_POST['surface'];
    $observation = $_POST['observation'];
    $couche = $_POST['couche'];

    if (empty($geom)) {
        echo "Erreur : La géométrie ne peut pas être vide.";
        exit;
    }

    $servicefoncierModel = new servicefoncierModel();
    $isSendUpdate = $servicefoncierModel->dpeupdate($couche, $gid, $geom, $surface, $observation);

    if ($isSendUpdate) {
        header("Location: ../maps/service-foncier.php");
        exit;
    } else {
        echo "Erreur lors de la mise à jour.";
    }
}

// Supprimer les couches refusées
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirmRefus'])) {
    require_once('../models/servicefoncierModel.php');
    session_start();

    $gid = $_POST['gid'];
    $couche = $_POST['couche'];

    $servicefoncierModel = new servicefoncierModel();
    $isRefuseDemande = $servicefoncierModel->DeleteMap($gid, $couche);

    if ($isRefuseDemande) {
        header("Location: ../maps/service-foncier.php");
        exit;
    } else {
        echo "Erreur lors de la suppression.";
    }
}
?>
