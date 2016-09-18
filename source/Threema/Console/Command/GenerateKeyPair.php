<?php
/**
 * @author Threema GmbH
 * @copyright Copyright (c) 2015-2016 Threema GmbH
 */


namespace Threema\Console\Command;

use Threema\Console\Common;

class GenerateKeyPair extends Base {
	public function __construct() {
		parent::__construct('Generate Key Pair',
			array(self::argPrivateKeyFile, self::argPublicKeyFile),
			'Generate a new key pair and write the private and public keys to the respective files (in hex).');
	}

	protected function doRun() {
		$cryptTool = CryptTool::getInstance();
		$keyPair = $cryptTool->generateKeyPair();

		$privateKeyHex = $cryptTool->bin2hex($keyPair->privateKey);
		$publicKeyHex = $cryptTool->bin2hex($keyPair->publicKey);

		file_put_contents($this->getArgument(self::argPrivateKeyFile), Common::convertPrivateKey($privateKeyHex)."\n");
		file_put_contents($this->getArgument(self::argPublicKeyFile), Common::convertPublicKey($publicKeyHex)."\n");

		Common::l('key pair generated');
	}
}
