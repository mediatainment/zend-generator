<?php
/**
 * @author L.Dronkers | http://www.sreknord.net | contact: leonard at instantaccess.nl
 * @license nope, use at will
 * @version 0.2
 */
class Generator_Api_DbTable extends Generator_Api_Abstract
{
    const MODEL_DATA = 'Table.txt';
    protected $_modelName;
    protected $_path;
    protected $_tableName;
    protected $_moduleName;
    protected $_tableContent;
    protected $_db = null;
    protected $_keys = null;
    protected $_tables = null;
    /**
     * Class constructor
     * 
     * @param $name class name
     * @param $path path to create it 
     * @param $module module to create it in
     */
    public function __construct ($name, $path, $tableName = '', $module = '')
    {
        $this->_modelName = $name;
        $this->_path = $path . '/' . self::TABLE_FOLDER_NAME;
        $this->_tableName = $tableName;
        $this->_moduleName = $module;
        $this->_tableContent = $this->fileGetContents(self::MODEL_DATA);
    }
    /**
     * getDb
     * 
     * @return Zend_Db_Adapter_Abstract
     */
    public function getDb ()
    {
        if ($this->_db === null) {
            $this->_db = Zend_Db_Table::getDefaultAdapter();
        }
        return ($this->_db);
    }
    //    protected function getKeys ()
    //    {
    //        if ($this->_keys === null) {
    //            $this->_keys = $this->getPrimaryKey();
    //        }
    //        return ($this->_keys);
    //    }
    public function getClassName ()
    {
        $modelname = (! empty($this->_moduleName) ? $this->_moduleName . '' : '');
        $modelname .= 'Model_';
        $modelname .= ucfirst(self::TABLE_FOLDER_NAME) . '_';
        $modelname .= $this->_modelName;
        echo "<br />ModelName: " . $modelname . "<br />";
        return $modelname;
    }
    public function getFilePath ()
    {
        $this->_path = $this->_path . '/' . ucfirst($this->_modelName) . '.php';
        return $this->_path;
    }
    public function getTablesList ()
    {
        if ($this->_tables === null) {
            $this->_tables = $this->getDb()->listTables();
        }
        return ($this->_tables);
    }
    public function getPrimaryKey ()
    {
        $table = $this->getTableName();
        $table = strtolower($table);
        echo "<br />TableName " . $table;
        $infos = $this->getDb()->describeTable($table);
        $pks = null;
        foreach ($infos as $field => $info) {
            if ($info['PRIMARY'] === true) {
                $pks .= $field . "";
                //catches only the first // uncomment that for more pks
                break;
            }
        }
        if (! $pks == NULL) {
            $pks = rtrim($pks, ",");
        } else {
            return null;
        }
        echo "<br />PRIMARY RETURNED:  " . $pks;
        return $pks;
    }
//    public function getFk ($ftable, $fk)
//    {
//        foreach ($this->getTablesList() as $ptable) {
//            if ($ftable != $ptable) {
//                $infos = $this->getDb()->describeTable($ptable);
//                foreach ($infos as $field => $info) {
//                    if ($field == $fk) {
//                        if ($info['PRIMARY'] === false &&
//                         $info['IDENTITY'] === false) {
//                            // Dependent Table
//                            $this->_keys[$ftable]['dt'][] = $ptable;
//                            // Reference tables
//                            $role = ucfirst($ptable) .
//                             "To" . ucfirst($ftable);
//                            $this->_keys[$ptable]['fk'][$role]['columns'] = $field;
//                            $this->_keys[$ptable]['fk'][$role]['refTableClass'] = ucfirst(
//                            $ftable);
//                            $this->_keys[$ptable]['fk'][$role]['refColumns'] = $field;
//                        } elseif ($info['PRIMARY'] === true &&
//                         $info['IDENTITY'] === false) {
//                            // Dependent Table
//                            $this->_keys[$ftable]['dt'][] = $ptable;
//                            // Reference tables
//                            $role = ucfirst($ptable) .
//                             "To" . ucfirst($ftable);
//                            $this->_keys[$ptable]['fk'][$role]['columns'] = $field;
//                            $this->_keys[$ptable]['fk'][$role]['refTableClass'] = ucfirst(
//                            $ftable);
//                            $this->_keys[$ptable]['fk'][$role]['refColumns'] = $field;
//                        }
//                    }
//                }
//            }
//        }
//        return;
//    }
    public function getTableName ()
    {
        if (! empty($this->_tableName)) {
            $name = strtolower($this->_tableName);
        } else {
            $name = strtolower($this->_modelName);
        }
        return $name;
    }
    /**
     * Generate Table class
     * 
     * @return Zend_Db_Table_Abstract
     */
    public function generate ()
    {
        $result = null;
        if ($this->createDir($this->_path, 0777, true)) {
            $className = $this->getClassName();
            // replace parts
            $replace = array();
            echo "ClassName: " . $className . "<br />";
            $replace['__CLASS_NAME__'] = $className;
            $replace['__TABLE_NAME__'] = $this->getTableName();
            $pk = $this->getPrimaryKey();
            echo "<h1>PRIMARY Keys: " . $pk . "</h1><br />";
            if ($pk === NULL) {
                echo "No Primary Key available <br />";
            } else {
                $pks = "protected \$_primary = '" . $pk . "';";
            }
            $replace['__PRIMARY_KEYS__'] = $pks;
            $data = str_replace(array_keys($replace), array_values($replace), 
            $this->_tableContent);
            echo "DATA: " . $data;
            // write to file
            $filePath = $this->getFilePath();
            if (false !== file_put_contents($filePath, $data)) {
                require_once $filePath;
                $result = new $className();
                echo "<strong>Variables</strong>";
                Zend_Debug::dump($replace);
            }
        }
        return $result;
    }
}