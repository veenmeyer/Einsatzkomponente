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
		
		// DB-Sicherung anlegen

		$bug='0';	

		$queries[] = "CREATE TABLE IF NOT EXISTS #__bak_eiko_alarmierungsarten LIKE #__eiko_alarmierungsarten";
		$queries[] = "TRUNCATE TABLE #__bak_eiko_alarmierungsarten";
		$queries[] = "ALTER TABLE #__bak_eiko_alarmierungsarten DISABLE KEYS";
		$queries[] = "INSERT INTO #__bak_eiko_alarmierungsarten SELECT * FROM #__eiko_alarmierungsarten";
		$queries[] = "ALTER TABLE #__bak_eiko_alarmierungsarten ENABLE KEYS";

		$queries[] = "CREATE TABLE IF NOT EXISTS #__bak_eiko_ausruestung LIKE #__eiko_ausruestung";
		$queries[] = "TRUNCATE TABLE #__bak_eiko_ausruestung";
		$queries[] = "ALTER TABLE #__bak_eiko_ausruestung DISABLE KEYS";
		$queries[] = "INSERT INTO #__bak_eiko_ausruestung SELECT * FROM #__eiko_ausruestung";
		$queries[] = "ALTER TABLE #__bak_eiko_ausruestung ENABLE KEYS";

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

		$queries[] = "CREATE TABLE IF NOT EXISTS #__bak_eiko_tickerkat LIKE #__eiko_tickerkat";
		$queries[] = "ALTER TABLE #__bak_eiko_tickerkat DISABLE KEYS";
		$queries[] = "TRUNCATE TABLE #__bak_eiko_tickerkat";
		$queries[] = "INSERT INTO #__bak_eiko_tickerkat SELECT * FROM #__eiko_tickerkat";
		$queries[] = "ALTER TABLE #__bak_eiko_tickerkat ENABLE KEYS";

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
		
		$parent->getParent()->setRedirectURL('index.php?option=com_einsatzkomponente&view=installation');
		
   }
	public function preflight($type, $parent) {
		echo '<h1>Bitte einen Moment geduld ...</h1>';
   }
	public function postflight($type, $parent) {
		echo '<h1>Danke für die Installation ...</h1>';
   }
												
												
												
}