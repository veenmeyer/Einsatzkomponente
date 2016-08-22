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

//Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_einsatzkomponente', JPATH_ADMINISTRATOR);


JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');

$version = new JVersion;
if ($version->isCompatible('3.0')) :
JHtml::_('formbehavior.chosen', 'select');
endif;

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_einsatzkomponente/assets/css/einsatzkomponente.css');
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'einsatzfahrzeug.cancel' || document.formvalidator.isValid(document.id('einsatzfahrzeug-form'))) {
			Joomla.submitform(task, document.getElementById('einsatzfahrzeug-form'));
		}
		else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>
<form action="<?php echo JRoute::_('index.php?option=com_einsatzkomponente&layout=edit&id='.(int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="einsatzfahrzeug-form" class="form-validate">
	<div class="row-fluid">
		<div class="span10 form-horizontal">
            <fieldset class="adminform">
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('id'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('id'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('name'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('name'); ?></div>
			</div>
			<div class="control-group">
				<div class="controls"><?php echo $this->form->getInput('detail1_label'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('detail1'); ?></div>
			</div>
			<div class="control-group">
				<div class="controls"><?php echo $this->form->getInput('detail2_label'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('detail2'); ?></div>
			</div>
            
			<div class="control-group">
				<div class="controls"><?php echo $this->form->getInput('detail3_label'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('detail3'); ?></div>
			</div>
			<div class="control-group">
				<div class="controls"><?php echo $this->form->getInput('detail4_label'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('detail4'); ?></div>
			</div>
			<div class="control-group">
				<div class="controls"><?php echo $this->form->getInput('detail5_label'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('detail5'); ?></div>
			</div>
			<div class="control-group">
				<div class="controls"><?php echo $this->form->getInput('detail6_label'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('detail6'); ?></div>
			</div>
			<div class="control-group">
				<div class="controls"><?php echo $this->form->getInput('detail7_label'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('detail7'); ?></div>
			</div>
            
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('department'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('department'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('link'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('link'); ?></div>
			</div>
			<div class="control-group" style="height:170px;">
				<div class="control-label"><?php echo $this->form->getLabel('image'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('image'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('desc'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('desc'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('state'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('state'); ?></div>
			</div>
			<!--<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('created_by'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('created_by'); ?></div>
			</div>-->
				
		<div class="control-group">
			<div class="controls">

				<?php if ($this->canSave): ?>
					<button type="submit" class="validate btn btn-primary">
						<?php echo JText::_('JSUBMIT'); ?>
					</button>
				<?php endif; ?>
				<a class="btn"
				   href="<?php echo JRoute::_('index.php?option=com_einsatzkomponente&task=einsatzfahrzeugform.cancel'); ?>"
				   title="<?php echo JText::_('JCANCEL'); ?>">
					<?php echo JText::_('JCANCEL'); ?>
				</a>
			</div>
		</div>

		<input type="hidden" name="option" value="com_einsatzkomponente"/>
		<input type="hidden" name="task"
			   value="einsatzfahrzeugform.save"/>
		<?php echo JHtml::_('form.token'); ?>
				
				
            </fieldset>
    	</div>
        
		
        
    </div>
</form>