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
require_once JPATH_COMPONENT.'/controller.php';
/**
 * Einsatzbericht controller class.
 */
class EinsatzkomponenteControllerEinsatzbericht extends EinsatzkomponenteController
{
	/**
	 * Method to check out an item for editing and redirect to the edit form.
	 *
	 * @since	1.6
	 */
	 
	 
	public function edit()
	{
		$app			= JFactory::getApplication();
		// Get the previous edit id (if any) and the current edit id.
		$previousId = (int) $app->getUserState('com_einsatzkomponente.edit.einsatzbericht.id');
		$editId	= JFactory::getApplication()->input->getInt('id', null, 'array');
		// Set the user id for the user to edit in the session.
		$app->setUserState('com_einsatzkomponente.edit.einsatzbericht.id', $editId);
		// Get the model.
		$model = $this->getModel('Einsatzbericht', 'EinsatzkomponenteModel');
		// Check out the item
		if ($editId) {
            $model->checkout($editId);
		}
		// Check in the previous user.
		if ($previousId) {
            $model->checkin($previousId);
		}
		// Redirect to the edit screen.
		$this->setRedirect(JRoute::_('index.php?option=com_einsatzkomponente&view=einsatzberichtform&layout=edit&id='.$editId.'', false));
	}
	/**
	 * Method to save a user's profile data.
	 *
	 * @return	void
	 * @since	1.6
	 */
	public function save()
	{
		// Check for request forgeries.
		echo 'upload_max_filesize: '.ini_get('upload_max_filesize'), "<br/>post_max_size: " , ini_get('post_max_size');
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		// Initialise variables.
		$app	= JFactory::getApplication();
		$model = $this->getModel('Einsatzbericht', 'EinsatzkomponenteModel');
		// Get the user data.
		$data = JFactory::getApplication()->input->get('jform', array(), 'array');
		// Validate the posted data.
		$form = $model->getForm();
		if (!$form) {
			JError::raiseError(500, $model->getError());
			return false;
		}
		// Validate the posted data.
		$data = $model->validate($form, $data);
		// Check for errors.
		if ($data === false) {
			// Get the validation messages.
			$errors	= $model->getErrors();
			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
				if ($errors[$i] instanceof Exception) {
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				} else {
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}
			// Save the data in the session.
			$app->setUserState('com_einsatzkomponente.edit.einsatzbericht.data', $data);
			// Redirect back to the edit screen.
			$id = (int) $app->getUserState('com_einsatzkomponente.edit.einsatzbericht.id');
			$this->setRedirect(JRoute::_('index.php?option=com_einsatzkomponente&view=einsatzberichtform&layout=edit&id='.$id, false));
			return false;
		}
		// Attempt to save the data.
		$return	= $model->save($data);
		// Check for errors.
		if ($return === false) {
			// Save the data in the session.
			$app->setUserState('com_einsatzkomponente.edit.einsatzbericht.data', $data);
			// Redirect back to the edit screen.
			$id = (int)$app->getUserState('com_einsatzkomponente.edit.einsatzbericht.id');
			$this->setMessage(JText::sprintf('Save failed', $model->getError()), 'warning');
			$this->setRedirect(JRoute::_('index.php?option=com_einsatzkomponente&view=einsatzberichtform&layout=edit&id='.$id, false));
			return false;
		}
            
        // Check in the profile.
        if ($return) {
            $model->checkin($return);
        }
		
		$cid_article = '';
        if (!$return) {
		$db = JFactory::getDBO();
		$query = "SELECT id FROM #__eiko_einsatzberichte ORDER BY id DESC LIMIT 1";
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		$cid_article      = $rows[0]->id;
        }
		
		// Joomla-Artikel erstellen
		if ($return OR $cid_article) {
		
		$params = JComponentHelper::getParams('com_einsatzkomponente');
		if ( $params->get('article_frontend', '0') ): 
		$data = JFactory::getApplication()->input->get('jform', array(), 'array');
		$cid = $data['id'];
		
		if (!$cid) : $cid = $cid_article;endif;
		
		$article = $data['einsatzticker'];
		if ($article): 
		// Check for request forgeries
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
		// Get items to remove from the request.

		//$model = $this->getModel();
		$params = JComponentHelper::getParams('com_einsatzkomponente');
			// Make sure the item ids are integers
		
			
		$query = 'SELECT * FROM `#__eiko_einsatzberichte` WHERE `id` = "'.$cid.'" and state ="1" LIMIT 1';
		$db = JFactory::getDBO();
		$db->setQuery($query);
		$result = $db->loadObjectList();

		//$kat	= EinsatzkomponenteHelper::getTickerKat ($result[0]->tickerkat); 
		
		$db = JFactory::getDbo();
		$db->setQuery('SELECT MAX(asset_id) FROM #__content');
		$max = $db->loadResult();
		$asset_id = $max+1;

				$link = JRoute::_( JURI::root() . 'index.php?option=com_einsatzkomponente&view=einsatzbericht&id='.$result[0]->id); 
		$image_intro = str_replace('/', '\/', $result[0]->image);
		$image_intro = $db->escape($image_intro);
		if (str_replace('\/com_einsatzkomponente\/einsatzbilder\/thumbs', '', $image_intro)):
		$image_fulltext = str_replace('\/thumbs', '', $image_intro);
		endif;
		
		$user = JFactory::getUser(); 
			
		$db = JFactory::getDbo();
		$query = $db->getQuery(true); // !important, true for every new query
		
		$query->insert('#__content'); // #__table_name = databse prefix + table name
		$query->set('`id`=NULL');
		$query->set('`asset_id`="'.$asset_id.'"');
		$query->set('`title`="'.$result[0]->summary.'"');
		
		$alias = strtolower($result[0]->summary);
		$alias = str_replace(" ", "-", $alias).'_'.date("Y-m-d", strtotime($result[0]->date1));
		$query->set('`alias`="'.$alias.'"');
		
		$intro = $result[0]->desc;
		$intro = preg_replace("#(?<=.{".$params->get('article_max_intro','400')."}?\\b)(.*)#is", " ...", $intro, 1);
		$query->set('`introtext`="'.$db->escape($intro).'"');
		
		if ($params->get('article_orgas','1')) :	
					$data = array();
					foreach(explode(',',$result[0]->auswahl_orga) as $value):
						$db = JFactory::getDbo();
						$sql	= $db->getQuery(true);
						$sql
							->select('name')
							->from('`#__eiko_organisationen`')
							->where('id = "' .$value.'"');
						$db->setQuery($sql);
						$results = $db->loadObjectList();
						if(count($results)){
							$data[] = ''.$results[0]->name.''; 
						}
					endforeach;
					$auswahl_orga=  implode(',',$data); 

					$orgas 		 = str_replace(",", " +++ ", $auswahl_orga);
		$orgas       = '<br/><br/><div class=\"eiko_article_orga\">Eingesetzte Kräfte :  '.$orgas.'</div>';
		$query->set('`fulltext`="'.$db->escape($result[0]->desc).$orgas.'"');
		else:
		$query->set('`fulltext`="'.$db->escape($result[0]->desc).'"');
		endif;
		
		$query->set('`state`="1"');
		$query->set('`catid`="'.$params->get('article_category','0').'"');
		$query->set('`created`="'.date("Y-m-d H:i:s", strtotime($result[0]->date1)).'"');
		$query->set('`created_by`="'.$user->id.'"');
		$query->set('`created_by_alias`=""');
		$query->set('`modified`=""');
		$query->set('`modified_by`="'.$user->id.'"');
		$query->set('`checked_out`="0"');
		$query->set('`checked_out_time`="0000-00-00 00:00:00.000000"');
		$query->set('`publish_up`="'.date("Y-m-d H:i:s", strtotime($result[0]->date1)).'"'); 
		$query->set('`publish_down`="0000-00-00 00:00:00.000000"');
		$query->set('`images`="{\"image_intro\":\"'.$image_intro.'\",\"float_intro\":\"\",\"image_intro_alt\":\"'.$result[0]->summary.'\",\"image_intro_caption\":\"'.$result[0]->summary.'\",\"image_fulltext\":\"'.$image_fulltext.'\",\"float_fulltext\":\"\",\"image_fulltext_alt\":\"'.$result[0]->summary.'\",\"image_fulltext_caption\":\"'.$result[0]->summary.'\"}"');
		$query->set('`urls`="{\"urla\":\"'.$link.'\",\"urlatext\":\"Weitere Informationen über diesen Einsatz im Detailbericht\",\"targeta\":\"\",\"urlb\":\"'.$result[0]->presse.'\",\"urlbtext\":\"'.$result[0]->presse_label.'\",\"targetb\":\"\",\"urlc\":\"'.$result[0]->presse2.'\",\"urlctext\":\"'.$result[0]->presse2_label.'\",\"targetc\":\"\"}"');
		$query->set('`attribs`="{\"show_title\":"",\"link_titles\":"",\"show_tags\":"",\"show_intro\":"",\"info_block_position\":"",\"show_category\":"",\"link_category\":"",\"show_parent_category\":"",\"link_parent_category\":"",\"show_author\":"",\"link_author\":"",\"show_create_date\":"",\"show_modify_date\":"",\"show_publish_date\":"",\"show_item_navigation\":"",\"show_icons\":"",\"show_print_icon\":"",\"show_email_icon\":"",\"show_vote\":"",\"show_hits\":"",\"show_noauth\":"",\"urls_position\":"",\"alternative_readmore\":"",\"article_layout\":"",\"show_publishing_options\":"",\"show_article_options\":"",\"show_urls_images_backend\":"",\"show_urls_images_frontend\":""}"');
		$query->set('`version`="1"');
		$query->set('`ordering`="0"');
		$query->set('`metakey`="'.$auswahl_orga.','.$params->get('article_meta_key','feuerwehr,einsatzbericht,unfall,feuer,hilfeleistung,polizei,thw,rettungsdienst,hilfsorganisation').',einsatzkomponente"');
		$query->set('`metadesc`="'.$params->get('article_meta_desc','Einsatzbericht').'"');
		$query->set('`access`="1"');
		$query->set('`hits`="0"');
		$query->set('`metadata`="{\"robots\":\"\",\"author\":\"'.$user->username.'\",\"rights\":\"\",\"xreference\":\"\"}"');
		$query->set('`featured`="1"');
		$query->set('`language`="*"');
		$query->set('`xreference`=""');
		/* or something like this:
		$query->columns('`1`,`2`,`3`');
		$query->values('"one","two","three"');
		*/
		
		$db->setQuery($query);
	try {
	// Execute the query in Joomla 3.0.
		$db->execute();
	} catch (Exception $e) {
	//print the errors
	print_r ($e).'';
	}
		$content_id = $db->insertId();
		// Joomla-Artikel Id in Einsatzbericht eintragen 
		$query = "UPDATE `#__eiko_einsatzberichte` SET `article_id` = '".$content_id."' WHERE `id` = '".$result[0]->id."'";
		$db = JFactory::getDBO();
		$db->setQuery($query);
		$db->query();
		
		if ($params->get('article_frontpage','1')) :	
		// Artikel als Haupteintrag-Eintrag markieren 
		$frontpage_query = "INSERT INTO `#__content_frontpage` SET `content_id`='".$content_id."'";
		$db = JFactory::getDBO();
		$db->setQuery($frontpage_query);
		$db->query();
		endif;
		
		endif;
		endif;
		}
		
		
		
		
	// Mail (Auto) Funktion
	if ($return) :
		$send = 'false';
		$data = JFactory::getApplication()->input->get('jform', array(), 'array');
		$cid = $data['id'];
		$params = JComponentHelper::getParams('com_einsatzkomponente');
		
		if ( $params->get('send_mail_auto', '0') ): 
		//if (!$cid) :
		//$db = JFactory::getDBO();
		//$query = "SELECT id FROM #__eiko_einsatzberichte ORDER BY id DESC LIMIT 1";
		//$db->setQuery($query);
		//$rows = $db->loadObjectList();
		//$cid      = $rows[0]->id;
		//$send = sendMail_auto($cid,'neuer Bericht: ');
		//else:
		$send = sendMail_auto($cid,'Update: ');
		//endif;
		endif;
	endif;
	if (!$return) :
		$send = 'false';
		$params = JComponentHelper::getParams('com_einsatzkomponente');
		
		if ( $params->get('send_mail_auto', '0') ): 
		//if (!$cid) :
		$db = JFactory::getDBO();
		$query = "SELECT id FROM #__eiko_einsatzberichte ORDER BY id DESC LIMIT 1";
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		$cid      = $rows[0]->id;
		$send = sendMail_auto($cid,'neuer Bericht: ');
		//else:
		//$send = sendMail_auto($cid,'Update: ');
		//endif;
		endif;
	endif;
	// ---------------------------

		
        // Clear the profile id from the session.
        $app->setUserState('com_einsatzkomponente.edit.einsatzbericht.id', null);
        // Redirect to the list screen.
        $this->setMessage(JText::_('Einsatzdaten erfolgreich gepeichert'));
        $menu = & JSite::getMenu();
        $item = $menu->getActive(); //print_r ($item);break;
//echo 'View :'.JFactory::getApplication()->input->get('view').'<br/>';
//echo 'Layout :'.JFactory::getApplication()->input->get('layout').'<br/>';
//echo 'Task :'.JFactory::getApplication()->input->get('task').'<br/>';break;

        //$this->setRedirect(JRoute::_($item->link, false));
		$this->setRedirect(JRoute::_('index.php?option=com_einsatzkomponente&view=einsatzberichte&Itemid='.$params->get('homelink', '').'', false));
		// Flush the data from the session.
		$app->setUserState('com_einsatzkomponente.edit.einsatzbericht.data', null);
	}
    
    
    public function cancel() {
		$menu = & JSite::getMenu();
        $item = $menu->getActive();
		$params = JComponentHelper::getParams('com_einsatzkomponente');
        $this->setMessage(JText::_('Einsatzeingabe abgebrochen'));
		$this->setRedirect(JRoute::_('index.php?option=com_einsatzkomponente&view=einsatzberichte&Itemid='.$params->get('homelink', '').'', false)); 
    }
    
