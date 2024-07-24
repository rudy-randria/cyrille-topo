<?php
// database/db.php
//connection database
function connectedb(){
	if (!defined('hoste')) {
    	define('hoste', 'localhost');
	}
    if (!defined('BD_NAME')) {
        define('BD_NAME', 'bd_centrale_rec');
    }
    if (!defined('users')) {
        define('users', 'postgres');
    }
     if (!defined('mdp')) {
        define('mdp', 'cul');
     }
	try{
        $db = new PDO("pgsql:host=" . hoste . ";dbname=" . BD_NAME, users, strrev(mdp));
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
	}catch(PDOException $e){
        echo "Erreur de connexion à la base de données : " . $e->getMessage();
        return null;
    }
}

 ?>