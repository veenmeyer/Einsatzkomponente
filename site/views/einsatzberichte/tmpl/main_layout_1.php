<?php
/**
 * @version     3.15.0
 * @package     com_einsatzkomponente
 * @copyright   Copyright (C) 2017 by Ralf Meyer. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ralf Meyer <ralf.meyer@mail.de> - https://einsatzkomponente.de
 */
// no direct access
defined('_JEXEC') or die;


//print_r ($this->organisationen);

//echo $this->selectedYear;
?>

<div>

<!--RSS-Feed Imag-->
<?php if ($this->params->get('display_home_rss','1')) : ?>
<div class="eiko_rss_main_1" ><a href="<?php JURI::base();?>index.php?option=com_einsatzkomponente&view=einsatzberichte&format=feed&type=rss"><span class="icon-feed" style="color:#cccccc;font-size:24px;"> </span> </a></div>
<?php endif;?>


<!--Page Heading-->
<?php if ($this->params->get('show_page_heading', 1)) : ?>
<div class="page-header eiko_header_main_1">
<h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1> 
</div>
<?php endif;?>


<?php // Filter ------------------------------------------------------------------------------------
	

?>
<form action="<?php echo JRoute::_('index.php?option=com_einsatzkomponente&view=einsatzberichte&list=1'); ?>" method="post" name="adminForm" id="adminForm">
<?php


if (!$this->params->get('anzeigejahr','0') and $this->params->get('display_filter_jahre','1')) : 
	$years[] = JHTML::_('select.option', '', JTEXT::_('COM_EINSATZKOMPONENTE_JAHR')  , 'id', 'title');
	$years[] = JHTML::_('select.option', '9999', JTEXT::_('COM_EINSATZKOMPONENTE_ALLE_EINSAETZE')  , 'id', 'title');
	$years = array_merge($years, (array)$this->years);
	 
	echo JHTML::_('select.genericlist',  $years, 'year', ' class="eiko_select_jahr_main_1" onchange=submit(); ', 'id', 'title', $this->selectedYear, $translate=true);?>
	
    <?php
endif;
if ($this->params->get('display_filter_einsatzarten','1')) : 
	$einsatzarten[] = JHTML::_('select.option', '', JTEXT::_('COM_EINSATZKOMPONENTE_ALLE_EINSATZARTEN')  , 'id', 'title');
	$einsatzarten = array_merge($einsatzarten, (array)$this->einsatzarten);
	?><?php  
	echo JHTML::_('select.genericlist',  $einsatzarten, 'selectedEinsatzart', ' class="eiko_select_einsatzart_main_1" onchange=submit(); ', 'id', 'title', $this->selectedEinsatzart, $translate=true);?>
    <?php
	endif;
	if (!$this->params->get('abfragewehr','0') and $this->params->get('display_filter_organisationen','1')) : 
	$organisationen[] = JHTML::_('select.option', '', JTEXT::_('COM_EINSATZKOMPONENTE_ALLE_ORGANISATIONEN')  , 'id', 'name');
	$organisationen = array_merge($organisationen, (array)$this->organisationen);
	?><?php 
	echo JHTML::_('select.genericlist',  $organisationen, 'selectedOrga', ' class="eiko_select_organisation_main_1" onchange=submit(); ', 'id', 'name', $this->selectedOrga, $translate=true);
	endif;?>
</div>
<?php // Filter ENDE   -------------------------------------------------------------------------------
 
  
echo '<span class="mobile_hide_320">'.$this->modulepos_2.'</span>';

?>
<?php if(JFactory::getUser()->authorise('core.create','com_einsatzkomponente.einsatzbericht')): ?><div class="eiko_neu"><a href="<?php echo JRoute::_('index.php?option=com_einsatzkomponente&view=einsatzberichtform&layout=edit&id=0'); ?>" class="eiko_btn2">Einsatz eintragen</a></div>
	<?php endif; ?>
