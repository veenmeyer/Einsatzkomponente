<?php
/**
 * @version     3.0.0
 * @package     com_einsatzkomponente
 * @copyright   Copyright (C) 2013 by Ralf Meyer. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ralf Meyer <webmaster@feuerwehr-veenhusen.de> - http://einsatzkomponente.de
 */
defined('JPATH_BASE') or die;
jimport('joomla.form.formfield');
/**
 * Supports an HTML select list of categories
 */
class JFormFieldoptgroup extends JFormField
{
        /**
         * The form field type.
         *
         * @var         string
         * @since       1.6
         */
        protected $type = 'optgroup';
        /**
         * Method to get the field input markup.
         *
         * @return      string  The field input markup.
         * @since       1.6
         */
        protected function getInput()
        {
                // Initialize variables.
                $html = array();
                $db = JFactory::getDBO();
                $query = 'SELECT id,name from #__eiko_organisationen where state=1 order by ordering ASC';
                $db->setQuery($query);
                $orgs = $db->loadObjectList();
                $html[] .= '<select id="'.$this->id.'" name="'.$this->name.'[]" multiple>';
                $html[] .= '<option>&nbsp;</option>';
                foreach ($orgs as $org) {
                        $html[].='<optgroup label="'.$org->name.'">';
                        $query = 'SELECT id,name from #__eiko_fahrzeuge where department = "' . $org->name . '" and state = 1 order by ordering ASC';
                        $db->setQuery($query);
                        $vehicles = $db->loadObjectList();
                                foreach ($vehicles as $vehicle) {
                                        $html[].='<option value="'.$vehicle->id.'">' . $vehicle->name . '</option>';
                                }
                        $html[].='</optgroup>';
                }
                $html[].='</select>';
                return implode($html);
        }
}
