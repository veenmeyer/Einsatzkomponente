<?php

defined('JPATH_BASE') or die;

$app = JFactory::getApplication();
$params = $app->getParams('com_einsatzkomponente');

if ($params->get('show_filter','1')) :



$data = $displayData;

// Receive overridable options
$data['options'] = !empty($data['options']) ? $data['options'] : array();

// Set some basic options
$customOptions = array(
    'filtersHidden' => isset($data['options']['filtersHidden']) ? $data['options']['filtersHidden'] : empty($data['view']->activeFilters),
    'defaultLimit' => isset($data['options']['defaultLimit']) ? $data['options']['defaultLimit'] : JFactory::getApplication()->get('list_limit', 20),
    'searchFieldSelector' => '#filter_search',
    'orderFieldSelector' => '#list_fullordering'
);


$data['options'] = array_unique(array_merge($customOptions, $data['options']));

$formSelector = !empty($data['options']['formSelector']) ? $data['options']['formSelector'] : '#adminForm';
$filters = false;
if (isset($data['view']->filterForm)) {
    $filters = $data['view']->filterForm->getGroup('filter');
}

// Load search tools
JHtml::_('searchtools.form', $formSelector, $data['options']);
?>

<div class="js-stools clearfix">
    <div class="clearfix">
        <div class="js-stools-container-bar">

		<?php if ($params->get('show_filter_search','1')) : ?>
            <label for="filter_search" class="element-invisible" aria-invalid="false"><?php echo JText::_('Suchen'); ?></label>

            <div class="btn-wrapper input-append">
                <?php echo $filters['filter_search']->input; ?>
                <button type="submit" class="btn hasTooltip" title="" data-original-title="<?php echo JText::_('Suchen'); ?>">
                    <i class="icon-search"></i>
                </button>
            </div>
		<?php endif; ?>
            <?php if ($filters) : ?>
            <div class="btn-wrapper hidden-phone">
                <button type="button" class="btn hasTooltip js-stools-btn-filter" title=""
                        data-original-title="<?php echo JText::_('Filter auswählen'); ?>">
                    <?php echo JText::_('Filter auswählen'); ?> <i class="caret"></i>
                </button>
            </div>
            <?php endif; ?>
            <div class="btn-wrapper hidden-phone">
                <button type="button" class="btn hasTooltip js-stools-btn-clear" title="" data-original-title="<?php echo JText::_('Alle Filter zurücksetzen'); ?>">
                    <?php echo JText::_('Alle Filter zurücksetzen'); ?>
                </button>
            </div>
        </div>
    </div>
	
	
    <!-- Filters div -->
    <div class="js-stools-container-filters hidden-phone clearfix" style="">
        <?php // Load the form filters ?>
        <?php if ($filters) : ?>
			
		<div class="js-stools-field-filter">
		
		<?php if ($params->get('show_filter_year','1')) : ?>
		<?php echo $filters['filter_year']->input; ?>
		<?php if ($params->get('show_filter_linebreak','0')) :echo '<br/>'; endif;?>
		<?php endif; ?>

		<?php if ($params->get('show_filter_auswahl_orga','1')) : ?>
		<?php echo $filters['filter_auswahl_orga']->input; ?>
		<?php if ($params->get('show_filter_linebreak','0')) :echo '<br/>'; endif;?>
		<?php endif; ?>
		
		<?php if ($params->get('show_filter_data1','1')) : ?>
		<?php echo $filters['filter_data1']->input; ?>
		<?php if ($params->get('show_filter_linebreak','0')) :echo '<br/>'; endif;?>
		<?php endif; ?>

		<?php if ($params->get('show_filter_tickerkat','1')) : ?>
		<?php echo $filters['filter_tickerkat']->input; ?>
		<?php if ($params->get('show_filter_linebreak','0')) :echo '<br/>'; endif;?>
		<?php endif; ?>

		<?php if ($params->get('show_filter_alerting','1')) : ?>
		<?php echo $filters['filter_alerting']->input; ?>
		<?php if ($params->get('show_filter_linebreak','0')) :echo '<br/>'; endif;?>
		<?php endif; ?>

		<?php if ($params->get('show_filter_vehicles','1')) : ?>
		<?php echo $filters['filter_vehicles']->input; ?>
		<?php endif; ?>
		
		</div>
		

		<?php endif; ?>
    </div>
	

 
		<div>
		<?php $active_name = ''; ?>
		<?php $active = $data['view']->activeFilters;?>
		<?php if($active): ?>
		<?php $active_name = 'Aktive Filter : '; ?>
            <?php foreach ($active as $fieldName => $field) : ?>
						
				<?php switch ($fieldName) 
				 { 
				 	case 'vehicles': $active_name .= '<span class="label label-info">Fahrzeug</span> ';break; 
				 	case 'alerting': $active_name .= '<span class="label label-info">Alarmierungsart</span> ';break; 
				 	case 'data1': $active_name .= '<span class="label label-info">Einsatzart</span> ';break; 
					case 'tickerkat': $active_name .= '<span class="label label-info">Einsatzkategorie</span> ';break; 
				 	case 'auswahl_orga': $active_name .= '<span class="label label-info">Organisation</span> ';break;
				 	case 'year': $active_name .= '<span class="label label-info">Jahr</span> ';break; 
				 	default: $active_name = '';break; 
				}  ?>

            <?php endforeach; ?>
			<?php echo $active_name;?>
		<?php endif; ?>
		</div>

</div>

<?php endif;?>