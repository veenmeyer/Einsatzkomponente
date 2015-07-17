<?php
/**
 * @version     3.0.0
 * @package     com_einsatzkomponente
 * @copyright   Copyright (C) 2013 by Ralf Meyer. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ralf Meyer <webmaster@feuerwehr-veenhusen.de> - http://einsatzkomponente.de
 */
// No direct access.
defined('_JEXEC') or die;
jimport('joomla.application.component.controlleradmin');

/**
 * Einsatzberichte list controller class.
 */
class EinsatzkomponenteControllerEinsatzberichte extends JControllerAdmin
{
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function getModel($name = 'einsatzbericht', $prefix = 'EinsatzkomponenteModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
    
    
	/**
	 * Method to save the submitted ordering values for records via AJAX.
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public function saveOrderAjax()
	{
		// Get the input
		$input = JFactory::getApplication()->input;
		$pks = $input->post->get('cid', array(), 'array');
		$order = $input->post->get('order', array(), 'array');
		// Sanitize the input
		JArrayHelper::toInteger($pks);
		JArrayHelper::toInteger($order);
		// Get the model
		$model = $this->getModel();
		// Save the ordering
		$return = $model->saveorder($pks, $order);
		if ($return)
		{
			echo "1";
		}
		// Close the application
		JFactory::getApplication()->close();
	}
	
	
	public function delete()
	{
		// Check for request forgeries
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));

		// Get items to remove from the request.
		$cid = JFactory::getApplication()->input->get('cid', array(), 'array');
		

		if (!is_array($cid) || count($cid) < 1)
		{
			JLog::add(JText::_($this->text_prefix . '_NO_ITEM_SELECTED'), JLog::WARNING, 'jerror');
		}
		else
		{
			// Get the model.
			$model = $this->getModel();
			// Make sure the item ids are integers
			jimport('joomla.utilities.arrayhelper');
			JArrayHelper::toInteger($cid);

			// Remove the items.
			if ($model->delete($cid))
			{
					// Einsatzbilder in DB und auf Server löschen , wenn im Bericht vorhanden !!
					foreach ($cid as $key => $val) {
					$db = JFactory::getDBO();
					$query = 'SELECT id, image, thumb FROM `#__eiko_images` WHERE `report_id`="'.$val.'"';
					$db->setQuery($query);
					$images = $db->loadObjectList();
					foreach ($images as $key_x => $val_x) {
					if ($images[$key_x]->image) : $delete_image = '../'.$images[$key_x]->image; endif;
					if ($images[$key_x]->thumb) : $delete_thumb = '../'.$images[$key_x]->thumb; endif;
					@ unlink ( $delete_image );
					@ unlink ( $delete_thumb );
					}
					}
					foreach ($cid as $key => $val) {
					$db = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query->delete($db->quoteName('#__eiko_images'));
					$query->where($db->quoteName('report_id') . '='.$val.'');
					$db->setQuery($query);
					$result = $db->query(); 			
					}
				
			$this->setMessage(JText::plural($this->text_prefix . '_N_ITEMS_DELETED', count($cid)));
			}
			else
			{
				$this->setMessage($model->getError());
			}
		}
				$this->postDeleteHook($model, $cid);

		$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list, false));
	}
	
    public function sendMail() {

		// Check for request forgeries
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
		require_once JPATH_SITE.'/administrator/components/com_einsatzkomponente/helpers/einsatzkomponente.php'; // Helper-class laden

		// Get items to remove from the request.
		$cid = JFactory::getApplication()->input->get('cid', array(), 'array');
		

		if (!is_array($cid) || count($cid) < 1)
		{
			JLog::add(JText::_($this->text_prefix . '_NO_ITEM_SELECTED'), JLog::WARNING, 'jerror');
		}
		else
		{
		//$model = $this->getModel();
		$params = JComponentHelper::getParams('com_einsatzkomponente');
			// Make sure the item ids are integers
			jimport('joomla.utilities.arrayhelper');
			JArrayHelper::toInteger($cid);
			$msg = EinsatzkomponenteHelper::sendMail($cid);
        $this->setRedirect('index.php?option=com_einsatzkomponente&view=einsatzberichte', $msg); 
    }
	}

    public function article() {

	// Check for request forgeries
	JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
	require_once JPATH_SITE.'/administrator/components/com_einsatzkomponente/helpers/einsatzkomponente.php'; // Helper-class laden
	
	// Get items to remove from the request.
	$cid = JFactory::getApplication()->input->get('cid', array(), 'array');
	
	
	if (!is_array($cid) || count($cid) < 1)
	{
	    JLog::add(JText::_($this->text_prefix . '_NO_ITEM_SELECTED'), JLog::WARNING, 'jerror');
	}
	else
	{
	    //$model = $this->getModel();
	    $params = JComponentHelper::getParams('com_einsatzkomponente');
	    // Make sure the item ids are integers
	    jimport('joomla.utilities.arrayhelper');
	    JArrayHelper::toInteger($cid);
	    $msg = EinsatzkomponenteHelper::article($cid);
	    $this->setRedirect('index.php?option=com_einsatzkomponente&view=einsatzberichte', $msg); 
	 }
    }
    public function pdf() {
    	// Check for request forgeries
	JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
	require_once JPATH_SITE.'/administrator/components/com_einsatzkomponente/helpers/einsatzkomponente.php'; // Helper-class laden
	
	// Get items to remove from the request.
	$cid = JFactory::getApplication()->input->get('cid', array(), 'array');
	
	if (!is_array($cid) || count($cid) < 1)
	{
	    JLog::add(JText::_($this->text_prefix . '_NO_ITEM_SELECTED'), JLog::WARNING, 'jerror');
	}
	else
	{
	    $msg = EinsatzkomponenteHelper::pdf($cid);
	    $this->setRedirect('index.php?option=com_einsatzkomponente&view=einsatzberichte', $msg); 
	}
    }
}
