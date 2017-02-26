<?php

/**
 * @version     3.15.0
 * @package     com_einsatzkomponente
 * @copyright   Copyright (C) 2017 by Ralf Meyer. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ralf Meyer <ralf.meyer@mail.de> - https://einsatzkomponente.de
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of Einsatzkomponente.
 */
class EinsatzkomponenteViewEinsatzarchiv extends JViewLegacy {

    protected $items;
    protected $pagination;
    protected $state;
    protected $params;
    protected $version;
    protected $monate;
	protected $modulepos_1;
	protected $modulepos_2;
	protected $gmap_config;

    /**
     * Display the view
     */
    public function display($tpl = null) {
		require_once JPATH_SITE.'/administrator/components/com_einsatzkomponente/helpers/einsatzkomponente.php'; // Helper-class laden
        $app = JFactory::getApplication();

        $this->state = $this->get('State');
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->params = $app->getParams('com_einsatzkomponente');
        $this->filterForm = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');
		$this->gmap_config = EinsatzkomponenteHelper::load_gmap_config(); // GMap-Config aus helper laden 

		

		
		$document = JFactory::getDocument();
        // Import CSS
		$document->addStyleSheet('components/com_einsatzkomponente/assets/css/einsatzkomponente.css');
		$document->addStyleSheet('components/com_einsatzkomponente/assets/css/responsive.css');

		// Bootstrap laden
		JHtml::_('behavior.framework', true);
		
		if ($this->params->get('display_home_bootstrap','0') == '1') :
		JHtml::_('bootstrap.framework');
		$document->addStyleSheet($this->baseurl . '/media/jui/css/bootstrap.min.css');
		$document->addStyleSheet($this->baseurl.'/media/jui/css/icomoon.css');
		endif;
		if ($this->params->get('display_home_bootstrap','0') == '2') :
		$document->addStyleSheet('components/com_einsatzkomponente/assets/css/bootstrap/bootstrap.min.css');
		$document->addStyleSheet('components/com_einsatzkomponente/assets/css/bootstrap/bootstrap-responsive.min.css');
		endif;
		
		// Import CSS aus Optionen
		$document->addStyleDeclaration($this->params->get('main_css','')); 
		
		
		if ($this->params->get('gmap_action','0') == '1') :
			
			$standort = new StdClass;
			$standort->gmap_latitude = '0';
			$standort->gmap_longitude= '0';
			$orga = EinsatzkomponenteHelper::getOrganisationen(); 
		if ($this->params->get('display_detail_organisationen','1')) :
			$orga = EinsatzkomponenteHelper::getOrganisationen(); 
	  		$organisationen='['; // Feuerwehr Details  ------>
	  		$n=0;
	  		for($i = 0; $i < count($orga); $i++) {
			$orga_image 	= $orga[$i]->gmap_icon_orga;
			if (!$orga_image) : $orga_image= 'images/com_einsatzkomponente/images/map/icons/'.$this->params->get('einsatzkarte_orga_image','haus_rot.png'); endif;
		  	if($i==$n-1){
			$organisationen=$organisationen.'["'.$orga[$i]->name.'",'.$orga[$i]->gmap_latitude.','.$orga[$i]->gmap_longitude.','.$i.',"'.$orga_image.'"]';
		 	}else {
			$organisationen=$organisationen.'["'.$orga[$i]->name.'",'.$orga[$i]->gmap_latitude.','.$orga[$i]->gmap_longitude.','.$i.',"'.$orga_image.'"';
			$organisationen=$organisationen.'],';
		    }
	        }
	  		$organisationen=substr($organisationen,0,strlen($organisationen)-1);
	  		$organisationen=$organisationen.' ];';
		else:
			$organisationen	 = '[["",1,1,0,"images/com_einsatzkomponente/images/map/icons/'.$this->params->get('einsatzkarte_orga_image','haus_rot.png').'"],["",1,1,0,"images/com_einsatzkomponente/images/map/icons/'.$this->params->get('einsatzkarte_orga_image','haus_rot.png').'"] ]';	
			endif;
		 $display_map_route		= 'false';	

	  	 $alarmareas1  = $this->gmap_config->gmap_alarmarea;  // Einsatzgebiet  ---->
	 	 $alarmareas = explode('|', $alarmareas1);
	     $einsatzgebiet='[ ';
		  for($i = 0; $i < count($alarmareas)-1; $i++)
		  {
			  	  $areas = explode(',', $alarmareas[$i]);
				  $einsatzgebiet=$einsatzgebiet.'new google.maps.LatLng('.$areas[0].','.$areas[1].'),';
		  }
		$areas = explode(',', $alarmareas[0]);
		$einsatzgebiet=$einsatzgebiet.'new google.maps.LatLng('.$areas[0].','.$areas[1].'),';
	    $einsatzgebiet=substr($einsatzgebiet,0,strlen($einsatzgebiet)-1);
	    $einsatzgebiet=$einsatzgebiet.' ]';	
		if (!$this->params->get('display_home_einsatzgebiet','1')) :
		$einsatzgebiet='[[0,0]]';
		endif;
			
        $display_detail_popup = 'false';
		$marker1_title 		= ''; // leer
		$marker1_lat  		= '1'; // leer
		$marker1_lng 		= '1'; // leer
		$marker1_image 		= '../../images/com_einsatzkomponente/images/map/icons/'.$this->params->get('detail_pointer1_image','circle.png');
		$marker2_title 		= ''; // leer
		$marker2_lat  		= ''; // leer
		$marker2_lng 		= '';// leer
		$marker2_image 		= ''; // leer
		$marker2_lat  		= '';// leer
		$marker2_lng 		= '';// leer
		$center_lat  		= $this->gmap_config->start_lat; 
		$center_lng 		= $this->gmap_config->start_lang;
		$gmap_zoom_level 	= $this->gmap_config->gmap_zoom_level; 
		$gmap_onload 		= $this->gmap_config->gmap_onload;
		$zoom_control 		= 'true';
		$document->addScript('//maps.googleapis.com/maps/api/js?key='.$this->params->get ("gmapkey","AIzaSyAuUYoAYc4DI2WBwSevXMGhIwF1ql6mV4E"));			
		$document->addScriptDeclaration( EinsatzkomponenteHelper::getGmap($marker1_title,$marker1_lat,$marker1_lng,$marker1_image,$marker2_title,$marker2_lat,$marker2_lng,$marker2_image,$center_lat,$center_lng,$gmap_zoom_level,$gmap_onload,$zoom_control,$organisationen,$orga_image,$einsatzgebiet,$display_detail_popup,$standort,$display_map_route) );		
		endif;

		if ($this->params->get('gmap_action','0') == '2') :
		
			$standort = new StdClass;
			$standort->gmap_latitude = '0';
			$standort->gmap_longitude= '0';

			$orga = EinsatzkomponenteHelper::getOrganisationen(); 
		if ($this->params->get('display_detail_organisationen','1')) :
			$orga = EinsatzkomponenteHelper::getOrganisationen(); 
	  		$organisationen='['; // Feuerwehr Details  ------>
	  		$n=0;
	  		for($i = 0; $i < count($orga); $i++) {
			$orga_image 	= $orga[$i]->gmap_icon_orga;
			if (!$orga_image) : $orga_image= 'images/com_einsatzkomponente/images/map/icons/'.$this->params->get('einsatzkarte_orga_image','haus_rot.png'); endif;
		  	if($i==$n-1){
			$organisationen=$organisationen.'["'.$orga[$i]->name.'",'.$orga[$i]->gmap_latitude.','.$orga[$i]->gmap_longitude.','.$i.',"'.$orga_image.'"]';
		 	}else {
			$organisationen=$organisationen.'["'.$orga[$i]->name.'",'.$orga[$i]->gmap_latitude.','.$orga[$i]->gmap_longitude.','.$i.',"'.$orga_image.'"';
			$organisationen=$organisationen.'],';
		    }
	        }
	  		$organisationen=substr($organisationen,0,strlen($organisationen)-1);
	  		$organisationen=$organisationen.' ];';
		else:
			$organisationen	 = '[["",1,1,0,"images/com_einsatzkomponente/images/map/icons/'.$this->params->get('einsatzkarte_orga_image','haus_rot.png').'"],["",1,1,0,"images/com_einsatzkomponente/images/map/icons/'.$this->params->get('einsatzkarte_orga_image','haus_rot.png').'"] ]';	
			endif;
			
	  	 $alarmareas1  = $this->gmap_config->gmap_alarmarea;  // Einsatzgebiet  ---->
	 	 $alarmareas = explode('|', $alarmareas1);
	     $einsatzgebiet='[ ';
		  for($i = 0; $i < count($alarmareas)-1; $i++)
		  {
			  	  $areas = explode(',', $alarmareas[$i]);
				  $einsatzgebiet=$einsatzgebiet.'['.$areas[1].','.$areas[0].'],';
		  }
		$areas = explode(',', $alarmareas[0]);
		$einsatzgebiet=$einsatzgebiet.'['.$areas[1].','.$areas[0].'],';
	    $einsatzgebiet=substr($einsatzgebiet,0,strlen($einsatzgebiet)-1);
	    $einsatzgebiet=$einsatzgebiet.' ]';	
		if (!$this->params->get('display_home_einsatzgebiet','1')) :
		$einsatzgebiet='[[0,0]]';
		endif;
		
		$display_map_route		= 'false';	
		
        $display_detail_popup = 'false';
		$marker1_title 		= '1'; // leer
		$marker1_lat  		= '1'; // leer
		$marker1_lng 		= '1'; // leer
		$marker1_image 		= '../../images/com_einsatzkomponente/images/map/icons/'.$this->params->get('detail_pointer1_image','circle.png');
		$marker2_title 		= ''; // leer
		$marker2_lat  		= ''; // leer
		$marker2_lng 		= '';// leer
		$marker2_image 		= ''; // leer
		$marker2_lat  		= '';// leer
		$marker2_lng 		= '';// leer
		$center_lat  		= $this->gmap_config->start_lat;
		$center_lng 		= $this->gmap_config->start_lang;
		$gmap_zoom_level 	= $this->gmap_config->gmap_zoom_level; 
		$gmap_onload 		= $this->gmap_config->gmap_onload;
		$zoom_control 		= 'true';
 		$document->addScript('components/com_einsatzkomponente/assets/osm/util.js');
   		$document->addScript('https://openlayers.org/api/OpenLayers.js');				
   		$document->addScript('https://openstreetmap.org/openlayers/OpenStreetMap.js');	
 		$document->addStyleSheet('components/com_einsatzkomponente/assets/osm/map.css');		
 		$document->addStyleSheet('components/com_einsatzkomponente/assets/osm/ie_map.css');	
 		$document->addScript('components/com_einsatzkomponente/assets/osm/OpenLayers_Map_minZoom_maxZoom_Patch.js');
		$document->addScriptDeclaration( EinsatzkomponenteHelper::getOsm($marker1_title,$marker1_lat,$marker1_lng,$marker1_image,$marker2_title,$marker2_lat,$marker2_lng,$marker2_image,$center_lat,$center_lng,$gmap_zoom_level,$gmap_onload,$zoom_control,$organisationen,$orga_image,$einsatzgebiet,$display_detail_popup,$standort,$display_map_route) );											
 		endif;
		
		//Komponentenversion aus Datenbank lesen
		$this->version 		= EinsatzkomponenteHelper::getVersion (); 

		// Monatsnamen auf Deutsch
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

		  //----Modulposition laden ----
		$this->modulepos_1 = '<div class="mod_eiko1">'.EinsatzkomponenteHelper::module ('eiko1').'</div>'; 
		$this->modulepos_2 = '<div class="mod_eiko2">'.EinsatzkomponenteHelper::module ('eiko2').'</div>'; 

		
        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
;
            throw new Exception(implode("\n", $errors));
        }

        $this->_prepareDocument();
        parent::display($tpl);
    }

    /**
     * Prepares the document
     */
    protected function _prepareDocument() {
        $app = JFactory::getApplication();
        $menus = $app->getMenu();
        $title = null;

        // Because the application sets a default page title,
        // we need to get it from the menu item itself
        $menu = $menus->getActive();
        if ($menu) {
            $this->params->def('page_heading', $this->params->get('page_title', $menu->title));
        } else {
            $this->params->def('page_heading', JText::_('COM_EINSATZKOMPONENTE_DEFAULT_PAGE_TITLE'));
        }
        $title = $this->params->get('page_title', '');
        if (empty($title)) {
            $title = $app->getCfg('sitename');
        } elseif ($app->getCfg('sitename_pagetitles', 0) == 1) {
            $title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
        } elseif ($app->getCfg('sitename_pagetitles', 0) == 2) {
            $title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
        }
        $this->document->setTitle($title);

        if ($this->params->get('menu-meta_description')) {
            $this->document->setDescription($this->params->get('menu-meta_description'));
        }

        if ($this->params->get('menu-meta_keywords')) {
            $this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
        }

        if ($this->params->get('robots')) {
            $this->document->setMetadata('robots', $this->params->get('robots'));
        }
    }

}