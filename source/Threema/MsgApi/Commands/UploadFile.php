<?php
/**
 * @author Threema GmbH
 * @copyright Copyright (c) 2015-2016 Threema GmbH
 */


namespace Threema\MsgApi\Commands;

use Threema\MsgApi\Commands\MultiPartCommandInterface;
use Threema\MsgApi\Commands\Results\UploadFileResult;

class UploadFile implements MultiPartCommandInterface {
	/**
	 * @var string
	 */
	private $encryptedFileData;

	/**
	 * @param string $encryptedFileData (binary) the encrypted file data
	 */
	public function __construct($encryptedFileData) {
		$this->encryptedFileData = $encryptedFileData;
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
		return 'upload_blob';
	}

	/**
	 * @return string
	 */
	public function getData() {
		return $this->encryptedFileData;
	}

	/**
	 * @param int $httpCode
	 * @param object $res
	 * @return UploadFileResult
	 */
	public function parseResult($httpCode, $res){
		return new UploadFileResult($httpCode, $res);
	}
}
