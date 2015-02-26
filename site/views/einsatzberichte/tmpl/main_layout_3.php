<?php
/**
 * @version     3.0.0
 * @package     com_einsatzkomponente
 * @copyright   Copyright (C) 2013 by Ralf Meyer. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ralf Meyer <webmaster@feuerwehr-veenhusen.de> - http://einsatzkomponente.de
 */
// no direct access
defined('_JEXEC') or die;


//Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_einsatzkomponente', JPATH_ADMINISTRATOR);
//print_r ($this->organisationen);

//echo $this->selectedYear; 
?>	

	<style>
	.klappLayer div.eintragHeadline { line-height:23px; font-weight:bold; color: #1e5ba5; background-color:#8eadd2;  }
.eintragHeadline span { background:url(images/arrow_down_blue.png) right bottom no-repeat; margin-left:7px; display:inline-block; width:98%; }
.klappLayer .active span { background:url(images/arrow_up_blue.png) right bottom no-repeat; }
.klappLayer div { cursor:pointer; width:452px; border-bottom:1px solid #1e5ba5; border-right:1px solid #1e5ba5; border-left:1px solid #1e5ba5; } 
.klappLayer div.first  { margin-top:10px; border-top:1px solid #1e5ba5; }
.klappLayer div.last { border-bottom:1px solid #1e5ba5; }
.klappLayer div.eintragContent { cursor:default; background-color: #c6d6e8; padding-top: 10px; padding-bottom: 15px; padding-left: 10px; padding-right: 10px; width: 432px; }
div.klappLayer {margin-bottom:10px;}
</style>

     <script language="javascript" type="text/javascript">
          var imageCount = 0;
          $(document).ready(function () {
                 //Aufklapp-Layer:
              $(".klappLayer div.eintragHeadline:first").addClass("active");
              $(".klappLayer div.eintragContent:not(:first)").hide();
              $(".klappLayer div.eintragHeadline").click(function () {
                  $(this).next("div").slideToggle("slow").siblings("div.eintragContent:visible").slideUp("slow");
                  $(this).toggleClass("active");
                  $(this).siblings("div.eintragHeadline").removeClass("active");
              });
			   });
      </script>

<!--RSS-Feed Imag-->
<?php if ($this->params->get('display_home_rss','1')) : ?>
<div style="float:right;" class="eiko_rss" ><a href="<?php JURI::base();?>index.php?option=com_einsatzkomponente&view=einsatzberichte&format=feed&type=rss"><img src="<?php echo JURI::Root();?>/components/com_einsatzkomponente/assets/images/livemarks.png" class="eiko_rss_icon" border="0" alt="rss-feed-image"></a></div>
<?php endif; ?>


<!--Page Heading-->
<?php if ($this->params->get('show_page_heading', 1)) : ?>
<div class="page-header">
<h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
</div>
<?php endif;?>


<?php // Filter ------------------------------------------------------------------------------------
	

?><form action="#" method=post><?php


if (!$this->params->get('anzeigejahr','0') and $this->params->get('display_filter_jahre','1')) : 
	$years[] = JHTML::_('select.option', '', JTEXT::_('Bitte das Jahr auswählen')  , 'id', 'title');
	$years[] = JHTML::_('select.option', '9999', JTEXT::_('Alle Einsätze anzeigen')  , 'id', 'title');
	$years = array_merge($years, (array)$this->years);
	 
	echo JHTML::_('select.genericlist',  $years, 'year', ' onchange=submit(); ', 'id', 'title', $this->selectedYear);?>
	
    <?php
endif;
if ($this->params->get('display_filter_einsatzarten','1')) : 
	$einsatzarten[] = JHTML::_('select.option', '', JTEXT::_('alle Einsatzarten')  , 'title', 'title');
	$einsatzarten = array_merge($einsatzarten, (array)$this->einsatzarten);
	?><?php 
	echo JHTML::_('select.genericlist',  $einsatzarten, 'selectedEinsatzart', ' onchange=submit(); ', 'title', 'title', $this->selectedEinsatzart);?>
    <?php
	endif;
	if (!$this->params->get('abfragewehr','0') and $this->params->get('display_filter_organisationen','1')) : 
	$organisationen[] = JHTML::_('select.option', '', JTEXT::_('alle Organisationen')  , 'name', 'name');
	$organisationen = array_merge($organisationen, (array)$this->organisationen);
	?><?php 
	echo JHTML::_('select.genericlist',  $organisationen, 'selectedOrga', ' onchange=submit(); ', 'name', 'name', $this->selectedOrga);
	endif;?>
	</form>
</div>
<?php // Filter ENDE   -------------------------------------------------------------------------------
 
?>


<?php 
$show = false;	
$first = ''; 
if ($this->params->get('display_home_pagination')) :
     $i=$this->pagination->total - $this->pagination->limitstart+1;
	 else:
     $i=count($this->reports)+1;
	 endif;
	 $m = ''; ?>
     
<div class="klappLayer" style ="margin-left:10px;padding-left:10px;">

	 <?php $hide=0;?>

     <?php foreach ($this->reports as $item) :
		   $i--; //print_r ($item);
		   $curTime = strtotime($item->date1); 
		   if (count($this->reports) == $i) :
		   $first = 'first'; 
		   endif;
		   ?>
          <!-- Filter State-->
		  <?php if($item->state == '1'): ?>
           
        <div id="bericht_<?php echo $item->id;?>" class="eintragHeadline <?php echo $first;?>">
        <span><?php echo date('d.m.Y ', $curTime).'&nbsp;' . date('H:i ', $curTime). ' - '.$item->data1;?></span>
        </div>
        <div class="eintragContent">
           <?php echo $item->summary;?>
        </div>
        
        <?php endif;?><!-- Filter State-->
		<?php if($item->state == '2'): ?>
        <?php $hide++;$i--;?>
        <?php endif;?> 
           
     <?php endforeach; ?>

</div>
