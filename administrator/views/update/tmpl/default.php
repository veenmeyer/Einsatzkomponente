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
	
// ------------------ Update von Version 3.05 auf 3.06 beta ---------------------------------------------------

	$db = JFactory::getDbo();
	$db->setQuery('show columns from `#__eiko_tickerkat` where Field="id"');
	try {
	$check_tickerkat = $db->execute();
	} catch (Exception $e) {

	
$eiko_tickerkat = array(
  array('id' => '1','asset_id' => '0','title' => 'Brandeinsatz > Brandmeldeanlage (Fehlalarm)','image' => 'images/com_einsatzkomponente/images/list/brand_bma_fehl.png','beschreibung' => '','ordering' => '1','state' => '1','created_by' => '0','checked_out' => '0','checked_out_time' => '0000-00-00 00:00:00'),
  array('id' => '2','asset_id' => '0','title' => 'Brandeinsatz > Wohngebäude','image' => 'images/com_einsatzkomponente/images/list/brand_wohnhaus.png','beschreibung' => '','ordering' => '2','state' => '1','created_by' => '0','checked_out' => '0','checked_out_time' => '0000-00-00 00:00:00'),
  array('id' => '3','asset_id' => '0','title' => 'Brandeinsatz > Fahrzeugbrand','image' => 'images/com_einsatzkomponente/images/list/brand_pkw.png','beschreibung' => '','ordering' => '3','state' => '1','created_by' => '0','checked_out' => '0','checked_out_time' => '0000-00-00 00:00:00'),
  array('id' => '4','asset_id' => '0','title' => 'Brandeinsatz > Wald / Flächen','image' => 'images/com_einsatzkomponente/images/list/brand_wald_flaechen.png','beschreibung' => '','ordering' => '4','state' => '1','created_by' => '0','checked_out' => '0','checked_out_time' => '0000-00-00 00:00:00'),
  array('id' => '5','asset_id' => '0','title' => 'Techn. Hilfe > Hochwasser','image' => 'images/com_einsatzkomponente/images/list/TH_WASSER.png','beschreibung' => '','ordering' => '5','state' => '1','created_by' => '0','checked_out' => '0','checked_out_time' => '0000-00-00 00:00:00'),
  array('id' => '6','asset_id' => '0','title' => 'Techn. Hilfe > Öl / Benzin auf Straße','image' => 'images/com_einsatzkomponente/images/list/hilfe_oelspur.png','beschreibung' => '','ordering' => '6','state' => '1','created_by' => '0','checked_out' => '0','checked_out_time' => '0000-00-00 00:00:00'),
  array('id' => '7','asset_id' => '0','title' => 'Techn. Hilfe > Öl / Benzin auf Gewässer','image' => 'images/com_einsatzkomponente/images/list/hilfe_amtshilfe.png','beschreibung' => '','ordering' => '7','state' => '1','created_by' => '0','checked_out' => '0','checked_out_time' => '0000-00-00 00:00:00'),
  array('id' => '8','asset_id' => '0','title' => 'Techn. Rettung > Verkehrsunfall','image' => 'images/com_einsatzkomponente/images/list/hilfe_pkw_unfall.png','beschreibung' => '','ordering' => '8','state' => '1','created_by' => '0','checked_out' => '0','checked_out_time' => '0000-00-00 00:00:00'),
  array('id' => '9','asset_id' => '0','title' => 'Techn. Rettung > Wasserrettung','image' => 'images/com_einsatzkomponente/images/list/brand_sonstiges.png','beschreibung' => '','ordering' => '9','state' => '1','created_by' => '0','checked_out' => '0','checked_out_time' => '0000-00-00 00:00:00'),
  array('id' => '10','asset_id' => '0','title' => 'Techn. Rettung > Person in Notlage','image' => 'images/com_einsatzkomponente/images/list/hilfe_amtshilfe.png','beschreibung' => '','ordering' => '10','state' => '1','created_by' => '0','checked_out' => '0','checked_out_time' => '0000-00-00 00:00:00'),
  array('id' => '11','asset_id' => '0','title' => 'Techn. Hilfe > Sturm','image' => 'images/com_einsatzkomponente/images/list/hilfe_sturm.png','beschreibung' => '','ordering' => '11','state' => '1','created_by' => '0','checked_out' => '0','checked_out_time' => '0000-00-00 00:00:00'),
  array('id' => '12','asset_id' => '0','title' => 'Med. Einsatz > First Responder','image' => 'images/com_einsatzkomponente/images/list/med_sonstiges.png','beschreibung' => '','ordering' => '12','state' => '1','created_by' => '0','checked_out' => '0','checked_out_time' => '0000-00-00 00:00:00'),
  array('id' => '13','asset_id' => '0','title' => 'Techn. Hilfe > Amtshilfe','image' => 'images/com_einsatzkomponente/images/list/brand_sonstiges.png','beschreibung' => '','ordering' => '13','state' => '1','created_by' => '0','checked_out' => '0','checked_out_time' => '0000-00-00 00:00:00'),
  array('id' => '14','asset_id' => '0','title' => 'Gefahrgut > Leckage','image' => 'images/com_einsatzkomponente/images/list/gefahr_sonstige.png','beschreibung' => '','ordering' => '14','state' => '1','created_by' => '0','checked_out' => '0','checked_out_time' => '0000-00-00 00:00:00'),
  array('id' => '15','asset_id' => '0','title' => 'Techn. Hilfe > sonstige techn. Hilfeleistung','image' => 'images/com_einsatzkomponente/images/list/hilfe_amtshilfe.png','beschreibung' => '','ordering' => '15','state' => '1','created_by' => '0','checked_out' => '0','checked_out_time' => '0000-00-00 00:00:00'),
  array('id' => '16','asset_id' => '0','title' => 'Techn. Rettung > sonstige techn. Rettung','image' => 'images/com_einsatzkomponente/images/list/hilfe_amtshilfe.png','beschreibung' => '','ordering' => '16','state' => '1','created_by' => '0','checked_out' => '0','checked_out_time' => '0000-00-00 00:00:00'),
  array('id' => '17','asset_id' => '0','title' => 'Brandeinsatz > Sonstiges','image' => 'images/com_einsatzkomponente/images/list/Alarmuebung.png','beschreibung' => '','ordering' => '17','state' => '1','created_by' => '0','checked_out' => '0','checked_out_time' => '0000-00-00 00:00:00'),
  array('id' => '18','asset_id' => '0','title' => 'Med. Einsatz > sonstige medizinische Einsatz','image' => 'images/com_einsatzkomponente/images/list/med_sonstiges.png','beschreibung' => '','ordering' => '18','state' => '1','created_by' => '0','checked_out' => '0','checked_out_time' => '0000-00-00 00:00:00'),
  array('id' => '19','asset_id' => '0','title' => 'Brandeinsatz > Kleinbrand','image' => 'images/com_einsatzkomponente/images/list/Sicherheitswache.png','beschreibung' => '','ordering' => '19','state' => '1','created_by' => '0','checked_out' => '0','checked_out_time' => '0000-00-00 00:00:00'),
  array('id' => '20','asset_id' => '0','title' => 'Brandeinsatz > Gewerbebetrieb','image' => 'images/com_einsatzkomponente/images/list/brand_wohnhaus.png','beschreibung' => '','ordering' => '20','state' => '1','created_by' => '0','checked_out' => '0','checked_out_time' => '0000-00-00 00:00:00'),
  array('id' => '21','asset_id' => '0','title' => 'Brandeinsatz > Industriebetrieb','image' => 'images/com_einsatzkomponente/images/list/brand_wohnhaus.png','beschreibung' => '','ordering' => '21','state' => '1','created_by' => '0','checked_out' => '0','checked_out_time' => '0000-00-00 00:00:00'),
  array('id' => '22','asset_id' => '0','title' => 'Brandeinsatz > Brandmeldeanlage','image' => 'images/com_einsatzkomponente/images/list/brand_bma.png','beschreibung' => '','ordering' => '22','state' => '1','created_by' => '0','checked_out' => '0','checked_out_time' => '0000-00-00 00:00:00'),
  array('id' => '23','asset_id' => '0','title' => 'Gefahrgut > sonstiger Gefahrgut-Einsatz','image' => 'images/com_einsatzkomponente/images/list/brand_sonstiges.png','beschreibung' => '','ordering' => '23','state' => '1','created_by' => '0','checked_out' => '0','checked_out_time' => '0000-00-00 00:00:00')
);

$e ='';
$sql="CREATE TABLE IF NOT EXISTS `#__eiko_tickerkat` (
`id` int(11)  UNSIGNED NOT NULL AUTO_INCREMENT,
  `asset_id` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `beschreibung` text NOT NULL,
  `ordering` int(11) NOT NULL,
  `state` tinyint(1) NOT NULL,
  `created_by` int(11) NOT NULL,
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;";
	$db = JFactory::getDbo();
	$db->setQuery($sql); 
	try {
	$result = $db->execute();
	} catch (Exception $e) {
		print_r ($e);$bug='1';
	}	

//$sql = "ALTER TABLE `#__eiko_tickerkat` CHANGE `id` `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;";
//	$db = JFactory::getDbo();
//	$db->setQuery($sql); 
//	try {
//	$result = $db->execute();
//	} catch (Exception $e) {
//		print_r ($e);$bug='1';
//	}	


foreach($eiko_tickerkat as $data){

    $sql = 'INSERT INTO `#__eiko_tickerkat` (id, asset_id, title, image, beschreibung, ordering, state,created_by,checked_out,checked_out_time)
    VALUES ("'.$data["id"].'", "'.$data["asset_id"].'", "'.$data["title"].'", "'.$data["image"].'", "'.$data["beschreibung"].'", "'.$data["ordering"].'", "'.$data["state"].'", "'.$data["created_by"].'","'.$data["checked_out"].'","'.$data["checked_out_time"].'")';
	//echo $sql.'<br/>';
	$db = JFactory::getDbo();
	$db->setQuery($sql); 
	try {
	$result = $db->execute();
	} catch (Exception $e) {
		print_r ($e);$bug='1';
	}	
	
}  
	echo 'DB-Updates Version 3.6 erfolgreich <span class="label label-success">aktualisiert.</span>.<br/><br/>'; 


}

	$db = JFactory::getDbo();
	$query = "ALTER TABLE `#__eiko_einsatzberichte` CHANGE `tickerkat` `tickerkat` INT(10) NOT NULL;";
	$db->setQuery($query); 
	try {
	$result = $db->execute();
	} catch (Exception $e) {
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
