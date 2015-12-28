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
require_once 'include/GlobalConstants.php';
require_once FILENAME_CONNCRED;
require_once FILENAME_PRIVKEY;

//include web files used
require_once 'include/GlobalConstants.php';
require_once 'include/Connection.php';
require_once 'include/PublicKey.php';
require_once 'include/GetPost.php';

/**
 * Fetches the public key of an ID from the Threema server
 *
 * @param Connection $connector connector
 * @param string $threemaId The id whose public key should be fetched
 *
 * @return string|Exception
 */
function FetchPublicKey($connector, $threemaId)
{
    $result = $connector->fetchPublicKey($threemaId);
    if($result->isSuccess()) {
    	return $result->getPublicKey();
    }
    else {
    	throw new Exception($result->getErrorMessage());
    }
}

//get params
$threemaId = null;
if (ReturnGetPost('threemaid') &&
    preg_match('/' . REGEXP_THREEMAID_ANY . '/', ReturnGetPost('threemaid'))
) {
    $threemaId = htmlentities(ReturnGetPost('threemaid'));
}

//create connection
$connector = CreateConnection();

//Fetch public key and return a 500 error in case of a failure
if ($threemaId != null) {
    try {
        $publicKey = FetchPublicKey($connector, $threemaId);
        echo $publicKey;
    }
    catch (Exception $e) {
        http_response_code(500);
        echo $e->getMessage();
    }
} else {
    http_response_code(500);
    echo 'Invalid Threema ID';
}
