<?php
/**
* @package		JJ SWFUpload
* @author		JoomJunk
* @copyright	Copyright (C) 2011 - 2012 JoomJunk. All Rights Reserved
* @license		http://www.gnu.org/licenses/gpl-3.0.html
*/
 
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport('joomla.application.component.controlleradmin');
 
class EinsatzkomponenteControllerSWFUpload extends JControllerAdmin
{
	function __construct()
	{
		parent::__construct();
 
		// Register Extra tasks
		$this->registerTask( 'upload' ,  'upload'); 
	}
	function upload()
	{
		$smiley	= JRequest::get('post');
		$model = $this->getModel('swfupload');
		
		if ($model->upload($smiley, true)) 
		{
			echo 1;
		}
		$this->setRedirect( 'index.php?option=com_einsatzkomponente'); 
	}
	
}