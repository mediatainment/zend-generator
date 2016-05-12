<?php
/**
 * @author L.Dronkers | http://www.sreknord.net | contact: leonard at instantaccess.nl
 * @license nope, use at will
 * @version 0.2
 */
class Generator_Api_Accessor
{
    /**
     * Name 
     * @var string accessor name
     */
    private $name;
    /**
     * Type to cast to when set
     * 
     * @var string 
     */
    private $type;
    /**
     * Accessor generator constructor
     * 
     * @param $name
     * @param $type
     */
    public function __construct ($name, $type = '')
    {
        $this->name = $name;
        $this->type = $type;
    }
    /**
     * Return generated accessor
     * 
     * @return string
     */
    public function get ()
    {
        $getName = lcfirst($this->name);
        $data = "\tpublic function get" . ucfirst($this->name) . "()" . PHP_EOL;
        $data .= "\t{" . PHP_EOL;
        $data .= "\t\treturn \$this->_{$getName};" . PHP_EOL;
        $data .= "\t}" . PHP_EOL;
        return $data;
    }
    /**
     * Return generated accessor
     * 
     * @return string
     */
    public function set ()
    {
        $setName = lcfirst($this->name);
        $data = "\tpublic function set" . ucfirst($this->name) . "(\$value)" .
         PHP_EOL;
        $data .= "\t{" . PHP_EOL;
        $type = (! empty($this->type) ? "($this->type)" : '');
        $data .= "\t\t\$this->_{$setName} = $type\$value;" . PHP_EOL;
        $data .= "\t\treturn \$this;" . PHP_EOL;
        $data .= "\t}" . PHP_EOL;
        return $data;
    }
}