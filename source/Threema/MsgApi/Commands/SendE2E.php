<?php
/**
 * @author Threema GmbH
 * @copyright Copyright (c) 2015-2016 Threema GmbH
 */


namespace Threema\MsgApi\Commands;

use Threema\MsgApi\Commands\CommandInterface;
use Threema\MsgApi\Commands\Results\SendE2EResult;
use Threema\MsgApi\Tools\CryptTool;

class SendE2E implements CommandInterface {
	/**
	 * @var string
	 */
	private $nonce;

	/**
	 * @var string
	 */
	private $box;

	/**
	 * @var string
	 */
	private $threemaId;

	/**
	 * @param string $threemaId
	 * @param string $nonce
	 * @param string $box
	 */
	public function __construct($threemaId, $nonce, $box) {
		$this->nonce = $nonce;
		$this->box = $box;
		$this->threemaId = $threemaId;
	}

	/**
	 * @return string
	 */
	public function getNonce() {
		return $this->nonce;
	}

	/**
	 * @return string
	 */
	public function getBox() {
		return $this->box;
	}

	/**
	 * @return array
	 */
	public function getParams() {
		$cryptTool = CryptTool::getInstance();

		$p['to'] = $this->threemaId;
		$p['nonce'] = $cryptTool->bin2hex($this->getNonce());
		$p['box'] = $cryptTool->bin2hex($this->getBox());
		return $p;
	}

	/**
	 * @return string
	 */
	public function getPath() {
		return 'send_e2e';
	}

	/**
	 * @param int $httpCode
	 * @param object $res
	 * @return SendE2EResult
	 */
	public function parseResult($httpCode, $res){
		return new SendE2EResult($httpCode, $res);
	}
}
