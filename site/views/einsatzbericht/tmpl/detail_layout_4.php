<?php
/**
 * @version     3.0.0
 * @package     com_einsatzkomponente
 * @copyright   Copyright (C) 2013 by Ralf Meyer. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ralf Meyer <webmaster@feuerwehr-veenhusen.de> - http://einsatzkomponente.de
 */

// no direct access
defined('_JEXEC') or die;  
require_once JPATH_SITE.'/administrator/components/com_einsatzkomponente/helpers/einsatzkomponente.php'; // Helper-class laden


$vehicles_images = '';
//Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_einsatzkomponente', JPATH_ADMINISTRATOR);
?>

<?php if( $this->item ) : ?>  <!--Einsatzdaten vorhanden ? Sonst ENDE --> 


<table class="table" border="0" style="border:hidden;max-width:700px;">
  <tr>
    <td class="layout4_row_1" style="border:hidden;">
    <!--Navigation-->
	<div class="eiko_navbar_2 " style="left"><?php echo $this->navbar;?></div>
    <!--Navigation ENDE-->
    </td>
    <!--<td class="layout4_row_2" style="border:hidden;">
    <span style="float:right;">Dieser Bericht wurde <?php echo $this->item->counter; ?> mal gelesen</span>
    </td>-->
  </tr>
  
  <tr>
   <td class="layout4_row_3" style="border:hidden;"></td>
   <td class="layout4_row_4" style="border:hidden;">
    <!--Socialbar-->
    <?php if($this->item->status_fb=='1'): ?>
    <span class="eiko_socialbar_2" style="float:right;"><?php echo $this->social;?></span> 
    <?php endif; ?>
    <!--Socialbar ENDE-->
    </td>
  </tr>
</table>

<table class="table table-bordered" border="1" style="width:100%;">  
 <?php if ($this->einsatzlogo->beschr) :?>
  <tr>
    <td class="layout4_row_5" colspan="2" style="text-align:center;">
    <img src="<?php echo JURI::Root();?><?php echo $this->einsatzlogo->beschr;?>"  alt="<?php echo $this->einsatzlogo->title;?>" title="<?php echo $this->einsatzlogo->title;?>" style="width:99%;margin:5px;"/>
    </td>
  </tr>
<?php endif;?>

  <tr style="padding:10px;">
    <td class="layout4_row_6" width="250px">Einsatzart:</td>
    <td class="layout4_row_6" style="font-weight:bold;"><?php echo $this->einsatzlogo->title; ?></td>
  </tr>
  
  <tr>
    <td class="layout4_row_7" width="250px">Kurzbericht:</td>
    <td class="layout4_row_7"><?php echo $this->item->summary; ?></td>
  </tr>
  
  <tr>
    <td class="layout4_row_8" width="250px">Einsatzort:</td>
    <td class="layout4_row_8"><?php echo $this->item->address.''; ?></td>
  </tr>

  <tr>
    <td class="layout4_row_9" width="250px">Alarmierung:</td>
    <td class="layout4_row_9">
    Alarmierung per
<?php if($this->alarmierungsart->image)	:?>
    <img src="<?php echo JURI::Root();?><?php echo $this->alarmierungsart->image;?>"  alt="<?php echo $this->alarmierungsart->title;?>" title="<?php echo $this->alarmierungsart->title;?>" width="32px"/><br/>
<?php endif;?>
<?php if(!$this->alarmierungsart->image)	:?>
    <?php echo $this->alarmierungsart->title;?><br/>
