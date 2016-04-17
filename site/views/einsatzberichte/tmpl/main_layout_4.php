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


//print_r ($this->organisationen);

//echo $this->selectedYear;
?>

<style type="text/css">
	.hover {
		a:hover {
			color: black;
			background-color: yellow;
		}
	}
</style>

<div>

<!--RSS-Feed Imag-->
<?php if ($this->params->get('display_home_rss','1')) : ?>
<div style="float:right;" class="eiko_rss" ><a href="<?php JURI::base();?>index.php?option=com_einsatzkomponente&view=einsatzberichte&format=feed&type=rss"><img src="<?php echo JURI::Root();?>/components/com_einsatzkomponente/assets/images/livemarks.png" class="eiko_rss_icon" border="0" alt="rss-feed-image"></a></div>
<?php endif; ?>


<!--Page Heading-->
<?php if ($this->params->get('show_page_heading', 1)) : ?>
<div class="page-header">
<h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
</div>
<?php endif;?>


<?php // Filter ------------------------------------------------------------------------------------
	if ($this->selectedYear == '9999') {
		$year = 0;
		$year_text = 'aller Jahre';
	}
	else {
		$year = $this->selectedYear;
		$year_text = $this->selectedYear;
	}
	
	//echo '<div style="text-align:center;margin-bottom:20px;"><img src="'.JURI::Root().'images/com_einsatzkomponente/images/years/home'.$year.'.png" title="Eins&auml;tze '.$year_text.'" /></div>';

?>
<form action="<?php echo JRoute::_('index.php?option=com_einsatzkomponente&view=einsatzberichte'); ?>" method="post" name="adminForm" id="adminForm">
<?php
	echo 'Jahr: ';

if (!$this->params->get('anzeigejahr','0') and $this->params->get('display_filter_jahre','1')) : 
	$years[] = JHTML::_('select.option', '', JTEXT::_('Bitte das Jahr auswählen')  , 'id', 'title');
	$years[] = JHTML::_('select.option', '9999', JTEXT::_('Alle Einsätze anzeigen')  , 'id', 'title');
	$years = array_merge($years, (array)$this->years);
	 
	echo JHTML::_('select.genericlist',  $years, 'year', ' onchange=submit(); ', 'id', 'title', $this->selectedYear);?>
		
    <?php
endif;
if ($this->params->get('display_filter_einsatzarten','1')) : 
	$einsatzarten[] = JHTML::_('select.option', '', JTEXT::_('alle Einsatzarten')  , 'id', 'title');
	$einsatzarten = array_merge($einsatzarten, (array)$this->einsatzarten);
	?><?php 
	echo JHTML::_('select.genericlist',  $einsatzarten, 'selectedEinsatzart', ' onchange=submit(); ', 'id', 'title', $this->selectedEinsatzart);?>
    <?php
	endif;
	if (!$this->params->get('abfragewehr','0') and $this->params->get('display_filter_organisationen','1')) : 
	$organisationen[] = JHTML::_('select.option', '', JTEXT::_('alle Organisationen')  , 'id', 'name');
	$organisationen = array_merge($organisationen, (array)$this->organisationen);
	?><?php 
	echo JHTML::_('select.genericlist',  $organisationen, 'selectedOrga', ' class="eiko_select_organisation_main_1" onchange=submit(); ', 'id', 'name', $this->selectedOrga);
	endif;?>
</div>
<?php // Filter ENDE   -------------------------------------------------------------------------------

echo $this->modulepos_2;
?>

<table width="100%" class="table table-striped table-bordered eiko_detail_4_table" id="example" border="1" cellspacing="0" cellpadding="0" style="margin-top:20px;">
    <thead>
        <tr class="eiko_detail_4_tr">
           <?php $col ='0';?>
           <?php if ($this->params->get('display_home_number','1') or $this->params->get('display_home_alertimage_num','0')) : ?>
		   <?php if ($this->params->get('display_home_number','1')):?>            
           <?php else:?>            
           <?php endif;?>
           <?php $col =$col+1;?>
           <?php endif;?>            
           <?php $col =$col+1;?>            
           <?php $col =$col+1;?>		               
           <?php $col =$col+1;?>
           <?php if ($this->params->get('display_home_orga','0')) : ?>            
           <?php $col =$col+1;?>
           <?php endif;?>
           <?php if ($this->params->get('display_home_image')) : ?>            
           <?php $col =$col+1;?>
           <?php endif;?>
        </tr>
        <!--<tr><th colspan="6"><hr /></th></tr>-->
    </thead>
    
 <tbody>
	 <?php 
