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
jimport('joomla.application.component.modelform');
jimport('joomla.event.dispatcher');
/**
 * Einsatzkomponente model.
 */
class EinsatzkomponenteModelEinsatzbericht extends JModelForm
{
    
    var $_item = null;
    
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since	1.6
	 */
	protected function populateState() 
	{
		
		$app = JFactory::getApplication('com_einsatzkomponente');
		// Load state from the request userState on edit or from the passed variable on default
        if (JFactory::getApplication()->input->get('layout') == 'edit') {
            $id = JFactory::getApplication()->getUserState('com_einsatzkomponente.edit.einsatzbericht.id');

        } else {
            $id = JFactory::getApplication()->input->get('id');
            JFactory::getApplication()->setUserState('com_einsatzkomponente.edit.einsatzbericht.id', $id);
        }
		$this->setState('einsatzbericht.id', $id);
		// Load the parameters.
		$params = $app->getParams();
        $params_array = $params->toArray();
        if(isset($params_array['item_id'])){
            $this->setState('einsatzbericht.id', $params_array['item_id']);
        }
		$this->setState('params', $params);
	}
        
	/**
	 * Method to get an ojbect.
	 *
	 * @param	integer	The id of the object to get.
	 *
	 * @return	mixed	Object on success, false on failure.
	 */
	public function &getData($id = null)
	{
		if ($this->_item === null)
		{
			$this->_item = false;
			if (empty($id)) {
				$id = $this->getState('einsatzbericht.id');
			}
			// Get a level row instance.
			$table = $this->getTable();
			// Attempt to load the row.
			if ($table->load($id))
			{
				// Check published state.
				if ($published = $this->getState('filter.published'))
				{
					if ($table->state != $published) {
						return $this->_item;
					}
				}
				// Convert the JTable to a clean JObject.
				$properties = $table->getProperties(1);
				//$properties[test]= 'testvalue';print_r ($properties);
				$this->_item = JArrayHelper::toObject($properties, 'JObject');
			} elseif ($error = $table->getError()) {
				$this->setError($error);
			}
		}
		return $this->_item;
	}
    
	public function getTable($type = 'Einsatzbericht', $prefix = 'EinsatzkomponenteTable', $config = array())
	{   
        $this->addTablePath(JPATH_COMPONENT_ADMINISTRATOR.'/tables');
        return JTable::getInstance($type, $prefix, $config);
	}     
    
	/**
	 * Method to check in an item.
	 *
	 * @param	integer		The id of the row to check out.
	 * @return	boolean		True on success, false on failure.
	 * @since	1.6
	 */
	public function checkin($id = null)
	{
		// Get the id.
		$id = (!empty($id)) ? $id : (int)$this->getState('einsatzbericht.id');
		if ($id) {
            
			// Initialise the table
			$table = $this->getTable();
			// Attempt to check the row in.
            if (method_exists($table, 'checkin')) {
                if (!$table->checkin($id)) {
                    $this->setError($table->getError());
                    return false;
                }
            }
		}
		return true;
	}
	/**
	 * Method to check out an item for editing.
	 *
	 * @param	integer		The id of the row to check out.
	 * @return	boolean		True on success, false on failure.
	 * @since	1.6
	 */
	public function checkout($id = null)
	{
		// Get the user id.
		$id = (!empty($id)) ? $id : (int)$this->getState('einsatzbericht.id');
		if ($id) {
            
			// Initialise the table
			$table = $this->getTable();
			// Get the current user object.
			$user = JFactory::getUser();
			// Attempt to check the row out.
            if (method_exists($table, 'checkout')) {
                if (!$table->checkout($user->get('id'), $id)) {
                    $this->setError($table->getError());
                    return false;
                }
            }
		}
		return true;
	}    
    
