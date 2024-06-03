<?php 
  session_start();

  if (!$_SESSION['mdp']) {
    session_destroy();
    header('location:../index.php');
  }

  // Inclure les ressources communes
  include_once('../includes/resources.php');

  // Controle des couches accessible par rapport à l'entite connecté
  $select = "";
  $entite = $_SESSION['id_entity'] ?? '';
  if ($entite === 1) { //superAdmin : N'a pas de formulaire demande
    $select = "<option value=''>Choisir ici</option>";
  } elseif ($entite === 2) { // autres entite
    $select = "<option value=''>Choisir ici</option>
               <option value=''></option>";
  } elseif ($entite === 3) { // services fonciers : dpe, ppnt, titre, plof 
    $select = "<option value=''>Choisir ici</option>
               <option value='dpe'>Domaine privé de l'Etat</option>
               <option value='ppnt'>Propriété Privé Non Titrée</option>
               <option value='titre'>Titre</option>
               <option value='cadastre'>Plan cadastral</option>
               <option value='plof'>Plan Local d'Occupation Foncière</option>";
  } elseif ($entite === 4) { // communes : 
    $select = "<option value=''>Choisir ici</option>
               <option value='certificats'>Certificat foncier</option>
               <option value='permis'>Permis de construire</option>";
  } elseif ($entite === 5) { // amenagement SRAT : pude
    $select = "<option value=''>Choisir ici</option>
               <option value='pude'>Plan d'urbanisme détaillé</option>";
  } elseif ($entite === 6) { // comité de validation : N'a pas de formulaire demande
    $select = "<option value=''>Choisir ici</option>
               <option value='cf'>Certificat foncier</option>
               <option value='pc'>Permis de construire</option>";
  } else {
    echo "Erreur";
  }

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

    #formDivDemande, #formDivRect, #formDivRefus {
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

    #formDemande, #formrectification {
      padding: 5px;
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

    #startEditing, #stopEditing {
      padding: 2px;
      border: 1px solid black;
      border-radius: 3px;
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
            <!-- Notifications Dropdown Menu -->
            <!-- notification des demandes en attente -->
            <li class="nav-item dropdown">
              <a class="nav-link" data-toggle="dropdown" href="#">
                Demande en attente
                <span class="badge badge-warning  navbar-badge" style="background-color: yellow; color: black;" id="Attentenombre">0</span>        
              </a>
              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" id="demandeAttentNot">
                <span class="dropdown-item dropdown-header" id="DemandeAttenteTitleNot">0 demande en attente</span>   
              </div>
            </li>
            
            <!-- notifications des demandes validées -->
            <li class="nav-item dropdown">
              <a class="nav-link" data-toggle="dropdown" href="#">
                Demande validée
                <span class="badge badge-warning  navbar-badge" style="background-color: green; color: black;" id="Valideenombre">0</span>
              </a>
              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" id="demandeValideeNot">
                <span class="dropdown-item dropdown-header">0 demande validée</span>     
              </div>
            </li>

            <!-- notifications des demandes à rectifier -->
            <li class="nav-item dropdown">
              <a class="nav-link" data-toggle="dropdown" href="#">
                Demande à rectifier
                <span class="badge badge-warning  navbar-badge" style="background-color: blue; color: black;" id="rectifierNombre">0</span>
              </a>
              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" id="demandeArecitfierNot">
                <span class="dropdown-item dropdown-header" id="DemandeVlideeTitleNot">0 demande à rectifier</span>     
              </div>
            </li>

            <!-- notifications des demandes refusées -->
            <li class="nav-item dropdown">
              <a class="nav-link" data-toggle="dropdown" href="#">
                Demande refusée
                <span class="badge badge-warning  navbar-badge" style="background-color: red; color: black;" id="refuseeNombre">0</span>
              </a>
              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" id="demandeRefuseeNot">
                <span class="dropdown-item dropdown-header" id="DemandeVlideeTitleNot">0 demande refusée</span>     
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
    
    <!-- ./Navbar -->
    <div class="content-wrapper">
      <section>
        <!-- formlaire de demande -->
          <div id="formDivDemande">
            <!-- Include a header DIV with the same name as the draggable DIV, followed by "header" -->
            <div id="formDivHeader" class="div-header d-flex justify-content-between align-items-center">
              <h3 id="formTitle">Demande</h3>
              
              <div>
                <button type="button" class="btn" onclick="collapseFormDemande('formDemande')"><i id="collapse" class="fas fa-minus"></i></button>
                <button type="button" class="btn" onclick="fermerFormDemande('formDivDemande')" id="closeForm"><i class="fas fa-times"></i></button>
              </div>
            </div>
            <form class="form-group" id="formDemande" method="POST" action="../controllers/DemandeController.php">
              <div class="form-group">
                <label for="ref">Type :</label>
                <select name="couche" class="form-control" id="ref" required onchange="setLabelNum()">
                  <?= $select; ?>
                </select>
              </div>
              <div class="form-group">
                <label for="numcf" id="numerolabel"> Numero : </label>
                <input type="text" class="form-control" name="numcf" id="numcf" required>
              </div>
              <div class="form-group">
                <label for="numdemande">Numero demande :</label>
                <input type="text" name="numdemande" class="form-control" id="numdemande" required>
              </div>
              <div class="form-group">
                <label for="observation">Observation :</label>
                <input type="text" class="form-control" name="observation" id="observation" required>
              </div>
              
               <!-- Mettre dans le formulaire le valeur de geométrie mais masquée-->
              <div class="form-group">
                <input type="hidden" class="form-control" id="geom" name="geom" required readonly>
              </div>
              
              <div class="form-group">
                <label for="surface">Surface (m²) :</label>
                <input type="decimal" class="form-control" name="surface" id="surface" required>
              </div>
              
              <button type="submit" name="demandeRun" class="btn btn-success">Envoyer demande</button>
              <button type="button" id="cancelForm-btn" onclick="fermerFormDemande('formDivDemande')" class="btn btn-danger" >Annuler</button>

              <?php 
                  if (isset($_SESSION['message'])) {
                      echo "<script> alert('".$_SESSION['message']."')</script>";
                      // unset the message session variable so it doesn't persist on refresh
                      unset($_SESSION['message']);
                  }
              ?>

            </form>
          </div>
          <!-- ./formulaire de demande -->

          <!-- formlaire de rectification -->
          <div id="formDivRect">
            <div id="formDivHeader" class="div-header d-flex justify-content-between align-items-center">
              <h3 id="formTitle">Rectification</h3>
              <div>
                <button type="button" class="btn" onclick="collapseFormDemande('formrectification')"><i id="collapse" class="fas fa-minus"></i></button>
                <button type="button" class="btn" onclick="fermerFormDemande('formDivRect')" id="closeForm"><i class="fas fa-times"></i></button>
              </div>
            </div>
            <form class="form-group" id="formrectification" method="POST" action="functions.php">
              <div class="form-group">
                <button type="button" id="startEditing">Start Editing</button>
                <button type="button" id="stopEditing">Stop Editing</button>
              </div>
              <div class="form-group">
                <label for="numcf" id="numerolabel"> Remarque du comité : </label>
                <textarea class="form-control" id="remarque" readonly></textarea>
              </div>              
              <div class="form-group">
                <label for="numcf" id="numerolabel"> Numero : </label>
                <input type="text" class="form-control" name="numcf" id="numcf2" required>
              </div>
              <div class="form-group">
                <label for="numdemande">Numero demande :</label>
                <input type="text" name="numdemande" class="form-control" id="numdemande2" required>
              </div>
              <div class="form-group">
                <label for="observation">Observation :</label>
                <input type="text" class="form-control" name="observation" id="observation2" required>
              </div>
              
              <!-- Mettre dans le formulaire les valeurs de id, nom couche et leur geométrie mais masquées -->
              <div class="form-group">
                <input type="hidden" class="form-control" id="geom2" name="geom" required readonly>
                <input type="hidden" name="gid" id="gid2">
                <input type="hidden" name="couche" id="couche2">
              </div>
              
              <div class="form-group">
                <label for="surface">Surface (m²) :</label>
                <input type="decimal" class="form-control" name="surface" id="surface2" required>
              </div>
              <button type="submit" name="RunUpdate" class="btn btn-success">Mettre à jour</button>
              <button type="button" id="cancelForm-btn" onclick="fermerFormDemande('formDivRect')" class="btn btn-danger" >Annuler</button>
            </form>
          </div>
          <!-- ./formulaire de rectification -->

          <!-- formlaire de refus -->
          <div id="formDivRefus">
            <div id="formDivHeader" class="div-header d-flex justify-content-between align-items-center">
              <h4 id="formTitle">Demande refusée</h4>
              <div>
                <button type="button" class="btn" onclick="collapseFormDemande('formrefus')"><i id="collapse" class="fas fa-minus"></i></button>
                <button type="button" class="btn" onclick="fermerFormDemande('formDivRefus')" id="closeForm"><i class="fas fa-times"></i></button>
              </div>
            </div>
            <form class="form-group" id="formrefus" method="POST" action="functions.php">
              <div class="form-group">
                <label for="numcf" id="numerolabel"> Remarque du comité : </label>
                <textarea class="form-control" id="remarque3" readonly></textarea>
              </div> 
              <input type="hidden" name="gid" id="gid3">
              <input type="hidden" name="couche" id="couche3"> 
              <button type="submit" name="confirmRefus" class="btn btn-success">OK</button>
            </form>
          </div>
          <!-- ./formulaire de refus -->
         
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

<?php //Controler ici l'affichage de carte selon l'entite connectée

if ($_SESSION['id_entity'] == 2) {
  echo '<script type="module" src="../assets/js/autres.js"></script>';
} elseif ($_SESSION['id_entity'] == 3) {
  echo '<script type="module" src="../assets/js/servicefoncier.js"></script>';
} elseif ($_SESSION['id_entity'] == 4) {
  echo '<script type="module" src="../assets/js/commune.js"></script>';
} elseif ($_SESSION['id_entity'] == 5) {
  echo '<script type="module" src="../assets/js/ammenagement.js"></script>';
} else {
  echo "Erreur";
}

 ?>
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
  function fermerFormDemande(form) {
    document.getElementById(form).style.display="none";    
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