<?php endif;?>

	<?php
		$wochentage = explode(',','Sonntag,Montag,Dienstag,Mittwoch,Donnerstag,Freitag,Samstag');
		$date2 = $wochentage[date('w', strtotime($this->item->date1))];			   		
	?>
	
    am <?php echo $date2.', '.date("d.m.Y", strtotime($this->item->date1)).''; ?>, um <?php echo date("H:i", strtotime($this->item->date1)).' Uhr'; ?>
    </td>
  </tr>

 <?php if( $this->item->date3>1) : ?>
  <tr>
    <td class="layout4_row_100" width="250px">Einsatzdauer:</td>
    <td class="layout4_row_100"><?php ?>
		<?php 
			$diff =  strtotime($this->item->date3)- strtotime($this->item->date1);
            $diff = $diff/60;
			
			if ($diff<60) {	
				if ($diff == 0) {
					echo 'k.A.';
				}
				else {
					echo $diff.' Min.';
				}
			}
			else {
				$diffDate = strtotime($this->item->date3)- strtotime($this->item->date1);

				$days = floor($diffDate / 24 / 60 / 60 ); // Anzahl Tage = Sekunden /24/60/60
				$diffDate = $diffDate - ($days*24*60*60); // den verbleibenden Rest berechnen = Stunden
				$hours = floor($diffDate / 60 / 60); // den Stundenanteil herausrechnen
				$diffDate = ($diffDate - ($hours*60*60));
				$minutes = floor($diffDate/60); // den Minutenanteil
				$diffDate = $diffDate - ($minutes*60);
				$seconds = floor($diffDate); // die verbleibenden Sekunden
				
				if ($days>0) {
					echo $days.' Tag(e), ';
				}
				if ($minutes == 0 && $hours == 0) {
					echo 'k.A.';
				}
				else {
					echo $hours.' Std. und '.$minutes.' Min.';			
				}								
			}			
		?>	
	</td>
  </tr>  
 <?php endif;?>
 
  <tr>
    <td class="layout4_row_10" width="250px">Mannschaftsst&auml;rke:</td>
    <td class="layout4_row_10">
		<?php
			if ($this->item->people == 0) {
				echo 'k.A.';
			}
			else {
				echo $this->item->people;
			}
		?>
	</td>
  </tr>
  
  <tr>
    <td class="layout4_row_11" width="250px">Am Einsatz beteiligte Einheiten:</td>
    <td class="layout4_row_11">
            <?php if( $this->item->auswahl_orga ) : ?>   
            <div class="well well-small">
			<?php echo '<span style="font-weight: bold;"><u>'.JText::_('Alarmierte Organisationen').'</u></span>'; ?>:
			<?php
				$array = array();
				foreach((array)$this->item->auswahl_orga as $value): 
					if(!is_array($value)):
						$array[] = $value;
					endif;
				endforeach;
				$data = array();
				foreach($array as $value):
					$db = JFactory::getDbo();
					$query	= $db->getQuery(true);
					$query
						->select('*')
						->from('`#__eiko_organisationen`')
						->where('id = "' .$value.'"');
					$db->setQuery($query);
					$results = $db->loadObjectList();
					
					if ($this->params->get('display_detail_orga_links','1')) :
					if (!$results[0]->link) :
					$data[] = '<li><a href="'.JRoute::_('index.php?option=com_einsatzkomponente&view=organisation&id=' . $results[0]->id).'" target="_self" alt="'.$results[0]->link.'">'.$results[0]->name.'</a></li>';
					else:					
					$data[] = '<li style="margin:0;"><a href="'.$results[0]->link.'" target="_blank" alt="'.$results[0]->link.'">'.$results[0]->name.'</a></li>';
					endif;
					else:
					if ($results[0]->link) :
					$data[] = '<li style="margin:0;"><a href="'.$results[0]->link.'" target="_blank" alt="'.$results[0]->link.'">'.$results[0]->name.'</a></li>';
					else:
					$data[] = '<li style="margin:0;">'.$results[0]->name.'</li>';
					endif;
					endif;

										
				endforeach;
				$this->item->auswahl_orga = implode('',$data); ?>
				<br/><br/>
			<?php echo '<ul style="margin:0;">'.$this->item->auswahl_orga.'</ul>'; ?>
            <?php endif;?>
            <br/><br/>
            
            <?php if( $this->item->vehicles ) : ?>
			<?php
				$array = array();
				foreach((array)$this->item->vehicles as $value): 
					if(!is_array($value)):
						$array[] = $value;
					endif;
				endforeach;
				$data = array();
				$vehicles_images = array();
				$vehicles_list = array();
				foreach($array as $value):
					$db = JFactory::getDbo();
					$query	= $db->getQuery(true);
					$query
						->select('*')
						->from('`#__eiko_fahrzeuge`')
						->where('id = "' .$value.'"');
					$db->setQuery($query);
					$results = $db->loadObjectList();
					$data[] = $results[0]->name;
					if ($results[0]->state == '2'): $results[0]->name = $results[0]->name.' (a.D.)';endif;
					
					if ($this->params->get('display_detail_fhz_links','1')) :
					if (!$results[0]->link) :
					$vehicles_images[] = '<span style="margin-right:10px;background-color:#D8D8D8;white-space:nowrap;"><a href="'.JRoute::_('index.php?option=com_einsatzkomponente&view=einsatzfahrzeug&id=' . $results[0]->id).'" target="_self"><img width="90px" style="margin-top:15px;"  src="'.JURI::Root().$results[0]->image.'"  alt="'.$results[0]->name.'" title="'.$results[0]->name.'  '.$results[0]->detail2.'"/></a>&nbsp;&nbsp;<a href="'.JRoute::_('index.php?option=com_einsatzkomponente&view=einsatzfahrzeug&id=' . $results[0]->id).'" target="_self">'.$results[0]->name.'</a>  '.$results[0]->detail2.'</span>';					
					else:
					$vehicles_images[] = '<span style="margin-right:10px;background-color:#D8D8D8;white-space:nowrap;"><a href="'.$results[0]->link.'" target="_blank"><img width="90px" style="margin-top:15px;" src="'.JURI::Root().$results[0]->image.'"  alt="'.$results[0]->name.'" title="'.$results[0]->name.'  ('.$results[0]->detail2.')" /></a>&nbsp;&nbsp;<a href="'.$results[0]->link.'" target="_blank">&nbsp;&nbsp;'.$results[0]->name.'</a></span>';
					endif;
					else:
					
					if ($results[0]->link) :
					$vehicles_images[] = '<span style="margin-right:10px;background-color:#D8D8D8;white-space:nowrap;"><a href="'.$results[0]->link.'" target="_blank"><img width="90px" style="margin-top:15px;" src="'.JURI::Root().$results[0]->image.'"  alt="'.$results[0]->name.'" title="'.$results[0]->name.'  ('.$results[0]->detail2.')" /></a>&nbsp;&nbsp;<a href="'.$results[0]->link.'" target="_blank">&nbsp;&nbsp;'.$results[0]->name.'</a></span>';
					else:
					$vehicles_images[] = '<span style="margin-right:10px;background-color:#D8D8D8;white-space:nowrap;"><img width="90px" style="margin-top:15px;" src="'.JURI::Root().$results[0]->image.'"  alt="'.$results[0]->name.'" title="'.$results[0]->name.'  ('.$results[0]->detail2.')" />&nbsp;&nbsp;'.$results[0]->name.'</span>';
					endif;
					endif;

					endforeach;
				$this->item->vehicles = implode(', ',$data); 
				$vehicles_images = implode(' ',$vehicles_images); 
				$vehicles_list = implode('<br/>',$vehicles_list); ?>
            <?php endif;?>
            
            <?php if( $this->item->vehicles ) : ?>
			<?php echo '<span style="font-weight: bold;"><u>'.JText::_('Fahrzeuge am Einsatzort:').'</u></span>'; ?>:
			<?php if ($this->params->get('display_detail_fhz_images','1') and $this->item->vehicles) :?>
			<?php echo '<br/><br/>'.$vehicles_images;?> 
            <?php else:?>
			<?php echo '<br/><br/>'.$vehicles_list;?>	
            <?php endif;?>
            <?php endif;?>
	</div>			            
    </td>
  </tr>


