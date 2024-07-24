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
</head>
<body>
	<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <!-- Container wrapper -->
  <div class="container-fluid">
    <!-- Navbar brand -->
    <a class="navbar-brand" href="#">Navbar</a>

    <!-- Toggle button -->
    <button class="navbar-toggler" type="button" data-mdb-toggle="collapse"
      data-mdb-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
      aria-label="Toggle navigation">
      <i class="fas fa-bars text-light"></i>
    </button>

    <!-- Collapsible wrapper -->
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <!-- Left links -->
      <ul class="navbar-nav me-auto d-flex flex-row mt-3 mt-lg-0">
        <li class="nav-item text-center mx-2 mx-lg-1">
          <a class="nav-link active" aria-current="page" href="#!">
            <div>
              <i class="fas fa-home fa-lg mb-1"></i>
            </div>
            Home
          </a>
        </li>
        <li class="nav-item text-center mx-2 mx-lg-1">
          <a class="nav-link" href="#!">
            <div>
              <i class="far fa-envelope fa-lg mb-1"></i>
              <span class="badge rounded-pill badge-notification bg-danger">11</span>
            </div>
            Link
          </a>
        </li>
        <li class="nav-item text-center mx-2 mx-lg-1">
          <a class="nav-link disabled" aria-disabled="true" href="#!">
            <div>
              <i class="far fa-envelope fa-lg mb-1"></i>
              <span class="badge rounded-pill badge-notification bg-warning">11</span>
            </div>
            Disabled
          </a>
        </li>
        <li class="nav-item dropdown text-center mx-2 mx-lg-1">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-mdb-toggle="dropdown"
            aria-expanded="false">
            <div>
              <i class="far fa-envelope fa-lg mb-1"></i>
              <span class="badge rounded-pill badge-notification bg-primary">11</span>
            </div>
            Dropdown
          </a>
          <!-- Dropdown menu -->
          <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="#">Action</a></li>
            <li><a class="dropdown-item" href="#">Another action</a></li>
            <li>
              <hr class="dropdown-divider" />
            </li>
            <li>
              <a class="dropdown-item" href="#">Something else here</a>
            </li>
          </ul>
        </li>
      </ul>
      <!-- Left links -->

      <!-- Right links -->
      <ul class="navbar-nav ms-auto d-flex flex-row mt-3 mt-lg-0">
        <li class="nav-item text-center mx-2 mx-lg-1">
          <a class="nav-link" href="#!">
            <div>
              <i class="fas fa-bell fa-lg mb-1"></i>
              <span class="badge rounded-pill badge-notification bg-info">11</span>
            </div>
            Messages
          </a>
        </li>
        <li class="nav-item text-center mx-2 mx-lg-1">
          <a class="nav-link" href="#!">
            <div>
              <i class="fas fa-globe-americas fa-lg mb-1"></i>
              <span class="badge rounded-pill badge-notification bg-success">11</span>
            </div>
            News
          </a>
        </li>
      </ul>
      <!-- Right links -->

      <!-- Search form -->
      <form class="d-flex input-group w-auto ms-lg-3 my-3 my-lg-0">
        <input type="search" class="form-control" placeholder="Search" aria-label="Search" />
        <button class="btn btn-primary" type="button" data-mdb-ripple-color="dark">
          Search
        </button>
      </form>
      <button type="button" title="Déconnecter la page" class="btn btn-danger navbar-btn" onclick="deconnecter()"> <i style="color: ;" class="nav-icon fas fa-sign-out-alt" ></i></button>
    </div>
    <!-- Collapsible wrapper -->
  </div>
  <!-- Container wrapper -->
</nav>
<!-- Navbar -->
	    

	<section>
		<div class="containner">
			<!-- carte -->
			<div id="map" class="map"></div>
			<!-- ./carte -->

			<!-- outils de la carte -->
			<div class="ol-outils">
		  	</div>
			<!-- ./outils de la carte -->
		</div>
	</section>
	
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