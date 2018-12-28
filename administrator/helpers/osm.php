<?php
/**
 * @version     3.15.0
 * @package     com_einsatzkomponente
 * @copyright   Copyright (C) 2017 by Ralf Meyer. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ralf Meyer <ralf.meyer@mail.de> - https://einsatzkomponente.de
 */
// No direct access
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

/**
 * OSM helper.
 */
 
class OsmHelper
{
	
		public static function installOsmMap()
	{
			$document = JFactory::getDocument();
			$document->addStyleSheet('components/com_einsatzkomponente/assets/leaflet/leaflet.css'); 
			$document->addScript('components/com_einsatzkomponente/assets/leaflet/leaflet.js');
			$document->addScript('components/com_einsatzkomponente/assets/leaflet/geocode.js');
			return;
	}

		public static function callOsmMap($zoom='13',$lat='53.26434271775887',$lon='7.5730027132448186')
	{
			?>
			<script type="text/javascript">
			
			var myOsmDe = L.tileLayer('https://{s}.tile.openstreetmap.de/tiles/osmde/{z}/{x}/{y}.png', {attribution:  'Map data &copy; <a href="https://osm.org/copyright"> OpenStreetMap</a> | Lizenz: <a href="http://opendatacommons.org/licenses/odbl/"> Open Database License (ODbL)</a>'});
			
			var map = L.map('map_canvas', {
				doubleClickZoom: false,
				center: [<?php echo $lat;?>, <?php echo $lon;?>],
				minZoom: 2,
				zoom: <?php echo $zoom;?>,
				layers: [myOsmDe]
			});
			
			var baseLayers = {
				"OSM deutscher Style": myOsmDe
			};
			L.control.layers(baseLayers).addTo(map);

			
			var osmGeocoder = new L.Control.OSMGeocoder();
			map.addControl(osmGeocoder);	

			
			</script>
			<?php
			return;
	}

		public static function addMarkerOsmMap($lat='53.26434271775887',$lon='7.5730027132448186')
	{
			?>
			<script type="text/javascript">
	function addMarker(e){	
		map.removeLayer(marker2);
 
		var marker = new L.marker(e.latlng,{draggable:'true',icon: blueIcon}).bindPopup().addTo(map);
	
		// Koordinaten im Feld aktualisieren
		var m = marker.getLatLng();
		document.getElementById("jform_gmap_report_latitude").value=m.lat.toFixed(15);
        document.getElementById("jform_gmap_report_longitude").value=m.lng.toFixed(15);
		
		marker.on("drag", function(e) {
			var marker = e.target;
			var m = marker.getLatLng();
			//map.panTo(new L.LatLng(m.lat, m.lng));
			document.getElementById("jform_gmap_report_latitude").value=m.lat.toFixed(15);
			document.getElementById("jform_gmap_report_longitude").value=m.lng.toFixed(15);
		});

		// Marker bei Doppelklick löschen
		marker.on('dblclick', ondblclick);	
	
		// Popup aktualisieren und öffnen wenn Marker angeklickt wird 
		marker.on('click', onclick);	
	
		// löscht den letzten Marker
		// Zeile entfernen wenn mehrere Marker angezeigt werden sollen
		map.on('click', ondblclick);

		function onclick() {
			var ZoomLevel = map.getZoom();
			var m = marker.getLatLng();
			document.getElementById("jform_gmap_report_latitude").value=m.lat.toFixed(15);
			document.getElementById("jform_gmap_report_longitude").value=m.lng.toFixed(15);
			
			//	marker._popup.setContent(
			//	  "<h4>OSM-Link (url)</h4>" 
			//	+ "www.openstreetmap.org/?mlat=" + m.lat.toFixed(6) + "&mlon=" +  m.lng.toFixed(6) 
			//	+ "#map=" + ZoomLevel + "/" + m.lat.toFixed(6) + "/" + m.lng.toFixed(6) + "<br>" 
			//	+ "<h4>Koordinaten</h4>" 
			//	+ "Lat,Lon: " + m.lat.toFixed(6) + "," + m.lng.toFixed(6) + "<br>" 
			//	+ "Lon,Lat: " + m.lng.toFixed(6) + "," + m.lat.toFixed(6)		
			//	)
		}  
	
		function ondblclick() 
			{	
			map.removeLayer(marker);
			}
	};


map.on('click', addMarker);

//*********************************************************************
// Icons globales Aussehen und Größe festlegen

var LeafIcon = L.Icon.extend({
			options: {
			iconSize:    [44, 36],		
			iconAnchor:  [9, 21],
			popupAnchor: [0, -14]
			}
});

//*********************************************************************
// Icons zuweisen

var blueIcon   = new LeafIcon({iconUrl:'<?php echo Uri::base();?>/components/com_einsatzkomponente/assets/leaflet/pin48blue.png'});
		
		
var marker2 = new L.marker([<?php echo $lat.','.$lon;?>],{draggable:'true',icon: blueIcon}).bindPopup().addTo(map);

marker2.on("drag", function(e) {
    var marker2 = e.target;
    var m = marker2.getLatLng();
    //map.panTo(new L.LatLng(m.lat, m.lng));
	document.getElementById("jform_gmap_report_latitude").value=m.lat.toFixed(15);
    document.getElementById("jform_gmap_report_longitude").value=m.lng.toFixed(15);
});

</script>
			<?php
			return;
}


	
}