	public function remove()
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		// Initialise variables.
		$app	= JFactory::getApplication();
		$model = $this->getModel('Einsatzbericht', 'EinsatzkomponenteModel');
		// Get the user data.
		$data = JFactory::getApplication()->input->get('jform', array(), 'array');
		// Validate the posted data.
		$form = $model->getForm();
		if (!$form) {
			JError::raiseError(500, $model->getError());
			return false;
		}
		// Validate the posted data.
		$data = $model->validate($form, $data);
		// Check for errors.
		if ($data === false) {
			// Get the validation messages.
			$errors	= $model->getErrors();
			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
				if ($errors[$i] instanceof Exception) {
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				} else {
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}
			// Save the data in the session.
			$app->setUserState('com_einsatzkomponente.edit.einsatzbericht.data', $data);
			// Redirect back to the edit screen.
			$id = (int) $app->getUserState('com_einsatzkomponente.edit.einsatzbericht.id');
			$this->setRedirect(JRoute::_('index.php?option=com_einsatzkomponente&view=einsatzbericht&layout=edit&id='.$id, false));
			return false;
		}
		// Attempt to save the data.
		$return	= $model->delete($data);
		// Check for errors.
		if ($return === false) {
			// Save the data in the session.
			$app->setUserState('com_einsatzkomponente.edit.einsatzbericht.data', $data);
			// Redirect back to the edit screen.
			$id = (int)$app->getUserState('com_einsatzkomponente.edit.einsatzbericht.id');
			$this->setMessage(JText::sprintf('Delete failed', $model->getError()), 'warning');
			$this->setRedirect(JRoute::_('index.php?option=com_einsatzkomponente&view=einsatzbericht&layout=edit&id='.$id, false));
			return false;
		}
            
