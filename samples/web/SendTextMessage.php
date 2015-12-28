<?php
/**
 * @author rugk
 * @copyright Copyright (c) 2015 rugk
 * @license MIT
 */

$isIncluded = count(get_included_files()) > 1;

//include SDK
require_once '../../source/bootstrap.php'; //use source
// require_once '../../threema_msgapi.phar'; //use phar

//include credentials
require_once 'include/GlobalConstants.php';
require_once FILENAME_CONNCRED;
require_once FILENAME_PRIVKEY;

//include web files used
require_once 'include/Connection.php';
require_once 'include/PublicKey.php';
require_once 'include/GetPost.php';

//parameters
$threemaId = null;
$message = null;
$messageId = null;
$errorMessage = null;

/**
 * Send a message to a Threema ID.
 *
 * @param Connection $connector connector
 * @param string $threemaId The id whose public key should be fetched
 * @param string $message The message to send (max 3500 characters)
 *
 * @return string|Exception
 */
function SendMessage($connector, $threemaId, $message)
{
    $e2eHelper = new \Threema\MsgApi\Helpers\E2EHelper(KeyHexToBin(MSGAPI_PRIVATE_KEY), $connector);
    $result = $e2eHelper->sendTextMessage($threemaId, $message);

    if (true === $result->isSuccess()) {
        return $result->getMessageId();
    } else {
        throw new Exception($result->getErrorMessage());
    }
}

//get params
if (ReturnGetPost('recieverid') &&
    preg_match('/' . REGEXP_THREEMAID_ANY . '/', ReturnGetPost('recieverid'))
) {
    $threemaId = htmlentities(ReturnGetPost('recieverid'));
    $message = ReturnGetPost('message');
}

//create connection
$connector = CreateConnection();

//Send message
if ($threemaId != null && $message != null) {
    if (!$isIncluded) {
        header('Content-Type: text/plain');
    }
    $actionDone = true;

    try {
        $messageId = SendMessage($connector, $threemaId, $message);
    } catch (Exception $e) {
        http_response_code(500);
        $errorMessage = $e->getMessage();
    }
}

//Show direct output if the file is called directly
if (!$isIncluded) {
    if ($errorMessage == null) {
        echo 'Message ID: ' . $messageId;
    } else {
        echo $errorMessage;
    }
}
