<?php
/**
 * @version     3.15.0
 * @package     com_einsatzkomponente
 * @copyright   Copyright (C) 2017 by Ralf Meyer. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ralf Meyer <ralf.meyer@mail.de> - https://einsatzkomponente.de
 */

// no direct access
defined('_JEXEC') or die;  


//Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_einsatzkomponente', JPATH_ADMINISTRATOR);

?>

  
          
<?php if( $this->item ) : ?> 

    
    
    
    
            <!--Navigation-->
			<div class="eiko_navbar_2" style="float:<?php echo $this->params->get('navi_detail_pos','left');?>;"><?php echo $this->navbar;?></div>
            <!--Navigation ENDE-->

  <div class="distance100">&nbsp;</div>
   <div class="clearfix"></div>
    

<div class="content_news_inner">
  <div id="post-6277">
    <div class="news"><h2 class="news_titel_single"><?php echo $this->item->summary; ?></h2></div> 
     <div class="content_news_info">
       <div class="content_news_datum"><i class="icon-calendar" style="margin-right:5px;"></i><strong><?php echo date("d.m.Y H:i", strtotime($this->item->date1)).' Uhr'; ?></strong>
			<?php if ($this->params->get('display_detail_hits','1')):?>
            <span class="badge pull-right small">Zugriffe: <?php echo $this->item->counter; ?></span>
            <?php endif;?>
       </div>
       <div class="content_news_ort"><i class="icon-globe" style="margin-right:5px;"></i>Ort: <?php echo $this->item->address; ?></div>
       <div class="content_news_autor"><i class="icon-user" style="margin-right:5px;"></i>Einsatzleiter: <?php echo $this->item->boss; ?></div>
       <div class="clear"></div>
     </div>
    <div class="news_post">
			<?php if( $this->item->desc) : ?>
			<?php if ($this->params->get('display_detail_desc','1')): ?>
            <?php jimport('joomla.html.content'); ?>  
            <?php $Desc = JHTML::_('content.prepare', $this->item->desc); ?>
        	<p style="text-align: justify;"><?php echo $Desc; ?></p>
			<?php endif;?>
			<?php endif;?>

		<?php
			$plugin = JPluginHelper::getPlugin('content', 'myshariff') ;
			if ($plugin) : 	echo JHTML::_('content.prepare', '{myshariff}');endif;
			?>
  
<div id="attachment_6278" class="wp-caption aligncenter">
<?php if( $this->item->image ) : ?>

  <div style=" text-align:center;">
                <div>
    			  <a href="<?php echo $this->item->image;?>" rel="highslide[<?php echo $this->item->id; ?>]" class="highslide" onClick="return hs.expand(this, { captionText: '<?php echo $this->item->einsatzart;?> am <?php echo date("d.m.Y - H:i", strtotime($this->item->date1)).' Uhr'; ?><br/><?php echo $this->images[$i]->comment;?>' });" alt ="<?php echo $this->item->einsatzart;?>">
                  <img  class="img-rounded" src="<?php echo $this->item->image;?>"  alt="<?php echo $this->item->einsatzart;?>" title="<?php echo $this->item->einsatzart;?>" alt ="<?php echo $this->item->einsatzart;?>" style="max-width:600px;"/>
                  </a>
                </div>
  </div>
<?php endif;?>

</div>
<p>&nbsp;</p>
    </div>  
  </div>
</div>
  
  <div class="distance100">&nbsp;</div>
   <h2>Sonstige Informationen</h2>
			<?php if ($this->images) : ?>
      <h3>Einsatzbilder</h3> 
              <div class="row-fluid">
            <ul class="thumbnails">
            <?php
			$n = false;
			for ($i = count($this->images)-count($this->images);$i < count($this->images);++$i) { 
			if (@$this->images[$i]->comment) : $n = true; 
			endif;
			}
			$i= '';
			for ($i = count($this->images)-count($this->images);$i < count($this->images);++$i) { 
			if (@$this->images[$i]) :
			$fileName_thumb = JURI::Root().$this->images[$i]->thumb;
			$fileName_image = JURI::Root().$this->images[$i]->image;
			$thumbwidth = $this->params->get('detail_thumbwidth','100px'); 
			?>   
              <li>
                <div class="thumbnail eiko_thumbnail_2" style="max-width:<?php echo $thumbwidth;?>;)">
    			<a href="<?php echo $fileName_image;?>" rel="highslide[<?php echo $this->item->id; ?>]" class="highslide" onClick="return hs.expand(this, { captionText: '<?php echo $this->einsatzlogo->title;?> am <?php echo date("d.m.Y - H:i", strtotime($this->item->date1)).' Uhr'; ?><?php if ($this->images[$i]->comment) : ?><?php echo '<br/>Bild-Info: '.$this->images[$i]->comment;?><?php endif; ?>' });" alt ="<?php echo $this->einsatzlogo->title;?>">
                <img  class="eiko_img-rounded eiko_thumbs_2" src="<?php echo $fileName_thumb;?>"  alt="<?php echo $this->einsatzlogo->title;?>" title="Bild-Nr. <?php echo $this->images[$i]->id;?>"  style="width:<?php echo $this->params->get('detail_thumbwidth','100px');?>;)" alt ="<?php echo $this->einsatzlogo->title;?>"/>
				
<?php if ($this->images[$i]->comment) : ?>
<br/><i class="icon-info-sign" style=" margin-right:5px;"></i><small>Bild-Info</small>
 <?php else: ?>
<?php if ($n == true) : echo '<br/><i class="" style=" margin-right:5px;"></i><small></small>
';endif;?>
 <?php endif; ?>
              </a>
			  </div>
           </li>
			<?php endif; ?>
			<?php 	} ?>
            </ul>
          </div>
