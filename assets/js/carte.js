// assets/js/carte.js
// gestion des fonctionnalités de la carte 

var urlParams = new URLSearchParams(window.location.search);
var lat = urlParams.get('lat');
var lon = urlParams.get('lon');
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

var newview = map.getView();
if (lat && lon) {
  newview.animate({
    center: [parseFloat(lon), parseFloat(lat)], // Nouveau centre de la carte
    duration: 6000, // Durée de l'animation en millisecondes
    zoom: 10,
  });
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
var limCommuneSource = new ol.source.TileWMS({
  url: 'http://srv.gendarmerie.mg:8080/geoserver/M.Cyrille/wms',
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
  url: "http://srv.gendarmerie.mg:8080/geoserver/M.Cyrille/wms",
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
  url: "http://srv.gendarmerie.mg:8080/geoserver/M.Cyrille/wms",
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
  url: "http://srv.gendarmerie.mg:8080/geoserver/M.Cyrille/wms",
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
  // map.addLayer(Hydrolignelayer);

// couche Hydrographie linéaire
var HydrozoneSource = new ol.source.TileWMS({
  url: "http://srv.gendarmerie.mg:8080/geoserver/M.Cyrille/wms",
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
}); 
  // map.addLayer(Hydrozonelayer);


var certificatSource = new ol.source.TileWMS({
  url: "http://srv.gendarmerie.mg:8080/geoserver/M.Cyrille/wms",
  params: {
    'LAYERS': 'M.Cyrille:certificats',
    // 'STYLES': 'M.Cyrille:vw_plof',
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

var certificatLayer = new ol.layer.Tile({
  source: certificatSource,
  visible: true
});
map.addLayer(certificatLayer);

// features in this layer will be snapped to
const baseVector = new ol.layer.Vector({
  source: new ol.source.Vector({
    format: new ol.format.GeoJSON(),
    url: '../assets/js/data.json',
  }),
  style: {
    'fill-color': 'red',
    'stroke-color': 'rgba(255, 0, 0, 0.9)',
    'stroke-width': 0.5,
  },
});

// this is where the drawn features go
const drawVector = new ol.layer.Vector({
  source: new ol.source.Vector(),
  style: {
    'stroke-color': 'rgba(100, 255, 0, 1)',
    'stroke-width': 2,
    'fill-color': 'rgba(100, 255, 0, 0.3)',
  },
});

map.addLayer(baseVector);
map.addLayer(drawVector);


let drawInteraction;

const snapInteraction = new ol.interaction.Snap({
  source: baseVector.getSource(),
});


function addInteraction(type) {
  drawVector.getSource().clear();
    drawInteraction = new ol.interaction.Draw({
      type: type,
      source: drawVector.getSource(),
      trace: true,
      traceSource: baseVector.getSource(),
      style: {
        'stroke-color': 'rgba(255, 255, 100, 0.5)',
        'stroke-width': 1.5,
        'fill-color': 'rgba(255, 255, 100, 0.25)',
        'circle-radius': 6,
        'circle-fill-color': 'rgba(255, 255, 100, 0.5)',
      },
    });
    map.addInteraction(drawInteraction);
    map.addInteraction(snapInteraction);
  
  var validateButton = document.getElementById('validateButton');
  var redrawButton = document.getElementById('redrawButton');
  var confirmDiv = document.getElementById("confirmDiv");

  var confirmOverlay = new ol.Overlay({
    element: confirmDiv,
    anchor: [10, 10],
    autoPan: true,
    autoPanAnimation: {
      duration: 250,
    },
  });
  map.addOverlay(confirmOverlay);

  drawInteraction.on('drawend', function(evt) {
    map.removeInteraction(drawInteraction);
    map.removeInteraction(snapInteraction);
    validateButton.style.display = 'block';
    redrawButton.style.display = 'block';
    confirmDiv.style.display = 'block';
    var position = evt.feature.getGeometry().getLastCoordinate();
    confirmOverlay.setPosition(position);

    validateButton.addEventListener('click', function() {
      var feature = evt.feature;
      var wktWriter = new ol.format.WKT();
      var geometry = feature.getGeometry();
      var wkt = wktWriter.writeGeometry(geometry);
      var area = geometry.getArea();
      

      map.removeInteraction(drawInteraction);
      map.removeInteraction(snapInteraction);
      // var geomvalue = "Polygon(("+ geometry.getCoordinates() +"))"
      var geomvalue = wkt;

      document.getElementById('geom').value = geomvalue;
      document.getElementById('surface').value = area.toFixed(2);
      document.getElementById("areaButton").innerHTML = 'Retracer';
      validateButton.style.display = 'none';
      redrawButton.style.display = 'none';
      confirmDiv.style.display = 'none';

    });

    redrawButton.addEventListener('click', function(feature) {
      drawVector.getSource().clear();

      map.removeInteraction(drawInteraction);
      addInteraction("Polygon");

      validateButton.style.display = 'none';
      redrawButton.style.display = 'none';
      confirmDiv.style.display = 'none';
    });
  });
}

var element = document.getElementById('formDivDemande');
var areaButton = document.getElementById('areaButton');
var areaControl = new ol.control.Control({
  element: element
});

areaButton.addEventListener("click", () => {
  // désactiver les autres interactions
  areaButton.classList.toggle("clicked");
    addInteraction("Polygon");
});
map.addControl(areaControl);








               
