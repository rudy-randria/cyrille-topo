<?php 
	// servicefoncierModel.php
	require_once __DIR__ . '/../config.php';

	/**
	 * 
	 */
	class servicefoncierModel
	{
		
		function dpeDemande($code, $geom, $surface, $observation, $id_user, $db = null) {
		    if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
		        session_start();
		    }

		    if ($db === null) {
		        global $db;
		    }
		    $demande = $db->prepare("INSERT INTO public.dpe(code, geom, observation, id_utilisateur, updated_or_new, surface)
			VALUES (:code, ST_Multi(ST_GeomFromText(:geom, 29702)),  :observation, :id_user, true, :surface)");
			$demande->execute([
				'code' => $code,
				'geom' => $geom,
				'surface' => $surface,
				'observation' => $observation,
				'id_user' => $id_user
			]);
			
			$_SESSION['message'] = "Demande N° " .$code . " d'un domaine privé de l'Etat envoyée avec success !";

		    return true;
		}

		// envoie d'une demande d'un permis de construire par l'entite commune
		function cadastreDemande($numcf, $geom, $surface, $observation, $id_user, $db = null) {
		    if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
		        session_start();
		    }

		    if ($db === null) {
		        global $db;
		    }
		    
		    $demande = $db->prepare("INSERT INTO public.permis(
			numero, geom, surface, observation, id_utilisateur, updated_or_new)
			VALUES (:numcf, ST_Multi(ST_GeomFromText(:geom, 29702)), :surface, :observation, :id_user, true)");
			$demande->execute([
				'numcf' => $numcf,
				'geom' => $geom,
				'surface' => $surface,
				'observation' => $observation,
				'id_user' => $id_user
			]);
		    
		    return true;
		}

		// envoie d'une mise à jour après rectification d'un certificat foncier par l'entite commune
		function dpeupdate ($couche, $gid, $geom, $surface, $observation, $db = null) {
			if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
		        session_start();
		    }

		    if ($db === null) {
		        global $db;
		    }
			$demande = $db->prepare("UPDATE $couche
			SET geom=ST_Multi(ST_GeomFromText(:geom, 29702)), surface=:surface, observation=:observation, validee_publiee= NULL, updated_or_new=TRUE,a_rectifier=NULL, vu=false
			WHERE gid = :gid");
			$demande->execute([
				'geom' => $geom,
				'surface' => $surface,
				'observation' => $observation,
				'gid' => $gid
			]);                                                                                                                                                          
			return true;
		}

		// supprimer les couches refusées sur la carte	
		function DeleteMap($gid, $couche, $db = null) {
			if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
		        session_start();
		    }

		    if ($db === null) {
		        global $db;
		    }
			$stmt = $db->prepare("DELETE FROM $couche WHERE gid = $gid");
			$stmt->execute();
			echo "Couche supprimée avec succes !";
		}
	}
?>