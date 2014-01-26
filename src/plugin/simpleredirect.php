<?php
/**
* @version		  plugin/simpleredirect.php 2014-01-26 20:14:00 UTC zanardi
* @package		  Simple Registration Redirect
* @author       The Krotek <support@thekrotek.com>
* @authorUrl    http://thekrotek.com
* @license		  GNU/GPL v3 or later
*/
defined('_JEXEC') or die();

if (!class_exists('plgSystemSimpleRedirect'))
{

    class plgSystemSimpleRedirect extends JPlugin
    {

        public function __construct(&$subject, $config)
        {
            parent::__construct($subject, $config);
            $this->loadLanguage();
            $this->app = JFactory::getApplication();
        }

        /**
         * onAfterRoute event handler
         *
         * @return boolean
         */
        public function onAfterRoute()
        {
            if (!$this->app->isSite())
            {
                return true;
            }

            if (!$this->params->get('override', 1))
            {
                return true;
            }

            $options = array('com_user','com_users');
            if (in_array($this->app->input->get('option',''), $options))
            {
                $this->overrideRegistration();
            }
        }

        /**
         * Redirect the normal registration to SimpleRegistration
         *
         * @return boolean
         */
        private function overrideRegistration()
        {
            $views = array('register','registration');
            if (!in_array($this->app->input->get('view',''), $views))
            {
                return true;
            }

            $item = $this->app->getMenu()->getItems('component', 'com_simpleregistration');
            if ($item)
            {
                $redirectURL = $item[0]->link . "&Itemid=" . $item[0]->id;
            }
            else
            {
                $redirectURL = "index.php?option=com_simpleregistration&view=registrationform";
            }

            $this->app->redirect(JRoute::_($redirectURL, false));
        }

    }

}