	/**
	 * Method to get the profile form.
	 *
	 * The base form is loaded from XML 
     * 
	 * @param	array	$data		An optional array of data for the form to interogate.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	JForm	A JForm object on success, false on failure
	 * @since	1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_einsatzkomponente.einsatzbericht', 'einsatzbericht', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}
		return $form;
	}
	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.6
	 */
	protected function loadFormData()
	{
		$data = $this->getData(); 
        
        return $data;
	}
	/**
	 * Method to save the form data.
	 *
	 * @param	array		The form data.
	 * @return	mixed		The user id on success, false on failure.
	 * @since	1.6
	 */
	public function save($data)
	{
		$id = (!empty($data['id'])) ? $data['id'] : (int)$this->getState('einsatzbericht.id');
        $state = (!empty($data['state'])) ? 1 : 0;
        $user = JFactory::getUser();
        if($id) {
			
			
            //Check the user can edit this item
            $authorised = $user->authorise('core.edit', 'com_einsatzkomponente') || $authorised = $user->authorise('core.edit.own', 'com_einsatzkomponente.einsatzbericht');
            if($user->authorise('core.edit.state', 'com_einsatzkomponente') !== true && $state == 1){ //The user cannot edit the state of the item.
                $data['state'] = 0;
            }
        } else {
            //Check the user can create new items in this section
            $authorised = $user->authorise('core.create', 'com_einsatzkomponente');
            if($user->authorise('core.edit.state', 'com_einsatzkomponente') !== true && $state == 1){ //The user cannot edit the state of the item.
                $data['state'] = 0;
            }
        }
        if ($authorised !== true) {
            JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
            return false;
        }
			
			
			// Einsatz kopieren
			if($user->authorise('core.create', 'com_einsatzkomponente') == true){
		    $copy = JFactory::getApplication()->getUserState('com_einsatzkomponente.edit.einsatzbericht.copy');
        	if (!$copy == 0) :
            JFactory::getApplication()->setUserState('com_einsatzkomponente.edit.einsatzbericht.id', 0);
            JFactory::getApplication()->setUserState('com_einsatzkomponente.edit.einsatzbericht.copy', 0);
            $data['id'] = 0;
			endif; 
			}

		$app	= JFactory::getApplication();
		$params = $app->getParams('com_einsatzkomponente');
        $table = $this->getTable();
        if ($table->save($data) === true) {
		if ($params->get('eiko')) :
		if(!$_FILES['data']['name']['0'] =='') :
		$this->upload ($table->id,'data');
		endif;	
		endif;	

            return $id;
        } else {
            return false;
        }
        
	}
    
     public function delete($data)
    {
        $id = (!empty($data['id'])) ? $data['id'] : (int)$this->getState('einsatzbericht.id');
        if(JFactory::getUser()->authorise('core.delete', 'com_einsatzkomponente') !== true){
            JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
            return false;
        }
        $table = $this->getTable();
        if ($table->delete($data['id']) === true) {
            return $id;
        } else {
            return false;
        }
        
        return true;
    }
	
	public function hit()
	{
		$id = $this->getState('einsatzbericht.id');
		// update hits count
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);
		$query->update('#__eiko_einsatzberichte');
		$query->set('counter = (counter + 1)');
		$query->where('id = ' . (int) $id);
		$db->setQuery((string) $query);