<table width="100%" class="table table-striped table-bordered eiko_table_main_1" border="0" cellspacing="0" cellpadding="0">
    <thead>
        <tr>
           <?php $col ='0';?>
           <?php if ($this->params->get('display_home_number','1') or $this->params->get('display_home_alertimage_num','0')) : ?>
		   <?php if ($this->params->get('display_home_number','1')):?>
            <th class="eiko_th_einsatznummer_main_1" width=""><?php echo JTEXT::_('COM_EINSATZKOMPONENTE_NR');?></th>
           <?php else:?>
            <th class="eiko_th_einsatznummer_main_1" width=""></th>
           <?php endif;?>
           <?php $col =$col+1;?>
           <?php endif;?>
           <?php if ($this->params->get('display_home_alertimage','0')) : ?>
            <th class="eiko_th_alarmierungsart_main_1 mobile_hide_480" width=""><?php echo JTEXT::_('COM_EINSATZKOMPONENTE_ALARM_UEBER');?></th>
           <?php $col =$col+1;?>
           <?php endif;?>
            <th class="eiko_th_datum_main_1 mobile_hide_320" width=""><?php echo JTEXT::_('COM_EINSATZKOMPONENTE_DATUM');?></th>
           <?php $col =$col+1;?>
            <th class="eiko_th_einsatzart_main_1" width=""><?php echo JTEXT::_('COM_EINSATZKOMPONENTE_EINSATZART_ORT');?></th>
           <?php $col =$col+1;?>
            <th class="eiko_th_kurzbericht_main_1 mobile_hide_480" width=""><?php echo JTEXT::_('COM_EINSATZKOMPONENTE_DESC');?></th>
           <?php $col =$col+1;?>
           <?php if ($this->params->get('display_home_orga','0')) : ?>
            <th class="eiko_th_organisationen_main_1 mobile_hide_480" width=""><?php echo JTEXT::_('COM_EINSATZKOMPONENTE_ORGANISATIONEN');?></th>
           <?php $col =$col+1;?>
           <?php endif;?>
           <?php if ($this->params->get('display_home_image')) : ?>
            <th class="eiko_th_einsatzbild_main_1 mobile_hide_480" width=""><?php echo JTEXT::_('COM_EINSATZKOMPONENTE_EINSATZFOTOS');?></th>
           <?php $col =$col+1;?>
           <?php endif;?>
           <?php if ($this->params->get('display_home_presse')) : ?>
            <th class="eiko_th_presse_main_1 mobile_hide_480" width=""><?php echo JTEXT::_('COM_EINSATZKOMPONENTE_PRESSELINK');?></th>
           <?php $col =$col+1;?>
           <?php endif;?>
		   <?php if ($this->params->get('display_home_counter','1')) : ?>
		   <th class="eiko_th_counter mobile_hide_480" width=""><?php echo JTEXT::_('COM_EINSATZKOMPONENTE_ZUGRIFFE');?></th>
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
     foreach ($this->reports as $item) :
	 
	 	// Funktion : Einsatzkategorie
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
					$query
						->select('*')
						->from('#__eiko_tickerkat')
						->where('id = "' .$item->tickerkat.'"  AND state = "1" ');
					$db->setQuery($query);
					$tickerkat = $db->loadObject();

