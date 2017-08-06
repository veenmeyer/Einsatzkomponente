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

//print_r($this->pagination);
//echo $this->selectedYear;
?>

<div>

<!--RSS-Feed Imag-->
<?php if ($this->params->get('display_home_rss','1')) : ?>
<div style="float:right;" ><a href="<?php echo JRoute::_('index.php?option=com_einsatzkomponente'.$this->layout_detail_link.'&task=einsatzberichte'); ?>&format=feed&type=rss"><span class="icon-feed" style="color:#cccccc;font-size:24px;"> </span> </a></div>
<?php endif; ?>

<!--Page Heading-->
<?php if ($this->params->get('show_page_heading', 1)) : ?>
<div class="page-header">
<h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
</div>
<?php endif;?>



<?php // Filter ------------------------------------------------------------------------------------
	

?>
<form action="<?php echo JRoute::_('index.php?option=com_einsatzkomponente&view=einsatzberichte&list=1'); ?>" method="post" name="adminForm" id="adminForm">
<?php
if (!$this->params->get('anzeigejahr')) : 
	$years[] = JHTML::_('select.option', '', JTEXT::_('Bitte das Jahr auswählen')  , 'id', 'title');
	$years[] = JHTML::_('select.option', '9999', JTEXT::_('Alle Einsätze anzeigen')  , 'id', 'title');
	$years = array_merge($years, (array)$this->years);
	 
	echo JHTML::_('select.genericlist',  $years, 'year', ' onchange=submit(); ', 'id', 'title', $this->selectedYear);?>
	
    <?php
endif;
	$einsatzarten[] = JHTML::_('select.option', '', JTEXT::_('alle Einsatzarten')  , 'id', 'title');
	$einsatzarten = array_merge($einsatzarten, (array)$this->einsatzarten);
	?><?php 
	echo JHTML::_('select.genericlist',  $einsatzarten, 'selectedEinsatzart', ' onchange=submit(); ', 'id', 'title', $this->selectedEinsatzart);?> 
    <?php
	if (!$this->params->get('abfragewehr','0') and $this->params->get('display_filter_organisationen','1')) : 
	$organisationen[] = JHTML::_('select.option', '', JTEXT::_('alle Organisationen')  , 'id', 'name');
	$organisationen = array_merge($organisationen, (array)$this->organisationen);
	?><?php 
	echo JHTML::_('select.genericlist',  $organisationen, 'selectedOrga', ' class="eiko_select_organisation_main_1" onchange=submit(); ', 'id', 'name', $this->selectedOrga);
	endif;?>
</div>
<?php // Filter ENDE   -------------------------------------------------------------------------------
 
$show = true;

echo $this->modulepos_2;

?>
<br />
<br />

<table width="100%" class="table table-striped table-bordered" id="example" border="0" cellspacing="0" cellpadding="0">
    
 <tbody>
	 <?php 
if ($this->params->get('display_home_pagination')) :
     $i=$this->pagination->total - $this->pagination->limitstart+1;
	 else:
     $i=count($this->reports)+1;
	 endif;

	 $hide=0;
     foreach ($this->reports as $item) :
		   $i--; 
		   $curTime = strtotime($item->date1);

		   ?>
          <!-- Filter State-->
		  <?php if($item->state == '1'): ?>
          <!-- Filter Einsatzart-->
		  <?php if(preg_match('/\b'.$this->selectedOrga.'\b/',$item->auswahl_orga)==true or $this->selectedOrga == '0'): ?>
		  <?php if ($this->selectedEinsatzart == $item->data1 or $this->selectedEinsatzart == '' ) : ?>
          
          
<h2>

           <?php if ($this->params->get('display_home_date_image','1')=='1') : ?>
			<div class="home_cal_icon">
			<div class="home_cal_monat"><?php echo date('M', $curTime);?></div>
			<div class="home_cal_tag"><?php echo date('d', $curTime);?></div>
			<div class="home_cal_jahr"><span style="font-size:10px;"><?php echo date('Y', $curTime);?></span></div>
			</div>
           <?php endif;?>
           <?php if ($this->params->get('display_home_date_image','1')=='2') : ?>
		   <?php echo date('d.m.Y ', $curTime);?><br /><?php echo date('H:i ', $curTime).' Uhr || '; ?>
           <?php endif;?>
           <?php if ($this->params->get('display_home_date_image','1')=='0') : ?>
		   <?php echo date('d.m.Y ', $curTime).' || ';?>
           <?php endif;?>

		   

