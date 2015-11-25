<?php
/**
 * @author Threema GmbH
 * @copyright Copyright (c) 2015 Threema GmbH
 */

/* INCLUDES */
require_once 'ConvertKey.php';

/* CONSTANTS */
const FILENAME_CONNCRED = 'ConnectionCredentials.php';
const FILENAME_PRIVKEY = 'PrivateKey.php';
const FILEEXT_EXAMPLE = '.example';

/* CHECK PREREQUISITES */
$fileConnCredentErr = '';
if (!file_exists(FILENAME_CONNCRED)) {
    $fileConnCredentErr .= ' The file does not exist.';
} else {
    require_once FILENAME_CONNCRED;
    if (!defined('MSGAPI_GATEWAY_THREEMA_ID') ||
        !defined('MSGAPI_GATEWAY_THREEMA_ID_SECRET') ||
        !defined('MSGAPI_DEFAULTRECIEVER')
    ) {
        $fileConnCredentErr .= ' Not all constants are defined.';
    } else {
        // RegExp of Threema Gateway ID: https://regex101.com/r/fF9hQ0/2
        if (MSGAPI_GATEWAY_THREEMA_ID == '' ||
            !preg_match('/^\h*\*[[:alnum:]]{7}\h*/', MSGAPI_GATEWAY_THREEMA_ID)
        ) {
            $fileConnCredentErr .= ' \'MSGAPI_GATEWAY_THREEMA_ID\' is invalid.';
        }

        if (MSGAPI_GATEWAY_THREEMA_ID_SECRET == '' ||
            !ctype_alnum(MSGAPI_GATEWAY_THREEMA_ID_SECRET)
        ) {
            $fileConnCredentErr .= ' \'MSGAPI_GATEWAY_THREEMA_ID_SECRET\' is invalid.';
        }

        // RegExp of Threema ID: https://regex101.com/r/bF6xV5/5
        if (MSGAPI_DEFAULTRECIEVER == '' ||
            !preg_match('/^\h*((\*[[:alnum:]]{7})|([[:alnum:]]{8}))\h*$/', MSGAPI_DEFAULTRECIEVER)
        ) {
            $fileConnCredentErr .= ' \'MSGAPI_DEFAULTRECIEVER\' is invalid.';
        }
    }
}


$fileChkPrivateKeyErr = '';
if (!file_exists(FILENAME_PRIVKEY)) {
    $fileChkPrivateKeyErr .= ' The file does not exist.';
} else {
    require_once FILENAME_PRIVKEY;
    if (!defined('MSGAPI_PRIVATE_KEY')) {
        $fileChkPrivateKeyErr .= ' Not all constants are defined.';
    } else {
        if (MSGAPI_PRIVATE_KEY == '' ||
            !KeyCheck(MSGAPI_PRIVATE_KEY, 'private:')
        ) {
            $fileChkPrivateKeyErr .= ' \'MSGAPI_PRIVATE_KEY\' is invalid.';
        }
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Development UI - MSGAPI-PHP-SDK - Threema Gateway</title>
        <link rel="stylesheet" type="text/css" href="assets/css/common.css" charset="utf-8">
    </head>
    <body>
        <?php
        // only shows content when it is not parsed by a PHP interpreter
        if (false):
        ?>
        <!-- PHP parsing error message -->
        <div class="warning">
            You are viewing this file within a browser. However you do need to call this file
            through PHP to view it correctly. To do this please setup a local server with
            PHP support and access the file like this: <code>http://127.0.0.1/threema-msgapi-sdk-php/samples/web/</code>.
        </div>
        <?php
        endif
        ?>

        <h1 id="devui">Development UI - MSGAPI-PHP-SDK - Threema Gateway</h1>
        <p>
            This is a development UI for the <a href="https://github.com/rugk/threema-msgapi-sdk-php" title="Threema Gateway PHP SDK">Threema MSGAPI PHP-SDK</a>.
            Here you can test sending of different messages.
        </p>
        <h2 id="prerequisites">Prerequisites</h2>
        <?php if ($fileConnCredentErr == '' && $fileChkPrivateKeyErr == ''): ?>
            <!-- files already exist - no need to show instructions -->
        <?php else: ?>
        <p>
            Before you can use this test, you have to get credentials at <a href="https://gateway.threema.ch" title="Threema Gateway">gateway.threema.ch</a> and <a href="https://github.com/rugk/threema-msgapi-sdk-php/wiki/How-to-generate-a-new-key-pair-and-send-a-message">create a key pair</a>. After you did so you have to open <code><?php echo FILENAME_CONNCRED . FILEEXT_EXAMPLE ?></code> and <code><?php echo FILENAME_PRIVKEY . FILEEXT_EXAMPLE ?></code> and add your credentials and private key. Save them without the <code><?php echo FILEEXT_EXAMPLE ?></code> file extension afterwards.
            By default these files are excluded from git pulls so you will not accidentally publish these sensitive files.
        </p>
        <?php endif ?>

        <!-- Show graphical indicator -->
        <div class="graphprepcheck">
            <?php if ($fileConnCredentErr == ''): ?>
            <div class="filecheck">
                <img class="graphicon" src="assets/img/tick.svg" alt="tick" />
                <span class="filecheckdesc">
                    <code><?php echo FILENAME_CONNCRED ?></code> was correctly created.
                </span>
            </div>
            <?php else: ?>
            <div class="filecheck">
                <img class="graphicon" src="assets/img/cross.svg" alt="tick" />
                <span class="filecheckdesc">
                    <code><?php echo FILENAME_CONNCRED ?></code> was not correctly created.<?php echo $fileConnCredentErr ?>
                </span>
            </div>
            <?php endif ?>

            <?php if ($fileChkPrivateKeyErr == ''): ?>
            <div class="filecheck">
                <img class="graphicon" src="assets/img/tick.svg" alt="tick" />
                <span class="filecheckdesc">
                    <code><?php echo FILENAME_PRIVKEY ?></code> was correctly created.
                </span>
            </div>
            <?php else: ?>
            <div class="filecheck">
                <img class="graphicon" src="assets/img/cross.svg" alt="tick" />
                <span class="filecheckdesc">
                    <code><?php echo FILENAME_PRIVKEY ?></code> was not correctly created.<?php echo $fileChkPrivateKeyErr ?>
                </span>
            </div>
            <?php endif ?>
        </div>
        <h2 id="test">Test</h2>
        <?php if ($fileConnCredentErr <> '' || $fileChkPrivateKeyErr <> ''): ?>
            <div class="warning">
                You do not have prepared your setup correctly to use the test. Please follow the intructions above to setup your envoriment.
            </div>
        <?php else: ?>
        <?php endif ?>
        <!-- TODO: add GUI -->
    </body>
</html>
