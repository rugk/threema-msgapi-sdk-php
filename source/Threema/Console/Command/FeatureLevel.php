<?php
/**
 * @author Threema GmbH
 * @copyright Copyright (c) 2015-2016 Threema GmbH
 */

namespace Threema\Console\Command;

use Threema\Console\Common;
use Threema\MsgApi\Tools\CryptTool;

class FeatureLevel extends Base {
	/**
	 * @param PublicKeyStore $publicKeyStore
	 */
	public function __construct() {
		parent::__construct('Show current feature level',
			[],
			'Show current version and feature level');
	}

	protected function doRun() {
		$cryptTool = CryptTool::getInstance();

		Common::l('Version: '.MSGAPI_SDK_VERSION);
		Common::l('Feature level: '.MSGAPI_SDK_FEATURE_LEVEL);
		Common::l('CryptTool: '.$cryptTool->getName().' ('.$cryptTool->getDescription().')');

		Common::l(' ╔═══════╤══════╤══════════════╤═══════╤══════╤═════════╗');
		Common::l(' ║ Level │ Text │ Capabilities │ Image │ File │ Credits ║');
		Common::l(' ╟───────┼──────┼──────────────┼───────┼──────┼─────────╢');
		Common::l(' ║ 1     │ X    │              │       │      │         ║');
		Common::l(' ║ 2     │ X    │ X            │ X     │ X    │         ║');
		Common::l("\033[1;32m\033[40m▶".'║ 3     │ X    │ X            │ X     │ X    │ X       ║'."\033[0m");
		Common::l(' ╚═══════╧══════╧══════════════╧═══════╧══════╧═════════╝');
	}
}
