<?php
/**
 * @version     3.0.0
 * @package     com_einsatzkomponente
 * @copyright   Copyright (C) 2013 by Ralf Meyer. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ralf Meyer <webmaster@feuerwehr-veenhusen.de> - http://einsatzkomponente.de
 */
// No direct access
defined('_JEXEC') or die;
/**
 * Einsatzkomponente helper.
 */
 
class EinsatzkomponenteHelper
{
	
	/**
	 * Configure the Linkbar.
	 */
	public static function addSubmenu($vName = '')
	{
		
		$uri = (string) JUri::getInstance();
		$return = urlencode(base64_encode($uri));
		
		$version = new JVersion;
        if ($version->isCompatible('3.0')) :

			$params = JComponentHelper::getParams('com_einsatzkomponente');

			  JHtmlSidebar::addEntry(
				  JText::_('COM_EINSATZKOMPONENTE_TITLE_KONTROLLCENTER'),
				  'index.php?option=com_einsatzkomponente&view=kontrollcenter',
				  $vName == 'kontrollcenter'
			  );
			  JHtmlSidebar::addEntry(
				  JText::_('COM_EINSATZKOMPONENTE_TITLE_EINSATZBERICHTE'),
				  'index.php?option=com_einsatzkomponente&view=einsatzberichte',
				  $vName == 'einsatzberichte'
			  );
			  JHtmlSidebar::addEntry(
				  JText::_('COM_EINSATZKOMPONENTE_TITLE_KATEGORIEN'),
				  'index.php?option=com_einsatzkomponente&view=kategorien',
				  $vName == 'kategorien'
			  );
			  JHtmlSidebar::addEntry(
				  JText::_('COM_EINSATZKOMPONENTE_TITLE_EINSATZARTEN'),
				  'index.php?option=com_einsatzkomponente&view=einsatzarten',
				  $vName == 'einsatzarten'
			  );
			  JHtmlSidebar::addEntry(
				  JText::_('COM_EINSATZKOMPONENTE_TITLE_ALARMIERUNGSARTEN'),
				  'index.php?option=com_einsatzkomponente&view=alarmierungsarten',
				  $vName == 'alarmierungsarten'
			  );
			  JHtmlSidebar::addEntry(
				  JText::_('COM_EINSATZKOMPONENTE_TITLE_EINSATZFAHRZEUGE'),
				  'index.php?option=com_einsatzkomponente&view=einsatzfahrzeuge',
				  $vName == 'einsatzfahrzeuge'
			  );
			  if ($params->get('eiko','0')) :
        		JHtmlSidebar::addEntry(
			JText::_('COM_EINSATZKOMPONENTE_TITLE_AUSRUESTUNGEN'),
			'index.php?option=com_einsatzkomponente&view=ausruestungen',
			$vName == 'ausruestungen'
		);
			  endif;
			  JHtmlSidebar::addEntry(
				  JText::_('COM_EINSATZKOMPONENTE_TITLE_ORGANISATIONEN'),
				  'index.php?option=com_einsatzkomponente&view=organisationen',
				  $vName == 'organisationen'
			  );
			  JHtmlSidebar::addEntry(
				  JText::_('COM_EINSATZKOMPONENTE_TITLE_EINSATZBILDMANAGER'),
				  'index.php?option=com_einsatzkomponente&view=einsatzbildmanager',
				  $vName == 'einsatzbildmanager'
			  );
			  if ($params->get('gmap_action','0')) :
			  JHtmlSidebar::addEntry(
				  JText::_('COM_EINSATZKOMPONENTE_TITLE_GMAPKONFIGURATIONEN'),
				  'index.php?option=com_einsatzkomponente&view=gmapkonfigurationen',
				  $vName == 'gmapkonfigurationen'
			  );
			  endif;
			  
			  JHtmlSidebar::addEntry(
				  JText::_('Optionen'),
				  'index.php?option=com_config&view=component&component=com_einsatzkomponente&return=' . $return,
				  $vName == 'configuration'
			  );
		endif; 
	}
	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return	JObject
	 * @since	1.6
	 */
	public static function getActions()
	{
		$user	= JFactory::getUser();
		$result	= new JObject;
		$assetName = 'com_einsatzkomponente';
		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);
		foreach ($actions as $action) {
			$result->set($action, $user->authorise($action, $assetName));
		}
		return $result;
	}
	
	public static function load_gmap_config()
	{
		// GMap-Config laden
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__eiko_gmap_config');
		$query->where('id="1"');
		$db->setQuery($query);
		$result = $db->loadObject();

		return $result;
	}
	
    public static function ermittle_einsatz_nummer ($selectedDate) {
		$query = 'SELECT COUNT(*) AS total FROM #__eiko_einsatzberichte WHERE (date1 BETWEEN "'.date('Y', $selectedDate).'-01-01 00:00:00" AND "'.date('Y-m-d H:i:s', $selectedDate).'") AND (state = "1" OR state = "2")  ' ;
		//return $query;
		$db	= JFactory::getDBO();
		$db->setQuery( $query );
		$result = $db->loadObjectList();
        return $result[0]->total;
    }
	

    public static function count_einsatz_daten_bestimmtes_jahr ($selectedYear) {
		// Funktion : Einsatzdaten für ein bestimmtes Jahr aus der DB holen<br />
		$query = 'SELECT COUNT(r.id) as total FROM #__eiko_einsatzberichte r JOIN #__eiko_einsatzarten rd ON r.data1 = rd.id LEFT JOIN #__eiko_alarmierungsarten re ON re.id = r.alerting WHERE r.date1 LIKE "'.$selectedYear.'%" AND (r.state = "1" OR r.state = "2") and rd.state = "1" and re.state ="1" GROUP BY r.id ORDER BY r.date1 DESC ' ;
		$db	= JFactory::getDBO();
		$db->setQuery( $query );
		$result = $db->loadObjectList();
        return $result;
    }

    public static function einsatz_daten_bestimmtes_jahr ($selectedYear,$limit,$limitstart) {
		// Funktion : Einsatzdaten für ein bestimmtes Jahr aus der DB holen<br />
		$query = 'SELECT COUNT(r.id) as total,r.id,r.image as foto,rd.marker,r.address,r.summary,r.date1,r.data1,r.counter,r.alerting,r.presse,r.gmap_report_latitude,r.gmap_report_longitude,re.image,re.title as alarmierungsart,rd.list_icon,rd.icon,r.desc,r.auswahl_orga,r.ausruestung,r.state,rd.title as einsatzart,r.tickerkat,r.gmap FROM #__eiko_einsatzberichte r JOIN #__eiko_einsatzarten rd ON r.data1 = rd.id LEFT JOIN #__eiko_alarmierungsarten re ON re.id = r.alerting WHERE r.date1 LIKE "'.$selectedYear.'%" AND (r.state = "1" OR r.state = "2") and rd.state = "1" and re.state ="1" GROUP BY r.id ORDER BY r.date1 DESC LIMIT '.$limitstart.','.$limit.' ' ;
		$db	= JFactory::getDBO();
		$db->setQuery( $query );
		$result = $db->loadObjectList();
        return $result;
    }
	
    public static function letze_x_einsatzdaten ($x) {
		// Funktion : letze x Einsatzdaten laden
		$query = 'SELECT r.id,r.image as foto,rd.marker,r.address,r.summary,r.auswahl_orga,r.ausruestung,r.desc,r.date1,r.data1,r.counter,r.alerting,r.presse,re.image,rd.list_icon,r.auswahl_orga,r.state,rd.title as einsatzart,r.tickerkat FROM #__eiko_einsatzberichte r JOIN #__eiko_einsatzarten rd ON r.data1 = rd.id LEFT JOIN #__eiko_alarmierungsarten re ON re.id = r.alerting WHERE (r.state = "1" OR r.state = "2") and rd.state = "1" and re.state = "1" ORDER BY r.date1 DESC LIMIT '.$x.' ' ;
		$db	= JFactory::getDBO();
		$db->setQuery( $query );
		$result = $db->loadObjectList();
        return $result;
    }
		
	public static function getYear() 
	{
		// Funktion : Alle Jahreszahlen aller Einsätze zusammenfassen
		$db = JFactory::getDBO();
		$query = 'SELECT Year(date1) as id, Year(date1) as title FROM `#__eiko_einsatzberichte` WHERE (state="1" OR state = "2") GROUP BY title ORDER BY date1 DESC';
		$db->setQuery($query);
		$result = $db->loadObjectList();
        return $result;
	}
	
	public static function getVersion()
	{
		// Funktion : Installierte Version ermitteln
		$db = JFactory::getDbo();
		$db->setQuery('SELECT manifest_cache FROM #__extensions WHERE name = "com_einsatzkomponente"');
		$params = json_decode( $db->loadResult(), true );
        $version = $params['version'];
        return $version;
	}
	
	public static function getEinsatzbilder ($report_id = '0') 
	{
		// Funktion : Alle Einsatzbilder per ID laden
		$db = JFactory::getDBO();
		$query = 'SELECT * FROM `#__eiko_images` WHERE report_id = "'.$report_id.'" AND state ="1" ORDER BY ordering';
		$db->setQuery($query);
		$result = $db->loadObjectList();
        return $result;
	}

    public static function getOrganisationen() {
 		// Funktion : Feuerwehrliste aus DB holen
		$db = JFactory::getDBO();
		$query = 'SELECT * FROM `#__eiko_organisationen` WHERE state="1" ORDER BY `id`';
		$db->setQuery($query);
		$result = $db->loadObjectList();
        return $result;
    }
    public static function getStandort_orga($orgas) {
 		// Funktion : Standard-Oragnisation aus DB holen
				$array = array();
				foreach((array)$orgas as $value): 
					if(!is_array($value)):
						$array[] = $value;
					endif;
				endforeach;
				$data = array();
		$db = JFactory::getDBO();
		$query = 'SELECT gmap_latitude,gmap_longitude,name,gmap_icon_orga,ffw FROM `#__eiko_organisationen` WHERE state="1" and id="'.$array[0].'" ';
		$db->setQuery($query);
		$result = $db->loadObject();
        return $result;
    }
	
	
    public static function getEinsatzarten() {
		// Funktion : Liste der Einsatzarten aus DB holen
		$query = 'SELECT id, title as title FROM `#__eiko_einsatzarten` WHERE state="1" ORDER BY `ordering` ASC';
		$db = JFactory::getDBO();
		$db->setQuery($query);
		$result = $db->loadObjectList();
        return $result;
    }
	
    public static function getPrev_id($cur_date,$selectedOrga) {
		// Funktion : Den Einsatz vor dem aktuellen Einsatz ermitteln
		if ($selectedOrga == 'alle Organisationen') :
		$query = 'SELECT id,summary FROM `#__eiko_einsatzberichte` WHERE `date1` < "'.$cur_date.'"  AND `state`="1" ORDER BY `date1` desc LIMIT 1';
		$db = JFactory::getDBO();
		$db->setQuery($query);
		$result = $db->loadObjectList();
        return $result;
		else:
		
		$query = 'SELECT id,summary FROM `#__eiko_einsatzberichte` WHERE `date1` < "'.$cur_date.'" AND auswahl_orga LIKE "%'.$selectedOrga.'%"  AND `state`="1" ORDER BY `date1` desc LIMIT 1';
		$db = JFactory::getDBO();
		$db->setQuery($query);
		$result = $db->loadObjectList();
        return $result;
		endif;
		
    }
	
    public static function getNext_id($cur_date,$selectedOrga) {
		// Funktion : Den Einsatz nach dem aktuellen Einsatz ermitteln
		if ($selectedOrga == 'alle Organisationen') :
		$query = 'SELECT id,summary FROM `#__eiko_einsatzberichte` WHERE `date1` > "'.$cur_date.'"  AND `state`="1" ORDER BY `date1` asc LIMIT 1';
		$db = JFactory::getDBO();
		$db->setQuery($query);
		$result = $db->loadObjectList();
        return $result;
		else:
		$query = 'SELECT id,summary FROM `#__eiko_einsatzberichte` WHERE `date1` > "'.$cur_date.'"  AND auswahl_orga LIKE "%'.$selectedOrga.'%"  AND `state`="1" ORDER BY `date1` asc LIMIT 1';
		$db = JFactory::getDBO();
		$db->setQuery($query);
		$result = $db->loadObjectList();
        return $result;
		endif;
    }
	
    public static function getEinsatzlogo($data1) {
		// Funktion : Daten für Einsatzlogo holen
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
					$query
						->select('*')
						->from('`#__eiko_einsatzarten`')
						->where('id = "' .$data1.'"  AND state = "1" ');
					$db->setQuery($query);
					$result = $db->loadObject();
        return $result;
    }



    public static function getTickerKat($kat) {
		// Funktion : Einsatzkategorie
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
					$query
						->select('*')
						->from('`#__eiko_tickerkat`')
						->where('id = "' .$kat.'"  AND state = "1" ');
					$db->setQuery($query);
					$result = $db->loadObject();

        return $result;
    }
	
    public static function getAlarmierungsart($alerting) {
		// Funktion : Daten für Einsatzlogo holen
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
					$query
						->select('*')
						->from('`#__eiko_alarmierungsarten`')
						->where('id = "' .$alerting.'"  AND state = "1" ');
					$db->setQuery($query);
					$result = $db->loadObject();
        return $result;
    }
	
    public static function getOrga_fahrzeuge($orga_id) {
 		// Funktion : Feuerwehrliste aus DB holen
		$db = JFactory::getDBO();
		$query = 'SELECT * FROM `#__eiko_fahrzeuge` WHERE department = "'.$orga_id.'" and (state = 1 or state = 2) ORDER BY `ordering`';
		$db->setQuery($query);
		$result = $db->loadObjectList();
        return $result;
	}
	
    public static function getFahrzeuge_mission ($array_vehicle ='',$orga_id='',$title='') {
 		// Funktion : sonstige Fahrzeuge aus DB holen
						$params = JComponentHelper::getParams('com_einsatzkomponente');
						$sonstige ='';
						$sonstige_result = '';
						$query = 'SELECT * from #__eiko_fahrzeuge where department = "'.$orga_id.'" and (state = 1 or state = 2) order by ordering ASC';
						$db = JFactory::getDBO();
                        $db->setQuery($query);
                        if ($vehicles = $db->loadObjectList()) :
                                foreach ($vehicles as $vehicle) {
						if (in_array($vehicle->id, $array_vehicle)) : 
						if ($vehicle->state == '2'): $vehicle->name = $vehicle->name.' (a.D.)';endif;
						if ($params->get('display_detail_fhz_links','1')) :
						if (!$vehicle->link) : 
						$sonstige .= '<a href="'.JRoute::_('index.php?option=com_einsatzkomponente&view=einsatzfahrzeug&id=' . $vehicle->id).'" target="_self"><li>'.$vehicle->name.'</li></a>';
						else:
						$sonstige .= '<a href="'.$vehicle->link.'" target="_self"><li>'.$vehicle->name.'</li></a>';
						endif;
						else:
						$sonstige .= '<li>'.$vehicle->name.'</li>';
						endif;
						
						endif;
                                }
						if ($sonstige):
						$sonstige_result = '';
						$sonstige_result = $title;
						$sonstige_result .= '<div class="items"><ul class="items_list">';
						$sonstige_result .= $sonstige;
						$sonstige_result .= '</ul></div>'; 
						endif;
						endif;
        return $sonstige_result;
	}
    public static function getFahrzeuge_mission_image ($array_vehicle ='',$orga_id='') {
 		// Funktion : sonstige Fahrzeuge aus DB holen
						$params = JComponentHelper::getParams('com_einsatzkomponente');
						$vehicles_image ='';
						$vehicles_images ='';
						$sonstige_result = '';
						$query = 'SELECT * from #__eiko_fahrzeuge where department = "'.$orga_id.'" and (state = 1 or state = 2) order by ordering ASC';
						$db = JFactory::getDBO();
                        $db->setQuery($query);
                        if ($vehicles = $db->loadObjectList()) :
                                foreach ($vehicles as $vehicle) {
						if (in_array($vehicle->id, $array_vehicle)) : 
						if ($vehicle->state == '2'): $vehicle->name = $vehicle->name.' (a.D.)';endif;
						if ($params->get('display_detail_fhz_links','1')) :
						if (!$vehicle->link) : 
						$vehicles_image .= '<a href="'.JRoute::_('index.php?option=com_einsatzkomponente&view=einsatzfahrzeug&id=' . $vehicle->id).'" target="_self">&nbsp;&nbsp;<img class="eiko_img-rounded eiko_image_fahrzeugaufgebot" src="'.JURI::Root().$vehicle->image.'"  alt="'.$vehicle->name.'" title="'.$vehicle->name.'   '.$vehicle->detail2.' ('.$vehicle->department.' )"/></a>';
						else:
						$vehicles_image .= '<a href="'.$vehicle->link.'" target="_self">&nbsp;&nbsp;<img class="eiko_img-rounded eiko_image_fahrzeugaufgebot" src="'.JURI::Root().$vehicle->image.'"  alt="'.$vehicle->name.'" title="'.$vehicle->name.'   '.$vehicle->detail2.' ('.$vehicle->department.' )"/></a>';
						endif;
						else:
						$vehicles_image .= '&nbsp;&nbsp;<img class="eiko_img-rounded eiko_image_fahrzeugaufgebot" src="'.JURI::Root().$vehicle->image.'"  alt="'.$vehicle->name.'" title="'.$vehicle->name.'   '.$vehicle->detail2.' ('.$vehicle->department.' )"/>';
						endif;
						
						endif;
                                }
						if ($vehicles_image):
						$vehicles_images = $vehicles_image;
						endif;
						endif;
        return $vehicles_images;
	}
	
