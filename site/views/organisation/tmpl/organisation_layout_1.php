<?php
/**
 * @version     3.0.0
 * @package     com_einsatzkomponente
 * @copyright   Copyright (C) by Ralf Meyer 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ralf Meyer <webmaster@feuerwehr-veenhusen.de> - http://einsatzkomponente.de
 */
// no direct access
defined('_JEXEC') or die;

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_einsatzkomponente/assets/css/einsatzkomponente.css');

//Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_einsatzkomponente', JPATH_ADMINISTRATOR);

//print_r ($this->orga_fahrzeuge);
?>
<input type="button" class="btn eiko_back_button" value="Zurück" onClick="history.back();">

<?php if( $this->item ) : ?>
    <div class="container-fluid"> 
<table class="organisation_box_1" cellpadding="2">
<tbody>

<tr class="organisation_box_4">
<th class="organisation_box_6" colspan="2">
<span class="organisation_box_title"><?php echo $this->item->name; ?></span><br/>
<span class="organisation_box_detail1"><?php echo $this->item->detail1; ?></span> 
</th>
</tr>

<tr class="organisation_box_3">
<td colspan="2" align="center">
			<?php if ($this->params->get('gmap_action','0') == '1') :?>
  			<div id="map-canvas" style="width:100%;max-width:400px;height:250px;border:1px solid;">
    		<noscript>Dieser Teil der Seite erfordert die JavaScript Unterstützung Ihres Browsers!</noscript>
			</div>
            <?php endif;?>
			<?php if ($this->params->get('gmap_action','0') == '2') :?>
				<body onLoad="drawmap();">
				<!--<div id="descriptionToggle" onClick="toggleInfo()">Informationen zur Karte anzeigen</div>
				<div id="description" class="">Einsatzkarte</div>-->
   				<div id="map" style="width:100%;max-width:400px;height:250px;border:1px solid;"></div> 
    		<noscript>Dieser Teil der Seite erfordert die JavaScript Unterstützung Ihres Browsers!</noscript>
            <?php endif;?>
</td>
</tr>
<tr class="organisation_box_5"><th class="organisation_box_2"  colspan="2">weitere Informationen</th></tr>

<?php if (isset($this->item->department)) : ?>
<tr class="organisation_box_3">
<td><strong>Organisation:</strong></td>
<td><?php echo $this->item->department; ?></td> 
</tr>
<?php endif; ?>
<?php if ($this->item->detail2) : ?>
<tr class="organisation_box_3">
<td><strong><?php echo $this->item->detail2_label; ?>:</strong></td>
<td><?php echo $this->item->detail2; ?></td> 
</tr>
<?php endif; ?>
<?php if ($this->item->detail3) : ?>
<tr class="organisation_box_3"> 
<td><strong><?php echo $this->item->detail3_label; ?>:</strong></td>
<td><?php echo $this->item->detail3; ?></td>
</tr>
<?php endif; ?>
<?php if ($this->item->detail4) : ?>
<tr class="organisation_box_3">
<td><strong><?php echo $this->item->detail4_label; ?>:</strong></td>
<td><?php echo $this->item->detail4; ?></td>
</tr>
<?php endif; ?>
<?php if ($this->item->detail5) : ?>
<tr class="organisation_box_3">
<td><strong><?php echo $this->item->detail5_label; ?>:</strong></td>
<td><?php echo $this->item->detail5; ?></td>
</tr>
<?php endif; ?>
<?php if ($this->item->detail6) : ?>
<tr class="organisation_box_3">
<td><strong><?php echo $this->item->detail6_label; ?>:</strong></td>
<td><?php echo $this->item->detail6; ?></td>
</tr>
<?php endif; ?>
<?php if ($this->item->detail7) : ?>
<tr class="organisation_box_3">
<td><strong><?php echo $this->item->detail7_label; ?>:</strong></td>
<td><?php echo $this->item->detail7; ?></td>
</tr>
<?php endif; ?>



<?php // letzter Einsatz   BUG : Problem bei der Suche nach ID z.B. 1 -> 11
//$database			= JFactory::getDBO();
//$query = 'SELECT * FROM #__eiko_einsatzberichte WHERE department LIKE "%'.$this->item->departm.'%" AND state="1" ORDER BY date1 DESC' ;
//$database->setQuery( $query );
//$total = $database->loadObjectList();
?>
<!--<?php if ($total) : ?>
<tr class="organisation_box_3">
<td><strong>Der letzte gemeinsame Einsatz:</strong></td>
<td><?php echo $total[0]->date1; ?></td>
</tr>
<?php endif; ?>
<tr height="10px"></tr>-->


<?php if ($this->item->link) : ?>
<tr class="organisation_box_3">
<td><strong>Link zur Homepage:</strong></td>
<td><?php echo '<a target="_blank" href="'.$this->item->link.'">'.$this->item->link.'</a> '; ?></td>
</tr>
<?php endif; ?>

