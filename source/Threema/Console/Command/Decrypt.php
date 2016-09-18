<?php
/**
 * @author Threema GmbH
 * @copyright Copyright (c) 2015-2016 Threema GmbH
 */


namespace Threema\Console\Command;

use Threema\Console\Common;
use Threema\MsgApi\Tools\CryptTool;

class Decrypt extends Base {
	public function __construct() {
		parent::__construct('Decrypt',
			array(self::argPrivateKey, self::argPublicKey, self::argNonce),
			'Decrypt standard input using the given recipient private key and sender public key. The nonce must be given on the command line, and the box (hex) on standard input. Prints the decrypted message to standard output.');
	}

	protected function doRun() {
		$cryptTool = CryptTool::getInstance();

		$privateKey = $this->getArgumentPrivateKey(self::argPrivateKey);
		$publicKey = $this->getArgumentPublicKey(self::argPublicKey);
		$nonce = $cryptTool->hex2bin($this->getArgument(self::argNonce));
		$input = $cryptTool->hex2bin($this->readStdIn());

		Common::required($privateKey, $publicKey, $nonce, $input);
		$cryptTool = CryptTool::getInstance();
		$message = $cryptTool->decryptMessage($input, $privateKey, $publicKey, $nonce);

		Common::l((String)$message);
	}
}