public static function getGmap($marker1_title='',$marker1_lat='1',$marker1_lng='1',$marker1_image='circle.png',$marker2_title='',$marker2_lat='1',$marker2_lng='1',$marker2_image='icon.png',$center_lat='1',$center_lng='1',$gmap_zoom_level='1',$gmap_onload='HYBRID',$zoom_control = 'false',$organisationen='[["",1,1,0,"images/com_einsatzkomponente/images/map/icons/haus_rot.png"],["",1,1,1,"images/com_einsatzkomponente/images/map/icons/haus_rot.png"] ]',$orga_image='haus_rot.png',$einsatzgebiet='[53.28071418254047,7.416630163574155],[53.294772929932165,7.4492458251952485],[53.29815865222114,7.4767116455077485],[53.31313468829642,7.459888830566342],[53.29949234792138,7.478256597900327],[53.29815865222114,7.506409063720639],[53.286461382800795,7.521686926269467],[53.26726681991669,7.499027624511655]',$display_detail_popup='false',$standort,$display_map_route="false")
 {
$params = JComponentHelper::getParams('com_einsatzkomponente');
$gmap ='function initialize() {
	
  var isDraggable = window.innerWidth > 680 ? true : false;
  var pan = window.innerWidth > 680 ? false : true;
  
  var mapOptions = {
	center: new google.maps.LatLng("'.$center_lat.'", "'.$center_lng.'"),
    zoom: '.$gmap_zoom_level.',
	maxZoom: '.$gmap_zoom_level.',
	disableDefaultUI: true,
	mapTypeControl: true,
	panControl: pan,
	draggable: isDraggable,
    scrollwheel: false,
    disableDoubleClickZoom: true,
	streetViewControl: false,
    keyboardShortcuts: false,
    mapTypeControlOptions: {
      style: google.maps.MapTypeControlStyle.DROPDOWN_MENU },
    mapTypeId: google.maps.MapTypeId.'.$gmap_onload.',
    zoomControl: '.$zoom_control.',
    zoomControlOptions: {
      style: google.maps.ZoomControlStyle.SMALL},
  }

  var map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
  var image = "'.JURI::base().$marker2_image.'";
  var myLatlng = new google.maps.LatLng("'.$marker2_lat.'","'.$marker2_lng.'");
  var marker2Marker = new google.maps.Marker({
      position: myLatlng,
      map: map,
      icon: image,
      title: "'.$marker2_title.'"
  });
  var infowindow_marker2 = new google.maps.InfoWindow({
      content: "'.$marker2_title.'"
  });
  google.maps.event.addListener(marker2Marker, "click", function() {
    infowindow_marker2.open(map,marker2Marker);
  });
google.maps.event.addListener(map, "click", function() { infowindow_marker2.close(); });
  
  var image = "'.JURI::base().$marker1_image.'";
	var image = {
    url: "'.JURI::base().$marker1_image.'",
    scaledSize: new google.maps.Size('.$params->get('einsatzkarte_gmap_icon', 14).','.$params->get('einsatzkarte_gmap_icon', 14).') 
    };
  var myLatLng = new google.maps.LatLng("'.$marker1_lat.'","'.$marker1_lng.'");
  var marker1Marker = new google.maps.Marker({
      position: myLatLng,
      map: map,
      icon: image,
	  title: "'.$marker1_title.'"
  });
  var infowindow_marker1 = new google.maps.InfoWindow({
      content: "'.$marker1_title.'"
  });
  google.maps.event.addListener(marker1Marker, "click", function() {
    infowindow_marker1.open(map,marker1Marker);
  });
  google.maps.event.addListener(map, "click", function() { infowindow_marker1.close(); });
  
  var popup = "'.$display_detail_popup.'";
  if (popup == "true") {
  infowindow_marker1.open(map,marker1Marker); // Infofenster beim Laden der Seite öffnen
  }
  
// Route anzeigen ANFANG ------------------------------------------------------------------
  var route = "'.$display_map_route.'";
  if (route == "true") {
		directionsService = new google.maps.DirectionsService();
		directionsDisplay = new google.maps.DirectionsRenderer(
		{
			suppressMarkers: true,      // Anfang und Endmarker ausblenden
			suppressInfoWindows: true,
			            preserveViewport:false,     // zoom-faktor auf auto stellen
		    			        });
		directionsDisplay.setMap(map);
  
var request = {
			destination:new google.maps.LatLng("'.$marker1_lat.'", "'.$marker1_lng.'"),
			origin: new google.maps.LatLng("'.$standort->gmap_latitude.'","'.$standort->gmap_longitude.'"),
			travelMode: google.maps.DirectionsTravelMode.DRIVING
		};
		directionsService.route(request, function(response, status) 
		{
		if (status == google.maps.DirectionsStatus.OK) 
			{
				directionsDisplay.setDirections(response);
	distance = "(Der Anfahrtsweg betrug ca. "+response.routes[0].legs[0].distance.text+")";
				/*distance += "<br/>Fahrtzeit ca. "+response.routes[0].legs[0].duration.text;*/
				document.getElementById("distance_road").innerHTML = distance;
							}
		});
  }
// Route anzeigen ENDE ----------------------------------------------------------------

var orgas = '.$organisationen.';
setMarkers(map, orgas);
function setMarkers(map, locations) {
  for (var i = 0; i < locations.length; i++) {
    var orgas = locations[i];
    var myLatLng = new google.maps.LatLng(orgas[1], orgas[2]);
	var image = {
    url: "'.JURI::base().'"+orgas[4],
    scaledSize: new google.maps.Size('.$params->get('einsatzkarte_gmap_icon_orga', 24).','.$params->get('einsatzkarte_gmap_icon_orga', 24).') 
    };

    var marker = new google.maps.Marker({
        position: myLatLng,
        map: map,
        icon: image,
        title: orgas[0]
    });
  }
}

 var einsatzgebiet_coords = '.$einsatzgebiet.';

  einsatzgebiet = new google.maps.Polygon({
    paths: einsatzgebiet_coords,
    strokeColor: "#FF0000",
    strokeOpacity: 0.8,
    strokeWeight: 2,
    fillColor: "#FF0000",
    fillOpacity: 0.25
  });

  einsatzgebiet.setMap(map);

  
}
google.maps.event.addDomListener(window, "load", initialize);';
return $gmap; }
	
	
	
