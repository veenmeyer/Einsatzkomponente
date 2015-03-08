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
 
class EinsatzkomponenteViewEinsatzkarte extends JViewLegacy
{
	
	
	
	protected $items;
	protected $pagination;
	protected $state;
    protected $params;
    protected $reports;
    protected $years;
    protected $selectedYear;
    protected $version;
    protected $einsatzarten;
    protected $selectedEinsatzart;
	protected $selectedOrga;
    protected $layout_detail_link;
    protected $gmap_config;
    protected $monate;
	protected $einsatzgebiet;
	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		require_once JPATH_SITE.'/administrator/components/com_einsatzkomponente/helpers/einsatzkomponente.php'; // Helper-class laden
		$app                = JFactory::getApplication();
        $this->state		= $this->get('State');
        $this->items		= $this->get('Items');
        $this->pagination	= $this->get('Pagination');
        $this->params       = $app->getParams('com_einsatzkomponente');
		$this->gmap_config = EinsatzkomponenteHelper::load_gmap_config(); // GMap-Config aus helper laden 
		//print_r ($this->params);break;
		
		
		require_once JPATH_SITE.'/administrator/components/com_einsatzkomponente/helpers/einsatzkomponente.php'; // Helper-class laden
		
		$aktuelles_Datum = getdate(); 
		$selectedYear = $aktuelles_Datum["year"];
		//if (!$app->input->get(year)) :
		//if ($this->params->get('anzeigejahr')) : $selectedYear = $this->params->get('anzeigejahr'); endif;
		//endif;
		//if ($app->input->get(year)) : $selectedYear = $app->input->get(year); endif;
		//if ($app->input->get(year)) :
		
		$selectedYear = $app->getUserStateFromRequest( "com_einsatzkomponente.selectedYear", 'year', $selectedYear );
		$selectedEinsatzart = '';
		$selectedEinsatzart = $app->getUserStateFromRequest( "com_einsatzkomponente.selectedEinsatzart", 'selectedEinsatzart', 'alle Einsatzarten' );
		$selectedOrga = '';
		$selectedOrga = $app->getUserStateFromRequest( "com_einsatzkomponente.selectedOrga", 'selectedOrga', 'alle Organisationen' );
		//print_r ($selectedEinsatzart);
		//echo $selectedOrga;
		//endif;
		if ($this->params->get('anzeigejahr')) : $selectedYear = $this->params->get('anzeigejahr'); endif;
		$this->selectedYear  = $selectedYear;	
		$this->selectedEinsatzart  = $selectedEinsatzart;	
		$app->setUserState( "com_einsatzkomponente.selectedYear", $selectedYear );
		$app->setUserState( "com_einsatzkomponente.selectedEinsatzart", $selectedEinsatzart );
		
		if ($this->params->get('abfragewehr','1')) :
		if ($this->params->get('anzeigewehr')) : $selectedOrga = $this->params->get('anzeigewehr'); endif;
		$this->selectedOrga  = $selectedOrga;	
		$this->selectedOrga  = $app->setUserState( "com_einsatzkomponente.selectedOrga", $selectedOrga );
		else:
		$selectedOrga = $app->getUserStateFromRequest( "com_einsatzkomponente.selectedOrga", 'selectedOrga', 'alle Organisationen' );
		$this->selectedOrga  = $selectedOrga;	
		endif;
		
		//Komponentenversion aus Datenbank lesen
		$this->version 		= EinsatzkomponenteHelper::getVersion (); 

		//Limitstart und Limit für Pagination
//        if ($this->params->get('display_home_pagination')) :
//		$limitstart = $this->pagination->limitstart;
//		$limitstart = $app->getUserStateFromRequest( "com_einsatzkomponente.limitstart", 'limitstart', $limitstart );
//		$limit = $this->pagination->limit;
//		else:
//		$limitstart = '0';
//		$limit = '10000';
//		endif;
		
		//Einsatzdaten aus der Datenbank holen
		$count = EinsatzkomponenteHelper::count_einsatz_daten_bestimmtes_jahr (''); 
//		$this->pagination->total = count($count);
//		$this->pagination->pagesTotal = ceil(count($count)/$limit);
//		$this->pagination->pagesStop = ceil(count($count)/$limit);
		$this->reports = EinsatzkomponenteHelper::einsatz_daten_bestimmtes_jahr ('','99999','0'); 

