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
jimport('joomla.application.component.controllerform');
/**
 * Einsatzbericht controller class.
 */
class EinsatzkomponenteControllerEinsatzbericht extends JControllerForm
{
    function __construct() {
        $this->view_list = 'einsatzberichte';
        parent::__construct();
    }
     function swf()  
    {    
	
        $pview      = JFactory::getApplication()->input->get('view', 'einsatzbericht');
		$rep_id      = JFactory::getApplication()->input->get('id', '0');

		if (parent::save()) :
		if ($rep_id == '0') :
		$db = JFactory::getDBO();
		$query = "SELECT id FROM #__eiko_einsatzberichte ORDER BY id DESC LIMIT 1";
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		$rep_id      = $rows[0]->id;
		$msg    = JText::_( 'Neuer Einsatzbericht gespeichert ! Sie können jetzt die Einsatzbilder zu diesem Einsatz hinzufügen.' );
        $this->setRedirect('index.php?option=com_einsatzkomponente&view=swfupload&pview='.$pview.'&rep_id='.$rep_id.'', $msg); 
		endif;
		
		else:
        $this->setRedirect('index.php?option=com_einsatzkomponente&view=einsatzbericht&layout=edit', $msg); 
		endif;
		
		if (!$rep_id == '0') :
        //$msg    = JText::_( '' );  
        $this->setRedirect('index.php?option=com_einsatzkomponente&view=swfupload&pview='.$pview.'&rep_id='.$rep_id.'', $msg); 
		endif;
		
    }
	//function  

//    function save() {
//		break;
//        parent::save();
//    }
	
}