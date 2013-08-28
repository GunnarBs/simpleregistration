<?php

defined('_JEXEC') or die('The way is shut');
/**
 * @version		  $Id: controller.php 2013-03-17 15:25:00Z zanardi $
 * @package		  GiBi SimpleRegistration
 * @author      GiBiLogic
 * @authorEmail info@gibilogic.com
 * @authorUrl   http://www.gibilogic.com
 * @copyright	  Copyright (C) 2011-2013 GiBiLogic. All rights reserved.
 * @license		  GNU/GPL v2 or later
 */
jimport('joomla.application.component.controller');
jimport('joomla.html.parameter');

class SimpleregistrationController extends JControllerLegacy
{

	private $_url;

	function __construct($config=array())
	{
		$this->_url = 'index.php?option=com_simpleregistration&view=registrationform';
		parent::__construct($config);
	}

	// show registration form
	function display()
	{
		if (!JRequest::getCmd('view')) {
			JRequest::setVar('view', 'registrationform');
		}
		parent::display(false);
	}

	// create new user
	function save()
	{
    if (JRequest::getVar('return','')) {
        $this->_url = base64_decode(JRequest::getVar('return',''));
    }

		jimport('joomla.filter.filterinput');

		$app = &JFactory::getApplication();
		$db = & JFactory::getDBO();

		$filter = & JFilterInput::getInstance();
		$email = $filter->clean(JRequest::getVar('email'));

		// Check that e-mail is not already taken
		$query = 'SELECT COUNT(*) FROM #__users WHERE email = ' . $db->quote($email);
		$db->setQuery($query);
		if ($db->loadResult() > 0) {
			$message = "COM_SIMPLEREGISTRATION_EMAIL_EXIST";
			$type = "error";
			$this->setRedirect($this->_url, $message, $type);
			return false;
		}

		jimport('joomla.application.component.helper');
		$params = & JComponentHelper::getParams('com_simpleregistration');

		jimport('joomla.mail.helper');
		jimport('joomla.user.helper');

		$lang = & JFactory::getLanguage();
		$lang->load('com_user');
		$lang->load('com_users');

		if (!JMailHelper::isEmailAddress($email)) {
			JError::raiseWarning('', JText::_('SIMPLEREGISTRATION_EMAIL_NOT_VALID'));
			return false;
		}

		$user = JFactory::getUser(0);

		$usersParams = &JComponentHelper::getParams('com_users');
		$usertype = $usersParams->get('new_usertype');

		$data = array();

		$data['name'] = $email;
		$data['email'] = $email;
		$data['email1'] = $email;
		$data['gid'] = $usertype;
		$data['sendEmail'] = 0;

		if ($params->get('generateusername', 0) == 1) {
			$tmp_array = explode('@', $email);
			$data['username'] = $tmp_array[0];
		}
		else {
			$data['username'] = $email;
		}

		//TODO add an option that adds a number to the username if it already exists
		// i.e. zanardi1, zanardi2, and so on

		if ($params->get('requestpassword', 0) == 0) {
			$password = JUserHelper::genRandomPassword();
		}
		else {
			$password = $filter->clean(JRequest::getVar('password'));
		}
		$data['password'] = $password;
		$data['password1'] = $password;
		$data['password2'] = $password;

		require_once(JPATH_SITE . '/components/com_users/models/registration.php');
		$model = new UsersModelRegistration();
		$activation = $model->register($data);

		switch ($activation) {
			case 'useractivate':
				$message = JText::_('COM_USERS_REGISTRATION_COMPLETE_ACTIVATE');
				break;
			case 'adminactivate':
				$message = JText::_('COM_USERS_REGISTRATION_COMPLETE_VERIFY');
				break;
			default:
				$message = JText::_('COM_USERS_REGISTRATION_ACTIVATE_SUCCESS');
		}

		if ($params->get('autologin', 0) == 1) {
			$credentials = array("username" => $data['username'], "password" => $data['password']);
			$app->login($credentials);
		}

		//$url = JRoute::_('index.php');
		JController::setRedirect($this->_url, $message);
	}

}
