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


JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$user = JFactory::getUser();
$userId = $user->get('id');
$canCreate = $user->authorise('core.create', 'com_einsatzkomponente');
$canEdit = $user->authorise('core.edit', 'com_einsatzkomponente');
$canCheckin = $user->authorise('core.manage', 'com_einsatzkomponente');
$canChange = $user->authorise('core.edit.state', 'com_einsatzkomponente');
$canDelete = $user->authorise('core.delete', 'com_einsatzkomponente');


require_once JPATH_SITE.'/components/com_einsatzkomponente/views/einsatzberichte/tmpl/'.$this->params->get('main_layout','main_layout_1.php').''; 

?> 