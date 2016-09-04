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
jimport('joomla.application.component.controllerform');
/**
 * Einsatzbericht controller class.
 */
class EinsatzkomponenteControllerEinsatzbericht extends JControllerForm
{
    function __construct() {
        $this->view_list = 'einsatzberichte';
        parent::__construct();
    }
    public function pdf() {
    	// Check for request forgeries
	JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
	require_once JPATH_SITE.'/administrator/components/com_einsatzkomponente/helpers/einsatzkomponente.php'; // Helper-class laden
	
    	$cid = JFactory::getApplication()->input->get('id', array(), 'array');
    	if (!is_array($cid) || count($cid) < 1)
	{
	    JLog::add(JText::_($this->text_prefix . '_NO_ITEM_SELECTED'), JLog::WARNING, 'jerror');
	}
	else
	{
    	    $msg = EinsatzkomponenteHelper::pdf($cid);
	    $this->setRedirect('index.php?option=com_einsatzkomponente&view=einsatzbericht&layout=edit&id='.$cid[0], $msg);
	}
    }
    /*public function pdf()
     {
     	require_once JPATH_COMPONENT.'/helpers/fpdf.php';
	$rep_id = JFactory::getApplication()->input->get('id', '0');
	
	$db = JFactory::getDBO();
	$query = 	"SELECT eb.id as id, eb.counter as counter, aa.title as alarmart, tk.title as einsatzkat, 
			  ea.title as einsatzart, eb.address as ort, eb.date1 as startd, eb.date2 as fahrd, 
			  eb.date3 as endd, eb.boss as el, eb.boss2 as ef, eb.people as pers, eb.auswahl_orga as orgas, 
			  eb.vehicles as fahrz, eb.ausruestung as ausruest, eb.summary as kurzt, eb.desc as langt 
			FROM ffineu_eiko_einsatzberichte eb 
			INNER JOIN #__eiko_einsatzarten ea ON ea.id = eb.data1 
			INNER JOIN #__eiko_alarmierungsarten aa ON aa.id = eb.alerting
			INNER JOIN #__eiko_tickerkat tk ON tk.id = eb.tickerkat
			WHERE eb.id = ".$rep_id;
	$db->setQuery($query);
	$einsatz = $db->loadObjectList();
	
	//Varaiblen für Orga- udn Fahrzeugnamen
	$orgas = $einsatz[0]->orgas;
	$fahrzeuge = $einsatz[0]->fahrz;
	
	$query = "SELECT name FROM #__eiko_fahrzeuge WHERE id IN (".$fahrzeuge.")";
	$db->setQuery($query);
	$db->execute();
	$anz_fahrz = $db->getNumRows();
	$fahrz_arr = $db->loadObjectList();
	$fahrz_all = "";
	$i = 0;
	foreach ($fahrz_arr as $key => $value) {
	    if ($i == 0)
	    	$fahrz_all .= "";
	    else
	    	$fahrz_all .= " ";
	    $fahrz_all .= $value->name.",";
	    $i += 1;
	}
	//Entferne das Komma am Ende
	$fahrz_all = substr($fahrz_all, 0, -1);
	
	$query = "SELECT name FROM #__eiko_organisationen WHERE id IN (".$orgas.")";
	$db->setQuery($query);
	$db->execute();
	$anz_orgas = $db->getNumRows();
	$orga_arr = $db->loadObjectList();
	$orgas_all = "";
	$i = 0;
	foreach ($orga_arr as $key => $value) {
	    if ($i == 0)
	    	$orgas_all .= "";
	    else
	    	$orgas_all .= " ";
	    $orgas_all .= $value->name.",";
	    $i += 1;
	}
	$i = 0;
	//Entferne das Komma am Ende
	$orgas_all = substr($orgas_all, 0, -1);
	
	//Variablendeklaraion für die PDF
	$id = $einsatz[0]->id;
	$counter = $einsatz[0]->counter;
	$alarmart = $einsatz[0]->alarmart;
	$einsatzkat = $einsatz[0]->einsatzkat;
	$einsatzart = $einsatz[0]->einsatzart;
	$ort = $einsatz[0]->ort;
	$beginn = $einsatz[0]->startd;
	$ausrueck = $einsatz[0]->fahrd;
	$ende = $einsatz[0]->endd;
	$einsatzleiter = $einsatz[0]->el;
	$einsatzfuehrer = $einsatz[0]->ef;
	$mannschaft = $einsatz[0]->pers;
	//Ausrüstung noch nicht implementiert
	$ausruest = $ausruest_all;
	$kurzbericht = $einsatz[0]->kurzt;
	$bericht = $einsatz[0]->langt;
	$organisationen = $orgas_all;
	$fahrzeuge = $fahrz_all;
	
     	$params = JComponentHelper::getParams('com_einsatzkomponente');
     	
     	//Hier wird das PDF-Grundgerüst erstellt
	$pdf=new FPDF('P','mm','A4');
	
	//Definiere die Breite und Höhe der Beschriftungszellen:
	$breite_beschriftung = 45;
	$hoehe = 8;
	
	//Breite des Inhalts. 0 = bis zum rechten Seitenrand
	$breite_inhalt = 10;
	
	//Neue Seite wird eingefügt
	$pdf->AddPage();
	
	//Schriftart und -größe wird definiert 
	$pdf->SetFont('Arial','',12);
	
	//Header-Image
	if (!$params->get('pdf_header') == '') {
		$img = "../media/com_einsatzkomponente/images/pdf/".$params->get('pdf_header');
		list($width, $height) = $pdf->resizeToFit($img);
		$pdf->resizeImage($img,0,0);
		//Setze Abstand von der Oberkante des Blatts die der Höhe des Bilds entspricht
		$pdf->Ln($height);
	}
	//Erstelle die Zellen
	if ($params->get('pdf_show_id') == 1) {
		$pdf->Cell($breite_beschriftung,$hoehe,utf8_decode(JText::_('COM_EINSATZKOMPONENTE_LEGEND_EINSATZBERICHT').'-'.JText::_('COM_EINSATZKOMPONENTE_FORM_LBL_EINSATZBERICHT_ID').':'));
		$pdf->Cell($breite_inhalt,$hoehe,$id,0,1);
	}
	if ($params->get('pdf_show_counter') == 1) {
		$pdf->Cell($breite_beschriftung,$hoehe,utf8_decode(JText::_('COM_EINSATZKOMPONENTE_FORM_LBL_EINSATZBERICHT_COUNTER').':'));
		$pdf->Cell($breite_inhalt,$hoehe,$counter,0,1);
	}
	if ($params->get('pdf_show_alarmart') == 1) {
		$pdf->Cell($breite_beschriftung,$hoehe,utf8_decode(JText::_('COM_EINSATZKOMPONENTE_FORM_LBL_EINSATZBERICHT_ALERTING').':'));
		$pdf->Cell($breite_inhalt,$hoehe,utf8_decode($alarmart),0,1);
	}
	if ($params->get('pdf_show_einsatzart') == 1) {
		$pdf->Cell($breite_beschriftung,$hoehe,utf8_decode(JText::_('COM_EINSATZKOMPONENTE_FORM_LBL_EINSATZBERICHT_DATA1').':'));
		$pdf->Cell($breite_inhalt,$hoehe,utf8_decode($einsatzart),0,1);
	}
	if ($params->get('pdf_show_einsatzkat') == 1) {
		$pdf->Cell($breite_beschriftung,$hoehe,utf8_decode(JText::_('COM_EINSATZKOMPONENTE_FORM_LBL_EINSATZBERICHT_KATEGORIE').':'));
		$pdf->Cell($breite_inhalt,$hoehe,utf8_decode($einsatzkat),0,1);
	}
	if ($params->get('pdf_show_ort') == 1) {
		$pdf->Cell($breite_beschriftung,$hoehe,utf8_decode(JText::_('COM_EINSATZKOMPONENTE_FORM_LBL_EINSATZBERICHT_ADDRESS').':'));
		$pdf->Cell($breite_inhalt,$hoehe,utf8_decode($ort),0,1);
	}
	if ($params->get('pdf_show_alarmzeit') == 1) {
		$pdf->Cell($breite_beschriftung,$hoehe,JText::_('COM_EINSATZKOMPONENTE_FORM_LBL_EINSATZBERICHT_TIMESTART').':');
		$pdf->Cell($breite_inhalt,$hoehe,$beginn,0,1);
	}
	if ($params->get('pdf_show_ausfahrzeit') == 1) {
		$pdf->Cell($breite_beschriftung,$hoehe,JText::_('COM_EINSATZKOMPONENTE_FORM_LBL_EINSATZBERICHT_DATE2').':');
		$pdf->Cell($breite_inhalt,$hoehe,$ausrueck,0,1);
	}
	if ($params->get('pdf_show_einsatzende') == 1) {
		$pdf->Cell($breite_beschriftung,$hoehe,JText::_('COM_EINSATZKOMPONENTE_FORM_LBL_EINSATZBERICHT_TIMEEND').':');
		$pdf->Cell($breite_inhalt,$hoehe,$ende,0,1);
	}
	if ($params->get('pdf_show_einsatzleiter') == 1) {
		$pdf->Cell($breite_beschriftung,$hoehe,utf8_decode(JText::_('COM_EINSATZKOMPONENTE_FORM_LBL_EINSATZBERICHT_BOSS').':'));
		$pdf->Cell($breite_inhalt,$hoehe,utf8_decode($einsatzleiter),0,1);
	}
	if ($params->get('pdf_show_einsatzfuehrer') == 1) {
		$pdf->Cell($breite_beschriftung,$hoehe,utf8_decode(JText::_('COM_EINSATZKOMPONENTE_FORM_LBL_EINSATZBERICHT_BOSS2').':'));
		$pdf->Cell($breite_inhalt,$hoehe,utf8_decode($einsatzführer),0,1);
	}
	if ($params->get('pdf_show_mannschaft') == 1) {
		$pdf->Cell($breite_beschriftung,$hoehe,utf8_decode(JText::_('COM_EINSATZKOMPONENTE_FORM_LBL_EINSATZBERICHT_PEOPLE').':'));
		$pdf->Cell($breite_inhalt,$hoehe,$mannschaft,0,1);
	}
	if ($params->get('pdf_show_orgas') == 1) {
		$pdf->Cell($breite_beschriftung,$hoehe,utf8_decode(JText::_('COM_EINSATZKOMPONENTE_FORM_LBL_EINSATZBERICHT_AUSWAHLORGA').':'));
		$pdf->Cell($breite_inhalt,$hoehe,utf8_decode($organisationen),0,1);
	}
	if ($params->get('pdf_show_fahrzeuge') == 1) {
		$pdf->Cell($breite_beschriftung,$hoehe,utf8_decode(JText::_('COM_EINSATZKOMPONENTE_FORM_LBL_EINSATZBERICHT_VEHICLES').':'));
		$pdf->Cell($breite_inhalt,$hoehe,utf8_decode($fahrzeuge),0,1);
	}
	if ($params->get('pdf_show_ausruestung') == 1) {
		$pdf->Cell($breite_beschriftung,$hoehe,utf8_decode(JText::_('COM_EINSATZKOMPONENTE_FORM_LBL_EINSATZBERICHT_AUSRUESTUNG').':'));
		$pdf->Cell($breite_inhalt,$hoehe,utf8_decode($ausruest),0,1);
	}
	if ($params->get('pdf_show_kurzbericht') == 1) {
		$pdf->Cell($breite_beschriftung,$hoehe,utf8_decode(JText::_('COM_EINSATZKOMPONENTE_FORM_LBL_EINSATZBERICHT_SUMMARY').':'));
		$pdf->Cell($breite_inhalt,$hoehe,utf8_decode($kurzbericht),0,1);
	}
	if ($params->get('pdf_show_langbericht') == 1) {
		$pdf->Cell($breite_beschriftung,$hoehe,utf8_decode(JText::_('COM_EINSATZKOMPONENTE_FORM_LBL_EINSATZBERICHT_DESC').':'));
		$pdf->MultiCell(150,$hoehe,utf8_decode($bericht),0,1);
	}
	
	//prüfe Pfadangabe auf "/" am Ende und schneide dieses Zeichen ab wenn nötig
	$speicherort = $params->get('pdf_speicherort');
	if ($speicherort != '')
	{
	    $lastchar = substr($speicherort, -1, 1);
	    if ($lastchar == "/")
	    {
	    	$speicherort = substr($speicherort, 0, -1);
	    }
	    $path = '../'.$speicherort;
	}
	
	//Gebe PDF in definiertes Verzeichnis aus und benenne sie mit der Einsatz-ID
	$pdfname = 'einsatzbericht_id'.$id.'.pdf';
	$pdf->Output($path.'/'.$pdfname,'F');
	
	//Nachricht bei Erfolg
	$msg = JText::_( 'Datei "'.$pdfname.'" wurde in den Ordner "'.$speicherort.'" exportiert.' );
	
	//Leite anschließend zum Einsatzbericht weiter
        $this->setRedirect('index.php?option=com_einsatzkomponente&view=einsatzbericht&layout=edit&id='.$rep_id, $msg); 
     	$this->redirect;
     }*/
     function swf()  
     { 
        $pview = JFactory::getApplication()->input->get('view', 'einsatzbericht');
	$rep_id = JFactory::getApplication()->input->get('id', '0');

	if (parent::save()) :
	    if ($rep_id == '0') :
		$db = JFactory::getDBO();
		$query = "SELECT id FROM #__eiko_einsatzberichte ORDER BY id DESC LIMIT 1";
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		$rep_id      = $rows[0]->id;
		$msg    = JText::_( 'Neuer Einsatzbericht gespeichert ! Sie können jetzt die Einsatzbilder zu diesem Einsatz hinzufügen.' );
	        $this->setRedirect('index.php?option=com_einsatzkomponente&view=swfupload&pview='.$pview.'&rep_id='.$rep_id.'', $msg); 
	    endif;
	else:
            $this->setRedirect('index.php?option=com_einsatzkomponente&view=einsatzbericht&layout=edit', $msg); 
	endif;
		
	if (!$rep_id == '0') :
            //$msg    = JText::_( '' );  
            $this->setRedirect('index.php?option=com_einsatzkomponente&view=swfupload&pview='.$pview.'&rep_id='.$rep_id.'', $msg); 
	endif;
	}
	//function  

