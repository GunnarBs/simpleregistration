<?php defined('_JEXEC') or die('The way is shut'); 
/**
* @version		$Id: views/simpleregistration/tmpl/default.php 2012-04-15 07:14:00Z zanardi $
* @package		Email Registration
* @copyright	Copyright (C) 2011 GiBiLogic. All rights reserved.
* @license		GNU/GPL
*/
JToolBarHelper::title( JText::_( 'SIMPLEREGISTRATION' ), 'generic.png' );
JToolBarHelper::preferences( 'com_simpleregistration' );
?>
<p><?php echo JText::_('SIMPLEREGISTRATION_USAGE') ?></p>
