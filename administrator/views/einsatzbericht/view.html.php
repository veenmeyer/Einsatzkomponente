
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
jimport('joomla.application.component.view');
/**
 * View to edit
 */
class EinsatzkomponenteViewEinsatzbericht extends JViewLegacy
{
	protected $state;
	protected $item;
	protected $form;
	
	protected $gmap_config;
	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		require_once JPATH_SITE.'/administrator/components/com_einsatzkomponente/helpers/einsatzkomponente.php'; // Helper-class laden
		require_once JPATH_SITE.'/administrator/components/com_einsatzkomponente/fpdf/fpdf.php'; //PHP PDF-Export Klasse laden
		$this->state	= $this->get('State');
		$this->item		= $this->get('Item');
		$this->form		= $this->get('Form');
		$this->gmap_config = EinsatzkomponenteHelper::load_gmap_config(); // GMap-Config aus helper laden 
		
 		$document = JFactory::getDocument();
		// Import Jquery
		$version = new JVersion;
		if ($version->isCompatible('3.0')) :
		else:
		$document->addScript('../components/com_einsatzkomponente/assets/jquery/jquery1.9.1.js');
		endif;
		// prüfen ob jquery geladen wurde
		echo "<script type=\"text/javascript\">
		if(typeof jQuery == \"function\")
		else
		  alert(\"jQuery nicht geladen\");
		</script>";
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors));
		}
		
		// Load JQuery Framework
		//JHtml::_('jquery.framework');   // added_130207
		$this->addToolbar();
		parent::display($tpl);
	}
	/**
	 * Add the page title and toolbar.
	 */
	protected function addToolbar()
	{
		
		JFactory::getApplication()->input->set('hidemainmenu', true);
		$user		= JFactory::getUser();
		$isNew		= ($this->item->id == 0);
        if (isset($this->item->checked_out)) {
		    $checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
        } else {
            $checkedOut = false;
        }
		$canDo		= EinsatzkomponenteHelper::getActions();
		JToolBarHelper::title(JText::_('COM_EINSATZKOMPONENTE_TITLE_EINSATZBERICHT'), 'einsatzbericht.png');
		// If not checked out, can save the item.
		if (!$checkedOut && ($canDo->get('core.edit')||($canDo->get('core.create'))))
		{
			JToolBarHelper::apply('einsatzbericht.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('einsatzbericht.save', 'JTOOLBAR_SAVE');
		}
		if (!$checkedOut && ($canDo->get('core.create'))){
			JToolBarHelper::custom('einsatzbericht.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
		}
		// If an existing item, can save to a copy.
		if (!$isNew && $canDo->get('core.create')) {
			JToolBarHelper::custom('einsatzbericht.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
		}
		if (empty($this->item->id)) {
			JToolBarHelper::cancel('einsatzbericht.cancel', 'JTOOLBAR_CANCEL');
		}
		else {
			JToolBarHelper::cancel('einsatzbericht.cancel', 'JTOOLBAR_CLOSE');
		}
			JToolBarHelper::custom( 'einsatzbericht.swf', 'upload','upload', 'Flash Uploader',  false );
			JToolBarHelper::custom( 'einsatzbericht.pdf', 'uparrow','uparrow', 'PDf-Export',  false );
	}
	
}
