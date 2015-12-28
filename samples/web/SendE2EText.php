<?php
/**
 * @author rugk
 * @copyright Copyright (c) 2015 rugk
 * @license MIT
 */

header('Content-Type: text/plain');

//include SDK
require_once '../../source/bootstrap.php'; //use source
// require_once '../../threema_msgapi.phar'; //use phar

//include credentials
require_once 'GlobalConstants.php';
require_once FILENAME_CONNCRED;
require_once FILENAME_PRIVKEY;

//include web files used
require_once 'include/Connection.php';
require_once 'include/PublicKey.php';

/**
 * Send an end-to-end encrypted message to a specific Threema ID.
 *
 * Undocumented function long description
 *
 * @param Connection $connector connector
 * @param string $receiverId The id the message should be sent to
 * @param string $message The message which should be send
 */
function SendText($connector, $receiverId, $message)
{
    //get private key
    $privateKey = KeyHexToBin(MSGAPI_PRIVATE_KEY);

    //send message
    $e2eHelper = new \Threema\MsgApi\Helpers\E2EHelper($privateKey, $connector);
    $result = $e2eHelper->sendTextMessage($receiverId, $message);

    //show result
    if(true === $result->isSuccess()) {
    	echo 'Message ID: '.$result->getMessageId() . "\n";
    } else {
    	echo 'Error: '.$result->getErrorMessage() . "\n";
    }

}

//create connection
$connector = CreateConnection();

SendText($connector, MSGAPI_DEFAULTRECEIVER, 'test');
