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


<?php // Filter ------------------------------------------------------------------------------------
	

?>
<form action="<?php echo JRoute::_('index.php?option=com_einsatzkomponente&view=einsatzberichte&list=1'); ?>" method="post" name="adminForm" id="adminForm">


 
  

<table width="100%" class="table table-striped table-bordered eiko_table_main_1" border="0" cellspacing="0" cellpadding="0">
    <thead>
        <tr>
           <?php $col ='0';?>
           <?php if ($this->params->get('display_home_number','1') or $this->params->get('display_home_alertimage_num','0')) : ?>
		   <?php if ($this->params->get('display_home_number','1')):?>
            <th class="eiko_th_einsatznummer_main_1" width="">Nr.</th>
           <?php else:?>
            <th class="eiko_th_einsatznummer_main_1" width=""></th>
           <?php endif;?>
           <?php $col =$col+1;?>
           <?php endif;?>
        </tr>
        <!--<tr><th colspan="6"><hr /></th></tr>-->
    </thead>
    
 <tbody>

 <?php
 		$query = 'SELECT COUNT(r.id) as total,r.id,r.image as foto,rd.marker,r.address,r.summary,r.date1,r.data1,r.counter,r.alerting,r.presse,r.gmap_report_latitude,r.gmap_report_longitude,re.image,re.title as alarmierungsart,rd.list_icon,rd.icon,r.desc,r.auswahl_orga,r.ausruestung,r.state,rd.title as einsatzart,r.tickerkat FROM #__eiko_einsatzberichte r JOIN #__eiko_einsatzarten rd ON r.data1 = rd.id LEFT JOIN #__eiko_alarmierungsarten re ON re.id = r.alerting WHERE (r.state = "1" OR r.state = "2") and rd.state = "1" and re.state ="1" GROUP BY r.id ORDER BY r.date1 DESC ' ;
		$db	= JFactory::getDBO();
		$db->setQuery( $query );
		$result = $db->loadObjectList();
?>
 
	 <?php 
	 $show = false;	 
     $i=count($result)+1;
	 $hide=0;
	 
     foreach ($result as $item) :
?>

	 <tr>

		  <?php if($item->state == '1'): ?>
		  <?php $i--;?>
          <?php echo '<td>ID.Nr. '.$item->id.'</td><td>'.$i.'</td><td></td>';$hide =0;?>
          <?php endif;?> 
		  
		  
		  <?php if($item->state == '2'): ?>
          <?php $hide++;$i--;?>
          <?php echo '<td>ID.Nr. '.$item->id.'</td><td></td><td>'.($i + $hide).'</td>';?>
          <?php endif;?> 
		  
		  </tr>
    <?php endforeach; ?>
    

    
 </tbody>   
    
    
</table>
	</form> 


