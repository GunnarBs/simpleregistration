<?php defined('_JEXEC') or die('Restricted access');

/**
* @version		$Id: simpleregistration.php 2011-09-04 12:40:00Z zanardi $
* @package		Email Registration
* @copyright	Copyright (C) 2011 GiBiLogic. All rights reserved.
* @license		GNU/GPL
*/

jimport('joomla.application.component.helper');
require_once(JPATH_COMPONENT.DS.'controller.php');
$controller = new SimpleregistrationController();
$controller->execute(JRequest::getVar('task', null, 'default', 'cmd'));
$controller->redirect();
