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
class com_einsatzkomponenteInstallerScript {
	public function install($parent) {
		
		// $parent is the class calling this method
		$parent->getParent()->setRedirectURL('index.php?option=com_einsatzkomponente&view=installation');
	}
	public function uninstall($parent) {
   }
										
	public function update($parent) {
		// $parent is the class calling this method 
		$parent->getParent()->setRedirectURL('index.php?option=com_einsatzkomponente&view=installation');
		
   }
	public function preflight($type, $parent) {
   }
	public function postflight($type, $parent) {
   }
												
												
												
}