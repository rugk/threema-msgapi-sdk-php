<?php
require_once("Salt/autoload.php");

//define possibly missing constants
defined('CURL_SSLVERSION_DEFAULT')  || define('CURL_SSLVERSION_DEFAULT', 0);
defined('CURL_SSLVERSION_TLSv1')    || define('CURL_SSLVERSION_TLSv1', 1);
defined('CURL_SSLVERSION_TLSv1_1')  || define('CURL_SSLVERSION_TLSv1_1', 5);
defined('CURL_SSLVERSION_TLSv1_2')  || define('CURL_SSLVERSION_TLSv1_2', 6);

//define autoloader
$d = dirname(__FILE__);
spl_autoload_register(function($className) use($d)
{
	$className = ltrim($className, '\\');
	$fileName  = '';
	if ($lastNsPos = strrpos($className, '\\')) {
		$namespace = substr($className, 0, $lastNsPos);
		$className = substr($className, $lastNsPos + 1);
		$fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
	}
	$fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

	if(true === file_exists( $d.'/'.$fileName)) {
		require $d.'/'.$fileName;
	}
});

$sdkVersion = '1.1.7';
define('MSGAPI_SDK_VERSION', $sdkVersion);
$cryptTool = Threema\MsgApi\Tools\CryptTool::getInstance();

if(null === $cryptTool) {
	throw new \Threema\Core\Exception("no supported crypt-tool");
}

//run validate
$cryptTool->validate();
