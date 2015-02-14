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
// try to set time limit
@set_time_limit(0);
// try to increase memory limit
if ((int) ini_get('memory_limit') < 32) {
          @ini_set('memory_limit', '64M');
		}
// Versions-Nummer 
$db = JFactory::getDbo();
$db->setQuery('SELECT manifest_cache FROM #__extensions WHERE name = "com_einsatzkomponente"');
$params = json_decode( $db->loadResult(), true );
	

$bug='0';	
		
?>
<div align="left">
<?php
		echo '<h2>'.JTEXT::_('Installationsmanager für die Einsatzkomponente Version ').$params['version'].'</h2>'; 
		
		?>
		<a target="_blank" href="http://www.einsatzkomponente.de/index.php"><img border=0  src="<?php echo JURI::base(); ?>components/com_einsatzkomponente/assets/images/komponentenbanner.jpg"/></a><br/><br/>
        <?php
// ------------------ Fahrzeugbilder -------------------------------------------------------------------------
	$discr = "Image-Ordner für die Einsatzkomponente";
	$dir = JPATH_ROOT.'/images/com_einsatzkomponente'; 
	if (!JFolder::exists($dir))   
	{
		echo 'Der '.$discr.' <span class="label label-important">existiert nicht</span>.<br/>';
		$source = JPATH_ROOT.'/'.'media/com_einsatzkomponente/'; 
		$target = JPATH_ROOT.'/images/com_einsatzkomponente/';
		echo 'Kopiere:&nbsp;&nbsp;&nbsp;'.$source.'&nbsp;&nbsp;&nbsp;&nbsp;<b>nach:</b>&nbsp;&nbsp;&nbsp;&nbsp;'.$target.'<br/>';
		JFolder::copy($source,$target);
			if (!JFolder::exists($dir))   
			{
			echo 'Der '.$discr.' <span class="label label-important">wurde nicht erstellt !!!!</span>.<br/><br/>';$bug='1'; 
			}
			else {
					echo 'Der '.$discr.' <span class="label label-success">wurde erstellt.</span>.<br/><br/>'; 
				}
		
	}
	else {
		echo 'Der '.$discr.' <span class="label label-success">existiert</span>.<br/><br/>'; 
		}
// ------------------ Check GMap-Config Datenbanktabelle ------------------------------------------------------

$db = JFactory::getDbo();
$db->setQuery('SELECT id FROM #__eiko_gmap_config WHERE id = "1"');
$check_gmap = $db->loadResult();

if ($check_gmap) {
	echo 'Die GMap-Konfigurationstabelle <span class="label label-success">existiert.</span>.<br/><br/>'; 
}
else
{
	$db = JFactory::getDbo();
	$query = "INSERT INTO `#__eiko_gmap_config`(`id`) VALUES (1)";
	$db->setQuery($query);
	try {
	// Execute the query in Joomla 3.0.
	$result = $db->execute();
	} catch (Exception $e) {
	//print the errors
	echo 'Die GMap-Konfigurationstabelle wurde <span class="label label-important">nicht erstellt.</span>.<br/><br/>';  
	print_r ($e).'<br/><br/>';$bug = '1';
	}
	$db = JFactory::getDbo();
	$db->setQuery('SELECT id FROM #__eiko_gmap_config WHERE id = "1"');
	$check_gmap = $db->loadResult();
	if ($check_gmap) {
	echo 'Die GMap-Konfigurationstabelle wurde <span class="label label-info">erstellt.</span>.<br/><br/>'; 
	
	$db = JFactory::getDbo();
    $query = 'UPDATE `#__eiko_gmap_config` SET `gmap_alarmarea` = "53.28071418254047,7.416630163574155|53.294772929932165,7.4492458251952485|53.29815865222114,7.4767116455077485|53.31313468829642,7.459888830566342|53.29949234792138,7.478256597900327|53.29815865222114,7.506409063720639|53.286461382800795,7.521686926269467|53.26726681991669,7.499027624511655|" WHERE `id` = 1;';	
	$db->setQuery($query); 
	//execute db object
	try {
	// Execute the query in Joomla 3.0.
	$result = $db->execute();
	} catch (Exception $e) {
	//print the errors
	print_r($e);
	}	
	}
	else{}
	}
// ------------------------------------ Alte Datenbanken vorhanden ? ---------------------------------------

	$db = JFactory::getDbo();
	$db->setQuery('SELECT id from #__reports');
	try {
	// Execute the query in Joomla 3.0.
	$check_db = $db->execute();
	} catch (Exception $e) {
	echo 'Frühere Datenbanktabellen #__reports_* <span class="label label-success">sind ncht vorhanden !!!!</span>.<br/><br/>';
	}	
	
	if ($check_db) : 
	echo 'Frühere Datenbanktabellen #__reports_* <span class="label label-important">sind vorhanden !!!!</span>.<br/><br/>';$bug ='2';
	endif;


// ------------------------------------------------------------------------------------------------------------


?>
<?php echo '<br/><br/>';?>

<?php if ($bug == '0') : ?>
<?php echo '<span class="label label-success">Installation erfolgreich ...</span><br/><br/>';?>
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
<?php echo '<span class="label label-important">Installation nicht erfolgreich ...</span><br/><br/>Versuchen Sie es nochmal, oder wenden Sie sich an das Supportforum : <a href="http://www.einsatzkomponente.de" target="_blank">http://www.einsatzkomponente.de</a>';?>
<?php endif; ?>

<?php if ($bug == '2') : ?>
<?php echo '<span class="label label-success">Installation erfolgreich ...</span><br/><br/>';?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<div align="center">
<input
   type="button"
   class="btn btn-primary"
   value=" weiter zum Kontrollzentrum "
   title=""
   onclick="window.location='index.php?option=com_einsatzkomponente&view=kontrollcenter'"
   />
<input
   type="button"
   class="btn btn-danger"
   value=" alte Datenbanktabellen importieren ? "
   title=""
   onclick="window.location='index.php?option=com_einsatzkomponente&view=datenimport'"
   />   </div>
</form>
<?php endif; ?>

</div>
<?php
?>
