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

    #formCheckingDiv {
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
        <a class="navbar-brand" href="javascript:void(0)" id="entite"><?=$_SESSION['entite']?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mynavbar">
          <ul class="navbar-nav me-auto">
            <!-- Notifications Dropdown Menu -->
            <!-- notification des demandes en attente -->
             <li class="nav-item dropdown">
              <a class="nav-link" data-toggle="dropdown" href="#">
                Nouvelle demande
                <span class="badge badge-warning  navbar-badge" style="background-color: yellow; color: black;" id="Attentenombre">0</span>        
              </a>
              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" id="demandeAttentNot" style="">
                <span class="dropdown-item dropdown-header" id="DemandeAttenteTitleNot">0 demande en attente</span>   
              </div>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link" data-toggle="dropdown" href="#">
                Demande à rectifier
                <span class="badge badge-warning  navbar-badge" style="background-color: blue; color: black;" id="rectifierNombre">0</span>
              </a>
              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" id="demandeArecitfierNot">
                <span class="dropdown-item dropdown-header" id="DemandeVlideeTitleNot">0 demande à rectifier</span>     
              </div>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link" data-toggle="dropdown" href="#">
                Mise à jour
                <span class="badge badge-warning  navbar-badge" style="background-color: green; color: black;" id="Attentenombre">0</span>        
              </a>
              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header" id="DemandeTitleNot">0 Mise à jour</span>
              </div>
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
        <!-- formlaire de verification -->
          <!-- Draggable DIV -->
          <div id="formCheckingDiv">
            <!-- Include a header DIV with the same name as the draggable DIV, followed by "header" -->
            <div id="formDivHeader" class="div-header d-flex justify-content-between align-items-center">
              Verification
              <div>
                <button type="button" class="btn" onclick="collapseFormDemande('formDemande')"><i id="collapse" class="fas fa-minus"></i></button>
                <button type="button" class="btn" onclick="fermerFormDemande()" id="closeForm"><i class="fas fa-times"></i></button>
              </div>
            </div>
            <form class="form-group" id="formDemande" method="POST" action="functions.php">
              <div class="form-group">
                <label for="numcf" id="numerolabel"> Propriétés </label>
                <div id="properties"></div>
              </div>
              <div class="form-group">
                <label for="observation">Observation :</label>
                <textarea type="text" class="form-control" name="remarque" id="remarque" value='' required></textarea>
              </div>
              <input type="hidden" name="type" id="coucheChamp">
              <input type="hidden" name="gid" id="gidChamp"> 
              <div class="form-group">
                <label for="ref">Résultat :</label>
                <select name="resultat" class="form-control" id="ref" required >
                  <option value="">Choisir ici</option>
                  <option value="rectifier">A rectifier</option>
                  <option value="refuser">Refuser</option>
                  <option value="accepter">Accepter</option>
                </select>
              </div>        
              <button type="submit" name="CheckRun" class="btn btn-success">Valider</button>
              <button type="button" id="cancelForm-btn" onclick="fermerFormDemande()" class="btn btn-danger" >Annuler</button>
            </form>
          </div>
          <!-- ./formulaire de demande -->
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
<script type="module" src="../assets/js/comite.js"></script>
<script type="text/javascript">
	function deconnecter() {
	    var confirmer = confirm("Voulez-vous vraiment quitter la plateforme ?");
	    if (confirmer == true ) {
      	window.location.href="../controllers/DeconectController.php";
    } else {} }

  // afficher le formulaire de demande
  function afficherFormDemande(argument) {
    document.getElementById("formCheckingDiv").style.display="block";
  } 
  function fermerFormDemande(argument) {
    document.getElementById("formCheckingDiv").style.display="none";    
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

  function setLabelNum() {
    var select = document.getElementById("ref");
    var selectedValue = select.options[select.selectedIndex].value;
    document.getElementById("numerolabel").innerHTML = "Numero " + selectedValue.toUpperCase() + " : ";
}


</script>
</body>
</html>