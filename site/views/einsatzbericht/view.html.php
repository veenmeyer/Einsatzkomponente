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
class EinsatzkomponenteViewEinsatzbericht extends JViewLegacy {
    protected $state;
    protected $item;
    protected $form;
    protected $params;					// Parameter aus 'Optionen'
    protected $images;
    protected $menu;					// Daten des Menü-Links
    protected $prev_id;					// vorherige Einsatz-ID
    protected $next_id;					// nächste Einsatz-ID
    protected $einsatzlogo;				// Daten für Einsatzart (Pfad für Icon,Logo,...)
    protected $tickerKat; 				// Text für Einsatzkategorie
    protected $alarmierungsart;			// Daten für Alarmierungsart (Pfad für Icon,Logo,...)
    protected $social;					// Daten für Social-Networks-Buttons
    protected $navbar;					// Daten für Social-Networks-Buttons
    /**
     * Display the view
     */
    public function display($tpl = null) {
        
		$this->next_id[0]= new stdClass();
		
		require_once JPATH_SITE.'/administrator/components/com_einsatzkomponente/helpers/einsatzkomponente.php'; // Helper-class laden
		$app	= JFactory::getApplication();
		$selectedOrga = $app->getUserStateFromRequest( "com_einsatzkomponente.selectedOrga", 'selectedOrga', 'alle Organisationen' );
		//echo $selectedOrga;
        $user		= JFactory::getUser();
		//$id = $app->input->get(id); 
        
        $this->state = $this->get('State');
        $this->item = $this->get('Data'); 
		$this->images = EinsatzkomponenteHelper::getEinsatzbilder($this->item->id);
		$this->prev_id = EinsatzkomponenteHelper::getPrev_id($this->item->date1,$selectedOrga); 
		$this->next_id = EinsatzkomponenteHelper::getNext_id($this->item->date1,$selectedOrga);
		
        $this->params = $app->getParams('com_einsatzkomponente');
   		$this->form		= $this->get('Form');
		$this->gmap_config = EinsatzkomponenteHelper::load_gmap_config(); // GMap-Config aus helper laden 
		$this->einsatzlogo = EinsatzkomponenteHelper::getEinsatzlogo($this->item->data1); 
		$this->tickerKat = EinsatzkomponenteHelper::getTickerKat($this->item->tickerkat); 
		$this->alarmierungsart = EinsatzkomponenteHelper::getAlarmierungsart($this->item->alerting); 
		
		// Get active menu
		$app	= JFactory::getApplication();
		$menus	= $app->getMenu();
		$this->menu = $menus->getActive();
		//print_r ($this->menu);
		//echo $this->menu->anzeigejahr;



		// Import CSS + JS 
		$document = JFactory::getDocument();
		$document->addStyleSheet('components/com_einsatzkomponente/assets/css/einsatzkomponente.css');	
		$document->addStyleSheet('components/com_einsatzkomponente/assets/css/responsive.css');
		$document->addScript('components/com_einsatzkomponente/assets/highslide/highslide-with-gallery.js');
		$document->addScript('components/com_einsatzkomponente/assets/highslide/highslide.config.js');		
		$document->addStyleSheet('components/com_einsatzkomponente/assets/highslide/highslide.css'); 
        
		
		
		//print_r ($this->menu);
		$this->social = EinsatzkomponenteHelper::getSocial($this->params,$this->item->id,$this->item->summary);
		
//		$sef = '';
//		$sef_rewrite = '';
//		$conf = JFactory::getConfig();
//		@$sef = $conf[sef]; 
//		@$sef_rewrite = $conf[sef_rewrite]; 
		
		//if ($sef_rewrite) :
		@$this->navbar = EinsatzkomponenteHelper::getNavbar($this->params,$this->prev_id['0']->id,$this->next_id['0']->id,$this->item->id,$this->menu->link.'&Itemid='.$this->menu->id);	
		//endif;	
		//if (!$sef_rewrite) :
		//@$this->navbar = EinsatzkomponenteHelper::getNavbar($this->params,$this->prev_id['0']->id,$this->next_id['0']->id,$this->item->id,$this->menu->link.'&Itemid='.$this->params->get('homelink','').'');	
		//endif;	
		

		if ($this->params->get('gmap_action','0') == '1') :
		
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
		
 		 $standort = EinsatzkomponenteHelper::getStandort_orga($this->item->auswahl_orga); 
		 $display_map_route		= $this->params->get('display_map_route','true');	
//		 echo $standort->gmap_latitude;
//		 echo $standort->gmap_longitude;
//		 echo $standort->name;
		if ($this->params->get('display_detail_einsatzgebiet','1')) :
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
		else:
		$einsatzgebiet='[[0,0]]';
		endif;
			
        $display_detail_popup = $this->params->get('display_detail_popup','false');
		$marker1_title 		= '';
		$marker1_lat  		= '';
		$marker1_lng 		= '';
		$marker1_image 		= '';
		$marker2_title 		= '';
		$marker2_lat  		= '';
		$marker2_lng 		= '';
		$marker2_image 		= '';
		if ($this->params->get('display_detail_einsatz_marker','1') && $this->item->gmap =='1') :
		$marker1_title 		= $this->item->summary;
		$marker1_lat  		= $this->item->gmap_report_latitude;
		$marker1_lng 		= $this->item->gmap_report_longitude;
		$marker1_image 		= $this->einsatzlogo->icon;
		//$marker1_image 		= 'images/com_einsatzkomponente/images/map/icons/'.$this->params->get('detail_pointer1_image','circle.png');
		else:
		$marker1_lat  		= '1';
		$marker1_lng 		= '1';
		endif;
		$center_lat  		= $this->item->gmap_report_latitude; 
		$center_lng 		= $this->item->gmap_report_longitude;
		$gmap_zoom_level 	= $this->params->get('detail_gmap_zoom_level','12'); 
		$gmap_onload 		= $this->params->get('detail_gmap_onload','HYBRID');
		$zoom_control 		= $this->params->get('detail_zoom_control','false');
		$document->addScript('//maps.googleapis.com/maps/api/js?key='.$this->params->get('gmapkey','AIzaSyAuUYoAYc4DI2WBwSevXMGhIwF1ql6mV4E').' ');		
		$document->addScriptDeclaration( EinsatzkomponenteHelper::getGmap($marker1_title,$marker1_lat,$marker1_lng,$marker1_image,$marker2_title,$marker2_lat,$marker2_lng,$marker2_image,$center_lat,$center_lng,$gmap_zoom_level,$gmap_onload,$zoom_control,$organisationen,$orga_image,$einsatzgebiet,$display_detail_popup,$standort,$display_map_route) );											
		endif;
		
		
		if ($this->params->get('gmap_action','0') == '2') :
		
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
			
		if ($this->params->get('display_detail_einsatzgebiet','1')) :
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
		else:
		$einsatzgebiet='[[0,0]]';
		endif;
		
 		$standort = EinsatzkomponenteHelper::getStandort_orga($this->item->auswahl_orga); 
		$display_map_route		= $this->params->get('$display_map_route','true');
		
        $display_detail_popup = $this->params->get('display_detail_popup','false');
		$marker1_title 		= '';
		$marker1_lat  		= '';
		$marker1_lng 		= '';
		$marker1_image 		= '';
		$marker2_title 		= '';
		$marker2_lat  		= '';
		$marker2_lng 		= '';
		$marker2_image 		= '';
		if ($this->params->get('display_detail_einsatz_marker','1')) :
		$marker1_title 		= $this->item->summary;
		$marker1_lat  		= $this->item->gmap_report_latitude;
		$marker1_lng 		= $this->item->gmap_report_longitude;
		$marker1_image 		= $this->einsatzlogo->icon;
		//$marker1_image 		= 'images/com_einsatzkomponente/images/map/icons/'.$this->params->get('detail_pointer1_image','circle.png');
		else:
		$marker1_title 		= $this->item->summary;
		$marker1_lat  		= '1';
		$marker1_lng 		= '1';
		$marker1_image 		= $this->einsatzlogo->icon;
		endif;
		$center_lat  		= $this->item->gmap_report_latitude; 
		$center_lng 		= $this->item->gmap_report_longitude;
		$gmap_zoom_level 	= $this->params->get('detail_gmap_zoom_level','12'); 
		$gmap_onload 		= $this->params->get('detail_gmap_onload','HYBRID');
		$zoom_control 		= $this->params->get('detail_zoom_control','false');
 		$document->addScript('components/com_einsatzkomponente/assets/osm/util.js');
   		$document->addScript('http://www.openlayers.org/api/OpenLayers.js');				
   		$document->addScript('http://www.openstreetmap.org/openlayers/OpenStreetMap.js');	
 		$document->addStyleSheet('components/com_einsatzkomponente/assets/osm/map.css');		
 		$document->addStyleSheet('components/com_einsatzkomponente/assets/osm/ie_map.css');	
 		$document->addScript('components/com_einsatzkomponente/assets/osm/OpenLayers_Map_minZoom_maxZoom_Patch.js');
		$document->addScriptDeclaration( EinsatzkomponenteHelper::getOsm($marker1_title,$marker1_lat,$marker1_lng,$marker1_image,$marker2_title,$marker2_lat,$marker2_lng,$marker2_image,$center_lat,$center_lng,$gmap_zoom_level,$gmap_onload,$zoom_control,$organisationen,$orga_image,$einsatzgebiet,$display_detail_popup,$standort,$display_map_route) );											
 		endif;
		
		
		
		if ($this->params->get('display_detail_bootstrap','0')) :
		// Import Bootstrap
 		$document->addScript('components/com_einsatzkomponente/assets/bootstrap/js/bootstrap.min.js');	
 		$document->addStyleSheet('components/com_einsatzkomponente/assets/bootstrap/css/bootstrap.min.css');
 		$document->addStyleSheet('components/com_einsatzkomponente/assets/bootstrap/css/bootstrap-responsive.min.css');
		endif;
		$document->addStyleDeclaration($this->params->get('detail_css','')); 
		
?>
    <script type="text/javascript">
	// override Highslide settings here
    // instead of editing the highslide.js file
    hs.graphicsDir = '<?php echo JURI::Root();?>components/com_einsatzkomponente/assets/highslide/graphics/';
    </script>
 
 
 
 <?php 	
		// Facebook OpenGraph
		if ($this->item->summary)
		{	$summary = strip_tags($this->item->summary);
 		$opengraph  = '<meta property="og:title" content="#Einsatzinfo: '.$summary.'"/>';
		}
		$opengraph .= '<meta property="og:type" content="article"/>'; 
		$opengraph .= '<meta property="og:url"  content="'.JURI::current().'"/>';
		$opengraph .= '<meta property="og:site_name" content="#Einsatzinfo: '.$this->einsatzlogo->title.'"/>';
		
		if ($this->item->summary)
		{	$summary = strip_tags($this->item->summary);
		$opengraph .= '<meta property="og:description" content="Datum: '.date('d.m.Y', strtotime($this->item->date1)).' --- Ort: #'.$this->item->address.' --- #'.$summary.'" />';
		}
		if ($this->item->desc)
		{	$desc = strip_tags($this->item->desc);
		$opengraph .= '<meta property="og:description" content="Datum: '.date('d.m.Y', strtotime($this->item->date1)).' --- Ort: #'.$this->item->address.' --- #'.$desc.'" />';
		}
	

		if($this->einsatzlogo->list_icon) :
			$fileName_image = str_replace(' ', '%20', $this->einsatzlogo->list_icon);  
			$opengraph .= '<meta property="og:image" content="'.JURI::base().$fileName_image.'"/>';
		endif;
		//$opengraph .= '<meta property="article:publisher" content="https://www.einsatzkomponente.de" />';
		if( $this->item->image ) :
			$fileName_image = str_replace(' ', '%20', $this->item->image);  
			$opengraph .= '<meta property="og:image" content="'.JURI::base().$fileName_image.'"/>';
		endif;

		if ($this->images) :
			for ($i = 0;$i < count($this->images);++$i) { 
			$fileName_image = str_replace(' ', '%20', $this->images[$i]->image);  
			$opengraph .= '<meta property="og:image" content="'.JURI::base().$fileName_image.'"/>';
			} 
	   endif;
	   

		$document->addCustomTag($opengraph);

		// Wenn Titlebild in Bildergalerie enthalten, dann dieses aus der Bildergalerie löschen
	//	if ($this->images and $this->item->image) :
		
	//		$i='0';
	//		while ($i < count($this->images)){ 
	//		if ($this->images[$i]->image == $this->item->image) : 
	//			unset($this->images[$i]);$this->images[$i] = '';
	//		endif;
	//		$i++;
	//		}
	//	endif;
	
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
        
			// Increment the hit counter of the event.
			$model = $this->getModel();
			$model->hit();
		
        $this->_prepareDocument();
        parent::display($tpl);
    }
	/**
	 * Prepares the document
	 */
	protected function _prepareDocument()
	{
		$document = JFactory::getDocument();

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
		
		
		if ($this->item->summary)
		{	$summary = strip_tags($this->item->summary);
			$this->document->setDescription('Datum: '.date('d.m.Y', strtotime($this->item->date1)).' --- Ort: #'.$this->item->address.' --- #'.$summary);
		}
		if ($this->item->desc)
		{	$desc = strip_tags($this->item->desc);
			$this->document->setDescription('Datum: '.date('d.m.Y', strtotime($this->item->date1)).' --- Ort: #'.$this->item->address.' --- #'.$desc);
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
