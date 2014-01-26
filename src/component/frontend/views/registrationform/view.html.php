<?php
/**
* @version		  frontend/views/registrationform/view.html.php 2014-01-26 20:14:00 UTC zanardi
* @package		  GiBi SimpleRegistration
* @author       GiBiLogic <info@gibilogic.com>
* @authorUrl    http://www.gibilogic.com
* @copyright	  Copyright (C) 2011-2014 GiBiLogic snc. All rights reserved.
* @license		  GNU/GPL v3 or later
*/
defined('_JEXEC') or die();
jimport('joomla.application.component.view');
jimport('joomla.application.component.helper');

class SimpleregistrationViewRegistrationform extends JViewLegacy
{

    protected $_url;

    public $params;

    public function __construct($config = array())
    {
        parent::__construct($config);
        $this->_url = 'index.php?option=com_simpleregistration&view=registrationform';
    }

    function display($tpl = null)
    {
        $app = JFactory::getApplication();
        $pathway = $app->getPathway();
        $params = $app->getParams();
        $document = JFactory::getDocument();
        $user = JFactory::getUser();

        $params->set('page_title', JText::_('COM_SIMPLEREGISTRATION_TITLE'));

        $jsite = new JSite();
        $menus = $jsite->getMenu();
        $menu = $menus->getActive();

        if (is_object($menu))
        {
            $menu_params = new JRegistry($menu->params);
            if ($menu_params->get('page_title'))
            {
                $params->set('page_title', $menu_params->get('page_title'));
            }
        }

        $document->setTitle($params->get('page_title'));
        $pathway->addItem(JText::_('New'), '');

        $comparams = JComponentHelper::getParams('com_simpleregistration');
        if ($comparams->get('mootools'))
        {
            JHTML::_('behavior.formvalidation');
        }

        $this->assignRef('params', $params);
        parent::display($tpl);
    }

}
