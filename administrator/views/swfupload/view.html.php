<?php
/**
* @package		JJ SWFUpload
* @author		JoomJunk
* @copyright	Copyright (C) 2011 - 2012 JoomJunk. All Rights Reserved
* @license		http://www.gnu.org/licenses/gpl-3.0.html
*/
 
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.view' );
class EinsatzkomponenteViewSWFUpload extends JViewLegacy
{
	public function display($tpl = null)
	{
		$this->state		= $this->get('State');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		
	    $this->addToolbar();
		
		jimport('joomla.environment.uri' );
 
		$document = JFactory::getDocument();
		$document->addScript(JURI::root().'administrator/components/com_einsatzkomponente/swfupload/swfupload.js');
		$document->addScript(JURI::root().'administrator/components/com_einsatzkomponente/swfupload/swfupload.queue.js');
		$document->addScript(JURI::root().'administrator/components/com_einsatzkomponente/swfupload/fileprogress.js');
		$document->addScript(JURI::root().'administrator/components/com_einsatzkomponente/swfupload/handlers.js');
		$document->addStyleSheet(JURI::root().'administrator/components/com_einsatzkomponente/swfupload/default.css');
		
		$session = JFactory::getSession();
		
		$params = JComponentHelper::getParams('com_einsatzkomponente');
		
		$rep_id = JRequest::getVar('rep_id', 0);   // Einsatz_ID holen für Zuordnung der Bilder in der Datenbank
		$watermark      = JRequest::getVar('watermark_image', $params->get('watermark_image'));

		$swfUploadHeadJs ='
		var swfu;
 
		window.onload = function()
		{
 
		var settings = 
		{
			flash_url : "'.JURI::root().'administrator/components/com_einsatzkomponente/swfupload/swfupload.swf",
 
			upload_url: "index.php",
			post_params:
			{
				"option" : "com_einsatzkomponente",
				"controller" : "swfupload",
				"task" : "upload",
				"rep_id" : "'.$rep_id.'",
				"watermark_image" : "'.$watermark.'",
				"'.$session->getName().'" : "'.$session->getId().'",
				"format" : "raw"
			}, 
			file_size_limit : 0,
			file_types : "*.*",
			file_types_description : "All Files",
			file_upload_limit : 100,
			file_queue_limit : 100,
			custom_settings : 
			{
				progressTarget : "fsUploadProgress",
				upload_target : "divFileProgressContainer",
				cancelButtonId : "btnCancel"
			},
			debug: false,
 
			// Button settings
			button_image_url: "'.JURI::root().'administrator/components/com_einsatzkomponente/swfupload/upload_button.png",
			button_width: "84",
			button_height: "29",
			button_placeholder_id: "spanButtonPlaceHolder",
			button_text_left_padding: 5,
			button_text_top_padding: 6,
			button_text: \'<span class="theFont">'.JText::_( 'Durchsuchen' ).'</span>\',
			button_text_style: ".theFont { font-size: 13px; }",
 
			// The event handler functions are defined in handlers.js
			file_queued_handler : fileQueued,
			file_queue_error_handler : fileQueueError,
			file_dialog_complete_handler : fileDialogComplete,
			upload_start_handler : uploadStart,
			upload_progress_handler : uploadProgress,
			upload_error_handler : uploadError,
			upload_success_handler : uploadSuccess,
			upload_complete_handler : uploadComplete,
			queue_complete_handler : queueComplete	// Queue plugin event
		};
		swfu = new SWFUpload(settings);
		};
 
		';
 
		//add the javascript to the head of the html document
		$document->addScriptDeclaration($swfUploadHeadJs);
		
		parent::display($tpl);
	}
	protected function addToolbar()
	{
		require_once JPATH_COMPONENT.'/helpers/einsatzkomponente.php';
		$state	= $this->get('State');
		$canDo	= EinsatzkomponenteHelper::getActions($state->get('filter.category_id'));
		$user	= JFactory::getUser();
		JRequest::setVar('hidemainmenu', true);
		$text = JText::_('Flash-Uploader für Einsatzbilder');
		JToolBarHelper::title(   JText::_( 'Flash-Uploader für Einsatzbilder' ), 'upload' );
		JToolBarHelper::preferences('com_einsatzkomponente');
	}
	
}
?>