<?php
// models/UserModel.php
require_once __DIR__ . '/../config.php';

class UserModel {
    public function verifyCredentials($username, $password, $test = false) {
        global $db;
        $stmt = $db->prepare("SELECT id_user,u.id_entity, nom, prenom, tel, mail, poste, login, mot_de_passe, e.name
            FROM 
                public.user_lp u
            JOIN 
                entity e ON u.id_entity = e.id_entity
            WHERE
                login =:login AND  mot_de_passe = :mdp
        ");
        $stmt->execute([
            'login' => $username,
            'mdp' => $password
        ]);

        $user = $stmt->fetch();

        if ($user == true) {
            // Si vous êtes en production, démarrer la session
            if ($this->isProductionEnvironment($test)) {
                session_start();
                $_SESSION['id_user'] = $user['id_user'];
                $_SESSION['mdp'] = $user['mot_de_passe'];
                $_SESSION['entite'] = $user['name'];
                $_SESSION['id_entity'] = $user['id_entity'];
            }
            return true;
        } else {
            return false;
        }
    }

    // Méthode pour déterminer si vous êtes en environnement de production ou en mode test
    private function isProductionEnvironment($test) {
        if ($test){
            return false;
        } else{
            return true;
        }
    }
}

?>
