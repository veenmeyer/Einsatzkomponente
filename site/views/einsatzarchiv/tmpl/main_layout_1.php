<?php
/**
 * @version     3.1.0
 * @package     com_einsatzkomponente
 * @copyright   Copyright (C) 2014. Alle Rechte vorbehalten.
 * @license     GNU General Public License Version 2 oder später; siehe LICENSE.txt
 * @author      Ralf Meyer <ralf.meyer@einsatzkomponente.de> - http://einsatzkomponente.de
 */
// no direct access
defined('_JEXEC') or die;
//echo count ($this->items).' - '.count($this->all_items);
//print_r ($this->all_items);
?>

<?php echo '<span class="mobile_hide_320">'.$this->modulepos_2.'</span>';?>

<form action="<?php echo JRoute::_('index.php?option=com_einsatzkomponente&view=einsatzarchiv'); ?>" method="post" name="adminForm" id="adminForm">

    <?php echo JLayoutHelper::render('default_filter', array('view' => $this), dirname(__FILE__)); ?>
    <table class="table" id = "einsatzberichtList" >
        <thead >
            <tr class="mobile_hide_480 ">
			
				<?php if ($this->params->get('display_home_number','1') ) : ?>
				<th class='left'>
				<?php echo 'Nr.'; ?>
				</th>
				<?php endif;?>
				
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_EINSATZKOMPONENTE_EINSATZBERICHTE_DATE1', 'a.date1', $listDirn, $listOrder); ?>
				</th>
				
    			<th class='left'>
				<?php echo ''; ?>
				</th>
				
           <?php if ($this->params->get('display_home_image')) : ?>
				<th class='left mobile_hide_480 '>
				<?php echo JHtml::_('grid.sort',  'COM_EINSATZKOMPONENTE_EINSATZBERICHTE_IMAGE', 'a.image', $listDirn, $listOrder); ?>
				</th>
			<?php endif;?>
				
		<!--		<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_EINSATZKOMPONENTE_EINSATZBERICHTE_IMAGES', 'a.images', $listDirn, $listOrder); ?>
				</th> -->
				<th class='left mobile_hide_480'>
				<?php echo JHtml::_('grid.sort',  'COM_EINSATZKOMPONENTE_EINSATZBERICHTE_SUMMARY', 'a.summary', $listDirn, $listOrder); ?>
				</th>
				<?php if ($this->params->get('display_home_orga','0')) : ?>
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_EINSATZKOMPONENTE_EINSATZBERICHTE_AUSWAHLORGA', 'a.auswahl_orga', $listDirn, $listOrder); ?>
				</th> 
				<?php endif; ?>
				
				<?php if ($this->params->get('display_home_presse','0') ) : ?>
				<th class='left'>
				<?php echo 'Pressebericht'; ?>
				</th>
				<?php endif;?>
				
		<!--		<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_EINSATZKOMPONENTE_EINSATZBERICHTE_VEHICLES', 'a.vehicles', $listDirn, $listOrder); ?>
				</th> -->
				<?php if ($this->params->get('display_home_counter','1')) : ?>
				<th class='left mobile_hide_480 '>
				<?php echo JHtml::_('grid.sort',  'Zugriffe', 'a.counter', $listDirn, $listOrder); ?>
				</th>
				<?php endif;?>
		<!--		<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_EINSATZKOMPONENTE_EINSATZBERICHTE_CREATED_BY', 'a.created_by', $listDirn, $listOrder); ?>
				</th> -->


    <?php if (isset($this->items[0]->id)): ?>
      <!--  <th width="1%" class="nowrap center hidden-phone">
            <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
        </th> -->
    <?php endif; ?>

    		<?php if ($canEdit || $canDelete): ?>
             <?php if (isset($this->items[0]->state)): ?>
				<th width="1%" class="nowrap center">
				<?php echo JHtml::_('grid.sort', 'Actions', 'a.state', $listDirn, $listOrder); ?>
				</th>
			<?php endif; ?>  
			<?php endif; ?>
	

    </tr>
    </thead>
	
    <tbody>
	
	<?php 	$m ='';
			$y=''; //print_r ($this->items);
	?>
    <?php foreach ($this->items as $i => $item) : ?>
        <?php $canEdit = $user->authorise('core.edit', 'com_einsatzkomponente'); ?>

        				<?php if (!$canEdit && $user->authorise('core.edit.own', 'com_einsatzkomponente')): ?>
					<?php $canEdit = JFactory::getUser()->id == $item->created_by; ?>
				<?php endif; ?>

           <!--Anzeige des Jahres-->
           <?php if ($item->date1_year != $y&& $this->params->get('display_home_jahr','1')) : ?>
		   <tr class="eiko_einsatzarchiv_jahr_tr"><td class="eiko_einsatzarchiv_jahr_td" colspan="7">
           <?php $y= $item->date1_year;?>
		   <?php echo '<div class="eiko_einsatzarchiv_jahr_div">';?>
           <?php echo 'Einsatzberichte '. $item->date1_year.' :';?> 
           <?php echo '</div>';?>
           </td></tr>
           <?php endif;?>
           <!--Anzeige des Jahres ENDE-->

           <!--Anzeige des Monatsnamen-->
           <?php if ($item->date1_month != $m && $this->params->get('display_home_monat','1')) : ?>
		   <tr class="eiko_einsatzarchiv_monat_tr"><td class="eiko_einsatzarchiv_monat_td" colspan="7">
           <?php $m= $item->date1_month;?>
		   <?php echo '<div class="eiko_einsatzarchiv_monat_div">';?>
           <?php echo 'Monat: <b>'.$this->monate[$m].'</b>';?>
           <?php echo '</div>';?>
           </td></tr>
           <?php endif;?>
           <!--Anzeige des Monatsnamen ENDE-->

		   <tr class="row<?php echo $i % 2; ?>">

           <?php if ($this->params->get('display_home_number','1') ) : ?>
           <?php if ($this->params->get('display_home_marker','1')) : ?>
		   <td class="eiko_td_marker_main_1" style="background-color:<?php echo $item->marker;?>;" >
           <?php else:?>
		   <td class="eiko_td_marker_main_1">
           <?php endif;?>
			<?php echo '<span style="white-space: nowrap;" class="eiko_span_marker_main_1">Nr. '.EinsatzkomponenteHelper::ermittle_einsatz_nummer($item->date1).'</span>';?> 
			</td>
           <?php endif;?>
		   
		   
           <?php if ($this->params->get('display_home_date_image','1')=='1') : ?>
		   <td class="eiko_td_kalender_main_1"> 
			<div class="home_cal_icon">
			<div class="home_cal_monat"><?php echo date('M', $item->date1);?></div>
			<div class="home_cal_tag"><?php echo date('d', $item->date1);?></div>
			<div class="home_cal_jahr"><span style="font-size:10px;"><?php echo date('Y', $item->date1);?></span></div>
			</div>
           </td>
           <?php endif;?>
           <?php if ($this->params->get('display_home_date_image','1')=='2') : ?>
		   <td class="eiko_td_datum_main_1"> <?php echo date('d.m.Y ', $item->date1);?><br /><?php echo date('H:i ', $item->date1); ?>Uhr</td>
           <?php endif;?>
           <?php if ($this->params->get('display_home_date_image','1')=='0') : ?>
		   <td class="eiko_td_datum_main_1"> <?php echo date('d.m.Y ', $item->date1);?></td>
           <?php endif;?>
           <?php if ($this->params->get('display_home_date_image','1')=='3') : ?>
		   <td class="eiko_td_kalender_main_1"> 
			<div class="home_cal_icon">
			<div class="home_cal_monat"><?php echo date('M', $item->date1);?></div>
			<div class="home_cal_tag"><?php echo date('d', $item->date1);?></div>
			<div class="home_cal_jahr"><span style="font-size:10px;"><?php echo date('Y', $item->date1);?></span></div>
			 <?php echo '<div style="font-size:12px;white-space: nowrap;">'.date('H:i ', $item->date1).' Uhr</div>'; ?>
			</div>
           </td>
           <?php endif;?>

            	<td>
					<?php if (isset($item->checked_out) && $item->checked_out) : ?>
					<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'einsatzarchiv.', $canCheckin); ?>
					<?php endif; ?> 
					<a href="<?php echo JRoute::_('index.php?option=com_einsatzkomponente&view=einsatzbericht&id='.(int) $item->id); ?>">
					
					<?php if ($this->params->get('display_home_alertimage','0')) : ?>
					<img class="eiko_icon hasTooltip" src="<?php echo JURI::Root();?><?php echo $item->alerting_image;?>" title="Alarmierung über: <?php echo $item->alerting;?>" />
					<?php endif;?>
					<?php if ($this->params->get('display_list_icon')) : ?>
					<img class="eiko_icon hasTooltip" src="<?php echo JURI::Root();?><?php echo $item->list_icon;?>" alt="<?php echo $item->list_icon;?>" title="Einsatzart: <?php echo $item->data1;?>"/>
					<?php endif;?>
					<?php if ($this->params->get('display_tickerkat_icon')) : ?>
					<img class="eiko_icon hasTooltip" src="<?php echo JURI::Root();?><?php echo $item->tickerkat_image;?>" alt="<?php echo $item->tickerkat;?>" title="Kategorie: <?php echo $item->tickerkat;?>"/>
					<?php endif;?>
					
					<span class="eiko_nowrap"><b><?php echo $item->data1; ?></b></span></a>
					<br/>
					<?php if ($item->address): ?>
					<?php //echo '<i class="icon-arrow-right"></i> '.$this->escape($item->address); ?>
					<?php echo ''.$this->escape($item->address); ?>
					<br/>
					<?php endif;?>

					

				</td>
				
           <?php if ($this->params->get('display_home_image')) : ?>
		   <td class="mobile_hide_480  eiko_td_einsatzbild_main_1">
		   <?php if ($item->image) : ?>
					<?php if (isset($item->checked_out) && $item->checked_out) : ?>
					<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'einsatzarchiv.', $canCheckin); ?>
					<?php endif; ?> 
					<a href="<?php echo JRoute::_('index.php?option=com_einsatzkomponente&view=einsatzbericht&id='.(int) $item->id); ?>">
		   <img  class="img-rounded eiko_img_einsatzbild_main_1" style="width:<?php echo $this->params->get('display_home_image_width','80px');?>;" src="<?php echo JURI::Root();?><?php echo $item->image;?>"/>
					</a>
           <?php endif;?>
		   </td>
           <?php endif;?>
		<!--		<td>

					<?php echo $item->images; ?>
				</td> -->
				
				<td class="mobile_hide_480">

					<?php echo $item->summary; ?>
				</td>
				
				
           <?php if ($this->params->get('display_home_orga','0')) : ?>
           <?php 					
					$data = array();
					foreach(explode(',',$item->auswahl_orga) as $value):
						if($value){
							$data[] = '<!-- <span class="label label-info"> --!>'.$value.'<!-- </span>--!>'; 
						}
					endforeach;
					$auswahl_orga=  implode('</br>',$data); 
