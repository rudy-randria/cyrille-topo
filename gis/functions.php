<?php 
require_once __DIR__ . '/../config.php';


$entite = $_GET['entite'] ?? '';
$status = $_GET['status'] ?? '';

if ($entite == 'commune') {
	if ($status == 'attente') {
		recupDemandeCommune('true');
	} elseif ($status == 'a_rectifier') {
		recupUpdateCommune();
	} elseif ($status == 'refusee') {
		recupRefuseeCommune();
	} elseif ($status == 'acceptee') {
		recupAcceptee();
	}
	 else {
		echo "erreur";
	}
} 
elseif ($entite == 'serviceFoncier') {
	if ($status == 'attente') {
		recupDemandeSF('true');
	} elseif ($status == 'a_rectifier') {
		recupUpdateSF();
	} elseif ($status == 'refusee') {
		recupRefuseeSF();
	} elseif ($status == 'acceptee') {
		recupAcceptee();
	}
	 else {
		echo "erreur";
	}
}
elseif ($entite == 'comite') {
	if ($status == 'attente') {
		recupDemandeComite('true');
	} elseif ($status == 'a_rectifier') {
		recupUpdateComite();
	} elseif ($status == 'refusee') {
		recupRefuseeSF();
	} elseif ($status == 'acceptee') {
		recupAcceptee();
	}
	 else {
		echo "erreur";
	}
}
elseif ($entite == 'amenagement') {
	if ($status == 'attente') {
		recupDemandeAmenagement('true');
	} elseif ($status == 'a_rectifier') {
		recupUpdateAmenagement();
	} elseif ($status == 'refusee') {
		recupRefuseeAmenagement();
	} elseif ($status == 'acceptee') {
		recupAcceptee();
	}
	 else {
		echo "erreur";
	}
}
else {
	echo "Erreur";
}

// fonction pour recuperer tous les demandes
function recupDemandeComite ($status, $db = null) {
	if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
        session_start();
    }

    if ($db === null) {
        global $db;
    }
	$rec = $db->prepare("SELECT 'certificats' as couche, c.gid, c.numcf, c.numdemande, c.surface, c.observation, c.vu, ST_AsText(ST_Centroid(c.geom)) AS centroid, e.name as entity
		FROM certificats c
		JOIN user_lp u on c.id_utilisateur = u.id_user
		JOIN entity e on u.id_entite  = e.id_entity
		WHERE updated_or_new = '$status'
		UNION ALL
		SELECT 'permis' as couche, p.gid, p.numero as numcf, p.numero as numdemande, p.surface, p.observation, p.vu, ST_AsText(ST_Centroid(p.geom)) AS centroid, e.name as entity
		FROM permis p
		JOIN user_lp u on p.id_utilisateur = u.id_user
		JOIN entity e on u.id_entite = e.id_entity
		WHERE updated_or_new = '$status'
		UNION ALL
		SELECT 'dpe' as couche, dpe.gid, dpe.code as numcf, ' ' as numdemande, dpe.surface, dpe.observation, dpe.vu, ST_AsText(ST_Centroid(dpe.geom)) AS centroid, e.name as entity
		FROM dpe dpe
		JOIN user_lp u on dpe.id_utilisateur = u.id_user
		JOIN entity e on u.id_entite = e.id_entity
		WHERE updated_or_new = '$status'
		UNION ALL
		SELECT 'cadastre' as couche, ca.gid, ca.num_parcelle as numcf, ' ' as numdemande, ca.surface, ca.observation, ca.vu, ST_AsText(ST_Centroid(ca.geom)) AS centroid, e.name as entity
		FROM cadastre ca
		JOIN user_lp u on ca.id_utilisateur = u.id_user
		JOIN entity e on u.id_entite = e.id_entity
		WHERE updated_or_new = '$status'
		UNION ALL 
		SELECT 'pudi' as couche, pudi.gid, pudi.code as numcf, ' ' as numdemande, pudi.surface_ha as surface, pudi.observation, pudi.vu, ST_AsText(ST_Centroid(pudi.geom)) AS centroid, e.name as entity
		FROM pudi pudi
		JOIN user_lp u on pudi.id_utilisateur = u.id_user
		JOIN entity e on u.id_entite = e.id_entity
		WHERE updated_or_new = '$status'
		");
	$rec -> execute();
	$demande = $rec->fetchAll(PDO::FETCH_ASSOC);
	echo json_encode($demande);
}