?>
          <!-- Filter State-->
		  <?php if($item->state == '1'): ?>
		  <?php
		   $i--; //print_r ($item);
		   $curTime = strtotime($item->date1); 
			?>
		   <!-- Filter Einsatzart--!>
		  <?php if(preg_match('/\b'.$this->selectedOrga.'\b/',$item->auswahl_orga)==true or $this->selectedOrga == '0'): ?>
		  <?php if ($this->selectedEinsatzart == $item->data1 or $this->selectedEinsatzart == '' ) : ?>
          <?php $show = true;?>
          
           <!--Anzeige des Monatsnamen-->
		   <?php if ($this->params->get('display_home_monat','1')) : ?>
           <?php if (date('n', $curTime) != $m) : ?>
		   <tr style="border-bottom:1px #666666  dotted;"><td colspan="<?php echo $col;?>" class="eiko_td_month_main_1">
           <?php $m=date('n', $curTime);?>
		   <?php echo '<h3>';?>
           <?php echo $this->monate[$m];?>
		   <?php if ($this->selectedYear == '9999') : echo date('Y', $curTime); endif;?>
           <?php echo '</h3>';?>
           </td></tr>
           <?php endif;?>
           <?php endif;?>
           <!--Anzeige des Monatsnamen ENDE-->
           
		   <tr class="eiko_tr_main_1">
           
           <?php if ($this->params->get('display_home_number','1') or $this->params->get('display_home_alertimage_num','0')) : ?>
           <?php if ($this->params->get('display_home_marker','1')) : ?>
		   <td class="eiko_td_marker_main_1" style="background-color:<?php echo $item->marker;?>;" >
           <?php else:?>
		   <td class="eiko_td_marker_main_1">
           <?php endif;?>
		   
           <?php if ($this->params->get('display_home_number','2') == '1') : ?>
           <?php if ($hide) :?>
           <?php echo $i.' - '.($i + $hide);$hide =0;?>
           <?php else:?>
           <?php echo $i;?>
           <?php endif;?>
           <?php endif;?>
		   
			
           <?php if ($this->params->get('display_home_number','2') == '2') : 
				$item->date_11 		= strtotime($item->date1);
				$item->date1_year 	= date('Y', $item->date_11);
				echo '<span style="white-space: nowrap;" class="eiko_span_marker_main_1">Nr. '.EinsatzkomponenteHelper::ermittle_einsatz_nummer($item->date_11,$item->data1).' / '.$item->date1_year.'</span>';
				endif;?>
		   
           <?php if ($this->params->get('display_home_alertimage_num','0')) : ?>
           <br/><img class="eiko_icon hasTooltip" src="<?php echo JURI::Root();?><?php echo $item->image;?>" title="<?php echo $item->alarmierungsart;?>" />
           <?php endif;?>
            </td>
           <?php endif;?>
           
           <?php if ($this->params->get('display_home_alertimage','0')) : ?>
		   <td class="eiko_td_alarmierungsart_main_1 mobile_hide_480"> 
           <img class="eiko_icon hasTooltip" src="<?php echo JURI::Root();?><?php echo $item->image;?>" title="<?php echo $item->alarmierungsart;?>" />
           </td>
           <?php endif;?>
           
           <?php if ($this->params->get('display_home_date_image','1')=='1') : ?>
		   <td class="eiko_td_kalender_main_1 mobile_hide_320"> 
			<div class="home_cal_icon">
			<div class="home_cal_monat"><?php echo date('M', $curTime);?></div>
			<div class="home_cal_tag"><?php echo date('d', $curTime);?></div>
			<div class="home_cal_jahr"><span style="font-size:10px;"><?php echo date('Y', $curTime);?></span></div>
			</div>
           </td>
           <?php endif;?>
           <?php if ($this->params->get('display_home_date_image','1')=='3') : ?>
		   <td class="eiko_td_kalender_main_1 mobile_hide_320"> 
			<div class="home_cal_icon">
			<div class="home_cal_monat"><?php echo date('M', $curTime);?></div>
			<div class="home_cal_tag"><?php echo date('d', $curTime);?></div>
			<div class="home_cal_jahr"><span style="font-size:10px;"><?php echo date('Y', $curTime);?></span></div>
			<?php echo '<span style="font-size:smaller;white-space: nowrap;" class="">'.date('H:i ', $curTime).'Uhr</span>'; ?>
			</div>
           </td>
           <?php endif;?>
           <?php if ($this->params->get('display_home_date_image','1')=='2') : ?>
		   <td class="eiko_td_datum_main_1 mobile_hide_320"> <?php echo date('d.m.Y ', $curTime);?><br /><?php echo date('H:i ', $curTime); ?>Uhr</td>
           <?php endif;?>
           <?php if ($this->params->get('display_home_date_image','1')=='0') : ?>
		   <td class="eiko_td_datum_main_1 mobile_hide_320"> <?php echo date('d.m.Y ', $curTime);?></td>
           <?php endif;?>

		   <td class="eiko_td_einsatzart_main_1">
           
		   <?php if ($this->params->get('display_list_icon')) : ?>
		   <?php if (isset($item->list_icon)) :?>
           <img class="eiko_icon hasTooltip" style="float:<?php echo $this->params->get('float_list_icon');?>;" src="<?php echo JURI::Root();?><?php echo $item->list_icon;?>" title="<?php echo JTEXT::_('COM_EINSATZKOMPONENTE_EINSATZART');?>: <?php echo $item->einsatzart;?>" />
           <?php endif;?>
           <?php endif;?>

		   <?php if ($this->params->get('display_tickerkat_icon')) : ?>
		   <?php if (isset($tickerkat->image)) :?>
           <img class="eiko_icon hasTooltip mobile_hide_480" style="float:<?php echo $this->params->get('float_tickerkat_icon');?>;" src="<?php echo JURI::Root();?><?php echo $tickerkat->image;?>" title="<?php echo JTEXT::_('COM_EINSATZKOMPONENTE_KATEGORIE');?>: <?php echo $tickerkat->title;?>" />
		   <?php endif;?>
           <?php endif;?>
		   
			<?php if ($this->params->get('display_home_links_2')) : ?>
           <a href="<?php echo JRoute::_('index.php?option=com_einsatzkomponente'.$this->layout_detail_link.'&view=einsatzbericht&id=' . (int)$item->id); ?>">
		   <?php endif; ?>
		   <?php echo ''.$item->einsatzart; ?>
			<?php if ($this->params->get('display_home_links_2','1')) : ?>
           </a>
		   <?php endif; ?>
           <br />

