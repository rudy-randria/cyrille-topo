<?php
// index.php

// Inclure les ressources communes
include_once('../includes/resources.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'accueil</title>    
</head>
<body>
    
        <!-- Toute la structure de votre page, y compris le formulaire de connexion -->
        <div class="container">
            <div class="row justify-content-center mt-5">
                <div class="col-md-6">
                    <h2 class="text-center mb-4">Cartographie web</h2>
                    <form action="../controllers/LoginController.php" method="post">
                        <div class="mb-3">
                            <label for="username" class="form-label">Nom d'utilisateur</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" name="submit" class="btn btn-primary">Se connecter</button>
                    </form>
                </div>
            </div>
        </div>
    
</body>
</html>
