<?php

/**
 * @version		  frontend/controller.php 2014-01-26 20:06:00 UTC zanardi
 * @package		  GiBi SimpleRegistration
 * @author      GiBiLogic <info@gibilogic.com>
 * @authorUrl   http://www.gibilogic.com
 * @copyright	  Copyright (C) 2011-2014 GiBiLogic. All rights reserved.
 * @license		  GNU/GPL v3 or later
 */
defined('_JEXEC') or die();
jimport('joomla.application.component.controller');
jimport('joomla.application.component.helper');
jimport('joomla.html.parameter');

class SimpleregistrationController extends JControllerLegacy
{

    public function __construct($config = array())
    {
        parent::__construct($config);
        $this->app = JFactory::getApplication();
        $this->db = JFactory::getDbo();
        $this->params = JComponentHelper::getParams('com_simpleregistration');
    }

    /**
     * If user is guest, we show the registration form, else we redirect to the
     * configured URL
     *
     * @param bool $cachable
     * @param bool $urlparams
     */
    public function display($cachable = false, $urlparams = false)
    {
        $user = JFactory::getUser();

        if ($user->guest)
        {
            if (!$this->app->input->get('view'))
            {
                $this->app->input->set('view', 'registrationform');
            }
        }
        else
        {
            $redirectURL = $this->getMenuLink($params->get('redirect', 1));
            $this->app->redirect(JRoute::_($redirectURL, false));
        }

        parent::display(false);
    }

    public function save()
    {
        jimport('joomla.filter.filterinput');
        $filter = JFilterInput::getInstance();
        $email = $filter->clean($this->app->input->get('email',''));

        $item = $this->app->getMenu()->getItems('component', 'com_simpleregistration');
        $redirectURL = $item[0]->link . "&Itemid=" . $item[0]->id;

        if (empty($email))
        {
            $message = JText::_('COM_SIMPLEREGISTRATION_EMAIL_EMPTY');
            $this->app->redirect(JRoute::_($redirectURL, false), $message);
            return false;
        }

        jimport('joomla.mail.helper');
        if (!JMailHelper::isEmailAddress($email))
        {
            $message = JText::_('COM_SIMPLEREGISTRATION_EMAIL_INVALID');
            $this->app->redirect(JRoute::_($redirectURL, false), $message);
            return false;
        }

        $query = 'SELECT COUNT(*) FROM #__users WHERE email = ' . $this->db->quote($email);
        $this->db->setQuery($query);
        if ($this->db->loadResult() > 0)
        {
            $message = JText::_('COM_SIMPLEREGISTRATION_EMAIL_EXIST');
            $this->app->redirect(JRoute::_($redirectURL, false), $message);
            return false;
        }

        jimport('joomla.user.helper');

        $lang = JFactory::getLanguage();
        $lang->load('com_user');
        $lang->load('com_users');

        $user = JFactory::getUser(0);
        $usersParams = JComponentHelper::getParams('com_users');
        $usertype = $usersParams->get('new_usertype');

        $data = array();

        $data['email'] = $email;
        $data['email1'] = $email;
        $data['gid'] = $usertype;
        $data['sendEmail'] = 0;

        if ($this->params->get('extractusername', 0) == 1)
        {
            $data['username'] = $this->extractUsername($email);
        }
        else
        {
            $data['username'] = $email;
        }

        $data['name'] = $data['username'];

        if ($this->params->get('requestpassword', 0) == 0)
        {
            $password = JUserHelper::genRandomPassword();
        }
        else
        {
            $password = $filter->clean($this->app->input->get('password'));
        }

        $data['password'] = $password;
        $data['password1'] = $password;
        $data['password2'] = $password;

        require_once(JPATH_COMPONENT . '/models/registration.php');

        $model = new SimpleregistrationModelRegistration();
        $activation = $model->register($data);

        switch ($activation)
        {
            case 'useractivate':
                $message = JText::_('COM_USERS_REGISTRATION_COMPLETE_ACTIVATE');
                break;
            case 'adminactivate':
                $message = JText::_('COM_USERS_REGISTRATION_COMPLETE_VERIFY');
                break;
            default:
                $message = JText::_('COM_USERS_REGISTRATION_ACTIVATE_SUCCESS');
        }

        if ($this->params->get('autologin', 0) == 1)
        {
            $credentials = array("username" => $data['username'], "password" => $data['password']);
            $this->app->login($credentials);
        }

        $redirectURL = $this->getMenuLink($this->params->get('redirect', 1));
        $this->app->redirect(JRoute::_($redirectURL, false), $message);
    }

    /**
     * Get the full link for given Itemid
     *
     * @param int $Itemid
     * @return type
     */
    private function getMenuLink($Itemid)
    {
        $query = 'SELECT menutype, link FROM #__menu WHERE id = ' . $this->db->quote($Itemid) . ' AND published = 1';
        $this->db->setQuery($query);
        $menu = $this->db->loadObject();
        if (!empty($menu))
        {
            $url = $menu->link . "&Itemid=" . $Itemid;
        }
        else
        {
            $url = JURI::root();
        }

        return $url;
    }

    /**
     * Extract the username as the first part of the email (before @), adding
     * an incremental number if that username already exists
     *
     * @param string $email
     * @return string
     */
    private function extractUsername($email)
    {
        $earray = explode('@', $email);
        $username = $earray[0];
        $query = 'SELECT COUNT(*) FROM #__users WHERE username = ' . $this->db->quote($username);
        $this->db->setQuery($query);
        $result = $this->db->loadResult();

        $increment = 1;
        while ($result > 0)
        {
            $username = $earray[0] . $increment;
            $query = 'SELECT COUNT(*) FROM #__users WHERE username = ' . $this->db->quote($username);
            $this->db->setQuery($query);
            $result = $this->db->loadResult();
            $increment++;
        }

        return $username;
    }

}
