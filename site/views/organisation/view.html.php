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
 * View to edit
 */
class EinsatzkomponenteViewOrganisation extends JViewLegacy {
    protected $state;
    protected $item;
    protected $form;
    protected $params;
		
    protected $gmap_config;
	protected $orga_fahrzeuge;
    /**
     * Display the view
     */
    public function display($tpl = null) {
        
		require_once JPATH_SITE.'/administrator/components/com_einsatzkomponente/helpers/einsatzkomponente.php'; // Helper-class laden

		$app	= JFactory::getApplication();
        $user		= JFactory::getUser();
        
        $this->state = $this->get('State');
        $this->item = $this->get('Data');
        $this->params = $app->getParams('com_einsatzkomponente');
   		$this->form		= $this->get('Form');
		$this->gmap_config = EinsatzkomponenteHelper::load_gmap_config(); // GMap-Config aus helper laden 
        //print_r ($this->item);break;
		$this->orga_fahrzeuge = EinsatzkomponenteHelper::getOrga_fahrzeuge($this->item->name);  
	    $document = JFactory::getDocument();

		
		if ($this->params->get('gmap_action','0') == '1') :
		$orga_image			= '';
		$standort = new StdClass;
		$standort->gmap_latitude = '0';
		$standort->gmap_longitude= '0';
		$organisationen	 	= '[["",1,1,0],["",1,1,0] ]';	
		$display_map_route	= 'false';	
		$einsatzgebiet		='[[0,0]]';
        $display_detail_popup = 'false';
		$marker1_title 		= $this->item->name;
		$marker1_lat  		= $this->item->gmap_latitude;
		$marker1_lng 		= $this->item->gmap_longitude;
		$marker1_image 		= '../../images/com_einsatzkomponente/images/map/icons/'.$this->params->get('detail_orga_image','haus_rot.png');
		$marker2_title 		= ''; // leer
		$marker2_lat  		= ''; // leer
		$marker2_lng 		= '';// leer
		$marker2_image 		= ''; // leer

		$center_lat  		= $this->item->gmap_latitude; 
		$center_lng 		= $this->item->gmap_longitude;
		$gmap_zoom_level 	= $this->params->get('detail_gmap_zoom_level','12'); 
		$gmap_onload 		= $this->params->get('detail_gmap_onload','HYBRID');
		$zoom_control 		= 'true';
		$document->addScript('https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false');			
		$document->addScriptDeclaration( EinsatzkomponenteHelper::getGmap($marker1_title,$marker1_lat,$marker1_lng,$marker1_image,$marker2_title,$marker2_lat,$marker2_lng,$marker2_image,$center_lat,$center_lng,$gmap_zoom_level,$gmap_onload,$zoom_control,$organisationen,$orga_image,$einsatzgebiet,$display_detail_popup,$standort,$display_map_route) );											
		endif;
		
		if ($this->params->get('gmap_action','0') == '2') :
		$orga_image			= '';
		$standort = new StdClass;
		$standort->gmap_latitude = '0';
		$standort->gmap_longitude= '0';
		$organisationen	 = '[["",1,1,0],["",1,1,0] ]';	
		$einsatzgebiet	='[[0,0]]';
		$display_map_route		= 'false';	
        $display_detail_popup = 'false';
		$marker1_title 		= $this->item->name;
		$marker1_lat  		= $this->item->gmap_latitude;
		$marker1_lng 		= $this->item->gmap_longitude;
		$marker1_image 		= '../../images/com_einsatzkomponente/images/map/icons/'.$this->params->get('detail_orga_image','haus_rot.png');
		$marker2_title 		= ''; // leer
		$marker2_lat  		= ''; // leer
		$marker2_lng 		= '';// leer
		$marker2_image 		= ''; // leer

		$center_lat  		= $this->item->gmap_latitude; 
		$center_lng 		= $this->item->gmap_longitude;
		$gmap_zoom_level 	= $this->params->get('detail_gmap_zoom_level','12'); 
		$gmap_onload 		= $this->params->get('detail_gmap_onload','HYBRID');
		$zoom_control 		= 'true';
 		$document->addScript('components/com_einsatzkomponente/assets/osm/util.js');
   		$document->addScript('http://www.openlayers.org/api/OpenLayers.js');				
   		$document->addScript('http://www.openstreetmap.org/openlayers/OpenStreetMap.js');	
 		$document->addStyleSheet('components/com_einsatzkomponente/assets/osm/map.css');		
 		$document->addStyleSheet('components/com_einsatzkomponente/assets/osm/ie_map.css');	
 		$document->addScript('components/com_einsatzkomponente/assets/osm/OpenLayers_Map_minZoom_maxZoom_Patch.js');
		$document->addScriptDeclaration( EinsatzkomponenteHelper::getOsm($marker1_title,$marker1_lat,$marker1_lng,$marker1_image,$marker2_title,$marker2_lat,$marker2_lng,$marker2_image,$center_lat,$center_lng,$gmap_zoom_level,$gmap_onload,$zoom_control,$organisationen,$orga_image,$einsatzgebiet,$display_detail_popup,$standort,$display_map_route) );											
 		endif;

		$document->addStyleDeclaration($this->params->get('organisation_css','')); 
		
		// Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors));
        }
        
        
        
        if($this->_layout == 'edit') {
            
            $authorised = $user->authorise('core.create', 'com_einsatzkomponente');
            if ($authorised !== true) {
                throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
            }
        }
        if($this->item->state === '0') : throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'),'0'); endif;
        if($this->item->state === '2') : throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'),'2'); endif;
        if($this->item->state === '-2') : throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'),'-2'); endif;
        
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
