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

?>

<form action="<?php echo JRoute::_('index.php?option=com_einsatzkomponente&view=ausruestungen'); ?>" method="post"
      name="adminForm" id="adminForm">

	<?php //echo JLayoutHelper::render('default_filter', array('view' => $this), dirname(__FILE__)); ?>
<?php if (!$this->params->get('eiko')) : ?>
        <tr><!-- Bitte das Copyright nicht entfernen. Danke. -->
            <th colspan="<?php echo isset($this->items[0]) ? count(get_object_vars($this->items[0])) : 10; ?>"><span class="copyright">Einsatzkomponente V<?php echo $this->version; ?>  (C) 2015 by Ralf Meyer ( <a class="copyright_link" href="http://einsatzkomponente.de" target="_blank">www.einsatzkomponente.de</a> )</span></th>
        </tr>
	<?php endif; ?>
	
	
		<div>
		<?php foreach ($this->items as $i => $item) : ?> 
		
<div>
    <div class="thumbnail">
					<img  class="img-rounded eiko_img_ausruestungen_main_1" style="float:right;margin:20px 20px 20px 20px;width:<?php echo $this->params->get('display_ausruestungen_image_width','200px');?>;" src="<?php echo JURI::Root();?><?php echo $item->image;?>" title="<?php echo $item->name;?>"/>
      <div class="caption">
        <h3><?php echo $this->escape($item->name); ?></h3>
        <p><?php echo $item->beschreibung; ?></p>
        <p><a href="<?php echo JRoute::_('index.php?option=com_einsatzkomponente&view=ausruestung&id='.(int) $item->id); ?>" class="btn btn-primary" role="button">Details</a></p>
      </div>
    </div>
  </div>

   

			
			
		<?php endforeach; ?>
		</div>



	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
	<?php echo JHtml::_('form.token'); ?>
</form>

<script type="text/javascript">

	jQuery(document).ready(function () {
		jQuery('.delete-button').click(deleteItem);
	});

	function deleteItem() {
		var item_id = jQuery(this).attr('data-item-id');
		<?php if($canDelete) : ?>
		if (confirm("<?php echo JText::_('COM_EINSATZKOMPONENTE_DELETE_MESSAGE'); ?>")) {
			window.location.href = '<?php echo JRoute::_('index.php?option=com_einsatzkomponente&task=ausruestungform.remove&id=', false, 2) ?>' + item_id;
		}
		<?php endif; ?>
	}
</script>