function recupDemandeAmenagement ($status, $db = null) {
	if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
        session_start();
    }

    if ($db === null) {
        global $db;
    }
	$rec = $db->prepare("
		SELECT 'pudi' as couche, pudi.gid, pudi.code as numcf, ' ' as numdemande, pudi.surface_ha as surface, pudi.observation, pudi.vu, ST_AsText(ST_Centroid(pudi.geom)) AS centroid, e.name as entity
		FROM pudi pudi
		JOIN user_lp u on pudi.id_utilisateur = u.id_user
		JOIN entity e on u.id_entite = e.id_entity
		WHERE updated_or_new = '$status'
		");
	$rec -> execute();
	$demande = $rec->fetchAll(PDO::FETCH_ASSOC);
	echo json_encode($demande);
}

// fonction pour récupérer les demandes pour commune
function recupDemandeCommune ($status, $db = null) {
	if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
        session_start();
    }

    if ($db === null) {
        global $db;
    }
	$rec = $db->prepare("SELECT 'certificats' as couche, c.gid, c.numcf, c.numdemande, c.surface, c.observation, c.vu, ST_AsText(ST_Centroid(c.geom)) AS centroid, e.name as entity
		FROM certificats c
		JOIN user_lp u on c.id_utilisateur = u.id_user
		JOIN entity e on u.id_entite  = e.id_entity
		WHERE updated_or_new = '$status'
		UNION ALL
		SELECT 'permis' as couche, p.gid, p.numero as numcf, p.numero as numdemande, p.surface, p.observation, p.vu, ST_AsText(ST_Centroid(p.geom)) AS centroid, e.name as entity
		FROM permis p
		JOIN user_lp u on p.id_utilisateur = u.id_user
		JOIN entity e on u.id_entite = e.id_entity
		WHERE updated_or_new = '$status'
		");
	$rec -> execute();
	$demande = $rec->fetchAll(PDO::FETCH_ASSOC);
	echo json_encode($demande);
}

function recupDemandeSF ($status, $db = null) {
	if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
        session_start();
    }

    if ($db === null) {
        global $db;
    }
	$rec = $db->prepare("
		SELECT 'dpe' as couche, dpe.gid, dpe.code as numcf, ' ' as numdemande, dpe.surface, dpe.observation, dpe.vu, ST_AsText(ST_Centroid(dpe.geom)) AS centroid, e.name as entity
		FROM dpe dpe
		JOIN user_lp u on dpe.id_utilisateur = u.id_user
		JOIN entity e on u.id_entite = e.id_entity
		WHERE updated_or_new = '$status'
		UNION ALL
		SELECT 'cadastre' as couche, ca.gid, ca.num_parcelle as numcf, ' ' as numdemande, ca.surface, ca.observation, ca.vu, ST_AsText(ST_Centroid(ca.geom)) AS centroid, e.name as entity
		FROM cadastre ca
		JOIN user_lp u on ca.id_utilisateur = u.id_user
		JOIN entity e on u.id_entite = e.id_entity
		WHERE updated_or_new = '$status'
		");
	$rec -> execute();
	$demande = $rec->fetchAll(PDO::FETCH_ASSOC);
	echo json_encode($demande);
}

