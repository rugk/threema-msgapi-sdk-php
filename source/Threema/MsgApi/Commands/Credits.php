<?php
/**
 * @author Threema GmbH
 * @copyright Copyright (c) 2015-2016 Threema GmbH
 */


namespace Threema\MsgApi\Commands;

use Threema\MsgApi\Commands\CommandInterface;
use Threema\MsgApi\Commands\Results\CreditsResult;

class Credits implements CommandInterface {
	/**
	 * @return array
	 */
	public function getParams() {
		return array();
	}

	public function getPath() {
		return 'credits';
	}

	/**
	 * @param int $httpCode
	 * @param object $res
	 * @return CreditsResult
	 */
	public function parseResult($httpCode, $res){
		return new CreditsResult($httpCode, $res);
	}
}
