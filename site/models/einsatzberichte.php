<?php
/**
 * @version     3.15.0
 * @package     com_einsatzkomponente
 * @copyright   Copyright (C) 2017 by Ralf Meyer. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ralf Meyer <ralf.meyer@mail.de> - https://einsatzkomponente.de
 */
defined('_JEXEC') or die;
jimport('joomla.application.component.modellist');
/**
 * Methods supporting a list of Einsatzkomponente records.
 */
class EinsatzkomponenteModelEinsatzberichte extends JModelList {
    /**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     * @see        JController
     * @since    1.6
     */
    public function __construct($config = array()) {
        parent::__construct($config);
    }
    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @since	1.6
     */
    protected function populateState($ordering = null, $direction = null)
    {


        // Initialise variables.
        $app = JFactory::getApplication();
		$params = $app->getParams('com_einsatzkomponente');
		$page_limit = $params->get('display_home_pagination_limit','5');
		if (!$page_limit) : $page_limit = $app->getCfg('list_limit'); endif;

        // List state information
        $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $page_limit);
        $this->setState('list.limit', $limit);
		
if ($limit != '0') :   
        $limitstart = $app->input->getInt('limitstart', 0);
        $this->setState('list.start', $limitstart);
else:  	$limitstart = '0';
		$this->setState('list.start', $limitstart);
endif;

//echo 'limit:'.$limit.'<br/>limitstart:'.$limitstart;
        if ($list = $app->getUserStateFromRequest($this->context . '.list', 'list', array(), 'array'))
        {
            foreach ($list as $name => $value)
            {
                // Extra validations
                switch ($name)
                {
                    case 'fullordering':
                        $orderingParts = explode(' ', $value);

                        if (count($orderingParts) >= 2)
                        {
                            // Latest part will be considered the direction
                            $fullDirection = end($orderingParts);

                            if (in_array(strtoupper($fullDirection), array('ASC', 'DESC', '')))
                            {
                                $this->setState('list.direction', $fullDirection);
                            }

                            unset($orderingParts[count($orderingParts) - 1]);

                            // The rest will be the ordering
                            $fullOrdering = implode(' ', $orderingParts);

                            if (in_array($fullOrdering, $this->filter_fields))
                            {
                                $this->setState('list.ordering', $fullOrdering);
                            }
                        }
                        else
                        {
                            $this->setState('list.ordering', $ordering);
                            $this->setState('list.direction', $direction);
                        }
                        break;

                    case 'ordering':
                        if (!in_array($value, $this->filter_fields))
                        {
                            $value = $ordering;
                        }
                        break;

                    case 'direction':
                        if (!in_array(strtoupper($value), array('ASC', 'DESC', '')))
                        {
                            $value = $direction;
                        }
                        break;

                    case 'limit':
                        $limit = $value;
                        break;

                    // Just to keep the default case
                    default:
                        $value = $value;
                        break;
                }

                $this->setState('list.' . $name, $value);
            }
        }

        // Receive & set filters
        if ($filters = $app->getUserStateFromRequest($this->context . '.filter', 'filter', array(), 'array'))
        {
            foreach ($filters as $name => $value)
            {
                $this->setState('filter.' . $name, $value);
            }
        }

        $this->setState('list.ordering', $app->input->get('filter_order'));
        $this->setState('list.direction', $app->input->get('filter_order_Dir'));
    }
    /**
     * Build an SQL query to load the list data.
     *
     * @return	JDatabaseQuery
     * @since	1.6
     */
    protected function getListQuery() {
        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        // Select the required fields from the table.
        $query->select(
                $this->getState(
                        'list.select', 'a.*'
                )
        );
        
        $query->from('#__eiko_einsatzberichte AS a');
        
		// Join over the foreign key 'auswahl_orga'
		$query->select('dep.id AS mission_orga');
		$query->select('dep.ordering AS department_ordering');
		$query->join('LEFT', '#__eiko_organisationen AS dep ON dep.id = a.auswahl_orga');
		// Join over the foreign key 'tickerkat'
		$query->select('tic.title AS mission_kat');
		$query->select('tic.ordering AS kat_ordering');
		$query->join('LEFT', '#__eiko_tickerkat AS tic ON tic.id = a.tickerkat');
		// Join over the foreign key 'vehicles'
		$query->select('veh.name AS mission_car');
		$query->select('veh.ordering AS vehicle_ordering');
		$query->join('LEFT', '#__eiko_fahrzeuge AS veh ON veh.id = a.vehicles');
		// Join over the foreign key 'ausruestung'
		$query->select('#__eiko_ausruestung_1662678.name AS ausruestung_name_1662678');
		$query->join('LEFT', '#__eiko_ausruestung AS #__eiko_ausruestung_1662678 ON #__eiko_ausruestung_1662678.id = a.ausruestung');
		// Join over the user field 'created_by'
		$query->select('created_by.name AS created_by');
		$query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');
		// Join over the user field 'modified_by'
		$query->select('modified_by.name AS modified_by');
		$query->join('LEFT', '#__users AS modified_by ON modified_by.id = a.modified_by');
		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			} else {
				$search = $db->Quote('%'.$db->escape($search, true).'%');
                $query->where('( a.address LIKE '.$search.'  OR  a.summary LIKE '.$search.' OR  a.ausruestung LIKE '.$search.' OR  a.boss LIKE '.$search.'  OR  a.boss2 LIKE '.$search.'  OR  a.desc LIKE '.$search.' )');
			}
		}
        
		//Filtering data1
		//Filtering alerting
		
		//Filtering updatedate
		$filter_updatedate_from = $this->state->get("filter.updatedate.from");
		if ($filter_updatedate_from) {
			$query->where("a.updatedate >= '".$filter_updatedate_from."'");
		}
		$filter_updatedate_to = $this->state->get("filter.updatedate.to");
		if ($filter_updatedate_to) {
			$query->where("a.updatedate <= '".$filter_updatedate_to."'");
		}
		
		//Filtering createdate
		$filter_createdate_from = $this->state->get("filter.createdate.from");
		if ($filter_createdate_from) {
			$query->where("a.createdate >= '".$filter_createdate_from."'");
		}
		$filter_createdate_to = $this->state->get("filter.createdate.to");
		if ($filter_createdate_to) {
			$query->where("a.createdate <= '".$filter_createdate_to."'");
		}

		//Filtering auswahl_orga
		$filter_auswahl_orga = $this->state->get("filter.auswahl_orga");
		if ($filter_auswahl_orga) {
			$query->where("a.auswahl_orga = '".$filter_auswahl_orga."'");
		}
		//Filtering ausruestung
		$filter_ausruestung = $this->state->get("filter.ausruestung");
		if ($filter_ausruestung) {
			$query->where("a.ausruestung = '".$filter_ausruestung."'");
		}
		//Filtering created_by
		$filter_created_by = $this->state->get("filter.created_by");
		if ($filter_created_by) {
			$query->where("a.created_by = '".$filter_created_by."'");
		}      
		
		//Filtering modified_by
		$filter_modified_by = $this->state->get("filter.modified_by");
		if ($filter_modified_by) {
			$query->where("a.modified_by = '".$filter_modified_by."'");
		}        

        return $query;
    }
}