$show = false;	 
if ($this->params->get('display_home_pagination')) :
     $i=$this->pagination->total - $this->pagination->limitstart+1;
	 else:
     $i=count($this->reports)+1;
	 endif;
	 $m = '';
	 $hide=0;
	 $z = 0;
     foreach ($this->reports as $item) :
		$z += 1;
?>
          <!-- Filter State-->
		  <?php if($item->state == '1'): ?>
		  <?php
		   $i--; //print_r ($item);
		   $curTime = strtotime($item->date1); 
		   ?>
          <!-- Filter Einsatzart-->
		  <?php if(preg_match('/\b'.$this->selectedOrga.'\b/',$item->auswahl_orga)==true or $this->selectedOrga == '0'): ?>
		  <?php if ($this->selectedEinsatzart == $item->data1 or $this->selectedEinsatzart == '' ) : ?>
          <?php $show = true;?>
          
           <!--Anzeige des Monatsnamen-->
		   <?php if ($this->params->get('display_home_monat','1')) : ?>
           <?php if (date('n', $curTime) != $m) : ?>
		   <?php if ($z <> 1) { ?>
			   <tr style="height:20px;">				
			   </tr>	
		   <?php } ?>
		   <tr style="border-bottom:1px #666666  dotted;text-align:center;" class="eiko_detail_4_tr_1"><td colspan="<?php echo $col;?>">
           <?php $m=date('n', $curTime);?>
		   <?php echo '<h3 style="margin:10px;">';?>
           <?php echo $this->monate[$m];?>
		   <?php echo date('Y', $curTime);?>
           <?php echo '</h3>';?>
           </td></tr>
			<tr class="eiko_detail_4_tr_2">
			   <?php $col ='0';?>
			   <?php if ($this->params->get('display_home_number','1') or $this->params->get('display_home_alertimage_num','0')) : ?>
			   <?php if ($this->params->get('display_home_number','1')):?>
				<th class="eiko_th_nr" width="">Nr.</th>
			   <?php else:?>
				<th class="eiko_th_nr" width=""></th>
			   <?php endif;?>
			   <?php $col =$col+1;?>
			   <?php endif;?>
				<th class="eiko_th_date" width="">Alarmierung</th>
			   <?php $col =$col+1;?>
				<th class="eiko_th_summary mobile_hide_summary" width="">Beschreibung</th>
			   <?php $col =$col+1;?>		   
				<th class="eiko_th_data" width="250px">Einsatzort</th>
			   <?php $col =$col+1;?>
			   <?php if ($this->params->get('display_home_orga','0')) : ?>
				<th class="eiko_th_orga" width="">Organisationen</th>
			   <?php $col =$col+1;?>
			   <?php endif;?>
			   <?php if ($this->params->get('display_home_image')) : ?>
				<th class="eiko_th_image mobile_hide_image" width="">Bild</th>
			   <?php $col =$col+1;?>
			   <?php endif;?>
			   <?php if ($this->params->get('display_home_counter','1')) : ?>
				<th class="eiko_th_counter mobile_hide_counter" width="">Zugriffe</th>
			   <?php $col =$col+1;?>
			   <?php endif;?>
			</tr>		   
           <?php endif;?>
           <?php endif;?>
           <!--Anzeige des Monatsnamen ENDE-->
           
		   <tr class="eiko_detail_4_tr_3">
           
           <?php if ($this->params->get('display_home_number','1') or $this->params->get('display_home_alertimage_num','0')) : ?>
           <?php if ($this->params->get('display_home_marker','1')) : ?>
		   <td style="text-align:center;color:#000000;font-weight:bold;font-size:larger;background-color:<?php echo $item->marker;?>;" >
           <?php else:?>
		   <td style="text-align:center;color:#000000;font-weight:bold;font-size:larger;" >
           <?php endif;?>
           <?php if ($this->params->get('display_home_number','1')) : ?>
           <?php if ($hide) :?>
           <?php echo $i.' - '.($i + $hide);$hide =0;?>
           <?php else:?>
           <?php echo $i;?>
           <?php endif;?>
           <?php endif;?>
           <?php if ($this->params->get('display_home_alertimage_num','0')) : ?>
           <br/><img class="img-rounded" style="width:30px; height:30px;" src="<?php echo JURI::Root();?><?php echo $item->image;?>" title="<?php echo $item->alarmierungsart;?>" />
           <?php endif;?>
            </td>
           <?php endif;?>
           

          
		   <td align="center"> 
		   
			<?php $wochentage = explode(',','So.,Mo.,Di.,Mi.,Do.,Fr.,Sa.');
				$date2 = $wochentage[date('w', $curTime)];?>
  
			<?php if ($this->params->get('display_home_date_image','1')=='1') : ?>
			<div class="home_cal_icon">
			<div class="home_cal_monat"><?php echo date('M', $curTime);?></div>
			<div class="home_cal_tag"><?php echo date('d', $curTime);?></div>
			<div class="home_cal_jahr"><span style="font-size:10px;"><?php echo date('Y', $curTime);?></span></div>
			</div>
           <?php endif;?>
           <?php if ($this->params->get('display_home_date_image','1')=='2') : ?>
		   <?php echo $date2.', '.date('d.m.Y ', $curTime);?><br /><?php echo date('H:i ', $curTime).' Uhr'; ?>
           <?php endif;?>
           <?php if ($this->params->get('display_home_date_image','1')=='0') : ?>
		   <?php echo $date2.', '.date('d.m.Y ', $curTime).'';?>
           <?php endif;?>

		   

			   <?php if ($this->params->get('display_home_alertimage','0')) : ?>			   
					<br /><img class="img-rounded" style="width:32px; height:32px;margin-right:2px;" src="<?php echo JURI::Root();?><?php echo $item->image;?>" title="<?php echo $item->alarmierungsart;?>" />			   
			   <?php endif;?>			
           </td>

		   <td align="center">         		   
				<?php if ($this->params->get('display_home_links')) : ?>
			   <a class="hover" href="<?php echo JRoute::_('index.php?option=com_einsatzkomponente'.$this->layout_detail_link.'&view=einsatzbericht&id=' . (int)$item->id); ?>" style="font-weight:bold;color:<?php echo $item->marker;?>;">
			   <?php endif; ?>
			   <?php echo ''.$item->einsatzart; ?>
				<?php if ($this->params->get('display_home_links','1')) : ?>
			   </a>
			   <?php endif; ?>
			   <br />		
			   <?php echo $item->summary;?>		   

				<?php if ($this->params->get('display_home_info','1')) : ?>
				<!--<br /><button class="btn-home" onClick="jQuery.toggle<?php echo $item->id;?>(div<?php echo $item->id;?>)">Kurzinfo</button>-->
				<br />
			   <?php if ($this->params->get('display_home_links','1')) : ?>
				<!--<a class="btn-home" href="<?php echo JRoute::_('index.php?option=com_einsatzkomponente'.$this->layout_detail_link.'&view=einsatzbericht&id=' . (int)$item->id); ?>">Details</a>-->
			   <?php endif;?>

				<script type="text/javascript">
				jQuery.toggle<?php echo $item->id;?> = function(query)
					{
					jQuery(query).slideToggle("5000");
					jQuery("#tr<?php echo $item->id;?>").fadeToggle("fast"); 
					}   
				</script>
				<?php endif;?>            
           </td>		   		   
		   <td align="center"><?php echo '<i class="icon-arrow-right"></i> '.$item->address;?></td>
           
           <?php if ($this->params->get('display_home_orga','0')) : ?>
           <?php 					$data = array();
					foreach(explode(',',$item->auswahl_orga) as $value):
						$db = JFactory::getDbo();
						$query	= $db->getQuery(true);
						$query
							->select('name')
							->from('`#__eiko_organisationen`')
							->where('id = "' .$value.'"');
						$db->setQuery($query);
						$results = $db->loadObjectList();
						if(count($results)){
							$data[] = '<!-- <span class="label label-info"> --!>'.$results[0]->name.'<!-- </span>--!>'; 
						}
					endforeach;
					$auswahl_orga=  implode('</br>',$data); 
