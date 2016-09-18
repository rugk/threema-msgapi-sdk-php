<?php
/**
 * @author Threema GmbH
 * @copyright Copyright (c) 2015-2016 Threema GmbH
 */


namespace Threema\MsgApi\Commands;

use Threema\MsgApi\Commands\CommandInterface;
use Threema\MsgApi\Commands\Results\LookupIdResult;
use Threema\MsgApi\Tools\CryptTool;

class LookupPhone implements CommandInterface {
	/**
	 * @var string
	 */
	private $phoneNumber;

	/**
	 * @param string $phoneNumber
	 */
	public function __construct($phoneNumber) {
		$this->phoneNumber = $phoneNumber;
	}

	/**
	 * @return string
	 */
	public function getPhoneNumber() {
		return $this->phoneNumber;
	}

	/**
	 * @return array
	 */
	public function getParams() {
		return array();
	}

	/**
	 * @return string
	 */
	public function getPath() {
		return 'lookup/phone_hash/'.urlencode(CryptTool::getInstance()->hashPhoneNo($this->phoneNumber));
	}

	/**
	 * @param int $httpCode
	 * @param object $res
	 * @return LookupIdResult
	 */
	public function parseResult($httpCode, $res){
		return new LookupIdResult($httpCode, $res);
	}
}
