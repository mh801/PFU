<?php
class Debug {
	
	protected $_file = null;
	protected $_data = array();

	function setData($data = null){
		if(null != $data){
			$this->_data[] = $data;			
		}
	}
	
	function getData(){
		if(null != $this->_data){
			return $this->_data;
		}
		return false;
	}
	
	function setFile($file = null){
		if(null != $file){
			$this->_file = $file;			
		}
	}
	
	function getFile(){
		if(null != $this->_file){
			return $this->_file;
		}
		return false;
	}
	
	function get($fileName) {
		if(null != $this->_file){
			$fileName = ROOT.DS.'tmp'.DS.'logs'.DS.$this->_file;
		}else{
			$fileName = ROOT.DS.'tmp'.DS.'logs'.DS.$fileName;
		}
		if (file_exists($fileName)) {
			$handle = fopen($fileName, 'rb');
			$variable = fread($handle, filesize($fileName));
			fclose($handle);
			return unserialize($variable);
		} else {
			return null;
		}
	}
	
	function set($fileName,$variable) {
		if(null != $this->_file){
			$fileName = ROOT.DS.'tmp'.DS.'logs'.DS.$this->_file;
		}else{
			$fileName = ROOT.DS.'tmp'.DS.'logs'.DS.$fileName;
		}
		$handle = fopen($fileName, 'a');
		foreach($variable as $key=>$d){			
			if(!is_array($d)){
				fwrite($handle, $key. ' => ' . $d . "\n");
			}else{
				foreach($d as $k=>$x){
					if(!is_array($x)){
						fwrite($handle, $k .' => '. $x . "\n");
					}else{
						foreach($x as $k1=>$x1){
							if(!is_array($x1)){
								fwrite($handle, $k1 .' => '. $x1 . "\n");
							}else{
								fwrite($handle, $k1 .' => '. implode(',',$x1) . "\n");
							}
							
						}
					}
					
				}
			}
						
		}

		fclose($handle);
	}

}