<?php endif; ?>

            <!--Einsatzkarte-->
			<?php $user	= JFactory::getUser();?>
            <?php if( $this->item->gmap) : ?> 
            <?php if( $this->item->gmap_report_latitude != '0' ) : ?> 
            <?php if( $this->params->get('display_detail_map_for_only_user','0') == '1' || $user->id ) :?> 
			<?php if ($this->params->get('gmap_action','0')=='1') : ?>
  			<div class="distance100">&nbsp;</div>
   			<h3>Einsatzort</h3> 
  			<div id="map-canvas"  style="width:100%; height:<?php echo $this->params->get('detail_map_height','250px');?>;">
    		<noscript>Dieser Teil der Seite erfordert die JavaScript Unterstützung Ihres Browsers!</noscript>
			</div>
            <?php endif;?>
			<?php if ($this->params->get('gmap_action','0')=='2') : ?>
  			<div class="distance100">&nbsp;</div>
  			<h3>Einsatzort</h3> 
			<body onLoad="drawmap();"></body>
			<!--<div id="descriptionToggle" onClick="toggleInfo()">Informationen zur Karte anzeigen</div>
			<div id="description" class="">Einsatzkarte</div>-->
			<div id="map" style="width:100%; height:<?php echo $this->params->get('detail_map_height','250px');?>;"></div> 
    		<noscript>Dieser Teil der Seite erfordert die JavaScript Unterstützung Ihres Browsers!</noscript>
            <?php endif;?>
			<?php else:?> 
			<?php echo '<span style="padding:5px;" class="label label-info">( Bitte melden Sie sich an, um den Einsatzort auf einer Karte zu sehen. )</span><br/><br/>';?>
			<?php endif;?>
            <?php endif;?>
            <?php endif;?>
            <!--Einsatzkarte ENDE-->
            
<div class="distance100">&nbsp;</div> 

<?php if( $this->item->presse or $this->item->presse2 or $this->item->presse3) : ?>
 <h3>Quelle oder weitere Infos</h3>
           <table>
           <tr>
           <td style="vertical-align: top;">
           </td>
           <td style="vertical-align: top;">
            <?php if( $this->item->presse) : ?>
			<?php echo '<a href="'.$this->item->presse.'" target="_blank"><i class="icon-share-alt" style="margin-right:5px;"></i><small>'.$this->item->presse_label.'</small></a><br/>'; ?>
            <?php endif;?>
            <?php if( $this->item->presse2 ) : ?>
			<?php echo '<a href="'.$this->item->presse2.'" target="_blank"><i class="icon-share-alt" style="margin-right:5px;"></i><small>'.$this->item->presse2_label.'</small></a><br/>'; ?>
            <?php endif;?>
            <?php if( $this->item->presse3 ) : ?>
			<?php echo '<a href="'.$this->item->presse3.'" target="_blank"><i class="icon-share-alt" style="margin-right:5px;"></i><small>'.$this->item->presse3_label.'</small></a><br/>'; ?>
            <?php endif;?>
            </td>
            </tr>
            </table>
            <?php endif;?>
    
<?php if ($this->params->get('display_detail_footer','1')) : ?>
<div class="distance100">&nbsp;</div>
<div class="detail_footer"><i class="icon-info-sign" style="margin-right:5px;"></i><?php echo $this->params->get('display_detail_footer_text','Kein Detail-Footer-Text vorhanden');?> </div>
<?php endif;?>

    
    
    
    
    

<?php else: ?>
    Could not load the item
<?php endif; ?>