function recupUpdateCommune ($db = null) {
	if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
        session_start();
    }

    if ($db === null) {
        global $db;
    }
	$rec = $db->prepare("SELECT 'Commune' as entite, 'certificats' as couche, c.gid, c.numcf, c.numdemande, c.surface, c.observation, c.vu, ST_AsText(ST_Centroid(c.geom)) AS centroid, e.name as entity
			FROM certificats c
			JOIN user_lp u on c.id_utilisateur = u.id_user
			JOIN entity e on u.id_entite = e.id_entity
			WHERE a_rectifier = true
		UNION ALL
			SELECT 'Commune' as entite, 'permis' as couche, p.gid, p.numero as numcf, p.numero as numdemande, p.surface, p.observation, p.vu, ST_AsText(ST_Centroid(p.geom)) AS centroid,e.name as entity
			FROM permis p
			JOIN user_lp u on p.id_utilisateur = u.id_user
			JOIN entity e on u.id_entite = e.id_entity
			WHERE a_rectifier = true
		");
	$rec -> execute();
	$demande = $rec->fetchAll(PDO::FETCH_ASSOC);
	echo json_encode($demande);
}

function recupUpdateSF ($db = null) {
	if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
        session_start();
    }

    if ($db === null) {
        global $db;
    }
	$rec = $db->prepare("
			SELECT 'Service Foncier' as entite, 'dpe' as couche, dpe.gid, dpe.code as numcf, ' ' as numdemande, dpe.surface, dpe.observation, dpe.vu, ST_AsText(ST_Centroid(dpe.geom)) AS centroid,e.name as entity
			FROM dpe dpe
			JOIN user_lp u on dpe.id_utilisateur = u.id_user
			JOIN entity e on u.id_entite = e.id_entity
			WHERE a_rectifier = true
		");
	$rec -> execute();
	$demande = $rec->fetchAll(PDO::FETCH_ASSOC);
	echo json_encode($demande);
}

function recupUpdateComite ($db = null) {
	if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
        session_start();
    }

    if ($db === null) {
        global $db;
    }
	$rec = $db->prepare("SELECT 'Commune' as entite, 'certificats' as couche, c.gid, c.numcf, c.numdemande, c.surface, c.observation, c.vu, ST_AsText(ST_Centroid(c.geom)) AS centroid, e.name as entity
			FROM certificats c
			JOIN user_lp u on c.id_utilisateur = u.id_user
			JOIN entity e on u.id_entite = e.id_entity
			WHERE a_rectifier = true
		UNION ALL
			SELECT 'Commune' as entite, 'permis' as couche, p.gid, p.numero as numcf, p.numero as numdemande, p.surface, p.observation, p.vu, ST_AsText(ST_Centroid(p.geom)) AS centroid,e.name as entity
			FROM permis p
			JOIN user_lp u on p.id_utilisateur = u.id_user
			JOIN entity e on u.id_entite = e.id_entity
			WHERE a_rectifier = true
		UNION ALL
			SELECT 'Service Foncier' as entite, 'dpe' as couche, dpe.gid, dpe.code as numcf, ' ' as numdemande, dpe.surface, dpe.observation, dpe.vu, ST_AsText(ST_Centroid(dpe.geom)) AS centroid,e.name as entity
			FROM dpe dpe
			JOIN user_lp u on dpe.id_utilisateur = u.id_user
			JOIN entity e on u.id_entite = e.id_entity
			WHERE a_rectifier = true
		UNION ALL 
			SELECT 'Aménagement' as entite, 'pudi' as couche, pudi.gid, pudi.code as numcf, ' ' as numdemande, pudi.surface_ha as surface, pudi.observation, pudi.vu, ST_AsText(ST_Centroid(pudi.geom)) AS centroid,e.name as entity
			FROM pudi pudi
			JOIN user_lp u on pudi.id_utilisateur = u.id_user
			JOIN entity e on u.id_entite = e.id_entity
			WHERE a_rectifier = true
		");
	$rec -> execute();
	$demande = $rec->fetchAll(PDO::FETCH_ASSOC);
	echo json_encode($demande);
}

