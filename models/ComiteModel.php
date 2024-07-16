<?php 
	// ComiteModel.php
	require_once __DIR__ . '/../config.php';

	/**
	 * 
	 */
	class ComiteModel
	{
		
		function DemandArectifier ($gid, $couche, $remarque, $db = null) {
			if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
		        session_start();
		    }

		    if ($db === null) {
		        global $db;
		    }
			
			$update = $db -> prepare("UPDATE public.$couche
			SET observation=:observation, a_rectifier=true, updated_or_new = NULL
			WHERE gid = :gid");
			$update->execute(['gid' => $gid , 'observation' => $remarque]);
			
			return true;
		}

		//validation des demandes
		function DemandValider ($gid, $couche, $remarque, $db = null) {
			if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
		        session_start();
		    }

		    if ($db === null) {
		        global $db;
		    }
			$update = $db -> prepare("UPDATE public.$couche
			SET observation=:observation, validee_publiee = true, a_rectifier= NULL, updated_or_new = NULL
			WHERE gid = :gid");
			$update->execute(['gid' => $gid , 'observation' => $remarque]);
			
			return true;
		}

		function DemandRefuser ($gid, $couche, $remarque, $db = null) {
			if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
		        session_start();
		    }

		    if ($db === null) {
		        global $db;
		    }
			$update = $db -> prepare("UPDATE public.$couche
			SET observation=:observation, validee_publiee = false, a_rectifier= NULL, updated_or_new = NULL
			WHERE gid = :gid");
			$update->execute(['gid' => $gid , 'observation' => $remarque]);
			return true;
		}

		function supprimerCouche($gid, $couche) {
			if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
		        session_start();
		    }

		    if ($db === null) {
		        global $db;
		    }

		    $stmt = $db->prepare("DELETE FROM $couche WHERE gid = $gid");
			$stmt->execute();
		}
	}
?>