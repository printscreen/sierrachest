<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initDefines()
    {
        defined('SIERRA_DB') || define('SIERRA_DB', 'Sierra_Database');
        defined('TOKEN') || define('TOKEN', 'User_Token');
        defined('SALT') || define('SALT', 'With_my_last_breath_i_curse_Zoidberg!');
        defined('SESSION') || define('SESSION', 'Sierra_Session');
        defined('SYSTEM_NAME') || define('SYSTEM_NAME', 'System_Name');
        defined('SYSTEM_EMAIL_ADDRESS') || define('SYSTEM_EMAIL_ADDRESS', 'System_Email_Address');
        defined('SYSTEM_MAILER') || define('SYSTEM_MAILER', 'System_Emailer_Object');
                defined('APPLICATION_URL') || define('APPLICATION_URL', 'Application_Url');
    }

    protected function _initAutoload()
    {
        $autoLoader = Zend_Loader_Autoloader::getInstance();
        $autoLoader->registerNamespace('Sierra_');
        $resourceLoader = new Zend_Loader_Autoloader_Resource(array(
            'basePath' => APPLICATION_PATH,
            'namespace' => '',
            'resourceTypes' => array(
                'form' => array(
                    'path' => '/modules/default/views/forms/',
                    'namespace' => 'Form_'
                ),
                'model' => array(
                    'path' => '/models/',
                    'namespace' => 'Model_'
                )
            )
        ));
    }

    protected function _initApplication()
    {
        date_default_timezone_set($this->getOption('default_time_zone'));
        Zend_Registry::set(SYSTEM_NAME, $this->getOption('application_name'));
        Zend_Registry::set(APPLICATION_URL, $this->getOption('application_url'));
    }

    protected function _initDb()
    {
        $db = $this->getPluginResource('db')->getDbAdapter();
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        Zend_Registry::set(SIERRA_DB, $db);
    }

    protected function _initPlugins()
    {
        $frontController = Zend_Controller_Front::getInstance();
        $frontController->registerPlugin(new Sierra_Controller_Plugin_Acl());
        $frontController->registerPlugin(new Sierra_Controller_Plugin_Init());
    }

    protected function _initSession()
    {
        $session = new Zend_Session_Namespace(SESSION);
        Zend_Registry::set(SESSION, $session);
    }

    protected function _initSpecialRoutes()
    {
        $router = Zend_Controller_Front::getInstance()->getRouter();
        $route = new Zend_Controller_Router_Route(
            'game/:slug',
            array(
                'module'     => 'default',
                'controller' => 'game',
                'action'     => 'game'
            ),
            array('slug' => '[a-z0-9\-]+')
        );
        $router->addRoute('author', $route);
    }

    protected function _initMailTransport()
    {
        $options = $this->getOption('mail');
        Zend_Registry::set(SYSTEM_EMAIL_ADDRESS, $options['system_address']);
        $mailer = new Zend_Mail_Transport_Smtp($options['server'], array(
                'ssl' => 'ssl',
                'port' => 465,
                'auth' => 'login',
                'username' => $options['user_name'],
                'password' => $options['password']
            )
        );
        Zend_Registry::set(SYSTEM_MAILER,$mailer);
    }
}