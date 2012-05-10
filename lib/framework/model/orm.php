<?php
require ROOT . DS . 'db'. . DS . 'adodb5' . DS . 'adodb.inc.php';
require ROOT . DS . 'db'. . DS . 'adodb5' . DS . 'adodb-active-record.inc.php';
class ORM extends ADOdb_Active_Record{
	protected $DB;
	protected $model;
	protected $class;
		
	function __construct(){
		$this->DB = NewADOConnection('mysql');
		$this->DB->connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME,$this->_table);
		ADOdb_Active_Record::SetDatabaseAdapter($this->db);	
		var_dump($this->class);
		die();
	}
}