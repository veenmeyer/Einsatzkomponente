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
 * View class for a list of Einsatzkomponente.
 */
class EinsatzkomponenteViewEinsatzberichte extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
	protected $params;
	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->state		= $this->get('State');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->params = JComponentHelper::getParams('com_einsatzkomponente');
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			throw new Exception(implode("\n", $errors));
		}
        
		
		EinsatzkomponenteHelper::addSubmenu('einsatzberichte');
        
		$this->addToolbar();
		
		// Weiterleitungsfunktionen -----------------------------------------------------
		if ($this->params->get('info112','0')) : 
		$tickerID2 = JFactory::getApplication()->input->get('tickerID2');
		if ($tickerID2):
		$this->send($tickerID2,'http://info112.net/gateway/gateway.php',$this->params->get('info112_user',''),$this->params->get('info112_api',''),$this->params->get('info112_debug','1'));
		endif;
		endif;
		// Weiterleitungsfunktionen ENDE ------------------------------------------------
        
        $this->sidebar = JHtmlSidebar::render();

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
		JToolBarHelper::title(JText::_('COM_EINSATZKOMPONENTE_TITLE_EINSATZBERICHTE'), 'einsatzberichte.png');
        //Check if the form exists before showing the add/edit buttons
        $formPath = JPATH_COMPONENT_ADMINISTRATOR.'/views/einsatzbericht';
        if (file_exists($formPath)) {
            if ($canDo->get('core.create')) {
			    JToolBarHelper::addNew('einsatzbericht.add','JTOOLBAR_NEW');
		    }
		    if ($canDo->get('core.edit') && isset($this->items[0])) {
			    JToolBarHelper::editList('einsatzbericht.edit','JTOOLBAR_EDIT');
		    }
        }
		if ($canDo->get('core.edit.state')) {
            if (isset($this->items[0]->state)) {
			    JToolBarHelper::divider();
			    JToolBarHelper::custom('einsatzberichte.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
			    JToolBarHelper::custom('einsatzberichte.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
            } else if (isset($this->items[0])) {
                //If this component does not use state then show a direct delete button as we can not trash
                JToolBarHelper::deleteList('','einsatzberichte.delete','JTOOLBAR_DELETE');
            }
            if (isset($this->items[0]->checked_out)) {
            	JToolBarHelper::custom('einsatzberichte.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
            }
		}
        
        //Show trash and delete for components that uses the state field
        if (isset($this->items[0]->state)) {
		    if ($state->get('filter.state') == -2 && $canDo->get('core.delete')) {
			    JToolBarHelper::deleteList('','einsatzberichte.delete','JTOOLBAR_EMPTY_TRASH');
			    JToolBarHelper::divider();
		    } else if ($canDo->get('core.edit.state')) {
			    //JToolBarHelper::trash('einsatzberichte.trash','JTOOLBAR_TRASH');
                JToolBarHelper::deleteList('','einsatzberichte.delete','JTOOLBAR_DELETE');
			    JToolBarHelper::divider();
		    }
        }
		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_einsatzkomponente');
				if ($this->params->get('send_mail_backend','0')) : 
				JToolBarHelper::custom( 'einsatzberichte.sendMail', 'edit','edit', 'Als Mail versenden',  true );
				endif;
		}
		
		$version = new JVersion;
		if ($version->isCompatible('3.0')) :
		if ($canDo->get('core.create')) {
				JToolBarHelper::custom( 'einsatzberichte.article', 'edit','edit', 'Als Artikel erstellen',  true );
		}
        endif;
		
            if ($canDo->get('core.create')) :
            if (isset($this->items[0]->state)) {
			    JToolBarHelper::divider();
			    JToolBarHelper::archiveList('einsatzberichte.archive','Als Folgeeinsatz markieren');
            }
			endif;


    	// Filter nach Datum  für Joomla 3
		$version = new JVersion;
        if ($version->isCompatible('3.0')) :
		JHtmlSidebar::setAction('index.php?option=com_einsatzkomponente&view=einsatzberichte');
        $this->extra_sidebar = '';
			$this->extra_sidebar .= '<small><label for="filter_from_date1">Anzeigen ab Datum</label></small>';
			$this->extra_sidebar .= EinsatzkomponenteHelper::kalender($this->state->get('filter.date1.from'), 'filter_date1_from', 'filter_from_date1', '%Y-%m-%d', ' style="width:142px;" onchange="this.form.submit();"');
			$this->extra_sidebar .= '<small><label for="filter_to_date1">bis Datum</label></small>';
			$this->extra_sidebar .= EinsatzkomponenteHelper::kalender($this->state->get('filter.date1.to'), 'filter_date1_to', 'filter_to_date1', '%Y-%m-%d', ' style="width:142px;" onchange="this.form.submit();"');
			$this->extra_sidebar .= '<hr class="hr-condensed">';  
        endif;

    	// Filter nach Datum  für Joomla 2.5
		$version = new JVersion;
        if (!$version->isCompatible('3.0')) :
        $this->extra_sidebar = '';
			$this->extra_sidebar .= '<small><label for="filter_from_date1">Anzeigen ab Datum</label></small>';
			$this->extra_sidebar .= JHtml::_('calendar',$this->state->get('filter.date1.from'), 'filter_date1_from', 'filter_from_date1', '%Y-%m-%d', ' style="width:100px;" onchange="this.form.submit();"');
			$this->extra_sidebar .= '<small><label for="filter_to_date1">bis Datum</label></small>';
			$this->extra_sidebar .= JHtml::_('calendar',$this->state->get('filter.date1.to'), 'filter_date1_to', 'filter_to_date1', '%Y-%m-%d', ' style="width:100px;" onchange="this.form.submit();"');
			$this->extra_sidebar .= '<hr class="hr-condensed">';  
        endif;
			
        //Filter for the field ".auswahlorga;
        jimport('joomla.form.form');
        JForm::addFormPath(JPATH_COMPONENT . '/models/forms');
        $form = JForm::getInstance('com_einsatzkomponente.einsatzbericht', 'einsatzbericht');
        $field = $form->getField('filter_auswahlorga');
        $query = $form->getFieldAttribute('filter_auswahlorga','query');
        $translate = $form->getFieldAttribute('filter_auswahlorga','translate');
        $key = $form->getFieldAttribute('filter_auswahlorga','key_field');
        $value = $form->getFieldAttribute('filter_auswahlorga','value_field');
        // Get the database object.
        $db = JFactory::getDBO();
        // Set the query and get the result list.
        $db->setQuery($query);
        $items = $db->loadObjectlist();
        // Build the field options.
        if (!empty($items))
        {
            foreach ($items as $item)
            {
                if ($translate == true)
                {
                    $options[] = JHtml::_('select.option', $item->$key, JText::_($item->$value));
                }
                else
                {
                    $options[] = JHtml::_('select.option', $item->$key, $item->$value);
                }
            }
        }
		
        JHtmlSidebar::addFilter(
            'Organisationen',
            'filter_auswahlorga',
            JHtml::_('select.options', $options, "value", "text", $this->state->get('filter.auswahlorga')),
            true
        );
		$options = '';
		$options[] = JHtml::_('select.option', '1', 'JPUBLISHED');
		$options[] = JHtml::_('select.option', '0', 'JUNPUBLISHED');
		$options[] = JHtml::_('select.option', '2', 'Folgeeinsatz');
		$options[] = JHtml::_('select.option', '*', 'JALL');
		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_PUBLISHED'),
			'filter_published',
			JHtml::_('select.options', $options, "value", "text", $this->state->get('filter.state'), true)
		);
		
		
        //Filter for the field tickerkat;
        jimport('joomla.form.form');
        $options = array();
        JForm::addFormPath(JPATH_COMPONENT . '/models/forms');
        $form = JForm::getInstance('com_einsatzkomponente.einsatzbericht', 'einsatzbericht');

        $field = $form->getField('tickerkat');

        $query = $form->getFieldAttribute('filter_tickerkat','query');
        $translate = $form->getFieldAttribute('filter_tickerkat','translate');
        $key = $form->getFieldAttribute('filter_tickerkat','key_field');
        $value = $form->getFieldAttribute('filter_tickerkat','value_field');

        // Get the database object.
        $db = JFactory::getDBO();

        // Set the query and get the result list.
        $db->setQuery($query);
        $items = $db->loadObjectlist();

        // Build the field options.
        if (!empty($items))
        {
            foreach ($items as $item)
            {
                if ($translate == true)
                {
                    $options[] = JHtml::_('select.option', $item->$key, JText::_($item->$value));
                }
                else
                {
                    $options[] = JHtml::_('select.option', $item->$key, $item->$value);
                }
            }
        }

        JHtmlSidebar::addFilter(
            '$tickerkat',
            'filter_tickerkat',
            JHtml::_('select.options', $options, "value", "text", $this->state->get('filter.tickerkat')),
            true
        );                                                
			
		
		
		
		//Filter for the field created_by
		$this->extra_sidebar .= '<small><label for="filter_created_by">Created by</label></small>';
		$this->extra_sidebar .= JHtmlList::users('filter_created_by', $this->state->get('filter.created_by'), 1, 'onchange="this.form.submit();"');
        
	}
    
	protected function getSortFields()
	{
		return array(
		'a.id' => JText::_('JGRID_HEADING_ID'),
		'a.ordering' => JText::_('JGRID_HEADING_ORDERING'),
		'a.data1' => JText::_('COM_EINSATZKOMPONENTE_EINSATZBERICHTE_DATA1'),
		'a.tickerkat' => JText::_('COM_EINSATZKOMPONENTE_EINSATZBERICHTE_KATEGORIE'),
		'a.image' => JText::_('COM_EINSATZKOMPONENTE_EINSATZBERICHTE_IMAGE'),
		'a.address' => JText::_('COM_EINSATZKOMPONENTE_EINSATZBERICHTE_ADDRESS'),
		'a.date1' => JText::_('COM_EINSATZKOMPONENTE_EINSATZBERICHTE_DATE1'),
		'a.summary' => JText::_('COM_EINSATZKOMPONENTE_EINSATZBERICHTE_SUMMARY'),
		'a.department' => JText::_('COM_EINSATZKOMPONENTE_EINSATZBERICHTE_DEPARTMENT'),
		'a.alerting' => JText::_('COM_EINSATZKOMPONENTE_EINSATZBERICHTE_ALERTING'),
		'a.counter' => JText::_('COM_EINSATZKOMPONENTE_EINSATZBERICHTE_COUNTER'),
		'a.gmap' => JText::_('COM_EINSATZKOMPONENTE_EINSATZBERICHTE_GMAP'),
		'a.updatedate' => JText::_('COM_EINSATZKOMPONENTE_EINSATZBERICHTE_UPDATEDATE'),
		'a.einsatzticker' => JText::_('COM_EINSATZKOMPONENTE_EINSATZBERICHTE_EINSATZTICKER'),
		'a.notrufticker' => JText::_('COM_EINSATZKOMPONENTE_EINSATZBERICHTE_NOTRUFTICKER'),
		'a.auswahlorga' => JText::_('COM_EINSATZKOMPONENTE_EINSATZBERICHTE_AUSWAHLORGA'),
		'a.state' => JText::_('JSTATUS'),
		'a.created_by' => JText::_('COM_EINSATZKOMPONENTE_EINSATZBERICHTE_CREATED_BY'),
		);
	}
	
	protected function send($Send_id,$url,$username,$api,$debug)
	{
			//------------------ info112.net Ticker ---------------------------
			$jinput = JFactory::getApplication()->input; 
        	$Send_Data = $this->get('Send_Data');
            $zeit = strtotime($Send_Data->date1); 
			$kat = $Send_Data->tickerkat;
			$msg = $Send_Data->summary;
			$msg = strip_tags($msg); // HTML-Tags aus Kurzbericht entfernen !!
			$msg = htmlentities($msg,ENT_COMPAT,'UTF-8');
			$msg = html_entity_decode($msg,ENT_COMPAT,'ISO-8859-1');			
			$desc = $Send_Data->desc;
			$desc = strip_tags($desc); // HTML-Tags aus Bericht entfernen !!
			$desc = htmlentities($desc,ENT_COMPAT,'UTF-8');
			$desc = html_entity_decode($desc,ENT_COMPAT,'ISO-8859-1');			
			$ort = $Send_Data->address;
			$ort = strip_tags($ort); // HTML-Tags aus Adresse entfernen !!
			$ort = htmlentities($ort,ENT_COMPAT,'UTF-8'); 
			$ort = html_entity_decode($ort,ENT_COMPAT,'ISO-8859-1');			
			$bild = '';
			if ($Send_Data->image)
			{
            $bild = JURI::root().$Send_Data->image;
			}
			



			$param["user"] = $username; // Dein Einsatzticker.de-Benutzername
			$param["apikey"] = $api; // API-Key
			$param["zeit"] = $zeit; // Einsatzzeit als Zeitstempel
			$param["kat"] =  $kat; // Kategorie Brände>Fahrzeuge
			$param["msg"] =  $msg; // Einsatzmeldung
			$param["ort"] =  $ort; // Einsatzort
			$param["desc"] =  $desc; // Einsatzbericht
			$param["debug"] = $debug; // Testmodus - Der Einsatz wird nicht gespeichert
			$param["lon"] = $Send_Data->gmap_report_longitude; // Gmap-Longitude
			$param["lat"] =  $Send_Data->gmap_report_latitude; // GMap-Latidude
			$param["bild"] =  $bild; // Bild-Url
			foreach($param as $key=>$val) // Alle Parameter durchlaufen
			{
			  $request.= $key."=".urlencode($val); // Werte müssen url-encoded sein
			  $request.= "&"; // Trennung der Parameter mit &
			}
			//echo $url."/gateway.php?".$request.'<br/><br/>';
			// Der Einsatz kann jetzt gemeldet werden
			$response = @file($url."?".$request); // Request absetzen
			//print_r ($response);break;
break;//$response_code = intval($response[0]) ?: intval($response[1])	;		
			//echo $response_code;
						
			//Array mit Rückgabe-Codes
			$code_arrayay[0] = "Keine Verbindung zur Schnittstelle";
			$code_array[10] = "Anmeldung -> Benutzername fehlt";
			$code_array[11] = "Anmeldung -> Apikey fehlt";
			$code_array[12] = "Anmeldung -> Zugangsdaten falsch";
			$code_array[13] = "Anmeldung -> Benutzername falsch";
			$code_array[14] = "Anmeldung -> Apikey falsch";
			$code_array[20] = "Zeitstempel fehlt";
			$code_array[21] = "Zeitstempel falsch -> Einsatzzeit liegt in der Zukunft";
			$code_array[22] = "Zeitstempel falsch -> Einsatz ist &auml;lter als 3 Tage";
			$code_array[30] = "Kategoriefehler -> ID fehlt";
			$code_array[31] = "Kategoriefehler -> ID ung&uuml;ltig";
			$code_array[40] = "Meldungsfehler -> Kein Meldungstext";
			$code_array[41] = "Meldungsfehler -> Meldung zu lang";
			$code_array[42] = "Meldungsfehler -> Meldung enth&auml;lt ung&uuml;ltige Zeichen";
			$code_array[50] = "Einsatzmeldung bereits vorhanden";
			$code_array[55] = "Einsatzort fehlt";
			$code_array[60] = "Kurzbericht fehlt";
			$code_array[65] = "Image Upload-Fehler";
			$code_array[99] = "Testmeldung erfolgreich";
			$code_array[100] = "Einsatzmeldung erfolgreich";
				
			?>
			<?php echo '<span class="label label-important"> '.$code_array[$response_code].'</span>';?>
			<?php
			
			
			if (JFactory::getApplication()->input->get('tickerID2')):
			if ($response_code == '100') 
			{
			$query = 'UPDATE `#__eiko_einsatzberichte` SET `notrufticker` = "2" WHERE `#__eiko_einsatzberichte`.`id` = '. $Send_id .' LIMIT 1;'; 
			$db = JFactory::getDBO();
			$db->setQuery($query);
			if ($db->query()) {} else {
			echo 'Fehler beim Update der DB : ' . $db->getErrorMsg();break;
			}
			?>
			<meta http-equiv="refresh" content="0; URL=index.php?option=com_einsatzkomponente&view=einsatzberichte">
			<?php
			}
			if ($response_code == '22' or $response_code == '50')
			{
			$query = 'UPDATE `#__eiko_einsatzberichte` SET `notrufticker` = "0" WHERE `#__eiko_einsatzberichte`.`id` = '. $Send_id .' LIMIT 1;'; 
			$db = JFactory::getDBO();
			$db->setQuery($query);
			if ($db->query()) {} else {
			echo 'Fehler beim Update der DB : ' . $db->getErrorMsg();break;
			}
			?>
			<meta http-equiv="refresh" content="0; URL=index.php?option=com_einsatzkomponente&view=einsatzberichte">
			<?php
			}
			endif;
			
	}
	
    
}
