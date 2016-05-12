<?php
/**
 * @author L.Dronkers | http://www.sreknord.net | contact: leonard at instantaccess.nl
 * @license nope, use at will
 * @version 0.2
 */
class Generator_Api_Property
{
    /**
     * Name of the property
     * 
     * @var string
     */
    private $name;
    /**
     * Accessibility e.g. public, private, protected..
     * 
     * @var string accessibility
     */
    private $type;
    /**
     * Property generator constructor
     * 
     * @param $name
     * @param $type
     */
    public function __construct ($name, $type = 'protected')
    {
        echo $name . "<br />";
        $this->name = $name;
        $this->type = $type;
    }
    /**
     * Return generated property
     * 
     * @return string
     */
    public function property ()
    {
        $data = "\t" . $this->type . " \$_{$this->name};" . PHP_EOL;
        return $data;
    }
}