// assets/js/carte.js
// gestion des fonctionnalités de la carte 
import { geoserverURL } from './config.js';

var urlParams = new URLSearchParams(window.location.search);
var lati = urlParams.get('lat');
var long = urlParams.get('lon');
var idmissionurl = urlParams.get('id');
// Vérifiez si les paramètres d'URL existent
// var center = (lat && lon) ? [parseFloat(lon), parseFloat(lat)] : [48, -19];

// Transformation de coordonnées avec proj4 en utilisant les paramètres epsg.io
import {register} from '../../vendor/ol/proj/proj4.js';

proj4.defs("EPSG:29702","+proj=omerc +lat_0=-18.9 +lonc=44.1 +alpha=18.9 +gamma=18.9 +k=0.9995 +x_0=400000 +y_0=800000 +ellps=intl +pm=paris +towgs84=-198.383,-240.517,-107.909,0,0,0,0 +units=m +no_defs +type=crs");

// Enregistrer Proj4js avec OpenLayers
register(proj4);

const projection = new ol.proj.Projection({
  code: 'EPSG:29702',
  extent: [198489.1544496529968455, 247569.8171144139487296, 890053.2951834150007926, 1038222.6880689850077033] 
});

// initialisation de la carte
var view =  new ol.View({
  projection: projection,
  center: [474571, 686922], // Centre de la carte (longitude, latitude)
  zoom: 4.5,// Niveau de zoom de la carte
  // extent: [338489.1544496529968455, 547569.8171144139487296, 610053.2951834150007926, 838222.6880689850077033]
});

var map = new ol.Map({
  target: 'map',
  layers: [],
  view: view
});

// centrer la carte sur la géométrie d'un élément cliqué
function centerMapTo (lat, lon) {
  var newview = map.getView();
  if (lat && lon) {
    newview.animate({
      center: [parseFloat(lon), parseFloat(lat)], // Nouveau centre de la carte
      duration: 6000, // Durée de l'animation en millisecondes
      zoom: 10,
    });
  }
  // ajouter un marqueur
var point = (lat && lon) ? [parseFloat(lon), parseFloat(lat)] : [];

// Ajoutez un marqueur sur le point
var marker = new ol.Feature(new ol.geom.Point(ol.proj.fromLonLat(point, projection)));
var markvectorSource = new ol.source.Vector({
    features: [marker]
});

var markerStyle = new ol.style.Style({
    image: new ol.style.Icon({
      anchor: [0.5, 0.9],
      scale: 0.3,
      // size: [1, 1],
      src: '../assets/images/carte/map.ico',
    }),
  });

var markerVectorLayer = new ol.layer.Vector({
    source: markvectorSource,
    // style: flashStyle
    style: markerStyle
});

map.addLayer(markerVectorLayer);
markerVectorLayer.setZIndex(999);
}


// ----- position de souris---------
var mousePositionControl = new ol.control.MousePosition({
  coordinateFormat: ol.coordinate.createStringXY(5),
  projection: projection,
  className: 'ol-mouse-position',
});
map.addControl(mousePositionControl);

// dragg pan
var pan = new ol.interaction.DragPan();
map.addInteraction(pan);

// rotation 
var rotation = new ol.interaction.DragRotateAndZoom ();
map.addInteraction(rotation);

// echelle graphique
var scaleControl = new ol.control.ScaleLine({
  units: 'metric',
  bar: true,
  steps: 4,
  text: true,
  minWidth: 140,
});
map.addControl(scaleControl);

// ---------Mode plein écran--------
var FullscreenControl = new ol.control.FullScreen();
map.addControl(FullscreenControl);

