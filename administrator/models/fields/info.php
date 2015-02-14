<?php
/**
 * @version     3.0.0
 * @package     com_einsatzkomponente
 * @copyright   Copyright (C) 2013 by Ralf Meyer. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ralf Meyer <webmaster@feuerwehr-veenhusen.de> - http://einsatzkomponente.de
 */

defined('JPATH_BASE') or die;

/**
 * displays the information panel for SimpleCalendar
 *
 * @package     com_simplecalendar
 * @subpackage  settings
 * @since       3.0
 */
class JFormFieldInfo extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since   3.0
	 */
	protected $type = 'info';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string	The field input markup.
	 * @since   3.0
	 */
	 
	 
	protected function getInput()
	{
		
		$document = JFactory::getDocument();
		require_once JPATH_SITE.'/administrator/components/com_einsatzkomponente/helpers/einsatzkomponente.php'; 
		$html = array();
		$val ='';
		$val= EinsatzkomponenteHelper::getValidation();

		if ($val == '12') : 
		$html[] = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">x</button><span aria-hidden="true" class="icon-cancel"></span> ' . JText::_('<p>Diese Version von <b>Einsatzkomponente</b> ist validiert. <br/> Vielen Dank für Ihre Unterstützung.</p><p>- Die Copyrights im Frontend, sollten jetzt alle entfernt sein.</p>');		
		else:
		$html[] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">x</button><span aria-hidden="true" class="icon-cancel"></span>Diese Version von <b>Einsatzkomponente</b> ist nicht validiert. Sie können selbstverständlich <b>alle Funktionen</b> kostenlos nutzen,<br/> aber über eine kleine Unterstützung an die Entwickler, würden wir uns natürlich sehr freuen. <br/>Helfen Sie uns, damit wir Ihnen dieses und weitere Projekte auch in Zukunft kostenlos und ohne Werbung anbieten können. <br/><br/>Schicken Sie eine Anfrage via Mail an validate@einsatzkomponente.de und holen Sie sich heute noch den Validation-Schlüssel.<br/><br/><p>kleiner Vorteil:<br/>- Das Copyright im Frontend wird dadurch entfernt.  ;-)<br/>- und bevorzugten Support im Forum und via Email</p></div><a target="_blank" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=9HDFKVJSKSEFY"><div>Spende über PAYPAL : <img border=0  src="https://www.paypalobjects.com/de_DE/DE/i/btn/btn_donateCC_LG.gif" /></div></a><br/><br/>
';		
		if ($val=='1') :	$html[] =  '<span class="label label-important">Achtung !! Dies ist keine gültige URL. (Wenden Sie sich bitte an den Support im Forum)</span>'; 	endif;
		if ($val=='2') :	$html[] =  '<span class="label label-important">Achtung !! Dies ist kein gültiges Passwort. (Wenden Sie sich bitte an den Support im Forum)</span>'; 	endif; 
		endif;
		$html[] = '<h2>Einsatzkomponente Version 3.x</h2>';
		if ($val=='12') :	$html[] = '';
		else:
		$html[] = '<h3>Entwickler-Version</h3>';
		endif;
		$html[] = '<p></p>';
		$html[] = '<p><a href="http://www.einsatzkomponente.de/" target="_blank">Supportforum: http://www.einsatzkomponente.de/</a></p>';	
		return implode($html);
	}
}