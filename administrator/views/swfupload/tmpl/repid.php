<?php

// No direct access to this file
defined('_JEXEC') or die;

$version = new JVersion;
if ($version->isCompatible('3.0')) :
JHtml::_('bootstrap.tooltip');
endif;

$pview      = JFactory::getApplication()->input->get('pview', 'kontrollcenter');
$db = JFactory::getDBO();
$query = 'SELECT id,CONCAT_WS(" | ",id,date1,data1) as title, YEAR(date1) as date FROM `#__eiko_einsatzberichte` WHERE state=1 OR state=0 ORDER BY `date1` DESC';
$db->setQuery($query);
$reportDb = $db->loadObjectList();
//print_r ($reportDb);
$current_id = $reportDb[0]->id;
$reports[] = JHTML::_('select.option', '', 'bitte auswählen', 'id', 'title');
if (count($reportDb)) $reports = array_merge($reports, $reportDb); 
$ReportList = JHTML::_('select.genericlist', $reports, 'rep_id',' class="input-xlarge"', 'id', 'title', $current_id);
		  
 
?>
  
  
<form action="index.php" method="post" name="adminForm" id="adminForm">
	<div class="row-fluid">
		<div class="span10 form-horizontal">
        
            <fieldset class="adminform">
            <h1>Flash-Uploader für Einsatzbilder</h1>
				<div class="control-group">
					<div class="control-label">Welchem Einsatzbericht sollen die Bilder zugeordnet werden ?</div>
					<div class="controls"><?php echo $ReportList;?></div>
				</div>
				<input type="submit" class="btn btn-primary" value=" weiter " onclick="window.location='index.php?option=om_einsatzkomponente&view=kontrollcenter'" />
            </fieldset>
            
    	</div>
    </div>
        
   <div class="clr"></div>
<input type="hidden" name="option" value="com_einsatzkomponente" />
<input type="hidden" name="view" value="swfupload" />
<input type="hidden" name="pview" value="<?php echo $pview;?>" /> 
</form>
<?php
?>