// #####fond de carte
// couche limite administratice de commune Urbaine Antsirabe
var url = geoserverURL + "/geoserver/M.Cyrille/wms"
var limCommuneSource = new ol.source.TileWMS({
  url: url,
  params: {
    'FORMAT': "image/png",
    'VERSION': '1.1.1',
    'LAYERS' : 'M.Cyrille:limite_commune',
    'TILED' : true,
    "exceptions": 'application/vnd.ogc.se_inimage',
    tilesOrigin: 438489.154449653 + "," + 647569.817114414
  },
  projection: projection,
  serverType: 'geoserver',
  attributions: 'Limite_Adm_Commune'
});
var limiteCommuneLayer = new ol.layer.Tile({
  source: limCommuneSource,
  title: 'Limite_Adm_Commune',
  visible: true
 }); 
// map.addLayer(limiteCommuneLayer);

// couche PUDI de commune Urbaine Antsirabe
var pudisource = new ol.source.TileWMS({
  url: url,
  params: {
    'LAYERS': 'M.Cyrille:pudi',
    'VERSION': '1.1.1',
    'TILED' : true,
    'STYLES': 'M.Cyrille:pudi',
    'FORMAT': 'image/png',
    // 'FORMAT_OPTIONS': "layout:style-editor-legend;fontAntiAliasing:true",
    'RANDOM': -553447854,
    'LEGEND_OPTIONS': 'forceLabels:on;fontAntiAliasing:true',
    'EXCEPTIONS': 'application/vnd.ogc.se_inimage'
  },
  projection: projection,
  serverType: 'geoserver',
  ratio: 1
});
var pudilayer = new ol.layer.Tile({
  source: pudisource,
  title: '',
  visible: true
});
map.addLayer(pudilayer);

var plofsource = new ol.source.TileWMS({
  url: url,
  params: {
    'LAYERS': 'M.Cyrille:vw_plof',
    'STYLES': 'M.Cyrille:vw_plof',
    'VERSION': '1.1.1',
    'FORMAT': 'image/png',
    // 'FORMAT_OPTIONS': "layout:style-editor-legend;fontAntiAliasing:true",
    'RANDOM': 48810829,
    'LEGEND_OPTIONS': 'forceLabels:on;fontAntiAliasing:true',
    'EXCEPTIONS': 'application/vnd.ogc.se_inimage'
  },
  projection: projection,
  serverType: 'geoserver',
  ratio: 1
});

var ploflayer = new ol.layer.Tile({
  source: plofsource,
  visible: true
});
map.addLayer(ploflayer);

// couche Hydrographie linéaire
var HydroligneSource = new ol.source.TileWMS({
  url: url,
  params: {
    'FORMAT': "image/png",
    'VERSION': '1.1.1',
    'LAYERS' : 'M.Cyrille:hydrographie_lineaire',
    'TILED' : true,
    "exceptions": 'application/vnd.ogc.se_inimage',
      tilesOrigin: 438132.15625 + "," + 647194.5
  }
});
var Hydrolignelayer = new ol.layer.Tile({
  source: HydroligneSource,
  visible: true
}); 
  map.addLayer(Hydrolignelayer);

// couche Hydrographie linéaire
var HydrozoneSource = new ol.source.TileWMS({
  url: url,
  params: {
    'FORMAT': "image/png",
    'VERSION': '1.1.1',
    'LAYERS' : 'M.Cyrille:hydrographie_zonale',
    'TILED' : true,
    "exceptions": 'application/vnd.ogc.se_inimage',
      tilesOrigin:438135.84375 + "," + 647143.125
  }
});
var Hydrozonelayer = new ol.layer.Tile({
  source: HydroligneSource,
  visible: true
}); map.addLayer(Hydrozonelayer);

// couche certificats

var certificatSource = new ol.source.TileWMS({
  url: url,
  params: {
    'LAYERS': 'M.Cyrille:certificats',
    // 'STYLES': 'M.Cyrille:vw_plof',
    'VERSION': '1.1.1',
    'FORMAT': 'image/png',
    // 'FORMAT_OPTIONS': "layout:style-editor-legend;fontAntiAliasing:true",
    'RANDOM': 48810829,
    'LEGEND_OPTIONS': 'forceLabels:on;fontAntiAliasing:true',
    'EXCEPTIONS': 'application/vnd.ogc.se_inimage',
    'CQL_FILTER': 'validee_publiee=true'
  },
  projection: projection,
  serverType: 'geoserver',
  ratio: 1
});