<?php echo '<span class="eiko_address_main_1"><i class="icon-location" ></i> '.$item->address.'</span>';?>

			<?php if ($this->params->get('display_home_info','1') or $this->params->get('display_home_links','1')) : ?>
			<div class="eiko_td_buttons_main_1">
			<?php endif;?>

			<?php if ($this->params->get('display_home_info','1')) : ?>
			<input type="button" class="btn-home" onClick="jQuery.toggle<?php echo $item->id;?>(div<?php echo $item->id;?>)" value="<?php echo JTEXT::_('COM_EINSATZKOMPONENTE_KURZINFO');?>"></input>
            <script type="text/javascript">
			jQuery.toggle<?php echo $item->id;?> = function(query)
				{
        		jQuery(query).slideToggle("5000");
				jQuery("#tr<?php echo $item->id;?>").fadeToggle("fast");
				}   
			</script>
            <?php endif;?>
			
			<?php if ($this->params->get('display_home_links','1')) : ?>
            <span class="mobile_hide_320"><a class="btn-home" href="<?php echo JRoute::_('index.php?option=com_einsatzkomponente'.$this->layout_detail_link.'&view=einsatzbericht&id=' . (int)$item->id); ?>"><?php echo JTEXT::_('COM_EINSATZKOMPONENTE_DETAILS');?></a></span>
           <?php endif;?>   
		   
			<?php if ($this->params->get('display_home_info','1') or $this->params->get('display_home_links','1')) : ?>
			</div>
			<?php endif;?>
           </td>
		   
		   <td class="mobile_hide_480 eiko_td_kurzbericht_main_1"> <?php echo $item->summary;?></td>
           
           <?php if ($this->params->get('display_home_orga','0')) : ?>
           <?php 					
					$data = array();
					foreach(explode(',',$item->auswahl_orga) as $value):
						$db = JFactory::getDbo();
						$query	= $db->getQuery(true);
						$query
							->select('name')
							->from('#__eiko_organisationen')
							->where('id = "' .$value.'"');
						$db->setQuery($query);
						$results = $db->loadObjectList();
						if(count($results)){
							$data[] = '<!-- <span class="label label-info"> --!>'.$results[0]->name.'<!-- </span>--!>'; 
						}
					endforeach;
					$auswahl_orga=  implode('</br>',$data); 
