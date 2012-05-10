<?php
	class Form extends upload {
		protected $element = array();
		protected $action;
		protected $method = 'post';
		protected $html = '';
		protected $name = '';
		protected $type ='';
		protected $class = '';
		protected $fieldset = true;
		protected $wrapper_class = '';
		protected $label = array();
		function __construct(){
			
		}
		
		function type($type=''){
			$this->type = $type;
		}
		
		function useFieldset($param = true,$class=''){
			$this->fieldset = $param;
			$this->wrapper_class = $class;
		}
		
		function addName($name=''){
			$this->name = $name;
		}

		function addClass($class=''){
			$this->class = $class;
		}

		function addLabel($for = '', $value='',$class=''){
			$this->label[$for] = '<label for="' . $for . '" class="' . $class . '">' . $value . '</label><br/>';
		}
		
		function addElement($type = null, $name ='', $value = '', $validate = false, $class = ''){
			$this->element[$name] = new FormElement($type, $name, $value, $validate, $class);
		}
		
		function addAction($action = 'post'){
			$this->action = $action;
		}
		
		public function render(){
			/* render default fieldset? */
			if($this->fieldset){
				$this->html .= '<fieldset class="base-form"><form name="'. $this->name .'" enctype="'.$this->type.'" action="'. $this->action .'" method="'. $this->method .'" class="'. $this->class .'">';
			}else{
				$this->html .= '<div class="form-wrapper '.$this->wrapper_class.'">';
			}
			
			foreach($this->element as $key=>$element){
				$this->html .= '<div class="form-text">';
				if(array_key_exists($key,$this->label)){
					$this->html .= $this->label[$key];
				}
				$this->html .= $element->getHtml();
				$this->html .='</div>';
			}
			$this->html .= '</form>';
			if($this->fieldset){
				$this->html .= '</fieldset>';
			}else{
				$this->html .= '</div>';
			}
			return $this->html;
		}
	}
?>