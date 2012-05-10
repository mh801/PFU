<?php
	class Message {
		protected $_message;
		
		function setMessage($msg = null){
			if(null != $msg){
				$this->_message = $msg;				
			}else{
				$this->_message = '';	
			}
		}
		
		function getMessage(){
			if(isset($this->_message)){
				return $this->_message;
			}
			return false;
		}
		
	}