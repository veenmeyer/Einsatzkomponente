<?php
/**
 * @version     3.0.0
 * @package     com_einsatzkomponente
 * @copyright   Copyright (C) 2013 by Ralf Meyer. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ralf Meyer <webmaster@feuerwehr-veenhusen.de> - http://einsatzkomponente.de
 */
// no direct access
defined('_JEXEC') or die;
$params = JComponentHelper::getParams('com_einsatzkomponente');
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.multiselect');

$version = new JVersion;
if ($version->isCompatible('3.0')) :
JHtml::_('bootstrap.tooltip');
JHtml::_('formbehavior.chosen', 'select');
endif;

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_einsatzkomponente/assets/css/einsatzkomponente.css');

$user	= JFactory::getUser();
$userId	= $user->get('id');

$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$canOrder	= $user->authorise('core.edit.state', 'com_einsatzkomponente');
$saveOrder	= $listOrder == 'a.ordering';
if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_einsatzkomponente&task=einsatzberichte.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'einsatzberichtList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
$sortFields = $this->getSortFields();
?>



<script type="text/javascript">
	Joomla.orderTable = function() {
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
		if (order != '<?php echo $listOrder; ?>') {
			dirn = 'asc';
		} else {
			dirn = direction.options[direction.selectedIndex].value;
		}
		Joomla.tableOrdering(order, dirn, '');
	}
</script>
<?php
//Joomla Component Creator code to allow adding non select list filters
if (!empty($this->extra_sidebar)) {
    $this->sidebar .= $this->extra_sidebar;
}

