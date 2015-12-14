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
require_once 'GlobalConstants.php';
require_once 'CreateConnection.php';
require_once 'ConvertKey.php';

/**
 * undocumented function summary
 *
 * Undocumented function long description
 *
 * @param Connection $connector connector
 * @param string $threemaID The id whose public key should be fetched
 *
 * @return string|Exception
 **/
function FetchPublicKey($connector, $threemaID)
{
    $result = $connector->fetchPublicKey($threemaID);
    if($result->isSuccess()) {
    	return $result->getPublicKey();
    }
    else {
    	throw new Exception($result->getErrorMessage());
    }
}

/**
 * Check whether the given string is a valid Threema ID
 *
 * Note: This does not confirm that the ID exists. It only checks the syntax of
 * the ID.
 *
 * @param string $threemaID The id which shoukd be checked
 *
 * @return boolean
 **/
function IsValidPublicKey($threemaID)
{
    return preg_match('/' . REGEXP_THREEMAID_ANY . '/', $_GET['threemaid']);
}

//get params
$threemaID = null;
if (isset($_GET['threemaid']) && IsValidPublicKey($_GET['threemaid'])) {
        $threemaID = $_GET['threemaid'];
}

//create connection
$connector = CreateConnection();

//Fetch public key and return a 500 error in case of a failure
if ($threemaID != null) {
    try {
        $publicKey = FetchPublicKey($connector, $threemaID);
        echo $publicKey.'x';
    }
    catch (Exception $e) {
        http_response_code(500);
        echo  $e->getMessage();
    }
}
