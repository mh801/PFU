<?php
class BaseModel extends SQLQuery {
	protected $_model;
	protected $_data = array();
	
	function __construct() {
		
		global $inflect;
		$this->connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
		$this->_limit = PAGINATE_LIMIT;
		$this->_model = get_class($this);
		$this->_table = strtolower($inflect->pluralize($this->_model));				
		if (!isset($this->abstract)) {
			$this->_describe();
		}
	}

	
	// set undeclared property
	public function __set($property = null, $value = null)
	{
		if ($property !== null and $property !== null)
		{
			$this->$property = $value;
		}
		return;
	}

	// get undeclared property
	public function __get($property)
	{
		if (isset($this->$property)){
			return $this->$property;
		}
	}
	
	function getId(){
		if(isset($this->id)){
			return $this->id;
		}else{
			return false;
		}
	}
	
	function setId($id = null){
		if(null != $id){
			 $this->id = $id;
		}else{
			return false;
		}
	}	


	function prepareData($data = null){
		foreach($data as $key=>$d){
			if($key != 'submit'){
				$this->_data[$key] = $d;
			}
		}
	}
	
	public function getData(){
		if($this->_data){
			return $this->_data;
		}else{
			return false;
		}
	}
	
	public function setData($data = null){
		if($data != null){
			foreach($data as $key=>$data){
				$this->$key = $data;
			}
			return true;			
		}
		if($this->_data){
			foreach($this->_data as $key=>$data){
				$this->$key = $data;
			}
			return true;
		}else{
			return false;
		}
	}	
	
	function __destruct() {
	}
}
