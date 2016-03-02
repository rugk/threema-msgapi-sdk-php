<?php

use Threema\MsgApi\Connection;
use Threema\MsgApi\ConnectionSettings;

//include_project
require_once 'bootstrap.php';

//define your connection settings
$settings = new ConnectionSettings(
	'*YOUR_GATEWAY_THREEMA_ID',
	'YOUR_GATEWAY_THREEMA_ID_SECRET'
);

//public key store file
//best practice: create a publickeystore
//Threema\MsgApi\PublicKeyStores\PhpFile::create('keystore.php'); //new keystore
//$publicKeyStore = new Threema\MsgApi\PublicKeyStores\PhpFile('keystore.php'); //reuse keystore
$publicKeyStore = null;

//create a connection
$connector = new Connection($settings, $publicKeyStore);


$result = $connector->fetchPublicKey('ECHOECHO');
if($result->isSuccess()) {
	echo 'public key '.$result->getPublicKey() . "\n";
}
else {
	echo 'error '.$result->getErrorMessage() . "\n";
}