var certificatLayer = new ol.layer.Tile({
  source: certificatSource,
  visible: true
});
map.addLayer(certificatLayer);

var dpesource = new ol.source.TileWMS({
  url: url,
  params: {
    'LAYERS': 'M.Cyrille:dpe',
    // 'STYLES': 'M.Cyrille:vw_plof',
    'VERSION': '1.1.1',
    'FORMAT': 'image/png',
    // 'FORMAT_OPTIONS': "layout:style-editor-legend;fontAntiAliasing:true",
    'RANDOM': 48810829,
    'LEGEND_OPTIONS': 'forceLabels:on;fontAntiAliasing:true',
    'EXCEPTIONS': 'application/vnd.ogc.se_inimage',
    'CQL_FILTER': 'validee_publiee=\'true\'',
  },
  projection: projection,
  serverType: 'geoserver',
  ratio: 1
});

var dpelayer = new ol.layer.Tile({
  source: dpesource,
  visible: true
});
map.addLayer(dpelayer);

// Afficher en jaune la nouvelle demande
  //Couche certificat foncier de l'entité `commune`
var url = geoserverURL+ "/geoserver/M.Cyrille/ows?service=WFS&version=1.0.0&request=GetFeature&typeName=%20certificats&CQL_FILTER=updated_or_new+=+%27true%27&outputFormat=application/json"; 

var AttentestyleFunction = function(feature) {
  return new ol.style.Style({
    fill: new ol.style.Fill({
      color: 'yellow'
    }),
    stroke: new ol.style.Stroke({
      color: 'yellow',
      width: 3
    }),
    image: new ol.style.Circle({
      radius: 7,
      fill: new ol.style.Fill({
        color: 'yellow'
      })
    }),
    text: new ol.style.Text({
      text: feature.get('numdemande'),
      fill: new ol.style.Fill({
        color: '#000'
      }),
      stroke: new ol.style.Stroke({
        color: '#fff',
        width: 3
      })
    })
  });
};

var CertificatsAttentegeojson = new ol.layer.Vector({
  source: new ol.source.Vector({
    url: url,
    format: new ol.format.GeoJSON()
  }),
  style: AttentestyleFunction // Utilisez la fonction de style ici
});

  map.addLayer(CertificatsAttentegeojson);

// Afficher la couche en attente en jaune
var urldemandeDPE = geoserverURL+ "/geoserver/M.Cyrille/ows?service=WFS&version=1.0.0&request=GetFeature&typeName=%20dpe&CQL_FILTER=updated_or_new+=+%27true%27&outputFormat=application/json"; 
// var urldemandePermis = geoserverURL+ "/geoserver/M.Cyrille/ows?service=WFS&version=1.0.0&request=GetFeature&typeName=%20permis&CQL_FILTER=updated_or_new+=+%27true%27&outputFormat=application/json"; 

var dpeAttentegeojson = new ol.layer.Vector({
  source: new ol.source.Vector({
    url: urldemandeDPE,
    format: new ol.format.GeoJSON()
  }),
  style: AttentestyleFunction // Utilisez la fonction de style ici
});

map.addLayer(dpeAttentegeojson);
// Afficher la couche demandé à rectifier en bleu
var certificatArectifierurl = geoserverURL + "/geoserver/M.Cyrille/ows?service=WFS&version=1.0.0&request=GetFeature&typeName=%20certificats&CQL_FILTER=a_rectifier+=+%27true%27&outputFormat=application/json"; 
var permisArectifierUrl = geoserverURL + "/geoserver/M.Cyrille/ows?service=WFS&version=1.0.0&request=GetFeature&typeName=%20permis&CQL_FILTER=a_rectifier+=+%27true%27&outputFormat=application/json"; 
var RectifierStyleFunction = function(feature) {
  return new ol.style.Style({
    fill: new ol.style.Fill({
      color: 'blue'
    }),
    stroke: new ol.style.Stroke({
      color: 'blue',
      width: 3
    }),
    image: new ol.style.Circle({
      radius: 7,
      fill: new ol.style.Fill({
        color: 'blue'
      })
    }),
    text: new ol.style.Text({
      text: feature.get('numdemande'),
      fill: new ol.style.Fill({
        color: '#000'
      }),
      stroke: new ol.style.Stroke({
        color: '#fff',
        width: 3
      })
    })
  });
};