?>
		   <td nowrap> <?php echo $auswahl_orga;?></td>
           <?php endif;?>

           <?php if ($this->params->get('display_home_image')) : ?>
		   <?php if ($item->foto) : ?>		   
		   <td class="mobile_image" style="text-align:center;vertical-align:center;padding-top:5px;"> <img  class="img-rounded" style="width:<?php echo $this->params->get('display_home_image_width','80px');?>;" src="<?php echo JURI::Root();?><?php echo $item->foto;?>"/></td>
           <?php endif;?>
		   <?php if (!$item->foto) : ?>
           <?php if ($this->params->get('display_home_image_nopic','0')) : ?>
		   <td class="mobile_image"> <img  class="img-rounded" style="width:<?php echo $this->params->get('display_home_image_width','80px');?>;" src="<?php echo JURI::Root().'images/com_einsatzkomponente/einsatzbilder/nopic.png';?>"/></td>
           <?php else:?>
			<td class="mobile_image">&nbsp;</td>
		   <?php endif;?>
           <?php endif;?>
           <?php endif;?>
		   
           <?php if ($this->params->get('display_home_counter','1')) : ?>
		   <?php echo '<td class="mobile_hide_counter"">';?>
		   <?php echo $item->counter;?>
		   <?php echo '</td>';?>
		   <?php endif;?>
		   </tr>
           
           <!-- Zusatzinformation -->
			<?php if ($this->params->get('display_home_info','1')) : ?>
            <?php $auswahl_orga = str_replace(",", " +++ ", $item->auswahl_orga);?>
            <tr id="tr<?php echo $item->id;?>" style=" display:none;" >
            
           <?php if ($this->params->get('display_home_marker','1')) : ?>
           <?php $rgba = hex2rgba($item->marker,0.7);?>
            <style>
				.td<?php echo $item->id;?> {
				background: -moz-linear-gradient(top,  <?php echo $rgba;?> 0%, rgba(125,185,232,0) 100%); /* FF3.6+ */
				background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,<?php echo $rgba;?>), color-stop(100%,rgba(125,185,232,0))); /* Chrome,Safari4+ */
				background: -webkit-linear-gradient(top,  <?php echo $rgba;?> 0%,rgba(125,185,232,0) 100%); /* Chrome10+,Safari5.1+ */
				background: -o-linear-gradient(top,  <?php echo $rgba;?> 0%,rgba(125,185,232,0) 100%); /* Opera 11.10+ */
				background: -ms-linear-gradient(top,  <?php echo $rgba;?> 0%,rgba(125,185,232,0) 100%); /* IE10+ */
				background: linear-gradient(to bottom,  <?php echo $rgba;?> 0%,rgba(125,185,232,0) 100%); /* W3C */
				filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='<?php echo $item->marker;?>', endColorstr='#007db9e8',GradientType=0 ); /* IE6-9 */
				}
			</style>
		   <td class="td<?php echo $item->id;?>" >
           <?php else:?>
		   <td>
           <?php endif;?>
            </td>
            <td colspan="<?php echo $col;?>">
			<div id ="div<?php echo $item->id;?>" style="display:none;">
            <h3>Alarmierungszeit :</h3><?php echo date('d.m.Y', $curTime);?> um <?php echo date('H:i', $curTime);?> Uhr
            <h3>alarmierte Organisationen :</h3><?php echo $auswahl_orga;?><br/>
		   <?php if ($item->desc) : ?>
			<h3>Einsatzbericht :</h3><?php echo $item->desc;?>
            <?php endif;?>
            <br /><button class="btn-home" onClick="jQuery.toggle<?php echo $item->id;?>(div<?php echo $item->id;?>)">Info schliessen</button>
           <?php if ($this->params->get('display_home_links','1')) : ?>
            <a class="btn-home" href="<?php echo JRoute::_('index.php?option=com_einsatzkomponente'.$this->layout_detail_link.'&view=einsatzbericht&id=' . (int)$item->id); ?>">zur Detailansicht</a>
           <?php endif;?>
           </div> 
           </td>
           </tr>
           <?php endif;?>
           <?php endif;?><?php endif;?><!-- Filter Einsatzart-->
           <?php endif;?><!-- Filter State-->
		  <?php if($item->state == '2'): ?>
          <?php $hide++;$i--;?>
          <?php endif;?> 
		  
    <?php endforeach; ?>
    
