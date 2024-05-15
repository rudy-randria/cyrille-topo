<?php 
	session_start();

	if (!$_SESSION['mdp']) {
		session_destroy();
		header('location:../index.php');
	}

	// Inclure les ressources communes
	include_once('../includes/resources.php');
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	<title>Cartographie </title>
	<link rel="stylesheet" href="../vendor/ol/ol.css"></link>
	<link rel="stylesheet" type="text/css" href="../assets/css/carte.css">
  <style type="text/css">
    .navbar-light .navbar-nav .nav-link {
      color: #000;
    } 

    #formDivDemande {
      display: none;
      position: absolute;
      z-index: 99999;
      background-color: #f1f1f1;
      border: 1px solid #d3d3d3;
      text-align: center;
      top: ;
      right: 2px;
      width: 250px;
    }

    #formDemande {
      padding: 5PX;
    }

    #formDemande input, select {
      text-align: center;
    }

    #formDivHeader {
      padding: 10px;
      z-index: 10;
      background-color: #2196F3;
      color: #fff;
    }           
  </style>
</head>
<body>
  <div class="wrapper">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-sm navbar-dark bg-dark" style="margin: 0;">
      <div class="container-fluid">
        <a class="navbar-brand" href="javascript:void(0)"><?=$_SESSION['entite']?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mynavbar">
          <ul class="navbar-nav me-auto">
            <li class="nav-item" id="draw-button">
              <span class="nav-link" style="margin: 0; padding: 15px 2px 15px 2px; cursor: pointer;">Dessiner la zone <br><small>(zone à demander)</small></span>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Demande en attente <span class="float-right text-sm text-warning">(0)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Demande validée <span class="float-right text-sm text-success">(0)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Demande à rectifiée <span class="float-right text-sm text-danger">(0)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Autres</a>
            </li>
            <li>
              <button type="button" title="Déconnecter la page" class="btn btn-danger navbar-btn" onclick="deconnecter()"> <i style="color: ;" class="nav-icon fas fa-sign-out-alt" ></i></button>
            </li>
          </ul>
          <!-- <form class="d-flex">
            <input class="form-control me-2" type="text" placeholder="Search">
            <button class="btn btn-primary" type="button">Search</button>
          </form> -->
            
        </div>
      </div>
    </nav>
    
    <!-- Navbar -->
    <div class="content-wrapper">
      <section>
        <!-- formlaire de demande -->
          <!-- Draggable DIV -->
          <div id="formDivDemande">
            <!-- Include a header DIV with the same name as the draggable DIV, followed by "header" -->
            <div id="formDivHeader" class="div-header d-flex justify-content-between align-items-center">
              Demande
              <div>
                <button type="button" class="btn" onclick="collapseFormDemande('formDemande')"><i id="collapse" class="fas fa-minus"></i></button>
                <button type="button" class="btn" onclick="fermerFormDemande()" id="closeForm"><i class="fas fa-times"></i></button>
              </div>
            </div>
            <form class="form-group" id="formDemande" method="POST" action="functions.php">
              <div class="form-group">
                <label for="ref">Type :</label>
                <select name="type" class="form-control" id="ref" required>
                  <option value="">Choisir ici</option>
                  <option value="cf">Certificat foncier</option>
                  <option value="pc">Permis de construire</option>
                </select>
              </div>
              <div class="form-group">
                <label for="numcf">Numéro (CF/PERMIS)  :</label>
                <input type="text" class="form-control" name="numcf" id="numcf" required>
              </div>
              <div class="form-group">
                <label for="numdemande">Numéro demande :</label>
                <input type="text" name="numdemande" class="form-control" id="numdemande" required>
              </div>
              <div class="form-group">
                <label for="observation">Observation :</label>
                <input type="text" class="form-control" name="observation" id="observation" required>
              </div>
              <!--
              On n'a pas besoin de ce champ si on commence par tracer le polygone pour faire une demande
              <div class="form-group">
                <label for="geom">Géométrie :</label>
                <button type="button" id="areaButton" class="btn btn-default">Tracer</button>
                <input type="text" class="form-control" id="geom" name="geom" required readonly>
              </div>
              -->
              <div class="form-group">
                <label for="surface">Surface (m²) :</label>
                <input type="decimal" class="form-control" name="surface" id="surface" required>
              </div>
              
              <button type="submit" name="demandeRun" class="btn btn-success">Envoyer demande</button>
              <button type="button" id="cancelForm-btn" onclick="fermerFormDemande()" class="btn btn-danger" >Annuler</button>
            </form>
          </div>
          <!-- ./formulaire de demande -->
          <div id="confirmDiv" style="position: absolute; display: none;">
            <button type="button" id="validateButton" class="btn btn-success" style="display: none;">Confirmer</button>
            <button type="button" id="redrawButton" class="btn btn-danger" style="display: none;">Annuler</button>
          </div>
          <!-- carte -->
          <div id="map" class="map"></div>
          <!-- ./carte -->

          <!-- outils de la carte -->
          <div class="ol-outils">
        <!-- <div><button id="areaButton" class="areaButton" title="Mésurer une surface"><img src="../assets/images/carte/mesure-surface.svg" width="20px" height="20px"></button></div> -->
      </div>
      <!-- ./outils de la carte -->
    </section>
    </div>
  </div>
<script src="../vendor/proj4/proj4.js"></script>
<script src="../vendor/ol/dist/ol.js"></script>
<script type="module" src="../assets/js/carte.js"></script>
<script type="text/javascript">
	function deconnecter() {
	    var confirmer = confirm("Voulez-vous vraiment quitter la plateforme ?");
	    if (confirmer == true ) {
      	window.location.href="../controllers/DeconectController.php";
    } else {} }

  // afficher le formulaire de demande
  function afficherFormDemande(argument) {
    document.getElementById("formDivDemande").style.display="block";
  } 
  function fermerFormDemande(argument) {
    document.getElementById("formDivDemande").style.display="none";    
  }
  function collapseFormDemande(form) {
    var collapse = document.getElementById("collapse");
    if (collapse.className == "fas fa-minus") {
      document.getElementById(form).style.display="none";
      collapse.setAttribute('class', 'fas fa-plus');
    } else {
      document.getElementById(form).style.display="block";
      collapse.setAttribute('class', 'fas fa-minus');
    }
  }
</script>
</body>
</html>