var CertificatsRectifiergeojson = new ol.layer.Vector({
  source: new ol.source.Vector({
    url: certificatArectifierurl,
    format: new ol.format.GeoJSON(),
    wrapX: false,
  }),
  style: RectifierStyleFunction // Utilisez la fonction de style ici
});

map.addLayer(CertificatsRectifiergeojson);

var permisRectifiergeojson = new ol.layer.Vector({
  source: new ol.source.Vector({
    url: permisArectifierUrl,
    format: new ol.format.GeoJSON(),
    wrapX: false,
  }),
  style: RectifierStyleFunction // Utilisez la fonction de style ici
});

map.addLayer(permisRectifiergeojson);

// Afficher la couche en attente en jaune
var url = geoserverURL + "/geoserver/M.Cyrille/ows?service=WFS&version=1.0.0&request=GetFeature&typeName=%20certificats&CQL_FILTER=validee_publiee+=+%27true%27&outputFormat=application/json"; 

var ValideeStyleFunction = function(feature) {
  return new ol.style.Style({
    fill: new ol.style.Fill({
      color: 'transparent'
    }),
    stroke: new ol.style.Stroke({
      color: 'green',
      width: 3
    }),
    image: new ol.style.Circle({
      radius: 7,
      fill: new ol.style.Fill({
        color: 'green'
      })
    }),
    text: new ol.style.Text({
      text: feature.get('numdemande'),
      fill: new ol.style.Fill({
        color: '#000'
      }),
      stroke: new ol.style.Stroke({
        color: '#fff',
        width: 3
      })
    })
  });
};

var CertificatsValideegeojson = new ol.layer.Vector({
  source: new ol.source.Vector({
    url: url,
    format: new ol.format.GeoJSON()
  }),
  style: ValideeStyleFunction // Utilisez la fonction de style ici
});


  map.addLayer(CertificatsValideegeojson);


function showProperties(couche, entite, gid, numdemande, num, surface, observation) {
    var checkingFrom = document.getElementById('formCheckingDiv');
    checkingFrom.style.display = 'block';
    var properties = document.getElementById("properties");
    var props =  "<p> <h4 style='display:block; background-color: blue; text-align: center;color: white; padding: 2px; border-radius: 2px;'> " +
        entite + " :  " + couche+ "</h4></p><p>Numero demande : " + numdemande + "</p><p>Numero : " + num + "</p><p>Surface : " + surface + " m²</p></p><p>Observation : " + observation + "</p>";
    properties.innerHTML = props;
    var gidChamp = document.getElementById('gidChamp');
    var coucheChamp = document.getElementById("coucheChamp");
    coucheChamp.value = couche;
    gidChamp.value = gid; //identifier la couche par l'id de la couche choisie
}