		if (!$this->reports) : 
		$count = EinsatzkomponenteHelper::count_einsatz_daten_bestimmtes_jahr ($aktuelles_Datum["year"]-1); 
//		$this->pagination->total = count($count);
//		$this->pagination->pagesTotal = ceil(count($count)/$limit);
//		$this->pagination->pagesStop = ceil(count($count)/$limit);
		$this->reports = EinsatzkomponenteHelper::einsatz_daten_bestimmtes_jahr ($aktuelles_Datum["year"]-1,'99999','0'); 
				// Falls Jahr ohne Einsatz per Menülink aufgerufen wird, dann kein anderes Jahr anzeigen
				if ($this->params->get('anzeigejahr') == $selectedYear) : 
				$this->reports='';
				endif;
		endif;
		
		//print_r ($this->reports);break;
		
		

		$this->years 				= EinsatzkomponenteHelper::getYear (); // Alle Jahre der Einsatzdaten ermitteln
		$this->einsatzarten 		= EinsatzkomponenteHelper::getEinsatzarten (); // Alle Einsatzarten der Einsatzdaten ermitteln
		$this->organisationen 		= EinsatzkomponenteHelper::getOrganisationen (); // Alle Einsatzarten der Einsatzdaten ermitteln
		//print_r ($this->einsatzarten);
		

		$layout_detail = $this->params->get('layout_detail',''); // Detailbericht Layout
		$this->layout_detail_link = ''; 
		if ($layout_detail) : $this->layout_detail_link = '&layout='.$layout_detail;  endif; // Detailbericht Layout 'default' ?
		
		$document = JFactory::getDocument();
		
        // Import CSS
		$document->addStyleSheet('components/com_einsatzkomponente/assets/css/einsatzkomponente.css');
		$document->addStyleSheet('components/com_einsatzkomponente/assets/css/responsive.css');
		if ($this->params->get('display_einsatzkarte_bootstrap','0')) :
 		$document->addScript('components/com_einsatzkomponente/assets/bootstrap/js/bootstrap.min.js');	
 		$document->addStyleSheet('components/com_einsatzkomponente/assets/bootstrap/css/bootstrap.min.css');
 		$document->addStyleSheet('components/com_einsatzkomponente/assets/bootstrap/css/bootstrap-responsive.min.css');
		endif;
		$document->addStyleDeclaration($this->params->get('gmap_css','')); 
		
		// Import Jquery
		JHtml::_('jquery.framework',true);
		JHtml::_('jquery.ui');
		//$document->addScript('components/com_einsatzkomponente/assets/jquery/jquery1.9.1.js');
		
		// prüfen ob jquery geladen wurde
		echo "<script type=\"text/javascript\">
		if(typeof jQuery == \"function\")
		var x='';
		else
		  alert(\"jQuery nicht geladen\");
		</script>";
		
		//$document->addStyleSheet('components/com_einsatzkomponente/assets/jquery/JQRangeSlider/iThing.css');
 		//$document->addScript('components/com_einsatzkomponente/assets/jquery/JQRangeSlider/jQDateRangeSlider-withRuler-min.js');	
		
		

		
        if ($this->params->get('display_home_rss','1')) : 
		// RSS-Feed in den Dokumenten-Header einfügen
		$href = 'index.php?option=com_einsatzkomponente&view=einsatzberichte&format=feed&type=rss'; 
		$attribs = array('type' => 'application/rss+xml', 'title' => 'RSS 2.0'); 
		$document->addHeadLink( $href, 'alternate', 'rel', $attribs );
		endif;


$this->monate = array(1=>"Januar",
                2=>"Februar",
                3=>"M&auml;rz",
                4=>"April",
                5=>"Mai",
                6=>"Juni",
                7=>"Juli",
                8=>"August",
                9=>"September",
                10=>"Oktober",
                11=>"November",
                12=>"Dezember");
				
		
		 // Check for errors.
        if (count($errors = $this->get('Errors'))) {;
            throw new Exception(implode("\n", $errors));
        }
        
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
