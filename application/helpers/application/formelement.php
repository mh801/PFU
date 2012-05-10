<?php
	class FormElement{
		protected $type;
		protected $name;
		protected $value;
		protected $validate;
		public $html;
		protected $class;
		
		function __construct($type = null, $name ='', $value = '', $validate = false, $class = ''){
			$this->type = $type;
			$this->name = $name;
			if($type != 'file'){
				$this->value = $value;	
			}

			$this->validate = $validate;
			$this->class =$class;
		}		
		
		public function getHTML(){
			switch ($this->type){
				case 'text':
					$this->html  = '<div class="form-text"><input type = "text" name="'. $this->name .'" value="'. $this->value .'"';
				    $this->html .= ($this->validate == true)?' class="reqd ' . $this->class . '"':' class="'. $this->class . '"';
					$this->html .= '/></div>';
					return  $this->html;
					break;

				case 'hidden':
					$this->html  = '<input type = "hidden" name="'. $this->name .'" value="'. $this->value .'"/>';
					return  $this->html;
					break;

				case 'textArea':
					$this->html  = '<div class="form-textarea"><textarea cols="40" rows="5" name="'. $this->name .'"';
				    $this->html .= ($this->validate == true)?' class="reqd ' . $this->class . '"':' class="'. $this->class . '"';
					$this->html .= '>';
					$this->html .= $this->value;
					$this->html .= '</textarea></div>';
					return  $this->html;
					break;
						
				case 'password':
					$this->html  = '<div class="form-password"><input type = "password" name="'. $this->name .'" value="'. $this->value .'"';
				    $this->html .= ($this->validate == true)?' class="reqd ' . $this->class . '"':' class="'. $this->class . '"';
					$this->html .= '/></div>';
					return  $this->html;
					break;					
					
				case 'select':
					$this->html = '<div class="form-select"><select name="'. $this->name .'"';
				    $this->html .= ($this->validate == true)?' class="reqd ' . $this->class . '"':' class="'. $this->class . '"';
					$this->html .= '>';
					foreach($this->value as $option=>$value){
						$this->html .= '<option value="'. $option .'">'. $value .'</option>';
					}
					$this->html .= '</select></div>';
					return  $this->html;
					break;
					
				case 'multiselect':
					$this->html = '<div class="form-multiselect"><select name="'. $this->name .'"';
			    	$this->html .= ($this->validate == true)?' class="reqd ' . $this->class . '"':' class="'. $this->class . '"';
					$this->html .= ' MULTIPLE>';
					foreach($this->value as $option=>$value){
						$this->html .= '<option value="'. $option .'">'. $value .'</option>';
					}
					$this->html .= '<select';
					$this->html .= '/></div>';
					return  $this->html;				
					break;
					
				case 'radio':
					$this->html  = '<div class="form-radio"><input type = "radio" name="'. $this->name .'" value="'. $this->value .'"';
				    $this->html .= ($this->validate == true)?' class="reqd ' . $this->class . '"':' class="'. $this->class . '"';
					$this->html .= '/>'. $this->value . '</div>';
					return  $this->html;
					break;
					
				case 'checkbox':
					$this->html  = '<div class="form-checkbox"><input type = "checkbox" name="'. $this->name .'" value="'. $this->value .'"';
				    $this->html .= ($this->validate == true)?' class="reqd ' . $this->class . '"':' class="'. $this->class . '"';
					$this->html .= '/>'. $this->value . '</div>';
					return  $this->html;
					break;
					
				case 'file':
					$this->html ='<div class="form-file"><input type = "file" name="'. $this->name .'" value="'. $this->value .'"';
				    $this->html .= ($this->validate == true)?' class="reqd ' . $this->class . '"':' class="'. $this->class . '"';
					$this->html .= '/></div>';
					return  $this->html;					
					break;	

				case 'submit':		
					$this->html  = '<div class="form-submit"><input type = "submit" name="'. $this->name .'" value="'. $this->value .'"';
				    $this->html .= ($this->validate == true)?' class="reqd ' . $this->class . '"':' class="'. $this->class . '"';
					$this->html .= '/></div>';
					return  $this->html;
					break;
									
				default:
					$this->html  = '<div class="form-text"><input type = "text" name="'. $this->name .'" value="'. $this->value .'"';
				    $this->html .= ($this->validate == true)?' class="reqd ' . $this->class . '"':' class="'. $this->class . '"';
					$this->html .= '/></div>';
					return  $this->html;
					break;
				
				
			
				return $this->html;
			}
		}
	}
?>