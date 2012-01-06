<?php

class BaseController {
	
	protected $_controller;
	protected $_action;
	protected $_template;
	protected $_params;
	protected $_form;
	protected $_debug;
	protected $_session;
	public $doNotRenderHeader;
	public $render;
	
	function beforeAction(){
		$session = new Session();
			if(isset($_COOKIE['PHPSESSID']) &&	$this->_session = $session->get($_COOKIE['PHPSESSID']) ){
				$this->set('session',$this->_session['data']); 
			}else{
				$this->set('session', '');
			}
	}
		
	function afterAction(){
		
		}

	function __construct($controller, $action, $params = array()) {
		
		global $inflect;
		
		if(DEBUG_MODE == true){
			$this->_debug = new Debug();
			$this->_debug->setFile('Controller-'.ucfirst($controller).'-'.date("Ymd"));
		}
		
		if($params){
			foreach($params as $key=>$param){
				$this->_params[$key] = $param;
			}
			if(DEBUG_MODE == true){
				$this->_debug->setData($this->_params);
			}
		}
		
		$this->_controller = ucfirst($controller);
		$this->_action = $action;

		if(DEBUG_MODE == true){
			$debugData = array();
			$debugData['controller'] = $this->_controller;
			$debugData['action'] = $this->_action;
			$this->_debug->setData($debugData);
		}
		
		$model = ucfirst($inflect->singularize($controller));
		$this->doNotRenderHeader = 0;
		$this->render = 1;
		$this->$model = new $model;
		$this->_form = new Form();
		if($action){
			$this->_template = new Template($controller,$action,$this->_params);
		}else{
			$this->_template = new Template($controller,'main',$this->_params);
		}
		
		if(DEBUG_MODE == true){
			$this->_debug->set($this->_debug->getFile(),$this->_debug->getData());
		}
	}

	function set($name,$value) {
		$this->_template->set($name,$value);
		if(DEBUG_MODE == true){
			$var = array();
			if(is_array($value)){
				$var[] = implode(',',$value);
			}else{
				$var[$name] = $value;
			}	
			$this->_debug->setData($var);
			$this->_debug->set($this->_debug->getFile(),$this->_debug->getData());
		}		
	}

	function getParams($param = null){	
		if(isset($param) && $param){
			return $this->_params[$param];
		}	
		return $this->_params;		
	}

	function getJsonParams($param = null){
		if($param){
			return json_encode($this->_params[$param]);
		}		
		return json_encode($this->_params);		
	}
	
	function noRender(){
		$this->render = false;
	}
	
	function __destruct() {
		if ($this->render) {
			$this->_template->render($this->doNotRenderHeader);
		}
	}
		
}	