<?php if(!$this->reports or !$show): ?>
<span class="label label-info"><b>Es können zu dieser Auswahl keine Daten in der Datenbank gefunden werden</b></span>
<?php endif; ?>

    
    <?php if ($this->params->get('display_home_map')) : ?>
    <tr class="eiko_detail_4_tr_4"><td colspan="<?php echo $col;?>">
    <h4>Einsatzgebiet</h4>
			<?php if ($this->params->get('gmap_action','0') == '1') :?>
  			<div id="map-canvas" style="width:100%; height:<?php echo $this->params->get('home_map_height','300px');?>;">
    		<noscript>Dieser Teil der Seite erfordert die JavaScript Unterstützung Ihres Browsers!</noscript>
			</div>
            <?php endif;?>
			<?php if ($this->params->get('gmap_action','0') == '2') :?>
<body onLoad="drawmap();">
				<!--<div id="descriptionToggle" onClick="toggleInfo()">Informationen zur Karte anzeigen</div>
				<div id="description" class="">Einsatzkarte</div>-->
   				<div id="map" style="width:100%; height:<?php echo $this->params->get('home_map_height','300px');?>;"></div> 
    		<noscript>Dieser Teil der Seite erfordert die JavaScript Unterstützung Ihres Browsers!</noscript>
            <?php endif;?>
            </td></tr>
    <?php endif;?>
 </tbody>   
    
    
    <tfoot>
    				<!--Prüfen, ob Pagination angezeigt wrden soll-->
    				<?php if ($this->params->get('display_home_pagination')) : ?>
    				<!--Prüfen, ob Einsatzart ausgwählt ist -->
                    <?php if ($this->selectedEinsatzart == '' or $this->selectedEinsatzart == 'alle Einsatzarten') : ?>
					<tr><td colspan="<?php echo $col;?>">
                    	<form action="#" method=post>
						<?php echo $this->pagination->getListFooter(); ?><!--Pagination anzeigen-->
						</form> 
					</td></tr>
		   			<?php endif;?><!--Prüfen, ob Einsatzart ausgwählt ist ENDE-->
		   			<?php endif;?><!--Prüfen, ob Pagination angezeigt wrden soll   ENDE -->

