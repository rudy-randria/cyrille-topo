<?php 
	// DemandeModel.php
	require_once __DIR__ . '/../config.php';

	/**
	 * 
	 */
	class DemandeModel
	{
		
		function certificatsDemande($numcf, $geom, $numdemande, $surface, $observation, $id_user, $db = null) {
		    if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
		        session_start();
		    }

		    if ($db === null) {
		        global $db;
		    }
		    $demande = $db->prepare("INSERT INTO public.certificats(
			numcf, geom, numdemande, surface, observation, id_user, updated_or_new)
			VALUES (:numcf, ST_Multi(ST_GeomFromText(:geom, 29702)), :numdemande, :surface, :observation, :id_user, true)");
			$demande->execute([
				'numcf' => $numcf,
				'geom' => $geom,
				'numdemande' => $numdemande,
				'surface' => $surface,
				'observation' => $observation,
				'id_user' => $id_user
			]);
			
			$_SESSION['message'] = "Demande N° " .$numdemande . " d'un certificat foncier envoyée avec success !";

		    return true;
		}

		// envoie d'une demande d'un permis de construire par l'entite commune
		function permisDemande($numcf, $geom, $surface, $observation, $id_user, $db = null) {
		    if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
		        session_start();
		    }

		    if ($db === null) {
		        global $db;
		    }
		    
		    $demande = $db->prepare("INSERT INTO public.permis(
			numero, geom, surface, observation, id_user, updated_or_new)
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
	}
?>