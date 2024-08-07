<?php
// controllers/LoginController.php

// Si les données du formulaire de connexion ont été soumises
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Inclure le fichier de modèle
    require_once('../models/UserModel.php');

    // Récupérer les données du formulaire
    $username = $_POST['username'];
    $password = $_POST['password'];
    // Appeler une méthode de modèle pour vérifier les informations d'identification
    $userModel = new UserModel();
    $isValidUser = $userModel->verifyCredentials($username, $password);

    // Si les informations d'identification sont valides, rediriger vers la page d'accueil
    if ($isValidUser) {
        if ($_SESSION['id_entity'] == 6) { //si l'utilisateur connecté est la comité de validation 
            header("Location: ../maps/comite.php");
        } elseif ($_SESSION['id_entity'] == 1) {  //si l'utilisateur connecté est la .
            header("Location: ../maps/admin.php");
        }
        elseif ($_SESSION['id_entity'] == 4) {
            header("Location: ../maps/commune.php");
        }
        elseif ($_SESSION['id_entity'] == 3) {
            header("Location: ../maps/service-foncier.php");
        }
        elseif ($_SESSION['id_entity'] == 5) {
            header("Location: ../maps/amenagement.php");
        } else { //si l'utilisateur connecté est un parmis les autres entités
            header("Location: ../maps/maps.php");
        }
        exit();
    } else {
        // Sinon, rediriger vers la page de connexion avec un message d'erreur
        header("Location: ../views/login.php?error=invalid_credentials");
        exit();
    }
}
?>
