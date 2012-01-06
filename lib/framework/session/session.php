<?php
class Session {
	protected $_file;	
	protected $session_id = null;
	
	function __construct(){

	}

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
	
		
	function setId($id = null){
		if(null != $id){
			$this->session_id = $id;			
		}
	}
	
	function getId(){
		if(null != $this->session_id){
			return $this->session_id;
		}
		return false;
	}
	
	
	function setFile(){
		if(null != $this->session_id){
			$this->_file = $this->session_id;			
		}
	}
	
	function getFile(){
		if(null != $this->_file){
			return $this->_file;
		}
		return false;
	}
	
	function destroy(){
		// Initialize the session.
		// If you are using session_name("something"), don't forget it now!
		session_start();

		// Unset all of the session variables.
		$_SESSION = array();

		// If it's desired to kill the session, also delete the session cookie.
		// Note: This will destroy the session, and not just the session data!
		if (ini_get("session.use_cookies")) {
		    $params = session_get_cookie_params();
		    setcookie(session_name(), '', time() - 42000,
		        $params["path"], $params["domain"],
		        $params["secure"], $params["httponly"]
		    );
		}

		// Finally, destroy the session.
		session_destroy();
	}
		
	function get($fileName) {
		$fileName = ROOT.DS.'tmp'.DS.'sessions'.DS.$fileName;
		if (file_exists($fileName)) {
			$handle = fopen($fileName, 'rb');
			$variable = fread($handle, filesize($fileName));
			fclose($handle);
			return unserialize($variable);
		} else {
			return null;
		}
	}
	
	function set($variable) {
		session_start();
		$this->session_id = session_id();		
		//setcookie('session',session_id());		
		ini_set('session.save_path',ROOT.DS.'tmp'.DS.'sessions'.DS.$this->_file);
		$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
		$_SESSION['data'] = $variable;
		$fileName = ROOT.DS.'tmp'.DS.'sessions'.DS.$this->session_id;
		$handle = fopen($fileName, 'a');	
		fwrite($handle, serialize($_SESSION));
		fclose($handle);
	}
	
	

}
