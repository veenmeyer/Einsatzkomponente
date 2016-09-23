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
/**
 * @param	array	A named array
 * @return	array
 */
function EinsatzkomponenteBuildRoute(&$query)
{
	$segments = array();
    
	if (isset($query['view'])) {
		$segments[] = implode('/',explode('.',$query['view']));
		unset($query['view']);
	}
	
	if (isset($query['task'])) {
		$segments[] = implode('/',explode('.',$query['task']));
		unset($query['task']);
	}

	if (isset($query['id'])) {
		$segments[] = $query['id'];
		unset($query['id']);
	}
	return $segments;
}
/**
 * @param	array	A named array
 * @param	array
 *
 * Formats:
 *
 * index.php?/einsatzkomponente/task/id/Itemid
 *
 * index.php?/einsatzkomponente/id/Itemid
 */
function EinsatzkomponenteParseRoute($segments)
{
	$vars = array();
    
	// view is always the first element of the array
	$count = count($segments);//print_r ($segments);exit;
    
	
    if ($count=='2')
	{
		$count--;
		$segment = array_pop($segments) ; 
		if (is_numeric($segment)) {
			$vars['id'] = $segment;
		}
        else{
            $count--;
            $vars['view'] = array_pop($segments) . '.' . $segment;
            $vars['task'] = array_pop($segments) . '.' . $segment;
        }
	}
	
	
	
	if ($count)
	{   
        $vars['view'] = implode('.',$segments);
        $vars['task'] = implode('.',$segments);
	}
	return $vars;
}
