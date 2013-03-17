<?php defined('_JEXEC') or die( 'The way is shut' );
/**
* @version		  $Id: views/registrationform/view.html.php 2012-10-09 19:41:00Z zanardi $
* @package		  GiBi SimpleRegistration
* @author       GiBiLogic 
* @authorEmail  info@gibilogic.com
* @authorUrl    http://www.gibilogic.com
* @copyright	  Copyright (C) 2011-2012 GiBiLogic snc. All rights reserved.
* @license		  GNU/GPL v2 or later

*/

jimport( 'joomla.application.component.view');
jimport( 'joomla.application.component.helper');

class SimpleregistrationViewRegistrationform extends JView
{
	function display($tpl = null)
	{
		$app =& JFactory::getApplication();
		$pathway	=& $app->getPathway();
		$params 	=& $app->getParams();
		$document	=& JFactory::getDocument();
		$user		=& JFactory::getUser();
		
		if ( $user->guest ) {
			$params->set('page_title', JText::_('REGISTRATION_FORM_TITLE') );
		} else {			
			$params->set('page_title', JText::_('ALREADY_REGISTERED_FORM_TITLE') );
		}
		
		$menus	= &JSite::getMenu();
		$menu	= $menus->getActive();
		if( is_object( $menu ) ) {
			$menu_params = new JParameter( $menu->params );
			if( $menu_params->get( 'page_title') ) {
				$params->set('page_title', $menu_params->get( 'page_title') );
			}
		}

		$document->setTitle( $params->get( 'page_title' ) );
		$document->addScript( DS . 'includes' . DS . 'js' . DS . 'joomla.javascript.js' );
		$pathway->addItem(JText::_('New'), '');
		
		JHTML::_('behavior.formvalidation');
		
		$this->assignRef('params', $params);
		parent::display($tpl);
	}
}
?>
