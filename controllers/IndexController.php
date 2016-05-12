<?php
class Generator_IndexController extends Zend_Controller_Action
{
    public function indexAction ()
    {
        $this->_helper->layout()->disableLayout();
        $request = $this->getRequest();
        $path = APPLICATION_PATH . '/models';
        $message = $this->getRequest()->getParam('message', '');
        $tables = Generator_Api_Util_Db::getAllTableNames();
        $module = "";
        echo "PATH:<br/>" . $path . "<br />";
        $MAPPERPATH = $path . "/mappers";
        echo "MAPPERPATH:<br/>" . $MAPPERPATH . "<hr>";
        $tableArray = Generator_Api_Util_Db::getAllTableNames();
        if (! file_exists($MAPPERPATH)) {
            mkdir($path . '/mappers', 0777, FALSE);
        }
        foreach ($tableArray as $key => $table) {
            $name = $table;
            $name = preg_replace('(_)', ' ', $name);
            $name = ucwords($name);
            $name = preg_replace("[ ]", '', $name);
            echo "<h2>Table: " . $table . "</h2>";
            echo "<h5>NAME: " . $name . "</h2>";
            //            $path = $path . '/' . strtolower($module);
            // create table class
            $x = new Generator_Api_DbTable($name, $path, 
            $table);
            $t = $x->generate();
            if ($t instanceof Zend_Db_Table_Abstract) {
                $columns = $t->info('cols');
                // create model class
                $model = new Generator_Api_Model($name, $path, 
                $module);
                $a = $model->generate($columns);
                // create mapper
                $mapper = new Generator_Api_Mapper($name, $path, 
                $module);
                $result = $mapper->generate($columns);
                //set response
                $request->setParam('message', 'Alrighty then!');
            } else {
                $request->setParam('message', 'Error with dbTable class');
            }
        }
        //        set_include_path(
        //        implode(PATH_SEPARATOR, 
        //        array(realpath(getcwd() . '/library'), get_include_path())));
        //      require_once './library/Generator.php';
        /**
         * DISABLE THAT FOLLOWING CODE if you wish another dbTable
         */
        $config = array(
        // db details
        'db' => array('dbname' => 'gn_development', 
        'host' => 'localhost', 'port' => '3306', 'username' => 'zend', 
        'password' => 'zend', 'charset' => 'UTF8'), 
        // generator options
        'generator' => array('templates' => array(), 
        // directory where results will be saved
        'dir' => array('models' => APPLICATION_PATH . '/models/Row', 
        'mapper' => APPLICATION_PATH . '/models/mappers', 
        'tables' => APPLICATION_PATH . '/models/DbTable'), 
        // name patterns
        'pattern' => array(
        'model' => array('classname' => 'Model_Row_{table}'),  //'extends' => 'Zend_Db_Table_Row',
        'table' => array('classname' => 'Model_DbTable_{table}', 
        'extends' => 'Zend_Db_Table_Abstract'), 
        'mapper' => array('classname' => 'Model_Mapper_{table}', 
        'ext	per_Abstract')), 
        // custom variables 
        'custom' => array('author' => 'Jan Jezek', 
        'email' => 'mail@webdeisgn-solution.eu', 
        'copyright' => 'http://www.webdesign-solution.eu', 
        'license' => 'strictly limited for projects of webdesign-solution.eu only', 
        'subPackage' => 'Model')));
        $zg = new Core_Generator($config);
        $zg->generate();
        echo "now ready generated";

    }
    /*
     * if you want only tables generated / dbTables
     */
    public function dbtableAction ()
    {
        $this->_helper->layout()->disableLayout();
                //        set_include_path(
        //        implode(PATH_SEPARATOR, 
        //        array(realpath(getcwd() . '/library'), get_include_path())));
        //      require_once './library/Generator.php';
        $config = array(
        // db details
        'db' => array('dbname' => 'gn_development', 
        'host' => 'localhost', 'port' => '3306', 'username' => 'zend', 
        'password' => 'zend', 'charset' => 'UTF8'), 
        // generator options
        'generator' => array('templates' => array(), 
        // directory where results will be saved
        'dir' => array('models' => APPLICATION_PATH . '/models/Row', 
        'mapper' => APPLICATION_PATH . '/models/mappers', 
        'tables' => APPLICATION_PATH . '/models/DbTable'), 
        // name patterns
        'pattern' => array(
        'model' => array('classname' => 'Model_Row_{table}'),  //'extends' => 'Zend_Db_Table_Row',
        'table' => array('classname' => 'Model_DbTable_{table}', 
        'extends' => 'Zend_Db_Table_Abstract'), 
        'mapper' => array('classname' => 'Model_Mapper_{table}', 
        'ext	per_Abstract')), 
        // custom variables 
        'custom' => array('author' => 'Jan Jezek', 
        'email' => 'mail@webdeisgn-solution.eu', 
        'copyright' => 'http://www.webdesign-solution.eu', 
        'license' => 'strictly limited for projects of webdesign-solution.eu only', 
        'subPackage' => 'Model')));
        $zg = new Core_Generator($config);
        $zg->generate();
    }
}