<!--Einsatzbericht anzeigen mit Plugin-Support-->           
<?php if( $this->item->desc ) : ?>
  <tr>
    <td  class="layout4_row_12" colspan="2">
		<b><u>Einsatzbericht:</u></b>
<?php jimport('joomla.html.content'); ?>  
<?php $Desc = JHTML::_('content.prepare', $this->item->desc); ?>
<div class="eiko_einsatzbericht_2">
<?php echo $Desc;?>
</div>
    </td>
  </tr>
<?php endif;?>
<!--Einsatzbericht anzeigen mit Plugin-Support  ENDE-->           
 
<!-- Presselinks -->           
<?php if( $this->item->presse or $this->item->presse2 or $this->item->presse3) : ?>
  <tr>
    <td class="layout4_row_13" colspan="2">
            <?php if( $this->item->presse) : ?>
			<?php echo '<a href="'.$this->item->presse.'" target="_blank"><i class="icon-share-alt" style=" margin-right:5px;"></i><small>'.$this->item->presse_label.'</small></a>&nbsp;&nbsp;&nbsp;'; ?>
            <?php endif;?>
            <?php if( $this->item->presse2 ) : ?>
			<?php echo '<a href="'.$this->item->presse2.'" target="_blank"><i class="icon-share-alt" style=" margin-right:5px;"></i><small>'.$this->item->presse2_label.'</small></a>&nbsp;&nbsp;&nbsp;'; ?>
            <?php endif;?>
            <?php if( $this->item->presse3 ) : ?>
			<?php echo '<a href="'.$this->item->presse3.'" target="_blank"><i class="icon-share-alt" style=" margin-right:5px;"></i><small>'.$this->item->presse3_label.'</small></a>'; ?>
            <?php endif;?>
    </td>
  </tr>
<?php endif;?>
<!-- Presselinks ENDE -->  

