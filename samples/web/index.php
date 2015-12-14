<?php
/**
 * @author Threema GmbH
 * @copyright Copyright (c) 2015 Threema GmbH
 */

/* INCLUDES */
require_once 'GlobalConstants.php';
require_once 'ConvertKey.php';

/* SOME SMALL FUNCTIONS */
function ShowDefaultReceiverId($addOptionsHtmlCode = false)
{
    if (MSGAPI_DEFAULTRECEIVER <> '') {
        if ($addOptionsHtmlCode) {
            echo '<option value="';
        }

        echo MSGAPI_DEFAULTRECEIVER;

        if ($addOptionsHtmlCode) {
            echo '">';
        }
    }
}

/* CHECK PREREQUISITES */
$fileConnCredentErr = '';
if (!file_exists(FILENAME_CONNCRED)) {
    $fileConnCredentErr .= ' The file does not exist.';
} else {
    require_once FILENAME_CONNCRED;
    if (!defined('MSGAPI_GATEWAY_THREEMA_ID') ||
        !defined('MSGAPI_GATEWAY_THREEMA_ID_SECRET')
    ) {
        $fileConnCredentErr .= ' Not all required constants are defined.';
    } else {
        if (MSGAPI_GATEWAY_THREEMA_ID == '' ||
            !preg_match('/' . REGEXP_THREEMAID_GATEWAY . '/', MSGAPI_GATEWAY_THREEMA_ID)
        ) {
            $fileConnCredentErr .= ' \'MSGAPI_GATEWAY_THREEMA_ID\' is invalid.';
        }

        if (MSGAPI_GATEWAY_THREEMA_ID_SECRET == '' ||
            !ctype_alnum(MSGAPI_GATEWAY_THREEMA_ID_SECRET)
        ) {
            $fileConnCredentErr .= ' \'MSGAPI_GATEWAY_THREEMA_ID_SECRET\' is invalid.';
        }

        // MSGAPI_DEFAULTRECEIVER is optional
        if (!defined('MSGAPI_DEFAULTRECEIVER')) {
            define('MSGAPI_DEFAULTRECEIVER', '');
        }

        if (MSGAPI_DEFAULTRECEIVER <> '' &&
            !preg_match('/' . REGEXP_THREEMAID_ANY . '/', MSGAPI_DEFAULTRECEIVER)
        ) {
            $fileConnCredentErr .= ' \'MSGAPI_DEFAULTRECEIVER\' is invalid.';
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
            Before you can use this test, you have to get credentials at <a href="https://gateway.threema.ch" title="Threema Gateway">gateway.threema.ch</a> and <a href="https://github.com/rugk/threema-msgapi-sdk-php/wiki/How-to-generate-a-new-key-pair-and-send-a-message">create a key pair</a>. After you did so, you have to open <code><?php echo FILENAME_CONNCRED . FILEEXT_EXAMPLE ?></code> and <code><?php echo FILENAME_PRIVKEY . FILEEXT_EXAMPLE ?></code> and add your credentials and private key. Save them without the <code><?php echo FILEEXT_EXAMPLE ?></code> file extension afterwards.
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

        <!-- Sending UI -->
        <h2 id="test">Test</h2>
        <?php if ($fileConnCredentErr <> '' || $fileChkPrivateKeyErr <> ''): ?>
            <div class="warning">
                You do not have prepared your setup correctly to use the test. Please follow the intructions above to setup your environment.
            </div>
        <?php else: ?>
            <form action="process.php" method="get">
                <div class="formcontainer">
                    <fieldset id="field_generalsettings">
                        <legend>General settings</legend>
                        <label for="SenderId">Sender: </label>
                        <input class="idInput" type="text" maxlength="8" name="SenderId" value="<?php echo MSGAPI_GATEWAY_THREEMA_ID ?>" placeholder="*THREEMA" disabled="" pattern="<?php echo REGEXP_THREEMAID_GATEWAY ?>"><br />
                        <div class="publickeynote">
                            public key: 4a6a1b34dcef15d43cb74de2fd36091be99fbbaf126d099d47d83d919712c72b
                        </div>
                        <label for="receiverId">Receiver: </label>
                        <input class="idInput" type="text" list="cachedRecieverIds" maxlength="8" name="RecieverIds" value="<?php ShowDefaultReceiverId(); ?>" placeholder="ECHOECHO" required="" pattern="<?php echo REGEXP_THREEMAID_ANY ?>"><br />
                        <datalist id="cachedRecieverIds">
                            <?php ShowDefaultReceiverId(true); ?>
                            <option value="ECHOECHO">
                        </datalist>
                        <div class="publickeynote">
                            public key: 4a6a1b34dcef15d43cb74de2fd36091be99fbbaf126d099d47d83d919712c72b
                        </div>
                    </fieldset>
                    <fieldset id="field_message">
                        <legend>Message</legend>
                        <textarea id="messageinput" type="text" id="messageedit" name="message" maxlength="3500" wrap="soft" value="" required="" autofocus=""></textarea>
                    </fieldset><br />
                </div>
            <input type="submit" value="Send">
        </form>
        <?php endif ?>
    </body>
</html>
