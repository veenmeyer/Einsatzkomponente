<?php
/**
 * @version     3.0.0
 * @package     com_einsatzkomponente
 * @copyright   Copyright (C) 2013 by Ralf Meyer. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ralf Meyer <webmaster@feuerwehr-veenhusen.de> - http://einsatzkomponente.de
 */
// No direct access
defined('_JEXEC') or die;
jimport( 'joomla.filesystem.file' );
jimport( 'joomla.filesystem.folder' );
// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_einsatzkomponente/assets/css/einsatzkomponente.css');

// Versions-Nummer 
$db = JFactory::getDbo();
$db->setQuery('SELECT manifest_cache FROM #__extensions WHERE name = "com_einsatzkomponente"');
$params = json_decode( $db->loadResult(), true );
	
$bug='0';	
		
?>
<div align="left">
<?php
		echo '<h2>'.JTEXT::_('Updatemanager für die Einsatzkomponente Version ').$params['version'].'</h2>'; 
		
		?>
		<a target="_blank" href="http://www.einsatzkomponente.de/index.php"><img border=0  src="<?php echo JURI::base(); ?>components/com_einsatzkomponente/assets/images/komponentenbanner.jpg"/></a><br/><br/>
        <?php
		

// ------------------ Update von Version 3.0 beta 3 auf 3.0 beta 4 --------------------------------------------------
	$db = JFactory::getDbo();
	$db->setQuery('show columns from `#__eiko_einsatzberichte` where Field="presse_label"');
	try {
	$check_presse = $db->execute();
	} catch (Exception $e) {
	print_r($e);$bug='1';
	}	
$check_presse = $check_presse->num_rows;
if (!$check_presse) {
	
	$db = JFactory::getDbo();
    $query = 'ALTER TABLE `#__eiko_einsatzberichte` ADD `presse_label` VARCHAR( 255 ) NOT NULL DEFAULT "Presselink" AFTER `gmap`';	
	$db->setQuery($query); 
	try {
	$result = $db->execute();
	} catch (Exception $e) {
	}	
	$db = JFactory::getDbo();
    $query = 'ALTER TABLE `#__eiko_einsatzberichte` ADD `presse2_label` VARCHAR( 255 ) NOT NULL DEFAULT "Presselink" AFTER `presse`';	
	$db->setQuery($query); 
	try {
	$result = $db->execute();
	} catch (Exception $e) {
	}	
	
	$db = JFactory::getDbo();
    $query = 'ALTER TABLE `#__eiko_einsatzberichte` ADD `presse3_label` VARCHAR( 255 ) NOT NULL DEFAULT "Presselink" AFTER `presse2`';	
	$db->setQuery($query); 
	try {
	$result = $db->execute();
	} catch (Exception $e) {
	}	
	
	echo 'DB-Updates  < 3.0 beta 4 erfolgreich <span class="label label-success">aktualisiert.</span>.<br/><br/>'; 
}
else {
	//echo 'Alle Daten sind bereits <span class="label label-success">aktuell</span><br/><br/>'; 
	}
	
// ------------------ Update von Version 3.04 auf 3.05 beta --------------------------------------------------
	$db = JFactory::getDbo();
	$db->setQuery('show columns from `#__eiko_einsatzberichte` where Field="status_fb"');
	try {
	$check_status_fb = $db->execute();
	} catch (Exception $e) {
	print_r($e);$bug='1';
	}	
$check_status_fb = $check_status_fb->num_rows;
if (!$check_status_fb) {
	
	$db = JFactory::getDbo();
    $query = 'ALTER TABLE `#__eiko_einsatzberichte` ADD `status_fb` VARCHAR( 255 ) NOT NULL DEFAULT "1" AFTER `gmap`';	
	$db->setQuery($query); 
	try {
	$result = $db->execute();
	} catch (Exception $e) {
	}	
}
else {
	}
	
	$db = JFactory::getDbo();
	$db->setQuery('show columns from `#__eiko_einsatzberichte` where Field="article_id"');
	try {
	$check_status_fb = $db->execute();
	} catch (Exception $e) {
	print_r($e);$bug='1';
	}	
$check_status_fb = $check_status_fb->num_rows;
if (!$check_status_fb) {
	
	$db = JFactory::getDbo();
    $query = 'ALTER TABLE `#__eiko_einsatzberichte` ADD `article_id` VARCHAR( 255 ) NOT NULL DEFAULT "0" AFTER `asset_id`';	
	$db->setQuery($query);  
	try {
	$result = $db->execute();
	} catch (Exception $e) {
	}	

	echo 'DB-Updates  < 3.05 beta erfolgreich <span class="label label-success">aktualisiert.</span>.<br/><br/>'; 
}
else {
	echo 'Alle Daten sind bereits <span class="label label-success">aktuell</span><br/><br/>'; 
	}
	
// ------------------------------------------------------------------------------------------------------------
?>
<?php echo '<br/><br/>';?>
<?php if ($bug == '0') : ?>
<?php echo '<span class="label label-success">Update erfolgreich ...</span><br/><br/>';?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<div align="center">
<input
   type="button"
   class="btn btn-primary"
   value=" weiter zum Kontrollzentrum "
   title=""
   onclick="window.location='index.php?option=com_einsatzkomponente&view=kontrollcenter'"
   /></div>
</form>
<?php endif; ?>
<?php if ($bug == '1') : ?>
<?php echo '<span class="label label-important">Update nicht erfolgreich ...</span><br/><br/>Versuchen Sie es nochmal, oder wenden Sie sich an das Supportforum : <a href="http://www.einsatzkomponente.de" target="_blank">http://www.einsatzkomponente.de</a>';?>
<?php endif; ?>
<?php if ($bug == '2') : ?>
<?php echo '<span class="label label-success">Update erfolgreich ...</span><br/><br/>';?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<div align="center">
<input
   type="button"
   class="btn btn-primary"
   value=" weiter zum Kontrollzentrum "
   title=""
   onclick="window.location='index.php?option=com_einsatzkomponente&view=kontrollcenter'"
   />
</div>
</form>
<?php endif; ?>
</div>
<?php
?>
