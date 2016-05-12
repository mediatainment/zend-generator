<?php
class Generator_Api_Util_Db 
{
	/**
	 * Retrieve an array of tablenames from Zend_Db_Table default adapter
	 * 
	 * @param string type defaults to assoc
	 * @return array:
	 */
	public static function getAllTableNames($type = 'assoc')
	{
		// get default db adapter
		$adapter = Zend_Db_Table::getDefaultAdapter();
		
		if (null === $adapter) {
			throw new Exception("No default database adapter is set");
		}

		$tables = array();
		
		if($type == 'assoc'){
			foreach ($adapter->listTables() as $table)
			{
				$tables[$table] = $table;
			}
		}else{
			$tables = $adapter->listTables();
		}
		
		return $tables;
	}
	
	/**
	 * Get columnames for a tablename
	 * 
	 * @param string $tableName
	 * @param string type defaults to assoc
	 * @return array
	 */
	public static function getColumns($tableName, $type = 'assoc')
	{
		// get default db adapter
		$adapter = Zend_Db_Table::getDefaultAdapter();
		
		if (null === $adapter) {
			throw new Exception("No default database adapter is set");
		}
		
		$tableInfo = $adapter->describeTable($tableName);
		
		$columns = array();
		
		if($type == 'assoc'){
			foreach ($tableInfo as $column => $info)
			{
				$columns[$column] = $column;
			}
		}else{
			$columns = array_keys($tableInfo);
		}
		return $columns;
	}
}