?>
		   <td nowrap class="eiko_td_organisationen_main_1 mobile_hide_480"> <?php echo $auswahl_orga;?></td>
           <?php endif;?>

           <?php if ($this->params->get('display_home_image')) : ?>
		   <?php if ($item->foto) : ?>
		   <td class="mobile_hide_480 eiko_td_einsatzbild_main_1"> 
		   
			<?php if ($this->params->get('display_home_links_3','0')) : ?>
			<a href="<?php echo JRoute::_('index.php?option=com_einsatzkomponente&view=einsatzbericht&id='.(int) $item->id); ?>">
			<?php endif; ?> 
		   <img  class="img-rounded eiko_img_einsatzbild_main_1" style="width:<?php echo $this->params->get('display_home_image_width','80px');?>;" src="<?php echo JURI::Root();?><?php echo $item->foto;?>"/>
			<?php if ($this->params->get('display_home_links_3','0')) : ?>
			</a>
			<?php endif;?>
		   
		   </td>
           <?php endif;?>
		   <?php if (!$item->foto) : ?>
           <?php if ($this->params->get('display_home_image_nopic','0')) : ?>
		   <td class="mobile_hide_480 eiko_td_einsatzbild_main_1"> <img  class="img-rounded eiko_img_einsatzbild_main_1" style="width:<?php echo $this->params->get('display_home_image_width','80px');?>;" src="<?php echo JURI::Root().'images/com_einsatzkomponente/einsatzbilder/nopic.png';?>"/></td>
           <?php else:?>
			<td class="mobile_hide_480">&nbsp;</td>
		   <?php endif;?>
           <?php endif;?>
           <?php endif;?>
		   
			<?php if ($this->params->get('display_home_presse','0')) : ?>
				<td class="mobile_hide_480 mobile_hide_presse">
					<?php if ($item->presse or $item->presse2 or $item->presse3) : ?>
					<?php if ($this->params->get('presse_image','')) : ?>					
					<img class="eiko_icon_press" src="<?php echo JURI::Root();?><?php echo $this->params->get('presse_image','');?>" title="" />
					<?php else:?>
					<?php echo JText::_('COM_EINSATZKOMPONENTE_PRESSELINK'); ?>
					<?php endif;?>
					<?php endif;?>
				</td>
			<?php endif; ?>

				<?php if ($this->params->get('display_home_counter','1')) : ?>
		   <?php echo '<td class="mobile_hide_480 mobile_hide_counter"">';?>
		   <?php echo $item->counter;?>
		   <?php echo '</td>';?>
		   <?php endif;?>
		   </tr>
           
           <!-- Zusatzinformation -->
			<?php if ($this->params->get('display_home_info','1')) : ?>
			<?php		$data = array();
					foreach(explode(',',$item->auswahl_orga) as $value):
						$db = JFactory::getDbo();
						$query	= $db->getQuery(true);
						$query
							->select('name')
							->from('#__eiko_organisationen')
							->where('id = "' .$value.'"');
						$db->setQuery($query);
						$results = $db->loadObjectList();
						if(count($results)){
							$data[] = '<!-- <span class="label label-info"> --!>'.$results[0]->name.'<!-- </span>--!>'; 
						}
					endforeach;
					$auswahl_orga=  implode(' +++ ',$data); ?> 
            <tr id="tr<?php echo $item->id;?>" class="eiko_tr_zusatz_main_1" style=" display:none;" >
            
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
            <td colspan="<?php echo $col;?>" class="eiko_td_zusatz_main_1">
			<div id ="div<?php echo $item->id;?>" style="display:none;">
            <h3><?php echo JText::_('COM_EINSATZKOMPONENTE_ALARMIERUNGSZEIT');?> :</h3><?php echo date('d.m.Y', $curTime);?> um <?php echo date('H:i', $curTime);?> Uhr
            <h3><?php echo JText::_('COM_EINSATZKOMPONENTE_EINSATZKRAEFTE');?> :</h3><?php echo $auswahl_orga;?><br/>
		   <?php if ($item->desc) : ?>
			<?php jimport('joomla.html.content'); ?>  
			<?php $Desc = JHTML::_('content.prepare', $item->desc); ?>
			<h3><?php echo JText::_('COM_EINSATZKOMPONENTE_TITLE_MAIN_3');?> :</h3><?php echo $Desc;?>
            <?php endif;?>
            <br /><input type="button" class="btn-home" onClick="jQuery.toggle<?php echo $item->id;?>(div<?php echo $item->id;?>)" value="<?php echo JText::_('COM_EINSATZKOMPONENTE_INFO_SCHLIESSEN');?>"></input>
           <?php if ($this->params->get('display_home_links','1')) : ?>
            <a class="btn-home" href="<?php echo JRoute::_('index.php?option=com_einsatzkomponente'.$this->layout_detail_link.'&view=einsatzbericht&id=' . (int)$item->id); ?>"><?php echo JText::_('COM_EINSATZKOMPONENTE_DETAILS');?></a>
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
    <tr><td colspan="<?php echo $col;?>" class="eiko_td_gmap_main_1">
    <h4><?php echo JText::_('COM_EINSATZKOMPONENTE_EINSATZGEBIET');?></h4>
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
					<tr><td colspan="<?php echo $col;?>">
                    	<form action="#" method=post>
						<?php echo $this->pagination->getListFooter(); ?><!--Pagination anzeigen-->
						</form> 
					</td></tr>
		   			<?php endif;?><!--Prüfen, ob Pagination angezeigt wrden soll   ENDE -->

<?php if (!$this->params->get('eiko')) : ?>
        <tr><!-- Bitte das Copyright nicht entfernen. Danke. -->
            <th colspan="<?php echo $col;?>"><span class="copyright">Einsatzkomponente V<?php echo $this->version; ?>  (C) 2017 by Ralf Meyer ( <a class="copyright_link" href="http://einsatzkomponente.de" target="_blank">www.einsatzkomponente.de</a> )</span></th>
        </tr>
	<?php endif; ?>
    
</tfoot>
</table>
	</form> 

<?php
echo '<span class="mobile_hide_320">'.$this->modulepos_1.'</span>';
?>

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
		
}
?> 
