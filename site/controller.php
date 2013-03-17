<?php defined('_JEXEC') or die( 'The way is shut' );
/**
 * @version		  $Id: controller.php 2012-10-09 19:41:00Z zanardi $
 * @package		  GiBi SimpleRegistration
 * @author      GiBiLogic
 * @authorEmail info@gibilogic.com
 * @authorUrl   http://www.gibilogic.com
 * @copyright	  Copyright (C) 2011-2012 GiBiLogic. All rights reserved.
 * @license		  GNU/GPL v2 or later
 */

jimport('joomla.application.component.controller');
jimport('joomla.html.parameter' );

class SimpleregistrationController extends JController
{
	// show registration form
	function display()
	{
		if ( ! JRequest::getCmd( 'view' ) ) {
			JRequest::setVar('view', 'registrationform' );
		}
		parent::display(false);
	}
	
	// create new user
	function save()
	{
		jimport('joomla.application.component.helper');
    $params =& JComponentHelper::getParams( 'com_simpleregistration' );
    
		jimport('joomla.filter.filterinput');
		jimport('joomla.mail.helper');
		jimport('joomla.user.helper');		
		$lang =& JFactory::getLanguage();
		$lang->load('com_user');
		$lang->load('com_users');
		
		$filter =& JFilterInput::getInstance();
		$email = $filter->clean( JRequest::getVar( 'email' ) );

		if(! JMailHelper::isEmailAddress( $email ) ){
			JError::raiseWarning('', JText::_( 'SIMPLEREGISTRATION_EMAIL_NOT_VALID'));
			return false;
		}
		
		$acl =& JFactory::getACL();
		$user = JFactory::getUser(0);

		$usersParams = &JComponentHelper::getParams( 'com_users' );
		$usertype = $usersParams->get('new_usertype');

		$data = array();

		$data['name'] 		= $email;
		$data['email'] 		= $email;
		$data['email1'] 	= $email;
		
		$jversion = new JVersion;
		if( $jversion->isCompatible( '1.6' ) ) {
			$data['gid']	= $usertype;
		} else {
			$data['gid'] 	= $acl->get_group_id( '', $usertype, 'ARO' );
		}
		$data['sendEmail'] 	= 0;
		$data['username'] 	= $email;
    
    if( $params->get('requestpassword',0 ) == 0 ) {
      $password = JUserHelper::genRandomPassword();
    } else {
      $password = $filter->clean( JRequest::getVar( 'password' ) );
    }
		$data['password'] 	= $password;
		$data['password1'] 	= $password;
		$data['password2'] 	= $password;

		if( $jversion->isCompatible( '1.6' ) ) {
			
			// Joomla 2.5 - core method takes care of everything  
			require_once( JPATH_SITE.DS.'components'.DS.'com_users'.DS.'models'.DS.'registration.php' ); 
			$model = new UsersModelRegistration();
			$activation = $model->register( $data );
			
			switch( $activation ) {
				case 'useractivate':
					$message = JText::_('COM_USERS_REGISTRATION_COMPLETE_ACTIVATE');
					break;
				case 'adminactivate':
					$message = JText::_('COM_USERS_REGISTRATION_COMPLETE_VERIFY');
					break;
				default:
					$message = JText::_('COM_USERS_REGISTRATION_ACTIVATE_SUCCESS');
			}
		
		} else {
			
			$data['block'] 		= 1;
			$data['activation']	= JUtility::getHash( JUserHelper::genRandomPassword());
			
			// Joomla 1.5
			if (!$user->bind( $data )) {
				JError::raiseWarning('', JText::_( $user->getError()));
				return false;
			}

			if (!$user->save()) {
				JError::raiseWarning('', JText::_( $user->getError()));
				return false;
			}
		
			require_once( JPATH_SITE.DS.'components'.DS.'com_user'.DS.'controller.php' );
			UserController::_sendMail($user, $password);
			$message  = JText::_( 'REG_COMPLETE_ACTIVATE' );
		}
		
		$url = JRoute::_( 'index.php' );
		JController::setRedirect( $url, $message);
	}



}
