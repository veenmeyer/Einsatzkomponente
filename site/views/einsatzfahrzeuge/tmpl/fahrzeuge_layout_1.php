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

?>

<!--Page Heading-->
<?php if ($this->params->get('show_page_heading', 1)) : ?>
<div class="page-header">
<h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
</div>
<?php endif;?>

<table>
<tr>
        <?php $show = false; ?>
        <?php $n='3';foreach ($this->items as $item) :?>
				<?php if($item->state == 1 or $item->state == 2):
				$show = true;?>
				<?php if ($item->state == '2'): $item->name = $item->name.' (a.D.)';endif;?>
<?php $n--;?>
<?php if ($this->params->get('abfragewehr_fhz','0')) :?>
<?php if ($this->params->get('anzeigewehr_fhz','') == $item->department) :?>
<td width="33%">
<a href="<?php echo JRoute::_('index.php?option=com_einsatzkomponente&view=einsatzfahrzeug&id=' . (int)$item->id); ?>"><?php echo '<img style="padding-right:3px;margin-right:3px;max-height:150px;" src="'.JURI::Root().$item->image.'"  alt="'.$item->name.'" title="'.$item->name.'"/><br/>'.$item->name.''; ?></a>
</td>
<?php endif;?>
<?php else:?>
<td width="33%">
<a href="<?php echo JRoute::_('index.php?option=com_einsatzkomponente&view=einsatzfahrzeug&id=' . (int)$item->id); ?>"><?php echo '<img style="padding-right:3px;margin-right:3px;max-height:150px;" src="'.JURI::Root().$item->image.'"  alt="'.$item->name.'" title="'.$item->name.'"/><br/>'.$item->name.''; ?></a>
</td>
<?php endif;?>

						<?php endif; ?>
<?php if ($n == '0') : echo '</tr><tr>'; $n='3';endif;?>

        <?php endforeach; ?>
        <?php if(!$show): ?>
            There are no items in the list
        <?php endif; ?>
</tr></table>






<?php if($show): ?>
    <div class="pagination">
        <p class="counter">
            <?php echo $this->pagination->getPagesCounter(); ?>
        </p>
        <?php echo $this->pagination->getPagesLinks(); ?>
    </div>
<?php endif; ?>
