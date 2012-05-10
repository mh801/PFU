<?php 
class User extends BaseModel {

	protected $password;
	
	function getPassword(){
		if(isset($this->password)){
			return $this->password;
		}else{
			return false;
		}
	}
	
	function setPassword($password = null){
		if(null != $password){
			 $this->password = $password;
		}else{
			return false;
		}
	}	

	function getEmail(){
		if(isset($this->email)){
			return $this->email;
		}else{
			return false;
		}
	}
	
	function setEmail($email = null){
		if(null != $email){
			 $this->email = $email;
		}else{
			return false;
		}
	}	
	
	function setIsActive($param = 0){
		$this->is_active = $param;
	}	
	
	function getIsActive(){
		if(isset($this->is_active)){
			return $this->is_active;
		}
		return false;
	}
	
	function loadByEmail($email = null){
		if(null != $email){			
			
			$user = $this->custom('SELECT * FROM users WHERE email ="' . $email .'" LIMIT 1');
			if($user){
				if($user[0] != null){
					return $user[0];
				}				
			}else{
				return false;
			}

		}
	}

}