function recupUpdateAmenagement ($db = null) {
	if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
        session_start();
    }

    if ($db === null) {
        global $db;
    }
	$rec = $db->prepare("
			SELECT 'Aménagement' as entite, 'pudi' as couche, pudi.gid, pudi.code as numcf, ' ' as numdemande, pudi.surface_ha as surface, pudi.observation, pudi.vu, ST_AsText(ST_Centroid(pudi.geom)) AS centroid,e.name as entity
			FROM pudi pudi
			JOIN user_lp u on pudi.id_utilisateur = u.id_user
			JOIN entity e on u.id_entite = e.id_entity
			WHERE a_rectifier = true
		");
	$rec -> execute();
	$demande = $rec->fetchAll(PDO::FETCH_ASSOC);
	echo json_encode($demande);
}

function recupRefuseeCommune ($db = null) {
	if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
        session_start();
    }

    if ($db === null) {
        global $db;
    }
		$rec = $db->prepare("SELECT 'Commune' as entite, 'certificats' as couche, c.gid, c.numcf, c.numdemande, c.surface, c.observation, c.vu, ST_AsText(ST_Centroid(c.geom)) AS centroid, e.name as entity
			FROM certificats c
			JOIN user_lp u on c.id_utilisateur = u.id_user
			JOIN entity e on u.id_entite = e.id_entity
			WHERE validee_publiee = false
		UNION ALL 
			SELECT 'Commune' as entite, 'permis' as couche, p.gid, p.numero as numcf, p.numero as numdemande, p.surface, p.observation, p.vu, ST_AsText(ST_Centroid(p.geom)) AS centroid,e.name as entity
			FROM permis p
			JOIN user_lp u on p.id_utilisateur = u.id_user
			JOIN entity e on u.id_entite = e.id_entity
			WHERE validee_publiee = false");
	$rec -> execute();
	$demande = $rec->fetchAll(PDO::FETCH_ASSOC);
	echo json_encode($demande);
}

function recupRefuseeSF ($db = null) {
	if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
        session_start();
    }

    if ($db === null) {
        global $db;
    }
		$rec = $db->prepare("
				SELECT 'Service Foncier' as entite, 'cadastre' as couche, ca.gid, ca.num_parcelle as numcf, ca.num_parcelle as numdemande, ca.surface, ca.observation, ca.vu, ST_AsText(ST_Centroid(ca.geom)) AS centroid,e.name as entity
				FROM cadastre ca
				JOIN user_lp u on ca.id_utilisateur = u.id_user
				JOIN entity e on u.id_entite = e.id_entity
				WHERE validee_publiee = false
			UNION ALL
					SELECT 'Service Foncier' as entite, 'dpe' as couche, dpe.gid, dpe.code as numcf, '' as numdemande, dpe.surface, dpe.observation, dpe.vu, ST_AsText(ST_Centroid(dpe.geom)) AS centroid,e.name as entity
				FROM dpe dpe
				JOIN user_lp u on dpe.id_utilisateur = u.id_user
				JOIN entity e on u.id_entite = e.id_entity
				WHERE validee_publiee = false
				");
	$rec -> execute();
	$demande = $rec->fetchAll(PDO::FETCH_ASSOC);
	echo json_encode($demande);
}

function recupRefuseeAmenagement ($db = null) {
	if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
        session_start();
    }

    if ($db === null) {
        global $db;
    }
		$rec = $db->prepare("
				SELECT 'Amenagement' as entite, 'pudi' as couche, pudi.gid, pudi.code as numcf, ' 'as numdemande, pudi.surface_ha as surface, pudi.observation, pudi.vu, ST_AsText(ST_Centroid(pudi.geom)) AS centroid,e.name as entity
				FROM pudi pudi
				JOIN user_lp u on pudi.id_utilisateur = u.id_user
				JOIN entity e on u.id_entite = e.id_entity
				WHERE validee_publiee = false
				");
	$rec -> execute();
	$demande = $rec->fetchAll(PDO::FETCH_ASSOC);
	echo json_encode($demande);
}

