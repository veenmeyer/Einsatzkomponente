
<?php
/**
 * @version    CVS: 3.9
 * @package    Com_Einsatzkomponente
 * @author     Ralf Meyer <ralf.meyer@einsatzkomponente.de>
 * @copyright  Copyright (C) 2015. Alle Rechte vorbehalten.
 * @license    GNU General Public License Version 2 oder später; siehe LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

require_once JPATH_SITE.'/administrator/components/com_einsatzkomponente/helpers/einsatzkomponente.php'; // Helper-class laden

$canEdit = JFactory::getUser()->authorise('core.edit', 'com_einsatzkomponente.' . $this->item->id);
if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_einsatzkomponente' . $this->item->id)) {
	$canEdit = JFactory::getUser()->id == $this->item->created_by;
}
?>
<?php if ($this->item) : ?>
<?php if ($this->item->state == '2'): $this->item->name = $this->item->name.' (a.D.)';endif;?>


	<div class="item_fields">
		<table class="table">

<?php if ($this->params->get('show_fahrzeuge_beschreibung','0')) : ?>
<?php if( $this->item->desc) : ?>
<tr>
	<?php jimport('joomla.html.content'); ?>  
	<?php $Desc = JHTML::_('content.prepare', $this->item->desc); ?>
	<div class="eiko_orga_desc">
	<?php echo $Desc;?>
	<br/>

			</div>
</tr>
<?php endif;?>
<?php endif;?>


<?php if (!$this->params->get('show_fahrzeuge_beschreibung','1')) : ?>

<tr>
    <div class="item_fields">
	
      	<h2><span class ="eiko_fahrezug_name"><?php echo $this->item->name; ?></span></h2>
  
        <ul class="fields_list">
			
			<?php if ($this->params->get('show_fahrzeuge_detail_1','1')) : ?>
			<li><?php echo $this->item->detail1_label; ?>:
			<?php echo $this->item->detail1; ?></li>
			<?php endif;?>

			<?php if ($this->params->get('show_fahrzeuge_detail_2','1')) : ?>
			<li><?php echo $this->item->detail2_label; ?>:
			<?php echo $this->item->detail2; ?></li>
			<?php endif;?>
			
			
			
			<?php if ($this->params->get('show_fahrzeuge_detail_3','1')) : ?>
			<li><?php echo $this->item->detail3_label; ?>:
			<?php echo $this->item->detail3; ?></li>
			<?php endif;?>
			
			<?php if ($this->params->get('show_fahrzeuge_detail_4','1')) : ?>
			<li><?php echo $this->item->detail4_label; ?>:
			<?php echo $this->item->detail4; ?></li>
			<?php endif;?>

			<?php if ($this->params->get('show_fahrzeuge_detail_5','1')) : ?>
			<li><?php echo $this->item->detail5_label; ?>:
			<?php echo $this->item->detail5; ?></li>
			<?php endif;?>

			<?php if ($this->params->get('show_fahrzeuge_detail_6','1')) : ?>
			<li><?php echo $this->item->detail6_label; ?>:
			<?php echo $this->item->detail6; ?></li>
			<?php endif;?>

			<?php if ($this->params->get('show_fahrzeuge_detail_7','1')) : ?>
			<li><?php echo $this->item->detail7_label; ?>:
			<?php echo $this->item->detail7; ?></li>
			<?php endif;?>
			
			<?php if ($this->params->get('show_fahrzeuge_orga','1')) : ?>
			<li><?php echo 'Organisation'; ?>:
			<?php echo $this->item->department; ?></li>
			<?php endif;?>

			<?php // letzter Einsatz   
			$database			= JFactory::getDBO();
			$query = 'SELECT * FROM #__eiko_einsatzberichte WHERE FIND_IN_SET ("'.$this->item->id.'",auswahl_orga) AND (state ="1" OR state="2") ORDER BY date1 DESC' ;
			$database->setQuery( $query );
			$total = $database->loadObjectList();
			?>
			<?php if ($total) : ?>
			<li>Letzter Eintrag:
			<a href="<?php echo JRoute::_('index.php?option=com_einsatzkomponente&view=einsatzbericht&id='.(int) $total[0]->id); ?>"><?php echo date("d.m.Y", strtotime($total[0]->date1));?></a></li>
			<?php endif; ?>

			
			
			<?php if ($this->params->get('show_fahrzeuge_link','1')) : ?>
			<?php if( $this->item->link) : ?>
			<?php echo '<li>Link zur Webseite: <a href="" target="blank" class="eiko_fahrzeuge_link">'.$this->item->link.'</a></li>'; ?>
			<?php endif;?>
			<?php endif;?>
        </ul>
        
    </div>
</tr>



			<?php if ($this->params->get('show_fahrzeuge_desc','1')) : ?>
			<?php if( $this->item->desc) : ?>
			<tr>
				<?php jimport('joomla.html.content'); ?>  
				<?php $Desc = JHTML::_('content.prepare', $this->item->desc); ?>
				<div class="eiko_fahrzeuge_desc">
				<?php echo $Desc;?>
				</div>
			</tr>
			<?php endif;?>
			<?php endif;?>


<?php endif;?>


		</table>
	</div>
	<?php if($canEdit): ?>
		<a class="btn" href="<?php echo JRoute::_('index.php?option=com_einsatzkomponente&view=fahrzeugform.&id='.$this->item->id); ?>"><?php echo JText::_("Bearbeiten"); ?></a>
	<?php endif; ?>
								<?php if(JFactory::getUser()->authorise('core.delete','com_einsatzkomponente.einsatzfahrzeug.'.$this->item->id)):?>
									<a class="btn" href="<?php echo JRoute::_('index.php?option=com_einsatzkomponente&task=fahrzeug.remove&id=' . $this->item->id, false, 2); ?>"><?php echo JText::_("Löschen"); ?></a>
								<?php endif; ?>
	<?php
else:
	echo JText::_('COM_EINSATZKOMPONENTE_ITEM_NOT_LOADED');
endif;?>













