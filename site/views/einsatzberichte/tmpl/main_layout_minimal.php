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
<div class="items">
    <ul class="items_list">
        <?php $show = false; ?>
        <?php foreach ($this->items as $item) :?>
                
				<?php
					if($item->state == 1 || ($item->state == 0 && JFactory::getUser()->authorise('core.edit.own',' com_einsatzkomponente.einsatzbericht.'.$item->id))):
						$show = true;
						?>
							<li>
								<a href="<?php echo JRoute::_('index.php?option=com_einsatzkomponente&view=einsatzbericht&id=' . (int)$item->id); ?>"><?php echo $item->einsatzart; ?></a>
								<?php
									if(JFactory::getUser()->authorise('core.edit.state','com_einsatzkomponente.einsatzbericht'.$item->id)):
									?>
										<a href="javascript:document.getElementById('form-einsatzbericht-state-<?php echo $item->id; ?>').submit()"><?php if($item->state == 1):?>Unpublish<?php else:?>Publish<?php endif; ?></a>
										<form id="form-einsatzbericht-state-<?php echo $item->id ?>" style="display:inline" action="<?php echo JRoute::_('index.php?option=com_einsatzkomponente&task=einsatzbericht.save'); ?>" method="post" class="form-validate" enctype="multipart/form-data">
											<input type="hidden" name="jform[id]" value="<?php echo $item->id; ?>" />
											<input type="hidden" name="jform[ordering]" value="<?php echo $item->ordering; ?>" />
											<input type="hidden" name="jform[data1]" value="<?php echo $item->data1; ?>" />
											<input type="hidden" name="jform[image]" value="<?php echo $item->image; ?>" />
											<input type="hidden" name="jform[address]" value="<?php echo $item->address; ?>" />
											<input type="hidden" name="jform[date1]" value="<?php echo $item->date1; ?>" />
											<input type="hidden" name="jform[date2]" value="<?php echo $item->date2; ?>" />
											<input type="hidden" name="jform[date3]" value="<?php echo $item->date3; ?>" />
											<input type="hidden" name="jform[summary]" value="<?php echo $item->summary; ?>" />
											<input type="hidden" name="jform[boss]" value="<?php echo $item->boss; ?>" />
											<input type="hidden" name="jform[boss2]" value="<?php echo $item->boss2; ?>" />
											<input type="hidden" name="jform[people]" value="<?php echo $item->people; ?>" />
											<input type="hidden" name="jform[department]" value="<?php echo $item->department; ?>" />
											<input type="hidden" name="jform[desc]" value="<?php echo $item->desc; ?>" />
											<input type="hidden" name="jform[alerting]" value="<?php echo $item->alerting; ?>" />
											<input type="hidden" name="jform[gmap_report_latitude]" value="<?php echo $item->gmap_report_latitude; ?>" />
											<input type="hidden" name="jform[gmap_report_longitude]" value="<?php echo $item->gmap_report_longitude; ?>" />
											<input type="hidden" name="jform[counter]" value="<?php echo $item->counter; ?>" />
											<input type="hidden" name="jform[gmap]" value="<?php echo $item->gmap; ?>" />
											<input type="hidden" name="jform[presse]" value="<?php echo $item->presse; ?>" />
											<input type="hidden" name="jform[presse2]" value="<?php echo $item->presse2; ?>" />
											<input type="hidden" name="jform[presse3]" value="<?php echo $item->presse3; ?>" />
											<input type="hidden" name="jform[updatedate]" value="<?php echo $item->updatedate; ?>" />
											<input type="hidden" name="jform[einsatzticker]" value="<?php echo $item->einsatzticker; ?>" />
											<input type="hidden" name="jform[notrufticker]" value="<?php echo $item->notrufticker; ?>" />
											<input type="hidden" name="jform[tickerkat]" value="<?php echo $item->tickerkat; ?>" />
											<input type="hidden" name="jform[auswahl_orga]" value="<?php echo $item->auswahl_orga; ?>" />
											<input type="hidden" name="jform[vehicles]" value="<?php echo $item->vehicles; ?>" />
											<input type="hidden" name="jform[status]" value="<?php echo $item->status; ?>" />
											<input type="hidden" name="jform[state]" value="<?php echo (int)!((int)$item->state); ?>" />
											<input type="hidden" name="option" value="com_einsatzkomponente" />
											<input type="hidden" name="task" value="einsatzbericht.save" />
											<?php echo JHtml::_('form.token'); ?>
										</form>
									<?php
									endif;
									if(JFactory::getUser()->authorise('core.delete','com_einsatzkomponente.einsatzbericht'.$item->id)):
									?>
										<a href="javascript:document.getElementById('form-einsatzbericht-delete-<?php echo $item->id; ?>').submit()">Delete</a>
										<form id="form-einsatzbericht-delete-<?php echo $item->id; ?>" style="display:inline" action="<?php echo JRoute::_('index.php?option=com_einsatzkomponente&task=einsatzbericht.remove'); ?>" method="post" class="form-validate" enctype="multipart/form-data">
											<input type="hidden" name="jform[id]" value="<?php echo $item->id; ?>" />
											<input type="hidden" name="jform[ordering]" value="<?php echo $item->ordering; ?>" />
											<input type="hidden" name="jform[data1]" value="<?php echo $item->data1; ?>" />
											<input type="hidden" name="jform[image]" value="<?php echo $item->image; ?>" />
											<input type="hidden" name="jform[address]" value="<?php echo $item->address; ?>" />
											<input type="hidden" name="jform[date1]" value="<?php echo $item->date1; ?>" />
											<input type="hidden" name="jform[date2]" value="<?php echo $item->date2; ?>" />
											<input type="hidden" name="jform[date3]" value="<?php echo $item->date3; ?>" />
											<input type="hidden" name="jform[summary]" value="<?php echo $item->summary; ?>" />
											<input type="hidden" name="jform[boss]" value="<?php echo $item->boss; ?>" />
											<input type="hidden" name="jform[boss2]" value="<?php echo $item->boss2; ?>" />
											<input type="hidden" name="jform[people]" value="<?php echo $item->people; ?>" />
											<input type="hidden" name="jform[department]" value="<?php echo $item->department; ?>" />
											<input type="hidden" name="jform[desc]" value="<?php echo $item->desc; ?>" />
											<input type="hidden" name="jform[alerting]" value="<?php echo $item->alerting; ?>" />
											<input type="hidden" name="jform[gmap_report_latitude]" value="<?php echo $item->gmap_report_latitude; ?>" />
											<input type="hidden" name="jform[gmap_report_longitude]" value="<?php echo $item->gmap_report_longitude; ?>" />
											<input type="hidden" name="jform[counter]" value="<?php echo $item->counter; ?>" />
											<input type="hidden" name="jform[gmap]" value="<?php echo $item->gmap; ?>" />
											<input type="hidden" name="jform[presse]" value="<?php echo $item->presse; ?>" />
											<input type="hidden" name="jform[presse2]" value="<?php echo $item->presse2; ?>" />
											<input type="hidden" name="jform[presse3]" value="<?php echo $item->presse3; ?>" />
											<input type="hidden" name="jform[updatedate]" value="<?php echo $item->updatedate; ?>" />
											<input type="hidden" name="jform[einsatzticker]" value="<?php echo $item->einsatzticker; ?>" />
											<input type="hidden" name="jform[notrufticker]" value="<?php echo $item->notrufticker; ?>" />
											<input type="hidden" name="jform[tickerkat]" value="<?php echo $item->tickerkat; ?>" />
											<input type="hidden" name="jform[auswahl_orga]" value="<?php echo $item->auswahl_orga; ?>" />
											<input type="hidden" name="jform[vehicles]" value="<?php echo $item->vehicles; ?>" />
											<input type="hidden" name="jform[status]" value="<?php echo $item->status; ?>" />
											<input type="hidden" name="jform[state]" value="<?php echo $item->state; ?>" />
											<input type="hidden" name="jform[created_by]" value="<?php echo $item->created_by; ?>" />
											<input type="hidden" name="option" value="com_einsatzkomponente" />
											<input type="hidden" name="task" value="einsatzbericht.remove" />
											<?php echo JHtml::_('form.token'); ?>
										</form>
									<?php
									endif;
								?>
							</li>
						<?php endif; ?>
        <?php endforeach; ?>
        <?php if(!$show): ?>
            There are no items in the list
        <?php endif; ?>
    </ul>
</div>
<?php if($show): ?>
    <div class="pagination">
        <p class="counter">
            <?php echo $this->pagination->getPagesCounter(); ?>
        </p>
        <?php echo $this->pagination->getPagesLinks(); ?>
    </div>
<?php endif; ?>
									<?php if(JFactory::getUser()->authorise('core.create','com_einsatzkomponente.einsatzbericht'.$item->id)): ?><a href="<?php echo JRoute::_('index.php?option=com_einsatzkomponente&view=einsatzberichtform&layout=edit&id=0'); ?>">Add</a>
	<?php endif; ?>
    
	<?php if (!$this->params->get('eiko')) : ?>
        <!-- Bitte das Copyright nicht entfernen. Danke. -->
            <span class="copyright">Einsatzkomponente V<?php echo $this->version; ?>  (C) 2017 by Ralf Meyer ( <a class="copyright_link" href="http://einsatzkomponente.de" target="_blank">www.einsatzkomponente.de</a> )</span>
<?php endif;?>