<!--Einsatzbilder Galerie -->           
<?php if ($this->images) : ?>
  <tr>
    <td class="layout4_row_15" colspan="2">
		<b><u>Einsatzbilder:</u></b>
            <div class="row-fluid">
            <ul class="thumbnails eiko_thumbnails_2">
            <?php
			for ($i = 0;$i < count($this->images);++$i) { 
			$fileName_thumb = JURI::Root().$this->images[$i]->thumb;
			$fileName_image = JURI::Root().$this->images[$i]->image;
			$thumbwidth = $this->params->get('detail_thumbwidth','100px'); 
			?>   
              <li>
                <div class="thumbnail eiko_thumbnail_2" style="max-width:<?php echo $thumbwidth;?>;)">
    			<a href="<?php echo $fileName_image;?>" rel="highslide[<?php echo $this->item->id; ?>]" class="highslide" onClick="return hs.expand(this, { captionText: '<?php echo $this->einsatzlogo->title;?> am <?php echo date("d.m.Y - H:i", strtotime($this->item->date1)).' Uhr'; ?><br/><?php echo $this->images[$i]->comment;?>' });" alt ="<?php echo $this->einsatzlogo->title;?>">
                <img  class="eiko_img-rounded eiko_thumbs_2" src="<?php echo $fileName_thumb;?>"  alt="<?php echo $this->einsatzlogo->title;?>" title="Bild-Nr. <?php echo $this->images[$i]->id;?>"  style="width:<?php echo $this->params->get('detail_thumbwidth','100px');?>;)" alt ="<?php echo $this->einsatzlogo->title;?>"/>
<?php if ($this->images[$i]->comment) : ?>
<br/><span><i class="icon-info-sign" style=" margin-right:5px;"></i>Info</span>
 <?php endif; ?>
              </a>
			  </div>
           </li>
			<?php 	} ?>
         </ul>
        </div>
    </td>
  </tr>
<?php endif; ?>
<!--Einsatzbilder Galerie ENDE -->      
 
 
<!--Einsatzkarte-->
			<?php $user	= JFactory::getUser();?>
            <?php if( $this->item->gmap) : ?> 
            <?php if( $this->item->gmap_report_latitude != '0' ) : ?> 
			<tr>
				<td colspan="2">
					<b><u>Ungef&auml;hrer Einsatzort:</u></b>
				</td>
			</tr>
			<tr>			
			<td class="layout4_row_14" colspan="2">
            <?php if( $this->params->get('display_detail_map_for_only_user','0') == '1' || $user->id ) :?> 
			<?php if ($this->params->get('gmap_action','0') == '1') :?>
  			<div id="map-canvas" class="eiko_einsatzkarte_2" style="height:<?php echo $this->params->get('detail_map_height','250px');?>;">
    		<noscript>Dieser Teil der Seite erfordert die JavaScript Unterstützung Ihres Browsers!</noscript>
			</div>
            <?php endif;?>
			<?php if ($this->params->get('gmap_action','0') == '2') :?>
				<body onLoad="drawmap();">
   				<div id="map" class="eiko_einsatzkarte_2" style="height:<?php echo $this->params->get('detail_map_height','250px');?>;"></div> 
    		<noscript>Dieser Teil der Seite erfordert die JavaScript Unterstützung Ihres Browsers!</noscript>
			<?php endif;?>
			<?php if ($this->params->get('gmap_action','0')) : ?>
            <?php if( $this->item->gmap ) : ?>
              <div style ="text-align:center;" class="eiko_distance_road hasTooltip" title ="Die Angabe kann vom tats&auml;chlichen Streckenverlauf abweichen, da diese Angabe automatisch von Google Maps errechnet wurde !" id="distance_road"></div>
            <?php endif;?>
            <?php endif;?>
			<?php else:?> 
			<?php echo '<span style="padding:5px;" class="label label-info">( Bitte melden Sie sich an, um den Einsatzort auf einer Karte zu sehen. )</span>';?>
			<?php endif;?>
			</td>
			</tr>
            <?php endif;?>
            <?php endif;?>
<!--Einsatzkarte ENDE-->     
  

</table>





<?php else:?>
<div class="eiko_distance20_2">&nbsp;</div>
<h3 class="eiko_keine_daten_2">Es sind keine Einsatzdaten für diese Auswahl vorhanden</h3>
<?php endif;?>


<!-- Detail-Footer -->           
<?php if ($this->params->get('display_detail_footer','1')) : ?>
<div class="eiko_distance100_2">&nbsp;</div>
<div class="eiko_detail_footer_2"><i class="icon-info-sign" style="margin-right:5px;"></i><?php echo $this->params->get('display_detail_footer_text','Kein Detail-Footer-Text vorhanden');?> </div>
<?php endif;?>
<!-- Detail-Footer ENDE -->           

<div class="clear"></div> 
