function recupAcceptee ($db = null) {
	if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
        session_start();
    }

    if ($db === null) {
        global $db;
    }
	$rec = $db->prepare("SELECT 'Commune' as entite, 'certificats' as couche, c.gid, c.numcf, c.numdemande, c.surface, c.observation, c.vu, ST_AsText(ST_Centroid(c.geom)) AS centroid, e.name as entity
			FROM certificats c
			JOIN user_lp u on c.id_utilisateur = u.id_user
			JOIN entity e on u.id_entite = e.id_entity
			WHERE validee_publiee = true
		UNION ALL
			SELECT 'Commune' as entite, 'permis' as couche, p.gid, p.numero as numcf, p.numero as numdemande, p.surface, p.observation, p.vu, ST_AsText(ST_Centroid(p.geom)) AS centroid,e.name as entity
			FROM permis p
			JOIN user_lp u on p.id_utilisateur = u.id_user
			JOIN entity e on u.id_entite = e.id_entity
			WHERE validee_publiee = true
		UNION ALL
		SELECT 'Service Foncier' as entite, 'dpe' as couche, p.gid, p.code as numcf, ' ' as numdemande, p.surface, p.observation, p.vu, ST_AsText(ST_Centroid(p.geom)) AS centroid,e.name as entity
			FROM dpe p
			JOIN user_lp u on p.id_utilisateur = u.id_user
			JOIN entity e on u.id_entite = e.id_entity
			WHERE validee_publiee = true
		-- 	SELECT 'Service Foncier' as entite, 'cadastre' as couche, ca.gid, ca.numero as numcf, ca.numero as numdemande, ca.surface, ca.observation, ca.vu, ST_AsText(ST_Centroid(ca.geom)) AS centroid,e.name as entity
		-- 	FROM cadastre ca
		-- 	JOIN user_lp u on ca.id_user = u.id_user
		-- 	JOIN entity e on u.id_entity = e.id_entity
		-- 	WHERE validee_publiee = true
		UNION ALL
		SELECT 'Amenagement' as entite, 'pudi' as couche, pudi.gid, pudi.code as numcf, ' 'as numdemande, pudi.surface_ha as surface, pudi.observation, pudi.vu, ST_AsText(ST_Centroid(pudi.geom)) AS centroid,e.name as entity
				FROM pudi pudi
				JOIN user_lp u on pudi.id_utilisateur = u.id_user
				JOIN entity e on u.id_entite = e.id_entity
				WHERE validee_publiee = true
		");
	$rec -> execute();
	$demande = $rec->fetchAll(PDO::FETCH_ASSOC);
	echo json_encode($demande);
}

function recupeAccepteeSF ($db = null) {
	if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
        session_start();
    }

    if ($db === null) {
        global $db;
    }
	$rec = $db->prepare("
			SELECT 'Service Foncier' as entite, 'dpe' as couche, p.gid, p.code as numcf, ' ' as numdemande, p.surface, p.observation, p.vu, ST_AsText(ST_Centroid(p.geom)) AS centroid,e.name as entity
			FROM dpe p
			JOIN user_lp u on p.id_utilisateur = u.id_user
			JOIN entity e on u.id_entite = e.id_entity
			WHERE validee_publiee = true
		-- UNION ALL
		-- 	SELECT 'Service Foncier' as entite, 'cadastre' as couche, ca.gid, ca.numero as numcf, ca.numero as numdemande, ca.surface, ca.observation, ca.vu, ST_AsText(ST_Centroid(ca.geom)) AS centroid,e.name as entity
		-- 	FROM cadastre ca
		-- 	JOIN user_lp u on ca.id_user = u.id_user
		-- 	JOIN entity e on u.id_entity = e.id_entity
		-- 	WHERE validee_publiee = true

		");
	$rec -> execute();
	$demande = $rec->fetchAll(PDO::FETCH_ASSOC);
	echo json_encode($demande);
}

?>