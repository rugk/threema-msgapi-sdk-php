<?php
/**
 * @author Threema GmbH
 * @copyright Copyright (c) 2015 Threema GmbH
 */

header('Content-Type: text/plain');

//include SDK
require_once '../../source/bootstrap.php'; //use source
// require_once '../../threema_msgapi.phar'; //use phar

//include credentials
require_once 'ConnectionCredentials.php';
require_once 'PrivateKey.php';

//include web files used
require_once 'CreateConnection.php';
require_once 'ConvertKey.php';

function SendText($connector, $recieverID, $message)
{
    //get private key
    $privateKey = KeyHexToBin(MSGAPI_PRIVATE_KEY);

    //send message
    $e2eHelper = new \Threema\MsgApi\Helpers\E2EHelper($privateKey, $connector);
    $result = $e2eHelper->sendTextMessage($recieverID, $message);

    //show result
    if(true === $result->isSuccess()) {
    	echo 'Message ID: '.$result->getMessageId() . "\n";
    }
    else {
    	echo 'Error: '.$result->getErrorMessage() . "\n";
    }

}

// create connection
$connector = CreateConnection();

SendText($connector, MSGAPI_DEFAULTRECIEVER, 'test');
