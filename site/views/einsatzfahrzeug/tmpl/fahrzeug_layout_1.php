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
?>
<input type="button" class="btn eiko_back_button" value="Zurück" onClick="history.back();">

<?php if( $this->item ) : ?>

<?php if ($this->item->state == '2'): $this->item->name = $this->item->name.' (a.D.)';endif;?>


<table class="fahrzeug_box_1" cellpadding="2">
<tbody>

<tr class="fahrzeug_box_4">
<th class="fahrzeug_box_6" colspan="2">
<span class="fahrzeug_box_detail1"><?php echo $this->item->detail1; ?></span><br/>
<span class="fahrzeug_box_title"><?php echo $this->item->name; ?></span> 
</th>
</tr>

<tr class="fahrzeug_box_3">
<td colspan="2" align="center">
<p><img src="./<?php echo $this->item->image; ?>" alt="<?php echo $this->item->name; ?>" width="100%" /></p>
</td>
</tr>
<tr class="fahrzeug_box_5" ><th class="fahrzeug_box_2" colspan="2">Fahrzeugdaten</th></tr>
<?php if ($this->item->name) : ?>
<tr class="fahrzeug_box_3">
<td><strong>Abkürzung:</strong></td>
<td><?php echo $this->item->name; ?></td> 
</tr>
<?php endif; ?>
<?php if ($this->item->detail1) : ?>
<tr class="fahrzeug_box_3">
<td><strong><?php echo $this->item->detail1_label; ?>:</strong></td>
<td><?php echo $this->item->detail1; ?></td> 
</tr>
<?php endif; ?>
<?php if ($this->item->department) : ?>
<tr class="fahrzeug_box_3">
<td><strong>Organisation:</strong></td>
<td><?php echo $this->item->department; ?></td> 
</tr>
<?php endif; ?>
<?php if ($this->item->detail2) : ?>
<tr class="fahrzeug_box_3">
<td><strong><?php echo $this->item->detail2_label; ?>:</strong></td>
<td><?php echo $this->item->detail2; ?></td> 
</tr>
<?php endif; ?>
<?php if ($this->item->detail3) : ?>
<tr class="fahrzeug_box_3"> 
<td><strong><?php echo $this->item->detail3_label; ?>:</strong></td>
<td><?php echo $this->item->detail3; ?></td>
</tr>
<?php endif; ?>
<?php if ($this->item->detail4) : ?>
<tr class="fahrzeug_box_3">
<td><strong><?php echo $this->item->detail4_label; ?>:</strong></td>
<td><?php echo $this->item->detail4; ?></td>
</tr>
<?php endif; ?>
<?php if ($this->item->detail5) : ?>
<tr class="fahrzeug_box_3">
<td><strong><?php echo $this->item->detail5_label; ?>:</strong></td>
<td><?php echo $this->item->detail5; ?></td>
</tr>
<?php endif; ?>
<?php if ($this->item->detail6) : ?>
<tr class="fahrzeug_box_3">
<td><strong><?php echo $this->item->detail6_label; ?>:</strong></td>
<td><?php echo $this->item->detail6; ?></td>
</tr>
<?php endif; ?>

<!--detail7 entfällt ,wenn im label als text 'letzter_einsatz' eingegeben wird und der value-text leer gelassen wird, dann wird automatisch der letzte einsatz des fahrezeuges angezeigt-->
<?php if ($this->item->detail7) : ?>   
<tr class="fahrzeug_box_3">
<td><strong><?php echo $this->item->detail7_label; ?>:</strong></td>
<td><?php echo $this->item->detail7; ?></td>
</tr>
<?php endif; ?>
<?php // letzter Einsatz   BUG : Problem bei der Suche nach ID z.B. 1 -> 11
$database			= JFactory::getDBO();
$query = 'SELECT * FROM #__eiko_einsatzberichte WHERE vehicles LIKE "%'.$this->item->id.'%" AND state ="1" ORDER BY date1 DESC' ;
$database->setQuery( $query );
$total = $database->loadObjectList();
$detail_check = $this->item->detail7_label;
?>
<?php if ($total) : ?>
<?php if ($detail_check =='letzter_einsatz') : ;?>

<tr class="fahrzeug_box_3">
<td><br/><strong>Letzter Einsatz:</strong></td>
<td><br/><?php echo date("d.m.Y", strtotime($total[0]->date1)); ?></td>
</tr>
<?php endif; ?>
<?php endif; ?>
<!--detail7 und letzter Einsatz ENDE -->

</tbody>
</table>
<!--<h4><span><?php echo $this->item->detail1; ?> <?php echo $this->item->name; ?></span></h4>-->
<br/>

<!--Einsatzbericht anzeigen mit Plugin-Support-->           
<?php jimport('joomla.html.content'); ?>  
<?php $Desc = JHTML::_('content.prepare', $this->item->desc); ?>
<table class="fahrzeug_box_7"><tr><td><?php echo $Desc; ?></td></tr></table>



    
<?php else: ?>
    Could not load the item
<?php endif; ?>
