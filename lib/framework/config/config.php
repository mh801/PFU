<?php
/*****************************/
/** Configuration Variables **/
/****************************/

define ('DEVELOPMENT_ENVIRONMENT',true);
define('DEBUG_MODE',false);

define('PASSWORD_SALT','pfutopian');

/* So we can use any dev DB we want */
if(DEVELOPMENT_ENVIRONMENT){
	define('DB_NAME', 'test');
	define('DB_USER', 'root');
	define('DB_PASSWORD', '');
	define('DB_HOST', '127.0.0.1');	
	/*always use trailing slash */
	define('BASE_PATH','http://pfu.mac/');	
}else{
	define('DB_NAME', 'production');
	define('DB_USER', 'root');
	define('DB_PASSWORD', '');
	define('DB_HOST', 'localhost');	
	/*always use trailing slash */
	define('BASE_PATH','http://pfu.com/');
}




define('PAGINATE_LIMIT', '25');

/* project bubble Variables */
define('API_KEY','a335d92b50574c1c3afc78e8d5539a365efdf8ba');
define('API_URL','https://thereadystore.projectbubble.com/api');

/* Mail default recipient */
define('EMAIL_ADDRESS','matth@thereadystore.com');