<?php
class Template {
	
	protected $variables = array();
	protected $_controller;
	protected $_action;
	protected $_params;
	
	function __construct($controller,$action,$params) {
		$this->_controller = $controller;
		$this->_action = $action;
		#make params available without call in all templates
		$this->_params = $params;
	}

	/** Set Variables **/

	function set($name,$value) {
		$this->variables[$name] = $value;	
	}

	function renderJSON(){
		$jsonArray = array();
		foreach($this->variables as $key=>$val){
			$jsonArray[$key][] = $val;
		}
		echo json_encode($jsonArray);
	}

	/** Display Template **/
	
    function render($noRender = 0, $doNotRenderHeader = 0) {
		
		$form = new Form();
		$html = new HTML();
		extract($this->variables);
		if($noRender == 0){
			if ($doNotRenderHeader == 0) {
			
				if (file_exists(ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . 'layout.phtml')) {
					include (ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . 'layout.phtml');
				} else {
					include (ROOT . DS . 'application' . DS . 'views' . DS . 'layout' . DS . 'layout.phtml');
				}
			}

			if (file_exists(ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . $this->_action . '.phtml')) {
				include (ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . $this->_action . '.phtml');		 
			}
			
			if ($doNotRenderHeader == 0) {
				if (file_exists(ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . 'footer.phtml')) {
					include (ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . 'footer.phtml');
				} else {
					include (ROOT . DS . 'application' . DS . 'views' . DS . 'layout' . DS .'footer.phtml');
				}
			}
		}	
    }

}