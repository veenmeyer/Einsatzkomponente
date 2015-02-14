<?php 
/**
 * @version     3.0.0
 * @package     com_einsatzkomponente
 * @copyright   Copyright (C) by Ralf Meyer 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ralf Meyer <webmaster@feuerwehr-veenhusen.de> - http://einsatzkomponente.de
 */
// No direct access
defined('_JEXEC') or die;
jimport('joomla.application.component.view');
class EinsatzkomponenteViewInstallation extends JViewLegacy
{
  function display($tpl = null) 
  {
    $this->addToolBar();
 
    // Display the template
    parent::display($tpl);
  }
        
	protected function addToolbar()
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);
		JToolBarHelper::title(JText::_('Installationsmanager'), 'upload');
	}
	
	
}
?>
