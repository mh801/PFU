#!/usr/local/bin/php
<?php
$project = new Presa();

	fwrite(STDOUT, "Please enter your project name (include path or will be generated in bin folder): \n");
	$project_name = fgets(STDIN);
	fwrite(STDOUT, "Do you want to include the user class for authentication (y/n)? (Y) : \n This is only currently compatible with MySQL.\n");
	$user = fgets(STDIN);
	$user = ($user=='')?'Y':$user;
	fwrite(STDOUT,"What is your projects url?: \n");
	$base_path = fgets(STDIN);	
	fwrite(STDOUT,"What would you like to use for your default pagination limit?: \n");
	$page_limit = fgets(STDIN);	
	fwrite(STDOUT,"What would you like to use for your encryption salt?: \n");
	$salt = fgets(STDIN);	
	fwrite(STDOUT,"What is your database host address?: \n");
	$db_host = fgets(STDIN);
	fwrite(STDOUT, "What is your project database name?: \n");
	$db_name = fgets(STDIN);
	fwrite(STDOUT, "What is your project database user name?: \n");
	$db_uname = fgets(STDIN);	
	fwrite(STDOUT, "What is your project database password?: \n");
	$db_pass = fgets(STDIN);

	$project->__set('name',$project_name);
	$project->__set('user',$user);
	$project->__set('salt',$salt);	
	$project->__set('page_limit',$page_limit);		
	$project->__set('base_path',$base_path);
	$project->__set('db',$db_name);
	$project->__set('db_user',$db_user);
	$project->__set('db_pass',$db_pass);	
	$project->__set('db_host',$db_host);
	$project->__set('path',$project_name);

	$project->mkPDir($project_name);
	$project->dbExec();
	$project->writeConfig();
// Exit correctly
exit(0);

class Presa{

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
	
	public function dbExec(){
		$this->_dbHandle = @mysql_connect('localhost', $this->db_user, $this->db_pass);
		$sql = 'CREATE TABLE IF NOT EXISTS `Users` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `first_name` varchar(50) NOT NULL,
		  `last_name` varchar(50) NOT NULL,
		  `email` varchar(100) NOT NULL,
		  `password` varchar(128) NOT NULL,
		  `is_active` tinyint(1) NOT NULL,
		  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		  `modified_at` datetime NOT NULL,
		  PRIMARY KEY (`id`),
		  UNIQUE KEY `email` (`email`)
		) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;';
		
