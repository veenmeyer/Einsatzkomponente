<?php 
/**
* @package		JJ SWFUpload
* @author		JoomJunk
* @copyright	Copyright (C) 2011 - 2012 JoomJunk. All Rights Reserved
* @license		http://www.gnu.org/licenses/gpl-3.0.html
*/
 
 // No direct access
defined('_JEXEC') or die;
$pview      = JFactory::getApplication()->input->get('pview', 'kontrollcenter');
$rep_id      = JFactory::getApplication()->input->get('rep_id', 0);

$db =  JFactory::getDBO();
$query = 'SELECT id, summary as title, date1 FROM `#__eiko_einsatzberichte` WHERE id='.$rep_id.' AND state=1 LIMIT 1';
$db->setQuery($query);
$reportDb = $db->loadObjectList();
?>
	<table style="width:500px;
	margin:0px auto;
	text-align:left;
	padding:15px;
	border:1px solid #333;
	background-color:#eee;
">
		
			<tr>
<td align="center">
<div id="swfuploader">
	<form id="adminForm" name="adminForm" action="index.php" method="post" enctype="multipart/form-data">
		<fieldset class="adminform">
            <h1>Flash-Uploader für Einsatzbilder</h1><br/><span><?php echo 'max. Filegröße darf '.ini_get("upload_max_filesize").' nicht überschreiten';?></span>
				<div class="fieldset flash" id="fsUploadProgress">
					<span class="label label-warning"><?php echo JText::_( 'Bilder hochladen für Einsatzbericht-ID : ' ).$reportDb[0]->id.'</span><br/><span class="label label-info">'.$reportDb[0]->date1.'</span><br/><span class="label label-info">'.$reportDb[0]->title; ?></span>
				</div>
			<div id="divStatus">0 <?php echo JText::_( 'Bilder hochgeladen' ); ?></div>
				<div>
					<span id="spanButtonPlaceHolder"></span><br/><br/>  
					<input id="btnCancel" type="button" value="Alle Uploads abbrechen" onclick="swfu.cancelQueue();" disabled="disabled" style="margin-left: 2px; font-size: 8pt; height: 29px;" /><br/><br/><br/><br/>
                    <input id="btnSubmit" type="submit" value="weiter / abbrechen" class="btn btn-primary" style="margin-left: 2px; font-size: 8pt; height: 29px;" />
				</div> 
		</fieldset>
<input type="hidden" name="option" value="com_einsatzkomponente" />
<input type="hidden" name="view" value="<?php echo $pview;?>" />
<input type="hidden" name="id" value="<?php echo $rep_id;?>" />
<input type="hidden" name="layout" value="edit" />
	</form>
</div>
</td></tr></table>
