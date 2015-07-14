<?php
/**
 * @version     3.0.7
 * @package     com_einsatzkomponente
 * @copyright   Copyright (C) 2015. Alle Rechte vorbehalten.
 * @license     GNU General Public License Version 2 oder später; siehe LICENSE.txt
 * @author      Ralf Meyer <ralf.meyer@mail.de> - http://einsatzkomponente.de
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Ausruestung controller class.
 */
class EinsatzkomponenteControllerAusruestung extends JControllerForm
{

    function __construct() {
        $this->view_list = 'ausruestungen';
        parent::__construct();
    }

}