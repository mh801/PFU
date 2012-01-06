<?php

/** Check if environment is development and display errors **/

function setReporting() {
if (DEVELOPMENT_ENVIRONMENT == true) {
		error_reporting(E_ALL);
		ini_set('display_errors','On');
	} else {
		error_reporting(E_ALL);
		ini_set('display_errors','Off');
		ini_set('log_errors', 'On');
		ini_set('error_log', ROOT.DS.'tmp'.DS.'logs'.DS.'error.log');
	}
}

/** Check for Magic Quotes and remove them **/

function stripSlashesDeep($value) {
	$value = is_array($value) ? array_map('stripSlashesDeep', $value) : stripslashes($value);
	return $value;
}

function removeMagicQuotes() {
	if ( get_magic_quotes_gpc() ) {
		$_GET    = stripSlashesDeep($_GET   );
		$_POST   = stripSlashesDeep($_POST  );
		$_COOKIE = stripSlashesDeep($_COOKIE);
	}
}

function fetchParams(){
	$params = array();
	
	if($_GET){
		foreach($_GET as $key=>$g){
				$params[$key] = $g;
		}		
	}
	if($_POST){
		foreach($_POST as $key=>$p){
				$params[$key] = $p;
		}		
	}	
	return $params;
}

/** Check register globals and remove them **/

function unregisterGlobals() {
	
    if (ini_get('register_globals')) {
        $array = array('_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
        foreach ($array as $value) {
            foreach ($GLOBALS[$value] as $key => $var) {
                if ($var === $GLOBALS[$key]) {
                    unset($GLOBALS[$key]);
                }
            }
        }
    }
}

/** Secondary Call Function **/

function performAction($controller,$action,$queryString = null,$render = 0) {
	$controllerName = ucfirst($controller).'Controller';
	$dispatch = new $controllerName($controller,$action);
	
	$dispatch->render = $render;
	if($queryString == null){
		$queryString = array();
	}	
	return call_user_func_array(array($dispatch,$action),$queryString);
}

/** Secondary Call Function **/

function redirectAction($controller,$action,$queryString = null,$render = 1) {
	$controllerName = ucfirst($controller).'Controller';
	$dispatch = new $controllerName($controller,$action);
	$dispatch->render = $render;
	if($queryString == null){
		$queryString = array();
	}
	return call_user_func_array(array($dispatch,$action),$queryString);
	exit();
}

/** Routing **/

function routeURL($url) {
	global $routing;

	foreach ( $routing as $pattern => $result ) {
            if ( preg_match( $pattern, $url ) ) {
				return preg_replace( $pattern, $result, $url );
			}
	}

	return ($url);
}

/** Main Call Function **/

function callHook() {
	global $url;
	global $default;
	global $params;
	$urlParams = fetchParams();
	$params = $urlParams;
	$queryString = array();

	if (!isset($url)) {
		$controller = $default['controller'];
		$action = $default['action'];
	} else {
		$url = routeURL($url);
		$urlArray = array();
		$urlArray = explode("/",$url);
		$controller = $urlArray[0];
		array_shift($urlArray);
		if (isset($urlArray[0])) {
			$action = $urlArray[0];
			array_shift($urlArray);
		} else {
			$action = 'index'; // Default Action
		}
		$queryString = $urlArray;
	}
	
	$controllerName = ucfirst($controller).'Controller';
	
	$dispatch = new $controllerName($controller,$action,$params);

	if ((int)method_exists($controllerName, $action)) {
		call_user_func_array(array($dispatch,"beforeAction"),$queryString);
		call_user_func_array(array($dispatch,$action),$queryString);
		call_user_func_array(array($dispatch,"afterAction"),$queryString);
	} else {
		/* Error Generation Code Here */
	}
}


/** Autoload any classes that are required **/

function __autoload($className) {
	if (file_exists(ROOT . DS . 'lib' . DS . 'framework' . DS . 'controller' . DS . strtolower($className) . '.php')) {
		require_once(ROOT . DS . 'lib' . DS . 'framework' . DS . 'controller' . DS . strtolower($className) . '.php');
	} else if (file_exists(ROOT . DS . 'lib' . DS . 'framework' . DS . 'util' . DS . strtolower($className) . '.php')) {
		require_once(ROOT . DS . 'lib' . DS . 'framework' . DS . 'util' . DS . strtolower($className) . '.php');	
	} else if (file_exists(ROOT . DS . 'lib' . DS . 'framework' . DS . 'model' . DS . strtolower($className) . '.php')) {
		require_once(ROOT . DS . 'lib' . DS . 'framework' . DS . 'model' . DS . strtolower($className) . '.php');
	} else if (file_exists(ROOT . DS . 'lib' . DS . 'framework' . DS . 'cache' . DS . strtolower($className) . '.php')) {
		require_once(ROOT . DS . 'lib' . DS . 'framework' . DS . 'cache' . DS . strtolower($className) . '.php');			
	} else if (file_exists(ROOT . DS . 'lib' . DS . 'framework' . DS . 'session' . DS . strtolower($className) . '.php')) {
		require_once(ROOT . DS . 'lib' . DS . 'framework' . DS . 'session' . DS . strtolower($className) . '.php');
	} else if (file_exists(ROOT . DS . 'lib' . DS . 'framework' . DS . 'template' . DS . strtolower($className) . '.php')) {
			require_once(ROOT . DS . 'lib' . DS . 'framework' . DS . 'template' . DS . strtolower($className) . '.php');	
		############################################################################################
		# Module specific loads ** TODO (when I have time) - ONLY autoload if in modules namespace #
		############################################################################################		
	} else if (file_exists(ROOT . DS . 'application' . DS . 'controllers' . DS . strtolower(str_replace('Controller','',$className)) . DS . strtolower($className) . '.php')) {
		require_once(ROOT . DS . 'application' . DS . 'controllers' . DS . strtolower(str_replace('Controller','',$className)) . DS . strtolower($className) . '.php');
	} else if (file_exists(ROOT . DS . 'application' . DS . 'models' . DS . strtolower($className) . DS . strtolower($className) . '.php')) {
		require_once(ROOT . DS . 'application' . DS . 'models' . DS . strtolower($className) . DS . strtolower($className) . '.php');				
	} else if (file_exists(ROOT . DS . 'application' . DS . 'helpers' . DS . 'application' . DS . strtolower($className) . '.php')) {		
		require_once (ROOT . DS . 'application' . DS . 'helpers' . DS . 'application' . DS . strtolower($className) . '.php');
	} else if (file_exists(ROOT . DS . 'application' . DS . 'helpers' . DS . strtolower($className) . DS . strtolower($className) . '.php')) {		
		require_once (ROOT . DS . 'application' . DS . 'helpers' . DS . strtolower($className) . DS . strtolower($className) . '.php');
	} else{
		/* Error Generation Code Here */
	}
	
}


/** GZip Output **/

function gzipOutput() {
    $ua = $_SERVER['HTTP_USER_AGENT'];

    if (0 !== strpos($ua, 'Mozilla/4.0 (compatible; MSIE ')
        || false !== strpos($ua, 'Opera')) {
        return false;
    }

    $version = (float)substr($ua, 30); 
    return (
        $version < 6
        || ($version == 6  && false === strpos($ua, 'SV1'))
    );
}

/** Get Required Files **/

gzipOutput() || ob_start("ob_gzhandler");


$cache = new Cache();
$inflect = new Inflection();

setReporting();
removeMagicQuotes();
unregisterGlobals();
callHook();


?>