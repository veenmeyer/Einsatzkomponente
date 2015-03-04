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
 * View class for a list of Einsatzkomponente.
 */
class EinsatzkomponenteViewKategorien extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->state		= $this->get('State');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			throw new Exception(implode("\n", $errors));
		}
        
		EinsatzkomponenteHelper::addSubmenu('kategorien');
        
		$this->addToolbar();
        
		$version = new JVersion;
        if ($version->isCompatible('3.0')) :
        $this->sidebar = JHtmlSidebar::render();
		endif;

		parent::display($tpl);
	}
	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
		require_once JPATH_COMPONENT.'/helpers/einsatzkomponente.php';
		$state	= $this->get('State');
		$canDo	= EinsatzkomponenteHelper::getActions($state->get('filter.category_id'));
		JToolBarHelper::title(JText::_('COM_EINSATZKOMPONENTE_TITLE_KATEGORIEN'), 'kategorien.png');
        //Check if the form exists before showing the add/edit buttons
        $formPath = JPATH_COMPONENT_ADMINISTRATOR.'/views/kategorie';
        if (file_exists($formPath)) {
            if ($canDo->get('core.create')) {
			    JToolBarHelper::addNew('kategorie.add','JTOOLBAR_NEW');
		    }
		    if ($canDo->get('core.edit') && isset($this->items[0])) {
			    JToolBarHelper::editList('kategorie.edit','JTOOLBAR_EDIT');
		    }
        }
		if ($canDo->get('core.edit.state')) {
            if (isset($this->items[0]->state)) {
			    JToolBarHelper::divider();
			    JToolBarHelper::custom('kategorien.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
			    JToolBarHelper::custom('kategorien.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
            } else if (isset($this->items[0])) {
                //If this component does not use state then show a direct delete button as we can not trash
                JToolBarHelper::deleteList('', 'kategorien.delete','JTOOLBAR_DELETE');
            }
//            if (isset($this->items[0]->state)) {
//			    JToolBarHelper::divider();
//			    JToolBarHelper::archiveList('kategorien.archive','JTOOLBAR_ARCHIVE');
//            }
            if (isset($this->items[0]->checked_out)) {
            	JToolBarHelper::custom('kategorien.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
            }
		}
        
        //Show trash and delete for components that uses the state field
        if (isset($this->items[0]->state)) {
		    if ($state->get('filter.state') == -2 && $canDo->get('core.delete')) {
			    JToolBarHelper::deleteList('', 'kategorien.delete','JTOOLBAR_EMPTY_TRASH');
			    JToolBarHelper::divider();
		    } else if ($canDo->get('core.edit.state')) {
			    //JToolBarHelper::trash('kategorien.trash','JTOOLBAR_TRASH');
                JToolBarHelper::deleteList('', 'kategorien.delete','JTOOLBAR_DELETE');
			    JToolBarHelper::divider();
		    }
        }
		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_einsatzkomponente');
		}
		
		$version = new JVersion;
        if ($version->isCompatible('3.0')) :
        //Set sidebar action - New in 3.0
		JHtmlSidebar::setAction('index.php?option=com_einsatzkomponente&view=kategorien');
		$options = '';
		$options[] = JHtml::_('select.option', '1', 'JPUBLISHED');
		$options[] = JHtml::_('select.option', '0', 'JUNPUBLISHED');
		$options[] = JHtml::_('select.option', '*', 'JALL');
		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_PUBLISHED'),
			'filter_published',
			JHtml::_('select.options', $options, "value", "text", $this->state->get('filter.state'), true)
		);
		endif;
		
        $this->extra_sidebar = '';
		
        
	}

    
	protected function getSortFields()
	{
		return array(
		'a.id' => JText::_('JGRID_HEADING_ID'),
		'a.ordering' => JText::_('JGRID_HEADING_ORDERING'),
		'a.title' => JText::_('COM_EINSATZKOMPONENTE_KATEGORIEN_TITLE'),
		'a.image' => JText::_('COM_EINSATZKOMPONENTE_KATEGORIEN_IMAGE'),
		'a.state' => JText::_('JSTATUS'),
		'a.created_by' => JText::_('COM_EINSATZKOMPONENTE_KATEGORIEN_CREATED_BY'),
		);
	}
    
}
