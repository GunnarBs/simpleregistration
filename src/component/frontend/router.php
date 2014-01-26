<?php
/**
 * @version		  router.php 2013-08-29 20:25:00 UTC monkeyman
 * @package		  GiBi SimpleRegistration
 * @author      GiBiLogic <info@gibilogic.com>
 * @authorUrl   http://www.gibilogic.com
 * @copyright	  Copyright (C) 2011-2014 GiBiLogic. All rights reserved.
 * @license		  GNU/GPL v3 or later
 */
defined('_JEXEC') or die();

function SimpleRegistrationBuildRoute(&$query)
{
    static $items;

    $segments = array();
    $itemid = null;

    $app = JFactory::getApplication();
    $items = $app->getMenu()->getItems('component', 'com_simpleregistration');

    if (is_array($items))
    {
        if (isset($query['Itemid']))
            $itemid = (int) $query['Itemid'];

        if (!$itemid)
        {
            foreach ($items as $item)
            {
                if (isset($query['view']) && ($query['view'] == 'registrationform'))
                {
                    if (isset($query['id']))
                    {
                        $itemid = $item->id;
                        $segments[] = isset($query['alias']) ? $query['id'] . ':' . $query['alias'] : $query['id'];
                        break;
                    }
                }
            }
        }
    }

    if (isset($query['view']))
    {
        if (empty($query['Itemid']))
        {
            $segments[] = $query['view'];
        }
        else
        {
            $menu = $app->getMenu();
            $menuItem = $menu->getItem($query['Itemid']);

            if (!isset($menuItem->query['view']) || ($menuItem->query['view'] != $query['view']))
            {
                $segments[] = $query['view'];
            }
        }

        unset($query['view']);
    }

    return $segments;
}

function SimpleRegistrationParseRoute($segments)
{
    $vars = array();

    $menu = new JSite();
    $item = $menu->getMenu()->getActive();

    if (!isset($item))
        $vars['view'] = $segments[0];

    return $vars;
}
