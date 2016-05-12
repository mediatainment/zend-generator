<?php
class Generator_Bootstrap extends Zend_Application_Module_Bootstrap
{

    protected function _initAutoload()
    {
    	$autoloader = new Zend_Application_Module_Autoloader(array(
            'namespace' => 'Generator_',
            'basePath'  => dirname(__FILE__),
        ));
	
        /**
         *  add App, should be there by default but that's not the case
         *  @see http://framework.zend.com/manual/en/zend.loader.autoloader-resource.html#zend.loader.autoloader-resource.module
         */
        $autoloader->addResourceType('api','api','Api');
        
        return $autoloader;
    }
    
}