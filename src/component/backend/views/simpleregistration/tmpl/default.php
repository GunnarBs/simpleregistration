<?php
/**
 * @version		  backend/views/simpleregistration/tmpl/default.php 2014-01-26 19:58:00 UTC zanardi
* @package		  GiBi SimpleRegistration
* @author       GiBiLogic <info@gibilogic.com>
* @authorUrl    http://www.gibilogic.com
* @copyright	  Copyright (C) 2011-2014 GiBiLogic snc. All rights reserved.
* @license		  GNU/GPL v3 or later
*/
defined('_JEXEC') or die();

JToolBarHelper::title(JText::_('COM_SIMPLEREGISTRATION'), 'generic.png');
JToolBarHelper::preferences('com_simpleregistration' );
?>
<p><?php echo JText::_('COM_SIMPLEREGISTRATION_USAGE') ?></p>