# [Threema Gateway](https://gateway.threema.ch/) PHP SDK

Version: [1.1.7](https://github.com/rugk/threema-msgapi-sdk-php/releases/tag/1.1.7)

[![Code Climate](https://codeclimate.com/github/rugk/threema-msgapi-sdk-php/badges/gpa.svg)](https://codeclimate.com/github/rugk/threema-msgapi-sdk-php)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/rugk/threema-msgapi-sdk-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/rugk/threema-msgapi-sdk-php/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/b2d332ae-4100-42e0-abda-9cc96a79b18a/mini.png)](https://insight.sensiolabs.com/projects/b2d332ae-4100-42e0-abda-9cc96a79b18a)
[![Codacy Badge](https://api.codacy.com/project/badge/grade/28a86de2f74a421b98ecb2dddd4355f6)](https://www.codacy.com/app/rugk/threema-msgapi-sdk-php)  
All code analysers are configured to ignore `source/Salt/*` as this should not be part of the analyses.

## Notes about this version
This is a fork of the [original repo](https://github.com/threema-ch/threema-msgapi-sdk-php) after it was announced that it is no longer maintained by Threema.
As this is the community version of the Threema Gateway PHP SDK it may contain additional changes compared to the official one downloadable from the Threema website. If you are looking for an exact mirror of the downloadable Threema version you can switch to the branch [`official`](https://github.com/rugk/threema-msgapi-sdk-php/tree/official).  
More information are avaliable in [the wiki](https://github.com/rugk/threema-msgapi-sdk-php/wiki/Branches-and-releases).

An automatically created documentation of this SDK can be found [here](https://rugk.github.io/threema-msgapi-sdk-php/).

The contributors of this repository are not affiliated with Threema or the Threema GmbH.

## Installation
- Install PHP 5.4 or later: [https://secure.php.net/manual/en/install.php](https://secure.php.net/manual/en/install.php)
- For better encryption performance, install the [libsodium PHP extension](https://github.com/jedisct1/libsodium-php).

  This step is optional; if the libsodium PHP extension is not available, the SDK will automatically fall back to (slower) pure PHP code for ECC encryption (file and image sending not supported).

  A 64bit version of PHP is required for pure PHP encryption.

  To install the libsodium PHP extension:

  ```ShellSession
  pecl install libsodium
  ```

  Then add the following line to your php.ini file:

  ```ini
  extension=libsodium.so
  ```

If you want to check whether your server meets the requirements and everything is configured properly you can execute `threema-msgapi-tool.php` without any parameters on the console or point your browser to the location where it is saved on your server.

If you want to use this library in your own product it is recommend to use [Composer](https://getcomposer.org/) and require  [`rugk/threema-msgapi-sdk-php`](https://packagist.org/packages/rugk/threema-msgapi-sdk-php).

## SDK usage
### Creating a connection

```php
use Threema\MsgApi\Connection;
use Threema\MsgApi\ConnectionSettings;
use Threema\MsgApi\Receiver;

require_once('lib/bootstrap.php');

//define your connection settings
$settings = new ConnectionSettings(
    '*THREEMA',
    'THISISMYSECRET'
);

//simple php file to store the public keys (this file must already exist)
$publicKeyStore = new Threema\MsgApi\PublicKeyStores\PhpFile('/path/to/my/keystore.php');

//create a connection
$connector = new Connection($settings, $publicKeyStore);
```

### Creating a connection with advanced options
**Attention:** These settings change internal values of the TLS connection. Choosing wrong settings can weaken the TLS connection or prevent a successful connection to the server. Use them with care!

Each of the additional options shown below is optional. You can leave it out or use `null` to use the default value for this option.

```php
use Threema\MsgApi\Connection;
use Threema\MsgApi\ConnectionSettings;
use Threema\MsgApi\Receiver;

require_once('lib/bootstrap.php');

//define your connection settings
$settings = new ConnectionSettings(
    '*THREEMA',
    'THISISMYSECRET',
    null, //the host to be used, set to null to use the default (recommend)
    [
        'forceHttps' => true, //set to true to force HTTPS, default: false
        'tlsVersion' => '1.2', //set the version of TLS to be used, default: null
        'tlsCipher' => 'ECDHE-RSA-AES128-GCM-SHA256' //choose a cipher or a list of ciphers, default: null
    ]
);

//simple php file to store the public keys (this file must already exist)
$publicKeyStore = new Threema\MsgApi\PublicKeyStores\PhpFile('/path/to/my/keystore.php');

//create a connection
$connector = new Connection($settings, $publicKeyStore);
```

If you want to get a list of all ciphers you can use have a look at the [SSLLabs scan](https://www.ssllabs.com/ssltest/analyze.html?d=msgapi.threema.ch&latest), at the list of all available [OpenSSL ciphers](https://www.openssl.org/docs/manmaster/apps/ciphers.html) and the [comparison table by Mozilla](https://wiki.mozilla.org/Security/Server_Side_TLS#Cipher_names_correspondence_table) which also has some suggestions for good ciphers you should use.

### Sending a text message to a Threema ID (Simple Mode)

```php
//create the connection
//(...)
//create a receiver
$receiver = new Receiver('ABCD1234', Receiver::TYPE_ID);

$result = $connector->sendSimple($receiver, "This is a Test Message");
if($result->isSuccess()) {
    echo 'new id created '.$result->getMessageId();
}
else {
    echo 'error '.$result->getErrorMessage();
}
```

### Sending a text message to a Threema ID (E2E Mode)

```php
//create the connection
//(...)

$e2eHelper = new \Threema\MsgApi\Helpers\E2EHelper($senderPrivateKey,$connector);
$result = $e2eHelper->sendTextMessage("TEST1234", "This is an end-to-end encrypted message");

if(true === $result->isSuccess()) {
    echo 'Message ID: '.$result->getMessageId() . "\n";
}
else {
    echo 'Error: '.$result->getErrorMessage() . "\n";
}
```

### Sending a file message to a Threema ID (E2E Mode)

```php
//create the connection
//(...)

$senderPrivateKey = "MY_PUBLIC_KEY_IN_BIN";
$filePath = "/path/to/my/file.pdf";

$e2eHelper = new \Threema\MsgApi\Helpers\E2EHelper($senderPrivateKey,$connector);
$result = $e2eHelper->sendFileMessage("TEST1234", $filePath);

if(true === $result->isSuccess()) {
    echo 'File Message ID: '.$result->getMessageId() . "\n";
}
else {
    echo 'Error: '.$result->getErrorMessage() . "\n";
}
```

## Console client usage
### Local operations (no network communication)
#### Encrypt

```ShellSession
threema-msgapi-tool.php -e <privateKey> <publicKey>
```

Encrypt standard input using the given sender private key and recipient public key. Two lines to standard output: first the nonce (hex), and then the box (hex).

#### Decrypt

```ShellSession
threema-msgapi-tool.php -D <privateKey> <publicKey> <nonce>
```

Decrypt standard input using the given recipient private key and sender public key. The nonce must be given on the command line, and the box (hex) on standard input. Prints the decrypted message to standard output.

#### Hash Email Address

```ShellSession
threema-msgapi-tool.php -h -e <email>
```

Hash an email address for identity lookup. Prints the hash in hex.

#### Hash Phone Number

```ShellSession
threema-msgapi-tool.php -h -p <phoneNo>
```

Hash a phone number for identity lookup. Prints the hash in hex.

#### Generate Key Pair

```ShellSession
threema-msgapi-tool.php -g <privateKeyFile> <publicKeyFile>
```

Generate a new key pair and write the private and public keys to the respective files (in hex).

#### Derive Public Key

```ShellSession
threema-msgapi-tool.php -d <privateKey>
```

Derive the public key that corresponds with the given private key.

### Network operations
#### Send Simple Message

```ShellSession
threema-msgapi-tool.php -s <threemaId> <from> <secret>
```

Send a message from standard input with server-side encryption to the given ID. `<from>` is the API identity and `<secret>` is the API secret. the message ID on success.

#### Send End-to-End Encrypted Text Message

```ShellSession
threema-msgapi-tool.php -S <threemaId> <from> <secret> <privateKey>
```

Encrypt standard input and send the text message to the given ID. `<from>` is the API identity and `<secret>` is the API secret. Prints the message ID on success.

#### Send a End-to-End Encrypted Image Message

```ShellSession
threema-msgapi-tool.php -S -i <threemaId> <from> <secret> <privateKey> <imageFile>
```

Encrypt the image file and send the message to the given ID. `<from>` is the API identity and `<secret>` is the API secret. Prints the message ID on success.

#### Send a End-to-End Encrypted File Message

```ShellSession
threema-msgapi-tool.php -S -f <threemaId> <from> <secret> <privateKey> <file> <thumbnailFile>
```

Encrypt the file (and thumbnail if given) and send the message to the given ID. `<from>` is the API identity and `<secret>` is the API secret. Prints the message ID on success.

#### ID-Lookup By Email Address

```ShellSession
threema-msgapi-tool.php -l -e <email> <from> <secret>
```

Lookup the ID linked to the given email address (will be hashed locally).

#### ID-Lookup By Phone Number

```ShellSession
threema-msgapi-tool.php -l -p <phoneNo> <from> <secret>
```

Lookup the ID linked to the given phone number (will be hashed locally).

#### Fetch Public Key

```ShellSession
threema-msgapi-tool.php -l -k <threemaId> <from> <secret>
```

Lookup the public key for the given ID.

#### Fetch Capability

```ShellSession
threema-msgapi-tool.php -c <threemaId> <from> <secret>
```

Fetch the capabilities of a Threema ID.

#### Decrypt a Message and download the Files

```ShellSession
threema-msgapi-tool.php -r <threemaId> <from> <secret> <privateKey> <messageId> <nonce> <outputFolder>
```

Decrypt a box (must be provided on stdin) message and download (if the message is an image or file message) the file(s) to the given `<outputFolder>` folder.

#### Remaining credits

```ShellSession
threema-msgapi-tool.php -C <from> <secret>
```

Fetch remaining credits.

## Contributing
Nice to see you want to contribute. We may periodically send patches to Threema to make it possible for them to implement them in the official SDK version.  
You can find more information [in our wiki](https://github.com/rugk/threema-msgapi-sdk-php/wiki/Contributing).

## Other platforms (Java and Python)
All repositories on GitHub are no longer maintained by the Threema GmbH. However, the community has forked the repositories of all platforms and they are now maintained unofficially.

You can find the Java repository at [simmac/threema-msgapi-sdk-java](https://github.com/simmac/threema-msgapi-sdk-java)  
and the Python repository at [lgrahl/threema-msgapi-sdk-python](https://github.com/lgrahl/threema-msgapi-sdk-python).