        // Check in the profile.
        if ($return) {
            $model->checkin($return);
        }
        
        // Clear the profile id from the session.
        $app->setUserState('com_einsatzkomponente.edit.einsatzbericht.id', null);
        // Redirect to the list screen.
        $this->setMessage(JText::_('Item deleted successfully'));
        $menu = & JSite::getMenu();
        $item = $menu->getActive();
        $this->setRedirect(JRoute::_($item->link, false));
		// Flush the data from the session.
		$app->setUserState('com_einsatzkomponente.edit.einsatzbericht.data', null);
	}
	
    
}


	    function sendMail_auto($cid,$status) {

		

		//$model = $this->getModel();
		$params = JComponentHelper::getParams('com_einsatzkomponente');
		$user = JFactory::getUser();
		$query = 'SELECT * FROM `#__eiko_einsatzberichte` WHERE `id` = "'.$cid.'" LIMIT 1';
		$db = JFactory::getDBO();
		$db->setQuery($query);
		$result = $db->loadObjectList();
		$mailer = JFactory::getMailer();
		$config = JFactory::getConfig();
		
		//$sender = array( 
    	//$config->get( 'config.mailfrom' ),
    	//$config->get( 'config.fromname' ) );
		$sender = array( 
    	$user->email,
    	$user->name );
		
		$mailer->setSender($sender);
		
		$user = JFactory::getUser();
		//$recipient = $user->email;
		$recipient = $params->get('mail_empfaenger_auto',$user->email);
		
		$recipient 	 = explode( ',', $recipient);
		$orga		 = explode( ',', $result[0]->auswahl_orga);
		$orgas 		 = str_replace(",", " +++ ", $result[0]->auswahl_orga);
 
		$mailer->addRecipient($recipient);
		
		$mailer->setSubject($status.''.$orga[0].'  +++ '.$result[0]->summary.' +++');
		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
					$query
						->select('*')
						->from('`#__eiko_tickerkat`')
						->where('id = "' .$result[0]->tickerkat.'"  AND state = "1" ');
					$db->setQuery($query);
					$kat = $db->loadObject();
		
		$link = JRoute::_( JURI::root() . 'index.php?option=com_einsatzkomponente&view=einsatzbericht&id='.$result[0]->id.'&Itemid='.$params->get('homelink','')); 
		
		$body   = ''
				. '<h2>+++ '.$result[0]->summary.' +++</h2>';
		if ($params->get('send_mail_kat','0')) :	
		$body   .= '<h4>'.JText::_($kat->title).'</h4>';
		endif;
		if ($params->get('send_mail_orga','0')) :	
		$body   .= '<span><b>Eingesetzte Kräfte:</b> '.$orgas.'</span>';
		endif;
		$body   .= '<div>';
		if ($params->get('send_mail_desc','0')) :	
		if ($result[0]->desc) :	
    	$body   .= '<p>'.$result[0]->desc.'</p>';
		else:
    	$body   .= '<p>Ein ausführlicher Bericht ist zur Zeit noch nicht vorhanden.</p>';
		endif;
		endif;
		if ($params->get('send_mail_link','0')) :	
    	$body   .= '<p><a href="'.$link.'" target="_blank">Link zur Homepage</a></p>';
		endif;
		if ($result[0]->image) :	
		if ($params->get('send_mail_image','0')) :	
		$body   .= '<img src="'.JURI::root().$result[0]->image.'" style="margin-left:10px;float:right;height:50%;" alt="Einsatzbild"/>';
		endif;
		endif;
		$body   .= '</div>';
		

		$mailer->isHTML(true);
		$mailer->Encoding = 'base64';
		$mailer->setBody($body);
		// Optionally add embedded image
		//$mailer->AddEmbeddedImage( JPATH_COMPONENT.'/assets/logo128.jpg', 'logo_id', 'logo.jpg', 'base64', 'image/jpeg' );
		
		$send = $mailer->Send();	

        return $send; 
    }
	
	

				

