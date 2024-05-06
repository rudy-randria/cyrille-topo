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
        header("Location: ../maps/maps.php");
        exit();
    } else {
        // Sinon, rediriger vers la page de connexion avec un message d'erreur
        header("Location: ../views/login.php?error=invalid_credentials");
        exit();
    }
}
?>
