<?php
class userController extends BaseController{
		
	function login(){

		$this->_form->addName('login');
		$this->_form->addClass('login');
		$this->_form->addAction('/user/validate');
		$this->_form->addLabel('email','Email','form');
		$this->_form->addElement('text','email', 'Email Address', true);
		$this->_form->addLabel('password','Password','form');
		$this->_form->addElement('password','password', 'Password', true, 'password');
		$this->_form->addElement('submit','LogIn', 'Log In', false, 'submit');		
		$html = $this->_form->render();	
		$this->set('form',$html);	
		$this->set('title', 'Log In To Account');				
	}
	
	function checkuser(){
		$session = new Session();
		$response = array();
		$response['msg']='No Input';
		$response['success']=false;	
		$exp = explode('@',$this->getParams('email'));	
		if($exp[1] == ''){
			$response['msg']='Email Must be a valid address';
			$response['success']=false;	
		}else{
			if(isset($_COOKIE['PHPSESSID']) && isset($session) && $session->get($_COOKIE['PHPSESSID']) != null){
				$response['msg']='You are already registered';
				$response['success']=false;
			}else{
				$user = $this->User->loadByEmail($this->getParams('email'));
				if($user != null){
					$response['msg']='Already a user with this email address';
					$response['success']=false;				
				}else{
					$response['msg']='Email is valid';
					$response['success']=true;				
				}
		
			}	
		}
			$this->noRender();	
			echo json_encode($response);		
		
	}	
	
	function logout(){
		$session = new Session();
		$session->destroy();
		header("location:/user/login");
	}
	
	function account(){
		$session = new Session();
		if(isset($_COOKIE['PHPSESSID']) && isset($session) && $session->get($_COOKIE['PHPSESSID']) != null){
			$this->set('title', 'Manage Account');
			$this->_form->useFieldset(false,'change-pass');
			$this->_form->addName('changepass');
			$this->_form->addClass('login');
			$this->_form->addAction('/user/updatepass');
			$this->_form->addLabel('old_password','Current Password <span class="small">(that you logged in with)</span>','reqd');
			$this->_form->addElement('password','old_password', 'Password', true, 'curr-password');			
			$this->_form->addLabel('password','New Password','form reqd');
			$this->_form->addElement('password','password', 'Password', true, 'new-password');
			$this->_form->addLabel('verify_password','Verify Password','reqd');
			$this->_form->addElement('password','verify_password', 'Password', true, 'verify-password');			
			$this->_form->addElement('submit','update', 'Update Password', false, 'submit');		
			$html = $this->_form->render();	
			$this->set('form',$html);				
		}else{
			header('location:/user/login');	
		}
	}
	
	function updatepass(){
		$session = new Session();
		$user = $this->User->loadByEmail($this->_session['data']->__get('email'));
		if($this->matchPassword($this->getParams('old_password'),$this->_session['data']->__get('password'))){		
			if(isset($_COOKIE['PHPSESSID']) && isset($session) && $session->get($_COOKIE['PHPSESSID']) != null){				
				$this->User->prepareData($this->getParams());		
				$data = $this->User->getData();		
				array_pop($data);	
				$this->User->setData($data);
				$this->User->setId($this->_session['data']->__get('id'));
				$this->User->setIsActive(1);
				$this->generatePasswordHash($this->User->getPassword());
				//var_dump($this->User);
				$this->User->save(true);
				if($this->User->getId()){
					$response['success'] = true;
					$response['msg'] = 'Your password has been updated';		
				}else{
					$response['success'] = false;
					$response['msg'] = 'There was a problem updating your account';
				}
			}else{
				$response['success'] = false;
				$response['msg'] = 'You must be logged in to modify your account';
			}
		}else{
			$response['success'] = false;
			$response['msg'] = 'You entered the incorrect current password';
		}
		$this->noRender();
		echo json_encode($response);
	}
	
	function register(){
		
		$this->_form->addName('register');
		$this->_form->addClass('register');
		$this->_form->addAction('/user/add');
		$this->_form->addLabel('first_name','First Name','form');
		$this->_form->addElement('text','first_name', 'First Name', true);
		$this->_form->addLabel('last_name','Last Name','form');
		$this->_form->addElement('text','last_name', 'Last Name', true);
		$this->_form->addLabel('email','Email','form');
		$this->_form->addElement('text','email', 'Email Address', true);
		$this->_form->addLabel('password','Password','reqd');
		$this->_form->addElement('password','password', 'Password', true, 'password');
		$this->_form->addLabel('verify_password','Verify Password','reqd');
		$this->_form->addElement('password','verify_password', 'Password', true, 'password');
		$this->_form->addElement('submit','submit', 'Register', false, 'submit');
		$html = $this->_form->render();	
		$this->set('form',$html);	
		$this->set('title', 'Register Account');	

	}
		
	function add(){
		$this->User->prepareData($this->getParams());		
		$data = $this->User->getData();		
		array_pop($data);	
		$this->User->setData($data);
		$this->User->setIsActive(1);
		$this->generatePasswordHash($this->User->getPassword());
		$this->User->save();	
		header("location:/user/login");
	//	redirectAction('user','login');			
	}	
	
	function validate(){		
		$user = $this->User->loadByEmail($this->getParams('email'));		
		if($user && array_key_exists('User',$user)){
			if($this->matchPassword($this->getParams('password'),$user['User']['password'])){
				$this->User->setData($user['User']);										
				$session = new Session();
				$session->setFile();
				$session->set($this->User);		
				header("location:/task/index");
				redirectAction('main','main');
			}else{
				$msg = new Message();
				$msg->setMessage('Your email and password combination do not match');
				header("location:/user/login");
				redirectAction('user','login');
			}
		}
	}
	
	function delete(){
		$this->set('title', 'PFU');				
	}	
	
	function update(){
		$this->set('title', 'Edit Account');				
	}	
	
	private function getSalt(){
		return base_64_encode(PASSWORD_SALT);
	}
	
	private function encryptPass( $pass_hash, $salt_hash ){
		$pass = $salt_hash . $pass_hash; 
		$this->User->setPassword( $salt_hash . $pass_hash );  
		return $pass;
	}
	
	private function generateSaltHash(){			    
		    return substr( sha1(PASSWORD_SALT . str_pad( dechex( mt_rand() ), 8, '0', STR_PAD_LEFT ), -8 ),-8);				
	}	
	
	private function generatePasswordHash($password){	
			$salt_hash = $this->generateSaltHash();
		    $hash = $this->encryptPass(sha1($password), $salt_hash );	
		    return $hash;
	}
	
	private function matchPassword($password,$userPass){
		$password_hash = substr($this->generatePasswordHash($password),8);
		if($password_hash == substr($userPass,8)){
			return true;
		}
		return false;
	}
	
	
}
