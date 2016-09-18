<?php
/**
 * @author Threema GmbH
 * @copyright Copyright (c) 2015-2016 Threema GmbH
 */


namespace Threema\MsgApi\Commands;

use Threema\MsgApi\Commands\Results\Result;

interface CommandInterface {
	/**
	 * @return string
	 */
	public function getPath();

	/**
	 * @return array
	 */
	public function getParams();

	/**
	 * @param int $httpCode
	 * @param object $res
	 * @return Result
	 */
	public function parseResult($httpCode, $res);
}