		try
		{
			$db->execute();
		}
		catch (RuntimeException $e)
		{
			JError::raiseError(500, $e->getMessage());
		}
	}
    
	public function upload($id,$fieldName)
	{
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');

		$user	= JFactory::getUser();
 
		//this is the name of the field in the html form, filedata is the default name for swfupload
		//so we will leave it as that
		//$fieldName = 'Filedata';

		ini_set('memory_limit', -1);
		
		$params = JComponentHelper::getParams('com_einsatzkomponente');
		$count_data=count($_FILES['data']['name']) ;  ######### count the data #####
$count = 0;
while($count < $count_data)
{
		$fileName = $_FILES['data']['name'][$count];//echo $count.'= Name:'.$fileName.'<br/>';
		$fileName = JFile::makeSafe($fileName);
		$uploadedFileNameParts = explode('.',$fileName);
		$uploadedFileExtension = array_pop($uploadedFileNameParts);
 
		$fileTemp = $_FILES['data']['tmp_name'][$count];
		$count++;
		// remove invalid chars
//		$file_extension = strtolower(substr(strrchr($fileName,"."),1));
//		$name_cleared = preg_replace("#[^A-Za-z0-9 _.-]#", "", $fileName);
//		if ($name_cleared != $file_extension){
//			$fileName = $name_cleared;
//		}
					
					
					
						
		$rep_id = $id;   // Einsatz_ID holen für Zuordnung der Bilder in der Datenbank
		$watermark_image = JRequest::getVar('watermark_image', $params->get('watermark_image'));
		
		// Check ob Bilder in einen Unterordner (OrdnerName = ID-Nr.) abgespeichert werden sollen :
		if ($params->get('new_dir', '1')) :
		$rep_id_ordner = '/'.$rep_id;
		else:
		$rep_id_ordner = '';
		endif;
		
		$fileName = $rep_id.'-'.$fileName;
		
		
		 // Check if dir already exists
        if (!JFolder::exists(JPATH_SITE.'/'.$params->get('uploadpath', 'images/com_einsatzkomponente/einsatzbilder').$rep_id_ordner)) 
		{ JFolder::create(JPATH_SITE.'/'.$params->get('uploadpath', 'images/com_einsatzkomponente/einsatzbilder').$rep_id_ordner);     }
		else  {}
        if (!JFolder::exists(JPATH_SITE.'/'.$params->get('uploadpath', 'images/com_einsatzkomponente/einsatzbilder').'/thumbs'.$rep_id_ordner)) 
		{ JFolder::create(JPATH_SITE.'/'.$params->get('uploadpath', 'images/com_einsatzkomponente/einsatzbilder').'/thumbs'.$rep_id_ordner);  }
		else  {}
	    
		$uploadPath  = JPATH_SITE.'/'.$params->get('uploadpath', 'images/com_einsatzkomponente/einsatzbilder').$rep_id_ordner.'/'.$fileName ;
		$uploadPath_thumb  = JPATH_SITE.'/'.$params->get('uploadpath', 'images/com_einsatzkomponente/einsatzbilder').'/thumbs'.$rep_id_ordner.'/'.$fileName ;
 //echo $fileTemp.' xxxx '.$uploadPath;exit; 
		if(!JFile::upload($fileTemp, $uploadPath)) 
		{
			echo JText::_( 'Bild konnte nicht verschoben werden' );
			return;
		}
		else
		{
			

		 // Check if dir already exists
        if (!JFolder::exists(JPATH_SITE.'/'.$params->get('uploadpath', 'images/com_einsatzkomponente/einsatzbilder').'/thumbs')) 
		{ JFolder::create(JPATH_SITE.'/'.$params->get('uploadpath', 'images/com_einsatzkomponente/einsatzbilder').'/thumbs');        }
		else  {}
		
		
		// Exif-Information --- Bild richtig drehen
	    $bild = $uploadPath;
		$image = imagecreatefromstring(file_get_contents($bild));
		$exif = exif_read_data($bild);
		if(!empty($exif['Orientation'])) {
			switch($exif['Orientation']) {
				case 8:
					$image = imagerotate($image,90,0);
					break;
				case 3:
					$image = imagerotate($image,180,0);
					break;
				case 6:
					$image = imagerotate($image,-90,0);
					break;
			}
		}
		 
		// scale image
		list( $original_breite, $original_hoehe, $typ, $imgtag, $bits, $channels, $mimetype ) = @getimagesize( $bild );
		$ratio = imagesx($image)/imagesy($image); // width/height
		if($ratio > 1) {
			$width = $original_breite;
			$height = round($original_breite/$ratio);
		} else {
			$width = round($original_hoehe*$ratio);
			$height = $original_hoehe;
		}
		$scaled = imagecreatetruecolor($width, $height);
		imagecopyresampled($scaled, $image, 0, 0, 0, 0, $width, $height, imagesx($image), imagesy($image));
		 
		imagejpeg($scaled, $bild);
		//imagedestroy($image);
		imagedestroy($scaled);

		
		// thumbs erstellen und unter /thumbs abspeichern
	    $bild = $uploadPath;
		@list( $original_breite, $original_hoehe, $typ, $imgtag, $bits, $channels, $mimetype ) = @getimagesize( $bild );
		$speichern = $uploadPath_thumb;
     	$originalbild = imagecreatefromjpeg( $bild ); 
	    $maxbreite = $params->get('thumbwidth', '100');
	    $maxhoehe = $params->get('thumbhigh', '100');
	  	$quadratisch = $params->get('quadratisch', 'true');
		$qualitaet = '80';
 
    if ($quadratisch === 'false')
    {
        // Höhe und Breite für proportionales Thumbnail berechnen
        if ($original_breite > $maxbreite || $original_hoehe > $maxhoehe)
        {
            $thumb_breite = $maxbreite;
            $thumb_hoehe  = $maxhoehe;
            if ($thumb_breite / $original_breite * $original_hoehe > $thumb_hoehe)
            {
                $thumb_breite = round( $thumb_hoehe * $original_breite / $original_hoehe );
            }
            else
            {
                $thumb_hoehe = round( $thumb_breite * $original_hoehe / $original_breite );
            }
        }
        else
        {
            $thumb_breite = $original_breite;
            $thumb_hoehe = $original_hoehe;
        }
		
        // Thumbnail erstellen
        $thumb = imagecreatetruecolor( $thumb_breite, $thumb_hoehe );
        imagecopyresampled( $thumb, $originalbild, 0, 0, 0, 0, $thumb_breite, $thumb_hoehe, $original_breite, $original_hoehe );
    }
    else if ($quadratisch === 'true')
    {
        // Kantenlänge für quadratisches Thumbnail ermitteln
        $originalkantenlaenge = $original_breite < $original_hoehe ? $original_breite : $original_hoehe;
        $tmpbild = imagecreatetruecolor( $originalkantenlaenge, $originalkantenlaenge );
        if ($original_breite > $original_hoehe)
        {
            imagecopy( $tmpbild, $originalbild, 0, 0, round( $original_breite-$originalkantenlaenge )/2, 0, $original_breite, $original_hoehe );
        }
        else if ($original_breite <= $original_hoehe )
        {
            imagecopy( $tmpbild, $originalbild, 0, 0, 0, round( $original_hoehe-$originalkantenlaenge )/2, $original_breite, $original_hoehe );
        }
        // Thumbnail für Einsatzliste usw. erstellen
        $thumb = imagecreatetruecolor( $maxbreite, $maxbreite );
        imagecopyresampled( $thumb, $tmpbild, 0, 0, 0, 0, $maxbreite, $maxbreite, $originalkantenlaenge, $originalkantenlaenge );
    }

 
        imagejpeg( $thumb, $speichern, $qualitaet ); 
   		imagedestroy( $thumb );
			
			
			
			$custompath = $params->get('uploadpath', 'images/com_einsatzkomponente/einsatzbilder');
			chmod($uploadPath, 0644);
			chmod($uploadPath_thumb, 0644);
			$db = JFactory::getDBO();
			$query = 'INSERT INTO `#__eiko_images` SET `report_id`="'.$rep_id.'", `image`="'.$custompath.$rep_id_ordner.'/'.$fileName.'", `thumb`="'.$custompath.'/thumbs'.$rep_id_ordner.'/'.$fileName.'", `state`="1", `created_by`="'.$user->id.'"';
			$db->setQuery($query);
			$db->query();
			
		$db = JFactory::getDBO();
		$query = 'SELECT image FROM `#__eiko_einsatzberichte` WHERE `id` ="'.$rep_id.'" ';
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		$check_image      = $rows[0]->image;

		if ($params->get('titelbild_auto', '1')):
		if ($check_image == ''):
		$db		= JFactory::getDBO();
		$query	= $db->getQuery(true);
		$query->update('#__eiko_einsatzberichte');
		$query->set('image = "'.$custompath.$rep_id_ordner.'/'.$fileName.'" ');
		$query->where('`id` ="'.$rep_id.'"');
		$db->setQuery((string) $query);

		try
		{
			$db->execute();
		}
		catch (RuntimeException $e)
		{
			JError::raiseError(500, $e->getMessage());
		}
		endif;
		endif;

			echo JText::_( 'Bild wurde hochgeladen' ).'<br/>';
			
			
$source = JPATH_SITE.'/'.$params->get('uploadpath', 'images/com_einsatzkomponente/einsatzbilder').$rep_id_ordner.'/'.$fileName ; //the source file
$destination =  JPATH_SITE.'/'.$params->get('uploadpath', 'images/com_einsatzkomponente/einsatzbilder').$rep_id_ordner.'/'.$fileName ; //were to place the thumb
$watermark =  JPATH_SITE.'/administrator/components/com_einsatzkomponente/assets/images/watermark/'.$watermark_image.''; //the watermark files

    // Einsatzbilder resizen
	$image_resize = $params->get('image_resize', 'true');
    if ($image_resize === 'true'):
	$newwidth = $params->get('image_resize_max_width', '800');
	$newheight = $params->get('image_resize_max_height', '600');
    list($width, $height) = getimagesize($source);
    if($width > $height && $newheight < $height){
        $newheight = $height / ($width / $newwidth);
    } else if ($width < $height && $newwidth < $width) {
        $newwidth = $width / ($height / $newheight);   
    } else {
        $newwidth = $width;
        $newheight = $height;
    }
    $thumb = imagecreatetruecolor($newwidth, $newheight);
    $source_name = imagecreatefromjpeg($source);
    imagecopyresized($thumb, $source_name, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
	imagejpeg($thumb, $destination, 100);  
	endif;

    // Wasserzeichen einbauen
	$watermark_show = $params->get('watermark_show', 'true');
    if ($watermark_show === 'true'):
	$watermark_pos_x = $params->get('watermark_pos_x', '0');
	$watermark_pos_y = $params->get('watermark_pos_y', '60');
	list($sourcewidth,$sourceheight)=getimagesize($source);
	list($watermarkwidth,$watermarkheight)=getimagesize($watermark);

	$w_pos_x = $watermark_pos_x;
	$w_pos_y = $sourceheight-$watermark_pos_y;

	$source_img = imagecreatefromjpeg($source);
	$watermark_img = imagecreatefrompng($watermark);
	imagecopy($source_img, $watermark_img, $w_pos_x, $w_pos_y, 0, 0, $watermarkwidth,$watermarkheight);
	imagejpeg($source_img, $destination, 100);  
	imagedestroy ($source_img);
	imagedestroy ($watermark_img);
	endif;
		}} 
 // Ende der Schleife			
	}
	
	

}