<tr class="organisation_box_3">
<td><strong>Fahrzeuge:</strong></td>
<td>
<?php
				$array = array();
				foreach((array)$this->orga_fahrzeuge as $value): 
					if(!is_array($value)):
						$array[] = $value;
					endif;
				endforeach; ?>
                <?php foreach($array as $value): ?>
				<?php if ($value->state == '2'): $value->name = $value->name.' (a.D.)';endif;?>
				
		<?php if ($this->params->get('display_orga_fhz_links','1')) :?>
					<?php if (!$value->link) :?>
					<a title ="<?php echo $value->detail2;?>" target="_self" href="<?php echo JRoute::_('index.php?option=com_einsatzkomponente&view=einsatzfahrzeug&id=' . $value->id); ?>"><?php echo $value->name; ?></a>
					<?php else :?>
					<a title ="<?php echo $value->detail2;?>" target="_blank" href="<?php echo $value->link; ?>"><?php echo $value->name; ?></a>
					<?php endif; ?>		
                    <?php else: ?>	
                    <?php echo $value->name; ?>		
					<?php endif; ?>
<br/>				
				<?php endforeach; ?>
</td>
</tr>


</tbody>
</table>

<!--<h4><span><?php echo $this->item->detail1; ?> <?php echo $this->item->name; ?></span></h4>-->
<!--Einsatzbericht anzeigen mit Plugin-Support-->           
<?php jimport('joomla.html.content'); ?>  
<?php $Desc = JHTML::_('content.prepare', $this->item->desc); ?>
<?php echo $Desc; ?>
    </div>
<?php else: ?>
    Could not load the item
<?php endif; ?>


<?php if ($this->params->get('gmap_action','0')) :?>
              <!-- Javascript für GMap-Anzeige -->
              <script type="text/javascript" src="http://maps.google.com/maps/api/js?v=3&sensor=false"></script> 
              <script type="text/javascript"> 
                    var map = null;
                    var marker = null;
                    var marker2 = null;
                    var geocoder;
                    
              function codeAddress() {
                  var address = document.getElementById("address").value;
                  geocoder.geocode( { 'address': address}, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                      map.setCenter(results[0].geometry.location);
                      var marker = new google.maps.Marker({
                          map: map, 
                          position: results[0].geometry.location
                      });
                      latLng = marker.getPosition();
                          document.getElementById("jform_gmap_latitude").value=latLng.lat();
                          document.getElementById("jform_gmap_longitude").value=latLng.lng();
                    } else {
                      alert("Geocode war nicht erfolgreich aus folgendem Grund: " + status);
                    }
                  });
                }	  
               
              // A function to create the marker and set up the event window function 
              function createMarker(latlng, name, html) {
                  var contentString = html;
                  var marker = new google.maps.Marker({
                      position: latlng,
                      map: map,
                      draggable: true,
                      zIndex: Math.round(latlng.lat()*-100000)<<5
                      });
                google.maps.event.addListener(marker, 'dragend', function() {
                  latLng = marker.getPosition();
                          document.getElementById("jform_gmap_latitude").value=latLng.lat();
                          document.getElementById("jform_gmap_longitude").value=latLng.lng();
                      });
                   google.maps.event.addListener(marker, 'click', function() {
                    latLng = marker.getPosition();
                          document.getElementById("jform_gmap_latitude").value=latLng.lat();
                          document.getElementById("jform_gmap_longitude").value=latLng.lng();
                      });
                  google.maps.event.trigger(marker, 'click');    
                  return marker;
              }
               
              function updateInfoWindow () {
              }
               
              function initialize() {
                // create the map
                geocoder = new google.maps.Geocoder();
                var myOptions = {
                  zoom: <?php echo $gmap_config->gmap_zoom_level; ?>,
                  center: new google.maps.LatLng(<?php echo $gmap_latitude; ?>,<?php echo $gmap_longitude; ?>), 
                  mapTypeControl: true,
                    scrollwheel: false,
                    disableDoubleClickZoom: true,
                    streetViewControl: false,
                    keyboardShortcuts: false,
                    mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU},
                    navigationControl: true,
                    navigationControlOptions: {style: google.maps.NavigationControlStyle.SMALL},
                  mapTypeId: google.maps.MapTypeId.ROADMAP
                }
                map = new google.maps.Map(document.getElementById("map_canvas"),
                                              myOptions);
              var marker2 = new google.maps.Marker({
                      position: new google.maps.LatLng(<?php echo $gmap_latitude; ?>,<?php echo $gmap_longitude; ?>), 
                      map: map,
                      draggable: true,
                      title:""
                  });
                  google.maps.event.addListener(map, 'click', function(event) {
                  //call function to create marker
                       if (marker) {
                          marker.setMap(null);
                          marker = null;
                          marker2 = null;
                       }
                       if (marker2) {
                          marker2.setMap(null);
                          marker2 = null;
                       }
                   marker = createMarker(event.latLng, "name", "<b>Location</b><br>"+event.latLng);
                });
              }
                  
              // Onload handler to fire off the app.
              google.maps.event.addDomListener(window, 'load', initialize);
              </script>
              <!-- Javascript für GMap-Anzeige ENDE -->
<?php endif; ?>
