<?php
/**
 * @author Threema GmbH
 * @copyright Copyright (c) 2015-2016 Threema GmbH
 */


namespace Threema\Console\Command;

use Threema\Console\Common;
use Threema\MsgApi\Tools\CryptTool;

class DerivePublicKey extends Base {
	function __construct() {
		parent::__construct('Derive Public Key',
			array(self::argPrivateKey),
			'Derive the public key that corresponds with the given private key.');
	}

	function doRun() {
		$privateKey = $this->getArgumentPrivateKey(self::argPrivateKey);

		Common::required($privateKey);

		$cryptTool = CryptTool::getInstance();

		$publicKey = $cryptTool->derivePublicKey($privateKey);
		Common::l(Common::convertPublicKey(\Sodium\bin2hex($publicKey)));
	}
}
