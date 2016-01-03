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


?>

<!--Page Heading-->
<?php if ($this->params->get('show_page_heading', 1)) : ?>
<div class="page-header eiko_header_main">
<h1 class="eiko_header_main_h1"> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1> 
<input type="button" class="btn eiko_back_button" value="Zurück" onClick="history.back();">
</div>
<br/>
<?php endif;?>

<?php
require_once JPATH_SITE.'/components/com_einsatzkomponente/views/einsatzfahrzeug/tmpl/'.$this->params->get('fahrzeuge_detail_layout','fahrzeug_layout_1.php').''; 


?> 