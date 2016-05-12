<?php
/**
 * @author L.Dronkers | http://www.sreknord.net | contact: leonard at instantaccess.nl
 * @license nope, use at will
 * @version 0.2
 */
abstract class Generator_Api_Abstract
{
    /**
     * Table folder name in module directory
     * standard Zend naming convention
     * 
     * @var String
     */
    const TABLE_FOLDER_NAME = 'DbTable';
    /**
     * Models folder name in module directory
     * standard Zend naming convention
     * 
     * @var string
     */
    const MODELS_FOLDER_NAME = 'models';
    const MAPPER_FOLDER_NAME = 'mappers';
    /**
     * Get the contents of a template file
     * 
     * @param string $file
     * @return string template contents
     */
    protected function fileGetContents ($file)
    {
        $path = realpath(dirname(__FILE__) . '/_templates/' . $file);
        return file_get_contents($path);
    }
    /**
     * Create directory for generated class
     * 
     * @param string $path
     * @param string $mode
     * @param boolean $recursive
     * @return boolean
     */
    public function createDir ($path, $mode = 0777, $recursive = false)
    {
        $result = false;
        if (file_exists($path) && is_dir($path)) {
            $result = true;
        } else {
            $result = mkdir($path, $mode, $recursive);
        }
        return $result;
    }
}