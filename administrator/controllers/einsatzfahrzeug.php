<?php
/**
 * @version     3.0.0
 * @package     com_einsatzkomponente
 * @copyright   Copyright (C) by Ralf Meyer 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ralf Meyer <webmaster@feuerwehr-veenhusen.de> - http://einsatzkomponente.de
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Einsatzfahrzeug controller class.
 */
class EinsatzkomponenteControllerEinsatzfahrzeug extends JControllerForm
{

    function __construct() {
        $this->view_list = 'einsatzfahrzeuge';
        parent::__construct();
    }

}