function updateNotification() {
  // get the notification of the new demande
    $.ajax({
        url: '../gis/functions.php',
        data: {
            entite: 'comite', status : 'attente'
        },
        type: 'get',
        dataType: 'json',
        success: function(response) {
            // var notificationNombre = $('#Attentenombre');
            var Attentenombre = response.length; //nombre sur le tableau geojson
            // notificationNombre.text(Attentenombre);

            var demandeAttentNot = $('#demandeAttentNot');
            demandeAttentNot.empty();
            var span = $('<span>').addClass('dropdown-item dropdown-header');
            span.text(Attentenombre + ' nouvelles demandes');
            demandeAttentNot.append(span);

            $.each(response.slice(0, 5), function (index, data){
                var coordinates = data.centroid.replace('POINT(', '').replace(')', '').split(' ');
                var lon = parseFloat(coordinates[0]);
                var lat = parseFloat(coordinates[1]);
                var div = $('<div>').addClass('dropdown-divider');
                var a = $('<a>').addClass('dropdown-item');
                var props = "<p>"+ data.entity +" : "+ data.couche + "</p> demande N° " + data.numdemande + "<p></p>";
                var div2 = $('<div>');
                div2.append(props);
                a.append(div2);
                // a.text( data.entity + " : demande N° " + data.numdemande + " d'un " + data.couche);
                a.css('cursor', 'pointer');
                // a.css('width', '200px');
                // a.css('overflow', 'clip');
                // a.css('whiteSpace', 'none');
                a.on('click', function() { 
                  setVu(data.gid, data.couche);
                  centerMapTo(lat, lon);
                  showProperties(data.couche, data.entity,data.gid, data.numdemande, data.numcf, data.surface, data.observation)
                });               
                demandeAttentNot.append(div);
                demandeAttentNot.append(a);
                addStarToUnseen(data, a);
            });
        },
        error: function() {
            console.log('Error retrieving notifications');
        }
    });

    // get the notification demande needs correction
     $.ajax({
        url: '../gis/functions.php',
        data: {
            entite: 'comite', status : 'a_rectifier'
        },
        type: 'get',
        dataType: 'json',
        success: function(response) {
            var notificationNombre = $('#rectifierNombre');
            var rectifierNombre = response.length; //nombre sur le tableau geojson
            notificationNombre.text(rectifierNombre);

            var demandeArecitfierNot = $('#demandeArecitfierNot');
            demandeArecitfierNot.empty();
            var span = $('<span>').addClass('dropdown-item dropdown-header');
            span.text(rectifierNombre + ' demandes en attente');
            demandeArecitfierNot.append(span);
            $.each(response.slice(0, 5), function (index, data){
              addStarToUnseen(data, a);
                var coordinates = data.centroid.replace('POINT(', '').replace(')', '').split(' ');
                var lon = parseFloat(coordinates[0]);
                var lat = parseFloat(coordinates[1]);

                var div = $('<div>').addClass('dropdown-divider');
                var a = $('<a>').addClass('dropdown-item');
                a.text(data.entite + ' : Demande ' + data.couche + ' N° : ' + data.numdemande + ' à reviser');
                a.css('cursor', 'pointer');
                a.on('click', function() { 
                  centerMapTo(lat, lon);
                }); 
                demandeArecitfierNot.append(div);
                demandeArecitfierNot.append(a);
            });
        },
        error: function() {
            console.log('Error retrieving notifications');
        }
    });
}

var previousNombre = 0; // Initialize the previous value of 'nombre'
function NewNotification(couche) {
  $.ajax({
    url: 'vu.php',
    type: 'POST',
    data: {
      action: 'newMessageComite', attribute : 'updated_or_new'
    },
    success: function(response) {
      var notificationNombre = $('#Attentenombre');
      var Attentenombre = response[0];
      notificationNombre.text(Attentenombre);
      var x = document.createElement("AUDIO");

      if (Attentenombre > previousNombre) {
        $('#notificationSon')[0].play();
      }

       previousNombre = Attentenombre;
    },
    error: function(error) {
      console.error(error);
    }
  })
}



function setVu(gid, couche) {

  $.ajax({
    url: 'vu.php',
    type: 'POST',
    data: {gid: gid, couche: couche, action: 'setvu'},
    success: function() {
      NewNotification('certificats');
    },
    error: function(error) {
      console.error(error);
    }
  })
}

function addStarToUnseen(data, a) {
  var star =$('<span>').addClass("float-right text-sm text-danger");
  var i = $('<i>').addClass("fas fa-star");
  star.append(i);
  if (data.vu == false) {
    a.append(star);
  }
}

$(document).ready(function() {
    updateNotification(); // Appel initial de la fonction pour afficher les notifications
    NewNotification('certificats');
    setInterval(updateNotification, 5000); // Actualisation des notifications toutes les 0.5 secondes
    setInterval(function() {
      NewNotification('certificats');
      }, 5000);

});