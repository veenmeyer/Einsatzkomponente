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

$params = JComponentHelper::getParams('com_einsatzkomponente');
$watermark      = JFactory::getApplication()->input->get('watermark_image', $params->get('watermark_image'));
//echo $watermark;
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
		<a class="btn btn-primary" href="index.php?option=com_einsatzkomponente&view=<?php echo $pview;?>&layout=edit&id=<?php echo $rep_id;?>" target="_self">zurück</a>
				</div> 
		
						<!-- Button to trigger modal -->
						<br/>
						<a href="#myModal" role="button" class="btn" data-toggle="modal">Temponäres Wasserzeichen auswählen</a>
		
						<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						<h3 id="myModalLabel">Standard-Wasserzeichen: <?php echo $watermark;?></h3>
						</div>
						<div class="modal-body">

<?php
jimport('joomla.filesystem.folder');
$png_files = JFolder::files('../administrator/components/com_einsatzkomponente/assets/images/watermark/', '.png');

$list = '<select id="watermark_image" name="watermark_image" onchange="changeText_watermark()">';
foreach ($png_files as $file) {
	if ($watermark == $file) {
		   $list .= '<option value="'.$file.'" selected>'.$file.'</option>';

	}
	else {
   $list .= '<option value="'.$file.'">'.$file.'</option>';
	}
}
$list .= '</select>'; 
?>

						
<div class="control-group">
	<div class="control-label">
	<label id="jform_watermark_image-lbl" for="jform_watermark_image" class="hasTooltip" title="">
	Wasserzeichen wählen :</label>									
	</div>
	<div class="controls">  
	<?php echo $list;?>
	</div>
</div>					
						
						<strong>Temponäres Wasserzeichen wählen </strong><br/>Sie können Wasserzeichen hinzufügen. Einfach in den folgenden Ordner hochladen : ../administrator/components/com_einsatzkompponente/assets/images/watermark/    Die Datei muss zwingend eine PNG-Datei sein !!
						
						<h4>Mehr Infos dazu auf www.einsatzkomponente.de</h4>
						</div>
						<div class="modal-footer">
						<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">OK</button>
						<button class="btn btn-primary">Speichern</button> 
						</div>
						</div>	
	
		</fieldset>
		
<script type="text/javascript">
function changeText_watermark(){
    var userInput1 = document.getElementById("watermark_image").value;
	document.getElementById("watermark").value = userInput1;
}
</script>	
<input type="text" name="watermark_image" id="watermark" value="<?php echo $watermark;?>"/>
<input type="hidden" name="option" value="com_einsatzkomponente" />
<input type="hidden" name="view" value="swfupload" />
<input type="hidden" name="pview" value="<?php echo $pview;?>" />
<input type="hidden" name="rep_id" value="<?php echo $rep_id;?>" />
<input type="hidden" name="layout" value="edit" />
	</form>
	
	
	

						
	
</div>
</td></tr></table>
