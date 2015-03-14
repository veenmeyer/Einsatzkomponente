<?php
/**
 * @version     3.0.0
 * @package     com_einsatzkomponente
 * @copyright   Copyright (C) 2013 by Ralf Meyer. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ralf Meyer <webmaster@feuerwehr-veenhusen.de> - http://einsatzkomponente.de
 */
defined('_JEXEC') or die;
jimport('joomla.application.component.modellist');
/**
 * Methods supporting a list of Einsatzkomponente records.
 */
class EinsatzkomponenteModeleinsatzberichte extends JModelList
{
    /**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     * @see        JController
     * @since    1.6
     */
    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                                'id', 'a.id',
                'article_id', 'a.article_id',
                'ordering', 'a.ordering',
                'data1', 'a.data1',
                'image', 'a.image',
                'address', 'a.address',
                'date1', 'a.date1',
                'date2', 'a.date2',
                'date3', 'a.date3',
                'summary', 'a.summary',
                'boss', 'a.boss',
                'boss2', 'a.boss2',
                'people', 'a.people',
                'department', 'a.department',
                'desc', 'a.desc',
                'alerting', 'a.alerting',
                'gmap_report_latitude', 'a.gmap_report_latitude',
                'gmap_report_longitude', 'a.gmap_report_longitude',
                'counter', 'a.counter',
                'gmap', 'a.gmap',
                'status_fb', 'a.status_fb',
				'presse_label', 'a.presse_label',
                'presse', 'a.presse',
				'presse2_label', 'a.presse2_label',
                'presse2', 'a.presse2',
                'presse3_label', 'a.presse3_label',
                'presse3', 'a.presse3',
                'updatedate', 'a.updatedate',
                'einsatzticker', 'a.einsatzticker',
                'notrufticker', 'a.notrufticker',
                'tickerkat', 'a.tickerkat',
                'auswahlorga', 'a.auswahlorga',
                'vehicles', 'a.vehicles',
                'status', 'a.status',
                'state', 'a.state',
                'created_by', 'a.created_by',
            );
        }
        parent::__construct($config);
    }
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');
		// Load the filter state.
		$search = $app->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);
		$published = $app->getUserStateFromRequest($this->context.'.filter.state', 'filter_published', '', 'string');
		$this->setState('filter.state', $published);
        
        
		//Filtering data1
		$this->setState('filter.data1', $app->getUserStateFromRequest($this->context.'.filter.data1', 'filter_data1', '', 'string'));
		//Filtering alerting
		$this->setState('filter.alerting', $app->getUserStateFromRequest($this->context.'.filter.alerting', 'filter_alerting', '', 'string'));
		//Filtering date1
		$this->setState('filter.date1.from', $app->getUserStateFromRequest($this->context.'.filter.date1.from', 'filter_date1_from', '', 'string'));
		$this->setState('filter.date1.to', $app->getUserStateFromRequest($this->context.'.filter.date1.to', 'filter_date1_to', '', 'string'));
		//Filtering auswahlorga
		$this->setState('filter.auswahlorga', $app->getUserStateFromRequest($this->context.'.filter.auswahlorga', 'filter_auswahlorga', '', 'string'));
		//Filtering tickerkat
		$this->setState('filter.tickerkat', $app->getUserStateFromRequest($this->context.'.filter.tickerkat', 'filter_tickerkat', '0', 'string'));
		//Filtering created_by
		$this->setState('filter.created_by', $app->getUserStateFromRequest($this->context.'.filter.created_by', 'filter_created_by', '', 'string'));
        
		// Load the parameters.
		$params = JComponentHelper::getParams('com_einsatzkomponente');
		$this->setState('params', $params);
		// List state information.
		parent::populateState('a.date1', 'DESC');
	}
	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param	string		$id	A prefix for the store id.
	 * @return	string		A store id.
	 * @since	1.6
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id.= ':' . $this->getState('filter.search');
		$id.= ':' . $this->getState('filter.state');
		return parent::getStoreId($id);
	}
	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return	JDatabaseQuery
	 * @since	1.6
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);
		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.*'
			)
		);
		$query->from('`#__eiko_einsatzberichte` AS a');
		// Join over the foreign key 'auswahlorga'
		$query->select('dep.name AS mission_orga');
		$query->select('dep.ordering AS department_ordering');
		$query->join('LEFT', '#__eiko_organisationen AS dep ON dep.name = a.auswahlorga');
		// Join over the foreign key 'tickerkat'
		$query->select('tic.title AS mission_kat');
		$query->select('tic.ordering AS kat_ordering');
		$query->join('LEFT', '#__eiko_tickerkat AS tic ON tic.id = a.tickerkat');
		// Join over the foreign key 'vehicles'
		$query->select('veh.name AS mission_car');
		$query->select('veh.ordering AS vehicle_ordering');
		$query->join('LEFT', '#__eiko_fahrzeuge AS veh ON veh.id = a.vehicles');
		// Join over the user field 'created_by'
		$query->select('created_by.name AS created_by');
		$query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');
    // Filter by published state
    $published = $this->getState('filter.state');
    if (is_numeric($published)) {
        $query->where('a.state = '.(int) $published);
    } else if ($published === '') {
        $query->where('(a.state IN (0, 1,2))');
    }
    
		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			} else {
				$search = $db->Quote('%'.$db->escape($search, true).'%');
                $query->where('( a.address LIKE '.$search.'  OR  a.summary LIKE '.$search.'  OR  a.boss LIKE '.$search.'  OR  a.boss2 LIKE '.$search.'  OR  a.desc LIKE '.$search.' OR  a.data1 LIKE '.$search.' OR a.id LIKE '.$search.' )'); 
			}
		}
        
		//Filtering data1
		//Filtering alerting
		//Filtering date1
		$filter_date1_from = $this->state->get("filter.date1.from");
		if ($filter_date1_from) {
			$query->where("a.date1 >= '".$db->escape($filter_date1_from)."'");
		}
		$filter_date1_to = $this->state->get("filter.date1.to");
		if ($filter_date1_to) {
			$query->where("a.date1 <= '".$db->escape($filter_date1_to)."'");
		}
		//Filtering auswahlorga
		$filter_auswahlorga = $this->state->get("filter.auswahlorga");
		if ($filter_auswahlorga == 'Organisation auswählen') {
			$query->where("a.auswahlorga LIKE '%'");
		}
		if ($filter_auswahlorga != 'Organisation auswählen') {
			$query->where("a.auswahlorga LIKE '%".$db->escape($filter_auswahlorga)."%'");
		}
		
		$filter_tickerkat = $this->state->get("filter.tickerkat");
		if ($filter_tickerkat == '0') {
			$query->where("a.tickerkat LIKE '%'");
		}
		if ($filter_tickerkat != '0') {
			$query->where("a.tickerkat LIKE '".$db->escape($filter_tickerkat)."'");
		}
		//Filtering created_by
		$filter_created_by = $this->state->get("filter.created_by");
		if ($filter_created_by) {
			$query->where("a.created_by = '".$db->escape($filter_created_by)."'");
		}        
        
        
		// Add the list ordering clause.
        $orderCol	= $this->state->get('list.ordering');
        $orderDirn	= $this->state->get('list.direction');
        if ($orderCol && $orderDirn) {
            $query->order($db->escape($orderCol.' '.$orderDirn));
        }
		return $query;
	}
    // Daten zum Senden bereit stellen -----------------------------------------------------------------
	public function getSend_Data($id = null)  
	{
					$jinput = JFactory::getApplication()->input; 
                 	$id = $jinput->get('tickerID1', '', 'INT') ?: $jinput->get('tickerID2', '', 'INT');

		if ($this->_item === null)
		{
			$this->_item = false;
			// Get a level row instance.
			$table = $this->getTable();
			// Attempt to load the row.
			if ($table->load($id))
			{
				
				// Convert the JTable to a clean JObject.
				$properties = $table->getProperties(1);
				$this->_item = JArrayHelper::toObject($properties, 'JObject');
			} elseif ($error = $table->getError()) {
				$this->setError($error);
			}
		}
		return $this->_item;
	}
    
	public function getTable($type = 'Einsatzbericht', $prefix = 'EinsatzkomponenteTable', $config = array())
	{   
        //$this->addTablePath(JPATH_COMPONENT_ADMINISTRATOR.'/tables');
        return JTable::getInstance($type, $prefix, $config);
	}     
    // Daten zum Senden bereit stellen ENDE   -----------------------------------------------------------
	
	
}