public static function getOsm($marker1_title='',$marker1_lat='1',$marker1_lng='1',$marker1_image='circle.png',$marker2_title='',$marker2_lat='1',$marker2_lng='1',$marker2_image='icon.png',$center_lat='1',$center_lng='1',$gmap_zoom_level='1',$gmap_onload='HYBRID',$zoom_control = 'false',$organisationen='[["",1,1,0,"../../images/com_einsatzkomponente/images/map/icons/haus_rot.png"],["",1,1,1,"../../images/com_einsatzkomponente/images/map/icons/haus_rot.png"] ]',$orga_image='haus_rot.png',$einsatzgebiet='[ [53.28071418254047,7.416630163574155],[53.294772929932165,7.4492458251952485],[53.29815865222114,7.4767116455077485],[53.31313468829642,7.459888830566342],[53.29949234792138,7.478256597900327],[53.29815865222114,7.506409063720639],[53.286461382800795,7.521686926269467],[53.26726681991669,7.499027624511655] ]',$display_detail_popup='false',$standort,$display_map_route="true")
 {
$params = JComponentHelper::getParams('com_einsatzkomponente');
$gmap ='//<![CDATA[

var map;

var showPopupOnHover = false;
text = new Array("Informationen zur Karte anzeigen","Informationen zur Karte verstecken");

function drawmap() {
    OpenLayers.Lang.setCode("de");
	map = new OpenLayers.Map ("map", {minZoom:1, maxZoom:'.$gmap_zoom_level.'});
	map.addControl(new OpenLayers.Control.Navigation({disableZoomWheel: true,defaultDblClick: false}));
	map.addControl(new OpenLayers.Control.PinchZoom({preserveCenter: true}));
	var layer_overviewmap = new OpenLayers.Layer.OSM.Mapnik("Mapnik");
	map.addControl(new OpenLayers.Control.OverviewMap({layers: [layer_overviewmap]}));

// Position und Zoomstufe der Karte
lon = '.$center_lng.';
lat = '.$center_lat.';
zoom = '.$gmap_zoom_level.'; 

//checkForPermalink();




// Layer hinzufügen

layer_markers = new OpenLayers.Layer.Markers("Marker", { projection: new OpenLayers.Projection("EPSG:4326"),visibility: true, displayInLayerSwitcher: false });
layer_standort = new OpenLayers.Layer.Markers("Standorte", { projection: new OpenLayers.Projection("EPSG:4326"),visibility: true, displayInLayerSwitcher: true });
layer_vectors = new OpenLayers.Layer.Vector("Zeichnungen", { displayInLayerSwitcher: false } );
map.addLayer(layer_vectors);
map.addLayer(layer_markers)
map.addLayer(layer_standort)

layers = new Array();
layer_layerMapnik = new OpenLayers.Layer.OSM.Mapnik("Mapnik");
map.addLayer(layer_layerMapnik)
layers.push(new Array(layer_layerMapnik,"layer_layerMapnik"));
setLayer(0);


// An die richtige Stelle springen..
jumpTo(lon,lat,zoom);

// Benutzte Marker Icons hinzufügen..
icons = new Array();
icons[4] = new Array("'.JURI::base().$marker1_image.'","'.$params->get('einsatzkarte_gmap_icon', 24).'","'.$params->get('einsatzkarte_gmap_icon', 24).'","0","1");


// Marker hinzufügen
addMarker(layer_markers,'.$marker1_lng.','.$marker1_lat.',"'.$marker1_title.'",'.$display_detail_popup.',4);


var orgas = '.$organisationen.';
setMarkers(map, orgas);
function setMarkers(map, locations) {
   for (var i = 0; i < locations.length; i++) {
     var orgas = locations[i];
	icons[i] = new Array("'.JURI::base().'"+orgas[4],"'.$params->get('einsatzkarte_gmap_icon_orga', 24).'","'.$params->get('einsatzkarte_gmap_icon_orga', 24).'","0","1");
	 addMarker(layer_standort,orgas[2],orgas[1],orgas[0],false,i);
  } }


geometries = new Array();geometries.push(drawPolygon('.$einsatzgebiet.',{strokeColor:"#FF0000",strokeWidth: 0.5,fillColor: "#FF0000",fillOpacity: 0.1}));



// Nochmal was..
jumpTo(lon, lat, zoom);
//toggleInfo();
checkUtilVersion(4);
}
//]]>';
return $gmap; }
	

    public static function getSocial($params,$id,$summary) {

			// Funktion URL kürzen
			function shortTinyUrl($url){
				$res = "";
				$handle = @fopen("http://tinyurl.com/api-create.php?url=".urlencode($url), "rb");
				if($handle){
				  while (!feof($handle)) {
					$res .= fgets($handle,2000);
				  }
				  fclose($handle);
				}
				else{
				  $layout_detail_link = JURI::current();	
				}
				return $res;
			}
  			if (!$layout_detail_link = shortTinyUrl( JURI::current() )):
				$layout_detail_link = JURI::current();
				endif;
			// Funktion URL kürzen ENDE
			
	  	$social = '';
		
 		if ( $params->get('show_twitter', '1') ): 
		$social .='<span class="eiko_social_2">';
		$social .='<a href="https://twitter.com/share" data-url="'.$layout_detail_link.'" data-via="'.$params->get('twitter_via', '').'" data-text="#Einsatzinfo: #'.$summary.' --- " class="twitter-share-button">Tweet</a>';
		$social .="<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></span>";
		endif;
		
		if ( $params->get('show_gplus', '1') ): 
		$social .='<span class="eiko_social_2">';
		$social .='<div class="g-plusone" data-size="medium" data-href="'. $layout_detail_link . '"></div>';
		$social .='<script type="text/javascript">(function() { var po = document.createElement("script"); po.type = "text/javascript"; po.async = true; po.src = "https://apis.google.com/js/plusone.js"; var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(po, s);  })();</script></span>';
	endif; 
	
		if ( $params->get('show_facebook', '1') ): 
		$social .='<span class="eiko_social_2">'; 
		$social .='<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/de_DE/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, "script", "facebook-jssdk"));</script><div class="fb-share-button" data-href="' . $layout_detail_link . '" data-width="" data-type="button_count"></div></span>';
 		endif; 
		
		return $social;
	}
	
	    public static function getNavbar($params,$prev_id,$next_id,$id,$menu_link) {
	
	$navbar  ='';
	$navbar .='<div class="btn-group-justified">';
	
	if( $prev_id) : 
    $navbar .='<a href="'.JRoute::_('index.php?option=com_einsatzkomponente&view=einsatzbericht&id=' . (int)$prev_id).'" class="btn eiko_btn_2" title="">';
    $navbar .='<strong>Zurück</strong></a>';
	endif; 
	
	if( $next_id) :
    $navbar .='<a href="'.JRoute::_('index.php?option=com_einsatzkomponente&view=einsatzbericht&id=' . (int)$next_id).'" class=" btn eiko_btn_2" title="">';
    $navbar .='<strong>Vor</strong></a>';
	endif; ?>
    
    <?php if ($menu_link=='&Itemid=') : 
			$menu_link = JRoute::_('index.php?Itemid='.$params->get('homelink','').'');
			endif;
			?>
    
	<?php if( $menu_link) :  
    $navbar .='<a href="'.$menu_link.'&list=1" class="btn eiko_btn_2"><strong>Übersicht</strong></a>';
	endif;
	if( !$menu_link) :
    $navbar .='<a href="'.JRoute::_('index.php?option=com_einsatzkomponente&view=einsatzberichte&Itemid='.$params->get('homelink','').'').'&list=1" class="btn eiko_btn_2"><strong>Übersicht</strong></a>';
	endif; 
	if(JFactory::getUser()->authorise('core.edit.own', 'com_einsatzkomponente') OR JFactory::getUser()->authorise('core.edit', 'com_einsatzkomponente')):
		$user=JFactory::getUser();
		$query = 'SELECT created_by FROM `#__eiko_einsatzberichte` WHERE state="1" AND id ="'.$id.'"';
		$db = JFactory::getDBO();
		$db->setQuery($query);
		$result = $db->loadResult();
	if ($user->id == $result OR JFactory::getUser()->authorise('core.edit', 'com_einsatzkomponente')) :
    $navbar .='<a href="'.JRoute::_('index.php?option=com_einsatzkomponente&view=einsatzberichtform&layout=edit&id='.$id).'" class=" btn eiko_btn_2">';
    $navbar .='<strong>Editieren</strong></a>';
	endif;
    endif;
	
	$navbar .='</div>';
		return $navbar;
	}

	    public static function getValidation() { 
		
		$db = JFactory::getDbo();
		$db->setQuery('SELECT manifest_cache FROM #__extensions WHERE name = "com_einsatzkomponente"');
		$params = json_decode( $db->loadResult(), true );
        $eikoversion = $params['version'];

		$version = new JVersion;
		$params = JComponentHelper::getParams('com_einsatzkomponente');
		$response = @file("http://einsatzkomponente.de/gateway/validation.php?validation=".$params->get('validation_key','0')."&domain=".$_SERVER['SERVER_NAME']."&version=".$version->getShortVersion()."&eikoversion=".$eikoversion); // Request absetzen
		@$response_code = intval($response[1]); // Rückgabewert auslesen
if ($response_code=='12') :	
$params->set('eiko', '1');
$db = JFactory::getDBO();
$query = $db->getQuery(true);
$query->update('#__extensions AS a');
$query->set('a.params = ' . $db->quote((string)$params));
$query->where('a.element = "com_einsatzkomponente"');
$db->setQuery($query);
		try
		{
			$db->execute();
		}
		catch (RuntimeException $e)
		{
			JError::raiseError(500, $e->getMessage());
		}
else:
$params->set('eiko', '0');

		$db = JFactory::getDbo();
		$db->setQuery('SELECT manifest_cache FROM #__extensions WHERE name = "com_einsatzkomponente"');
		$paramms = json_decode( $db->loadResult(), true );
        $version = $paramms['version'];
        if($version!=str_replace("Premium","",$version)):
		$paramms = JComponentHelper::getParams('com_einsatzkomponente');
		$paramms->set('eiko', '1');
		$response_code='12';
		endif;  

$db = JFactory::getDBO();
$query = $db->getQuery(true);
$query->update('#__extensions AS a');
$query->set('a.params = ' . $db->quote((string)$params));
$query->where('a.element = "com_einsatzkomponente"');
$db->setQuery($query);
//$db->query();
		try
		{
			$db->execute();
		}
		catch (RuntimeException $e)
		{
			JError::raiseError(500, $e->getMessage());
		}
endif;		
		return $response_code;
		}
		
		
    public function sendMail($cid) {

		// Check for request forgeries
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
		$user = JFactory::getUser();
		// Get items to remove from the request.
		$cid = JFactory::getApplication()->input->get('cid', array(), 'array');
		

		if (!is_array($cid) || count($cid) < 1)
		{
			JLog::add(JText::_($this->text_prefix . '_NO_ITEM_SELECTED'), JLog::WARNING, 'jerror');
		}
		else
		{
		//$model = $this->getModel();
		$params = JComponentHelper::getParams('com_einsatzkomponente');
			// Make sure the item ids are integers
			jimport('joomla.utilities.arrayhelper');
			JArrayHelper::toInteger($cid);
			
		foreach ($cid as $key => $val) {
			
		$query = 'SELECT * FROM `#__eiko_einsatzberichte` WHERE `id` = "'.$val.'" LIMIT 1';
		$db = JFactory::getDBO();
		$db->setQuery($query);
		$result = $db->loadObjectList();
	
		$mailer = JFactory::getMailer();
		$config = JFactory::getConfig();
		
		//$sender = array( 
    	//$config->get( 'config.mailfrom' ),
    	//$config->get( 'config.fromname' ) );
		$sender = array( 
    	$user->email,
    	$user->name );
		
		$mailer->setSender($sender);
		
		$user = JFactory::getUser();
		//$recipient = $user->email;
		$recipient = $params->get('mail_empfaenger',$user->email);
		
		$recipient 	 = explode( ',', $recipient);
		
					$data = array();
					foreach(explode(',',$result[0]->auswahl_orga) as $value):
						$db = JFactory::getDbo();
						$query	= $db->getQuery(true);
						$query
							->select('name')
							->from('`#__eiko_organisationen`')
							->where('id = "' .$value.'"');
						$db->setQuery($query);
						$results = $db->loadObjectList();
						if(count($results)){
							$data[] = ''.$results[0]->name.''; 
						}
					endforeach;
					$auswahl_orga=  implode(',',$data); 

		$orga		 = explode( ',', $auswahl_orga);
		$orgas 		 = str_replace(",", " +++ ", $auswahl_orga);
 
		$mailer->addRecipient($recipient);
		
		$mailer->setSubject(''.$orga[0].'  +++ '.$result[0]->summary.' +++');
		
		$kat	= EinsatzkomponenteHelper::getTickerKat ($result[0]->tickerkat); 
		
		$link = JRoute::_( JURI::root() . 'index.php?option=com_einsatzkomponente&view=einsatzbericht&id='.$result[0]->id.'&Itemid='.$params->get('homelink','')); 
		
		$body   = ''
				. '<h2>+++ '.$result[0]->summary.' +++</h2>';
		if ($params->get('send_mail_kat','0')) :	
		$body   .= '<h4>'.JText::_($kat->title).'</h4>';
		endif;
		if ($params->get('send_mail_orga','0')) :	
		$body   .= '<span><b>Eingesetzte Kräfte:</b> '.$orgas.'</span>';
		endif;
		$body   .= '<div>';
		if ($params->get('send_mail_desc','0')) :	
		if ($result[0]->desc) :	
    	$body   .= '<p>'.$result[0]->desc.'</p>';
		else:
    	$body   .= '<p>Ein ausführlicher Bericht ist zur Zeit noch nicht vorhanden.</p>';
		endif;
		endif;
		if ($params->get('send_mail_link','0')) :	
    	$body   .= '<p><a href="'.$link.'" target="_blank">Link zur Homepage</a></p>';
		endif;
		if ($result[0]->image) :	
		if ($params->get('send_mail_image','0')) :	
		$body   .= '<img src="'.JURI::root().$result[0]->image.'" style="margin-left:10px;float:right;height:50%;" alt="Einsatzbild"/>';
		endif;
		endif;
		$body   .= '</div>';
		

		$mailer->isHTML(true);
		$mailer->Encoding = 'base64';
		$mailer->setBody($body);
		// Optionally add embedded image
		//$mailer->AddEmbeddedImage( JPATH_COMPONENT.'/assets/logo128.jpg', 'logo_id', 'logo.jpg', 'base64', 'image/jpeg' );
		
		$send = $mailer->Send();
		}
        $msg    = count($cid).' Mail(s) an '.count($recipient).' Empfänger versendet ('.$params->get('mail_empfaenger',$user->email).')';  
		}
        return $msg; 
    }
	
	
	
	
	// Joomla-Core  Override    Bugfix
	public static function kalender($value, $name, $id, $format = '%Y-%m-%d', $attribs = null)
	{
		static $done;

		if ($done === null)
		{
			$done = array();
		}
	
		$attribs = substr("$attribs", 0, -1); // Bugfix
		$attribs = (array) $attribs; // Bugfix
		
		$attribs['class'] = isset($attribs['class']) ? $attribs['class'] : 'input-medium';
		$attribs['class'] = trim($attribs['class'] . ' hasTooltip');

		$readonly = isset($attribs['readonly']) && $attribs['readonly'] == 'readonly';
		$disabled = isset($attribs['disabled']) && $attribs['disabled'] == 'disabled';

		if (is_array($attribs))
		{
			$attribs = JArrayHelper::toString($attribs);
			$attribs =  substr("$attribs", 3); // Bugfix
		}
		////static::_('bootstrap.tooltip');
		// Format value when not '0000-00-00 00:00:00', otherwise blank it as it would result in 1970-01-01.
		if ((int) $value)
		{
			$tz = date_default_timezone_get();
			date_default_timezone_set('UTC');
			$inputvalue = strftime($format, strtotime($value));
			date_default_timezone_set($tz);
		}
		else
		{
			$inputvalue = '';
		}

		// Load the calendar behavior
		JHTML::_('behavior.calendar');

		// Only display the triggers once for each control.
		if (!in_array($id, $done))
		{
		$document = JFactory::getDocument();
		$document->addScriptDeclaration(
		'jQuery(document).ready(function($) {Calendar.setup({
		// Id of the input field
		inputField: "' . $id . '",
		// Format of the input field
		ifFormat: "' . $format . '",
		// Trigger for the calendar (button ID)
		button: "' . $id . '_img",
		// Alignment (defaults to "Bl")
		align: "Tl",
		singleClick: true,
		firstDay: ' . JFactory::getLanguage()->getFirstDay() . '
		});});'
		);
		$done[] = $id;
		}

		// Hide button using inline styles for readonly/disabled fields
		$btn_style	= ($readonly || $disabled) ? ' style="display:none;"' : '';
		$div_class	= (!$readonly && !$disabled) ? ' class="input-append"' : '';

		return '<div' . $div_class . '>'
				. '<input type="text" title="' . (0 !== (int) $value ? JHTML::_('date', $value, null, null) : '')
				. '" name="' . $name . '" id="' . $id . '" value="' . htmlspecialchars($inputvalue, ENT_COMPAT, 'UTF-8') . '" ' . $attribs . ' />'
				. '<button type="button" class="btn" id="' . $id . '_img"' . $btn_style . '><i class="icon-calendar"></i></button>'
			. '</div>';
	}
		
    public function article($cid) {

		// Check for request forgeries
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
		// Get items to remove from the request.
		if (!$cid) : $cid = JFactory::getApplication()->input->get('cid', array(), 'array'); endif;

		if (!is_array($cid) || count($cid) < 1)
		{
			JLog::add(JText::_($this->text_prefix . '_NO_ITEM_SELECTED'), JLog::WARNING, 'jerror');
		}
		else
		{
		//$model = $this->getModel();
		$params = JComponentHelper::getParams('com_einsatzkomponente');
			// Make sure the item ids are integers
			jimport('joomla.utilities.arrayhelper');
			JArrayHelper::toInteger($cid);
			
		foreach ($cid as $key => $val) {
			
		$query = 'SELECT * FROM `#__eiko_einsatzberichte` WHERE `id` = "'.$val.'" and state ="1" LIMIT 1';
		$db = JFactory::getDBO();
		$db->setQuery($query);
		$result = $db->loadObjectList();

		$kat	= EinsatzkomponenteHelper::getTickerKat ($result[0]->tickerkat); 
		
		$db = JFactory::getDbo();
		$db->setQuery('SELECT MAX(asset_id) FROM #__content');
		$max = $db->loadResult();
		$asset_id = $max+1;

		$link = JRoute::_( JURI::root() . 'index.php?option=com_einsatzkomponente&view=einsatzbericht&id='.$result[0]->id).'&Itemid='.$params->get('homelink','');
		
		$image_intro = str_replace('/', '\/', $result[0]->image);
		$image_intro = $db->escape($image_intro);
		if (str_replace('\/com_einsatzkomponente\/einsatzbilder\/thumbs', '', $image_intro)):
		$image_fulltext = str_replace('\/thumbs', '', $image_intro);
		endif;
		
		$user = JFactory::getUser(); 
			
		$db = JFactory::getDbo();
		$query = $db->getQuery(true); // !important, true for every new query
		
		$query->insert('#__content'); // #__table_name = databse prefix + table name
		$query->set('`id`=NULL');
		$query->set('`asset_id`="'.$asset_id.'"');
		$query->set('`title`="'.$result[0]->summary.'"');
		
		$alias = strtolower($result[0]->summary);
		$alias = str_replace(" ", "-", $alias).'_'.date("Y-m-d", strtotime($result[0]->date1));
		$query->set('`alias`="'.$alias.'"');
		
		$intro = $result[0]->desc;
		$intro = preg_replace("#(?<=.{".$params->get('article_max_intro','400')."}?\\b)(.*)#is", " ...", $intro, 1);
		$query->set('`introtext`="'.$db->escape($intro).'"');
		
		if ($params->get('article_orgas','1')) :	
					$data = array();
					foreach(explode(',',$result[0]->auswahl_orga) as $value):
						$db = JFactory::getDbo();
						$sql	= $db->getQuery(true);
						$sql
							->select('name')
							->from('`#__eiko_organisationen`')
							->where('id = "' .$value.'"');
						$db->setQuery($sql);
						$results = $db->loadObjectList();
						if(count($results)){
							$data[] = ''.$results[0]->name.''; 
						}
					endforeach;
					$auswahl_orga=  implode(',',$data); 

					$orgas 		 = str_replace(",", " +++ ", $auswahl_orga);
		$orgas       = '<br/><div class=\"eiko_article_orga\">Eingesetzte Kräfte: '.$orgas.'</div>';
		$query->set('`fulltext`="'.$db->escape($result[0]->desc).$orgas.'"');
		else:
		$query->set('`fulltext`="'.$db->escape($result[0]->desc).'"');
		endif;
		
		$query->set('`state`="1"');
		$query->set('`catid`="'.$params->get('article_category','0').'"');
		$query->set('`created`="'.date("Y-m-d H:i:s", strtotime($result[0]->date1)).'"');
		$query->set('`created_by`="'.$user->id.'"');
		$query->set('`created_by_alias`=""');
		$query->set('`modified`=""');
		$query->set('`modified_by`="'.$user->id.'"');
		$query->set('`checked_out`="0"');
		$query->set('`checked_out_time`="0000-00-00 00:00:00.000000"');
		$query->set('`publish_up`="'.date("Y-m-d H:i:s", strtotime($result[0]->date1)).'"'); 
		$query->set('`publish_down`="0000-00-00 00:00:00.000000"');
		$query->set('`images`="{\"image_intro\":\"'.$image_intro.'\",\"float_intro\":\"\",\"image_intro_alt\":\"\",\"image_intro_caption\":\"\",\"image_fulltext\":\"'.$image_fulltext.'\",\"float_fulltext\":\"\",\"image_fulltext_alt\":\"\",\"image_fulltext_caption\":\"\"}"');
		$query->set('`urls`="{\"urla\":\"'.$link.'\",\"urlatext\":\"Weitere Informationen über diesen Einsatz im Detailbericht\",\"targeta\":\"\",\"urlb\":\"'.$result[0]->presse.'\",\"urlbtext\":\"'.$result[0]->presse_label.'\",\"targetb\":\"\",\"urlc\":\"'.$result[0]->presse2.'\",\"urlctext\":\"'.$result[0]->presse2_label.'\",\"targetc\":\"\"}"');
		$query->set('`attribs`="{\"show_title\":"",\"link_titles\":"",\"show_tags\":"",\"show_intro\":"",\"info_block_position\":"",\"show_category\":"",\"link_category\":"",\"show_parent_category\":"",\"link_parent_category\":"",\"show_author\":"",\"link_author\":"",\"show_create_date\":"",\"show_modify_date\":"",\"show_publish_date\":"",\"show_item_navigation\":"",\"show_icons\":"",\"show_print_icon\":"",\"show_email_icon\":"",\"show_vote\":"",\"show_hits\":"",\"show_noauth\":"",\"urls_position\":"",\"alternative_readmore\":"",\"article_layout\":"",\"show_publishing_options\":"",\"show_article_options\":"",\"show_urls_images_backend\":"",\"show_urls_images_frontend\":""}"');
		$query->set('`version`="1"');
		$query->set('`ordering`="0"');
		$query->set('`metakey`="'.$auswahl_orga.','.$params->get('article_meta_key','feuerwehr,einsatzbericht,unfall,feuer,hilfeleistung,polizei,thw,rettungsdienst,hilfsorganisation').',einsatzkomponente"');
		$query->set('`metadesc`="'.$params->get('article_meta_desc','Einsatzbericht').'"');
		$query->set('`access`="1"');
		$query->set('`hits`="0"');
		$query->set('`metadata`="{\"robots\":\"\",\"author\":\"'.$user->username.'\",\"rights\":\"\",\"xreference\":\"\"}"');
		$query->set('`featured`="1"');
		$query->set('`language`="*"');
		$query->set('`xreference`=""');
		/* or something like this:
		$query->columns('`1`,`2`,`3`');
		$query->values('"one","two","three"');
		*/
		
		$db->setQuery($query);
	try {
	// Execute the query in Joomla 3.0.
		$db->execute();
	} catch (Exception $e) {
	//print the errors
	print_r ($e).'';
	}
		$content_id = $db->insertId();
		// Joomla-Artikel Id in Einsatzbericht eintragen 
		$query = "UPDATE `#__eiko_einsatzberichte` SET `article_id` = '".$content_id."' WHERE `id` = '".$result[0]->id."'";
		$db = JFactory::getDBO();
		$db->setQuery($query);
		$db->query();
		
		if ($params->get('article_frontpage','1')) :	
		// Artikel als Haupteintrag-Eintrag markieren 
		$frontpage_query = "INSERT INTO `#__content_frontpage` SET `content_id`='".$content_id."'";
		$db = JFactory::getDBO();
		$db->setQuery($frontpage_query);
		$db->query();
		endif;
				
				}
				$msg    = count($cid).' Artikel erstellt';  
				}
				return $msg; 
			}

    static function module ($pos) {
		$document = JFactory::getDocument();
		$renderer = $document->loadRenderer( 'modules' );
		$options = array( 'style' => 'xhtml' );
		return $renderer->render( $pos, $options, null);  
    }
	public function pdf($cid)
     	{
	     	require_once JPATH_COMPONENT.'/helpers/fpdf.php';
		//$model = $this->getModel();
		$params = JComponentHelper::getParams('com_einsatzkomponente');
		// Make sure the item ids are integers
		jimport('joomla.utilities.arrayhelper');
		JArrayHelper::toInteger($cid);
		
		foreach ($cid as $key => $rep_id) {
			$db = JFactory::getDBO();
			$query = 	"SELECT eb.id as id, eb.counter as counter, aa.title as alarmart, tk.title as einsatzkat, 
					  ea.title as einsatzart, eb.address as ort, eb.date1 as startd, eb.date2 as fahrd, 
					  eb.date3 as endd, eb.boss as el, eb.boss2 as ef, eb.people as pers, eb.auswahl_orga as orgas, 
					  eb.vehicles as fahrz, eb.ausruestung as ausruest, eb.summary as kurzt, eb.desc as langt 
					FROM #__eiko_einsatzberichte eb 
					INNER JOIN #__eiko_einsatzarten ea ON ea.id = eb.data1 
					INNER JOIN #__eiko_alarmierungsarten aa ON aa.id = eb.alerting
					INNER JOIN #__eiko_tickerkat tk ON tk.id = eb.tickerkat
					WHERE eb.id = ".$rep_id;
			$db->setQuery($query);
			$einsatz = $db->loadObjectList();
			
			//Varaible für Organisationen
			$orgas = $einsatz[0]->orgas;
			
			//Prüfe auf Kommas am Anfang und Ende (Workaround für einen Updatefehler, wo Komponentenzuordnungen entfernt wurden)
				$lastchar = substr($orgas, -1, 1);
				$firstchar = substr($orgas, 0, 1);
				if ($lastchar == ",")
				{
					$orgas = substr($orgas, 0, -1);
					if ($firstchar == ",")
						$orgas = substr($orgas, 1);
				}
				//Ersetze doppelte Kommas durch ein einzelnes
				$orgas = preg_replace('/,,+/', ',', $orgas);
				$orgas .= $rep_id;
			
			//Variable für Fahrzeuge
			$fahrzeuge = $einsatz[0]->fahrz;
			
			//Prüfe auf Kommas am Anfang und Ende (Workaround für einen Updatefehler, wo Komponentenzuordnungen entfernt wurden)
				$lastchar = substr($fahrzeuge, -1, 1);
				$firstchar = substr($fahrzeuge, 0, 1);
				if ($lastchar == ",")
				{
					$fahrzeuge = substr($fahrzeuge, 0, -1);
					if ($firstchar == ",")
						$fahrzeuge = substr($fahrzeuge, 1);
				}
				//Ersetze doppelte Kommas durch ein einzelnes
				$fahrzeuge = preg_replace('/,,+/', ',', $fahrzeuge);
				
			if ($fahrzeuge != null && $fahrzeuge >= 0 && $fahrzeuge != "") {
				$query = "SELECT name FROM #__eiko_fahrzeuge WHERE id IN (".$fahrzeuge.")";
				$db->setQuery($query);
				$db->execute();
				$anz_fahrz = $db->getNumRows();
				$fahrz_arr = $db->loadObjectList();
				$fahrz_all = "";
				$i = 0;
				foreach ($fahrz_arr as $key => $value) {
				    if ($i == 0)
				    	$fahrz_all .= "";
				    else
				    	$fahrz_all .= " ";
				    $fahrz_all .= $value->name.",";
				    $i += 1;
				}
				//Entferne das Komma am Ende
				$fahrz_all = substr($fahrz_all, 0, -1);
			}
			
			if ($fahrzeuge != null && $fahrzeuge >= 0 && $fahrzeuge != "") {
				$query = "SELECT name FROM #__eiko_organisationen WHERE id IN (".$orgas.")";
				$db->setQuery($query);
				$db->execute();
				$anz_orgas = $db->getNumRows();
				$orga_arr = $db->loadObjectList();
				$orgas_all = "";
				$i = 0;
				foreach ($orga_arr as $key => $value) {
				    if ($i == 0)
				    	$orgas_all .= "";
				    else
				    	$orgas_all .= " ";
				    $orgas_all .= $value->name.",";
				    $i += 1;
				}
				$i = 0;
				//Entferne das Komma am Ende
				$orgas_all = substr($orgas_all, 0, -1);
			}
			
			//Variablendeklaraion für die PDF
			$id = $einsatz[0]->id;
			$counter = $einsatz[0]->counter;
			$alarmart = $einsatz[0]->alarmart;
			$einsatzkat = $einsatz[0]->einsatzkat;
			$einsatzart = $einsatz[0]->einsatzart;
			$ort = $einsatz[0]->ort;
			$beginn = $einsatz[0]->startd;
			$ausrueck = $einsatz[0]->fahrd;
			$ende = $einsatz[0]->endd;
			$einsatzleiter = $einsatz[0]->el;
			$einsatzfuehrer = $einsatz[0]->ef;
			$mannschaft = $einsatz[0]->pers;
			//Ausrüstung noch nicht implementiert
			$ausruest = $ausruest_all;
			$kurzbericht = $einsatz[0]->kurzt;
			$bericht = $einsatz[0]->langt;
			$organisationen = $orgas_all;
			$fahrzeuge = $fahrz_all;
			$bericht = strip_tags($bericht);
			
		     	$params = JComponentHelper::getParams('com_einsatzkomponente');
		     	
		     	//Hier wird das PDF-Grundgerüst erstellt
			$pdf=new FPDF('P','mm','A4');
			
			//Definiere die Breite und Höhe der Beschriftungszellen:
			$breite_beschriftung = 45;
			$hoehe = 8;
			
			//Breite des Inhalts. 0 = bis zum rechten Seitenrand
			$breite_inhalt = 10;
			
			//Neue Seite wird eingefügt
			$pdf->AddPage();
			
			//Schriftart und -größe wird definiert 
			$pdf->SetFont('Arial','',10);
			
			//Header-Image
			if (!$params->get('pdf_header') == '') {
				$img = "../media/com_einsatzkomponente/images/pdf/".$params->get('pdf_header');
				list($width, $height) = $pdf->resizeToFit($img);
				$pdf->resizeImage($img,0,0);
				//Setze Abstand von der Oberkante des Blatts die der Höhe des Bilds entspricht
				$pdf->Ln($height);
			}
			//Erstelle die Zellen
			if ($params->get('pdf_show_id') == 1) {
				$pdf->SetFont('Arial','',8);
				$pdf->Cell($breite_beschriftung,$hoehe,utf8_decode(JText::_('COM_EINSATZKOMPONENTE_LEGEND_EINSATZBERICHT').'-'.JText::_('COM_EINSATZKOMPONENTE_FORM_LBL_EINSATZBERICHT_ID').':'));
				$pdf->Cell($breite_inhalt,$hoehe,$id,0,1);
				$pdf->SetFont('Arial','',10);
			}
			if ($params->get('pdf_show_counter') == 1) {
				$pdf->SetFont('Arial','',8);
				$pdf->Cell($breite_beschriftung,$hoehe,utf8_decode(JText::_('WEB-Zugriffe').':'));
				$pdf->Cell($breite_inhalt,$hoehe,$counter,0,1);
				$pdf->SetFont('Arial','',10);
			}
			if ($params->get('pdf_show_kurzbericht') == 1) {
				if ($kurzbericht) {
				$pdf->SetFont('Arial','',14);
				$pdf->Cell($breite_beschriftung,$hoehe,utf8_decode(JText::_('COM_EINSATZKOMPONENTE_FORM_LBL_EINSATZBERICHT_SUMMARY').':'));
				$pdf->Cell($breite_inhalt,$hoehe,utf8_decode($kurzbericht),0,1);
				$pdf->SetFont('Arial','',10);
			}}
			if ($params->get('pdf_show_alarmart') == 1) {
				$pdf->Cell($breite_beschriftung,$hoehe,utf8_decode(JText::_('COM_EINSATZKOMPONENTE_FORM_LBL_EINSATZBERICHT_ALERTING').':'));
				$pdf->Cell($breite_inhalt,$hoehe,utf8_decode($alarmart),0,1);
			}
			if ($params->get('pdf_show_einsatzart') == 1) {
				$pdf->Cell($breite_beschriftung,$hoehe,utf8_decode(JText::_('COM_EINSATZKOMPONENTE_FORM_LBL_EINSATZBERICHT_DATA1').':'));
				$pdf->Cell($breite_inhalt,$hoehe,utf8_decode($einsatzart),0,1);
			}
			if ($params->get('pdf_show_einsatzkat') == 1) {
				$pdf->Cell($breite_beschriftung,$hoehe,utf8_decode(JText::_('COM_EINSATZKOMPONENTE_FORM_LBL_EINSATZBERICHT_KATEGORIE').':'));
				$pdf->Cell($breite_inhalt,$hoehe,utf8_decode($einsatzkat),0,1);
			}
			if ($params->get('pdf_show_ort') == 1) {
				$pdf->Cell($breite_beschriftung,$hoehe,utf8_decode(JText::_('COM_EINSATZKOMPONENTE_FORM_LBL_EINSATZBERICHT_ADDRESS').':'));
				$pdf->Cell($breite_inhalt,$hoehe,utf8_decode($ort),0,1);
			}
			if ($params->get('pdf_show_alarmzeit') == 1) {
				$pdf->Cell($breite_beschriftung,$hoehe,JText::_('COM_EINSATZKOMPONENTE_FORM_LBL_EINSATZBERICHT_TIMESTART').':');
				$pdf->Cell($breite_inhalt,$hoehe,$beginn,0,1);
			}
			if ($params->get('pdf_show_ausfahrzeit') == 1 AND $ausrueck != "0000-00-00 00:00:00") {
				$pdf->Cell($breite_beschriftung,$hoehe,JText::_('COM_EINSATZKOMPONENTE_FORM_LBL_EINSATZBERICHT_DATE2').':');
				$pdf->Cell($breite_inhalt,$hoehe,$ausrueck,0,1);
			}
			if ($params->get('pdf_show_einsatzende') == 1 AND $ende != "0000-00-00 00:00:00") {
				$pdf->Cell($breite_beschriftung,$hoehe,JText::_('COM_EINSATZKOMPONENTE_FORM_LBL_EINSATZBERICHT_TIMEEND').':');
				$pdf->Cell($breite_inhalt,$hoehe,$ende,0,1);
			}
			if ($params->get('pdf_show_einsatzleiter') == 1) {
				if ($einsatzleiter) {
				$pdf->Cell($breite_beschriftung,$hoehe,utf8_decode(JText::_('COM_EINSATZKOMPONENTE_FORM_LBL_EINSATZBERICHT_BOSS').':'));
				$pdf->Cell($breite_inhalt,$hoehe,utf8_decode($einsatzleiter),0,1);
			}}
			if ($params->get('pdf_show_einsatzfuehrer') == 1) {
				if ($einsatzführer) {
				$pdf->Cell($breite_beschriftung,$hoehe,utf8_decode(JText::_('COM_EINSATZKOMPONENTE_FORM_LBL_EINSATZBERICHT_BOSS2').':'));
				$pdf->Cell($breite_inhalt,$hoehe,utf8_decode($einsatzführer),0,1);
			}}
			if ($params->get('pdf_show_mannschaft') == 1) {
				if ($mannschaft) {
				$pdf->Cell($breite_beschriftung,$hoehe,utf8_decode(JText::_('COM_EINSATZKOMPONENTE_FORM_LBL_EINSATZBERICHT_PEOPLE').':'));
				$pdf->Cell($breite_inhalt,$hoehe,$mannschaft,0,1);
			}}
			
			if ($params->get('pdf_show_orgas') == 1) {
				if ($organisationen) {
				$pdf->Cell($breite_beschriftung,$hoehe,utf8_decode(JText::_('COM_EINSATZKOMPONENTE_FORM_LBL_EINSATZBERICHT_AUSWAHLORGA').':'));
				$pdf->Cell($breite_inhalt,$hoehe,utf8_decode($organisationen),0,1);
			}}
			
			if ($params->get('pdf_show_fahrzeuge') == 1) {
				if ($fahrzeuge) {
				$pdf->Cell($breite_beschriftung,$hoehe,utf8_decode(JText::_('COM_EINSATZKOMPONENTE_FORM_LBL_EINSATZBERICHT_VEHICLES').':'));
				$pdf->Cell($breite_inhalt,$hoehe,utf8_decode($fahrzeuge),0,1);
			}}
			
			if ($params->get('pdf_show_ausruestung') == 1) {
				if ($ausruest) {
				$pdf->Cell($breite_beschriftung,$hoehe,utf8_decode(JText::_('COM_EINSATZKOMPONENTE_FORM_LBL_EINSATZBERICHT_AUSRUESTUNG').':'));
				$pdf->Cell($breite_inhalt,$hoehe,utf8_decode($ausruest),0,1);
			}}
			
			
			if ($params->get('pdf_show_langbericht') == 1) {
				if ($bericht) {
				$pdf->Cell($breite_beschriftung,$hoehe,utf8_decode(JText::_('COM_EINSATZKOMPONENTE_FORM_LBL_EINSATZBERICHT_DESC').':'));
				$pdf->MultiCell(150,$hoehe,utf8_decode($bericht),0,1);
			}}
			
			
			//prüfe Pfadangabe auf "/" am Ende und schneide dieses Zeichen ab wenn nötig
			$speicherort = $params->get('pdf_speicherort');
			if ($speicherort != '')
			{
			    $lastchar = substr($speicherort, -1, 1);
			    if ($lastchar == "/")
			    {
			    	$speicherort = substr($speicherort, 0, -1);
			    }
			    $path = '../'.$speicherort;
			}
			
		if (!file_exists($path)) {
		if(mkdir($path, 0755, true)) {
		}}

			//Gebe PDF in definiertes Verzeichnis aus und benenne sie mit der Einsatz-ID
			$pdfname = 'einsatzbericht_id'.$id.'.pdf';
			$pdf->Output($path.'/'.$pdfname,'F');
		}
		//Nachricht bei Erfolg
		if (count($cid) == 1)
			$msg = count($cid).JText::_(' Einsatz wurden in den Ordner "'.$speicherort.'" exportiert.' );
		else
			$msg = count($cid).JText::_(' Einsätze wurden in den Ordner "'.$speicherort.'" exportiert.' );
		
		return $msg;
	}
}