?>
<form action="<?php echo JRoute::_('index.php?option=com_einsatzkomponente&view=einsatzberichte'); ?>" method="post" name="adminForm" id="adminForm">
<?php if(!empty($this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>
    
		<div id="filter-bar" class="btn-toolbar">
        
		<?php $version = new JVersion;
        if ($version->isCompatible('3.0')) :?>
			<div class="filter-search btn-group pull-left">
				<label for="filter_search" class="element-invisible"><?php echo JText::_('JSEARCH_FILTER');?></label>
				<input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('JSEARCH_FILTER'); ?>" />
			</div>
			<div class="btn-group pull-left">
				<button class="btn hasTooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
				<button class="btn hasTooltip" type="button" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.id('filter_search').value='';this.form.submit();"><i class="icon-remove"></i></button>
			</div>
<?php endif;?>
    
		<?php $version = new JVersion;
        if (!$version->isCompatible('3.0')) :?>
			<div class="filter-search btn-group pull-left">
				<label for="filter_search" class="element-invisible"><?php echo JText::_('JSEARCH_FILTER');?></label>
				<input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('JSEARCH_FILTER'); ?>" />
			</div>
			<div class="btn-group pull-left">
				<button class="btn hasTooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>">Suchen</button>
				<button class="btn hasTooltip" type="button" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.id('filter_search').value='';this.form.submit();">Zurücksetzen</button>
			</div>
<?php endif;?>
            
		<?php $version = new JVersion;
        if ($version->isCompatible('3.0')) :?>
			<div class="btn-group pull-right hidden-phone">
				<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
				<?php echo $this->pagination->getLimitBox(); ?>
			</div>
			<div class="btn-group pull-right hidden-phone">
				<label for="directionTable" class="element-invisible"><?php echo JText::_('JFIELD_ORDERING_DESC');?></label>
				<select name="directionTable" id="directionTable" class="input-medium" onchange="Joomla.orderTable()">
					<option value=""><?php echo JText::_('JFIELD_ORDERING_DESC');?></option>
					<option value="asc" <?php if ($listDirn == 'asc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_ASCENDING');?></option>
					<option value="desc" <?php if ($listDirn == 'desc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_DESCENDING');?></option>
				</select>
			</div>
			<div class="btn-group pull-right">
				<label for="sortTable" class="element-invisible"><?php echo JText::_('JGLOBAL_SORT_BY');?></label>
				<select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
					<option value=""><?php echo JText::_('JGLOBAL_SORT_BY');?></option>
					<?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $listOrder);?>
				</select>
			</div>
<?php endif;?>

		</div>        
		<div class="clearfix"> </div>
		<table class="table table-striped" id="einsatzberichtList">
			<thead>
				<tr>
                <?php if (isset($this->items[0]->ordering)): ?>
					<th width="1%" class="nowrap center hidden-phone">
						<?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
					</th>
                <?php endif; ?>
					<th width="1%" class="hidden-phone">
						<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
					</th>
                <?php if (isset($this->items[0]->state)): ?>
					<th width="1%" class="nowrap center">
						<?php echo JHtml::_('grid.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
					</th>
                <?php endif; ?>
                
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_EINSATZKOMPONENTE_EINSATZBERICHTE_DATE1', 'a.date1', $listDirn, $listOrder); ?>
				</th>
<!-- Spalte Alarmierungsart wurde mit Spalte Einsatzart zusammen gelegt			
                <th>
				<?php echo JHtml::_('grid.sort',  'COM_EINSATZKOMPONENTE_EINSATZBERICHTE_ALERTING', 'a.alerting', $listDirn, $listOrder); ?>
				</th>
-->				
                <th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_EINSATZKOMPONENTE_EINSATZBERICHTE_DATA1', 'a.data1', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_EINSATZKOMPONENTE_EINSATZBERICHTE_ADDRESS', 'a.address', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_EINSATZKOMPONENTE_EINSATZBERICHTE_IMAGE', 'a.image', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_EINSATZKOMPONENTE_EINSATZBERICHTE_SUMMARY', 'a.summary', $listDirn, $listOrder); ?>
				</th>
<!--				<th class='left'>
				<?php //echo JHtml::_('grid.sort',  'COM_EINSATZKOMPONENTE_EINSATZBERICHTE_DEPARTMENT', 'a.department', $listDirn, $listOrder); ?>
				</th>
-->				<th class='left'>
				<?php echo JHtml::_('grid.sort',  '<small>Counter</small>', 'a.counter', $listDirn, $listOrder); ?>
				</th>
                
            	<?php if ($params->get('gmap_action','0')) : ?>
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  '<small>GMap</small>', 'a.gmap', $listDirn, $listOrder); ?>
				</th>
 				<?php  endif; ?>
                
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_EINSATZKOMPONENTE_EINSATZBERICHTE_UPDATEDATE', 'a.updatedate', $listDirn, $listOrder); ?>
				</th>
                
                
                <?php if ($this->params->get('info112','0')) : ?>
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  '<small>Info112.net</small>', 'a.notrufticker', $listDirn, $listOrder); ?>
				</th>
                <?php endif; ?>

				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_EINSATZKOMPONENTE_EINSATZBERICHTE_AUSWAHLORGA', 'a.auswahlorga', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_EINSATZKOMPONENTE_EINSATZBERICHTE_CREATED_BY', 'a.created_by', $listDirn, $listOrder); ?>
				</th>
                    
                    
                <?php if (isset($this->items[0]->id)): ?>
					<th width="1%" class="nowrap center hidden-phone">
						<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
					</th>
                <?php endif; ?>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="10">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
			<tbody>
			<?php foreach ($this->items as $i => $item) :
				$ordering   = ($listOrder == 'a.ordering');
                $canCreate	= $user->authorise('core.create',		'com_einsatzkomponente');
                $canEdit	= $user->authorise('core.edit',			'com_einsatzkomponente');
                $canCheckin	= $user->authorise('core.manage',		'com_einsatzkomponente');
                $canChange	= $user->authorise('core.edit.state',	'com_einsatzkomponente');
				?>
				<tr class="row<?php echo $i % 2; ?>">
                    
                <?php if (isset($this->items[0]->ordering)): ?>
					<td class="order nowrap center hidden-phone">
					<?php if ($canChange) :
						$disableClassName = '';
						$disabledLabel	  = '';
						if (!$saveOrder) :
							$disabledLabel    = JText::_('JORDERINGDISABLED');
							$disableClassName = 'inactive tip-top';
						endif; ?>
						<span class="sortable-handler hasTooltip <?php echo $disableClassName?>" title="<?php echo $disabledLabel?>">
							<i class="icon-menu"></i>
						</span>
						<input type="text" style="display:none" name="order[]" size="5" value="<?php echo $item->ordering;?>" class="width-20 text-area-order " />
					<?php else : ?>
						<span class="sortable-handler inactive" >
							<i class="icon-menu"></i>
						</span>
					<?php endif; ?>
					</td>
                <?php endif; ?>
					<td class="center hidden-phone">
						<?php echo JHtml::_('grid.id', $i, $item->id); ?>
					</td>
                <?php if (isset($this->items[0]->state)): ?>
					<td class="center">
						<?php echo JHtml::_('jgrid.published', $item->state, $i, 'einsatzberichte.', $canChange, 'cb'); ?>
					</td>
                <?php endif; ?>
				<td>
					<?php echo $item->date1; ?>
				</td>
                
				<td>
                <?php // Get Image of Alarmierungsart
				     $database = JFactory::getDBO();
                     $query = 'SELECT image FROM #__eiko_alarmierungsarten WHERE id = "'.$item->alerting.'" LIMIT 1 ' ;
                     $database->setQuery( $query );
                     $alerting_image = $database->loadObject();	
				?>
                <?php // Get color of Einsatzart
				     $database = JFactory::getDBO();
                     $query = 'SELECT marker FROM #__eiko_einsatzarten WHERE title = "'.$item->data1.'" LIMIT 1 ' ;
                     $database->setQuery( $query );
                     $data1_color = $database->loadObject();	
				?>
				<?php if (isset($item->checked_out) && $item->checked_out) : ?>
					<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'einsatzberichte.', $canCheckin); ?>
				<?php endif; ?>
				<?php if ($canEdit) : ?>
					<p><a href="<?php echo JRoute::_('index.php?option=com_einsatzkomponente&task=einsatzbericht.edit&id='.(int) $item->id); ?>">
                    <?php echo '<b>'.$item->data1; ?></b></a></p>
				      <?php echo '<span style="float:left;background:'.$data1_color->marker.';"><img src="../'.$alerting_image->image.'" width="16" height="100%" />&nbsp;&nbsp;&nbsp;</span>';?>
				<?php else : ?>
					 <?php echo '<p><b>'.$item->data1.'</b></p>'; ?>
			       	 <?php echo '<span style="float:left;background:'.$data1_color->marker.';"><img src="../'.$alerting_image->image.'" width="16" height="100%" />&nbsp;&nbsp;&nbsp;</span>';?>
                    
				<?php endif; ?>
                </td>
				<td>
					<?php echo $this->escape($item->address); ?>
				</td>
				<td>
					<?php echo '<span style="float:left;"> <img src="../'.$item->image.'" width="30" height="100%" title="'.$item->image.'"/></span>';?>
				</td>
				<td>
					<?php echo $item->summary; ?>
				</td>