		    mysql_query($sql, $this->_dbHandle);
			fwrite(STDOUT,"Users table created succesfully\n");		
        if ($this->_dbHandle != 0) {
            if (mysql_select_db($this->db, $this->_dbHandle)) {
                return 1;
            }
            else {
                return 0;
            }
        }
        else {
            return 0;
        }		
	}

	function createUserTable(){
		$sql = 'CREATE TABLE IF NOT EXISTS `Users` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `first_name` varchar(50) NOT NULL,
		  `last_name` varchar(50) NOT NULL,
		  `email` varchar(100) NOT NULL,
		  `password` varchar(128) NOT NULL,
		  `is_active` tinyint(1) NOT NULL,
		  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		  `modified_at` datetime NOT NULL,
		  PRIMARY KEY (`id`),
		  UNIQUE KEY `email` (`email`)
		) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;';
		
		    mysql_query($sql, $this->_dbHandle);
			fwrite(STDOUT,"Users table created succesfully\n");		
		
	}

	function createConfig($values = array()){
	
	}	

	function copyFiles($path){
		copy('../../../index.php',$path.'/index.php');
		copy('../../bootstrap.php',$path.'/lib/bootstrap.php');
		copy('presa.php',$path.'/lib/framework/bin/presa.php');
		copy('../cache/cache.php',$path.'/lib/framework/cache/cache.php');
		copy('../config/inflection.php',$path.'/lib/framework/config/inflection.php');
		copy('../config/routing.php',$path.'/lib/framework/config/routing.php');
		copy('../controller/basecontroller.php',$path.'/lib/framework/controller/basecontroller.php');
		copy('../model/basemodel.php',$path.'/lib/framework/model/basemodel.php');		
		copy('../model/inflection.php',$path.'/lib/framework/model/inflection.php');
		copy('../model/sqlquery.php',$path.'/lib/framework/model/sqlquery.php');			
		copy('../session/session.php',$path.'/lib/framework/session/session.php');	
		copy('../template/template.php',$path.'/lib/framework/template/template.php');	
		copy('../util/brain.php',$path.'/lib/framework/util/brain.php');
		copy('../util/debug.php',$path.'/lib/framework/util/debug.php');		
		copy('../../../public/.htaccess',$path.'/public/.htaccess');
		copy('../../../public/index.php',$path.'/public/index.php');	
		copy('../../../public/css/calendar.css',$path.'/public/css/calendar.css');
		copy('../../../public/js/jquery-1.6.2.min.js',$path.'/public/js/jquery-1.6.2.min.js');														
		copy('../../../public/js/jquery-ui-1.8.16.custom.min.js',$path.'/public/js/jquery-ui-1.8.16.custom.min.js');																
	}
	
	function mkPDir ($path) {       
        $pathArr = explode("/",$path);  
		$end = count($pathArr)-1;
		fwrite(STDOUT,$pathArr[$end]."\n");
		fwrite(STDOUT,$path."\n");
        	if(mkdir($path)){
				fwrite(STDOUT,"creating " . $path ."\n");
				if(mkdir($path . '/application')){
					fwrite(STDOUT,"creating " . $path ."/application\n");
					if(mkdir($path . '/application/controllers')){
						fwrite(STDOUT,"creating " . $path ."/application/controllers\n");
						if($this->user == Y){
							if(mkdir($path . '/application/controllers/user')){
								fwrite(STDOUT,"creating " . $path ."/application/controllers/user\n");
							}else{
								fwrite(STDOUT,"There was a problem creating directories, please check your file permissions\n");
							}							
						}						
					}else{
						fwrite(STDOUT,"There was a problem creating directories, please check your file permissions\n");
					}
					if(mkdir($path . '/application/helpers')){
						fwrite(STDOUT,"creating " . $path ."/application/helpers\n");
					}else{
						fwrite(STDOUT,"There was a problem creating directories, please check your file permissions\n");
					}					
					if(mkdir($path . '/application/models')){
						fwrite(STDOUT,"creating " . $path ."/application/models\n");
					}else{
						fwrite(STDOUT,"There was a problem creating directories, please check your file permissions\n");
					}						
					if(mkdir($path . '/application/views')){
						fwrite(STDOUT,"creating " . $path ."/application/views\n");
					}else{
						fwrite(STDOUT,"There was a problem creating directories, please check your file permissions\n");
					}
					
					if(mkdir($path . '/lib')){
						fwrite(STDOUT,"creating " . $path ."/lib\n");
					}else{
						fwrite(STDOUT,"There was a problem creating directories, please check your file permissions\n");
					}					
					if(mkdir($path . '/lib/framework')){
						fwrite(STDOUT,"creating " . $path ."/lib/framework\n");
					}else{
						fwrite(STDOUT,"There was a problem creating directories, please check your file permissions\n");
					}					
					if(mkdir($path . '/lib/framework/bin')){
						fwrite(STDOUT,"creating " . $path ."/lib/framework/bin\n");
					}else{
						fwrite(STDOUT,"There was a problem creating directories, please check your file permissions\n");
					}
					if(mkdir($path . '/lib/framework/cache')){
						fwrite(STDOUT,"creating " . $path ."/lib/framework/cache\n");
					}else{
						fwrite(STDOUT,"There was a problem creating directories, please check your file permissions\n");
					}		
					if(mkdir($path . '/lib/framework/config')){
						fwrite(STDOUT,"creating " . $path ."/lib/framework/config\n");
					}else{
						fwrite(STDOUT,"There was a problem creating directories, please check your file permissions\n");
					}	
					if(mkdir($path . '/lib/framework/controller')){
						fwrite(STDOUT,"creating " . $path ."/lib/framework\n");
					}else{
						fwrite(STDOUT,"There was a problem creating directories, please check your file permissions\n");
					}		
					if(mkdir($path . '/lib/framework/model')){
						fwrite(STDOUT,"creating " . $path ."/lib/framework/model\n");
					}else{
						fwrite(STDOUT,"There was a problem creating directories, please check your file permissions\n");
					}	
					if(mkdir($path . '/lib/framework/session')){
						fwrite(STDOUT,"creating " . $path ."/lib/framework/session\n");
					}else{
						fwrite(STDOUT,"There was a problem creating directories, please check your file permissions\n");
					}
					if(mkdir($path . '/lib/framework/template')){
						fwrite(STDOUT,"creating " . $path ."/lib/framework/template\n");
					}else{
						fwrite(STDOUT,"There was a problem creating directories, please check your file permissions\n");
					}	
					if(mkdir($path . '/lib/framework/util')){
						fwrite(STDOUT,"creating " . $path ."/lib/framework/util\n");
					}else{
						fwrite(STDOUT,"There was a problem creating directories, please check your file permissions\n");
					}
					if(mkdir($path . '/public')){
						fwrite(STDOUT,"creating " . $path ."/public\n");
					}else{
						fwrite(STDOUT,"There was a problem creating directories, please check your file permissions\n");
					}
					if(mkdir($path . '/public/css')){
						fwrite(STDOUT,"creating " . $path ."/public/css\n");
					}else{
						fwrite(STDOUT,"There was a problem creating directories, please check your file permissions\n");
					}		
					if(mkdir($path . '/public/img')){
						fwrite(STDOUT,"creating " . $path ."/public/img\n");
					}else{
						fwrite(STDOUT,"There was a problem creating directories, please check your file permissions\n");
					}			
					if(mkdir($path . '/public/js')){
						fwrite(STDOUT,"creating " . $path ."/public/js\n");
					}else{
						fwrite(STDOUT,"There was a problem creating directories, please check your file permissions\n");
					}						
					if(mkdir($path . '/scripts')){
						fwrite(STDOUT,"creating " . $path ."/scripts\n");
					}else{
						fwrite(STDOUT,"There was a problem creating directories, please check your file permissions\n");
					}					
					if(mkdir($path . '/tmp')){
						fwrite(STDOUT,"creating " . $path ."/tmp\n");
						if(mkdir($path . '/tmp/cache')){
							fwrite(STDOUT,"creating " . $path ."/tmp/cache\n");
						}else{
							fwrite(STDOUT,"There was a problem creating directories, please check your file permissions\n");
						}	
						if(mkdir($path . '/tmp/sessions')){
							fwrite(STDOUT,"creating " . $path ."/sessions\n");
						}else{
							fwrite(STDOUT,"There was a problem creating directories, please check your file permissions\n");
						}	
						if(mkdir($path . '/tmp/logs')){
							fwrite(STDOUT,"creating " . $path ."/tmp/logs\n");
						}else{
							fwrite(STDOUT,"There was a problem creating directories, please check your file permissions\n");
						}																
					}else{
						fwrite(STDOUT,"There was a problem creating directories, please check your file permissions\n");
					}			
																																																											
				}else{
					fwrite(STDOUT,"There was a problem creating directories, please check your file permissions\n");
				}
			}
			
			$this->copyFiles($path);
	}
	
	function writeConfig(){
		$fileName = $this->path . '/lib/framework/config/config.php';
		$handle = fopen($fileName, 'a');
		fwrite($handle,"<?php \n");
		fwrite($handle,"/** Configuration Variables **/ \n\n");
		fwrite($handle,"define ('DEVELOPMENT_ENVIRONMENT',true);");
		fwrite($handle,"\n");
		fwrite($handle,"define('DEBUG_MODE',false);");
		fwrite($handle,"\n");
		fwrite($handle,"define('PASSWORD_SALT','" . $this->salt . "');");
		fwrite($handle,"\n");
		fwrite($handle,"define('DB_NAME', '" . $this->db . "');");
		fwrite($handle,"\n");
		fwrite($handle,"define('DB_USER', '" . $this->db_user . "');");
		fwrite($handle,"\n");
		fwrite($handle,"define('DB_PASSWORD', '" . $this->db_pass . "');");
		fwrite($handle,"\n");
		fwrite($handle,"define('DB_HOST', '" . $this->db_host . "');");
		fwrite($handle,"\n");
		fwrite($handle,"define('BASE_PATH','" . $this->base_path . "');");
		fwrite($handle,"\n");
		fwrite($handle,"define('PAGINATE_LIMIT', '" . $this->page_limit . "');");
		fwrite($handle,"\n");
		fclose($handle);
	}

}

?>