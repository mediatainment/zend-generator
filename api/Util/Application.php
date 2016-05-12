<?php
class Generator_Api_Util_Application
{
    /**
     * Get the path to the requested module
     * @return string $path
     */
    public static function getActiveModuleDir ()
    {
        $front = Zend_controller_Front::getInstance();
        return $front->getModuleDirectory();
    }
    /**
     * Get the name of the requested module
     * 
     * @return string $modulename
     */
    public static function getActiveModuleName ()
    {
        $front = Zend_Controller_Front::getInstance();
        $name = $front->getRequest()->getModuleName();
        return ($name == '') ? 'default' : $name;
    }
    /**
     * Get the names of the modules loaded by the application bootstrap class
     * 
     * @return array $modules
     */
    public static function getModuleNames ($includeDefault = false)
    {
        $front = Zend_Controller_Front::getInstance();
        $bootstrap = $front->getParam('bootstrap');
        $modules = $includeDefault ? array('default' => 'default') : array();
        foreach ($bootstrap->modules as $key => $mod) {
            $modules[$key] = $key;
        }
        return $modules;
    }
}