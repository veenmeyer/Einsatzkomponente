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
/**
 * View to edit
 */
class EinsatzkomponenteViewEinsatzfahrzeug extends JViewLegacy {
    protected $state;
    protected $item;
    protected $form;
    protected $params;
    /**
     * Display the view
     */
    public function display($tpl = null) {
        
		$app	= JFactory::getApplication();
        $user		= JFactory::getUser();
        
        $this->state = $this->get('State');
        $this->item = $this->get('Data');
        $this->params = $app->getParams('com_einsatzkomponente');
   		$this->form		= $this->get('Form');
        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors));
        }
        
		$document = JFactory::getDocument();
		if ($this->params->get('display_detail_bootstrap','0')) :
		// Import Bootstrap
 		$document->addScript('components/com_einsatzkomponente/assets/bootstrap/js/bootstrap.min.js');	
 		$document->addStyleSheet('components/com_einsatzkomponente/assets/bootstrap/css/bootstrap.min.css');
 		$document->addStyleSheet('components/com_einsatzkomponente/assets/bootstrap/css/bootstrap-responsive.min.css');
		endif;
		$document->addStyleDeclaration($this->params->get('fahrzeug_css','')); 
        
        if($this->_layout == 'edit') {
            
            $authorised = $user->authorise('core.create', 'com_einsatzkomponente');
            if ($authorised !== true) {
                throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
            }
        }
        if(isset($this->item->state) === '0') : throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'),'0'); endif;
        //if($this->item->state === '2') : throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'),'2'); endif;
        if(isset($this->item->state) === '-2') : throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'),'-2'); endif;
        
        $this->_prepareDocument();
        parent::display($tpl);
    }
	/**
	 * Prepares the document
	 */
	protected function _prepareDocument()
	{
		$app	= JFactory::getApplication();
		$menus	= $app->getMenu();
		$title	= null;
		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();
		if($menu)
		{
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		} else {
			$this->params->def('page_heading', JText::_('com_einsatzkomponente_DEFAULT_PAGE_TITLE'));
		}
		$title = $this->params->get('page_title', '');
		if (empty($title)) {
			$title = $app->getCfg('sitename');
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 1) {
			$title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 2) {
			$title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
		}
		$this->document->setTitle($title);
		if ($this->params->get('menu-meta_description'))
		{
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}
		if ($this->params->get('menu-meta_keywords'))
		{
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}
		if ($this->params->get('robots'))
		{
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}
	}        
    
}
