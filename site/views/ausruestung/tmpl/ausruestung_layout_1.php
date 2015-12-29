<?php
/**
 * @version     3.0.7
 * @package     com_einsatzkomponente
 * @copyright   Copyright (C) 2015. Alle Rechte vorbehalten.
 * @license     GNU General Public License Version 2 oder später; siehe LICENSE.txt
 * @author      Ralf Meyer <ralf.meyer@mail.de> - http://einsatzkomponente.de
 */
// no direct access
defined('_JEXEC') or die;

$canEdit = JFactory::getUser()->authorise('core.edit', 'com_einsatzkomponente.' . $this->item->id);
if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_einsatzkomponente' . $this->item->id)) {
	$canEdit = JFactory::getUser()->id == $this->item->created_by;
}
?>
<?php if ($this->item) : ?>

    <div class="item_fields">
        <table class="table eiko_table_ausruestung">

<?php if ($this->params->get('show_ausruestung_beschreibung','0')) : ?>
<?php if( $this->item->beschreibung) : ?>
<tr>
	<?php jimport('joomla.html.content'); ?>  
	<?php $Desc = JHTML::_('content.prepare', $this->item->beschreibung); ?>
	<div class="eiko_ausruestung_desc">
	<?php echo $Desc;?>
	<br/>
			</div>
</tr>
<?php endif;?>
<?php endif;?>

<?php if (!$this->params->get('show_ausruestung_beschreibung','1')) : ?>

<tr>
			<td><?php echo $this->item->name; ?></td>
</tr>

<?php if( $this->item->image) : ?>
<tr>
			<td colspan="2">
			<img src="<?php echo $this->item->image; ?>" alt="<?php echo $this->item->name; ?>" style="width: 100%;" class ="eiko_ausruestung_detail_image"/> 
			</td>
</tr>
<?php endif;?>

<?php if( $this->item->beschreibung) : ?>
<tr>
			<th><?php echo JText::_('COM_EINSATZKOMPONENTE_FORM_LBL_AUSRUESTUNG_BESCHREIBUNG'); ?></th>
			<td><?php echo $this->item->beschreibung; ?></td>
</tr>
<?php endif;?>

<?php endif;?>
	
		
        </table>
    </div>
    <?php if($canEdit && $this->item->checked_out == 0): ?>
		<a class="btn" href="<?php echo JRoute::_('index.php?option=com_einsatzkomponente&view=ausruestungform&id='.$this->item->id); ?>"><?php echo JText::_("Bearbeiten"); ?></a>
	<?php endif; ?>
								<?php if(JFactory::getUser()->authorise('core.delete','com_einsatzkomponente.ausruestung.'.$this->item->id)):?>
									<a class="btn" href="<?php echo JRoute::_('index.php?option=com_einsatzkomponente&task=ausruestung.remove&id=' . $this->item->id, false, 2); ?>"><?php echo JText::_("Löschen"); ?></a>
								<?php endif; ?>
    <?php
else:
    echo JText::_('COM_EINSATZKOMPONENTE_ITEM_NOT_LOADED');
endif;
?>
