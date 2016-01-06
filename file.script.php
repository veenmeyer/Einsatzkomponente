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
class com_einsatzkomponenteInstallerScript {
	public function install($parent) {
		
		// $parent is the class calling this method
		$parent->getParent()->setRedirectURL('index.php?option=com_einsatzkomponente&view=installation');
	}
	public function uninstall($parent) {
		echo '<h1>Die Datenbanktabellen müssen Sie manuell löschen ...</h1>';
   }
										
	public function update($parent) {
		// $parent is the class calling this method 
		
		// Überflüssige Files löschen
		jimport( 'joomla.filesystem.file' );
		jimport( 'joomla.filesystem.folder' );

		if (JFolder::delete(JPATH_ROOT.'/components/com_einsatzkomponente/views/einsatzberichte_neu')): endif;
		if (JFile::delete(JPATH_ROOT.'/components/com_einsatzkomponente/models/einsatzberichte_neu.php')): endif;
		if (JFile::delete(JPATH_ROOT.'/components/com_einsatzkomponente/models/forms/filter_einsatzberichte_neu.xml')) : endif;
		if (JFile::delete(JPATH_ROOT.'/components/com_einsatzkomponente/controllers/einsatzberichte_neu.php')) : endif;
		
		// DB-Sicherung anlegen

		$bug='0';	

		$queries[] = "CREATE TABLE IF NOT EXISTS #__bak_eiko_alarmierungsarten LIKE #__eiko_alarmierungsarten";
		$queries[] = "TRUNCATE TABLE #__bak_eiko_alarmierungsarten";
		$queries[] = "ALTER TABLE #__bak_eiko_alarmierungsarten DISABLE KEYS";
		$queries[] = "INSERT INTO #__bak_eiko_alarmierungsarten SELECT * FROM #__eiko_alarmierungsarten";
		$queries[] = "ALTER TABLE #__bak_eiko_alarmierungsarten ENABLE KEYS";

		//$queries[] = "CREATE TABLE IF NOT EXISTS #__bak_eiko_ausruestung LIKE #__eiko_ausruestung";
		//$queries[] = "TRUNCATE TABLE #__bak_eiko_ausruestung";
		//$queries[] = "ALTER TABLE #__bak_eiko_ausruestung DISABLE KEYS";
		//$queries[] = "INSERT INTO #__bak_eiko_ausruestung SELECT * FROM #__eiko_ausruestung";
		//$queries[] = "ALTER TABLE #__bak_eiko_ausruestung ENABLE KEYS";

		$queries[] = "CREATE TABLE IF NOT EXISTS #__bak_eiko_einsatzarten LIKE #__eiko_einsatzarten";
		$queries[] = "TRUNCATE TABLE #__bak_eiko_einsatzarten";
		$queries[] = "ALTER TABLE #__bak_eiko_einsatzarten DISABLE KEYS";
		$queries[] = "INSERT INTO #__bak_eiko_einsatzarten SELECT * FROM #__eiko_einsatzarten";
		$queries[] = "ALTER TABLE #__bak_eiko_einsatzarten ENABLE KEYS";

		$queries[] = "CREATE TABLE IF NOT EXISTS #__bak_eiko_einsatzberichte LIKE #__eiko_einsatzberichte";
		$queries[] = "TRUNCATE TABLE #__bak_eiko_einsatzberichte";
		$queries[] = "ALTER TABLE #__bak_eiko_einsatzberichte DISABLE KEYS";
		$queries[] = "INSERT INTO #__bak_eiko_einsatzberichte SELECT * FROM #__eiko_einsatzberichte";
		$queries[] = "ALTER TABLE #__bak_eiko_einsatzberichte ENABLE KEYS";

		$queries[] = "CREATE TABLE IF NOT EXISTS #__bak_eiko_fahrzeuge LIKE #__eiko_fahrzeuge";
		$queries[] = "TRUNCATE TABLE #__bak_eiko_fahrzeuge";
		$queries[] = "ALTER TABLE #__bak_eiko_fahrzeuge DISABLE KEYS";
		$queries[] = "INSERT INTO #__bak_eiko_fahrzeuge SELECT * FROM #__eiko_fahrzeuge";
		$queries[] = "ALTER TABLE #__bak_eiko_fahrzeuge ENABLE KEYS";

		$queries[] = "CREATE TABLE IF NOT EXISTS #__bak_eiko_gmap_config LIKE #__eiko_gmap_config";
		$queries[] = "TRUNCATE TABLE #__bak_eiko_gmap_config";
		$queries[] = "ALTER TABLE #__bak_eiko_gmap_config DISABLE KEYS";
		$queries[] = "INSERT INTO #__bak_eiko_gmap_config SELECT * FROM #__eiko_gmap_config";
		$queries[] = "ALTER TABLE #__bak_eiko_gmap_config ENABLE KEYS";

		$queries[] = "CREATE TABLE IF NOT EXISTS #__bak_eiko_images LIKE #__eiko_images";
		$queries[] = "TRUNCATE TABLE #__bak_eiko_images";
		$queries[] = "ALTER TABLE #__bak_eiko_images DISABLE KEYS";
		$queries[] = "INSERT INTO #__bak_eiko_images SELECT * FROM #__eiko_images";
		$queries[] = "ALTER TABLE #__bak_eiko_images ENABLE KEYS";

		$queries[] = "CREATE TABLE IF NOT EXISTS #__bak_eiko_organisationen LIKE #__eiko_organisationen";
		$queries[] = "TRUNCATE TABLE #__bak_eiko_organisationen";
		$queries[] = "ALTER TABLE #__bak_eiko_organisationen DISABLE KEYS";
		$queries[] = "INSERT INTO #__bak_eiko_organisationen SELECT * FROM #__eiko_organisationen";
		$queries[] = "ALTER TABLE #__bak_eiko_organisationen ENABLE KEYS";

		//$queries[] = "CREATE TABLE IF NOT EXISTS #__bak_eiko_tickerkat LIKE #__eiko_tickerkat";
		//$queries[] = "ALTER TABLE #__bak_eiko_tickerkat DISABLE KEYS";
		//$queries[] = "TRUNCATE TABLE #__bak_eiko_tickerkat";
		//$queries[] = "INSERT INTO #__bak_eiko_tickerkat SELECT * FROM #__eiko_tickerkat";
		//$queries[] = "ALTER TABLE #__bak_eiko_tickerkat ENABLE KEYS";

		foreach ($queries as $sql) {
			$db = JFactory::getDbo();
			$query = "$sql";
			$db->setQuery($query);
			try {
			$result = $db->execute();
			} catch (Exception $e) {
			print_r ($e).exit;  
			$bug = '1';
			}
		}
		
// ------------------ Update -------------------------------------------------------------------------
	$db = JFactory::getDbo();
	$query = "CREATE TABLE IF NOT EXISTS `#__eiko_ausruestung` (`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',`name` VARCHAR(255)  NOT NULL ,`image` VARCHAR(255)  NOT NULL ,`beschreibung` TEXT NOT NULL ,`created_by` INT(11)  NOT NULL ,`checked_out` INT(11)  NOT NULL ,`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',`ordering` INT(11)  NOT NULL ,`state` TINYINT(1)  NOT NULL ,PRIMARY KEY (`id`)) DEFAULT COLLATE=utf8_general_ci;";
	$db->setQuery($query);
	try {
	$result = $db->execute();
	} catch (Exception $e) {
	echo '<h2>Fehler in Query: '.$query.' : </h2>';  
	print_r ($e).'<br/><br/>';exit;
	}

	$db = JFactory::getDbo();
	$query = "CREATE TABLE IF NOT EXISTS `#__eiko_tickerkat` ( `id` int(11) unsigned NOT NULL AUTO_INCREMENT,  `asset_id` int(10) unsigned NOT NULL DEFAULT '0',  `title` varchar(255) NOT NULL,  `image` varchar(255) NOT NULL,  `beschreibung`text NOT NULL,  `ordering` int(11) NOT NULL,  `state` tinyint(1) NOT NULL,  `created_by` int(11) NOT NULL,  `checked_out` int(11) NOT NULL,  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',  PRIMARY KEY (`id`)) DEFAULT COLLATE=utf8_general_ci;";
	$db->setQuery($query);
	try {
	$result = $db->execute();
	} catch (Exception $e) {
	echo '<h2>Fehler in Query: '.$query.' : </h2>';  
	print_r ($e).'<br/><br/>';exit;
	}
// -------------------------------------------------------------------------------------------------
		
		
		$parent->getParent()->setRedirectURL('index.php?option=com_einsatzkomponente&view=installation');
		
   }
   
   
	public function preflight($type, $parent) {
		echo '<h1>Bitte einen Moment geduld ...</h1>';
   }
	public function postflight($type, $parent) {
		echo '<h1>Danke für die Installation ...</h1>';
   }
												
												
												
}