    	function save($key = NULL, $urlVar = NULL) {

		// Check for request forgeries
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));

		// Get items to remove from the request.
		$send = 'false';
		$cid = JFactory::getApplication()->input->get('id','0');
		$params = JComponentHelper::getParams('com_einsatzkomponente');

		if (parent::save()) :

		if(!$_FILES['data']['name']['0'] =='') : 
		if (!$cid) :
		$db = JFactory::getDBO();
		$query = "SELECT id FROM #__eiko_einsatzberichte ORDER BY id DESC LIMIT 1";
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		$cid      = $rows[0]->id;
		endif;
		upload ($cid,'data');
			endif;		

				if ( $params->get('send_mail_auto', '0') ): 
		if (!$cid) :
		$db = JFactory::getDBO();
		$query = "SELECT id FROM #__eiko_einsatzberichte ORDER BY id DESC LIMIT 1";
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		$cid      = $rows[0]->id;
		$send = sendMail_auto($cid,'neuer Bericht: ');
		else:
		$send = sendMail_auto($cid,'Update: ');
		endif;
		endif;
	endif;
    //print_r ($send);break;
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
		
					$data = array();
					foreach(explode(',',$result[0]->auswahl_orga) as $value):
						$db = JFactory::getDbo();
						$query	= $db->getQuery(true);
						$query
							->select('name')
							->from('`#__eiko_organisationen`')
							->where('id = "' .$value.'"');
						$db->setQuery($query);
						$results = $db->loadObjectList();
						if(count($results)){
							$data[] = ''.$results[0]->name.''; 
						}
					endforeach;
					$auswahl_orga=  implode(',',$data); 

					$orga		 = explode( ',', $auswahl_orga);
		$orgas 		 = str_replace(",", " +++ ", $auswahl_orga);
 
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
        return 'gesendet'; 
    }
	
	function upload($id,$fieldName)
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

