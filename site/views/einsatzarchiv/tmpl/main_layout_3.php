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
?>

<?php echo '<span class="mobile_hide_320">'.$this->modulepos_2.'</span>';?>

<form action="<?php echo JRoute::_('index.php?option=com_einsatzkomponente&view=einsatzarchiv'); ?>" method="post" name="adminForm" id="adminForm">

   <?php echo JLayoutHelper::render('default_filter', array('view' => $this), dirname(__FILE__)); ?>
   
    <table class="table" id = "einsatzberichtList" >
        <thead >
            <tr class="mobile_hide_480 ">
			
				<?php $eiko_col = 0;?>
				
				<?php if ($this->params->get('display_home_number','1') ) : ?>
				<th class='left'>
				<!--<?php echo JText::_('COM_EINSATZKOMPONENTE_NR'); ?>-->
				<?php $eiko_col = $eiko_col+1;?>
				</th>
				<?php endif;?>
				
				<th class='left'>
				<!--<?php echo JHtml::_('grid.sort',  'COM_EINSATZKOMPONENTE_EINSATZBERICHTE_DATE1', 'a.date1', $listDirn, $listOrder); ?>-->
				<?php $eiko_col = $eiko_col+1;?>
				</th>
				
				<?php if ($this->params->get('display_home_image')) : ?>
				<th class='left mobile_hide_480 '>
				<!--<?php echo JHtml::_('grid.sort',  'COM_EINSATZKOMPONENTE_EINSATZBERICHTE_IMAGE', 'a.image', $listDirn, $listOrder); ?>-->
				<?php $eiko_col = $eiko_col+1;?>
				</th>
				<?php endif;?>
				
		
			   <?php if ($canEdit || $canDelete): ?>
               <?php if (isset($this->items[0]->state)): ?>
			   <th width="1%" class="nowrap center">
				<?php echo JText::_('Actions'); ?>
				<?php $eiko_col = $eiko_col+1;?>
			   </th>
			   <?php endif; ?>  
		       <?php endif; ?>
	
			</tr>
    <?php if ($canCreate): ?>
        <tr>
        <td colspan="<?php echo $eiko_col;?>">
        <a href="<?php echo JRoute::_('index.php?option=com_einsatzkomponente&view=einsatzberichtform&layout=edit&id=0&addlink=1', false, 2); ?>"
           class="btn btn-success btn-small"><i
                class="icon-plus"></i> <?php echo JText::_('COM_EINSATZKOMPONENTE_ADD'); ?></a>
		</td></tr>
    <?php endif; ?>
		</thead>
		
	    <tbody>
	
				<?php 	
					$m ='';
					$y=''; 	
				?>
						
    <?php foreach ($this->items as $i => $item) 
	{ ?>
	
        <?php $canEdit = $user->authorise('core.edit', 'com_einsatzkomponente'); ?>
		
        <?php if (!$canEdit && $user->authorise('core.edit.own', 'com_einsatzkomponente'))
				{ 
					$canEdit = JFactory::getUser()->id == $item->created_by; 
				} ?>
		
           <!--Anzeige des Jahres-->
           <?php if ($item->date1_year != $y && $this->params->get('display_home_jahr','1')) : ?>
		   <tr class="eiko_einsatzarchiv_jahr_tr"><td class="eiko_einsatzarchiv_jahr_td" colspan="<?php echo $eiko_col;?>">
           <?php $y= $item->date1_year;?>
		   <?php echo '<div class="eiko_einsatzarchiv_jahr_div">';?>
           <?php echo JText::_('COM_EINSATZKOMPONENTE_TITLE_MAIN_3').' '. $item->date1_year.''; ?>  
           <?php echo '</div>';?>
           </td></tr>
           <?php endif;?>
           <!--Anzeige des Jahres ENDE-->

           <!--Anzeige des Monatsnamen-->
           <?php if ($item->date1_month != $m && $this->params->get('display_home_monat','1')) : ?>
		   <tr class="eiko_einsatzarchiv_monat_tr"><td class="eiko_einsatzarchiv_monat_td" colspan="<?php echo $eiko_col;?>">
           <?php $m= $item->date1_month;?>
		   <?php echo '<div class="eiko_einsatzarchiv_monat_div">';?>
           <?php echo '<b>'. $this->monate[$m].'</b>'; ?>
           <?php echo '</div>';?>
           </td></tr>
           <?php endif;?>
           <!--Anzeige des Monatsnamen ENDE-->

		   <tr class="row<?php echo $i % 2; ?>">

           <?php if ($this->params->get('display_home_number','1') ) : ?>
           <?php if ($this->params->get('display_home_marker','1')) : ?>
		   <td class="eiko_td_marker_main_1" style="width:35px;background-color:<?php echo $item->marker;?>;" >
           <?php else:?>
		   <td class="eiko_td_marker_main_1">
           <?php endif;?>
			<?php echo '<span style="white-space: nowrap;margin-left:5px !important;" class="eiko_span_marker_main_1">'.JText::_('COM_EINSATZKOMPONENTE_NR').' '.EinsatzkomponenteHelper::ermittle_einsatz_nummer($item->date1,$item->data1_id).'</span>';?> 
			</td>
           <?php endif;?>
		   
		   
		   
		   
		   <td class="eiko_td_datum_main_1" style="max-width:200px !important;"> 
		   
					<?php if ($this->params->get('display_home_links','1')) : ?>
		   			<a href="<?php echo JRoute::_('index.php?option=com_einsatzkomponente&view=einsatzbericht&id='.(int) $item->id); ?>">
					<?php endif;?>
					<span class="eiko_nowrap eiko_data1"><b><?php echo $item->data1; ?></b></span>
					<?php if ($this->params->get('display_home_links','1')) : ?>
					</a>
					<?php endif;?>
					<?php if ($this->params->get('display_home_alertimage','0')) : ?>
					<img class="eiko_icon_3 " src="<?php echo JURI::Root();?><?php echo $item->alerting_image;?>" title="<?php echo JText::_('COM_EINSATZKOMPONENTE_ALARMIERUNG_UEBER'); ?>: <?php echo $item->alerting;?>" />
					<?php endif;?>
					<?php if ($this->params->get('display_list_icon')) : ?>
					<img class="eiko_icon_3 " src="<?php echo JURI::Root();?><?php echo $item->list_icon;?>" alt="<?php echo $item->list_icon;?>" title="<?php echo JText::_('COM_EINSATZKOMPONENTE_EINSATZART'); ?>: <?php echo $item->data1;?>"/>
					<?php endif;?>
					<?php if ($this->params->get('display_tickerkat_icon')) : ?>
					<img class="eiko_icon_3 " src="<?php echo JURI::Root();?><?php echo $item->tickerkat_image;?>" alt="<?php echo $item->tickerkat;?>" title="<?php echo JText::_('COM_EINSATZKOMPONENTE_KATEGORIE'); ?>: <?php echo $item->tickerkat;?>"/>
					<?php endif;?>
					<br /></br>
					
					<!-- Einsatzstärke -->
					<?php if ($this->params->get('display_home_einsatzstaerke','1')) { ?>
					<?php if ($item->people) : $people = $item->people; endif;?>
					<?php if (!$item->people) : $people = '0'; endif;?>
		  			<?php $vehicles = explode (",",$item->vehicles);?>
					<?php $vehicles = count($vehicles); ?>
		  			<?php $auswahl_orga = explode (",",$item->auswahl_orga);?>
					<?php $auswahl_orga = count($auswahl_orga); ?>
					<?php $strength = ($people*$this->params->get('einsatzstaerke_people','0.5')) + ($vehicles*$this->params->get('einsatzstaerke_vehicles','2')) + ($auswahl_orga*$this->params->get('einsatzstaerke_orga','15')) ; ?>
					<div class="progress progress-danger progress-striped " style="margin-top:-12px;margin-bottom:5px;color:#000000 !important;width:180px;" title="<?php echo JText::_('COM_EINSATZKOMPONENTE_EINSATZKRAFT'); ?>: <?php if ($auswahl_orga) :echo $auswahl_orga;?> <?php echo JText::_('COM_EINSATZKOMPONENTE_ORGANISATIONEN'); ?> //<?php endif;?> <?php if ($vehicles):echo $vehicles;?> <?php echo JText::_('COM_EINSATZKOMPONENTE_EINSATZFAHRZEUGE'); ?> <?php endif;?><?php if ($people) :echo '// '.$people;?> Einsatzkräfte <?php endif;?>"> 
					<div class="bar" style="color:#000000 !important;width:<?php echo $strength;?>px"></div></div>
					<?php } ?>
					<!-- Einsatzstärke ENDE --> 

					
					<?php echo  '<i class="icon-arrow-right-4" ></i> <b>'.$item->summary.'</b>'; ?>
					<br />
					<?php echo '<i class="icon-calendar" ></i> '.date('d.m.Y ', $item->date1);?>
					<br />
					<?php echo '<i class="icon-clock" ></i> '.date('H:i ', $item->date1); ?>Uhr		
					</br>	
					
					<?php if ($item->address): ?>
					<?php echo '<i class="icon-location" ></i> '.$this->escape($item->address); ?>
				    </br>			
					<?php endif;?>
					
					<?php if ($this->params->get('display_home_image')) : ?>
						</br>	
						<?php $images =$item->images;?>
						<?php echo '<i class="icon-image" ></i> '.JText::_('COM_EINSATZKOMPONENTE_EINSATZFOTOS').': '.$images; ?>
					<?php endif;?>

					<?php if ($this->params->get('display_home_presse','0')) : ?>
						<?php if ($item->presse or $item->presse2 or $item->presse3) : ?>
							</br>	
							
					<?php if ($this->params->get('presse_image','')) : ?>					
					<img class="eiko_icon_press" src="<?php echo JURI::Root();?><?php echo $this->params->get('presse_image','');?>" title="" />
					<?php else:?>
							
							<?php echo '<i class="icon-file-2" ></i> '.JText::_('COM_EINSATZKOMPONENTE_WEITERE_PRESSELINKS').''; ?>
					<?php endif;?>					
						<?php endif;?>
					<?php endif; ?>
			
					<?php if ($this->params->get('display_home_counter','1')) : ?>
						</br>	
						<?php echo '<span class="" title="Dieser Bericht wurde bereits '.$item->counter.' mal gelesen." ><i class="icon-eye" ></i> '.JText::_('COM_EINSATZKOMPONENTE_ZUGRIFFE').': '.$item->counter.'</span>'; ?>
					<?php endif; ?>
</br>
					<!-- Button Kurzinfo --> 
					<?php if ($this->params->get('display_home_info','1')) : ?>
					<input type="button" class="btn btn-info" onClick="jQuery.toggle<?php echo $item->id;?>(div<?php echo $item->id;?>)" value="<?php echo JTEXT::_('COM_EINSATZKOMPONENTE_KURZINFO');?>"></input>
					<script type="text/javascript">
					jQuery.toggle<?php echo $item->id;?> = function(query)
						{
						jQuery(query).slideToggle("5000");
						jQuery("#tr<?php echo $item->id;?>").fadeToggle("fast");
						}   
					</script>
					<?php endif;?>
					
				<!-- Button Detaillink --> 
				<?php if ($this->params->get('display_home_links','1')) : ?>
				<a href="<?php echo JRoute::_('index.php?option=com_einsatzkomponente&view=einsatzbericht&id='.(int) $item->id); ?>" type="button" class="btn btn-primary"><?php echo JText::_('COM_EINSATZKOMPONENTE_DETAILS'); ?></a>	
				<?php endif;?>
			</td>
			

				
          <?php if ($this->params->get('display_home_image')) : ?>
		  <td class="mobile_hide_480  eiko_td_einsatzbild_main_1">
		   
			<?php if ($item->image) : ?>
					<?php if (isset($item->checked_out) && $item->checked_out) : ?>
						<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'einsatzarchiv.', $canCheckin); ?>
					<?php endif; ?> 
					
				<?php if ($this->params->get('display_home_links_3','0')) : ?>
					<a href="<?php echo JRoute::_('index.php?option=com_einsatzkomponente&view=einsatzbericht&id='.(int) $item->id); ?>">
				<?php endif; ?> 
						<img  class="img-rounded eiko_img_einsatzbild_main_1" style="width:<?php echo $this->params->get('display_home_image_width','150px');?>;" src="<?php echo JURI::Root();?><?php echo $item->image;?>"/> 
				<?php if ($this->params->get('display_home_links_3','0')) : ?>
					</a>
				<?php endif; ?> 
           <?php endif;?>
			<?php if (!$item->image AND $this->params->get('display_home_image_nopic','0')) : ?>
					<?php if (isset($item->checked_out) && $item->checked_out) : ?>
						<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'einsatzarchiv.', $canCheckin); ?>
					<?php endif; ?> 
					
				<?php if ($this->params->get('display_home_links_3','0')) : ?>
					<a href="<?php echo JRoute::_('index.php?option=com_einsatzkomponente&view=einsatzbericht&id='.(int) $item->id); ?>">
				<?php endif; ?> 
					<img  class="img-rounded eiko_img_einsatzbild_main_1" style="width:<?php echo $this->params->get('display_home_image_width','150px');?>;" src="<?php echo JURI::Root().'images/com_einsatzkomponente/einsatzbilder/nopic.png';?>"/>
				<?php if ($this->params->get('display_home_links_3','0')) : ?>
					</a>
				<?php endif; ?> 
           <?php endif;?>
		   
		   
		  <?php if ($this->params->get('gmap_action','0') == '1') :?>
			<?php if ($item->gmap & $item->gmap_report_latitude): ?>
				</br></br>
				<img class="img-rounded eiko_karte_klein" src="https://maps.googleapis.com/maps/api/staticmap?center=<?php echo $item->gmap_report_latitude;?>,<?php echo $item->gmap_report_longitude;?>&zoom=14&size=150x84&maptype=roadmap&markers=color:red%7Clabel:x%7C<?php echo $item->gmap_report_latitude;?>,<?php echo $item->gmap_report_longitude;?>&key=<?php echo $this->params->get ("gmapkey","AIzaSyAuUYoAYc4DI2WBwSevXMGhIwF1ql6mV4E");?>" width="<?php echo $this->params->get('display_home_image_width','150px');?>;" alt="Einsatzkarte <?php echo $item->summary;?>">		  
			<?php endif;?>
		<?php endif;?>
		
		</td> 
		<?php endif;?>
				
		   

				
				



		  <?php if ($canEdit || $canDelete): ?>
            <?php if (isset($this->items[0]->state)): ?>
                <?php $class = ($canEdit || $canChange) ? 'active' : 'disabled'; ?>
                <td class="center">
					<?php if ($canEdit): ?>
                    <a class="btn btn-mini <?php echo $class; ?>"
                       href="<?php echo ($canChange) ? JRoute::_('index.php?option=com_einsatzkomponente&task=einsatzberichtform.publish&id=' . $item->id . '&state=' . (($item->state + 1) % 2), false, 2) : '#'; ?>">
                        <?php if ($item->state == 1): ?>
                            <i class="icon-save"></i>
                        <?php else: ?>
                            <i class="icon-radio-unchecked"></i>
                        <?php endif; ?>
                    </a>
					<?php endif; ?>
						<?php if ($canEdit): ?>
							<a href="<?php echo JRoute::_('index.php?option=com_einsatzkomponente&task=einsatzberichtform.edit&layout=edit&id=' . $item->id, true, 2); ?>" class="btn btn-mini eiko_action_button" type="button"><i class="icon-edit" ></i></a>
						<?php endif; ?>
						<?php if ($canDelete): ?>
							<button data-item-id="<?php echo $item->id; ?>" class="btn btn-mini delete-button" type="button"><i class="icon-trash" ></i></button>
						<?php endif; ?>
                </td>
            <?php endif; ?>
            <?php endif; ?>

        </tr>
		
        <!-- Zusatzinformation Kurzinfo -->
		<?php if ($this->params->get('display_home_info','1')) : ?>
			<?php		
					$data = array();
					foreach(explode(',',$item->auswahl_orga) as $value):
						if($value){
							$data[] = '<!-- <span class="label label-info"> --!>'.$value.'<!-- </span>--!>'; 
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
            <td colspan="<?php echo $eiko_col-1; ?>" class="eiko_td_zusatz_main_1">
			<div id ="div<?php echo $item->id;?>" style="display:none;">
            <h3 style="text-decoration:underline;"><?php echo JText::_('COM_EINSATZKOMPONENTE_ALARMIERUNGSZEIT');?> </h3><?php echo date('d.m.Y', $item->date1);?> um <?php echo date('H:i', $item->date1);?> Uhr
            <h3 style="text-decoration:underline;"><?php echo JText::_('COM_EINSATZKOMPONENTE_EINSATZKRAEFTE');?> </h3><?php echo $auswahl_orga;?><br/>
		   <?php if ($item->desc) : ?>
			<?php jimport('joomla.html.content'); ?>  
			<?php $Desc = JHTML::_('content.prepare', $item->desc); ?>
			<h3 style="text-decoration:underline;"><?php echo JText::_('COM_EINSATZKOMPONENTE_TITLE_MAIN_3');?> </h3><?php echo $Desc;?>
            <?php endif;?>
            <br /><input type="button" class="btn btn-info" onClick="jQuery.toggle<?php echo $item->id;?>(div<?php echo $item->id;?>)" value="<?php echo JText::_('COM_EINSATZKOMPONENTE_INFO_SCHLIESSEN');?>"></input>
					<?php if ($this->params->get('display_home_links','1')) : ?>
					<a href="<?php echo JRoute::_('index.php?option=com_einsatzkomponente&view=einsatzbericht&id='.(int) $item->id); ?>" type="button" class="btn btn-primary"><?php echo JText::_('COM_EINSATZKOMPONENTE_DETAILS'); ?></a>	
					<?php endif;?>
           </div> 
           </td>
           </tr>
	<?php endif;?>
     <!-- Zusatzinformation Kurzinfo ENDE -->
	 
	 
    <?php } ?>
    </tbody>
	
    <tfoot>
    				<!--Prüfen, ob Pagination angezeigt werden soll-->
    				<?php if ($this->params->get('display_home_pagination')) : ?>
					<tr>
					<td colspan="<?php echo $eiko_col;?>">
                    	<form action="#" method=post>
						<?php echo $this->pagination->getListFooter(); ?><!--Pagination anzeigen-->
						</form> 
					</td></tr>
		   			<?php endif;?><!--Prüfen, ob Pagination angezeigt werden soll   ENDE -->
		
		<?php if (!$this->params->get('eiko')) : ?>
        <tr><!-- Bitte das Copyright nicht entfernen. Danke. // Don't remove without permission -->
        <td colspan="<?php echo $eiko_col;?>">
			<span class="copyright">Einsatzkomponente V<?php echo $this->version; ?>  (C) 2017 by Ralf Meyer ( <a class="copyright_link" href="https://einsatzkomponente.de" target="_blank">www.einsatzkomponente.de</a> )</span></td>
        </tr>
	<?php endif; ?>
	
    </tfoot>



    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
    <?php echo JHtml::_('form.token'); ?>
</form>



    <?php if ($this->params->get('display_home_map')) : ?>
    <tr><td colspan="<?php echo $eiko_col;?>" class="eiko_td_gmap_main_1">
    <h4><?php echo JText::_('COM_EINSATZKOMPONENTE_EINSATZGEBIET'); ?></h4>
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
	
    </table>
	
<?php echo '<span class="mobile_hide_320">'.$this->modulepos_1.'</span>'; ?>


<script type="text/javascript">

    jQuery(document).ready(function () {
        jQuery('.delete-button').click(deleteItem);
    });

    function deleteItem() {
        var item_id = jQuery(this).attr('data-item-id');
        if (confirm("<?php echo JText::_('COM_EINSATZKOMPONENTE_WIRKLICH_LOESCHEN'); ?>")) {
            window.location.href = '<?php echo JRoute::_('index.php?option=com_einsatzkomponente&task=einsatzberichtform.remove&id=', false, 2) ?>' + item_id;
        }
    }
</script>

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

