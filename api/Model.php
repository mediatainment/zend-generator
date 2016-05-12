<?php
/**
 * @author L.Dronkers | http://www.sreknord.net | contact: leonard at instantaccess.nl
 * @license nope, use at will
 * @version 0.2
 */
class Generator_Api_Model extends Generator_Api_Abstract
{
    const MODEL_DATA = 'Model.txt';
    protected $_modelName;
    protected $_path;
    protected $_modelContent;
    protected $_moduleName;
    public function __construct ($name, $path, $module = '')
    {
        $name = preg_replace('[_]', '[ ]', $name);
        $name = ucwords($name);
        $this->_modelName = preg_replace('[ ]', '[]', $name);
        $this->_path = $path . '/';
        $this->_moduleName = $module;
        $this->_modelContent = $this->fileGetContents(self::MODEL_DATA);
    }
    private function getClassName ()
    {
        $name = (! empty($this->_moduleName) ? $this->_moduleName . '_' : '');
        $name .= 'Model_';
        $name .= $this->_modelName;
        return $name;
    }
    private function getFilePath ()
    {
        return $this->_path . '/' . $this->_modelName . '.php';
    }
    private function getProperties (array $cols)
    {
        $properties = '';
        foreach ($cols as $col) {
            $col = preg_replace('[_]', ' ', $col);
            $col = ucwords($col);
            $col = preg_replace('[ ]', '', $col);
            $col = lcfirst($col);
            $p = new Generator_Api_Property($col);
            $properties .= $p->property();
        }
        return $properties;
    }
    private function getAccessors (array $cols)
    {
        $accessors = '';
        foreach ($cols as $col) {
            $col = preg_replace('[_]', ' ', $col);
            $col = ucwords($col);
            $col = preg_replace('[ ]', '', $col);
            $a = new Generator_Api_Accessor($col);
            $accessors .= $a->get() . PHP_EOL;
            $accessors .= $a->set() . PHP_EOL;
        }
        return $accessors;
    }
    private function getToArray (array $cols)
    {
        $toArray = '$data = array();' . PHP_EOL;
        foreach ($cols as $col) {
        $col = preg_replace('[_]', ' ', $col);
        $col = ucwords($col);
        $col = preg_replace('[ ]', '', $col);
            $toArray .= "\t\t" . '$data[\'' . lcfirst($col) . '\'] = $this->get' .
             ucfirst($col) . '();' . PHP_EOL;
        }
        $toArray .= "\t\t" . 'return $data;';
        return $toArray;
    }
    public function generate (array $cols)
    {
        $className = $this->getClassName();
        $fileName = preg_replace('[Model_]', '', $className);
        echo "ModelFileName: " . $fileName ."<br />";
        $result = null;
        // replace items
        $replace = array();
        $replace['__CLASS_NAME__'] = $className;
        $replace['__CLASS_PROPERTIES__'] = $this->getProperties($cols);
        $replace['__MODEL_ACCESSORS__'] = $this->getAccessors($cols);
        $replace['__MODEL_TO_ARRAY__'] = $this->getToArray($cols);
        $replace['__MAPPER_CLASS__'] = "Model_Mapper_" . $fileName;
        $data = str_replace(array_keys($replace), array_values($replace), 
        $this->_modelContent);
        // write to file
        $filePath = $this->getFilePath();
        if (false !== file_put_contents($filePath, $data)) {
            require_once $filePath;
            $result = new $className();
        }
        return $result;
    }
}