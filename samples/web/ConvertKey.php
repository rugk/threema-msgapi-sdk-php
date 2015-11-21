<?php
/**
 * @author Threema GmbH
 * @copyright Copyright (c) 2015 Threema GmbH
 */

/**
 * Returns the short user-friendly hash of the public key.
 *
 * This is the way the key is also displayed in the Threema app. For example
 * there ECHOECHO is shown as `d30f795a904a213578baecc62c8611b5`. However the
 * full public key of it is:
 * `4a6a1b34dcef15d43cb74de2fd36091be99fbbaf126d099d47d83d919712c72b`
 *
 * @param type var Description
 *
 * @return type string 32 hex characters
 **/
function KeyGetUserDisplay($publicKey)
{
    //force key to be binary
    if (ctype_alnum($publicKey)) {
        $publicKey = KeyHexToBin($publicKey);
    }

    //create short hash
    $shortHash = substr(hash('sha256', $publicKey, 0, 32));
    return $shortHash;
}


/**
 * undocumented function summary
 *
 * Undocumented function long description
 *
 * @param type var Description
 **/
function KeyHexToBin($keyHex)
{
    //delete prefix
    $keyTypeCheck = substr($keyHex, 0, 8);
    if ($keyTypeCheck == 'private:' || $keyTypeCheck == 'public:') {
        $keyHex = substr($keyHex, 8);
    }

    //convert key
    $keyBin = hex2bin($keyHex);
    return $keyBin;
}