?>
		   <td nowrap class="eiko_td_organisationen_main_1 mobile_hide_480"> <?php echo $auswahl_orga;?></td>
           <?php endif;?>
		   
				<?php if ($this->params->get('display_home_presse','0')) : ?>
				<td class="mobile_hide_480 ">
					<?php if ($item->presse or $item->presse2 or $item->presse3) : ?>
					<?php echo '+Presselinks'; ?>
					<?php endif;?>
				</td>
				<?php endif; ?>

				<!--		<td>

					<?php echo $item->vehicles; ?>
				</td> -->
				
				<?php if ($this->params->get('display_home_counter','1')) : ?>
				<td class="mobile_hide_480 ">

					<?php echo $item->counter; ?>
				</td>
				<?php endif; ?>
				
		<!--		<td>

							<?php echo JFactory::getUser($item->created_by)->name; ?>				</td> -->


            <?php if (isset($this->items[0]->id)): ?>
          <!--      <td class="center hidden-phone">
                    <?php echo (int)$item->id; ?>
                </td> -->
            <?php endif; ?>

			
            <?php if (isset($this->items[0]->state)): ?>
                <?php $class = ($canEdit || $canChange) ? 'active' : 'disabled'; ?>
                <td class="center">
					<?php if ($canEdit): ?>
                    <a class="btn btn-micro <?php echo $class; ?>"
                       href="<?php echo ($canEdit || $canChange) ? JRoute::_('index.php?option=com_einsatzkomponente&task=einsatzberichtform.publish&id=' . $item->id . '&state=' . (($item->state + 1) % 2), false, 2) : '#'; ?>">
                        <?php if ($item->state == 1): ?>
                            <i class="icon-publish"></i>
                        <?php else: ?>
                            <i class="icon-unpublish"></i>
                        <?php endif; ?>
                    </a>
					<?php endif; ?>
						<?php if ($canEdit): ?>
							<a href="<?php echo JRoute::_('index.php?option=com_einsatzkomponente&view=einsatzberichtform&layout=edit&id=' . $item->id, false, 2); ?>" class="btn btn-mini" type="button"><i class="icon-edit" ></i></a>
						<?php endif; ?>
						<?php if ($canDelete): ?>
							<button data-item-id="<?php echo $item->id; ?>" class="btn btn-mini delete-button" type="button"><i class="icon-trash" ></i></button>
						<?php endif; ?>
                </td>
            <?php endif; ?>

        </tr>
		
		
    <?php endforeach; ?>
    </tbody>
	
    <tfoot>
    				<!--Prüfen, ob Pagination angezeigt werden soll-->
    				<?php if ($this->params->get('display_home_pagination')) : ?>
					<tr>
					<td colspan="<?php echo isset($this->items[0]) ? count(get_object_vars($this->items[0])) : 10; ?>">
                    	<form action="#" method=post>
						<?php echo $this->pagination->getListFooter(); ?><!--Pagination anzeigen-->
						</form> 
					</td></tr>
		   			<?php endif;?><!--Prüfen, ob Pagination angezeigt werden soll   ENDE -->
		
		<?php if (!$this->params->get('eiko')) : ?>
        <tr><!-- Bitte das Copyright nicht entfernen. Danke. -->
        <td colspan="<?php echo isset($this->items[0]) ? count(get_object_vars($this->items[0])) : 10; ?>">
			<span class="copyright">Einsatzkomponente V<?php echo $this->version; ?>  (C) 2015 by Ralf Meyer ( <a class="copyright_link" href="http://einsatzkomponente.de" target="_blank">www.einsatzkomponente.de</a> )</span></td>
        </tr>
	<?php endif; ?>
    </tfoot>

    </table>

    <?php if ($canCreate): ?>
        <a href="<?php echo JRoute::_('index.php?option=com_einsatzkomponente&view=einsatzberichtform&layout=edit&id=0', false, 2); ?>"
           class="btn btn-success btn-small"><i
                class="icon-plus"></i> <?php echo JText::_('Einsatz einreichen'); ?></a>
    <?php endif; ?>

    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
    <?php echo JHtml::_('form.token'); ?>
</form>



    <?php if ($this->params->get('display_home_map')) : ?>
    <tr><td colspan="<?php echo $col;?>" class="eiko_td_gmap_main_1">
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
	

	
<?php echo '<span class="mobile_hide_320">'.$this->modulepos_1.'</span>'; ?>


<script type="text/javascript">

    jQuery(document).ready(function () {
        jQuery('.delete-button').click(deleteItem);
    });

    function deleteItem() {
        var item_id = jQuery(this).attr('data-item-id');
        if (confirm("<?php echo JText::_('Möchten Sie diesen Einsatzbericht wirklich löschen ?'); ?>")) {
            window.location.href = '<?php echo JRoute::_('index.php?option=com_einsatzkomponente&task=einsatzberichtform.remove&id=', false, 2) ?>' + item_id;
        }
    }
</script>


