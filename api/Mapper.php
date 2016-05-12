<?php
/**
 * @author L.Dronkers | http://www.sreknord.net | contact: leonard at instantaccess.nl
 * @license nope, use at will
 * @version 0.2
 */
class Generator_Api_Mapper extends Generator_Api_Abstract
{
    const MAPPER_DATA = 'Mapper.txt';
    protected $_mapperName;
    protected $_path;
    protected $_mapperContent;
    protected $_moduleName;
    public function __construct ($name, $path, $module = '')
    {
        $this->_mapperName = $name;
        $this->_path = APPLICATION_PATH . '/models/mappers';
        $this->_moduleName = $module;
        $this->_mapperContent = $this->fileGetContents(self::MAPPER_DATA);
    }
    private function getClassName ()
    {
        $name = (! empty($this->_moduleName) ? $this->_moduleName . '_' : '');
        $name .= 'Model_Mapper_';
        $name .= ucfirst($this->_mapperName);
        return $name;
    }
    private function getTableName ()
    {
        $name = (! empty($this->_moduleName) ? $this->_moduleName . '_' : '');
        $name .= 'Model_';
        $name .= self::TABLE_FOLDER_NAME . '_';
        $name .= $this->_mapperName;
        return $name;
    }
    private function getFilePath ()
    {
        return $this->_path . '/' . $this->_mapperName . '.php';
    }
    private function getModelDataArray (array $cols)
    {
        $data = "array(" . PHP_EOL;
        $i = 1;
        $count = count($cols);
        foreach ($cols as $col) {
            $dbCol = $col;
            $col = preg_replace('[_]', ' ', $col);
            $col = ucwords($col);
            $col = preg_replace('[ ]', '', $col);
            $col = lcfirst($col);
            if ($col === 'id') {
                continue;
            }
            $data .= "\t\t\t" . "'$dbCol' => \$model->get";
            $data .= ucfirst($col) . "()";
            $data .= ($i < $count ? ',' . PHP_EOL : PHP_EOL);
            $i ++;
        }
        $data .= "\t\t);" . PHP_EOL;
        return $data;
    }
    public function getModelSetProperties (array $cols)
    {
    $data = '';
        foreach ($cols as $col) {
            $dbColName = $col;
            $col = preg_replace('[_]', ' ', $col);
            $col = ucwords($col);
            $col = preg_replace('[ ]', '', $col);
            $col = lcfirst($col);
            $data .= "\t\t\$model->set" .ucfirst($col) . "(\$row['$dbColName']);" . PHP_EOL;
        }
        return $data;
    }
    public function generate (array $cols)
    {
        $className = $this->getClassName();
        $modelName = str_replace('Mapper_', '', $className);
        $result = null;
        // replace items
        $replace = array();
        $replace['__CLASS_NAME__'] = $className;
        $replace['__TABLE_NAME__'] = $this->getTableName();
        $replace['__MODEL_NAME__'] = $modelName;
        $replace['__MODEL_DATA_ARRAY__'] = $this->getModelDataArray($cols);
        $replace['__MODEL_SET_PROPERTIES__'] = $this->getModelSetProperties(
        $cols);
        $data = str_replace(array_keys($replace), array_values($replace), 
        $this->_mapperContent);
        // write to file
        $filePath = $this->getFilePath();
        if (false !== file_put_contents($filePath, $data)) {
            require_once $filePath;
            $result = new $className();
        }
        return $result;
    }
}