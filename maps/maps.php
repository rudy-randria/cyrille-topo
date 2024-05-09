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

    nav {
      margin: 0;
      margin-bottom: 0;
    }            
  </style>
</head>
<body>
  <div class="wrapper">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
          <a class="navbar-brand" href="#">Commune</a>
          <button type="button" title="Déconnecter la page" class="btn btn-danger navbar-btn" onclick="deconnecter()"> <i style="color: ;" class="nav-icon fas fa-sign-out-alt" ></i></button>
        </div>
      </div>
    </nav>     
    <!-- Navbar -->
    <div class="content-wrapper">
      <section>
      <!-- carte -->
      <div id="map" class="map"></div>
      <!-- ./carte -->

      <!-- outils de la carte -->
      <div class="ol-outils">
        <div><button id="areaButton" class="areaButton" title="Mésurer une surface"><img src="../assets/images/carte/mesure-surface.svg" width="20px" height="20px"></button></div>
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
</script>
</body>
</html>