<?php echo $item->summary;?>
</h2>
<hr align="left" />

       	   <?php if ($this->params->get('display_home_image')) : ?>
<div class="eiko_sidepicture_layout1">
		   <?php if ($item->foto) : ?>
		   <?php if ($this->params->get('display_home_links')) : ?>
			<a href="<?php echo JRoute::_('index.php?option=com_einsatzkomponente'.$this->layout_detail_link.'&view=einsatzbericht&id=' . (int)$item->id); ?>">
           <?php endif;?>
		   <img  class="img-rounded" style="width:<?php echo $this->params->get('display_home_image_width','120px');?>;" src="<?php echo JURI::Root();?><?php echo $item->foto;?>"/>
		   <?php if ($this->params->get('display_home_links')) : ?>
		   </a>
           <?php endif;?>
           <?php endif;?>
		   <?php if (!$item->foto) : ?>
           <?php if (!$this->params->get('display_home_image_nopic','0')) : ?>
           <?php else:?>
		   <?php if ($this->params->get('display_home_links_3','0')) : ?>
		   <a href="<?php echo JRoute::_('index.php?option=com_einsatzkomponente'.$this->layout_detail_link.'&view=einsatzbericht&id=' . (int)$item->id); ?>">
           <?php endif;?>
           <img  class="img-rounded" style="width:<?php echo $this->params->get('display_home_image_width','120px');?>;" src="<?php echo JURI::Root().'images/com_einsatzkomponente/einsatzbilder/nopic.png';?>"/>
		   <?php if ($this->params->get('display_home_links_3','0')) : ?>
           </a>
           <?php endif;?>
           <?php endif;?>
           <?php endif;?>
</div>
           <?php endif;?>
<p>
<?php
$string = $item->desc;
//$rSummary = mb_strlen($rSummarylong) > '40' ? mb_strlen($rSummarylong, 0, '40').'...' : $rSummarylong;
$string = preg_replace("/[^ ]*$/", '', substr($string, 0, 200));
echo $string;
?>
    </p>
    <?php if ($this->params->get('display_home_links')) : ?>
    <a href="<?php echo JRoute::_('index.php?option=com_einsatzkomponente'.$this->layout_detail_link.'&view=einsatzbericht&id=' . (int)$item->id); ?>">
    [weiterlesen]
    </a>
    <?php endif;?>
    <br /><br />
           
		   
		   
		   
		   <?php endif;?><?php endif;?><!-- Filter Einsatzart-->
           <?php endif;?><!-- Filter State-->
		   <?php if($item->state == '2'): ?>
           <?php $hide++;$i--;?>
           <?php endif;?> 
		  
    <?php endforeach; ?>

    <?php if ($this->params->get('display_home_map')) : ?> 
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
    <?php endif;?>
    
 </tbody>   
    
    
    <tfoot>
    				<!--Prüfen, ob Pagination angezeigt wrden soll-->
    				<?php if ($this->params->get('display_home_pagination')) : ?>
    				<!--Prüfen, ob Einsatzart ausgwählt ist -->
                    <?php if ($this->selectedEinsatzart == '' or $this->selectedEinsatzart == 'alle Einsatzarten') : ?>
					<tr><td colspan="10">
                    	<form action="#" method=post>
						<?php echo $this->pagination->getListFooter(); ?><!--Pagination anzeigen-->
						</form>
					</td></tr>
		   			<?php endif;?><!--Prüfen, ob Einsatzart ausgwählt ist ENDE-->
		   			<?php endif;?><!--Prüfen, ob Pagination angezeigt wrden soll   ENDE -->
   
<?php if (!$this->params->get('eiko')) : ?>
        <tr><!-- Bitte das Copyright nicht entfernen. Danke. -->
            <th colspan="6"><span class="copyright">Einsatzkomponente V<?php echo $this->version; ?>  (C) 2017 by Ralf Meyer ( <a class="copyright_link" href="http://einsatzkomponente.de" target="_blank">www.einsatzkomponente.de</a> )</span></th>
        </tr>
<?php endif;?>

    </tfoot>
</table>
	</form>
<?php echo $this->modulepos_1;?>


<?php if(JFactory::getUser()->authorise('core.create','com_einsatzkomponente.einsatzbericht'.$item->id)): ?><a href="<?php echo JRoute::_('index.php?option=com_einsatzkomponente&view=einsatzberichtform&layout=edit&id=0'); ?>">Einsatz eintragen</a>
	<?php endif; ?>