<?php if (!$this->params->get('eiko')) : ?>
        <tr><!-- Bitte das Copyright nicht entfernen. Danke. -->
            <th colspan="<?php echo $col;?>"><span class="copyright">Einsatzkomponente V<?php echo $this->version; ?>  (C) 2015 by Ralf Meyer (<a class="copyright_link" href="http://einsatzkomponente.de" target="_blank">www.einsatzkomponente.de</a>)</span></th>
        </tr>
	<?php endif; ?>
    
</tfoot>
</table>
	</form>
<?php echo $this->modulepos_1;?>


<?php if(JFactory::getUser()->authorise('core.create','com_einsatzkomponente.einsatzbericht'.$item->id)): ?><a href="<?php echo JRoute::_('index.php?option=com_einsatzkomponente&view=einsatzberichtform&layout=edit&id=0'); ?>">Einsatz eintragen</a>
	<?php endif; ?>
	
    
    <?php function hex2rgba($color, $opacity = false) {  // Farbe von HEX zu RGBA umwandeln 

	$default = 'rgb(0,0,0)';
	//Return default if no color provided
	if(empty($color))
          return $default; 
	//Sanitize $color if "#" is provided 
        if ($color[0] == '#' ) {
        	$color = substr( $color, 1 );
        }
        //Check if color has 6 or 3 characters and get values
        if (strlen($color) == 6) {
                $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
        } elseif ( strlen( $color ) == 3 ) {
                $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
        } else {
                return $default;
        }
        //Convert hexadec to rgb
        $rgb =  array_map('hexdec', $hex);
        //Check if opacity is set(rgba or rgb)
        if($opacity){
        	if(abs($opacity) > 1)
        		$opacity = 1.0;
        	$output = 'rgba('.implode(",",$rgb).','.$opacity.')';
        } else {
        	$output = 'rgb('.implode(",",$rgb).')';
        }
        //Return rgb(a) color string
        return $output;
		
}?> 