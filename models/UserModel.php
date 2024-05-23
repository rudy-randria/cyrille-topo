<?php
// models/UserModel.php
require_once __DIR__ . '/../config.php';

class UserModel {
    // Méthode pour vérifier les informations d'identification de l'utilisateur
    public function verifyCredentials($username, $password) {
        // Vous devriez implémenter ici la logique de vérification des informations d'identification
        // Par exemple, vérifier les informations d'identification dans une base de données
        // Si les informations d'identification sont valides, retournez true, sinon retournez false
        
        global $db;
        $stmt = $db->prepare("SELECT id_user,u.id_entity, nom, prenom, tel, mail, poste, login, mot_de_passe, e.name
            FROM 
                public.user_lp u
            JOIN 
                entity e ON u.id_entity = e.id_entity   -- structure de database à modifier, remplacer id_entite à id_entity dans la table user_lp
                -- executer la commande 'ALTER TABLE user_lp RENAME COLUMN id_entite TO id_entity;'
            WHERE
                login =:login AND  mot_de_passe = :mdp
        ");
        $stmt->execute([
            'login' => $username,
            'mdp' => $password
        ]);

        $user = $stmt->fetch();

        if ($user == true) {
            session_start();
            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['mdp'] = $user['mot_de_passe'];
            $_SESSION['entite'] = $user['name'];
            $_SESSION['id_entity'] = $user['id_entity'];
            return true;
        } else {
            return false;
        }
        // // En attendant, nous retournons toujours true pour l'exemple
    }
}
?>