<!--				<td>
					<?php //echo $item->department; ?>
				</td>
-->				<td>
					<?php echo $item->counter; ?>
				</td>
                
            	<?php if ($params->get('gmap_action','0')) : ?>
                <?php
			$gmap = $item->gmap;
			
			if ($gmap == '1')
			{
			echo '<td align="center" nowrap="nowrap"><img src="../administrator/components/com_einsatzkomponente/assets/images/ok.png" width="24" height="24" /></td>';
			}
            
            if ($gmap == '0')
            {
			echo '<td align="center" nowrap="nowrap"><img src="../administrator/components/com_einsatzkomponente/assets/images/error.png" width="24" height="24" /></td>';
			}
			?>
 				<?php  endif; ?>
                
				<td>
					<?php echo $item->updatedate; ?>
				</td>
            
                <?php 
// ----------------  info112.net  ----------------------------------------
			if ($this->params->get('info112','0')) : 
			$notrufticker = $item->notrufticker;
			
			if ($notrufticker == '1')
			{
			echo '<td align="center" nowrap="nowrap"><a class="link" id="link" href="'.JRoute::_('index.php?option=com_einsatzkomponente').'&view=einsatzberichte&tickerID2='.$item->id.'"><img src="../administrator/components/com_einsatzkomponente/assets/images/send.png" width="32" height="32" /></a></td>';
			}
			
			
			if ($notrufticker == '2')
			{
			echo '<td align="center" nowrap="nowrap"><img src="../administrator/components/com_einsatzkomponente/assets/images/ok.png" width="24" height="24" /></td>';
			}
            
            if ($notrufticker == '0')
            {
			echo '<td align="center" nowrap="nowrap"><img src="../administrator/components/com_einsatzkomponente/assets/images/error.png" width="24" height="24" /></td>';
			}
			endif;
// ----------------  info112.net  ENDE ----------------------------------------
			?>
            <td>
					<?php
					$data = array();
					foreach(explode(',',$item->auswahlorga) as $value):
						$db = JFactory::getDbo();
						$query	= $db->getQuery(true);
						$query
							->select('name')
							->from('`#__eiko_organisationen`')
							->where('name = "' .$value.'"');
						$db->setQuery($query);
						$results = $db->loadObjectList();
						if(count($results)){
							$data[] = '<span class="label label-info">'.$results[0]->name.'</span>';
						}
					endforeach;
					echo implode(' ',$data); ?>
				</td>
				<td>
					<?php echo $item->created_by; ?>
				</td>
                <?php if (isset($this->items[0]->id)): ?>
					<td class="center hidden-phone">
						<?php echo (int) $item->id; ?>
					</td>
                <?php endif; ?>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>        
		
